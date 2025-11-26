<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;

class LicenciaReader
{
    protected $db;
    protected $db2;

    public function __construct(ConnectionInterface $db, ConnectionInterface $db2)
    {
        $this->db = $db;
        $this->db2 = $db2;
    }

    public function findById($licId)
    {
         return $this->db
        ->table('licencia.vu_licencia')
        ->select("*")
        ->where('lic_id', $licId)
        ->get()
        ->first();
    }
    
    public function obtenerDatosDeRazonSocialPorExpediente($expnum){
        $exp= $this->db2
            ->table('DS_VALORES.DUR_EXPEDIENTE')
            ->select('exp_codcon')
            ->where('EXP_NUM', $expnum)
            ->first();
        //VALIDAR SI EXP_CODCON ES NULL
        if(!$exp || $exp->exp_codcon == null){
            return null;
        }
        //ASIGNAR VALOR DE EXP_CODCON A COD_CON
        $cod_con = $exp->exp_codcon;

        //OBTENER DATOS DE LA PERSONA/RAZON SOCIAL DE LA TABLA VU_PERSONA2
        $datos_persona = $this->db2
            ->table('DS_VALORES.VU_PERSONA2')
            ->select('nomcom','domfis','numtel','correo')
            ->where('CODCON', $cod_con)
            ->get()
            ->first();
        return $datos_persona;
    }

    public function obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat){
        $sql = "
            SELECT                     
                TRIM(t.codpredio) AS codpredio,
                TRIM(t.descurb) AS descurb,
                TRIM(t.codvia) AS codvia,
                TRIM(t.numvia) AS numvia,
                TRIM(t.intdpto) AS intdpto,
                TRIM(t.blockedif) AS blockedif,
                TRIM(t.mz) AS mz,
                TRIM(t.lote) AS lote,
                TRIM(t.zonificacion) AS zonificacion,
                TRIM(TO_CHAR(t.area_economica, 'FM9999999990.00')) AS area_economica
            FROM TABLE(MESQUECHE.FU_SYSCATFICHAUBICACION_SEL(?, '')) t
        ";

        $datos_catastro = $this->db2->select($sql, [$codcat]);

        if (empty($datos_catastro)) {
            return [];
        }

        // ----------------------------------------------------------
        // 1) AGREGAR VIA COMPLETA
        // ----------------------------------------------------------
        $sql_db_via = "
            SELECT 
                (vt.vit_abretipvia || ' ' || v.via_descvia) AS via_completa
            FROM syscat.via v
            LEFT JOIN syscat.viatipo vt 
                ON v.via_codtipvia = vt.vit_codtipvia
            WHERE v.via_codvia = ?
        ";

        foreach ($datos_catastro as $item) {

            // Obtener la vía completa
            $via = $this->db->select($sql_db_via, [$item->codvia]);
            $item->via_completa = $via[0]->via_completa ?? null;
        }

        // ----------------------------------------------------------
        // 2) BUSCAR fiu_id comparando codvia y numvia en PostgreSQL
        // ----------------------------------------------------------
        $sql_fiu = "
            SELECT fiu_id, fiu_numvia
            FROM syscat.fichaubicacion
            WHERE fiu_codvia = ?
        ";

        foreach ($datos_catastro as $item) {

            $codvia = $item->codvia;
            $numvia = trim($item->numvia);

            // Buscar todos los registros por codvia
            $fichas = $this->db->select($sql_fiu, [$codvia]);

            $fiu_id_encontrado = null;

            foreach ($fichas as $f) {

                // Comparación estricta entre texto del JSON y BD
                if (trim($f->fiu_numvia) === $numvia) {
                    $fiu_id_encontrado = $f->fiu_id;
                    break;
                }
            }

            // Agregar resultado
            $item->fiu_id = $fiu_id_encontrado;
        }

        // ----------------------------------------------------------
        // 3) FILTRAR DUPLICADOS (mismo registro, distinta puerta)
        // ----------------------------------------------------------

        $grupos = [];

        foreach ($datos_catastro as $item) {

            // Creamos una clave única sin incluir numvia
            $key = implode('|', [
                $item->codpredio,
                $item->descurb,
                $item->codvia,
                $item->intdpto,
                $item->blockedif,
                $item->mz,
                $item->lote,
                $item->zonificacion,
                $item->area_economica,
                $item->via_completa,
            ]);

            // Agrupar por clave
            if (!isset($grupos[$key])) {
                $grupos[$key] = [];
            }

            $grupos[$key][] = $item;
        }

        // Ahora elegimos el fiu_id más alto por cada grupo
        $resultado_final = [];

        foreach ($grupos as $items) {
            $itemSeleccionado = collect($items)->sortByDesc('fiu_id')->first();
            $resultado_final[] = $itemSeleccionado;
        }

        return $resultado_final;

    }
}
