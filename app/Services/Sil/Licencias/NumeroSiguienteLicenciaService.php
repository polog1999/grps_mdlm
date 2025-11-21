<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NumeroSiguienteLicenciaService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function obtenerSiguienteNumeroLicencia()
    {
        $result = $this->connectionToPostgreSQL->select('SELECT licencia.fn_get_next_lic_numlic() as next_numlic');
        return $result[0]->next_numlic ?? null;
    }
}