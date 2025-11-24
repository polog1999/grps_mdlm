<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;

class TipoLocalService
{
    protected $connectionToPostgreSQL;
      public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function getTipoLocal()
    {
        return $this->connectionToPostgreSQL
        ->table('licencia.vu_tipolocal')
        ->select('tlo_id', 'tlo_descripcion')   
        ->get();

    }
}