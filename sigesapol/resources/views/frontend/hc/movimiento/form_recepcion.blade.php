@extends('frontend.layouts.master')

@section('rendercss')
<style>
    .hc-card {
        border: 1px solid #ddd;
        border-radius: 3px;
        padding: 8px 12px;
        margin-bottom: 15px;
        background: #fff;
    }
    .hc-card-numero { font-size: 22px; font-weight: 700; line-height: 1.1; }
    .hc-card-texto { font-size: 11px; color: #777; }
    .hc-card-alerta { border-color: #d9534f; }
    .hc-card-alerta .hc-card-numero { color: #d9534f; }
    #tabla-recepcion tbody td,
    #tabla-ingresos tbody td {
        vertical-align: middle;
    }
    
</style>
@endsection

@section('content')
<div class="container" style="width: 80%">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">INGRESO DE HISTORIAS CLÍNICAS (DEVOLUCIÓN A ARCHIVO)</h3>
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ route('hc_movimiento.guardar_recepcion') }}" id="frmRecepcion" autocomplete="off">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_movimiento_hc_solicitud" id="id_movimiento_hc_solicitud" value="">
                        <input type="hidden" name="fecha_hora_actual_sin_formato" value="{{ $fecha_hora_actual_sin_formato }}">
                        <input type="hidden" name="ingreso_anio" value="{{ $ingreso_anio }}">

                        {{-- Búsqueda de la salida a recepcionar --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Buscar por N° de solicitud, N° de HC o DNI <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-sm" name="buscar_salida" id="buscar_salida" value="{{ old('buscar_salida') }}">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-info input-sm form-control" id="btnBuscarSalida">Buscar</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>N° Ingreso</label>
                                    <input type="text" class="form-control input-sm" id="nro_ingreso" value="{{ $ingreso_nro }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Fecha y hora de ingreso</label>
                                    <input type="text" class="form-control input-sm" id="fecha_hora_ingreso" value="{{ $fecha_hora_actual }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Recibido por (Archivo)</label>
                                    <input type="text" class="form-control input-sm" id="recibido_por" value="{{ $usuario_nombre_completo }}" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- Datos de la salida --}}
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <strong>DATOS DE LA SALIDA</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>N° Solicitud</label>
                                            <input type="text" class="form-control input-sm" id="salida_solicitud" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Servicio que devuelve</label>
                                            <input type="text" class="form-control input-sm" id="salida_servicio" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Solicitado por</label>
                                            <input type="text" class="form-control input-sm" id="salida_solicitante" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Motivo</label>
                                            <input type="text" class="form-control input-sm" id="salida_motivo" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Fecha de salida</label>
                                            <input type="text" class="form-control input-sm" id="salida_fecha" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Retorno previsto</label>
                                            <input type="text" class="form-control input-sm" id="salida_retorno" value="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Persona que entrega <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control input-sm" name="persona_entrega" id="persona_entrega" maxlength="150" value="{{ old('persona_entrega') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Documento de devolución <span class="text-danger">*</span></label>
                                            <select class="form-control input-sm" name="id_tipo_documento" id="id_tipo_documento" required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($tipos_documento_devolucion as $tipo_documento)
                                                    <option value="{{ $tipo_documento->id }}" {{ old('id_tipo_documento') == $tipo_documento->id ? 'selected' : '' }}>{{ $tipo_documento->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>N° de cargo / oficio <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control input-sm" name="nro_documento" id="nro_documento" maxlength="50" value="{{ old('nro_documento') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Observación general</label>
                                            <input type="text" class="form-control input-sm" name="observacion" id="observacion" maxlength="200" value="{{ old('observacion') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Tarjetas de cantidades --}}
                                <div class="row" style="display:none;">
                                    <div class="col-md-3">
                                        <div class="hc-card">
                                            <div class="hc-card-numero">&mdash;</div>
                                            <div class="hc-card-texto">Despachadas</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="hc-card">
                                            <div class="hc-card-numero">&mdash;</div>
                                            <div class="hc-card-texto">Devueltas en este ingreso</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="hc-card">
                                            <div class="hc-card-numero">&mdash;</div>
                                            <div class="hc-card-texto">Devueltas antes</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="hc-card hc-card-alerta">
                                            <div class="hc-card-numero">&mdash;</div>
                                            <div class="hc-card-texto">Siguen fuera</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Historias de la salida --}}
                                <div class="table-responsive">
                                    <table id="tabla-recepcion" class="table table-bordered table-hover" style="font-size:12px">
                                        <thead>
                                            <tr>
                                                <th style="width:90px">
                                                    <label style="margin:0; font-weight:700">
                                                        <input type="checkbox" id="chk_todas"> Devuelta
                                                    </label>
                                                </th>
                                                <th>N° HC</th>
                                                <th>DNI</th>
                                                <th>Paciente</th>
                                                <th>Fecha de salida</th>
                                                <th>Retorno previsto</th>
                                                <th>Días fuera</th>
                                                <th>Cumplimiento</th>
                                                <!-- <th>Folios / estado de la carpeta</th> -->
                                                <th>Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="fila-sin-historias">
                                                <td colspan="10" class="text-center text-muted">Busque una salida para ver las historias despachadas.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row" style="margin-top:15px">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-success form-control" id="btnRegistrarIngreso">Registrar ingreso</button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('hc_movimiento.lista_solicitudes') }}" id="btnCancelarRecepcion" class="btn btn-danger form-control">Cancelar</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>

                    {{-- Ingresos registrados --}}
                    <div class="panel panel-success" id="panel-ingresos" style="display:none">
                        <div class="panel-heading">
                            <strong>INGRESOS REGISTRADOS</strong>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha desde</label>
                                        <input type="text" class="form-control input-sm datepicker" name="ingresos_desde" id="ingresos_desde" value="" autocomplete="off" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha hasta</label>
                                        <input type="text" class="form-control input-sm datepicker" name="ingresos_hasta" id="ingresos_hasta" value="" autocomplete="off" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Servicio</label>
                                        <select class="form-control input-sm" name="ingresos_servicio" id="ingresos_servicio">
                                            <option value="">TODOS</option>
                                            @foreach ($servicios as $servicio)
                                                <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-info input-sm form-control" id="btnBuscarIngresos">Buscar</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tabla-ingresos" class="table table-bordered table-hover" style="font-size:12px">
                                    <thead>
                                        <tr>
                                            <th>N° Ingreso</th>
                                            <th>Fecha y hora</th>
                                            <th>N° Orden</th>
                                            <th>Servicio</th>
                                            <th>Entregó</th>
                                            <th>Recibió</th>
                                            <th>Despachadas</th>
                                            <th>Devueltas</th>
                                            <th>Pendientes</th>
                                            <th>Cargo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">Busque una solicitud para ver sus ingresos.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection

