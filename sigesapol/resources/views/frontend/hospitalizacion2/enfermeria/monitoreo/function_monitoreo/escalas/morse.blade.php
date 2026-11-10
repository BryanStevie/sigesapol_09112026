<script> 
  function MORSE1(form)  { 
      var FF1= 0; 
      var MM1= 0; 
      var AA1= 0; 
      var MOMO1= 0; 
      var II1= 0; 
      var II1I= 0; 
      var P1=0;  
      var MSEDS=""; 
      var tabla = document.getElementById('tabla1FF1'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i <l; i++) { 
      if (items[i].checked) { 
      FF1= FF1 + parseInt(items[i].value); 
      } 
      } 
      var tabla = document.getElementById('tabla2MM1'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i < l; i++) { 
      if (items[i].checked) { 
      MM1= MM1 + parseInt(items[i].value); 
      } 
      } 
      var tabla = document.getElementById('tabla3AA1'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i < l; i++) { 
      if (items[i].checked) { 
      AA1= AA1 + parseInt(items[i].value); 
      } 
      } 
      var tabla = document.getElementById('tabla4MOMO1'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i < l; i++) { 
      if (items[i].checked) { 
      MOMO1= MOMO1 + parseInt(items[i].value); 
      } 
      } 
      var tabla = document.getElementById('tabla5II1'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i < l; i++) { 
      if (items[i].checked) { 
      II1= II1 + parseInt(items[i].value); 
      } 
      } 
      var tabla = document.getElementById('tabla6II1I'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i < l; i++) { 
      if (items[i].checked) { 
      II1I= II1I + parseInt(items[i].value); 
      } 
      } 
      P1=FF1+MM1+AA1+MOMO1+II1+II1I; 
      form.MSE.value=P1;  
      if (P1>=51) {MSEDS="RIESGO ALTO"} 
      else if (P1>=25 && P1<=50) {MSEDS="RIESGO BAJO"} 
      else if (P1<=24) {MSEDS="SIN RIESGO"}; 
      $('#MSEDS').val(MSEDS);   
  } 

    $('.calcularEscalaMorse').click(function () {   
      $('#FormularioListarEscalaMorse').trigger("reset");
      $('#tituloFormularioListarEscalaMorse').html("Listado de Escala MORSE");
      $('#modalListarEscalaMorse').modal('show'); 
    }); 
    

  
  $( "#procesar_EscalaMorse" ).on( "click", function() {
    $(this).attr('disabled', true);
  });
      
  $(document).ready(function () {
    $('#procesar_EscalaMorse').click(function (e) {
      e.preventDefault();  
      var MSE=$('#MSE').val();  
      var MSEDS=$('#MSEDS').val();  
      $('#putaje_calculado_morse').val(MSE);  
      $('#valoracion_calculado_morse').val(MSEDS);    

      $('#FormularioListarEscalaMorse').trigger("reset");
      $('#modalListarEscalaMorse').modal('hide');
      $(this).attr('disabled', false);
    });
  });
</script>