<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<script>
  var ppFirmaTokenDocumento = null;
  var ppFirmaTokenDatos = { id_solicitud: {{ intval($solicitud->id_solicitud) }}, id_procedimiento: {{ intval($id_procedimiento) }} };
  var ppCsrfToken = function () { return $('meta[name="csrf-token"]').attr('content'); };

  function ppIniciarFirmaToken() {
    Swal.fire({
      title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Preparando los documentos para la firma</span></div>",
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      onBeforeOpen: () => { Swal.showLoading(); }
    });
    $.ajax({
      url: '/pp_preparar_firma_token',
      type: 'POST',
      data: $.extend({ _token: ppCsrfToken() }, ppFirmaTokenDatos),
      dataType: 'json'
    }).done(function (res) {
      Swal.close();
      if (res && res.sw) {
        ppFirmaTokenDocumento = res.documento;
        initInvoker('W');
      } else {
        Swal.fire({ icon: 'info', title: 'Firma de la atención', text: (res && res.msg) ? res.msg : 'No se pudo preparar los documentos para la firma.' }).then(function () { window.location.reload(); });
      }
    }).fail(function () {
      Swal.close();
      Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo preparar los documentos para la firma.' }).then(function () { window.location.reload(); });
    });
  }

  $(document).on('click', '#btnFirmarTokenPP', function () {
    ppIniciarFirmaToken();
  });

  window.addEventListener('getArguments', async function (e) {
    if (!ppFirmaTokenDocumento) return;
    try {
      const response = await fetch("{{ route('pp_gastro_post_arguments') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': ppCsrfToken() },
        body: JSON.stringify({ type: 'W', documentName: ppFirmaTokenDocumento })
      });
      const data = await response.text();
      await dispatchEventClient('sendArguments', data);
    } catch (error) {
      console.error('Error al obtener argumentos de firma:', error);
    }
  });

  window.addEventListener('invokerOk', function (e) {
    if (!ppFirmaTokenDocumento) return;
    ppConfirmarFirmaToken(0);
  });

  window.addEventListener('invokerCancel', function (e) {
    ppFirmaTokenDocumento = null;
    window.location.reload();
  });

  function ppConfirmarFirmaToken(intento) {
    $.post('/pp_confirmar_firma_token', $.extend({ _token: ppCsrfToken() }, ppFirmaTokenDatos), function (res) {
      if (res && res.status === 'ok') {
        ppFirmaTokenDocumento = null;
        Swal.fire({ icon: 'success', title: 'Documentos firmados digitalmente', showConfirmButton: false, timer: 1800 }).then(function () { window.location.reload(); });
      } else if (res && res.status === 'pendiente' && intento < 10) {
        setTimeout(function () { ppConfirmarFirmaToken(intento + 1); }, 2000);
      } else {
        Swal.fire({ icon: 'info', title: 'Firma de la atención', text: (res && res.message) ? res.message : 'La firma aún no se refleja. Actualice la página en unos minutos.' }).then(function () { window.location.reload(); });
      }
    });
  }
</script>
