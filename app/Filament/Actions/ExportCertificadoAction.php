<?php

namespace App\Filament\Actions;

use App\Models\CertificadoInspeccion;
use Filament\Actions\Action;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;

/**
 * Acción personalizada para exportar certificados de inspección a Excel.
 *
 * Esta clase proporciona una acción de Filament que permite exportar los registros
 * de certificados de inspección filtrados desde la tabla a un archivo Excel (.xlsx)
 * utilizando una plantilla predefinida. La exportación incluye múltiples columnas
 * como Item, Procedimiento, Año, Tipo Edificación, Número, Expediente, etc.
 *
 * Funcionalidades principales:
 * - Aplica filtros activos de la tabla (tipo edificación, año, número, etc.).
 * - Carga una plantilla Excel desde app/Templates/template_exportar_datos_tabla.xlsx.
 * - Reemplaza placeholders como {{fecha_consulta}} y {{name}} en la plantilla.
 * - Busca automáticamente las posiciones de encabezados en la plantilla.
 * - Escribe datos manteniendo estilos de fila plantilla.
 * - Genera descarga del archivo con nombre dinámico.
 *
 * Uso en Filament:
 * - Se registra como acción en la tabla de CertificadoInspeccionResource.
 * - Al ejecutarse, descarga el archivo Excel con los datos filtrados.
 */
class ExportCertificadoAction
{
    /**
     * Crea la instancia de la acción de Filament.
     *
     * Define la etiqueta, ícono, color y la acción que se ejecutará al hacer clic.
     * La acción llama al método export() pasando el livewire de la tabla.
     *
     * @return Action La acción configurada para Filament.
     */
    public static function make(): Action
    {
        return Action::make('exportar_excel')
            ->label('Exportar Certificados')
            ->icon('heroicon-o-document-arrow-down')
            ->color('warning')
            ->action(function ($livewire) {
                return static::export($livewire);
            });
    }

