<?php

namespace App\Filament\Exports;

use App\Models\Sede;
use App\Models\Visita;
use App\Models\VisitaHistorico;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;
use Symfony\Component\HttpFoundation\Response;

class VisitaExporter extends Exporter
{
    protected static ?string $model = VisitaHistorico::class;

    public static function getColumns(): array
    {
        return [
            // ExportColumn::make('id')
            //     ->label('ID'),
            ExportColumn::make('fecha')
                ->label('Fecha')
                ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('d-m-Y') : ''),
            // ->date('d/m/Y'),
            ExportColumn::make('hora_ingreso')
                ->label('Ingreso'),

            ExportColumn::make('hora_salida')
                ->label('Salida'),
            ExportColumn::make('tipo_documento'),
            ExportColumn::make('numero_documento')
                ->label('Documento'),

            // Como en tu tabla usas 'Apellidos y nombres', aquí lo mapeamos:
            ExportColumn::make('nombres_completos')
                ->label('Visitante')
                 ->formatStateUsing(function ($record) {
                    
                        $nombreVisitante = $record->nombres_completos;
                        $proveedor = $record->proveedor ? "[{$record->proveedor}]" : '';

                        return "{$nombreVisitante} {$proveedor}";
                    }),
            ExportColumn::make('ruc')
                ->label('Ruc Empresa')
                ->default('-'),
            ExportColumn::make('sede_id')
                ->label('Sede')
                ->formatStateUsing(fn($record) => $record->sede?->nombre ?? 'N/A'),
            ExportColumn::make('area')
                ->label('Área'),

            ExportColumn::make('oficina')
                ->label('Oficina')
                ->enabledByDefault(false),

            ExportColumn::make('trabajador_cita')
                ->label('Cita con'),

            ExportColumn::make('Autorizado por')
                ->label('Autorizado por')
                ->enabledByDefault(false),

            ExportColumn::make('motivo')
                ->label('Motivo'),
            ExportColumn::make('detalle_motivo')
                ->label('Detalle Motivo'),
            ExportColumn::make('sistema')
                ->label('Sistema')
                ->enabledByDefault(false),
        ];
    }
public function getFileName(Export $export): string
    {
        // Esto generará algo como: reporte-visitas-2026-04-06.csv (o .xlsx)
        $fecha = now()->format('Y-m-d');
        
        return "reporte-visitas-{$fecha}";
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

    public function getFormats(): array
    {
        return [
            ExportFormat::Xlsx,
            ExportFormat::Csv,
        ];
    }

    // Esto optimiza el uso de memoria para archivos grandes
    public function getXlsxWriter(): string
    {
        return \OpenSpout\Writer\XLSX\Writer::class;
    }
    public function download(Export $export, string $format): Response
    {
        $storage = storage_path("app/{$export->file_disk}/{$export->file_name}");

        // Limpiamos cualquier error de PHP que se quiera colar en el Excel
        if (ob_get_length()) ob_end_clean();

        // Forzamos a Laravel a leer el archivo y enviarlo directamente
        return response()->download($storage, "reporte_visitas.{$format}", [
            'Content-Type' => ($format === 'xlsx')
                ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                : 'text/csv',
        ]);
    }
    public static function getUrl(Export $export, string $format): string
    {
        // Esto genera la URL pero sin forzar un guardián específico que pueda dar 401
        return route('filament.exports.download', [
            'export' => $export,
            'format' => $format,
        ]);
    }
}
