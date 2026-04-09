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
        Schema::create('visitas.visitas_trabajadores_ruc', function (Blueprint $table) {
            $table->id();
            
            // Relación con la tabla visitas
            $table->foreignId('visita_id')
                ->constrained('visitas.visitas'); // Asegúrate de que este sea el nombre real de tu tabla física
                
            // Relación con la tabla personas (Acompañante)
            $table->foreignId('persona_id')
                ->constrained('visitas.personas') // Ajusta según tu esquema de personas
                ->restrictOnDelete();

            // IMPORTANTE: Guardamos el cargo específico de ESE día
            $table->string('cargo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas.visitas_trabajadores_ruc');
    }
};
