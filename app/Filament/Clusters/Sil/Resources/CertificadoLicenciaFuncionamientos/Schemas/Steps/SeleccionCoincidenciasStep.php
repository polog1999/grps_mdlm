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
use Illuminate\Support\Facades\Log;

class SeleccionCoincidenciasStep
{
    public static function make(): Step
    {
        return Step::make('Selección')
            ->description(fn($get) => self::getDescription($get))
            ->icon('heroicon-m-clipboard-document-check')
            ->schema(fn($get) => self::generarInterfazSeleccion($get))
            ->beforeValidation(function ($get, $set) {
                if ($get('_persona_requerida')) {
                    self::ejecutarCreacionPersonaAlSiguiente($get, $set);
                }
            })
            ->visible(
                fn($get) =>
                !empty($get('_catastro_coincidencias')) ||
                !empty($get('_resolucion_areas_coincidencias')) ||
                !empty($get('_persona_requerida')) ||
                !empty($get('_itse_coincidencias'))
            );

    }
    private static function ejecutarCreacionPersonaAlSiguiente(callable $get, callable $set): void
    {
        try {
            $service = app(\App\Services\Sil\Personas\PersonaService::class);

            // Mapeo de datos desde el estado actual del formulario
            $dataToSend = [
                'per_nombrerazonsocial' => $get('exp_nomrec') ?? '',
                'per_ruc' => $get('numdoc') ?? '',
                'per_direccion' => $get('domfis') ?? '',
                'per_telefono' => $get('numtel') ?? '',
                'per_email' => $get('correo') ?? '',
                'per_expcodcon' => $get('exp_codcon') ?? '',
            ];

            $result = $service->create_unico($dataToSend);

            if (!$result['success']) {
                // Si falla, enviamos notificación y lanzamos error para detener el Wizard
                Notification::make()->title('Error')->body($result['message'])->danger()->send();

                // Esto evita que el Wizard pase al siguiente paso
                throw \Illuminate\Validation\ValidationException::withMessages([
                    '_accion_crear_persona' => $result['message'],
                ]);
            }

            // Éxito: Vincular IDs
            $perId = $result['per_id'];
            $set('exp_nomrec_id', $perId);
            $set('exp_razsoc_id', $perId);

            // Actualizar _datos_completos
            $datosRaw = $get('_datos_completos');
            $datosCompletos = is_string($datosRaw) ? json_decode($datosRaw, true) : ($datosRaw ?? []);

            if (!empty($datosCompletos)) {
                $expediente = (array) ($datosCompletos['expediente'] ?? []);
                $expediente['per_id'] = $perId;
                $expediente['exp_nomrec_id'] = $perId;
                $expediente['exp_razsoc_id'] = $perId;
                $datosCompletos['expediente'] = $expediente;
                $set('_datos_completos', $datosCompletos);
            }

            $set('_persona_requerida', false);

            Notification::make()
                ->title('¡Persona Creada y Vinculada!')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()->title('Error Crítico')->body($e->getMessage())->danger()->send();

            throw \Illuminate\Validation\ValidationException::withMessages([
                '_accion_crear_persona' => 'No se pudo crear la persona automáticamente.',
            ]);
        }
    }
    private static function getDescription(callable $get): string
    {
        if (!empty($get('_itse_coincidencias'))) {
            return 'Seleccione una ITSE disponible para riesgo alto';
        }
        return !empty($get('_resolucion_areas_coincidencias'))
            ? 'Seleccione el área de resolución correcta'
            : 'Resolver duplicados de catastro';
    }

