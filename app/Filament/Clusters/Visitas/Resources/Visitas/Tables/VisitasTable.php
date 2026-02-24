<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                TextColumn::make('persona.numero_documento')->label('N° documento')->searchable(),
                TextColumn::make('persona.full_nombre')->label('Nombres y Apellidos')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('persona', function ($q) use ($search) {
                            $q->where('nombres', 'ilike', "%{$search}%")
                                ->orWhere('apellido_paterno', 'ilike', "%{$search}%")
                                ->orWhere('apellido_materno', 'ilike', "%{$search}%");
                        });
                    }),

                TextColumn::make('area.nombre')->label('Area'),
                TextColumn::make('trabajadorAutoriza.persona.full_nombre')->label('Autorizado por'),
                TextColumn::make('fecha_ingreso')->dateTime('H:i A'),
                TextColumn::make('fecha_salida')->dateTime('H:i A'),
                TextColumn::make('motivo'),
                TextColumn::make('fecha_visita')->badge()
                    ->label('Estado')
                    ->state(fn($record) => $record->fecha_salida ? 'Salió' : 'En Sede')
                    ->color(fn($record) => $record->fecha_salida ? 'gray' : 'success'),
            ])
            ->filters([
                Filter::make('fecha_ingreso')
                    ->schema([
                        DatePicker::make('fecha'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['fecha'],
                                fn($query, $date) =>
                                $query->whereDate('fecha_ingreso', $date)
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
                    ->visible(fn($record) => $record->fecha_salida === null) // Solo si no ha salido
                    ->action(function ($record) {
                        $record->update([
                            'fecha_salida' => now(),
                            'user_id_salida' => auth()->id(),
                        ]);
                        Notification::make()->title('Salida registrada')->success()->send();
                    })
            ]);
    }
}
