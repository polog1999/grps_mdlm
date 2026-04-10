<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Limpieza previa por seguridad
        DB::statement("DROP VIEW IF EXISTS visitas.historico_visitas");

        // 2. Creación de la vista
        DB::statement("
            CREATE VIEW visitas.historico_visitas AS 
            SELECT 
                row_number() OVER () AS id,
                t.*
            FROM (
                SELECT
                    v.id AS id_original,   
                    v.fecha_ingreso AS fecha,
                    p.tipo_documento AS tipo_documento,
                    p.numero_documento  AS numero_documento,
                    CASE 
                        WHEN v.es_empresa THEN (p.apellido_paterno || ' ' || p.apellido_materno || ' ' || p.nombres)
                        ELSE (p.apellido_paterno || ' ' || p.apellido_materno || ' ' || p.nombres)
                    END AS \"nombres_completos\",
                    CASE 
                        WHEN v.es_empresa THEN v.ruc
                        ELSE NULL
                    END 
                    ruc,
                    v.proveedor,
                    v.area AS area,
                    v.oficina,
                    v.trabajador_autoriza AS \"Autorizado por\",
                    v.trabajador_cita AS trabajador_cita,
                    v.sede_id AS sede_id,
                    v.fecha_ingreso::time AS hora_ingreso,
                    v.fecha_salida::time AS hora_salida,
                    v.motivo,
                    v.detalle_motivo,
                    ui.id AS user_id_ingreso,
                    us.id AS user_id_salida,
                    'SISTEMA' AS origen,
                    v.es_empresa,
                    v.sistema
                FROM visitas.visitas v
                INNER JOIN visitas.visita_persona vp ON v.id = vp.visita_id
                INNER JOIN visitas.personas p ON vp.persona_id = p.id
                LEFT JOIN itse.users ui ON ui.id = v.user_id_ingreso
                LEFT JOIN itse.users us ON us.id = v.user_id_salida

                UNION ALL

                SELECT 
                    id,
                    (fecha::date + COALESCE(hora_ingreso::time, '00:00:00'::time)) AS fecha,
                    tipo_documento,
                    numero_documento,
                    nombres_completos,
                    NULL,
                    NULL,
                    area, 
                    NULL AS oficina,
                    persona_autoriza,
                    trabajador_cita,
                    1 AS sede_id,
                    hora_ingreso::time,
                    hora_salida::time,
                    motivo,
                    NULL AS detalle_motivo,
                    usuario::integer,
                    usuario::integer,
                    'MIGRACION' AS origen,
                    NULL,
                    sistema
                FROM visitas.visitas_historico
            ) t
            ORDER BY fecha DESC NULLS LAST
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS visitas.historico_visitas");
    }
};
