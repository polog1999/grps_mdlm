<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Actions;

use App\Models\CaracteristicasFisicas;
use Illuminate\Support\Facades\DB;

class CreateCaracteristicasFisicasAction
{
    public function execute(string $descripcion): CaracteristicasFisicas
    {
        return DB::transaction(function () use ($descripcion) {
            return CaracteristicasFisicas::create([
                'descripcion' => strtoupper($descripcion),
            ]);
        });
    }
}