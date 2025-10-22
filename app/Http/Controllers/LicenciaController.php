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
}
