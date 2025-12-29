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
        Schema::create('tipoedificacion', function (Blueprint $table) {
            $table->id('tie_id');
            $table->string('tie_descripcion')->nullable();
            $table->string('tie_sigla')->nullable();
            $table->boolean('tie_activo')->nullable()->default(true);
            $table->boolean('tie_filaoriginal')->nullable()->default(true);
            $table->boolean('tie_filaeliminada')->nullable()->default(false);
            $table->timestamp('tie_filafecha')->useCurrent();
            $table->unsignedBigInteger('usa_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoedificacion');
    }
};
