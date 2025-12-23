<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Licencia - {{ $licencia->NUMERO_LICENCIA ?? 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .content {
            margin-top: 20px;
        }

        .field {
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .field-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .field-value {
            font-size: 16px;
            color: #333;
        }

        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .giro-field .field-value {
            font-size: 14px;
            line-height: 1.5;
        }

        .qr-section {
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 4px;
            text-align: center;
        }

        .qr-container {
            margin-top: 10px;
        }

        .qr-image {
            max-width: 200px;
            width: 100%;
            height: auto;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>CERTIFICADO DE LICENCIA DE FUNCIONAMIENTO</h1>
        </div>

        <div class="content">
            <div class="two-columns">
                <div class="field">
                    <div class="field-label">Expediente N°</div>
                    <div class="field-value">{{ $licencia->EXPEDIENTE_NRO ?? 'No disponible' }}</div>
                </div>

                <div class="field">
                    <div class="field-label">RSG</div>
                    <div class="field-value">{{ $licencia->RESOLUCION_NRO ?? 'No disponible' }}</div>
                </div>
            </div>

            <div class="two-columns">
                <div class="field">
                    <div class="field-label">Código Catastral</div>
                    <div class="field-value">{{ $licencia->CODIGO_CATASTRAL ?? 'No disponible' }}</div>
                </div>

                <div class="field">
                    <div class="field-label">Área Actividad Económica</div>
                    <div class="field-value">
                        @if(isset($licencia->AREA) && is_numeric($licencia->AREA))
                            {{ number_format((float) $licencia->AREA, 2, '.', ',') }} m²
                        @else
                            No disponible
                        @endif
                    </div>
                </div>
            </div>

            <div class="two-columns">
                <div class="field">
                    <div class="field-label">Zonificación</div>
                    <div class="field-value">{{ $licencia->ZONIFICACION ?? 'No disponible' }}</div>
                </div>

                <div class="field">
                    <div class="field-label">N° Licencia</div>
                    <div class="field-value">{{ $licencia->NUMERO_LICENCIA ?? 'No disponible' }}</div>
                </div>
            </div>

            <div class="field">
                <div class="field-label">Tipo Licencia</div>
                <div class="field-value">{{ $licencia->TIPO_LICENCIA ?? 'No disponible' }}</div>
            </div>

            <div class="field">
                <div class="field-label">Otorgado a</div>
                <div class="field-value">{{ $licencia->RAZON_SOCIAL ?? 'No disponible' }}</div>
            </div>

            <div class="field">
                <div class="field-label">RUC N°</div>
                <div class="field-value">{{ $licencia->RUC ?? 'No disponible' }}</div>
            </div>

            <div class="field">
                <div class="field-label">Ubicado en</div>
                <div class="field-value">{{ $licencia->UBICACION ?? 'No disponible' }}</div>
            </div>

            <div class="field giro-field">
                <div class="field-label">Giro(s)</div>
                <div class="field-value">{{ $licencia->GIRO ?? 'No disponible' }}</div>
            </div>

            <div class="field">
                <div class="field-label">Horario Atención</div>
                <div class="field-value">
                    @php
                        $horaInicio = $licencia->lic_horainicio ?? null;
                        $horaFin = $licencia->lic_horafin ?? null;

                        if ($horaInicio && $horaFin) {
                            // Formatear horas (quitar segundos si existen)
                            $horaInicioFormateada = \Carbon\Carbon::parse($horaInicio)->format('H:i');
                            $horaFinFormateada = \Carbon\Carbon::parse($horaFin)->format('H:i');
                            $horario = $horaInicioFormateada . ' - ' . $horaFinFormateada . ' Horas';
                        } else {
                            $horario = 'No disponible';
                        }
                    @endphp
                    {{ $horario }}
                </div>
            </div>

            @if(isset($qrImage) && $qrImage)
                <div class="qr-section">
                    <div class="field-label">Código QR</div>
                    <div class="qr-container">
                        <img src="{{ $qrImage }}" alt="Código QR de la licencia" class="qr-image">
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>

</html>