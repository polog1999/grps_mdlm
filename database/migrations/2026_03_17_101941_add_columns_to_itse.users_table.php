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
        Schema::table('itse.users', function (Blueprint $table) {
            if (!Schema::hasColumn('itse.users', 'sede_id')) {
                $table->integer('sede_id')->nullable();
            }
            if (!Schema::hasColumn('itse.users', 'sede')) {
                $table->string('sede')->nullable();
            }
            if (!Schema::hasColumn('itse.users', 'trabajador_id')) {
                $table->integer('trabajador_id')->nullable();
            }
            if (!Schema::hasColumn('itse.users', 'nombres_completos')) {
                $table->string('nombres_completos')->nullable();
            }
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
            );
        });
    }
};
