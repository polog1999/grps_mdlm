<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Pages;

use App\Filament\Clusters\Sil\Resources\Anuncios\AnunciosResource;
use App\Services\Sil\Licencias\LicenciaService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAnuncios extends EditRecord
{
    protected static string $resource = AnunciosResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Cargar datos de las relaciones para los campos auxiliares del Wizard
        $expediente = $this->record->expediente;

        if ($expediente) {
            $data['n_expediente'] = $expediente->n_expediente;
            $data['folios'] = $expediente->folios;
            $data['zonificacion_id'] = $expediente->zonificacion_id;

            // Mapeo de Solicitante
            $data['snapshot_solicitante_dni'] = $expediente->snapshot_solicitante_dni;
            $data['snapshot_solicitante_nombre_completo'] = $expediente->snapshot_solicitante_nombre_completo;
            $data['snapshot_solicitante_telefono'] = $expediente->snapshot_solicitante_telefono;

            // Direcciones (Usando los nombres descriptivos del formulario)
            $data['form_domicilio_fiscal'] = $expediente->snapshot_legal_direccion;
            $data['form_direccion_predio'] = $expediente->snapshot_solicitante_direccion;

            // Persona Legal (Representante)
            $data['snapshot_persona_legal_dni'] = $expediente->snapshot_legal_dni_ruc;
            $data['snapshot_persona_legal_nombre_completo'] = $expediente->snapshot_legal_nombre;
            $data['snapshot_persona_legal_telefono'] = $expediente->snapshot_legal_telefono;
            $data['snapshot_persona_legal_distrito'] = $expediente->snapshot_legal_distrito;

            // Información de Pago
            $recibo = $expediente->reciboPago;
            if ($recibo) {
                $data['n_pago'] = $recibo->n_recibo_pago;
                $data['monto'] = $recibo->monto;
            }
        }

        // Determinar si tiene licencia y recuperar datos del servicio
        $data['tiene_licencia'] = $this->record->id_licencia ? 'si' : 'no';

        if ($this->record->id_licencia) {
            try {
                $service = app(LicenciaService::class);
                $datosLicencia = $service->obtenerDatosPorIdLicenciaDirecta($this->record->id_licencia);

                if ($datosLicencia) {
                    $giroFinal = !empty($datosLicencia->GIRO_ESPECIFICOS)
                        ? $datosLicencia->GIRO_ESPECIFICOS
                        : ($datosLicencia->GIRO ?? 'GIRO NO DEFINIDO');

                    $data['giro_especifico_snapshot'] = $this->record->giro_especifico_snapshot ?? str($giroFinal)->upper();
                    $data['snapshot_lic_tipo'] = str($datosLicencia->TIPO_LICENCIA)->upper()->trim()->toString();
                    // La dirección ya se recuperó del expediente (predio), pero si se prefiere del servicio:
                    // $data['form_direccion_predio'] = str($datosLicencia->LIC_DIRECCION)->upper();
                }
            } catch (\Exception $e) {
                // Silently fail if service is down, the user can still edit
            }
        }

        return $data;
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        $expediente = $this->record->expediente;

        if ($expediente) {
            // 1. Actualizar datos del Expediente
            $expediente->update([
                'n_expediente' => $data['n_expediente'] ?? $expediente->n_expediente,
                'folios' => $data['folios'] ?? $expediente->folios,
                'zonificacion_id' => $data['zonificacion_id'] ?? $expediente->zonificacion_id,
                'snapshot_solicitante_dni' => $data['snapshot_solicitante_dni'] ?? $expediente->snapshot_solicitante_dni,
                'snapshot_solicitante_nombre_completo' => $data['snapshot_solicitante_nombre_completo'] ?? $expediente->snapshot_solicitante_nombre_completo,
                'snapshot_solicitante_telefono' => $data['snapshot_solicitante_telefono'] ?? $expediente->snapshot_solicitante_telefono,
                'snapshot_solicitante_direccion' => $data['form_direccion_predio'] ?? $expediente->snapshot_solicitante_direccion, // Predio
                'snapshot_legal_direccion' => $data['form_domicilio_fiscal'] ?? $expediente->snapshot_legal_direccion, // Fiscal
                'snapshot_legal_nombre' => $data['snapshot_persona_legal_nombre_completo'] ?? $expediente->snapshot_legal_nombre,
                'snapshot_legal_dni_ruc' => $data['snapshot_persona_legal_dni'] ?? $expediente->snapshot_legal_dni_ruc,
                'snapshot_legal_telefono' => $data['snapshot_persona_legal_telefono'] ?? $expediente->snapshot_legal_telefono,
                'snapshot_legal_distrito' => $data['snapshot_persona_legal_distrito'] ?? $expediente->snapshot_legal_distrito,
            ]);

            // 2. Actualizar datos del Recibo de Pago
            if ($expediente->reciboPago) {
                $expediente->reciboPago->update([
                    'n_recibo_pago' => $data['n_pago'] ?? $expediente->reciboPago->n_recibo_pago,
                    'monto' => $data['monto'] ?? $expediente->reciboPago->monto,
                ]);
            }
        }

        // 3. Asignar auditoria
        $data['updated_by_user_id'] = auth()->id();

        // 4. Limpiar campos auxiliares para que no fallen al guardar el Anuncio (la tabla no tiene estas columnas)
        $auxiliaryFields = [
            'n_expediente_search',
            'tiene_licencia',
            'n_pago',
            'monto',
            'n_expediente',
            'folios',
            'snapshot_solicitante_dni',
            'snapshot_solicitante_nombre_completo',
            'form_domicilio_fiscal',
            'snapshot_solicitante_telefono',
            'snapshot_persona_legal_dni',
            'snapshot_persona_legal_nombre_completo',
            'snapshot_persona_legal_telefono',
            'snapshot_persona_legal_distrito',
            'snapshot_lic_tipo',
            'form_direccion_predio',
            'zonificacion_id',
        ];

        foreach ($auxiliaryFields as $field) {
            unset($data[$field]);
        }

        return $data;
    }

}

