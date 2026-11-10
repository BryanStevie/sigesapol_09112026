<script> 
  const copiar_direccion_nacimiento = document.getElementById("copiar_direccion_nacimiento");
  copiar_direccion_nacimiento.addEventListener("click", function() { 
        if (copiar_direccion_nacimiento.checked) { 
            swal({
            buttons: true,
            title: "¿Desea Copiar los datos de Dirección de Actual?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) { 
                 
                  ////COPIAR DATOS DE LA DIRECCION DE NACIMIENTO
                  var codigo_ubig_actual=$("#codigo_ubig_actual").val();  
                  var id_departamento_actual=$("#id_departamento_actual").val(); 
                  if(id_departamento_actual=='*'){
                    swal({
                      buttons: false,
                      title: "Operación Cancelada",
                      text: "Seleccione un Departamento de Nacimiento válido.",
                      icon: "error",
                      timer: 2000,
                    }); 
                    copiar_direccion_nacimiento.checked = false;
                    return;
                  }
                  var id_provincia_actual=$("#id_provincia_actual").val();
                  if(id_provincia_actual=='*'){
                    swal({
                      buttons: false,
                      title: "Operación Cancelada",
                      text: "Seleccione una Provincia de Nacimiento válido.",
                      icon: "error",
                      timer: 2000,
                    }); 
                    copiar_direccion_nacimiento.checked = false;
                    return;
                  }
                  var id_distrito_actual=$("#id_distrito_actual").val();
                  if(id_distrito_actual=='*'){
                    swal({
                      buttons: false,
                      title: "Operación Cancelada",
                      text: "Seleccione un Distrito de Nacimiento válido.",
                      icon: "error",
                      timer: 2000,
                    }); 
                    copiar_direccion_nacimiento.checked = false;
                    return;
                  }
                  var direccion_actual=$("#direccion_actual").val().replace(/\s+/g, ' ').trim(); 
                  


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////VALIDAMOS EL COPIAR DIRECCION A NACIMIENTO/////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                  var id_departamento_donde_copiar_nac=$("#id_departamento_nacimiento").val(); 
                  var id_provincia_donde_copiar_nac=$("#id_provincia_nacimiento").val();
                  var id_distrito_donde_copiar_nac=$("#id_distrito_nacimiento").val();

                  if(id_distrito_donde_copiar_nac!='*' && id_provincia_donde_copiar_nac!='*' && id_departamento_donde_copiar_nac!='*'){ 
                    
                    $("#direccion_nacimiento").val(direccion_actual);   
                    $("#direccion_actual").val(direccion_actual); 

                  }else if(id_distrito_donde_copiar_nac=='*' && id_provincia_donde_copiar_nac!='*' && id_departamento_donde_copiar_nac!='*'){ 

                      setTimeout(function() { 
                        $("#id_distrito_nacimiento").val(id_distrito_actual).change();  
                        $("#direccion_nacimiento").val(direccion_actual);   
                        $("#direccion_actual").val(direccion_actual);   
                      }, 1200); 

                  }else if(id_distrito_donde_copiar_nac=='*' && id_provincia_donde_copiar_nac=='*' && id_departamento_donde_copiar_nac!='*'){

                      $("#id_departamento_nacimiento").val(id_departamento_actual).change();    
                      lista_parametros_by_id(id_departamento_actual,'id_provincia_nacimiento','combo','TABLA_PROVINCIAS',id_provincia_actual);    
                      setTimeout(function() { 
                        $("#id_distrito_nacimiento").val(id_distrito_actual).change();  
                        $("#direccion_nacimiento").val(direccion_actual);   
                        $("#direccion_actual").val(direccion_actual);   
                      }, 1200); 

                  }else if(id_distrito_donde_copiar_nac=='*' && id_provincia_donde_copiar_nac=='*' && id_departamento_donde_copiar_nac=='*'){
                      
                      lista_parametros('TABLA_DEPARTAMENTO','id_departamento_nacimiento','combo','TABLA_DEPARTAMENTO',id_departamento_actual);
 
                      setTimeout(function() { 
                        lista_parametros_by_id(id_departamento_actual,'id_provincia_nacimiento','combo','TABLA_PROVINCIAS',id_provincia_actual);    
                        setTimeout(function() { 
                          $("#id_distrito_nacimiento").val(id_distrito_actual).change();  
                          $("#direccion_nacimiento").val(direccion_actual);   
                          $("#direccion_actual").val(direccion_actual);   
                        }, 2000); 
                      }, 1200); 

                  } 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////VALIDAMOS EL COPIAR DIRECCION A NACIMIENTO/////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                  ///SE DES-SELECCIONA
                  copiar_direccion_nacimiento.checked = false;

                  swal({
                    buttons: false,
                    title: "Copia de Datos Exitosa",
                    text: "La dirección de Nacimiento fue copiada de forma Correcta.",
                    icon: "success",
                    timer: 1000,
                  });  

              } else { 
                  copiar_direccion_nacimiento.checked = false;
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
    });   


</script>