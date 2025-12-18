<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Resources\Pages\EditRecord;
use App\Services\Sil\Licencias\GiroLicenciaService;
use App\Services\Sil\Licencias\LicenciaService;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class TransferirCertificadoLicenciaFuncionamiento extends EditRecord
{
    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;

    protected static ?string $title = 'Transferir Certificado de Licencia de Funcionamiento';

    protected function getHeaderActions(): array
    {
        return [
            // No actions needed for transfer page
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $service = app(LicenciaService::class);
        $row = $service->obtenerDatosPorIdLicenciaDirecta($this->record->lic_id);

        if (!$row) {
            return $data;
        }

        // Mapeo de campos usando los nombres de columna del stored procedure
        // El SP retorna columnas en MAYÚSCULAS
        $mapaSimple = [
            // Expediente
            'exp_num' => 'EXPEDIENTE_NRO',
            'exp_fec' => 'lic_expfec',
            'exp_nomrec' => 'PERSONA_SOLICITANTE',
            'exp_nomrec_id' => 'PERSONA_SOLICITANTE_ID',
            'exp_razsoc' => 'RAZON_SOCIAL',
            'exp_razsoc_id' => 'PERSONA_RAZON_SOCIAL_ID',
            'numdoc' => 'RUC',
            'numtel' => 'TELEFONO',
            'correo' => 'EMAIL',
            'domfis' => 'UBICACION',

            // Catastro - El SP ya incluye estos datos
            'codpredio' => 'CODIGO_PREDIAL',
            'descurb' => 'URBANIZACION',
            'direccion' => 'LIC_DIRECCION',
            'via_completa' => 'CALLE',
            'numvia' => 'N',
            'blockedif' => 'blockedif',
            'mz' => 'MZ',
            'lote' => 'LT',
            'zonificacion' => 'ZONIFICACION',
            'area_economica' => 'AREA',
            'fiu_id' => 'fiu_id',
            'coduca' => 'CODIGO_CATASTRAL',

            // Licencia
            'nir_id' => 'nir_id',
            'nir_descripcion' => 'nir_descripcion',
            'tipo_resolucion' => 'tir_id',
            'n_resolucion' => 'RESOLUCION_NRO',
            'numero_licencia' => 'NUMERO_LICENCIA',
            'tipo_licencia' => 'CODIGO_TIPO',
            'compatibilidad' => 'lic_compatibilidad',
            'nro_compatibilidad' => 'lic_compatibilidadnumero',
            'hora_inicio' => 'lic_horainicio',
            'hora_fin' => 'lic_horafin',
            'observaciones' => 'OBSERVACIONES',
            'centro_comercial' => 'cec_id',
            'tipo_local' => 'tlo_id',
            'local' => 'lcc_local',
            'observaciones_local' => 'lcc_observacion',
        ];

        foreach ($mapaSimple as $formField => $dbColumn) {
            $data[$formField] = $row->$dbColumn ?? null;
        }

        // Tipo de establecimiento
        if (isset($row->tes_id)) {
            $data['tipo_establecimientos'] = $row->tes_id;
        }

        // Manejo de fechas - El SP retorna fechas como objetos date o null
        $data['fecha_resolucion'] = $row->RESOLUCION_FECHA
            ? Carbon::parse($row->RESOLUCION_FECHA)->toDateString()
            : null;

        $data['fecha_emision'] = $row->FECHA_EMISION
            ? Carbon::parse($row->FECHA_EMISION)->toDateString()
            : null;

        $data['fecha_compatibilidad'] = $row->lic_compatibilidadfecha
            ? Carbon::parse($row->lic_compatibilidadfecha)->toDateString()
            : null;

        // Convertir MYPE a string para el componente Radio
        $valorMype = strtolower((string) ($row->MYPE ?? 'false'));
        $esMype = in_array($valorMype, ['true', 't', '1', 'on', 's', 'si'], true);

        $data['mype'] = $esMype ? '1' : '0';

        // Cargar Giros asociados a la licencia
        $giroService = app(GiroLicenciaService::class);
        $girosLicencia = $giroService->obtenerGirosPorIdLicencia($this->record->lic_id);

        $girosIds = [];
        $tablaGiros = [];

        foreach ($girosLicencia as $giroLicencia) {
            $girosIds[] = $giroLicencia->gir_id;
            $tablaGiros[] = [
                'giro' => $giroLicencia->gir_descripcion ?? '',
                'giro_especifico' => $giroLicencia->lig_giroespecifico ?? '',
            ];
        }

        $data['giros_seleccionar'] = $girosIds;
        $data['tabla_giros'] = $tablaGiros;

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Preparar giros para transferencia
        $giros = [];
        $selectedIds = $data['giros_seleccionar'] ?? [];
        $repeaterItems = $data['tabla_giros'] ?? [];

        // Create a map of Giro Name -> Specifico from repeater
        $specificMap = [];
        foreach ($repeaterItems as $item) {
            if (isset($item['giro'])) {
                $specificMap[$item['giro']] = $item['giro_especifico'] ?? '';
            }
        }

        // Map ID -> Name
        $giroService = app(GiroLicenciaService::class);
        $allGiros = $giroService->buscarGiros('');
        $idToName = $allGiros->pluck('gir_descripcion', 'gir_id')->toArray();

        foreach ($selectedIds as $girId) {
            $name = $idToName[$girId] ?? '';
            $specific = $specificMap[$name] ?? '';

            $giros[] = [
                'gir_id' => $girId,
                'giro_especifico' => $specific,
            ];
        }

        // Preparar datos para el servicio de transferencia
        // Este servicio usa spu_licencia_upd_transferir2
        $params = [
            // ID de la licencia original (importante para el SP)
            'lic_id_ori' => $record->lic_id,

            // Datos base del formulario
            'tli_id' => $data['tipo_licencia'],
            'tes_id' => $data['tipo_establecimientos'],
            'per_idsolicitante' => $data['exp_nomrec_id'] ?? 0,
            'per_idrazonsocial' => $data['exp_razsoc_id'] ?? 0,
            'lic_numlic' => $data['numero_licencia'] ?? '',
            'lic_codigopredial' => $data['codpredio'] ?? '',
            'lic_expnum' => $data['exp_num'],
            'lic_area' => $data['area_economica'] ?? 0,

            // Conversión Booleana
            'lic_mype' => $data['mype'] == '1',

            // Datos de Resolución y Fechas
            'lic_resnum' => $data['n_resolucion'] ?? '',
            'lic_fecharesolucion' => $data['fecha_resolucion'],
            'lic_fechaemision' => $data['fecha_emision'],
            'lic_fechavencimiento' => null,

            // Observaciones y Catastro
            'lic_licobs' => $data['observaciones'] ?? '',
            'fiu_id' => $data['fiu_id'] ?? 0,
            'lca_descripcion' => $data['direccion'] ?? '',
            'urbanizacion_id' => $data['coduca'] ?? '', // Código de urbanización
            'lca_zonificacion' => $data['zonificacion'] ?? '',

            // Centro Comercial
            'cec_id' => $data['centro_comercial'] ?? 0,
            'tlo_id' => $data['tipo_local'] ?? 0,
            'lcc_observacion' => $data['observaciones_local'] ?? '',
            'lcc_local' => $data['local'] ?? '',

            // Horario y Compatibilidad
            'lic_horainicio' => $data['hora_inicio'] ?? '09:00',
            'lic_horafin' => $data['hora_fin'] ?? '18:00',
            'tir_id' => $data['tipo_resolucion'] ?? 2,
            'lic_nota' => $data['observaciones'] ?? '',
            'nir_id' => $data['nir_id'] ?? 0,

            // Valores por defecto/estáticos
            'lic_giro' => '', // Se mantiene vacío
            'lic_modidirecc' => false,

            // Giros
            'giros' => $giros,
        ];

        try {
            $service = app(LicenciaService::class);
            $resultado = $service->transfer($params);

            // El SP retorna error > 0 para éxito (el nuevo lic_id)
            if (!empty($resultado) && isset($resultado[0])) {
                $spResult = $resultado[0];
                $nuevoLicId = $spResult->error ?? 0;

                if ($nuevoLicId > 0) {
                    Notification::make()
                        ->title('Licencia transferida exitosamente')
                        ->body("Nueva licencia creada con ID: {$nuevoLicId}. La licencia original ha sido marcada como transferida.")
                        ->success()
                        ->send();

                    // Redirigir a la lista
                    $this->redirect(CertificadoLicenciaFuncionamientoResource::getUrl('index'));
                } else {
                    throw new \Exception($spResult->mensaje ?? 'Error desconocido al transferir');
                }
            } else {
                throw new \Exception('No se recibió respuesta del servidor');
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al transferir licencia')
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw $e;
        }

        return $record;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Transferir Licencia')
                ->icon('heroicon-o-arrow-path'),
            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
