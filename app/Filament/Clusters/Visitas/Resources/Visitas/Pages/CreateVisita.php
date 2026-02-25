<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Pages;

use App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource;
use App\Models\PersonaUno;
use App\Models\Visita;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateVisita extends CreateRecord
{
    protected static string $resource = VisitaResource::class;
    // ESTO ES LO IMPORTANTE: Redirige la inserción a la tabla física
    // protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    // {
    //     return Visita::create($data); 
    // }
    // protected function handleRecordCreation(array $data): Model
    // {
    //     // Forzamos la creación en la TABLA REAL
    //     return static::Visita::create($data);
    // }
    protected function handleRecordCreation(array $data): Model
    {
        // Forzamos que el INSERT vaya a la tabla física
        return Visita::create($data);
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->model(Visita::class)
                ->using(fn(array $data) => Visita::create($data)),
        ];
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $persona = PersonaUno::updateOrCreate(
            ['numero_documento' => $data['numero_documento']],
            [
                'tipo_documento_id' => $data['tipo_documento_id'],
                'nombres' => $data['nombres'],
                'apellido_paterno' => $data['apellido_paterno'],
                'apellido_materno' => $data['apellido_materno'],
                'foto_url' => $data['foto_url'] ?? null,
            ]
        );

        $data['persona_id'] = $persona->id;
        $data['fecha_ingreso'] = now();
        $data['user_id_ingreso'] = auth()->id();
        $data['sede_id'] = auth()->user()->sede_id;

        unset(
            $data['tipo_documento_id'],
            $data['numero_documento'],
            $data['nombres'],
            $data['trabajador_id'],
            $data['apellido_paterno'],
            $data['apellido_materno'],
            $data['foto_url'],
            $data['pide_fallo']
        );
        return $data;
    }
}
