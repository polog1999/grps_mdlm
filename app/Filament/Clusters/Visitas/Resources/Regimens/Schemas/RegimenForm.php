<?php

namespace App\Filament\Clusters\Visitas\Resources\Regimens\Schemas;

use App\Models\Regimen;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RegimenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                TextInput::make('cregimen')
                ->label('Nombre')
                    ->required(),
                TextInput::make('de_regimen')
                ->label('Descripción'),
                Select::make('parent_id')
                ->label('Pertenece a:')
                ->relationship('parentRegimen', 'cregimen')
                ->options(Regimen::where('estado', true)->where('parent_id', '=', null)->orderBy('cregimen', 'asc')->pluck('cregimen', 'id')),
                Toggle::make('estado')
                ->default(true)
                    ->required(),
                // TextInput::make('nu_tasa_impuesto')
                //     ->numeric(),
                // TextInput::make('user_id_creo')
                //     ->numeric(),
                // TextInput::make('user_id_modi')
                //     ->numeric(),
            ]);
    }
}
