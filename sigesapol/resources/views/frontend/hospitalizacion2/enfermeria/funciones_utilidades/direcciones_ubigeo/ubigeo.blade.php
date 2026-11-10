<script>
   
  /////LLENADO DE COMBO DE UBIGEOS    DE NACIMIENTO   
  $("#id_departamento_nacimiento").select2({ width: 'resolve'});  
  //lista_parametros('TABLA_DEPARTAMENTO','id_departamento_nacimiento','combo','TABLA_DEPARTAMENTO',0);
  $("#id_provincia_nacimiento").select2({ width: 'resolve'}); 
  $('#id_departamento_nacimiento').on('change', function() { 
        var id_departamento_nacimiento=$("#id_departamento_nacimiento option:selected" ).val(); 
        if(id_departamento_nacimiento=='*'){  
          $("#id_provincia_nacimiento").empty();  
          $("#id_provincia_nacimiento").append("<option value='*'>--SELECCIONAR--</option>"); 
          $("#id_provincia_nacimiento").val("*").change();
        }else{
          lista_parametros_by_id(id_departamento_nacimiento,'id_provincia_nacimiento','combo','TABLA_PROVINCIAS',0);
        }  
    })
    
  $("#id_distrito_nacimiento").select2({ width: 'resolve'}); 
  $('#id_provincia_nacimiento').on('change', function() {  
        var id_provincia_nacimiento=$("#id_provincia_nacimiento option:selected" ).val(); 
        if(id_provincia_nacimiento=='*'){  
          $("#id_distrito_nacimiento").empty();  
          $("#id_distrito_nacimiento").append("<option value='*'>--SELECCIONAR--</option>"); 
          $("#id_distrito_nacimiento").val("*").change();
        }else{
          lista_parametros_by_id(id_provincia_nacimiento,'id_distrito_nacimiento','combo','TABLA_DISTRITOS',0);
        } 
    })

  /////LLENADO DE COMBO DE UBIGEOS    DE PROCEDENCIA 
  $("#id_departamento_procedencia").select2({ width: 'resolve'}); 
  //lista_parametros('TABLA_DEPARTAMENTO','id_departamento_procedencia','combo','TABLA_DEPARTAMENTO',0);
  $("#id_provincia_procedencia").select2({ width: 'resolve'}); 
  $('#id_departamento_procedencia').on('change', function() {  
        var id_departamento_procedencia=$("#id_departamento_procedencia option:selected" ).val(); 
        if(id_departamento_procedencia=='*'){  
          $("#id_provincia_procedencia").empty();  
          $("#id_provincia_procedencia").append("<option value='*'>--SELECCIONAR--</option>"); 
          $("#id_provincia_procedencia").val("*").change();
        }else{
          lista_parametros_by_id(id_departamento_procedencia,'id_provincia_procedencia','combo','TABLA_PROVINCIAS',0);
        } 
    })

  $("#id_distrito_procedencia").select2({ width: 'resolve'}); 
  $('#id_provincia_procedencia').on('change', function() { 
      var id_provincia_procedencia=$("#id_provincia_procedencia  option:selected" ).val(); 
      if(id_provincia_procedencia=='*'){  
        $("#id_distrito_procedencia").empty();  
        $("#id_distrito_procedencia").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#id_distrito_procedencia").val("*").change();
      }else{
        lista_parametros_by_id(id_provincia_procedencia,'id_distrito_procedencia','combo','TABLA_DISTRITOS',0);
      }
    })

/////LLENADO DE COMBO DE UBIGEOS ACTUAL 
$("#id_departamento_actual").select2({ width: 'resolve'}); 
  //lista_parametros('TABLA_DEPARTAMENTO','id_departamento_actual','combo','TABLA_DEPARTAMENTO',0);
  $("#id_provincia_actual").select2({ width: 'resolve'}); 
  $('#id_departamento_actual').on('change', function() { 
      var id_departamento_actual=$("#id_departamento_actual option:selected" ).val(); 
      if(id_departamento_actual=='*'){  
        $("#id_provincia_actual").empty();  
        $("#id_provincia_actual").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#id_provincia_actual").val("*").change();
      }else{
        lista_parametros_by_id(id_departamento_actual,'id_provincia_actual','combo','TABLA_PROVINCIAS',0);
      }
    })
  $("#id_distrito_actual").select2({ width: 'resolve'}); 
  $('#id_provincia_actual').on('change', function() { 
      var id_provincia_actual=$("#id_provincia_actual option:selected" ).val(); 
      if(id_provincia_actual=='*'){  
        $("#id_distrito_actual").empty();  
        $("#id_distrito_actual").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#id_distrito_actual").val("*").change();
      }else{
        lista_parametros_by_id(id_provincia_actual,'id_distrito_actual','combo','TABLA_DISTRITOS',0);
      }
    })


   
