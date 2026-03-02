<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Tables;

use App\Models\Anuncios;
use App\Services\Sil\Anuncios\InformeAnuncioService;
use App\Services\Sil\Anuncios\CertificadoAnuncioService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
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
            ->defaultSort('created_at', 'desc')
            ->recordUrl(null)
            ->columns([
                TextColumn::make('n_anuncio')
                    ->label('N° Anuncio')
                    ->searchable(),

                TextColumn::make('documentos')
                    ->label('N° Informe Técnico')
                    ->getStateUsing(fn(Anuncios $record) => $record->documentos()->where('tipo_documento', 'INFORME TÉCNICO')->first()?->n_documento)
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('documentos', function ($q) use ($search) {
                            $q->where('tipo_documento', 'INFORME TÉCNICO')
                                ->where('n_documento', 'like', "%{$search}%");
                        });
                    }),
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
                    ->action(function (Anuncios $record) {
                        $path = app(InformeAnuncioService::class)->generarInforme($record, $record->expediente?->n_expediente);
                        $nInforme = $record->documentos()->where('tipo_documento', 'INFORME TÉCNICO')->first()?->n_documento ?? $record->id;
                        return response()->download(
                            $path,
                            'Informe_' . $nInforme . '.docx'
                        )->deleteFileAfterSend(true);
                    }),
                Action::make('Generar Certificado')
                    ->label('Generar Certificado')
                    ->iconButton()
                    ->tooltip('Generar Certificado')
                    ->color(Color::Yellow)
                    ->icon('heroicon-o-envelope')
                    ->action(function (Anuncios $record) {
                        $path = app(CertificadoAnuncioService::class)->generarCertificado($record);
                        return response()->download(
                            $path,
                            'Certificado_' . ($record->n_anuncio ?? $record->id) . '.docx'
                        )->deleteFileAfterSend(true);
                    }),
                Action::make('Dar de Baja')
                    ->label('Dar de Baja')
                    ->iconButton()
                    ->tooltip('Dar de Baja')
                    ->color(Color::Red)
                    ->icon('heroicon-o-trash')
                    ->action(function (Anuncios $record) {

                    })
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([

            ]);
    }
}
