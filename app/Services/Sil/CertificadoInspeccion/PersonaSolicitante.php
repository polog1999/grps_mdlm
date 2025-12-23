<?php
namespace App\Services\Sil\CertificadoInspeccion;

use App\Models\Persona;

/**
 * Servicio para operaciones relacionadas con Personas Solicitantes.
 *
 * Esta clase maneja consultas usando modelos Eloquent para obtener
 * información de personas solicitantes.
 */
class PersonaSolicitante
{
    /**
     * Obtiene información de una persona solicitante por su ID.
     *
     * Utiliza el modelo Persona para buscar por per_id.
     * Maneja casos de no encontrado o error, retornando un array
     * con 'status' y 'data'.
     *
     * @param mixed $per_idsolicitante ID del solicitante.
     * @return array Resultado de la consulta con status y data.
     */
    public function obtenerPorIdSolicitante($per_idsolicitante)
    {
        try {
            // Buscar persona por ID usando el modelo Eloquent
            $persona = Persona::find($per_idsolicitante);

            if (!$persona) {
                logger()->info("No se encontró ninguna persona con per_id: {$per_idsolicitante}");
                return ['status' => 'no_encontrado', 'data' => null];
            }

            return ['status' => 'ok', 'data' => $persona];
        } catch (\Throwable $e) {
            logger()->error('Error al consultar persona solicitante: ' . $e->getMessage());
            return ['status' => 'error', 'data' => null];
        }
    }
}
