<?php

namespace App\Filament\Actions;

use App\Models\CertificadoInspeccion;
use Filament\Actions\Action;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportCertificadoAction
{
    public static function make(): Action
    {
        return Action::make('exportar_excel')
            ->label('Exportar Certificados')
            ->icon('heroicon-o-document-arrow-down')
            ->color('success')
            ->action(function ($livewire) {
                return static::export($livewire);
            });
    }

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
     * Obtiene los registros filtrados según los filtros de la tabla
     */
    protected static function getFilteredRecords($livewire)
    {
        $tableFilters = $livewire->tableFilters ?? [];
        $query = CertificadoInspeccion::query()->where('cin_filaeliminada', false);

        // Filtros simples (comparación exacta)
        $simpleFilters = [
            'tie_id', 'cin_anio', 'cin_numero', 'cin_solicitante',
            'cin_ubicacion', 'cin_giro', 'cin_expediente'
        ];

        foreach ($simpleFilters as $filter) {
            if (isset($tableFilters[$filter]['value']) && !empty($tableFilters[$filter]['value'])) {
                $query->where($filter, $tableFilters[$filter]['value']);
            }
        }

        // Filtro de fecha (rango)
        if (isset($tableFilters['cin_fecha']['from']) && !empty($tableFilters['cin_fecha']['from'])) {
            $query->whereDate('cin_fecha', '>=', $tableFilters['cin_fecha']['from']);
        }
        if (isset($tableFilters['cin_fecha']['to']) && !empty($tableFilters['cin_fecha']['to'])) {
            $query->whereDate('cin_fecha', '<=', $tableFilters['cin_fecha']['to']);
        }

        return $query->orderBy('cin_fecha', 'desc')
                    ->with('tipoEdificacion')
                    ->get();
    }

    /**
     * Carga la plantilla de Excel
     */
    protected static function loadTemplate()
    {
        $templatePath = app_path('Templates' . DIRECTORY_SEPARATOR . 'template_exportar_datos_tabla.xlsx');
        
        if (!file_exists($templatePath)) {
            \Filament\Notifications\Notification::make()
                ->title('Error')
                ->body('La plantilla de Excel no fue encontrada en: ' . $templatePath)
                ->danger()
                ->send();
            
            return null;
        }

        return IOFactory::load($templatePath);
    }

    /**
     * Define las columnas a exportar con sus configuraciones
     */
    protected static function getExportColumns($records)
    {
        return [
            //item llenar del 1 hasta el último, autogenera tu el numero en orden del 1 hasta el ultimo
            [
                'header' => 'Item',
                'header_variations' => ['item'],
                'data' => range(1, $records->count()),
                'default_col' => 'A',
            ],
            [
                'header' => 'Procedimiento',
                'header_variations' => ['procedimiento'],
                'data' => $records->pluck('cin_procedimiento')->toArray(),
                'default_col' => 'B',
            ],
            [
                'header' => 'Año',
                'header_variations' => ['año', 'ano'],
                'data' => $records->pluck('cin_anio')->toArray(),
                'default_col' => 'C',
            ],
            [
                'header' => 'Tipo Edificación',
                'header_variations' => [
                    'tipo edificacion',
                    'tipo edificación',
                    'tipo de edificacion',
                    'tipo de edificación',
                ],
                'header_contains' => ['tipo', 'edif'], // Buscar si contiene ambas palabras
                'data' => $records->pluck('tipoEdificacion.tie_descripcion')->toArray(),
                'default_col' => 'D',
            ],
            [
                'header' => 'Número',
                'header_variations' => ['número', 'numero'],
                'data' => $records->pluck('cin_numero')->toArray(),
                'default_col' => 'E',
            ],
            [
                'header'=>'Expediente',
                'header_variations' => ['expediente'],
                'data' => $records->pluck('cin_expediente')->toArray(),
                'default_col' => 'F',
            ],
            [
                'header'=>'Resolucion',
                'header_variations' => ['resolucion', 'resolución'],
                //cin_resolucion + cin_resolucion_sigla
                'data' => $records->map(function ($record) {
                    return trim($record->cin_resolucion. $record->cin_resolucion_sigla);
                })->toArray(),
                'default_col' => 'G',

            ],
            [
                'header' => 'Solicitante',
                'header_variations' => ['solicitante'],
                'data' => $records->pluck('cin_solicitante')->toArray(),
                'default_col' => 'H',
            ],
            [
                'header' => 'Ubicación',
                'header_variations' => ['ubicacion', 'ubicación', 'direccion', 'dirección'],
                'data' => $records->pluck('cin_ubicacion')->toArray(),
                'default_col' => 'I',
            ],
            [
                'header' => 'Giro',
                'header_variations' => ['giro'],
                'data' => $records->pluck('cin_giro')->toArray(),
                'default_col' => 'J',
            ],
            //FECHA EN FORMATO d/m/y
            [
                'header' => 'Fecha',
                'header_variations' => ['fecha'],
                'data' => $records->map(function ($record) {
                    if (!$record->cin_fecha) {
                        return '';
                    }
                    return $record->cin_fecha instanceof \Carbon\Carbon 
                        ? $record->cin_fecha->format('d/m/Y')
                        : \Carbon\Carbon::parse($record->cin_fecha)->format('d/m/Y');
                })->toArray(),
                'default_col' => 'K',
            ],
            //fecha inicio
            [
                'header' => 'Inicio',
                'header_variations' => ['inicio'],
                'data' => $records->map(function ($record) {
                    if (!$record->cin_fec_inicio) {
                        return '';
                    }
                    return $record->cin_fec_inicio instanceof \Carbon\Carbon 
                        ? $record->cin_fec_inicio->format('d/m/Y')
                        : \Carbon\Carbon::parse($record->cin_fec_inicio)->format('d/m/Y');
                })->toArray(),
                'default_col' => 'L',
            ],
            //fecha fin
            [
                'header' => 'Fin',
                'header_variations' => ['fin'],
                'data' => $records->map(function ($record) {
                    if (!$record->cin_fec_fin) {
                        return '';
                    }
                    return $record->cin_fec_fin instanceof \Carbon\Carbon 
                        ? $record->cin_fec_fin->format('d/m/Y')
                        : \Carbon\Carbon::parse($record->cin_fec_fin)->format('d/m/Y');
                })->toArray(),
                'default_col' => 'M',
            ],
            //capacidad
            [
                'header' => 'Capacidad',
                'header_variations' => ['capacidad'],
                'data' => $records->pluck('cin_capacidad')->toArray(),
                'default_col' => 'N',
            ],
            //area
            [
                'header' => 'Área',
                'header_variations' => ['area', 'área'],
                'data' => $records->pluck('cin_area')->toArray(),
                'default_col' => 'O',
            ],
            // Agregar más columnas aquí siguiendo el mismo patrón:
            /*
            [
                'header' => 'Número',
                'header_variations' => ['numero', 'número', 'nro'],
                'data' => $records->pluck('cin_numero')->toArray(),
                'default_col' => 'C',
            ],
            */
        ];
    }

    /**
     * Reemplaza los placeholders en la plantilla
     */
    protected static function replacePlaceholders($sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        
        $fechaConsulta = now('America/Lima')->format('d/m/y H:i');
        
        // Recorrer todas las celdas buscando {{fecha_consulta}}
        for ($r = 1; $r <= $highestRow; $r++) {
            for ($c = 1; $c <= $highestColumnIndex; $c++) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
                $cell = $sheet->getCell($colLetter . $r);
                $cellValue = (string) $cell->getValue();
                
                if (strpos($cellValue, '{{fecha_consulta}}') !== false) {
                    $newValue = str_replace('{{fecha_consulta}}', $fechaConsulta, $cellValue);
                    $cell->setValue($newValue);
                }
            }
        }
    }

    /**
     * Busca la posición de un encabezado en la hoja
     */
    protected static function findHeaderPosition($sheet, $columnConfig)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        for ($r = 1; $r <= $highestRow; $r++) {
            for ($c = 1; $c <= $highestColumnIndex; $c++) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
                $cellValue = trim((string) $sheet->getCell($colLetter . $r)->getValue());
                
                if ($cellValue === '') {
                    continue;
                }
                
                $lower = mb_strtolower($cellValue);
                
                // Verificar coincidencias exactas
                if (isset($columnConfig['header_variations'])) {
                    foreach ($columnConfig['header_variations'] as $variation) {
                        if ($lower === $variation) {
                            return ['col' => $colLetter, 'row' => $r];
                        }
                    }
                }
                
                // Verificar si contiene palabras clave
                if (isset($columnConfig['header_contains'])) {
                    $allFound = true;
                    foreach ($columnConfig['header_contains'] as $keyword) {
                        if (strpos($lower, $keyword) === false) {
                            $allFound = false;
                            break;
                        }
                    }
                    if ($allFound) {
                        return ['col' => $colLetter, 'row' => $r];
                    }
                }
            }
        }

        // Si no se encuentra, usar valores por defecto
        return ['col' => $columnConfig['default_col'], 'row' => 1];
    }

    /**
     * Escribe los datos en la hoja de cálculo
     */
    protected static function writeDataToSheet($sheet, $columns)
    {
        // Reemplazar {{fecha_consulta}} con la fecha y hora actual
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
                $sheet->setCellValue($col . $currentRow, $value);
            }
        }
    }

    /**
     * Genera el archivo y lo prepara para descarga
     */
    protected static function generateDownload($spreadsheet)
    {
        $writer = new Xlsx($spreadsheet);
        $fileName = 'certificados_consultados_' . now('America/Lima')->format('Y-m-d_His') . '.xlsx';
        
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($tempFile);
        
        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}