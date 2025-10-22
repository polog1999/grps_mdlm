<?php

namespace App\Http\Controllers;

use App\Services\LicenciaService;
use Illuminate\Http\Request;

class LicenciaController extends Controller
{
     protected $service;

    public function __construct(LicenciaService $service)
    {
        $this->service = $service;
    }

    // Ruta que devuelve los 10 primeros en JSON
    public function primerosDiez()
    {
        $items = $this->service->obtenerPrimerosDiez();
        return response()->json($items);
    }

    public function obtenerPorNumeroExpediente($lic_numexp)
    {
        $items = $this->service->obtenerPorNumeroExpediente($lic_numexp);
        return response()->json($items);
    }
    public function obtenerPorNumeroLicencia($lic_numlic)
    {
        $items = $this->service->obtenerPorNumeroLicencia($lic_numlic);
        return response()->json($items);
    }
}
