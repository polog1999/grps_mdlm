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
        Schema::create('visitas.visita_persona', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visita_id')->constrained('visitas.visitas')->onDelete('cascade');
            $table->foreignId('persona_id')->constrained('visitas.personas')->onDelete('cascade');
            $table->string('cargo')->nullable(); // Útil para cuando vienen por empresa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visita_persona');
    }
};
