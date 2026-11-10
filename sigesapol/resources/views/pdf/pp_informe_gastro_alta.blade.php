<?php
    use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>INFORME ALTA - GASTROENTEROLOGIA</title>
  <style>
  .td_left {
    text-align: left !important;
  }

.linea-vertical {
  height: 80%;
  width:2px;
  background-color:red;
  }

  td {
    padding-left: 2px !important;
    text-align:left;
  }
  .td_center {
    font-size: 7px;
    text-align:center;
    vertical-align: top;
  }

  table {
    font-size: 10px;
    font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
  }


  h2 {
    font-size: 14px !important
  }

  body {
  }
  
  footer {
    margin-bottom: 0px;
    padding: 0px;
    position: fixed;
    height: 40px;
    line-height: 40px;
    text-align: left;
    border: 0px;
    margin-left: 5px;
    font-size: 7px !important;
    font-family: Arial, Helvetica, sans-serif
  }

  table {
    margin:0px;
    padding:0px;
    padding-bottom:0px;
    width: 100%;
  }

  table,
  table td {
    vertical-align: top;
  }

  div.table-top {
    vertical-align: top;
  }

  </style>
</head>

<body>
  
  <table style="margin:0px;padding:0px;padding-bottom:0px;">
    <tbody>
      <tr>
        <td colspan="2" width="70%" style="vertical-align:bottom;padding-bottom:30px">
          @if(!empty($firma_electronica))
          <table style="width:auto;margin:0 0 4px 15px;padding:0;border-collapse:collapse">
            <tr>
              <td style="width:50px;padding:0 6px 0 0 !important;vertical-align:middle"><img src="images/logo_sanidad_firma.png" width="47" height="70" /></td>
              <td style="padding:0 !important;vertical-align:middle;font-size:8px;line-height:1.3">
                Firmado electrónicamente por:
                <br/>{{ $firma_electronica['medico'] }}
                <br/>Motivo: Soy el autor del documento
                <br/>Fecha: {{ $firma_electronica['fecha'] }}
              </td>
            </tr>
          </table>
          @endif
          <div><?php echo $url_descarga?></div>
        </td>
        <td colspan="2" width="30%" style="margin-top:0px;padding-top:0px">
          <img src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
        </td>
      </tr>
    </tbody>
  </table>

  <div class="table-top">

    <table border="0">
      <tbody>
        <tr>
          <td style="width:13cm; height:17cm">
            <div id="columna1">
              <table>
                <tbody>
                  <tr>
                    <td class="td_left" width="15%">
                      <img src="images/logo_sanidad.png" width="54px"/>
                    </td>
                    <td style="text-align:center" width="70%">
                      <h2>
                        HOSPITAL NACIONAL PNP. LUIS N. SAENZ
                      </h2>
                      <p>Av. Brasil cdra 26 S/N – Jesús María – Lima (13591)</p>
                    </td>
                    <td class="td_rigth" width="15%">
                      <img src="images/logo_sanidad.png" width="54px"/>
                    </td>
                  </tr>
                </tbody>
              </table>
              <hr>
              <table>
                <tbody>
                  <tr>
                    <td style="text-align:center" width="100%">
                      <h3>
                        SERVICIO DE GASTROENTEROLOGIA (222800)
                      </h3>
                      <h4>INFORME DE ENDOSCOPIA DIGESTIVA ALTA</h4>
                    </td>
                  </tr>
                </tbody>
              </table>
              <hr>
              <table>
                <tr>
                  <td width="100%" colspan="8"><strong>NOMBRE : </strong> {{$prestacion->asegurado_paterno . " " . $prestacion->asegurado_materno . ", " . $prestacion->asegurado_nombre }}</td>
                </tr>
                <tr>
                  <td width="8%" class="text-align:left"><strong>DISTRITO : </strong></td>
                  <td width="19%"><?php //echo  ?></td>
                  <td width="8%" class="text-align:left"><strong>SEXO : </strong></td>
                  <td width="19%">{{$prestacion->sexo }}</td>
                  <td width="8%" class="text-align:left"><strong>EDAD : </strong></td>
                  <td width="19%">{{$prestacion->edadpaciente }}</td>
                  <td width="8%" class="text-align:left"><strong>FECHA : </strong></td>
                  <td width="19%">{{ $prestacion->fecha_nac }}</td>
                </tr>
                <tr>
                  <td width="8%" class="text-align:left"><strong>HC : </strong></td>
                  <td width="19%">{{$prestacion->nro_historia }}</td>
                  <td width="8%" class="text-align:left"><strong>CIP : </strong></td>
                  <td width="19%">{{$prestacion->cip }}</td>
                  <td width="8%" class="text-align:left"><strong>DNI : </strong></td>
                  <td width="19%">{{$prestacion->nro_doc_ident }}</td>
                  <td width="8%" class="text-align:left"><strong>GRADO : </strong></td>
                  <td width="19%">{{$prestacion->grado }}</td>
                </tr>
                <tr>
                  <td width="8%" class="text-align:left"><strong>MEDICO : </strong></td>
                  <td width="19%" colspan="3">{{$prestacion->medico_paterno . " " . $prestacion->medico_materno . ", " . $prestacion->medico_nombre }}</td>
                  <td width="8%" class="text-align:left"><strong>DNI : </strong></td>
                  <td width="19%">{{$prestacion->dni }}</td>
                  <td width="8%" class="text-align:left"><strong>CMP : </strong></td>
                  <td width="19%">{{$prestacion->colegiatura_medico }}</td>
                </tr>
                <tr>
                  <td width="8%" class="text-align:left"><strong>SERVICIO : </strong></td>
                  <td width="19%" colspan="5">{{$prestacion->servicio }}</td>
                  <td width="8%" class="text-align:left"><strong>INFORME : </strong></td>
                  <td width="19%"><?php //echo  ?></td>
                </tr>
              </table>
              <hr>
              <table>
                <tr>
                  <td width="100%" colspan="8"><strong>PROCEDIMIENTO EFECTUADO </strong> </td>
                </tr>
                <tr><td width="100%" colspan="8"><table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td valign="top">
                              <table style="background-color:white !important;border-collapse:separate;border-spacing:1px"  width="100%">
                                <tbody>
                                  <?php $n = 0; ?>
                                  @foreach($procedimiento as $p)
                                  <?php $n++;?>
                                  <tr>
                                    <td width="5%" style="border:0px solid #A4A4A4"><?php echo $n?>.-</td>
                                    <td width="15%" style="border:0px solid #A4A4A4">{{$p->codigo}}</td>
                                    <td width="80%" style="border:0px solid #A4A4A4">{{$p->nombre}}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                  <td width="100%" colspan="8"><strong>INDICACIONES : </strong> {{$datos_gastro->alta_indicaciones }}</td>
                </tr>
                <tr>
                  <td width="100%" colspan="8"><strong>MEDICACION : </strong> {{$datos_gastro->alta_medicacion }}</td>
                </tr>
                <tr>
                  <td width="100%" colspan="8"><strong>DIAGNOSTICO PRECLINICO </strong> </td>

                </tr>
                <tr><td width="100%" colspan="8"><table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td valign="top">
                              <table style="background-color:white !important;border-collapse:separate;border-spacing:1px"  width="100%">
                                <tbody>
                                  <?php $n = 0; ?>
                                  @foreach($diagnostico as $p)
                                  <?php $n++;?>
                                  <tr>
                                    <td width="5%" style="border:0px solid #A4A4A4"><?php echo $n?>.-</td>
                                    <td width="15%" style="border:0px solid #A4A4A4">{{$p->codigo}}</td>
                                    <td width="60%" style="border:0px solid #A4A4A4">{{$p->nombre}}</td>
                                    <td width="20%" style="border:0px solid #A4A4A4"><?php switch ($p->id_tipo_diagnostico) {
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
                    </td>
                </tr>
              </table>
              <br><br>
              <table>
                <tr>
                  <td width="100%" colspan="9"><strong>HALLAZGOS</strong></td>
                </tr>
              </table>
              <br><br>
              <table>
                <tr>
                  <td width="100%" colspan="9"><strong>ESOFAGO</strong></td>
                </tr>
                <tr>
                  <td width="100%" colspan="9">{{$datos_gastro->alta_esofago}}</td>
                </tr>
              </table>
              <br><br>
              <table>
                <tr>
                  <td width="100%" colspan="9"><strong>ESTOMAGO</strong></td>
                </tr>
                <tr>
                  <td width="100%" colspan="9"><strong>Cardias:</strong> {{$datos_gastro->alta_cardias}}</td>
                </tr>
                <tr>
                  <td width="100%" colspan="9"><strong>Fondo:</strong> {{$datos_gastro->alta_fondo}}</td>
                </tr>
                <tr>
                  <td width="100%" colspan="9"><strong>Cuerpo:</strong> {{$datos_gastro->alta_cuerpo}}</td>
                </tr>
                <tr>
                  <td width="100%" colspan="9"><strong>Antro:</strong> {{$datos_gastro->alta_antro}}</td>
                </tr>
              </table>
              <br><br>
              <table>
                <tr>
                  <td width="100%" colspan="9"><strong>DUODENO</strong></td>
                </tr>
                <tr>
                  <td width="100%" colspan="9"><strong>Bulbo:</strong> {{$datos_gastro->alta_bulbo}}</td>
                </tr>
                <tr>
                  <td width="100%" colspan="9"><strong>Segunda porción:</strong> {{$datos_gastro->alta_seg_porcion}}</td>
                </tr>
              </table>
              <br><br>
              <table>
                <tr>
                  <td width="20%" class="text-align:left"><strong>BIOPSIA : </strong></td>
                  <td width="20%" colspan="5">{{$datos_gastro->alta_biopsia}}</td>
                  <td></td>
                  <td width="20%" class="text-align:left"><strong>COMPLICACIONES : </strong></td>
                  <td width="20%">{{$datos_gastro->alta_complicaciones}}</td>
                </tr>
                <tr>
                  <td width="20%" class="text-align:left"><strong>OBSERVACIONES : </strong></td>
                  <td width="80%" colspan="8">{{$datos_gastro->alta_observaciones}}</td>
                </tr>
              </table>
              <br><br>
              <table>
                <tr>
                  <td width="100%" colspan="9"><strong>DIAGNOSTICO ENDOSCOPICO : </strong></td>
                </tr>
                <tr>
                  <td width="10%"></td>
                  <td width="100%" colspan="8">{{$datos_gastro->alta_diag_endoscopico}}</td>
                </tr>
              </table>
             <footer>
                
              </footer>
            </div>
            
          </td>
          
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>