<?php

namespace App\Http\Controllers;

use App\Services\CertificadoInspeccionService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use App\Models\CertificadoInspeccion;

class CertificadoInspeccionController extends Controller
{
    protected $service;

    public function __construct(CertificadoInspeccionService $service)
    {
        $this->service = $service;
    }

    public function buscarUbicacion(Request $request)
    {
        $q = (string) $request->query('q', '');

        if (trim($q) === '') {
            return response()->json([]);
        }

        $items = $this->service->buscarUbicacion($q);
        $options = [];
        foreach ($items as $key => $value) {
            $options[(string) $key] = (string) $value;
        }

        return response()->json($options);
    }

    public function exportarPdf($certificadoId)
    {
    $record = CertificadoInspeccion::with('tipoEdificacion')->findOrFail($certificadoId);
        $record = CertificadoInspeccion::with('tipoEdificacion')->findOrFail($certificadoId);

        $tipo = $record->tie_id;
        if (! in_array($tipo, [5, 6, 7, 8], true)) {
            $base = config('certificado.redirect_url'); 
            if ($base) {
                $url = rtrim($base, '?&') . (str_contains($base, '?') ? '' : '?')  . urlencode($certificadoId);
                return redirect()->away($url);
            }
            return redirect()->back()->with('error', 'Tipo de edificación no permitido para generar este certificado.');
        }

        // Renderizar la vista Blade a HTML
        $html = view('certificados.pdf', compact('record'))->render();

        // Configurar opciones de Dompdf
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        // Generar PDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "certificado_inspeccion_{$certificadoId}.pdf";

        // Mostrar en el navegador en vez de forzar descarga
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
        ]);
    }



}
