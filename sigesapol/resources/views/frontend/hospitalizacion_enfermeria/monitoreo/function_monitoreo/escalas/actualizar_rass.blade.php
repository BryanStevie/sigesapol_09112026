<script>
    function procesarRass_up(form) { 
        var tabla_1Rass = 0; 
        var descripcion_Rass = '';
        var tabla = document.getElementById('tabla1Rass_up'); 
        var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i < l; i++) { 
            if (items[i].checked) { 
                tabla_1Rass += parseInt(items[i].value); 
            } 
        }
        total_Rass = tabla_1Rass; 
        const intrepretacionRass_up_2 = {
            "4":'COMBATIVO',
            "3":'MUY AGITADO',
            "2":'AGITADO',
            "1":'INQUIETO',
            "0":'ALERTA Y TRANQUILO',
            "-1":'SOMNOLIENTO',
            "-2":'SEDACION LIGERA',
            "-3":'SEDACION MODERADA',
            "-4":'SEDACION PROFUNDA',
            "-5":'NO ESTIMULABLE',
        }
        const descripcionRass_up_2 = {
            "4":'Violento o combativo, con riesgo para el personal',
            "3":'Intenta arrancarse los tubos o cateteres o es agresivo con el personal',
            "2":'Movimientos descoordinados o desadaptacion del respirador',
            "1":'Ansioso, pero sin movimientos agresivos o vigorosos',
            "0":'',
            "-1":'Tendencia al sueño, es capaz de estar mas de 10 segundos despierto (apertura de ojos) a la llamada',
            "-2":'Menos de 10 segundos despierto (apertura de ojos) a la llamada',
            "-3":'Movimientos (sin apertura de ojos) a la llamada',
            "-4":'No responde a la voz, pero se mueve o abre los ojos, al estimulo fisico',
            "-5":'Sin respuesta a la voz o al estimulo fisico',
        };
        $('#total_rass_up').val(total_Rass);   
        $('#rass_up').val(total_Rass); 
        $('#puntaje_calculado_rass_up').val(`${total_Rass} | ${intrepretacionRass_up_2[total_Rass.toString()]}`); 
        $('#desc_rass_up').html(descripcionRass_up_2[total_Rass.toString()]);
    }

    $('.calcularEscalaRass_up').click(function () {   
        console.log('object');
        $('#FormularioListarEscalaRass_up').trigger("reset");
        $('#tituloFormularioListarEscalaRass_up').html("Listado de Escala de Agitación/Sedación de Richimond");
        $('#modalListarEscalaRass_up').modal('show'); 
        var puntaje_cr_memoria = $('#rass_up').val();  
        $("input[name=F_r_up][value='" + puntaje_cr_memoria + "']").prop("checked", true);
    });
    
    $( "#procesar_EscalaRass_up" ).on( "click", function() {
        $(this).attr('disabled', true);
    });

    $(document).ready(function () {
        $('#procesar_EscalaRass_up').click(function (e) {
            e.preventDefault();  
            $('#modalListarEscalaRass_up').modal('hide');
            $(this).attr('disabled', false);
        });
    });
</script>