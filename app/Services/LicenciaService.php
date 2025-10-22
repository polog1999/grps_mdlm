<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class LicenciaService
{
    protected $connection;

    public function __construct()
    {
        $this->connection = DB::connection('pgsql_licencias');
    }

    public function getLicencias()
    {
        return $this->connection->table('licencia.licencia')->get();
    }

    public function obtenerPrimerosDiez()
    {
        return $this->connection
            ->table('licencia.licencia')
            ->limit(10)
            ->get();
    }
}