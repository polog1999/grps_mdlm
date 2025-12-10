<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Sil\Licencias\LicenciaService;

class QrLicenciaController extends Controller
{
    public function mostrarQr($idLicencia)
    {
        $service = app(LicenciaService::class);
        $licencia = $service->getById($idLicencia);

        if (!$licencia) {
            abort(404, 'Licencia no encontrada');
        }

        // Solo pasar los datos de la licencia, sin generar QR
        return view('qr-licencia', [
            'licencia' => $licencia
        ]);
    }
}
