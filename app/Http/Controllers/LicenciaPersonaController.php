<?php

namespace App\Http\Controllers;
use App\Services\Sil\Licencias\LicenciaPersonaService;

use Illuminate\Http\Request;


class LicenciaPersonaController extends Controller
{
    protected $service;
    
    public function __construct(LicenciaPersonaService $service)
    {
        $this->service = $service;
    }

    public function getLicenciaPersonaNombre()
    {
        $resultado = $this->service->getLicenciaPersonaNombre();
        return response()->json($resultado);
    }
}
