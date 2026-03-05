<?php

namespace App\Filament\Clusters\Visitas\Resources\Areas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AreasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable(),

                TextColumn::make('nombre_corto')
                    ->searchable(),
                TextColumn::make('parentArea.nombre')
                    ->searchable()
                    ->default('Sin área superior'), // Muestra esto si la relación es nula,
                TextColumn::make('orden')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('estado')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('orden', 'asc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn() => auth()->user()->hasPermissionTo('edit::visitas_area')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
