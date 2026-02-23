<?php

namespace App\Services\Sil\Anuncios;

use App\Models\ExpedienteGestrad;
use Illuminate\Support\Facades\Log;

class ExpedienteAnuncioService
{
    /**
     * Obtiene todos los datos de un expediente por su número.
     *
     * @param string $n_expediente
     * @return ExpedienteGestrad|null
     */
    public function getExpedienteByNumero(string $n_expediente): ?ExpedienteGestrad
    {
        try {
            return ExpedienteGestrad::with('contribuyente')
                ->leftJoin('DS_VALORES.VU_PERSONA2', 'DS_VALORES.DUR_EXPEDIENTE.EXP_CODCON', '=', 'DS_VALORES.VU_PERSONA2.CODCON')
                ->select([
                    'DS_VALORES.DUR_EXPEDIENTE.EXP_NUM',
                    'DS_VALORES.DUR_EXPEDIENTE.EXP_CODCON',
                    'DS_VALORES.DUR_EXPEDIENTE.EXP_NOMREC',
                    'DS_VALORES.DUR_EXPEDIENTE.EXP_NUMFOL',
                    'DS_VALORES.DUR_EXPEDIENTE.EXP_FEC',
                    'DS_VALORES.DUR_EXPEDIENTE.EXP_TELEFONO',
                    'DS_VALORES.DUR_EXPEDIENTE.EXP_EMAIL',

                    'DS_VALORES.VU_PERSONA2.numdoc',
                    'DS_VALORES.VU_PERSONA2.nomcom',
                    'DS_VALORES.VU_PERSONA2.domfis'
                ])
                ->where('DS_VALORES.DUR_EXPEDIENTE.EXP_NUM', $n_expediente)
                ->first();
        } catch (\Exception $e) {
            Log::error("Error al obtener expediente Gestrad: " . $e->getMessage(), [
                'n_expediente' => $n_expediente,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }


    public function getDatosPersonaPorDni(string $dni): ?object
    {
        try {
            // Log para ver exactamente qué string estamos mandando a Oracle
            Log::info("Consultando VU_PERSONA2 para DNI: '{$dni}'");

            // Usamos TRIM en la base de datos por si es un campo de longitud fija (CHAR)
            // que rellena con espacios a la derecha.
            $sql = "SELECT 
                        NUMDOC, 
                        NOMCOM,
                        NUMTEL
                    FROM DS_VALORES.VU_PERSONA2 
                    WHERE TRIM(NUMDOC) = :dni 
                    AND ROWNUM = 1";

            $resultado = \DB::connection('oracle') // Asegúrate de especificar la conexión si no es la default
                ->select($sql, ['dni' => trim($dni)]);

            if (!empty($resultado)) {
                Log::info("Persona encontrada: " . $resultado[0]->nomcom);
                return (object) $resultado[0];
            }

            Log::warning("No se encontró ninguna persona con DNI: {$dni}");
            return null;

        } catch (\Exception $e) {
            Log::error("Error SQL nativo en VU_PERSONA2: " . $e->getMessage(), [
                'dni' => $dni,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}
