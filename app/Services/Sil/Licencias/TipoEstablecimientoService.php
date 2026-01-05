<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;
use App\Models\Mvcatfind;
use App\Models\TipoEstablecimiento;

class TipoEstablecimientoService
{
    protected $connectionToPostgreSQL;
    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function getTipoEstablecimiento()
    {
        return $this->connectionToPostgreSQL
            ->table('licencia.tipoestablecimiento')
            ->select('tes_id', 'tes_descripcion')
            ->where('tes_filaeliminada', false)
            ->whereNotNull('tes_descripcion')
            ->get();
    }

    /**
     * Obtiene el tipo de establecimiento basándose en el código catastral (CODUCA)
     * 
     * Flujo:
     * 1. Busca en Mvcatfind (Oracle) por CODUCA, ordenando por FECHCREA DESC
     * 2. Obtiene el CODUSO más reciente
     * 3. Busca en TipoEstablecimiento (PostgreSQL) por coduso
     * 4. Retorna el tes_id correspondiente
     * 
     * @param string $coduca Código catastral (CODUCA)
     * @return int|null tes_id del tipo de establecimiento, o null si no se encuentra
     */
    public function obtenerTipoEstablecimientoPorCoduca(string $coduca): ?int
    {
        try {
            // Limpiar el código catastral
            $coduca = trim($coduca);

            if (empty($coduca)) {
                return null;
            }

            // 1. Buscar ficha catastral más reciente por CODUCA
            $ficha = Mvcatfind::where('CODUCA', $coduca)
                ->orderBy('FECHCREA', 'desc')
                ->first();

            if (!$ficha) {
                Log::debug("TipoEstablecimientoService: No se encontró ficha para CODUCA: {$coduca}");
                return null;
            }

            // 2. Obtener CODUSO de la ficha
            $coduso = trim($ficha->CODUSO ?? '');

            if (empty($coduso)) {
                Log::debug("TipoEstablecimientoService: Ficha sin CODUSO para CODUCA: {$coduca}");
                return null;
            }

            Log::debug("TipoEstablecimientoService: CODUCA {$coduca} -> CODUSO {$coduso}");

            // 3. Buscar tipo de establecimiento por código de uso
            $tipoEstablecimiento = TipoEstablecimiento::noEliminados()
                ->byCodigoUso($coduso)
                ->first();

            if (!$tipoEstablecimiento) {
                Log::debug("TipoEstablecimientoService: No se encontró TipoEstablecimiento para CODUSO: {$coduso}");
                return null;
            }

            Log::info("TipoEstablecimientoService: Autocompletado exitoso", [
                'coduca' => $coduca,
                'coduso' => $coduso,
                'tes_id' => $tipoEstablecimiento->tes_id,
                'tes_descripcion' => $tipoEstablecimiento->tes_descripcion
            ]);

            return $tipoEstablecimiento->tes_id;

        } catch (\Exception $e) {
            Log::error("TipoEstablecimientoService: Error al obtener tipo establecimiento", [
                'coduca' => $coduca,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}