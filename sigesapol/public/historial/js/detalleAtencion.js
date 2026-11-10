$(document).ready(function () {
  let form = [
    // { id: 1, name: 'id_tiempo_internamiento_preqx', required: true, panelId: "valueToogleProgramacionQuirurgica" },
    // { id: 2, name: 'id_tiempo_hospitalizacion_postqx', required: true, panelId: "valueToogleProgramacionQuirurgica" },
    // { id: 3, name: 'id_lugar_operacion', required: true, panelId: "valueToogleProgramacionQuirurgica" },
    // { id: 4, name: 'tiempo_curacion_cqx', required: true, panelId: "valueToogleProgramacionQuirurgica" },
  ];

  main();

  async function main() {
    const showFormDetailIntervencionQuirurgica = $("#showFormDetailIntervencionQuirurgica").val();
    if (showFormDetailIntervencionQuirurgica && parseInt(showFormDetailIntervencionQuirurgica) === 1) {
      $("#rq_cardiologia_aa").prop("checked", false).on("click", (e) => e.preventDefault());
      $("#rq_neumologia_aa").prop("checked", false).on("click", (e) => e.preventDefault());
      $("#rq_anestesiologia_aa").prop("checked", false).on("click", (e) => e.preventDefault());
      const intervencion_quirurgica = window.intervencion_quirurgica || null;
      if (intervencion_quirurgica && intervencion_quirurgica.id) {
        const cqxIntervencion = await getIntervencionById(intervencion_quirurgica.id);
        buildDataFormDetail(cqxIntervencion.data);
      }
    }
  }

  function buildDataFormDetail(cqxIntervencion) {
    if (cqxIntervencion) {
      if (cqxIntervencion.cqxCitas && cqxIntervencion.cqxCitas.length > 0) {
        cqxIntervencion.cqxCitas.forEach(cqxCita => {
          if (parseInt(cqxCita.id_consultorio) === 63) {
            $("#rq_neumologia_aa").prop("checked", true);
          }
          if (parseInt(cqxCita.id_consultorio) === 72) {
            $("#rq_anestesiologia_aa").prop("checked", true);
          }
          if (parseInt(cqxCita.id_consultorio) === 27) {
            $("#rq_cardiologia_aa").prop("checked", true);
          }
        });
      }
      if (cqxIntervencion.cqxProcedimientos && cqxIntervencion.cqxProcedimientos.length > 0) {
        let newRowTableProceduresHIq = "";
        cqxIntervencion.cqxProcedimientos.forEach((cqxProcedimiento) => {
          newRowTableProceduresHIq = `<tr>
                <td><input type="text" class="form-control input-sm" value="${cqxProcedimiento.procedimiento}" readonly></td>
                <td></td>
              </tr>`;
          $('#tableProcedures_h_iq tbody').append(newRowTableProceduresHIq);
        });
      }
      if (cqxIntervencion.cqxFormatoEscaneados && cqxIntervencion.cqxFormatoEscaneados.length > 0) {
        const listFormatoEscaneados = $('#fileList');
        listFormatoEscaneados.empty();
        let newRowCqxFormatoEscaneados = "";
        cqxIntervencion.cqxFormatoEscaneados.forEach((cqxFormatoEscaneado) => {
          newRowCqxFormatoEscaneados = `<li class="list-group-item d-flex justify-content-between align-items-center">
                                          <span>${cqxFormatoEscaneado.nombre_simple}</span>
                                          <a href="/${cqxFormatoEscaneado.ruta}${cqxFormatoEscaneado.nombre_archivo}" 
                                            class="btn btn-link text-danger btn-sm" 
                                            data-toggle="tooltip" 
                                            title="Descargar" 
                                            target="_blank">
                                            <i class="fa fa-download" aria-hidden="true"></i>
                                          </a>
                                        </li>`;
          listFormatoEscaneados.append(newRowCqxFormatoEscaneados);
        });
      }
      if (cqxIntervencion.interconsultas && cqxIntervencion.interconsultas.length > 0) {
        $("#toogleInterconsultaPreQuirurgica").addClass("active");
        $("#panelInterconsultaPreQuirurgica").slideDown("slow");
        $("#toogleInterconsultaPreQuirurgica").prop("disabled", true);
        $("#valueToogleInterconsultaPreQuirurgica").val("0");

        $("#btnAddRowTableInterconsultaPreQx").hide();

        let newRowInterconsultas = "";
        cqxIntervencion.interconsultas.forEach((interconsulta) => {
          newRowInterconsultas = `<tr>
                <td><input type="text" class="form-control input-sm" value="${interconsulta.consultorio}" readonly></td>
                <td><input type="text" class="form-control input-sm" value="${interconsulta.motivo_interconsulta}" readonly></td>
                <td></td>
              </tr>`;
          $('#tableInterconsultaPreQx tbody').append(newRowInterconsultas);
        });
      }
    }
  }

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

  $('#myModalAtencion1').on('shown.bs.modal', function () {
    $('[data-toggle="tooltip"]', this).tooltip({ container: 'body' });
  });

  addAsteriskToLabel();
  showAlertExistsIntervencion();

  function addAsteriskToLabel() {
    for (const control of form) {
        if (control.required) {
          const label = $('label[for="' + control.name + '"]');
          const labelText = label.text();
          if (!labelText.includes('*')) {
              label.html(labelText + ' <span style="color:red;">*</span>');
          }
        }
    }
  }

  $('#toogleProgramacionQuirurgica').on('click', function () {
    const isPressed = $(this).attr("aria-pressed") === "false";
    $("#valueToogleProgramacionQuirurgica").val(isPressed ? "1" : "0");
    $("#panelProgramacionQuirurgica").slideUp("slow");
    if (isPressed) {
      $("#panelProgramacionQuirurgica").slideDown("slow");
    } 
  });

  $('#toogleInterconsultaPreQuirurgica').on('click', function () {
    const isPressed = $(this).attr("aria-pressed") === "false";
    $("#valueToogleInterconsultaPreQuirurgica").val(isPressed ? "1" : "0");
    $("#panelInterconsultaPreQuirurgica").slideUp("slow");
    if (isPressed) {
      $("#panelInterconsultaPreQuirurgica").slideDown("slow");
    } 
  });

  $('#toogleRequiereInternPreQx').on('click', function () {
    const isPressed = $(this).attr("aria-pressed") === "false";
    $("#valueToogleRequiereInternPreQx").val(isPressed ? "1" : "0");
  });

  $('#toogleDiasHospitPostQx').on('click', function () {
    const isPressed = $(this).attr("aria-pressed") === "false";
    $("#valueToogleDiasHospitPostQx").val(isPressed ? "1" : "0");
  });

  $("#btnSaveAtencion").off("click"); 
  $("#btnSaveAtencion").on("click", function (e) {
    e.preventDefault();
    onSubmitForm();
  });

  // $("#btnAddRowTableCirujanoIntOper").on("click", function () {
  //   onClickAddRowTableCirujanosIntOper();
  // });

  $("#btnAddRowTableProcedure_h_iq").on("click", function () {
    onClickAddRowTableProcedimientos();
  });

  $("#btnAddRowTableInterconsultaPreQx").on("click", function () {
    onClickAddRowTableInterconsultaPreQuirurgica();
  });

  $('#fileMopri').on('change', function () {
    const files = Array.from(this.files);
    const $list = $('#fileList');
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

  $('#btnMopriQx').on('click', function() {
    $('#fileMopri').click();
  });

  // function onClickAddRowTableCirujanosIntOper() {
  //   const cqx_tipo_professionales = window.cqx_tipo_professionales || [];
  //   const cqx_profesionales = window.cqx_profesionales || [];
  //   const lastRowValid = validateLastRowTable("tableCirujanoIntOper");
  //   if (!lastRowValid) {
  //     Swal.fire({
  //       icon: "warning",
  //       title: '<p style="font-size: 15px">CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN</p>',
  //       html: '<p class="descripcion-alert" style="font-size: 12px">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO CIRUJANO</p>', 
  //     });
  //     return null;
  //   }
  //   const indexRow = $('#tableCirujanoIntOper tbody tr').length + 1;
  //   let options = "";
  //   cqx_tipo_professionales.forEach(opcion => {
  //     options += `<option value="${opcion.id}">${opcion.descripcion}</option>`;
  //   });

  //   let optionsProfesionales = "<option value='' selected>--- Seleccionar ---</option>";

  //   cqx_profesionales.forEach(opcionProfesional => {
  //     optionsProfesionales += `<option value="${opcionProfesional.id}">${opcionProfesional.nombre}, ${opcionProfesional.paterno} ${opcionProfesional.materno}</option>`;
  //   });

  //   const newRow = `
  //     <tr id="tr_cirujanos_intervienen_operacion_${indexRow}">
  //         <td>
  //           <select class="form-control input-sm" name="cqx_profesional[]" id="cqx_profesional_${indexRow}">${optionsProfesionales}</select>
  //         </td>
  //         <td>
  //           <select class="form-control input-sm" name="cqx_tipo_professional[]" id="cqx_tipo_professional_${indexRow}">${options}</select>
  //         </td>
  //         <td><button id="btnRemoveRowCirujano" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
  //     </tr>`;

  //   $('#tableCirujanoIntOper tbody').append(newRow);
  //   $("#tableCirujanoIntOperHeader").removeClass("table-error danger").nextAll('.label-help').remove();
  //   $(`#cqx_profesional_${indexRow}`).select2({ width: '100%' });

  //   $('#tableCirujanoIntOper').on('click', '#btnRemoveRowCirujano', function(){
  //     const row = $(this).closest('tr');
  //     const rowId = row.attr('id');
  //     form = form.filter(obj => String(obj.tr_id) !== String(rowId));
  //     row.remove();
  //   });

  //   form.push({id: indexRow, name: `cqx_profesional_${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, panelId: "valueToogleProgramacionQuirurgica"});
  //   form.push({id: indexRow, name: `cqx_tipo_professional_${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, panelId: "valueToogleProgramacionQuirurgica"});
  // }

  function onClickAddRowTableProcedimientos() {
    const lastRowValid = validateLastRowTable("tableProcedures_h_iq");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p style="font-size: 15px">PROCEDIMIENTOS DE INTERVENCIÓN</p>',
        html: '<p class="descripcion-alert" style="font-size: 12px">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO PROCEDIMIENTO</p>', 
      });
      return null;
    }
    const indexRow = $('#tableProcedures_h_iq tbody tr').length + 1;

    const newRow = `
      <tr id="tr_procedimientos_${indexRow}">
          <td><input type="text" name="procedimiento_nombre_h_iq[]" id="procedimiento_nombre_h_iq${indexRow}" class="form-control input-sm"></td>
          <td><button id="btnRemoveRowProcedure_h_iq" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
          <td><input type="hidden" name="id_procedimiento_h_iq" id="procedure_id_h_iq${indexRow}"></td>
      </tr>`;

    $('#tableProcedures_h_iq tbody').append(newRow);
    $("#tableProceduresHeader_h_iq").removeClass("table-error danger").nextAll('.label-help').remove();

    const sexo = String($("#sexo").val()).toUpperCase() === "M" ? 1 : 2;
    const id_establecimiento = $("#id_establecimiento").val() || "76";

    $(`#procedimiento_nombre_h_iq${indexRow}`).autocomplete({
      appendTo: "#muestra-det-atencion1",
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          url: `/obtener_procedimiento_convenio/${request.term}/${id_establecimiento}/${sexo}/${1}`,
          type: "GET",
          dataType: "json",
          success: function(data)  {
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
        $(`#procedure_id_h_iq${indexRow}`).val(idItem);
      }
    });

    $(`#procedimiento_nombre_h_iq${indexRow}`).on('input', function() {
      $(`#procedure_id_h_iq${indexRow}`).val("");
    });

    $(`#procedimiento_nombre_h_iq${indexRow}`).blur(function() {
      const id_procedimiento_h_iq = $(`#procedure_id_h_iq${indexRow}`).val();
      clearInputError($(this).attr("id"))
      if (!id_procedimiento_h_iq) {
          markInputError($(this).attr("id"), "espere los resultados y seleccione un procedimiento");
      }
    });

    $('#tableProcedures_h_iq').on('click', '#btnRemoveRowProcedure_h_iq', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      // form = form.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });


    form.push({id: indexRow, name: `procedimiento_nombre_h_iq${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, panelId: "valueToogleProgramacionQuirurgica"});
  }

  function onClickAddRowTableInterconsultaPreQuirurgica() {
    const cqx_consultorios = window.consultorios || [];
    const lastRowValid = validateLastRowTable("tableInterconsultaPreQx");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p style="font-size: 15px">INTERCONSULTA</p>',
        html: '<p class="descripcion-alert" style="font-size: 12px">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UNA NUEVA INTERCONSULTA</p>', 
      });
      return null;
    }
    const indexRow = $('#tableInterconsultaPreQx tbody tr').length + 1;
    let options = "";
    cqx_consultorios.forEach(opcion => {
      options += `<option value="${opcion.id}">${opcion.nombre}</option>`;
    });

    const newRow = `
      <tr id="tr_cirujanos_intervienen_operacion_${indexRow}">
          <td>
            <select class="form-control input-sm" name="preqx_id_servicio[]" id="preqx_id_servicio_${indexRow}">${options}</select>
          </td>
          <td>
            <input type="text" class="form-control" name="preqx_motivo_interconsulta[]" id="preqx_motivo_interconsulta_${indexRow}">
          </td>
          <td><button id="btnRemoveRowTablePreQx" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
      </tr>`;

    $('#tableInterconsultaPreQx tbody').append(newRow);
    $("#tableInterconsultaPreQxHead").removeClass("table-error danger").nextAll('.label-help').remove();
    $(`#preqx_id_servicio_${indexRow}`).select2({ width: '100%' });

    $('#tableInterconsultaPreQx').on('click', '#btnRemoveRowTablePreQx', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      form = form.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    form.push({id: indexRow, name: `preqx_id_servicio_${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, panelId: "valueToogleInterconsultaPreQuirurgica"});
    form.push({id: indexRow, name: `preqx_motivo_interconsulta_${indexRow}`, required: false, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, panelId: "valueToogleInterconsultaPreQuirurgica"});
  }

  function validateLastRowTable(tableId) {
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

  function onSubmitForm() {
    const formValid = validateForm();
    if (!formValid) {
      Swal.fire({
        icon: "error",
        title: "<p style='font-size: 15px'>DETALLE DE ATENCION</p>",
        html: `<div style='margin: 10px 0px'>
                <p style='font-size: 12px'>Faltan campos obligatorios. Asegúrese de llenar aquellos marcados con (*) para continuar.</p>
              </div>`,
        showLoaderOnConfirm: false,
        confirmButtonText: "ENTENDIDO"
      });
      return null;
    }
    const formData = buildFormData();
    $.ajax({
      type: "POST",
      url: "/api/consulta-externa/atencion-adicional/intervencion-quirurgica",
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function () {
        $("#btnSaveAtencion").prop('disabled', true);
        Swal.fire({
          html: '<h5>GUARDANDO INFORMACION...</h5>',
          showLoaderOnConfirm: true,
          onOpen: () => {
              Swal.showLoading();
          }
        });
      },
    })
    .done(function (response) {
      Swal.close();
      if (response && response.success) {
        Swal.hideLoading();
        $('#myModalAtencion1').modal('hide');
        const message = response.message || "Orden registrada exitosamente.";
        Swal.fire({
          icon: 'success',
          title: 'DETALLE DE ATENCION',
          text: message,
          confirmButtonText: "ENTENDIDO",
        });
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.hideLoading();
      $("#btnSaveAtencion").prop('disabled', false);
      const message = jqXHR.responseJSON.message || "Ocurrio un error al registrar, intentelo en unos minutos";
      Swal.fire({
        icon: 'error',
        title: "INTERVENCIÓN QUIRÚRGICA",
        html: `<p>${message}</p>`,
        confirmButtonText: "ENTENDIDO",
      });
    })
    .always(function () {
      $("#btnSaveAtencion").prop('disabled', false);
      Swal.hideLoading();
    });
  }

  function validateForm() {
    let controlsError = [];
    let isActiveToogle = 0;
    for (const control of form) {
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
          } else {
            parentDiv.removeClass('has-error');
            inputElement.nextAll('.label-help').remove();
          }
        }
      }
    }

    // $("#tableCirujanoIntOperHeader").removeClass("table-error danger").nextAll('.label-help').remove();
    $("#tableProceduresHeader_h_iq").removeClass("table-error danger").nextAll('.label-help').remove();
    $("#tableInterconsultaPreQxHead").removeClass("table-error danger").nextAll('.label-help').remove();

    const valueToogleProgramacionQuirurgica = parseInt($("#valueToogleProgramacionQuirurgica").val(), 10) ? true : false;
    if (valueToogleProgramacionQuirurgica) {
      // const rowsTableCirujanoIntOper = $('#tableCirujanoIntOper tbody tr');
      // if (rowsTableCirujanoIntOper.length === 0) {
      //   $("#tableCirujanoIntOperHeader").addClass("table-error danger").after(`<span class="label-help">Debe ingresar al menos un cirujano.</span>`);
      //   controlsError.push({ name: "tabProcedimiendos" });
      // } else {
      //   $("#tableCirujanoIntOperHeader").removeClass("table-error danger").nextAll('.label-help').remove();
      // }

      const rowsTableProcedures= $('#tableProcedures_h_iq tbody tr');
      if (rowsTableProcedures.length === 0) {
        $("#tableProceduresHeader_h_iq").addClass("table-error danger").after(`<span class="label-help">Debe ingresar al menos un procedimiento.</span>`);
        controlsError.push({ name: "tableProcedures_h_iq" });
      } else {
        $("#tableProceduresHeader_h_iq").removeClass("table-error danger").nextAll('.label-help').remove();
      } 

      const valueToogleInterconsultaPreQuirurgica = parseInt($("#valueToogleInterconsultaPreQuirurgica").val(), 10) ? true : false;
      if (valueToogleInterconsultaPreQuirurgica) {
        const rowsTableInterconsultaPreQx = $('#tableInterconsultaPreQx tbody tr');
        if (rowsTableInterconsultaPreQx.length === 0) {
          $("#tableInterconsultaPreQxHead").addClass("table-error danger").after(`<span class="label-help">Debe ingresar al menos un cirujano.</span>`);
          controlsError.push({ name: "tableInterconsultaPreQx" });
        } else {
          $("#tableInterconsultaPreQxHead").removeClass("table-error danger").nextAll('.label-help').remove();
        }
      }
    }


    focusFirstInputError(controlsError);
    return controlsError.length === 0;
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

  function buildFormData() {
    const formData = new FormData();

    // const cirujanos = getValuesRowByTable("tableCirujanoIntOper") || [];
    const procedimientos = getValuesRowByTable("tableProcedures_h_iq") || [];
    const interconsultas = getValuesRowByTable("tableInterconsultaPreQx") || [];

    formData.append("rqCardiologia", $("#rq_cardiologia_aa").is(':checked') ? 1 : 0);
    formData.append("rqNeumologia", $("#rq_neumologia_aa").is(':checked') ? 1 : 0);
    formData.append("rqAnestesiologia", $("#rq_anestesiologia_aa").is(':checked') ? 1 : 0);

    formData.append("isActiveIntervencionQuirurgica", $("#valueToogleProgramacionQuirurgica").val() === "1" ? 1 : 0);
    formData.append("isActiveInterconsultaPreQuirurgica", $("#valueToogleInterconsultaPreQuirurgica").val() === "1" ? 1 : 0);
    // formData.append("requiereInternPreQx", $("#valueToogleRequiereInternPreQx").val() === "1" ? 1 : 0);
    // formData.append("requiereHospitalizacionPostQx", $("#valueToogleDiasHospitPostQx").val() === "1" ? 1 : 0);

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

    let files = $('#fileMopri')[0].files;
    for (let i = 0; i < files.length; i++) {
      formData.append("fileMopri[]", files[i]);
    }
    
    return formData;
  }

  function getValuesRowByTable(tableName) {
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

  function getIntervencionById(idCqxIntervencion) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/centro-quirurgico/intervencion/${idCqxIntervencion}`,
        type: "GET",
        dataType: "json",
        success: function (response) {
          resolve(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          reject(jqXHR.responseText || errorThrown);
        }
      });
    });
  }
});