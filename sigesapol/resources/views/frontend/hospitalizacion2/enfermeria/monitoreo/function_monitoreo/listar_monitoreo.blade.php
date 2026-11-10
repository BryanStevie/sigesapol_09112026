<script> 

    $('#fecha_monitoreo_inicial').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
      console.log('click sobre fecha_monitoreo_inicial');
    });
  
    $('#fecha_monitoreo_final').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy', 
        todayHighlight: true,
        autoclose: true,  
        orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_monitoreo_final');
    });
   
    /*=============================================
    AJAX PARA crear Listar
    =============================================*/ 
  $('body').on('click', '#listarMonitoreos', function () {   
      var id_hospitalizacion = $(this).data('id_hospitalizacion');  
      $('#FormularioListarMonitoreo').trigger("reset");
      $('#tituloFormularioListarMonitoreo').html("Listado de Monitoreo");
      $('#modalListarMonitoreo').modal('show');

      $('#id_hospitalizacion_listado_monitoreo').val(id_hospitalizacion);
    }); 
    
    </script>
    
    