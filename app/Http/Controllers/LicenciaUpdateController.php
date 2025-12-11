<?php

namespace App\Http\Controllers;
use App\Services\Sil\Licencias\LicenciaService;

use Illuminate\Http\Request;


class LicenciaUpdateController extends Controller
{
    protected $service;

    public function __construct(LicenciaService $service)
    {
        $this->service = $service;
    }

    public function obtenerPorIdLicencia($lic_id)
    {
        $items = $this->service->getById($lic_id);
        return response()->json($items);
    }
}