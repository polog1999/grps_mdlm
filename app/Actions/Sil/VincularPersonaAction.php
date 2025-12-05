<?php

namespace App\Actions\Sil;

use App\Models\Persona;

class VincularPersonaAction
{
    public function execute(array $datosActuales, string $personaId): array
    {
        $persona = Persona::find($personaId);

        if (!$persona) {
            return $datosActuales;
        }

        $expediente = $datosActuales['expediente'] ?? [];
        // Convertir expediente a array si es un objeto
        if (is_object($expediente)) {
            $expediente = (array) $expediente;
        }


        $expediente['per_id'] = $persona->per_id;
        $expediente['exp_nomrec_id'] = $persona->per_id; // Para selects de Livewire
        $expediente['exp_razsoc_id'] = $persona->per_id;

        $expediente['exp_nomrec'] = $persona->per_nombrerazonsocial;
        $expediente['exp_razsoc'] = $persona->per_nombrerazonsocial; // Asumimos mismo valor por defecto


        $datosActuales['expediente'] = $expediente;

        return $datosActuales;
    }
}