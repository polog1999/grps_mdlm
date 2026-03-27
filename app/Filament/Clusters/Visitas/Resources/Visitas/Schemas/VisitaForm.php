<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Schemas;

use App\Models\Area;
use App\Models\Motivo;
use App\Models\Oficina;
use App\Models\PersonaUno;
use App\Models\Proveedor;
use App\Models\TipoDocumento;
use App\Models\Trabajador;
use App\Services\PideService;
use App\Services\RucService;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class VisitaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del Visitante')
                    ->schema([
                        ToggleButtons::make('es_empresa')
                            ->label('Tipo')
                            ->options([
                                0 => 'Persona',
                                1 => 'Empresa',
                            ])
                            ->colors([
                                0 => 'success',
                                1 => 'info'
                            ])
                            ->live()
                            ->columnSpanFull()
                            ->default(0)
                            ->grouped(),
                        Select::make('tipo_documento_id')
                            ->label('Tipo de Documento')
                            // ->relationship('persona.tipoDocumento', 'nombre')
                            ->options(
                                fn() =>
                                TipoDocumento::pluck('abreviatura', 'id_tipo_documento') // Trae el nombre para mostrar y el id para guardar
                            )
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Al cambiar el número, reseteamos los campos de identidad
                                $set('numero_documento', null);
                                $set('nombres', null);
                                $set('apellido_paterno', null);
                                $set('apellido_materno', null);
                                $set('foto_url', null);
                            })
                            ->visible(fn(Get $get) => $get('es_empresa') == 0)
                            ->default(1)
                            ->live()
                            ->required(fn(Get $get) => $get('es_empresa') == 0),
                        Hidden::make('tipo_documento'),
                        TextInput::make('numero_documento')
                            ->label(fn(Get $get) => $get('es_empresa') == 0 ? 'Número Documento' : 'RUC')
                            ->required()
                            ->maxLength(fn(Get $get) => $get('tipo_documento_id') == 1 && $get('es_empresa') == 0 ? 8 : ($get('es_empresa') == 1 ? 11 : 20))
                            // 1. Solo permite números pero mantiene el input como tipo 'text' (sin flechas)
                            ->mask(fn(Get $get) => $get('tipo_documento_id') == 1  && $get('es_empresa') == 0 ? '99999999' : null)

                            // 2. Validación de servidor por si acaso
                            ->regex(fn(Get $get) => $get('tipo_documento_id') == 1 && $get('es_empresa') == 0
                                ? '/^[0-9]{8}$/'
                                : '/^[a-zA-Z0-9]{1,20}$/') // Regex flexible para otros documentos)


                            // 3. Tu validación de números iguales que pediste antes
                            ->rules(function (Get $get) {
                                if ($get('tipo_documento_id') == 1 && $get('es_empresa') == 0) {
                                    return [
                                        'regex:/^[0-9]{8}$/',            // Exactamente 8 números
                                        'regex:/^(?!.*(\d)\1{7}).*$/',   // No repetidos
                                        'not_in:00000000',               // No ceros
                                    ];
                                }
                                return []; // Sin reglas especiales para otros tipos
                            })
                            ->validationMessages([
                                'regex' => 'El número de documento no puede consistir en dígitos todos iguales.',
                                'not_in' => 'El número de documento no puede ser todo ceros.',
                                'numeric' => 'Solo se permiten números.',
                            ])

                            // 4. Mantiene el teclado numérico en celulares
                            ->inputMode(fn(Get $get) => $get('tipo_documento_id') == 1 ? 'numeric' : 'text')
                            // ->live(onBlur: true)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Al cambiar el número, reseteamos los campos de identidad
                                $set('nombres', null);
                                $set('apellido_paterno', null);
                                $set('apellido_materno', null);
                                $set('foto_url', null);
                            })
                            ->suffixActions(
                                [
                                    Action::make('buscar_visitante')
                                        ->color('success')
                                        ->icon('heroicon-m-magnifying-glass')
                                        ->visible(fn(Get $get) => $get('tipo_documento_id') == 1 && $get('es_empresa') == 0)
                                        ->action(function ($state, Set $set, Get $get) {
                                            if (!$state) return;

                                            // 1. Validar si ya existe como TRABAJADOR
                                            // $existeTrabajador = PersonaUno::where('numero_documento', $state)
                                            //     ->whereHas('trabajador')
                                            //     ->exists();

                                            // if ($existeTrabajador) {
                                            //     Notification::make()
                                            //         ->title('El trabajador ya se encuentra registrado.')
                                            //         ->danger()
                                            //         ->send();
                                            //     return;
                                            // }

                                            // 2. Buscar en tabla PERSONAS (Si ya fue visitante antes)
                                            if (strlen($state) === 8) {

                                                // $trabajador = Trabajador::where('dni', $state)->first();

                                                // if ($trabajador) {
                                                //     $set('persona_id', $trabajador->id_usuario);
                                                //     $set('nombres', $trabajador->nombres);
                                                //     $set('apellido_paterno', $trabajador->apellido_paterno);
                                                //     $set('apellido_materno', $trabajador->apellido_materno);
                                                //     $set('foto_url', $trabajador->foto_url); // Traer foto de la BD
                                                //     Notification::make()
                                                //         ->title('BD local')
                                                //         ->body('Datos de la Base de Datos local')
                                                //         ->success()
                                                //         ->send();
                                                //     return;
                                                // }

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
                                                    $set('foto_url', '/uploads/foto_dni/' . $state . '.png');
                                                    Notification::make()
                                                        ->title('Datos del PIDE')
                                                        ->body('Se consumió el PIDE')
                                                        ->success()
                                                        ->send();
                                                } else {
                                                    $datosApiPeru = PideService::apiPeruDni($state);

                                                    if ($datosApiPeru['success']) {
                                                        // dd('probando');
                                                        $set('pide_fallo', false); // Activamos edición manual
                                                        $set('nombres', $datosApiPeru['data']['nombres']);
                                                        $set('apellido_paterno', $datosApiPeru['data']['apellido_paterno']);
                                                        $set('apellido_materno', $datosApiPeru['data']['apellido_materno']);
                                                        Notification::make()
                                                            ->title('Datos del ApisPeru')
                                                            ->body('Se consumió el ApisPeru')
                                                            ->success()
                                                            ->send();
                                                    } else {
                                                        $datosApisNet = PideService::apisNet($state);

                                                        if ($datosApisNet['success']) {
                                                            $set('pide_fallo', false); // Activamos edición manual
                                                            $set('nombres', $datosApisNet['nombres']);
                                                            $set('apellido_paterno', $datosApisNet['apellidoPaterno']);
                                                            $set('apellido_materno', $datosApisNet['apellidoMaterno']);
                                                            Notification::make()
                                                                ->title('Datos de ApisNet')
                                                                ->body('Se consumió el ApisNet')
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
                                                    }
                                                }
                                            } else {
                                                Notification::make()
                                                    ->title('Alerta')
                                                    ->body('El DNI debe tener 8 dígitos')
                                                    ->warning()
                                                    ->send();
                                            }
                                        }),
                                    Action::make('buscar_ruc')
                                    ->color('info')
                                        ->icon('heroicon-m-magnifying-glass')
                                        ->visible(fn(Get $get) =>  $get('es_empresa') == 1)
                                        ->action(function ($state, Set $set, Get $get) {
                                            if (!$state) return;

                                            // 1. Validar si ya existe como TRABAJADOR
                                            // $existeTrabajador = PersonaUno::where('numero_documento', $state)
                                            //     ->whereHas('trabajador')
                                            //     ->exists();

                                            // if ($existeTrabajador) {
                                            //     Notification::make()
                                            //         ->title('El trabajador ya se encuentra registrado.')
                                            //         ->danger()
                                            //         ->send();
                                            //     return;
                                            // }

                                            // 2. Buscar en tabla PERSONAS (Si ya fue visitante antes)
                                            if (strlen($state) === 11) {

                                                // $trabajador = Trabajador::where('dni', $state)->first();

                                                // if ($trabajador) {
                                                //     $set('persona_id', $trabajador->id_usuario);
                                                //     $set('nombres', $trabajador->nombres);
                                                //     $set('apellido_paterno', $trabajador->apellido_paterno);
                                                //     $set('apellido_materno', $trabajador->apellido_materno);
                                                //     $set('foto_url', $trabajador->foto_url); // Traer foto de la BD
                                                //     Notification::make()
                                                //         ->title('BD local')
                                                //         ->body('Datos de la Base de Datos local')
                                                //         ->success()
                                                //         ->send();
                                                //     return;
                                                // }

                                                $proveedor = Proveedor::where('ruc', $state)->first();

                                                if ($proveedor) {
                                                    $set('proveedor_id', $proveedor->id_proveedor);
                                                    $set('nombres', $proveedor->nombre);
                                                    $set('direccion', $proveedor->direccion);

                                                    Notification::make()
                                                        ->title('BD inventario')
                                                        ->body('Datos de la Base de Datos inventario')
                                                        ->success()
                                                        ->send();
                                                    return;
                                                }


                                                $datosApiRuc = RucService::apiRuc($state);

                                                if ($datosApiRuc['success']) {

                                                    $set('pide_fallo', false); // Activamos edición manual
                                                    $set('nombres', $datosApiRuc['razon_social']);
                                                    $set('direccion', $datosApiRuc['direccion']);

                                                    Notification::make()
                                                        ->title('Datos del ApiRuc')
                                                        ->body('Se consumió el ApisRuc')
                                                        ->success()
                                                        ->send();
                                                } else {
                                                    $datosApisPeruRuc = RucService::apisPeruRuc($state);

                                                    if ($datosApisPeruRuc['success']) {

                                                        $set('pide_fallo', false); // Activamos edición manual
                                                        $set('nombres', $datosApisPeruRuc['razonSocial']);
                                                        $set('direccion', $datosApiRuc['direccion']);

                                                        Notification::make()
                                                            ->title('Datos del ApiRuc')
                                                            ->body('Se consumió el ApisPeru')
                                                            ->success()
                                                            ->send();
                                                    } else {
                                                        // FALLÓ EL PIDE
                                                        $set('pide_fallo', true); // Activamos edición manual
                                                        $set('nombres', null);
                                                        $set('direccion');
                                                        Notification::make()
                                                                ->title('API no disponible')
                                                                ->body('Complete los datos manualmente.')
                                                                ->warning()
                                                                ->send();
                                                    }
                                                }
                                            } else {
                                                Notification::make()
                                                    ->title('Alerta')
                                                    ->body('El DNI debe tener 11 dígitos')
                                                    ->warning()
                                                    ->send();
                                            }
                                        }),
                                ]
                            ),


                        Hidden::make('pide_fallo')->default(false)->live(),
                        TextInput::make('nombres')
                            ->label('Razón Social')
                            ->visible(fn(Get $get) => $get('es_empresa') == 1)
                            ->required(fn(Get $get) => $get('es_empresa') == 1)
                            ->readOnly(fn(Get $get) => $get('es_empresa') == 1 && !$get('pide_fallo')),
                        Hidden::make('direccion'),
                        // ->columnSpanFull()

                        TextInput::make('nombres')
                            ->visible(fn(Get $get) => $get('es_empresa') != 1) // Se oculta si es 6
                            ->required(fn(Get $get) => $get('es_empresa') != 1) // Solo requerido si no es 6
                            ->readOnly(fn(Get $get) => $get('tipo_documento_id') == 1 && !$get('pide_fallo') && $get('es_empresa') != 1),
                        TextInput::make('apellido_paterno')
                            ->visible(fn(Get $get) => $get('es_empresa') != 1) // Se oculta si es 6
                            ->required(fn(Get $get) => $get('es_empresa') != 1) // Solo requerido si no es 6
                            ->readOnly(fn(Get $get) =>
                            $get('tipo_documento_id') == 1 && $get('pide_fallo') == false && $get('es_empresa') != 1),

                        TextInput::make('apellido_materno')
                            ->visible(fn(Get $get) => $get('es_empresa') != 1) // Se oculta si es 6
                            ->required(fn(Get $get) => $get('es_empresa') != 1) // Solo requerido si no es 6
                            ->readOnly(fn(Get $get) =>
                            $get('tipo_documento_id') == 1 && $get('pide_fallo') == false && $get('es_empresa') != 1),
                        Placeholder::make('.')
                            ->label(false),
                        Placeholder::make('foto_visual')
                            ->visible(fn(Get $get) => $get('es_empresa') != 1 && $get('tipo_documento_id') == 1) // Se oculta si es 6
                            ->label('Foto RENIEC')
                            ->content(fn(Get $get) => new \Illuminate\Support\HtmlString(
                                $get('foto_url')
                                    ? '<img src="' . asset('fotos_externas/' . $get('foto_url')) . '" class="flex items-center justify-center bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-600" style="width:96px">'
                                    : '<p class="text-gray-400 text-xs">Sin foto</p>'
                            )),
                        Hidden::make('foto_url'),
                        Placeholder::make('.')
                            ->label(false),

                        Repeater::make('lista_trabajadores')
                            ->label('Trabajadores')
                            // Importante: SIN ->relationship()
                            ->schema([
                                Select::make('tipo_documento_id')
                                    ->label('Tipo de Documento')
                                    // ->relationship('persona.tipoDocumento', 'nombre')
                                    ->options(
                                        fn() =>
                                        TipoDocumento::pluck('abreviatura', 'id_tipo_documento') // Trae el nombre para mostrar y el id para guardar
                                    )
                                    ->default(1)
                                    ->live()
                                    ->required(fn($livewire) => $livewire->data['es_empresa'] == 1) // Solo requerido si no es 6,
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if ($state) {
                                            // Buscamos el nombre del área basada en el ID seleccionado
                                            $tipoDocumento = TipoDocumento::where('id_tipo_documento', $state)->value('abreviatura');
                                            // Guardamos ese nombre en el campo 'area_nombre'
                                            $set('tipo_documento', $tipoDocumento);
                                        } else {
                                            $set('tipo_documento', null);
                                        }
                                    }),
                                Hidden::make('tipo_documento'),
                                TextInput::make('numero_documento')

                                    ->required(fn($livewire) => $livewire->data['es_empresa'] == 1) // Solo requerido si no es 6
                                    ->maxLength(fn(Get $get) => $get('tipo_documento_id') == 1 ? 8 : 20)
                                    // 1. Solo permite números pero mantiene el input como tipo 'text' (sin flechas)
                                    ->mask(fn(Get $get) => $get('tipo_documento_id') == 1 ? '99999999' : null)

                                    // 2. Validación de servidor por si acaso
                                    ->regex(fn(Get $get) => $get('tipo_documento_id') == 1
                                        ? '/^[0-9]{8}$/'
                                        : '/^[a-zA-Z0-9]{1,20}$/') // Regex flexible para otros documentos)


                                    // 3. Tu validación de números iguales que pediste antes
                                    ->rules(function (Get $get) {
                                        if ($get('tipo_documento_id') == 1) {
                                            return [
                                                'regex:/^[0-9]{8}$/',            // Exactamente 8 números
                                                'regex:/^(?!.*(\d)\1{7}).*$/',   // No repetidos
                                                'not_in:00000000',               // No ceros
                                            ];
                                        }
                                        return []; // Sin reglas especiales para otros tipos
                                    })
                                    ->validationMessages([
                                        'regex' => 'El número de documento no puede consistir en dígitos todos iguales.',
                                        'not_in' => 'El número de documento no puede ser todo ceros.',
                                        'numeric' => 'Solo se permiten números.',
                                    ])

                                    // 4. Mantiene el teclado numérico en celulares
                                    ->inputMode(fn(Get $get) => $get('tipo_documento_id') == 1 ? 'numeric' : 'text')
                                    // ->live(onBlur: true)
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Al cambiar el número, reseteamos los campos de identidad
                                        $set('nombres', null);
                                        $set('apellido_paterno', null);
                                        $set('apellido_materno', null);
                                        $set('foto_url', null);
                                    })
                                    ->suffixAction(
                                        Action::make('buscar_visitante')
                                            ->icon('heroicon-m-magnifying-glass')
                                            ->visible(fn(Get $get) => $get('tipo_documento_id') == 1)
                                            ->action(function ($state, Set $set, Get $get) {
                                                if (!$state) return;

                                                if (strlen($state) === 8) {

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

                                                    $datosPide = PideService::ws_reniec($state);

                                                    if ($datosPide['codResu'] === '0000') {
                                                        $set('pide_fallo', false); // Activamos edición manual
                                                        $set('nombres', $datosPide['nombre']);
                                                        $set('apellido_paterno', $datosPide['paterno']);
                                                        $set('apellido_materno', $datosPide['materno']);
                                                        $set('foto_url', '/uploads/foto_dni/' . $state . '.png');
                                                        Notification::make()
                                                            ->title('Datos del PIDE')
                                                            ->body('Se consumió el PIDE')
                                                            ->success()
                                                            ->send();
                                                    } else {
                                                        $datosApiPeru = PideService::apiPeruDni($state);

                                                        if ($datosApiPeru['success']) {

                                                            $set('pide_fallo', false); // Activamos edición manual
                                                            $set('nombres', $datosApiPeru['data']['nombres']);
                                                            $set('apellido_paterno', $datosApiPeru['data']['apellido_paterno']);
                                                            $set('apellido_materno', $datosApiPeru['data']['apellido_materno']);
                                                            Notification::make()
                                                                ->title('Datos del ApisPeru')
                                                                ->body('Se consumió el ApisPeru')
                                                                ->success()
                                                                ->send();
                                                        } else {
                                                            $datosApisNet = PideService::apisNet($state);

                                                            if ($datosApisNet['success']) {
                                                                $set('pide_fallo', false); // Activamos edición manual
                                                                $set('nombres', $datosApisNet['nombres']);
                                                                $set('apellido_paterno', $datosApisNet['apellidoPaterno']);
                                                                $set('apellido_materno', $datosApisNet['apellidoMaterno']);
                                                                Notification::make()
                                                                    ->title('Datos de ApisNet')
                                                                    ->body('Se consumió el ApisNet')
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
                                                        }
                                                    }
                                                } else {
                                                    Notification::make()
                                                        ->title('Alerta')
                                                        ->body('El DNI debe tener 8 dígitos')
                                                        ->warning()
                                                        ->send();
                                                }
                                            })
                                    ),
                                TextInput::make('nombres')
                                    ->visible()
                                    ->required(fn($livewire) => $livewire->data['es_empresa'] == 1) // Solo requerido si no es 6
                                    ->readOnly(fn(Get $get) => $get('tipo_documento_id') == 1 && !$get('pide_fallo')),
                                TextInput::make('apellido_paterno')
                                    ->visible()
                                    ->required(fn($livewire) => $livewire->data['es_empresa'] == 1) // Solo requerido si no es 6
                                    ->readOnly(fn(Get $get) =>
                                    $get('tipo_documento_id') == 1 && $get('pide_fallo') == false),

                                TextInput::make('apellido_materno')
                                    ->visible()
                                    ->required(fn($livewire) => $livewire->data['es_empresa'] == 1) // Solo requerido si no es 6
                                    ->readOnly(fn(Get $get) =>
                                    $get('tipo_documento_id') == 1 && $get('pide_fallo') == false),
                                TextInput::make('cargo')
                                    ->label('Cargo')
                                    ->visible()
                                    ->required(fn($livewire) => $livewire->data['es_empresa'] == 1), // Solo requerido si no es 6

                            ])->columnSpanFull()->columns(2)
                            ->visible(fn($livewire) => $livewire->data['es_empresa'] == 1)

                            ->dehydrated(false), // <--- ESTO EVITA EL ERROR SQL AL GUARDAR,





                    ])
                    ->columns(2),


                Section::make('Detalles de la Visita')
                    ->schema([
                        // Hidden::make('sede_id')->default(1),
                        // Hidden::make('user_id_ingreso')
                        //     ->default(auth()->id())
                        //     ->dehydrated(),
                        Select::make('area_id')
                            ->label('Área de Destino')
                            ->options(fn() => Area::where('id_uo_estado', 1)->orderBy('nombre', 'asc')->pluck('nombre', 'id_unidad_organica'))
                            ->searchable()
                            ->live() // Crucial para que el segundo select se entere del cambio
                            ->required()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    // Buscamos el nombre del área basada en el ID seleccionado
                                    $nombreArea = Area::where('id_unidad_organica', $state)->value('nombre');
                                    // Guardamos ese nombre en el campo 'area_nombre'
                                    $set('area', $nombreArea);
                                } else {
                                    $set('area', null);
                                }
                            }),
                        Hidden::make('area'),
                        Select::make('oficina_id')
                            ->label('Oficina')
                            ->options(function (Get $get) {
                                $areaId = $get('area_id');
                                if (!$areaId) return [];

                                return Oficina::query()
                                    ->where('id_unidad_organica', $areaId)
                                    ->get()
                                    ->mapWithKeys(function ($oficina) {
                                        // Forzamos que el label sea un string y no null
                                        $nombre = $oficina->nombre ?? "Oficina {$oficina->id_oficina}";
                                        return [$oficina->id_oficina => $nombre];
                                    });
                            })
                            ->searchable()
                            ->required(fn(Get $get) => Oficina::where('id_unidad_organica', $get('area_id'))->exists())
                            ->hidden(fn(Get $get) => !(Oficina::where('id_unidad_organica', $get('area_id'))->exists()))
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    // Buscamos el nombre del área basada en el ID seleccionado
                                    $nombreOficina = Oficina::where('id_oficina', $state)->value('nombre');
                                    // Guardamos ese nombre en el campo 'area_nombre'
                                    $set('oficina', $nombreOficina);
                                } else {
                                    $set('oficina', null);
                                }
                            }),
                        Hidden::make('oficina'),
                        Select::make('trabajador_id_autoriza')
                            ->label('Autorizado por')
                            ->options(function (Get $get) {
                                $areaId = $get('area_id');
                                if (!$areaId) return [];

                                return Trabajador::query()
                                    ->where('id_unidad_organica', $areaId)
                                    ->where('id_estado', 1)
                                    // ->whereHas('cargo', function ($q) {
                                    //     $q->where('nombre', 'like', '%secretaria%')
                                    //         ->orWhere('nombre', 'like', '%SECRETARIA%')
                                    //         ->orWhere('nombre', 'like', '%jefe%')
                                    //         ->orWhere('nombre', 'like', '%JEFE%')
                                    //         ->orWhere('nombre', 'like', '%GERENTE%')
                                    //         ->orWhere('nombre', 'like', '%gerente%');
                                    // })
                                    // ->with('persona') // Eager loading para evitar consultas lentas
                                    ->whereNotIn('id_contratacion', [3, 8])
                                    ->get()
                                    ->mapWithKeys(function ($trabajador) {
                                        // Forzamos que el label sea un string y no null
                                        $nombre = $trabajador->nombres . ' ' . $trabajador->apellidos ?? "Trabajador {$trabajador->id_usuario}";
                                        return [$trabajador->id_usuario => $nombre];
                                    });
                            })
                            ->searchable()
                            ->required(fn(Get $get) => $get('area_id') == 1 ? false : true)
                            ->hidden(fn(Get $get) => $get('area_id') == 1)
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    // Buscamos el nombre del área basada en el ID seleccionado
                                    $trabajador = Trabajador::where('id_usuario', $state)->first(['nombres', 'apellidos']);
                                    $nombreCompleto = "{$trabajador->nombres} {$trabajador->apellidos}";
                                    // Guardamos ese nombre en el campo 'area_nombre'
                                    $set('trabajador_autoriza', $nombreCompleto);
                                } else {
                                    $set('trabajador_autoriza', null);
                                }
                            }),
                        Hidden::make('trabajador_autoriza'),
                        Select::make('trabajador_id_cita')
                            ->label('Cita con')
                            ->options(function (Get $get) {
                                $areaId = $get('area_id');
                                if (!$areaId) return [];

                                return Trabajador::query()
                                    ->where('id_unidad_organica', $areaId)
                                    // ->whereNotIn('regimen_id', ['5', '6', '7', '14'])
                                    ->where('id_estado', 1)
                                    // ->whereHas('cargo', function ($q) {
                                    //     $q->where('nombre', 'like', '%secretaria%')
                                    //         ->orWhere('nombre', 'like', '%SECRETARIA%')
                                    //         ->orWhere('nombre', 'like', '%jefe%')
                                    //         ->orWhere('nombre', 'like', '%JEFE%')
                                    //         ->orWhere('nombre', 'like', '%GERENTE%')
                                    //         ->orWhere('nombre', 'like', '%gerente%');
                                    // })
                                    // ->with('persona') // Eager loading para evitar consultas lentas
                                    ->get()
                                    ->mapWithKeys(function ($trabajador) {
                                        // Forzamos que el label sea un string y no null
                                        $nombre = $trabajador->nombres . ' ' . $trabajador->apellidos ?? "Trabajador {$trabajador->id_usuario}";
                                        return [$trabajador->id_usuario => $nombre];
                                    });
                            })
                            ->searchable()
                            ->required(fn(Get $get) => $get('area_id') == 1 ? false : true)
                            ->hidden(fn(Get $get) => $get('area_id') == 1)
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    // Buscamos el nombre del área basada en el ID seleccionado
                                    $trabajador = Trabajador::where('id_usuario', $state)->first(['nombres', 'apellidos']);
                                    $nombreCompleto = "{$trabajador->nombres} {$trabajador->apellidos}";
                                    // Guardamos ese nombre en el campo 'area_nombre'
                                    $set('trabajador_cita', $nombreCompleto);
                                } else {
                                    $set('trabajador_cita', null);
                                }
                            }),
                        Hidden::make('trabajador_cita'),
                        Placeholder::make('.'),
                        Section::make('Motivo')
                            ->schema([
                                Select::make('motivo')
                                    ->label('Sugerencia')
                                    ->options(
                                        fn() => Motivo::query()
                                            ->pluck('motivo', 'motivo') // (Lo que se ve, Lo que se guarda)

                                    )->searchable()
                                    ->live()
                                    ->required(),

                                TextInput::make('detalle_motivo')
                                    ->label('Detalle')
                                    ->visible(fn(Get $get) => $get('motivo') != "")
                                    ->required(fn(Get $get) => $get('motivo') != "")

                            ])->columnSpanFull()->columns(2),
                        Radio::make('sistema')
                            ->label('Sistema')
                            ->options([
                                'VISITAS' => 'Visitas',
                                'PCM' => 'PCM',
                            ])
                            ->default('VISITAS')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
