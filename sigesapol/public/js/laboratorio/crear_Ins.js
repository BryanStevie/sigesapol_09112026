function actualizarEstadoCheckbox($checkbox) {
    var $fila = $checkbox.closest('tr');
    var $valueCheck = $fila.find('input[name="value_cheack"]');
    var $statusIndicator = $fila.find('.status-indicator');
    var $pendiente = $fila.find('.pendiente'); // tu <select>
    let $servicio = $fila.find('.servicio'); // tu <select>
    let id_rua = $fila.find('.id_rua'); // tu <select>

    if ($checkbox.is(':checked')) {
        $valueCheck.val('0');
        $statusIndicator.text('R').css('color', 'green');
        $pendiente.prop('disabled', false).removeClass('readonly-select');
        $servicio.prop('disabled', false).removeClass('readonly-select');
        id_rua.prop('disabled', false).removeClass('readonly-select');

    } else {
        $valueCheck.val('4');
        $statusIndicator.text('SR').css('color', 'red');
        $pendiente.prop('disabled', true).addClass('readonly-select').val('NO');
        $servicio.prop('disabled', true).addClass('readonly-select');
        id_rua.prop('disabled', true).addClass('readonly-select');
    }
}

$(document).ready(function () {
    // Ejecutar al cargar la página para todos los checkboxes existentes
    $('.exam-checkbox').each(function () {
        actualizarEstadoCheckbox($(this));
    });

    // Ejecutar cuando el usuario cambia el estado del checkbox
    $(document).on('change', '.exam-checkbox', function () {
        actualizarEstadoCheckbox($(this));
    });
});

