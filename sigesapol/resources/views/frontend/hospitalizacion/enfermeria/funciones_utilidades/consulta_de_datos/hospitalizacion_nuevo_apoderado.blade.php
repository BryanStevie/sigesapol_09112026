<script>
    function validar_documento_apoderado() {
        var input = document.getElementById('nro_doc_ident_apoderado');
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
            var tipodoc_beneficiario = $("#tipo_documento_apoderado_tipo").val();
            if (tipodoc_beneficiario == "1" || tipodoc_beneficiario == "DNI") {
                if (this.value.length > 8) {
                    this.value = this.value.slice(0, 8);
                }
            }
        });
    }


    function getReniecByDni_apoderado() {
        limpiar_apoderado();
        var dni_beneficiario_para_validar = $("#nro_doc_ident_apoderado").val();
        var tipo_documento_apoderado_tipo = $("#tipo_documento_apoderado_tipo").val();
        var dni_apoderado = $("#nro_doc_ident_apoderado").val();
        var tipo_documento_apoderado = $("#tipo_documento_apoderado_tipo option:selected").text();
        $("#tipo_documento_apoderado").val(tipo_documento_apoderado);

        if (tipo_documento_apoderado_tipo == "1" || tipo_documento_apoderado_tipo == "DNI") {
            if (dni_apoderado.length != 8) {

                /////VALIDAMOS QUE EL DNI DEL PADRE, MADRE NO SEA EL MISMO QUE DEL PACIENTE
                if (dni_apoderado == dni_beneficiario_para_validar) {
                    swal({
                        buttons: false , 
                        title: "Mensaje de Error", 
                        text: "El N° DNI del PADRE / MADRE / APODERADO no puede ser el mismo que del PACIENTE.", 
                        icon: "error", 
                        timer: 1500, 
                    });
                    $('#nro_doc_ident_responsable').addClass('borde-alerta');
                    $("#nro_doc_ident_responsable").val("");
                    $('#nro_doc_ident_responsable').focus();
                    return;
                }

                swal({
                    buttons: false, 
                    title: "Mensaje de Error", 
                    text: "El N° DNI del apoderado debe tener 8 dígitos.", 
                    icon: "error", 
                    timer: 1500, 
                });
                $('#nro_doc_ident_responsable').addClass('borde-alerta');
                $("#nro_doc_ident_responsable").val("");
                $('#nro_doc_ident_responsable').focus();
                return;
            }
        } else {
            swal({
                buttons: false, 
                title: "Mensaje de Error", 
                text: "La Consulta de Datos tiene que realizarse únicamente por un Número de DNI.", 
                icon: "error", 
                timer: 1500, 
            });
            $('#nro_doc_ident_responsable').addClass('borde-alerta');
            $("#nro_doc_ident_responsable").val("");
            $('#nro_doc_ident_responsable').focus();
            return;
        }

        if (dni_apoderado == "") {
            swal({
                buttons: false, 
                title: "Mensaje de Error", 
                text: "Falta ingresar el Documento del Apoderado.", 
                icon: "error", 
                timer: 1500,
            });
            $('#nro_doc_ident_responsable').addClass('borde-alerta');
            $("#nro_doc_ident_apoderado").val("");
            $('#nro_doc_ident_apoderado').focus();
            return;
        }

        $('#btnBuscar_apoderado').prop('disabled', true);

        var paciente_no_existe = false;
        $.ajax({ 
            url: "{{ route('obtener_datos_solo_reniec_ajax') }}", 
            type: "GET", 
            dataType: "JSON", 
            data: {
                dni: dni_apoderado, 
                tipo_documento: tipo_documento_apoderado_tipo
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
                if (result != null) {
                    if (result[0] != undefined) {
                        paciente_no_existe = true;

                    } else if (tipo_documento_apoderado_tipo == "1" && result.codigoMensaje == '00') { //reniec 
                        console.log('tabla reniec');

                        ////calcular edad para poder validar
                        calcular_edad_apoderado(result.fecha_nac);
                        console.log(result.fecha_nac);
     
                        ///SI NO ES PADRE O MADRE VALIDA QUE NO SEA MENOR DE EDAD
                        var edad_apoderado_validar_ma_edad = $("#edad_de_apoderado").val();   
                        console.log(edad_apoderado_validar_ma_edad);
                        if(edad_apoderado_validar_ma_edad<18){
                            swal({
                              buttons: false,
                              title: "Mensaje de Error",
                              text: "El N° DNI del APODERADO ingresado no puede ser de un menor de edad.",
                              icon: "error",
                              timer: 2000,
                            });  
                            $('#edad_de_apoderado').val(''); 
                            $('#nro_doc_ident_apoderado').val(''); 
                            $('#nro_doc_ident_responsable').addClass('borde-alerta');
                            $('#nro_doc_ident_apoderado').focus(); 
                            return;
                        } 
 
                        paciente = result.paterno + ' ' + result.materno + ', ' + result.nombres;
                        $('#nom_completo_apoderado').val(paciente);
                        $('#numero_documento_apoderado').val(dni_apoderado);
                        $('#nro_doc_ident_apoderado_vista').val(dni_apoderado);
                        $('#nombre_apoderado').val(paciente); 
                        
                        if(result.direccionDomicilio!=null && result.direccionDomicilio!=undefined){
                        $('#direccion_apoderado').val(result.direccionDomicilio.replace(/\s+/g, ' ').trim()); 
                        } 
                
                        /*CARGA DE DATOS DE UBIGEO*/
                        $('#codigo_ubig_apoderado').val(result.codigo_ubigDomicilio);
                        if(result.codigo_ubigDomicilio!=null && result.codigo_ubigDomicilio != undefined){  
                            $('#codigo_ubig_apoderado_id_departamento').val(result.id_departamentoDomicilio);
                            $('#codigo_ubig_apoderado_id_provincia').val(result.id_provinciaDomicilio);
                            $('#codigo_ubig_apoderado_id_distrito').val(result.id_distritoDomicilio);
                            llenar_combos_de_ubigeo_apoderado();
                        }else{
                            $('#codigo_ubig_apoderado_id_departamento').val('');
                            $('#codigo_ubig_apoderado_id_provincia').val('');
                            $('#codigo_ubig_apoderado_id_distrito').val('');
                            $('#codigo_ubig_apoderado').val(''); 
                            $('#codigo_ubig_apoderado').focus(); 
                        }
       
                        swal({
                            buttons: false,
                            title: "Datos Correctos",
                            text: "Los Datos fueronc argado de forma correcta.",
                            icon: "success",
                            timer: 1500,
                        });  
                        $('#nro_doc_ident_responsable').removeClass('borde-alerta');
                        $('#direccion_apoderado').focus();

                    } else {
                        paciente_no_existe = true;
                    }
                } else {
                    paciente_no_existe = true;
                }
            }
            , error: function(xhr, status) {
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
                    $('#nro_doc_ident_apoderado').addClass('borde-alerta');
                    $('#btnBuscar_apoderado').prop('disabled', false);
                    $("#nro_doc_ident_apoderado").val("");
                    $('#nro_doc_ident_apoderado').focus(); 
                  } else { 
                  ///NO
                    $('#btnBuscar_apoderado').prop('disabled', false); 
                    $('#nro_doc_ident_apoderado_vista').removeAttr("readonly");
                    $('#nom_completo_apoderado').removeAttr("readonly");
                    
                    $("#nro_doc_ident_apoderado_vista").val($("#nro_doc_ident_apoderado").val());
                    $('#nom_completo_apoderado').focus(); 
                  }

              });
            }
            , complete: function(xhr, status) {
                $('#content').html('');
                $('#btnBuscar_apoderado').prop('disabled', false);
            }
        });
    }

    function calcular_edad_apoderado(fecha_nac){ 
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

        calcularEdadPersona(ano,mes,dia,'input','','edad_de_apoderado')

      }
    }
</script>
