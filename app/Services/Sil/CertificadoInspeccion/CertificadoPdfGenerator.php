<?php

namespace App\Services\Sil\CertificadoInspeccion;

use App\Models\CertificadoInspeccion;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;

class CertificadoPdfGenerator
{
    /**
     * Genera un PDF del certificado y lo guarda en el disco certificados_externos.
     *
     * @param CertificadoInspeccion $record
     * @return string|null Ruta del archivo guardado o null si falla
     */
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
