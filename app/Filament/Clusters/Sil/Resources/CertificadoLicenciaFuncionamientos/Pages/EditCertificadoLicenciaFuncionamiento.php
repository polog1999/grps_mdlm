<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Services\Sil\Licencias\TipoEstablecimientoService;
use App\Services\Sil\Licencias\GiroLicenciaService;
use App\Services\Sil\Licencias\LicenciaService;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class EditCertificadoLicenciaFuncionamiento extends EditRecord
{
    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $service = app(LicenciaService::class);
        $row = $service->getById($this->record->lic_id);

        if (!$row) {
            return $data;
        }

        $mapaSimple = [
            'exp_num' => 'lic_expnum',
            'exp_fec' => 'lic_expfec',
            'exp_nomrec' => 'personasolicitante',
            'exp_nomrec_id' => 'per_idsolicitante',
            'exp_razsoc' => 'razonsocial',
            'exp_razsoc_id' => 'per_idrazonsocial',
            'numdoc' => 'per_ruc',
            'numtel' => 'per_telefono',
            'correo' => 'per_email',
            'domfis' => 'per_direccion',
            'direccion' => 'lic_direccion',
            'nir_id' => 'nir_id',
            'nir_descripcion' => 'nir_descripcion',
            'tipo_resolucion' => 'tir_id',
            'n_resolucion' => 'lic_resnum',
            'numero_licencia' => 'lic_numlic',
            'tipo_licencia' => 'tli_id',
            'compatibilidad' => 'lic_compatibilidad',
            'nro_compatibilidad' => 'lic_compatibilidadnumero',
            'hora_inicio' => 'lic_horainicio',
            'hora_fin' => 'lic_horafin',
            'observaciones' => 'lic_licobs',
            'centro_comercial' => 'cec_id',
            'tipo_local' => 'tlo_id',
            'local' => 'lcc_local',
            'observaciones_local' => 'lcc_observacion',
        ];

        foreach ($mapaSimple as $formField => $dbColumn) {
            $data[$formField] = $row->$dbColumn ?? null;
        }

        if (!empty($row->codigocatastral)) {
            $datosCatastro = $service->obtenerDatosGeneralesDeCatastroPorCodigoCatastral($row->codigocatastral);

            if (!empty($datosCatastro) && isset($datosCatastro[0])) {
                $catastro = $datosCatastro[0];
                $data['coduca'] = $catastro->coduca ?? null;
                $data['codpredio'] = $catastro->codpredio ?? null;
                $data['descurb'] = $catastro->descurb ?? null;
                $data['via_completa'] = $catastro->via_completa ?? null;
                $data['numvia'] = $catastro->numvia ?? null;
                $data['intdpto'] = $catastro->intdpto ?? null;
                $data['blockedif'] = $catastro->blockedif ?? null;
                $data['mz'] = $catastro->mz ?? null;
                $data['lote'] = $catastro->lote ?? null;
                $data['zonificacion'] = $catastro->zonificacion ?? null;
                $data['area_economica'] = $catastro->area_economica ?? null;
                $data['fiu_id'] = $catastro->fiu_id ?? null;
            }
        }

        $data['fecha_resolucion'] = $row->lic_fecharesolucion
            ? Carbon::createFromFormat('d/m/Y', $row->lic_fecharesolucion)->toDateString()
            : null;
        $data['fecha_emision'] = $row->lic_fechaemision
            ? Carbon::createFromFormat('d/m/Y', $row->lic_fechaemision)->toDateString()
            : null;
        $data['fecha_compatibilidad'] = $row->lic_compatibilidadfecha
            ? Carbon::parse(trim($row->lic_compatibilidadfecha))->toDateString()
            : null;

        $data['mype'] = ($row->lic_mype ?? false) ? '1' : '0';

        if (isset($row->tes_descripcion)) {
            $tesService = app(TipoEstablecimientoService::class);
            $tes = $tesService->getTipoEstablecimiento()->firstWhere('tes_descripcion', $row->tes_descripcion);
            $data['tipo_establecimientos'] = $tes ? $tes->tes_id : null;
        }

        // Cargar Giros asociados a la licencia
        $giroService = app(GiroLicenciaService::class);
        $girosLicencia = $giroService->obtenerGirosPorIdLicencia($this->record->lic_id);

        $girosIds = [];
        $tablaGiros = [];

        foreach ($girosLicencia as $giroLicencia) {
            // Agregar el ID del giro al array de seleccionados
            $girosIds[] = $giroLicencia->gir_id;

            // Agregar el giro al repeater con su nombre y específico
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

        // We need a service to map ID -> Name to use the map above
        $giroService = app(GiroLicenciaService::class);
        $allGiros = $giroService->buscarGiros('');
        $idToName = $allGiros->pluck('gir_descripcion', 'gir_id')->toArray();

        foreach ($selectedIds as $girId) {
            $name = $idToName[$girId] ?? '';
            $specific = $specificMap[$name] ?? '';

            $giros[] = [
                'gir_id' => $girId,
                'giro_especifico' => $specific,
                'lig_id' => 0,
                'estado' => 'M'
            ];
        }
        $service = app(LicenciaService::class);
        $datosCatastroActualizados = [];

        if (!empty($data['coduca'])) {
            $codigoCatastral = $data['coduca']; // o la lógica que uses para construir el código
            $datosCatastro = $service->obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codigoCatastral);

            if (!empty($datosCatastro) && isset($datosCatastro[0])) {
                $catastro = $datosCatastro[0];

                // Sobrescribir los datos del formulario con los datos actualizados del catastro
                $data['codpredio'] = $catastro->codpredio ?? $data['codpredio'];
                $data['descurb'] = $catastro->descurb ?? $data['descurb'];
                $data['zonificacion'] = $catastro->zonificacion ?? $data['zonificacion'];
                $data['area_economica'] = $catastro->area_economica ?? $data['area_economica'];
                $data['fiu_id'] = $catastro->fiu_id ?? ($data['fiu_id'] ?? 0);
            }
        }

        $params = [
            'lic_id' => $record->lic_id,
            'tli_id' => $data['tipo_licencia'],
            'tes_id' => $data['tipo_establecimientos'],
            'per_idsolicitante' => $data['exp_nomrec_id'] ?? null,
            'per_idrazonsocial' => $data['exp_razsoc_id'] ?? null,
            'lic_numlic' => $data['numero_licencia'],
            'lic_codigopredial' => $data['codpredio'] ?? '',
            'lic_expnum' => $data['exp_num'],
            'lic_direccion' => $data['direccion'],
            'lic_urbanizacion' => $data['descurb'] ?? '',
            'lic_area' => $data['area_economica'] ?? 0,
            'lic_mype' => $data['mype'] == '1',
            'lic_resnum' => $data['n_resolucion'],
            'lic_fecharesolucion' => $data['fecha_resolucion'],
            'lic_fechaemision' => $data['fecha_emision'],
            'lic_fechavencimiento' => null,
            'lic_licobs' => $data['observaciones'],
            'fiu_id' => $data['fiu_id'] ?? 0,
            'lca_urbanizacion' => $data['descurb'] ?? '',
            'lca_zonificacion' => $data['zonificacion'] ?? '',
            'cec_id' => $data['centro_comercial'] ?? 0,
            'tlo_id' => $data['tipo_local'] ?? 0,
            'lcc_observacion' => $data['observaciones_local'] ?? '',
            'lcc_local' => $data['local'] ?? '',
            'lic_horainicio' => $data['hora_inicio'],
            'lic_horafin' => $data['hora_fin'],
            'tir_id' => $data['tipo_resolucion'],
            'compatibilidad' => $data['compatibilidad'],
            'nir_id' => $data['nir_id'],
            'lic_giro' => '',
            'lca_descripcion' => '',
            'lca_origen' => '',
            'lic_modidirecc' => false,
            'lic_nota' => '',
            'rsgparrafo1' => '',
            'rsgparrafo2' => '',
            'giros' => $giros,
        ];

        $service->update($params);

        return $record;
    }
}
