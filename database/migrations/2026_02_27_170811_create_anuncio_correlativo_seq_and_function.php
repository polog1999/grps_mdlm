<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -------------------------------------------------------
        // 1. Crear la secuencia correlativa para n_anuncio
        // -------------------------------------------------------
        DB::statement("
            CREATE SEQUENCE IF NOT EXISTS anuncios.anuncio_correlativo_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1
        ");

        // -------------------------------------------------------
        // 2. Crear la función que obtiene el siguiente n_anuncio
        // -------------------------------------------------------
        DB::statement("
            CREATE OR REPLACE FUNCTION anuncios.fn_obtener_siguiente_n_anuncio()
                RETURNS character varying
                LANGUAGE 'plpgsql'
                COST 100
                VOLATILE PARALLEL UNSAFE
            AS \$BODY\$
            DECLARE
                v_siguiente_id BIGINT;
                v_is_called BOOLEAN;
                v_last_value BIGINT;
                v_start_value BIGINT;
            BEGIN
                -- Obtenemos el estado actual de la secuencia
                SELECT last_value, is_called INTO v_last_value, v_is_called 
                FROM anuncios.anuncio_correlativo_seq;
                
                -- Obtenemos el valor inicial configurado en la secuencia
                SELECT start_value INTO v_start_value 
                FROM pg_sequence_parameters('anuncios.anuncio_correlativo_seq'::regclass);

                -- Lógica de decisión:
                -- Si is_called es false, la secuencia nunca se ha usado, el siguiente es el inicial.
                -- Si is_called es true, el siguiente es el último valor + 1.
                IF v_is_called THEN
                    v_siguiente_id := v_last_value + 1;
                ELSE
                    v_siguiente_id := v_start_value;
                END IF;

                RETURN LPAD(v_siguiente_id::text, 6, '0');
            END;
            \$BODY\$
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP FUNCTION IF EXISTS anuncios.fn_obtener_siguiente_n_anuncio()");
        DB::statement("DROP SEQUENCE IF EXISTS anuncios.anuncio_correlativo_seq");
    }
};
