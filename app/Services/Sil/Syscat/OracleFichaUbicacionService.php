<?php

namespace App\Services\Sil\Syscat;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OracleFichaUbicacionService
{

    public function obtenerUbicacionPorCoduca(string $coduca)
    {
        try {
            $sql = "
                SELECT 
                    TRIM(f.CODUCA) AS CODUCA, 
                    TRIM(f.CODURB) AS CODURB, 
                    TRIM(u.CODVIA) AS CODVIA,
                    TRIM(u.SECUCA) AS SECUCA,
                    TRIM(u.NUMVIA) AS NUMVIA,
                    TRIM(u.INTDPTO) AS INTDPTO,
                    TRIM(u.BLOCKEDIF) AS BLOCKEDIF,
                    TRIM(u.CODTIPPUE) AS CODTIPPUE,
                    TRIM(u.CODUBIFRE) AS CODUBIFRE,
                    TRIM(f.MZ) AS MZ,
                    TRIM(f.LOTE) AS LOTE,
                    TRIM(f.ZONIFICAC) AS ZONIFICAC,
                    TRIM(f.AREADECL) AS AREADECL,
                    TRIM(f.CODPREDIO) AS CODPREDIO,
                    TRIM(u.SECUBIPRE) AS SECUBIPRE
                FROM smvcatfind f
                INNER JOIN smvcatubipre u ON f.CODUCA = u.CODUCA
                WHERE f.CODUCA = ? 
                ORDER BY 
                    CAST(f.FECHCREA AS DATE) DESC, 
                    f.HORACREA DESC, 
                    CAST(u.SECUCA AS INTEGER) DESC
                FETCH FIRST 1 ROW ONLY
            ";

            $results = DB::connection('oracle')->select($sql, [$coduca]);

            return !empty($results) ? $results[0] : null;

        } catch (\Exception $e) {
            Log::error('Error en OracleFichaUbicacionService/obtenerUbicacionPorCoduca: ' . $e->getMessage(), [
                'coduca' => $coduca
            ]);

            return null;
        }
    }
}
