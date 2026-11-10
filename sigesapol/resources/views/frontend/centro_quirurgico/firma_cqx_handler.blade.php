<script>
  let tipo_envio ="";
  window.addEventListener('invokerOk', function(e) {
      const quienFirma = $("#flag_firma_centro_quirurgico").val();
      // const atencionReceta = $("#flag_firma_receta").val();
      if (quienFirma == "1") {
        actualiza_datos_firma_con_token_io();
      }else if (quienFirma == "2") {
        actualiza_datos_firma_con_token_io_hr();
      }else if (quienFirma == "3" && tipo_envio == "receta"){
        console.log('RECETA');
        actualiza_datos_firma_con_token_io_receta();
      } else if (quienFirma == "3" && tipo_envio == "atencion") {
          console.log('ATENCION');
          actualiza_datos_firma_con_token_io_atencion_cqx();
      }else if (quienFirma == "4" && tipo_envio == "receta_anestesio"){
        actualiza_datos_firma_con_token_io_receta_anestesio();
      }
      // AGREGAR DEMAS METODOS
  });

  window.addEventListener('invokerCancel', function(e) {
    MiFuncionCancel(); 
  });

  window.addEventListener('getArguments', async function(e) {
      const quienFirma = $("#flag_firma_centro_quirurgico").val();
      //const atencionReceta = $("#flag_firma_receta").val();
      if (quienFirma == "1") {
          await ObtieneArgumentosParaFirmaDesdeLaWebIntraoperatorio();
      }else if (quienFirma == "2") {
          await ObtieneArgumentosParaFirmaDesdeLaWebHojaRecuperacion();
      }else if (quienFirma == "3" && tipo_envio == "receta") {
          await ObtieneArgumentosParaFirmaDesdeLaWebAtencionCqxReceta();
      } else if (quienFirma == "3" && tipo_envio == "atencion") {
          await ObtieneArgumentosParaFirmaDesdeLaWebAtencionCqx();
      }else if (quienFirma == "4" && tipo_envio == "receta_anestesio") {
          await ObtieneArgumentosParaFirmaDesdeLaWebAtencionCqxRecetaAnestesio();
      }
      //Agregar los demas metodos
  });


  function MiFuncionCancel() {
      alert("El proceso de firma digital fue cancelado.");
  }

</script>