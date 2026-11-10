<script>
    /*=============================================
    AJAX PARA crear
    =============================================*/
    $('#addAlergia2').on('click', function() {
        AddAlergia2();
    });
    $('#tblAlergia tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();
        var ind = $('#tblAlergia tbody tr').length;
        if(ind==0){
          $("#registrarAlergias").attr("disabled", true);  
        }else{
            $("#registrarAlergias").attr("disabled", false); 
        }
    });

    $('#crearAlergias').click(function() {
        $("#tblAlergia tbody tr").remove();
        var tipoatenciones = document.getElementById('tipoatenciones').value;
        var estado = document.getElementById('estado').value;
        $('#detectado_en').val(tipoatenciones);
        
        $("#registrarAlergias").attr("disabled", true);
        $("#registrarAlergiasM").attr("id", "registrarAlergiasM");
        $("#registrarAlergiasM").attr("disabled", false);
        $('#FormularioAlergiasM').trigger("reset");
        $('#tituloFormularioAlergiasM').html("Registro Nueva Alergia Médica");
        $('#modalAlergiasM').modal('show'); 
        listar_medicamentos_alergias(tipoatenciones,estado);
    });

    $("#registrarAlergiasM").on("click", function() {
        $(this).attr('disabled', true);
    });

    $(document).ready(function() {
        $('#registrarAlergiasM').click(function(e) {
            e.preventDefault();
        });
    });

    $("#registrarAlergias" ).on( "click", function() {
        swal({
                buttons: true,
                title: "¿Desea Realizar el Registro?",
                text: "Recuerde verificar que todos los datos sean correctos.",
                icon: "warning",
                dangerMode: true,
              }).then((willDelete) => {
                if (willDelete) { 
                    var token=$('#_token').val();
                    var route='{{url('registrar_medicamento_alergias')}}';
                    //var notificador_observaciones = $('#notificador_observaciones').val();
                    var formData = new FormData($("#FormularioAlergiasM") [0]);
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
                              $('#FormularioAlergiasM').trigger("reset");
                              $('#modalAlergiasM').modal('hide');
                                var tipoatenciones = document.getElementById('tipoatenciones').value;
                                var estado = document.getElementById('estado').value;
                              listar_medicamentos_alergias(tipoatenciones,estado);
                              swal({
                                buttons: false,
                                title: "Registro Exitoso",
                                text: "Datos registrados Exitosamente.",
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
        //$("#FormularioAlergiasM").valid();
    });

    function validar(validar,id){
        var tipoatenciones = document.getElementById('tipoatenciones').value;
        var estado = document.getElementById('estado').value;
        validar_medicamento_alergia(validar,id);
        listar_medicamentos_alergias(tipoatenciones,estado);
    }
    function validar_medicamento_alergia(validar,id){
        var route='{{url('validar_alergias_medicas')}}';
        $.ajax({
          url:route,
          type:'POST',
          dataType:'JSON',
          data: {
            validar: validar, 
            id: id,
          },
          success: function(data){
            if(data!=null){
                  
                }
            }
        });
    }

    function AddAlergia2() {
        $("#registrarAlergias").attr("disabled", true);
        var newRow = "";
        var ind = $('#tblAlergia tbody tr').length;

        if (ind > 0) {
            $('.id_servicio_Alergia').each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp => ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });

            fila_medicamentos = $('#tblAlergia tbody').children('tr');
            rowIndex = $('#hidden_rowIndex').val();
            var idval = ind - 1;
            fila_medicamento = fila_medicamentos[idval];

        }

        newRow += '<tr>';
        newRow +=
            '<td><div class="panel panel-info panel-bordered panel-sm"><div class="form-group"> <div class="panel-body"> <div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>MEDICAMENTO</label></div> <div class="col-md-5" style="margin-left:0px;padding-left:0px"> <input type="text" class="form-control" data-toggle="tooltip" data-placement="top" title="Ingresar el servicio" name="nombre_servicio_Alergia[]" tabindex="112" required id="nombre_servicio_Alergia' +
            ind +
            '" class="input-sm" style="margin-left:14px; width:100%; text-align:left" /> </div> <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>CAUSA</label> </div> <div class="col-md-4"> <input type="text" class="form-control" value="" name="motivo_Alergia[]" id="motivo_Alergia'  +
            ind +
            '" tabindex="113" data-date-end-date="0d" autocomplete="off" /> </div> <div class="col-md-12"><br/> <label>RESUMEN</label> <textarea class="form-control" cols="2" id="resumen_Alergia' +
            ind +
            '" name="resumen_Alergia[]" placeholder="Relato m&eacute;dico o caracteristicas de la Alergia"></textarea></div></div></div></div></td>';
        newRow +=
            '<td><button tabindex="114" type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow +=
            '<input type="hidden" class="form-control typeahead tt-query id_servicio_Alergia" id="id_servicio_Alergia' +
            ind + '" name="id_servicio_Alergia[]" value="" data-id=' + ind + '></td>';
        newRow += '</tr>';
       
        $('#tblAlergia tbody').append(newRow);

        $("#nombre_servicio_Alergia" + ind).autocomplete({
            minLength: 1,
            delay: 400,
            source: function(request, response) {
                url_consulta = '{{ url('obtener_medicina/') }}/' + request.term;
                $.ajax({
                    url: url_consulta,
                    dataType: "json",
                    success: function(data) {
                        let listaServicioSelecionado = $(".id_servicio_Alergia").map(function(){return parseInt($(this).val());}).get();
                        let filtro = data.filter((element)=>!listaServicioSelecionado.includes(element.id));
                        response(filtro);
                    }
                })

            },
            select: function(e, ui) {
                var id_servicio_Alergia = ui.item.id;
                $("#id_servicio_Alergia" + ind).val(id_servicio_Alergia);
                $("#nombre_servicio_Alergia" + ind).prop("readonly", true);
                $("#registrarAlergias").attr("disabled", false); 
            }
        });
        $("#nombre_servicio_Alergia" + ind).on('blur', function() {
            var id_servicio_Alergia = $("#id_servicio_Alergia" + ind).val();
            if (id_servicio_Alergia == "") {
                $("#registrarAlergias").attr("disabled", true); 
                var box = bootbox.alert("Falta ingresar el servicio en esta Alergia");
                // box.on('hidden.bs.modal',function(){
                //     document.getElementById("nombre_servicio_Alergia" + ind).focus();
                // });
                return false;
            }else{
                $("#registrarAlergias").attr("disabled", false);
            }
        });
        
    }
    function listar_medicamentos_alergias(tipoatenciones,estado){
        
        var var_url='{{url('lista_alergias_medicas_filtro')}}';
        $.ajax({
          url:var_url,
          type:'POST',
          dataType:'JSON',
          data: {
            tipoatenciones: tipoatenciones,
            estado: estado,
          }, 
          beforeSend: function() {
            swal({
              buttons: false,
              title: "Realizando Consulta ...",
              text: "Espere un momento mientras se genera la solicitud.",
              icon: "warning",
            });
          }, 
          success: function(data){
       
            $("#listaAlergiasMedicas").empty();
            if(data!=null){
              var valor = '' ;
              $.each(data, function (key, value) {
                var obj = '';
                if(value.estado==true){
                    obj = obj+
                    '   <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" title="Validado"'+ 
                    '   class="btn btn-success btn-xs" id="validarAlergia" onclick="validar('+value.estado+','+value.id+')"><i class="fa fa-thumbs-o-up"></i>'+
                    '   </a>';
                  }else{
                    obj = obj+
                    '   <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" title="Validar"'+ 
                    '   class="btn btn-danger btn-xs" id="validarAlergia" onclick="validar('+value.estado+','+value.id+')"><i class="fa fa-thumbs-o-down"></i>'+
                    '   </a>';
                  }
                  valor = valor+
                  '<tr >'+
                  ' <td style="vertical-align: middle;">'+value.fecha+'</td>'+
                  ' <td style="vertical-align: middle;text-transform: uppercase;">'+value.medicamento+'</td>'+ 
                  ' <td style="vertical-align: middle;text-transform: uppercase;">'+value.nombre+' '+value.paterno+' '+value.materno+'</td>'+ 
                  ' <td style="vertical-align: middle;text-transform: uppercase;">'+value.tipo_atencion+'</td>'+ 
                  ' <td style="vertical-align: middle;">'+
                  '   <a href="javascript:void(0)" aria-hidden="true" data-id_alergia="'+value.id+'" id="verAlergias" name="verAlergias" '+ 
                  '     data-toggle="tooltip" title="Visualizar" class="btn btn-success btn-xs"> <i class="fa fa-eye"></i>'+ 
                  '   </a>'+ 
                  '   <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" data-id_alergia="'+value.id+'" id="editarAlergias" title="Editar"'+
                  '     class="btn btn-warning btn-xs "> <i class="fa fa-pencil-square-o"></i>'+
                  '   </a>'+obj+
                  ' </td>'+
                  '</tr>';
              });
              $("#listaAlergiasMedicas").html(valor);
              
              swal({
                buttons: false,
                title: "Registros encontrados",
                text: "List de registros cargados de forma correcta.",
                icon: "warning",
                timer: 1500,
              }); 
              
            }else{
              
              swal({
                buttons: false,
                title: "Sin Historial",
                text: "No se han detectado registros durante la búsqueda realizada.",
                icon: "warning",
                timer: 1500,
              }); 

            }
          }
        })
      };

    
</script>
