<?php
    use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>RECETA VALE</title>
  <style>
  .td_left {
    text-align: left !important;
  }

  /* .td_ancho_n {
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
  } */

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
    font-size: 9px;
    font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
  }

  /* .ancho_nro {
    width: 5%;
  }

  .ancho_producto {
    width: 45%;
  }

  .titulos {
    padding: 0px 10px;
  } */

  h2 {
    font-size: 15px !important
  }

  /* .titulo_principal {
    width: 60%;
  } */

  /* .logo {
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
  } */

  body {
    /* height: 100%; */
  }
  
  /* .container {
    display: table;
    width: 100%;
  }
  
  div.container div {
    flex: 1;
  } */

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

  table {
    margin:0px;
    padding:0px;
    padding-bottom:0px;
    width: 100%;
  }

  table,
  table td {
    /* border: 1px red solid; */
    vertical-align: top;
  }

  div.table-top {
    vertical-align: top;
  }

  </style>
</head>

<body>
<!--body style="font-size: 12px; font-family: arial;border:1px solid #A4A4A4;padding:10px;background-color:white !important"-->
  <div class="table-top">
    <!-- <br/><br/><br/> -->
  <table>
    <tbody>
      <tr>
        <td style="width:13cm; height:17cm">
          <table style="height: 50px;">
            <tbody>
              <tr>
                <td>
                  <div style="font-size: 8px; text-align: left;">
                    <table width="250px">
                      <tr>
                        <td width="60px">
                          <p align="left">
                            <img src="images/logo_sanidad.png" width="60px;" />
                          </p>
                        </td>
                        <td width="170px" style="vertical-align: middle;">
                          <p align="left">Firmado electrónicamente por: 
                          <br/>{{ $receta->paterno_medico }} {{ $receta->materno_medico }} {{ ucwords(strtolower($receta->nombre_medico)) }}
                          <br/>Motivo: Soy el autor del documento
                          <br/>Fecha: {{ date("d/m/Y H:i:s ", strtotime($receta->fecha_firma)) }}
                          </p>
                        </td>
                      </tr>
                    </table>
                  </div>  
                </td>
                <td rowspan="2" style="margin-top:0px;padding-top:0px;text-align:right;width:30%; vertical-align: bottom;">
                  <img  style="width:100px;" src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
                </td>
              </tr>
              <tr>
                <td><?php echo $url_descarga?></td>
              </tr>
            </tbody>
          </table>
          
          <table>
            <tbody>
              <tr>
                <td class="td_left" width="15%">
                  
                </td>
                <td class="td_left" width="20%">
                  
                </td>
                <td style="text-align:center" width="40%">
                    <b>N° :</b> <?php echo $receta->nro_receta ?>
                  
                </td>
                <td class="titulos" width="30%" style="padding-right:0px">
                    <strong>F.EXPEDICIÓN : </strong>
                    <?php
                    if ($receta->fecha_registro <> "") {
                      $fecha_admision = date("d-m-Y", strtotime($receta->fecha_registro));
                      echo $fecha_admision;
                    } else {
                      echo "---";
                    }
                    ?>
                  
                </td>
              </tr>
              <tr>
                <td class="td_left" width="15%">
                  <img src="images/logo_sanidad.png" width="54px"/>
                </td>
                <td class="td_left" width="20%">
                  <table style="padding-bottom:10px">
                    <tr  valign="center"><td><br/><b><?php echo $establecimiento->nombre;?> </b></td></tr>
                    <tr  valign="center"><td><?php echo $establecimiento->direccion_establecimiento;?></td></tr>
                  </table>
                </td>
                <td style="text-align:center" width="40%">
                  <h2>
                    RECETA - VALE @if(!empty($es_proc_prog)) - PROC. PROG. @endif
                  </h2>
                </td>
                <td width="30%"><br/><br/>
                  <b>CODIGO UPSS: </b>110500
                </td>
              </tr>
              
            </tbody>
          </table>

          <table>
            <tr>
              <td class="td_left"  width="15%"><strong>IPRESS : </strong> <?php echo $receta->codigoipress ?></td>
              <td class="td_left" width="30%" class="text-align:left"><strong>TIPO DE ATENCIÓN : </strong>@if($receta->tipo_receta=='QUIRURGICO')
                  CENTRO QUIRURGICO
                  @else
                  @if($receta->tipo_receta=='NUTRICIONAL')
                  SOPORTE NUTRICIONAL
                  @else
                  <?php echo $receta->tipo_receta ?>
                  @endif
                  @endif
              </td>
              <td class="td_left" width="55%"><strong>FARMACIA : </strong> <?php echo $receta->farmacia ?></td>
            </tr>
            <tr>
              <td class="td_left" width="15%"><strong><?php echo $receta->tipodoc_beneficiario ?> : </strong>
              <?php echo $receta->dni_beneficiario ?> </td>
              <td class="td_left" width="30%">
                <strong>NRO H.C. : </strong>
              <?php if($receta->nro_historia!=""): 
                            echo $receta->nro_historia;
                        else:
                            echo $receta->nro_historia2;
                        endif; 
                  ?>
              </td>
              <td class="td_left" width="55%"><strong>APELLIDOS Y NOMBRES : </strong><?php echo $receta->paterno_beneficiario . " " . $receta->materno_beneficiario . ", " . $receta->nombre_beneficiario ?></td>
            </tr>
            @if($receta->tipo_receta=='EMERGENCIA' || $receta->tipo_receta=='HOSPITALIZACION' || $receta->id_establecimiento==76)
                <tr>
                  <!--
                  <td class="text-align:left"><strong>TALLA : </strong>
                    <?php echo $receta->talla;?>
                  </td>
                  <td><strong>PESO : </strong>
                    <?php echo $receta->peso;?>
                  </td>
                  -->
                  <td class="td_left" width="30%"><strong>CAMA : </strong>
                      <?php
                        echo $receta->cama;
                      ?>
                  </td>
                  <td class="td_left" ><strong>EDAD : </strong>
                    <?php 
                          if($receta->fecha_nacimiento_beneficiario!=''):
                            $edad = Carbon::createFromFormat('d/m/Y', $receta->fecha_nacimiento_beneficiario)->format('Y/m/d');
                            echo $edad = Carbon::parse($edad)->age;
                          endif;
                    ?>
                  </td>
                  <td class="td_left" ><strong>PARENTESCO : </strong>
                    <?php echo $receta->tipo_beneficiario;?>
                  </td>
                </tr>
              @endif
            <tr>
              <td class="td_left" ><strong>FECHA EXPIRACIÓN : </strong>
                  <?php
                    if($receta->tipo_receta=='EMERGENCIA'):
                      $fecha_inicio = strtotime('+1 day', strtotime($receta->fecha_registro));
                      $fecha_expiracion = date('j-m-Y', $fecha_inicio);
                    else:
                      if($receta->tipo_receta=='HOSPITALIZACION'):
                        $fecha_inicio = strtotime('+3 day', strtotime($receta->fecha_registro));
                        $fecha_expiracion = date('j-m-Y', $fecha_inicio);
                      else:
                        $fecha_inicio = strtotime('+30 day', strtotime($receta->fecha_registro));
                        $fecha_expiracion = date('j-m-Y', $fecha_inicio);
                      endif;
                    endif;
                    echo $fecha_expiracion;
                  ?>
              </td>
            </tr>
            @if(!is_null($titular) && $receta->dni_titular!=$receta->dni_beneficiario)
            <tr>
              <td class="td_left" colspan="3">
                <strong>DATOS DEL TITULAR</strong>
              </td>
            </tr>
            <tr>
              <td class="td_left" ><strong><?php echo $titular->tipo_doc_ident ?> : </strong>
                <?php echo $titular->nro_doc_ident ?></td>
              <td class="td_left" ><strong>GRADO : </strong>
                  <?php echo $titular->grado . "&nbsp;" ?>
              </td>
              <td class="td_left" ><strong>APELLIDOS Y NOMBRES : </strong>
                <?php echo $titular->paterno." ".$titular->materno.", ".$titular->nombre ?>
              </td>
            </tr>
            @endif
            <tr>
              <td class="td_left" colspan="2"><strong>MEDICO : </strong>
              <?php echo $receta->nombre_medico . " " . $receta->paterno_medico . " " . $receta->materno_medico ?></td>
              <td class="td_left" ><strong>ESPECIALIDAD : </strong>
              <?php echo $receta->especializacion ?></td>
            </tr>
            <tr>
              <td class="td_left"  colspan="2"><strong>COLEGIATURA : </strong>
              <?php echo $receta->colegiatura_medico ?></td>
              <td class="td_left" ><strong>UPSS - SERVICIO : </strong>
                  @if($receta->tipo_receta=='EMERGENCIA')
                    @if($establecimiento->id==76)
                        <?php echo $receta->codigo_upss.'-'.$receta->servicio.'-'.$receta->consultorio; ?>
                    @else
                      @if($receta->codigo_upss!='')
                        <?php echo $receta->codigo_upss.'-'.$receta->consultorio; ?>
                      @else
                        <?php echo $receta->consultorio ?> 
                      @endif
                    @endif
                  @else
                    @if($receta->codigo_upss!='')
                        <?php echo $receta->codigo_upss.'-'.$receta->consultorio; ?>
                    @else
                        <?php echo $receta->consultorio ?> 
                    @endif
                  @endif
              </td>
            </tr>
          </table>
          <table border="0px" style="background-color:white !important">
            <tr>
              <td class="td_left" ><strong>DIAGNOSTICO(S) (CIE 10)</strong></td>
            </tr>
            <tr valign="left">
              <td  class="td_left" width="100%" style="padding-right:0px">
                <div class="rectangulo" style="">
                  <?php
                  $cad = "";
                  foreach ($diagnosticos as $row) :
                    $cad .= $row->codigo . " - " . $row->nombre . "<br>";
                  endforeach;
                  echo strtoupper($cad);
                  ?>
                </div>
              </td>
            </tr>
          </table>
        
          <table width="90%">
              <tbody>
                <tr>
                  <td  class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="2%">N </td>
                  <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="90%">
                    PRODUCTO</td>
                  <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="5%">DÍAS
                  </td>
                  <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="5%">CANT.
                  </td>
                </tr>
                <?php $n = 0; $sw=0; ?>
                @foreach($productos as $p)
                  <?php $n++;?>
                  @if($sw==0)
                  <tr>
                    <td><?php echo $n?>.- </td>
                    <td>{{$p->codigo}} - {{$p->nombre}}
                      @if($p->presentacion_comercial!=1)
                      ( {{$p->cantidad_prescrita}} X {{$p->presentacion_comercial}} )
                      @endif
                      @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                      ({{$p->cantidad_prescrita}} L )
                      @endif
                      <br><span><b>Via Adm.   : </b></span>{{$p->via}}
                      <br><span><b>Diagnos.    : </b></span>{{$p->cod_diagnostico}}
                      <br><span><b>Posología. : </b></span>{{$p->posologia}}
                    </td>
                    <td>{{$p->tiempo_periodo}}</td>
                    <td>@if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                        <?php $cantidad_pre = ($p->cantidad_prescrita)/1000; echo  $cantidad_pre; ?>
                      @else
                        <?php $cantidad_pre = ($p->cantidad_prescrita); echo  $cantidad_pre; ?>
                      @endif</td>
                  </tr>
                   @if($n==11 || $n==31 || $n==50)
                      <?php $sw=1; ?>
                   @endif
                  @else
                      <tr>
                        <td><?php echo $n?>.- </td>
                        <td>{{$p->codigo}} - {{$p->nombre}}
                          @if($p->presentacion_comercial!=1)
                          ( {{$p->cantidad_prescrita}} X {{$p->presentacion_comercial}} )
                          @endif
                          @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                          ({{$p->cantidad_prescrita}} L )
                          @endif
                          <br><span><b>Via Adm.   : </b></span>{{$p->via}}
                          <br><span><b>Diagnos.    : </b></span>{{$p->cod_diagnostico}}
                          <br><span><b>Posología. : </b></span>{{$p->posologia}}
                        </td>
                        <td>{{$p->tiempo_periodo}}</td>
                        <td>@if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                            <?php $cantidad_pre = ($p->cantidad_prescrita)/1000; echo  $cantidad_pre; ?>
                          @else
                            <?php $cantidad_pre = ($p->cantidad_prescrita); echo  $cantidad_pre; ?>
                          @endif</td>
                      </tr>
                      </tbody>
                    </table>
                    <div style="page-break-after:always;"></div>
                    <table>
                      <tbody>
                        <tr>
                          <td  class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="2%">N </td>
                          <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="90%">
                            PRODUCTO</td>
                          <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="5%">DÍAS
                          </td>
                          <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="5%">CANT.
                          </td>
                        </tr>
                      <?php $sw=0; ?>
                  @endif
                @endforeach
                
              </tbody>
            </table>
          <br/><br/><br/>
        </td>
      </tr>
    </tbody>
  </table>
  </div> {{-- .table-top --}}
  {{-- .container --}}

  {{-- <script type="text/php">
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
        </script> --}}
</body>

</html>