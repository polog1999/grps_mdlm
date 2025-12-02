<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;
use App\Services\Sil\Licencias\Concerns\PostgresHelpers;

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
        // 1. Preparar Arrays de Giros
        $arrGirId = [];
        $arrEspecifico = [];
        $arrLigId = [];
        $arrEstado = [];

        if (!empty($data['giros'])) {
            foreach ($data['giros'] as $giro) {
                $arrGirId[] = $giro['gir_id'];
                // Escapamos comillas dobles para el array de texto de Postgres
                // La logica original usaba str_replace manual, aqui usamos el helper si fuera necesario
                // Pero el helper formatPostgresArray ya maneja el escaping si le pasamos isText=true
                // Sin embargo, la logica original hacia un str_replace especifico antes.
                // Usaremos los arrays crudos y dejaremos que formatPostgresArray lo maneje.
                $arrEspecifico[] = $giro['especifico'];
                $arrLigId[] = $giro['lig_id'];
                $arrEstado[] = $giro['estado'];
            }
        }

        // 2. Ejecutar SP
        // Nota: Como devuelve SETOF resultado, usamos select()
        // Usamos spu_licencia_upd3 como en el servicio original
        $sql = "SELECT * FROM licencia.spu_licencia_upd3(
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
            ?::int[], ?::text[], ?::int[], ?::text[], 
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $params = [
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

            // Arrays formateados usando el helper
            $this->formatPostgresArray($arrGirId),
            $this->formatPostgresArray($arrEspecifico, true),
            $this->formatPostgresArray($arrLigId),
            $this->formatPostgresArray($arrEstado, true),

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
        ];

        return $this->db->select($sql, $params);
    }
}
