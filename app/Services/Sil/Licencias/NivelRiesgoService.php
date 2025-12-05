<?php

namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;

class NivelRiesgoService
{
    protected $connection;

    public function __construct()
    {
        $this->connection = DB::connection('pgsql_licencias');
    }

    /**
     * Obtiene todos los niveles de riesgo
     *
     * @return \Illuminate\Support\Collection
     */
    public function getNivelesRiesgo()
    {
        return $this->connection
            ->table('licencia.nivelriesgo')
            ->select('nir_id', 'nir_descripcion')
            ->where('nir_activo', true)
            ->orderBy('nir_descripcion', 'asc')
            ->get();
    }

    /**
     * Obtiene un nivel de riesgo por ID
     *
     * @param int $nir_id
     * @return object|null
     */
    public function getNivelRiesgoPorId($nir_id)
    {
        return $this->connection
            ->table('licencia.nivelriesgo')
            ->select('nir_id', 'nir_descripcion')
            ->where('nir_id', $nir_id)
            ->where('nir_activo', true)
            ->first();
    }
}
