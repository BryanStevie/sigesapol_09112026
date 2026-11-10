$(document).ready(function () {
    // AUTOCOMPLETE de estrategia sanitaria
    $('#inputEstrategia').autocomplete({
        source: function (request, response) {
            if (request.term.length < 2) return;
            $.get('/estrategia_sanitaria/autocomplete', { q: request.term }, function (data) {
                const resultados = data.map(item => ({
                    label: `ESTRATEGIA - ${item.codigo} - ${item.nombre}`,
                    value: `ESTRATEGIA - ${item.codigo} - ${item.nombre}`,
                    data: item
                }));
                response(resultados);
            });
        },
        minLength: 2,
        select: function (event, ui) {
            const estrategia = ui.item.data;

            $('#estrategiaId').val(estrategia.codigo);
            return true;
        },
        change: function (event, ui) {
            // Si el texto no coincide con ningún item seleccionado, limpiar el ID
            if (!ui.item) {
                $("#estrategiaId").val('');


                if ($(this).val().length >= 2) {
                    $(this).autocomplete("search");
                }
            }
        },
        appendTo: '#modalAtencionEstrategia'
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>").append(`<div>${item.label}</div>`).appendTo(ul);
    };

    // BORRAR estrategiaId si modifican el texto del input
    $('#inputEstrategia').on('input', function () {
        const estrategia = $(this).data('estrategia');
        const texto = $(this).val();
        if (!estrategia || !texto.includes(estrategia.codigo)) {
            $(this).removeData('estrategia');
            $('#estrategiaId').val('');
        }
    });

    // AGREGAR estrategia
    $('#btnAgregarEstrategia').click(function () {
        const estrategia = $('#inputEstrategia').val();
        const estrategiaId = $('#estrategiaId').val();
        const observacion = $('#observacionEstrategia').val();


        if (estrategiaId == "") {
            alert('Debe seleccionar una estrategia sanitaria válida.');
            return;
        }

        const fila = `
            <tr>
                <td>
                    <input type="hidden" name="estrategias[][codigo]" value="${estrategiaId}">
                    ${estrategia}
                </td>
                <td>
                    <input type="hidden" name="estrategias[][observacion]" value="${observacion}">
                    ${observacion}
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm eliminarEstrategia"><i class="fa fa-times"></i></button>
                </td>
            </tr>`;

        $('#tablaEstrategias tbody').append(fila);

        // Limpiar campos
        $('#inputEstrategia').val('').removeData('estrategia');
        $('#estrategiaId').val('');

        $('#observacionEstrategia').val('');
    });

    // ELIMINAR fila de tabla
    $(document).on('click', '.eliminarEstrategia', function () {
        $(this).closest('tr').remove();
    });
});
