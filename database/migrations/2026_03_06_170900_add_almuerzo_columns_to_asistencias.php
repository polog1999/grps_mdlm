<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('
            ALTER TABLE teletrabajo.asistencias
            ADD COLUMN hora_almuerzo_salida TIMESTAMPTZ NULL,
            ADD COLUMN hora_almuerzo_entrada TIMESTAMPTZ NULL
        ');
    }

    public function down(): void
    {
        DB::statement('
            ALTER TABLE teletrabajo.asistencias
            DROP COLUMN IF EXISTS hora_almuerzo_salida,
            DROP COLUMN IF EXISTS hora_almuerzo_entrada
        ');
    }
};
