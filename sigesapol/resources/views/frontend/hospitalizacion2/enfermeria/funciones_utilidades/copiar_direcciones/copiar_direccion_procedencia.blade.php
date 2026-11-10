<script>
    
    
    const copiar_direccion_procedencia = document.getElementById("copiar_direccion_procedencia");
    copiar_direccion_procedencia.addEventListener("click", function() { 
          if (copiar_direccion_procedencia.checked) { 
              swal({
              buttons: true,
              title: "¿Desea Copiar los datos de Dirección de Procedencia?",
              text: "Recuerde verificar que todos los datos sean correctos.",
              icon: "warning",
              dangerMode: true,
              }).then((willDelete) => {
                if (willDelete) { 
                  
                   
                    ////COPIAR DATOS DE LA DIRECCION DE NACIMIENTO
                  var codigo_ubig_nacimiento=$("#codigo_ubig_nacimiento").val(); 
                  $("#codigo_ubig_procedencia").val(codigo_ubig_nacimiento); 
                    var id_departamento_nacimiento=$("#id_departamento_nacimiento").val(); 
                    if(id_departamento_nacimiento=='*'){
                      swal({
                        buttons: false,
                        title: "Operación Cancelada",
                        text: "Seleccione un Departamento de Procedencia válido.",
                        icon: "error",
                        timer: 2000,
                      }); 
                      copiar_direccion_procedencia.checked = false;
                      return;
                    }
                    var id_provincia_nacimiento=$("#id_provincia_nacimiento").val();
                    if(id_provincia_nacimiento=='*'){
                      swal({
                        buttons: false,
                        title: "Operación Cancelada",
                        text: "Seleccione una Provincia de Procedencia válido.",
                        icon: "error",
                        timer: 2000,
                      }); 
                      copiar_direccion_procedencia.checked = false;
                      return;
                    }
                    var id_distrito_nacimiento=$("#id_distrito_nacimiento").val(); 
                    if(id_distrito_nacimiento=='*'){
                      swal({
                        buttons: false,
                        title: "Operación Cancelada",
                        text: "Seleccione un Distrito de Procedencia válido.",
                        icon: "error",
                        timer: 2000,
                      }); 
                      copiar_direccion_procedencia.checked = false;
                      return;
                    }
                    var direccion_nacimiento=$("#direccion_nacimiento").val().replace(/\s+/g, ' ').trim();
                  


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////VALIDAMOS EL COPIAR DIRECCION A NACIMIENTO/////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                  var id_departamento_donde_copiar_pro=$("#id_departamento_procedencia").val(); 
                  var id_provincia_donde_copiar_pro=$("#id_provincia_procedencia").val();
                  var id_distrito_donde_copiar_pro=$("#id_distrito_procedencia").val();

                  if(id_distrito_donde_copiar_pro!='*' && id_provincia_donde_copiar_pro!='*' && id_departamento_donde_copiar_pro!='*'){ 

                      $("#direccion_procedencia").val(direccion_nacimiento);   
                      $("#direccion_procedencia").val(direccion_nacimiento);   
                      $("#direccion_nacimiento").val(direccion_nacimiento);  

                  }else if(id_distrito_donde_copiar_pro=='*' && id_provincia_donde_copiar_pro!='*' && id_departamento_donde_copiar_pro!='*'){ 

                    setTimeout(function() { 
                      $("#id_distrito_procedencia").val(id_distrito_nacimiento).change();  
                      $("#direccion_procedencia").val(direccion_nacimiento);   
                      $("#direccion_procedencia").val(direccion_nacimiento);   
                      $("#direccion_nacimiento").val(direccion_nacimiento);   
                    }, 1200); 

                  }else if(id_distrito_donde_copiar_pro=='*' && id_provincia_donde_copiar_pro=='*' && id_departamento_donde_copiar_pro!='*'){

                    
                    $("#id_departamento_procedencia").val(id_departamento_nacimiento).change();    
                    lista_parametros_by_id(id_departamento_nacimiento,'id_provincia_procedencia','combo','TABLA_PROVINCIAS',id_provincia_nacimiento); 
                      
                    setTimeout(function() { 
                      $("#id_distrito_procedencia").val(id_distrito_nacimiento).change();  
                      $("#direccion_procedencia").val(direccion_nacimiento);   
                      $("#direccion_procedencia").val(direccion_nacimiento);   
                      $("#direccion_nacimiento").val(direccion_nacimiento);   
                    }, 1200); 

                  }else if(id_distrito_donde_copiar_pro=='*' && id_provincia_donde_copiar_pro=='*' && id_departamento_donde_copiar_pro=='*'){
 
                      lista_parametros('TABLA_DEPARTAMENTO','id_departamento_procedencia','combo','TABLA_DEPARTAMENTO',id_departamento_nacimiento);

                      setTimeout(function() { 
                        lista_parametros_by_id(id_departamento_nacimiento,'id_provincia_procedencia','combo','TABLA_PROVINCIAS',id_provincia_nacimiento); 
                          
                        setTimeout(function() { 
                          $("#id_distrito_procedencia").val(id_distrito_nacimiento).change();  
                          $("#direccion_procedencia").val(direccion_nacimiento);   
                          $("#direccion_procedencia").val(direccion_nacimiento);   
                          $("#direccion_nacimiento").val(direccion_nacimiento);   
                        }, 2000); 
                      }, 1200); 
                  }
 

                    ///SE DES-SELECCIONA
                    copiar_direccion_procedencia.checked = false;
  
                    swal({
                      buttons: false,
                      title: "Copia de Datos Exitosa",
                      text: "La dirección de Procedencia fue copiada de forma Correcta.",
                      icon: "success",
                      timer: 1000,
                    });  
  
                } else { 
                      copiar_direccion_procedencia.checked = false;
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