<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VisitasTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn($query) => $query->leftjoin('visitas.personas', 'visitas.visitas.persona_id','=','visitas.personas.id')->select('visitas.visitas.*'))
        ->columns([
            TextColumn::make('persona.numero_documento')->label('N° documento')->searchable()->sortable(),
            TextColumn::make('persona.full_nombre')->label('Nombres y Apellidos')->searchable(['visitas.personas.nombres', 'visitas.personas.apellido_paterno', 'visitas.personas.apellido_materno']),
            TextColumn::make('area.nombre')->label('Area'),
            TextColumn::make('trabajadorAutoriza.persona.full_nombre')->label('Autorizado por'),
            TextColumn::make('fecha_ingreso')->dateTime('H:i A'),
            TextColumn::make('fecha_salida')->dateTime('H:i A'),
            TextColumn::make('motivo'),
            TextColumn::make('fecha_visita')->badge()
                ->label('Estado')
                ->state(fn ($record) => $record->fecha_salida ? 'Salió': 'En Sede')
                ->color(fn ($record) => $record->fecha_salida ? 'gray' : 'success'),
        ])
        ->actions([
            Action::make('marcar_salida')
                ->label('Registrar Salida')
                ->icon('heroicon-o-arrow-right-on-rectangle')
                ->color('danger')
                ->visible(fn ($record) => $record->fecha_salida === null) // Solo si no ha salido
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
