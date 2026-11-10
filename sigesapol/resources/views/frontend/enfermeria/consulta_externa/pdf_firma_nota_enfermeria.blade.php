<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NUEVA NOTA DE ENFERMERIA</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
        .td_left {
            text-align: left !important
        }

        td {
            padding-left: 2px !important
        }

        table {
            font-size: 10px !important;
            font-family: Arial, Helvetica, sans-serif
        }

        .titulos {
            font-weight: 700
        }

        h2 {
            font-size: 13px !important;
            font-family: Arial, Helvetica, sans-serif
        }

        .titulo_principal {
            width: 60%
        }

        .logo {
            width: 15%
        }

        .grado {
            width: 14%
        }

        .dni {
            width: 8%
        }

        .rectangulo {
            padding: 2px
        }

        footer {
            text-align: left;
            border: 0;
            margin-left: 10px
        }
    </style>
    <style>
        .td_left {
            text-align: left !important
        }

        .td_ancho_n {
            width: 4%
        }

        .cantidad {
            width: 11%
        }

        .td_ancho_espacios {
            width: 5%
        }

        .td_ancho_codigo {
            width: 10%
        }

        .td_ancho_especialidad {
            width: 25%
        }

        td {
            padding-left: 2px !important
        }

        table {
            font-size: 11px;
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif
        }

        .ancho_nro {
            width: 5%
        }

        .ancho_producto {
            width: 45%
        }

        .titulos {
            padding: 0 10px
        }

        h2 {
            font-size: 15px !important
        }

        .titulo_principal {
            width: 60%
        }

        .logo {
            width: 15%
        }

        .rectangulo {
            padding: 1.5px
        }

        footer {
            border: 0;
            margin-left: 10px;
            font-size: 7px !important;
            font-family: Arial, Helvetica, sans-serif
        }
    </style>
    <style>
        table {
            border-collapse: collapse
        }

        body {
            position: relative
        }

        @media print {
            @page {
                size: A4;
                margin: 0
            }

            #planoCartesiano,
            .imagenCapturada {
                visibility: visible;
                width: 100%;
                height: 100%
            }
        }

        .content {
            width: 90%;
            height: 90%;
            margin: 10mm;
            margin-top: 15mm;
            margin-bottom: 10mm;
            box-sizing: border-box
        }

        td {
            height: 15px;
            font-size: 11.5px
        }

        th {
            font-size: 11.5px;
            text-align: left;/border: 1px #000 solid;/ height: 15px;
            padding: 1px
        }

        td {
            border-top-width: 1px;
            border-bottom-width: 1px
        }

        td.lineapuntos {
            border-bottom: 1px dotted #000
        }

        .imagencelda {
            width: 140px;
            height: 150px
        }

        .imagencelda2 {
            position: relative;
            padding-bottom: 3px;
            padding-top: 5px;
            width: 220px;
            height: 60px;
            left: 8mm
        }

        .imagen-esquina {
            position: absolute;
            top: -3mm;
            left: 10mm;
            width: 40px;
            height: 50px
        }

        .imagen-esquina-derecha {
            position: absolute;
            top: -3mm;
            right: 10mm;
            width: 40px;
            height: 50px
        }

        #planoCartesiano {
            width: 162px;
            height: 180px;
            position: relative;
            background-image: url(/images/persona.jpeg);
            background-size: 162px 180px
        }

        .coordenada {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: red;
            cursor: pointer
        }
    </style>
</head>

