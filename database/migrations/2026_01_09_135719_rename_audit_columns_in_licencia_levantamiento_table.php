<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    protected $connection = 'pgsql_licencias';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renombrar columnas de auditoría a inglés
        DB::connection('pgsql_licencias')->statement('ALTER TABLE licencia.licencia_levantamiento RENAME COLUMN creado_por TO created_by');
        DB::connection('pgsql_licencias')->statement('ALTER TABLE licencia.licencia_levantamiento RENAME COLUMN actualizado_por TO updated_by');

        // Eliminar columnas de timestamp duplicadas (ya existen created_at y updated_at)
        Schema::connection('pgsql_licencias')->table('licencia.licencia_levantamiento', function (Blueprint $table) {
            $table->dropColumn(['creado_en', 'actualizado_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrear columnas de timestamp
        Schema::connection('pgsql_licencias')->table('licencia.licencia_levantamiento', function (Blueprint $table) {
            $table->timestamp('creado_en')->nullable()->after('created_by');
            $table->timestamp('actualizado_en')->nullable()->after('updated_by');
        });

        // Renombrar columnas de vuelta a español
        DB::connection('pgsql_licencias')->statement('ALTER TABLE licencia.licencia_levantamiento RENAME COLUMN created_by TO creado_por');
        DB::connection('pgsql_licencias')->statement('ALTER TABLE licencia.licencia_levantamiento RENAME COLUMN updated_by TO actualizado_por');
    }
};
