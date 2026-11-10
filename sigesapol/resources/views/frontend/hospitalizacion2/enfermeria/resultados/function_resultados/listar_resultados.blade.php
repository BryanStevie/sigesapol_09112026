<script> 

    $('#fecha_resultados_inicial').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
      console.log('click sobre fecha_resultados_inicial');
    });
  
    $('#fecha_resultados_final').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy', 
        todayHighlight: true,
        autoclose: true,  
        orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_resultados_final');
    });
   
    /*=============================================
    AJAX PARA crear Listar 
    =============================================*/ 
    $('body').on('click', '#listarResultados', function () {  
      var id_hospitalizacion = $(this).data('id');  
      $('#FormularioListarResultados').trigger("reset");
      $('#tituloFormularioListarResultados').html("Listado de Resultados");
      $('#modalListarResultados').modal('show'); 
    }); 
    
    </script>
    
    