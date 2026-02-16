<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use App\Services\Sil\Licencias\TipoLicenciaService;
use App\Services\Sil\Licencias\TipoResolucionService;
use App\Services\Sil\Licencias\NumeroSiguienteLicenciaService;
use App\Services\Sil\Licencias\TipoEstablecimientoService;
use App\Services\Sil\Licencias\GiroLicenciaService;
use Illuminate\Support\Facades\Log;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Actions\SectionHeaderActions;
use App\Services\Sil\Licencias\TipoCentroComercialService;
use App\Services\Sil\Licencias\TipoLocalService;
use App\Models\Giro;
use App\Services\Sil\Licencias\NivelRiesgoService;
class LicenciasSection
{
    public static function make(): Section
    {
        return Section::make('Licencias')
            ->description('Información de Licencias')
            ->icon('heroicon-o-exclamation-triangle')
            ->collapsible()
            ->schema([
                Select::make('nir_id')
                    ->label('Nivel de Riesgo')
                    ->options(self::nivelesRiesgo())
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $service = app(NivelRiesgoService::class);
                            $nivel = $service->getNivelRiesgoPorId($state);
                            if ($nivel) {
                                $set('nir_descripcion', $nivel->nir_descripcion);
                            }
                        } else {
                            $set('nir_descripcion', null);
                        }
                    })
                    ->disabled(fn($get) => $get('_section_licencias_saved'))
                    ->dehydrated(),

