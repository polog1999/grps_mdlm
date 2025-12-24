<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Nota el nombre de la tabla con el esquema
        Schema::table('itse.permissions', function (Blueprint $table) {
            // Agregamos la columna module_id
            $table->foreignId('module_id')
                ->nullable() // Puede ser nulo (para permisos generales)
                ->constrained('modules') // Apunta a la tabla 'modules'
                ->nullOnDelete(); // Si borras el módulo, el permiso se queda huérfano (seguro)
        });
    }

    public function down(): void
    {
        Schema::table('itse.permissions', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn('module_id');
        });
    }
};
