<style>
  .morris-hover.morris-default-style {
    border-radius: 10px;
    padding: 6px;
    color: #666;
    background: rgba(255,255,255,.8);
    border: solid 2px rgba(230,230,230,.8);
    font-family: sans-serif;
    font-size: 12px;
    text-align: center;
}

.morris-hover {
    position: absolute;
    z-index: 1000;
}
</style>

<script>

      $('#fecha_registro_ver_registro_grafico_inicial').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy', 
        todayHighlight: true,
        autoclose: true,  
        orientation: "bottom left" 
      }).on('changeDate',function(e){ 
        console.log('click sobre fecha_registro_ver_registro_grafico_inicial');
      });

    $('#fecha_registro_ver_registro_grafico_final').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_registro_ver_registro_grafico_final');
    }); 

    $('.select2').select2();
     

    $('#visualizargraficoHojaGrafica').click(function () {
    
      $('#id').val('');
      $("#registrarGraficoHojaGrafica").attr("id","registrarGraficoHojaGrafica");
      $("#registrarGraficoHojaGrafica").attr("disabled",false);
      $('#FormularioGraficoHojaGrafica').trigger("reset");
      $('#tituloFormularioGraficoHojaGrafica').html("Visualizar Gráfica");
      $('#modalGraficoHojaGrafica').modal('show');
     
      cargar_grafico_pa();
      cargar_grafico_temp();
    }); 
   

    </script>
    
    