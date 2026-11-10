<script>
  function NORTON1(form)  { 
      var FF= 0; 
      var MM= 0; 
      var AA= 0; 
      var MOMO= 0; 
      var II= 0; 
      var P=0; 
      var J=""; 
      var tabla = document.getElementById('tabla1FF'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i <l; i++) { 
        if (items[i].checked) { 
        FF= FF + parseInt(items[i].value); 
        } 
      } 
      
      var tabla = document.getElementById('tabla2MM'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
        MM= MM + parseInt(items[i].value); 
        } 
      } 
      
      var tabla = document.getElementById('tabla3AA'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
        AA= AA + parseInt(items[i].value); 
        } 
      } 
      
      var tabla = document.getElementById('tabla4MOMO'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
        MOMO= MOMO + parseInt(items[i].value); 
        } 
      } 
      
      var tabla = document.getElementById('tabla5II'); 
      var items = tabla.getElementsByTagName('input'); 
        for (var i = 0, l = items.length; i < l; i++) { 
        if (items[i].checked) { 
        II= II + parseInt(items[i].value); 
        } 
      } 
      
      P=FF+MM+AA+MOMO+II; 
      form.P.value=P; 
      if (P>14) {J="SIN RIESGO"} 
      else if (P>12 && P<15) {J="RIESGO MEDIO"} 
      else if (P>9 && P<13) {J="RIESGO ALTO"} 
      else {J="RIESGO MUY ALTO"}; 
      $('#J').val(J);    
  } 
  
  $('.calcularEscalaNorton').click(function () {   
    $('#FormularioListarEscalaNorton').trigger("reset");
    $('#tituloFormularioListarEscalaNorton').html("Listado de Escala NORTON");
    $('#modalListarEscalaNorton').modal('show'); 
  }); 

  
$( "#procesar_EscalaNorton" ).on( "click", function() {
  $(this).attr('disabled', true);
});
    
$(document).ready(function () {
  $('#procesar_EscalaNorton').click(function (e) {
    e.preventDefault();  
    var P=$('#P').val(); 
    var J=$('#J').val(); 
    $('#putaje_calculado_norton').val(P);   
    $('#valoracion_calculado_norton').val(J);   

    $('#FormularioListarEscalaNorton').trigger("reset");
    $('#modalListarEscalaNorton').modal('hide');
    $(this).attr('disabled', false);
  });
});
    
</script>