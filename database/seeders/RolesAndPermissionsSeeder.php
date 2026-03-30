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

            //Permisos para DataLevantamiento
            'view::data_levantamiento',

            //Permisos para Ver Ticket
            'view::solicitud_permisos',
            //Permisos para Editar Ticket
            'edit::solicitud_permisos',

            //Permisos para acciones de CertificadoLicenciaFuncionamiento
            'create::certificado_licencia_funcionamiento', // Registrar Nueva Licencia
            'edit::certificado_licencia_funcionamiento', // Modificar licencia
            'view_details::certificado_licencia_funcionamiento', // Ver detalles de la licencia
            'view_qr::certificado_licencia_funcionamiento', // Ver QR
            'view_itse::certificado_licencia_funcionamiento', // Ver Certificados ITSE
            'generate_certificate::certificado_licencia_funcionamiento', // Generar certificado
            'upload_pdf::certificado_licencia_funcionamiento', // Subir PDF actualizado
            'upload_compatibility::certificado_licencia_funcionamiento', // Subir compatibilidad
            'duplicate::certificado_licencia_funcionamiento', // Duplicar licencia
            'transfer::certificado_licencia_funcionamiento', // Transferir licencia
            'rectify::certificado_licencia_funcionamiento', // Rectificar licencia
            'assign::certificado_licencia_funcionamiento', // Cesionar licencia
            'deactivate::certificado_licencia_funcionamiento', // Dar de baja licencia

            //Permisos para acciones de CertificadoInspeccion
            'create::certificado_inspeccion', // Registrar Certificado de Inspección
            'view_details::certificado_inspeccion', // Ver detalles
            'edit::certificado_inspeccion', // Editar certificado
            'delete::certificado_inspeccion', // Borrar certificado
            'view_pdf_original::certificado_inspeccion', // Ver PDF original
            'upload_pdf::certificado_inspeccion', // Subir PDF actualizado
            'upload_anexos::certificado_inspeccion', // Subir anexos
            'export::certificado_inspeccion', // Exportar certificados

            //Permisos para acciones de LicenciasLevantamientos
            'view_actions::licencias_levantamientos', // Ver acciones realizadas
            'view_data::licencias_levantamientos', // Ver data del levantamiento
            'perform_actions::licencias_levantamientos', // Realizar acciones
            'view_photo::licencias_levantamientos', // Ver foto levantamiento
            'view_itse::licencias_levantamientos', // Ver certificados ITSE

            //Permisos para acciones de Personas
            'create::persona', // Crear persona
            'edit::persona', // Editar persona


            //Permisos para RR.HH
            'view::rrhh',
            'view::asistencias',
            'view::informe_actividades',

            //PERMISOS PARA VISITAS DEL MODULO AREAS

            'view::visitas_area',              // Ver listado de visitas
            'edit::visitas_area',         // Ver detalle o información específica de una visita
            'create::visitas_area',            // Registrar/Crear una nueva visita
            'audit::visitas_area',

            //VER, CREAR Y EDITAR DE CARGO
            'view::visitas_cargo',              // Ver listado de visitas
            'edit::visitas_cargo',         // Ver detalle o información específica de una visita
            'create::visitas_cargo',            // Registrar/Crear una nueva visita
            'audit::visitas_cargo',

            //VER, CREAR Y EDITAR DE CLASIFICACION
            'view::visitas_clasificacion',              // Ver listado de visitas
            'edit::visitas_clasificacion',         // Ver detalle o información específica de una visita
            'create::visitas_clasificacion',            // Registrar/Crear una nueva visita
            'audit::visitas_clasificacion',

            //VER, CREAR Y EDITAR DE SEDE
            'view::visitas_sede',              // Ver listado de visitas
            'edit::visitas_sede',         // Ver detalle o información específica de una visita
            'create::visitas_sede',            // Registrar/Crear una nueva visita
            'audit::visitas_sede',

            //VER, CREAR Y EDITAR DE TIPO_DOCUMENTO
            'view::visitas_tipo_documento',              // Ver listado de visitas
            'edit::visitas_tipo_documento',         // Ver detalle o información específica de una visita
            'create::visitas_tipo_documento',            // Registrar/Crear una nueva visita
            'audit::visitas_tipo_documento',


            //VER, CREAR Y EDITAR DE TRABAJADOR
            'view::visitas_trabajador',              // Ver listado de visitas
            'edit::visitas_trabajador',         // Ver detalle o información específica de una visita
            'create::visitas_trabajador',            // Registrar/Crear una nueva visita
            'audit::visitas_trabajador',

            //VER, CREAR Y EDITAR DE VISITA
            'view::visitas_visita',              // Ver listado de visitas
            'edit::visitas_visita',         // Ver detalle o información específica de una visita
            'create::visitas_visita',            // Registrar/Crear una nueva visita
            'audit::visitas_visita',
            //VER, CREAR Y EDITAR DE REGIMEN
            'view::visitas_regimen',              // Ver listado de visitas
            'edit::visitas_regimen',         // Ver detalle o información específica de una visita
            'create::visitas_regimen',            // Registrar/Crear una nueva visita
            'audit::visitas_regimen',
            //PERMISOS PARA LOTE 1 (ZONIFICACION DE SECTORES)
            'view::lote1',
            'edit::lote1',

            //VER, CREAR Y EDITAR DE Motivo
            'view::visitas_motivo',              // Ver listado de visitas
            'edit::visitas_motivo',         // Ver detalle o información específica de una visita
            'create::visitas_motivo',            // Registrar/Crear una nueva visita
            'audit::visitas_motivo',

            
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