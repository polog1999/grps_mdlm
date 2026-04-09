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
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->extraAttributes([
                        'x-on:input' => '$el.querySelector("input").value = $el.querySelector("input").value.toUpperCase()',
                    ])
                    ->trim()
                    ->dehydrateStateUsing(fn($state) => mb_strtoupper(trim($state)))
                    ->required()
            ]);
    }
}
