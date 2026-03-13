<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditoriaTrabajadorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex() // <--- Esta es la clave en Filament v3
                    ->alignCenter(),
                TextColumn::make('persona.tipoDocumento.nombre_corto')
                    ->sortable(),
                TextColumn::make('persona.numero_documento')
                ->label('Número Documento')
                    ->sortable()
                    ->copyable(),
                // TextColumn::make('cui')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('regimen_id')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('clasificacion_id')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('fecha_ingreso')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('userCreo.name')
                    ->label('Creado por:')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('userModi.name')
                    ->label('Editado por:')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y h:i:A' )
                    ->sortable(),
                // ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Editado')
                    ->dateTime('d/m/Y h:i:A' )
                    ->sortable(),
                IconColumn::make('estado')
                    ->boolean(),
                // ->toggleable(isToggledHiddenByDefault: true),

            ]);
            // ->filters([
            //     //
            // ])
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
