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
            $('#tablaPorTipo').DataTable({
                columns: [{
                        data: 'ipress',
                        title: 'IPRESS'
                    },
                    {
                        data: 'nombre_ipress',
                        title: 'Nombre IPRESS'
                    },
                    {
                        data: 'id_aten',
                        title: 'ID Atención',
                        className: 'text-center'
                    },
                    {
                        data: 'dni',
                        title: 'DNI'
                    },
                    {
                        data: 'ape_nom',
                        title: 'Apellidos y Nombres'
                    },
                    {
                        data: 'edad',
                        title: 'Edad',
                        className: 'text-center'
                    },
                    {
                        data: 'beneficiario',
                        title: 'Beneficiario'
                    },
                    {
                        data: 'upss',
                        title: 'UPSS'
                    },
                    {
                        data: 'nom_upss',
                        title: 'Nombre UPSS'
                    },
                    {
                        data: 'dni_responsable',
                        title: 'DNI Responsable'
                    },
                    {
                        data: 'nom_responsable',
                        title: 'Nombre Responsable'
                    },
                    {
                        data: 'colegio_responsable',
                        title: 'Colegio Responsable'
                    },
                    {
                        data: 'ipress_destino',
                        title: 'IPRESS Destino'
                    },
                    {
                        data: 'nombre_ipress_destino',
                        title: 'Nombre IPRESS Destino'
                    },
                    {
                        data: 'upss_destino',
                        title: 'UPSS Destino'
                    },
                    {
                        data: 'nom_upss_destino',
                        title: 'Nombre UPSS Destino'
                    },
                    {
                        data: 'servicio_destino',
                        title: 'Servicio Destino'
                    },
                    {
                        data: 'cie10',
                        title: 'CIE10'
                    },
                    {
                        data: 'diagnostico',
                        title: 'Diagnóstico'
                    },
                    {
                        data: 'codigo_cpt',
                        title: 'Código CPT'
                    },
                    {
                        data: 'nom_cpt',
                        title: 'Nombre CPT'
                    },
                    {
                        data: 'fecha_extencion',
                        title: 'Fecha Extención'
                    },
                    {
                        data: 'motivo_referencia',
                        title: 'Motivo de Referencia'
                    },
                    {
                        data: 'fecha_tramite',
                        title: 'Fecha Trámite'
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
            const buscarPorEdad = $("#buscarPorEdad").is(":checked");
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const anio = $("#año").val();
            const periodo = $("#periodo").val();
            const sexo = $("#sexo").val();
            const responsable = $("#responsable").val();
            const diagnostico = $("#diagnostico_input").val();
            const tipo_beneficiario = $("#tipo_beneficiario").val();
            const paciente = $("#paciente").val();
            const upss = $("#upss").val();
            const fechaDesde = $("#fechaDesde").val();
            const fechaHasta = $("#fechaHasta").val();
            const ipressDestino = $("#ipressDestino").val();
            const procedimiento = $("#procedimiento").val();
            const edadDesde = $("#edadDesde").val();
            const edadHasta = $("#edadHasta").val();
            if (periodo === "" && anio === "" && fechaDesde == "" & fechaHasta == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar un periodo, ingresar el año o un rango de fechas.',
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
                    url: "/listarReporteReferencias",
                    type: "POST",
                    data: {
                        ipress,
                        anio,
                        sexo,
                        responsable,
                        diagnostico,
                        tipo_beneficiario,
                        paciente,
                        upss,
                        fechaDesde: fechaDesde == "" ? (periodo == "" ? null : primerDiaMes(periodo)) :
                            fechaDesde,
                        fechaHasta: fechaHasta == "" ? (periodo == "" ? null : ultimoDiaMes(periodo)) :
                            fechaHasta,
                        ipressDestino,
                        procedimiento,
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
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar al menos una ipress de origen.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
        $("#btnExportarExcel").on("click", function(e) {
            let tipoReporte = "excel";
            const buscarPorEdad = $("#buscarPorEdad").is(":checked");
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const anio = $("#año").val();
            const periodo = $("#periodo").val();
            const sexo = $("#sexo").val();
            const responsable = $("#responsable").val();
            const diagnostico = $("#diagnostico_input").val();
            const tipo_beneficiario = $("#tipo_beneficiario").val();
            const paciente = $("#paciente").val();
            const upss = $("#upss").val();
            const fechaDesde = $("#fechaDesde").val();
            const fechaHasta = $("#fechaHasta").val();
            const ipressDestino = $("#ipressDestino").val();
            const procedimiento = $("#procedimiento").val();
            const edadDesde = $("#edadDesde").val();
            const edadHasta = $("#edadHasta").val();
            if (periodo === "" && anio === "" && fechaDesde == "" & fechaHasta == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar un periodo, ingresar el año o un rango de fechas.',
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
            if (ipress.length > 0) {
                const params = new URLSearchParams();
                ipress.forEach(i => params.append("ipress[]", i));
                params.append("anio", anio);
                params.append("sexo", sexo);
                params.append("responsable", responsable);
                params.append("diagnostico", diagnostico);
                params.append("tipo_beneficiario", tipo_beneficiario);
                params.append("paciente", paciente);
                params.append("upss", upss);
                params.append(
                    "fechaDesde",
                    fechaDesde === "" ?
                    (periodo === "" ? null : primerDiaMes(periodo)) :
                    fechaDesde
                );

                params.append(
                    "fechaHasta",
                    fechaHasta === "" ?
                    (periodo === "" ? null : ultimoDiaMes(periodo)) :
                    fechaHasta
                );
                params.append("ipressDestino", ipressDestino);
                params.append("procedimiento", procedimiento);
                params.append("edadDesde", edadDesde);
                params.append("edadHasta", edadHasta);
                params.append("tipoReporte", tipoReporte);
                const url = "/exportarReporteReferencias?" + params.toString();
                window.location.href = url;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe seleccionar una ipress.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });

        function validarAnio(input) {
            input.value = input.value
                .replace(/[^0-9]/g, '') // solo números
                .replace(/^0+/, ''); // elimina ceros iniciales
        }

        $("#buscarPorFecha").on("click", function(e) {

            const id = this.id;
            const isChecked = $(this).is(":checked");

            if (isChecked) {
                $("#containerPeriodoTotal").hide();
                $("#containerPeriodoInicio").show();
                $("#containerPeriodoFin").show();
                $("#periodo").prop("disabled", true);
                $("#fechaDesde").prop("disabled", false);
                $("#fechaHasta").prop("disabled", false);
            } else {
                $("#containerPeriodoTotal").show();
                $("#containerPeriodoInicio").hide();
                $("#containerPeriodoFin").hide();
                $("#periodo").prop("disabled", false);
                $("#fechaDesde").prop("disabled", true);
                $("#fechaHasta").prop("disabled", true);
                $("#periodo").val("");
                $("#fechaDesde").val("");
                $("#fechaHasta").val("");
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
                <h3 class="panel-title">REPORTE REFERENCIA</h3>
            </div>
            <div class="panel-body panel_custom-body">
                <div class="panel panel-success">
                    <div class="panel-body">
                        <!-- FILA 1: Buscar por periodo y rangos -->
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="año">Año</label>
                                    <input type="text" class="form-control" id="año" placeholder="Año"
                                        oninput="validarAnio(this)">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="responsable">Responsable que Ordena</label>
                                    <input type="text" name="" id="responsable" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="procedimiento">Procedimiento</label>
                                    <input type="text" name="" id="procedimiento" class="form-control"
                                        placeholder="COD Y/O DESCRIPCION">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="diagnostico_input">Diagnostico</label>
                                    <input type="text" name="" id="diagnostico_input" class="form-control"
                                        placeholder="COD. Y/O DESCRIPCION">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipo_beneficiario">Beneficiario</label>
                                    <select type="text" class="form-control" id="tipo_beneficiario">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($tipoBeneficiarios as $tipoBeneficiario)
                                            <option value="{{ $tipoBeneficiario->id }}">{{ $tipoBeneficiario->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="paciente">Nombre Paciente</label>
                                    <input type="text" class="form-control" id="paciente">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="upss">UPS DESTINO</label>
                                    <input type="text" class="form-control" id="upss"
                                        placeholder="COD. Y/O DESCRIPCION">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" id="containerPeriodoTotal">
                                <div class="form-group">
                                    <label for="periodo">Periodo</label>
                                    <input type="month" class="form-control" id="periodo" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="checkbox" style="margin-top: 28px; margin-left: 20px;">
                                    <label>
                                        <input type="checkbox" id="buscarPorFecha"> Buscar por Fecha
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2" id="containerPeriodoInicio" style="display: none;">
                                <div class="form-group">
                                    <label for="fechaDesde" style="">Fec. Desde</label>
                                    <input type="date" id="fechaDesde" class="form-control" style=""
                                        disabled="true">
                                </div>
                            </div>
                            <div class="col-md-2" id="containerPeriodoFin" style="display: none;">
                                <div class="form-group">
                                    <label for="fechaHasta" style="">Fec. Hasta</label>
                                    <input type="date" id="fechaHasta" class="form-control" style=""
                                        disabled="true">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ipressDestino">IPRESS Destino</label>
                                    <select class="form-control" id="ipressDestino">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($establecimientos as $establecimiento)
                                            <option value="{{ $establecimiento->id }}">{{ $establecimiento->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-2">
                                <div class="form-group" style="margin-bottom: 0; margin-top: 25px; margin-left: 20px;">
                                    <label for="buscarPorEdad" class="checkbox-inline" style="">
                                        <input type="checkbox" id="buscarPorEdad"> Buscar por Edad
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="edadDesde" id="labelEdadDesde" style="display: none;">Desde
                                    </label>
                                    <input type="number" id="edadDesde" class="form-control input-sm"
                                        style="display: none;" disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="edadHasta" id="labelEdadHasta" style="display: none;">Hasta
                                    </label>
                                    <input type="number" id="edadHasta" class="form-control input-sm"
                                        style="display: none;" disabled>
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
                                                    <th>BENEFICIARIO</th>
                                                    <th>UPSS</th>
                                                    <th>NOM UPSS</th>
                                                    <th>DNI RESPONSABLE</th>
                                                    <th>NOM RESPONSABLE</th>
                                                    <th>COLEGIO RESPONSABLE</th>
                                                    <th>IPRESS DESTINO</th>
                                                    <th>NOM DESTINO</th>
                                                    <th>UPSS DESTINO</th>
                                                    <th>NOM UPSS DESTINO</th>
                                                    <th>SERVICIO DESTINO</th>
                                                    <th>CIE 10</th>
                                                    <th>DIAGNOSTICO</th>
                                                    <th>CODIGO CPT</th>
                                                    <th>NOMBRE CPT</th>
                                                    <th>FEC EXTENCION</th>
                                                    <th>MOTIVO REFERENCIA</th>
                                                    <th>FEC TRAMITE</th>
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
