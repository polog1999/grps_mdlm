<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;
use App\Services\Sil\Licencias\Concerns\PostgresHelpers;
use Illuminate\Support\Facades\Log;

class LicenciaUpdater
{
    use PostgresHelpers;

    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function execute(array $data)
    {
        try {
            // Procesar giros si existen
            $girosIds = [];
            $girosEspecificos = [];
            $ligIds = [];
            $ligEstados = [];

            if (isset($data['giros']) && is_array($data['giros'])) {
                foreach ($data['giros'] as $giro) {
                    $girosIds[] = $giro['gir_id'] ?? 0;
                    $girosEspecificos[] = $giro['giro_especifico'] ?? '';
                    $ligIds[] = $giro['lig_id'] ?? 0;
                    $ligEstados[] = $giro['estado'] ?? 'A';
                }
            }

            $sql = "SELECT licencia.spu_licencia_upd3(
                ?::integer,
                ?::integer,
                ?::integer,
                ?::integer,
                ?::integer,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?::numeric,
                ?::boolean,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?::integer,
                ?,
                ?,
                ?,
                ?,
                ?::integer,
                ?::integer,
                ?,
                ?,
                ?::integer[],
                ?::text[],
                ?::integer[],
                ?::text[],
                ?::boolean,
                ?,
                ?,
                ?::integer,
                ?,
                ?::bigint,
                ?,
                ?,
                ?,
                ?::integer
            )";

            $parametros = [
                $data['lic_id'] ?? null,                                           // 1 p_lic_id
                $data['tli_id'] ?? null,                                           // 2 p_tli_id
                $data['tes_id'] ?? null,                                           // 3 p_tes_id
                $data['per_idsolicitante'] ?? null,                                // 4 p_per_idsolicitante
                $data['per_idrazonsocial'] ?? null,                                // 5 p_per_idrazonsocial
                $data['lic_numlic'] ?? '',                                         // 6 p_lic_numlic
                $data['lic_codigopredial'] ?? '',                                  // 7 p_lic_codigopredial
                $data['lic_expnum'] ?? '',                                         // 8 p_lic_expnum
                $data['lic_direccion'] ?? '',                                      // 9 p_lic_direccion
                $data['lic_urbanizacion'] ?? '',                                   // 10 p_lic_urbanizacion
                (float) ($data['lic_area'] ?? 0),                                  // 11 p_lic_area
                ($data['lic_mype'] ?? false) === true || ($data['lic_mype'] ?? '') === '1', // 12 p_lic_mype
                $data['lic_resnum'] ?? '',                                         // 13 p_lic_resnum
                $this->formatDate($data['lic_fecharesolucion'] ?? null),          // 14 p_lic_fecharesolucion
                $this->formatDate($data['lic_fechaemision'] ?? null),             // 15 p_lic_fechaemision
                $this->formatDate($data['lic_fechavencimiento'] ?? null),         // 16 p_lic_fechavencimiento
                $data['lic_licobs'] ?? '',                                         // 17 p_lic_licobs
                $data['lic_giro'] ?? '',                                           // 18 p_lic_giro
                $data['fiu_id'] ?? null,                                           // 19 p_fiu_id
                $data['lca_descripcion'] ?? '',                                    // 20 p_lca_descripcion
                $data['lca_urbanizacion'] ?? '',                                   // 21 p_lca_urbanizacion
                $data['lca_zonificacion'] ?? '',                                   // 22 p_lca_zonificacion
                $data['lca_origen'] ?? '',                                         // 23 p_lca_origen
                $data['cec_id'] ?? null,                                           // 24 p_cec_id
                $data['tlo_id'] ?? null,                                           // 25 p_tlo_id
                $data['lcc_observacion'] ?? '',                                    // 26 p_lcc_observacion
                $data['lcc_local'] ?? '',                                          // 27 p_lcc_local
                $this->formatPostgresArray($girosIds),                             // 28 p_gir_id
                $this->formatPostgresArray($girosEspecificos, true),               // 29 p_lig_giroespecifico
                $this->formatPostgresArray($ligIds),                               // 30 p_lig_id
                $this->formatPostgresArray($ligEstados, true),                     // 31 p_lig_estado
                ($data['lic_modidirecc'] ?? false) === true,                       // 32 p_lic_modidirecc
                $data['lic_horainicio'] ?? '09:00',                                // 33 p_lic_horainicio
                $data['lic_horafin'] ?? '18:00',                                   // 34 p_lic_horafin
                $data['tir_id'] ?? 2,                                              // 35 p_tir_id
                $data['lic_nota'] ?? '',                                           // 36 p_lic_nota
                auth()->id() ?? 0,                                                 // 37 p_usa_id
                $data['compatibilidad'] ?? '',                                     // 38 p_compatibilidad
                $data['rsgparrafo1'] ?? '',                                        // 39 p_rsgparrafo1
                $data['rsgparrafo2'] ?? '',                                        // 40 p_rsgparrafo2
                $data['nir_id'] ?? null,                                           // 41 p_nir_id
            ];

            Log::info('Ejecutando spu_licencia_upd3 con parámetros:', $parametros);

            $result = $this->db->select($sql, $parametros);

            Log::info('Licencia actualizada exitosamente', ['result' => $result]);

            return $result;
        } catch (\Exception $e) {
            Log::error("Error al actualizar licencia: " . $e->getMessage());
            throw $e;
        }
    }
}
