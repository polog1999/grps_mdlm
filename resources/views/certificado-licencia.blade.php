<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Licencia de Funcionamiento - {{ $licencia->NUMERO_LICENCIA ?? 'N/A' }}</title>
    <style>
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
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: white;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            max-width: 17cm;
            margin: 0 auto;
            padding: 0;
            background: white;
        }

        /* Header con datos y QR */
        .header-section {
            width: 100%;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 3px 0;
        }

        .header-left-col {
            width: 55%;
            white-space: nowrap;
        }

        .header-right-col {
            width: 45%;
            white-space: nowrap;
        }

        .qr-col {
            width: 15%;
            text-align: right;
            vertical-align: top;
        }

        .field-label {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #000;
            font-weight: normal;
        }

        .field-value {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            font-weight: bold;
            color: #000;
        }

        .qr-image {
            width: 150px;
            height: 150px;
            display: block;
        }

        .certificate-header {
            font-family: 'Sultan Nahia Regular', Arial, sans-serif;
            font-size: 14pt;
            font-weight: bold;
            color: #000;
            text-align: left;
        }

        /* Título principal */
        .title-section {
            text-align: center;
            margin: 25px 0 20px 0;
        }

        .title-main {
            font-family: Arial, sans-serif;
            color: #000;
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .title-sub {
            font-family: Arial, sans-serif;
            color: #000;
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .title-type {
            font-family: Arial, sans-serif;
            color: #000;
            font-size: 13pt;
            font-weight: bold;
        }

        /* Campos de datos del certificado */
        .data-section {
            margin-bottom: 15px;
            font-family: Arial, sans-serif;
            font-size: 9pt;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .data-label {
            width: 130px;
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 9pt;
        }

        .data-value {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            font-weight: bold;
            color: #000;
        }

        /* Texto legal */
        .legal-text {
            text-align: justify;
            margin-bottom: 10px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 8pt;
            line-height: 1.5;
            color: #000;
        }

        .legal-text-bold {
            font-weight: bold;
            line-height: 1.8;
        }

        .legal-title {
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 3px;
            font-size: 9pt;
            color: #000;
        }

        .legal-paragraph {
            margin-bottom: 8px;
        }

        .legal-paragraph-normal {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8pt;
            font-weight: normal;
            text-align: justify;
            margin-bottom: 8px;
            line-height: 1.5;
            color: #000;
        }

        .vigencia-text {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9pt;
            font-weight: bold;
            color: #000;
            text-align: justify;
        }

        /* Segunda sección legal - con outdent a la izquierda */
        .legal-section-two {
            margin-left: -10px;
            text-align: justify;
        }

        /* Footer */
        .footer-section {
            margin-top: 30px;
            font-size: 10pt;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-left {
            text-align: left;
            color: #000;
        }

        .footer-right {
            text-align: right;
            color: #000;
        }

        .nota {
            margin-top: 15px;
            font-size: 9pt;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Espaciador invisible para margen superior -->
        <table style="width: 100%; border: none; margin: 0; padding: 0;">
            <tr>
                <td style="height: 0.8cm; border: none;">&nbsp;</td>
            </tr>
        </table>

        <!-- Header con datos y QR -->
        <table class="header-table">
            <!-- Primera fila: Certificado Nº -->
            <tr>
                <td colspan="2" style="text-align: left; vertical-align: top;">
                    <div class="certificate-header">Certificado Nº {{ $licencia->NUMERO_LICENCIA ?? '' }}</div>
                </td>
            </tr>
            <!-- Espaciador vertical -->
            <tr>
                <td colspan="2" style="height: 1.50cm; border: none;">&nbsp;</td>
            </tr>
            <!-- Segunda fila: QR a la derecha -->
            <tr>
                <td colspan="2" style="text-align: right; vertical-align: top; padding-bottom: 10px;">
                    @if(isset($qrImage) && $qrImage)
                        <img src="{{ $qrImage }}" alt="QR" class="qr-image">
                    @endif
                </td>
            </tr>

            <!-- Segunda fila: datos en dos columnas -->
            <tr>
                <td class="header-left-col">
                    <table style="width: 100%;">
                        <tr>
                            <td><span class="field-label">Expediente Nº</span>.................... : <span
                                    class="field-value">{{ $licencia->EXPEDIENTE_NRO ?? '' }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="field-label">RSG</span>................................... : <span
                                    class="field-value">{{ $licencia->RESOLUCION_NRO ?? '' }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="field-label">Código Catastral</span>................ : <span
                                    class="field-value">{{ $licencia->CODIGO_CATASTRAL ?? '' }}</span></td>
                        </tr>
                    </table>
                </td>
                <td class="header-right-col">
                    <table style="width: 100%;">
                        <tr>
                            <td><span class="field-label">Área Actividad Económica</span> : <span
                                    class="field-value">@if(isset($licencia->AREA) && is_numeric($licencia->AREA)){{ number_format((float) $licencia->AREA, 2, '.', '') }}m2
                                    @endif</span></td>
                        </tr>
                        <tr>
                            <td><span class="field-label">Zonificación</span>....................... : <span
                                    class="field-value">{{ $licencia->ZONIFICACION ?? '' }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="field-label">Nº Licencia</span>......................... : <span
                                    class="field-value">{{ $licencia->NUMERO_LICENCIA ?? '' }}</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Título -->
        <div class="title-section">
            <div class="title-main">SUBGERENCIA DE PROMOCIÓN EMPRESARIAL Y AUTORIZACIONES</div>
            <div class="title-sub">LICENCIA DE FUNCIONAMIENTO</div>
            <div class="title-type">{{ $licencia->TIPO_LICENCIA ?? 'ESPECIAL' }}</div>
        </div>

        <!-- Datos del certificado -->
        <div class="data-section">
            <table class="data-table">
                <tr>
                    <td class="data-label">Otorgado a......... :</td>
                    <td class="data-value">{{ $licencia->RAZON_SOCIAL ?? '' }}</td>
                </tr>
                <tr>
                    <td class="data-label">RUC Nº............... :</td>
                    <td class="data-value">{{ $licencia->RUC ?? '' }}</td>
                </tr>
                <tr>
                    <td class="data-label">Ubicado en......... :</td>
                    <td class="data-value">{{ $licencia->UBICACION ?? '' }}</td>
                </tr>
                <tr>
                    <td class="data-label">Giro(s)................ :</td>
                    <td class="data-value">{{ !empty($giros) ? implode(', ', $giros) : ($licencia->GIRO ?? '') }}</td>
                </tr>
                <tr>
                    <td class="data-label">Horario Atención :</td>
                    <td class="data-value">
                        @php
                            $horaInicio = $licencia->lic_horainicio ?? null;
                            $horaFin = $licencia->lic_horafin ?? null;
                            if ($horaInicio && $horaFin) {
                                $horaInicioFormateada = \Carbon\Carbon::parse($horaInicio)->format('H:i');
                                $horaFinFormateada = \Carbon\Carbon::parse($horaFin)->format('H:i');
                                echo $horaInicioFormateada . ' - ' . $horaFinFormateada . ' Horas.';
                            }
                        @endphp
                    </td>
                </tr>
            </table>
        </div>

        <!-- Texto legal -->
        <div class="legal-text">
            <p class="legal-paragraph legal-text-bold">
                El presente certificado se expide en aplicación al D.S. Nº 163-2020-PCM Decreto Supremo que aprueba el
                Texto Único Ordenado de la Ley Nº 28976, Ley Marco de Licencia de Funcionamiento en concordancia al D.S.
                Nº 002-2018-PCM Decreto Supremo que aprueba el Nuevo Reglamento de Inspecciones Técnicas de Seguridad en
                Edificaciones, que determina la matriz de riesgos de los establecimiento objeto de inspección.
            </p>
        </div>

        <!-- Segunda sección legal con outdent -->
        <div class="legal-section-two">
            <p class="vigencia-text">
                @php
                    $tipoLicencia = strtoupper($licencia->TIPO_LICENCIA ?? '');
                @endphp
                @if($tipoLicencia === 'INDETERMINADA' || $tipoLicencia === 'TEMPORAL')
                    La licencia tiene vigencia {{ $licencia->TIPO_LICENCIA }}.
                @else
                    El plazo de esta licencia se encuentra sujeto a la licencia principal.
                @endif
            </p>
            <p class="legal-title">COMPROMISO :</p>
            <p class="legal-paragraph-normal">
                El administrado asume el compromiso de no vulnerar ni infringir la normativa legal vigente al momento de
                realizar la actividad comercial autorizada
                por medio de la Licencia de Funcionamiento.
            </p>
            <p class="legal-title">OBSERVACIONES :</p>
            <p class="legal-paragraph-normal">
                En caso se detecte incumplimiento de la normativa legal vigente, así como el haber incurrido en alguna
                de las causales establecidas en el artículo 32 de
                la Ordenanza N° 475/MDLM, se dará inicio al procedimiento de Revocatoria de la Licencia de
                Funcionamiento; así como en la Clausura del Establecimiento
                donde se realiza la actividad comercial autorizada.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <table class="footer-table">
                <tr>
                    <td class="footer-right">La Molina, {{ now()->format('d') }} de
                        {{ now()->locale('es')->translatedFormat('F') }} de {{ now()->format('Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>