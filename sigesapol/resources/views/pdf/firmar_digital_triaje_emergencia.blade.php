<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TRIAJE EMERGENCIA</title>
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
  </style>
</head>

<body style="font-size: 12px; font-family: arial;padding:-10px !important;background-color:white !important">
  <table style="margin:0px;padding:0px;padding-bottom:10px;">
    <tbody>
      <tr>
        <td colspan="2" class="" width="10%">
        </td>
        <td colspan="2" class="" width="80%">
          <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">{{$nombre_establecimiento}}</span>
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">SERVICIO DE EMERGENCIA - TRIAJE</span>
        </td>
        <td colspan="2" width="50%"> 
        </td> 
      </tr>
    </tbody>
  </table>

  <table style="margin:0px;padding:0px;padding-bottom:-25px;">
    <tbody>
        <tr> 
            <td colspan="2" width="50%" >
                <span style="margin: 0; font-size: 0.9rem; font-weight: bold; font-size: 20px !important;">
                    HOJA DE TRIAJE
                </span>
            </td> 
        </tr>
    </tbody>
  </table>
  <table style="margin:0px;padding:0px;padding-bottom:-5px;">
    <tbody>
        <tr>
            <td colspan="2" width="20%" >
            </td>
            <td colspan="2" width="50%" style="vertical-align:bottom;"> 
              <img src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
            </td>
            <td colspan="2" width="30%" >
                <div>
                    <table style="margin:0px;padding:0px;" >
                        <tbody>
                          <tr>
                              <td width="30%" style="text-align:left;" ><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">H.C:</span></td>
                              <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">  <b>{{$emergencia_triaje->dni_beneficiario}}</b></span></td>
                          </tr>
                          <tr>
                              <td width="30%" style="text-align:left;" ><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">H.C.A:</td>
                              <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">  <b>{{$emergencia_triaje->nro_historia_paciente}}</b></td>
                          </tr>
                          <tr>
                              <td width="30%" style="text-align:left;" ><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">N° ORDEN:</span></td>
                              <td width="70%" style="text-align:left;"> <b style="font-size: 0.9rem;"> {{str_pad($emergencia_triaje->secuencia, 8, '0', STR_PAD_LEFT)}}</b></td>
                          </tr>
                            
                        </tbody>
                    </table>
                </div>
            </td>  
        </tr>
    </tbody>
  </table>
  <table  style="margin:0px;padding:-5px;" >
    <tr>
      <td width="50%" style="vertical-align:top;"> 
      </td>
      <td width="21.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem; font-weight: bold;  padding-top: 1.3rem; text-align:left;">
          FECHA/HORA:   {{$emergencia_triaje->fecha_registro_ingreso}}&nbsp;&nbsp;{{$emergencia_triaje->hora_registro_ingreso}}</span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="52%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">I.- PACIENTE: {{$emergencia_triaje->nombre_asegurado}}</span>
      </td>
      <td width="10%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: right; ">SEXO : <b>{{$emergencia_triaje->sexo}}</b></span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem;text-align: right; padding-right: -5px;">EDAD: <b>{{$emergencia_triaje->edadpaciente}} AÑOS</b></span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="35%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">PARENTESCO: <b>{{$emergencia_triaje->parentesco}}</b></span>
      </td>
      <td width="40%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">ESTADO CIVIL: <b>{{$apersonal['estado_civil']}}</b></span>
      </td>
      <td width="40%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: right;padding-right: -5px;">TELÉFONO   : <b>{{$emergencia_triaje->telefono}}</b></span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px; border-bottom: 1" >
    <tr>
      <td width="100%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">
          DIRECCIÓN: <b>{{$apersonal['direccionDomicilio']}} - ({{$apersonal['departamentoDomicilio']}}/{{$apersonal['provinciaDomicilio']}}/{{$apersonal['distritoDomicilio']}})</b></span>
      </td>  <br>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">III.- INGRESO:</span>
      </td> 
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">FORMA DE INGRESO:  <b>{{$emergencia_triaje->descripcion_forma_ingreso}}</b></span>
      </td>
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">ESTADO DE INGRESO:  <b>{{$emergencia_triaje->descripcion_estado_ingreso}}</b></span>
      </td>
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">MOTIVO DE INGRESO:  <b>{{$emergencia_triaje->descripcion_motivo_ingreso}}</b></span>
      </td> 
    </tr>
  </table>
  <hr/>
  
  @if($emergencia_triaje->nombre_asegurado_titular != null)
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">III.- TITULAR: <b>{{$emergencia_triaje->nombre_asegurado_titular}}</b></span>
      </td>
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: left"> GRADO: <b>{{$emergencia_triaje->grado_asegurado_titular}}</b></span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="33.3%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> DNI: <b>{{$emergencia_triaje->nro_doc_asegurado_titular}}</b></span>
      </td>
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">CIP:  <b>{{$emergencia_triaje->cip_asegurado_titular}}</b></span>
      </td>
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">SITUACIÓN: <b>{{$emergencia_triaje->situacion_paciente_titular}}</b>  </span>
      </td>
    </tr>
  </table> 
  <hr/>
  @endif
