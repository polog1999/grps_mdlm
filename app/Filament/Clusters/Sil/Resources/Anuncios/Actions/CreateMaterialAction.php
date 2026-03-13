<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Actions;

use App\Models\Materiales;
use Illuminate\Support\Facades\DB;

class CreateMaterialAction
{
    public function execute(string $descripcion): Materiales
    {
        return DB::transaction(function () use ($descripcion) {
            return Materiales::create([
                'descripcion' => strtoupper($descripcion),
            ]);
        });
    }
}