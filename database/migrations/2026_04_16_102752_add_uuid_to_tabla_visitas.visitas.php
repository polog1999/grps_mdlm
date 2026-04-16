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
        Schema::table('visitas.visitas', function (Blueprint $table) {
            $table->uuid('grupo_uid')->nullable()->index(); // Identificador de grupo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitas.visitas', function (Blueprint $table) {
            $table->dropColumn('grupo_uid');
        });
    }
};
