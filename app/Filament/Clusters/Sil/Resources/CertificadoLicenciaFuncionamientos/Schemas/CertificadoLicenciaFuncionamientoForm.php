<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas;

use Filament\Forms\Components\TextInput;    
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;
use App\Services\Sil\Licencias\TipoLicenciaService;
use App\Services\Sil\Licencias\TipoResolucionService;
use App\Services\Sil\Licencias\NumeroSiguienteLicenciaService;
use App\Services\Sil\Licencias\TipoEstablecimientoService;
use App\Services\Sil\Licencias\GiroLicenciaService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Http;

class CertificadoLicenciaFuncionamientoForm
{

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Wizard::make([
                self::busquedaStep(),
                self::datosCompletosStep(),
            ])->columnSpanFull()
        ]);
    }


    private static function busquedaStep(): Step
    {
        return Step::make('Búsqueda')
            ->description('Ingrese el número de expediente')
            ->icon('heroicon-o-magnifying-glass')
            ->schema([
                TextInput::make('lic_expnum')
                    ->label('Número de Expediente')
                    ->placeholder('Ej: E-06073-2025')
                    ->required()
                    ->maxLength(50)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => self::buscarExpediente($state, $set))
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'text-center']),
            ]);
    }

    private static function buscarExpediente(?string $state, callable $set): void
    {
        if (empty($state)) return;

        try {
            // Asegúrate que este servicio esté bien inyectado
            $service = app(CertificadoLincenciaFuncionamientoService::class);
            $result = $service->obtenerDatosCompletosParaRegistrarPorExpediente($state);

            if ($result && isset($result['expediente'])) {
                $set('_datos_completos', $result);
                self::autocompletarDatos($result, $set);
                self::notify('success', 'Datos recuperados exitosamente', 'Se encontraron los datos del expediente.');
            } else {
                self::notify('warning', 'No se encontraron datos', 'No se encontró información para el expediente ingresado.');
            }
        } catch (\Throwable $e) {
            Log::error('Error recuperando datos: ' . $e->getMessage());
            self::notify('danger', 'Error', 'Ocurrió un error al recuperar los datos.');
        }
    }

    private static function datosCompletosStep(): Step
    {
        return Step::make('Datos Completos')
            ->description('Revise y complete la información')
            ->icon('heroicon-o-document-text')
            ->schema([
                self::expedienteSection(),
                self::catastroSection(),
                self::licenciasSection(),
            ]);
    }

    private static function expedienteSection(): Section
    {
        return Section::make('Expediente')
            ->description('Datos del expediente administrativo')
            ->icon('heroicon-o-archive-box')
            ->collapsible()
            ->schema([
                TextInput::make('exp_num')->label('Número de Expediente')->disabled()->dehydrated(),
                DatePicker::make('exp_fec')->label('Fecha de Expediente')->displayFormat('d/m/Y')->native(false)->disabled(fn ($get) => $get('_section_expediente_saved')),
                TextInput::make('exp_nomrec')->label('Nombre/Razón Social')->maxLength(255)->columnSpanFull()->disabled(fn ($get) => $get('_section_expediente_saved')),
                TextInput::make('numdoc')->label('RUC/DNI')->maxLength(22)->numeric()->disabled(fn ($get) => $get('_section_expediente_saved')),
                TextInput::make('numtel')->label('Teléfono')->maxLength(50)->numeric()->disabled(fn ($get) => $get('_section_expediente_saved')),
                TextInput::make('correo')->label('Correo Electrónico')->email()->maxLength(255)->disabled(fn ($get) => $get('_section_expediente_saved')),
                TextInput::make('domfis')->label('Domicilio Fiscal')->maxLength(255)->columnSpanFull()->disabled(fn ($get) => $get('_section_expediente_saved')),
            ])
            ->headerActions([
                self::guardarAction('expediente'),
                self::editarAction('expediente'),
            ])
            ->columnSpanFull()
            ->columns(2);
    }

    private static function catastroSection(): Section
    {
        return Section::make('Catastro')
            ->description('Información catastral del predio')
            ->icon('heroicon-o-map-pin')
            ->collapsible()
            ->schema([
                TextInput::make('coduca')->label('Código Catastral')->maxLength(50)->extraAttributes(['inputmode' => 'numeric'])->rule('regex:/^[0-9]+$/')->disabled(fn ($get) => $get('_section_catastro_saved')),
                TextInput::make('codpredio')->label('Código Predial')->maxLength(50)->rule('regex:/^[0-9]+$/')->disabled(fn ($get) => $get('_section_catastro_saved')),
                
                // AQUI: Agregamos onBlur: true para asegurar que el evento se dispare al salir
                TextInput::make('descurb')->label('Urbanización')->maxLength(255)->columnSpanFull()
                    ->live(onBlur: true) 
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved')),
                
                TextInput::make('via_completa')->label('Vía')->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved')),
                
                TextInput::make('numvia')->label('Número')->maxLength(20)->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved')),
                
                TextInput::make('intdpto')->label('Dpto.')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved')),
                
                TextInput::make('blockedif')->label('Bloque')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved')),
                
                TextInput::make('mz')->label('Manzana')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved')),
                
                TextInput::make('lote')->label('Lote')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved')),
                
                TextInput::make('zonificacion')->label('Zonificación')->maxLength(100)->disabled(fn ($get) => $get('_section_catastro_saved')),
                TextInput::make('area_economica')->label('Área Económica')->numeric()->step(0.01)->suffix('m²')->formatStateUsing(fn ($state) => $state ? number_format((float)$state, 2, '.', '') : null)->extraInputAttributes(['onchange' => "if(this.value) this.value = parseFloat(this.value).toFixed(2)"])->disabled(fn ($get) => $get('_section_catastro_saved')),
            ])
            ->headerActions([
                self::guardarAction('catastro'),
                self::editarAction('catastro'),
            ])
            ->columnSpanFull()
            ->columns(3);
    }

    private static function licenciasSection(): Section
    {
        return Section::make('Licencias')
            ->description('Información de Licencias')
            ->icon('heroicon-o-exclamation-triangle')
            ->collapsible()
            ->schema([
                // ... (Resto de tus inputs de Nivel de Riesgo y Resolución igual) ...
                TextInput::make('proccodigo')->label('Código de Procedimiento')->maxLength(50)->disabled(fn ($get) => $get('_section_licencias_saved')),
                TextInput::make('procnivel')->label('Nivel de Riesgo')->maxLength(100)->disabled(fn ($get) => $get('_section_licencias_saved')),
                TextInput::make('nir_id')->label('ID Nivel de Riesgo')->numeric()->disabled(fn ($get) => $get('_section_licencias_saved')),
                TextInput::make('nir_descripcion')->label('Descripción Nivel de Riesgo')->maxLength(255)->columnSpanFull()->disabled(fn ($get) => $get('_section_licencias_saved')),
                Select::make('tipo_resolucion')->label('Tipo Resolución')->options(self::tiposResolucion())->default(6)->searchable()->disabled(fn ($get) => $get('_section_licencias_saved')),
                TextInput::make('n_resolucion')->label('N° Resolución')->maxLength(100)->disabled(fn ($get) => $get('_section_licencias_saved')),
                DatePicker::make('fecha_resolucion')->label('Fecha Resolución')->displayFormat('d/m/Y')->native(false)->live()->afterStateUpdated(fn ($state, callable $set) => $set('fecha_emision', $state))->disabled(fn ($get) => $get('_section_licencias_saved')),
                TextInput::make('numero_licencia')->label('Número Licencia')->maxLength(100)->default(fn () => app(NumeroSiguienteLicenciaService::class)->obtenerSiguienteNumeroLicencia())->disabled(fn ($get) => $get('_section_licencias_saved')),
                Select::make('tipo_licencia')->label('Tipo Licencia')->options(self::tiposLicencia())->searchable()->disabled(fn ($get) => $get('_section_licencias_saved')),
                DatePicker::make('fecha_emision')->label('Fecha Emisión')->displayFormat('d/m/Y')->native(false)->live()->afterStateUpdated(fn ($state, callable $set) => $set('fecha_resolucion', $state))->disabled(fn ($get) => $get('_section_licencias_saved')),
                //DatePicker::make('fecha_vencimiento')->label('Fecha Vencimiento')->displayFormat('d/m/Y')->native(false)->disabled(fn ($get) => $get('_section_licencias_saved')),
                Radio::make('mype')->label('Mype')->options(['1' => 'Sí', '0' => 'No'])->inline()->disabled(fn ($get) => $get('_section_licencias_saved')),
                TextInput::make('compatibilidad')->label('Compatibilidad')->maxLength(255)->disabled(fn ($get) => $get('_section_licencias_saved')),
                TextInput::make('nro_compatibilidad')->label('Nro. Compatibilidad')->maxLength(100)->disabled(fn ($get) => $get('_section_licencias_saved')),
                DatePicker::make('fecha_compatibilidad')->label('Fecha Compatibilidad')->displayFormat('d/m/Y')->native(false)->disabled(fn ($get) => $get('_section_licencias_saved')),
                
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
                        } 
                        elseif ($state === 'extraordinario') { 
                            $set('hora_inicio', '23:00'); 
                            $set('hora_fin', '03:00'); 
                        } 
                        elseif ($state === '24_horas') { 
                            $set('hora_inicio', '00:00'); 
                            $set('hora_fin', '23:59'); 
                        }
                    })
                     ->disabled(fn ($get) => $get('_section_licencias_saved')),
                TimePicker::make('hora_inicio')->label('Hora Inicio')->seconds(false)->disabled(fn ($get) => $get('_section_licencias_saved')),
                TimePicker::make('hora_fin')->label('Hora Fin')->seconds(false)->disabled(fn ($get) => $get('_section_licencias_saved')),
                
                TextInput::make('direccion')->label('Dirección')->maxLength(255)->disabled(fn ($get) => $get('_section_licencias_saved')),
                
                Select::make('tipo_establecimientos')->label('Tipo Establecimientos')->options(self::tiposEstablecimientos())->searchable()->disabled(fn ($get) => $get('_section_licencias_saved')),
                Select::make('giros_seleccionados'),
                    
                Textarea::make('descripcion_giros')->label('Descripción Giros')->rows(3)->columnSpanFull()->disabled(fn ($get) => $get('_section_licencias_saved')),
                Textarea::make('observaciones')->label('Observaciones')->rows(3)->columnSpanFull()->disabled(fn ($get) => $get('_section_licencias_saved')),
            ])
            ->headerActions([
                self::guardarAction('licencias'),
                self::editarAction('licencias'),
            ])
            ->columnSpanFull()
            ->columns(3);
    }
    


    private static function guardarAction(string $section): Action
    {
        return Action::make("guardar_{$section}")
            ->label('Guardar')
            ->icon('heroicon-o-check')
            ->color('success')
            ->visible(fn ($get) => $get("_section_{$section}_saved") !== true)
            ->action(function ($set) use ($section) {
                $set("_section_{$section}_saved", true);
                self::notify('success', ucfirst($section) . ' Guardado', 'Los datos han sido guardados.');
            });
    }

    private static function editarAction(string $section): Action
    {
        return Action::make("editar_{$section}")
            ->label('Editar')
            ->icon('heroicon-o-pencil')
            ->color('warning')
            ->visible(fn ($get) => $get("_section_{$section}_saved") === true)
            ->action(function ($set) use ($section) {
                $set("_section_{$section}_saved", false);
                self::notify('info', 'Modo edición', 'Ahora puede editar los datos.');
            });
    }

    private static function autocompletarDatos(array $data, callable $set): void
    {
        // Expediente
        if (isset($data['expediente'])) {
            $exp = (array) $data['expediente'];
            foreach (['exp_num', 'exp_fec', 'exp_nomrec', 'numdoc', 'numtel', 'correo', 'domfis'] as $field) {
                $set($field, $exp[$field] ?? null);
            }
        }

        // Catastro
        if (!empty($data['catastro'])) {
            $cat = (array) $data['catastro'];
            foreach (['coduca', 'codpredio', 'descurb', 'via_completa', 'numvia', 'intdpto', 'blockedif', 'mz', 'lote', 'zonificacion', 'area_economica'] as $field) {
                $set($field, $cat[$field] ?? null);
            }


            $componentes = [
                'via_completa' => '',
                'descurb' => '',
                'numvia' => 'NRO',
                'intdpto' => 'DPTO',
                'blockedif' => 'BLQ',
                'mz' => 'MZ',
                'lote' => 'LT',
            ];
            $parts = [];
            foreach ($componentes as $campo => $prefijo) {
                $valor = trim($cat[$campo] ?? '');
                if (!empty($valor)) {
                    $parts[] = trim($prefijo . ' ' . strtoupper($valor));
                }
            }
            $direccionCalculada = implode(' ', $parts);
            $set('direccion', $direccionCalculada);
            // -----------------------------------------------------
        }

        // Nivel de Riesgo
        if (!empty($data['nivel_riesgo'])) {
            $nr = (array) $data['nivel_riesgo'];
            $set('proccodigo', $nr['proccodigo'] ?? null);
            $set('procnivel', $nr['procnivel'] ?? null);
            
            if (isset($nr['nivel_riesgo'])) {
                $nrd = (array) $nr['nivel_riesgo'];
                $set('nir_id', $nrd['nir_id'] ?? null);
                $set('nir_descripcion', $nrd['nir_descripcion'] ?? null);
            }
        }
    }

    // ... (Tus funciones auxiliares notify, tiposLicencia, etc, se mantienen igual) ...
    private static function notify(string $type, string $title, string $body): void
    {
        Notification::make()->$type()->title($title)->body($body)->send();
    }
    private static function tiposLicencia(): array
    {
        return self::obtenerOpciones(TipoLicenciaService::class, 'getTipoLicencias', 'tli_descripcion', 'tli_id');
    }   
    private static function tiposResolucion(): array
    {
        return self::obtenerOpciones(TipoResolucionService::class, 'getTipoResoluciones', 'tir_descripcion', 'tir_id');
    }
    private static function horariosAtencion(): array { return ['normal'=>'Normal', 'extraordinario'=>'Extra', '24_horas'=>'24H']; }
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
    /**
     * Esta función se ejecuta SOLO cuando el usuario escribe manualmente
     */
    private static function actualizarDireccion(callable $get, callable $set): void
    {
        // Log::info se ve en storage/logs/laravel.log incluso en producción
        Log::info('Iniciando actualización de dirección...');

        $componentes = [
            'via_completa' => 'AV',
            'descurb' => 'URB',
            'numvia' => 'NRO',
            'intdpto' => 'DPTO',
            'blockedif' => 'BLQ',
            'mz' => 'MZ',
            'lote' => 'LT',
        ];

        $parts = [];
        foreach ($componentes as $campo => $prefijo) {
            // Usamos $get para obtener el estado actual del formulario
            $valor = trim($get($campo) ?? '');
            
            if (!empty($valor)) {
                $parts[] = $prefijo . ' ' . strtoupper($valor);
            }
        }

        $direccion = implode(' ', $parts);
        
        Log::info('Dirección calculada: ' . $direccion);
        
        $set('direccion', $direccion);
    }
}