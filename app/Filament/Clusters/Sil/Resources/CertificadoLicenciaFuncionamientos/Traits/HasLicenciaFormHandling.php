<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Traits;

use App\Services\Sil\Licencias\GiroLicenciaService;
use App\Services\Sil\Licencias\LicenciaService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

trait HasLicenciaFormHandling
{
    /**
     * Llena los datos del formulario a partir del registro existente.
     */
    protected function fillLicenciaFormData(array $data, Model $record, bool $clearLicenseNumber = false): array
    {
        $service = app(LicenciaService::class);
        $row = $service->obtenerDatosPorIdLicenciaDirecta($record->lic_id);

        if (!$row) {
            return $data;
        }

        // Mapeo de campos usando los nombres de columna del stored procedure
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

            // Catastro
            'codpredio' => 'CODIGO_PREDIAL',
            'descurb' => 'URBANIZACION',
            'direccion' => 'LIC_DIRECCION',
            'via_completa' => 'VIA',
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

        if ($clearLicenseNumber) {
            $data['numero_licencia'] = '';
        }

        // Tipo de establecimiento
        if (isset($row->tes_id)) {
            $data['tipo_establecimientos'] = $row->tes_id;
        }

        // Manejo de fechas
        $data['fecha_resolucion'] = $row->RESOLUCION_FECHA
            ? Carbon::parse($row->RESOLUCION_FECHA)->toDateString()
            : null;

        $data['fecha_emision'] = $row->FECHA_EMISION
            ? Carbon::parse($row->FECHA_EMISION)->toDateString()
            : null;

        $data['fecha_vencimiento'] = $row->FECHA_VENCIMIENTO
            ? Carbon::parse($row->FECHA_VENCIMIENTO)->toDateString()
            : null;

        $data['fecha_compatibilidad'] = $row->lic_compatibilidadfecha
            ? Carbon::parse($row->lic_compatibilidadfecha)->toDateString()
            : null;

        // Limpiar campos de compatibilidad al duplicar/transferir
        if ($clearLicenseNumber) {
            $data['compatibilidad'] = null;
            $data['nro_compatibilidad'] = null;
            $data['fecha_compatibilidad'] = null;
            $data['observaciones'] = null;
        }


        // Convertir MYPE
        $valorMype = strtolower((string) ($row->MYPE ?? 'false'));
        $esMype = in_array($valorMype, ['true', 't', '1', 'on', 's', 'si'], true);
        $data['mype'] = $esMype ? '1' : '0';

        // Cargar Giros
        $giroService = app(GiroLicenciaService::class);
        $girosLicencia = $giroService->obtenerGirosPorIdLicencia($record->lic_id);

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

    /**
     * Prepara los parámetros comunes para los servicios de licencia.
     */
    protected function getCommonLicenciaParams(array $data): array
    {
        return [
            'tli_id' => $data['tipo_licencia'],
            'tes_id' => $data['tipo_establecimientos'],
            'per_idsolicitante' => $data['exp_nomrec_id'] ?? 0,
            'per_idrazonsocial' => $data['exp_razsoc_id'] ?? 0,
            'lic_numlic' => $data['numero_licencia'] ?? '',
            'lic_codigopredial' => $data['codpredio'] ?? '',
            'lic_expnum' => $data['exp_num'],
            'lic_expfec' => $data['exp_fec'],
            'lic_direccion' => $data['direccion'] ?? '',
            'lic_urbanizacion' => $data['urbanizacion'] ?? '',
            'lic_area' => $data['area_economica'] ?? 0,
            'lic_mype' => $data['mype'] == '1',
            'lic_resnum' => $data['n_resolucion'] ?? '',
            'lic_fecharesolucion' => $data['fecha_resolucion'],
            'lic_fechaemision' => $data['fecha_emision'],
            'lic_fechavencimiento' => $data['fecha_vencimiento'] ?? null,
            'lic_licobs' => $data['observaciones'] ?? '',
            'fiu_id' => $data['fiu_id'] ?? 0,
            'lca_zonificacion' => $data['zonificacion'] ?? '',
            'cec_id' => $data['centro_comercial'] ?? 0,
            'tlo_id' => $data['tipo_local'] ?? 0,
            'lcc_observacion' => $data['observaciones_local'] ?? '',
            'lcc_local' => $data['local'] ?? '',
            'lic_horainicio' => $data['hora_inicio'] ?? '09:00',
            'lic_horafin' => $data['hora_fin'] ?? '18:00',
            'lic_compatibilidad' => $data['compatibilidad'] ?? '',
            'lic_compatibilidadnumero' => $data['nro_compatibilidad'] ?? '',
            'lic_compatibilidadfecha' => $data['fecha_compatibilidad'],
            'tir_id' => $data['tipo_resolucion'] ?? 2,
            'nir_id' => $data['nir_id'] ?? 0,
            'lic_giro' => '',
            'lic_modidirecc' => true,
            'lic_nota' => $data['observaciones'] ?? '', // Many use observations as note too


        ];
    }

    /**
     * Procesa giros para inserción/duplicación/transferencia (lista plana de nuevos giros).
     */
    protected function getGirosForNew(array $data): array
    {
        $giros = [];
        $selectedIds = $data['giros_seleccionar'] ?? [];
        $repeaterItems = $data['tabla_giros'] ?? [];

        $specificMap = [];
        foreach ($repeaterItems as $item) {
            if (isset($item['giro'])) {
                $specificMap[$item['giro']] = $item['giro_especifico'] ?? '';
            }
        }

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

        return $giros;
    }

    /**
     * Procesa giros para actualización (diff con existentes).
     */
    protected function getGirosForUpdate(Model $record, array $data): array
    {
        $giros = [];
        $selectedIds = $data['giros_seleccionar'] ?? [];
        $repeaterItems = $data['tabla_giros'] ?? [];

        $specificMap = [];
        foreach ($repeaterItems as $item) {
            if (isset($item['giro'])) {
                $specificMap[$item['giro']] = $item['giro_especifico'] ?? '';
            }
        }

        $giroService = app(GiroLicenciaService::class);
        $allGiros = $giroService->buscarGiros('');
        $idToName = $allGiros->pluck('gir_descripcion', 'gir_id')->toArray();

        // Obtener giros existentes en la BD
        $existingGiros = $giroService->obtenerLicenciaGiros($record->lic_id);
        $existingByGirId = $existingGiros->keyBy('gir_id');
        $processedGirIds = [];

        foreach ($selectedIds as $girId) {
            $name = $idToName[$girId] ?? '';
            $specific = $specificMap[$name] ?? '';

            if ($existingByGirId->has($girId)) {
                // Actualizar existente
                $existingRecord = $existingByGirId->get($girId);
                $giros[] = [
                    'lig_id' => $existingRecord->lig_id,
                    'gir_id' => $girId,
                    'giro_especifico' => $specific,
                    'estado' => 'M'
                ];
            } else {
                // Insertar nuevo
                $giros[] = [
                    'lig_id' => 0,
                    'gir_id' => $girId,
                    'giro_especifico' => $specific,
                    'estado' => 'I'
                ];
            }
            $processedGirIds[] = $girId;
        }

        // Identificar eliminados
        foreach ($existingByGirId as $girId => $existingGiroRecord) {
            if (!in_array($girId, $processedGirIds)) {
                $giros[] = [
                    'lig_id' => $existingGiroRecord->lig_id,
                    'gir_id' => $girId,
                    'giro_especifico' => $existingGiroRecord->lig_giroespecifico ?? '',
                    'estado' => 'E'
                ];
            }
        }

        return $giros;
    }
}
