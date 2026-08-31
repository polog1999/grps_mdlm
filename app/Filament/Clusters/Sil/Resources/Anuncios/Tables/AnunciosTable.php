<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Tables;

use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\Dictamen;
use App\Models\Anuncios;
use App\Services\Sil\Anuncios\InformeAnuncioService;
use App\Services\Sil\Anuncios\CertificadoAnuncioService;
use Dotswan\MapPicker\Fields\Map;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class AnunciosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('expediente.fecha_expediente', 'desc')
            ->recordUrl(null)
            ->columns([
                TextColumn::make('n_anuncio')
                    ->label('N° Anuncio')
                    ->badge()
                    ->default('Sin número')
                    ->color(fn($state) => $state === 'Sin número' ? 'gray' : 'info')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('documentos')
                    ->label('N° Informe Técnico')
                    ->getStateUsing(fn(Anuncios $record) => $record->documentos()->where('tipo_documento', 'INFORME TÉCNICO')->first()?->n_documento)
                    ->sortable(query: function (Builder $query, string $direction) {
                        return $query->orderBy(
                            \App\Models\DocumentosAnuncio::select('n_documento')
                                ->whereColumn('anuncios.documentos_anuncio.anuncio_id', 'anuncios.anuncios.id')
                                ->where('tipo_documento', 'INFORME TÉCNICO')
                                ->limit(1),
                            $direction
                        );
                    })
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('documentos', function ($q) use ($search) {
                            $q->where('tipo_documento', 'INFORME TÉCNICO')
                                ->where('n_documento', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('expediente.n_expediente')
                    ->label('N° Expediente')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('expediente.fecha_expediente')
                    ->label('Fecha Expediente')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('dictamen')
                    ->badge()
                    ->sortable()
                    ->color(fn(Dictamen $state): string => match ($state) {
                        Dictamen::PROCEDENTE => 'success',
                        Dictamen::IMPROCEDENTE => 'danger',
                        Dictamen::OBSERVADO => 'warning',
                    })
                    ->searchable(),
                TextColumn::make('expediente.snapshot_solicitante_nombre_completo')
                    ->label('Solicitante')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('expediente.snapshot_solicitante_dni')
                    ->label('DNI/RUC Solicitante')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('expediente.snapshot_legal_nombre')
                    ->label('Representante Legal')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('expediente.snapshot_legal_dni_ruc')
                    ->label('DNI/RUC Representante Legal')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('expediente.snapshot_solicitante_direccion')
                    ->label('Dirección del Predio')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fecha_recepcion_evaluar')
                    ->label('Fecha Recepción Evaluar')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('caracteristicaFisica.descripcion')
                    ->label('Característica Física')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tipoAnuncio.descripcion')
                    ->label('Tipo de Anuncio')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('licencia.lic_numlic')
                    ->label('N° Licencia')
                    ->sortable(query: function (Builder $query, string $direction) {
                        return $query->orderBy(
                            \Illuminate\Support\Facades\DB::table('licencia.licencia')
                                ->select('lic_numlic')
                                ->whereRaw('licencia.licencia.lic_id::varchar = anuncios.anuncios.id_licencia')
                                ->limit(1),
                            $direction
                        );
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereExists(function ($q) use ($search) {
                            $q->select(\Illuminate\Support\Facades\DB::raw(1))
                                ->from('licencia.licencia')
                                ->whereRaw('anuncios.anuncios.id_licencia = licencia.licencia.lic_id::varchar')
                                ->whereRaw('lower(lic_numlic::text) like ?', ['%' . strtolower($search) . '%']);
                        });
                    }),
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('n_de_caras')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('estado_anuncio')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('derivadoLegal.name')
                    ->label('Derivado a Legal')
                    ->sortable(),

                TextColumn::make('fecha_derivado')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('vigencia')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fecha_inicio_vigencia')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('fecha_fin_vigencia')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('maps_url')
                    ->label('Ubicación')
                    ->getStateUsing(fn(Anuncios $record) => ($record->latitud && $record->longitud) ? 'Ver Mapa' : 'No Asignado')
                    ->url(fn(Anuncios $record) => ($record->latitud && $record->longitud) ? $record->maps_url : null)
                    ->color(fn(Anuncios $record) => ($record->latitud && $record->longitud) ? 'info' : 'gray')
                    ->icon(fn(Anuncios $record) => ($record->latitud && $record->longitud) ? 'heroicon-o-map-pin' : null)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                Filter::make('fecha_expediente')
                    ->form([
                        DatePicker::make('desde')
                            ->label('Desde (Fecha Expediente)')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('hasta')
                            ->label('Hasta (Fecha Expediente)')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn(Builder $query, $date): Builder => $query->whereHas('expediente', function ($q) use ($date) {
                                    $q->whereDate('fecha_expediente', '>=', $date);
                                }),
                            )
                            ->when(
                                $data['hasta'],
                                fn(Builder $query, $date): Builder => $query->whereHas('expediente', function ($q) use ($date) {
                                    $q->whereDate('fecha_expediente', '<=', $date);
                                }),
                            );
                    }),

                Filter::make('fecha_emision')
                    ->form([
                        DatePicker::make('desde')
                            ->label('Desde (Fecha Emisión)')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('hasta')
                            ->label('Hasta (Fecha Emisión)')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn(Builder $query, $date): Builder => $query->whereHas('documentos', function ($q) use ($date) {
                                    $q->whereDate('fecha_emision', '>=', $date);
                                }),
                            )
                            ->when(
                                $data['hasta'],
                                fn(Builder $query, $date): Builder => $query->whereHas('documentos', function ($q) use ($date) {
                                    $q->whereDate('fecha_emision', '<=', $date);
                                }),
                            );
                    }),
            ])
            ->recordActions([
                EditAction::make()->iconButton()->visible(fn() => auth()->user()->hasPermissionTo('edit::anuncios')),
                Action::make('Generar Informe')
                    ->label('Generar Informe')
                    ->iconButton()
                    ->tooltip('Generar Informe')
                    ->color(Color::Cyan)
                    ->icon('heroicon-o-document-text')
                    ->visible(fn() => auth()->user()->hasPermissionTo('generate_report::anuncios'))
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
                    ->visible(fn() => auth()->user()->hasPermissionTo('generate_certificate::anuncios'))
                    ->url(fn(Anuncios $record) => route('anuncios.certificado-pdf', ['anuncio' => $record->id]))
                    ->openUrlInNewTab(),
                /*
                Action::make('Dar de Baja')
                    ->label('Dar de Baja')
                    ->iconButton()
                    ->tooltip('Dar de Baja')
                    ->color(Color::Red)
                    ->icon('heroicon-o-trash')
                    ->action(function (Anuncios $record) {

                    }),
                */
                Action::make('Geolocalizar')
                    ->label('Geolocalizar')
                    ->iconButton()
                    ->tooltip('Ubicación Geográfica')
                    ->color(Color::Orange)
                    ->icon('heroicon-o-map-pin')
                    ->fillForm(fn($record): array => [
                        // Si hay data en DB, la usamos. Si no, forzamos TUS coordenadas.
                        'latitud' => $record->latitud ?? -12.081884296809166,
                        'longitud' => $record->longitud ?? -76.9134418482905,
                        'location' => [
                            'lat' => $record->latitud ?? -12.081884296809166,
                            'lng' => $record->longitud ?? -76.9134418482905,
                        ],
                    ])
                    ->form([
                        Map::make('location')
                            ->label('Ubicación del Anuncio')
                            ->columnSpanFull()
                            ->defaultLocation(
                                latitude: -12.081884296809166,
                                longitude: -76.9134418482905
                            )
                            ->afterStateUpdated(function (array $state, callable $set): void {
                                $set('latitud', $state['lat'] ?? null);
                                $set('longitud', $state['lng'] ?? null);
                            })
                            ->extraStyles([
                                'min-height: 400px',
                                'border-radius: 8px'
                            ])
                            ->live(onBlur: true)
                            ->showMarker()
                            ->markerColor("#2563eb")
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable()
                            ->clickable(true),

                        // Cambiamos disabled() por readOnly() para que la data viaje en el submit
                        TextInput::make('latitud')->numeric()->readOnly()->hidden(),
                        TextInput::make('longitud')->numeric()->readOnly()->hidden(),
                    ])
                    // AQUÍ ESTÁ LA MAGIA: Le decimos a Filament cómo guardar
                    ->action(function (array $data, $record): void {
                        try {
                            // Actualizamos el registro en la base de datos
                            $record->update([
                                'latitud' => $data['location']['lat'] ?? $data['latitud'],
                                'longitud' => $data['location']['lng'] ?? $data['longitud'],
                            ]);

                            Notification::make()
                                ->title('Ubicación actualizada correctamente')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error al actualizar la ubicación')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([]);
    }
}
