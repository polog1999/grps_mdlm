<?php

namespace App\Filament\Clusters\RRHH\Resources\Asistencias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AsistenciasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('hora_entrada', 'desc')->recordUrl(null)
            ->columns([
                TextColumn::make('usuario.name')
                    ->sortable(),

                TextColumn::make('hora_entrada')
                    ->label('Fecha y Hora de Entrada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->searchable()
                    ->color('success'),
                TextColumn::make('hora_salida')
                    ->label('Fecha y Hora de Salida')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->searchable()
                    ->color('danger'),
                TextColumn::make('almuerzo')
                    ->label('Almuerzo')
                    ->getStateUsing(function ($record) {
                        $inicio = $record->hora_almuerzo_salida?->format('h:i:s A');
                        $fin = $record->hora_almuerzo_entrada?->format('h:i:s A');

                        if (!$inicio && !$fin) {
                            return '';
                        }

                        return ($inicio ?? '') . ' - ' . ($fin ?? '');
                    }),
            ])
            ->filters([

            ])
            ->recordActions([

            ])
            ->toolbarActions([

            ]);
    }
}
