<script> 


    $('#fecha_incidencia_inicial').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
      console.log('click sobre fecha_incidencia_inicial');
    });
  
    $('#fecha_incidencia_final').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_incidencia_final');
    });
    
    $('body').on('click', '#listarIncidentes', function () {  
      var id_hospitalizacion = $(this).data('id');   
      $('#FormularioListarIncidentes').trigger("reset");
      $('#tituloFormularioListarIncidentes').html("Listado de Incidentes y Eventos Adversos");
      $('#modalListarIncidentes').modal('show');
      
      $('#id_hospitalizacion_incidencia').val(id_hospitalizacion);  
    }); 
    
    </script>
    
    