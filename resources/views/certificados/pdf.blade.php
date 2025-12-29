@php
    use Carbon\Carbon;
    use NumberToWords\NumberToWords;

    $tipoEdificacion = $record->tie_id;
    if ($tipoEdificacion == 5 || $tipoEdificacion == 6) {
        $textoTipoEdificacion = "DE RIESGO BAJO O RIESGO MEDIO";
        $numeroAnexo = 13;
    } else if ($tipoEdificacion == 7 || $tipoEdificacion == 8) {
        $textoTipoEdificacion = "DE RIESGO ALTO O RIESGO MUY ALTO";
        $numeroAnexo = 14;
    }

    $numberToWords = new NumberToWords();
    $numberTransformer = $numberToWords->getNumberTransformer('es');
    $fechaCaducidad = Carbon::parse($record->cin_fec_fin);
    $fechaExpedicion = Carbon::parse($record->cin_fecha);
    $fechaRenovacion = $fechaCaducidad->copy()->subWeekdays(30)->format('d/m/Y');
    $fechaCaducidadFormatted = $fechaCaducidad->format('d/m/Y');
    $fechaExpedicionFormatted = $fechaExpedicion->format('d/m/Y');

    $capacidad = $record->cin_capacidad;
    $capacidadTexto = strtoupper($numberTransformer->toWords($capacidad));
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 49px;
            line-height: 1.4;
        }

        /* === Encabezados === */
        .top-header {
            text-align: left;
            font-size: 7px;
        }

        .anexo {
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            padding-top: 130px;
        }

        .main-header {
            text-align: center;
            justify-content: center;
        }

        .main-header h1 {
            font-size: 17px;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .main-header .numero {
            font-size: 19px;
            margin-top: 8px;
            font-weight: bold;
        }

        .intro-text {
            text-align: justify;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .establecimiento-nombre {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 10px 0;
            border-bottom: 1px solid #000;
            padding-bottom: 0;
            line-height: 1;
            width: 100%;
        }

        /* === Tablas base === */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 15px;
        }

        /* === Celdas base === */
        table td {
            vertical-align: top;
            border: none;
            line-height: 1;
            padding-left: 0;
        }

        /* === Ubicación === */
        table.ubicacion-table {
            border: none;
        }

        table.ubicacion-table td {
            padding: 5px 8px;
            padding-top: 20px;
            padding-bottom: 0;
        }

        table.ubicacion-table td.value {
            border-bottom: 1px solid #000;
            width: 32%;
            text-align: center;
        }

        table.ubicacion-table td.label {
            width: 18%;
            background-color: #fff;
        }

        /* === Texto de certificación === */
        .certificacion-text {
            text-align: justify;
            margin: 10px 0;
            font-size: 15px;
        }

        .certificacion-text strong {
            font-style: italic;
        }

        /* === Tablas estructuradas (info, giro, expediente, fecha) === */
        table.info-table,
        table.giro-table,
        table.expediente-table,
        table.fecha-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 15px;
        }

        /* === Celdas comunes === */
        table.info-table td,
        table.giro-table td,
        table.expediente-table td,
        table.fecha-table td {
            border: none;
            line-height: 1;
            padding-top: 20px;
            padding-bottom: 0;
        }

        /* === Info table === */
        table.info-table td.label {
            width: 36%;
        }

        table.info-table td.numero {
            text-align: center;
            width: 10%;
            border-bottom: 1px solid #000;
            border-right: 5px solid #fff;
        }

        table.info-table td.texto {
            text-align: center;
            width: 35%;
            border-bottom: 1px solid #000;
        }

        table.info-table td.unidad {
            text-align: right;
            width: 15%;
        }

        /* === Giro table === */
        table.giro-table td {
            padding: 0;
            margin: 0;
        }

        table.giro-table td.label {
            width: 35%;
            font-weight: normal;
            padding-right: 2px;
        }

        table.giro-table td.value {
            border-bottom: 1px solid #000;
            font-weight: bold;
            padding-left: 0;
        }

        /* === Expediente table === */
        table.expediente-table td.label {
            width: 15%;
        }

        table.expediente-table td.value {
            border-bottom: 1px solid #000;
            text-align: center;
        }

        table.expediente-table td.numero {
            width: 25%;
        }

        table.expediente-table td.resolucion {
            width: 35%;
        }

        /* === Vigencia === */
        .vigencia-section {
            margin: 12px 0;
            font-weight: bold;
            font-size: 15px;
        }

        /* === Fecha table === */
        table.fecha-table td.label {
            width: 45%;
            font-weight: bold;
        }

        table.fecha-table td.value {
            text-decoration: underline;
        }

        table.fecha-table td.nota {
            font-size: 10px;
            text-align: left;
            font-weight: bold;
            padding-top: 0;
            padding-bottom: 5px;
        }

        /* === Footer === */
        .footer-note {
            margin-top: 5px;
            font-size: 9px;
            line-height: 1.3;
            color: #000;
        }

        .footer-note>div {
            margin-bottom: 8px;
        }

        .footer-note ul {
            list-style: none;
            margin: 0;
        }

        .footer-note ul li {
            text-align: justify;
            position: relative;
            padding-left: 10px;
        }

        /* Guion al inicio */
        .footer-note ul li::before {
            content: "-";
            position: absolute;
            left: 0;
        }
    </style>
</head>

