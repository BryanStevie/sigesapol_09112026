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
        $('#tblReporteCA').DataTable({
            language: {
                emptyTable: "No hay registros disponibles",
            },
            columns: [{
                    title: "Tipo",
                    data: "tipo"
                },
                {
                    title: "Grupo",
                    data: "grupo"
                },
                {
                    title: "Descripcion",
                    data: "descripcion"
                },
                {
                    title: "Total General",
                    data: "total"
                },
                {
                    title: "Total",
                    data: "total"
                },
                {
                    title: "Titular",
                    data: "titular"
                },
                {
                    title: "Familiar",
                    data: "familiar"
                },
                {
                    title: "Accion Civica",
                    data: "civica"
                },
                {
                    title: "Civiles Tarifa",
                    data: "tarifa"
                },
            ]
        });

        $("#btnReportePorIpress").on("click", function() {
            $("#modalReportePorIpress").modal("show");
        });
        $("#tablaReportePorIpress").DataTable({
            ordering: false,
            columns: [{
                    title: "Periodo",
                    data: "periodo"
                },
                {
                    title: "IPRESS",
                    data: "codigo_ipress"
                },
                {
                    title: "Nombre IPRESS",
                    data: "nombre_ipress"
                },
                {
                    title: "Categoria",
                    data: "categoria"
                },
                {
                    title: "Tipo Atencion",
                    data: "tipo_atencion"
                },
                {
                    title: "Tipo Consulta",
                    data: "tipo_consulta"
                },
                {
                    title: "Total",
                    data: "total"
                },
                /* {
                                                    title: "Acciones",
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
                                                } */
            ]
        });
        $("#tablaReportePorIpress2").DataTable({
            columns: [{
                    title: "Periodo",
                    data: "periodo"
                },
                {
                    title: "IPRESS",
                    data: "codigo_ipress"
                },
                {
                    title: "Nombre IPRESS",
                    data: "nombre_ipress"
                },
                {
                    title: "Categoria",
                    data: "categoria"
                },
                {
                    title: "Tipo Atencion",
                    data: "tipo_atencion"
                },
                {
                    title: "Total",
                    data: "total"
                },
                {
                    title: "Accion",
                    data: null,
                    render: function() {
                        return `
                            <button class="btn btn-primary btn-export-CAF"
                                    data-fecha-ini="" 
                                    data-fecha-fin="" 
                                    style="width: 32px; height: 32px; border-radius: 8px; padding: 0; font-size: 17px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
                            </button>`;
                    },
                    className: "text-center"
                },
            ]
        });
        $("#tablaReportePorIpress3").DataTable({
            columns: [{
                    title: "Periodo",
                    data: "periodo"
                },
                {
                    title: "IPRESS",
                    data: "codigo_ipress"
                },
                {
                    title: "Nombre IPRESS",
                    data: "nombre_ipress"
                },
                {
                    title: "Categoria",
                    data: "categoria"
                },
                {
                    title: "Tipo Examen",
                    data: "tipo_examen"
                },
                {
                    title: "Cantidad Examen",
                    data: "total"
                },
                {
                    title: "Accion",
                    data: null,
                    render: function() {
                        return `
                            <button class="btn btn-primary btn-export-CAP"
                                    data-fecha-ini="" 
                                    data-fecha-fin="" 
                                    style="width: 32px; height: 32px; border-radius: 8px; padding: 0; font-size: 17px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
                            </button>`;
                    },
                    className: "text-center"
                },
            ]
        });
        $("#tablaReportePorIpress4").DataTable({
            columns: [{
                    title: "IPRESS",
                    data: "codigo_ipress"
                },
                {
                    title: "Nombre IPRESS",
                    data: "nombre_ipress"
                },
                {
                    title: "Cod. Producto",
                    data: "codigo_producto"
                },
                {
                    title: "Nombre Producto",
                    data: "nombre_producto"
                },
                {
                    title: "Cantidad Solicitada",
                    data: "cantidad_solicitada"
                },
                {
                    title: "Cantidad Atendida",
                    data: "cantidad_atendida"
                },
            ]
        });
        $("#tablaReportePorIpress5").DataTable({
            columns: [{
                    title: "IPRESS",
                    data: "codigo_ipress"
                },
                {
                    title: "Nombre IPRESS",
                    data: "nombre_ipress"
                },
                {
                    title: "Cod. Procedimiento",
                    data: "codigo_procedimiento"
                },
                {
                    title: "Nombre Procedimiento",
                    data: "nombre_procedimiento"
                },
                {
                    title: "Cantidad Atendida",
                    data: "total"
                },
            ]
        });

        $("#buscarPeriodo").on("click", function(e) {
            const id = this.id;
            const isChecked = $(this).is(":checked");

            if (isChecked) {
                $("#containerPeriodoTotal").show();
                $("#containerFechaIni").hide();
                $("#containerFechaFin").hide();
                $("#fechaInicio").val("");
                $("#fechaFin").val("");
            } else {
                $("#containerPeriodoTotal").hide();
                $("#containerFechaIni").show();
                $("#containerFechaFin").show();
                $("#periodoTotal").val("");
            }
        });

        $("#fechaInicio, #fechaFin").on("change", function() {
            const inicio = $("#fechaInicio").val();
            const fin = $("#fechaFin").val();

            if (inicio && fin && inicio > fin) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La fecha de inicio no puede ser mayor a la fecha final.',
                    timer: 2000,
                    showConfirmButton: false
                });
                $("#fechaInicio").val("");
            }
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
                    title: "Acciones",
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
        $("#btnBuscarReporte").on("click", function(e) {
            const conPeriodo = $("#buscarPeriodo").is(":checked");
            const periodo = $("#periodoTotal").val();
            const fechaIni = $("#fechaInicio").val();
            const fechaFin = $("#fechaFin").val();
            let tableIpress = $("#tblFinalIpressFilter").DataTable();
            let ipress = tableIpress.rows().data().toArray().map(r => r.id);
            console.log(ipress);
            if (conPeriodo) {
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
            /* if (ipress.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar una ipress.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            } */
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
                url: "/listarConsolidadoAcciones",
                type: "POST",
                data: {
                    ipress,
                    fechaIni: !conPeriodo ? fechaIni : primerDiaMes(periodo),
                    fechaFin: !conPeriodo ? fechaFin : ultimoDiaMes(periodo),
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    Swal.close();
                    let tabla = $("#tblReporteCA").DataTable();
                    tabla.clear();
                    tabla.rows.add(response);
                    tabla.draw();
                    let total = response.length;
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire("Error", "Ocurrió un problema al obtener los datos", "error");
                }
            });
        });
        $("#btnExportarExcel, #btnExportarCSV").on("click", function(e) {
            let idBtn = e.currentTarget.id;
            let tipoReporte = null;
            let tableIpress = $("#tblFinalIpressFilter").DataTable();
            let ipress = tableIpress.rows().data().toArray().map(r => r.id);
            const conPeriodo = $("#buscarPeriodo").is(":checked");
            const periodo = $("#periodoTotal").val();
            const fechaIni = $("#fechaInicio").val();
            const fechaFin = $("#fechaFin").val();
            const fechaIniParsed = !conPeriodo ? fechaIni : primerDiaMes(periodo);
            const fechaFinParsed = !conPeriodo ? fechaFin : ultimoDiaMes(periodo);
            if (conPeriodo) {
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
            /* if (ipress.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar una ipress.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            } */
            if (idBtn == "btnExportarExcel") {
                tipoReporte = "excel";
            } else if (idBtn == "btnExportarCSV") {
                tipoReporte = "csv";
            }
            let url = "/exportConsolidadoAcciones?";
            if (ipress.length > 0) {
                url += "ipress[]=" + ipress.join("&ipress[]=") + "&";
            }
            url += "fechaIni=" + fechaIniParsed +
                "&fechaFin=" + fechaFinParsed +
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
                <h3 class="panel-title">Consolidado de las acciones desarrolladas por la Dirsapol PNP</h3>
            </div>
            <div class="panel-body panel_custom-body">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <strong>Filtros de Búsqueda</strong>
                    </div>

                    <div class="panel-body">
                        <!-- FILA 1: Buscar por periodo y rangos -->
                        <div class="row">
                            <div class="col-md-3" id="containerPeriodoTotal" style="display: none;">
                                <div class="form-group">
                                    <label for="periodoTotal">Periodo</label>
                                    <input type="month" class="form-control" id="periodoTotal" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="checkbox" style="margin-top: 5px;">
                                    <label>
                                        <input type="checkbox" id="buscarPeriodo"> Buscar por Periodo
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3" id="containerFechaIni">
                                <div class="form-group">
                                    <label for="fechaInicio">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="fechaInicio" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-3" id="containerFechaFin">
                                <div class="form-group">
                                    <label for="fechaFin">Fecha Fin</label>
                                    <input type="date" class="form-control" id="fechaFin" placeholder="">
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
                                            <th class="text-center">Código</th>
                                            {{-- <th class="text-center">Descripción</th>
                                            <th class="text-center">Inicio Proceso</th> --}}
                                            <th class="text-center">Fin Proceso</th>
                                            <th class="text-center">Acciones</th>
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
                                    <i class="glyphicon glyphicon-remove"></i> Limpiar
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
                            <button type="button" class="btn btn-warning" id="btnReportePorIpress">
                                Reporte por IPRESS
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tblReporteCA" class="table table-bordered table-condensed" width="100%">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Grupo</th>
                                    <th>Descripcion</th>
                                    <th>Total General</th>
                                    <th>Total</th>
                                    <th>Titular</th>
                                    <th>Familiar</th>
                                    <th>Accion Civica</th>
                                    <th>Civiles Tarifa</th>
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
    @include('frontend.seis.filters.modal_reporte_ipress_2')
    @include('frontend.seis.filters.modal_reporte_ipress_3')
    @include('frontend.seis.filters.modal_reporte_ipress_4')
    @include('frontend.includes.footer')
@endsection
