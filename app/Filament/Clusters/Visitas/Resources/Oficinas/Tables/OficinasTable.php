<?php

namespace App\Filament\Clusters\Visitas\Resources\Oficinas\Tables;

use App\Models\Oficina;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OficinasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Oficina::whereNotNull('anexo')
                ->where('anexo', '!=', ''))
            ->columns([

                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('ubicacion')
                    ->searchable(),
                TextColumn::make('anexo')
                    ->searchable(),
                TextColumn::make('area.nombre')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
              
            ]);
         
    }
}
