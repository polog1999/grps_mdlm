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
        // Reemplaza 'visitas_municipalidad' por el nombre de tu esquema
        $schemaName = 'visitas';

        // Ejecuta la sentencia SQL directa para PostgreSQL
        DB::statement("CREATE SCHEMA IF NOT EXISTS {$schemaName}");
        
        info("Esquema '{$schemaName}' creado o ya existente.");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $schemaName = 'visitas';

        // Ten cuidado: DROP SCHEMA CASCADE borrará TODO lo que esté adentro
        // Si quieres que sea reversible, descomenta la línea de abajo:
        // DB::statement("DROP SCHEMA IF EXISTS {$schemaName} CASCADE");
    }
};