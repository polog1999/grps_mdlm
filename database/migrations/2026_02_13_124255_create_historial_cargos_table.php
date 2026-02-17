<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visitas.historial_cargos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajador_id')->constrained('visitas.trabajadores');
            $table->foreignId('cargo_id')->constrained('visitas.cargos');
            $table->foreignId('area_id')->constrained('visitas.areas');
            // $table->foreignId('sede_id')->constrained('visitas.sedes');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->boolean('es_actual')->default(true);
            $table->smallInteger('estado')->default(1);
            $table->foreignId('user_id_creo')->nullable()->constrained('users');
            $table->foreignId('user_id_modi')->nullable()->constrained('users');
            $table->timestamps();
        });;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas.historial_cargos');
    }
};
