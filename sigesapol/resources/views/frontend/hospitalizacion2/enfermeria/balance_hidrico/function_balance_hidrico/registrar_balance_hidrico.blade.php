<script> 
    $('#add_ENDOVENOSO').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_ENDOVENOSO', 
          'descripcion_ENDOVENOSO', 
          'descripcion_detalle_ENDOVENOSO', 
          'id_relacionado_ENDOVENOSO', 
          'boton_eliminar_item_ENDOVENOSO',
          'MEDICAMENTOS_ENDOVENOSO',
          'input',
          'null',
          'null', 
          'SI'
          );

    });

    $('#add_DRENAJES').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_DRENAJES', 
          'descripcion_DRENAJES', 
          'descripcion_detalle_DRENAJES', 
          'id_relacionado_DRENAJES', 
          'boton_eliminar_item_DRENAJES',
          'DRENAJES',
          'input',
          'null',
          'null', 
          'SI'
          );

    });

    $('#add_INGRESOS_OTROS').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_INGRESOS_OTROS', 
          'descripcion_INGRESOS_OTROS', 
          'descripcion_detalle_INGRESOS_OTROS', 
          'id_relacionado_INGRESOS_OTROS', 
          'boton_eliminar_item_INGRESOS_OTROS',
          'INGRESOS_OTROS',
          'input',
          'null',
          'null', 
          'SI'
          );

    });

    $('#add_EGRESOS_OTROS').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_EGRESOS_OTROS', 
          'descripcion_EGRESOS_OTROS', 
          'descripcion_detalle_EGRESOS_OTROS', 
          'id_relacionado_EGRESOS_OTROS', 
          'boton_eliminar_item_EGRESOS_OTROS',
          'INGRESOS_OTROS',
          'input',
          'null',
          'null', 
          'SI'
          );

    });

    delete_table_tr_campo('tbl_ENDOVENOSO','boton_eliminar_item_ENDOVENOSO','');
    delete_table_tr_campo('tbl_DRENAJES','boton_eliminar_item_DRENAJES','');
    delete_table_tr_campo('tbl_INGRESOS_OTROS','boton_eliminar_item_INGRESOS_OTROS','');
    delete_table_tr_campo('tbl_EGRESOS_OTROS','boton_eliminar_item_EGRESOS_OTROS','');


    $( "#registrarBalanceHidrico" ).on( "click", function() {
      $(this).attr('disabled', true);
    });
     
    $('#crearBalanceHidrico').click(function () {
    
      $('#id').val('');
      $("#registrarBalanceHidrico").attr("id","registrarBalanceHidrico");
      $("#registrarBalanceHidrico").attr("disabled",false);
      $('#FormularioBalanceHidrico').trigger("reset");
      $('#tituloFormularioBalanceHidrico').html("Registro de Balance Hídrico");
      $('#modalBalanceHidrico').modal('show'); 

      $("#item_dieta_enteral").select2({ width: 'resolve'}); 
      lista_parametros('DIETA_ENTERAL','item_dieta_enteral','combo','TABLA_PARAMETROS',0);

      $("#tbl_ENDOVENOSO tbody").empty(); 
      $("#tbl_INGRESOS_OTROS tbody").empty(); 
      $("#tbl_DRENAJES tbody").empty(); 
      $("#tbl_EGRESOS_OTROS tbody").empty(); 
      
    });
    
    $(document).ready(function () {
      $('#registrarBalanceHidrico').click(function (e) {
        e.preventDefault(); 
        

          swal({
                buttons: true,
                title: "¿Desea Realizar el Registro?",
                text: "Recuerde verificar que todos los datos sean correctos.",
                icon: "warning",
                dangerMode: true,
              }).then((willDelete) => {
                if (willDelete) { 

                  $('#FormularioBalanceHidrico').trigger("reset");
                  $('#modalBalanceHidrico').modal('hide');

                  swal({
                    buttons: false,
                    title: "Registro Exitoso",
                    text: "Datos registrados Exitosamente.",
                    icon: "success",
                    timer: 1500,
                  });

                } else {
                  
                  $(this).attr('disabled', false);
                  swal({
                    buttons: false,
                    title: "Operación Cancelada",
                    text: "Proceso de Registro Cancelado.",
                    icon: "error",
                    timer: 1500,
                  });

                }
              });


      });
    });
    
    
    
</script>