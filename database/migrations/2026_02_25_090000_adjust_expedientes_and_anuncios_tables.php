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
        // 1. Ajustes en la tabla de expedientes
        Schema::table('anuncios.expedientes', function (Blueprint $table) {
            $table->dropForeign(['per_id_solicitante']);
            $table->dropColumn('per_id_solicitante');

            $table->dropForeign(['per_id_legal']);
            $table->dropColumn('per_id_legal');
        });

        // 2. Ajustes en la tabla de anuncios
        Schema::table('anuncios.anuncios', function (Blueprint $table) {
            $table->text('materiales_descripcion')->nullable()->after('alto_m');
        });

        // 3. Eliminar tablas de materiales
        Schema::dropIfExists('anuncios.anuncio_material');
        Schema::dropIfExists('anuncios.materiales');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Esta reversión es compleja porque eliminamos tablas, pero intentaremos recrear lo básico
        Schema::create('anuncios.materiales', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion')->unique();
            $table->timestamps();
        });

        Schema::create('anuncios.anuncio_material', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('anuncio_id')->constrained('anuncios.anuncios')->onDelete('cascade');
            $table->unsignedInteger('material_id');
            $table->foreign('material_id')->references('id')->on('anuncios.materiales');
            $table->timestamps();
        });

        Schema::table('anuncios.anuncios', function (Blueprint $table) {
            $table->dropColumn('materiales_descripcion');
        });

        Schema::table('anuncios.expedientes', function (Blueprint $table) {
            $table->unsignedInteger('per_id_solicitante')->nullable();
            $table->foreign('per_id_solicitante')->references('per_id')->on('licencia.persona');

            $table->unsignedInteger('per_id_legal')->nullable();
            $table->foreign('per_id_legal')->references('per_id')->on('licencia.persona');
        });
    }
};
