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
        Schema::table('visitas.personas', function (Blueprint $table) {
            // $table->foreignId('dependencia_id')->nullable()->constrained('visitas.personas');
            // $table->string('cargo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitas.personas', function (Blueprint $table) {
            // $table->dropColumn('dependencia_id');
            // $table->dropColumn('cargo');
        });
    }
};
