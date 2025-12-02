<?php

namespace App\Http\Controllers;
use App\Services\Sil\Licencias\TipoLocalService;

use Illuminate\Http\Request;


class TipoLocalController extends Controller
{
    protected $service;

    public function __construct(TipoLocalService $service)
    {
        $this->service = $service;
    }

    public function getTipoLocal()
    {
        $resultado = $this->service->getTipoLocal();
        return response()->json($resultado);
    }
}
