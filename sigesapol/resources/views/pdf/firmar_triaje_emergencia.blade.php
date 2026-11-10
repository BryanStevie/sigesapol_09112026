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

<body style="font-family: arial;padding:-10px !important;background-color:white !important">
 
  <table style="margin:0px;padding:0px;padding-bottom:10px;">
    <tbody>
      <tr>
        <td colspan="2" class="" width="10%">
          <img src="images/logo_sanidad.png" width="40rem"/>
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
        <tr>
          <td colspan="2" width="50%" >
            <span style="margin: 0; font-weight: bold; font-size: 9px !important">CUPS PNP @if(isset($emergencia_triaje->cups_pnp)){{$emergencia_triaje->cups_pnp}}@else {{'-------------------------'}}@endif</span></td>
        </tr>
        {{-- <tr>
          <td colspan="2" width="50%"   >
            <span style="margin: 0; font-weight: bold; font-size: 8px !important">COD.SITEDS @if(isset($emergencia_triaje->cod_siteds_susalud)){{$emergencia_triaje->cod_siteds_susalud}}@else {{'-------'}}@endif</span></td>
        </tr> --}}
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
                <div>
                    <table style="margin:0px;padding:0px;" >
                        <tbody>
                          <tr>
                              <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C:</span></td>
                              <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{$emergencia_triaje->dni_beneficiario}}</b></span></td>
                          </tr>
                          <tr>
                              <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A:</td>
                              <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  <b>{{$emergencia_triaje->nro_historia_paciente}}</b></td>
                          </tr>
                          <tr>
                              <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size:1.3rem; font-weight: bold;">N° ORDEN:</span></td>
                              <td width="70%" style="text-align:left;"> <b style="font-size: 1.3rem;"> {{str_pad($emergencia_triaje->secuencia, 8, '0', STR_PAD_LEFT)}}</b></td>
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
      <td width="22%">
        <span style="display: block; margin: 0; font-size: 0.9rem; font-weight: bold;  padding-top: 1.3rem; text-align:left;">
          FECHA/HORA: {{$emergencia_triaje->fecha_registro_ingreso}}&nbsp;&nbsp;{{$emergencia_triaje->hora_registro_ingreso}}</span>
      </td>
    </tr>
  </table>



  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="100%" style="vertical-align:top;">
        <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">
          I.- PACIENTE: 
          @if($emergencia_triaje->tipo_doc_ident)
            {{$emergencia_triaje->tipo_doc_ident}} {{$emergencia_triaje->nombre_asegurado}}</span>
          @else
            {{$emergencia_triaje->nombre_asegurado}} </span>
          @endif
      </td> 
      <td width="65%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem;text-align: right; padding-left: 15px;text-transform: uppercase;">
          EDAD: <b>{{$emergencia_triaje->fec_nacim_descrip}}</b></span>
      </td>
    </tr>
  </table>



  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">PARENTESCO: <b>{{$emergencia_triaje->parentesco}}</b></span>
      </td>
      <td width="15%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: left; ">SEXO : <b>{{$emergencia_triaje->sexo}}</b></span>
      </td>
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">ESTADO CIVIL: 
          <b>
            @if($apersonal['estado_civil'])
            {{$apersonal['estado_civil']}}
          @endif
          </b>
        </span>
      </td>
      <td width="30%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: right;padding-right: -5px;">
          @if($emergencia_triaje->telefono!='')
            TELÉFONO: <b>{{$emergencia_triaje->telefono}}</b>
          @endif
        </span>
      </td>
    </tr>
  </table>



  <table  style="margin:0px;padding:0px;" >
    <tr>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px; border-bottom: 1" >
    <tr>
      <td width="100%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">
          DIRECCIÓN: 
          <b>
            @if($apersonal['direccionDomicilio'] !='-' && $apersonal['departamentoDomicilio'] !='-'  && 
              $apersonal['provinciaDomicilio'] !='-'  && $apersonal['distritoDomicilio'] !='-' )
              {{$apersonal['direccionDomicilio']}} - 
              ({{$apersonal['departamentoDomicilio']}}/{{$apersonal['provinciaDomicilio']}}/{{$apersonal['distritoDomicilio']}}) 
            @else
            {{$emergencia_triaje->domicilio_aseg}} - 
            ({{$emergencia_triaje->departamento_descripcion}}/{{$emergencia_triaje->provincia_descripcion}}/{{$emergencia_triaje->distrito_descripcion}}) 
            @endif
          </b>
        </span>
      </td>   
    </tr>
  </table>
  

  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">II.- INGRESO:</span>
      </td> 
    </tr>
  </table>


  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem; text-align: left">FORMA DE INGRESO:  <b>{{$emergencia_triaje->descripcion_forma_ingreso}}</b></span>
      </td>
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem; text-align: left">ESTADO DE INGRESO:  <b>{{$emergencia_triaje->descripcion_estado_ingreso}}</b></span>
      </td>
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem; text-align: left">MOTIVO DE INGRESO:  <b>{{$emergencia_triaje->descripcion_motivo_ingreso}}</b></span>
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
          AU (cm):<b>{{$emergencia_triaje->tamano_cintura_cm}}cm</b></span>
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

  <hr/>
  @if($emergencia_triaje->id_gestacion != null || $emergencia_triaje->id_puerperio != null)
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">VI.- SALUD MATERNA:</span>
      </td> 
    </tr>
  </table>
  @endif


  @if($emergencia_triaje->id_gestacion != null)
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 1.0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;"> FECHA REGLA :<b>{{$emergencia_triaje->fech_ult_regla}}</b></span>
    </td>

  
    <td width="20%">
      <span style="display: block; margin: 0; font-size: 1.0.9rem;;  padding-top: 0.3rem;">FECHA_PROBABLE_PARTO :<b>{{$emergencia_triaje->fech_prob_parto}}</b></span>
    </td>
    <td width="20%">
      <span style="display: block; margin: 0; font-size: 1.0.9rem;;  padding-top: 0.3rem;">SEMANAS_GESTACION :<b>{{$emergencia_triaje->semanas_gestacion}}</b></span>
    </td>
    <td width="20%">
      <span style="display: block; margin: 0; font-size: 1.0.9rem;;  padding-top: 0.3rem;">N° CONTROLES :<b>{{$emergencia_triaje->num_controles}}</b></span>
    </td>
  </tr>
