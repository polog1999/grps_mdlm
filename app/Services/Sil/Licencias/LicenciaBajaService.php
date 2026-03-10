<?php

namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class LicenciaBajaService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function bajaLicencia(array $data)
    {
        \Log::info('LicenciaBajaService::bajaLicencia - Iniciando proceso de baja de licencia', [
            'lic_id' => $data['lic_id'] ?? null,
            'lib_expnum' => $data['lib_expnum'] ?? null,
            'lib_anexo' => $data['lib_anexo'] ?? null,
            'lib_resnum' => $data['lib_resnum'] ?? null,
            'lib_fecharesolucion' => $data['lib_fecharesolucion'] ?? null,
            'lib_fechabaja' => $data['lib_fechabaja'] ?? null,
            'lib_id' => $data['lib_id'] ?? 0,
            'user_id' => Auth::id(),
        ]);

        try {
            $sql = "SELECT * FROM licencia.spu_licenciabaja_ins2(
                :lic_id,
                :p_lib_expnum,
                :p_lib_anexo,
                :p_lib_resnum,
                :p_lib_fecharesolucion,
                :p_lib_fechabaja,
                :p_lib_id,
                :p_user_id::integer,
                :p_fecha_operacion::timestamp
            )";

            $bindings = [
                'lic_id' => $data['lic_id'],
                'p_lib_expnum' => $data['lib_expnum'],
                'p_lib_anexo' => $data['lib_anexo'],
                'p_lib_resnum' => $data['lib_resnum'],
                'p_lib_fecharesolucion' => Carbon::parse($data['lib_fecharesolucion'])->format('d/m/Y'),
                'p_lib_fechabaja' => Carbon::parse($data['lib_fechabaja'])->format('d/m/Y'),
                'p_lib_id' => $data['lib_id'] ?? 0,
                'p_user_id' => Auth::id() ?? 0,
                'p_fecha_operacion' => now()->format('Y-m-d H:i:s'),
            ];

            \Log::info('LicenciaBajaService::bajaLicencia - Ejecutando stored procedure', [
                'sql' => $sql,
                'bindings' => $bindings
            ]);

            $resultado = $this->connectionToPostgreSQL->selectOne($sql, $bindings);

            \Log::info('LicenciaBajaService::bajaLicencia - Resultado del stored procedure', [
                'resultado' => $resultado,
                'error' => $resultado->error ?? null,
                'mensaje' => $resultado->mensaje ?? null
            ]);

            // SINCRONIZACIÓN DE ANUNCIOS:
            // Si el procedimiento almacenado fue éxitoso (en el log vemos que error = 3197 es "Registro se ha grabado satisfactoriamente"),
            // procedemos a dar de baja los anuncios.
            if (isset($resultado->error) && $resultado->error > 0) {
                // Importamos modelos dinámicamente si no están arriba
                $updatedCount = \App\Models\Anuncios::where('id_licencia', $data['lic_id'])
                    ->where('estado_anuncio', '!=', \App\Filament\Clusters\Sil\Resources\Anuncios\Enums\EstadoAnuncio::BAJA->value)
                    ->update(['estado_anuncio' => \App\Filament\Clusters\Sil\Resources\Anuncios\Enums\EstadoAnuncio::BAJA->value]);

                \Log::info("LicenciaBajaService::bajaLicencia - Sincronización Anuncios BAJA", [
                    'lic_id' => $data['lic_id'],
                    'anuncios_actualizados' => $updatedCount
                ]);
            }

            return $resultado;

        } catch (\Exception $e) {
            \Log::error('LicenciaBajaService::bajaLicencia - Error en el proceso', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

            throw $e;
        }
    }
}