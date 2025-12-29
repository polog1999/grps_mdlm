<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Usamos DB::statement para ejecutar el SQL crudo
        DB::statement(<<<SQL
            CREATE OR REPLACE FUNCTION licencia.spu_persona_insertar_unica(
                p_per_nombrerazonsocial character varying,
                p_per_ruc character varying,
                p_per_direccion character varying,
                p_per_telefono character varying,
                p_per_email character varying,
                p_per_expcodcon character varying)
                RETURNS TABLE(error_out integer, mensaje_out text) 
                LANGUAGE 'plpgsql'
            AS \$BODY$
            DECLARE
                v_per_id    integer := 0;
                v_existe    boolean;
            BEGIN
                -- 1. Validar que el nombre no sea vacío
                IF (p_per_nombrerazonsocial IS NULL OR TRIM(p_per_nombrerazonsocial) = '') THEN
                    error_out := -1;
                    mensaje_out := 'El nombre o razón social no puede estar vacío.';
                    RETURN NEXT;
                    RETURN;
                END IF;

                -- 2. BUSCAR DUPLICIDAD EXACTA
                SELECT EXISTS (
                    SELECT 1 FROM licencia.persona 
                    WHERE TRIM(per_nombreRazonSocial) = TRIM(p_per_nombrerazonsocial)
                      AND TRIM(COALESCE(per_ruc, '')) = TRIM(COALESCE(p_per_ruc, ''))
                      AND TRIM(COALESCE(per_direccion, '')) = TRIM(COALESCE(p_per_direccion, ''))
                      AND TRIM(COALESCE(per_telefono, '')) = TRIM(COALESCE(p_per_telefono, ''))
                      AND TRIM(COALESCE(per_email, '')) = TRIM(COALESCE(p_per_email, ''))
                      AND TRIM(COALESCE(per_expcodcon, '')) = TRIM(COALESCE(p_per_expcodcon, ''))
                ) INTO v_existe;

                -- 3. Si existe duplicidad total
                IF v_existe THEN
                    error_out := -20;
                    mensaje_out := 'Error: Ya existe un registro con exactamente los mismos datos.';
                    RETURN NEXT;
                    RETURN;
                END IF;

                -- 4. Lógica de Inserción
                SELECT COALESCE(MAX(per_id), 0) + 1 INTO v_per_id FROM licencia.persona;

                INSERT INTO licencia.persona(
                    per_id,
                    per_nombreRazonSocial,
                    per_ruc,
                    per_direccion,
                    per_telefono,
                    per_email,
                    per_expcodcon
                )
                VALUES(
                    v_per_id,
                    p_per_nombrerazonsocial,
                    p_per_ruc,
                    p_per_direccion,
                    p_per_telefono,
                    p_per_email,
                    p_per_expcodcon			
                );

                -- 5. Respuesta de éxito
                error_out := v_per_id;
                mensaje_out := 'Registro guardado exitosamente.';
                RETURN NEXT;

            EXCEPTION WHEN OTHERS THEN
                error_out := -500;
                mensaje_out := 'Error interno: ' || SQLERRM;
                RETURN NEXT;
            END;
            \$BODY$;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Borramos la función si se hace rollback
        DB::statement('DROP FUNCTION IF EXISTS licencia.spu_persona_insertar_unica(varchar, varchar, varchar, varchar, varchar, varchar)');
    }
};