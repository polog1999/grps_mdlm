<?php

namespace App\Filament\Clusters\Visitas\Resources\TipoDocumentos\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TipoDocumentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('nombre_corto')
                    ->required(),
                Toggle::make('estado')
                ->default(true)
                    ->required()
            ]);
    }
}
