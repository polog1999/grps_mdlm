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
        Schema::create('certificados_borrados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cin_id');
            $table->text('cin_razon_borrado')->nullable();
            $table->timestamps();

            // Índices para consultas rápidas
            $table->index('user_id');
            $table->index('cin_id');

            // Foreign key: user_id -> users.id
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('cin_id')
                ->references('cin_id')
                ->on('certificadoinspeccion')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificados_borrados');
    }
};
