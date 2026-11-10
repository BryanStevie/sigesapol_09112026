<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PDF - INDICACIONES DE HOSPITALIZACION</title>
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
 
<table style="margin:0px;padding:0px;padding-bottom:-25px;">
    <tbody>
      <tr> 
          <td colspan="2" width="50%" >
              <span style="margin: 0; font-size: 0.9rem; font-weight: bold; font-size: 17px !important;">
                 INDICACION DE HOSPITALIZACION<br>
              </span>
          </td> 
      </tr> 
    </tbody>
    <br>
  </table>
  <br>
  <hr/>  
  <table  style="margin:0px;padding:0px;" >
    <tr> 
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: center">FECHA DE SALIDA:  <?php echo date("d-m-Y", strtotime($emergencia->fecha_alta_medica))?></span>
      </td>
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: center">HORA DE SALIDA:  <?php echo date("H:i", strtotime($emergencia->hora_alta_medica))?></span>
      </td>
    </tr>
   
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr> 
    <td width="30%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">ORIGEN DE ATENCION : <b>EMERGENCIA</b></span>
      </td>
      <td width="30%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">AREA DE EGRESO: <b>{{$triajes->descripcion}}</b></span>
      </td>
      <td width="30%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">AREA DE HOSPITALIZACION: <b>{{$upss->nombre_consultorio}}</b></span></span>
      </td>
    </tr>
   
  </table>
  <hr/>
            
  <table  style="margin:0px;padding:0px;" >
    <tr> 
    <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">TIPO DE BENEFICIARIO :  <b>{{$tipo_benefi->descripcion}}</b></span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">DOCUMENTO DEL PACIENTE : <b>{{$emergencia->dni_beneficiario}}</b></span>
      </td>
      <td width="20%">
                  <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">NUMERO DE HISTORIA :  <b>{{$prestacion->nro_historia}}</b></span>
                </td>
      
    </tr>
   
  </table>

  <table  style="margin:0px;padding:0px;" >
              <tr> 
              <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">NOMBRES Y APELLIDOS DEL PACIENTE:  <b> {{$aseguradoemer->paterno}} {{$aseguradoemer->materno}} {{$aseguradoemer->nombre}}</b></span>
      </td>
               
              </tr>
             
            </table>

            
  <hr/>
  <table  style="margin:0px;padding:0px;" >
    <tr> 
    <td width="12%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">DIAGNOSTICOS DE EGRESO </span>
      </td>
    
    </tr>
   
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr> 
    <td width="12%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">CODIGO : <br> <?php foreach($diagnosticos as $row):?><br> {{$row->codigo}} <?php endforeach;?></span>
      </td>
      <td width="50%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">NOMBRE:<br>  <?php foreach($diagnosticos as $row):?><br> {{$row->nombre}} <?php endforeach;?></span>
      </td>
      <td width="16%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: left">TIPO DIAGNOSTICO:<br> <?php foreach($diagnosticos as $row):?><br> {{$row->tipo}} <?php endforeach;?></span>
      </td> 
      <td width="12%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left"><br><?php foreach($diagnosticos as $row):?><br> {{$row->cabecera}} <?php endforeach;?></span>
      </td>
    </tr>
   
  </table>
  <hr/>
 

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
