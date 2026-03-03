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
        Schema::create('anuncios.expedientes', function (Blueprint $table) {
            // Usamos UUID como llave primaria
            $table->uuid('id')->primary();

            // Mantenemos el número de expediente como llave natural única
            $table->string('n_expediente')->unique();

            // Relaciones con esquema externo
            $table->unsignedInteger('per_id_solicitante');
            $table->foreign('per_id_solicitante')->references('per_id')->on('licencia.persona');

            // Snapshots (Auditoría histórica)
            $table->string('snapshot_solicitante_nombre_completo')->nullable();
            $table->string('snapshot_solicitante_dni')->nullable();
            $table->string('snapshot_solicitante_telefono')->nullable();
            $table->string('snapshot_solicitante_direccion')->nullable();

            $table->unsignedInteger('per_id_legal')->nullable();
            $table->foreign('per_id_legal')->references('per_id')->on('licencia.persona');
            $table->string('snapshot_legal_nombre')->nullable();
            $table->string('snapshot_legal_dni_ruc')->nullable();
            $table->string('snapshot_legal_telefono')->nullable();
            $table->string('snapshot_legal_direccion')->nullable();

            $table->integer('folios')->default(0);

            // Relaciones con esquema anuncios
            $table->unsignedInteger('zonificacion_id');
            $table->foreign('zonificacion_id')->references('id')->on('licencia.zonificaciones');

            $table->unsignedBigInteger('recibo_pago_id')->unique();
            $table->foreign('recibo_pago_id')->references('id')->on('anuncios.recibo_pago');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
