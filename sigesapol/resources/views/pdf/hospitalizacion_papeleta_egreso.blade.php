<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>
        PAPELETA DE EGRESO DE LA HOSPITALIZACION N° {{ $hospitalizacion->nro_hospitalizacion }}
    </title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
        .td_left {
            text-align: left !important;
        }

        .td_ancho_n {
            width: 4%;
            text-align: center
        }

        .page_break {
            page-break-before: always;
        }

        .cantidad {
            width: 11%;
        }

        .td_ancho_espacios {
            width: 5%;
        }

        .td_ancho_codigo {
            width: 10%;
        }

        .td_ancho_especialidad {
            width: 25%;
        }

        td {
            padding-left: 2px !important;
        }

        table {
            font-size: 11px;
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        .ancho_nro {
            width: 5%;
        }

        .ancho_producto {
            width: 45%;
        }

        .titulos {
            padding: 0px 10px;
        }

        h2 {
            font-size: 15px !important
        }

        .titulo_principal {
            width: 60%;
        }

        .logo {
            width: 15%;
        }

        .grado {
            width: 14%;
        }

        .dni {
            width: 8%;
        }

        .rectangulo {
            padding: 3px
        }
        .negrita{
            font-weight: bold;
        }
        footer {
            border: 0px;
            margin-left: 10px;
            font-size: 7px !important;
            font-family: Arial, Helvetica, sans-serif
        }

        /* agregado */
        #tbl_hospitalizacion td,
        #tbl_hospitalizacion_atencion td {
            text-align: left;
        }

        table#tbl_hospitalizacion_footer {
            margin-bottom: 0 !important;
        }

        #tbl_hospitalizacion_footer td {
            border: 1px black solid !important;
            font-size: 9px;
            padding-top: 1px;
            padding-bottom: 1px;
            text-align: center;
            margin-bottom: 0 !important;
        }
    </style>
</head>

