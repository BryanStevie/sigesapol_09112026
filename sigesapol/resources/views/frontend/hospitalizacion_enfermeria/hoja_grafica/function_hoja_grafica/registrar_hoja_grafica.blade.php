<script>

// PARA HACER EL CALCULO DE DISTOLICA ENTRE SISDOLICA 

function agregarEventos() {
    $('input[id^="caracter_fisico_"]').on('input', calcularPresionMedia);
}

function calcularPresionMedia() {
    let sistolica = parseFloat($('input[name="caracter_fisico_valor_8"]').val());
    let diastolica = parseFloat($('input[name="caracter_fisico_valor_9"]').val());
    let mediaInput = $('input[name="caracter_fisico_valor_10"]');
    mediaInput.prop('readonly', true);

    if (!isNaN(sistolica) && !isNaN(diastolica) && mediaInput.length) {
        let media = (((sistolica + diastolica) * 2) / 3);
        mediaInput.val(media.toFixed(2));
    } else if (mediaInput.length) {
        mediaInput.val('');
    }
}

function validarSoloEnteros(element) {
    $(element).on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
}



    $('#add_RITMO_CARDIACO').on('click', function() {

        lista_table_tr_campo_detalle(
            'tbl_RITMO_CARDIACO',
            'descripcion_RITMO_CARDIACO',
            'descripcion_detalle_RITMO_CARDIACO',
            'id_relacionado_RITMO_CARDIACO',
            'boton_eliminar_item_RITMO_CARDIACO',
            'RITMO_CARDIACO',
            'input',
            'null',
            'null',
            'SI'
        );

    });

    $('#add_MODO_MONITOREO').on('click', function() {

        lista_table_tr_campo_detalle(
            'tbl_MODO_MONITOREO',
            'descripcion_MODO_MONITOREO',
            'descripcion_detalle_MODO_MONITOREO',
            'id_relacionado_MODO_MONITOREO',
            'boton_eliminar_item_MODO_MONITOREO',
            'MODO_VENTILATORIO',
            'input',
            'null',
            'null',
            'SI'
        );
        delete_table_tr_campo('tbl_RITMO_CARDIACO', 'boton_eliminar_item_RITMO_CARDIACO', '');
        delete_table_tr_campo('tbl_MODO_MONITOREO', 'boton_eliminar_item_MODO_MONITOREO', '');
    });




    $('#crearHojaGrafica').click(function() {

        $('#id').val('');
        $("#registrarHojaGrafica").attr("id", "registrarHojaGrafica");
        $("#registrarHojaGrafica").attr("disabled", false);
        $('#FormularioHojaGrafica').trigger("reset");
        $('#tituloFormularioHojaGrafica').html("Registro de Control de Hoja Gráfica");
        $('#modalHojaGrafica').modal('show');

        $("#tbl_RITMO_CARDIACO tbody").empty();
        $("#tbl_MODO_MONITOREO tbody").empty();

    });

   

    // Evento para el botón "NUEVO REGISTRO"
    // Evento para el botón "NUEVO REGISTRO"



    // ESTO UTILIZO PARA TRAER EL ENCABEZADO DEL ASEGURADO AL DARLE CLICK EN NUEVOV REGISTRO 
    //


    $('#crearHojaGrafica').click(async function(e) {
        let id_asegurado = $('#id_asegurado_hiden').val();
        console.log('ID Asegurado:', id_asegurado); // Para depuración
        try {
            let response = await getValues(id_asegurado);
            // Rellenar datos del paciente
            $('#id_enfermeria_register').val(response.paciente.id_enfermeria);
            $('#paciente2').val(response.paciente.nombre);
            $('#document2').val(response.paciente.nro_doc_ident);
            $('#parentesco2').val(response.paciente.parentesgo);
            $('#sexo2').val(response.paciente.sexo);
            $('#edad_corta2').val(response.paciente.edad_corta);
            $('#tipo_sangre2').val(response.paciente.tipo_sangre);
            $('#nro_historia2').val(response.paciente.nro_historia);
            $('#cip2').val(response.paciente.cip);

            // Agregar dinámicamente los campos de caracteres físicos
            $('#signosVitalesContainer').empty();

// Mostrar todos los inputs dinámicamente
let caracteresFisicoArray = response.caracteres_fisico;
for (let id in caracteresFisicoArray) {
    let descripcion = caracteresFisicoArray[id];
    let readonlyAttribute = (id == 10) ? 'readonly' : ''; // Solo el id 10 será readonly
    let campo = `
    <div class="form-group col-md-4">
        <label for="caracter_fisico_${id}">${descripcion}</label>
        <input type="hidden" name="caracter_fisico[]" value="${id}">
        <input type="text" class="form-control" id="caracter_fisico_${id}" name="caracter_fisico_valor_${id}">
    </div>`;
    $('#signosVitalesContainer').append(campo);
}

// Agregar eventos a los nuevos inputs
agregarEventos();

// Validar solo enteros en los inputs
validarSoloEnteros('input[id^="caracter_fisico_"]');

// Mostrar el modal
$('#modalHojaGrafica').modal('show');
} catch (error) {
console.error('Error al obtener valores:', error);
}
});

    //$(document).on('click', '.view-btn', function() {
    //    var id = $(this).data('id');
    // Aquí puedes redirigir a la ruta o abrir un modal
    //    window.location.href = '/ver_registro_nuevo/' + id;
    // o para abrir un modal, usa una función específica para cargar el contenido del modal
    // loadModalContent(id);
    //});
    // Evento para enviar el formulario y guardar signos vitales
    // Evento para enviar el formulario y guardar signos vitales
    $('#registrarHojaGrafica').click(async function(e) {
        e.preventDefault(); // Prevenir el comportamiento por defecto del formulario

        var formData = $('#FormularioHojaGrafica').serialize(); // Serializa el formulario
        console.log(formData);
        let id_asegurado = $('#id_asegurado_hiden').val();
        try {
            let response = await $.ajax({
                url: '/guardarSignosVitales',
                type: 'POST',
                data: formData,
            });

            console.log('Signos vitales guardados:', response);

            // Hacer petición para traer datos para la tabla
            let signos_response = await getDataTable(id_asegurado);
            console.log(signos_response);

            // Llenar tabla
            let tbody = $('#bodyEmergenciaTriaje');
            tbody.empty(); // Limpiar el contenido previo de la tabla

            if (signos_response && Array.isArray(signos_response.data) && signos_response.data.length > 0) {
                let signos = signos_response.data;
                let filas = '';

                for (let signo of signos) {
                    // Verificar si hay detalles en signo_vital_detalle
                    if (signo.signo_vital_detalle && signo.signo_vital_detalle.length > 0) {
                        let fechaAtencion = signo.fecha_atencion || '';
                        let idEnfermeria = signo.id_enfermeria || '';
                        let ubicacion = signo.ubicacion || '';

                        let detalles = signo.signo_vital_detalle.reduce((acc, detalle) => {
                            // Verificar que detalle y detalle.caracter_fisico no sean nulos
                            if (detalle && detalle.caracter_fisico) {
                                acc[detalle.caracter_fisico.descripcion] = detalle.descripcion || '';
                            }
                            return acc;
                        }, {});

                        let fila = `
                        <tr>
                    <td></td>
                    <td>${fechaAtencion}</td>
                    <td>${idEnfermeria}</td>
                    <td>${ubicacion}</td>
                    <td>${detalles.Respiratorio || ''}</td>
                    <td>${detalles.pulso || ''}</td>
                    <td>${detalles.sistolica_ ? detalles.sistolica_ : ''} / ${detalles.diastolica_ ? detalles.diastolica_ : ''}</td>
                    <td>${detalles.temperatura || ''}</td>
                    <td>${detalles.peso || ''}</td>
                    <td>${detalles.diuresis || ''}</td>
                    <td>${detalles.deposicion || ''}</td>
                    <td>${detalles.drenaje || ''}</td>
                    <td>${detalles.turno || ''}</td>
                    <td>${detalles.bh || ''}</td>
                    <td><button class="btn btn-primary btn-sm view-btn" data-id="${signo.id}"><i class="fa fa-eye"></i></button></td>
                </tr>`;

                        filas += fila;
                    }
                }

                // Solo agregar filas si hay contenido
                if (filas) {
                    tbody.append(filas);
                }
            }

            $('#modalHojaGrafica').modal('hide'); // Cierra el modal
            swal({
                buttons: false,
                title: "Registro Exitoso",
                text: "Datos registrados exitosamente.",
                icon: "success",
                timer: 1500,
            });
        } catch (error) {
            console.error('Error al guardar signos vitales:', error);

            $('#registrarHojaGrafica').attr('disabled', false);

            swal({
                buttons: false,
                title: "Error",
                text: "Ocurrió un error al registrar los datos.",
                icon: "error",
                timer: 1500,
            });
        }
    });


    async function getDataTable(id_asegurado) {
        try {
            const response = await $.ajax({
                url: '/signosVitalesAsegurado/' + id_asegurado,
                type: 'GET'
            });
            //console.log(response); // Para depuración: muestra la respuesta completa en la consola
            return response;
        } catch (xhr) {
            console.error(xhr.responseText);
            throw xhr.responseText;
        }
    }




    $(document).ready(function() {


    });
</script>
