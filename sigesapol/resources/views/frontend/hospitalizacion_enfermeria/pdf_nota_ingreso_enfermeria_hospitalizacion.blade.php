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
              <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO DE HOSPITALIZACION</span>
            </td>
            <td colspan="2" width="50%"> 
            </td> 
          </tr>
        </tbody>
      </table>
    
      <table style="margin:0px;padding-bottom:-40px;">
        <tbody>
            <tr> 
                <td colspan="3" width="50%" >
                    <span style="margin: 0; font-weight: bold; font-size: 20px !important; padding-bottom: -20px !important;">
                        NOTA DE ENFERMERIA 
                    </span>
                    <br>
                    <span style="margin: 0; font-weight: bold; font-size: 15px !important;"> 
                        {{str_pad($datos_nota_ingreso[0]->id, 8, '0', STR_PAD_LEFT)}}
                    </span>
                    <br>
                    <span style="margin: 0; font-weight: bold; font-size: 15px !important;"> 
                        CUPS: <span style="font-size: 10px; font-weight: 200; padding: 0; margin: 0;">{{str_pad($datos_atencion[0]->codigo_cups, 8, '0', STR_PAD_LEFT)}}</span>
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
                <td colspan="2" width="30%" > 
                </td>
                <td colspan="2" width="20%" >
                    <div  style="margin:0px;padding:0px;"> 
                        <table style="margin:0px;padding:0px;" >
                            <tbody>                                                  
                                <tr>
                                    <td width="50%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C: </span></td>
                                    <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{ isset($datos_atencion[0]->nro_historiass) ? $datos_atencion[0]->nro_historiass : '' }}</b></span></td>
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A: </td>
                                    <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{ isset($datos_atencion[0]->dni_beneficiario) ? $datos_atencion[0]->dni_beneficiario : '' }}</b></td>
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align:left;"><span style="margin: 0; font-size:1.3rem; font-weight: bold;">N. HOSPI: </span></td>
                                    <td width="70%" style="text-align:left;"> <b style="font-size: 1.3rem;"> {{ isset($datos_atencion[0]->id_hospitalizacion ) ? str_pad($datos_atencion[0]->id_hospitalizacion , 8, '0', STR_PAD_LEFT) : '' }}</b></td>
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
                <?php echo 
                     $datos_atencion[0]->nombre_beneficiario . ' ' . 
                     $datos_atencion[0]->paterno_beneficiario . ' ' . 
                     $datos_atencion[0]->materno_beneficiario; ?></b>  
            </td>  
            <td width="20%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                SEXO: <b> {{$datos_atencion[0]->sexo}} </b> 
            </td>  
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                EDAD: <b> {{$dato_cabecera_jhon[0]->asegurado_edad_anios}} AÑOS CON {{$dato_cabecera_jhon[0]->asegurado_edad_meses}} MESES Y {{$dato_cabecera_jhon[0]->asegurado_edad_dias}} DÍAS </b>
            </td>  
        </tr>
      </table>
