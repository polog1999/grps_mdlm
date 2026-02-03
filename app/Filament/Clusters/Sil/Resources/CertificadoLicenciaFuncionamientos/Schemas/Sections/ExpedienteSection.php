<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections;

use Filament\Forms\Components\Hidden;
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
                TextInput::make('exp_num')
                    ->label('Número de Expediente')
                    ->disabled()
                    ->dehydrated()
                    ->suffixAction(
                        Action::make('editar_expediente')
                            ->icon('heroicon-o-pencil-square')
                            ->color('warning')
                            ->tooltip('Editar/Buscar otro expediente')
                            ->modalHeading('Buscar Nuevo Expediente')
                            ->modalWidth('7xl')
                            ->steps([
                                \App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps\BusquedaStep::make(),
                                \App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps\SeleccionCoincidenciasStep::make(),
                                \Filament\Schemas\Components\Wizard\Step::make('Confirmación')
                                    ->description('Confirme los datos seleccionados')
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('confirmation_text')
                                            ->content('Por favor revise los datos seleccionados en los pasos anteriores. Al hacer clic en "Confirmar", se actualizarán los datos del formulario principal.'),
                                        \Filament\Forms\Components\Hidden::make('_datos_completos')
                                            ->dehydrated()
                                    ]),
                            ])
                            ->action(function (array $data, $set) {
                                // Hydrate main form with data from the wizard (which effectively comes from _datos_completos populated by BusquedaStep)
                                // We need to check if we have the full structure or need to rely on what DatosCompletosStep expects
                    
                                // BusquedaStep populates '_datos_completos' in the form state.
                                // However, in an Action, the $data array contains the values of the fields in the Action's form.
                                // Since '_datos_completos' is a hidden field in DatosCompletosStep but NOT explicitly added to Busqueda/Seleccion steps as a field returned in $data by default unless dehydrated.
                    
                                // Let's check BusquedaStep implementation. It uses $set('_datos_completos', $result->data).
                                // We need to ensure we can access this.
                    
                                // Ideally, we should use the same logic as DatosCompletosStep::autocompletarDatos.
                                // We can pass $data directly if it contains the necessary keys, but typically the Wizard steps might structure data differently or flat.
                                // But wait, BusquedaStep and SeleccionCoincidenciasStep primarily manipulate internal state variables like '_datos_completos'.
                    
                                // IMPORTANT: The $data passed to this action() will contain the dehydrated values of the fields inside the wizard steps.
                                // We need to make sure '_datos_completos' is accessible. It is populated in the state.
                                // If it is not a form field, it might not be in $data.
                                // However, SeleccionCoincidenciasStep likely deals with '_datos_completos' via state.
                    
                                // Re-reading BusquedaStep: it calls $set('_datos_completos', ...).
                                // If we don't have a Hidden field for it in the Action's form schema, it won't be in $data.
                                // We should probably add it to the schema of the last step or ensure it's returned.
                    
                                // Alternatively, we can assume that if the user passed the steps, the state has the data.
                                // But $data in action() is strictly validated data from fields.
                    
                                // We will trust that the fields we manually mapped in DatosCompletosStep exist in $data IF we had mapped them? 
                                // No, DatosCompletosStep::autocompletarDatos TAKES the source structure (from Oracle/Service) and SETS the form fields.
                                // We need that SOURCE structure (the array usually called $data in that method).
                    
                                // Quick fix: Add a hidden field for '_datos_completos' in the last step of this Wizard to capture the state.
                                // OR rely on the fact that we can just re-compose it? No, that's hard.
                    
                                // Let's assume there is a hidden field in the wizard. I will add it to the Confirmation step I just created.
                    
                                \App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps\DatosCompletosStep::autocompletarDatos($data['_datos_completos'] ?? [], $set);

                                Notification::make()
                                    ->title('Expediente actualizado')
                                    ->success()
                                    ->send();
                            })
                    ),
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
                                        return $service->getPersonasFormateadas();
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
                                        return $service->getPersonasFormateadas();
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
                                        // Actualizar campos de Razón Social
                                        $set('exp_razsoc', $personaSeleccionada->per_nombrerazonsocial);
                                        $set('exp_razsoc_id', $personaSeleccionada->per_id);

                                        // Actualizar campos adicionales del formulario
                                        $set('numdoc', $personaSeleccionada->per_ruc ?? '');
                                        $set('numtel', $personaSeleccionada->per_telefono ?? '');
                                        $set('correo', $personaSeleccionada->per_email ?? '');
                                        $set('domfis', $personaSeleccionada->per_direccion ?? '');

                                        self::notify('success', 'Razón Social seleccionada', 'Se ha actualizado la razón social y los datos asociados correctamente');
                                    }
                                }
                            })
                    ),



                TextInput::make('numdoc')->label('RUC/DNI')->maxLength(22)->numeric()->disabled()->dehydrated(),
                TextInput::make('numtel')->label('Teléfono')->maxLength(50)->numeric()
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('correo')->label('Correo Electrónico')->maxLength(255)->disabled()->dehydrated(),
                TextInput::make('domfis')->label('Domicilio Fiscal')->maxLength(255)->columnSpanFull()->disabled()->dehydrated(),
                Hidden::make('exp_nomrec_id')
                    ->label('ID Persona')
                    ->disabled()
                    ->dehydrated()
                    //->numeric()
                    ->columnSpan(1),
                Hidden::make('exp_razsoc_id')
                    ->label('ID Razón Social')
                    ->disabled()
                    ->dehydrated()
                    //->numeric()
                    ->columnSpan(1),
            ])
            ->columnSpanFull()
            ->columns(2);
    }

    private static function notify(string $type, string $title, string $body): void
    {
        Notification::make()->$type()->title($title)->body($body)->send();
    }
}
