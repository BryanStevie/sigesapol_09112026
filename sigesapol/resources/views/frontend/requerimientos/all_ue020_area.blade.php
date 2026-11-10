@extends('frontend.layouts.master')
@section('renderjs')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        /*************Styles de sweetAlert2*************/
        .swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen)>.swal2-modal {
            margin: 15rem auto auto auto !important;
        }

        .swal2-popup {
            width: 50em !important;
        }

        .swal2-popup,
        .swal2-header {
            padding: 0 !important;
        }

        .swal2-header {
            background: #3c763d;
        }

        .swal2-html-container {
            margin: 0 !important;
            padding: 1rem 0 !important;
        }

        .titulo-padding {
            padding: 1rem 0 0 0;
        }

        .titulo-padding p {
            color: white;
            margin: 0;
            font-size: 2.5rem;
        }

        .descripcion-alert {
            font-size: 1.5rem;
            padding: 0 1rem;
        }

        .descripcion-alert {
            margin: 0;
        }

        .descripcion-alert span {
            font-weight: 700;
        }

        .swal2-actions {
            margin: 0 !important;
            padding: 1rem;
            justify-content: space-evenly;
        }

        .swal2-actions button {
            margin: 0 !important;
        }

        .borde-alerta {
            border-color: red !important;
            border: 2px red solid !important;
            outline: 0;
            border-radius: 4px;
            box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important;
        }

        /* .form-check-label {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    display: inline-flex;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    margin: 0;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                .form-check-label > input {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    display: flex;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    padding-right: 5px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    margin: 0;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                } */

        .table-responsive {
            margin-top: 10px;
        }

        a[data-toggle="tab"] {
            color: #000 !important;
            /* texto negro siempre */
            /* fondo claro */
            border-radius: 0 !important;
            font-weight: 800;
            border-bottom: 1px !important;
        }

        .header-filters {
            margin-bottom: 15px;
        }

        .panel_custom-body {
            padding: 35px;
        }

        .table-filter_1 {
            border: 1px solid #ddd;
            margin: 0;
        }

        .container_filter-1 {
            display: flex;
            padding: 25px;
            border: 1px solid #ddd;
            border-top: 0;
        }

        .filter-1_table {
            width: 75%;
        }

        .filter-1_add {
            width: 25%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #servicio,
        #diagnostico,
        #ipress,
        #tipo,
        #responsables,
        #pacientes,
        #ubigeo,
        #auditoria {
            border: 1px solid #ddd;
            border-top: 0;
            padding: 20px;
            padding-top: 10px;
        }

        #pacientes,
        #ubigeo {
            padding-top: 20px;
            padding-bottom: 10px;
        }

        thead .filters th {
            text-align: center;
        }

        .mi-fieldset {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            margin: 15px;
        }

        .mi-fieldset legend {
            width: auto;
            padding: 0 10px;
            font-size: 14px;
            font-weight: 600;
            border: 0;
            margin: 0;
        }

        .modal-xxl {
            width: 1200px;
        }

        .modal-xl {
            width: 1000px;
        }

        #tableBandejaEntrada td,
        #tableBandejaSalida td,
        #tableBandejaProceso td {
            vertical-align: middle !important;
        }

        .btn-bandeja {
            border-radius: 8px;
            outline: 0;
            font-size: 15px;
        }

        .btn-bandeja:focus {
            outline: none;
            box-shadow: none;
        }

        .btn-bandeja:active {
            outline: none;
            box-shadow: none;
        }

        .col-center-vertical {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #container_button-recepcionar {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        #container_button-recepcionar>button {
            width: 100%;
        }

        .col-detalle-info {
            display: flex;
            justify-content: left;
            gap: 8px;
        }

        .content_requerimiento {
            background-color: #f8f5f5;
            padding: 6px 12px;
            border-radius: 4px;
        }

        .table_detalle {
            padding: 10 0px;
            border: 1px solid #ddd;
        }

        .row-flex {
            display: flex;
            align-items: stretch;
        }

        .modal-lg {
            width: 1050px;
        }
    </style>
    <script src="<?php echo URL::to('/'); ?>/js/js.util.grid.js"></script>
    <script>
        $(document).ready(function() {
            $('#modalDetalleEntrada').on('hidden.bs.modal', function() {
                $("#container_button-recepcionar").hide();
                $("#container_estado-recepcionado").hide();
                $("#container_estado-observado").hide();
                $("#container_button-enviar").hide();
                $("#container_estado-enviado").hide();
                $("#tblRowEntregas-det").html("");
                $("#container-observacion-proceso").hide("");
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $("#estado").html("");
                $("#estado").attr("disabled", false);
                var tabActiva = $(e.target).attr('href');
                var tabAnterior = $(e.relatedTarget).attr('href');

                if (tabActiva == "#tab1") {
                    /* $("#thead_recepcion").html("FEC. RECEPCION"); */
                    $("#estado").html(`
                                            <option value="2" selected="">POR RECEPCIONAR</option>
                                            <option value="6">OBSERVADO</option>`);
                }
                if (tabActiva == "#tab3") {
                    /* $("#thead_recepcion").html("FEC. RECEPCION"); */
                    $("#estado").html(`
                                            <option value="3" selected="">POR ENVIAR</option>
                                            <option value="2">ENVIADO</option>`);
                }
            });

            const tablaBandejaEntrada = $('#tableBandejaEntrada').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/listado_requerimientos_procesados-pfdm_paginado',
                    type: 'GET',
                    data: function(d) {
                        d.nro_requerimiento = $('#nroRequerimiento').val();
                        d.nroSgdHt = $('#nroSgdHt').val();
                        d.estado_actual = $('#estado').val();
                        d.tipo_producto_requerimiento = $('#tipo_producto_requerimiento').val();
                        d.prioridad_requerimiento = $('#prioridad_requerimiento').val();
                        d.rangoInicio = $('#rangoInicio').val();
                        d.rangoFin = $('#rangoFin').val();
                    }
                },
                paging: true,
                searching: false,
                info: true,
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10,
                ordering: false,
                autoWidth: false,
                language: {
                    zeroRecords: "No se encontraron resultados",
                    infoEmpty: "No hay registros disponibles",
                    paginate: {
                        previous: "Anterior",
                        next: "Siguiente"
                    }
                },
                dom: '<"top"l>rt<"bottom"ip><"clear">',
                columns: [{
                        data: 'requerimiento_id',
                        className: "text-center"
                    },
                    {
                        data: 'nro_requerimiento',
                        className: "text-center"
                    },
                    {
                        data: 'tipo_doc_requerimiento',
                        className: "text-center"
                    },
                    {
                        data: 'nro_doc_sgdht',
                        className: "text-center"
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {
                            return row.fecha_proceso_requerimiento ??
                                `<span style="font-style: italic;">Sin recepcionar.</span>`;
                        }
                    },
                    {
                        data: 'prioridad_requerimiento',
                        className: "text-center",
                        render: function(data, type, row) {
                            let color = '';

                            if (data == "BAJA") {
                                color = 'green';
                            } else if (data == "MEDIA") {
                                color = 'orange';
                            } else if (data == "ALTA") {
                                color = 'red';
                            }

                            return `<span style="color:${color}; font-weight:bold;">${data}</span>`;
                        }
                    },
                    {
                        data: 'tipoProduc_requerimiento',
                        className: "text-center"
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {
                            return "<span style='font-weight: bold;'>UGPFDMPS</span>";
                        }
                    },
                    {
                        data: 'registrador_requerimiento',
                        className: "text-center"
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {
                            if (row.estado_requerimiento == 2) {
                                return `
                                        <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: #d70202; color: white; font-weight: bold;">POR RECEPCIONAR</p>
                                    `;
                            } else if (row.estado_requerimiento == 3) {
                                return `
                                        <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: #54b554; color: white; font-weight: bold;">RECEPCIONADO <i class="fa fa-flag"></i></p>
                                    `;
                            } else if (row.estado_requerimiento == 6) {
                                return `
                                        <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: yellow; color: black; font-weight: bold;">OBSERVADO <i class="fa fa-flag"></i></p>
                                    `;
                            }
                        },
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {
                            return `
                                    <button class="btn btn-info btn-bandeja btn-detalle-proceso" 
                                            data-id="${row.requerimiento_id}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                `;
                        },
                    },
                ]
            });

            const tablaBandejaSalida = $('#tableBandejaSalida').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/listado_requerimientos_recibidos_abast',
                    type: 'GET',
                    data: function(d) {
                        let tabActivo = document.querySelector('li.active a[data-toggle="tab"]');
                        if (tabActivo.getAttribute('href') == "#tab1") {
                            d.estado_actual = 3;
                        } else if (tabActivo.getAttribute('href') == "#tab3") {
                            d.estado_actual = $('#estado').val();
                        }
                        d.nro_requerimiento = $('#nroRequerimiento').val();
                        d.nroSgdHt = $('#nroSgdHt').val();
                        d.tipo_producto_requerimiento = $('#tipo_producto_requerimiento').val();
                        d.prioridad_requerimiento = $('#prioridad_requerimiento').val();
                        d.rangoInicio = $('#rangoInicio').val();
                        d.rangoFin = $('#rangoFin').val();
                    }
                },
                paging: true,
                searching: false,
                info: true,
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10,
                ordering: false,
                autoWidth: false,
                language: {
                    zeroRecords: "No se encontraron resultados",
                    infoEmpty: "No hay registros disponibles",
                    paginate: {
                        previous: "Anterior",
                        next: "Siguiente"
                    }
                },
                dom: '<"top"l>rt<"bottom"ip><"clear">',
                columns: [{
                        data: 'requerimiento_id',
                        className: "text-center"
                    },
                    {
                        data: 'nro_requerimiento',
                        className: "text-center"
                    },
                    {
                        data: 'tipo_doc_requerimiento',
                        className: "text-center"
                    },
                    {
                        data: 'nro_doc_sgdht',
                        className: "text-center"
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {
                            return row.fecha_proceso_requerimiento ??
                                `<span style="font-style: italic;">Sin recepcionar.</span>`;
                        }
                    },
                    {
                        data: 'prioridad_requerimiento',
                        className: "text-center",
                        render: function(data, type, row) {
                            let color = '';

                            if (data == "BAJA") {
                                color = 'green';
                            } else if (data == "MEDIA") {
                                color = 'orange';
                            } else if (data == "ALTA") {
                                color = 'red';
                            }

                            return `<span style="color:${color}; font-weight:bold;">${data}</span>`;
                        }
                    },
                    {
                        data: 'tipoProduc_requerimiento',
                        className: "text-center"
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {
                            return "<span style='font-weight: bold;'>UGPFDMPS</span>";
                        }
                    },
                    {
                        data: 'registrador_requerimiento',
                        className: "text-center"
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {
                            if (row.estado_requerimiento == 3) {
                                return `
                                        <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: #d70202; color: white; font-weight: bold;">POR ENVIAR</p>
                                    `;
                            } else if (row.estado_requerimiento == 2) {
                                return `
                                        <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: #54b554; color: white; font-weight: bold;">ENVIADO <i class="fa fa-flag"></i></p>
                                    `;
                            }
                        },
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {
                            return `
                                    <button class="btn btn-info btn-bandeja btn-detalle-proceso" 
                                            data-id="${row.requerimiento_id}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                `;
                        },
                    },
                ]
            });

            const tablaDetalleRequerimiento = $('#tableDetalleRequerimiento').DataTable({
                paging: true,
                searching: false,
                info: true,
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10,
                ordering: false,
                autoWidth: false,
                language: {
                    zeroRecords: "No se encontraron resultados",
                    infoEmpty: "No hay registros disponibles",
                    paginate: {
                        previous: "Anterior",
                        next: "Siguiente"
                    }
                },
                dom: '<"top"l>rt<"bottom"ip><"clear">',
                columns: [{
                        data: 'requerimiento_id',
                        className: "text-center"
                    },
                    {
                        data: 'codigo',
                        className: "text-center"
                    },
                    {
                        data: 'descripcion_producto',
                        className: "text-center"
                    },
                    {
                        data: 'unidad_medida',
                        className: "text-center"
                    },
                    {
                        data: 'codigo_siga',
                        className: "text-center"
                    },
                    {
                        data: 'codigo_sismed',
                        className: "text-center"
                    },
                    {
                        data: 'nro_entrega',
                        className: "text-center"
                    },
                    {
                        data: 'total',
                        className: "text-center"
                    },
                ],
                footerCallback: function(row, data, start, end, display) {}
            });

            $('#btnBuscar').on('click', function() {
                let tabActivo = $('.nav-tabs li.active a').attr('href');
                let tabla = $(tabActivo).find('table');
                tabla.DataTable().ajax.reload();
            });
        });

        $("#btnEnviarSeccion").on("click", function(e) {
            $("#modalEnviarRequerimientoSeccionProgramacion").modal("show");
        });

        function cargarDetalleRequerimientoProceso(data) {
            $("#btnEnviarRequerimiento").prop("disabled", true);
            $("#info_req-anulado").show();
            $("#nro_req-det").html(data.numero_requerimiento);
            $("#fecha_registro-det").html(data.created_at);
            $("#tipo_producto-det").html(data.tipo_producto?.nombre);
            $("#nro_pedido_siga-det").html(data.numero_pedido_siga);
            $("#denominacion_req-det").html(data.denominacion);
            $("#pfdm_nombre-det").val(`${data.producto.codigo} - ${data.producto.desc_trama ?? data.producto.nombre}`);
            $("#codigo_siga-producto-det").val(data.producto.codigo_siga);
            $("#codigo_sismed-producto-det").val(data.producto.codigo_sismed);
            $("#unidad_medida-producto-det").val(data.unidad_medida);
            $("#cantidad_total-det").val(data.cantidad_total);
            const movimientos = data.movimientos;
            movimientos.forEach((mov) => {
                if (mov.orden == 4) {
                    $("#observacion_proceso-det").val(mov.observacion);
                }
                if (mov.orden == null) {
                    $("#observacion_proceso-det").val(mov.observacion);
                }
            });
            $("#req_id-det").val(data.id);
            const detalles = data.detalles;
            detalles.forEach((detalle) => {
                $("#tblRowEntregas-det").append(`<tr>
                    <td>
                        <div>
                            <span>Nro Entrega ${detalle.numero_entrega}</span>
                            <input type="hidden" id="numero_entrega1" name="numero_entrega[]" value="1">
                        </div>
                    </td>
                    <td>
                        <div>
                            <span>Cantidad</span>
                        </div>
                        <div>
                            <input type="text" data-placement="top"
                                class="input-sm form-control" style="width:100%;" disabled value="${detalle.cantidad_parcial}">
                        </div>
                    </td>
                    <td>
                        <div>
                            <span>N° Dias de Entrega</span>
                        </div>
                        <div>
                            <input type="text" data-placement="top"
                                class="input-sm form-control" style="width:100%;" disabled value="${detalle.numero_dias}">
                        </div>
                    </td>
                </tr>`);
            });
            if (data.estado_id == 2) {
                if (data.ubicacion_id == 2) {
                    $("#container_button-recepcionar").show();
                } else if (data.ubicacion_id == 3) {
                    $("#container_estado-enviado").show();
                }
            } else if (data.estado_id == 3) {
                if (data.ubicacion_id == 3) {
                    $("#container_button-enviar").show();
                } else if (data.ubicacion_id == 2) {
                    $("#container_estado-recepcionado").show();
                }
            } else if (data.estado_id == 5) {
                $("#container_estado-enviado").show();
            } else if (data.estado_id == 6) {
                $("#container_estado-observado").show();
            }
        }

        $("#btnRecepcionarEntrada").on("click", function(e) {
            const tableBandejaEntrada = $("#tableBandejaEntrada").DataTable();
            const tableBandejaSalida = $("#tableBandejaSalida").DataTable();

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'No, cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/requerimientos_pfdm/recepcionar_abastecimiento',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            req_id: $('#req_id-det').val(),
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Actualizando...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualizado',
                                text: 'Los cambios se guardaron correctamente'
                            });
                            $('#modalDetalleEntrada').modal('hide');
                            tableBandejaEntrada.ajax.reload();
                            tableBandejaSalida.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un problema al actualizar'
                            });
                        }
                    });
                } else if (result.isDismissed) {
                    console.log('Cancelado');
                }
            });
        });

        $("#btnGuardarObservacion").on("click", function(e) {
            const tableBandejaEntrada = $("#tableBandejaEntrada").DataTable();
            const tableBandejaSalida = $("#tableBandejaSalida").DataTable();

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'No, cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/requerimientos_pfdm/observar_requerimiento_abast',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            req_id: $('#req_id-det').val(),
                            observacion: $('#obs_abast').val(),
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Actualizando...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualizado',
                                text: 'Los cambios se guardaron correctamente'
                            });
                            $('#modalDetalleEntrada').modal('hide');
                            $('#modalObservacionRequerimiento').modal('hide');
                            tableBandejaEntrada.ajax.reload();
                            tableBandejaSalida.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un problema al actualizar'
                            });
                        }
                    });
                } else if (result.isDismissed) {
                    console.log('Cancelado');
                }
            });
        });

        $(document).on('click', '.btn-detalle-entrada', function() {
            $("#modalDetalleEntrada").modal("show");
            $("#tituloModalDetalleEntrada").html("Detalle del Requerimiento");
        });
        $(document).on('click', '.btn-observar-detalle-requerimiento_entrada', function() {
            /* $("#modalEnviarRequerimientoSeccionProgramacion").modal("show"); */
            $("#modalObservacionRequerimiento").modal("show");
            $("#tituloModalObservacionRequerimiento").html("Observacion del Requerimiento");
        });

        $('#destino_responsable').select2({
            dropdownParent: $('#modalEnviarRequerimientoSeccionProgramacion'),
            width: '100%',
            placeholder: 'Busque por nombre o dni del responsable...',
            minimumInputLength: 2,
            ajax: {
                url: '/buscar-responsable',
                type: 'GET',
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(item => ({
                            id: item.id,
                            text: `${item.dni} - ${item.name}, ${item.paterno} ${item.materno}`,
                        }))
                    };
                },
                cache: true
            }
        });

        $('#destino_responsable').on('select2:select', function(e) {
            let data = e.params.data;

            $('#responsable_id').val(data.id || '');
            $(".required_pfdm").next().hide();
        });

        $('#modalEnviarRequerimientoSeccionProgramacion').on('hidden.bs.modal', function() {
            $('#destino_responsable').val(null).trigger('change');
            $('#responsable_id').val("");
            $('#denominacion_requerimiento-enviar').val("");
        });

        $(document).on('click', '.btn-detalle-proceso', function() {
            $("#tituloModalDetalleEntrada").html("Detalle del Requerimiento");
            const req_id = $(this).data("id");

            $.ajax({
                url: `/ver-requerimiento_pfdm/${req_id}`,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Cargando informacion...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message
                    });
                    $("#modalDetalleEntrada").modal("show");
                    const req_data = response.data ?? null;
                    $("#container-observacion-proceso").show("");
                    if (req_data) {
                        cargarDetalleRequerimientoProceso(response.data);
                    } else {
                        alert("No existe data, reportar error!!!");
                    }
                },
                error: function() {
                    let errors = xhr.responseJSON.errors;
                    let mensaje = 'Ocurrió un error';

                    if (errors) {
                        mensaje = Object.values(errors).map(e => e[0]).join('<br>');
                    } else if (xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: mensaje
                    });
                },
            });
        });

        $(document).on('click', '.btn-detalle-salida', function() {
            $("#modalDetalleSalida").modal("show");
            $("#tituloModalDetalleSalida").html("Detalle del Requerimiento");
        });

        $(document).on('click', '.btn-enviar-proceso', function() {
            $("#modalDestinoProceso").modal("show");
            $("#tituloModalDestinoProceso").html("Destino del Requerimiento");
        });

        $("#btnEnviarReqSeccion").on("click", function(e) {
            const tableBandejaEntrada = $("#tableBandejaEntrada").DataTable();
            const tableBandejaSalida = $("#tableBandejaSalida").DataTable();

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'No, cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/requerimientos_pfdm/enviar_req_seccion',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            req_id: $('#req_id-det').val(),
                            responsable_id: $('#responsable_id').val(),
                            observacion: $('#denominacion_requerimiento-enviar').val(),
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Actualizando...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualizado',
                                text: 'Los cambios se guardaron correctamente'
                            });
                            $('#modalDetalleEntrada').modal('hide');
                            $("#modalEnviarRequerimientoSeccionProgramacion").modal("hide");
                            tableBandejaEntrada.ajax.reload();
                            tableBandejaSalida.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un problema al actualizar'
                            });
                        }
                    });
                } else if (result.isDismissed) {
                    console.log('Cancelado');
                }
            });
        });
    </script>
