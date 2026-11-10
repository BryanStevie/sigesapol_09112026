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

        #modalDetalleMovimientos .modal-dialog {
            width: 90%;
            max-width: 1400px;
        }

        .container {
            min-height: 0;
        }

        #containerTimeline {
            display: flex;
            align-items: stretch;
        }

        #container-ver_movimientos {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media (max-width: 768px) {

            #containerTimeline {
                display: block;
            }

            #container-ver_movimientos {
                justify-content: flex-start;
            }

            #timeline-ap {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            #timeline-ap .paso {
                width: 100%;
                margin-top: 30px;
                margin-bottom: 30px;
                text-align: left;
                position: relative;
                padding-left: 60px;
            }

            #timeline-ap .icono {
                position: absolute;
                left: 0;
                top: 0;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #timeline-ap .paso::after {
                content: "";
                position: absolute;
                left: 20px;
                top: 40px;
                width: 2px;
                height: calc(100% + 20px);
                background: #ddd;
            }

            #timeline-ap .paso:last-child::after {
                display: none;
            }

            #timeline-ap .paso p,
            #timeline-ap .paso small {
                display: block;
                margin: 0;
            }
        }

        #tableRequerimientos td {
            vertical-align: middle !important;
        }

        .btn-detalle-req,
        .btn-editar-req,
        .btn-eliminar-req {
            border-radius: 8px;
            outline: 0;
            font-size: 15px;
        }

        .btn-detalle-req:focus,
        .btn-editar-req:focus,
        .btn-eliminar-req:focus {
            outline: none;
            box-shadow: none;
        }

        .btn-detalle-req:active,
        .btn-editar-req:active,
        .btn-eliminar-req:active {
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

        .row-flex {
            display: flex;
            align-items: stretch;
        }

        #tblAddEntrega>tbody>tr>td {
            padding: 0 5px;
        }

        #tblAddEntrega>thead>tr>th {
            padding: 0 5px;
        }

        .input-error {
            border: 1px solid rgba(255, 14, 14, 0.712) !important;
        }

        .error-text {
            color: rgba(255, 14, 14, 0.712);
            font-size: 10px;
            display: none;
        }

        .modal-body {
            max-height: 75vh;
            overflow-y: auto;
        }

        #tblRowEntregas>tr>div {
            padding: 5px 0;
        }

        .select2-container .select2-selection--single {
            height: 34px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 34px;
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 34px;
        }

        .proceso-linea {
            position: relative;
            margin-top: 30px;
        }

        .proceso-linea {
            position: relative;
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .paso {
            position: relative;
            z-index: 2;
        }

        .paso {
            min-width: 250px;
            position: relative;
        }

        .paso:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 60%;
            width: 100%;
            height: 3px;
            background: #5cb85c;
            z-index: 1;
        }

        /* Línea horizontal */
        /* .proceso-linea:before {
                                        content: '';
                                        position: absolute;
                                        top: 20px;
                                        left: 0;
                                        width: 100%;
                                        height: 3px;
                                        background-color: #5cb85c;
                                        z-index: 1;
                                    } */

        /* Cada paso */
        /* Ícono */
        .icono {
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
            background-color: #5cb85c;
            color: #fff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        /* Texto */
        .paso p {
            font-size: 12px;
            margin: 0;
        }

        .paso.activo .icono {
            background-color: #337ab7;
        }

        .proceso-linea {
            display: flex;
            flex-wrap: nowrap;
            /* evita salto vertical */
            overflow-x: auto;
            /* scroll horizontal */
            overflow-y: hidden;
            /* evita scroll vertical */
            white-space: nowrap;
            padding-bottom: 10px;
        }

        .paso {
            min-width: 220px;
            /* ancho fijo por etapa */
            flex: 0 0 auto;
        }

        .icono {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #337ab7;
            color: white;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
    </style>
    <script src="<?php echo URL::to('/'); ?>/js/js.util.grid.js"></script>
    <script>
        $(document).ready(function () {

            const tablaReq = $('#tableRequerimientos').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/listado_requerimientos_all',
                    type: 'GET',
                    data: function (d) {
                        d.nro_requerimiento = $('#nroRequerimiento').val();
                        d.nroSgdHt = $('#nroSgdHt').val();
                        d.estado_actual = $('#estadoRequerimientoPfdm').val();
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
                    data: 'fechaReg_requerimiento',
                    className: "text-center"
                },
                /* {
                    data: 'prioridad_requerimiento',
                    className: "text-center"
                }, */
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
                    data: 'registrador_requerimiento',
                    className: "text-center"
                },
                {
                    data: 'null',
                    className: "text-center",
                    render: function (data, type, row) {
                        if (row.estado_requerimiento == 8) {
                            return '<span class="text-danger" style="font-weight: bold;">ANULADO</span>';
                        } else if (row.estado_requerimiento >= 1) {
                            return '<span class="text-info" style="font-weight: bold; color: green;">ACTIVO</span>';
                        }
                    }
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row) {
                        return `
                                        <button class="btn btn-info btn-detalle-req" 
                                                data-id="${row.requerimiento_id}">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>
                                    `;
                    },
                },
                ]
            });

            $('#btnBuscar').on('click', function () {
                tablaReq.ajax.reload();
            });

            $('#btnGenerarCsv').on('click', function (e) {
                e.preventDefault();

                let fecha_inicio = $('#rangoInicio').val();
                let fecha_fin = $('#rangoFin').val();
                let nro_requerimiento = $('#nroRequerimiento').val();
                let estado_actual = $('#estadoRequerimientoPfdm').val();
                let registrador_requerimiento = $('#registrador_requerimiento').val() ?? "";
                let tipo_producto_requerimiento = $('#tipo_producto_requerimiento').val();
                let prioridad_requerimiento = $('#prioridad_requerimiento').val();

                let params = new URLSearchParams({
                    fecha_inicio,
                    fecha_fin,
                    nro_requerimiento,
                    estado_actual,
                    registrador_requerimiento,
                    tipo_producto_requerimiento,
                    prioridad_requerimiento
                });

                let url = `/exportar-csv_requerimiento-pfdm?${params.toString()}`;

                window.location.href = url;
            });

            /* const tablaEntregaProductos = $('#tableEntregaProductos').DataTable({
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
            }); */

            /* $("#btnAgregarRequerimiento").on("click", function(e) {
                $("#modalRequerimiento").modal("show");
                $("#tituloModalRequerimiento").html("Nuevo Requerimiento");
            }); */

            $('#registrador_requerimiento').select2({
                placeholder: 'Busque por nombre o dni...',
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

            $('#registrador_requerimiento').on('select2:select', function (e) {
                let data = e.params.data;
            });
        });
    </script>
    @include('frontend.requerimientos.functions.functions_detalle_movimientos')
