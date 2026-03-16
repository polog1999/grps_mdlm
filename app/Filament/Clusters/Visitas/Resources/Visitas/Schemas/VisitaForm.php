<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Schemas;

use App\Models\Area;
use App\Models\Oficina;
use App\Models\PersonaUno;
use App\Models\TipoDocumento;
use App\Models\Trabajador;
use App\Services\PideService;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                        Select::make('tipo_documento_id')
                            ->label('Tipo de Documento')
                            // ->relationship('persona.tipoDocumento', 'nombre')
                            ->options(
                                fn() =>
                                TipoDocumento::where('estado', true)
                                    ->pluck('nombre', 'id') // Trae el nombre para mostrar y el id para guardar
                            )
                            ->default(1)
                            ->live()
                            ->required(),
                        TextInput::make('numero_documento')

                            ->required()
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
                                    })
                            ),

                        Hidden::make('pide_fallo')->default(false)->live(),
                        TextInput::make('nombres')
                            ->label('Razón Social')
                            ->visible(fn(Get $get) => $get('tipo_documento_id') == 6)
                            ->required(fn(Get $get) => $get('tipo_documento_id') == 6)
                            // ->columnSpanFull()
                            ,
                        TextInput::make('nombres')
                            ->visible(fn(Get $get) => $get('tipo_documento_id') != 6) // Se oculta si es 6
                            ->required(fn(Get $get) => $get('tipo_documento_id') != 6) // Solo requerido si no es 6
                            ->readOnly(fn(Get $get) => $get('tipo_documento_id') == 1 && !$get('pide_fallo')),
                        TextInput::make('apellido_paterno')
                            ->visible(fn(Get $get) => $get('tipo_documento_id') != 6) // Se oculta si es 6
                            ->required(fn(Get $get) => $get('tipo_documento_id') != 6) // Solo requerido si no es 6
                            ->readOnly(fn(Get $get) =>
                            $get('tipo_documento_id') == 1 && $get('pide_fallo') == false),

                        TextInput::make('apellido_materno')
                            ->visible(fn(Get $get) => $get('tipo_documento_id') != 6) // Se oculta si es 6
                            ->required(fn(Get $get) => $get('tipo_documento_id') != 6) // Solo requerido si no es 6
                            ->readOnly(fn(Get $get) =>
                            $get('tipo_documento_id') == 1 && $get('pide_fallo') == false),
                        Placeholder::make('.')
                            ->label(false),
                        Placeholder::make('foto_visual')
                            ->label('Foto RENIEC')
                            ->content(fn(Get $get) => new \Illuminate\Support\HtmlString(
                                $get('foto_url')
                                    ? '<img src="' . asset('fotos_externas/' . $get('foto_url')) . '" class="flex items-center justify-center bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-600" style="width:96px">'
                                    : '<p class="text-gray-400 text-xs">Sin foto</p>'
                            )),
                        Hidden::make('foto_url'),


                    ])->columns(2),

                Section::make('Detalles de la Visita')
                    ->schema([
                        // Hidden::make('sede_id')->default(1),
                        // Hidden::make('user_id_ingreso')
                        //     ->default(auth()->id())
                        //     ->dehydrated(),
                        Select::make('area_id')
                            ->label('Área de Destino')
                            ->options(fn() => Area::orderBy('nombre', 'asc')->pluck('nombre', 'id_unidad_organica'))
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
                                    ->whereNotIn('id_contratacion', [3, 8])
                                    ->get()
                                    ->mapWithKeys(function ($trabajador) {
                                        // Forzamos que el label sea un string y no null
                                        $nombre = $trabajador->nombres . ' ' . $trabajador->apellidos ?? "Trabajador {$trabajador->id_usuario}";
                                        return [$trabajador->id_usuario => $nombre];
                                    });
                            })
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

                        TextInput::make('motivo')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }
}
