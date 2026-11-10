<script>
  function validar_documento_paciente(){
    var input = document.getElementById('dni_beneficiario'); 
    // Capturar el evento keypress en el campo de entrada
    input.addEventListener('keypress', function(event) {
      var charCode = event.which ? event.which : event.keyCode;

      // Comprobar si el carácter ingresado es un número o no
      if (charCode < 48 || charCode > 57) {
        // Si no es un número, cancelar el evento de pulsación de tecla
        event.preventDefault();
      }
    });

    // Capturar el evento input en el campo de entrada
    input.addEventListener('input', function() {
      // Eliminar cualquier carácter no numérico del valor del campo de entrada
      this.value = this.value.replace(/\D/g, ''); 
      // Limitar la longitud máxima del campo de entrada a 8 dígitos solo para DNI
      var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
      if (tipodoc_beneficiario == "1"||tipodoc_beneficiario == "DNI") {
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
          }
      }
    }); 
  }
  
  
  function getReniecByDni_paciente(valor_parametro) {
    limpiar_paciente(); 
    
    $('#dni_beneficiario').prop('disabled', false);
    $('#btnBuscarDniPaciente').prop('disabled', false);

    var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    var dni_paciente = $("#dni_beneficiario").val().trim();
    $("#dni_beneficiario").val(dni_paciente);
    var tipdoc_beneficiario = $("#tipodoc_beneficiario_bus option:selected").text();
    $("#tipdoc_beneficiario").val(tipdoc_beneficiario);
    
    if (tipodoc_beneficiario == "1"||tipodoc_beneficiario == "DNI") {
      if (dni_paciente.length != 8) { 
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "El N° DNI del paciente debe tener 8 dígitos.",
              icon: "error",
              timer: 1500,
            });  
        $("#dni_beneficiario").val("");
        $('#dni_beneficiario').focus(); 
        $('#dni_beneficiario').addClass('borde-alerta');
        return ;
      }else{ 
        $('#dni_beneficiario').removeClass('borde-alerta');
      }
    }else{
      swal({
        buttons: false,
        title: "Mensaje de Error",
        text: "La Consulta de Datos tiene que realizarse únicamente por un Número de DNI.",
        icon: "error",
        timer: 1500,
      }); 
        $("#dni_beneficiario").val("");
        $('#dni_beneficiario').focus();   
        $('#dni_beneficiario').addClass('borde-alerta');
      return;
    }

    if(dni_paciente==""){
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "Falta ingresar el Documento del paciente.",
              icon: "error",
              timer: 1500,
            }); 
        $("#dni_beneficiario").val("");
        $('#dni_beneficiario').focus(); 
        $('#dni_beneficiario').addClass('borde-alerta');
       return ;
    }
 
    $('#dni_beneficiario').prop('disabled', false);
    $('#btnBuscarDniPaciente').prop('disabled', false); 
    $("#dni_beneficiario").blur();
 
    $('#tipo_seguro_estadows').val(''); 
    var paciente_no_existe=false;
    $.ajax({ 
      url: "{{ route('obtener_datos_paciente_ajax') }}",         
      type: "GET",
      dataType: "JSON",
      data: {           
        dni: dni_paciente,
        tipo_documento:tipodoc_beneficiario      
      },
			beforeSend: function() {
				swal({
					buttons: false,
					title: "Realizando Consulta ...",
					text: "Espere un momento mientras se genera la solicitud.",
					icon: "warning",
          buttons: false,
          closeOnClickOutside: false,
          closeOnEsc: false
				});
			},
      success: function(result) {  
        console.log("result:result:result:result:: "+result);
        if(result!=null){
          if (result[0] != undefined) { 
             paciente_no_existe=true;

          /*}else if(result.estado_titular=='El asegurado no esta activo'){
            
            console.log(result.estado_titular);
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "CUIDADO, EL ASEGURADO NO ESTÁ ACTIVO",
              icon: "error",
              timer: 2800,
            });
            $('#btnBuscar').prop('disabled', false);
            $("#dni_beneficiario").val("");
            $('#dni_beneficiario').focus(); 
            $('#dni_beneficiario').addClass('borde-alerta');
            return;

          }else if(result.estado_titular=='No se encontro ningún resultado'){
            
            console.log(result.estado_titular);
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "CUIDADO, NO SE HA ENCONTRADO NINGÚN REGISTRO VÁLIDO.",
              icon: "error",
              timer: 2800,
            });
            $('#btnBuscar').prop('disabled', false);
            $("#dni_beneficiario").val("");
            $('#dni_beneficiario').focus(); 
            $('#dni_beneficiario').addClass('borde-alerta');
            return;*/

          }else if(result.id>0 && result.id_tipo_doc_ident>0){ //tabla asegurados 
            

            /*if(result.id_situacion != undefined && result.id_situacion != '' && result.id_situacion != "" && result.id_situacion != null){ 
              if(result.id_situacion == "FALLECIDO"){ 
                console.log(result.estado_titular);
                swal({
                  buttons: false,
                  title: "Mensaje de Error",
                  text: "CUIDADO, EL PACIENTE CONULTADO, SE ENCUENTRA COMO FALLECIDO.",
                  icon: "error",
                  timer: 2800,
                });
                $('#btnBuscar').prop('disabled', false);
                $("#dni_beneficiario").val("");
                $('#dni_beneficiario').focus(); 
                $('#dni_beneficiario').addClass('borde-alerta');
                return;
              } 
            } */

            $('#dni_beneficiario').removeClass('borde-alerta');
            console.log('tabla asegurados-- removeClass'); 
            console.log('tabla asegurados--::: '+result.codigo_ubig);   
            paciente = result.paterno + ' ' + result.materno + ', ' + result.nombre;
            
            /*$('#tipo_seguro_vencimiento').val('');  
            if(result.caducidad_seguro != undefined && result.caducidad_seguro != '' && result.caducidad_seguro != "" && result.caducidad_seguro != null){ 
              var anio = result.caducidad_seguro.substring(0, 4);
              var mes = result.caducidad_seguro.substring(4, 6);
              var dia = result.caducidad_seguro.substring(6, 8);
              $('#tipo_seguro_vencimiento').val(dia+'/'+mes+'/'+anio);  
            }*/
             
            /*$('#tipo_seguro_estadows').val('');  
            if(result.estado_seguro != undefined && result.estado_seguro != '' && result.estado_seguro != "" && result.estado_seguro != null){ 
              $('#tipo_seguro_estadows').val(result.estado_seguro);  
            }
            $('#tipo_seguro_numero').val('');  
            $('#tipo_seguro_numero').val(result.nro_doc_ident);  */


            $('#beneficiario').val(paciente);            
            $('#id_tipo_doc_ident').val(tipodoc_beneficiario);
            $('#tipo_doc_ident').val(result.tipo_doc_ident);
            $('#nro_doc_ident').val(result.nro_doc_ident);
            $('#nombre_beneficiario').val(result.nombre);
            $('#paterno_beneficiario').val(result.paterno);
            //if(result.materno != ''){
              $('#materno_beneficiario').val(result.materno);
            /*}else{
              $('#materno_beneficiario').val('');
            }*/
            $('#sexo').val(result.sexo);
            $('#fecha_nac').val(result.fecha_nac);
            $('#parentesco_paciente').val(result.parentesco);
            $('#nro_historia').val(result.nro_historia);

            if(result.domicilio != null && result.domicilio !=''){
              $('#direccion_actual').val(result.domicilio.replace(/\s+/g, ' ').trim()); 
            }

            /*if(result.direccion_nacimiento != null && result.direccion_nacimiento !=''){
              $('#direccion_nacimiento').val(result.direccion_nacimiento.replace(/\s+/g, ' ').trim()); 
            } */
            
            /*console.log('--id_estado_civil--'+result.id_estado_civil+'---')
            if(result.id_estado_civil != null && result.id_estado_civil !='' && result.id_estado_civil !='0'){
              $('#id_estado_civil_tomando_dato').val(result.id_estado_civil);
            }*/
             
            $('#codigo_ubig_actual').val('');
            $('#codigo_ubig_actual_id_departamento').val('');
            $('#codigo_ubig_actual_id_provincia').val('');
            $('#codigo_ubig_actual_id_distrito').val('');
            if(result.codigo_ubig != undefined && result.codigo_ubig != null){
              if( result.codigo_ubig.length == 6){
                $('#codigo_ubig_actual').val(result.codigo_ubig);  
                if(result.id_departamento != undefined && result.id_departamento != null){
                  $('#codigo_ubig_actual_id_departamento').val(result.id_departamento);  
                  if(result.id_provincia != undefined && result.id_provincia != null){
                    $('#codigo_ubig_actual_id_provincia').val(result.id_provincia);
                    if(result.id_distrito != undefined && result.id_distrito != null){
                      $('#codigo_ubig_actual_id_distrito').val(result.id_distrito);  
                    }  
                  }
                }
              }
            }
            
            
            /*$('#codigo_ubig_nacimiento').val('');
            $('#codigo_ubig_nacimiento_id_departamento').val('');
            $('#codigo_ubig_nacimiento_id_provincia').val('');
            $('#codigo_ubig_nacimiento_id_distrito').val('');
            if(result.codigo_ubig_nacimiento != undefined && result.codigo_ubig_nacimiento != null){
              if( result.codigo_ubig_nacimiento.length == 6){
                $('#codigo_ubig_nacimiento').val(result.codigo_ubig_nacimiento);  
                if(result.id_departamento_nacimiento != undefined && result.id_departamento_nacimiento != null){
                  $('#codigo_ubig_nacimiento_id_departamento').val(result.id_departamento_nacimiento);  
                  if(result.id_provincia_nacimiento != undefined && result.id_provincia_nacimiento != null){
                    $('#codigo_ubig_nacimiento_id_provincia').val(result.id_provincia_nacimiento);
                    if(result.id_distrito_nacimiento != undefined && result.id_distrito_nacimiento != null){
                      $('#codigo_ubig_nacimiento_id_distrito').val(result.id_distrito_nacimiento);  
                    }  
                  }
                }
              }
            }*/

            /*$('#nro_historia').val('');
            if(result.nro_historia == undefined || result.nro_historia == '' || result.nro_historia == ""){
              $('#nro_historia').val(result.nro_doc_ident);  
            }else{
              $('#nro_historia').val(result.nro_historia);  
            }*/

            /*$('#grado_paciente').val('');   
              if(result.grado != undefined && result.grado != '' && result.grado != "" && result.grado != null){ 
                $('#grado_paciente').val(result.grado);  
              } else{
                if(result.id_grado != undefined && result.id_grado != '' && result.id_grado != "" && result.id_grado != null){ 
                  $('#grado_paciente').val(result.id_grado);  
                }  
              } */

            $('#id_situacion').val('');  
            $('#situacion_paciente').val('');  
            $('#nombre_titular').html(''); 

            
            $('#sexo_titular').html('');  
            $('#dni_titular').html('');  
            $('#grado_titular').html(''); 
            $('#situacion_titular').html('');
            $('#unidad_titular').html(''); 


            $('#dni_de_titular').val('');  
            $('#grado_de_titular').val(''); 
            $('#situacion_de_titular').val('');   
            $('#unidad_de_titular').val(''); 

            if (result.situacion_titular != undefined) {
              $("#tab1DatosPacienteTitularLI").show();
              $('#dni_titular').html(result.nrodoctitular); 
              $('#dni_de_titular').val(result.nrodoctitular);
              $('#nombre_titular').html(result.apepattitular+' '+result.apemattitular+' '+result.nomtitular);  
              $('#grado_titular').html(result.grado_titular); 
              $('#grado_de_titular').html(result.grado_titular);
              $('#situacion_titular').html(result.situacion_titular); 
              $('#situacion_de_titular').val(result.situacion_titular); 
              $('#unidad_titular').html(result.unidad_pnptitular);
              $('#unidad_de_titular').val(result.unidad_pnptitular); 
            }
            /*if(result.situacion_titular != undefined && result.situacion_titular != '' && result.situacion_titular != "" && result.situacion_titular != null){ 
              $('#situacion_paciente').val(result.situacion_titular);  
            } */

            /*if(result.tipo_beneficiario == undefined){ 
              //$('#tipo_beneficiario').val(result.id_tipo_beneficiario); 
              //$('#parentesco_paciente').val(result.id_tipo_beneficiario); 
              getTipo_beneficiario(result.id_tipo_beneficiario);
              if(result.id_tipo_beneficiario == 'TITULAR'){
                $('#dni_de_titular').val(dni_paciente);
                $("#tab1DatosPacienteTitularLI").hide();  
              }else if(result.id_tipo_beneficiario == ''||result.id_tipo_beneficiario == null || result.id_tipo_beneficiario == undefined){
                $("#tab1DatosPacienteTitularLI").hide();
              }else{ 
                $("#tab1DatosPacienteTitularLI").show();
                ////DATOS DE TITULAR  
                if(result.nrodoctitular != undefined && result.nrodoctitular != '' && result.nrodoctitular != "" && result.nrodoctitular != null){ 
                  $('#dni_titular').html(result.nrodoctitular); 
                  $('#dni_de_titular').val(result.nrodoctitular);
                  $('#nombre_titular').html(result.apepattitular+' '+result.apemattitular+' '+result.nomtitular);  
                  if(result.gradotitular=='' || result.gradotitular==null){
                    $('#grado_titular').html('-'); 
                    $('#grado_de_titular').html('-'); 
                  }else{
                    $('#grado_titular').html(result.gradotitular); 
                    $('#grado_de_titular').html(result.gradotitular); 
                  }
                   
                  if(result.situaciontitular=='' || result.situaciontitular==null){
                    $('#situacion_titular').html('-'); 
                    $('#situacion_de_titular').val('-'); 
                  }else{
                    $('#situacion_titular').html(result.situaciontitular); 
                    $('#situacion_de_titular').val(result.situaciontitular); 
                  } 
                   
                  if(result.unidad_pnptitular=='' || result.unidad_pnptitular==null){
                    $('#unidad_titular').html('-');
                    $('#unidad_de_titular').val('-'); 
                  }else{
                    $('#unidad_titular').html(result.unidad_pnptitular);
                    $('#unidad_de_titular').val(result.unidad_pnptitular); 
                  }  
                   
                  if(result.id_tipo_beneficiario != 'TITULAR'){
                    if(result.sexotitular != undefined && result.sexotitular=='' || result.sexotitular==null){
                      $('#sexo_titular').html('-'); 
                    }else{
                      $('#sexo_titular').html(result.sexotitular ); 
                    }    
                  }else{ 
                    $('#dni_de_titular').val(dni_paciente);
                    if(result.nomsexo != undefined && result.nomsexo=='' || result.nomsexo==null){
                      $('#sexo_titular').html('-'); 
                    }else{
                      $('#sexo_titular').html(result.nomsexo ); 
                    }   
                  }

                }
              }
            }else{ 
              $('#tipo_beneficiario').val(result.tipo_beneficiario); 
              $('#parentesco_paciente').val(result.tipo_beneficiario);  
              if(result.tipo_beneficiario == 'TITULAR'){
                $('#dni_de_titular').val(dni_paciente);
                $("#tab1DatosPacienteTitularLI").hide();
                if(result.id_situacion != undefined && result.id_situacion != '' && result.id_situacion != "" && result.id_situacion != null){ 
                  $('#situacion_paciente').val(result.id_situacion);  
                }  
              }else if(result.tipo_beneficiario == ''||result.tipo_beneficiario == null ||result.tipo_beneficiario == undefined){ 
                $("#tab1DatosPacienteTitularLI").hide();
              }else{ 
                
                $("#tab1DatosPacienteTitularLI").show();
                ////DATOS DE TITULAR 
                if(result.nrodoctitular != undefined && result.nrodoctitular != '' && result.nrodoctitular != "" && result.nrodoctitular != null){ 
                  $('#dni_titular').html(result.nrodoctitular); 
                  $('#dni_de_titular').val(result.nrodoctitular);
                  $('#nombre_titular').html(result.apepattitular+' '+result.apemattitular+' '+result.nomtitular);   
                  if(result.gradotitular=='' || result.gradotitular==null){
                    $('#grado_titular').html('-'); 
                    $('#grado_de_titular').val('-'); 
                  }else{
                    $('#grado_titular').html(result.gradotitular); 
                    $('#grado_de_titular').val(result.gradotitular); 
                  }
                   
                  if(result.situaciontitular=='' || result.situaciontitular==null){
                    $('#situacion_titular').html('-'); 
                    $('#situacion_de_titular').val('-'); 
                  }else{
                    $('#situacion_titular').html(result.situaciontitular); 
                    $('#situacion_de_titular').val(result.situaciontitular); 
                  } 
                   
                  if(result.unidad_pnptitular=='' || result.unidad_pnptitular==null){
                    $('#unidad_titular').html('-'); 
                    $('#unidad_de_titular').val('-');
                  }else{
                    $('#unidad_titular').html(result.unidad_pnptitular);
                    $('#unidad_de_titular').val(result.unidad_pnptitular); 
                  }  
                   
                  if(result.id_tipo_beneficiario != 'TITULAR'){
                    if(result.sexotitular != undefined && result.sexotitular=='' || result.sexotitular==null){
                      $('#sexo_titular').html('-'); 
                    }else{
                      $('#sexo_titular').html(result.sexotitular ); 
                    }    
                  }else{ 
                    $('#dni_de_titular').val(dni_paciente);
                    if(result.nomsexo != undefined && result.nomsexo=='' || result.nomsexo==null){
                      $('#sexo_titular').html('-'); 
                    }else{
                      $('#sexo_titular').html(result.nomsexo ); 
                    }   
                  }
                   
                }
              }
            }*/

            //$('#cip_beneficiario').val(result.cip); 


            calcular_edad_paciente(result.fecha_nac);
            
            ////obtenemos la foto
            if(result.foto != undefined && result.foto!=null){  
              $('#foto_paciente').val(result.foto);  
              var imagen = document.getElementById("foto_imagen_paciente"); 
              //console.log(imagen);  
              imagen.src = "data:image/jpeg;base64," + result.foto;
            }

            swal({
              buttons: false,
              title: "Datos Correctos",
              text: "Los Datos fueron cargado de forma correcta.",
              icon: "success",
              timer: 1500,
            });  
 
           
 

            cargar_datos_desde_emergencia(valor_parametro);



            /*
            if(edad>=17 && edad<60){ 
              ////CONSULTA SI TIENE ACOMPANIANTE 
              consultar_tiene_acompaniante();
            } 
            */
            
                       
            
            var dni_beneficiario= $("#dni_beneficiario").val();
            var tipdoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
            cargar_datos_adicionale_by_documento_paciente(dni_beneficiario,tipdoc_beneficiario); 

          }else if (tipodoc_beneficiario=="1" && result.codigoMensaje == '00') { //reniec 
            console.log('tabla reniec');
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "CUIDADO, el documento ingresado corresponde a un CIVIL.",
              icon: "error",
              timer: 2500,
            }); 
            $("#dni_beneficiario").val("");
            $('#dni_beneficiario').focus();
            $('#dni_beneficiario').addClass('borde-alerta');
            return;

          }else if(result.nompaisdelafiliado!=""){ //tabla Saludpol
            

            if(result.situacion != undefined && result.situacion != '' && result.situacion != "" && result.situacion != null){ 
                if(result.situacion == "FALLECIDO"){ 
                  console.log(result.estado_titular);
                  swal({
                    buttons: false,
                    title: "Mensaje de Error",
                    text: "CUIDADO, EL PACIENTE CONULTADO, SE ENCUENTRA COMO FALLECIDO.",
                    icon: "error",
                    timer: 2800,
                  });
                  $('#btnBuscar').prop('disabled', false);
                  $("#dni_beneficiario").val("");
                  $('#dni_beneficiario').focus(); 
                  $('#dni_beneficiario').addClass('borde-alerta');
                  return;
                } 
              } 
              
            $('#dni_beneficiario').removeClass('borde-alerta');
            
            console.log('tabla Saludpol');
            $('#tipo_registro').val('1');
            paciente = result.apepatafiliado + ' ' + result.apematafiliado + ', ' + result.nomafiliado;
            $('#beneficiario').val(paciente);      
            
            $('#id_tipo_doc_ident').val(tipodoc_beneficiario);
            $('#tipo_doc_ident').val(result.nomtipdocafiliado);
            $('#nro_doc_ident').val(result.nrodocafiliado);
            $('#nombre_beneficiario').val(result.nomafiliado);
            $('#paterno_beneficiario').val(result.apepatafiliado); 
            if(result.apematafiliado != ''){
              $('#materno_beneficiario').val(result.apematafiliado);
            }else{
              $('#materno_beneficiario').val('-');
            }
            $('#parentesco_paciente').val(result.parentesco); 
            $('#sexo').val(result.nomsexo);
            $('#fecha_nac').val(result.fecnacafiliado);
            $('#direccion_actual').val('');
            //$('#tipo_beneficiario').val(result.id_tipo_beneficiario);
            
            getTipo_beneficiario(result.id_tipo_beneficiario);

            $('#nro_historia').val('');
            if(result.nro_historia == undefined || result.nro_historia == '' || result.nro_historia == ""){
              $('#nro_historia').val(result.nro_doc_ident);  
            }else{
              $('#nro_historia').val(result.nro_historia);  
            }
            
            $('#tipo_seguro_vencimiento').val('');  
            if(result.caducidad_seguro != undefined && result.caducidad_seguro != '' && result.caducidad_seguro != "" && result.caducidad_seguro != null){ 
              var anio = result.caducidad_seguro.substring(0, 4);
              var mes = result.caducidad_seguro.substring(4, 6);
              var dia = result.caducidad_seguro.substring(6, 8);
              $('#tipo_seguro_vencimiento').val(dia+'/'+mes+'/'+anio);  
            }
             
            $('#tipo_seguro_estadows').val('');  
            if(result.estado_seguro != undefined && result.estado_seguro != '' && result.estado_seguro != "" && result.estado_seguro != null){ 
              $('#tipo_seguro_estadows').val(result.estado_seguro);  
            }
            $('#tipo_seguro_numero').val('');  
            $('#tipo_seguro_numero').val(result.nrodocafiliado);  
            

            calcular_edad_paciente(result.fecnacafiliado);

            
            $("#tab1DatosPacienteTitularLI").show();
                ////DATOS DE TITULAR 
                console.log(result.nrodoctitular);
            if(result.nrodoctitular != undefined && result.nrodoctitular != '' && result.nrodoctitular != "" && result.nrodoctitular != null){ 
              $('#dni_titular').html(result.nrodoctitular); 
              $('#dni_de_titular').val(result.nrodoctitular);
              $('#nombre_titular').html(result.apepattitular+' '+result.apemattitular+' '+result.nomtitular);  

              console.log(result.gradotitular);
              if(result.gradotitular=='' || result.gradotitular==null){
                $('#grado_titular').html('-'); 
                $('#grado_de_titular').val('-'); 
              }else{
                $('#grado_titular').html(result.gradotitular);  
                $('#grado_de_titular').val(result.gradotitular); 
              }
              
            


              if(result.situacion != undefined && result.situacion=='' || result.situacion==null){
                $('#situacion_paciente').val(""); 
              }else{ 
                $('#situacion_paciente').val(result.situacion); 
              }
                   
              if(result.situaciontitular != undefined && result.situaciontitular != undefined && result.situaciontitular=='' || result.situaciontitular==null){
                $('#situacion_titular').html('-'); 
                $('#situacion_de_titular').val('-'); 
              }else{ 
                $('#situacion_titular').html(result.situaciontitular ); 
                $('#situacion_de_titular').val(result.situaciontitular); 
              } 
                   
                   
              if(result.unidad_pnp != undefined && result.unidad_pnp=='' || result.unidad_pnp==null){
                $('#unidad_titular').html('-'); 
                $('#unidad_de_titular').val('-');
              }else{
                $('#unidad_titular').html(result.unidad_pnp );
                $('#unidad_de_titular').val(result.unidad_pnp); 
              }  
                   
              if(result.id_tipo_beneficiario != 'TITULAR'){
                if(result.sexotitular != undefined && result.sexotitular=='' || result.sexotitular==null){
                  $('#sexo_titular').html('-'); 
                }else{
                  $('#sexo_titular').html(result.sexotitular ); 
                }    
              }else{ 
                $('#dni_de_titular').val(dni_paciente);
                if(result.nomsexo != undefined && result.nomsexo=='' || result.nomsexo==null){
                  $('#sexo_titular').html('-'); 
                }else{
                  $('#sexo_titular').html(result.nomsexo ); 
                }   
              }
             
              $('#codigo_ubig_actual').val('');
              $('#codigo_ubig_actual_id_departamento').val('');
              $('#codigo_ubig_actual_id_provincia').val('');
              $('#codigo_ubig_actual_id_distrito').val('');
              if(result.codigo_ubig != undefined && result.codigo_ubig != null){
                if( result.codigo_ubig.length == 6){
                  $('#codigo_ubig_actual').val(result.codigo_ubig);  
                  if(result.id_departamento != undefined && result.id_departamento != null){
                    $('#codigo_ubig_actual_id_departamento').val(result.id_departamento);  
                    if(result.id_provincia != undefined && result.id_provincia != null){
                      $('#codigo_ubig_actual_id_provincia').val(result.id_provincia);
                      if(result.id_distrito != undefined && result.id_distrito != null){
                        $('#codigo_ubig_actual_id_distrito').val(result.id_distrito);  
                        

                        var id_dep_capturado = $("#codigo_ubig_actual_id_departamento").val();  
                        var id_pro_capturado = $("#codigo_ubig_actual_id_provincia").val(); 
                        var id_dis_capturado = $("#codigo_ubig_actual_id_distrito").val(); 
                        setTimeout(function() { 
                            console.log(id_dep_capturado);
                            $("#id_departamento_actual").val(id_dep_capturado).change();  
                        }, 5000);  

                        setTimeout(function() { 
                            console.log(id_pro_capturado);
                            $("#id_provincia_actual").val(id_pro_capturado).change();   
                        }, 6200);   

                        setTimeout(function() { 
                            console.log(id_dis_capturado);
                            $("#id_distrito_actual").val(id_dis_capturado).change();   
                        }, 7500);    

 
                      }  
                    }
                  }
                }
              }
                     
            }

            swal({
              buttons: false,
              title: "Datos Correctos",
              text: "Los Datos fueronc argado de forma correcta.",
              icon: "success",
              timer: 1500,
            });  
            
           
 
          
          cargar_datos_desde_emergencia(valor_parametro);
          


            
          var dni_beneficiario= $("#dni_beneficiario").val();
          var tipdoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
          cargar_datos_adicionale_by_documento_paciente(dni_beneficiario,tipdoc_beneficiario); 

          }else{
            //alert("Paciente NO encontrado");
            paciente_no_existe=true;
          } 
        }else{
            //alert("Paciente NO encontrado");
            paciente_no_existe=true;
        }

        console.log('paciente_no_existe: '+paciente_no_existe);

        if(paciente_no_existe==true){ //habilitar para nuevo registro
          bootbox.alert("Titulo","Paciente no encontrado");
          /*bootbox.confirm({
            message: "Paciente NO encontrado. <b>¿Desea registrarlo manualmente?</b>",
            buttons: {
              confirm: {
                  label: 'Si',
                  className: 'btn-success'
              },
              cancel: {
                  label: 'No',
                  className: 'btn-danger'
              }
            }
          }); */

        }else{ //paciente existe
          llenar_manual_datos_paciente(false);
        }     
        //
        $('#content').html('');
        $('#dni_beneficiario').prop('disabled', false);
        $('#btnBuscarDniPaciente').prop('disabled', false);

      }, 
      error: function(xhr, status) {
        $("#tab1DatosPacienteTitularLI").hide();
        swal({
          buttons: false,
          title: "Mensaje de Error",
          text: "Ha ocurrido un error al consultar el N° Documento del paciente.",
          icon: "error",
          timer: 2500,
        });  
        $('#btnBuscar').prop('disabled', false);
        $("#dni_beneficiario").val("");
        $('#dni_beneficiario').focus();  
        $('#dni_beneficiario').addClass('borde-alerta'); 
      }, 
      complete : function(xhr, status) {
        $('#content').html('');
        $('#dni_beneficiario').prop('disabled', false);
        $('#btnBuscarDniPaciente').prop('disabled', false);  
      }
    });    
   
  }
 


