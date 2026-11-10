<script>
  function LPP1(form)  { 
      var tabla_1LPP= 0; 
      var tabla_2LPP= 0; 
      var tabla_3LPP= 0; 
      var tabla_4LPP= 0; 
      var tabla_5LPP= 0; 
      var tabla_6LPP= 0;  
      var total_LPP= 0;   
      var tabla = document.getElementById('tabla1LPP'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i <l; i++) { 
        if (items[i].checked) { 
          tabla_1LPP= tabla_1LPP + parseInt(items[i].value); 
        } 
      } 
      
      var tabla = document.getElementById('tabla2LPP'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
          tabla_2LPP= tabla_2LPP + parseInt(items[i].value); 
        } 
      } 
      
      var tabla = document.getElementById('tabla3LPP'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
          tabla_3LPP= tabla_3LPP + parseInt(items[i].value); 
        } 
      }  
      
      var tabla = document.getElementById('tabla4LPP'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
          tabla_4LPP= tabla_4LPP + parseInt(items[i].value); 
        } 
      }  
      
      var tabla = document.getElementById('tabla5LPP'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
          tabla_5LPP= tabla_5LPP + parseInt(items[i].value); 
        } 
      }  
      
      var tabla = document.getElementById('tabla6LPP'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
          tabla_6LPP= tabla_6LPP + parseInt(items[i].value); 
        } 
      }  
      
      total_LPP = tabla_1LPP + tabla_2LPP + tabla_3LPP + tabla_4LPP + tabla_5LPP + tabla_6LPP;   
      console.log("totales glas"+total_LPP)

      if (total_LPP <12) { 
      $('#descripcion_detalle_riesgolpp').val("ALTO RIESGO"); 
      
  } else if (total_LPP >= 13 && total_LPP <= 14) {  
      $('#descripcion_detalle_riesgolpp').val("RIESGO MODERADO"); 
  } else if (total_LPP >= 15) { 
      $('#descripcion_detalle_riesgolpp').val("RIESGO BAJO"); 

  } 

      $('#total_LPP').val(total_LPP);   
      $('#puntaje_calculado_lpp_memoria').val(tabla_1LPP+','+tabla_2LPP+','+tabla_3LPP+','+tabla_4LPP+','+tabla_5LPP+','+tabla_6LPP); 

  } 
  
  $('.calcularEscalaLPP').click(function () {   
    $('#FormularioListarEscalaLPP').trigger("reset");
    $('#tituloFormularioListarEscalaLPP').html("Listado de Escala LESION POR PRESION");
    $('#modalListarEscalaLPP').modal('show'); 

    var puntaje_clpp_memoria=$('#puntaje_calculado_lpp_memoria').val();  
    const puntaje_clpp_memoria_Array = puntaje_clpp_memoria.split(",");
    let percepcion_sensorial = puntaje_clpp_memoria_Array[0];
    let exposicion_humedad = puntaje_clpp_memoria_Array[1];
    let actividad_fisica = puntaje_clpp_memoria_Array[2];
    let movilidad = puntaje_clpp_memoria_Array[3];
    let nutricion = puntaje_clpp_memoria_Array[4];
    let peligro_lesiones = puntaje_clpp_memoria_Array[5];
    if (percepcion_sensorial === "0") {
      percepcion_sensorial = "4"; //  Cambiar  aque opcion seria
  }
  if (exposicion_humedad === "0") {
    exposicion_humedad = "4"; // Cambiar  aque opcion seria
  }
  if (actividad_fisica === "0") {
    actividad_fisica = "4"; //  Cambiar  aque opcion seria
  }
  if (movilidad === "0") {
    movilidad = "4"; //  Cambiar  aque opcion seria
  }
  if (nutricion === "0") {
    nutricion = "4"; //  Cambiar  aque opcion seria
  }
  if (peligro_lesiones === "0") {
    peligro_lesiones = "3"; //  Cambiar  aque opcion seria
  }

    $("input[name=F][value='"+percepcion_sensorial+"']").prop("checked",true);  
    $("input[name=FF][value='"+exposicion_humedad+"']").prop("checked",true);  
    $("input[name=FA][value='"+actividad_fisica+"']").prop("checked",true);  
    $("input[name=FAA][value='"+movilidad+"']").prop("checked",true);  
    $("input[name=FAAS][value='"+nutricion+"']").prop("checked",true);  
    $("input[name=FAASD][value='"+peligro_lesiones+"']").prop("checked",true);  

  }); 

  
  $( "#procesar_EscalaLPP" ).on( "click", function() {
    $(this).attr('disabled', true);
  });
    
  $(document).ready(function () {
    $('#procesar_EscalaLPP').click(function (e) {
      e.preventDefault();  
      var total_LPP=$('#total_LPP').val();  
      var descripcion_detalle_riesgolpp=$('#descripcion_detalle_riesgolpp').val();  
      $('#puntaje_calculado_lpp').val(total_LPP);     
      $('#descripcion_detalle_riesgolpp').val(descripcion_detalle_riesgolpp);    
      $('#FormularioListarEscalaLPP').trigger("reset");
      $('#modalListarEscalaLPP').modal('hide');
      $(this).attr('disabled', false);
    });
  });
    
</script>