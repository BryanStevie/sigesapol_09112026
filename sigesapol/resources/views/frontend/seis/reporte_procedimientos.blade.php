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
            $('#tablaProcedimientos').DataTable({
                columns: [{
                        data: 'codigo',
                        title: 'Código'
                    },
                    {
                        data: 'nombre_procedimiento',
                        title: 'Nombre Procedimiento'
                    },
                    {
                        data: 'tipo_procedimiento',
                        title: 'Tipo Procedimiento'
                    },
                    {
                        data: 'titular',
                        title: 'Titular'
                    },
                    {
                        data: 'familiar',
                        title: 'Familiar'
                    },
                    {
                        data: 'civica',
                        title: 'Cívica'
                    },
                    {
                        data: 'pagante',
                        title: 'Pagante'
                    }
                ]
            });
            $('#tablaMedico').DataTable({
                columns: [{
                        data: 'codigo_ipress',
                        title: 'IPRESS'
                    },
                    {
                        data: 'nombre_ipress',
                        title: 'NOMBRE IPRESS'
                    },
                    {
                        data: 'dni',
                        title: 'DNI'
                    },
                    {
                        data: 'nombre_medico',
                        title: 'NOMBRE DEL MEDICO'
                    },
                    {
                        data: 'grado',
                        title: 'GRADO'
                    },
                    {
                        data: 'colegio',
                        title: 'COLEGIO'
                    },
                    {
                        data: 'total',
                        title: 'TOTAL',
                        className: 'text-center'
                    },
                    {
                        data: 'anestesia',
                        title: 'ANESTESIA',
                        className: 'text-center'
                    },
                    {
                        data: 'cirugia',
                        title: 'CIRUGIA',
                        className: 'text-center'
                    },
                    {
                        data: 'evaluacion',
                        title: 'EVALUACION',
                        className: 'text-center'
                    },
                    {
                        data: 'laboratorio',
                        title: 'LABORATORIO',
                        className: 'text-center'
                    },
                    {
                        data: 'medicina',
                        title: 'MEDICINA',
                        className: 'text-center'
                    },
                    {
                        data: 'nanda',
                        title: 'NANDA',
                        className: 'text-center'
                    },
                    {
                        data: 'odontologia',
                        title: 'ODONTOLOGIA',
                        className: 'text-center'
                    },
                    {
                        data: 'radiologia',
                        title: 'RADIOLOGIA',
                        className: 'text-center'
                    },
                    {
                        data: 'tiempo',
                        title: 'MINUTO',
                        className: 'text-center'
                    }
                ]
            });
            $('#tablaCE').DataTable({
                columns: [{
                        data: 'codigo_ipress',
                        title: 'IPRESS'
                    },
                    {
                        data: 'nombre_ipress',
                        title: 'NOMBRE IPRESS'
                    },
                    {
                        data: 'dni',
                        title: 'DNI'
                    },
                    {
                        data: 'nombre',
                        title: 'NOMBRE DEL MEDICO'
                    },
                    {
                        data: 'grado',
                        title: 'GRADO'
                    },
                    {
                        data: 'nombre_upss',
                        title: 'NOMBRE UPSS'
                    },
                    {
                        data: 'total',
                        title: 'TOTAL',
                        className: 'text-center'
                    }
                ]
            });
            $('#tablaProduccionIndividual').DataTable({
                columns: [{
                        data: 'codigo_ipress',
                        title: 'IPRESS'
                    },
                    {
                        data: 'nombre_ipress',
                        title: 'NOMBRE IPRESS'
                    },
                    {
                        data: 'dni',
                        title: 'DNI'
                    },
                    {
                        data: 'nombre_medico',
                        title: 'MEDICO RESPONSABLE'
                    },
                    {
                        data: 'colegio',
                        title: 'COLEGIO'
                    },
                    {
                        data: 'codigo',
                        title: 'CODIGO'
                    },
                    {
                        data: 'descripcion',
                        title: 'NOMBRE PROCEDMIENTO'
                    },
                    {
                        data: 'seccion',
                        title: 'TIPO PROCEDIMIENTO'
                    },
                    {
                        data: 'cantidad',
                        title: 'TOTAL PROCEDIMIENTO',
                        className: 'text-center'
                    },
                    {
                        data: 'tiempo',
                        title: 'TOTAL MINUTOS',
                        className: 'text-center'
                    }
                ]
            });
            $('#tablaPaciente').DataTable({
                columns: [{
                        data: 'id_prestacion',
                        title: 'ID PRESTACION'
                    },
                    {
                        data: 'fecha_atencion',
                        title: 'FECHA ATENCION'
                    },
                    {
                        data: 'dni',
                        title: 'DNI PACIENTE'
                    },
                    {
                        data: 'asegurado',
                        title: 'NOMBRE PACIENTE'
                    },
                    {
                        data: 'dni_responsable',
                        title: 'DNI RESPONSABLE'
                    },
                    {
                        data: 'medico_responsable',
                        title: 'PROF. REALIZA PROCEDIMIENTO'
                    },
                    {
                        data: 'colegio_responsable',
                        title: 'COL. PROFESIONAL'
                    },
                    {
                        data: 'dni_indica',
                        title: 'DNI INDICA'
                    },
                    {
                        data: 'nombre_medico_indica',
                        title: 'MEDICO INDICA PROCEDIMIENTO'
                    },
                    {
                        data: 'colegio_indica',
                        title: 'COL. INDICA'
                    },
                    {
                        data: 'codigo_diagnostico',
                        title: 'COD. DIAGNOSTICO'
                    },
                    {
                        data: 'diagnostico',
                        title: 'NOM. DIAGNOSTICO'
                    },
                    {
                        data: 'codigo',
                        title: 'COD. PROCEDIMIENTO'
                    },
                    {
                        data: 'descripcion_procedimiento',
                        title: 'NOM. PROCEDIMIENTO'
                    },
                    {
                        data: 'tipo_procedimiento',
                        title: 'TIPO PROCEDIMIENTO'
                    },
                    {
                        data: 'tipo_atenciom',
                        title: 'TIPO ATENCION'
                    },
                    {
                        data: 'ups',
                        title: 'UPS'
                    },
                    {
                        data: 'nombre_consultorio',
                        title: 'SERVICIO',
                        className: 'text-center'
                    },
                    {
                        data: 'cantidad',
                        title: 'TOTAL PROCEDIMIENTOS',
                        className: 'text-center'
                    },
                    {
                        data: 'tiempo',
                        title: 'TOTAL MINUTOS',
                        className: 'text-center'
                    }
                ]
            });
             $('#tablaPacienteU').DataTable({
                columns: [{
                        data: 'id_prestacion',
                        title: 'ID PRESTACION'
                    },
                    {
                        data: 'fecha_atencion',
                        title: 'FECHA ATENCION'
                    },
                    {
                        data: 'dni',
                        title: 'DNI PACIENTE'
                    },
                    {
                        data: 'asegurado',
                        title: 'NOMBRE PACIENTE'
                    },
                    {
                        data: 'dni_responsable',
                        title: 'DNI RESPONSABLE'
                    },
                    {
                        data: 'medico_responsable',
                        title: 'PROF. REALIZA PROCEDIMIENTO'
                    },
                    {
                        data: 'colegio_responsable',
                        title: 'COL. PROFESIONAL'
                    },
                    {
                        data: 'dni_indica',
                        title: 'DNI INDICA'
                    },
                    {
                        data: 'nombre_medico_indica',
                        title: 'MEDICO INDICA PROCEDIMIENTO'
                    },
                    {
                        data: 'colegio_indica',
                        title: 'COL. INDICA'
                    },
                    {
                        data: 'codigo_diagnostico',
                        title: 'COD. DIAGNOSTICO'
                    },
                    {
                        data: 'diagnostico',
                        title: 'NOM. DIAGNOSTICO'
                    },
                    {
                        data: 'codigo',
                        title: 'COD. PROCEDIMIENTO'
                    },
                    {
                        data: 'descripcion_procedimiento',
                        title: 'NOM. PROCEDIMIENTO'
                    },
                    {
                        data: 'tipo_procedimiento',
                        title: 'TIPO PROCEDIMIENTO'
                    },
                    {
                        data: 'tipo_atenciom',
                        title: 'TIPO ATENCION'
                    },
                    {
                        data: 'ups',
                        title: 'UPS'
                    },
                    {
                        data: 'nombre_consultorio',
                        title: 'SERVICIO',
                        className: 'text-center'
                    },
                    {
                        data: 'cantidad',
                        title: 'TOTAL PROCEDIMIENTOS',
                        className: 'text-center'
                    },
                    {
                        data: 'tiempo',
                        title: 'TOTAL MINUTOS',
                        className: 'text-center'
                    }
                ]
            });
            $("#btnModalIpress5").on("click", function() {
                $("#seleccionarIPRESSModal").modal("show");
            });
        });

        $("#periodoInicio").on("change", function(e) {
            $("#fechaDesde").val("");
            $("#fechaHasta").val("");
        });

        $("#fechaDesde, #fechaHasta").on("change", function(e) {
            $("#periodoInicio").val("");
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
        $("#btnBuscarReporte").on("click", function(e) {
            let tipoReporte = "json";
            var activeTab = $('.nav-tabs li.active a').attr('href');
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const periodo = $("#periodoInicio").val();
            const fechaDesde = $("#fechaDesde").val();
            const fechaHasta = $("#fechaHasta").val();
            const codigoCpt = $("#codigoCpt").val();
            const tipoCpt = $("#tipoCpt").val();
            const descripcion = $("#descripcion").val();
            if (periodo === "") {
                if (
                    (fechaDesde !== "" && fechaHasta === "") ||
                    (fechaDesde === "" && fechaHasta !== "")
                ) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe completar ambas fechas del rango.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }

                if (fechaDesde === "" && fechaHasta === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar un periodo o un rango de fechas.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
            }
            switch (activeTab) {
                case "#tabCPT":
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
                        url: "/listarReporteProcedimientosCPT",
                        type: "POST",
                        data: {
                            ipress,
                            fechaInicio: periodo == "" ? fechaDesde : primerDiaMes(periodo),
                            fechaFin: periodo == "" ? fechaHasta : ultimoDiaMes(periodo),
                            codigoCpt,
                            tipoCpt,
                            descripcion,
                            tipoReporte
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tablaProcedimientos").DataTable();
                            tabla.clear();
                            tabla.rows.add(response);
                            tabla.draw();
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire("Error", "Ocurrió un problema al obtener los datos", "error");
                        }
                    });
                    break;
                case "#tabMedico":
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
                        url: "/listarReporteProcedimientosMedico",
                        type: "POST",
                        data: {
                            ipress,
                            fechaInicio: periodo == "" ? fechaDesde : primerDiaMes(periodo),
                            fechaFin: periodo == "" ? fechaHasta : ultimoDiaMes(periodo),
                            codigoCpt,
                            tipoCpt,
                            descripcion,
                            tipoReporte
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tablaMedico").DataTable();
                            tabla.clear();
                            tabla.rows.add(response);
                            tabla.draw();
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire("Error", "Ocurrió un problema al obtener los datos", "error");
                        }
                    });
                    break;
                case "#tabConsultaExterna":
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
                        url: "/listarReporteProcedimientosCE",
                        type: "POST",
                        data: {
                            ipress,
                            fechaInicio: periodo == "" ? fechaDesde : primerDiaMes(periodo),
                            fechaFin: periodo == "" ? fechaHasta : ultimoDiaMes(periodo),
                            codigoCpt,
                            tipoCpt,
                            descripcion,
                            tipoReporte
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tablaCE").DataTable();
                            tabla.clear();
                            tabla.rows.add(response);
                            tabla.draw();
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire("Error", "Ocurrió un problema al obtener los datos", "error");
                        }
                    });
                    break;
                case "#tabProduccionIndividual":
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
                        url: "/listarReporteProcedimientosProduccionIndividual",
                        type: "POST",
                        data: {
                            ipress,
                            fechaInicio: periodo == "" ? fechaDesde : primerDiaMes(periodo),
                            fechaFin: periodo == "" ? fechaHasta : ultimoDiaMes(periodo),
                            codigoCpt,
                            tipoCpt,
                            descripcion,
                            tipoReporte
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tablaProduccionIndividual").DataTable();
                            tabla.clear();
                            tabla.rows.add(response);
                            tabla.draw();
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire("Error", "Ocurrió un problema al obtener los datos", "error");
                        }
                    });
                    break;
                case "#tabPaciente":
                    if (ipress.length > 0) {
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
                            url: "/listarReporteProcedimientosPaciente",
                            type: "POST",
                            data: {
                                ipress,
                                fechaInicio: periodo == "" ? fechaDesde : primerDiaMes(periodo),
                                fechaFin: periodo == "" ? fechaHasta : ultimoDiaMes(periodo),
                                codigoCpt,
                                tipoCpt,
                                descripcion,
                                tipoReporte
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
                                Swal.fire("Error", "Ocurrió un problema al obtener los datos", "error");
                            }
                        });
                    }break;
                case "#tabPacienteU":
                    if (ipress.length > 0) {
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
                            url: "/listarReporteProcedimientosPacienteU",
                            type: "POST",
                            data: {
                                ipress,
                                fechaInicio: periodo == "" ? fechaDesde : primerDiaMes(periodo),
                                fechaFin: periodo == "" ? fechaHasta : ultimoDiaMes(periodo),
                                codigoCpt,
                                tipoCpt,
                                descripcion,
                                tipoReporte
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tablaPacienteU").DataTable();
                                tabla.clear();
                                tabla.rows.add(response);
                                tabla.draw();
                            },
                            error: function(xhr) {
                                Swal.close();
                                Swal.fire("Error", "Ocurrió un problema al obtener los datos", "error");
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar una ipress.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
            }
        });
        $("#btnExportarExcel, #btnExportarCSV").on("click", function(e) {
            let tipoReporte = "";
            let idBtn = e.currentTarget.id;
            if (idBtn == "btnExportarExcel") {
                tipoReporte = "excel";
            } else if (idBtn == "btnExportarCSV") {
                tipoReporte = "csv";
            }
            var activeTab = $('.nav-tabs li.active a').attr('href');
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const periodo = $("#periodoInicio").val();
            const fechaDesde = $("#fechaDesde").val();
            const fechaHasta = $("#fechaHasta").val();
            const codigoCpt = $("#codigoCpt").val();
            const tipoCpt = $("#tipoCpt").val();
            const descripcion = $("#descripcion").val();
            if (periodo === "") {
                if (
                    (fechaDesde !== "" && fechaHasta === "") ||
                    (fechaDesde === "" && fechaHasta !== "")
                ) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe completar ambas fechas del rango.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }

                if (fechaDesde === "" && fechaHasta === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar un periodo o un rango de fechas.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
            }
            const fechaInicio = periodo == "" ? fechaDesde : primerDiaMes(periodo);
            const fechaFin = periodo == "" ? fechaHasta : ultimoDiaMes(periodo);
            switch (activeTab) {
                case "#tabCPT":
                    const urlCPT = `/exportarReporteProcedimientosCPT?` +
                        "ipress[]=" + ipress.join("&ipress[]=") +
                        `&fechaInicio=${encodeURIComponent(fechaInicio)}` +
                        `&fechaFin=${encodeURIComponent(fechaFin)}` +
                        `&codigoCpt=${encodeURIComponent(codigoCpt)}` +
                        `&tipoCpt=${encodeURIComponent(tipoCpt)}` +
                        `&descripcion=${encodeURIComponent(descripcion)}` +
                        `&tipoReporte=${encodeURIComponent(tipoReporte)}`;
                    window.location.href = urlCPT;
                    break;
                case "#tabMedico":
                    const urlMedico = `/exportarReporteProcedimientosMedico?` +
                        "ipress[]=" + ipress.join("&ipress[]=") +
                        `&fechaInicio=${encodeURIComponent(fechaInicio)}` +
                        `&fechaFin=${encodeURIComponent(fechaFin)}` +
                        `&codigoCpt=${encodeURIComponent(codigoCpt)}` +
                        `&tipoCpt=${encodeURIComponent(tipoCpt)}` +
                        `&descripcion=${encodeURIComponent(descripcion)}` +
                        `&tipoReporte=${encodeURIComponent(tipoReporte)}`;
                    window.location.href = urlMedico;
                    break;
                case "#tabConsultaExterna":
                    const urlCE = `/exportarReporteProcedimientosCE?` +
                        "ipress[]=" + ipress.join("&ipress[]=") +
                        `&fechaInicio=${encodeURIComponent(fechaInicio)}` +
                        `&fechaFin=${encodeURIComponent(fechaFin)}` +
                        `&codigoCpt=${encodeURIComponent(codigoCpt)}` +
                        `&tipoCpt=${encodeURIComponent(tipoCpt)}` +
                        `&descripcion=${encodeURIComponent(descripcion)}` +
                        `&tipoReporte=${encodeURIComponent(tipoReporte)}`;
                    window.location.href = urlCE;
                    break;
                case "#tabProduccionIndividual":
                    const urlPI = `/exportarReporteProcedimientosProduccionIndividual?` +
                        "ipress[]=" + ipress.join("&ipress[]=") +
                        `&fechaInicio=${encodeURIComponent(fechaInicio)}` +
                        `&fechaFin=${encodeURIComponent(fechaFin)}` +
                        `&codigoCpt=${encodeURIComponent(codigoCpt)}` +
                        `&tipoCpt=${encodeURIComponent(tipoCpt)}` +
                        `&descripcion=${encodeURIComponent(descripcion)}` +
                        `&tipoReporte=${encodeURIComponent(tipoReporte)}`;
                    window.location.href = urlPI;
                    break;
                case "#tabPaciente":
                    if (ipress.length > 0) {
                        const urlPaciente = `/exportarReporteProcedimientosPaciente?` +
                            "ipress[]=" + ipress.join("&ipress[]=") +
                            `&fechaInicio=${encodeURIComponent(fechaInicio)}` +
                            `&fechaFin=${encodeURIComponent(fechaFin)}` +
                            `&codigoCpt=${encodeURIComponent(codigoCpt)}` +
                            `&tipoCpt=${encodeURIComponent(tipoCpt)}` +
                            `&descripcion=${encodeURIComponent(descripcion)}` +
                            `&tipoReporte=${encodeURIComponent(tipoReporte)}`;
                        window.location.href = urlPaciente;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar una ipress.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
                     case "#tabPacienteU":
                    if (ipress.length > 0) {
                        const urlPaciente = `/exportarReporteProcedimientosPacienteU?` +
                            "ipress[]=" + ipress.join("&ipress[]=") +
                            `&fechaInicio=${encodeURIComponent(fechaInicio)}` +
                            `&fechaFin=${encodeURIComponent(fechaFin)}` +
                            `&codigoCpt=${encodeURIComponent(codigoCpt)}` +
                            `&tipoCpt=${encodeURIComponent(tipoCpt)}` +
                            `&descripcion=${encodeURIComponent(descripcion)}` +
                            `&tipoReporte=${encodeURIComponent(tipoReporte)}`;
                        window.location.href = urlPaciente;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar una ipress.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
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
            $("#btnModalIpress5").prop("disabled", true);
        }
    </script>
@endsection
@section('content')
    <div class="container-fluid" style="margin: 0 100px;">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">R. PROCEDIMIENTOS</h3>
            </div>
            <div class="panel-body panel_custom-body">
                <div class="panel panel-success">
                    <div class="panel-body">
                        <!-- FILA 1: Buscar por periodo y rangos -->
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="periodoInicio">Periodo</label>
                                    <input type="month" class="form-control" id="periodoInicio">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="codigoCpt">Codigo CPT</label>
                                    <input type="text" class="form-control" id="codigoCpt">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipoCpt">Tipo CPT</label>
                                    <select type="text" class="form-control" id="tipoCpt">
                                        <option value="0">- Seleccionar -</option>
                                        @foreach ($secciones as $seccion)
                                            <option value="{{ $seccion->id }}">{{ $seccion->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fechaDesde">Fec. Desde</label>
                                    <input type="date" class="form-control" id="fechaDesde" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fechaHasta">Fec. Hasta</label>
                                    <input type="date" class="form-control" id="fechaHasta" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Nom. y Apell. del Profesional</label>
                                    <input type="text" class="form-control" id="descripcion">
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
                            <button type="button" class="btn btn-warning" id="btnExportarCSV">
                                Exportar CSV
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
                                <li class="active"><a href="#tabCPT" data-toggle="tab">Por CPT</a></li>
                                <li><a href="#tabMedico" data-toggle="tab">Por Médico</a></li>
                                <li><a href="#tabConsultaExterna" data-toggle="tab">Por Consulta Externa</a></li>
                                <li><a href="#tabProduccionIndividual" data-toggle="tab">Por Producción Individual</a>
                                </li>
                                <li><a href="#tabPaciente" data-toggle="tab">Por Paciente</a></li>
                                <li><a href="#tabPacienteU" data-toggle="tab">Por Paciente (Unico)</a></li>
                            </ul>

                            <div class="tab-content" style="margin-top:15px;">
                                <div class="tab-pane fade in active" id="tabCPT">
                                    <div class="table-responsive">
                                        <table id="tablaProcedimientos" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Nombre Procedimiento</th>
                                                    <th>Tipo Procedimiento</th>
                                                    <th>Titular</th>
                                                    <th>Familiar</th>
                                                    <th>Cívica</th>
                                                    <th>Pagante</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Filas dinámicas -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabMedico">
                                    <div class="table-responsive">
                                        <table id="tablaMedico" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>IPRESS</th>
                                                    <th>NOMBRE IPRESS</th>
                                                    <th>DNI</th>
                                                    <th>NOMBRE DEL MEDICO</th>
                                                    <th>GRADO</th>
                                                    <th>COLEGIO</th>
                                                    <th>TOTAL</th>
                                                    <th>ANESTESIA</th>
                                                    <th>CIRUGIA</th>
                                                    <th>EVALUACION</th>
                                                    <th>LABORATORIO</th>
                                                    <th>MEDICINA</th>
                                                    <th>NANDA</th>
                                                    <th>ODONTOLOGIA</th>
                                                    <th>RADIOLOGIA</th>
                                                    <th>MINUTO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Filas dinámicas -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabConsultaExterna">
                                    <div class="table-responsive">
                                        <table id="tablaCE" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>IPRESS</th>
                                                    <th>NOMBRE IPRESS</th>
                                                    <th>DNI</th>
                                                    <th>NOMBRE DEL MEDICO</th>
                                                    <th>GRADO</th>
                                                    <th>NOMBRE UPS</th>
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
                                                    <th>ID PRESTACION</th>
                                                    <th>FECHA ATENCION</th>
                                                    <th>DNI PACIENTE</th>
                                                    <th>NOMBRE PACIENTE</th>
                                                    <th>DNI RESPONSABLE</th>
                                                    <th>PROF. REALIZA PROCEDIMIENTO</th>
                                                    <th>COLEGIO RESPONSABLE</th>
                                                    <th>DNI INDICA</th>
                                                    <th>RESPONSABLE INDICA PROCEDIMIENTO</th>
                                                    <th>COLEGIO INDICA</th>
                                                    <th>COD. DIAGNOSTICO</th>
                                                    <th>NOM. DIAGNOSTICO</th>
                                                    <th>COD. PROCEDIMIENTO</th>
                                                    <th>NOM. PROCEDIMIENTO</th>
                                                    <th>TIPO PROCEDIMIENTO</th>
                                                    <th>TIPO ATENCION</th>
                                                    <th>UPS</th>
                                                    <th>SERVICIO</th>
                                                    <th>TOTAL PROCEDIMIENTOS</th>
                                                    <th>TOTAL MINUTOS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Filas dinámicas -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                                                <div class="tab-pane fade" id="tabPacienteU">
                                    <div class="table-responsive">
                                        <table id="tablaPacienteU" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID PRESTACION</th>
                                                    <th>FECHA ATENCION</th>
                                                    <th>DNI PACIENTE</th>
                                                    <th>NOMBRE PACIENTE</th>
                                                    <th>DNI RESPONSABLE</th>
                                                    <th>PROF. REALIZA PROCEDIMIENTO</th>
                                                    <th>COLEGIO RESPONSABLE</th>
                                                    <th>DNI INDICA</th>
                                                    <th>RESPONSABLE INDICA PROCEDIMIENTO</th>
                                                    <th>COLEGIO INDICA</th>
                                                    <th>COD. DIAGNOSTICO</th>
                                                    <th>NOM. DIAGNOSTICO</th>
                                                    <th>COD. PROCEDIMIENTO</th>
                                                    <th>NOM. PROCEDIMIENTO</th>
                                                    <th>TIPO PROCEDIMIENTO</th>
                                                    <th>TIPO ATENCION</th>
                                                    <th>UPS</th>
                                                    <th>SERVICIO</th>
                                                    <th>TOTAL PROCEDIMIENTOS</th>
                                                    <th>TOTAL MINUTOS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Filas dinámicas -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabProduccionIndividual">
                                    <div class="table-responsive">
                                        <table id="tablaProduccionIndividual" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>IPRESS</th>
                                                    <th>NOMBRE IPRESS</th>
                                                    <th>DNI</th>
                                                    <th>MEDICO RESPONSABLE</th>
                                                    <th>COLEGIO</th>
                                                    <th>CODIGO</th>
                                                    <th>NOMBRE PROCEDIMIENTO</th>
                                                    <th>TIPO PROCEDIMIENTO</th>
                                                    <th>TOTAL PROCEDIMIENTO</th>
                                                    <th>TOTAL MINUTOS</th>
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
