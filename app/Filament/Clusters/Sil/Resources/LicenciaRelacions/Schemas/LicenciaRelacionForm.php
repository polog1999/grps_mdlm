<?php

namespace App\Filament\Clusters\Sil\Resources\LicenciaRelacions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LicenciaRelacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('lic_id')
                    ->required()
                    ->numeric(),
                TextInput::make('lic_id_dependencia')
                    ->required()
                    ->numeric(),
                TextInput::make('esl_id')
                    ->required()
                    ->numeric(),
                TextInput::make('lil_item')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('lil_fecha')
                    ->required(),
                Toggle::make('lil_filaoriginal')
                    ->required(),
                Toggle::make('lil_filaeliminada')
                    ->required(),
            ]);
    }
}
