<?php

namespace App\Actions\Sil;

use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;
use App\DTOs\Sil\BusquedaExpedienteResult;
use App\DTOs\Sil\CatastroSearchResult;
use App\DTOs\Sil\ResolucionSearchResult;
use App\Models\CertificadoInspeccion;

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
        // Regla 0: Riesgo ALTO o MUY ALTO - Buscar ITSEs disponibles (sin licencia) para el mismo expediente
        $codigosRiesgoAlto = ['P043', 'P044', 'P047', 'P048'];
        $proccodigo = $datos['nivel_riesgo']['proccodigo'] ?? null;
        $expedienteNum = $datos['expediente']->exp_num ?? null;

        if ($proccodigo && in_array($proccodigo, $codigosRiesgoAlto)) {
            // Buscar ITSEs sin licencia vinculada Y del mismo expediente
            $itses = CertificadoInspeccion::where(function ($query) {
                $query->whereNull('cin_licencia')
                    ->orWhere('cin_licencia', '');
            })
                ->where('cin_expediente', $expedienteNum)
                ->orderBy('cin_id', 'desc')
                ->limit(50)
                ->get()
                ->map(fn($itse) => $itse->toArray())
                ->toArray();

            if (empty($itses)) {
                return BusquedaExpedienteResult::notFound("Riesgo Alto/Muy Alto: No hay ITSEs disponibles para el expediente {$expedienteNum}. Primero debe registrar una ITSE para este expediente.");
            }

            return BusquedaExpedienteResult::requireItseSelection($datos, $itses);
        }

        // Regla 1: Catastro no encontrado -> Se permite continuar pero sin datos de catastro
        if ($catastroResult->status === CatastroSearchResult::STATUS_NOT_FOUND) {
            $codcat = $datos['expediente']->ecc_codcat ?? 'N/A';
            \Log::warning("BusquedaExpediente: Código catastral no encontrado ($codcat) para expediente {$datos['expediente']->exp_num}. Se continúa sin catastro.");
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