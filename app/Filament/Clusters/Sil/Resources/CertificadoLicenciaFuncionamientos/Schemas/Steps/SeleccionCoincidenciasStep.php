<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps;

use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard\Step;
use App\Actions\Sil\SeleccionarAreaResolucionAction;
use App\Actions\Sil\SeleccionarCatastroAction;
use Filament\Forms\Components\Select;
use App\Actions\Sil\VincularPersonaAction;
use App\Models\Persona;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use App\Services\Sil\Licencias\LicenciaPersonaService;

class SeleccionCoincidenciasStep
{
    public static function make(): Step
    {
        return Step::make('Selección')
            ->description(fn($get) => self::getDescription($get))
            ->icon('heroicon-m-clipboard-document-check')
            ->schema(fn($get) => self::generarInterfazSeleccion($get))
            // Solo visible si hay coincidencias pendientes
            ->visible(
                fn($get) =>
                !empty($get('_catastro_coincidencias')) ||
                !empty($get('_resolucion_areas_coincidencias')) ||
                !empty($get('_persona_requerida'))

            );

    }

    private static function getDescription(callable $get): string
    {
        return !empty($get('_resolucion_areas_coincidencias'))
            ? 'Seleccione el área de resolución correcta'
            : 'Resolver duplicados de catastro';
    }

    private static function generarInterfazSeleccion(callable $get): array
    {
        $areasResolucion = $get('_resolucion_areas_coincidencias') ?? [];
        $catastroCoincidencias = $get('_catastro_coincidencias') ?? [];

        if (!empty($areasResolucion)) {
            return self::schemaAreas($areasResolucion);
        } elseif (!empty($catastroCoincidencias)) {
            return self::schemaCatastro($catastroCoincidencias);
        }

        if ($get('_persona_requerida')) {
            return self::schemaBuscarPersona();
        }

        return [];
    }

