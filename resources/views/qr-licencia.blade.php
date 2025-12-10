<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Licencia de Funcionamiento - Municipalidad de La Molina</title>
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px 30px;
            border-bottom: 3px solid #000;
        }

        .header-left {
            font-size: 11px;
            font-weight: bold;
            max-width: 200px;
        }

        .header-center {
            text-align: center;
            flex: 1;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
        }

        .municipalidad {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .gerencia {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .subgerencia {
            font-size: 10px;
            margin-bottom: 10px;
        }

        .titulo-principal {
            font-size: 13px;
            font-weight: bold;
            border: 2px solid #000;
            padding: 5px;
            display: inline-block;
        }

        .header-right {
            font-size: 10px;
            text-align: right;
            max-width: 150px;
        }

        /* Sección de datos */
        .seccion {
            padding: 15px 30px;
        }

        .seccion-titulo {
            background-color: #000;
            color: white;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .dato-fila {
            display: flex;
            padding: 5px 0;
            border-bottom: 1px solid #e0e0e0;
            font-size: 11px;
        }

        .dato-label {
            font-weight: bold;
            min-width: 180px;
            padding-right: 10px;
        }

        .dato-valor {
            flex: 1;
        }

        /* Print styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                max-width: 100%;
            }

            @page {
                margin: 1cm;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .header-left,
            .header-right {
                max-width: 100%;
                text-align: center;
                margin: 10px 0;
            }

            .dato-fila {
                flex-direction: column;
            }

            .dato-label {
                min-width: auto;
                margin-bottom: 5px;
            }

            .seccion {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                SISTEMA DE LICENCIA DE FUNCIONAMIENTO
            </div>

            <div class="header-center">
                <div class="logo">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="30" r="20" fill="#FFD700" />
                        <path d="M 20 50 Q 50 30 80 50" fill="#90EE90" />
                        <path d="M 20 50 L 20 80 L 80 80 L 80 50" fill="#87CEEB" />
                        <rect x="35" y="60" width="30" height="20" fill="#8B4513" />
                    </svg>
                </div>
                <div class="municipalidad">Municipalidad de La Molina</div>
                <div class="gerencia">GERENCIA DE AUTORIZACIONES Y PROMOCION COMERCIAL</div>
                <div class="subgerencia">SUBGERENCIA DE LICENCIAS COMERCIALES</div>
                <div class="titulo-principal">CONSULTA DE LICENCIA DE FUNCIONAMIENTO INDETERMINADA</div>
            </div>

            <div class="header-right">
                {{ now()->format('d') }} de {{ now()->locale('es')->translatedFormat('F') }} del
                {{ now()->format('Y') }} - {{ now()->format('H:i') }}
            </div>
        </div>

        <!-- Datos de la Razón Social -->
        <div class="seccion">
            <div class="seccion-titulo">I. DATOS DE LA RAZÓN SOCIAL</div>

            <div class="dato-fila">
                <div class="dato-label">N AUTORIZACIÓN</div>
                <div class="dato-valor">: {{ $licencia->lic_numlic ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">N EXPEDIENTE</div>
                <div class="dato-valor">: {{ $licencia->lic_expnum ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">NOMBRES Y APELLIDOS</div>
                <div class="dato-valor">: {{ $licencia->personasolicitante ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">RAZÓN SOCIAL</div>
                <div class="dato-valor">: {{ $licencia->razonsocial ?? $licencia->lic_razonsocial ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">R.U.C.</div>
                <div class="dato-valor">: {{ $licencia->per_ruc ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">UBICACIÓN</div>
                <div class="dato-valor">: {{ $licencia->lic_direccion ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">TELÉFONO</div>
                <div class="dato-valor">: {{ $licencia->per_telefono ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">E-MAIL</div>
                <div class="dato-valor">: {{ $licencia->per_email ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Datos del Lote Catastral -->
        <div class="seccion">
            <div class="seccion-titulo">II. DATOS DEL LOTE CATASTRAL</div>

            <div class="dato-fila">
                <div class="dato-label">COD. CATASTRAL</div>
                <div class="dato-valor">: {{ $licencia->codigocatastral ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">COD. PREDIAL</div>
                <div class="dato-valor">: {{ $licencia->lic_codigopredial ?? 'N/A' }}</div>
                <div class="dato-label" style="margin-left: 40px;">MYPE</div>
                <div class="dato-valor">: {{ $licencia->lic_mype ? 'Sí' : 'No' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">URBANIZACIÓN</div>
                <div class="dato-valor">: {{ $licencia->lic_urbanizacion ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">DIRECCIÓN</div>
                <div class="dato-valor">: {{ $licencia->lic_direccion ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">NUMERO</div>
                <div class="dato-valor">: {{ $licencia->numvia ?? 'N/A' }}</div>
                <div class="dato-label" style="margin-left: 40px;">ETAPA</div>
                <div class="dato-valor">: {{ $licencia->etapa ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">MZ.</div>
                <div class="dato-valor">: {{ $licencia->mz ?? 'N/A' }}</div>
                <div class="dato-label" style="margin-left: 40px;">LOTE</div>
                <div class="dato-valor">: {{ $licencia->lote ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">ZONIFICACIÓN</div>
                <div class="dato-valor">: {{ $licencia->zonificacion ?? 'N/A' }}</div>
                <div class="dato-label" style="margin-left: 40px;">N° TRABAJADORES</div>
                <div class="dato-valor">: {{ $licencia->num_trabajadores ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">AREA</div>
                <div class="dato-valor">:
                    {{ $licencia->lic_area ? number_format($licencia->lic_area, 3) . 'm2.' : 'N/A' }}
                </div>
                <div class="dato-label" style="margin-left: 40px;">VENTA ANUAL</div>
                <div class="dato-valor">: {{ $licencia->venta_anual ?? 'N/A' }} UIT</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">CONSTITUCIÓN</div>
                <div class="dato-valor">: {{ $licencia->constitucion ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">TIPO ESTABLECIMIENTO</div>
                <div class="dato-valor">: {{ $licencia->tes_descripcion ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Datos de la Licencia -->
        <div class="seccion">
            <div class="seccion-titulo">III. DATOS DE LA LICENCIA</div>

            <div class="dato-fila">
                <div class="dato-label">TIPO LICENCIA</div>
                <div class="dato-valor">: {{ $licencia->tli_descripcion ?? 'INDETERMINADA' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">N° RESOLUCIÓN</div>
                <div class="dato-valor">: {{ $licencia->lic_resnum ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">FECHA RESOLUCIÓN</div>
                <div class="dato-valor">: {{ $licencia->lic_fecharesolucion ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">GIRO</div>
                <div class="dato-valor">: {{ $licencia->lic_giro ?? 'N/A' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">FECHA EMISIÓN</div>
                <div class="dato-valor">: {{ $licencia->lic_fechaemision ?? 'N/A' }}</div>
                <div class="dato-label" style="margin-left: 40px;">FECHA VENCIMIENTO</div>
                <div class="dato-valor">: {{ $licencia->lic_fechavencimiento ?? '/ /' }}</div>
            </div>

            <div class="dato-fila">
                <div class="dato-label">OBSERVACIONES</div>
                <div class="dato-valor">: {{ $licencia->lic_licobs ?? '' }}</div>
            </div>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>

</html>