<?php

namespace App\Actions\Sil;

use App\DTOs\Sil\CatastroSearchResult;
use App\Services\Sil\Licencias\LicenciaService;
use Illuminate\Support\Facades\Log;
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

        // Lógica de decisión encapsulada aquí
        if ($collection->count() > 1) {
            $matches = $collection->map(fn($item) => (array) $item)->toArray();
            return CatastroSearchResult::multiple($matches);
        }

        // Caso único
        $singleData = (array) $collection->first();
        return CatastroSearchResult::found($singleData);
    }
}