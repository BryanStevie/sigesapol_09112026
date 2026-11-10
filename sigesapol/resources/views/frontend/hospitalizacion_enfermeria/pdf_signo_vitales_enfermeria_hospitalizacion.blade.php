<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PDF CONSOLIDADO SIGNOS VITALES</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
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
                height: 100%;
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
            left: 10mm;
            width: 40px;
            height: 50px;
        }

        .imagen-esquina-derecha {
            position: absolute;
            top: -3mm;
            right: 10mm;
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
        .grafico-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Espacio entre gráficos */
            justify-content: center; /* Centrar los gráficos */
            padding: 20px;
        }
        .grafico-item {
            width: 45%; /* Ancho de cada gráfico */
            height: 300px; /* Alto de cada gráfico */
        }
        #grafico {
            width: 600px;
            height: 400px;
            margin: 0 auto;
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
              <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO DE EMERGENCIA</span>
            </td>
            <td colspan="2" width="50%"> 
            </td> 
          </tr>
        </tbody>
      </table>
    
      <table style="margin:0px;padding-bottom:-40px;">
        <tbody>
            <tr> 
                <td colspan="2" width="50%" >
                    <span style="margin: 0; font-weight: bold; font-size: 20px !important; padding-bottom: -20px !important;">
                        SIGNOS VITALES
                        <BR>(Consolidado)
                    </span><br>
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
                                    <td width="50%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C:</span></td>
                                    <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{ isset($datos_atencion[0]->nro_historiass) ? $datos_atencion[0]->nro_historiass : '' }}</b></span></td>
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A:</td>
                                    <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{ isset($datos_atencion[0]->dni_beneficiario) ? $datos_atencion[0]->dni_beneficiario : '' }}</b></td>
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align:left;"><span style="margin: 0; font-size:1.3rem; font-weight: bold;">N° HOSPI:</span></td>
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
                PESO: <b>{{$signos_vitales[6]->descripcion}} kg</b> 
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
                         {{$usuario_enfermeria->name}}, {{$usuario_enfermeria->paterno}}  {{$usuario_enfermeria->materno}}
                    </b>  

                </td>     
            </tr>
        </table>
   
      <hr>

    <b style="font-size: 12px !important;">DATOS DE INGRESO</b>
    <table  style="margin:0px;padding-bottom:-10px;padding-top:-3px;" >
          <tr>
              <td width="60%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                  FECHA INTERNAMIENTO: <b> {{ $datos_atencion[0]->fecha_ingreso }} {{ $datos_atencion[0]->hora_ingreso }} </b> 
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
          <td width="50%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                SERVICIO: {{ $servicio }}</b> 
          </td>  
          <td width="50%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PABÉLLON: <b>  {{ $pabellon }}</b> 
          </td>  
          <td width="120%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PISO: <b>  {{ $piso }} </b> / CUARTO: <b>  {{ $cuarto }}</b> / N° CAMA: <b> {{ $cama }}</b> 
          </td>    
      </tr>
    </table>
    <hr>
 
    <b style="font-size: 12px !important;">PROFESIONAL QUE ORDENA LA HOSPITALIZACION</b>
    <table  style="margin:0px;padding-bottom:-10px;padding-top:-3px;" >
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
              {{-- <td width="50%" style="vertical-align:top;">
                <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                  CÓDIGO IPRESS: <b>{{$datos_atencion[0]->codigoipress}}</b> 
              </td>    --}}
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
                    PESO: <b>{{$signos_vitales[6]->descripcion}} KG</b> 
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

    <b style="font-size: 12px !important;">SIGNOS VITALES</b>
    
    <table  style="margin:0px;padding-bottom:-6px;padding-top:-2px;" >
          <tr>
            <td width="100%" style="vertical-align:top;">
            <table id="tblSuperUsuario" class="table table-bordered ">
                                            <thead>
                                                <tr>
                                                    <th>N</th>
                                                    <th>PRESION ARTERIAL</th>
                                                    <th>FRECUENCIA CARDIACA</th>
                                                    <th>FRECUENCIA RESPIRATORIA</th>
                                                    <th>TEMPERATURA</th>
                                                    <th>PRECIÓN ARTERIAL MEDIA</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                          @if($estado == 1)
                                              @foreach( $arreglo_lista as $dato)
                                              <tr>
                                                    <th>{{$dato->numero}}</th>

                                                    <th>{{$dato->presionx}} / {{$dato->presiony}}</th>
                                                    <th>{{$dato->cardiaca}}</th>
                                                    <th>{{$dato->respiratorio}}</th>
                                                    <th>{{$dato->temperatura}}</th>
                                                    <th>{{$dato->presionmedia}}</th>

                                              </tr>
                                              @endforeach
                                          @else
                                                  <tr>
                                                    <td colspan="11" style="text-align: center; color: #999;">No existen datos</td>
                                                  </tr>
                                          @endif

                                            </tbody>
                                        </table>
             
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

                    <i><b>CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE DOCIMENTOS DE SIGNOS VITALES DE ENFERMERÍA</b></i>
                </td>
                <td style="text-align: right; padding-right: 10px;">
                    USUARIO RESPOSABLE: <b>{{$imprime_pdf}}</b> <br>
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