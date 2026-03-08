<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear la tabla de asistencias con TIMESTAMPTZ para manejo correcto de zonas horarias
        DB::statement("
            CREATE TABLE teletrabajo.asistencias (
                id BIGSERIAL PRIMARY KEY,

                -- Relación con la tabla de usuarios del sistema
                usuario_id INTEGER NOT NULL
                    REFERENCES public.users(id)
                    ON DELETE RESTRICT,

                -- TIMESTAMPTZ almacena internamente en UTC y convierte automáticamente
                -- según la zona horaria de la sesión o la especificada en las consultas
                hora_entrada TIMESTAMPTZ NOT NULL,

                -- La hora de salida es nullable porque el usuario registra la entrada primero
                -- y la salida se registra al finalizar la jornada
                hora_salida TIMESTAMPTZ,

                created_at TIMESTAMPTZ DEFAULT NOW(),
                updated_at TIMESTAMPTZ DEFAULT NOW(),

                -- CHECK: La hora de entrada (convertida a zona horaria de Lima/Perú)
                -- debe ser a partir de las 06:00 AM. Esto impide registros de madrugada
                -- o fuera del horario laboral permitido.
                CONSTRAINT chk_hora_entrada_minima
                    CHECK (
                        (hora_entrada AT TIME ZONE 'America/Lima')::time >= '06:00:00'::time
                    ),

                -- CHECK: La hora de salida (convertida a zona horaria de Lima/Perú)
                -- no puede ser antes de las 05:30 PM (17:30). Esto asegura que el
                -- trabajador cumpla con la jornada mínima antes de registrar su salida.
                CONSTRAINT chk_hora_salida_minima
                    CHECK (
                        hora_salida IS NULL
                        OR (hora_salida AT TIME ZONE 'America/Lima')::time >= '17:30:00'::time
                    ),

                -- CHECK: La hora de salida siempre debe ser posterior a la hora de entrada.
                -- Previene inconsistencias lógicas en el registro de asistencia.
                CONSTRAINT chk_salida_despues_entrada
                    CHECK (
                        hora_salida IS NULL
                        OR hora_salida > hora_entrada
                    )
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS teletrabajo.asistencias');
    }
};
