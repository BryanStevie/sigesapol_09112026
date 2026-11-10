<script> 

    $('#fecha_hoja_grafica_inicial').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
      console.log('click sobre fecha_hoja_grafica_inicial');
    });
  
  $('#fecha_hoja_grafica_final').datepicker({
    useCurrent: false,
    format: 'dd/mm/yyyy', 
    todayHighlight: true,
    autoclose: true,  
    orientation: "bottom left" 
  }).on('changeDate',function(e){ 
      console.log('click sobre fecha_hoja_grafica_final');
  });
   
    /*=============================================
    AJAX PARA crear 
    =============================================*/ 
  $('body').on('click', '#listarHojaGrafica', function () {    
      var id_hospitalizacion = $(this).data('id');  
      $('#FormularioListarHojaGrafica').trigger("reset");
      $('#tituloFormularioListarHojaGrafica').html("Lista de Signos Vitales");
      $('#modalListarHojaGrafica').modal('show');
      
      $('#id_hospitalizacion_hoja_grafica').val(id_hospitalizacion);  
    }); 
    
    </script>
    
    