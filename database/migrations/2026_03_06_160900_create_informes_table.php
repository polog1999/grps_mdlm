<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear la tabla de informes para el registro de documentos de teletrabajo
        DB::statement("
            CREATE TABLE teletrabajo.informes (
                id BIGSERIAL PRIMARY KEY,

                -- Número de informe único, sirve como identificador de negocio
                -- Ejemplo: 'INF-2026-001'
                numero_informe VARCHAR(100) NOT NULL UNIQUE,

                -- Relación con la tabla de usuarios del sistema
                usuario_id INTEGER NOT NULL
                    REFERENCES public.users(id)
                    ON DELETE RESTRICT,

                -- Ruta o URL del archivo del informe (almacenado en storage)
                url_archivo TEXT NOT NULL,

                -- Fecha de subida del informe, por defecto toma la fecha actual
                -- ajustada a la zona horaria de Perú (America/Lima, UTC-5).
                -- Esto garantiza que si el servidor está en UTC, la fecha registrada
                -- corresponda al día real en Perú.
                fecha_subida DATE NOT NULL DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'America/Lima')::date,

                created_at TIMESTAMPTZ DEFAULT NOW(),
                updated_at TIMESTAMPTZ DEFAULT NOW()
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS teletrabajo.informes');
    }
};
