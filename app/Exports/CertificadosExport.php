<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Clase para exportar certificados de inspección a Excel.
 *
 * Esta clase implementa la exportación de datos de certificados de inspección
 * utilizando Laravel Excel (maatwebsite/excel). Actualmente exporta únicamente
 * la columna 'Año' (cin_anio) basada en una consulta Eloquent proporcionada.
 *
 * Uso típico:
 * - Se instancia con un Builder de CertificadoInspeccion filtrado.
 * - Exporta a Excel con encabezado "Año" y los valores correspondientes.
 */
class CertificadosExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * Consulta Eloquent para la exportación.
     *
     * @var Builder
     */
    protected Builder $query;

    /**
     * Constructor de la clase.
     *
     * Recibe una consulta Eloquent (Builder) que se utilizará para obtener
     * los datos a exportar. Esta consulta puede incluir filtros aplicados
     * desde Filament o cualquier otro lugar.
     *
     * @param Builder $query La consulta Eloquent con los filtros aplicados.
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * Define la consulta para la exportación.
     *
     * Selecciona únicamente la columna 'cin_anio' de la tabla
     * certificadoinspeccion para optimizar la consulta y reducir
     * el tamaño del archivo exportado.
     *
     * @return Builder La consulta modificada con select.
     */
    public function query(): Builder
    {
        return $this->query->select('cin_anio');
    }

    /**
     * Define los encabezados de las columnas en el archivo Excel.
     *
     * @return array Lista de encabezados. Actualmente solo "Año".
     */
    public function headings(): array
    {
        return ['Año'];
    }

    /**
     * Mapea cada fila de datos para la exportación.
     *
     * Transforma el objeto de modelo en un array que representa
     * una fila en el Excel. Actualmente solo incluye el año.
     *
     * @param mixed $row El objeto del modelo (CertificadoInspeccion).
     * @return array Array con los valores mapeados para la fila.
     */
    public function map($row): array
    {
        return [
            $row->cin_anio,
        ];
    }
}