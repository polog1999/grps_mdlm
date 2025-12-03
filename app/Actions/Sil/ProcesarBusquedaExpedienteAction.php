<?php

namespace App\Actions\Sil;

use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;
use App\DTOs\Sil\BusquedaExpedienteResult;
use App\DTOs\Sil\CatastroSearchResult;
use App\DTOs\Sil\ResolucionSearchResult;

class ProcesarBusquedaExpedienteAction
{
    public function __construct(
        protected CertificadoLincenciaFuncionamientoService $serviceCertificado,
        protected ObtenerDatosCatastroAction $catastroAction,
        protected ObtenerDatosResolucionAction $resolucionAction
    ) {
    }

    public function execute(string $numeroExpediente): BusquedaExpedienteResult
    {
        // 1. Obtención de datos base
        $datos = $this->obtenerExpedienteBase($numeroExpediente);

        if (!$datos) {
            return BusquedaExpedienteResult::notFound("No se encontró información para el expediente: $numeroExpediente");
        }

        // 2. Ejecución de búsquedas externas (Catastro y Resolución)
        $catastroResult = $this->catastroAction->execute($datos['expediente']->ecc_codcat ?? null);
        $resolucionResult = $this->resolucionAction->execute($numeroExpediente);

        // 3. Enriquecimiento de datos (Solo si hubo éxito)
        $datos = $this->enriquecerDatos($datos, $catastroResult, $resolucionResult);

        // 4. Evaluación de reglas de negocio y retorno
        return $this->evaluarReglasDeNegocio($datos, $catastroResult, $resolucionResult);
    }

    private function obtenerExpedienteBase(string $numeroExpediente): ?array
    {
        $datos = $this->serviceCertificado->obtenerDatosCompletosParaRegistrarPorExpediente($numeroExpediente);
        return ($datos && isset($datos['expediente'])) ? $datos : null;
    }

    private function enriquecerDatos(array $datos, CatastroSearchResult $catastro, ResolucionSearchResult $resolucion): array
    {
        if ($catastro->status === CatastroSearchResult::STATUS_FOUND) {
            $datos['catastro'] = $catastro->data;
        }

        if ($resolucion->data) {
            $datos['resolucion'] = $resolucion->data;
        }

        return $datos;
    }

    private function evaluarReglasDeNegocio(array $datos, CatastroSearchResult $catastroResult, ResolucionSearchResult $resolucionResult): BusquedaExpedienteResult
    {
        // Regla 1: Error Crítico de Catastro
        if ($catastroResult->status === CatastroSearchResult::STATUS_NOT_FOUND) {
            $codcat = $datos['expediente']->ecc_codcat ?? 'N/A';
            return BusquedaExpedienteResult::notFound("Código catastral no encontrado: $codcat");
        }

        // Regla 2: Falta Persona (Retorna data parcial)
        if (empty($datos['expediente']->per_id)) {
            return BusquedaExpedienteResult::requirePersonaSearch($datos);
        }

        // Regla 3: Selección de Catastro Múltiple
        if ($catastroResult->status === CatastroSearchResult::STATUS_MULTIPLE) {
            return BusquedaExpedienteResult::requireCatastroSelection($datos, $catastroResult->matches);
        }

        // Regla 4: Selección de Resolución Múltiple
        if ($resolucionResult->status === ResolucionSearchResult::STATUS_MULTIPLE_AREAS) {
            return BusquedaExpedienteResult::requireResolucionSelection($datos, $resolucionResult->areaMatches);
        }

        // Éxito
        return BusquedaExpedienteResult::success($datos);
    }
}