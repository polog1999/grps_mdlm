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
            'view_certificado_inspeccion',

            // Permisos para CertificadoLicenciaFuncionamientoResource
            'view_certificado_licencia_funcionamiento',

            // Permisos para LicenciaRelacionResource
            'view_licencia_relacion',

            //Permisos para Persona
            'view_persona',

            //Permisos para Roles
            'view_roles',

            //Permisos para Usuarios
            'view_usuarios',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Crear rol de Administrador
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $spea_user = Role::firstOrCreate(['name' => 'SPEA User']);

        // 4. Asignar todos los permisos para administrador
        $admin->givePermissionTo(Permission::all());
        $spea_user->givePermissionTo([
            'view_certificado_inspeccion',
            'view_certificado_licencia_funcionamiento',
            'view_licencia_relacion',
            'view_persona',
        ]);

    }
}