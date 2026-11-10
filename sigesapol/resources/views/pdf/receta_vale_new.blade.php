<?php
use Carbon\Carbon;
$nro_diagnosticos = count($diagnosticos);
$max_por_pagina = 11;
$max_por_pagina_2 = 17;
$exceso = max(0, $nro_diagnosticos - 3);
$por_pagina_primera = max(0, $max_por_pagina - $exceso);
$por_pagina_restante = $max_por_pagina_2;

if ($por_pagina_primera > 0) {
  $primera_pagina = array_slice($productos, 0, $por_pagina_primera);
  $resto = array_slice($productos, $por_pagina_primera);
  $paginas_restantes = array_chunk($resto, $por_pagina_restante);
  $paginas = array_merge([$primera_pagina], $paginas_restantes);
} else {
  $paginas = array_chunk($productos, $por_pagina_restante);
}

$total_paginas = count($paginas);
$pagina_actual = 1;
$contador_global = 0;
$inicio_pagina = 0;

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>RECETA VALE</title>

<style>
@page {
    size: A4 landscape;
    margin: 0.3cm 0.8cm 0.2cm 0.8cm;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
}

.page {
    /* page-break-after: always; */
}

table.main {
    width: 100%;
    border-collapse: collapse;
}

td.col {
    width: 100%;
    vertical-align: top;
}

.col-inner {
    min-height: 18cm;
    padding: 4px;
}

</style>
</head>

<body>

<?php foreach ($paginas as $productos_pagina): ?>

