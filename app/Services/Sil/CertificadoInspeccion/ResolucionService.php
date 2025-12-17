<?php
namespace App\Services\Sil\CertificadoInspeccion;

use Illuminate\Support\Facades\DB;

class ResolucionService
{

    protected $connection;

    public function __construct()
    {
        $this->connection = DB::connection('pgsql_gestrad');
    }

    public function obtenerNumeroExpedientePorNumeroResolucion($tram_todo)
    {
        return $this->connection
            ->table('sistema.p_dtos_ntrnos as n')
            ->join('sistema.p_dto_trmtes as t', 'n.cdgo_dto_trmte', '=', 't.cdgo_dto_trmte')
            ->where('n.cdgo_area', 29)
            ->where('n.cdgo_tpo_trmte', 48)
            ->whereNotNull('n.cdgo_dto_trmte')
            ->where('n.nu_tram_todo', $tram_todo)
            ->select('t.nu_expe_todo as numero_expediente')
            ->first();
    }

    public function obtenerNumeroResolucionPorNumeroExpediente($nu_expe_todo)
    {
        return $this->connection
            ->table('sistema.p_dto_trmtes as t')
            ->join('sistema.p_dtos_ntrnos as n', 'n.cdgo_dto_trmte', '=', 't.cdgo_dto_trmte')
            ->where('n.cdgo_area', 29)
            ->where('n.cdgo_tpo_trmte', 48)
            ->whereNotNull('n.cdgo_dto_trmte')
            ->where('t.nu_expe_todo', $nu_expe_todo)
            ->selectRaw("n.nu_tram_todo as numero_resolucion, TO_CHAR(n.fe_ingrso, 'DD/MM/YYYY') as fecha_ingreso")
            ->get();
    }


    public function obtenerResolucionMasAreaCompletaPorNumeroResolucion($nu_tram_todo)
    {
        $query = "
            WITH calculo_previo AS (
                SELECT 
                    t.cdgo_dtos_ntrnos,
                    t.nu_tram_todo,
                    t.cdgo_area,
                    -- Calculamos el area_completa
                    CASE 
                        WHEN hijo.cdgo_area_prim IS NULL OR hijo.cdgo_area_prim = 0 THEN 
                            'MDLM-' || REPLACE(hijo.dc_area, '.', '')       
                        ELSE 
                            'MDLM-' || REPLACE(padre.dc_area, '.', '') || '/' || REPLACE(hijo.dc_area, '.', '')
                    END as area_completa
                FROM sistema.p_dtos_ntrnos t
                INNER JOIN sistema.a_areas hijo ON t.cdgo_area = hijo.cdgo_area
                LEFT JOIN sistema.a_areas padre ON hijo.cdgo_area_prim = padre.cdgo_area
                WHERE t.nu_tram_todo = ?
            )
            SELECT 
                *, 
                nu_tram_todo || '-' || area_completa as codigo_unico_tramite
            FROM calculo_previo
            ORDER BY cdgo_dtos_ntrnos DESC;
        ";

        $resultados = $this->connection->select($query, [$nu_tram_todo]);

        return collect($resultados);
    }

    public function obtenerResoluciones($nu_expe_todo)
    {
        $search = '%' . $nu_expe_todo . '%';

        $sql = "
        WITH calculo_previo AS (
            SELECT 
                t.cdgo_dtos_ntrnos,
                t.nu_tram_todo,
                t.cdgo_area,
                CASE 
                    WHEN hijo.cdgo_area_prim IS NULL OR hijo.cdgo_area_prim = 0 THEN 
                        'MDLM-' || REPLACE(hijo.dc_area, '.', '')        
                    ELSE 
                        'MDLM-' || REPLACE(padre.dc_area, '.', '') || '/' || REPLACE(hijo.dc_area, '.', '')
                END as area_completa
            FROM sistema.p_dtos_ntrnos t
            INNER JOIN sistema.a_areas hijo ON t.cdgo_area = hijo.cdgo_area
            LEFT JOIN sistema.a_areas padre ON hijo.cdgo_area_prim = padre.cdgo_area
            WHERE t.nu_tram_todo LIKE ? AND t.cdgo_area = 29
        )
        SELECT 
            nu_tram_todo || '-' || area_completa as codigo_unico_tramite
        FROM calculo_previo
        ORDER BY cdgo_dtos_ntrnos DESC
        ";

        $resultados = $this->connection->select($sql, [$search]);


        return collect($resultados)->pluck('codigo_unico_tramite')->toArray();
    }

}
