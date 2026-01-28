<?php

namespace App\Filament\Clusters\Sil\Resources\LicenciaRelacions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Support\Colors\Color;

class LicenciaRelacionsTable
{
    public static function configure(Table $table): Table
    {

        return $table
            ->defaultSort('lil_id', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('lil_filaeliminada', false);
                return $query;
            })
            ->columns([
                TextColumn::make('licencia.lic_numlic')
                    ->label('Licencia')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('licencia.tipoEstadoLicencia.esl_descripcion')
                    ->label('Estado Licencia')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn($state) => match (strtoupper($state ?? '')) {
                        'ACTIVO' => Color::Green,
                        'ACTUALIZADO' => Color::Teal,
                        'ANULADO' => Color::Red,
                        'BAJA' => Color::Rose,
                        'DUPLICADO' => Color::Orange,
                        'HISTORICO' => Color::Slate,
                        'INAC' => Color::Gray,
                        'SUSTITUIDO' => Color::Amber,
                        'TRANSFERIDO' => Color::Sky,
                        default => Color::Gray,
                    }),

                TextColumn::make('licencia.tipoLicencia.tli_descripcion')
                    ->label('Tipo Licencia')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn($state) => match (strtoupper(trim($state ?? ''))) {

                        'TEMPORAL' => Color::Sky,
                        'CESIONARIO' => Color::Pink,
                        'ESPECIAL' => Color::Fuchsia,
                        'INDETERMINADA' => Color::Emerald,
                        'CORPORATIVA' => Color::Teal,

                        default => Color::Gray,
                    }),
                TextColumn::make('licenciaDependencia.lic_numlic')
                    ->label('Licencia Dependencia')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('licenciaDependencia.tipoEstadoLicencia.esl_descripcion')
                    ->label('Estado Dependencia')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn($state) => match (strtoupper($state ?? '')) {
                        'ACTIVO' => Color::Green,
                        'ACTUALIZADO' => Color::Teal,
                        'ANULADO' => Color::Red,
                        'BAJA' => Color::Rose,
                        'DUPLICADO' => Color::Orange,
                        'HISTORICO' => Color::Slate,
                        'INAC' => Color::Gray,
                        'SUSTITUIDO' => Color::Amber,
                        'TRANSFERIDO' => Color::Sky,
                        default => Color::Gray,
                    }),

                TextColumn::make('licenciaDependencia.tipoLicencia.tli_descripcion')
                    ->label('Tipo Dependencia')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn($state) => match (strtoupper(trim($state ?? ''))) {
                        'TEMPORAL' => Color::Sky,
                        'CESIONARIO' => Color::Pink,
                        'ESPECIAL' => Color::Fuchsia,
                        'INDETERMINADA' => Color::Emerald,
                        'CORPORATIVA' => Color::Teal,

                        default => Color::Gray,
                    }),

                TextColumn::make('lil_fecha')
                    ->label('Fecha de Relación')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('lic_id')
                    ->label('Licencia')
                    ->relationship('licencia', 'lic_numlic')
                    ->searchable()
                    ->preload()
                    ->indicator('Licencia'),

                SelectFilter::make('lic_id_dependencia')
                    ->label('Licencia Dependencia')
                    ->relationship('licenciaDependencia', 'lic_numlic')
                    ->searchable()
                    ->preload()
                    ->indicator('Licencia Dependencia'),

                SelectFilter::make('estado_licencia')
                    ->label('Estado Licencia')
                    ->relationship('licencia.tipoEstadoLicencia', 'esl_descripcion')
                    ->searchable()
                    ->preload()
                    ->indicator('Estado Licencia'),

                SelectFilter::make('estado_dependencia')
                    ->label('Estado Dependencia')
                    ->relationship('licenciaDependencia.tipoEstadoLicencia', 'esl_descripcion')
                    ->searchable()
                    ->preload()
                    ->indicator('Estado Dependencia'),

                SelectFilter::make('tipo_licencia')
                    ->label('Tipo Licencia')
                    ->relationship('licencia.tipoLicencia', 'tli_descripcion')
                    ->searchable()
                    ->preload()
                    ->indicator('Tipo Licencia'),

                SelectFilter::make('tipo_dependencia')
                    ->label('Tipo Dependencia')
                    ->relationship('licenciaDependencia.tipoLicencia', 'tli_descripcion')
                    ->searchable()
                    ->preload()
                    ->indicator('Tipo Dependencia'),

                Filter::make('lil_fecha')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('fecha_desde')
                            ->label('Fecha Desde'),
                        \Filament\Forms\Components\DatePicker::make('fecha_hasta')
                            ->label('Fecha Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_desde'],
                                fn(Builder $query, $date) => $query->whereDate('lil_fecha', '>=', $date)
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn(Builder $query, $date) => $query->whereDate('lil_fecha', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['fecha_desde'] ?? null) {
                            $indicators['fecha_desde'] = 'Desde: ' . \Carbon\Carbon::parse($data['fecha_desde'])->toFormattedDateString();
                        }
                        if ($data['fecha_hasta'] ?? null) {
                            $indicators['fecha_hasta'] = 'Hasta: ' . \Carbon\Carbon::parse($data['fecha_hasta'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),

            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->filtersTriggerAction(
                fn(\Filament\Actions\Action $action) => $action
                    ->button()
                    ->label('Filtros')
                    ->modalHeading('Filtros de Relaciones')
                    ->color('info')
            )
            ->recordActions([
            ])
            ->toolbarActions([
            ]);
    }
}