<br>
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
                <!--aqui AUN NO SE SABE QUE TIPO DE SEGUROS SE VA A INCLUIRSE-->
                TIPO DE SEGURO: SALUDPOL
            </td> 
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                N° DE SEGURO: 457899  
            </td>   
        </tr>
      </table>

      <table  style="margin:0px;padding-bottom:6px;" >
        <tr> 
            <td width="40%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TALLA: <b>{{$signos_vitales[2]->descripcion}} cm</b> 
            </td>  
            <td width="50%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PESO: <b>{{$signos_vitales[4]->descripcion}} kg</b> 
            </td>  
            <td width="79%" style="vertical-align:top;">
              <span style="display: block; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TIPO DE SANGRE: <b style=" font-size: 1.2rem !important;"> {{$datos_atencion[0]->descripcion_tipo_sangres}} </b> 
            </td>   
        </tr>
      </table>
 
        <table style="margin:0px;padding-top:-10px;padding-bottom:6px;" >
            <tr> 
                <td width="190%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    RESPONSABLE:  
                    <b>
                        {{ $dato_cabecera_jhon[0]->nombre_responsable }}, {{ $dato_cabecera_jhon[0]->paterno_responsable }}
                        {{ $dato_cabecera_jhon[0]->materno_responsable }}
                    </b>

                </td>     
            </tr>
        </table>
      
      <table style="margin:0px;padding-top:-10px;padding-bottom:4px;" >
        <tr> 
            <td width="190%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                @if($valoraciones_enfermerias->id_acompanante != 0)
                ACOMPAÑADO POR: 
                    <b>{{$valoraciones_enfermerias->descrip_acompanante == '' ? 'SOLO' : $valoraciones_enfermerias->descrip_acompanante}}</b> 
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
                  FECHA INTERNAMIENTO: <b> {{ explode(' ', $datos_atencion[0]->fecha_ingreso)[0] }} {{ $datos_atencion[0]->hora_ingreso }} </b> 
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

    <br>

    <table style="margin:0px;padding-top:-3px;padding-bottom:4px;" >
      <tr> 
          {{-- <td width="50%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                SERVICIO: {{ $servicio }}</b> 
          </td>   --}}
          <td width="50%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PABELLON: <b>  {{ $pabellon }}</b> 
          </td>  
          <td width="120%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PISO: <b>  {{ $piso }} </b> / CUARTO: <b>  {{ $cuarto }}</b> / N° CAMA: <b> {{ $cama }}</b> 
          </td>    
      </tr>
    </table>
    <hr>
 
    {{-- <b style="font-size: 12px !important;">PROFESIONAL QUE ORDENA LA HOSPITALIZACION</b>
    <table  style="margin:0px;padding-top:-3px;" >
          <tr>
              <td width="60%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    {{ $datos_atencion[0]->nombre_usuario }}
                    {{ $datos_atencion[0]->paterno_usuario }}
                    {{ $datos_atencion[0]->materno_usuario }}</b> 
              </td>  
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                  DNI PROFESIONAL: <b>{{$datos_atencion[0]->profesional_dni}}</b> 
              </td>  
          </tr>
    </table>

    <hr> --}}

    <b style="font-size: 12px !important;">PROFESIONAL QUE REGISTRA NOTA DE INGRESO</b>
    <table  style="margin:0px;padding-bottom:-10px;padding-top:-3px;" >
          <tr>
              <td width="60%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    {{ $usuario_enfermeria->name }}
                    {{ $usuario_enfermeria->paterno }}
                    {{ $usuario_enfermeria->materno }}</b> 
                </span>
              </td>  
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                  DNI PROFESIONAL: <b>{{$usuario_enfermeria->dni}}</b> 
                </span>
              </td>  
          </tr>
    </table>


    <table style="margin:0px;padding-top:-3px;padding-bottom:4px;" >
      <tr> 
          
          <td width="55%" style="vertical-align:top;">
            
          </td>  
          
      </tr>
    </table>
    <hr>
    <b style="font-size: 12px !important;">FUNCIONES VITALES</b>
    <table  style="margin:0px;padding-bottom:-4px;padding-top:-3px;" >
                <br>
            <tr>
              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    PA: <b>{{$signos_vitales[0]->descripcion}} / {{$signos_vitales[1]->descripcion}} mmHg </b> 
              </td>  
              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    FC: <b>{{$signos_vitales[3]->descripcion}} /min</b> 
              </td>  
              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    FR: <b>{{$signos_vitales[5]->descripcion}} /min</b> 
              </td>   
              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    Fi02: <b>{{$signos_vitales[6]->descripcion}} %</b> 
              </td>  
              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    Sa02: <b>{{$signos_vitales[7]->descripcion}} %</b> 
              </td> 
                            <!--------------->
            </tr>
            <tr> 
              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    T°: <b>{{$signos_vitales[8]->descripcion}} °C</b> 
              </td>   

              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    TALLA: <b>{{$signos_vitales[2]->descripcion}} cm</b> 
              </td>   
              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    PESO: <b>{{$signos_vitales[4]->descripcion}} KG</b> 
              </td>  
              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    IMC: <b>{{$signos_vitales[4]->descripcion}} </b> 
              </td>  
              <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    PESO IDEAL: <b>{{$signos_vitales[10]->descripcion}} Kg</b> 
              </td>      
 
          </tr>
          <br>
    </table>
    <hr>

 
 
    <b style="font-size: 12px !important;">ANTECEDENTES</b>
   
       
    <table style="margin: 0; padding: 0;">
        <tr>
            <td width="40%" style="vertical-align: top;">
                <span style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                     HIPERTENSION ARTERIAL.: <b> @if($antecedentesenfermeria->id_hipertension == '1') SI @else NO @endif</b> 
                </span>
            </td>
            <td width="60%" style="vertical-align: top;">
                <span style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                  
                    @if($antecedentesenfermeria->descripcion_hemodialisis != null)
                    DESCRIPCIÓN HIPERTENSION ARTERIAL:
                        <b>{{ $antecedentesenfermeria->descripcion_hemodialisis }}</b>
                    @else
                        <b></b>
                    @endif
                </span>
            </td>
        </tr>
    </table>

    <table style="margin: 0; padding: 0;">
        <tr>
            <td width="40%" style="vertical-align: top;">
                <span style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                    DIABETES_MELLITUS: <b> @if($antecedentesenfermeria->id_diabetes_millitus == '1') SI @else NO @endif</b>
                </span>
            </td>
            <td width="60%" style="vertical-align: top;">
                <span style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                   
                    @if($antecedentesenfermeria->descripcion_diabetes_millitus != null)
                    DESCRIPCIÓN DIABETES MELLITUS:
                        <b>{{ $antecedentesenfermeria->descripcion_diabetes_millitus }}</b>
                    @else
                        <b></b>
                    @endif
                </span>
            </td>
        </tr>
    </table>

    <table style="margin: 0; padding: 0;">
        <tr>
            <td width="40%" style="vertical-align: top;">
                <span style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                    ALERGIA: <b>@if($antecedentesenfermeria->id_alergia == '1') SI @else NO @endif</b>
                </span>
            </td>
            <td width="60%" style="vertical-align: top;">
                <span style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                    
                    @if($antecedentesenfermeria->descripcion_alergia != null)
                    DESCRIPCIÓN ALERGIA:
                        <b>{{ $antecedentesenfermeria->descripcion_alergia }}</b>
                    @else
                        <b></b>
                    @endif
                </span>
            </td>
        </tr>
    </table>
    <table style="margin: 0; padding: 0;">
        <tr>
            <td width="40%" style="vertical-align: top;">
                <span style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                    HEMODIALISIS: <b>@if($antecedentesenfermeria->id_hemodialisis == '1') SI @else NO @endif</b>
                </span>
            </td>
            <td width="60%" style="vertical-align: top;">
                <span style="display: inline-block; font-size: 1.1rem; text-align: left; margin: 0; padding: 0.3rem 0; text-transform: uppercase;">
                    
                    @if($antecedentesenfermeria->descripcion_hemodialisis != null)
                    DESCRIPCIÓN HEMODIALISIS:
                        <b>{{ $antecedentesenfermeria->descripcion_hemodialisis }}</b>
                    @else
                        <b></b>
                    @endif
                </span>
            </td>
        </tr>
    </table>



    <table style="margin:0px;padding-top:3px;padding-top:-1px;" >
      <tr> 
        <td width="80%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            CARDIOLÓGICO: <b>{{ $nombre }}</b>
                                                                                  
        </td>  
