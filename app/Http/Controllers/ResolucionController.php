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

    
    public function obtenerNumeroExpedientePorNumeroResolucion()
    {
        $resultado = $this->service->obtenerNumeroExpedientePorNumeroResolucion();
        return response()->json($resultado);
    }
}
