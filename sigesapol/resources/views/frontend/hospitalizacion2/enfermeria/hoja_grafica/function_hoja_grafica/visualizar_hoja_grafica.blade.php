<script>
    /*=============================================
    AJAX PARA crear HojaGraficaS
    =============================================*/
    

    $('#visualizarRegistroHojaGrafica').click(function () {
    
      $('#id').val('');
      $("#registrarHojaGrafica").attr("id","registrarHojaGrafica");
      $("#registrarHojaGrafica").attr("disabled",false);
      $('#FormularioHojaGrafica').trigger("reset");
      $('#tituloFormularioHojaGrafica').html("Visualizar Control de Hoja Gráfica");
      $('#modalHojaGrafica').modal('show');
     
    }); 
    
    
    
    </script>
    
    