<script>
    
  var input_relacionados_ubig_nacimiento = [
    'codigo_ubig_nacimiento_id_departamento',
    'codigo_ubig_nacimiento_id_provincia', 
    'codigo_ubig_nacimiento_id_distrito', 
  ];  
  listar_datos_by_autocomplete_input('input','codigo_ubig_nacimiento',input_relacionados_ubig_nacimiento,'UBIGEO_DITRITO',2);
  
  $('#codigo_ubig_nacimiento').keydown(function(event) {
    if (event.keyCode == 13) {
      event.preventDefault(); 
      //validamos si el código de ubigeo existe
      var codigo_ubigeo=$("#codigo_ubig_nacimiento").val(); 
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
              var id_departamento_nacimiento=$("#codigo_ubig_nacimiento_id_departamento").val(); 
              var id_provincia_nacimiento=$("#codigo_ubig_nacimiento_id_provincia").val(); 
              var id_distrito_nacimiento=$("#codigo_ubig_nacimiento_id_distrito").val(); 
              $("#id_departamento_nacimiento").val(id_departamento_nacimiento).change(); 
              
              lista_parametros_by_id(id_departamento_nacimiento,'id_provincia_nacimiento','combo','TABLA_PROVINCIAS',id_provincia_nacimiento); 
                            
              setTimeout(function() { 
                $("#id_distrito_nacimiento").val(id_distrito_nacimiento).change();   
              }, 1200);   
              $('#direccion_nacimiento').focus(); 
          }else{
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "El CÓDIGO DE UBIGEO DE LA DIRECCIÓN DE NACIMIENTO ingresado no existe, verifique el dato ingresado.",
              icon: "error",
              timer: 2500,
            });  
            $('#codigo_ubig_nacimiento').val(''); 
            $('#codigo_ubig_nacimiento').focus(); 
          }
        } 
      }) 
    }
  });   
  
</script>