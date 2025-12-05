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
        Schema::create('certificadoinspeccion', function (Blueprint $table) {
            // Primary key
            $table->id('cin_id');
            // Basic fields
            $table->integer('cin_anio')->nullable();
            $table->unsignedBigInteger('tie_id')->nullable();
            $table->integer('cin_numero')->nullable();
            $table->decimal('cin_area', 10, 2)->nullable();

            // Area: use decimal with reasonable precision
            $table->string('cin_licencia')->nullable();
            $table->string('cin_procedimiento')->nullable();
            $table->integer('cin_capacidad')->nullable();
            $table->string('cin_expediente')->nullable();
            // Dates

            $table->date('cin_fecha')->nullable();
            $table->date('cin_fec_inicio')->nullable();
            $table->date('cin_fec_fin')->nullable();

            $table->string('cin_giro')->nullable();
            $table->string('cin_resolucion')->nullable();
            $table->string('cin_establecimiento')->nullable();

            $table->string('cin_solicitante')->nullable();
            $table->string('cin_departamento')->nullable();
            $table->string('cin_provincia')->nullable();
            $table->string('cin_distrito')->nullable();
            $table->string('cin_ubicacion')->nullable();
            $table->boolean('cin_indeterminado')->nullable();
            $table->timestamp('cin_filafecha')->nullable();
            $table->boolean('cin_filaoriginal')->nullable();
            $table->boolean('cin_filaeliminada')->nullable();
            $table->unsignedBigInteger('usa_id')->nullable();
            $table->string('cin_resolucion_sigla')->nullable();
            $table->string('cin_nota', 400)->nullable();
            $table->boolean('cin_consello')->nullable();
            $table->unsignedBigInteger('lic_id')->nullable();


            // Booleans / flags

            // Foreign keys / references (nullable)
            // Text / string fields




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificadoinspeccion');
    }
};
