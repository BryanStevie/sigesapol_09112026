
 <script> 
  $('body').on('click', '#anularEvoluciones', function () {
    var id_evoluciones = $(this).data('id_evoluciones');
    $('#anularFormularioEvoluciones').trigger("reset");
    $('#tituloFormularioEvoluciones-anular').html("Anular Proceso de Cuidado Enfermero - PCE");
    $('#modalAnularEvoluciones').modal('show');
    $('#id_evoluciones').val(id_evoluciones);
  });

  
  $(document).ready(function () {
    $('#AnularEvoluciones').click(function (e) {
      e.preventDefault();
      var motivoAnulacion=$('#anulacion_motivo').val();
      
      
      if(motivoAnulacion == null || motivoAnulacion.length <=5){ 
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese un motivo de la anulacion mayor a 5 caracteres para el campo motivo anulacion.",
          icon: "error",
          timer: 2500,
        });
       
        $('#anulacion_motivo').addClass('borde-alerta');
        setTimeout(function() {
          $('#anulacion_motivo').focus();  
        }, 1000);
        return;
      }else{
        $('#anulacion_motivo').removeClass('borde-alerta');
      }
      var token=$('#_token').val();
      var route='{{url('motivo_anulacion_evolucion')}}';
      swal({
            buttons: true,
            title: "¿Desea Anular el registro?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            dangerMode: true,
          }).then((willDelete) => {
            if (willDelete) { 

              var formData = new FormData($("#anularFormularioEvoluciones") [0]);
              $.ajax({
                data: formData,
                url:route,
                contentType: false,
                processData: false,
                headers: {'X-CSRF-TOKEN':token},
                type:'POST',
                enctype: 'multipart/form-data',
                dataType:'JSON',
                success: function(data){
                  if(data!=null){
                      if(data!=0){

                          $('#anularFormularioEvoluciones').trigger("reset");
                          $('#modalAnularEvoluciones').modal('hide');

                          swal({
                            buttons: false,
                            title: "Anulación Exitoso",
                            text: "El registro fue anulado Exitosamente.",
                            icon: "success",
                            timer: 1500,
                          });
                          const $p = [
                            $('#fecha_evolucion_bus_inicial_listado').val(), 
                            $('#fecha_evolucion_bus_final_listado').val(),
                            $('#descripcion_turno_bus_listado').val(),
                            $('#tipo_atenciones').val(),
                            $('#id_asegurado').val()
                          ];
                         listar_evoluciones($p);
                      }else{
                  
                        swal({
                          buttons: false,
                          title: "Error de Servicio de Registros",
                          text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                          icon: "error",
                          timer: 2500,
                        });
    
                      }
                }else{
                
                  swal({
                      buttons: false,
                      title: "Error de Servicio de Registros",
                      text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                      icon: "error",
                      timer: 2500,
                    });

                }
              }
            });
 
          } else {
               
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

