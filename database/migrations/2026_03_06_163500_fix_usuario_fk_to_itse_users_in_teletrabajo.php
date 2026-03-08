<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Corregir FK en asistencias: de public.users a itse.users
        DB::statement("
            ALTER TABLE teletrabajo.asistencias
            DROP CONSTRAINT IF EXISTS asistencias_usuario_id_fkey
        ");

        DB::statement("
            ALTER TABLE teletrabajo.asistencias
            ADD CONSTRAINT asistencias_usuario_id_fkey
            FOREIGN KEY (usuario_id) REFERENCES itse.users(id) ON DELETE RESTRICT
        ");

        // Corregir FK en informes: de public.users a itse.users
        DB::statement("
            ALTER TABLE teletrabajo.informes
            DROP CONSTRAINT IF EXISTS informes_usuario_id_fkey
        ");

        DB::statement("
            ALTER TABLE teletrabajo.informes
            ADD CONSTRAINT informes_usuario_id_fkey
            FOREIGN KEY (usuario_id) REFERENCES itse.users(id) ON DELETE RESTRICT
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a public.users
        DB::statement("
            ALTER TABLE teletrabajo.asistencias
            DROP CONSTRAINT IF EXISTS asistencias_usuario_id_fkey
        ");

        DB::statement("
            ALTER TABLE teletrabajo.asistencias
            ADD CONSTRAINT asistencias_usuario_id_fkey
            FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE RESTRICT
        ");

        DB::statement("
            ALTER TABLE teletrabajo.informes
            DROP CONSTRAINT IF EXISTS informes_usuario_id_fkey
        ");

        DB::statement("
            ALTER TABLE teletrabajo.informes
            ADD CONSTRAINT informes_usuario_id_fkey
            FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE RESTRICT
        ");
    }
};
