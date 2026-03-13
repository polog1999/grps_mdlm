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
        $regimenes = [
            ['parent_id' => null, 'cregimen' => 'D.L N° 276', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'cregimen' => 'D.L N° 728', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'cregimen' => 'D.L N° 157', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'cregimen' => 'D.L N° 1057', 'de_regimen' => 'CONTRATO ADMINISTRATIVO DE SERVICIOS', 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['parent_id' => 5, 'cregimen' => 'PROVEEDOR', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => 6, 'cregimen' => 'PRACTICANTE', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => 7, 'cregimen' => 'D.L. N° 30057', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['parent_id' => 1, 'cregimen' => 'D.L N° 276 - JUDICIALIZADOS', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => 1, 'cregimen' => 'D.L N° 276 - NOMBRADOS', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['parent_id' => 2, 'cregimen' => 'D.L N° 728 - CONTRATADOS', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => 2, 'cregimen' => 'D.L N° 728 - NOMBRADOS', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['parent_id' => 4, 'cregimen' => 'D.L N° 1057 - FUNCIONARIOS', 'de_regimen' => 'CONTRATO ADMINISTRATIVO DE SERVICIOS', 'nu_tasa_impuesto' => 0.08, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => 4, 'cregimen' => 'D.L N° 1057 - EMPLEADOS', 'de_regimen' => 'CONTRATO ADMINISTRATIVO DE SERVICIOS', 'nu_tasa_impuesto' => 0.08, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['parent_id' => 8, 'cregimen' => 'PENSIONISTAS', 'de_regimen' => null, 'nu_tasa_impuesto' => null, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('visitas.regimenes')->insert($regimenes);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('visitas.regimenes')->delete();
    }
};
