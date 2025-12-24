<?php

namespace App\Filament\Clusters\Sistemas\Resources\Modules\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('filament_class')
                    ->required(),
                TextInput::make('cluster'),
            ]);
    }
}
