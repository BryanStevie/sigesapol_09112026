<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PDF-CONSEJERIA-HOSPITALIZACION</title>
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
      font-size: 9px;
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

    @page { 
      margin-bottom: 50px; 
    }
    footer { 
      position: fixed;
      bottom: 0;
      width: 100%;
      text-align: center;
      font-size: 10px;
    }
  </style>
</head>
<body style="font-size: 12px; font-family: arial; background-color:white !important">
  <table style="margin:0px;padding:0px;padding-bottom:10px;">
    <tbody>
      <tr>
        <td colspan="2" class="td_left logo">
          <img src="images/logo_sanidad.png" width="60" />
        </td>
        <td colspan="2" width="90%" style="padding-right:80px;">
          <h2 style="text-align: center">
            PROCEDIMIENTO Nº {{ $consejeria->consejeria_id }}
          </h2>
        </td>
      </tr>
    </tbody>
  </table>
  <table>
    <tr>
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$consejeria->tipo_atencion}}
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$consejeria->establecimiento}}
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{ date("d-m-Y H:i:s", strtotime($consejeria->consejeria_fecha_atencion)) }}
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>TIPO DE ATENCIÓN</strong></td>
      <td class="td_center"><strong>ESTABLECIMIENTO</strong></td>
      <td class="td_center"><strong>FECHA DE ATENCION</strong></td>
    </tr>
  </table>
  <table style="background-color:white !important">
    <tr>
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$consejeria->asegurado_nro_doc_ident}}
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$consejeria->asegurado_paterno}}  {{$consejeria->asegurado_materno}}, {{$consejeria->asegurado_nombre}}
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$consejeria->nro_historia}}
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong><?php echo $consejeria->tipo_documento_identidad ?> DEL PACIENTE</strong></td>
      <td class="td_center"><strong>APELLIDOS Y NOMBRES COMPLETOS DEL PACIENTE</strong></td>
      <td class="td_center"><strong>HISTORIA CLINICA</strong></td>
    </tr>
  </table>
  <table style="background-color:white !important">
    <tr>
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $consejeria->consultorio ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $consejeria->medico_nombre." ".$consejeria->medico_paterno.", ".$consejeria->medico_materno ?>
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $consejeria->medico_colegiatura ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>SERVICIO DEL PROFESIONAL</strong></td>
      <td class="td_center"><strong>MEDICO</strong></td>
      <td class="td_center"><strong>COLEGIATURA</strong></td>
    </tr>
  </table>

  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="250" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>DIAGNOSTICOS</b></td>
            </tr>
            <tr>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CODIGO
              </td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">NOMBRE</td>
            </tr>
            @if (isset($diagnosticos) && count($diagnosticos) > 0)
              <?php $n = 0; ?>
              @foreach($diagnosticos as $p)
                <?php $n++;?>
                <tr>
                  <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
                  <td style="border:1px solid #A4A4A4">{{$p->codigo}}</td>
                  <td style="border:1px solid #A4A4A4"><?php echo strtoupper($p->nombre); ?></td>
                </tr>
              @endforeach
            @endif
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
                <b>PROCEDIMIENTOS</b></td>
            </tr>
            <tr>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CODIGO
              </td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">NOMBRE</td>
            </tr>
            @if (isset($procedimientos) && count($procedimientos) > 0)
              <?php $n = 0; ?>
              @foreach($procedimientos as $p)
              <?php $n++;?>
              <tr>
                <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
                <td style="border:1px solid #A4A4A4">{{$p->codigo}}</td>
                <td style="border:1px solid #A4A4A4"><?php echo strtoupper($p->nombre) ?></td>
              </tr>
              @endforeach
            @endif
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
                <b>DESCRIPCIÓN</b>
              </td>
            </tr>
            <tr>
              <td colspan="3" style="border:1px solid #A4A4A4">
                <?php echo htmlspecialchars($consejeria->observacion); ?>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>


  <footer>
    <table>
      <thead>
        <tr style="font-weight: bold">
          <th style="border: 1px solid #000; padding: 2px">Apellido Paterno</th>
          <th style="border: 1px solid #000; padding: 2px">Apellido Materno</th>
          <th style="border: 1px solid #000; padding: 2px">Nombres</th>
          <th style="border: 1px solid #000; padding: 2px">Historia Clínica</th>
          <th style="border: 1px solid #000; padding: 2px">Servicio</th>
          <th style="border: 1px solid #000; padding: 2px">N° de Cama</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="border: 1px solid #000; padding: 2px;">{{$consejeria->asegurado_paterno}}</td>
          <td style="border: 1px solid #000; padding: 2px;">{{$consejeria->asegurado_materno}}</td>
          <td style="border: 1px solid #000; padding: 2px;">{{$consejeria->asegurado_nombre}}</td>
          <td style="border: 1px solid #000; padding: 2px;">{{$consejeria->nro_historia}}</td>
          <td style="border: 1px solid #000; padding: 2px;">{{$consejeria->consultorio}}</td>
          <td style="border: 1px solid #000; padding: 2px;">{{isset($consejeria->cama) ? $consejeria->cama : "-"}}</td>
        </tr>
      </tbody>
    </table>
  </footer>
</body>
</html>