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

        .modal-lg-2 {
            width: 1110px;
        }

        .modal.modal-center .modal-dialog {
            position: fixed;
            top: 21%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .select2-container--default .select2-selection--single {
            height: 34px;
            padding: 6px 12px;
            border: 1px solid #ccc;
        }

        .select2-container .select2-selection--single {
            height: 34px;
            /* altura que quieres */
            display: flex;
            align-items: center;
            /* centra el texto verticalmente */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px;
            /* mismo valor que el height */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px;
            /* ajusta la flecha */
        }

        /* #modalDetalleProceso {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                position: relative;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            }

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            #modalDestinoProceso {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                position: absolute;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                top: 50%;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                left: 50%;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                transform: translate(-50%, -50%);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } */
    </style>
    <script src="<?php echo URL::to('/'); ?>/js/js.util.grid.js"></script>
    <script>
        $(document).ready(function () {
            $('#modalDetalleEntrada').on('hidden.bs.modal', function () {
                $("#container_estado-recepcionado").hide();
                $("#container_button-recepcionar").hide();
                $("#container_button-enviar").hide();
                $("#container_estado-enviado").hide();
                $("#tblRowEntregas-det").html("");
            });

            $('#modalDetalleSalida').on('hidden.bs.modal', function () {
                $("#container_button-enviar_3").hide();
                $("#container_estado-enviado_3").hide();
                $("#container-destino-salida_3").hide();
                $("#tblRowEntregas-det_3").html("");
                $('#destino_responsable_3').val(null).trigger('change');
                $('#responsable_id_3').val("");
                $('#denominacion_requerimiento-enviar_3').val("");
            });

            $('#modalEnviarProceso').on('hidden.bs.modal', function () {
                $('#destino_responsable').val(null).trigger('change');
                $('#responsable_id').val("");
                $('#denominacion_requerimiento-enviar').val("");
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $("#estadoRequerimiento").html("");
                $("#estadoRequerimiento").attr("disabled", false);
                var tabActiva = $(e.target).attr('href');
                var tabAnterior = $(e.relatedTarget).attr('href');

                if (tabActiva == "#tab1") {
                    $("#thead_recepcion").html("FEC. RECEPCION");
                    $("#estadoRequerimiento").html(`
                                                                                <option value="2" selected="">NO RECEPCIONADO</option>
                                                                                <option value="3">RECEPCIONADO</option>`);
                }
                if (tabActiva == "#tab2") {
                    $("#thead_recepcion").html("FEC. RECEPCION");
                    $("#estadoRequerimiento").attr("disabled", true);
                    $("#estadoRequerimiento").html(`
                                                                                <option value="">-- Deshabilitado --</option>`);
                }
                if (tabActiva == "#tab3") {
                    $("#thead_recepcion").html("FEC. SALIDA");
                    $("#estadoRequerimiento").html(`
                                                                                <option value="9">EN PROCESO</option>
                                                                                <option value="2">ENVIADO</option>`);
                }
            });

            $('#destino_responsable').select2({
                dropdownParent: $('#modalEnviarProceso'),
                width: '100%',
                placeholder: 'Busque por nombre o dni del responsable...',
                minimumInputLength: 2,
                ajax: {
                    url: '/buscar-responsable',
                    type: 'GET',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
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

            $('#destino_responsable').on('select2:select', function (e) {
                let data = e.params.data;

                $('#responsable_id').val(data.id || '');
                $(".required_pfdm").next().hide();
            });

            $('#destino_responsable_3').select2({
                dropdownParent: $('#modalDetalleSalida'),
                width: '100%',
                placeholder: 'Busque por nombre o dni del responsable...',
                minimumInputLength: 2,
                ajax: {
                    url: '/buscar-responsable',
                    type: 'GET',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
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

            $('#destino_responsable_3').on('select2:select', function (e) {
                let data = e.params.data;

                $('#responsable_id_3').val(data.id || '');
                $(".required_pfdm").next().hide();
            });

            const tablaBandejaEntrada = $('#tableBandejaEntrada').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/listado_requerimientos_entrada_pc',
                    type: 'GET',
                    data: function (d) {
                        d.estado_actual = $('#estadoRequerimiento').val() != "" ? $(
                            '#estadoRequerimiento').val() : 2;
                        d.nro_requerimiento = $('#nroRequerimiento').val();
                        d.nroSgdHt = $('#nroSgdHt').val();
                        d.registrador_requerimiento = $('#registrador_requerimiento').val();
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
                    render: function (data, type, row) {
                        return row.fecha_recepcion_requerimiento ??
                            `<span style="font-style: italic;">Sin recepcionar.</span>`;
                    }
                },
                {
                    data: 'prioridad_requerimiento',
                    className: "text-center",
                    render: function (data, type, row) {
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
                    render: function (data, type, row) {
                        return "<span style='font-weight: bold;'>UE020</span>";
                    }
                },
                {
                    data: 'registrador_requerimiento',
                    className: "text-center"
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row) {
                        if (row.estado_requerimiento == 2) {
                            return `
                                                                            <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: #d70202; color: white; font-weight: bold;">POR RECEPCIONAR</p>
                                                                        `;
                        } else if (row.estado_requerimiento == 3) {
                            return `
                                                                            <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: #54b554; color: white; font-weight: bold;">RECEPCIONADO <i class="fa fa-flag"></i></p>
                                                                        `;
                        }
                    },
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row) {
                        return `
                                                                        <button class="btn btn-info btn-bandeja btn-detalle-entrada" 
                                                                                data-id="${row.requerimiento_id}">
                                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        </button>
                                                                    `;
                    },
                },
                ]
            });

            const tablaBandejaProceso = $('#tableBandejaProceso').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/listado_requerimientos_procesos_pc',
                    type: 'GET',
                    data: function (d) {
                        d.nro_requerimiento = $('#nroRequerimiento').val();
                        d.nroSgdHt = $('#nroSgdHt').val();
                        d.registrador_requerimiento = $('#registrador_requerimiento').val();
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
                    render: function (data, type, row) {
                        return row.fecha_recepcion_requerimiento ??
                            `<span style="font-style: italic;">Sin recepcionar.</span>`;
                    }
                },
                {
                    data: 'prioridad_requerimiento',
                    className: "text-center",
                    render: function (data, type, row) {
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
                    render: function (data, type, row) {
                        return "<span style='font-weight: bold;'>PROGRAMACION Y ADQ.</span>";
                    }
                },
                {
                    data: 'registrador_requerimiento',
                    className: "text-center"
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row) {
                        if (row.estado_requerimiento == 3) {
                            return `
                                                                            <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: #d70202; color: white; font-weight: bold;">POR PROCESAR</p>
                                                                        `;
                        } else if (row.estado_requerimiento == 9) {
                            return `
                                                                    <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: yellow; color: black; font-weight: bold;">EN PROCESO ${row.estado_int_requerimiento ? '<span style="display: block;">(En observacion)</span>' : ''} <i class="fa fa-flag"></i></p>
                                                                        `;
                        }
                    },
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row) {
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
                    url: '/listado_requerimientos_salidas_pc',
                    type: 'GET',
                    data: function (d) {
                        const tabActivo = $('.nav-tabs li.active a').attr('href');

                        if (tabActivo == '#tab1') {
                            d.estado_actual = 9;
                        } else if (tabActivo == '#tab2') {
                            d.estado_actual = 9;
                        } else if (tabActivo == '#tab3') {
                            d.estado_actual = $('#estadoRequerimiento').val();
                        }

                        d.nro_requerimiento = $('#nroRequerimiento').val();
                        d.nroSgdHt = $('#nroSgdHt').val();
                        d.registrador_requerimiento = $('#registrador_requerimiento').val();
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
                    render: function (data, type, row) {
                        return row.fecha_envio_requerimiento ??
                            `<span style="font-style: italic;">Sin enviar.</span>`;
                    }
                },
                {
                    data: 'prioridad_requerimiento',
                    className: "text-center",
                    render: function (data, type, row) {
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
                    render: function (data, type, row) {
                        return "<span style='font-weight: bold;'>PROGRAMACION Y ADQ.</span>";
                    }
                },
                {
                    data: 'registrador_requerimiento',
                    className: "text-center"
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row) {
                        if (row.estado_requerimiento == 9) {
                            return `
                                                                            <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: yellow; color: black; font-weight: bold;">EN PROCESO</p>
                                                                        `;
                        } else if (row.estado_requerimiento == 2) {
                            return `
                                                                            <p style="width: auto; padding: 8px 0px; border-radius: 4px; background-color: #54b554; color: white; font-weight: bold;">ENVIADO<i class="fa fa-flag"></i></p>
                                                                        `;
                        }
                    },
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row) {
                        return `
                                                                        <button class="btn btn-info btn-bandeja btn-detalle-salida" 
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
                footerCallback: function (row, data, start, end, display) { }
            });

            const tableMovimientosAp = $('#tableMovimientosAp').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/listado_movimientos_ap',
                    type: 'GET',
                    data: function (d) {
                        d.requerimiento_id = $('#req_id-det_2').val();
                        d.tipo_movimiento = 2;
                        d.rangoInicio = $('#rangoInicioAp').val();
                        d.rangoFin = $('#rangoFinAp').val();
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
                columns: [
                    /* {
                                            data: "movimiento_id",
                                            className: "text-center"
                                        }, */
                    {
                        data: null,
                        className: "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: "fechaReg_movimiento",
                        className: "text-center"
                    },
                    {
                        data: "registrador_movimiento",
                        className: "text-center"
                    },
                    {
                        data: "etapa_movimiento",
                        className: "text-center"
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function (data, type, row) {
                            return `<p style="color: ${row.estado_movimiento ? 'red' : 'green'};">
                                                                    ${row.estado_movimiento ? 'OBSERVADO' : 'SIN OBSERVACIONES'}
                                                                    </p>`;
                        }
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function (data, type, row) {
                            return `
                                                                        <button type="button" class="btn btn-info btn-detalle-movimiento" 
                                                                                data-id="${row.movimiento_id}">
                                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        </button>
                                                                    `;
                        },
                    },
                ],
                footerCallback: function (row, data, start, end, display) { }
            });

            $('#btnBuscar').on('click', function () {
                let tabActivo = $('.nav-tabs li.active a').attr('href');
                let tabla = $(tabActivo).find('table');
                console.log(tabla);
                tabla.DataTable().ajax.reload();
            });

            $("#btnRecepcionarEntrada").on("click", function (e) {
                const tableBandejaEntrada = $("#tableBandejaEntrada").DataTable();
                const tableBandejaProceso = $("#tableBandejaProceso").DataTable();
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
                            url: '/requerimientos_pfdm/recepcionar_entrada_pc',
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                req_id: $('#req_id-det').val(),
                            },
                            beforeSend: function () {
                                Swal.fire({
                                    title: 'Actualizando...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Actualizado',
                                    text: 'Los cambios se guardaron correctamente'
                                });
                                $('#modalDetalleEntrada').modal('hide');
                                tableBandejaEntrada.ajax.reload();
                                tableBandejaProceso.ajax.reload();
                                tableBandejaSalida.ajax.reload();
                            },
                            error: function (xhr) {
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

            $("#btnEnviarProcesoForm").on("click", function (e) {
                const tableBandejaEntrada = $("#tableBandejaEntrada").DataTable();
                const tableBandejaProceso = $("#tableBandejaProceso").DataTable();
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
                            url: '/requerimientos_pfdm/enviar_proceso',
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                req_id: $('#req_id-det').val(),
                                responsable_id: $('#responsable_id').val(),
                                observacion: $('#denominacion_requerimiento-enviar').val(),
                            },
                            beforeSend: function () {
                                Swal.fire({
                                    title: 'Actualizando...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Actualizado',
                                    text: 'Los cambios se guardaron correctamente'
                                });
                                $('#modalDetalleEntrada').modal('hide');
                                $("#modalEnviarProceso").modal("hide");
                                tableBandejaEntrada.ajax.reload();
                                tableBandejaProceso.ajax.reload();
                                tableBandejaSalida.ajax.reload();
                            },
                            error: function (xhr) {
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

            $("#btnEnviarProceso").on("click", function (e) {
                $("#modalEnviarProceso").modal("show");
            });
        });

        function cargarDetalleRequerimiento(data) {
            $("#btnEnviarRequerimiento").prop("disabled", true);
            $("#info_req-anulado").show();
            $("#nro_req-det").html(data.numero_requerimiento);
            $("#fecha_registro-det").html(data.created_at);
            $("#nro_pedido_siga-det").html(data.numero_pedido_siga);
            $("#tipo_producto-det").html(data.tipo_producto?.nombre);
            $("#denominacion_req-det").html(data.denominacion);
            $("#pfdm_nombre-det").val(`${data.producto.codigo} - ${data.producto.desc_trama ?? data.producto.nombre}`);
            $("#codigo_siga-producto-det").val(data.producto.codigo_siga);
            $("#codigo_sismed-producto-det").val(data.producto.codigo_sismed);
            $("#unidad_medida-producto-det").val(data.unidad_medida);
            $("#cantidad_total-det").val(data.cantidad_total);
            $("#req_id-det").val(data.id);
            /* const movimientos = data.movimientos;
            movimientos.forEach((mov) => {
                if (mov.orden == 11) {
                    $("#observacion_proceso-det_entrada").val(mov.observacion);
                }
            }); */
            const detalles = data.detalles;
            $("#tblRowEntregas-det").html("");
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
                $("#container_button-recepcionar").show();
            } else {
                $("#container_estado-recepcionado").show();
            }
        }

        function cargarDetalleRequerimientoSalida(data) {
            $("#nro_req-det_3").html(data.numero_requerimiento);
            $("#fecha_registro-det_3").html(data.created_at);
            $("#nro_pedido_siga-det_3").html(data.numero_pedido_siga);
            $("#tipo_producto-det_3").html(data.tipo_producto?.nombre);
            $("#denominacion_req-det_3").html(data.denominacion);
            $("#pfdm_nombre-det_3").val(`${data.producto.codigo} - ${data.producto.desc_trama}`);
            $("#codigo_siga-producto-det_3").val(data.producto.codigo_siga);
            $("#codigo_sismed-producto-det_3").val(data.producto.codigo_sismed);
            $("#unidad_medida-producto-det_3").val(data.unidad_medida);
            $("#cantidad_total-det_3").val(data.cantidad_total);
            $("#req_id-det_3").val(data.id);
            /* const movimientos = data.movimientos;
            let observacion = '';

            const mov11 = movimientos.find(m => m.orden == 11);
            const mov10 = movimientos.find(m => m.orden == 10);

            if (mov11) {
                observacion = mov11.observacion;
            } else if (mov10) {
                observacion = mov10.observacion;
            }

            $("#observacion_proceso-det_entrada_3").val(observacion); */
            const detalles = data.detalles;
            $("#tblRowEntregas-det_3").html("");
            detalles.forEach((detalle) => {
                $("#tblRowEntregas-det_3").append(`<tr>
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
                $("#container_estado-enviado_3").show();
                $("#container-destino-salida_3").hide();
            } else {
                $("#container-destino-salida_3").show();
                $("#container_button-enviar_3").show();

            }
        }

        function cargarDetalleRequerimientoProceso(data) {
            $("#info_req-anulado").show();
            $("#nro_req-det_2").html(data.numero_requerimiento);
            $("#fecha_registro-det_2").html(data.created_at);
            $("#tipo_producto-det_2").html(data.tipo_producto?.nombre);
            $("#nro_pedido_siga-det_2").html(data.numero_pedido_siga);
            $("#denominacion_req-det_2").html(data.denominacion);
            $("#pfdm_nombre-det_2").val(`${data.producto.codigo} - ${data.producto.desc_trama}`);
            $("#codigo_siga-producto-det_2").val(data.producto.codigo_siga);
            $("#codigo_sismed-producto-det_2").val(data.producto.codigo_sismed);
            $("#unidad_medida-producto-det_2").val(data.unidad_medida);
            $("#cantidad_total-det_2").val(data.cantidad_total);
            /* const movimientos = data.movimientos; */
            $("#req_id-det_2").val(data.id);

            const procSeleccionMap = {
                1: "MENOR A 8 UIT",
                2: "MAYOR A 8 UIT"
            };

            const tipoProcesoMap = {
                1: "SUBASTA INVERSA ELECTRONICA",
                2: "LICITACION PUBLICA",
                4: "LICITACION PUBLICA ABREVIADA",
                3: "COMPRA DIRECTA"
            };

            $("#proc_seleccion_ap").val(
                procSeleccionMap[data.id_procedimiento_seleccion]
            );

            $("#tipo_proceso_ap").val(
                tipoProcesoMap[data.id_tipo_proceso] || "SIN REGISTRO"
            );

            const detalles = data.detalles;
            $("#tblRowEntregas-det_2").html("");
            detalles.forEach((detalle) => {
                $("#tblRowEntregas-det_2").append(`<tr>
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
        }

        function cargarDetalleMovimiento(data) {
            console.log(data);
            const procSeleccionMap = {
                1: "MENOR A 8 UIT",
                2: "MAYOR A 8 UIT"
            };

            const tipoProcesoMap = {
                1: "SUBASTA INVERSA ELECTRONICA",
                2: "LICITACION PUBLICA Y LPA",
                3: "COMPRA DIRECTA"
            };

            $("#proc_seleccion_ap-det").val(
                procSeleccionMap[data.requerimiento?.id_procedimiento_seleccion]
            );

            $("#tipo_proceso_ap-det").val(
                tipoProcesoMap[data.id_tipo_proceso] || "SIN REGISTRO"
            );

            $("#nueva_etapa_ap-det").val(data.etapa);
            $("#obs_ap-det").val(data.observacion);
        }

        $(document).on('click', '.btn-detalle-entrada', function () {
            $("#tituloModalDetalleEntrada").html("Detalle del Requerimiento");
            const req_id = $(this).data("id");

            $.ajax({
                url: `/ver-requerimiento_pfdm/${req_id}`,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    Swal.fire({
                        title: 'Cargando informacion...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message
                    });
                    $("#modalDetalleEntrada").modal("show");
                    const req_data = response.data ?? null;
                    if (req_data) {
                        cargarDetalleRequerimiento(response.data);
                    } else {
                        alert("No existe data, reportar error!!!");
                    }
                },
                error: function () {
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

        $(document).on('click', '.btn-detalle-proceso', function () {
            $("#tituloModalDetalleProceso").html("Detalle del Requerimiento");
            const req_id = $(this).data("id");
            const tableMovimientosAp = $("#tableMovimientosAp").DataTable();

            $.ajax({
                url: `/ver-requerimiento_pfdm/${req_id}`,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    Swal.fire({
                        title: 'Cargando informacion...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message
                    });
                    $("#modalDetalleProceso").modal("show");
                    const req_data = response.data ?? null;
                    if (req_data) {
                        cargarDetalleRequerimientoProceso(response.data);
                        tableMovimientosAp.ajax.reload();
                    } else {
                        alert("No existe data, reportar error!!!");
                    }
                },
                error: function () {
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

        $(document).on('click', '.btn-enviar-proceso', function () {
            $("#modalDestinoProceso").modal("show");
            $("#tituloModalDestinoProceso").html("Destino del Requerimiento");
        });

        $(document).on('click', '.btn-detalle-salida', function () {
            $("#modalDetalleSalida").modal("show");
            $("#tituloModalDetalleSalida").html("Detalle del Requerimiento");
        });

        $(document).on('click', '.btn-detalle-movimiento', function () {
            $("#tituloModalDetalleMovimiento").html("Detalle del Movimiento");
            const mov_id = $(this).data("id");

            $.ajax({
                url: `/ver-movimiento_ap/${mov_id}`,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    Swal.fire({
                        title: 'Cargando informacion...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message
                    });
                    const req_data = response.data ?? null;
                    if (req_data) {
                        cargarDetalleMovimiento(req_data);
                        $("#modalDetalleMovimiento").modal("show");
                    } else {
                        alert("No existe data, reportar error!!!");
                    }
                },
                error: function () {
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

        $(document).on('hidden.bs.modal', '.modal', function () {
            if ($('.modal.in').length > 0) {
                $('body').addClass('modal-open');
            }
        });

        $(document).on('click', '#btnAddMovimientoAp', function () {
            $("#modalAddMovimientosAp").modal("show");
            $("#tituloModalAddMovimientosAp").html("Actualizacion del Requerimiento");
            const table = $('#tableMovimientosAp').DataTable();
            const rows = table.rows().data().toArray();
            if (rows.length > 0) {
                const ultimoRow = rows[rows.length - 1];
                $("#etapa_actual_ap").val(ultimoRow.etapa_movimiento);
            } else {
                $("#etapa_actual_ap").val("SIN MOVIMIENTOS");
            }
        });

        $(document).on('click', '#btnBuscarAp', function () {
            const tableMovimientosAp = $("#tableMovimientosAp").DataTable();
            tableMovimientosAp.ajax.reload();
        });

        $('#modalAddMovimientosAp').on('hidden.bs.modal', function () {
            $("#nueva_etapa_ap").val("").change();
            $("#obs_ap").val("");
            $("#estado_obs-req").prop("checked", false);
        });


        $("#btnEnviarSeccion3").on("click", function (e) {
            const tableBandejaProceso = $("#tableBandejaProceso").DataTable();
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
                        url: '/requerimientos_pfdm/enviar_req_pc',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            req_id: $('#req_id-det_3').val(),
                            responsable_id: $('#responsable_id_3').val(),
                            etapa_id: $('#destino_ss_3').val(),
                            observacion: $('#denominacion_requerimiento-enviar_3').val(),
                        },
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Actualizando...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualizado',
                                text: 'Los cambios se guardaron correctamente'
                            });
                            $('#modalDetalleSalida').modal('hide');
                            tableBandejaProceso.ajax.reload();
                            tableBandejaSalida.ajax.reload();
                        },
                        error: function (xhr) {
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

        $(document).on('click', '#btnRegistrarMovimientoAp', function () {
            const tableBandejaProceso = $("#tableBandejaProceso").DataTable();
            const tableBandejaSalida = $("#tableBandejaSalida").DataTable();
            const tableMovimientosAp = $("#tableMovimientosAp").DataTable();

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
                        url: '/requerimientos_pfdm/registrar_movimiento_pc',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            req_id: $('#req_id-det_2').val(),
                            tipo_mov: 2,
                            tiene_obs: $('#estado_obs-req').is(':checked'),
                            nueva_etapa_ap_desc: $('#nueva_etapa_ap option:selected').text(),
                            obs_ap: $('#obs_ap').val(),
                        },
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Actualizando...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualizado',
                                text: 'Los cambios se guardaron correctamente'
                            });
                            $('#modalAddMovimientosAp').modal('hide');
                            tableBandejaProceso.ajax.reload();
                            tableBandejaSalida.ajax.reload();
                            tableMovimientosAp.ajax.reload();
                        },
                        error: function (xhr) {
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

        $(document).on('click', '.btn-detalle-salida', function () {
            const req_id = $(this).data("id");

            $.ajax({
                url: `/ver-requerimiento_pfdm/${req_id}`,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    Swal.fire({
                        title: 'Cargando informacion...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    const req_data = response.data ?? null;
                    if (!req_data) {
                        alert("No existe data, reportar error!!!");
                        return;
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.message
                        });

                        $("#tituloModalDetalleSalida").html("Detalle del Requerimiento");
                        $("#modalDetalleSalida").modal("show");
                        cargarDetalleRequerimientoSalida(response.data);
                    }
                },
                error: function () {
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
    </script>
@endsection
@section('content')
    <div class="panel panel-primary filterable">

        <div class="panel-heading">
            <h3 class="panel-title" style="font-size: 13px !important">Unidad UE 020 / Area Abastecimiento Seccion Prog.
                Adq.
                / Etapa Proceso de Seleccion</h3>
        </div>

        <div class="panel-body">

            <ul class="nav nav-tabs" id="tabsBandeja">
                <li class="active">
                    <a data-toggle="tab" href="#tab1">BANDEJA ENTRADA</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab2">BANDEJA PROCESO</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab3">BANDEJA SALIDA</a>
                </li>
            </ul>

            <div class="tab-content" style="border: 1px solid #ddd; overflow: hidden; border-top: 0;">
                <div class="col-sm-12" style="margin-top: 15px;">
                    <form id="frmBandejaUE020" class="form-horizontal" method="post" action="">
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

                                            @foreach ($prioridades as $prioridad)
                                                <option value="{{ $prioridad->id }}">{{ $prioridad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label><small>Tipo Producto</small></label>
                                        <select class="form-control" id="tipo_producto_requerimiento"
                                            name="tipo_producto_requerimiento">
                                            <option value="" selected>--- Seleccionar ---</option>

                                            @foreach ($tipos_producto as $tipo_producto)
                                                <option value="{{ $tipo_producto->id }}">{{ $tipo_producto->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label><small>Estado</small></label>
                                        <select class="form-control" id="estadoRequerimiento" name="estadoRequerimiento">
                                            <option value="2" selected>NO RECEPCIONADO</option>
                                            <option value="3">RECEPCIONADO</option>
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
                                    <th style="text-align:center">UNIDAD ORIGEN</th>
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
                <div class="tab-pane fade in" id="tab2">
                    <div class="col-sm-12" style="margin-top: 15px;">
                        <table id="tableBandejaProceso" class="table table-hover" style="font-size: 12px !important;">
                            <thead>
                                <tr class="filters">
                                    <th style="text-align:center">N °</th>
                                    <th style="text-align:center">NRO. REQUERIMIENTO</th>
                                    <th style="text-align:center">TIPO DOC.</th>
                                    <th style="text-align:center">NRO DOC (SGD/HT).</th>
                                    <th style="text-align:center">FEC. RECEPCION</th>
                                    <th style="text-align:center">PRIORIDAD</th>
                                    <th style="text-align:center">TIPO PRODUC.</th>
                                    <th style="text-align:center">AREA ACTUAL</th>
                                    <th style="text-align:center">REGISTRADOR</th>
                                    <th style="text-align:center">SITUACION</th>
                                    <th style="text-align:center">VER DETALLE</th>
                                </tr>
                            </thead>
                            <tbody id="tblRowsBandejaProceso" style="font-size: 11px !important;">
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
                                    <th style="text-align:center" id="thead_recepcion">FEC. RECEPCION</th>
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

    <div class="modal fade" id="modalDetalleEntrada" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
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
                                    <button type="button" id="btnRecepcionarEntrada"
                                        class="btn btn-success">RECEPCIONAR</button>
                                </div>
                                <div id="container_estado-recepcionado" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <p class="" style="font-weight: bold; color: #3c763d; font-style: italic;">
                                        RECEPCIONADO</p>
                                </div>
                                <div id="container_button-enviar" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <button type="button" id="btnEnviarProceso" class="btn btn-success">ENVIAR</button>
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
                                            <textarea class="form-control" disabled
                                                id="denominacion_req-det">Este es un texto largo que viene del backend...</textarea>
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
                                            <input type="text" class="form-control" id="codigo_siga-producto-det" disabled>
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

                        <div class="col-sm-12" id="container-observacion-proceso_entrada">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="observacion_proceso-det_entrada" class="control-label">Observacion
                                                del
                                                proceso</label>
                                            <textarea class="form-control required" name="observacion_proceso-det_entrada"
                                                id="observacion_proceso-det_entrada" disabled></textarea>
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

    <div class="modal fade" id="modalDetalleProceso" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <input type="hidden" id="req_id-det_2">


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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Requerimiento: </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="nro_req-det_2">REQ-000123
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
                                                            id="fecha_registro-det_2">REQ-000123
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
                                                            id="tipo_producto-det_2">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Pedido SIGA: </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="nro_pedido_siga-det_2">REQ-000123
                                                        </p>
                                                    </div>
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
                                            <label for="" class="control-label">Denominacion del
                                                Requerimiento</label>
                                            <textarea class="form-control" disabled
                                                id="denominacion_req-det_2">Este es un texto largo que viene del backend...</textarea>
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
                                            <label for="pfdm_nombre-det_2" class="control-label">Producto
                                                Requerido</label>
                                            <input type="text" id="pfdm_nombre-det_2" name="pfdm_nombre-det_2"
                                                class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="container_inputs-pfdm">

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="codigo_siga-producto-det_2" class="control-label">Codigo
                                                SIGA</label>
                                            <input type="text" class="form-control" id="codigo_siga-producto-det_2"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="codigo_sismed-producto-det_2" class="control-label">Codigo
                                                SISMED</label>
                                            <input type="text" class="form-control" disabled
                                                id="codigo_sismed-producto-det_2">
                                        </div>
                                    </div>

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="unidad_medida-producto-det_2" class="control-label">Unidad
                                                Medida</label>
                                            <input type="text" class="form-control" disabled
                                                id="unidad_medida-producto-det_2">
                                        </div>
                                    </div>

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="cantidad_total-det_2" class="control-label">Cantidad Total</label>
                                            <input type="text" class="form-control" id="cantidad_total-det_2"
                                                name="cantidad_total-det_2" disabled>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <fieldset class="mi-fieldset" style="padding: 15px 10px; margin-bottom: 0;"
                                        id="fieldsetTableEntregas2">
                                        <table class="display" id="tableEntregas-det_2" cellspacing="0" width="100%">
                                            <tbody id="tblRowEntregas-det_2" class="fill-rows_required">
                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                            </fieldset>

                            <fieldset class="mi-fieldset">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label><small>Fecha Inicio</small></label>
                                        <input type="date" class="form-control" id="rangoInicioAp" name="rangoInicioAp"
                                            placeholder="">
                                    </div>
                                    <div class="col-md-2">
                                        <label><small>Fecha Fin</small></label>
                                        <input type="date" class="form-control" id="rangoFinAp" name="rangoFinAp"
                                            placeholder="">
                                    </div>
                                    <div class="col-md-2">
                                        <label style="visibility:hidden;"><small>Espacio</small></label>
                                        <button type="button" id="btnBuscarAp" class="btn btn-success form-control">
                                            Buscar
                                        </button>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2">
                                        <label style="visibility:hidden;"><small>Espacio</small></label>
                                        <button type="button" id="btnAddMovimientoAp" class="btn btn-primary form-control">
                                            <i class="fa fa-pencil-square-o"></i> Nuevo Registro
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="mi-fieldset">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="tableMovimientosAp" class="table table-hover"
                                            style="font-size: 12px !important;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center">N°</th>
                                                    <th style="text-align:center">FECHA Y HORA REGISTRO</th>
                                                    <th style="text-align:center">RESPONSABLE</th>
                                                    <th style="text-align:center">ETAPA</th>
                                                    <th style="text-align:center">ESTADO</th>
                                                    <th style="text-align:center">VER</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblMovimientosApRows"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade modal-center" id="modalEnviarProceso" tabindex="-1" role="dialog" aria-labelledby=""
        aria-hidden="true">

        <div class="modal-dialog">

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

                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="destino_responsable" class="control-label">Responsable</label>
                                <select name="destino_responsable" id="destino_responsable" class="form-control required">
                                    <option value="">--- Seleccionar ---</option>
                                </select>
                                <input type="hidden" id="responsable_id" name="responsable_id" class="required_pfdm">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="" class="control-label" style="visibility:hidden;">Responsable</label>
                                <button class="btn btn-success" type="button" id="btnEnviarProcesoForm">Guardar</button>
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

    <div class="modal fade" id="modalDetalleSalida" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <input type="hidden" id="req_id-det_3">

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

                    <form class="row" id="">
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
                                                            id="nro_req-det_3">REQ-000123
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
                                                            id="fecha_registro-det_3">REQ-000123
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
                                                            id="tipo_producto-det_3">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Pedido SIGA: </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="nro_pedido_siga-det_3">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div id="container_button-enviar_3" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <button type="button" id="btnEnviarSeccion3" class="btn btn-success">ENVIAR</button>
                                </div>
                                <div id="container_estado-enviado_3" class="col-md-2 col-center-vertical"
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
                                            <textarea class="form-control" disabled
                                                id="denominacion_req-det_3">Este es un texto largo que viene del backend...</textarea>
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
                                            <input type="text" id="pfdm_nombre-det_3" name="pfdm_nombre-det_3"
                                                class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="container_inputs-pfdm">

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="codigo_siga-producto-det_3" class="control-label">Codigo
                                                SIGA</label>
                                            <input type="text" class="form-control" id="codigo_siga-producto-det_3"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="codigo_sismed-producto-det_3" class="control-label">Codigo
                                                SISMED</label>
                                            <input type="text" class="form-control" disabled
                                                id="codigo_sismed-producto-det_3">
                                        </div>
                                    </div>

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="unidad_medida-producto-det_3" class="control-label">Unidad
                                                Medida</label>
                                            <input type="text" class="form-control" disabled
                                                id="unidad_medida-producto-det_3">
                                        </div>
                                    </div>

                                    <div class="col-sm-3" style="padding: 5px 15px;">
                                        <div class="form-group">
                                            <label for="cantidad_total-det_3" class="control-label">Cantidad Total</label>
                                            <input type="text" class="form-control" id="cantidad_total-det_3"
                                                name="cantidad_total-det_3" disabled>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <fieldset class="mi-fieldset" style="padding: 15px 10px; margin-bottom: 0;"
                                        id="fieldsetTableEntregas_3">
                                        <table class="display" id="tableEntregas-det_3" cellspacing="0" width="100%">
                                            <tbody id="tblRowEntregas-det_3" class="fill-rows_required">
                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-sm-12" id="container-observacion-proceso_entrada_3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="observacion_proceso-det_entrada_3" class="control-label">Observacion
                                                del
                                                proceso</label>
                                            <textarea class="form-control required" name="observacion_proceso-det_entrada_3"
                                                id="observacion_proceso-det_entrada_3" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="col-md-12" id="container-destino-salida_3" style="display: none;">
                            <fieldset class="mi-fieldset">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="destino_unidad_3" class="control-label">Unidad</label>
                                        <select name="destino_unidad_3" id="destino_unidad_3" class="form-control required"
                                            disabled>
                                            <option value="">--- Seleccionar ---</option>
                                            <option selected>UE 020</option>
                                        </select>
                                        <input type="hidden" id="envio-unidad_id_3" name="envio-unidad_id_3"
                                            class="required_pfdm">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="destino_area_3" class="control-label">Area</label>
                                        <select name="destino_area_3" id="destino_area_3" class="form-control required"
                                            disabled>
                                            <option value="">--- Seleccionar ---</option>
                                            <option selected>ABASTECIMIENTO</option>
                                        </select>
                                        <input type="hidden" id="envio-area_id_3" name="envio-area_id_3"
                                            class="required_pfdm">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="destino_seccion_3" class="control-label">Seccion</label>
                                        <select name="destino_seccion_3" id="destino_seccion_3"
                                            class="form-control required" disabled>
                                            <option value="">--- Seleccionar ---</option>
                                            <option selected>PROGRAMACION Y ADQUISICIONES</option>
                                        </select>
                                        <input type="hidden" id="envio-seccion_id_3" name="envio-seccion_id_3"
                                            class="required_pfdm">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="destino_ss_3" class="control-label">Sub-seccion</label>
                                        <select name="destino_ss_3" id="destino_ss_3" class="form-control required"
                                            disabled>
                                            <option value="">--- Seleccionar ---</option>
                                            <option value="8">PARA PROCESO DE SELECCION</option>
                                            <option value="9" selected>PARA EJECUCION CONTRACTUAL</option>
                                            {{-- <option value="10">DEVUELTO</option> --}}
                                        </select>
                                        <input type="hidden" id="envio-ss_id_3" name="envio-ss_id_3" class="required_pfdm">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="destino_responsable_3" class="control-label">Responsable</label>
                                        <select name="destino_responsable_3" id="destino_responsable_3"
                                            class="form-control required">
                                            <option value="">--- Seleccionar ---</option>
                                        </select>
                                        <input type="hidden" id="responsable_id_3" name="responsable_id_3"
                                            class="required_pfdm">
                                    </div>
                                </div>

                                <br>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="denominacion_requerimiento-enviar_3"
                                            class="control-label">Observacion</label>
                                        <textarea class="form-control required" name="denominacion_requerimiento-enviar_3"
                                            id="denominacion_requerimiento-enviar_3"></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddMovimientosAp" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">

        <div class="modal-dialog modal-lg-2">

            <div class="modal-content">

                <div class="modal-header"
                    style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModalAddMovimientosAp"></h4>
                </div>

                <div class="modal-body">

                    <form class="row" id="frmSubmitAddMovAp">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="control-label">Procedimiento de Seleccion</label>
                                        <input class="form-control" disabled id="proc_seleccion_ap">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="control-label">Tipo de Proceso</label>
                                        <input class="form-control" disabled id="tipo_proceso_ap">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="control-label">Etapa Actual</label>
                                        <input class="form-control" disabled value="SIN MOVIMIENTOS" id="etapa_actual_ap">
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="control-label">Etapa</label>
                                        <select class="form-control">
                                            <option selected>Actos Preparatorios</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="control-label">Nueva Etapa</label>
                                        <select class="form-control" id="nueva_etapa_ap">
                                            <option value="" selected>-- Seleccionar --</option>
                                            <option>DESIGNACION DE PERSONAL</option>
                                            <option>SELECCIONAR TIPO DE PROCESO</option>
                                            <option>EN APROBACION DE EXPEDIENTE</option>
                                            <option>EN CONVOCATORIA</option>
                                            <option>EN PRESENTACION DE OFERTAS</option>
                                            <option>EN EVALUACION DE OFERTAS</option>
                                            <option>EN OTORGAMIENTO DE BUENA PRO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="" style="visibility: hidden;">Estado</label>
                                    <div class="form-group"
                                        style="display:flex; align-items:center; gap:8px; margin: 0; margin-top: 5px;">
                                        <label for="estado_obs-req" class="control-label" style="margin:0;">
                                            ¿Hay observaciones?
                                        </label>
                                        <input type="checkbox" id="estado_obs-req" style="margin: 0;">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="control-label" style="visibility: hidden;">Guardar</label>
                                    <button class="btn btn-success btn-block" type="button"
                                        id="btnRegistrarMovimientoAp">Guardar</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="" class="control-label">Observacion</label>
                                        <textarea class="form-control" id="obs_ap"></textarea>
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
                                                    <button class="btn btn-success btn-block" type="button">GUARDAR</button>
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

    <div class="modal fade" id="modalDetalleMovimiento" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header"
                    style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModalDetalleMovimiento"></h4>
                </div>

                <div class="modal-body">

                    <form class="row" id="">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="control-label">Procedimiento de Seleccion</label>
                                        <input class="form-control" id="proc_seleccion_ap-det" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="control-label">Tipo de Proceso</label>
                                        <input class="form-control" id="tipo_proceso_ap-det" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="control-label">Etapa</label>
                                        <input class="form-control" id="nueva_etapa_ap-det" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="" class="control-label">Observacion</label>
                                        <textarea class="form-control" id="obs_ap-det" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    @include('frontend.includes.footer')
@endsection