<?php
namespace App\Services\Sil\CertificadoInspeccion;

use Illuminate\Support\Facades\DB;


class ResolucionService{

 
    protected $connection;

    public function __construct()
    {
        $this->connection = DB::connection('pgsql_gestrad');
    }

    /*
     
SELECT 
    t.nu_expe_todo AS numero_expediente
FROM sistema.p_dtos_ntrnos n
JOIN sistema.p_dto_trmtes t
      ON n.cdgo_dto_trmte = t.cdgo_dto_trmte
WHERE n.cdgo_area = 29
  AND n.cdgo_tpo_trmte = 48
  AND n.cdgo_dto_trmte IS NOT NULL
  AND n.nu_tram_todo = '3095-2024'
*/
    public function obtenerNumeroExpedientePorNumeroResolucion($tram_todo){
        return $this->connection->table('sistema.p_dtos_ntrnos n')
        ->join('sistema.p_dto_trmtes t', 'n.cdgo_dto_trmte', 't.cdgo_dto_trmte')
        ->where('n.cdgo_area', 29)
        ->where('n.cdgo_tpo_trmte', 48)
        ->where('n.cdgo_dto_trmte', 'IS NOT NULL')
        ->where('n.nu_tram_todo', $tram_todo)
        ->select('t.nu_expe_todo AS numero_expediente')
        ->first();
    }

}
