<?php

namespace App\Services\Sil\Personas;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonaService
{
    /**
     * Crea una nueva persona usando el stored procedure licencia.spu_persona_ins
     */
    public function create(array $data)
    {
        try {
            $sql = "SELECT * FROM licencia.spu_persona_ins(
                ?::character varying, -- p_per_nombrerazonsocial
                ?::character,         -- p_ruc
                ?::character varying, -- p_direccion
                ?::character varying, -- p_telefono
                ?::character varying  -- p_email
            )";

            $parametros = [
                $data['per_nombrerazonsocial'] ?? '',
                $data['per_ruc'] ?? null,
                $data['per_direccion'] ?? '',
                $data['per_telefono'] ?? '',
                $data['per_email'] ?? ''
            ];

            // Ejecuta el SP en la conexión 'pgsql_licencias' que es la que se usa para licencia.persona según el modelo
            $result = DB::connection('pgsql_licencias')->select($sql, $parametros);

            if (empty($result)) {
                throw new \RuntimeException('El procedimiento almacenado no retornó ningún resultado');
            }

            $spResult = $result[0];
            $error = $spResult->error ?? 0;
            $mensaje = $spResult->mensaje ?? 'Error desconocido';

            // Según el SP:
            // error > 0: Éxito (retorna el nuevo ID o el ID existente si ya existía)
            // error = -10: Error al generar registro
            // error = 0: Excepción o vacío

            if ($error <= 0 && $error != -10) {
                // Consideramos <=0 errores, aunque el SP usa retorno v_per_id si existe que es > 0.
                // Si error es -10 es un error explícito.
                // Si exception ocurre devuelve 0.
                if ($error === 0 && str_contains($mensaje, 'generar el Registro')) {
                    throw new \RuntimeException($mensaje);
                }
            }

            if ($error == -10) {
                throw new \RuntimeException($mensaje);
            }

            return [
                'success' => true,
                'per_id' => $error,
                'message' => $mensaje,
                'exists' => ($mensaje === 'Nombre/Razon Social ya existe.')
            ];

        } catch (\Exception $e) {
            Log::error('Error creating persona via SP', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Crea una nueva persona única usando el stored procedure licencia.spu_persona_insertar_unica
     * Este método incluye el código de contribuyente de Oracle (EXP_CODCON)
     *
     * @param array $data Datos de la persona
     * @return array Resultado de la operación
     */
    public function create_unico(array $data)
    {
        try {
            Log::info('Iniciando create_unico', ['data' => $data]);

            $sql = "SELECT * FROM licencia.spu_persona_insertar_unica(?, ?, ?, ?, ?, ?)";

            $parametros = [
                $data['per_nombrerazonsocial'] ?? '',
                $data['per_ruc'] ?? '',
                $data['per_direccion'] ?? '',
                $data['per_telefono'] ?? '',
                $data['per_email'] ?? '',
                $data['per_expcodcon'] ?? '' // Código de contribuyente de Oracle
            ];

            Log::info('Ejecutando spu_persona_insertar_unica', ['parametros' => $parametros]);

            // Ejecuta el SP en la conexión 'pgsql_licencias'
            $result = DB::connection('pgsql_licencias')->select($sql, $parametros);

            if (empty($result)) {
                throw new \RuntimeException('El procedimiento almacenado no retornó ningún resultado');
            }

            $spResult = $result[0];

            Log::info('Resultado del SP', ['spResult' => $spResult]);

            // El SP retorna error_out y mensaje_out
            $errorCode = $spResult->error_out ?? 0;
            $mensaje = $spResult->mensaje_out ?? 'Sin mensaje';

            /*
             * Códigos de error del SP:
             * -1: Nombre vacío
             * -20: Duplicado exacto
             * -500: Error interno
             * > 0: Éxito (retorna el per_id)
             */

            // Error: Nombre vacío
            if ($errorCode === -1) {
                return [
                    'success' => false,
                    'error_code' => -1,
                    'message' => $mensaje,
                    'per_id' => null,
                    'type' => 'validation'
                ];
            }

            // Error: Duplicado exacto
            if ($errorCode === -20) {
                return [
                    'success' => false,
                    'error_code' => -20,
                    'message' => $mensaje,
                    'per_id' => null,
                    'type' => 'duplicate'
                ];
            }

            // Error: Error interno
            if ($errorCode === -500) {
                return [
                    'success' => false,
                    'error_code' => -500,
                    'message' => $mensaje,
                    'per_id' => null,
                    'type' => 'internal'
                ];
            }

            // Éxito: error_out > 0 es el ID de la persona creada
            if ($errorCode > 0) {
                return [
                    'success' => true,
                    'error_code' => $errorCode,
                    'message' => $mensaje,
                    'per_id' => $errorCode,
                    'type' => 'success'
                ];
            }

            // Cualquier otro caso (no debería ocurrir)
            return [
                'success' => false,
                'error_code' => $errorCode,
                'message' => $mensaje ?: 'Error desconocido',
                'per_id' => null,
                'type' => 'unknown'
            ];

        } catch (\Exception $e) {
            Log::error('Error en create_unico', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'error_code' => -999,
                'message' => 'Error de conexión: ' . $e->getMessage(),
                'per_id' => null,
                'type' => 'exception'
            ];
        }
    }
}
