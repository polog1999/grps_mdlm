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
        Schema::create('anuncios.anuncio_material', function (Blueprint $table) {
            $table->id();

            // Relación con el anuncio (UUID)
            $table->foreignUuid('anuncio_id')
                ->constrained('anuncios.anuncios')
                ->onDelete('cascade'); // Si se borra el anuncio, se limpia esta relación

            // Relación con el maestro de materiales
            $table->unsignedInteger('material_id');
            $table->foreign('material_id')
                ->references('id')
                ->on('anuncios.materiales')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anuncios.anuncio_material');
    }
};
