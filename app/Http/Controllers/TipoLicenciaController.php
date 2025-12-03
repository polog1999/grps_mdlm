<?php

namespace App\Http\Controllers;
use App\Services\Sil\Licencias\TipoLicencia;

use Illuminate\Http\Request;


class TipoLicenciaController extends Controller
{

    protected $service;

    public function __construct(TipoLicencia $service)
    {
        $this->service = $service;
    }

    public function getTipoLicencias()
    {
        $resultado = $this->service->getTipoLicencias();
        return response()->json($resultado);
    }
}
