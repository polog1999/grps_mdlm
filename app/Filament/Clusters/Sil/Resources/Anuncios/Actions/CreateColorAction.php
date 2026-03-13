<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Actions;

use App\Models\Colores;
use Illuminate\Support\Facades\DB;

class CreateColorAction
{
    public function execute(string $descripcion): Colores
    {
        return DB::transaction(function () use ($descripcion) {
            return Colores::create([
                'descripcion' => strtoupper($descripcion),
            ]);
        });
    }
}