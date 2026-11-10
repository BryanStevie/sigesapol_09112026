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
            /* $('#tablaDiasCamas').DataTable({
                language: {
                    emptyTable: "No hay registros disponibles",
                },
            }); */
            $('#tablaDiagnostico').DataTable({
                columns: [{
                        data: 'codigo',
                        title: 'Código'
                    },
                    {
                        data: 'nombre',
                        title: 'Nombre Diagnostico'
                    },
                    {
                        data: 'enero',
                        title: 'ENE'
                    },
                    {
                        data: 'febrero',
                        title: 'FEB'
                    },
                    {
                        data: 'marzo',
                        title: 'MAR'
                    },
                    {
                        data: 'abril',
                        title: 'ABR'
                    },
                    {
                        data: 'mayo',
                        title: 'MAY'
                    },
                    {
                        data: 'junio',
                        title: 'JUN'
                    },
                    {
                        data: 'julio',
                        title: 'JUL'
                    },
                    {
                        data: 'agosto',
                        title: 'AGO'
                    },
                    {
                        data: 'septiembre',
                        title: 'SEP'
                    },
                    {
                        data: 'octubre',
                        title: 'OCT'
                    },
                    {
                        data: 'noviembre',
                        title: 'NOV'
                    },
                    {
                        data: 'diciembre',
                        title: 'DIC'
                    },
                    {
                        data: 'total',
                        title: 'TOTAL'
                    }
                ]
            });
            $('#tablaNumerico').DataTable({
                columns: [{
                        data: 'ipress',
                        title: 'UNIDAD 1'
                    },
                    {
                        data: 'unidad',
                        title: 'UNIDAD 2'
                    },
                    {
                        data: 'enero',
                        title: 'ENE'
                    },
                    {
                        data: 'febrero',
                        title: 'FEB'
                    },
                    {
                        data: 'marzo',
                        title: 'MAR'
                    },
                    {
                        data: 'abril',
                        title: 'ABR'
                    },
                    {
                        data: 'mayo',
                        title: 'MAY'
                    },
                    {
                        data: 'junio',
                        title: 'JUN'
                    },
                    {
                        data: 'julio',
                        title: 'JUL'
                    },
                    {
                        data: 'agosto',
                        title: 'AGO'
                    },
                    {
                        data: 'septiembre',
                        title: 'SEP'
                    },
                    {
                        data: 'octubre',
                        title: 'OCT'
                    },
                    {
                        data: 'noviembre',
                        title: 'NOV'
                    },
                    {
                        data: 'diciembre',
                        title: 'DIC'
                    },
                    {
                        data: 'total',
                        title: 'TOTAL'
                    }
                ]
            });
            $('#tablaPaciente').DataTable({
                columns: [{
                        data: 'codigo_ipress',
                        title: 'IPRESS'
                    },
                    {
                        data: 'nombre_ipress',
                        title: 'NOM IPRESS'
                    },
                    {
                        data: 'id_atencion',
                        title: 'ID ATENCION'
                    },
                    {
                        data: 'fecha_atencion',
                        title: 'FEC ATENCION'
                    },
                    {
                        data: 'nombre_paciente',
                        title: 'NOM PACIENTE'
                    },
                    {
                        data: 'diag_basico',
                        title: 'BASICO'
                    },
                    {
                        data: 'nombre_basico',
                        title: 'NOM DIAGNOSTICO BASICO'
                    },
                    {
                        data: 'diag_intermedio',
                        title: 'INTERMEDIO'
                    },
                    {
                        data: 'nombre_intermedio',
                        title: 'NOM DIAGNOSTICO INTERMEDIO'
                    },
                    {
                        data: 'diag_final',
                        title: 'FINAL'
                    },
                    {
                        data: 'nombre_final',
                        title: 'NOM DIAGNOSTICO FINAL'
                    },
                    {
                        data: 'edad_meses',
                        title: 'EDAD MESES'
                    },
                    {
                        data: 'edad',
                        title: 'EDAD'
                    },
                    {
                        data: 'tipo_beneficiario',
                        title: 'BENEFICIARIO'
                    },
                    {
                        data: 'grado',
                        title: 'GRADO'
                    },
                    {
                        data: 'situacion',
                        title: 'SITUACION'
                    }
                ]
            });
            $("#btnModalIpress5").on("click", function() {
                $("#seleccionarIPRESSModal").modal("show");
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

        function validarAnio(input) {
            input.value = input.value
                .replace(/[^0-9]/g, '') // solo números
                .replace(/^0+/, ''); // elimina ceros iniciales
        }

        $("#buscarPorEdad").on("change", function() {
            if ($("#buscarPorEdad").is(":checked")) {
                $("#edadDesde, #edadHasta").prop("disabled", false).show();
                $("label[for='edadDesde'], label[for='edadHasta']").show();
                $("#labelEdadDesde, #labelEdadHasta").show();
                $("#grupo_edad").val("")
                $("#grupo_edad").prop("disabled", true);
            } else {
                $("#edadDesde, #edadHasta").prop("disabled", true).hide();
                $("label[for='edadDesde'], label[for='edadHasta']").hide();
                $("#labelEdadDesde, #labelEdadHasta").hide();
                $("#edadDesde").val("");
                $("#edadHasta").val("");
                $("#grupo_edad").prop("disabled", false);
            }
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

        $("#edadDesde").on("change", function() {
            const desde = parseInt($(this).val());
            const hasta = parseInt($("#edadHasta").val());

            if (hasta && desde > hasta) {
                alert("La edad Desde no puede ser mayor que la edad Hasta");
                $(this).val("");
            }
        });

        $("#edadHasta").on("change", function() {
            const hasta = parseInt($(this).val());
            const desde = parseInt($("#edadDesde").val());

            if (desde && hasta < desde) {
                alert("La edad Hasta no puede ser menor que la edad Desde");
                $(this).val("");
            }
        });

        $("#btnBuscarReporte").on("click", function(e) {
            let tipoReporte = "json";
            var activeTab = $('.nav-tabs li.active a').attr('href');
            const buscarPorEdad = $("#buscarPorEdad").is(":checked");
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const periodo = $("#periodo").val();
            const anio = $("#año").val();
            const diagnostico = $("#diagnostico_text").val();
            const tipo_atencion = $("#tipo_atencion").val();
            const hora_fallecimiento = $("#hora_fallecimiento").val();
            const servicio = $("#servicio_id").val();
            const sexo = $("#sexo").val();
            const upss = $("#upss").val();
            const grupo_edad = $("#grupo_edad").val();
            const edadDesde = $("#edadDesde").val();
            const edadHasta = $("#edadHasta").val();

            if (periodo === "" && anio === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar un periodo o ingresar el año.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            if (buscarPorEdad) {
                if (edadDesde === "" || edadHasta === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe ingresar las edades Desde y Hasta.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
            }

            /* if (sexo == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar el sexo.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            } */

            if (ipress.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar una ipress.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            switch (activeTab) {
                case "#tabDiagnostico":
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
                        url: "/listarReporteMortalidadDiagnostico",
                        type: "POST",
                        data: {
                            ipress,
                            anio: periodo !== "" ? "" : anio,
                            fechaIni: periodo !== "" ? primerDiaMes(periodo) : "",
                            fechaFin: periodo !== "" ? ultimoDiaMes(periodo) : "",
                            diagnostico,
                            hora_fallecimiento,
                            sexo,
                            upss,
                            grupo_edad,
                            servicio,
                            edadDesde,
                            edadHasta,
                            tipo_atencion,
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tablaDiagnostico").DataTable();
                            tabla.clear();
                            tabla.rows.add(response);
                            tabla.draw();
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire("Error",
                                "Ocurrió un problema al obtener los datos", "error");
                        }
                    });
                    break;
                case "#tabNumerico":
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
                        url: "/listarReporteMortalidadNumerico",
                        type: "POST",
                        data: {
                            ipress,
                            anio: periodo !== "" ? "" : anio,
                            fechaIni: periodo !== "" ? primerDiaMes(periodo) : "",
                            fechaFin: periodo !== "" ? ultimoDiaMes(periodo) : "",
                            diagnostico,
                            hora_fallecimiento,
                            sexo,
                            upss,
                            grupo_edad,
                            servicio,
                            edadDesde,
                            edadHasta,
                            tipo_atencion,
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tablaNumerico").DataTable();
                            tabla.clear();
                            tabla.rows.add(response);
                            tabla.draw();
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire("Error",
                                "Ocurrió un problema al obtener los datos", "error");
                        }
                    });
                    break;
                case "#tabPaciente":
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
                        url: "/listarReporteMortalidadPaciente",
                        type: "POST",
                        data: {
                            ipress,
                            anio: periodo !== "" ? "" : anio,
                            fechaIni: periodo !== "" ? primerDiaMes(periodo) : "",
                            fechaFin: periodo !== "" ? ultimoDiaMes(periodo) : "",
                            diagnostico,
                            hora_fallecimiento,
                            sexo,
                            upss,
                            grupo_edad,
                            servicio,
                            edadDesde,
                            edadHasta,
                            tipo_atencion,
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tablaPaciente").DataTable();
                            tabla.clear();
                            tabla.rows.add(response);
                            tabla.draw();
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire("Error",
                                "Ocurrió un problema al obtener los datos", "error");
                        }
                    });
                    break;
            }
        });

        $("#btnExportarExcel").on("click", function(e) {
            let tipoReporte = "";
            let idBtn = e.currentTarget.id;
            if (idBtn == "btnExportarExcel") {
                tipoReporte = "excel";
            } else if (idBtn == "btnExportarCSV") {
                tipoReporte = "csv";
            }
            var activeTab = $('.nav-tabs li.active a').attr('href');
            const buscarPorEdad = $("#buscarPorEdad").is(":checked");
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const periodo = $("#periodo").val();
            const anio = $("#año").val();
            const diagnostico = $("#diagnostico_text").val();
            const tipo_atencion = $("#tipo_atencion").val();
            const hora_fallecimiento = $("#hora_fallecimiento").val();
            const servicio = $("#servicio_id").val();
            const sexo = $("#sexo").val();
            const upss = $("#upss").val();
            const grupo_edad = $("#grupo_edad").val();
            const edadDesde = $("#edadDesde").val();
            const edadHasta = $("#edadHasta").val();

            if (periodo === "" && anio === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar un periodo o ingresar el año.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            if (buscarPorEdad) {
                if (edadDesde === "" || edadHasta === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe ingresar las edades Desde y Hasta.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
            }

            /* if (sexo == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar el sexo.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            } */

            if (ipress.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar una ipress.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            switch (activeTab) {
                case "#tabDiagnostico":
                    let urlDiagnostico = "/exportarReporteMortalidadDiagnostico?" +
                        "ipress[]=" + ipress.join("&ipress[]=") +
                        "&anio=" + encodeURIComponent(periodo !== "" ? "" : anio) +
                        "&fechaIni=" + encodeURIComponent(periodo !== "" ? primerDiaMes(periodo) : "") +
                        "&fechaFin=" + encodeURIComponent(periodo !== "" ? ultimoDiaMes(periodo) : "") +
                        "&diagnostico=" + encodeURIComponent(diagnostico) +
                        "&hora_fallecimiento=" + encodeURIComponent(hora_fallecimiento) +
                        "&sexo=" + encodeURIComponent(sexo) +
                        "&upss=" + encodeURIComponent(upss) +
                        "&grupo_edad=" + encodeURIComponent(grupo_edad) +
                        "&servicio=" + encodeURIComponent(servicio) +
                        "&edadDesde=" + encodeURIComponent(edadDesde) +
                        "&edadHasta=" + encodeURIComponent(edadHasta) +
                        "&tipo_atencion=" + encodeURIComponent(tipo_atencion) +
                        "&tipoReporte=" + encodeURIComponent(tipoReporte);

                    window.location.href = urlDiagnostico;
                    break;
                case "#tabNumerico":
                    let urlNumerico = "/exportarReporteMortalidadNumerico?" +
                        "ipress[]=" + ipress.join("&ipress[]=") +
                        "&anio=" + encodeURIComponent(periodo !== "" ? "" : anio) +
                        "&fechaIni=" + encodeURIComponent(periodo !== "" ? primerDiaMes(periodo) : "") +
                        "&fechaFin=" + encodeURIComponent(periodo !== "" ? ultimoDiaMes(periodo) : "") +
                        "&diagnostico=" + encodeURIComponent(diagnostico) +
                        "&hora_fallecimiento=" + encodeURIComponent(hora_fallecimiento) +
                        "&sexo=" + encodeURIComponent(sexo) +
                        "&upss=" + encodeURIComponent(upss) +
                        "&grupo_edad=" + encodeURIComponent(grupo_edad) +
                        "&servicio=" + encodeURIComponent(servicio) +
                        "&edadDesde=" + encodeURIComponent(edadDesde) +
                        "&edadHasta=" + encodeURIComponent(edadHasta) +
                        "&tipo_atencion=" + encodeURIComponent(tipo_atencion) +
                        "&tipoReporte=" + encodeURIComponent(tipoReporte);
                    window.location.href = urlNumerico;
                    break;
                case "#tabPaciente":
                    let urlPaciente = "/exportarReporteMortalidadPaciente?" +
                        "ipress[]=" + ipress.join("&ipress[]=") +
                        "&anio=" + encodeURIComponent(periodo !== "" ? "" : anio) +
                        "&fechaIni=" + encodeURIComponent(periodo !== "" ? primerDiaMes(periodo) : "") +
                        "&fechaFin=" + encodeURIComponent(periodo !== "" ? ultimoDiaMes(periodo) : "") +
                        "&diagnostico=" + encodeURIComponent(diagnostico) +
                        "&hora_fallecimiento=" + encodeURIComponent(hora_fallecimiento) +
                        "&sexo=" + encodeURIComponent(sexo) +
                        "&upss=" + encodeURIComponent(upss) +
                        "&grupo_edad=" + encodeURIComponent(grupo_edad) +
                        "&servicio=" + encodeURIComponent(servicio) +
                        "&edadDesde=" + encodeURIComponent(edadDesde) +
                        "&edadHasta=" + encodeURIComponent(edadHasta) +
                        "&tipo_atencion=" + encodeURIComponent(tipo_atencion) +
                        "&tipoReporte=" + encodeURIComponent(tipoReporte);
                    window.location.href = urlPaciente;
                    break;
            }
        });

        var ipressDefault = {!! json_encode($ipressActual) !!};
        if (ipressDefault) {
            tblFinalIpressFilter.row.add(ipressDefault).draw(false);
        }
        if (!{!! json_encode($flag_establecimientos) !!}) {
            $("#btnModalIpress5").prop("disabled", true);
        }
    </script>
@endsection
@section('content')
    <div class="container-fluid" style="margin: 0 100px;">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">REPORTE DE MORTALIDAD</h3>
            </div>
            <div class="panel-body panel_custom-body">
                <div class="panel panel-success">
                    <div class="panel-body">
                        <!-- FILA 1: Buscar por periodo y rangos -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="año">Año</label>
                                    <input id="año" type="text" class="form-control" placeholder="aaaa"
                                        oninput="validarAnio(this)">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="periodo">Periodo</label>
                                    <input id="periodo" type="month" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="diagnostico_text">Nombre Diagnóstico</label>
                                    <input id="diagnostico_text" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipo_atencion">Lugar Fallecimiento</label>
                                    <select id="tipo_atencion" class="form-control">
                                        <option value="">- Seleccionar -</option>
                                        <option value="2">EMERGENCIA</option>
                                        <option value="3">HOSPITALIZACION</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 2 -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="hora_fallecimiento">Hr. Fallecimiento</label>
                                    <select id="hora_fallecimiento" class="form-control">
                                        <option value="">- Seleccionar -</option>
                                        <option>FALLECIDO 12 HORAS</option>
                                        <option>FALLECIDO 24 HORAS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="servicio_id">Servicio</label>
                                    <select id="servicio_id" class="form-control">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($serviciosParaUps as $servicio)
                                            <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sexo">Sexo</label>
                                    <select id="sexo" class="form-control">
                                        <option value="">- Seleccionar -</option>
                                        <option value="M">MASCULINO</option>
                                        <option value="F">FEMENINO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="upss">UPS</label>
                                    <input id="upss" type="text" class="form-control"
                                        placeholder="CÓDIGO Y/O DESCRIPCIÓN">
                                </div>
                            </div>
                        </div>

                        <!-- Fila 3 -->
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="grupo_edad">Grupo Edad</label>
                                    <select id="grupo_edad" class="form-control">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($grupos_edad as $grupo_edad)
                                            <option value="{{ $grupo_edad->codigoelemento }}">
                                                {{ $grupo_edad->descripcionlocal }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="checkbox" style="margin-top: 25px;">
                                    <label>
                                        <input type="checkbox" id="buscarPorEdad"> Buscar por Edad
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="edadDesde" style="display: none;">Desde</label>
                                    <input id="edadDesde" type="number" class="form-control" style="display: none;"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="edadHasta" style="display: none;">Hasta</label>
                                    <input id="edadHasta" type="number" class="form-control" style="display: none;"
                                        disabled>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- TABLA IPRESS -->
                        <div class="row">
                            <div class="col-md-1" style="text-align: right;">
                                <strong>IPRESS</strong>
                            </div>
                            <div class="col-md-11">
                                <table id="tblFinalIpressFilter" class="table table-bordered table-condensed text-center">
                                    <thead>
                                        <tr style="background-color: #e5f2e0;">
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Descripción</th>
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
                                <button id="btnModalIpress5" class="btn btn-success btn-sm">
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
                        </div>
                    </div>
                    <div class="panel panel-default" style="margin: 0; margin-top: 10px;">
                        <div class="panel-body">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tabDiagnostico" data-toggle="tab">Por Diagnostico</a></li>
                                <li><a href="#tabNumerico" data-toggle="tab">Numerico</a></li>
                                <li><a href="#tabPaciente" data-toggle="tab">Por Paciente</a></li>
                            </ul>

                            <div class="tab-content" style="margin-top:15px;">
                                <div class="tab-pane fade in active" id="tabDiagnostico">
                                    <div class="table-responsive">
                                        <table id="tablaDiagnostico" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Nombre Diagnostico</th>
                                                    <th>ENE</th>
                                                    <th>FEB</th>
                                                    <th>MAR</th>
                                                    <th>ABR</th>
                                                    <th>MAY</th>
                                                    <th>JUN</th>
                                                    <th>JUL</th>
                                                    <th>AGO</th>
                                                    <th>SEP</th>
                                                    <th>OCT</th>
                                                    <th>NOV</th>
                                                    <th>DIC</th>
                                                    <th>TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Filas dinámicas -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabNumerico">
                                    <div class="table-responsive">
                                        <table id="tablaNumerico" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>UNIDAD 1</th>
                                                    <th>UNIDAD 2</th>
                                                    <th>ENE</th>
                                                    <th>FEB</th>
                                                    <th>MAR</th>
                                                    <th>ABR</th>
                                                    <th>MAY</th>
                                                    <th>JUN</th>
                                                    <th>JUL</th>
                                                    <th>AGO</th>
                                                    <th>SEP</th>
                                                    <th>OCT</th>
                                                    <th>NOV</th>
                                                    <th>DIC</th>
                                                    <th>TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Filas dinámicas -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabPaciente">
                                    <div class="table-responsive">
                                        <table id="tablaPaciente" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>IPRESS</th>
                                                    <th>NOM IPRESS</th>
                                                    <th>ID ATENCION</th>
                                                    <th>FEC ATENCION</th>
                                                    <th>NOM PACIENTE</th>
                                                    <th>BASICO</th>
                                                    <th>NOM DIAGNOSTICO BASICO</th>
                                                    <th>INTERMEDIO</th>
                                                    <th>NOM DIAGNOSTICO INTERMEDIO</th>
                                                    <th>FINAL</th>
                                                    <th>NOM DIAGNOSTICO FINAL</th>
                                                    <th>EDAD MESES</th>
                                                    <th>EDAD</th>
                                                    <th>BENEFICIARIO</th>
                                                    <th>GRADO</th>
                                                    <th>SITUACION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Filas dinámicas -->
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
    </div>
    @include('frontend.seis.filters.modal_add_ipress')
    @include('frontend.includes.footer')
@endsection
