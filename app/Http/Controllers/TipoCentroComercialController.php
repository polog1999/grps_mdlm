<?php

namespace App\Http\Controllers;
use App\Services\Sil\Licencias\TipoCentroComercialService;

use Illuminate\Http\Request;


class TipoCentroComercialController extends Controller
{
    protected $service;
    
    public function __construct(TipoCentroComercialService $service)
    {
        $this->service = $service;
    }

    public function getTipoCentroComercial()
    {
        $resultado = $this->service->getTipoCentroComercial();
        return response()->json($resultado);
    }
}
