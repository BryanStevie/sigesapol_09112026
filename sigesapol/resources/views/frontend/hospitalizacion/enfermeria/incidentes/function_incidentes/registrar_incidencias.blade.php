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
   $("input[name=notificador_tipo_personal]").each(function(index) {
      $(this).click(function () {   
        
          if($(this).val()=='NO MEDICO' || $(this).val()=='ESTUDIANTE'){
            $('#personal_que_notifica').show(); 
          }else{
            //caja de texto descripcion
              //segun la logica qu pidio luis, el campo descripcion se debe quedar con el ultimo valor 
              //ingresado
              //$("input[name=descripcion]").val(''); 
              ///////$("#FormularioIncidentes").validate().resetForm();
              $("#personal_que_notifica").hide();
          }
      });
    });

  
  $("#tipo_de_eventos_adversos").hide();
  $("input[name=notificador_tipo_registro]").each(function(index) {
  $(this).click(function () {  
    console.log('this val'+ $(this).val());   

    if($(this).val()=='EVENTO ADVERSO'){
      //$("#FormularioIncidentes").validate().resetForm();
        //Seleccionar el option por defecto del select
      $("select[name=notificador_tipo_evento_ea]").val("").change();
      $('#tipo_de_eventos_adversos').show(); 
    }else{         
      $("#tipo_de_eventos_adversos").hide();
    }
  });

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
    var id_hospitalizacion = document.getElementById('id_hospitalizacion_incidencia').value;
    var id_asegurado = document.getElementById('id_asegurado').value;
    var id_servicio = document.getElementById('id_servicio').value;
    var nombre_servicio = document.getElementById('nombre_servicio').value;
    var id_nota_enfermeria = document.getElementById('id_nota_enfermeria').value;
    var tipo_atenciones = document.getElementById('tipo_atenciones').value;

    $('#id_hospitalizacion_i').val(id_hospitalizacion);
    $('#id_asegurado_i').val(id_asegurado);
    $('#id_servicio_i').val(id_servicio);
    $('#tipo_atencion').val(tipo_atenciones);
    $('#id_nota_enfermeria2').val(id_nota_enfermeria);
    
    var numero_doc2 = document.getElementById('numero_doc2').value;
    var nombres2 = document.getElementById('nombres2').value;
    var tipo_sangre2 = document.getElementById('tipo_sangre2').value;
    var edad2 = document.getElementById('edad2').value;
    var sexo2 = document.getElementById('sexo2').value;
    var hc2 = document.getElementById('hc2').value;
    
   

    $('#numero_a').html(numero_doc2);
    $('#nombre_a').html(nombres2);
    $('#tipo_sangre_a').html(tipo_sangre2);
    $('#edad_a').html(edad2);
    $('#sexo_a').html(sexo2);
    $('#hc_a').html(hc2);
    $('#nombre_servicio2').html(nombre_servicio);

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
  
  $("#registrarIncidentes" ).on( "click", function() {

    $("#FormularioIncidentes").valid();
  });
  
    //para validacion condicional
    function getTipoNotificador() {
      console.log("getTipoNotificador!");
     
      //int con la cant de radio "No medico" selecconado (obvio que debe ser solo 1 max)
      var sel_no_med =$("#FormularioIncidentes").find("#personal_notifica2:checked").length;
      //int con la cant de radio "Estudiane" selecconado (obvio que debe ser solo 1 max)
      var sel_estudiante =$("#FormularioIncidentes").find("#personal_notifica3:checked").length;

      console.log('No med :'+sel_no_med);
      console.log('Estud  :'+sel_estudiante);

      if ((sel_no_med==1)||(sel_estudiante==1)){
        return 1;
      } else{
        return 0;
      }
      
    }

    function getEventoAdverso(){
      //console.log("Radio event advers selecc");
      var selecTipoAdv = $("#FormularioIncidentes").find("#tipo_incidente2:checked").length;
      if (selecTipoAdv==1){
        console.log("Radio event advers selecc, 1");
        return 1;
      }else{
        console.log("Radio event advers selecc, 0");
        return 0;
      }

    }

    //funcion personalizada para el campo : fecha_incidente_suceso
    //
    $.validator.addMethod("fecha_dmA", function(value, element) {
      
      var date2 = value.split("/");
      var d = parseInt(date2[0], 10),
          m = parseInt(date2[1], 10),
          y = parseInt(date2[2], 10);
      return new Date(y, m - 1, d);
    }, "El formato de fecha es dd/mm/aaaa.");

    var validador = $("#FormularioIncidentes").validate({        
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
            var route='{{url('registrar_formato_incidente')}}';

            var notificador_observaciones = $('#notificador_observaciones').val();
            
            if(notificador_observaciones<5){
              swal({
                buttons: false,
                title: "Verifique los campos ingresados",
                text: "Proceso cancelado. Ingrese una obervación.",
                icon: "error",
                timer: 2500,
              });
              cambiar_de_PestanaTab("tab1defaultIncidentes");
              // $('#descripcion_evaluaciones').addClass('borde-alerta');
              setTimeout(function() {
                $('#notificador_observaciones').focus();  
              }, 1000);
              return;
            }

            swal({
              buttons: true,
              title: "¿Desea Realizar el Registro?",
              text: "Recuerde verificar que todos los datos sean correctos.",
              icon: "warning",
              dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) { 

                var formData = new FormData($("#FormularioIncidentes") [0]);
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
                            $('#FormularioIncidentes').trigger("reset");
                            $('#modalIncidentes').modal('hide');

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

    function getListado(){
      var tipo = document.getElementById('tipo_atenciones').value;
      var fecha_inicio = document.getElementById('fecha_incidencia_inicial').value;
      var fecha_final = document.getElementById('fecha_incidencia_final').value;
      listar_incidencias_eventos_a(tipo,fecha_inicio,fecha_final);
    }
    function listar_incidencias_eventos_a(tipo,f_inicial,f_final){
      
      var var_url='{{url('lista_incidencias_eventos_a')}}';
      $.ajax({
        url:var_url,
        type:'POST',
        dataType:'JSON',
        data: {
            fecha_inicial: f_inicial,
            fecha_final: f_final,
            tipo: tipo
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
          console.log(data );
          $("#listaDatosIncidenciasEventosA").empty();
          if(data!=null){
            var valor = '' ;
            $.each(data, function (key, value) {
                valor = valor+
                '<tr >'+
                ' <td style="vertical-align: middle;">'+value.notificador_fecha_evento+' '+value.notificador_hora_evento+'</td>'+
                ' <td style="vertical-align: middle;text-transform: uppercase;">'+value.nombre_consultorio+'</td>'+ 
                ' <td style="vertical-align: middle;text-transform: uppercase;">'+value.name+' '+value.paterno+' '+value.materno+'</td>'+ 
                ' <td style="vertical-align: middle;text-transform: uppercase;">'+value.tipo_atencion+'</td>'+ 
                ' <td style="vertical-align: middle;">'+
                '   <a href="javascript:void(0)" aria-hidden="true" data-id_formato="'+value.id_formato+'" id="verFormatoIncicentesEA" name="verFormatoIncicentesEA" '+ 
                '     data-toggle="tooltip" title="Visualizar" class="btn btn-success btn-xs"> <i class="fa fa-eye"></i>'+ 
                '   </a>'+ 
                
                '   <a href="javascript:void(0)" aria-hidden="true" data-id_formato="'+value.id_formato+'" id="editarFormatoIncicentesEA" data-toggle="tooltip" title="Editar"'+
                '     class="btn btn-warning btn-xs "> <i class="fa fa-pencil-square-o"></i>'+
                '   </a>'+
                
                '   <a href="/pdf_formato_incidencia_evento_adverso/'+value.id_formato+'" target="_blank" aria-hidden="true" data-toggle="tooltip" title="PDF"'+
                '     class="btn btn-danger btn-xs"> <i class="fa fa-file-pdf-o"></i>'+
                '   </a>'+
                ' </td>'+
                '</tr>';
            });
            $("#listaDatosIncidenciasEventosA").html(valor);
            
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
