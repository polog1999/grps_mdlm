<?php

namespace App\Services\Sil\Licencias;

use App\Models\LicenciaCatastro;
use Illuminate\Support\Facades\Log;

class LicenciaCatastroService
{
    public function obtenerPorLicId(int $id): ?LicenciaCatastro
    {
        try {
            // Buscamos el primer registro que coincida con el lic_id proporcionado
            return LicenciaCatastro::where('lic_id', $id)->first();

        } catch (\Exception $e) {
            // Registramos el error en los logs para depuración técnica
            Log::error("Error en LicenciaCatastroService@obtenerPorLicId: " . $e->getMessage(), [
                'lic_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function obtenerIdFichaUbicacion(int $licId): ?int
    {
        $registro = $this->obtenerPorLicId($licId);

        if (!$registro) {
            return null;
        }

        // Si fiu_id_syscat es 0 o null, usamos fiu_id_infocat
        if (empty($registro->fiu_id_syscat)) {
            return $registro->fiu_id_infocat;
        }

        return $registro->fiu_id_syscat;
    }
}