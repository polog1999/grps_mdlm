<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('itse.users', function (Blueprint $table) {
            $table->foreignId('sede_id')->nullable()->constrained('visitas.sedes');
            $table->foreignId('trabajador_id')->nullable()->constrained('visitas.trabajadores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itse.users', function (Blueprint $table) {
            $table->dropColumn('sede_id');
            $table->dropColumn('trabajador_id');
        });
    }
};
