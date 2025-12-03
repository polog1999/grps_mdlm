<?php

namespace App\Http\Controllers;

use App\Services\Sil\CertificadoInspeccion\CertificadoInspeccionService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use App\Models\CertificadoInspeccion;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CertificadosExport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Controlador encargado de operaciones relacionadas con Certificados de Inspección.
 *
 * Proporciona endpoints para búsqueda de ubicaciones (autocompletado),
 * generación de PDF del certificado y marcación lógica de eliminación.
 */
class CertificadoInspeccionController extends Controller
{
    /**
     * Servicio que encapsula la lógica de dominio para certificados.
     *
     * @var CertificadoInspeccionService
     */
    protected $service;

    /**
     * Constructor.
     *
     * @param CertificadoInspeccionService $service Servicio inyectado para operaciones sobre certificados.
     */
    public function __construct(CertificadoInspeccionService $service)
    {
        $this->service = $service;
    }

    /**
     * Endpoint para autocompletar ubicaciones.
     *
     * Recibe query string `q` y delega al servicio para obtener coincidencias.
     * Devuelve un JSON con un array asociativo [key => label] donde ambas claves
     * se convierten a string para evitar problemas de tipado en el frontend.
     *
     * @param Request $request Petición HTTP con parámetro `q`.
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Genera y muestra el PDF de un certificado.
     *
     * Valida el tipo de edificación; si no está permitido puede redirigir a
     * una URL externa configurada en `certificado.redirect_url`. Si el tipo es
     * permitido, renderiza la vista Blade `certificados.pdf` y la convierte a PDF
     * usando Dompdf. Devuelve la respuesta con Content-Type `application/pdf`.
     *
     * @param int|string $certificadoId Identificador del certificado.
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function exportarPdf($certificadoId)
    {
        $record = CertificadoInspeccion::with('tipoEdificacion')->findOrFail($certificadoId);

        $tipo = $record->tie_id;
        $consello = $record->cin_consello;
        if (!in_array($tipo, [5, 6, 7, 8], true) || $consello == true) {
            $base = config('certificado.redirect_url');
            if ($base) {
                $url = rtrim($base, '?&') . (str_contains($base, '?') ? '' : '?') . urlencode($certificadoId);
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

    /**
     * Marca un certificado como eliminado (eliminación lógica).
     *
     * @param int|string $certificadoId Identificador del certificado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function borrarCertificado($certificadoId)
    {
        $record = CertificadoInspeccion::findOrFail($certificadoId);
        $record->cin_filaeliminada = true;
        $record->save();

        return response()->json([
            'status' => 'ok',
            'message' => 'Certificado marcado como eliminado correctamente.',
        ]);
    }

    public function subirPdfActualizado($certificadoId)
    {
        $request = request();

        // Validar que se haya enviado un archivo
        $request->validate([
            'certificado_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ], [
            'certificado_file.required' => 'Debe seleccionar un archivo PDF',
            'certificado_file.mimes' => 'El archivo debe ser un PDF',
            'certificado_file.max' => 'El archivo no debe superar los 10MB',
        ]);

        try {
            $file = $request->file('certificado_file');
            $result = $this->service->subirPdfActualizado($certificadoId, $file);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], $result['status_code'] ?? 400);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'file_name' => $result['file_name'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el certificado: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reemplazarPdfActualizado($certificadoId)
    {
        $request = request();

        // Validar que se haya enviado un archivo
        $request->validate([
            'certificado_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ], [
            'certificado_file.required' => 'Debe seleccionar un archivo PDF',
            'certificado_file.mimes' => 'El archivo debe ser un PDF',
            'certificado_file.max' => 'El archivo no debe superar los 10MB',
        ]);

        try {
            $file = $request->file('certificado_file');
            $result = $this->service->reemplazarPdfActualizado($certificadoId, $file);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], $result['status_code'] ?? 400);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'file_name' => $result['file_name'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reemplazar el certificado: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function obtenerSiguienteNumero()
    {
        $siguiente = $this->service->obtenerSiguienteNumero();
        return response()->json(['siguiente_numero' => $siguiente]);
    }

}
