<script type="text/javascript">
    (() => {
        $('#modalEditarCpt').on('hidden.bs.modal', function() {
            $("#hora_upt_cpt").val("");
        });

        $(document).on('click', '.boton_eliminar_item_cpms_cpt', function() {
            var $registro = $(this).closest('tr');
            var id_cpms = $registro.find('input.id_cpms_upt').val();
            var id_cpt = $registro.find('input#id_cpt_delete').val();

            var route = '{{ url('anular_item_procedimiento_enfermeria_cpt') }}';
            Swal.fire({
                title: "¿Esta seguro de anular el registro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, anular",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: route,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id_cpms,
                            id_cpt
                        },
                        success: function(data) {
                            $registro.css('text-decoration', 'line-through');
                            $registro.css('color', 'red');
                            if (data != null) {
                                if (data != 0) {
                                    $registro.remove();
                                    console.log('Se ha anulado el registro');
                                } else {
                                    swal({
                                        buttons: false,
                                        title: "Error de Servicio de Registros",
                                        text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                                        icon: "error",
                                        timer: 2500,
                                    });
                                }
                            } else {
                                swal({
                                    buttons: false,
                                    title: "Error de Servicio de Registros",
                                    text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                                    icon: "error",
                                    timer: 2500,
                                });
                            }
                        },
                        error: function(xhr) {
                            alert('Error en la solicitud');
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Operación Cancelada",
                        text: "Proceso de Registro Cancelado.",
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $registro.css('text-decoration', '');
                    $registro.css('color', '');
                }
            });
        });

        function isTimeInRangeCptUpt(time, startTime, endTime) {
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

        let diagnosticosCptCurrUpt = null;

        $("#turno_upt_cpt").on('change', function(e) {
            const horaSinRecortar = $("#hora_upt_cpt").val();
            const hora = horaSinRecortar.substring(0, 5);
            if (hora) {
                if ($("#turno_upt_cpt").val() == '2') {
                    if (!isTimeInRangeCptUpt(hora, "07:30", "13:29")) {
                        Swal.fire({
                            icon: "error",
                            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                            confirmButtonText: "ENTENDIDO",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            confirmButtonText: "ENTENDIDO",
                        });
                        e.preventDefault();
                        const turno = isTimeInRangeCptUpt(hora, "07:30", "13:29") ? "2" :
                            isTimeInRangeCptUpt(hora, "13:30", "19:29") ? "3" :
                            isTimeInRangeCptUpt(hora, "19:30", "01:29") ? "4" :
                            isTimeInRangeCptUpt(hora, "01:30", "07:29") ? "1" : "";
                        $("#turno_upt_cpt").val(turno).trigger('change.select2');
                    }
                }
                if ($("#turno_upt_cpt").val() == '3') {
                    if (!isTimeInRangeCptUpt(hora, "13:30", "19:29")) {
                        Swal.fire({
                            icon: "error",
                            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                            confirmButtonText: "ENTENDIDO",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            confirmButtonText: "ENTENDIDO",
                        });
                        e.preventDefault();
                        const turno = isTimeInRangeCptUpt(hora, "07:30", "13:29") ? "2" :
                            isTimeInRangeCptUpt(hora, "13:30", "19:29") ? "3" :
                            isTimeInRangeCptUpt(hora, "19:30", "01:29") ? "4" :
                            isTimeInRangeCptUpt(hora, "01:30", "07:29") ? "1" : "";
                        $("#turno_upt_cpt").val(turno).trigger('change.select2');
                    }
                }
                if ($("#turno_upt_cpt").val() == '4') {
                    if (!isTimeInRangeCptUpt(hora, "19:30", "01:29")) {
                        Swal.fire({
                            icon: "error",
                            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                            confirmButtonText: "ENTENDIDO",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            confirmButtonText: "ENTENDIDO",
                        });
                        e.preventDefault();
                        const turno = isTimeInRangeCptUpt(hora, "07:30", "13:29") ? "2" :
                            isTimeInRangeCptUpt(hora, "13:30", "19:29") ? "3" :
                            isTimeInRangeCptUpt(hora, "19:30", "01:29") ? "4" :
                            isTimeInRangeCptUpt(hora, "01:30", "07:29") ? "1" : "";
                        $("#turno_upt_cpt").val(turno).trigger('change.select2');
                    }
                }
                if ($("#turno_upt_cpt").val() == '1') {
                    if (!isTimeInRangeCptUpt(hora, "01:30", "07:29")) {
                        Swal.fire({
                            icon: "error",
                            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                            confirmButtonText: "ENTENDIDO",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            confirmButtonText: "ENTENDIDO",
                        });
                        e.preventDefault();
                        const turno = isTimeInRangeCptUpt(hora, "07:30", "13:29") ? "2" :
                            isTimeInRangeCptUpt(hora, "13:30", "19:29") ? "3" :
                            isTimeInRangeCptUpt(hora, "19:30", "01:29") ? "4" :
                            isTimeInRangeCptUpt(hora, "01:30", "07:29") ? "1" : "";
                        $("#turno_upt_cpt").val(turno).trigger('change.select2');
                    }
                }
            }
        });

        function ver_cpt_editar(id_cpt, id_enfermeria, id_asegurado, id_ext, id_tipo_atenciones_listado) {
            var var_url = '{{ url('ver_nota_ingreso_cpt') }}';
            $.ajax({
                url: var_url,
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id_cpt,
                    id_enfermeria,
                    id_asegurado,
                    id_ext,
                    id_tipo_atencion: id_tipo_atenciones_listado
                },
                success: function(data) {
                    const diagnosticos_para_combo_upt = [
                        ...data.diagnosticos.diagnosticos_atencion,
                        ...data.diagnosticos.diagnosticos_admision
                    ].map(({
                        id_diagnostico,
                        codigo,
                        nombre
                    }) => ({
                        id_diagnostico,
                        codigo,
                        nombre
                    }));
                    diagnosticosCptCurrUpt = diagnosticos_para_combo_upt;

                    $('#turno_upt_cpt').val(`${data.nota_cpt[0].id_turno}`);
                    $('#turno_upt_cpt').select2();
                    $("#hora_upt_cpt").val(data.nota_cpt[0].updated_at.split(" ")[1]);
                    $("#fecha_upt_cpt").val(data.nota_cpt[0].updated_at.split(" ")[0]);
                    $("#id_prestacion_cpt_upt").val(data.nota_cpt[0].id_prestacion);

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
                    const diagnosticos_para_combo = [
                        ...data.diagnosticos.diagnosticos_atencion,
                        ...data.diagnosticos.diagnosticos_admision
                    ].map(({
                        id_diagnostico,
                        codigo,
                        nombre
                    }) => ({
                        id_diagnostico,
                        codigo,
                        nombre
                    }));

                    $("#fecha_hora_ing_upt_cpt").val(data.fechas_ingreso_egreso.fecha_hora_ingreso);
                    $("#fecha_hora_eg_upt_cpt").val(data.fechas_ingreso_egreso.fecha_hora_egreso);

                    const inputFechaConHoraEv = document.getElementById('hora_upt_cpt');
                    const horaEv = inputFechaConHoraEv.value;
                    inputFechaConHoraEv.addEventListener('change', function(e) {
                        const horaEv = e.target.value;
                        $("#turno_upt_cpt").val(isTimeInRangeCptUpt(horaEv, "07:30", "13:29") ?
                                "2" :
                                isTimeInRangeCptUpt(horaEv, "13:30", "19:29") ? "3" :
                                isTimeInRangeCptUpt(horaEv, "19:30", "01:29") ? "4" :
                                isTimeInRangeCptUpt(horaEv, "01:30", "07:29") ? "1" : "")
                            .change();
                    });

                    const optionsHTML = diagnosticos_para_combo
                        .map(d =>
                            `<option value="${d.id_diagnostico}">${d.codigo} - ${d.nombre}</option>`)
                        .join('\n');
                    $('#num_documento_cpt_u').html(data.datos_paciente.nro_doc_ident);
                    $('#nom_paciente_cpt_u').html(data.datos_paciente.paterno + " " + data
                        .datos_paciente.materno + " " + data.datos_paciente.nombre);
                    $('#tipo_sangre_cpt_u').html(data.datos_paciente.tipo_sangre);
                    $('#edad_cpt_u').html(data.datos_paciente.edad_corta);
                    $('#sexo_cpt_u').html(data.datos_paciente.sexo);
                    $('#hc_cpt_u').html(data.datos_paciente.nro_historia);
                    $('#parentesgo_cpt_u').html(data.datos_paciente.parentesgo);
                    if (data.estado_alergia.flag_alergia) {
                        $('#alergia_si_cpt_upt').show();
                    } else {
                        $('#alergia_no_cpt_upt').show();
                    }

                    var cuerpo_CPMS_ENFERMERIA = '';
                    $("#cuerpo_CPMS_ENFERMERIA_upt").empty();
                    if (data.cpms && data.cpms.length > 0) {
                        $.each(data.cpms, function(key, value) {
                            let cuerpo_CPMS_ENFERMERIA_string =
                                '<tr>' +
                                '<input type="hidden" id="id_cpt_delete" value="' + value
                                .id_nota_enfermeria_cpt + '">' +
                                '<input type="hidden" class="id_cpms_upt" id="id_cpms_upt" name="id_cpms_upt[]" value="' +
                                value
                                .id + '">' +
                                '<input type="hidden" class="form-control typeahead tt-query id_relacionado_CPMS_ENFERMERIA_EDITAR" name="id_relacionado_CPMS_ENFERMERIA_EDITAR[]" data-id=' +
                                key + ' style="height: 28px !important;" value="' + value
                                .id_procedimiento +
                                '">' +
                                ' <td><input type="text" class="form-control input-sm" ' +
                                ' style="height: 28px !important;font-weight: 800;" name="procedimiento_descripcion[]" id="procedimiento_descripcion' +
                                key +
                                '" value="' + value.codigo_procedimiento + ' - ' + value
                                .nombre_procedimiento + '" disabled> ' +
                                ' </td>' +
                                '<td>' +
                                '<input name="procedimiento_cantidad[]" id="procedimiento_cantidad' +
                                key + '" value="' +
                                value.cantidad +
                                '" type="text" class="form-control" style="height: 28px !important;font-weight: 40;">' +
                                '</td>' +
                                '<td>' +
                                ' <select class="form-control" style="height: 28px !important;" id="procedimiento_diagnosticos' +
                                key + '" name="procedimiento_diagnosticos[]">' +
                                optionsHTML + '</select></td>' +
                                '<td> <button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_cpms_cpt  btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>' +
                                '</tr>';
                            let template = document.createElement('template');
                            template.innerHTML = cuerpo_CPMS_ENFERMERIA_string.trim();
                            let selectDiagnosticos = template.content.querySelector('select');
                            let options = selectDiagnosticos.options;
                            for (let i = 0; i < options.length; i++) {
                                let option = options[i];
                                if (option.value == value.id_diagnostico) {
                                    option.selected = true;
                                    option.setAttribute('selected', 'selected');
                                    break;
                                }
                            }
                            let cuerpoActualizado = template.content.firstElementChild
                                .outerHTML;
                            cuerpo_CPMS_ENFERMERIA += cuerpoActualizado;
                        });
                    }
                    $("#cuerpo_CPMS_ENFERMERIA_upt").html(cuerpo_CPMS_ENFERMERIA);

                    var cuerpo_DIAGNOSTICOS_ADMISION = '';
                    $("#diagnosticos_upt_cpt").empty();
                    $.each(data.diagnosticos.diagnosticos_admision, function(key, value) {
                        cuerpo_DIAGNOSTICOS_ADMISION = cuerpo_DIAGNOSTICOS_ADMISION +
                            '<tr>' +
                            '<td>' + value.codigo + '</td>' +
                            '<td>' + value.nombre + '</td>' +
                            '<td>' + (value.tipo ? value.tipo : 'DE ADMISION') + '</td>' +
                            '<td>' + (value.clasificacion ? value.clasificacion :
                                'DE ADMISION') +
                            '</td>' +
                            '</tr>';
                    });
                    $('#diagnosticos_upt_cpt').append(cuerpo_DIAGNOSTICOS_ADMISION != '' ?
                        cuerpo_DIAGNOSTICOS_ADMISION : `<tr>
                        <td colspan="4" style="text-align:center;">No hay datos registrados.</td>
                        </tr>`);

                    var cuerpo_DIAGNOSTICOS_PRESTACION = '';
                    $("#diagnosticos_upt_cpt_prest").empty();
                    $.each(data.diagnosticos.diagnosticos_atencion, function(key, value) {
                        cuerpo_DIAGNOSTICOS_PRESTACION = cuerpo_DIAGNOSTICOS_PRESTACION +
                            '<tr>' +
                            '<td>' + value.codigo + '</td>' +
                            '<td>' + value.nombre + '</td>' +
                            '<td>' + (value.tipo ? value.tipo : 'DE ADMISION') + '</td>' +
                            '<td>' + (value.clasificacion ? value.clasificacion :
                                'DE ADMISION') +
                            '</td>' +
                            '</tr>';
                    });
                    $('#diagnosticos_upt_cpt_prest').append(cuerpo_DIAGNOSTICOS_PRESTACION != '' ?
                        cuerpo_DIAGNOSTICOS_PRESTACION : `<tr>
                        <td colspan="4" style="text-align:center;">No hay datos registrados.</td>
                        </tr>`);
                }
            })
        };

        $('body').on('click', '#editarCpt', function() {
            $('#modalEditarCpt').modal('show');
            var id_cpt = $(this).data('id_cpt');
            var id_tipo_atenciones_listado = $('#id_tipo_atenciones_listado_cpt').val();
            var id_asegurado = $(this).data('id_asegurado');
            var id_ext = $('#id_ext_cpt').val();
            var id_enfermeria = $('#id_enfermeria_ver_cpt').val();
            $('#id_enfermeria_cpt_upt').val(id_enfermeria);
            $('#id_asegurado2_cpt_upt').val(id_asegurado);
            $('#id_tipo_atenciones_listado_cpt_upt').val(3);
            $('#id_cpt_upt').val(id_cpt);
            Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando informacion</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
            });
            $('#nombre_tipo_atencion_upt_cpt').val('HOSPITALIZACION');
            var prioridad = $('#prioridad_cpt').val();
            $('#prioridad_upt_cpt').val(prioridad);
            $("#tbl_CPMS_ENFERMERIA_upt tbody").empty();
            ver_cpt_editar(id_cpt, id_enfermeria, id_asegurado, id_ext, id_tipo_atenciones_listado);
        });

        async function actualizarCpt() {
            try {
                const willUpdate = await swal({
                    buttons: true,
                    title: "¿Desea Realizar la Actualización?",
                    text: "Recuerde verificar que todos los datos sean correctos.",
                    icon: "warning",
                    dangerMode: true,
                });
                if (willUpdate) {
                    const token = $('#_token').val();
                    const route = '{{ url('actualizar_cpt_cpms') }}';
                    const formData = new FormData($("#FormularioUpdateCpt")[0]);
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
                        success: function() {
                            $('#FormularioUpdateCpt').trigger("reset");
                            $('#modalEditarCpt').modal('hide');
                            swal({
                                buttons: false,
                                title: "Registro Exitoso",
                                text: "Datos actualizados Exitosamente.",
                                icon: "success",
                                timer: 1500,
                            });
                            const $p = [
                                $('#fecha_cpt_inicial_listado').val(),
                                $('#fecha_cpt_final_listado').val(),
                                $('#id_turno_cpt').val(),
                                $('#tipo_atenciones_listado_cpt').val(),
                                $('#id_asegurado_cpt').val(),
                            ];
                            listar_cpt($p);
                        },
                        error: function(err) {
                            console.log(err)
                            swal({
                                buttons: false,
                                title: "Error",
                                text: `${err.responseJSON.message}`,
                                icon: "error",
                                timer: 2500,
                            });
                        }
                    });
                } else {
                    console.log("El usuario ha cancelado la actualización.");
                }
            } catch (err) {
                console.log(err)
            }
        }

        $("#actualizarCpt").on("click", function() {
            var turno = $('#turno_upt_cpt').val();
            let tbl_CPMS_ENFERMERIA_EDITAR = $('#tbl_CPMS_ENFERMERIA_upt tbody tr').length;

            let fechaReg = $("#fecha_upt_cpt").val();
            let horaReg = $("#hora_upt_cpt").val();
            let fechaIng = $("#fecha_hora_ing_upt_cpt").val();
            let fechaEg = $("#fecha_hora_eg_upt_cpt").val() != "" ? $("#fecha_hora_eg_upt_cpt").val() :
            null;

            if (!horaReg.match(/^\d{2}:\d{2}:\d{2}$/)) {
                horaReg += ':00';
            }

            let fechaHoraReg = `${fechaReg} ${horaReg}`;
            let fechaHoraRegDate = new Date(fechaHoraReg.replace(' ', 'T'));
            let fechaIngDate = new Date(fechaIng.replace(' ', 'T'));
            let fechaEgDate = fechaEg ? new Date(fechaEg.replace(' ', 'T')) : null;

            if (fechaHoraRegDate < fechaIngDate) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: `Proceso cancelado. No se puede registrar con una fecha y hora anterior al ingreso del paciente: ${fechaIng}.`,
                    icon: "error",
                    timer: 3500,
                });
                return;
            }

            if (fechaEgDate && (fechaHoraRegDate > fechaEgDate)) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: `Proceso cancelado. No se puede registrar con una fecha y hora posterior al egreso del paciente: ${fechaEg}.`,
                    icon: "error",
                    timer: 3500,
                });
                return;
            }
            if (turno == null || turno == '' || turno.length < 1) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: "Proceso cancelado. Seleccione un TURNO VÁLIDO para el registro correspondiente.",
                    icon: "error",
                    timer: 2500,
                });
                return;
            }
            if (tbl_CPMS_ENFERMERIA_EDITAR <= 0) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de CPMS.",
                    icon: "error",
                    timer: 2500,
                });
                setTimeout(function() {
                    $('#add_CPMS_ENFERMERIA_upt_cpt').focus();
                }, 1000);
                return;
            }
            let isValid = false;
            $('input[name="procedimiento_cantidad_upt[]"]').each(function(index) {
                let cantidad = $(this).val().trim(); // Elimina espacios en blanco
                let cantidadNumerica = parseFloat(cantidad); // Convierte a número
                let descripcionInput = $('input[name="procedimiento_cantidad_upt[]"]').eq(
                    index);
                // Verifica si el valor es un número válido y mayor a 1
                if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                    isValid = true;
                    descripcionInput.addClass('is-invalid');
                    // Muestra un mensaje de error
                } else {
                    descripcionInput.removeClass('is-invalid');
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
                return false;
            }
            actualizarCpt();
        });

        $('#add_CPMS_ENFERMERIA_upt_cpt').on('click', function() {
            lista_table_tr_campo_detalle(
                'tbl_CPMS_ENFERMERIA_upt',
                'descripcion_CPMS_ENFERMERIA_EDITAR',
                'descripcion_detalle_CPMS_ENFERMERIA',
                'id_relacionado_CPMS_ENFERMERIA_EDITAR',
                'boton_eliminar_item_CPMS_ENFERMERIA_upt',
                'null',
                'sin_elemento_adicional',
                'null',
                'null',
                'SI',
                'SI',
                'procedimiento_cantidad',
                diagnosticosCptCurrUpt
            );
        });

        $(document).on('click', '.boton_eliminar_item_CPMS_ENFERMERIA_upt', function() {
            $(this).closest('tr').remove();
        });
    })();
</script>
