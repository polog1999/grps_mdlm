<?php

namespace App\Http\Controllers;
use App\Services\Sil\CertificadoInspeccion\TipoEdificacionService;

use Illuminate\Http\Request;

/**
 * Controlador para gestionar tipos de edificación.
 *
 * Proporciona endpoints públicos simples que retornan listas o catálogos
 * relacionados con los tipos de edificación utilizando el servicio
 * `TipoEdificacionService`.
 */
class TipoEdificacionController extends Controller
{
    /**
     * Servicio que provee métodos para obtener los tipos de edificación.
     *
     * @var TipoEdificacionService
     */
    protected $service;

    /**
     * Constructor.
     *
     * @param TipoEdificacionService $service Servicio inyectado.
     */
    public function __construct(TipoEdificacionService $service)
    {
        $this->service = $service;
    }

    /**
     * Retorna un JSON con la lista de tipos de edificación.
     *
     * Ideal para poblar selects o catálogos en el frontend. El formato
     * depende de la implementación del servicio (array simple, key-value, etc.).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTipoEdificaciones()
    {
        $resultado = $this->service->getTipoEdificaciones();
        return response()->json($resultado);
    }
}
