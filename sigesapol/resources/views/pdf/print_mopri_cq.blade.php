<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MOPRI</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
     @page {
      margin-left: 0.25in;
      margin-right: 0.25in;
      margin-top: 0.15in;
      margin-bottom: 0.15in;
      size: 8.5in 10.5in;
    }

    body {
        margin: 0; /* Eliminar márgenes predeterminados del cuerpo */
    }
    .tabla {
        display: table;
        width: 113%;
        border-collapse: collapse;
        font-size: 7.0px;
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
        padding: 2px; 
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
    .valor2 {
      top: 1px;
      position: relative;
      z-index: 1;
      border: 1px solid black;
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
    </style>
</head>

<body>
    <div class="tabla">
        <div class="fila">
            <div class="celda titulo" colspan="14">FECHA DE EVALUACIÓN PRE-QUIRÚRGICA MOPRI - SNP </div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14">HOSPITALIZACIÓN: CUPS: {{isset($datos['cups_pnp'])?$datos['cups_pnp']:''}}</div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14"><strong style="font-size: 20px;">MOPRI</strong></div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 30%;"  colspan="5"> CÓDIGO UNICO DE RENIPRESS</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;"  colspan="7">NOMBRE  DE LA IPRESS QUE REALIZA LA ATENCIÓN</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 10%;">FECHA INICIO</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 10%;">FECHA DE CULMINO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 30%;"  colspan="5">{{isset($datos['codigo_renipres'])?$datos['codigo_renipres']:''}}</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="7">{{$datos['nombre_ipres']}}</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 10%;">{{ date("d-m-Y", strtotime($datos['fecha_inicio_intervencion'])) }}</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 10%;"> {{ !empty($datos['fecha_fin_intervencion']) ? date("d-m-Y", strtotime($datos['fecha_fin_intervencion'])) : '' }}</div>
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
               
              </tr>
              <tr>
                <td class="celda_plomo1" style="width: 1%;">TDI</td>
                <td class="celda_plomo1" style="width: 5%;"> N° DOCUMENTO DE IDENTIDAD</td>
                <td class="celda_blanco1" rowspan="2">{{$datos['nro_historia']}}</td>
                <td class="celda_blanco1" style="width: 2%;">Masculino</td>
                <td class="celda_blanco1" style="width: 1%;">{{ $datos['sexo'] == 'M' ? 'X' : '' }}</td>
                <td class="celda_blanco1" style="width: 1%;">AÑOS</td>
                <td class="celda_blanco1" style="width: 1%;">MESES</td>
                <td class="celda_blanco1" style="width: 1%;">DIAS</td>
                <td class="celda_blanco1" rowspan="2">{{$datos['servicio_encargado']}}</td>
               
              </tr>
              <tr>
                <td class="celda_blanco1" style="width: 1%;">{{$datos['tipo_doc_ident']}}</td>
                <td class="celda_blanco1" style="width: 5%;">{{$datos['dni']}}</td>
                <td class="celda_blanco1" style="width: 2%;">Femenino</td>
                <td class="celda_blanco1" style="width: 1%;">{{ $datos['sexo'] == 'F' ? 'X' : '' }}</td>
                <td class="celda_blanco1" style="width: 1%;">{{$datos['anio_nac']}}</td>
                <td class="celda_blanco1" style="width: 1%;">{{$datos['mes_nac']}}</td>
                <td class="celda_blanco1" style="width: 1%;">{{$datos['dia_nac']}}</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;"  colspan="5"> APELLIDO PATERNO</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;"  colspan="9">APELLIDO MATERNO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="5">{{$datos['paciente_paterno']}}</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="9">{{$datos['paciente_materno']}}</div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 100%;"  colspan="14"> NOMBRES</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="14">{{$datos['paciente_nombre']}}</div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14"> DATOS DEL TITULAR</div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 20%;"  colspan="3"> TITULAR</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 20%;"  colspan="3">CIP</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 20%;"  colspan="2">GRADO</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 20%;"  colspan="3">SITUACIÓN</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 20%;"  colspan="3">UNIDAD</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 20%;"  colspan="3">{{$datos['titular_nombre']}} {{$datos['titular_paterno']}} {{$datos['titular_materno']}}</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 20%;"  colspan="3">{{$datos['cip']}}</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 20%;"  colspan="2">{{$datos['grado']}}</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 20%;"  colspan="3">{{$datos['situacion_paciente']}}</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 20%;"  colspan="3">{{isset($datos['unidad_pnptitular'])?$datos['unidad_pnptitular']:''}}</div>
        </div>
      
        <div class="fila">
          <div class="celda titulo" colspan="14"> INFORMACION DE LA INTERVENCIÓN QUIRURGICA</div>
        </div>
        <div class="fila">
            <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
                <table style="width: 100%;" border="1">
                <tr>
                    <td class="celda_plomo1" style="width: 5%;">SERVICIO DE</td>
                    <td class="celda_plomo1" style="width: 5%;">CIRUJANO QUE INDICA LA OPERACIÓN</td>
                    <td class="celda_plomo1"  style="width: 5%;">DATOS CLINICOS MAS IMPORTANTES</td>
                </tr>
                <tr>
                    <td class="celda_blanco1" style="width: 5%;">{{$datos['servicio_encargado']}}</td>
                    <td class="celda_blanco1" style="width: 5%;">{{$datos['p_medico_nombre']}} {{$datos['p_medico_apaterno']}} {{$datos['p_medico_materno']}}</td>
                    <td class="celda_blanco1" style="width: 5%;">{{$datos['datos_clinicos_importantes']}}</td>
                </tr>
                </table>
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
            @if($datos['pre_diagonosticos_emergencia'])
                @foreach($datos['pre_diagonosticos_emergencia'] as $key=> $dig)
                        @if(isset($dig['diagnosticos_a']['codigo']) && trim($dig['diagnosticos_a']['codigo']) !== '')
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$dig['diagnosticos_a']['codigo']}}</td>
                            <td>{{$dig['diagnosticos_a']['nombre']}}</td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 14px;">P</div>
                                    <div class="marca">X</div>
                                </div>
                            </td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 12px;">D</div>
                                    <div class="marca"></div>
                                </div>
                            </td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 12px;">R</div>
                                    <div class="marca"></div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @if(isset($dig['diagnosticos_b']['codigo']) && trim($dig['diagnosticos_b']['codigo']) !== '')
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$dig['diagnosticos_b']['codigo']}}</td>
                            <td>{{$dig['diagnosticos_b']['nombre']}}</td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 14px;">P</div>
                                    <div class="marca">X</div>
                                </div>
                            </td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 12px;">D</div>
                                    <div class="marca"></div>
                                </div>
                            </td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 12px;">R</div>
                                    <div class="marca"></div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @if(isset($dig['diagnosticos_c']['codigo']) && trim($dig['diagnosticos_c']['codigo']) !== '')
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$dig['diagnosticos_c']['codigo']}}</td>
                            <td>{{$dig['diagnosticos_c']['nombre']}}</td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 14px;">P</div>
                                    <div class="marca">X</div>
                                </div>
                            </td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 12px;">D</div>
                                    <div class="marca"></div>
                                </div>
                            </td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 12px;">R</div>
                                    <div class="marca"></div>
                                </div>
                            </td>
                        </tr>
                        @endif
                @endforeach
            @elseif($datos['pre_diagonosticos_consultaexterna'])
                @foreach($datos['pre_diagonosticos_consultaexterna'] as $key=> $diag)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$diag['codigo']}}</td>
                            <td>{{$diag['nombre']}}</td>
                            <td>
                                <div class="contenedor">
                                    <div class="valor" style="font-size: 14px;">P</div>
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
              @if(isset($proc['flag_pre_post']))
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
                @endif
                @endforeach
                @endif
              </tr>
            </table>
          </div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
              <table style="width: 100%;" border="1">
              <tr>
                  <td class="celda_plomo1" style="width: 25%;">PRIORIDAD DE PROGRAMACION EN SEMANAS</td>
                  <td class="celda_plomo1" style="width: 25%;">HACER OPERADO EN</td>
                  <td class="celda_plomo1"  style="width: 25%;">REQUIERE INTERVENCION EN EL PRE-QUIRURGICO</td>
                  <td class="celda_plomo1"  style="width: 25%;">DIAS DE HOSPITALIZACION POST-QUIRURGICA</td>
              </tr>
              <tr>
                  <td class="celda_blanco1" style="width: 25%;">
                    <table style="border-collapse: collapse;">
                      <tr>
                        <td style="padding-left: 20px;">
                          <div class="contenedor">
                            <div class="valor2" style="font-size: 14px;">SI</div>
                            <div class="marca">{{ isset($datos['prioridad_programacion']) ? 'X' : '' }}</div>
                          </div>
                        </td>
                        <td style="padding-right:50px;">
                            {{ isset($datos['prioridad_programacion']) ? $datos['prioridad_programacion'] : '' }}
                        </td>
                          <td>
                          <div class="contenedor">
                            <div class="valor2" style="font-size: 14px;">NO</div>
                            <div class="marca">{{ !isset($datos['prioridad_programacion']) ? 'X' : '' }}</div>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class="celda_blanco1" style="width: 25%;">
                  @if(!empty($datos['lugar_operacion']) && $datos['lugar_operacion'] == 1) HOSPITALIZACIÓN @endif
                  @if(!empty($datos['lugar_operacion']) && $datos['lugar_operacion'] == 2) CLINICA DE DÍA @endif
                  </td>
                  <td class="celda_blanco1" style="width: 25%;">
                    <table>
                      <tr>
                        <td style="padding-left: 20px;">
                          <div class="contenedor">
                            <div class="valor2" style="font-size: 14px;">SI</div>
                            <div class="marca">{{ !empty($datos['tiempo_intervencion']) ? 'X' : '' }}</div>
                          </div>
                        </td>
                        <td style="padding-right:50px;">
                            {{ !empty($datos['tiempo_intervencion']) ? $datos['tiempo_intervencion'] : '' }}
                        </td>
                          <td>
                          <div class="contenedor">
                            <div class="valor2" style="font-size: 14px;">NO</div>
                            <div class="marca">{{ empty($datos['tiempo_intervencion']) ? 'X' : '' }}</div>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class="celda_blanco1" style="width: 25%;">
                    <table style="border-collapse: collapse;">
                      <tr>
                        <td style="padding-left: 20px;">
                          <div class="contenedor">
                            <div class="valor2" style="font-size: 14px;">SI</div>
                            <div class="marca">{{ !empty($datos['dias_hospitalizacion']) ? 'X' : '' }}</div>
                          </div>
                        </td>
                        <td style="padding-right:50px;">
                          <div  style="margin: 0; padding: 0;">
                            {{ !empty($datos['dias_hospitalizacion']) ? $datos['dias_hospitalizacion'] : '' }}
                          </div>
                        </td>
                          <td>
                          <div class="contenedor">
                            <div class="valor2" style="font-size: 14px;">NO</div>
                            <div class="marca">{{ empty($datos['dias_hospitalizacion']) ? 'X' : '' }}</div>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
              </tr>
              </table>
          </div>
        </div>
           
        <div class="fila">
          <div class="celda titulo" colspan="14">  HISTORIA CLINICA</div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 30%;"  colspan="3">ENFERMEDAD ACTUAL</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 70%;"  colspan="11">{{$datos['enfermedad_actual']}}</div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14">ANTECEDENTES PATOLÓGICOS</div>
        </div>
        <div class="fila">
            <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
              <table style="width: 100%;" border="1">
                <tr>
                    <td class="celda_plomo1" style="text-align: left;">
                        A) PERSONALES: (ENFERMEDAD)(OPERACIONES)/ALERGIAS, MEDICAMENTOS Y OTROS)
                    </td>
                </tr>
                <tr>
                    <td class="celda_blanco1" style="text-align: left;">
                        {{ $datos['antecedente_personal'] }}
                    </td>
                </tr>
                <tr>
                    <td class="celda_plomo1" style="text-align: left;">
                        B) FAMILIARES
                    </td>
                </tr>
                <tr>
                    <td class="celda_blanco1" style="text-align: left;">
                        {{ $datos['antecedente_familiar'] }}
                    </td>
                </tr>
              </table>
            </div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 30%;"  colspan="3">EXAMEN FISICO</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 70%;"  colspan="11">{{$datos['examen_fisico']}}</div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 30%;"  colspan="3">IMPRESIÓN DIAGNOSTICA</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 70%;"  colspan="11"></div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="3">RIESGO QUIRÚRGICO</div>
          <div class="celda titulo" colspan="6">CONTENIDO</div>
          <div class="celda titulo" colspan="5">MEDICO</div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 20%;"  colspan="3">CARDIOLOGÍA</div>
          <div class="celda_blanco1 titulo" style="text-align: left; width: 60%;"  colspan="6">
           {{$datos['cardio_plan_trabajo']}}
           <br>
           {{ !empty($datos['cardio_fecha_registro']) ? 'FECHA: ' . \Carbon\Carbon::parse($datos['cardio_fecha_registro'])->format('d-m-Y') : '' }} {{ !empty($datos['cardio_orden_riesgo']) ? 'RIESGO: ' . $datos['cardio_orden_riesgo'] : '' }}
          </div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 20%; font-size: 9px;"  colspan="5">
            <table>
              <tr><td>{{ !empty($datos['medico_cardio']) ? $datos['medico_cardio'] : '' }} - {{ !empty($datos['medico_cardio_colegiatura']) ? $datos['medico_cardio_colegiatura'] : '' }} - {{ !empty($datos['medico_cardio_rne']) ? $datos['medico_cardio_rne'] : '' }}</td></tr>
            </table> 
          </div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 20%;"  colspan="3">NEOMOLOGÍA</div>
          <div class="celda_blanco1 titulo" style="text-align: left; width: 60%;"  colspan="6">
            {{$datos['neumo_plan_trabajo']}}
           <br>
          {{ !empty($datos['neumo_fecha_registro']) ? 'FECHA: ' . \Carbon\Carbon::parse($datos['neumo_fecha_registro'])->format('d-m-Y') : '' }} {{ !empty($datos['neumo_orden_riesgo']) ? 'RIESGO: ' . $datos['neumo_orden_riesgo'] : '' }}
          </div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 20%; font-size: 9px;"  colspan="5">
            <table>
              <tr><td>{{ !empty($datos['medico_neumo']) ? $datos['medico_neumo'] : '' }} - {{ !empty($datos['medico_neumo_colegiatura']) ? $datos['medico_neumo_colegiatura'] : '' }} - {{ !empty($datos['medico_neumo_rne']) ? $datos['medico_neumo_rne'] : '' }}</td></tr>
            </table> 
          </div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 20%;"  colspan="3">ANESTESIOLOGÍA</div>
          <div class="celda_blanco1 titulo" style="text-align: left; width: 60%;"  colspan="6">
            {{!empty($datos['anestesio_plan_trabajo'])?$datos['anestesio_plan_trabajo']:''}}
           <br>
           {{ !empty($datos['anestesio_fecha_registro']) ? 'FECHA: ' . \Carbon\Carbon::parse($datos['anestesio_fecha_registro'])->format('d-m-Y') : '' }} {{ !empty($datos['anestesio_orden_riesgo']) ? 'RIESGO: ' . $datos['anestesio_orden_riesgo'] : '' }} 
          </div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 20%; font-size: 9px;"  colspan="5">
            <table>
                <tr><td>{{ !empty($datos['medico_anestesio']) ? $datos['medico_anestesio'] : '' }} - {{ !empty($datos['medico_anestesio_colegiatura']) ? $datos['medico_anestesio_colegiatura'] : '' }} - {{ !empty($datos['medico_anestesio_rne']) ? $datos['medico_anestesio_rne'] : '' }}</td></tr>
            </table> 
          </div>
        </div>
        <!-- LISTADO DE INTERCONSULTAS -->
        @if($datos['cita_interconsultas'])
              @foreach($datos['cita_interconsultas'] as $key=> $inter)
                <div class="fila">
                  <div class="celda_plomo1 titulo" style="text-align: center; width: 20%;"  colspan="3">{{!empty($inter['especialidad'])?$inter['especialidad']:''}}</div>
                  <div class="celda_blanco1 titulo" style="text-align: left; width: 60%;"  colspan="6">
                    {{!empty($inter['plan_trabajo'])?$inter['plan_trabajo']:''}}
                  <br>
                  {{ !empty($inter['fecha']) ? 'FECHA: ' . \Carbon\Carbon::parse($inter['fecha'])->format('d-m-Y') : '' }} {{ !empty($inter['orden_riesgo']) ? 'RIESGO: ' . $inter['orden_riesgo'] : '' }} 
                  </div>
                  <div class="celda_blanco1 titulo" style="text-align: center; width: 20%; font-size: 9px;"  colspan="5">
                    <table>
                        <tr><td>{{ !empty($inter['medico']) ? $inter['medico'] : '' }} - {{ !empty($inter['colegiatura']) ? $inter['colegiatura'] : '' }} - {{ !empty($inter['rne']) ? $inter['rne'] : '' }}</td></tr>
                    </table> 
                  </div>
              </div>
            @endforeach
        @endif
    </div>
</body>

</html> 