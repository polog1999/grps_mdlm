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
        Schema::table('anuncios.expedientes', function (Blueprint $table) {
            $table->dropUnique('anuncios_expedientes_n_expediente_unique');
            $table->index('n_expediente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anuncios.expedientes', function (Blueprint $table) {
            $table->dropIndex(['n_expediente']);
            $table->unique('n_expediente');
        });
    }
};
