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
        Schema::create('visitas.excel_control1', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 20);
            $table->string('numero_documento', 20);
            $table->string('nombres_completos', 20);
            $table->string('area', 20);
            $table->string('persona_autoriza', 20);
            $table->string('cargo', 20);
            $table->string('sede', 20);
            $table->timestap('hora_salida');
            $table->timestap('hora_ingreso');
            $table->string('motivo', 20);
            $table->date('fecha');
            $table->string('usuario', 20);

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
