<?php

namespace App\Services\Sil\DataLevantamiento;

use App\Models\DataLevantamientoConsolida;
use App\Models\FichaUbicacionInfocat;
use App\Models\FichaUbicacionSyscat;
use App\Models\LicenciaCatastro;
use App\Models\CertificadoLicenciaFuncionamiento;

/**
 * Servicio para gestionar operaciones relacionadas con DataLevantamientoConsolida
 * y sus relaciones con fichas de ubicación catastral
 */
class DataLevantamientoService
{
    /**
     * Constructor del servicio
     */
    public function __construct(
        protected DataLevantamientoConsolida $dataLevantamiento,
        protected FichaUbicacionInfocat $fichaInfocat,
        protected FichaUbicacionSyscat $fichaSyscat,
        protected LicenciaCatastro $licenciaCatastro,
        protected CertificadoLicenciaFuncionamiento $certificadoLicenciaFuncionamiento
    ) {
    }

    /**
     * Verifica si existe un SML en la tabla de data_levantamiento_consolida
     * 
     * @param string $codcat Código catastral (SML) a buscar
     * @return bool True si existe, False si no existe
     */
    public function existeSMLporCodigoCatastral(string $codcat): bool
    {
        return $this->dataLevantamiento
            ->where('sml', $codcat)
            ->exists();
    }

