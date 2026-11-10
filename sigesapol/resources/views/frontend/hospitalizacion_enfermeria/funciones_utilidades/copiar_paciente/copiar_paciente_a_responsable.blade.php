<script>
    const copiar_datos_paciente_a_responsable = document.getElementById("copiar_datos_paciente_a_responsable");
    copiar_datos_paciente_a_responsable.addEventListener("click", function() { 
          if (copiar_datos_paciente_a_responsable.checked) { 
            
            consultar_copia_de_responsable();

          }  
      });   
  
    function consultar_copia_de_responsable(){
            swal({
              buttons: true,
              title: "¿Desea Copiar los datos del PACIENTE A RESPONSABLE?",
              text: "Recuerde verificar que todos los datos sean correctos.",
              icon: "warning",
              dangerMode: true,
              }).then((willDelete) => {
                if (willDelete) { 
                    procesar_copia_de_responsable();
                } else { 
                    copiar_datos_paciente_a_responsable.checked = false;
                    swal({
                      buttons: false,
                      title: "Operación Cancelada",
                      text: "Proceso de Copia Cancelado.",
                      icon: "error",
                      timer: 1000,
                    }); 
                    $("#nro_doc_ident_responsable").focus();
                }
            });
    }  
  
  function procesar_copia_de_responsable(){
              limpiar_responsable();
              $('#nro_doc_ident_responsable').val("");  
    
                    ///VALIDACION DE COPIAR DATOS DE PACIENTE PARA ACOMPAÑANTE
                    var tipodoc_beneficiario_copia = $("#tipdoc_beneficiario").val();
                    var dni_paciente_copia = $("#dni_beneficiario").val().trim();
                    $("#dni_beneficiario").val(dni_paciente_copia); 
                    if (tipodoc_beneficiario_copia == "1"||tipodoc_beneficiario_copia == "DNI") {
                        if (dni_paciente_copia.length != 8) { 
                            copiar_datos_paciente_a_responsable.checked = false;
                                swal({
                                buttons: false,
                                title: "Mensaje de Error",
                                text: "El N° DNI del paciente debe tener 8 dígitos.",
                                icon: "error",
                                timer: 2500,
                                }); 
                            $("#dni_beneficiario").val("");
                            $('#dni_beneficiario').focus();
                            return;
                        }
                    }else{
                        copiar_datos_paciente_a_responsable.checked = false;
                        swal({
                            buttons: false,
                            title: "Mensaje de Error",
                            text: "La Consulta de Datos tiene que realizarse únicamente por un Número de DNI.",
                            icon: "error",
                            timer: 2500,
                        }); 
                        copiar_datos_paciente_a_responsable.checked = false;
                        $('#dni_beneficiario').focus();
                        return;
                    }

                    var nro_doc_ident	= $('#nro_doc_ident').val();
                    var nombre_beneficiario	= $('#nombre_beneficiario').val();
                    var paterno_beneficiario = $('#paterno_beneficiario').val();
                    var materno_beneficiario = $('#materno_beneficiario').val();
                    if( nro_doc_ident == "" || nro_doc_ident.length==0 || nombre_beneficiario.length==0 || paterno_beneficiario.length==0 || 
                        materno_beneficiario.length==0){
                        copiar_datos_paciente_a_responsable.checked = false;
                        swal({
                            buttons: false,
                            title: "Mensaje de Error",
                            text: "Verifique el número de documento del paciente ingresado.",
                            icon: "error",
                            timer: 2500,
                        }); 
                        $("#nro_doc_ident").val("");
                        $('#nro_doc_ident').focus();
                        return;
                    }

                    ////PROCESO DE COPIA  
                    $("#nro_doc_ident_responsable").val(nro_doc_ident);
                    
                    ////EJECUTA CLICK SOBRE EL BOTON DE CONSULTAR DNI
                    copiar_datos_paciente_a_responsable.checked = false;
                    var boton_btnBuscar_responsable = document.getElementById("btnBuscar_responsable");
                    boton_btnBuscar_responsable.click();

                    ///VALIDAMOS Y SETEAMOS NUMERO DE CONTACTO
                    var nro_doc_ident_paciente = $('#dni_beneficiario').val();
                    var nro_doc_ident_responsable	= $('#nro_doc_ident_responsable').val();

                    ///si el acompañanate es el mismo que el paciente
                    if(nro_doc_ident_paciente == nro_doc_ident_responsable){
                        var telefono_paciente = $('#telefono').val();
                        var telefono_2_paciente	= $('#telefono2').val();
                        if( telefono_paciente != null && telefono_paciente != "" && telefono_paciente.length != 0){ 
                            $("#telefono_responsable").val(telefono_paciente.trim().replace(/\s+/g, ''));
                        }else{
                            if( telefono_2_paciente != null && telefono_2_paciente != "" && telefono_2_paciente.length != 0){ 
                                $("#telefono_responsable").val(telefono_2_paciente.trim().replace(/\s+/g, ''));
                            } 
                        }
                    }
 
  }
</script>