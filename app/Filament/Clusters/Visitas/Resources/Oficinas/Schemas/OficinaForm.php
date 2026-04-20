<?php

namespace App\Filament\Clusters\Visitas\Resources\Oficinas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OficinaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_unidad_organica')
                    ->required()
                    ->numeric(),
                TextInput::make('nombre')
                    ->default(null),
                TextInput::make('ubicacion')
                    ->default(null),
                TextInput::make('anexo')
                    ->default(null),
            ]);
    }
}
