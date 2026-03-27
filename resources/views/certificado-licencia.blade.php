<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Licencia de Funcionamiento - {{ $licencia->NUMERO_LICENCIA ?? 'N/A' }}</title>
    <style>
        :root {
            --font-family-base: Arial, sans-serif;
            --padding-vertical: 2px;
            --padding-horizontal: 12px;
            --font-size-base: 10pt;

            --mdlm-green: #007A33;
            --mdlm-yellow: #FFC400;
            --text-black: #000000;
            --border-light: #e0e0e0;
        }

        @page {
            size: A4 portrait;
            margin: 3.39cm 2cm 0.49cm 2cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family-base);
            font-size: var(--font-size-base);
            color: #000;
            background: white;
            line-height: 1.1;
            /* REDUCCIÓN: Line-height ligeramente más ajustado */
        }

        .container {
            width: 100%;
            max-width: 17cm;
            margin: 0 auto;
            padding: 0;
            background: white;
        }

        /* --- Header Técnico --- */
        .technical-box {
            width: 100%;
            /* REDUCCIÓN: Margen inferior de 12px a 5px */
            margin-bottom: 15px;
            background-color: #fcfcfc;
            /* REDUCCIÓN: Padding interno reducido */
            padding: 4px 0;
            position: relative;
        }

        .technical-box::after {
            content: '';
            display: block;
            width: 100%;
            height: 2px;
            /* REDUCCIÓN: Margen superior de 10px a 2px */
            margin-top: 2px;
            opacity: 0.6;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            /* REDUCCIÓN: Padding de celda reducido a 1px vertical */
            padding: 5px 5px;
            border-bottom: 1px dotted var(--border-light);
        }

        .header-table tr:last-child td {
            border-bottom: none;
        }

        .header-left-col {
            padding-right: 15px;
        }

        .header-right-col {
            width: 1%;
            white-space: nowrap;
            padding-left: 15px;
            border-left: 1px solid #eee;
        }

        .field-row {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .field-label {
            white-space: nowrap;
            font-size: var(--font-size-base);
            color: #333;
            font-weight: normal;
            flex-shrink: 0;
        }

        .field-separator {
            padding: 0 5px;
            color: var(--mdlm-green);
            font-weight: bold;
            flex-shrink: 0;
        }

        .field-value {
            text-align: left;
            font-size: var(--font-size-base);
            font-weight: bold;
            color: var(--text-black);
        }

        .qr-image {
            width: 150px;
            height: 150px;
            display: block;
        }

        .certificate-header {
            font-size: 14.3pt;
            font-weight: bold;
            color: #000;
            text-align: left;
        }

        /* --- Título --- */
        .title-section {
            text-align: center;
            /* REDUCCIÓN: Márgenes externos reducidos */
            margin: 5px 0;
            margin-bottom: 15px;
            width: 100%;
        }

        .title-text-common {
            font-size: 14pt;
            color: #000;
            text-transform: uppercase;
            line-height: 1;
            display: block;
        }

        .title-main {
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            white-space: nowrap;
        }

        .title-sub {
            font-weight: bold;
            margin-bottom: 10px;
            display: inline-block;
            padding-bottom: 1px;
        }

        .title-type {
            font-weight: bold;
            display: inline-block;
            background-color: #f2f2f2;
            padding: 2px 25px;
            /* REDUCCIÓN: Padding vertical interno */
            border-radius: 20px;
            border: 1px solid #e0e0e0;
        }

        /* --- Datos (Data Section) --- */
        .data-section {
            /* REDUCCIÓN: Márgenes verticales reducidos */
            margin-bottom: 15px;
            margin-top: 5px;
            font-family: Arial, sans-serif;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .data-table tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .data-table td {
            /* REDUCCIÓN: Padding vertical de celda a 3px */
            padding: 5px 5px;
            vertical-align: top;
        }

        .data-label-col {
            width: 95px;
            color: #444;
            font-size: var(--font-size-base);
            font-weight: normal;
            white-space: nowrap;
        }

        .data-separator-col {
            width: 20px;
            text-align: center;
            font-weight: bold;
        }

        .data-value-col {
            color: #000;
            font-size: var(--font-size-base);
            font-weight: bold;
            line-height: 1.2;
            text-transform: uppercase;
            text-align: justify;
        }

        .highlight-name {
            font-size: var(--font-size-base);
            letter-spacing: 0.5px;
        }

        /* --- Sección Legal --- */
        .legal-wrapper {
            /* REDUCCIÓN: Margen superior */
            margin-top: 5px;
            font-family: var(--font-family-base);
            color: var(--text-black);
        }

        .legal-base-box {
            text-align: justify;
            /* REDUCCIÓN: Margen inferior de 12px a 5px */
            margin-bottom: 15px;
            font-size: var(--font-size-base);
            line-height: 1.2;
            color: #444;
            border-bottom: 1px solid var(--border-light);
            /* REDUCCIÓN: Padding inferior */
            padding-bottom: 4px;
        }

        .vigencia-box {
            background-color: #f9f9f9;
            /* REDUCCIÓN: Padding y margen */
            padding: 4px 0;
            margin-bottom: 15px;
            font-size: var(--font-size-base);
            font-weight: bold;
            color: var(--text-black);
        }

        .legal-grid-table {
            width: 100%;
            border-collapse: collapse;
        }

        .legal-grid-td {
            vertical-align: top;
            width: 50%;
            padding-right: 15px;
        }

        .legal-grid-td.section-spacing {
            padding-bottom: 15px;
        }

        .legal-header {
            font-size: var(--font-size-base);
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
            /* REDUCCIÓN */
            display: block;
            border-bottom: 1px dotted var(--border-light);
            padding-bottom: 1px;
        }

        .legal-body {
            font-size: var(--font-size-base);
            font-weight: normal;
            text-align: justify;
            line-height: 1.2;
            /* REDUCCIÓN */
            color: var(--text-black);
            margin-top: 2px;
            /* REDUCCIÓN */
        }

        /* --- Footer --- */
        .footer-section {
            /* REDUCCIÓN: Margen superior de 30px a 10px */
            margin-top: 20px;
            font-size: var(--font-size-base);
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-right {
            text-align: right;
            color: #000;
        }
    </style>
</head>

<body>
    <div class="container">
        <table style="width: 100%; border: none; margin: 0; padding: 0;">
            <tr>
                <td style="height: 2.7cm; border: none; vertical-align: top; padding-top: 1.6cm;">
                    <div class="certificate-header">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{$licencia->NUMERO_LICENCIA ?? '' }}
                    </div>
                </td>
                <td
                    style="height: 3.8cm; border: none; vertical-align: top; text-align: right; padding-top: 3.5cm; padding-right: 0;">
                    @if(isset($qrImage) && $qrImage)
                        <img src="{{ $qrImage }}" alt="QR" class="qr-image" style="margin-right: -20px;">
                    @endif
                </td>
            </tr>
        </table>

        <div class="technical-box">
            <table class="header-table">
                <tr>
                    <td class="header-left-col">
                        <div class="field-row">
                            <span class="field-label">Expediente Nº.......... :</span>
                            <span class="field-value">{{ $licencia->EXPEDIENTE_NRO ?? '' }}</span>
                        </div>
                    </td>
                    <td class="header-right-col">
                        <div class="field-row">
                            <span class="field-label">Área Actividad Económica :</span>
                            <span class="field-value">
                                @if(isset($licencia->AREA) && is_numeric($licencia->AREA))
                                    {{ number_format((float) $licencia->AREA, 2, '.', '') }} m²
                                @endif
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="header-left-col">
                        <div class="field-row">
                            <span class="field-label">RSG......................... :</span>
                            <span class="field-value">{{ $licencia->RESOLUCION_NRO ?? '' }}</span>
                        </div>
                    </td>
                    <td class="header-right-col">
                        <!--
                        @php
                            $zonificacion = $licencia->ZONIFICACION ?? '';
                            try {
                                $licenciaId = $licencia->lic_id ?? $licencia->LIC_ID ?? null;
                                if ($licenciaId) {
                                    $licenciaCatastroService = app(\App\Services\Sil\Licencias\LicenciaCatastroService::class);
                                    $fiuId = $licenciaCatastroService->obtenerIdFichaUbicacion($licenciaId);

                                    if ($fiuId) {
                                        $fichaService = app(\App\Services\Sil\Infocat\FichaUbicacionService::class);
                                        $ficha = $fichaService->obtenerPorId($fiuId);

                                        if ($ficha && !empty($ficha->fiu_zonificacion)) {
                                            $zonificacion = $ficha->fiu_zonificacion;
                                        }
                                    }
                                }
                            } catch (\Exception $e) {
                                // Silent fallback
                            }
                        @endphp
                        -->
                        <div class="field-row">
                            <span class="field-label">Zonificación........................ :</span>
                            <span class="field-value">{{ $licencia->ZONIFICACION ?? ''}}</span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="header-left-col">
                        <div class="field-row">
                            <span class="field-label">Código Catastral...... :</span>
                            <span class="field-value">{{ $licencia->CODIGO_CATASTRAL ?? '' }}</span>
                        </div>
                    </td>
                    <td class="header-right-col">
                        <div class="field-row">
                            <span class="field-label">Nº Licencia......................... :</span>
                            <span class="field-value">{{ $licencia->NUMERO_LICENCIA ?? '' }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="title-section">
            <div class="title-text-common title-main">Subgerencia de Promoción Empresarial y Autorizaciones</div>

            <div class="title-text-common">
                <span class="title-sub">LICENCIA DE FUNCIONAMIENTO</span>
            </div>

            <div class="title-text-common title-type">{{ $licencia->TIPO_LICENCIA ?? 'NO ENCONTRADO' }}</div>

            @if(isset($antecedente) && !empty($antecedente))
                <div class="title-text-common" style="margin-top: 5px; font-size: 10pt; font-weight: bold;">
                    (
                    @if(strtoupper($antecedente) === 'ACTIVO')
                        {{-- Caso especial cuando es ACTIVO --}}
                        LICENCIA PRINCIPAL N° {{ $numeroLicenciaPadre ?? '' }}
                    @else
                        {{-- Lógica original para otros antecedentes --}}
                        {{ $antecedente }}
                        @if(isset($numeroLicenciaPadre) && !empty($numeroLicenciaPadre))
                            @if(($licencia->TIPO_LICENCIA ?? '') === 'ESPECIAL')
                                DE LA AUTORIZACIÓN N° {{ $numeroLicenciaPadre }}
                            @else
                                DE LA LICENCIA N° {{ $numeroLicenciaPadre }}
                            @endif
                        @endif
                    @endif
                    )
                </div>
            @endif
        </div>

        <div class="data-section">
            <table class="data-table">
                <tr>
                    <td class="data-label-col">Otorgado a</td>
                    <td class="data-separator-col">:</td>
                    <td class="data-value-col highlight-name">{{ $licencia->RAZON_SOCIAL ?? '' }}</td>
                </tr>

                <tr>
                    <td class="data-label-col">RUC Nº</td>
                    <td class="data-separator-col">:</td>
                    <td class="data-value-col">{{ $licencia->RUC ?? '' }}</td>
                </tr>

                <tr>
                    <td class="data-label-col">Ubicado en</td>
                    <td class="data-separator-col">:</td>
                    <td class="data-value-col">
                        {{ $licencia->LIC_DIRECCION ?? '' }}
                    </td>
                </tr>

                <tr>
                    <td class="data-label-col">Giro(s)</td>
                    <td class="data-separator-col">:</td>
                    <td class="data-value-col">
                        {{ !empty($giros) ? implode(', ', $giros) : ($licencia->GIRO ?? '') }}
                    </td>
                </tr>

                <tr style="border-bottom: none;">
                    <td class="data-label-col">Horario Atención</td>
                    <td class="data-separator-col">:</td>
                    <td class="data-value-col">
                        @php
                            $horaInicio = $licencia->lic_horainicio ?? null;
                            $horaFin = $licencia->lic_horafin ?? null;
                            if ($horaInicio && $horaFin) {
                                $horaInicioFormateada = \Carbon\Carbon::parse($horaInicio)->format('H:i');
                                $horaFinFormateada = \Carbon\Carbon::parse($horaFin)->format('H:i');
                                echo $horaInicioFormateada . ' - ' . $horaFinFormateada . ' HORAS.';
                            }
                        @endphp
                    </td>
                </tr>
            </table>
        </div>

        <div class="legal-wrapper">

            <div class="legal-base-box">
                El presente certificado se expide en aplicación al D.S. Nº 163-2020-PCM Decreto Supremo que aprueba el
                Texto Único Ordenado de la Ley Nº 28976, Ley Marco de Licencia de Funcionamiento en concordancia al D.S.
                Nº 002-2018-PCM Decreto Supremo que aprueba el Nuevo Reglamento de Inspecciones Técnicas de Seguridad en
                Edificaciones, que determina la matriz de riesgos de los establecimiento objeto de inspección.
            </div>

            <div class="vigencia-box">
                @php
                    $tipoLicencia = strtoupper($licencia->TIPO_LICENCIA ?? '');
                @endphp
                VIGENCIA:
                @if($tipoLicencia === 'INDETERMINADA')
                    {{ $licencia->TIPO_LICENCIA }}
                @elseif($tipoLicencia === 'TEMPORAL')
                    {{ $licencia->TIPO_LICENCIA }} hasta el
                    {{ \Carbon\Carbon::parse($licencia->lic_fecha_fin ?? $licencia->FECHA_VENCIMIENTO ?? now())->format('d/m/Y') }}
                @elseif($tipoLicencia === 'ESPECIAL')
                    Sujeto a la Ordenanza N° 062/MDLM
                @else
                    Sujeta a la Licencia Principal N°:
                    {{ $licencia->lic_numlic_principal ?? $licencia->LIC_NUMLIC_PRINCIPAL ?? '' }}
                @endif
            </div>

            <table class="legal-grid-table">
                <tr>
                    <td class="legal-grid-td section-spacing">
                        <span class="legal-header">COMPROMISO</span>
                        <p class="legal-body">
                            El administrado asume el compromiso de no vulnerar ni infringir la normativa legal vigente
                            al momento de realizar la actividad comercial autorizada por medio de la Licencia de
                            Funcionamiento.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td class="legal-grid-td">
                        <span class="legal-header">OBSERVACIONES</span>
                        <p class="legal-body">
                            En caso se detecte incumplimiento de la normativa legal vigente, así como el haber incurrido
                            en alguna de las causales establecidas en el artículo 32 de la Ordenanza N° 475/MDLM, se
                            dará inicio al procedimiento de Revocatoria de la Licencia de Funcionamiento; así como en la
                            Clausura del Establecimiento donde se realiza la actividad comercial autorizada.
                        </p>
                    </td>
                </tr>
            </table>

        </div>
        <div class="footer-section">
            <table class="footer-table">
                <tr>
                    @php
                        $fechaEmision = $licencia->lic_fechaemision ?? $licencia->LIC_FECHAEMISION ?? $licencia->FECHA_EMISION ?? now();
                        $fechaCarbon = \Carbon\Carbon::parse($fechaEmision);
                    @endphp
                    <td class="footer-right">La Molina, {{ $fechaCarbon->format('d') }} de
                        {{ $fechaCarbon->locale('es')->translatedFormat('F') }} de {{ $fechaCarbon->format('Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>