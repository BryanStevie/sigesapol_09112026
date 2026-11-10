<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>NUEVA NOTA DE ENFERMERIA</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
      table {
          border-collapse: collapse;
      }
      
      @page {
          margin: 0.5in 1in 1in 1in; /* Ajusta los márgenes según tus necesidades */
      }
      footer {
          position:absolute;
          bottom: 0;
          left: 0;
          width: 100%;
          text-align: center;
          font-size: 12px;
          border-top: 1px solid #000;
          padding-top: 5px;
          padding-bottom: 20px;
      }

      .content {
          margin-bottom: 1in; /* Asegura que el contenido no se sobreponga al footer */
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
  </style>
</head>

<body style="font-family: arial;padding:-30px;font-family:Arial, Helvetica, sans-serif;">
  <div class="content">

    <table style="margin-top:70px;padding:0px;padding-bottom:0px;">
    <tbody>
        <tr>
            <td colspan="2" width="70%" style="vertical-align:bottom;padding-bottom:30px">
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
              <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO DE ENFERMERIA</span>
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
                        {{str_pad($evoluciones_cabecera[0]->id, 8, '0', STR_PAD_LEFT)}}
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
                <td colspan="2" width="50%" > 
                </td>
                <td colspan="2" width="40%" >
                    <div  style="margin:0px;padding:0px;"> 
                        <table style="margin:0px;padding:0px;" >
                            <tbody>                                                  
                              <tr >
                                  <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C:</span></td>
                                  <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  
                                    <b>{{$datos_paciente->nro_historia}}</b>
                                  </span></td>
                              </tr>
                              <tr>
                                  <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A:</td>
                                  <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  
                                    <b>{{$datos_paciente->nro_doc_ident}}</b>
                                  </span>
                                  </td>
                              </tr>
                              <tr>
                                <td width="150%" style="text-align:left;" ><span style="margin: 0; font-size:1.0rem; font-weight: bold;">
                                    <?php
                                    if($id_tipo_atencion==2){
                                    ?>
                                    N° EMERGENCIA:
                                    <?php } ?>
                                    <?php
                                    if($id_tipo_atencion==3){
                                    ?>
                                    N° HOSPITALIZACIÓN:
                                    <?php } ?>
                                  </span></td>
                                  <td width="70%" style="text-align:left;"> <b style="font-size: 1.0rem;">
                                    {{str_pad($evoluciones_cabecera[0]->id, 8, '0', STR_PAD_LEFT)}}
                                  </b></td>
                              </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </td>  
            </tr>
        </tbody>
      </table>

      <b style="font-size: 12px !important;">DATOS DEL PACIENTE</b>
      <table  style="margin:0px;padding-bottom:-6px;" >
        <tr> 
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                F.NACIMIENTO: 
                <b>{{$datos_paciente->fecha_nacimiento}}</b> 
              </span>
            </td>  
            <td width="100%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                PARENTESGO: 
                <b> {{$datos_paciente->parentesgo}} </b> 
              </span>
            </td> 
            <td width="20%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                SEXO: 
                <b> {{$datos_paciente->sexo}} </b> 
              </span>
            </td>   
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                EDAD: 
                <b> {{$datos_paciente->edad_corta}} </b>
              </span>
            </td> 
        </tr>
      </table>
 


      <table  style="margin:0px;padding-bottom:6px;" >
        <tr> 
            <td width="79%" style="vertical-align:top;">
              <span style="display: block; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TIPO DE SANGRE: 
              <b style=" font-size: 1.2rem !important;"> {{$datos_paciente->tipo_sangre}} </b>
              </span>
            </td>   
        </tr>
      </table>
 
      {{-- @if($datos_atencion[0]->numero_documento_responsable)
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
      @endif  --}}
      
      
      <hr>


    <b style="font-size: 12px !important;">DATOS DE LA ATENCIÓN</b>
    <table  style="margin:0px;padding-bottom:-10px;padding-top:-3px;" >
      <tr>
          <td width="20%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              TIPO DE ATENCIÓN:
              <b> {{$evoluciones_cabecera[0]->descripcion_tipo_atenciones }}</b> 
            </span>
          </td>  
          <td  width="20%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              FECHA DE ATENCIÓN: 
              <b>{{$evoluciones_cabecera[0]->fecha_evol}}</b> 
            </span>
          </td>  
          <td width="20%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              HORA DE ATENCIÓN:
              <b>{{$evoluciones_cabecera[0]->hora_evol}}</b> 
            </span>
          </td>    
      </tr>
      <tr>
        <?php
        if($id_tipo_atencion==2){
        ?>
        <td width="40%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
           PRIORIDAD:
            <b> {{ $datos_atencion->prioridad}}</b> 
          </span>
        </td> 
        <?php } ?> 
        <td  width="20%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            TURNO: 
            <b>{{$evoluciones_cabecera[0]->turno}}</b> 
          </span>
        </td>  
        <td width="60%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            UBICACION:
            <b> 
              <?php
              if($id_tipo_atencion==2){
              ?>
              {{ $datos_atencion->area}} -  {{ $datos_atencion->nombre_cuarto}} - {{ $datos_atencion->nombre_cama}}
              <?php }else if($id_tipo_atencion==3){ ?>
              {{ $datos_atencion->nombre_cuarto}} - {{ $datos_atencion->nombre_cama}}
              <?php }?>
            </b>
          </span>
        </td>    
    </tr>
    </table>
    <br>
    <hr>

  
    <b style="font-size: 12px !important;">VALORACIÓN DE ENFERMERÍA</b>
    <table  style="margin:0px;padding-bottom:-4px;padding-top:-3px;" >
        <tr>
            <td width="60%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;"> 
                  <b>SUBJETIVO: </b> <br> 
                  {{ $evoluciones_cabecera[0]->descripcion_subjetivo }}
            </td>  
        </tr>
        <tr>
            <td width="60%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                <b>OBJETIVO: </b> <br>  
                {{ $evoluciones_cabecera[0]->descripcion_objetivo }}
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
        @foreach ($evoluciones_cabecera_diagnostico as $nota_diagnostico)
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
        @foreach ($evoluciones_cabecera_intervenciones as $intervenciones)
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
                {{ $evoluciones_cabecera[0]->descripcion_intervenciones }}
            </td>  
        </tr> 
    </table> 
    
    <table style="margin:0px; padding-top: 7px;padding-bottom: 3px;" > 
        <tr style=" text-align: left !important;">
            <td width="90%"><b>PLANEACIÓN</b></td>
            <td width="10%"><b>VALORACIÓN</b></td> 
        </tr>
        <?php $contador = 1; ?> 
        @foreach ($evoluciones_cabecera_plan_cuidado as $plan_cuidado)
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
        @foreach ($evoluciones_cabecera_evaluaciones as $evaluaciones)
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
                {{ $evoluciones_cabecera[0]->descripcion_evaluaciones }}
            </td>  
        </tr>
    </table> 
    
    <hr>
    <table style="margin:0px; padding-top: 7px;" > 
        <tr style=" text-align: left !important;">
            <td width="100%"><b>CMPS</b></td> 
        </tr>
        <?php $contador = 1; ?> 
        @foreach ($evoluciones_cabecera_cpms as $cpms)
            <tr style="padding: 0px;margin: 0px; text-align: left !important;"> 
                <td width="100%" style=" margin: 0px; padding-bottom: 3px;"> 
                    <b>{{ $contador }}.</b> 
                    <m style="padding: -10px 0px !important; text-transform:uppercase;">
                      {{ $cpms->nombre_procedimiento }} 
                    </m>
                </td> 
            </tr>
            <?php $contador++; ?> 
        @endforeach
    </table> 
    <hr>
    <table width="100%">
      <tr>
        <td width="80%">
          <table style="margin:0px; padding-top: 0px;" > 
            <tr style=" text-align: left !important;">
              <td width="100%" colspan="4"><b>INVASIVOS</b></td> 
            </tr>
            <tr style=" text-align: left !important;">
                <td width="100%"><b>#</b></td>
                <td width="100%"><b>DESCRIPCIÓN</b></td> 
                <td width="100%"><b>FECHA</b></td> 
                <td width="100%"><b>LUGAR</b></td> 
            </tr>
            <?php $contador = 1; ?> 
            @if ($evoluciones_cabecera_invasivos)
              @foreach ($evoluciones_cabecera_invasivos as $invasivos)
                  <tr style="padding: 0px;margin: 0px; text-align: left !important;"> 
                      <td width="100%" style=" margin: 0px; padding-bottom: 3px;"> <b>{{ $contador }}.</b></td> 
                      <td width="100%" style=" margin: 0px; padding-bottom: 3px;"> 
                        <m style="padding: -10px 0px !important; text-transform:uppercase;">
                          {{ $invasivos->tipo && $invasivos->tipo !== 'NULL' ? $invasivos->tipo : '' }}
                        </m>
                    </td> 
                    <td width="100%" style=" margin: 0px; padding-bottom: 3px;"> 
                      <m style="padding: -10px 0px !important; text-transform:uppercase;">
                        {{ $invasivos->fecha && $invasivos->fecha !== 'NULL' ? $invasivos->fecha : '' }}
                      </m>
                    </td>
                    <td width="100%" style=" margin: 0px; padding-bottom: 3px;"> 
                      <m style="padding: -10px 0px !important; text-transform:uppercase;">
                        {{ $invasivos->lugar && $invasivos->lugar !== 'NULL' ? $invasivos->lugar : '' }}
                      </m>
                    </td>
                  </tr>
                  <?php $contador++; ?> 
              @endforeach
            @endif
        </table>  
        </td>
        <td>
          @if($evoluciones_cabecera[0]->img_puntos_base === 'data:,')
          <img src="{{ public_path('images/hospitalizacion_enfermeria/imagen2.jpg') }}"  style="width: 300px; height: auto;">
          @else
            <img src="data:image/png;base64,{{ $evoluciones_cabecera[0]->img_puntos_base }}" style="width: 300px; height: auto;">
          @endif
        </td>
      </tr>
    </table> 
 
  </div>
  
     
    <footer style=" color: black; height: 25px;">
        <table > 
            <tr style="font-size: 6px !important;">
                <td style="text-align: left">
                    <b style=" font-size: 6px !important;font-weight: 200;">PACIENTE: </b> 
                    <b style="text-align: right; padding-right: 10px;">
                        
                        {{ $datos_paciente->nro_doc_ident }} - 
                       <?php echo  $datos_paciente->paterno . ' ' . $datos_paciente->materno . ' ' . $datos_paciente->nombre;?>
                      </b>
                    <br>

                    <i><b>CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE EVOLUCIÓN DE ENFERMERÍA</b></i>
                </td>
                <td style="text-align: right; padding-right: 10px;">
                    USUARIO RESPOSABLE: 
                    <b> {{$evoluciones_cabecera[0]->nombre_usuario}} 
                        {{ $evoluciones_cabecera[0]->paterno_usuario }}
                        {{ $evoluciones_cabecera[0]->materno_usuario }}</b> <br>
                    FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b>  <br>
                    HORA DE IMPRESIÓN: <b>{{$horaActual}}</b> 
                </td>
            </tr>
        </table>
    </footer>
    <script type="text/php">
      if ( isset($pdf) ) { 
         
          $pdf->page_script('
              if ($PAGE_COUNT > 1) {
                 $pdf->text(520, 800, "Pagina " . $PAGE_NUM . " de " . $PAGE_COUNT, 7,7);
              } else {
                $pdf->text(520, 800, "Pagina " . $PAGE_NUM, 7, 7);
              }
          ');
      }
  </script>

</body>

</html>
FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b>  <br>
HORA DE IMPRESIÓN: <b>{{$horaActual}}</b> 