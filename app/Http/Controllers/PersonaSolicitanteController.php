<?php

namespace App\Http\Controllers;

use App\Services\PersonaSolicitante;
use Illuminate\Http\Request;


class PersonaSolicitanteController extends Controller
{
    protected $service;
    
    public function __construct(PersonaSolicitante $service)
    {
        $this->service = $service;
    }

     public function obtenerPorIdSolicitante($per_idsolicitante)
    {
        $resultado = $this->service->obtenerPorIdSolicitante($per_idsolicitante);
        return response()->json($resultado);
    }
}
