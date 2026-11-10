$(document).ready(function () {
  const destino = {
    1: "DOMICILIO",
    2: "HOSPITALIZACIÓN",
  };

  const condiciones = {
    1: "CURADO",
    7: "TRANSFERIDO",
    8: "MEJORADO",
  }

  $('#modalCqDetalleAltaNivel2').on('shown.bs.modal', function () {
    getAltaIntervencion();
  });

  $('#modalCqDetalleAltaNivel2').on('hide.bs.modal', function () {
    console.log('El modal está a punto de cerrarse');
  });

  $("#btnFirmarCqxAltaNivel2").on("click", function () {
    Swal.fire({
      title: "¿Está seguro?",
      text: "¿Desea firmar la alta de la intervención quirurgica?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, firmar!",
    }).then((result) => {
      if (result.isConfirmed) {
        firmarPdfAltaCqxNivel2();
      }
    });
  });

  function getAltaIntervencion() {
    const cqxIntervencionId = $("#fr_det_alta_cqx_intervencion_id").val();
    $.ajax({
        url: `/api/cqx_intervenciones/${cqxIntervencionId}/alta/nivel2`,
        method: 'GET',
        dataType: 'json',
        beforeSend: function () {
          Swal.fire({
            html: `<div style="font-size: 17px;font-weight: 600; margin-bottom: 6px;">Consultando información...</div>
              <div style="font-size: 14px;color: #444;margin-top: 4px;">Estamos validando los datos, por favor espera unos segundos.</div>`,
            allowOutsideClick: false,
            allowEscapeKey: false,
            width: '100px',
            didOpen: () => Swal.showLoading()
          });
        }
    })
    .done(function (response) {
      Swal.close();
      if (response && response.data && response.data) {
        setForm(response.data)
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      $('#modalCqDetalleAltaNivel2').modal('hide');
      let mensajeError = 'Ocurrió un error al procesar la solicitud. Por favor, inténtalo nuevamente.';
      if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
        mensajeError = jqXHR.responseJSON.message;
      }
      Swal.fire({
        icon: 'error',
        title: 'No se pudo completar la operación',
        text: mensajeError,
        confirmButtonText: 'Entendido'
      });
    });
  }

  function setForm(data) {
    if (parseInt(data.hospitalizacion_id_tipo_egreso) === 2) {
      $("#cq_det_section_servicio_upss").slideDown();
      $("#cq_det_section_destino_ubicacion").slideDown();

      $("#cq_det_alta_section_epicrisis").slideUp();
    } else {
      $("#cq_det_alta_section_epicrisis").slideDown();
    }
    
    const fechaEgreso = moment(data.hospitalizacion_fecha_alta_medica).format("DD/MM/YYYY") || "-";
    const horaEgreso = moment(data.hospitalizacion_hora_alta_medica, "HH:mm:ssZ").format("HH:mm") || "-";
    $("#cq_det_alta_destino").val(destino[data.hospitalizacion_id_tipo_egreso] || "-");
    $("#cq_det_alta_condicion").val(condiciones[data.hospitalizacion_id_condicion_alta] || "-");
    $("#cq_det_alta_fecha_egreso").val(fechaEgreso);
    $("#cq_det_alta_hora_egreso").val(horaEgreso);
    $("#cq_det_alta_servicio_hospitalizacion").val(data.descripcion_upss || "-");
    $("#cq_det_alta_sala_cargo").val(data.consultorio || "-");
    $("#cq_det_alta_cama").val(data.cama_nombre || "-");
    $("#cq_det_alta_resumen_enfermedad_actual").val(data.egreso_resumen || "-");
    $("#cq_det_alta_examen_clinico").val(data.egreso_examen_clinico || "-");
    $("#cq_det_alta_examenes_auxiliares").val(data.egreso_examen_auxiliar || "-");
    $("#cq_det_alta_evolucion").val(data.egreso_evolucion || "-");
    $("#cq_det_alta_tratamiento").val(data.egreso_tratamiento || "-");
    $("#cq_det_alta_procedimientos_terap_diag").val(data.egreso_procedimiento || "-");
    $("#cq_det_alta_complicaciones").val(data.egreso_complicacion || "-");
    $("#cq_det_alta_medidas_generales").val(data.egreso_medidas_generales || "-");
    $("#cq_det_alta_medidamentos_prescritos").val(data.egreso_medicamentos_prescritos || "-");
    $("#cq_det_alta_pronostico").val(data.egreso_pronostico || "-");
  }

  function firmarPdfAltaCqxNivel2() {
    $("#btnFirmarCq").attr("disabled", true);
    Swal.fire({
      title: 'Generando PDF para firmar...',
      text: 'Por favor espere unos segundos.',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => Swal.showLoading()
    });
    const id_intervencion = $("#fr_det_alta_cqx_intervencion_id").val();
    $.ajax({
      url: `/api/cqx_intervenciones/${id_intervencion}/firmar-alta`,
      type: "GET",
      dataType: "json",
    })
    .done(function (response) {
      Swal.close();
      if (response.sw) {
        var csv = "<?php echo url()?>/tmp/csv/" + response.csv + ".csv";
        $("#fr_det_doc_firmar").val(`${response.csv}.7z`);
        $("#fr_det_opt_firma").val(`${response.opt}`);
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
    const arg = $("#fr_det_argumentos").val();
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
      documentName: $('#fr_det_doc_firmar').val(),
      entidad: $("#fr_det_user_dni").val()
    }, function(data, status) {
      document.getElementById("fr_det_argumentos").value = data;
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
      url: `/api/cqx_intervenciones/firmar-alta/confirmar`,
      type: "POST",
      dataType: "json",
      data: {
        _token: CSRF_TOKEN,
        id_intervencion: $("#fr_det_alta_cqx_intervencion_id").val(),
      }
    })
    .done(function (data) {
      Swal.close();
      $('#modalCqDetalleAltaNivel2').modal('hide');
      onSearchIntervencionNivel2();
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