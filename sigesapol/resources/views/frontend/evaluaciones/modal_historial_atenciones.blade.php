<title>Sistema de Farmacia</title>
<style>
    /*****************/
    .modal-dialog {
        width: 90%;
    }

    #tablemodal {
        border-spacing: 0;
        display: flex;
        /*Se ajuste dinamicamente al tamano del dispositivo**/
        max-height: 80vh;
        /*El alto que necesitemos**/
        overflow-y: auto;
        /**El scroll verticalmente cuando sea necesario*/
        overflow-x: hidden;
        /*Sin scroll horizontal*/
        table-layout: fixed;
        /**Forzamos a que las filas tenga el mismo ancho**/
        width: 98vw;
        /*El ancho que necesitemos*/
        border: 1px solid #c4c0c9;
    }

    #tablemodal thead {
        background-color: #e2e3e5;
        position: fixed !important;
    }

    #tablemodal th {
        border-bottom: 1px solid #c4c0c9;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal th {
        font-weight: normal;
        margin: 0;
        max-width: 9.5vw;
        min-width: 9.5vw;
        word-wrap: break-word;
        font-size: 10px;
        font-weight: bold;
        height: 3.5vh !important;
        line-height: 12px;
        vertical-align: middle;
        /*height:20px;*/
        padding: 4px;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal td {
        font-weight: normal;
        margin: 0;
        max-width: 9.5vw;
        min-width: 9.5vw;
        word-wrap: break-word;
        font-size: 11px;
        height: 3.5vh !important;
        padding: 4px;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal tbody tr:hover td,
    #tablemodal tbody tr:hover th {
        /*background-color: red!important;*/
        font-weight: bold;
        /*mix-blend-mode: difference;*/

    }

    #tablemodalm {}
</style>

