<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCertificadoLicenciaFuncionamiento extends CreateRecord
{
    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;
    protected static ?string $title = 'Registro de Certificado de Licencia de Funcionamiento';
    
    /**
     * Intercepta los datos antes de crear el registro
     * Reorganiza los datos por secciones: expediente, catastro, licencias
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Definir qué campos pertenecen a cada sección
        $camposExpediente = ['exp_num', 'exp_fec', 'exp_nomrec', 'numdoc', 'numtel', 'correo', 'domfis'];
        $camposCatastro = ['fiu_id','coduca', 'codpredio', 'descurb', 'via_completa', 'numvia', 'intdpto', 'blockedif', 'mz', 'lote', 'zonificacion', 'area_economica'];
        $camposLicencias = ['proccodigo', 'procnivel', 'nir_id', 'nir_descripcion', 'tipo_resolucion', 'n_resolucion', 'fecha_resolucion', 'numero_licencia', 'tipo_licencia', 'fecha_emision', 'mype', 'compatibilidad', 'nro_compatibilidad', 'fecha_compatibilidad', 'horario_atencion', 'hora_inicio', 'hora_fin', 'direccion', 'tipo_establecimientos', 'giros_seleccionar', 'tabla_giros', 'observaciones'];
        
        // Reorganizar datos por secciones
        $datosOrganizados = [
            'expediente' => [],
            'catastro' => [],
            'licencias' => [],
        ];
        
        foreach ($data as $campo => $valor) {
            if (in_array($campo, $camposExpediente)) {
                $datosOrganizados['expediente'][$campo] = $valor;
            } elseif (in_array($campo, $camposCatastro)) {
                $datosOrganizados['catastro'][$campo] = $valor;
            } elseif (in_array($campo, $camposLicencias)) {
                $datosOrganizados['licencias'][$campo] = $valor;
            }
        }
        
        // Log de datos organizados
        \Log::info('=== DATOS DEL FORMULARIO ORGANIZADOS POR SECCIONES ===');
        \Log::info('Expediente:', $datosOrganizados['expediente']);
        \Log::info('Catastro:', $datosOrganizados['catastro']);
        \Log::info('Licencias:', $datosOrganizados['licencias']);
        
        // JSON formateado
        \Log::info('JSON Completo:', [
            'json' => json_encode($datosOrganizados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        ]);
        
        // Opción: Ver en pantalla (descomenta para debug)
        // dd($datosOrganizados);
        
        // Retornar datos originales para que Filament los guarde normalmente
        return $data;
    }
    
    /**
     * Maneja la creación del registro usando el procedimiento almacenado
     */

    /*
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Reorganizar datos por secciones
        $camposExpediente = ['exp_num', 'exp_fec', 'exp_nomrec', 'numdoc', 'numtel', 'correo', 'domfis'];
        $camposCatastro = ['fiu_id','coduca', 'codpredio', 'descurb', 'via_completa', 'numvia', 'intdpto', 'blockedif', 'mz', 'lote', 'zonificacion', 'area_economica'];
        $camposLicencias = ['proccodigo', 'procnivel', 'nir_id', 'nir_descripcion', 'tipo_resolucion', 'n_resolucion', 'fecha_resolucion', 'numero_licencia', 'tipo_licencia', 'fecha_emision', 'mype', 'compatibilidad', 'nro_compatibilidad', 'fecha_compatibilidad', 'horario_atencion', 'hora_inicio', 'hora_fin', 'direccion', 'tipo_establecimientos', 'giros_seleccionar', 'tabla_giros', 'observaciones'];
        
        $datosOrganizados = [
            'expediente' => [],
            'catastro' => [],
            'licencias' => [],
        ];
        
        foreach ($data as $campo => $valor) {
            if (in_array($campo, $camposExpediente)) {
                $datosOrganizados['expediente'][$campo] = $valor;
            } elseif (in_array($campo, $camposCatastro)) {
                $datosOrganizados['catastro'][$campo] = $valor;
            } elseif (in_array($campo, $camposLicencias)) {
                $datosOrganizados['licencias'][$campo] = $valor;
            }
        }
        
        // Ejecutar procedimiento almacenado
        $service = app(\App\Services\Sil\Licencias\LicenciaInsertService::class);
        
        try {
            $result = $service->insertarLicencia($datosOrganizados);
            
            \Log::info('Procedimiento almacenado ejecutado exitosamente', ['result' => $result]);
            
            // Crear un modelo temporal para que Filament no falle
            // TODO: Ajustar según lo que retorne el procedimiento
            $model = new \App\Models\CertificadoLicenciaFuncionamiento();
            $model->exists = true;
            $model->id = $result[0]->lic_id ?? 1; // Ajustar según el retorno del SP
            
            return $model;
        } catch (\Exception $e) {
            \Log::error('Error al ejecutar procedimiento almacenado: ' . $e->getMessage());
            throw $e;
        }
    }*/
}
