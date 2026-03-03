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
        Schema::create('anuncios.documentos_anuncio', function (Blueprint $table) {
            // Usamos id() que por defecto es BigInt, pero si prefieres integer simple:
            $table->id('id_documento');

            // Relación con Anuncios (Debe ser UUID para coincidir con anuncios.id)
            $table->foreignUuid('anuncio_id')
                ->constrained('anuncios.anuncios')
                ->onDelete('cascade');

            // Definición del ENUM
            $table->enum('tipo_documento', ['CARTA', 'INFORME TÉCNICO']);

            $table->string('n_documento');
            $table->date('fecha_emision')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anuncios.documentos_anuncio');
    }
};
