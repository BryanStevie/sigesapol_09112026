$(document).ready(function() {
    // Botón para mostrar las opciones de configuración
    $('#btnShowConfigOptions').click(function() {
        // Realizar la petición AJAX para obtener las opciones
        $.ajax({
            url: '/getAllOptions',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Crear HTML para mostrar en la modal
                let optionsHtml = '<div class="table-responsive">';
                optionsHtml += '<table class="table table-bordered table-striped">';
                optionsHtml += '<thead><tr>';
                optionsHtml += '<th>ID</th>';
                optionsHtml += '<th>Nombre</th>';
                optionsHtml += '<th>Etiqueta</th>';
                optionsHtml += '<th>Tipo</th>';
                optionsHtml += '<th>Valor</th>';
                optionsHtml += '<th>Autoload</th>';
                optionsHtml += '<th>¿Es grupo?</th>';
                optionsHtml += '<th>Dependiente</th>';
                optionsHtml += '<th>Acciones</th>';
                optionsHtml += '</tr></thead>';
                optionsHtml += '<tbody>';
                
                // Iteramos sobre las opciones
                $.each(response, function(index, option) {
                    optionsHtml += '<tr>';
                    optionsHtml += '<td>' + option.id + '</td>';
                    optionsHtml += '<td>' + option.option_name + '</td>';
                    optionsHtml += '<td>' + (option.option_label || '—') + '</td>';
                    optionsHtml += '<td>' + (option.option_type || '—') + '</td>';
                    optionsHtml += '<td>' + (option.option_value || '—') + '</td>';
                    optionsHtml += '<td>' + (option.autoload ? 'Sí' : 'No') + '</td>';
                    optionsHtml += '<td>' + (option.option_grupo ? 'Sí' : 'No') + '</td>';
                    optionsHtml += '<td>' + (option.option_dependiente || '—') + '</td>';
                    optionsHtml += '<td><button class="btn btn-primary btn-sm edit-option" data-id="' + option.id + '">Editar</button></td>';
                    optionsHtml += '</tr>';
                });
                
                optionsHtml += '</tbody></table></div>';
                
                // Mostrar SweetAlert con las opciones
                Swal.fire({
                    title: 'Opciones de Configuración',
                    html: optionsHtml,
                    width: '900px',
                    confirmButtonText: 'Cerrar',
                    customClass: {
                        container: 'swal-wide',
                    },
                    didOpen: () => {
                        // Agregar evento a los botones de editar
                        $('.edit-option').click(function() {
                            // Obtener el ID de la opción
                            const optionId = $(this).data('id');
                            
                            // Encontrar la opción en la respuesta
                            const option = response.find(opt => opt.id == optionId);
                            
                            // Si se encontró la opción, mostrar el formulario de edición
                            if (option) {
                                showEditForm(option, response);
                            }
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                // Mostrar error en caso de fallo
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al cargar las opciones de configuración'
                });
                console.error(error);
            }
        });
    });

    // Función para mostrar el formulario de edición
    function showEditForm(option, allOptions) {
        // Determinar el tipo de input a mostrar basado en option_type
        let inputHtml = '';
        
        // Si el tipo está definido, usar el tipo correspondiente
        if (option.option_type) {
            switch(option.option_type.toLowerCase()) {
                case 'text':
                    inputHtml = '<input type="text" id="edit-value" class="form-control" value="' + (option.option_value || '') + '">';
                    break;
                case 'textarea':
                    inputHtml = '<textarea id="edit-value" class="form-control" rows="5">' + (option.option_value || '') + '</textarea>';
                    break;
                case 'number':
                    inputHtml = '<input type="number" id="edit-value" class="form-control" value="' + (option.option_value || '') + '">';
                    break;
                case 'select':
                    // Asumir que option_value contiene opciones separadas por comas o un valor seleccionado
                   inputHtml = '<input type="text" id="edit-value" class="form-control" value="' + (option.option_value || '') + '">';
                    break;
                case 'checkbox':
                    const isChecked = option.option_value === '1' || option.option_value === 'true' || option.option_value === true;
                    inputHtml = '<div class="checkbox"><label>';
                    inputHtml += '<input type="checkbox" id="edit-value" ' + (isChecked ? 'checked' : '') + '> Activar';
                    inputHtml += '</label></div>';
                    break;
                case 'radio':
                    // Manejar opciones de radio
                    if (option.option_value && option.option_value.includes(',')) {
                        const radioOptions = option.option_value.split(',');
                        const currentValue = option.current_value || radioOptions[0].trim();
                        
                        inputHtml = '<div>';
                        radioOptions.forEach(function(optValue) {
                            const trimmedValue = optValue.trim();
                            inputHtml += '<div class="radio"><label>';
                            inputHtml += '<input type="radio" name="edit-radio-value" value="' + trimmedValue + '" ' + 
                                (trimmedValue === currentValue ? 'checked' : '') + '>';
                            inputHtml += ' ' + trimmedValue;
                            inputHtml += '</label></div>';
                        });
                        inputHtml += '</div>';
                    } else {
                        inputHtml = '<div class="radio"><label>';
                        inputHtml += '<input type="radio" name="edit-radio-value" value="' + (option.option_value || '1') + '" checked> ' + (option.option_value || 'Opción');
                        inputHtml += '</label></div>';
                    }
                    break;
                case 'date':
                    inputHtml = '<input type="date" id="edit-value" class="form-control" value="' + (option.option_value || '') + '">';
                    break;
                case 'time':
                    inputHtml = '<input type="time" id="edit-value" class="form-control" value="' + (option.option_value || '') + '">';
                    break;
                case 'color':
                    inputHtml = '<input type="color" id="edit-value" class="form-control" value="' + (option.option_value || '#000000') + '">';
                    break;
                default:
                    // Por defecto, usar input text
                    inputHtml = '<input type="text" id="edit-value" class="form-control" value="' + (option.option_value || '') + '">';
            }
        } else {
            // Si no hay tipo definido, usar input text
            inputHtml = '<input type="text" id="edit-value" class="form-control" value="' + (option.option_value || '') + '">';
        }
        
        // Crear formulario HTML
        let formHtml = '<form id="edit-option-form">';
        formHtml += '<div class="form-group">';
        formHtml += '<label for="edit-name">Nombre:</label>';
        formHtml += '<input type="text" id="edit-name" class="form-control" value="' + option.option_name + '" readonly>';
        formHtml += '</div>';
        
        formHtml += '<div class="form-group">';
        formHtml += '<label for="edit-label">Etiqueta:</label>';
        formHtml += '<input type="text" id="edit-label" class="form-control" value="' + (option.option_label || '') + '">';
        formHtml += '</div>';
        
        formHtml += '<div class="form-group">';
        formHtml += '<label for="edit-type">Tipo:</label>';
        formHtml += '<select id="edit-type" class="form-control">';
        
        // Opciones de tipo
        const types = ['text', 'textarea', 'number', 'select', 'checkbox', 'radio', 'date', 'time', 'color'];
        types.forEach(function(type) {
            formHtml += '<option value="' + type + '"' + (option.option_type === type ? ' selected' : '') + '>' + type.charAt(0).toUpperCase() + type.slice(1) + '</option>';
        });
        
        formHtml += '</select>';
        formHtml += '</div>';
        
        formHtml += '<div class="form-group">';
        formHtml += '<label for="edit-value">' + (option.option_label || 'Valor') + ':</label>';
        formHtml += inputHtml;
        formHtml += '</div>';
        
        formHtml += '<div class="checkbox">';
        formHtml += '<label>';
        formHtml += '<input type="checkbox" id="edit-autoload"' + (option.autoload ? ' checked' : '') + '> Autoload';
        formHtml += '</label>';
        formHtml += '</div>';
        
        // Añadir opción para grupo
        formHtml += '<div class="checkbox">';
        formHtml += '<label>';
        formHtml += '<input type="checkbox" id="edit-grupo"' + (option.option_grupo ? ' checked' : '') + '> Es un grupo';
        formHtml += '</label>';
        formHtml += '</div>';
        
        // Añadir selector para dependencia de grupo
        formHtml += '<div class="form-group" id="grupo-dependiente-container"' + (!option.option_grupo ? ' style="display:none;"' : '') + '>';
        formHtml += '<label for="edit-dependiente">Pertenece al grupo:</label>';
        formHtml += '<select id="edit-dependiente" class="form-control">';
        formHtml += '<option value="">Ninguno</option>';
        
        // Listar solo opciones que son grupos
        const grupos = allOptions.filter(opt => opt.option_grupo === true && opt.id !== option.id);
        $.each(grupos, function(index, grupo) {
            formHtml += '<option value="' + grupo.id + '"' + (option.option_dependiente == grupo.id ? ' selected' : '') + '>' 
                      + (grupo.option_label || grupo.option_name) + '</option>';
        });
        
        formHtml += '</select>';
        formHtml += '</div>';
        
        formHtml += '</form>';
        
        // Mostrar SweetAlert con el formulario
        Swal.fire({
            title: 'Editar Opción: ' + option.option_name,
            html: formHtml,
            width: '500px',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                // Capturar valores del formulario
                let value;
                
                // Obtener el valor según el tipo de input
                const type = $('#edit-type').val();
                if (type === 'checkbox') {
                    value = $('#edit-value').is(':checked') ? '1' : '0';
                } else if (type === 'radio') {
                    value = $('input[name="edit-radio-value"]:checked').val() || '';
                } else {
                    value = $('#edit-value').val();
                }
                
                // Crear objeto con los datos a enviar
                return {
                    id: option.id,
                    option_name: $('#edit-name').val(),
                    option_label: $('#edit-label').val(),
                    option_type: type,
                    option_value: value,
                    autoload: $('#edit-autoload').is(':checked') ? 1 : 0,
                    option_grupo: $('#edit-grupo').is(':checked') ? 1 : 0,
                    option_dependiente: $('#edit-grupo').is(':checked') ? null : $('#edit-dependiente').val()
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar datos al servidor
                saveOption(result.value);
            }
        });
        
        // Evento para mostrar/ocultar selector de grupo dependiente
        $('#edit-grupo').change(function() {
            if ($(this).is(':checked')) {
                $('#grupo-dependiente-container').hide();
                $('#edit-dependiente').val('');
            } else {
                $('#grupo-dependiente-container').show();
            }
        });
        
        // Evento para cambiar el tipo de input
        $('#edit-type').change(function() {
            const selectedType = $(this).val();
            const currentValue = option.option_value || '';
            
            // Regenerar el HTML del input según el tipo seleccionado
            let newInputHtml = '';
            
            switch(selectedType) {
                case 'text':
                    newInputHtml = '<input type="text" id="edit-value" class="form-control" value="' + currentValue + '">';
                    break;
                case 'textarea':
                    newInputHtml = '<textarea id="edit-value" class="form-control" rows="5">' + currentValue + '</textarea>';
                    break;
                case 'number':
                    newInputHtml = '<input type="number" id="edit-value" class="form-control" value="' + currentValue + '">';
                    break;
                case 'select':
                    newInputHtml = '<input type="text" id="edit-value" class="form-control" value="' + currentValue + '">';
                    break;
                case 'checkbox':
                    const isChecked = currentValue === '1' || currentValue === 'true' || currentValue === true;
                    newInputHtml = '<div class="checkbox"><label>';
                    newInputHtml += '<input type="checkbox" id="edit-value" ' + (isChecked ? 'checked' : '') + '> Activar';
                    newInputHtml += '</label></div>';
                    break;
                case 'radio':
                    if (currentValue && currentValue.includes(',')) {
                        const radioOptions = currentValue.split(',');
                        newInputHtml = '<div>';
                        radioOptions.forEach(function(optValue, idx) {
                            const trimmedValue = optValue.trim();
                            newInputHtml += '<div class="radio"><label>';
                            newInputHtml += '<input type="radio" name="edit-radio-value" value="' + trimmedValue + '"' + (idx === 0 ? ' checked' : '') + '>';
                            newInputHtml += ' ' + trimmedValue;
                            newInputHtml += '</label></div>';
                        });
                        newInputHtml += '</div>';
                    } else {
                        newInputHtml = '<div class="radio"><label>';
                        newInputHtml += '<input type="radio" name="edit-radio-value" value="' + (currentValue || '1') + '" checked> ' + (currentValue || 'Opción');
                        newInputHtml += '</label></div>';
                    }
                    break;
                case 'date':
                    newInputHtml = '<input type="date" id="edit-value" class="form-control" value="' + currentValue + '">';
                    break;
                case 'time':
                    newInputHtml = '<input type="time" id="edit-value" class="form-control" value="' + currentValue + '">';
                    break;
                case 'color':
                    newInputHtml = '<input type="color" id="edit-value" class="form-control" value="' + (currentValue || '#000000') + '">';
                    break;
                default:
                    newInputHtml = '<input type="text" id="edit-value" class="form-control" value="' + currentValue + '">';
            }
            
            // Reemplazar el input anterior con el nuevo
            $('#edit-value').closest('.form-group').html('<label for="edit-value">' + (option.option_label || 'Valor') + ':</label>' + newInputHtml);
        });
    }
    
    // Función para guardar la opción editada
    function saveOption(data) {
        $.ajax({
            url: '/updateOption',
            type: 'POST',
            dataType: 'json',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Guardado',
                        text: 'La opción ha sido actualizada correctamente',
                        timer: 1500
                    }).then(() => {
                        // Volver a cargar las opciones para refrescar la tabla
                        $('#btnShowConfigOptions').click();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Ocurrió un error al guardar la opción'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Mostrar mensaje de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al guardar la opción: ' + error
                });
                console.error(error);
            }
        });
    }

    // Botón para mostrar configuración de laboratorio
    $('#btnLaboratorioConfig').click(function() {
        // Mostrar loading
        Swal.fire({
            title: 'Cargando...',
            text: 'Obteniendo opciones de configuración',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Petición AJAX para obtener las opciones
        $.ajax({
            url: '/getOptionsByPrefix',
            type: 'GET',
            data: {
                prefix: 'laboratorio_'
            },
            dataType: 'json',
            success: function(response) {
                if (response.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin datos',
                        text: 'No hay opciones de configuración disponibles para Laboratorio'
                    });
                    return;
                }
                
                showConfigForm(response);
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al cargar las opciones: ' + error
                });
                console.error(error);
            }
        });
    });
    
    // Función para mostrar el formulario de configuración
    function showConfigForm(options) {
        // Crear formulario HTML
        let formHtml = '<form id="laboratorioForm">';
        
        // Primero identificar grupos
        let grupos = options.filter(option => option.option_grupo === true);
        
        // Mostrar opciones que no pertenecen a grupos
        let opcionesSinGrupo = options.filter(option => 
            !option.option_dependiente && option.option_grupo !== true);
        
        $.each(opcionesSinGrupo, function(index, option) {
            formHtml += createFieldByType(option);
        });
        
        // Mostrar grupos y sus opciones dependientes
        $.each(grupos, function(index, grupo) {
            formHtml += '<fieldset class="border p-3 mt-3">';
            formHtml += '<legend>' + (grupo.option_label || grupo.option_name.replace('laboratorio_', '').replace(/_/g, ' ')) + '</legend>';
            
            // Mostrar opciones dependientes
            let opcionesDependientes = options.filter(option => 
                option.option_dependiente == grupo.id);
            
            $.each(opcionesDependientes, function(index, option) {
                formHtml += createFieldByType(option);
            });
            
            formHtml += '</fieldset>';
        });
        
        formHtml += '</form>';
        
        // Mostrar SweetAlert con el formulario
        Swal.fire({
            title: 'Configuración de Laboratorio',
            html: formHtml,
            width: '650px',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            customClass: {
                container: 'swal-wide'
            },
            preConfirm: () => {
                // Recolectar datos del formulario
                let formData = {};
                
                $.each(options, function(index, option) {
                    let value;
                    
                    // Obtener el valor según el tipo de campo
                    if (option.option_type === 'checkbox') {
                        value = $('#option_' + option.id).is(':checked') ? '1' : '0';
                    } else if (option.option_type === 'radio') {
                        value = $('input[name="radio_' + option.id + '"]:checked').val() || '';
                    } else {
                        value = $('#option_' + option.id).val();
                    }
                    
                    // Agregar al objeto de datos
                    formData[option.id] = value;
                });
                
                return formData;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                saveOptions(result.value);
            }
        });
    }
    
    // Función para crear un campo según su tipo
    function createFieldByType(option) {
        let fieldHtml = '<div class="form-group">';
        let label = option.option_label || option.option_name.replace('laboratorio_', '').replace(/_/g, ' ');
        
        // Convertir primera letra a mayúscula
        label = label.charAt(0).toUpperCase() + label.slice(1);
        
        // Agregar label
        fieldHtml += '<label for="option_' + option.id + '">' + label + '</label>';
        
        // Determinar el tipo de input y crear el HTML correspondiente
        if (option.option_type) {
            switch(option.option_type.toLowerCase()) {
                case 'text':
                    fieldHtml += '<input type="text" id="option_' + option.id + '" class="form-control" value="' + (option.option_value || '') + '">';
                    break;
                    
                case 'textarea':
                    fieldHtml += '<textarea id="option_' + option.id + '" class="form-control" rows="4">' + (option.option_value || '') + '</textarea>';
                    break;
                    
                case 'number':
                    fieldHtml += '<input type="number" id="option_' + option.id + '" class="form-control" value="' + (option.option_value || '0') + '">';
                    break;
                    
                case 'select':
                    fieldHtml += '<select id="option_' + option.id + '" class="form-control">';
                    
                    // Si hay opciones en el option_value (separadas por comas)
                    if (option.option_value && option.option_value.includes(';')) {
                        const options = option.option_value.split(';');
                        const currentValue = option.current_value || '';
                        
                        options.forEach(function(optValue) {
                            const trimmedValue = optValue.trim();
                            fieldHtml += '<option value="' + trimmedValue + '"' + 
                                (trimmedValue === currentValue ? ' selected' : '') + '>' + 
                                trimmedValue + '</option>';
                        });
                    } else {
                        fieldHtml += '<option value="' + (option.option_value || '') + '">' + (option.option_value || 'Seleccione una opción') + '</option>';
                    }
                    
                    fieldHtml += '</select>';
                    break;
                    
                case 'checkbox':
                    const isChecked = option.option_value === '1' || option.option_value === 'true' || option.option_value === true;
                    fieldHtml += '<div class="checkbox"><label>';
                    fieldHtml += '<input type="checkbox" id="option_' + option.id + '" ' + (isChecked ? 'checked' : '') + '>';
                    fieldHtml += ' Activado';
                    fieldHtml += '</label></div>';
                    break;
                    
                case 'radio':
                    // Similar a select, asumimos opciones separadas por comas
                    if (option.option_value && option.option_value.includes(',')) {
                        const options = option.option_value.split(',');
                        const currentValue = option.current_value || options[0].trim();
                        
                        fieldHtml += '<div>';
                        options.forEach(function(optValue) {
                            const trimmedValue = optValue.trim();
                            fieldHtml += '<div class="radio"><label>';
                            fieldHtml += '<input type="radio" name="radio_' + option.id + '" id="option_' + option.id + '_' + trimmedValue + '" value="' + trimmedValue + '" ' + 
                                (trimmedValue === currentValue ? 'checked' : '') + '>';
                            fieldHtml += ' ' + trimmedValue;
                            fieldHtml += '</label></div>';
                        });
                        fieldHtml += '</div>';
                    } else {
                        fieldHtml += '<div class="radio"><label>';
                        fieldHtml += '<input type="radio" name="radio_' + option.id + '" id="option_' + option.id + '" value="' + (option.option_value || '1') + '" checked>';
                        fieldHtml += ' ' + (option.option_value || 'Opción');
                        fieldHtml += '</label></div>';
                    }
                    break;
                    
                case 'date':
                    fieldHtml += '<input type="date" id="option_' + option.id + '" class="form-control" value="' + (option.option_value || '') + '">';
                    break;
                    
                case 'time':
                    fieldHtml += '<input type="time" id="option_' + option.id + '" class="form-control" value="' + (option.option_value || '') + '">';
                    break;
                    
                case 'color':
                    fieldHtml += '<input type="color" id="option_' + option.id + '" class="form-control" value="' + (option.option_value || '#000000') + '">';
                    break;
                    
                default:
                    // Por defecto, usar input text
                    fieldHtml += '<input type="text" id="option_' + option.id + '" class="form-control" value="' + (option.option_value || '') + '">';
            }
        } else {
            // Si no hay tipo definido, usar input text
            fieldHtml += '<input type="text" id="option_' + option.id + '" class="form-control" value="' + (option.option_value || '') + '">';
        }
        
        // Si hay un nombre de opción, mostrarlo como ayuda
        if (option.option_name) {
            fieldHtml += '<small class="form-text text-muted">' + option.option_name + '</small>';
        }
        
        fieldHtml += '</div>';
        
        return fieldHtml;
    }
    
    // Función para guardar las opciones
    function saveOptions(optionsData) {
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Guardando...',
            text: 'Guardando configuración, por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Enviar datos al servidor
        $.ajax({
            url: '/saveOptions',
            type: 'POST',
            dataType: 'json',
            data: {
                options: optionsData,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Configuración Guardada',
                        text: response.message,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Ocurrió un error al guardar la configuración'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al guardar la configuración: ' + error
                });
                console.error(error);
            }
        });
    }
    
    // Botón para agregar una nueva opción de configuración
    $('#btnAddNewOption').click(function() {
        showNewOptionForm();
    });
    
    // Función para mostrar el formulario de nueva opción
    function showNewOptionForm() {
        // Obtener grupos para el selector de dependencia
        $.ajax({
            url: '/getGroupOptions',
            type: 'GET',
            dataType: 'json',
            success: function(grupos) {
                // Crear el formulario
                let formHtml = '<form id="new-option-form">';
                
                formHtml += '<div class="form-group">';
                formHtml += '<label for="new-name">Nombre:</label>';
                formHtml += '<input type="text" id="new-name" class="form-control" placeholder="Ej: sistema_tiempo_sesion">';
                formHtml += '<small class="form-text text-muted">Nombre único para identificar la opción (sin espacios, usar guion bajo)</small>';
                formHtml += '</div>';
                
                formHtml += '<div class="form-group">';
                formHtml += '<label for="new-label">Etiqueta:</label>';
                formHtml += '<input type="text" id="new-label" class="form-control" placeholder="Ej: Tiempo de sesión">';
                formHtml += '<small class="form-text text-muted">Texto que se mostrará como etiqueta del campo</small>';
                formHtml += '</div>';
                
                formHtml += '<div class="form-group">';
                formHtml += '<label for="new-type">Tipo:</label>';
                formHtml += '<select id="new-type" class="form-control">';
                
                // Opciones de tipo
                const types = ['text', 'textarea', 'number', 'select', 'checkbox', 'radio', 'date', 'time', 'color'];
                types.forEach(function(type) {
                    formHtml += '<option value="' + type + '">' + type.charAt(0).toUpperCase() + type.slice(1) + '</option>';
                });
                
                formHtml += '</select>';
                formHtml += '</div>';
                
                formHtml += '<div class="form-group">';
                formHtml += '<label for="new-value">Valor:</label>';
                formHtml += '<input type="text" id="new-value" class="form-control" placeholder="Valor inicial">';
                formHtml += '<small class="form-text text-muted">Para select,cheack separe con ";" las opciones </small>';
                formHtml += '</div>';
                
                formHtml += '<div class="checkbox">';
                formHtml += '<label>';
                formHtml += '<input type="checkbox" id="new-autoload" checked> Autoload';
                formHtml += '</label>';
                formHtml += '</div>';
                
                formHtml += '<div class="checkbox">';
                formHtml += '<label>';
                formHtml += '<input type="checkbox" id="new-grupo"> Es un grupo';
                formHtml += '</label>';
                formHtml += '</div>';
                
                formHtml += '<div class="form-group" id="new-grupo-dependiente-container">';
                formHtml += '<label for="new-dependiente">Pertenece al grupo:</label>';
                formHtml += '<select id="new-dependiente" class="form-control">';
                formHtml += '<option value="">Ninguno</option>';
                
                // Listar grupos
                $.each(grupos, function(index, grupo) {
                    formHtml += '<option value="' + grupo.id + '">' + (grupo.option_label || grupo.option_name) + '</option>';
                });
                
                formHtml += '</select>';
                formHtml += '</div>';
                
                formHtml += '</form>';
                
                // Mostrar SweetAlert con el formulario
                Swal.fire({
                    title: 'Agregar Nueva Opción',
                    html: formHtml,
                    width: '500px',
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                    didOpen: () => {
                        // Mostrar/ocultar selector de dependencia según el estado del checkbox de grupo
                        $('#new-grupo').change(function() {
                            if ($(this).is(':checked')) {
                                $('#new-grupo-dependiente-container').hide();
                                $('#new-dependiente').val('');
                            } else {
                                $('#new-grupo-dependiente-container').show();
                            }
                        });
                        
                        // Cambiar el tipo de input cuando cambia el tipo seleccionado
                        $('#new-type').change(function() {
                            const selectedType = $(this).val();
                            let placeholder = 'Valor inicial';
                            
                            if (selectedType === 'select' || selectedType === 'radio') {
                                placeholder = 'Opción1, Opción2, Opción3';
                            } else if (selectedType === 'checkbox') {
                                placeholder = '1 para activado, 0 para desactivado';
                            } else if (selectedType === 'date') {
                                placeholder = 'YYYY-MM-DD';
                            } else if (selectedType === 'time') {
                                placeholder = 'HH:MM';
                            } else if (selectedType === 'color') {
                                placeholder = '#000000';
                            }
                            
                            $('#new-value').attr('placeholder', placeholder);
                        });
                    },
                    preConfirm: () => {
                        // Validar campos obligatorios
                        const optionName = $('#new-name').val().trim();
                        if (!optionName) {
                            Swal.showValidationMessage('El nombre de la opción es obligatorio');
                            return false;
                        }
                        
                        // Capturar valores del formulario
                        let data = {
                            option_name: optionName,
                            option_label: $('#new-label').val().trim(),
                            option_type: $('#new-type').val(),
                            option_value: $('#new-value').val(),
                            autoload: $('#new-autoload').is(':checked') ? 1 : 0,
                            option_grupo: $('#new-grupo').is(':checked') ? 1 : 0,
                            option_dependiente: $('#new-grupo').is(':checked') ? null : $('#new-dependiente').val()
                        };
                        
                        return data;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Guardar la nueva opción
                        saveNewOption(result.value);
                    }
                });
            },
            error: function(xhr, status, error) {
                // Mostrar error en caso de fallo
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al cargar los grupos: ' + error
                });
                console.error(error);
            }
        });
    }
    
    // Función para guardar una nueva opción
    function saveNewOption(data) {
        $.ajax({
            url: '/createOption',
            type: 'POST',
            dataType: 'json',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Guardado',
                        text: 'La opción ha sido creada correctamente',
                        timer: 1500
                    }).then(() => {
                        // Volver a cargar las opciones para refrescar la tabla
                        $('#btnShowConfigOptions').click();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Ocurrió un error al crear la opción'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Mostrar mensaje de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al crear la opción: ' + error
                });
                console.error(error);
            }
        });
    }
});
        