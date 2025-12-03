<?php

namespace App\Actions\Sil;

use App\DTOs\Sil\PersonaResult;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;
class ObtenerDatosPersonaAction
{
    public function __construct(
        protected CertificadoLincenciaFuncionamientoService $servicePersona
    ) {
    }

    public function execute(?string $nombrePersona): PersonaResult
    {
        if (empty($nombrePersona)) {
            return PersonaResult::notFound();
        }

        $results = $this->servicePersona->getIdPersonaPorNombre($nombrePersona);

        $collection = collect($results);

        if ($collection->isEmpty()) {
            return PersonaResult::notFound();
        }

        // Caso único
        $singleData = (array) $collection->first();
        return PersonaResult::found($singleData);
    }
}