@if($modal)
<div class="container-fluid pp-contenedor marca_agua_container" style="position:relative;">
  <div class="marca_agua_overlay" id="marca_agua_overlay_modal_pp"></div>
  @include('frontend.procedimientos programados.partials.pp_atencion_datos')
</div>
@else
<div class="container-fluid pp-contenedor">
  <div class="pp-encabezado">MÓDULO DE PROCEDIMIENTO PROGRAMADO @if($confirmacion) - CONFIRMACIÓN DE LA ATENCIÓN @endif</div>
  <div class="panel panel-default" style="border-top: 0;">
    <div class="panel-heading" style="background: #fff;">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tabNuevaAtencion" data-toggle="tab" style="font-size:12px">NUEVA ATENCIÓN</a></li>
        <li><a href="#tabHistAtenciones" data-toggle="tab" style="font-size:12px">HIST. ATENCIONES</a></li>
        <li><a href="#tabHistLaboratorio" data-toggle="tab" style="font-size:12px">HIST. EXÁMENES DE LABORATORIO</a></li>
        <li><a href="#tabHistRadiologia" data-toggle="tab" style="font-size:12px">HIST. EXÁMENES DE RADIOLOGÍA</a></li>
        <li><a href="#tabHistInterconsulta" data-toggle="tab" style="font-size:12px">HIST. INTERCONSULTA</a></li>
        <li><a href="#tabHistReferencias" data-toggle="tab" style="font-size:12px">HIST. REFERENCIAS / CONTRARREFERENCIAS</a></li>
        <li><a href="#tabHistProcProg" data-toggle="tab" style="font-size:12px">HIST. DE ATENCIONES P. PROGRAMADO</a></li>
      </ul>
    </div>
    <div class="panel-body">
      <div class="tab-content">
        <div class="tab-pane fade in active" id="tabNuevaAtencion">
          <div class="marca_agua_overlay" id="marca_agua_overlay_iframe_pp" style="display:none"></div>
          @include('frontend.procedimientos programados.partials.pp_atencion_datos')
        </div>

        <div class="tab-pane fade marca_agua_container" id="tabHistAtenciones" style="position:relative;">
          <div class="marca_agua_overlay" id="marca_agua_overlay_tabHistAtenciones"></div>
          <div class="pp-historial"></div>
        </div>
        <div class="tab-pane fade" id="tabHistLaboratorio"><div class="pp-historial"></div></div>
        <div class="tab-pane fade" id="tabHistRadiologia"><div class="pp-historial"></div></div>
        <div class="tab-pane fade marca_agua_container" id="tabHistInterconsulta" style="position:relative;">
          <div class="marca_agua_overlay" id="marca_agua_overlay_tabHistInterconsulta"></div>
          <div class="pp-historial"></div>
        </div>
        <div class="tab-pane fade" id="tabHistReferencias"><div class="pp-historial"></div></div>
        <div class="tab-pane fade marca_agua_container" id="tabHistProcProg" style="position:relative;">
          <div class="marca_agua_overlay" id="marca_agua_overlay_tabHistProcProg"></div>
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>HISTORIAL DE INFORMES DE PROCEDIMIENTO PROGRAMADO</b>
                </div>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-sm" id="tblHistorialInformesPP" style="width: 100% !important;">
                    <thead>
                      <tr>
                        <th>Establecimiento</th>
                        <th class="text-center">Nº de Doc (Paciente)</th>
                        <th>Servicio solicitante</th>
                        <th>Procedimiento solicitado</th>
                        <th class="text-center">Fecha de atención</th>
                        <th>Médico</th>
                        <th class="text-center">Ver atención</th>
                        <th class="text-center">Ver informe</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </div>
  </div>
</div>

@include('frontend.procedimientos programados.partials.pp_atencion_ordenes_modales')

<div class="modal fade" id="modalVerAtencionPPHist" role="dialog">
  <div class="modal-dialog modal-lg" role="document" style="width: 1200px; max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">VER ATENCIÓN DEL PROCEDIMIENTO PROGRAMADO</h5>
      </div>
      <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
        <div id="modalVerAtencionPPHistBody"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAtencionOrigen" role="dialog">
  <div class="modal-dialog modal-lg" role="document" style="width: 1100px; max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">DETALLE DE LA ATENCIÓN DE ORIGEN - ORDEN Nº {{ $solicitud->id_solicitud }}</h5>
      </div>
      <div class="modal-body marca_agua_container" style="position:relative; overflow-y: auto; max-height: 80vh;">
        <div class="marca_agua_overlay" id="marca_agua_overlay_atencion_origen"></div>
        <div id="pp-det-atencion-origen" style="position:relative; z-index:1;"></div>
      </div>
    </div>
  </div>
</div>

<div id="pp-plantilla-solicitud" style="display:none">
  <fieldset>
    <div class="panel panel-info panel-bordered panel-sm">
      <div class="panel-heading">
        <div class="panel-title text-center">
          <b>SOLICITUD DE PROCEDIMIENTO PROGRAMADO Nº {{ str_pad($solicitud->id_solicitud, 8, '0', STR_PAD_LEFT) }}</b>
        </div>
      </div>
      <div class="panel-body" style="font-size:12px">
        <div class="row">
          <div class="col-md-4"><b>FECHA DE SOLICITUD:</b> {{ $solicitud->fecha_solicitud }}</div>
          <div class="col-md-4"><b>TIPO DE ATENCIÓN:</b> {{ $solicitud->tipo_atencion }}</div>
          <div class="col-md-4"><b>SERVICIO SOLICITANTE:</b> {{ $solicitud->servicio_solicitante }}</div>
        </div>
        <div class="row" style="margin-bottom:8px">
          <div class="col-md-12"><b>MÉDICO SOLICITANTE:</b> DNI - {{ $solicitud->solicitante_dni }} / {{ $solicitud->solicitante }} - CMP: {{ $solicitud->solicitante_cmp }} - RNE: {{ $solicitud->solicitante_rne }}</div>
        </div>
        <?php $estados_pp = [1 => 'PENDIENTE', 2 => 'CITADO', 3 => 'ATENDIDO', 4 => 'ANULADO']; ?>
        <table class="display" cellspacing="0" width="100%" style="margin-top:5px;">
          <thead>
            <tr>
              <th width="9%">Codigo</th>
              <th width="38%">Procedimiento</th>
              <th width="7%">Cantidad</th>
              <th width="22%">Diagnóstico</th>
              <th width="16%">Indicación</th>
              <th width="8%">Estado</th>
            </tr>
          </thead>
          <tbody>
            @foreach($solicitud_detalle as $sd)
            <tr>
              <td>{{ $sd->codigo }}</td>
              <td>{{ $sd->descripcion }}</td>
              <td>{{ $sd->cantidad ? $sd->cantidad : 1 }}</td>
              <td>{{ $sd->diagnostico }}</td>
              <td>{{ $sd->indicacion }}</td>
              <td>{{ isset($estados_pp[$sd->estado]) ? $estados_pp[$sd->estado] : $sd->estado }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </fieldset>
</div>
@endif
