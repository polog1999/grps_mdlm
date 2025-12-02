<?php

namespace App\Http\Controllers;

use App\Services\Sil\CertificadoInspeccion\PersonaSolicitante;
use Illuminate\Http\Request;


/**
 * Controlador para operaciones relacionadas con Personas Solicitantes.
 *
 * Este controlador expone endpoints simples que delegan en el servicio
 * `PersonaSolicitante` para recuperar información de solicitantes por ID.
 */
class PersonaSolicitanteController extends Controller
{
    /**
     * Servicio que maneja la lógica de persona solicitante.
     *
     * @var PersonaSolicitante
     */
    protected $service;

    /**
     * Constructor.
     *
     * @param PersonaSolicitante $service Servicio inyectado para operaciones sobre solicitantes.
     */
    public function __construct(PersonaSolicitante $service)
    {
        $this->service = $service;
    }

    /**
     * Obtiene la información de un solicitante por su ID.
     *
     * Devuelve un JSON con la estructura que provee el servicio. Se espera que
     * el servicio retorne un arreglo o modelo serializable.
     *
     * @param int|string $per_idsolicitante Identificador del solicitante.
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPorIdSolicitante($per_idsolicitante)
    {
        $resultado = $this->service->obtenerPorIdSolicitante($per_idsolicitante);
        return response()->json($resultado);
    }
}