<script type="text/javascript">
    $(document).ready(function() {

    });

    function ver_det_atencion_evaluacion(id, id_tipo_atencion) {
        $('#nrorecmodal').text(id);
        let ruta = "";
        if (id_tipo_atencion == 2) {
            ruta = "/mostrar_atencion_emergencia/";
        } else if (id_tipo_atencion == 3) {
            ruta = "/mostrar_atencion_hospitalizacion/";
        }
        $.ajax({
            url: ruta + id,
            async: false,
            success: function(result) {
                $("#muestra-det-atencion").html(result);
                $("#myModalAtencionEvaluacion").modal();
            },
            complete: function() {
            },
            error: function (xhr, status, err) {
                const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message  : "Ocurrio un error, intentelo en unos minutos";
                Swal.fire({
                    icon: 'error',
                    title: "HISTORIAL DE ATENCIONES MEDICAS",
                    html: `<p style="font-size: 13px">${message}</p>`,
                    showConfirmButton: false,
                    timer: 3000,
                });
            }
        });
    }

    function ver_det_atencion_evaluacion_ajax(id_atencion, id_tipo_atencion) {
        if (id_tipo_atencion == 2 && $("#modalAtencionAjax").val() == "1") {
            Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
            });
            $.ajax({
                url: "/cargarDatosPrestacionEmergencia/" + id_atencion,
                success: function(result) {
                    const data_triaje = JSON.parse(result.dato_triaje);
                    let peso_ideal = null;
                    let imc = null;
                    let peso_ideal_PREST = null;
                    let imc_PREST = null;
                    if (data_triaje.talla_cm != "") {
                        if (parseFloat(data_triaje.talla_cm) > 0) {
                            peso_ideal = 50 + ((parseFloat(data_triaje.talla_cm) - 150) / 4) * 3 + (
                                parseInt(result.prestacion.edadpaciente) - 20) / 4;
                            if (result.prestacion.sexo == "M") {
                                peso_ideal = peso_ideal * 0.9;
                            }
                        } else {
                            peso_ideal = "";
                        }
                    } else {
                        peso_ideal = "";
                    }

                    if (data_triaje.peso_kg != "" && data_triaje.talla_cm != "") {
                        if (parseFloat(data_triaje.peso_kg) > 0 && parseFloat(data_triaje.talla_cm) > 0) {
                            let talla_en_metros = data_triaje.talla_cm * 0.01;
                            imc = data_triaje.peso_kg / (talla_en_metros * talla_en_metros);
                            imc = imc.toFixed(2);
                        }
                    } else {
                        imc = '';
                    }

                    if (result.prestacion.talla_cm != "") {
                        if (parseFloat(result.prestacion.talla_cm) > 0) {
                            peso_ideal_PREST = 50 + ((parseFloat(result.prestacion.talla_cm) - 150) / 4) *
                                3 + (parseInt(result.prestacion.edadpaciente) - 20) / 4;
                            if (result.prestacion.sexo == "M") {
                                peso_ideal_PREST = peso_ideal_PREST * 0.9;
                            }
                        } else {
                            peso_ideal_PREST = "";
                        }
                    } else {
                        peso_ideal_PREST = "";
                    }

                    if (result.prestacion.peso_kg != "" && result.prestacion.talla_cm != "") {
                        if (parseFloat(result.prestacion.peso_kg) > 0 && parseFloat(result.prestacion
                                .talla_cm) > 0) {
                            let talla_en_metros = result.prestacion.talla_cm * 0.01;
                            imc_PREST = result.prestacion.peso_kg / (talla_en_metros * talla_en_metros);
                            imc_PREST = imc_PREST.toFixed(2);
                        }
                    } else {
                        imc_PREST = '';
                    }
                    Swal.fire({
                        icon: "success",
                        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                        confirmButtonText: "ENTENDIDO",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: "ENTENDIDO",
                        timer: 1200,
                        timerProgressBar: true,
                    });
                    $("#myModalAtencionEvaluacionAjax").modal('show');

                    $("#prest_tipoAtencion").html(result.prestacion.tipo_atencion);
                    $("#prest_fechaAtencion").html(result.prestacion.fecha_atencion);
                    $("#prest_nroHistoria").html(result.prestacion.nro_historia);
                    $("#prest_servicio").html(result.prestacion.servicio);
                    $("#prest_consultorio").html(result.prestacion.consultorio);
                    $("#prest_establecimiento").html(result.prestacion.establecimiento);
                    $("#prest_dni").html(result.prestacion.dni);
                    $("#prest_colegiatura").html(result.prestacion.colegiatura);
                    $("#prest_rne").html(result.prestacion.rne);
                    $("#prest_profesion").html(result.prestacion.profesion);
                    $("#prest_medico_paterno").html(result.prestacion.medico_paterno);
                    $("#prest_medico_materno").html(result.prestacion.medico_materno);
                    $("#prest_medico_nombre").html(result.prestacion.medico_nombre);
                    $("#prest_especializacion").html(result.prestacion.especializacion);

                    $("#dt_temperatura").html(data_triaje.temperatura);
                    $("#dt_talla_cm").html(data_triaje.talla_cm);
                    $("#dt_peso_kg").html(data_triaje.peso_kg);
                    $("#dt_sa02_porcentaje").html(data_triaje.sa02_porcentaje);
                    $("#dt_tamano_cintura_cm").html(data_triaje.tamano_cintura_cm);
                    $("#dt_frecuencia_cardiaca_fetal").html(data_triaje.frecuencia_cardiaca_fetal);
                    $("#dt_presion_arterial_x").html(data_triaje.presion_arterial_x);
                    $("#dt_presion_arterial_y").html(data_triaje.presion_arterial_y);
                    $("#dt_frecuencia_respiratoria").html(data_triaje.frecuencia_respiratoria);
                    $("#dt_frecuencia_cardiaca").html(data_triaje.frecuencia_cardiaca);
                    $("#dt_fi02").html(data_triaje.fi02);
                    $("#dt_peso_ideal").html(peso_ideal);
                    $("#dt_imc").html(imc);

                    $("#prest_antecedente").html(result.prestacion.antecedente);
                    $("#prest_antecedente_familiar").html(result.prestacion.antecedente_familiar);
                    $("#prest_motivo_visita").html(result.prestacion.motivo_visita);
                    $("#prest_anamnesis").html(result.prestacion.anamnesis);
                    $("#prest_examen_auxiliar").html(result.prestacion.examen_auxiliar);
                    $("#prest_examen_fisico").html(result.prestacion.examen_fisico);
                    $("#prest_examen_clinico").html(result.prestacion.examen_clinico);
                    $("#prest_tratamiento_auxiliar").html(result.prestacion.tratamiento_auxiliar);
                    $("#prest_plan_trabajo").html(result.prestacion.plan_trabajo);

                    $("#prest_temperatura").html(result.prestacion.temperatura);
                    $("#prest_talla_cm").html(result.prestacion.talla_cm);
                    $("#prest_peso_kg").html(result.prestacion.peso_kg);
                    $("#prest_imc").html(result.prestacion.imc);
                    $("#prest_peso_ideal").html(result.prestacion.peso_ideal);
                    $("#prest_sa02_porcentaje").html(result.prestacion.sa02_porcentaje);
                    $("#prest_tamano_cintura_cm").html(result.prestacion.tamano_cintura_cm);
                    $("#prest_frecuencia_cardiaca_fetal").html(result.prestacion.frecuencia_cardiaca_fetal);
                    $("#prest_presion_arterial_x").html(result.prestacion.presion_arterial_x);
                    $("#prest_presion_arterial_y").html(result.prestacion.presion_arterial_y);
                    $("#prest_frecuencia_respiratoria").html(result.prestacion.frecuencia_respiratoria);
                    $("#prest_frecuencia_cardiaca").html(result.prestacion.frecuencia_cardiaca);
                    $("#prest_fi02").html(result.prestacion.fi02);

                    $("#prest_peso_ideal").html(peso_ideal_PREST);
                    $("#prest_imc").html(imc_PREST);
                    $('#tableDiagAtencion').html('');
                    $('#tableMedicamentosRecet').html('');
                    $('#tableProcAtencion').html('');
                    $.each(result.diagnostico, function(index, item) {
                        const fila1 = `<tr>
                            <td>${item.codigo}</td>
                            <td>${item.nombre}</td>
                            <td>${item.id_tipo_diagnostico == "1" ? "PRESUNTIVO" : item.id_tipo_diagnostico == "2" ? "DEFINITIVO" : "REITERATIVO"}</td>
                        </tr>`;
                        $('#tableDiagAtencion').append(fila1);
                    });
                /*    $.each(result.procedimiento, function(index, item) {
                        const fila = `<tr>
                            <td>${item.codigo}</td>
                            <td>${item.nombre}</td>
                            <td class="text-center">${item.cantidad}</td>
                        </tr>`;
                        $('#tableProcAtencion').append(fila);
                    });*/

                    $.each(result.medicamentos, function(index, item) {
                        const fila2 = `<tr>
                            <td>${item.codigo}</td>
                            <td>${item.nombre}</td>
                            <td>${item.abreviatura}</td>
                            <td>${item.tiempo_periodo}</td>
                            <td>${item.posologia}</td>
                            <td>${item.cantidad_prescrita}</td>
                        </tr>`;
                        $('#tableMedicamentosRecet').append(fila2);
                    });
                    $.each(result.procedimiento, function(index, item) {
                        const fila3 = `<tr>
                            <td>${item.codigo}</td>
                            <td>${item.nombre}</td>
                            <td class="text-center">${item.cantidad}</td>
                        </tr>`;
                        $('#tableProcAtencion').append(fila3);
                    });

                    if (result.sw) {
                        $("#swHandler").attr("hidden", false);
                        $("#datoAdicional_pabellon").html(result.datoadicional.pabellon);
                        $("#datoAdicional_piso").html(result.datoadicional.piso);
                        $("#datoAdicional_cuarto").html(result.datoadicional.cuarto);
                        $("#datoAdicional_cama").html(result.datoadicional.cama);
                    } else {
                        $("#swHandler").attr("hidden", true);
                    }

                    result.receta.forEach(rowReceta => {
                        if (rowReceta.estado == 1) {
                            $("#recetaEstaDispensada").attr("hidden", false);
                            $("#recetaNoEstaDispensada").attr("hidden", true);
                            $("#recet_fecha_expedicion").html(rowReceta.fecha_expedicion);
                        } else {
                            $("#recetaEstaDispensada").attr("hidden", true);
                            $("#recetaNoEstaDispensada").attr("hidden", false);
                        }
                        const fecha = new Date(rowReceta.fecha_registro);
                        const formato =
                            `${fecha.getDate().toString().padStart(2, '0')}-${(fecha.getMonth() + 1).toString().padStart(2, '0')}-${fecha.getFullYear()}`;
                        $("#recet_nro_receta").html(rowReceta.nro_receta);
                        $("#recet_fecha_expedicion").html(formato);
                        $("#recet_farmacia").html(rowReceta.farmacia);
                    });
                },
            });
        } else if (id_tipo_atencion == 2 || id_tipo_atencion == 3) {
            ver_det_atencion_evaluacion(id_atencion, id_tipo_atencion);
        } else if (id_tipo_atencion == 3 && $("#modalAtencionAjax").val() == "1") {
            Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
            });
            $.ajax({
                url: "/cargarDatosPrestacionHospitalizacion/" + id_atencion,
                success: function(result) {
                    Swal.fire({
                        icon: "success",
                        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                        confirmButtonText: "ENTENDIDO",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: "ENTENDIDO",
                        timer: 1200,
                        timerProgressBar: true,
                    });
                    $("#modalAtencionHospiAjax").html(result);
                    $("#myModalAtencionEvaluacionAjax").modal("show");
                },
            });
        }
    }

    function generarCodigoSiteds(emergencia) {
        const resultValidate = isValidDateAccreditation(emergencia.dia);
        if (resultValidate) {
            Swal.fire({
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>GENERANDO C?DIGO SITEDS</span></div>",
                html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            })
            const body = {
                "idCorrelativo": emergencia.id_emergencia,
                "aseguradoId": emergencia.id_asegurado,
                "establecimientoId": emergencia.id_establecimiento,
                "tipoAtencionId": "2",
                "servicioId": emergencia.id_servicio
            };
            $.ajax({
                url: '/v2/ws-dirsapol-siteds',
                type: 'POST',
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify(body),
                success: (result, textStatus, jqXHR) => {
                    if (result && result.nuAutorizacion && (parseInt(jqXHR.status) === 200 || parseInt(jqXHR
                            .status) === 201)) {
                        updateCodeSiteds(emergencia.id_emergencia, result.nuAutorizacion, result.message);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>?OPERACI?N NO EXITOSA!</span></div>",
                            html: `<div style='margin: 0px 0px; padding: 0px 20px'>
                                    <span style='color: #000000; font-size: 18px'>Servicio no disponible. Por favor, int?ntelo m?s tarde o utilice el sistema web de SUSALUD para regularizar el SITEDS manualmente. <a href="https://app8.susalud.gob.pe:8080/wb-siteds/login.htm" target='_blank'>SITEDS WEB</a></span>
                                    </div>`,
                            confirmButtonText: 'ENTENDIDO',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON
                        .message : "Servicio no disponible. intentelo en un minutos.";
                    Swal.fire({
                        icon: 'error',
                        title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                        html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                        confirmButtonText: 'ENTENDIDO',
                    });
                },
                complete: function(xhr, status) {
                    Swal.hideLoading();
                }
            })
        }
    }

    function updateCodeSiteds(id_emergencia, nuAutorizacion, message) {
        Swal.fire({
            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR C?DIGO SITEDS</span></div>",
            html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Esper? un momento, por favor...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onOpen: () => {
                Swal.showLoading();
            }
        })
        const auth = {!! json_encode(Auth::user()) !!};
        const body = {
            cod_siteds_susalud: nuAutorizacion,
            updated_us_cod_siteds: auth.id
        };
        $.ajax({
            url: `/emergencia/${id_emergencia}/update-code-siteds`,
            type: 'PUT',
            data: body,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR C?DIGO SITEDS</span></div>",
                    html: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>${message || "C?digo SITEDS actualizado correctamente"}</span></div>`,
                    confirmButtonText: 'ENTENDIDO',
                });
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {},
            complete: function(xhr, status) {
                Swal.hideLoading();
            }
        });
    }

    function isValidDateAccreditation(date) {
        const dateMoment = moment(date, 'YYYY-MM-DD').startOf('day');
        const currentDate = moment().startOf('day');
        if (currentDate.isAfter(dateMoment)) {
            Swal.fire({
                icon: 'warning',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>C?DIGO SITEDS</span></div>",
                html: `<div style='padding: 20px'><span style='color: #000000; font-size: 18px'>Lamentamos informarle que el plazo para la acreditaci?n del paciente ha expirado. La fecha m?xima para completar la acreditaci?n era ${dateMoment.format('DD-MM-YYYY')}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
            return false;
        }
        if (currentDate.isBefore(dateMoment)) {
            Swal.fire({
                icon: 'warning',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>C?DIGO SITEDS</span></div>",
                html: `<div style='padding: 20px'><span style='color: #000000; font-size: 18px'>La cita solo puede ser acreditada el d?a de la atenci?n, programada para el ${dateMoment.format('DD-MM-YYYY')}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
            return false;
        }
        return true;
    }

    function regularizeCodeSiteds(emergencia) {
        Swal.fire({
            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>GENERANDO C�DIGO SITEDS</span></div>",
            html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => Swal.showLoading()
        })
        const body = {
            "recordId": emergencia.id_emergencia,
            "moduleId": 3,
            "dateAttention": emergencia.dia,
            "establecimientoId": emergencia.id_establecimiento,
            "tipoAtencionId": "2",
            "servicioId": emergencia.id_servicio,
        };
        $.ajax({
            type: "POST",
            url: "/regularize-multiple-siteds-temporary",
            data: JSON.stringify(body),
            contentType: "application/json",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const messageSuccess = response.message ||
                    "�La regularizaci�n de los c�digos SITEDS temporales se complet� con �xito!"
                Swal.fire({
                    icon: 'success',
                    title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>�OPERACI�N NO EXITOSA!</span></div>",
                    html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageSuccess}</span></div>`,
                    confirmButtonText: 'ENTENDIDO',
                });
                modalHistorial();
                datatablenew();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                const messageError = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON
                    .message : "Servicio no disponible. intentelo en un minutos.";
                Swal.fire({
                    icon: 'error',
                    title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                    html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                    confirmButtonText: 'ENTENDIDO',
                });
            },
            complete: function(jqXHR, status) {
                Swal.hideLoading();
            }
        });
    }
