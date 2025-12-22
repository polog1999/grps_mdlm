<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;
use App\Services\Sil\Licencias\Concerns\PostgresHelpers;
use Illuminate\Support\Facades\Log;

class LicenciaDuplicator
{
    use PostgresHelpers;

    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Construye el resumen de giros para el campo lic_giro basado en la selección actual
     */
    protected function buildLicGiroSummary(array $data): string
    {
        if (!isset($data['giros']) || empty($data['giros'])) {
            return '';
        }

        $giroIds = [];
        foreach ($data['giros'] as $giro) {
            // Solo incluir giros que no están eliminados
            if (($giro['estado'] ?? 'I') !== 'E') {
                $giroIds[] = $giro['gir_id'] ?? 0;
            }
        }

        if (empty($giroIds)) {
            return '';
        }

        try {
            $giros = $this->db->table('licencia.giro')
                ->whereIn('gir_id', $giroIds)
                ->orderBy('gir_id')
                ->pluck('gir_descripcion')
                ->toArray();

            return implode(', ', $giros);
        } catch (\Exception $e) {
            Log::warning('No se pudo construir lic_giro para duplicación', [
                'giro_ids' => $giroIds,
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }

    /**
     * Ejecuta el procedimiento almacenado para duplicar una licencia
     * 
     * @param array $data Datos de la licencia a duplicar
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

            // SQL para llamar al stored procedure de duplicación
            // RETURNS SETOF resultado (error integer, mensaje text)
            $sql = "SELECT * FROM licencia.spu_licencia_upd_duplicar2(
                ?::integer,      -- 1  p_fiu_id
                ?::integer[],    -- 2  p_gir_id
                ?::text[],       -- 3  p_lig_giroespecifico
                ?::integer,      -- 4  p_tli_id
                ?::integer,      -- 5  p_tes_id
                ?::integer,      -- 6  p_per_idsolicitante
                ?::integer,      -- 7  p_per_idrazonsocial
                ?::varchar,      -- 8  p_lic_numlic
                ?::varchar,      -- 9  p_lic_codigopredial
                ?::varchar,      -- 10 p_lic_expnum
                ?::varchar,      -- 11 p_lic_expfec
                ?::varchar,      -- 12 p_lic_direccion
                ?::numeric,      -- 13 p_lic_area
                ?::boolean,      -- 14 p_lic_mype
                ?::varchar,      -- 15 p_lic_resnum
                ?::varchar,      -- 16 p_lic_fecharesolucion (character)
                ?::varchar,      -- 17 p_lic_fechaemision (character)
                ?::varchar,      -- 18 p_lic_fechavencimiento (character)
                ?::varchar,      -- 19 p_lic_licobs
                ?::integer,      -- 20 p_cec_id
                ?::integer,      -- 21 p_tlo_id
                ?::varchar,      -- 22 p_lcc_observacion
                ?::varchar,      -- 23 p_lcc_local
                ?::varchar,      -- 24 p_lca_descripcion
                ?::varchar,      -- 25 p_urbanizacion_id
                ?::varchar,      -- 26 p_lca_zonificacion
                ?::varchar,      -- 27 p_lic_giro
                ?::integer,      -- 28 p_lic_id_ori (ID de la licencia original)
                ?::boolean,      -- 29 p_lic_modidirecc
                ?::varchar,      -- 30 p_lic_horainicio (character)
                ?::varchar,      -- 31 p_lic_horafin (character)
                ?::integer,      -- 32 p_tir_id
                ?::text,         -- 33 p_lic_nota
                ?::bigint,       -- 34 p_usa_id
                ?::varchar,      -- 35 p_compatibilidad
                ?::varchar,      -- 36 p_compatibilidadnumero
                ?::varchar,      -- 37 p_compatibilidadfecha
                ?::integer       -- 38 p_nir_id
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
                $this->buildLicGiroSummary($data),                             // 27 p_lic_giro (rebuild from current)
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

            Log::info('Ejecutando spu_licencia_upd_duplicar2', [
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

            // El SP retorna: SETOF resultado (error integer, mensaje text)
            $error = $spResult->error ?? 0;
            $mensaje = $spResult->mensaje ?? 'Error desconocido';

            Log::info('Resultado del SP spu_licencia_upd_duplicar2', [
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
            // -50 = Error al actualizar licencia original
            // -60 = Error al registrar relación licencia-licencia
            // -70 = Error general
            // -80 = Número de licencia ya existe
            if ($error < 0) {
                throw new \RuntimeException(
                    "Error en el procedimiento almacenado: {$mensaje} (Código: {$error})"
                );
            }

            Log::info('Licencia duplicada exitosamente', [
                'nuevo_lic_id' => $error,
                'mensaje' => $mensaje
            ]);

            return $result;

        } catch (\RuntimeException $e) {
            // Errores del stored procedure
            Log::error('Error del stored procedure al duplicar licencia', [
                'error' => $e->getMessage(),
                'datos' => $data
            ]);
            throw $e;

        } catch (\Exception $e) {
            // Errores inesperados
            Log::error('Error inesperado al duplicar licencia', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'datos' => $data
            ]);
            throw new \RuntimeException(
                'Error inesperado al duplicar la licencia: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
}
