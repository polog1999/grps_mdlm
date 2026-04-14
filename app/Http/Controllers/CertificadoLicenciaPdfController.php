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
        // Prioridad: si lig_giroespecifico tiene valor, usarlo; sino, usar gir_descripcion
        $giros = \App\Models\LicenciaGiro::where('lic_id', $licenciaId)
            ->where('lig_filaeliminada', false) // Filtra solo los no eliminados    
            ->with('giro')
            ->get()
            ->map(function ($licenciaGiro) {
                // Verificar si lig_giroespecifico tiene valor (no vacío, no solo espacios)
                $giroEspecifico = trim($licenciaGiro->lig_giroespecifico ?? '');
                if (!empty($giroEspecifico)) {
                    return $giroEspecifico;
                }
                // Fallback: usar la descripción del giro
                return $licenciaGiro->giro->gir_descripcion ?? null;
            })
            ->filter()
            ->toArray();

        // Lógica para determinar antecedente (origen)
        $antecedente = null;
        $numeroLicenciaPadre = null;
        try {
            \Log::info("--- INICIO BUSQUEDA ANTECEDENTE PARA LICENCIA ID: {$licenciaId} ---");

            // Buscar si esta licencia es dependiente de otra
            $relacion = \App\Models\LicenciaRelacion::where('lic_id_dependencia', $licenciaId)->first();

            if ($relacion) {
                // Obtener el ID de la licencia padre
                $licenciaPadreId = $relacion->lic_id;
                \Log::info("1. Relación encontrada en BD. ID Licencia Padre: {$licenciaPadreId}");

                // Buscar la licencia padre para ver su estado
                $licenciaPadre = \App\Models\CertificadoLicenciaFuncionamiento::find($licenciaPadreId);

                if ($licenciaPadre) {
                    $numeroLicenciaPadre = $licenciaPadre->lic_numlic;
                    \Log::info("2. Modelo Licencia Padre encontrado. Número recuperado: " . ($numeroLicenciaPadre ?? 'NULO'));

                    if ($licenciaPadre->esl_id) {
                        $estado = $licenciaPadre->tipoEstadoLicencia;
                        if ($estado) {
                            $antecedente = $estado->esl_descripcion;
                            \Log::info("3. Estado (Antecedente) recuperado: {$antecedente}");
                        } else {
                            \Log::warning("3. El padre tiene esl_id {$licenciaPadre->esl_id} pero no se encontró la descripción en tipoEstadoLicencia.");
                        }
                    } else {
                        \Log::info("3. La licencia padre no tiene un estado (esl_id es nulo).");
                    }
                } else {
                    \Log::error("2. ERROR: Existe relación ID {$licenciaPadreId} pero no se encontró el registro en CertificadoLicenciaFuncionamiento.");
                }
            } else {
                \Log::info("1. No se encontró relación de dependencia (Es una licencia nueva o el trámite no registró el padre).");
            }

        } catch (\Exception $e) {
            \Log::error('CRITICAL ERROR al obtener antecedente', [
                'id' => $licenciaId,
                'error' => $e->getMessage()
            ]);
        }

        $isTransferido = false;
        $numeroLicenciaTransferida = null;
        try {
            $relacionTransferida = \App\Models\LicenciaRelacion::where('lic_id_dependencia', $licenciaId)
                ->whereNotNull('old_lic_id')
                ->first();

            if ($relacionTransferida) {
                $isTransferido = true;
                $licenciaAntigua = \App\Models\CertificadoLicenciaFuncionamiento::find($relacionTransferida->old_lic_id);
                if ($licenciaAntigua) {
                    $numeroLicenciaTransferida = $licenciaAntigua->lic_numlic;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error al verificar transferencia', [
                'id' => $licenciaId,
                'error' => $e->getMessage()
            ]);
        }

        // Renderizar la vista Blade a HTML
        $html = view('certificado-licencia', [
            'licencia' => $datosLicencia,
            'qrImage' => $qrDataUri,
            'giros' => $giros,
            'antecedente' => $antecedente,
            'numeroLicenciaPadre' => $numeroLicenciaPadre,
            'isTransferido' => $isTransferido,
            'numeroLicenciaTransferida' => $numeroLicenciaTransferida,
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
        // Prioridad: si lig_giroespecifico tiene valor, usarlo; sino, usar gir_descripcion
        $giros = \App\Models\LicenciaGiro::where('lic_id', $licenciaId)
            ->with('giro')
            ->get()
            ->map(function ($licenciaGiro) {
                // Verificar si lig_giroespecifico tiene valor (no vacío, no solo espacios)
                $giroEspecifico = trim($licenciaGiro->lig_giroespecifico ?? '');
                if (!empty($giroEspecifico)) {
                    return $giroEspecifico;
                }
                // Fallback: usar la descripción del giro
                return $licenciaGiro->giro->gir_descripcion ?? null;
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
