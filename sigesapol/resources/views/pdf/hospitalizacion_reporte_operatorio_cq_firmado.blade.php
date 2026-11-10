<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>REPORTE OPERATORIO</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
     @page {
      margin-left: 0.25in;
      margin-right: 0.25in;
      margin-top: 0.15in;
      margin-bottom: 0.15in;
      size: 8.5in 9in;
    }

    body {
        margin: 0; /* Eliminar márgenes predeterminados del cuerpo */
    }
    .tabla {
        display: table;
        width: 113%;
        border-collapse: collapse;
        font-size: 9.5px;
        font-weight: bold;
    }
    .tabla-center{
      margin: auto;
    }
    .fila {
        display: table-row;
    }
    .celda {
        display: table-cell;
        padding: 2px;
        border: 1px solid black;
        background-color:rgb(67, 68, 70);
        color: white;
        text-align: center;
    }
    .celda2 {
        display: table-cell;
        padding: 3px;
    }
    .celda_blanco1 {
        display: table-cell;
        border: 1px solid black;
        padding: 5px; 
    }
    .celda_solo_border_izquierdo{
      display: table-cell;
      border-left: 1px solid black;
      border-right: 1px solid rgb(255, 255, 255);
      padding: 2px; 
    }
    .celda_sin_border {
        display: table-cell;
        border-left: 1px solid rgb(255, 255, 255);
        border-right: 1px solid rgb(255, 255, 255);
        padding: 2px; 
    }
    .celda_blanco2 {
        display: table-cell;
        border: 1px solid black;
        padding: 2px;
        height: 200px; /* Altura reducida */
    }
    .celda_blanco3 {
        display: table-cell;
        padding: 2px;
        height: 200px; /* Altura reducida */
    }
    
    .celda_plomo1 {
        display: table-cell;
        border: 1px solid black;
        background-color: #E1E1E1;
        padding: 2px;
        height: 10px; /* Altura reducida */
    }
    .titulo {
      text-align: center;
    }
    .celda_firma{
      border-top:#021919 solid 2px;
      width: 36%;
      text-align: center;
    }

    .contenedor {
      position: relative;
      width: 30px; /* Ajusta el ancho según sea necesario */
      height: 17px; /* Ajusta la altura según sea necesario */
    }

    .valor {
      top: 1px;
      position: relative;
      z-index: 1;
    }

    .marca {
      position: absolute;
      top: -0.2%;
      right: 30%;
      z-index: 2;
      color: rgba(22, 125, 189, 0.1);
      opacity: 0.7; /* Opacidad del 50% */
      font-size: 18px; 
      transform: translate(-50%, -50%); 
      font-weight: lighter;
      font-family: Helvetica,Arial,sans-serif;
    }
    .valor2 {
      position: relative;
      top: 1px;          /* pequeño ajuste fino */
      z-index: 1;
      font-size: 12px;
      height: 14px;      /* igual que la celda */
      line-height: 14px; /* centra verticalmente dentro de la altura fija */
      display: inline-block;
      text-align: center;
    }
    table{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        font-size: 11px; 
        font-family: "Times New Roman", Times, serif;
    }
    </style>
</head>

