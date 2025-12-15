<?php

namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use App\Services\Sil\Licencias\Handlers\LicenciaReader;
use App\Services\Sil\Licencias\Handlers\LicenciaCreator;
use App\Services\Sil\Licencias\Handlers\LicenciaUpdater;
use App\Services\Sil\Licencias\Handlers\LicenciaDuplicator;
use App\Services\Sil\Licencias\Handlers\LicenciaTransferor;
class LicenciaService
{
    protected $reader;
    protected $creator;
    protected $updater;
    protected $duplicator;
    protected $transferor;
    protected $connection;
    protected $connection2;

    public function __construct()
    {
        $this->connection = DB::connection('pgsql_licencias');
        $this->connection2 = DB::connection('oracle');

        $this->reader = new LicenciaReader($this->connection, $this->connection2);
        $this->creator = new LicenciaCreator($this->connection);
        $this->updater = new LicenciaUpdater($this->connection);
        $this->duplicator = new LicenciaDuplicator($this->connection);
        $this->transferor = new LicenciaTransferor($this->connection);
    }
    //READER

    public function getById($id)
    {
        return $this->reader->getById($id);
    }


    public function obtenerDatosDeRazonSocialPorExpediente($expnum)
    {
        return $this->reader->obtenerDatosDeRazonSocialPorExpediente($expnum);
    }
    public function obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat)
    {
        return $this->reader->obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat);
    }
    public function obtenerDatosDePersonaORazonSocialPorNombre($nombre_razon_social)
    {
        return $this->reader->obtenerDatosDePersonaORazonSocialPorNombre($nombre_razon_social);
    }

    //READER DATOS PARA EDITAR
    public function obtenerDatosDeExpedienteParaEditarPorIdLicencia($lic_id)
    {
        return $this->reader->obtenerDatosDeExpedienteParaEditarPorIdLicencia($lic_id);
    }

    public function obtenerDatosPorIdLicenciaDirecta($licId)
    {
        return $this->reader->obtenerDatosPorIdLicenciaDirecta($licId);
    }

    //CREATOR
    public function create(array $data)
    {
        return $this->creator->execute($data);
    }

    public function update(array $data)
    {
        return $this->updater->execute($data);
    }

    public function duplicate(array $data)
    {
        return $this->duplicator->execute($data);
    }

    public function transfer(array $data)
    {
        return $this->transferor->execute($data);
    }
}
