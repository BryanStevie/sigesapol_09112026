<?php
    use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>HOJA DE TRATAMIENTO TERAPEUTICO</title>
  <style>
  .td_left {
    text-align: left !important;
  }
  .td_right {
    text-align: right; !important;
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
    <br/><br/><br/>
  <table border="0">
    <tbody>
      <tr>
        <td style="width:13cm; height:17cm">
          <table style="height: 100px;">
            <tbody>
              <tr>
                <td style="vertical-align:bottom;padding-bottom:30px; width:100%">
                  <?php echo $url_descarga?>
                </td>
                <td style="margin-top:0px;padding-top:0px;text-align:right;width:30%">
                  <img  style="width:100px;" src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
                </td>
              </tr>
            </tbody>
          </table>
          <br/><br/><br/>
          <table width="95%">
              <tbody>
                <tr><td colspan="3"><h1 align="center"><?php echo $establecimiento->nombre;?></h1></td>
                </tr>
                <tr>
                  <td class="td_left" width="20%">
                    <img src="images/logo_sanidad.png" width="54px"/>
                  </td>
                  <td class="td_left" width="60%">
                    <h2  align="center">
                      TRATAMIENTO HOJA TERAPÉUTICA - HOSP
                    </h2>
                  </td>
                  <td class="td_right" width="20%"><br/>
                    <b>CAMA: </b>
                    <?php
                        echo $receta->cama;
                      ?><br>
                      <b>Nro H.C.: </b>
                    <?php if($receta->nro_historia!=""): 
                            echo $receta->nro_historia;
                        else:
                            echo $receta->nro_historia2;
                        endif; 
                  ?>
                  <br>
                      <b>F.Hosp.: </b>
                    <?php
                      if ($obj_fecha->fecha_ingreso <> "") {
                        $fecha_ingreso = date("d-m-Y", strtotime($obj_fecha->fecha_ingreso));
                        echo $fecha_ingreso;
                      } else {
                        echo "---";
                      }
                      ?>
                  </td>
                </tr>
                
              </tbody>
            </table>
            <br/>
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
              <td class="td_left" width="55%"><strong>APELLIDOS Y NOMBRES : </strong><?php echo $receta->paterno_beneficiario . " " . $receta->materno_beneficiario . ", " . $receta->nombre_beneficiario ?></td>
            </tr>
            <tr>
              <td class="td_left" width="15%"><strong><?php echo $receta->tipodoc_beneficiario ?> : </strong>
              <?php echo $receta->dni_beneficiario ?> </td>
              <td class="td_left" width="30%">
                <strong> SEXO: </strong><?php echo $receta->sexo_beneficiario ?>
              </td>
              <td class="td_left"  width="55%"><strong>PARENTESCO : </strong>
                <?php echo $receta->tipo_beneficiario;?>
              </td>
            </tr>
            <tr>
              <td class="td_left" ><strong>EDAD : </strong>
                  <?php 
                        if($receta->fecha_nacimiento_beneficiario!=''):
                          $edad = Carbon::createFromFormat('d/m/Y', $receta->fecha_nacimiento_beneficiario)->format('Y/m/d');
                          echo $edad = Carbon::parse($edad)->age;
                        endif;
                  ?>
              </td>
              @if($receta->tipo_receta=='EMERGENCIA' || $receta->tipo_receta=='HOSPITALIZACION' || $receta->id_establecimiento==76)
                <td class="td_left"><strong>TALLA : </strong>
                    <?php echo $receta->talla;?></td>
                    <td class="td_left"><strong>PESO : </strong>
                    <?php echo $receta->peso;?></td>
              @else
                <td class="td_left"></td>
                <td class="td_left"></td>
              @endif
            </tr>
            @if($receta->dni_titular!=$receta->dni_beneficiario)
            <tr>
              <td class="td_left" colspan="3">
                <strong>DATOS DEL TITULAR</strong>
              </td>
            </tr>
            <tr>
              <td class="td_left" ><strong><?php echo $receta->tipo_doc_ident ?> : </strong>
                <?php echo $receta->dni_titular ?></td>
              <td class="td_left" ><strong>GRADO : </strong>
                  <?php echo $receta->grado . "&nbsp;" ?>
              </td>
              <td class="td_left" ><strong>APELLIDOS Y NOMBRES : </strong>
                <?php echo $receta->paterno_titular." ".$receta->materno_titular.", ".$receta->nombre_titular ?>
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
                <td><strong>DIAGNOSTICO(S) (CIE 10)</strong></td>
                <td></td>
              </tr>
              <tr valign="left">
                <td  class="titulos" width="70%" style="padding-right:0px">
                  <div class="rectangulo" style="">
                    <?php
                    $cad = "";
                    foreach ($diagnosticos as $row) :
                      $cad .= $row->codigo." - ".$row->nombre." - "."<span> ".$row->tipo."</span><br>";
                    endforeach;
                    echo strtoupper($cad);
                    ?>
                  </div>
                </td>
                <td   class="titulos" width="30%" style="padding-right:0px"></td>
              </tr>
            </table>
          <table width="100%">
              <tbody>
                <tr><td colspan="10"><hr/></td></tr>
                
                <tr>
                  <td rowspan="2" style="font-style:italic;font-weight:bold" width="2%">N </td>
                  <td rowspan="2" style=" text-align: center; font-style:italic;font-weight:bold" width="50%">
                    NOMBRE DEL MEDICAMENTO</td>
                  <td rowspan="2" style=" text-align: center; font-style:italic;font-weight:bold" width="8%">Via Adm.
                  </td>
                  <td rowspan="2" style=" text-align: center; font-style:italic;font-weight:bold" width="20%">Posología
                  </td>
                  <td rowspan="2" style=" text-align: center; font-style:italic;font-weight:bold" width="4%">DÍAS
                  </td>
                  <td rowspan="2" style=" text-align: center; font-style:italic;font-weight:bold" width="4%">CANT.
                  </td>
                  <td colspan="4" style=" text-align: center; font-style:italic;font-weight:bold" width="12%">Fecha y Hora <br><hr/></td>
                </tr>
                <tr>
                  <td  style=" text-align: center; font-style:italic;font-weight:bold" width="3%">M.
                  </td>
                  <td  style=" text-align: center; font-style:italic;font-weight:bold" width="3%">T.
                  </td>
                  <td  style=" text-align: center; font-style:italic;font-weight:bold" width="3%">N.
                  </td>
                  <td  style=" text-align: center; font-style:italic;font-weight:bold" width="3%">MD.
                  </td>
                </tr>
                <tr><td colspan="10"><hr/></td></tr>
                <?php $n = 0; $sw=0; ?>
                @foreach($productos as $p)
                  <?php $n++;?>
                  @if($sw==0)
                  <tr >
                    <td style="border-bottom-style: dotted;"><?php echo $n?>.- </td>
                    <td style="border-bottom-style: dotted;">{{$p->codigo}} - {{$p->nombre}}
                      @if($p->presentacion_comercial!=1)
                      ( {{$p->cantidad_prescrita}} X {{$p->presentacion_comercial}} )
                      @endif
                      @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                      ({{$p->cantidad_prescrita}} L )
                      @endif
                    </td>
                    <td style="border-bottom-style: dotted;">{{$p->via}}</td>
                    <td style="border-bottom-style: dotted;">{{$p->posologia}}</td>
                    <td style="border-bottom-style: dotted;">{{$p->tiempo_periodo}}</td>
                    <td style="border-bottom-style: dotted;">@if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                        <?php $cantidad_pre = ($p->cantidad_prescrita)/1000; echo  $cantidad_pre; ?>
                      @else
                        <?php $cantidad_pre = ($p->cantidad_prescrita); echo  $cantidad_pre; ?>
                      @endif</td>
                      <td style="border-bottom-style: dotted;"></td>
                        <td style="border-bottom-style: dotted;"></td>
                        <td style="border-bottom-style: dotted;"></td>
                        <td style="border-bottom-style: dotted;"></td>
                  </tr>
                  
                   @if($n==11 || $n==31 || $n==50)
                      <?php $sw=1; ?>
                   @endif
                  @else
                      <tr>
                        <td style="border-bottom-style: dotted;"><?php echo $n?>.- </td>
                        <td style="border-bottom-style: dotted;">{{$p->codigo}} - {{$p->nombre}}
                          @if($p->presentacion_comercial!=1)
                          ( {{$p->cantidad_prescrita}} X {{$p->presentacion_comercial}} )
                          @endif
                          @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                          ({{$p->cantidad_prescrita}} L )
                          @endif
                        </td>
                        <td style="border-bottom-style: dotted;">{{$p->via}}</td>
                        <td style="border-bottom-style: dotted;">{{$p->posologia}}</td>
                        <td style="border-bottom-style: dotted;">{{$p->tiempo_periodo}}</td>
                        <td style="border-bottom-style: dotted;">@if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                            <?php $cantidad_pre = ($p->cantidad_prescrita)/1000; echo  $cantidad_pre; ?>
                          @else
                            <?php $cantidad_pre = ($p->cantidad_prescrita); echo  $cantidad_pre; ?>
                          @endif</td>
                        <td style="border-bottom-style: dotted;"></td>
                        <td style="border-bottom-style: dotted;"></td>
                        <td style="border-bottom-style: dotted;"></td>
                        <td style="border-bottom-style: dotted;"></td>
                      </tr>
                      <tr><td colspan="10"><hr/></td></tr>
                      </tbody>
                    </table>
                    <div style="page-break-after:always;"></div>
                    <table width="100%">
                      <tbody>
                        <tr><td colspan="10"><hr/></td></tr>
                        <tr>
                          <td rowspan="2" style="font-style:italic;font-weight:bold" width="2%">N </td>
                          <td rowspan="2" style=" text-align: center; font-style:italic;font-weight:bold" width="50%">
                            NOMBRE DEL MEDICAMENTO</td>
                          <td rowspan="2" style=" text-align: center; font-style:italic;font-weight:bold" width="8%">Via Adm.
                          </td>
                          <td rowspan="2" style=" text-align: center; font-style:italic;font-weight:bold" width="20%">Posología
                          </td>
                          <td rowspan="2" style=" text-align: center; font-style:italic;font-weight:bold" width="4%">DÍAS
                          </td>
                          <td rowspan="2" style=" text-align: center; font-style:italic;font-weight:bold" width="4%">CANT.
                          </td>
                          <td colspan="4" style=" text-align: center; font-style:italic;font-weight:bold" width="12%">Fecha y Hora <br><hr/></td>
                        </tr>
                        <tr>
                          <td  style=" text-align: center; font-style:italic;font-weight:bold" width="3%">M.
                          </td>
                          <td  style=" text-align: center; font-style:italic;font-weight:bold" width="3%">T.
                          </td>
                          <td  style=" text-align: center; font-style:italic;font-weight:bold" width="3%">N.
                          </td>
                          <td  style=" text-align: center; font-style:italic;font-weight:bold" width="3%">MD.
                          </td>
                        </tr>
                        <tr><td colspan="10"><hr/></td></tr>
                        
                      <?php $sw=0; ?>
                  @endif
                @endforeach
                
              </tbody>
            </table>
          <br/><br/><br/>
          <table border="0px" style="background-color:white !important">
            <tbody>
            <tr><td><hr/></td></tr>
            <tr>
              <td><strong>DIETA</strong></td>
            </tr>
            <tr><td><hr/></td></tr>
            <tr valign="left">
              <td class="titulos" width="100%" style="padding-right:0px">
                {{$obj_hoja->dieta_terapeutica}}
              </td>
            </tr>
            </tbody>
          </table>
          <br/><br/><br/>
          <table border="0px" style="background-color:white !important">
            <tbody>
            <tr><td><hr/></td></tr>
            <tr>
              <td><strong>INDICACION TERAPEUTICA</strong></td>
            </tr>
            <tr><td><hr/></td></tr>
            <tr valign="left">
              <td class="titulos" width="100%" style="padding-right:0px">
                {{$obj_hoja->indicacion_terapeutica}}
              </td>
            </tr>
            </tbody>
          </table>
          <br/><br/><br/>
          <table border="0px" style="background-color:white !important">
            <tbody>
            <tr><td><hr/></td></tr>
            <tr>
              <td><strong>INDICACION COMPLEMENTARIA</strong></td>
            </tr>
            <tr><td><hr/></td></tr>
            <tr valign="left">
              <td class="titulos" width="100%" style="padding-right:0px">
                {{$obj_hoja->complementario_terapeutica}}
              </td>
            </tr>
            </tbody>
          </table>
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