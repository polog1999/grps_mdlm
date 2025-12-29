<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();

            // Nombre amigable (Ej: "Usuarios", "Licencias")
            $table->string('name');

            // La clase completa, sirve como ID único (Ej: "App\Filament\Resources\UserResource")
            $table->string('filament_class')->unique();

            // (Opcional) Guardar a qué cluster pertenece (Ej: "Sistemas", "Sil")
            $table->string('cluster')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};