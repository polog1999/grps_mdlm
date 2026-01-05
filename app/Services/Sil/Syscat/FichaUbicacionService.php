<?php

namespace App\Services\Sil\Syscat;

use App\Models\FichaUbicacion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para gestionar operaciones relacionadas con FichaUbicacion
 * 
 * Proporciona métodos para consultar y manipular datos de la tabla syscat.fichaubicacion
 */
class FichaUbicacionService
{
    /**
     * Obtiene todos los códigos catastrales (fiu_coduca) únicos
     * 
     * @param bool $soloActivos Si es true, solo retorna registros no eliminados
     * @return Collection Colección de códigos catastrales
     */
    public function listarCodigosCatastrales(bool $soloActivos = true): Collection
    {
        try {
            $query = FichaUbicacion::query()
                ->select('fiu_coduca', 'fiu_id')
                ->distinct()
                ->orderBy('fiu_coduca');

            if ($soloActivos) {
                $query->noEliminados();
            }

            $resultados = $query->get();

            Log::info('FichaUbicacionService: Códigos catastrales obtenidos', [
                'total' => $resultados->count(),
                'solo_activos' => $soloActivos,
            ]);

            return $resultados;

        } catch (\Throwable $e) {
            Log::error('FichaUbicacionService: Error al listar códigos catastrales', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return collect();
        }
    }

    /**
     * Obtiene todas las fichas de ubicación con paginación
     * 
     * @param int $perPage Cantidad de registros por página
     * @param bool $soloActivos Si es true, solo retorna registros no eliminados
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listarFichas(int $perPage = 50, bool $soloActivos = true)
    {
        try {
            $query = FichaUbicacion::query();

            if ($soloActivos) {
                $query->noEliminados();
            }

            return $query->orderBy('fiu_coduca')->paginate($perPage);

        } catch (\Throwable $e) {
            Log::error('FichaUbicacionService: Error al listar fichas', [
                'error' => $e->getMessage(),
            ]);

            return collect()->paginate($perPage);
        }
    }

    /**
     * Busca fichas por código catastral
     * 
     * @param string $coduca Código catastral a buscar
     * @param bool $exacto Si es true, busca coincidencia exacta; si es false, busca coincidencias parciales
     * @return Collection Colección de fichas encontradas
     */
    public function buscarPorCoduca(string $coduca, bool $exacto = false): Collection
    {
        try {
            $query = FichaUbicacion::with(['via', 'urbanizacion'])->noEliminados();

            if ($exacto) {
                $query->where('fiu_coduca', $coduca);
            } else {
                $query->where('fiu_coduca', 'LIKE', "%{$coduca}%");
            }

            $resultados = $query->get();

            Log::info('FichaUbicacionService: Búsqueda por CODUCA', [
                'coduca' => $coduca,
                'exacto' => $exacto,
                'resultados' => $resultados->count(),
            ]);

            return $resultados;

        } catch (\Throwable $e) {
            Log::error('FichaUbicacionService: Error al buscar por CODUCA', [
                'coduca' => $coduca,
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Busca fichas por código predial
     * 
     * @param string $codpre Código predial a buscar
     * @return Collection Colección de fichas encontradas
     */
    public function buscarPorCodigoPredial(string $codpre): Collection
    {
        try {
            $resultados = FichaUbicacion::byCodigoPredial($codpre)
                ->noEliminados()
                ->get();

            Log::info('FichaUbicacionService: Búsqueda por código predial', [
                'codpre' => $codpre,
                'resultados' => $resultados->count(),
            ]);

            return $resultados;

        } catch (\Throwable $e) {
            Log::error('FichaUbicacionService: Error al buscar por código predial', [
                'codpre' => $codpre,
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Obtiene una ficha por su ID
     * 
     * @param int $fiuId ID de la ficha
     * @return FichaUbicacion|null Ficha encontrada o null
     */
    public function obtenerPorId(int $fiuId): ?FichaUbicacion
    {
        try {
            return FichaUbicacion::with(['via', 'urbanizacion'])->find($fiuId);

        } catch (\Throwable $e) {
            Log::error('FichaUbicacionService: Error al obtener ficha por ID', [
                'fiu_id' => $fiuId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Busca fichas con filtros múltiples
     * 
     * @param array $filtros Array de filtros ['campo' => 'valor']
     * @return Collection Colección de fichas encontradas
     */
    public function buscarConFiltros(array $filtros): Collection
    {
        try {
            $query = FichaUbicacion::query()->noEliminados();

            foreach ($filtros as $campo => $valor) {
                if (!empty($valor)) {
                    if (in_array($campo, ['fiu_coduca', 'fiu_codpre', 'fiu_manzana', 'fiu_lote'])) {
                        $query->where($campo, 'LIKE', "%{$valor}%");
                    } else {
                        $query->where($campo, $valor);
                    }
                }
            }

            $resultados = $query->get();

            Log::info('FichaUbicacionService: Búsqueda con filtros', [
                'filtros' => $filtros,
                'resultados' => $resultados->count(),
            ]);

            return $resultados;

        } catch (\Throwable $e) {
            Log::error('FichaUbicacionService: Error al buscar con filtros', [
                'filtros' => $filtros,
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Obtiene estadísticas de las fichas de ubicación
     * 
     * @return array Array con estadísticas
     */
    public function obtenerEstadisticas(): array
    {
        try {
            return [
                'total' => FichaUbicacion::count(),
                'activos' => FichaUbicacion::noEliminados()->count(),
                'eliminados' => FichaUbicacion::where('fiu_filaeliminada', true)->count(),
                'originales' => FichaUbicacion::originales()->count(),
                'modificados' => FichaUbicacion::where('fiu_modificado', true)->count(),
            ];

        } catch (\Throwable $e) {
            Log::error('FichaUbicacionService: Error al obtener estadísticas', [
                'error' => $e->getMessage(),
            ]);

            return [
                'total' => 0,
                'activos' => 0,
                'eliminados' => 0,
                'originales' => 0,
                'modificados' => 0,
            ];
        }
    }
}
