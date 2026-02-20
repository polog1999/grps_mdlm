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
        Schema::create('anuncios.anuncio_colores', function (Blueprint $table) {
            $table->id();

            // Relación con el anuncio (UUID)
            $table->foreignUuid('anuncio_id')
                ->constrained('anuncios.anuncios')
                ->onDelete('cascade');

            // Relación con el maestro de colores
            $table->unsignedInteger('color_id');
            $table->foreign('color_id')
                ->references('id')
                ->on('anuncios.colores')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anuncios.anuncios.colores');
    }
};
