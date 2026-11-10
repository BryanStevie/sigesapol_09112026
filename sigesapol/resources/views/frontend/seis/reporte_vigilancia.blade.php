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
            $('#tablaEventos').DataTable({
                columns: [{
                        data: "codigo",
                        title: "Código"
                    },
                    {
                        data: "id_atencion",
                        title: "ID Atención"
                    },
                    {
                        data: "descripcion_evento",
                        title: "Descripción del Evento"
                    },
                    {
                        data: "nombre_ipress",
                        title: "IPRESS"
                    },
                    {
                        data: "cie10",
                        title: "CIE10"
                    },
                    {
                        data: "nombre_cie10",
                        title: "Nombre CIE10"
                    },
                    {
                        data: "fecha_evento",
                        title: "Fecha Evento"
                    },
                    {
                        data: "dni",
                        title: "DNI"
                    },
                    {
                        data: "paciente",
                        title: "Paciente"
                    },
                    {
                        data: "descripcion_upss",
                        title: "UPSS"
                    },
                    {
                        data: "nombre_consultorio",
                        title: "Consultorio"
                    },
                    {
                        data: "beneficiarios",
                        title: "Beneficiario"
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
                        data: "tipo_atencion",
                        title: "Tipo Atención"
                    }
                ]
            });
            $('#tablaAlertas').DataTable({
                columns: [{
                        data: "codigo",
                        title: "Código"
                    },
                    {
                        data: "id_atencion",
                        title: "ID Atención"
                    },
                    {
                        data: "nombre_ipress",
                        title: "IPRESS"
                    },
                    {
                        data: "cie10",
                        title: "CIE10"
                    },
                    {
                        data: "nombre_cie10",
                        title: "Diagnóstico"
                    },
                    {
                        data: "fecha_evento",
                        title: "Fecha Evento"
                    },
                    {
                        data: "dni",
                        title: "DNI"
                    },
                    {
                        data: "paciente",
                        title: "Paciente"
                    },
                    {
                        data: "descripcion_upss",
                        title: "UPSS"
                    },
                    {
                        data: "nombre_consultorio",
                        title: "Consultorio"
                    },
                    {
                        data: "beneficiarios",
                        title: "Beneficiario"
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
                        data: "tipo_atencion",
                        title: "Tipo Atención"
                    }
                ]
            });
            $("#btnModalIpress5").on("click", function() {
                $("#seleccionarIPRESSModal").modal("show");
            });
        });

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

        $("#btnBuscarReporte").on("click", function(e) {
            var activeTab = $('.nav-tabs li.active a').attr('href');
            let tipoReporte = "json";
            const buscarPorEdad = $("#buscarPorEdad").is(":checked");
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const anio = $("#año").val();
            const periodo = $("#periodo").val();
            const nombrePaciente = $("#nombrePaciente").val();
            const sexo = $("#sexo").val();
            const codigoEvt = $("#codigoEvt").val();
            const tipo_beneficiario = $("#tipo_beneficiario").val();
            const ups = $("#ups").val();
            const servicio_id = $("#servicio_id").val();
            const edadDesde = $("#edadDesde").val();
            const edadHasta = $("#edadHasta").val();

            if (periodo === "" && anio === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe ingresar el año o seleccionar el periodo.',
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

            switch (activeTab) {
                case "#tabEventos":
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
                        url: "/listarReporteVigilancia",
                        type: "POST",
                        data: {
                            ipress,
                            anio: periodo !== "" ? "" : anio,
                            fechaIni: periodo !== "" ? primerDiaMes(periodo) : "",
                            fechaFin: periodo !== "" ? ultimoDiaMes(periodo) : "",
                            nombrePaciente,
                            sexo,
                            codigoEvt,
                            tipo_beneficiario,
                            ups,
                            servicio_id,
                            edadDesde,
                            edadHasta,
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tablaEventos").DataTable();
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
                case "#tabAlertas":
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
                        url: "/listarReporteVigilanciaAlertas",
                        type: "POST",
                        data: {
                            ipress,
                            anio: periodo !== "" ? "" : anio,
                            fechaIni: periodo !== "" ? primerDiaMes(periodo) : "",
                            fechaFin: periodo !== "" ? ultimoDiaMes(periodo) : "",
                            nombrePaciente,
                            sexo,
                            tipo_beneficiario,
                            ups,
                            servicio_id,
                            edadDesde,
                            edadHasta,
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tablaAlertas").DataTable();
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
            let tipoReporte = "excel";
            var activeTab = $('.nav-tabs li.active a').attr('href');
            const buscarPorEdad = $("#buscarPorEdad").is(":checked");
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const anio = $("#año").val();
            const periodo = $("#periodo").val();
            const nombrePaciente = $("#nombrePaciente").val();
            const sexo = $("#sexo").val();
            const codigoEvt = $("#codigoEvt").val();
            const tipo_beneficiario = $("#tipo_beneficiario").val();
            const ups = $("#ups").val();
            const servicio_id = $("#servicio_id").val();
            const edadDesde = $("#edadDesde").val();
            const edadHasta = $("#edadHasta").val();

            if (periodo === "" && anio === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe ingresar el año o seleccionar el periodo.',
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

            switch (activeTab) {
                case "#tabEventos":
                    const url =
                        "/exportarReporteVigilancia?" +
                        "ipress[]=" + ipress.join("&ipress[]=") +
                        "&anio=" + encodeURIComponent(periodo !== "" ? "" : anio) +
                        "&fechaIni=" + encodeURIComponent(periodo !== "" ? primerDiaMes(periodo) : "") +
                        "&fechaFin=" + encodeURIComponent(periodo !== "" ? ultimoDiaMes(periodo) : "") +
                        "&nombrePaciente=" + encodeURIComponent(nombrePaciente) +
                        "&sexo=" + encodeURIComponent(sexo) +
                        "&codigoEvt=" + encodeURIComponent(codigoEvt) +
                        "&tipo_beneficiario=" + encodeURIComponent(tipo_beneficiario) +
                        "&ups=" + encodeURIComponent(ups) +
                        "&servicio_id=" + encodeURIComponent(servicio_id) +
                        "&edadDesde=" + encodeURIComponent(edadDesde) +
                        "&edadHasta=" + encodeURIComponent(edadHasta) +
                        "&tipoReporte=" + encodeURIComponent(tipoReporte);
                    window.location.href = url;
                    break;
                case "#tabAlertas":
                    const urlAlertas =
                        "/exportarReporteVigilanciaAlertas?" +
                        "ipress[]=" + ipress.join("&ipress[]=") +
                        "&anio=" + encodeURIComponent(periodo !== "" ? "" : anio) +
                        "&fechaIni=" + encodeURIComponent(periodo !== "" ? primerDiaMes(periodo) : "") +
                        "&fechaFin=" + encodeURIComponent(periodo !== "" ? ultimoDiaMes(periodo) : "") +
                        "&nombrePaciente=" + encodeURIComponent(nombrePaciente) +
                        "&sexo=" + encodeURIComponent(sexo) +
                        "&tipo_beneficiario=" + encodeURIComponent(tipo_beneficiario) +
                        "&ups=" + encodeURIComponent(ups) +
                        "&servicio_id=" + encodeURIComponent(servicio_id) +
                        "&edadDesde=" + encodeURIComponent(edadDesde) +
                        "&edadHasta=" + encodeURIComponent(edadHasta) +
                        "&tipoReporte=" + encodeURIComponent(tipoReporte);
                    window.location.href = urlAlertas;
                    break;
            }

        });

        function validarAnio(input) {
            input.value = input.value
                .replace(/[^0-9]/g, '') // solo números
                .replace(/^0+/, ''); // elimina ceros iniciales
        }

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

        function validarAnio(input) {
            input.value = input.value
                .replace(/[^0-9]/g, '') // solo números
                .replace(/^0+/, ''); // elimina ceros iniciales
        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var anterior = $(e.relatedTarget).attr("href");
            var actual = $(e.target).attr("href");
            if (actual == "#tabAlertas") {
                $("#containerCodigoEvento").hide();
                $("#codigoEvt").val("");
                $("#codigoEvt").prop("disabled", true);
            } else {
                $("#containerCodigoEvento").show();
                $("#codigoEvt").prop("disabled", false);
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
                <h3 class="panel-title">REPORTE VIGILANCIA</h3>
            </div>
            <div class="panel-body panel_custom-body">
                <div class="panel panel-success">
                    <div class="panel-body">
                        <!-- FILA 1: Buscar por periodo y rangos -->
                        <div class="row">
                            <div class="col-md-1">
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
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="nombrePaciente">Nombre Paciente</label>
                                    <input type="text" class="form-control" id="nombrePaciente" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sexo">Sexo</label>
                                    <select type="text" class="form-control" id="sexo">
                                        <option value="">- Seleccionar -</option>
                                        <option value="M">MASCULINO</option>
                                        <option value="F">FEMENINO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2" id="containerCodigoEvento">
                                <div class="form-group">
                                    <label for="codigoEvt">Codigo Evento</label>
                                    <input type="number" class="form-control" id="codigoEvt" placeholder="" step="1"
                                        min="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tipo_beneficiario">Tipo Beneficiario</label>
                                    <select type="text" class="form-control" id="tipo_beneficiario">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($tipoBeneficiarios as $tipoBeneficiario)
                                            <option value="{{ $tipoBeneficiario->id }}">
                                                {{ $tipoBeneficiario->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" style="padding-top: 30px;">
                                    <label class="checkbox-inline" style="">
                                        <input type="checkbox" id="buscarPorEdad"> Buscar por Edad
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="edadDesde" style="display: none;">Desde</label>
                                    <input type="number" id="edadDesde" class="form-control" style="display: none;"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="edadHasta" style="display: none;">Hasta</label>
                                    <input type="number" id="edadHasta" class="form-control" style="display: none;"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ups" style="">UPS</label>
                                    <input type="text" id="ups" class="form-control" style=""
                                        placeholder="COD Y/O DESCRIPCION">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="servicio_id">Servicio</label>
                                    <select type="text" class="form-control" id="servicio_id">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($serviciosParaUps as $servicio)
                                            <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                                        @endforeach
                                    </select>
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
                                <li class="active"><a href="#tabEventos" data-toggle="tab">Eventos</a></li>
                                <li><a href="#tabAlertas" data-toggle="tab">Alertas</a></li>
                            </ul>

                            <div class="tab-content" style="margin-top:15px;">
                                <div class="tab-pane fade in active" id="tabEventos">
                                    <div class="table-responsive">
                                        <table id="tablaEventos" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>ID ATEN</th>
                                                    <th>EVENTO</th>
                                                    <th>IPRESS</th>
                                                    <th>CIE10</th>
                                                    <th>NOM CIE10</th>
                                                    <th>FEC EVENTO</th>
                                                    <th>DNI</th>
                                                    <th>PACIENTE</th>
                                                    <th>UPS</th>
                                                    <th>SERVICIO</th>
                                                    <th>BENEFICIARIO</th>
                                                    <th>EDAD</th>
                                                    <th>GRADO</th>
                                                    <th>TIPO ATENCION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Filas dinámicas -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabAlertas">
                                    <div class="table-responsive">
                                        <table id="tablaAlertas" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>ID ATEN</th>
                                                    <th>IPRESS</th>
                                                    <th>CIE10</th>
                                                    <th>NOM CIE10</th>
                                                    <th>FEC EVENTO</th>
                                                    <th>DNI</th>
                                                    <th>PACIENTE</th>
                                                    <th>UPS</th>
                                                    <th>SERVICIO</th>
                                                    <th>BENEFICIARIO</th>
                                                    <th>EDAD</th>
                                                    <th>GRADO</th>
                                                    <th>TIPO ATENCION</th>
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
{{--  --}}
