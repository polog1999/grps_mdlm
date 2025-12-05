<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Services\Sil\Licencias\TipoEstablecimientoService;
use App\Services\Sil\Licencias\GiroLicenciaService;
use App\Services\Sil\Licencias\LicenciaService;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
=======
use Carbon\Carbon;
>>>>>>> feature/licencias
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

<<<<<<< HEAD
        // Expediente
        $data['exp_num'] = $row->lic_expnum;
        $data['exp_fec'] = $row->lic_expfec;
        $data['exp_nomrec'] = $row->personasolicitante;
        $data['exp_razsoc'] = $row->razonsocial;
        $data['exp_razsoc_id'] = $row->per_idrazonsocial;
        // Assuming per_idsolicitante might be needed if available, but not in list.
        // $data['exp_nomrec_id'] = $row->per_idsolicitante; 

        $data['numdoc'] = $row->per_ruc;
        $data['numtel'] = $row->per_telefono;
        $data['correo'] = $row->per_email;
        $data['domfis'] = $row->per_direccion;

        // Catastro
        $data['coduca'] = $row->codigocatastral;
        $data['codpredio'] = $row->lic_codigopredial;
        $data['descurb'] = $row->urbanizacion;
        $data['via_completa'] = $row->via;
        $data['numvia'] = $row->numero;
        $data['intdpto'] = $row->departamento;
        $data['blockedif'] = $row->blockedif;
        $data['mz'] = $row->manzana;
        $data['lote'] = $row->lote;
        $data['zonificacion'] = $row->lca_zonificacion;
        $data['area_economica'] = $row->lic_area;

        // Reconstruct address for display if needed, or rely on individual fields
        $data['direccion'] = $row->lic_direccion;

        // Licencias
        $data['nir_id'] = $row->nir_id;
        $data['nir_descripcion'] = $row->nir_descripcion;
        $data['n_resolucion'] = $row->lic_resnum;
        $data['fecha_resolucion'] = $row->lic_fecharesolucion;
        $data['numero_licencia'] = $row->lic_numlic;
        $data['tipo_licencia'] = $row->tli_id;
        $data['fecha_emision'] = $row->lic_fechaemision;
        $data['mype'] = (string) $row->lic_mype; // Cast to string for Radio component if needed
        $data['compatibilidad'] = $row->lic_compatibilidad;
        $data['nro_compatibilidad'] = $row->lic_compatibilidadnumero;
        $data['fecha_compatibilidad'] = $row->lic_compatibilidadfecha;
        $data['hora_inicio'] = $row->lic_horainicio;
        $data['hora_fin'] = $row->lic_horafin;
        $data['observaciones'] = $row->lic_licobs;

        // Tipo Establecimiento Lookup
=======
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

>>>>>>> feature/licencias
        if (isset($row->tes_descripcion)) {
            $tesService = app(TipoEstablecimientoService::class);
            $tes = $tesService->getTipoEstablecimiento()->firstWhere('tes_descripcion', $row->tes_descripcion);
            $data['tipo_establecimientos'] = $tes ? $tes->tes_id : null;
        }

<<<<<<< HEAD
        // Giros - Assuming lic_giro contains text description. 
        // If we want to populate the repeater/select, we'd need to match it to IDs or just show it.
        // For now, we'll leave it as is or if there's a specific field for text giro.
        // The form has 'giros_seleccionar' (ids) and 'tabla_giros' (repeater).
        // If lic_giro is a simple string, we might not be able to fully populate the complex selector without more logic.

        return $data;
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
=======
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
>>>>>>> feature/licencias
    {
        $giros = [];
<<<<<<< HEAD
        // We need to map the selected IDs (giros_seleccionar) to the repeater data (tabla_giros)
        // Assumption: The order in tabla_giros matches the order of selection or we can't easily map without ID in repeater.
        // However, the repeater has 'giro' name. We can try to look up ID by name if needed, 
        // OR we can assume the 'giros_seleccionar' contains the IDs we need.
        // But 'tabla_giros' contains the 'giro_especifico'.

        // Let's try to use the 'giros_seleccionar' array as the source of truth for IDs.
        // And try to find the matching specific text from 'tabla_giros'.
        // This is imperfect if there are duplicate giro names, but best effort.
=======
>>>>>>> feature/licencias

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
<<<<<<< HEAD
        // This might be expensive if we fetch all, but let's fetch selected ones if possible or just all.
        // For now, let's assume we can't easily get the name for the ID without a query.
        // A simpler approach: Just iterate the repeater items. 
        // If the repeater items don't have IDs, we are stuck.
        // WAIT: The repeater items are populated in the form using:
        // $filas[] = ['giro' => $mapaGiros[$giroId], 'giro_especifico' => ''];
        // So 'giro' holds the NAME.

        // So we can iterate selected IDs, get their Name, look up in $specificMap.
        // But we don't have the Name for the ID here easily without fetching.

        // Let's fetch all giros to map ID to Name.
=======
>>>>>>> feature/licencias
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
<<<<<<< HEAD
            'lic_giro' => '',
=======
>>>>>>> feature/licencias
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
