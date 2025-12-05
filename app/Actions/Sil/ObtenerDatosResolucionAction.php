<?php

namespace App\Actions\Sil;

use App\DTOs\Sil\ResolucionSearchResult;
use App\Services\Sil\CertificadoInspeccion\ResolucionService;
use App\Enums\AreaCodigo;
use Illuminate\Support\Facades\Log;

class ObtenerDatosResolucionAction
{
    public function __construct(
        protected ResolucionService $resolucionService
    ) {
    }

    public function execute(string $numeroExpediente): ResolucionSearchResult
    {
        try {
            // 1. Buscar Resolución por Expediente
            $resoluciones = $this->resolucionService->obtenerNumeroResolucionPorNumeroExpediente($numeroExpediente);

            if (!$resoluciones || $resoluciones->isEmpty()) {
                return ResolucionSearchResult::notFound();
            }

            $primeraResolucion = $resoluciones->first();
            $numeroResolucion = $primeraResolucion->numero_resolucion ?? '';

            // Datos base que siempre queremos devolver
            $datosBase = [
                'codigo_unico_tramite' => $numeroResolucion,
                'fecha_ingreso' => $primeraResolucion->fecha_ingreso ?? ''
            ];

            if (empty($numeroResolucion)) {
                return ResolucionSearchResult::found($datosBase);
            }

            // 2. Buscar Áreas asociadas a la Resolución
            $areasData = $this->resolucionService->obtenerResolucionMasAreaCompletaPorNumeroResolucion($numeroResolucion);

            if (!$areasData || $areasData->isEmpty()) {
                return ResolucionSearchResult::found($datosBase);
            }

            $areasFiltradas = $areasData->where('cdgo_area', AreaCodigo::SUBGERENCIA_PROMOCION_EMPRESARIAL->value);


            $cantidad = $areasFiltradas->count();

            Log::info("Áreas de resolución encontradas para {$numeroResolucion}: {$cantidad}");

            if ($cantidad > 1) {
                // Caso Múltiple: Devolvemos datos base + las opciones
                $matches = $areasFiltradas->map(fn($item) => (array) $item)->toArray();
                return ResolucionSearchResult::multipleAreas($datosBase, $matches);
            }

            // Caso Único: Agregamos el código único de trámite a los datos base
            if ($cantidad === 1) {
                $primeraArea = $areasFiltradas->first();
                $datosBase['codigo_unico_tramite'] = $primeraArea->codigo_unico_tramite ?? '';
            }

            return ResolucionSearchResult::found($datosBase);

        } catch (\Throwable $e) {
            Log::warning('Error en ObtenerDatosResolucionAction: ' . $e->getMessage());
            // En caso de error, preferimos "no encontrado" a romper la app (Fail Soft)
            return ResolucionSearchResult::notFound();
        }
    }
}