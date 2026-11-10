<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')

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
    </style>
    <script src="<?php echo URL::to('/'); ?>/js/js.util.grid.js"></script>
    <script>
        $(document).ready(function() {
            $('#tblReporteAtendidos').DataTable({
                paging: true,
                searching: false,
                info: true,
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10,
                ordering: false,
                autoWidth: false,
                language: {
                    lengthMenu: "Mostrar _MENU_ registros",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros en total)",
                    paginate: {
                        previous: "Anterior",
                        next: "Siguiente"
                    }
                },
                dom: '<"top"l>rt<"bottom"ip><"clear">',
                columns: [{
                        data: 'codigo_ipress'
                    },
                    {
                        data: 'nombre_ipress'
                    },
                    {
                        data: 'consultorio'
                    },
                    {
                        data: 'especialidad'
                    },
                    {
                        data: 'total_general'
                    },
                    {
                        data: 'total_general'
                    },
                    {
                        data: 'titular'
                    },
                    {
                        data: 'familiar'
                    },
                    {
                        data: 'accion_civica'
                    },
                    {
                        data: 'tarifa'
                    },
                    {
                        data: 'total_general_nuevo_reingreso'
                    },
                    {
                        data: 'total_general_nuevo_reingreso'
                    },
                    {
                        data: 'titular_nuevo_reingreso'
                    },
                    {
                        data: 'familiar_nuevo_reingreso'
                    },
                    {
                        data: 'accion_civica'
                    },
                    {
                        data: 'tarifa'
                    },
                ]
            });
            $("#buscarPorFecha").on("click", function(e) {

                const id = this.id;
                const isChecked = $(this).is(":checked");

                if (isChecked) {
                    $("#containerPeriodoTotal").hide();
                    $("#containerPeriodoInicio").show();
                    $("#containerPeriodoFin").show();
                    $("#periodoTotal").prop("disabled", true);
                    $("#periodoInicio").prop("disabled", false);
                    $("#periodoFin").prop("disabled", false);
                } else {
                    $("#containerPeriodoTotal").show();
                    $("#containerPeriodoInicio").hide();
                    $("#containerPeriodoFin").hide();
                    $("#periodoTotal").prop("disabled", false);
                    $("#periodoInicio").prop("disabled", true);
                    $("#periodoFin").prop("disabled", true);
                    $("#periodoTotal").val("");
                    $("#periodoInicio").val("");
                    $("#periodoFin").val("");
                }
            });
            $("#activaAtendidos").on("click", function(e) {
                const id = this.id;
                const isChecked = $(this).is(":checked");

                if (isChecked) {
                    $("#containerCondicionEst").hide();
                    $("#containerCondicionServ").hide();
                    $("#condicionEstablecimiento").prop("disabled", true);
                    $("#condicionServicio").prop("disabled", true);
                    $("#condicionEstablecimiento").val("");
                    $("#condicionServicio").val("");
                } else {
                    $("#containerCondicionEst").show();
                    $("#containerCondicionServ").show();
                    $("#condicionEstablecimiento").prop("disabled", false);
                    $("#condicionServicio").prop("disabled", false);
                }
            });
            $("#periodoInicio, #periodoFin").on("change", function() {
                const inicio = $("#periodoInicio").val();
                const fin = $("#periodoFin").val();

                if (inicio && fin && inicio > fin) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'La fecha de inicio no puede ser mayor a la fecha final.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $("#periodoInicio").val("");
                }
            });
        });
        var tblFinalIpressFilter = $("#tblFinalIpressFilter").DataTable({
            searching: false,
            lengthChange: false,
            paging: false,
            info: false,
            ordering: false,
            columns: [{
                    title: "Codigo",
                    data: "codigo"
                },
                {
                    title: "Descripcion",
                    data: "nombre_ipress"
                },
                {
                    title: "Accion",
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-danger btn-removeToFilter-ipress" 
                                    data-id="${row.id}" 
                                    style="width: 32px; height: 32px; border-radius: 8px; padding: 0; font-size: 17px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                            </button>
                        `;
                    },
                    className: "text-center"
                }
            ]
        });
        $('#tblFinalIpressFilter').on('click', '.btn-removeToFilter-ipress', function() {
            var row = tblFinalIpressFilter.row($(this).closest('tr'));
            row.remove().draw();
        });
        $('#btnRemoveFilters1').on('click', function() {
            tblFinalIpressFilter.clear().draw();
        });
        $("#btnAddFilters1").on("click", function(e) {
            $("#seleccionarIPRESSModal").modal("show");
        });
        $("#btnBuscarReporte").on("click", function(e) {
            const conFecha = $("#buscarPorFecha").is(":checked");
            const seleccionarAtendidos = $("#activaAtendidos").is(":checked");
            const periodo = $("#periodoTotal").val();
            const fechaIni = $("#periodoInicio").val();
            const fechaFin = $("#periodoFin").val();
            const condicionEsta = $("#condicionEstablecimiento").val();
            const condicionServ = $("#condicionServicio").val();
            let tableIpress = $("#tblFinalIpressFilter").DataTable();
            let ipress = tableIpress.rows().data().toArray().map(r => r.id);
            if (!conFecha) {
                if (periodo == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar el periodo.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
            } else {
                if (fechaIni == "" || fechaFin == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar la fecha inicial y final.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
            }
            Swal.fire({
                title: "Cargando...",
                text: "Por favor espere",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: "/reporteAtendidosIpress",
                type: "POST",
                data: {
                    ipress,
                    fechaIni: conFecha ? fechaIni : primerDiaMes(periodo),
                    fechaFin: conFecha ? fechaFin : ultimoDiaMes(periodo),
                    condicionEstablecimiento: seleccionarAtendidos ? null : condicionEsta,
                    condicionServicio: seleccionarAtendidos ? null : condicionServ,
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    Swal.close();
                    let tabla = $("#tblReporteAtendidos").DataTable();
                    tabla.clear();
                    tabla.rows.add(response);
                    tabla.draw();
                    let total = response.length;
                    $("#containerTotalReporteAt")
                        .html(`Total de registros: <strong> ${total}</strong>`);
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire("Error", "Ocurrió un problema al obtener los datos", "error");
                }
            });
        });
        $("#btnExportarExcel, #btnExportarCSV").on("click", function(e) {
            let idBtn = e.currentTarget.id;
            let tableIpress = $("#tblFinalIpressFilter").DataTable();
            let tipoReporte = null;
            let ipress = tableIpress.rows().data().toArray().map(r => r.id);
            const conFecha = $("#buscarPorFecha").is(":checked");
            const seleccionarAtendidos = $("#activaAtendidos").is(":checked");
            const periodo = $("#periodoTotal").val();
            const fechaIni = $("#periodoInicio").val();
            const fechaFin = $("#periodoFin").val();
            const condicionEsta = $("#condicionEstablecimiento").val();
            const condicionServ = $("#condicionServicio").val();
            const fechaIniParsed = conFecha ? fechaIni : primerDiaMes(periodo);
            const fechaFinParsed = conFecha ? fechaFin : ultimoDiaMes(periodo);
            const condicionEstablecimientoParsed = seleccionarAtendidos ? null : condicionEsta;
            const condicionServicioParsed = seleccionarAtendidos ? null : condicionServ;
            if (!conFecha) {
                if (periodo == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar el periodo.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
            } else {
                if (fechaIni == "" || fechaFin == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar la fecha inicial y final.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
            }
            if (idBtn == "btnExportarExcel") {
                tipoReporte = "excel";
            } else if (idBtn == "btnExportarCSV") {
                tipoReporte = "csv";
            }
            let url = "/reporteAtendidosIpressExport?" +
                "ipress[]=" + ipress.join("&ipress[]=") +
                "&fechaIni=" + fechaIniParsed +
                "&fechaFin=" + fechaFinParsed +
                "&condicionEstablecimiento=" + (condicionEstablecimientoParsed ? condicionEstablecimientoParsed :
                    "") +
                "&condicionServicio=" + (condicionServicioParsed ? condicionServicioParsed : "") +
                "&tipoReporte=" + tipoReporte;

            window.location.href = url;
        });

        function primerDiaMes(valorMonth) {
            if (!valorMonth) return null;
            const [y, m] = valorMonth.split("-").map(Number);
            return new Date(y, m - 1, 1).toISOString().split("T")[0];
        }

        function ultimoDiaMes(valorMonth) {
            if (!valorMonth) return null;
            const [anio, mes] = valorMonth.split("-").map(Number);
            const fechaFin = new Date(anio, mes, 0);
            return fechaFin.toISOString().split("T")[0];
        }

        $("#seleccionarIPRESSModal").on("click",
            ".select_all",
            function(e) {
                const idPadre = $(e.delegateTarget).attr("id");
                const $tabla = $("#" + idPadre).find("table.toAdd");
                const dt = $tabla.DataTable();
                const todosLosDatos = dt.rows().data().toArray();
                const $tablasDestino = $("#" + idPadre).find("table.toQuit");

                console.log("Items encontrados:", todosLosDatos);
                const dtDestino = $tablasDestino.DataTable();
                const datosExistentes = dtDestino.rows().data().toArray();
                const idsExistentes = datosExistentes.map(d => d.id);
                const datosUnicos = todosLosDatos.filter(d => !idsExistentes.includes(d.id));

                if (datosUnicos.length > 0) {
                    dtDestino.rows.add(datosUnicos).draw();
                    console.log(`Añadidos ${datosUnicos.length} nuevos registros.`);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Ya estan seleccionados todos los registros.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });

        $("#seleccionarIPRESSModal").on("click",
            ".remove_all",
            function(e) {
                const idPadre = $(e.delegateTarget).attr("id");
                const $tablaRemove = $("#" + idPadre).find("table.toQuit");
                const dtDestino = $tablaRemove.DataTable();
                dtDestino.clear().draw();
            });

        var ipressDefault = {!! json_encode($ipressActual) !!};
        if (ipressDefault) {
            tblFinalIpressFilter.row.add(ipressDefault).draw(false);
        }
        if (!{!! json_encode($flag_establecimientos) !!}) {
            $("#btnAddFilters1").prop("disabled", true);
        }
    </script>
@endsection
@section('content')
    <div class="container-fluid" style="margin: 0 100px;">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">R. ATENDIDOS</h3>
            </div>
            <div class="panel-body panel_custom-body">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <strong>Filtros de Búsqueda</strong>
                    </div>

                    <div class="panel-body">
                        <!-- FILA 1: Buscar por periodo y rangos -->
                        <div class="row">
                            <div class="col-md-3" id="containerPeriodoTotal">
                                <div class="form-group">
                                    <label for="periodoTotal">Periodo</label>
                                    <input type="month" class="form-control" id="periodoTotal" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="checkbox" style="margin-top: 5px;">
                                    <label>
                                        <input type="checkbox" id="buscarPorFecha"> Buscar por Fecha
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3" id="containerPeriodoInicio" style="display: none;">
                                <div class="form-group">
                                    <label for="periodoInicio">Periodo Inicio</label>
                                    <input type="date" class="form-control" id="periodoInicio" placeholder=""
                                        disabled="true">
                                </div>
                            </div>
                            <div class="col-md-3" id="containerPeriodoFin" style="display: none;">
                                <div class="form-group">
                                    <label for="periodoFin">Periodo Fin</label>
                                    <input type="date" class="form-control" id="periodoFin" placeholder=""
                                        disabled="true">
                                </div>
                            </div>
                        </div>

                        <!-- FILA 2: Atendidos y combos -->
                        <div class="row">
                            <div class="col-md-2">
                                <div class="checkbox" style="margin-top: 30px;">
                                    <label>
                                        <input type="checkbox" id="activaAtendidos" checked> Atenciones
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" id="containerCondicionEst" style="display: none;">
                                <div class="form-group">
                                    <label for="condicionEstablecimiento">Condición Establecimiento</label>
                                    <select class="form-control" id="condicionEstablecimiento" disabled>
                                        <option value="CONTINUADOR" selected>CONTINUADOR</option>
                                        <option value="NUEVO">NUEVO</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4" id="containerCondicionServ" style="display: none;">
                                <div class="form-group">
                                    <label for="condicionServicio">Condición Servicio</label>
                                    <select class="form-control" id="condicionServicio" disabled>
                                        <option value="CONTINUADOR" selected>CONTINUADOR</option>
                                        <option value="NUEVO">NUEVO</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- TABLA IPRESS -->
                        <div class="row">
                            <div class="col-md-12">
                                <table id="tblFinalIpressFilter" class="table table-bordered table-condensed text-center">
                                    <thead>
                                        <tr style="background-color: #e5f2e0;">
                                            <th class="text-center" style="width: 20%;">Código</th>
                                            <th class="text-center" style="width: 60%;">Descripción</th>
                                            <th class="text-center" style="width: 20%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button id="btnAddFilters1" class="btn btn-success btn-sm">
                                    <i class="glyphicon glyphicon-plus"></i> Agregar
                                </button>
                                <button id="btnRemoveFilters1" class="btn btn-default btn-sm">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Limpiar
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="" id="global">
                    <div class="row">
                        <!-- Botones a la derecha -->
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="button" id="btnExportarExcel">
                                <i class="fa fa-file-excel-o"></i> Exportar
                            </button>
                            <button class="btn btn-info" type="button" id="btnBuscarReporte">
                                <i class="fa fa-search" aria-hidden="true"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tblReporteAtendidos" class="table table-bordered table-condensed" width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="2">Cod. IPRESS</th>
                                    <th rowspan="2">Descripción IPRESS</th>
                                    <th rowspan="2">Tipo de Consulta</th>
                                    <th rowspan="2">Especialidad</th>
                                    <th colspan="6" class="text-center">Atenciones (Nuevas + Reingresantes +
                                        Continuadores)</th>
                                    <th colspan="6" class="text-center">Atendidos (Nuevos + Reingresantes)</th>
                                </tr>
                                <tr>
                                    <th>Total General</th>
                                    <th>Total</th>
                                    <th>Titular</th>
                                    <th>Familiar</th>
                                    <th>Acción Cívica</th>
                                    <th>Tarifa</th>
                                    <th>Total General</th>
                                    <th>Total</th>
                                    <th>Titular</th>
                                    <th>Familiar</th>
                                    <th>Acción Cívica</th>
                                    <th>Tarifa</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.seis.filters.modal_add_ipress')
    @include('frontend.includes.footer')
@endsection
