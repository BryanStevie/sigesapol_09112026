<script>
  function GLASGOW1_up(form) { 
    var tabla_1Glasgow = 0; 
    var tabla_2Glasgow = 0; 
    var tabla_3Glasgow = 0;  
    var total_Glasgow = 0;  
    var descripcion_Glasgow = '';

    // Sumatoria de los valores seleccionados en tabla1Glasgow
    var tabla = document.getElementById('tabla1Glasgow_up'); 
    var items = tabla.getElementsByTagName('input'); 
    for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
            tabla_1Glasgow += parseInt(items[i].value); 
        } 
    }

    // Sumatoria de los valores seleccionados en tabla2Glasgow
    var tabla = document.getElementById('tabla2Glasgow_up'); 
    var items = tabla.getElementsByTagName('input'); 
    for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
            tabla_2Glasgow += parseInt(items[i].value); 
        } 
    }

    // Sumatoria de los valores seleccionados en tabla3Glasgow
    var tabla = document.getElementById('tabla3Glasgow_up'); 
    var items = tabla.getElementsByTagName('input'); 
    for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
            tabla_3Glasgow += parseInt(items[i].value); 
        } 
    }

    // Cálculo total de la Escala de Glasgow
    total_Glasgow = tabla_1Glasgow + tabla_2Glasgow + tabla_3Glasgow; 
    // Determinación de la descripción del trauma según la puntuación total
    if (total_Glasgow >= 13 && total_Glasgow <= 15) { 
        $('#desc_glasgow_up').html("TRAUMA LEVE"); 
    } else if (total_Glasgow >= 9 && total_Glasgow <= 12) { 
        $('#desc_glasgow_up').html("TRAUMA MODERADO"); 
    } else if (total_Glasgow >= 3 && total_Glasgow <= 15) { 
        $('#desc_glasgow_up').html("TRAUMA GRAVE"); 

    } 

    // Actualización de los campos con los resultados calculados
    $('#glasgow_up').val(total_Glasgow);   
    $('#total_Glasgow_up').val(total_Glasgow);   
    $('#puntaje_calculado_glasgow_memoria_up').val(tabla_1Glasgow + ',' + tabla_2Glasgow + ',' + tabla_3Glasgow); 
}

    
$('.calcularEscalaGlasgowMonitoreo_up').click(function () {  
    $('#FormularioListarEscalaGlasgow_up').trigger("reset");
    $('#tituloFormularioListarEscalaGlasgow_up').html("Listado de Escala GLASGOW");
    $('#modalListarEscalaGlasgow_up').modal('show'); 
    var puntaje_cg_memoria = $('#puntaje_calculado_glasgow_memoria_up').val(); 
    if (puntaje_cg_memoria) {
        const puntaje_cg_memoria_Array = puntaje_cg_memoria.split(",");
        let abertura_ocular = puntaje_cg_memoria_Array[0];
        let respuesta_verbal = puntaje_cg_memoria_Array[1];
        let respuesta_motora = puntaje_cg_memoria_Array[2];
    
        // Cambiar valores 
        // LOS VALORES PREDETERINADOS COMO EL VALOR 1 QUE SE APERTURABA POR DDEFECTO AL ABRIR EL MODAL ERA PORQUE TENIA EL VALUE = 1 EN EL HTML 
        //EN EL HTML DE GRASGOW.BLADE 
        // ACA <input id="F" type="radio" name="F" value="1" style="cursor: pointer;" checked>
    
        /* if (abertura_ocular === "1") {
            abertura_ocular = "4"; //  Cambiar  aque opcion seria
        }
        if (respuesta_verbal === "1") {
            respuesta_verbal = "5"; // Cambiar  aque opcion seria
        }
        if (respuesta_motora === "1") {
            respuesta_motora = "6"; //  Cambiar  aque opcion seria
        } */
    
        $("input[name=F_up][value='" + abertura_ocular + "']").prop("checked", true);  
        $("input[name=FF_up][value='" + respuesta_verbal + "']").prop("checked", true);  
        $("input[name=FA_up][value='" + respuesta_motora + "']").prop("checked", true);   
    } 
});

  
    
  $( "#procesar_EscalaGlasgow_up" ).on( "click", function() {
    $(this).attr('disabled', true);
  });
      
  $(document).ready(function () {
    $('#procesar_EscalaGlasgow_up').click(function (e) {
      e.preventDefault();  
      $('#modalListarEscalaGlasgow_up').modal('hide');
      $(this).attr('disabled', false);
    });
  });
      
  </script>