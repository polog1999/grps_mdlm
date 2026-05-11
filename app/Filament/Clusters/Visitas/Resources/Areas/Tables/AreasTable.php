<?php

namespace App\Filament\Clusters\Visitas\Resources\Areas\Tables;

use App\Models\Area;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AreasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Area::with(['oficinas', 'sede', 'area']))
            ->columns([

                // Usamos Split para la fila principal
                Split::make([
                    // TextColumn::make('index')
                    //     ->label('#')
                    //     ->rowIndex() // <--- Esta es la clave en Filament v3
                    //     ->alignCenter(),
                    TextColumn::make('nombre')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('abreviatura')
                        ->label('Área')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('area.nombre')
                        ->label('Dependencia')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('sede.nombre')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('anexo')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('id_uo_estado')
                        ->label('Estado')
                        ->formatStateUsing(fn(int $state): string => match ($state) {
                            1 => 'Activo',
                            2 => 'Inactivo',
                            default => 'Desconocido',
                        })
                        ->badge()
                        ->color(fn(int $state): string => match ($state) {
                            1 => 'success', // Verde
                            2 => 'danger',  // Rojo
                            default => 'gray',
                        })
                        ->sortable()
                ]),

                Panel::make([
                    // Usamos la relación directamente
                    TextColumn::make('oficinas')
                        ->label('Detalle de Oficinas')
                        ->listWithLineBreaks()
                        ->bulleted()
                        // Importante: $state aquí es la instancia de la oficina relacionada
                        ->formatStateUsing(fn($state) => "Oficina: {$state->nombre} — Anexo: " . ($state->anexo ?? 'Sin anexo')),
                ])
                    ->collapsible() 
->collapsed()
                    // ESTA ES LA CLAVE: Solo se muestra si el conteo de oficinas es mayor a 0
                    ->visible(fn($record) => $record->oficinas()->count() > 0), // Para que aparezca cerrado por defecto
                    



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
                SelectFilter::make('id_uo_estado')
                    ->options([
                        1 => 'Activo',
                        2 => 'Inactivo',
                    ]),
                //     TrashedFilter::make(), // Permite filtrar por: "Solo activos", "Solo eliminados", "Todos"
                // ])
                // ->bulkActions([
                //     DeleteBulkAction::make(),
                //     RestoreBulkAction::make(),
                //     ForceDeleteBulkAction::make(),
            ])
            ->recordActions([
                // EditAction::make()
                //     ->visible(fn($record) => $record->deleted_at === null && auth()->user()->hasPermissionTo('edit::visitas_area')),
                // DeleteAction::make()
                //     ->visible(fn($record) => $record->deleted_at === null),        // Mueve a la papelera (Soft Delete)
                // RestoreAction::make()
                //     ->visible(fn($record) => $record->deleted_at !== null),       // Restaura el registro
                // ForceDeleteAction::make()
                //     ->visible(fn($record) => $record->deleted_at !== null),  // Borra permanentemente
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
