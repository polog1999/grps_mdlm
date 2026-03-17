<?php

namespace App\Filament\Clusters\Sistemas\Resources\Users\Schemas;

use App\Models\Sede;
use App\Models\Trabajador;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Set;
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
                Select::make('sede_id')
                ->label('Sede')
                ->live()
                ->options(function () {
                                return \App\Models\Sede::query()
                                    ->get()
                                    ->mapWithKeys(function ($sede) {
                                        $nombre = $sede->nombre ?? "Sede {$sede->id_sede}";
                                        return [$sede->id_sede => $nombre];
                                    });
                            })
                            ->searchable()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    // Buscamos el nombre del área basada en el ID seleccionado
                                    $sede = Sede::where('id_sede', $state)->value('nombre');
                                 
                                    $set('sede', $sede);
                                } else {
                                    $set('sede', null);
                                }
                            }),
                            Hidden::make('sede'),
                Select::make('trabajador_id')
                ->label('Trabajador')
                ->live()
                ->options(function () {
                                return \App\Models\Trabajador::query()
                                    // ->whereNot('regimen_id',['5','6','7','14'])
                                    ->where('id_estado',1)
                                    // ->with('persona') // Eager loading para evitar consultas lentas
                                    ->get()
                                    ->mapWithKeys(function ($trabajador) {
                                        // Forzamos que el label sea un string y no null
                                        $nombre = $trabajador->nombres . ' ' . $trabajador->apellidos ?? "Trabajador {$trabajador->id_usuario}";
                                        return [$trabajador->id_usuario => $nombre];
                                    });
                            })
                            ->searchable()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    // Buscamos el nombre del área basada en el ID seleccionado
                                    $trabajador = Trabajador::where('id_usuario', $state)->first(['nombres', 'apellidos']);
                                    $nombreCompleto = "{$trabajador->nombres} {$trabajador->apellidos}";
                                    // Guardamos ese nombre en el campo 'area_nombre'
                                    $set('nombres_completos', $nombreCompleto);
                                } else {
                                    $set('nombres_completos', null);
                                }
                            }),
                            Hidden::make('nombres_completos'),
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
