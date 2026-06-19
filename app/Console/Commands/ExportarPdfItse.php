<?php

namespace App\Console\Commands;

use App\Models\CertificadoInspeccion;
use App\Services\Sil\CertificadoInspeccion\CertificadoPdfGenerator;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportarPdfItse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:exportar-pdf-itse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $this->info("Iniciando la regeneración masiva de PDFs...");

        // // 1. Instanciamos el generador UNA SOLA VEZ afuera del ciclo
        // $pdfGenerator = app(CertificadoPdfGenerator::class);

        // 2. Usamos chunk() o chunkById() para procesar de 100 en 100 y cuidar la memoria RAM
     
   
        CertificadoInspeccion::query()->chunkById(100, function ($recordsItse) {
            foreach ($recordsItse as $record) {
                $this->info("Procesando Itse N° {$record->cin_numero} - ID: {$record->cin_id}");

                try {
    //                 if ($record->cin_id == 7252) {
        $this->info("=== DEBUG REGISTRO {$record->cin_numero} ===");
        $this->line("tie_id (tipo): " . var_export($record->tie_id, true));
        $this->line("cin_consello: " . var_export($record->cin_consello, true));
        $this->info("===========================");
    // }
                    $filename = $this->generateAndSave($record);

                    if ($filename) {
                        $this->info("  [OK] Regenerado exitosamente: {$filename}");
                    } else {
                        // Aquí caerá si la validación interna (ej. tipo de edificación) devolvió null
                        $this->warn("  [OMITIDO] No cumplió los requisitos para generar PDF (Validación interna).");
                    }
                } catch (\Exception $e) {
                    // Capturamos cualquier error crítico (permisos de carpeta, base de datos, etc.) sin detener todo el comando
                    $this->error("  [ERROR] Falló el ID {$record->cin_id}: {$e->getMessage()}");
                }
            }
        });

        $this->info("¡Proceso de regeneración terminado!");
    }

 public function generateAndSave(CertificadoInspeccion $record): ?string
    {
        try {
            // Cargar la relación tipoEdificacion si no está cargada
            if (!$record->relationLoaded('tipoEdificacion')) {
                $record->load('tipoEdificacion');
            }

            $tipo = $record->tie_id;
            $consello = $record->cin_consello;

            // Validar tipo de edificación permitido
            if (!in_array($tipo, [5, 6, 7, 8], true) || $consello == true) {
                logger()->warning('Tipo de edificación no permitido para generar PDF', [
                    'cin_id' => $record->cin_id,
                    'tie_id' => $tipo,
                    'consello' => $consello,
                ]);
                return null;
            }

            // Renderizar la vista Blade a HTML
            $html = view('certificados.pdf', compact('record'))->render();

            // Configurar opciones de Dompdf
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            // Generar PDF
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Nombre del archivo con subdirectorio
            $filename = "originales/certificado_inspeccion_id_{$record->cin_id}.pdf";

            // Guardar en el disco certificados_externos
            $pdfContent = $dompdf->output();
            Storage::disk('certificados_externos')->put($filename, $pdfContent);

            logger()->info('PDF generado exitosamente', [
                'cin_id' => $record->cin_id,
                'filename' => $filename,
            ]);

            return $filename;
        } catch (\Throwable $e) {
            logger()->error('Error al generar PDF del certificado', [
                'cin_id' => $record->cin_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }
}
