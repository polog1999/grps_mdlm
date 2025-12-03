<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;

class TipoResolucionService
{
    protected $connectionToPostgreSQL;
    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function getTipoResoluciones()
    {
        return $this->connectionToPostgreSQL
            ->table('licencia.tiporesolucion')
            ->select('tir_id', 'tir_descripcion')
            ->where('tir_activo', true)
            ->get();
    }
}