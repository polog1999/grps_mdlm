<?php

namespace App\Services\Sil\Infocat;

use App\Models\FichaUbicacionInfocat;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


class FichaUbicacionService
{

    public function obtenerPorCoduca(string $coduca): ?FichaUbicacionInfocat
    {
        try {
            $ficha = FichaUbicacionInfocat::where('fiu_codcat', $coduca)->first();
            return $ficha;
        } catch (\Exception $e) {
            Log::error('Error al obtener ficha por coduca: ' . $e->getMessage());
            return null;
        }
    }

    public function obtenerPorId(int $id): ?FichaUbicacionInfocat
    {
        try {
            $ficha = FichaUbicacionInfocat::where('fiu_id', $id)->first();
            return $ficha;
        } catch (\Exception $e) {
            Log::error('Error al obtener ficha por id: ' . $e->getMessage());
            return null;
        }
    }

}