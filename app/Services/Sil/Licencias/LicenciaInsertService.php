<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
            
            // Lógica para extraer IDs de giros y específicos
            // Se asume que $datos['licencias']['giros_seleccionar'] contiene los IDs seleccionados
            // y $datos['licencias']['tabla_giros'] contiene los detalles (incluyendo giro_especifico)
            
            if (isset($datos['licencias']['tabla_giros']) && is_array($datos['licencias']['tabla_giros'])) {
                $girosSeleccionados = $datos['licencias']['giros_seleccionar'] ?? [];
                
                foreach ($datos['licencias']['tabla_giros'] as $index => $giro) {
                    // Intentar obtener el ID del giro de la selección múltiple usando el índice
                    // Esto asume que el orden en tabla_giros corresponde al orden en giros_seleccionar
                    // Una mejor aproximación sería si tabla_giros tuviera el ID del giro, pero el repeater a veces pierde contexto
                    // Usaremos la lógica del controlador:
                    $girosIds[] = isset($girosSeleccionados[$index]) ? (int)$girosSeleccionados[$index] : 0;
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
                $datos['catastro']['fiu_id'] ?? null, // pfiu_id
                '{' . implode(',', $girosIds) . '}', // giros_id como array PostgreSQL
                '{' . implode(',', array_map(fn($g) => '"' . str_replace('"', '\"', $g) . '"', $girosEspecificos)) . '}', // giros_especificos
                $datos['licencias']['tipo_licencia'] ?? null, // ptli_id
                $datos['licencias']['tipo_establecimientos'] ?? null, // ptes_id
                $datos['expediente']['exp_nomrec_id'] ?? null, // pper_idsolicitante
                $datos['expediente']['exp_razsoc_id'] ?? null, // pper_idrazonsocial
                $datos['licencias']['numero_licencia'] ?? '', // plic_numlic
                $datos['catastro']['codpredio'] ?? '', // plic_codigopredial
                $datos['expediente']['exp_num'] ?? '', // plic_expnum
                (float)($datos['catastro']['area_economica'] ?? 0), // area
                ($datos['licencias']['mype'] ?? '0') === '1', // mype (boolean)
                $datos['licencias']['n_resolucion'] ?? '', // resnum
                $this->formatDate($datos['licencias']['fecha_resolucion'] ?? null), // fecha resol
                $this->formatDate($datos['licencias']['fecha_emision'] ?? null), // fecha emision
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
                $datos['licencias']['hora_inicio'] ?? '09:00', // hora inicio
                $datos['licencias']['hora_fin'] ?? '18:00', // hora fin
                $datos['licencias']['tipo_resolucion'] ?? 2, // tir_id
                '', // nota
                auth()->id() ?? 0, // usa_id
                $datos['licencias']['compatibilidad'] ?? '', // compatibilidad
                $datos['licencias']['nir_id'] ?? 0, // nir_id
                0, // cin_id - TODO: Determinar
                $this->formatDate($datos['expediente']['exp_fec'] ?? null), // expfec
                $datos['licencias']['nro_compatibilidad'] ?? '', // compatib_num
                $this->formatDate($datos['licencias']['fecha_compatibilidad'] ?? null) // compatib_fecha
            ];

            // Log para depuración
            Log::info('Ejecutando spu_licencia_ins4 con parámetros:', $parametros);

            $result = $this->connectionToPostgreSQL->select($sql, $parametros);
            
            Log::info('Licencia insertada exitosamente', ['result' => $result]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error("Error al insertar licencia: " . $e->getMessage());
            throw $e;
        }
    }

    private function formatDate($date): ?string
    {
        if (empty($date)) {
            return null;
        }
        
        try {
            return Carbon::parse($date)->format('d/m/Y');
        } catch (\Exception $e) {
            return null;
        }
    }
}