</table>
<hr/>
@endif





  @if($emergencia_triaje->id_puerperio != null)
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 1.0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">FECHA_PARTO :<b>{{$emergencia_triaje->fecha_parto}}</b></span>
    </td>
    <td width="20%">
      <span style="display: block; margin: 0; font-size: 1.0.9rem; padding-top: 0.3rem;">
          TIPO PARTO:
          <b>
              @if($emergencia_triaje->id_tipo_parto == 1)
                  VAGINAL
              @elseif($emergencia_triaje->id_tipo_parto == 2)
                  CESÁREA
              @else
                  {{$emergencia_triaje->id_tipo_parto}}
              @endif
          </b>
      </span>
  </td>
  
    <td width="20%">
      <span style="display: block; margin: 0; font-size: 1.0.9rem;;  padding-top: 0.3rem;">COMPLICACIONES<b>{{$emergencia_triaje->complicaciones}}</b></span>
    </td>
   
  </tr>
</table>


<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 1.0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">descripcion<b>{{$emergencia_triaje->descripcion}}</b></span>
    </td>
  </tr>
</table>

<hr/>
@endif






  

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

  <!--
  <footer style=" color: black;"> 
    <table>   
        <tr style="font-size: 5px !important; margin-top: 19px !important;">
            <td style="text-align: left">
                <b style=" font-size: 5px !important;font-weight: 200;">PACIENTE: </b>  <b style="font-size: 7px !important;">{{$emergencia_triaje->nombre_asegurado_titular}}</b> <br> 
                <i><b> * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE PRESTACIÓN</b></i>
            </td>  
            <td style="text-align: right; padding-right: 10px;">  
                FECHA DE IMPRESIÓN: <b>{{\Carbon\Carbon::now()->format('d/m/Y')}}</b>  <br>
                HORA DE IMPRESIÓN: <b>{{\Carbon\Carbon::now()->format('H:m:s A')}}</b> 
            </td>
        </tr>   
    </table>-->
</footer>  
  
</body>

</html>
