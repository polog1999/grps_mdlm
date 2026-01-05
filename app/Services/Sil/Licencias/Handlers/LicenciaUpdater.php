<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;
use App\Services\Sil\Licencias\Concerns\PostgresHelpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LicenciaUpdater
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
            Log::warning('No se pudo construir lic_giro', [
                'giro_ids' => $giroIds,
                'error' => $e->getMessage()
            ]);
            return '';
        }
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
                :p_lic_id::integer,
                :p_tli_id::integer,
                :p_tes_id::integer,
                :p_per_idsolicitante::integer,
                :p_per_idrazonsocial::integer,
                :p_lic_numlic,
                :p_lic_codigopredial,
                :p_lic_expnum,
                :p_lic_expfec,
                :p_lic_direccion,
                :p_lic_urbanizacion,
                :p_lic_area::numeric,
                :p_lic_mype::boolean,
                :p_lic_resnum,
                :p_lic_fecharesolucion,
                :p_lic_fechaemision,
                :p_lic_fechavencimiento,
                :p_lic_licobs,
                :p_lic_giro,
                :p_fiu_id::integer,
                :p_lca_descripcion,
                :p_lca_urbanizacion,
                :p_lca_zonificacion,
                :p_lca_origen,
                :p_cec_id::integer,
                :p_tlo_id::integer,
                :p_lcc_observacion,
                :p_lcc_local,
                :p_gir_id::integer[],
                :p_lig_giroespecifico::text[],
                :p_lig_id::integer[],
                :p_lig_estado::text[],
                :p_lic_modidirecc::boolean,
                :p_lic_horainicio,
                :p_lic_horafin,
                :p_tir_id::integer,
                :p_lic_nota,
                :p_usa_id::bigint,
                :p_compatibilidad,
                :p_lic_compatibilidadnumero,
                :p_lic_compatibilidadfecha,
                :p_rsgparrafo1,
                :p_rsgparrafo2,
                :p_nir_id::integer,
                :p_user_id::integer,
                :p_fecha_operacion::timestamp
            )";

            $parametros = [
                'p_lic_id' => $data['lic_id'] ?? null,
                'p_tli_id' => $data['tli_id'] ?? null,
                'p_tes_id' => $data['tes_id'] ?? null,
                'p_per_idsolicitante' => $data['per_idsolicitante'] ?? null,
                'p_per_idrazonsocial' => $data['per_idrazonsocial'] ?? null,
                'p_lic_numlic' => $data['lic_numlic'] ?? '',
                'p_lic_codigopredial' => $data['lic_codigopredial'] ?? '',
                'p_lic_expnum' => $data['lic_expnum'] ?? '',
                'p_lic_expfec' => $this->formatDate($data['lic_expfec'] ?? null),
                'p_lic_direccion' => $data['lic_direccion'] ?? '',
                'p_lic_urbanizacion' => $data['lic_urbanizacion'] ?? '',
                'p_lic_area' => (float) ($data['lic_area'] ?? 0),
                'p_lic_mype' => ($data['lic_mype'] ?? false) === true || ($data['lic_mype'] ?? '') === '1',
                'p_lic_resnum' => $data['lic_resnum'] ?? '',
                'p_lic_fecharesolucion' => $this->formatDate($data['lic_fecharesolucion'] ?? null),
                'p_lic_fechaemision' => $this->formatDate($data['lic_fechaemision'] ?? null),
                'p_lic_fechavencimiento' => $this->formatDate($data['lic_fechavencimiento'] ?? null),
                'p_lic_licobs' => $data['lic_licobs'] ?? '',
                'p_lic_giro' => $this->buildLicGiroSummary($data),
                'p_fiu_id' => $data['fiu_id'] ?? null,
                'p_lca_descripcion' => $data['lca_descripcion'] ?? '',
                'p_lca_urbanizacion' => $data['lca_urbanizacion'] ?? '',
                'p_lca_zonificacion' => $data['lca_zonificacion'] ?? '',
                'p_lca_origen' => $data['lca_origen'] ?? '',
                'p_cec_id' => $data['cec_id'] ?? null,
                'p_tlo_id' => $data['tlo_id'] ?? null,
                'p_lcc_observacion' => $data['lcc_observacion'] ?? '',
                'p_lcc_local' => $data['lcc_local'] ?? '',
                'p_gir_id' => $this->formatPostgresArray($girosIds),
                'p_lig_giroespecifico' => $this->formatPostgresArray($girosEspecificos, true),
                'p_lig_id' => $this->formatPostgresArray($ligIds),
                'p_lig_estado' => $this->formatPostgresArray($ligEstados, true),
                'p_lic_modidirecc' => ($data['lic_modidirecc'] ?? false) === true,
                'p_lic_horainicio' => $data['lic_horainicio'] ?? '09:00',
                'p_lic_horafin' => $data['lic_horafin'] ?? '18:00',
                'p_tir_id' => $data['tir_id'] ?? 2,
                'p_lic_nota' => $data['lic_nota'] ?? '',
                'p_usa_id' => auth()->id() ?? 0,
                'p_compatibilidad' => $data['lic_compatibilidad'] ?? '',
                'p_lic_compatibilidadnumero' => $data['lic_compatibilidadnumero'] ?? '',
                'p_lic_compatibilidadfecha' => $this->formatDate($data['lic_compatibilidadfecha'] ?? null),
                'p_rsgparrafo1' => $data['rsgparrafo1'] ?? '',
                'p_rsgparrafo2' => $data['rsgparrafo2'] ?? '',
                'p_nir_id' => $data['nir_id'] ?? null,
                'p_user_id' => Auth::id() ?? 0,
                'p_fecha_operacion' => now()->format('Y-m-d H:i:s'),
            ];

            Log::info('Datos de entrada para actualización (data):', $data);
            Log::info('Ejecutando spu_licencia_upd3 con parámetros:', [
                'parametros' => $parametros,
                'usuario_id' => Auth::id(),
                'usuario_name' => Auth::user()?->name ?? 'N/A'
            ]);

            $result = $this->db->select($sql, $parametros);

            Log::info('Licencia actualizada exitosamente', ['result' => $result]);

            return $result;
        } catch (\Exception $e) {
            Log::error("Error al actualizar licencia: " . $e->getMessage());
            throw $e;
        }
    }
}
