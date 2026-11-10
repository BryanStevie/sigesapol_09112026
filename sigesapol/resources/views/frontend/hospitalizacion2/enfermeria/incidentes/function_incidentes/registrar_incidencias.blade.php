<script> 
  $('#fecha_incidente_notificacion').datepicker({
    useCurrent: false,
    format: 'dd/mm/yyyy', 
    todayHighlight: true,
    autoclose: true,  
    orientation: "bottom left" 
  }).on('changeDate',function(e){ 
      console.log('click sobre fecha_incidente_notificacion');
  });
  
  $('#fecha_incidente_verificacion').datepicker({
    useCurrent: false,
    format: 'dd/mm/yyyy', 
    todayHighlight: true,
    autoclose: true,  
    orientation: "bottom left" 
  }).on('changeDate',function(e){ 
      console.log('click sobre fecha_incidente_verificacion');
  });
    
    $('#fecha_incidente_suceso').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_incidente_suceso');
    });
     
    $("#personal_que_notifica").hide();
     $("input[name=personal_notifica]").click(function () {      
         if($(this).val()=='NO MEDICO' || $(this).val()=='ESTUDIANTE'){
           $('#personal_que_notifica').show(); 
         }else{
           $("#personal_que_notifica").hide();
         }
     });

    $("#tipo_de_eventos_adversos").hide();
     $("input[name=tipo_incidente]").click(function () {      
         if($(this).val()=='EVENTO ADVERSO'){
           $('#tipo_de_eventos_adversos').show(); 
         }else{
           $("#tipo_de_eventos_adversos").hide();
         }
     }); 

    $('#categoria_evento_adverso').on('change', function() { 
      var id_categoria_Seleccionada=$("#categoria_evento_adverso option:selected" ).val(); 
      if(id_categoria_Seleccionada=='*'){  
        $("#categoria_tipo_evento_adverso").empty();  
        $("#categoria_tipo_evento_adverso").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#categoria_tipo_evento_adverso").val("*").change();
      }else{
        lista_parametros_by_id(id_categoria_Seleccionada,'categoria_tipo_evento_adverso','combo','TABLA_PARAMETROS',0);
      }
    })

  $('#add_PERS_CONV_EVEAD').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_PERS_CONV_EVEAD', 
          'descripcion_PERS_CONV_EVEAD', 
          'descripcion_detalle_PERS_CONV_EVEAD', 
          'id_relacionado_PERS_CONV_EVEAD', 
          'boton_eliminar_item_PERS_CONV_EVEAD',
          'null',
          'sin_elemento_adicional',
          'null',
          'null',
          'SI'
          );

    });
 
    $('#add_ACCI_CORRECT').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_ACCI_CORRECT', 
          'descripcion_ACCI_CORRECT', 
          'descripcion_detalle_ACCI_CORRECT', 
          'id_relacionado_ACCI_CORRECT', 
          'boton_eliminar_item_ACCI_CORRECT',
          'null',
          'checkbox',
          'null',
          'null',
          'NO'
          );

    });

    $('#add_ACCI_PREVENT').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_ACCI_PREVENT', 
          'descripcion_ACCI_PREVENT', 
          'descripcion_detalle_ACCI_PREVENT', 
          'id_relacionado_ACCI_PREVENT', 
          'boton_eliminar_item_ACCI_PREVENT',
          'null',
          'checkbox',
          'null',
          'null',
          'NO'
          );

    });
     
    delete_table_tr_campo('tbl_PERS_CONV_EVEAD','boton_eliminar_item_PERS_CONV_EVEAD','');
    delete_table_tr_campo('tbl_ACCI_CORRECT','boton_eliminar_item_ACCI_CORRECT','');  
    delete_table_tr_campo('tbl_ACCI_PREVENT','boton_eliminar_item_ACCI_PREVENT','');  


    $('#crearIncidencias').click(function () {
    
      $('#id').val('');
      $("#registrarIncidentes").attr("id","registrarIncidentes");
      $("#registrarIncidentes").attr("disabled",false);
      $('#FormularioIncidentes').trigger("reset");
      $('#tituloFormularioIncidentes').html("Formato de registro y reporte de Incidentes y Eventos Adversos");
      $('#modalIncidentes').modal('show');
      
      $("#tbl_PERS_CONV_EVEAD tbody").empty(); 
      $("#tbl_ACCI_CORRECT tbody").empty(); 
      $("#tbl_ACCI_PREVENT tbody").empty(); 
      lista_parametros('CATEGORIA_EV_ADV','categoria_evento_adverso','combo','TABLA_PARAMETROS',0);
    });
    
    $( "#registrarIncidentes" ).on( "click", function() {
      $(this).attr('disabled', true);
    });
    
    $(document).ready(function () {
      $('#registrarIncidentes').click(function (e) {
        e.preventDefault(); 
    
        swal({
          buttons: true,
          title: "¿Desea Realizar el Registro?",
          text: "Recuerde verificar que todos los datos sean correctos.",
          icon: "warning",
          dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) { 

						$('#FormularioIncidentes').trigger("reset");
						$('#modalIncidentes').modal('hide');

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