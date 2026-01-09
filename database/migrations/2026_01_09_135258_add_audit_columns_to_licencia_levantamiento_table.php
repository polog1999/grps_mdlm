<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'pgsql_licencias';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('pgsql_licencias')->table('licencia.licencia_levantamiento', function (Blueprint $table) {
            // Columnas de auditoría de creación
            $table->unsignedBigInteger('creado_por')->nullable()->after('id_estado_levantamiento');
            $table->timestamp('creado_en')->nullable()->after('creado_por');

            // Columnas de auditoría de actualización
            $table->unsignedBigInteger('actualizado_por')->nullable()->after('creado_en');
            $table->timestamp('actualizado_en')->nullable()->after('actualizado_por');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql_licencias')->table('licencia.licencia_levantamiento', function (Blueprint $table) {
            $table->dropColumn([
                'creado_por',
                'creado_en',
                'actualizado_por',
                'actualizado_en'
            ]);
        });
    }
};
