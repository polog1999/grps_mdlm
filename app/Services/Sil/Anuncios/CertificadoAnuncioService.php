<?php

namespace App\Services\Sil\Anuncios;

use App\Models\Anuncios;
use Log;
use NcJoes\OfficeConverter\OfficeConverter;
use PhpOffice\PhpWord\TemplateProcessor;

class CertificadoAnuncioService
{
    /**
     * Genera un certificado de anuncio en formato Word
     * reemplazando las variables del template con los datos del registro.
     */
    public function generarCertificado(Anuncios $anuncio): string
    {
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
        $this->setVar($processor, 'FECHA_RESOLUCION', $expediente?->fecha_resolucion_subgerencial ? \Carbon\Carbon::parse($expediente->fecha_resolucion_subgerencial)->format('d/m/Y') : '');

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

        // -------------------------------------------------------
        // Guardar en archivo temporal y devolver la ruta
        // -------------------------------------------------------
        $outputDir = storage_path('app/temp');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $baseFilename = 'certificado_anuncio_' . ($anuncio->id) . '_' . now()->format('YmdHis');
        $wordOutputPath = $outputDir . DIRECTORY_SEPARATOR . $baseFilename . '.docx';
        $processor->saveAs($wordOutputPath);

        // -------------------------------------------------------
        // 5. CONVERSIÓN A PDF (Parche para Windows)
        // -------------------------------------------------------
        try {
            // Solución al error "Undefined array key HOME"
            $tempProfileDir = $outputDir . DIRECTORY_SEPARATOR . 'libreoffice_profile';
            if (!is_dir($tempProfileDir))
                mkdir($tempProfileDir, 0755, true);

            $_SERVER['HOME'] = $tempProfileDir;
            putenv("HOME={$tempProfileDir}");

            // Ruta al ejecutable de LibreOffice
            $binPath = 'C:\Program Files\LibreOffice\program\soffice.exe';

            if (!file_exists($binPath)) {
                throw new \Exception("No se encontró el ejecutable de LibreOffice en: " . $binPath);
            }

            $converter = new OfficeConverter($wordOutputPath, $outputDir, $binPath);

            $pdfFilename = $baseFilename . '.pdf';
            $converter->convertTo($pdfFilename);

            $pdfOutputPath = $outputDir . DIRECTORY_SEPARATOR . $pdfFilename;

            // Limpiar el archivo Word temporal
            if (file_exists($wordOutputPath)) {
                unlink($wordOutputPath);
            }

            if (!file_exists($pdfOutputPath)) {
                throw new \Exception("El proceso de conversión terminó sin generar el archivo PDF.");
            }

            return $pdfOutputPath;

        } catch (\Exception $e) {
            Log::error("Error en generación de PDF: " . $e->getMessage());
            throw $e;
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
