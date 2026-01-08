<?php

namespace App\Services\Sil\Widgets;

use App\Models\CertificadoLicenciaFuncionamiento;
use App\Models\NivelRiesgo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para obtener datos de licencias agrupadas por nivel de riesgo
 * Utilizado principalmente para widgets y dashboards
 */
class NivelesRiesgoLicenciasService
{

    const BAJO = '#22c55e';   // Verde (Tailwind green-500)
    const MEDIO = '#f59e0b';  // Ámbar (Tailwind amber-500)
    const ALTO = '#ef4444';   // Rojo (Tailwind red-500)
    const MUY_ALTO = '#7f1d1d'; // Rojo oscuro

    // Método para recuperar el color por descripción del NIR
    public static function getColorByDescripcion(string $descripcion): string
    {
        return match (strtoupper($descripcion)) {
            'RIESGO MUY ALTO' => self::MUY_ALTO,
            'RIESGO ALTO' => self::ALTO,
            'RIESGO MEDIO' => self::MEDIO,
            'RIESGO BAJO' => self::BAJO,
            default => '#94a3b8',
        };
    }
    public function obtenerDatosParaChart(?string $filtro = 'all'): array
    {
        $tablaCertificadosLicencia = (new CertificadoLicenciaFuncionamiento())->getTable();
        $tablaNivelesRiesgo = (new NivelRiesgo())->getTable();

        $query = CertificadoLicenciaFuncionamiento::query()
            ->join($tablaNivelesRiesgo, "{$tablaCertificadosLicencia}.nir_id", '=', "{$tablaNivelesRiesgo}.nir_id")
            ->where("{$tablaCertificadosLicencia}.lic_filaeliminada", false);

        // Aplicar filtro de fecha según la opción seleccionada
        match ($filtro) {
            'today' => $query->whereDate("{$tablaCertificadosLicencia}.lic_filafecha", today()),
            'week' => $query->whereBetween("{$tablaCertificadosLicencia}.lic_filafecha", [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]),
            'month' => $query->whereMonth("{$tablaCertificadosLicencia}.lic_filafecha", now()->month)
                ->whereYear("{$tablaCertificadosLicencia}.lic_filafecha", now()->year),
            'year' => $query->whereYear("{$tablaCertificadosLicencia}.lic_filafecha", now()->year),
            default => null, // 'all' - sin filtro de fecha
        };

        return $query
            ->selectRaw("{$tablaNivelesRiesgo}.nir_descripcion as label, count(*) as total")
            ->groupBy("{$tablaNivelesRiesgo}.nir_descripcion")
            ->pluck('total', 'label')
            ->toArray();
    }



}
