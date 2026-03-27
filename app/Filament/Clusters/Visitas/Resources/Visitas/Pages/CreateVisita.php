<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Pages;

use App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource;
use App\Models\PersonaUno;
use App\Models\Proveedor;
use App\Models\Visita;
use App\Models\VisitaTrabajadorRuc;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateVisita extends CreateRecord
{
    protected static string $resource = VisitaResource::class;
    protected ?string $heading = 'Registrar Visita';
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
            // CreateAction::make()
            //     // ->model(Visita::class)
            //     // ->using(fn(array $data) => Visita::create($data))
            //     ->label('Registrar Visita'),
        ];
    }
    public function getBreadcrumb(): string
    {
        return 'Registro';
    }
    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Registro de Visita';
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Manejo de la Persona Principal (Empresa o Visitante)
        if ($data['es_empresa'] == 0) {
            $persona = PersonaUno::updateOrCreate(
                [
                    'numero_documento' => $data['numero_documento'],
                    'tipo_documento_id' => $data['tipo_documento_id']
                ],
                [
                    'tipo_documento' => $data['tipo_documento'],
                    'nombres' => $data['nombres'],
                    'apellido_paterno' => $data['apellido_paterno'] ?? '',
                    'apellido_materno' => $data['apellido_materno'] ?? '',
                    'foto_url' => $data['foto_url'] ?? null,
                    'user_id_modi' => auth()->id(),
                    // Mantenemos el creador original si existe
                    'user_id_creo' => PersonaUno::where('numero_documento', $data['numero_documento'])
                        ->where('tipo_documento_id', $data['tipo_documento_id'])
                        ->value('user_id_creo') ?? auth()->id(),
                ]
            );
            $data['persona_id'] = $persona->id;
            $data['proveedor_id'] = null;
            $data['proveedor'] = null;
            $data['ruc'] = null;
        } else {
            $proveedor = Proveedor::firstOrCreate(
                ['ruc' => $data['numero_documento']],
                [
                    'nombres' => $data['nombres'],
                    'direccion' => $data['direccion']
                ]
            );
            $data['persona_id'] = null;
            $data['proveedor_id'] = $proveedor->id_proveedor;
            $data['proveedor'] = $proveedor->nombre;
            $data['ruc'] = $proveedor->ruc;
        }

        $data['fecha_ingreso'] = now();
        $data['user_id_ingreso'] = auth()->id();
        $data['sede_id'] = auth()->user()->sede_id;


        // 2. Inyectamos los IDs necesarios para la tabla 'visitas'



        // IMPORTANTE: NO borres 'lista_trabajadores' aquí, 
        // la necesitamos en el método afterCreate()
        unset(
            $data['tipo_documento'],
            $data['tipo_documento_id'],
            $data['numero_documento'],
            $data['pide_fallo'],
            $data['nombres'],
            $data['apellido_paterno'],
            $data['apellido_materno'],
            $data['foto_url'],
            $data['cargo'],
            $data['lista_trabajadores'] // <--- Esto es vital para que no intente insertar un array
        );

        return $data;
    }

    protected function afterCreate(): void
    {

        // 1. Obtenemos los datos que se enviaron en el formulario
        $dataForm = $this->data;

        // 2. Verificamos si la persona principal es RUC (ID 6)
        // Si NO es RUC, no hacemos nada y salimos de la función
        if (($dataForm['es_empresa'] ?? null) != 1) {
            return;
        }
        $data = $this->record->toArray();
        // Recuperamos la lista del estado del formulario, ya que mutate pudo haberla filtrado
        $listaTrabajadores = $this->data['lista_trabajadores'] ?? [];
        $personaId = $this->record->persona_id; // El ID de la empresa/persona principal

        foreach ($listaTrabajadores as $item) {
            $persona = PersonaUno::updateOrCreate(
                [
                    'tipo_documento' => $item['tipo_documento'],
                    'numero_documento' => $item['numero_documento'],
                    'tipo_documento_id' => $item['tipo_documento_id']
                ],
                [
                    'nombres' => $item['nombres'],
                    'apellido_paterno' => $item['apellido_paterno'] ?? '',
                    'apellido_materno' => $item['apellido_materno'] ?? '',
                    // 'cargo' => $item['cargo'] ?? '',
                    // 'dependencia_id' => $personaId,
                    'user_id_modi' => auth()->id(),
                    'user_id_creo' => PersonaUno::where('numero_documento', $item['numero_documento'])
                        ->where('tipo_documento_id', $item['tipo_documento_id'])
                        ->value('user_id_creo') ?? auth()->id(),
                ]
            );
            VisitaTrabajadorRuc::create([
                'visita_id' => $this->record->id,
                'persona_id' => $persona->id,
                'cargo' => $item['cargo']
            ]);
        }
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Registrar Visita'); // Cambia el texto aquí
    }
    protected function getCreateAnotherFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Registrar y registrar otro'); // <--- Pon aquí el nombre que prefieras
    }
}
