<?php

namespace App\Filament\Clusters\Sil\Resources\Personas\Schemas;

use Filament\Forms\Components\Hidden;
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
                    ->label('Nombre razón social')
                    ->required(),
                TextInput::make('per_ruc')
                    ->label('RUC')
                    ->required(),
                TextInput::make('per_direccion')
                    ->label('Dirección')
                    ->required(),
                TextInput::make('per_telefono')
                    ->label('Teléfono')
                    ->tel(),
                TextInput::make('per_email')
                    ->label('Email')
                    ->email(),


                Hidden::make('per_expcodcon')
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }
}