@section('renderjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datepicker();

        var urlBuscarSalida = "{{ route('hc_movimiento.buscar_salida') }}";
        var urlListarIngresos = "{{ route('hc_movimiento.listar_ingresos') }}";

        function mensajeTabla(texto) {
            $('#tabla-recepcion tbody').html('<tr id="fila-sin-historias"><td colspan="10" class="text-center text-muted"></td></tr>');
            $('#fila-sin-historias td').text(texto);
        }

        function pintarIngresos(ingresos) {
            var tbody = $('#tabla-ingresos tbody').empty();
            if (!ingresos || ingresos.length === 0) {
                tbody.append('<tr><td colspan="10" class="text-center text-muted">La solicitud aún no tiene ingresos registrados.</td></tr>');
                return;
            }
            $.each(ingresos, function(i, g) {
                var fila = $('<tr>');
                fila.append($('<td>').text(g.nro_ingreso));
                fila.append($('<td>').text(g.fecha));
                fila.append($('<td>').text(g.solicitud));
                fila.append($('<td>').text(g.servicio));
                fila.append($('<td>').text(g.entrego));
                fila.append($('<td>').text(g.recibio));
                fila.append($('<td>').text(g.despachadas));
                fila.append($('<td>').text(g.devueltas));
                fila.append($('<td>').text(g.pendientes));
                fila.append($('<td>').text(g.cargo));
                tbody.append(fila);
            });
        }

        function limpiarIngresos() {
            $('#panel-ingresos').hide();
            $('#tabla-ingresos tbody').html('<tr><td colspan="10" class="text-center text-muted">Busque una solicitud para ver sus ingresos.</td></tr>');
        }

        function limpiarSalida() {
            limpiarIngresos();
            $('#id_movimiento_hc_solicitud, #salida_solicitud, #salida_servicio, #salida_solicitante, #salida_motivo, #salida_fecha, #salida_retorno').val('');
            $('#chk_todas').prop('checked', false);
        }

        // Observación "No devuelta" en rojo cuando la HC no está marcada
        function actualizarObservacion(fila) {
            var td = fila.find('.celda-observacion');
            if (fila.find('.chk-devuelta').is(':checked')) {
                td.empty();
            } else {
                td.html('<span class="text-danger">No devuelta</span>');
            }
        }

        function pintarHistorias(historias) {
            var tbody = $('#tabla-recepcion tbody').empty();

            $.each(historias, function(i, h) {
                var fila = $('<tr>');
                fila.append($('<td class="text-center">').append(
                    $('<input type="checkbox" class="chk-devuelta" name="historias[]">').val(h.id)
                ));
                fila.append($('<td>').append($('<strong>').text(h.nro_hc)));
                fila.append($('<td>').text(h.dni));
                fila.append($('<td>').text(h.paciente));
                fila.append($('<td>').text(h.fecha_salida));
                fila.append($('<td>'));
                fila.append($('<td>').text(h.dias_fuera));
                fila.append($('<td>').append($('<span class="label label-success">').text('A TIEMPO')));
                // fila.append($('<td class="text-muted">').html('&mdash;'));
                fila.append($('<td class="celda-observacion">'));
                tbody.append(fila);
                actualizarObservacion(fila);
            });

            if (historias.length === 0) {
                mensajeTabla('La solicitud no tiene historias asociadas.');
            }
        }

        function buscarSalida() {
            var termino = $.trim($('#buscar_salida').val());
            if (termino.length < 3) {
                limpiarSalida();
                mensajeTabla('Ingrese al menos 3 caracteres.');
                return;
            }

            $.get(urlBuscarSalida, { q: termino }, function(r) {
                if (!r.ok) {
                    limpiarSalida();
                    mensajeTabla(r.mensaje);
                    bootbox.alert(r.mensaje);
                    return;
                }
                $('#id_movimiento_hc_solicitud').val(r.solicitud.id);
                $('#salida_solicitud').val(r.solicitud.nro);
                $('#salida_servicio').val(r.solicitud.servicio);
                $('#salida_solicitante').val(r.solicitud.solicitante);
                $('#salida_motivo').val(r.solicitud.motivo);
                $('#salida_fecha').val(r.solicitud.fecha_salida);
                $('#salida_retorno').val('');
                $('#chk_todas').prop('checked', false);
                pintarHistorias(r.historias);
                pintarIngresos(r.ingresos);
                $('#panel-ingresos').show();
            }, 'json').fail(function() {
                limpiarSalida();
                mensajeTabla('No se pudo realizar la búsqueda.');
            });
        }

        // Filtros del panel "Ingresos registrados": fecha desde / hasta y servicio
        $('#btnBuscarIngresos').on('click', function() {
            $.get(urlListarIngresos, {
                desde: $('#ingresos_desde').val(),
                hasta: $('#ingresos_hasta').val(),
                id_servicio: $('#ingresos_servicio').val()
            }, function(r) {
                pintarIngresos(r.ingresos);
            }, 'json').fail(function() {
                $('#tabla-ingresos tbody').html('<tr><td colspan="10" class="text-center text-danger">No se pudo consultar los ingresos.</td></tr>');
            });
        });

        $('#btnBuscarSalida').on('click', buscarSalida);
        $('#buscar_salida').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                buscarSalida();
            }
        });

        // Marcar o desmarcar todas las historias de la tabla
        $('#chk_todas').on('change', function() {
            $('#tabla-recepcion tbody .chk-devuelta').prop('checked', this.checked).trigger('change');
        });

        $('#tabla-recepcion').on('change', '.chk-devuelta', function() {
            actualizarObservacion($(this).closest('tr'));
        });

        $('#frmRecepcion').on('submit', function(e) {
            var msg = '';

            if ($('#id_movimiento_hc_solicitud').val() == '') msg += 'Debe buscar la salida a recepcionar<br>';
            if ($.trim($('#persona_entrega').val()) == '') msg += 'Debe ingresar la persona que entrega<br>';
            if ($('#id_tipo_documento').val() == '') msg += 'Debe seleccionar el documento de devolución<br>';
            if ($.trim($('#nro_documento').val()) == '') msg += 'Debe ingresar el N° de cargo / oficio<br>';
            //if ($('#tabla-recepcion .chk-devuelta:checked').length === 0) msg += 'Debe marcar al menos una historia como devuelta<br>';

            if (msg != '') {
                e.preventDefault();
                bootbox.alert(msg);
                return false;
            }

            // Confirmación antes de registrar el ingreso a Archivo
            e.preventDefault();
            var form = this;
            bootbox.confirm({
                message: '¿Desea registrar el ingreso a Archivo?',
                buttons: {
                    confirm: { label: 'Sí, registrar', className: 'btn-success' },
                    cancel: { label: 'No', className: 'btn-default' }
                },
                callback: function(ok) {
                    if (!ok) return;
                    form.submit();
                }
            });
        });

        $('#btnCancelarRecepcion').on('click', function(e) {
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

        // Al volver con errores de validación, recargar la salida buscada
        if ($.trim($('#buscar_salida').val()) !== '') {
            buscarSalida();
        }
    });
</script>
@endsection
