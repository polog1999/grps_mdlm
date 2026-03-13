<?php

namespace App\Http\Controllers;

use App\Models\Anuncios;
use App\Services\Sil\Anuncios\CertificadoAnuncioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // <--- AGREGA ESTA LÍNEA

class AnuncioPdfController extends Controller
{
    public function mostrar($anuncioId)
    {
        try {
            // 1. Buscamos el anuncio
            $anuncio = Anuncios::findOrFail($anuncioId);

            // 2. Llamamos a tu servicio para generar el PDF
            // Usamos app() para resolver el servicio automáticamente
            $pdfPath = app(CertificadoAnuncioService::class)->generarCertificado($anuncio);

            // 3. Validamos que el archivo realmente se haya creado
            if (!file_exists($pdfPath)) {
                abort(404, 'No se pudo encontrar el archivo PDF generado.');
            }

            // 4. Lo devolvemos al navegador en modo "INLINE" (ver en pestaña)
            return response()->file($pdfPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Certificado_Anuncio_' . ($anuncio->n_anuncio ?? $anuncio->id) . '.pdf"'
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error("Error mostrando PDF en nueva pestaña: " . $e->getMessage());
            abort(500, "Error técnico al generar el documento: " . $e->getMessage());
        }
    }
}
