<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamiento;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Filament\Schemas\Components\Fieldset;


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
                    ->afterStateUpdated(function (?string $state, callable $set, callable $get) {
                        if (empty($state)) return;

                        try {
                            $service = app(CertificadoLincenciaFuncionamiento::class);
                            $result = $service->obtenerDatosCompletosParaRegistrarPorExpediente($state);

                            if ($result && isset($result['expediente'])) {
                                // Guardar datos completos en el estado
                                $set('_datos_completos', $result);
                                
                                // Autocompletar todos los campos
                                self::autocompletarTodosLosDatos($result, $set);

                                // Mostrar notificación de éxito
                                Notification::make()
                                    ->success()
                                    ->title('Datos recuperados exitosamente')
                                    ->body('Se encontraron los datos del expediente.')
                                    ->send();
                            } else {
                                Notification::make()
                                    ->warning()
                                    ->title('No se encontraron datos')
                                    ->body('No se encontró información para el expediente ingresado.')
                                    ->send();
                            }
                        } catch (\Throwable $e) {
                            Log::error('Error recuperando datos completos: ' . $e->getMessage());
                            Notification::make()
                                ->danger()
                                ->title('Error')
                                ->body('Ocurrió un error al recuperar los datos.')
                                ->send();
                        }
                    })
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'text-center']),
            ])
            ->columns(1);
    }

    private static function datosCompletosStep(): Step
    {
        return Step::make('Datos Completos')
            ->description('Revise y complete la información')
            ->icon('heroicon-o-document-text')
            ->schema([
                // Sección Expediente
                Section::make('Expediente')
                    ->description('Datos del expediente administrativo')
                    ->icon('heroicon-o-archive-box')
                    ->collapsible()
                    ->collapsed(false)
                    ->schema([
                        TextInput::make('exp_num')->label('Número de Expediente')->disabled()->dehydrated(),
                        DatePicker::make('exp_fec')->label('Fecha de Expediente')->displayFormat('d/m/Y')->native(false)->disabled(fn (callable $get) => $get('_section_expediente_saved') === true),
                        TextInput::make('exp_nomrec')->label('Nombre/Razón Social')->maxLength(255)->columnSpanFull()->disabled(fn (callable $get) => $get('_section_expediente_saved') === true),
                        TextInput::make('numdoc')->label('RUC/DNI')->maxLength(20)->disabled(fn (callable $get) => $get('_section_expediente_saved') === true),
                        TextInput::make('numtel')->label('Teléfono')->maxLength(50)->disabled(fn (callable $get) => $get('_section_expediente_saved') === true),
                        TextInput::make('correo')->label('Correo Electrónico')->email()->maxLength(255)->disabled(fn (callable $get) => $get('_section_expediente_saved') === true),
                        TextInput::make('domfis')->label('Domicilio Fiscal')->maxLength(255)->columnSpanFull()->disabled(fn (callable $get) => $get('_section_expediente_saved') === true),
                        ])
                    ->headerActions([
                            Action::make('guardar_expediente')
                                ->label('Guardar')
                                ->icon('heroicon-o-check')
                                ->color('success')
                                ->visible(fn (callable $get) => $get('_section_expediente_saved') !== true)
                                ->action(function (callable $set) {
                                    $set('_section_expediente_saved', true);
                                    Notification::make()
                                        ->success()
                                        ->title('Expediente Guardado')
                                        ->body('Los datos de expediente han sido guardados.')
                                        ->send();
                                }),
                            Action::make('editar_expediente')
                                ->label('Editar')
                                ->icon('heroicon-o-pencil')
                                ->color('warning')
                                ->visible(fn (callable $get) => $get('_section_expediente_saved') === true)
                                ->action(function (callable $set) {
                                    $set('_section_expediente_saved', false);
                                    Notification::make()
                                        ->info()
                                        ->title('Modo edición')
                                        ->body('Ahora puede editar los datos de expediente.')
                                        ->send();
                                }),
                     ])
                    ->columnSpanFull()
                    ->columns(2),

                // Sección Catastro
                Section::make('Catastro')
                    ->description('Información catastral del predio')
                    ->icon('heroicon-o-map-pin')
                    ->collapsible()
                    ->collapsed(false)
                    ->schema([

                        TextInput::make('coduca')->label('Código Catastral')->maxLength(50)->disabled(fn ($get) => $get('_section_catastro_saved')),
                        TextInput::make('codpredio')->label('Código Predial')->maxLength(50)->disabled(fn ($get) => $get('_section_catastro_saved')),
                        TextInput::make('descurb')->label('Urbanización')->maxLength(255)->columnSpanFull()->disabled(fn ($get) => $get('_section_catastro_saved')),
                        TextInput::make('via_completa')->label('Vía')->maxLength(255)->disabled(fn ($get) => $get('_section_catastro_saved')),
                        TextInput::make('numvia')->label('Número')->maxLength(20)->disabled(fn ($get) => $get('_section_catastro_saved')),
                        TextInput::make('intdpto')->label('Dpto.')->maxLength(20)->disabled(fn ($get) => $get('_section_catastro_saved')),
                        TextInput::make('blockedif')->label('Bloque')->maxLength(20)->disabled(fn ($get) => $get('_section_catastro_saved')),
                        TextInput::make('mz')->label('Manzana')->maxLength(20)->disabled(fn ($get) => $get('_section_catastro_saved')),
                        TextInput::make('lote')->label('Lote')->maxLength(20)->disabled(fn ($get) => $get('_section_catastro_saved')),
                        TextInput::make('zonificacion')->label('Zonificación')->maxLength(100)->disabled(fn ($get) => $get('_section_catastro_saved')),
                        TextInput::make('area_economica')->label('Área Económica')->numeric()->suffix('m²')->disabled(fn ($get) => $get('_section_catastro_saved')),

                    ])
                    ->headerActions([

                        Action::make('guardar_catastro')
                            ->label('Guardar')
                            ->icon('heroicon-o-check')
                            ->color('success')
                            ->visible(fn ($get) => $get('_section_catastro_saved') !== true)
                            ->action(function (callable $set) {
                                $set('_section_catastro_saved', true);
                                Notification::make()->success()->title('Catastro Guardado')->body('Los datos han sido guardados')->send();
                            }),

                        Action::make('editar_catastro')
                            ->label('Editar')
                            ->icon('heroicon-o-pencil')
                            ->color('warning')
                            ->visible(fn ($get) => $get('_section_catastro_saved') === true)
                            ->action(function (callable $set) {
                                $set('_section_catastro_saved', false);
                                Notification::make()->info()->title('Modo edición')->body('Ahora puede editar los datos')->send();
                            }),

                    ])
                    ->columnSpanFull()
                    ->columns(3),



                // Sección Licencias
                Section::make('Licencias')
                    ->description('Información de Licencias')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->collapsible()
                    ->collapsed(false)
                    ->schema([
                        // Campos de Nivel de Riesgo (Autocompletados)
                        TextInput::make('proccodigo')
                            ->label('Código de Procedimiento')
                            ->maxLength(50)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        TextInput::make('procnivel')
                            ->label('Nivel de Riesgo')
                            ->maxLength(100)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        TextInput::make('nir_id')
                            ->label('ID Nivel de Riesgo')
                            ->numeric()
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        TextInput::make('nir_descripcion')
                            ->label('Descripción Nivel de Riesgo')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        // Campos de Licencia
                        // Fila 1
                        Select::make('tipo_resolucion')
                            ->label('Tipo Resolución')
                            ->options([
                                'resolucion_gerencial' => 'Resolución Gerencial',
                                'resolucion_alcaldia' => 'Resolución de Alcaldía',
                                'resolucion_subgerencia' => 'Resolución de Subgerencia',
                            ])
                            ->searchable()
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        TextInput::make('n_resolucion')
                            ->label('N° Resolución')
                            ->maxLength(100)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        DatePicker::make('fecha_resolucion')
                            ->label('Fecha Resolución')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        // Fila 2
                        TextInput::make('numero_licencia')
                            ->label('Número Licencia')
                            ->maxLength(100)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        Select::make('tipo_licencia')
                            ->label('Tipo Licencia')
                            ->options([
                                'temporal' => 'Temporal',
                                'indefinida' => 'Indefinida',
                                'provisional' => 'Provisional',
                            ])
                            ->searchable()
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        DatePicker::make('fecha_emision')
                            ->label('Fecha Emisión')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        // Fila 3
                        DatePicker::make('fecha_vencimiento')
                            ->label('Fecha Vencimiento')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        Radio::make('mype')
                            ->label('Mype')
                            ->options([
                                '1' => 'Sí',
                                '0' => 'No',
                            ])
                            ->inline()
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        TextInput::make('compatibilidad')
                            ->label('Compatibilidad')
                            ->maxLength(255)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        // Fila 4
                        TextInput::make('nro_compatibilidad')
                            ->label('Nro. Compatibilidad')
                            ->maxLength(100)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        DatePicker::make('fecha_compatibilidad')
                            ->label('Fecha Compatibilidad')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        Select::make('horario_atencion')
                            ->label('Horario Atención')
                            ->options([
                                'diurno' => 'Diurno',
                                'nocturno' => 'Nocturno',
                                'mixto' => 'Mixto',
                                '24_horas' => '24 Horas',
                            ])
                            ->searchable()
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        // Fila 5
                        TimePicker::make('hora_inicio')
                            ->label('Hora Inicio')
                            ->seconds(false)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        TimePicker::make('hora_fin')
                            ->label('Hora Fin')
                            ->seconds(false)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        TextInput::make('direccion')
                            ->label('Dirección')
                            ->maxLength(255)
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        // Fila 6
                        Select::make('tipo_establecimientos')
                            ->label('Tipo Establecimientos')
                            ->options([
                                'comercial' => 'Comercial',
                                'industrial' => 'Industrial',
                                'servicios' => 'Servicios',
                                'mixto' => 'Mixto',
                            ])
                            ->searchable()
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        Textarea::make('descripcion_giros')
                            ->label('Descripción Giros')
                            ->rows(3)
                            ->columnSpanFull()
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->rows(3)
                            ->columnSpanFull()
                            ->disabled(fn ($get) => $get('_section_licencias_saved') === true),
                        
                        
                    ])
                    ->headerActions([
                        Action::make('guardar_licencias')
                            ->label('Guardar')
                            ->icon('heroicon-o-check')
                            ->color('success')
                            ->visible(fn (callable $get) => $get('_section_licencias_saved') !== true)
                            ->action(function (callable $set) {
                                $set('_section_licencias_saved', true);
                                Notification::make()
                                    ->success()
                                    ->title('Licencias Guardado')
                                    ->body('Los datos de licencias han sido guardados.')
                                    ->send();
                            }),
                        
                        Action::make('editar_licencias')
                            ->label('Editar')
                            ->icon('heroicon-o-pencil')
                            ->color('warning')
                            ->visible(fn (callable $get) => $get('_section_licencias_saved') === true)
                            ->action(function (callable $set) {
                                $set('_section_licencias_saved', false);
                                Notification::make()
                                    ->info()
                                    ->title('Modo edición')
                                    ->body('Ahora puede editar los datos de licencias.')
                                    ->send();
                            }),
                    ])
                    ->columnSpanFull()
                    ->columns(3),
            ])
            ->columns(1);
    }

    private static function autocompletarTodosLosDatos(array $data, callable $set): void
    {
        // Datos del Expediente
        if (isset($data['expediente'])) {
            $expediente = (array) $data['expediente'];
            $set('exp_num', $expediente['exp_num'] ?? null);
            $set('exp_fec', $expediente['exp_fec'] ?? null);
            $set('exp_nomrec', $expediente['exp_nomrec'] ?? null);
            $set('numdoc', $expediente['numdoc'] ?? null);
            $set('numtel', $expediente['numtel'] ?? null);
            $set('correo', $expediente['correo'] ?? null);
            $set('domfis', $expediente['domfis'] ?? null);
        }

        // Datos del Catastro
        if (isset($data['catastro']) && $data['catastro']) {
            $catastro = (array) $data['catastro'];
            $set('coduca', $catastro['coduca'] ?? null);
            $set('codpredio', $catastro['codpredio'] ?? null);
            $set('descurb', $catastro['descurb'] ?? null);
            $set('via_completa', $catastro['via_completa'] ?? null);
            $set('numvia', $catastro['numvia'] ?? null);
            $set('intdpto', $catastro['intdpto'] ?? null);
            $set('blockedif', $catastro['blockedif'] ?? null);
            $set('mz', $catastro['mz'] ?? null);
            $set('lote', $catastro['lote'] ?? null);
            $set('zonificacion', $catastro['zonificacion'] ?? null);
            $set('area_economica', $catastro['area_economica'] ?? null);
        }

        // Datos del Nivel de Riesgo
        if (isset($data['nivel_riesgo']) && $data['nivel_riesgo']) {
            $nivelRiesgo = (array) $data['nivel_riesgo'];
            $set('proccodigo', $nivelRiesgo['proccodigo'] ?? null);
            $set('procnivel', $nivelRiesgo['procnivel'] ?? null);
            
            if (isset($nivelRiesgo['nivel_riesgo'])) {
                $nivelRiesgoDetalle = (array) $nivelRiesgo['nivel_riesgo'];
                $set('nir_id', $nivelRiesgoDetalle['nir_id'] ?? null);
                $set('nir_descripcion', $nivelRiesgoDetalle['nir_descripcion'] ?? null);
            }
        }
    }
}