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
    public function obtenerPorNumeroLicenciaYExpediente($lic_numlic, $lic_expnum)
    {
        $items = $this->service->obtenerPorNumeroLicenciaYExpediente($lic_numlic, $lic_expnum);
        return response()->json($items);
    }
}
