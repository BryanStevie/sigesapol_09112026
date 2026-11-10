function actualizarEstadoCheckbox($checkbox) {
    var $fila = $checkbox.closest('tr');
    var $valueCheck = $fila.find('input[name="value_cheack"]');
    var $statusIndicator = $fila.find('.status-indicator');
    var $pendiente = $fila.find('.pendiente'); // tu <select>

    if ($checkbox.is(':checked')) {
        console.log("se checko uno que estaba eliminado.");
        $valueCheck.val('0');
        $statusIndicator.text('R').css('color', 'green');
        /*$pendiente.on('mousedown', function (e) {
            e.preventDefault();
        }).addClass('readonly-select');*/
        $pendiente.prop('disabled', false).removeClass('readonly-select').val('NO');
        // $pendiente.val('NO');

    } else {
        $valueCheck.val('4');
        $statusIndicator.text('SR').css('color', 'red');
        /*$pendiente.on('mousedown', function (e) {
            e.preventDefault();
        }).addClass('readonly-select');
        $pendiente.val('NO');*/
        $pendiente.prop('disabled', true).addClass('readonly-select').val('NO');
    }
}


function actualizarEstadoCheckboxCarga1($checkbox) {
    var $fila = $checkbox.closest('tr');
    var $valueCheck = $fila.find('input[name="value_cheack"]');
    var $statusIndicator = $fila.find('.status-indicator');
    var $pendiente = $fila.find('.pendiente'); // tu <select>

    if ($checkbox.is(':checked')) {
        $valueCheck.val('0');
        $statusIndicator.text('R').css('color', 'green');

        // Deshabilitar el checkbox para que no se vuelva a enviar
        $checkbox.prop('disabled', true);

        if ($pendiente.val() === 'NO') {
            // NO deshabilitar el select
            

             /*$pendiente.on('mousedown', function (e) {
                e.preventDefault();
            }).addClass('readonly-select');*/
            $pendiente.prop('disabled', true).addClass('readonly-select');
        } else {
            // Si no es "NO", sí lo deshabilita
            $checkbox.prop('disabled', false);
            $pendiente.off('mousedown').removeClass('readonly-select').prop('disabled', false);
        }

    } else {
        $valueCheck.val('4');
        $statusIndicator.text('SR').css('color', 'red');

        // Dejar el checkbox habilitado para permitir cambios
        // $checkbox.prop('disabled', false);

        // Bloquear el select para evitar cambios y ponerlo en NO si deseas
        /*$pendiente.on('mousedown', function (e) {
            e.preventDefault();
        }).addClass('readonly-select');*/
        $pendiente.prop('disabled', false).addClass('readonly-select').prop('disabled', true);
      
    }
}


$(document).ready(function () {
    // Ejecutar al cargar la página para todos los checkboxes existentes
    
    $('.exam-checkbox').each(function () {
        actualizarEstadoCheckboxCarga1($(this));
    });

    // Ejecutar cuando el usuario cambia el estado del checkbox
    $(document).on('change', '.exam-checkbox', function () {
        actualizarEstadoCheckbox($(this));
    });
});

