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
             $table->integer('sede_id')->nullable();
            $table->string('sede')->nullable();
             $table->integer('trabajador_id')->nullable();
            $table->string('nombres_completos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itse.users', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'sede_id',
                    'sede',
                    'trabajador_id',
                    'nombres_completos'
                ]
            )
        });
    }
};
