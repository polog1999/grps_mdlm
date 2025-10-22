<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class LicenciaService
{
    protected $connection;
    
    public function __construct()
    {
        $this->connection = DB::connection('pgsql_licencias');
    }

    public function getLicencias()
    {
        return $this->connection->table('licencia.licencia')->get();
    }

    public function obtenerPrimerosDiez()
    {
        return $this->connection
            ->table('licencia.licencia')
            ->limit(10)
            //->select('lic_id')
            ->get();
    }   

    public function  contarResultados($count,$resultados,$numero)
    {
        if ($count === 0) {
            logger()->info("No se encontró ningún registro con lic_expnum = {$numero}");
            return [
                'status' => 'no_encontrado',
                'data' => collect(),
            ];
        } elseif ($count > 1) {
            logger()->warning("Se encontraron {$count} registros duplicados con lic_expnum = {$numero}");
            return [
                'status' => 'duplicado',
                'data' => $resultados,
            ];
        }
        return [
            'status' => 'ok',
            'data' => $resultados->first(),
        ];
    }
    public function obtenerPorNumeroExpediente($lic_numexp)
    {
        try {
            $resultados = $this->connection
                ->table('licencia.licencia')
                ->where('lic_expnum', $lic_numexp)
                ->get();

            $count = $resultados->count();
            return $this->contarResultados($count, $resultados, $lic_numexp);

        } catch (\Throwable $e) {
            logger()->error('Error al consultar licencias: ' . $e->getMessage());
            return [
                'status' => 'error',
                'data' => collect(),
            ];
        }
    }

    public function obtenerPorNumeroLicencia($lic_numlic)
    {
        try {
            $resultados = $this->connection
                ->table('licencia.licencia')
                ->where('lic_numlic', $lic_numlic)
                ->get();

            $count = $resultados->count();
            return $this->contarResultados($count, $resultados, $lic_numlic);

        } catch (\Throwable $e) {
            logger()->error('Error al consultar licencias: ' . $e->getMessage());
            return [
                'status' => 'error',
                'data' => collect(),
            ];
        }
    }
}