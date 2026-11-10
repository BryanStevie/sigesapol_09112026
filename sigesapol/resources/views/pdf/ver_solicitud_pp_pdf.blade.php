<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ORDEN DE SOLICITUD PROCEDIMIENTO</title>
    <style>
        @page {
            margin: 30px 35px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7pt;
        }
        table {
            border-collapse: collapse;
        }

        /* Encabezado (mismo esquema que rad_reporte) */
        table.encabezado {
            width: 100%;
        }
        table.encabezado td {
            border: 1px solid #000;
            font-family: Helvetica;
            font-size: 6.5pt;
            font-weight: 700;
            height: 46px;
            padding: 3px 5px;
            vertical-align: middle;
        }
        .img-escudo {
            height: 40px;
        }
        td.style1 {
            width: 38px;
            text-align: center;
            border: none !important;
        }
        td.style2 {
            background-color: red;
            color: white;
            text-align: center;
            width: 30px;
        }
        td.style3 {
            background-color: rgb(102, 102, 102);
            color: white;
        }
        td.style3-1 {
            background-color: rgb(166, 166, 166);
            color: #000;
        }
        td.style3-2 {
            background-color: rgb(198, 198, 198);
            color: #000;
        }
        td.style3-3 {
            background-color: rgb(217, 217, 217);
            color: #000;
        }
        td.style3-4 {
            background-color: #fff;
            color: #000;
        }

        /* Titulo */
        .titulo {
            text-align: center;
            font-size: 12pt;
            font-weight: 700;
            margin-top: 8px;
        }
        .nro-orden {
            text-align: center;
            font-size: 8pt;
            margin-top: 2px;
        }

        /* Datos */
        table.datos {
            margin-top: 18px;
            width: 100%;
        }
        table.datos td {
            font-size: 7pt;
            padding: 0;
            vertical-align: top;
        }
        table.datos td.etiqueta {
            font-weight: 700;
            width: 150px;
            white-space: nowrap;
        }
        .paciente {
            font-size: 10pt;
            font-weight: 700;
        }

        /* Diagnosticos */
        table.diagnosticos {
            margin-top: 18px;
            width: 100%;
        }
        table.diagnosticos th {
            font-size: 7pt;
            font-weight: 700;
            text-align: left;
            padding-bottom: 6px;
        }
        table.diagnosticos td {
            font-size: 7pt;
            vertical-align: top;
            padding-bottom: 4px;
        }
        .separador {
            border-top: 2px solid #000;
            margin: 0;
        }
        .espacio {
            width: 100%;
            margin: 0;
            padding: 0;
            font-size: 0;
            line-height: 0;
        }

        /* Procedimientos */
        .subtitulo {
            font-size: 8pt;
            font-weight: 700;
            margin: 0;
        }
        table.procedimientos {
            width: 100%;
        }
        table.procedimientos th,
        table.procedimientos td {
            border: 1px solid #000;
            padding: 4px 6px;
        }
        table.procedimientos th {
            font-size: 7pt;
            font-weight: 700;
            text-align: left;
        }
        table.procedimientos td {
            font-size: 8.5pt;
            vertical-align: middle;
        }
        .centro {
            text-align: center !important;
        }

        /* Indicacion */
        table.indicacion td {
            border: 1px solid #000;
        }

        /* Firma sin token */
        .firma {
            position: absolute;
            bottom: 20px;
            right: 10px;
            width: 250px;
        }
        .firma td {
            border: none;
            vertical-align: middle;
            font-size: 6.5pt;
        }
    </style>
</head>

<body>
<?php
    $prestacionPp = $solicitudPp->prestacion;
    $nombreEstablecimiento = ($prestacionPp && $prestacionPp->establecimiento) ? $prestacionPp->establecimiento->nombre : '';
    $nombreServicio = $servicio ? $servicio->nombre_consultorio : '';

    $edadTexto = $datos_paciente->edad_corta;
    try {
        $fechaNac = \Carbon\Carbon::createFromFormat('d/m/Y', trim($datos_paciente->fecha_nacimiento));
        $diffEdad = $fechaNac->diff(\Carbon\Carbon::now());
        $edadTexto = $diffEdad->y . ' A, ' . str_pad($diffEdad->m, 2, '0', STR_PAD_LEFT) . ' M, ' . str_pad($diffEdad->d, 2, '0', STR_PAD_LEFT) . ' D';
    } catch (\Exception $e) {
    }

    $sexoTexto = $datos_paciente->sexo == 'M' ? 'MASCULINO' : ($datos_paciente->sexo == 'F' ? 'FEMENINO' : $datos_paciente->sexo);

    $tipoAtencionTexto = $solicitudPp->id_tipo_atencion == 1 ? 'AMBULATORIO'
        : ($solicitudPp->id_tipo_atencion == 2 ? 'EMERGENCIA'
        : ($solicitudPp->id_tipo_atencion == 3 ? 'HOSPITALIZACION' : ''));

    $fechaAtencion = '';
    if ($prestacionPp && $prestacionPp->created_at) {
        $fechaAtencion = \Carbon\Carbon::parse($prestacionPp->created_at)->format('d/m/Y - h:i:s A');
    }
