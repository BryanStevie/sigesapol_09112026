<script> 
  function CPOT1(form)  { 
      var FF1M= 0; 
      var MM1M= 0; 
      var AA1M= 0; 
      var MOMO1M= 0; 
      var II1M= 0;  
      var P1M=0;  
      var CPOT_DESC=""; 
      var tabla = document.getElementById('tabla1FF1M'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i <l; i++) { 
      if (items[i].checked) { 
      FF1M= FF1M + parseInt(items[i].value); 
      } 
      } 
      var tabla = document.getElementById('tabla2MM1M'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i < l; i++) { 
      if (items[i].checked) { 
      MM1M= MM1M + parseInt(items[i].value); 
      } 
      } 
      var tabla = document.getElementById('tabla3AA1M'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i < l; i++) { 
      if (items[i].checked) { 
      AA1M= AA1M + parseInt(items[i].value); 
      } 
      } 
      var tabla = document.getElementById('tabla4MOMO1M'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i < l; i++) { 
      if (items[i].checked) { 
      MOMO1M= MOMO1M + parseInt(items[i].value); 
      } 
      } 
      var tabla = document.getElementById('tabla5II1M'); 
      var items = tabla.getElementsByTagName('input'); 
      for (var i = 0, l = items.length; i < l; i++) { 
      if (items[i].checked) { 
      II1M= II1M + parseInt(items[i].value); 
      } 
      }  
      P1M=FF1M+MM1M+AA1M+MOMO1M+II1M; 
      form.CPOT_VAL.value=P1M;  

      if (P1M<=3) {CPOT_DESC="DOLOR LEVE A MODERADO"} 
      else if (P1M>=4 && P1M<=6) {CPOT_DESC="DOLOR MODERADO A GRAVE"} 
      else if (P1M>=7) {CPOT_DESC="DOLOR MUY INTENSO"}; 
      $('#CPOT_DESC').val(CPOT_DESC);   
  } 

  
    $('.calcularEscalaCpot').click(function () {   
      $('#FormularioListarEscalaCpot').trigger("reset");
      $('#tituloFormularioListarEscalaCpot').html("Listado de Escala CPOT");
      $('#modalListarEscalaCpot').modal('show'); 
    }); 
    

  
  $( "#procesar_EscalaCpot" ).on( "click", function() {
    $(this).attr('disabled', true);
  });
      
  $(document).ready(function () {
    $('#procesar_EscalaCpot').click(function (e) {
      e.preventDefault();  
      var CPOT_VAL=$('#CPOT_VAL').val();  
      var CPOT_DESC=$('#CPOT_DESC').val();  
      $('#putaje_calculado_cpot').val(CPOT_VAL);  
      $('#valoracion_calculado_cpot').val(CPOT_DESC);    

      $('#FormularioListarEscalaCpot').trigger("reset");
      $('#modalListarEscalaCpot').modal('hide');
      $(this).attr('disabled', false);
    });
  });

</script>