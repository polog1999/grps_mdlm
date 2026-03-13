<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaAreas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditoriaAreasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex() // <--- Esta es la clave en Filament v3
                    ->alignCenter(),
                TextColumn::make('nombre')
                    ->searchable(),

                TextColumn::make('nombre_corto')
                    ->searchable(),
                TextColumn::make('userCreo.name')
                    ->label('Creado por:')
                    ->sortable(),
                TextColumn::make('userModi.name')
                    ->label('Editado por:')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y h:i:A')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Editado')
                    ->dateTime('d/m/Y h:i:A')
                    ->sortable(),
                IconColumn::make('estado')
                    ->boolean(),
            ])
            ->defaultSort('orden', 'asc')
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
