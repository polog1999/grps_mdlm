<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaCargos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditoriaCargosTable
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
                TextColumn::make('userCreo.name')
                    ->sortable(),
                TextColumn::make('userModi.name')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y h:i:A')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y h:i:A')
                    ->sortable(),
                    IconColumn::make('estado')
                    ->boolean(),
            ])
            ->defaultSort('nombre', 'asc')
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
