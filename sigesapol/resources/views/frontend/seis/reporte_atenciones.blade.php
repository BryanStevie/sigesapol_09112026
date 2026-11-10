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
        #ups,
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
            $('#tipoAtencionDA').select2({
                placeholder: '',
                width: '100%'
            });
            $('#selectGrado').select2({
                placeholder: '',
                width: '100%'
            });
            $('#ugipress_DA').select2({
                allowClear: true,
                width: '100%'
            });
            $("#selectDepartamento").on("change", function(e) {
                const valor = $(this).val();

                $.ajax({
                    url: "/buscarProvinciaPorDepartamento",
                    type: "POST",
                    data: {
                        departamento_id: valor
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    success: function(response) {
                        console.log("Respuesta del servidor:", response);
                        $("#selectProvincia").empty();
                        $("#selectDistrito").empty();
                        $("#selectProvincia").append(
                            $("<option>", {
                                value: "",
                                text: "-- Seleccione --"
                            })
                        );
                        response.forEach(function(provincia) {
                            $("#selectProvincia").append(
                                $("<option>", {
                                    value: provincia.id_provincia,
                                    text: provincia.descripcion
                                })
                            );
                        });
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                    }
                });
            });
            $("#selectProvincia").on("change", function(e) {
                const valor = $(this).val();

                $.ajax({
                    url: "/buscarDistritoPorProvincia",
                    type: "POST",
                    data: {
                        provincia_id: valor
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    success: function(response) {
                        console.log("Respuesta del servidor:", response);
                        $("#selectDistrito").empty();
                        $("#selectDistrito").append(
                            $("<option>", {
                                value: "",
                                text: "-- Seleccione --"
                            })
                        );
                        response.forEach(function(distrito) {
                            $("#selectDistrito").append(
                                $("<option>", {
                                    value: distrito.id_distrito,
                                    text: distrito.descripcion
                                })
                            );
                        });
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                    }
                });
            });
            $("#omitirFechaDA, #seleccionarAtendidosDA").on("click", function(e) {
                const id = this.id;
                const isChecked = $(this).is(":checked");

                console.log("ID:", id);
                console.log("¿Está activo?:", isChecked);

                if (id === "omitirFechaDA") {
                    if (isChecked) {
                        $("#divFechaIniDA").hide();
                        $("#divFechaFinDA").hide();
                        $("#fechaIniInputDA").prop("disabled", true);
                        $("#fechaFinInputDA").prop("disabled", true);
                        $("#fechaIniInputDA").val("");
                        $("#fechaFinInputDA").val("");
                        $("#divPeriodoDA").show();
                        $("#periodoInputDA").prop("disabled", false);
                    } else {
                        $("#divFechaIniDA").show();
                        $("#divFechaFinDA").show();
                        $("#fechaIniInputDA").prop("disabled", false);
                        $("#fechaFinInputDA").prop("disabled", false);
                        $("#divPeriodoDA").hide();
                        $("#periodoInputDA").prop("disabled", true);
                        $("#periodoInputDA").val("");
                    }
                } else if (id === "seleccionarAtendidosDA") {
                    if (isChecked) {
                        $("#divCondicionEstaDA").hide();
                        $("#divCondicionServDA").hide();
                        $("#condicionEstaDA").prop("disabled", true);
                        $("#condicionServDA").prop("disabled", true);
                    } else {
                        $("#divCondicionEstaDA").show();
                        $("#divCondicionServDA").show();
                        $("#condicionEstaDA").prop("disabled", false);
                        $("#condicionServDA").prop("disabled", false);
                    }
                }
            });
            $("#checkOmitirFechaAudi").on("click", function(e) {
                const id = this.id;
                const isChecked = $(this).is(":checked");

                if (isChecked) {
                    $("#containerFechaIniAudi").hide();
                    $("#containerFechaFinAudi").hide();
                    $("#inputFechaInicioAudi").prop("disabled", true);
                    $("#inputFechaFinAudi").prop("disabled", true);
                    $("#inputFechaInicioAudi").val("");
                    $("#inputFechaFinAudi").val("");
                } else {
                    $("#containerFechaIniAudi").show();
                    $("#containerFechaFinAudi").show();
                    $("#inputFechaInicioAudi").prop("disabled", false);
                    $("#inputFechaFinAudi").prop("disabled", false);
                }
            });
            let tblReporteAtenciones = $('#tblReporteAtenciones').DataTable({
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'fecha_atencion'
                    },
                    {
                        data: 'tipo_atencion'
                    },
                    {
                        data: 'establecimiento'
                    },
                    {
                        data: 'descripcion_upss'
                    },
                    {
                        data: 'nombre_consultorio'
                    },
                    {
                        data: 'asegurado'
                    },
                    {
                        data: 'edad'
                    },
                    {
                        data: 'sexo'
                    },
                    {
                        data: 'medico'
                    },
                    {
                        data: "estado",
                        render: function(data, type, row) {
                            if (data == 1) {
                                return `<span style="background-color: green;" class="badge bg-success">Activo</span>`;
                            } else {
                                return `<span class="badge bg-danger">No activo</span>`;
                            }
                        },
                        className: "text-center"
                    },
                    {
                        data: 'colegio_profesional'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `${row.codigo_diagnostico} - ${row.nombre_diagnostico}`;
                        },
                    },
                    {
                        data: 'discapacidad'
                    }
                ],
                language: {
                    emptyTable: "No hay datos disponibles en la tabla"
                },
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                destroy: true,
            });

            var tblFinalUpsFilter = $("#tblFinalUpsFilter").DataTable({
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
                        data: "descripcion_upss"
                    },
                    {
                        title: "Accion",
                        data: null,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-danger btn-removeToFilter-upss" 
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
            $('#tblFinalUpsFilter').on('click', '.btn-removeToFilter-upss', function() {
                var row = tblFinalUpsFilter.row($(this).closest('tr'));
                row.remove().draw();
            });

            var ipressDefault = {!! json_encode($ipressActual) !!};

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

            if (ipressDefault) {
                tblFinalIpressFilter.row.add(ipressDefault).draw(false);
            }

            $('#tblFinalIpressFilter').on('click', '.btn-removeToFilter-ipress', function() {
                var row = tblFinalIpressFilter.row($(this).closest('tr'));
                row.remove().draw();
            });

            var tblFinalDiagFilter = $("#tblFinalDiagFilter").DataTable({
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
                        data: "nombre_diagnostico"
                    },
                    {
                        title: "Accion",
                        data: null,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-danger btn-removeToFilter-diag" 
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
            $('#tblFinalDiagFilter').on('click', '.btn-removeToFilter-diag', function() {
                var row = tblFinalDiagFilter.row($(this).closest('tr'));
                row.remove().draw();
            });

            var tblFinalServFilter = $("#tblFinalServFilter").DataTable({
                searching: false,
                lengthChange: false,
                paging: false,
                info: false,
                ordering: false,
                columns: [{
                        title: "Codigo",
                        data: "id"
                    },
                    {
                        title: "Descripcion",
                        data: "nombre_servicio"
                    },
                    {
                        title: "Accion",
                        data: null,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-danger btn-removeToFilter-serv" 
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
            $('#tblFinalServFilter').on('click', '.btn-removeToFilter-serv', function() {
                var row = tblFinalServFilter.row($(this).closest('tr'));
                row.remove().draw();
            });
        });

        $("#checkEdadPaciente").on("click", function(e) {
            if ($(this).is(":checked")) {
                $("#containerRangoEdadPaciente").show();
                $("#containerEdadSoloPaciente").hide();
                $("#edadPaciente").val("");
            } else {
                $("#containerRangoEdadPaciente").hide();
                $("#fechaDesdePaciente").val("");
                $("#fechaHastaPaciente").val("");
                $("#containerEdadSoloPaciente").show();
            }
        });

        $("#btnBuscarPersonalMedico").on("click", function(e) {
            e.preventDefault();
            const dni = $("#inputNumeroDocumento").val().trim();

            if (dni === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo vacío',
                    text: 'Por favor, ingrese el número de documento del médico.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            const datos = {
                dni_medico: dni
            };

            $.ajax({
                url: '/buscarMedicoPorDni',
                type: 'POST',
                data: datos,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $("#responseDataMedico").val(
                        `${res.nombre_medico}, ${res.paterno_medico} ${res.materno_medico}`);
                    $("#medicoId").val(res.id_medico);
                    $("#selectTipoDocumento").val(res.id_td);
                    $("#tipo_doc_responsable").val(res.id_td);
                    $("#selectProfesion").val(res.id_profesion);
                    $("#selectColegioProfesional").val(res.id_cp);
                    $("#selectEspecialidad").val(res.id_especializacion);
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud:", error);
                    alert("Ocurrió un error al consultar los médicos.");
                }
            });
        });

        $("#btnBuscarUserAuditoria").on("click", function(e) {
            e.preventDefault();
            const dni = $("#inputNumeroDocumentoAudi").val().trim();
            if (dni === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo vacío',
                    text: 'Por favor, ingrese el número de documento del médico.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            const datos = {
                dni_user: dni
            };

            $.ajax({
                url: '/buscarUsuarioPorDni',
                type: 'POST',
                data: datos,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $("#namesUser").val(
                        `${res.name}, ${res.paterno} ${res.materno}`);
                    $("#userId").val(res.id);
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud:", error);
                    alert("Ocurrió un error al consultar los médicos.");
                }
            });
        });

        $("#btnBuscarAsegurado").on("click", function(e) {
            e.preventDefault();
            const dni = $("#inputNumeroDocumentoPaciente").val().trim();

            if (dni === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo vacío',
                    text: 'Por favor, ingrese el número de documento del médico.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            const datos = {
                dni_asegurado: dni
            };

            $.ajax({
                url: '/buscarAseguradoPorDni',
                type: 'POST',
                data: datos,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    console.log(res);
                    $("#selectTipoDocumentoPaciente").val(res.id_tipo_doc_ident);
                    $("#tipoDocumentoPaciente").val(res.id_tipo_doc_ident);
                    $("#nombresConApellidosPaciente").val(
                        `${res.nombre}, ${res.paterno} ${res.materno}`);
                    $("#nroIdAseguradoPaciente").val(res.id_asegurado);
                    $("#selectSexoPaciente").val(res.sexo == "F" ? "FEMENINO" : "MASCULINO");
                    $("#inputNumeroCIP").val(res.cip);
                    $("#selectBeneficiario").val(res.id_tipo_beneficiario);
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud:", error);
                    alert("Ocurrió un error al consultar los médicos.");
                }
            });
        });

        $("#modalAddUpssToFilters, #seleccionarServModal, #seleccionarIPRESSModal, #seleccionarDiagModal").on("click",
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

        $("#modalAddUpssToFilters, #seleccionarServModal, #seleccionarIPRESSModal, #seleccionarDiagModal").on("click",
            ".remove_all",
            function(e) {
                const idPadre = $(e.delegateTarget).attr("id");
                const $tablaRemove = $("#" + idPadre).find("table.toQuit");
                const dtDestino = $tablaRemove.DataTable();
                dtDestino.clear().draw();
            });

        $("#btnAddFilters1").on("click", function(e) {
            $("#modalAddUpssToFilters").modal("show");
        });
        $("#btnAddFilters2").on("click", function(e) {
            $("#seleccionarIPRESSModal").modal("show");
        });
        if (!{!! json_encode($flag_establecimientos) !!}) {
            $("#btnAddFilters2").prop("disabled", true);
        }
        $("#btnAddFilters3").on("click", function(e) {
            $("#seleccionarDiagModal").modal("show");
        });
        $("#btnAddFilters4").on("click", function(e) {
            $("#seleccionarServModal").modal("show");
        });
        $("#btnAddFilters5").on("click", function(e) {
            $("#seleccionarDependenciaModal").modal("show");
        });
        $("#fechaIniInputDA, #fechaFinInputDA").on("change", function() {
            const inicio = $("#fechaIniInputDA").val();
            const fin = $("#fechaFinInputDA").val();

            if (inicio && fin && inicio > fin) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La fecha de inicio no puede ser mayor a la fecha final.',
                    timer: 2000,
                    showConfirmButton: false
                });
                $("#fechaIniInputDA").val("");
            }
        });
        $("#inputFechaInicioAudi, #inputFechaFinAudi").on("change", function() {
            const inicio = $("#inputFechaInicioAudi").val();
            const fin = $("#inputFechaFinAudi").val();

            if (inicio && fin && inicio > fin) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La fecha de inicio no puede ser mayor a la fecha final.',
                    timer: 2000,
                    showConfirmButton: false
                });
                $("#inputFechaInicioAudi").val("");
            }
        });
        $("#fechaDesdePaciente, #fechaHastaPaciente").on("change", function(e) {
            e.preventDefault();
            const inicio = $("#fechaDesdePaciente").val();
            const fin = $("#fechaHastaPaciente").val();

            if (inicio && fin && inicio > fin) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La edad del paciente inicial no puede ser mayor a la edad final.',
                    timer: 2000,
                    showConfirmButton: false
                });
                $("#fechaDesdePaciente").val("");
            }
        });
        $("#btnBuscarReporte").on("click", function(e) {
            let tipoReporte = "json";
            var activeTab = $('.nav-tabs li.active a').attr('href');
            switch (activeTab) {
                case "#ups":
                    let table = $("#tblFinalUpsFilter").DataTable();
                    let ups = table.rows().data().toArray().map(r => r.id);
                    if (ups.length > 0) {
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
                            url: "/reporteAtencionesUps",
                            type: "POST",
                            data: {
                                ups,
                                tipoReporte
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tblReporteAtenciones").DataTable();
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
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar una upss.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
                case "#atencion":
                    const tipoAtencionMultiple = $("#tipoAtencionDA").val();
                    const ugipress = $("#ugipress_DA").val();
                    const omitirFechaDA = $("#omitirFechaDA").is(":checked");
                    const seleccionarAtendidosDA = $("#seleccionarAtendidosDA").is(":checked");
                    const periodoInputDA = $("#periodoInputDA").val();
                    const fechaIniInputDA = $("#fechaIniInputDA").val();
                    const fechaFinInputDA = $("#fechaFinInputDA").val();
                    const condicionEstaDA = $("#condicionEstaDA").val();
                    const condicionServDA = $("#condicionServDA").val();
                    if (!tipoAtencionMultiple) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar un tipo de atencion.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        break;
                    }
                    if (omitirFechaDA) {
                        if (periodoInputDA == "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Advertencia',
                                text: 'Debe seleccionar el periodo.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            break;
                        }
                    } else {
                        if (fechaIniInputDA == "" || fechaFinInputDA == "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Advertencia',
                                text: 'Debe seleccionar la fecha inicial y final.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            break;
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
                        url: "/reporteAtencionesDA",
                        type: "POST",
                        data: {
                            tipoAtencionMultiple,
                            ugipress,
                            fechaIni: !omitirFechaDA ? fechaIniInputDA : primerDiaMes(periodoInputDA),
                            fechaFin: !omitirFechaDA ? fechaFinInputDA : ultimoDiaMes(periodoInputDA),
                            condicionEstablecimiento: seleccionarAtendidosDA ? null : condicionEstaDA,
                            condicionServicio: seleccionarAtendidosDA ? null : condicionServDA,
                            tipoReporte
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tblReporteAtenciones").DataTable();
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
                    break;
                case "#tipo":
                    const todosDTA = $("#todosDTA").is(":checked");
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
                        url: "/reporteAtencionesTA",
                        type: "POST",
                        data: {
                            todosDTA,
                            tipoReporte
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tblReporteAtenciones").DataTable();
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
                    break;
                case "#ipress":
                    let tableIpress = $("#tblFinalIpressFilter").DataTable();
                    let ipress = tableIpress.rows().data().toArray().map(r => r.id);
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
                            url: "/reporteAtencionesIpress",
                            type: "POST",
                            data: {
                                ipress,
                                tipoReporte
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tblReporteAtenciones").DataTable();
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
                case "#diagnostico":
                    let tableDiag = $("#tblFinalDiagFilter").DataTable();
                    let diagnosticos = tableDiag.rows().data().toArray().map(r => r.id);
                    if (diagnosticos.length > 0) {
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
                            url: "/reporteAtencionesDiagnosticos",
                            type: "POST",
                            data: {
                                diagnosticos,
                                tipoReporte
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tblReporteAtenciones").DataTable();
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
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar un diagnostico.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
                case "#servicio":
                    let tableServ = $("#tblFinalServFilter").DataTable();
                    let servicios = tableServ.rows().data().toArray().map(r => r.id);
                    let situacion = $('input[name="situacionSER"]:checked').val();
                    if (servicios.length > 0) {
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
                            url: "/reporteAtencionesServicios",
                            type: "POST",
                            data: {
                                servicios,
                                situacion,
                                tipoReporte
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tblReporteAtenciones").DataTable();
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
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar una servicio.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
                case "#responsables":
                    const medico_id = $("#medicoId").val();
                    if (medico_id || medico_id != "") {
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
                            url: "/reporteAtencionesResponsables",
                            type: "POST",
                            data: {
                                medico_id,
                                tipoReporte
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tblReporteAtenciones").DataTable();
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
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe buscar datos del personal medico.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
                case "#pacientes":
                    const asegurado_td = $("#selectTipoDocumentoPaciente").val();
                    const asegurado_nro_doc = $("#inputNumeroDocumentoPaciente").val();
                    const asegurado_nro_cip = $("#inputNumeroCIP").val();
                    const asegurado_sexo = $("#selectSexoPaciente").val();
                    const asegurado_edad = $("#edadPaciente").val();
                    const asegurado_edad_desde = $("#fechaDesdePaciente").val();
                    const asegurado_edad_hasta = $("#fechaHastaPaciente").val();
                    const asegurado_tipo_beneficiario = $("#selectBeneficiario").val();
                    const asegurado_grado = $("#selectGrado").val();
                    const asegurado_situacion = $("#selectSituacion").val();
                    const asegurado_discapacidad = $("#checkDiscapacidad").is(":checked");
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
                        url: "/reporteAtencionesPaciente",
                        type: "POST",
                        data: {
                            asegurado_td,
                            asegurado_nro_doc,
                            asegurado_nro_cip,
                            asegurado_sexo,
                            asegurado_edad,
                            asegurado_edad_desde,
                            asegurado_edad_hasta,
                            asegurado_tipo_beneficiario,
                            asegurado_grado,
                            asegurado_situacion,
                            asegurado_discapacidad,
                            tipoReporte
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            Swal.close();
                            let tabla = $("#tblReporteAtenciones").DataTable();
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
                    break;
                case "#ubigeo":
                    const departamento_id = $("#selectDepartamento").val();
                    const provincia_id = $("#selectProvincia").val();
                    const distrito_id = $("#selectDistrito").val();
                    if (departamento_id == "") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar al menos el departamento.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
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
                            url: "/reporteAtencionesPacienteUbigeo",
                            type: "POST",
                            data: {
                                departamento_id,
                                provincia_id,
                                distrito_id,
                                tipoReporte
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tblReporteAtenciones").DataTable();
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
                    }
                    break;
                case "#auditoria":
                    const nro_documento = $("#inputNumeroDocumentoAudi").val();
                    const fecha_inicio = $("#inputFechaInicioAudi").val();
                    const fecha_fin = $("#inputFechaFinAudi").val();
                    if (nro_documento == "") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe ingresar el nro de documento.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
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
                            url: "/reporteAtencionesAuditoria",
                            type: "POST",
                            data: {
                                nro_documento,
                                fecha_inicio,
                                fecha_fin,
                                tipoReporte
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tblReporteAtenciones").DataTable();
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
                    }
                    break;
            }
        });
        $("#btnExportarExcel, #btnExportarCSV").on("click", function(e) {
            let idBtn = e.currentTarget.id;
            let tipoReporte = "json";
            if (idBtn == "btnExportarExcel") {
                tipoReporte = "excel";
            } else if (idBtn == "btnExportarCSV") {
                tipoReporte = "csv";
            }
            var activeTab = $('.nav-tabs li.active a').attr('href');
            const seleccionado = $("input[name='mostrar']:checked").val();
            switch (activeTab) {
                case "#ups":
                    let table = $("#tblFinalUpsFilter").DataTable();
                    let ups = table.rows().data().toArray().map(r => r.id);
                    if (ups.length > 0) {
                        let url = "/reporteAtencionesUpsExport?" +
                            "ups[]=" + ups.join("&ups[]=") +
                            "&tipoReporte=" + tipoReporte;

                        window.location.href = url;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar una upss.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
                case "#atencion":
                    const tipoAtencionMultiple = $("#tipoAtencionDA").val();
                    const ugipress = $("#ugipress_DA").val();
                    const omitirFechaDA = $("#omitirFechaDA").is(":checked");
                    const seleccionarAtendidosDA = $("#seleccionarAtendidosDA").is(":checked");
                    const periodoInputDA = $("#periodoInputDA").val();
                    const fechaIniInputDA = $("#fechaIniInputDA").val();
                    const fechaFinInputDA = $("#fechaFinInputDA").val();
                    const condicionEstaDA = $("#condicionEstaDA").val();
                    const condicionServDA = $("#condicionServDA").val();
                    const fechaIniParsed = !omitirFechaDA ? fechaIniInputDA : primerDiaMes(periodoInputDA);
                    const fechaFinParsed = !omitirFechaDA ? fechaFinInputDA : ultimoDiaMes(periodoInputDA);
                    const condicionEstablecimientoParsed = seleccionarAtendidosDA ? null : condicionEstaDA;
                    const condicionServicioParsed = seleccionarAtendidosDA ? null : condicionServDA;
                    if (!tipoAtencionMultiple) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar un tipo de atencion.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        break;
                    }
                    if (omitirFechaDA) {
                        if (periodoInputDA == "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Advertencia',
                                text: 'Debe seleccionar el periodo.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            break;
                        }
                    } else {
                        if (fechaIniInputDA == "" || fechaFinInputDA == "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Advertencia',
                                text: 'Debe seleccionar la fecha inicial y final.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            break;
                        }
                    }
                    let url = "/reporteAtencionesDAExport?" +
                        "tipoAtencionMultiple[]=" + tipoAtencionMultiple.join("&tipoAtencionMultiple[]=") +
                        "&ugipress=" + ugipress +
                        /* "&fechaIni=" + fechaIniInputDA +
                        "&fechaFin=" + fechaFinInputDA */
                        "&fechaIni=" + (!omitirFechaDA ? fechaIniInputDA : primerDiaMes(periodoInputDA)) +
                        "&fechaFin=" + (!omitirFechaDA ? fechaFinInputDA : ultimoDiaMes(periodoInputDA)) +
                        "&condicionEstablecimiento=" + (condicionEstablecimientoParsed ?
                            condicionEstablecimientoParsed : "") +
                        "&condicionServicio=" + (condicionServicioParsed ? condicionServicioParsed : "") +
                        "&tipoReporte=" + tipoReporte;

                    window.location.href = url;
                    break;
                case "#tipo":
                    const todosDTA = $("#todosDTA").is(":checked");
                    let urlTA = "/reporteAtencionesTAExport?" +
                        "&todosDTA=" + todosDTA +
                        "&tipoReporte=" + tipoReporte;

                    window.location.href = urlTA;
                    break;
                case "#ipress":
                    let tableIpress = $("#tblFinalIpressFilter").DataTable();
                    let ipress = tableIpress.rows().data().toArray().map(r => r.id);
                    if (ipress.length > 0) {
                        let url = "/reporteAtencionesIpressExport?" +
                            "ipress[]=" + ipress.join("&ipress[]=") +
                            "&tipoReporte=" + tipoReporte;

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
                    break;
                case "#diagnostico":
                    let tableDiag = $("#tblFinalDiagFilter").DataTable();
                    let diagnosticos = tableDiag.rows().data().toArray().map(r => r.id);
                    if (diagnosticos.length > 0) {
                        let url = "/reporteAtencionesDiagnosticosExport?" +
                            "diagnosticos[]=" + diagnosticos.join("&diagnosticos[]=") +
                            "&tipoReporte=" + tipoReporte;

                        window.location.href = url;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar un diagnostico.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
                case "#servicio":
                    let tableServ = $("#tblFinalServFilter").DataTable();
                    let servicios = tableServ.rows().data().toArray().map(r => r.id);
                    let situacion = $('input[name="situacionSER"]:checked').val();
                    if (servicios.length > 0) {
                        let url = "/reporteAtencionesServiciosExport?" +
                            "servicios[]=" + servicios.join("&servicios[]=") +
                            "&situacion=" + situacion +
                            "&tipoReporte=" + tipoReporte;

                        window.location.href = url;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar una servicio.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
                case "#responsables":
                    const medico_id = $("#medicoId").val();
                    if (medico_id || medico_id != "") {
                        let url = "/reporteAtencionesResponsablesExport?" +
                            "&medico_id=" + medico_id +
                            "&tipoReporte=" + tipoReporte;

                        window.location.href = url;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe buscar datos del personal medico.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    break;
                case "#pacientes":
                    const asegurado_td = $("#selectTipoDocumentoPaciente").val();
                    const asegurado_nro_doc = $("#inputNumeroDocumentoPaciente").val();
                    const asegurado_nro_cip = $("#inputNumeroCIP").val();
                    const asegurado_sexo = $("#selectSexoPaciente").val();
                    const asegurado_edad = $("#edadPaciente").val();
                    const asegurado_edad_desde = $("#fechaDesdePaciente").val();
                    const asegurado_edad_hasta = $("#fechaHastaPaciente").val();
                    const asegurado_tipo_beneficiario = $("#selectBeneficiario").val();
                    const asegurado_grado = $("#selectGrado").val();
                    const asegurado_situacion = $("#selectSituacion").val();
                    const asegurado_discapacidad = $("#checkDiscapacidad").is(":checked");
                    let urlPacientes = "/reporteAtencionesPacienteExport?" +
                        "&asegurado_td=" + asegurado_td +
                        "&asegurado_nro_doc=" + asegurado_nro_doc +
                        "&asegurado_nro_cip=" + asegurado_nro_cip +
                        "&asegurado_sexo=" + asegurado_sexo +
                        "&asegurado_edad=" + asegurado_edad +
                        "&asegurado_edad_desde=" + asegurado_edad_desde +
                        "&asegurado_edad_hasta=" + asegurado_edad_hasta +
                        "&asegurado_tipo_beneficiario=" + asegurado_tipo_beneficiario +
                        "&asegurado_grado=" + asegurado_grado +
                        "&asegurado_situacion=" + asegurado_situacion +
                        "&asegurado_discapacidad=" + asegurado_discapacidad +
                        "&tipoReporte=" + tipoReporte;

                    window.location.href = urlPacientes;
                    break;
                case "#ubigeo":
                    const departamento_id = $("#selectDepartamento").val();
                    const provincia_id = $("#selectProvincia").val();
                    const distrito_id = $("#selectDistrito").val();
                    if (departamento_id == "") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar al menos el departamento.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        let urlPacientes = "/reporteAtencionesPacienteUbigeoExport?" +
                            "&departamento_id=" + departamento_id +
                            "&provincia_id=" + provincia_id +
                            "&distrito_id=" + distrito_id +
                            "&tipoReporte=" + tipoReporte;

                        window.location.href = urlPacientes;
                    }
                    break;
                case "#auditoria":
                    const nro_documento = $("#inputNumeroDocumentoAudi").val();
                    const fecha_inicio = $("#inputFechaInicioAudi").val();
                    const fecha_fin = $("#inputFechaFinAudi").val();
                    if (nro_documento == "") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe ingresar el nro de documento.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        let urlPacientes = "/reporteAtencionesAuditoriaExport?" +
                            "&nro_documento=" + nro_documento +
                            "&fecha_inicio=" + fecha_inicio +
                            "&fecha_fin=" + fecha_fin +
                            "&tipoReporte=" + tipoReporte;

                        window.location.href = urlPacientes;
                    }
                    break;
            }
        });
        $("#btnReload").on("click", function() {
            location.reload();
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

        $('input[name="mostrar"]').on('change', function() {
            let valor = $(this).attr('id');

            if (valor === "mostrarNroReg") {
                $("#containerTotalReporteAt").show();
                $("#containerTableReporteAt").hide();
            } else if (valor == "mostrarDatos") {
                $("#containerTableReporteAt").show();
                $("#containerTotalReporteAt").hide();
            }
        });
    </script>
@endsection
@section('content')
    <div class="container-fluid" style="margin: 0 100px;">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">R. ATENCIONES</h3>
            </div>
            <div class="panel-body panel_custom-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#ups" data-toggle="tab">Datos UPS</a></li>
                    <li><a href="#atencion" data-toggle="tab">Datos Atención</a></li>
                    <li><a href="#tipo" data-toggle="tab">Datos Tipo Atención</a></li>
                    <li><a href="#ipress" data-toggle="tab">Datos IPRESS</a></li>
                    <li><a href="#diagnostico" data-toggle="tab">Dat. Diagnostico</a></li>
                    <li><a href="#servicio" data-toggle="tab">Dat. Servicio</a></li>
                    <li><a href="#responsables" data-toggle="tab">Dat. Responsables</a></li>
                    <li><a href="#pacientes" data-toggle="tab">Dat. Pacientes</a></li>
                    <li><a href="#ubigeo" data-toggle="tab">Dat. Paciente/Ubigeo</a></li>
                    <li><a href="#auditoria" data-toggle="tab">Auditoria</a></li>
                </ul>
                <div class="header-filters">
                    <!-- Contenido de Tabs -->
                    <div class="tab-content">

                        <!-- Tab UPS -->
                        <div class="tab-pane fade in active" id="ups">
                            <div class="row">
                                <div class="col-xs-10">
                                    <div class="">
                                        <table id="tblFinalUpsFilter" class="table table-bordered table-striped"
                                            style="width: 100%; margin-bottom: 0;">
                                            <thead>
                                                <tr>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Código</th>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Descripción
                                                    </th>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Accion
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <div class="col-xs-2">
                                    <div class="text-left" style="margin-top: 10px;">
                                        <button class="btn btn-default" id="btnAddFilters1">
                                            <i class="fa fa-pencil"></i> Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Atencion -->
                        <div class="tab-pane fade" id="atencion">
                            <div class="panel panel-default" style="border-top: 0; border-radius: 0; padding-bottom: 15px;">
                                <div class="panel-body">
                                    <!-- Primera fila -->
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Tipo Atención</label>
                                            <div style="width: 100%; max-width: 400px;">
                                                <select id="tipoAtencionDA" multiple class="form-control input-sm">
                                                    <option></option>
                                                    <option value="1">Consulta Externa</option>
                                                    <option value="2">Emergencia</option>
                                                    <option value="3">Hospitalizacion</option>
                                                    <option value="25">Estrategias Sanitarias</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label>UGIPRESS</label>
                                            <select id="ugipress_DA" class="form-control input-sm"
                                                @if (!$flag_establecimientos) {{ 'disabled' }} @endif>
                                                @foreach ($ugipress as $ipress)
                                                    <option @if (Auth::user()->id_establecimiento == $ipress->id) {{ 'selected' }} @endif
                                                        value="{{ $ipress->id }}">{{ $ipress->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Segunda fila -->
                                    <div class="row" style="margin-top:10px;">
                                        <div class="col-sm-3">
                                            <div class="checkbox" style="margin-top:25px;">
                                                <label>
                                                    <input id="omitirFechaDA" type="checkbox"> Omitir Fecha
                                                </label>
                                            </div>
                                        </div>

                                        <div id="divPeriodoDA" class="col-sm-3" style="display: none;">
                                            <label>Periodo</label>
                                            <div class="input-group input-group-sm">
                                                <input id="periodoInputDA" type="month" class="form-control"
                                                    placeholder="mm/aaaa" disabled>
                                            </div>
                                        </div>

                                        <div id="divFechaIniDA" class="col-sm-3">
                                            <label>Fecha Inicio</label>
                                            <div class="input-group input-group-sm">
                                                <input id="fechaIniInputDA" type="date" class="form-control"
                                                    placeholder="dd/mm/aaaa">
                                            </div>
                                        </div>

                                        <div id="divFechaFinDA" class="col-sm-3">
                                            <label>Fecha Fin</label>
                                            <div class="input-group input-group-sm">
                                                <input id="fechaFinInputDA" type="date" class="form-control"
                                                    placeholder="dd/mm/aaaa">
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Tercera fila -->
                                    <div class="row" style="margin-top:10px;">
                                        <div class="col-sm-2">
                                            <div class="checkbox" style="margin-top:25px;">
                                                <label>
                                                    <input id="seleccionarAtendidosDA" type="checkbox"> Seleccionar
                                                    Atenciones
                                                </label>
                                            </div>
                                        </div>
                                        <div id="divCondicionEstaDA" class="col-sm-4">
                                            <label>Condición Establecimiento</label>
                                            <select id="condicionEstaDA" class="form-control input-sm">
                                                <option selected value="CONTINUADOR">CONTINUADOR</option>
                                                <option value="NUEVO">NUEVO</option>
                                            </select>
                                        </div>

                                        <div id="divCondicionServDA" class="col-sm-4">
                                            <label>Condición Servicio</label>
                                            <select id="condicionServDA" class="form-control input-sm">
                                                <option selected value="CONTINUADOR">CONTINUADOR</option>
                                                <option value="NUEVO">NUEVO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resto de tabs... -->
                        <div class="tab-pane fade" id="tipo">
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-xs-12">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="todosDTA" checked> Todos
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="ipress">
                            <div class="row">

                                <div class="col-xs-10">
                                    <div class="">
                                        <table id="tblFinalIpressFilter" class="table table-bordered table-striped"
                                            style="width: 100%; margin-bottom: 0;">
                                            <thead>
                                                <tr>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Código</th>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Descripción
                                                    </th>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Acciones
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-xs-2">
                                    <div class="text-left" style="margin-top: 10px;">
                                        <button id="btnAddFilters2" class="btn btn-default" style="">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"
                                                style="font-size: 1.5em; vertical-align: middle;"></span>
                                            <span style="vertical-align: middle; margin-left: 5px;">Agregar</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="diagnostico">
                            <div class="row">

                                <div class="col-xs-10">
                                    <div class="table-responsive">
                                        <table id="tblFinalDiagFilter" class="table table-bordered table-striped"
                                            style="width: 100%; margin-bottom: 0; margin-top: 0 !important;">
                                            <thead>
                                                <tr>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Código</th>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Descripción
                                                    </th>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Acciones
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-xs-2">
                                    <div class="text-left" style="margin-top: 10px;">
                                        <button id="btnAddFilters3" class="btn btn-default">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"
                                                style="font-size: 1.5em; vertical-align: middle;"></span>
                                            <span style="vertical-align: middle; margin-left: 5px;">Agregar</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="servicio">
                            <div class="row">
                                <div class="col-xs-10">
                                    <div class="table-responsive">
                                        <table id="tblFinalServFilter" class="table table-bordered table-striped"
                                            style="width: 100%; margin-bottom: 0; margin-top: 0 !important;">
                                            <thead>
                                                <tr>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Código</th>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Descripción
                                                    </th>
                                                    <th style="background-color: #e0e0e0; text-align: center;">Acciones
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-xs-2">
                                    <div class="text-left" style="margin-top: 10px;">
                                        <button id="btnAddFilters4" class="btn btn-default">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"
                                                style="font-size: 1.5em; vertical-align: middle;"></span>
                                            <span style="vertical-align: middle; margin-left: 5px;">Agregar</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <div class="row" style="margin-top: 5px;">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-2"
                                            style="padding: 0; padding-left: 3px;">Situación:</label>
                                        <div class="col-md-10" style="padding: 0;">
                                            <label class="radio-inline">
                                                <input type="radio" name="situacionSER" value="1" checked>
                                                Ingresos
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="situacionSER" value="2"> Egresos
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="responsables">
                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="selectTipoDocumento" class="control-label">Tipo Documento</label>
                                        <select disabled class="form-control" id="selectTipoDocumento">
                                            <option value="">- Seleccionar -</option>
                                            @foreach ($tipo_docs as $tipo_doc)
                                                <option value="{{ $tipo_doc->id }}">{{ $tipo_doc->descripcion }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="tipo_doc_responsable" id="tipo_doc_responsable"
                                            value="">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="inputNumeroDocumento" class="control-label">Número Documento</label>
                                        <input type="text" class="form-control" id="inputNumeroDocumento">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" style="opacity: 0;">&nbsp;</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Nombres y Apellidos"
                                                style="background-color: #f0f0f0;" id="responseDataMedico" disabled>
                                            <input type="hidden" id="medicoId">
                                            <span class="input-group-btn">
                                                <button id="btnBuscarPersonalMedico" class="btn btn-default"
                                                    type="button">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                    Buscar
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="selectColegioProfesional" class="control-label">Colegio
                                            Profesional</label>
                                        <select disabled class="form-control" id="selectColegioProfesional">
                                            <option value="">- Seleccionar -</option>
                                            @foreach ($colegio_profesionales as $colegio_profesional)
                                                <option value="{{ $colegio_profesional->id }}">
                                                    {{ $colegio_profesional->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="selectProfesion" class="control-label">Profesion</label>
                                        <select disabled class="form-control" id="selectProfesion">
                                            <option value="">- Seleccionar -</option>
                                            @foreach ($profesiones as $profesion)
                                                <option value="{{ $profesion->id }}">{{ $profesion->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="selectEspecialidad" class="control-label">Especialidad</label>
                                        <select disabled class="form-control" id="selectEspecialidad">
                                            <option value="">- Seleccionar -</option>
                                            @foreach ($especialidades as $especialidad)
                                                <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="pacientes">
                            <div class="row">

                                <!-- COLUMNA IZQUIERDA -->
                                <div class="col-sm-6">
                                    <div class="panel" style="border: 1px solid #ccc; padding: 15px;">

                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label for="selectTipoDocumentoPaciente" class="control-label">Tipo
                                                        Documento</label>
                                                    <select class="form-control" id="selectTipoDocumentoPaciente">
                                                        <option value="">- Seleccionar -</option>
                                                        @foreach ($tipo_docs as $tipo_doc)
                                                            <option value="{{ $tipo_doc->id }}">
                                                                {{ $tipo_doc->descripcion }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" id="tipoDocumentoPaciente">
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label class="control-label" style="color: #00000000;">a</label>
                                                    <input id="nombresConApellidosPaciente" disabled type="text"
                                                        class="form-control" placeholder="Nombres y Apellidos"
                                                        style="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label for="inputNumeroDocumentoPaciente" class="control-label">Número
                                                        Documento</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                            id="inputNumeroDocumentoPaciente">
                                                        <input type="hidden" id="nroIdAseguradoPaciente">
                                                        <span class="input-group-btn">
                                                            <button id="btnBuscarAsegurado" class="btn btn-default"
                                                                type="button">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label for="selectSexoPaciente" class="control-label">Sexo</label>
                                                    <select class="form-control" id="selectSexoPaciente">
                                                        <option value="">- Seleccionar -</option>
                                                        <option value="MASCULINO">MASCULINO</option>
                                                        <option value="FEMENINO">FEMENINO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label for="inputNumeroCIP" class="control-label">Número CIP</label>
                                                    <input type="text" class="form-control" id="inputNumeroCIP">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-xs-2" id="containerEdadSoloPaciente">
                                                <div class="form-group">
                                                    <label for="edadPaciente" class="control-label">
                                                        <strong>Edad</strong>
                                                    </label>
                                                    <input type="number" id="edadPaciente" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-xs-10" id="">
                                                <div class="form-group row align-items-center">
                                                    <div class="col-xs-4">
                                                        <label class="control-label">Buscar por<br>Rango</label>
                                                        <input class="" type="checkbox" id="checkEdadPaciente">
                                                    </div>
                                                    <div id="containerRangoEdadPaciente" style="display: none">
                                                        <div class="col-xs-3">
                                                            <label class="form-label">Desde</label>
                                                            <input type="number" class="form-control"
                                                                id="fechaDesdePaciente">
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <label class="form-label">Hasta</label>
                                                            <input type="number" class="form-control"
                                                                id="fechaHastaPaciente">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- COLUMNA DERECHA -->
                                <div class="col-sm-6">
                                    <div class="panel" style="border: 1px solid #ccc; padding: 15px;">

                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label for="selectBeneficiario"
                                                        class="control-label">Beneficiario</label>
                                                    <select class="form-control" id="selectBeneficiario">
                                                        <option value="">- Seleccionar -</option>
                                                        @foreach ($tipoBeneficiarios as $tipoBeneficiario)
                                                            <option value="{{ $tipoBeneficiario->id }}">
                                                                {{ $tipoBeneficiario->descripcion }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label for="selectSituacion" class="control-label">Situación</label>
                                                    <select class="form-control" id="selectSituacion">
                                                        <option value="">- Seleccionar -</option>
                                                        @foreach ($situaciones as $situacion)
                                                            <option value="{{ $situacion->id }}">
                                                                {{ $situacion->descripcion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label for="selectGrado" class="control-label">Grado</label>
                                                    <select class="form-control" id="selectGrado">
                                                        <option value="">- Seleccionar -</option>
                                                        @foreach ($grados as $grado)
                                                            <option value="{{ $grado->id }}">{{ $grado->descripcion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-xs-6">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="checkDiscapacidad">
                                                        <strong>Discapacidad</strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="ubigeo">
                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="selectDepartamento" class="control-label">Departamento</label>
                                        <select class="form-control" id="selectDepartamento">
                                            <option selected value="">- Seleccionar -</option>
                                            @foreach ($departamentos as $departamento)
                                                <option value="{{ $departamento->id_departamento }}">
                                                    {{ $departamento->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="selectProvincia" class="control-label">Provincia</label>
                                        <select class="form-control" id="selectProvincia">
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="selectDistrito" class="control-label">Distrito</label>
                                        <select class="form-control" id="selectDistrito">
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="auditoria">
                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="selectTipoDocumentoAud" class="control-label">Tipo Documento</label>
                                        <select disabled class="form-control" id="selectTipoDocumentoAud">
                                            {{-- <option value="">- Seleccionar -</option>
                                            @foreach ($tipo_docs as $tipo_doc)
                                                <option value="{{ $tipo_doc->id }}">
                                                    {{ $tipo_doc->descripcion }}</option>
                                            @endforeach --}}
                                            <option value="DNI" selected>DNI</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputNumeroDocumentoAudi" class="control-label">Número
                                            Documento</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="inputNumeroDocumentoAudi">
                                            <span class="input-group-btn">
                                                <button id="btnBuscarUserAuditoria" class="btn btn-default"
                                                    type="button">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="namesUser" class="control-label" style="color: #00000000">a</label>
                                        <input id="namesUser" type="text" class="form-control"
                                            placeholder="Nombres y Apellidos" style="background-color: #f0f0f0;">
                                        <input type="hidden" id="userId">
                                    </div>
                                </div>

                            </div>

                            <div class="row" style="margin-top: 10px;">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="checkOmitirFechaAudi"> Omitir Fecha
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="containerFechaIniAudi" class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputFechaInicio" class="control-label col-sm-4"
                                            style="text-align: left; padding-left: 0;">Fec. Inicio</label>
                                        <div class="col-sm-8" style="padding-right: 0;">
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="inputFechaInicioAudi"
                                                    placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="containerFechaFinAudi" class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputFechaFin" class="control-label col-sm-3"
                                            style="text-align: left; padding-left: 0;">Fec. Fin</label>
                                        <div class="col-sm-8" style="padding-right: 0;">
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="inputFechaFinAudi"
                                                    placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="" id="global">
                    <div class="row">
                        <!-- Radios a la izquierda -->
                        <div class="col-md-6 text-left">
                            <div {{-- style="display: none;" --}}>
                                <label class="form-control-sm d-block" style="padding-right: 15px;">Mostrar como:</label>
                                <label class="form-check-label">
                                    <input type="radio" name="mostrar" id="mostrarDatos" checked value="d"> Datos
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" name="mostrar" id="mostrarNroReg" value="r"> Nro.
                                    Registros
                                </label>
                            </div>
                        </div>

                        <!-- Botones a la derecha -->
                        <div class="col-md-6 text-right">
                            <button class="btn btn-info" type="button" id="btnBuscarReporte">
                                <i class="fa fa-search" aria-hidden="true"></i> Buscar
                            </button>

                            <button id="btnExportarExcel" class="btn btn-primary" type="button">
                                <i class="fa fa-file-excel-o"></i> Exportar
                            </button>

                            <button id="btnExportarCSV" class="btn btn-warning" type="button">
                                <i class="fa fa-file-text-o"></i> Exportar CSV
                            </button>
                            {{-- <button class="btn btn-default" type="button" id="btnReload">
                                <i class="fa fa-trash-o"></i> Limpiar Filtros
                            </button> --}}
                        </div>
                    </div>
                    <div class="table-responsive" id="containerTableReporteAt">
                        <table id="tblReporteAtenciones" class="table table-condensed table-bordered"
                            style="font-size: 14px !important;">
                            <thead>
                                <tr>
                                    <th>Cod.</th>
                                    <th>Fec. Atencion</th>
                                    <th>Tip. Atencion</th>
                                    <th>IPRESS</th>
                                    <th>UPSS</th>
                                    <th>Servicio</th>
                                    <th>Paciente</th>
                                    <th>Edad</th>
                                    <th>Sexo</th>
                                    <th>Responsable</th>
                                    <th>Estado</th>
                                    <th>Colegio Profesional</th>
                                    <th>1er Diagnostico</th>
                                    <th>Discapacidad</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="containerTotalReporteAt"
                        style="display: none; 
                            text-align: center;
                            width: 100%; 
                            min-height: 80px; 
                            font-size: 18px; 
                            font-weight: bold;
                            margin-top: 20px; 
                            color: #555;">
                        No se encontró información.
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.seis.filters.modal_add_upss')
    @include('frontend.seis.filters.modal_add_ipress')
    @include('frontend.seis.filters.modal_add_diagnostico')
    @include('frontend.seis.filters.modal_add_servicio')
    @include('frontend.seis.filters.modal_add_dependencia')
    @include('frontend.includes.footer')
@endsection
