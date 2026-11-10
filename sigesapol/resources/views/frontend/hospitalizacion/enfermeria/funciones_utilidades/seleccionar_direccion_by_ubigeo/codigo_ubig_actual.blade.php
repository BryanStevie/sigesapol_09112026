<script>
    
  var input_relacionados_ubig_actua = [
    'codigo_ubig_actual_id_departamento',
    'codigo_ubig_actual_id_provincia', 
    'codigo_ubig_actual_id_distrito', 
  ];       
  listar_datos_by_autocomplete_input('input','codigo_ubig_actual',input_relacionados_ubig_actua,'UBIGEO_DITRITO',2);
                
  $('#codigo_ubig_actual').keydown(function(event) {
    if (event.keyCode == 13) {
      event.preventDefault(); 
      //validamos si el código de ubigeo existe
      var codigo_ubigeo=$("#codigo_ubig_actual").val(); 
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
            var id_departamento_actual=$("#codigo_ubig_actual_id_departamento").val(); 
            var id_provincia_actual=$("#codigo_ubig_actual_id_provincia").val(); 
            var id_distrito_actual=$("#codigo_ubig_actual_id_distrito").val(); 
            $("#id_departamento_actual").val(id_departamento_actual).change(); 

            lista_parametros_by_id(id_departamento_actual,'id_provincia_actual','combo','TABLA_PROVINCIAS',id_provincia_actual); 
                          
            setTimeout(function() { 
              $("#id_distrito_actual").val(id_distrito_actual).change();   
            }, 1400);   
            $('#direccion_actual').focus(); 
          }else{
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "El CÓDIGO DE UBIGEO DE LA DIRECCIÓN ACTUAL ingresado no existe, verifique el dato ingresado.",
              icon: "error",
              timer: 2500,
            });  
            $('#codigo_ubig_actual').val(''); 
            $('#codigo_ubig_actual').focus(); 
          }
        } 
      }) 
    }
  });    
  
</script>