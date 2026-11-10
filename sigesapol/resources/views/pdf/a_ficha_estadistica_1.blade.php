<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ficha estadistica de la Hospitalizacion N° {{ $id_hospitalizacion }}</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
        .container {
            width: 900px;
        }

        .border {
            border: 1px;
        }

        .border-secondary {
            border-color: black;
        }

        table {
            font-size: 11px;
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .fw-bold{
            font-weight: bold;
        }
        .bg-header{
            background-color: rgb(240, 240, 240);
        }
        .txt-align-left{
            text-align: left
        }
        .txt-align-right{
            text-align: right
        }
    </style>
</head>

<body style="font-size: 12px; font-family: arial;padding:-10px !important;background-color:white !important">
    <table style="margin:0px;padding:0px;padding-bottom:10px;">
        <tbody>
            <tr>
                <td colspan="2" class="" width="10%"><img src="images/logo_sanidad.png" width="40rem" />
                </td>
                <td colspan="2" class="" width="80%">
                    <span
                        style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;
                        color: red;">{{ $hospitalizacion->establecimiento }}</span>
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">SERVICIO
                        DE HOSPITALIZACION</span>
                </td>
                <td colspan="2" width="50%"></td>
            </tr>
        </tbody>
    </table>
    <table style="margin:0px;padding:0px;padding-bottom:-25px;">
        <tbody>
            <tr>
                <td colspan="2" width="50%">
                    <span style="margin: 0; font-weight: bold; font-size: 22px !important;">FICHA ESTADISTICA<br></span>
                    {{-- <span style="margin: 0; font-weight: bold; font-size: 9px !important">CUPS PNP
                        {{ $hospitalizacion->cups_pnp }}</span><br>
                    <span style="margin: 0; font-weight: bold; font-size: 8px !important">COD.SITEDS
                        {{ $hospitalizacion->cod_siteds_susalud }}</span> --}}
                </td>
            </tr>
        </tbody>
    </table>
    <table style="margin:0px;padding-top:30px;">
        <tbody>
            <tr>
                <td width="30%">
                    <table style="margin:0px;padding:0px;">
                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="fw-bold txt-align-left">
                                    INGRESO
                                </td>
                                <td class="txt-align-left">
                                    {{-- {{$hospitalizacion->fecha_ingreso}} --}}
                                </td>
                            </tr>
                            <tr>
                                <td class="txt-align-left">FECHA INGRESO</td>
                                <td>{{$hospitalizacion->fecha_ingreso}}</td>
                            </tr>
                            <tr>
                                <td class="txt-align-left">HORA INGRESO</td>
                                <td><?php echo date("H:i:s", strtotime($hospitalizacion->hora_ingreso)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width="50%">{{-- 
                    <table>
                        <tbody>
                            <tr>
                                <td class="fw-bold" style="text-align: left;">
                                    FECHA INGRESO
                                </td>
                                <td class="" style="text-align: left;">
                                    {{$hospitalizacion->fecha_ingreso}}
                                </td>
                            </tr>
                        </tbody>
                    </table> --}}
                </td>
                <td width="25%">
                    <table style="margin:0px;padding:0px;">
                        <tbody>
                            <tr>
                                <td width="40%" style="text-align:left;"><span
                                        style="margin: 0; font-size: 0.9rem; font-weight: bold;">H.C:</span></td>
                                <td width="60%" style="text-align:left;"><span
                                        style="margin: 0; font-size: 0.9rem; font-weight: bold;"><b>{{ $hospitalizacion->paciente_ndocumento }}</b></span>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%" style="text-align:left;"><span
                                        style="margin: 0; font-size: 0.9rem; font-weight: bold;">H.C.A:</td>
                                <td width="60%" style="text-align:left;"><span
                                        style="margin: 0; font-size: 0.9rem; font-weight: bold;"><b>{{ $hospitalizacion->paciente_nhistoria }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%" style="text-align:left;"><span
                                        style="margin: 0; font-size: 0.9rem; font-weight: bold;">N° ORDEN:</span></td>
                                <td width="60%" style="text-align:left;"> <b style="font-size: 0.9rem;">
                                        {{ str_pad($hospitalizacion->secuencia, 8, '0', STR_PAD_LEFT) }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table border="1" class="border border-secondary">
        <thead>
            <tr>
                <th class="fw-bold bg-header" colspan="3">DATOS DEL PACIENTE</th>
            </tr>
            <tr>
                <th>APELLIDO PATERNO</th>
                <th>APELLIDO MATERNO</th>
                <th>NOMBRES COMPLETOS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{$hospitalizacion->paciente_paterno}}
                </td>
                <td>
                    {{$hospitalizacion->paciente_materno}}
                </td>
                <td>
                    {{$hospitalizacion->paciente_nombre}}
                </td>
            </tr>
        </tbody>
    </table>
    <table border="1" class="border border-secondary">
        <thead>
            <tr>
                <th class="fw-bold bg-header" colspan="3">DATOS DEL TITULAR</th>
            </tr>
            <tr>
                <th>APELLIDO PATERNO</th>
                <th>APELLIDO MATERNO</th>
                <th>NOMBRES COMPLETOS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{$hospitalizacion->titular_paterno}}
                </td>
                <td>
                    {{$hospitalizacion->titular_materno}}
                </td>
                <td>
                    {{$hospitalizacion->titular_nombre}}
                </td>
            </tr>
        </tbody>
    </table>
    <table border="1" class="border border-secondary">
        <thead>
            <tr>
                <th class="bg-header" rowspan="2">ALTA</th>
                <td>FECHA DE ALTA MEDICA</td>
                <td>{{$hospitalizacion->fecha_alta_medica}}</td>
            </tr>
        </thead>
            <tr>
                <td>FECHA DE ALTA ADMINISTRATIVA</td>
                <td>{{$hospitalizacion->fecha_alta_administrativa}}</td>
            </tr>
            <tr>
                <td class="bg-header">DIAGNOSTICO PRINCIPAL</td>
                <td colspan="2">{{$diagnostico_inicial->nombre}}</td>
            </tr>
            <tr>
                <td class="bg-header">OTRAS AFECCIONES</td>
                <td colspan="2">-</td>
            </tr>
        </tbody>
    </table>
    @if(isset($hospitalizacion->causas_muerte) /* && $hospitalizacion->causas_muerte != '0'  */)
    <table border="1" class="border border-secondary">
        <thead>
            <tr>
                <td class="bg-header">DEFUNCION</td>
                <td>FECHA DE DEFUNCION</td>
                <td>20-20-20</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="bg-header"> I. CAUSA TERMINAL</td>
                <td colspan="2">--</td>
            </tr>
            <tr>
                <td class="bg-header">BEBIDA A</td>
                <td colspan="2">--</td>
            </tr>
            <tr>
                <td class="bg-header">BEBIDA B</td>
                <td colspan="2">--</td>
            </tr>
            <tr>
                <td class="bg-header"> II. Otras condiciones asociadas importantes pero no relacionadas con la indicada en (a)</td>
                <td colspan="2">--</td>
            </tr>
        </tbody>
    </table>
    @endif
</body>

</html>
