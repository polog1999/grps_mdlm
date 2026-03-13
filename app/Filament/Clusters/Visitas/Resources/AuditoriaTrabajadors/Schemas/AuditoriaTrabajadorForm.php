<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AuditoriaTrabajadorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('persona_id')
                    ->required()
                    ->numeric(),
                TextInput::make('cui')
                    ->numeric(),
                TextInput::make('regimen_id')
                    ->numeric(),
                TextInput::make('clasificacion_id')
                    ->numeric(),
                DatePicker::make('fecha_ingreso'),
                Toggle::make('estado')
                    ->required(),
                TextInput::make('user_id_creo')
                    ->numeric(),
                TextInput::make('user_id_modi')
                    ->numeric(),
                
            ]);
    }
}
