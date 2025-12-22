<?php

namespace App\Services\Sil\ExpedienteGestrad;

use App\Models\ExpedienteGestrad;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ExpedienteGestradService
{
    /**
     * Busca expedientes por similitud en el nombre del recurrente (EXP_NOMREC).
     *
     * @param string $term Término de búsqueda
     * @param int $limit Límite de resultados
     * @return Collection
     */


    protected $connectionToOracle;

    public function __construct()
    {
        // Asegúrate de que 'oracle' esté bien definido en config/database.php
        $this->connectionToOracle = DB::connection('oracle');
    }

    public function getPersona(string $term = ''): Collection
    {
        try {
            Log::info('Service getPersona iniciado', [
                'term' => $term,
                'term_length' => strlen($term),
                'term_empty' => empty($term)
            ]);

            $query = $this->connectionToOracle
                ->table('DS_VALORES.VU_EXPEDIENTE_LIC')
                ->select('EXP_NOMREC', 'EXP_RUC', 'EXP_TELEFONO', 'EXP_EMAIL', 'EXP_DIR', 'EXP_FEC', 'EXP_CODCON');

            if (!empty($term)) {
                // Convertimos el término a mayúsculas para coincidir con el UPPER de SQL
                $termUpper = strtoupper(trim($term));

                Log::info('Aplicando filtro WHERE', [
                    'term_upper' => $termUpper,
                    'pattern' => "%{$termUpper}%"
                ]);

                $query->whereRaw("UPPER(EXP_NOMREC) LIKE ?", ["%{$termUpper}%"]);
            }

            $results = $query->limit(10)->get();

            Log::info('Service getPersona completado', [
                'count' => $results->count(),
                'has_results' => $results->isNotEmpty()
            ]);

            return $results;

        } catch (\Exception $e) {
            Log::error("Error en Service getPersona: " . $e->getMessage(), [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect();
        }
    }

}