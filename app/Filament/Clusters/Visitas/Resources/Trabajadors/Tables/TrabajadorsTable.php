<?php

namespace App\Filament\Clusters\Visitas\Resources\Trabajadors\Tables;

use App\Models\Area;
use App\Models\Trabajador;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TrabajadorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Trabajador::query()->where('id_estado', 1))
            ->columns([
                // // 1. Mostrar la foto que guardamos en la carpeta/URL
                // ImageColumn::make('persona.foto_url')
                //     ->label('Foto')
                //     ->disk('public') // Filament buscará dentro de storage/app/public/
                //     ->circular(),

                // 2. Datos de la tabla Personas (Relación)
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex() // <--- Esta es la clave en Filament v3
                    ->alignCenter(),
                TextColumn::make('nro_documento')
                    ->label('DNI')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nombres')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('apellidos')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('area.nombre')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('regimen.nombre')
                    ->label('Régimen')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cargo.nombre')
                    ->label('Cargo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('fecha_nacimiento')
                    // ->label('Apellidos')
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('fecha_registro')
                    ->dateTime('d/m/Y H:i A')
                    // ->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('id_estado')
                    ->label('Estado')
                    ->formatStateUsing(fn(int $state): string => match ($state) {
                        1 => 'Activo',
                        2 => 'Suspendido',
                        3 => 'Eliminado',
                        default => 'Desconocido',
                    })
                    ->badge()
                    ->color(fn(int $state): string => match ($state) {
                        1 => 'success', // Verde
                        2 => 'gray',     //Gris
                        3 => 'danger',  // Rojo
                        default => 'alert',
                    })
                    ->sortable()

                // TextColumn::make('persona.full_nombre') // Usando el accesor que creamos en el modelo Persona
                //     ->label('Trabajador')
                //     ->searchable(['nombres', 'apellido_paterno', 'apellido_materno']),

                // // 3. Mostrar el Cargo Actual (Buscando en el historial)
                // // Esto asume que el trabajador tiene una relación 'cargoActual' o 'historiales'
                // TextColumn::make('cargo_actual')
                //     ->label('Cargo Actual')
                //     ->getStateUsing(function ($record) {
                //         // Obtenemos el registro del historial que no tiene fecha_fin o es_actual
                //         $actual = $record->historiales->where('es_actual', true)->first();
                //         return $actual ? $actual->cargo?->nombre : 'Sin cargo';
                //     })
                //     ->description(
                //         fn($record) =>
                //         $record->historiales->where('es_actual', true)->first()?->area->nombre ?? ''
                //     ),

                // // 4. Datos propios de la tabla Trabajadores
                // TextColumn::make('fecha_ingreso')
                //     ->label('Ingreso')
                //     ->date('d/m/Y')
                //     ->sortable(),

                // IconColumn::make('estado')
                //     ->label('Estado')
                //     ->boolean(),
            ])
            // ->defaultSort('updated_at','desc')
            ->filters([
                // // Puedes añadir filtros aquí, por ejemplo: solo sedes activas
                SelectFilter::make('id_unidad_organica')
                    ->label('Área')
                    ->searchable()
                    ->options(fn() => Area::pluck('nombre', 'id_unidad_organica')),
            ]);
        // ->recordActions([
        //     EditAction::make(),
        // ])
        // ->toolbarActions([
        //     BulkActionGroup::make([
        //         DeleteBulkAction::make(),
        //     ]),
        // ]);
    }
}
