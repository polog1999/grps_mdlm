<?php
namespace App\Http\Controllers;

use App\Services\Sil\Licencias\LicenciaService;

class LicenciaCrudController extends Controller
{
    protected $service;
    public function __construct(LicenciaService $service)
    {
        $this->service = $service;
    }

    public function getById($id)
    {
        $items = $this->service->getById($id);
        return response()->json($items);
    }
    public function obtenerDatosDeRazonSocialPorExpediente($expnum)
    {
        $items = $this->service->obtenerDatosDeRazonSocialPorExpediente($expnum);
        return response()->json($items);
    }

    public function obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat)
    {
        $items = $this->service->obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat);
        return response()->json($items);
    }
    //
    public function obtenerDatosDePersonaORazonSocialPorNombre($nombre_razon_social)
    {
        $items = $this->service->obtenerDatosDePersonaORazonSocialPorNombre($nombre_razon_social);
        return response()->json($items);
    }

    //obtenerDatosDeExpedienteParaEditarPorIdLicencia
    public function obtenerDatosDeExpedienteParaEditarPorIdLicencia($lic_id)
    {
        $items = $this->service->obtenerDatosDeExpedienteParaEditarPorIdLicencia($lic_id);
        return response()->json($items);
    }

    //obtenerDatosPorIdLicenciaDirecta
    public function obtenerDatosPorIdLicenciaDirecta($lic_id)
    {
        $items = $this->service->obtenerDatosPorIdLicenciaDirecta($lic_id);
        return response()->json($items);
    }
}