<?php

namespace App\Services\Sil\Anuncios;

use App\Models\Anuncios;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log;
use NcJoes\OfficeConverter\OfficeConverter;
class CertificadoAnuncioService
{
    /**
     * Genera un certificado de anuncio en formato Word
     * reemplazando las variables del template con los datos del registro.
     */
    public function generarCertificado(Anuncios $anuncio): string
    {

        Log::info("Iniciando generación de certificado para anuncio ID: {$anuncio->id}");

        // Cargar relaciones necesarias
        $anuncio->loadMissing(['expediente', 'tipoAnuncio', 'licencia', 'colores', 'caracteristicaFisica']);

        $templatePath = app_path('Filament/Clusters/Sil/Resources/Anuncios/Template/template_carton_anuncio.docx');
        $processor = new TemplateProcessor($templatePath);

        // -------------------------------------------------------
        // Datos del Expediente
        // -------------------------------------------------------
        $expediente = $anuncio->expediente;
        $this->setVar($processor, 'N_EXPEDIENTE', $expediente?->n_expediente ?? '');
        $this->setVar($processor, 'SOLICITANTE', $expediente?->snapshot_solicitante_nombre_completo ?? '');
        $this->setVar($processor, 'DNI_RUC', $expediente?->snapshot_solicitante_dni ?? '');
        $this->setVar($processor, 'DIRECCION_PREDIO', $expediente?->snapshot_solicitante_direccion ?? '');
        $this->setVar($processor, 'N_RESOLUCION', $expediente?->n_resolucion_subgerencial ?? '');
        if ($expediente?->fecha_resolucion_subgerencial) {
            \Carbon\Carbon::setLocale('es');
            $fechaObj = \Carbon\Carbon::parse($expediente->fecha_resolucion_subgerencial);
            $fechaResolucionFormato = str_pad($fechaObj->format('d'), 2, '0', STR_PAD_LEFT) . ' de ' . ucfirst($fechaObj->translatedFormat('F')) . ' de ' . $fechaObj->format('Y');
            $this->setVar($processor, 'FECHA_RESOLUCION', $fechaResolucionFormato);
        } else {
            $this->setVar($processor, 'FECHA_RESOLUCION', '');
        }

        $this->setVar($processor, 'LICENCIA_DE_F', $anuncio->licencia?->lic_numlic ?? '');
        $this->setVar($processor, 'LICENCIA_ANIO', $anuncio->licencia?->lic_filafecha ? \Carbon\Carbon::parse($anuncio->licencia->lic_filafecha)->format('Y') : '');

        // -------------------------------------------------------
        // Datos del Anuncio
        // -------------------------------------------------------
        $this->setVar($processor, 'N_ANUNCIO', $anuncio->n_anuncio ?? '');
        $this->setVar($processor, 'DESCRIPCION_LEYENDA', $anuncio->descripcion ?? '');
        $this->setVar($processor, 'CARACTERISTICAS_FISICAS', $anuncio->caracteristicaFisica?->descripcion ? mb_convert_case($anuncio->caracteristicaFisica->descripcion, MB_CASE_TITLE, "UTF-8") : '');
        $this->setVar($processor, 'TIPO_ANUNCIO', $anuncio->tipoAnuncio?->descripcion ? mb_convert_case($anuncio->tipoAnuncio->descripcion, MB_CASE_TITLE, "UTF-8") : '');
        $this->setVar($processor, 'MATERIALES', $anuncio->materiales_descripcion ?? '');
        $this->setVar($processor, 'UBICACIÓN_ANUNCIO', $anuncio->ubicacion_del_anuncio ?? '');

        // Formato de medidas: "X m ancho x Y m alto x Z cm espesor"
        $medidasParts = [];
        if (!empty($anuncio->ancho_m)) {
            $medidasParts[] = "{$anuncio->ancho_m} m ancho";
        }
        if (!empty($anuncio->alto_m)) {
            $medidasParts[] = "{$anuncio->alto_m} m alto";
        }
        if (!empty($anuncio->espesor_cm)) {
            $medidasParts[] = "{$anuncio->espesor_cm} cm espesor";
        }
        $this->setVar($processor, 'MEDIDAS', implode(' x ', $medidasParts));

        // Formato de caras: "Una (01) cara"
        $nCaras = (int) ($anuncio->n_de_caras ?? 1);
        $nombresNumeros = [
            1 => 'Una',
            2 => 'Dos',
            3 => 'Tres',
            4 => 'Cuatro',
            5 => 'Cinco',
            6 => 'Seis',
            7 => 'Siete',
            8 => 'Ocho',
            9 => 'Nueve',
            10 => 'Diez'
        ];
        $nombre = $nombresNumeros[$nCaras] ?? (string) $nCaras;
        $label = $nCaras === 1 ? 'cara' : 'caras';
        $nFormatted = str_pad($nCaras, 2, '0', STR_PAD_LEFT);
        $this->setVar($processor, 'N_CARAS', "{$nombre} ({$nFormatted}) {$label}");

        // Colores separados por comas
        $coloresStr = $anuncio->colores?->pluck('descripcion')->implode(', ') ?? '';
        $this->setVar($processor, 'COLORES', $coloresStr);

        // Vigencia con formato temporal si aplica
        $vigenciaStr = $anuncio->vigencia?->value ?? '';
        if (strtoupper($vigenciaStr) === 'TEMPORAL' && $anuncio->fecha_inicio_vigencia && $anuncio->fecha_fin_vigencia) {
            $inicio = \Carbon\Carbon::parse($anuncio->fecha_inicio_vigencia);
            $fin = \Carbon\Carbon::parse($anuncio->fecha_fin_vigencia);
            $meses = (int) $inicio->diffInMonths($fin);
            $unid = $meses == 1 ? 'Mes' : 'Meses';
            $vigenciaStr = "Temporal ({$meses} {$unid}) " . $inicio->format('d/m/Y') . " - " . $fin->format('d/m/Y');
        } else {
            $vigenciaStr = mb_convert_case($vigenciaStr, MB_CASE_TITLE, "UTF-8");
        }
        $this->setVar($processor, 'VIGENCIA', $vigenciaStr);

        // Lógica para VIGENCIA_LICENCIA usando LicenciaService
        $vigenciaLicenciaStr = '.'; // Valor por defecto si no es temporal o falla
        if ($anuncio->licencia && $anuncio->licencia->lic_id) {
            try {
                $licenciaService = app(\App\Services\Sil\Licencias\LicenciaService::class);
                $datosLicencia = $licenciaService->obtenerDatosPorIdLicenciaDirecta($anuncio->licencia->lic_id);

                if ($datosLicencia) {
                    $tipoLicencia = $datosLicencia->TIPO_LICENCIA ?? '';
                    $fechaEmision = $datosLicencia->FECHA_EMISION ?? null;
                    $fechaVencimiento = $datosLicencia->FECHA_VENCIMIENTO ?? null;

                    if (strtoupper($tipoLicencia) === 'TEMPORAL' && $fechaEmision && $fechaVencimiento) {
                        $inicio = \Carbon\Carbon::parse($fechaEmision);
                        $fin = \Carbon\Carbon::parse($fechaVencimiento);
                        $meses = (int) $inicio->diffInMonths($fin);
                        $unid = $meses == 1 ? 'Mes' : 'Meses';
                        $vigenciaLicenciaStr = ", de vigencia Temporal ({$meses} {$unid}) " . $inicio->format('d/m/Y') . " - " . $fin->format('d/m/Y');
                    }
                }
            } catch (\Throwable $e) {
                Log::error("Error obteniendo datos de licencia para VIGENCIA_LICENCIA anuncio ID: {$anuncio->id}", ['error' => $e->getMessage()]);
            }
        }

        $this->setVar($processor, 'VIGENCIA_LICENCIA', $vigenciaLicenciaStr);

        // -------------------------------------------------------
        // Guardar en archivo temporal y devolver la ruta
        // -------------------------------------------------------
        $outputDir = storage_path('app/temp');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $baseFilename = 'certificado_anuncio_' . ($anuncio->id) . '_' . now()->format('YmdHis');
        $wordOutputPath = $outputDir . DIRECTORY_SEPARATOR . $baseFilename . '.docx';

        Log::info("Guardando archivo DOCX temporal en: {$wordOutputPath}");
        $processor->saveAs($wordOutputPath);

        try {
            Log::info("Preparando entorno de LibreOffice...");

            $tempProfileDir = storage_path('app/temp/libreoffice_profile');
            if (!is_dir($tempProfileDir)) {
                mkdir($tempProfileDir, 0755, true);
            }
            $_SERVER['HOME'] = $tempProfileDir;
            putenv("HOME={$tempProfileDir}");

            Log::info("Iniciando conversión a PDF...");

            $binPath = config('services.libreoffice.bin');
            $converter = new OfficeConverter($wordOutputPath, $outputDir, $binPath);

            $pdfFilename = $baseFilename . '.pdf';
            $converter->convertTo($pdfFilename);

            $pdfOutputPath = $outputDir . DIRECTORY_SEPARATOR . $pdfFilename;

            Log::info("Conversión finalizada. Verificando existencia de archivo PDF...");

            // Validar que el PDF realmente exista
            if (!file_exists($pdfOutputPath)) {
                throw new \Exception("El comando de conversión se ejecutó pero no generó el archivo en: {$pdfOutputPath}");
            }

            // Limpieza: borrar el .docx para ahorrar espacio
            if (file_exists($wordOutputPath)) {
                Log::info("Eliminando archivo DOCX temporal.");
                unlink($wordOutputPath);
            }

            Log::info("Proceso completado con éxito. Devolviendo ruta del PDF.");
            return $pdfOutputPath;

        } catch (\Exception $e) {
            Log::error("Error crítico durante la conversión a PDF", [
                'anuncio_id' => $anuncio->id,
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);

            if (file_exists($wordOutputPath)) {
                unlink($wordOutputPath);
            }

            throw new \Exception("No se pudo generar el documento PDF. Revise los logs del sistema.");
        }
    }

    /**
     * Reemplaza placeholders del template Word de forma segura.
     */
    private function setVar(TemplateProcessor $processor, string $key, mixed $value): void
    {
        try {
            $processor->setValue($key, htmlspecialchars((string) $value));
        } catch (\Throwable) {
            // Si el placeholder no existe en el template, lo ignoramos silenciosamente
        }
    }
}
