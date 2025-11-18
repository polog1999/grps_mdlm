<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamiento;

class CertificadoLicenciaFuncionamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Expediente')
                        ->description('Datos del expediente y solicitante')
                        ->icon('heroicon-o-archive-box')
                        ->schema([
                            TextInput::make('lic_expnum')
                                ->label('Número Expediente')
                                ->placeholder('Ingrese número de expediente')
                                ->required()
                                ->maxLength(50)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $state, callable $set) {
                                    if (empty($state)) {
                                        return;
                                    }

                                    try {
                                        $service = app(CertificadoLincenciaFuncionamiento::class);
                                        $result = $service->obtenerDatosLicenciaFuncionamiento($state);

                                        if ($result->isNotEmpty()) {
                                            $data = $result->first();

                                            // Mapear campos del JSON a los inputs del formulario
                                            if (isset($data->exp_fec)) {
                                                $set('lic_fecha_expediente', $data->exp_fec);
                                            }
                                            if (isset($data->exp_nomrec)) {
                                                $set('per_nombre_apellidos', $data->exp_nomrec);
                                            }
                                            if (isset($data->exp_nomrec)) {
                                                $set('lic_razonsocial', $data->exp_nomrec);
                                            }
                                            if (isset($data->numdoc)) {
                                                $set('per_ruc', $data->numdoc);
                                            }
                                            if (isset($data->numtel)) {
                                                $set('per_numtel', $data->numtel);
                                            }
                                            if (isset($data->domfis)) {
                                                $set('per_direccion', $data->domfis);
                                            }
                                            if (isset($data->correo)) {
                                                $set('per_correo', $data->correo);
                                            }
                                        }
                                    } catch (\Throwable $e) {
                                        // Silenciar errores o mostrar notificación si lo deseas
                                        \Illuminate\Support\Facades\Log::error('Error autocompletando expediente: ' . $e->getMessage());
                                    }
                                }),

                            DatePicker::make('lic_fecha_expediente')
                                ->label('Fecha Expediente')
                                ->displayFormat('d/m/Y')
                                ->native(false),

                            TextInput::make('per_nombre_apellidos')
                                ->label('Nombre y Apellidos')
                                ->placeholder('Ingrese nombre y apellidos')
                                ->maxLength(255),

                            TextInput::make('lic_razonsocial')
                                ->label('Razón Social')
                                ->placeholder('Ingrese razón social')
                                ->maxLength(255),

                            TextInput::make('per_ruc')
                                ->label('RUC')
                                ->placeholder('Ingrese RUC')
                                ->maxLength(20),

                            TextInput::make('per_numtel')
                                ->label('Teléfono')
                                ->placeholder('Ingrese teléfono')
                                ->maxLength(50),

                            TextInput::make('per_direccion')
                                ->label('Dirección')
                                ->placeholder('Ingrese dirección')
                                ->columnSpanFull()
                                ->maxLength(255),

                            TextInput::make('per_correo')
                                ->label('Email')
                                ->email()
                                ->placeholder('Ingrese email')
                                ->maxLength(255),
                        ])
                        ->columns(2),
                    
                    Step::make('Ubicación y Detalles')
                        ->description('Información adicional de la licencia')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            TextInput::make('lic_direccion')
                                ->label('Dirección')
                                ->placeholder('Ingrese dirección')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            
                            TextInput::make('lic_codigopredial')
                                ->label('Código Predial')
                                ->placeholder('Ingrese código predial')
                                ->maxLength(100),
                            
                            TextInput::make('lic_area')
                                ->label('Área (m²)')
                                ->numeric()
                                ->placeholder('0.00'),
                            
                            Textarea::make('lic_giro')
                                ->label('Giro del Negocio')
                                ->placeholder('Ingrese el giro del negocio')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->columnSpanFull()
            ]);
    }
}
