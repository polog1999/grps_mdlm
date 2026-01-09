<?php

namespace App\Services\Sil\DataLevantamiento;

use App\Models\LicenciaLevantamiento;
use App\Models\EstadoLevantamiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Servicio para gestionar las operaciones de LicenciaLevantamiento
 */
class LicenciaLevantamientoService
{
    /**
     * Constructor del servicio
     */
    public function __construct(
        protected LicenciaLevantamiento $licenciaLevantamiento
    ) {
    }

    /**
     * Crea o actualiza el estado de levantamiento de una licencia
     * 
     * @param int $licId ID de la licencia
     * @param int $estadoLevantamientoId ID del estado de levantamiento
     * @return LicenciaLevantamiento
     * @throws Exception
     */
    public function guardarEstadoLevantamiento(int $licId, int $estadoLevantamientoId): LicenciaLevantamiento
    {
        try {
            DB::connection('pgsql_licencias')->beginTransaction();

            // Buscar si ya existe un registro para esta licencia
            $licenciaLevantamiento = $this->licenciaLevantamiento
                ->where('lic_id', $licId)
                ->first();

            if ($licenciaLevantamiento) {
                // Actualizar el estado existente
                $licenciaLevantamiento->id_estado_levantamiento = $estadoLevantamientoId;
                $licenciaLevantamiento->registrarActualizacion();
                $licenciaLevantamiento->save();

                Log::info('Estado de levantamiento actualizado', [
                    'lic_id' => $licId,
                    'estado_levantamiento_id' => $estadoLevantamientoId,
                    'licencia_levantamiento_id' => $licenciaLevantamiento->id
                ]);
            } else {
                // Crear nuevo registro
                $licenciaLevantamiento = $this->licenciaLevantamiento->create([
                    'lic_id' => $licId,
                    'id_estado_levantamiento' => $estadoLevantamientoId,
                ]);

                Log::info('Estado de levantamiento creado', [
                    'lic_id' => $licId,
                    'estado_levantamiento_id' => $estadoLevantamientoId,
                    'licencia_levantamiento_id' => $licenciaLevantamiento->id
                ]);
            }

            DB::connection('pgsql_licencias')->commit();

            return $licenciaLevantamiento;

        } catch (Exception $e) {
            DB::connection('pgsql_licencias')->rollBack();

            Log::error('Error al guardar estado de levantamiento', [
                'lic_id' => $licId,
                'estado_levantamiento_id' => $estadoLevantamientoId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Obtiene el estado de levantamiento actual de una licencia
     * 
     * @param int $licId ID de la licencia
     * @return LicenciaLevantamiento|null
     */
    public function obtenerEstadoLevantamiento(int $licId): ?LicenciaLevantamiento
    {
        return $this->licenciaLevantamiento
            ->with(['estadoLevantamiento'])
            ->where('lic_id', $licId)
            ->first();
    }

    /**
     * Obtiene todas las licencias con un estado específico
     * 
     * @param int $estadoLevantamientoId ID del estado de levantamiento
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerLicenciasPorEstado(int $estadoLevantamientoId)
    {
        return $this->licenciaLevantamiento
            ->with(['licencia', 'estadoLevantamiento'])
            ->where('id_estado_levantamiento', $estadoLevantamientoId)
            ->get();
    }

    /**
     * Elimina el estado de levantamiento de una licencia
     * 
     * @param int $licId ID de la licencia
     * @return bool
     */
    public function eliminarEstadoLevantamiento(int $licId): bool
    {
        try {
            $licenciaLevantamiento = $this->licenciaLevantamiento
                ->where('lic_id', $licId)
                ->first();

            if ($licenciaLevantamiento) {
                $licenciaLevantamiento->delete();

                Log::info('Estado de levantamiento eliminado', [
                    'lic_id' => $licId,
                    'licencia_levantamiento_id' => $licenciaLevantamiento->id
                ]);

                return true;
            }

            return false;

        } catch (Exception $e) {
            Log::error('Error al eliminar estado de levantamiento', [
                'lic_id' => $licId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Cuenta cuántas licencias de una lista tienen estado de levantamiento asignado
     * 
     * @param array $licIds Array de IDs de licencias
     * @return int
     */
    public function contarLicenciasAtendidasPorLicIds(array $licIds): int
    {
        if (empty($licIds)) {
            return 0;
        }

        return $this->licenciaLevantamiento
            ->whereIn('lic_id', $licIds)
            ->count();
    }
}
