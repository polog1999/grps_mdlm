<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Pages;

use App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource;
use App\Models\PersonaUno;
use Filament\Resources\Pages\CreateRecord;

class CreateVisita extends CreateRecord
{
    protected static string $resource = VisitaResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
{
    $persona = PersonaUno::updateOrCreate(
        ['numero_documento' => $data['numero_documento']],
        [
            'tipo_documento_id' => $data['tipo_documento_id'],
            'nombres' => $data['nombres'],
            'apellido_paterno' => $data['apellido_paterno'],
            'apellido_materno' => $data['apellido_materno'],
        ]
    );

    $data['persona_id'] = $persona->id;
    $data['fecha_ingreso'] = now();
    $data['user_id_ingreso'] = auth()->id();

    unset($data['tipo_documento_id'], $data['numero_documento'], $data['nombres'], $data['trabajador_id'], $data['user_id_ingreso'],
              $data['apellido_paterno'], $data['apellido_materno'], $data['foto_url'], 
              $data['pide_fallo']);
    return $data;
}
}
