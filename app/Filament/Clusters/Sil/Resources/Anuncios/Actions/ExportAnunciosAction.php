<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Actions;

use Filament\Actions\Action;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\Anuncios;
use Illuminate\Database\Eloquent\Builder;

class ExportAnunciosAction
{
    /**
     * Crea la acción de exportar anuncios
     */
    public static function make(): Action
    {
        return Action::make('exportar_anuncios')
            ->label('Exportar Anuncios')
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
        $records = static::getFilteredRecords($livewire);

        $spreadsheet = static::loadTemplate();
        if (!$spreadsheet) {
            return null;
        }

        $sheet = $spreadsheet->getActiveSheet();

        $columns = static::getExportColumns($records);

        static::writeDataToSheet($sheet, $columns);

        return static::generateDownload($spreadsheet);
    }

    /**
     * Obtiene los registros filtrados desde el Livewire
     */
    protected static function getFilteredRecords($livewire)
    {
        $tableFilters = $livewire->tableFilters ?? [];
        $query = Anuncios::query();

        // Búsqueda Global (Search Bar)
        $searchTerm = null;
        if (isset($livewire->tableSearch)) {
            $searchTerm = $livewire->tableSearch;
        } elseif (method_exists($livewire, 'getTableSearch')) {
            try {
                $searchTerm = $livewire->getTableSearch();
            } catch (\Throwable $e) {
                $searchTerm = null;
            }
        }

        if (!empty($searchTerm) && trim((string) $searchTerm) !== '') {
            $search = trim((string) $searchTerm);
            $query->where(function ($q) use ($search) {
                $q->where('n_anuncio', 'ILIKE', "%{$search}%")
                    ->orWhereHas('expediente', function ($eq) use ($search) {
                        $eq->where('n_expediente', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('documentos', function ($dq) use ($search) {
                        $dq->where('tipo_documento', 'INFORME TÉCNICO')
                            ->where('n_documento', 'ILIKE', "%{$search}%");
                    });
            });
        }

        // Filtro de fecha expediente (rango)
        if (isset($tableFilters['fecha_expediente'])) {
            $filterData = $tableFilters['fecha_expediente'];
            if (!empty($filterData['desde'])) {
                $query->whereHas('expediente', function ($q) use ($filterData) {
                    $q->whereDate('fecha_expediente', '>=', $filterData['desde']);
                });
            }
            if (!empty($filterData['hasta'])) {
                $query->whereHas('expediente', function ($q) use ($filterData) {
                    $q->whereDate('fecha_expediente', '<=', $filterData['hasta']);
                });
            }
        }

        // Filtro por tab activo (dictamen/vigencia)
        $activeTab = null;
        if (isset($livewire->activeTab)) {
            $activeTab = $livewire->activeTab;
        }

        if ($activeTab && $activeTab !== 'todos') {
            switch ($activeTab) {
                case 'procedentes':
                    $query->where('dictamen', 'PROCEDENTE');
                    break;
                case 'improcedentes':
                    $query->where('dictamen', 'IMPROCEDENTE');
                    break;
                case 'temporales':
                    $query->where('vigencia', 'TEMPORAL');
                    break;
                case 'indeterminadas':
                    $query->where('vigencia', 'INDETERMINADA');
                    break;
            }
        }

        return $query->orderBy('created_at', 'desc')
            ->with([
                'expediente.zonificacion',
                'expediente.reciboPago',
                'documentos',
                'caracteristicaFisica',
                'tipoAnuncio',
                'licencia',
                'colores',
                'derivadoLegal',
            ])
            ->get();
    }

    /**
     * Carga la plantilla de Excel
     */
    protected static function loadTemplate()
    {
        $templatePath = app_path(
            'Filament' . DIRECTORY_SEPARATOR .
            'Clusters' . DIRECTORY_SEPARATOR .
            'Sil' . DIRECTORY_SEPARATOR .
            'Resources' . DIRECTORY_SEPARATOR .
            'Anuncios' . DIRECTORY_SEPARATOR .
            'Template' . DIRECTORY_SEPARATOR .
            'template_exportar_anuncios.xlsx'
        );

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
            // 1. ITEM
            [
                'header' => 'ITEM',
                'header_variations' => ['item', 'Item', 'ITEM'],
                'data' => range(1, $records->count()),
                'default_col' => 'A',
            ],
            // 2. EXPEDIENTE
            [
                'header' => 'EXPEDIENTE',
                'header_variations' => ['expediente', 'Expediente', 'EXPEDIENTE'],
                'data' => $records->map(fn($r) => $r->expediente?->n_expediente ?? '')->toArray(),
                'default_col' => 'B',
            ],
            // 3. FECHA DEL EXPEDIENTE
            [
                'header' => 'FECHA DEL EXPEDIENTE',
                'header_variations' => ['fecha del expediente', 'Fecha Del Expediente', 'FECHA DEL EXPEDIENTE', 'FECHA EXPEDIENTE'],
                'data' => $records->map(fn($r) => $r->expediente?->fecha_expediente?->format('d/m/Y') ?? '')->toArray(),
                'default_col' => 'C',
            ],
            // 4. FECHA DE RECEPCION PARA EVALUAR
            [
                'header' => 'FECHA DE RECEPCION PARA EVALUAR',
                'header_variations' => ['fecha de recepcion para evaluar', 'FECHA DE RECEPCION PARA EVALUAR', 'FECHA RECEPCION'],
                'data' => $records->map(fn($r) => $r->fecha_recepcion_evaluar?->format('d/m/Y') ?? '')->toArray(),
                'default_col' => 'D',
            ],
            // 5. INFORME TECNICO
            [
                'header' => 'INFORME TECNICO',
                'header_variations' => ['informe tecnico', 'INFORME TECNICO', 'INFORME TÉCNICO'],
                'data' => $records->map(fn($r) => $r->documentos->where('tipo_documento', 'INFORME TÉCNICO')->first()?->n_documento ?? '')->toArray(),
                'default_col' => 'E',
            ],
            // 6. FECHA EMISION INFORME TECNICO
            [
                'header' => 'FECHA EMISION INFORME TECNICO',
                'header_variations' => ['fecha emision informe tecnico', 'FECHA EMISION INFORME TECNICO', 'FECHA EMISION INFORME TÉCNICO'],
                'data' => $records->map(fn($r) => $r->documentos->where('tipo_documento', 'INFORME TÉCNICO')->first()?->fecha_emision?->format('d/m/Y') ?? '')->toArray(),
                'default_col' => 'F',
            ],
            // 7. CARTA
            [
                'header' => 'CARTA',
                'header_variations' => ['carta', 'Carta', 'CARTA'],
                'data' => $records->map(fn($r) => $r->documentos->where('tipo_documento', 'CARTA')->first()?->n_documento ?? '')->toArray(),
                'default_col' => 'G',
            ],
            // 8. FECHA DE EMISION CARTA
            [
                'header' => 'FECHA DE EMISION CARTA',
                'header_variations' => ['fecha de emision carta', 'FECHA DE EMISION CARTA'],
                'data' => $records->map(fn($r) => $r->documentos->where('tipo_documento', 'CARTA')->first()?->fecha_emision?->format('d/m/Y') ?? '')->toArray(),
                'default_col' => 'H',
            ],
            // 9. SOLICITANTE
            [
                'header' => 'SOLICITANTE',
                'header_variations' => ['solicitante', 'Solicitante', 'SOLICITANTE'],
                'data' => $records->map(fn($r) => $r->expediente?->snapshot_solicitante_nombre_completo ?? '')->toArray(),
                'default_col' => 'I',
            ],
            // 10. DNI/ RUC
            [
                'header' => 'DNI/ RUC',
                'header_variations' => ['dni/ ruc', 'DNI/ RUC', 'DNI/RUC', 'dni/ruc'],
                'data' => $records->map(fn($r) => $r->expediente?->snapshot_solicitante_dni ?? '')->toArray(),
                'default_col' => 'J',
                'format_as_text' => true,
            ],
            // 11. REPRESENTANTE LEGAL O APODERADO
            [
                'header' => 'REPRESENTANTE LEGAL O APODERADO',
                'header_variations' => ['representante legal o apoderado', 'REPRESENTANTE LEGAL O APODERADO', 'REPRESENTANTE LEGAL'],
                'data' => $records->map(fn($r) => $r->expediente?->snapshot_legal_nombre ?? '')->toArray(),
                'default_col' => 'K',
            ],
            // 12. DNI / CARNET DE EXT.
            [
                'header' => 'DNI / CARNET DE EXT.',
                'header_variations' => ['dni / carnet de ext.', 'DNI / CARNET DE EXT.', 'DNI/CARNET'],
                'data' => $records->map(fn($r) => $r->expediente?->snapshot_legal_dni_ruc ?? '')->toArray(),
                'default_col' => 'L',
                'format_as_text' => true,
            ],
            // 13. TELEFONO SISTEMA/DECLARADO
            [
                'header' => 'TELEFONO SISTEMA/DECLARADO',
                'header_variations' => ['telefono sistema/declarado', 'TELEFONO SISTEMA/DECLARADO', 'TELEFONO'],
                'data' => $records->map(fn($r) => $r->expediente?->snapshot_solicitante_telefono ?? '')->toArray(),
                'default_col' => 'M',
            ],
            // 14. DIRECCION FISCAL
            [
                'header' => 'DIRECCION FISCAL',
                'header_variations' => ['direccion fiscal', 'DIRECCION FISCAL', 'Direccion Fiscal'],
                'data' => $records->map(fn($r) => $r->expediente?->snapshot_legal_direccion ?? '')->toArray(),
                'default_col' => 'N',
            ],
            // 15. DIRECCION DEL PREDIO MATERIA A EVALUAR
            [
                'header' => 'DIRECCION DEL PREDIO MATERIA A EVALUAR',
                'header_variations' => ['direccion del predio materia a evaluar', 'DIRECCION DEL PREDIO MATERIA A EVALUAR', 'DIRECCION DEL PREDIO'],
                'data' => $records->map(fn($r) => $r->expediente?->snapshot_solicitante_direccion ?? '')->toArray(),
                'default_col' => 'O',
            ],
            // 16. ASUNTO
            [
                'header' => 'ASUNTO',
                'header_variations' => ['asunto', 'Asunto', 'ASUNTO'],
                'data' => $records->map(fn($r) => $r->asunto?->value ?? '')->toArray(),
                'default_col' => 'P',
            ],
            // 17. CARACTERISTICAS FISICAS
            [
                'header' => 'CARACTERISTICAS FISICAS',
                'header_variations' => ['caracteristicas fisicas', 'CARACTERISTICAS FISICAS', 'CARACTERÍSTICAS FÍSICAS'],
                'data' => $records->map(fn($r) => $r->caracteristicaFisica?->descripcion ?? '')->toArray(),
                'default_col' => 'Q',
            ],
            // 18. TIPO DE ANUNCIO
            [
                'header' => 'TIPO DE ANUNCIO',
                'header_variations' => ['tipo de anuncio', 'TIPO DE ANUNCIO', 'Tipo De Anuncio'],
                'data' => $records->map(fn($r) => $r->tipoAnuncio?->descripcion ?? '')->toArray(),
                'default_col' => 'R',
            ],
            // 19. LICENCIA DE F.
            [
                'header' => 'LICENCIA DE F.',
                'header_variations' => ['licencia de f.', 'LICENCIA DE F.', 'LICENCIA', 'N° LICENCIA'],
                'data' => $records->map(fn($r) => $r->licencia?->lic_numlic ?? '')->toArray(),
                'default_col' => 'S',
            ],
            // 20. GIRO
            [
                'header' => 'GIRO',
                'header_variations' => ['giro', 'Giro', 'GIRO'],
                'data' => $records->map(fn($r) => $r->giro_especifico_snapshot ?? '')->toArray(),
                'default_col' => 'T',
            ],
            // 21. ZONIFICACION
            [
                'header' => 'ZONIFICACION',
                'header_variations' => ['zonificacion', 'Zonificacion', 'ZONIFICACION', 'ZONIFICACIÓN'],
                'data' => $records->map(function ($r) {
                    $zon = $r->expediente?->zonificacion;
                    return $zon ? "{$zon->siglas} - {$zon->descripcion}" : '';
                })->toArray(),
                'default_col' => 'U',
            ],
            // 22. DESCRIPCION
            [
                'header' => 'DESCRIPCION',
                'header_variations' => ['descripcion', 'Descripcion', 'DESCRIPCION', 'DESCRIPCIÓN'],
                'data' => $records->map(fn($r) => $r->descripcion ?? '')->toArray(),
                'default_col' => 'V',
            ],
            // 23. MEDIDAS
            [
                'header' => 'MEDIDAS',
                'header_variations' => ['medidas', 'Medidas', 'MEDIDAS'],
                'data' => $records->map(fn($r) => "{$r->ancho_m}m x {$r->alto_m}m x {$r->espesor_cm}cm")->toArray(),
                'default_col' => 'W',
            ],
            // 24. UBICACIÓN DEL ANUNCIO
            [
                'header' => 'UBICACIÓN DEL ANUNCIO',
                'header_variations' => ['ubicación del anuncio', 'UBICACIÓN DEL ANUNCIO', 'UBICACION DEL ANUNCIO'],
                'data' => $records->map(fn($r) => $r->ubicacion_del_anuncio ?? '')->toArray(),
                'default_col' => 'X',
            ],
            // 25. COLORES
            [
                'header' => 'COLORES',
                'header_variations' => ['colores', 'Colores', 'COLORES'],
                'data' => $records->map(fn($r) => $r->colores->pluck('descripcion')->implode(', '))->toArray(),
                'default_col' => 'Y',
            ],
            // 26. MATERIALES
            [
                'header' => 'MATERIALES',
                'header_variations' => ['materiales', 'Materiales', 'MATERIALES'],
                'data' => $records->map(fn($r) => $r->materiales_descripcion ?? '')->toArray(),
                'default_col' => 'Z',
            ],
            // 27. N° DE CARAS
            [
                'header' => 'N° DE CARAS',
                'header_variations' => ['n° de caras', 'N° DE CARAS', 'N DE CARAS', 'NUMERO DE CARAS'],
                'data' => $records->map(fn($r) => $r->n_de_caras ?? '')->toArray(),
                'default_col' => 'AA',
            ],
            // 28. RECIBO DE PAGO
            [
                'header' => 'RECIBO DE PAGO',
                'header_variations' => ['recibo de pago', 'RECIBO DE PAGO', 'N° RECIBO'],
                'data' => $records->map(fn($r) => $r->expediente?->reciboPago?->n_recibo_pago ?? '')->toArray(),
                'default_col' => 'AB',
            ],
            // 29. VIGENCIA
            [
                'header' => 'VIGENCIA',
                'header_variations' => ['vigencia', 'Vigencia', 'VIGENCIA'],
                'data' => $records->map(fn($r) => $r->vigencia?->value ?? '')->toArray(),
                'default_col' => 'AC',
            ],
            // 30. DICTAMEN
            [
                'header' => 'DICTAMEN',
                'header_variations' => ['dictamen', 'Dictamen', 'DICTAMEN'],
                'data' => $records->map(fn($r) => $r->dictamen?->value ?? '')->toArray(),
                'default_col' => 'AD',
            ],
            // 31. COSTO
            [
                'header' => 'COSTO',
                'header_variations' => ['costo', 'Costo', 'COSTO', 'MONTO'],
                'data' => $records->map(fn($r) => $r->expediente?->reciboPago?->monto ?? '')->toArray(),
                'default_col' => 'AE',
            ],
            // 32. FOLIOS
            [
                'header' => 'FOLIOS',
                'header_variations' => ['folios', 'Folios', 'FOLIOS'],
                'data' => $records->map(fn($r) => $r->expediente?->folios ?? '')->toArray(),
                'default_col' => 'AF',
            ],
            // 33. OBS
            [
                'header' => 'OBS',
                'header_variations' => ['obs', 'Obs', 'OBS', 'OBSERVACIONES'],
                'data' => $records->map(fn($r) => $r->obs ?? '')->toArray(),
                'default_col' => 'AG',
            ],
            // 34. DERIVADO A
            [
                'header' => 'DERIVADO A',
                'header_variations' => ['derivado a', 'DERIVADO A', 'Derivado A'],
                'data' => $records->map(fn($r) => $r->derivadoLegal?->name ?? '')->toArray(),
                'default_col' => 'AH',
            ],
            // 35. FECHA DERIVADO
            [
                'header' => 'FECHA DERIVADO',
                'header_variations' => ['fecha derivado', 'FECHA DERIVADO', 'Fecha Derivado'],
                'data' => $records->map(fn($r) => $r->fecha_derivado?->format('d/m/Y') ?? '')->toArray(),
                'default_col' => 'AI',
            ],
            // 36. N° DE RESOLUCION
            [
                'header' => 'N° DE RESOLUCION',
                'header_variations' => ['n° de resolucion', 'N° DE RESOLUCION', 'N° DE RESOLUCIÓN', 'RESOLUCION'],
                'data' => $records->map(fn($r) => $r->expediente?->n_resolucion_subgerencial ?? '')->toArray(),
                'default_col' => 'AJ',
            ],
            // 37. N° CODIGO DE CARTON
            [
                'header' => 'N° CODIGO DE CARTON',
                'header_variations' => ['n° codigo de carton', 'N° CODIGO DE CARTON', 'CODIGO DE CARTON'],
                'data' => $records->map(fn($r) => $r->n_anuncio ?? '')->toArray(),
                'default_col' => 'AK',
            ],
            // 38. FECHA DE EMISION
            [
                'header' => 'FECHA DE EMISION',
                'header_variations' => ['fecha de emision', 'FECHA DE EMISION', 'FECHA DE EMISIÓN'],
                'data' => $records->map(fn($r) => $r->expediente?->fecha_resolucion_subgerencial?->format('d/m/Y') ?? '')->toArray(),
                'default_col' => 'AL',
            ],
            // 39. DERIVADO A (segunda columna, para legal)
            [
                'header' => 'DERIVADO A ',
                'header_variations' => ['derivado a '],
                'data' => $records->map(fn($r) => $r->derivadoLegal?->name ?? '')->toArray(),
                'default_col' => 'AM',
            ],
        ];
    }

    /**
     * Reemplaza los placeholders en la plantilla
     */
    protected static function replacePlaceholders($sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($row = 1; $row <= $highestRow; $row++) {
            for ($colIndex = 1; $colIndex <= $highestColIndex; $colIndex++) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $cellValue = $sheet->getCell($col . $row)->getValue();

                if (is_string($cellValue)) {
                    if (str_contains($cellValue, '{{fecha_consulta}}')) {
                        $cellValue = str_replace(
                            '{{fecha_consulta}}',
                            now()->format('d/m/Y H:i:s'),
                            $cellValue
                        );
                    }

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
    protected static function findHeaderPosition($sheet, $columnConfig, &$usedPositions = [])
    {
        $highestRow = $sheet->getHighestRow();
        $highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($row = 1; $row <= min($highestRow, 10); $row++) { // Limitar búsqueda a las primeras 10 filas para evitar falsos positivos con datos
            for ($colIndex = 1; $colIndex <= $highestColIndex; $colIndex++) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $cellValue = $sheet->getCell($col . $row)->getValue();

                if (is_string($cellValue)) {
                    $cellValue = trim($cellValue);
                    $posId = $col . $row;

                    // Si esta celda ya fue usada por otro encabezado, saltar
                    if (in_array($posId, $usedPositions)) {
                        continue;
                    }

                    foreach ($columnConfig['header_variations'] as $variation) {
                        if (strcasecmp($cellValue, trim($variation)) === 0) {
                            $usedPositions[] = $posId;
                            return ['col' => $col, 'row' => $row];
                        }
                    }
                }
            }
        }

        return ['col' => $columnConfig['default_col'], 'row' => 1];
    }

    /**
     * Escribe los datos en la hoja de cálculo
     */
    protected static function writeDataToSheet($sheet, $columns)
    {
        static::replacePlaceholders($sheet);
        $usedPositions = [];

        foreach ($columns as $columnConfig) {
            $position = static::findHeaderPosition($sheet, $columnConfig, $usedPositions);
            $col = $position['col'];
            $headerRow = $position['row'];
            $startRow = $headerRow + 1;
            $data = $columnConfig['data'];

            $templateRowExists = !empty($sheet->getCell($col . $startRow)->getValue()) ||
                $sheet->getStyle($col . $startRow)->getFont()->getSize() > 0;

            foreach ($data as $i => $value) {
                $currentRow = $startRow + $i;

                if ($i > 0 && $templateRowExists) {
                    $sheet->duplicateStyle(
                        $sheet->getStyle($col . $startRow),
                        $col . $currentRow
                    );
                }

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
        $fileName = 'anuncios_consultados_' . now()->format('Y-m-d_His') . '.xlsx';

        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
