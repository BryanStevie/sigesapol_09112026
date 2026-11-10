<?php 
  function GetNroHistoria(&$row) {
    $sufijo = '';
    if (!empty($row->id_tipo_cita)) {
      switch((int)$row->id_tipo_cita) {
        case 1 : $sufijo = 'P'; break;
        case 2 : $sufijo = 'T'; break;
      }
    }
    return str_pad($row->nro_historia . $sufijo, 8, '0', STR_PAD_LEFT);
  }
?>
<body style="font-size: 12px; font-family: arial;border:1px solid #A4A4A4;padding:10px">
  <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
  <style>
    table {
      border-collapse: inherit;
    }

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
      font-size: 11px !important;
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
  </style>

  <table style="margin:0px;padding:0px;padding-bottom:10px">
    <tr>
      <td colspan="1" class="td_left logo">
        <img src="images/logo_sanidad.png" width="60" />
      </td>
      <td colspan="2" class="titulo_principal">
        <table border="0px" style="background-color:white !important">
          <tr>
            <td colspan="4" style="padding-right:90px">
              <h2><?php echo $establecimiento_nombre ?></h2>
            </td>
          </tr>
          <tr style="padding:0px;margin:0px">
            <td width="12%" style="text-align:left">FECHA DE CITA :</td>
            <td width="10%" style="text-align:left"><?php echo $fecha_ini ?></td>
            <td width="10%" style="text-align:right">SERVICIO :</td>
            <td width="20%" style="text-align:left"><?php echo $nombre_servicio ?></td>
          </tr>
          <tr style="padding:0px;margin:0px">
            <td width="10%" style="text-align:left">CONSULTORIO :</td>
            <td width="5%" style="text-align:left"><?php echo $nombre_consultorio ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table style="background-color:white !important;padding:0px;margin:0px" width="96%">

    <tr>
      <td class="titulos_" width="14%" valign="top">
        <table style="padding:0px;margin:0px">
          <?php
          $cantidad = 1;
          foreach ($citas as $key => $row) {
            if ($row->nro_historia != "" && $cantidad <= 35) {
          ?>
              <tr>
                <td style="font-size:18px!important;line-height:25px;text-align:left"><?php echo GetNroHistoria($row); ?></td>
                <td><?php echo '<div style="border: 1px solid #000000;float:left;display:block;width:15px;height:15px"></div>' ?></td>
              </tr>
          <?php
              $cantidad++;
            }
          }
          ?>
        </table>
      </td>
      <td class="titulos_" width="14%" valign="top">
        <table style="padding:0px;margin:0px;margin-left:20px">
          <?php
          $cantidad = 0;
          foreach ($citas as $key => $row) {
            if ($row->nro_historia != "") {
              if ($cantidad > 35 && $cantidad <= 70) {
          ?>
                <tr>
                  <td style="font-size:18px!important;line-height:25px;text-align:left"><?php echo GetNroHistoria($row); ?></td>
                  <td><?php echo '<div style="border: 1px solid #000000;float:left;display:block;width:15px;height:15px"></div>' ?></td>
                </tr>
          <?php
              }
              $cantidad++;
            }
          }
          ?>
        </table>
      </td>
      <td class="titulos_" width="14%" valign="top">
        <table style="padding:0px;margin:0px;margin-left:20px">
          <?php
          $cantidad = 0;
          foreach ($citas as $key => $row) {
            if ($row->nro_historia != "") {
              if ($cantidad > 70 && $cantidad <= 105) {
          ?>
                <tr>
                  <td style="font-size:18px!important;line-height:25px;text-align:left"><?php echo GetNroHistoria($row); ?></td>
                  <td><?php echo '<div style="border: 1px solid #000000;float:left;display:block;width:15px;height:15px"></div>' ?></td>
                </tr>
          <?php
              }
              $cantidad++;
            }
          }
          ?>
        </table>
      </td>
      <td class="titulos_" width="14%" valign="top">
        <table style="padding:0px;margin:0px;margin-left:20px">
          <?php
          $cantidad = 0;
          foreach ($citas as $key => $row) {
            if ($row->nro_historia != "") {
              if ($cantidad > 105 && $cantidad <= 140) {
          ?>
                <tr>
                  <td style="font-size:18px!important;line-height:25px;text-align:left"><?php echo GetNroHistoria($row); ?></td>
                  <td><?php echo '<div style="border: 1px solid #000000;float:left;display:block;width:15px;height:15px"></div>' ?></td>
                </tr>
          <?php
              }
              $cantidad++;
            }
          }
          ?>
        </table>
      </td>
      <td class="titulos_" width="14%" valign="top">
        <table style="padding:0px;margin:0px;margin-left:20px">
          <?php
          $cantidad = 0;
          foreach ($citas as $key => $row) {
            if ($row->nro_historia != "") {
              if ($cantidad > 140 && $cantidad <= 175) {
          ?>
                <tr>
                  <td style="font-size:18px!important;line-height:25px;text-align:left"><?php echo GetNroHistoria($row); ?></td>
                  <td><?php echo '<div style="border: 1px solid #000000;float:left;display:block;width:15px;height:15px"></div>' ?></td>
                </tr>
          <?php
              }
              $cantidad++;
            }
          }
          ?>
        </table>
      </td>
    </tr>

  </table>
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