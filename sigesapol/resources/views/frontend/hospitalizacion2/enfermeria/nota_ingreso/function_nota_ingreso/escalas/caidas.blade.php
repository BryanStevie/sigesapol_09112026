<script>
    function CAIDAS1(form)  { 
        var tabla_1CAIDAS= 0; 
        var tabla_2CAIDAS= 0; 
        var tabla_2CAIDAS_name= 'FF0'; 
        var tabla_3CAIDAS= 0; 
        var tabla_3CAIDAS_name= 'FA0'; 
        var tabla_4CAIDAS= 0; 
        var tabla_5CAIDAS= 0;  
        var tabla_5CAIDAS_name= 'FAAS0';  
        var total_CAIDAS= 0;   

        var tabla = document.getElementById('tabla1CAIDAS'); 
        var items = tabla.getElementsByTagName('input'); 
          for (var i = 0, l = items.length; i <l; i++) { 
          if (items[i].checked) { 
            tabla_1CAIDAS= tabla_1CAIDAS + parseInt(items[i].value); 
          } 
        } 
        
        var tabla = document.getElementById('tabla2CAIDAS'); 
        var items = tabla.getElementsByTagName('input'); 
          for (var i = 0, l = items.length; i < l; i++) { 
          if (items[i].checked) { 
            tabla_2CAIDAS= tabla_2CAIDAS + parseInt(items[i].value); 
            tabla_2CAIDAS_name= (items[i].id);  
          } 
        } 
        
        var tabla = document.getElementById('tabla3CAIDAS'); 
        var items = tabla.getElementsByTagName('input'); 
          for (var i = 0, l = items.length; i < l; i++) { 
          if (items[i].checked) { 
            tabla_3CAIDAS= tabla_3CAIDAS + parseInt(items[i].value); 
            tabla_3CAIDAS_name= (items[i].id);  
          } 
        }  
        
        var tabla = document.getElementById('tabla4CAIDAS'); 
        var items = tabla.getElementsByTagName('input'); 
          for (var i = 0, l = items.length; i < l; i++) { 
          if (items[i].checked) { 
            tabla_4CAIDAS= tabla_4CAIDAS + parseInt(items[i].value);  
          } 
        }  
        
        var tabla = document.getElementById('tabla5CAIDAS'); 
        var items = tabla.getElementsByTagName('input'); 
          for (var i = 0, l = items.length; i < l; i++) { 
          if (items[i].checked) { 
            tabla_5CAIDAS= tabla_5CAIDAS + parseInt(items[i].value); 
            tabla_5CAIDAS_name= (items[i].id); 
          } 
        }     
        
        total_CAIDAS = tabla_1CAIDAS + tabla_2CAIDAS + tabla_3CAIDAS + tabla_4CAIDAS + tabla_5CAIDAS; 
        console.log("totales glas"+total_CAIDAS)
        if (total_CAIDAS >= 2) { 
      $('#descripcion_detalle_caidas').val("SIN RIESGO"); 
      
  } else if (total_CAIDAS < 2) {  
      $('#descripcion_detalle_caidas').val("Alto Riesgo"); 
  } 



        $('#total_CAIDAS').val(total_CAIDAS);   
        $('#puntaje_calculado_caidas_memoria').val(tabla_1CAIDAS+','+tabla_2CAIDAS_name+','+tabla_3CAIDAS_name+','+tabla_4CAIDAS+','+tabla_5CAIDAS_name); 
    } 
    
    $('.calcularEscalaCAIDAS').click(function () {   
      $('#FormularioListarEscalaCAIDAS').trigger("reset");
      $('#tituloFormularioListarEscalaCAIDAS').html("Listado de Escala de RIESGO DE CAÍDAS (J.H DOWNTON)");
      $('#modalListarEscalaCAIDAS').modal('show'); 
 
      var puntaje_calcai_memoria=$('#puntaje_calculado_caidas_memoria').val();  
      const puntaje_calcai_memoria_Array = puntaje_calcai_memoria.split(",");
      let caidas = puntaje_calcai_memoria_Array[0];
      let medicamentos = puntaje_calcai_memoria_Array[1];
      let deficit = puntaje_calcai_memoria_Array[2];
      let estado_mental = puntaje_calcai_memoria_Array[3];
      let deambulacion = puntaje_calcai_memoria_Array[4]; 
      // PONIENDO CONSOLE LOG PARA SABER QUE VALORES ESTA TRAYENDO SI ES EL VALUE = 0 O SU ID QUE ES IGAK A =FF0 PAR AMANEJAR LA LOGICA DE LOS IFS
      console.log("Valores desglosados:"+ medicamentos+deficit+estado_mental+deambulacion); // Verificar valores desglosados

      if (caidas === "0") {
        caidas = "1"; //  Cambiar  aque opcion seria
    }
    if (medicamentos ==="FF0") {
      medicamentos = "FF6"; // Cambiar  aque opcion seria
    }
    if (deficit === "FA0") {
      deficit = "FA3"; //  Cambiar  aque opcion seria
    }
    if (estado_mental === "0") {
      estado_mental = "1"; //  Cambiar  aque opcion seria
    }
    if (deambulacion === "FAAS0") {
      deambulacion = "FAAS3"; //  Cambiar  aque opcion seria
    }


      $("input[name=F][value='"+caidas+"']").prop("checked",true);  
      $("input[name=FF][id='"+medicamentos+"']").prop("checked",true); 

      $("input[name=FA][id='"+deficit+"']").prop("checked",true);  
      
      $("input[name=FAA][value='"+estado_mental+"']").prop("checked",true);  
      $("input[name=FAAS][id='"+deambulacion+"']").prop("checked",true);  
      
    }); 
  
    
    $( "#procesar_EscalaCAIDAS" ).on( "click", function() {
    $(this).attr('disabled', true);
  });
      
  $(document).ready(function () {
    $('#procesar_EscalaCAIDAS').click(function (e) {
      e.preventDefault();  
      var total_CAIDAS=$('#total_CAIDAS').val();  
      var descripcion_detalle_caidas=$('#descripcion_detalle_caidas').val();  

      $('#puntaje_calculado_caidas').val(total_CAIDAS);     
      $('#descripcion_detalle_caidas').val(descripcion_detalle_caidas);    
      $('#FormularioListarEscalaCAIDAS').trigger("reset");
      $('#modalListarEscalaCAIDAS').modal('hide');
      $(this).attr('disabled', false);
    });
  });
  
      
  </script>