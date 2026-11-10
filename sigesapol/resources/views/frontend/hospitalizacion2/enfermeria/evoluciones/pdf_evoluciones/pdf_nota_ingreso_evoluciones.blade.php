<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>NUEVA NOTA DE ENFERMERIA</title>
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
              <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO DE CUIDADOS INTENSIVOS</span>
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
                        EVOLUCIÓN DE ENFERMERÍA
                    </span><br>
                    <span style="margin: 0; font-weight: bold; font-size: 15px !important;"> 
                        {{str_pad($nota_enfermeria[0]->id, 8, '0', STR_PAD_LEFT)}}
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
                                  <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{$datos_atencion[0]->nro_historia}}</b></span></td>
                              </tr>
                              <tr>
                                  <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A:</td>
                                  <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{$datos_atencion[0]->dni_beneficiario}}</b></td>
                              </tr>
                              <tr>
                                  <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size:1.3rem; font-weight: bold;">N° HOSPIT:</span></td>
                                  <td width="70%" style="text-align:left;"> <b style="font-size: 1.3rem;"> {{str_pad($datos_atencion[0]->nro_hospitalizacion, 8, '0', STR_PAD_LEFT)}}</b></td>
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
                     $datos_atencion[0]->nombre_beneficiario . ' ' . 
                     $datos_atencion[0]->paterno_beneficiario . ' ' . 
                     $datos_atencion[0]->materno_beneficiario); ?></b>  
            </td>  
            <td width="20%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                SEXO: <b> {{$datos_atencion[0]->sexo}} </b> 
            </td>  
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                EDAD: <b> {{$datos_nota_ingreso[0]->fec_nacim_descrip}} </b>
            </td>  
        </tr>
      </table>

      <table  style="margin:0px;padding-bottom:-6px;" >
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
                TIPO DE SEGURO: <b> {{$datos_atencion[0]->descripcion_tipo_seguro}} </b> 
            </td> 
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                N° DE SEGURO: <b> {{$datos_atencion[0]->tipo_seguro_numero}} </b> 
            </td>   
        </tr>
      </table>

      <table  style="margin:0px;padding-bottom:6px;" >
        <tr> 
            <td width="40%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TALLA: <b>{{$datos_nota_ingreso[0]->talla}} cm</b> 
            </td>  
            <td width="50%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PESO: <b>{{$datos_nota_ingreso[0]->peso}} kg</b> 
            </td>  
            <td width="79%" style="vertical-align:top;">
              <span style="display: block; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TIPO DE SANGRE: <b style=" font-size: 1.2rem !important;"> {{$datos_atencion[0]->descripcion_tipo_sangres}} </b> 
            </td>   
        </tr>
      </table>
 
      @if($datos_atencion[0]->numero_documento_responsable)
        <table style="margin:0px;padding-top:-10px;padding-bottom:6px;" >
            <tr> 
                <td width="190%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    RESPONSABLE:  
                    <b>
                        {{$datos_atencion[0]->tipo_documento_responsable}} {{$datos_atencion[0]->numero_documento_responsable}} 
                        <b style="color: white">-</b>
                        {{$datos_atencion[0]->nombre_responsable}}  
                    </b>  
                    <b style="color: white"> - </b>
                        
                    @if($datos_atencion[0]->telefono_responsable)
                    CONTACTO: <b> {{$datos_atencion[0]->telefono_responsable}} </b>  
                    @endif
                </td>     
            </tr>
        </table>
      @endif 
      
      <table style="margin:0px;padding-top:-10px;padding-bottom:4px;" >
        <tr> 
            <td width="190%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                ACOMPAÑADO POR: 
                @if($datos_nota_ingreso[0]->descripcion_acompaniado_por != null)
                    <b>{{$datos_nota_ingreso[0]->descripcion_acompaniado_por}} ({{$datos_nota_ingreso[0]->acompaniado_por}})</b> 
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
                  FECHA INTERNAMIENTO: <b> {{ $datos_atencion[0]->fecha_ingreso }} ({{ $datos_atencion[0]->hora_ingreso }}) </b> 
              </td>  
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                  NIVEL IPRESS: <b>{{$datos_atencion[0]->nivel_ipress}}</b> 
              </td>  
              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                  CÓDIGO IPRESS: <b>{{$datos_atencion[0]->codigoipress}}</b> 
              </td>   
          </tr>
    </table>


    <table style="margin:0px;padding-top:-3px;padding-bottom:4px;" >
      <tr> 
          <td width="86%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                SERVICIO: <b>{{$datos_atencion[0]->servicio}}</b> 
          </td>  
          <td width="55%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PABELLON: <b> {{$datos_atencion[0]->pabellon}} </b> 
          </td>  
          <td width="60%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PISO: <b> {{$datos_atencion[0]->piso}}</b> / CUARTO: <b> {{$datos_atencion[0]->cuarto}}</b> / N° CAMA: <b> {{$datos_atencion[0]->cama}}</b> 
          </td>    
      </tr>
    </table>
    <hr>

  
    <b style="font-size: 12px !important;">VALORACIÓN DE ENFERMERÍA</b>
    <table  style="margin:0px;padding-bottom:-4px;padding-top:-3px;" >
        <tr>
            <td width="60%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;"> 
                  <b>SUBJETIVO: </b> <br> 
                  {{ $nota_enfermeria[0]->descripcion_subjetivo }}
            </td>  
        </tr>
        <tr>
            <td width="60%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                <b>OBJETIVO: </b> <br>  
                {{ $nota_enfermeria[0]->descripcion_objetivo }}
            </td>  
        </tr>
    </table>
    <hr>




    <b style="font-size: 12px !important;">ATENCIÓN DE ENFERMERÍA</b>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%"><b>DIAGNÓTICO DE ENFERMERÍA</b></td>
            <td width="10%"><b>VALORACIÓN</b></td> 
        </tr>
        <?php $contador = 1; ?> 
        @foreach ($nota_enfermeria_diagnostico as $nota_diagnostico)
            <tr style="padding: 0px;margin: 0px; text-align: left !important;"> 
                <td width="90%" style="padding: 0px;margin: 0px;"> 
                    <b>{{ $contador }}.</b> {{ $nota_diagnostico->descrip_abrev }} 
                </td>
                <td width="10%" style="padding: 0px;margin: 0px; text-align: center !important;"> 
                    <b>{{ $nota_diagnostico->valor_de_seleccion }}</b>
                </td> 
            </tr>  
            <?php $contador++; ?>  
        @endforeach
    </table>   
    
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%"><b>EJECUCIÓN</b></td>
            <td width="10%"><b>VALORACIÓN</b></td> 
        </tr>
        <?php $contador = 1; ?> 
        @foreach ($nota_enfermeria_intervenciones as $intervenciones)
            <tr style="padding: 0px;margin: 0px; text-align: left !important;"> 
                <td width="90%" style="padding: 0px;margin: 0px;"> 
                    <b>{{ $contador }}.</b> {{ $intervenciones->descrip_abrev }} 
                </td>
                <td width="10%" style="padding: 0px;margin: 0px; text-align: center !important;"> 
                    <b>{{ $intervenciones->valor_de_seleccion }}</b>
                </td> 
            </tr>
            <?php $contador++; ?> 
        @endforeach
    </table>  

    <table style="margin:0px; padding-top: 0px; " > 
        <tr>
            <td width="60%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                <b>DETALLE DE INTERVENCIONES: </b> <br> 
                {{ $nota_enfermeria[0]->descripcion_intervenciones }}
            </td>  
        </tr> 
    </table> 
    
    <table style="margin:0px; padding-top: 7px;padding-bottom: 3px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%"><b>PLANEACIÓN</b></td>
            <td width="10%"><b>VALORACIÓN</b></td> 
        </tr>
        <?php $contador = 1; ?> 
        @foreach ($nota_enfermeria_plan_cuidado as $plan_cuidado)
            <tr style="padding: 0px;margin: 0px; text-align: left !important;"> 
                <td width="90%" style="padding: 0px;margin: 0px;"> 
                    <b>{{ $contador }}.</b> {{ $plan_cuidado->descrip_abrev }} 
                </td>
                <td width="10%" style="padding: 0px;margin: 0px; text-align: center !important;"> 
                    <b>{{ $plan_cuidado->valor_de_seleccion }}</b>
                </td> 
            </tr>
            <?php $contador++; ?>  
        @endforeach
    </table>   

    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%"><b>EVALUACIÓN</b></td>
            <td width="10%"><b>VALORACIÓN</b></td> 
        </tr>
        <?php $contador = 1; ?> 
        @foreach ($nota_enfermeria_evaluaciones as $evaluaciones)
            <tr style="padding: 0px;margin: 0px; text-align: left !important;"> 
                <td width="90%" style="padding: 0px;margin: 0px;"> 
                    <b>{{ $contador }}.</b> {{ $evaluaciones->descrip_abrev }} 
                </td>
                <td width="10%" style="padding: 0px;margin: 0px; text-align: center !important;"> 
                    <b>{{ $evaluaciones->valor_de_seleccion }}</b>
                </td> 
            </tr>
            <?php $contador++; ?> 
        @endforeach
    </table>  
    <table style="margin:0px; padding-top: 0px; padding-bottom: 5px" >  
        <tr>
            <td width="60%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                <b>DETALLE DE EVALUACIONES: </b> <br> 
                {{ $nota_enfermeria[0]->descripcion_evaluaciones }}
            </td>  
        </tr>
    </table> 
    
    <hr>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="100%"><b>CMPS</b></td> 
        </tr>
        <?php $contador = 1; ?> 
        @foreach ($nota_enfermeria_cpms as $cpms)
            <tr style="padding: 0px;margin: 0px; text-align: left !important;"> 
                <td width="100%" style=" margin: 0px; padding-bottom: 3px;"> 
                    <b>{{ $contador }}.</b> 
                    <m style="padding: -10px 0px !important; text-transform:uppercase;">{{ $cpms->descripcion }} </m>
                </td> 
            </tr>
            <?php $contador++; ?> 
        @endforeach
    </table>  
 
 
  
     
    <footer style=" color: black; height: 25px;">
        <table > 
            <tr style="font-size: 6px !important;">
                <td style="text-align: left">
                    <b style=" font-size: 6px !important;font-weight: 200;">PACIENTE: </b> <b
                        style="font-size: 8px !important;">{{ $datos_atencion[0]->dni_beneficiario }} - 
                        {{ $datos_atencion[0]->paterno_de_asegurado . ' ' . $datos_atencion[0]->materno_de_asegurado . ' ' . $datos_atencion[0]->nombre_de_asegurado }}</b>
                    <br>

                    <i><b>CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE EVOLUCIÓN DE ENFERMERÍA</b></i>
                </td>
                <td style="text-align: right; padding-right: 10px;">
                    USUARIO RESPOSABLE: <b>{{ $datos_atencion[0]->nombre_usuario }}
                        {{ $datos_atencion[0]->paterno_usuario }}
                        {{ $datos_atencion[0]->materno_usuario }}</b> <br>
                    FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b>  <br>
                    HORA DE IMPRESIÓN: <b>{{$horaActual}}</b> 
                </td>
            </tr>
        </table>
    </footer>


</body>

</html>
FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b>  <br>
HORA DE IMPRESIÓN: <b>{{$horaActual}}</b> 