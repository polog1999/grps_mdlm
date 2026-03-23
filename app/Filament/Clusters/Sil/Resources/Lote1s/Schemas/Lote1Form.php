<?php

namespace App\Filament\Clusters\Sil\Resources\Lote1s\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class Lote1Form
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sector_cat'),
                TextInput::make('mzna_cat'),
                TextInput::make('lote_cat'),
                TextInput::make('cod_lote_cat'),
                TextInput::make('peligrosidad')
                    ->numeric(),
                TextInput::make('ubicacionareaverde')
                    ->numeric(),
                TextInput::make('subsector'),
                TextInput::make('zonif_anterior'),
                TextInput::make('lote_urbano'),
                TextInput::make('mz_urbana'),
                TextInput::make('arealoteurbano')
                    ->numeric(),
                TextInput::make('zonif_actual'),
                TextInput::make('altura_actual'),
                TextInput::make('cod_cat_anterior'),
                TextInput::make('altura_anterior'),
                TextInput::make('geometry'),
                TextInput::make('geometrycentroid'),
                TextInput::make('arealotecartografico')
                    ->numeric(),
                Toggle::make('zonif_trama'),
                TextInput::make('lot_esquina')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('sublote_urbano'),
                DateTimePicker::make('lot_fecha'),
                TextInput::make('lot_usuariobd')
                    ->default('"current_user"()'),
                TextInput::make('lot_catsc')
                    ->numeric(),
                TextInput::make('lot_catmz')
                    ->numeric(),
            ]);
    }
}
