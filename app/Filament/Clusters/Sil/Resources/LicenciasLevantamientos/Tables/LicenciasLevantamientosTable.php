<?php

namespace App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use App\Services\Sil\DataLevantamiento\DataLevantamientoService;
use App\Models\DataLevantamientoConsolida;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Grid;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;

class LicenciasLevantamientosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {

                $tableName = (new DataLevantamientoConsolida())->getTable();
                return $query->where(function ($q) use ($tableName) {
                    // Caso 1: Existe a través de Syscat
                    $q->whereHas('licenciaCatastro.fichaUbicacionSyscat', function ($sub) use ($tableName) {
                        $sub->whereExists(function ($existsQuery) use ($tableName) {
                            $existsQuery->select(\Illuminate\Support\Facades\DB::raw(1))
                                ->from($tableName) // La tabla que usa tu servicio
                                ->whereRaw("{$tableName}.sml = SUBSTRING(fiu_coduca, 7, 6)");
                        });
                    })
                        ->orWhereHas('licenciaCatastro.fichaUbicacionInfocat', function ($sub) use ($tableName) {
                        $sub->whereExists(function ($existsQuery) use ($tableName) {
                            $existsQuery->select(\Illuminate\Support\Facades\DB::raw(1))
                                ->from($tableName) // La tabla que usa tu servicio
                                ->whereRaw("{$tableName}.sml = SUBSTRING(fiu_codcat, 3, 6)");
                        });
                    });
                });
            })
            ->defaultSort('lic_filafecha', 'desc')
            ->columns([
                /*
                                TextColumn::make('existe_en_levantamiento')

                                    ->label('Existe en Levantamiento')
                                    ->state(function ($record) {
                                        $service = app(DataLevantamientoService::class);

                                        $smlSyscat = $record->licenciaCatastro?->fichaUbicacionSyscat?->fiu_coduca;
                                        if ($smlSyscat) {
                                            $sml = substr($smlSyscat, 6, 6);
                                            if ($service->existeSMLporCodigoCatastral($sml)) {
                                                return true;
                                            }
                                        }
                                        $smlInfocat = $record->licenciaCatastro?->fichaUbicacionInfocat?->fiu_codcat;
                                        if ($smlInfocat) {
                                            $sml = substr($smlInfocat, 2, 6);
                                            if ($service->existeSMLporCodigoCatastral($sml)) {
                                                return true;
                                            }
                                        }
                                        return false;
                                    })
                                    ->badge()
                                    ->formatStateUsing(fn($state) => $state ? 'Sí' : 'No')
                                    ->color(fn($state) => $state ? 'success' : 'danger')
                                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                                    ->alignCenter(),
                */
                TextColumn::make('lic_numlic')
                    ->label('Licencia')
                    ->badge()
                    ->color('primary')
                    // ->url(fn($record) => "/certificado-licencia/{$record->lic_id}")
                    // ->openUrlInNewTab()
                    ->action(
                        Action::make('ver_certificado_pdf')
                            ->modalHeading(fn($record) => "Certificado de Licencia N° {$record->lic_numlic}")
                            ->modalWidth('7xl')
                            ->modalIcon('heroicon-o-document-text')
                            ->modalIconColor('primary')
                            ->form(fn($record) => [
                                \Filament\Forms\Components\Placeholder::make('pdf_viewer')
                                    ->label('')
                                    ->content(fn() => new \Illuminate\Support\HtmlString(
                                        '<div style="width: 100%; height: 80vh;">
                                            <iframe src="/certificado-licencia/' . $record->lic_id . '" 
                                                    style="width: 100%; height: 100%; border: none; border-radius: 8px;"
                                                    title="Certificado de Licencia">
                                            </iframe>
                                        </div>'
                                    ))
                                    ->columnSpanFull(),
                            ])
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Cerrar')
                    )
                    ->searchable(),


                TextColumn::make('lic_fechaemision')
                    ->label('Fecha Emisión De Licencia')
                    ->date('d/m/Y')
                    ->searchable(),

                TextColumn::make('licenciaLevantamientoReciente.estadoLevantamiento.descripcion')
                    ->label('Estado de Levantamiento')
                    ->default('Sin estado')
                    ->badge()
                    ->color(fn($state) => $state === 'Sin estado' ? 'gray' : 'success')
                    ->searchable(),

                TextColumn::make('licenciaLevantamientoReciente.observaciones')
                    ->label('Observaciones')
                    ->default('Sin observaciones')
                    ->searchable(),

                TextColumn::make('licenciaCatastro.fichaUbicacionSyscat.fiu_coduca')
                    ->label('Syscat SML')
                    ->formatStateUsing(fn($state) => $state ? substr($state, 6, 6) : 'N/A')
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('licenciaCatastro.fichaUbicacionSyscat', function ($q) use ($search) {
                            $q->whereRaw("SUBSTRING(fiu_coduca, 7, 6) LIKE ?", ["%{$search}%"]);
                        });
                    }),

                TextColumn::make('licenciaCatastro.fichaUbicacionInfocat.fiu_codcat')
                    ->label('Infocat SML')
                    ->formatStateUsing(fn($state) => $state ? substr($state, 2, 6) : 'N/A')
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('licenciaCatastro.fichaUbicacionInfocat', function ($q) use ($search) {
                            $q->whereRaw("SUBSTRING(fiu_codcat, 3, 6) LIKE ?", ["%{$search}%"]);
                        });
                    }),

            ])
            ->filters([
                \Filament\Tables\Filters\TernaryFilter::make('tiene_itse')
                    ->label('Con ITSE')
                    ->placeholder('Todos')
                    ->trueLabel('Solo con ITSE')
                    ->falseLabel('Sin ITSE')
                    ->queries(
                        true: fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereIn('lic_id', function ($subquery) {
                            $subquery->select('lic_id')
                                ->from('licencia.vu_licencia')
                                ->whereNotNull('cin_numero');
                        }),
                        false: fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereNotIn('lic_id', function ($subquery) {
                            $subquery->select('lic_id')
                                ->from('licencia.vu_licencia')
                                ->whereNotNull('cin_numero');
                        }),
                        blank: fn(\Illuminate\Database\Eloquent\Builder $query) => $query,
                    )
                    ->indicator('ITSE'),
            ])

            ->recordActions([

                Action::make('Ver Acciones Realizadas')
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->tooltip('Ver Acciones Realizadas')
                    ->color(Color::Blue)
                    ->modalHeading(fn($record) => "Acciones Realizadas - Licencia #{$record->lic_numlic}")
                    ->modalWidth('5xl')
                    ->form([
                        Section::make('Detalles del Levantamiento')
                            ->schema([
                                TextInput::make('usuario_registro')
                                    ->label('Usuario Registrador')
                                    ->disabled(),

                                TextInput::make('fecha_registro')
                                    ->label('Fecha de Registro')
                                    ->disabled(),

                                TextInput::make('usuario_modificacion')
                                    ->label('Última Modificación Por')
                                    ->disabled(),

                                TextInput::make('fecha_modificacion')
                                    ->label('Fecha de Modificación')
                                    ->disabled(),

                                Textarea::make('observaciones')
                                    ->label('Observaciones Técnicas')
                                    ->disabled()
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ])->columns(2)
                    ])
                    ->fillForm(function ($record) {
                        // Obtener el registro más reciente de licencia_levantamiento
                        $levantamiento = $record->licenciaLevantamientoReciente;

                        if (!$levantamiento) {
                            return [
                                'usuario_registro' => 'Sin registros',
                                'fecha_registro' => 'N/A',
                                'usuario_modificacion' => 'N/A',
                                'fecha_modificacion' => 'N/A',
                                'observaciones' => 'No se han registrado acciones para esta licencia',
                            ];
                        }

                        // Obtener usuarios manualmente para evitar problemas de conexión
                        $usuarioCreador = null;
                        $usuarioModificador = null;

                        if ($levantamiento->created_by) {
                            $usuarioCreador = \App\Models\User::find($levantamiento->created_by);
                        }

                        if ($levantamiento->updated_by) {
                            $usuarioModificador = \App\Models\User::find($levantamiento->updated_by);
                        }

                        return [
                            'usuario_registro' => $usuarioCreador?->name ?? 'N/A',
                            'fecha_registro' => $levantamiento->created_at?->format('d/m/Y H:i:s') ?? 'N/A',
                            'usuario_modificacion' => $usuarioModificador?->name ?? 'N/A',
                            'fecha_modificacion' => $levantamiento->updated_at?->format('d/m/Y H:i:s') ?? 'N/A',
                            'observaciones' => $levantamiento->observaciones ?? 'Sin observaciones',
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
                Action::make('ver_data_levantamiento')
                    ->label('Ver Data del Levantamiento')
                    ->icon('heroicon-o-map')
                    ->iconButton()
                    ->tooltip('Ver Data del Levantamiento')
                    ->color(Color::Yellow)
                    ->modalHeading(fn($record) => "Data de Levantamiento - Licencia #{$record->lic_numlic}")
                    ->modalWidth('5xl')
                    ->infolist(function ($record) {
                        // Obtener SML de Syscat o Infocat
                        $sml = null;
                        $smlSyscat = $record->licenciaCatastro?->fichaUbicacionSyscat?->fiu_coduca;
                        if ($smlSyscat) {
                            $sml = substr($smlSyscat, 6, 6);
                        } else {
                            $smlInfocat = $record->licenciaCatastro?->fichaUbicacionInfocat?->fiu_codcat;
                            if ($smlInfocat) {
                                $sml = substr($smlInfocat, 2, 6);
                            }
                        }

                        // Buscar data de levantamiento
                        $dataLevantamiento = null;
                        if ($sml) {
                            $dataLevantamiento = DataLevantamientoConsolida::where('sml', $sml)->first();
                        }

                        if (!$dataLevantamiento) {
                            return [
                                Section::make()
                                    ->schema([
                                        TextEntry::make('no_data')
                                            ->label('Sin datos')
                                            ->state('No se encontró información de levantamiento para este SML')
                                            ->icon('heroicon-o-exclamation-triangle')
                                            ->iconColor('warning')
                                    ])
                            ];
                        }

                        return [
                            Section::make('Información General')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextEntry::make('sml')
                                                ->label('SML')
                                                ->state($dataLevantamiento->sml)
                                                ->badge()
                                                ->color('info'),
                                            TextEntry::make('feclevan')
                                                ->label('Fecha Levantamiento')
                                                ->state($dataLevantamiento->feclevan),
                                            TextEntry::make('ubicacion')
                                                ->label('Ubicación')
                                                ->state("Mz: {$dataLevantamiento->mza_urb} - Lt: {$dataLevantamiento->lot_urb}"),
                                        ]),
                                ]),

                            Section::make('Edificación')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextEntry::make('npisos')
                                                ->label('N° Pisos')
                                                ->state($dataLevantamiento->npisos ?? 'N/A'),
                                            TextEntry::make('usopredom')
                                                ->label('Uso Predominante')
                                                ->state($dataLevantamiento->usopredom ?? 'N/A'),
                                            TextEntry::make('det_ousos')
                                                ->label('Otros Usos')
                                                ->state($dataLevantamiento->det_ousos ?? 'N/A')
                                                ->columnSpanFull(),
                                        ]),
                                ])
                                ->collapsible(),

                            Section::make('Giros y Actividades')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextEntry::make('giro1')
                                                ->label('Giro 1')
                                                ->state($dataLevantamiento->giro1 ?? 'N/A'),
                                            TextEntry::make('giro2')
                                                ->label('Giro 2')
                                                ->state($dataLevantamiento->giro2 ?? 'N/A'),
                                            TextEntry::make('giro3')
                                                ->label('Giro 3')
                                                ->state($dataLevantamiento->giro3 ?? 'N/A'),
                                            TextEntry::make('giro4')
                                                ->label('Giro 4')
                                                ->state($dataLevantamiento->giro4 ?? 'N/A'),
                                            TextEntry::make('numacteco')
                                                ->label('N° Actividades Económicas')
                                                ->state($dataLevantamiento->numacteco ?? 'N/A'),
                                        ]),
                                ])
                                ->collapsible()
                                ->collapsed(),

                            Section::make('Observaciones')
                                ->schema([
                                    TextEntry::make('observa')
                                        ->label('Observaciones')
                                        ->state($dataLevantamiento->observa ?? 'Sin observaciones')
                                        ->columnSpanFull(),
                                    TextEntry::make('correo')
                                        ->label('Correo')
                                        ->state($dataLevantamiento->correo ?? 'N/A'),
                                ])
                                ->collapsible()
                                ->collapsed(),
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
                Action::make('realizar_acciones')
                    ->label('Realizar Acciones')
                    ->icon('heroicon-o-wrench')
                    ->iconButton()
                    ->tooltip('Realizar Acciones')
                    ->color(Color::Orange)
                    ->modalHeading(fn($record) => "Registrar Acción - Licencia #{$record->lic_numlic}")
                    ->form([
                        \Filament\Forms\Components\Hidden::make('id_estado_levantamiento')
                            ->default(3),

                        \Filament\Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->rows(4)
                            ->placeholder('Ingrese observaciones sobre esta acción...')
                            ->maxLength(1000),
                    ])
                    ->fillForm(function ($record) {
                        // Pre-llenar con el estado más reciente si existe
                        $levantamiento = $record->licenciaLevantamientoReciente;

                        return [
                            'id_estado_levantamiento' => $levantamiento?->id_estado_levantamiento ?? 3,
                            'observaciones' => $levantamiento?->observaciones,
                        ];
                    })
                    ->action(function ($record, array $data) {
                        // Verificar si ya existe un registro de levantamiento
                        $levantamiento = \App\Models\LicenciaLevantamiento::where('lic_id', $record->lic_id)->first();

                        if ($levantamiento) {
                            // Actualizar registro existente
                            $levantamiento->update([
                                'id_estado_levantamiento' => $data['id_estado_levantamiento'],
                                'observaciones' => $data['observaciones'] ?? null,
                                'updated_by' => auth()->id(),
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Estado actualizado')
                                ->success()
                                ->body('El estado de levantamiento ha sido actualizado correctamente.')
                                ->send();
                        } else {
                            // Crear nuevo registro
                            \App\Models\LicenciaLevantamiento::create([
                                'lic_id' => $record->lic_id,
                                'id_estado_levantamiento' => $data['id_estado_levantamiento'],
                                'observaciones' => $data['observaciones'] ?? null,
                                'created_by' => auth()->id(),
                                'updated_by' => auth()->id(),
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Estado registrado')
                                ->success()
                                ->body('El estado de levantamiento ha sido registrado correctamente.')
                                ->send();
                        }
                    })
                    ->modalSubmitActionLabel('Guardar')
                    ->modalCancelActionLabel('Cancelar'),
                Action::make('ver_foto_licencia')
                    ->label('Ver Foto Levantamiento')
                    ->icon('heroicon-o-photo')
                    ->iconButton()
                    ->tooltip('Ver Foto de la Licencia')
                    ->color(Color::Green)
                    ->url(function ($record) {
                        // Obtener SML de Syscat o Infocat
                        $sml = null;
                        $smlSyscat = $record->licenciaCatastro?->fichaUbicacionSyscat?->fiu_coduca;
                        if ($smlSyscat) {
                            $sml = substr($smlSyscat, 6, 6);
                        } else {
                            $smlInfocat = $record->licenciaCatastro?->fichaUbicacionInfocat?->fiu_codcat;
                            if ($smlInfocat) {
                                $sml = substr($smlInfocat, 2, 6);
                            }
                        }

                        // Buscar data de levantamiento
                        if ($sml) {
                            $dataLevantamiento = DataLevantamientoConsolida::where('sml', $sml)->first();

                            if ($dataLevantamiento && $dataLevantamiento->img_edificacion) {
                                return $dataLevantamiento->img_edificacion;
                            }
                        }

                        return null;
                    }, shouldOpenInNewTab: true)
                    ->disabled(function ($record) {
                        // Deshabilitar si no hay imagen
                        $sml = null;
                        $smlSyscat = $record->licenciaCatastro?->fichaUbicacionSyscat?->fiu_coduca;
                        if ($smlSyscat) {
                            $sml = substr($smlSyscat, 6, 6);
                        } else {
                            $smlInfocat = $record->licenciaCatastro?->fichaUbicacionInfocat?->fiu_codcat;
                            if ($smlInfocat) {
                                $sml = substr($smlInfocat, 2, 6);
                            }
                        }

                        if ($sml) {
                            $dataLevantamiento = DataLevantamientoConsolida::where('sml', $sml)->first();
                            return !($dataLevantamiento && $dataLevantamiento->img_edificacion);
                        }

                        return true;
                    }),

                Action::make('Ver Itse')
                    ->icon('tabler-clipboard-check')
                    ->iconButton()
                    ->tooltip('Ver Certificados ITSE')
                    ->color(Color::Stone)
                    ->disabled(function ($record) {
                        $service = app(CertificadoLincenciaFuncionamientoService::class);
                        return !$service->tieneCertificadoCompleto($record->lic_id);
                    })
                    ->modalHeading('Certificados de Inspección Técnica (ITSE)')
                    ->modalDescription(fn($record) => "Certificados ITSE relacionados con la Licencia N° {$record->lic_numlic}")
                    ->modalWidth('5xl')
                    ->modalIcon('tabler-clipboard-check')
                    ->modalIconColor(Color::Stone)
                    ->infolist(function ($record) {
                        $service = app(CertificadoLincenciaFuncionamientoService::class);
                        $certificados = $service->obtenerCertificadosInspeccionPorLicencia($record->lic_id);

                        if ($certificados->isEmpty()) {
                            return [
                                Section::make()
                                    ->schema([
                                        TextEntry::make('sin_datos')
                                            ->label('')
                                            ->default('No se encontraron certificados ITSE para esta licencia.')
                                            ->color('warning')
                                            ->icon('heroicon-o-exclamation-triangle')
                                    ])
                            ];
                        }

                        return [
                            Section::make('Certificados Encontrados')
                                ->description("Total: {$certificados->count()} certificado(s)")
                                ->icon('heroicon-o-document-check')
                                ->schema([
                                    RepeatableEntry::make('certificados')
                                        ->label('')
                                        ->state($certificados->toArray())
                                        ->schema([
                                            TextEntry::make('cin_annio')
                                                ->label('Año')
                                                ->badge()
                                                ->color('primary'),
                                            TextEntry::make('cin_numero')
                                                ->label('Número')
                                                ->weight('bold')
                                                ->copyable()
                                                ->copyMessage('Número copiado')
                                                ->icon('heroicon-o-hashtag'),
                                            TextEntry::make('tie_descripcion')
                                                ->label('Tipo de Edificación')
                                                ->badge()
                                                ->color('success'),
                                            TextEntry::make('cin_expediente')
                                                ->label('Expediente')
                                                ->icon('heroicon-o-folder-open')
                                                ->copyable()
                                                ->copyMessage('Expediente copiado'),
                                            TextEntry::make('cin_resolucion')
                                                ->label('Resolución')
                                                ->icon('heroicon-o-document-text')
                                                ->copyable()
                                                ->copyMessage('Resolución copiada'),
                                            TextEntry::make('cin_fecha')
                                                ->label('Fecha')
                                                ->date('d/m/Y')
                                                ->icon('heroicon-o-calendar'),
                                        ])
                                        ->columns(3)
                                        ->contained(true)
                                ])
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),

            ], position: RecordActionsPosition::BeforeCells)
            //->actionsColumnLabel('Acciones')
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
