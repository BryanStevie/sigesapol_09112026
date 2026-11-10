<?php
  $pp_vias = [1 => 'Oral', 2 => 'Sublingual', 3 => 'Rectal', 4 => 'Intravenosa', 5 => 'Intramuscular', 6 => 'Subcutánea', 7 => 'Dérmica',
    8 => 'Nasal', 9 => 'Oftalmologica', 10 => 'Inhalatoria', 11 => 'Epidural', 12 => 'Intratecal', 13 => 'Vaginal', 14 => 'Intraarticular',
    15 => 'No Aplica', 16 => 'Otros', 17 => 'Parenteral'];
  $pp_lados = [1 => 'Izquierdo', 2 => 'Derecho', 3 => 'Ambos'];
  $pp_puede_agregar = !$modal;
  $pp_es_gastro = intval($establecimiento->id) === 76 && intval($solicitud->id_servicio) === 102;
  $pp_gastro = $ordenes_pp ? $ordenes_pp['gastro'] : null;
?>
<style>
  .pp-ver-form > fieldset > .panel:first-child { display: none; }
</style>@if($pp_puede_agregar)
@include('frontend.procedimientos programados.partials.pp_form_ocultos')
@endif

<div class="form-horizontal">

  @if(!$modal && !empty($ordenes_pp['informe']))
  <fieldset>
    <div class="panel panel-info panel-bordered panel-sm">
      <div class="panel-heading">
        <div class="panel-title text-center"><b>INFORME DEL PROCEDIMIENTO</b></div>
      </div>
      <div class="panel-body">
        @if($ordenes_pp['informe']['subido'])
          <b>ESTADO:</b> Subido
          &nbsp;&nbsp;
          @foreach($ordenes_pp['informe']['archivos'] as $pp_i => $pp_archivo)
            <a href="/pp_ver_informe/{{ $solicitud->id_solicitud }}/{{ $id_procedimiento }}/{{ $pp_archivo->id }}" target="_blank" class="btn btn-primary btn-sm" title="Subido el {{ date('d/m/Y H:i', strtotime($pp_archivo->created_at)) }}"><i class="fa fa-file-pdf-o"></i> Ver Informe{{ count($ordenes_pp['informe']['archivos']) > 1 ? ' ' . ($pp_i + 1) : '' }}</a>
          @endforeach
        @else
          <b>ESTADO:</b> <span class="text-danger">Pendiente</span> — lo sube el médico que realizó la atención desde el listado (botón <b>Subir Informe</b>).
        @endif
      </div>
    </div>
  </fieldset>
  @endif

  @if($pp_gastro)
  <fieldset>
    <div class="panel panel-info panel-bordered panel-sm">
      <div class="panel-heading">
        <div class="panel-title text-center"><b>INFORME DE GASTROENTEROLOGÍA</b></div>
      </div>
      <div class="panel-body">
        <?php $g_flag = intval($pp_gastro->flag); ?>
        <b>ESTADO:</b> {{ $g_flag === 1 ? 'Firmado digitalmente' : ($g_flag === 2 ? 'Firmado electrónicamente' : 'Sin firma') }}
        @unless($modal)
        &nbsp;&nbsp;
        @if(intval($pp_gastro->baja) == 1)
          <a href="/pp_informe_gastro_pdf/{{ $pp_gastro->id }}/baja" target="_blank" class="btn btn-sm" style="color:#fff;background-color:#572364;border-color:#42214b"><i class="fa fa-file-pdf-o"></i> Endoscopia Baja</a>
        @endif
        @if(intval($pp_gastro->alta) == 1)
          <a href="/pp_informe_gastro_pdf/{{ $pp_gastro->id }}/alta" target="_blank" class="btn btn-sm" style="color:#fff;background-color:#000080;border-color:#0a0a5c"><i class="fa fa-file-pdf-o"></i> Endoscopia Alta</a>
        @endif
        @endunless
        @include('frontend.procedimientos programados.partials.pp_gastro_ver')
      </div>
    </div>
  </fieldset>
  @elseif($pp_es_gastro && $pp_puede_agregar)
  <fieldset>
    <div class="panel panel-info panel-bordered panel-sm">
      <div class="panel-heading">
        <div class="panel-title text-center"><b>INFORME DE GASTROENTEROLOGÍA</b></div>
      </div>
      <div class="panel-body">
        <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
          <a href="javascript:void(0)" class="btn btn-success pp-btn-agregar" data-flag="0" onclick="ppVerToggle(this, '#addGastro', 'Agregar Informe de Gastroenterología', 'Ocultar Informe de Gastroenterología')">Agregar Informe de Gastroenterología</a>
        </div>
        <div style="clear:both"></div>
        <div class="pp-ver-form">@include('frontend.procedimientos programados.partials.pp_form_gastro')</div>
      </div>
    </div>
  </fieldset>
  @endif

  @if(count($ordenes_pp['recetas']) > 0 || $pp_puede_agregar)
  <fieldset>
    <div class="panel panel-info panel-bordered panel-sm">
      <div class="panel-heading">
        <div class="panel-title text-center"><b>PRODUCTOS MEDICOS</b></div>
      </div>
      <div class="panel-body">
        @foreach($ordenes_pp['recetas'] as $rv)
          @if($pp_puede_agregar)
          <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
            @if($rv->estado == "2")
              <a data-toggle="modal" data-target="#modalInvalidarRecetaPP" onclick="ppEnviarReceta({{ $rv->id }})" class="btn btn-danger"><i class="fa fa-xing"></i> Invalidar Receta</a>
            @else
              <button class="btn btn-danger" disabled="disabled" title="La receta ya fue dispensada, no se puede invalidar"><i class="fa fa-xing"></i> Invalidar Receta</button>
            @endif
          </div>
          <div style="clear:both"></div>
          @endif
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-body">
                <div class="form-group">
                  <div class="col-md-12">
                    <label class="col-md-2" style="padding-left:0px">RECETA VALE N °</label>
                    <div class="col-md-1"><p style="font-weight: normal;">{{ $rv->nro_receta }}</p></div>
                    <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                    <div class="col-md-2"><p style="font-weight: normal;">{{ $rv->fecha_expedicion ? date("d-m-Y", strtotime($rv->fecha_expedicion)) : '' }}</p></div>
                    <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                    <div class="col-md-3" style="padding:0px"><p style="font-weight: normal;">{{ $rv->farmacia }}</p></div>
                    <div class="col-md-1 text-right" style="padding:0px">
                    </div>
                  </div>
                </div>
                <table class="display" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th width="10%">Codigo</th>
                      <th width="30%">Producto farmacético o dispositivo médico</th>
                      <th width="7%">Tiempo(Dias)</th>
                      <th width="18%">Posologia</th>
                      <th width="10%">Via Administración</th>
                      <th width="8%">Cantidad Prescrita</th>
                      <th width="17%">Diagnósticos</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($rv->productos as $p)
                    <tr>
                      <td style="font-weight: normal;">{{ $p->codigo }}</td>
                      <td style="font-weight: normal;">{{ $p->nombre }}</td>
                      <td style="font-weight: normal;">{{ $p->tiempo_periodo }}</td>
                      <td style="font-weight: normal;">{{ $p->posologia }}</td>
                      <td style="font-weight: normal;">{{ isset($pp_vias[intval($p->id_via_administracion)]) ? $pp_vias[intval($p->id_via_administracion)] : '' }}</td>
                      <td style="font-weight: normal;">{{ $p->cantidad_prescrita }}</td>
                      <td style="font-weight: normal;">{{ $p->diagnostico_codigo ? $p->diagnostico_codigo . ' - ' . $p->diagnostico : '' }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </fieldset>
        @endforeach

        @if($pp_puede_agregar)
          <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
            <a href="javascript:void(0)" class="btn btn-success pp-btn-agregar" data-flag="0" onclick="ppVerToggle(this, '#addRecetaVale', 'Agregar Receta', 'Ocultar Receta')">Agregar Receta</a>
          </div>
          <div style="clear:both"></div>
          <div class="pp-ver-form">@include('frontend.procedimientos programados.partials.pp_form_receta')</div>
        @endif
      </div>
    </div>
  </fieldset>
  @endif

  @if(count($ordenes_pp['laboratorios']) > 0 || $pp_puede_agregar)
  <fieldset>
    <div class="panel panel-info panel-bordered panel-sm">
      <div class="panel-heading">
        <div class="panel-title text-center"><b>EXAMEN DE LABORATORIO</b></div>
      </div>
      <div class="panel-body">
        @foreach($ordenes_pp['laboratorios'] as $lab)
          @if($pp_puede_agregar)
          <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
            @if($lab->estado == "1" && !$lab->fecha_registro_toma_muestra)
              <a data-toggle="modal" data-target="#modalInvalidarLabPP" onclick="ppEnviarOrden('pp_id_lab_invalidar', {{ $lab->id }})" class="btn btn-danger"><i class="fa fa-xing"></i> Invalidar Examen</a>
            @else
              <button class="btn btn-danger" disabled="disabled" title="{{ $lab->estado == '1' ? 'Ya se registró la toma de muestra' : 'Orden anulada' }}"><i class="fa fa-xing"></i> Invalidar Examen</button>
            @endif
          </div>
          <div style="clear:both"></div>
          @endif
          <div class="form-group">
            <div class="col-md-12">
              <label class="col-md-2" style="padding-left:0px">N ° ORDEN</label>
              <div class="col-md-3"><p style="font-weight: normal;">{{ $solicitud->id_prestacion }} - {{ $lab->id }}</p></div>
              <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
              <div class="col-md-3"><p style="font-weight: normal;">{{ $lab->created_at ? date("d-m-Y", strtotime($lab->created_at)) : '' }}</p></div>
              <div class="col-md-2 text-right" style="padding:0px">
              </div>
            </div>
          </div>
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-body">
                <table class="display" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th width="8%">Código</th>
                      <th width="45%">Nombre</th>
                      <th width="25%">Observación</th>
                      <th width="7%">Cantidad</th>
                      <th width="15%">Diagnóstico</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($lab->examenes as $e)
                    <tr>
                      <td style="font-weight: normal;">{{ $e->codigo }}</td>
                      <td style="font-weight: normal;">{{ $e->descripcion }}</td>
                      <td style="font-weight: normal;">{{ $e->observacion }}</td>
                      <td style="font-weight: normal;">{{ $e->cantidad }}</td>
                      <td style="font-weight: normal;">{{ $e->diagnostico_codigo }}</td>
                    </tr>
                    @endforeach
                    @if(!empty($lab->observacion))
                    <tr><td colspan="5"><b>Observación:</b> <span style="font-weight: normal">{{ $lab->observacion }}</span></td></tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </fieldset>
        @endforeach

        @if($pp_puede_agregar)
          <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
            <a href="javascript:void(0)" class="btn btn-success pp-btn-agregar" data-flag="0" onclick="ppVerToggle(this, '#addExamenLab', 'Examen Laboratorio', 'Ocultar Examen Laboratorio')">Examen Laboratorio</a>
          </div>
          <div style="clear:both"></div>
          <div class="pp-ver-form">@include('frontend.procedimientos programados.partials.pp_form_lab')</div>
        @endif
      </div>
    </div>
  </fieldset>
  @endif

  @if(count($ordenes_pp['radiologias']) > 0 || $pp_puede_agregar)
  <fieldset>
    <div class="panel panel-info panel-bordered panel-sm">
      <div class="panel-heading">
        <div class="panel-title text-center"><b>EXAMEN DE IMAGENOLOGIA</b></div>
      </div>
      <div class="panel-body">
        @foreach($ordenes_pp['radiologias'] as $rad)
          @if($pp_puede_agregar)
          <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
            @if($rad->estado == "1")
              <a data-toggle="modal" data-target="#modalInvalidarRadPP" onclick="ppEnviarOrden('pp_id_rad_invalidar', {{ $rad->id }})" class="btn btn-danger"><i class="fa fa-xing"></i> Invalidar Examen</a>
            @else
              <button class="btn btn-danger" disabled="disabled" title="Orden anulada"><i class="fa fa-xing"></i> Invalidar Examen</button>
            @endif
          </div>
          <div style="clear:both"></div>
          @endif
          <div class="form-group">
            <div class="col-md-12">
              <label class="col-md-2" style="padding-left:0px">N ° ORDEN</label>
              <div class="col-md-3"><p style="font-weight: normal;">{{ $solicitud->id_prestacion }} - {{ $rad->id }}</p></div>
              <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
              <div class="col-md-3"><p style="font-weight: normal;">{{ $rad->created_at ? date("d-m-Y", strtotime($rad->created_at)) : '' }}</p></div>
              <div class="col-md-2 text-right" style="padding:0px">
              </div>
            </div>
          </div>
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-body">
                <table class="display" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th width="8%">Código</th>
                      <th width="37%">Nombre</th>
                      <th width="9%">Comparativo</th>
                      <th width="9%">Lado</th>
                      <th width="17%">Observación</th>
                      <th width="7%">Cantidad</th>
                      <th width="13%">Diagnóstico</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($rad->examenes as $e)
                    <tr>
                      <td style="font-weight: normal;">{{ $e->codigo }}</td>
                      <td style="font-weight: normal;">{{ $e->descripcion }}{{ $e->radiologia_portatil == 'SI' ? ' (PORTÁTIL)' : '' }}</td>
                      <td style="font-weight: normal;">{{ intval($e->comparativo_radio) == 1 ? 'SI' : (intval($e->comparativo_radio) == 2 ? 'NO' : '') }}</td>
                      <td style="font-weight: normal;">{{ isset($pp_lados[intval($e->lado_radio)]) ? $pp_lados[intval($e->lado_radio)] : '' }}</td>
                      <td style="font-weight: normal;">{{ $e->observacion }}</td>
                      <td style="font-weight: normal;">{{ $e->cantidad }}</td>
                      <td style="font-weight: normal;">{{ $e->diagnostico_codigo }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </fieldset>
        @endforeach

        @if($pp_puede_agregar)
          <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
            <a href="javascript:void(0)" class="btn btn-success pp-btn-agregar" data-flag="0" onclick="ppVerToggle(this, '#addExamenRad', 'Examen de Imagenología', 'Ocultar Examen de Imagenología')">Examen de Imagenología</a>
          </div>
          <div style="clear:both"></div>
          <div class="pp-ver-form">@include('frontend.procedimientos programados.partials.pp_form_rad')</div>
        @endif
      </div>
    </div>
  </fieldset>
  @endif

</div>

@if($pp_puede_agregar)

<div class="modal fade" id="modalInvalidarRecetaPP" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span></button>
        <h3 class="modal-title">Invalidar Receta</h3>
      </div>
      <div class="modal-body">
        <form method="post" action="/pp_invalidar_receta">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="id_receta" id="pp_id_receta_invalidar" value="">
          <input type="hidden" name="id_solicitud" value="{{ $solicitud->id_solicitud }}">
          <input type="hidden" name="id_procedimiento" value="{{ $id_procedimiento }}">
          <div class="form-group">
            <label>Escribir el motivo para invalidar el registro de esta receta</label>
            <input type="text" class="form-control" name="motivo" required="">
          </div>
          <div class="form-group">
            <input class="btn btn-danger" type="submit" value="Invalidar">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@foreach([['modalInvalidarLabPP', '/pp_invalidar_laboratorio', 'id_laboratorio', 'pp_id_lab_invalidar', 'Invalidar Examen de Laboratorio', 'examen de laboratorio'], ['modalInvalidarRadPP', '/pp_invalidar_imagenologia', 'id_radiologia', 'pp_id_rad_invalidar', 'Invalidar Examen Imagenologia', 'examen de imagenologia']] as $inv)
<div class="modal fade" id="{{ $inv[0] }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span></button>
        <h3 class="modal-title">{{ $inv[4] }}</h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ $inv[1] }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="{{ $inv[2] }}" id="{{ $inv[3] }}" value="">
          <input type="hidden" name="id_solicitud" value="{{ $solicitud->id_solicitud }}">
          <input type="hidden" name="id_procedimiento" value="{{ $id_procedimiento }}">
          <div class="form-group">
            <label>Escribir el motivo para invalidar el registro de este {{ $inv[5] }}</label>
            <input type="text" class="form-control" name="motivo" required="">
          </div>
          <div class="form-group">
            <input class="btn btn-danger" type="submit" value="Invalidar">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach
<script>
  function ppEnviarReceta(id) { document.getElementById('pp_id_receta_invalidar').value = id; }
  function ppEnviarOrden(campo, id) { document.getElementById(campo).value = id; }
  function ppVerToggle(btn, toggle, textoAbrir, textoCerrar) {
    if (window.PP_EDITANDO_OBS) {
      Swal.fire({ icon: 'warning', title: 'Alerta', html: 'Guarde o cancele los cambios de la <b>observación</b> antes de agregar órdenes.', showConfirmButton: true });
      return;
    }
    var abierto = $(btn).attr('data-flag') == '1';
    $(toggle).trigger('click');
    $(btn).attr('data-flag', abierto ? '0' : '1').html(abierto ? textoAbrir : textoCerrar);

    var alguno = $('.pp-btn-agregar[data-flag="1"]').length > 0;
    $('#btnVolverPP').toggle(alguno || {{ $en_atencion ? 'true' : 'false' }});
    $('#btnEditarObsPP').toggle(!alguno);
    $('#btnGuardarOrdenesPP').toggle(alguno);
    $('#btnFinalizarPP').toggle(!alguno);
  }
</script>
@endif
