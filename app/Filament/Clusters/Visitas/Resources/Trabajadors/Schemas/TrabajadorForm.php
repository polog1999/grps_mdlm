<?php

namespace App\Filament\Clusters\Visitas\Resources\Trabajadors\Schemas;

use Filament\Schemas\Schema;
use App\Models\Persona;
use App\Models\PersonaUno;
use App\Models\Regimen;
use App\Models\Trabajador;
use App\Services\PideService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class TrabajadorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación y Datos Personales')
                    ->schema([
                        Select::make('tipo_documento_id')
                            ->relationship('persona.tipoDocumento', 'nombre')
                            ->label('Tipo de Documento')
                            ->required()
                            ->live(),
                        TextInput::make('numero_documento')
                            ->label('Nro. Documento')
                            ->required()
                            ->maxLength(fn(Get $get) => $get('tipo_documento_id') == 1?8:20)
                            ->live()
                            // Acción para buscar en BD o PIDE
                            ->suffixAction(
                                Action::make('buscar_pide')
                                    ->icon('heroicon-m-magnifying-glass')
                                    // CONDICIÓN DE VISIBILIDAD:
                                    // Cambia el '1' por el ID que corresponda a DNI en tu tabla tipo_documentos
                                    ->visible(fn(Get $get) => $get('tipo_documento_id') == 1)
                                    ->action(function ($state, Set $set, Get $get) {
                                        if (!$state) return;

                                        // 1. Validar si ya existe como TRABAJADOR
                                        $existeTrabajador = PersonaUno::where('numero_documento', $state)
                                            ->whereHas('trabajador')
                                            ->exists();

                                        if ($existeTrabajador) {
                                            Notification::make()
                                                ->title('El trabajador ya se encuentra registrado.')
                                                ->danger()
                                                ->send();
                                            return;
                                        }

                                        // 2. Buscar en tabla PERSONAS (Si ya fue visitante antes)
                                        $persona = PersonaUno::where('numero_documento', $state)->first();

                                        if ($persona) {
                                            $set('persona_id', $persona->id);
                                            $set('nombres', $persona->nombres);
                                            $set('apellido_paterno', $persona->apellido_paterno);
                                            $set('apellido_materno', $persona->apellido_materno);
                                            $set('foto_url', $persona->foto_url); // Traer foto de la BD
                                            Notification::make()
                                                ->title('BD local')
                                                ->body('Datos de la Base de Datos local')
                                                ->success()
                                                ->send();
                                            return;
                                        }

                                        // 3. Si no existe en BD, Consultar al PIDE
                                        // Supongamos que tienes un Service: PideService::consultar($dni)
                                        $datosPide = PideService::ws_reniec($state);

                                        if ($datosPide['codResu'] === '0000') {
                                            $set('pide_fallo', false); // Activamos edición manual
                                            $set('nombres', $datosPide['nombre']);
                                            $set('apellido_paterno', $datosPide['paterno']);
                                            $set('apellido_materno', $datosPide['materno']);
                                            $set('foto_url', 'uploads/foto_dni/' . $state . '.png');
                                            Notification::make()
                                                ->title('Datos del PIDE')
                                                ->body('Se consumió el PIDE')
                                                ->success()
                                                ->send();
                                        } else {
                                            // FALLÓ EL PIDE
                                            $set('pide_fallo', true); // Activamos edición manual
                                            $set('nombres', null);
                                            $set('apellido_paterno', null);
                                            $set('apellido_materno', null);
                                            $set('foto_url', null);
                                            Notification::make()
                                                ->title('PIDE no disponible')
                                                ->body('Complete los datos manualmente.')
                                                ->warning()
                                                ->send();
                                        }
                                    })
                            ),


                        // Campo oculto para controlar el estado de edición
                        Hidden::make('pide_fallo')->default(false)->live(),

                        // Campos que se habilitan si pide_fallo es true
                        TextInput::make('nombres')
                            ->required()
                            ->readOnly(fn(Get $get) =>
                            $get('tipo_documento_id') == 1 && $get('pide_fallo') == false), // Si NO falló el pide, es de solo lectura

                        TextInput::make('apellido_paterno')
                            ->required()
                            ->readOnly(fn(Get $get) =>
                            $get('tipo_documento_id') == 1 && $get('pide_fallo') == false),

                        TextInput::make('apellido_materno')
                            ->required()
                            ->readOnly(fn(Get $get) =>
                            $get('tipo_documento_id') == 1 && $get('pide_fallo') == false),

                        DatePicker::make('fecha_nacimiento')
                        ->required(),
                        

                        Select::make('clasificacion_id')
                            ->relationship('clasificacion', 'nombre')
                            ->label('Clasificación')
                            ->required(),
                        Select::make('regimen_id')
                            ->relationship('regimen', 'cregimen')
                            ->options(Regimen::where('estado',true)->where('parent_id','>',0)->orderBy('parent_id','asc')->pluck('cregimen', 'id'))
                            ->label('Régimen')
                            ->required(),
                        DatePicker::make('fecha_ingreso')
                            ->default(now())
                            ->required(),
                        Placeholder::make('foto_visual')
                            ->label('Foto RENIEC')
                            ->content(fn(Get $get) => new \Illuminate\Support\HtmlString(
                                $get('foto_url')
                                    ? '<img src="' . asset('storage/'.$get('foto_url')) . '" class="flex items-center justify-center bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-600" style="width:96px">'
                                    : '<p class="text-gray-400 text-xs">Sin foto</p>'
                            )),

                        Hidden::make('foto_url'),
                    ])->columns(2),

                Section::make('Cargos Asignados')
                    ->description('Puede asignar uno o más cargos vigentes al trabajador.')
                    ->schema([
                        Repeater::make('cargos_activos') // Nombre clave
                            ->label('Lista de Cargos')
                            ->schema([
                                Select::make('cargo_id')
                                ->label('Cargo')
                                    ->options(\App\Models\Cargo::pluck('nombre', 'id'))
                                    ->required()
                                    ->searchable(),
                                TextInput::make('de_cargo')
                                ->label('Descripción de Cargo')
                                ->maxLength('300')
                                ->required(),
                                Select::make('area_id')
                                ->label('Área')
                                    ->options(\App\Models\Area::pluck('nombre', 'id'))
                                    ->required()
                                    ->searchable(),
                                // Select::make('sede_id')
                                // ->label('Sede')
                                //     ->options(\App\Models\Sede::pluck('nombre', 'id'))
                                //     ->required(),
                                DatePicker::make('fecha_inicio')
                                    ->default(now())
                                    ->required(),
                            ])
                            ->columns(2)
                            // ->createItemButtonLabel('Asignar otro cargo') // Habilita el botón "+"
                            ->dehydrated(false), // EVITA EL ERROR SQL: Filament ignorará esto al hacer el UPDATE principal
                    ]),

                Section::make('Historial de Cargos (Pasados)')
                    ->collapsed()
                    ->schema([
                        Repeater::make('historial_visual')
                            ->label('Línea de tiempo')
                            // Importante: SIN ->relationship()
                            ->schema([
                                TextInput::make('cargo_nombre')->label('Cargo')->disabled(),
                                TextInput::make('area_nombre')->label('Área')->disabled(),
                                TextInput::make('fecha_inicio')->label('Fecha Inicio')->disabled(),
                                TextInput::make('fecha_fin')->label('Fecha Fin')->disabled(),
                                Hidden::make('es_actual') // O un checkbox disabled
                            ])->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->dehydrated(false) // <--- ESTO EVITA EL ERROR SQL AL GUARDAR
                    ])
            ]);
    }
}
