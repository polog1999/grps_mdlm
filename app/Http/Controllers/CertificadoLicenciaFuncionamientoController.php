<?php 
namespace App\Http\Controllers;

use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamiento;
use Illuminate\Http\Request;


class CertificadoLicenciaFuncionamientoController extends Controller
{
   
    protected $service;

    public function __construct(CertificadoLincenciaFuncionamiento $service)
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

    public function obtenerDatosLicenciaFuncionamiento2($codcon)
    {
        $datos = $this->service->obtenerDatosLicenciaFuncionamiento2($codcon);

        if ($datos->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data' => $datos,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos para el código de contacto proporcionado.',
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

        if ($datos->isNotEmpty()) {
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

    public function obtenerViaNombrePorCodVia($codvia)
    {
        // $datos ahora es $row (un objeto) o null
        $datos = $this->service->obtenerViaNombrePorCodVia($codvia); 
        
        if ($datos) { // Comprueba si la FILA fue encontrada
            return response()->json([
                'success' => true,
                'data' => $datos->via_completa, // Esto puede ser null, y está bien
            ]);
        }
        
        // Si $datos es null, significa que la fila no se encontró
        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos para el CODVIA proporcionado.',
        ], 404);
    }
}