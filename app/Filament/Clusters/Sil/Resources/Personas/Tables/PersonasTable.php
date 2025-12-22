<?php

namespace App\Filament\Clusters\Sil\Resources\Personas\Tables;


use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class PersonasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('per_id', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                // El modelo Persona ya tiene un GlobalScope 'activo' que filtra per_filaeliminada = false
                // Pero lo reforzamos aquí explícitamente si se desea o se mantiene el patrón
                $query->where('per_filaeliminada', false);
                return $query;
            })
            ->columns([
                TextColumn::make('per_nombrerazonsocial')
                    ->label('Nombre / Razón Social')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('per_ruc')
                    ->label('RUC / DNI')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('per_direccion')
                    ->label('Dirección')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('per_telefono')
                    ->label('Teléfono')
                    ->searchable(),
                TextColumn::make('per_email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('per_expcodcon')
                    ->label('Cód. Constribuyente')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('per_ruc')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('ruc')
                            ->label('RUC / DNI')
                            ->placeholder('Ingrese RUC o DNI...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['ruc'],
                            fn($query, $term) => $query->where('per_ruc', 'ilike', "%{$term}%")
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['ruc'] ?? null) {
                            return 'RUC/DNI: ' . $data['ruc'];
                        }
                        return null;
                    }),

                \Filament\Tables\Filters\Filter::make('per_nombrerazonsocial')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('nombre')
                            ->label('Nombre / Razón Social')
                            ->placeholder('Ingrese Nombre...'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query->when(
                            $data['nombre'],
                            fn($query, $term) => $query->where('per_nombrerazonsocial', 'ilike', "%{$term}%")
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['nombre'] ?? null) {
                            return 'Nombre: ' . $data['nombre'];
                        }
                        return null;
                    }),

            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->filtersTriggerAction(
                fn(\Filament\Actions\Action $action) => $action
                    ->button()
                    ->label('Filtros')
                    ->modalHeading('Filtros de Personas')
                    ->color('info')
            )
            ->recordActions([
                // EditAction removed
            ])
            ->toolbarActions([
                // BulkActionGroup removed
            ]);
    }
}
