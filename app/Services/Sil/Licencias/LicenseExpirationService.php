<?php

namespace App\Services\Sil\Licencias;

use App\Models\CertificadoLicenciaFuncionamiento;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Servicio para gestionar la expiración de licencias temporales
 */
class LicenseExpirationService
{
    const TEMPORARY_LICENSE_TYPE = 2;
    const EXPIRED_STATUS = 6;

    /**
     * Obtiene licencias temporales vencidas que necesitan actualización
     * 
     * @return Collection
     */
    public function getExpiredTemporaryLicenses(): Collection
    {
        try {
            return CertificadoLicenciaFuncionamiento::query()
                ->where('tli_id', self::TEMPORARY_LICENSE_TYPE)
                ->where('lic_fechavencimiento', '<', Carbon::today())
                ->where('esl_id', '!=', self::EXPIRED_STATUS)
                ->where('lic_filaeliminada', false)
                ->with(['tipoLicencia', 'tipoEstadoLicencia'])
                ->get();
        } catch (\Exception $e) {
            Log::error('Error al obtener licencias vencidas: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Actualiza el estado de una licencia a "De Baja"
     * 
     * @param int $licenseId
     * @return bool
     */
    public function expireLicense(int $licenseId): bool
    {
        try {
            $license = CertificadoLicenciaFuncionamiento::find($licenseId);

            if (!$license) {
                Log::warning("Licencia no encontrada: {$licenseId}");
                return false;
            }

            // Verificar que sea temporal y esté vencida
            if ($license->tli_id != self::TEMPORARY_LICENSE_TYPE) {
                Log::warning("Licencia {$licenseId} no es temporal");
                return false;
            }

            if (!$license->lic_fechavencimiento || $license->lic_fechavencimiento >= Carbon::today()) {
                Log::warning("Licencia {$licenseId} no está vencida");
                return false;
            }

            $oldStatus = $license->esl_id;
            $license->esl_id = self::EXPIRED_STATUS;
            $license->save();

            // SINCRONIZACIÓN DE ANUNCIOS:
            // Si la licencia pasó a estado 6 (EXPIRED_STATUS), debemos dar de baja los anuncios relacionados.
            $anunciosUpdatedCount = \App\Models\Anuncios::where('id_licencia', $licenseId)
                ->where('estado_anuncio', '!=', \App\Filament\Clusters\Sil\Resources\Anuncios\Enums\EstadoAnuncio::BAJA->value)
                ->update(['estado_anuncio' => \App\Filament\Clusters\Sil\Resources\Anuncios\Enums\EstadoAnuncio::BAJA->value]);

            Log::info("Licencia expirada automáticamente", [
                'lic_id' => $licenseId,
                'lic_numlic' => $license->lic_numlic,
                'old_status' => $oldStatus,
                'new_status' => self::EXPIRED_STATUS,
                'fecha_vencimiento' => $license->lic_fechavencimiento,
                'anuncios_baja_sync' => $anunciosUpdatedCount
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Error al expirar licencia {$licenseId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza múltiples licencias a estado expirado
     * 
     * @param Collection $licenseIds
     * @return int Número de licencias actualizadas
     */
    public function expireMultipleLicenses(Collection $licenseIds): int
    {
        $updated = 0;

        foreach ($licenseIds as $licenseId) {
            if ($this->expireLicense($licenseId)) {
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * Obtiene licencias que vencerán pronto
     * 
     * @param int $days Número de días hacia adelante
     * @return Collection
     */
    public function getLicensesExpiringSoon(int $days = 30): Collection
    {
        try {
            return CertificadoLicenciaFuncionamiento::query()
                ->where('tli_id', self::TEMPORARY_LICENSE_TYPE)
                ->whereBetween('lic_fechavencimiento', [
                    Carbon::today(),
                    Carbon::today()->addDays($days)
                ])
                ->where('esl_id', '!=', self::EXPIRED_STATUS)
                ->where('lic_filaeliminada', false)
                ->with(['tipoLicencia', 'tipoEstadoLicencia'])
                ->orderBy('lic_fechavencimiento', 'asc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error al obtener licencias por vencer: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtiene estadísticas de licencias temporales
     * 
     * @return array
     */
    public function getExpirationStats(): array
    {
        try {
            $total = CertificadoLicenciaFuncionamiento::where('tli_id', self::TEMPORARY_LICENSE_TYPE)
                ->where('lic_filaeliminada', false)
                ->count();

            $expired = CertificadoLicenciaFuncionamiento::where('tli_id', self::TEMPORARY_LICENSE_TYPE)
                ->where('esl_id', self::EXPIRED_STATUS)
                ->where('lic_filaeliminada', false)
                ->count();

            $expiredToday = $this->getExpiredTemporaryLicenses()->count();

            $expiringSoon = $this->getLicensesExpiringSoon(30)->count();

            return [
                'total_temporary' => $total,
                'total_expired' => $expired,
                'expired_today' => $expiredToday,
                'expiring_next_30_days' => $expiringSoon,
            ];
        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            return [
                'total_temporary' => 0,
                'total_expired' => 0,
                'expired_today' => 0,
                'expiring_next_30_days' => 0,
            ];
        }
    }
}
