<?php

namespace App\Filament\Clusters\Visitas\Resources\Sedes\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form; // Asegúrate de que use Form si es para un Resource
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SedeForm
{

    /*
 public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('guard_name')
                    ->required(),
            ]);
    }*/
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la Sede')
                    ->description('Detalles principales de la ubicación.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre de la Sede')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Oficina Central'),

                                TextInput::make('aforo')
                                    ->label('Capacidad Máxima (Aforo)')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->placeholder('0'),
                            ]),

                        Toggle::make('estado')
                            ->label('Sede Activa')
                            ->default(true)
                            ->helperText('Define si la sede está disponible para recibir visitas.'),

                    ]),
                Hidden::make('user_id_creo')
                    ->default(auth()->id())
                    ->dehydrated(),
                Hidden::make('user_id_modi')
                    ->default(auth()->id())
                    // Esta línea fuerza que, al guardar, siempre se use el ID del usuario actual
                    ->formatStateUsing(fn() => auth()->id())
                    ->dehydrated(),
            ]);
    }
}
