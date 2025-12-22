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
     * Obtiene el valor actual de lic_giro para preservarlo durante la transferencia
     */
    protected function getCurrentLicGiro(?int $licId): string
    {
        if (!$licId) {
            return '';
        }

        try {
            $result = $this->db->table('licencia.licencia')
                ->where('lic_id', $licId)
                ->value('lic_giro');

            return $result ?? '';
        } catch (\Exception $e) {
            Log::warning('No se pudo obtener lic_giro actual para transferencia', [
                'lic_id' => $licId,
                'error' => $e->getMessage()
            ]);
            return '';
        }
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
                    $girosEspecificos[] = $giro['giro_especifico'] ?? '';
                }
            }

            // SQL para llamar al stored procedure de transferencia
            // RETURNS TABLE(error integer, mensaje character varying)
            $sql = "SELECT * FROM licencia.spu_licencia_upd_transferir2(
                ?::integer,     -- 1
                ?::integer[],   -- 2
                ?::text[],      -- 3
                ?::integer,     -- 4
                ?::integer,     -- 5
                ?::integer,     -- 6
                ?::integer,     -- 7
                ?::varchar,     -- 8
                ?::varchar,     -- 9
                ?::varchar,     -- 10
                ?::varchar,     -- 11
                ?::varchar,     -- 12 p_lic_direccion
                ?::numeric,     -- 13
                ?::boolean,     -- 14
                ?::varchar,     -- 15
                ?::varchar,     -- 16
                ?::varchar,     -- 17
                ?::varchar,     -- 18
                ?::varchar,     -- 19
                ?::integer,     -- 20
                ?::integer,     -- 21
                ?::varchar,     -- 22
                ?::varchar,     -- 23
                ?::varchar,     -- 24
                ?::varchar,     -- 25 (Urbanización como texto)
                ?::varchar,     -- 26
                ?::varchar,     -- 27
                ?::integer,     -- 28
                ?::boolean,     -- 29 (p_lic_modidirecc)
                ?::varchar,     -- 30
                ?::varchar,     -- 31
                ?::integer,     -- 32
                ?::text,        -- 33
                ?::bigint,      -- 34
                ?::varchar,     -- 35 p_compatibilidad
                ?::varchar,     -- 36 p_compatibilidadnumero
                ?::varchar,     -- 37 p_compatibilidadfecha
                ?::integer      -- 38
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
                $data['lic_expnum'] ?? '',                                          // 10 p_lic_expnum
                $this->formatDate($data['lic_expfec'] ?? null),                    // 11 p_lic_expfec
                $data['lic_direccion'] ?? '',                                      // 12 p_lic_direccion
                (float) ($data['lic_area'] ?? 0),                                  // 13 p_lic_area
                ($data['lic_mype'] ?? false) === true || ($data['lic_mype'] ?? '') === '1', // 14 p_lic_mype
                $data['lic_resnum'] ?? '',                                         // 15 p_lic_resnum
                $this->formatDate($data['lic_fecharesolucion'] ?? null),          // 16 p_lic_fecharesolucion
                $this->formatDate($data['lic_fechaemision'] ?? null),             // 17 p_lic_fechaemision
                $this->formatDate($data['lic_fechavencimiento'] ?? null),         // 18 p_lic_fechavencimiento
                $data['lic_licobs'] ?? '',                                         // 19 p_lic_licobs
                $data['cec_id'] ?? 0,                                              // 20 p_cec_id
                $data['tlo_id'] ?? 0,                                              // 21 p_tlo_id
                $data['lcc_observacion'] ?? '',                                    // 22 p_lcc_observacion
                $data['lcc_local'] ?? '',                                          // 23 p_lcc_local
                $data['lca_descripcion'] ?? '',                                    // 24 p_lca_descripcion
                $data['urbanizacion_id'] ?? '',                                    // 25 p_urbanizacion_id
                $data['lca_zonificacion'] ?? '',                                   // 26 p_lca_zonificacion
                $this->getCurrentLicGiro($data['lic_id_ori'] ?? null),        // 27 p_lic_giro (preserve from original)
                $data['lic_id_ori'] ?? null,                                       // 28 p_lic_id_ori (ID original)
                ($data['lic_modidirecc'] ?? false) === true,                       // 29 p_lic_modidirecc
                $data['lic_horainicio'] ?? '09:00',                                // 30 p_lic_horainicio
                $data['lic_horafin'] ?? '18:00',                                   // 31 p_lic_horafin
                $data['tir_id'] ?? 2,                                              // 32 p_tir_id
                $data['lic_nota'] ?? '',                                           // 33 p_lic_nota
                auth()->id() ?? 0,                                                 // 34 p_usa_id
                $data['lic_compatibilidad'] ?? '',                                 // 35 p_compatibilidad
                $data['lic_compatibilidadnumero'] ?? '',                          // 36 p_compatibilidadnumero
                $this->formatDate($data['lic_compatibilidadfecha'] ?? null),      // 37 p_compatibilidadfecha
                $data['nir_id'] ?? null,                                           // 38 p_nir_id
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
