<?php

namespace App\Filament\Clusters\Visitas\Resources\Trabajadors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrabajadorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Mostrar la foto que guardamos en la carpeta/URL
                ImageColumn::make('persona.foto_url')
                    ->label('Foto')
                    ->disk('public') // Filament buscará dentro de storage/app/public/
                    ->circular(),

                // 2. Datos de la tabla Personas (Relación)
                TextColumn::make('persona.numero_documento')
                    ->label('DNI')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('persona.full_nombre') // Usando el accesor que creamos en el modelo Persona
                    ->label('Trabajador')
                    ->searchable(['nombres', 'apellido_paterno', 'apellido_materno']),

                // 3. Mostrar el Cargo Actual (Buscando en el historial)
                // Esto asume que el trabajador tiene una relación 'cargoActual' o 'historiales'
                TextColumn::make('historiales')
                    ->label('Cargo Actual')
                    ->formatStateUsing(function ($record) {
                        // Obtenemos el registro del historial que no tiene fecha_fin o es_actual
                        $actual = $record->historiales->where('es_actual', true)->first();
                        return $actual ? $actual->cargo?->nombre : 'Sin cargo';
                    })
                    ->description(
                        fn($record) =>
                        $record->historiales->where('es_actual', true)->first()?->area->nombre ?? ''
                    ),

                // 4. Datos propios de la tabla Trabajadores
                TextColumn::make('fecha_ingreso')
                    ->label('Ingreso')
                    ->date('d/m/Y')
                    ->sortable(),

                IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean(),
            ])
            ->defaultSort('updated_at','desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
