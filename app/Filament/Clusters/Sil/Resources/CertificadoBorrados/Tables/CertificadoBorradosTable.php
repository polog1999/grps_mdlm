<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoBorrados\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CertificadoBorradosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('certificadoInspeccion.cin_numero')
                    ->label('N° Certificado')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('certificadoInspeccion.cin_licencia')
                    ->label('N° Licencia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('certificadoInspeccion.cin_expediente')
                    ->label('N° Expediente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_razon_borrado')
                    ->label('Razón de borrado')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Fecha de borrado')
                    ->dateTime('d/m/Y')
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
            ])
            ->toolbarActions([

            ]);
    }
}
