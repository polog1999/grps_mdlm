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
                                ->modalDescription('Seleccione una persona de la lista')
                                ->modalSubmitActionLabel('Seleccionar')
                                ->modalWidth('5xl')
                                ->form([
                                    Select::make('_persona_nombre_temp')
                                        ->label('Buscar Persona')
                                        ->searchable()
                                        ->getSearchResultsUsing(function (string $search): array {
                                            return Persona::query()
                                                ->where('per_nombrerazonsocial', 'like', "%{$search}%")
                                                ->limit(20)
                                                ->pluck('per_nombrerazonsocial', 'per_id')
                                                ->toArray();
                                        })
                                        ->getOptionLabelUsing(function ($value): ?string {
                                            return Persona::find($value)?->per_nombrerazonsocial;
                                        })
                                        ->required()
                                        ->columnSpanFull()
                                ])
                                ->action(function (array $data, $set, $get) {
                                    if (isset($data['_persona_nombre_temp'])) {
                                        $persona = Persona::find($data['_persona_nombre_temp']);
                                        if ($persona) {
                                            $set('_temp_nombre_apellidos', $persona->per_nombrerazonsocial);

                                            // IMPORTANTE: HE BORRADO LA LÍNEA QUE LIMPIABA LA RAZÓN SOCIAL
                                            // $set('_temp_razon_social', null); <--- ELIMINADO
                        
                                            $set('exp_nomrec', $persona->per_nombrerazonsocial);
                                            $set('exp_nomrec_id', $persona->per_id);

                                            self::actualizarPersonaEnDatos($get, $set, 'exp_nomrec', $persona->per_nombrerazonsocial, $persona->per_id);
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
                                ->modalDescription('Seleccione una razón social de la lista')
                                ->modalSubmitActionLabel('Seleccionar')
                                ->modalWidth('5xl')
                                ->form([
                                    Select::make('_persona_razon_temp')
                                        ->label('Buscar Razón Social')
                                        ->searchable()
                                        ->getSearchResultsUsing(function (string $search): array {
                                            return Persona::query()
                                                ->where('per_nombrerazonsocial', 'like', "%{$search}%")
                                                ->limit(20)
                                                ->pluck('per_nombrerazonsocial', 'per_id')
                                                ->toArray();
                                        })
                                        ->getOptionLabelUsing(function ($value): ?string {
                                            return Persona::find($value)?->per_nombrerazonsocial;
                                        })
                                        ->required()
                                        ->columnSpanFull()
                                ])
                                ->action(function (array $data, $set, $get) {
                                    if (isset($data['_persona_razon_temp'])) {
                                        $persona = Persona::find($data['_persona_razon_temp']);
                                        if ($persona) {
                                            $set('_temp_razon_social', $persona->per_nombrerazonsocial);

                                            // IMPORTANTE: HE BORRADO LA LÍNEA QUE LIMPIABA EL NOMBRE
                                            // $set('_temp_nombre_apellidos', null); <--- ELIMINADO
                        
                                            $set('exp_razsoc', $persona->per_nombrerazonsocial);
                                            $set('exp_razsoc_id', $persona->per_id);

                                            self::actualizarPersonaEnDatos($get, $set, 'exp_razsoc', $persona->per_nombrerazonsocial, $persona->per_id);
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