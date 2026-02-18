<?php

namespace App\Filament\Clusters\Visitas\Resources\Trabajadors\Pages;

use App\Filament\Clusters\Visitas\Resources\Trabajadors\TrabajadorResource;
use App\Models\PersonaUno;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrabajador extends EditRecord
{
    protected static string $resource = TrabajadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $trabajador = $this->getRecord();

        // 1. Cargar datos de Persona (Ya lo tienes bien)
        $persona = $trabajador->persona;
        if ($persona) {
            $data['tipo_documento_id'] = $persona->tipo_documento_id;
            $data['numero_documento']  = $persona->numero_documento;
            $data['nombres']           = $persona->nombres;
            $data['apellido_paterno']  = $persona->apellido_paterno;
            $data['apellido_materno']  = $persona->apellido_materno;
            $data['foto_url']          = $persona->foto_url;
        }

        // 2. Cargar TODOS los cargos que sean es_actual = true en el REPEATER
        $data['cargos_activos'] = $trabajador->historiales()
            ->where('es_actual', true)
            ->get()
            ->map(fn($h) => [
                'cargo_id'     => $h->cargo_id,
                'area_id'      => $h->area_id,
                'de_cargo'      => $h->de_cargo,
                // 'sede_id'      => $h->sede_id,
                'fecha_inicio' => $h->fecha_inicio,
            ])
            ->toArray();

        // Cargar el historial en el Repeater visual
        $data['historial_visual'] = $trabajador->historiales()
            ->with(['cargo', 'area'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($h) => [
                'cargo_nombre' => $h->cargo?->nombre,
                'area_nombre'  => $h->area?->nombre,
                'fecha_inicio' => $h->fecha_inicio,
            ])
            ->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $trabajador = $this->getRecord();

        // Actualizar Persona (Ya lo tienes bien)
        if ($trabajador->persona) {
            $trabajador->persona->update([
                'tipo_documento_id' => $data['tipo_documento_id'],
                'numero_documento'  => $data['numero_documento'],
                'nombres'           => $data['nombres'],
                'apellido_paterno'  => $data['apellido_paterno'],
                'apellido_materno'  => $data['apellido_materno'],
                'foto_url'          => $data['foto_url'] ?? $trabajador->persona->foto_url,
            ]);
        }

        // LIMPIEZA: Quitamos TODO lo que no sea de la tabla 'trabajadores'
        return array_diff_key($data, array_flip([
            'tipo_documento_id',
            'numero_documento',
            'nombres',
            'apellido_paterno',
            'apellido_materno',
            'foto_url',
            'pide_fallo',
            'cargos_activos'
        ]));
    }

    protected function afterSave(): void
    {
        $trabajador = $this->getRecord();
        $data = $this->form->getRawState();
        $nuevosCargos = $data['cargos_activos'] ?? [];

        // 1. Marcar como NO ACTUALES los cargos que ya no están en el repeater
        // (Opcional: Si quieres que al borrar del repeater se borre de la vigencia)
        $trabajador->historiales()->where('es_actual', true)->update(['es_actual' => false]);

        // 2. Insertar/Actualizar los cargos que vienen en el repeater
        foreach ($nuevosCargos as $item) {
            $trabajador->historiales()->create([
                'cargo_id'     => $item['cargo_id'],
                'area_id'      => $item['area_id'],
                'de_cargo'      => $item['de_cargo'],
                // 'sede_id'      => $item['sede_id'],
                'fecha_inicio' => $item['fecha_inicio'],
                'es_actual'    => true, // Vuelven a ser actuales
                'user_id_creo' => auth()->id(),
                'user_id_modi' => auth()->id(),
            ]);
        }
    }
}
