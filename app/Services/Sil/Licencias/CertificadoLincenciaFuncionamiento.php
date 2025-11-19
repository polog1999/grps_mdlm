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

        $rows = $query->get();

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
                FROM
                    TABLE(MESQUECHE.FU_SYSCATFICHAUBICACION_SEL(?, '')) t
            ";
            $result = $this->connectionToOracle->select($sql, [$codcat]);
            $rows = collect($result);

            // Si no hay filas, devolver colección vacía
            if ($rows->isEmpty()) {
                return $rows;
            }

            // Recolectar codvia únicos para obtener nombres en una sola consulta
            $codvias = $rows
                ->pluck('codvia')
                ->map(fn($v) => trim((string) $v))
                ->filter(fn($v) => $v !== '')
                ->unique()
                ->values()
                ->all();

            $viaMap = [];

            if (!empty($codvias)) {
                $placeholders = implode(',', array_fill(0, count($codvias), '?'));

                $sqlVias = "
                    SELECT
                        v.via_codvia,
                        (vt.vit_abretipvia || ' ' || v.via_descvia) AS via_completa
                    FROM syscat.via v
                    LEFT JOIN syscat.viatipo vt
                        ON v.via_codtipvia = vt.vit_codtipvia
                    WHERE v.via_codvia IN ($placeholders)
                ";

                $viaRows = $this->connectionToPostgreSQL->select($sqlVias, $codvias);

                foreach ($viaRows as $vr) {
                    $key = trim((string) ($vr->via_codvia ?? ''));
                    $viaMap[$key] = $vr->via_completa ?? null;
                }
            }

            $rows = $rows->map(function ($r) use ($viaMap) {
                $codvia = isset($r->codvia) ? trim((string) $r->codvia) : '';
                $r->via_completa = $viaMap[$codvia] ?? null;
                return $r;
            });

            return $rows;
        } catch (\Throwable $e) {
            Log::error("Error al ejecutar FU_SYSCATFICHAUBICACION_SEL para CODCAT {$codcat}: " . $e->getMessage());
            return collect();
        }
    }

    public function obtenerListaDeProcedimientosTupaDeLicencias()
 {
        try {
            $rows = $this->connectionToOracle
                ->table('ds_valores.VU_PROCEDIMIENTO_TOTAL')
                ->select('PROCCODIGO', 'PROCDESCRI')
                ->whereIn('PROCCODIGO', ['P047', 'P046', 'P048', 'P043', 'P041'])
               ->get();

            $collection = collect($rows)->map(function ($r) {
                // obtener la descripción de forma robusta (clave puede variar entre conexiones)
                $arr = (array) $r;
                $descr = '';
                foreach ($arr as $k => $v) {
                    if (stripos($k, 'DESCR') !== false || stripos($k, 'PROCDESCRI') !== false) {
                        $descr = (string) $v;
                        break;
                    }
                }

                $texto = strtoupper($descr);

                // determinar nivel de riesgo (orden: muy alto -> alto -> medio -> bajo)
                if (preg_match('/\bMUY\s+ALTO\b/i', $texto) || preg_match('/RIESGO\s+MUY\s+ALTO/i', $texto)) {
                    $nivel = 'RIESGO MUY ALTO';
                } elseif (preg_match('/\bALTO\b/i', $texto) || preg_match('/RIESGO\s+ALTO/i', $texto)) {
                    $nivel = 'RIESGO ALTO';
                } elseif (preg_match('/\bMEDIO\b/i', $texto) || preg_match('/RIESGO\s+MEDIO/i', $texto)) {
                    $nivel = 'RIESGO MEDIO';
                } elseif (preg_match('/\bBAJO\b/i', $texto) || preg_match('/RIESGO\s+BAJO/i', $texto)) {
                    $nivel = 'RIESGO BAJO';
                } else {
                    $nivel = 'RIESGO NO ESPECIFICADO';
                }

                // agregar propiedad procnivel al objeto
                $r->procnivel = $nivel;

                return $r;
            });

            return $collection;
        } catch (\Throwable $e) {
            Log::error("Error al obtener lista de procedimientos TUPA de licencias: " . $e->getMessage());
            return collect();
        }
    }
}