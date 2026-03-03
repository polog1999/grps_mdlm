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
        Schema::table('anuncios.anuncios', function (Blueprint $table) {
            // Añadimos los campos de vigencia
            // He incluido 'INDETERMINADA' para los anuncios que no son temporales
            $table->enum('vigencia', ['TEMPORAL', 'INDETERMINADA'])
                ->default('INDETERMINADA')
                ->after('n_de_caras');

            $table->date('fecha_inicio_vigencia')
                ->nullable()
                ->after('vigencia');

            $table->date('fecha_fin_vigencia')
                ->nullable()
                ->after('fecha_inicio_vigencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anuncios.anuncios', function (Blueprint $table) {
            $table->dropColumn(['vigencia', 'fecha_inicio_vigencia', 'fecha_fin_vigencia']);
        });
    }
};
