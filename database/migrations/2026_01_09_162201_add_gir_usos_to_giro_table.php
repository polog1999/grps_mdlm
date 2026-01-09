<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'pgsql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('pgsql')->table('licencia.giro', function (Blueprint $table) {
            $table->boolean('gir_usos')->default(false)->after('gir_descripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql')->table('licencia.giro', function (Blueprint $table) {
            $table->dropColumn('gir_usos');
        });
    }
};
