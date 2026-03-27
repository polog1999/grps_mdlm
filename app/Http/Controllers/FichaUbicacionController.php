<?php

namespace App\Http\Controllers;

use App\Services\Sil\Syscat\FichaUbicacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FichaUbicacionController extends Controller
{
    protected $fichaUbicacionService;

    public function __construct(FichaUbicacionService $fichaUbicacionService)
    {
        $this->fichaUbicacionService = $fichaUbicacionService;
    }

    /**
     * Recibe un CODUCA, lo busca en Oracle, cruza los datos con Postgres e inserta o recupera la ficha.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardar(Request $request)
    {
        $coduca = $request->input('coduca');

        if (blank($coduca)) {
            return response()->json([
                'success' => false,
                'message' => 'El código catastral (CODUCA) es obligatorio.'
            ], 400);
        }

        try {
            $ficha = $this->fichaUbicacionService->guardarFichaUbicacion($coduca);

            if ($ficha) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ficha de ubicación procesada con éxito.',
                    'data' => $ficha
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se encontró la información en Oracle o hubo un error en el cruce de datos.'
            ], 404);

        } catch (\Throwable $e) {
            Log::error('FichaUbicacionController: Error al procesar guardar', [
                'coduca' => $coduca,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno al procesar la solicitud.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
