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
                        data: 'codigo_ipress',
                        title: 'IPRESS'
                    },
                    {
                        data: 'nombre_ipress',
                        title: 'NOMBRE IPRESS'
                    },
                    {
                        data: 'codigo_estrategia',
                        title: 'CODIGO'
                    },
                    {
                        data: 'estrategia',
                        title: 'NOMBRE DE LA ESTRATEGIA'
                    },
                    {
                        data: 'estado',
                        title: 'ESTADO'
                    },
                    {
                        data: 'titular',
                        title: 'TITULAR',
                        className: 'text-center'
                    },
                    {
                        data: 'familiar',
                        title: 'FAMILIAR',
                        className: 'text-center'
                    },
                    {
                        data: 'civil',
                        title: 'CIVICA',
                        className: 'text-center'
                    },
                    {
                        data: 'pagante',
                        title: 'PAGANTE',
                        className: 'text-center'
                    }
                ]
            });
            $('#tablaPorTipoMasivo').DataTable({
                columns: [{
                        data: 'codigo_ipress',
                        title: 'IPRESS'
                    },
                    {
                        data: 'nombre_ipress',
                        title: 'NOMBRE IPRESS'
                    },
                    {
                        data: 'codigo_estrategia',
                        title: 'CODIGO'
                    },
                    {
                        data: 'estrategia',
                        title: 'NOMBRE DE LA ESTRATEGIA'
                    },
                    {
                        data: 'cantidad',
                        title: 'CANTIDAD'
                    },
                    {
                        data: 'participantes',
                        title: 'PARTICIPANTES',
                        className: 'text-center'
                    },
                ]
            });
            $("#btnModalIpress5").on("click", function() {
                $("#seleccionarIPRESSModal").modal("show");
            });

            $('input[name="tipoEstrategia"]').on('change', function() {
                const valor = $(this).val(); // "individual" o "masivo"

                if (valor === 'individual') {
                    $(".containerIndividual").show();
                    $(".containerMasivo").hide();
                    $("#nombre").val("");
                    $("#containerTblIndividual").show();
                    $("#containerTblMasivo").hide();
                } else if (valor === 'masivo') {
                    $(".containerIndividual").hide();
                    $(".containerMasivo").show();
                    $("#descripcion").val("");
                    $("#sexo").val("");
                    $("#estadoEst").val("");
                    $("#containerTblIndividual").hide();
                    $("#containerTblMasivo").show();
                }
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
            $('#tblFinalIpressFilter').on('click', '.btn-removeToFilter-ipress', function() {
                var row = tblFinalIpressFilter.row($(this).closest('tr'));
                row.remove().draw();
            });
            $('#btnRemoveFilters1').on('click', function() {
                tblFinalIpressFilter.clear().draw();
            });

            $("#btnBuscarReporte").on("click", function(e) {
                let tipoReporte = "json";
                var tipoEstrategia = $("input[name='tipoEstrategia']:checked").val();
                const buscarPorEdad = $("#buscarPorEdad").is(":checked");
                let table = $("#tblFinalIpressFilter").DataTable();
                let ipress = table.rows().data().toArray().map(r => r.id);
                const periodo = $("#periodoInicio").val();
                const codigoCpt = $("#codigoCpt").val();
                const estadoEst = $("#estadoEst").val();
                const descripcion = $("#descripcion").val().trim();
                const nombre = $("#nombre").val();
                const edadDesde = $("#edadDesde").val();
                const edadHasta = $("#edadHasta").val();
                const sexo = $("#sexo").val();
                let periodoConvertido = "";
                if (periodo) {
                    const partes = periodo.split("-");
                    periodoConvertido = `${partes[1]}/${partes[0]}`;
                }
                if (periodo === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar un periodo.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
                /* if (estadoEst === "" && tipoEstrategia == "individual") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar el estado.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                } */
                /* if (sexo == "" && tipoEstrategia == "individual") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar el sexo.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                } */
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
                switch (tipoEstrategia) {
                    case "individual":
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
                            url: "/listarReporteEstrategiasTipoIndividual",
                            type: "POST",
                            data: {
                                ipress,
                                periodo: periodoConvertido,
                                codigoCpt,
                                estadoEst,
                                descripcion,
                                sexo,
                                edadDesde,
                                edadHasta,
                                tipoReporte
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
                        break;
                    case "masivo":
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
                            url: "/listarReporteEstrategiasTipoMasivo",
                            type: "POST",
                            data: {
                                ipress,
                                periodo: periodoConvertido,
                                codigoCpt,
                                nombre,
                                tipoReporte
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tablaPorTipoMasivo").DataTable();
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
            $("#btnExportarExcel, #btnExportarCSV").on("click", function(e) {
                let tipoReporte = "";
                let idBtn = e.currentTarget.id;
                if (idBtn == "btnExportarExcel") {
                    tipoReporte = "excel";
                } else if (idBtn == "btnExportarCSV") {
                    tipoReporte = "csv";
                }
                var tipoEstrategia = $("input[name='tipoEstrategia']:checked").val();
                const buscarPorEdad = $("#buscarPorEdad").is(":checked");
                let table = $("#tblFinalIpressFilter").DataTable();
                let ipress = table.rows().data().toArray().map(r => r.id);
                const periodo = $("#periodoInicio").val();
                const codigoCpt = $("#codigoCpt").val();
                const estadoEst = $("#estadoEst").val();
                const descripcion = $("#descripcion").val();
                const nombre = $("#nombre").val();
                const edadDesde = $("#edadDesde").val();
                const edadHasta = $("#edadHasta").val();
                const sexo = $("#sexo").val();
                let periodoConvertido = "";
                if (periodo) {
                    const partes = periodo.split("-");
                    periodoConvertido = `${partes[1]}/${partes[0]}`;
                }
                if (periodo === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar un periodo.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
                /* if (estadoEst === "" && tipoEstrategia == "individual") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar el estado.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
                if (sexo == "" && tipoEstrategia == "individual") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe seleccionar el sexo.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                } */
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
                switch (tipoEstrategia) {
                    case "individual":
                        const urlIndividual =
                            "/exportarReporteEstrategiasTipoIndividual?" +
                            "ipress[]=" + ipress.join("&ipress[]=") +
                            "&periodo=" + encodeURIComponent(periodoConvertido) +
                            "&codigoCpt=" + encodeURIComponent(codigoCpt) +
                            "&estadoEst=" + encodeURIComponent(estadoEst) +
                            "&descripcion=" + encodeURIComponent(descripcion) +
                            "&sexo=" + encodeURIComponent(sexo) +
                            "&edadDesde=" + encodeURIComponent(edadDesde) +
                            "&edadHasta=" + encodeURIComponent(edadHasta) +
                            "&tipoReporte=" + encodeURIComponent(tipoReporte);

                        window.location.href = urlIndividual;
                        break;
                    case "masivo":
                        let urlMasivo =
                            "/exportarReporteEstrategiasTipoMasivo?" +
                            "ipress[]=" + ipress.join("&ipress[]=") +
                            "&periodo=" + encodeURIComponent(periodoConvertido) +
                            "&codigoCpt=" + encodeURIComponent(codigoCpt) +
                            "&nombre=" + encodeURIComponent(nombre) +
                            "&tipoReporte=" + encodeURIComponent(tipoReporte);
                        window.location.href = urlMasivo;
                        break;
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
            var ipressDefault = {!! json_encode($ipressActual) !!};
            if (ipressDefault) {
                tblFinalIpressFilter.row.add(ipressDefault).draw(false);
            }
            if (!{!! json_encode($flag_establecimientos) !!}) {
                $("#btnModalIpress5").prop("disabled", true);
            }
        });
    </script>
@endsection
@section('content')
    <div class="container-fluid" style="margin: 0 100px;">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">REPORTE DE ESTRATEGIAS: POR TIPO</h3>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="codigoCpt">Codigo CPT</label>
                                    <input type="text" class="form-control" id="codigoCpt">
                                </div>
                            </div>
                            <div class="col-md-2 containerIndividual">
                                <div class="form-group">
                                    <label for="estadoEst">Estado Estrategia</label>
                                    <select type="text" class="form-control" id="estadoEst">
                                        <option value="">- Seleccionar -</option>
                                        <option value="1">PROTEGIDO</option>
                                        <option value="2">VACUNADO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 containerMasivo" style="display: none;">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre">
                                </div>
                            </div>
                            <div class="col-md-4 containerIndividual">
                                <div class="form-group">
                                    <label for="descripcion">Descripcion</label>
                                    <input type="text" class="form-control" id="descripcion">
                                </div>
                            </div>
                            <div class="col-md-2 containerIndividual">
                                <div class="form-group">
                                    <label for="sexo">Sexo</label>
                                    <select type="text" class="form-control" id="sexo">
                                        <option value="">- Seleccionar -</option>
                                        <option value="M">MASCULINO</option>
                                        <option value="F">FEMENINO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="margin-right: 10px; font-weight: 600;">Tipo de Estrategia</label>

                                    <label class="radio-inline">
                                        <input type="radio" name="tipoEstrategia" value="individual" checked> Individual
                                    </label>

                                    <label class="radio-inline">
                                        <input type="radio" name="tipoEstrategia" value="masivo"> Masivo
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="checkbox-inline" style="margin-left: 20px;">
                                        <input type="checkbox" id="buscarPorEdad"> Buscar por Edad
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="number" id="edadDesde" class="form-control input-sm"
                                        style="margin-left: 5px; display: none;" disabled placeholder="Edad desde">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="number" id="edadHasta" class="form-control input-sm"
                                        style="margin-left: 5px; display: none;" disabled placeholder="Edad hasta">
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
                                <button class="btn btn-default btn-sm" id="btnRemoveFilters1">
                                    <i class="glyphicon glyphicon-remove"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="" id="global">
                    <div class="row">
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
                            <div class="tab-content" style="margin-top:15px;">
                                <div class="tab-pane fade in active" id="">
                                    <div class="table-responsive" id="containerTblIndividual">
                                        <table id="tablaPorTipo" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>IPRESS</th>
                                                    <th>NOMBRE IPRESS</th>
                                                    <th>CODIGO</th>
                                                    <th>NOMBRE DE LA ESTRATEGIA</th>
                                                    <th>ESTADO</th>
                                                    <th>TITULAR</th>
                                                    <th>FAMILIAR</th>
                                                    <th>CIVICA</th>
                                                    <th>PAGANTE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive" id="containerTblMasivo" style="display: none;">
                                        <table id="tablaPorTipoMasivo" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>IPRESS</th>
                                                    <th>NOMBRE IPRESS</th>
                                                    <th>CODIGO</th>
                                                    <th>NOMBRE DE LA ESTRATEGIA</th>
                                                    <th>CANTIDAD</th>
                                                    <th>PARTICIPANTES</th>
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

                </div>
            </div>
        </div>
    </div>
    @include('frontend.seis.filters.modal_add_ipress')
    @include('frontend.includes.footer')
@endsection
