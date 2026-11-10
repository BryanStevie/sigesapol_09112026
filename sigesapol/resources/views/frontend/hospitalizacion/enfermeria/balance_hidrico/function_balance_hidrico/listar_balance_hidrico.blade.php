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
      function diff_years(dt2, dt1) {
        var diff = (dt2.getTime() - dt1.getTime()) / 1000;
        diff /= (60 * 60 * 24);
        return Math.abs(Math.round(diff / 365.25));
      }

      var id_hospitalizacion = $(this).data('id');
      const idAsegurado = JSON.parse(this.getAttribute('data-asegurado')).id_asegurado;
      $.ajax({
        url: `/buscar_asegurado_id/${idAsegurado}`,
        success: function(result) {
          const asegurado = JSON.parse(result);
          const date = new Date();
          const partes_fecha_nac_asegurado = asegurado.fecha_nac.split('/');
          $('#FormularioListarBalanceHidrico').trigger("reset");
          $('#tituloFormularioListarBalanceHidrico').html("Balance Hídrico de Enfermería");
          $('#dni-paciente').text(`${asegurado.nro_doc_ident} - ${asegurado.nombre} ${asegurado.paterno} ${asegurado.materno}`);
          $("#edad_paciente").text(diff_years(new Date(date.getFullYear(), date.getMonth(), date.getDate()), new Date(partes_fecha_nac_asegurado[2], partes_fecha_nac_asegurado[1] - 1, partes_fecha_nac_asegurado[0])));
          $("#sexo_paciente").text(asegurado.sexo);
          $("#hc_paciente").text(asegurado.nro_historia);
          $('#modalListarBalanceHidrico').modal('show'); 
        },
        error: function(err) {
          alert('Ocurrio un error inesperado.');
        }
      });
      
      $('#id_hospitalizacion_balance_hidrico').val(id_hospitalizacion);  
    }); 
    
    </script>
    
    