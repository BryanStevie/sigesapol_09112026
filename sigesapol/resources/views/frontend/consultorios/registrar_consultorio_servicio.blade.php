@extends('frontend.layouts.master')
@section('renderjs')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
    <script>
        function justNumbers(e) {
            var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum == 46))
                return true;
            return /\d/.test(String.fromCharCode(keynum));
        }

        $(document).ready(function() {
            // $(document).on("click", ".btnCambiarEstado", function(e) {
            //     e.preventDefault();
            //     const procedimiento_id = $(this).data("procedimiento_id");
            //     const subconsultorio_id = $(this).data("subconsultorio_id");
            //     $.ajax({
            //         url: '/procedimiento_subconsultorio/actualizar-estado',
            //         method: 'POST',
            //         data: {
            //             procedimiento_id: procedimiento_id,
            //             subconsultorio_id: subconsultorio_id,
            //             _token: $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             console.log(response);
            //             $('#tblProcVinc').DataTable().ajax.reload(null,
            //                 false);
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //             alert("Ocurrió un error al cambiar el estado.");
            //         }
            //     });
            // });

            // $('#modalBindProcedimientoToConsultorio').on('hidden.bs.modal', function(e) {
            //     $("#procedimiento_proc_info").val("");
            //     $("#procedimiento_id").val("");
            //     $("#panelProcInfo").hide();
            // });
            // let tableProcVinc = null;
            // $(".btnBindProcedimiento").click(function(e) {
            //     $("#ipress_det").html($(this).data("establecimiento"));
            //     $("#sc_id").val($(this).data("id"));
            //     $("#ta_det").html($(this).data("ta"));
            //     $("#consultorio_det").html($(this).data("descrip"));
            //     $("#modalBindProcedimientoToConsultorio").modal("show");
            //     if ($.fn.DataTable.isDataTable('#tblProcVinc')) {
            //         $('#tblProcVinc').DataTable().clear().destroy();
            //     }

            //     tableProcVinc = $('#tblProcVinc').DataTable({
            //         pageLength: 5,
            //         lengthMenu: [5, 10, 25, 50],
            //         searching: false,
            //         processing: true,
            //         serverSide: true,
            //         ajax: `/listarProcedimientosVinculados/${$("#sc_id").val()}`,
            //         columns: [{
            //                 data: null,
            //                 width: "95%",
            //                 render: function(data, type, row) {
            //                     return `${row.codigo} - ${row.descripcion}`
            //                 }
            //             },
            //             {
            //                 data: null,
            //                 width: "5%",
            //                 render: function(data, type, row) {
            //                     if (row.sub_consultorios[0].pivot.id_estado_reg) {
            //                         return `<button type="button" data-toggle="tooltip" title="Desactivar procedimiento" data-procedimiento_id="${row.id}" data-subconsultorio_id="${row.sub_consultorios[0].id}" class="btn btn-sm btn-primary btnCambiarEstado"><i class="fa fa-thumbs-up" aria-hidden="true"></i></button>`;
            //                     }
            //                     return `<button type="button" data-toggle="tooltip" title="Reactivar procedimiento" data-procedimiento_id="${row.id}" data-subconsultorio_id="${row.sub_consultorios[0].id}" class="btn btn-sm btn-danger btnCambiarEstado"><i class="fa fa-thumbs-down" aria-hidden="true"></i></button>`;
            //                 }
            //             }
            //         ],
            //     });
            // });

            // /* $('#tblProcVinc').on('draw.dt', function() {
            //     $('.btnCambiarEstado').tooltip();
            // }); */

            // $("#procedimiento_codigo-nombre").autocomplete({
            //     minLength: 1,
            //     delay: 400,
            //     source: function(request, response) {
            //         $.ajax({
            //             url: `/procedimientos/listado?keyword=${$("#procedimiento_codigo-nombre").val().toUpperCase()}`,
            //             dataType: "json",
            //             success: function(data) {
            //                 response(data);
            //             }
            //         })
            //     },
            //     select: function(e, ui) {
            //         var procedimiento_id = ui.item.id;
            //         var procedimiento_nombre = ui.item.label;
            //         var procedimiento_codigo = ui.item.codigo;
            //         $("#procedimiento_proc_info").val(procedimiento_nombre);
            //         $("#procedimiento_id").val(procedimiento_id);
            //         $("#panelProcInfo").show();
            //         $(this).val('');
            //         return false;
            //     }
            // });

            // $("#btnVincularProcedimiento").click(function(e) {
            //     const codigo_nombre = $("#procedimiento_proc_info").val().trim();
            //     const proc_id = $("#procedimiento_id").val().trim();
            //     if (codigo_nombre === '' || proc_id === '') {
            //         Swal.fire({
            //             title: 'Completa los campos',
            //             text: 'Debes ingresar algun procedimiento.',
            //         });
            //     } else {
            //         Swal.fire({
            //             title: `¿Estás seguro que desea vincular el procedimiento: ${$("#procedimiento_codigo-nombre").val()}?`,
            //             showCancelButton: true,
            //             confirmButtonText: 'Sí, vincular',
            //             cancelButtonText: 'Cancelar',
            //             customClass: {
            //                 title: 'mi-titulo-personalizado'
            //             }
            //         }).then((result) => {
            //             if (result.isConfirmed) {
            //                 $.ajax({
            //                     url: '/registrar_procedimiento_con_consultorio',
            //                     method: 'POST',
            //                     data: {
            //                         procedimiento_id: $("#procedimiento_id").val(),
            //                         subconsultorio_id: $("#sc_id").val(),
            //                     },
            //                     headers: {
            //                         'X-CSRF-TOKEN': $('#_token_vinc').val(),
            //                     },
            //                     success: function(res) {
            //                         if (res.success) {
            //                             Swal.fire('¡Guardado!',
            //                                 'Los datos han sido guardados.',
            //                                 'success');
            //                             tableProcVinc.ajax.reload();
            //                         } else {
            //                             Swal.fire('Ya esta vinculado el procedimiento',
            //                                 'El procedimiento registrado, ya se encuentra vinculado.',
            //                                 'error');
            //                         }
            //                     },
            //                     error: function(jqXHR, textStatus, errorThrown) {
            //                         Swal.fire('Error', 'Ocurrió un error al guardar.',
            //                             'error');
            //                         console.error('Error:', textStatus, errorThrown);
            //                     }
            //                 });
            //             }
            //         });
            //     }
            // });

            $('#consultorios_agregar_dia').hide();
            @if ($existe == 1)
                $("#id_establecimiento").select2({
                    width: 'resolve' // need to override the changed default
                });
            @endif
            $("#id_tipo_atencion").select2({
                width: 'resolve' // need to override the changed default
            });

            $("#btnReg").on("click", function() {

                let descripcion = $("#descripcion").val();
                var msg = "";
                if ($("#id_establecimiento").val() == "0") msg +=
                    "Falta seleccionar el <b>Establecimiento (IPRESS)</b>.<br>";
                if ($("#id_tipo_atencion").val() == "0") msg +=
                    "Falta seleccionar el <b>Tipo de Atención</b>.<br>";
                if (descripcion.trim() == "") msg += "Falta ingresar el <b>Nombre de Consultorio</b>.<br>";

                if (msg != "") {
                    bootbox.alert(msg);
                    return false;
                } else {
                    $("#frm_transferencia").submit();
                }

            });

        });

    function getConsultorios() {

            var id_establecimiento = $('#id_establecimiento').val();
            var id_servicio = $('#id_servicio').val();
            var estado_tiempo = $('#estado_tiempo').val();
            //$('#tblConsultorio tbody').html("");
            if (estado_tiempo == null) {
                $('#tiempo_atencion').append(
                    `<div id="tiempo_atencion_temporal" class="form-group">
                    <input id="estado_tiempo" type="hidden" value="1" />
                    <label class="col-lg-2 control-label">Tiempo Atenci&oacute;n</label>
                    <div class="col-lg-9">
                        <input name="tiempo_atencion" type="text" class="form-control" required="" placeholder="Tiempo Atenci&oacute;n" maxlength="3" autocomplete="off" onKeyPress="return justNumbers(event)">
                    </div>
                </div>`
                );
                $('#consultorios_agregar_dia').hide();
                $('#consultorios_agregar_turnos').show();
            }
            if (id_establecimiento == 76) {
                $('#tiempo_atencion_temporal').remove();
                $('#consultorios_agregar_turnos').hide();
                $('#consultorios_agregar_dia').show();
            }

            var idsConsultoriosProcedimiento = {!! json_encode($ids_consultorios_procedimiento) !!};
            function botonTurnosConsultorio(id_sub_consultorio, url_turnos) {
                if (idsConsultoriosProcedimiento.indexOf(parseInt(id_sub_consultorio, 10)) !== -1) {
                    return '<td class="text-center"><a class="btn btn-warning" title="Turnos de procedimientos programados" href="/add_procprogramados_turnos/' +
                        id_sub_consultorio + '/' + id_servicio + '"><i class="fa fa-medkit"></i></a></td>';
                }
                return '<td class="text-center"><a class="btn btn-primary" href="' + url_turnos + '"><i class="fa fa-medkit"></i></a></td>';
            }

            $.ajax({
                url: '/obtener_consultorio_por_id_establecimiento/' + id_establecimiento + '/' + id_servicio,
                success: function(result) {
                    var html = "";
                    var adicional = "";
                    $(result).each(function(ii, oo) {
                        if (id_establecimiento == 76) {
                            if (oo.adicional == 0) {
                                adicional = "NO";
                            } else {
                                adicional = "SI";
                            }
                            html += "<tr>";
                            html += "<td>" + oo.establecimiento + "</td>";
                            html += "<td>" + oo.tipo_atencion + "</td>";
                            html += "<td>" + oo.descripcion + "</td>";
                            html += "<td>" + oo.abreviatura + "</td>";
                            html += "<td></td>";
                            html += "<td>" + oo.tiene_inicio + "</td>";
                            html += "<td>" + oo.descripcion_tv + "</td>";
                            html += "<td>" + adicional + "</td>";
                            html +=
                                '<td class="text-center"><a class="btn btn-success" href="/edit_subconsultorios_servicio_2/' +
                                oo.id + '"><i class="fa fa-edit"></i></a></td>';
                            html += botonTurnosConsultorio(oo.id, '/add_consultorio_turnos_dia/' + oo.id + '/' + id_servicio);
                            <?php if ($existe == 1): ?>
                            html += '<td class="text-center">';
                            if (oo.id_estado_reg == "1") {
                                html +=
                                    '<a class="btn btn-success" href="javascript:void(0)" onclick="desactivar(' +
                                    oo.id + ',' + oo.id_estado_reg +
                                    ')"><i class="fa fa-thumbs-up"></i></a>';
                            } else {
                                html +=
                                    '<a class="btn btn-danger" href="javascript:void(0)" onclick="desactivar(' +
                                    oo.id + ',' + oo.id_estado_reg +
                                    ')"><i class="fa fa-thumbs-down"></i></a>';
                            }
                            html += "</td>";
                            <?php endif; ?>
                            html += "</tr>";
                        } else {
                            html += "<tr>";
                            html += "<td>" + oo.establecimiento + "</td>";
                            html += "<td>" + oo.tipo_atencion + "</td>";
                            html += "<td>" + oo.descripcion + "</td>";
                            html += "<td>" + oo.abreviatura + "</td>";
                            html += "<td>" + oo.tiempo_atencion + "  min.</td>";
                            html += "<td>" + oo.tiene_inicio + "</td>";
                            html += "<td>" + oo.descripcion_tv + "</td>";
                            html += "<td></td>";
                            html +=
                                '<td class="text-center"><a class="btn btn-success" href="/edit_subconsultorios_servicio/' +
                                oo.id + '"><i class="fa fa-edit"></i></a></td>';
                            html += botonTurnosConsultorio(oo.id, '/add_consultorio_turnos/' + oo.id + '/' + id_servicio);
                            <?php if ($existe == 1): ?>
                            html += '<td class="text-center">';
                            if (oo.id_estado_reg == "1") {
                                html +=
                                    '<a class="btn btn-success" href="javascript:void(0)" onclick="desactivar(' +
                                    oo.id + ',' + oo.id_estado_reg +
                                    ')"><i class="fa fa-thumbs-up"></i></a>';
                            } else {
                                html +=
                                    '<a class="btn btn-danger" href="javascript:void(0)" onclick="desactivar(' +
                                    oo.id + ',' + oo.id_estado_reg +
                                    ')"><i class="fa fa-thumbs-down"></i></a>';
                            }
                            html += "</td>";
                            <?php endif; ?>
                            html += "</tr>";
                        }
                    });
                    $('#tblConsultorio tbody').html(html);
                    //$("#id_farmacia").select2({max_selected_options: 4});
                }
            });

        }

        function desactivar(id, estado) {
            if (estado == 3) {
                bootbox.confirm("¿Se activará el Consultorio para este servicio, desea continuar?", function(result) {
                    if (result == true) {
                        $.ajax({
                            url: '/desactivar_consultorio_servicio/' + id + '/' + estado,
                            success: function(result) {
                                location.reload();
                            }
                        });
                    }
                });
            } else {
                bootbox.confirm("¿Se desactivará el Consultorio para este servicio, desea continuar?", function(result) {
                    if (result == true) {
                        $.ajax({
                            url: '/desactivar_consultorio_servicio/' + id + '/' + estado,
                            success: function(result) {
                                location.reload();
                            }
                        });
                    }
                });
            }
        }

        function getfechainicio() {
            $fecha_inicial = $("#fecha_inicio").val();
            console.log($fecha_inicial);

        }
    </script>
    <style>
        .etiqueta {
            margin-top: 5px
        }

        .panel_medicamentos {
            border: 1px solid gray;
            padding-top: 10px
        }

        .panel-heading div {
            margin-top: -18px;
            font-size: 15px;
        }

        .panel-heading div span {
            margin-left: 5px;
        }

        .ui-autocomplete {
            z-index: 1051 !important;
            /* o más alto según el z-index de tu modal */
        }

        .mi-titulo-personalizado {
            font-size: 15px;
            padding: 16px;
        }

        .swal2-title {
            font-size: 15px !important;
            /* Cambia el tamaño del título */
            padding: 20px 10px !important;
            /* Añade padding superior/inferior y lateral */
            padding-bottom: 0;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Servicio - {{ $consultorio->nombre }}</h3>
                        <div class="pull-right">
                            <a class="btn btn-default btn-xs" href="/all_servicios">
                                <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12" style="margin-top:15px">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Agregar Consultorio</h3>
                                </div>
                                <?php //exit();
                                ?>
                                <div class="panel-body">
                                    <form id="frm_transferencia" class="form-horizontal" method="POST"
                                        action="{{ route('agregar_consultorio_servicio') }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id_servicio" id="id_servicio"
                                            value="<?php echo $id_servicio; ?>">
                                        <?php if ($existe == 1): ?>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">IPRESS</label>
                                            <div class="col-lg-9">
                                                <select class="form-control" name="id_establecimiento"
                                                    id="id_establecimiento" onchange="getConsultorios()" required=""
                                                    placeholder="Seleccionar un Hospital" style="width: 100%">
                                                    <option value="0">-Seleccionar-</option>
                                                    @foreach ($establecimientos as $f)
                                                        <option value="{{ $f->id }}">{{ $f->codigo }} -
                                                            {{ $f->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">IPRESS</label>
                                            <div class="col-lg-9">
                                                @foreach ($establecimientos as $f)
                                                    @if ($establecimientotot->id_establecimiento == $f->id)
                                                        <input readonly="" name="establecimiento"
                                                            value="{{ $f->codigo }} - {{ $f->nombre }}" type="text"
                                                            class="form-control" required="" placeholder="Consultorio"
                                                            autocomplete="off">
                                                    @endif
                                                @endforeach
                                                <input type="hidden" name="id_establecimiento" id="id_establecimiento"
                                                    value="<?php echo $establecimientotot->id_establecimiento; ?>">
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Tipo de Atención</label>
                                            <div class="col-lg-9">
                                                <select name="id_tipo_atencion" id="id_tipo_atencion" class="form-control"
                                                    required="" style="width: 100%">
                                                    <option value="0">-Seleccionar-</option>
                                                    @foreach ($tipo_atenciones as $f)
                                                        <option value="{{ $f->id }}">{{ $f->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Consultorio</label>
                                            <div class="col-lg-9">
                                                <input name="descripcion" id="descripcion" type="text"
                                                    class="form-control text-uppercase" required=""
                                                    placeholder="Consultorio" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Abreviatura</label>
                                            <div class="col-lg-9">
                                                <input name="abreviatura" id="abreviatura" type="text"
                                                    class="form-control" required="" placeholder="Abreviatura"
                                                    maxlength="4" autocomplete="off">
                                            </div>
                                        </div>
                                        @if ($establecimientotot->id_establecimiento == 76)
                                            <div class="form-group">
                                                <label class="col-lg-2 control-label">Descripción para TV</label>
                                                <div class="col-lg-9">
                                                    <input name="descripcion_tv" type="text" class="form-control"
                                                        placeholder="Descripción para TV" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 control-label">Consultorio permite cupo
                                                    adicional</label>
                                                <div class="col-lg-9">
                                                    <input type="checkbox" value="1" name="adicional" />
                                                </div>
                                            </div>
                                        @endif
                                        @if ($establecimientotot->id_establecimiento == 76 || $establecimientotot->id_establecimiento == 77)
                                            <div class="form-group">
                                                <label class="col-lg-2 control-label">MOPRI (Centro quirurgico)</label>
                                                <div class="col-lg-9">
                                                    <input type="checkbox" value="1" name="mopri_cqx" />
                                                </div>
                                            </div>
                                        @endif
                                        @if ($establecimientotot->id_establecimiento == 76)
                                            <div class="form-group">
                                                <div class="col-lg-5">
                                                    <label class="col-lg-6 control-label">Fecha de Inicio de
                                                        Atencion</label>
                                                    <div class="col-lg-6">
                                                        <input name="fecha_inicio" id= "fecha_inicio" type="text"
                                                            data-date-start-date="0d" class="form-control datepicker"
                                                            placeholder="Fecha Inicial">
                                                    </div>
                                                </div>
                                                <div class="col-lg-1">
                                                </div>
                                                <div class="col-lg-5">
                                                    <label class="col-lg-6 control-label">Fecha de Fin de Atencion</label>
                                                    <div class="col-lg-6">
                                                        <input name="fecha_final" type="text"
                                                            data-date-start-date="0d" class="form-control datepicker"
                                                            placeholder="Fecha Finalizar" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-offset-7 col-md-4">
                                            <input id="btnReg" class="btn btn-success form-control" value="Agregar"
                                                type="submit">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div id="consultorios_agregar_turnos" class="col-md-12" style="margin-top:15px">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Consultorios seleccionados</h3>
                                </div>
                                <div class="panel-body">
                                    <table id="tblConsultorio" class="table" style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th width="30%">IPRESS</th>
                                                <th width="15%">Tipo Atención</th>
                                                <th width="25%">Consultorio</th>
                                                <th width="15%">Abreviatura</th>
                                                <th width="15%">Tiempo Atención</th>
                                                <th width="15%">Fecha de Apertura</th>
                                                <th width="15%">Descripción TV</th>

                                                @if (Auth::user()->id_establecimiento == 76 || Auth::user()->flagestablecimientos == 76)
                                                    <th width="15%">Cita Adicional</th>
                                                @endif
                                                @if (Auth::user()->id_establecimiento == 76 ||
                                                        Auth::user()->flagestablecimientos == 76 ||
                                                        Auth::user()->id_establecimiento == 77 ||
                                                        Auth::user()->flagestablecimientos == 77)
                                                    <th width="15%">MOPRI (Centro Quirurgico)</th>
                                                @endif
                                                <th width="10%" class="text-center">Editar</th>
                                                <th width="10%" class="text-center">Agregar Turnos/Día</th>
                                                <!--th width="10%" class="text-center">Agregar Procedimiento Programado</th-->
                                                <?php if ($existe == 1): ?>
                                                <th width="10%" class="text-center">Estado</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subconsultorio_servicio as $e)
                                                <tr>
                                                    <td>{{ $e->establecimiento }}</td>
                                                    <td>{{ $e->tipo_atencion }}</td>
                                                    <td>{{ $e->descripcion }}</td>
                                                    <td>{{ $e->abreviatura }}</td>

                                                    @if ($e->id_establecimiento == 76)
                                                        <td></td>
                                                    @else
                                                        <td>{{ $e->tiempo_atencion }} min.</td>
                                                    @endif
                                                    <td>{{ $e->tiene_inicio }}</td>
                                                    <td>{{ $e->descripcion_tv }}</td>

                                                    @if (Auth::user()->id_establecimiento == 76 || Auth::user()->flagestablecimientos == 76)
                                                        <td>
                                                            @if ($e->adicional == 0)
                                                                NO
                                                            @else
                                                                SI
                                                            @endif
                                                        </td>
                                                    @endif
                                                    @if (Auth::user()->id_establecimiento == 76 ||
                                                            Auth::user()->flagestablecimientos == 76 ||
                                                            Auth::user()->id_establecimiento == 77 ||
                                                            Auth::user()->flagestablecimientos == 77)
                                                        <td>
                                                            @if ($e->mopri_cqx == 0)
                                                                NO
                                                            @else
                                                                SI
                                                            @endif
                                                        </td>
                                                    @endif
                                                    @if ($e->id_establecimiento == 76)
                                                        <td class="text-center">
                                                            <a class="btn btn-success"
                                                                href="/edit_subconsultorios_servicio_2/{{ $e->id }}"><i
                                                                    class="fa fa-edit"></i></a>
                                                        </td>
                                                    @else
                                                        <td class="text-center">
                                                            <a class="btn btn-success"
                                                                href="/edit_subconsultorios_servicio/{{ $e->id }}"><i
                                                                    class="fa fa-edit"></i></a>
                                                        </td>
                                                    @endif
                                                    @if (in_array(intval($e->id), $ids_consultorios_procedimiento))
                                                        <td class="text-center">
                                                            <a class="btn btn-warning" title="Turnos de procedimientos programados"
                                                                href="/add_procprogramados_turnos/{{ $e->id }}/{{ $id_servicio }}"><i
                                                                    class="fa fa-medkit"></i></a>
                                                        </td>
                                                    @elseif ($e->id_establecimiento == 76)
                                                        <td class="text-center">
                                                            <a class="btn btn-primary"
                                                                href="/add_consultorio_turnos_dia/{{ $e->id }}/{{ $id_servicio }}"><i
                                                                    class="fa fa-medkit"></i></a>
                                                        </td>
                                                    @else
                                                        <td class="text-center">
                                                            <a class="btn btn-primary"
                                                                href="/add_consultorio_turnos/{{ $e->id }}/{{ $id_servicio }}"><i
                                                                    class="fa fa-medkit"></i></a>
                                                        </td>
                                                    @endif
                                                    <!--td class="text-center">
                                                        <button id="btnBindProcedimiento"
                                                            class="btn btn-primary btnBindProcedimiento"
                                                            data-establecimiento="{{ $e->establecimiento }}"
                                                            data-ta="{{ $e->tipo_atencion }}"
                                                            data-descrip="{{ $e->descripcion }}"
                                                            data-id="{{ $e->id }}">
                                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                                        </button>
                                                    </td-->
                                                    <?php if ($existe == 1): ?>
                                                    <td class="text-center">
                                                        <?php if($e->id_estado_reg == "1"){ ?>
                                                        <a class="btn btn-success" href="javascript:void(0)"
                                                            onclick="desactivar(<?php echo $e->id; ?>,<?php echo $e->id_estado_reg; ?>)"><i
                                                                class="fa fa-thumbs-up"></i></a>
                                                        <?php } else {?>
                                                        <a class="btn btn-danger" href="javascript:void(0)"
                                                            onclick="desactivar(<?php echo $e->id; ?>,<?php echo $e->id_estado_reg; ?>)"><i
                                                                class="fa fa-thumbs-down"></i></a>
                                                        <?php }?>
                                                    </td>
                                                    <?php endif; ?>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="consultorios_agregar_dia" class="col-md-12" style="margin-top:15px">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Consultorios seleccionados</h3>
                                </div>
                                <div class="panel-body">
                                    <table id="tblConsultorio" class="table" style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th width="30%">IPRESS</th>
                                                <th width="15%">Tipo Atención</th>
                                                <th width="25%">Consultorio</th>
                                                <th width="15%">Abreviatura</th>
                                                <th width="10%">Tiempo Atención</th>
                                                <th width="10%">Fecha de Apertura</th>
                                                <th width="10%">Descripción TV</th>

                                                @if (Auth::user()->id_establecimiento == 76 || Auth::user()->flagestablecimientos == 76)
                                                    <th width="15%">Cita Adicional</th>
                                                @endif
                                                <th width="10%" class="text-center">Editar</th>
                                                <th width="10%" class="text-center">Agregar Turnos/Día</th>
                                                <?php if ($existe == 1): ?>
                                                <th width="10%" class="text-center">Estado</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subconsultorio_servicio as $e)
                                                <tr>
                                                    <td>{{ $e->establecimiento }}</td>
                                                    <td>{{ $e->descripcion }}</td>
                                                    <td>{{ $e->abreviatura }}</td>
                                                    <td>{{ $e->tipo_atencion }}</td>
                                                    <td>{{ $e->tiene_inicio }}</td>
                                                    <td>{{ $e->descripcion_tv }}</td>

                                                    @if (Auth::user()->id_establecimiento == 76 || Auth::user()->flagestablecimientos == 76)
                                                        <td>
                                                            @if ($e->adicional == 0)
                                                                NO
                                                            @else
                                                                SI
                                                            @endif
                                                        </td>
                                                    @endif
                                                    @if ($e->id_establecimiento == 76)
                                                        <td class="text-center">
                                                            <a class="btn btn-success"
                                                                href="/edit_subconsultorios_servicio_2/{{ $e->id }}"><i
                                                                    class="fa fa-edit"></i></a>
                                                        </td>
                                                    @else
                                                        <td class="text-center">
                                                            <a class="btn btn-success"
                                                                href="/edit_subconsultorios_servicio/{{ $e->id }}"><i
                                                                    class="fa fa-edit"></i></a>
                                                        </td>
                                                    @endif
                                                    @if (in_array(intval($e->id), $ids_consultorios_procedimiento))
                                                        <td class="text-center">
                                                            <a class="btn btn-warning" title="Turnos de procedimientos programados"
                                                                href="/add_procprogramados_turnos/{{ $e->id }}/{{ $id_servicio }}"><i
                                                                    class="fa fa-medkit"></i></a>
                                                        </td>
                                                    @elseif ($e->id_establecimiento == 76)
                                                        <td class="text-center">
                                                            <a class="btn btn-primary"
                                                                href="/add_consultorio_turnos_dia/{{ $e->id }}/{{ $id_servicio }}"><i
                                                                    class="fa fa-medkit"></i></a>
                                                        </td>
                                                    @else
                                                        <td class="text-center">
                                                            <a class="btn btn-primary"
                                                                href="/add_consultorio_turnos/{{ $e->id }}/{{ $id_servicio }}"><i
                                                                    class="fa fa-medkit"></i></a>
                                                        </td>
                                                    @endif
                                                    <!--td>
                                                        <button id="addProcedimiento" class="btn btn-xs btn-primary">
                                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                                        </button>
                                                    </td-->

                                                    <?php if ($existe == 1): ?>
                                                    <td class="text-center">
                                                        <?php if($e->id_estado_reg == "1"){ ?>
                                                        <a class="btn btn-success" href="javascript:void(0)"
                                                            onclick="desactivar(<?php echo $e->id; ?>,<?php echo $e->id_estado_reg; ?>)"><i
                                                                class="fa fa-thumbs-up"></i></a>
                                                        <?php } else {?>
                                                        <a class="btn btn-danger" href="javascript:void(0)"
                                                            onclick="desactivar(<?php echo $e->id; ?>,<?php echo $e->id_estado_reg; ?>)"><i
                                                                class="fa fa-thumbs-down"></i></a>
                                                        <?php }?>
                                                    </td>
                                                    <?php endif; ?>
                                                </tr>
                                            @endforeach
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
