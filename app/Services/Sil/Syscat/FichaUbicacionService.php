<?php

namespace App\Services\Sil\Syscat;

use App\Models\FichaUbicacion;
use App\Models\Urbanizacion;
use App\Models\Via;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Services\Sil\Syscat\OracleFichaUbicacionService;
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
            $query = FichaUbicacion::with(['via.viatipo', 'urbanizacion'])->noEliminados();

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

    public function obtenerUbicacionPorCoduca(string $coduca)
    {
        try {
            $oracleService = new OracleFichaUbicacionService();
            $resultado = $oracleService->obtenerUbicacionPorCoduca($coduca);

            $codUrbOracle = $resultado->CODURB ?? $resultado->codurb ?? null;
            $codViaOracle = $resultado->CODVIA ?? $resultado->codvia ?? null;
            if (!empty($codUrbOracle)) {
                $codUrbLimpio = trim($codUrbOracle);
                $urbanizacion = Urbanizacion::where('urb_codurb', $codUrbLimpio)->first();
                $resultado->urb_id = $urbanizacion ? $urbanizacion->urb_id : null;
                $resultado->urb_zonacodi = $urbanizacion ? $urbanizacion->urb_zonacodi : null;
                Log::info('Cruce de Urbanización', [
                    'codurb_crudo' => "'{$codUrbOracle}'",
                    'codurb_limpio' => "'{$codUrbLimpio}'",
                    'encontro_urb_id' => $resultado->urb_id,
                    'encontro_zonacodi' => $resultado->urb_zonacodi
                ]);
            }
            if (!empty($codViaOracle)) {
                $codViaLimpio = trim($codViaOracle);
                $via = Via::where('via_codvia', $codViaLimpio)->first();
                $resultado->via_id = $via ? $via->via_id : null;
                $resultado->via_viascodi = $via ? $via->via_viascodi : null;
                Log::info('Cruce de Vía', [
                    'codvia_crudo' => "'{$codViaOracle}'",
                    'codvia_limpio' => "'{$codViaLimpio}'",
                    'encontro_via_id' => $resultado->via_id,
                    'encontro_viascodi' => $resultado->via_viascodi
                ]);
            }
            return $resultado;
        } catch (\Throwable $e) {
            \Log::error('FichaUbicacionService: Error al obtener ubicación por CODUCA', [
                'coduca' => $coduca,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    protected function mapearDatosFichaUbicacion($resultado): array
    {
        if (!$resultado) {
            return [];
        }

        return [
            'urb_id' => $resultado->urb_id ?? null,
            'via_id' => $resultado->via_id ?? null,
            'fiu_coduca' => $resultado->coduca ?? null,
            'fiu_secuca' => $resultado->secuca ?? null,
            'fiu_codvia' => $resultado->codvia ?? null,
            'fiu_numvia' => $resultado->numvia ?? null,
            'fiu_intdpto' => $resultado->intdpto ?? null,
            'fiu_blockedif' => $resultado->blockedif ?? null,
            'fiu_codtippue' => $resultado->codtippue ?? null,
            'fiu_codubifre' => $resultado->codubifre ?? null,
            'fiu_codurb' => $resultado->codurb ?? null,
            'fiu_manzana' => $resultado->mz ?? null,
            'fiu_lote' => $resultado->lote ?? null,
            'fiu_zonificacion' => $resultado->zonificac ?? null,
            'fiu_areaeconomica' => $resultado->areadecl ?? null,
            'fiu_viascodi' => $resultado->via_viascodi ?? null,
            'fiu_zonacodi' => $resultado->urb_zonacodi ?? null,
            'fiu_filaoriginal' => true,
            'fiu_filaeliminada' => false,
            'fiu_codpre' => $resultado->codpredio ?? null,
            'fiu_modificado' => false,
            'fiu_secubipre' => $resultado->secubipre ?? null,


        ];
    }

    /**
     * Obtiene los datos de Oracle, los cruza con Postgres y los guarda en la tabla fichaubicacion
     */
    public function guardarFichaUbicacion(string $coduca)
    {
        try {
            // 1. Obtener datos frescos de Oracle
            $resultado = $this->obtenerUbicacionPorCoduca($coduca);

            if (!$resultado) {
                Log::warning("No se pudo procesar: CODUCA {$coduca} no encontrado en Oracle.");
                return null;
            }

            // 2. Mapear los datos al formato de Postgres (sin el ID aún)
            $datosNuevos = $this->mapearDatosFichaUbicacion($resultado);

            // 3. VALIDACIÓN DE DUPLICIDAD TOTAL
            // Quitamos los campos que no queremos comparar (como los booleanos por defecto)
            $criteriosComparacion = collect($datosNuevos)->except([
                'fiu_filaoriginal',
                'fiu_filaeliminada',
                'fiu_modificado'
            ])->toArray();

            // Buscamos si existe UN registro que tenga EXACTAMENTE esos mismos valores
            $fichaIdentica = FichaUbicacion::where($criteriosComparacion)->first();

            if ($fichaIdentica) {
                Log::info("Se omitió la inserción: Ya existe un registro idéntico en la base de datos.", [
                    'fiu_id' => $fichaIdentica->fiu_id,
                    'coduca' => $coduca
                ]);
                return $fichaIdentica;
            }

            // 4. Si el registro es diferente o no existe, procedemos a insertar
            // Calculamos el ID manual para evitar el error de Primary Key nula
            $maxId = FichaUbicacion::max('fiu_id') ?? 0;
            $datosNuevos['fiu_id'] = $maxId + 1;

            $nuevaFicha = FichaUbicacion::create($datosNuevos);

            Log::info("Nueva Ficha de Ubicación creada (datos actualizados o nuevos)", [
                'fiu_id' => $nuevaFicha->fiu_id,
                'coduca' => $coduca
            ]);

            return $nuevaFicha;

        } catch (\Throwable $e) {
            Log::error('Error en validación e inserción de FichaUbicacion', [
                'coduca' => $coduca,
                'mensaje' => $e->getMessage()
            ]);
            return null;
        }
    }
}
