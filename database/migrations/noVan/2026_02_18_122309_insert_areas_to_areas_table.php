<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */


    public function up(): void
    {
        $areas = [
            ['nombre' => 'Alcaldía', 'parent_id' => null, 'sede_id' => 1, 'nombre_corto' => 'ALC', 'orden' => 1],
            ['nombre' => 'Órgano de Control Institucional', 'parent_id' => 1, 'sede_id' => 6, 'nombre_corto' => 'OCI', 'orden' => 4],
            ['nombre' => 'Procuraduría Pública Municipal', 'parent_id' => 1, 'sede_id' => 1, 'nombre_corto' => 'PPM', 'orden' => 5],
            ['nombre' => 'Oficina General de Secretaría de Concejo', 'parent_id' => 1, 'sede_id' => 1, 'nombre_corto' => 'SG', 'orden' => 3],
            ['nombre' => 'Oficina de Gestión Documentaria y Atención al Ciudadano', 'parent_id' => 1, 'sede_id' => 1, 'nombre_corto' => 'SGGDAC', 'orden' => 41],
            ['nombre' => 'Subgerencia de Serenazgo', 'parent_id' => 40, 'sede_id' => 1, 'nombre_corto' => 'SGS', 'orden' => 40],
            ['nombre' => 'Gerencia Municipal', 'parent_id' => 1, 'sede_id' => 1, 'nombre_corto' => 'GM', 'orden' => 2],
            ['nombre' => 'Oficina General de Administración y Finanzas', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'GAF', 'orden' => 8],
            ['nombre' => 'Oficina General de Asesoría Jurídica', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'GAJ', 'orden' => 9],
            ['nombre' => 'Subgerencia de Gestión del Riesgo de Desastres y Defensa Civil', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'GPPDI', 'orden' => 18],
            ['nombre' => 'Gerencia de Administración Tributaria', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'GATR', 'orden' => 9],
            ['nombre' => 'Gerencia de Desarrollo Urbano', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'GDU', 'orden' => 12],
            ['nombre' => 'Gerencia de Desarrollo Económico e Inversión Privada', 'parent_id' => 7, 'sede_id' => 4, 'nombre_corto' => 'GGRDDC', 'orden' => 13],
            ['nombre' => 'Gerencia de Desarrollo Sostenible y Servicios a la Ciudad', 'parent_id' => 7, 'sede_id' => 3, 'nombre_corto' => 'GDSSC', 'orden' => 14],
            ['nombre' => 'Oficina General de Planeamiento, Presupuesto y Desarrollo Institucional', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'GDEIP', 'orden' => 15],
            ['nombre' => 'Oficina de Tecnologías de la Información y Estadística', 'parent_id' => 7, 'sede_id' => 5, 'nombre_corto' => 'GTI', 'orden' => 16],
            ['nombre' => 'Gerencia de Desarrollo Humano y Educación', 'parent_id' => 7, 'sede_id' => 2, 'nombre_corto' => 'GDHE', 'orden' => 17],
            ['nombre' => 'Subgerencia de Movilidad Sostenible y Transitabilidad', 'parent_id' => 7, 'sede_id' => 5, 'nombre_corto' => 'GMS', 'orden' => 18],
            ['nombre' => 'Oficina de Gestión de Recursos Humanos', 'parent_id' => 8, 'sede_id' => 1, 'nombre_corto' => 'SGTH', 'orden' => 19],
            ['nombre' => 'Subgerencia de Contabilidad y Costos', 'parent_id' => 8, 'sede_id' => 1, 'nombre_corto' => 'SGCC', 'orden' => 20],
            ['nombre' => 'Subgerencia de Tesorería', 'parent_id' => 8, 'sede_id' => 1, 'nombre_corto' => 'SGT', 'orden' => 21],
            ['nombre' => 'Oficina de Abastecimiento', 'parent_id' => 8, 'sede_id' => 1, 'nombre_corto' => 'SGL', 'orden' => 22],
            ['nombre' => 'Subgerencia de Servicios Generales y Patrimonio', 'parent_id' => 8, 'sede_id' => 1, 'nombre_corto' => 'SGSGP', 'orden' => 23],
            ['nombre' => 'Subgerencia de Recaudación y Ejecutoria Coactiva', 'parent_id' => 11, 'sede_id' => 1, 'nombre_corto' => 'SGREC', 'orden' => 24],
            ['nombre' => 'Subgerencia de Registro y Fiscalización Tributaria', 'parent_id' => 11, 'sede_id' => 1, 'nombre_corto' => 'SGRFT', 'orden' => 25],
            ['nombre' => 'Subgerencia de Obras Privadas', 'parent_id' => 12, 'sede_id' => 1, 'nombre_corto' => 'SGOP', 'orden' => 26],
            ['nombre' => 'Subgerencia de Operaciones Ambientales', 'parent_id' => 14, 'sede_id' => 1, 'nombre_corto' => 'SGOA', 'orden' => 27],
            ['nombre' => 'Subgerencia de Fiscalización Administrativa', 'parent_id' => 13, 'sede_id' => 2, 'nombre_corto' => 'SGFA', 'orden' => 28],
            ['nombre' => 'Subgerencia de Planeamiento, Inversiones y Modernización del Estado', 'parent_id' => 15, 'sede_id' => 1, 'nombre_corto' => 'SGPIME', 'orden' => 29],
            ['nombre' => 'Subgerencia de Programas Sociales y Salud', 'parent_id' => 17, 'sede_id' => 2, 'nombre_corto' => 'SGPSS', 'orden' => 30],
            ['nombre' => 'Pensionista*', 'parent_id' => 19, 'sede_id' => 1, 'nombre_corto' => 'PENS', 'orden' => 44],
            ['nombre' => 'Oficina General de Comunicaciones e Imagen Institucional', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'GCII', 'orden' => 32],
            ['nombre' => 'Oficina General de Integridad Institucional', 'parent_id' => 1, 'sede_id' => 1, 'nombre_corto' => 'GCI', 'orden' => 33],
            ['nombre' => 'Subgerencia de Ecología y Ornato', 'parent_id' => 14, 'sede_id' => 1, 'nombre_corto' => 'SGEO', 'orden' => 34],
            ['nombre' => 'Subgerencia de Educación, Cultura Y Turismo', 'parent_id' => 17, 'sede_id' => 1, 'nombre_corto' => 'SGECT', 'orden' => 35],
            ['nombre' => 'Subgerencia de Integración, Deporte y Bienestar Social', 'parent_id' => 17, 'sede_id' => 1, 'nombre_corto' => 'SGIDBS', 'orden' => 36],
            ['nombre' => 'Subgerencia de Obras Públicas', 'parent_id' => 12, 'sede_id' => 1, 'nombre_corto' => 'SGOPV', 'orden' => 37],
            ['nombre' => 'Subgerencia de Habilitaciones Urbanas, Planeamiento Urbano y Catastro', 'parent_id' => 12, 'sede_id' => 1, 'nombre_corto' => 'SGHUPUC', 'orden' => 38],
            ['nombre' => 'Subgerencia de Promoción  Empresarial y Autorizaciones', 'parent_id' => 13, 'sede_id' => 1, 'nombre_corto' => 'SGPEA', 'orden' => 39],
            ['nombre' => 'Gerencia de Seguridad Ciudadana', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'GSC', 'orden' => 6],
            ['nombre' => 'Gerencia de Participación Vecinal', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'GPV', 'orden' => 7],
            ['nombre' => 'Oficina de Planeamiento, Inversiones y Modernización Institucional', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'OPIM', 'orden' => 40],
            ['nombre' => 'Subgerencia de Gestión de Inversiones', 'parent_id' => 7, 'sede_id' => 1, 'nombre_corto' => 'SEP', 'orden' => 41],
            ['nombre' => 'SIN AREA', 'parent_id' => null, 'sede_id' => null, 'nombre_corto' => 'SA', 'orden' => 999],
        ];
        
        DB::table('visitas.areas')->insert($areas);

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::statement('ALTER TABLE visitas.areas DISABLE TRIGGER ALL;');
        DB::table('visitas.areas')->delete();
        // DB::statement('ALTER TABLE visitas.areas ENABLE TRIGGER ALL;');
    }
};
