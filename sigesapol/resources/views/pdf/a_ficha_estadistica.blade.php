<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ficha estadistica de la Hospitalizacion N° {{ $hospitalizacion->nro_hospitalizacion }}</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
        table {
            font-size: 12px;
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .container {
            width: 100%;
        }

        .bg-header{
            background-color: rgb(225, 225, 225);
        }
        .bg-sub-header{
            background-color: rgb(172, 172, 172);
        }
        .bg-sub-header2{
            background-color: rgb(199, 199, 199);
        }
        .bg-seleccionado{
            background-color: rgb(99, 99, 99);
        }
        .border {
            border: black  1px solid; 
        }

        .border-secondary {
            border-color: black;
        }

        .fw-bold{
            font-weight: bold;
        }
        .txt-align-left{
            text-align: left;
        }
        .txt-align-right{
            text-align: right;
        }
        .txt-align-center{
            text-align: center;
        }
        .box-wrapper{
            border: black  1px solid; 
            margin: 15px 0px;
        }
        .box-pd-wrapper{
            padding: 5px 5px -5px 5px;
        }
    </style>
</head>

<body style="font-size: 12px; font-family: arial;padding:-30px !important;background-color:white !important" class="container">
    <table style="margin:0px;padding:0px;padding-bottom:10px;">
        <tbody>
            <tr>
                <td colspan="2" class="txt-align-left" width="5%"><img src="images/logo_sanidad.png" width="40rem" />
                </td>
                <td colspan="2" class="txt-align-left" width="95%">
                    <span
                        style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;
                        color: red;">{{ $hospitalizacion->establecimiento }}</span>
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">SERVICIO
                        DE HOSPITALIZACION</span>
                </td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                {{-- PRIMERA COLUMNA --}}
                <td style="width: 28%;"> 
                    <div class="box-wrapper box-pd-wrapper">
                        <table >
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left">Nro Historia</td>
                                    <td class="txt-align-right" rowspan="2">{{$hospitalizacion->paciente_nhistoria}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold txt-align-left">CLINICA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-wrapper box-pd-wrapper">
                        <table >
                            <tbody>
                                <tr>
                                    <td class="txt-align-left fw-bold" colspan="4">INGRESO</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-left">HORA</td>
                                    <td class="txt-align-left"><?php echo date("g:i A", strtotime($hospitalizacion->hora_ingreso)); ?></td>
                                    <td class="txt-align-left">FECHA</td>
                                    <td class="txt-align-left">{{$hospitalizacion->fecha_ingreso}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left">{{$hospitalizacion->pabellon}} - {{$hospitalizacion->cuarto}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold txt-align-left">SALA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr style="border: black 1px 0px 0px 0px solid">
                                    <td class="txt-align-left">{{$hospitalizacion->cama}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold txt-align-left">CAMA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="margin: 20px 0px 0px 0px">
                        <table>
                            <tbody>
                                <tr style="border: black 1px 0px 0px 0px solid">
                                    <td class="fw-bold txt-align-left" colspan="4">TRANSFERENCIA</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-left">Hora</td>
                                    <td class="txt-align-left">-----</td>
                                    <td class="txt-align-left">Fecha</td>
                                    <td class="txt-align-left">------</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left">SALA</td>
                                    <td class="txt-align-left">----</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left">CAMA</td>
                                    <td class="txt-align-left">----</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- INCAPACIDAD POR ENFERMEDAD --}}
                    <div class="border box-pd-wrapper" style="margin: 20px 0px 0px 0px">
                        <table>
                            <tbody>
                                <tr style="border: black 1px 0px 0px 0px solid">
                                    <td class="fw-bold txt-align-left" colspan="4">INCAPACIDAD POR ENFERMEDAD</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left">N° de Hospitalizacion</td>
                                    <td class="txt-align-right">{{$hospitalizacion->nro_hospitalizacion}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left">N° de días en el hospital</td>
                                    {{-- <td class="txt-align-right">{{$data_estancia->dia_estancia}}</td> --}}
                                    <td class="txt-align-right">{{$dias_en_hospital}} días</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left">Descanso absoluto</td>
                                    <td class="txt-align-right">----</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-wrapper border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td> 
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center">Firma del paciente</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
                {{-- SEPARACION --}}
                <td style="width: 1%"></td>
                {{-- SEGUNDA COLUMNA --}}
                <td style="40%">
                    <div>
                        <table style="margin:0px;padding:0px 0px 10px 0px;">
                            <tbody>
                                <tr>
                                    <td colspan="2" width="50%">
                                        <span style="margin: 0px 0px 40px 0px; font-weight: bold; font-size: 22px !important;">FICHA ESTADISTICA<br></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="50%">
                                        <span style="margin: 0px 0px 40px 0px; font-size: 12px !important;">UPSS ALTA: {{$upss_alta->codigo}} </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-wrapper" style="padding: 0px 0px -20px 0px;margin: 15px 0px 20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td>{{$hospitalizacion->paciente_paterno}}</td>
                                    <td>{{$hospitalizacion->paciente_materno}}</td>
                                    <td>{{$hospitalizacion->paciente_nombre}}</td>
                                </tr>
                                <tr class="bg-header">
                                    <td>AP. Paterno</td>
                                    <td>AP. Materno</td>
                                    <td>Nombres</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-wrapper" style="padding: 0px 0px -20px 0px; margin: 25px 0px 20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold bg-header" rowspan="2">Familia de </td>
                                    <td>{{$hospitalizacion->titular_paterno}}</td>
                                    <td>{{$hospitalizacion->titular_materno}}</td>
                                    <td>{{$hospitalizacion->titular_nombre}}</td>
                                </tr>
                                <tr class="bg-header"> 
                                    <td>AP. Paterno</td>
                                    <td>AP. Materno</td>
                                    <td>Nombres</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border" style="padding: 0px 5px -20px 0px; margin: 25px 0px 0px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold bg-header" rowspan="2"> ALTA</td>
                                    <td> Fecha de Alta médica</td>
                                    <td class="txt-align-right" style="width: 35%">{{$hospitalizacion->fecha_alta_medica}}</td>
                                </tr>
                                <tr>
                                    <td>Fecha de Alta administrativa</td>
                                    <td class="txt-align-right">{{$hospitalizacion->fecha_alta_administrativa}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper margin: 20px 0px 0px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left" style="width: 30%"> Diagnositco Principal</td>
                                    <td style="width: 70%">{{$hospitalizacion->cie_egreso_codigo1}} - {{$hospitalizacion->cie_egreso_nombre1}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left bg-header" style="padding: 10px 0px 10px 20px;">Otras afecciones</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left">No menciona</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="margin: 20px 0px 0px 0px">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left" style="width: 25%"> DEFUNCION</td>
                                    <td style="width: 50%">HORA - DIA - MES - AÑO</td>
                                    <td class="txt-align-right" style="width: 25%">
                                        @if(isset($hospitalizacion->fecha_defuncion))
                                            A las {{$hospitalizacion->hora_defuncion}} el 
                                            {{$hospitalizacion->fecha_defuncion}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td> &nbsp;</td>
                                    <td> &nbsp;</td>
                                    <td> &nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-left">CAUSA TERMINAL</td>
                                    <td class="txt-align-right" colspan="2">
                                        @if($hospitalizacion->causas_muerte)
                                            {{$hospitalizacion->causas_muerte}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left">Bebida A (b)</td>
                                    <td class="txt-align-right">-----------------</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left" style="width: 50%">Bebida B (c)</td>
                                    <td class="txt-align-right" style="width: 50%">-----------------</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-left" style="width: 50%">&nbsp;</td>
                                    <td class="txt-align-right" style="width: 50%">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-left" rowspan="2" style="width: 55%">Otras condiciones asociadas importantes pero no relacionadas con la indicada en (a)</td>
                                    <td class="txt-align-right" rowspan="2" style="width: 45%">-------------</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
                {{-- SEPARACION --}}
                <td style="width: 1%"></td>
                {{-- TERCERA COLUMNA --}}
                <td style="30%">
                    <div class="box-wrapper box-pd-wrapper">
                        <table >
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left">Nro de Carnet de Familia</td>
                                    <td class="txt-align-right" rowspan="2"><?php echo isset($hospitalizacion->asegurado_cip)?$hospitalizacion->asegurado_cip: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold txt-align-left">o de personal</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold txt-align-left">DNI del paciente: </td>
                                    <td class="txt-align-right">{{$hospitalizacion->asegurado_ndocumento}} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-wrapper box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-center" style="width: 50%;">CLASE</td>
                                    <td class="fw-bold txt-align-center" style="width: 50%">UNIDAD</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center" style="width: 50%">--------------</td>
                                    <td class="txt-align-center" style="width: 50%">--------------</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-center">Procedencia de Unidad</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center"><?php echo isset($hospitalizacion->unidad_pnptitular)?$hospitalizacion->unidad_pnptitular: '-'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-center">Años de servicio</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center">------------------------------------------------------------------</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-wrapper box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left">Tarifa</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center">{{$hospitalizacion->tarifa}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="margin: 20px 0px 0px 0px">
                        <table>
                            <tbody>
                                <tr style="border: black 1px 0px 0px 0px solid">
                                    <td class="fw-bold txt-align-center">FECHA DE NACIMIENTO</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center">{{$hospitalizacion->fecha_nacimiento_beneficiario}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left" style="width: 80%">Edad</td>
                                    <td class="txt-align-left" style="width: 20%">{{$hospitalizacion->paciente_edad_ingreso}} años</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 5px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left">SEXO</td>
                                    <td class="txt-align-center @if($hospitalizacion->paciente_sexo == 'M') bg-seleccionado @else bg-header @endif" style="padding: 10px 0px">M</td>
                                    <td class="txt-align-center @if($hospitalizacion->paciente_sexo == 'F') bg-seleccionado @else bg-header @endif">F</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left" style="width: 80%">N° de Hijos</td>
                                    <td class="txt-align-left" style="width: 20%">No menciona</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left" style="width: 75%">Estado civil</td>
                                    <td class="txt-align-left" style="width: 25%"><?php echo isset($busquedaReniec->estado_civil)?$busquedaReniec->estado_civil: '-'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left">Distrito</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center"><?php echo isset($busquedaReniec->distrito_nacimiento)?$busquedaReniec->distrito_nacimiento: '-'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    {{-- SEGUNDA HOJA --}}
    <table>
        <tbody>
            <tr>
                {{-- PRIMERA COLUMNA --}}
                <td style="width: 24%">
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left">RAZA</td>
                                    <td class="txt-align-right"><?php echo isset($asegurado->raza)?$asegurado->raza: 'No menciona'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left">Categoria o parentezco</td>
                                    <td class="txt-align-right">{{$asegurado->tipo_beneficiario}}</td>
                                </tr>
                                <tr>
                                    <td>&nbsp; </td>
                                    <td>&nbsp; </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left">Pronóstico</td>
                                    <td class="txt-align-right">
                                        @if($hospitalizacion->egreso_pronostico)
                                            {{$hospitalizacion->egreso_pronostico}}
                                        @else
                                            --
                                        @endif
                                        </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left" style="width: 80%">
                                        Por el médico <u>
                                            {{$hospitalizacion->user_nombre}}
                                            {{$hospitalizacion->user_paterno}} 
                                            {{$hospitalizacion->user_materno}}</u>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- INICIO-ESCALAS ELECTRO --}}
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left bg-header" style="padding: 10px 5px 10px 5px;">Electro</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-center {{-- bg-seleccionado --}}" style="padding: 10px 5px">1. Bueno</td>
                                    <td class="txt-align-center" style="padding: 10px 5px">2. Dudoso</td>
                                    <td class="txt-align-center" style="padding: 10px 5px">3. Malo</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- FIN-ESCALAS ELECTRO --}}
                    {{-- INICIO-ESCALAS EMERGENCIA --}}
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left bg-header" style="padding: 10px 5px 10px 5px;">Emergencia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-center" style="padding: 10px 5px">1. Bueno</td>
                                    <td class="txt-align-center{{--  bg-seleccionado --}}" style="padding: 10px 5px">2. Dudoso</td>
                                    <td class="txt-align-center" style="padding: 10px 5px">3. Malo</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- FIN-ESCALAS EMERGENCIA --}}
                    {{-- INICIO-ESCALAS PALEATIVO --}}
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left bg-header" style="padding: 10px 5px 10px 5px;">Paleativo</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-center" style="padding: 10px 5px">1. Bueno</td>
                                    <td class="txt-align-center" style="padding: 10px 5px">2. Dudoso</td>
                                    <td class="txt-align-center{{--  bg-seleccionad --}}o" style="padding: 10px 5px">3. Malo</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- FIN-ESCALAS PALEATIVO --}}
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left bg-header" style="padding: 10px 5px 10px 5px;">Infección</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-center" style="padding: 10px 5px 10px 5px;">-------------------------------------------</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left" style="padding: 10px 5px 10px 5px;">en el Hospital</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center" style="padding: 10px 5px 10px 5px;">{{-- {{ $hospitalizacion->establecimiento }} --}}--------------------------------------------------</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="fw-bold txt-align-left bg-header" style="padding: 10px 5px 10px 5px;">No infectado</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-center" style="padding: 10px 5px 10px 5px;">Médico de Admisión</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center" style="padding: 10px 5px 10px 5px;">
                                        {{$hospitalizacion->medico_admision_nombre}}
                                        {{$hospitalizacion->medico_admision_paterno}}
                                        {{$hospitalizacion->medico_admision_materno}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
                {{-- SEPARACION --}}
                <td style="width: 1%"></td>
                {{-- SEGUNDA COLUMNA --}}
                <td style="width: 75%;">
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-center" style="padding: 10px 5px 10px 5px; width:65%">DIAGNÓSTICO PROVISIONAL</td>
                                    <td class="fw-bold txt-align-center bg-sub-header" style="padding: 10px 5px 10px 5px; width:44%;">RESULTADOS PARA </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-center" style="padding: 10px 5px; width:65%;" rowspan="3" colspan="2">{{$diagnostico_inicial->nombre}}</td>
                                    <td class="txt-align-center @if($hospitalizacion->id_condicion_alta == '2') bg-seleccionado @else bg-header @endif" style="padding: 10px 5px; width:22%;">Curado</td>
                                    <td class="txt-align-center  @if($hospitalizacion->id_condicion_alta == '1') bg-seleccionado @else bg-header @endif" style="padding: 10px 5px; width:22%;">Mejorado</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center {{-- @if($hospitalizacion->id_condicion_alta == '4') bg-seleccionado @else --}} bg-header {{-- @endif --}}" style="padding: 10px 5px;">No mejorado</td>
                                    <td class="txt-align-center {{-- @if($hospitalizacion->id_condicion_alta == '5') bg-seleccionado @else --}} bg-header {{-- @endif --}}" style="padding: 10px 5px;">No tratado</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center @if($hospitalizacion->id_condicion_alta == '4') bg-seleccionado @else bg-header @endif" style="padding: 10px 5px;">Alta voluntaria</td>
                                    <td class="txt-align-center @if($hospitalizacion->id_condicion_alta == '5') bg-seleccionado @else bg-header @endif" style="padding: 10px 5px;">Fugado</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left" style="padding: 10px 5px 10px 5px; width:35%;">Solo casos Obstétricos</td>
                                    <td class="txt-align-center" style="padding: 10px 5px; width:30%;">&nbsp;</td>
                                    <td class="txt-align-left bg-sub-header" style="padding: 10px 5px; width:22%;">Solo para diagnósticos</td>
                                    <td class="txt-align-center bg-sub-header" style="padding: 10px 5px; width:22%;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold txt-align-left" style="padding: 5px 5px 5px 5px;">PADRE</td>
                                    <td class="txt-align-left" style="padding: 5px 5px 10px 5px;">&nbsp;</td>
                                    <td class="txt-align-center  @if($hospitalizacion->id_condicion_alta == 6) bg-seleccionado @else bg-header @endif" style="padding: 10px 5px;" rowspan="4">MUERTO</td>
                                    <td class="txt-align-center  @if($dias_en_hospital == 1) bg-seleccionado @else bg-header @endif"  style="padding: 10px 5px;" rowspan="2">Dentro de las 24 horas</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 5px;">Edad</td>
                                    <td class="txt-align-left" style="padding: px 5px 5px 5px;">---------------</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 5px;">Raza</td>
                                    <td class="txt-align-left" style="padding: px 5px 5px 5px;">---------------</td>
                                    <td class="txt-align-center @if($dias_en_hospital == 2) bg-seleccionado @else bg-header @endif" style="padding: 10px 5px;" rowspan="2">Dentro de las 48 horas</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 5px;">Ocupación</td>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 5px;">---------------</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold txt-align-left" style="padding: 5px 5px 5px 5px;">MADRE</td>
                                    <td class="txt-align-left" style="padding: 5px 5px 10px 5px;">---------------</td>
                                    <td class="txt-align-center bg-header" style="padding: 10px 5px;" colspan="2">Autopsia</td>
                                </tr>
                                <tr>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 5px;">Recién nacido véase Ficha N°</td>
                                    <td class="txt-align-left" style="padding: 5px 5px 10px 5px;">...........................................................</td>
                                    <td class="txt-align-center bg-header" style="padding: 10px 5px;" colspan="2">Nacido muerto</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border box-pd-wrapper" style="padding: 0px 0px -20px 0px;">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 5px;">Resumen historia clinica (Médico Tratante)</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold txt-align-left" style="padding: 5px 5px 5px 5px;">El paciente llegó con los siguientes sintomas principales:</td>
                                </tr>
                                {{-- inicio-iterar sintomas --}}
                                <tr>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 5px;">
                                        @if(isset($hospitalizacion_atenciones->sintomas_signos_principal))
                                            {{$hospitalizacion_atenciones->sintomas_signos_principal}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                {{--  fin-iterar sintomas--}}
                                <tr>
                                    <td class="fw-bold txt-align-left" style="padding: 5px 5px 5px 5px;">El examen fisico demostró los siguientes signos positivos:</td>
                                </tr>
                                {{-- inicio-iterar signos positivos --}}
                                <tr>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 5px;">{{$hospitalizacion->egreso_examen_clinico}}</td>
                                </tr>
                                {{--  fin-iterar signos positivos--}}
                                <tr>
                                    <td class="fw-bold txt-align-left" style="padding: 5px 5px 5px 5px;">Los examenes de laboratorio auxiliares y de laboratorio demostraron:</td>
                                </tr>
                                {{-- inicio-iterar signos positivos --}}
                                <tr>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 5px;">{{$hospitalizacion->egreso_examen_auxiliar}}</td>
                                </tr>
                                {{--  fin-iterar signos positivos--}}
                                <tr>
                                    <td class="fw-bold txt-align-left" style="padding: 5px 5px 0px 5px;">Otra información que el médico considere importante</td>
                                </tr>
                                {{-- inicio-iterar signos positivos --}}
                                <tr>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 20px;">
                                        - {{$hospitalizacion->egreso_procedimiento}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="txt-align-left" style="padding: 5px 5px 5px 20px;">
                                        - {{$hospitalizacion->egreso_complicacion}}
                                    </td>
                                </tr>
                                {{--  fin-iterar signos positivos--}}
                            </tbody>
                        </table>
                    </div>
                    <div class="box-wrapper border box-pd-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td> 
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="txt-align-center">Firma del médico tratante</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
