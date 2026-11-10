<script> 
    $('#fecha_kardex_cvc').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_kardex_cvc');
    });
    
    $('#fecha_kardex_tet').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_kardex_tet');
    });
    
    $('#fecha_kardex_traq').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_kardex_traq');
    });
    
    $('#fecha_kardex_la').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_kardex_la');
    });
    
    $('#fecha_kardex_sng').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_kardex_sng');
    });
    
    $('#fecha_kardex_sf').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_kardex_sf');
    });
    
    $('#fecha_kardex_cvp').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_kardex_cvp');
    });
    
    $('#crearKardex').click(function () {
    
      $('#id').val('');
      $("#registrarKardex").attr("id","registrarKardex");
      $("#registrarKardex").attr("disabled",false);
      $('#FormularioKardex').trigger("reset");
      $('#tituloFormularioKardex').html("Kardex de Enfermería");
      $('#modalKardex').modal('show');
     
    });
     
    
    
    
    </script>
    
    