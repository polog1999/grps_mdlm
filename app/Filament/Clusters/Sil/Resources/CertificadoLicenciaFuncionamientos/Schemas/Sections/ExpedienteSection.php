<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use App\Services\Sil\Licencias\LicenciaPersonaService;
use Filament\Notifications\Notification;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Actions\SectionHeaderActions;

class ExpedienteSection
{
    public static function make(): Section
    {
        return Section::make('Expediente')
            ->description('Datos del expediente administrativo')
            ->icon('heroicon-o-archive-box')
            ->collapsible()
            ->schema([
                TextInput::make('exp_num')->label('Número de Expediente')->disabled()->dehydrated(),
                DatePicker::make('exp_fec')->label('Fecha de Expediente')->displayFormat('d/m/Y')->native(false)->disabled()->dehydrated(),
                TextInput::make('exp_nomrec')
                    ->label('Nombre y Apellidos')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->dehydrated()
                    ->suffixAction(
                        Action::make('buscar_nombre')
                            ->icon('heroicon-o-magnifying-glass')
                            ->modalHeading('Buscar Persona')
                            ->modalDescription('Seleccione una persona de la lista y visualice sus datos')
                            ->modalSubmitActionLabel('Seleccionar')
                            ->modalWidth('5xl')
                            ->form([
                                Select::make('persona_seleccionada')
                                    ->label('Buscar Persona')
                                    ->options(function () {
                                        $service = app(LicenciaPersonaService::class);
                                        $personas = $service->getLicenciaPersonaNombre();
                                        return $personas->pluck('per_nombrerazonsocial', 'per_id')->toArray();
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
                                    ->visible(fn($get) => $get('persona_seleccionada') !== null),
                            ])
                            ->action(function (array $data, $set) {
                                if (isset($data['persona_seleccionada'])) {
                                    $service = app(LicenciaPersonaService::class);
                                    $personas = $service->getLicenciaPersonaNombre();
                                    $personaSeleccionada = $personas->firstWhere('per_id', $data['persona_seleccionada']);

                                    if ($personaSeleccionada) {
                                        $set('exp_nomrec', $personaSeleccionada->per_nombrerazonsocial);
                                        $set('exp_nomrec_id', $personaSeleccionada->per_id);
                                        self::notify('success', 'Persona seleccionada', 'Se ha actualizado el nombre correctamente');
                                    }
                                }
                            })
                    ),
                TextInput::make('exp_nomrec_id')
                    ->label('ID Persona')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
<<<<<<< HEAD
                    ->columnSpan(1)
                    ->visible(fn($get, $state) => $state !== null || $get('persona_seleccionada') !== null),
=======
                    ->columnSpan(1),
>>>>>>> feature/licencias

                TextInput::make('exp_razsoc')
                    ->label('Razón Social')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->dehydrated()
                    ->suffixAction(
                        Action::make('buscar_razon_social')
                            ->icon('heroicon-o-magnifying-glass')
                            ->modalHeading('Buscar Razón Social')
                            ->modalDescription('Seleccione una razón social de la lista y visualice sus datos')
                            ->modalSubmitActionLabel('Seleccionar')
                            ->modalWidth('5xl')
                            ->form([
                                Select::make('persona_seleccionada_rs')
                                    ->label('Buscar Razón Social')
                                    ->options(function () {
                                        $service = app(LicenciaPersonaService::class);
                                        $personas = $service->getLicenciaPersonaNombre();
                                        return $personas->pluck('per_nombrerazonsocial', 'per_id')->toArray();
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
                                    ->visible(fn($get) => $get('persona_seleccionada_rs') !== null),
                            ])
                            ->action(function (array $data, $set) {
                                if (isset($data['persona_seleccionada_rs'])) {
                                    $service = app(LicenciaPersonaService::class);
                                    $personas = $service->getLicenciaPersonaNombre();
                                    $personaSeleccionada = $personas->firstWhere('per_id', $data['persona_seleccionada_rs']);

                                    if ($personaSeleccionada) {
                                        $set('exp_razsoc', $personaSeleccionada->per_nombrerazonsocial);
                                        $set('exp_razsoc_id', $personaSeleccionada->per_id);
                                        self::notify('success', 'Razón Social seleccionada', 'Se ha actualizado la razón social correctamente');
                                    }
                                }
                            })
                    ),
                TextInput::make('exp_razsoc_id')
                    ->label('ID Razón Social')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->columnSpan(1),


                TextInput::make('numdoc')->label('RUC/DNI')->maxLength(22)->numeric()->disabled()->dehydrated(),
                TextInput::make('numtel')->label('Teléfono')->maxLength(50)->numeric()->disabled()->dehydrated(),
                TextInput::make('correo')->label('Correo Electrónico')->maxLength(255)->disabled()->dehydrated(),
                TextInput::make('domfis')->label('Domicilio Fiscal')->maxLength(255)->columnSpanFull()->disabled()->dehydrated(),
            ])
            ->headerActions(SectionHeaderActions::make('expediente'))
            ->columnSpanFull()
            ->columns(2);
    }

    private static function notify(string $type, string $title, string $body): void
    {
        Notification::make()->$type()->title($title)->body($body)->send();
    }
}
