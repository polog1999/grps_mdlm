<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;

class TipoCentroComercialService
{
    protected $connectionToPostgreSQL;
      public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function getTipoCentroComercial()
    {
        return $this->connectionToPostgreSQL
        ->table('licencia.vu_centrocomercial')
        ->select('cec_id', 'cec_descripcion')   
        ->get();

    }
}