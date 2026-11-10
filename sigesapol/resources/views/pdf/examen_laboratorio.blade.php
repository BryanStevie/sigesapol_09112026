<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>EXAMEN DE LABORATORIO</title>
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
        </td>
        <td colspan="2" width="30%" style="margin-top:0px;padding-top:0px">
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
            SOLICITUD DE ANALISIS CLINICO Nº <?php echo $prestacion->id ?> - <?php echo $laboratorio->id ?>
          </h2>
        </td>
        <!--<td colspan="2" class="titulos" width="12%" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php //echo $receta->nro_receta ?>
                        </div>
                    </td>-->
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
    </tr>
    <tr>
      <td class="td_center"><strong>TIPO DE ATENCIÓN</strong></td>
      <td class="td_center"><strong>ESTABLECIMIENTO</strong></td>
      <td class="td_center"><strong>FECHA DE ATENCION</strong></td>
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


  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $prestacion->servicio ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $prestacion->medico_nombre." ".$prestacion->medico_paterno." ".$prestacion->medico_materno ?>
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">

      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>SERVICIO</strong></td>
      <td class="td_center"><strong>MEDICO</strong></td>
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

  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="250" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;"><b>LISTADO DE
                  SOLICITUD ANALISIS CLINICO</b></td>
            </tr>
            <tr>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
              <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CODIGO
              </td>
              <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">NOMBRE</td>
            </tr>
            <?php $n = 0; ?>
            @foreach($proclaboratorio as $p)
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
  @if($tipo_examen==1)
  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td width="25%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td width="15%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>VALOR DE
                  REFERENCIA &nbsp;</label></td>
              <td width="25%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td width="15%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>VALOR DE
                  REFERENCIA &nbsp;</label></td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>GLUCOSA &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->glucosa}}</td>
              <td>
                <p align="right">70-110 mg</p>
              </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>PROTEINAS TOTALES</b></p>
              </td>
              <td>{{$resultado_examen->proteinas_totales}}</td>
              <td>
                <p align="right">6.0 - 8.0 g/dl</p>
              </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> UREA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->urea}}</td>
              <td>
                <p align="right">10 - 50 mg/dl</p>
              </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> ALBUMINA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->albumina}}</td>
              <td>
                <p align="right">3.5 - 5.5 g/dl</p>
              </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> CREATININA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->creatinina}}</td>
              <td>
                <p align="right">0.6 - 1.4 mg/dl</p>
              </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> GLOBULINA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->globulinas}}</td>
              <td>
                <p align="right">6.0 - 8.0 g/dl</p>
              </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> COLESTEROL&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->colesterol}}</td>
              <td>
                <p align="right">
                  < 200 mg/dl </p>
              </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> TGO&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->tgo}}</td>
              <td>
                <p align="right">1.5 - 3.0 g/dl</p>
              </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> TRIGLICERIDOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->trigliceridos}}</td>
              <td>
                <p align="right">35 - 150 mg/dl</p>
              </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> TGP&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->tgp}}</td>
              <td></td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> HDL - COLESTEROL &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->hdl_colesterol}}</td>
              <td>
                <p align="right">35 - 65 mg/dl</p>
              </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> BILIRRUBINA TOTAL&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->bilirrubina_total}}</td>
              <td>
                <p align="right">0.2 - 1.0 mg/dl</p>
              </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> LDL - COLESTEROL &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->ldl_colesterol}}</td>
              <td>
                <p align="right">
                  < 150 mg/dl </p>
              </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> BILIRRUBINA DIRECTA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->bilirrubina_directa}}</td>
              <td>
                <p align="right">0.1 - 0.4 mg/dl</p>
              </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> AMILASA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->amilasa}}</td>
              <td>
                <p align="right">16 - 116 UI/dl</p>
              </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> BILIRRUBINA INDIRECTA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->bilirrubina_indirecta}}</td>
              <td>
                <p align="right">0.2 - 0.6 mg/dl</p>
              </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> ACIDO URICO&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->acido_urico}}</td>
              <td>
                <p align="right">2.4 - 7.0 mg/dl</p>
              </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> FOSFATASA ALCALINA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->fosfatasa_alcalina}}</td>
              <td>
                <p align="right">30-125 u/L</p>
              </td>
            </tr>


          </tbody>
        </table>
      </td>
    </tr>
  </table>
  @endif
  @if($tipo_examen==2)
  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td width="23%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td width="23%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td width="23%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>HEMATIES &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->hematies}} xmm3</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>LEUCOCITOS</b></p>
              </td>
              <td>{{$resultado_examen->leucocitos}} xmm3</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> PLAQUETAS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->plaquetas}} xmm3</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> HEMOGLOBINA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->hemoglobina}} gr%</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> NEUTROFILOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->neutrofilos}} %</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> MIELOCITOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->mielocitos}} %</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> HEMATOCRITO&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->hematocrito}} %</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> EOSINOFILOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->eosinofilos}} %</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> METAMIELOCITOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->metamielocitos}} %</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> VSG&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->vsg}} mm a los 60°</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> BASOFILOS &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->basofilos}} %</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> ABASTONADOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->abastonados}} %</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> T. DE COAGULACION&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->t_coagulacion}} %</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> MONOCITOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->monocitos}} %</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> SEGMENTADOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->segmentados}} %</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> T. DE SANGRE&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->t_sangria}} %</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> LINFOCITOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->linfocitos}} %</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> T. DE PROTOMBINA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->t_protombina}} %</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>
  @endif
  @if($tipo_examen==4)
  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td width="13%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  &nbsp;</label></td>
              <td width="12%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td width="13%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  &nbsp;</label></td>
              <td width="12%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td colspan="2" width="50%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>EXAMEN DE DIFERENCIACION CELULAR &nbsp;</label></td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>MUESTRA &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->muestra1}} </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>ASPECTO</b></p>
              </td>
              <td>{{$resultado_examen->aspecto1}} </td>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>PARASITOS &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->parasitos}} </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> CONSISTENCIA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->consistencia}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> COLOR&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->color1}} </td>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>POLIMORFONUCLEARES &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->polimorfonucleares}} %</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> LEUCOCITOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->lecucitos}} </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> HEMATIES&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->hematies2}} </td>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>MONONUCLEARES &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->mononucleares}} %</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> LEVADURAS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->levaduras}} </td>
              <td></td>
              <td></td>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>OBSERVACIONES &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->observaciones1}} </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td width="13%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  &nbsp;</label></td>
              <td width="12%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td width="13%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  &nbsp;</label></td>
              <td width="12%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td colspan="2" width="50%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>EXAMEN QUIMICO &nbsp;</label></td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>COLOR &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->color2}} </td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>ASPECTO</b></p>
              </td>
              <td>{{$resultado_examen->aspecto2}} </td>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>ALBUMINA &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->albumina2}} </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> DENSIDAD&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->densidad}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> PH&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->color1}} </td>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>GLUCOSA &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->glucosa2}} %</td>
            </tr>
            <tr>
              <td colspan="4">
                <p align="center"> &nbsp; &nbsp;<b> SEDIMENTOS&nbsp;</b></p>
              </td>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>UROBILINA &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->urobilina}} %</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> HEMATIES&nbsp;</b></p>
              </td>
              <td></td>
              <td>{{$resultado_examen->hematies3}} </td>
              <td></td>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>PIGMENTOS BILIARES &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->pigmentos_biliares}} </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> LEUCOCITOS&nbsp;</b></p>
              </td>
              <td></td>
              <td>{{$resultado_examen->lecucitos2}} </td>
              <td></td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> CUERPOS CETONICOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->cuerpos_cetonicos}} </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> CELULAS EPITELIALES&nbsp;</b></p>
              </td>
              <td></td>
              <td>{{$resultado_examen->celulas_epiteliales}} </td>
              <td></td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> AC ASCORBICO&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->ac_ascorbico}} </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> CRISTALES&nbsp;</b></p>
              </td>
              <td></td>
              <td>{{$resultado_examen->cristales}} </td>
              <td></td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> NITRITOS&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->nitritos}} </td>
            </tr>

            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> CILINDROS&nbsp;</b></p>
              </td>
              <td></td>
              <td>{{$resultado_examen->cilindros}} </td>
              <td></td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> THEVENON&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->thevenon}} </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> GERMENES&nbsp;</b></p>
              </td>
              <td></td>
              <td>{{$resultado_examen->germenes}} </td>
              <td></td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> OBSERVACIONES&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->observaciones2}} </td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> PIOCITOS&nbsp;</b></p>
              </td>
              <td></td>
              <td>{{$resultado_examen->piocitos}} </td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>
  @endif
  @if($tipo_examen==3 || $tipo_examen==5)
  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td width="23%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td width="23%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td width="23%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>AGLUTINACIONES FEBRILES &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->aglutinaciones_febriles}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>TIFICO "H"</b></p>
              </td>
              <td>{{$resultado_examen->tifico_h}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> TIFICO "O"&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->tifico_o}}</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> PARATIFICO "A" &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->paratifico_a}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> PARATIFICO "B" &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->paratifico_b}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> BRUCELLAS PLACA &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->brucellas_placa}}</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> VDRL o RPR &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->vdrl_rpr}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>PROTEINA "C" REACTIVA &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->proteina_c_reactiva}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>LATEX REUMATOIDÉO &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->latex_reumatoideo}}</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>ANTIESTREPTOLISINA&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->antiestreptolisina}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>ROSA DE BANGALS &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->rosa_bengals}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>PRUEBA EN TUBO &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->prueba_tulio}}</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>MERCAPTOETANOL&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->mercaptoetanol}}</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>
  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%" valign="top">
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
          <tbody>
            <tr>
              <td width="23%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td width="23%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
              <td width="23%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold"><label>EXAMEN
                  SOLICITADO &nbsp;</label></td>
              <td width="10%" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">
                <label>RESULTADO</label></td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp; <b>HBcTOTAL &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->hbctotal}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b>HAVgM</b></p>
              </td>
              <td>{{$resultado_examen->havgm}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> HB c IgM &nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->hbcigm}}</td>
            </tr>
            <tr>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> AgHBs&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->aghbs}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> HCV&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->hcv}}</td>
              <td>
                <p align="left"> &nbsp; &nbsp;<b> HIV&nbsp;</b></p>
              </td>
              <td>{{$resultado_examen->hiv}}</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>
  @endif
  <table style="margin-top: 10px">
    <tr>
      <td class="td_ancho_espacios"></td>
      <td class="td_ancho_espacios"></td>
    </tr>
  </table>

  <footer>
    * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE RUA
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