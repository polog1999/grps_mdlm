<?php

namespace App\Filament\Clusters\Sil\Resources\TipoResolucions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TipoResolucionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tir_descripcion'),
                DateTimePicker::make('tir_filafecha')
                    ->required(),
                Toggle::make('tir_filaoriginal')
                    ->required(),
                Toggle::make('tir_filaeliminada')
                    ->required(),
                Toggle::make('tir_activo'),
            ]);
    }
}
