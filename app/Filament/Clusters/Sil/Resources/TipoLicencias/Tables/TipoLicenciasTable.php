<?php

namespace App\Filament\Clusters\Sil\Resources\TipoLicencias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TipoLicenciasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tli_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tli_descripcion')
                    ->searchable(),
                IconColumn::make('tli_filaoriginal')
                    ->boolean(),
                IconColumn::make('tli_filaeliminada')
                    ->boolean(),
                IconColumn::make('tli_activo')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([

            ]);
    }
}