    private static function generarInterfazSeleccion(callable $get): array
    {
        $areasResolucion = $get('_resolucion_areas_coincidencias') ?? [];
        $catastroCoincidencias = $get('_catastro_coincidencias') ?? [];
        $itseCoincidencias = $get('_itse_coincidencias') ?? [];

        Log::info('SeleccionCoincidenciasStep: Generando interfaz', [
            'itse_count' => count($itseCoincidencias),
            'areas_count' => count($areasResolucion),
            'catastro_count' => count($catastroCoincidencias),
            'persona_requerida' => $get('_persona_requerida')
        ]);

        // Prioridad: ITSE > Áreas > Catastro > Persona
        if (!empty($itseCoincidencias)) {
            return self::schemaItse($itseCoincidencias);
        }

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

    // --- SECCIÓN 3: Schema de ITSE ---
    private static function schemaItse(array $coincidencias): array
    {
        $opciones = [];
        $descripciones = [];

        foreach ($coincidencias as $item) {
            $item = (array) $item;
            $id = $item['cin_id'] ?? null;
            if ($id) {
                $numero = $item['cin_numero'] ?? 'S/N';
                $anio = $item['cin_anio'] ?? '';
                $expediente = $item['cin_expediente'] ?? '';
                $ubicacion = $item['cin_ubicacion'] ?? '';
                $solicitante = $item['cin_solicitante'] ?? '';

                $opciones[$id] = "ITSE N° {$numero}-{$anio}";
                $descripciones[$id] = "Expediente: {$expediente} | Solicitante: {$solicitante} | Ubicación: {$ubicacion}";
            }
        }

        return [
            Section::make('Seleccionar ITSE para Riesgo Alto/Muy Alto')
                ->description("Se encontraron " . count($coincidencias) . " ITSEs disponibles (sin licencia vinculada). Seleccione una para continuar.")
                ->icon('heroicon-o-shield-check')
                ->schema([
                    Radio::make('itse_seleccionado')
                        ->hiddenLabel()
                        ->options($opciones)
                        ->descriptions($descripciones)
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn($state, $set, $get) => self::procesarSeleccionItse($state, $set, $get))
                        ->columnSpanFull()
                ])
                ->compact()
        ];
    }
    // --- SECCIÓN 4: Schema de Persona ---
    private static function schemaBuscarPersona(): array
    {
        return [
            Section::make('Vincular Persona al Expediente')
                ->description("El expediente encontrado no tiene un solicitante identificado. Puede crear una nueva persona con los datos del expediente o buscar una existente.")
                ->icon('heroicon-o-user-plus')
                ->schema([
                    // Información del expediente desde Oracle
                    Placeholder::make('_info_nombre_oracle')
                        ->label('Nombre buscado')
                        ->content(fn($get) => $get('exp_nomrec') ?? 'No disponible')
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'text-sm text-gray-600']),

                    Placeholder::make('_info_numdoc')
                        ->label('Numero de Documento')
                        ->content(fn($get) => $get('numdoc') ?? 'No disponible')
                        ->columnSpan(1)
                        ->extraAttributes(['class' => 'text-sm text-gray-600']),

                    Placeholder::make('_info_codcontrib')
                        ->label('Codigo de Contribuyente')
                        ->content(fn($get) => $get('exp_codcon') ?? 'No disponible')
                        ->columnSpan(1)
                        ->extraAttributes(['class' => 'text-sm text-gray-600']),

                    Placeholder::make('_info_telefono')
                        ->label('Telefono')
                        ->content(fn($get) => $get('numtel') ?? 'No disponible')
                        ->columnSpan(1)
                        ->extraAttributes(['class' => 'text-sm text-gray-600']),

                    Placeholder::make('_info_direccion')
                        ->label('Direccion')
                        ->content(fn($get) => $get('domfis') ?? 'No disponible')
                        ->columnSpan(2)
                        ->extraAttributes(['class' => 'text-sm text-gray-600']),

                    Placeholder::make('_info_correo')
                        ->label('Correo Electronico')
                        ->content(fn($get) => $get('correo') ?? 'No disponible')
                        ->columnSpan(1)
                        ->extraAttributes(['class' => 'text-sm text-gray-600']),

                ])->columns(3)->compact()
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

    private static function procesarSeleccionItse($state, callable $set, callable $get): void
    {
        if (!$state)
            return;

        $datosCompletos = $get('_datos_completos') ?? [];
        $coincidencias = $get('_itse_coincidencias') ?? [];

        // Buscar la ITSE seleccionada
        $itseSeleccionada = null;
        foreach ($coincidencias as $item) {
            $item = (array) $item;
            if (($item['cin_id'] ?? null) == $state) {
                $itseSeleccionada = $item;
                break;
            }
        }

        if (!$itseSeleccionada) {
            return;
        }

        // Guardar cin_id seleccionado para uso posterior
        $cinId = $itseSeleccionada['cin_id'];
        $set('_cin_id_seleccionado', $cinId);

        // Mostrar notificación con el cin_id
        Notification::make()
            ->success()
            ->title('ITSE Seleccionada')
            ->body("Se ha seleccionado la ITSE con ID: {$cinId}")
            ->send();

        // Agregar la ITSE seleccionada a los datos completos
        $datosCompletos['itse_seleccionada'] = $itseSeleccionada;

        // Limpiar las coincidencias de ITSE ya que se seleccionó una
        $set('_itse_coincidencias', null);

        // Actualizar estado global y autocompletar formulario
        self::actualizarEstadoGlobal($datosCompletos, $set);
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