<?php

namespace App\Filament\Resources\CertificadoInspeccions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Carbon\Carbon;


class CertificadoInspeccionsTable
{

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipoEdificacion.tie_descripcion')
                    ->label('Edificación')
                    ->numeric()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'RIESGO BAJO' => 'info',
                        'RIESGO MEDIO' => 'warning',
                        'RIESGO ALTO' => 'danger-high',
                        'RIESGO MUY ALTO' => 'danger-very-high', 
                        "EX POST" => 'info',
                        "EX ANTE" => 'info',
                        "DE PARTE" => 'info',
                        "DE DETALLE" => 'info',
                    })
                    ->sortable(),
                TextColumn::make('cin_anio')
                    ->label('Año')
                    ->sortable(),
                TextColumn::make('cin_numero')
                    ->label('N° Certificado')
                    ->sortable(),
                TextColumn::make('cin_expediente')
                    ->label('Expediente')
                    ->searchable(),
                TextColumn::make('cin_resolucion')
                    ->label('Resolución')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('cin_resolucion_sigla')
                    ->label('Resolución Sigla')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('cin_resolucion_completa')
                    ->label('Resolución Completa')
                    ->getStateUsing(fn ($record) => $record->cin_resolucion. $record->cin_resolucion_sigla)
                    ->searchable(),
                TextColumn::make('cin_solicitante')
                    ->label('Solicitante')
                    ->searchable(),
                TextColumn::make('cin_ubicacion')
                    ->label('Ubicación')
                    ->searchable(),
                TextColumn::make('cin_giro')
                    ->label('Giro')
                    ->searchable(),
                TextColumn::make('cin_fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('cin_fec_inicio')
                    ->label('Vig. Fec. Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('cin_fec_fin')
                    ->label('Vig. Fec. Fin')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('cin_capacidad')
                    ->label('Capacidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cin_area')
                    ->label('Área (m²)')
                    ->numeric()
                    ->sortable(), 


        
                IconColumn::make('cin_indeterminado')
                    ->label('Indeterminado')
                    ->hidden()
                    ->boolean(),
                TextColumn::make('cin_filafecha')
                    ->label('Fecha de Fila')
                    ->dateTime()
                    ->hidden()
                    ->sortable(),
                IconColumn::make('cin_filaoriginal')
                    ->label('Fila Original')
                    ->hidden()
                    ->boolean(),
                IconColumn::make('cin_filaeliminada')
                    ->label('Fila Eliminada')
                    ->hidden()
                    ->boolean(),
                TextColumn::make('usa_id')
                    ->numeric()
                    ->hidden(),

                IconColumn::make('cin_consello')
                    ->label('Consello')
                    ->hidden()
                    ->boolean(),
                TextColumn::make('lic_id')
                    ->label('Licencia ID')
                    ->numeric()
                    ->hidden(),
                TextColumn::make('cin_departamento')
                    ->label('Departamento')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_provincia')
                    ->label('Provincia')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_distrito')
                    ->label('Distrito')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_licencia')
                    ->label('Licencia Número')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_procedimiento')
                    ->label('Procedimiento')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_expediente')
                    ->label('Expediente')
                    ->hidden()
                    ->searchable(),

                TextColumn::make('cin_nota')
                    ->label('Nota')
                    ->hidden()
                    ->searchable(),
                
   

                TextColumn::make('cin_establecimiento')
                    ->label('Establecimiento')
                    ->hidden()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->hidden()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Fecha de Actualización')
                    ->dateTime()
                    ->hidden()
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
            ->modifyQueryUsing(fn ($query) => $query->where('cin_filaeliminada', false))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
            
            
    }
}
