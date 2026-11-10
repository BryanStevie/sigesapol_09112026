<script>
    /*=============================================
    AJAX PARA crear Listar
    =============================================*/ 
    $('.listarHorarioKardex').click(function () {
      var id_hospitalizacion =  $('#id_hospitalizacion_kardex').val(); 
      $('#tituloFormularioHorarioKardex').html("Horario de Aplicación");
      $('#FormularioHorarioKardex').trigger("reset");
      $('#modalHorarioKardex').modal('show'); 
      
      $('#id_hospitalizacion_horario_kardex').val(id_hospitalizacion);  
    }); 
    
</script>