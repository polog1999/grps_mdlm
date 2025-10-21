<?php

namespace App\Filament\Resources\Tipoedificacions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;

class TipoedificacionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tie_id')->label('ID')->sortable(),
                TextColumn::make('tie_descripcion')->label('Descripción')->searchable()->sortable(),
                TextColumn::make('tie_sigla')->label('Sigla')->sortable(),
                BooleanColumn::make('tie_activo')->label('Activo'),
                BooleanColumn::make('tie_filaeliminada')->label('Eliminada'),
                TextColumn::make('tie_filafecha')->label('Fecha')->date(),
                TextColumn::make('usa_id')->label('Usuario ID'),
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