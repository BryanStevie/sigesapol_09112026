<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PDF - ALTA - EPICRISIS</title>
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
 <br/>
  <table style="margin:0px;padding:0px;padding-bottom:10px;">
    <tbody>
      <tr>
        <td colspan="2" class="" width="10%">
          <img src="images/logo_sanidad.png" width="40rem"/>
        </td>
        <td colspan="2" class="" width="80%">
          <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">
            {{$establecimie->nombre}}</span>
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">SERVICIO DE EMERGENCIA - ALTA - @if($emergencia->id_tipo_egres_med == 3)  TRASFERIDO<br>@endif @if($emergencia->id_tipo_egres_med == 4)  FALLECIDO<br>@endif</span>
        </td>
        <td colspan="2" width="50%">
          
        </td>
        <!--<td colspan="2" class="titulos" width="12%" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php //echo $receta->nro_receta ?>
                        </div>
                    </td>-->
      </tr>
    </tbody>
  </table>

  <table style="margin:0px;padding:0px;padding-bottom:-25px;">
    <tbody>
      <tr> 
          <td colspan="2" width="50%" >
              <span style="margin: 0; font-size: 0.9rem; font-weight: bold; font-size: 17px !important;">EPICRISIS</span><br>
          </td> 
          <br>
      </tr> 
    </tbody>
  </table>
<br>
 <hr>

 <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: center">FECHA DE INGRESO:  <b><?php  echo date("d/m/Y", strtotime($emergencia->fecha_registro_ingreso))?></b></span>
      </td>
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: center">HORA DE INGRESO:  <b> <?php  echo date("H:i", strtotime($emergencia->fecha_registro_ingreso))?></b></span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: center">SERVICIO :  <b>{{$triajes->descripcion}}</b></span>
      </td>
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: center">CAMA:  <b>@if($existecama == '1') {{$cama->nombre}} @else @endif</b></span>
      </td>
    </tr>
  </table>
<hr>
<table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="55%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> PACIENTE: <b> {{$aseguradoemer->paterno}} {{$aseguradoemer->materno}} {{$aseguradoemer->nombre}}</b></span>
        </td>
        <td width="15%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> DNI: <b> {{$aseguradoemer->nro_doc_ident}} </b></span>
        </td>
        <td width="15%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> CIP: <b> {{$aseguradoemer->cip}} </b></span>
        </td>
        <td width="15%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> HC: <b> {{$emergencia->dni_beneficiario}} </b></span>
        </td>
      </tr>
    </table> 
    <hr>

<table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: center; margin: 0; font-weight: bold; padding-top: 0.3rem;">RESUMEN</span>
      </td> 
    </tr><br>
  </table>

  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">ANTECEDENTES PERSONALES:  <b>{{$evaluacion->antecedente}}</b></span>
      </td>
    </tr>
    <tr>
    <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">ANTECEDENTES FAMILIARES:  <b>{{$evaluacion->antecedente_familiar}}</b></span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">EXAMEN FISICO:  <b>{{$evaluacion->examen_clinico}}</b></span>
      </td>
    </tr>
    <tr>
    <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">EXAMEN AUXILIAR :  <b>{{$evaluacion->examen_auxiliar}}</b></span>
      </td>
    </tr>
    <tr>
    <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">TRATAMIENTO  :  <b>{{$evaluacion->tratamiento_auxiliar}}</b></span>
      </td>
    </tr>
  </table>


  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">PROCEDIMIENTOS</span>
      </td> 
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="85%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left"><?php foreach($procedimiento as $row):?>{{$row->codigo}} - {{$row->nombre}}<br><?php endforeach;?><br></span>
      </td>
    </tr>
  </table>
 @if($emergencia->id_tipo_egres_med == 4) 
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">NECROPCIA</span>
      </td> 
    </tr>
  </table> 
  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">SE REALIZO NECROPCIA:  <b>{{$emergencia->necropsia}}</b></span>
      </td>
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">CAUSAS DE LA MUERTE:  <b>{{$emergencia->causas_muerte}}</b></span>
      </td>
    </tr>
  </table>
  @endif
  <hr/>
  <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DE EGRESO: <b><?php echo date("d/m/Y", strtotime($emergencia->fecha_alta_medica))?></b></span>
        </td>
        <td width="50%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">HORA DE EGRESO:  <b><?php  echo date("H:i", strtotime($emergencia->hora_alta_medica))?></b></span>
        </td>
        <td width="50%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">DIAS DE ESTANCIA:  <b>{{$resta}}</b></span>
        </td>
      </tr><br>
    </table>
  <hr/>
  <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%">
            <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">TIPO DE EGRESO:  <b>@if($emergencia->id_tipo_egres_med == 3)  TRASFERIDO<br>@endif
              @if($emergencia->id_tipo_egres_med == 4) DEFUNCION<br>@endif
              
          </td>
        <td width="50%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">CONDICION DE EGRESO:  <b>{{$condiegre->descripcion}}</b></span>
        </td>
      </tr><br>
    </table>
  <hr/>

  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">DIAGNOSTICO DE EGRESO:</span>
      </td> 
    </tr>
  </table>


  <table  style="margin:0px;padding:0px;" >
    <tr> 
   
      <td width="12%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left"><b>CIE10:</b> <br> <?php foreach($diagnosticos as $row):?><br> {{$row->codigo}} <?php endforeach;?></span>
      </td>
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left"><b>DIAGNOSTICOS:</b> <br> <?php foreach($diagnosticos as $row):?><br> {{$row->nombre}} <?php endforeach;?></span>
      </td>
      <td width="16%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left"><b>TIPO DIAGNOSTICO:</b> <br><?php foreach($diagnosticos as $row):?><br> {{$row->tipo}} <?php endforeach;?></span>
      </td> 
      <td width="12%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left"><br><?php foreach($diagnosticos as $row):?><br> {{$row->cabecera}} <?php endforeach;?></span>
      </td>
    </tr>
   <br>
  </table>
<hr>
<table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="80%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> MEDICO ENCARGADO DEL EGRESO: <b> {{$medicoegreso->name}} </b> <b>{{$medicoegreso->paterno}}</b> <b>{{$medicoegreso->materno}}</b></span>
        </td>
      </tr><br>
    </table> 
    <hr>

 
  

  <table style="margin-top: 10px">
    <tr>
      <td class="td_ancho_espacios"></td>
      <td class="td_ancho_espacios"></td>
    </tr>
  </table>
  <table>   
      <tr style="font-size: 6px !important; margin-top: 19px !important;">
          <td style="text-align: left"> 
          </td>  
          <td style="text-align: right; padding-right: 10px;">  
              FECHA DE IMPRESIÓN: <b><?php echo $fechaActual?></b>  <br>
              HORA DE IMPRESIÓN: <b><?php echo $horaActual?></b>  <br>
          </td>
      </tr>   
  </table>
              	
</footer>  
  
</body>

</html>
