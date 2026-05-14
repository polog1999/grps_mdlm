<?php

namespace App\Filament\Clusters\Visitas\Resources\Areas\Tables;

use App\Models\Area;
use App\Models\Oficina;
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
use Filament\Support\Enums\FontWeight;
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
            ->query(Area::with(['oficinas', 'sede'])->where('estado', 1))
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
                                ->orWhere('abreviatura', 'like', "%{$search}%")
                                ->orWhereHas('oficinas', function ($q2) use ($search) {
                                    // Aquí usamos solo el nombre de la columna de la tabla relacionada
                                    $q2->where('nombre', 'like', "%{$search}%")
                                        ->orWhere('anexo', 'like', "%{$search}%");
                                });
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
                            ->where('estado', '1')
                            ->get()
                            ->mapWithKeys(function ($area) {
                                // Combinamos nombre y abreviatura en el label
                                return [$area->id_unidad_organica => "{$area->nombre} ({$area->abreviatura})"];
                            });
                    }),
                SelectFilter::make('id_unidad_organica')
                    ->label('Área')
                    ->searchable()
                    ->options(function () {
                        return Area::query()
                            ->where('estado', '1')
                            ->get()
                            ->mapWithKeys(function ($area) {
                                // Combinamos nombre y abreviatura en el label
                                return [$area->id_unidad_organica => "{$area->nombre} ({$area->abreviatura})"];
                            });
                    }),
                SelectFilter::make('oficina')
                    ->label('Oficina')
                    ->searchable()
                    ->options(function () {
                        // Traemos las oficinas para llenar el select
                        return Oficina::query()
                          
                            ->pluck('nombre', 'id_unidad_organica'); // 'id' de la oficina
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        // Filtramos la tabla Areas (Unidad Orgánica) buscando si tiene 
                        // la oficina seleccionada mediante la relación
                        return $query->whereHas('oficinas', function (Builder $q) use ($data) {
                            $q->where('id_unidad_organica', $data['value']);
                        });
                    })
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading(fn($record) => "Área: {$record->nombre} ({$record->abreviatura})")
                    ->modalWidth('5xl')
                    ->schema([
                        // Título o "Encabezado" de la tabla manual
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('header_nombre')->default('Oficina')->weight(FontWeight::Bold)->hiddenLabel(), // <--- Esto quita el texto 'header_nombre',
                                TextEntry::make('header_ubicacion')->default('Ubicación')->weight(FontWeight::Bold)->hiddenLabel(), // <--- Esto quita el texto 'header_nombre',
                                TextEntry::make('header_anexo')->default('Anexo')->weight(FontWeight::Bold)->hiddenLabel(), // <--- Esto quita el texto 'header_nombre',
                            ])
                            // Eliminamos 'hidden md:grid' y usamos 'grid' a secas
                            ->extraAttributes(['class' => 'border-b border-gray-200 pb-2 mb-2 grid uppercase text-xs tracking-wider text-gray-500'])
                            ->columnSpanFull(), // IMPORTANTE: Para que ocupe todo el ancho del modal

                        RepeatableEntry::make('oficinas')
                            ->label('') // Quitamos el label principal
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('nombre')
                                            ->label('Oficina') // El label servirá para vista móvil
                                            ->hiddenLabel() // Lo oculta en vista escritorio (si el grid lo permite)
                                            ->weight(FontWeight::Bold),

                                        TextEntry::make('ubicacion')
                                            ->label('Ubicación')
                                            ->hiddenLabel()
                                            ->icon('heroicon-m-map-pin'),

                                        TextEntry::make('anexo')
                                            ->label('Anexo')
                                            ->hiddenLabel()
                                            ->icon('heroicon-m-phone')
                                            ->badge()
                                            ->color('info'),
                                    ]),
                            ])
                            // ->contentBefore('') // Limpiamos cualquier decoración
                            ->contained(false) // Quita el borde de "tarjeta" para que parezcan filas
                            ->columnSpanFull()
                            ->hiddenLabel(), // <--- Esto quita el texto 'header_nombre',
                    ])
                    ->iconButton()
                    ->visible(fn($record) => $record->oficinas()->exists())
            ])

            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
