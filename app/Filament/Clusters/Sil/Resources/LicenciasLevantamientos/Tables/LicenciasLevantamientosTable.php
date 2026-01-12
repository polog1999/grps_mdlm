<?php

namespace App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use App\Services\Sil\DataLevantamiento\DataLevantamientoService;
use App\Models\DataLevantamientoConsolida;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;

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

                TextColumn::make('lic_numlic')
                    ->label('Licencia')
                    ->searchable(),

                TextColumn::make('licenciaLevantamientoReciente.estadoLevantamiento.descripcion')
                    ->label('Estado de Levantamiento')
                    ->default('Sin estado')
                    ->badge()
                    ->color(fn($state) => $state === 'Sin estado' ? 'gray' : 'success')
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
                //
            ])

            ->recordActions([

                Action::make('Ver Acciones Realizadas')
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->tooltip('Ver Acciones Realizadas')
                    ->color(Color::Blue),
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
                        \Filament\Forms\Components\Select::make('id_estado_levantamiento')
                            ->label('Estado de Levantamiento')
                            ->options(\App\Models\EstadoLevantamiento::pluck('descripcion', 'id'))
                            ->required()
                            ->searchable()
                            ->placeholder('Seleccione un estado'),

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
                            'id_estado_levantamiento' => $levantamiento?->id_estado_levantamiento,
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
                Action::make('Ver Foto de la Licencia')
                    ->icon('heroicon-o-photo')
                    ->iconButton()
                    ->tooltip('Ver Foto de la Licencia')
                    ->color(Color::Green),

            ], position: RecordActionsPosition::BeforeCells)
            //->actionsColumnLabel('Acciones')
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
