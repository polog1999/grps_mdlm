<?php
namespace App\Http\Controllers;

use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;

use Illuminate\Http\Request;


class CertificadoLicenciaFuncionamientoController extends Controller
{

    protected $service;

    public function __construct(CertificadoLincenciaFuncionamientoService $service)
    {
        $this->service = $service;
    }

    public function obtenerDatosLicenciaFuncionamiento($cinId)
    {
        $datos = $this->service->obtenerDatosLicenciaFuncionamiento($cinId);

        if ($datos->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data' => $datos,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos para la Licencia de Funcionamiento.',
        ], 404);
    }
    public function obtenerCodCatPorExpediente($expnum)
    {
        $datos = $this->service->obtenerCodCatPorExpediente($expnum);

        if ($datos) {
            return response()->json([
                'success' => true,
                'data' => $datos,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos para el número de expediente proporcionado.',
        ], 404);
    }


    //obtenerDatosPorCodCat
    public function obtenerDatosPorCodCat($codcat)
    {
        $datos = $this->service->obtenerDatosPorCodCat($codcat);

        if ($datos) {
            return response()->json([
                'success' => true,
                'data' => $datos,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos para el CODCAT proporcionado.',
        ], 404);
    }

    public function obtenerListaDeProcedimientosTupaDeLicencias()
    {
        $datos = $this->service->obtenerListaDeProcedimientosTupaDeLicencias();

        if ($datos->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data' => $datos->values(), // colección serializable
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos de procedimientos TUPA para licencias.',
        ], 404);
    }

    public function obtenerNivelDeRiesgoPorExpediente($exp_num)
    {
        $datos = $this->service->obtenerNivelDeRiesgoPorExpediente($exp_num);

        if ($datos) {
            return response()->json([
                'success' => true,
                'data' => $datos,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos para el número de expediente proporcionado.',
        ], 404);
    }

    //obtenerDatosCompletosParaRegistrarPorExpediente
    public function obtenerDatosCompletosParaRegistrarPorExpediente($exp_num)
    {
        $datos = $this->service->obtenerDatosCompletosParaRegistrarPorExpediente($exp_num);

        if ($datos) {
            return response()->json([
                'success' => true,
                'data' => $datos,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos para el número de expediente proporcionado.',
        ], 404);
    }


    //obtenerDatosDePersonaPorExpediente
    public function obtenerDatosDePersonaPorExpediente($expnum)
    {
        $items = $this->service->obtenerDatosDePersonaPorExpediente($expnum);
        return response()->json($items);
    }

}