<div class="page">
  <table class="main" border="0" >
    <tr>
      <td class="col">
        <div class="col-inner" >
          <!-- ================= ENCABEZADO ================= -->
          <?php if ($pagina_actual == 1): ?>
          <table width="95%" style="padding-right:10px;">
            <tr>
              <td width="15%"></td>
              <td width="20%"></td>
              <td width="40%" align="center">
                <b>N° :</b> <?php echo $receta->nro_receta; ?>
              </td>
              <td width="25%">
                <b>F.EXPEDICIÓN :</b>
                <?php
                  if ($receta->fecha_registro != '') {
                    echo date('d-m-Y', strtotime($receta->fecha_registro));
                  } else {
                    echo '---';
                  }
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <img src="images/logo_sanidad_negro.png" width="50">
              </td>
              <td>
                <b><?php echo $establecimiento->nombre; ?></b><br>
                <?php echo $establecimiento->direccion_establecimiento; ?>
              </td>
              <td align="center">
                <b>RECETA - VALE @if(!empty($es_proc_prog)) - PROC. PROG. @endif</b><br>
                CUPS PNP
                <?php
                  if (isset($cups_pnp) && isset($cups_pnp->cups)) {
                    echo $cups_pnp->cups;
                  } else {
                    echo '--------------------';
                  }
                ?>
              </td>
              <td>
                <b>COD. UPSS:</b> 110500<br>
                <b>SITEDS:</b> <?php echo $receta->codigo_siteds; ?>
              </td>
            </tr>
          </table>
          <!-- ================= DATOS PACIENTE ================= -->
          <table width="95%" style="padding-right:10px;">
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
            <?php if($receta->id_prestacion!=0): ?>
            @if(Auth::user()->id_farmacia == 19 || Auth::user()->id_farmacia == 128)

              <tr>
                <td class="text-align:left"><strong>TALLA : </strong>
                  <?php echo $receta->talla;?>
                </td>
                <td><strong>PESO : </strong>
                  <?php echo $receta->peso;?>
                </td>
                <td><strong>CAMA : </strong>
                    <?php
                      echo $receta->cama;
                    ?>
                </td>
              </tr>
            @endif
          <?php endif; ?>
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
              <td class="text-align:left"><strong><?php echo $receta->tipo_doc_ident ?> : </strong>
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
          <table width="95%" border="0px" style="background-color:white !important; padding-right:10px;">
              <tr>
                <td><strong>DIAGNOSTICO(S) (CIE 10)</strong></td>
                <td><strong>FECHA DISPENSACIÓN : </strong></td>
              </tr>
              <tr valign="left">
                <td  class="titulos" width="70%" style="padding-left:0px">
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
                <td   class="titulos" width="20%" style="padding-left:0px">
                    @if($receta->id_prestacion != 0)
                    <?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?> - <?php echo date("H:i:s", strtotime($receta->updated_at)) ?>
                    @else
                    <?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?> - <?php echo date("H:i:s", strtotime($receta->created_at)) ?>
                    @endif
                </td>
              </tr>
          </table>
          <?php else: ?>
          <table width="95%" style="padding-right:10px;">
            <tr>
              <td width="15%"></td>
              <td width="20%"></td>
              <td width="40%" align="center">
                <b>N° :</b> <?php echo $receta->nro_receta; ?>
              </td>
              <td width="25%">
                <b>F.EXPEDICIÓN :</b>
                <?php
                  if ($receta->fecha_registro != '') {
                    echo date('d-m-Y', strtotime($receta->fecha_registro));
                  } else {
                    echo '---';
                  }
                ?>
              </td>
            </tr>
          </table>
          <?php endif; ?>
          <!-- ================= PRODUCTOS ================= -->
          <table width="95%" style="background-color:white !important;border-collapse:separate;padding-right:10px;">
            <tbody>
              <tr>
                <td colspan="6" style="border-top:1px solid #000;height:1px;"></td>
              </tr>

              <tr>
                <td  style="font-style:italic;font-weight:bold" width="2%">N </td>
                <td  style="font-style:italic;font-weight:bold" width="36%">PRODUCTO</td>
                <td  style="font-style:italic;font-weight:bold" width="5%">DÍAS</td>
                <td  style="font-style:italic;font-weight:bold" width="5%">CANT.</td>
                <td  style="font-style:italic;font-weight:bold" width="5%">ENTR.</td>
                <td  style="font-style:italic;font-weight:bold" width="5%">OBSERV.</td>
              </tr>
              <tr>
                <td colspan="6" style="border-top:1px solid #000;height:1px;"></td>
              </tr>
              @foreach($productos_pagina as $p)
              <?php $contador_global++; ?>

              <tr>
                  <td style="padding-top:-17px"><?php echo $contador_global ?>.-</td>

                <td>
                  {{$p->codigo}} - {{$p->nombre}}

                  @if(in_array($p->id_producto,[11018,13748,11019,11020,11021,11023,11024,11025,11026]))
                    ( {{$p->cantidad_prescrita}} X {{$p->presentacion_comercial}} )
                  @endif

                  @if(in_array($p->id_producto,[11045,15938,14607,14608]))
                    ( {{$p->cantidad_prescrita}} L )
                  @endif

                  @if($receta->id_prestacion!=0)
                    <div style="margin-left:6px;font-size:7px;">
                      <b>Vía:</b> {{$p->via}} &nbsp;&nbsp;
                      <b>Diagnos.:</b> {{$p->cod_diagnostico}}<br>
                      <b>Posología:</b> {{$p->posologia}}
                    </div>
                  @endif
                </td>

                <td>{{$p->tiempo_periodo}}</td>

                <td>
                  <?php $cantidad_pre = in_array($p->id_producto,[11045,15938,14607,14608]) ? $p->cantidad_prescrita / 1000 : $p->cantidad_prescrita; echo $cantidad_pre;?>
                </td>

                <td>
                  <?php $cantidad_dis = in_array($p->id_producto,[11045,15938,14607,14608]) ? $p->cantidad_dispensada / 1000 : $p->cantidad_dispensada; echo $cantidad_dis;?>
                </td>

                <td>
                  <?php
                    if($cantidad_pre > $cantidad_dis):
                     switch ($p->id_observacion) {
                      case 0: 
                        if (Auth::user()->id_farmacia == 128):
                          if(($cantidad_dis==0) || ($cantidad_dis!=0)): echo "Sin Stock"; else: echo " "; endif;
                        else:
                          if($cantidad_dis==0): echo "Sin Stock"; else: echo " "; endif;
                        endif;
                        break;
                        case 1: if(($cantidad_dis==0) || ($cantidad_dis!=0)) echo "Sin Stock"; break;
                        case 2: if(($cantidad_dis==0) || ($cantidad_dis!=0)) echo "PT"; break;
                        case 3: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "NR"; break;
                        case 4: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "NC"; break;
                        case 5: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 6: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "Alerta DIGEMID"; break;
                        case 7: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "Suspendido"; break;
                        case 8: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 9: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 10: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 11: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 12: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 13: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 14: if(($cantidad_dis==0) || ($cantidad_dis!=0)) echo "MP"; break;
                        case 15: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 16: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 17: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "RP"; break;
                        case 18: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "Atendido"; break;
                        case 19: if(($cantidad_dis==0))  echo "Por Recoger"; break;
                        case 20: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "No Petitorio"; break;
                    } 
                  else:
                    echo " ";
                  endif;
                  ?>
                </td>
              </tr>

              @endforeach

              <tr>
                <td colspan="6" style="border-top:1px solid #000;height:1px;"></td>
              </tr>
            </tbody>
          </table>
          <!-- ================= FIRMAS ================= -->
          <?php if ($pagina_actual == $total_paginas): ?>
          <table width="95%" style="padding-right:10px;">
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
<td align="center" valign="top">
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