<table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="70%" style="vertical-align:top;">
        @if($emergencia_triaje->nombre_asegurado_titular != null)
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">IV.- SIGNOS VITALES: </span>
        @else
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">III.- SIGNOS VITALES: </span>
        @endif
      </td>
      <td width="30%">
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="20%" style="vertical-align:top;">
        <span style="display: block; font-size: 1.0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">PA: <b>{{$emergencia_triaje->presion_arterial_x}}/{{$emergencia_triaje->presion_arterial_y}} mmHg</b></span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 1.0.9rem;  padding-top: 0.3rem;">FC: <b>{{$emergencia_triaje->frecuencia_cardiaca}}/min</b></span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 1.0.9rem;;  padding-top: 0.3rem;">FR: <b>{{$emergencia_triaje->frecuencia_respiratoria}}/min</b></span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 1.0.9rem;;  padding-top: 0.3rem;">FiO2: <b>{{$emergencia_triaje->fi02}}%</b></span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 1.0.9rem;;  padding-top: 0.3rem;">SaO2: <b>{{$emergencia_triaje->sa02_porcentaje}}%</b></span>
      </td>
    </tr>
  </table>

  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="18.5%" style="vertical-align:top;">
        <span style="display: block; font-size: 1.0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">TM: <b>{{$emergencia_triaje->temperatura}} (°C)</b></span>
      </td>
      <td width="22%">
        <span style="display: block; margin: 0; font-size: 1.0.9rem;  padding-top: 0.3rem;">
          @if($emergencia_triaje->talla_cm==0)
            TL: <b>0.00 (cm)</b></span>
          @else
            TL: <b>{{$emergencia_triaje->talla_cm}} (cm)</b></span>
          @endif
      </td>
      <td width="22%">
        <span style="display: block; margin: 0; font-size: 1.0.9rem;;  padding-top: 0.3rem;">PS: <b>{{$emergencia_triaje->peso_kg}} (Kg)</b></span>
      </td>  

      @if($emergencia_triaje->es_gestante=='1') 
      <td width="22%">
        <span style="display: block; margin: 0; font-size: 1.0.9rem;  padding-top: 0.3rem;">
          FCF: <b>{{$emergencia_triaje->frecuencia_cardiaca_fetal}}/min</b></span>
      </td> 
      <td width="22%" style="vertical-align:top;">
        <span style="display: block; font-size: 1.0.9rem; margin: 0; padding-top: 0.3rem; text-align: left">
          TC (cm):<b>{{$emergencia_triaje->tamano_cintura_cm}}cm</b></span>
      </td> 
      @else
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 1.0.9rem;  padding-top: 0.3rem;color: white;">
          FCF: <b>{{$emergencia_triaje->frecuencia_cardiaca_fetal}}/min</b></span>
      </td> 
      <td width="20%" style="vertical-align:top;">
        <span style="display: block; font-size: 1.0.9rem; margin: 0; padding-top: 0.3rem; ; color: white;">
          TC (cm):<b>{{$emergencia_triaje->tamano_cintura_cm}}cm</b></span>
      </td> 
      @endif
    </tr>
  </table>
  <hr/>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="70%" style="vertical-align:top;">
        @if($emergencia_triaje->nombre_asegurado_titular != null)
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">V.TRIAJE </span>
        @else
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">IV.TRIAJE </span>
        @endif
      </td>
      <td width="30%">
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">PRIORIDAD: <b>{{$emergencia_triaje->descripcion}}</b></span>
      </td>
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left;">SERVICIO: <b>{{$emergencia_triaje->descripcion_topico}}</b></span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>  
      <td width="100%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">
          MOTIVO DE CONSULTA: <b  style="text-transform: uppercase;">{{$emergencia_triaje->observacion_triaje}}</b></span>
      </td>
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
</footer>  
</body>
</html>