<body style="font-size: 12px; font-family: arial;padding:-10px !important;background-color:white !important">
    <table style="margin:0px;padding:0px;padding-bottom:10px;">
        <tbody>
            <tr>
                <td colspan="2" width="50%"></td>
                <td colspan="2" class="" width="10%"><img src="images/logo_sanidad.png" width="40rem" /></td>
                <td colspan="2" class="" width="80%">
                    <span
                        style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">{{ $hospitalizacion->establecimiento }}</span>
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">SERVICIO
                        DE HOSPITALIZACIÓN - PAPELETA DE EGRESO</span>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="margin:0px;padding:0px;padding-bottom:-25px;">
        <tbody>
            <tr>
                <td colspan="2" width="50%">
                    <span style="margin: 0; font-size: 0.9rem; font-weight: bold; font-size: 17px !important;">PAPELETA DE EGRESO<br></span>
                    <span style="margin: 0; font-weight: bold; font-size: 9px !important">CUPS PNP
                        {{ $hospitalizacion->cups_pnp }}</span><br>
                    <span style="margin: 0; font-weight: bold; font-size: 8px !important">COD.SITEDS
                        {{ $hospitalizacion->cod_siteds_susalud }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="margin:0px;padding-top:30px;">
        <tbody>
            <tr>
                <td width="75%"></td>
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

    <table style="margin:0px;padding:-5px;">
        <tr>
            <td style="text-align:right !important;">
                <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 1.3rem; text-align:left;">
                    FECHA Y HORA INGRESO: <b>{{ $hospitalizacion->fecha_ingreso}}
                        {{ date('H:i:s', strtotime($hospitalizacion->hora_ingreso)) }}</b>
                </span>
            </td>
        </tr>
    </table>
    <br/>
    <table id="tbl_hospitalizacion">
        <tr>
            <th style="width:38%"></th>
            <th style="width:38%"></th>
            <th style="width:2%"></th>
            <th style="width:7%"></th>
            <th style="width:15%"></th>
        </tr>
        <tr>
            <td colspan="5" class="subtitulo_1"><b>DATOS DEL PACIENTE</b></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Apellidos y Nombres: <b>{{ $hospitalizacion->paciente_paterno }}
                    {{ $hospitalizacion->paciente_materno }} {{ $hospitalizacion->paciente_nombre }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @if ($titular != null)
        @if ($titular->dni_titular != $hospitalizacion->paciente_ndocumento)
            <tr>
                <td colspan="2">Apellidos y Nombres del titular: <b>{{ $titular->paterno }} {{ $titular->materno }}
                        {{ $titular->nombre }}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endif
        @endif
        <tr>
            <td colspan="2">Parentesco con el paciente: <b>{{ $hospitalizacion->paciente_parentesco }}</b></td>
            <td></td>
            <td><b>DNI:</b></td>
            <td>{{ $hospitalizacion->paciente_ndocumento }}</td>
        </tr>
        <tr>
            <td>Edad: <b>{{ $hospitalizacion->paciente_edad_ingreso }}</b></td>
            <td>Sexo: <b>{{ $hospitalizacion->paciente_sexo }}</b></td>
            <td></td>
            <td><b>CIP</b></td>
            <td>
                @if (isset($persona_datos_adicionales->cip))
                    {{ $persona_datos_adicionales->cip }}
                @endif
            </td>
        </tr>
        <tr>
            <td>Raza: <b>{{ $asegurado->etnia }}</b></td>
            <td>Estado civil: <b>{{ $asegurado->estado_civil }}</b></td>
            <td></td>
            <td>{{-- <b>HC</b> --}}</td>
            <td>{{-- {{$hospitalizacion->nro_historia}} --}}</td>
        </tr>
        <tr>
            <td>Grado de Instrucción: <b>{{ $asegurado->grado_instruccion }}</b></td>
            <td>Ocupación: <b>{{ $asegurado->ocupacion }}</b></td>
            <td></td>
            <td><b>RX N°</b></td>
            <td>-</td>
        </tr>
        <tr>
            <td>Lugar de Nacimiento: <b>{{ $asegurado->departamento_nacimiento2 }} /
                    {{ $asegurado->provincia_nacimiento2 }} / {{ $asegurado->distrito_nacimiento2 }} /
                    {{ strtoupper($asegurado->direccion_nacimiento) }}</b></td>
            <td>Fecha de nacimiento: <b>{{ $asegurado->fecha_nac }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Procedencia: <b>{{ $asegurado->departamento_procedencia2 }} / {{ $asegurado->provincia_procedencia2 }}
                    / {{ $asegurado->distrito_procedencia2 }} /
                    {{ strtoupper($asegurado->direccion_procedencia) }}</b></td>
            <td>Religión: <b>{{ $asegurado->religion }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Domicilio: <b>{{ strtoupper($asegurado->domicilio) }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Distrito: <b>{{ $asegurado->distrito }}</b></td>
            <td>Departamento: <b>{{ $asegurado->departamento }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Teléfono fijo: <b>{{ $asegurado->telefono }}</b></td>
            <td>Celular: <b>{{ $asegurado->telefono2 }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Fecha y hora de ingreso: <b>{{ $hospitalizacion->fecha_ingreso }}
                <?php echo date("H:i:s", strtotime($hospitalizacion->hora_ingreso)); ?> </b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Fecha y hora de Historia Clínica: <b>{{ $hospitalizacion->created_at }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Código SITEDS: <b>{{ $hospitalizacion->cod_siteds_susalud }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">CUPS: <b>{{ $hospitalizacion->cups_pnp }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Días de estancia: <b>{{$dias_estancia}} días</b>.
            </b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <hr>
    <table  style="margin:0px;padding:0px;" >
        <tr>
            <td colspan="5" class="subtitulo_1" style="text-align: left"><b>DATOS DEL EGRESO</b></td>
        </tr>
        <tr>
            <td>
                <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> 
                    MÉDICO: <b>{{$hospitalizacion->user_nombre_medico_alta}} {{$hospitalizacion->user_paterno_medico_alta }} {{$hospitalizacion->user_materno_medico_alta}}</b>
                </span>
            </td>
        </tr>
        <tr>
            <td width="41.3%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> 
                    PABELLON: <b>{{$hospitalizacion->pabellon}}</b>
                </span>
            </td>
            <td width="25%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: right"> 
                    PISO: <b>{{$hospitalizacion->piso}}</b>
                </span>
            </td>
            <td width="33.3%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: right"> 
                    HABITACION : <b>{{$hospitalizacion->cuarto}}</b>
            </span>
            </td>
            <td width="33.3%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: right"> 
                    CAMA/CAMILLA : <b>{{$hospitalizacion->cama}}</b>
                </span>
            </td>
        </tr>
    </table>
    <table  style="margin:0px;padding:0px;" >
        <tr>
            <td width="33.3%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> 
                    FECHA Y HORA EGRESO MÉDICO: <b>{{$hospitalizacion->fecha_alta_medica}} <?php echo date("H:i:s", strtotime($hospitalizacion->hora_alta_medica)); ?></b>
                </span>
            </td>
        </tr>
    </table>
    <div style="margin: 10px 0px 10px 0px;">
        <table  style="margin:0px;padding:0px;" >
            <tr>
                <td width="25%" style="vertical-align:top;">
                    <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> 
                        TIPO EGRESO: <b>{{$hospitalizacion->tipo_egreso}}</b>
                    </span>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5" style="font-size: 0.9rem; text-align: left;">CONDICION:</td>
            </tr>
            <tr>
                <td style="width: 25%">Curado
                    ( @if($hospitalizacion->id_condicion_alta == 2) 
                    <b>
                        <u>X</u> 
                    </b>
                    @endif )
                </td>
                <td style="width: 25%">Aliviado 
                    ( @if($hospitalizacion->id_condicion_alta == 1)
                    <b>
                        <u>X</u> 
                    </b>
                    @endif )
                </td>
                <td style="width: 25%">No tratado
                    ( @if($hospitalizacion->id_condicion_alta == 4)
                    <b>
                        <u>X</u> 
                    </b>
                    @endif )
                </td>
                <td style="width: 25%">Fallecido
                    ( @if($hospitalizacion->id_condicion_alta == 6)
                    <b>
                        <u>X</u> 
                    </b>
                    @endif )
                </td>
            </tr>
        </table>
    </div>
    <hr />
    <table  style="margin:0px;padding:0px;" >
        <tr>
            <td width="70%" style="vertical-align:top;">
                <span style="display: block; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">DIAGNOSTICOS DEL EGRESO</span>
            </td>
        </tr>
    </table>
    <table style="margin:8px 2px; padding:0px;" border="1">
            <tr>
                <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000; width: 10%">CODÍGO</th>
                <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000" colspan="2">NOMBRE</th>
                <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000; width: 15%">TIPO</th>
                <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000; width: 15%">CATEGORÍA</th>
            </tr>
            @if ($hospitalizacion->cie_egreso_codigo1 != null)
                <tr>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_codigo1}}</td>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;" colspan="2">{{$hospitalizacion->cie_egreso_nombre1}}</td>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">DEFINITIVO</td>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_categoria1}}</td>
                </tr>
            @endif
            @if ($hospitalizacion->cie_egreso_codigo2 != null)
                <tr>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_codigo2}}</td>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;" colspan="2">{{$hospitalizacion->cie_egreso_nombre2}}</td>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">DEFINITIVO</td>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_categoria2}}</td>
                </tr>
            @endif
            @if ($hospitalizacion->cie_egreso_codigo3 != null)
                <tr>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_codigo3}}</td>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;" colspan="2">{{$hospitalizacion->cie_egreso_nombre3}}</td>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">DEFINITIVO</td>
                    <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_categoria3}}</td>
                </tr>
            @endif
    </table>
    <table style="margin:0px; padding:0px;">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        @if(count($concurrencias) != 0)
            <tr>
                <td colspan="5" style="text-align: left;">Debe presentarse para su control en: 
                    <ul>
                        @foreach($concurrencias as $item)
                            <li class="negrita">{{$item->nombre}}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        @endif
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    {{-- IMPRESION --}}
    <table style="font-size: 7px !important;">   
        <tr>
            <td style="text-align: left;">USUARIO REGISTRO: <b>{{$hospitalizacion->user_paterno}} {{$hospitalizacion->user_materno}}, {{$hospitalizacion->user_nombre}}</b></td>  
            <td style="text-align: right;">FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b></td>
        </tr>
        <tr>
            <td style="text-align: left;">USUARIO DE IMPRESIÓN: <b>{{$user_actual->paterno}} {{$user_actual->materno}}, {{$user_actual->name}}</b></td>
            <td style="text-align: right;">HORA DE IMPRESIÓN: <b>{{$horaActual}}</b></td>
        </tr>
    </table>
</body>
</html>
