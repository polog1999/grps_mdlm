<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;

class AnunciosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([

                TextColumn::make('n_anuncio')
                    ->label('N° Anuncio')
                    ->searchable(),
                TextColumn::make('expediente.n_expediente')
                    ->label('N° Expediente')
                    ->searchable(),
                TextColumn::make('expediente.snapshot_solicitante_nombre_completo')
                    ->label('Solicitante')
                    ->searchable(),
                TextColumn::make('expediente.snapshot_solicitante_dni')
                    ->label('DNI/RUC Solicitante')
                    ->searchable(),
                TextColumn::make('expediente.snapshot_legal_nombre')
                    ->label('Representante Legal')
                    ->searchable(),
                TextColumn::make('expediente.snapshot_legal_dni_ruc')
                    ->label('DNI/RUC Representante Legal')
                    ->searchable(),
                TextColumn::make('expediente.snapshot_solicitante_direccion')
                    ->label('Dirección del Predio')
                    ->searchable(),
                TextColumn::make('fecha_recepcion_evaluar')
                    ->label('Fecha Recepción Evaluar')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('caracteristicaFisica.descripcion')
                    ->label('Característica Física')
                    ->searchable(),
                TextColumn::make('tipoAnuncio.descripcion')
                    ->label('Tipo de Anuncio')
                    ->searchable(),
                TextColumn::make('licencia.lic_numlic')
                    ->label('N° Licencia')
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
                TextColumn::make('derivadoLegal.name')
                    ->label('Derivado a Legal')
                    ->sortable(),

                TextColumn::make('fecha_derivado')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('vigencia')
                    ->searchable(),
                TextColumn::make('fecha_inicio_vigencia')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('fecha_fin_vigencia')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                Action::make('Generar Informe')
                    ->label('Generar Informe')
                    ->iconButton()
                    ->tooltip('Generar Informe')
                    ->color(Color::Cyan)
                    ->icon('heroicon-o-document-text')
                    ->action(function (Anuncio $record) {

                    }),
                Action::make('Generar Carta')
                    ->label('Generar Carta')
                    ->iconButton()
                    ->tooltip('Generar Carta')
                    ->color(Color::Yellow)
                    ->icon('heroicon-o-envelope')
                    ->action(function (Anuncio $record) {

                    }),
                Action::make('Dar de Baja')
                    ->label('Dar de Baja')
                    ->iconButton()
                    ->tooltip('Dar de Baja')
                    ->color(Color::Red)
                    ->icon('heroicon-o-trash')
                    ->action(function (Anuncio $record) {

                    })
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([

            ]);
    }
}