@endsection
@section('content')
    <div class="panel panel-primary filterable">

        <div class="panel-heading">
            <h3 class="panel-title" style="font-size: 13px !important">HISTORIAL MOVIMIENTOS</h3>
        </div>

        <div class="panel-body">

            <form id="frmRequerimientos" class="form-horizontal" method="post" action="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-md-2">
                        <label><small>Fecha Inicio</small></label>
                        <input type="date" class="form-control" id="rangoInicio" name="rangoInicio" placeholder="">
                    </div>
                    <div class="col-md-2">
                        <label><small>Fecha Fin</small></label>
                        <input type="date" class="form-control" id="rangoFin" name="rangoFin" placeholder="">
                    </div>
                    <div class="col-md-3">
                        <label><small>Numero de Requerimiento</small></label>
                        <input type="text" class="form-control" id="nroRequerimiento" name="nroRequerimiento"
                            placeholder="Ingrese nro.">
                    </div>
                    <div class="col-md-2">
                        <label><small>Numero de SGD/HT</small></label>
                        <input type="text" class="form-control" id="nroSgdHt" name="nroSgdHt"
                            placeholder="Ingrese nro. SGD/HT">
                    </div>
                    <div class="col-md-3">
                        <label for="estadoRequerimientoPfdm"><small>Estado</small></label>
                        <select class="form-control" id="estadoRequerimientoPfdm" name="estadoRequerimientoPfdm">
                            <option value="1" selected>ACTIVO</option>
                            <option value="0">ANULADO</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3">
                        <label><small>Prioridad</small></label>
                        <select class="form-control" id="prioridad_requerimiento" name="prioridad_requerimiento">
                            <option value="" selected>--- Seleccionar ---</option>
                            @foreach ($prioridades as $prioridad)
                                <option value="{{ $prioridad->id }}">{{ $prioridad->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label><small>Tipo Producto</small></label>
                        <select class="form-control" id="tipo_producto_requerimiento" name="tipo_producto_requerimiento">
                            <option value="" selected>--- Seleccionar ---</option>

                            @foreach ($tipos_producto as $tipo_producto)
                                <option value="{{ $tipo_producto->id }}">{{ $tipo_producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label><small>Registrador</small></label>
                        <select class="form-control" id="registrador_requerimiento" name="registrador_requerimiento">
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label style="visibility:hidden;"><small>Espacio</small></label>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" id="btnBuscar" class="btn btn-success form-control">
                                    Buscar
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a id="btnGenerarCsv" class="btn btn-primary form-control" href="#">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>Generar Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-warning form-control" id="btnAgregarRequerimiento">
                        <i class="fa fa-plus"></i>
                        Agregar Requerimiento
                    </button>
                </div>
            </div> --}}

            <br>

            <div class="row">

                <div class="responsive"></div>

                <div class="col-sm-12">
                    <table id="tableRequerimientos" class="table table-hover" style="font-size: 12px !important;">
                        <thead>
                            <tr class="filters">
                                <th style="text-align:center">N °</th>
                                <th style="text-align:center">NRO. REQUERIMIENTO</th>
                                <th style="text-align:center">TIPO DOC.</th>
                                <th style="text-align:center">NRO DOC (SGD/HT).</th>
                                <th style="text-align:center">FEC. REGISTRO</th>
                                <th style="text-align:center">PRIORIDAD</th>
                                <th style="text-align:center">TIPO PRODUC.</th>
                                <th style="text-align:center">REGISTRADOR</th>
                                <th style="text-align:center">ESTADO</th>
                                <th style="text-align:center">VER DETALLE</th>
                            </tr>
                        </thead>
                        <tbody id="tblRowsRequerimientos" style="font-size: 11px !important;">
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

    @include('frontend.requerimientos.modals.modal_detalle_movimientos')
    @include('frontend.includes.footer')
@endsection