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
        Schema::connection('pgsql_licencias')->create('licencia.licencia_levantamiento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lic_id');
            $table->foreignId('id_estado_levantamiento')
                ->constrained('licencia.estado_levantamiento')
                ->onDelete('cascade');
            $table->timestamps();

            // Índice para mejorar búsquedas por licencia
            $table->index('lic_id');

            // Evitar duplicados de la misma licencia con el mismo estado
            $table->unique(['lic_id', 'id_estado_levantamiento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql_licencias')->dropIfExists('licencia.licencia_levantamiento');
    }
};
