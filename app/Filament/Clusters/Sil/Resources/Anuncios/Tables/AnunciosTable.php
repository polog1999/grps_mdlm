<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class AnunciosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('n_anuncio')
                    ->searchable(),
                TextColumn::make('expediente.id')
                    ->searchable(),
                TextColumn::make('fecha_recepcion_evaluar')
                    ->date()
                    ->sortable(),
                TextColumn::make('caracteristicaFisica.id')
                    ->searchable(),
                TextColumn::make('tipoAnuncio.id')
                    ->searchable(),
                TextColumn::make('id_licencia')
                    ->searchable(),
                TextColumn::make('ancho_m')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('alto_m')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('espesor_cm')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ubicacion_del_anuncio')
                    ->searchable(),
                TextColumn::make('n_de_caras')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dictamen')
                    ->searchable(),
                TextColumn::make('estado_anuncio')
                    ->searchable(),
                TextColumn::make('derivado_a_legal_user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fecha_derivado')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_by_user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_by_user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('vigencia')
                    ->searchable(),
                TextColumn::make('fecha_inicio_vigencia')
                    ->date()
                    ->sortable(),
                TextColumn::make('fecha_fin_vigencia')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
