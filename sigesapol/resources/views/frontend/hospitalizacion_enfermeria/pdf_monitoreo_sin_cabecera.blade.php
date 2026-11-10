<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MONITOREO DE ENFERMERIA</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
        .td_left {
            text-align: left !important;
        } 

        td {
            padding-left: 2px !important;
        }

        table {
            font-size: 10px !important;
            font-family: Arial, Helvetica, sans-serif;
        } 

        .titulos {
            font-weight: bold;
        }

        h2 {
            font-size: 13px !important;
            font-family: Arial, Helvetica, sans-serif;
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
            padding: 2px
        }

        footer {
            text-align: left;
            border: 0px;
            margin-left: 10px;
        }
    </style>
    <style>
        .td_left {
            text-align: left !important;
        }

        .td_ancho_n {
            width: 4%; 
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

        .rectangulo {
            padding: 1.5px
        }

        footer {
            border: 0px;
            margin-left: 10px;
            font-size: 7px !important;
            font-family: Arial, Helvetica, sans-serif
        }
    </style>
    <style>
        table {
            border-collapse: collapse;
        }
        body {
            position: relative
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            #planoCartesiano,
            .imagenCapturada {
                visibility: visible;
                width: 100%;
                /* Ajusta el ancho de la imagen capturada para que llene la página al imprimir */
                height: 100%;
                /* Ajusta la altura de la imagen capturada para mantener la proporción */
            }
        }

        .content {
            width: 90%;
            height: 90%;
            margin: 10mm;
            margin-top: 15mm;
            margin-bottom: 10mm;
            box-sizing: border-box;
        }

        td {
            height: 15px; 
            font-size: 11.5px; 
        }

        th {
            font-size: 11.5px;
            text-align: left;
            /border: 1px black solid;/
            height: 15px;
            padding: 1px
        }

        td {
            border-top-width: 1px;
            border-bottom-width: 1px;
        }



        td.lineapuntos {
            border-bottom: 1px dotted black
        }

        .imagencelda {
            width: 140px;
            height: 150px;
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
            /* Ajusta la distancia desde el borde superior (cambia 10mm según necesites) */
            left: 10mm;
            /* Ajusta la distancia desde el borde izquierdo (cambia 10mm según necesites) */
            /* Ajusta el tamaño de la imagen (cambia width y height según necesites) */
            width: 40px;
            height: 50px;
        }

        .imagen-esquina-derecha {
            position: absolute;
            top: -3mm;
            /* Ajusta la distancia desde el borde superior (cambia 10mm según necesites) */
            right: 10mm;
            /* Ajusta la distancia desde el borde derecho (cambia 10mm según necesites) */
            /* Ajusta el tamaño de la imagen (cambia width y height según necesites) */
            width: 40px;
            height: 50px;
        }

        /*<?php for ($i = 1; $i < 7; $i++) {
            echo '<td>';
            echo $i;
            echo '</td>';
        } ?>*/
        #planoCartesiano {
            width: 162px;
            height: 180px;
            position: relative;
            background-image: url('/images/persona.jpeg');
            background-size: 162px 180px;
        }

        .coordenada {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: red;
            cursor: pointer;
        }
    </style>
</head>

