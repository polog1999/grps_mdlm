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
        Schema::create('solicitudes_permisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->unsignedBigInteger('record_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('tipo_accion'); // 'EDITAR_DATOS', 'ACTUALIZAR_ARCHIVO'
            $table->string('estado')->default('PENDIENTE'); // 'PENDIENTE', 'APROBADO', 'RECHAZADO', 'FINALIZADO'

            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('fecha_aprobacion')->nullable();
            $table->text('observacion')->nullable();

            $table->timestamps();

            // Index for fast lookups: "Does THIS user have APPROVED permission for THIS record?"
            $table->index(['module_id', 'record_id', 'user_id', 'tipo_accion', 'estado'], 'idx_permisos_lookup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_permisos');
    }
};
