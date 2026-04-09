<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

return new class extends Migration
{
    public function up(): void
    {
        $schemaName = 'visitas'; // <--- El esquema que quieres fulminar

        // SEGURIDAD: Evitar borrar esquemas del sistema o de producción activa
        if (in_array($schemaName, ['public', 'information_schema', 'pg_catalog'])) {
            throw new \Exception("ERROR CRÍTICO: No se puede borrar un esquema del sistema.");
        }

        DB::transaction(function () use ($schemaName) {
            DB::statement("DROP TABLE IF EXISTS itse.notifications CASCADE");
            DB::statement("DROP TABLE IF EXISTS itse.imports CASCADE");
            DB::statement("DROP TABLE IF EXISTS itse.exports CASCADE"); // Agregada por si acaso, según tu lista de abajo
            DB::statement("DROP TABLE IF EXISTS itse.failed_import_rows CASCADE");
            DB::statement("DROP VIEW IF EXISTS visitas.historico_visitas");
            // 1. Borrar el esquema y TODO su contenido (tablas, secuencias, etc.)
            // CASCADE es vital para que PostgreSQL no se detenga por llaves foráneas
            DB::statement("DROP SCHEMA IF EXISTS {$schemaName} CASCADE");
            

            // 2. Limpiar la tabla migrations de Laravel
            // Buscamos las migraciones que mencionen el esquema o sus tablas principales
            // para que los batches dejen de existir y puedas remigrar si quieres.
            DB::table('migrations')
                ->where('migration', 'like', "%{$schemaName}%")
                ->orWhere('migration', 'like', "%create_motivos%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%create_tipo_documentos%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%create_persona_unos_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%create_visitas_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%insert_tipo_documentos_to_tipo_documentos_table%") // Ajusta según tus nombres




                ->orWhere('migration', 'like', "%visitas_historico%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_columns_to_personas_table%") // Ajusta según tus nombres

                ->orWhere('migration', 'like', "%add_soft_deletes_to_tipo_documentos_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%create_motivos_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_soft_deletes_to_motivos_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_columns_to_visitas.visitas_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_columns_to_visitas.visitas_historico_table%") // Ajusta según tus nombres


                ->orWhere('migration', 'like', "%add_columns_to_visitas.visitas_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%create_visitas_trabajadores_ruc_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_columns_to_visitas.visitas_historico_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_columns_to_visitas.personas%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%create_notifications_table%") // Ajusta según tus nombres

                ->orWhere('migration', 'like', "%create_imports_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%create_exports_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%create_failed_import_rows_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_columns_to_visitas.visitas%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%drop_visitas_schema%") // Ajusta según tus nombres

                ->orWhere('migration', 'like', "%insert_regimenes_to_regimenes_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%insert_clasificaciones_to_clasificaciones_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%insert_sedes_to_sedes_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%insert_areas_to_areas_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%insert_cargos_to_cargos_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%insert_areas_to_areas_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_sede_id_to_users_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_columns_to_itse.users_table%") // Ajusta según tus nombres
                 
                ->orWhere('migration', 'like', "%excel_control1%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_soft_deletes_to_areas_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_soft_deletes_to_cargos_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_soft_deletes_to_clasificaciones_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_soft_deletes_to_regimenes_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_soft_deletes_to_sedes_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%add_soft_deletes_to_areas_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%create_historico_visitas_view%") // Ajusta según tus nombres
                ->delete();
        });

        info("El esquema {$schemaName} y sus registros de migración han sido eliminados.");
    }

    public function down(): void
    {
        // El DROP SCHEMA CASCADE es destructivo y no tiene reversión automática.
    }
};
