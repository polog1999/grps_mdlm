<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LicenciaUpdateService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function obtenerPorIdLicencia($lic_id)
    {
         return $this->connectionToPostgreSQL
        ->table('licencia.vu_licencia')
        ->select("*")
        ->where('lic_id', $lic_id)
        ->get();
    }
    public function actualizarLicencia($data)
    {
        // 1. Preparar Arrays de Giros
        // Suponemos que $data['giros'] es un array de objetos desde el frontend
        // Estructura esperada de $data['giros']:
        // [
        //    ['gir_id' => 10, 'especifico' => 'ABC', 'lig_id' => 50, 'estado' => 'M'],
        //    ['gir_id' => 20, 'especifico' => 'XYZ', 'lig_id' => 0,  'estado' => 'I'],
        // ]
        
        $arrGirId = [];
        $arrEspecifico = [];
        $arrLigId = [];
        $arrEstado = [];

        foreach ($data['giros'] as $giro) {
            $arrGirId[] = $giro['gir_id'];
            // Escapamos comillas dobles para el array de texto de Postgres
            $spec = str_replace('"', '\\"', $giro['especifico']);
            $arrEspecifico[] = '"' . $spec . '"'; 
            $arrLigId[] = $giro['lig_id'];
            $arrEstado[] = $giro['estado']; // 'I', 'M', 'E'
        }

        // Convertir a string formato Postgres: "{1,2,3}"
        $strGirId = "{" . implode(',', $arrGirId) . "}";
        $strEspecifico = "{" . implode(',', $arrEspecifico) . "}";
        $strLigId = "{" . implode(',', $arrLigId) . "}";
        $strEstado = "{" . implode(',', $arrEstado) . "}"; // Ej: "{M,I,E}"

        // 2. Ejecutar SP
        // Nota: Como devuelve SETOF resultado, usamos select()
        $result = DB::connection('pgsql_licencias')->select(
            "SELECT * FROM licencia.spu_licencia_upd3(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                ?::int[], ?::text[], ?::int[], ?::text[], 
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )",
            [
                $data['lic_id'],
                $data['tli_id'],
                $data['tes_id'],
                $data['per_idsolicitante'],
                $data['per_idrazonsocial'],
                $data['lic_numlic'],
                $data['lic_codigopredial'],
                $data['lic_expnum'],
                $data['lic_direccion'],
                $data['lic_urbanizacion'],
                $data['lic_area'],
                $data['lic_mype'] ? 'true' : 'false',
                $data['lic_resnum'],
                $data['lic_fecharesolucion'],   // String 'DD/MM/YYYY'
                $data['lic_fechaemision'],      // String 'DD/MM/YYYY'
                $data['lic_fechavencimiento'],  // String 'DD/MM/YYYY' o null
                $data['lic_licobs'],
                $data['lic_giro'],
                $data['fiu_id'],
                $data['lca_descripcion'],
                $data['lca_urbanizacion'],
                $data['lca_zonificacion'],
                $data['lca_origen'],
                $data['cec_id'] ?? 0,
                $data['tlo_id'] ?? 0,
                $data['lcc_observacion'] ?? '',
                $data['lcc_local'] ?? '',
                
                // Arrays formateados
                $strGirId,
                $strEspecifico,
                $strLigId,
                $strEstado,
                
                $data['lic_modidirecc'] ? 'true' : 'false',
                $data['lic_horainicio'],
                $data['lic_horafin'],
                $data['tir_id'],
                $data['lic_nota'],
                auth()->id(), // usa_id
                $data['compatibilidad'],
                $data['rsgparrafo1'] ?? '',
                $data['rsgparrafo2'] ?? '',
                $data['nir_id'] ?? 0
            ]
        );

        return $result[0] ?? null;
    }
}