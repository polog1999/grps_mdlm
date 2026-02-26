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
            $table->string('giro_especifico_snapshot')->nullable();
            $table->string('direccion_predio_snapshot')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anuncios.anuncios', function (Blueprint $table) {
            $table->dropColumn(['giro_especifico_snapshot', 'direccion_predio_snapshot']);
        });
    }
};
