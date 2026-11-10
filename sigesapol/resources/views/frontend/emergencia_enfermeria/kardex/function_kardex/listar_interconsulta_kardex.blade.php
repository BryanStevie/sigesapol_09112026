<script> 
  
    $('#fecha_int_kardex_hemod').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy', 
        todayHighlight: true,
        autoclose: true,  
        orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_int_kardex_hemod');
    });

    $('#add_INTERVE_QUIRURJICA').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_INTERVE_QUIRURJICA', 
          'descripcion_INTERVE_QUIRURJICA', 
          'descripcion_detalle_INTERVE_QUIRURJICA', 
          'id_relacionado_INTERVE_QUIRURJICA', 
          'boton_eliminar_item_INTERVE_QUIRURJICA',
          'null',
          'fecha',
          'null',
          'null',
          'NO'
          );
          delete_table_tr_campo('tbl_INTERVE_QUIRURJICA','boton_eliminar_item_INTERVE_QUIRURJICA','');
    });
  
    $('.listarInterconsultaKardex').click(function () { 
      $('#FormularioListar_interconsultaKardex').trigger("reset");
      $('#tituloFormularioListar_interconsultaKardex').html("Kardex de Enfermería");
      $('#modalListar_interconsultaKardex').modal('show');  

      $("#tbl_INTERVE_QUIRURJICA tbody").empty(); 
    }); 
    
    </script>
    
    