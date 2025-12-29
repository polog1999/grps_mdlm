<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        DB::connection('pgsql')->unprepared("
            CREATE OR REPLACE FUNCTION licencia.fn_get_next_lic_numlic()
            RETURNS VARCHAR(6)
            LANGUAGE plpgsql
            AS $$
            DECLARE
                next_lic_numlic VARCHAR(6);
            BEGIN

                SELECT LPAD(
                    (CAST(lic_numlic AS INTEGER) + 1)::text,
                    6,
                    '0'
                )
                INTO next_lic_numlic
                FROM licencia.licencia
                WHERE lic_id = (SELECT MAX(lic_id) FROM licencia.licencia);

                RETURN next_lic_numlic;

            END;
            $$;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
            DROP FUNCTION IF EXISTS licencia.fn_get_next_lic_numlic();
        ");
    }
};
