<?php

namespace App\Actions\Sil;

use App\DTOs\Sil\CatastroSearchResult;
use App\Services\Sil\Licencias\LicenciaService;
class ObtenerDatosCatastroAction
{
    public function __construct(
        protected LicenciaService $licenciaService
    ) {
    }

    public function execute(?string $codigoCatastral): CatastroSearchResult
    {
        if (empty($codigoCatastral)) {
            return CatastroSearchResult::notFound();
        }

        // Llamada al servicio original
        $results = $this->licenciaService->obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codigoCatastral);

        // Normalización: asegurarnos de trabajar con una colección
        $collection = collect($results);

        if ($collection->isEmpty()) {
            return CatastroSearchResult::notFound();
        }

        // Si hay múltiples resultados, seleccionar automáticamente el fiu_id más reciente
        if ($collection->count() > 1) {
            /*
              $matches = $collection->map(fn($item) => (array) $item)->toArray();
            return CatastroSearchResult::multiple($matches);*/
            $mostRecent = $collection->sortByDesc('fiu_id')->first();
            $singleData = (array) $mostRecent;
            return CatastroSearchResult::found($singleData);
        }

        // Caso único
        $singleData = (array) $collection->first();
        return CatastroSearchResult::found($singleData);
    }
}