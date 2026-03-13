<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
            TextColumn::make('userIng.name')
                ->label('Usuario Registró Ingreso')
                ->searchable()
                ->sortable(),
            TextColumn::make('userSal.name')
                ->label('Usuario Registró Salida')
                ->searchable()
                ->sortable(),
            TextColumn::make('fecha')
                ->dateTime('d/m/Y')
                ->sortable()
                ->searchable(),
            TextColumn::make('hora_ingreso')
                ->dateTime('h:i:A')
                ->sortable()
                ->searchable(),
            TextColumn::make('hora_salida')
                ->dateTime('h:i:A')
                ->sortable()
                ->searchable(),
            // IconColumn::make('estado')
            //     ->boolean(),
        ])
            ->defaultSort('fecha', 'desc')
            ->filters([
                Filter::make('hora_ingreso')
                    ->schema([
                        DatePicker::make('fecha'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['fecha'],
                                fn($query, $date) =>
                                $query->whereDate('fecha', $date)
                            );
                    }),
                //     SelectFilter::make('usera')
                //     ->label('Creado Por')
                //     ->relationship('userIng', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->indicator('Creado Por'),

                // SelectFilter::make('userb')
                //     ->label('Actualizado Por')
                //     ->relationship('userSal', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->indicator('Actualizado Por'),

            ]);
        
    }
}
