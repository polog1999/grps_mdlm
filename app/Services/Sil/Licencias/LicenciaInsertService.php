<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LicenciaInsertService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    /**
     * Ejecuta el procedimiento almacenado spu_licencia_ins4
     * 
     * @param array $datos Datos organizados del formulario
     * @return mixed
     */
    public function insertarLicencia(array $datos)
    {
        try {
            // Extraer giros
            $girosIds = [];
            $girosEspecificos = [];
            
            if (isset($datos['licencias']['tabla_giros']) && is_array($datos['licencias']['tabla_giros'])) {
                foreach ($datos['licencias']['tabla_giros'] as $giro) {
                    // Necesitarás obtener el ID del giro desde la descripción
                    // Por ahora lo dejamos como placeholder
                    $girosIds[] = $giro['gir_id'] ?? 0;
                    $girosEspecificos[] = $giro['giro_especifico'] ?? '';
                }
            }

            $sql = "SELECT * FROM licencia.spu_licencia_ins4(
                ?,  -- pfiu_id
                ?,  -- giros_id (ARRAY)
                ?,  -- giros_especificos (ARRAY)
                ?,  -- ptli_id
                ?,  -- ptes_id
                ?,  -- pper_idsolicitante
                ?,  -- pper_idrazonsocial
                ?,  -- plic_numlic
                ?,  -- plic_codigopredial
                ?,  -- plic_expnum
                ?,  -- area
                ?,  -- mype
                ?,  -- resnum
                ?,  -- fecha resol
                ?,  -- fecha emision
                ?,  -- fecha venc
                ?,  -- licobs
                ?,  -- pcec_id
                ?,  -- ptlo_id
                ?,  -- plcc_observacion
                ?,  -- plcc_local
                ?,  -- plca_descripcion
                ?,  -- urbanizacion_id
                ?,  -- zonificación
                ?,  -- plic_giro
                ?,  -- modidirecc
                ?,  -- hora inicio
                ?,  -- hora fin
                ?,  -- tir_id
                ?,  -- usa_id
                ?,  -- nota
                ?,  -- compatibilidad
                ?,  -- nir_id
                ?,  -- cin_id
                ?,  -- expfec
                ?,  -- compatib_num
                ?   -- compatib_fecha
            )";

            $parametros = [
                123, // pfiu_id - TODO: Obtener de algún lado
                '{' . implode(',', $girosIds) . '}', // giros_id como array PostgreSQL
                '{' . implode(',', array_map(fn($g) => '"' . $g . '"', $girosEspecificos)) . '}', // giros_especificos
                $datos['licencias']['tipo_licencia'] ?? null, // ptli_id
                $datos['licencias']['tipo_establecimientos'] ?? null, // ptes_id
                2000, // pper_idsolicitante - TODO: Obtener del usuario
                3040, // pper_idrazonsocial - TODO: Obtener de expediente
                $datos['licencias']['numero_licencia'] ?? null, // plic_numlic
                $datos['catastro']['codpredio'] ?? null, // plic_codigopredial
                $datos['expediente']['exp_num'] ?? null, // plic_expnum
                $datos['catastro']['area_economica'] ?? 0, // area
                $datos['licencias']['mype'] === '1', // mype (boolean)
                $datos['licencias']['n_resolucion'] ?? null, // resnum
                $datos['licencias']['fecha_resolucion'] ?? null, // fecha resol
                $datos['licencias']['fecha_emision'] ?? null, // fecha emision
                null, // fecha venc - TODO: Calcular
                $datos['licencias']['observaciones'] ?? '', // licobs
                1, // pcec_id - TODO: Determinar
                1, // ptlo_id - TODO: Determinar
                '', // plcc_observacion
                '', // plcc_local
                $datos['licencias']['direccion'] ?? '', // plca_descripcion
                $datos['catastro']['descurb'] ?? '', // urbanizacion_id
                $datos['catastro']['zonificacion'] ?? '', // zonificación
                '', // plic_giro - Concatenación de giros
                false, // modidirecc
                $datos['licencias']['hora_inicio'] ?? '9', // hora inicio
                $datos['licencias']['hora_fin'] ?? '18', // hora fin
                $datos['licencias']['tipo_resolucion'] ?? 2, // tir_id
                99, // usa_id - TODO: Obtener usuario actual
                '', // nota
                $datos['licencias']['compatibilidad'] ?? '', // compatibilidad
                $datos['licencias']['nir_id'] ?? 0, // nir_id
                0, // cin_id - TODO: Determinar
                $datos['expediente']['exp_fec'] ?? '', // expfec
                $datos['licencias']['nro_compatibilidad'] ?? '', // compatib_num
                $datos['licencias']['fecha_compatibilidad'] ?? '' // compatib_fecha
            ];

            $result = $this->connectionToPostgreSQL->select($sql, $parametros);
            
            Log::info('Licencia insertada exitosamente', ['result' => $result]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error("Error al insertar licencia: " . $e->getMessage());
            throw $e;
        }
    }
}