<body>
  <br>
    <table style="margin:0px;padding:0px;padding-bottom:0px;" border="0">
        <tr>
          <td width="20%" rowspan="2">
              
            </td>
            <td width="70%">
                <table style="margin:0px;padding:0px;padding-bottom:0px;" border="0">
                    <tr>
                        <td width="100%" style="text-align:center; vertical-align:middle;">
                            <h2 style="text-transform: uppercase;color: red;">{{$establecimiento}}</h2>
                            <h3 style="padding-top: 0.1rem">DEPARTAMENTO DE CENTRO QUIRURGICO</h3>
                            <h3 style="padding-top: 0.1rem">SERVICIO DE HOSPITALIZACIÓN - REPORTE OPERATORIO</h3>
                        </td>
                    </tr>
                   
                </table>
            </td>
            <td width="10%" rowspan="1" style="padding: 0; margin: 0; text-align: center; vertical-align: middle;">
                <img src="images/logo_pnp.png" width="80" style="display: block; margin: 0 auto; padding: 0;" />
            </td>
            
        </tr>
    </table>
    <div class="tabla">
        <div class="fila">
            <div class="celda titulo" colspan="14">REPORTE OPERATORIO</div>
        </div>
        
        <div class="fila">
          <div class="celda titulo" colspan="14">DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD</div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 35%;"  colspan="5"> CÓDIGO UNICO DE RENIPRESS</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 65%;"  colspan="9">NOMBRE  DE LA IPRESS QUE REALIZA LA ATENCIÓN</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 35%;"  colspan="5">{{!empty($datos['codigo_renipres'])?$datos['codigo_renipres']:''}}</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 65%;"  colspan="9">{{!empty($datos['nombre_impress'])?$datos['nombre_impress']:''}}</div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14"> DATOS DE IDENTIFICACIÓN DEL PACIENTE</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1" style="width: 6%;" colspan="2">  IDENTIFICACIÓN</td>
                <td class="celda_plomo1" style="width: 10%;" >  HISTORIA CLINICA</td>
                <td class="celda_plomo1" style="width: 3%;" colspan="2">SEXO</td>
                <td class="celda_plomo1"  style="width: 5%;" colspan="3">EDAD </td>
                <td class="celda_plomo1" style="width: 10%;">SERVICIO</td>
                <td class="celda_plomo1" style="width: 10%;">N° CAMA</td>
              </tr>
              <tr>
                <td class="celda_plomo1" style="width: 1%;">TDI</td>
                <td class="celda_plomo1" style="width: 5%;"> N° DOCUMENTO DE IDENTIDAD</td>
                <td class="celda_blanco1" rowspan="2">{{!empty($datos['nro_historia'])?$datos['nro_historia']:''}}</td>
                <td class="celda_blanco1" style="width: 2%;">Masculino</td>
                <td class="celda_blanco1" style="width: 1%;">{{!empty($datos['sexo']) && $datos['sexo'] == 'M' ? 'X' : '' }}</td>
                <td class="celda_blanco1" style="width: 1%;">AÑOS</td>
                <td class="celda_blanco1" style="width: 1%;">MESES</td>
                <td class="celda_blanco1" style="width: 1%;">DIAS</td>
                <td class="celda_blanco1" rowspan="2">{{!empty($datos['servicio_encargado'])?$datos['servicio_encargado']:''}}</td>
                <td class="celda_blanco1" rowspan="2">{{!empty($datos['cama'])?$datos['cama']:''}}</td>
              </tr>
              <tr>
                <td class="celda_blanco1" style="width: 1%;">{{!empty($datos['tipo_doc_ident'])?$datos['tipo_doc_ident']:''}}</td>
                <td class="celda_blanco1" style="width: 5%;">{{!empty($datos['nro_doc_ident'])?$datos['nro_doc_ident']:''}}</td>
                <td class="celda_blanco1" style="width: 2%;">Femenino</td>
                <td class="celda_blanco1" style="width: 1%;">{{ !empty($datos['sexo']) && $datos['sexo'] == 'F' ? 'X' : '' }}</td>
                <td class="celda_blanco1" style="width: 1%;">{{!empty($datos['dia_nac'])?$datos['edad_anio']:''}}</td>
                <td class="celda_blanco1" style="width: 1%;">{{!empty($datos['mes_nac'])?$datos['edad_mes']:''}}</td>
                <td class="celda_blanco1" style="width: 1%;">{{!empty($datos['anio_nac'])?$datos['edad_dia']:''}}</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;"  colspan="5"> APELLIDO PATERNO</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;"  colspan="9">APELLIDO MATERNO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="5">{{!empty($datos['paciente_paterno'])?$datos['paciente_paterno']:''}}</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="9">{{!empty($datos['paciente_materno'])?$datos['paciente_materno']:''}}</div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 100%;"  colspan="14"> NOMBRES</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="14">{{!empty($datos['paciente_nombre'])?$datos['paciente_nombre']:''}}</div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14"> INFORMACION DE LA INTERVENCIÓN QUIRURGICA</div>
        </div>
        <div class="fila">
            <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
                <table style="width: 100%;" border="1">
                <tr>
                    <td class="celda_plomo1" style="width: 5%;">TPO DE ANESTESIA</td>
                    <td class="celda_plomo1" style="width: 5%;">FECHA INTERVENCIÓN</td>
                    <td class="celda_plomo1" style="width: 5%;">HORA INICIO DE INTERVENCIÓN</td>
                    <td class="celda_plomo1"  style="width: 5%;">HORA FIN DE INTERVENCIÓN</td>
                    <td class="celda_plomo1"  style="width: 5%;">TIEMPO OPERATORIO</td>
                </tr>
                <tr>
                    <td class="celda_blanco1" style="width: 5%;">{{!empty($datos['tipo_anestesia'])?$datos['tipo_anestesia']:''}}</td>
                    <td class="celda_blanco1" style="width: 5%;">{{!empty($datos['fecha_intervencion'])?$datos['fecha_intervencion']:''}}</td>
                    <td class="celda_blanco1" style="width: 5%;">
                      {{ !empty($datos['hora_inicio_operatorio']) ? $datos['hora_inicio_operatorio'] : '' }} 
                    </td>
                    <td class="celda_blanco1" style="width: 5%;">
                      {{ !empty($datos['hora_fin_operatorio']) ? $datos['hora_fin_operatorio'] : '' }}
                    </td>
                    <td class="celda_blanco1" style="width: 5%;">
                      {{ !empty($datos['hora_duracion_operatorio']) ? $datos['hora_duracion_operatorio'] : '' }}
                    </td>
                </tr>
                </table>
            </div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14"> DESCRIPCION DEL PROCEDIMIENTO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: left; height: 2%;" colspan="14">
          {!! !empty($datos['descripcion_procedimiento'])?$datos['descripcion_procedimiento']:'' !!}
          </div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14">HALAZGO OPERATORIO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: left; height: 2%;" colspan="14">
          {!! !empty($datos['allazgo_operatorio'])?$datos['allazgo_operatorio']:'' !!}
          </div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14">EXAMEN PATOLOGICO</div>
        </div>
        <div class="fila">
           <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1">N°</td>
                <td class="celda_plomo1">CÓDIGO</td>
                <td colspan="9" class="celda_plomo1">  NOMBRE DEL PROCEDIMIENTO</td>
              </tr>
              <?php $n = 1; ?>
              @if(!empty($datos['procedimientos']))
                @foreach($datos['procedimientos'] as $key=> $proc)
                  @if($proc['flag_examen_patologia'])
                  <tr>
                    <td>{{ $n }}</td>
                    <td>{{$proc['codigo']}}</td>
                    <td colspan="9" style="text-align: left;">{{$proc['descripcion']}}</td>
                  </tr>
                  <?php $n++; ?>
                  @endif
                @endforeach
              @endif
            </table>
          </div>
        </div>
        @if(!empty($datos['pieza_operatorio']))
        <div class="fila">
          <div class="celda titulo" colspan="14">PIEZA OPERATORIO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: left; height: 2%;" colspan="14">
           {!! !empty($datos['pieza_operatorio'])?$datos['pieza_operatorio']:'' !!}
          </div>
        </div>
        @endif
       <div class="fila">
          <div class="celda titulo" colspan="14">EXAMEN BACTERIOLOGICO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: left; height: 2%;" colspan="14">
           {!! !empty($datos['examen_bacteriologico'])?$datos['examen_bacteriologico']:'' !!}
          </div>
        </div>
         <div class="fila">
          <div class="celda titulo" colspan="14"> DIAGNÓSTICOS PRE OPERATORIO</div>
        </div>
        
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1" style="width: 1%;">N°</td>
                <td class="celda_plomo1" style="width: 6%;">CIE - 10</td>
                <td  class="celda_plomo1">DESCRIPCIÓN DEL DIAGNÓSTICO</td>
                <td class="celda_plomo1" colspan="3" style="width: 5%;"> TIPO DE DIAGNÓSTICO</td>
              </tr>
            @if(!empty($datos['pre_diagonosticos_emergencia']))
                @foreach($datos['pre_diagonosticos_emergencia'] as $key=> $dig)
                         <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$dig['codigo']}}</td>
                            <td>{{$dig['nombre']}}</td>
                            <td >
                              <div class="contenedor">
                                  <div class="valor2" style="font-size: 12px;">P</div>
                                  <div class="marca"><?php if($dig['tipo']=='PRESUNTIVO'){echo 'X';}?></div>
                              </div>
                            </td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor2" style="font-size: 12px;">D</div>
                                    <div class="marca"><?php if($dig['tipo']=='DEFINITIVO'){echo 'X';}?></div>
                                </div>
                            </td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor2" style="font-size: 12px;">R</div>
                                    <div class="marca"><?php if($dig['tipo']=='REITERATIVO'){echo 'X';}?></div>
                                </div>
                            </td>
                        </tr>
                @endforeach
            @elseif(!empty($datos['pre_diagonosticos_consultaexterna']))
                @foreach($datos['pre_diagonosticos_consultaexterna'] as $key=> $diag)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$diag['codigo']}}</td>
                            <td>{{$diag['nombre']}}</td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 12px;">P</div>
                                    <div class="marca"><?php if($diag['tipo']=='PRESUNTIVO'){echo 'X';}?></div>
                                </div>
                            </td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 12px;">D</div>
                                    <div class="marca"><?php if($diag['tipo']=='DEFINITIVO'){echo 'X';}?></div>
                                </div>
                            </td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 12px;">R</div>
                                    <div class="marca"><?php if($diag['tipo']=='REITERATIVO'){echo 'X';}?></div>
                                </div>
                            </td>
                        </tr>
                @endforeach
            @endif
            </table>
          </div>
        </div>
       
        <div class="fila">
          <div class="celda titulo" colspan="14"> DIAGNÓSTICOS POST OPERATORIO</div>
        </div>

        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1" style="width: 1%;">N°</td>
                <td class="celda_plomo1" style="width: 6%;">CIE - 10</td>
                <td  class="celda_plomo1">DESCRIPCIÓN DEL DIAGNÓSTICO</td>
                <td class="celda_plomo1" colspan="3" style="width: 5%;"> TIPO DE DIAGNÓSTICO</td>
              </tr>
              @if(!empty($datos['post_diagnosticos']))
              @foreach($datos['post_diagnosticos'] as $key=> $dig)
              <tr>
                <td>{{$key+1}}</td>
                <td>{{$dig['codigo']}}</td>
                <td >{{$dig['nombre']}}</td>
                <td>
                  <div class="contenedor">
                    <div class="valor"  style="font-size: 14px;"> P </div>
                    <div class="marca"><?php if($dig['tipo']=='PRESUNTIVO'){echo 'X';}?></div>
                  </div>
                </td>
                <td>
                  <div class="contenedor">
                  <div class="valor"  style="font-size: 12px;">D</div>
                  <div class="marca"><?php if($dig['tipo']=='DEFINITIVO'){echo 'X';}?></div>
                  </div>
                </td>
                <td>
                  <div class="contenedor">
                    <div class="valor"  style="font-size: 12px;">R</div>
                    <div class="marca"><?php if($dig['tipo']=='REPETITIVO'){echo 'X';}?></div>
                  </div>
                 </td>
              </tr>
              @endforeach
              @endif
            </table>
          </div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14">  PROCEDIMIENTOS</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1">N°</td>
                <td class="celda_plomo1">CÓDIGO</td>
                <td colspan="9" class="celda_plomo1">  NOMBRE DEL PROCEDIMIENTO</td>
                <td colspan="2" class="celda_plomo1" style="width: 12%;">FASE OPERATORIA</td>
              </tr>
              @if(!empty($datos['procedimientos']))
              @foreach($datos['procedimientos'] as $key=> $proc)
              @if(!$proc['flag_examen_patologia']) 
              <tr>
                <td>{{$key+1}}</td>
                <td>{{$proc['codigo']}}</td>
                <td colspan="9" style="text-align: left;">{{$proc['descripcion']}}</td>
                <td>
                    <div class="contenedor">
                        <div class="valor"  style="font-size: 12px;">PRE</div>
                        <div class="marca"><?php if($proc['flag_pre_post']==1){echo 'X';}?></div>
                    </div>
                </td>
                <td>
                    <div class="contenedor">
                        <div class="valor"  style="font-size: 12px;">POST</div>
                        <div class="marca"><?php if($proc['flag_pre_post']==2){echo 'X';}?></div>
                    </div>
                </td>
              </tr>
              @endif
              @endforeach
              @endif
            </table>
          </div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14">  PROFESIONALES PARTICIPANTES</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1">N°</td>
                <td class="celda_plomo1">CARGO</td>
                <td class="celda_plomo1"> PROFESIONAL</td>
              </tr>
              @if(!empty($datos['profesionales']))
              @foreach($datos['profesionales'] as $key=> $prof)
              <tr>
                <td class="celda_blanco1">{{$key+1}}</td>
                <td class="celda_blanco1" style="text-align: left;">{{$prof['descripcion']}}</td>
                <td class="celda_blanco1" style="text-align: left;">{{$prof['medico_nombre']}} {{$prof['medico_paterno']}} {{$prof['medico_materno']}}</td>
              </tr>
              @endforeach
              @endif
            </table>
          </div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14"> COMPLICACIONES DURANTE LA INTERVENCON QUIRURGICA</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: left; height: 2%;" colspan="14">
          {{!empty($datos['complicaciones'])?$datos['complicaciones']:''}}
          </div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14">ESTADO POSTOPERATORIO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1">DESTINO Y ESTADO</td>
                <td class="celda_plomo1">CONTEO DE GASAS</td>
              </tr>
              <tr>
                <td class="celda_blanco1">{{!empty($datos['destino'])?$datos['destino']:''}} - {{!empty($datos['estado'])?$datos['estado']:''}}</td>
                <td class="celda_blanco1">SI: {{!empty($datos['total_gasas'])?$datos['total_gasas']:''}}</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="fila">
          <div class="celda_blanco1" colspan="14">
              <table>
                <tr>
                  <td style="text-align:right;vertical-align:middle;margin: 0;">
                      <img src="images/sello_depdiagimag.png" style="max-width:80px;margin: 0;"  />
                  </td>
                   <td style="text-align:left;margin: 0;" width="210px">
                      <?php
                          $dniUsuario = Auth::user()->dni;
                          $nombreFirma = DB::table('medicos')
                                          ->where('dni', $dniUsuario)
                                          ->value('nombre_firma');

                          $firmaPath = public_path('images/profesionales/' . $nombreFirma);
                      ?>

                      @if ($nombreFirma && file_exists($firmaPath))
                          <img src="file://{{ $firmaPath }}" alt="Firma del médico" style="max-width:135px;margin: 0;">
                      @endif
                  </td>
                </tr>
              </table>
          </div>
        </div>
    </div>
    <script type="text/php">
      if ( !empty($pdf) ) {
              $pdf->page_script('
                  $pdf->text(560, 20, "$PAGE_NUM/$PAGE_COUNT", "Arial", 8);
              ');
          }
    </script>
</body>

</html> 