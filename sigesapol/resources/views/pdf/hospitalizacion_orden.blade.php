<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>PDF INDICACION DE HOSPITALIZACION</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        @page {
            margin: 0cm 0cm;
            font-family: Arial;
        }

        body {
            margin: 1cm 1.5cm 3cm 1.5cm !important;
            color: #001028;
            background: #FFFFFF; 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            font-family: Arial;
            /* border: 1px solid red; */
        }
        
        table {
            width: 100% !important;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px !important;
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        #tbl_header {
            text-align: center;
        }

        footer {               
            position: fixed;
            bottom: 3cm;
            left: 1.5cm;
            right: 1.5cm;
            width: 100%;
            text-align: center;
            overflow: auto;
        }
        
        #tbl_hospitalizacion_footer td {
            border: 1px black solid !important;
            font-size: 9px;
            padding-top: 1px;
            padding-bottom: 1px;
            text-align: center;
            margin-bottom: 0 !important;
        }

        table td {
            padding: 5px;
        }

        /**
        Para los subtitulos que tengan Numero Romanos
         */
        .subtitulo_1 {
            font-size: 14px; 
            padding-bottom:10px;
        }

        /**
        Para los subtitulos que tengan Numero enteros
         */
        .subtitulo_2 {
            font-size: 13px; 
            padding-bottom:1px;
        }
    </style>
</head>

<body >

    <table border="0" id="tbl_header">
        <tr>
            <td style="width:50px;">
                <img src="images/logo_sanidad.png" alt="" width="50px;"/>
            </td>
            <td style="text-align:left"><p>{{ $orden->establecimiento }}</p></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 18px;"><b>INDICACIÓN DE HOSPITALIZACIÓN</b></td>
        </tr>
    </table>
    
    <table border="0">
        <tr>
            <td style="width:13px"><b>I.-</b></td>
            <td><b>Paciente:</b> {{ $orden->paterno }} {{ $orden->materno }}, {{ $orden->nombre }}</td>
            <td style="width:140px"><b>DNI:</b> {{ $orden->nro_doc_ident }}</td>
            <td style="width:210px"><b>Fecha de Internamiento:</b> {{ $orden->fecha_derivacion }}</td>
        </tr>
        <tr>
            <td></td>
            <td><b>Parentesco:</b> {{ $orden->tipo_beneficiario }}</td>
            <td><b>HC. General:</b> {{ $orden->nro_historia }}</td>
            <td><b>CIP:</b> {{ $orden->cip }}</td>
        </tr>
    </table>
    <hr>
    <table border="0">
        <tr>
            <td style="width:13px"><b>II.-</b></td>
            <td><b>SERVICIO DE TRASLADO:</b> {{ $orden->codigo_upss_traslado }} - {{ $orden->descripcion_upss_traslado }}</td>
        </tr>
        <tr>
            <td></td>
            <td><b>SERVICIO A CARGO:</b> {{ $orden->codigo_upss_responsable }} - {{ $orden->descripcion_upss_responsable }}</td>
        </tr>
    </table>
    <hr>
    <table>
        <tr>
            <td style="width:13px"><b>III.-</b></td>
            <td><b>DIAGNOSTICO PRE-OPERATORIO:</b></td>
        </tr>
        @foreach ($diagnosticos as $d)
        <tr>
            <td></td>
            <td>{{ $d->codigo }} - {{ $d->nombre }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>