<script>
  
  $('body').on('click', '#editarAlergias', function () {
    $('#editTituloFormularioAlergiasM').html("Editar Nueva Alergia Médica");
    $('#editarModalAlergiasM').modal('show');
    var id_alergia = $(this).data('id_alergia');
    $('#id_alergia').val(id_alergia);
    $("#tblAlergia_editar tbody tr").remove();
    ver_medicina_alergias_editar(id_alergia); 
  });

  $("#actualizarAlergias" ).on( "click", function() {
    swal({
        buttons: true,
        title: "¿Desea Actualizar el Registro?",
        text: "Recuerde verificar que todos los datos sean correctos.",
        icon: "warning",
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) { 
            var token=$('#_token').val();
            var route='{{url('actualizar_medicamento_alergias')}}';
            //var notificador_observaciones = $('#notificador_observaciones').val();
            var formData = new FormData($("#editarFormularioAlergiasM") [0]);
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
                      $('#editarFormularioAlergiasM').trigger("reset");
                      $('#editarModalAlergiasM').modal('hide');
                      listar_medicamentos_alergias('','');
                      swal({
                        buttons: false,
                        title: "Registro Exitoso",
                        text: "Datos actualizados Exitosamente.",
                        icon: "success",
                        timer: 1500,
                      });

                      getListado();
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

  function ver_medicina_alergias_editar(id){
     
     var route='{{url('ver_lergias_medicas')}}';
     $.ajax({
       url:route,
       type:'POST',
       dataType:'JSON',
       data: {
         id_alergia: id, 
       },
        success: function(data){
          if(data!=null){
            var newRow = "";
            newRow += '<tr>';
            newRow +=
                '<td><div class="panel panel-info panel-bordered panel-sm">'+
                  '<div class="form-group"> '+
                    '<div class="panel-body"> '+
                      '<div class="col-md-3" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>MEDICAMENTO</label></div> '+
                      '<div class="col-md-4" style="margin-left:0px;padding-left:0px"> '+
                          '<input type="text" class="form-control" data-toggle="tooltip" data-placement="top" title="Ingresar el servicio" name="editar_nombre_servicio_Alergia" id="editar_nombre_servicio_Alergia"  tabindex="112" required="" value="'+data[0].medicamento+'" '+
                          'class="input-sm" style="margin-left:14px; width:100%; text-align:left" /> '+
                      '</div>'+
                      '<div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>CAUSA</label> </div>'+ 
                      '<div class="col-md-3"> '+
                        '<input type="text" class="form-control" value="'+data[0].causa+'" name="causa"  tabindex="113" data-date-end-date="0d" autocomplete="off" />'+
                      '</div> '+
                      '<div class="col-md-12"><br/> <label>RESUMEN</label> <textarea class="form-control" cols="2" name="resumen" placeholder="Relato m&eacute;dico o caracteristicas de la Alergia" >'+data[0].resumen+'</textarea>'+
                      '</div></div></div></div>';
                      newRow +=
                          '<input type="hidden" class="form-control typeahead tt-query editar_id_servicio_Alergia" id="editar_id_servicio_Alergia" name="editar_id_servicio_Alergia" value="'+data[0].id_medicamento+'"></td>';
                      
            $('#tblAlergia_editar tbody').append(newRow);

            $("#editar_nombre_servicio_Alergia").autocomplete({
              minLength: 1,
              delay: 400,
              source: function(request, response) {
                  url_consulta = '{{ url('obtener_medicina/') }}/' + request.term;
                  $.ajax({
                      url: url_consulta,
                      dataType: "json",
                      success: function(data) {
                          let listaServicioSelecionado = $(".editar_id_servicio_Alergia").map(function(){return parseInt($(this).val());}).get();
                          let filtro = data.filter((element)=>!listaServicioSelecionado.includes(element.id));
                          response(filtro);
                      }
                  })
              },
              select: function(e, ui) {
                var id_servicio_Alergia = ui.item.id;
                $("#editar_id_servicio_Alergia").val(id_servicio_Alergia);
                $("#editar_nombre_servicio_Alergia").prop("readonly", false);
                //$("#registrarAlergias").attr("disabled", false); 
              }
            });

            $("#editar_nombre_servicio_Alergia").on('blur', function() {
              var id_servicio_Alergia = $("#editar_id_servicio_Alergia").val();
              if (id_servicio_Alergia == "") {
                $("#registrarAlergias").attr("disabled", true); 
                var box = bootbox.alert("Falta ingresar el servicio en esta Alergia");
                return false;
              }else{
                $("#registrarAlergias").attr("disabled", false);
              }
            });
          }
        }
     });
  }
</script>
