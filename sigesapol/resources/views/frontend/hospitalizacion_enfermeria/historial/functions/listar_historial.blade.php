<script>
    /* $('#modalHistorialEnfermeria').on('show.bs.modal', function(e) {
        const button = $(e.relatedTarget);
        const idAsegurado = button.data('id_asegurado');
        const id_ext = button.data('id_ext');
        const dni_beneficiario = button.data('dni_beneficiario');
        getHistory(idAsegurado);
        modalHistorial(id_ext);
        modalLaboratorio(dni_beneficiario);
        modalRadiologico(dni_beneficiario);
        modalInterconsulta(dni_beneficiario);
    }); */


    $(document).on('click', '.btn-mostrarModalHistorial', async function() {

        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const button = $(this);
        const idAsegurado = button.data('id_asegurado');
        const id_ext = button.data('id_ext');
        const dni_beneficiario = button.data('dni_beneficiario');

        try {
            await Promise.all([
                getHistory(idAsegurado),
                modalHistorial(id_ext),
                modalLaboratorio(dni_beneficiario),
                modalRadiologico(dni_beneficiario),
                modalInterconsulta(dni_beneficiario)
            ]);

            Swal.close();
            $('#modalHistorialEnfermeria').modal('show');

        } catch (error) {
            Swal.fire('Error', 'No se pudo cargar', 'error');
        }

    });

    /* function modalHistorial(id_principal) {
        $.ajax({
            url: "../modal_historial_evaluaciones_atenciones/" + id_principal + '/' + 3,
            type: "GET",
            success: function(result) {
                $("#divHistorial").html(result);
                $('#tablemodal1').DataTable({
                    "sPaginationType": "full_numbers",

                    "language": {
                        "url": "/js/Spanish.json"
                    },
                });
            }
        });
    } */

    /* function modalInterconsulta(dni_beneficiario) {

        $.ajax({
            url: "../modal_historial_interconsulta_enf/" + dni_beneficiario,
            type: "GET",
            success: function(result) {
                $("#divInterconsultaH").html(result);
                $('#tablemodalm4').DataTable({
                    "sPaginationType": "full_numbers",

                    "language": {
                        "url": "/js/Spanish.json"
                    },
                });
            }
        });
    } */

    /* function modalLaboratorio(dni_beneficiario) {

        $.ajax({
            url: "../modal_historial_laboratorio/" + dni_beneficiario,
            type: "GET",
            success: function(result) {
                $("#divLaboratorio").html(result);
                $('#tablemodalm1').DataTable({
                    "sPaginationType": "full_numbers",

                    "language": {
                        "url": "/js/Spanish.json"
                    },
                });
            }
        });

    } */

    /* function modalRadiologico(dni_beneficiario) {

        $.ajax({
            url: "../modal_historial_radiologia/" + dni_beneficiario,
            type: "GET",
            success: function(result) {
                $("#divRadiologico").html(result);
                $('#tablemodalm2').DataTable({
                    "sPaginationType": "full_numbers",

                    "language": {
                        "url": "/js/Spanish.json"
                    },
                });
            }
        });

    } */

    /* function getHistory(id_asegurado) {
        const renderTableHistory = $('#tableHistoryEnfermeriaEmer').DataTable();
        if ($.fn.DataTable.isDataTable('#tableHistoryEnfermeriaEmer')) {
            $('#tableHistoryEnfermeriaEmer').DataTable().destroy();
            $('#tableHistoryEnfermeriaEmer tbody').empty();
        }
        $('#tableHistoryEnfermeriaEmer').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            ordering: false,
            retrieve: false,
            destroy: true,
            resetPaging: false,
            language: {
                "processing": "Buscando historial...",
                "info": "",
                "infoEmpty": "",
                "infoFiltered": "",
            },
            ajax: {
                url: `/get-atenciones-idasegurado/${id_asegurado}`,
                type: 'GET',
                dataSrc: function(json) {
                    console.log("[json] => ", json);
                    $("#panelInfoPacienteHistory").slideUp();
                    $("#mh_nro_doc_ident").val("");
                    $("#mh_nombre").val("");
                    $("#mh_apellidos").val("");
                    $("#mh_sexo").val("");
                    // $("#mh_edad").val("");
                    if (json && json.length > 0) {
                        $("#panelInfoPacienteHistory").slideDown();
                        const firsItem = json[0];
                        $("#mh_nro_doc_ident").val(firsItem.nro_doc_ident || "");
                        $("#mh_nombre").val(firsItem.asegurado_nombre || "");
                        $("#mh_apellidos").val(
                            `${firsItem.asegurado_paterno || ""} ${firsItem.asegurado_materno || ""}`);
                        $("#mh_sexo").val(firsItem.asegurado_sexo === "M" ? "MASCULINO" : "FEMENINO" || "");
                        // $("#mh_edad").val(firsItem.asegurado_edad);
                    }
                    return json;
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", status, error);
                }
            },
            columns: [{
                    data: 'id',
                    className: 'text-center dt-center'
                },
                {
                    data: 'tipo_atencion',
                    className: 'text-center dt-center'
                },
                {
                    data: null,
                    className: 'dt-center',
                    render: function(data, type, row) {
                        return `<span>${moment(row.enfermeria_fecha_ingreso).format("DD/MM/YYYY")}</span>`;
                    },
                },
                {
                    data: 'hora',
                    className: 'text-center dt-center'
                },
                {
                    data: null,
                    className: 'dt-center',
                    render: function(data, type, row) {
                        return `<div>
                            <span><b>NOMBRE:<b/> ${row.medico_nombre || ""}</span><br>
                            <span><b>APELLIDOS:</b> ${row.medico_nombre || "" } ${row.medico_paterno || ""}</span>
                          </div>`;
                    },
                },
                {
                    data: 'servicio',
                    className: 'text-center dt-center',
                },
            ]
        });
    } */

    function modalHistorial(id_principal) {
        return $.ajax({
            url: "../modal_historial_evaluaciones_atenciones/" + id_principal + '/' + 3,
            type: "GET"
        }).then(function(result) {
            $("#divHistorial").html(result);
            $('#tablemodal1').DataTable({
                "sPaginationType": "full_numbers",
                "language": {
                    "url": "/js/Spanish.json"
                },
            });
        });
    }

    function modalInterconsulta(dni_beneficiario) {
        return $.ajax({
            url: "../modal_historial_interconsulta_enf/" + dni_beneficiario,
            type: "GET"
        }).then(function(result) {
            $("#divInterconsultaH").html(result);
            $('#tablemodalm4').DataTable({
                "sPaginationType": "full_numbers",
                "language": {
                    "url": "/js/Spanish.json"
                },
            });
        });
    }

    function modalLaboratorio(dni_beneficiario) {
        return $.ajax({
            url: "../modal_historial_laboratorio/" + dni_beneficiario,
            type: "GET"
        }).then(function(result) {
            $("#divLaboratorio").html(result);
            $('#tablemodalm1').DataTable({
                "sPaginationType": "full_numbers",
                "language": {
                    "url": "/js/Spanish.json"
                },
            });
        });
    }

    function modalRadiologico(dni_beneficiario) {
        return $.ajax({
            url: "../modal_historial_radiologia/" + dni_beneficiario,
            type: "GET"
        }).then(function(result) {
            $("#divRadiologico").html(result);
            $('#tablemodalm2').DataTable({
                "sPaginationType": "full_numbers",
                "language": {
                    "url": "/js/Spanish.json"
                },
            });
        });
    }

    function getHistory(id_asegurado) {
        return new Promise((resolve, reject) => {

            if ($.fn.DataTable.isDataTable('#tableHistoryEnfermeriaEmer')) {
                $('#tableHistoryEnfermeriaEmer').DataTable().destroy();
                $('#tableHistoryEnfermeriaEmer tbody').empty();
            }

            $('#tableHistoryEnfermeriaEmer').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                paging: false,
                ordering: false,
                destroy: true,
                language: {
                    "processing": "Buscando historial...",
                    "info": "",
                    "infoEmpty": "",
                    "infoFiltered": "",
                },
                ajax: {
                    url: `/get-atenciones-idasegurado/${id_asegurado}`,
                    type: 'GET',
                    dataSrc: function(json) {

                        $("#panelInfoPacienteHistory").slideUp();
                        $("#mh_nro_doc_ident").val("");
                        $("#mh_nombre").val("");
                        $("#mh_apellidos").val("");
                        $("#mh_sexo").val("");
                        // $("#mh_edad").val("");
                        if (json && json.length > 0) {
                            $("#panelInfoPacienteHistory").slideDown();
                            const firsItem = json[0];
                            $("#mh_nro_doc_ident").val(firsItem.nro_doc_ident || "");
                            $("#mh_nombre").val(firsItem.asegurado_nombre || "");
                            $("#mh_apellidos").val(
                                `${firsItem.asegurado_paterno || ""} ${firsItem.asegurado_materno || ""}`
                                );
                            $("#mh_sexo").val(firsItem.asegurado_sexo === "M" ? "MASCULINO" :
                                "FEMENINO" || "");
                            // $("#mh_edad").val(firsItem.asegurado_edad);
                        }

                        resolve();
                        return json;
                    },
                    error: function() {
                        reject();
                    }
                },
                columns: [{
                        data: 'id',
                        className: 'text-center dt-center'
                    },
                    {
                        data: 'tipo_atencion',
                        className: 'text-center dt-center'
                    },
                    {
                        data: null,
                        className: 'dt-center',
                        render: function(data, type, row) {
                            return `<span>${moment(row.enfermeria_fecha_ingreso).format("DD/MM/YYYY")}</span>`;
                        },
                    },
                    {
                        data: 'hora',
                        className: 'text-center dt-center'
                    },
                    {
                        data: null,
                        className: 'dt-center',
                        render: function(data, type, row) {
                            return `<div>
                            <span><b>NOMBRE:<b/> ${row.medico_nombre || ""}</span><br>
                            <span><b>APELLIDOS:</b> ${row.medico_nombre || "" } ${row.medico_paterno || ""}</span>
                          </div>`;
                        },
                    },
                    {
                        data: 'servicio',
                        className: 'text-center dt-center',
                    },
                ]
            });

        });
    }
</script>
