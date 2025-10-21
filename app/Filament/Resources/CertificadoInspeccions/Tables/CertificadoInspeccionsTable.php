<?php

namespace App\Filament\Resources\CertificadoInspeccions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CertificadoInspeccionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cin_anio')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tie_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cin_numero')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cin_area')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cin_capacidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cin_fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('cin_fec_inicio')
                    ->date()
                    ->sortable(),
                TextColumn::make('cin_fec_fin')
                    ->date()
                    ->sortable(),
                IconColumn::make('cin_indeterminado')
                    ->boolean(),
                TextColumn::make('cin_filafecha')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('cin_filaoriginal')
                    ->boolean(),
                IconColumn::make('cin_filaeliminada')
                    ->boolean(),
                TextColumn::make('usa_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('cin_consello')
                    ->boolean(),
                TextColumn::make('lic_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cin_departamento')
                    ->searchable(),
                TextColumn::make('cin_provincia')
                    ->searchable(),
                TextColumn::make('cin_licencia')
                    ->searchable(),
                TextColumn::make('cin_procedimiento')
                    ->searchable(),
                TextColumn::make('cin_distrito')
                    ->searchable(),
                TextColumn::make('cin_expediente')
                    ->searchable(),
                TextColumn::make('cin_ubicacion')
                    ->searchable(),
                TextColumn::make('cin_nota')
                    ->searchable(),
                TextColumn::make('cin_resolucion_sigla')
                    ->searchable(),
                TextColumn::make('cin_giro')
                    ->searchable(),
                TextColumn::make('cin_resolucion')
                    ->searchable(),
                TextColumn::make('cin_establecimiento')
                    ->searchable(),
                TextColumn::make('cin_solicitante')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
