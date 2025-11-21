<?php

namespace App\Http\Controllers;
use App\Services\Sil\Licencias\GiroLicenciaService;

use Illuminate\Http\Request;


class GiroLicenciaController extends Controller
{

    protected $service;
    
    public function __construct(GiroLicenciaService $service)
    {
        $this->service = $service;
    }
   public function listar()
    {
        $giros = $this->service->obtenerTodosLosGiros();
        $giros = $giros->map(function ($giro) {
            return [
                'gir_id' => $giro->gir_id,
                'gir_descripcion' => $giro->gir_descripcion

            ];
        });
        return response()->json($giros);
    }

    public function buscar($search) 
    {
        $giros = $this->service->buscarGiros($search);
        $giros = $giros->map(function ($giro) {
            return [
                'gir_id' => $giro->gir_id,
                'gir_descripcion' => $giro->gir_descripcion
            ];
        });

        return response()->json($giros);
    }

}
