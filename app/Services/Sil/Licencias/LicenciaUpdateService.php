<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LicenciaUpdateService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function obtenerPorIdLicencia($lic_id)
    {
         return $this->connectionToPostgreSQL
        ->table('licencia.vu_licencia')
        ->select("*")
        ->where('lic_id', $lic_id)
        ->get();
    }

}