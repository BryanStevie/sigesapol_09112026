<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SOLICITUD DE REQUERIMIENTO</title>
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

     <table style="margin:0px;padding:0px;padding-bottom:10px;">
        <tbody>
            <tr>
                <td colspan="2" class="td_left logo">
                    <img src="images/logo_sanidad.png" width="60" />
                </td>
                <td colspan="2" width="90%" style="padding-right:80px;">
                    <h2>
                        SOLICITUD DE REQUERIMIENTO Nº <?php echo $solicitud_requerimiento->id; ?>
                    </h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table border="0px" style="background-color:white !important">
        <tr>
            <td class="td_center"><strong>PRIORIDAD</strong></td>
            <td class="td_center"><strong>DNI Solicitante</strong></td>
            <td class="td_center"><strong>Nombres y apellidos</strong></td>
        </tr>
        <tr>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4; @if($solicitud_requerimiento->id_prioridad_requerimiento == 1) color: red; @elseif($solicitud_requerimiento->id_prioridad_requerimiento == 2) color:orange; @else color:green; @endif">
                    <?php echo $solicitud_requerimiento->prioridad->nombre; ?>
                </div>
            </td>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                    <?php echo $solicitud_requerimiento->dni; ?>
                </div>
            </td>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                    <?php echo $solicitud_requerimiento->nombres. ' ' .$solicitud_requerimiento->paterno. ' ' .$solicitud_requerimiento->materno; ?>
                </div>
            </td>
        </tr>
    </table>
    @if ($solicitud_requerimiento->cip != null)
    <table border="0px" style="background-color:white !important">
        <tr>
            <td class="td_center">
                <strong>CIP</strong>
            </td>
            <td class="td_center">
                <strong>Grado</strong>
            </td>
        </tr>
        <tr>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                    <?php echo $solicitud_requerimiento->cip; ?>
                </div>
            </td>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                    <?php echo $solicitud_requerimiento->grado; ?>
                </div>
            </td>
        </tr>
    </table>
    @endif
    <table border="0px" style="background-color:white !important">
        <tr>
            <td class="td_center">
                <strong>RNE</strong>
            </td>
            <td class="td_center">
                <strong>Colegiatura</strong>
            </td>
            <td class="td_center">
                <strong>Especialidad</strong>
            </td>
        </tr>
        <tr>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                    <?php echo $solicitud_requerimiento->rne; ?>
                </div>
            </td>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                    <?php echo $solicitud_requerimiento->colegiatura; ?>
                </div>
            </td>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                    <?php echo $solicitud_requerimiento->especializacion->nombre; ?>
                </div>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="td_center">
                <strong>IPRESS Origen</strong>
            </td>
        </tr>
        <tr>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                    <?php echo $solicitud_requerimiento->ipress->nombre; ?>
                </div>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="td_center">
                <strong>Atencion</strong>
            </td>
            <td class="td_center">
                <strong>Servicio</strong>
            </td>
        </tr>
        <tr>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                    <?php echo $solicitud_requerimiento->tipoAtencion->nombre; ?>
                </div>
            </td>
            <td class="grado titulos">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                    <?php echo $solicitud_requerimiento->servicio->nombre; ?>
                </div>
            </td>
        </tr>
    </table>
    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="250" valign="top">
            <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
              <tbody>
                <tr>
                  <td width="50%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                    <b>ASUNTO</b>
                  </td>
                  <td width="50%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                    {{{$solicitud_requerimiento->asunto}}}
                  </td>
                </tr>
                <tr>
                  <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                    <b>Descripcion del requerimiento</b>
                  </td>
                  <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                    {{{$solicitud_requerimiento->descripcion}}}
                  </td>
                </tr>           
              </tbody>
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
