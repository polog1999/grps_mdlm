<?php

namespace App\Filament\Clusters\Sil\Resources\Lote1s\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
class Lote1sTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('sector_cat')
                    ->label('Sector')
                    ->searchable(),
                TextColumn::make('mzna_cat')
                    ->label('Manzana')
                    ->searchable(),
                TextColumn::make('lote_cat')
                    ->label('Lote')
                    ->searchable(),
                TextColumn::make('cod_lote_cat')
                    ->label('Codigo SML')
                    ->searchable(),
                TextColumn::make('zonif_actual')
                    ->label('Zonificación')
                    ->searchable(),

            ])
            ->filters([
                SelectFilter::make('sector_cat')
                    ->label('Filtrar por Sector')
                    ->options(fn() => \App\Models\Lote1::query()->whereNotNull('sector_cat')->distinct()->pluck('sector_cat', 'sector_cat')->toArray())
                    ->searchable(),
                SelectFilter::make('mzna_cat')
                    ->label('Filtrar por Manzana')
                    ->options(fn() => \App\Models\Lote1::query()->whereNotNull('mzna_cat')->distinct()->pluck('mzna_cat', 'mzna_cat')->toArray())
                    ->searchable(),
                SelectFilter::make('lote_cat')
                    ->label('Filtrar por Lote')
                    ->options(fn() => \App\Models\Lote1::query()->whereNotNull('lote_cat')->distinct()->pluck('lote_cat', 'lote_cat')->toArray())
                    ->searchable(),
                SelectFilter::make('zonif_actual')
                    ->label('Filtrar por Zonificación')
                    ->options(\App\Models\Zonificacion::pluck('siglas', 'siglas'))
                    ->searchable(),
            ])
            ->recordActions([
                Action::make('cambiarZonificacion')
                    ->label('Actualizar')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading('Actualizar Zonificación')
                    ->requiresConfirmation()
                    ->modalDescription('¿Está seguro de que desea actualizar la zonificación actual del lote? Esto modificará permanentemente los datos.')
                    ->form([
                        Select::make('zonif_actual')
                            ->label('Zonificación')
                            ->options(\App\Models\Zonificacion::pluck('siglas', 'siglas'))
                            ->searchable()
                            ->required()
                            ->default(fn($record) => $record->zonif_actual),
                    ])
                    ->action(function (array $data, $record): void {
                        $record->update(['zonif_actual' => $data['zonif_actual']]);
                    }),
            ])
            ->toolbarActions([

            ]);
    }
}
