<?php

namespace App\Actions\Sil;

use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;
use App\DTOs\Sil\BusquedaExpedienteResult;
use App\DTOs\Sil\CatastroSearchResult;
use App\DTOs\Sil\ResolucionSearchResult;
use Illuminate\Support\Facades\Log;

class ProcesarBusquedaExpedienteAction
{
    public function __construct(
        protected CertificadoLincenciaFuncionamientoService $serviceCertificado,
        protected ObtenerDatosCatastroAction $catastroAction,
        protected ObtenerDatosResolucionAction $resolucionAction,
        protected ObtenerDatosPersonaAction $personaAction
    ) {
    }

    public function execute(string $numeroExpediente): BusquedaExpedienteResult
    {
        // 1. Obtener Expediente Base
        $datos = $this->serviceCertificado->obtenerDatosCompletosParaRegistrarPorExpediente($numeroExpediente);

        if (!$datos || !isset($datos['expediente'])) {
            return BusquedaExpedienteResult::notFound("No se encontró información para el expediente: $numeroExpediente");
        }

        // 2. Procesar Catastro
        $codcat = $datos['expediente']->ecc_codcat ?? null;
        $catastroResult = $this->catastroAction->execute($codcat);

        switch ($catastroResult->status) {
            case CatastroSearchResult::STATUS_NOT_FOUND:
                // Decisión de negocio: ¿Detenemos todo o seguimos? Asumiré que es crítico y detenemos.
                return BusquedaExpedienteResult::notFound("Código catastral no encontrado: $codcat");

            case CatastroSearchResult::STATUS_MULTIPLE:
                // Interrupción inmediata: Necesitamos que el usuario seleccione antes de seguir
                return BusquedaExpedienteResult::requireCatastroSelection($datos, $catastroResult->matches);

            case CatastroSearchResult::STATUS_FOUND:
                $datos['catastro'] = $catastroResult->data;
                break;
        }

        // 3. Procesar Resolución (Solo llegamos aquí si catastro fue único o no bloqueante)
        $resolucionResult = $this->resolucionAction->execute($numeroExpediente);

        switch ($resolucionResult->status) {
            case ResolucionSearchResult::STATUS_FOUND:
                $datos['resolucion'] = $resolucionResult->data;
                break;

            case ResolucionSearchResult::STATUS_NOT_FOUND:
                $datos['resolucion'] = null;
                break;

            case ResolucionSearchResult::STATUS_MULTIPLE_AREAS:
                // Aquí guardamos los datos base, pero marcamos que requerimos selección
                $datos['resolucion'] = $resolucionResult->data;
                return BusquedaExpedienteResult::requireResolucionSelection($datos, $resolucionResult->areaMatches);
        }

        // 4. Éxito Total
        return BusquedaExpedienteResult::success($datos);
    }
}