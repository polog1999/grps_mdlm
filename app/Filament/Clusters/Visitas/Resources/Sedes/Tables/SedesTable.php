<?php

namespace App\Filament\Clusters\Visitas\Resources\Sedes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SedesTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
            // Mostramos el ID personalizado
            // TextColumn::make('id_sede')
            //     ->label('ID')
            //     ->sortable(),

            TextColumn::make('nombre')
                ->label('Nombre de la Sede')
                ->searchable() // Permite buscar por nombre
                ->sortable(),

            TextColumn::make('aforo')
                ->label('Capacidad')
                ->numeric()
                ->sortable(),

            // Mostramos el estado como un icono (Verde si es 1, Rojo si es 0)
            IconColumn::make('estado')
                ->label('Estado')
                ->boolean() // Funciona porque casteamos 'estado' a integer/boolean en el Modelo
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->color(fn (int $state): string => $state === 1 ? 'success' : 'danger'),

            TextColumn::make('created_at')
                ->label('Fecha Registro')
                ->dateTime('d/m/Y H:i')
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            // Puedes añadir filtros aquí, por ejemplo: solo sedes activas
            SelectFilter::make('estado')
                ->options([
                    1 => 'Activo',
                    0 => 'Inactivo',
                ]),
        ])
        ->actions([
           EditAction::make(),
           DeleteAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}
