<?php
namespace App\Services\Sil\CertificadoInspeccion;

use Illuminate\Support\Facades\DB;

class ResolucionService{

    protected $connection;

    public function __construct()
    {
        $this->connection = DB::connection('pgsql_gestrad');
    }

    public function obtenerNumeroExpedientePorNumeroResolucion($tram_todo){
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

    public function obtenerNumeroResolucionPorNumeroExpediente($nu_expe_todo){
        return $this->connection
            ->table('sistema.p_dto_trmtes as t')
            ->join('sistema.p_dtos_ntrnos as n', 'n.cdgo_dto_trmte', '=', 't.cdgo_dto_trmte')
            ->where('n.cdgo_area', 29)
            ->where('n.cdgo_tpo_trmte', 48)
            ->whereNotNull('n.cdgo_dto_trmte')
            ->where('t.nu_expe_todo', $nu_expe_todo)
            ->select('n.nu_tram_todo as numero_resolucion')
            ->get();
    }

}
