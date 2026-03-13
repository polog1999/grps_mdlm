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
            $table->integer('folios')->nullable()->change();
            $table->unsignedInteger('zonificacion_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anuncios.expedientes', function (Blueprint $table) {
            $table->integer('folios')->nullable(false)->default(0)->change();
            $table->unsignedInteger('zonificacion_id')->nullable(false)->change();
        });
    }
};
