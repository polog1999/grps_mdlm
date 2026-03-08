<?php

namespace App\Filament\Clusters\RRHH\Resources\Asistencias\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class AsistenciaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('usuario_id')
                    ->default(fn() => Auth::id()),
                Placeholder::make('usuario_nombre')
                    ->label('Usuario')
                    ->content(fn() => Auth::user()->name),
                DateTimePicker::make('hora_entrada')
                    ->required(),
                DateTimePicker::make('hora_salida'),
            ]);
    }
}
