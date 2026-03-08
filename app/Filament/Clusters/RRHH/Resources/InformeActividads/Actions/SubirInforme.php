<?php

namespace App\Filament\Clusters\RRHH\Resources\InformeActividads\Actions;

use App\Models\InformeActividad;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubirInforme
{
    public static function make(): Action
    {
        return Action::make('subir_informe')
            ->label('Subir Informe')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('primary')
            ->modalHeading('Subir Informe de Actividades')
            ->modalDescription('Sube tu informe de actividades en formato PDF.')
            ->modalSubmitActionLabel('Subir Informe')
            ->form([
                Placeholder::make('info_usuario')
                    ->label('Usuario')
                    ->content(fn() => Auth::user()->name),

                Placeholder::make('info_fecha')
                    ->label('Fecha de subida')
                    ->content(fn() => Carbon::now('America/Lima')->format('d/m/Y')),

                FileUpload::make('archivo_pdf')
                    ->label('Archivo PDF')
                    ->required()
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240) // 10 MB
                    ->disk('informes_tecnicos_teletrabajo')
                    ->visibility('public')
                    ->helperText('Solo se permiten archivos PDF. Tamaño máximo: 10 MB.'),
            ])
            ->action(function (array $data) {
                $hoyLima = Carbon::now('America/Lima');
                $fechaStr = $hoyLima->format('Ymd'); // 20260306
    
                // Contar cuántos informes se han subido hoy para generar el correlativo
                $cantidadHoy = InformeActividad::where('numero_informe', 'like', "INF-{$fechaStr}-%")
                    ->where('usuario_id', Auth::id())
                    ->count();

                $correlativo = str_pad($cantidadHoy + 1, 3, '0', STR_PAD_LEFT);
                $numeroInforme = "INF-{$fechaStr}-{$correlativo}";

                InformeActividad::create([
                    'numero_informe' => $numeroInforme,
                    'usuario_id' => Auth::id(),
                    'url_archivo' => Storage::disk('informes_tecnicos_teletrabajo')->path($data['archivo_pdf']),
                    'fecha_subida' => $hoyLima->toDateString(),
                ]);

                Notification::make()
                    ->title('Informe subido')
                    ->body('Tu informe "' . $numeroInforme . '" ha sido registrado correctamente.')
                    ->success()
                    ->send();
            });
    }
}
