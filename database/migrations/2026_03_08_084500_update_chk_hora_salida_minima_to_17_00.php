<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE teletrabajo.asistencias
            DROP CONSTRAINT IF EXISTS chk_hora_salida_minima,
            ADD CONSTRAINT chk_hora_salida_minima
                CHECK (
                    hora_salida IS NULL
                    OR (hora_salida AT TIME ZONE 'America/Lima')::time >= '17:00:00'::time
                )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE teletrabajo.asistencias
            DROP CONSTRAINT IF EXISTS chk_hora_salida_minima,
            ADD CONSTRAINT chk_hora_salida_minima
                CHECK (
                    hora_salida IS NULL
                    OR (hora_salida AT TIME ZONE 'America/Lima')::time >= '17:30:00'::time
                )
        ");
    }
};
