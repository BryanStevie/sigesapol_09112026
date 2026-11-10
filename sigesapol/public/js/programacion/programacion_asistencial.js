$(document).ready(function () {
    // Inicialización
    buscarProgramacion();
    $('#id_establecimiento_programacion.select2').select2({ width: '100%' });


    // Formato automático para el campo periodo (mm/aaaa)
    $('#periodo, #modal_periodo').on('input', function () {
        let value = $(this).val();
        if (value.length === 2 && !value.includes('/')) {
            $(this).val(value + '/');
        }
    });

    // Variables globales
    let registros = [];
    let periodoActual = '';
    let establecimientoActual = '';

    // Buscar programación
    $('#btnBuscar').click(function () {
        buscarProgramacion();
    });

    // Nuevo registro
    $('#btnNuevo').click(function () {
        // Obtener valores del formulario principal
        $('#formularioModal').show();
        $('.modal-footer').show();
        $('#modalProgramacion .modal-title').text('Registro de Programación');
        $('#modalProgramacion .modal-header').removeClass('bg-primary').addClass('bg-success');

        const periodo = $('#periodo').val();
        const idEstablecimiento = $('#id_establecimiento_programacion').val();


        // Limpiar modal y establecer valores básicos
        $('#formModalProgramacion')[0].reset();
        $('#id_clase_modal, #id_tipo_modal, #id_personal_modal').val('').trigger('change');
        $('#id_registro_edit').val('');

        // Establecer periodo e IPRESS en la modal
        $('#modal_periodo').val(periodo);
        $('#modal_establecimiento').val(idEstablecimiento).trigger('change');

        // Guardar valores actuales para uso posterior
        periodoActual = periodo.replace(/\D/g, '');
        establecimientoActual = idEstablecimiento;

        // Cargar detalle existente
        cargarDetalle(periodoActual, establecimientoActual);

        // Mostrar modal
        $('#modalProgramacion').modal('show');
    });

    // Cargar tipos de programación al cambiar clase
    $('#id_clase_modal').change(function () {
        const claseId = $(this).val();
        const $tipo = $('#id_tipo_modal');
        $tipo.empty().append('<option value="">- Seleccione -</option>');

        if (claseId) {
            $.get('/programacion_asistencial/listar_tipos/' + claseId, function (data) {
                $.each(data, function (i, item) {
                    $tipo.append($('<option>', {
                        value: item.id_tipo,
                        text: item.nombre_tipo
                    }));
                });
            });
        }
    });

    // Guardar registro
    $('#formModalProgramacion').submit(function (e) {
        e.preventDefault();

        const periodo = $('#modal_periodo').val();
        const id_establecimiento = $('#modal_establecimiento').val();
        const id_medico = $('input[name="id_medico"]').val();
        //alert($("#id_personal").val());
        console.log("Personal" + $("#id_personal").val());
        if (!periodo || !id_establecimiento) {
            alert('Complete periodo e IPRESS en la modal');
            return;
        }

        const data = {
            _token: $('input[name="_token"]').val(),
            periodo: periodo.replace(/\D/g, ''),
            id_establecimiento: id_establecimiento,
            id_personal: $("#id_personal").val(),
            id_clase: $('#id_clase_modal').val(),
            id_tipo: $('#id_tipo_modal').val(),
            responsable_pnp: $('#responsable_pnp').is(':checked') ? 1 : 0,
            responsable_susalud: $('#responsable_susalud').is(':checked') ? 1 : 0,
            horas_programadas: $('#horas_programadas').val(),
            horas_efectivas: $('#horas_efectivas').val()
        };

        const url = $('#id_registro_edit').val() ?
            '/programacion_asistencial/update/' + $('#id_registro_edit').val() :
            '/programacion_asistencial/store';

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                const mensaje = response.message || 'Operación exitosa';
                alert(mensaje);

                // Actualizar detalle en la modal y tabla principal
                cargarDetalle(periodo.replace(/\D/g, ''), id_establecimiento);
                buscarProgramacion();

                // Limpiar solo los campos editables, mantener periodo e IPRESS
                $('#id_clase_modal, #id_tipo_modal, #id_personal_modal').val('').trigger('change');
                $('#id_registro_edit').val('');
                $('#responsable_pnp, #responsable_susalud').prop('checked', false);
                $('#horas_programadas, #horas_efectivas').val('');

                // Poner foco en el primer campo editable
                $('#id_clase_modal').focus();
            },
            error: function (xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || xhr.statusText));
            }
        });
    });


    function buscarProgramacion() {
        const periodo = $('#periodo').val();
        const idEstablecimiento = $('#id_establecimiento_programacion').val();
        const id_medico = $('input[name="id_medico"]').val();
        const periodoActual = periodo ? periodo.replace(/\D/g, '') : '';
        const establecimientoActual = idEstablecimiento;

        $.ajax({
            url: '/programacion_asistencial/buscar',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                periodo: periodoActual,
                id_establecimiento: establecimientoActual,
                id_personal: id_medico
            },
            dataType: 'json',
            beforeSend: function () {
                $('#tablaResumen tbody').html('<tr><td colspan="9" class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</td></tr>');
            },
            success: function (response) {
                if (response.success) {
                    registros = response.data;
                    actualizarTablaResumen(establecimientoActual);
                } else {
                    alert(response.message || 'Error en la respuesta del servidor');
                }
            },
            error: function (xhr, status, error) {
                let errorMsg = 'Error al realizar la búsqueda';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += ': ' + xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMsg += ': ' + xhr.responseText;
                }
                console.error('Detalles del error:', status, error, xhr.responseText);
                alert(errorMsg);
            }
        });
    }

    function cargarDetalle(periodo, idEstablecimiento) {
        $('#tablaDetalle .columna-acciones').show();
        $('#tablaDetalle tbody tr td:nth-child(8)').show();
        const id_medico = $('input[name="id_medico"]').val();
        $.ajax({
            url: '/programacion_asistencial/buscar',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                periodo: periodo,
                id_establecimiento: idEstablecimiento,
                id_personal: id_medico

            },
            success: function (response) {
                const $tbody = $('#tablaDetalle tbody');
                $tbody.empty();

                if (response.success && response.data.length > 0) {
                    response.data.forEach(function (item, index) {
                        const fila = `
                            <tr data-id="${item.id}">
                                <td><input type="checkbox" disabled ${item.responsable_pnp ? 'checked' : ''}></td>
                                <td><input type="checkbox" disabled ${item.responsable_susalud ? 'checked' : ''}></td>
                                <td>${item.clase?.nombre_clase || ''}</td>
                                <td>${item.tipo?.nombre_tipo || ''}</td>
                                
                                <td>${item.horas_programadas}</td>
                                <td>${item.horas_efectivas}</td>
                                <td>
                                    <button class="btn btn-warning btn-xs btn-editar" data-id="${item.id}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-xs btn-eliminar" data-id="${item.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        $tbody.append(fila);
                    });
                } else {
                    $tbody.append('<tr><td colspan="8" class="text-center">No hay registros</td></tr>');
                }
            },
            error: function (xhr) {
                console.error('Error al cargar detalle:', xhr.responseText);
                $('#tablaDetalle tbody').html('<tr><td colspan="8" class="text-center">Error al cargar datos</td></tr>');
            }
        });
    }

    function actualizarTablaResumen(idEstablecimiento) {
        const $tbody = $('#tablaResumen tbody');
        $tbody.empty();

        if (registros.length === 0) {
            $tbody.append('<tr><td colspan="9" class="text-center">No hay registros</td></tr>');
            return;
        }

        // Agrupar por personal, periodo Y establecimiento (clave compuesta)
        const resumen = {};
        registros.forEach(item => {
            const clave = `${item.id_personal}_${item.periodo}_${idEstablecimiento}`; // Clave única combinada

            if (!resumen[clave]) {
                resumen[clave] = {
                    id: item.id,
                    periodo: item.periodo,
                    id_personal: item.id_personal,
                    personal: item.personal?.nombre + ' ' + item.personal?.paterno,
                    horas_programadas: 0,
                    horas_efectivas: 0,
                    count: 0,
                    id_establecimiento: idEstablecimiento // Agregamos el establecimiento aquí
                };
            }

            resumen[clave].horas_programadas += parseFloat(item.horas_programadas) || 0;
            resumen[clave].horas_efectivas += parseFloat(item.horas_efectivas) || 0;
            resumen[clave].count++;
        });

        // Mostrar en tabla (ordenado por periodo y nombre)
        Object.values(resumen)
            .sort((a, b) => {
                // Primero ordenar por periodo (descendente)
                if (a.periodo > b.periodo) return -1;
                if (a.periodo < b.periodo) return 1;

                // Luego por apellido (ascendente)
                return a.personal.localeCompare(b.personal);
            })
            .forEach((item, index) => {
                $tbody.append(`
        <tr data-id="${item.id}" 
            data-id-personal="${item.id_personal}" 
            data-periodo="${item.periodo}" 
            data-establecimiento="${item.id_establecimiento}">
            <td>${index + 1}</td>
            <td>${formatPeriodo(item.periodo)}</td> <!-- Función para formatear periodo -->
            <td>${$('#id_establecimiento_programacion option:selected').text()}</td>
            <td>${item.horas_programadas.toFixed(2)}</td>
            <td>${item.horas_efectivas.toFixed(2)}</td>
            <td><button class="btn btn-primary btn-xs btn-ver"><i class="fa fa-eye"></i></button></td>
            <td><button class="btn btn-warning btn-xs btn-editar-index"><i class="fa fa-edit"></i></button></td>
            <td><button class="btn btn-danger btn-xs btn-eliminar-index"><i class="fa fa-trash"></i></button></td>
        </tr>`);
            });
    }
    // Función auxiliar para formatear periodo (ej: "012024" → "01/2024")
    function formatPeriodo(periodo) {
        if (periodo.length === 6) {
            return `${periodo.substring(0, 2)}/${periodo.substring(2)}`;
        }
        return periodo;
    }
    // Eventos delegados para acciones en la modal
    $(document).on('click', '.btn-editar', function () {
        const id = $(this).data('id');
        const registro = registros.find(r => r.id == id);
        const idEstablecimiento = $(this).data('establecimiento');

        const periodo = $(this).data('periodo'); // "092025"
        const mes = periodo.substring(0, 2);
        const anio = periodo.substring(2);
        const periodoFormateado = mes + '/' + anio;


        if (registro) {
            $('#id_registro_edit').val(registro.id);
            $('#id_clase_modal').val(registro.id_clase).trigger('change');

            // Cargar tipos según clase seleccionada
            $('#id_tipo_modal').empty().append('<option value="">Cargando...</option>');
            $.get('/programacion_asistencial/listar_tipos/' + registro.id_clase, function (data) {
                $('#id_tipo_modal').empty().append('<option value="">- Seleccione -</option>');
                $.each(data, function (i, item) {
                    $('#id_tipo_modal').append($('<option>', {
                        value: item.id_tipo,
                        text: item.nombre_tipo
                    }));
                });

                // Establecer el valor después de que se cargaron las opciones
                $('#id_tipo_modal').val(registro.id_tipo).trigger('change');
            });
            $("#modal_periodo").val(periodoFormateado);
            $('#modal_establecimiento').val(idEstablecimiento).trigger('change');
            $('#id_personal_modal').val(registro.id_personal).trigger('change');
            $('#responsable_pnp').prop('checked', registro.responsable_pnp == 1);
            $('#responsable_susalud').prop('checked', registro.responsable_susalud == 1);
            $('#horas_programadas').val(registro.horas_programadas);

            $('#horas_efectivas').val(registro.horas_efectivas);
        }
    });

    $(document).on('click', '.btn-eliminar', function () {
        if (!confirm('¿Está seguro de eliminar este registro?')) return;

        const id = $(this).data('id');

        $.ajax({
            url: '/programacion_asistencial/eliminar',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id, // Enviamos el ID como parámetro
                periodo: periodoActual,
                id_establecimiento: establecimientoActual
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    cargarDetalle(periodoActual, establecimientoActual);
                    buscarProgramacion();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function (xhr) {
                let errorMsg = 'Error al eliminar: ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else {
                    errorMsg += xhr.statusText;
                }
                alert(errorMsg);
                console.error('Detalles del error:', xhr);
            }
        });
    });
    // Eventos delegados para acciones en el index
    $(document).on('click', '.btn-ver', function () {
        const $tr = $(this).closest('tr');
        const idPersonal = $tr.data('id-personal');
        const periodo = $tr.data('periodo');
        const idEstablecimiento = $('#id_establecimiento_programacion').val();//ojo aqui

        // Limpiar modal y ocultar formulario
        $('#formModalProgramacion')[0].reset();
        $('#formularioModal').hide();
        $('.modal-footer').show();

        // Configurar modal para vista
        $('#modalProgramacion .modal-title').text('Detalle de Programación');
        $('#modalProgramacion .modal-header').removeClass('bg-success').addClass('bg-primary');

        // Mostrar solo los datos relevantes
        $('#modal_periodo').val(periodo);
        $('#modal_establecimiento').val(idEstablecimiento).trigger('change');

        // Cargar solo los registros del personal seleccionado (sin columna de acciones)
        cargarDetallePersonal(periodo.replace(/\D/g, ''), idEstablecimiento, idPersonal, false);

        $('#modalProgramacion').modal('show');
    });

    function cargarDetallePersonal(periodo, idEstablecimiento, idPersonal, mostrarAcciones = true) {
        // Mostrar u ocultar columna de acciones
        if (mostrarAcciones) {
            $('#tablaDetalle .columna-acciones').show();
            $('#tablaDetalle tbody tr td:nth-child(8)').show();
        } else {
            $('#tablaDetalle .columna-acciones').hide();
            $('#tablaDetalle tbody tr td:nth-child(8)').hide();
        }

        $.ajax({
            url: '/programacion_asistencial/buscar',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                periodo: periodo,
                id_establecimiento: idEstablecimiento,
                id_personal: idPersonal
            },
            success: function (response) {
                const $tbody = $('#tablaDetalle tbody');
                $tbody.empty();

                if (response.success && response.data.length > 0) {
                    response.data.forEach(function (item, index) {
                        const acciones = mostrarAcciones ? `
                        <button class="btn btn-warning btn-xs btn-editar" data-id="${item.id}" data-establecimiento="${idEstablecimiento}" data-periodo="${periodo}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-xs btn-eliminar" data-id="${item.id}">
                            <i class="fa fa-trash"></i>
                        </button>` : '';

                        const fila = `
                    <tr data-id="${item.id}" data-establecimiento="${idEstablecimiento}" data-periodo="${periodo}">
                        <td><input type="checkbox" disabled ${item.responsable_pnp ? 'checked' : ''}></td>
                        <td><input type="checkbox" disabled ${item.responsable_susalud ? 'checked' : ''}></td>
                        <td>${item.clase?.nombre_clase || ''}</td>
                        <td>${item.tipo?.nombre_tipo || ''}</td>
                        
                        <td>${item.horas_programadas}</td>
                        <td>${item.horas_efectivas}</td>
                        <td class="columna-acciones">${acciones}</td>
                    </tr>`;
                        $tbody.append(fila);
                    });
                } else {
                    $tbody.append('<tr><td colspan="8" class="text-center">No hay registros</td></tr>');
                }
            },
            error: function (xhr) {
                console.error('Error al cargar detalle:', xhr.responseText);
                $('#tablaDetalle tbody').html('<tr><td colspan="8" class="text-center">Error al cargar datos</td></tr>');
            }
        });
    }
    $(document).on('click', '.btn-editar-index', function () {
        const idPersonal = $(this).closest('tr').data('id-personal');
        periodoActual = $(this).closest('tr').data('periodo');
        establecimientoActual = $('#id_establecimiento_programacion').val();
        idEstablecimiento = $(this).closest('tr').data('establecimiento');

        // Mostrar formulario
        $('#formularioModal').show();
        $('.modal-footer').show();
        $('#modalProgramacion .modal-title').text('Editar Programación');
        $('#modalProgramacion .modal-header').removeClass('bg-primary').addClass('bg-success');

        // Limpiar formulario
        $('#formModalProgramacion')[0].reset();
        $('#id_clase_modal, #id_tipo_modal, #id_personal_modal').val('').trigger('change');
        $('#id_registro_edit').val('');

        // Establecer valores básicos
        const periodoFormateado = periodoActual.replace(/(\d{2})(\d{4})/, '$1/$2');

        // Asignar al campo modal
        $('#modal_periodo').val(periodoFormateado);
        $('#modal_establecimiento').val(establecimientoActual).trigger('change');
        $('#id_personal_modal').val(idPersonal).trigger('change');

        // Cargar todos los registros del personal (con acciones)
        cargarDetallePersonal(periodoActual, establecimientoActual, idPersonal, true);

        $('#modalProgramacion').modal('show');
    });

    $(document).on('click', '.btn-eliminar-index', function () {
        if (!confirm('¿Está seguro de eliminar todos los registros de este personal?')) return;

        const id = $(this).closest('tr').data('id');
        const periodoActual = $(this).closest('tr').data('periodo');
        const establecimientoActual = $('#id_establecimiento_programacion').val();
        const $tr = $(this).closest('tr');
        $.ajax({
            url: '/programacion_asistencial/eliminarTodos',
            type: 'post',
            data: {
                _token: $('input[name="_token"]').val(),
                periodo: periodoActual,
                id_establecimiento: establecimientoActual,
                id_personal: $tr.data('id-personal'),
                delete_all: true
            },
            success: function (response) {
                alert(response.message || 'Registros eliminados');
                buscarProgramacion();
            },
            error: function (xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || xhr.statusText));
            }
        });
    });
});