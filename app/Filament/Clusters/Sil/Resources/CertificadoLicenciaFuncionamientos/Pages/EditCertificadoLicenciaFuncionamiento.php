<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Services\Sil\Licencias\TipoEstablecimientoService;
use App\Services\Sil\Licencias\LicenciaUpdateService;

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
        $service = app(LicenciaUpdateService::class);
        $rows = $service->obtenerPorIdLicencia($this->record->lic_id);
        
        if ($rows->isEmpty()) {
            return $data;
        }

        $row = $rows->first();

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
        $data['mype'] = (string)$row->lic_mype; // Cast to string for Radio component if needed
        $data['compatibilidad'] = $row->lic_compatibilidad;
        $data['nro_compatibilidad'] = $row->lic_compatibilidadnumero;
        $data['fecha_compatibilidad'] = $row->lic_compatibilidadfecha;
        $data['hora_inicio'] = $row->lic_horainicio;
        $data['hora_fin'] = $row->lic_horafin;
        $data['observaciones'] = $row->lic_licobs;
        
        // Tipo Establecimiento Lookup
        if (isset($row->tes_descripcion)) {
            $tesService = app(TipoEstablecimientoService::class);
            $tes = $tesService->getTipoEstablecimiento()->firstWhere('tes_descripcion', $row->tes_descripcion);
            $data['tipo_establecimientos'] = $tes ? $tes->tes_id : null;
        }

        // Giros - Assuming lic_giro contains text description. 
        // If we want to populate the repeater/select, we'd need to match it to IDs or just show it.
        // For now, we'll leave it as is or if there's a specific field for text giro.
        // The form has 'giros_seleccionar' (ids) and 'tabla_giros' (repeater).
        // If lic_giro is a simple string, we might not be able to fully populate the complex selector without more logic.
        
        return $data;
    }
}