<body>
    <!-- Anexo Encabezado arriba -->
    <div class="anexo">
        ANEXO {{$numeroAnexo}}
    </div>


    <!-- Título principal -->
    <div class="main-header">
        <h1>
            CERTIFICADO DE INSPECCIÓN TÉCNICA DE SEGURIDAD EN EDIFICACIONES<br>
            PARA ESTABLECIMIENTOS OBJETO DE INSPECCIÓN CLASIFICADOS CON NIVEL<br>
            {{$textoTipoEdificacion}} SEGÚN LA MATRIZ DE RIESGOS
        </h1>
        <div class="numero">N° {{$record->cin_numero}}-{{$record->cin_anio}}</div>
    </div>

    <!-- Texto introductorio -->
    <div class="intro-text">
        El Órgano Ejecutante de la Municipalidad de La Molina, en cumplimiento de lo establecido en el D.S. N°
        002-2018-PCM, ha realizado la Inspección Técnica de Seguridad en Edificaciones al Establecimiento
        Objeto de Inspección:
    </div>

    <!-- Nombre del establecimiento -->
    <div class="establecimiento-nombre">
        {{$record->cin_establecimiento ?? 'SIN DATO'}}
    </div>

    <!-- Tabla de ubicación -->
    <table class="ubicacion-table">
        <tr>
            <td class="label" style="width: 28%;">Ubicado en</td>
            <td class="value" style="text-align: left;" colspan="6">
                {{ $record->cin_ubicacion ?? 'AV. LA MOLINA N°864, MZ. A1, LT. 03, URB. AMPLIACION RESIDENCIAL MONTERRICO, DISTRITO DE LA MOLINA' }}
            </td>
        </tr>
        <tr>
            <td class="label" style="width: 28%;">Distrito</td>
            <td class="value">La Molina</td>
            <td class="label" style="width: 28%;">Provincia</td>
            <td class="value">Lima</td>
            <td class="label" style="width: 28%;">Departamento</td>
            <td class="value">Lima</td>
        </tr>
        <tr>
            <td class="label">solicitado por</td>
            <td class="value" colspan="6">{{$record->cin_solicitante}}</td>
        </tr>
    </table>

    <!-- Certificación -->
    <div class="certificacion-text">
        El que suscribe <strong>CERTIFICA</strong> que el Establecimiento Objeto antes señalado <strong>CUMPLE CON LAS
            CONDICIONES DE SEGURIDAD.</strong>
    </div>

    <!-- Capacidad -->
    <table class="info-table">
        <tr>
            <td class="label">Capacidad Máxima de la Edificación:</td>
            <td class="value numero">{{$record->cin_capacidad}}</td>
            <td class="value texto">({{$capacidadTexto}})</td>
            <td class="value unidad">personas</td>
        </tr>
    </table>
    <table class="giro-table">
        <tr>
            <td class="label">Giro o actividad de la Edificación:</td>
            <td class="value">{{$record->cin_giro}}</td>
        </tr>
    </table>
    <table class="expediente-table">
        <tr>
            <td class="label">Expediente N°</td>
            <td class="value numero">{{$record->cin_expediente}}</td>
            <td class="label">Resolución N°:</td>
            <td class="value resolucion">{{$record->cin_resolucion}}{{$record->cin_resolucion_sigla}}</td>
        </tr>
    </table>


    <!-- Vigencia -->
    <div class="vigencia-section">
        VIGENCIA: &nbsp;&nbsp;&nbsp; 2 AÑOS*
    </div>

    <table class="fecha-table">
        <tr>
            <td class="label">LUGAR:</td>
            <td class="value">La Molina</td>
        </tr>
        <tr>
            <td class="label">FECHA DE EXPEDICIÓN:</td>
            <td class="value">{{$fechaExpedicionFormatted}}</td>
        </tr>
        <tr>
            <td class="label">FECHA DE SOLICITUD DE RENOVACIÓN:</td>
            <td class="value">{{ $fechaRenovacion }}</td>
        </tr>
        <tr>
            <td class="nota" colspan="2">
                (Treinta días hábiles anteriores a la fecha de caducidad)
            </td>
        </tr>
        <tr>
            <td class="label">FECHA DE CADUCIDAD:</td>
            <td class="value">{{$fechaCaducidadFormatted}}</td>
        </tr>
    </table>
    @if(filled($record->cin_nota))
        <div>
            <div style="text-decoration: underline;font-weight: bold">
                Nota:
            </div>
            <div>
                {!! nl2br(e($record->cin_nota)) !!}
            </div>
        </div>
    @endif
    <!-- Notas al pie -->
    <div class="footer-note">
        <div style="margin-bottom: 0px;font-style:normal;">
            "El presente Certificado de ITSE no constituye autorización alguna para el funcionamiento del objeto de la
            presente inspección"
        </div>
        <div style="margin-bottom: 8px; font-style:normal;">
            NOTA:
        </div>
        <ul>
            <li>DE ACUERDO A LO ESTABLECIDO EN EL REGLAMENTO DE INSPECCIONES TECNICAS DE SEGURIDAD EN EDIFICACIONES
                APROBADO POR DECRETO SUPREMO Nº 002-2018-PCM., EL PRESENTE CERTIFICADO DEBERÁ SER FIMADO POR EL
                RESPONSABLE DEL ÓRGANO EJECUTANTE.
            </li>
            <li>ESTE CERTIFICADO DEBERÁ COLOCARSE EN UN LUGAR VISIBLE DENTRO DEL ESTABLECIMIENTO O OBJETO DE INSPECCION.
            </li>
            <li>CUALQUIER TACHA O ENMENDADURA INVALIDA EL PRESENTE CERTIFICADO</li>
        </ul>
    </div>

</body>

</html>