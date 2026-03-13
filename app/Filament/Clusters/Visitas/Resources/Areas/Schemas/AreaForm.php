<?php

namespace App\Filament\Clusters\Visitas\Resources\Areas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AreaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                Select::make('parent_id')
                ->label('Area Padre')
                ->relationship('parentArea', 'nombre'),
                    
                // Select::make('sede_id')
                //     ->relationship('sede', 'nombre'),
                TextInput::make('nombre_corto')
                ->required(),
                TextInput::make('orden')
                    ->numeric(),
                Toggle::make('estado')
                ->default(true)
                    ->required()
            ]);
    }
}
