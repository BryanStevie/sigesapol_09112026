<script>
    const copiar_datos_paciente_a_acompaniante = document.getElementById("copiar_datos_paciente_a_acompaniante");
    copiar_datos_paciente_a_acompaniante.addEventListener("click", function() { 
          if (copiar_datos_paciente_a_acompaniante.checked) { 
            
            consultar_copia_de_acompaniante();

          }  
      });   
      
    function consultar_copia_de_acompaniante(){

        swal({
        buttons: true,
        title: "¿Desea Copiar los datos del ACOMPAÑANATE A RESPONSABLE?",
        text: "Recuerde verificar que todos los datos sean correctos.",
        icon: "warning",
        dangerMode: true,
        }).then((willDelete) => {
        if (willDelete) { 
            procesar_copia_de_acompaniante();
        } else { 
            copiar_datos_paciente_a_acompaniante.checked = false;
            swal({
                buttons: false,
                title: "Operación Cancelada",
                text: "Proceso de Copia Cancelado.",
                icon: "error",
                timer: 1000,
            }); 
        }
        });

    }
  
  function procesar_copia_de_acompaniante(){
              limpiar_acompaniante();
              $('#nro_doc_ident_acompaniante').val("");  
    
                    ///VALIDACION DE COPIAR DATOS DE PACIENTE PARA ACOMPAÑANTE
                    var tipodoc_beneficiario_copia = $("#tipdoc_beneficiario").val();
                    var nro_doc_ident_responsable_copia = $("#nro_doc_ident_responsable").val().trim();
                    $("#nro_doc_ident_responsable").val(nro_doc_ident_responsable_copia); 
                    if (tipodoc_beneficiario_copia == "1"||tipodoc_beneficiario_copia == "DNI") {
                        if (nro_doc_ident_responsable_copia.length != 8) { 
                            copiar_datos_paciente_a_acompaniante.checked = false;
                                swal({
                                buttons: false,
                                title: "Mensaje de Error",
                                text: "El N° DNI del paciente debe tener 8 dígitos.",
                                icon: "error",
                                timer: 2500,
                                }); 
                            $("#nro_doc_ident_responsable").val("");
                            $('#nro_doc_ident_responsable').focus();
                            return;
                        }
                    }else{
                        copiar_datos_paciente_a_acompaniante.checked = false;
                        swal({
                            buttons: false,
                            title: "Mensaje de Error",
                            text: "La Consulta de Datos tiene que realizarse únicamente por un Número de DNI.",
                            icon: "error",
                            timer: 2500,
                        }); 
                        copiar_datos_paciente_a_acompaniante.checked = false;
                        $('#nro_doc_ident_responsable').focus();
                        return;
                    }

                    var nro_doc_ident_responsable	= $('#nro_doc_ident_responsable').val();
                    var nom_completo_responsable	= $('#nom_completo_responsable').val(); 
                    if( nro_doc_ident_responsable == "" || nro_doc_ident_responsable.length==0 || nom_completo_responsable.length==0){
                        copiar_datos_paciente_a_acompaniante.checked = false;
                        swal({
                            buttons: false,
                            title: "Mensaje de Error",
                            text: "Verifique el número de documento del paciente ingresado.",
                            icon: "error",
                            timer: 2500,
                        }); 
                        $("#nro_doc_ident_responsable").val("");
                        $('#nro_doc_ident_responsable').focus();
                        return;
                    }

                    ////PROCESO DE COPIA  
                    $("#nro_doc_ident_acompaniante").val(nro_doc_ident_responsable);
                    
                    ////EJECUTA CLICK SOBRE EL BOTON DE CONSULTAR DNI
                    copiar_datos_paciente_a_acompaniante.checked = false;
                    var boton_btnBuscar_acompaniante = document.getElementById("btnBuscar_acompaniante");
                    boton_btnBuscar_acompaniante.click();

                    ///VALIDAMOS Y SETEAMOS NUMERO DE CONTACTO
                    var nro_doc_ident_responsable_paciente = $('#nro_doc_ident_responsable').val();
                    var nro_doc_ident_acompaniante	= $('#nro_doc_ident_acompaniante').val();

                    ///si el acompañanate es el mismo que el paciente
                    if(nro_doc_ident_responsable_paciente == nro_doc_ident_acompaniante){
                        var telefono_paciente = $('#telefono_responsable').val(); 
                        if( telefono_paciente != null && telefono_paciente != "" && telefono_paciente.length != 0){ 
                            $("#telefono_acompaniante").val(telefono_paciente.trim().replace(/\s+/g, ''));
                        } 
                    }
 
  }
</script>