                Select::make('tipo_resolucion')->label('Tipo Resolución')->options(self::tiposResolucion())->default(6)->searchable()->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                TextInput::make('n_resolucion')
                    ->label('N° Resolución')
                    ->placeholder('Ej: 1111-2026-MDLM-GDEIP/SPEA')
                    ->maxLength(255)
                    ->required()
                    /*->validationMessages([
                        'required' => 'Documento no disponible > El archivo solicitado no figura en el sistema de trámite. Por favor, cargue el documento para habilitar su consulta.',
                    ])*/
                    //->disabled()
                    ->dehydrated(),
                DatePicker::make('fecha_resolucion')->label('Fecha Resolución')->displayFormat('d/m/Y')->native(false)->live()->afterStateUpdated(fn($state, callable $set) => $set('fecha_emision', $state))->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                Hidden::make('numero_licencia')->default(fn() => app(NumeroSiguienteLicenciaService::class)->obtenerSiguienteNumeroLicencia())->dehydrated(),
                Select::make('tipo_licencia')
                    ->label('Tipo Licencia')
                    ->options(fn(string $operation) => self::tiposLicencia($operation))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state != 2) {
                            $set('fecha_vencimiento', null);
                        }
                    })
                    ->disabled(fn($get) => $get('_section_licencias_saved'))
                    ->dehydrated(),
                DatePicker::make('fecha_emision')->label('Fecha Emisión')->displayFormat('d/m/Y')->native(false)->live()->afterStateUpdated(fn($state, callable $set) => $set('fecha_resolucion', $state))->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                DatePicker::make('fecha_vencimiento')
                    ->label('Fecha Vencimiento')
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->visible(fn($get) => $get('tipo_licencia') == 2)
                    ->disabled(fn($get) => $get('_section_licencias_saved'))
                    ->dehydrated(),
                Radio::make('mype')->label('Mype')->options(['1' => 'Sí', '0' => 'No'])->default('0')->inline()->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                Select::make('compatibilidad')->label('Compatibilidad')->options(['CONFORME' => 'CONFORME', 'NO CONFORME' => 'NO CONFORME'])->searchable()->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                TextInput::make('nro_compatibilidad')
                    ->label('Nro. Compatibilidad')
                    ->placeholder('Ej: 0058-2026-MDLM-GDEIP/SPEA')
                    ->maxLength(255)
                    ->disabled(fn($get) => $get('_section_licencias_saved'))
                    ->dehydrated(),
                /* Comentado temporalmente - Lógica de búsqueda de resoluciones
                Select::make('nro_compatibilidad')
                    ->label('Nro. Compatibilidad')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        if (empty($search)) {
                            return [];
                        }
                        $service = app(\App\Services\Sil\CertificadoInspeccion\ResolucionService::class);
                        $resoluciones = $service->obtenerResoluciones($search);
                        return array_combine($resoluciones, $resoluciones);
                    })
                    ->getOptionLabelUsing(fn($value): ?string => $value)
                    ->disabled(fn($get) => $get('_section_licencias_saved'))
                    ->dehydrated(),
                */
                DatePicker::make('fecha_compatibilidad')->label('Fecha Compatibilidad')->displayFormat('d/m/Y')->native(false)->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),

                Select::make('horario_atencion')
                    ->label('Horario Atención')
                    ->options(self::horariosAtencion())
                    ->searchable()
                    ->live() // live() por defecto detecta el cambio ("onChange")
                    ->afterStateUpdated(function ($state, callable $set) {
                        // CORRECCIÓN: Comparar con las llaves (keys) del array
                        if ($state === 'normal') {
                            $set('hora_inicio', '07:00');
                            $set('hora_fin', '23:00');
                        } elseif ($state === 'extraordinario') {
                            $set('hora_inicio', '23:00');
                            $set('hora_fin', '03:00');
                        } elseif ($state === '24_horas') {
                            $set('hora_inicio', '00:00');
                            $set('hora_fin', '23:59');
                        }
                    })
                    ->disabled(fn($get) => $get('_section_licencias_saved'))
                    ->dehydrated(),
                TimePicker::make('hora_inicio')->label('Hora Inicio')->seconds(false)->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                TimePicker::make('hora_fin')->label('Hora Fin')->seconds(false)->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),

                TextInput::make('direccion')->label('Dirección')->maxLength(255)->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),

                Select::make('tipo_establecimientos')
                    ->label('Tipo Establecimientos')
                    ->options(self::tiposEstablecimientos())
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Siempre establecer estos campos como null
                        $set('centro_comercial', null);
                        $set('tipo_local', null);
                        $set('local', null);
                        $set('observaciones_local', null);
                    })
                    ->disabled(fn($get) => $get('_section_licencias_saved'))
                    ->dehydrated(),

                /*
            Select::make('centro_comercial')
                ->label('Centro Comercial')
                ->options(function () {
                    $service = app(TipoCentroComercialService::class);
                    $centros = $service->getTipoCentroComercial();

                    return $centros->pluck('cec_descripcion', 'cec_id')
                        ->filter()
                        ->toArray();
                })
                ->hidden()
                ->disabled(fn($get) => $get('_section_licencias_saved'))
                ->dehydrated(),

            Select::make('tipo_local')
                ->label('Tipo Local')
                ->options(function () {
                    $service = app(TipoLocalService::class);
                    $locales = $service->getTipoLocal();

                    return $locales->pluck('tlo_descripcion', 'tlo_id')
                        ->filter()
                        ->toArray();
                })
                ->hidden()
                ->disabled(fn($get) => $get('_section_licencias_saved'))
                ->dehydrated(),

            TextInput::make('local')
                ->label('Local')
                ->hidden()
                ->disabled(fn($get) => $get('_section_licencias_saved'))
                ->dehydrated(),

            TextInput::make('observaciones_local')
                ->label('Observaciones')
                ->hidden()
                ->disabled(fn($get) => $get('_section_licencias_saved'))
                ->dehydrated(),
*/
                Select::make('giros_seleccionar')
                    ->label('Giros encontrados')
                    ->multiple()
                    ->options(function (\Livewire\Component $livewire) {
                        $clasesSinFiltro = [
                            \App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages\TransferirCertificadoLicenciaFuncionamiento::class,
                            \App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages\DuplicateCertificadoLicenciaFuncionamiento::class,
                            \App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages\CesionarioCertificadoLicenciaFuncionamiento::class,
                        ];

                        $query = Giro::query();

                        if (!in_array(get_class($livewire), $clasesSinFiltro)) {
                            $query->where('gir_usos', true);
                        }

                        return $query->get()
                            ->mapWithKeys(function ($giro) {
                                // Aquí concatenamos el código y la descripción
                                $label = "{$giro->gir_descripcion} - {$giro->gir_girocodi} ";

                                // Retornamos [ID => Etiqueta]
                                return [$giro->gir_id => $label];
                            })
                            ->toArray();
                    })
                    ->searchable() // Al ser searchable, buscará tanto por código como por descripción gracias al cambio anterior
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (empty($state)) {
                            $set('tabla_giros', []);
                            return;
                        }

                        // Opcional: Si también quieres que en la tabla de abajo se vea el código, 
                        // aplica la misma lógica de concatenación aquí.
                        $todosLosGiros = Giro::all();
                        $mapaGiros = $todosLosGiros->mapWithKeys(function ($giro) {
                            return [$giro->gir_id => "{$giro->gir_girocodi} - {$giro->gir_descripcion}"];
                        })->toArray();

                        $filas = [];
                        foreach ((array) $state as $giroId) {
                            if (isset($mapaGiros[$giroId])) {
                                $filas[] = [
                                    'giro' => $mapaGiros[$giroId],
                                    'giro_especifico' => '',
                                ];
                            }
                        }
                        $set('tabla_giros', $filas);
                    })
                    ->columnSpanFull()
                    ->disabled(fn($get) => $get('_section_licencias_saved'))
                    ->dehydrated(),

                Repeater::make('tabla_giros')
                    ->label('Giros Seleccionados')
                    ->schema([
                        TextInput::make('giro')
                            ->label('Giro')
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('giro_especifico')
                            ->label('Giro Específico')
                            ->placeholder('Ingrese especificación...')
                            ->disabled(fn($get) => $get('../../_section_licencias_saved'))
                            ->dehydrated(),
                    ])
                    ->columns(2)
                    ->defaultItems(0)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->columnSpanFull()
                    ->disabled(fn($get) => $get('_section_licencias_saved'))
                    ->dehydrated(),
                Textarea::make('observaciones')->label('Observaciones')->rows(3)->columnSpanFull()->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                Hidden::make('proccodigo')->label('Código de Procedimiento')
                    //->maxLength(50)
                    ->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                Hidden::make('procnivel')->label('Nivel de Riesgo')
                    //->maxLength(100)
                    ->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),

                Hidden::make('nir_descripcion')
                    ->label('Descripción Nivel de Riesgo')
                    //->maxLength(255)
                    ->disabled()
                    ->dehydrated()
                    ->columnSpanFull(),

            ])
            ->columnSpanFull()
            ->columns(3);
    }

    private static function tiposLicencia(string $operation = 'create'): array
    {
        try {
            $service = app(TipoLicenciaService::class);
            $items = $service->getTipoLicencias();

            // Filtrar opciones que contengan "CESIONARIO" solo al CREAR nueva licencia
            if ($operation === 'create') {
                $items = $items->filter(function ($item) {
                    $descripcion = strtoupper($item->tli_descripcion ?? '');
                    return strpos($descripcion, 'CESIONARIO') === false;
                });
            }

            return $items->pluck('tli_descripcion', 'tli_id')->toArray();
        } catch (\Exception $e) {
            Log::error("Error obteniendo tipos de licencia: " . $e->getMessage());
            return [];
        }
    }
    private static function tiposResolucion(): array
    {
        return self::obtenerOpciones(TipoResolucionService::class, 'getTipoResoluciones', 'tir_descripcion', 'tir_id');
    }
    private static function horariosAtencion(): array
    {
        return ['normal' => 'Normal', 'extraordinario' => 'Extra', '24_horas' => '24H'];
    }
    private static function tiposEstablecimientos(): array
    {
        return self::obtenerOpciones(TipoEstablecimientoService::class, 'getTipoEstablecimiento', 'tes_descripcion', 'tes_id');
    }


    private static function nivelesRiesgo(): array
    {
        return self::obtenerOpciones(NivelRiesgoService::class, 'getNivelesRiesgo', 'nir_descripcion', 'nir_id');
    }

    private static function obtenerOpciones(string $serviceClass, string $method, string $labelField, string $valueField): array
    {
        try {
            $service = app($serviceClass);
            $items = $service->$method();
            return $items->pluck($labelField, $valueField)->toArray();
        } catch (\Exception $e) {
            Log::error("Error obteniendo opciones de {$serviceClass}: " . $e->getMessage());
            return [];
        }
    }
}
