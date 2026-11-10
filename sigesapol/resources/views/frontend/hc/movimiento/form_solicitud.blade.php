@extends('frontend.layouts.master')

@section('rendercss')
<style>
    #tabla-historias tbody td {
        vertical-align: middle;
    }
    .hc-quitar-fila {
        cursor: pointer;
        color: #d9534f;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<?php
$editando = !empty($movimiento);
$val = function ($campo) use ($movimiento) {
    return ($movimiento && isset($movimiento->$campo)) ? $movimiento->$campo : '';
};
?>
<div class="container" style="width: 80%">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $editando ? 'EDITAR' : 'REGISTRAR' }} SOLICITUD DE HISTORIAS CLÍNICAS</h3>
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ $editando ? route('hc_movimiento.actualizar_solicitud', $movimiento->id) : route('hc_movimiento.guardar_solicitud') }}" id="frmSolicitud" autocomplete="off" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @if (!$editando)
                        <input type="hidden" name="id_establecimiento" value="{{ $user_id_establecimiento }}">
                        <input type="hidden" name="fecha_hora_actual_sin_formato" value="{{ $fecha_hora_actual_sin_formato }}">
                        <input type="hidden" name="fecha_actual" value="{{ $fecha_actual }}">
                        <input type="hidden" name="movimiento_anio" value="{{ $movimiento_anio }}">
                        @endif

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>N° Solicitud</label>
                                    <input type="text" class="form-control input-sm" value="{{ $solicitud_nro }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha y hora de registro</label>
                                    <input type="text" class="form-control input-sm" value="{{ $fecha_hora_actual }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Registrado por</label>
                                    <input type="text" class="form-control input-sm" value="{{ $usuario_nombre_completo }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <strong>1. SOLICITANTE Y SUSTENTO</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Servicio / departamento solicitante <span class="text-danger">*</span></label>
                                            <select class="form-control input-sm" name="id_servicio_solicitante" id="id_servicio_solicitante" style="width: 100%;" required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($servicios as $servicio)
                                                    <option value="{{ $servicio->id }}" {{ (string) old('id_servicio_solicitante', $val('id_servicio')) === (string) $servicio->id ? 'selected' : '' }}>{{ $servicio->nombre }}</option>
                                                @endforeach
                                                <option value="0" {{ (string) old('id_servicio_solicitante', $val('id_servicio')) === '0' ? 'selected' : '' }}>OTROS</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="grupo_servicio_otro" style="display: none;">
                                            <label>Especifique el servicio solicitante <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control input-sm" name="servicio_otro" id="servicio_otro" maxlength="50" value="{{ old('servicio_otro', $val('servicio_otro')) }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Persona que solicita <span class="text-danger">*</span></label>
                                            <select class="form-control input-sm" name="id_user_responsable" id="persona_solicita_sel" style="width: 100%;" data-valor="{{ old('id_user_responsable', $val('id_user_responsable')) }}" data-texto="{{ $val('responsable') }}" required>
                                                <option value="">Seleccione...</option>
                                            </select>
                                            <input type="text" class="form-control input-sm" name="persona_solicita" id="persona_solicita" maxlength="150" value="{{ old('persona_solicita', $val('id_user_responsable') ? '' : $val('responsable')) }}" style="display: none;" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Canal de ingreso <span class="text-danger">*</span></label>
                                            <select class="form-control input-sm" name="id_canal_ingreso" id="id_canal_ingreso" required>
                                                <option value="">Seleccione...</option>
                                                @foreach($canales_ingreso as $canal_ingreso)
                                                    <option value="{{ $canal_ingreso->id }}" {{ (string) old('id_canal_ingreso', $val('id_canal_ingreso')) === (string) $canal_ingreso->id ? 'selected' : '' }}>{{ $canal_ingreso->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Motivo <span class="text-danger">*</span></label>
                                            <select class="form-control input-sm" name="id_motivo" id="id_motivo" required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($motivos as $motivo)
                                                    <option value="{{ $motivo->id }}" {{ (string) old('id_motivo', $val('id_motivo')) === (string) $motivo->id ? 'selected' : '' }}>{{ $motivo->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tipo de documento <span class="text-danger">*</span></label>
                                            <select class="form-control input-sm" name="id_tipo_documento" id="id_tipo_documento" required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($tipo_documentos as $tipo_documento)
                                                    <option value="{{ $tipo_documento->id }}" {{ (string) old('id_tipo_documento', $val('id_documento')) === (string) $tipo_documento->id ? 'selected' : '' }}>{{ $tipo_documento->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>N° de documento <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control input-sm" name="nro_documento" id="nro_documento" placeholder="" value="{{ old('nro_documento', $val('nro_documento')) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Fecha del documento <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control input-sm datepicker" name="fecha_documento" id="fecha_documento" data-date-end-date="0d" value="{{ old('fecha_documento', $editando && $movimiento->fecha_documento ? \Carbon\Carbon::parse($movimiento->fecha_documento)->format('d-m-Y') : \Carbon\Carbon::now()->format('d-m-Y')) }}" autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Cant. HC <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control input-sm" name="cantidad_hc" id="cantidad_hc" min="1" step="1" value="{{ old('cantidad_hc', $val('cantidad_hc')) }}" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Adjuntar documento (PDF)</label>
                                            <input type="file" class="form-control input-sm" name="documento_adjunto" id="documento_adjunto" accept="application/pdf">
                                            @if ($editando && $val('documento_ruta'))
                                                <a href="{{ route('hc_movimiento.descargar_documento', $movimiento->id) }}" target="_blank" id="documento_actual" data-tiene="1">
                                                    <span class="glyphicon glyphicon-file"></span> Ver documento actual
                                                </a>
                                                <small class="text-muted">Suba otro PDF solo si desea sustituirlo.</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Lugar de archivo</label>
                                            <select class="form-control input-sm" name="id_lugar_archivo" id="id_lugar_archivo">
                                                <option value="">Seleccione...</option>
                                                @foreach ($lugares_archivo as $lugar_archivo)
                                                    <option value="{{ $lugar_archivo->id }}" {{ (string) old('id_lugar_archivo', $val('id_lugar_archivo')) === (string) $lugar_archivo->id ? 'selected' : '' }}>{{ $lugar_archivo->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Fecha en que se requiere</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control datepicker" name="fecha_requerida" id="fecha_requerida" data-date-start-date="0d" value="{{ old('fecha_requerida', $editando && $movimiento->fecha_requerida ? \Carbon\Carbon::parse($movimiento->fecha_requerida)->format('d-m-Y') : '') }}" autocomplete="off" readonly>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default" id="btnLimpiarFechaRequerida" title="Quitar fecha"><span class="glyphicon glyphicon-remove"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Prioridad</label>
                                            <select class="form-control input-sm" name="id_prioridad" id="id_prioridad">
                                                <option value="">Seleccione...</option>
                                                @foreach ($prioridades as $prioridad)
                                                    <option value="{{ $prioridad->id }}" {{ (string) old('id_prioridad', $val('id_prioridad')) === (string) $prioridad->id ? 'selected' : '' }}>{{ $prioridad->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Fecha retorno prevista <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control input-sm datepicker" name="fecha_retorno_prevista" id="fecha_retorno_prevista" data-date-start-date="0d" value="{{ old('fecha_retorno_prevista', $editando && $movimiento->fecha_retorno_prevista ? \Carbon\Carbon::parse($movimiento->fecha_retorno_prevista)->format('d-m-Y') : '') }}" autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Observación</label>
                                            <input type="text" class="form-control input-sm" name="observacion" id="observacion" value="{{ old('observacion', $val('observacion')) }}">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <strong>2. HISTORIAS SOLICITADAS</strong>
                            </div>
                            <div class="panel-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab-buscar" aria-controls="tab-buscar" role="tab" data-toggle="tab">Buscar historia</a>
                                    </li>
                                    <!-- <li role="presentation">
                                        <a href="#tab-citadas" aria-controls="tab-citadas" role="tab" data-toggle="tab">Historias citadas (consulta del día)</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#tab-lista" aria-controls="tab-lista" role="tab" data-toggle="tab">Cargar lista (N° HC / DNI)</a>
                                    </li> -->
                                </ul>

                                <div class="tab-content" style="padding-top:15px">
                                    <div role="tabpanel" class="tab-pane active" id="tab-buscar">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label class="help-block">Buscar por N° de HC, DNI o apellidos</label>
                                                    <input type="text" class="form-control input-sm" id="buscar_historia" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="help-block">&nbsp;</label>
                                                    <button type="button" class="btn btn-info input-sm form-control" onclick="buscarHistorias()">Buscar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div role="tabpanel" class="tab-pane" id="tab-citadas">
                                        <p class="help-block">Seleccione las historias de los pacientes citados el día de hoy para el servicio solicitante.</p>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="tab-lista">
                                        <div class="form-group">
                                            <label class="help-block">Pegue una lista de N° de HC o DNI (uno por línea)</label>
                                            <textarea class="form-control input-sm" id="lista_hc_dni" rows="4" placeholder="00341250&#10;00298871&#10;..."></textarea>
                                        </div>
                                        <button type="button" class="btn btn-default" onclick="cargarListaHc()">Cargar lista</button>
                                    </div> -->
                                </div>

                                <div class="table-responsive" style="margin-top:15px">
                                    <table id="tabla-historias" class="table table-bordered" style="font-size:12px">
                                        <thead>
                                            <tr>
                                                <th style="width:36px">#</th>
                                                <th>N° HC</th>
                                                <th>DNI</th>
                                                <th>Paciente</th>
                                                <th>Tipo de historia</th>
                                                <th>Lugar de archivo</th>
                                                <th>Situación actual</th>
                                                <th>Fecha en que se requiere</th>
                                                <th style="width:60px">Quitar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($historiasSeleccionadas as $i => $h)
                                            <tr data-numero-hc="{{ $h['nro_hc'] }}" data-id-historia="{{ $h['id'] }}">
                                                <td>{{ $i + 1 }}</td>
                                                <td><strong>{{ $h['nro_hc'] }}</strong></td>
                                                <td>{{ $h['dni'] }}</td>
                                                <td>{{ $h['paciente'] }}</td>
                                                <td class="text-muted">&mdash;</td>
                                                <td class="text-muted">&mdash;</td>
                                                <td class="text-muted">&mdash;</td>
                                                <td class="text-muted">&mdash;</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-xs btn-danger" title="Quitar" onclick="quitarHistoria(this)">&times;</button>
                                                    <input type="hidden" name="historias[]" value="{{ $h['id'] }}">
                                                </td>
                                            </tr>
                                            @empty
                                            <tr id="fila-sin-historias">
                                                <td colspan="9" class="text-center text-muted">Aún no ha agregado historias clínicas a la solicitud.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <p class="help-block">
                                    <strong>Historias agregadas: <span id="total-historias"> ## de ##</span></strong>
                                </p>

                                <div class="form-group">
                                    <label>Observación de Archivo</label>
                                    <input type="text" class="form-control input-sm" name="observacion_archivo" id="observacion_archivo" maxlength="500" value="{{ old('observacion_archivo', $val('observacion_archivo')) }}">
                                </div>
                            </div>
                        </div>

                        <!-- <p class="help-block">La registra la secretaria del servicio, el área de informes (expediente de Mesa de Partes) o Archivo (consulta del día desde «Historias citadas»). * obligatorio. El plazo y la fecha de retorno salen del catálogo de motivos.</p> -->

                        <hr style="clear:both">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button type="submit" name="accion" value="enviar" class="btn btn-success form-control">ENVIAR SOLICITUD</button>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="accion" value="borrador" class="btn btn-info form-control">GUARDAR</button>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('hc_movimiento.lista_solicitudes') }}" id="btnCancelarSolicitud" class="btn btn-danger form-control">CANCELAR</a>
                                </div>
                                
                                
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalBuscarHistorias" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Resultados de la búsqueda: <small id="modal-termino" style="color: white;"></small></h4>
            </div>
            <div class="modal-body">
                <div id="modal-aviso" class="alert alert-warning" style="display:none"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" style="font-size:12px">
                        <thead>
                            <tr>
                                <th>N° HC</th>
                                <th>Tipo doc.</th>
                                <th>N° documento</th>
                                <th>Paciente</th>
                                <th style="width:90px">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="modal-resultados"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Listo</button>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection

@section('renderjs')
<script type="text/javascript">
    
    function toggleServicioOtro() {
        var esOtros = $('#id_servicio_solicitante').val() === '0';
        $('#grupo_servicio_otro').toggle(esOtros);
        $('#servicio_otro').prop('disabled', !esOtros);
        if (!esOtros) $('#servicio_otro').val('');
        cargarPersonasServicio(esOtros);
    }

    // "Persona que solicita": select con los usuarios del servicio, o input manual si es OTROS
    function cargarPersonasServicio(esOtros) {
        var idServicio = $('#id_servicio_solicitante').val();
        var $sel = $('#persona_solicita_sel');
        var $txt = $('#persona_solicita');
        var valorActual = String($sel.data('valor') || '');
        var textoActual = $sel.data('texto') || '';

        $sel.data('valor', '');
        $sel.find('option:not([value=""])').remove();

        if (esOtros) {
            $sel.hide().prop('disabled', true).next('.select2-container').hide();
            $txt.show().prop('disabled', false);
            return;
        }

        $txt.hide().prop('disabled', true);
        $sel.show().prop('disabled', false).next('.select2-container').show();
        $sel.val('').trigger('change.select2');

        if (idServicio === '' || idServicio === null) return;

        $.getJSON("{{ route('hc_movimiento.usuarios_servicio') }}", { id_servicio: idServicio })
            .done(function(r) {
                var encontrado = false;
                $.each(r.usuarios || [], function(i, u) {
                    $sel.append($('<option>').val(u.id).text(u.nombre));
                    if (String(u.id) === valorActual) encontrado = true;
                });
                // Al editar, conserva el solicitante guardado aunque ya no figure en el servicio
                if (valorActual !== '' && !encontrado) $sel.append($('<option>').val(valorActual).text(textoActual));
                $sel.val(valorActual).trigger('change.select2');
            })
            .fail(function() {
                bootbox.alert('No se pudo cargar el personal del servicio. Intente nuevamente.');
            });
    }

    $(document).ready(function() {
        $('.datepicker').datepicker();
        actualizarTotales();

        $('#cantidad_hc').on('keydown', function(e) {
            if (['-', '+', '.', ',', 'e', 'E'].indexOf(e.key) !== -1) e.preventDefault();
        }).on('blur', function() {
            var n = parseInt($(this).val(), 10);
            if ($(this).val() !== '' && (isNaN(n) || n < 1)) $(this).val('');
        });

        $('#id_servicio_solicitante').select2();
        $('#persona_solicita_sel').select2();
        $('#id_servicio_solicitante').on('change', toggleServicioOtro);
        toggleServicioOtro();

        $('#buscar_historia').on('keydown', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                buscarHistorias();
            }
        });

        $('#id_motivo').on('change', function() {
            var plazo = $(this).find(':selected').data('plazo');
            if (plazo) {
                $('#plazo_motivo').val(plazo + ' días');
                recalcularFechaRetorno();
            } else {
                $('#plazo_motivo').val('—');
                $('#fecha_retorno_prevista').val('');
            }
        });

        $('#fecha_requerida').on('change', recalcularFechaRetorno);

        // La fecha requerida es opcional: el botón la deja vacía
        $('#btnLimpiarFechaRequerida').on('click', function() {
            $('#fecha_requerida').val('').trigger('change');
        });

        $('#frmSolicitud').on('submit', function(e) {
            var msg = '';

            if ($('#id_servicio_solicitante').val() == '') msg += 'Debe seleccionar el servicio / departamento solicitante.<br>';
            if ($('#id_servicio_solicitante').val() === '0' && $.trim($('#servicio_otro').val()) == '') msg += 'Debe especificar el servicio solicitante.<br>';
            var esOtros = $('#id_servicio_solicitante').val() === '0';
            var persona = esOtros ? $('#persona_solicita').val() : $('#persona_solicita_sel').val();
            if ($.trim(persona) == '') msg += esOtros ? 'Debe ingresar la persona que solicita.<br>' : 'Debe seleccionar la persona que solicita.<br>';
            if ($('#nro_documento').val() == '') msg += 'Debe ingresar el N° de documento sustentatorio.<br>';
            if ($('#fecha_retorno_prevista').val() == '') msg += 'Debe ingresar la fecha de retorno prevista.<br>';
            if ($('#tabla-historias tbody tr[data-id-historia]').length === 0) msg += 'Debe agregar al menos una Historia Clínica a la solicitud.<br>';

            if ($('#documento_adjunto').val() != '') {
                var archivoPdf = $('#documento_adjunto')[0].files[0];
                if (archivoPdf && archivoPdf.size > 5 * 1024 * 1024) msg += 'El PDF no debe superar los 5 MB<br>';
            }

            // La cantidad indicada debe coincidir con las HC seleccionadas
            var cantidadIndicada = parseInt($('#cantidad_hc').val(), 10);
            if (isNaN(cantidadIndicada) || cantidadIndicada < 1) msg += 'La cantidad de HC debe ser un número entero mayor a 0.<br>';
            var cantidadSeleccionada = $('#tabla-historias tbody tr[data-id-historia]').length;
            if (!isNaN(cantidadIndicada)) {
                if (cantidadSeleccionada > 0) {
                    if (cantidadSeleccionada > cantidadIndicada) {
                        msg += 'Las historias seleccionadas (' + cantidadSeleccionada + ') superan la cantidad indicada (' + cantidadIndicada + '). Favor de verificar.<br>';
                    } else if (cantidadSeleccionada < cantidadIndicada && $.trim($('#observacion_archivo').val()) == '') {
                        msg += 'Las historias seleccionadas (' + cantidadSeleccionada + ') son menos que la cantidad indicada (' + cantidadIndicada + '). Complete el campo <b>observación de Archivo</b>.<br>';
                    }
                }
            }

            if (msg != '') {
                e.preventDefault();
                bootbox.alert(msg);
                return false;
            }

            // Confirmación antes de registrar (borrador o envío)
            e.preventDefault();
            var form = this;
            var enviar = accionSolicitud === 'enviar';
            bootbox.confirm({
                message: enviar
                    ? '¿Desea ENVIAR la solicitud?'
                    : '¿Desea guardar la solicitud?',
                buttons: {
                    confirm: { label: 'Sí, continuar', className: 'btn-success' },
                    cancel: { label: 'No', className: 'btn-default' }
                },
                callback: function(ok) {
                    if (!ok) return;
                    $('<input type="hidden" name="accion">').val(accionSolicitud).appendTo(form);
                    $(form).find('button[type=submit]').prop('disabled', true);
                    form.submit();
                }
            });
        });

        // Botón pulsado (enviar / borrador): se pierde al reenviar el formulario tras confirmar
        $('#frmSolicitud').on('click', 'button[name=accion]', function() {
            accionSolicitud = $(this).val();
        });

        $('#btnCancelarSolicitud').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            bootbox.confirm({
                message: '¿Desea cancelar? Se perderán los datos no guardados.',
                buttons: {
                    confirm: { label: 'Sí, cancelar', className: 'btn-danger' },
                    cancel: { label: 'No', className: 'btn-default' }
                },
                callback: function(ok) {
                    if (ok) window.location.href = url;
                }
            });
        });
    });

    var accionSolicitud = 'borrador';

    function recalcularFechaRetorno() {
        var plazoTexto = $('#id_motivo').find(':selected').data('plazo');
        var fechaRequerida = $('#fecha_requerida').val();
        if (!fechaRequerida) {
            $('#fecha_retorno_prevista').val('');
            return;
        }
        if (!plazoTexto) return;

        var partes = fechaRequerida.split('-');
        var fecha = new Date(partes[2], partes[1] - 1, partes[0]);
        fecha.setDate(fecha.getDate() + parseInt(plazoTexto, 10));

        var dd = String(fecha.getDate()).padStart(2, '0');
        var mm = String(fecha.getMonth() + 1).padStart(2, '0');
        var yyyy = fecha.getFullYear();
        $('#fecha_retorno_prevista').val(dd + '-' + mm + '-' + yyyy);
    }

    var historiasEncontradas = [];

    function escapeHtml(texto) {
        return $('<div>').text(texto === null || texto === undefined ? '' : texto).html();
    }

    function buscarHistorias() {
        var termino = $.trim($('#buscar_historia').val());
        if (termino.length < 3) {
            bootbox.alert('Ingrese al menos 3 caracteres (N° de HC, DNI o apellidos).');
            return;
        }

        $.getJSON("{{ route('hc_movimiento.buscar_historias') }}", { q: termino, id_solicitud: "{{ isset($movimiento) ? $movimiento->id : '' }}" })
            .done(function(resp) {
                historiasEncontradas = resp.historias || [];
                pintarResultados(termino, resp);
                $('#modalBuscarHistorias').modal('show');
            })
            .fail(function() {
                bootbox.alert('No se pudo realizar la búsqueda. Intente nuevamente.');
            });
    }

    function pintarResultados(termino, resp) {
        var $tbody = $('#modal-resultados').empty();
        $('#modal-termino').text(termino);

        if (resp.excede) {
            $('#modal-aviso').text('Se muestran solo los primeros ' + historiasEncontradas.length + ' resultados. Refine la búsqueda.').show();
        } else {
            $('#modal-aviso').hide();
        }

        if (historiasEncontradas.length === 0) {
            $tbody.append('<tr><td colspan="5" class="text-center text-muted">No se encontraron historias clínicas.</td></tr>');
            return;
        }

        $.each(historiasEncontradas, function(i, h) {
            var yaAgregada = $('#tabla-historias tbody tr[data-id-historia="' + h.id + '"]').length > 0;
            var boton = yaAgregada
                ? '<button type="button" class="btn btn-default btn-xs" disabled>Ya agregada</button>'
                : h.estado_hc == 1
                ? '<span class="label label-warning">Bloqueada</span>'
                : h.estado_hc == 2
                ? '<span class="label label-primary">En el Servicio</span>'
                : '<button type="button" class="btn btn-success btn-xs" onclick="agregarHistoria(' + i + ', this)">Agregar</button>';

            $tbody.append(
                '<tr>' +
                '<td><strong>' + escapeHtml(h.nro_hc) + '</strong></td>' +
                '<td>' + escapeHtml(h.tipo_doc) + '</td>' +
                '<td>' + escapeHtml(h.dni) + '</td>' +
                '<td>' + escapeHtml(h.paciente) + '</td>' +
                '<td class="text-center">' + boton + '</td>' +
                '</tr>'
            );
        });
    }

    function agregarHistoria(indice, boton) {
        var h = historiasEncontradas[indice];
        if (!h || $('#tabla-historias tbody tr[data-id-historia="' + h.id + '"]').length > 0) return;

        $('#fila-sin-historias').remove();

        var n = $('#tabla-historias tbody tr[data-id-historia]').length + 1;
        $('#tabla-historias tbody').append(
            '<tr data-numero-hc="' + escapeHtml(h.nro_hc) + '" data-id-historia="' + h.id + '">' +
            '<td>' + n + '</td>' +
            '<td><strong>' + escapeHtml(h.nro_hc) + '</strong></td>' +
            '<td>' + escapeHtml(h.dni) + '</td>' +
            '<td>' + escapeHtml(h.paciente) + '</td>' +
            '<td class="text-muted">&mdash;</td>' +
            '<td class="text-muted">&mdash;</td>' +
            '<td class="text-muted">&mdash;</td>' +
            '<td class="text-muted">&mdash;</td>' +
            '<td class="text-center">' +
                '<button type="button" class="btn btn-xs btn-danger" title="Quitar" onclick="quitarHistoria(this)">&times;</button>' +
                '<input type="hidden" name="historias[]" value="' + h.id + '">' +
            '</td>' +
            '</tr>'
        );

        $(boton).replaceWith('<button type="button" class="btn btn-default btn-xs" disabled>Ya agregada</button>');
        actualizarTotales();
    }

    function cargarListaHc() {
        var lista = $('#lista_hc_dni').val();
        if (lista.trim() == '') {
            bootbox.alert('Ingrese al menos un N° de HC o DNI.');
            return;
        }
        // TODO: reemplazar por AJAX real que valide y agregue cada HC/DNI de la lista
        Swal.fire({
            icon: 'info',
            title: 'Carga simulada',
            text: 'Se procesaría la lista ingresada y se agregarían las historias encontradas.',
            timer: 1800,
            showConfirmButton: false
        });
    }

    function quitarHistoria(el) {
        $(el).closest('tr').remove();
        actualizarTotales();
    }

    function actualizarTotales() {
        var $filas = $('#tabla-historias tbody tr[data-id-historia]');
        var cantidad = $('#cantidad_hc').val();
        $('#total-historias').text($filas.length + ' de ' + (cantidad ? cantidad : '—'));
        // $('#total-disponibles').text('—');
        // $('#total-espera').text('—');

        $filas.each(function(i) {
            $(this).children('td:first').text(i + 1);
        });

        if ($filas.length === 0 && $('#fila-sin-historias').length === 0) {
            $('#tabla-historias tbody').append('<tr id="fila-sin-historias"><td colspan="9" class="text-center text-muted">Aún no ha agregado historias clínicas a la solicitud.</td></tr>');
        }
    }
</script>
@endsection
