$(document).ready(function () {
  const ReasonMaxLength = 255;
  const formSuspenderInter = [
    { id: 1, name: 'cpx_suspender_motivo', required: true },
  ];

  addAsteriskToLabel();

  $('#cpx_suspender_motivo').on('input', function () {
    const currentLength = $(this).val().length;
    $('#reason-length').text(currentLength);
    if (currentLength > ReasonMaxLength) {
      $(this).val($(this).val().substring(0, ReasonMaxLength));
      $('#reason-length').text(ReasonMaxLength);
    }
  });

  $("#btnSuspenderInt").on("click", function () {
    onSubmitFormSuspenderInt();
  });

  function addAsteriskToLabel() {
    for (const control of formSuspenderInter) {
      if (control.required) {
        const label = $('label[for="' + control.name + '"]');
        const labelText = label.text();
        if (!labelText.includes('*')) {
          label.html(labelText + ' <span style="color:red;">*</span>');
        }
      }
    }
  }

  function onSubmitFormSuspenderInt() {
    const formValid = validateForm();
    if (!formValid) {
      Swal.fire({
        icon: "error",
        title: "<p style='font-size: 16px; font-weight: bold;'>Campos requeridos incompletos</p>",
        html: `<div style='padding: 10px 20px; font-size: 18px; text-align: center;'>
                <p>Por favor, complete los campos obligatorios marcados con <span style="color: #dc3545;">*</span> para continuar.</p>
              </div>`,
        confirmButtonText: "OK"
      });
      return null;
    }
    const cqx_intervencio_id = $('#cqx_intervencio_id').val();
    const body = {
      motivo: $('#cpx_suspender_motivo').val().trim(),
    };
    $.ajax({
      url: `/api/cqx_intervenciones/${cqx_intervencio_id}/suspender`,
      type: "PATCH",
      data: body,
      dataType: "json",
      beforeSend: function () {
        Swal.fire({
          title: 'Suspendiendo...',
          text: 'Por favor espera',
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => Swal.showLoading()
        });
      }
    }).done(function (response) {
      Swal.close();
      if (response.success) {
        Swal.fire({
          icon: 'success',
          title: 'Suspendida',
          text: response.message || 'La intervención ha sido suspendida.',
          timer: 2000,
          showConfirmButton: false
        });
        onSearchIntervencionNivel2();
        $('#cpx_suspender_motivo').val();
        $('#modalSuspender').modal('hide');
      }
    }).fail(function (jqXHR) {
      Swal.close();
      let msg = 'Ocurrió un error inesperado.';
      if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
        msg = jqXHR.responseJSON.message;
      }
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: msg,
      });
    });
  }

  function validateForm() {
    let controlsError = [];
    for (const control of formSuspenderInter) {
      if (control.required) {
        const inputElement = $(`#${control.name}`);
        inputElement.nextAll('.label-help').remove();
        let parentDiv = null;
        if (control.tr_id) {
          parentDiv = inputElement.closest('td');
        } else {
          parentDiv = inputElement.closest('.form-group');
        }
        if (parentDiv) {
          if (inputElement.val() === "") {
            parentDiv.addClass('has-error');
            inputElement.after(`<span class="label-help">campo requerido</span>`);
            controlsError.push(control);
          } else {
            parentDiv.removeClass('has-error');
            inputElement.nextAll('.label-help').remove();
          }
        }
      }
    }
    return controlsError.length === 0;
  }
});