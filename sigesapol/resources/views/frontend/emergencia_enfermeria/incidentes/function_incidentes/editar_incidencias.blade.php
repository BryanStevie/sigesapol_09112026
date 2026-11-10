
<script> 

    $('#fecha_notificacion_edit').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_incidente_notificacion');
    });
    
    $('#fecha_incidente_verificacion_edit').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_incidente_verificacion');
    });
    
    $('#fecha_incidente_suceso_n_edit').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
        console.log('click sobre fecha_incidente_suceso');
    });

    $("input[name=notificador_tipo_personal]").each(function(index) {
      $(this).click(function () {  
          if($(this).val()=='NO MEDICO' || $(this).val()=='ESTUDIANTE'){
            $('#personal_que_notifica_n_edit').show(); 
          }else{
            $("#personal_que_notifica_n_edit").hide();
          }
      });
    });

    $("#tipo_de_eventos_adversos2").hide();
    $("input[name=notificador_tipo_registro]").each(function(index) {
      $(this).click(function () {  
        if($(this).val()=='EVENTO ADVERSO'){
          //$("#FormularioIncidentes").validate().resetForm();
            //Seleccionar el option por defecto del select
          $("select[name=notificador_tipo_evento_ea]").val("").change();
          $('#tipo_de_eventos_adversos2').show(); 
        }else{         
          $("#tipo_de_eventos_adversos2").hide();
        }
      });
    });

    $('#categoria_evento_adverso_r_edit').on('change', function() { 
      var id_categoria_Seleccionada=$("#categoria_evento_adverso_r_edit option:selected" ).val(); 
      if(id_categoria_Seleccionada=='*'){  
        $("#categoria_tipo_evento_adverso_r_edit").empty();  
        $("#categoria_tipo_evento_adverso_r_edit").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#categoria_tipo_evento_adverso_r_edit").val("*").change();
      }else{
        lista_parametros_by_id(id_categoria_Seleccionada,'categoria_tipo_evento_adverso_r_edit','combo','TABLA_PARAMETROS',0);
      }
    })

    $('#add_PERS_CONV_EVEAD2').on('click',function(){
      lista_table_tr_campo_detalle(
          'tbl_PERS_CONV_EVEAD2', 
          'descripcion_PERS_CONV_EVEAD', 
          'descripcion_detalle_PERS_CONV_EVEAD', 
          'id_relacionado_PERS_CONV_EVEAD', 
          'boton_eliminar_item_PERS_CONV_EVEAD2',
          'null',
          'sin_elemento_adicional',
          'null',
          'null',
          'SI'
      );

    });
 
    $('#add_ACCI_CORRECT2').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_ACCI_CORRECT2', 
          'descripcion_ACCI_CORRECT', 
          'descripcion_detalle_ACCI_CORRECT', 
          'id_relacionado_ACCI_CORRECT', 
          'boton_eliminar_item_ACCI_CORRECT2',
          'null',
          'checkbox',
          'null',
          'null',
          'NO'
          );

    });

    $('#add_ACCI_PREVENT2').on('click',function(){
      
      lista_table_tr_campo_detalle(
          'tbl_ACCI_PREVENT2', 
          'descripcion_ACCI_PREVENT', 
          'descripcion_detalle_ACCI_PREVENT', 
          'id_relacionado_ACCI_PREVENT', 
          'boton_eliminar_item_ACCI_PREVENT2',
          'null',
          'checkbox',
          'null',
          'null',
          'NO'
          );

    });

    $("#tipo_de_eventos_adversos2").hide();
    $("input[name=notificador_tipo_registro]").each(function(index) {
      $(this).click(function () {  
        if($(this).val()=='EVENTO ADVERSO'){
          $("select[name=notificador_tipo_evento_ea]").val("").change();
          $('#tipo_de_eventos_adversos2').show(); 
        }else{         
          $("#tipo_de_eventos_adversos2").hide();
        }
      });
    });

    $('#categoria_evento_adverso2').on('change', function() { 
      var id_categoria_Seleccionada=$("#categoria_evento_adverso2 option:selected" ).val(); 
      var id_categoria_adverso=$("#id_categoria_adverso" ).val(); 
      var id_categoria_tipo_adverso=$("#id_categoria_tipo_adverso" ).val(); 

      if(id_categoria_Seleccionada=='*'){  
        $("#categoria_tipo_evento_adverso2").empty();  
        $("#categoria_tipo_evento_adverso2").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#categoria_tipo_evento_adverso2").val("*").change();
      }else{
        
        if(id_categoria_Seleccionada!=id_categoria_adverso){
          lista_parametros_by_id(id_categoria_Seleccionada,'categoria_tipo_evento_adverso2','combo','TABLA_PARAMETROS',0); 
        }else{
          lista_parametros_by_id(id_categoria_Seleccionada,'categoria_tipo_evento_adverso2','combo','TABLA_PARAMETROS',id_categoria_tipo_adverso);
        }
      }
    });
     
    delete_table_tr_campo('tbl_PERS_CONV_EVEAD2','boton_eliminar_item_PERS_CONV_EVEAD2','');
    delete_table_tr_campo('tbl_ACCI_CORRECT2','boton_eliminar_item_ACCI_CORRECT2','');  
    delete_table_tr_campo('tbl_ACCI_PREVENT2','boton_eliminar_item_ACCI_PREVENT2','');  

  $('body').on('click', '#editarFormatoIncicentesEA', function () {
      var id_formato = $(this).data('id_formato');
      $('#tituloFormularioIncidente').html("Formato de registro y reporte de Incidentes y Eventos Adversos");
      $('#editarModalIncidenteEA').modal('show');
      editar_incidencia(id_formato);
  });

  $("#actualizarIncidentes" ).on( "click", function() {
    $("#FormularioIncidentesEdit").valid();
  })
  var validador = $("#FormularioIncidentesEdit").validate({        
      rules: {   
          notificador_tipo_personal_descripcion :{
              maxlength: 50,
              minlength: 5
          },
          notificador_observaciones :{
          required: true,
          minlength: 5
          },
          notificador_fecha_evento :{
            required: true,
            //fecha_dmA: true,
          },
          notificador_hora_evento :{
            required: true,
          },           
          selecTipoAdv :{
              required : function(element){
                  return (getEventoAdverso() == 1);
              },  
          },
          responsable_analisis_causas :{
            required: true,
            minlength: 5
          },
          

        },
        messages : {
          notificador_tipo_personal_descripcion : {
            required : "Ingresar la descripcion",
            minlength : "Ingrese al menos 5 caracteres",
            maxlength : "Ingrese máximo 50 caracteres"
          },
          notificador_observaciones : {
            required : "Ingresar la descripcion",
            minlength : "Ingrese al menos 5 caracteres"
          },
          notificador_fecha_evento:{
            required:"Seleccione la fecha",
            //fecha_dmA: "El formato de fecha es dd/mm/aaaa",
          },
          notificador_hora_evento:{
            required:"La hora es requerida",
          },
          selecTipoAdv : {
            required : "Seleccionar una opción",
          },
          responsable_analisis_causas :{
            required : "Ingresar la descripcion",
            minlength : "Ingrese al menos 5 caracteres"
          }

        },          
        submitHandler: function(form) {
            //se ejecuta una vez que se han validado los campos
            var token=$('#_token').val();
            var route='{{url('actualizar_formato_incidente')}}';

            // var notificador_observaciones = $('#notificador_observaciones').val();
            
            // if(notificador_observaciones<5){
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese una obervación.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultIncidentes");
            //   setTimeout(function() {
            //     $('#notificador_observaciones').focus();  
            //   }, 1000);
            //   return;
            // }

            swal({
              buttons: true,
              title: "¿Desea Realizar el Registro?",
              text: "Recuerde verificar que todos los datos sean correctos.",
              icon: "warning",
              dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) { 

                var formData = new FormData($("#FormularioIncidentesEdit") [0]);
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
                            $('#FormularioIncidentesEdit').trigger("reset");
                            $('#editarModalIncidenteEA').modal('hide');

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
            
        }
    });   
  
  function editar_incidencia(id){
    $('#id_incidencia').val(id);
    getDiagnosticosEdit(); 
      var route='{{url('ver_incidencia_emergencia')}}';
      $.ajax({
        url:route,
        type:'POST',
        dataType:'JSON',
        data: {
          id_incidencia: id, 
        },  
        success: function(data){
              if(data!=null){
                
                console.log(data.fecha_verificacion);
                  // $("#add_PERS_CONV_EVEAD2").attr("disabled",true);
                  // $("#add_ACCI_CORRECT_P").attr("disabled",true);
                  // $("#add_ACCI_PREVENT_P").attr("disabled",true);

                  editarCargarCabeceraModal(data.id_asegurado); 
                  $('#nombre_servicio_edit').html(data.nombre_servicio);
                  $('#fecha_notificacion_edit').val(data.fecha_notificacion);
                  $('#fecha_incidente_verificacion_edit').val(data.fecha_verificacion);
                
                  //NOTIFICADOR
                  if(data.notificador_tipo_personal==='MEDICO'){
                      document.getElementById("personal_notifica1_edit").checked = true;
                      $("#personal_que_notifica_edit").hide();
                    }
                  if(data.notificador_tipo_personal==='NO MEDICO'){tipo_incidente1_edit
                      document.getElementById("personal_notifica2_edit").checked = true;
                      $('#personal_que_notifica_edit').show(); 
                      $('#notificador_tipo_personal_descripcion_n_edit').val(data.notificador_tipo_personal_descripcion); 
                  }
                  if(data.notificador_tipo_personal==="ESTUDIANTE"){tipo_incidente1_edit
                      document.getElementById("personal_notifica3_edit").checked = true;
                      $('#personal_que_notifica_edit').show(); 
                      $('#notificador_tipo_personal_descripcion_n_edit').val(data.notificador_tipo_personal_descripcion); 
                  }
                  if(data.notificador_tipo_registro==="INCIDENTE"){
                      document.getElementById("tipo_incidente1_edit").checked = true; 
                      $("#tipo_de_eventos_adversos").hide();
                  }
                  if(data.notificador_tipo_registro==="EVENTO ADVERSO"){
                      document.getElementById("tipo_incidente2_edit").checked = true;
                      $('#selecTipoAdv_edit').val(data.notificador_tipo_evento_a).trigger('change.select2');
                      $('#tipo_de_eventos_adversos2').show(); 
                  }
                  $('#fecha_incidente_suceso_n_edit').val(data.notificador_fecha_evento);
                  $('#hora_edit').val(data.notificador_hora_evento);
                  $('#notificador_observaciones_edit').val(data.notificador_observaciones);
                  //RESPONSABLE
                  var cuerpo_PERS_CONV_EVEAD_R_EDIT = '' ;
                  $.each(data.convocados, function (key, value) { 
                      cuerpo_PERS_CONV_EVEAD_R_EDIT = cuerpo_PERS_CONV_EVEAD_R_EDIT+
                      '<tr >'+ 
                        '<td style="width: 100%;"><input type="text" name="descripcion_PERS_CONV_EVEAD[]" id="descripcion_PERS_CONV_EVEAD'+key+'" class="form-control  input-sm" value="'+value['nombre']+'" style="width:350px;"></td>'+
                        '<td> <input type="hidden" class="form-control id_relacionado_PERS_CONV_EVEAD  typeahead tt-query" id="id_relacionado_PERS_CONV_EVEAD'+key+'" name="id_relacionado_PERS_CONV_EVEAD[]" data-id='+key+' value="'+value['id']+'" style="height: 28px !important;" required> </td>'+ 
                        '<td> <button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_PERS_CONV_EVEAD2 btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>' +
                      '</tr>';
                  }); 
                  
                  $("#cuerpo_PERS_CONV_EVEAD2").html(cuerpo_PERS_CONV_EVEAD_R_EDIT);
                  $('#responsable_analisis_causas2').val(data.analisis_causas);
                  
                  if(data.responsable_categoria_adverso){
                      lista_parametros('CATEGORIA_EV_ADV','categoria_evento_adverso2','combo','TABLA_PARAMETROS',data.responsable_categoria_adverso);
                      $('#id_categoria_adverso').val(data.responsable_categoria_adverso);
                  }
                  
                  if(data.responsable_tipo_adverso){
                    $('#id_categoria_tipo_adverso').val(data.responsable_tipo_adverso);
                    lista_parametros_by_id(data.responsable_categoria_adverso,'categoria_tipo_evento_adverso2','combo','TABLA_PARAMETROS',data.responsable_tipo_adverso);
                  }
                  //PLAN DE MEJORA
                  var cuerpo_ACCI_CORRECT_P_EDIT = '' ;
                  $.each(data.correctivas, function (key, value) { 
                      
                      if(value.implementado==='on'){
                        cuerpo_ACCI_CORRECT_P_EDIT = cuerpo_ACCI_CORRECT_P_EDIT+
                        '<tr><td style="width: 82%;">'+
                        '<input type="text" required="" name="descripcion_ACCI_CORRECT[]" id="descripcion_ACCI_CORRECT'+key+'" class="form-control input-sm" value="'+value.acciones_correctivas+'" style="height: 28px !important;">'+
                        '</td><td> '+
                        '<input type="hidden" class="form-control id_relacionado_ACCI_CORRECT  typeahead tt-query" id="id_relacionado_ACCI_CORRECT2" name="id_relacionado_ACCI_CORRECT[]" data-id="2" style="height: 28px !important;" required="">'+
                        '</td>'+
                        '<td><input type="checkbox" id="descripcion_detalle_ACCI_CORRECT'+key+'" name="descripcion_detalle_ACCI_CORRECT'+key+'" id="descripcion_detalle_ACCI_CORRECT'+key+'" style="margin-left: 10px;cursor: pointer;" checked>'+
                          '<label for="descripcion_detalle_ACCI_CORRECT2" style="cursor: pointer;"> IMPLEMENTADO </label> '+
                        '</td>'+
                        '<td> <button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_ACCI_CORRECT2 btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>'+
                        '</tr>';
                      }else{
                        cuerpo_ACCI_CORRECT_P_EDIT = cuerpo_ACCI_CORRECT_P_EDIT+
                        '<tr><td style="width: 82%;">'+
                        '<input type="text" required="" name="descripcion_ACCI_CORRECT[]" id="descripcion_ACCI_CORRECT'+key+'" class="form-control input-sm" value="'+value.acciones_correctivas+'" style="height: 28px !important;">'+
                        '</td><td> '+
                        '<input type="hidden" class="form-control id_relacionado_ACCI_CORRECT  typeahead tt-query" id="id_relacionado_ACCI_CORRECT2" name="id_relacionado_ACCI_CORRECT[]" data-id="2" style="height: 28px !important;" required="">'+
                        '</td>'+
                        '<td><input type="checkbox" id="descripcion_detalle_ACCI_CORRECT'+key+'" name="descripcion_detalle_ACCI_CORRECT'+key+'" id="descripcion_detalle_ACCI_CORRECT'+key+'" style="margin-left: 10px;cursor: pointer;">'+
                          '<label for="descripcion_detalle_ACCI_CORRECT2" style="cursor: pointer;"> IMPLEMENTADO </label> '+
                        '</td>'+
                        '<td> <button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_ACCI_CORRECT2 btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>'+
                        '</tr>';
                      }
                  }); 
                  $("#cuerpo_ACCI_CORRECT2").html(cuerpo_ACCI_CORRECT_P_EDIT);

                  var cuerpo_ACCI_PREVENT_P_EDIT = '' ;
                  $.each(data.preventivas, function (key, value) { 
                      if(value.implementado==='on'){
                          cuerpo_ACCI_PREVENT_P_EDIT = cuerpo_ACCI_PREVENT_P_EDIT+
                          '<tr>'+
                            '<td style="width: 82%;">'+
                            '<input type="text" required name="descripcion_ACCI_PREVENT[]" id="descripcion_ACCI_PREVENT'+key+'" value="'+value.acciones_preventivas+'" class="form-control input-sm" value="" style="height: 28px !important;">'+
                            '</td>'+
                            '<td>'+
                            '<input type="hidden" class="form-control id_relacionado_ACCI_PREVENT  typeahead tt-query" id="id_relacionado_ACCI_PREVENT2" name="id_relacionado_ACCI_PREVENT[]" data-id="2" style="height: 28px !important;" required="">'+
                            '</td>'+
                            '<td><input type="checkbox" id="descripcion_detalle_ACCI_PREVENT'+key+'" name="descripcion_detalle_ACCI_PREVENT'+key+'" style="margin-left: 10px;cursor: pointer;" checked>'+
                            '<label for="descripcion_detalle_ACCI_PREVENT2" style="cursor: pointer;"> IMPLEMENTADO </label>'+
                            '</td>'+
                            '<td><button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_ACCI_PREVENT2 btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>'+
                          '</tr>';
                      }else{
                          cuerpo_ACCI_PREVENT_P_EDIT = cuerpo_ACCI_PREVENT_P_EDIT+
                          '<tr>'+
                            '<td style="width: 82%;">'+
                            '<input type="text" required name="descripcion_ACCI_PREVENT[]" id="descripcion_ACCI_PREVENT'+key+'" value="'+value.acciones_preventivas+'" class="form-control input-sm" value="" style="height: 28px !important;">'+
                            '</td>'+
                            '<td>'+
                            '<input type="hidden" class="form-control id_relacionado_ACCI_PREVENT  typeahead tt-query" id="id_relacionado_ACCI_PREVENT2" name="id_relacionado_ACCI_PREVENT[]" data-id="2" style="height: 28px !important;" required="">'+
                            '</td>'+
                            '<td><input type="checkbox" id="descripcion_detalle_ACCI_PREVENT'+key+'" name="descripcion_detalle_ACCI_PREVENT'+key+'" style="margin-left: 10px;cursor: pointer;">'+
                            '<label for="descripcion_detalle_ACCI_PREVENT2" style="cursor: pointer;"> IMPLEMENTADO </label>'+
                            '</td>'+
                            '<td><button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_ACCI_PREVENT2 btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>'+
                          '</tr>';
                      }
                  }); 
                  $("#cuerpo_ACCI_PREVENT2").html(cuerpo_ACCI_PREVENT_P_EDIT);
                  $("#verificacion_plan_mejora_v_edit").val(data.verificacion_plan_mejora);
              }
          }
      });
  }

  function editarCargarCabeceraModal(id_asegurado) {  
    $.ajax({
      url: '{{ url('getAseguradoby_id/') }}/' + id_asegurado,
      type: 'GET',
      dataType: 'JSON',
      success: function(data) { 
        $('#numero_doc_edit').html(data.nro_doc_ident);
        $('#nombres_edit').html(data.paterno +" " +data.materno+" " +data.nombre);
        $('#tipo_sangre_edit').html(data.tipo_sangre);
        $('#edad_edit').html(data.edad_corta);
        $('#sexo_edit').html(data.sexo);
        $('#hc_edit').html(data.nro_historia);
      }
    });
  }

  function getDiagnosticosEdit(){
      var id_emergencia = $('#id_hospitalizacion_incidencia').val();
      var numero_doc = $('#numero_doc2').val();

      var var_url='{{url('incidente_diagnosticos_emergencia')}}';
      $("#diagnosticos_edit").empty();
      $.ajax({
        url:var_url,
        type:'POST',
        data: {
          id_emergencia: id_emergencia,
            dni_paciente: numero_doc
        }, 
        dataType:'JSON',
        success: function(data){
          $.each(data, function (key, value) {
            $("#diagnosticos_edit").append("<tr><td>"+(key+1)+". "+value.diagnosticos+"</td></tr>");
          });
        }
      })
  }
  function validarCaracteresEspeciales(texto){
    const formato = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    if (formato.test(texto)) {
     return true;
    } else {
      return false
    }  
  }
</script>
