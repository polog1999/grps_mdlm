<?php

namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LicenciaNotificacionService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function notificarLicencia(array $data)
    {
        $sql = "SELECT * FROM licencia.spu_licencia_upd_notifica(
            :p_lic_id,
            :p_lic_fechanotificacion,
            :p_lic_fechalimite
        )";

        $bindings = [
            'p_lic_id' => $data['lic_id'],
            'p_lic_fechanotificacion' => Carbon::parse($data['fecha_notificacion'])->format('Y-m-d'),
            'p_lic_fechalimite' => Carbon::parse($data['fecha_limite'])->format('Y-m-d'),
        ];

        return $this->connectionToPostgreSQL->selectOne($sql, $bindings);
    }

    public function datosLicenciaNotificada($lic_id)
    {
        $sql = "SELECT lic_id, lic_numlic, lic_expnum, lic_fechanotificacion, lic_fechalimite 
                FROM licencia.licencia 
                WHERE lic_id = :lic_id 
                AND lic_filaeliminada = false";

        $bindings = [
            'lic_id' => $lic_id,
        ];

        return $this->connectionToPostgreSQL->selectOne($sql, $bindings);
    }
}