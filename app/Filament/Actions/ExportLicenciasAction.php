<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\CertificadoLicenciaFuncionamiento;

class ExportLicenciasAction
{
    /**
     * Crea la acción de exportar licencias
     */
    public static function make(): Action
    {
        return Action::make('exportar_licencias')
            ->label('Exportar Licencias')
            ->icon('heroicon-o-document-arrow-down')
            ->color('warning')
            ->action(function ($livewire) {
                return static::export($livewire);
            });
    }

    /**
     * Ejecuta la exportación
     */
    protected static function export($livewire)
    {
        // Obtener registros filtrados
        $records = static::getFilteredRecords($livewire);

        // Cargar plantilla
        $spreadsheet = static::loadTemplate();
        if (!$spreadsheet) {
            return null;
        }

        $sheet = $spreadsheet->getActiveSheet();

        // Configuración de columnas a exportar
        $columns = static::getExportColumns($records);

        // Escribir datos en la plantilla
        static::writeDataToSheet($sheet, $columns);

        // Generar y descargar archivo
        return static::generateDownload($spreadsheet);
    }

    /**
     * Obtiene los registros filtrados desde el Livewire
     */
    protected static function getFilteredRecords($livewire)
    {
        $tableFilters = $livewire->tableFilters ?? [];
        $query = CertificadoLicenciaFuncionamiento::query()->where('lic_filaeliminada', false);

        // Filtros simples (comparación exacta)
        $simpleFilters = [
            'tli_id',
            'esl_id',
            'lic_numlic',
            'lic_expnum',
            'lic_codigopredial',
            'lic_razonsocial',
            'nir_id',
        ];

        foreach ($simpleFilters as $filter) {
            if (isset($tableFilters[$filter]['value']) && !empty($tableFilters[$filter]['value'])) {
                $query->where($filter, $tableFilters[$filter]['value']);
            }
        }

        // Filtro de fecha de emisión (rango)
        if (isset($tableFilters['lic_fechaemision']['from']) && !empty($tableFilters['lic_fechaemision']['from'])) {
            $query->whereDate('lic_fechaemision', '>=', $tableFilters['lic_fechaemision']['from']);
        }
        if (isset($tableFilters['lic_fechaemision']['to']) && !empty($tableFilters['lic_fechaemision']['to'])) {
            $query->whereDate('lic_fechaemision', '<=', $tableFilters['lic_fechaemision']['to']);
        }

        // Filtro de fecha de vencimiento (rango)
        if (isset($tableFilters['lic_fechavencimiento']['from']) && !empty($tableFilters['lic_fechavencimiento']['from'])) {
            $query->whereDate('lic_fechavencimiento', '>=', $tableFilters['lic_fechavencimiento']['from']);
        }
        if (isset($tableFilters['lic_fechavencimiento']['to']) && !empty($tableFilters['lic_fechavencimiento']['to'])) {
            $query->whereDate('lic_fechavencimiento', '<=', $tableFilters['lic_fechavencimiento']['to']);
        }

        // Filtro de fecha de fila (lic_filafecha - rango)
        if (isset($tableFilters['lic_filafecha']['desde']) && !empty($tableFilters['lic_filafecha']['desde'])) {
            $query->whereDate('lic_filafecha', '>=', $tableFilters['lic_filafecha']['desde']);
        }
        if (isset($tableFilters['lic_filafecha']['hasta']) && !empty($tableFilters['lic_filafecha']['hasta'])) {
            $query->whereDate('lic_filafecha', '<=', $tableFilters['lic_filafecha']['hasta']);
        }

        // Filtro de código catastral (búsqueda en vista)
        if (isset($tableFilters['codigocatastral']['codigocatastral']) && !empty($tableFilters['codigocatastral']['codigocatastral'])) {
            $query->whereIn('lic_id', function ($subquery) use ($tableFilters) {
                $subquery->select('lic_id')
                    ->from('licencia.vu_licencia')
                    ->where('codigocatastral', 'LIKE', '%' . $tableFilters['codigocatastral']['codigocatastral'] . '%');
            });
        }

        // Filtro de RUC personas (búsqueda en vista)
        if (isset($tableFilters['per_ruc']['per_ruc']) && !empty($tableFilters['per_ruc']['per_ruc'])) {
            $query->whereIn('lic_id', function ($subquery) use ($tableFilters) {
                $subquery->select('lic_id')
                    ->from('licencia.vu_licencia')
                    ->where('per_ruc', 'LIKE', '%' . $tableFilters['per_ruc']['per_ruc'] . '%');
            });
        }

        // Filtro de número dirección (búsqueda en lic_direccion)
        if (isset($tableFilters['numero']['numero']) && !empty($tableFilters['numero']['numero'])) {
            $query->whereRaw("lic_direccion LIKE ?", ['%' . $tableFilters['numero']['numero'] . '%']);
        }

        // Filtro de dirección licencia
        if (isset($tableFilters['lic_direccion']['lic_direccion']) && !empty($tableFilters['lic_direccion']['lic_direccion'])) {
            $query->where('lic_direccion', 'ILIKE', '%' . $tableFilters['lic_direccion']['lic_direccion'] . '%');
        }

        // Filtro de dirección solicitante (búsqueda en vista)
        if (isset($tableFilters['per_direccionsol']['per_direccionsol']) && !empty($tableFilters['per_direccionsol']['per_direccionsol'])) {
            $query->whereIn('lic_id', function ($subquery) use ($tableFilters) {
                $subquery->select('lic_id')
                    ->from('licencia.vu_licencia')
                    ->where('per_direccionsol', 'ILIKE', '%' . $tableFilters['per_direccionsol']['per_direccionsol'] . '%');
            });
        }

        // Filtro de tiene ITSE (TernaryFilter)
        if (isset($tableFilters['tiene_itse']['value'])) {
            if ($tableFilters['tiene_itse']['value'] === true) {
                // Solo con ITSEZ
                $query->whereIn('lic_id', function ($subquery) {
                    $subquery->select('lic_id')
                        ->from('licencia.vu_licencia')
                        ->whereNotNull('cin_numero');
                });
            } elseif ($tableFilters['tiene_itse']['value'] === false) {
                // Sin ITSE
                $query->whereNotIn('lic_id', function ($subquery) {
                    $subquery->select('lic_id')
                        ->from('licencia.vu_licencia')
                        ->whereNotNull('cin_numero');
                });
            }
            // Si es null/blank, no aplicar filtro
        }

        return $query->orderBy('lic_fechaemision', 'desc')
            ->with([
                'tipoLicencia',
                'tipoEstadoLicencia',
                'nivelRiesgo',
                'licenciaCatastro.fichaUbicacionSyscat',
                'licenciaCatastro.fichaUbicacionInfocat',
                'personaRazonSocial'
            ])
            ->get();
    }

