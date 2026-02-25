<?php

namespace App\Filament\Clusters\Sistemas\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('user_id')
                ->relationship('sede', 'nombre'),
                Select::make('trabajador_id')
                ->options(function () {
                                return \App\Models\Trabajador::query()
                                    // ->whereNot('regimen_id',['5','6','7','14'])
                                    ->where('estado',true)
                                    ->with('persona') // Eager loading para evitar consultas lentas
                                    ->get()
                                    ->mapWithKeys(function ($trabajador) {
                                        // Forzamos que el label sea un string y no null
                                        $nombre = $trabajador->persona->full_nombre ?? "Trabajador {$trabajador->id}";
                                        return [$trabajador->id => $nombre];
                                    });
                            })
                            ->searchable(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Textarea::make('two_factor_secret')
                    ->columnSpanFull(),
                Textarea::make('two_factor_recovery_codes')
                    ->columnSpanFull(),
                DateTimePicker::make('two_factor_confirmed_at'),
            ]);
    }
}