    public function getLicenciasRelacionadas($sml)
    {
        return $this->certificadoLicenciaFuncionamiento
            ->select([
                'licencia.licencia.lic_id',
                'licencia.licencia.lic_numlic',
                'licencia.licencia.lic_giro',
                'licencia.licencia.tli_id',
                'licencia.licencia.esl_id',
                'licencia.licencia.lic_fechaemision',
                'f.tli_descripcion',
                'esl.esl_descripcion',
                \DB::raw("to_char(licencia.licencia.lic_fechaemision, 'yyyy') as anno"),
                \DB::raw("
                    case 
                        when d.fiu_coduca is null then substring(e.fiu_codcat, 3, 6) 
                        else substring(d.fiu_coduca, 7, 6) 
                    end as codcat
                "),
                'd.fiu_coduca',
                'e.fiu_codcat'
            ])
            ->from('licencia.licencia')
            ->leftJoin('licencia.persona as b', 'licencia.licencia.per_idrazonsocial', '=', 'b.per_id')
            ->leftJoin('licencia.licenciacatastro as c', 'licencia.licencia.lic_id', '=', 'c.lic_id')
            ->leftJoin('licencia.tipolicencia as f', 'licencia.licencia.tli_id', '=', 'f.tli_id')
            ->leftJoin('licencia.estadolicencia as esl', 'licencia.licencia.esl_id', '=', 'esl.esl_id')
            ->leftJoin('syscat.fichaubicacion as d', 'c.fiu_id_syscat', '=', 'd.fiu_id')
            ->leftJoin('infocat.fichaubicacion as e', 'c.fiu_id_infocat', '=', 'e.fiu_id')
            ->whereRaw("
                case 
                    when d.fiu_coduca is null then substring(e.fiu_codcat, 3, 6) 
                    else substring(d.fiu_coduca, 7, 6) 
                end = ?
            ", [$sml])
            ->get();
    }

    /**
     * Obtiene todos los códigos SML que tienen licencias relacionadas
     * 
     * @return array
     */
    public function getSmlsConLicencias()
    {
        $resultados = $this->certificadoLicenciaFuncionamiento
            ->select([
                \DB::raw("
                    case 
                        when d.fiu_coduca is null then substring(e.fiu_codcat, 3, 6) 
                        else substring(d.fiu_coduca, 7, 6) 
                    end as sml
                ")
            ])
            ->from('licencia.licencia')
            ->leftJoin('licencia.licenciacatastro as c', 'licencia.licencia.lic_id', '=', 'c.lic_id')
            ->leftJoin('syscat.fichaubicacion as d', 'c.fiu_id_syscat', '=', 'd.fiu_id')
            ->leftJoin('infocat.fichaubicacion as e', 'c.fiu_id_infocat', '=', 'e.fiu_id')
            ->whereNotNull(\DB::raw("
                case 
                    when d.fiu_coduca is null then substring(e.fiu_codcat, 3, 6) 
                    else substring(d.fiu_coduca, 7, 6) 
                end
            "))
            ->distinct()
            ->get();

        return $resultados->pluck('sml')->filter()->toArray();
    }

    /**
     * Obtiene SMls que tienen una cantidad específica de licencias
     * 
     * @param int $cantidad Cantidad de licencias a buscar
     * @param bool $orMore Si es true, busca $cantidad o más licencias
     * @return array Array de SMls que cumplen la condición
     */
    public function getSmlsPorCantidadLicencias(int $cantidad, bool $orMore = false): array
    {
        // Obtener todos los SMls con su conteo de licencias
        $smlsConConteo = $this->certificadoLicenciaFuncionamiento
            ->select([
                \DB::raw("
                    case 
                        when d.fiu_coduca is null then substring(e.fiu_codcat, 3, 6) 
                        else substring(d.fiu_coduca, 7, 6) 
                    end as sml
                "),
                \DB::raw('COUNT(*) as total_licencias')
            ])
            ->from('licencia.licencia')
            ->leftJoin('licencia.licenciacatastro as c', 'licencia.licencia.lic_id', '=', 'c.lic_id')
            ->leftJoin('syscat.fichaubicacion as d', 'c.fiu_id_syscat', '=', 'd.fiu_id')
            ->leftJoin('infocat.fichaubicacion as e', 'c.fiu_id_infocat', '=', 'e.fiu_id')
            ->whereNotNull(\DB::raw("
                case 
                    when d.fiu_coduca is null then substring(e.fiu_codcat, 3, 6) 
                    else substring(d.fiu_coduca, 7, 6) 
                end
            "))
            ->groupBy(\DB::raw("
                case 
                    when d.fiu_coduca is null then substring(e.fiu_codcat, 3, 6) 
                    else substring(d.fiu_coduca, 7, 6) 
                end
            "))
            ->get();

        // Filtrar según la cantidad
        $smlsFiltrados = $smlsConConteo->filter(function ($item) use ($cantidad, $orMore) {
            if ($orMore) {
                return $item->total_licencias >= $cantidad;
            }
            return $item->total_licencias == $cantidad;
        });

        return $smlsFiltrados->pluck('sml')->filter()->toArray();
    }

    /**
     * Obtiene SMls que tienen una cantidad específica de licencias atendidas
     * 
     * @param int $cantidad Cantidad de licencias atendidas a buscar
     * @param bool $orMore Si es true, busca $cantidad o más licencias atendidas
     * @return array Array de SMls que cumplen la condición
     */
    public function getSmlsPorLicenciasAtendidas(int $cantidad, bool $orMore = false): array
    {
        // Usar una sola consulta SQL para obtener SMls con conteo de licencias atendidas
        $smlsConConteo = $this->certificadoLicenciaFuncionamiento
            ->select([
                \DB::raw("
                    case 
                        when d.fiu_coduca is null then substring(e.fiu_codcat, 3, 6) 
                        else substring(d.fiu_coduca, 7, 6) 
                    end as sml
                "),
                \DB::raw('COUNT(DISTINCT ll.id) as total_atendidas')
            ])
            ->from('licencia.licencia')
            ->leftJoin('licencia.licenciacatastro as c', 'licencia.licencia.lic_id', '=', 'c.lic_id')
            ->leftJoin('syscat.fichaubicacion as d', 'c.fiu_id_syscat', '=', 'd.fiu_id')
            ->leftJoin('infocat.fichaubicacion as e', 'c.fiu_id_infocat', '=', 'e.fiu_id')
            ->leftJoin('licencia.licencia_levantamiento as ll', 'licencia.licencia.lic_id', '=', 'll.lic_id')
            ->whereNotNull(\DB::raw("
                case 
                    when d.fiu_coduca is null then substring(e.fiu_codcat, 3, 6) 
                    else substring(d.fiu_coduca, 7, 6) 
                end
            "))
            ->groupBy(\DB::raw("
                case 
                    when d.fiu_coduca is null then substring(e.fiu_codcat, 3, 6) 
                    else substring(d.fiu_coduca, 7, 6) 
                end
            "))
            ->get();

        // Filtrar según la cantidad
        $smlsFiltrados = $smlsConConteo->filter(function ($item) use ($cantidad, $orMore) {
            if ($orMore) {
                return $item->total_atendidas >= $cantidad;
            }
            return $item->total_atendidas == $cantidad;
        });

        return $smlsFiltrados->pluck('sml')->filter()->toArray();
    }
}
