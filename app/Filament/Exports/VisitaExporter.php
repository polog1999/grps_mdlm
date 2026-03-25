<?php

namespace App\Filament\Exports;

use App\Models\Visita;
use App\Models\VisitaHistorico;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class VisitaExporter extends Exporter
{
    protected static ?string $model = VisitaHistorico::class;

    public static function getColumns(): array
    {
        return [
            // ExportColumn::make('id')
            //     ->label('ID'),
            ExportColumn::make('fecha')
                ->label('Fecha'),
                
            ExportColumn::make('numero_documento')
                ->label('Documento'),
                
            // Como en tu tabla usas 'Apellidos y nombres', aquí lo mapeamos:
            ExportColumn::make('Apellidos y nombres')
                ->label('Visitante'),
                
            ExportColumn::make('area')
                ->label('Área'),
                
            ExportColumn::make('motivo')
                ->label('Motivo'),
                
            ExportColumn::make('hora_ingreso')
                ->label('Ingreso'),
                
            ExportColumn::make('hora_salida')
                ->label('Salida'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your visita export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
    public static function getJobProgressNotificationTitle(): ?string
{
    return 'Procesando exportación de Visitas...';
}

// ESTA ES LA CLAVE:
// Forzamos a que la notificación de progreso se envíe como un mensaje "broadcast"
public function getJobProgressNotification(): ?\Filament\Notifications\Notification
{
    return \Filament\Notifications\Notification::make()
        ->title(static::getJobProgressNotificationTitle())
        ->info() // Color azul de información
        ->persistent() // Para que no se cierre sola
        ->body('Por favor, no cierre la ventana hasta completar los 30,000 registros.');
}
}
