<?php

namespace App\Http\Controllers;

use App\Services\Sil\Licencias\LicenciaService;
use App\Services\Sil\Licencias\QrCodeService;
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
     * Muestra el PDF del certificado de licencia
     *
     * @param int $licenciaId
     * @return \Illuminate\View\View
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

        return view('certificado-licencia', [
            'licencia' => $datosLicencia,
            'qrImage' => $qrDataUri,
        ]);
    }
}
