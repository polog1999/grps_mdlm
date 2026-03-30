<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditoriaMotivosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                ->label('#')
                ->rowIndex() // <--- Esta es la clave en Filament v3
                ->alignCenter(),
            TextColumn::make('motivo')
                ->searchable(),
        
            TextColumn::make('userCreo.name')
                ->label('Usuario Registró')
                ->searchable()
                ->sortable(),
            TextColumn::make('userModi.name')
                ->label('Usuario Modificó')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
