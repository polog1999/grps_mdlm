<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Servicio para operaciones relacionadas con Personas Solicitantes.
 *
 * Esta clase maneja consultas a la base de datos de licencias para obtener
 * información de personas solicitantes, utilizando la tabla 'licencia.lictotal'.
 * Maneja casos de no encontrado, duplicado o error.
 */
class PersonaSolicitante{

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
     * Obtiene información de una persona solicitante por su ID.
     *
     * Consulta la tabla 'licencia.lictotal' filtrando por 'per_idsolicitante'.
     * Maneja casos de no encontrado, duplicado o error, retornando un array
     * con 'status' y 'data'.
     *
     * @param mixed $per_idsolicitante ID del solicitante.
     * @return array Resultado de la consulta con status y data.
     */
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
