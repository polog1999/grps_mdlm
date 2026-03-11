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
        Schema::create('visitas.regimenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('visitas.regimenes'); // id_regimen_sup
            $table->string('cregimen'); // c_regimen
            $table->string('de_regimen')->nullable(); // de_regimen
            $table->boolean('estado')->default(true);
            $table->float('nu_tasa_impuesto')->nullable();
            $table->foreignId('user_id_creo')->nullable()->constrained('users');
            $table->foreignId('user_id_modi')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas.regimenes');
    }
};
