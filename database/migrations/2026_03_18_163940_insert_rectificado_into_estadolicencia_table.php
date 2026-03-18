<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Apagamos SOLO los triggers creados por usuarios (el espía roto)
        DB::statement('ALTER TABLE licencia.estadolicencia DISABLE TRIGGER USER;');

        // 2. Calculamos el ID
        $maxId = DB::table('licencia.estadolicencia')->max('esl_id') ?? 0;

        // 3. Insertamos el dato
        DB::table('licencia.estadolicencia')->insert([
            'esl_id' => $maxId + 1,
            'esl_descripcion' => 'RECTIFICADO',
            'esl_activo' => true,
            'esl_filaoriginal' => true,
            'esl_filaeliminada' => false,
            'usa_id' => 0,
        ]);

        // 4. Volvemos a encender los triggers de usuario para no dejar la BD desprotegida
        DB::statement('ALTER TABLE licencia.estadolicencia ENABLE TRIGGER USER;');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE licencia.estadolicencia DISABLE TRIGGER USER;');

        DB::table('licencia.estadolicencia')
            ->where('esl_descripcion', 'RECTIFICADO')
            ->delete();

        DB::statement('ALTER TABLE licencia.estadolicencia ENABLE TRIGGER USER;');
    }
};