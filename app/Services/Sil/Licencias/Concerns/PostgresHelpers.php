<?php

namespace App\Services\Sil\Licencias\Concerns;

use Carbon\Carbon;

trait PostgresHelpers
{
    /**
     * Convierte un array PHP a string de array PostgreSQL: "{1,2,3}"
     */
    protected function formatPostgresArray(array $array, bool $isText = false): string
    {
        if (empty($array)) {
            return '{}';
        }

        if ($isText) {
            $processed = array_map(function ($item) {
                // Escapar comillas dobles y backslashes
                $item = str_replace('\\', '\\\\', $item);
                return '"' . str_replace('"', '\\"', $item) . '"';
            }, $array);
            return '{' . implode(',', $processed) . '}';
        }

        return '{' . implode(',', $array) . '}';
    }

    /**
     * Formatea fechas para el SP o devuelve null/string vacío según necesidad
     */
    protected function formatDate($date, $default = null): ?string
    {
        if (empty($date))
            return $default;
        try {
            return Carbon::parse($date)->format('d/m/Y');
        } catch (\Exception $e) {
            return $default;
        }
    }
}
