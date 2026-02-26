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
    public function generarInforme(Anuncios $anuncio, string $nInformeTecnico = ''): string
    {
        // Asegurar que la relación expediente esté cargada
        $anuncio->loadMissing('expediente');

        $templatePath = app_path('Filament/Clusters/Sil/Resources/Anuncios/Template/template_informe_anuncio.docx');

        $processor = new TemplateProcessor($templatePath);

        // -------------------------------------------------------
        // Datos del Expediente
        // -------------------------------------------------------
        $expediente = $anuncio->expediente;
        $this->setVar($processor, 'N_EXPEDIENTE', $expediente?->n_expediente ?? '');
        $this->setVar($processor, 'SOLICITANTE', $expediente?->snapshot_solicitante_nombre_completo ?? '');
        $this->setVar($processor, 'DNI_RUC', $expediente?->snapshot_solicitante_dni ?? '');
        $this->setVar($processor, 'TELEFONO_SISTEMADECLARADO', $expediente?->snapshot_solicitante_telefono ?? '');
        $this->setVar($processor, 'DIRECCION_DEL_PREDIO_MATERIA_A_EVALUAR', $expediente?->snapshot_solicitante_direccion ?? '');
        $this->setVar($processor, 'DIRECCION_FISCAL', $expediente?->snapshot_legal_direccion ?? '');
        $this->setVar($processor, 'REPRESENTANTE_LEGAL_O_APODERADO', $expediente?->snapshot_legal_nombre ?? '');
        $this->setVar($processor, 'DNI_CARNET_DE_EXT', $expediente?->snapshot_legal_dni_ruc ?? '');

        $zonificacionStr = '';
        if ($expediente && $expediente->zonificacion) {
            $zonificacionStr = $expediente->zonificacion->siglas . ' - ' . $expediente->zonificacion->descripcion;
        }
        $this->setVar($processor, 'ZONIFICACION', $zonificacionStr);

        $recibo = $expediente?->reciboPago;
        $this->setVar($processor, 'RECIBO_DE_PAGO', $recibo?->n_recibo_pago ?? '');
        $this->setVar($processor, 'MONTO', $recibo?->monto ?? '');

        // -------------------------------------------------------
        // Datos del Anuncio
        // -------------------------------------------------------
        $this->setVar($processor, 'N_INFORME_TECNICO', $nInformeTecnico);
        $this->setVar($processor, 'N_ANUNCIO', $anuncio->n_anuncio ?? '');
        $this->setVar($processor, 'FECHA_RECEPCION', $anuncio->fecha_recepcion_evaluar ? \Carbon\Carbon::parse($anuncio->fecha_recepcion_evaluar)->format('d/m/Y') : '');

        $this->setVar($processor, 'UBICACIÓN_DEL_ANUNCIO', $anuncio->ubicacion_del_anuncio ?? '');
        $this->setVar($processor, 'ANCHO', $anuncio->ancho_m ?? '');
        $this->setVar($processor, 'ALTO', $anuncio->alto_m ?? '');
        $this->setVar($processor, 'ESPESOR', $anuncio->espesor_cm ?? '');

        // Formatear MEDIDAS: "10.58 m ancho x 1.35 m alto x 1 cm espesor"
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
        $this->setVar($processor, 'N_DE_CARAS', $anuncio->n_de_caras ?? '');
        $this->setVar($processor, 'DESCRIPCION', $anuncio->descripcion ?? '');
        $this->setVar($processor, 'MATERIALES', $anuncio->materiales_descripcion ?? '');
        $this->setVar($processor, 'DICTAMEN', $anuncio->dictamen?->value ?? '');
        $this->setVar($processor, 'ESTADO', $anuncio->estado_anuncio?->value ?? '');
        $vigenciaStr = $anuncio->vigencia?->value ?? '';
        if (strtoupper($vigenciaStr) === 'TEMPORAL' && $anuncio->fecha_inicio_vigencia && $anuncio->fecha_fin_vigencia) {
            $inicio = \Carbon\Carbon::parse($anuncio->fecha_inicio_vigencia);
            $fin = \Carbon\Carbon::parse($anuncio->fecha_fin_vigencia);
            $meses = (int) $inicio->diffInMonths($fin);
            $unid = $meses == 1 ? 'Mes' : 'Meses';
            $vigenciaStr = "Temporal ({$meses} {$unid}) " . $inicio->format('d/m/Y') . " - " . $fin->format('d/m/Y');
        }
        $this->setVar($processor, 'VIGENCIA', $vigenciaStr);
        $this->setVar($processor, 'TIPO_DE_ANUNCIO', $anuncio->tipoAnuncio?->descripcion ?? '');

        // Obtener colores separados por comas
        $coloresStr = $anuncio->colores?->pluck('descripcion')->implode(', ') ?? '';
        $this->setVar($processor, 'COLORES', $coloresStr);
        $this->setVar($processor, 'LICENCIA_DE_F', $anuncio->licencia?->lic_numlic ?? '');
        // Obtener características físicas
        $this->setVar($processor, 'CARACTERISTICAS_FISICAS', $anuncio->caracteristicaFisica?->descripcion ?? '');

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
