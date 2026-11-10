<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>PRESTACION MEDICA</title>
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
  </style>
</head>

<body
  style="font-size: 12px; font-family: arial;border:1px solid #A4A4A4;padding:10px;background-color:white !important">

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

  <table style="margin:0px;padding:0px;padding-bottom:10px;">
    <tbody>
      <tr>
        <td colspan="2" class="td_left logo">
          <img src="images/logo_sanidad.png" width="60" />
        </td>
        <td colspan="2" width="90%" style="padding-right:80px;">
          <h2>
            PRESTACION MEDICA Nº <?php echo $prestacion->id ?>
          </h2>
        </td>
      </tr>
    </tbody>
  </table>

  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $prestacion->tipo_atencion ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $prestacion->establecimiento ?>
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php
                        if($prestacion->fecha_atencion <> ""){
                        $fecha_atencion = date("d-m-Y", strtotime($prestacion->fecha_atencion));
                        echo $fecha_atencion;
                        } else {
                            echo "---";
                        }
                        ?>
        </div>
      </td>
    </tr>
    <tr>
      <!--<td class="td_center">CODIGO IPRESS</td>-->
      <td class="td_center"><strong>TIPO DE ATENCIÓN</strong></td>
      <td class="td_center"><strong>ESTABLECIMIENTO</strong></td>
      <td class="td_center"><strong>FECHA DE ATENCION</strong></td>
      <!--<td class="td_center">TURNO</td>-->
    </tr>
  </table>

  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $prestacion->nro_doc_ident ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $prestacion->asegurado_paterno . " " . $prestacion->asegurado_materno . ", " . $prestacion->asegurado_nombre ?>
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $prestacion->nro_historia."&nbsp;" ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong><?php echo $prestacion->tipo_doc_ident ?> DEL PACIENTE</strong></td>
      <td class="td_center"><strong>APELLIDOS Y NOMBRES COMPLETOS DEL PACIENTE</strong></td>
      <td class="td_center"><strong>HISTORIA CLINICA</strong></td>
    </tr>
  </table>
  @if($prestacion->id_tipo_atencion == 3)
  <table border="0px" style="background-color:white !important">
    <tr>
        <td class="grado titulos">
            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php //echo $prestacion->pabellon?>
            </div>
        </td>
        <td class="grado titulos">
            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php //echo $prestacion->piso?>
            </div>
        </td>
        <td class="grado titulos">
            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php //echo $prestacion->cuarto?>
            </div>
        </td>
        <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php //echo $prestacion->cama?>
            </div>
        </td>
    </tr>
    <tr>
        <td class="td_center">PABELLON</td>
        <td class="td_center">PISO</td>
        <td class="td_center">HABITACION</td>
        <td class="td_center">CAMA</td>
    </tr>
  </table>
  @endif


  <table border="0px" style="background-color:white !important">
    <tr>
      <!--<td class="titulos" width="14.5%">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        &nbsp;
                    </div>
                </td>-->
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $prestacion->codigo_upss.'-'.$prestacion->servicio; ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $prestacion->medico_nombre." ".$prestacion->medico_paterno." ".$prestacion->medico_materno ?>
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          @if($prestacion->id_tipo_atencion==1)
             <?php 
                if($prestacion->id_modalidad==1){
                  echo "PRESENCIAL";
                }
                else
                {
                  echo "VIRTUAL";
                }
              ?>
          @endif
        </div>
      </td>
    </tr>
    <tr>
      <!--<td class="td_center">CONSULTORIA - CAMA</td>-->
      <td class="td_center"><strong>UPPS - SERVICIO</strong></td>
      <td class="td_center"><strong>MEDICO</strong></td>
      <td class="td_center">
        @if($prestacion->id_tipo_atencion==1)
          <strong>MODALIDAD</strong>
        @endif
      </td>
    </tr>
  </table>

  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr><td width="250" valign="top"><b>ANAMNESIS</b></td></tr>
    <tr>
      <td width="250" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>MOTIVO DE VISITA</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p><?php echo $prestacion->motivo_visita?></p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>TIEMPO DE ENFERMEDAD</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p><?php echo $prestacion->tiempo_enfermedad?></p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>RELATO CRONOLOGICO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p><?php echo $prestacion->anamnesis?></p>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>

  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="250" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>EXAMEN FISICO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                {{{$prestacion->examen_fisico}}}
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>EXAMEN CLINICO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                {{{$prestacion->examen_clinico}}}
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>EXAMENES AUXILIARES</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{{{$prestacion->examen_auxiliar}}}</p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>TRATAMIENTO AUXILIAR</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{{{$prestacion->tratamiento_auxiliar}}}</p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>PLAN DE TRABAJO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{{{$prestacion->plan_trabajo}}}</p>
              </td>
            </tr>           
          </tbody>
        </table>
      </td>
    </tr>
  </table>
  <?php if (!empty($prestacion->aspectos_generales)) { ?>
    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="250" valign="top">
          <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
            <tbody>
              <tr>
                <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                  <b>ASPECTOS GENERALES</b>
                </td>
              </tr>
              <tr>
                <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                  <?php 
                    $aux_aspectos_generales = $prestacion->aspectos_generales;
                    if (!empty($aux_aspectos_generales)) {
                      echo str_replace(chr(13), '<br>', $prestacion->aspectos_generales);
                    }
                  ?>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </table>
  <?php } ?>
  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="250" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td colspan="4" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>DIAGNOSTICOS</b>
              </td>
            </tr>
            <tr>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CODIGO
              </td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">NOMBRE</td>
              <td width="30" class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">TIPO</td>
            </tr>
            <?php $n = 0; ?>
            @foreach($diagnostico as $p)
            <?php $n++;?>
            <tr>
              <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
              <td style="border:1px solid #A4A4A4">{{$p->codigo}}</td>
              <td style="border:1px solid #A4A4A4">{{$p->nombre}}</td>
              <td style="border:1px solid #A4A4A4"><?php switch ($p->id_tipo_diagnostico) {
                                                    case 1: echo 'PRESUNTIVO'; break;
                                                    case 2: echo 'DEFINITIVO'; break;
                                                    case 3: echo 'REITERATIVO'; break;
                                                  }
                                                  ?>                                                  
              </td>
            </tr>
            @endforeach

          </tbody>
        </table>
      </td>
    </tr>
  </table>

  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="250" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>PROCEDIMIENTOS MÉDICOS</b>
              </td>
            </tr>
            <tr>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CODIGO
              </td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">NOMBRE</td>
            </tr>
            <?php $n = 0; ?>
            @foreach($procedimiento as $p)
            <?php $n++;?>
            <tr>
              <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
              <td style="border:1px solid #A4A4A4">{{$p->codigo}}</td>
              <td style="border:1px solid #A4A4A4">{{$p->nombre}}</td>
            </tr>
            @endforeach

          </tbody>
        </table>
      </td>
    </tr>
  </table>
                  
  <table style="margin-top: 10px">
    <tr>
      <td class="td_ancho_espacios"></td>
      <td class="td_ancho_espacios"></td>
    </tr>
  </table>

  <footer>
    
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
