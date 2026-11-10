<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>SERVICIO DE HOSPITALIZACIÓN - ADMISIÓN</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
  <style>
    .td_left {
      text-align: left !important;
    }

    .td_ancho_n {
      width: 4%;
      text-align: center
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
          <td colspan="2" class="" width="10%">
            <img src="images/logo_sanidad.png" width="40rem"/>
          </td>
          <td colspan="2" class="" width="80%">
            <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">{{$nombre_establecimiento}}</span>
            <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">SERVICIO DE HOSPITALIZACIÓN - ADMISIÓN</span>
          </td>
          <td colspan="2" width="50%"></td>
        </tr>
      </tbody>
    </table>

    <table style="margin:0px;padding:0px;">
      <tbody>
        <tr> 
          <td colspan="2" width="50%" >
            <span style="margin: 0; font-size: 0.9rem; font-weight: bold; font-size: 17px !important;">HOJA DE HOSPITALIZACIÓN<br></span>
            <span style="margin: 0; font-weight: bold; font-size: 9px !important">CUPS PNP {{$hospitalizacion->cups_pnp}}</span><br>
            <span style="margin: 0; font-weight: bold; font-size: 8px !important">COD.SITEDS {{$hospitalizacion->cod_siteds_susalud}}</span>
          </td> 
        </tr> 
      </tbody>
    </table>
 
    <table style="margin:0px;padding:0px;;border-color:red">
      <tbody>
          <tr>
              <td colspan="2" width="50%" style="vertical-align:bottom;"> </td>
              <td colspan="2" width="20%" > </td>
              <td colspan="2" width="30%" >
                  <div>
                      <table style="margin:0px;padding:0px;" >
                          <tbody>
                            <tr>
                                <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C:</span></td>
                                <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{$hospitalizacion->dni_beneficiario}}</b></span></td>
                            </tr>
                            <tr>
                                <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A:</td>
                                <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{$hospitalizacion->nro_historia}}</b></td>
                            </tr>
                            <tr>
                                <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size:1.3rem; font-weight: bold;">N° ORDEN:</span></td>
                                <td width="70%" style="text-align:left;"> <b style="font-size: 1.3rem;"> {{str_pad($hospitalizacion->secuencia, 8, '0', STR_PAD_LEFT)}}</b></td>
                            </tr> 
                          </tbody>
                      </table>
                  </div>
              </td>
          </tr>
      </tbody>
    </table>

    <table style="margin:0px;" >
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="text-align:right !important">
          <span style="display: block; margin: 0; font-size: 0.9rem; text-align:left;">
            FECHA / HORA: <b>{{$hospitalizacion->fecha_ingreso}} {{$hospitalizacion->hora_ingreso}}</b></span>
        </td>
      </tr>
    </table>
{{-- ANTERIOR ABAJO --}}
    {{-- <table style="margin:0px;padding:0px;" >
      <tr>
        <td width="100%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; font-weight: bold;">
            I.- PACIENTE: 
            @if($hospitalizacion->tipo_doc_ident)
              {{$hospitalizacion->tipo_doc_ident}} {{$hospitalizacion->dni_beneficiario}} {{$hospitalizacion->nombre_beneficiario}} {{$hospitalizacion->paterno_beneficiario}} {{$hospitalizacion->materno_beneficiario}}</span>
            @else
              {{$hospitalizacion->nombre_asegurado}} </span>
            @endif
        </td> 
        <td width="65%">
          <span style="display: block; margin: 0; font-size: 0.9rem; text-align: right; padding-left: 15px;text-transform: uppercase;">
            EDAD: <b>{{strtoupper($hospitalizacion->fecha_nac_descripcion)}}</b></span>
        </td>
      </tr>
    </table>

    <table style="margin:0px;padding:0px;" >
      <tr> 
        <td width="30%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">PARENTESCO: <b>{{$hospitalizacion->tipo_beneficiario}}</b></span>
        </td>
        <td width="15%"><span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: left; ">SEXO : <b>{{$hospitalizacion->sexo}}</b></span></td>
        <td width="30%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">ESTADO CIVIL: 
            <b>{{$hospitalizacion->estado_civil}}</b>
          </span>
        </td>
        <td width="25%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: right;">
            @if($hospitalizacion->telefono!='')
              TELÉFONO: <b>{{$hospitalizacion->telefono}}</b>
            @endif
          </span>
        </td>
      </tr>
      <tr>
        <td colspan="4" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">
            DIRECCIÓN: 
            <b>{{$hospitalizacion->domicilio_actual}} - ({{$hospitalizacion->departamento_actual}}/{{$hospitalizacion->provincia_actual}}/{{$hospitalizacion->distrito_actual}})</b>
          </span>
        </td>  
      </tr>
    </table> --}}
{{-- ANTERIOR ARRIBA--}}
    <table id="tbl_hospitalizacion" >
      <tr>
          <th style="width:38%"></th>
          <th style="width:38%"></th>
          <th style="width:2%"></th>
          <th style="width:7%"></th>
          <th style="width:15%"></th>
      </tr>
      <tr>
          <td colspan="5" class="subtitulo_1"><b>I.- DATOS DEL PACIENTE</b></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
    </tr>
      <tr>
          <td colspan="2">Apellidos y Nombres: <b>{{$hospitalizacion->paterno_beneficiario}} {{$hospitalizacion->materno_beneficiario}} {{$hospitalizacion->nombre_beneficiario}}</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      @if($titular != null)
      <tr>
        <td colspan="2">Apellidos y Nombres del titular: <b>{{$titular->paterno}} {{$titular->materno}} {{$titular->nombre}}</b></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      @endif
      <tr>
        <td colspan="2">Parentesco del paciente: <b>{{$hospitalizacion->tipo_beneficiario}}</b></td>
        <td></td>
        <td><b>DNI:</b></td>
        <td>{{$hospitalizacion->dni_beneficiario}}</td>
      </tr>
      <tr>
          <td>Edad: <b>{{$hospitalizacion->edad}}</b></td>
          <td>Sexo: <b>{{$hospitalizacion->sexo}}</b></td>
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

    <!-- <table border="1" style="margin:0px;padding:0px; border-bottom: 1" >
      <tr>
        <td width="100%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">
            DIRECCIÓN: 
            <b>{{$hospitalizacion->domicilio_actual}} - ({{$hospitalizacion->departamento_actual}}/{{$hospitalizacion->provincia_actual}}/{{$hospitalizacion->distrito_actual}})</b>
          </span>
        </td>  
      </tr>
    </table> -->

    <table style="margin:0px;padding:0px; border-bottom: 1">
      <tr>
        <td width="100%" style="vertical-align:top;">
          @if ($hospitalizacion->fecha_alta_medica && $hospitalizacion->hora_alta_medica)
            <td width="25%">
              <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">FECHA ALTA:
                <b>{{$hospitalizacion->fecha_alta_medica}} {{$hospitalizacion->hora_alta_medica}}</b> 
              </span>
            </td> 
          @endif
        </td>
      </tr>
    </table>
    <hr/>
    @if($hospitalizacion->dni_titular != null)
    <table style="margin:0px;padding:0px;" >
      <tr>
        <td colspan="2" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">II.- TITULAR: <b>{{$hospitalizacion->nombre_titular}} {{$hospitalizacion->paterno_titular}} {{$hospitalizacion->materno_titular}}</b></span>
        </td>
        <td width="35%">
          <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: left"> GRADO: <b>{{$hospitalizacion->gradotitular}}</b></span>
        </td>
      </tr>
      <tr>
        <td width="30%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> DNI: <b>{{$hospitalizacion->dni_titular}}</b></span>
        </td>
        <td width="35%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">CIP:  <b>{{$hospitalizacion->ciptitular}}</b></span>
        </td>
        <td width="35%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">SITUACIÓN: <b>{{$hospitalizacion->situaciontitular}}</b>  </span>
        </td>
      </tr>
    </table>  
    @endif
    <hr/>
    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">III.- INGRESO:</span>
        </td> 
      </tr>
    </table>

    <!-- <table  style="margin:0px;padding:0px; margin-top: 5px">
      <tr>
        <td width="65%" style="vertical-align:top;">
          <span style="display: block; margin: 0; font-size: 0.9rem;text-align: left">REGISTRADO POR: <b>{{$hospitalizacion->nombre_registrador}}</b></span>
        </td>
        <td width="35%">
          <span style="display: block; margin: 0; font-size: 0.9rem;text-align: left">FEC. INGRESO: <b>{{$hospitalizacion->fecha_ingreso}} {{$hospitalizacion->hora_ingreso}}</b></span>
        </td>
      </tr>
    </table> -->
    <table style="margin:0px;padding:0px;" >
      <tr>
        <td width="30%">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">ORIGEN:  <b>{{$hospitalizacion->tipo_origen}}</b>
          </span>
        </td>
        <td width="35%">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">PABELLON:  <b>{{$hospitalizacion->pabellon}}</b>
          </span>
        </td>
        <td width="35%">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">SERVICIO:  <b>{{$hospitalizacion->nombre_consultorio}}</b>
          </span>
        </td>
      </tr>
      <tr>
        <td>
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">PISO:  <b>{{$hospitalizacion->piso}}</b>
          </span>
        </td>
        <td>
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">CUARTO:  <b>{{$hospitalizacion->cuarto}}</b>
          </span>
        </td>
        <td>
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">CAMA:  <b>{{$hospitalizacion->cama}}</b>
          </span>
        </td>
      </tr>
      <tr>
        <td width="65%" colspan="2">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">UPSS ENCARGADO:  <b>{{$hospitalizacion->descripcion_upss_origen}}</b>
          </span>
        </td>
        <td width="35%">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">SERVICIO ENCARGADO:  <b>{{$hospitalizacion->nombre_servicio_origen}}</b><br>
          </span>
        </td>
      </tr>
      
    </table>
    <hr/>
    {{-- ********************* IV.- PROFESIONAL QUE ORDENA LA HOSPITALIZACIÓN ********************* --}}
    <table style="margin:0px;padding:0px;" >
      <tr>
        <td colspan="4" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">IV.- PROFESIONAL QUE ORDENA LA HOSPITALIZACIÓN: 
            <b>{{$hospitalizacion->nombre_medico_hospitalizacion}} {{$hospitalizacion->paterno_medico_hospitalizacion}} {{$hospitalizacion->materno_medico_hospitalizacion}}</b>
          </span>
        </td>
      </tr>
      <tr>
        <td width="30%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> DNI: <b>{{$hospitalizacion->dni_medico_hospitalizacion}}</b></span>
        </td>
        <td width="35%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> PROFESION: <b>{{$hospitalizacion->profesion}}</b></span>
        </td>
        <td width="20%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">CMP:  <b>{{$hospitalizacion->colegiatura_medico}}</b></span>
        </td>
        <td width="15%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">RNE: <b>{{$hospitalizacion->rne_medico}}</b>  </span>
        </td>
      </tr>
    </table> 
    {{-- ********************* IV.- PROFESIONAL QUE ORDENA LA HOSPITALIZACIÓN  ********************* END --}}

    {{-- ********************* V.- DIAGNÓSTICOS ********************* --}}
    <hr/>
    <table style="margin:0px;padding:0px;" >
      <tr>
        <td width="100%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">IV.- DIAGNÓSTICOS: </span>
        </td>
      </tr>
    </table>
    <table style="margin-top: 5px; margin-left: 2px;">
        <tr>
          <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000; color: #000000;">CIE</th>
          <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000; color: #000000;">DESCRIPCIÓN</th>
          <th style="font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem; padding-left: 0.1rem; border: 1px solid #000000; color: #000000;">TIPO</th>
        </tr>
        @if($hospitalizacion->codigo_dx_1 != null)
        <tr>
          <td style="font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->codigo_dx_1}}</td>
          <td style="font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->descripcion_dx_1}}</td>
          <td style="font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;border: 1px solid #000000;">DEFINITIVO</td>
        </tr>
        @endif
        @if($hospitalizacion->codigo_dx_2 != null)
        <tr>
          <td style="font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->codigo_dx_2}}</td>
          <td style="font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->descripcion_dx_2}}</td>
          <td style="font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;border: 1px solid #000000;">DEFINITIVO</td>
        </tr>
        @endif
        @if($hospitalizacion->codigo_dx_3 != null)
        <tr>
          <td style="font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->codigo_dx_3}}</td>
          <td style="font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;border: 1px solid #000000;">{{$hospitalizacion->descripcion_dx_3}}</td>
          <td style="font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;border: 1px solid #000000;">DEFINITIVO</td>
        </tr>
        @endif
    </table> 
    {{-- ********************* V.- DIAGNÓSTICOS  ********************* END --}}

    
    <table style="margin-top: 10px">
      <tr>
        <td class="td_ancho_espacios"></td>
        <td class="td_ancho_espacios"></td>
      </tr>
    </table> 

    <table>   
        <tr style="font-size: 5px !important; margin-top: 19px !important;">
            <td style="text-align: left"> 
            </td>  
            <td style="text-align: right; padding-right: 10px;">  
                FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b>  <br>
                HORA DE IMPRESIÓN: <b>{{$horaActual}}</b> 
            </td>
        </tr>   
    </table>
    {{-- footer --}}
    <div style="position: absolute; bottom: 0; width: 100%;">
      <table id="tbl_hospitalizacion_footer">
          <tr>
              <td colspan="7" class="td_firma"><br><br><br><br></td>
          </tr>
          <tr>
              <td>
                  <b>Apellido Paterno</b>
              </td>
              <td>
                  <b>Apellido Materno</b>
              </td>
              <td>
                  <b>Nombres</b>
              </td>
              <td style="width: 80px">
                  <b>Historia Clínica</b>
              </td>
              <td style="width: 150px">
                  <b>Servicio</b>
              </td>
              <td style="width: 60px">
                  <b>N° de Cama</b>
              </td>
              <td style="width: 50px">
                  <b>Episodio</b>
              </td>
          </tr>
          <tr>
              <td style="height:22px !important">{{$hospitalizacion->paterno_beneficiario}}</td>
              <td>{{$hospitalizacion->materno_beneficiario}}</td>
              <td>{{$hospitalizacion->nombre_beneficiario}}</td>
              <td>{{$hospitalizacion->nro_historia}}</td>
              <td>{{$hospitalizacion->nombre_consultorio}}</td>
              <td>{{$hospitalizacion->cama}}</td>
              <td></td>
          </tr>
      </table>
  </div>
  </body></html>