<td align="center" valign="top">
    _________________________<br>
    FIRMA DEL ASEGURADO
</td>

          </tr>
          </table>
          <?php endif; ?>
        </div>
      </td>
      <td class="col">
        <div class="col-inner">
          <?php if ($pagina_actual == 1): ?>
          <table width="95%" style="padding-left:10px;">
            <tr>
              <td width="15%"></td>
              <td width="20%"></td>
              <td width="40%" align="center">
                <b>N° :</b> <?php echo $receta->nro_receta; ?>
              </td>
              <td width="25%">
                <b>F.EXPEDICIÓN :</b>
                <?php
                  if ($receta->fecha_registro != '') {
                    echo date('d-m-Y', strtotime($receta->fecha_registro));
                  } else {
                    echo '---';
                  }
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <img src="images/logo_sanidad_negro.png" width="50">
              </td>
              <td>
                <b><?php echo $establecimiento->nombre; ?></b><br>
                <?php echo $establecimiento->direccion_establecimiento; ?>
              </td>
              <td align="center">
                <b>RECETA - VALE @if(!empty($es_proc_prog)) - PROC. PROG. @endif</b><br>
                CUPS PNP
                <?php
                  if (isset($cups_pnp) && isset($cups_pnp->cups)) {
                    echo $cups_pnp->cups;
                  } else {
                    echo '--------------------';
                  }
                ?>
              </td>
              <td>
                <b>COD. UPSS:</b> 110500<br>
                <b>SITEDS:</b> <?php echo $receta->codigo_siteds; ?>
              </td>
            </tr>
          </table>
          <!-- ================= DATOS PACIENTE ================= -->
          <table width="95%" style="padding-left:10px;">
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
            <?php if($receta->id_prestacion!=0): ?>
            @if(Auth::user()->id_farmacia == 19 || Auth::user()->id_farmacia == 128)
            <tr>
              <td class="text-align:left"><strong>TALLA : </strong>
                <?php echo $receta->talla;?>
              </td>
              <td><strong>PESO : </strong>
                <?php echo $receta->peso;?>
              </td>
              <td><strong>CAMA : </strong>
                  <?php
                    echo $receta->cama;
                  ?>
              </td>
            </tr>
            @endif
            <?php endif; ?>
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
              <td class="text-align:left"><strong><?php echo $receta->tipo_doc_ident ?> : </strong>
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
          <table width="95%" border="0px" style="background-color:white !important; padding-left:9px;">
              <tr>
                <td><strong>DIAGNOSTICO(S) (CIE 10)</strong></td>
                <td><strong>FECHA DISPENSACIÓN : </strong></td>
              </tr>
              <tr valign="left">
                <td  class="titulos" width="75%" style="padding-right:0px">
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
                <td   class="titulos" width="20%" style="padding-right:0px">
                    @if($receta->id_prestacion != 0)
                    <?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?> - <?php echo date("H:i:s", strtotime($receta->updated_at)) ?>
                    @else
                    <?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?> - <?php echo date("H:i:s", strtotime($receta->created_at)) ?>
                    @endif
                </td>
              </tr>
          </table>
          <?php else: ?>
          <table width="95%" style="padding-right:10px;">
            <tr>
              <td width="15%"></td>
              <td width="20%"></td>
              <td width="40%" align="center">
                <b>N° :</b> <?php echo $receta->nro_receta; ?>
              </td>
              <td width="25%">
                <b>F.EXPEDICIÓN :</b>
                <?php
                  if ($receta->fecha_registro != '') {
                    echo date('d-m-Y', strtotime($receta->fecha_registro));
                  } else {
                    echo '---';
                  }
                ?>
              </td>
            </tr>
          </table>
          <?php endif; ?>
          <!-- ================= PRODUCTOS ================= -->
          <table width="95%" style="background-color:white !important;border-collapse:separate;padding-left:10px;">
            <tbody>
              <tr>
                <td colspan="6" style="border-top:1px solid #000;height:1px;"></td>
              </tr>

              <tr>
                <td  style="font-style:italic;font-weight:bold" width="2%">N </td>
                <td  style="font-style:italic;font-weight:bold" width="36%">PRODUCTO</td>
                <td  style="font-style:italic;font-weight:bold" width="5%">DÍAS</td>
                <td  style="font-style:italic;font-weight:bold" width="5%">CANT.</td>
                <td  style="font-style:italic;font-weight:bold" width="5%">ENTR.</td>
                <td  style="font-style:italic;font-weight:bold" width="5%">OBSERV.</td>
              </tr>
              <tr>
                <td colspan="6" style="border-top:1px solid #000;height:1px;"></td>
              </tr>

              @foreach($productos_pagina as $p)
              <?php $inicio_pagina++; ?>
              <tr>
                <td  style="padding-top:-17px"><?php echo $inicio_pagina; ?>.-</td>

                <td>
                  {{$p->codigo}} - {{$p->nombre}}

                  @if(in_array($p->id_producto,[11018,13748,11019,11020,11021,11023,11024,11025,11026]))
                    ( {{$p->cantidad_prescrita}} X {{$p->presentacion_comercial}} )
                  @endif

                  @if(in_array($p->id_producto,[11045,15938,14607,14608]))
                    ( {{$p->cantidad_prescrita}} L )
                  @endif

                  @if($receta->id_prestacion!=0)
                    <div style="margin-left:6px;font-size:7px;">
                      <b>Vía:</b> {{$p->via}} &nbsp;&nbsp;
                      <b>Diagnos.:</b> {{$p->cod_diagnostico}}<br>
                      <b>Posología:</b> {{$p->posologia}}
                    </div>
                  @endif
                </td>

                <td>{{$p->tiempo_periodo}}</td>

                <td>
                  <?php $cantidad_pre = in_array($p->id_producto,[11045,15938,14607,14608]) ? $p->cantidad_prescrita / 1000 : $p->cantidad_prescrita; echo $cantidad_pre;?>
                </td>

                <td>
                  <?php $cantidad_dis = in_array($p->id_producto,[11045,15938,14607,14608]) ? $p->cantidad_dispensada / 1000 : $p->cantidad_dispensada; echo $cantidad_dis;?>
                </td>

                <td>
                  <?php
                    if($cantidad_pre > $cantidad_dis):
                     switch ($p->id_observacion) {
                      case 0: 
                        if (Auth::user()->id_farmacia == 128):
                          if(($cantidad_dis==0) || ($cantidad_dis!=0)): echo "Sin Stock"; else: echo " "; endif;
                        else:
                          if($cantidad_dis==0): echo "Sin Stock"; else: echo " "; endif;
                        endif;
                        break;
                        case 1: if(($cantidad_dis==0) || ($cantidad_dis!=0)) echo "Sin Stock"; break;
                        case 2: if(($cantidad_dis==0) || ($cantidad_dis!=0)) echo "PT"; break;
                        case 3: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "NR"; break;
                        case 4: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "NC"; break;
                        case 5: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 6: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "Alerta DIGEMID"; break;
                        case 7: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "Suspendido"; break;
                        case 8: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 9: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 10: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 11: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 12: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 13: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 14: if(($cantidad_dis==0) || ($cantidad_dis!=0)) echo "MP"; break;
                        case 15: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 16: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "MP"; break;
                        case 17: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "RP"; break;
                        case 18: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "Atendido"; break;
                        case 19: if(($cantidad_dis==0))  echo "Por Recoger"; break;
                        case 20: if(($cantidad_dis==0) || ($cantidad_dis!=0))  echo "No Petitorio"; break;
                    } 
                  else:
                    echo " ";
                  endif;
                  ?>
                </td>
              </tr>
              @endforeach
              <tr>
                <td colspan="6" style="border-top:1px solid #000;height:1px;"></td>
              </tr>
            </tbody>
          </table>

          <!-- ================= FIRMAS ================= -->
          <?php if ($pagina_actual == $total_paginas): ?>
          <table width="95%" style="padding-left:10px;">
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
<td align="center" valign="top">
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


<td align="center" valign="top">
    _________________________<br>
    FIRMA DEL ASEGURADO
</td>

          </tr>
          </table>
          <?php endif; ?>
        </div>
      </td>
    </tr>
  </table>
</div>
<?php if ($pagina_actual < $total_paginas): ?>
  <div style="page-break-after: always;"></div>
<?php endif; ?>
<?php $pagina_actual++; ?>
<?php endforeach; ?>
<script type="text/php">
if (isset($pdf)) {

    $text = "Pagina {PAGE_NUM} de {PAGE_COUNT}";
    $size = 8;
    $color = array(0,0,0);
    $y = 550;

    //==(columna izquierda)
    $x_original = 341;
    $pdf->page_text($x_original, $y, $text, null, $size, $color);

    //==(columna derecha)
    $x_copia = 750;
    $pdf->page_text($x_copia, $y, $text, null, $size, $color);
}
</script>

</body>
</html>