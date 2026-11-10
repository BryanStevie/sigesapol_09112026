<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PDF - ALTA MEDICA - RETIRO VOLUNTARIO</title>
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
          <img src="images/logo_sanidad.png" width="40rem"/>
        </td>
        <td colspan="2" class="" width="80%">
          <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">
            {{$establecimie->nombre}}</span>
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">SERVICIO DE EMERGENCIA - ALTA MEDICA</span>
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
              <span style="margin: 0; font-size: 0.9rem; font-weight: bold; font-size: 17px !important;">
                  HOJA DE ALTA MEDICA - RETIRO VOLUNTARIO<br>
              </span>
              <span style="margin: 0; font-weight: bold; font-size: 9px !important">
                CUPS PNP {{$emergencia->cups_pnp}}
              </span><br>
              <span style="margin: 0; font-weight: bold; font-size: 8px !important">
                COD.SITEDS {{$emergencia->cod_siteds_susalud}}
              </span>
          </td> 
      </tr> 
    </tbody>
  </table>

  <table style="margin:0px;padding-top:30px;">
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
                              <td width="30%" style="text-align:left;" ><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">H.C:</span></td>
                              <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">  <b>{{$emergencia->dni_beneficiario}}</b></span></td>
                          </tr>  
                          <tr>
                              <td width="30%" style="text-align:left;" ><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">H.C.A:</td>
                              <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">  <b>{{$prestacion->nro_historia}}</b></td>
                          </tr>
                          <tr>
                              <td width="30%" style="text-align:left;" ><span style="margin: 0; font-size: 0.9rem; font-weight: bold;">N° ORDEN:</span></td>
                              <td width="70%" style="text-align:left;"> <b style="font-size: 0.9rem;"> {{str_pad($emergencia->secuencia, 8, '0', STR_PAD_LEFT)}}</b></td>
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
         INGRESO FECHA / HORA: <?php  echo date("d/m/Y", strtotime($emergencia->fecha_registro_ingreso))?> <?php  echo date("H:i", strtotime($emergencia->fecha_registro_ingreso))?></span>
      </td>
    </tr>
  </table>

  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">I.- PACIENTE: {{$aseguradoemer->paterno}} {{$aseguradoemer->materno}} {{$aseguradoemer->nombre}}</span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: center">CIP:  <b>{{$aseguradoemer->cip}}</b></span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: right; ">DNI : <b>{{$aseguradoemer->nro_doc_ident}}</b></span>
      </td>
      <td width="10%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem;text-align: right; padding-right: -5px;">SEXO: <b>{{$aseguradoemer->sexo}} </b></span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="35%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">PARENTESCO: <b>{{$tipo_benefi->descripcion}}</b></span>
      </td>
      <td width="35%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: center">GRADO:  <b>{{$aseguradoemer->grado}}</b></span>
      </td>
      <td width="35%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem;text-align: right; padding-right: -5px;">EDAD: <b>{{$tiempo}}</b></span>
      </td>
    </tr>
  </table>

  <hr/>
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
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">FORMA DE INGRESO:  <b>{{$formaingreso->descripcion}}</b></span>
      </td>
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">ESTADO DE INGRESO:  <b>{{$estadoingre->descripcion}}</b></span>
      </td>
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">MOTIVO DE INGRESO:  <b>{{$motivoingre->descripcion}}</b></span>
      </td> 
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: center">SERVICIO:  <b>{{$triajes->descripcion}}</b></span>
      </td>
      <td width="33.3%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: center">NUMERO DE CAMA:  <b>@if($existecama == '1') {{$cama->nombre}} @else @endif</b></span>
      </td>
    </tr>
  </table>


  <hr/>
  @if($emergencia->nombre_asegurado_titular != null)
    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">III.- TITULAR: <b>{{$emergencia->nombre_asegurado_titular}}</b></span>
        </td>
        <td width="50%">
          <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: left"> GRADO: <b>{{$emergencia->grado_asegurado_titular}}</b></span>
        </td>
      </tr>
    </table>  

    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="30.3%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> DNI: <b>{{$emergencia->nro_doc_asegurado_titular}}</b></span>
        </td>
        <td width="30.3%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">CIP:  <b>{{$emergencia->cip_asegurado_titular}}</b></span>
        </td>
        <td width="40.3%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">SITUACIÓN: <b>{{$emergencia->situacion_paciente_titular}}</b>  </span>
        </td>
      </tr>
    </table>  
  @endif

  
  @if($emergencia->oficial_guardia!=null || $emergencia->oficial_guardia !='')
    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="100%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> <b>III.- CORONEL DE GUARDIA QUE AUTORIZA: {{$emergencia->oficial_guardia}}</b></span>
          <br>
        </td> 
      </tr>
    </table>  
  @endif
  
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">IV.- ANTECEDENTES:</span>
      </td> 
    </tr>
  </table>


  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">ANTECEDENTES PERSONALES:  <b>{{$prestacion->antecedente}}</b></span>
      </td>
    </tr>
    <tr>
    <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">ANTECEDENTES FAMILIARES:  <b>{{$prestacion->antecedente_familiar}}</b></span>
      </td>
    </tr>
  </table>

  <hr/>

  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">V.- RESUMEN DE LA ATENCION:</span>
      </td> 
    </tr>
  </table>


  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">EXAMEN CLINICO:  <b>{{$prestacion->examen_clinico}}</b></span>
      </td>
    </tr>
    <tr>
    <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">EXAMEN AUXILIAR :  <b>{{$prestacion->examen_auxiliar}}</b></span>
      </td>
    </tr>
    <tr>
    <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">TRATAMIENTO AUXILIAR :  <b>{{$prestacion->tratamiento_auxiliar}}</b></span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">MEDICAMENTOS SUMINISTRADOS:</span>
      </td> 
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr> 
    <td width="10%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left"><b>N RECETA:</b><br>@if($buscareceta != '0')<?php foreach($recetas as $row):?><br>{{$row->nro_receta}} <br><?php endforeach;?> <br>@endif</span>
      </td>
      <td width="15%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left"><b>RV. FIRMA:</b><br>@if($buscareceta != '0')<?php foreach($recetas as $row):?><br> {{$row->fecha_firma}} <br><?php endforeach;?><br>@endif</span>
      </td>
      <td width="60%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left"><b>NOMBRE PRODUCTO:</b><br>@if($buscareceta != '0')<?php foreach($recetas as $row):?><br>{{$row->nombre}} <br><?php endforeach;?><br>@endif</span>
      </td>
      <td width="10%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left"><b>CANTIDAD :</b><br>@if($buscareceta != '0')<?php foreach($recetas as $row):?> <br>{{$row->cantidad_prescrita}} <br><?php endforeach;?><br>@endif</span>
      </td>
    </tr>
  </table>
  <hr/>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">VI.- PROCEDIMIENTOS DE ATENCION:</span>
      </td> 
    </tr>
  </table>

  <table  style="margin:0px;padding:0px;" >
    <tr> 
   
      <td width="25%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left"><b>CPMS:</b><br><?php foreach($procedimiento as $row):?>{{$row->codigo}} <br><?php endforeach;?><br></span>
      </td>
      <td width="75%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left"><b>PROCEDIMIENTOS:</b><br><?php foreach($procedimiento as $row):?> {{$row->nombre}} <br><?php endforeach;?><br></span>
      </td>
    
    </tr>
   
  </table>

  <hr/>
  
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">VII.- DIAGNOSTICO DE ATENCION:</span>
      </td> 
    </tr>
  </table>


  <table  style="margin:0px;padding:0px;" >
    <tr> 
   
      <td width="10%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left"><b>CIE10:</b> <br> <?php foreach($diagnostico as $row):?><br> {{$row->codigo}} <br><?php endforeach;?><br></span>
      </td>
      <td width="70%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left"><b>DIAGNOSTICOS:</b> <br> <?php foreach($diagnostico as $row):?><br> {{$row->nombre}} <br><?php endforeach;?><br></span>
      </td>
    
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left"><b>TIPO DIAGNOSTICO:</b> <br><?php foreach($diagnostico as $row):               						
        $tipo_diagnostico = "";
              						if($row->id_tipo_diagnostico==1)$tipo_diagnostico = "PRESUNTIVO";
              						if($row->id_tipo_diagnostico==2)$tipo_diagnostico = "DEFINITIVO";
              						if($row->id_tipo_diagnostico==3)$tipo_diagnostico = "REITERATIVO";
                          ?>
                          <br> {{$tipo_diagnostico}} <br>
                          <?php endforeach;?>
                        </span>
      </td> 
    </tr>
   
  </table>

  <hr/>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="70%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">VIII.ALTA MEDICA </span>
      </td>
      <td width="30%">
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="30.3%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> TIPO DE ATENCI&Oacute;N: <b>{{$prestacion->tipo_atencion}}</b></span>
        </td>
        <td width="30.3%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">FECHA DE ATENCION:  <b>{{$prestacion->fecha_atencion}}</b></span>
        </td>
        <td width="40.3%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">PRIORIDAD: <b>{{$priori->descripcion}}</b>  </span>
        </td>
      </tr>
    </table> 

    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="80%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> MEDICO ENCARGADO DEL ALTA: <b> {{$medicoegreso->name}} </b> <b>{{$medicoegreso->paterno}}</b> <b>{{$medicoegreso->materno}}</b></span>
        </td>
       
      </tr>
    </table> 

    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> TIPO DE EGRESO: <b>RETIRO VOLUNTARIO</b></span>
        </td>
        <td width="50%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">CONDICION DE EGRESO:  <b>{{$condiegre->descripcion}}</b></span>
        </td>
      </tr>
    </table>

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
      </tr>
    </table>
    <hr/>

  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">IX.- DIAGNOSTICO DE EGRESO:</span>
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
   
  </table>

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

  </table>
        
              	
</footer>  
  
</body>

</html>
