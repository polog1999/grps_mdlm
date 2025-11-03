<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Servicio para operaciones relacionadas con Tipos de Edificación.
 *
 * Esta clase maneja consultas a la base de datos para obtener tipos de edificación,
 * utilizando la tabla 'itse.tipoedificacion'. Proporciona métodos para listar
 * todos los tipos o solo los activos.
 */
class TipoEdificacionService{

    /**
     * Conexión a la base de datos PostgreSQL.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * Constructor del servicio.
     *
     * Inicializa la conexión a la base de datos 'pgsql'.
     */
    public function __construct()
    {
        $this->connection = DB::connection('pgsql');
    }

    /**
     * Obtiene todos los tipos de edificación.
     *
     * Retorna una colección con 'tie_id' y 'tie_descripcion' de la tabla 'itse.tipoedificacion'.
     *
     * @return \Illuminate\Support\Collection Colección de tipos de edificación.
     */
    public function getTipoEdificaciones()
    {
        return $this->connection->table('itse.tipoedificacion')->select('tie_id', 'tie_descripcion')->get();
    }

    /**
     * Obtiene solo los tipos de edificación activos.
     *
     * Filtra por 'tie_activo' = true y retorna 'tie_id' y 'tie_descripcion'.
     *
     * @return \Illuminate\Support\Collection Colección de tipos de edificación activos.
     */
    public function getTipoEdificacionesActivos()
    {
        return $this->connection->table('itse.tipoedificacion')
            ->select('tie_id', 'tie_descripcion')
            ->where('tie_activo', true)
            ->get();
    }
}
