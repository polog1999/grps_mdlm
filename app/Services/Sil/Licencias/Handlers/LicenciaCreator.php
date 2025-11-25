<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;
use App\Services\Sil\Licencias\Concerns\PostgresHelpers;
use Illuminate\Support\Facades\Log;

class LicenciaCreator
{
    use PostgresHelpers;

    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function execute(array $datos)
    {
        try {
            // Extraer giros
            $girosIds = [];
            $girosEspecificos = [];
            
            if (isset($datos['licencias']['tabla_giros']) && is_array($datos['licencias']['tabla_giros'])) {
                $girosSeleccionados = $datos['licencias']['giros_seleccionar'] ?? [];
                
                foreach ($datos['licencias']['tabla_giros'] as $index => $giro) {
                    $girosIds[] = isset($girosSeleccionados[$index]) ? (int)$girosSeleccionados[$index] : 0;
                    $girosEspecificos[] = $giro['giro_especifico'] ?? '';
                }
            }

            $sql = "SELECT * FROM licencia.spu_licencia_ins4(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";

            $parametros = [
                $datos['catastro']['fiu_id'] ?? null, // pfiu_id
                $this->formatPostgresArray($girosIds), // giros_id
                $this->formatPostgresArray($girosEspecificos, true), // giros_especificos
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
                null, // fecha venc
                $datos['licencias']['observaciones'] ?? '', // licobs
                1, // pcec_id
                1, // ptlo_id
                '', // plcc_observacion
                '', // plcc_local
                $datos['licencias']['direccion'] ?? '', // plca_descripcion
                $datos['catastro']['descurb'] ?? '', // urbanizacion_id
                $datos['catastro']['zonificacion'] ?? '', // zonificación
                '', // plic_giro
                false, // modidirecc
                $datos['licencias']['hora_inicio'] ?? '09:00', // hora inicio
                $datos['licencias']['hora_fin'] ?? '18:00', // hora fin
                $datos['licencias']['tipo_resolucion'] ?? 2, // tir_id
                '', // nota
                auth()->id() ?? 0, // usa_id
                $datos['licencias']['compatibilidad'] ?? '', // compatibilidad
                $datos['licencias']['nir_id'] ?? 0, // nir_id
                0, // cin_id
                $this->formatDate($datos['expediente']['exp_fec'] ?? null), // expfec
                $datos['licencias']['nro_compatibilidad'] ?? '', // compatib_num
                $this->formatDate($datos['licencias']['fecha_compatibilidad'] ?? null) // compatib_fecha
            ];

            Log::info('Ejecutando spu_licencia_ins4 con parámetros:', $parametros);

            $result = $this->db->select($sql, $parametros);
            
            Log::info('Licencia insertada exitosamente', ['result' => $result]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error("Error al insertar licencia: " . $e->getMessage());
            throw $e;
        }
    }
}
