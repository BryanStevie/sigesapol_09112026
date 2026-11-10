<script>
    /*=============================================
       AJAX PARA crear Listar
       =============================================*/ 
       $('.listarHojaBalanceHidrico').click(function () {
         var id_hospitalizacion = $(this).data('id');  
         $('#FormularioListarHojaBalanceHidrico').trigger("reset");
         $('#tituloFormularioListarHojaBalanceHidrico').html("Hoja de Balance Hídrico de Enfermería");
         $('#modalListarHojaBalanceHidrico').modal('show');  
       }); 
       
</script>