<?php

namespace App\Filament\Clusters\RRHH\Resources\Asistencias\Actions;

use App\Models\Asistencia;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class RegistrarEntrada
{
    public static function make(): Action
    {
        return Action::make('registrar_entrada')
            ->label('Registrar Entrada')
            ->icon('heroicon-o-arrow-right-start-on-rectangle')
            ->color('success')
            ->modalHeading('Registrar Hora de Entrada')
            ->modalDescription('Se registrará tu hora de entrada para el día de hoy.')
            ->modalSubmitActionLabel('Confirmar Entrada')
            ->form([
                Placeholder::make('info_usuario')
                    ->label('Usuario')
                    ->content(fn() => Auth::user()->name),

                Placeholder::make('info_hora')
                    ->label('Hora actual')
                    ->content(fn() => Carbon::now('America/Lima')->format('d/m/Y h:i:s A')),

                Placeholder::make('info_regla')
                    ->label('')
                    ->content('⚠️ No se puede registrar entrada antes de las 06:00 AM.'),
            ])
            ->before(function (Action $action) {
                $ahora = Carbon::now('America/Lima');

                // Validar que no sea antes de las 6:00 AM
                if ($ahora->hour < 6) {
                    Notification::make()
                        ->title('Fuera de horario')
                        ->body('No puedes registrar tu entrada antes de las 06:00 AM.')
                        ->danger()
                        ->send();

                    $action->halt();
                }

                // Validar que no tenga una entrada ya registrada hoy
                $yaRegistro = Asistencia::where('usuario_id', Auth::id())
                    ->whereDate('hora_entrada', $ahora->toDateString())
                    ->exists();

                if ($yaRegistro) {
                    Notification::make()
                        ->title('Entrada ya registrada')
                        ->body('Ya tienes una entrada registrada para el día de hoy.')
                        ->warning()
                        ->send();

                    $action->halt();
                }
            })
            ->action(function () {
                Asistencia::create([
                    'usuario_id' => Auth::id(),
                    'hora_entrada' => Carbon::now('America/Lima'),
                ]);

                Notification::make()
                    ->title('Entrada registrada')
                    ->body('Tu hora de entrada ha sido registrada correctamente a las ' . Carbon::now('America/Lima')->format('h:i:s A'))
                    ->success()
                    ->send();
            });
    }
}