    // --- SECCIÓN 1: Schema de Áreas ---
    private static function schemaAreas(array $areas): array
    {
        $opciones = [];
        $descripciones = [];

        foreach ($areas as $item) {
            $item = (array) $item; // Cast rápido para lectura
            $id = $item['codigo_unico_tramite'] ?? null;
            if ($id) {
                $opciones[$id] = "Código: {$id}";
                $descripciones[$id] = "Área: " . ($item['area_completa'] ?? 'Sin área');
            }
        }

        return [
            Section::make('Múltiples áreas de resolución encontradas')
                ->description("Se encontraron " . count($areas) . " áreas. Seleccione la correcta.")
                ->icon('heroicon-o-building-office')
                ->schema([
                    Radio::make('codigo_unico_tramite_seleccionado')
                        ->hiddenLabel()
                        ->options($opciones)
                        ->descriptions($descripciones)
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn($state, $set, $get) => self::procesarSeleccionArea($state, $set, $get))
                        ->columnSpanFull()
                ])
                ->compact()
        ];
    }

    // --- SECCIÓN 2: Schema de Catastro ---
    private static function schemaCatastro(array $coincidencias): array
    {
        $opciones = [];
        $descripciones = [];

        foreach ($coincidencias as $item) {
            $item = (array) $item;
            $id = $item['fiu_id'] ?? null;
            if ($id) {
                $opciones[$id] = "FIU ID: {$id}";
                $descripciones[$id] = "Dirección: " . ($item['via_completa'] ?? 'Sin vía');
            }
        }

        return [
            Section::make('Múltiples registros catastrales encontrados')
                ->description("Se encontraron " . count($coincidencias) . " ubicaciones. Seleccione la correcta.")
                ->icon('heroicon-o-exclamation-triangle')
                ->schema([
                    Radio::make('fiu_id_seleccionado')
                        ->hiddenLabel()
                        ->options($opciones)
                        ->descriptions($descripciones)
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn($state, $set, $get) => self::procesarSeleccionCatastro($state, $set, $get))
                        ->columnSpanFull()
                ])
                ->compact()
        ];
    }
    // --- SECCIÓN 3: Schema de Persona ---
    private static function schemaBuscarPersona(): array
    {
        return [
            Section::make('Vincular Persona al Expediente')
                ->description("El expediente encontrado no tiene un solicitante identificado...")
                ->icon('heroicon-o-user-plus')
                ->schema([
                    // Información del nombre buscado desde Oracle
                    Placeholder::make('_info_nombre_oracle')
                        ->label('Nombre buscado')
                        ->content(fn($get) => $get('exp_nomrec') ?? 'No disponible')
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'text-sm text-gray-600']),

                    // Campo 1: Nombre y Apellidos
                    TextInput::make('_temp_nombre_apellidos')
                        ->label('Nombre y Apellidos')
                        ->placeholder('Buscar persona...')
                        ->dehydrated(false)
                        ->columnSpan(1)
                        ->required()
                        ->validationMessages([
                            'required' => 'Debes buscar y seleccionar una Persona Natural.',
                        ])
                        ->validationAttribute('Nombre y Apellidos')
                        ->suffixAction(
                            Action::make('buscar_nombre_modal')
                                ->icon('heroicon-o-magnifying-glass')
                                ->modalHeading('Buscar Nombre y Apellidos')
                                ->modalDescription('Seleccione una persona de la lista y visualice sus datos')
                                ->modalSubmitActionLabel('Seleccionar')
                                ->modalWidth('5xl')
                                ->form([
                                    Select::make('_persona_nombre_temp')
                                        ->label('Buscar Persona')
                                        ->options(function () {
                                            $service = app(LicenciaPersonaService::class);
                                            return $service->getPersonasFormateadas();
                                        })
                                        ->searchable()
                                        ->required()
                                        ->placeholder('Busque por nombre o razón social...')
                                        ->live(onBlur: false)
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                                $service = app(LicenciaPersonaService::class);
                                                $personas = $service->getLicenciaPersonaNombre();
                                                $persona = $personas->firstWhere('per_id', $state);

                                                if ($persona) {
                                                    $set('preview_nombre', $persona->per_nombrerazonsocial ?? '');
                                                    $set('preview_ruc', $persona->per_ruc ?? '');
                                                    $set('preview_direccion', $persona->per_direccion ?? '');
                                                    $set('preview_telefono', $persona->per_telefono ?? '');
                                                    $set('preview_email', $persona->per_email ?? '');
                                                    $set('preview_expediente', $persona->per_expcodcon ?? '');
                                                }
                                            } else {
                                                $set('preview_nombre', '');
                                                $set('preview_ruc', '');
                                                $set('preview_direccion', '');
                                                $set('preview_telefono', '');
                                                $set('preview_email', '');
                                                $set('preview_expediente', '');
                                            }
                                        })
                                        ->columnSpanFull(),

                                    Section::make('Datos de la Persona')
                                        ->schema([
                                            TextInput::make('preview_nombre')
                                                ->label('Nombre / Razón Social')
                                                ->disabled()
                                                ->dehydrated(false)
                                                ->columnSpan(2),
                                            TextInput::make('preview_ruc')
                                                ->label('RUC/DNI')
                                                ->disabled()
                                                ->dehydrated(false),
                                            TextInput::make('preview_expediente')
                                                ->label('Expediente')
                                                ->disabled()
                                                ->dehydrated(false),
                                            TextInput::make('preview_direccion')
                                                ->label('Dirección')
                                                ->disabled()
                                                ->dehydrated(false)
                                                ->columnSpan(2),
                                            TextInput::make('preview_telefono')
                                                ->label('Teléfono')
                                                ->disabled()
                                                ->dehydrated(false),
                                            TextInput::make('preview_email')
                                                ->label('Email')
                                                ->disabled()
                                                ->dehydrated(false),
                                        ])
                                        ->columns(3)
                                        ->columnSpanFull()
                                        ->visible(fn($get) => $get('_persona_nombre_temp') !== null),
                                ])
                                ->action(function (array $data, $set, $get) {
                                    if (isset($data['_persona_nombre_temp'])) {
                                        $service = app(LicenciaPersonaService::class);
                                        $personas = $service->getLicenciaPersonaNombre();
                                        $personaSeleccionada = $personas->firstWhere('per_id', $data['_persona_nombre_temp']);

                                        if ($personaSeleccionada) {
                                            $set('_temp_nombre_apellidos', $personaSeleccionada->per_nombrerazonsocial);

                                            $set('exp_nomrec', $personaSeleccionada->per_nombrerazonsocial);
                                            $set('exp_nomrec_id', $personaSeleccionada->per_id);

                                            self::actualizarPersonaEnDatos($get, $set, 'exp_nomrec', $personaSeleccionada->per_nombrerazonsocial, $personaSeleccionada->per_id);
                                        }
                                    }
                                })
                        ),

                    // Campo 2: Razón Social
                    TextInput::make('_temp_razon_social')
                        ->label('Razón Social')
                        ->placeholder('Buscar razón social...')
                        ->dehydrated(false)
                        ->columnSpan(1)
                        ->required()
                        ->validationMessages([
                            'required' => 'Debes buscar y seleccionar una Razón Social (Empresa).',
                        ])
                        ->validationAttribute('Razón Social')
                        ->suffixAction(
                            Action::make('buscar_razon_modal')
                                ->icon('heroicon-o-magnifying-glass')
                                ->modalHeading('Buscar Razón Social')
                                ->modalDescription('Seleccione una razón social de la lista y visualice sus datos')
                                ->modalSubmitActionLabel('Seleccionar')
                                ->modalWidth('5xl')
                                ->form([
                                    Select::make('_persona_razon_temp')
                                        ->label('Buscar Razón Social')
                                        ->options(function () {
                                            $service = app(LicenciaPersonaService::class);
                                            return $service->getPersonasFormateadas();
                                        })
                                        ->searchable()
                                        ->required()
                                        ->placeholder('Busque por razón social...')
                                        ->live(onBlur: false)
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                                $service = app(LicenciaPersonaService::class);
                                                $personas = $service->getLicenciaPersonaNombre();
                                                $persona = $personas->firstWhere('per_id', $state);

                                                if ($persona) {
                                                    $set('preview_nombre_rs', $persona->per_nombrerazonsocial ?? '');
                                                    $set('preview_ruc_rs', $persona->per_ruc ?? '');
                                                    $set('preview_direccion_rs', $persona->per_direccion ?? '');
                                                    $set('preview_telefono_rs', $persona->per_telefono ?? '');
                                                    $set('preview_email_rs', $persona->per_email ?? '');
                                                    $set('preview_expediente_rs', $persona->per_expcodcon ?? '');
                                                }
                                            } else {
                                                $set('preview_nombre_rs', '');
                                                $set('preview_ruc_rs', '');
                                                $set('preview_direccion_rs', '');
                                                $set('preview_telefono_rs', '');
                                                $set('preview_email_rs', '');
                                                $set('preview_expediente_rs', '');
                                            }
                                        })
                                        ->columnSpanFull(),

                                    Section::make('Datos de la Razón Social')
                                        ->schema([
                                            TextInput::make('preview_nombre_rs')
                                                ->label('Nombre / Razón Social')
                                                ->disabled()
                                                ->dehydrated(false)
                                                ->columnSpan(2),
                                            TextInput::make('preview_ruc_rs')
                                                ->label('RUC/DNI')
                                                ->disabled()
                                                ->dehydrated(false),
                                            TextInput::make('preview_expediente_rs')
                                                ->label('Expediente')
                                                ->disabled()
                                                ->dehydrated(false),
                                            TextInput::make('preview_direccion_rs')
                                                ->label('Dirección')
                                                ->disabled()
                                                ->dehydrated(false)
                                                ->columnSpan(2),
                                            TextInput::make('preview_telefono_rs')
                                                ->label('Teléfono')
                                                ->disabled()
                                                ->dehydrated(false),
                                            TextInput::make('preview_email_rs')
                                                ->label('Email')
                                                ->disabled()
                                                ->dehydrated(false),
                                        ])
                                        ->columns(3)
                                        ->columnSpanFull()
                                        ->visible(fn($get) => $get('_persona_razon_temp') !== null),
                                ])
                                ->action(function (array $data, $set, $get) {
                                    if (isset($data['_persona_razon_temp'])) {
                                        $service = app(LicenciaPersonaService::class);
                                        $personas = $service->getLicenciaPersonaNombre();
                                        $personaSeleccionada = $personas->firstWhere('per_id', $data['_persona_razon_temp']);

                                        if ($personaSeleccionada) {
                                            $set('_temp_razon_social', $personaSeleccionada->per_nombrerazonsocial);

                                            $set('exp_razsoc', $personaSeleccionada->per_nombrerazonsocial);
                                            $set('exp_razsoc_id', $personaSeleccionada->per_id);

                                            self::actualizarPersonaEnDatos($get, $set, 'exp_razsoc', $personaSeleccionada->per_nombrerazonsocial, $personaSeleccionada->per_id);
                                        }
                                    }
                                })
                        ),
                ])->columns(2)->compact()
        ];
    }
    private static function actualizarPersonaEnDatos(callable $get, callable $set, string $campo, string $nombre, int $personaId): void
    {
        $datosRaw = $get('_datos_completos');
        $datosCompletos = is_string($datosRaw) ? json_decode($datosRaw, true) : ($datosRaw ?? []);

        if (empty($datosCompletos)) {
            return;
        }

        $expediente = $datosCompletos['expediente'] ?? [];
        if (is_object($expediente)) {
            $expediente = (array) $expediente;
        }

        // Actualizar según el campo
        if ($campo === 'exp_nomrec') {
            $expediente['exp_nomrec'] = $nombre;
            $expediente['exp_nomrec_id'] = $personaId;
        } else {
            $expediente['exp_razsoc'] = $nombre;
            $expediente['exp_razsoc_id'] = $personaId;
        }

        $datosCompletos['expediente'] = $expediente;
        $set('_datos_completos', $datosCompletos);
    }
    private static function procesarVinculacionPersona($state, callable $set, callable $get): void
    {
        if (!$state)
            return;

        // 1. OBTENER Y DECODIFICAR
        // Como definiste formatStateUsing con json_encode, aquí llega un String.
        $datosRaw = $get('_datos_completos');
        $datosCompletos = is_string($datosRaw) ? json_decode($datosRaw, true) : ($datosRaw ?? []);

        // Validación de seguridad para no perder datos previos
        if (empty($datosCompletos)) {
            // Si esto ocurre, es mejor detenerse que sobreescribir con vacío
            Notification::make()->title('Error')->body('No se pudieron recuperar los datos previos.')->danger()->send();
            return;
        }

        // 2. EJECUTAR ACTION
        // Ahora $datosCompletos es un array con Catastro y Resolución intactos
        $action = app(VincularPersonaAction::class);
        $nuevosDatos = $action->execute($datosCompletos, $state);

        // 3. ACTUALIZAR ESTADO INTERNO
        $set('_datos_completos', $nuevosDatos);

        DatosCompletosStep::autocompletarDatos($nuevosDatos, $set);
    }
    // --- SECCIÓN 3: Handlers (Puente entre UI y Actions) ---

    private static function procesarSeleccionArea($state, callable $set, callable $get): void
    {
        if (!$state)
            return;

        // 1. Obtener datos crudos del estado
        $datosCompletos = $get('_datos_completos') ?? [];
        $coincidencias = $get('_resolucion_areas_coincidencias') ?? [];

        // 2. Llamar a la Action (Lógica Pura)
        $action = app(SeleccionarAreaResolucionAction::class);
        $nuevosDatos = $action->execute($datosCompletos, $coincidencias, $state);

        // 3. Actualizar UI
        self::actualizarEstadoGlobal($nuevosDatos, $set);
    }

    private static function procesarSeleccionCatastro($state, callable $set, callable $get): void
    {
        if (!$state)
            return;

        $datosCompletos = $get('_datos_completos') ?? [];
        $coincidencias = $get('_catastro_coincidencias') ?? [];

        $action = app(SeleccionarCatastroAction::class);
        $nuevosDatos = $action->execute($datosCompletos, $coincidencias, $state);

        self::actualizarEstadoGlobal($nuevosDatos, $set);
    }

    private static function actualizarEstadoGlobal(array $datos, callable $set): void
    {
        // Guardamos el estado actualizado
        $set('_datos_completos', $datos);

        // Disparamos el autocompletado de los campos visibles
        DatosCompletosStep::autocompletarDatos($datos, $set);
    }
}