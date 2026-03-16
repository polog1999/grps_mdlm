<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $cargos = [
        ['nombre' => 'ALCALDE', 'nombre_corto' => 'ALC', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['nombre' => 'GERENTE MUNICIPAL', 'nombre_corto' => 'GM', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['nombre' => 'GERENTE', 'nombre_corto' => 'GE', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['nombre' => 'SUBGERENTE', 'nombre_corto' => 'SG', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['nombre' => 'SECRETARIO', 'nombre_corto' => 'SC', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['nombre' => 'PROCURADOR', 'nombre_corto' => 'PR', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['nombre' => 'ASISTENTE', 'nombre_corto' => 'AS', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
    ];
        DB::table('visitas.cargos')->insert($cargos);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('visitas.cargos')->delete();
    }
};
