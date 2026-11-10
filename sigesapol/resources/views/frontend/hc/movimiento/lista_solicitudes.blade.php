@extends('frontend.layouts.master')

@section('rendercss')
<style>
    #tabla-solicitudes tbody td {
        vertical-align: middle;
    }
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
    #panel-record { margin-top: 15px; }
    .hc-dato { margin-bottom: 8px; font-size: 12px; }
    .hc-dato-label { display: block; font-size: 10.5px; font-weight: 700; color: #777; text-transform: uppercase; }
    .hc-dato-valor { display: block; min-height: 16px; }
    #tabla-historias-solicitud tr.hc-fila-seleccionada { background: #e8f1e4; font-weight: 700; }
    .hc-record-titulo { background: #e8f1e4; padding: 8px 12px; font-weight: 700; font-size: 12px; color: #2d6a2d; }
    .hc-record-lista { list-style: none; margin: 0; padding: 12px 0 0 6px; font-size: 12px; }
    .hc-record-lista li { position: relative; padding: 0 0 8px 18px; border-left: 2px solid #2d6a2d; margin-left: 3px; }
    .hc-record-lista li:before { content: ''; position: absolute; left: -6px; top: 3px; width: 10px; height: 10px; border-radius: 50%; background: #2d6a2d; }
    .hc-record-lista li.hc-record-pendiente { color: #999; }
</style>
@endsection

@section('content')
<div class="container" style="width: 90%">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">SEGUIMIENTO DEL MOVIMIENTO DE HISTORIAS CLÍNICAS</h3>
                </div>
                <div class="panel-body">

                    <form method="get" action="{{ route('hc_movimiento.lista_solicitudes') }}" autocomplete="off">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Fecha de solicitud desde</label>
                                    <input type="text" class="form-control input-sm datepicker" name="desde" value="{{ $desde }}" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Hasta</label>
                                    <input type="text" class="form-control input-sm datepicker" name="hasta" value="{{ $hasta }}" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Servicio</label>
                                    <select class="form-control input-sm" name="id_servicio" id="id_servicio" style="width: 100%;">
                                        <option value="">TODOS</option>
                                        @foreach ($servicios as $servicio)
                                            <option value="{{ $servicio->id }}" {{ (string) $id_servicio === (string) $servicio->id ? 'selected' : '' }}>{{ $servicio->nombre }}</option>
                                        @endforeach
                                        <option value="0" {{ (string) $id_servicio === '0' ? 'selected' : '' }}>OTROS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Motivo</label>
                                    <select class="form-control input-sm" name="id_motivo">
                                        <option value="">TODOS</option>
                                        @foreach ($motivos as $motivo)
                                            <option value="{{ $motivo->id }}" {{ (string) $id_motivo === (string) $motivo->id ? 'selected' : '' }}>{{ $motivo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select class="form-control input-sm" name="estado">
                                        <option value="">TODOS</option>
                                        <option value="1" {{ (string) $estado === '1' ? 'selected' : '' }}>BORRADOR</option>
                                        <option value="2" {{ (string) $estado === '2' ? 'selected' : '' }}>ENVIADA</option>
                                        <option value="3" {{ (string) $estado === '3' ? 'selected' : '' }}>RECEPCION COMPLETA</option>
                                        <option value="4" {{ (string) $estado === '4' ? 'selected' : '' }}>RECEPCION INCOMPLETA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>N° HC, DNI o N° solicitud</label>
                                    <input type="text" class="form-control input-sm" name="texto" value="{{ $texto }}">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-info input-sm form-control">Buscar</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Tarjetas de cantidades: solo diseño, aún sin datos --}}
                    <div class="row" style="display:none;">
                        <div class="col-md-3">
                            <div class="hc-card">
                                <div class="hc-card-numero">&mdash;</div>
                                <div class="hc-card-texto">Fuera de Archivo</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="hc-card hc-card-alerta">
                                <div class="hc-card-numero">&mdash;</div>
                                <div class="hc-card-texto">Vencidas</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="hc-card">
                                <div class="hc-card-numero">&mdash;</div>
                                <div class="hc-card-texto">Pendientes de despacho</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="hc-card">
                                <div class="hc-card-numero">&mdash;</div>
                                <div class="hc-card-texto">En espera</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="hc-card">
                                <div class="hc-card-numero">&mdash;</div>
                                <div class="hc-card-texto">Devueltas hoy</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tabla-solicitudes" class="table table-bordered table-hover" style="font-size:12px">
                            <thead>
                                <tr>
                                    <th>N° HC</th>
                                    <th>N° Documento</th>
                                    <th>Paciente</th>
                                    <th>N° Solicitud</th>
                                    <th>Fecha registro</th>
                                    <th>Solicitado por</th>
                                    <th>Motivo</th>
                                    <th>Documento</th>
                                    <th>Dónde está</th>
                                    <th>Fecha de salida</th>
                                    <th>Retorno previsto</th>
                                    <th>Días Fuera</th>
                                    <th>Estado</th>
                                    <th style="width:90px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($solicitudes as $s)
                                <?php
                                    $historias_fila = isset($historias_por_solicitud[$s->id]) ? $historias_por_solicitud[$s->id] : [null];
                                    $nombre_motivo = isset($nombres['motivos'][$s->id_motivo]) ? $nombres['motivos'][$s->id_motivo] : '';
                                    $nombre_documento = (isset($nombres['tipo_documentos'][$s->id_documento]) ? $nombres['tipo_documentos'][$s->id_documento] : '') . ' N° ' . $s->nro_documento;
                                    $fecha_hora_registro = $s->fecha_hora_registro ? \Carbon\Carbon::parse($s->fecha_hora_registro)->format('d-m-Y H:i') : '';
                                    $fecha_despacho = $s->fecha_hora_despacho ? \Carbon\Carbon::parse($s->fecha_hora_despacho)->format('d-m-Y H:i') : '';
                                    $fecha_retorno = $s->fecha_retorno_prevista ? \Carbon\Carbon::parse($s->fecha_retorno_prevista)->format('d-m-Y') : '';
                                    $dias_fuera = $s->fecha_hora_despacho ? \Carbon\Carbon::parse($s->fecha_hora_despacho)->startOfDay()->diffInDays(\Carbon\Carbon::now()->startOfDay()) : null;
                                ?>
                                @foreach ($historias_fila as $h)
                                <?php
                                    $nro_hc = $h ? $h->nro_historia : '';
                                    $paciente = $h ? trim($h->paterno . ' ' . $h->materno . ', ' . $h->nombre) : '';
                                    $recepcion_hc = ($h && $h->recepcion_at)
                                        ? \Carbon\Carbon::parse($h->recepcion_at)->format('d-m-Y H:i') . ' · Devuelta a Archivo · Ingreso N° ' . sprintf('%s-%s', $h->anio_ingreso, str_pad($h->nro_ingreso, 6, '0', STR_PAD_LEFT))
                                        : '';
                                ?>
                                <tr>
                                    <td><strong>{{ $nro_hc }}</strong></td>
                                    <td>{{ $h->nro_doc_ident }}</td>
                                    <td>{{ $paciente }}</td>
                                    <td><strong>SOL-{{ $s->anio }}-{{ $s->numero }}</strong></td>
                                    <td>{{ $fecha_hora_registro }}</td>
                                    <td>{{ $s->servicio_solicitante }}</td>
                                    <td>{{ $nombre_motivo }}</td>
                                    <td>{{ $nombre_documento }}</td>
                                    <td>
                                        @if ($s->estado >= 2)
                                            {{ $s->servicio_solicitante }}
                                        @else
                                            &mdash;
                                        @endif
                                    </td>
                                    <td>{!! $fecha_despacho ? $fecha_despacho : '<span class="text-muted">&mdash;</span>' !!}</td>
                                    <td>{!! $fecha_retorno ? $fecha_retorno : '<span class="text-muted">&mdash;</span>' !!}</td>
                                    <td>{!! $dias_fuera !== null ? $dias_fuera : '<span class="text-muted">&mdash;</span>' !!}</td>
                                    <td>
                                        @if ($h && $h->estado_hc == 1)
                                            <span class="label label-default">PENDIENTE DE DESPACHO</span>
                                        @elseif ($h && $h->estado_hc == 2)
                                            <span class="label label-primary">EN EL SERVICIO</span>
                                        @elseif ($h && $h->estado_hc == 3)
                                            <span class="label label-success">DEVUELTA</span>
                                        @endif
                                    </td>
                                    <td class="text-center" style="white-space:nowrap">
                                        <button type="button" class="btn btn-info btn-xs btn-ver-record" title="Ver detalle de la solicitud"
                                            data-id="{{ $s->id }}"
                                            data-nro-hc="{{ $nro_hc }}"
                                            data-paciente="{{ $paciente }}"
                                            data-solicitud="SOL-{{ $s->anio }}-{{ $s->numero }}"
                                            data-fecha_hora_registro="{{ $fecha_hora_registro }}"
                                            data-solicita="{{ $s->responsable }}"
                                            data-servicio="{{ $s->servicio_solicitante }}"
                                            data-motivo="{{ $nombre_motivo }}"
                                            data-documento="{{ trim($nombre_documento) }}"
                                            data-fecha_despacho="{{ $fecha_despacho }}"
                                            data-recepcion="{{ $recepcion_hc }}"
                                            data-usuario_despacho="{{ $s->user_paterno_despacho }} {{ $s->user_materno_despacho }}, {{ $s->user_nombre_despacho }}">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                        @if ($s->estado >= 3)
                                            <button type="button" class="btn btn-success btn-xs btn-ver-ingresos" title="Ver ingresos a Archivo"
                                                data-id="{{ $s->id }}" data-solicitud="SOL-{{ $s->anio }}-{{ $s->numero }}">
                                                <span class="glyphicon glyphicon-inbox"></span>
                                            </button>
                                        @endif
                                        @if ($s->estado == 1)
                                            <a href="{{ route('hc_movimiento.editar_solicitud', $s->id) }}" class="btn btn-warning btn-xs">Editar</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Botones solo frontend, aún sin funcionalidad --}}
                    <div style="margin-top:10px">
                        <button type="button" class="btn btn-primary btn-sm" id="btnExportarExcel">Exportar a Excel</button>
                        <button type="button" class="btn btn-danger btn-sm">Memorando múltiple de vencidas (PDF)</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
{{-- Detalle de la solicitud registrada en el formulario --}}
<div class="modal fade" id="modal-solicitud" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Solicitud <span id="det-nro"></span></h4>
            </div>
            <div class="modal-body">
                <div id="det-mensaje" class="alert alert-warning" style="display:none"></div>
                <div id="det-contenido">

                    <div class="panel panel-success">
                        <div class="panel-heading"><strong>1. DATOS DE LA SOLICITUD</strong></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Fecha de registro</span>
                                    <span class="hc-dato-valor" id="det-fecha-registro"></span>
                                </div>
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Servicio solicitante</span>
                                    <span class="hc-dato-valor" id="det-servicio"></span>
                                </div>
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Persona que solicita</span>
                                    <span class="hc-dato-valor" id="det-solicitante"></span>
                                </div>
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Canal de ingreso</span>
                                    <span class="hc-dato-valor" id="det-canal"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Motivo</span>
                                    <span class="hc-dato-valor" id="det-motivo"></span>
                                </div>
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Documento</span>
                                    <span class="hc-dato-valor" id="det-documento"></span>
                                </div>
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Fecha del documento</span>
                                    <span class="hc-dato-valor" id="det-fecha-documento"></span>
                                </div>
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Cant. HC</span>
                                    <span class="hc-dato-valor" id="det-cantidad"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Lugar de archivo</span>
                                    <span class="hc-dato-valor" id="det-lugar"></span>
                                </div>
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Prioridad</span>
                                    <span class="hc-dato-valor" id="det-prioridad"></span>
                                </div>
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Fecha en que se requiere</span>
                                    <span class="hc-dato-valor" id="det-fecha-requerida"></span>
                                </div>
                                <div class="col-md-3 hc-dato">
                                    <span class="hc-dato-label">Fecha retorno prevista</span>
                                    <span class="hc-dato-valor" id="det-fecha-retorno"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 hc-dato">
                                    <span class="hc-dato-label">Observación del solicitante</span><span class="hc-dato-valor" id="det-observacion"></span>
                                </div>
                                <div class="col-md-4 hc-dato">
                                    <span class="hc-dato-label">Documento sustentatorio (PDF)</span>
                                    <span class="hc-dato-valor" id="det-pdf"></span>
                                </div>
                                <div class="col-md-2 hc-dato">
                                    <span class="hc-dato-label">Estado de la Solicitud</span>
                                    <span class="hc-dato-valor" id="det-estado"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading"><strong>2. HISTORIAS SOLICITADAS</strong> <span class="badge" id="det-total-hc"></span></div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="tabla-historias-solicitud" class="table table-bordered table-condensed" style="font-size:12px; margin:0">
                                    <thead>
                                        <tr><th>N° HC</th><th>DNI</th><th>Paciente</th><th>Estado</th></tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <br/>
                            <div class="row">
                                <div class="col-md-4 hc-dato">
                                    <span class="hc-dato-label">Observación de Archivo</span>
                                    <span class="hc-dato-valor" id="det-observacion-archivo"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Récord de la HC seleccionada con la lupa --}}
                    <div id="panel-record">
                        <div class="hc-record-titulo">
                            RÉCORD DE LA HC SELECCIONADA &middot; <span id="record-nro-hc"></span> &middot; <span id="record-paciente"></span>
                        </div>
                        <ul class="hc-record-lista">
                            <li id="record-registro"></li>
                            <li id="record-despacho"></li>
                            <li id="record-recepcion"></li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
{{-- Ingresos a Archivo de una solicitud --}}
<div class="modal fade" id="modal-ingresos" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width:95%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ingresos a Archivo &middot; <span id="modal-ingresos-solicitud"></span></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tabla-ingresos-solicitud" class="table table-bordered table-hover" style="font-size:12px">
                        <thead>
                            <tr>
                                <th style="width:30px"></th>
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
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
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
        $('#id_servicio').select2();

        $('#tabla-solicitudes').DataTable({
            order: [],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
            }
        });

        // Exporta a Excel con los mismos filtros del formulario
        $('#btnExportarExcel').on('click', function() {
            window.location.href = "{{ route('hc_movimiento.exportar_solicitudes') }}?" + $('form[action="{{ route('hc_movimiento.lista_solicitudes') }}"]').serialize();
        });

        var urlListarIngresos = "{{ route('hc_movimiento.listar_ingresos') }}";

        function mensajeIngresos(texto, clase) {
            $('#tabla-ingresos-solicitud tbody').html('<tr><td colspan="11" class="text-center ' + clase + '"></td></tr>');
            $('#tabla-ingresos-solicitud tbody td').text(texto);
        }

        // Expandir / contraer las historias de un ingreso
        $('#tabla-ingresos-solicitud').on('click', '.btn-historias-ingreso', function() {
            var boton = $(this);
            var abierto = boton.text() === '-';
            boton.closest('tr').next('.fila-historias-ingreso').toggle(!abierto);
            boton.text(abierto ? '+' : '-');
        });

        // Modal con los ingresos a Archivo de la solicitud
        $('#tabla-solicitudes').on('click', '.btn-ver-ingresos', function() {
            var d = $(this).data();
            $('#modal-ingresos-solicitud').text(d.solicitud);
            mensajeIngresos('Cargando...', 'text-muted');
            $('#modal-ingresos').modal('show');

            $.get(urlListarIngresos, { id_solicitud: d.id }, function(r) {
                var tbody = $('#tabla-ingresos-solicitud tbody').empty();
                if (!r.ingresos || r.ingresos.length === 0) {
                    mensajeIngresos('La solicitud no tiene ingresos registrados.', 'text-muted');
                    return;
                }
                $.each(r.ingresos, function(i, g) {
                    var fila = $('<tr>');
                    var historias = g.historias || [];
                    var toggle = $('<button type="button" class="btn btn-default btn-xs btn-historias-ingreso" title="Ver historias recepcionadas">').text('+');
                    fila.append($('<td class="text-center">').append(toggle));
                    $.each([g.nro_ingreso, g.fecha, g.solicitud, g.servicio, g.entrego, g.recibio,
                            g.despachadas, g.devueltas, g.pendientes, g.cargo], function(j, valor) {
                        fila.append($('<td>').text(valor));
                    });
                    tbody.append(fila);

                    // Fila expandible: historias recepcionadas en este ingreso
                    var detalle = $('<table class="table table-condensed table-bordered" style="margin:0; background:#fff">');
                    detalle.append('<thead><tr><th>N° HC</th><th>DNI</th><th>Paciente</th></tr></thead>');
                    var cuerpo = $('<tbody>').appendTo(detalle);
                    if (historias.length === 0) {
                        cuerpo.append($('<tr>').append($('<td colspan="3" class="text-muted text-center">').text('Sin historias registradas en este ingreso.')));
                    }
                    $.each(historias, function(j, h) {
                        cuerpo.append($('<tr>')
                            .append($('<td>').text(h.nro_hc))
                            .append($('<td>').text(h.dni))
                            .append($('<td>').text(h.paciente)));
                    });
                    tbody.append($('<tr class="fila-historias-ingreso" style="display:none; background:#f7f7f7">')
                        .append($('<td colspan="11">').append(detalle)));
                });
            }, 'json').fail(function() {
                mensajeIngresos('No se pudo consultar los ingresos.', 'text-danger');
            });
        });

        // Modal con lo registrado en el formulario de la solicitud + récord de la HC de la fila
        var urlVerSolicitud = "{{ route('hc_movimiento.ver_solicitud', '__ID__') }}";
        var estadosHc = { 1: 'PENDIENTE DE DESPACHO', 2: 'EN EL SERVICIO', 3: 'DEVUELTA' };

        function valorDato(selector, valor) {
            $(selector).text(valor ? valor : '—');
        }

        function pintarSolicitud(r, nroHc) {
            var d = r.solicitud;
            $('#det-nro').text(d.nro);
            $('#det-estado').text(d.estado);
            valorDato('#det-fecha-registro', d.fecha_registro);
            valorDato('#det-servicio', d.servicio);
            valorDato('#det-solicitante', d.solicitante);
            valorDato('#det-canal', d.canal_ingreso);
            valorDato('#det-motivo', d.motivo);
            valorDato('#det-documento', $.trim((d.tipo_documento || '') + ' N° ' + (d.nro_documento || '')));
            valorDato('#det-fecha-documento', d.fecha_documento);
            valorDato('#det-cantidad', d.cantidad_hc);
            valorDato('#det-lugar', d.lugar_archivo);
            valorDato('#det-prioridad', d.prioridad);
            valorDato('#det-fecha-requerida', d.fecha_requerida);
            valorDato('#det-fecha-retorno', d.fecha_retorno_prevista);
            valorDato('#det-observacion', d.observacion);
            valorDato('#det-observacion-archivo', d.observacion_archivo);

            if (d.url_documento) {
                $('#det-pdf').empty().append($('<a target="_blank">').attr('href', d.url_documento)
                    .append('<span class="glyphicon glyphicon-file"></span> Descargar PDF'));
            } else {
                $('#det-pdf').text('Sin documento adjunto');
            }

            var tbody = $('#tabla-historias-solicitud tbody').empty();
            $('#det-total-hc').text(r.historias.length);
            $.each(r.historias, function(i, h) {
                var fila = $('<tr>')
                    .append($('<td>').text(h.nro_hc))
                    .append($('<td>').text(h.dni))
                    .append($('<td>').text(h.paciente))
                    .append($('<td>').text(estadosHc[h.estado_hc] || ''));
                // if (String(h.nro_hc) === String(nroHc)) fila.addClass('hc-fila-seleccionada');
                tbody.append(fila);
            });
        }

        $('#tabla-solicitudes').on('click', '.btn-ver-record', function() {
            var d = $(this).data();

            // Récord de la HC de la fila
            $('#record-nro-hc').text(d.nroHc || '[N° HC]');
            $('#record-paciente').text(d.paciente || '[paciente]');
            $('#record-registro').text(
                d.fecha_hora_registro + ' · Solicitud ' + d.solicitud + ' solicitada por ' + d.solicita +
                ' (' + d.servicio + ') · ' + d.motivo + ' · ' + d.documento
            );
            $('#record-despacho').text(d.fecha_despacho ? d.fecha_despacho + ' · Despachada por ' + d.usuario_despacho : '');
            $('#record-recepcion').text(d.recepcion || '');

            // Datos registrados en el formulario
            $('#det-nro').text(d.solicitud);
            $('#det-estado').text('');
            $('#det-mensaje').hide();
            $('#det-contenido').hide();
            $('#modal-solicitud').modal('show');

            $.get(urlVerSolicitud.replace('__ID__', d.id), function(r) {
                pintarSolicitud(r, d.nroHc);
                $('#det-contenido').show();
            }, 'json').fail(function() {
                $('#det-mensaje').text('No se pudo consultar la solicitud.').show();
            });
        });
    });
</script>
@endsection
