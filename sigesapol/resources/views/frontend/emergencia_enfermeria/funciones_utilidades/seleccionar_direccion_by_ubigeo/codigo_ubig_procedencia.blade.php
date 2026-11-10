<script>
    
  var input_relacionados_ubig_procedencia = [
    'codigo_ubig_procedencia_id_departamento',
    'codigo_ubig_procedencia_id_provincia', 
    'codigo_ubig_procedencia_id_distrito', 
  ];              
  listar_datos_by_autocomplete_input('input','codigo_ubig_procedencia',input_relacionados_ubig_procedencia,'UBIGEO_DITRITO',2);
                
  $('#codigo_ubig_procedencia').keydown(function(event) {
    if (event.keyCode == 13) {
      event.preventDefault(); 
      //validamos si el código de ubigeo existe
      var codigo_ubigeo=$("#codigo_ubig_procedencia").val(); 
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
            var id_departamento_procedencia=$("#codigo_ubig_procedencia_id_departamento").val(); 
            var id_provincia_procedencia=$("#codigo_ubig_procedencia_id_provincia").val(); 
            var id_distrito_procedencia=$("#codigo_ubig_procedencia_id_distrito").val(); 
            $("#id_departamento_procedencia").val(id_departamento_procedencia).change(); 
            
            lista_parametros_by_id(id_departamento_procedencia,'id_provincia_procedencia','combo','TABLA_PROVINCIAS',id_provincia_procedencia); 
                          
            setTimeout(function() { 
              $("#id_distrito_procedencia").val(id_distrito_procedencia).change();   
            }, 1400);   
            $('#direccion_procedencia').focus(); 
          }else{
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "El CÓDIGO DE UBIGEO DE LA DIRECCIÓN DE PROCEDENCIA ingresado no existe, verifique el dato ingresado.",
              icon: "error",
              timer: 2500,
            });  
            $('#codigo_ubig_procedencia').val(''); 
            $('#codigo_ubig_procedencia').focus(); 
          }
        } 
      }) 
    }
  });   
  
</script>