<?php

namespace App\Filament\Clusters\Visitas\Resources\Areas\Tables;

use App\Models\Area;
use App\Models\Sede;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AreasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Area::with(['oficinas', 'sede'])->where('id_uo_estado', 1))
            ->columns([
                // TextColumn::make('index')
                //     ->label('#')
                //     ->rowIndex() // <--- Esta es la clave en Filament v3
                //     ->alignCenter(),
                TextColumn::make('nombre')
                    ->label('Nombre del Área')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($q) use ($search) {
                            $q->where('nombre', 'like', "%{$search}%")
                                ->orWhere('abreviatura', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('sede.nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('anexo')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('id_uo_estado')
                //     ->label('Estado')
                //     ->formatStateUsing(fn(int $state): string => match ($state) {
                //         1 => 'Activo',
                //         2 => 'Inactivo',
                //         default => 'Desconocido',
                //     })
                //     ->badge()
                //     ->color(fn(int $state): string => match ($state) {
                //         1 => 'success', // Verde
                //         2 => 'danger',  // Rojo
                //         default => 'gray',
                //     })
                //     ->sortable()


                // Panel::make([
                //     // Usamos la relación directamente
                //     TextColumn::make('oficinas')
                //         ->label('Detalle de Oficinas')
                //         ->listWithLineBreaks()
                //         ->bulleted()
                //         // Importante: $state aquí es la instancia de la oficina relacionada
                //         ->formatStateUsing(fn($state) => "Oficina: {$state->nombre} — Anexo: " . ($state->anexo ?? 'Sin anexo')),
                // ])
                //     ->collapsible()
                //     ->collapsed()
                //     // ESTA ES LA CLAVE: Solo se muestra si el conteo de oficinas es mayor a 0
                //     ->visible(fn($record) => $record->oficinas()->count() > 0), // Para que aparezca cerrado por defecto




                // TextColumn::make('parentArea.nombre')
                //     ->searchable()
                //     ->default('Sin área superior'), // Muestra esto si la relación es nula,
                // TextColumn::make('orden')
                //     ->numeric()
                //     ->sortable(),
                // IconColumn::make('estado')
                //     ->boolean(),
            ])
            ->defaultSort('id_unidad_organica', 'asc')
            ->filters([
                SelectFilter::make('id_sede')
                    ->label('Sede')
                    ->searchable()
                    ->options(fn() => Sede::pluck('nombre', 'id_sede')),
                SelectFilter::make('id_unidad_organica')
                    ->label('Área')
                    ->searchable()
                    ->options(function () {
                        return Area::query()
                            ->where('id_uo_estado', '1')
                            ->get()
                            ->mapWithKeys(function ($area) {
                                // Combinamos nombre y abreviatura en el label
                                return [$area->id_unidad_organica => "{$area->nombre} ({$area->abreviatura})"];
                            });
                    }),
            ])
            ->recordActions([
                ViewAction::make()

                    ->modalHeading(fn($record) => "Área: $record->nombre ($record->abreviatura)" ?? 'Oficina')
                    ->modalWidth('4xl') // Un ancho mayor para que las 2 columnas respiren
                    ->schema([

                        RepeatableEntry::make('oficinas') // Relación HasMany
                            ->label('Oficinas')
                            ->schema([
                                Section::make(fn($record) => $record->nombre ?? 'Oficina')
                                    ->description('Haz clic para ver detalles de contacto y ubicación')
                                    ->schema([
                                        TextEntry::make('anexo')
                                            ->icon('heroicon-m-phone'),
                                        TextEntry::make('ubicacion')
                                            ->icon('heroicon-m-map-pin'),
                                    ])
                                    ->columns(2)
                                    ->collapsible() // Esto permite expandir/contraer
                                    ->collapsed(),  // Opcional: que aparezcan cerradas por defecto
                            ])
                            ->grid(1) // Una oficina debajo de otra
                            ->columnSpanFull(),
                    ])->iconButton()
                    ->tooltip('Ver Oficinas')
                    ->visible(fn($record) => $record->oficinas()->count() > 0), // Para que aparezca cerrado por defecto,
            ])

            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
