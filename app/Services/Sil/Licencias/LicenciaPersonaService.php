<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;

class LicenciaPersonaService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function getLicenciaPersonaNombre()
    {
        return $this->connectionToPostgreSQL
            ->table('licencia.persona')
            ->select(
                'per_id',
                'per_nombrerazonsocial',
                'per_ruc',
                'per_direccion',
                'per_telefono',
                'per_email',
                'per_expcodcon'
            )
            ->where('per_filaeliminada', false)
            ->distinct('per_nombrerazonsocial')
            ->orderBy('per_nombrerazonsocial')
            ->orderByDesc('per_id')
            ->get();

    }
    public function getIdPersonaPorNombre($nombre)
    {
        return $this->connectionToPostgreSQL
            ->table('licencia.persona')
            ->select('per_id')
            ->where('per_nombrerazonsocial', $nombre)
            ->where('per_filaeliminada', false)
            ->get();
    }
}