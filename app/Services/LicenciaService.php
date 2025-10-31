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

    /*
    public function obtenerPrimerosDiez()
    {
        return $this->connection
            ->table('licencia.licencia')
            ->limit(10)
            //->select('lic_id')
            ->get();
    }   
*/
    /**
     * Método privado para buscar y contar registros según condiciones
     * Filtra automáticamente registros con cin_filaeliminada = false
     */
    private function buscarYContar(array $condiciones, string $descripcion)
    {
        try {
            $resultados = $this->connection
                ->table('licencia.licencia')
                ->where($condiciones)
                ->where('lic_filaeliminada', false)
                ->get();

            $count = $resultados->count();

            if ($count === 0) {
                logger()->info("No se encontró ningún registro activo con {$descripcion}");
                return ['status' => 'no_encontrado', 'data' => collect()];
            } elseif ($count > 1) {
                logger()->warning("Se encontraron {$count} registros activos duplicados con {$descripcion}");
                return ['status' => 'duplicado', 'data' => $resultados];
            }

            return ['status' => 'ok', 'data' => $resultados->first()];
        } catch (\Throwable $e) {
            logger()->error('Error al consultar licencias: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => collect()
            ];
        }
    }

    /**
     * Buscar por número de expediente
     */
    public function obtenerPorNumeroExpediente($lic_expnum)
    {
        return $this->buscarYContar(
            [['lic_expnum', '=', $lic_expnum]],
            "lic_expnum = {$lic_expnum}"
        );
    }

    /**
     * Buscar por número de licencia
     */
    public function obtenerPorNumeroLicencia($lic_numlic)
    {
        return $this->buscarYContar(
            [['lic_numlic', '=', $lic_numlic]],
            "lic_numlic = {$lic_numlic}"
        );
    }

    /**
     * Buscar por ID de licencia
     */
    public function obtenerPorIdLicencia($lic_id)
    {
        return $this->buscarYContar(
            [['lic_id', '=', $lic_id]],
            "lic_id = {$lic_id}"
        );
    }

    /**
     * Buscar por número de licencia y expediente (combinado)
     */
    public function obtenerPorNumeroLicenciaYExpediente($lic_numlic, $lic_expnum)
    {
        return $this->buscarYContar(
            [
                ['lic_numlic', '=', $lic_numlic],
                ['lic_expnum', '=', $lic_expnum]
            ],
            "lic_numlic = {$lic_numlic} y lic_expnum = {$lic_expnum}"
        );
    }
}