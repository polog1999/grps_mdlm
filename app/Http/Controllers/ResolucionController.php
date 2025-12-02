<?php

namespace App\Http\Controllers;
use App\Services\Sil\CertificadoInspeccion\ResolucionService;

use Illuminate\Http\Request;

class ResolucionController extends Controller
{

    protected $service;


    public function __construct(ResolucionService $service)
    {
        $this->service = $service;
    }


    public function obtenerNumeroExpedientePorNumeroResolucion($num_res)
    {
        $resultado = $this->service->obtenerNumeroExpedientePorNumeroResolucion($num_res);
        return response()->json($resultado);
    }

    public function obtenerNumeroResolucionPorNumeroExpediente($num_exp)
    {
        $resultado = $this->service->obtenerNumeroResolucionPorNumeroExpediente($num_exp);
        return response()->json($resultado);
    }
}
