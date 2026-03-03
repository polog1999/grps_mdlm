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
        Schema::create('anuncios.anuncios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('n_anuncio');

            // Relación obligatoria con Expedientes (ahora por ID UUID)
            $table->foreignUuid('expediente_id')
                ->constrained('anuncios.expedientes')
                ->onDelete('restrict');

            $table->date('fecha_recepcion_evaluar')->nullable();
            $table->text('asunto')->nullable();

            // Llaves foráneas a tablas maestras
            $table->unsignedInteger('caracteristica_fisica_id');
            $table->foreign('caracteristica_fisica_id')
                ->references('id')
                ->on('anuncios.caracteristicas_fisicas');

            $table->unsignedInteger('tipo_anuncio_id');
            $table->foreign('tipo_anuncio_id')
                ->references('id')
                ->on('anuncios.tipo_anuncios');

            // Relación opcional con Licencias (0..1)
            $table->string('id_licencia')->nullable();
            // Si la tabla licencias está en otro esquema, especificarlo aquí:
            // $table->foreign('id_licencia')->references('lic_id')->on('licencia.licencias');

            $table->text('descripcion')->nullable();

            // Medidas técnicas
            $table->decimal('ancho_m', 10, 2)->default(0);
            $table->decimal('alto_m', 10, 2)->default(0);
            $table->decimal('espesor_cm', 10, 2)->default(0);

            $table->string('ubicacion_del_anuncio')->nullable();
            $table->integer('n_de_caras')->default(1);

            // Evaluación y Estados
            $table->enum('dictamen', ['PROCEDENTE', 'IMPROCEDENTE', 'OBSERVADO'])->nullable();
            $table->text('obs')->nullable();
            $table->string('estado_anuncio');

            // Auditoría y Derivación
            $table->unsignedInteger('derivado_a_legal_user_id')->nullable();
            $table->date('fecha_derivado')->nullable();

            // Campos de Auditoría (UnsignedInteger para coincidir con IDs de usuarios)
            $table->unsignedInteger('created_by_user_id');
            $table->unsignedInteger('updated_by_user_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anuncios.anuncios');
    }
};
