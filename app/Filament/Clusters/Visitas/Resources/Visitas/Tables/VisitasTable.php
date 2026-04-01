<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Tables;

use App\Filament\Exports\VisitaExporter;
use App\Models\Area;
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
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\BadgeColumn;
// use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
// use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class VisitasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([ // <-- Añadimos esto
                ExportAction::make()->exporter(VisitaExporter::class)
                    ->label('Exportar Visitas')
                    ->chunkSize(100) // Procesar de 100 en 100 para que la barra suba fluido
                    ->color('info')
            ])
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
                TextColumn::make('nombres_full')->label('Visitante')
                    ->getStateUsing(function ($record) {
                        // Asumiendo que tienes relaciones 'persona' y 'proveedor'
                        $nombreVisitante = $record->nombres_completos;
                        $proveedor = $record->proveedor ? "[{$record->proveedor}]" : '';

                        return "{$nombreVisitante} {$proveedor}";
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        // IMPORTANTE: Cuando usas getStateUsing, debes definir el searchable manualmente
                        return $query->where('nombres_completos', 'ilike', "%{$search}%")
                        ->orWhere('proveedor', 'ilike', "%{$search}%");
                    }),
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
                SelectFilter::make('es_empresa')
                ->label('Tipo')
                ->options([
                        1 => 'Empresa',
                        0 => 'Persona',
                    ]),
                SelectFilter::make('area')
                    ->label('Área')
                    ->searchable()
                    ->options(fn() => Area::pluck('nombre', 'nombre')),
                SelectFilter::make('sistema')
                    ->options([
                        'VISITAS' => 'Visitas',
                        'PCM' => 'PCM',
                    ]),
                // Filter::make('hora_ingreso')
                //     ->schema([
                //         DatePicker::make('fecha'),
                //     ])
                //     ->query(function ($query, array $data) {
                //         return $query
                //             ->when(
                //                 $data['fecha'],
                //                 fn($query, $date) =>
                //                 $query->whereDate('fecha', $date)
                //             );
                //     }),
                Filter::make('fecha_rango')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['desde'],
                                fn($query, $date) => $query->whereDate('fecha', '>=', $date),
                            )
                            ->when(
                                $data['hasta'],
                                fn($query, $date) => $query->whereDate('fecha', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['desde'] ?? null) {
                            $indicators[] = 'Desde: ' . \Carbon\Carbon::parse($data['desde'])->format('d/m/Y');
                        }
                        if ($data['hasta'] ?? null) {
                            $indicators[] = 'Hasta: ' . \Carbon\Carbon::parse($data['hasta'])->format('d/m/Y');
                        }
                        return $indicators;
                    })
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
                    ->visible(fn($record) => $record->hora_salida === null && $record->origen !== 'MIGRACION' && Carbon::parse($record->fecha)->isToday()) // Solo si no ha salido
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
                                            ->date('d/m/Y')
                                            ->columnSpanFull(),
                                        TextEntry::make('tipo_documento')
                                            ->label('Tipo de Documento'),
                                        TextEntry::make('numero_documento')
                                            ->label('N° de Documento'),
                                        // Usamos nombres de columnas reales de tu BD
                                        TextEntry::make('nombres_completos')
                                            ->label('Visitante'),
                                        TextEntry::make('ruc_empresa')
                                            ->label('Empresa')
                                            ->getStateUsing(function ($record) {
                                                // Asumiendo que tienes relaciones 'persona' y 'proveedor'
                                                $ruc = $record->ruc;
                                                $proveedor = $record->proveedor ? "{$record->proveedor}" : '';

                                                return "RUC {$ruc} - {$proveedor}";
                                            })->visible(fn($record) => $record->ruc != null)
                                        // TextEntry::make('trabajadores') // Apuntamos a la relación, no a la columna id
                                        //     ->label('Trabajadores')
                                        //     ->columnSpanFull()
                                        //     ->html() // Necesario para el <br>
                                        //     ->getStateUsing(function ($record) {
                                        //         // Obtenemos los registros relacionados (Eager Loaded)
                                        //         $relaciones = $record->trabajadores;

                                        //         if ($relaciones->isEmpty()) {
                                        //             return 'Sin asignar';
                                        //         }

                                        //         return $relaciones->map(function ($item) {
                                        //             // Accedemos a la relación 'persona' que debe estar en el modelo VisitaTrabajadorRuc
                                        //             $persona = $item->persona;

                                        //             if ($persona) {
                                        //                 return "<b>{$persona->tipoDocumento?->abreviatura}</b>&nbsp;<b>{$persona->numero_documento}</b>&nbsp;{$persona->nombres}&nbsp;{$persona->apellido_paterno}&nbsp;{$persona->apellido_materno} - <b>{$item->cargo}</b>";
                                        //             }

                                        //             return "Cargo: {$item->cargo} (Sin datos de persona)";
                                        //         })->implode('<br>'); // Aquí generamos el salto de línea
                                        //     })->visible(fn($record) => $record && $record->trabajadores()->exists()),
                                    ]),
                            ])->collapsible(),

                        Section::make('Detalles de la Visita')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('sede.nombre')
                                            ->label('Sede'),
                                        TextEntry::make('area')
                                            ->label('Área Destino'),
                                        TextEntry::make('oficina')
                                            ->label('Oficina Destino')
                                            ->visible(fn($state) => $state != null),
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
                                            ->label('Motivo de la visita')
                                            ->getStateUsing(function ($record) {
                                                $motivo = $record->motivo;
                                                $detalle = $record->detalle_motivo;

                                                return filled($detalle)
                                                    ? "{$motivo} - {$detalle}"
                                                    : $motivo;
                                            }),
                                        TextEntry::make('sistema')
                                            ->label('Sistema'),
                                    ]),
                            ])->collapsible(),
                    ]),
            ], position: RecordActionsPosition::BeforeColumns);
    }
}
