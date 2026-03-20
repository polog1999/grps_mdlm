<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Pages;

use App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource;
use App\Models\PersonaUno;
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
    $persona = PersonaUno::updateOrCreate(
        [
            'numero_documento' => $data['numero_documento'],
            'tipo_documento_id' => $data['tipo_documento_id']
        ],
        [
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

    // 2. Inyectamos los IDs necesarios para la tabla 'visitas'
    $data['persona_id'] = $persona->id;
    $data['fecha_ingreso'] = now();
    $data['user_id_ingreso'] = auth()->id();
    $data['sede_id'] = auth()->user()->sede_id;
    

    // IMPORTANTE: NO borres 'lista_trabajadores' aquí, 
    // la necesitamos en el método afterCreate()
      unset(
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
    $data = $this->record->toArray();
    // Recuperamos la lista del estado del formulario, ya que mutate pudo haberla filtrado
    $listaTrabajadores = $this->data['lista_trabajadores'] ?? [];
    $personaId = $this->record->persona_id; // El ID de la empresa/persona principal

    foreach ($listaTrabajadores as $item) {
        $persona = PersonaUno::updateOrCreate(
            [
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
