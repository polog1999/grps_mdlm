<?php

namespace App\Filament\Clusters\Sil\Resources\TipoLicencias\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TipoLicenciaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tli_descripcion'),
                Toggle::make('tli_filaoriginal')
                    ->required(),
                Toggle::make('tli_filaeliminada')
                    ->required(),
                Toggle::make('tli_activo'),
            ]);
    }
}
