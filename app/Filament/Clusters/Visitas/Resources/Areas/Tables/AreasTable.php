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
            ->columns([
                TextColumn::make('nombre')
                    ->searchable(),

                TextColumn::make('nombre_corto')
                    ->searchable(),
                TextColumn::make('parentArea.nombre')
                    ->searchable()
                    ->default('Sin área superior'), // Muestra esto si la relación es nula,
                TextColumn::make('orden')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('estado')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('orden', 'asc')
            ->filters([
                SelectFilter::make('estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ]),
                TrashedFilter::make(), // Permite filtrar por: "Solo activos", "Solo eliminados", "Todos"
            ])
            // ->bulkActions([
            //     DeleteBulkAction::make(),
            //     RestoreBulkAction::make(),
            //     ForceDeleteBulkAction::make(),
            // ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn($record) => $record->deleted_at === null && auth()->user()->hasPermissionTo('edit::visitas_area')),
                DeleteAction::make()
                    ->visible(fn($record) => $record->deleted_at === null),        // Mueve a la papelera (Soft Delete)
                RestoreAction::make()
                    ->visible(fn($record) => $record->deleted_at !== null),       // Restaura el registro
                ForceDeleteAction::make()
                    ->visible(fn($record) => $record->deleted_at !== null),  // Borra permanentemente
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
