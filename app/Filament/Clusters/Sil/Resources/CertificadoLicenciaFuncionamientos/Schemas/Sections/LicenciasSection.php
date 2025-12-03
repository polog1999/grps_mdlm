<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections;

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

class LicenciasSection
{
    public static function make(): Section
    {
        return Section::make('Licencias')
            ->description('Información de Licencias')
            ->icon('heroicon-o-exclamation-triangle')
            ->collapsible()
            ->schema([
                TextInput::make('proccodigo')->label('Código de Procedimiento')->maxLength(50)->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                TextInput::make('procnivel')->label('Nivel de Riesgo')->maxLength(100)->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                TextInput::make('nir_id')->label('ID Nivel de Riesgo')->numeric()->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                TextInput::make('nir_descripcion')->label('Descripción Nivel de Riesgo')->maxLength(255)->columnSpanFull()->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                Select::make('tipo_resolucion')->label('Tipo Resolución')->options(self::tiposResolucion())->default(6)->searchable()->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
<<<<<<< HEAD
                TextInput::make('n_resolucion')->label('N° Resolución')->maxLength(100)->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
=======
                TextInput::make('n_resolucion')
                    ->label('N° Resolución')
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Código único de trámite: RESOLUCIÓN-ÁREA'),
>>>>>>> feature/licencias
                DatePicker::make('fecha_resolucion')->label('Fecha Resolución')->displayFormat('d/m/Y')->native(false)->live()->afterStateUpdated(fn($state, callable $set) => $set('fecha_emision', $state))->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                TextInput::make('numero_licencia')->label('Número Licencia')->maxLength(100)->default(fn() => app(NumeroSiguienteLicenciaService::class)->obtenerSiguienteNumeroLicencia())->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                Select::make('tipo_licencia')->label('Tipo Licencia')->options(self::tiposLicencia())->searchable()->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                DatePicker::make('fecha_emision')->label('Fecha Emisión')->displayFormat('d/m/Y')->native(false)->live()->afterStateUpdated(fn($state, callable $set) => $set('fecha_resolucion', $state))->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                //DatePicker::make('fecha_vencimiento')->label('Fecha Vencimiento')->displayFormat('d/m/Y')->native(false)->disabled(fn ($get) => $get('_section_licencias_saved')),
                Radio::make('mype')->label('Mype')->options(['1' => 'Sí', '0' => 'No'])->inline()->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                TextInput::make('compatibilidad')->label('Compatibilidad')->maxLength(255)->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
                TextInput::make('nro_compatibilidad')->label('Nro. Compatibilidad')->maxLength(100)->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),
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

                Select::make('tipo_establecimientos')->label('Tipo Establecimientos')->options(self::tiposEstablecimientos())->searchable()->disabled(fn($get) => $get('_section_licencias_saved'))->dehydrated(),

                Select::make('giros_seleccionar')
                    ->label('Giros encontrados')
                    ->multiple()
                    ->options(function () {
                        $service = app(GiroLicenciaService::class);
                        $giros = $service->buscarGiros('');
                        return $giros->pluck('gir_descripcion', 'gir_id')->toArray();
                    })
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (empty($state)) {
                            $set('tabla_giros', []);
                            return;
                        }

                        $service = app(GiroLicenciaService::class);
                        $todosLosGiros = $service->buscarGiros('');
                        $mapaGiros = $todosLosGiros->pluck('gir_descripcion', 'gir_id')->toArray();

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
            ])
            ->headerActions(SectionHeaderActions::make('licencias'))
            ->columnSpanFull()
            ->columns(3);
    }

    private static function tiposLicencia(): array
    {
        return self::obtenerOpciones(TipoLicenciaService::class, 'getTipoLicencias', 'tli_descripcion', 'tli_id');
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
