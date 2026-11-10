<script>
  function cargar_datos_adicionale_by_documento_paciente($dni_beneficiario, $tipdoc_beneficiario){
    
    /////CARGAMOS DATOS ADICIONALES DEL PACIENTE
    setTimeout(function() {   
        $.ajax({
          url:  '{{url('datos_adicionales_paciente_dni/')}}/'+ $dni_beneficiario+'/'+ $tipdoc_beneficiario,  
          type:'GET',
          dataType:'JSON',
          success: function(data){        
            if(data.data==null){
              lista_parametros('etnias','id_etnia','combo','TABLA_CARACTERISTICA_PACIENTE',74);
              lista_parametros('idiomas','id_idioma','combo','TABLA_CARACTERISTICA_PACIENTE',101);
              lista_parametros('TABLA_TIPO_DE_SANGRE','id_tipo_sangre','combo','TABLA_TIPO_DE_SANGRE',9);
              lista_parametros('estado_civil', 'id_estado_civil', 'combo', 'TABLA_CARACTERISTICA_PACIENTE',6);
              lista_parametros('religiones','id_religion','combo','TABLA_CARACTERISTICA_PACIENTE',11);
              lista_parametros('grados_instruccion','id_grado_instruccion','combo','TABLA_CARACTERISTICA_PACIENTE',10); 
              lista_parametros('ocupaciones','id_ocupacion','combo','TABLA_CARACTERISTICA_PACIENTE',85);  
              return;
            }
             
            $('#telefono').val(''); 
            if(data.data.telefono != undefined && data.data.telefono != '' && data.data.telefono != "" && data.data.telefono != null){ 
              $('#telefono').val(data.data.telefono.trim().replace(/\s+/g, '')); 
            }
             
             $('#telefono2').val(''); 
             if(data.data.telefono2 != undefined && data.data.telefono2 != '' && data.data.telefono2 != "" && data.data.telefono2 != null){ 
               $('#telefono2').val(data.data.telefono2.trim().replace(/\s+/g, '')); 
             }
             
             $('#correo').val(''); 
             if(data.data.correo != undefined && data.data.correo != '' && data.data.correo != "" && data.data.correo != null){ 
               $('#correo').val(data.data.correo.trim().replace(/\s+/g, '')); 
             } 


            ///SECCION DE DATOS ADICIONALES AL PACIENTE
            ///carga de id_etnia 
            if(data.data.id_etnia==''||data.data.id_etnia==null||data.data.id_etnia==0){
              lista_parametros('etnias','id_etnia','combo','TABLA_CARACTERISTICA_PACIENTE',74);
            }else{  
              lista_parametros('etnias','id_etnia','combo','TABLA_CARACTERISTICA_PACIENTE',data.data.id_etnia);
            } 

            ///carga de id_idioma
            if(data.data.id_idioma==''||data.data.id_idioma==null||data.data.id_idioma==0){
              lista_parametros('idiomas','id_idioma','combo','TABLA_CARACTERISTICA_PACIENTE',101);
            }else{  
              lista_parametros('idiomas','id_idioma','combo','TABLA_CARACTERISTICA_PACIENTE',data.data.id_idioma);
            } 

            ///carga de id_tipo_sangre
            if(data.data.id_tipo_sangre==''||data.data.id_tipo_sangre==null||data.data.id_tipo_sangre==0){
              lista_parametros('TABLA_TIPO_DE_SANGRE','id_tipo_sangre','combo','TABLA_TIPO_DE_SANGRE',9);
            }else{  
              lista_parametros('TABLA_TIPO_DE_SANGRE','id_tipo_sangre','combo','TABLA_TIPO_DE_SANGRE',data.data.id_tipo_sangre); 
            }  

            ///carga de id_estado_civil 
            var estado_civil_tomando_dato = $("#id_estado_civil_tomando_dato").val(); 
            console.log('--estado_civil_tomando_dato--'+estado_civil_tomando_dato+'---')
            if(estado_civil_tomando_dato==null || estado_civil_tomando_dato ==''|| estado_civil_tomando_dato =='0'){
              if(data.data.id_estado_civil==''||data.data.id_estado_civil==null||data.data.id_estado_civil==0){
                lista_parametros('estado_civil', 'id_estado_civil', 'combo', 'TABLA_CARACTERISTICA_PACIENTE',6);
              }else{  
                lista_parametros('estado_civil', 'id_estado_civil', 'combo', 'TABLA_CARACTERISTICA_PACIENTE', data.data.id_estado_civil);
              } 
            }else{
                lista_parametros('estado_civil', 'id_estado_civil', 'combo', 'TABLA_CARACTERISTICA_PACIENTE', estado_civil_tomando_dato);
            }

            ///carga de id_religion
            if(data.data.id_religion==''||data.data.id_religion==null||data.data.id_religion==0){
              lista_parametros('religiones','id_religion','combo','TABLA_CARACTERISTICA_PACIENTE',11);
            }else{  
              lista_parametros('religiones','id_religion','combo','TABLA_CARACTERISTICA_PACIENTE',data.data.id_religion);
            } 

            ///carga de id_religion
            if(data.data.id_grado_instruccion==''||data.data.id_grado_instruccion==null||data.data.id_grado_instruccion==0){
              lista_parametros('grados_instruccion','id_grado_instruccion','combo','TABLA_CARACTERISTICA_PACIENTE',10); 
            }else{  
              lista_parametros('grados_instruccion','id_grado_instruccion','combo','TABLA_CARACTERISTICA_PACIENTE',data.data.id_grado_instruccion);
            } 

            ///carga de id_religion
            if(data.data.id_ocupacion==''||data.data.id_ocupacion==null||data.data.id_ocupacion==0){
              lista_parametros('ocupaciones','id_ocupacion','combo','TABLA_CARACTERISTICA_PACIENTE',85);  
            }else{  
              lista_parametros('ocupaciones','id_ocupacion','combo','TABLA_CARACTERISTICA_PACIENTE',data.data.id_ocupacion);
            } 
            ///SECCION DE DATOS ADICIONALES AL PACIENTE
  
            /////SECCION DE UBIGEOS DEL PACIENTE     
            /*
            */ 
            var direccion_actual_existe = $("#direccion_actual").val(); 
            if(direccion_actual_existe==null || direccion_actual_existe ==''){
              $('#direccion_actual').val(''); 
              if(data.data.domicilio != null && data.data.domicilio !=''){
                $('#direccion_actual').val(data.data.domicilio.replace(/\s+/g, ' ').trim()); 
              }  
            }  


            ///VALIDAMOS EL UBIGEO DE RENIEC
            var id_departamento_existe_reniec = $("#codigo_ubig_actual_id_departamento").val();  
            var id_departamento_capturado='';
            if(id_departamento_existe_reniec!=null && id_departamento_existe_reniec !=''){
              id_departamento_capturado = id_departamento_existe_reniec;
            }else{
              id_departamento_capturado = data.data.id_departamento;
            }

            var id_provincia_existe_reniec = $("#codigo_ubig_actual_id_provincia").val(); 
            var id_provincia_capturado='';
            if(id_provincia_existe_reniec!=null && id_provincia_existe_reniec !=''){
              id_provincia_capturado = id_provincia_existe_reniec;
            }else{
              id_provincia_capturado = data.data.id_provincia;
            }

            var id_distrito_existe_reniec = $("#codigo_ubig_actual_id_distrito").val(); 
            var id_distrito_capturado='';
            if(id_distrito_existe_reniec!=null && id_distrito_existe_reniec !=''){
              id_distrito_capturado = id_distrito_existe_reniec;
            }else{
              id_distrito_capturado = data.data.id_distrito;
            }

            if(id_departamento_capturado!='*' && id_departamento_capturado!='' && id_departamento_capturado!=null && id_departamento_capturado!=0){ 
                setTimeout(function() { 
                    console.log(id_departamento_capturado);
                    $("#id_departamento_actual").val(id_departamento_capturado).change();  
                }, 5000);  

                setTimeout(function() { 
                    console.log(id_provincia_capturado);
                    $("#id_provincia_actual").val(id_provincia_capturado).change();   
                }, 6000);   

                setTimeout(function() { 
                    console.log(id_distrito_capturado);
                    $("#id_distrito_actual").val(id_distrito_capturado).change();   
                }, 7000);   
            }  
 

            /////UBIGEO DE NACIMIENTO
            var direccion_nacimiento_existe = $("#direccion_nacimiento").val(); 
            console.log('----'+direccion_nacimiento_existe+'----')
            if(direccion_nacimiento_existe == null || direccion_nacimiento_existe ==''){
              $('#direccion_nacimiento').val(''); 
              if(data.data.direccion_nacimiento != null && data.data.direccion_nacimiento !=''){
                $('#direccion_nacimiento').val(data.data.domicilio.replace(/\s+/g, ' ').trim()); 
              } 
            }  
            ///VALIDAMOS EL UBIGEO DE RENIEC
            var id_departamento_existe_reniec_naci = $("#codigo_ubig_nacimiento_id_departamento").val();  
            var id_departamento_capturado_naci='';
            if(id_departamento_existe_reniec_naci!=null && id_departamento_existe_reniec_naci !=''){
              id_departamento_capturado_naci = id_departamento_existe_reniec_naci;
            }else{
              id_departamento_capturado_naci = data.data.id_departamento_nacimiento;
            }
           
            var id_provincia_existe_reniec_naci = $("#codigo_ubig_nacimiento_id_provincia").val(); 
            var id_provincia_capturado_naci='';
            if(id_provincia_existe_reniec_naci!=null && id_provincia_existe_reniec_naci !=''){
              id_provincia_capturado_naci = id_provincia_existe_reniec_naci;
            }else{
              id_provincia_capturado_naci = data.data.id_provincia_nacimiento;
            }

            var id_distrito_existe_reniec_naci = $("#codigo_ubig_nacimiento_id_distrito").val(); 
            var id_distrito_capturado_naci='';
            if(id_distrito_existe_reniec_naci!=null && id_distrito_existe_reniec_naci !=''){
              id_distrito_capturado_naci = id_distrito_existe_reniec_naci;
            }else{
              id_distrito_capturado_naci = data.data.id_distrito_nacimiento;
            }

            if(id_departamento_capturado_naci!='*' && id_departamento_capturado_naci!='' && id_departamento_capturado_naci!=null && 
              id_departamento_capturado_naci!=0){ 

                setTimeout(function() { 
                  console.log(id_departamento_capturado_naci);
                  $("#id_departamento_nacimiento").val(id_departamento_capturado_naci).change();  
                }, 4000);  

                setTimeout(function() { 
                  console.log(id_provincia_capturado_naci);
                  $("#id_provincia_nacimiento").val(id_provincia_capturado_naci).change();   
                }, 5000);   

                setTimeout(function() { 
                  console.log(id_distrito_capturado_naci);
                  $("#id_distrito_nacimiento").val(id_distrito_capturado_naci).change();   
                 }, 6000);   
            }  
 
            $('#direccion_procedencia').val(data.data.direccion_procedencia); 
            if(data.data.id_departamento_procedencia!='*' && data.data.id_departamento_procedencia!='' && data.data.id_departamento_procedencia!=null 
            && data.data.id_departamento_procedencia!=0){ 
                setTimeout(function() { 
                  console.log(data.data.id_departamento_procedencia);
                  $("#id_departamento_procedencia").val(data.data.id_departamento_procedencia).change();  
                }, 4500);  

                setTimeout(function() { 
                  console.log(data.data.id_provincia_procedencia);
                  $("#id_provincia_procedencia").val(data.data.id_provincia_procedencia).change();   
                }, 5500);   

                setTimeout(function() { 
                  console.log(data.data.id_distrito_procedencia);
                  $("#id_distrito_procedencia").val(data.data.id_distrito_procedencia).change();   
                }, 6500);   
            }  
            /////SECCION DE UBIGEOS DEL PACIENTE 
          }
        });

    }, 2000);
  }
</script>