<body style="font-family: arial;padding:-30px;font-family:Arial, Helvetica, sans-serif;">
    {{-- <table style="margin:0px;padding:0px;padding-bottom:0px;">
        <tbody>
            <tr>
                <td colspan="2" width="70%" style="vertical-align:bottom;padding-bottom:30px">{{ $url_descarga }}
                </td>
                <td colspan="2" width="30%" style="margin-top:0px;padding-top:0px"><img
                        src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}"></td>
            </tr>
        </tbody>
    </table> --}}
    <table style="margin:0px;padding:0px;padding-bottom:-10px;">
        <tbody>
            <tr>
                <td colspan="2" class="" width="10%"><img src="images/logo_sanidad.png" width="40rem" />
                </td>
                <td colspan="2" class="" width="80%"><span
                        style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">{{ $enfermeria->establecimiento }}</span><span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO
                        DE ENFERMERIA</span></td>
                <td colspan="2" width="50%"></td>
            </tr>
        </tbody>
    </table>
    <table >
        <tbody>
            <tr>
                <td>
                    <span style="margin: 0; font-weight: bold; font-size: 20px !important; padding-bottom: -20px !important;">NOTA DE ENFERMERIA </span><br>
                    <span style="margin: 0; font-weight: bold; font-size: 15px !important;">{{ str_pad($enfermeria->enfermeria_id, 8, '0', STR_PAD_LEFT) }}</span>
                </td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                <td><b>CUPS PNP {{$enfermeria->cups_pnp}}</b></td>
            </tr>
            <tr>
                <td><b>COD.SITEDS {{$enfermeria->codigo_siteds}}</b></td>
            </tr>
        </tbody>
    </table>
    <table style="margin:0px;padding:0px;padding-bottom:-5px;">
        <tbody>
            <tr>
                <td colspan="2" width="50%" style="vertical-align:bottom;"></td>
                <td colspan="2" width="20%"></td>
                <td colspan="2" width="30%">
                    <div style="margin:0px;padding:0px;">
                        <table style="margin:0px;padding:0px;">
                            <tbody>
                                <tr>
                                    <td width="50%" style="text-align:left;">
                                        <spanstyle="margin: 0; font-size: 1rem; font-weight: bold;">H.C:</span>
                                    </td>
                                    <td width="70%" style="text-align:left;">
                                        <spanstyle="margin: 0; font-size: 1rem; font-weight: bold;">
                                            <b>{{ $enfermeria->nro_historia }}</b></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align:left;"><span
                                            style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A:</td>
                                    <td width="70%" style="text-align:left;"><span
                                            style="margin: 0; font-size: 1rem; font-weight: bold;"><b>{{ $enfermeria->nro_historia }}</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
    </table><b style="font-size: 12px !important;">DATOS DEL PACIENTE</b>
    <table style="margin:0px;padding-bottom:-13px;">
        <tr>
            <td width="150%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;"><b>{{ $enfermeria->asegurado_tipo_documento_identidad }} {{ $enfermeria->asegurado_nro_doc_ident }} - {{ $enfermeria->asegurado_nombre . ' ' . $enfermeria->asegurado_paterno . ' ' . $enfermeria->asegurado_materno }}
            </td>
            <td width="20%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">SEXO:<b>{{ $enfermeria->asegurado_sexo }}</b>
            </td>
            <td width="80%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">EDAD:<b>{{ $enfermeria->asegurado_anios }}AÑOS
                        CON{{ $enfermeria->asegurado_meses }}MESES Y{{ $enfermeria->asegurado_dias }}DÍAS </b></td>
        </tr>
    </table><br>
    <table style="margin:0px;padding-bottom:-6px;">
        <tr>
            <td width="80%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">F.NACIMIENTO:<b>{{ $enfermeria->asegurado_fecha_nacimiento }}</b>
            </td>
            <td width="100%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">TIPO
                    BENEF:<b>{{ $enfermeria->asegurado_tipo_beneficiario }}</b></td>
            <td width="80%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">TIPO
                    DE SEGURO:SALUDPOL </td>
            <td width="80%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">N°
                    DE SEGURO:457899 </td>
        </tr>
    </table>
    <table style="margin:0px;padding-bottom:6px;">
        <tr>
            <td width="40%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">TALLA:<b>{{ $signosVitales[2]->descripcion }}cm</b>
            </td>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">PESO:<b>{{ $signosVitales[4]->descripcion }}kg</b>
            </td>
            <td width="79%" style="vertical-align:top;"><span
                    style="display: block; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">TIPO
                    DE SANGRE:<b
                        style=" font-size: 1.2rem !important;">{{ $enfermeria->tipo_sangre_descripcion_corta }}</b>
            </td>
        </tr>
    </table><b style="font-size: 12px !important;">DATOS DE INGRESO</b>
    <table style="margin:0px;padding-bottom:-10px;padding-top:-3px;">
        <tr>
            <td width="60%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">FECHA
                    DE
                    ATENCIÓN:<b>{{ $enfermeria->fecha_atencion_enfermeria_formatter }} {{ $enfermeria->hora_atencion_enfermeria_formatter }}</b>
            </td>
            <td width="30%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">NIVEL
                    IPRESS:<b>{{ $enfermeria->establecimiento_nivel }}</b></td>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">CÓDIGO
                    IPRESS:<b>{{ $enfermeria->establecimiento_codigo }}</b></td>
        </tr>
    </table><br>
    <table style="margin:0px;padding-top:-3px;padding-bottom:4px;">
        <tr>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">SERVICIO:{{ $enfermeria->consultorio }}</b>
            </td>
        </tr>
    </table>
    <hr><b style="font-size: 12px !important;">RESPONSABLE</b>
    <table style="margin:0px;padding-bottom:-4px;padding-top:-3px;"><br>
      <tr>
          <td width="50%" style="vertical-align:top;"><span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            DNI: <b>{{ $enfermeria->enfermero_nro_doc_ident }}</b>
          </td>
      </tr>
      <tr>
        <td width="50%" style="vertical-align:top;"><span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
          NOMBRES: <b>{{ $enfermeria->enfemero_nombre }}</b>
        </td>
        <td width="50%" style="vertical-align:top;"><span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
          APELLIDO PATERNO: <b>{{ $enfermeria->enfermero_paterno }}</b>
        </td>
        <td width="50%" style="vertical-align:top;"><span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
          APELLIDO MATERNO: <b>{{ $enfermeria->enfermero_materno }}</b>
        </td>
      </tr>
      <tr>
        <td width="50%" style="vertical-align:top;"><span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
          ESPECIALIDAD: <b>{{ $enfermeria->enfemero_especialidad }}</b>
        </td>
        <td width="50%" style="vertical-align:top;"><span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
          PROFESION: <b>{{ $enfermeria->enfemero_profesion }}</b>
        </td>
        <td width="50%" style="vertical-align:top;"><span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
          N° COLEGIATURA: <b>{{ $enfermeria->enfemero_colegiatura }}</b>
        </td>
      </tr>
    </table>
    <hr><b style="font-size: 12px !important;">FUNCIONES VITALES</b>
    <table style="margin:0px;padding-bottom:-4px;padding-top:-3px;"><br>
        <tr>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">PA:<b>{{ $signosVitales[0]->descripcion }}/{{ $signosVitales[1]->descripcion }}mmHg
                    </b></td>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">FC:<b>{{ $signosVitales[3]->descripcion }}/min</b>
            </td>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">FR:<b>{{ $signosVitales[5]->descripcion }}/min</b>
            </td>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">Fi02:<b>{{ $signosVitales[6]->descripcion }}%</b>
            </td>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">Sa02:<b>{{ $signosVitales[7]->descripcion }}%</b>
            </td>
        </tr>
        <tr>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">T°:<b>{{ $signosVitales[8]->descripcion }}°C</b>
            </td>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">TALLA:<b>{{ $signosVitales[2]->descripcion }}cm</b>
            </td>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">PESO:<b>{{ $signosVitales[4]->descripcion }}KG</b>
            </td>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">IMC:<b>{{ $signosVitales[4]->descripcion }}</b>
            </td>
            <td width="50%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">PESO
                    IDEAL:<b>{{ $signosVitales[10]->descripcion }}Kg</b></td>
        </tr><br>
    </table>
    <hr><b style="font-size: 12px !important;">ANTECEDENTES</b>
    <table style="margin: 0; padding: 0;">
        <tr>
            <td width="40%" style="vertical-align: top;"><span
                    style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">HIPERTENSION
                    ARTERIAL:<b>{{ intval($enfermeria->id_hipertension) === 1 ? 'SI' : 'NO' }}</b></span></td>
            <td width="60%" style="vertical-align: top;"><span
                    style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                    @if (intval($enfermeria->id_hipertension) === 1)
                        DESCRIPCIÓN HIPERTENSION ARTERIAL:
                        <b>{{ $enfermeria->descripcion_hipertension }}</b>
                    @endif
                </span></td>
        </tr>
    </table>
    <table style="margin: 0; padding: 0;">
        <tr>
            <td width="40%" style="vertical-align: top;"><span
                    style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">DIABETES_MELLITUS:<b>{{ intval($enfermeria->id_diabetes_millitus) === 1 ? 'SI' : 'NO' }}</b></b></span>
            </td>
            <td width="60%" style="vertical-align: top;"><span
                    style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                    @if (intval($enfermeria->id_diabetes_millitus) === 1)
                        DESCRIPCIÓN DIABETES MELLITUS:
                        <b>{{ $enfermeria->descripcion_diabetes_millitus }}</b>
                    @endif
                </span></td>
        </tr>
    </table>
    <table style="margin: 0; padding: 0;">
        <tr>
            <td width="40%" style="vertical-align: top;"><span
                    style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">ALERGIA:<b>{{ intval($enfermeria->id_alergia) === 1 ? 'SI' : 'NO' }}</b></span>
            </td>
            <td width="60%" style="vertical-align: top;"><span
                    style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                    @if (intval($enfermeria->id_alergia) === 1)
                        DESCRIPCIÓN ALERGIA:
                        <b>{{ $enfermeria->descripcion_alergia }}</b>
                    @endif
                </span></td>
        </tr>
    </table>
    <table style="margin: 0; padding: 0;">
        <tr>
            <td width="40%" style="vertical-align: top;"><span
                    style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">HEMODIALISIS:<b>{{ intval($enfermeria->id_hemodialisis) === 1 ? 'SI' : 'NO' }}</b></span>
            </td>
            <td width="60%" style="vertical-align: top;"><span
                    style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                    @if (intval($enfermeria->id_hemodialisis) === 1)
                        DESCRIPCIÓN HEMODIALISIS:
                        <b>{{ $enfermeria->descripcion_hemodialisis }}</b>
                        @endif
                </span></td>
        </tr>
    </table>
    <table style="margin:10px 0px;padding-top:-5px;">
        <tr>
            <td width="100%" style="vertical-align:top;"><span
                    style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">CARDIOLÓGICO:
                    @if (isset($enfermeria->cardio_1_descripcion))
                        <b>{{ $enfermeria->cardio_1_descripcion }}</b>
                        @endif @if (isset($enfermeria->cardio_2_descripcion))
                            <b>-{{ $enfermeria->cardio_2_descripcion }}</b>
                            @endif @if (isset($enfermeria->cardio_3_descripcion))
                                <b>-{{ $enfermeria->cardio_3_descripcion }}</b>
                                @endif @if (isset($enfermeria->cardio_4_descripcion))
                                    <b>-{{ $enfermeria->cardio_4_descripcion }}</b>
                                @endif
            </td>
        </tr>
    </table>
    @if ($enfermeria->descripcion_observacion)
        <table style="margin:0px;padding-top:-5px;">
            <tr>
                <td width="100%" style="vertical-align:top;"><span
                        style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">OBSERVACIONES:<b>{{ $enfermeria->descripcion_observacion }}</b>
                </td>
            </tr>
        </table>
        @endif @if ($enfermeria->descripcion_otro)
            <table style="margin:0px;padding-top:-5px;">
                <tr>
                    <td width="100%" style="vertical-align:top;"><span
                            style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">OTROS
                            ANTECEDENTES:<b>{{ $enfermeria->descripcion_otro }}</b></td>
                </tr>
            </table>
        @endif
        <hr><b style="font-size: 12px !important;">CPMS/CPT</b>
        <table style="margin:0px; text-align: left !important; padding-top: 7px;">
            <tr>
                <th width="4%"><b>CÓDIGO</b></th>
                <th width="70%"><b>DIAGNÓSTICO</b></th>
                <th width="8%"><b>TIPO</b></th>
            </tr>
            @foreach ($diagnosticos as $diagnostico)
                <tr style="padding: 0px;margin: 0px;">
                    <td width="8%" style="padding: 0px;margin: 0px;">{{ $diagnostico->codigo_diagnostico }}</td>
                    <td width="80%" style="padding: 0px;margin: 0px;">{{ $diagnostico->nombre_diagnostico }}</td>
                    <td width="12%" style="padding: 0px;margin: 0px;">{{ $diagnostico->tipo_diagnostico }}</td>
                </tr>
            @endforeach
        </table>
        <table style="margin:0px; text-align: left !important; padding-top: 7px;">
            <tr>
                <td width="5%"><b>CÓDIGO</b></td>
                <td width="45%"><b>PROCEDIMIENTO</b></td>
                <td width="5%"><b>CANTIDAD</b></td>
                <td width="45%"><b>DIAGNOSTICOS</b></td>
            </tr>
            @foreach ($procedimientos as $procedimiento)
                <tr style="padding: 0px;margin: 0px;">
                    <td width="5%" style="padding: 0px;margin: 0px;">{{ $procedimiento->procedimiento_codigo }}
                    </td>
                    <td width="45%" style="padding: 0px;margin: 0px;">
                        {{ $procedimiento->procedimiento_descripcion }}</td>
                    <td width="5%" style="padding: 0px;margin: 0px;">
                        {{ $procedimiento->prestacion_procedimiento_cantidad }}</td>
                    <td width="45%" style="padding: 0px;margin: 0px;">{{ $procedimiento->diagnostico_nombre }}
                    </td>
                </tr>
            @endforeach
        </table>
        <hr><b style="font-size: 12px !important;">OBSERVACIONES</b>
        <table style="margin:0px;padding-bottom:-6px;padding-top:-2px;">
            <tr>
                <td width="100%" style="vertical-align:top;"><span
                        style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">{{ $enfermeria->observacion_enfermeria }}
                </td>
            </tr>
        </table>
        <footer style=" color: black; height: 25px;">
            <table>
                <tr style="font-size: 6px !important;">
                    <td style="text-align: left"><b
                            style=" font-size: 6px !important;font-weight: 200;">PACIENTE:</b><b
                            style="font-size: 8px !important;">{{ $enfermeria->asegurado_nro_doc_ident }}-{{ $enfermeria->asegurado_paterno . ' ' . $enfermeria->asegurado_materno . ' ' . $enfermeria->asegurado_nombre }}</b><br><i><b>CUALQUIER
                                OMISIÓN DE REGISTRO,INVALIDA LA PRESENTE NOTA DE ENFERMERÍA</b></i></td>
                    <td style="text-align: right; padding-right: 10px;">USUARIO
                        RESPOSABLE:<b>{{ $usuario->name }}{{ $usuario->paterno }}{{ $usuario->materno }}</b><br>FECHA
                        DE IMPRESIÓN:<b>{{ $fechaActual }}</b><br>HORA DE IMPRESIÓN:<b>{{ $horaActual }}</b></td>
                </tr>
            </table>
        </footer>
</body>

</html>FECHA DE IMPRESIÓN:<b>{{ $fechaActual }}</b><br>HORA DE IMPRESIÓN:<b>{{ $horaActual }}</b>
