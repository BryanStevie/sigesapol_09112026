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
            $('#tablaPorTipo').DataTable({
                columns: [{
                        data: "codigo_ipres",
                        title: "IPRESS"
                    },
                    {
                        data: "nombre_ipress",
                        title: "Nombre IPRESS"
                    },
                    {
                        data: "id_atencion",
                        title: "ID Atención"
                    },
                    {
                        data: "dni",
                        title: "DNI"
                    },
                    {
                        data: "ape_nom",
                        title: "Apellidos y Nombres"
                    },
                    {
                        data: "edad",
                        title: "Edad"
                    },
                    {
                        data: "grado",
                        title: "Grado"
                    },
                    {
                        data: "sexo",
                        title: "Sexo"
                    },
                    {
                        data: "beneficiario",
                        title: "Beneficiario"
                    },
                    {
                        data: "nro_historia",
                        title: "Historia Clínica"
                    },
                    {
                        data: "unidad_pnptitular",
                        title: "Unidad Laboral"
                    },
                    {
                        data: "upss_servicio",
                        title: "Servicio"
                    },
                    {
                        data: "dni_responsable",
                        title: "DNI Responsable"
                    },
                    {
                        data: "medico_responsable",
                        title: "Nombre Responsable"
                    },
                    {
                        data: "colegio_responsable",
                        title: "Colegio Responsable"
                    },
                    {
                        data: "dni_indica",
                        title: "DNI Inidica"
                    },
                    {
                        data: "medico_indica",
                        title: "Nombre Indica"
                    },
                    {
                        data: "colegio_indica",
                        title: "Colegio indica"
                    },
                    {
                        data: "dni_anestesiologo",
                        title: "DNI Anestesiologo"
                    },
                    {
                        data: "nombre_anesteciologo",
                        title: "Nombre Anestesiologo"
                    },
                    {
                        data: "colegio_anestesiologo",
                        title: "Colegio Anestesiologo"
                    },
                    {
                        data: "cie10",
                        title: "CIE 10"
                    },
                    {
                        data: "diagnostico",
                        title: "Diagnostico"
                    },
                    {
                        data: "codigp_cpt",
                        title: "Codigo CPT"
                    },
                    {
                        data: "nombre_cpt",
                        title: "Nombre CPT"
                    },
                    {
                        data: "tipo_cirugia",
                        title: "Tipo Cirugia"
                    },
                    {
                        data: "tipo_complicacion",
                        title: "Tipo Complicacion"
                    },
                    {
                        data: "tipo_anestecia",
                        title: "Tipo Anestesia"
                    },
                    {
                        data: "tipo_programacion",
                        title: "Tipo Programacion"
                    },
                    {
                        data: "estado_programcion",
                        title: "Estado Programacion"
                    },
                    {
                        data: "horas_acto_qx",
                        title: "Hr Acto Quirurgico"
                    },
                    {
                        data: "horas_efectivas",
                        title: "Hr Ejecutadas"
                    },
                    {
                        data: "horas_programadas",
                        title: "Hr Programadas"
                    }
                ]
            });
            $("#btnModalIpress5").on("click", function() {
                $("#seleccionarIPRESSModal").modal("show");
            });
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
            } else {
                $("#edadDesde, #edadHasta").prop("disabled", true).hide();
                $("label[for='edadDesde'], label[for='edadHasta']").hide();
                $("#labelEdadDesde, #labelEdadHasta").hide();
                $("#edadDesde").val("");
                $("#edadHasta").val("");
            }
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
        $('#tblFinalIpressFilter').on('click', '.btn-removeToFilter-ipress', function() {
            var row = tblFinalIpressFilter.row($(this).closest('tr'));
            row.remove().draw();
        });
        $('#btnRemoveFilters1').on('click', function() {
            tblFinalIpressFilter.clear().draw();
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

        $("#btnBuscarReporte").on("click", function(e) {
            let tipoReporte = "json";
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const periodo = $("#periodo").val();
            const anio = $("#año").val();
            const fechaDesde = $("#fechaDesde").val();
            const fechaHasta = $("#fechaHasta").val();
            const sexo = $("#sexo").val();
            const tipoAnestesia = $("#tipoAnestesia").val();
            const tipoCirugia = $("#tipoCirugia").val();
            const complicacion = $("#complicacion").val();
            const tipoProgramacion = $("#tipoProgramacion").val();
            const estadoProgramacion = $("#estadoProgramacion").val();
            const responsable = $("#responsable").val();
            const medico_ordena = $("#medico_ordena").val();
            const diagnostico_nombre = $("#diagnostico_nombre").val();
            const tipo_beneficiario = $("#tipo_beneficiario").val();
            const grado = $("#grado").val();
            const paciente = $("#paciente").val();
            const edadDesde = $("#edadDesde").val();
            const edadHasta = $("#edadHasta").val();

            if (periodo === "" && anio === "" && (fechaDesde === "" || fechaHasta === "")) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar el año, periodo o colocar un rango de fechas.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            /* if (conFecha) {
                if (fechaDesde === "" || fechaHasta === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe completar el rango de fechas.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
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
                url: "/listarReporteIntervenciones",
                type: "POST",
                data: {
                    ipress,
                    anio,
                    fechaIni: periodo !== "" ? primerDiaMes(periodo) : fechaDesde,
                    fechaFin: periodo !== "" ? ultimoDiaMes(periodo) : fechaHasta,
                    sexo,
                    tipoAnestesia,
                    tipoCirugia,
                    complicacion,
                    tipoProgramacion,
                    estadoProgramacion,
                    responsable,
                    medico_ordena,
                    diagnostico_nombre,
                    tipo_beneficiario,
                    grado,
                    paciente,
                    edadDesde,
                    edadHasta,
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    Swal.close();
                    let tabla = $("#tablaPorTipo").DataTable();
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
        });

        $("#btnExportarExcel, #btnExportarCSV").on("click", function(e) {
            let idBtn = e.currentTarget.id;
            let tipoReporte = "";
            if (idBtn == "btnExportarExcel") {
                tipoReporte = "excel";
            } else if (idBtn == "btnExportarCSV") {
                tipoReporte = "csv";
            }
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const periodo = $("#periodo").val();
            const anio = $("#año").val();
            const fechaDesde = $("#fechaDesde").val();
            const fechaHasta = $("#fechaHasta").val();
            const sexo = $("#sexo").val();
            const tipoAnestesia = $("#tipoAnestesia").val();
            const tipoCirugia = $("#tipoCirugia").val();
            const complicacion = $("#complicacion").val();
            const tipoProgramacion = $("#tipoProgramacion").val();
            const estadoProgramacion = $("#estadoProgramacion").val();
            const responsable = $("#responsable").val();
            const medico_ordena = $("#medico_ordena").val();
            const diagnostico_nombre = $("#diagnostico_nombre").val();
            const tipo_beneficiario = $("#tipo_beneficiario").val();
            const grado = $("#grado").val();
            const paciente = $("#paciente").val();
            const edadDesde = $("#edadDesde").val();
            const edadHasta = $("#edadHasta").val();

            if (periodo === "" && anio === "" && (fechaDesde === "" || fechaHasta === "")) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar el año, periodo o colocar un rango de fechas.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            /* if (conFecha) {
                if (fechaDesde === "" || fechaHasta === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe completar el rango de fechas.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
            } */

            const urlGet =
                `/exportarReporteIntervenciones?` +

                ipress.map(i => `ipress[]=${encodeURIComponent(i)}`).join("&") +

                `&anio=${encodeURIComponent(anio)}` +
                `&fechaIni=${encodeURIComponent(periodo !== "" ? primerDiaMes(periodo) : fechaDesde)}` +
                `&fechaFin=${encodeURIComponent(periodo !== "" ? ultimoDiaMes(periodo) : fechaHasta)}` +
                `&sexo=${encodeURIComponent(sexo)}` +
                `&tipoAnestesia=${encodeURIComponent(tipoAnestesia)}` +
                `&tipoCirugia=${encodeURIComponent(tipoCirugia)}` +
                `&complicacion=${encodeURIComponent(complicacion)}` +
                `&tipoProgramacion=${encodeURIComponent(tipoProgramacion)}` +
                `&estadoProgramacion=${encodeURIComponent(estadoProgramacion)}` +
                `&responsable=${encodeURIComponent(responsable)}` +
                `&medico_ordena=${encodeURIComponent(medico_ordena)}` +
                `&diagnostico_nombre=${encodeURIComponent(diagnostico_nombre)}` +
                `&tipo_beneficiario=${encodeURIComponent(tipo_beneficiario)}` +
                `&grado=${encodeURIComponent(grado)}` +
                `&paciente=${encodeURIComponent(paciente)}` +
                `&edadDesde=${encodeURIComponent(edadDesde)}` +
                `&edadHasta=${encodeURIComponent(edadHasta)}` +
                `&tipoReporte=${encodeURIComponent(tipoReporte)}`;
            window.location.href = urlGet;

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
            $("#btnModalIpress5").prop("disabled", true);
        }
    </script>
@endsection
@section('content')
    <div class="container-fluid" style="margin: 0 100px;">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">REPORTE INTERVENCIONES</h3>
            </div>
            <div class="panel-body panel_custom-body">
                <div class="panel panel-success">
                    <div class="panel-body">
                        <!-- FILA 1: Buscar por periodo y rangos -->
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="año">Año</label>
                                    <input type="text" class="form-control" id="año" placeholder="aaaa"
                                        oninput="validarAnio(this)">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="periodo">Periodo</label>
                                    <input type="month" class="form-control" id="periodo">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sexo">Sexo</label>
                                    <select class="form-control" id="sexo">
                                        <option value="">- Seleccionar -</option>
                                        <option value="M">MASCULINO</option>
                                        <option value="F">FEMENINO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tipoAnestesia">Tipo Anestesia</label>
                                    <select type="text" class="form-control" id="tipoAnestesia">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($tiposAnestesia as $tipoAnestesia)
                                            <option value="{{ $tipoAnestesia->id }}">{{ $tipoAnestesia->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tipoCirugia">Tipo Cirugia</label>
                                    <select type="text" class="form-control" id="tipoCirugia">
                                        <option value="">- Seleccionar -</option>
                                        <option value="1">CIRUGIA MENOR</option>
                                        <option value="2">CIRUGIA MAYOR</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="complicacion">Complicacion de la Intervencion</label>
                                    <select class="form-control" id="complicacion">
                                        <option value="">- Seleccionar -</option>
                                        <option value="0">SIN COMPLICACIONES</option>
                                        <option value="1">COMPLICADO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tipoProgramacion">Tipo Programacion</label>
                                    <select type="text" class="form-control" id="tipoProgramacion">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($tiposProgramacion as $tipoProgramacion)
                                            <option value="{{ $tipoProgramacion->id }}">{{ $tipoProgramacion->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="estadoProgramacion">Estado de Programacion</label>
                                    <select type="text" class="form-control" id="estadoProgramacion">
                                        <option value="">- Seleccionar -</option>
                                        <option value="1">Pendiente</option>
                                        <option value="4">Atendido</option>
                                        <option value="6">Alta</option>
                                        <option value="8">Suspendido</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="responsable">Responsable Atencion</label>
                                    <input type="text" name="" id="responsable" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="medico_ordena">Medico Ordena</label>
                                    <input type="text" name="" id="medico_ordena" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="diagnostico_nombre">Diagnostico</label>
                                    <input type="text" name="" id="diagnostico_nombre" class="form-control"
                                        placeholder="COD. Y/O DESCRIPCION">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fechaDesde" style="">Fec. Desde</label>
                                    <input type="date" id="fechaDesde" class="form-control" style="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fechaHasta" style="">Fec. Hasta</label>
                                    <input type="date" id="fechaHasta" class="form-control" style="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tipo_beneficiario">Tipo Beneficiario</label>
                                    <select class="form-control" id="tipo_beneficiario">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($tipoBeneficiarios as $tipoBeneficiario)
                                            <option value="{{ $tipoBeneficiario->id }}">
                                                {{ $tipoBeneficiario->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="grado">Grado</label>
                                    <select type="text" class="form-control" id="grado">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($grados as $grado)
                                            <option value="{{ $grado->id }}">
                                                {{ $grado->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paciente">Nombre Paciente</label>
                                    <input type="text" name="" id="paciente" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-2">
                                <div class="form-group" style="padding-top: 30px;">
                                    <label class="checkbox-inline" style="" for="buscarPorEdad">
                                        <input type="checkbox" id="buscarPorEdad"> Buscar por Edad
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="edadDesde" style="display: none;">Desde</label>
                                    <input type="number" id="edadDesde" class="form-control" style="display: none;"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="edadHasta" style="display: none;">Hasta</label>
                                    <input type="number" id="edadHasta" class="form-control" style="display: none;"
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
                            <button class="btn btn-info" type="button" id="btnBuscarReporte">
                                <i class="fa fa-search" aria-hidden="true"></i> Buscar
                            </button>

                            <button id="btnExportarExcel" class="btn btn-primary" type="button">
                                <i class="fa fa-file-excel-o"></i> Exportar
                            </button>

                            <button id="btnExportarCSV" class="btn btn-warning" type="button">
                                <i class="fa fa-file-text-o"></i> Exportar CSV
                            </button>
                        </div>
                    </div>
                    <div class="panel panel-default" style="margin: 0; margin-top: 10px;">
                        <div class="panel-body">
                            <div class="tab-content" style="margin-top:15px;">
                                <div class="tab-pane fade in active" id="">
                                    <div class="table-responsive">
                                        <table id="tablaPorTipo" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>IPRESS</th>
                                                    <th>NOMBRE IPRESS</th>
                                                    <th>ID ATEN</th>
                                                    <th>DNI</th>
                                                    <th>APE Y NOM</th>
                                                    <th>EDAD</th>
                                                    <th>SEXOO</th>
                                                    <th>GRADO</th>
                                                    <th>BENEFICIARIO</th>
                                                    <th>N° HC</th>
                                                    <th>UNIDAD LABORAL</th>
                                                    <th>SERVICIO</th>
                                                    <th>DNI RESPONSABLE</th>
                                                    <th>NOM RESPONSABLE</th>
                                                    <th>COLEGIO RESPONSABLE</th>
                                                    <th>DNI INDICA</th>
                                                    <th>NOM RESIDENTE INDICA</th>
                                                    <th>COLEGIO INDICA</th>
                                                    <th>DNI ANESTESIÓLOGO</th>
                                                    <th>NOM ANESTESIÓLOGO</th>
                                                    <th>COLEGIO ANESTESIÓLOGO</th>
                                                    <th>CIE10</th>
                                                    <th>DIAGNOSTICO</th>
                                                    <th>CODIGO CPT</th>
                                                    <th>NOMBRE CPT</th>
                                                    <th>TIPO CIRUGIA</th>
                                                    <th>TIPO COMPLICACION</th>
                                                    <th>TIPO ANESTESIA</th>
                                                    <th>TIPO PROGRAMACION</th>
                                                    <th>ESTADO PROGRAMACION</th>
                                                    <th>HR ACTO QUIRURGICO</th>
                                                    <th>HR EJECUTADA</th>
                                                    <th>HR PROGRAMADAS</th>
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
