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
            // 1. Borrar el esquema y TODO su contenido (tablas, secuencias, etc.)
            // CASCADE es vital para que PostgreSQL no se detenga por llaves foráneas
            DB::statement("DROP SCHEMA IF EXISTS {$schemaName} CASCADE");

            // 2. Limpiar la tabla migrations de Laravel
            // Buscamos las migraciones que mencionen el esquema o sus tablas principales
            // para que los batches dejen de existir y puedas remigrar si quieres.
            DB::table('migrations')
                ->where('migration', 'like', "%{$schemaName}%")
                ->orWhere('migration', 'like', "%create_motivos%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_02_13_115244_create_tipo_documentos%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_02_13_122716_create_persona_unos_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_02_13_124535_create_visitas_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_02_18_113659_insert_tipo_documentos_to_tipo_documentos_table%") // Ajusta según tus nombres




                ->orWhere('migration', 'like', "%2026_03_10_143813_visitas_historico%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_11_095550_add_columns_to_personas_table%") // Ajusta según tus nombres

                ->orWhere('migration', 'like', "%2026_03_11_141836_add_soft_deletes_to_tipo_documentos_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_16_171110_create_motivos_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_16_171111_add_soft_deletes_to_motivos_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_17_112437_add_columns_to_visitas.visitas_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_17_112514_add_columns_to_visitas.visitas_historico_table%") // Ajusta según tus nombres

                ->orWhere('migration', 'like', "%2026_03_20_094123_add_columns_to_visitas.visitas_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_20_112951_create_visitas_trabajadores_ruc_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_20_140236_add_columns_to_visitas.visitas_historico_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_23_155301_add_columns_to_visitas.personas%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_25_100424_create_notifications_table%") // Ajusta según tus nombres

                ->orWhere('migration', 'like', "%2026_03_25_101639_create_imports_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_25_101640_create_exports_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_25_101641_create_failed_import_rows_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_27_103420_add_columns_to_visitas.visitas%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_27_163611_drop_visitas_schema%") // Ajusta según tus nombres

                ->orWhere('migration', 'like', "%2026_02_18_141638_insert_regimenes_to_regimenes_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_02_25_153741_add_sede_id_to_users_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_25_101641_create_failed_import_rows_table%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_27_103420_add_columns_to_visitas.visitas%") // Ajusta según tus nombres
                ->orWhere('migration', 'like', "%2026_03_27_163611_drop_visitas_schema%") // Ajusta según tus nombres

                ->delete();
        });

        info("El esquema {$schemaName} y sus registros de migración han sido eliminados.");
    }

    public function down(): void
    {
        // El DROP SCHEMA CASCADE es destructivo y no tiene reversión automática.
    }
};
