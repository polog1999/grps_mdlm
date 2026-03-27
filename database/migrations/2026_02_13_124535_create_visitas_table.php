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
            $table->foreignId('persona_id')->nullable()->constrained('visitas.personas');
            $table->integer('sede_id')->nullable();
            $table->string('sede')->nullable();
            $table->integer('area_id');
            $table->string('area');
            $table->integer('oficina_id')->nullable();
            $table->string('oficina')->nullable();
            $table->integer('trabajador_id_autoriza')->nullable();
            $table->string('trabajador_autoriza')->nullable();
            $table->integer('trabajador_id_cita')->nullable();
            $table->string('trabajador_cita')->nullable();
            $table->foreignId('user_id_ingreso')->nullable()->constrained('users');
            $table->foreignId('user_id_salida')->nullable()->constrained('users');
            $table->string('motivo')->nullable();
            // $table->string('obs')->nullable();
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
