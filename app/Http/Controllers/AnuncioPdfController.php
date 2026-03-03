<?php

namespace App\Http\Controllers;

use App\Models\Anuncios;
use App\Services\Sil\Anuncios\CertificadoAnuncioService;
use Illuminate\Http\Request;
use NcJoes\OfficeConverter\OfficeConverter;
use Illuminate\Support\Facades\Log;

class AnuncioPdfController extends Controller
{
    protected $certificadoService;

    public function __construct(CertificadoAnuncioService $certificadoService)
    {
        $this->certificadoService = $certificadoService;
    }

    /**
     * @param string $anuncioId
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function mostrar($anuncioId)
    {
        // 1. Validar existencia del Anuncio
        $anuncio = Anuncios::findOrFail($anuncioId);

        // 2. Aplicar el parche de "HOME" por si acaso la librería lo pide al inicializar el servicio
        $tempProfileDir = storage_path('app/temp/libreoffice_profile');
        if (!is_dir($tempProfileDir)) {
            mkdir($tempProfileDir, 0755, true);
        }
        putenv("HOME={$tempProfileDir}");
        $_SERVER['HOME'] = $tempProfileDir;

        try {
            // El servicio YA genera el PDF y devuelve la ruta del .pdf
            $pdfPath = $this->certificadoService->generarCertificado($anuncio);

            if (!file_exists($pdfPath)) {
                throw new \Exception("El archivo PDF no se encuentra en la ruta: $pdfPath");
            }

            // 3. Devolver el PDF al navegador
            return response()->file($pdfPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Certificado_' . ($anuncio->n_anuncio ?? $anuncioId) . '.pdf"'
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error en AnuncioPdfController', [
                'anuncio_id' => $anuncioId,
                'error' => $e->getMessage()
            ]);

            return response("Error al generar el certificado: " . $e->getMessage(), 500);
        }
    }
}
