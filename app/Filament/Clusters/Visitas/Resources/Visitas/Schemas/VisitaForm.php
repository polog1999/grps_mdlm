<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Schemas;

use App\Models\Area;
use App\Models\PersonaUno;
use App\Models\Trabajador;
use App\Services\PideService;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
                                    ->action(function ($state, Set $set, Get $get) {
                                        if (!$state) return;

                                        // 1. Validar si ya existe como TRABAJADOR
                                        // $existeTrabajador = PersonaUno::where('numero_documento', $state)
                                        //     ->whereHas('trabajador')
                                        //     ->exists();

                                        // if ($existeTrabajador) {
                                        //     Notification::make()
                                        //         ->title('El trabajador ya se encuentra registrado.')
                                        //         ->danger()
                                        //         ->send();
                                        //     return;
                                        // }

                                        // 2. Buscar en tabla PERSONAS (Si ya fue visitante antes)
                                        $persona = PersonaUno::where('numero_documento', $state)->first();

                                        if ($persona) {
                                            $set('persona_id', $persona->id);
                                            $set('nombres', $persona->nombres);
                                            $set('apellido_paterno', $persona->apellido_paterno);
                                            $set('apellido_materno', $persona->apellido_materno);
                                            $set('foto_url', $persona->foto_url); // Traer foto de la BD
                                            return;
                                        }

                                        // 3. Si no existe en BD, Consultar al PIDE
                                        // Supongamos que tienes un Service: PideService::consultar($dni)
                                        $datosPide = PideService::ws_reniec($state);

                                        if ($datosPide['codResu'] === '0000') {
                                            $set('pide_fallo', false); // Activamos edición manual
                                            $set('nombres', $datosPide['nombre']);
                                            $set('apellido_paterno', $datosPide['paterno']);
                                            $set('apellido_materno', $datosPide['materno']);
                                            $set('foto_url', '/uploads/foto_dni/' . $state . '.png');
                                        } else {
                                            // FALLÓ EL PIDE
                                            $set('pide_fallo', true); // Activamos edición manual
                                            $set('nombres', null);
                                            $set('apellido_paterno', null);
                                            $set('apellido_materno', null);
                                            Notification::make()
                                                ->title('PIDE no disponible')
                                                ->body('Complete los datos manualmente.')
                                                ->warning()
                                                ->send();
                                        }
    })
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
