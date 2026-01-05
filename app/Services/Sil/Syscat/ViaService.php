<?php

namespace App\Services\Sil\Syscat;

use App\Models\Via;
use App\Models\ViaTipo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Servicio para gestionar operaciones relacionadas con Vías
 * 
 * Proporciona métodos para consultar y manipular datos de la tabla syscat.via
 */
class ViaService
{
    /**
     * Obtiene una vía con su tipo, formateando el nombre completo
     * Replica: SELECT v.via_id, v.via_codvia, (vt.vit_abretipvia || ' ' || v.via_descvia) AS via_completa
     * 
     * @param string $codvia Código de vía a buscar
     * @return object|null Objeto con via_id, via_codvia y via_completa
     */
    public function obtenerViaCompletaPorCodigo(string $codvia): ?object
    {
        try {
            $via = Via::with('viaTipo')
                ->where('via_codvia', $codvia)
                ->noEliminados()
                ->first();

            if (!$via) {
                Log::info('ViaService: Vía no encontrada', ['codvia' => $codvia]);
                return null;
            }

            $viaCompleta = $this->formatearViaCompleta($via);

            Log::info('ViaService: Vía obtenida', [
                'codvia' => $codvia,
                'via_completa' => $viaCompleta,
            ]);

            return (object) [
                'via_id' => $via->via_id,
                'via_codvia' => $via->via_codvia,
                'via_completa' => $viaCompleta,
            ];

        } catch (\Throwable $e) {
            Log::error('ViaService: Error al obtener vía completa', [
                'codvia' => $codvia,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Busca vías por código (parcial o exacto)
     * 
     * @param string $codvia Código de vía a buscar
     * @param bool $exacto Si es true, busca coincidencia exacta
     * @return Collection Colección de vías con formato completo
     */
    public function buscarViasPorCodigo(string $codvia, bool $exacto = false): Collection
    {
        try {
            $query = Via::with('viaTipo')->noEliminados();

            if ($exacto) {
                $query->where('via_codvia', $codvia);
            } else {
                $query->where('via_codvia', 'LIKE', "%{$codvia}%");
            }

            $vias = $query->get();

            return $vias->map(function ($via) {
                return (object) [
                    'via_id' => $via->via_id,
                    'via_codvia' => $via->via_codvia,
                    'via_completa' => $this->formatearViaCompleta($via),
                    'via_descvia' => $via->via_descvia,
                    'via_descvia2' => $via->via_descvia2,
                    'vit_abretipvia' => $via->viaTipo->vit_abretipvia ?? null,
                ];
            });

        } catch (\Throwable $e) {
            Log::error('ViaService: Error al buscar vías por código', [
                'codvia' => $codvia,
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Busca vías por descripción
     * 
     * @param string $descripcion Descripción a buscar
     * @return Collection Colección de vías con formato completo
     */
    public function buscarViasPorDescripcion(string $descripcion): Collection
    {
        try {
            $vias = Via::with('viaTipo')
                ->byDescripcion($descripcion)
                ->noEliminados()
                ->get();

            return $vias->map(function ($via) {
                return (object) [
                    'via_id' => $via->via_id,
                    'via_codvia' => $via->via_codvia,
                    'via_completa' => $this->formatearViaCompleta($via),
                    'via_descvia' => $via->via_descvia,
                    'via_descvia2' => $via->via_descvia2,
                    'vit_abretipvia' => $via->viaTipo->vit_abretipvia ?? null,
                ];
            });

        } catch (\Throwable $e) {
            Log::error('ViaService: Error al buscar vías por descripción', [
                'descripcion' => $descripcion,
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Lista todas las vías con su tipo
     * 
     * @param int $limit Límite de resultados (0 = sin límite)
     * @return Collection Colección de vías con formato completo
     */
    public function listarViasCompletas(int $limit = 100): Collection
    {
        try {
            $query = Via::with('viaTipo')
                ->noEliminados()
                ->orderBy('via_descvia');

            if ($limit > 0) {
                $query->limit($limit);
            }

            $vias = $query->get();

            return $vias->map(function ($via) {
                return (object) [
                    'via_id' => $via->via_id,
                    'via_codvia' => $via->via_codvia,
                    'via_completa' => $this->formatearViaCompleta($via),
                ];
            });

        } catch (\Throwable $e) {
            Log::error('ViaService: Error al listar vías completas', [
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Obtiene una vía por ID con su tipo
     * 
     * @param int $viaId ID de la vía
     * @return object|null Objeto con información completa de la vía
     */
    public function obtenerViaPorId(int $viaId): ?object
    {
        try {
            $via = Via::with('viaTipo')->find($viaId);

            if (!$via) {
                return null;
            }

            return (object) [
                'via_id' => $via->via_id,
                'via_codvia' => $via->via_codvia,
                'via_completa' => $this->formatearViaCompleta($via),
                'via_descvia' => $via->via_descvia,
                'via_descvia2' => $via->via_descvia2,
                'vit_abretipvia' => $via->viaTipo->vit_abretipvia ?? null,
                'vit_desctipvia' => $via->viaTipo->vit_desctipvia ?? null,
            ];

        } catch (\Throwable $e) {
            Log::error('ViaService: Error al obtener vía por ID', [
                'via_id' => $viaId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Busca vías con filtros múltiples
     * 
     * @param array $filtros Array de filtros
     * @return Collection Colección de vías encontradas
     */
    public function buscarConFiltros(array $filtros): Collection
    {
        try {
            $query = Via::with('viaTipo')->noEliminados();

            if (!empty($filtros['via_codvia'])) {
                $query->where('via_codvia', 'LIKE', "%{$filtros['via_codvia']}%");
            }

            if (!empty($filtros['via_descvia'])) {
                $query->where('via_descvia', 'LIKE', "%{$filtros['via_descvia']}%");
            }

            if (!empty($filtros['vit_id'])) {
                $query->where('vit_id', $filtros['vit_id']);
            }

            if (!empty($filtros['via_codtipvia'])) {
                $query->where('via_codtipvia', $filtros['via_codtipvia']);
            }

            $vias = $query->get();

            return $vias->map(function ($via) {
                return (object) [
                    'via_id' => $via->via_id,
                    'via_codvia' => $via->via_codvia,
                    'via_completa' => $this->formatearViaCompleta($via),
                    'via_descvia' => $via->via_descvia,
                ];
            });

        } catch (\Throwable $e) {
            Log::error('ViaService: Error al buscar con filtros', [
                'filtros' => $filtros,
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Formatea el nombre completo de la vía
     * Replica: (vt.vit_abretipvia || ' ' || v.via_descvia)
     * 
     * @param Via $via Modelo de vía con relación viaTipo cargada
     * @return string Nombre completo formateado
     */
    private function formatearViaCompleta(Via $via): string
    {
        $abreviatura = $via->viaTipo->vit_abretipvia ?? '';
        $descripcion = $via->via_descvia ?? '';

        if ($abreviatura && $descripcion) {
            return $abreviatura . ' ' . $descripcion;
        }

        return $descripcion ?: $abreviatura;
    }

    /**
     * Obtiene estadísticas de vías
     * 
     * @return array Array con estadísticas
     */
    public function obtenerEstadisticas(): array
    {
        try {
            return [
                'total' => Via::count(),
                'activas' => Via::noEliminados()->count(),
                'eliminadas' => Via::where('via_filaeliminada', true)->count(),
                'por_tipo' => Via::with('viaTipo')
                    ->noEliminados()
                    ->get()
                    ->groupBy('viaTipo.vit_desctipvia')
                    ->map(fn($group) => $group->count())
                    ->toArray(),
            ];

        } catch (\Throwable $e) {
            Log::error('ViaService: Error al obtener estadísticas', [
                'error' => $e->getMessage(),
            ]);

            return [
                'total' => 0,
                'activas' => 0,
                'eliminadas' => 0,
                'por_tipo' => [],
            ];
        }
    }
}
