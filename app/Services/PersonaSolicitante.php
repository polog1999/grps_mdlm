<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class PersonaSolicitante{

    protected $connection;
        
    public function __construct()
    {
        $this->connection = DB::connection('pgsql_licencias');
    }



    //En la tabla licencia.lictotal recoletar los datos por per_idsolicitante

    public function obtenerPorIdSolicitante($per_idsolicitante)
    {    

        try {
            $resultados = $this->connection
                ->table('licencia.lictotal')
                ->where('per_idsolicitante', $per_idsolicitante)
                ->get();

            $count = $resultados->count();

            if ($count === 0) {
                logger()->info("No se encontró ningún registro con per_idsolicitante: {$per_idsolicitante}");
                return ['status' => 'no_encontrado', 'data' => collect()];
            } elseif ($count > 1) {
                logger()->warning("Se encontraron {$count} registros duplicados con per_idsolicitante: {$per_idsolicitante}");
                return ['status' => 'duplicado', 'data' => $resultados];
            }
            return ['status' => 'ok', 'data' => $resultados->first()];
        } catch (\Throwable $e) {
            logger()->error('Error al consultar persona solicitante: ' . $e->getMessage());
            return ['status' => 'error', 'data' => collect()];
        }
    }


}
