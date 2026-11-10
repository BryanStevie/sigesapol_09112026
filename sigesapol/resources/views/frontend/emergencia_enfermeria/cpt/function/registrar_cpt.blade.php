<script>
    $(document).ready(function() {});

    $('#modalRegistrarCpt').on('hidden.bs.modal', function() {
        $('#num_documento_cpt_n').html("");
        $('#nom_paciente_cpt_n').html("");
        $('#tipo_sangre_cpt_n').html("");
        $('#edad_cpt_n').html("");
        $('#sexo_cpt_n').html("");
        $('#hc_cpt_n').html("");
        $('#parentesgo_cpt_n').html("");
        $('#tipo_sangre_cpt_n').html("");
        $('#alergia_si_cpt_reg').hide();
        $('#alergia_no_cpt_reg').hide();
    });

    function isTimeInRangeCpt(time, startTime, endTime) {
        const timeToCheck = new Date(`1970-01-01T${time}:00`);
        const start = new Date(`1970-01-01T${startTime}:00`);
        const end = new Date(`1970-01-01T${endTime}:00`);

        // Manejar el caso en que el rango de tiempo cruza la medianoche
        if (end < start) {
            return (timeToCheck >= start && timeToCheck < new Date('1970-01-02T00:00:00')) ||
                (timeToCheck >= new Date('1970-01-01T00:00:00') && timeToCheck <= end);
        } else {
            return timeToCheck >= start && timeToCheck <= end;
        }
    }

    let diagnosticosCptCurr = null;

    $('body').on('click', '#crearCpt', function() {
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
        $('#diagnosticos_nuevo_cpt').html('');
        $("#diagnosticos_nuevo_cpt_prest").html('');
        var id_enfermeria = $('#id_enfermeria_cpt').val();
        var id_ext = $('#id_ext_cpt').val();
        var id_asegurado = $('#id_asegurado_cpt').val();
        cargarDatosGlobalesCpt(id_enfermeria, id_asegurado, 2,
            id_ext);
        $("#registrarCpt").attr("id", "registrarCpt");
        $("#registrarCpt").attr("disabled", false);
        $('#FormularioCpt').trigger("reset");
        $('#modalRegistrarCpt').modal('show');

        $("#add_DIAGNOSTICO_ENFERMERIA").show();
        $("#add_PLAN_CUIDADO_ENFERMERIA").show();
        $("#add_INTERVENCIONES_ENFERMERIA").show();
        $("#add_EVALUACIONES_ENFERMERIA").show();
        $("#add_CPMS_ENFERMERIA").show();

        var id_tipo_atenciones_listado = $('#id_tipo_atenciones_listado_cpt').val();
        var nombre_tipo_atencion = $('#nombre_tipo_atencion_cpt').val();
        var prioridad = $('#prioridad_cpt').val();

        $('#nombre_tipo_atencion_registro_cpt_n').val("EMERGENCIA");
        $('#prioridad_registro_cpt_n').val(prioridad);
        $('#id_tipo_atenciones_registro_cpt_reg').val(2);
        $("#fecha_registro_evoluciones_inicial").val('{{ \Carbon\Carbon::now()->format('d/m/Y') }}');

        $("#tbl_DIAGNOSTICO_ENFERMERIA tbody").empty();
        $("#tbl_INTERVENCIONES_ENFERMERIA tbody").empty();
        $("#tbl_PLAN_CUIDADO_ENFERMERIA tbody").empty();
        $("#tbl_EVALUACIONES_ENFERMERIA tbody").empty();
        $("#tbl_CPMS_ENFERMERIA tbody").empty();

        $('#id_enfermeria_cpt_reg').val(id_enfermeria);
        $('#id_asegurado2_cpt_reg').val(id_asegurado);
        $('#id_ext_cpt_reg').val(id_ext);
    });

    $('#add_CPMS_ENFERMERIA_cpt').on('click', function() {
        lista_table_tr_campo_detalle(
            'tbl_CPMS_ENFERMERIA',
            'descripcion_CPMS_ENFERMERIA_NUEVO',
            'descripcion_detalle_CPMS_ENFERMERIA',
            'id_relacionado_CPMS_ENFERMERIA',
            'boton_eliminar_item_CPMS_ENFERMERIA',
            'null',
            'sin_elemento_adicional',
            'null',
            'null',
            'SI',
            'SI',
            'procedimiento_cantidad',
            'procedimiento_tiempo',
            diagnosticosCptCurr,
            $("#nivel_ipress").val()
        );
        var id_prestacion_listado = $('#id_prestacion_listado_cpt').val();
    });

    delete_table_tr_campo('tbl_CPMS_ENFERMERIA', 'boton_eliminar_item_CPMS_ENFERMERIA', '');
    $('#add_EVALUACIONES_ENFERMERIA').on('click', function() {
        copiar_datos_otra_table();
        delete_table_tr_campo('tbl_DIAGNOSTICO_ENFERMERIA', 'boton_eliminar_item_DIAGNOSTICO_ENFERMERIA', '');
        delete_table_tr_campo('tbl_INTERVENCIONES_ENFERMERIA', 'boton_eliminar_item_INTERVENCIONES_ENFERMERIA',
            '');
        delete_table_tr_campo('tbl_PLAN_CUIDADO_ENFERMERIA', 'boton_eliminar_item_PLAN_CUIDADO_ENFERMERIA',
            'copiar_tabla_datos');
        delete_table_tr_campo('tbl_EVALUACIONES_ENFERMERIA', 'boton_eliminar_item_EVALUACIONES_ENFERMERIA', '');
        delete_table_tr_campo('tbl_CPMS_ENFERMERIA', 'boton_eliminar_item_CPMS_ENFERMERIA', '');
    });

    $(document).ready(function() {
        $('#registrarCpt').click(function(e) {
            e.preventDefault();
            var descripcion_turno = $('#turnos_nuevo_cpt').val();
            let tbl_CPMS_ENFERMERIA = $('#tbl_CPMS_ENFERMERIA tbody tr').length;
            var tipo_registro = $('#tipo_rgs').val();
            var id_prestacion = parseInt($('#id_prestacion_cpt_reg').val());
            console.log('ID PRESTACION SETEADO => ' + id_prestacion)
            if (tbl_CPMS_ENFERMERIA <= 0) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de CPMS.",
                    icon: "error",
                    timer: 2500,
                });
                setTimeout(function() {
                    $('#add_CPMS_ENFERMERIA_cpt').focus();
                }, 1000);
                return;
            }
            let isValidName = false;
            $('input[name="id_relacionado_CPMS_ENFERMERIA[]"]').each(function(index) {
                let id_relacionado = $(this).val().trim(); // Elimina espacios en blanco
                let cantidadNumerica = parseFloat(id_relacionado); // Convierte a número
                // Selecciona el input correspondiente de descripcion_CPMS_ENFERMERIA[] usando el índice
                //let descripcionInput = $('input[name="descripcion_CPMS_ENFERMERIA[]"]').eq(index);
                // Verifica si el valor es un número válido y mayor a 0
                if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                    isValidName = true; // Marca como inválido
                    //descripcionInput.addClass('is-invalid'); // Agrega clase de error al input de descripción
                    $('#descripcion_CPMS_ENFERMERIA_NUEVO' + index).css('border',
                        '1px solid red');
                } else {
                    // descripcionInput.removeClass('is-invalid'); // Remueve clase de error si es válido
                    $('#descripcion_CPMS_ENFERMERIA_NUEVO' + index).css('border', '');
                }
            });
            if (isValidName) {
                swal({
                    buttons: false,
                    title: "Verifique los campos de procedimiento",
                    text: "Proceso cancelado. Ingrese un valor válido.",
                    icon: "error",
                    timer: 2500,
                });
                return false; // Previene el envío del formulario
            }
            let isValid = false;
            $('input[name="procedimiento_cantidad[]"]').each(function(index) {
                let cantidad = $(this).val().trim(); // Elimina espacios en blanco
                let cantidadNumerica = parseFloat(cantidad); // Convierte a número
                // Verifica si el valor es un número válido y mayor a 1
                if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                    isValid = true;
                    $('#procedimiento_cantidad' + index).css('border', '1px solid red');
                    // Muestra un mensaje de error
                } else {
                    $('#procedimiento_cantidad' + index).css('border', '');
                }
            });
            if (isValid) {
                swal({
                    buttons: false,
                    title: "Verifique los campos de cantidad",
                    text: "Proceso cancelado. Ingrese un valor en la cantidad.",
                    icon: "error",
                    timer: 2500,
                });
                cambiar_de_PestanaTab("tab2defaultEvoluciones");
                return false;
            }

            let isValid2 = false;
            $('input[name="procedimiento_tiempo[]"]').each(function(index) {
                let cantidad = $(this).val().trim(); // Elimina espacios en blanco
                let cantidadNumerica = parseFloat(cantidad); // Convierte a número
                // Verifica si el valor es un número válido y mayor a 1
                if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                    isValid = true;
                    $('#procedimiento_tiempo' + index).css('border', '1px solid red');
                    // Muestra un mensaje de error
                } else {
                    $('#procedimiento_tiempo' + index).css('border', '');
                }
            });
            if (isValid2) {
                swal({
                    buttons: false,
                    title: "Verifique los campos de tiempo",
                    text: "Proceso cancelado. Ingrese un valor en el tiempo.",
                    icon: "error",
                    timer: 2500,
                });
                cambiar_de_PestanaTab("tab2defaultEvoluciones");
                return false;
            }

            if (descripcion_turno == null || descripcion_turno == '' || descripcion_turno.length < 1) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: "Proceso cancelado. Seleccione un TURNO VÁLIDO para el registro correspondiente.",
                    icon: "error",
                    timer: 2500,
                });
                $('#turnos_nuevo_cpt').next('.select2-container').addClass('borde-alerta');
                setTimeout(function() {
                    $('#turnos_nuevo_cpt').focus();
                }, 1000);
                return;
            } else {
                $('#turnos_nuevo_cpt').next('.select2-container').removeClass('borde-alerta');
            }
            registrarCpt();
        });
    });

    function registrarCpt() {
        swal({
            buttons: true,
            title: "¿Desea Realizar el Registro?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                var token = $('#_token').val();
                var route = '{{ url('registrar_nota_ingreso_cpt') }}';
                var formData = new FormData($("#FormularioCpt")[0]);
                $.ajax({
                    data: formData,
                    url: route,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    dataType: 'JSON',
                    success: function(data) {
                        // Limpiar formulario y cerrar modal
                        $('#FormularioCpt').trigger("reset");
                        $('#modalRegistrarCpt').modal('hide');
                        swal({
                            buttons: false,
                            title: "Registro Exitoso",
                            text: "Datos registrados Exitosamente.",
                            icon: "success",
                            timer: 1500,
                        });
                        // Actualizar listado de evoluciones
                        const $p = [
                            $('#fecha_cpt_inicial_listado').val(),
                            $('#fecha_cpt_final_listado').val(),
                            $('#id_turno_cpt').val(),
                            2,
                            $('#id_asegurado_cpt').val(),
                        ];
                        listar_cpt($p);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 409) {
                            swal({
                                buttons: false,
                                title: "REGISTRO EXISTENTE.",
                                text: "Ya existe un registro en el turno seleccionado.",
                                icon: "error",
                                timer: 2500,
                            });
                        } else {
                            swal({
                                buttons: false,
                                title: "Error de Servicio de Registros",
                                text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                                icon: "error",
                                timer: 2500,
                            });
                            console.log({
                                xhr,
                                status,
                                error
                            })
                        }
                    }
                });
            } else {
                console.log("El usuario ha cancelado la acción.");
            }
        });
    }

    function cargarDatosGlobalesCpt(id_enfermeria, id_asegurado, id_tipo_atencion, id_ext) {
        var var_url = '{{ url('datos_globales_evoluciones_v2') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_asegurado,
                id_enfermeria,
                id_ext,
                id_tipo_atencion,
            },
            success: function(data) {
                const diagnosticos_para_combo = [
                    ...data.diagnosticos.diagnosticos_atencion,
                    /* ...data.diagnosticos.diagnosticos_admision */
                ].map(({
                    id_diagnostico,
                    codigo,
                    nombre
                }) => ({
                    id_diagnostico,
                    codigo,
                    nombre
                }));
                diagnosticosCptCurr = diagnosticos_para_combo;
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
                /***********DATOS DEL PACIENTE***********/
                $('#num_documento_cpt_n').html(data.datos_paciente.nro_doc_ident);
                $('#nom_paciente_cpt_n').html(data.datos_paciente.paterno + " " + data.datos_paciente
                    .materno + " " + data.datos_paciente.nombre);
                $('#tipo_sangre_cpt_n').html(data.datos_paciente.tipo_sangre);
                $('#edad_cpt_n').html(data.datos_paciente.edad_corta);
                $('#sexo_cpt_n').html(data.datos_paciente.sexo);
                $('#hc_cpt_n').html(data.datos_paciente.nro_historia);
                $('#cel_acomp_cpt_n').html(data.estado_acomp != 0 ? data.estado_acomp :
                    'Sin registrar');
                $('#parentesgo_cpt_n').html(data.datos_paciente.parentesgo);
                $('#tipo_sangre_cpt_n').html(data.datos_paciente.tipo_sangre);
                if (data.estado_alergia.flag_alergia) {
                    $('#alergia_si_cpt_reg').show();
                } else {
                    $('#alergia_no_cpt_reg').show();
                }
                /*****************TURNOS**********/
                ;
                $("#turnos_nuevo_cpt").empty(); //
                $.each(data.turnos, function(key, value) {
                    $("#turnos_nuevo_cpt").append("<option value='" + value.id + "'>" + value
                        .descripcion + "</option>");
                });

                const inputFechaConHoraEvReg = document.getElementById('hora_registro_cpt');
                const horaEvReg = inputFechaConHoraEvReg.value;
                inputFechaConHoraEvReg.addEventListener('change', function(e) {
                    const horaEvReg = e.target.value;
                    $("#turnos_nuevo_cpt").val(isTimeInRangeCpt(horaEvReg, "07:30", "13:29") ? "2" :
                        isTimeInRangeCpt(horaEvReg, "13:30", "19:29") ? "3" :
                        isTimeInRangeCpt(horaEvReg, "19:30", "01:29") ? "4" :
                        isTimeInRangeCpt(horaEvReg, "01:30", "07:29") ? "1" : "").change();
                });

                const inputFechaConHora = document.getElementById('hora_registro_cpt');
                const hora = inputFechaConHora.value;
                $("#turnos_nuevo_cpt").val(isTimeInRangeCpt(hora, "07:30", "13:29") ? "2" :
                    isTimeInRangeCpt(
                        hora, "13:30", "19:29") ? "3" : isTimeInRangeCpt(hora, "19:30", "01:29") ?
                    "4" : isTimeInRangeCpt(hora, "01:30", "07:29") ? "1" : "").change();
                inputFechaConHora.addEventListener('change', function(e) {
                    const hora = e.target.value;
                    $("#turnos_nuevo_cpt").val(isTimeInRangeCpt(hora, "07:30", "13:29") ? "2" :
                        isTimeInRangeCpt(hora, "13:30", "19:29") ? "3" : isTimeInRangeCpt(
                            hora, "19:30", "01:29") ? "4" : isTimeInRangeCpt(hora, "01:30",
                            "07:29") ? "1" : "").change();
                });

                $("#turnos_nuevo_cpt").on('change', function(e) {
                    const hora = $("#hora_registro_cpt").val();
                    if (hora) {
                        if ($("#turnos_nuevo_cpt").val() == '2') {
                            if (!isTimeInRangeCpt(hora, "07:30", "13:29")) {
                                Swal.fire({
                                    icon: "error",
                                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                                    confirmButtonText: "ENTENDIDO",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: "ENTENDIDO",
                                });
                                e.preventDefault();
                                $("#turnos_nuevo_cpt").val(isTimeInRangeCpt(hora, "07:30",
                                            "13:29") ?
                                        "2" : isTimeInRangeCpt(hora, "13:30", "19:29") ? "3" :
                                        isTimeInRangeCpt(hora, "19:30", "01:29") ? "4" :
                                        isTimeInRangeCpt(hora, "01:30", "07:29") ? "1" : "")
                                    .change();
                            }
                        }
                        if ($("#turnos_nuevo_cpt").val() == '3') {
                            if (!isTimeInRangeCpt(hora, "13:30", "19:29")) {
                                Swal.fire({
                                    icon: "error",
                                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                                    confirmButtonText: "ENTENDIDO",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: "ENTENDIDO",
                                });
                                e.preventDefault();
                                $("#turnos_nuevo_cpt").val(isTimeInRangeCpt(hora, "07:30",
                                            "13:29") ?
                                        "2" : isTimeInRangeCpt(hora, "13:30", "19:29") ? "3" :
                                        isTimeInRangeCpt(hora, "19:30", "01:29") ? "4" :
                                        isTimeInRangeCpt(hora, "01:30", "07:29") ? "1" : "")
                                    .change();
                            }
                        }
                        if ($("#turnos_nuevo_cpt").val() == '4') {
                            if (!isTimeInRangeCpt(hora, "19:30", "01:29")) {
                                Swal.fire({
                                    icon: "error",
                                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                                    confirmButtonText: "ENTENDIDO",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: "ENTENDIDO",
                                });
                                e.preventDefault();
                                $("#turnos_nuevo_cpt").val(isTimeInRangeCpt(hora, "07:30",
                                            "13:29") ?
                                        "2" : isTimeInRangeCpt(hora, "13:30", "19:29") ? "3" :
                                        isTimeInRangeCpt(hora, "19:30", "01:29") ? "4" :
                                        isTimeInRangeCpt(hora, "01:30", "07:29") ? "1" : "")
                                    .change();
                            }
                        }
                        if ($("#turnos_nuevo_cpt").val() == '1') {
                            if (!isTimeInRangeCpt(hora, "01:30", "07:29")) {
                                Swal.fire({
                                    icon: "error",
                                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                                    confirmButtonText: "ENTENDIDO",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: "ENTENDIDO",
                                });
                                e.preventDefault();
                                $("#turnos_nuevo_cpt").val(isTimeInRangeCpt(hora, "07:30",
                                            "13:29") ?
                                        "2" : isTimeInRangeCpt(hora, "13:30", "19:29") ? "3" :
                                        isTimeInRangeCpt(hora, "19:30", "01:29") ? "4" :
                                        isTimeInRangeCpt(hora, "01:30", "07:29") ? "1" : "")
                                    .change();
                            }
                        }
                    }
                });

                /***********************DIAGNOSTICOS PACIENTES ADMISION************************/
                /* var cuerpo_DIAGNOSTICOS_ADMISION = '';
                $("#diagnosticos_nuevo_cpt").empty();
                $.each(data.diagnosticos.diagnosticos_admision, function(key, value) {
                    cuerpo_DIAGNOSTICOS_ADMISION = cuerpo_DIAGNOSTICOS_ADMISION +
                        '<tr>' +
                        '<td>' + value.codigo + '</td>' +
                        '<td>' + value.nombre + '</td>' +
                        '<td>' + (value.tipo ? value.tipo : 'DE ADMISION') + '</td>' +
                        '<td>' + (value.clasificacion ? value.clasificacion : 'DE ADMISION') +
                        '</td>' +
                        '</tr>';
                });
                $('#diagnosticos_nuevo_cpt').append(cuerpo_DIAGNOSTICOS_ADMISION != '' ?
                    cuerpo_DIAGNOSTICOS_ADMISION : `<tr>
                        <td colspan="4" style="text-align:center;">No hay datos registrados.</td>
                        </tr>`); */

                /***********************DIAGNOSTICOS PACIENTES PRESTACION************************/
                var cuerpo_DIAGNOSTICOS_PRESTACION = '';
                $("#diagnosticos_nuevo_cpt_prest").empty();
                $.each(data.diagnosticos.diagnosticos_atencion, function(key, value) {
                    cuerpo_DIAGNOSTICOS_PRESTACION = cuerpo_DIAGNOSTICOS_PRESTACION +
                        '<tr>' +
                        '<td>' + value.codigo + '</td>' +
                        '<td>' + value.nombre + '</td>' +
                        '<td>' + (value.tipo ? value.tipo : 'DE ADMISION') + '</td>' +
                        '<td>' + (value.clasificacion ? value.clasificacion : 'DE ADMISION') +
                        '</td>' +
                        '</tr>';
                });
                $('#diagnosticos_nuevo_cpt_prest').append(cuerpo_DIAGNOSTICOS_PRESTACION != '' ?
                    cuerpo_DIAGNOSTICOS_PRESTACION : `<tr>
                        <td colspan="4" style="text-align:center;">No hay datos registrados.</td>
                        </tr>`);
            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Error al realizar la solicitud.</span></div>",
                    confirmButtonText: "ENTENDIDO",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: "ENTENDIDO",
                    timer: 1200,
                    timerProgressBar: true,
                });
            }
        });
    }

    // Maneja el clic en el botón "Cargar más"
    $(document).on('click', '#load-more-button-registrar', function() {
        var id_enfermeria_registrar = $('#id_enfermeria_evolucion').val();
        var nextPageUrlRegistrar = $(this).data('next-page-url-registrar');

        var $button = $(this);
        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Cargando...');
        var var_url = '{{ url('cargar_mas_historiaL_invasivos') }}' +
            '?page=' + nextPageUrlRegistrar;

        $.ajax({
            url: var_url,
            type: 'POST ',
            dataType: 'JSON',
            data: {
                id_enfermeria: id_enfermeria_registrar,
                page: nextPageUrlRegistrar // Pasar el número de página actual
            },
            success: function(data) {
                $button.prop('disabled', false).html(
                    'Cargar más <i class="fa fa-chevron-down"></i>');
                procesarRespuesta_registrar(data, false); // false indica que es una carga adicional
            }
        });
        return false;

    });

    function procesarRespuesta_registrar(data, esCargaInicial) {
        var cuerpo_INVASIVOS_ATENCION_REGISTRAR = '';
        if (data.invasivos_por_atencion.items && data.invasivos_por_atencion.items.length > 0) {
            $.each(data.invasivos_por_atencion.items, function(index, item) {
                cuerpo_INVASIVOS_ATENCION_REGISTRAR +=
                    '<tr>' +
                    '<td>' + ((data.invasivos_por_atencion.current_page - 1) * 5 + index + 1) + '</td>' +
                    '<td>' + item.fecha + '</td>' +
                    '<td>' + item.tipo + '</td>' +
                    '<td>' + item.lugar + '</td>' +
                    '<td>' + item.accion + '</td>' +
                    '</tr>';
            });
        }

        if (esCargaInicial) {
            // Para la carga inicial, establecemos el contenido
            $('#historial_invasivos').html(cuerpo_INVASIVOS_ATENCION_REGISTRAR);
        } else {
            // Para cargas adicionales, agregamos al contenido existente
            $('#historial_invasivos').append(cuerpo_INVASIVOS_ATENCION_REGISTRAR);
        }

        // Manejo del botón "Cargar más"
        if (data.invasivos_por_atencion.nextPageUrl) {
            $('#load-more-container-registrar').html(
                '<button id="load-more-button-registrar" class="btn enf_btn-celeste" data-next-page-url-registrar="' +
                (data.invasivos_por_atencion.current_page + 1) + '">Cargar más </button>'
            );
        } else {
            $('#load-more-container-registrar').html('');
        }

    }

    function validacionFechasInvasivosRegitro(fecha) {
        // Fecha actual
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        var fechaRegistrada = fecha; //fecha del registro del paciente

        $('.fecha').each(function() {
            $(this).attr('min', fechaRegistrada);
            $(this).attr('max', today);
            // Establece la fecha actual como valor predeterminado si está dentro del rango permitido
            var valorActual = $(this).val();
            if (!valorActual || valorActual < fechaRegistrada) {
                $(this).val(fechaRegistrada);
            } else if (valorActual > today) {
                $(this).val(today);
            }
            // Desactiva la entrada manual y permite solo la selección con el selector
            $(this).on('input', function() {
                var valorActual = $(this).val();
                // Restringe la entrada a las fechas dentro del rango permitido
                if (valorActual < fechaRegistrada || valorActual > today) {
                    $(this).val('');
                }
            });
            // Impide la entrada manual en el campo de fecha
            $(this).on('keydown', function(e) {
                e.preventDefault(); // Previene la entrada manual desde el teclado
            });

            // Asegúrate de que el campo solo permita fechas dentro del rango permitido
            $(this).on('change', function() {
                var valorActual = $(this).val();
                if (valorActual < fechaRegistrada || valorActual > today) {
                    $(this).val('');
                }
            });
        });
    }
</script>
