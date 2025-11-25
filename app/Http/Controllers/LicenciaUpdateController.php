<?php

namespace App\Http\Controllers;
use App\Services\Sil\Licencias\LicenciaUpdateService;

use Illuminate\Http\Request;


class LicenciaUpdateController extends Controller
{
    protected $service;
    
    public function __construct(LicenciaUpdateService $service)
    {
        $this->service = $service;
    }

    public function obtenerPorIdLicencia($lic_id)
    {
        $items = $this->service->obtenerPorIdLicencia($lic_id);
        return response()->json($items);
    }
}