    /**
     * Método principal que coordina la exportación.
     *
     * Obtiene los registros filtrados, carga la plantilla, configura las columnas,
     * escribe los datos y genera la descarga.
     *
     * @param mixed $livewire Instancia de Livewire de la tabla Filament.
     * @return mixed Respuesta de descarga o null si hay error.
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
     * Obtiene los registros filtrados según los filtros aplicados en la tabla.
     *
     * Construye una consulta Eloquent aplicando filtros simples (exactos) y de rango
     * de fechas. Excluye registros eliminados (cin_filaeliminada = false) y ordena
     * por fecha descendente. Carga la relación tipoEdificacion para acceder a tie_descripcion.
     *
     * Filtros aplicados:
     * - Simples: tie_id, cin_anio, cin_numero, cin_solicitante, cin_ubicacion, cin_giro, cin_expediente.
     * - Fecha: rango cin_fecha (desde/hasta).
     *
     * @param mixed $livewire Instancia de Livewire con los filtros de tabla.
     * @return \Illuminate\Database\Eloquent\Collection Colección de registros filtrados.
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
     * Carga la plantilla de Excel desde el directorio de plantillas.
     *
     * Busca el archivo template_exportar_datos_tabla.xlsx en app/Templates/.
     * Si no existe, muestra una notificación de error y retorna null.
     *
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet|null La hoja de cálculo cargada o null si falla.
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
     * Define la configuración de las columnas a exportar.
     *
     * Retorna un array con la configuración de cada columna, incluyendo:
     * - header: Nombre principal del encabezado.
     * - header_variations: Variaciones posibles para buscar en la plantilla.
     * - header_contains: Palabras clave que debe contener el encabezado.
     * - data: Array de datos a escribir.
     * - default_col: Columna por defecto si no se encuentra el encabezado.
     *
     * Columnas incluidas: Item, Procedimiento, Año, Tipo Edificación, Número,
     * Expediente, Resolución, Solicitante, Ubicación, Giro, Fecha, Inicio, Fin,
     * Capacidad, Área.
     *
     * @param \Illuminate\Database\Eloquent\Collection $records Registros a exportar.
     * @return array Configuración de columnas.
     */
    protected static function getExportColumns($records)
    {
        return [
            // Item: número secuencial autogenerado
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
                'header' => 'Expediente',
                'header_variations' => ['expediente'],
                'data' => $records->pluck('cin_expediente')->toArray(),
                'default_col' => 'F',
            ],
            [
                'header' => 'Resolucion',
                'header_variations' => ['resolucion', 'resolución'],
                // Combina cin_resolucion + cin_resolucion_sigla
                'data' => $records->map(function ($record) {
                    return trim($record->cin_resolucion . $record->cin_resolucion_sigla);
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
            // Fecha en formato d/m/Y
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
            // Fecha inicio
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
            // Fecha fin
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
            // Capacidad
            [
                'header' => 'Capacidad',
                'header_variations' => ['capacidad'],
                'data' => $records->pluck('cin_capacidad')->toArray(),
                'default_col' => 'N',
            ],
            // Área
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
     * Reemplaza los placeholders en la plantilla con valores dinámicos.
     *
     * Busca en todas las celdas de la hoja y reemplaza:
     * - {{fecha_consulta}}: Fecha y hora actual en formato d/m/y H:i.
     * - {{name}}: Nombre del usuario autenticado o 'Usuario' si no hay sesión.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet La hoja de cálculo.
     */
    protected static function replacePlaceholders($sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        $fechaConsulta = now()->format('d/m/y H:i');
        $userName = Auth::check() ? Auth::user()->name : 'Usuario';

        // Recorrer todas las celdas buscando placeholders
        for ($r = 1; $r <= $highestRow; $r++) {
            for ($c = 1; $c <= $highestColumnIndex; $c++) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
                $cell = $sheet->getCell($colLetter . $r);
                $cellValue = (string) $cell->getValue();

                // Reemplazar {{fecha_consulta}}
                if (strpos($cellValue, '{{fecha_consulta}}') !== false) {
                    $cellValue = str_replace('{{fecha_consulta}}', $fechaConsulta, $cellValue);
                }

                // Reemplazar {{name}}
                if (strpos($cellValue, '{{name}}') !== false) {
                    $cellValue = str_replace('{{name}}', $userName, $cellValue);
                }

                // Actualizar celda si hubo cambios
                if ($cellValue !== (string) $cell->getValue()) {
                    $cell->setValue($cellValue);
                }
            }
        }
    }

    /**
     * Busca la posición de un encabezado en la hoja de cálculo.
     *
     * Recorre la hoja buscando coincidencias exactas con header_variations
     * o si contiene todas las palabras en header_contains.
     * Si no encuentra, usa la columna por defecto.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet La hoja de cálculo.
     * @param array $columnConfig Configuración de la columna.
     * @return array Posición con 'col' (letra) y 'row' (número).
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
     * Escribe los datos de las columnas en la hoja de cálculo.
     *
     * Primero reemplaza placeholders, luego para cada columna:
     * - Encuentra la posición del encabezado.
     * - Escribe los datos fila por fila, duplicando estilos si hay fila plantilla.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet La hoja de cálculo.
     * @param array $columns Configuración de columnas con datos.
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
     * Genera el archivo Excel y lo prepara para descarga.
     *
     * Guarda el archivo en un temporal, crea una respuesta de descarga
     * con nombre dinámico (certificados_consultados_YYYY-MM-DD_HHMMSS.xlsx)
     * y elimina el archivo después de enviar.
     *
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet La hoja de cálculo.
     * @return \Illuminate\Http\Response Respuesta de descarga.
     */
    protected static function generateDownload($spreadsheet)
    {
        $writer = new Xlsx($spreadsheet);
        $fileName = 'certificados_consultados_' . now()->format('Y-m-d_His') . '.xlsx';

        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}