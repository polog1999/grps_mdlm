<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Schemas;

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
                                ->required()
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
                                        $set('snapshot_solicitante_direccion', str($expediente->domfis)->upper());
                                        $set('snapshot_solicitante_telefono', $expediente->exp_telefono);
                                        $set('n_expediente', $expediente->exp_num);
                                        $set('folios', $expediente->exp_numfol + 1);
                                    } else {
                                        Notification::make()
                                            ->title('Expediente no encontrado')
                                            ->body('No se pudo encontrar información con el número ingresado en Oracle.')
                                            ->warning()
                                            ->send();
                                    }
                                }),
                            TextInput::make('snapshot_solicitante_dni')
                                ->label('DNI/RUC Solicitante')
                                ->readonly(),
                            TextInput::make('snapshot_solicitante_nombre_completo')
                                ->label('Nombre Completo Solicitante')
                                ->readonly(),
                            TextInput::make('snapshot_solicitante_direccion')
                                ->label('Dirección Fiscal')
                                ->readonly(),
                            TextInput::make('snapshot_solicitante_telefono')
                                ->label('Teléfono Solicitante')
                                ->readonly(),
                            TextInput::make('folios')
                                ->label('Folios')
                                ->readonly(),
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
                                    $set('snapshot_lic_giro', null);
                                    $set('snapshot_lic_tipo', null);
                                    $set('snapshot_lic_direccion', null);
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
                                                $set('snapshot_lic_giro', null);
                                                $set('snapshot_lic_tipo', null);
                                                $set('snapshot_lic_direccion', null);
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

                                                    $set('snapshot_lic_giro', str($giroFinal)->upper() ?? null);
                                                    $set('snapshot_lic_tipo', str($tipo)->upper() ?? null);
                                                    $set('snapshot_lic_direccion', str($datos->LIC_DIRECCION)->upper() ?? null);
                                                } else {
                                                    // LOG 3: No hay resultados
                                                    Log::warning('El servicio no devolvió datos para la licencia ID: ' . $state);

                                                    $set('snapshot_lic_giro', null);
                                                    $set('snapshot_lic_tipo', null);
                                                    $set('snapshot_lic_direccion', null);
                                                }
                                            } catch (\Exception $e) {
                                                // LOG 4: Error crítico en el servicio o DB
                                                Log::error('Error al consultar el servicio de licencias: ' . $e->getMessage(), [
                                                    'state' => $state,
                                                    'trace' => $e->getTraceAsString()
                                                ]);
                                            }
                                        }),

                                    TextInput::make('snapshot_lic_giro')
                                        ->label('Giro Específico')
                                        ->readonly()
                                        ->placeholder('Se completará al seleccionar la licencia'),

                                    TextInput::make('snapshot_lic_tipo')
                                        ->label('Tipo de Licencia (INDETERMINADA - TEMPORAL)')
                                        ->readonly()
                                        ->live()
                                        ->placeholder('Se completará al seleccionar la licencia'),

                                    DatePicker::make('lic_fecha_inicio')
                                        ->label('Fecha Inicio de Vigencia')
                                        ->required(fn($get) => str($get('snapshot_lic_tipo'))->contains('TEMPORAL'))
                                        ->visible(fn($get) => str($get('snapshot_lic_tipo'))->contains('TEMPORAL'))
                                        ->native(false)
                                        ->displayFormat('d/m/Y'),

                                    DatePicker::make('lic_fecha_fin')
                                        ->label('Fecha Fin de Vigencia')
                                        ->required(fn($get) => str($get('snapshot_lic_tipo'))->contains('TEMPORAL'))
                                        ->visible(fn($get) => str($get('snapshot_lic_tipo'))->contains('TEMPORAL'))
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->afterOrEqual('lic_fecha_inicio'),

                                    TextInput::make('snapshot_lic_direccion')
                                        ->label('Dirección del Predio Materia a Evaluar')
                                        ->readonly()
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
                                        return;
                                    }

                                    try {
                                        $service = app(ExpedienteAnuncioService::class);
                                        $persona = $service->getDatosPersonaPorDni($state);

                                        if ($persona) {
                                            $set('snapshot_persona_legal_nombre_completo', str($persona->nomcom)->upper()->trim()->toString());
                                            $set('snapshot_persona_legal_telefono', $persona->numtel ?? null);

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
                                ->label('Nombre Completo Persona Legal')
                                ->readonly(),
                            TextInput::make('snapshot_persona_legal_telefono')
                                ->label('Teléfono Persona Legal')
                                ->readonly(),
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
                                                $set('snapshot_solicitante_direccion', str($expediente->domfis)->upper());
                                                $set('snapshot_solicitante_telefono', $expediente->exp_telefono);
                                                $set('folios', $expediente->exp_numfol + 1);
                                            }
                                        }),
                                    TextInput::make('snapshot_solicitante_dni')
                                        ->label('DNI/RUC Solicitante'),
                                    TextInput::make('snapshot_solicitante_nombre_completo')
                                        ->label('Nombre Completo Solicitante'),
                                    TextInput::make('snapshot_solicitante_direccion')
                                        ->label('Dirección Fiscal'),
                                    TextInput::make('snapshot_solicitante_telefono')
                                        ->label('Teléfono Solicitante'),
                                    TextInput::make('folios')
                                        ->label('Folios')
                                        ->numeric(),
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
                                                $set('snapshot_lic_giro', null);
                                                $set('snapshot_lic_tipo', null);
                                                $set('snapshot_lic_direccion', null);
                                                return;
                                            }

                                            try {
                                                $service = app(LicenciaService::class);
                                                $datos = $service->obtenerDatosPorIdLicenciaDirecta($state);

                                                if ($datos) {
                                                    $giroFinal = !empty($datos->GIRO_ESPECIFICOS) ? $datos->GIRO_ESPECIFICOS : ($datos->GIRO ?? 'GIRO NO DEFINIDO');
                                                    $tipo = str($datos->TIPO_LICENCIA)->upper()->trim()->toString();

                                                    $set('snapshot_lic_giro', str($giroFinal)->upper() ?? null);
                                                    $set('snapshot_lic_tipo', str($tipo)->upper() ?? null);
                                                    $set('snapshot_lic_direccion', str($datos->LIC_DIRECCION)->upper() ?? null);
                                                }
                                            } catch (\Exception $e) {
                                                Log::error('Error al consultar licencia en paso final: ' . $e->getMessage());
                                            }
                                        }),
                                    TextInput::make('snapshot_lic_giro')
                                        ->label('Giro Específico'),
                                    TextInput::make('snapshot_lic_tipo')
                                        ->label('Tipo de Licencia'),
                                    TextInput::make('snapshot_lic_direccion')
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
                                                }
                                            } catch (\Exception $e) {
                                                Log::error('Error al buscar persona legal en paso final: ' . $e->getMessage());
                                            }
                                        }),
                                    TextInput::make('snapshot_persona_legal_nombre_completo')
                                        ->label('Nombre Completo Persona Legal'),
                                    TextInput::make('snapshot_persona_legal_telefono')
                                        ->label('Teléfono Persona Legal'),
                                ])->columns(2),

                            //section para dos campos, n pago y monto
                            Section::make('Información de Pago')
                                ->schema([
                                    TextInput::make('n_pago')
                                        ->required(),
                                    TextInput::make('monto')
                                        ->required()
                                        ->numeric(),
                                ])->columns(2),
                            Section::make('Información Técnica del Anuncio')
                                ->schema([
                                    TextInput::make('n_anuncio')
                                        ->required(),
                                    Select::make('expediente_id')
                                        ->relationship('expediente', 'id')
                                        ->required(),
                                    DatePicker::make('fecha_recepcion_evaluar'),
                                    Textarea::make('asunto')
                                        ->columnSpanFull(),
                                    Select::make('caracteristica_fisica_id')
                                        ->relationship('caracteristicaFisica', 'id')
                                        ->required(),
                                    Select::make('tipo_anuncio_id')
                                        ->relationship('tipoAnuncio', 'id')
                                        ->required(),
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
                                        ->numeric()
                                        ->default(1),
                                    TextInput::make('dictamen'),
                                    Textarea::make('obs')
                                        ->columnSpanFull(),
                                ])->columns(2),

                            Section::make('Estados y Vigencia')
                                ->schema([
                                    TextInput::make('estado_anuncio')
                                        ->required(),
                                    TextInput::make('derivado_a_legal_user_id')
                                        ->numeric(),
                                    DatePicker::make('fecha_derivado'),
                                    TextInput::make('created_by_user_id')
                                        ->required()
                                        ->numeric(),
                                    TextInput::make('updated_by_user_id')
                                        ->numeric(),
                                    TextInput::make('vigencia')
                                        ->required()
                                        ->default('INDETERMINADA'),
                                    DatePicker::make('fecha_inicio_vigencia'),
                                    DatePicker::make('fecha_fin_vigencia'),
                                ])->columns(2),
                        ]),
                ])
                    ->columnSpanFull(),
            ]);
    }
}
