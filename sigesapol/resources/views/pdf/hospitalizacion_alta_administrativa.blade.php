<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="utf-8">
  <title>
    PDF - ALTA - {{$hospitalizacion->tipo_egreso}}
  </title>
  <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
  <style>
    .td_left {
      text-align: left !important;
    }
    .td_ancho_n {
      width: 4%;
      text-align: center
    }
    .page_break {
    page-break-before: always;
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

    .grado {
      width: 14%;
    }

    .dni {
      width: 8%;
    }

    .rectangulo {
      padding: 3px
    }

    footer { 
      border: 0px;
      margin-left: 10px;
      font-size: 7px !important;
      font-family: Arial, Helvetica, sans-serif
    }
    /* agregado */
    #tbl_hospitalizacion td,
    #tbl_hospitalizacion_atencion td {
          text-align: left;
        }
    table#tbl_hospitalizacion_footer {
        margin-bottom: 0 !important;
      }
    #tbl_hospitalizacion_footer td {
        border: 1px black solid !important;
        font-size: 9px;
        padding-top: 1px;
        padding-bottom: 1px;
        text-align: center;
        margin-bottom: 0 !important;
      }
  </style>
</head>
<body style="font-size: 12px; font-family: arial;padding:-10px !important;background-color:white !important">
  <table style="margin:0px;padding:0px;padding-bottom:10px;">
    <tbody>
      <tr>
        <td colspan="2" class="" width="10%"><img src="images/logo_sanidad.png" width="40rem"/></td>
        <td colspan="2" class="" width="80%">
          <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">{{$hospitalizacion->establecimiento}}</span>
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">SERVICIO DE HOSPITALIZACIÓN - ALTA ADMINISTRATIVA</span>
        </td>
        <td colspan="2" width="50%"></td>
      </tr>
    </tbody>
  </table>

  <table style="margin:0px;padding:0px;padding-bottom:-25px;">
    <tbody>
      <tr> 
        <td colspan="2" width="50%" >
          <span style="margin: 0; font-size: 0.9rem; font-weight: bold; font-size: 17px !important;">HOJA DE ALTA ADMINISTRATIVA<br></span>
          <span style="margin: 0; font-weight: bold; font-size: 9px !important">CUPS PNP {{$hospitalizacion->cups_pnp}}</span><br>
          <span style="margin: 0; font-weight: bold; font-size: 8px !important">COD.SITEDS {{$hospitalizacion->cod_siteds_susalud}}</span>
        </td> 
      </tr> 
    </tbody>
  </table>

  <table style="margin:0px;padding-top:30px;">
    <tbody>
        <tr>
          <td width="75%"></td>
          <td width="25%">
            <table style="margin:0px;padding:0px;" >
              <tbody>
                <tr>
                    <td width="40%" style="text-align:left;" ><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">H.C:</span></td>
                    <td width="60%" style="text-align:left;"><span style="margin: 0; font-size: 0.9rem; font-weight: bold;"><b>{{$hospitalizacion->paciente_ndocumento}}</b></span></td>
                </tr>  
                <tr>
                    <td width="40%" style="text-align:left;" ><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">H.C.A:</td>
                    <td width="60%" style="text-align:left;"><span style="margin: 0; font-size: 0.9rem; font-weight: bold;"><b>{{$hospitalizacion->paciente_nhistoria}}</b></td>
                </tr>
                <tr>
                    <td width="40%" style="text-align:left;" ><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">N° ORDEN:</span></td>
                    <td width="60%" style="text-align:left;"> <b style="font-size: 0.9rem;"> {{str_pad($hospitalizacion->secuencia, 8, '0', STR_PAD_LEFT)}}</b></td>
                </tr>   
              </tbody>
            </table>
          </td>  
        </tr>
    </tbody>
  </table>

  <table  style="margin:0px;padding:-5px;" >
    <tr>
      
      <td style="text-align:right !important;">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 1.3rem; text-align:left;">
          FECHA Y HORA INGRESO: <b>{{date("d/m/Y", strtotime($hospitalizacion->fecha_ingreso))}} {{date("H:i:s", strtotime($hospitalizacion->hora_ingreso))}}</b>
        </span>
      </td>
    </tr>
  </table>
  {{-- ANTERIOR ABAJO --}}
  {{-- <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="59%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> 
          PACIENTE: {{$hospitalizacion->paciente_paterno}} {{$hospitalizacion->paciente_materno}} {{$hospitalizacion->paciente_nombre}}
        </span>
      </td>
      <td width="16%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: left; "> DNI : <b>{{$hospitalizacion->paciente_ndocumento}}</b></span>
      </td>
      @if($tipo_beneficiario->id == '8')
      <td width="15%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">CIP:  <b>{{$hospitalizacion->titular_cip}}</b></span>
      </td>
      @endif
      <td width="10%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem;text-align: right;">SEXO: <b>{{$hospitalizacion->paciente_sexo}}</b></span>
      </td>
    </tr>
  </table>

  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="35%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">PARENTESCO: <b>{{$hospitalizacion->paciente_parentesco}}</b></span>
      </td>
      @if($tipo_beneficiario->id == '8')
      <td width="35%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: center">GRADO: <b>{{$hospitalizacion->titular_grado}}</b></span>
      </td>
      @endif
      <td width="30%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem;text-align: right;">EDAD: <b>{{$hospitalizacion->paciente_edad_ingreso}}</b></span>
      </td>
    </tr>
  </table>

  @if($tipo_beneficiario->id != 8)
    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> TITULAR: 
            <b>{{$hospitalizacion->titular_paterno}} {{$hospitalizacion->titular_materno}} {{$hospitalizacion->titular_nombre}}</b>
          </span>
        </td>
        <td width="50%">
          <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: right"> DNI: <b>{{$hospitalizacion->titular_ndocumento}}</b></span>
        </td>
      </tr>
    </table>
  @endif --}}
{{-- ANTERIOR ARRIBA --}}
  <table id="tbl_hospitalizacion" >
    <tr>
        <th style="width:38%"></th>
        <th style="width:38%"></th>
        <th style="width:2%"></th>
        <th style="width:7%"></th>
        <th style="width:15%"></th>
    </tr>
    <tr>
        <td colspan="5" class="subtitulo_1"><b>DATOS DEL PACIENTE</b></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
  </tr>
    <tr>
        <td colspan="2">Apellidos y Nombres: <b>{{$hospitalizacion->paciente_paterno}} {{$hospitalizacion->paciente_materno}} {{$hospitalizacion->paciente_nombre}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @if($titular->dni_titular != $hospitalizacion->paciente_ndocumento)
    <tr>
      <td colspan="2">Apellidos y Nombres del titular: <b>{{$titular->paterno}} {{$titular->materno}} {{$titular->nombre}}</b></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    @endif
    <tr>
      <td colspan="2">Parentesco con el paciente: <b>{{$hospitalizacion->paciente_parentesco}}</b></td>
      <td></td>
      <td><b>DNI:</b></td>
      <td>{{$hospitalizacion->paciente_ndocumento}}</td>
    </tr>
    <tr>
        <td>Edad: <b>{{$hospitalizacion->paciente_edad_ingreso}}</b></td>
        <td>Sexo: <b>{{$hospitalizacion->paciente_sexo}}</b></td>
        <td></td>
        <td><b>CIP</b></td>
        <td>@if(isset($persona_datos_adicionales->cip)) {{$persona_datos_adicionales->cip}}@endif</td>
    </tr>
    <tr>
        <td>Raza: <b>{{$asegurado->etnia}}</b></td>
        <td>Estado civil: <b>{{$asegurado->estado_civil}}</b></td>
        <td></td>
        <td>{{-- <b>HC</b> --}}</td>
        <td>{{-- {{$hospitalizacion->nro_historia}} --}}</td>
    </tr>
    <tr>
        <td>Grado de Instrucción: <b>{{$asegurado->grado_instruccion}}</b></td>
        <td>Ocupación: <b>{{$asegurado->ocupacion}}</b></td>
        <td></td>
        <td><b>RX N°</b></td>
        <td>-</td>
    </tr>
    <tr>
        <td>Lugar de Nacimiento: <b>{{$asegurado->departamento_nacimiento2}} / {{$asegurado->provincia_nacimiento2}} / {{$asegurado->distrito_nacimiento2}} / {{strtoupper($asegurado->direccion_nacimiento)}}</b></td>
        <td>Fecha de nacimiento: <b>{{$asegurado->fecha_nac}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Procedencia: <b>{{$asegurado->departamento_procedencia2}} / {{$asegurado->provincia_procedencia2}} / {{$asegurado->distrito_procedencia2}} / {{strtoupper($asegurado->direccion_procedencia)}}</b></td>
        <td>Religión: <b>{{$asegurado->religion}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">Domicilio: <b>{{strtoupper($asegurado->domicilio)}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Distrito: <b>{{$asegurado->distrito}}</b></td>
        <td>Departamento: <b>{{$asegurado->departamento}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Teléfono fijo: <b>{{$asegurado->telefono}}</b></td>
        <td>Celular: <b>{{$asegurado->telefono2}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    {{-- <tr>
        <td colspan="2">Apellidos y Nombres del titular: <b>{{$titular->paterno}} {{$titular->materno}} {{$titular->nombre}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr> --}}
    {{-- <tr>
        <td colspan="2">Grado de Situación del titular: <b>@if(isset($grado->descripcion_corta)) {{$grado->descripcion_corta}}@else SIN ESPECIFICAR @endif</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr> --}}
    {{-- <tr>
        <td colspan="2">Parentesco con el paciente: <b>{{$hospitalizacion->tipo_beneficiario}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr> --}}
    <tr>
        <td colspan="2">Fecha y hora de ingreso: <b>{{$hospitalizacion->fecha_ingreso}} {{$hospitalizacion->hora_ingreso}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">Fecha y hora de Historia Clínica: <b>{{$hospitalizacion->created_at}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">Código SITEDS: <b>{{$hospitalizacion->cod_siteds_susalud}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">CUPS: <b>{{$hospitalizacion->cups_pnp}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

  </table>
  <hr/>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> RESPONSABLE DEL ALTA: 
          <b>{{$hospitalizacion->medico_egreso_paterno}} {{$hospitalizacion->medico_egreso_materno}} {{$hospitalizacion->medico_egreso_nombre}}</b>
        </span>
      </td>
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: right"> DNI: <b>{{$hospitalizacion->medico_egreso_ndocumento}}</b></span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="35%" style="vertical-align:top;">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: left"> ESPECIALIDAD: <b>{{$hospitalizacion->medico_egreso_especialidad}}</b>
        </span>
      </td>
      <td width="35%" style="vertical-align:top;">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: left"> 
          PROFESION: <b>{{$hospitalizacion->medico_egreso_profesion}}</b>
        </span>
      </td>
      <td width="15%" style="vertical-align:top;">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: left"> 
          N° CMP: <b>{{$hospitalizacion->medico_egreso_colegiatura}}</b>
        </span>
      </td>
      <td width="15%" style="vertical-align:top;">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: right"> 
          N° RNE: <b>{{$hospitalizacion->medico_egreso_rne}}</b>
        </span>
      </td>
    </tr>
  </table>
  
  <hr/>

  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="70%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">ALTA MEDICA </span>
      </td>
    </tr>
  </table>

  
  
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="33.3%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> 
          PABELLON: <b>{{$hospitalizacion->pabellon}}</b>
        </span>
      </td>
      <td width="33.3%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: right"> 
          PISO: <b>{{$hospitalizacion->piso}}</b>
        </span>
      </td>
      <td width="33.3%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: right"> 
          HABITACION : <b>{{$hospitalizacion->cuarto}}</b>
        </span>
      </td>
      <td width="33.3%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: right"> 
          CAMA/CAMILLA : <b>{{$hospitalizacion->cama}}</b>
        </span>
      </td>
    </tr>
  </table>
      <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="33.3%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> 
            FECHA Y HORA EGRESO MÉDICO: <b><?php echo date("d-m-Y", strtotime($hospitalizacion->fecha_alta_medica)); ?> <?php echo date("H:i:s", strtotime($hospitalizacion->hora_alta_medica)); ?></b>
          </span>
        </td>
        <td width="33.3%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: right"> 
            FECHA Y HORA EGRESO ADMINISTRATIVO : <b><?php echo date("d-m-Y", strtotime($hospitalizacion->fecha_alta_administrativa)); ?> <?php echo date("H:i:s", strtotime($hospitalizacion->hora_alta_administrativa)); ?></b>
          </span>
        </td>
      </tr>
    </table>
  
  <div style="margin: 10px 0px 10px 0px;">
    <!-- <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="100%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">DETALLES DE LA ALTA </span>
        </td>
      </tr>
    </table> -->
    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> 
            TIPO EGRESO: <b>{{$hospitalizacion->tipo_egreso}}
              <?php 
                /*if($hospitalizacion->id_tipo_egreso=="1"){
                  echo "ALTA";
                }else if($hospitalizacion->id_tipo_egreso=="2"){
                  echo "HOSPITALIZACION";
                }else if($hospitalizacion->id_tipo_egreso=="3"){
                  echo "TRASNFERIDO";
                }else if($hospitalizacion->id_tipo_egreso=="4"){
                  echo "FALLECIDO";
                }else if($hospitalizacion->id_tipo_egreso=="7"){
                  echo "RETIRO VOLUNTARIO";
                }else if($hospitalizacion->id_tipo_egreso=="6"){
                  echo "FUGA";
                } else {
                  echo "-";
                }*/
              ?>
            </b>
          </span>
        </td>
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: right"> 
            CONDICION ALTA: <b>{{$hospitalizacion->condicion_egreso}}
            <?php 
              /*if($hospitalizacion->id_condicion_alta=="1"){
                echo "MEJORADO";
              }else if($hospitalizacion->id_condicion_alta=="2"){
              echo "CURADO";
              }else if($hospitalizacion->id_condicion_alta=="3"){
              echo "TRASLADO";
              }else if($hospitalizacion->id_condicion_alta=="4"){
              echo "RETIRO VOLUNTARIO";
              }else if($hospitalizacion->id_condicion_alta=="5"){
              echo "FUGADO";
              }else if($hospitalizacion->id_condicion_alta=="6"){
              echo "FALLECIDO";
              } else {
              echo "-";
              }*/
            ?>
            </b>
          </span>
        </td>
      </tr>
    </table>
  </div>
<hr />
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="70%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">DIAGNOSTICOS DE EGRESO</span>
    </td>
  </tr>
</table>

<table style="margin:8px 2px; padding:0px;">
  <tr>
    <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000; width: 10%">CODÍGO</th>
    <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000">NOMBRE</th>
    <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000; width: 15%">TIPO</th>
    <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000; width: 15%">CATEGORÍA</th>
  </tr>
  @if ($hospitalizacion->cie_egreso_codigo1 != null)
    <tr>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_codigo1}}</td>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_nombre1}}</td>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">DEFINITIVO</td>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_categoria1}}</td>
    </tr>
  @endif
  @if ($hospitalizacion->cie_egreso_codigo2 != null)
    <tr>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_codigo2}}</td>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_nombre2}}</td>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">DEFINITIVO</td>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_categoria2}}</td>
    </tr>
  @endif
  @if ($hospitalizacion->cie_egreso_codigo3 != null)
    <tr>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_codigo3}}</td>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_nombre3}}</td>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">DEFINITIVO</td>
      <td style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->cie_egreso_categoria3}}</td>
    </tr>
  @endif
</table>

<br>


  <table style="font-size: 7px !important;">   
      <tr>
        <td style="text-align: left;">USUARIO REGISTRO: <b>{{$hospitalizacion->user_paterno}} {{$hospitalizacion->user_materno}}, {{$hospitalizacion->user_nombre}}</b></td>  
        <td style="text-align: right;">FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b></td>
      </tr>
      <tr>
        <td style="text-align: left;">USUARIO DE IMPRESIÓN: <b>{{$user_actual->paterno}} {{$user_actual->materno}}, {{$user_actual->name}}</b></td>
        <td style="text-align: right;">HORA DE IMPRESIÓN: <b>{{$horaActual}}</b></td>
      </tr>
  </table>
</body></html>
