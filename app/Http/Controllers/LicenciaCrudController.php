<?php
namespace App\Http\Controllers;

use App\Services\Sil\Licencias\LicenciaService;

class LicenciaCrudController extends Controller{
    protected $service;
    public function __construct(LicenciaService $service)
    {
        $this->service = $service;
    }

    public function obtenerDatosDeRazonSocialPorExpediente($expnum){
        $items = $this->service->obtenerDatosDeRazonSocialPorExpediente($expnum);
        return response()->json($items);
    }

    public function obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat){
        $items = $this->service->obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat);
        return response()->json($items);
    }
}