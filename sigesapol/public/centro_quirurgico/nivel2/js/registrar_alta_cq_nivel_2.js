$(document).ready(function () {
  const condicionesDomicilio = [
    { id: "", label: "-- seleccionar --"},
    { id: 1, label: "CURADO"},
    { id: 8, label: "MEJORADO"},
  ];

  const condicionesHospitalizacion = [
    { id: 7, label: "TRANSFERIDO"},
  ];

  let formRegisterAlta = [
    { id: 1, name: 'cq_alta_destino', required: true, initialValue: "" },
    { id: 2, name: 'cq_alta_condicion', required: true, initialValue: "" },
    { id: 3, name: 'cq_alta_fecha_egreso', required: true, initialValue: moment().format("DD/MM/YYYY")  },
    { id: 4, name: 'cq_alta_hora_egreso', required: true, initialValue: "" },
    { id: 5, name: 'cq_alta_servicio_hospitalizacion', required: false, is_select2: true, initialValue: "" },
    // { id: 6, name: 'cq_alta_sala_cargo', required: false, initialValue: "" },
    { id: 7, name: 'cq_alta_sala_destino', required: false, is_select2: true, initialValue: "" },
    { id: 8, name: 'cq_alta_cama', required: false,  is_select2: true, initialValue: "" },
    { id: 9, name: 'cq_alta_resumen_enfermedad_actual', required: false, initialValue: "" },
    { id: 10, name: 'cq_alta_examen_clinico', required: false, initialValue: "" },
    { id: 11, name: 'cq_alta_examenes_auxiliares', required: false, initialValue: "" },
    { id: 12, name: 'cq_alta_evolucion', required: false, initialValue: "" },
    { id: 14, name: 'cq_alta_tratamiento', required: false, initialValue: "" },
    { id: 15, name: 'cq_alta_procedimientos_terap_diag', required: false, initialValue: "" },
    { id: 15, name: 'cq_alta_complicaciones', required: false, initialValue: "" },
    { id: 15, name: 'cq_alta_medidas_generales', required: false, initialValue: "" },
    { id: 15, name: 'cq_alta_medidamentos_prescritos', required: false, initialValue: "" },
    { id: 15, name: 'cq_alta_pronostico', required: false, initialValue: "" },
  ];

  $('#modalCqAltaNivel2').on('shown.bs.modal', function (e) {
    main();
  });
  
  $(".close").on("click", function () {
    resetFormRegisterAlta();
  });
  
  $("#cqBtnCancelAlta").on("click", function () {
    resetFormRegisterAlta();
  });

  $('#cq_alta_servicio_hospitalizacion').select2({ width: '100%', dropdownParent: $('#modalCqAltaNivel2') });
  $('#cq_alta_sala_destino').select2({ width: '100%', dropdownParent: $('#modalCqAltaNivel2') });
  $('#cq_alta_cama').select2({ width: '100%', dropdownParent: $('#modalCqAltaNivel2') });

  $('#cq_alta_fecha_egreso').datepicker({
    useCurrent: false,
    format: 'dd/mm/yyyy',
    todayHighlight: true,
    autoclose: true,
    orientation: "bottom left"
  });

  $("#cq_alta_destino").on("change", function () {
    const value = $(this).val();
    const flagDestino = value && parseInt(value) === 2;
    if (flagDestino) {
      $("#section_servicio_upss").slideDown();
      $("#section_destino_ubicacion").slideDown();
      $("#cq_alta_section_epicrisis").slideUp();

      $("#cq_alta_resumen_enfermedad_actual").val("");
      $("#cq_alta_examen_clinico").val("");
      $("#cq_alta_examenes_auxiliares").val("");
      $("#cq_alta_evolucion").val("");
      $("#cq_alta_tratamiento").val("");
      $("#cq_alta_procedimientos_terap_diag").val("");
      $("#cq_alta_complicaciones").val("");
      $("#cq_alta_medidas_generales").val("");
      $("#cq_alta_medidamentos_prescritos").val("");
      $("#cq_alta_pronostico").val("");
    } else {
      $("#section_servicio_upss").slideUp();
      $("#section_destino_ubicacion").slideUp();
      $("#cq_alta_section_epicrisis").slideDown();
    }
    setRequiredInConfig('cq_alta_servicio_hospitalizacion', flagDestino);
    setRequiredInConfig('cq_alta_sala_cargo', flagDestino);
    setRequiredInConfig('cq_alta_sala_destino', flagDestino);
    setRequiredInConfig('cq_alta_cama', flagDestino);
    addAsteriskToLabel();
    handlerOptionsSelectCondicion(value);
  });

  $("#cq_alta_sala_destino").change(function (e) { 
    e.preventDefault();
    const value = $(this).val();
    if (value) {
      getCamasByIdServicio(value);
    }
  });

  $("#cqBtnRegisterAlta").on("click", function () {
    onSubmitForm();
  });

  async function main() {
    try {
      Swal.fire({
        title: 'CARGANDO INFORMACIÓN',
        html: 'Por favor espera un momento...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
      addAsteriskToLabel();
      const dataForm = await getDataForm();
      if (dataForm && dataForm.data) {
        buildSetOptionsSelect(dataForm.data.upsses, dataForm.data.consultorios) 
        if (dataForm.data.intervencion && dataForm.data.intervencion.id_establecimiento) {
          $("#fa_id_establecimiento").val(dataForm.data.intervencion.id_establecimiento);
        }
      }
      if (dataForm && dataForm.data && dataForm.data.currentConsultorio) {
        $("#fa_servicio_cargo_id").val(dataForm.data.currentConsultorio.id);
        $("#cq_alta_sala_cargo").val(dataForm.data.currentConsultorio.nombre);
      }
      resetFormRegisterAlta();
    } catch (error) {
      console.error('Error en main:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Ocurrió un problema cargando la información.'
      });
    } finally {
      Swal.close();
    }
  };

  function addAsteriskToLabel() {
    for (const control of formRegisterAlta) {
      if (control.required) {
        const label = $('label[for="' + control.name + '"]');
        const labelText = label.text();
        if (!labelText.includes('*')) {
          label.html(labelText + ' <span style="color:red;">*</span>');
        }
      }
    }
  }

  function onSubmitForm() {
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

    const body = buildRequestSave();
      $.ajax({
        url: '/api/cqx_intervenciones/alta/nivel2',
        type: 'PUT',
        dataType: 'json',
        data: body,
        beforeSend: function () {
          Swal.fire({
            title: 'Procesando solicitud',
            text: 'Registrando Alta. Por favor, espera...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
        }
      }).done(function (response) {
        Swal.close();
        const message = response.message || 'el registro de alta se procesó correctamente.';
        Swal.fire({
          icon: 'success',
          title: 'INTERVENCIONES QUIRURGICAS',
          text: message,
          timer: 2000,
          timerProgressBar: true,
          showConfirmButton: false
        });
        onSearchIntervencionNivel2();
        
        $('#modalCqAltaNivel2').modal('hide');
        $("#fr_det_alta_cqx_intervencion_id").val(body.intervencionId);
        $('#modalCqAltaNivel2').one('hidden.bs.modal', function () {
          $('#modalCqDetalleAltaNivel2').modal({ backdrop: 'static', keyboard: false }).modal('show');
          $('body').addClass('modal-open');
        });

        // $('#modalCqAltaNivel2').modal('hide');
        // $('#modalCqDetalleAltaNivel2').modal({ backdrop: 'static', keyboard: false }).modal('show');
        // $("#fr_det_alta_cqx_intervencion_id").val(body.intervencionId);
      }).fail(function (jqXHR, textStatus, errorThrown) {
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
      }).always(function () {});
  }

  function validateForm() {
    const controlsError = [];

    for (const control of formRegisterAlta) {
      if (!control.required) continue;

      const $input = $(`#${control.name}`);
      if (!$input.length) continue;

      const $s2Container = getSelect2Container($input);
      const isSelect2 = $s2Container.length > 0;

      // limpiar estado anterior
      clearFieldError($input, $s2Container);

      const parentDiv = control.tr_id
        ? $input.closest('td')
        : $input.closest('.form-group');

      if (!parentDiv.length) continue;

      const value = $input.val();
      const isEmpty = value === "" || value === null || value === undefined;

      if (isEmpty) {
        parentDiv.addClass('has-error');
        setFieldError($input, $s2Container, 'campo requerido');
        controlsError.push(control);
      } else {
        parentDiv.removeClass('has-error');
        clearFieldError($input, $s2Container);
      }
    }

    focusFirstInputError(controlsError);
    return controlsError.length === 0;
  }

  function setFieldError($input, $s2Container, message) {
    const msg = message || 'campo requerido';

    if ($s2Container && $s2Container.length) {
      $s2Container.nextAll('.label-help').remove();
      $s2Container.after(`<span class="label-help">${msg}</span>`);

      const $visSelection = $s2Container.find('.select2-selection').first();
      ($visSelection.length ? $visSelection : $s2Container).addClass('select2-error');
    } else {
      $input.nextAll('.label-help').remove();
      $input.after(`<span class="label-help">${msg}</span>`);
      $input.addClass('input-error');
    }
  }

  function setFieldWarning($input, $s2Container, message) {
    const msg = message || 'campo requerido';

    if ($s2Container && $s2Container.length) {
      $s2Container.nextAll('.label-warn').remove();
      $s2Container.after(`<span class="label-warn">${msg}</span>`);

      const $visSelection = $s2Container.find('.select2-selection').first();
      ($visSelection.length ? $visSelection : $s2Container).addClass('select2-warn');
    } else {
      $input.nextAll('.label-warn').remove();
      $input.after(`<span class="label-warn">${msg}</span>`);
      $input.addClass('input-warn');
    }
  }

  function clearFieldWarning($input, $s2Container) {
    if ($s2Container && $s2Container.length) {
      $s2Container.nextAll('.label-warn').remove();
      $s2Container
        .find('.select2-selection, .select2-selection--single')
        .removeClass('select2-warn');
      $s2Container.removeClass('select2-warn');
    }
    $input.nextAll('.label-help').remove();
    $input.removeClass('input-warn');
  }

  function clearFieldError($input, $s2Container) {
    if ($s2Container && $s2Container.length) {
      $s2Container.nextAll('.label-help').remove();
      $s2Container
        .find('.select2-selection, .select2-selection--single')
        .removeClass('select2-error');
      $s2Container.removeClass('select2-error');
    }
    $input.nextAll('.label-help').remove();
    $input.removeClass('input-error');
  }

  function focusFirstInputError(errors = []) {
    if (errors && errors.length > 0) {
      const controFirst = errors[0];
      $(`#${controFirst.name}`).focus();

      if ($(`#${controFirst.name}`).data('select2')) {
        $(`#${controFirst.name}`).select2('open');
      }
    }
  }

  function getSelect2Container($select) {
    if (!$select || !$select.length) return $();
    const candidates = [
      () => $select.next('span.select2'),
      () => $select.next('.select2-container'),
      () => $select.siblings('span.select2'),
      () => $select.siblings('.select2-container'),
      () => $select.closest('.form-group').find('span.select2').first(),
      () => $select.closest('.form-group').find('.select2-container').first()
    ];
    for (const getCandidate of candidates) {
      const $c = getCandidate();
      if ($c && $c.length) return $c;
    }
    return $();
  }

  function setRequiredInConfig(fieldName, isRequired) {
    formRegisterAlta = formRegisterAlta.map(field => {
      if (field.name === fieldName) {
        return {
          ...field,
          required: !!isRequired,
        };
      }
      return field;
    });
  }

  function buildRequestSave() {
    const body = {
      intervencionId: $("#fa_id_intervencion").val(),
      destino: $("#cq_alta_destino").val(),
      condicion: $("#cq_alta_condicion").val(),
      fechaEgreso: $("#cq_alta_fecha_egreso").val(),
      horaEgreso: $("#cq_alta_hora_egreso").val(),
      servicioHospitalizacion: $("#cq_alta_servicio_hospitalizacion").val(),
      salaCargo: $("#cq_alta_sala_cargo").val(),
      salaDestino: $("#cq_alta_sala_destino").val(),
      cama: $("#cq_alta_cama").val(),
      resumenEnfermedadActual: $("#cq_alta_resumen_enfermedad_actual").val(),
      examenClinico: $("#cq_alta_examen_clinico").val(),
      examenesAuxiliares: $("#cq_alta_examenes_auxiliares").val(),
      evolucion: $("#cq_alta_evolucion").val(),
      tratamiento: $("#cq_alta_tratamiento").val(),
      procedimientosTerapeuticos: $("#cq_alta_procedimientos_terap_diag").val(),
      complicaciones: $("#cq_alta_complicaciones").val(),
      medidasGenerales: $("#cq_alta_medidas_generales").val(),
      medidamentosPrescritos: $("#cq_alta_medidamentos_prescritos").val(),
      pronostico: $("#cq_alta_pronostico").val()
    }
    
    return body
  }

  function resetFormRegisterAlta() {
    for (const control of formRegisterAlta) {
      const $input = $(`#${control.name}`);
      if (control.name === 'cq_alta_cama') {
        const $select2Container = getSelect2Container($input);
        clearFieldWarning($input, $select2Container);
      }
      if (control.is_select2) {
        $input.val(control.initialValue || "").trigger("change.select2");
      } else {
        $input.val(control.initialValue || "");
      }
    }
    $('#cq_alta_condicion').prop('disabled', false);
    $('#cq_alta_condicion').empty();
    $.each(condicionesDomicilio, function(index, option) {
      $('#cq_alta_condicion').append($('<option>', { value: option.id, text: option.label }));
    }); 
    $("#cq_alta_condicion").val("");
    $("#cq_alta_hora_egreso").val(moment().format("HH:mm"));
    $("#cq_alta_fecha_egreso").val(moment().format("DD/MM/YYYY"));
    $("#section_servicio_upss").hide();
    $("#section_destino_ubicacion").hide();
  }

  function getDataForm() {
    const id_intervencion = $("#fa_id_intervencion").val();
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/cqx_intervenciones/alta/nivel2/${id_intervencion}`,
        method: 'GET',
        dataType: 'json'
      })
      .done(function (response) {
        resolve(response);
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error en la petición:', textStatus, errorThrown);
        reject({ jqXHR, textStatus, errorThrown });
      })
    });
  }

  function getCamasByIdServicio(id_servicio_ubicacion) { 
    const $selectCamas = $('#cq_alta_cama');
    const $select2Container = getSelect2Container($selectCamas);

    $selectCamas.empty();
    $selectCamas.prop('disabled', false);
    $selectCamas.append($('<option>', { value: "", text: "Cargando camas..." }));
    $selectCamas.val("").trigger('change');
    $selectCamas.prop('disabled', true);

    clearFieldWarning($selectCamas, $select2Container);
    
    const id_ipress = $('#fa_id_establecimiento').val();
    $.ajax({
      url: `/get_camas_by_idestablecimiento_nombretipoatencion_idcuarto_activos/${id_ipress}/HOSPITALIZACION/${id_servicio_ubicacion}`,
      type: 'GET',
      dataType: 'json',
    })
    .done(function (response) {
      $selectCamas.empty();
      $selectCamas.append($('<option>', { value: "", text: "-- seleccionar --" }));
      $selectCamas.val("").trigger('change');
      $('#cq_alta_cama').val("").trigger('change');
      if (response && response.length > 0) {
        $selectCamas.prop('disabled', false);
        $.each(response, function(index, cama) {
          $selectCamas.append($('<option>', { value: cama.id, text: `${cama.nombre} - ${cama.cuarto_nombre}`}));
        });
      } else {
        setFieldWarning($selectCamas, $select2Container, 'no existe camas disponibles');
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {})
  }

  function buildSetOptionsSelect(upsses = [], consultorios = []) {
    const $selectUpss = $('#cq_alta_servicio_hospitalizacion');
    const $selectSalaDestino = $('#cq_alta_sala_destino');
    $selectUpss.empty();
    $selectSalaDestino.empty();

    $selectUpss.append($('<option>', { value: "", text: "-- seleccionar --" }));
    $selectSalaDestino.append($('<option>', { value: "", text: "-- seleccionar --" }));

    $.each(upsses, function(index, upss) {
      $selectUpss.append($('<option>', { value: upss.id_upss, text: upss.nombre }));
    });

    $.each(consultorios, function(index, consultorio) {
      $selectSalaDestino.append($('<option>', { value: consultorio.id, text: consultorio.nombre }));
    });
  }

  function handlerOptionsSelectCondicion(destinoId) {
    $('#cq_alta_condicion').prop('disabled', false);
    const $selectCondicion = $('#cq_alta_condicion');
    $selectCondicion.empty();
    const options = parseInt(destinoId) === 1 ? condicionesDomicilio : condicionesHospitalizacion;
    $.each(options, function(index, option) {
      $selectCondicion.append(
        $('<option>', {
          value: option.id,
          text: option.label
        })
      );
    }); 
    if (parseInt(destinoId) === 2 ) {
      $('#cq_alta_condicion').prop('disabled', true);
    }
  }
});
