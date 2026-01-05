<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;
use App\Services\Sil\Licencias\Concerns\PostgresHelpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
                :p_fiu_id::integer,
                :p_gir_id::integer[],
                :p_lig_giroespecifico::text[],
                :p_tli_id::integer,
                :p_tes_id::integer,
                :p_per_idsolicitante::integer,
                :p_per_idrazonsocial::integer,
                :p_lic_numlic::varchar,
                :p_lic_codigopredial::varchar,
                :p_lic_expnum::varchar,
                :p_lic_expfec::varchar,
                :p_lic_direccion::varchar,
                :p_lic_area::numeric,
                :p_lic_mype::boolean,
                :p_lic_resnum::varchar,
                :p_lic_fecharesolucion::varchar,
                :p_lic_fechaemision::varchar,
                :p_lic_fechavencimiento::varchar,
                :p_lic_licobs::varchar,
                :p_cec_id::integer,
                :p_tlo_id::integer,
                :p_lcc_observacion::varchar,
                :p_lcc_local::varchar,
                :p_lca_descripcion::varchar,
                :p_urbanizacion_id::varchar,
                :p_lca_zonificacion::varchar,
                :p_lic_giro::varchar,
                :p_lic_id_ori::integer,
                :p_lic_modidirecc::boolean,
                :p_lic_horainicio::varchar,
                :p_lic_horafin::varchar,
                :p_tir_id::integer,
                :p_lic_nota::text,
                :p_usa_id::bigint,
                :p_compatibilidad::varchar,
                :p_compatibilidadnumero::varchar,
                :p_compatibilidadfecha::varchar,
                :p_nir_id::integer,
                :p_user_id::integer,
                :p_fecha_operacion::timestamp
            )";

            $parametros = [
                'p_fiu_id' => $data['fiu_id'] ?? null,
                'p_gir_id' => $this->formatPostgresArray($girosIds),
                'p_lig_giroespecifico' => $this->formatPostgresArray($girosEspecificos, true),
                'p_tli_id' => $data['tli_id'] ?? null,
                'p_tes_id' => $data['tes_id'] ?? null,
                'p_per_idsolicitante' => $data['per_idsolicitante'] ?? null,
                'p_per_idrazonsocial' => $data['per_idrazonsocial'] ?? null,
                'p_lic_numlic' => $data['lic_numlic'] ?? '',
                'p_lic_codigopredial' => $data['lic_codigopredial'] ?? '',
                'p_lic_expnum' => $data['lic_expnum'] ?? '',
                'p_lic_expfec' => $this->formatDate($data['lic_expfec'] ?? null),
                'p_lic_direccion' => $data['lic_direccion'] ?? '',
                'p_lic_area' => (float) ($data['lic_area'] ?? 0),
                'p_lic_mype' => ($data['lic_mype'] ?? false) === true || ($data['lic_mype'] ?? '') === '1',
                'p_lic_resnum' => $data['lic_resnum'] ?? '',
                'p_lic_fecharesolucion' => $this->formatDate($data['lic_fecharesolucion'] ?? null),
                'p_lic_fechaemision' => $this->formatDate($data['lic_fechaemision'] ?? null),
                'p_lic_fechavencimiento' => $this->formatDate($data['lic_fechavencimiento'] ?? null),
                'p_lic_licobs' => $data['lic_licobs'] ?? '',
                'p_cec_id' => $data['cec_id'] ?? 0,
                'p_tlo_id' => $data['tlo_id'] ?? 0,
                'p_lcc_observacion' => $data['lcc_observacion'] ?? '',
                'p_lcc_local' => $data['lcc_local'] ?? '',
                'p_lca_descripcion' => $data['lca_descripcion'] ?? '',
                'p_urbanizacion_id' => $data['urbanizacion_id'] ?? '',
                'p_lca_zonificacion' => $data['lca_zonificacion'] ?? '',
                'p_lic_giro' => $this->buildLicGiroSummary($data),
                'p_lic_id_ori' => $data['lic_id_ori'] ?? null,
                'p_lic_modidirecc' => ($data['lic_modidirecc'] ?? false) === true,
                'p_lic_horainicio' => $data['lic_horainicio'] ?? '09:00',
                'p_lic_horafin' => $data['lic_horafin'] ?? '18:00',
                'p_tir_id' => $data['tir_id'] ?? 2,
                'p_lic_nota' => $data['lic_nota'] ?? '',
                'p_usa_id' => auth()->id() ?? 0,
                'p_compatibilidad' => $data['lic_compatibilidad'] ?? '',
                'p_compatibilidadnumero' => $data['lic_compatibilidadnumero'] ?? '',
                'p_compatibilidadfecha' => $this->formatDate($data['lic_compatibilidadfecha'] ?? null),
                'p_nir_id' => $data['nir_id'] ?? null,
                'p_user_id' => Auth::id() ?? 0,
                'p_fecha_operacion' => now()->format('Y-m-d H:i:s'),
            ];

            Log::info('Ejecutando spu_licencia_upd_duplicar2', [
                'parametros' => $parametros,
                'lic_id_original' => $data['lic_id_ori'] ?? null,
                'usuario_id' => Auth::id(),
                'usuario_name' => Auth::user()?->name ?? 'N/A'
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
