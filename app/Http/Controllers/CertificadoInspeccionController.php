<?php

namespace App\Http\Controllers;

use App\Services\CertificadoInspeccionService;
use Illuminate\Http\Request;

class CertificadoInspeccionController extends Controller
{
    protected $service;

    public function __construct(CertificadoInspeccionService $service)
    {
        $this->service = $service;
    }

    public function buscarUbicacion(Request $request)
    {
        $q = (string) $request->query('q', '');

        if (trim($q) === '') {
            return response()->json([]);
        }

        $items = $this->service->buscarUbicacion($q);
        $options = [];
        foreach ($items as $key => $value) {
            $options[(string) $key] = (string) $value;
        }

        return response()->json($options);
    }
}
