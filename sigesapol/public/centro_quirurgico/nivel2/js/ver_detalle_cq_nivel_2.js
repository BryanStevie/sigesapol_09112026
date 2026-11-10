$(document).ready(function () {
  $("#btnFirmarCq").on("click", function () {
    Swal.fire({
      title: "¿Está seguro?",
      text: "¿Desea firmar la atencion de la intervención quirurgica?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, firmar!",
    }).then((result) => {
      if (result.isConfirmed) {
        firmarPdfCqxIntervencion();
      }
    });
  });

  function firmarPdfCqxIntervencion() {
    $("#btnFirmarCq").attr("disabled", true);
    Swal.fire({
      title: 'Generando PDF para firmar...',
      text: 'Por favor espere unos segundos.',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => Swal.showLoading()
    });
    const id_intervencion = $("#id_intervencion").val();
    $.ajax({
      url: `/api/cqx_intervenciones/${id_intervencion}/firmar-pdf`,
      type: "GET",
      dataType: "json",
    })
    .done(function (resultado) {
      Swal.close();
      if (resultado.sw) {
        var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
        $("#doc_firmar").val(`${resultado.csv}.7z`);
        $("#opt_firma").val(`${resultado.opt}`);
        initInvoker("W");
      }
    })
    .fail(function (jqXHR, textStatus) {
      Swal.close();
      let mensaje = "Ocurrió un error inesperado.";
      if (jqXHR.responseJSON) {
        mensaje = jqXHR.responseJSON.message || jqXHR.responseJSON.error || mensaje;
      }
      Swal.fire({
        icon: "error",
        title: "Error",
        text: mensaje
      });
    })
    .always(function () {
      $("#btnFirmarCq").attr("disabled", false);
    });
  }

  window.addEventListener('getArguments', async function(e) {
    await ObtieneArgumentosParaFirmaDesdeLaWeb();
  });

  async function getArguments() {
    const arg = $("#argumentos").val();
    await dispatchEventClient('sendArguments', arg);
  }

  window.addEventListener('invokerOk', function(e) {
    MiFuncionOkWeb();
  });

  window.addEventListener('invokerCancel', function(e) {
    MiFuncionCancel();
  });


  async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    $.post("/refirma/postArgumentsCqxIntervencionNivel2", {
      _token: CSRF_TOKEN,
      type: "W",
      documentName: $('#doc_firmar').val(),
      entidad: $("#user_dni").val()
    }, function(data, status) {
      document.getElementById("argumentos").value = data;
      getArguments();
    });

  }

  async function MiFuncionOkWeb() {
    let CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    Swal.fire({
      title: "Actualizando...",
      text: "Por favor espere un momento.",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });
    $.ajax({
      url: `/api/cqx_intervenciones/firmar-pdf/confirmar`,
      type: "POST",
      dataType: "json",
      data: {
        _token: CSRF_TOKEN,
        id_intervencion: $("#id_intervencion").val(),
        cqx_atencion_id: $("#cqx_atencion_id").val(),
        prestacion_id: $("#prestacion_id").val(),
      }
    })
    .done(function (data) {
      location.reload(true);
    })
    .fail(function (jqXHR) {
      Swal.close();
      let mensaje = "Ocurrió un error inesperado.";
      if (jqXHR.responseJSON) {
        mensaje = jqXHR.responseJSON.message || jqXHR.responseJSON.error || mensaje;
      }
      Swal.fire({ icon: "error", title: "Error", text: mensaje });
    });
  }

  function MiFuncionCancel() {
    Swal.close();
    Swal.fire({
      icon: "warning",
      title: "Proceso Cancelado",
      text: "El proceso de firma digital fue cancelado.",
      confirmButtonText: "Entendido",
      confirmButtonColor: "#d33",
    });
  }
});
