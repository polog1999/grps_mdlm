<?php

namespace App\Filament\Clusters\Visitas\Resources\Sedes\Tables;

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
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex() // <--- Esta es la clave en Filament v3
                    ->alignCenter(),
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable() // Permite buscar por nombre
                    ->sortable(),
                TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable() // Permite buscar por nombre
                    ->sortable(),

                // TextColumn::make('aforo')
                //     ->label('Capacidad')
                //     ->numeric()
                //     ->sortable(),

                // // Mostramos el estado como un icono (Verde si es 1, Rojo si es 0)
                // IconColumn::make('estado')
                //     ->label('Estado')
                //     ->boolean() // Funciona porque casteamos 'estado' a integer/boolean en el Modelo
                //     ->trueIcon('heroicon-o-check-circle')
                //     ->falseIcon('heroicon-o-x-circle')
                //     ->color(fn (int $state): string => $state === 1 ? 'success' : 'danger'),

            ]);
        // ->filters([
        //     // Puedes añadir filtros aquí, por ejemplo: solo sedes activas
        //     SelectFilter::make('estado')
        //         ->options([
        //             1 => 'Activo',
        //             0 => 'Inactivo',
        //         ]),
        //         TrashedFilter::make(), // Permite filtrar por: "Solo activos", "Solo eliminados", "Todos"
        // ])

        //     ->recordActions([
        //         EditAction::make()
        //         ->visible(fn($record) => $record->deleted_at === null && auth()->user()->hasPermissionTo('edit::visitas_sede')),
        //         DeleteAction::make()
        //             ->visible(fn($record) => $record->deleted_at === null),        // Mueve a la papelera (Soft Delete)
        //         RestoreAction::make()
        //             ->visible(fn($record) => $record->deleted_at !== null),       // Restaura el registro
        //         ForceDeleteAction::make()
        //             ->visible(fn($record) => $record->deleted_at !== null),  // Borra permanentemente
        //     ])
        // ->bulkActions([
        //     BulkActionGroup::make([
        //         DeleteBulkAction::make(),
        //     ]),
        // ]);
    }
}
