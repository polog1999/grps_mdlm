<?php

namespace App\Filament\Clusters\Visitas\Resources\Cargos\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CargoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('nombre_corto'),
                Toggle::make('estado')
                    ->default(true)
                    ->required()
            ]);
    }
}
