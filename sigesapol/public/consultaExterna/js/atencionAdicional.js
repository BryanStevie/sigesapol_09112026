// $(document).ready(function () {
  let isFirstActiveToogle = false;
  let listCqxProfesionales = [];
  let listCqxTipoProfessionales = [];
  let formProgramacionQuirurgica = [
    { id: 1, name: 'id_tiempo_internamiento_preqx', required: true, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Seleccione el tiempo de internamiento prequirúrgico en programación quirúrgica" },
    { id: 2, name: 'id_tiempo_hospitalizacion_postqx', required: true, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Seleccione el tiempo de hospitalización postquirúrgico en programación quirúrgica" },
    { id: 3, name: 'id_lugar_operacion', required: true, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Seleccione el lugar de operación en programación quirúrgica" },
    { id: 5, name: 'hora_inicio_pq', required: true, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Ingrese la hora de inicio en programación quirúrgica" },
    { id: 6, name: 'hora_fin_pq', required: true, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Ingrese la hora de fin en programación quirúrgica" },
    { id: 7, name: 'tiempo_curacion_cqx', required: false, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Ingrese el tiempo de duración de la cirugía en programación quirúrgica" },
    { id: 8, name: 'fecha_probable_pq', required: false, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Ingrese la fecha probable de cirugía en programación quirúrgica" },
    { id: 9, name: 'id_prioridad_programacion', required: true, minValue: 1, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Ingrese el turno en programación quirúrgica" },
    { id: 10, name: 'id_tipo_cirugia', required: true, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Seleccione el tipo de cirugía en programación quirúrgica" },
  ];

  $('#fecha_probable_pq').datepicker({
    dateFormat: 'dd-mm-yy',
    autoclose: true,
    changeMonth: true,
    changeYear: true,
    minDate: 0
  });

  function showAlertExistsIntervencion() {
    const intervencion_quirurgica = window.intervencion_quirurgica || null;
    if (intervencion_quirurgica && intervencion_quirurgica.estado) {
      Swal.fire({
        icon: 'warning',
        title: '<p style="font-size: 17px">INTERVENCIÓN QUIRÚRGICA</p>',
        html: `<div style='padding: 0px 50px'><p style="font-size: 15px">El paciente ya tiene un solicitud de intervencion quirurgica pendiente en el servicio <b>${intervencion_quirurgica.consultorio}</b> con fecha de solicitud <b>${intervencion_quirurgica.fecha_solicitud}</b></p></div>`,
        confirmButtonText: "ENTENDIDO",
      });
    }
  } 

  $('#myModalAtencion').on('shown.bs.modal', function () {
    $('[data-toggle="tooltip"]', this).tooltip({ container: 'body' });
  });

  addAsteriskToLabel();
  showAlertExistsIntervencion();

  function addAsteriskToLabel() {
    for (const control of formProgramacionQuirurgica) {
        if (control.required) {
          const label = $('label[for="' + control.name + '"]');
          const labelText = label.text();
          if (!labelText.includes('*')) {
              label.html(labelText + ' <span style="color:red;">*</span>');
          }
        }
    }
  }

  $('#toogleProgramacionQuirurgica_pq').on('click', function () {
    const isPressed = $(this).attr("aria-pressed") === "false";
    $("#valueToogleProgramacionQuirurgica_pq").val(isPressed ? "1" : "0");
    const fieldFechaProbablePq = formProgramacionQuirurgica.find(f => f.name === 'fecha_probable_pq');
    if (isPressed) {
      $("#panelProgramacionQuirurgica_pq").slideDown("slow");
      if (!isFirstActiveToogle) {
        isFirstActiveToogle = true;
        if (fieldFechaProbablePq) fieldFechaProbablePq.required = true;
        main();
        $("#fecha_probable_pq").val("");
      }
    } else {
      $("#panelProgramacionQuirurgica_pq").slideUp("slow");
      if (fieldFechaProbablePq) fieldFechaProbablePq.required = false;
      $("#fecha_probable_pq").val("");
      $("#btnPrestacion").prop("disabled", false);
    }
  });

  $('#toogleInterconsultaPreQuirurgica_pq').on('click', function () {
    const isPressed = $(this).attr("aria-pressed") === "false";
    $("#valueToogleInterconsultaPreQuirurgica_pq").val(isPressed ? "1" : "0");
    $("#panelInterconsultaPreQuirurgica_pq").slideUp("slow");
    if (isPressed) {
      $("#panelInterconsultaPreQuirurgica_pq").slideDown("slow");
    } 
  });

  $('#toogleRequiereInternPreQx_pq').on('click', function () {
    const isPressed = $(this).attr("aria-pressed") === "false";
    $("#valueToogleRequiereInternPreQx_pq").val(isPressed ? "1" : "0");
    $("#formControlTiempoInternamiento").hide();
    if (isPressed) {
      $("#formControlTiempoInternamiento").show();
    }
  });

  $('#toogleDiasHospitPostQx_pq').on('click', function () {
    const isPressed = $(this).attr("aria-pressed") === "false";
    $("#valueToogleDiasHospitPostQx_pq").val(isPressed ? "1" : "0");
    $("#formControlTiempoHospitalizacion").hide();
    if (isPressed) {
      $("#formControlTiempoHospitalizacion").show();
    }
  });

  // $("#btnSaveAtencion").off("click"); 
  // $("#btnSaveAtencion").on("click", function (e) {
  //   e.preventDefault();
  //   onSubmitForm();
  // });

  $("#btnAddRowTableCirujanoIntOper").on("click", function () {
    onClickAddRowTableCirujanosIntOperPq();
  });

  $("#btnAddRowTableProcedure_pq").on("click", function () {
    onClickAddRowTableProcedimientosPq();
  });

  $("#btnAddRowTableInterconsultaPreQx_pq").on("click", function () {
    onClickAddRowTableInterconsultaPreQuirurgicaPq();
  });

  $('#fileMopri_pq').on('change', function () {
    const files = Array.from(this.files);
    const $list = $('#fileList_pq');
    $list.empty(); // limpiar lista previa

    if (files.length === 0) return;

    files.forEach((file, index) => {
      const name = file.name.toLowerCase();

      const isPdf = file.type === "application/pdf" || name.endsWith(".pdf");
      const isImage = file.type.startsWith("image/");
      
      if (!isPdf && !isImage) {
        Swal.fire({
          icon: "error",
          title: "Archivo no permitido",
          html: `<p>El archivo <b>"${file.name}"</b> no es PDF ni imagen.<br>Se ignorará.</p>`,
          confirmButtonText: "Entendido"
        });
        return;
      }

      const li = `
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span>${file.name}</span>
          <button type="button" class="btn btn-link text-danger btn-sm remove-file" data-index="${index}" data-toggle="tooltip" title="Quitar">
            <i class="fa fa-trash"></i>
          </button>
        </li>
      `;

      $list.append(li);
    });

    $list.on("click", ".remove-file", function () {
      const index = $(this).data("index");
      files.splice(index, 1);
      $(this).closest("li").remove();
    });

    // Reactivar tooltips dentro de la lista
    $('[data-toggle="tooltip"]').tooltip();
});

  $('#btnMopriQx_pq').on('click', function() {
    $('#fileMopri_pq').click();
  });

  $("#hora_inicio_pq").on("change", function () {
    const horaInicio = $(this).val();
    const horaInicioMoment = moment(horaInicio, "HH:mm");
    
    const horaFin = $("#hora_fin_pq").val();
    const horaFinMoment = moment(horaFin, "HH:mm");
    
    if (horaInicio && horaFin && horaInicioMoment.isValid() && horaFinMoment.isValid()) {
      if (horaFin && horaFinMoment.isBefore(horaInicioMoment)) {
        Swal.fire({
          icon: "warning",
          title: '<p style="font-size: 15px">PROGRAMACIÓN QUIRÚRGICA</p>',
          html: '<p class="descripcion-alert" style="font-size: 12px">LA HORA DE INICIO NO PUEDE SER MAYOR A HORA DE FIN</p>', 
        });
        $("#hora_fin_pq").val("");
        $("#tiempo_curacion_cqx").val("");
        return
      }
      const diferenciaMin = horaFinMoment.diff(horaInicioMoment, "minutes");
      // const diffHoras = Math.floor(diferenciaMin / 60);
      // const diffMinutos = diferenciaMin % 60;
      // $("#tiempo_curacion_cqx").val(`${diffHoras}:${diffMinutos}`);
      $("#tiempo_curacion_cqx").val(diferenciaMin);
    }
  });

  $("#hora_fin_pq").on("change", function () {
    const horaFin = $(this).val();
    const horaFinMoment = moment(horaFin, "HH:mm");

    const horaInicio = $("#hora_inicio_pq").val();
    const horaInicioMoment = moment(horaInicio, "HH:mm");

    if (horaInicio && horaFin && horaInicioMoment.isValid() && horaFinMoment.isValid()) {
      if (horaInicio && horaFinMoment.isBefore(horaInicioMoment)) {
        Swal.fire({
          icon: "warning",
          title: '<p style="font-size: 15px">PROGRAMACIÓN QUIRÚRGICA</p>',
          html: '<p class="descripcion-alert" style="font-size: 12px">LA HORA DE FIN NO PUEDE SER MENOR A HORA DE INICIO</p>', 
        });
        $("#hora_inicio_pq").val("");
        $("#tiempo_curacion_cqx").val("");
        return
      }

      const diferenciaMin = horaFinMoment.diff(horaInicioMoment, "minutes");
      // const diffHoras = Math.floor(diferenciaMin / 60);
      // const diffMinutos = diferenciaMin % 60;
      // $("#tiempo_curacion_cqx").val(`${diffHoras}:${diffMinutos}`);
      $("#tiempo_curacion_cqx").val(diferenciaMin);
    }
  });

  function main() {
    Swal.fire({
      title: '<p style="font-size: 15px; margin-top: 20px">CARGANDO DATOS...</p>',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });
    loadAllData()
      .then(([cqxProfesionales, cqxTipoProfessionales, cqxDias]) => {
        console.log("[cqxProfesionales] =>", cqxProfesionales);
        if (cqxDias && cqxDias.data) {
          builOptionsSelectIdTiempoInternamientoPreqx(cqxDias.data);
          builOptionsSelectIdTiempoHospitalizacionPostqx(cqxDias.data);
        }
        if (cqxProfesionales && cqxProfesionales.data && cqxProfesionales.data.length > 0) {
          listCqxProfesionales = cqxProfesionales.data;
        }
        if (cqxTipoProfessionales && cqxTipoProfessionales.data && cqxTipoProfessionales.data.length > 0) {
          listCqxTipoProfessionales = cqxTipoProfessionales.data;
        }
        Swal.close();
      })
      .catch(err => {
        console.error("Error en alguna petición:", err);
        Swal.close();
        Swal.fire({
          icon: 'error',
          title: 'Error al cargar los datos',
          text: err.responseText || 'Ocurrió un error'
        });
      });
  }

  function loadAllData() {
    return Promise.all([
      Promise.resolve(getCqxProfesionales()),
      Promise.resolve(getCqxTipoProfessionales()),
      Promise.resolve(getCqxDias())
    ]);
  }

  function getCqxProfesionales() {
    const id_establecimiento = $("#id_establecimiento").val();
    const id_consultorio = $("#id_consultorio").val();
    return $.ajax({
        url: `/api/cqx_profesionales/establecimiento/${id_establecimiento}/consultorio/${id_consultorio}`,
        method: "GET",
        dataType: "json"
    });
  }

  function getCqxTipoProfessionales() {
    return $.ajax({
        url: `/api/cqx_tipo_professionales`,
        method: "GET",
        dataType: "json"
    });
  }

  function getCqxDias() {
    return $.ajax({
        url: `/api/cqx_dias`,
        method: "GET",
        dataType: "json"
    });
  }

  function builOptionsSelectIdTiempoInternamientoPreqx(listCqxDias = []) {
    let $select = $("#id_tiempo_internamiento_preqx");
    $select.empty();
    if (listCqxDias && listCqxDias.length > 0) {
      $.each(listCqxDias, function(index, item) {
        if (parseInt(item.flag) === 2) {
          $select.append($("<option>", { value: item.id, text: item.descripcion })); 
        }
      });
    }
  }

  function builOptionsSelectIdTiempoHospitalizacionPostqx(listCqxDias = []) {
    let $select = $("#id_tiempo_hospitalizacion_postqx");
    $select.empty();
    if (listCqxDias && listCqxDias.length > 0) {
      $.each(listCqxDias, function(index, item) {
        if (parseInt(item.flag) === 3) {
          $select.append(
            $("<option>", { value: item.id, text: item.descripcion })
          ); 
        }
      });
    }
  }

  function onClickAddRowTableCirujanosIntOperPq() {
    const lastRowValid = validateLastRowTablePq("tableCirujanoIntOper");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p style="font-size: 15px">CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN</p>',
        html: '<p class="descripcion-alert" style="font-size: 12px">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO CIRUJANO</p>', 
      });
      return null;
    }
    const indexRow = $('#tableCirujanoIntOper tbody tr').length + 1;
    let options = "";
    listCqxTipoProfessionales.forEach(opcion => {
      options += `<option value="${opcion.id}">${opcion.descripcion}</option>`;
    });

    let optionsProfesionales = "<option value='' selected>--- Seleccionar ---</option>";

    listCqxProfesionales.forEach(opcionProfesional => {
      optionsProfesionales += `<option value="${opcionProfesional.id}">${opcionProfesional.paterno} ${opcionProfesional.materno}, ${opcionProfesional.nombre}</option>`;
    });

    const newRow = `
      <tr id="tr_cirujanos_intervienen_operacion_${indexRow}">
          <td>
            <select class="form-control input-sm" name="cqx_profesional[]" id="cqx_profesional_${indexRow}">${optionsProfesionales}</select>
          </td>
          <td>
            <select class="form-control input-sm" name="cqx_tipo_professional[]" id="cqx_tipo_professional_${indexRow}">${options}</select>
          </td>
          <td><button id="btnRemoveRowCirujano" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
      </tr>`;

    $('#tableCirujanoIntOper tbody').append(newRow);
    $("#tableCirujanoIntOperHeader").removeClass("table-error danger").nextAll('.label-help').remove();
    $(`#cqx_profesional_${indexRow}`).select2({ width: '100%' });

    $('#tableCirujanoIntOper').on('click', '#btnRemoveRowCirujano', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      formProgramacionQuirurgica = formProgramacionQuirurgica.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    formProgramacionQuirurgica.push({id: indexRow, name: `cqx_profesional_${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Seleccione el profesional que interviene en la operación" });
    formProgramacionQuirurgica.push({id: indexRow, name: `cqx_tipo_professional_${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Seleccione el tipo de profesional que interviene en la operación" });
  }

  function onClickAddRowTableProcedimientosPq() {
    const lastRowValid = validateLastRowTablePq("tableProcedures_pq");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p style="font-size: 15px">PROCEDIMIENTOS DE INTERVENCIÓN</p>',
        html: '<p class="descripcion-alert" style="font-size: 12px">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO PROCEDIMIENTO</p>', 
      });
      return null;
    }
    const indexRow = $('#tableProcedures_pq tbody tr').length + 1;

    const newRow = `
      <tr id="tr_procedimientos_${indexRow}">
          <td><input type="text" name="cqx_procedimiento_nombre_pq[]" id="cqx_procedimiento_nombre_pq${indexRow}" class="form-control input-sm"></td>
          <td><button id="btnRemoveRowProcedure_pq" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
          <td><input type="hidden" name="cqx_procedure_id_pq[]" id="cqx_procedure_id_pq_${indexRow}"></td>
      </tr>`;

    $('#tableProcedures_pq tbody').append(newRow);
    $("#tableProceduresHeader_pq").removeClass("table-error danger").nextAll('.label-help').remove();

    const sexo = String($("#nomsexo").val()).toUpperCase() === "M" ? 1 : 2;
    const id_establecimiento = $("#id_establecimiento").val() || "76";

    $(`#cqx_procedimiento_nombre_pq${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          url: `/obtener_procedimiento_convenio/${request.term}/${id_establecimiento}/${sexo}/${1}`,
          type: "GET",
          dataType: "json",
          success: function(data)  {
            console.log("[data] =>", data);
            response(data.map(function(item) {
              return { id: item.id, label: item.label};
            }));
          },
          error: function() {
            response([]);
          }			
        })
      },
      select: function(event, ui) {
        let idItem = ui.item.id;
        $(this).val(ui.item.label);
        $(`#cqx_procedure_id_pq_${indexRow}`).val(idItem);
      }
    });

    $(`#cqx_procedimiento_nombre_pq${indexRow}`).on('input', function() {
      $(`#cqx_procedure_id_pq_${indexRow}`).val("");
    });

    $(`#cqx_procedimiento_nombre_pq${indexRow}`).blur(function() {
      const id_procedimiento = $(`#cqx_procedure_id_pq_${indexRow}`).val();
      clearInputError($(this).attr("id"))
      if (!id_procedimiento) {
        markInputError($(this).attr("id"), "espere los resultados y seleccione un procedimiento");
      }
    });

    $('#tableProcedures_pq').on('click', '#btnRemoveRowProcedure_pq', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      formProgramacionQuirurgica = formProgramacionQuirurgica.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    formProgramacionQuirurgica.push({id: indexRow, name: `cqx_procedimiento_nombre_pq${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, panelId: "valueToogleProgramacionQuirurgica_pq", message: "Ingrese el nombre del procedimiento en programación quirúrgica" });
  }

  function onClickAddRowTableInterconsultaPreQuirurgicaPq() {
    const cqx_consultorios = window.consultorios || [];
    const lastRowValid = validateLastRowTablePq("tableInterconsultaPreQx_pq");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p style="font-size: 15px">INTERCONSULTA</p>',
        html: '<p class="descripcion-alert" style="font-size: 12px">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UNA NUEVA INTERCONSULTA</p>', 
      });
      return null;
    }
    const indexRow = $('#tableInterconsultaPreQx_pq tbody tr').length + 1;
    let options = "";
    cqx_consultorios.forEach(opcion => {
      options += `<option value="${opcion.id}">${opcion.nombre}</option>`;
    });

    const newRow = `
      <tr id="tr_cirujanos_intervienen_operacion_${indexRow}">
          <td>
            <select class="form-control input-sm" name="preqx_id_servicio_pq[]" id="preqx_id_servicio_pq_${indexRow}">${options}</select>
          </td>
          <td>
            <input type="text" class="form-control" name="preqx_motivo_interconsulta_pq[]" id="preqx_motivo_interconsulta_${indexRow}">
          </td>
          <td><button id="btnRemoveRowTablePreQx_pq" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
      </tr>`;

    $('#tableInterconsultaPreQx_pq tbody').append(newRow);
    $("#tableInterconsultaPreQxHead_pq").removeClass("table-error danger").nextAll('.label-help').remove();
    $(`#preqx_id_servicio_pq_${indexRow}`).select2({ width: '100%' });

    $('#tableInterconsultaPreQx_pq').on('click', '#btnRemoveRowTablePreQx_pq', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      formProgramacionQuirurgica = formProgramacionQuirurgica.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    formProgramacionQuirurgica.push({id: indexRow, name: `preqx_id_servicio_pq_${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, panelId: "valueToogleInterconsultaPreQuirurgica_pq", message: "Seleccione el servicio en interconsulta prequirúrgica en programación quirúrgica" });
    formProgramacionQuirurgica.push({id: indexRow, name: `preqx_motivo_interconsulta_${indexRow}`, required: false, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, panelId: "valueToogleInterconsultaPreQuirurgica_pq", message: "Ingrese el motivo de la interconsulta en programación quirúrgica en programación quirúrgica" });
  }

  function validateLastRowTablePq(tableId) {
    let lastRow = $(`#${tableId} tbody tr:last`);
    let isValid = true;
    lastRow.find('input:visible').each(function() {
      clearInputError($(this).attr("id"));
      if ($(this).val().trim() === '') {
        markInputError($(this).attr("id"));
        isValid = false;
      }
    });
    lastRow.find('select:visible').each(function() {
      clearInputError($(this).attr("id"));
      if ($(this).val() === '') {
          markInputError($(this).attr("id"));
          isValid = false;
      }
    });
    return isValid; 
  }

  function clearInputError(nameInput) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest('.form-group, td');
    inputElement.siblings('.form-control-feedback, .label-help').remove();
    parentDiv.removeClass("has-error has-success");
  }

  function markInputError(nameInput, messsageError = null) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest('.form-group, td' );
    parentDiv.addClass("has-error");
    inputElement.after(`<span class="label-help">${messsageError || "campo requerido"}</span>`);
  }

  // function onSubmitForm() {
  //   const formValid = validateForm();
  //   if (!formValid) {
  //     Swal.fire({
  //       icon: "error",
  //       title: "<p style='font-size: 15px'>DETALLE DE ATENCION</p>",
  //       html: `<div style='margin: 10px 0px'>
  //               <p style='font-size: 12px'>Faltan campos obligatorios. Asegúrese de llenar aquellos marcados con (*) para continuar.</p>
  //             </div>`,
  //       showLoaderOnConfirm: false,
  //       confirmButtonText: "ENTENDIDO"
  //     });
  //     return null;
  //   }
  //   const formData = buildFormDataPq();
  //   $.ajax({
  //     type: "POST",
  //     url: "/api/consulta-externa/atencion-adicional/intervencion-quirurgica",
  //     data: formData,
  //     processData: false,
  //     contentType: false,
  //     beforeSend: function () {
  //       $("#btnSaveAtencion").prop('disabled', true);
  //       Swal.fire({
  //         html: '<h5>GUARDANDO INFORMACION...</h5>',
  //         showLoaderOnConfirm: true,
  //         onOpen: () => {
  //             Swal.showLoading();
  //         }
  //       });
  //     },
  //   })
  //   .done(function (response) {
  //     Swal.close();
  //     if (response && response.success) {
  //       Swal.hideLoading();
  //       $('#myModalAtencion').modal('hide');
  //       const message = response.message || "Orden registrada exitosamente.";
  //       Swal.fire({
  //         icon: 'success',
  //         title: 'DETALLE DE ATENCION',
  //         text: message,
  //         confirmButtonText: "ENTENDIDO",
  //       });
  //     }
  //   })
  //   .fail(function (jqXHR, textStatus, errorThrown) {
  //     Swal.hideLoading();
  //     $("#btnSaveAtencion").prop('disabled', false);
  //     const message = jqXHR.responseJSON.message || "Ocurrio un error al registrar, intentelo en unos minutos";
  //     Swal.fire({
  //       icon: 'error',
  //       title: "INTERVENCIÓN QUIRÚRGICA",
  //       html: `<p>${message}</p>`,
  //       confirmButtonText: "ENTENDIDO",
  //     });
  //   })
  //   .always(function () {
  //     $("#btnSaveAtencion").prop('disabled', false);
  //     Swal.hideLoading();
  //   });
  // }

  function validateFormProgramacionQuirurgicaPq() {
    let controlsError = [];
    let arrayMessageError = [];
    let isActiveToogle = 0;
    for (const control of formProgramacionQuirurgica) {
      if (control.required) {
        const inputElement = $(`#${control.name}`);
        isActiveToogle = parseInt($(`#${control.panelId}`).val(), 10) === 1 ? true : false;
        inputElement.nextAll('.label-help').remove();
        let parentDiv = null;
        if (control.tr_id) {
          parentDiv = inputElement.closest('td'); 
        } else {
          parentDiv = inputElement.closest('.form-group'); 
        }
        if (parentDiv && isActiveToogle) {
          if (inputElement.val() === "") {
            parentDiv.addClass('has-error');
            inputElement.after(`<span class="label-help">campo requerido</span>`);
            controlsError.push(control);
            arrayMessageError.push(control.message ? `${control.message}<br>` : null);
          } else if (control.minValue && parseFloat(inputElement.val()) <= 0) {
            parentDiv.addClass('has-error');
            inputElement.after(`<span class="label-help">Ingrese un número mayor a 0</span>`);
            controlsError.push(control);
            arrayMessageError.push(control.message ? `${control.message}. El valor debe ser mayor a 0<br>` : null);
          } else {
            parentDiv.removeClass('has-error');
            inputElement.nextAll('.label-help').remove();
          }
        }
      }
    }

    $("#tableCirujanoIntOperHeader").removeClass("table-error danger").nextAll('.label-help').remove();
    $("#tableProceduresHeader_pq").removeClass("table-error danger").nextAll('.label-help').remove();
    $("#tableInterconsultaPreQxHead_pq").removeClass("table-error danger").nextAll('.label-help').remove();

    const valueToogleProgramacionQuirurgica_pq = parseInt($("#valueToogleProgramacionQuirurgica_pq").val(), 10) ? true : false;
    if (valueToogleProgramacionQuirurgica_pq) {
      const rowsTableCirujanoIntOper = $('#tableCirujanoIntOper tbody tr');
      if (rowsTableCirujanoIntOper.length === 0) {
        $("#tableCirujanoIntOperHeader").addClass("table-error danger").after(`<span class="label-help">Debe ingresar al menos un cirujano.</span>`);
        controlsError.push({ name: "tableCirujanoIntOper" });
        arrayMessageError.push("Debe ingresar al menos un cirujano en programación quirúrgica.<br>");
      } else {
        $("#tableCirujanoIntOperHeader").removeClass("table-error danger").nextAll('.label-help').remove();
      }

      const rowsTableProcedures= $('#tableProcedures_pq tbody tr');
      if (rowsTableProcedures.length === 0) {
        $("#tableProceduresHeader_pq").addClass("table-error danger").after(`<span class="label-help">Debe ingresar al menos un procedimiento.</span>`);
        controlsError.push({ name: "tableProcedures_pq" });
        arrayMessageError.push("Debe ingresar al menos un procedimiento en programación quirúrgica.<br>");
      } else {
        $("#tableProceduresHeader_pq").removeClass("table-error danger").nextAll('.label-help').remove();
      } 

      const valueToogleInterconsultaPreQuirurgica_pq = parseInt($("#valueToogleInterconsultaPreQuirurgica_pq").val(), 10) ? true : false;
      if (valueToogleInterconsultaPreQuirurgica_pq) {
        const rowsTableInterconsultaPreQx = $('#tableInterconsultaPreQx_pq tbody tr');
        if (rowsTableInterconsultaPreQx.length === 0) {
          $("#tableInterconsultaPreQxHead_pq").addClass("table-error danger").after(`<span class="label-help">Debe ingresar al menos un cirujano.</span>`);
          controlsError.push({ name: "tableInterconsultaPreQx_pq" });
          arrayMessageError.push("Debe ingresar al menos una interconsulta en programación quirúrgica.<br>");
        } else {
          $("#tableInterconsultaPreQxHead_pq").removeClass("table-error danger").nextAll('.label-help').remove();
        }
      }
    }

    return arrayMessageError;
  }

  function buildFormDataPq() {
    const formData = new FormData();

    // const cirujanos = getValuesRowByTablePq("tableCirujanoIntOper") || [];
    const procedimientos = getValuesRowByTablePq("tableProcedures_pq") || [];
    const interconsultas = getValuesRowByTablePq("tableInterconsultaPreQx_pq") || [];

    // formData.append("rqCardiologia", $("#rq_cardiologia_aa").is(':checked') ? 1 : 0);
    // formData.append("rqNeumologia", $("#rq_neumologia_aa").is(':checked') ? 1 : 0);
    // formData.append("rqAnestesiologia", $("#rq_anestesiologia_aa").is(':checked') ? 1 : 0);

    formData.append("isActiveIntervencionQuirurgica", $("#valueToogleProgramacionQuirurgica_pq").val() === "1" ? 1 : 0);
    formData.append("isActiveInterconsultaPreQuirurgica", $("#valueToogleInterconsultaPreQuirurgica_pq").val() === "1" ? 1 : 0);
    // formData.append("requiereInternPreQx", $("#valueToogleRequiereInternPreQx_pq").val() === "1" ? 1 : 0);
    // formData.append("requiereHospitalizacionPostQx", $("#valueToogleDiasHospitPostQx_pq").val() === "1" ? 1 : 0);

    // formData.append("idTiempoInternamientoPreqx", $("#id_tiempo_internamiento_preqx").val() || null);
    // formData.append("idTiempoHospitalizacionPostqx", $("#id_tiempo_hospitalizacion_postqx").val() || null);
    // formData.append("idLugarOperacion", $("#id_lugar_operacion").val() || null);
    // formData.append("tiempoCuracionCqx", $("#tiempo_curacion_cqx").val() || null);

    formData.append("idServicio", $("#id_servicio_aa").val() || null);
    formData.append("idPrestacion", $("#id_prestacion_aa").val() || null);
    formData.append("idCita", $("#id_cita_aa").val() || null);
    // formData.append("idCqCita", $("#id_cq_cita_aa").val() || null);
    // formData.append("idCqxIntervenciones", $("#id_cqx_intervenciones_aa").val() || null);
    formData.append("idAsegurado", $("#id_asegurado_aa").val() || null);

    // formData.append("cirujanos", JSON.stringify(cirujanos));
    formData.append("procedimientos", JSON.stringify(procedimientos));
    formData.append("interconsultas", JSON.stringify(interconsultas));

    let files = $('#fileMopri_pq')[0].files;
    for (let i = 0; i < files.length; i++) {
      formData.append("fileMopri_pq[]", files[i]);
    }
    
    return formData;
  }

  function getValuesRowByTablePq(tableName) {
    const rows = $(`#${tableName} tbody tr`);
    const resultados  = [];
    rows.each(function(index, fila) {
        let valores = {};
        $(fila).find('input, select').each(function() {
          let name = $(this).attr('name') || "";
          name = name.replace(/\[\]$/, "");
          valores[name] = $(this).val();
            // valores[$(this).attr('name')] = $(this).val();
        });
        resultados.push(valores);
    });
    return resultados ;
  }
  function alertaFechaProgramacion() {
    return new Promise(function(resolve, reject) {
        var fecha = $('#fecha_probable_pq').val();
        if (!fecha) {
            Swal.fire({
                title: "Fecha no válida",
                text: "Por favor, seleccione una fecha antes de continuar.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            resolve(false);
            return;
        }
        Swal.fire({
            title: "Confirmación de Programación",
            html: "Está a punto de registrar la programación con la siguiente fecha:<br><br><strong>" + fecha + "</strong><br><br>¿Desea continuar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, continuar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            resolve(result.isConfirmed);
        });
    });
  }
// });

  $("#fecha_probable_pq").on("change", function () {

      const isPressed = $("#toogleProgramacionQuirurgica_pq").attr("aria-pressed") === "true";

      if (!isPressed) {
          $("#btnPrestacion").prop("disabled", false);
          return;
      }

      let fechaInput = $(this).val();
      let btn = $("#btnPrestacion");

      if (!fechaInput) {
          btn.prop("disabled", false);
          return;
      }
      // convertir dd-mm-yyyy
      let p = fechaInput.split("-");
      let fechaSeleccionada = new Date(`${p[2]}-${p[1]}-${p[0]}T00:00:00`);

      let ahora = new Date();

      // ==== CALCULAR RANGO DE 24 HORAS DESDE HOY 12:30 PM ====
      let limite24hInicio = new Date();
      limite24hInicio.setHours(12, 30, 0, 0); // hoy 12:30 PM

      // Si aún no son las 12:30 PM, no bloquear nada
      if (ahora < limite24hInicio) {
          btn.prop("disabled", false);
          return;
      }

      let limite24hFin = new Date(limite24hInicio.getTime() + 24 * 60 * 60 * 1000); // 24 horas después

      // Validar si la fecha seleccionada cae dentro de las próximas 24 horas
      if (fechaSeleccionada >= limite24hInicio && fechaSeleccionada <= limite24hFin) {

          bootbox.alert({
              message: `
                    <div style="color:#b30000; font-weight:bold; font-size:16px; text-align:center;">
                      <i class="fa fa-exclamation-triangle" style="font-size:22px; margin-right:8px;"></i>
                      Programación fuera del horario.<br>
                      <strong>No se puede programar después de las 12:30 PM dentro de las próximas 24 horas.</strong><br>
                      Coordinar con anestesiología.
                  </div>
              `,
              size: "small"
          });

          btn.prop("disabled", true);
          return;
      }
      btn.prop("disabled", false);

  });