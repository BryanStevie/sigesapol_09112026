<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>EVALUACION MEDICA</title>
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

  table.table-firmas tr {
      border: none;
  }

  table.table-firmas td {
      border: none;
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

<body style="font-size: 12px; font-family: arial;border:1px solid #A4A4A4;padding:10px;background-color:white !important">
  
    @if ($is_habilitado_firma_electronica && intval($evaluacion->flag) === 2)
      <table class="table-firmas style24" width="100%" style="border: none;">
          <tbody>
              <tr>
                  <td width="40%"><p align="right"><img src="images/logo_sanidad.png" width="55px;" /></p></td>
                  <td width="60%">
                      <p align="left">
                          Firmado electrónicamente por: 
                          <br/>{{ $evaluacion->medico_paterno }} {{ $evaluacion->medico_materno }} {{ ucwords(strtolower($evaluacion->medico_nombre)) }}
                          <br/>Motivo: Soy el autor del documento
                          <br/>Fecha: {{ date("d/m/Y H:i:s ", strtotime($evaluacion->fecha_firma)) }}
                      </p>
                  </td>
              </tr>
          </tbody>
      </table>
    @endif
  

  <table style="margin:0px;padding:0px;padding-bottom:10px;">
    <tbody>
      <tr>
        <td colspan="2" class="td_left logo">
          <img src="images/logo_sanidad.png" width="60" />
        </td>
        <td colspan="2" width="90%" style="padding-right:80px;">
          <table>
            <tr>
              <h2>
                EVALUACION MEDICA Nº <?php echo $evaluacion->id ?>
              </h2>
            </tr>
            <tr>
              <td style="text-align: center"><b>CUPS PNP</b>
                @if(isset($cups_pnp->cups)){{$cups_pnp->cups}}
                @else {{'---------------------------'}}@endif</td>
            </tr>
            <tr>
              <td style="text-align: center"><b>COD SITEDS</b>
                @if(isset($evaluacion->cod_siteds_susalud)){{$evaluacion->cod_siteds_susalud}}
                @else {{'--------'}}@endif</td>
            </tr>
          </table>
        </td>
      </tr>
    </tbody>
  </table>

  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $evaluacion->tipo_atencion ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <p><?php echo $evaluacion->establecimiento?></p>
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php
                        if($evaluacion->fecha_atencion <> ""){
                        $fecha_atencion = date("d-m-Y", strtotime($evaluacion->fecha_atencion));
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
          <?php echo $evaluacion->nro_doc_ident ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $evaluacion->asegurado_paterno . " " . $evaluacion->asegurado_materno . ", " . $evaluacion->asegurado_nombre ?>
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $evaluacion->nro_historia."&nbsp;" ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong><?php echo $evaluacion->tipo_doc_ident ?> DEL PACIENTE</strong></td>
      <td class="td_center"><strong>APELLIDOS Y NOMBRES COMPLETOS DEL PACIENTE</strong></td>
      <td class="td_center"><strong>HISTORIA CLINICA</strong></td>
    </tr>
  </table>


  <table border="0px" style="background-color:white !important">
    <tr>
      <!--<td class="titulos" width="14.5%">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        &nbsp;
                    </div>
                </td>-->
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $evaluacion->servicio ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $evaluacion->medico_nombre." ".$evaluacion->medico_paterno." ".$evaluacion->medico_materno ?>
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
      </td>
    </tr>
    <tr>
      <!--<td class="td_center">CONSULTORIA - CAMA</td>-->
      <td class="td_center"><strong>SERVICIO</strong></td>
      <td class="td_center"><strong>MEDICO</strong></td>
      <td class="td_center">
        <!--<strong>TURNO</strong>-->
      </td>
    </tr>
  </table>

  @if(count($diagnostico)>0)
  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="250" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>DIAGNOSTICOS</b>
              </td>
            </tr>
            <tr>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CODIGO
              </td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">NOMBRE</td>
            </tr>
            <?php $n = 0; ?>
            @foreach($diagnostico as $p)
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
  @endif
  @if(count($procedimiento)>0)
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
  @endif
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
  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
        INFORMACIÓN DE SIGNOS VITALES
      </td>
    </tr>
  </table>
  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$evaluacion->temperatura}} °C
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$evaluacion->talla_cm}} cm
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$evaluacion->peso_kg}} kg
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$evaluacion->sa02_porcentaje}} %
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$evaluacion->tamano_cintura_cm}} cm
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>TEMPERATURA (°C)</strong></td>
      <td class="td_center"><strong>TALLA (cm)</strong></td>
      <td class="td_center"><strong>PESO (Kg)</strong></td>
      <td class="td_center"><strong>Sa02 (%)</strong></td>
      <td class="td_center"><strong>TAMAÑO DE CINTURA (cm)</strong></td>
    </tr>
    <tr>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$evaluacion->frecuencia_cardiaca_fetal}} /min
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
           {{$evaluacion->presion_arterial_x}} 
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$evaluacion->frecuencia_respiratoria}} /min
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$evaluacion->frecuencia_cardiaca}} /min
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$evaluacion->fi02}} %
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>FREC. CARDIACA FETAL (/min)</strong></td>
      <td class="td_center"><strong>PRESIÓN ARTERIAL</strong></td>
      <td class="td_center"><strong>FREC. RESPIRATORIA (/min)</strong></td>
      <td class="td_center"><strong>FREC. CARDIACA (/min)</strong></td>
      <td class="td_center"><strong>Fi02 (%)</strong></td>
    </tr>
  </table>
  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr><td width="250" valign="top"><b>ANAMNESIS</b></td></tr>
  </table>
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>MOTIVO DE VISITA</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{{$evaluacion->motivo_visita}}</p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>TIEMPO DE ENFERMEDAD / RELATO CRONOLOGICO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{{$evaluacion->tiempo_enfermedad}}</p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>EXAMEN AUXILIARES</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{{$evaluacion->examen_auxiliar}}</p>
              </td>
            </tr>
          </tbody>
        </table>

  
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>EXAMEN FISICO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                {{$evaluacion->examen_fisico}}
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>EXAMENES CLINICO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{{$evaluacion->examen_clinico}}</p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>TRATAMIENTO AUXILIAR</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <p>{!! nl2br(e($evaluacion->tratamiento_auxiliar)) !!}</p>
              </td>
            </tr>
            <tr>
              <td width="10%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                <b>PLAN DE TRABAJO</b>
              </td>
              <td width="90%" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
              <p>{!! str_replace(["\r\n", "\n", "\r"], '<br>', html_entity_decode($evaluacion->plan_trabajo)) !!}</p>
              </td>
            </tr>           
          </tbody>
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

</html>