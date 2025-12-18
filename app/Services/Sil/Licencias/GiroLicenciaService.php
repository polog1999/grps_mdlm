<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class GiroLicenciaService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    /**
     * Busca giros usando el procedimiento almacenado spu_giro_sel
     * 
     * @param string $searchTerm Término de búsqueda (ej: 'BODEGA')
     * @return \Illuminate\Support\Collection
     */
    public function buscarGiros(string $searchTerm)
    {
        try {
            $sql = "SELECT * FROM licencia.spu_giro_sel(0, ?)";

            $result = $this->connectionToPostgreSQL->select($sql, [$searchTerm]);
            return collect($result);
        } catch (\Exception $e) {
            Log::error("Error al buscar giros con término '{$searchTerm}': " . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtiene todos los giros (sin filtro específico)
     * 
     * @return \Illuminate\Support\Collection
     */
    public function obtenerTodosLosGiros()
    {
        try {
            $sql = "SELECT * FROM licencia.spu_giro_sel(0, '')";

            $result = $this->connectionToPostgreSQL->select($sql);
            return collect($result);

        } catch (\Exception $e) {
            Log::error("Error al obtener todos los giros: " . $e->getMessage());
            return collect();
        }
    }


    /**
     * Busca un giro específico por su ID
     * 
     * @param int $giroId ID del giro
     * @return object|null
     */
    public function buscarGiroPorId(int $giroId)
    {
        try {
            $sql = "SELECT * FROM licencia.spu_giro_sel(?, '')";

            $result = $this->connectionToPostgreSQL->select($sql, [$giroId]);
            return $result;
        } catch (\Exception $e) {
            Log::error("Error al buscar giro por ID {$giroId}: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerGirosPorIdLicencia(int $lic_id)
    {
        try {
            $sql = "SELECT* FROM licencia.vu_licenciagiro where lic_id= ?";
            $result = $this->connectionToPostgreSQL->select($sql, [$lic_id]);
            return collect($result);
        } catch (\Exception $e) {
            Log::error("Error al obtener giros por ID de licencia {$lic_id}: " . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtiene los registros de la tabla licencia.licenciagiro para una licencia
     * 
     * @param int $lic_id
     * @return \Illuminate\Support\Collection
     */
    public function obtenerLicenciaGiros(int $lic_id)
    {
        try {
            return $this->connectionToPostgreSQL->table('licencia.licenciagiro')
                ->where('lic_id', $lic_id)
                ->get();
        } catch (\Exception $e) {
            Log::error("Error al obtener licenciagiro por ID de licencia {$lic_id}: " . $e->getMessage());
            return collect();
        }
    }
}
