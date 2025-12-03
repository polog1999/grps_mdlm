<?php

namespace App\Services\Sil\Licencias\Handlers;

use App\Models\CertificadoLicenciaFuncionamiento;
use Illuminate\Database\ConnectionInterface;
use App\Services\Sil\Licencias\Concerns\PostgresHelpers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LicenciaUpdater
{
    public function execute(array $data)
    {
        // El modelo ya tiene definida la conexión 'pgsql_licencias'
        // No necesitamos especificarla aquí
        return DB::transaction(function () use ($data) {

            // 1. Buscamos la Licencia (usa automáticamente pgsql_licencias)
            $licencia = CertificadoLicenciaFuncionamiento::findOrFail($data['lic_id']);

            // 2. Helper local para limpiar fechas
            $parseDate = function ($date) {
                if (empty($date) || trim($date) === '/  /' || trim($date) === '') {
                    return null;
                }
                try {
                    return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
                } catch (\Exception $e) {
                    return null;
                }
            };

            // 3. Actualizamos los campos
            $licencia->update([
                // IDs y Relaciones
                'tli_id' => $data['tli_id'],
                'tes_id' => $data['tes_id'],
                'per_idsolicitante' => $data['per_idsolicitante'],
                'per_idrazonsocial' => $data['per_idrazonsocial'],
                'usa_id' => auth()->id() ?? $data['usa_id'],

                // Datos Generales
                'lic_numlic' => $data['lic_numlic'],
                'lic_codigopredial' => $data['lic_codigopredial'],
                'lic_expnum' => $data['lic_expnum'],
                'lic_direccion' => $data['lic_direccion'],
                'lic_urbanizacion' => $data['lic_urbanizacion'],
                'lic_area' => $data['lic_area'],
                'lic_mype' => (bool) $data['lic_mype'],
                'lic_resnum' => $data['lic_resnum'],

                // Fechas
                'lic_fecharesolucion' => $parseDate($data['lic_fecharesolucion']),
                'lic_fechaemision' => $parseDate($data['lic_fechaemision']),
                'lic_fechavencimiento' => $parseDate($data['lic_fechavencimiento']),

                // Observaciones y Textos
                'lic_licobs' => $data['lic_licobs'],
                'lic_giro' => $data['lic_giro'],
                'lic_nota' => mb_strtoupper($data['lic_nota'] ?? '', 'UTF-8'),

                // Campos de control interno
                'lic_filaoriginal' => false,

                // Horarios y Compatibilidad
                'lic_horainicio' => $data['lic_horainicio'],
                'lic_horafin' => $data['lic_horafin'],
                'tir_id' => $data['tir_id'],
                'lic_compatibilidad' => $data['compatibilidad'],
                'lic_rsgparrafo1' => $data['rsgparrafo1'] ?? '',
                'lic_rsgparrafo2' => $data['rsgparrafo2'] ?? '',
                'nir_id' => $data['nir_id'] ?? 0
            ]);

            // Retornamos el modelo actualizado
            return $licencia;
        });
    }
}
