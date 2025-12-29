<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoBorrados\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CertificadoBorradoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('cin_id')
                    ->required()
                    ->numeric(),
                Textarea::make('cin_razon_borrado')
                    ->columnSpanFull(),
            ]);
    }
}