<body style="font-family: arial;padding:-30px;font-family:Arial, Helvetica, sans-serif;">

    <table style="margin-left:0px;padding-top: 30px;">
        <tbody>
            <tr> 
                <td>
                    <span style="margin: 0; font-weight: bold; font-size: 16px !important; padding-bottom: -20px !important;">
                        MONITOREO POR TURNO Y HORA
                    </span><br>
                    <span style="margin: 0; font-weight: bold; font-size: 15px !important;"> 
                    {{$fecha_hora . ' - ' . $turno_monitoreo->descripcion}}
                    </span>
                </td> 
            </tr>
        </tbody>
    </table>
    <hr style="margin-top: 20px">
    <b style="font-size: 12px !important;">MONITOREO HEMODINAMICO</b>
    <table style="width:100%; margin: 0px;">
        <tr>
            <td style="text-align: left;">G. CONCIENCIA: <b>{{$conciencia_monitoreo->descripcion}}</b></td>
            <td style="text-align: left;">G. DEPENDENCIA: <b>{{$grado_dependencia->descripcion}}</b></td>
            <td style="text-align: left;">GLASGOW: <b>{{$glasgow->descripcion}}</b></td>
        </tr>
        <tr>
            <td style="text-align: left;">P. DERECHA: <b>{{$pupila_derecha->descripcion}}</b></td>
            <td style="text-align: left;">P. IZQUIERDA: <b>{{$pupila_izquierda->descripcion}}</b></td>
        </tr>
        <tr>
            <table>
                <thead>
                    <tr>
                        <th colspan="3"><b>ESCALA DE AGITACION / SEDACION DE RICHIMOND (RASS)</b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>CATEGORIA</b></td>
                        <td><b>DESCRIPCION</b></td>
                        <td><b>PUNTOS</b></td>
                    </tr>
                    <tr>
                        <td>{{$intrepretacionRass}}</td>
                        <td>{{$descripcionRass}}</td>
                        <td>{{$rass->descripcion}}</td>
                    </tr>
                </tbody>
            </table>
        </tr>
    </table>
    <hr style="margin-top: 20px">
    <b style="font-size: 12px !important;">SIGNOS VITALES</b>
    <table style="width:100%; margin: 0px;">
        <tr>
            <td style="text-align: left;">PRESION ARTERIAL(PA): <b>{{$pa_sisdolica->descripcion}}</b>/<b>{{$pa_diastolica->descripcion}}</b></td>
            <td style="text-align: left;">PRESION ARTERIAL MEDIA(PAM): <b>{{$pam->descripcion}}</b></td>
            <td style="text-align: left;">FRECUENCIA CARDIACA(/min): <b>{{$pulso_fc->descripcion}}</b></td>
        </tr>
        <tr>
            <td style="text-align: left;">FRECUENCIA RESPIRATORIA(/min): <b>{{$frecuencia_respiratoria->descripcion}}</b></td>
            <td style="text-align: left;">TEMPERATURA(T): <b>{{$temperatura->descripcion}}</b></td>
            <td style="text-align: left;">Sa02(%): <b>{{$sa02->descripcion}}</b></td>
            <td style="text-align: left;">Fi02(%): <b>{{$fi02->descripcion}}</b></td>
        </tr>
    </table>
    <hr style="margin-top: 20px">
    <b style="font-size: 12px !important;">MONITOREO RESPIRATORIO</b>
    <table style="width:100%; margin: 0px;">
        <tbody>
            @if ($id_tipo_atencion == 2)
                <tr>
                    <td style="text-align: left;">FIJACION DE TUBO (F/V): <b>{{$fijacion_nombre}}</b></td>
                    <td style="text-align: left;">VALOR: <b>{{$fijacion_der->descripcion . $fijacion_izq->descripcion}}</b></td>
                    <td style="text-align: left;">VOLUMEN TIDAL: <b>{{$volumen_tiral->descripcion}}</b></td>
                    <td style="text-align: left;">VOL / MIN: <b>{{$vol_min->descripcion}}</b></td>
                </tr>
                <tr>
                    <td style="text-align: left;">P. MAXIMA: <b>{{$p_maxima->descripcion}}</b></td>
                    <td style="text-align: left;">PRESION SOPORTE: <b>{{$presion_soporte->descripcion}}</b></td>
                    <td style="text-align: left;">P. LIMITE: <b>{{$p_limite->descripcion}}</b></td>
                    <td style="text-align: left;">CO2: <b>{{$co2->descripcion}}</b></td>
                    <td style="text-align: left;">P. PICCO: <b>{{$p_picco->descripcion}}</b></td>
                </tr>
                <tr>
                    <td style="text-align: left;">TIEMPO DE INSPIRACION / TIEMPO DE EXPIRACION (I:E): <b>{{$i_e->descripcion}}</b></td>
                    <td style="text-align: left;">PRESION POSITIVA AL FINAL DE LA EXPIRACION (PEEP): <b>{{$peed->descripcion}}</b></td>
                </tr>
                <tr>
                    <td style="text-align: left;">MODOS DE VENTILACION: <b>{{$vm_nombre}}</b></td>
                    <td style="text-align: left;">DESCRIPCIÓN: <b>{{$vmi->descripcion . $vmni->descripcion}}</b></td>
                </tr>
                <tr>
                    <td style="text-align: left;">MODALIDAD - ASISTIDO/CONTROLADO: <b>{{$modalidad->descripcion}}</b></td>
                    <td style="text-align: left;">FRECUENCIA RESPIRATORIA POR PERSONA  (FR(P)): <b>{{$frecuencia_m->descripcion}}</b> / <b>{{$frecuencia_p->descripcion}}</b></td>
                </tr>
            @elseif ($id_tipo_atencion == 3)
                <tr>
                    <td style="text-align: left;">RESPIRACION ESPONTANEA: <b>{{$respiracion_espontanea->descripcion === '1' ? 'SI' : 'NO'}}</b></td>
                    <td style="text-align: left;">PRESENCIA DE TIRAJE: <b>{{$tiraje->descripcion === '1' ? 'SI' : 'NO'}}</b></td>
                </tr>
                <tr>
                    <td style="text-align: left;">DISPOSITIVOS DE OXIGENOTERAPIA: <b>{{$dispositivo_oxigenoterapia->descripcion ==! '' ? $dispositivo_oxigenoterapia->descripcion : 'NO HAY PRESENCIA'}}</b></td>
                </tr>
            @endif
    </table>
    <hr style="margin-top: 20px">
    <b style="font-size: 12px !important;">INFUSIONES</b>
    <table style="width:auto; margin: 0px;">
        @if (isset($infusiones))
            @for ($i = 0; $i < count($infusiones); $i++)
                <tr>
                    <td style="text-align: left; color: red">{{$infusiones[$i]['nombre']}}: </td>
                    <td style="text-align: left; "><b>{{$infusiones[$i]['cantidad']}}</b></td>
                </tr>
            @endfor
        @endif
    </table>
    <hr style="margin-top: 20px">
    <b style="font-size: 12px !important;">ADICIONAL</b>
    <table style="width:auto;">
        <tbody style="width: 100%;">
            <tr style="width: auto;">
                <td style="width: auto; text-align: left; color: green">DIURESIS:<b style="color: black;">{{$diurisis_horaria->descripcion}}</b></td>
            </tr>
            <tr style="width: auto;">
                <td style="width: auto; text-align: left;">HEMOGLUCOTEST:<b style="color: black;">{{$hemoglucotest->descripcion}}</b></td>
            </tr>
        </tbody>
    </table>
</body>

</html>