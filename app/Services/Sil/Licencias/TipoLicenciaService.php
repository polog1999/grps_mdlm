<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;

class TipoLicenciaService
{
    protected $connectionToPostgreSQL;
    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function getTipoLicencias()
    {
        return $this->connectionToPostgreSQL
            ->table('licencia.tipolicencia')
            ->select('tli_id', 'tli_descripcion')
            ->where('tli_activo', true)
            ->get();
    }
}