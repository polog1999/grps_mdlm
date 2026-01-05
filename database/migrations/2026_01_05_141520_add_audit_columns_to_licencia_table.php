<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * La conexión de base de datos que debe usar la migración.
     */
    protected $connection = 'pgsql_licencias';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->table('licencia.licencia', function (Blueprint $table) {
            // Auditoría de creación
            $table->integer('lic_creado_por')->nullable()->comment('ID del usuario que creó el registro');
            $table->timestamp('lic_creado_en')->nullable()->comment('Fecha y hora de creación del registro');

            // Auditoría de actualización
            $table->integer('lic_actualizado_por')->nullable()->comment('ID del usuario que actualizó el registro');
            $table->timestamp('lic_actualizado_en')->nullable()->comment('Fecha y hora de última actualización');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->table('licencia.licencia', function (Blueprint $table) {
            $table->dropColumn([
                'lic_creado_por',
                'lic_creado_en',
                'lic_actualizado_por',
                'lic_actualizado_en'
            ]);
        });
    }
};
