<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\CertificadoLicenciaFuncionamiento;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Services\Sil\Licencias\LicenciaService;

class CreateCertificadoLicenciaFuncionamiento extends CreateRecord
{
    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;
    protected static ?string $title = 'Registro de Certificado de Licencia de Funcionamiento';
    protected static bool $canCreateAnother = false;
    /**
     * Intercepta los datos antes de crear el registro
     * Reorganiza los datos por secciones: expediente, catastro, licencias
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Mantenemos este método por si Filament necesita hacer algo con los datos,
        // pero la lógica principal se ha movido a handleRecordCreation.
        // Solo retornamos los datos tal cual.
        return $data;
    }

    /**
     * Maneja la creación del registro usando el procedimiento almacenado
     */
    protected function handleRecordCreation(array $data): Model
    {
        // Reorganizar datos por secciones
        $camposExpediente = ['exp_num', 'exp_fec', 'exp_nomrec', 'numdoc', 'numtel', 'correo', 'domfis', 'exp_nomrec_id', 'exp_razsoc_id', 'exp_razsoc'];
        $camposCatastro = ['fiu_id', 'coduca', 'codpredio', 'descurb', 'via_completa', 'numvia', 'intdpto', 'blockedif', 'mz', 'lote', 'zonificacion', 'area_economica'];
        $camposLicencias = ['proccodigo', 'procnivel', 'nir_id', 'nir_descripcion', 'tipo_resolucion', 'n_resolucion', 'fecha_resolucion', 'numero_licencia', 'tipo_licencia', 'fecha_emision', 'fecha_vencimiento', 'mype', 'compatibilidad', 'nro_compatibilidad', 'fecha_compatibilidad', 'horario_atencion', 'hora_inicio', 'hora_fin', 'direccion', 'tipo_establecimientos', 'centro_comercial', 'tipo_local', 'local', 'observaciones_local', 'giros_seleccionar', 'tabla_giros', 'observaciones'];

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

        // LOG: Verificar tipo y valor del campo mype antes de enviar al servicio
        \Log::info('=== DEBUG MYPE FIELD ===', [
            'mype_value' => $datosOrganizados['licencias']['mype'] ?? 'NOT_SET',
            'mype_type' => gettype($datosOrganizados['licencias']['mype'] ?? null),
            'mype_is_string' => is_string($datosOrganizados['licencias']['mype'] ?? null),
            'mype_is_bool' => is_bool($datosOrganizados['licencias']['mype'] ?? null),
            'mype_strict_equals_1' => ($datosOrganizados['licencias']['mype'] ?? null) === '1',
            'mype_strict_equals_true' => ($datosOrganizados['licencias']['mype'] ?? null) === true,
            'mype_loose_equals_1' => ($datosOrganizados['licencias']['mype'] ?? null) == '1',
            'mype_loose_equals_true' => ($datosOrganizados['licencias']['mype'] ?? null) == true,
        ]);

        // LOG: Datos completos organizados
        \Log::info('Datos organizados antes de enviar al servicio', [
            'expediente' => $datosOrganizados['expediente'],
            'catastro' => $datosOrganizados['catastro'],
            'licencias' => $datosOrganizados['licencias'],
        ]);

        $service = app(LicenciaService::class);

        try {
            $result = $service->create($datosOrganizados);

            \Log::info('Procedimiento almacenado ejecutado', ['result' => $result]);

            // Validar que el resultado no esté vacío
            if (empty($result) || !isset($result[0])) {
                Notification::make()
                    ->title('Error al guardar')
                    ->body('El procedimiento almacenado no retornó ningún resultado.')
                    ->danger()
                    ->send();

                $this->halt();
            }

            $spResult = $result[0];

            // Verificar el status del procedimiento almacenado
            $status = $spResult->status ?? 0;
            $message = $spResult->message ?? 'Error desconocido';
            $newId = $spResult->new_id ?? null;

            \Log::info('Resultado del SP', [
                'status' => $status,
                'message' => $message,
                'new_id' => $newId
            ]);

            // Si el status es error (< 1), detener el proceso
            if ($status < 1) {
                Notification::make()
                    ->title('Error al guardar')
                    ->body($message)
                    ->danger()
                    ->send();

                $this->halt();
            }

            // Si no hay new_id, también es un error
            if (!$newId) {
                Notification::make()
                    ->title('Error al guardar')
                    ->body('No se pudo obtener el ID del registro creado.')
                    ->danger()
                    ->send();

                $this->halt();
            }

            // Crear el modelo con el ID correcto
            $model = new CertificadoLicenciaFuncionamiento();
            $model->exists = true;
            $model->lic_id = $newId;

            // Hidratar otros atributos del modelo si es necesario
            $model->lic_expnum = $datosOrganizados['expediente']['exp_num'] ?? null;
            $model->lic_numlic = $datosOrganizados['licencias']['numero_licencia'] ?? null;

            // Si hay cin_id seleccionado, actualizar CertificadoInspeccion con el número de licencia
            $cinId = $data['_cin_id_seleccionado'] ?? null;
            $numeroLicencia = $datosOrganizados['licencias']['numero_licencia'] ?? null;

            \Log::info('=== DEBUG ITSE UPDATE ===', [
                'cin_id_seleccionado' => $cinId,
                'cin_id_type' => gettype($cinId),
                'numero_licencia' => $numeroLicencia,
                'numero_licencia_type' => gettype($numeroLicencia),
                'data_keys' => array_keys($data),
                'has_cin_id_key' => array_key_exists('_cin_id_seleccionado', $data),
            ]);

            if ($cinId && $numeroLicencia) {
                \Log::info('Entrando al bloque de actualización ITSE');

                $itse = \App\Models\CertificadoInspeccion::find($cinId);

                \Log::info('Resultado de búsqueda ITSE', [
                    'itse_found' => $itse ? true : false,
                    'itse_id' => $itse->cin_id ?? null,
                    'itse_current_licencia' => $itse->cin_licencia ?? null,
                ]);

                if ($itse) {
                    $itse->cin_licencia = $numeroLicencia;
                    $saved = $itse->save();

                    \Log::info('ITSE actualizada con número de licencia', [
                        'cin_id' => $cinId,
                        'cin_licencia' => $numeroLicencia,
                        'save_result' => $saved,
                    ]);
                }
            } else {
                \Log::info('No se actualiza ITSE - faltan datos', [
                    'cin_id_empty' => empty($cinId),
                    'numero_licencia_empty' => empty($numeroLicencia),
                ]);
            }

            // Mostrar notificación de éxito
            Notification::make()
                ->title('Registro creado exitosamente')
                ->body($message)
                ->success()
                ->send();

            return $model;

        } catch (\Exception $e) {
            \Log::error('Error al ejecutar procedimiento almacenado: ' . $e->getMessage());

            Notification::make()
                ->title('Error al guardar')
                ->body('Ocurrió un error al guardar la licencia: ' . $e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
