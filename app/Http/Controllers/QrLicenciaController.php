<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Sil\Licencias\LicenciaService;
use App\Services\Sil\Licencias\QrAccessService;
use App\Services\Sil\Licencias\QrCodeService;

class QrLicenciaController extends Controller
{
    protected $licenciaService;
    protected $qrAccessService;
    protected $qrCodeService;

    public function __construct(
        LicenciaService $licenciaService,
        QrAccessService $qrAccessService,
        QrCodeService $qrCodeService
    ) {
        $this->licenciaService = $licenciaService;
        $this->qrAccessService = $qrAccessService;
        $this->qrCodeService = $qrCodeService;
    }

    public function mostrarQr(Request $request, $idLicencia)
    {
        // Obtener datos de la licencia
        $licencia = $this->licenciaService->getById($idLicencia);

        if (!$licencia) {
            abort(404, 'Licencia no encontrada');
        }

        // Obtener el cqr_id asociado a esta licencia
        $cqrId = $this->qrCodeService->obtenerCqrIdPorLicencia($idLicencia);

        // Registrar el acceso al QR (auditoría) usando el cqr_id
        // Esto no bloqueará el flujo principal si falla
        if ($cqrId) {
            $this->qrAccessService->registrarAcceso($request, $cqrId);
        }

        return view('qr-licencia', [
            'licencia' => $licencia
        ]);
    }
}
