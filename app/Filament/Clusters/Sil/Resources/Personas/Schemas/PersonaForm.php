<?php

namespace App\Filament\Clusters\Sil\Resources\Personas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PersonaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('per_nombrerazonsocial')
                    ->required(),
                TextInput::make('per_ruc'),
                TextInput::make('per_direccion'),
                TextInput::make('per_telefono')
                    ->tel(),
                TextInput::make('per_email')
                    ->email(),
                Toggle::make('per_filaoriginal')
                    ->required(),
                Toggle::make('per_filaeliminada')
                    ->required(),
                TextInput::make('per_expcodcon'),
            ]);
    }
}
