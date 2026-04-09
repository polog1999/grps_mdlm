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
        $tipos_documento = [
            [
                'nombre' => 'Documento de identidad',
                'nombre_corto' => 'DNI',
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Libreta Electoral',
                'nombre_corto' => 'LE',
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Carnet de Extranjería',
                'nombre_corto' => 'CE',
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Pasaporte',
                'nombre_corto' => 'PASS',
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Carnet de Indentidad',
                'nombre_corto' => 'CI',
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Registro Único de Contribuyentes',
                'nombre_corto' => 'RUC',
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('visitas.tipo_documentos')->insert($tipos_documento);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        DB::table('visitas.tipo_documentos')->delete();
    }
};
