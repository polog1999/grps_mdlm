<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Calculamos el ID máximo actual
        $maxId = DB::table('licencia.estadolicencia')->max('esl_id') ?? 0;

        // Insertamos directamente sin alterar los triggers
        DB::table('licencia.estadolicencia')->insert([
            'esl_id' => $maxId + 1,
            'esl_descripcion' => 'RECTIFICADO',
            'esl_activo' => true,
            'esl_filaoriginal' => true,
            'esl_filaeliminada' => false,
            'usa_id' => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos el registro directamente
        DB::table('licencia.estadolicencia')
            ->where('esl_descripcion', 'RECTIFICADO')
            ->delete();
    }
};