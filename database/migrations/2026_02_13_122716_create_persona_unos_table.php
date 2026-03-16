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
        Schema::create('visitas.personas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_documento_id')->nullable()->constrained('visitas.tipo_documentos');
            $table->string('numero_documento')->nullable()->unique();
            $table->string('nombres')->nullable();
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('foto')->nullable(); // foto
            $table->text('foto_url')->nullable();
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
        Schema::dropIfExists('visitas.personas');
    }
};
