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

    public function __construct()
    {
        $connection = DB::connection('pgsql_licencias');

        $this->reader = new LicenciaReader($connection);
        $this->creator = new LicenciaCreator($connection);
        $this->updater = new LicenciaUpdater($connection);
    }

    public function getById($id)
    {
        return $this->reader->findById($id);
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
