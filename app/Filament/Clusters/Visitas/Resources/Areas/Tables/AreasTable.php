<?php

namespace App\Filament\Clusters\Visitas\Resources\Areas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AreasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Area::with(['oficinas', 'sede'])->where('estado', 1))
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex() // <--- Esta es la clave en Filament v3
                    ->alignCenter(),
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('abreviatura')
                ->label('Área')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('area.nombre')
                ->label('Dependencia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sede.nombre')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('id_uo_estado')
                    ->label('Estado')
                    ->formatStateUsing(fn(int $state): string => match ($state) {
                        1 => 'Activo',
                        2 => 'Inactivo',
                        default => 'Desconocido',
                    })
                    ->badge()
                    ->color(fn(int $state): string => match ($state) {
                        1 => 'success', // Verde
                        2 => 'danger',  // Rojo
                        default => 'gray',
                    })
                    ->sortable()

                // TextColumn::make('parentArea.nombre')
                //     ->searchable()
                //     ->default('Sin área superior'), // Muestra esto si la relación es nula,
                // TextColumn::make('orden')
                //     ->numeric()
                //     ->sortable(),
                // IconColumn::make('estado')
                //     ->boolean(),
            ])
            ->defaultSort('id_unidad_organica', 'asc')
            ->filters([
                SelectFilter::make('id_sede')
                    ->label('Sede')
                    ->searchable()
                    ->options(fn() => Sede::pluck('nombre', 'id_sede')),
                SelectFilter::make('id_unidad_organica')
                    ->label('Área')
                    ->searchable()
                    ->options(function () {
                        return Area::query()
                            ->where('estado', '1')
                            ->get()
                            ->mapWithKeys(function ($area) {
                                // Combinamos nombre y abreviatura en el label
                                return [$area->id_unidad_organica => "{$area->nombre} ({$area->abreviatura})"];
                            });
                    }),
                SelectFilter::make('id_unidad_organica')
                    ->label('Área')
                    ->searchable()
                    ->options(function () {
                        return Area::query()
                            ->where('estado', '1')
                            ->get()
                            ->mapWithKeys(function ($area) {
                                // Combinamos nombre y abreviatura en el label
                                return [$area->id_unidad_organica => "{$area->nombre} ({$area->abreviatura})"];
                            });
                    }),
                SelectFilter::make('oficina')
                    ->label('Oficina')
                    ->searchable()
                    ->options(function () {
                        // Traemos las oficinas para llenar el select
                        return Oficina::query()
                          
                            ->pluck('nombre', 'id_unidad_organica'); // 'id' de la oficina
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        // Filtramos la tabla Areas (Unidad Orgánica) buscando si tiene 
                        // la oficina seleccionada mediante la relación
                        return $query->whereHas('oficinas', function (Builder $q) use ($data) {
                            $q->where('id_unidad_organica', $data['value']);
                        });
                    })
            ])
            ->recordActions([
                // EditAction::make()
                //     ->visible(fn($record) => $record->deleted_at === null && auth()->user()->hasPermissionTo('edit::visitas_area')),
                // DeleteAction::make()
                //     ->visible(fn($record) => $record->deleted_at === null),        // Mueve a la papelera (Soft Delete)
                // RestoreAction::make()
                //     ->visible(fn($record) => $record->deleted_at !== null),       // Restaura el registro
                // ForceDeleteAction::make()
                //     ->visible(fn($record) => $record->deleted_at !== null),  // Borra permanentemente
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
