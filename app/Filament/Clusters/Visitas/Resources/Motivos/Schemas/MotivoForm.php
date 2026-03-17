<?php

namespace App\Filament\Clusters\Visitas\Resources\Motivos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MotivoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('motivo')
                ->required()
            ]);
    }
}
