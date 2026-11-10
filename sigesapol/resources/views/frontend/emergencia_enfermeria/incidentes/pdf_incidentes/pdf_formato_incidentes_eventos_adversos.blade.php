<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>INCIDENCIA DE EVENTOS ADVERSOS</title>
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

    <table style="margin:0px;padding:0px;padding-bottom:-10px;">
        <tbody>
          <tr>
            <td colspan="2" class="" width="10%">
              <img src="images/logo_sanidad.png" width="40rem"/>
            </td>
            <td colspan="2" class="" width="80%">
              <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">{{$nombre_establecimiento}}</span>
              <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO DE {{$data['tipo_atencion']}}</span>
            </td>
            <td colspan="2" width="50%"> 
            </td> 
          </tr>
        </tbody>
      </table>
    
      <table style="margin:0px;padding-bottom:-20px;">
        <tbody>
            <tr> 
                <td colspan="2" width="50%" >
                    <span style="margin: 0; font-weight: bold; font-size: 20px !important; padding-bottom: -20px !important;">
                       FORMATO DE INCIDENCIA Y EVENTOS ADVERSOS
                    </span><br><br><br>
                    <span style="margin: 0; font-weight: bold; font-size: 15px !important;"> 
                        {{str_pad($data['id_incidente'], 8, '0', STR_PAD_LEFT)}}
                    </span>
                </td> 
            </tr>
        </tbody>
      </table>
    
      <table style="margin:0px;padding:0px;padding-bottom:-5px;">
        <tbody>
            <tr>
                <td colspan="2" width="50%" style="vertical-align:bottom;"> 
                </td>
                <td colspan="2" width="20%" > 
                </td>
                <td colspan="2" width="30%" >
                    <div  style="margin:0px;padding:0px;"> 
                        <table style="margin:0px;padding:0px;" >
                            <tbody>                                                  
                              <tr >
                                  <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C:</span></td>
                                  <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{$data['hc']}}</b></span></td>
                              </tr>
                              <tr>
                                  <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A:</td>
                                  <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{$data['hca']}}</b></td>
                              </tr>
                              <tr>
                                  <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size:1.3rem; font-weight: bold;">N° HOSPIT:</span></td>
                                  <td width="70%" style="text-align:left;"> <b style="font-size: 1.3rem;"></b>{{$data['cups_pnp']}}</td>
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
                <b> {{$data['paciente_tipo_doc']}} 
                    {{$data['dni_beneficiario']}} 

                    {{$data['paciente']}}
                </b>  
            </td>  
            <td width="20%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                SEXO: <b>{{$data['sexo']}}</b> 
            </td>  
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                EDAD: <b>{{$data['edad']}}</b>
            </td>  
        </tr>
      </table>

      <table  style="margin:0px;padding-bottom:-6px;" >
        <tr> 
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                F.NACIMIENTO: <b>{{$data['fecha_nac']}}</b> 
            </td>  
            <td width="100%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TIPO BENEF: <b>{{$data['tipo_beneficiario']}}</b> 
            </td>  
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TIPO DE SEGURO: <b> {{$data['tipo_seguro']}}</b> 
            </td> 
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                N° DE SEGURO: <b>{{$data['tipo_seguro_numero']}}</b> 
            </td>   
        </tr>
      </table>

      <table  style="margin:0px;padding-bottom:6px;" >
        <tr> 
            <td width="40%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TALLA: <b>{{$data['talla']}} cm</b> 
            </td>  
            <td width="50%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PESO: <b>{{$data['peso']}} kg</b> 
            </td>  
            <td width="79%" style="vertical-align:top;">
              <span style="display: block; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TIPO DE SANGRE: <b style=" font-size: 1.2rem !important;">{{$data['tipo_sangre']}}</b> 
            </td>   
        </tr>
      </table>
      
      <table style="margin:0px;padding-top:-10px;padding-bottom:6px;" >
        <tr> 
            <td width="190%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                RESPONSABLE:  
                <b>
                    {{$data['tipo_doc_ident_titular']}} {{$data['dni_titular']}} 
                    <b style="color: white">-</b>
                    {{$data['nombre_titular']}}  
                </b>  
                <b style="color: white"> - </b>
                    
                @if($data['contacto_titular'])
                CONTACTO: <b> {{$data['contacto_titular']}} </b>  
                @endif
            </td>     
        </tr>
    </table>
    <table style="margin:0px;padding-top:-10px;padding-bottom:4px;" >
        <tr> 
            <td width="190%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                ACOMPAÑADO POR: 
                @if($data['descripcion_acompaniado_por'] != null)
                    <b>{{$data['descripcion_acompaniado_por']}} ({{$data['acompaniado_por']}})</b> 
                @else
                    <b>-</b>
                @endif
            </td>     
        </tr>
      </table>
      <hr>


    <b style="font-size: 12px !important;">DATOS DE INGRESO</b>
   
    <table  style="margin:0px;padding-bottom:-10px;padding-top:-3px;" >
        <tr>
            <td width="60%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                FECHA INTERNAMIENTO: <b> {{ $data['fecha_ingreso'] }} ({{ $data['hora_ingreso']}}) </b> 
            </td>  
            <td width="30%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                NIVEL IPRESS: <b> {{ $data['nivel_ipress']}}</b> 
            </td>  
            <td width="50%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                CÓDIGO IPRESS: <b>{{$data['codigo_ipress']}}</b> 
            </td>   
        </tr>
    </table>
    <table style="margin:0px;padding-top:-3px;padding-bottom:4px;" >
        <tr> 
            <td width="86%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                SERVICIO: <b>{{$data['nombre_servicio']}}</b> 
            </td>  
            <td width="55%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                {{-- PABELLON: <b> {{$datos_atencion[0]->pabellon}} </b>  --}}
            </td>  
            <td width="60%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PISO: <b> {{$data['piso']}}</b> / CUARTO: <b> {{$data['cuarto']}}</b> / N° CAMA: <b> {{$data['cama']}}</b> 
            </td>    
        </tr>
    </table>
    <hr>
    <b style="font-size: 12px !important;">NOTIFICADOR</b>
    <table  style="margin:0px;padding-bottom:-4px;padding-top:-3px;" >
        <tr>
            <td width="60%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;"> 
                   <b>PERSONAL QUE NOTIFICA:</b> {{$data['notificador_tipo_personal']}} <br>  
            </td>  
            <td width="40%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                  <b>TIPO: </b> {{$data['notificador_tipo_registro']}} <br>  
            </td>  
            <td width="40%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                <b>FECHA DE SUCESO:</b>  {{$data['notificador_fecha_evento']}} <br>  
            </td> 
            <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                  <b>HORA:</b>  {{$data['notificador_hora_evento']}} <br>  
              </td>
        </tr>
    </table>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%"> 
                <b>OBSERVACIONES: </b> <br>
                {{$data['notificador_observaciones']}}
            </td>
        </tr>
    </table> 
    <hr>

    <b style="font-size: 12px !important;">RESPONSABLE</b>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%"><b style="font-size: 12px !important;">PERSONAL CONVOCADO:</b></td>
        </tr>
        <?php $contador = 1; ?> 
        @foreach ($data['convocados'] as $pc)
            <tr style="padding: 0px;margin: 0px; text-align: left !important;"> 
                <td width="90%" style="padding: 0px;margin: 0px;"> 
                    <b>{{ $contador }}.</b> {{ $pc['nombre'] }} 
                </td>
            </tr>  
            <?php $contador++; ?>  
        @endforeach
    </table>  
    <b style="font-size: 12px !important;">CATEGORIA DE E. ADVERSO</b>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%">
                {{$data['categoria_eadverso']}}
            </td>
        </tr>
    </table>
    <b style="font-size: 12px !important;">TIPO DE E. ADVERSOS</b>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%">
                {{$data['tipo_eadverso']}}
            </td>
        </tr>
    </table> 

    <b style="font-size: 12px !important;">ANALISIS DE CAUSAS</b>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%">
                {{$data['analisis_causas']}}
            </td>
        </tr>
    </table>  
    <hr>
    <b style="font-size: 12px !important;">PLAN MEJORA</b>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%"><b>ACCIONES CORRECTIVAS:</b></td>
        </tr>
        <?php $contador = 1; ?> 
        @foreach ($data['correctivas'] as $ac)
            <tr style="padding: 0px;margin: 0px; text-align: left !important;"> 
                <td width="300%" style="padding: 0px;margin: 0px;"> 
                  
                    <b>{{ $contador }}.</b>
                    <?php
                    $textoLargo = $ac['acciones_correctivas'];                            
                    $longitud = strlen($textoLargo);
                    $resultado = '';
                    for ($i = 0; $i < $longitud; $i += 80) {
                        $resultado .= substr($textoLargo, $i, 80) . "\n";
                    }
                    echo $resultado;
                    ?> 
                </td>
                <td width="90%" style="padding: 0px;margin: 0px;"> 
                    <b>Implementado : </b> @if($ac['implementado']=='on')  SI  @else   NO   @endif 
                </td>
            </tr>  
            <?php $contador++; ?>  
        @endforeach
        <tr style=" text-align: left !important;">
            <td width="90%"></td>
        </tr>
        <tr style=" text-align: left !important;">
            <td width="90%"><b>ACCIONES PREVENTIVAS:</b></td>
        </tr>
        <?php $contador = 1; ?> 
        @foreach ($data['preventivas'] as $ap)
            <tr style="padding: 0px;margin: 0px; text-align: left !important;"> 
                <td width="90%" style="padding: 0px;margin: 0px;"> 
                    <b>{{ $contador }}.</b> 
                    <?php
                    $textoLargo2 = $ap['acciones_preventivas'];                            
                    $longitud2 = strlen($textoLargo2);
                    $resultado2 = '';
                    for ($i2 = 0; $i2 < $longitud2; $i2 += 80) {
                        $resultado2 .= substr($textoLargo2, $i2, 80) . "\n";
                    }
                    echo $resultado2;
                    ?> 
                </td>
                <td width="90%" style="padding: 0px;margin: 0px;"> 
                    <b>Implementado : </b> @if($ap['implementado']=='on')  SI  @else   NO   @endif 
                </td>
            </tr>  
            <?php $contador++; ?>  
        @endforeach
    </table> 

   
    <hr>
    <b style="font-size: 12px !important;">VERIFICACIÓN DE LA EFECTIVIDAD DEL PLAN DE MEJORA:</b>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%"> 
                {{$data['verificacion_plan_mejora']}}<br>
            </td>
        </tr>
    </table> 
    
    <b style="font-size: 12px !important;">FECHA DE VERIFICACIÓN:</b>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%"> 
                {{$data['fecha_verificacion']}}
            </td>
        </tr>
    </table>  

    <footer style=" color: black; height: 25px;">
        <table > 
            <tr style="font-size: 6px !important;">
                <td style="text-align: left">
                    <b style=" font-size: 6px !important;font-weight: 200;">PACIENTE: </b> <b
                        style="font-size: 8px !important;">{{$data['dni_beneficiario']}} - 
                        {{ $data['paciente'] }}</b>
                    <br>

                    <i><b>CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE EVOLUCIÓN DE ENFERMERÍA</b></i>
                </td>
                <td style="text-align: right; padding-right: 10px;">
                    USUARIO RESPOSABLE: <b>{{ $data['nombre_usuario'] }}
                        {{ $data['paterno_usuario'] }}
                        {{ $data['materno_usuario'] }}</b> <br>
                    FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b>  <br>
                    HORA DE IMPRESIÓN: <b>{{$horaActual}}</b> 
                </td>
            </tr>
        </table>
    </footer>
  
</body>

</html>
FECHA DE IMPRESIÓN: <b></b>  <br>
HORA DE IMPRESIÓN: <b></b> 