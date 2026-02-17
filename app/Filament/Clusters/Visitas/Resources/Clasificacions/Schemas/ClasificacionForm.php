<?php

namespace App\Filament\Clusters\Visitas\Resources\Clasificacions\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClasificacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de Clasificación')
                ->schema([
                TextInput::make('nombre')
                    ->required(),
                Toggle::make('in_esta')
                ->default(true)
                    ->required(),
                Toggle::make('estado')
                ->default(true)
                    ->required(),
                Hidden::make('user_id_creo')
                    ->default(auth()->id())
                    ->dehydrated(),
                Hidden::make('user_id_modi')
                    ->default(auth()->id())
                    // Esta línea fuerza que, al guardar, siempre se use el ID del usuario actual
                    ->formatStateUsing(fn() => auth()->id())
                    ->dehydrated(),
                ])
                
            ]);
    }
}
