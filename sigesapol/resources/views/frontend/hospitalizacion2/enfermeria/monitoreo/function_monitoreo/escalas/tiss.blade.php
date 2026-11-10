<script>
  function TISS1(form) { 
    var TISS_VAL= 0; 
    var TISS_J=""; 
    var tabla = document.getElementById('tablaTISS'); 
    var items = tabla.getElementsByTagName('input'); 
    for (var i = 0, l = items.length; i <l; i++) { 
      if (items[i].checked) { 
        TISS_VAL = TISS_VAL + parseInt(items[i].value); 
      } 
    } 
    form.TISS.value = TISS_VAL; 
      if (TISS_VAL<10) {TISS_J="CLASE I:NO TIENE NECESIDAD DE UCI"} 
      else if (TISS_VAL>=10 && TISS_VAL<=19) {TISS_J="CLASE II:INDICACIÒN UCI"} 
      else if (TISS_VAL>=20 && TISS_VAL<=39) {TISS_J="CLASE III:INESTABILIDAD HEMODINÀMICA"} 
      else if (TISS_VAL>=40) {TISS_J="CLASE IV:GRAN INESTABILIDAD"} 
      $('#TISS_J').val(TISS_J);  
  } 

  $('.calcularEscalaTiss').click(function () {   
    $('#FormularioListarEscalaTiss').trigger("reset");
    $('#tituloFormularioListarEscalaTiss').html("Listado de Escala TISS");
    $('#modalListarEscalaTiss').modal('show'); 
  }); 

  
  $( "#procesar_EscalaTiss" ).on( "click", function() {
    $(this).attr('disabled', true);
  });
      
  $(document).ready(function () {
    $('#procesar_EscalaTiss').click(function (e) {
      e.preventDefault();  
      var TISS=$('#TISS').val(); 
      var TISS_J=$('#TISS_J').val(); 
      $('#putaje_calculado_tiss').val(TISS);   
      $('#valoracion_calculado_tiss').val(TISS_J);   
 
      $('#FormularioListarEscalaTiss').trigger("reset");
      $('#modalListarEscalaTiss').modal('hide');
      $(this).attr('disabled', false);
    });
  });
    
</script>