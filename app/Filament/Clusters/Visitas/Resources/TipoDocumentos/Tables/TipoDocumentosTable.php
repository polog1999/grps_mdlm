<?php

namespace App\Filament\Clusters\Visitas\Resources\TipoDocumentos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TipoDocumentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('nombre_corto')
                    ->searchable(),
                IconColumn::make('estado')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('estado')
                ->options([
                    1 => 'Activo',
                    0 => 'Inactivo',
                ]),
                TrashedFilter::make(), // Permite filtrar por: "Solo activos", "Solo eliminados", "Todos"
            ])
            ->recordActions([
                EditAction::make()
                ->visible(fn($record) => $record->deleted_at === null && auth()->user()->hasPermissionTo('edit::visitas_tipo_documento')),
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