    /**
     * Carga la plantilla de Excel
     */
    protected static function loadTemplate()
    {
        $templatePath = app_path('Templates' . DIRECTORY_SEPARATOR . 'template_exportar_datos_licencias.xlsx');

        if (!file_exists($templatePath)) {
            Notification::make()
                ->title('Error')
                ->body('La plantilla de Excel no fue encontrada en: ' . $templatePath)
                ->danger()
                ->send();

            return null;
        }

        return IOFactory::load($templatePath);
    }

    /**
     * Define las columnas a exportar
     */
    protected static function getExportColumns($records)
    {
        return [
            // ITEM: número secuencial autogenerado
            [
                'header' => 'ITEM',
                'header_variations' => ['item', 'Item'],
                'data' => range(1, $records->count()),
                'default_col' => 'A',
            ],
            // LICENCIA: número de licencia
            [
                'header' => 'LICENCIA',
                'header_variations' => ['licencia', 'Licencia', 'NUMERO LICENCIA'],
                'data' => $records->pluck('lic_numlic')->toArray(),
                'default_col' => 'B',
            ],
            // EXPEDIENTE: número de expediente
            [
                'header' => 'EXPEDIENTE',
                'header_variations' => ['expediente', 'Expediente', 'NUMERO EXPEDIENTE'],
                'data' => $records->pluck('lic_expnum')->toArray(),
                'default_col' => 'C',
            ],
            // CODIGO CATASTRAL: código catastral (prioriza syscat sobre infocat)
            [
                'header' => 'CODIGO CATASTRAL',
                'header_variations' => ['codigo catastral', 'Codigo Catastral', 'CODIGO_CATASTRAL', 'codigo_catastral'],
                'data' => $records->map(function ($record) {
                    // Priorizar syscat (más reciente) sobre infocat (antiguo)
                    $codigoCatastral = $record->licenciaCatastro?->fichaUbicacionSyscat?->fiu_coduca
                        ?? $record->licenciaCatastro?->fichaUbicacionInfocat?->fiu_codcat
                        ?? '';
                    return $codigoCatastral;
                })->toArray(),
                'default_col' => 'D',
                'format_as_text' => true, // Evitar notación científica
            ],
            // RUC: número de RUC de la razón social
            [
                'header' => 'RUC',
                'header_variations' => ['ruc', 'Ruc', 'RUC'],
                'data' => $records->map(function ($record) {
                    return $record->personaRazonSocial?->per_ruc ?? '';
                })->toArray(),
                'default_col' => 'E',
            ],
            // DIRECCION: dirección de la licencia
            [
                'header' => 'DIRECCION',
                'header_variations' => ['direccion', 'Direccion', 'DIRECCION'],
                'data' => $records->pluck('lic_direccion')->toArray(),
                'default_col' => 'F',
            ],
            // ZONIFICACION: zonificación (prioriza syscat sobre infocat)
            [
                'header' => 'ZONIFICACION',
                'header_variations' => ['zonificacion', 'Zonificacion', 'ZONIFICACION'],
                'data' => $records->map(function ($record) {
                    // Priorizar syscat (más reciente) sobre infocat (antiguo)
                    $zonificacion = $record->licenciaCatastro?->fichaUbicacionSyscat?->fiu_zonificacion
                        ?? $record->licenciaCatastro?->fichaUbicacionInfocat?->fiu_zonificacion
                        ?? '';
                    return $zonificacion;
                })->toArray(),
                'default_col' => 'G',
            ],
            // RESOLUCION: número de resolución
            [
                'header' => 'RESOLUCION',
                'header_variations' => ['resolucion', 'Resolucion', 'RESOLUCION', 'NUMERO RESOLUCION'],
                'data' => $records->pluck('lic_resnum')->toArray(),
                'default_col' => 'H',
            ],
            // RAZON SOCIAL: razón social
            [
                'header' => 'RAZON SOCIAL',
                'header_variations' => ['razon social', 'Razon Social', 'RAZON_SOCIAL', 'razon_social'],
                'data' => $records->pluck('lic_razonsocial')->toArray(),
                'default_col' => 'I',
            ],
            // RIESGO LICENCIA: nivel de riesgo
            [
                'header' => 'RIESGO LICENCIA',
                'header_variations' => ['riesgo licencia', 'Riesgo Licencia', 'RIESGO_LICENCIA', 'riesgo_licencia', 'RIESGO'],
                'data' => $records->map(function ($record) {
                    return $record->nivelRiesgo?->nir_descripcion ?? '';
                })->toArray(),
                'default_col' => 'J',
            ],
            // TIPO LICENCIA: tipo de licencia
            [
                'header' => 'TIPO LICENCIA',
                'header_variations' => ['tipo licencia', 'Tipo Licencia', 'TIPO_LICENCIA', 'tipo_licencia', 'TIPO'],
                'data' => $records->map(function ($record) {
                    return $record->tipoLicencia?->tli_descripcion ?? '';
                })->toArray(),
                'default_col' => 'K',
            ],
            // ESTADO LICENCIA: estado de la licencia
            [
                'header' => 'ESTADO LICENCIA',
                'header_variations' => ['estado licencia', 'Estado Licencia', 'ESTADO_LICENCIA', 'estado_licencia', 'ESTADO'],
                'data' => $records->map(function ($record) {
                    return $record->tipoEstadoLicencia?->esl_descripcion ?? '';
                })->toArray(),
                'default_col' => 'L',
            ],
        ];
    }

