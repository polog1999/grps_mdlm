<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Tables;

use App\Models\ExcelControl1;
use App\Models\Persona;
use App\Models\PersonaUno;
use App\Models\Visita;
use App\Models\VisitaTrabajadorRuc;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;

class VisitasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex() // <--- Esta es la clave en Filament v3
                    ->alignCenter(),
                TextColumn::make('fecha_visita')->badge()
                    ->sortable()
                    ->label('Estado')
                    ->state(fn($record) => $record->hora_salida ? 'Salió' : 'En Sede')
                    ->color(fn($record) => $record->hora_salida ? 'gray' : 'success'),
                TextColumn::make('fecha')->label('Fecha')->date('d/m/Y')->searchable(),
                TextColumn::make('tipo_documento')->label('Tipo Documento')->searchable(),
                TextColumn::make('numero_documento')->label('N° documento')->searchable(),
                TextColumn::make('Apellidos y nombres')->label('Visitante')
                    ->searchable(
                        //     query: function (Builder $query, string $search): Builder {
                        //     return $query-> whereHas('persona', function ($q) use ($search) {
                        //         $q->where('nombres', 'ilike', "%{$search}%")
                        //             ->orWhere('apellido_paterno', 'ilike', "%{$search}%")
                        //             ->orWhere('apellido_materno', 'ilike', "%{$search}%");
                        //     });
                        // }
                    ),
                // TextColumn::make('sede.nombre')->label('Sede')
                //     ->sortable()
                //     ->searchable(),
                TextColumn::make('area')->label('Area')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('Autorizado por')->label('Autorizado por')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hora_ingreso')->dateTime('H:i A')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hora_salida')->dateTime('H:i A')
                    ->sortable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('motivo')
                    ->sortable()
                    ->searchable(),

            ])->recordUrl(null)
            ->defaultSort('fecha', 'desc') // <-- CAMBIA 'id' POR UNA COLUMNA QUE SÍ EXISTA

            ->filters([
                SelectFilter::make('sistema')
                    ->options([
                        'VISITAS' => 'Visitas',
                        'PCM' => 'PCM',
                    ]),
                Filter::make('hora_ingreso')
                    ->schema([
                        DatePicker::make('fecha'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['fecha'],
                                fn($query, $date) =>
                                $query->whereDate('fecha', $date)
                            );
                    }),
            ])
            ->recordActions([
                Action::make('marcar_salida')
                    ->label('Registrar Salida')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('¿Marcar salida?')
                    ->modalDescription('¿Estás seguro de que desea marcar la salida para este visitante? Esta acción registrará la hora actual.')
                    ->modalSubmitActionLabel('Sí, marcar salida')
                    ->visible(fn($record) => $record->hora_salida === null) // Solo si no ha salido
                    // AQUÍ ES DONDE VA EL MÉTODO, aplicado al $table

                    ->action(function ($record) {
                        $idOriginal = $record->id_original;

                        if ($record->origen == "SISTEMA") {
                            $visita = Visita::find($idOriginal);
                            if ($visita) {
                                $visita->update([
                                    'fecha_salida' => now(),
                                    'user_id_salida' => auth()->id(),
                                ]);
                                Notification::make()->title('Salida registrada')->success()->send();
                            }
                        }
                        // else if ($record->origen == "EXCEL") {
                        //     $visita = ExcelControl1::find($idOriginal);
                        //     if ($visita) {
                        //         $visita->update([
                        //             'hora_salida' => now(),
                        //             'usuario' => auth()->id(),
                        //         ]);
                        //         Notification::make()->title('Salida registrada')->success()->send();
                        //     }
                        // }
                    }),
                // ViewAction::make()
                // , // Abre un modal de solo lectura
                ViewAction::make()
                    ->modalHeading('Detalle Completo de la Visita')
                    ->modalWidth('4xl') // Un ancho mayor para que las 2 columnas respiren
                    ->form([
                        Section::make('Información del Visitante')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('fecha')
                                            ->label('Fecha de Visita')
                                            ->date('d/m/Y'),
                                        TextEntry::make('tipo_documento')
                                            ->label('Tipo de Documento'),
                                        TextEntry::make('numero_documento')
                                            ->label('N° de Documento'),
                                        // Usamos nombres de columnas reales de tu BD
                                        TextEntry::make('Apellidos y nombres')
                                            ->label('Visitante / Razón Social')
                                            ->columnSpanFull(),
                                       TextEntry::make('trabajadores') // Apuntamos a la relación, no a la columna id
    ->label('Visitante / Razón Social')
    ->columnSpanFull()
    ->html() // Necesario para el <br>
    ->getStateUsing(function ($record) {
        // Obtenemos los registros relacionados (Eager Loaded)
        $relaciones = $record->trabajadores; 

        if ($relaciones->isEmpty()) {
            return 'Sin asignar';
        }

        return $relaciones->map(function ($item) {
            // Accedemos a la relación 'persona' que debe estar en el modelo VisitaTrabajadorRuc
            $persona = $item->persona;
            
            if ($persona) {
                return "<b>{$persona->tipoDocumento->nombre_corto}</b>&nbsp;<b>{$persona->numero_documento}</b>&nbsp;{$persona->nombres}&nbsp;{$persona->apellido_paterno}&nbsp;{$persona->apellido_materno} - <b>{$item->cargo}</b>";
            }

            return "Cargo: {$item->cargo} (Sin datos de persona)";
        })->implode('<br>'); // Aquí generamos el salto de línea
    }),
                                    ]),
                            ])->collapsible(),

                        Section::make('Detalles de la Visita')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('sede.nombre')
                                            ->label('Sede Destino'),
                                        TextEntry::make('area')
                                            ->label('Área Destino'),
                                        TextEntry::make('hora_ingreso')
                                            ->label('Hora de Ingreso'),
                                        TextEntry::make('hora_salida')
                                            ->label('Hora de Salida')
                                            ->placeholder('Aún en sede'),
                                        TextEntry::make('Autorizado por')
                                            ->label('Autorizado por:'),
                                        TextEntry::make('trabajador_cita')
                                            ->label('Cita con:'),
                                        TextEntry::make('motivo')
                                            ->label('Motivo de la visita'),
                                        TextEntry::make('sistema')
                                            ->label('Sistema'),
                                    ]),
                            ]),
                    ]),
            ], position: RecordActionsPosition::BeforeColumns);
    }
}
