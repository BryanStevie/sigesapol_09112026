(() => {
  function firmar() {
    $.ajax({
      url: `${
        window.location.href.split("/")[0]
      }/genera_csv_modificacion`,
      type: "post",
      data: $("#frmBloque").serialize(),
      dataType: "json",
    })
      .done(function (resultado) {
        console.log(resultado);
        if (resultado.sw) {
          var csv = `${window.location.href.split("/")[0]}/tmp/csv/${
            resultado.csv
          }.csv`;
          $("#doc_firmar").val(`${resultado.csv}.7z`);
          $("#opt_firma").val(`${resultado.opt}`);
          $("#flagServicioFirma").val("1");
          initInvoker("W");
        } else {
        }
        $("#btnLiquidacionFirmarPdf").attr("disabled", false);
      })
      .fail(function (jqXHR, textStatus) {
        console.log("Request failed: " + textStatus);
      })
      .always(function () {});
  }

  $(document).on("click", "#btnfirmar", function (e) {
    e.preventDefault();
    const data = $("#frmBloque").serialize();
    //console.log(data);
    firmar();
  });

  //
  window.addEventListener("getArguments", async function (e) {
    await ObtieneArgumentosParaFirmaDesdeLaWeb();
  });

  async function getArguments() {
    arg = document.getElementById("argumentos").value;
    await dispatchEventClient("sendArguments", arg);
  }

  window.addEventListener("invokerOk", function (e) {
    MiFuncionOkWeb();
  });

  window.addEventListener("invokerCancel", function (e) {
    MiFuncionCancel();
  });

  async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr("content");
      $.post(
        `${window.location.href.split("/")[0]}/refirma/postArguments`,
        {
          _token: CSRF_TOKEN,
          type: "W",
          documentName: $("#doc_firmar").val(),
          
          entidad: $("#dni").val(),
        },
        function (data, status) {
          document.getElementById("argumentos").value = data;
          getArguments();
        }
      );
  }

  async function MiFuncionOkWeb() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    let id_resol = $('#id_prestacion').val();
    
    $.post(
      `${window.location.href.split("/")[0]}/refirma/actualizar_prestacion_modificada`,
    {
      _token: CSRF_TOKEN,
      id_tipo: "prestacion",
      id_resol: id_resol,
    }, function(data, status) {
        location.href = '/all_citas_medico_atenciones';
    });
  }

  function MiFuncionCancel() {
    alert("El proceso de firma digital fue cancelado.");
  }
})();

