<?php

namespace App\Http\Controllers;

use App\Services\Sil\Licencias\LicenciaService;
use App\Services\Sil\Licencias\QrCodeService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class CertificadoLicenciaPdfController extends Controller
{
    protected $licenciaService;
    protected $qrCodeService;

    public function __construct(LicenciaService $licenciaService, QrCodeService $qrCodeService)
    {
        $this->licenciaService = $licenciaService;
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Genera y muestra el PDF del certificado de licencia
     *
     * @param int $licenciaId
     * @return \Illuminate\Http\Response
     */
    public function mostrar(int $licenciaId)
    {
        // Obtener datos de la licencia usando el servicio
        $datosLicencia = $this->licenciaService->obtenerDatosPorIdLicenciaDirecta($licenciaId);

        if (!$datosLicencia) {
            abort(404, 'Licencia no encontrada');
        }

        // Intentar obtener el QR code
        $qrDataUri = null;
        try {
            $qrDataUri = $this->qrCodeService->generarQrDataUri($licenciaId);
        } catch (\Exception $e) {
            \Log::warning('No se pudo generar QR para licencia', [
                'licencia_id' => $licenciaId,
                'error' => $e->getMessage()
            ]);
        }

        // Obtener giros de la licencia con sus descripciones
        $giros = \App\Models\LicenciaGiro::where('lic_id', $licenciaId)
            ->with('giro')
            ->get()
            ->map(function ($licenciaGiro) {
                return $licenciaGiro->giro->gir_descripcion ?? $licenciaGiro->lig_giroespecifico;
            })
            ->filter()
            ->toArray();

        // Renderizar la vista Blade a HTML
        $html = view('certificado-licencia', [
            'licencia' => $datosLicencia,
            'qrImage' => $qrDataUri,
            'giros' => $giros,
        ])->render();

        // Configurar opciones de Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        $options->set('isHtml5ParserEnabled', true);

        // Generar PDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "licencia_funcionamiento_{$licenciaId}.pdf";

        // Mostrar en el navegador
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Descarga el PDF del certificado de licencia
     *
     * @param int $licenciaId
     * @return \Illuminate\Http\Response
     */
    public function descargar(int $licenciaId)
    {
        // Obtener datos de la licencia usando el servicio
        $datosLicencia = $this->licenciaService->obtenerDatosPorIdLicenciaDirecta($licenciaId);

        if (!$datosLicencia) {
            abort(404, 'Licencia no encontrada');
        }

        // Intentar obtener el QR code
        $qrDataUri = null;
        try {
            $qrDataUri = $this->qrCodeService->generarQrDataUri($licenciaId);
        } catch (\Exception $e) {
            \Log::warning('No se pudo generar QR para licencia', [
                'licencia_id' => $licenciaId,
                'error' => $e->getMessage()
            ]);
        }

        // Obtener giros de la licencia con sus descripciones
        $giros = \App\Models\LicenciaGiro::where('lic_id', $licenciaId)
            ->with('giro')
            ->get()
            ->map(function ($licenciaGiro) {
                return $licenciaGiro->giro->gir_descripcion ?? $licenciaGiro->lig_giroespecifico;
            })
            ->filter()
            ->toArray();

        // Renderizar la vista Blade a HTML
        $html = view('certificado-licencia', [
            'licencia' => $datosLicencia,
            'qrImage' => $qrDataUri,
            'giros' => $giros,
        ])->render();

        // Configurar opciones de Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        $options->set('isHtml5ParserEnabled', true);

        // Generar PDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "licencia_funcionamiento_{$licenciaId}.pdf";

        // Forzar descarga
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
