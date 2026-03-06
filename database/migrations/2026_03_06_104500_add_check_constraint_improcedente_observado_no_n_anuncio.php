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
            ADD CONSTRAINT chk_improcedente_observado_no_n_anuncio
            CHECK (
                dictamen NOT IN ('IMPROCEDENTE', 'OBSERVADO')
                OR (n_anuncio IS NULL OR TRIM(n_anuncio) = '')
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE anuncios.anuncios
            DROP CONSTRAINT IF EXISTS chk_improcedente_observado_no_n_anuncio
        ");
    }
};
