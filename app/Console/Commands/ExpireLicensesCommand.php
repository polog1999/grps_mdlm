<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Sil\Licencias\LicenseExpirationService;

class ExpireLicensesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'licenses:expire 
                            {--dry-run : Ejecutar sin hacer cambios en la base de datos}
                            {--report : Mostrar reporte detallado de licencias}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de licencias temporales vencidas a "De Baja" (esl_id=6)';

    /**
     * Execute the console command.
     */
    public function handle(LicenseExpirationService $service)
    {
        $this->info('🔍 Buscando licencias temporales vencidas...');
        $this->newLine();

        $expiredLicenses = $service->getExpiredTemporaryLicenses();

        if ($expiredLicenses->isEmpty()) {
            $this->info('✓ No se encontraron licencias vencidas.');
            return Command::SUCCESS;
        }

        $count = $expiredLicenses->count();
        $this->warn("⚠ Encontradas {$count} licencia(s) vencida(s)");
        $this->newLine();

        if ($this->option('report')) {
            $this->displayReport($expiredLicenses);
        }

        if ($this->option('dry-run')) {
            $this->warn('🔸 Modo dry-run: No se realizaron cambios en la base de datos.');
            $this->info('   Para ejecutar los cambios, ejecute el comando sin --dry-run');
            return Command::SUCCESS;
        }

        if (!$this->confirm('¿Desea actualizar estas licencias a estado "De Baja"?', true)) {
            $this->info('Operación cancelada.');
            return Command::SUCCESS;
        }

        $this->info('⏳ Actualizando licencias...');
        $this->newLine();

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        $updated = 0;
        foreach ($expiredLicenses->pluck('lic_id') as $licenseId) {
            if ($service->expireLicense($licenseId)) {
                $updated++;
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✓ {$updated} de {$count} licencia(s) actualizada(s) a estado 'De Baja'");

        if ($updated < $count) {
            $failed = $count - $updated;
            $this->warn("⚠ {$failed} licencia(s) no pudieron ser actualizadas. Revise los logs para más detalles.");
        }

        return Command::SUCCESS;
    }

    /**
     * Muestra un reporte detallado de las licencias
     */
    private function displayReport($licenses)
    {
        $this->table(
            ['ID', 'Número Licencia', 'Fecha Vencimiento', 'Estado Actual', 'Días Vencidos'],
            $licenses->map(function ($license) {
                $daysExpired = now()->diffInDays($license->lic_fechavencimiento, false);
                return [
                    $license->lic_id,
                    $license->lic_numlic ?? 'N/A',
                    $license->lic_fechavencimiento ? $license->lic_fechavencimiento->format('d/m/Y') : 'N/A',
                    $license->tipoEstadoLicencia->esl_descripcion ?? 'N/A',
                    abs($daysExpired) . ' días'
                ];
            })
        );

        $this->newLine();
    }
}
