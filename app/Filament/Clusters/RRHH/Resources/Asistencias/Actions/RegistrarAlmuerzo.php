<?php

namespace App\Filament\Clusters\RRHH\Resources\Asistencias\Actions;

use App\Models\Asistencia;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class RegistrarAlmuerzo
{
    public static function make(): Action
    {
        return Action::make('registrar_almuerzo')
            ->label('Almuerzo')
            ->icon('heroicon-o-clock')
            ->color('info')
            ->modalSubmitActionLabel('Confirmar')
            ->form(function (): array {
                $ahora = Carbon::now('America/Lima');

                $asistencia = Asistencia::where('usuario_id', Auth::id())
                    ->whereDate('hora_entrada', $ahora->toDateString())
                    ->first();

                // Si no hay entrada registrada hoy
                if (!$asistencia) {
                    return [
                        Placeholder::make('sin_entrada')
                            ->label('')
                            ->content('⚠️ No tienes una entrada registrada para hoy. Primero registra tu entrada.'),
                    ];
                }

                // Si no ha registrado inicio de almuerzo
                if ($asistencia->hora_almuerzo_salida === null) {
                    return [
                        Placeholder::make('info_usuario')
                            ->label('Usuario')
                            ->content(Auth::user()->name),
                        Placeholder::make('info_hora')
                            ->label('Hora de inicio de almuerzo')
                            ->content($ahora->format('h:i:s A')),
                        Placeholder::make('info_aviso')
                            ->label('')
                            ->content('⚠️ Se registrará esta hora como inicio de tu almuerzo. Esta acción no se puede cambiar.'),
                    ];
                }

                // Si ya registró inicio pero no fin de almuerzo
                if ($asistencia->hora_almuerzo_entrada === null) {
                    return [
                        Placeholder::make('info_usuario')
                            ->label('Usuario')
                            ->content(Auth::user()->name),
                        Placeholder::make('info_inicio')
                            ->label('Hora de inicio de almuerzo')
                            ->content($asistencia->hora_almuerzo_salida->format('h:i:s A')),
                        Placeholder::make('info_hora_fin')
                            ->label('Hora de fin de almuerzo')
                            ->content($ahora->format('h:i:s A')),
                        Placeholder::make('info_aviso')
                            ->label('')
                            ->content('⚠️ Se registrará esta hora como fin de tu almuerzo. Esta acción no se puede cambiar.'),
                    ];
                }

                // Ya completó ambos registros
                return [
                    Placeholder::make('ya_registrado')
                        ->label('')
                        ->content('✅ Ya registraste tu horario de almuerzo completo para hoy: '
                            . $asistencia->hora_almuerzo_salida->format('h:i A')
                            . ' - '
                            . $asistencia->hora_almuerzo_entrada->format('h:i A')),
                ];
            })
            ->modalHeading(function (): string {
                $ahora = Carbon::now('America/Lima');

                $asistencia = Asistencia::where('usuario_id', Auth::id())
                    ->whereDate('hora_entrada', $ahora->toDateString())
                    ->first();

                if (!$asistencia || $asistencia->hora_almuerzo_salida === null) {
                    return 'Registrar Inicio de Almuerzo';
                }

                if ($asistencia->hora_almuerzo_entrada === null) {
                    return 'Registrar Fin de Almuerzo';
                }

                return 'Almuerzo Registrado';
            })
            ->before(function (Action $action) {
                $ahora = Carbon::now('America/Lima');

                $asistencia = Asistencia::where('usuario_id', Auth::id())
                    ->whereDate('hora_entrada', $ahora->toDateString())
                    ->first();

                if (!$asistencia) {
                    Notification::make()
                        ->title('Sin entrada registrada')
                        ->body('Primero debes registrar tu entrada del día.')
                        ->warning()
                        ->send();

                    $action->halt();
                }

                if ($asistencia->hora_almuerzo_salida !== null && $asistencia->hora_almuerzo_entrada !== null) {
                    Notification::make()
                        ->title('Almuerzo ya registrado')
                        ->body('Ya completaste tu registro de almuerzo para hoy.')
                        ->warning()
                        ->send();

                    $action->halt();
                }
            })
            ->action(function () {
                $ahora = Carbon::now('America/Lima');

                $asistencia = Asistencia::where('usuario_id', Auth::id())
                    ->whereDate('hora_entrada', $ahora->toDateString())
                    ->first();

                // Registrar inicio de almuerzo
                if ($asistencia->hora_almuerzo_salida === null) {
                    $asistencia->update([
                        'hora_almuerzo_salida' => $ahora,
                    ]);

                    Notification::make()
                        ->title('Inicio de almuerzo registrado')
                        ->body('Salida a almuerzo registrada a las ' . $ahora->format('h:i:s A'))
                        ->success()
                        ->send();

                    return;
                }

                // Registrar fin de almuerzo
                $asistencia->update([
                    'hora_almuerzo_entrada' => $ahora,
                ]);

                Notification::make()
                    ->title('Fin de almuerzo registrado')
                    ->body('Regreso de almuerzo registrado a las ' . $ahora->format('h:i:s A'))
                    ->success()
                    ->send();
            });
    }
}
