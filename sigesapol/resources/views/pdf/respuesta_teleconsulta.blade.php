<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>
               PDF - TELECONSULTA - RESPUESTA<br>
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
          <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">
            {{$ipress_destino->nombre}}</span>
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">RESPUESTA TELECONSULTA</span>
        </td>
        <td colspan="2" width="50%">          
        </td>
      </tr>
    </tbody>
  </table>
  <table style="margin:0px;padding:0px;padding-bottom:-25px;">
  <tbody>
    <tr> 
      <td colspan="2" width="50%">
        <span style="margin: 0; font-size: 0.9rem; font-weight: bold; font-size: 17px !important; border: 1px solid #000; padding: 3px 6px; display: inline-block;">
          FORMATO DE ATENCION DE TELECONSULTA N°{{$referencia->id}} <br>
        </span>
        <br>
        <span style="margin: 0; font-weight: bold; font-size: 9px !important; border: 1px solid #000; padding: 3px 6px; display: inline-block;">
          RESPUESTA
        </span>
        <br>
        <span style="margin: 0; font-weight: bold; font-size: 9px !important; border: 1px solid #000; padding: 3px 6px; display: block; text-align: left;">
           I. ATENCION DE TELECONSULTA (llenado por el teleconsultor)
        </span>
      </td> 
    </tr> 
  </tbody>
</table>
  <br>
  <br>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="70%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> 1.- RESPUESTA DE TELECONSULTA</span>
      </td>
    </tr>
  </table>
  <hr/>
  <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA: <b>{{$referencia->fecha_cita_teleinterconsulta}}</b></span>
        </td>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> HORA: <b>{{$referencia->hora_cita_teleinterconsulta}}</b></span>
        </td>
      </tr>
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> IPRESS TELECONSULTADA: <b>{{$ipress_destino->nombre}}</b></span>
        </td>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> CODIGO RENIPRESS TELECONSULTADA: <b>{{$ipress_destino->codigo}}</b></span>
        </td>
      </tr>
    </table> 
    <table style="margin:0px; padding:0px;">
  <tr>
    <td width="23%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; margin: 0; padding-top: 0.3rem; text-align: left;">
        MODALIDAD DE TELECONSULTA:
      </span>
    </td>
    
    <td width="13%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.8rem; margin: 0; padding-top: 0.3rem; text-align: left;">
        FUERA DE LÍNEA
        <span style="border: 1px solid #000; width: 12px; height: 12px; display: inline-block; margin-left: 5px;"></span>
      </span>
    </td>    
    
    <td width="13%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.8rem; margin: 0; padding-top: 0.3rem; text-align: left;">
        EN LÍNEA
         <span style="border: 1px solid #000; width: 12px; height: 12px; display: inline-block; margin-left: 5px; text-align: center; font-weight: bold;">X</span>
      </span>
    </td>   
    
    <td width="13%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.8rem; margin: 0; padding-top: 0.3rem; text-align: left;">
        SIMPLE
        <span style="border: 1px solid #000; width: 12px; height: 12px; display: inline-block; margin-left: 5px;"></span>
      </span>
    </td>    
    
    <td width="15%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.8rem; margin: 0; padding-top: 0.3rem; text-align: left;">
        TELEPRESENCIA
        <span style="border: 1px solid #000; width: 12px; height: 12px; display: inline-block; margin-left: 5px; text-align: center; font-weight: bold;">X</span>
      </span>
    </td> 
    
    <td width="13%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.8rem; margin: 0; padding-top: 0.3rem; text-align: left;">
        TELEJUNTA
        <span style="border: 1px solid #000; width: 12px; height: 12px; display: inline-block; margin-left: 5px;"></span>
      </span>
    </td>   
  </tr>
</table>

  <hr/> 
<table style="margin: 0; padding: 0; width: 100%;">
  <tr>
    <td style="
      vertical-align: top;
      text-align: left;
      font-size: 0.9rem;
      line-height: 1.5;
      padding-top: 0.3rem;
      min-height: 180px;
      height: 180px;
    ">
      <b>{{ $referencia->comentario_teleinterconsulta }}</b>
    </td>
  </tr>
</table>

    <hr/>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> DIAGNOSTICO</span>
      </td> 
    </tr>
  </table>


  <table  style="margin:0px;padding:0px;" >
    <tr> 
   
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left"><b>CIE10:</b> <br> <?php foreach($diagnosticos as $row):?><br> {{$row->codigo}} <?php endforeach;?></span>
      </td>
      <td width="60%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left"><b>DIAGNOSTICOS:</b> <br> <?php foreach($diagnosticos as $row):?><br> {{$row->nombre}} <?php endforeach;?></span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left"><b>TIPO DIAGNOSTICO:</b> <br><?php foreach($diagnosticos as $row):?><br> {{$row->codigo}} <?php endforeach;?></span>
      </td> 

    </tr>
   
  </table>
  <hr/>

  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> DATOS DEL CONSULTANTE</span>
      </td> 
    </tr>
  </table>
    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="75%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> NOMBRES Y APELLIDOS: <b>{{$prestacion->medico_nombre}}, {{$prestacion->medico_paterno}} {{$prestacion->medico_materno}}</b></span>
        </td>
      </tr>
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> CODIGO COLEGIO PROFESIONAL: <b>{{$prestacion->colegiatura}}</b></span>
        </td>
        </tr>
        <tr>
        <td width="30%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ESTABLECIMIENTO: <b>{{$prestacion->establecimiento}}</b></span>
        </td>
        <td width="30%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> SERVICIO: <b>{{$prestacion->servicio}}</b></span>
        </td>
      </tr>
    </table>
    <hr/>
    <hr/>
  <table style="margin-top: 10px">
    <tr>
      <td class="td_ancho_espacios"></td>
      <td class="td_ancho_espacios"></td>
    </tr>
  </table>
   	
</footer>  
  
</body>

</html>
