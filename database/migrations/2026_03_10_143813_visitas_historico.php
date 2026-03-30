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
        Schema::create('visitas.visitas_historico', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 20)->nullable();
            $table->string('numero_documento',20)->nullable();
            $table->string('nombres_completos')->nullable();
            $table->string('area')->nullable();
            $table->string('persona_autoriza')->nullable();
            $table->string('cargo')->nullable();
            $table->string('sede')->nullable();
            $table->timestamp('hora_salida')->nullable();
            $table->timestamp('hora_ingreso')->nullable();
            $table->string('motivo')->nullable();
            $table->date('fecha')->nullable();
            $table->string('usuario')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas.excel_control1');
    }
};
