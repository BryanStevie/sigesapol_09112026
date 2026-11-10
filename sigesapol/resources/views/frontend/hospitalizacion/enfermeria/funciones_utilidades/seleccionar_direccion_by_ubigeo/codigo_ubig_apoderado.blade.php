<script>
    
    var input_relacionados_ubig_actua = [
      'codigo_ubig_apoderado_id_departamento',
      'codigo_ubig_apoderado_id_provincia', 
      'codigo_ubig_apoderado_id_distrito', 
    ];       
    listar_datos_by_autocomplete_input('input','codigo_ubig_apoderado',input_relacionados_ubig_actua,'UBIGEO_DITRITO',2);
                  
    $('#codigo_ubig_apoderado').keydown(function(event) {
      if (event.keyCode == 13) {
        event.preventDefault(); 
        //validamos si el código de ubigeo existe
        var codigo_ubigeo=$("#codigo_ubig_apoderado").val(); 
        $.ajax({
          data: { 
            codigo_ubigeo: codigo_ubigeo, 
          }, 
          url:'{{url('parametro/validar_existencia_ubigeo')}}', 
          type:'POST', 
          dataType:'JSON',
          success: function(data){  
            console.log(data[0]);
            ///VALIDAMOS SI EL CODIGO DE UBIGEO EXISTE EN LA BD
            if(data[0]!=null && data[0] != undefined){ 
              llenar_combos_de_ubigeo_apoderado();
            }else{
              swal({
                buttons: false,
                title: "Mensaje de Error",
                text: "El CÓDIGO DE UBIGEO DE LA DIRECCIÓN ACTUAL ingresado no existe, verifique el dato ingresado.",
                icon: "error",
                timer: 2500,
              });  
              $('#codigo_ubig_apoderado_id_departamento').val('');
              $('#codigo_ubig_apoderado_id_provincia').val('');
              $('#codigo_ubig_apoderado_id_distrito').val(''); 
              $('#codigo_ubig_apoderado').val(''); 
              $('#codigo_ubig_apoderado').focus(); 
            }
          } 
        }) 
      }
    });    
    
    
    function llenar_combos_de_ubigeo_apoderado(){
      var id_departamento_apoderado=$("#codigo_ubig_apoderado_id_departamento").val(); 
      var id_provincia_apoderado=$("#codigo_ubig_apoderado_id_provincia").val(); 
      var id_distrito_apoderado=$("#codigo_ubig_apoderado_id_distrito").val(); 
      $("#id_departamento_apoderado").val(id_departamento_apoderado).change(); 
  
      lista_parametros_by_id(id_departamento_apoderado,'id_provincia_apoderado','combo','TABLA_PROVINCIAS',id_provincia_apoderado); 
                            
      setTimeout(function() { 
        $("#id_distrito_apoderado").val(id_distrito_apoderado).change();   
      }, 1500);   
      $('#direccion_apoderado').focus(); 
    }
  </script>