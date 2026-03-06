<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Schemas;

use App\Models\Anuncios;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use App\Services\Sil\Anuncios\ExpedienteAnuncioService;
use App\Services\Sil\Licencias\LicenciaService;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Placeholder;
use App\Models\CertificadoLicenciaFuncionamiento;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Colores;
use App\Models\TipoAnuncio;
use App\Models\CaracteristicasFisicas;
use App\Models\DocumentosAnuncio;
use App\Models\Zonificacion;
use App\Services\Sil\Anuncios\DatosTramiteService;

use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\TipoDocumento;
use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\AsuntoAnuncio;
use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\VigenciaAnuncio;
use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\Dictamen;
use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\EstadoAnuncio;
use App\Models\User;

use Filament\Forms\Components\Repeater;
use Illuminate\Validation\Rule;

class AnunciosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Buscar Expediente')
                        ->description('Busque el expediente en el sistema Gestrad')
                        ->schema([
                            TextInput::make('n_expediente_search')
                                ->label('Número de Expediente')
                                ->placeholder('Ej: 2024-0001')
                                ->live()
                                ->required(fn($record) => $record === null)
                                ->validationMessages([
                                    'required' => 'Es obligatorio vincular un expediente válido para continuar.',
                                ])

                                ->afterStateUpdated(function ($state, $set) {
                                    if (blank($state)) {
                                        return;
                                    }

                                    $service = app(ExpedienteAnuncioService::class);
                                    $expediente = $service->getExpedienteByNumero($state);

                                    if ($expediente) {
                                        Notification::make()
                                            ->title('Expediente encontrado')
                                            ->body("Se encontró el expediente: {$expediente->exp_nomrec}")
                                            ->success()
                                            ->send();

                                        // Mapear datos a campos del formulario (si existen en el esquema)
                                        $set('snapshot_solicitante_dni', $expediente->numdoc);
                                        $set('snapshot_solicitante_nombre_completo', str($expediente->nomcom)->upper());
                                        $set('form_domicilio_fiscal', str($expediente->domfis)->upper());
                                        $set('snapshot_solicitante_telefono', $expediente->exp_telefono);


                                        $set('n_expediente', $expediente->exp_num);
                                        $set('fecha_expediente', $expediente->exp_fec);
                                        $set('folios', $expediente->exp_numfol + 1);

                                        // Autocompletar Información de Pago desde PostgreSQL/Oracle
                                        try {
                                            $pagoService = app(DatosTramiteService::class);
                                            $pago = $pagoService->getPagoPorExpediente($state);
                                            if ($pago) {
                                                $set('n_pago', $pago->pagidentif);
                                                $set('monto', $pago->pagmontota);
                                            }
                                        } catch (\Exception $e) {
                                            Log::error('Error al autocompletar pago en AnunciosForm: ' . $e->getMessage());
                                        }
                                    } else {
                                        Notification::make()
                                            ->title('Expediente no encontrado')
                                            ->body('No se pudo encontrar información con el número ingresado en Oracle.')
                                            ->warning()
                                            ->send();
                                    }
                                }),
                            TextInput::make('snapshot_solicitante_dni')
                                ->label('DNI/RUC Solicitante'),
                            TextInput::make('snapshot_solicitante_nombre_completo')
                                ->label('Nombre Completo Solicitante'),
                            TextInput::make('form_domicilio_fiscal')
                                ->label('Dirección Fiscal'),
                            TextInput::make('snapshot_solicitante_telefono')
                                ->label('Teléfono Solicitante'),
                            TextInput::make('folios')
                                ->label('Folios'),
                            DatePicker::make('fecha_expediente')
                                ->label('Fecha de Expediente')
                                ->native(false)
                                ->displayFormat('d/m/Y'),
                            Select::make('zonificacion_id')
                                ->label('Zonificación')
                                ->options(fn() => Zonificacion::query()
                                    ->get()
                                    ->mapWithKeys(fn($z) => [$z->id => "{$z->siglas} - {$z->descripcion}"]))
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),

                    Step::make('Datos Licencia de Funcionamiento')
                        ->description('Indique si cuenta con licencia de funcionamiento')
                        ->schema([
                            ToggleButtons::make('tiene_licencia')
                                ->label('¿Cuenta con Licencia de Funcionamiento?')
                                ->options([
                                    'si' => 'Sí, cuenta con licencia',
                                    'no' => 'No cuenta con licencia',
                                ])
                                ->icons([
                                    'si' => 'heroicon-o-check-circle',
                                    'no' => 'heroicon-o-x-circle',
                                ])
                                ->colors([
                                    'si' => 'success',
                                    'no' => 'danger',
                                ])
                                ->default('si')
                                ->required()
                                ->live()
                                ->inline()
                                ->afterStateUpdated(function ($set) {
                                    $set('id_licencia', null);
                                    $set('giro_especifico_snapshot', null);
                                    $set('snapshot_lic_tipo', null);
                                    $set('form_direccion_predio', null);
                                }),

                            Section::make('Detalles de la Licencia')
                                ->description('Ingrese la información del documento')
                                ->schema([
                                    Select::make('id_licencia')
                                        ->label('Número de Licencia')
                                        ->options(fn() => CertificadoLicenciaFuncionamiento::query()
                                            ->whereNotNull('lic_numlic')
                                            ->where('lic_numlic', '!=', '')
                                            ->orderBy('lic_numlic', 'asc')
                                            ->pluck('lic_numlic', 'lic_id')
                                            ->toArray())
                                        ->searchable()
                                        ->placeholder('Buscar número de licencia...')
                                        ->native(false)
                                        ->required(fn($get) => $get('tiene_licencia') === 'si')
                                        ->validationMessages([
                                            'required' => 'Debes seleccionar una licencia para continuar.',
                                        ])
                                        ->live()
                                        ->afterStateUpdated(function ($state, $set) {
                                            // LOG 1: Inicio del proceso
                                            Log::info('Iniciando búsqueda de licencia. ID seleccionado: ' . ($state ?? 'NULO'));

                                            if (blank($state)) {
                                                Log::warning('El ID de licencia está vacío, limpiando campos snapshot.');
                                                $set('giro_especifico_snapshot', null);
                                                $set('snapshot_lic_tipo', null);
                                                $set('form_direccion_predio', null);
                                                return;
                                            }

                                            try {
                                                $service = app(LicenciaService::class);
                                                $datos = $service->obtenerDatosPorIdLicenciaDirecta($state);

                                                if ($datos) {
                                                    $giroFinal = !empty($datos->GIRO_ESPECIFICOS)
                                                        ? $datos->GIRO_ESPECIFICOS
                                                        : ($datos->GIRO ?? 'GIRO NO DEFINIDO');
                                                    $tipo = str($datos->TIPO_LICENCIA)->upper()->trim()->toString();
                                                    // LOG 2: Éxito
                                                    Log::info('Datos de licencia encontrados con éxito.', [
                                                        'id' => $state,
                                                        'giro' => $giroFinal,
                                                        'tipo' => $tipo
                                                    ]);

                                                    $set('giro_especifico_snapshot', str($giroFinal)->upper() ?? null);
                                                    $set('snapshot_lic_tipo', str($tipo)->upper() ?? null);
                                                    $set('form_direccion_predio', str($datos->LIC_DIRECCION)->upper() ?? null);
                                                } else {
                                                    // LOG 3: No hay resultados
                                                    Log::warning('El servicio no devolvió datos para la licencia ID: ' . $state);

                                                    $set('giro_especifico_snapshot', null);
                                                    $set('snapshot_lic_tipo', null);
                                                    $set('form_direccion_predio', null);
                                                }
                                            } catch (\Exception $e) {
                                                // LOG 4: Error crítico en el servicio o DB
                                                Log::error('Error al consultar el servicio de licencias: ' . $e->getMessage(), [
                                                    'state' => $state,
                                                    'trace' => $e->getTraceAsString()
                                                ]);
                                            }
                                        }),

                                    TextInput::make('giro_especifico_snapshot')
                                        ->label('Giro Específico')
                                        ->placeholder('Se completará al seleccionar la licencia'),

                                    TextInput::make('snapshot_lic_tipo')
                                        ->label('Tipo de Licencia (INDETERMINADA - TEMPORAL)')
                                        ->live()
                                        ->readOnly()
                                        ->placeholder('Se completará al seleccionar la licencia'),

                                    TextInput::make('form_direccion_predio')
                                        ->label('Dirección del Predio Materia a Evaluar')
                                        ->placeholder('Se completará al seleccionar la licencia')
                                        ->columnSpanFull(),
                                ])
                                ->visible(fn($get) => $get('tiene_licencia') === 'si')
                                ->columns(2),

                            Placeholder::make('info_no_licencia')
                                ->label('')
                                ->content('El anuncio se procesará como "Sin Licencia Previa".')
                                ->visible(fn($get) => $get('tiene_licencia') === 'no'),
                        ]),
                    //Step buscar persona legal
                    Step::make('Persona Legal')
                        ->description('Complete la información de la persona legal')
                        ->schema([
                            TextInput::make('snapshot_persona_legal_dni')
                                ->label('DNI/RUC Persona Legal')
                                ->placeholder('Ingrese DNI o RUC y presione Tab')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, $set) {
                                    if (blank($state)) {
                                        $set('snapshot_persona_legal_nombre_completo', null);
                                        $set('snapshot_persona_legal_telefono', null);
                                        $set('snapshot_persona_legal_distrito', null);
                                        return;
                                    }

                                    try {
                                        $service = app(ExpedienteAnuncioService::class);
                                        $persona = $service->getDatosPersonaPorDni($state);

                                        if ($persona) {
                                            $set('snapshot_persona_legal_nombre_completo', str($persona->nomcom)->upper()->trim()->toString());
                                            $set('snapshot_persona_legal_telefono', $persona->numtel ?? null);
                                            $set('snapshot_persona_legal_distrito', str($persona->nomdis)->upper()->trim()->toString() ?? null);

                                            Notification::make()
                                                ->title('Datos de persona legal encontrados')
                                                ->success()
                                                ->send();
                                        } else {
                                            $set('snapshot_persona_legal_nombre_completo', null);
                                            $set('snapshot_persona_legal_telefono', null);

                                            Notification::make()
                                                ->title('No se encontró información')
                                                ->warning()
                                                ->send();
                                        }
                                    } catch (\Exception $e) {
                                        Log::error('Error al buscar persona legal por DNI: ' . $e->getMessage());
                                    }
                                }),
                            TextInput::make('snapshot_persona_legal_nombre_completo')
                                ->label('Nombre Completo Persona Legal'),
                            TextInput::make('snapshot_persona_legal_telefono')
                                ->label('Teléfono Persona Legal'),
                            TextInput::make('snapshot_persona_legal_distrito')
                                ->label('Distrito Persona Legal'),
                        ]),
                    Step::make('Detalles del Anuncio')
                        ->description('Complete la información técnica del anuncio')
                        ->schema([
                            Section::make('Resumen de Expediente y Solicitante')
                                ->collapsed()
                                ->schema([
                                    TextInput::make('n_expediente')
                                        ->label('Número de Expediente')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, $set) {
                                            if (blank($state))
                                                return;

                                            $service = app(ExpedienteAnuncioService::class);
                                            $expediente = $service->getExpedienteByNumero($state);

                                            if ($expediente) {
                                                $set('snapshot_solicitante_dni', $expediente->numdoc);
                                                $set('snapshot_solicitante_nombre_completo', str($expediente->nomcom)->upper());
                                                $set('form_domicilio_fiscal', str($expediente->domfis)->upper());
                                                $set('snapshot_solicitante_telefono', $expediente->exp_telefono);
                                                $set('fecha_expediente', $expediente->EXP_FEC);
                                                $set('folios', $expediente->exp_numfol + 1);
                                            }
                                        }),
                                    TextInput::make('snapshot_solicitante_dni')
                                        ->label('DNI/RUC Solicitante'),
                                    TextInput::make('snapshot_solicitante_nombre_completo')
                                        ->label('Nombre Completo Solicitante'),
                                    TextInput::make('form_domicilio_fiscal')
                                        ->label('Dirección Fiscal'),
                                    TextInput::make('snapshot_solicitante_telefono')
                                        ->label('Teléfono Solicitante'),
                                    TextInput::make('folios')
                                        ->label('Folios')
                                        ->numeric(),
                                    DatePicker::make('fecha_expediente')
                                        ->label('Fecha de Expediente')
                                        ->native(false)
                                        ->displayFormat('d/m/Y'),
                                    Select::make('zonificacion_id')
                                        ->label('Zonificación')
                                        ->options(fn() => Zonificacion::query()
                                            ->get()
                                            ->mapWithKeys(fn($z) => [$z->id => "{$z->siglas} - {$z->descripcion}"]))
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                ])->columns(2),


                            Section::make('Resumen de Licencia')
                                ->collapsed()
                                ->visible(fn($get) => $get('tiene_licencia') === 'si')
                                ->schema([
                                    Select::make('id_licencia')
                                        ->label('Número de Licencia')
                                        ->options(fn() => CertificadoLicenciaFuncionamiento::query()
                                            ->whereNotNull('lic_numlic')
                                            ->where('lic_numlic', '!=', '')
                                            ->orderBy('lic_numlic', 'asc')
                                            ->pluck('lic_numlic', 'lic_id')
                                            ->toArray())
                                        ->searchable()
                                        ->live()
                                        ->afterStateUpdated(function ($state, $set) {
                                            if (blank($state)) {
                                                $set('giro_especifico_snapshot', null);
                                                $set('snapshot_lic_tipo', null);
                                                $set('form_direccion_predio', null);
                                                return;
                                            }

                                            try {
                                                $service = app(LicenciaService::class);
                                                $datos = $service->obtenerDatosPorIdLicenciaDirecta($state);

                                                if ($datos) {
                                                    $giroFinal = !empty($datos->GIRO_ESPECIFICOS) ? $datos->GIRO_ESPECIFICOS : ($datos->GIRO ?? 'GIRO NO DEFINIDO');
                                                    $tipo = str($datos->TIPO_LICENCIA)->upper()->trim()->toString();

                                                    $set('giro_especifico_snapshot', str($giroFinal)->upper() ?? null);
                                                    $set('snapshot_lic_tipo', str($tipo)->upper() ?? null);
                                                    $set('form_direccion_predio', str($datos->LIC_DIRECCION)->upper() ?? null);
                                                }
                                            } catch (\Exception $e) {
                                                Log::error('Error al consultar licencia en paso final: ' . $e->getMessage());
                                            }
                                        }),
                                    TextInput::make('giro_especifico_snapshot')
                                        ->label('Giro Específico'),
                                    TextInput::make('snapshot_lic_tipo')
                                        ->readOnly()
                                        ->label('Tipo de Licencia'),
                                    TextInput::make('form_direccion_predio')
                                        ->label('Dirección del Predio Materia a Evaluar')
                                        ->columnSpanFull(),
                                ])->columns(2),

                            Section::make('Resumen de Persona Legal')
                                ->collapsed()
                                ->schema([
                                    TextInput::make('snapshot_persona_legal_dni')
                                        ->label('DNI/RUC Persona Legal')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, $set) {
                                            if (blank($state))
                                                return;

                                            try {
                                                $service = app(ExpedienteAnuncioService::class);
                                                $persona = $service->getDatosPersonaPorDni($state);

                                                if ($persona) {
                                                    $set('snapshot_persona_legal_nombre_completo', str($persona->nomcom)->upper()->trim()->toString());
                                                    $set('snapshot_persona_legal_telefono', $persona->numtel ?? null);
                                                    $set('snapshot_persona_legal_distrito', str($persona->nomdis)->upper()->trim()->toString() ?? null);
                                                }
                                            } catch (\Exception $e) {
                                                Log::error('Error al buscar persona legal en paso final: ' . $e->getMessage());
                                            }
                                        }),
                                    TextInput::make('snapshot_persona_legal_nombre_completo')
                                        ->label('Nombre Completo Persona Legal'),
                                    TextInput::make('snapshot_persona_legal_telefono')
                                        ->label('Teléfono Persona Legal'),
                                    TextInput::make('snapshot_persona_legal_distrito')
                                        ->label('Distrito Persona Legal'),
                                ])->columns(2),

                            Section::make('Información de Pago')
                                ->schema([
                                    TextInput::make('n_pago')
                                        ->label('N° de Pago'),
                                    //->readOnly()
                                    //->required(),
                                    TextInput::make('monto')
                                        ->label('Monto')
                                        //->readOnly()
                                        //->required()
                                        ->numeric(),
                                ])->columns(2),

                            Section::make('Documentos del Anuncio')
                                ->schema([
                                    Repeater::make('documentos')
                                        ->relationship('documentos')
                                        ->schema([
                                            Select::make('tipo_documento')
                                                ->label('Tipo de Documento')
                                                ->options(TipoDocumento::class)
                                                ->required(),
                                            TextInput::make('n_documento')
                                                ->label('N° de Documento')
                                                ->required(),
                                            DatePicker::make('fecha_emision')
                                                ->label('Fecha de Emisión'),
                                        ])
                                        ->columns(3)
                                        ->columnSpanFull()
                                        ->defaultItems(0)
                                        ->reorderable(false)
                                        ->addActionLabel('Agregar Documento'),
                                ]),


                            Section::make('Información Técnica del Anuncio')
                                ->schema([
                                    TextInput::make('n_anuncio')
                                        ->label('N° Anuncio')
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(6)
                                        ->regex('/^[0-9]{6}$/')
                                        ->validationMessages([
                                            'unique' => 'El número de anuncio ":input" ya está registrado en el sistema.',
                                            'regex' => 'El N° de Anuncio debe contener exactamente 6 números (por ejemplo: 000124).',
                                        ])
                                        ->default(fn() => Anuncios::getSiguienteNumero()),
                                    DatePicker::make('fecha_recepcion_evaluar'),
                                    Select::make('asunto')
                                        ->options(AsuntoAnuncio::class)
                                        ->required()
                                        ->columnSpanFull(),
                                    Select::make('caracteristica_fisica_id')
                                        ->label('Características Físicas')
                                        ->relationship('caracteristicaFisica', 'descripcion')
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    Select::make('tipo_anuncio_id')
                                        ->label('Tipo de Anuncio')
                                        ->relationship('tipoAnuncio', 'descripcion')
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    Select::make('colores')
                                        ->label('Colores')
                                        ->multiple()
                                        ->relationship('colores', 'descripcion')
                                        ->searchable()
                                        ->preload(),
                                    Textarea::make('materiales_descripcion')
                                        ->label('Materiales')
                                        ->columnSpanFull(),

                                ])->columns(2),


                            Section::make('Dimensiones y Más')
                                ->schema([
                                    Textarea::make('descripcion')
                                        ->columnSpanFull(),
                                    TextInput::make('ancho_m')
                                        ->required()
                                        ->numeric()
                                        ->default(0),
                                    TextInput::make('alto_m')
                                        ->required()
                                        ->numeric()
                                        ->default(0),
                                    TextInput::make('espesor_cm')
                                        ->required()
                                        ->numeric()
                                        ->default(0),
                                    TextInput::make('ubicacion_del_anuncio'),
                                    TextInput::make('n_de_caras')
                                        ->required()
                                        ->default(1),
                                    Select::make('dictamen')
                                        ->options(Dictamen::class)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $esProcedente = $state === Dictamen::PROCEDENTE->value || $state === Dictamen::PROCEDENTE;

                                            if ($esProcedente && blank($get('n_anuncio'))) {
                                                $set('n_anuncio', Anuncios::getSiguienteNumero());
                                            } elseif (!$esProcedente) {
                                                $set('n_anuncio', null);
                                            }
                                        }),
                                    Textarea::make('obs')
                                        ->columnSpanFull(),
                                ])->columns(2),

                            Section::make('Estados y Vigencia')
                                ->schema([
                                    Select::make('estado_anuncio')
                                        ->options(EstadoAnuncio::class)
                                        ->default(EstadoAnuncio::VIGENTE->value)
                                        ->required(),

                                    Select::make('derivado_a_legal_user_id')
                                        ->label('Derivado a Legal')
                                        ->relationship('derivadoLegal', 'name')
                                        ->searchable()
                                        ->preload(),
                                    DatePicker::make('fecha_derivado'),
                                    Select::make('vigencia')
                                        ->options(VigenciaAnuncio::class)
                                        ->required()
                                        ->default(VigenciaAnuncio::INDETERMINADA->value),
                                    DatePicker::make('fecha_inicio_vigencia')
                                        ->label('Fecha Inicio Vigencia')
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->live(onBlur: true),

                                    DatePicker::make('fecha_fin_vigencia')
                                        ->label('Fecha Fin Vigencia')
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->minDate(fn($get) => $get('fecha_inicio_vigencia')) // <-- Sin importar la clase Get
                                        ->afterOrEqual('fecha_inicio_vigencia')
                                        ->validationMessages([
                                            'after_or_equal' => 'La fecha de fin no puede ser menor a la fecha de inicio.',
                                        ]),
                                ])->columns(2),

                            Section::make('Resolución Subgerencial')
                                ->schema([
                                    TextInput::make('n_resolucion_subgerencial')
                                        ->label('N° Resolución Subgerencial')
                                        ->placeholder('0410-2026')
                                        ->regex('/^[0-9-]+$/')
                                        ->validationMessages([
                                            'regex' => 'Solo se permiten números y guiones.',
                                        ]),
                                    DatePicker::make('fecha_resolucion_subgerencial')
                                        ->label('Fecha Resolución Subgerencial'),
                                ])->columns(2),

                        ]),
                ])
                    ->startOnStep(fn($record) => $record ? 4 : 1)
                    ->columnSpanFull(),

            ]);
    }
}
