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
                        data: "codigo_ipress",
                        title: "Código IPRESS"
                    },
                    {
                        data: "nombre_ipress",
                        title: "Nombre IPRESS"
                    },
                    {
                        data: "profesion",
                        title: "Profesión"
                    },
                    {
                        data: "tipo_persona",
                        title: "Tipo Persona"
                    },
                    {
                        data: "colegio",
                        title: "Colegio Profesional"
                    },
                    {
                        data: "nro_colegiatura",
                        title: "N° Colegiatura"
                    },
                    {
                        data: "rne",
                        title: "RNE"
                    },
                    {
                        data: "dni",
                        title: "DNI"
                    },
                    {
                        data: "grado",
                        title: "Grado"
                    },
                    {
                        data: "apellidos_nombres",
                        title: "Apellidos y Nombres"
                    },

                    // ---- Permisos / Licencias ----
                    {
                        data: "feriados",
                        title: "Feriados"
                    },
                    {
                        data: "otros_permisos",
                        title: "Otros Permisos"
                    },
                    {
                        data: "vacaciones",
                        title: "Vacaciones"
                    },

                    // ---- Actividades Asistenciales ----
                    {
                        data: "consulta_externa",
                        title: "Consulta Externa"
                    },
                    {
                        data: "procedimientos",
                        title: "Procedimientos"
                    },
                    {
                        data: "hospitalizacion",
                        title: "Hospitalización"
                    },
                    {
                        data: "centro_quirurgico",
                        title: "Quirúrgico"
                    },
                    {
                        data: "obstetricia",
                        title: "Obstetricia"
                    },
                    {
                        data: "emergencia",
                        title: "Emergencia"
                    },
                    {
                        data: "administrativo",
                        title: "Administración"
                    },

                    // ---- Prevención / Apoyos ----
                    {
                        data: "preventivo_promocional_1",
                        title: "Prevención I"
                    },
                    {
                        data: "apoyo_dx",
                        title: "Apoyo Diagnóstico"
                    },
                    {
                        data: "apoyo_tto",
                        title: "Apoyo Tratamiento"
                    },
                    {
                        data: "capacitacion_perfeccionamiento_1",
                        title: "Capacitación"
                    },
                    {
                        data: "preventivo_promocional_2",
                        title: "Prevención II"
                    },

                    // ---- Comunitarios / Otros ----
                    {
                        data: "domiciliaria",
                        title: "Visitas Domiciliarias"
                    },
                    {
                        data: "comisiones_asistencial",
                        title: "Comunitario Asistencial"
                    },
                    {
                        data: "comisiones_administrativo",
                        title: "Comunitario Administrativo"
                    },
                    {
                        data: "comisiones_otros",
                        title: "Comunitario Otros"
                    },
                    {
                        data: "capacitacion_perfeccionamiento_2",
                        title: "CAP"
                    },
                    {
                        data: "otros",
                        title: "Otros"
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

        $("#btnBuscarReporte").on("click", function(e) {
            let tipoReporte = "json";
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const anio = $("#año").val();
            const periodoRaw = $("#periodo").val();
            const tipoPersona = $("#tipoPersona").val();
            const colegio = $("#colegio").val();
            const grado = $("#grado").val();
            const situacion = $("#situacion").val();
            const sexo = $("#sexo").val();
            const profesion = $("#profesion").val();
            const especialidad = $("#especialidad").val();
            const empleado = $("#empleado").val();
            let periodo = "";
            if (periodoRaw) {
                const [anio, mes] = periodoRaw.split("-");
                periodo = mes + anio; // "092025"
            }

            if (periodoRaw === "" && anio === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe ingresar el año o seleccionar el periodo.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
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
                url: "/listarReporteRecursos",
                type: "POST",
                data: {
                    ipress,
                    anio: periodo !== "" ? "" : anio,
                    periodo,
                    tipoPersona,
                    colegio,
                    grado,
                    situacion,
                    sexo,
                    profesion,
                    especialidad,
                    empleado,
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
        $("#btnExportarCSV, #btnExportarExcel").on("click", function(e) {
            let idBtn = e.currentTarget.id;
            let tipoReporte = "json";
            if (idBtn == "btnExportarExcel") {
                tipoReporte = "excel";
            } else if (idBtn == "btnExportarCSV") {
                tipoReporte = "csv";
            }
            let table = $("#tblFinalIpressFilter").DataTable();
            let ipress = table.rows().data().toArray().map(r => r.id);
            const anio = $("#año").val();
            const periodoRaw = $("#periodo").val();
            const tipoPersona = $("#tipoPersona").val();
            const colegio = $("#colegio").val();
            const grado = $("#grado").val();
            const situacion = $("#situacion").val();
            const sexo = $("#sexo").val();
            const profesion = $("#profesion").val();
            const especialidad = $("#especialidad").val();
            const empleado = $("#empleado").val();
            let periodo = "";
            if (periodoRaw) {
                const [anio, mes] = periodoRaw.split("-");
                periodo = mes + anio; // "092025"
            }

            if (periodoRaw === "" && anio === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Debe ingresar el año o seleccionar el periodo.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            const url =
                "/exportarReporteRecursos?" +
                "ipress[]=" + ipress.join("&ipress[]=") +
                "&anio=" + encodeURIComponent(periodo !== "" ? "" : anio) +
                "&periodo=" + encodeURIComponent(periodo) +
                "&tipoPersona=" + encodeURIComponent(tipoPersona) +
                "&colegio=" + encodeURIComponent(colegio) +
                "&grado=" + encodeURIComponent(grado) +
                "&situacion=" + encodeURIComponent(situacion) +
                "&sexo=" + encodeURIComponent(sexo) +
                "&profesion=" + encodeURIComponent(profesion) +
                "&especialidad=" + encodeURIComponent(especialidad) +
                "&empleado=" + encodeURIComponent(empleado) +
                "&tipoReporte=" + encodeURIComponent(tipoReporte);
            window.location.href = url;
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
                <h3 class="panel-title">REPORTE RECURSOS</h3>
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
                                    <label for="tipoPersona">Tipo Persona</label>
                                    <select type="text" class="form-control" id="tipoPersona">
                                        <option value="">- Seleccionar -</option>
                                        <option value="1">INSTITUCIONAL (PNP)</option>
                                        <option value="2">PROFESIONAL CONTRATADO</option>
                                        <option value="3">SERUMS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="colegio">Colegio Prof.</label>
                                    <select type="text" class="form-control" id="colegio">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($colegio_profesionales as $colegio_profesional)
                                            <option value="{{ $colegio_profesional->id }}">
                                                {{ $colegio_profesional->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="empleado">Empleado</label>
                                    <input type="text" class="form-control" id="empleado"
                                        placeholder="DNI Y/O NOM, APE">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- <div class="col-md-3">
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
                            </div> --}}
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
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sexo">Sexo</label>
                                    <select type="text" class="form-control" id="sexo">
                                        <option value="">- Seleccionar -</option>
                                        <option value="M">MASCULINO</option>
                                        <option value="F">FEMENINO</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="profesion">Profesion</label>
                                    <select type="text" class="form-control" id="profesion">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($profesiones as $profesion)
                                            <option value="{{ $profesion->id }}">{{ $profesion->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="especialidad">Especialidad</label>
                                    <select type="text" class="form-control" id="especialidad">
                                        <option value="">- Seleccionar -</option>
                                        @foreach ($especialidades as $especialidad)
                                            <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}
                                            </option>
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
                            <button class="btn btn-warning" type="button" id="btnExportarCSV">
                                <i class="fa fa-file-text-o"></i> Exportar CSV
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
                                                    <th>PROFESION</th>
                                                    <th>TIPO PERSONA</th>
                                                    <th>COLEGIO</th>
                                                    <th>N° COLEGIATURA</th>
                                                    <th>RNE</th>
                                                    <th>DNI</th>
                                                    <th>GRADO</th>
                                                    <th>APELLIDOS Y NOMBRES</th>
                                                    <th>FERIADOS</th>
                                                    <th>OTROS (permisos)</th>
                                                    <th>VACACIONES</th>
                                                    <th>CON.EXT.</th>
                                                    <th>PROC.</th>
                                                    <th>HOSPT.</th>
                                                    <th>QUIRUR.</th>
                                                    <th>OBST.</th>
                                                    <th>EMER.</th>
                                                    <th>ADMIS.</th>
                                                    <th>PREVEN.</th>
                                                    <th>APOYO Dx.</th>
                                                    <th>APOYO TTO</th>
                                                    <th>CAPAC.</th>
                                                    <th>PREVEN.</th>
                                                    <th>DOMIC.</th>
                                                    <th>COM.ASIS.</th>
                                                    <th>COM.ADM.</th>
                                                    <th>COM.OTROS</th>
                                                    <th>CAPAC.</th>
                                                    <th>OTROS</th>
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
