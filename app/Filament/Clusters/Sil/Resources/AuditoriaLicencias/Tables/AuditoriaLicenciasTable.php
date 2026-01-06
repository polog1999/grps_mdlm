<?php

namespace App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class AuditoriaLicenciasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('lic_filafecha', 'desc')
            ->columns([
                TextColumn::make('lic_numlic')
                    ->label('Nº Licencia')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('lic_expnum')
                    ->label('Nº Expediente')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('lic_filafecha')
                    ->label('Fecha Registro')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('creador.name')
                    ->label('Creado por')
                    ->sortable()
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('lic_creado_en')
                    ->label('Fecha Creado')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('actualizador.name')
                    ->label('Actualizado por')
                    ->sortable()
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('lic_actualizado_en')
                    ->label('Fecha Actualizado')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('lic_creado_por')
                    ->label('Creado Por')
                    ->relationship('creador', 'name')
                    ->searchable()
                    ->preload()
                    ->indicator('Creado Por'),

                SelectFilter::make('lic_actualizado_por')
                    ->label('Actualizado Por')
                    ->relationship('actualizador', 'name')
                    ->searchable()
                    ->preload()
                    ->indicator('Actualizado Por'),

                Filter::make('lic_creado_en')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('creado_desde')
                            ->label('Creado Desde'),
                        \Filament\Forms\Components\DatePicker::make('creado_hasta')
                            ->label('Creado Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['creado_desde'],
                                fn(Builder $query, $date) => $query->whereDate('lic_creado_en', '>=', $date)
                            )
                            ->when(
                                $data['creado_hasta'],
                                fn(Builder $query, $date) => $query->whereDate('lic_creado_en', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['creado_desde'] ?? null) {
                            $indicators['creado_desde'] = 'Creado Desde: ' . \Carbon\Carbon::parse($data['creado_desde'])->toFormattedDateString();
                        }
                        if ($data['creado_hasta'] ?? null) {
                            $indicators['creado_hasta'] = 'Creado Hasta: ' . \Carbon\Carbon::parse($data['creado_hasta'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),

                Filter::make('lic_actualizado_en')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('actualizado_desde')
                            ->label('Actualizado Desde'),
                        \Filament\Forms\Components\DatePicker::make('actualizado_hasta')
                            ->label('Actualizado Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['actualizado_desde'],
                                fn(Builder $query, $date) => $query->whereDate('lic_actualizado_en', '>=', $date)
                            )
                            ->when(
                                $data['actualizado_hasta'],
                                fn(Builder $query, $date) => $query->whereDate('lic_actualizado_en', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['actualizado_desde'] ?? null) {
                            $indicators['actualizado_desde'] = 'Actualizado Desde: ' . \Carbon\Carbon::parse($data['actualizado_desde'])->toFormattedDateString();
                        }
                        if ($data['actualizado_hasta'] ?? null) {
                            $indicators['actualizado_hasta'] = 'Actualizado Hasta: ' . \Carbon\Carbon::parse($data['actualizado_hasta'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),

            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->filtersTriggerAction(
                fn(\Filament\Actions\Action $action) => $action
                    ->button()
                    ->label('Filtros')
                    ->modalHeading('Filtros de Auditoría')
                    ->modalDescription('Utilice los filtros para refinar la búsqueda de registros de auditoría.')
                    ->modalIcon('heroicon-o-funnel')
                    ->color('info')
            )
            ->recordActions([
            ])
            ->toolbarActions([
            ]);
    }
}
