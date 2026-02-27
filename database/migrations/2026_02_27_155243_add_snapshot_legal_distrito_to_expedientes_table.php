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
            $table->string('snapshot_legal_distrito')->nullable()->after('snapshot_legal_direccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anuncios.expedientes', function (Blueprint $table) {
            $table->dropColumn('snapshot_legal_distrito');
        });
    }
};
