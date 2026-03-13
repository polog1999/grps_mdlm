<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditoriaVisitasTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('index')
                ->label('#')
                ->rowIndex() // <--- Esta es la clave en Filament v3
                ->alignCenter(),
            TextColumn::make('tipo_documento')
                ->searchable(),
            TextColumn::make('numero_documento')
                ->searchable()
                ->copyable(),
            TextColumn::make('user_ingreso')
                ->label('Usuario Registró Ingreso')
                ->sortable(),
            TextColumn::make('user_salida')
                ->label('Usuario Registró Salida')
                ->sortable(),
            TextColumn::make('fecha')
                ->dateTime('d/m/Y')
                ->sortable(),
            TextColumn::make('hora_ingreso')
                ->dateTime('h:i:A')
                ->sortable(),
            TextColumn::make('hora_salida')
                ->dateTime('h:i:A')
                ->sortable(),
            // IconColumn::make('estado')
            //     ->boolean(),
        ])
            ->defaultSort('fecha', 'desc')
            ->filters([
                //
            ]);
        // ->recordActions([
        //     EditAction::make(),
        // ])
        // ->toolbarActions([
        //     BulkActionGroup::make([
        //         DeleteBulkAction::make(),
        //     ]),
        // ]);
    }
}
