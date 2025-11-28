<?php
namespace App\Services\Sil\CertificadoInspeccion;

use Illuminate\Support\Facades\DB;


class ResolucionService{

 
    protected $connection;

    public function __construct()
    {
        $this->connection = DB::connection('pgsql_gestrad');
    }

}
