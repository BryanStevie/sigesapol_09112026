<script>
    $('#btnGuardarIntraOperatorio2').on("click", function(e) {
        e.preventDefault();
        Swal.fire({
            title: '<div class="titulo-padding"><p>Procesando el envío...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            backdrop: true,
            customClass: {
                header: 'swal2-header-generar'
            },
            didOpen: () => {
                Swal.showLoading();
                $('#formIntraoperario2').submit();
            }
        });
    });

    // Manejo del envío del formulario
    $('#formIntraoperario2').on('submit', function(e) {
        e.preventDefault(); // Detiene el envío normal del formulario

        var formData = $(this).serialize(); // Serializa los datos del formulario
        $.ajax({
            url: $(this).attr('action'), // La URL a la que enviar los datos
            method: 'POST', // Método HTTP
            data: formData, // Los datos del formulario serializados
            success: function(response) {
                Swal.close(); // Cierra el loading
                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback')
                    .hide(); // Asegúrate de ocultar todos los mensajes
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Los datos se han guardado correctamente.',
                        timer: 3000, // Tiempo en milisegundos antes de cerrar automáticamente (3 segundos)
                        showConfirmButton: false // Elimina el botón "OK"
                    }).then(() => {
                        $.ajax({
                            url: "/detalle_intraoperario_ajax/" +
                                response.id_intraoperatorio,
                            type: "GET",
                            data: {
                                _token: $('input[name="_token"]').val()
                            },
                            success: function(result) {
                                $('#modal-editar-intraoperatorio')
                                    .on('hidden.bs.modal',
                                        function() {
                                            $('#modal-detalle-intraoperatorio')
                                                .modal({
                                                    backdrop: 'static',
                                                    keyboard: false
                                                });
                                            /* $("#div-modal-detalle-intraoperatorio").html(result); 
                                            $('#modal-detalle-intraoperatorio').modal('show'); */
                                            cargarInformacionDetalleIntraoperario
                                                (result.data);
                                            $('#modal-detalle-intraoperatorio')
                                                .modal('show');
                                        });
                                $('#modal-editar-intraoperatorio')
                                    .modal('hide');
                            }
                        });
                    });
                } else {
                    validaCampos(response);
                    // Muestra un mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.'
                    });
                }
            },
            error: function(xhr) {
                console.log('Ocurrió un error en la solicitud AJAX');
                Swal.close();
                // Manejar el error (ejemplo: mostrar mensaje de error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                });
            }
        });
    });

    /*******************************/
    let procedimientosRegistrados = {!! json_encode($procedimientos_intervencion_intraope) !!};
    
    let recuentoGasas = {!! json_encode($recuento_gasas_enfermeria) !!};
    cargarGasas(recuentoGasas);

    $.each(procedimientosRegistrados, function(index, item) {
        console.log(item);
        agregarProcedimientoExistente(index, item);
    });

    function cargarGasas(datos) {

        $.each(datos, function(i, item) {
            $('#enfermeriauno' + i).val(item.enfermeria1);
            $('#enfermeriados' + i).val(item.enfermeria2);
            $('#id_gasa' + i).val(item.id);
            var total =
                (parseInt(item.enfermeria1) || 0) +
                (parseInt(item.enfermeria2) || 0);
            $('#total' + i).val(total);
            $('#totalx' + i).val(total);
        });

    }

    function agregarProcedimientoExistente(ind, item) {
        var newRow = '';

        newRow += '<tr>';

        newRow += '<td>';
        newRow += '<input type="text" ' +
            'name="nombre_procedimiento[]" ' +
            'id="nombre_procedimiento' + ind + '" ' +
            'class="form-control" ' +
            'value="' + item.codigo_procedimiento + ' - ' + item.descripcion_procedimiento + '">';

        newRow += '<input type="hidden" ' +
            'name="id_procedimiento[]" ' +
            'id="id_procedimiento' + ind + '" ' +
            'class="id_procedimiento" ' +
            'data-id="' + ind + '" ' +
            'value="' + item.id_procedimiento + '">';

        newRow += '<input type="hidden" ' +
            'name="id_procedimiento_existente[]" ' +
            'id="id_procedimiento_existente' + ind + '" ' +
            'class="id_procedimiento" ' +
            'data-id="' + ind + '" ' +
            'value="1">';
        newRow += '</td>';

        newRow += '<td>';

        newRow += '<input type="number" ' +
            'name="procedimiento_cantidad[]" ' +
            'id="procedimiento_cantidad' + ind + '" ' +
            'class="form-control procedimiento_cantidad" ' +
            'value="' + item.cantidad + '">';

        newRow += '</td>';

        newRow += '<td>';

        newRow += '<select ' +
            'class="form-control select-diagnostico-2" ' +
            'id="miSelect2' + ind + '" ' +
            'name="miSelect2[]">' + +'</select>';

        newRow += '</td>';
        newRow += '</tr>';

        $('#tblProcedimiento2 tbody').append(newRow);

        cargarDiagnosticos(ind, item.id_diagnostico);

        configurarAutocompleteProcedimiento(ind);
    }

    function cargarDiagnosticos(ind, idDiagnosticoSeleccionado) {
        var lista_diagnosticos = {!! json_encode($diagnosticos) !!};

        var listIdDiagnosticoObjetos = [];

        $.each(lista_diagnosticos, function(key, value) {

            listIdDiagnosticoObjetos.push({
                id: value.id,
                nombre: value.codigo + ' - ' + value.nombre
            });

        });
        const selectElement = document.getElementById('miSelect2' + ind);

        selectElement.innerHTML = '';

        listIdDiagnosticoObjetos.forEach(function(item) {

            let option = document.createElement('option');

            option.value = item.id;
            option.textContent = item.nombre;

            if (item.id == idDiagnosticoSeleccionado) {
                option.selected = true;
            }

            selectElement.appendChild(option);
        });
    }

    function configurarAutocompleteProcedimiento(ind) {
        var nivel = $('#id_nivel').val();

        var sexo = $('#sexo').val();

        var genero = sexo == 'MASCULINO' ? 1 : 2;

        $("#nombre_procedimiento" + ind).autocomplete({
            minLength: 1,
            delay: 400,
            source: function(request, response) {

                $.ajax({

                    url: '../obtener_procedimiento_convenio/' +
                        request.term + '/' + nivel + '/' + genero + '/1',

                    dataType: 'json',

                    success: function(data) {

                        response(data);
                    }
                });
            },
            select: function(e, ui) {
                $('#id_procedimiento' + ind)
                    .val(ui.item.id);
                $('#nombre_procedimiento' + ind)
                    .val(ui.item.value);
                return false;
            }
        });
    }
</script>