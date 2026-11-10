<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>RECETA VALE DISPENSADA</title>
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
    text-align:center;
  }
  .td_center {
    font-size: 7px;
    text-align:center;
    vertical-align: top;
  }

  table {
    font-size: 7px !important;
    font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
  }

  /* .ancho_nro {
    width: 5%;
  }

  .ancho_producto {
    width: 45%;
  }

  .titulos {
    /*font-weight: bold;*/
    padding: 0px 10px;
  } */

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


<body style="font-family: arial;border:1px solid #A4A4A4;padding:10px">
  <!---table style="margin:0px;padding:0px;padding-bottom:10px"-->
  <table border="0px" style="background-color:white !important">
    <tbody>
      <tr>
        <td colspan="2" class="td_left logo" width="10%">
          <img src="images/logo_sanidad.png" width="60" />
        </td>
        <td colspan="2" class="td_left logo" width="40%">
          <table style="margin:0px;padding:0px;padding-bottom:10px">
            <tr  valign="center"><td colspan="2"><br/><b><?php echo $establecimiento->nombre;?> </b></td></tr>
            <tr  valign="center"><td colspan="2"><?php echo $establecimiento->direccion_establecimiento;?></td></tr>
          </table>
        </td>
        <td colspan="2" class="titulo_principal">
          <h2>
            RECETA - VALE
          </h2>
          <p>
          <?php 
            switch($receta->tipo_receta){
              case 'AMBULATORIO' : echo '110200'; break;
              case 'EMERGENCIA' : echo '110300'; break;
              case 'HOSPITALIZACION' : echo '110500'; break;
              case 'CENTRO QUIRURGICO' : echo '040000'; break;
              case 'SERVICIO NUTRICIONAL - AMBULATORIO' : echo '110200'; break;
              case 'SERVICIO NUTRICIONAL - HOSPITALIZACION' : echo '110400'; break;
              case 'URGENCIA' : echo '110500';break;
              case 'ONCOLOGICO - HOSPITALIZACION' : echo '110400'; break;
            }

          ?>
          <br/>
          <b>CODIGO UPSS</b></p>
        </td>
        <td colspan="2" class="titulos" width="20%" style="padding-right:0px">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <b>N° :</b> <?php echo $receta->nro_receta ?>
          </div>
        </td>
      </tr>
    </tbody>
  </table>

  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="15%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $receta->codigoipress ?>
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          @if($receta->tipo_receta=='QUIRURGICO')
          CENTRO QUIRURGICO
          @else
          @if($receta->tipo_receta=='NUTRICIONAL')
          SOPORTE NUTRICIONAL
          @else
          <?php echo $receta->tipo_receta ?>
          @endif
          @endif
        </div>
      </td>
      <td class="titulos"  width="50%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $receta->farmacia ?>
        </div>
      </td>
      <td class="titulos" width="15%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php
          if ($receta->fecha_registro <> "") {
            $fecha_admision = date("d-m-Y", strtotime($receta->fecha_registro));
            echo $fecha_admision;
          } else {
            echo "---";
          }
          ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>CÓDIGO IPRESS</strong></td>
      <td class="td_center"><strong>TIPO DE ATENCIÓN</strong></td>
      <td class="td_center"><strong>FARMACIA</strong></td>
      <td class="td_center"><strong>FECHA DE EXPEDICIÓN</strong></td>
      <!--<td class="td_center">TURNO</td>-->
    </tr>
  </table>

  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="15%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $receta->dni_beneficiario ?>
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $receta->nro_historia . "&nbsp;" ?>
        </div>
      </td>
      <td class="titulos" width="50%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $receta->paterno_beneficiario . " " . $receta->materno_beneficiario . ", " . $receta->nombre_beneficiario ?>
        </div>
      </td>
      <td class="titulos" width="15%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php use Carbon\Carbon;
                if($receta->fecha_nacimiento_beneficiario!=''):
                  $edad = Carbon::createFromFormat('d/m/Y', $receta->fecha_nacimiento_beneficiario)->format('Y/m/d');
                  echo $edad = Carbon::parse($edad)->age;
                endif;
                
          ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong><?php echo $receta->tipodoc_beneficiario ?> DEL PACIENTE</strong></td>
      <td class="td_center"><strong>HISTORIA CLINICA</strong></td>
      <td class="td_center"><strong>APELLIDOS Y NOMBRES COMPLETOS DEL PACIENTE</strong></td>
      <td class="td_center"><strong>EDAD DEL PACIENTE</strong></td>
    </tr>
  </table>
  @if($receta->dni_titular!=$receta->dni_beneficiario && $receta->dni_titular!="00000000" && $receta->dni_titular!='' )
    <table border="0px" style="background-color:white !important">
      <tr>
        <td class="titulos" width="15%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php echo $titular->nro_doc_ident ?>
          </div>
        </td>
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php echo $titular->grado . "&nbsp;" ?>
          </div>
        </td>
        <td class="titulos" width="65%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $titular->paterno . " " . $titular->materno . ", " . $titular->nombre ?>
          </div>
        </td>
        
      </tr>
      <tr>
        <td class="td_center"><strong><?php echo $receta->tipo_doc_ident ?> DEL TITULAR</strong></td>
        <td class="td_center"><strong>GRADO</strong></td>
        <td class="td_center"><strong>APELLIDOS Y NOMBRES COMPLETOS DEL TITULAR</strong></td>
        
      </tr>
    </table>
  @endif

  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="25%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php echo $receta->tipo_beneficiario; ?>
          </div>
        </td>
      <td class="titulos" width="60%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php 
              if($receta->tipo_receta=='EMERGENCIA' || $receta->tipo_receta=='HOSPITALIZACION'):
                  $consultorio= $receta->tipo_receta;
              else:
                  $consultorio= $receta->consultorio;
              endif;

                if($receta->codigo_upss!=''){
                  echo $receta->codigo_upss.'-'.$consultorio;
                }
                else{

                  echo $consultorio;
                }
          ?>
        </div>
      </td>
      <td class="titulos" width="15%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php
            if($receta->tipo_receta=='EMERGENCIA' || $receta->tipo_receta=='HOSPITALIZACION'):
              $fecha_inicio = strtotime('+3 day', strtotime($receta->fecha_registro));
              $fecha_expiracion = date('j-m-Y', $fecha_inicio);
              echo $fecha_expiracion;
            else:
              $fecha_inicio = strtotime('+30 day', strtotime($receta->fecha_registro));
              $fecha_expiracion = date('j-m-Y', $fecha_inicio);
              echo $fecha_expiracion;
            endif;
          ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>PARENTESCO</strong></td>
      <td class="td_center"><strong>UPSS - SERVICIO</strong></td>
      <td class="td_center"><strong>FECHA EXPIRACIÓN</strong></td>
    </tr>
  </table>

  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="7%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $receta->especializacion ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $receta->nombre_medico . " " . $receta->paterno_medico . " " . $receta->materno_medico ?>
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php /*
          $turno = "--";
          if ($receta->turno == "M")
            $turno = "MAÑANA";
          if ($receta->turno == "T")
            $turno = "TARDE";
          if ($receta->turno == "N")
            $turno = "NOCHE";
          if ($receta->turno == "MD")
            $turno = "MADRUGADA";
          echo $turno */
          ?>
          <?php echo $receta->colegiatura ?>
        </div>
      </td>
    </tr>
    <tr>
      <!--<td class="td_center">CONSULTORIA - CAMA</td>-->
      <td class="td_center"><strong>ESPECIALIDAD</strong></td>
      <td class="td_center"><strong>MEDICO</strong></td>
      <td class="td_center"><strong>COLEGIATURA</strong></td>
    </tr>
  </table>

  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="9%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php
          $cad = "";
          foreach ($diagnosticos as $row) :
            $cad .= $row->codigo . " - " . $row->nombre . "<br>";
          endforeach;
          echo $cad;
          ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>DIAGNOSTICO (CIE 10)</strong></td>
    </tr>
  </table>
  <!--table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="250" valign="top"-->
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
          <tbody>
            <tr>
              <td colspan="6" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>PRESCRIPCIÓN</b>
              </td>
              <td colspan="2" class="titulos"
                style="border:1px solid #A4A4A4;font-size:8px!important;font-style:italic;text-align:left;font-weight:bold">
                FECHA DE DISPENSACIÓN : <?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?></td>
            </tr>
            <tr>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
              <td class="ancho_producto titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                PRODUCTO</td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">UN.
                MED.</td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="1%">CANT.
              </td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="1%">DÍAS
              </td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="1%">
                POSOLOGÍA</td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"
                width="1%!important">CANT. DADO</td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">OBSERV.</td>
            </tr>
            <?php $n = 0; ?>
            @foreach($medicamentos as $p)
            <?php $n++; ?>
            <tr>
              <td style="border:1px solid #A4A4A4"><?php echo $n ?></td>
              <td class="td_left" style="border:1px solid #A4A4A4">{{$p->codigo}} - {{$p->nombre}}
                @if($p->presentacion==1)
                ( {{$p->cantidad_dispensada}} X {{$p->presentacion_comercial}} )
                @endif
                @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                ({{$p->cantidad_dispensada}} L )
                @endif
                
              </td>
              <td style="border:1px solid #A4A4A4">{{$p->abreviatura}}</td>
              <td style="border:1px solid #A4A4A4">
                @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                  <?php echo ($p->cantidad_prescrita)/1000 ?>
                @else
                  {{$p->cantidad_prescrita}}
                @endif
              </td>
              <td style="border:1px solid #A4A4A4">{{$p->tiempo_periodo}}</td>
              <td style="border:1px solid #A4A4A4">{{$p->posologia}}</td>
              <td style="border:1px solid #A4A4A4">
                @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                  <?php echo ($p->cantidad_dispensada)/1000 ?>
                @else
                  {{$p->cantidad_dispensada}}
                @endif
              </td>
              <td style="border:1px solid #A4A4A4">@if($p->presentacion==1)
                Equiv. {{$p->cantidad_dispensada*$p->presentacion_comercial}} {{$p->abreviatura}}
                @endif</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      <!--/td>
    </tr>
  </table-->
  <footer>
    * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE RECETA
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
