<?php
namespace App\Services\Sil\CertificadoInspeccion;
use Illuminate\Support\Facades\DB;

/**
 * Servicio para operaciones relacionadas con Licencias.
 *
 * Esta clase maneja consultas a la base de datos de licencias, utilizando
 * una conexión específica a PostgreSQL. Proporciona métodos para buscar
 * licencias por diferentes criterios, manejando duplicados y errores.
 */
class LicenciaService
{
    /**
     * Conexión a la base de datos de licencias (PostgreSQL).
     *
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * Constructor del servicio.
     *
     * Inicializa la conexión a la base de datos 'pgsql_licencias'.
     */
    public function __construct()
    {
        $this->connection = DB::connection('pgsql_licencias');
    }

    /**
     * Obtiene todas las licencias de la base de datos.
     *
     * Retorna una colección con todos los registros de la tabla 'licencia.licencia'.
     *
     * @return \Illuminate\Support\Collection Colección de licencias.
     */
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
     * Método privado para buscar y contar registros según condiciones.
     *
     * Filtra automáticamente registros con lic_filaeliminada = false.
     * Maneja casos de no encontrado, duplicado o error.
     *
     * @param array $condiciones Condiciones de búsqueda (pares clave-valor).
     * @param string $descripcion Descripción de las condiciones para logging.
     * @return array Resultado con 'status' y 'data'.
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
     * Busca una licencia por número de expediente.
     *
     * @param mixed $lic_expnum Número de expediente.
     * @return array Resultado de la búsqueda.
     */
    public function obtenerPorNumeroExpediente($lic_expnum)
    {
        return $this->buscarYContar(
            [['lic_expnum', '=', $lic_expnum]],
            "lic_expnum = {$lic_expnum}"
        );
    }

    /**
     * Busca una licencia por número de licencia.
     *
     * @param mixed $lic_numlic Número de licencia.
     * @return array Resultado de la búsqueda.
     */
    public function obtenerPorNumeroLicencia($lic_numlic)
    {
        return $this->buscarYContar(
            [['lic_numlic', '=', $lic_numlic]],
            "lic_numlic = {$lic_numlic}"
        );
    }

    /**
     * Busca una licencia por ID de licencia.
     *
     * @param mixed $lic_id ID de la licencia.
     * @return array Resultado de la búsqueda.
     */
    public function obtenerPorIdLicencia($lic_id)
    {
        return $this->buscarYContar(
            [['lic_id', '=', $lic_id]],
            "lic_id = {$lic_id}"
        );
    }

    /**
     * Busca una licencia por número de licencia y expediente (combinado).
     *
     * @param mixed $lic_numlic Número de licencia.
     * @param mixed $lic_expnum Número de expediente.
     * @return array Resultado de la búsqueda.
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

    public function obtenerSiguienteNumeroLicencia()
    {
        return $this->connection->select('SELECT licencia.fn_get_next_lic_numlic() as next_numlic')->first()->next_numlic;
    }
}