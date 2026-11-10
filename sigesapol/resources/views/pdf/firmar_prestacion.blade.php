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

  .row_qr {
    padding-bottom: 0;
    margin-bottom: 0;
  }

  .row_qr > * {
    padding: 0 !important;
    margin: 0 !important;
  }

  .span_url_descarga{
    margin: 0 !important;
    margin-top: -20px !important;
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

  <table style="margin:0px;padding:0px;padding-bottom:0px;">
    <tbody>
      <tr>
        <td width="100%" style="">
          <h3 style="margin: 0; padding: 0; margin-bottom: 6px;">
            @if ($prestacion->id_tipo_atencion == 1)
              <span style="margin: 0;color: rgb(111, 204, 111)">CONSULTA EXTERNA</span> - CUPS: <span style="margin: 0; font-weight: 100;">{{$prestacion->codigo_cups}}</span>
            @elseif($prestacion->id_tipo_atencion == 2)
              <span style="margin: 0;color: rgb(210, 219, 82)">EMERGENCIA</span> - CUPS: <span style="margin: 0; font-weight: 100;">@if ($emergencia_triaje != null){{$emergencia_triaje->cups_pnp}}@endif</span>
            @elseif($prestacion->id_tipo_atencion == 3)
              <span style="margin: 0;color: rgb(196, 54, 54)">HOSPITALIZACION</span> - CUPS: <span style="margin: 0; font-weight: 100;">{{$prestacion->codigo_cups}}</span>
            @elseif($prestacion->id_tipo_atencion == 7)
              <span style="margin: 0;color: rgb(210, 219, 82)">URGENCIA</span> - CUPS: <span style="margin: 0; font-weight: 100;">{{$prestacion->codigo_cups}}</span>
            @endif
          </h3>
        </td>
      </tr>
      <tr>
        <td width="100%" style="">
          <h3 style="margin: 0; padding: 0; margin-bottom: 6px;">
            PRESTACION MEDICA Nº <?php echo $prestacion->id ?>
          </h3>
        </td>
      </tr>
      <tr>
        <td width="100%" >
          <span style="margin: 0; font-weight: bold; font-size: 9px !important">CUPS PNP @if(isset($cups_pnp->cups)){{$cups_pnp->cups}}@else {{'-------------------------'}}@endif</span></td>
      </tr>
      <tr>
        <td width="100%"   >
          <span style="margin: 0; font-weight: bold; font-size: 8px !important">COD.SITEDS @if(isset($prestacion->codigo_siteds)){{$prestacion->codigo_siteds}}@else {{'-------'}}@endif</span></td>
      </tr>
    </tbody>
  </table>

  <table border="0px" style="background-color:white !important">
    <tr>
      <!--<td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php //echo $receta->codigoipress ?>
                    </div>
                </td>-->
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
      <!--<td class="grado titulos" style="padding-right:0px">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php
                        /*$turno = "--";
                        if ($receta->turno == "M")
                            $turno = "MAÑANA";
                        if ($receta->turno == "T")
                            $turno = "TARDE";
                        if ($receta->turno == "N")
                            $turno = "NOCHE";
                        if ($receta->turno == "MD")
                            $turno = "MADRUGADA";
                        echo $turno
                        */?>
                    </div>
                </td>-->
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
                  if($prestacion->id_modalidad==2){
                    echo "VIRTUAL";
                  }
                  else{
                    echo "EXTRAMURAL";
                  }
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
                <p><?php echo $prestacion->motivo_visita == '' ? 'No se registro informacion.' : $prestacion->motivo_visita?></p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>TIEMPO DE ENFERMEDAD</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p><?php echo $prestacion->tiempo_enfermedad == '' ? 'No se registro informacion.' : $prestacion->tiempo_enfermedad?></p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>RELATO CRONOLOGICO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p><?php echo $prestacion->anamnesis == '' ? 'No se registro informacion.' : $prestacion->anamnesis?></p>
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
                {{{$prestacion->examen_fisico == '' ? 'No se registro informacion.' : $prestacion->examen_fisico}}}
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>EXAMEN CLINICO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                {{{$prestacion->examen_clinico == '' ? 'No se registro informacion.' : $prestacion->examen_clinico}}}
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>EXAMENES AUXILIARES</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{{{$prestacion->examen_auxiliar == '' ? 'No se registro informacion.' : $prestacion->examen_auxiliar}}}</p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>TRATAMIENTO AUXILIAR</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{{{$prestacion->tratamiento_auxiliar == '' ? 'No se registro informacion.' : $prestacion->tratamiento_auxiliar}}}</p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>PLAN DE TRABAJO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{{{$prestacion->plan_trabajo == '' ? 'No se registro informacion.' : $prestacion->plan_trabajo}}}</p>
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
  <!--
        <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
            <tr><td width="450" valign="top">
                    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>                                
                                <td width="40%"></td>
                                <td ><p align="right"><img src="images/logo_sanidad.png" width="150px;" /></p></td>
                                <td ><p style="font-size: 9px;"  align="left">Validado digitalmente por: <br/></?php echo $prestacion->medico_nombre." ".$prestacion->medico_paterno." ".$prestacion->medico_materno ?><br/> DNI:</?php echo $prestacion->dni ?> - CMP: </?php echo$prestacion->colegiatura ?><br/>Motivo: Soy el autor del documento <br/></?php echo$prestacion->fecha_firma ?></p></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
                    -->
                    @if(isset($emergencia_triaje) && isset($emergencia_triaje->id_gestacion))
                    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="250" valign="top">
                                <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
                                    <tbody>
                                        <tr>
                                            <td colspan="4" style="text-align: center; border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                                                <b>GESTACION</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">FECHA REGLA</td>
                                            <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">FECHA_PROBABLE_PARTO</td>
                                            <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">SEMANAS_GESTACION</td>
                                            <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N° CONTROLES</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #A4A4A4">{{$emergencia_triaje->fech_ult_regla}}</td>
                                            <td style="border:1px solid #A4A4A4">{{$emergencia_triaje->fech_prob_parto}}</td>
                                            <td style="border:1px solid #A4A4A4">{{$emergencia_triaje->semanas_gestacion}}</td>
                                            <td style="border:1px solid #A4A4A4">{{$emergencia_triaje->num_controles}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    @endif

                    @if ($emergencia_triaje && $emergencia_triaje->id_puerperio)
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>EVENTOS EPIDEMIOLÓGICOS</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="row">
                              <div class="col-md-6">
                                [{!! in_array(11, $eventosMarcados) ? 'x' : ' ' !!}] Gestantes que han recibido por lo menos un control prenatal en la IPRESS
                              </div>
                              <div class="col-md-6">
                                [{!! in_array(12, $eventosMarcados) ? 'x' : ' ' !!}] Gestantes que han recibido por lo menos 6 controles prenatales
                              </div>
                              <div class="col-md-6">
                                [{!! in_array(13, $eventosMarcados) ? 'x' : ' ' !!}] Gestantes que han recibido la primera consulta en el primer trimestre del embarazo
                              </div>
                              <div class="col-md-6">
                                [{!! in_array(16, $eventosMarcados) ? 'x' : ' ' !!}] Nacidos vivos con bajo peso al nacer (menos de 2500 gramos)
                              </div>
                              <div class="col-md-6">
                                [{!! in_array(21, $eventosMarcados) ? 'x' : ' ' !!}] Nacidos vivos con edad gestacional menor a 37 semanas de gestación (según fecha última regla)
                              </div>
                              <div class="col-md-6">
                                [{!! in_array(15, $eventosMarcados) ? 'x' : ' ' !!}] Gestantes con diagnóstico confirmado de infección HIV
                              </div>
                              <div class="col-md-6">
                                [{!! in_array(14, $eventosMarcados) ? 'x' : ' ' !!}] Gestantes con diagnóstico confirmado de Tuberculosis
                              </div>
                              <div class="col-md-6">
                                [{!! in_array(8, $eventosMarcados) ? 'x' : ' ' !!}] Fallecimiento del paciente
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    @endif

                    @if(isset($emergencia_triaje) && isset($emergencia_triaje->id_puerperio))

                    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
                        <tbody>
                            <tr>
                                <td colspan="3" style="text-align: center; border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                                    <b>PUERPERIO</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">FECHA PARTO</td>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">TIPO PARTO</td>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">COMPLICACIONES</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #A4A4A4">{{$emergencia_triaje->fecha_parto}}</td>
                                <td style="border:1px solid #A4A4A4">
                                    @if($emergencia_triaje->id_tipo_parto == 1)
                                    VAGINAL
                                    @elseif($emergencia_triaje->id_tipo_parto == 2)
                                    CESÁREA
                                    @else
                                    {{$emergencia_triaje->id_tipo_parto}}
                                    @endif
                                </td>
                                <td style="border:1px solid #A4A4A4">{{$emergencia_triaje->complicaciones}}</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border:1px solid #A4A4A4">
                                    <b>Descripción:</b> {{$emergencia_triaje->descripcion}}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @endif

                    @if (isset($natis) && count($natis) > 0)
                    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
                        <tbody>
                            <tr>
                                <td colspan="4" style="text-align: center; border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                                    <b>NEONATOS</b>
                                </td>
                            </tr>
                            @foreach ($natis as $nati)
                            <tr>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                                    <label style="font-weight: bold;">ESTADO DE VIDA:</label><br>
                                    <p>{{$nati->estado}}</p>
                                </td>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 20%;">
                                    <label style="font-weight: bold;">TIPO DE DOCUMENTOS:</label><br>
                                    <p>
                                        @if($nati->id_tipo_doc_nati == 1)
                                            DNI
                                        @elseif($nati->id_tipo_doc_nati == 2)
                                            CNV
                                        @elseif($nati->id_tipo_doc_nati == 3)
                                            AFILIACION DEL RNE
                                        @else
                                            Desconocido
                                        @endif
                                    </p>
                                </td>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                                    <label style="font-weight: bold;">NUM. DOCUMENTOS:</label><br>
                                    <p>{{$nati->num_doc}}</p>
                                </td>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                                    <label style="font-weight: bold;">SEXO:</label><br>
                                    <p>
                                        @if($nati->sexo == 'M')
                                            MASCULINO
                                        @elseif($nati->sexo == 'F')
                                            FEMENINO
                                        @else
                                            Desconocido
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                
                    {{-- @if(isset($emergencia_triaje) && isset($emergencia_triaje->id_puerperio))

                    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
                        <tbody>
                            <tr>
                                <td colspan="3" style="text-align: center; border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                                    <b>PUERPERIO</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">FECHA PARTO</td>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">TIPO PARTO</td>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">COMPLICACIONES</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #A4A4A4">{{$emergencia_triaje->fecha_parto}}</td>
                                <td style="border:1px solid #A4A4A4">
                                    @if($emergencia_triaje->id_tipo_parto == 1)
                                    VAGINAL
                                    @elseif($emergencia_triaje->id_tipo_parto == 2)
                                    CESÁREA
                                    @else
                                    {{$emergencia_triaje->id_tipo_parto}}
                                    @endif
                                </td>
                                <td style="border:1px solid #A4A4A4">{{$emergencia_triaje->complicaciones}}</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border:1px solid #A4A4A4">
                                    <b>Descripción:</b> {{$emergencia_triaje->descripcion}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                    
                    @if (isset($natis) && count($natis) > 0)
                    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
                        <tbody>
                            <tr>
                                <td colspan="4" style="text-align: center; border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                                    <b>NEONATOS</b>
                                </td>
                            </tr>
                            @foreach ($natis as $nati)
                            <tr>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                                    <label style="font-weight: bold;">ESTADO DE VIDA:</label><br>
                                    <p>{{$nati->estado}}</p>
                                </td>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 20%;">
                                    <label style="font-weight: bold;">TIPO DE DOCUMENTOS:</label><br>
                                    <p>
                                        @if($nati->id_tipo_doc_nati == 1)
                                            DNI
                                        @elseif($nati->id_tipo_doc_nati == 2)
                                            CNV
                                        @elseif($nati->id_tipo_doc_nati == 3)
                                            AFILIACION DEL RNE
                                        @else
                                            Desconocido
                                        @endif
                                    </p>
                                </td>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                                    <label style="font-weight: bold;">NUM. DOCUMENTOS:</label><br>
                                    <p>{{$nati->num_doc}}</p>
                                </td>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                                    <label style="font-weight: bold;">SEXO:</label><br>
                                    <p>
                                        @if($nati->sexo == 'M')
                                            MASCULINO
                                        @elseif($nati->sexo == 'F')
                                            FEMENINO
                                        @else
                                            Desconocido
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif --}}

                    {{-- @if(isset($emergencia_triaje ) && isset($emergencia_triaje->id_puerperio))

                    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
                        <tbody>
                            <tr>
                                <td colspan="3" style="text-align: center; border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                                    <b>PUERPERIO</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">FECHA PARTO</td>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">TIPO PARTO</td>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">COMPLICACIONES</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #A4A4A4">{{$emergencia_triaje->fecha_parto}}</td>
                                <td style="border:1px solid #A4A4A4">
                                    @if($emergencia_triaje->id_tipo_parto == 1)
                                    VAGINAL
                                    @elseif($emergencia_triaje->id_tipo_parto == 2)
                                    CESÁREA
                                    @else
                                    {{$emergencia_triaje->id_tipo_parto}}
                                    @endif
                                </td>
                                <td style="border:1px solid #A4A4A4">{{$emergencia_triaje->complicaciones}}</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border:1px solid #A4A4A4">
                                    <b>Descripción:</b> {{$emergencia_triaje->descripcion}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endif --}}
                    




                    {{-- @if (isset($natis) && count($natis) > 0)
                    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
                        <tbody>
                            <tr>
                                <td colspan="4" style="text-align: center; border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                                    <b>NEONATOS</b>
                                </td>
                            </tr>
                            @foreach ($natis as $nati)
                            <tr>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                                    <label style="font-weight: bold;">ESTADO DE VIDA:</label><br>
                                    <p>{{$nati->estado}}</p>
                                </td>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 20%;">
                                    <label style="font-weight: bold;">TIPO DE DOCUMENTOS:</label><br>
                                    <p>
                                        @if($nati->id_tipo_doc_nati == 1)
                                            DNI
                                        @elseif($nati->id_tipo_doc_nati == 2)
                                            CNV
                                        @elseif($nati->id_tipo_doc_nati == 3)
                                            AFILIACION DEL RNE
                                        @else
                                            Desconocido
                                        @endif
                                    </p>
                                </td>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                                    <label style="font-weight: bold;">NUM. DOCUMENTOS:</label><br>
                                    <p>{{$nati->num_doc}}</p>
                                </td>
                                <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                                    <label style="font-weight: bold;">SEXO:</label><br>
                                    <p>
                                        @if($nati->sexo == 'M')
                                            MASCULINO
                                        @elseif($nati->sexo == 'F')
                                            FEMENINO
                                        @else
                                            Desconocido
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif --}}
                
                    
                







                

                  
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
