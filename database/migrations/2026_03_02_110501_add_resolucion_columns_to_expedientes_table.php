<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('anuncios.expedientes', function (Blueprint $table) {
            $table->string('n_resolucion_subgerencial')->nullable()->after('snapshot_legal_distrito');
            $table->date('fecha_resolucion_subgerencial')->nullable()->after('n_resolucion_subgerencial');
        });
    }

    public function down(): void
    {
        Schema::table('anuncios.expedientes', function (Blueprint $table) {
            $table->dropColumn(['n_resolucion_subgerencial', 'fecha_resolucion_subgerencial']);
        });
    }
};
