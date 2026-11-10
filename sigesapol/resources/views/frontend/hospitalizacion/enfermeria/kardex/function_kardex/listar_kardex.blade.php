<script> 

    $('#fecha_kardex_inicial').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
      console.log('click sobre fecha_kardex_inicial');
    });
  
  $('#fecha_kardex_final').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
  }).on('changeDate',function(e){ 
      console.log('click sobre fecha_kardex_final');
  });
  
  $('#fecha_kardex_ingreso').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
  }).on('changeDate',function(e){ 
      console.log('click sobre fecha_kardex_ingreso');
  });
   
    /*=============================================
    AJAX PARA crear Listar
    =============================================*/ 
  $('body').on('click', '#listarKardex', function () {    
      var id_hospitalizacion = $(this).data('id');  
      $('#FormularioListarKardex').trigger("reset");
      $('#tituloFormularioListarKardex').html("Kardex de Enfermería");
      $('#modalListarKardex').modal('show'); 
      
      $('#id_hospitalizacion_kardex').val(id_hospitalizacion);  
    }); 
    
    </script>
    
    