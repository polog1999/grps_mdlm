<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $lugares = [
            ['nombre' => 'Palacio Municipal', 'aforo' => 208, 'estado' => true,'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'EVENTOS', 'aforo' => 10000, 'estado' => true,'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Oficinas de Recaudación', 'aforo' => 118, 'estado' => true,'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Edificio 2', 'aforo' => 100, 'estado' => true,'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Deposito Municipal MUSA', 'aforo' => 74, 'estado' => true,'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'CSI', 'aforo' => 50, 'estado' => true,'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Complejo Municipal', 'aforo' => 50, 'estado' => true,'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'La Casona (Servicios Generales y la Subgerencia de Deporte)', 'aforo' => 60, 'estado' => true,'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Casa de la Mujer', 'aforo' => 69, 'estado' => true,'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Edificio 1', 'aforo' => 120, 'estado' => true,'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('visitas.sedes')->insert(
            $lugares
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('visitas.sedes')->delete();
    }
};
