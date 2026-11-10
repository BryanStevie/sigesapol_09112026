<script>
  
  function validar_al_escribir_fechas($input_de_fecha){ 
    // Asegúrate de que el valor ingresado coincida con el formato "dd/mm/yyyy"
    $("#"+$input_de_fecha).on("input", function() {
      var valor = this.value;
      valor = valor.replace(/\D/g, ""); // Elimina todos los caracteres que no sean números
      valor = valor.slice(0, 8); // Limita la longitud máxima a 8 caracteres

      // Añade los "/" después del segundo y cuarto dígito
      if (valor.length > 2 && valor.length < 5) {
        valor = valor.slice(0, 2) + "/" + valor.slice(2);
      } else if (valor.length > 4) {
        valor = valor.slice(0, 2) + "/" + valor.slice(2, 4) + "/" + valor.slice(4);
      }


      this.value = valor;
      // Validar la fecha
      var dia = valor.slice(0, 2);
      var mes = valor.slice(3, 5);
      var anio = valor.slice(6, 10);
      const fecha = new Date(); 
      var curyear = fecha.getFullYear();
      if (valor.length >= 1 && valor.length <= 2) {
        if(dia>31){ 
          this.value = valor.slice(0, 1);
        }
      } else if (valor.length >= 4 && valor.length <= 5) {
        if (valor.length == 4) {
          if(valor.slice(3, 4)>=2){
            this.value = valor.slice(0, 3);
          }
        } 
        if(mes>12){ 
          this.value = valor.slice(0, 4);
        } 
      } else if (valor.length >= 7 && valor.length <= 10) {
        if (valor.length == 7) {  
          if(valor.slice(6, 7)<1){
            this.value = valor.slice(0, 6);
          }
        }
        if(anio>curyear){ 
          this.value = valor.slice(0, 8);
        }
      }
  
    });
  }

  function validar_campos_solo_numerico($nombre_de_campo){
    $($nombre_de_campo).keypress(function(event) {
      var keyCode = event.which;
      if (keyCode < 48 || keyCode > 57) {
        event.preventDefault();
      }
    });  
  }

</script>