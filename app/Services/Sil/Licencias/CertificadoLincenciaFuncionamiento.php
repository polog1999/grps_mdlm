<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;

class CertificadoLincenciaFuncionamiento
{
    protected $connectionToOracle;
    protected $connectionToPostgreSQL;
      public function __construct()
    {
        $this->connectionToOracle = DB::connection('oracle');
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }


    public function getLicenciasQueryBuilder(): Builder
    {
        return $this->connectionToPostgreSQL
            ->table('licencia.vu_licencia')
            ->select(
                'lic_id',
                'lic_numlic',
                'lic_expnum',
                'lic_razonsocial',
                'codigocatastral',
                'tli_descripcion',
                'tes_descripcion',
                'per_direccion',
                'per_direccionsol'
            )
            ->distinct();
    }


    public function obtenerDatosLicenciaFuncionamiento(string $expnum, array $columns = [
        'exp_num',
        'exp_fec',
        'exp_nomrec',
        'exp_codcon'
    ])
    {
        try {
        $query = $this->connectionToOracle
            ->table('ds_valores.dur_expediente as e')
            ->join('ds_valores.vu_persona2 as p', 'e.exp_codcon', '=', 'p.codcon')
            ->where('e.exp_num', $expnum);

        $selects = array_merge(
            array_map(fn($col) => "e.{$col}", $columns), 
            ['p.numdoc', 'p.domfis','p.numtel','p.correo'] 
        );
        $query->select($selects);

        if (count($columns) === 1) {
            return $query->pluck("e.{$columns[0]}");
        }

        // Ejecuta y obtiene los resultados principales
        $rows = $query->get();

        // Obtener codcat asociado al expediente y, si existe, recuperar registros por ecc_codcat
        $codcat = $this->obtenerCodCatPorExpediente($expnum);
        if (! empty($codcat)) {
            $rows = $rows->map(function ($r) use ($codcat) {
                $r->ecc_codcat = $codcat;
                return $r;
            });
        }

        return $rows;

        } catch (\Throwable $e) {
            Log::error("Error al obtener datos de Expediente para EXP_NUM {$expnum}: " . $e->getMessage());
            return collect();
        }
    }

        
    public function obtenerDatosLicenciaFuncionamiento2(string $codcon)
    {
        try{
            return $this->connectionToOracle
                ->table('DS_VALORES.VU_PERSONA2')
                ->where('CODCON', $codcon)
                ->get(); 
        } catch (\Throwable $e) {
            Log::error("Error al obtener datos de Expediente por codcon {$codcon}: " . $e->getMessage());
            return collect();
        }
    }


    public function obtenerCodCatPorExpediente(string $expnum)
    {
        try {
            $result = $this->connectionToOracle
                ->table('ds_valores.dur_expcodcat')
                ->select('ecc_codcat')
                ->where('exp_num', $expnum)
                ->first();

            return $result ? $result->ecc_codcat : null;
        } catch (\Throwable $e) {
            Log::error("Error al obtener CODCAT para EXP_NUM {$expnum}: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerCodCatPorExpedienteConVuLicencias(string $lic_id) {

        try{
            $result = $this -> connectionToPostgreSQL
                ->table('licencia.vu_licencia')
                ->select ('codigocatastral')
                ->where('lic_id', $lic_id)
                ->first();

            return $result ? $result->codigocatastral : null;

        }catch (\Throwable $e) {
            Log::error("Error al obtener CODCAT para LIC_ID {$lic_id}: " . $e->getMessage());
            return null;
        }
        
    }

    public function obtenerDireccionSolicitantePorIdLicencia(string $lic_id)
    {
        try {
            $result = $this->connectionToPostgreSQL
                ->table('licencia.vu_licencia')
                ->select('per_direccionsol')
                ->where('lic_id', $lic_id)
                ->first();

            return $result ? $result->per_direccionsol : null;
        } catch (\Throwable $e) {
            Log::error("Error al obtener dirección solicitante para LIC_ID {$lic_id}: " . $e->getMessage());
            return null;
        }
    }
    /**
     * Ejecuta la función de tabla Oracle MESQUECHE.FU_SYSCATFICHAUBICACION_SEL
     * y retorna sus filas como colección.
     *
     * @param string $codcat
     * @return \Illuminate\Support\Collection
     */
    public function obtenerDatosPorCodCat(string $codcat)
    {
        try {
            // Seleccionar sólo las columnas necesarias desde la función de tabla Oracle
            $sql = "SELECT t.codpredio, t.descurb, t.codvia FROM TABLE(MESQUECHE.FU_SYSCATFICHAUBICACION_SEL(?, '')) t";
            $result = $this->connectionToOracle->select($sql, [$codcat]);
            return collect($result);
        } catch (\Throwable $e) {
            Log::error("Error al ejecutar FU_SYSCATFICHAUBICACION_SEL para CODCAT {$codcat}: " . $e->getMessage());
            return collect();
        }
    }


    public function obtenerViaNombrePorCodVia(string $codvia)
    {
        try {
            $sql = <<<'SQL'
                SELECT
                    v.via_id,
                    v.via_codvia,
                    (vt.vit_abretipvia || ' ' || v.via_descvia) AS via_completa
                FROM syscat.via v
                LEFT JOIN syscat.viatipo vt
                    ON v.via_codtipvia = vt.vit_codtipvia
                WHERE v.via_codvia = ?
                LIMIT 1
                SQL;
            
            // Devuelve el objeto $row directamente (o null si no se encuentra)
            return $this->connectionToPostgreSQL->selectOne($sql, [$codvia]);

        } catch (\Throwable $e) {
            Log::error("Error al obtener nombre de vía para CODVIA {$codvia}: " . $e->getMessage());
            return null;
        }
    }



}