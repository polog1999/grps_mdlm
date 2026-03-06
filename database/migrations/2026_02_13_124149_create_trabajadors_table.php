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
        Schema::create('visitas.trabajadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->unique()->constrained('visitas.personas');
            $table->integer('cui')->nullable();
            $table->foreignId('regimen_id')->nullable()->constrained('visitas.regimenes');
            $table->foreignId('clasificacion_id')->nullable()->constrained('visitas.clasificaciones');
            $table->date('fecha_ingreso')->nullable();
            $table->boolean('estado')->default(true);
            $table->foreignId('user_id_creo')->nullable()->constrained('users');
            $table->foreignId('user_id_modi')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas.trabajadores');
    }
};
