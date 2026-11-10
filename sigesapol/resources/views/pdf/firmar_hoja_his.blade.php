<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Registro Diario de Atención y Otras Actividades de Salud</title>
  <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
  <style>
  .td_center {
    text-align: center !important;
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
    /*font-weight: bold;*/
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
    margin-bottom: 0px;
    padding: 0px;
    position: fixed;
    height: 60px;
    line-height: 60px;
    text-align: left;
    border: 0px;
    margin-left: 10px;
    font-size: 7px !important;
    font-family: Arial, Helvetica, sans-serif
  }

  .texto-firma {
    font-size: 10px !important;
  }
  </style>
</head>
<body style="font-size: 12px; font-family: arial;border:1px solid #A4A4A4;padding:10px;background-color:white !important">
    
  <table style="margin:0px;padding:0px;padding-bottom:0px;">
    <tbody>
        <tr>
            <td colspan="2" width="70%" style="vertical-align:bottom;padding-bottom:30px">
                <?php echo $url_descarga?>
            </td>
            <td colspan="2" width="30%" style="margin-top:0px;padding-top:0px">
                <img src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
            </td>
        </tr>
    </tbody>
  </table>
  <!---table style="margin:0px;padding:0px;padding-bottom:10px"-->
  <table border="0px" style="background-color:white !important">
    <tbody>
      <tr>
        <td colspan="4" valign="center"><h2>
            <?php echo $establecimiento->nombre;?>
          </h2>
        </td>
      </tr>
      <tr>
        <td class="td_center logo" width="10%">
          <img src="images/logo_sanidad.png" width="60" />
          <b>POLICIA NACIONAL DEL PERÚ </b>
        </td>
        <td class="titulo_principal">
          <h5></h5>
          <p>UNIDAD  DE TECNOLOGIA DE LA INFORMACIÓN Y COMUNICACIONES<br/>UNIDAD DE INTELIGENCIA SANITARIA -ESTADISTICA<br/>Registro Diario de Atención y Otras Actividades de Salud<br/>
          <b>ATENCIÓN {{$nombre_atencion}} </b> <?php if($tipo_atencion==1): ?> (CONSULTA/PROCEDIMIENTO) <?php else: ?> (PROCEDIMIENTO) <?php endif; ?></p>
        </td>
        <td width="2%"></td>
        <td class="titulos" width="30%" style="padding-right:0px">
          <table style="margin:0px;padding:0px;padding-bottom:10px">
            <tr  valign="center"><td colspan="2">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <b>Código IPRESS:</b> {{$establecimiento->codigo}}<br/><b>Código UPSS:</b> {{$codigo_upss}}<br/><b>Servicio :</b> {{$nombre_upss}}</div>
            </tr>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <?php
      $script_tz = date_default_timezone_get();
      $fecha_hoy = date('d-m-Y');
      $separa = explode("-",$fecha_atencion);
      $dia = $separa[0];
      $mes = $separa[1];
      $ano = $separa[2]; 
      switch($mes){
        case '01': $meses='Enero'; break;
        case '02': $meses='Febrero'; break;
        case '03': $meses='Marzo'; break;
        case '04': $meses='Abril'; break;
        case '05': $meses='Mayo'; break;
        case '06': $meses='Junio'; break;
        case '07': $meses='Julio'; break;
        case '08': $meses='Agosto'; break;
        case '09': $meses='Septiembre'; break;
        case '10': $meses='Octubre'; break;
        case '11': $meses='Noviembre'; break;
        case '12': $meses='Diciembre'; break;
      }
      $arrayDias = array('Domingo', 'Lunes', 'Martes','Miercoles', 'Jueves', 'Viernes', 'Sabado');
  ?>
  <table border="0px" style="background-color:white !important">
    <tbody>
      <tr>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>AÑO</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>MES</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>DNI DEL PROFESIONAL</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>APELLIDOS Y NOMBRES DEL PROFESIONAL</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>PROFESIÓN</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>TURNO</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>FECHA DE ATENCIÓN</b></p></td>
      </tr>
      <tr>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">{{$ano}}</p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">{{$meses}}</p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">{{$medico->dni}}</p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">{{$medico->paterno}} {{$medico->materno}}, {{$medico->nombre}}</p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">{{$medico->especializacion}}</p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">{{$fecha_atencion}}</p></td>
      </tr>
    </tbody>
  </table>

  <table border="0px" style="background-color:white !important">
    <thead>
      <tr>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>N°</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>HORA DE <br/>ATENCION</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>DNI</b></p><hr><p style="font-size:8px"><b>HISTORIA CLINICA</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>E</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>S</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>DIAGNOSTICO MOTIVO DE CONSULTA</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>TIPO <br/>DIAG</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>CIE10</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>CANT</b></p></td>
        <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px"><b>CPT</b></p></td>
      </tr>
    </thead>
    <tbody>
      <?php $x=1;?>
      @foreach ($prestaciones as $r)
        <tr>
          <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">{{$x}}</p></td>
          <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">{{$r->horario}}</p></td>
          <td style="border: 1px solid #A4A4A4;">
            <p style="font-size:8px">{{$r->nro_doc_ident}}</p><hr><p style="font-size:8px"><b>{{$r->nro_historia}}</b></p>
          </td>
          <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">
            <?php switch ($r->ipress) {
                    case 'NUEVO': echo 'N';break;
                    case 'CONTINUADOR': echo 'C';break;
                    case 'REPETITIVO': echo 'R';break;
                  }
            ?></p>
          </td>
          <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">
            <?php switch ($r->upss) {
                    case 'NUEVO': echo 'N';break;
                    case 'CONTINUADOR': echo 'C';break;
                    case 'REPETITIVO': echo 'R';break;
                  }
            ?></p>
          </td>
          <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">
            <?php 
                $model_prestaciones = new \App\Models\Prestaciones;
                $diagnostico = $model_prestaciones->GetDiagnosticoByIdPrestacion($r->id);
                foreach ($diagnostico as $d):
                  echo $d->nombre.'<br/>';
                endforeach; 
            ?>
          </p></td>
          <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">
            <?php 
              foreach ($diagnostico as $d):
                  $tipo_diagnostico = "";
                  if($d->id_tipo_diagnostico==1)$tipo_diagnostico = "P";
                  if($d->id_tipo_diagnostico==2)$tipo_diagnostico = "D";
                  if($d->id_tipo_diagnostico==3)$tipo_diagnostico = "R";
                  echo $tipo_diagnostico.'<br/>';
                endforeach; 

            ?>
          </p>
          </td>          
          <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">
            <?php 
              foreach ($diagnostico as $d):
                  echo $d->codigo.'<br/>';
                endforeach; 

            ?>
            </p>
          </td>
            
            <?php 
                $procedimientos = $model_prestaciones->GetProcedimientosByIdPrestacion($r->id,1);
                $laboratorios = $model_prestaciones->GetProcedimientosByIdPrestacion($r->id, 2);
                $radiologias = $model_prestaciones->GetProcedimientosByIdPrestacion($r->id, 3);
            ?>
          <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">
            <?php 

              foreach ($procedimientos as $p):
                  echo $p->cantidad.'<br/>';
              endforeach; 
              foreach ($laboratorios as $lab):
                  echo $lab->cantidad.'<br/>';
              endforeach; 
              foreach ($radiologias as $rad):
                  echo $rad->cantidad.'<br/>';
              endforeach; 

            ?>
            </p>
          </td>        
          <td style="border: 1px solid #A4A4A4;"><p style="font-size:8px">
            <?php 
            
                foreach ($procedimientos as $p):
                  echo $p->codigo.'<br/>';
                endforeach; 
                foreach ($laboratorios as $lab):
                  echo $lab->codigo.'<br/>';
                endforeach; 
                foreach ($radiologias as $rad):
                  echo $rad->codigo.'<br/>';
                endforeach; 
                
            ?>
            </p>
          </td>
        </tr>
        <?php $x++;?>
      @endforeach
    </tbody>
  </table>
  <br/>
  <br/>
  <footer>
    * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE ATENCION - Fecha Impresión: <?php echo $fecha_hoy?>
  </footer>
  <script type="text/php">
    if (isset($pdf)) {
                $x = 490;
                $y = 790;
                $text = "Pagina {PAGE_NUM} de {PAGE_COUNT}";
                $font = null;
                $size = 10;
                $color = array(255,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
</body>

</html>
