<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Resetear la caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Definir Permisos específicos por Resource
        $permissions = [
            // Permisos para CertificadoInspeccionResource
            'view::certificado_inspeccion',
            // Permisos para CertificadoLicenciaFuncionamientoResource
            'view::certificado_licencia_funcionamiento',
            // Permisos para LicenciaRelacionResource
            'view::licencia_relacion',
            //Permisos para Persona
            'view::persona',
            //Permisos para Roles
            'view::roles',
            //Permisos para Modulos
            'view::modules',
            //Permisos para Usuarios
            'view::users',

            //Permisos para Permisos
            'view::permissions',

            //Permisos para CertificadoBorrado
            'view::certificado_borrado',

            //Permisos para TipoLicencias
            'view::tipo_licencias',

            //Permisos para TipoResoluciones    
            'view::tipo_resoluciones',

            //Permisos para AuditoriaLicencias
            'view::auditoria_licencias',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Crear rol de Administrador
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $spea_user = Role::firstOrCreate(['name' => 'SPEA User']);

        // 4. Asignar todos los permisos para administrador
        $admin->givePermissionTo(Permission::all());

    }
}