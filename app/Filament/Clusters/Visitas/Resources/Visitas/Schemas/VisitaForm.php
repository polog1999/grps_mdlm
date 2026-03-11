<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Schemas;

use App\Models\Area;
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
                            ->options(fn() =>
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
                            ->required()
                            ->readOnly(fn(Get $get) => $get('tipo_documento_id') == 1 && !$get('pide_fallo')),
                        TextInput::make('apellido_paterno')
                            ->required()
                            ->readOnly(fn(Get $get) =>
                            $get('tipo_documento_id') == 1 && $get('pide_fallo') == false),

                        TextInput::make('apellido_materno')
                            ->required()
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
                            ->options(fn() => Area::where('estado',true)->orderBy('nombre', 'asc')->pluck('nombre', 'id'))
                            ->searchable()
                            ->live() // Crucial para que el segundo select se entere del cambio
                            ->required(),

                        Select::make('trabajador_id_autoriza')
                            ->label('Autorizado por')
                            ->options(function (Get $get) {
                                $areaId = $get('area_id');
                                if (!$areaId) return [];

                                return Trabajador::query()
                                    ->whereHas('historiales', function ($query) use ($areaId) {
                                        $query->where('area_id', $areaId)
                                            ->where('es_actual', true);
                                    })
                                    ->whereNotIn('regimen_id', ['5', '6', '7', '14'])
                                    ->where('estado', true)
                                    ->with('persona') // Eager loading para evitar consultas lentas
                                    ->get()
                                    ->mapWithKeys(function ($trabajador) {
                                        // Forzamos que el label sea un string y no null
                                        $nombre = $trabajador->persona->full_nombre ?? "Trabajador {$trabajador->persona->id}";
                                        return [$trabajador->persona->id => $nombre];
                                    });
                            })
                            ->searchable()
                            ->required(),

                        TextInput::make('motivo')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }
}
