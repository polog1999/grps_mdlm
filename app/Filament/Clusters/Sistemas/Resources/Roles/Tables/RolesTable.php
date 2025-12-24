<?php

namespace App\Filament\Clusters\Sistemas\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\Action;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                /*    TextColumn::make('guard_name')
                    ->searchable(),

                */
                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Fecha de actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Modificar usuario')
                    ->color('warning'),
                Action::make('borrar')
                    ->label('Borrar')
                    ->icon('heroicon-o-trash')
                    ->tooltip('Borrar certificado')
                    ->iconButton()
                    ->color('danger')
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([

            ]);
    }
}
