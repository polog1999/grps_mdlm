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
    // Esto hace que la notificación de progreso sea persistente y visible
public static function getJobProgressNotificationTitle(): ?string
{
    return 'Generando reporte de Visitas...';
}

// Opcional: Si quieres que la barra se vea en grande
public static function getJobStartedNotificationTitle(): ?string
{
    return 'La exportación ha comenzado';
}
// Opcional: Personalizar el título de la notificación final
public static function getCompletedNotificationTitle(Export $export): string
{
    return '¡Reporte Listo!';
}
public function getFileDisk(): string
    {
        return 'public'; // <--- Forzamos el uso del disco público aquí
    }
}
