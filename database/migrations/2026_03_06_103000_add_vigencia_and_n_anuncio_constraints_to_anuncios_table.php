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
            ADD CONSTRAINT chk_fecha_vigencia_valida
            CHECK (fecha_fin_vigencia >= fecha_inicio_vigencia),
            ADD CONSTRAINT chk_n_anuncio_formato
            CHECK (n_anuncio ~ '^[0-9]{6}$')
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE anuncios.anuncios
            DROP CONSTRAINT IF EXISTS chk_fecha_vigencia_valida,
            DROP CONSTRAINT IF EXISTS chk_n_anuncio_formato
        ");
    }
};
