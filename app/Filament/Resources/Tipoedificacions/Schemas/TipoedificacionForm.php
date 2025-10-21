<?php


namespace App\Filament\Resources\Tipoedificacions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;

class TipoedificacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tie_descripcion')->label('Descripción')->required(),
                TextInput::make('tie_sigla')->label('Sigla'),
                Toggle::make('tie_activo')->label('Activo')->default(true),
                Toggle::make('tie_filaoriginal')->label('Fila original')->default(true),
                Toggle::make('tie_filaeliminada')->label('Eliminada')->default(false),
                Hidden::make('usa_id')->default(null),
            ]);
    }
}