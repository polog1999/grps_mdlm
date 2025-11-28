<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;

class TipoEstablecimientoService
{
    protected $connectionToPostgreSQL;
      public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function getTipoEstablecimiento()
    {
        return $this->connectionToPostgreSQL
        ->table('licencia.tipoestablecimiento')
        ->select('tes_id', 'tes_descripcion')   
        ->where('tes_filaeliminada', false)
        ->whereNotNull('tes_descripcion')
        ->get();
    }
}