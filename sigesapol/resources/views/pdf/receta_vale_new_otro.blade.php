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

.linea-vertical {
  height: 80%;
  width:2px;
  background-color:red;
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
  .td_left {
    font-size: 7px;
    text-align:left;
    vertical-align: top;
  }

  table {
    font-size: 8px;
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
    font-size: 14px !important
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
    /* border: 1px red solid; */
    vertical-align: top;
  }

  div.table-top {
    vertical-align: top;
  }

  @page{
      margin: 0.3cm 0.8cm 0.2cm 0.8cm;
  }
  </style>
</head>

<body>
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
                    
                  </td>
                  <td class="td_left" width="20%">
                    
                  </td>
                  <td style="text-align:center" width="40%">
                      <h4>N° : <?php echo $receta->nro_receta ?> </h4>
                    
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
                    <img src="images/logo_sanidad_negro.png" width="54px"/>
                  </td>
                  <td class="td_left" width="20%">
                    <table style="padding-bottom:10px">
                      <tr  valign="center"><td><br/><b><?php echo $establecimiento->nombre;?> </b></td></tr>
                      <tr  valign="center"><td><?php echo $establecimiento->direccion_establecimiento;?></td></tr>
                    </table>
                  </td>
                  <td>
                    <table>
                      <tr>
                        <td style="text-align: center"><h2> RECETA - VALE @if(!empty($es_proc_prog)) - PROC. PROG. @endif</h2></td>
                      </tr>
                      <tr>
                        <td style="text-align: center"><b>CUPS PNP</b>
                          @if(isset($cups_pnp->cups)){{$cups_pnp->cups}}
                          @else {{'---------------------------'}}@endif</td>
                      </tr>
                    </table>
                  </td>
                  <td width="30%"><br/><br/>
                    <table>
                      <tr>
                        <td><b>CODIGO UPSS: </b>110500</td>
                      </tr>
                      <tr>
                        <td><b>COD. SITEDS: </b>{{$receta->codigo_siteds}}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                
              </tbody>
            </table>

            <table>
              <tr>
                <td width="15%"><strong>IPRESS : </strong> <?php echo $receta->codigoipress ?></td>
                <td width="30%" class="text-align:left"><strong>TIPO DE ATENCIÓN : </strong>@if($receta->tipo_receta=='QUIRURGICO')
                    CENTRO QUIRURGICO
                    @else
                    @if($receta->tipo_receta=='NUTRICIONAL')
                    SOPORTE NUTRICIONAL
                    @else
                    <?php echo $receta->tipo_receta ?>
                    @endif
                    @endif
                </td>
                <td width="55%"><strong>FARMACIA : </strong> <?php echo $receta->farmacia ?></td>
              </tr>
              <tr>
                <td class="text-align:left"><strong><?php echo $receta->tipodoc_beneficiario ?> : </strong>
                <?php echo $receta->dni_beneficiario ?> </td>
                <td class="text-align:left">
                  <strong>NRO H.C. : </strong>
                <?php if($receta->nro_historia!=""): 
                              echo $receta->nro_historia;
                          else:
                              echo $receta->nro_historia2;
                          endif; 
                    ?>
                </td>
                <td><strong>APELLIDOS Y NOMBRES : </strong><?php echo $receta->paterno_beneficiario . " " . $receta->materno_beneficiario . ", " . $receta->nombre_beneficiario ?></td>
              </tr>
              <tr>
                <td class="text-align:left"><strong>EDAD : </strong>
                    <?php 
                          if($receta->fecha_nacimiento_beneficiario!=''):
                            $edad = Carbon::createFromFormat('d/m/Y', $receta->fecha_nacimiento_beneficiario)->format('Y/m/d');
                            echo $edad = Carbon::parse($edad)->age;
                          endif;
                    ?>
                  
                </td>
                <td><strong>PARENTESCO : </strong>
                  <?php echo $receta->tipo_beneficiario;?>
                </td>
                <td><strong>FECHA EXPIRACIÓN : </strong>
                    <?php
                      if($receta->fecha_expiracion==''):
                        if($receta->tipo_receta=='EMERGENCIA'):
                          $fecha_inicio = strtotime('+1 day', strtotime($receta->fecha_registro));
                          $fecha_expiracion = date('j-m-Y', $fecha_inicio);
                        else:
                          if($receta->tipo_receta=='HOSPITALIZACION'):
                            $fecha_inicio = strtotime('+3 day', strtotime($receta->fecha_registro));
                            $fecha_expiracion = date('j-m-Y', $fecha_inicio);
                          else:
                            $fecha_inicio = strtotime('+29 day', strtotime($receta->fecha_registro));
                            $fecha_expiracion = date('j-m-Y', $fecha_inicio);
                          endif;
                        endif;
                      else:
                        $originalDate4 = $receta->fecha_expiracion;
                        $fecha_expiracion = date("d-m-Y", strtotime($originalDate4));
                      endif;
                      echo $fecha_expiracion;
                    ?>
                </td>
              </tr>
              @if($titular!=null && $receta->dni_titular!=$receta->dni_beneficiario)
              <tr>
                <td colspan="3">
                  <strong>DATOS DEL TITULAR</strong>
                </td>
              </tr>
              <tr>
                <td class="text-align:left"><strong><?php echo $titular->tipo_doc_ident ?> : </strong>
                  <?php echo $receta->dni_titular ?></td>
                <td><strong>GRADO : </strong>
                    <?php echo $receta->grado . "&nbsp;" ?>
                </td>
                <td><strong>APELLIDOS Y NOMBRES : </strong>
                <?php echo $titular->paterno." ".$titular->materno.", ".$titular->nombre ?>
                </td>
              </tr>
              @endif
              <tr>
                <td colspan="2"><strong>MEDICO : </strong>
                <?php echo $receta->nombre_medico . " " . $receta->paterno_medico . " " . $receta->materno_medico ?></td>
                <td><strong>ESPECIALIDAD : </strong>
                <?php echo $receta->especializacion ?></td>
              </tr>
              <tr>
                <td colspan="2"><strong>COLEGIATURA : </strong>
                <?php echo $receta->colegiatura_medico ?></td>
                <td><strong>UPSS - SERVICIO : </strong>
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
              </tr>
              <tr valign="left">
                <td  class="titulos" width="100%" style="padding-right:0px">
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
          
            <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="250" valign="top">
                  <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
                    <tbody>
                      <?php if($receta->id_prestacion!=0): ?>
                      <tr>
                        <td colspan="5" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                          <b>PRESCRIPCIÓN</b>
                        </td>
                        <td colspan="3" class="titulos" style="border:1px solid #A4A4A4;font-size:8px!important;font-style:italic;text-align:left;font-weight:bold">FECHA DE DISPENSACIÓN : <?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?></td>
                      </tr>
                      <?php else: ?>
                        <tr>
                        <td colspan="5" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                          <b>PRESCRIPCIÓN</b>
                        </td>
                        <td colspan="3" class="titulos" style="border:1px solid #A4A4A4;font-size:8px!important;font-style:italic;text-align:left;font-weight:bold">FECHA DE DISPENSACIÓN : <?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?></td>
                      </tr>
                      <?php endif; ?>
                      <tr>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">N°
                        </td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="4%">
                          PRODUCTO</td>
                        <!--td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">UN.
                          MED.</td-->
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">CANT.
                        </td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">DÍAS
                        </td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">
                          VIA ADM.</td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="2%">
                          POSOLOGÍA</td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">CANT. DADO</td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="1.5%">OBSERV.</td>
                      </tr>
                      <?php $n = 0; ?>
                      @foreach($productos as $p)
                      <?php $n++;?>
                      <tr>
                        <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
                        <td style="text-align:left; border:1px solid #A4A4A4">{{$p->codigo}} - {{$p->nombre}}
                          @if($p->id_producto==11018 || $p->id_producto==13748 || $p->id_producto==11019 || $p->id_producto==11020 || $p->id_producto==11021 || $p->id_producto==11023 || $p->id_producto==11024 || $p->id_producto==11025 || $p->id_producto==11026)
                          ( {{$p->cantidad_prescrita}} X {{$p->presentacion_comercial}} )
                          @endif
                          @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                          ({{$p->cantidad_prescrita}} L )
                          @endif
                        </td>
                        <!--td style="border:1px solid #A4A4A4">{{$p->abreviatura}}</td-->
                        <td style="border:1px solid #A4A4A4">@if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                            <?php $cantidad_pre = ($p->cantidad_prescrita)/1000; echo  $cantidad_pre; ?>
                          @else
                            <?php $cantidad_pre = ($p->cantidad_prescrita); echo  $cantidad_pre; ?>
                          @endif</td>
                        <td style="border:1px solid #A4A4A4">{{$p->tiempo_periodo}}</td>
                        <td style="border:1px solid #A4A4A4">{{$p->via}}</td>
                        @if (intval($receta->id) === 9245184)
                          <td style="border:1px solid #A4A4A4; font-size: 7px;">{{$p->posologia}}</td>
                        @else
                        <td style="border:1px solid #A4A4A4">{{$p->posologia}}</td>
                        @endif
                        <td style="border:1px solid #A4A4A4">
                          @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                            <?php $cantidad_dis = ($p->cantidad_dispensada)/1000; echo  $cantidad_dis; ?>
                          @else
                            <?php $cantidad_dis = ($p->cantidad_dispensada); echo  $cantidad_dis; ?>
                          @endif
                        </td>
                        <td style="border:1px solid #A4A4A4">
                          <?php   
                              if($cantidad_pre>$cantidad_dis):
                                switch ($p->id_observacion) {
                                  case 0: if(($cantidad_dis==0) || ($cantidad_dis!=0)): echo "Sin Stock"; else: echo " "; endif;break;
                                  case 1: if(($cantidad_dis==0) || ($cantidad_dis!=0)) echo "Sin Stock"; break;
                                  case 2: if(($cantidad_dis==0) || ($cantidad_dis!=0)) echo "Paciente tiene"; break;
                                  case 3: if($cantidad_dis==0) echo "No requiere"; break;
                                  case 4: if($cantidad_dis==0) echo "No corresponde"; break;
                                  case 5: if($cantidad_dis==0) echo "Mal prescrito"; break;
                                  case 6: if($cantidad_dis==0) echo "Alerta DIGEMID"; break;
                                  case 7: if($cantidad_dis==0) echo "Suspendido"; break;
                                  case 8: if($cantidad_dis==0) echo "CIE 10 (DIAGNOSTICO)"; break;
                                  case 9: if($cantidad_dis==0) echo "N DE CAMA "; break;
                                  case 10: if($cantidad_dis==0) echo "NOMBRE DEL SERVICIO"; break;
                                  case 11: if($cantidad_dis==0) echo "CONCENTRACIÓN DEL PRODUCTO FARMACEÚTICO "; break;
                                  case 12: if($cantidad_dis==0) echo "FORMA FARMACEÚTICA DEL PRODUCTO FARMACEÚTICO"; break;
                                  case 13: if($cantidad_dis==0) echo "VIA DE ADMINISTRACIÓN"; break;
                                  case 14: if($cantidad_dis==0) echo "CANTIDAD PRESCRITA"; break;
                                  case 15: if($cantidad_dis==0) echo "FIRMA DEL MÉDICO PRESCRIPTOR"; break;
                                  case 16: if($cantidad_dis==0) echo "ERROR DE DOSIFICACIÓN"; break;
                                  case 17: if($cantidad_dis==0) echo "SEGÚN INDICACIÓN MÉDICA"; break;
                                  case 18: if($cantidad_dis==0) echo "ATENDIDO"; break;
                                  case 19: if($cantidad_dis==0)  echo "Por Recoger"; break;
                                  case 20: if($cantidad_dis==0) echo "No Petitorio"; break;
                                } 
                              else:
                                echo " ";
                              endif;
                          ?>
                        </td>
                      </tr>
                      @endforeach
                      
                    </tbody>
                  </table>
                </td>
              </tr>
            </table>
            <br/><br/><br/>
            <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
              <tr>
                @if($receta->fecha_firma!='')
                  @if($receta->flag == 2)
                    <td width="35%" valign="top">
                      <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                          <tbody>
                              <tr>
                                  <td ><p align="right"><img src="images/logo_sanidad_negro.png" width="40px;" /></p></td>
                                  <td ><p align="left">Firmado electrónicamente por: <br/><?php echo $receta->nombre_medico." ".$receta->paterno_medico." ".$receta->materno_medico ?><br/><?php echo$receta->fecha_firma ?></p></td>
                              </tr>
                          </tbody>
                      </table>
                    </td>
                  @else
                    @if($receta->flag == 1)
                    <td width="35%" valign="top">
                      <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                          <tbody>
                              <tr>
                                  <td ><p align="right"><img src="images/logo_sanidad_negro.png" width="40px;" /></p></td>
                                  <td ><p align="left">Firmado digitalmente por: <br/><?php echo $receta->nombre_medico." ".$receta->paterno_medico." ".$receta->materno_medico ?><br/><?php echo$receta->fecha_firma ?></p></td>
                              </tr>
                          </tbody>
                      </table>
                    </td>
                    @endif
                  @endif
                @endif
                <td  width="35%" class="td_center"  valign="center">
    _________________________<br>
    FIRMA DEL DISPENSADOR

    @if($receta->id_farmacia == 10)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
    
        @if($receta->id_farmacia == 20 || $receta->id_farmacia == 128)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_1.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
        @if($receta->id_farmacia == 18)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_3.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
          @if($receta->id_farmacia == 19)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_2.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
     @if($receta->id_farmacia == 109)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_4.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
    @if($receta->id_farmacia == 3)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_6.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
       @if(!in_array($receta->id_farmacia, [109,3, 19,20,10, 18, 128]))
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_5.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
                </td>
                <td  width="30%" class="td_left"   valign="left">
                  <br/><br/>_____________________________________
                  <br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRMA DEL ASEGURADO
                  <br/><br/><span style="font-size: 9px;font-style:italic;font-weight:bold">Nombres y Apellidos : </span>
                  <br/>       
                  <br/><br/><span style="font-size: 9px;font-style:italic;font-weight:bold">Celular : </span>
                  <br/><br/><span style="font-size: 9px;font-style:italic;font-weight:bold">Parentesco : </span>
                </td>
              </tr>
            </table>
            
            <footer>
              
            </footer>
          </div>
          
        </td>
        @if(count($productos)<9)


<td style="width:13cm;">

          <div id="columna2">
            <table>
              <tbody>
                <tr>
                  <td class="td_left" width="15%">
                    
                  </td>
                  <td class="td_left" width="20%">
                    
                  </td>
                  <td style="text-align:center" width="40%">
                      <h4>N° : <?php echo $receta->nro_receta ?> </h4>
                    
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
                    <img src="images/logo_sanidad_negro.png" width="54px"/>
                  </td>
                  <td class="td_left" width="20%">
                    <table style="padding-bottom:10px">
                      <tr  valign="center"><td><br/><b><?php echo $establecimiento->nombre;?> </b></td></tr>
                      <tr  valign="center"><td><?php echo $establecimiento->direccion_establecimiento;?></td></tr>
                    </table>
                  </td>
                  <td>
                    <table>
                      <tr>
                        <td style="text-align: center"><h2> RECETA - VALE @if(!empty($es_proc_prog)) - PROC. PROG. @endif</h2></td>
                      </tr>
                      <tr>
                        <td style="text-align: center"><b>CUPS PNP</b>
                          @if(isset($cups_pnp->cups)){{$cups_pnp->cups}}
                          @else {{'---------------------------'}}@endif</td>
                      </tr>
                    </table>
                  </td>
                  <td width="30%"><br/><br/>
                    <table>
                      <tr>
                        <td><b>CODIGO UPSS: </b>110500</td>
                      </tr>
                      <tr>
                        <td><b>COD. SITEDS: </b>{{$receta->codigo_siteds}}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                
              </tbody>
            </table>

            <table>
              <tr>
                <td width="15%"><strong>IPRESS : </strong> <?php echo $receta->codigoipress ?></td>
                <td width="30%" class="text-align:left"><strong>TIPO DE ATENCIÓN : </strong>@if($receta->tipo_receta=='QUIRURGICO')
                    CENTRO QUIRURGICO
                    @else
                    @if($receta->tipo_receta=='NUTRICIONAL')
                    SOPORTE NUTRICIONAL
                    @else
                    <?php echo $receta->tipo_receta ?>
                    @endif
                    @endif
                </td>
                <td width="55%"><strong>FARMACIA : </strong> <?php echo $receta->farmacia ?></td>
              </tr>
              <tr>
                <td class="text-align:left"><strong><?php echo $receta->tipodoc_beneficiario ?> : </strong>
                <?php echo $receta->dni_beneficiario ?> </td>
                <td class="text-align:left">
                  <strong>NRO H.C. : </strong>
                <?php if($receta->nro_historia!=""): 
                              echo $receta->nro_historia;
                          else:
                              echo $receta->nro_historia2;
                          endif; 
                    ?>
                </td>
                <td><strong>APELLIDOS Y NOMBRES : </strong><?php echo $receta->paterno_beneficiario . " " . $receta->materno_beneficiario . ", " . $receta->nombre_beneficiario ?></td>
              </tr>
              <tr>
                <td class="text-align:left"><strong>EDAD : </strong>
                    <?php 
                          if($receta->fecha_nacimiento_beneficiario!=''):
                            $edad = Carbon::createFromFormat('d/m/Y', $receta->fecha_nacimiento_beneficiario)->format('Y/m/d');
                            echo $edad = Carbon::parse($edad)->age;
                          endif;
                    ?>
                  
                </td>
                <td><strong>PARENTESCO : </strong>
                  <?php echo $receta->tipo_beneficiario;?>
                </td>
                <td><strong>FECHA EXPIRACIÓN : </strong>
                    <?php
                      if($receta->fecha_expiracion==''):
                        if($receta->tipo_receta=='EMERGENCIA'):
                          $fecha_inicio = strtotime('+1 day', strtotime($receta->fecha_registro));
                          $fecha_expiracion = date('j-m-Y', $fecha_inicio);
                        else:
                          if($receta->tipo_receta=='HOSPITALIZACION'):
                            $fecha_inicio = strtotime('+3 day', strtotime($receta->fecha_registro));
                            $fecha_expiracion = date('j-m-Y', $fecha_inicio);
                          else:
                            $fecha_inicio = strtotime('+29 day', strtotime($receta->fecha_registro));
                            $fecha_expiracion = date('j-m-Y', $fecha_inicio);
                          endif;
                        endif;
                      else:
                        $originalDate4 = $receta->fecha_expiracion;
                        $fecha_expiracion = date("d-m-Y", strtotime($originalDate4));
                      endif;
                      echo $fecha_expiracion;
                    ?>
                </td>
              </tr>
              @if($titular!=null && $receta->dni_titular!=$receta->dni_beneficiario)
              <tr>
                <td colspan="3">
                  <strong>DATOS DEL TITULAR</strong>
                </td>
              </tr>
              <tr>
                <td class="text-align:left"><strong><?php echo $titular->tipo_doc_ident ?> : </strong>
                  <?php echo $receta->dni_titular ?></td>
                <td><strong>GRADO : </strong>
                    <?php echo $receta->grado . "&nbsp;" ?>
                </td>
                <td><strong>APELLIDOS Y NOMBRES : </strong>
                <?php echo $titular->paterno." ".$titular->materno.", ".$titular->nombre ?>
                </td>
              </tr>
              @endif
              <tr>
                <td colspan="2"><strong>MEDICO : </strong>
                <?php echo $receta->nombre_medico . " " . $receta->paterno_medico . " " . $receta->materno_medico ?></td>
                <td><strong>ESPECIALIDAD : </strong>
                <?php echo $receta->especializacion ?></td>
              </tr>
              <tr>
                <td colspan="2"><strong>COLEGIATURA : </strong>
                <?php echo $receta->colegiatura_medico ?></td>
                <td><strong>UPSS - SERVICIO : </strong>
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
              </tr>
              <tr valign="left">
                <td  class="titulos" width="100%" style="padding-right:0px">
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
          
            <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="250" valign="top">
                  <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
                    <tbody>
                      <?php if($receta->id_prestacion!=0): ?>
                      <tr>
                        <td colspan="5" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                          <b>PRESCRIPCIÓN</b>
                        </td>
                        <td colspan="3" class="titulos" style="border:1px solid #A4A4A4;font-size:8px!important;font-style:italic;text-align:left;font-weight:bold">FECHA DE DISPENSACIÓN : <?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?></td>
                      </tr>
                      <?php else: ?>
                        <tr>
                        <td colspan="5" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                          <b>PRESCRIPCIÓN</b>
                        </td>
                        <td colspan="3" class="titulos" style="border:1px solid #A4A4A4;font-size:8px!important;font-style:italic;text-align:left;font-weight:bold">FECHA DE DISPENSACIÓN : <?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?></td>
                      </tr>
                      <?php endif; ?>
                      <tr>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">N°
                        </td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="4%">
                          PRODUCTO</td>
                        <!--td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">UN.
                          MED.</td-->
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">CANT.
                        </td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">DÍAS
                        </td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">
                          VIA ADM.</td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="2%">
                          POSOLOGÍA</td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">CANT. DADO</td>
                        <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="1.5%">OBSERV.</td>
                      </tr>
                      <?php $n = 0; ?>
                      @foreach($productos as $p)
                      <?php $n++;?>
                      <tr>
                        <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
                        <td style="text-align:left; border:1px solid #A4A4A4">{{$p->codigo}} - {{$p->nombre}}
                          @if($p->presentacion_comercial==1)
                          ( {{$p->cantidad_prescrita}} X {{$p->presentacion_comercial}} )
                          @endif
                          @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                          ({{$p->cantidad_prescrita}} L )
                          @endif
                        </td>
                        <!--td style="border:1px solid #A4A4A4">{{$p->abreviatura}}</td-->
                        <td style="border:1px solid #A4A4A4">@if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                            <?php $cantidad_pre = ($p->cantidad_prescrita)/1000; echo  $cantidad_pre; ?>
                          @else
                            <?php $cantidad_pre = ($p->cantidad_prescrita); echo  $cantidad_pre; ?>
                          @endif</td>
                        <td style="border:1px solid #A4A4A4">{{$p->tiempo_periodo}}</td>
                        <td style="border:1px solid #A4A4A4">{{$p->via}}</td>
                        @if (intval($receta->id) === 9245184)
                          <td style="border:1px solid #A4A4A4; font-size: 7px;">{{$p->posologia}}</td>
                        @else
                        <td style="border:1px solid #A4A4A4">{{$p->posologia}}</td>
                        @endif
                        <td style="border:1px solid #A4A4A4">
                          @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                            <?php $cantidad_dis = ($p->cantidad_dispensada)/1000; echo  $cantidad_dis; ?>
                          @else
                            <?php $cantidad_dis = ($p->cantidad_dispensada); echo  $cantidad_dis; ?>
                          @endif
                        </td>
                        <td style="border:1px solid #A4A4A4">
                          <?php   
                              if($cantidad_pre>$cantidad_dis):
                                switch ($p->id_observacion) {
                                  case 0: if(($cantidad_dis==0) || ($cantidad_dis!=0)): echo "Sin Stock"; else: echo " "; endif;break;
                                  case 1: if(($cantidad_dis==0) || ($cantidad_dis!=0)) echo "Sin Stock"; break;
                                  case 2: if(($cantidad_dis==0) || ($cantidad_dis!=0)) echo "Paciente tiene"; break;
                                  case 3: if($cantidad_dis==0) echo "No requiere"; break;
                                  case 4: if($cantidad_dis==0) echo "No corresponde"; break;
                                  case 5: if($cantidad_dis==0) echo "Mal prescrito"; break;
                                  case 6: if($cantidad_dis==0) echo "Alerta DIGEMID"; break;
                                  case 7: if($cantidad_dis==0) echo "Suspendido"; break;
                                  case 8: if($cantidad_dis==0) echo "CIE 10 (DIAGNÓSTICO)"; break;
                                  case 9: if($cantidad_dis==0) echo "N DE CAMA "; break;
                                  case 10: if($cantidad_dis==0) echo "NOMBRE DEL SERVICIO"; break;
                                  case 11: if($cantidad_dis==0) echo "CONCENTRACIÓN DEL PRODUCTO FARMACEÚTICO "; break;
                                  case 12: if($cantidad_dis==0) echo "FORMA FARMACEÚTICA DEL PRODUCTO FARMACEÚTICO"; break;
                                  case 13: if($cantidad_dis==0) echo "VIA DE ADMINISTRACIÓN"; break;
                                  case 14: if($cantidad_dis==0) echo "CANTIDAD PRESCRITA"; break;
                                  case 15: if($cantidad_dis==0) echo "FIRMA DEL MÉDICO PRESCRIPTOR"; break;
                                  case 16: if($cantidad_dis==0) echo "ERROR DE DOSIFICACIÓN"; break;
                                  case 17: if($cantidad_dis==0) echo "SEGÚN INDICACIÓN MÉDICA"; break;
                                  case 18: if($cantidad_dis==0) echo "ATENDIDO"; break;
                                  case 19: if($cantidad_dis==0)  echo "Por Recoger"; break;
                                  case 20: if($cantidad_dis==0)  echo "No Petitorio"; break;
                                } 
                              else:
                                echo " ";
                              endif;
                          ?>
                        </td>
                      </tr>
                      @endforeach
                      
                    </tbody>
                  </table>
                </td>
              </tr>
            </table>
            <br/><br/><br/>
            <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
              <tr>
                @if($receta->fecha_firma!='')
                  @if($receta->flag == 2)
                    <td width="35%" valign="top">
                      <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                          <tbody>
                              <tr>
                                  <td ><p align="right"><img src="images/logo_sanidad_negro.png" width="40px;" /></p></td>
                                  <td ><p align="left">Firmado electrónicamente por: <br/><?php echo $receta->nombre_medico." ".$receta->paterno_medico." ".$receta->materno_medico ?><br/><?php echo$receta->fecha_firma ?></p></td>
                              </tr>
                          </tbody>
                      </table>
                    </td>
                  @else
                    @if($receta->flag == 1)
                    <td width="35%" valign="top">
                      <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                          <tbody>
                              <tr>
                                  <td ><p align="right"><img src="images/logo_sanidad_negro.png" width="40px;" /></p></td>
                                  <td ><p align="left">Firmado digitalmente por: <br/><?php echo $receta->nombre_medico." ".$receta->paterno_medico." ".$receta->materno_medico ?><br/><?php echo$receta->fecha_firma ?></p></td>
                              </tr>
                          </tbody>
                      </table>
                    </td>
                    @endif
                  @endif
                @endif
                <td  width="35%" class="td_center"  valign="center">
    _________________________<br>
    FIRMA DEL DISPENSADOR

    @if($receta->id_farmacia == 10)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
    
        @if($receta->id_farmacia == 20 || $receta->id_farmacia == 128)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_1.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
        @if($receta->id_farmacia == 18)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_3.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
          @if($receta->id_farmacia == 19)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_2.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
     @if($receta->id_farmacia == 109)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_4.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
    @if($receta->id_farmacia == 3)
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_6.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
       @if(!in_array($receta->id_farmacia, [109,3, 19,20,10, 18, 128]))
        <br><br>
        <img 
            src="{{ public_path('images/otro_logo_5.png') }}" 
            style="width: 200px; height: auto;"
        />
    @endif
                </td>
  


                <td  width="30%" class="td_left"  valign="left">
                  <br/><br/>_____________________________________
                  <br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRMA DEL ASEGURADO
                  <br/><br/><span style="font-size: 9px;font-style:italic;font-weight:bold">Nombres y Apellidos : </span>  
                  <br/>
                  <br/><br/><span style="font-size: 9px;font-style:italic;font-weight:bold">Celular : </span>
                  <br/><br/><span style="font-size: 9px;font-style:italic;font-weight:bold">Parentesco : </span>
                </td>
 
              </tr>
            </table>

            <footer>
              
            </footer>
          </div>
        </td>
        @endif
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
                $color = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script> --}}
</body>

</html>