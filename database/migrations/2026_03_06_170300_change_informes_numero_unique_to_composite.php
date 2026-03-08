<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('
            ALTER TABLE teletrabajo.informes
            DROP CONSTRAINT informes_numero_informe_key
        ');

        DB::statement('
            ALTER TABLE teletrabajo.informes
            ADD CONSTRAINT informes_numero_informe_usuario_unique
            UNIQUE (numero_informe, usuario_id)
        ');
    }

    public function down(): void
    {
        DB::statement('
            ALTER TABLE teletrabajo.informes
            DROP CONSTRAINT informes_numero_informe_usuario_unique
        ');

        DB::statement('
            ALTER TABLE teletrabajo.informes
            ADD CONSTRAINT informes_numero_informe_key
            UNIQUE (numero_informe)
        ');
    }
};
