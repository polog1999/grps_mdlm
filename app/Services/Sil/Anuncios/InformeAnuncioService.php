<?php

namespace App\Services\Sil\Anuncios;

use App\Models\Anuncios;
use PhpOffice\PhpWord\TemplateProcessor;

class InformeAnuncioService
{
    /**
     * Reemplaza los placeholders {{VAR}} del template Word
     * con los datos reales del Anuncio y devuelve la ruta del
     * archivo temporal generado.
     */
    public function generarInforme(Anuncios $anuncio): string
    {
        // Asegurar que la relación expediente esté cargada
        $anuncio->loadMissing('expediente');

        $templatePath = storage_path('app/templates/anuncios/template_informe_anuncio.docx');

        $processor = new TemplateProcessor($templatePath);

        // -------------------------------------------------------
        // Datos del Expediente
        // -------------------------------------------------------
        $expediente = $anuncio->expediente;
        $this->setVar($processor, 'N_EXPEDIENTE', $expediente?->n_expediente ?? '');
        $this->setVar($processor, 'SOLICITANTE', $expediente?->snapshot_solicitante_nombre_completo ?? '');
        $this->setVar($processor, 'SOLICITANTE_DNI', $expediente?->snapshot_solicitante_dni ?? '');
        $this->setVar($processor, 'SOLICITANTE_TELEFONO', $expediente?->snapshot_solicitante_telefono ?? '');
        $this->setVar($processor, 'SOLICITANTE_DIRECCION', $expediente?->snapshot_solicitante_direccion ?? '');
        $this->setVar($processor, 'DOMICILIO_FISCAL', $expediente?->snapshot_legal_direccion ?? '');
        $this->setVar($processor, 'LEGAL_NOMBRE', $expediente?->snapshot_legal_nombre ?? '');
        $this->setVar($processor, 'LEGAL_DNI', $expediente?->snapshot_legal_dni_ruc ?? '');

        // -------------------------------------------------------
        // Datos del Anuncio
        // -------------------------------------------------------
        $this->setVar($processor, 'N_ANUNCIO', $anuncio->n_anuncio ?? '');
        $this->setVar($processor, 'FECHA_RECEPCION', $anuncio->fecha_recepcion_evaluar ? \Carbon\Carbon::parse($anuncio->fecha_recepcion_evaluar)->format('d/m/Y') : '');

        $this->setVar($processor, 'UBICACION', $anuncio->ubicacion_del_anuncio ?? '');
        $this->setVar($processor, 'ANCHO', $anuncio->ancho_m ?? '');
        $this->setVar($processor, 'ALTO', $anuncio->alto_m ?? '');
        $this->setVar($processor, 'ESPESOR', $anuncio->espesor_cm ?? '');
        $this->setVar($processor, 'N_CARAS', $anuncio->n_de_caras ?? '');
        $this->setVar($processor, 'DESCRIPCION', $anuncio->descripcion ?? '');
        $this->setVar($processor, 'MATERIALES', $anuncio->materiales_descripcion ?? '');
        $this->setVar($processor, 'DICTAMEN', $anuncio->dictamen?->value ?? '');
        $this->setVar($processor, 'ESTADO', $anuncio->estado_anuncio?->value ?? '');

        // -------------------------------------------------------
        // Guardar en archivo temporal y devolver la ruta
        // -------------------------------------------------------
        $outputDir = storage_path('app/temp');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $filename = 'informe_anuncio_' . ($anuncio->n_anuncio ?? $anuncio->id) . '_' . now()->format('YmdHis') . '.docx';
        $outputPath = $outputDir . DIRECTORY_SEPARATOR . $filename;

        $processor->saveAs($outputPath);

        return $outputPath;
    }

    /**
     * Reemplaza la sintaxis {{VAR}} que usa el template.
     * TemplateProcessor de PHPWord trabaja con ${VAR}, así que
     * usamos setValue que maneja ambos formatos internamente.
     */
    private function setVar(TemplateProcessor $processor, string $key, mixed $value): void
    {
        try {
            // phpoffice/phpword TemplateProcessor acepta ${VAR} y {{VAR}} indistintamente
            $processor->setValue($key, htmlspecialchars((string) $value));
        } catch (\Throwable) {
            // Si el placeholder no existe en el template, lo ignoramos silenciosamente
        }
    }
}