?>
    <table class="encabezado">
        <tr>
            <td class="style1">
                <img class="img-escudo" src="images/escudo_republica_peru.png" alt="escudo_republica_peru">
            </td>
            <td class="style2">PERÚ</td>
            <td class="style3">MINISTERIO DEL INTERIOR</td>
            <td class="style3-1">POLICIA NACIONAL DEL PERÚ</td>
            <td class="style3-2">DIRECCION DE SANIDAD POLICIAL</td>
            <td class="style3-3">{{ $nombreEstablecimiento }}</td>
            <td class="style3-4">Departamento de {{ mb_convert_case($nombreServicio, MB_CASE_TITLE, 'UTF-8') }}</td>
        </tr>
    </table>

    <div class="titulo">ORDEN DE SOLICITUD PROCEDIMIENTO</div>
    <div class="nro-orden"><b>N° DE ORDEN:</b> {{ str_pad($solicitudPp->id, 8, '0', STR_PAD_LEFT) }}</div>

    <table class="datos">
        <tr>
            <td class="etiqueta">FECHA Y HORA DE ATENCION</td>
            <td>: {{ $fechaAtencion }}</td>
        </tr>
        <tr>
            <td class="etiqueta">TIPO Y N° DE DOCUMENTO</td>
            <td>: DNI - {{ $datos_paciente->nro_doc_ident }}</td>
        </tr>
        <tr>
            <td class="etiqueta" style="padding-top:4px;">PACIENTE</td>
            <td class="paciente">: {{ $datos_paciente->paterno . ' ' . $datos_paciente->materno . ' ' . $datos_paciente->nombre }}</td>
        </tr>
        <tr>
            <td class="etiqueta">EDAD Y SEXO</td>
            <td>: {{ $edadTexto }} - {{ $sexoTexto }}</td>
        </tr>
        <tr>
            <td class="etiqueta">PARENTESCO</td>
            <td>: {{ $datos_paciente->parentesgo }}</td>
        </tr>
        <tr>
            <td class="etiqueta">TIPO Y SERVICIO DE ATENCION</td>
            <td>: {{ $tipoAtencionTexto }} - {{ $nombreServicio }}</td>
        </tr>
        <tr>
            <td class="etiqueta">PROFESIONAL QUE SOLICITA</td>
            <td>:
                @if ($medicoPrestacion)
                    {{ $medicoPrestacion->nombre . ' ' . $medicoPrestacion->paterno . ' ' . $medicoPrestacion->materno }}
                    (CMP: {{ $medicoPrestacion->colegiatura }} - RNE: {{ $medicoPrestacion->rne }})
                @endif
            </td>
        </tr>
    </table>

    <table class="diagnosticos">
        <thead>
            <tr>
                <th style="width: 12%;">CODIGO</th>
                <th style="width: 68%;">DIAGNOSTICO</th>
                <th style="width: 20%; text-align: center;">TIPO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($diagnosticosAtencion as $diagnostico)
                <tr>
                    <td>{{ $diagnostico->codigo }}</td>
                    <td>{{ $diagnostico->nombre }}</td>
                    <td class="centro">{{ $diagnostico->descripcion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="espacio" style="height: 10px;"></div>
    <div class="separador"></div>
    <div class="espacio" style="height: 18px;"></div>

    <div class="subtitulo">NOMBRE DEL PROCEDIMIENTO</div>
    <div class="espacio" style="height: 6px;"></div>
    <table class="procedimientos">
        <thead>
            <tr>
                <th style="width: 6%;">N°</th>
                <th style="width: 84%;">DESCRIPCIÓN</th>
                <th style="width: 10%;" class="centro">CANT.</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach ($detallesAgrupados as $id_procedimiento => $grupoDetalles)
                <tr>
                    <td class="centro">{{ $i++ }}</td>
                    <td>{{ $grupoDetalles->first()->procedimiento->codigo . ' - ' . $grupoDetalles->first()->procedimiento->descripcion }}</td>
                    <td class="centro">{{ $grupoDetalles->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="espacio" style="height: 26px;"></div>
    <div class="subtitulo">INDICACIÓN DEL PROCEDIMIENTO</div>
    <div class="espacio" style="height: 6px;"></div>
    <table class="indicacion" style="width: 100%;">
        <tr>
            <td style="height: 110px; vertical-align: top; padding: 6px; font-size: 8pt;">
                <?php $j = 1; $totalProc = count($detallesAgrupados); ?>
                @foreach ($detallesAgrupados as $id_procedimiento => $grupoDetalles)
                    @if ($totalProc > 1)
                        <b>{{ $j++ }}.</b>
                    @endif
                    {!! nl2br(e($grupoDetalles->first()->indicacion)) !!}<br>
                @endforeach
            </td>
        </tr>
    </table>

    @if (intval($solicitudPp->flag) === 2 && $medicoPrestacion)
        <div class="firma">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 40%; text-align: right;"><img src="images/logo_sanidad.png" width="71px" /></td>
                    <td style="width: 60%; text-align: left;">
                        Firmado electrónicamente por:
                        <br/>{{ $medicoPrestacion->nombre }} {{ $medicoPrestacion->paterno }} {{ $medicoPrestacion->materno }} FIR {{ $medicoPrestacion->dni }} hard
                        <br/>Motivo: Soy el autor del documento
                        <br/>Fecha: {{ date("d/m/Y H:i:s", strtotime($solicitudPp->fecha_firma)) }}
                    </td>
                </tr>
            </table>
        </div>
    @endif
</body>
</html>
