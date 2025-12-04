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

    //Paso 1: Datos de Expediente
    public function obtenerDatosDeRazonSocialPorExpediente($expnum)
    {
        $exp = $this->db2
            ->table('DS_VALORES.DUR_EXPEDIENTE')
            ->select(
                'exp_codcon',
                'exp_num',
                $this->db2->raw("TO_CHAR(exp_fec, 'DD/MM/YYYY') AS exp_fec_formateado")
            )
            ->where('EXP_NUM', $expnum)
            ->first();

        if (!$exp || $exp->exp_codcon == null) {
            return null;
        }

        $cod_con = $exp->exp_codcon;

        $datos_persona = $this->db2
            ->table('DS_VALORES.VU_PERSONA2')
            ->select('nomcom', 'domfis', 'numtel', 'correo', 'codcon')
            ->where('CODCON', $cod_con)
            ->first();

        if (!$datos_persona) {
            return (object) [
                'nomcom' => null,
                'domfis' => null,
                'numtel' => null,
                'correo' => null,
                'codcon' => $cod_con,
                'exp_num' => $exp->exp_num,
                'exp_fec' => $exp->exp_fec_formateado,
            ];
        }

        $datos_persona->exp_num = $exp->exp_num;
        $datos_persona->exp_fec = $exp->exp_fec_formateado;

        return $datos_persona;
    }

    public function obtenerDatosDePersonaORazonSocialPorNombre($nombre)
    {
        $personas = $this->db
            ->table('licencia.persona')
            ->select('per_id', 'per_nombrerazonsocial')
            ->where('per_nombrerazonsocial', $nombre)
            ->where('per_filaeliminada', false)
            ->orderBy('per_id', 'desc')
            ->get()
            ->first();
        return $personas;
    }
    //Paso 1: Datos de Expediente
    public function obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat)
    {
        $sql = "
            SELECT      
                TRIM(t.coduca) AS coduca,               
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


    public function obtenerDatosDeExpedienteOraclePoRExpediente($expnum)
    {
        $exp = $this->db2
            ->table('DS_VALORES.DUR_EXPEDIENTE')
            ->select(
                'exp_codcon',
                'exp_num',
                $this->db2->raw("TO_CHAR(exp_fec, 'DD/MM/YYYY') AS exp_fec_formateado")
            )
            ->where('EXP_NUM', $expnum)
            ->first();

        if (!$exp || $exp->exp_codcon === null) {
            return null;
        }

        $cod_con = $exp->exp_codcon;

        $datos_contacto = $this->db2
            ->table('DS_VALORES.VU_PERSONA2')
            ->select(
                'nomcom',
                'domfis',
                'numtel',
                'correo',
                'numdoc',
                'codcon'
            )
            ->where('codcon', $cod_con)
            ->first();
        if (!$datos_contacto) {
            return (object) [
                'nomcom' => null,
                'domfis' => null,
                'numtel' => null,
                'correo' => null,
                'numdoc' => null,
                'codcon' => $cod_con,
                'exp_num' => $exp->exp_num,
                'exp_fec' => $exp->exp_fec_formateado,
            ];
        }
        $datos_contacto->exp_num = $exp->exp_num;
        $datos_contacto->exp_fec = $exp->exp_fec_formateado;

        return $datos_contacto;
    }

    public function obtenerDatosDeExpedienteParaEditarPorIdLicencia($lic_id)
    {
        $licencia = $this->db
            ->table('licencia.licencia as l')
            ->leftJoin('licencia.persona as p1', 'p1.per_id', '=', 'l.per_idrazonsocial')
            ->leftJoin('licencia.persona as p2', 'p2.per_id', '=', 'l.per_idsolicitante')
            ->select(
                'l.lic_id',
                'l.lic_expnum',
                'l.per_idrazonsocial',
                'p1.per_nombrerazonsocial as razon_social_nombre',
                'l.per_idsolicitante',
                'p2.per_nombrerazonsocial as solicitante_nombre'
            )
            ->where('l.lic_id', $lic_id)
            ->first();

        if (!$licencia) {
            return null;
        }

        $datosExpediente = $this->obtenerDatosDeExpedienteOraclePoRExpediente($licencia->lic_expnum);

        return (object) [
            'lic_id' => $licencia->lic_id,
            'lic_expnum' => $licencia->lic_expnum,
            'per_idrazonsocial' => $licencia->per_idrazonsocial,
            'razon_social_nombre' => $licencia->razon_social_nombre,
            'per_idsolicitante' => $licencia->per_idsolicitante,
            'solicitante_nombre' => $licencia->solicitante_nombre,

            'domfis' => $datosExpediente->domfis ?? null,
            'numtel' => $datosExpediente->numtel ?? null,
            'correo' => $datosExpediente->correo ?? null,
            'numdoc' => $datosExpediente->numdoc ?? null,
            'codcon' => $datosExpediente->codcon ?? null,
            'exp_fec' => $datosExpediente->exp_fec ?? null,
        ];
    }


}
