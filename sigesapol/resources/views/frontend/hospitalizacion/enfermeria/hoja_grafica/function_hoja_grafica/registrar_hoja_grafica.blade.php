<script>

    $('#add_RITMO_CARDIACO').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_RITMO_CARDIACO', 
          'descripcion_RITMO_CARDIACO', 
          'descripcion_detalle_RITMO_CARDIACO', 
          'id_relacionado_RITMO_CARDIACO', 
          'boton_eliminar_item_RITMO_CARDIACO',
          'RITMO_CARDIACO',
          'input',
          'null',
          'null', 
          'SI'
          );

    });
   
    $('#add_MODO_MONITOREO').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_MODO_MONITOREO', 
          'descripcion_MODO_MONITOREO', 
          'descripcion_detalle_MODO_MONITOREO', 
          'id_relacionado_MODO_MONITOREO', 
          'boton_eliminar_item_MODO_MONITOREO',
          'MODO_VENTILATORIO',
          'input',
          'null',
          'null', 
          'SI'
          );

    });

    delete_table_tr_campo('tbl_RITMO_CARDIACO','boton_eliminar_item_RITMO_CARDIACO','');
    delete_table_tr_campo('tbl_MODO_MONITOREO','boton_eliminar_item_MODO_MONITOREO','');

    // 

    $('#crearHojaGrafica').click(function () {
    
      $('#id').val('');
      $("#registrarHojaGrafica").attr("id","registrarHojaGrafica");
      $("#registrarHojaGrafica").attr("disabled",false);
      $('#FormularioHojaGrafica').trigger("reset");
      $('#tituloFormularioHojaGrafica').html("Registro de Control de Hoja Gráfica");
      $('#modalHojaGrafica').modal('show');
  
      $("#tbl_RITMO_CARDIACO tbody").empty(); 
      $("#tbl_MODO_MONITOREO tbody").empty(); 
     
    });
    
    $( "#registrarHojaGrafica" ).on( "click", function() {
      $(this).attr('disabled', true);
    });
    
    $(document).ready(function () {
      $('#registrarHojaGrafica').click(function (e) {
        e.preventDefault(); 
    
        swal({
          buttons: true,
          title: "¿Desea Realizar el Registro?",
          text: "Recuerde verificar que todos los datos sean correctos.",
          icon: "warning",
          dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) { 

						$('#FormularioHojaGrafica').trigger("reset");
						$('#modalHojaGrafica').modal('hide');

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
    
    