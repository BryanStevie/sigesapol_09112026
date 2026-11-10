$(document).ready(function () {
    cargarListadoPrincipal();
    $('.select2').select2({ dropdownParent: $('#formAtencionEstrategia'), width: '100%' });
    $('#btnNuevo').click(function () {
        $('input, select, textarea, button').not('#btnVolver').prop('disabled', false);
        limpiarFormulario();

        /* $('#formAtencionEstrategia')[0].reset(); */

        $("#modalLabel").text("Nueva Atención de Estrategia Sanitaria");





        $('#form_individual').show();
        $('#form_masivo').hide();
        $('#tipo_estrategia').val('1'); // individual por defecto
        $('#modalAtencionEstrategia').modal('show');
    });

    $('#tipo_estrategia').change(function () {
        if ($(this).val() === '1') {
            $('#form_individual').show();
            $('#form_masivo').hide();
        } else {
            $('#form_individual').hide();
            $('#form_masivo').show();
        }
    });

    $('input[name="tipo_estrategia"]').change(function() {
        let valor = $(this).val();
        if (valor === "1") {
            $("#divEstadoEstrategia").show();
        } else if (valor === "2") {
            $("#divEstadoEstrategia").hide();
        }
    });

    $('#formAtencion').submit(function (e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const data = form.serialize();

        $.post(url, data)
            .done(function (res) {
                alert('Registro guardado correctamente');
                $('#modalAtencionEstrategia').modal('hide');
                cargarListadoPrincipal();
            })
            .fail(function (err) {
                alert('Error al guardar los datos.');
                console.error(err.responseText);
            });
    });

    $(document).on('click', '.btn-editar', function () {
        const id = $(this).data('id');



        $.get('/atencion_estrategia_sanitaria/' + id + '/edit', function (data) {
            $('#modalAtencion').modal('show');

            $('#id_atencion').val(data.id);
            $('#periodo').val(data.periodo);
            $('#id_establecimiento').val(data.id_establecimiento).trigger('change');
            $('#tipo_estrategia').val(data.tipo_estrategia).trigger('change');

            if (data.tipo_estrategia == 1) {
                $('#fecha_atencion').val(data.fecha_atencion);
                $('#id_asegurado').val(data.id_asegurado).trigger('change');
                $('#id_estrategia').val(data.id_estrategia).trigger('change');
                $('#id_upss').val(data.id_upss).trigger('change');
                $('#estado_individual').val(data.estado);
                $('#observacion_individual').val(data.observacion);
            } else {
                $('#fecha_atencion_masivo').val(data.fecha_atencion);
                $('#id_estrategia_masivo').val(data.id_estrategia).trigger('change');
                $('#id_upss_masivo').val(data.id_upss).trigger('change');
                $('#cant_personas_masivo').val(data.cant_personas);
                $('#id_medico_masivo').val(data.id_medico).trigger('change');
            }
        });
    });

    $(document).on('click', '.btnEliminar', function () {
        const id = $(this).data('id');
        const active = $(this).data('active');
        let msj = "";
        let msjReg = "";
        if (active == 1) {
            msj = "¿Está seguro de activar este registro?";
        } else {
            msj = "¿Está seguro de anular este registro?";

        }

        if (active == 1) {
            msjReg = "Registro Activado correctamente.";
        } else {
            msjReg = "Registro anulado correctamente.";

        }

        if (!confirm(msj)) return;

        $.ajax({
            url: '/atencion_estrategia_sanitaria/eliminar/' + id,
            method: 'DELETE',
            data: { _token: $('input[name="_token"]').val() },
            success: function () {
                alert(msjReg);
                cargarListadoPrincipal();
            },
            error: function (err) {
                alert('Error en el registro.');
                console.error(err.responseText);
            }
        });
    });

    function limpiarFormulario() {
        // $('#formAtencion')[0].reset();
        $("#id_registro_edit").val("");
        $("#btnAgregarDiagnostico").show();
        $("#btnAgregarEstrategia").show();
        $('#id_asegurado, #tipoDocPaciente, #id_establecimiento, #id_estrategia, #id_upss, #id_medico_masivo').val('').trigger('change');
        $("#nroPaciente").text("");
        $('#nro_registro').val();
        $('#periodo').val("");
        $('#fecha_atencion_ind').val("");
        $('#fecha_registro').val("");
        $('#estado_estrategia_form').val("");
        $('#cant_personas').val("");
        $("#id_upss_ind").val("").trigger('change');
        $("#fecha_registro").prop('readonly', true);
        $("#id_establecimiento_programacion, #tipo_atencion, #ugipress").prop('disabled', true);

        $('#tipoDocPaciente').val("").trigger('change');
        $('#nroPaciente').val("");
        $('#idEstPaciente').val("");
        $('#paciente_nombre').text("");

        $("#paciente_parentesco").text('');
        $("#paciente_sexo").text('');
        $('#paciente_hc').text('');


        $('#paciente_fec_nac').text('');
        $('#paciente_edad').text('');
        $('#paciente_titular').text('');
        $('#titular_nombre').text('');
        $('#titular_grado').text('');
        $('#titular_situacion').text('');
        $('#tablaDiagnostico tbody').empty();
        $("#tablaEstrategias tbody").empty();
        $("#thDeleteDiag").show();
        $("#thDeleteEstrategia").show();

    }

    $('#btnBuscar').click(function () {
        cargarListadoPrincipal();
    });

    function cargarListadoPrincipal() {
        var formData = $('#formFiltro').serialize();

        $.ajax({
            url: '/atencion_estrategia_sanitaria/listar',
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#tablaListado tbody').html('<tr><td colspan="8" class="text-center">Buscando...</td></tr>');
            },
            success: function (response) {
                if (response.length > 0) {
                    var html = '';
                    $.each(response, function (index, item) {
                        html += '<tr>';
                        html += '<td>' + (index + 1) + '</td>';

                        // Columna Establecimiento
                        html += '<td><strong>COD. IPRESS: </strong>' +
                            (item.establecimiento ? item.establecimiento.codigo : '') + '<br><strong>NOM IPRESS: </strong>' +
                            (item.establecimiento ? item.establecimiento.nombre : '') +
                            '</td>';

                        // Columna Atención
                        html += '<td><strong>N°: </strong>' +
                            (item.nro_registro || '') + '<br>' +
                            'ESTRATEGIA SANITARIA<br><strong>T.ESTRATEGIA: </strong>' +
                            (item.tipo_estrategia == 1 ? 'Individual' : 'Masivo') + '<br><strong>FEC.REG: </strong>' +
                            (item.fecha_registro ? formatDate(item.fecha_registro) : '') +
                            '</td>';

                        // Columna Paciente
                        html += '<td><strong>CANT: </strong>' + 
                        (item.cant_personas || '');
                        if (item.tipo_estrategia != 2) {
                            html += '<br><strong>NOM Y APE: </strong>' +
                            (item.asegurado ? (item.asegurado.nombre + ' ' + item.asegurado.paterno + ' ' + item.asegurado.materno) : (item.nombre_civil + ' ' + item.paterno_civil + ' ' + item.materno_civil)) + '<br><strong>DNI: </strong>' +
                            (item.asegurado ? item.asegurado.nro_doc_ident : item.dni_civil);
                        }
                        html += '</td>';

                        // Columna Registrador
                        html += '<td><strong>NOM Y APE : </strong>' +
                            (item.user ? (item.nombre_medico + ' ' + item.apellido_paterno_medico + ' ' + item.apellido_materno_medico) : '') + '<br><strong>DNI </strong>' +
                            (item.user ? item.user.dni : '')  +
                            '</td>';

                        // Botones de acción
                        html += '<td class="text-center"><button class="btn btn-xs btn-info btnVer" data-mode="visualizacion" data-id="' + item.id + '"><i class="fa fa-eye"></i></button></td>';
                        html += '<td class="text-center"><button class="btn btn-xs btn-warning btnEditar" data-id="' + item.id + '"><i class="fa fa-edit"></i></button></td>';
                        if (item.estado == 0) {
                            html += '<td class="text-center"><button class="btn btn-xs btn-success btnEliminar" data-active="1"  data-id="' + item.id + '"><i class="fa fa-check"></i></button></td>';
                        } else {
                            html += '<td class="text-center"><button class="btn btn-xs btn-danger btnEliminar" data-id="' + item.id + '"><i class="fa fa-trash"></i></button></td>';
                        }
                        html += '</tr>';
                    });
                    $('#tablaListado tbody').html(html);
                } else {
                    $('#tablaListado tbody').html('<tr><td colspan="8" class="text-center">No se encontraron resultados</td></tr>');
                }
            },
            error: function (xhr) {
                $('#tablaListado tbody').html('<tr><td colspan="8" class="text-center text-danger">Error al buscar</td></tr>');
                console.error(xhr.responseText);
            }
        });
    }

    // Función para formatear fecha
    function formatDate(dateString) {
        if (!dateString) return '';
        var date = new Date(dateString);
        var day = date.getDate().toString().padStart(2, '0');
        var month = (date.getMonth() + 1).toString().padStart(2, '0');
        var year = date.getFullYear();
        return day + '-' + month + '-' + year;
    }

});