/////LLENADO DE COMBO DE UBIGEOS    DE RESPONSABLE   
$("#id_departamento_responsable").select2({ width: 'resolve'});  
//lista_parametros('TABLA_DEPARTAMENTO','id_departamento_responsable','combo','TABLA_DEPARTAMENTO',0);
$("#id_provincia_responsable").select2({ width: 'resolve'}); 
$('#id_departamento_responsable').on('change', function() { 
      var id_departamento_responsable=$("#id_departamento_responsable option:selected" ).val(); 
      if(id_departamento_responsable=='*'){  
        $("#id_provincia_responsable").empty();  
        $("#id_provincia_responsable").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#id_provincia_responsable").val("*").change();
      }else{
        lista_parametros_by_id(id_departamento_responsable,'id_provincia_responsable','combo','TABLA_PROVINCIAS',0);
      }  
  })
  
$("#id_distrito_responsable").select2({ width: 'resolve'}); 
$('#id_provincia_responsable').on('change', function() {  
      var id_provincia_responsable=$("#id_provincia_responsable option:selected" ).val(); 
      if(id_provincia_responsable=='*'){  
        $("#id_distrito_responsable").empty();  
        $("#id_distrito_responsable").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#id_distrito_responsable").val("*").change();
      }else{
        lista_parametros_by_id(id_provincia_responsable,'id_distrito_responsable','combo','TABLA_DISTRITOS',0);
      } 
  })


   
/////LLENADO DE COMBO DE UBIGEOS    DE APODERADO   
$("#id_departamento_apoderado").select2({ width: 'resolve'});  
//lista_parametros('TABLA_DEPARTAMENTO','id_departamento_apoderado','combo','TABLA_DEPARTAMENTO',0);
$("#id_provincia_apoderado").select2({ width: 'resolve'}); 
$('#id_departamento_apoderado').on('change', function() { 
      var id_departamento_apoderado=$("#id_departamento_apoderado option:selected" ).val(); 
      if(id_departamento_apoderado=='*'){  
        $("#id_provincia_apoderado").empty();  
        $("#id_provincia_apoderado").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#id_provincia_apoderado").val("*").change();
      }else{
        lista_parametros_by_id(id_departamento_apoderado,'id_provincia_apoderado','combo','TABLA_PROVINCIAS',0);
      }  
  })
  
$("#id_distrito_apoderado").select2({ width: 'resolve'}); 
$('#id_provincia_apoderado').on('change', function() {  
      var id_provincia_apoderado=$("#id_provincia_apoderado option:selected" ).val(); 
      if(id_provincia_apoderado=='*'){  
        $("#id_distrito_apoderado").empty();  
        $("#id_distrito_apoderado").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#id_distrito_apoderado").val("*").change();
      }else{
        lista_parametros_by_id(id_provincia_apoderado,'id_distrito_apoderado','combo','TABLA_DISTRITOS',0);
      } 
  })


   
/////LLENADO DE COMBO DE UBIGEOS    DE ACOMPAÑANTE   
$("#id_departamento_acompaniante").select2({ width: 'resolve'});  
//lista_parametros('TABLA_DEPARTAMENTO','id_departamento_acompaniante','combo','TABLA_DEPARTAMENTO',0);
$("#id_provincia_acompaniante").select2({ width: 'resolve'}); 
$('#id_departamento_acompaniante').on('change', function() { 
      var id_departamento_acompaniante=$("#id_departamento_acompaniante option:selected" ).val(); 
      if(id_departamento_acompaniante=='*'){  
        $("#id_provincia_acompaniante").empty();  
        $("#id_provincia_acompaniante").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#id_provincia_acompaniante").val("*").change();
      }else{
        lista_parametros_by_id(id_departamento_acompaniante,'id_provincia_acompaniante','combo','TABLA_PROVINCIAS',0);
      }  
  })
  
$("#id_distrito_acompaniante").select2({ width: 'resolve'}); 
$('#id_provincia_acompaniante').on('change', function() {  
      var id_provincia_acompaniante=$("#id_provincia_acompaniante option:selected" ).val(); 
      if(id_provincia_acompaniante=='*'){  
        $("#id_distrito_acompaniante").empty();  
        $("#id_distrito_acompaniante").append("<option value='*'>--SELECCIONAR--</option>"); 
        $("#id_distrito_acompaniante").val("*").change();
      }else{
        lista_parametros_by_id(id_provincia_acompaniante,'id_distrito_acompaniante','combo','TABLA_DISTRITOS',0);
      } 
  })
   
</script>