<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Establecer explícitamente la conexión a la base de datos
        DB::connection('pgsql_licencias')->unprepared("
            CREATE SCHEMA IF NOT EXISTS licencia;

            CREATE OR REPLACE FUNCTION licencia.fn_get_next_resolucion_spea()
            RETURNS TEXT
            LANGUAGE plpgsql
            AS $$
            DECLARE
                siguiente_num TEXT;
            BEGIN
                SELECT LPAD(
                    COALESCE(
                        (
                            SELECT 
                                CAST(SPLIT_PART(lic_resnum, '-', 1) AS INTEGER) + 1
                            FROM licencia.licencia
                            WHERE lic_resnum LIKE '%SPEA%'
                              AND lic_resnum LIKE '%-' || EXTRACT(YEAR FROM NOW())::TEXT || '-%'
                              AND LENGTH(SPLIT_PART(lic_resnum, '-', 1)) = 4
                            ORDER BY CAST(SPLIT_PART(lic_resnum, '-', 1) AS INTEGER) DESC
                            LIMIT 1
                        ),
                        1
                    )::text,
                    4,
                    '0'
                )
                INTO siguiente_num;

                RETURN siguiente_num;
            END;
            $$;
        ");
    }

    public function down(): void
    {
        DB::connection('pgsql_licencias')->unprepared("
            DROP FUNCTION IF EXISTS licencia.fn_get_next_resolucion_spea();
        ");
    }
};
