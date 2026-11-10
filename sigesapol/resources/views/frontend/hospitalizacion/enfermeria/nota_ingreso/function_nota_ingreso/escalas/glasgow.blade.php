<script>
  // Esta función calcula la puntuación total de la Escala de Glasgow en
  //  base a las selecciones hechas en tres tablas de opciones (tabla1Glasgow, tabla2Glasgow, tabla3Glasgow),
  //   y determina la descripción del trauma (grave, moderado, leve) según la puntuación total.
  function GLASGOW1(form) { 
    var tabla_1Glasgow = 0; 
    var tabla_2Glasgow = 0; 
    var tabla_3Glasgow = 0;  
    var total_Glasgow = 0;  
    var descripcion_Glasgow = '';

    // Sumatoria de los valores seleccionados en tabla1Glasgow
    var tabla = document.getElementById('tabla1Glasgow'); 
    var items = tabla.getElementsByTagName('input'); 
    for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
            tabla_1Glasgow += parseInt(items[i].value); 
        } 
    }

    // Sumatoria de los valores seleccionados en tabla2Glasgow
    var tabla = document.getElementById('tabla2Glasgow'); 
    var items = tabla.getElementsByTagName('input'); 
    for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
            tabla_2Glasgow += parseInt(items[i].value); 
        } 
    }

    // Sumatoria de los valores seleccionados en tabla3Glasgow
    var tabla = document.getElementById('tabla3Glasgow'); 
    var items = tabla.getElementsByTagName('input'); 
    for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
            tabla_3Glasgow += parseInt(items[i].value); 
        } 
    }

    // Cálculo total de la Escala de Glasgow
    total_Glasgow = tabla_1Glasgow + tabla_2Glasgow + tabla_3Glasgow; 
console.log("totales glas"+total_Glasgow)
    // Determinación de la descripción del trauma según la puntuación total
if (total_Glasgow >= 13 && total_Glasgow <= 15) { 
        $('#descripcion_total_Glasgow').val("TRAUMA LEVE"); 
    } else if (total_Glasgow >= 9 && total_Glasgow <= 12) { 
        $('#descripcion_total_Glasgow').val("TRAUMA MODERADO"); 
    } else if (total_Glasgow >= 3 && total_Glasgow <= 15) { 
        $('#descripcion_total_Glasgow').val("TRAUMA GRAVE"); 

    } 

    // Actualización de los campos con los resultados calculados
    $('#total_Glasgow').val(total_Glasgow);   
    $('#puntaje_calculado_glasgow_memoria').val(tabla_1Glasgow + ',' + tabla_2Glasgow + ',' + tabla_3Glasgow); 
    console.log(tabla_1Glasgow + ',' + tabla_2Glasgow + ',' + tabla_3Glasgow)
  
}

    
$('.calcularEscalaGlasgow').click(function () {   
    $('#FormularioListarEscalaGlasgow').trigger("reset");
    $('#tituloFormularioListarEscalaGlasgow').html("Listado de Escala GLASGOW");
    $('#modalListarEscalaGlasgow').modal('show'); 
    var puntaje_cg_memoria = $('#puntaje_calculado_glasgow_memoria').val();  
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

    $("input[name=F][value='" + abertura_ocular + "']").prop("checked", true);  
    $("input[name=FF][value='" + respuesta_verbal + "']").prop("checked", true);  
    $("input[name=FA][value='" + respuesta_motora + "']").prop("checked", true);   
});

  
    
  $( "#procesar_EscalaGlasgow" ).on( "click", function() {
    $(this).attr('disabled', true);
  });
      
  $(document).ready(function () {
    $('#procesar_EscalaGlasgow').click(function (e) {
      e.preventDefault();  
      var total_Glasgow=$('#total_Glasgow').val(); 
      var descripcion_total_Glasgow=$('#descripcion_total_Glasgow').val();  
      $('#puntaje_calculado_glasgow').val(total_Glasgow);    
      $('#descripcion_detalle_Glasgow').val(descripcion_total_Glasgow);    

      $('#FormularioListarEscalaGlasgow').trigger("reset");
      $('#modalListarEscalaGlasgow').modal('hide');
      $(this).attr('disabled', false);
    });
  });

  // GLASGOW PEDIATRIA
  $(document).ready(function () {
    const servicio = $("#servicio_info").html();
    const edad = parseInt($("#edad_info").html());
    if (servicio == "PEDIATRÍA") {
        if (edad < 2) {
            const cambio1 = $('#glasgowPediatria1').html("<b>AL HABLA O UN GRITO</b>");
            const cambio6 = $('#glasgowPediatria6').html('<b>RETIRA AL CONTACTO</b>');
            const cambio7 = $('#glasgowPediatria7').html('<b>ESPONTANEA</b>');
        }
        if (edad < 2) {
            const cambio2 = $('#glasgowPediatria2').html('<b>GEMIDO AL DOLOR</b>');
            const cambio3 = $('#glasgowPediatria3').html('<b>LLORA AL DOLOR</b>');
            const cambio4 = $('#glasgowPediatria4').html('<b>LLANTO CONSOLABLE</b>');
            const cambio5 = $('#glasgowPediatria5').html('<b>SONRIE/BALBUCEA</b>');
        } else if (edad >= 2 && edad <= 5) {
            const cambio2 = $('#glasgowPediatria2').html('<b>PALABRAS ADECUADAS</b>');
            const cambio3 = $('#glasgowPediatria3').html('<b>PALABRAS INADECUADAS</b>');
            const cambio4 = $('#glasgowPediatria4').html('<b>LLORA AL DOLOR O GRITA</b>');
            const cambio5 = $('#glasgowPediatria5').html('<b>GRUÑE AL DOLOR</b>');
        }
    }
  });
      
  </script>