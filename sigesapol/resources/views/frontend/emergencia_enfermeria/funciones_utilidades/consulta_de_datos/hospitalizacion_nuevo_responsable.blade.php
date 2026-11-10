<script>
  function validar_documento_responsable(){
    var input = document.getElementById('nro_doc_ident_responsable'); 
    // Capturar el evento keypress en el campo de entrada
    input.addEventListener('keypress', function(event) {
      var charCode = event.which ? event.which : event.keyCode;

      // Comprobar si el carácter ingresado es un número o no
      if (charCode < 48 || charCode > 57) {
        // Si no es un número, cancelar el evento de pulsación de tecla
        event.preventDefault();
      }
    });

    // Capturar el evento input en el campo de entrada
    input.addEventListener('input', function() {
      // Eliminar cualquier carácter no numérico del valor del campo de entrada
      this.value = this.value.replace(/\D/g, '');

        // Limitar la longitud máxima del campo de entrada a 8 dígitos solo para DNI
      var tipodoc_beneficiario = $("#tipo_documento_responsable_tipo").val();
      if (tipodoc_beneficiario == "1"||tipodoc_beneficiario == "DNI") {
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
          }
      }
    }); 
  }
   
     
  function getReniecByDni_responsable() { 
      limpiar_responsable(); 
      //
      var tipo_documento_responsable_tipo = $("#tipo_documento_responsable_tipo").val();
      var dni_responsable = $("#nro_doc_ident_responsable").val();
      var dni_beneficiario = $("#dni_beneficiario").val();
      var tipo_documento_responsable = $("#tipo_documento_responsable_tipo option:selected").text();
      $("#tipo_documento_responsable").val(tipo_documento_responsable);
                  
      if (tipo_documento_responsable_tipo == "1"||tipo_documento_responsable_tipo == "DNI") {
          if (dni_responsable.length != 8) {
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "El N° DNI del paciente debe tener 8 dígitos.",
              icon: "error",
              timer: 1500,
            });  
            $('#nro_doc_ident_responsable').addClass('borde-alerta');
            $("#nro_doc_ident_responsable").val("");
            $('#nro_doc_ident_responsable').focus(); 
            return;
          }
      }else{
          swal({
            buttons: false,
            title: "Mensaje de Error",
            text: "La Consulta de Datos tiene que realizarse únicamente por un Número de DNI.",
            icon: "error",
            timer: 1500,
          }); 
          $('#nro_doc_ident_responsable').addClass('borde-alerta');
          $("#nro_doc_ident_responsable").val("");
          $('#nro_doc_ident_responsable').focus(); 
        return;
      }
  
      if(dni_responsable==""){
          swal({
            buttons: false,
            title: "Mensaje de Error",
            text: "El N° DNI del responsable debe tener 8 dígitos.",
            icon: "error",
            timer: 1500,
          });  
          $('#nro_doc_ident_responsable').addClass('borde-alerta');
          $("#nro_doc_ident_responsable").val("");
          $('#nro_doc_ident_responsable').focus(); 
          return;
      } 
      
      /*
      if(dni_responsable == dni_beneficiario){
          swal({
            buttons: false,
            title: "Mensaje de Error",
              text: "El N° de DNI ingresado no puede ser igual al N° de DNI del RESPONSABLE.",
            icon: "error",
            timer: 2500,
          });   
          $("#nro_doc_ident_responsable").val("");
          $('#nro_doc_ident_responsable').focus(); 
          return;
      } 
      */
  
      $('#btnBuscar_responsable').prop('disabled', true);
  
      var paciente_no_existe=false;
      $.ajax({
          //url: '/validar_documento/' + dni_responsable,
          url: "{{ route('obtener_datos_solo_reniec_ajax') }}",          
          type: "GET",
          dataType: "JSON",
          data: {           
            dni: dni_responsable,
            tipo_documento:tipo_documento_responsable_tipo      
          },      
            beforeSend: function() {
              swal({
                buttons: false,
                title: "Realizando Consulta ...",
                text: "Espere un momento mientras se genera la solicitud.",
                icon: "warning",
                buttons: false,
                closeOnClickOutside: false,
                closeOnEsc: false
              });
            },
          success: function(result) { //alert(result.id); 
          if(result!=null){
             
              if (result[0] != undefined) { 
                  paciente_no_existe=true;
      
              }else if (tipo_documento_responsable_tipo=="1" && result.codigoMensaje == '00') { //reniec 
                  console.log('tabla reniec');
                     
                  ////calcular edad para poder validar
                  calcular_edad_responsable(result.fecha_nac);  
     
                  ///SI NO ES PADRE O MADRE VALIDA QUE NO SEA MENOR DE EDAD
                  var edad_responsable_validar_ma_edad = $("#edad_de_responsable").val(); 
                  console.log(edad_responsable_validar_ma_edad);  
                  if(edad_responsable_validar_ma_edad<=16){
                      swal({
                        buttons: false,
                        title: "Mensaje de Error",
                        text: "El N° DNI del RESPONSABLE ingresado no puede ser de un menor de edad.",
                        icon: "error",
                        timer: 2000,
                      });  
                      $('#edad_de_responsable').val(''); 
                      $('#nro_doc_ident_responsable').addClass('borde-alerta');
                      $('#nro_doc_ident_responsable').val(''); 
                      $('#nro_doc_ident_responsable').focus(); 
                      return;
                  }
                  
                  /*
                  else if(edad_responsable_validar_ma_edad>=60){ 
                      swal({
                        buttons: false,
                        title: "Mensaje de Error",
                        text: "El Acompañante NO PUEDE SER UN ADULTO MAYOR.",
                        icon: "error",
                        timer: 2500,
                      }); 
                      $('#edad_de_responsable').val(''); 
                      $('#nro_doc_ident_responsable').val(''); 
                      $('#nro_doc_ident_responsable').focus(); 
                      return;
                  }
                  */
                  paciente = result.paterno + ' ' + result.materno + ', ' + result.nombres;
                  $('#nom_completo_responsable').val(paciente);         
                  $('#numero_documento_responsable').val(dni_responsable);
                  $('#nro_doc_ident_responsable_vista').val(dni_responsable);
                  $('#nombre_responsable').val(paciente); 
                  $('#direccion_responsable').val('');
                    if(result.direccionDomicilio!=null && result.direccionDomicilio!=undefined){
                      $('#direccion_responsable').val(result.direccionDomicilio.replace(/\s+/g, ' ').trim()); 
                    } 
            
                  /*CARGA DE DATOS DE UBIGEO*/
                  $('#codigo_ubig_responsable').val(result.codigo_ubigDomicilio);
                  if(result.codigo_ubigDomicilio!=null && result.codigo_ubigDomicilio != undefined){  
                    $('#codigo_ubig_responsable_id_departamento').val(result.id_departamentoDomicilio);
                    $('#codigo_ubig_responsable_id_provincia').val(result.id_provinciaDomicilio);
                    $('#codigo_ubig_responsable_id_distrito').val(result.id_distritoDomicilio);
                    llenar_combos_de_ubigeo_responsable();
                  }else{
                    $('#codigo_ubig_responsable_id_departamento').val('');
                    $('#codigo_ubig_responsable_id_provincia').val('');
                    $('#codigo_ubig_responsable_id_distrito').val('');
                    $('#codigo_ubig_responsable').val(''); 
                    $('#codigo_ubig_responsable').focus(); 
                  }

                  swal({
                    buttons: false,
                    title: "Datos Correctos",
                    text: "Los Datos fueron cargado de forma correcta.",
                    icon: "success",
                    timer: 1500,
                  });  

                  $('#dni_beneficiario').removeClass('borde-alerta');
                  $('#observacion_responsable').focus();  

                  swal({
                    buttons: false,
                    title: "Datos Correctos",
                    text: "Los Datos fueron cargado de forma correcta.",
                    icon: "success",
                    timer: 1500,
                  });  
      
              }else{ 
                  paciente_no_existe=true;
              } 
          }else{ 
              paciente_no_existe=true;
          }
      
          },
          error: function(xhr, status) {  
              swal({
                buttons: true,
                title: "¿DESEA REGISTRAR LO DATOS DE FORMA MANUAL?",
                text: "Ha ocurrido un error al consultar el N° Documento del Responsable.",
                icon: "warning",
                dangerMode: true,
                buttons: ["Sí", "No"],
                closeOnClickOutside: false,
                closeOnEsc: false 
              }).then((willDelete) => { 
                  if (willDelete) {
                  ///SI 
                    $('#nro_doc_ident_responsable').addClass('borde-alerta');
                    $('#btnBuscar_responsable').prop('disabled', false);
                    $("#nro_doc_ident_responsable").val("");
                    $('#nro_doc_ident_responsable').focus(); 
                  } else { 
                  ///NO
                    $('#btnBuscar_responsable').prop('disabled', false); 
                    $('#nro_doc_ident_responsable_vista').removeAttr("readonly");
                    $('#nom_completo_responsable').removeAttr("readonly");
                    
                    $("#nro_doc_ident_responsable_vista").val($("#nro_doc_ident_responsable").val());
                    $('#nom_completo_responsable').focus(); 
                  }

              });  
          },
          complete : function(xhr, status) {
              $('#content').html('');
              $('#btnBuscar_responsable').prop('disabled', false);
          }
      });    
   
  } 

  function calcular_edad_responsable(fecha_nac){ 
    if(fecha_nac != null && fecha_nac != undefined && fecha_nac.length>8 ){
      var oldDate = fecha_nac; //20/04/1946
      var values = oldDate.split("/");
      if(values.length==1){
        var values = oldDate.split("-");
        var dia = values[2];
        var mes = values[1];
        var ano = values[0]; 
      }else{
        var dia = values[0];
        var mes = values[1];
        var ano = values[2];
      }  
      calcularEdadPersona(ano,mes,dia,'input','','edad_de_responsable')

    }
  }
</script>
