<?php

namespace App\Filament\Exports;

use App\Models\VisitaDashboard;
use App\Models\VisitaHistorico;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class VisitaDashboardExporter extends Exporter
{
    protected static ?string $model = VisitaHistorico::class;

   public static function getColumns(): array
{
    return [
        ExportColumn::make('id')
            ->label('ID Registro'),

        ExportColumn::make('fecha')
            ->label('Fecha de Visita'),

        ExportColumn::make('hora_ingreso')
            ->label('Hora Ingreso'),

        ExportColumn::make('hora_salida')
            ->label('Hora Salida'),

        // Datos del Visitante
        ExportColumn::make('numero_documento')
            ->label('DNI/Documento'),

        ExportColumn::make('nombres_completos') // O el campo que uses para el nombre
            ->label('Nombre del Visitante'),

        // Ubicación y Destino
        ExportColumn::make('sede.nombre')
            ->label('Sede'),

        ExportColumn::make('area.nombre')
            ->label('Área Destino'),
            
        ExportColumn::make('area.abreviatura')
            ->label('Siglas Área'),

        ExportColumn::make('oficina')
            ->label('Oficina específica'),

        // Detalles de la gestión
        ExportColumn::make('motivo_visita') // Asumiendo que guardas el texto o relación
            ->label('Motivo'),

        ExportColumn::make('autorizado_por')
            ->label('Personal que Autoriza'),
            
        ExportColumn::make('trabajador_cita')
            ->label('Persona a quien visita'),

        // Metadatos para Auditoría
        ExportColumn::make('user.name')
            ->label('Registrado por (Usuario)'),

        ExportColumn::make('fecha')
            ->label('Fecha Sistema'),
            // Esta columna ayuda a recrear el gráfico de "Frecuencia de Visitantes" en Excel
        ExportColumn::make('tipo_visitante')
            ->label('Frecuencia')
            ->state(fn ($record) => $record->numero_documento > 1 ? 'Recurrente' : 'Primera vez'),

        // Esta columna ayuda a recrear el "Flujo por Hora"
        ExportColumn::make('rango_horario')
            ->label('Bloque Horario')
            ->state(fn ($record) => Carbon::parse($record->hora_ingreso)->format('H:00')),
            
        // Esta columna ayuda al "Ranking de Áreas"
        ExportColumn::make('area_nombre')
            ->label('Área Destino')
            ->state(fn ($record) => $record->area),
    ];
}

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your visita dashboard export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