</script>

<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <br>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="tablemodalm">
                                    <thead>
                                        <tr>
                                            <th>FECHA</th>
                                            <th>HORA</th>
                                            <th>ESTABLECIMIENTO</th>
                                            <th>TIPO ATENCION</th>
                                            <th>SERVICIO</th>
                                            @if ($id_tipo_atencion != 3)
                                                <th>CONSULTORIO</th>
                                            @endif
                                            <th>MEDICO</th>
                                            <th class="text-center">VER</th>
                                            <th class="text-center">SITEDS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="muestra-atenciones" style="padding-top:32px">
                                        <?php foreach($atenciones as $rowHorario):?>
                                        <tr>
                                            <td><?php echo $rowHorario->dia; ?></td>
                                            <td><?php echo $rowHorario->hora; ?></td>
                                            <td><?php echo $rowHorario->establecimiento; ?></td>
                                            <td><?php echo $rowHorario->tipo_atencion; ?></td>
                                            <td><?php echo $rowHorario->servicio; ?></td>
                                            @if ($id_tipo_atencion != 3)
                                                <td><?php echo $rowHorario->consultorio; ?></td>
                                            @endif
                                            <td><?php echo $rowHorario->medico_nombre . ' ' . $rowHorario->medico_paterno . ' ' . $rowHorario->medico_materno; ?></td>
                                            <td class="text-center">
                                                <?php 
                                          if ( $rowHorario->id_estado_reg != 0) {
                                          ?>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true"
                                                    title="<b>Ver Atencion</b>">
                                                    <a href="javascript:void(0)"
                                                        onClick="ver_det_atencion_evaluacion_ajax({{ $rowHorario->id }},{{ $id_tipo_atencion }})"
                                                        class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                                                </div>
                                                <?php
                                          } else {
                                          ?>
                                                <button class="btn btn-success btn-xs" disabled=""><i
                                                        class="fa fa-eye"></i></button>
                                                <?php 
                                          }
                                          ?>
                                            </td>
                                            <td style="text-align: center">
                                                @if (isset($rowHorario->cod_siteds_susalud))
                                                    @if (substr($rowHorario->cod_siteds_susalud, -1) === 'T' || strlen($rowHorario->cod_siteds_susalud) <= 6)
                                                        <p><span class="label label-warning"
                                                                style="font-weight: lighter">{{ $rowHorario->cod_siteds_susalud }}</span>
                                                        </p>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true"
                                                            title="<b>Regularizar</b>">
                                                            <button class="btn btn-success btn-xs"
                                                                onclick="regularizeCodeSiteds({{ json_encode($rowHorario) }})"><i
                                                                    class="fa fa-check-circle-o"></i></button>
                                                        </div>
                                                    @else
                                                        <span class="label label-success"
                                                            style="font-weight: lighter">{{ $rowHorario->cod_siteds_susalud }}</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <div class="modal fade" id="myModalAtencionEvaluacion" role="dialog"
        style="z-index: 99999999999999999999 !important;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">DETALLE DE ATENCION. <span id="nrorecmodal"></span></h5>
                </div>
                <div class="modal-body">
                    <div id="muestra-det-atencion"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>
