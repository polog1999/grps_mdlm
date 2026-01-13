<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoBorrados\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CertificadoBorradosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
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
                SelectFilter::make('usa_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->indicator('Usuario'),

                Filter::make('cin_numero')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('numero_certificado')
                            ->label('N° Certificado')
                            ->placeholder('Ej: 123')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['numero_certificado'],
                            fn(Builder $query, $numero) => $query->whereHas('certificadoInspeccion', function ($q) use ($numero) {
                                $q->where('cin_numero', $numero);
                            })
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['numero_certificado'] ?? null) {
                            return 'N° Certificado: ' . $data['numero_certificado'];
                        }
                        return null;
                    }),

                Filter::make('cin_licencia')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('numero_licencia')
                            ->label('N° Licencia')
                            ->placeholder('Ej: 004008'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['numero_licencia'],
                            fn(Builder $query, $numero) => $query->whereHas('certificadoInspeccion', function ($q) use ($numero) {
                                $q->where('cin_licencia', 'LIKE', "%{$numero}%");
                            })
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['numero_licencia'] ?? null) {
                            return 'N° Licencia: ' . $data['numero_licencia'];
                        }
                        return null;
                    }),

                Filter::make('cin_expediente')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('numero_expediente')
                            ->label('N° Expediente')
                            ->placeholder('Ej: 2025-001234'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['numero_expediente'],
                            fn(Builder $query, $numero) => $query->whereHas('certificadoInspeccion', function ($q) use ($numero) {
                                $q->where('cin_expediente', 'LIKE', "%{$numero}%");
                            })
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['numero_expediente'] ?? null) {
                            return 'N° Expediente: ' . $data['numero_expediente'];
                        }
                        return null;
                    }),

                Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('fecha_desde')
                            ->label('Fecha Desde')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        \Filament\Forms\Components\DatePicker::make('fecha_hasta')
                            ->label('Fecha Hasta')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_desde'],
                                fn(Builder $query, $date) => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn(Builder $query, $date) => $query->whereDate('created_at', '<=', $date)
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
                    ->modalHeading('Filtros de Certificados Borrados')
                    ->color('info')
            )
            ->recordActions([
            ])
            ->toolbarActions([

            ]);
    }
}
