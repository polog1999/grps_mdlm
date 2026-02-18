<?php

namespace App\Filament\Clusters\Visitas\Resources\Trabajadors\Pages;

use App\Filament\Clusters\Visitas\Resources\Trabajadors\TrabajadorResource;
use App\Models\PersonaUno;
use Filament\Resources\Pages\CreateRecord;

class CreateTrabajador extends CreateRecord
{
    protected static string $resource = TrabajadorResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Buscamos o creamos la persona antes de crear el trabajador
        // Esto es necesario para obtener el persona_id
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

        // 2. Limpiar campos que no pertenecen a la tabla 'trabajadores'
        // para evitar el error SQL de "columna no existe"
        unset($data['tipo_documento_id'], $data['numero_documento'], $data['nombres'], 
              $data['apellido_paterno'], $data['apellido_materno'], $data['foto_url'], 
              $data['pide_fallo'], $data['cargos_activos']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $trabajador = $this->getRecord();
        // Obtenemos los datos "crudos" del formulario (incluyendo el repeater deshidratado)
        $data = $this->form->getRawState();
        $nuevosCargos = $data['cargos_activos'] ?? [];

        // Guardamos cada cargo del repeater en la tabla historial_cargos
        foreach ($nuevosCargos as $item) {
            $trabajador->historiales()->create([
                'cargo_id'     => $item['cargo_id'],
                'area_id'      => $item['area_id'],
                // 'sede_id'      => $item['sede_id'],
                'fecha_inicio' => $item['fecha_inicio'],
                'es_actual'    => true,
                'user_id_creo' => auth()->id(),
                'user_id_modi' => auth()->id(),
            ]);
        }
    }
}
