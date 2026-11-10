<script>
    $('#fecha_hoja_grafica_inicial').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    }).on('changeDate', function(e) {
        console.log('click sobre fecha_hoja_grafica_inicial');
    });

    $('#fecha_hoja_grafica_final').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    }).on('changeDate', function(e) {
        console.log('click sobre fecha_hoja_grafica_final');
    });


    $('body').on('click', '#crearRegistro', async function() {
    // Código para crear el registro...
    
    // Después de crear el registro, actualiza la tabla
    try {
        let id_asegurado = $('#id_asegurado_hiden').val();
        let signos_response = await getDataTable(id_asegurado);

        let tbody = $('#bodyEmergenciaTriaje');
        tbody.empty(); // Limpiar contenido previo

        if (signos_response && Array.isArray(signos_response.data) && signos_response.data.length > 0) {
            let signos = signos_response.data;
            for (let signo of signos) {
                // Construir fila de tabla como antes
                let fila = `
                    <tr>
                        <td></td>
                        <td>${signo.fecha_atencion || ''}</td>
                        <td>${signo.user_name || ''}</td>
                        <td>${(signo.nombre_atencion || '') + ' ' + (signo.cuartos_nombre || '') + ' ' + (signo.nombre || '')}</td>
                        <td>${signo.signo_vital_detalle.find(detalle => detalle.caracter_fisico.descripcion === 'Respiratorio')?.descripcion || ''}</td>
                        <!-- Más celdas -->
                        <td><button class="btn btn-primary btn-sm view-btn" data-id="${signo.id}"><i class="fa fa-eye"></i></button></td>
                    </tr>`;
                tbody.append(fila);
            }
        }

    } catch (error) {
        console.error(error);
    }
});

    /*=============================================
    AJAX PARA crear 
    =============================================*/
    $('body').on('click', '#listarHojaGrafica', async function() {
        var id_hospitalizacion = $(this).data('id');
        var id_asegurado = $(this).data('id_asegurado');
        console.log(id_asegurado);
        $('#FormularioListarHojaGrafica').trigger("reset");
        $('#tituloFormularioListarHojaGrafica').html("Lista de Signos Vitales");

        try {
            let response = await getValues(id_asegurado);

            // Rellenar datos del paciente
            let paciente = response.paciente;
            $('#document').val(paciente.nro_doc_ident);
            $('#paciente').val(paciente.nombre);
            $('#parentesco').val(paciente.parentesgo);
            $('#sexo').val(paciente.sexo);
            $('#edad_corta').val(paciente.edad_corta);
            $('#tipo_sangre').val(paciente.tipo_sangre);
            $('#nro_historia').val(paciente.nro_historia);
            $('#cip').val(paciente.cip);

            // setear el id asegurado en el otro modal
            $('#id_asegurado_hiden').val(id_asegurado);

            // Agregar dinámicamente los campos de caracteres físicos
            // $('#signosVitalesContainer').empty(); // Limpiar contenedor
            // let caracteresFisicoArray = response.caracteres_fisico;
            // for (let id in caracteresFisicoArray) {
            //     let descripcion = caracteresFisicoArray[id];
            //     let campo = `
            //     <div class="form-group col-md-6">
            //         <label for="caracter_fisico_${id}">${descripcion}</label>
            //         <input type="hidden" name="caracter_fisico[]" value="${id}">
            //         <input type="text" class="form-control" id="caracter_fisico_${id}" name="caracter_fisico_valor_${id}">
            //     </div>`;
            //     $('#signosVitalesContainer').append(campo);
            // }




            //hacer peticion para traer datos para la tabla
            let signos_response = await getDataTable(id_asegurado);


            let tbody = $('#bodyEmergenciaTriaje');
            tbody.empty(); // Limpiar el contenido previo de la tabla

            if (signos_response && Array.isArray(signos_response.data) && signos_response.data.length > 0) {
                let signos = signos_response.data;
                for (let signo of signos) {
                    let fechaAtencion = signo.fecha_atencion || '';
                    let idEnfermeria = signo.id_enfermeria || '';
                    let id_profesional = signo.user_name || '';
                    let camas_nombre = (signo.nombre_atencion || '') + ' ' + (signo.cuartos_nombre || '') + ' ' + (signo.nombre || '');
                    
                    let detalles = {};
if (Array.isArray(signo.signo_vital_detalle)) {
    detalles = signo.signo_vital_detalle.reduce((acc, detalle) => {
    
        if (detalle.caracter_fisico) {
            acc[detalle.caracter_fisico.descripcion] = detalle.descripcion || '';
        }
        return acc; // Asegúrate de devolver el acumulador
    }, {});
} else {
    console.warn('signo.signo_vital_detalle no es una matriz o está indefinido');
}

                    let fila = `
                <tr>
                    <td></td>
                    <td>${fechaAtencion}</td>
                    <td>${id_profesional}</td>
                    <td>${camas_nombre}</td>
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
                    tbody.append(fila);
                }
            }


            $('#modalListarHojaGrafica').modal('show');
            $('#id_hospitalizacion_hoja_grafica').val(id_hospitalizacion);
        } catch (error) {
            console.error(error);
        }
    });



    //ESTO ES PARA LA VISTA DETALLADA 

    $(document).on('click', '.view-btn', async function(event) {
        event.preventDefault();
        let id_asegurado = $('#id_asegurado_hiden').val();
        console.log('ID Asegurado del nuevo modal:', id_asegurado); // Para depuración
        try {
            let response = await getValues(id_asegurado);
            // Rellenar datos del paciente
            $('#id_enfermeria_register').val(response.paciente.id_enfermeria);
            $('#paciente3').val(response.paciente.nombre);
            $('#document3').val(response.paciente.nro_doc_ident);
            $('#parentesco3').val(response.paciente.parentesgo);
            $('#sexo3').val(response.paciente.sexo);
            $('#edad_corta3').val(response.paciente.edad_corta);
            $('#tipo_sangre3').val(response.paciente.tipo_sangre);
            $('#nro_historia3').val(response.paciente.nro_historia);
            $('#cip3').val(response.paciente.cip);


            // Agregar dinámicamente los campos de caracteres físicos
                        $('#signosVitalesContainer2').empty(); // Limpiar contenedor
                        let caracteresFisicoArray = response.caracteres_fisico;
let signovitalData = {
    1: response.signovital_data_1,
    2: response.signovital_data_2,
    4: response.signovital_data_3,
    6: response.signovital_data_4,
    9: response.signovital_data_5,
    17: response.signovital_data_6
};

// Limpiar el contenedor antes de agregar nuevos elementos
$('#signosVitalesContainer2').empty();

// Diagnóstico
console.log('caracteresFisicoArray:', caracteresFisicoArray);
console.log('signovitalData:', signovitalData);

for (let id in caracteresFisicoArray) {
    let descripcion = caracteresFisicoArray[id];
    let valor = ''; // Valor predeterminado para la descripción

    // Verifica si hay datos para esta clave en signovitalData
    if (signovitalData[id]) {
        valor = signovitalData[id].descripcion || ''; // Obtén la descripción, o usa una cadena vacía si no existe
    } else {
        console.log(`No data for ID ${id}`);
    }

    let campo = `
    <div class="form-group col-md-4">
        <label for="caracter_fisico_${id}">${descripcion}</label>
        <input type="hidden" name="caracter_fisico[]" value="${id}">
        <input type="text" class="form-control" id="caracter_fisico_${id}" value="${valor}" name="caracter_fisico_valor_${id}" readonly>
    </div>`;
    $('#signosVitalesContainer2').append(campo);
}




            var id = $(this).data('id');
            var row = $(this).closest('tr');
            var data = {
                

                respiratorio: row.find('td').eq(4).text(),

                temperatura: row.find('td').eq(5).text(),
            };

            console.log(data.temperatura);
            $('#caracter_fisico_7').val(data.respiratorio);
            $('#caracter_fisico_12').val(data.temperatura);

            // Abrir modal y pasar los datos
            $('#viewModal').modal('show');

        } catch (error) {
            console.error(error);
        }

    });


    async function getValues(id_asegurado) {
        try {
            const response = await $.ajax({
                url: '/signosVitales_Global/' + id_asegurado,
                type: 'GET'
            });
            console.log("entrando", response); // Para depuración: muestra la respuesta completa en la consola
            return response;
        } catch (xhr) {
            console.error(xhr.responseText);
            throw xhr.responseText;
        }
    }


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
</script>
