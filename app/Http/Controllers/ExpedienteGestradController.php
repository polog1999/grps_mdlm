<?php

namespace App\Http\Controllers;

use App\Services\Sil\ExpedienteGestrad\ExpedienteGestradService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExpedienteGestradController extends Controller
{
    protected ExpedienteGestradService $expedienteService;

    public function __construct(ExpedienteGestradService $expedienteService)
    {
        $this->expedienteService = $expedienteService;
    }

    /**
     * Obtiene la lista de personas desde la tabla DUR_EXPEDIENTE.
     *
     * @param string $term Término de búsqueda (opcional)
     * @return JsonResponse
     */
    public function getPersona(string $term = ''): JsonResponse
    {
        Log::info('Controller getPersona llamado', ['term' => $term]);

        $personas = $this->expedienteService->getPersona($term);

        return response()->json([
            'success' => true,
            'data' => $personas->map(function ($item) {
                $obj = array_change_key_case((array) $item, CASE_UPPER);
                return [
                    'nombre' => trim($obj['EXP_NOMREC'] ?? ''),
                    'ruc' => trim($obj['EXP_RUC'] ?? ''),
                    'codigo' => trim($obj['EXP_CODCON'] ?? ''),
                    'telefono' => trim($obj['EXP_TELEFONO'] ?? ''),
                    'email' => trim($obj['EXP_EMAIL'] ?? ''),
                    'direccion' => trim($obj['EXP_DIR'] ?? ''),
                    'fecha' => trim($obj['EXP_FEC'] ?? ''),
                ];
            }),
            'count' => $personas->count(),
        ]);
    }
}
