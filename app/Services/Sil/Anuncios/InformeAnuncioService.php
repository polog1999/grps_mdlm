<?php

namespace App\Services\Sil\Anuncios;

use App\Models\Anuncios;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Services\Sil\Anuncios\DatosTramiteService;

class InformeAnuncioService
{

    protected $datosTramiteService;

    // Inyectamos el servicio de trámites para tener acceso a la data de Gestrad
    public function __construct(DatosTramiteService $datosTramiteService)
    {
        $this->datosTramiteService = $datosTramiteService;
    }

    /**
     * Reemplaza los placeholders {{VAR}} del template Word
     * con los datos reales del Anuncio y devuelve la ruta del
     * archivo temporal generado.
     */

    public function generarInforme(Anuncios $anuncio, ?string $nExpediente = null): string
    {
        // Asegurar que las relaciones necesarias estén cargadas antes de procesar
        $anuncio->loadMissing(['expediente', 'documentos', 'tipoAnuncio', 'licencia']);

        if (!$nExpediente) {
            $nExpediente = $anuncio->expediente?->n_expediente ?? '';
        }

        // Recuperar el número de informe técnico de los documentos del anuncio
        $informeTecnicoDoc = $anuncio->documentos->where('tipo_documento', 'INFORME TÉCNICO')->first();
        $nInformeTecnico = $informeTecnicoDoc?->n_documento ?? '';

        $datosGestrad = $this->datosTramiteService->getDatosTramite($nInformeTecnico, $nExpediente);

        $templatePath = app_path('Filament/Clusters/Sil/Resources/Anuncios/Template/template_informe_anuncio.docx');

        $processor = new TemplateProcessor($templatePath);


        if ($datosGestrad) {
            // Reemplazamos ANIO_DESCRIP por el campo no_anio_fscal (ej: AÑO DE LA ESPERANZA...)
            $this->setVar(
                $processor,
                'ANIO_DESCRIP',
                $datosGestrad->aAnio->no_anio_fscal ?? ''
            );
            // Formatear OBSV_ASUNTO: Primera letra en mayúscula, lo demás en minúscula excepto después de "/"
            $asuntoRaw = $datosGestrad->de_obser ?? '';
            $asuntoFormatted = '';
            if (!empty($asuntoRaw)) {
                $lower = mb_strtolower($asuntoRaw, 'UTF-8');
                // Capitalizar primera letra
                $temp = mb_strtoupper(mb_substr($lower, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($lower, 1, null, 'UTF-8');
                // Capitalizar después de "/" (incluyendo espacios opcionales)
                $asuntoFormatted = preg_replace_callback('/(\/\s*)([a-z]|[áéíóúñ])/u', function ($matches) {
                    return $matches[1] . mb_strtoupper($matches[2], 'UTF-8');
                }, $temp);
            }

            $this->setVar(
                $processor,
                'OBSV_ASUNTO',
                $asuntoFormatted
            );

            $this->setVar(
                $processor,
                'NU_INFORM_COMPLETO',
                $datosGestrad->nu_tram_cmplto ?? ''
            );

            // Obtenemos los objetos intermedios para mayor claridad y seguridad
            $pCrgo = $datosGestrad->pDtoTrmte?->pCrgos?->first();
            $rCrgoLgin = $pCrgo?->rCrgosLgins?->first();

            $this->setVar(
                $processor,
                'USR_DESTINO',
                $rCrgoLgin?->pLgin?->pUsrio?->no_crto ? mb_convert_case($rCrgoLgin->pLgin->pUsrio->no_crto, MB_CASE_TITLE, "UTF-8") : ''
            );

            $cargoRaw = $pCrgo?->de_crgo ?? '';

            $cargoFormateado = '';
            if (!empty($cargoRaw)) {
                // 1. Convertimos todo a formato Título
                $cargoTitulo = mb_convert_case($cargoRaw, MB_CASE_TITLE, "UTF-8");

                // 2. Buscamos lo que esté entre paréntesis y lo pasamos a MAYÚSCULAS
                $cargoFormateado = preg_replace_callback('/\((.*?)\)/', function ($matches) {
                    return '(' . mb_strtoupper($matches[1], 'UTF-8') . ')';
                }, $cargoTitulo);
            }

            $this->setVar($processor, 'USR_DESTINO_CARGO', $cargoFormateado);

            // Acceso correcto a la colección aSmllaIntrnos
            $smlla = $datosGestrad->aSmllaIntrnos?->first();
            $this->setVar(
                $processor,
                'FECHA_INFORME_TECNICO_GESTRAD',
                ($smlla && $smlla->ts_usua_modi) ? \Carbon\Carbon::parse($smlla->ts_usua_modi)->format('d/m/Y') : ''
            );

            $this->setVar(
                $processor,
                'USR_ORIGEN',
                $datosGestrad->pLgin->pUsrio->no_crto ? mb_convert_case($datosGestrad->pLgin->pUsrio->no_crto, MB_CASE_TITLE, "UTF-8") : ''
            );

            $this->setVar(
                $processor,
                'FECHA_INGRESO_GESTRAD',
                $datosGestrad->pDtoTrmte?->fe_ingr_trmte ? \Carbon\Carbon::parse($datosGestrad->pDtoTrmte?->fe_ingr_trmte)->format('d/m/Y') : ''
            );
        }
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
        $this->setVar($processor, 'DISTRITO_LEGAL', $expediente?->snapshot_legal_distrito ? mb_convert_case($expediente->snapshot_legal_distrito, MB_CASE_TITLE, "UTF-8") : '');
        $this->setVar($processor, 'REPRESENTANTE_LEGAL_O_APODERADO', $expediente?->snapshot_legal_nombre ?? '');
        $this->setVar($processor, 'DNI_CARNET_DE_EXT', $expediente?->snapshot_legal_dni_ruc ?? '');
        $this->setVar($processor, 'N_RESOLUCION', $expediente?->n_resolucion_subgerencial ?? '');
        $this->setVar($processor, 'FECHA_RESOLUCION', $expediente?->fecha_resolucion_subgerencial ? \Carbon\Carbon::parse($expediente->fecha_resolucion_subgerencial)->format('d/m/Y') : '');

        $zonificacionStr = '';
        if ($expediente && $expediente->zonificacion) {
            $zonificacionStr = $expediente->zonificacion->siglas . ' - ' . $expediente->zonificacion->descripcion;
        }
        $this->setVar($processor, 'ZONIFICACION', $zonificacionStr);

        $recibo = $expediente?->reciboPago;
        $this->setVar($processor, 'RECIBO_DE_PAGO', $recibo?->n_recibo_pago ?? '');
        $this->setVar($processor, 'MONTO', $recibo?->monto ?? '');

        // -------------------------------------------------------
        // Insertar Código QR
        // -------------------------------------------------------
        if (!empty($nInformeTecnico)) {
            $qrPath = $this->datosTramiteService->getQrImageByInforme($nInformeTecnico);
            if ($qrPath && file_exists($qrPath)) {
                try {
                    $processor->setImageValue('IMAGEN_QR', [
                        'path' => $qrPath,
                        'width' => 120, // Ajustar el ancho según sea necesario
                        'height' => 120, // Ajustar el alto según sea necesario
                        'ratio' => false
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error al insertar imagen QR en Word: ' . $e->getMessage());
                    $this->setVar($processor, 'IMAGEN_QR', '');
                }
            } else {
                $this->setVar($processor, 'IMAGEN_QR', '');
            }
        } else {
            $this->setVar($processor, 'IMAGEN_QR', '');
        }

        // -------------------------------------------------------
        // Datos del Anuncio
        // -------------------------------------------------------
        $this->setVar($processor, 'N_INFORME_TECNICO', $nInformeTecnico);
        $this->setVar($processor, 'N_ANUNCIO', $anuncio->n_anuncio ?? '');
        $this->setVar($processor, 'TIPO_ANUNCIO', $anuncio->tipoAnuncio?->descripcion ? mb_convert_case($anuncio->tipoAnuncio->descripcion, MB_CASE_TITLE, "UTF-8") : '');
        $this->setVar($processor, 'LICENCIA_ANIO', $anuncio->licencia?->lic_filafecha ? $anuncio->licencia->lic_filafecha->format('Y') : '');
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
        $carasTexto = "{$nombre} ({$nFormatted}) {$label}";
        $this->setVar($processor, 'N_DE_CARAS', $carasTexto);
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
