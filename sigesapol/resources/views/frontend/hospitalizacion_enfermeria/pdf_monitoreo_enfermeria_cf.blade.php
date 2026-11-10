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

<table style="margin:0px;padding:0px;padding-bottom:0px;margin-top: 70px;">
        <tbody>
            <tr>
                <td colspan="2" width="70%" style="vertical-align:bottom;padding-bottom:30px;">
                    <?php echo $url_descarga?>
                </td>
                <td colspan="2" width="30%" style="margin-top:0px;padding-top:0px">
                    <img src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
                </td>
            </tr>
        </tbody>
    </table>
    <table style="margin:0px;padding:0px;padding-bottom:-10px;">
        <tbody>
            <tr>
                <td colspan="2" class="" width="10%">
                <img src="images/logo_sanidad.png" width="40rem"/>
                </td>
            <td colspan="2" class="" width="80%">
                <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">{{$nombre_establecimiento}}</span>
                <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO DE EMERGENCIA</span>
                <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">{{$datos_atencion[0]->topico}}</span>
            </td>
            <td colspan="2" width="50%"> 
            </td> 
        </tr>
        </tbody>
    </table>

    <table style="margin-left:0px;padding-top: 30px;">
        <tbody>
            <tr> 
                <td>
                    <span style="margin: 0; font-weight: bold; font-size: 20px !important; padding-bottom: -20px !important;">
                        MONITOREO DE ENFERMERIA DE TURNO Y HORA
                    </span><br>
                    <span style="margin: 0; font-weight: bold; font-size: 15px !important;"> 
                        {{$id_monitoreo . ' - ' .$fecha_hora . ' - ' . $turno_monitoreo->descripcion}}
                    </span>
                </td> 
                <td>
                    <div  style="margin:0px;padding:0px;"> 
                        <table style="margin:0px;padding:0px;" >
                            <tbody>                                                  
                                <tr >
                                    <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C:</span></td>
                                    <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{$datos_atencion[0]->nro_historia}}</b></span></td>
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A:</td>
                                    <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{$datos_atencion[0]->dni_beneficiario}}</b></td>
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size:1.3rem; font-weight: bold;">N° EMERGENCIA:</span></td>
                                    <td width="70%" style="text-align:left;"> <b style="font-size: 1.3rem;"> {{str_pad($notaEnfermeria->id_ext_enfermeria, 8, '0', STR_PAD_LEFT)}}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>  
            </tr>
        </tbody>
    </table>

    <b style="font-size: 12px !important;">DATOS DEL PACIENTE</b>
    <table  style="margin:0px;padding-bottom:-13px;" >
        <tr>
            <td width="150%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                <b> {{$datos_atencion[0]->tipo_doc_ident_asegurado}} 
                    {{$datos_atencion[0]->dni_beneficiario}} 
                <?php echo utf8_decode(
                    ' - ' . $datos_atencion[0]->nombre_beneficiario . ' ' . 
                    $datos_atencion[0]->paterno_beneficiario . ' ' . 
                    $datos_atencion[0]->materno_beneficiario); ?></b>  
            </td>  
            <td width="20%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                SEXO: <b> {{$datos_atencion[0]->sexo}} </b> 
            </td>  
            <td width="80%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                EDAD: <b> {{$cabecera->asegurado_edad_anios . " AÑOS " . $cabecera->asegurado_edad_meses . " MESES Y " . $cabecera->asegurado_edad_dias . " DIAS"}} </b>
            </td>  
        </tr>
        <tr> 
            <td width="80%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                F.NACIMIENTO: <b>{{$datos_atencion[0]->fecha_nac_paciente}}</b> 
            </td>  
            <td width="100%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TIPO BENEF: <b> {{$datos_atencion[0]->tipo_beneficiario}} </b> 
            </td>  
            <td width="80%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TIPO DE SEGURO: <b>SALUDPOL</b> 
            </td> 
            {{-- <td width="80%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                N° DE SEGURO: <b></b> 
            </td> --}}   
        </tr>
        <tr> 
            <td width="40%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TALLA: <b>{{$datos_atencion[0]->talla_cm}} cm</b> 
            </td>  
            <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PESO: <b>{{$datos_atencion[0]->peso_kg}} kg</b> 
            </td>  
            <td width="79%" style="vertical-align:top;">
                <span style="display: block; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TIPO DE SANGRE: <b style=" font-size: 1.2rem !important;">{{$datos_atencion[0]->descripcion_tipo_sangres}}</b> 
            </td>   
        </tr>
    </table>
    <hr style="margin-top: 20px">
    <b style="font-size: 12px !important;">DATOS DE INGRESO</b>
    <table style="width:100%; margin: 0px;">
        <tr>
            <td style="text-align: left; width: 33%;">FECHA DE INTERNAMIENTO: <b>{{$cabecera->fecha_registro_ingreso}}</b></td>
            <td style="text-align: left; width: 33%;">NIVEL IPRESS: <b>{{$cabecera->nivel_ipress}}</b></td>
            <td style="text-align: left; width: 33%;">CODIGO IPRESS: <b>{{$cabecera->nivel_ipress}}</b></td>
        </tr>
        <tr>
            <td style="text-align: left; width: 33%;">SERVICIO: <b>{{$cabecera->servicio}}</b></td>
            <td style="text-align: left; width: 33%;">PABELLON: <b>{{$cabecera->pabellon}}</b></td>
            <td style="text-align: left; width: 33%;">PISO: <b>{{$cabecera->piso}}</b> / CUARTO: <b>{{$cabecera->cuarto}}</b> / NRO. CAMA: <b>{{$cabecera->cama}}</b></td>
        </tr>
    </table>
    <hr style="margin-top: 20px">
    <b style="font-size: 12px !important;">USUARIO RESPONSABLE</b>
    <table style="width:auto; margin: 0px;">
        <tr>
            <td style="width: 50%;">{{$cabecera->nombre_medico . ' ' . $cabecera->paterno_medico . ' ' . $cabecera->materno_medico}} - </td>
            <td style="width: 50%;">{{'DNI'}}: <b>{{$cabecera->dni_medico}}</b></td>
        </tr>
    </table>
    <hr style="margin-top: 20px">
    <b style="font-size: 12px !important;">DIAGNOSTICO DE ATENCION</b>
    <table>
        <thead>
            <tr>
                <th><b>N</b></th>
                <th><b>CODIGO</b></th>
                <th><b>DESCRIPCION</b></th>
                <th><b>TIPO</b></th>
                <th><b>CLASIFICACION</b></th>
            </tr>
        </thead>
        <tbody>
            @if(isset($diagnosticos)) 
                {{-- @php $index = 1; @endphp --}}
                @for ($i = 0; $i < count($diagnosticos); $i++)
                    <tr>
                        <td>{{$i + 1}}</td>
                        <td>{{$diagnosticos[$i]->codigo_diagnostico}}</td>
                        <td>{{$diagnosticos[$i]->descripcion_diagnostico}}</td>
                        <td>{{$diagnosticos[$i]->descripcion_tipo_diagnosticos}}</td>
                        <td>ATENCION</td>
                    </tr>
                    {{-- @php $index++; @endphp --}}
                @endfor
            @endif
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
        </tbody>
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
    

    FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b>  <br>
    HORA DE IMPRESIÓN: <b>{{$horaActual}}</b> <br>
    <b>CUALQUIER OMISION DE REGISTRO, INVALIDA LA PRESENTE MONITOREO POR DIA</b>
</body>

</html>