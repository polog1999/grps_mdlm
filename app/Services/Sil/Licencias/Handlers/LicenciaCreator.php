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
            $girosIds = [];
            $girosEspecificos = [];
            $girosDescripciones = [];  // Para construir plic_giro

            if (isset($datos['licencias']['tabla_giros']) && is_array($datos['licencias']['tabla_giros'])) {
                $girosSeleccionados = $datos['licencias']['giros_seleccionar'] ?? [];

                foreach ($datos['licencias']['tabla_giros'] as $index => $giro) {
                    $girosIds[] = isset($girosSeleccionados[$index]) ? (int) $girosSeleccionados[$index] : 0;
                    $girosEspecificos[] = $giro['giro_especifico'] ?? '';

                    // Agregar la descripción del giro para plic_giro
                    if (!empty($giro['giro'])) {
                        $girosDescripciones[] = $giro['giro'];
                    }
                }
            }

            // Construir plic_giro como string concatenado con comas
            $plicGiro = !empty($girosDescripciones) ? implode(',', $girosDescripciones) : '';

            $sql = "SELECT * FROM licencia.spu_licencia_ins4(
                ?, ?::integer[], ?::text[], ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";

            $parametros = [
                $datos['catastro']['fiu_id'] ?? null, // 1 pfiu_id
                $this->formatPostgresArray($girosIds), // 2 giros_id
                $this->formatPostgresArray($girosEspecificos, true), // 3 giros_especificos
                $datos['licencias']['tipo_licencia'] ?? null, // 4 ptli_id
                $datos['licencias']['tipo_establecimientos'] ?? null, // 5 ptes_id
                $datos['expediente']['exp_nomrec_id'] ?? null, // 6 pper_idsolicitante
                $datos['expediente']['exp_razsoc_id'] ?? null, // 7 pper_idrazonsocial
                $datos['licencias']['numero_licencia'] ?? '', // 8 plic_numlic
                $datos['catastro']['codpredio'] ?? '', // 9 plic_codigopredial
                $datos['expediente']['exp_num'] ?? '', // 10 plic_expnum
                (float) ($datos['catastro']['area_economica'] ?? 0), // 11 plic_area
                (($datos['licencias']['mype'] ?? '0') === '1' || ($datos['licencias']['mype'] ?? '0') === 1 || ($datos['licencias']['mype'] ?? false) === true), // 12 plic_mype
                $datos['licencias']['n_resolucion'] ?? '', // 13 plic_resnum
                $this->formatDate($datos['licencias']['fecha_resolucion'] ?? null), // 14 p_lic_fecharesolucion
                $this->formatDate($datos['licencias']['fecha_emision'] ?? null), // 15 p_lic_fechaemision
                null, // 16 p_lic_fechavencimiento
                $datos['licencias']['observaciones'] ?? '', // 17 plic_licobs
                $datos['licencias']['centro_comercial'] ?? null, // 18 pcec_id
                $datos['licencias']['tipo_local'] ?? 0, // 19 ptlo_id
                $datos['licencias']['observaciones_local'] ?? '', // 20 plcc_observacion
                $datos['licencias']['local'] ?? '', // 21 plcc_local
                $datos['licencias']['direccion'] ?? '', // 22 plca_descripcion
                $datos['catastro']['descurb'] ?? '', // 23 urbanizacion_id
                $datos['catastro']['zonificacion'] ?? '', // 24 plca_zonificacion
                $plicGiro, // 25 plic_giro (concatenado con comas)
                false, // 26 p_lic_modidirecc
                $datos['licencias']['hora_inicio'] ?? '09:00', // 27 p_lic_horainicio
                $datos['licencias']['hora_fin'] ?? '18:00', // 28 p_lic_horafin
                $datos['licencias']['tipo_resolucion'] ?? 2, // 29 p_tir_id
                '', // 30 p_lic_nota
                0, // 31 p_usa_id
                $datos['licencias']['compatibilidad'] ?? '', // 32 p_compatibilidad
                $datos['licencias']['nir_id'] ?? 0, // 33 p_nir_id
                0, // 34 p_cin_id
                $this->formatDate($datos['expediente']['exp_fec'] ?? null), // 35 p_lic_expfec
                $datos['licencias']['nro_compatibilidad'] ?? '', // 36 p_lic_compatibilidadnumero
                $this->formatDate($datos['licencias']['fecha_compatibilidad'] ?? null) // 37 p_lic_compatibilidadfecha
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
