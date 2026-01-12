<?php

namespace App\Http\Controllers;

use App\Services\Sil\DataLevantamiento\DataLevantamientoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para operaciones de Data Levantamiento
 */
class DataLevantamientoController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct(
        protected DataLevantamientoService $dataLevantamientoService
    ) {
    }

    /**
     * Verifica si existe un SML (código catastral) en la tabla de data_levantamiento_consolida
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function existeSML(Request $request): JsonResponse
    {
        // 1. Validar que 'sml' esté presente y sea un string
        $request->validate([
            'sml' => 'required|string|max:50'
        ]);

        $sml = $request->input('sml');

        try {
            // 2. Ejecutar la lógica del servicio
            $existe = $this->dataLevantamientoService->existeSMLporCodigoCatastral($sml);

            // 3. Respuesta estructurada
            return response()->json([
                'existe' => $existe,
                'sml' => $sml,
                'message' => $existe ? 'Registro encontrado.' : 'El código no existe en el sistema.'
            ], 200);

        } catch (\Exception $e) {
            // 4. Manejo de errores inesperados
            return response()->json([
                'error' => 'Ocurrió un error al procesar la consulta.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
