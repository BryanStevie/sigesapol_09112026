<script> 
 
 $('#fecha_balance_hidrico_inicial').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
      console.log('click sobre fecha_balance_hidrico_inicial');
    });
  
  $('#fecha_balance_hidrico_final').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
  }).on('changeDate',function(e){ 
      console.log('click sobre fecha_balance_hidrico_final');
  });
    /*=============================================
    AJAX PARA crear Listar
    =============================================*/ 
  $('body').on('click', '#listarBalanceHidrico', function () {   
      var id_hospitalizacion = $(this).data('id');  
      $('#FormularioListarBalanceHidrico').trigger("reset");
      $('#tituloFormularioListarBalanceHidrico').html("Balance Hídrico de Enfermería");
      $('#modalListarBalanceHidrico').modal('show'); 
      
      $('#id_hospitalizacion_balance_hidrico').val(id_hospitalizacion);  
    }); 
    
    </script>
    
    