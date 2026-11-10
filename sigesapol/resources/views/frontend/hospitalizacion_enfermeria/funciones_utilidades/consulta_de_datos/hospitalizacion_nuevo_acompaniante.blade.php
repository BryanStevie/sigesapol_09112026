

<script>
    function validar_documento_acompaniante(){
      var input = document.getElementById('nro_doc_ident_acompaniante'); 
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
        var tipodoc_beneficiario = $("#tipo_documento_acompaniante_tipo").val();
        if (tipodoc_beneficiario == "1"||tipodoc_beneficiario == "DNI") {
          if (this.value.length > 8) {
              this.value = this.value.slice(0, 8);
            }
        }
      }); 
    }
     
    function getReniecByDni_acompaniante() { 
        limpiar_acompaniante();
        var tipodoc_beneficiario = $("#tipo_documento_acompaniante_tipo").val();
        var dni_acompaniante = $("#nro_doc_ident_acompaniante").val();
        var dni_beneficiario = $("#dni_beneficiario").val();
        var tipo_documento_acompaniante = $("#tipo_documento_acompaniante_tipo option:selected").text();
        $("#tipo_documento_acompaniante").val(tipo_documento_acompaniante); 
           
        if (tipodoc_beneficiario == "1"||tipodoc_beneficiario == "DNI") {
            if (dni_acompaniante.length != 8) {
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "El N° DNI debe tener 8 dígitos.",
              icon: "error",
              timer: 1500,
            }); 
            $('#nro_doc_ident_acompaniante').addClass('borde-alerta');
            $("#nro_doc_ident_acompaniante").val("");
            $('#nro_doc_ident_acompaniante').focus(); 
            return 0;
            }
        }else{
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "La Consulta de Datos tiene que realizarse únicamente por un Número de DNI.",
              icon: "error",
              timer: 1500,
            }); 
            $('#nro_doc_ident_acompaniante').addClass('borde-alerta');
            $("#nro_doc_ident_acompaniante").val("");
            $('#nro_doc_ident_acompaniante').focus(); 
          return;
        }
    
        if(dni_acompaniante==""){
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "Falta ingresar el Documento del Acompañante.",
              icon: "error",
              timer: 1500,
            }); 
            $('#nro_doc_ident_acompaniante').addClass('borde-alerta');
            $("#nro_doc_ident_acompaniante").val("");
            $('#nro_doc_ident_acompaniante').focus(); 
            return;
        } 
                    /*  
                    var edad= $("#edad").val(); 
                    if(edad<=16){
                        copiar_datos_paciente_a_acompaniante.checked = false;
                        swal({
                            buttons: false,
                            title: "Mensaje de Error",
                            text: "El Acompañante NO PUEDE SER MENOR DE EDAD.",
                            icon: "error",
                            timer: 2500,
                        }); 
                        $("#nro_doc_ident_acompaniante").val("");
                        $('#nro_doc_ident_acompaniante').focus();
                        $("#copiar_paciente_a_acompaniante").hide();
                        return;
                    } else if(edad>=60){
                        copiar_datos_paciente_a_acompaniante.checked = false;
                        
                        if(dni_acompaniante == dni_beneficiario){
                            swal({
                              buttons: false,
                              title: "Mensaje de Error",
                                text: "El N° de DNI ingresado no puede ser igual al N° de DNI del PACIENTE, POR SER UN ADULTO MAYOR.",
                              icon: "error",
                              timer: 3000,
                            });   
                            $("#nro_doc_ident_acompaniante").val("");
                            $('#nro_doc_ident_acompaniante').focus();  
                            $("#copiar_paciente_a_acompaniante").hide();
                            return;
                        }
                       
                         swal({
                                buttons: false,
                                title: "Mensaje de Error",
                                text: "El Acompañante NO PUEDE SER UN ADULTO MAYOR.",
                                icon: "error",
                                timer: 2500,
                            }); 
                            $("#nro_doc_ident_acompaniante").val("");
                            $('#nro_doc_ident_acompaniante').focus();
                         
                    }else{
                        $("#copiar_paciente_a_acompaniante").show(); 
                    } 
        */
        
        /*
        if(dni_acompaniante == dni_beneficiario){
            swal({
              buttons: false,
              title: "Mensaje de Error",
                text: "El N° de DNI ingresado no puede ser igual al N° de DNI del PACIENTE.",
              icon: "error",
              timer: 2500,
            });   
            $("#nro_doc_ident_acompaniante").val("");
            $('#nro_doc_ident_acompaniante').focus(); 
            return;
        } 
        */
        
        $('#nro_doc_ident_acompaniante').removeClass('borde-alerta');
        $('#btnBuscar_acompaniante').prop('disabled', true);  
    
        var paciente_no_existe=false;
        $.ajax({ 
            url: "{{ route('obtener_datos_solo_reniec_ajax') }}",         
            type: "GET",
            dataType: "JSON",
            data: {           
            dni: dni_acompaniante,
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
            success: function(result) { //alert(result.id); 
            console.log('result : result: ' + result);
            if(result!=null){
              /**/
                if (result[0] != undefined) { 
                    paciente_no_existe=true;
        
                }else if (tipodoc_beneficiario=="1" && result.codigoMensaje == '00') { //reniec
                    
                    console.log('tabla reniec'); 
                     
                    ////calcular edad para poder validar
                    calcular_edad_acompaniante(result.fecha_nac); 
    
                    //validar edad que no sea menor de edad
                    var edad_acompaniante_validar_ma_edad = $("#edad_de_acompaniante").val();   
                    if(edad_acompaniante_validar_ma_edad<17){
                      
                      swal({
                        buttons: false,
                        title: "Mensaje de Error",
                        text: "El N° DNI del ACOMPAÑANTE ingresado no puede ser menor de edad.",
                        icon: "error",
                        timer: 1500,
                      });  
                      $('#nro_doc_ident_acompaniante').addClass('borde-alerta');
                      return;

                    }else{
                      
                      if(dni_acompaniante == dni_beneficiario){
                          swal({
                              buttons: false,
                              title: "Mensaje de Error",
                                text: "El N° de DNI ingresado no puede ser igual al N° de DNI del PACIENTE.",
                              icon: "error",
                              timer: 3000,
                          });   
                          $('#nro_doc_ident_acompaniante').addClass('borde-alerta');
                          $("#nro_doc_ident_acompaniante").val("");
                          $('#nro_doc_ident_acompaniante').focus();   
                          return;
                      }

                      if(edad_acompaniante_validar_ma_edad>60){ 
                        swal({
                          buttons: false,
                          title: "Mensaje de Error",
                          text: "El N° DNI del ACOMPAÑANTE NO PUDE SER EL DE UN ADULTO MAYOR.",
                          icon: "error",
                          timer: 1500,
                        });  
                        $('#nro_doc_ident_acompaniante').addClass('borde-alerta');
                        $("#nro_doc_ident_acompaniante").val("");
                        $('#nro_doc_ident_acompaniante').focus();  
                        return;
                      }
                    }

                    ///datos cargados 
                    paciente = result.paterno + ' ' + result.materno + ', ' + result.nombres;
                    $('#nom_completo_acompaniante').val(paciente);         
                    $('#numero_documento_acompaniante').val(dni_acompaniante);
                    $('#nro_doc_ident_acompaniante_vista').val(dni_acompaniante);
                    $('#nombre_acompaniante').val(paciente); 
                    $('#direccion_acompaniante').val('');
                    if(result.direccionDomicilio!=null && result.direccionDomicilio!=undefined){
                      $('#direccion_acompaniante').val(result.direccionDomicilio.replace(/\s+/g, ' ').trim()); 
                    }
            
                    /*CARGA DE DATOS DE UBIGEO*/
                    $('#codigo_ubig_acompaniante').val(result.codigo_ubigDomicilio);
                    if(result.codigo_ubigDomicilio!=null && result.codigo_ubigDomicilio != undefined){  
                      $('#codigo_ubig_acompaniante_id_departamento').val(result.id_departamentoDomicilio);
                      $('#codigo_ubig_acompaniante_id_provincia').val(result.id_provinciaDomicilio);
                      $('#codigo_ubig_acompaniante_id_distrito').val(result.id_distritoDomicilio);
                      llenar_combos_de_ubigeo_acompaniante();
                    }else{
                      $('#codigo_ubig_acompaniante_id_departamento').val('');
                      $('#codigo_ubig_acompaniante_id_provincia').val('');
                      $('#codigo_ubig_acompaniante_id_distrito').val('');
                      $('#codigo_ubig_acompaniante').val(''); 
                      $('#codigo_ubig_acompaniante').focus(); 
                    }

                    /*
                    ////obtenemos la foto
                    if(result.foto != undefined && result.foto!=null){  
                      $('#foto_acompaniante').val(result.foto);  
                      var imagen = document.getElementById("foto_imagen_acompaniante");   
                      imagen.src = "data:image/jpeg;base64," + result.foto;
                    }
                    */
 
                    swal({
                        buttons: false,
                        title: "Datos Correctos",
                        text: "Los Datos fueron cargado de forma correcta.",
                        icon: "success",
                        timer: 1500,
                      });  
                        
                    $('#nro_doc_ident_acompaniante').removeClass('borde-alerta');
                    $('#btnBuscar_acompaniante').prop('disabled', false);
                    $('#observacion_acompaniante').focus(); 
        
                }else{ 
                    paciente_no_existe=true;
                } 
            }else{ 
                paciente_no_existe=true;
            }
        
            } ,
            error: function(xhr, status) {   
              swal({
                buttons: true,
                title: "¿DESEA REGISTRAR LO DATOS DE FORMA MANUAL?",
                text: "Ha ocurrido un error al consultar el N° Documento del Acompañante.",
                icon: "warning",
                dangerMode: true,
                buttons: ["Sí", "No"],
                closeOnClickOutside: false,
                closeOnEsc: false 
              }).then((willDelete) => { 
                  if (willDelete) {
                  ///SI 
                    $('#nro_doc_ident_acompaniante').addClass('borde-alerta');
                    $('#btnBuscar_acompaniante').prop('disabled', false);
                    $("#nro_doc_ident_acompaniante").val("");
                    $('#nro_doc_ident_acompaniante').focus(); 
                  } else { 
                  ///NO
                    $('#btnBuscar_acompaniante').prop('disabled', false); 
                    $('#nro_doc_ident_acompaniante_vista').removeAttr("readonly");
                    $('#nom_completo_acompaniante').removeAttr("readonly");
                    
                    $("#nro_doc_ident_acompaniante_vista").val($("#nro_doc_ident_acompaniante").val());
                    $('#nom_completo_acompaniante').focus(); 
                  }

              }); 
            },
            complete : function(xhr, status) {
                $('#content').html('');
                $('#btnBuscar_acompaniante').prop('disabled', false);
            }
        });     
    } 

    

  function calcular_edad_acompaniante(fecha_nac){ 
    if(fecha_nac != null && fecha_nac != undefined && fecha_nac.length>8 ){
      var oldDate = fecha_nac; //20/04/1946
      var values = oldDate.split("/");
      if(values.length==1){
        var values = oldDate.split("-");
        var dia = values[2];
        var mes = values[1];
        var ano = values[0]; 
      }else{
        var dia = values[0];
        var mes = values[1];
        var ano = values[2];
      } 

      calcularEdadPersona(ano,mes,dia,'input','','edad_de_acompaniante')
 
    }
  }
  </script>