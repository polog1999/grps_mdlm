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
        Schema::create('visitas.visitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('visitas.personas');
            // $table->foreignId('sede_id')->constrained('visitas.sedes');
            $table->foreignId('area_id')->constrained('visitas.areas');
            $table->foreignId('trabajador_id_autoriza')->constrained('visitas.trabajadores');
            $table->foreignId('user_id_ingreso')->constrained('users');
            $table->foreignId('user_id_salida')->nullable()->constrained('users');
            $table->string('motivo');
            $table->string('obs')->nullable();
            $table->timestamp('fecha_ingreso');
            $table->timestamp('fecha_salida')->nullable();
            $table->boolean('es_manual')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas.visitas');
    }
};