function cargarDatos(id, modo = 'edicion') {
    function formatearFechaDateV2(fecha) {
        const dia = String(fecha.getDate()).padStart(2, '0');
        const mes = String(fecha.getMonth() + 1).padStart(2, '0');
        const año = fecha.getFullYear();

        return `${dia}/${mes}/${año}`;
    }

    function calcularEdadV2(fechaNacimientoStr) {
        const [year, month, day] = fechaNacimientoStr.split('-').map(Number);
        const hoy = new Date();
        let edad = hoy.getFullYear() - year;

        const mesActual = hoy.getMonth() + 1;
        const diaActual = hoy.getDate();
        if (mesActual < month || (mesActual === month && diaActual < day)) {
            edad--;
        }

        return edad;
    }
    
    $('#modalAtencionEstrategia').modal('show');
    if (modo != "edicion") {
        $("#id_registro_edit").val(id);
        $("#modalLabel").text("Atención de Estrategia Sanitaria");
        $("#thDeleteDiag").hide();
        $("#thDeleteEstrategia").hide();
    } else {
        $("#modalLabel").text("Editar Atención de Estrategia Sanitaria");
        $("#id_registro_edit").val("");
        $("#thDeleteDiag").show();
        $("#thDeleteEstrategia").show();
    }

    $.get('/atencion_estrategia_sanitaria/obtener/' + id, function (response) {
        if (!response.success) {
            alert('Error al cargar los datos');
            return;
        }

        $("#tipo_registro").val("");

        const data = response.data;

        $("#id_registro_edit").val(data.id);
        $('#nro_registro').val(data.nro_registro);
        $('#periodo').val(data.periodo);
        $('#fecha_atencion_ind').val(data.fecha_atencion);
        $('#fecha_registro').val(data.fecha_registro.substring(0, 10));
        $('#estado_estrategia_form').val(data.estado_estrategia);
        $('#cant_personas').val(data.cant_personas);
        $("#id_upss_ind").val(data.id_upss).trigger('change');

        if (data.tipo_estrategia == 2) {
            $("#divEstadoEstrategia").hide();
        } else {
            $("#divEstadoEstrategia").show();
        }

        if (modo === 'visualizacion') {
            $('input, select, textarea, button').not('#btnVolver').prop('disabled', true);
            $(".btnVer").prop('disabled', false);
            $(".btnEditar").prop('disabled', false);
            $(".btnEliminar").prop('disabled', false);
            $("#btnBuscar").prop('disabled', false);
            $("#btnNuevo").prop('disabled', false);
            $("#fecha_desde").prop('disabled', false);
            $("#fecha_hasta").prop('disabled', false);
            $("#documento").prop('disabled', false);
            $("#tipo_estrategia").prop('disabled', false);
            $("#estado_estrategia").prop('disabled', false);

            $('.eliminarDiagnostico, .eliminarEstrategia').hide();
            $('#btnAgregarDiagnostico, #btnAgregarEstrategia').hide();
        } else {
            $('input, select, textarea, button').not('#btnVolver').prop('disabled', false);
            $("#id_establecimiento_programacion, #tipo_atencion, #ugipress").prop('disabled', true);
            $('.eliminarDiagnostico, .eliminarEstrategia').hide();
            $('#btnAgregarDiagnostico, #btnAgregarEstrategia').show();
            $('#nombre_asegurado_es').val(data.nombre_civil);
            $('#paterno_es').val(data.paterno_civil);
            $('#materno_es').val(data.materno_civil);
            $('#dni_es').val(data.dni_civil);
            $('#sexo_es').val(data.sexo).change();
            $('#nacimiento_es').val(data.fecha_nac);
        }

        if (data.tipo_estrategia == 1) {
            $('input[name="tipo_estrategia"][value="1"]').prop('checked', true);
            $('#formIndividual').show();
            $('#formMasivo').hide();
            $('#divPaciene').show();
            $("#divcant_personas").hide();
            $('#divMedico').removeClass('col-md-12').addClass('col-md-6');

            if (data.asegurado) {
                $('#tipoDocPaciente').val(data.asegurado.id_tipo_doc_ident).trigger('change');
            } else {
                $('#tipoDocPaciente').val(1).trigger('change');
                $("#tipo_registro").val("U");
            }
            $('#nroPaciente').val(data.asegurado ? data.asegurado.nro_doc_ident : data.dni_civil);
            $('#idEstPaciente').val(data.asegurado ? data.asegurado.id : '');

            $('#paciente_nombre').text(
                (data.asegurado ? data.asegurado.paterno : data.paterno_civil) + ' ' +
                (data.asegurado ? data.asegurado.materno : data.materno_civil) + ' ' +
                (data.asegurado ? data.asegurado.nombre : data.nombre_civil)
            );

            $("#paciente_parentesco").text(data.asegurado_extendido?.tipo_beneficiario_nombre || 'CIVIL');
            $("#paciente_sexo").text(data.asegurado?.sexo || data.sexo == "M" ? "MASCULINO" : "FEMENINO");
            $('#paciente_hc').text(data.asegurado_extendido?.nro_historia || 'Sin data.');


            $('#paciente_fec_nac').text(data.asegurado?.fecha_nac || formatearFechaDateV2(new Date(data.fecha_nac)));
            $('#paciente_edad').text(data.asegurado_extendido?.edad || calcularEdadV2(data.fecha_nac));
            $('#paciente_titular').text(data.asegurado?.titular || '');

            let asegurado = data.asegurado;

            if (data.asegurado) {
                if (
                    !asegurado.paterno_titular &&
                    !asegurado.materno_titular &&
                    !asegurado.nombre_titular
                ) {
                    // Mostrar nombre del paciente
                    $('#titular_nombre').text(
                        (asegurado.paterno || '') + ' ' +
                        (asegurado.materno || '') + ' ' +
                        (asegurado.nombre || '')
                    );
                } else {
                    // Mostrar nombre del titular
                    $('#titular_nombre').text(
                        (asegurado.paterno_titular || '') + ' ' +
                        (asegurado.materno_titular || '') + ' ' +
                        (asegurado.nombre_titular || '')
                    );
                }

                $('#titular_grado').text(data.asegurado.grado || '');
                $('#titular_situacion').text(data.asegurado.situacion_paciente || '');
            } else {
                $('#titular_nombre').wrapInner('<i style="font-size: 10px;">No disponible por condicion de CIVIL.</i>');
                $('#titular_grado').wrapInner('<i style="font-size: 10px;">No disponible por condicion de CIVIL.</i>');
                $('#titular_situacion').wrapInner('<i style="font-size: 10px;">No disponible por condicion de CIVIL.</i>');
            }

            // Cargar diagnósticos (solo individual)
            $('#tablaDiagnostico tbody').empty();
            if (data.diagnosticos && data.diagnosticos.length > 0) {
                data.diagnosticos.forEach(function (diag, index) {
                    const fila = `
                        <tr>
                            <td> <input type="hidden" name="diagnosticos[${index}][id_diagnostico]" value="${diag.id}">${diag.diagnostico.codigo} - ${diag.diagnostico.nombre}</td>
                            <td> <input type="hidden" name="diagnosticos[${index}][tipo]" value="${diag.id_tipo_diagnostico || ''}" >${diag.id_tipo_diagnostico == '1' ? 'Presuntivo' :
                            diag.id_tipo_diagnostico == '2' ? 'Definitivo' : 'Repetitivo'}</td>
                            <td> <input type="hidden" name="diagnosticos[${index}][observacion]" value="${diag.observacion || ''}" >${diag.observacion || ''}</td>
                            ${modo === 'edicion' ? `
    <td>
        <button type="button" class="btn btn-danger btn-sm eliminarDiagnostico">
            <i class="fa fa-times"></i>
        </button>
    </td>` : ''}

                        </tr>`;
                    $('#tablaDiagnostico tbody').append(fila);
                });
            }
        } else {
            $('input[name="tipo_estrategia"][value="2"]').prop('checked', true);
            $('#formIndividual').hide();
            $("#divcant_personas").show();

            $('#formMasivo').show();
            $('#divPaciene').hide();
            $('#divMedico').removeClass('col-md-6').addClass('col-md-12');
        }

        // Cargar estrategias sanitarias
        $('#tablaEstrategias tbody').empty();
        if (data.actividades && data.actividades.length > 0) {
            data.actividades.forEach(function (actividades) {
                const fila = `
                    <tr>
                        <td> <input type="hidden" name="estrategias[][codigo]" value="${actividades.estrategia.codigo}"> ${actividades.estrategia.codigo} - ${actividades.estrategia.nombre}</td>
                        <td><input type="hidden" name="estrategias[][observacion]" value="${actividades.observacion}">${actividades.observacion || ''}</td>
                        ${modo === 'edicion' ? `
                        <td>
                            <button type="button" class="btn btn-danger btn-sm eliminarEstrategia">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>` : ''}
                    </tr>`;
                $('#tablaEstrategias tbody').append(fila);
            });
        }

        // Si es visualización, ocultar el botón de guardar
        if (modo === 'visualizacion') {
            $('#btnGuardar').hide();
            $('#btnVolver').show();
        } else {

            $('#btnGuardar').show();
            $('#btnVolver').hide();
        }
    }).fail(function () {
        alert('Error al cargar los datos de la atención');
    });
}

$(document).on('click', '.btnEditar, .btnVer', function () {
    const id = $(this).data('id');
    const mode = $(this).data('mode'); // 'edicion' o 'visualizacion'
    $("#btnAgregarEstrategia").show();
    $("#btnAgregarDiagnostico").show();
    // Cargar los datos en el formulario
    cargarDatos(id, mode);

    // Opcional: Mostrar el formulario si está oculto
    /* $('#formContainer').show();
     $('html, body').animate({
         scrollTop: $('#/').offset().top
     }, 500);*/

    // Opcional: Cambiar el título según el modo
    //$('#formTitle').text(mode === 'edicion' ? 'Editar Atención' : 'Ver Atención');
});