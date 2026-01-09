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
        Schema::connection('pgsql_licencias')->create('licencia.estado_levantamiento', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql_licencias')->dropIfExists('licencia.estado_levantamiento');
    }
};
