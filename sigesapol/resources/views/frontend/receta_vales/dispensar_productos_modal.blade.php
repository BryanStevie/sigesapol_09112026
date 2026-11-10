<div class="row">
  @if(count($recetas)>0)
  @if(count($medicamentos)>0)
  <div class="col-md-2">
    <label>{{$recetas[0]->tipodoc_beneficiario}}</label>
    <p>{{$recetas[0]->dni_beneficiario}}</p>

  </div>
  <div class="col-md-5">
    <label>NOMBRE Y APELLIDOS</label>
    <p>
      <?php echo $recetas[0]->nombre_beneficiario . " " . $recetas[0]->paterno_beneficiario . " " . $recetas[0]->materno_beneficiario ?>
    </p>
  </div>
  @if($recetas[0]->tipo_receta=="HOSPITALIZACION")
  <div class="col-md-2">
    <label>Historia Clinica</label>
    <p class="text-center">
      {{$recetas[0]->nro_historia}}
    </p>
  </div>
  <div class="col-md-1">
    <label>Nro_GUIA</label>
    <p class="text-center">
      {{$recetas[0]->nro_guia}}
    </p>
  </div>

  <div class="col-md-1">
    <label>Intercons.</label>
    <p class="text-center">
      {{$recetas[0]->interconsulta}}
    </p>
  </div>
  @else
  <div class="col-md-3">
    <label>HISTORIA CLÍNICA</label>
    <p class="text-center">
      {{$recetas[0]->nro_historia}}
    </p>
  </div>
  @endif
  <div class="col-md-2">
    <label>BENEFICIARIO</label>
    <p>{{$recetas[0]->tipo_beneficiario}}</p>
  </div>
  @if($recetas[0]->tipo_receta=="CIRUGIA")
  <div class="col-md-3">
    <label>SERVICIO</label>
    <p>
      {{$recetas[0]->consultorio}}
    </p>
  </div>
  <div class="col-md-3">
    <label>TIPO DE ATENCI&Oacute;N</label>
    <p>
      {{$recetas[0]->tipo_receta}}
    </p>
  </div>
  <div class="col-md-3">
    <label>FECHA DE INTERVENCION</label>
    <p>
      {{$recetas[0]->fecha_intervencion}}
    </p>
  </div>
  <div class="col-md-3">
    <label>NRO. HISTORIA</label>
    <p>
      {{$recetas[0]->nro_historia}}
    </p>
  </div>
  <div class="col-md-6" style="margin-bottom: 10px">
    <table border="1px" style="background-color:white !important">
      <tbody>
        <tr>
          <td colspan="3" class="td_left">DIAGNOSTICOS</td>
        </tr>
        <tr>
          <td class="td_left td_ancho_n">N</td>
          <td class="td_left td_ancho_codigo">CODIGO</td>
          <td class="td_left">DESCRIPCION</td>
        </tr>
        <tr>
          <td class="td_left td_ancho_n">1</td>
          <td class="td_left">{{$recetas[0]->cie}}</td>
          <td class="td_left">{{$recetas[0]->diagnostico}}</td>
        </tr>
        <?php $n = 1; ?>
        @foreach($diagnosticos as $d)
        <tr>
          <td class="td_left td_ancho_n"><?php
                                          $n = $n + 1;
                                          echo $n;
                                          ?></td>
          <td class="td_left">{{$d->codigo}}</td>
          <td class="td_left">{{$d->nombre}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  
  <div class="col-md-12" style="margin-bottom: 10px">
    <!----------------------------------------------------->
    <table border="1px" style="background-color:white !important">
      <tbody>
        <tr>
          <td colspan="4" class="td_left">PROFESIONALES DE LA SALUD</td>
        </tr>
        <tr>
          <td class="td_left td_ancho_n">N</td>
          <td class="td_left td_ancho_codigo">DNI</td>
          <td class="td_left">NOMBRE Y APELLIDOS</td>
          <td class="td_left td_ancho_codigo">ESPECIALIDAD</td>
        </tr>
        <tr>
          <td class="td_left td_ancho_n">1</td>
          <td class="td_left td_ancho_codigo">{{$recetas[0]->dni_medico}}</td>
          <td class="td_left">{{$recetas[0]->nombre_medico}} {{$recetas[0]->paterno_medico}}
            {{$recetas[0]->materno_medico}}</td>
          <td class="td_left td_ancho_especialidad">{{$recetas[0]->especializacion}}</td>
        </tr>
        <?php $n = 1; ?>
        @foreach($cirujanos as $cir)
        <tr>
          <td class="td_left td_ancho_n"><?php
                                    $n = $n + 1;
                                    echo $n;
                                    ?></td>
          <td class="td_left td_ancho_codigo">{{$cir->dni}}</td>
          <td class="td_left">{{$cir->nombre}} {{$cir->paterno}} {{$cir->materno}} </td>
          <td class="td_left td_ancho_especialidad">{{$cir->nombre_especialidad}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <!----------------------------------------------------->
  </div>
  @else
  @if($recetas[0]->tipo_receta=='EMERGENCIA' || $recetas[0]->tipo_receta=='HOSPITALIZACION' || $recetas[0]->id_establecimiento==76)
  <div class="col-md-3">
    <label>TALLA</label>
    <p>
      {{$recetas[0]->talla}}
    </p>
  </div>
  <div class="col-md-3">
    <label>PESO</label>
    <p>
      {{$recetas[0]->peso}}
    </p>
  </div>
  <div class="col-md-4">
    <label>CAMA</label>
    <p>{{$recetas[0]->cama}} 
    </p>
  </div>
  <div class="col-md-2">
    <label>SEXO</label>
    <p>
      {{$recetas[0]->sexo_beneficiario}}
    </p>
  </div>
  @endif
  <div class="col-md-3">
    <label>SERVICIO</label>
    <p>
      {{$recetas[0]->consultorio}}
    </p>
  </div>
  <div class="col-md-3">
    <label>TIPO DE ATENCI&Oacute;N</label>
    <p>
      {{$recetas[0]->tipo_receta}}
    </p>
  </div>
  <div class="col-md-4">
    <label>PROFESIONAL PRESCRIPTOR</label>
    <p>{{$recetas[0]->nombre_medico}} {{$recetas[0]->paterno_medico}} {{$recetas[0]->materno_medico}}
    </p>
  </div>
  <div class="col-md-2">
    <label>FECHA EXPEDICIÓN</label>
    <p>
      <?php
      $originalDate2 = $recetas[0]->fecha_registro;
      $fecha = date("d-m-Y", strtotime($originalDate2));
      echo $fecha;
      ?>
    </p>
  </div>

  <div class="col-md-6">
    <label>DIAGNOSTICO</label>
    <p>
      <?php
      $cad = "";
      $diagnosticos_mostrar = (count($diagnosticos_egreso) > 0) ? $diagnosticos_egreso : $diagnosticos;
      foreach ($diagnosticos_mostrar as $row) :
        $cad .= $row->codigo . " - " . $row->nombre . "<br>";
      endforeach;
      echo $cad;
      ?>
    </p>
  </div>

  <div class="col-md-4">
    <label>REGISTRADOR</label>
    <p>{{$recetas[0]->nombre_usuario}} {{$recetas[0]->paterno_usuario}} {{$recetas[0]->materno_usuario}}</p>
  </div>
  <div class="col-md-2">
    <label>FECHA DE DISPENSACIÓN</label>
    <?php
    $fecha_expedicion = $recetas[0]->fecha_expedicion;
    $fecha_expedicion = date("d-m-Y", strtotime($fecha_expedicion));
    ?>
    <p><?php echo $fecha_expedicion ?></p>
  </div>
  @endif
  @if($recetas[0]->estado!=1)
  <div class="col-md-12">
    <button class="btn btn-danger" disabled="">
      Registro de Receta invalidada
    </button>
  </div>
  <div class="col-md-8" style="border: 1px solid red; margin-top: 10px; background-color: #FFDA6A">
    Motivo: {{$receta_devuelta[0]->motivo}}<br>
    <?php
    if (isset($receta_devuelta[0]->fecha_devolucion)) {
      $originalDate8 = $receta_devuelta[0]->fecha_devolucion;
      $fecha_devolucion = date("d-m-Y", strtotime($originalDate8));
    } else {
      $fecha_devolucion = "";
    }
    ?>
    Fecha de Invalidación: <?php echo $fecha_devolucion ?><br>
    Usuario Invalidador: {{$receta_devuelta[0]->name}} {{$receta_devuelta[0]->paterno}} {{$receta_devuelta[0]->materno}}
  </div>
  @endif

  <div class="col-md-12" style="text-align: left; font-size: 14px; margin-top: 15px">
    <legend>PRODUCTOS FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS DISPENSADOS</legend>
  </div>
  <div class="col-md-5" style="font-size: 9px;">
    <label>PRODUCTOS FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS</label>
  </div>

  <?php if (isset($flag_receta_dias) && $flag_receta_dias == 1) : ?>
  <div class="col-md-1" style="font-size: 10px">
    <label>TIEMPO<br />(DIAS)</label>
  </div>
  <?php endif; ?>

  <div class="col-md-1" style="font-size: 10px">
    <label>CANT.<br />PRESCRITA</label>
  </div>
  <div class="col-md-1" style="font-size: 10px">
    <label>CANT.<br />DISPENSADA</label>
  </div>
  <div class="col-md-1" style="font-size: 10px">
      <label>Via Adm.</label>
  </div>
  <div class="col-md-1" style="font-size: 10px">
      <label>Diagnostico</label>
  </div>
  <div class="col-md-1" style="font-size: 10px">
      <label>Posologia</label>
  </div>
  <div class="col-md-1" style="font-size: 10px">
    <label>ESTADO</label>
  </div>
  <?php if ($modo_registro == 1) : ?>
  <div class="col-md-1" style="font-size: 10px">
    <label>FARMACIA</label>
  </div>
  <?php endif; ?>
  <div class="col-md-1" style="font-size: 10px">
    <label></label>
  </div>
  <form id="frmDispensar" class="form-horizontal" method="post" action="{{ route('dispensar_producto')}}" id="frmReceta"
    style="font-size:12px;">
    <?php $i = 0; ?>
    @foreach($medicamentos as $med)
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" value="{{$recetas[0]->id}}" name="id_receta_vale">
    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
    <div class="row">
      <div class="col-md-5" style=" margin-bottom: 5px">
        <spam id="lblNombre">
          {{$med->codigo}} - {{$med->nombre}}
          @if($med->presentacion==1)
          ({{$med->cantidad_dispensada}} X {{$med->presentacion_comercial}})
          @endif
          - {{$med->abreviatura}}
          @if($med->id_producto==11045 || $med->id_producto==15938 || $med->id_producto==14607 || $med->id_producto==14608)
          ({{$med->cantidad_dispensada}} L )
          @endif
        </spam>
        <input type="hidden" class="form-control" value="{{$med->codigo}}" name="codigo_producto[]"
          id="codigo_producto[]">
        <input type="hidden" class="form-control" value="{{$med->id_producto}}" name="id_producto[]" id="id_producto[]">
        <input type="hidden" class="form-control" value="{{$med->id_producto_receta}}" name="id_producto_receta[]"
          id="id_producto_receta[]">
        <input type="hidden" value="{{$recetas[0]->id_farmacia}}" name="id_farmacia[]">
        <input type="hidden" value="{{$recetas[0]->nro_receta}}" name="nro_receta[]">
      </div>

      <?php if (isset($flag_receta_dias) && $flag_receta_dias == 1) : ?>
      <div class="col-md-1" style=" margin-bottom: 5px">
        <input type="text" id="txtTiempoPeriodo" class="" value="{{$med->tiempo_periodo}}" disabled=""
          style="width: 50px">
        <input type="hidden" class="" value="{{$med->tiempo_periodo}}" name="tiempo_periodo[]" id="tiempo_periodo[]">
      </div>
      <?php endif; ?>

      <div class="col-md-1" style=" margin-bottom: 5px">
        <input type="text" id="txtCantidadPrescrita" class="" value="<?php if($med->id_producto==11045 || $med->id_producto==15938 || $med->id_producto==14607 || $med->id_producto==14608){ echo ($med->cantidad_prescrita)/1000; }else{ echo $med->cantidad_prescrita; } ?>" disabled=""
          style="width: 50px">
        <input type="hidden" class="" value="<?php if($med->id_producto==11045 || $med->id_producto==15938 || $med->id_producto==14607 || $med->id_producto==14608){ echo ($med->cantidad_prescrita)/1000; }else{ $med->cantidad_prescrita;} ?>" name="cantidad_prescrita[]"
          id="cantidad_prescrita[]">
      </div>
      <div class="col-md-1" style=" margin-bottom: 5px">
        <input type="text" id="txtCantidadDispensada" class="" value="<?php if($med->id_producto==11045 || $med->id_producto==15938 || $med->id_producto==14607 || $med->id_producto==14608){ echo ($med->cantidad_dispensada)/1000; }else{ echo $med->cantidad_dispensada;} ?>" disabled=""
          style="width: 50px">
        <input type="hidden" class="" value="<?php if($med->id_producto==11045 || $med->id_producto==15938 || $med->id_producto==14607 || $med->id_producto==14608) echo ($med->cantidad_dispensada)/1000; else $med->cantidad_dispensada; ?>" name="cantidad_dispensada[]"
          id="cantidad_dispensada[]">
      </div>
      <div class="col-md-1" style=" margin-bottom: 5px">
        <?php if($recetas[0]->id_prestacion!=0): ?>
          <spam id="lblNombre"><?php echo $med->via; ?></spam> 
        <?php endif; ?>
      </div>
      <div class="col-md-1" style=" margin-bottom: 5px">
          <span id="lblNombre"><?php echo $med->cod_diagnostico; ?></span>
      </div>
      <div class="col-md-1" style=" margin-bottom: 5px">
        <?php if($recetas[0]->id_prestacion!=0): ?>
          <spam id="lblNombre"><?php echo $med->posologia; ?></spam> 
        <?php endif; ?>
      </div>
      <div class="col-md-1" style="margin-bottom: 5px">
        <?php   
            if($med->cantidad_prescrita>$med->cantidad_dispensada ):
                switch ($med->id_observacion) {
                  case 0: if (Auth::user()->id_farmacia == 128):
                              if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)): echo "Sin Stock"; else: echo " "; endif;
                          else:
                              if($med->cantidad_dispensada==0): echo "Sin Stock"; else: echo " "; endif;
                          endif;
                          break;
                  case 1: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Sin Stock"; break;
                  case 2: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Paciente tiene"; break;
                  case 3: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "No requiere"; break;
                  case 4: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "No corresponde"; break;
                  case 5: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Mal prescrito"; break;
                  case 6: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Alerta DIGEMID"; break;
                  case 7: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Suspendido"; break;
                  case 8: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "CIE 10 (Diagnostico)"; break;
                  case 9: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "N de cama"; break;
                  case 10: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Nombre del servicio"; break;
                  case 11: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Concentracion del producto farmaceutico"; break;
                  case 12: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Forma farmaceutica del producto farmaceutico"; break;
                  case 13: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Via de administracion"; break;
                  case 14: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Cantidad prescrita"; break;
                  case 15: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Firma del medico prescriptor"; break;
                  case 16: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Error de dosificación"; break;
                  case 17: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Según indicación médica"; break;
                  case 18: if (Auth::user()->id_farmacia==128):
                              if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "Atendido"." - ".$med->receta_nro;
                           endif;
                           break;
                  case 19: if(Auth::user()->id_farmacia == 18):
                              if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "Por Recoger";
                           endif;
                           break;
                  case 20: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "No Petitorio"; break;
                }
            else:
                echo '<i class="fa fa-check"></i>';
            endif;
        ?>
      </div>
      <?php if ($modo_registro == 1) : ?>
      <div class="col-md-3" style=" margin-bottom: 5px">
        <spam>{{$med->farmacia}}</spam>
      </div>
      <?php endif; ?>
    </div>
    <?php $i++; ?>
    @endforeach
    <div class="col-md-3">
      <label>HORA DE DISPENSANCIÓN</label>
      <p>
        <?php
        $originalDate2 = $recetas[0]->updated_at;
        $fecha = date("H:i:s", strtotime($originalDate2));
        echo $fecha;
        ?>
      </p>
    </div>
    <div class="col-md-3">
      <label>TOTAL PRODUCTOS</label>
      <p>
        <?php
        echo $i;
        ?>
      </p>
    </div>
    @if($recetas[0]->estado==0)
    <div class="col-md-7 pull-right" style="border-top: 1px solid gray; padding-top: 10px">
      Receta Invalidada
    </div>
    @else
    @if($recetas[0]->tipo_receta!="CIRUGIA")
    <div class="col-md-12 pull-right" style="border-top: 1px solid gray; padding-top: 10px">

      <a href="/imprimir_receta_vale/{{$recetas[0]->id}}/{{$recetas[0]->id_farmacia}}" class="btn btn-danger pull-right"
        target="_blank">
        Imprimir Receta Dispensada <i class="fa fa-file-pdf-o"></i>
      </a>
      <?php
      if ($recetas[0]->ruta_receta == "") {
      ?>
      <button class="btn btn-danger pull-right" style="margin-right:15px" disabled="">Imprimir Receta Prescrita <i
          class="fa fa-file-pdf-o"></i></button>
      <?php
      } else {
          if ($recetas[0]->id >3618175) {
      ?>
      <a href="/{{$recetas[0]->flag}}/{{$recetas[0]->ruta_receta}}" class="btn btn-danger pull-right" target="_blank"
        style="margin-right:15px">
        Imprimir Receta Prescrita <i class="fa fa-file-pdf-o"></i>
      </a>
      <?php
          }else{
            ?>
        <a href="https://sgf-prv.saludpol.gob.pe:10451/{{$recetas[0]->ruta_receta}}" class="btn btn-danger pull-right" target="_blank"
        style="margin-right:15px">
        Imprimir Receta Prescrita <i class="fa fa-file-pdf-o"></i>
      </a>
          <?php 
          }
      }
      ?>

      <?php
      if ($recetas[0]->ruta_prestacion == "") {
      ?>
      <button class="btn btn-danger pull-right" style="margin-right:15px" disabled="">Imprimir Prestación Medica <i
          class="fa fa-file-pdf-o"></i></button>
      <?php
      } else {
        if ($recetas[0]->id >3618175) {
      ?>
      <a href="/{{$recetas[0]->flag}}/{{$recetas[0]->ruta_prestacion}}" class="btn btn-danger pull-right"
        target="_blank" style="margin-right:15px">
        Imprimir Prestación Medica <i class="fa fa-file-pdf-o"></i>
      </a>
      <?php
        }
        else{ ?>
      <a href="https://sgf-prv.saludpol.gob.pe:10451/{{$recetas[0]->flag}}/{{$recetas[0]->ruta_prestacion}}" class="btn btn-danger pull-right"
        target="_blank" style="margin-right:15px">
        Imprimir Prestación Medica <i class="fa fa-file-pdf-o"></i>
      </a>
        <?php }
      }
      ?>


    </div>
    @else
    <div class="col-md-5" style="border-top: 1px solid gray; padding-top: 10px; text-align: right">
      <a href="/imprimir_receta_cirugia/{{$recetas[0]->id}}/{{$recetas[0]->id_farmacia}}"
        class="btn btn-default btn-xs pull-right" target="_blank">
        <i class="fa fa-print"></i> Imprimir receta
      </a>
    </div>
    <div class="col-md-4" style="border-top: 1px solid gray; padding-top: 10px">
      <button class="btn btn-default btn-xs pull-right" disabled=""><i class="fa fa-print"></i> Imprimir receta
        valorizado</button>
    </div>
    @endif
    @endif
  </form>
  @endif
  @else
  <div class="panel-body" style="padding-top:10px; font-size: 13px">
    No se encuentra el numero de receta que digito.
  </div>
  @endif
</div>
