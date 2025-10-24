<?php

namespace App\Http\Controllers;
use App\Services\TipoEdificacionService;

use Illuminate\Http\Request;

class TipoEdificacionController extends Controller
{
        protected $service;
    
    public function __construct(TipoEdificacionService $service)
    {
        $this->service = $service;
    }

    public function getTipoEdificaciones()
    {
        $resultado = $this->service->getTipoEdificaciones();
        return response()->json($resultado);
    }
}