function calcular_edad_paciente(fecha_nac){

  if(fecha_nac != null && fecha_nac != undefined && fecha_nac.length>8 ){
    var oldDate = fecha_nac; //20/04/1946
    var values = oldDate.split("/");
    if(values.length==1){
      var values = oldDate.split("-");
      var dia = values[2];
      var mes = values[1];
      var ano = values[0];
      $('#fecha_nac').val(dia+'/'+mes+'/'+ano);
    }else{
      var dia = values[0];
      var mes = values[1];
      var ano = values[2];
    }
    console.log(ano+'-'+mes+'-'+dia);
    $('#fecha_nacimiento_beneficiario').val(ano+'-'+mes+'-'+dia);

    calcularEdadPersona(ano,mes,dia,'input','edad_detallada','edad')

    setTimeout(function() {
      var edad = $("#edad").val();
      if(edad != null && edad != undefined && edad>0 || edad == '' ){
          if(edad<=16){ 
            limpiar_responsable(); 
            $("#datos_de_apoderado").show(); 
            $("#datos_de_responsable").hide();
            $("#copiar_paciente_a_acompaniante").hide();
            $('#nro_doc_ident_apoderado').focus(); 
          }else if(edad>=60){
            limpiar_apoderado();
            $("#datos_de_apoderado").hide();
            $("#datos_de_responsable").show(); 
            $("#copiar_paciente_a_acompaniante").show();
            //$('#nro_doc_ident_responsable').focus();
          }else{
            limpiar_responsable();
            limpiar_apoderado();
            $('#nro_doc_ident_acompaniante').focus();
          }
        }
      }, 1000);
    }
  }

  function consultar_tiene_acompaniante(){
      swal({
      buttons: true,
      title: "¿EL PACIENTE INGRESADO, VIENE CON ACOMPAÑANTE?",
      text: "Recuerde verificar que todos los datos sean correctos.",
      icon: "warning",
      dangerMode: true,
      buttons: ["Sí", "No"],
      closeOnClickOutside: false,
      closeOnEsc: false 
      }).then((willDelete) => { 
        if (willDelete) {
        ///SI NO TIENE ACOMPANIANTE COPIA AL PACIENTE
          setTimeout(function() {
            procesar_copia_de_acompaniante();
          }, 1300); 
        } else { 
        ///SI TIENE ACOMPANIANTE SE LIMPIAN LOS CAMPOS Y SALTA PULSOR
          limpiar_acompaniante();
          
          var edad = $("#edad").val();
          if(edad != null && edad != undefined && edad>0 || edad == '' ){
            if(edad>16 && edad<60){
              $("#copiar_paciente_a_acompaniante").show(); 
            }else{
              $("#copiar_paciente_a_acompaniante").hide();
            }
          }

          $('#nro_doc_ident_responsable').val("");  
          //$('#nro_doc_ident_responsable').focus();
        }

      });
  }


  function llenar_manual_datos_paciente(valor){
    if(valor==true){    
      $('#tipo_registro').val('0');
          $("#paterno").val("").attr("readonly", false);
          $("#materno").val("").attr("readonly", false);
          $("#nombre").val("").attr("readonly", false);  

          $("#sexo").hide();
          $("#selectSexo").val("");
          $("#selectSexo").show();
          $("#fecha_nac").hide();
          $("#fecha_nac2").val("");
          $("#fecha_nacimiento_editable").show();
          document.getElementById("paterno").focus(); 
    }else{
        $("#nro_doc_ident").attr("readonly", true);
        $("#paterno").attr("readonly", true);
        $("#materno").attr("readonly", true);
        $("#nombre").attr("readonly", true);  
        $("#fecha_nac").attr("readonly", true);
        $("#sexo").show();
        $("#selectSexo").hide();
        $("#fecha_nac").show();
        $("#fecha_nacimiento_editable").hide();
        $("#dni_beneficiario").attr("readonly", false); 
        document.getElementById("dni_beneficiario").focus();  
    }   
  }

function consultar_tiene_responsable(){
    $("#dni_beneficiario").blur();
    swal({
    buttons: true,
    title: "¿DESEA ASIGNAR AL PACIENTE COMO RESPONSABLE?",
    text: "Recuerde verificar que todos los datos sean correctos.",
    icon: "warning",
    dangerMode: true,
    buttons: ["Sí", "No"],
    closeOnClickOutside: false,
    closeOnEsc: false  
    }).then((willDelete) => { 
      if (willDelete) {
      ///SI TIENE ACOMPANIANTE SE LIMPIAN LOS CAMPOS Y SALTA PULSOR
        limpiar_responsable();
        $('#nro_doc_ident_responsable').val("");  
        //$('#nro_doc_ident_responsable').focus();
      } else { 
      ///SI NO TIENE ACOMPANIANTE COPIA AL PACIENTE
        setTimeout(function() {
          procesar_copia_de_responsable();
        }, 1300); 
      }

    });
}
</script>