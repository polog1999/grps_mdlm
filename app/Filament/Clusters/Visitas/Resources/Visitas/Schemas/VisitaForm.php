<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Schemas;

use App\Models\Area;
use App\Models\Trabajador;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Ramsey\Collection\Set;

class VisitaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del Visitante')
                    ->schema([
                        Select::make('tipo_documento_id')
                            ->relationship('persona.tipoDocumento', 'nombre')
                            ->live()
                            ->required(),
                        TextInput::make('numero_documento')
                        ->required()
                            ->suffixAction(
                                Action::make('buscar_visitante')
                                    ->icon('heroicon-m-magnifying-glass')
                                    ->visible(fn(Get $get) => $get('tipo_documento_id') == 1)
                                    ->action(fn($state, Set $set) => self::buscarVisitante($state, $set))
                            )->live(),
                        TextInput::make('nombres')
                            ->required()
                            ->readOnly(fn(Get $get) => $get('tipo_documento_id') == 1 && !$get('pide_fallo')),
                        TextInput::make('apellido_paterno')
                            ->required()
                            ->readOnly(fn(Get $get) =>
                            $get('tipo_documento_id') == 1 && $get('pide_fallo') == false),

                        TextInput::make('apellido_materno')
                            ->required()
                            ->readOnly(fn(Get $get) =>
                            $get('tipo_documento_id') == 1 && $get('pide_fallo') == false),
                        Hidden::make('pide_fallo')->default(false)->live(),

                    ])->columns(2),

                Section::make('Detalles de la Visita')
                    ->schema([
                        Hidden::make('sede_id')->default(1),
                        Hidden::make('user_id_registra')
                            ->default(auth()->id())
                            ->dehydrated(),
                        Select::make('area_id')
                            ->label('Área de Destino')
                            ->options(Area::pluck('nombre', 'id'))
                            ->live() // Crucial para que el segundo select se entere del cambio
                            ->required(),

                        Select::make('trabajador_id_autoriza')
                            ->label('Persona a quien visita')
                            ->options(function (Get $get) {
                                $areaId = $get('area_id');
                                if (!$areaId) return [];

                                return \App\Models\Trabajador::query()
                                    ->whereHas('historiales', function ($query) use ($areaId) {
                                        $query->where('area_id', $areaId)
                                            ->where('es_actual', true);
                                    })
                                    ->with('persona') // Eager loading para evitar consultas lentas
                                    ->get()
                                    ->mapWithKeys(function ($trabajador) {
                                        // Forzamos que el label sea un string y no null
                                        $nombre = $trabajador->persona->full_nombre ?? "Trabajador {$trabajador->id}";
                                        return [$trabajador->id => $nombre];
                                    });
                            })
                            ->searchable()
                            ->required(),

                        TextInput::make('motivo')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }
}
