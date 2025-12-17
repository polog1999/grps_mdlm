<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;
use App\Services\Sil\Licencias\Concerns\PostgresHelpers;
use Illuminate\Support\Facades\Log;

class LicenciaTransferor
{
    use PostgresHelpers;

    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Ejecuta el procedimiento almacenado para transferir una licencia
     * 
     * @param array $data Datos de la licencia a transferir
     * @return array Resultado del stored procedure
     * @throws \RuntimeException Si el SP retorna error
     */
    public function execute(array $data)
    {
        try {
            // Procesar giros si existen
            $girosIds = [];
            $girosEspecificos = [];

            if (isset($data['giros']) && is_array($data['giros'])) {
                foreach ($data['giros'] as $giro) {
                    $girosIds[] = $giro['gir_id'] ?? 0;
                    $girosEspecificos[] = $giro['especifico'] ?? '';
                }
            }

            // SQL para llamar al stored procedure de transferencia
            // RETURNS TABLE(error integer, mensaje character varying)
            $sql = "SELECT * FROM licencia.spu_licencia_upd_transferir2(
                ?::integer,      -- 1  p_fiu_id
                ?::integer[],    -- 2  p_gir_id
                ?::text[],       -- 3  p_lig_giroespecifico
                ?::integer,      -- 4  p_tli_id
                ?::integer,      -- 5  p_tes_id
                ?::integer,      -- 6  p_per_idsolicitante
                ?::integer,      -- 7  p_per_idrazonsocial
                ?,               -- 8  p_lic_numlic
                ?,               -- 9  p_lic_codigopredial
                ?,               -- 10 p_lic_expnum
                ?::numeric,      -- 11 p_lic_area
                ?::boolean,      -- 12 p_lic_mype
                ?,               -- 13 p_lic_resnum
                ?,               -- 14 p_lic_fecharesolucion (character)
                ?,               -- 15 p_lic_fechaemision (character)
                ?,               -- 16 p_lic_fechavencimiento (character)
                ?,               -- 17 p_lic_licobs
                ?::integer,      -- 18 p_cec_id
                ?::integer,      -- 19 p_tlo_id
                ?,               -- 20 p_lcc_observacion
                ?,               -- 21 p_lcc_local
                ?,               -- 22 p_lca_descripcion
                ?,               -- 23 p_urbanizacion_id
                ?,               -- 24 p_lca_zonificacion
                ?,               -- 25 p_lic_giro
                ?::integer,      -- 26 p_lic_id_ori (ID de la licencia original)
                ?::boolean,      -- 27 p_lic_modidirecc
                ?,               -- 28 p_lic_horainicio (character)
                ?,               -- 29 p_lic_horafin (character)
                ?::integer,      -- 30 p_tir_id
                ?,               -- 31 p_lic_nota
                ?::bigint,       -- 32 p_usa_id
                ?::integer       -- 33 p_nir_id
            )";

            $parametros = [
                $data['fiu_id'] ?? null,                                           // 1  p_fiu_id
                $this->formatPostgresArray($girosIds),                             // 2  p_gir_id
                $this->formatPostgresArray($girosEspecificos, true),               // 3  p_lig_giroespecifico
                $data['tli_id'] ?? null,                                           // 4  p_tli_id
                $data['tes_id'] ?? null,                                           // 5  p_tes_id
                $data['per_idsolicitante'] ?? null,                                // 6  p_per_idsolicitante
                $data['per_idrazonsocial'] ?? null,                                // 7  p_per_idrazonsocial
                $data['lic_numlic'] ?? '',                                         // 8  p_lic_numlic
                $data['lic_codigopredial'] ?? '',                                  // 9  p_lic_codigopredial
                $data['lic_expnum'] ?? '',                                         // 10 p_lic_expnum
                (float) ($data['lic_area'] ?? 0),                                  // 11 p_lic_area
                ($data['lic_mype'] ?? false) === true || ($data['lic_mype'] ?? '') === '1', // 12 p_lic_mype
                $data['lic_resnum'] ?? '',                                         // 13 p_lic_resnum
                $this->formatDate($data['lic_fecharesolucion'] ?? null),          // 14 p_lic_fecharesolucion
                $this->formatDate($data['lic_fechaemision'] ?? null),             // 15 p_lic_fechaemision
                $this->formatDate($data['lic_fechavencimiento'] ?? null),         // 16 p_lic_fechavencimiento
                $data['lic_licobs'] ?? '',                                         // 17 p_lic_licobs
                $data['cec_id'] ?? 0,                                              // 18 p_cec_id
                $data['tlo_id'] ?? 0,                                              // 19 p_tlo_id
                $data['lcc_observacion'] ?? '',                                    // 20 p_lcc_observacion
                $data['lcc_local'] ?? '',                                          // 21 p_lcc_local
                $data['lca_descripcion'] ?? '',                                    // 22 p_lca_descripcion
                $data['urbanizacion_id'] ?? '',                                    // 23 p_urbanizacion_id
                $data['lca_zonificacion'] ?? '',                                   // 24 p_lca_zonificacion
                $data['lic_giro'] ?? '',                                           // 25 p_lic_giro
                $data['lic_id_ori'] ?? null,                                       // 26 p_lic_id_ori (ID original)
                ($data['lic_modidirecc'] ?? false) === true,                       // 27 p_lic_modidirecc
                $data['lic_horainicio'] ?? '09:00',
                // 28 p_lic_horainicio
                $data['lic_horafin'] ?? '18:00',                                   // 29 p_lic_horafin
                $data['tir_id'] ?? 2,                                              // 30 p_tir_id
                $data['lic_nota'] ?? '',                                           // 31 p_lic_nota
                auth()->id() ?? 0,                                                 // 32 p_usa_id
                $data['nir_id'] ?? null,                                           // 33 p_nir_id
            ];

            Log::info('Ejecutando spu_licencia_upd_transferir2', [
                'parametros' => $parametros,
                'lic_id_original' => $data['lic_id_ori'] ?? null,
                'usuario_id' => auth()->id()
            ]);

            // Ejecutar stored procedure

            $result = $this->db->select($sql, $parametros);

            // Validar que el SP retornó resultado
            if (empty($result)) {
                throw new \RuntimeException('El procedimiento almacenado no retornó ningún resultado');
            }

            $spResult = $result[0];

            // El SP retorna: TABLE(error integer, mensaje character varying)
            $error = $spResult->error ?? 0;
            $mensaje = $spResult->mensaje ?? 'Error desconocido';

            Log::info('Resultado del SP spu_licencia_upd_transferir2', [
                'error' => $error,
                'mensaje' => $mensaje
            ]);

            // Validar status del SP
            // Error codes:
            // > 0 = Success (retorna el nuevo lic_id)
            // -10 = Error al generar registro en Licencia
            // -20 = Error al registrar giros
            // -30 = Error al registrar catastro
            // -40 = Error al registrar centro comercial
            // -50 = Error al actualizar estado de licencia original
            // -60 = Error al generar historial de licencias
            // -70 = Error interno flujo comercial
            // -80 = Número de licencia ya existe
            // -90 = Registro ya existe (violación única)
            if ($error < 0) {
                throw new \RuntimeException(
                    "Error en el procedimiento almacenado: {$mensaje} (Código: {$error})"
                );
            }

            Log::info('Licencia transferida exitosamente', [
                'nuevo_lic_id' => $error,
                'mensaje' => $mensaje
            ]);

            return $result;

        } catch (\RuntimeException $e) {
            // Errores del stored procedure
            Log::error('Error del stored procedure al transferir licencia', [
                'error' => $e->getMessage(),
                'datos' => $data
            ]);
            throw $e;

        } catch (\Exception $e) {
            // Errores inesperados
            Log::error('Error inesperado al transferir licencia', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'datos' => $data
            ]);
            throw new \RuntimeException(
                'Error inesperado al transferir la licencia: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
}
