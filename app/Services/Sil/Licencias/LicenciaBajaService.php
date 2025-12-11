<?php

namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LicenciaBajaService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function bajaLicencia(array $data)
    {
        $sql = "SELECT * FROM licencia.spu_licenciabaja_ins2(
            :lic_id,
            :p_lib_expnum,
            :p_lib_anexo,
            :p_lib_resnum,
            :p_lib_fecharesolucion,
            :p_lib_fechabaja,
            :p_lib_id
        )";

        $bindings = [
            'lic_id' => $data['lic_id'],
            'p_lib_expnum' => $data['lib_expnum'],
            'p_lib_anexo' => $data['lib_anexo'],
            'p_lib_resnum' => $data['lib_resnum'],
            'p_lib_fecharesolucion' => Carbon::parse($data['lib_fecharesolucion'])->format('d/m/Y'),
            'p_lib_fechabaja' => Carbon::parse($data['lib_fechabaja'])->format('d/m/Y'),
            'p_lib_id' => $data['lib_id'] ?? 0,
        ];

        return $this->connectionToPostgreSQL->selectOne($sql, $bindings);
    }
}