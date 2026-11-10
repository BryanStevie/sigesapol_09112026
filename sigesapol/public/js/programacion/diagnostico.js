$(document).ready(function () {
    let diagnosticos = [];

    // Configurar autocomplete
    $("#inputDiagnostico").autocomplete({
        source: function (request, response) {
            $.get('/diagnostico/autocomplete', { q: request.term }, function (data) {
                response($.map(data, function (item) {
                    return {
                        label: item.codigo + " - " + item.nombre,
                        value: item.codigo + " - " + item.nombre,
                        id: item.id
                    };
                }));
            }).fail(function () {
                console.error('Error en la búsqueda de diagnósticos');
            });
        },
        minLength: 2,
        select: function (event, ui) {
            $("#diagnosticoId").val(ui.item.id);
            return true;
        },
        change: function (event, ui) {
            // Si el texto no coincide con ningún item seleccionado, limpiar el ID
            if (!ui.item) {
                $("#diagnosticoId").val('');

                // Si hay texto pero no coincide con selección, volver a buscar
                if ($(this).val().length >= 2) {
                    $(this).autocomplete("search");
                }
            }
        },
        focus: function (event, ui) {
            // Mostrar el valor completo al navegar con teclas
            $("#inputDiagnostico").val(ui.item.value);
            return false;
        }
    });

    // También capturar el evento keyup para detectar cuando se borra texto
    $("#inputDiagnostico").on('keyup', function (e) {
        // Si se presiona backspace o delete y el texto es menor que antes
        if ((e.keyCode == 8 || e.keyCode == 46) && $(this).val().length < $("#inputDiagnostico").data('lastLength')) {
            $("#diagnosticoId").val('');
        }
        // Guardar longitud actual para comparar después
        $("#inputDiagnostico").data('lastLength', $(this).val().length);
    });


    // Agregar diagnóstico
    $('#btnAgregarDiagnostico').click(function () {
        const inputVal = $('#inputDiagnostico').val();
        const id = $("#diagnosticoId").val();
        const tipo = $('#tipoDiagnostico').val();
        const observacion = $('#observacionDiagnostico').val();

        if (id && tipo && inputVal) {
            const nombre = inputVal;

            diagnosticos.push({
                id: id,
                nombre: nombre,
                tipo: tipo,
                observacion: observacion
            });

            renderDiagnosticos();
            $('#inputDiagnostico').val('');
            $('#diagnosticoId').val('');
            $('#observacionDiagnostico').val('');
        } else {
            alert('Por favor complete todos los campos requeridos');
        }
    });

    // Renderizar tabla de diagnósticos
    function renderDiagnosticos() {
        let html = '';
        diagnosticos.forEach((d, index) => {
            const tipoTexto = d.tipo === '1'
                ? 'Presuntivo'
                : d.tipo === '2'
                    ? 'Definitivo'
                    : 'Repetitivo';
            html += `<tr>
                <td >
                <input type="hidden" name="diagnosticos[${index}][id_diagnostico]" value="${d.id}">
                ${d.nombre}</td>
                <td> <input type="hidden" name="diagnosticos[${index}][tipo]" value="${d.tipo || ''}" >${tipoTexto}</td>
                <td> <input type="hidden" name="diagnosticos[${index}][observacion]" value="${d.observacion || ''}" >${d.observacion || ''}</td>
                <td><button type="button" class="btn btn-danger btn-xs" onclick="eliminarDiagnostico(${index})"><i class="fa fa-times"></i></button></td>
            </tr>`;
        });
        $('#tablaDiagnostico tbody').html(html);
    }

    // Función global para eliminar

    $(document).on('click', '.eliminarDiagnostico', function () {
        $(this).closest('tr').remove();
    });
});