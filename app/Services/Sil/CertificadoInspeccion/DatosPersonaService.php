<?php

namespace App\Services\Sil\CertificadoInspeccion;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatosPersonaService
{

    public function obtenerRucPersonaPorExpediente(string $expNum): ?object
    {
        try {
            Log::info("Consultando RUC de persona en Oracle para expediente: {$expNum}");

            $sql = "SELECT 
                        e.EXP_NUM,
                        e.EXP_CODCON as CODCON,
                        e.EXP_NOMREC as NOMBRE_EXPEDIENTE,
                        p.numdoc as RUC
                    FROM DS_VALORES.DUR_EXPEDIENTE e
                    LEFT JOIN DS_VALORES.VU_PERSONA2 p ON e.EXP_CODCON = p.CODCON
                    WHERE e.EXP_NUM = :exp_num";

            $resultado = DB::connection('oracle')->select($sql, ['exp_num' => $expNum]);

            if (!empty($resultado)) {
                return (object) $resultado[0];
            }

            Log::warning("No se encontró información para el expediente: {$expNum}");
            return null;

        } catch (\Exception $e) {
            Log::error("Error al obtener RUC de persona por expediente en Oracle: " . $e->getMessage(), [
                'exp_num' => $expNum,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function buscarExpedientesPorCriterio(string $search): array
    {
        try {
            $sql = "SELECT e.EXP_NUM 
                FROM DS_VALORES.DUR_EXPEDIENTE e
                LEFT JOIN DS_VALORES.VU_PERSONA2 p ON e.EXP_CODCON = p.CODCON
                WHERE p.numdoc LIKE :search1 OR e.EXP_NOMREC LIKE :search2";

            $resultados = DB::connection('oracle')->select($sql, [
                'search1' => "%{$search}%",
                'search2' => "%{$search}%",
            ]);

            return array_column($resultados, 'exp_num');
        } catch (\Exception $e) {
            Log::error("Error en buscarExpedientesPorCriterio: " . $e->getMessage());
            return [];
        }
    }
}
