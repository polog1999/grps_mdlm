<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'pgsql_licencias';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('pgsql_licencias')->table('licencia.licencia_levantamiento', function (Blueprint $table) {
            $table->text('observaciones')->nullable()->after('id_estado_levantamiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql_licencias')->table('licencia.licencia_levantamiento', function (Blueprint $table) {
            $table->dropColumn('observaciones');
        });
    }
};
