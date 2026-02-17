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
        Schema::create('visitas.areas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('parent_id')->nullable()->constrained('visitas.areas'); // parent
            $table->foreignId('sede_id')->nullable()->constrained('visitas.sedes');
            $table->string('nombre_corto')->nullable();
            $table->integer('orden');
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('visitas.areas');
    }
};
