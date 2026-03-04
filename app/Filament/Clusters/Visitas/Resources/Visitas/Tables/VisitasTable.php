<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Tables;

use App\Models\Visita;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VisitasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha')->label('Fecha')->date('d/m/Y')->searchable(),
                TextColumn::make('numero_documento')->label('N° documento')->searchable(),
                TextColumn::make('Apellidos y nombres')->label('Visitante')
                    ->searchable(
                        //     query: function (Builder $query, string $search): Builder {
                        //     return $query->whereHas('persona', function ($q) use ($search) {
                        //         $q->where('nombres', 'ilike', "%{$search}%")
                        //             ->orWhere('apellido_paterno', 'ilike', "%{$search}%")
                        //             ->orWhere('apellido_materno', 'ilike', "%{$search}%");
                        //     });
                        // }
                    ),

                TextColumn::make('area')->label('Area')
                ->searchable(),
                TextColumn::make('Autorizado por')->label('Autorizado por')
                ->searchable(),
                TextColumn::make('hora_ingreso')->dateTime('H:i A')
                ->searchable(),
                TextColumn::make('hora_salida')->dateTime('H:i A')
                ->searchable(),
                TextColumn::make('motivo'),
                TextColumn::make('fecha_visita')->badge()
                    ->label('Estado')
                    ->state(fn($record) => $record->fecha_salida ? 'Salió' : 'En Sede')
                    ->color(fn($record) => $record->fecha_salida ? 'gray' : 'success'),
            ])->recordUrl(null)
            ->defaultSort('fecha', 'desc') // <-- CAMBIA 'id' POR UNA COLUMNA QUE SÍ EXISTA

            ->filters([
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

            ->actions([
                
                Action::make('marcar_salida')
                    ->label('Registrar Salida')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('¿Marcar salida?')
                    ->modalDescription('¿Estás seguro de que desea marcar la salida para este visitante? Esta acción registrará la hora actual.')
                    ->modalSubmitActionLabel('Sí, marcar salida')
                    ->visible(fn($record) => $record->hora_salida === null) // Solo si no ha salido
                    ->action(function ($record) {
                        $idOriginal = $record->id_original;
                        $visita = Visita::find($idOriginal);

                        if ($visita) {
                            $visita->update([
                                'fecha_salida' => now(),
                                'user_id_salida' => auth()->id(),
                            ]);
                            Notification::make()->title('Salida registrada')->success()->send();
                        }
                    }),
                    // ViewAction::make()
                    // , // Abre un modal de solo lectura
            ]);
    }
}
