@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;
    }
    .panel-heading div span{
        margin-left:5px;
    }
    .grilla-semanal td, .grilla-semanal th{vertical-align: middle !important}
    .grilla-semanal td.celda-dia{text-align: center; min-width: 110px; height: 62px}
    .grilla-semanal .btn-agregar-dia{color: #aaa; font-size: 18px; text-decoration: none}
    .grilla-semanal .btn-agregar-dia:hover{color: #337ab7}
    .grilla-semanal .bloque-turno{background: #dff0d8; border: 1px solid #b2dba1; border-radius: 4px; padding: 4px 2px; font-size: 12px; margin-bottom: 3px}
    .grilla-semanal .bloque-turno.inactivo{background: #f5f5f5; border-color: #ddd; color: #999}
    .grilla-semanal .bloque-turno.inactivo b{text-decoration: line-through}
    .grilla-semanal .bloque-turno .acciones a{margin: 0 3px; text-decoration: none}
    .md-dias{display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 8px}
    .md-dia{padding: 8px 10px; border: 1px solid #ccc; border-radius: 6px; cursor: pointer; user-select: none; font-weight: bold; color: #555}
    .md-dia.activo{background: #337ab7; border-color: #337ab7; color: #fff}
    .md-dia.deshabilitado{opacity: .4; cursor: not-allowed}
</style>
<script>
    var TD_TOKEN = "{{ csrf_token() }}";
    var TD_ID_SUB_CONSULTORIO = {{ intval($id_sub_consultorio) }};
    var TD_TIEMPO_CONSULTORIO = "{{ isset($subconsultorio->tiempo_atencion) ? $subconsultorio->tiempo_atencion : '' }}";
    var TD_DIAS = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"];
    var TD_DIAS_TXT = {"Lunes": "Lunes", "Martes": "Martes", "Miercoles": "Miércoles", "Jueves": "Jueves", "Viernes": "Viernes", "Sabado": "Sábado", "Domingo": "Domingo"};
    <?php
    $turnos_grilla = array();
    foreach ($turno as $f) {
        $turnos_grilla[] = array(
            'id' => $f->id,
            'descripcion' => $f->descripcion,
            'hora_ini' => $f->hora_ini,
            'hora_fin' => $f->hora_fin,
            'hospitalizacion' => isset($f->flag_enfermeria_hospitalizacion) ? $f->flag_enfermeria_hospitalizacion : null,
            'rango' => isset($f->rango) ? $f->rango : '',
            'permitido' => isset($f->permitido) ? $f->permitido : 0
        );
    }
    ?>
    var TD_TURNOS = {!! json_encode($turnos_grilla) !!};
    var TD_FILAS = {!! json_encode($turnos_dia) !!};

    function tdEscape(texto) {
        return $("<div>").text(texto == null ? "" : texto).html();
    }

    function tdHora24(hora) {
        if (!hora) return "";
        var m = String(hora).trim().match(/^(\d{1,2}):(\d{2})\s*(am|pm)?$/i);
        if (!m) return "";
        var h = parseInt(m[1], 10);
        var ap = m[3] ? m[3].toLowerCase() : "";
        if (ap == "pm" && h < 12) h += 12;
        if (ap == "am" && h == 12) h = 0;
        return (h < 10 ? "0" : "") + h + ":" + m[2];
    }

    function tdDetalle(f) {
        var tiempo = f.tiempo_atencion ? f.tiempo_atencion : TD_TIEMPO_CONSULTORIO;
        var txt = tiempo ? "cada " + tdEscape(tiempo) + " min" : "";
        if (f.fecha_inicio || f.fecha_fin) {
            txt += (txt ? "<br>" : "") + (f.fecha_inicio ? tdEscape(f.fecha_inicio) : "...") + " al " + (f.fecha_fin ? tdEscape(f.fecha_fin) : "...");
        }
        return txt == "" ? "" : '<div class="text-muted" style="font-size:10px">' + txt + "</div>";
    }

    function tdPintarGrilla() {
        var usados = {};
        $(TD_FILAS).each(function (i, f) { usados[f.id_turno] = true; });

        var html = "";
        $(TD_TURNOS).each(function (i, t) {
            if (t.hospitalizacion == 1 && !usados[t.id]) return;
            var filasTurno = $.grep(TD_FILAS, function (f) { return f.id_turno == t.id; });

            html += "<tr>";
            html += "<td><b>" + tdEscape(t.descripcion) + "</b>" + (t.rango ? '<div class="text-muted" style="font-size:10px">' + tdEscape(t.rango) + "</div>" : "") + "</td>";
            $(TD_DIAS).each(function (j, dia) {
                var filasDia = $.grep(filasTurno, function (f) { return f.dia == dia; });
                html += '<td class="celda-dia">';
                $(filasDia).each(function (k, f) {
                    if (f.id_estado_reg == 1) {
                        html += '<div class="bloque-turno">';
                        html += "<b>" + tdEscape(f.hora_ini) + " - " + tdEscape(f.hora_fin) + "</b>";
                        html += tdDetalle(f);
                        html += '<div class="acciones">';
                        html += '<a href="javascript:void(0)" title="Editar" onclick="tdAbrirEditar(' + f.id + ')"><i class="fa fa-edit"></i></a>';
                        html += '<a href="javascript:void(0)" title="Copiar a otros días" onclick="tdAbrirCopiar(' + f.id + ')"><i class="fa fa-copy"></i></a>';
                        html += '<a href="javascript:void(0)" title="Desactivar" class="text-warning" onclick="tdCambiarEstado(' + f.id + ', 1)"><i class="fa fa-thumbs-down"></i></a>';
                    } else {
                        html += '<div class="bloque-turno inactivo" title="Desactivado">';
                        html += "<b>" + tdEscape(f.hora_ini) + " - " + tdEscape(f.hora_fin) + "</b>";
                        html += '<div class="acciones">';
                        html += '<a href="javascript:void(0)" title="Activar" onclick="tdCambiarEstado(' + f.id + ', ' + f.id_estado_reg + ')"><i class="fa fa-thumbs-up"></i></a>';
                    }
                    html += '<a href="javascript:void(0)" title="Eliminar" class="text-danger" onclick="tdEliminar(' + f.id + ')"><i class="fa fa-trash"></i></a>';
                    html += "</div></div>";
                });
                // Solo Mañana, Tarde y Noche permiten agregar horarios
                if (t.permitido == 1) {
                    html += '<a class="btn-agregar-dia" href="javascript:void(0)" title="Agregar horario" onclick="tdAbrirNuevo(' + t.id + ', \'' + dia + '\')"><i class="fa fa-plus-circle"></i></a>';
                }
                html += "</td>";
            });
            html += "</tr>";
        });
        if (html == "") html = '<tr><td colspan="8" class="text-center">No hay turnos disponibles</td></tr>';
        $("#tblGrillaSemanal tbody").html(html);
    }

    function tdBuscarFila(id) {
        var r = $.grep(TD_FILAS, function (f) { return f.id == id; });
        return r.length > 0 ? r[0] : null;
    }

    function tdBuscarTurno(id) {
        var r = $.grep(TD_TURNOS, function (t) { return t.id == id; });
        return r.length > 0 ? r[0] : null;
    }

    // Rango permitido del turno en el modal (Noche cruza la medianoche: se registra en un solo tramo)
    function tdMostrarRango(id_turno) {
        var t = tdBuscarTurno(id_turno);
        var txt = "";
        if (t && t.rango) {
            txt = "Horario permitido: <b>" + tdEscape(t.rango) + "</b>";
        } else if (t) {
            txt = "Este turno ya no está permitido para procedimientos programados.";
        }
        $("#td_rango").html(txt);
    }

    function tdAbrirNuevo(id_turno, dia) {
        var t = tdBuscarTurno(id_turno);
        $("#td_id").val("");
        $("#td_id_turno").val(id_turno);
        $("#td_dia").val(dia);
        $("#td_turno_txt").html(tdEscape(t ? t.descripcion : ""));
        $("#td_dia_txt").html(TD_DIAS_TXT[dia]);
        $("#td_hora_ini").val(t ? tdHora24(t.hora_ini) : "");
        $("#td_hora_fin").val(t ? tdHora24(t.hora_fin) : "");
        tdMostrarRango(id_turno);
        $("#td_tiempo_atencion").val(TD_TIEMPO_CONSULTORIO);
        $("#td_fecha_inicio").val("");
        $("#td_fecha_fin").val("");
        $("#modalTurnoDia").modal("show");
    }

    function tdAbrirEditar(id) {
        var f = tdBuscarFila(id);
        if (!f) return;
        $("#td_id").val(f.id);
        $("#td_id_turno").val(f.id_turno);
        $("#td_dia").val(f.dia);
        $("#td_turno_txt").html(tdEscape(f.turno));
        $("#td_dia_txt").html(TD_DIAS_TXT[f.dia]);
        $("#td_hora_ini").val(tdHora24(f.hora_ini));
        $("#td_hora_fin").val(tdHora24(f.hora_fin));
        tdMostrarRango(f.id_turno);
        $("#td_tiempo_atencion").val(f.tiempo_atencion ? f.tiempo_atencion : TD_TIEMPO_CONSULTORIO);
        $("#td_fecha_inicio").val(f.fecha_inicio ? f.fecha_inicio : "");
        $("#td_fecha_fin").val(f.fecha_fin ? f.fecha_fin : "");
        $("#modalTurnoDia").modal("show");
    }

    function tdCambiarEstado(id, estado) {
        var msg = estado == 1 ? "¿Se desactivará el turno de este día, desea continuar?" : "¿Se activará el turno de este día, desea continuar?";
        bootbox.confirm(msg, function (result) {
            if (result == true) {
                $.ajax({
                    url: "/pp_estado_turno_dia",
                    type: "POST",
                    data: {_token: TD_TOKEN, id: id},
                    success: function (res) {
                        if (res.success) {
                            location.reload();
                        } else {
                            bootbox.alert(res.message);
                        }
                    }
                });
            }
        });
    }

    function tdEliminar(id) {
        bootbox.confirm("¿Se eliminará el turno, desea continuar?", function (result) {
            if (result == true) {
                $.ajax({
                    url: "/pp_eliminar_turno_dia",
                    type: "POST",
                    data: {_token: TD_TOKEN, id: id},
                    success: function (res) {
                        if (res.success) {
                            location.reload();
                        } else {
                            bootbox.alert(res.message);
                        }
                    }
                });
            }
        });
    }

    function tdAbrirCopiar(id) {
        var f = tdBuscarFila(id);
        if (!f) return;
        $("#md_id").val(id);
        $("#md_descripcion").html("Turno <b>" + tdEscape(f.turno) + "</b> del " + TD_DIAS_TXT[f.dia] + ": <b>" + tdEscape(f.hora_ini) + " - " + tdEscape(f.hora_fin) + "</b>. Marque los días a los que desea copiarlo.");
        var html = "";
        $(TD_DIAS).each(function (i, dia) {
            var cls = dia == f.dia ? "md-dia deshabilitado" : "md-dia";
            html += '<div class="' + cls + '" data-dia="' + dia + '">' + TD_DIAS_TXT[dia] + "</div>";
        });
        $("#md_dias").html(html);
        $("#modalCopiarDias").modal("show");
    }

    $(document).ready(function () {
        tdPintarGrilla();

        $(document).on("click", "#md_dias .md-dia", function () {
            if ($(this).hasClass("deshabilitado")) return;
            $(this).toggleClass("activo");
        });

        $(".btnAtajoDias").on("click", function () {
            var dias = String($(this).data("dias")).split(",");
            $("#md_dias .md-dia").each(function () {
                if ($(this).hasClass("deshabilitado")) return;
                $(this).toggleClass("activo", $.inArray(String($(this).data("dia")), dias) > -1);
            });
        });

        $("#btnGuardarTurnoDia").on("click", function () {
            if ($("#td_hora_ini").val() == "" || $("#td_hora_fin").val() == "") {
                bootbox.alert("Ingrese la <b>Hora Inicio</b> y la <b>Hora Fin</b>.");
                return;
            }
            // Noche (id_turno 4) puede cruzar la medianoche: el servidor lo guarda en dos horarios
            if ($("#td_hora_fin").val() <= $("#td_hora_ini").val() && $("#td_id_turno").val() != "4") {
                bootbox.alert("La hora de fin debe ser mayor a la hora de inicio.");
                return;
            }
            if ($.trim($("#td_tiempo_atencion").val()) == "" || parseInt($("#td_tiempo_atencion").val(), 10) <= 0) {
                bootbox.alert("Ingrese el <b>Tiempo de Atención</b> (minutos).");
                return;
            }
            var $btn = $(this).prop("disabled", true);
            $.ajax({
                url: "/pp_guardar_turno_dia",
                type: "POST",
                data: {
                    _token: TD_TOKEN,
                    id: $("#td_id").val(),
                    id_sub_consultorio: TD_ID_SUB_CONSULTORIO,
                    id_turno: $("#td_id_turno").val(),
                    dia: $("#td_dia").val(),
                    hora_ini: $("#td_hora_ini").val(),
                    hora_fin: $("#td_hora_fin").val(),
                    tiempo_atencion: $("#td_tiempo_atencion").val(),
                    fecha_inicio: $("#td_fecha_inicio").val(),
                    fecha_fin: $("#td_fecha_fin").val()
                },
                success: function (res) {
                    if (res.success) {
                        location.reload();
                    } else {
                        bootbox.alert(res.message);
                    }
                },
                complete: function () {
                    $btn.prop("disabled", false);
                }
            });
        });

        $("#btnCopiarDias").on("click", function () {
            var dias = [];
            $("#md_dias .md-dia.activo").each(function () { dias.push($(this).data("dia")); });
            if (dias.length == 0) {
                bootbox.alert("Seleccione al menos un día.");
                return;
            }
            var $btn = $(this).prop("disabled", true);
            $.ajax({
                url: "/pp_copiar_turno_dias",
                type: "POST",
                data: {_token: TD_TOKEN, id: $("#md_id").val(), dias: dias},
                success: function (res) {
                    if (!res.success) {
                        bootbox.alert(res.message);
                        return;
                    }
                    // Se agrega automáticamente; solo se avisa si algún día no se pudo agregar
                    if (!res.errores || res.errores.length == 0) {
                        location.reload();
                        return;
                    }
                    bootbox.alert("No se pudo agregar en:<br>" + $.map(res.errores, tdEscape).join("<br>"), function () { location.reload(); });
                },
                complete: function () {
                    $btn.prop("disabled", false);
                }
            });
        });
    });
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Proc. Programados - {{$consultorio->nombre}} : Consultorio - {{$subconsultorio->descripcion}}</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/add_consultorios_servicio/{{ $id_servicio }}">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Horario semanal de procedimientos</h3>
                            </div>
                            <div class="panel-body">
                                <p class="text-muted" style="margin-bottom:10px">
                                    Haga clic en <b>+</b> para agregar el horario de un turno en un día. Para todos los días, agréguelo en un día y use <i class="fa fa-copy"></i> <b>Copiar a otros días</b>.
                                </p>
                                <div class="table-responsive">
                                    <table id="tblGrillaSemanal" class="table table-bordered grilla-semanal">
                                        <thead>
                                            <tr>
                                                <th width="10%">Turno</th>
                                                <th class="text-center">Lunes</th>
                                                <th class="text-center">Martes</th>
                                                <th class="text-center">Miércoles</th>
                                                <th class="text-center">Jueves</th>
                                                <th class="text-center">Viernes</th>
                                                <th class="text-center">Sábado</th>
                                                <th class="text-center">Domingo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td colspan="8" class="text-center">Cargando...</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Turnos seleccionados</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th width="14%">Turno</th>
                                            <th width="11%">Día</th>
                                            <th width="12%">Hora Inicio</th>
                                            <th width="12%">Hora Fin</th>
                                            <th width="12%">Tiempo Atención</th>
                                            <th width="12%">Fecha de Inicio</th>
                                            <th width="12%">Fecha Final</th>
                                            <th width="8%" class="text-center">Estado</th>
                                            <th width="7%" class="text-center">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $dias_txt = array('Miercoles' => 'Miércoles', 'Sabado' => 'Sábado'); ?>
                                        @forelse($turnos_dia as $e)
                                        <tr>
                                            <td>{{$e->turno}}</td>
                                            <td>{{ isset($dias_txt[$e->dia]) ? $dias_txt[$e->dia] : $e->dia }}</td>
                                            <td>{{$e->hora_ini}}</td>
                                            <td>{{$e->hora_fin}}</td>
                                            <td>{{ $e->tiempo_atencion ? $e->tiempo_atencion : (isset($subconsultorio->tiempo_atencion) ? $subconsultorio->tiempo_atencion : '') }} min.</td>
                                            <td>{{ $e->fecha_inicio ? $e->fecha_inicio : '-' }}</td>
                                            <td>{{ $e->fecha_fin ? $e->fecha_fin : '-' }}</td>
                                            <td class="text-center">
                                                <?php if ($e->id_estado_reg == 1) { ?>
                                                    <a class="btn btn-success" href="javascript:void(0)" title="Desactivar" onclick="tdCambiarEstado(<?php echo $e->id ?>, 1)"><i class="fa fa-thumbs-up"></i></a>
                                                <?php } else { ?>
                                                    <a class="btn btn-danger" href="javascript:void(0)" title="Activar" onclick="tdCambiarEstado(<?php echo $e->id ?>, <?php echo $e->id_estado_reg ?>)"><i class="fa fa-thumbs-down"></i></a>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-danger" href="javascript:void(0)" title="Eliminar" onclick="tdEliminar(<?php echo $e->id ?>)"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No hay turnos registrados</td>
                                        </tr>
                                        @endforelse
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

<div class="modal fade" id="modalTurnoDia" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Horario - <span id="td_turno_txt"></span> - <span id="td_dia_txt"></span></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="td_id" value="">
                <input type="hidden" id="td_id_turno" value="">
                <input type="hidden" id="td_dia" value="">
                <div class="row">
                    <div class="col-xs-4">
                        <label>Hora Inicio</label>
                        <input type="time" id="td_hora_ini" class="form-control">
                    </div>
                    <div class="col-xs-4">
                        <label>Hora Fin</label>
                        <input type="time" id="td_hora_fin" class="form-control">
                    </div>
                    <div class="col-xs-4">
                        <label>Tiempo Atención (min.)</label>
                        <input type="text" id="td_tiempo_atencion" class="form-control" maxlength="3" autocomplete="off"
                            onkeypress="return /\d/.test(String.fromCharCode(event.which || event.keyCode))">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-muted" id="td_rango" style="font-size:11px; margin-top:5px"></div>
                </div>
                <div class="row" style="margin-top:10px">
                    <div class="col-xs-6">
                        <label>Fecha de Inicio</label>
                        <input type="text" id="td_fecha_inicio" class="form-control datepicker" placeholder="Opcional" autocomplete="off">
                    </div>
                    <div class="col-xs-6">
                        <label>Fecha Final</label>
                        <input type="text" id="td_fecha_fin" class="form-control datepicker" placeholder="Opcional" autocomplete="off">
                    </div>
                </div>
                <small class="text-muted">Sin fechas, el horario aplica siempre. Con fechas, solo se generan cupos dentro de ese rango.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarTurnoDia"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCopiarDias" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Copiar horario a otros días</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="md_id" value="">
                <p id="md_descripcion" class="text-muted"></p>
                <div style="margin-bottom:8px">
                    <button type="button" class="btn btn-default btn-xs btnAtajoDias" data-dias="Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo">Todos (Lun - Dom)</button>
                    <button type="button" class="btn btn-default btn-xs btnAtajoDias" data-dias="Lunes,Martes,Miercoles,Jueves,Viernes,Sabado">Lun - Sáb</button>
                    <button type="button" class="btn btn-default btn-xs btnAtajoDias" data-dias="Lunes,Martes,Miercoles,Jueves,Viernes">Lun - Vie</button>
                    <button type="button" class="btn btn-default btn-xs btnAtajoDias" data-dias="Lunes,Miercoles,Viernes">Interdiario L - Mi - V</button>
                    <button type="button" class="btn btn-default btn-xs btnAtajoDias" data-dias="Martes,Jueves,Sabado">Interdiario Ma - J - S</button>
                    <button type="button" class="btn btn-default btn-xs btnAtajoDias" data-dias="">Limpiar</button>
                </div>
                <div id="md_dias" class="md-dias"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnCopiarDias"><i class="fa fa-check"></i> Aplicar</button>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
