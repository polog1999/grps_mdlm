<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE anuncios.anuncios
            DROP CONSTRAINT IF EXISTS chk_procedente_requires_n_anuncio,
            DROP CONSTRAINT IF EXISTS chk_improcedente_observado_no_n_anuncio
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-agregamos el constraint de procedencia (17:30 era el original pero aquí es n_anuncio)
        // Nota: El down recreará los constraints previos si fuera necesario revertir.
        DB::statement("
            ALTER TABLE anuncios.anuncios
            ADD CONSTRAINT chk_procedente_requires_n_anuncio
            CHECK (
                dictamen <> 'PROCEDENTE'
                OR (n_anuncio IS NOT NULL AND TRIM(n_anuncio) <> '')
            )
        ");

        DB::statement("
            ALTER TABLE anuncios.anuncios
            ADD CONSTRAINT chk_improcedente_observado_no_n_anuncio
            CHECK (
                dictamen NOT IN ('IMPROCEDENTE', 'OBSERVADO')
                OR (n_anuncio IS NULL OR TRIM(n_anuncio) = '')
            )
        ");
    }
};