@endsection
@section('content')
    <div class="panel panel-primary filterable">

        <div class="panel-heading">
            <h3 class="panel-title" style="font-size: 13px !important">Unidad UE 020: Area de Abastecimiento</h3>
        </div>

        <div class="panel-body">

            <ul class="nav nav-tabs" id="tabsBandeja">
                <li class="active">
                    <a data-toggle="tab" href="#tab1">BANDEJA ENTRADA</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab3">BANDEJA SALIDA</a>
                </li>
            </ul>

            <div class="tab-content" style="border: 1px solid #ddd; overflow: hidden; border-top: 0;">
                <div class="col-sm-12" style="margin-top: 15px;">
                    <form id="frmUE020Area" class="form-horizontal" method="post" action="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label><small>Fecha Inicio</small></label>
                                        <input type="date" class="form-control" id="rangoInicio" name="rangoInicio"
                                            placeholder="">
                                    </div>
                                    <div class="col-md-2">
                                        <label><small>Fecha Fin</small></label>
                                        <input type="date" class="form-control" id="rangoFin" name="rangoFin"
                                            placeholder="">
                                    </div>
                                    <div class="col-md-2">
                                        <label><small>Numero de Requerimiento</small></label>
                                        <input type="text" class="form-control" id="nroRequerimiento"
                                            name="nroRequerimiento" placeholder="Ingrese nro.">
                                    </div>
                                    <div class="col-md-2">
                                        <label><small>Prioridad</small></label>
                                        <select class="form-control" id="prioridad_requerimiento"
                                            name="prioridad_requerimiento">
                                            <option value="" selected>--- Seleccionar ---</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label><small>Tipo Producto</small></label>
                                        <select class="form-control" id="tipo_producto_requerimiento"
                                            name="tipo_producto_requerimiento">
                                            <option value="" selected>--- Seleccionar ---</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label><small>Estado</small></label>
                                        <select class="form-control" id="estado">
                                            <option value="2" selected>POR RECEPCIONAR</option>
                                            <option value="6">OBSERVADO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><small>Numero de SGD/HT</small></label>
                                        <input type="text" class="form-control" id="nroSgdHt" name="nroSgdHt"
                                            placeholder="Ingrese nro. SGD/HT">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label style="visibility:hidden;"><small>Espacio</small></label>
                                        <button type="button" id="btnBuscar" class="btn btn-success form-control">
                                            Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade in active" id="tab1">
                    <div class="col-sm-12" style="margin-top: 15px;">
                        <table id="tableBandejaEntrada" class="table table-hover" style="font-size: 12px !important;">
                            <thead>
                                <tr class="filters">
                                    <th style="text-align:center">N °</th>
                                    <th style="text-align:center">NRO. REQUERIMIENTO</th>
                                    <th style="text-align:center">TIPO DOC.</th>
                                    <th style="text-align:center">NRO DOC (SGD/HT).</th>
                                    <th style="text-align:center">FEC. RECEPCION</th>
                                    <th style="text-align:center">PRIORIDAD</th>
                                    <th style="text-align:center">TIPO PRODUC.</th>
                                    <th style="text-align:center">AREA ORIGEN</th>
                                    <th style="text-align:center">REGISTRADOR</th>
                                    <th style="text-align:center">SITUACION</th>
                                    <th style="text-align:center">VER DETALLE</th>
                                </tr>
                            </thead>
                            <tbody id="tblRowsBandejaEntrada" style="font-size: 11px !important;">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade in" id="tab3">
                    <div class="col-sm-12" style="margin-top: 15px;">
                        <table id="tableBandejaSalida" class="table table-hover" style="font-size: 12px !important;">
                            <thead>
                                <tr class="filters">
                                    <th style="text-align:center">N °</th>
                                    <th style="text-align:center">NRO. REQUERIMIENTO</th>
                                    <th style="text-align:center">TIPO DOC.</th>
                                    <th style="text-align:center">NRO DOC (SGD/HT).</th>
                                    <th style="text-align:center">FEC. RECEPCION</th>
                                    <th style="text-align:center">PRIORIDAD</th>
                                    <th style="text-align:center">TIPO PRODUC.</th>
                                    <th style="text-align:center">AREA DESTINO</th>
                                    <th style="text-align:center">REGISTRADOR</th>
                                    <th style="text-align:center">SITUACION</th>
                                    <th style="text-align:center">VER DETALLE</th>
                                </tr>
                            </thead>
                            <tbody id="tblRowsBandejaSalida" style="font-size: 11px !important;">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal fade" id="modalDetalleEntrada" tabindex="-1" role="dialog" aria-labelledby=""
        aria-hidden="true">
        <input type="hidden" id="req_id-det">

        <div class="modal-dialog modal-xxl">

            <div class="modal-content">

                <div class="modal-header"
                    style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModalDetalleEntrada"></h4>
                </div>

                <div class="modal-body">

                    <form class="row" id="frmSubmitReq">
                        <div class="col-md-12">
                            <div class="row row-flex">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Requerimiento: </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="nro_req-det">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Fecha y hora de registro:
                                                        </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="fecha_registro-det">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Tipo de Producto: </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="tipo_producto-det">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Pedido SIGA: </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="nro_pedido_siga-det">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="container_button-recepcionar" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <button class="btn btn-success" type="button"
                                        id="btnRecepcionarEntrada">RECEPCIONAR</button>
                                    <button class="btn btn-danger btn-observar-detalle-requerimiento_entrada"
                                        type="button">OBSERVAR</button>
                                </div>
                                <div id="container_estado-recepcionado" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <p class="" style="font-weight: bold; color: #3c763d; font-style: italic;">
                                        RECEPCIONADO</p>
                                </div>
                                <div id="container_estado-observado" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <p class="" style="font-weight: bold; color: red; font-style: italic;">
                                        OBSERVADO</p>
                                </div>
                                <div id="container_button-enviar" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <button type="button" id="btnEnviarSeccion" class="btn btn-success">ENVIAR</button>
                                </div>
                                <div id="container_estado-enviado" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <p class="" style="font-weight: bold; color: #3c763d; font-style: italic;">
                                        ENVIADO</p>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="" class="control-label">Denominacion del
                                                Requerimiento</label>
                                            <textarea class="form-control" disabled id="denominacion_req-det">Este es un texto largo que viene del backend...</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <fieldset class="mi-fieldset">

                                <div class="row">
                                    <div class="col-sm-12" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="pfdm_nombre-det" class="control-label">Producto Requerido</label>
                                            <input type="text" id="pfdm_nombre-det" name="pfdm_nombre-det"
                                                class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="container_inputs-pfdm">

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="codigo_siga-producto-det" class="control-label">Codigo
                                                SIGA</label>
                                            <input type="text" class="form-control" id="codigo_siga-producto-det"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="codigo_sismed-producto-det" class="control-label">Codigo
                                                SISMED</label>
                                            <input type="text" class="form-control" disabled
                                                id="codigo_sismed-producto-det">
                                        </div>
                                    </div>

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="unidad_medida-producto-det" class="control-label">Unidad
                                                Medida</label>
                                            <input type="text" class="form-control" disabled
                                                id="unidad_medida-producto-det">
                                        </div>
                                    </div>

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="cantidad_total-det" class="control-label">Cantidad Total</label>
                                            <input type="text" class="form-control" id="cantidad_total-det"
                                                name="cantidad_total-det" disabled>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <fieldset class="mi-fieldset" style="padding: 15px 10px; margin-bottom: 0;"
                                        id="fieldsetTableEntregas">
                                        <table class="display" id="tableEntregas-det" cellspacing="0" width="100%">
                                            <tbody id="tblRowEntregas-det" class="fill-rows_required">
                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-sm-12" id="container-observacion-proceso" style="display: none;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="observacion_proceso-det" class="control-label">Observacion del
                                                proceso</label>
                                            <textarea class="form-control required" name="observacion_proceso-det" id="observacion_proceso-det" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetalleProceso" tabindex="-1" role="dialog" aria-labelledby=""
        aria-hidden="true">

        <div class="modal-dialog modal-xxl">

            <div class="modal-content">

                <div class="modal-header"
                    style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModalDetalleProceso"></h4>
                </div>

                <div class="modal-body">

                    <form class="row" id="frmSubmitReq">
                        <div class="col-md-12">
                            <div class="row row-flex">
                                <div class="col-md-10">
                                    <div class="row" style="margin-bottom: 6px;">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Requerimiento: </label>
                                                        <p class="form-control-static content_requerimiento">REQ-000123</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Fecha: </label>
                                                        <p class="form-control-static content_requerimiento">REQ-000123</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Tipo de Producto: </label>
                                                        <p class="form-control-static content_requerimiento">REQ-000123</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Pedido SIGA: </label>
                                                        <p class="form-control-static content_requerimiento">REQ-000123</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="container_button-recepcionar" class="col-md-2 col-center-vertical">
                                    <button class="btn btn-success btn-enviar-proceso" type="button">ENVIAR</button>
                                </div>
                                <div id="container_estado-recepcionar" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <p class="" style="font-weight: bold; color: #3c763d; font-style: italic;">
                                        ENVIADO</p>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="" class="control-label">Denominacion del
                                                Requerimiento</label>
                                            <textarea class="form-control" disabled>Este es un texto largo que viene del backend...</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <table id="tableDetalleRequerimiento" class="table table-hover table_detalle"
                                    style="font-size: 12px !important;">
                                    <thead>
                                        <tr class="filters">
                                            <th style="text-align:center">N °</th>
                                            <th style="text-align:center">Cod.</th>
                                            <th style="text-align:center">Descripcion Produc.</th>
                                            <th style="text-align:center">Unid. Med.</th>
                                            <th style="text-align:center">Cod. SIGA</th>
                                            <th style="text-align:center">Cod. SISMED</th>
                                            <th style="text-align:center">Nro. Entrega</th>
                                            <th style="text-align:center">Cantidad Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblRowsDetalleRequerimiento" style="font-size: 11px !important;"></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align:right; font-size: 11px !important;">
                                                TOTAL:</th>
                                            <th id="totalGeneral" style="text-align: center;">99999</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetalleSalida" tabindex="-1" role="dialog" aria-labelledby=""
        aria-hidden="true">

        <div class="modal-dialog modal-xxl">

            <div class="modal-content">

                <div class="modal-header"
                    style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModalDetalleSalida"></h4>
                </div>

                <div class="modal-body">

                    <form class="row" id="frmSubmitReq">
                        <div class="col-md-12">
                            <div class="row row-flex">
                                <div class="col-md-10">
                                    <div class="row" style="margin-bottom: 6px;">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Requerimiento: </label>
                                                        <p class="form-control-static content_requerimiento">REQ-000123</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Fecha: </label>
                                                        <p class="form-control-static content_requerimiento">REQ-000123</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Tipo de Producto: </label>
                                                        <p class="form-control-static content_requerimiento">REQ-000123</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Pedido SIGA: </label>
                                                        <p class="form-control-static content_requerimiento">REQ-000123</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Destino: </label>
                                                        <p class="form-control-static content_requerimiento">REQ-000123</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Enviado Por: </label>
                                                        <p class="form-control-static content_requerimiento">REQ-000123</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="container_estado-recepcionar" class="col-md-2 col-center-vertical">
                                    <p class="" style="font-weight: bold; color: #3c763d; font-style: italic;">
                                        ENVIADO</p>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="" class="control-label">Denominacion del
                                                Requerimiento</label>
                                            <textarea class="form-control" disabled>Este es un texto largo que viene del backend...</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <table id="tableDetalleRequerimiento" class="table table-hover table_detalle"
                                    style="font-size: 12px !important;">
                                    <thead>
                                        <tr class="filters">
                                            <th style="text-align:center">N °</th>
                                            <th style="text-align:center">Cod.</th>
                                            <th style="text-align:center">Descripcion Produc.</th>
                                            <th style="text-align:center">Unid. Med.</th>
                                            <th style="text-align:center">Cod. SIGA</th>
                                            <th style="text-align:center">Cod. SISMED</th>
                                            <th style="text-align:center">Nro. Entrega</th>
                                            <th style="text-align:center">Cantidad Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblRowsDetalleRequerimiento" style="font-size: 11px !important;"></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align:right; font-size: 11px !important;">
                                                TOTAL:</th>
                                            <th id="totalGeneral" style="text-align: center;">99999</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="" class="control-label">Observacion</label>
                                        <textarea class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <div style="margin-top: 20px;" class="modal fade" id="modalObservacionRequerimiento" tabindex="-1" role="dialog"
        aria-labelledby="" aria-hidden="true">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header"
                    style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModalObservacionRequerimiento"></h4>
                </div>

                <div class="modal-body">

                    <form class="row" id="frmSubmitReq">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="" class="control-label">Observacion</label>
                                            <textarea class="form-control" id="obs_abast"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-control-static" style="visibility:hidden;">Espacio</label>
                                        <button class="btn btn-success btn-block" type="button"
                                            id="btnGuardarObservacion">GUARDAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <div style="margin-top: 20px;" class="modal fade" id="modalDestinoProceso" tabindex="-1" role="dialog"
        aria-labelledby="" aria-hidden="true">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header"
                    style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModalDestinoProceso"></h4>
                </div>

                <div class="modal-body">

                    <form class="row" id="frmSubmitReq">
                        <div class="col-md-12">
                            <div class="row row-flex">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="form-control-static">Unidad</label>
                                                    <select name="" id="" class="form-control">
                                                        <option value="">--- Seleccionar ---</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="form-control-static">Area</label>
                                                    <select name="" id="" class="form-control">
                                                        <option value="">--- Seleccionar ---</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-control-static">Fec. Registro</label>
                                                    <input type="date" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label class="form-control-static">Responsable</label>
                                                    <select name="" id="" class="form-control">
                                                        <option value="">--- Seleccionar ---</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-static"
                                                        style="visibility:hidden;">Espacio</label>
                                                    <button class="btn btn-success btn-block"
                                                        type="button">GUARDAR</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="" class="control-label">Observacion</label>
                                            <textarea class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade modal-center" id="modalEnviarRequerimientoSeccionProgramacion" tabindex="-1" role="dialog"
        aria-labelledby="" aria-hidden="true">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <div class="modal-header"
                    style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModalEnviarProceso">Destino del requerimiento</h4>
                </div>

                <div class="modal-body">

                    <form class="row" id="frmSubmitEnviar">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="destino_unidad" class="control-label">Unidad</label>
                                <select name="destino_unidad" id="destino_unidad" class="form-control required" disabled>
                                    <option value="">--- Seleccionar ---</option>
                                    <option selected>UE 020</option>
                                </select>
                                <input type="hidden" id="envio-unidad_id" name="envio-unidad_id" class="required_pfdm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="destino_area" class="control-label">Area</label>
                                <select name="destino_area" id="destino_area" class="form-control required" disabled>
                                    <option value="">--- Seleccionar ---</option>
                                    <option selected>ABASTECIMIENTO</option>
                                </select>
                                <input type="hidden" id="envio-area_id" name="envio-area_id" class="required_pfdm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="destino_seccion" class="control-label">Seccion</label>
                                <select name="destino_seccion" id="destino_seccion" class="form-control required"
                                    disabled>
                                    <option value="">--- Seleccionar ---</option>
                                    <option selected>PROGRAMACION Y ADQUISICIONES</option>
                                </select>
                                <input type="hidden" id="envio-seccion_id" name="envio-seccion_id"
                                    class="required_pfdm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="destino_etapa" class="control-label">Etapa</label>
                                <select name="destino_etapa" id="destino_etapa" class="form-control required" disabled>
                                    <option value="">--- Seleccionar ---</option>
                                    @foreach ($etapas as $etapa)
                                        <option @if ($etapa->id == 6) {{ 'selected' }} @endif
                                            value="{{ $etapa->id }}">{{ $etapa->nombre }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="envio-etapa_id" name="envio-etapa_id" class="required_pfdm">
                            </div>
                        </div>

                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="destino_responsable" class="control-label">Responsable</label>
                                <select name="destino_responsable" id="destino_responsable"
                                    class="form-control required">
                                    <option value="">--- Seleccionar ---</option>
                                </select>
                                <input type="hidden" id="responsable_id" name="responsable_id" class="required_pfdm">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="" class="control-label"
                                    style="visibility:hidden;">Responsable</label>
                                <button class="btn btn-success" type="button" id="btnEnviarReqSeccion">Guardar</button>
                            </div>
                        </div>

                        <br>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="denominacion_requerimiento-enviar" class="control-label">Observacion</label>
                                <textarea class="form-control required" name="denominacion_requerimiento-enviar"
                                    id="denominacion_requerimiento-enviar"></textarea>
                            </div>
                        </div>

                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
    @include('frontend.includes.footer')
@endsection