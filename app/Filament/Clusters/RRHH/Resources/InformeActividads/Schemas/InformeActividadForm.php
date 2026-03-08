<?php

namespace App\Filament\Clusters\RRHH\Resources\InformeActividads\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InformeActividadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero_informe')
                    ->required(),
                Select::make('usuario_id')
                    ->relationship('usuario', 'name')
                    ->required(),
                Textarea::make('url_archivo')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('fecha_subida')
                    ->required(),
            ]);
    }
}
