<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;

class LicenciaReader
{
    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function findById($licId)
    {
         return $this->db
        ->table('licencia.vu_licencia')
        ->select("*")
        ->where('lic_id', $licId)
        ->get()
        ->first();
    }
}
