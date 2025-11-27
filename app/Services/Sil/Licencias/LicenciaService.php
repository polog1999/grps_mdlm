<?php

namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use App\Services\Sil\Licencias\Handlers\LicenciaReader;
use App\Services\Sil\Licencias\Handlers\LicenciaCreator;
use App\Services\Sil\Licencias\Handlers\LicenciaUpdater;
class LicenciaService
{
    protected $reader;
    protected $creator;
    protected $updater;
    protected $connection;
    protected $connection2;

    public function __construct()
    {
        $this->connection = DB::connection('pgsql_licencias');
        $this->connection2 = DB::connection('oracle');

        $this->reader = new LicenciaReader($this->connection,$this->connection2);
        $this->creator = new LicenciaCreator($this->connection);
        $this->updater = new LicenciaUpdater($this->connection);
    }

    public function getById($id)
    {
        return $this->reader->findById($id);
    }       

    public function obtenerDatosDeRazonSocialPorExpediente($expnum){
        return $this->reader->obtenerDatosDeRazonSocialPorExpediente($expnum);
    }
    public function obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat){
        return $this->reader->obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat);
    }
    public function create(array $data)
    {
        return $this->creator->execute($data);
    }

    public function update(array $data)
    {
        return $this->updater->execute($data);
    }
}
