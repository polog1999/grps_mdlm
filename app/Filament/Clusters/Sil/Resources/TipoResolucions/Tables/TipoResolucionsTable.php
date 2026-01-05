<?php

namespace App\Filament\Clusters\Sil\Resources\TipoResolucions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TipoResolucionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tir_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tir_descripcion')
                    ->searchable(),
                TextColumn::make('tir_filafecha')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('tir_filaoriginal')
                    ->boolean(),
                IconColumn::make('tir_filaeliminada')
                    ->boolean(),
                IconColumn::make('tir_activo')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
