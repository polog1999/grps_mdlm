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
        DB::statement('ALTER TABLE licencia.estadolicencia DISABLE TRIGGER ALL;');

        $maxId = DB::table('licencia.estadolicencia')->max('esl_id') ?? 0;

        DB::table('licencia.estadolicencia')->insert([
            'esl_id' => $maxId + 1,
            'esl_descripcion' => 'RECTIFICADO',
            'esl_activo' => true,
            'esl_filaoriginal' => true,
            'esl_filaeliminada' => false,
            'usa_id' => 0,
        ]);

        DB::statement('ALTER TABLE licencia.estadolicencia ENABLE TRIGGER ALL;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE licencia.estadolicencia DISABLE TRIGGER ALL;');

        DB::table('licencia.estadolicencia')
            ->where('esl_descripcion', 'RECTIFICADO')
            ->delete();

        DB::statement('ALTER TABLE licencia.estadolicencia ENABLE TRIGGER ALL;');
    }
};