    /**
     * Reemplaza los placeholders en la plantilla
     */
    protected static function replacePlaceholders($sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $cellValue = $sheet->getCell($col . $row)->getValue();

                if (is_string($cellValue)) {
                    // Reemplazar {{fecha_consulta}}
                    if (str_contains($cellValue, '{{fecha_consulta}}')) {
                        $cellValue = str_replace(
                            '{{fecha_consulta}}',
                            now()->format('d/m/Y H:i:s'),
                            $cellValue
                        );
                    }

                    // Reemplazar {{name}}
                    if (str_contains($cellValue, '{{name}}')) {
                        $cellValue = str_replace(
                            '{{name}}',
                            Auth::user()->name ?? 'Usuario',
                            $cellValue
                        );
                    }

                    $sheet->setCellValue($col . $row, $cellValue);
                }
            }
        }
    }

    /**
     * Encuentra la posición del encabezado en la plantilla
     */
    protected static function findHeaderPosition($sheet, $columnConfig)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Buscar el encabezado en la plantilla
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $cellValue = $sheet->getCell($col . $row)->getValue();

                if (is_string($cellValue)) {
                    $cellValue = trim($cellValue);

                    // Verificar si coincide con alguna variación del encabezado
                    foreach ($columnConfig['header_variations'] as $variation) {
                        if (strcasecmp($cellValue, $variation) === 0) {
                            return ['col' => $col, 'row' => $row];
                        }
                    }
                }
            }
        }

        // Si no se encuentra, usar la columna por defecto
        return ['col' => $columnConfig['default_col'], 'row' => 1];
    }

    /**
     * Escribe los datos en la hoja de cálculo
     */
    protected static function writeDataToSheet($sheet, $columns)
    {
        // Reemplazar {{fecha_consulta}} y {{name}} con valores reales
        static::replacePlaceholders($sheet);

        foreach ($columns as $columnConfig) {
            $position = static::findHeaderPosition($sheet, $columnConfig);
            $col = $position['col'];
            $headerRow = $position['row'];
            $startRow = $headerRow + 1;
            $data = $columnConfig['data'];

            // Verificar si existe una fila plantilla con estilos
            $templateRowExists = !empty($sheet->getCell($col . $startRow)->getValue()) ||
                $sheet->getStyle($col . $startRow)->getFont()->getSize() > 0;

            foreach ($data as $i => $value) {
                $currentRow = $startRow + $i;

                // Duplicar estilos de la fila plantilla si existe
                if ($i > 0 && $templateRowExists) {
                    $sheet->duplicateStyle(
                        $sheet->getStyle($col . $startRow),
                        $col . $currentRow
                    );
                }

                // Escribir el valor
                // Si la columna debe formatearse como texto, usar setCellValueExplicit
                if (isset($columnConfig['format_as_text']) && $columnConfig['format_as_text']) {
                    $sheet->setCellValueExplicit(
                        $col . $currentRow,
                        $value,
                        DataType::TYPE_STRING
                    );
                } else {
                    $sheet->setCellValue($col . $currentRow, $value);
                }
            }
        }
    }

    /**
     * Genera el archivo de descarga
     */
    protected static function generateDownload($spreadsheet)
    {
        $writer = new Xlsx($spreadsheet);
        $fileName = 'licencias_consultadas_' . now()->format('Y-m-d_His') . '.xlsx';

        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