<!---
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            VACUNA PARA COVID-19: <b>}</b> 
        </td>       
      </tr>
--->
    </table>


    @if($antecedentesenfermeria->descripcion_observacion)
        <table style="margin:0px;padding-top:-5px;" >
        <tr> 
            <td width="100%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                OBSERVACIONES: <b>{{$antecedentesenfermeria->descripcion_observacion}}</b>  
            </td>    
        </tr>
        </table>
    @endif


    @if($antecedentesenfermeria->descripcion_otro)
        <table style="margin:0px;padding-top:-5px;" >
        <tr> 
            <td width="100%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                OTROS ANTECEDENTES: <b>{{$antecedentesenfermeria->descripcion_otro}}</b> 
            </td>    
        </tr>
        </table>
    @endif
   

    @if (count($diagnosticos) > 0)
    <table style="margin:0px; text-align: left !important; padding-top: 7px;" >
        <tr>
            <td width="4%"><b>CÓDIGO</b></td>
            <td width="70%"><b>DIAGNÓSTICO</b></td>
            <td width="8%"><b>TIPO</b></td>
        </tr>
        @foreach ($diagnosticos as $diagHosp)
            <tr style="padding: 0px;margin: 0px;"> 
                <td width="8%" style="padding: 0px;margin: 0px;"> 
                    {{ $diagHosp->codigo_diagnostico }} 
                </td>
                <td width="70%" style="padding: 0px;margin: 0px;"> 
                    {{ $diagHosp->descripcion_diagnostico }}
                </td>
                <td width="15%" style="padding: 0px;margin: 0px;">   
                    {{ $diagHosp->descripcion_tipo_diagnosticos }} 
                </td>
                <td width="15%" style="padding: 0px;margin: 0px;">   
                    {{ $diagHosp->id_evaluacion == 1 ? 'ADMISION' : 'ATENCION' }} 
                </td>
            </tr>
        @endforeach
    </table> 
    @endif
    @if (count($diagnosticosEvaluacion) > 0)
    <table style="margin:0px; text-align: left !important; padding-top: 7px;" >
        <tr>
            <td width="4%"><b>CÓDIGO</b></td>
            <td width="70%"><b>DIAGNÓSTICO</b></td>
            <td width="8%"><b>TIPO</b></td>
        </tr>
        @foreach ($diagnosticosEvaluacion as $diagHosp)
            <tr style="padding: 0px;margin: 0px;"> 
                <td width="8%" style="padding: 0px;margin: 0px;"> 
                    {{ $diagHosp->codigo_diagnostico }} 
                </td>
                <td width="70%" style="padding: 0px;margin: 0px;"> 
                    {{ $diagHosp->descripcion_diagnostico }}
                </td>
                <td width="15%" style="padding: 0px;margin: 0px;">   
                    {{ $diagHosp->descripcion_tipo_diagnosticos }} 
                </td>
                <td width="15%" style="padding: 0px;margin: 0px;">   
                    {{ 'EVALUACION' }} 
                </td>
            </tr>
        @endforeach
    </table> 
    @endif
    <hr>


 
 
    <b style="font-size: 12px !important;">VALORACIÓN</b>
    <table  style="margin:0px;padding-bottom:-6px;padding-top:-3px;" >
          <tr>
            <td width="45%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                  INGRESA POR: <b>{{$ingresa_por->descrip_larga}}</b> 
            </td> 
            
          </tr>
    </table>


    <table style="margin:0px;padding-top:-6px;padding-top:0px;" >
      <tr> 
        <td width="60%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            CONDICIÓN DE INGRESO: <b>{{$condicion_ingreso->descrip_larga}}</b>  
        </td>  
        <td width="56%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
            ESTADO DE CONCIENCIA: <b>{{$estado_conciencia->descrip_larga}}</b> 
        </td> 
        <td width="60%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
            DETALLE:  <b>@if ($valoraciones_enfermerias->orientado_detalle_tiempo == '0' && 
            $valoraciones_enfermerias->orientado_detalle_espacio == '0' &&
            $valoraciones_enfermerias->orientado_detalle_persona == '0') 
               -
            @else 
                @if ($valoraciones_enfermerias->orientado_detalle_tiempo == '1')
                    {{ $nota_detalle .' '.'TIEMPO' }} 
                @endif
                @if ($valoraciones_enfermerias->orientado_detalle_espacio == 'on')
                    @if ($valoraciones_enfermerias->orientado_detalle_tiempo == 'on')
                        {{ $nota_detalle .' '.' ESPACIO' }}
                    @else
                        {{ $nota_detalle .' '.' ESPACIO' }}  
                    @endif
                @endif    
                @if ($valoraciones_enfermerias->orientado_detalle_persona == 'on')
                    
                    @if ($valoraciones_enfermerias->orientado_detalle_espacio == 'on')
                        {{ $nota_detalle .' '.'PERSONA' }}
                    @elseif ($valoraciones_enfermerias->orientado_detalle_tiempo == 'on')
                        {{ $nota_detalle .' '.'PERSONA' }}
                    @else
                        {{ $nota_detalle . 'PERSONA' }}
                    @endif
                @endif
            @endif  </b> 
        </td>    
      </tr>
    </table>


    <table style="margin:0px;padding-top:-6px;padding-bottom:5px;" >
      <tr> 
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            FUNCION MOTORA: <b>{{ $funcion_motora->descrip_larga }}</b>  
        </td>  
        <td width="30%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
            RESPIRATORIO: <b>{{$respiratorio->descrip_larga}}</b> 
        </td>  
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
            SECRECIONES BRONQUIALES: <b>@if( $valoraciones_enfermerias->id_secrecionbronquiales == '1') SI @else NO @endif</b> 
        </td>   
      </tr>
    </table>


    <table style="margin:0px;padding-top:-3px;padding-bottom:3px;" >
      <tr> 
        <td width="30%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            OXIGENOTERAPIA: <b>@if( $valoraciones_enfermerias->id_oxigenoterapia == '1') SI @else NO @endif</b>  
        </td>  
        @if($valoraciones_enfermerias->id_oxigenoterapia == '1')
            <td width="74.5%" style="vertical-align:top;">
                @if($nombres_oxigeno!=null)
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
                    DESCRIPCION: <br> 
                    {{-- DESCRIPCIÓN:  --}}{{-- <b>{{$detalle_oxigeno->descrip_larga}}</b> --}}
                    @foreach ($nombres_oxigeno as $nombre)
                        <b>{{ "- " . $nombre }}</b><br>
                    @endforeach
                </span>
                @endif 
            </td>  
            <td width="20%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
                    Fi02 (%): <b>{{$valoraciones_enfermerias->oxigenoterapia_fi02}}</b> </span>
            </td>   
        @endif
      </tr>
    </table>

    
    <table style="margin:0px;padding-top:-8px;padding-bottom:5px;" >
      <tr> 
        <td width="30%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            MICCIÓN ESPONTÁNEA: <b> @if( $valoraciones_enfermerias->id_miccion_espontanea == '1') SI @else NO @endif </b>  
        </td>   
      </tr>
    </table>


    <table style="margin:0px;padding-top:-11px;padding-bottom:5px;" >
      <tr> 
        <td width="25.5%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            ESCALA DE GLASGOW: <b>{{ $valoraciones_enfermerias->punto_calculado_glasgow }}</b>  
        </td>  
        <td width="80%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
            VALORACIÓN DE GLASGOW: <b>{{$valoraciones_enfermerias->descripcion_calculado_glasgow}}</b> 
        </td>    
      </tr>
      <tr> 
        <td width="25.5%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            ESCALA DE RASS: <b>{{ $valoraciones_enfermerias->punto_calculado_rass }}</b>  
        </td>  
        <td width="80%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
            VALORACIÓN DE RASS: <b>{{$valoraciones_enfermerias->descripcion_calculado_rass}}</b> 
        </td>    
      </tr>
    </table>

    <hr>


 
 
    <b style="font-size: 12px !important;">PIEL & DISPOSITIVOS</b> 
    <table  style="margin:0px;padding-bottom:-14px;padding-top:-3px;" >
          <tr>  
            <td width="45%" style="vertical-align:top;">
             <div>
                <table style="margin:0px; padding-top:2px" >
                    <tr> 
                        <td width="100%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                                DISPOSITIVOS INVASIVOS: <b> @if($pieldispositivo_enfermerias->id_dispositivo_invasivos == '1') SI @else NO @endif </b>
                        </td>       
                    </tr>
                </table>
                
                @if($pieldispositivo_enfermerias->id_dispositivo_invasivos == '1')  
                    <table style="margin:0px;padding-bottom: 5px;padding-top:3px" >
                        <tr> 
                            <td width="30%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                                VÍA PERIFÉRICA: <b> @if($pieldispositivo_enfermerias->id_via_periferica == '1') SI @else NO @endif</b>  
                            </td>  
                            <td width="30%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
                                VÍA CENTRAL: <b> @if($pieldispositivo_enfermerias->id_via_central == '1') SI @else NO @endif<</b> 
                            </td>      
                        </tr>
                    </table>  
                    <table style="margin:0px;padding-bottom: -1px;padding-top:-10px" >
                        <tr>  
                            <td width="30%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
                                CATÉTER ALTO FLUJO: <b> @if($pieldispositivo_enfermerias->id_caracter_alto_flujo == '1') SI @else NO @endif</b>   
                            </td>   

                        </tr>
                    </table>  


                    <hr style="color: aliceblue; border-width: 1%;">
                    <table  style="margin:0px;padding-top:-7px;" >
                        <tr>
                          <td width="50%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                                SONDAS: <b>@if($pieldispositivo_enfermerias->id_sonda == '1') SI @else NO @endif</b> 
                          </td>     
                            <td width="50%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
                                LÍNEA ARTERIAL: <b>@if($pieldispositivo_enfermerias->id_linea_arterial == '1') SI @else NO @endif</b> 
                            </td>     
                        </tr>
                    </table>
                    <hr style="color: aliceblue; border-width: 1%;">
                    <table style="margin:0px;padding-bottom: -1px;padding-top:-10px" >
                        <tr>  
                            <td width="30%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
                                CURACION: <b> @if($pieldispositivo_enfermerias->id_caracter_curacion == '1') SI @else NO @endif</b>   
                            </td>   

                        </tr>
                    </table>  
                    @if($pieldispositivo_enfermerias->id_sonda == '1') 
                        <table  style="margin:0px; padding-top: -4px;" >
                            <tr>    
                            <td width="50%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                                DESCRIPCIÓN SONDAS: <b>{{$pieldispositivo_enfermerias->descripcion_sonda }}</b>  
                            </td>    
                            </tr>
                        </table>
                    @endif   
         
 
                    <hr style="color: aliceblue; border-width: 1%;">
                    <table  style="margin:0px;padding-top:-7px;" >
                        <tr> 
                            <td width="100%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                                DRENAJES: <b>@if($pieldispositivo_enfermerias->id_tiene_drenaje == '1') SI @else NO @endif</b>  
                            </td>   
                        </tr>
                    </table> 
                    @if($pieldispositivo_enfermerias->id_tiene_drenaje == '1')
                        <table  style="margin:0px; padding-top: -6px;" >
                            <tr>  
                                <td width="100%" style="vertical-align:top;">
                                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">   
                                    DESCRIPCIÓN DRENAJES: 
                                    <b>{{$pieldispositivo_enfermerias->descripcion_drenaje}}</b> 
                                </td>    
                            </tr>
                        </table>
                    @endif  
                @endif
                
                <hr style="color: aliceblue; border-width: 1%;">
                <table  style="margin:0px;padding-top:-7px;" >
                    <tr>
                        <td width="50%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                            @if (isset($piel))
                                PIEL (COLOR): 
                                @if( $piel->descrip_larga ) 
                                    <b>{{$piel->descrip_larga}}</b>
                                @else
                                    <b>-</b>
                                @endif
                            @else
                                PIEL (COLOR): 
                                <b>NO SE SELECCIONO UNA OPCION</b>
                            @endif
                        </td>   
                        <td width="50%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                                EDEMA: <b>@if($valoraciones_enfermerias->id_edema == '1') SI @else NO @endif</b> 
                        </td>        
                    </tr>
                </table> 

                <table  style="margin:0px; padding-bottom:-5px;" >
                    @if($valoraciones_enfermerias->id_integridad=='')
                        <tr>    
                            <td width="100%" style="vertical-align:top;">
                                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                                    INTEGRIDAD: <b>{{$integridad->descrip_larga}}</b>  
                            </td>      
                        </tr>
                    @endif 
                    @if($valoraciones_enfermerias->id_abdomen=='')
                        <tr>     
                            <td width="100%" style="vertical-align:top;">
                                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: -1px;">
                                    ABDOMEN: <b>{{$abdomen->descrip_larga}}</b>  
                            </td>      
                        </tr>
                    @endif
                </table> 
    
                <hr style="color: aliceblue; border-width: 1%;">
                <table  style="margin:0px;padding-bottom:6px; padding-top: -7px;" >
                    <tr>
                      <td width="30%" style="vertical-align:top;">
                        <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                            EVALUACIÓN MALLAMPATY: <b> @if($valoraciones_enfermerias->id_mallampaty == '1') SI @else NO @endif</b> 
                      </td>          
                    </tr>
                    <tr> 
                    @if($valoraciones_enfermerias->id_mallampaty == '1')
                        <td width="80%" style="vertical-align:top;">
                            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0;">
                                <b>
                                @if($valoraciones_enfermerias->descripcion_mallampaty == '*')
                                    -
                                @endif
                                @if($valoraciones_enfermerias->descripcion_mallampaty == '1')
                                    <b>CLASE I:</b> VISIBILIDAD DEL PALADAR BLANDO, ÚVULA Y PILARES
                                @endif
                                @if($valoraciones_enfermerias->descripcion_mallampaty == '2')
                                    <b>CLASE II:</b> VISIBILIDAD DEL PALADAR BLANDO Y ÚVULA
                                @endif
                                @if($valoraciones_enfermerias->descripcion_mallampaty == '3')
                                    <b>CLASE III:</b> VISIBILIDAD DEL PALADAR BLANDO BASE DE LA ÚVULA
                                @endif
                                @if($valoraciones_enfermerias->descripcion_mallampaty == '4')
                                    <b>CLASE IV:</b> IMPOSIBILIDAD PARA VER EL PALADAR BLANDO
                                @endif
                                </b>  
                        </td> 
                      @endif 
                    </tr>
                </table>

             </div>
            </td>    
            <td style="width: 40%;" > 
                    <img src="data:image/png;base64,{{ $datos_nota_ingreso[0]->img_base }}" width="350px"
                        height="245px"   style="padding-left: 15px; padding-top: -17px;">  
            </td>  
          </tr> 
    </table> 
    <hr>


 
 
    <b style="font-size: 12px !important;">ESCALAS</b>
    <table  style="margin:0px;padding-bottom:-3px;padding-top:-3px;" >
          <tr>
            <td width="" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                E. RIESGO LPP: <b>{{$enfermeria_escalas->valoracion_rlp}}</b> 
            </td> 
            <td width="" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                E. CAÍDAS: <b>{{$enfermeria_escalas->valoracion_rc}}</b> 
            </td>
            @if ($valoraciones_enfermerias->id_dolor != null || $valoraciones_enfermerias->id_dolor == 1)
            <td width="" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                E. DOLOR:
                @if($enfermeria_escalas->escala_dolor == 0) 
                    <b>SIN DOLOR (0)</b>   
                @elseif($enfermeria_escalas->escala_dolor == 1)
                    <b>DOLOR LEVE (1-2-3)</b>     
                @elseif($enfermeria_escalas->escala_dolor == 2)
                    <b>DOLOR MODERADO (4-5-6-7)</b>          
                @elseif($enfermeria_escalas->escala_dolor == 3)
                    <b>DOLOR SEVERO (8-9-10)</b>             
                @endif  
            </td>   
            @endif   

            @if($enfermeria_escalas->id_brazalete == '1')
                <td width="" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                    BRAZALETE DE IDENTIFICACIÓN: <b>{{$enfermeria_escalas->id_color_brazalete }}</b> 
                </td>  
            @endif     
          </tr>
    </table>
    <hr>


 
 
    <b style="font-size: 12px !important;">OBSERVACIONES</b>
    <table  style="margin:0px;padding-bottom:-6px;padding-top:-2px;" >
          <tr>
            <td width="100%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              {{$datos_nota_ingreso[0]->observacion_enfermeria}}
            </td>     
          </tr>
    </table>



     
    <footer style=" color: black; height: 25px;">
        <table > 
            <tr style="font-size: 6px !important;">
                <td style="text-align: left">
                    <b style=" font-size: 6px !important;font-weight: 200;">PACIENTE: </b> <b
                        style="font-size: 8px !important;">{{ $datos_atencion[0]->dni_beneficiario }} - 
                        {{ $datos_atencion[0]->paterno_beneficiario . ' ' . $datos_atencion[0]->materno_beneficiario . ' ' . $datos_atencion[0]->nombre_beneficiario }}</b>
                    <br>

                    <i><b>CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE NOTA DE ENFERMERÍA</b></i>
                </td>
                <td style="text-align: right; padding-right: 10px;">
                    USUARIO RESPOSABLE: <b>{{ $dato_cabecera_jhon[0]->nombre_medico }}
                        {{ $dato_cabecera_jhon[0]->paterno_medico }}
                        {{ $dato_cabecera_jhon[0]->materno_medico }}</b> <br>
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