<?php

namespace App\Filament\Clusters\Visitas\Resources\Regimens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegimensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('cregimen')
                    ->searchable(),
                TextColumn::make('parentRegimen.cregimen')
                 ->label('Régimen Padre')
                 ->default('Sin Régimen Parent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('de_regimen')
                ->label('Descripción')
                    ->searchable(),
                IconColumn::make('estado')
                    ->boolean(),
                // TextColumn::make('nu_tasa_impuesto')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('user_id_creo')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('user_id_modi')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
