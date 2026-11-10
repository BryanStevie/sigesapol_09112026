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
                        title: 'Código IPRESS'
                    },
                    {
                        data: 'nombre_ipress',
                        title: 'Nombre IPRESS'
                    },
                    {
                        data: 'id_atencion',
                        title: 'ID Atención'
                    },
                    {
                        data: 'dni_beneficiario',
                        title: 'DNI Beneficiario'
                    },
                    {
                        data: 'apellidos_nombres',
                        title: 'Apellidos y Nombres'
                    },
                    {
                        data: 'edad',
                        title: 'Edad'
                    },
                    {
                        data: 'sexo',
                        title: 'Sexo'
                    },
                    {
                        data: 'grado',
                        title: 'Grado'
                    },
                    {
                        data: 'beneficiario',
                        title: 'Beneficiario'
                    },
                    {
                        data: 'historia',
                        title: 'Historia Clínica'
                    },
                    {
                        data: 'area_procedencia',
                        title: 'Área de Procedencia'
                    },
                    {
                        data: 'upss_procedencia',
                        title: 'UPSS Procedencia'
                    },
                    {
                        data: 'dni_ordena',
                        title: 'DNI Ordena'
                    },
                    {
                        data: 'medico_ordena',
                        title: 'Médico que Ordena'
                    },
                    {
                        data: 'colegio_responsable',
                        title: 'Colegio Responsable'
                    },

                    // Diagnósticos
                    {
                        data: 'cie1',
                        title: 'CIE 1'
                    },
                    {
                        data: 'diagnostico_1',
                        title: 'Diagnóstico 1'
                    },
                    {
                        data: 'cie2',
                        title: 'CIE 2'
                    },
                    {
                        data: 'diagnostico_2',
                        title: 'Diagnóstico 2'
                    },
                    {
                        data: 'cie3',
                        title: 'CIE 3'
                    },
                    {
                        data: 'diagnostico_3',
                        title: 'Diagnóstico 3'
                    },

                    // Alta
                    {
                        data: 'dni_alta',
                        title: 'DNI Alta'
                    },
                    {
                        data: 'medico_alta',
                        title: 'Médico Alta'
                    },
                    {
                        data: 'colegio_alta',
                        title: 'Colegio Alta'
                    },
                    {
                        data: 'cie4',
                        title: 'CIE 4'
                    },
                    {
                        data: 'diagnostico_4',
                        title: 'Diagnóstico 4'
                    },
                    {
                        data: 'cie5',
                        title: 'CIE 5'
                    },
                    {
                        data: 'diagnostico_5',
                        title: 'Diagnóstico 5'
                    },
                    {
                        data: 'cie6',
                        title: 'CIE 6'
                    },
                    {
                        data: 'diagnostico_6',
                        title: 'Diagnóstico 6'
                    },

                    // Otros
                    {
                        data: 'estancias',
                        title: 'Estancias'
                    },
                    {
                        data: 'fecha_ingreso',
                        title: 'Fecha Ingreso'
                    },
                    {
                        data: 'fecha_alta',
                        title: 'Fecha Alta'
                    },
                    {
                        data: 'motivo_alta',
                        title: 'Motivo Alta'
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

        $("#btnBuscarReporte").on("click", function(e) {
            let tipoReporte = "json";
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const conFecha = $("#buscarPorFecha").is(":checked");
            const periodo = $("#periodo").val();
            const anio = $("#año").val();
            const fechaDesde = $("#fechaDesde").val();
            const fechaHasta = $("#fechaHasta").val();
            const medico_ordena = $("#medico_ordena").val();
            const sexo = $("#sexo").val();
            const medico_alta = $("#medico_alta").val();
            const tipoBeneficiario = $("#tipoBeneficiario").val();
            const grado = $("#grado").val();
            const paciente = $("#paciente").val();
            const situacion = $("#situacion").val();
            const areaProc = $("#areaProc").val();
            const servProc = $("#servProc").val();
            const condicion = $("#condicion").val();
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

            if (conFecha) {
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
                url: "/listarReporteEgresosHospi",
                type: "POST",
                data: {
                    ipress,
                    anio,
                    fechaIni: periodo !== "" ? primerDiaMes(periodo) : fechaDesde,
                    fechaFin: periodo !== "" ? ultimoDiaMes(periodo) : fechaHasta,
                    medico_ordena,
                    sexo,
                    medico_alta,
                    tipoBeneficiario,
                    grado,
                    paciente,
                    situacion,
                    areaProc,
                    servProc,
                    condicion,
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
            const conFecha = $("#buscarPorFecha").is(":checked");
            const periodo = $("#periodo").val();
            const anio = $("#año").val();
            const fechaDesde = $("#fechaDesde").val();
            const fechaHasta = $("#fechaHasta").val();
            const medico_ordena = $("#medico_ordena").val();
            const sexo = $("#sexo").val();
            const medico_alta = $("#medico_alta").val();
            const tipoBeneficiario = $("#tipoBeneficiario").val();
            const grado = $("#grado").val();
            const paciente = $("#paciente").val();
            const situacion = $("#situacion").val();
            const areaProc = $("#areaProc").val();
            const servProc = $("#servProc").val();
            const condicion = $("#condicion").val();
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

            if (conFecha) {
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
            }

            const urlGet =
                `/exportarReporteEgresosHospi?` +

                ipress.map(i => `ipress[]=${encodeURIComponent(i)}`).join("&") +

                `&anio=${encodeURIComponent(anio)}` +
                `&fechaIni=${encodeURIComponent(periodo !== "" ? primerDiaMes(periodo) : fechaDesde)}` +
                `&fechaFin=${encodeURIComponent(periodo !== "" ? ultimoDiaMes(periodo) : fechaHasta)}` +
                `&medico_ordena=${encodeURIComponent(medico_ordena)}` +
                `&sexo=${encodeURIComponent(sexo)}` +
                `&medico_alta=${encodeURIComponent(medico_alta)}` +
                `&tipoBeneficiario=${encodeURIComponent(tipoBeneficiario)}` +
                `&grado=${encodeURIComponent(grado)}` +
                `&paciente=${encodeURIComponent(paciente)}` +
                `&situacion=${encodeURIComponent(situacion)}` +
                `&areaProc=${encodeURIComponent(areaProc)}` +
                `&servProc=${encodeURIComponent(servProc)}` +
                `&condicion=${encodeURIComponent(condicion)}` +
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
                <h3 class="panel-title">REPORTE EGRESOS HOSPITALARIOS</h3>
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
                            <div class="col-md-2" id="containerPeriodoTotal">
                                <div class="form-group">
                                    <label for="periodo">Periodo</label>
                                    <input type="month" class="form-control" id="periodo">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="medico_ordena">Medico Ordena Hospi</label>
                                    <input type="text" id="medico_ordena" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sexo">Sexo</label>
                                    <select type="text" class="form-control" id="sexo">
                                        <option value="">- Seleccionar -</option>
                                        <option value="M">MASCULINO</option>
                                        <option value="F">FEMENINO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="medico_alta">Medico Alta</label>
                                    <input type="text" id="medico_alta" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipoBeneficiario">Beneficiario</label>
                                    <select type="text" class="form-control" id="tipoBeneficiario">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($tipoBeneficiarios as $tipoBeneficiario)
                                            <option value="{{ $tipoBeneficiario->id }}">
                                                {{ $tipoBeneficiario->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="grado">Grado</label>
                                    <select type="text" class="form-control" id="grado">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($grados as $grado)
                                            <option value="{{ $grado->id }}">{{ $grado->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="paciente">Nom. Paciente</label>
                                    <input type="text" class="form-control" id="paciente">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="situacion">Situacion</label>
                                    <select type="text" class="form-control" id="situacion">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($situaciones as $situacion)
                                            <option value="{{ $situacion->id }}">
                                                {{ $situacion->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="areaProc">Area Procedencia</label>
                                    <select type="text" class="form-control" id="areaProc">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($areasP as $area)
                                            <option value="{{ $area->id_upss_origen }}">{{ $area->descripcion_upss }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="servProc">Servicio Procedencia</label>
                                    <select type="text" class="form-control" id="servProc">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($serviciosParaUps as $servicio)
                                            <option value="{{ $servicio->id }}">
                                                {{ $servicio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="condicion">Condicion</label>
                                    <select type="text" class="form-control" id="condicion">
                                        <option value="">- Seleccionar -</option>
                                        <option value="1">INGRESO</option>
                                        <option value="2">EGRESO</option>
                                    </select>
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
                                    <label for="edadDesde" id="labelEdadDesde" style="display: none;">Desde</label>
                                    <input type="number" id="edadDesde" class="form-control" style="display: none;"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="edadHasta" id="labelEdadHasta" style="display: none;">Hasta</label>
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
                                                    <th>SEXO</th>
                                                    <th>GRADO</th>
                                                    <th>BENEFICIARIO</th>
                                                    <th>N° HC</th>
                                                    <th>AREA PROCEDENCIA</th>
                                                    <th>UPSS PROCEDENCIA</th>
                                                    <th>DNI ORDENA</th>
                                                    <th>MED ORDENA HOSP</th>
                                                    <th>COLEGIO RESPONSABLE</th>
                                                    <th>CIE 1</th>
                                                    <th>DIAGNOSTICO 1</th>
                                                    <th>CIE 2</th>
                                                    <th>DIAGNOSTICO 2</th>
                                                    <th>CIE 3</th>
                                                    <th>DIAGNOSTICO 3</th>
                                                    <th>DNI ALTA</th>
                                                    <th>MEDICO ALTA</th>
                                                    <th>IPRESS DESTINO</th>
                                                    <th>CIE 4</th>
                                                    <th>DIAGNOSTICO 4</th>
                                                    <th>CIE 5</th>
                                                    <th>DIAGNOSTICO 5</th>
                                                    <th>CIE 6</th>
                                                    <th>DIAGNOSTICO 6</th>
                                                    <th>ESTANCIAS</th>
                                                    <th>FEC. INGRESO</th>
                                                    <th>FEC. ALTA</th>
                                                    <th>MOTIVO ALTA</th>
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
