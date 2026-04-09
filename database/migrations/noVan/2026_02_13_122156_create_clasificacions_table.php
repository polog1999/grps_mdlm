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
        Schema::create('visitas.clasificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // de_clasificacion
            $table->boolean('in_esta')->default(true); // in_esta
            $table->boolean('estado')->default(true); // estado
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
        Schema::dropIfExists('visitas.clasificaciones');
    }
};
