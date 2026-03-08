<?php

namespace App\Filament\Clusters\RRHH\Resources\Asistencias\Actions;

use App\Models\Asistencia;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class RegistrarSalida
{
    public static function make(): Action
    {
        return Action::make('registrar_salida')
            ->label('Registrar Salida')
            ->icon('heroicon-o-arrow-right-end-on-rectangle')
            ->color('danger')
            ->modalHeading('Registrar Hora de Salida')
            ->modalDescription('Se registrará tu hora de salida para el día de hoy.')
            ->modalSubmitActionLabel('Confirmar Salida')
            ->form([
                Placeholder::make('info_usuario')
                    ->label('Usuario')
                    ->content(fn() => Auth::user()->name),

                Placeholder::make('info_hora')
                    ->label('Hora actual')
                    ->content(fn() => Carbon::now('America/Lima')->format('d/m/Y h:i:s A')),

                Placeholder::make('info_regla')
                    ->label('')
                    ->content('⚠️ No se puede registrar salida antes de las 05:00 PM.'),
            ])
            ->before(function (Action $action) {
                $ahora = Carbon::now('America/Lima');

                if ($ahora->hour < 17 || ($ahora->hour === 17 && $ahora->minute < 00)) {
                    Notification::make()
                        ->title('Fuera de horario')
                        ->body('No puedes registrar tu salida antes de las 05:00 PM.')
                        ->danger()
                        ->send();

                    $action->halt();
                }

                // Buscar la asistencia de hoy del usuario
                $asistencia = Asistencia::where('usuario_id', Auth::id())
                    ->whereDate('hora_entrada', $ahora->toDateString())
                    ->first();

                // Validar que tenga una entrada registrada hoy
                if (!$asistencia) {
                    Notification::make()
                        ->title('Sin entrada registrada')
                        ->body('No tienes una entrada registrada para el día de hoy. Primero debes registrar tu entrada.')
                        ->warning()
                        ->send();

                    $action->halt();
                }

                // Validar que no haya registrado salida ya
                if ($asistencia->hora_salida !== null) {
                    Notification::make()
                        ->title('Salida ya registrada')
                        ->body('Ya tienes una salida registrada para el día de hoy.')
                        ->warning()
                        ->send();

                    $action->halt();
                }
            })
            ->action(function () {
                try {
                    $ahora = Carbon::now('America/Lima');

                    $asistencia = Asistencia::where('usuario_id', Auth::id())
                        ->whereDate('hora_entrada', $ahora->toDateString())
                        ->whereNull('hora_salida')
                        ->first();

                    $asistencia->update([
                        'hora_salida' => $ahora,
                    ]);

                    Notification::make()
                        ->title('Salida registrada')
                        ->body('Tu hora de salida ha sido registrada correctamente a las ' . $ahora->format('h:i:s A'))
                        ->success()
                        ->send();
                } catch (\Illuminate\Database\QueryException $e) {
                    if (str_contains($e->getMessage(), 'chk_hora_salida_minima')) {
                        Notification::make()
                            ->title('Restricción de horario')
                            ->body('No se puede registrar la salida antes de las 05:00 PM (horario Perú). Por favor, intenta nuevamente después de las 5:00 PM.')
                            ->danger()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Error al registrar salida')
                            ->body('Ocurrió un error inesperado: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }
            });
    }
}
