$(document).ready(function () {
  let form = [
    { id: 1, name: 'hora_atencion', required: true },
    { id: 2, name: 'fecha_atencion', required: true },
    { id: 3, name: 'servicio', required: true, is_select2: true  },
    { id: 4, name: 'cod_upss', required: true, is_select2: true },
    { id: 5, name: 'tipo_cirugia', required: true },
    { id: 6, name: 'tipo_anestesia', required: true },
    { id: 7, name: 'hr_programadas', required: true },
    { id: 8, name: 'hra_inicio_intervencion', required: true },
    { id: 9, name: 'hra_fin_intervencion', required: true },
    { id: 10, name: 'hrs_efectivas', required: true },
    { id: 11, name: 'hra_inicio_acto_qx', required: true },
    { id: 12, name: 'hra_fin_acto_qx', required: true },
    { id: 14, name: 'complicacion_intervencion', required: true },
    { id: 15, name: 'tipo_programacion', required: true },
  ];

  let tables = [
    { id: "faTableCirujano", headId: "faTableCirujanoHead", messageEmpty: "Debe ingresar al menos un cirujano.", minLength: 1 },
    { id: "faTableAnastesiologo", headId: "faTableAnastesiologoHead", messageEmpty: "Debe ingresar al menos un anestesiologo.", minLength: 1 },
    { id: "faTableEnfermeraIntru", headId: "faTableEnfermeraIntruHead", messageEmpty: "Debe ingresar al menos una enfermera instrumentista.", minLength: 0 },
    { id: "faTableDiagnosticos", headId: "faTableDiagnosticosHead", messageEmpty: "Debe ingresar al menos un diagnostico.", minLength: 1 },
    { id: "faTableProcedures", headId: "faTableProceduresHead", messageEmpty: "Debe ingresar al menos un procedimiento.", minLength: 1 },
  ]

  let listCirujanos = [];
  let listAnastesiologos = [];
  let listEnfermerasIntru = [];

  let listTiposDiagnostico = [
    { id: 1, value: "PRESUNTIVO" },
    { id: 2, value: "DEFINITIVO" },
    { id: 3, value: "REITERATIVO" }
  ];

  let listClasificacionDiagnostico = [
    { id: 1, value: "PRINCIPAL" },
    { id: 2, value: "SEGUNDARIO" }
  ];

  let listDiagnosticos = [];

  main();

  $('#fecha_atencion').datepicker({
    useCurrent: false,
    format: 'dd/mm/yyyy',
    todayHighlight: true,
    autoclose: true,
    orientation: "bottom left"
  }).datepicker("setDate", new Date());

  $('#servicio').select2({ width: '100%' });
  $('#cod_upss').select2({ width: '100%' });

  $("#faBtnAddTableCirujano").on("click", function () {
    onClickAddRowTableCirujano();
  });

  $("#faBtnAddTableAnastesiologo").on("click", function () {
    onClickAddRowTableAnastesiologo();
  });

  $("#faBtnAddTableEnfermeraIntru").on("click", function () {
    onClickAddRowTableEnfermeraIntru();
  });

  $("#faBtnAddTableDiagnosticos").on("click", function () {
    onClickAddRowTableDiagnosticos();
  });

  $("#faBtnAddProcedures").on("click", function () {
    onClickAddRowTableProcedimientos();
  });

  $("#faBtnSaveAttention").on("click", function () {
    onSubmitForm();
  });

  $("#hra_inicio_intervencion, #hra_fin_intervencion").on("change", function () {
    const $inicioInput = $("#hra_inicio_intervencion");
    const $finInput = $("#hra_fin_intervencion");
    const $ingresoInput = $("#hospitalizacion_fecha_ingreso");

    const inicioVal = $inicioInput.val();
    const finVal = $finInput.val();
    const ingresoVal = $ingresoInput.val();

    if (!inicioVal || !finVal || !ingresoVal) return;

    const inicio = moment(inicioVal);
    const fin = moment(finVal);
    const ingreso = moment(ingresoVal);

    if (!inicio.isValid() || !fin.isValid() || !ingreso.isValid()) return;

    // Validar que inicio no sea menor que fecha de ingreso
    if (inicio.isBefore(ingreso)) {
      Swal.fire({
        icon: 'warning',
        title: 'Error en las fechas',
        text: 'La fecha/hora de inicio no puede ser anterior a la fecha de ingreso de la hospitalización.'
      });
      $inicioInput.val('');
      $finInput.val('');
      $inicioInput.focus();
      return;
    }

    // Validar que fin no sea menor que fecha de ingreso
    if (fin.isBefore(ingreso)) {
      Swal.fire({
        icon: 'warning',
        title: 'Error en las fechas',
        text: 'La fecha/hora de fin no puede ser anterior a la fecha de ingreso de la hospitalización.'
      });
      $inicioInput.val('');
      $finInput.val('');
      $finInput.focus();
      return;
    }

    if (inicio.isAfter(fin)) {
      Swal.fire({
        icon: 'warning',
        title: 'Error en las fechas',
        text: 'La fecha/hora de inicio no puede ser mayor que la de fin.'
      });
      $inicioInput.val('');
      $finInput.val('');
      $inicioInput.focus();
      return;
    }

    if (fin.isBefore(inicio)) {
      Swal.fire({
        icon: 'warning',
        title: 'Error en las fechas',
        text: 'La fecha/hora de fin no puede ser menor que la de inicio.'
      });
      $inicioInput.val('');
      $finInput.val('');
      $finInput.focus();
      return;
    }

    const diffMinutesTotal = fin.diff(inicio, 'minutes');

    const hours = Math.floor(diffMinutesTotal / 60);
    // const minutes = diffMinutesTotal % 60;

    $("#hrs_efectivas").val(`${hours}`);
  });

  $("#hra_inicio_acto_qx, #hra_fin_acto_qx").on("change", function () {
    const $inicioInput = $("#hra_inicio_acto_qx");
    const $finInput = $("#hra_fin_acto_qx");
    const $ingresoInput = $("#hospitalizacion_fecha_ingreso");

    const inicioVal = $inicioInput.val();
    const finVal = $finInput.val();
    const ingresoVal = $ingresoInput.val();

    if (!inicioVal || !finVal || !ingresoVal) return;

    const inicio = moment(inicioVal);
    const fin = moment(finVal);
    const ingreso = moment($ingresoInput.val());

    if (!inicio.isValid() || !fin.isValid() || !ingreso.isValid()) return;

        // Validar que inicio no sea menor que fecha de ingreso
    if (inicio.isBefore(ingreso)) {
      Swal.fire({
        icon: 'warning',
        title: 'Error en las fechas',
        text: 'La fecha/hora de inicio no puede ser anterior a la fecha de ingreso de la hospitalización.'
      });
      $inicioInput.val('');
      $finInput.val('');
      $inicioInput.focus();
      return;
    }

    // Validar que fin no sea menor que fecha de ingreso
    if (fin.isBefore(ingreso)) {
      Swal.fire({
        icon: 'warning',
        title: 'Error en las fechas',
        text: 'La fecha/hora de fin no puede ser anterior a la fecha de ingreso de la hospitalización.'
      });
      $inicioInput.val('');
      $finInput.val('');
      $finInput.focus();
      return;
    }

    if (inicio.isAfter(fin)) {
      Swal.fire({
        icon: 'warning',
        title: 'Error en las fechas',
        text: 'La fecha/hora de inicio no puede ser mayor que la de fin.'
      });
      $inicioInput.val('');
      $inicioInput.focus();
      return;
    }

    if (fin.isBefore(inicio)) {
      Swal.fire({
        icon: 'warning',
        title: 'Error en las fechas',
        text: 'La fecha/hora de fin no puede ser menor que la de inicio.'
      });
      $finInput.val('');
      $finInput.focus();
      return;
    }

    const diffMinutesTotal = fin.diff(inicio, 'minutes');

    const hours = Math.floor(diffMinutesTotal / 60);
    const minutes = diffMinutesTotal % 60;

    $("#hrs_acto_qx").val(`${hours}.${minutes}`);
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

      const results = await getCqxTipoProfesionales();
      results.forEach(({ flag, response }) => {
        if (response && response.data) {
          switch (flag) {
            case 1:
              listCirujanos = response.data;
              break;
            case 2:
              listAnastesiologos = response.data;
              break;
            case 3:
              listEnfermerasIntru = response.data;
              break;
          }
        }
      });

      const intervencion = await getIntervencionById();
      if (intervencion && intervencion.data) {
        setValuesForm(intervencion.data);
      }

      $('#hora_atencion').val(moment().format('HH:mm'));
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

  function onClickAddRowTableCirujano() {
    const lastRowValid = validateLastRowTable("faTableCirujano");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p>ATENDER INTERVENCIÓN CIRURGICA</p>',
        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO CIRUJANO</p>',
      });
      return null;
    }
    const indexRow = $('#faTableCirujano tbody tr').length + 1;

    let options = "<option value=''>--Seleccionar--</option>";
    listCirujanos.forEach(opcion => {
      options += `<option value="${opcion.id}">${opcion.descripcion}</option>`;
    });

    const newRow = `
      <tr id="tr_cirujano_${indexRow}">
          <td><input type="text" name="cirujano_profesional" id="cirujano_profesional${indexRow}" class="form-control input-sm"></td>
          <td><input type="text" name="cirujano_profesion" id="cirujano_profesion${indexRow}" class="form-control input-sm input-readonly" readonly></td>
          <td><input type="text" name="cirujano_cpm" id="cirujano_cpm${indexRow}" class="form-control input-sm input-readonly" readonly></td>
          <td><select class="form-control input-sm" name="cirujano_tipo_cirujano" id="cirujano_tipo_cirujano${indexRow}">${options}</select></td>
          <td><button id="faBtnRemoveRowCirujano" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
          <td><input type="hidden" name="cirujano_profesional_id" id="cirujano_profesional_id${indexRow}"></td>
      </tr>`;

    $('#faTableCirujano tbody').append(newRow);
    $("#faTableCirujanoHead").removeClass("table-error danger").nextAll('.label-help').remove();

    $(`#cirujano_profesional${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function (request, response) {
        $.ajax({
          url: `/profesional_que_interviene/${request.term}`,
          type: "GET",
          dataType: "json",
          success: function (data) {
            response(data.map(function (item) {
              return {
                id: item.id,
                label: item.label,
                profesion: item.profesion,
                colegiatura: item.colegiatura,
              };
            }));
          },
          error: function () {
            response([]);
          }
        })
      },
      select: function (event, ui) {
        let idItem = ui.item.id;
        const isRepeated = validateRepeatedRowTable('faTableCirujano', 'cirujano_profesional_id', idItem);
        if (!isRepeated) {
          $(this).val(ui.item.label);
          $(`#cirujano_profesional_id${indexRow}`).val(idItem);
          $(`#cirujano_profesion${indexRow}`).val(ui.item.profesion ?? "-");
          $(`#cirujano_cpm${indexRow}`).val(ui.item.colegiatura ?? "-");
        } else {
          Swal.fire({
            icon: "warning",
            title: '<p>CIRUJANO REPETIDO</p>',
            html: '<p class="descripcion-alert">EL CIRUJANO YA FUE AGREGADO, NO SE PERMITEN CIRUJANOS REPETIDOS</p>',
          });
          $(this).val("");
          $(`#cirujano_profesional${indexRow}`).val("");
          $(`#cirujano_profesion${indexRow}`).val("");
          $(`#cirujano_cpm${indexRow}`).val("");
          $(`#cirujano_profesional_id${indexRow}`).val("");
          event.preventDefault();
        }
      }
    });

    $(`#cirujano_profesional${indexRow}`).on('input', function () {
      $(`#cirujano_profesional_id${indexRow}`).val("");
    });

    $(`#cirujano_profesional${indexRow}`).blur(function () {
      const profesional_id = $(`#cirujano_profesional_id${indexRow}`).val();
      clearInputError($(this).attr("id"))
      if (!profesional_id) {
        markInputError($(this).attr("id"), "espere los resultados y seleccione un cirujano");
      }
    });

    $('#faTableCirujano').on('click', '#faBtnRemoveRowCirujano', function () {
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      form = form.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    form.push({ id: indexRow, name: `cirujano_profesional${indexRow}`, required: true, tr_id: `tr_cirujano_${indexRow}` });
    form.push({ id: indexRow, name: `cirujano_tipo_cirujano${indexRow}`, required: true, tr_id: `tr_cirujano_${indexRow}` });
  }

  function onClickAddRowTableAnastesiologo() {
    const lastRowValid = validateLastRowTable("faTableAnastesiologo");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p>ATENDER INTERVENCIÓN CIRURGICA</p>',
        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO ANESTESIOLOGO</p>',
      });
      return null;
    }
    const indexRow = $('#faTableAnastesiologo tbody tr').length + 1;

    let options = "<option value=''>--Seleccionar--</option>";
    listAnastesiologos.forEach(opcion => {
      options += `<option value="${opcion.id}">${opcion.descripcion}</option>`;
    });

    const newRow = `
      <tr id="tr_anastesiologo_${indexRow}">
          <td><input type="text" name="anastesiologo_profesional" id="anastesiologo_profesional${indexRow}" class="form-control input-sm"></td>
          <td><input type="text" name="anastesiologo_profesion" id="anastesiologo_profesion${indexRow}" class="form-control input-sm input-readonly" readonly></td>
          <td><input type="text" name="anastesiologo_cpm" id="anastesiologo_cpm${indexRow}" class="form-control input-sm input-readonly" readonly></td>
          <td><select class="form-control input-sm" name="anastesiologo_tipo_cirujano" id="anastesiologo_tipo_cirujano${indexRow}">${options}</select></td>
          <td><button id="faBtnRemoveRowAnastesiologo" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
          <td><input type="hidden" name="anastesiologo_profesional_id" id="anastesiologo_profesional_id${indexRow}"></td>
      </tr>`;

    $('#faTableAnastesiologo tbody').append(newRow);
    $("#faTableAnastesiologoHead").removeClass("table-error danger").nextAll('.label-help').remove();

    $(`#anastesiologo_profesional${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function (request, response) {
        $.ajax({
          url: `/profesional_que_interviene/${request.term}`,
          type: "GET",
          dataType: "json",
          success: function (data) {
            response(data.map(function (item) {
              return {
                id: item.id,
                label: item.label,
                profesion: item.profesion,
                colegiatura: item.colegiatura,
              };
            }));
          },
          error: function () {
            response([]);
          }
        })
      },
      select: function (event, ui) {
        let idItem = ui.item.id;
        const isRepeated = validateRepeatedRowTable('faTableAnastesiologo', 'anastesiologo_profesional_id', idItem);
        if (!isRepeated) {
          $(this).val(ui.item.label);
          $(`#anastesiologo_profesional_id${indexRow}`).val(idItem);
          $(`#anastesiologo_profesion${indexRow}`).val(ui.item.profesion ?? "-");
          $(`#anastesiologo_cpm${indexRow}`).val(ui.item.colegiatura ?? "-");
        } else {
          Swal.fire({
            icon: "warning",
            title: '<p>ANESTESIOLOGO REPETIDO</p>',
            html: '<p class="descripcion-alert">EL ANESTESIOLOGO YA FUE AGREGADO, NO SE PERMITEN ANESTESIOLOGO REPETIDOS</p>',
          });
          $(this).val("");
          $(`#anastesiologo_profesional${indexRow}`).val("");
          $(`#anastesiologo_profesion${indexRow}`).val("");
          $(`#anastesiologo_cpm${indexRow}`).val("");
          $(`#anastesiologo_profesional_id${indexRow}`).val("");
          event.preventDefault();
        }
      }
    });

    $(`#anastesiologo_profesional${indexRow}`).on('input', function () {
      $(`#anastesiologo_profesional_id${indexRow}`).val("");
    });

    $(`#anastesiologo_profesional${indexRow}`).blur(function () {
      const profesional_id = $(`#anastesiologo_profesional_id${indexRow}`).val();
      clearInputError($(this).attr("id"))
      if (!profesional_id) {
        markInputError($(this).attr("id"), "espere los resultados y seleccione un cirujano");
      }
    });

    $('#faTableAnastesiologo').on('click', '#faBtnRemoveRowAnastesiologo', function () {
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      form = form.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    form.push({ id: indexRow, name: `anastesiologo_profesional${indexRow}`, required: true, tr_id: `tr_anastesiologo_${indexRow}` });
    form.push({ id: indexRow, name: `anastesiologo_tipo_cirujano${indexRow}`, required: true, tr_id: `tr_anastesiologo_${indexRow}` });
  }

  function onClickAddRowTableEnfermeraIntru() {
    const lastRowValid = validateLastRowTable("faTableEnfermeraIntru");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p>ATENDER INTERVENCIÓN CIRURGICA</p>',
        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UNA NUEVA ENFERMERA INSTRUMENTISTA</p>',
      });
      return null;
    }
    const indexRow = $('#faTableEnfermeraIntru tbody tr').length + 1;

    let options = "<option value=''>--Seleccionar--</option>";
    listEnfermerasIntru.forEach(opcion => {
      options += `<option value="${opcion.id}">${opcion.descripcion}</option>`;
    });

    const newRow = `
      <tr id="tr_enfermera_intru_${indexRow}">
          <td><input type="text" name="enfermera_intru_profesional" id="enfermera_intru_profesional${indexRow}" class="form-control input-sm"></td>
          <td><input type="text" name="enfermera_intru_profesion" id="enfermera_intru_profesion${indexRow}" class="form-control input-readonly" readonly></td>
          <td><input type="text" name="enfermera_intru_cpm" id="enfermera_intru_cpm${indexRow}" class="form-control input-readonly" readonly></td>
          <td><select class="form-control input-sm" name="enfermera_intru_tipo_cirujano" id="enfermera_intru_tipo_cirujano${indexRow}">${options}</select></td>
          <td><button id="faBtnRemoveRowEnfermeraIntru" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
          <td><input type="hidden" name="enfermera_intru_profesional_id" id="enfermera_intru_profesional_id${indexRow}"></td>
      </tr>`;

    $('#faTableEnfermeraIntru tbody').append(newRow);
    $("#faTableEnfermeraIntruHead").removeClass("table-error danger").nextAll('.label-help').remove();

    $(`#enfermera_intru_profesional${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function (request, response) {
        $.ajax({
          url: `/profesional_que_interviene/${request.term}/8`,
          type: "GET",
          dataType: "json",
          success: function (data) {
            response(data.map(function (item) {
              return {
                id: item.id,
                label: item.label,
                profesion: item.profesion,
                colegiatura: item.colegiatura,
              };
            }));
          },
          error: function () {
            response([]);
          }
        })
      },
      select: function (event, ui) {
        let idItem = ui.item.id;
        const isRepeated = validateRepeatedRowTable('faTableEnfermeraIntru', 'enfermera_intru_profesional_id', idItem);
        if (!isRepeated) {
          $(this).val(ui.item.label);
          $(`#enfermera_intru_profesional_id${indexRow}`).val(idItem);
          $(`#enfermera_intru_profesion${indexRow}`).val(ui.item.profesion ?? "-");
          $(`#enfermera_intru_cpm${indexRow}`).val(ui.item.colegiatura ?? "-");
        } else {
          Swal.fire({
            icon: "warning",
            title: '<p>ENFERMERA INSTRUMENTISTA REPETIDO</p>',
            html: '<p class="descripcion-alert">ENFERMERA INSTRUMENTISTA YA FUE AGREGADO, NO SE PERMITEN ENFERMERA INSTRUMENTISTA REPETIDOS</p>',
          });
          $(this).val("");
          $(`#enfermera_intru_profesional${indexRow}`).val("");
          $(`#enfermera_intru_profesion${indexRow}`).val("");
          $(`#enfermera_intru_cpm${indexRow}`).val("");
          $(`#enfermera_intru_profesional_id${indexRow}`).val("");
          event.preventDefault();
        }
      }
    });

    $(`#enfermera_intru_profesional${indexRow}`).on('input', function () {
      $(`#enfermera_intru_profesional_id${indexRow}`).val("");
    });

    $(`#enfermera_intru_profesional${indexRow}`).blur(function () {
      const profesional_id = $(`#enfermera_intru_profesional_id${indexRow}`).val();
      clearInputError($(this).attr("id"))
      if (!profesional_id) {
        markInputError($(this).attr("id"), "espere los resultados y seleccione un cirujano");
      }
    });

    $('#faTableEnfermeraIntru').on('click', '#faBtnRemoveRowEnfermeraIntru', function () {
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      form = form.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    form.push({ id: indexRow, name: `enfermera_intru_profesional${indexRow}`, required: true, tr_id: `tr_enfermera_intru_${indexRow}` });
    form.push({ id: indexRow, name: `enfermera_intru_tipo_cirujano${indexRow}`, required: true, tr_id: `tr_enfermera_intru_${indexRow}` });
  }

  function onClickAddRowTableDiagnosticos() {
    const lastRowValid = validateLastRowTable("faTableDiagnosticos");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p>ATENDER INTERVENCIÓN CIRURGICA</p>',
        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO DIAGNOSTICO</p>',
      });
      return null;
    }
    const indexRow = $('#faTableDiagnosticos tbody tr').length + 1;

    let optionsTipos = "<option value=''>--Seleccionar--</option>";
    listTiposDiagnostico.forEach(opcion => {
      optionsTipos += `<option value="${opcion.id}">${opcion.value}</option>`;
    });

    let optionsClasificacion = "<option value=''>--Seleccionar--</option>";
    listClasificacionDiagnostico.forEach(opcion => {
      optionsClasificacion += `<option value="${opcion.id}">${opcion.value}</option>`;
    });

    const newRow = `
      <tr id="tr_diagnosticos_${indexRow}">
        <td><input type="text" name="diagnosticos" id="diagnosticos${indexRow}" class="form-control input-sm"></td>
        <td><select class="form-control input-sm" name="tipo_diagnosticos" id="tipo_diagnosticos${indexRow}">${optionsTipos}</select></td>
        <td><select class="form-control input-sm" name="clasificacion" id="clasificacion${indexRow}">${optionsClasificacion}</select></td>
        <td><button id="faBtnRemoveRowDiagnosticos" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
        <td><input type="hidden" name="diagnosticos_id" id="diagnosticos_id${indexRow}"></td>
      </tr>`;

    $('#faTableDiagnosticos tbody').append(newRow);
    $("#faTableDiagnosticosHead").removeClass("table-error danger").nextAll('.label-help').remove();

    const sexo = $("#asegurado_sexo_abrev").val();
    //const sexo = String($("#asegurado_sexo_abrev").val()).toUpperCase() === "M" ? 1 : 2;

    $(`#diagnosticos${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function (request, response) {
        $.ajax({
          url: `/obtener_diagnostico_genero/${request.term}/${sexo}`,
          type: "GET",
          dataType: "json",
          success: function (data) {
            response(data.map(function (item) {
              return { id: item.id, label: item.label };
            }));
          },
          error: function () {
            response([]);
          }
        })
      },
      select: function (event, ui) {
        let idItem = ui.item.id;
        const isRepeated = validateRepeatedRowTable('faTableDiagnosticos', 'diagnosticos_id', idItem);
        if (!isRepeated) {
          $(this).val(ui.item.label);
          $(`#diagnosticos_id${indexRow}`).val(idItem);
          addItemListDiagnosticos({ diagnostico_id: idItem, nombre_diagnostico: ui.item.label })
        } else {
          Swal.fire({
            icon: "warning",
            title: '<p>NOTA DE INGRESO DE ENFERMERÍA</p>',
            html: '<p class="descripcion-alert">EL DIAGNÓSTICO YA FUE AGREGADO, NO SE PERMITEN DIAGNÓSTICO REPETIDOS</p>',
          });
          $(this).val("");
          $(`#diagnosticos${indexRow}`).val("");
          $(`#diagnosticos_id${indexRow}`).val("");
          event.preventDefault();
        }

      }
    });

    $(`#diagnosticos_profesional${indexRow}`).on('input', function () {
      $(`#diagnosticos_profesional_id${indexRow}`).val("");
    });

    $(`#diagnosticos_profesional${indexRow}`).blur(function () {
      const profesional_id = $(`#diagnosticos_profesional_id${indexRow}`).val();
      clearInputError($(this).attr("id"))
      if (!profesional_id) {
        markInputError($(this).attr("id"), "espere los resultados y seleccione un diagnostico");
      }
    });

    $('#faTableDiagnosticos').on('click', '#faBtnRemoveRowDiagnosticos', function () {
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      const diagnosticoId = row.find('input[name="diagnosticos_id"]').val();
      removeItemListDiagnosticos(diagnosticoId)
      form = form.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    form.push({ id: indexRow, name: `diagnosticos${indexRow}`, required: true, tr_id: `tr_diagnosticos_${indexRow}` });
    form.push({ id: indexRow, name: `tipo_diagnosticos${indexRow}`, required: true, tr_id: `tr_diagnosticos_${indexRow}` });
    form.push({ id: indexRow, name: `clasificacion${indexRow}`, required: true, tr_id: `tr_diagnosticos_${indexRow}` });
  }

  function onClickAddRowTableProcedimientos() {
    const lastRowValid = validateLastRowTable("faTableProcedures");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p>NOTA DE INGRESO DE ENFERMERÍA</p>',
        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO PROCEDIMIENTO</p>',
      });
      return null;
    }
    const indexRow = $('#faTableProcedures tbody tr').length + 1;

    const options = generarOptionsDiagnosticos(listDiagnosticos);

    const newRow = `
      <tr id="tr_procedimientos_${indexRow}">
          <td><input type="text" name="procedimiento_nombre" id="procedimiento_nombre${indexRow}" class="form-control input-sm"></td>
          <td><input type="number" name="procedimiento_cantidad" id="procedimiento_cantidad${indexRow}" class="form-control input-sm" value="1"></td>
          <td><input type="number" name="procedimiento_tiempo" id="procedimiento_tiempo${indexRow}" class="form-control input-sm" value="1"></td>
          <td><select class="form-control input-sm" name="procedimiento_diagnostico" id="procedimiento_diagnostico${indexRow}">${options}</select></td>
          <td><button id="faBtnRemoveRowProcedure" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
          <td><input type="hidden" name="procedure_id" id="procedure_id${indexRow}"></td>
      </tr>`;

    $('#faTableProcedures tbody').append(newRow);
    $("#faTableProceduresHeader").removeClass("table-error danger").nextAll('.label-help').remove();

    const sexo = $("#asegurado_sexo_abrev").val();
    //const sexo = String($("#asegurado_sexo_abrev").val()).toUpperCase() === "M" ? 1 : 2;
    const establecimiento_nivel = $("#establecimiento_nivel").val() || "1";

    $(`#procedimiento_nombre${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function (request, response) {
        $.ajax({
          url: `/obtener_procedimiento_convenio/${request.term}/${establecimiento_nivel}/${sexo}/${1}`,
          type: "GET",
          dataType: "json",
          success: function (data) {
            response(data.map(function (item) {
              return { id: item.id, label: item.label };
            }));
          },
          error: function () {
            response([]);
          }
        })
      },
      select: function (event, ui) {
        let idItem = ui.item.id;
        const isRepeated = validateRepeatedRowTable('faTableProcedures', 'procedure_id', idItem);
        if (!isRepeated) {
          $(this).val(ui.item.label);
          $(`#procedure_id${indexRow}`).val(idItem);
        } else {
          Swal.fire({
            icon: "warning",
            title: '<p>NOTA DE INGRESO DE ENFERMERÍA</p>',
            html: '<p class="descripcion-alert">EL PROCEDIMIENTO YA FUE AGREGADO, NO SE PERMITEN PROCEDIMIENTOS REPETIDOS</p>',
          });
          $(this).val("");
          $(`#procedimiento_nombre${indexRow}`).val("");
          $(`#procedure_id${indexRow}`).val("");
          event.preventDefault();
        }

      }
    });

    $(`#procedimiento_nombre${indexRow}`).on('input', function () {
      $(`#procedure_id${indexRow}`).val("");
    });

    $(`#procedimiento_nombre${indexRow}`).blur(function () {
      const id_procedimiento = $(`#procedure_id${indexRow}`).val();
      clearInputError($(this).attr("id"))
      if (!id_procedimiento) {
        markInputError($(this).attr("id"), "espere los resultados y seleccione un procedimiento");
      }
    });

    $('#faTableProcedures').on('click', '#faBtnRemoveRowProcedure', function () {
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      form = form.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    form.push({ id: indexRow, name: `procedimiento_nombre${indexRow}`, required: true, tr_id: `tr_procedimientos_${indexRow}` });
    form.push({ id: indexRow, name: `procedimiento_cantidad${indexRow}`, required: true, tr_id: `tr_procedimientos_${indexRow}` });
    form.push({ id: indexRow, name: `procedimiento_tiempo${indexRow}`, required: true, tr_id: `tr_procedimientos_${indexRow}` });
    form.push({ id: indexRow, name: `procedimiento_diagnostico${indexRow}`, required: true, tr_id: `tr_procedimientos_${indexRow}` });
  }

  function markInputError(nameInput, messsageError = null) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest('.form-group, td');
    parentDiv.addClass("has-error");
    inputElement.after(`<span class="label-help">${messsageError || "campo requerido"}</span>`);
  }

  function clearInputSuccess(nameInput) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest('.form-group, td');
    inputElement.siblings('.form-control-feedback, .label-help-success').remove();
    parentDiv.removeClass("has-error has-success");
  }

  function markInputSuccess(nameInput, messsageError = null) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest('.form-group');
    parentDiv.addClass("has-success");
    inputElement.after(`<span class="label-help-success">${messsageError || "campo correcto"}</span>`);
  }

  function clearInputError(nameInput) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest('.form-group, td');
    inputElement.siblings('.form-control-feedback, .label-help').remove();
    parentDiv.removeClass("has-error has-success");
  }

  function validateLastRowTable(tableId) {
    let lastRow = $(`#${tableId} tbody tr:last`);
    let isValid = true;
    lastRow.find('input:visible').each(function () {
      clearInputError($(this).attr("id"));
      if ($(this).val().trim() === '') {
        markInputError($(this).attr("id"));
        isValid = false;
      }
    });
    lastRow.find('select:visible').each(function () {
      clearInputError($(this).attr("id"));
      if ($(this).val() === '') {
        markInputError($(this).attr("id"));
        isValid = false;
      }
    });
    return isValid;
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
      url: '/api/cqx_intervenciones/atender/nivel2',
      type: 'POST',
      dataType: 'json',
      data: body,
      beforeSend: function () {
        Swal.fire({
          title: 'Procesando solicitud',
          text: 'Estamos atendiendo la intervención. Por favor, espera...',
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
      }
    }).done(function (response) {
      if (response && response.data && response.data.id_cqx_intervencion) {
        Swal.fire({
          icon: 'success',
          title: 'Intervención atendida',
          text: 'La solicitud se procesó correctamente.',
          confirmButtonText: 'Aceptar'
        });
        window.location.replace(`/intervenciones/${response.data.id_cqx_intervencion}/ver-detalle`);
      }      
    }).fail(function (jqXHR, textStatus, errorThrown) {
      // Intentar obtener mensaje de error del backend (si viene en JSON)
      let mensajeError = 'Ocurrió un error al procesar la solicitud. Por favor, inténtalo nuevamente.';

      if (jqXHR.responseJSON && jqXHR.responseJSON.mensaje) {
        mensajeError = jqXHR.responseJSON.mensaje;
      }

      Swal.fire({
        icon: 'error',
        title: 'No se pudo completar la operación',
        text: mensajeError,
        confirmButtonText: 'Entendido'
      });

    }).always(function () {
      // Si necesitas hacer algo siempre (habilitar botón, limpiar loaders externos, etc.)
      // $('#btnGuardar').prop('disabled', false);
    });

  }

  function validateForm() {
    let controlsError = [];
    let errorFaTableCirujano = false;

    for (const control of form) {
      if (!control.required) continue;
      const inputElement = $(`#${control.name}`);
      const select2Container = getSelect2Container(inputElement);
      if (select2Container) {
        select2Container.nextAll('.label-help').remove();
      } else {
        inputElement.nextAll('.label-help').remove();
      }

      let parentDiv = null;
      if (control.tr_id) {
        parentDiv = inputElement.closest('td');
      } else {
        parentDiv = inputElement.closest('.form-group');
      }
      if (!parentDiv || parentDiv.length === 0) continue;
      const value = inputElement.val();
      const isSelect2 = !!select2Container;
      if (value === "" || value === null || value === undefined) {
        parentDiv.addClass('has-error');

        if (isSelect2) {
          select2Container.after('<span class="label-help">campo requerido</span>');
          const $selVis = select2Container.find('.select2-selection, .select2-selection--single').first();
          if ($selVis && $selVis.length) {
            $selVis.addClass('select2-error');
          } else {
            select2Container.addClass('select2-error');
          }
        } else {
          inputElement.after('<span class="label-help">campo requerido</span>');
          inputElement.addClass('input-error');
        }

        controlsError.push(control);
      } else {
        parentDiv.removeClass('has-error');

        if (isSelect2) {
          select2Container.nextAll('.label-help').remove();
          select2Container.find('.select2-selection, .select2-selection--single').removeClass('select2-error');
          select2Container.removeClass('select2-error');
        } else {
          inputElement.nextAll('.label-help').remove();
          inputElement.removeClass('input-error');
        }
      }
    }

    // tu validación de tablas (dejé tal cual)
    for (const table of tables) {
      $(`#${table.id}`).removeClass("table-error danger").nextAll('.label-help').remove();
      const rowsFaTableCirujano = $(`#${table.id} tbody tr`);
      if (table.minLength > 0 && rowsFaTableCirujano.length === 0) {
        $(`#${table.headId}`).addClass("table-error danger").after(`<span class="label-help">${table.messageEmpty}</span>`);
        controlsError.push({ name: table.id });
      } else {
        $(`#${table.headId}`).removeClass("table-error danger").nextAll('.label-help').remove();
      }
    }

    for (const controlError of controlsError) {
      if (controlError.name === "faTableCirujano") {
        errorFaTableCirujano = true;
        break;
      }
    }

    if (!errorFaTableCirujano) {
      const isNotExistCirujanoPrincipal = validarQueExistaCirujanoPrincipal();
      if (!isNotExistCirujanoPrincipal) {
        $(`#faTableCirujano`).addClass("table-error danger").after(`<span class="label-help">Selecciona al menos un CIRUJANO PRINCIPAL entre los cirujanos registrados.</span>`);
        controlsError.push({ name: "faTableCirujano" });
        $('html, body').animate({ scrollTop: $('#faTableCirujano').offset().top - 100 }, 500);
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

  function getIntervencionById() {
    const intervencion_id = $("#id_intervencion").val();
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/cqx_intervenciones/${intervencion_id}`,
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

  function setValuesForm(data) {
    let datos_del_titular = "-";
    if (parseInt(data.id_tipo_beneficiario) !== 8) {
      datos_del_titular = `${data.asegurado_titular_nombre ?? ""} ${data.asegurado_titular_paterno} ${data.asegurado_titular_materno}`;
    }
    let cip_dni_grado = `${data.asegurado_titular_cip}/${data.asegurado_titular_nro_doc_ident}/${data.asegurado_titular_grado}`;
    const apellido_nombres = `${data.asegurado_nombre} ${data.asegurado_paterno} ${data.asegurado_materno}`;
    $("#id_hospitalizacion").val(data.id_hospitalizacion);
    $("#id_asegurado").val(data.id_asegurado);
    $("#id_hospitalizacion").val(data.id_hospitalizacion);

    $("#nro_documento").text(data.asegurado_nro_doc_ident);
    $("#nro_hc").text(data.asegurado_nro_historia);
    $("#apellido_nombres").text(apellido_nombres);
    $("#fec_nacimiento").text(data.asegurado_fecha_nac);
    $("#edad").text(data.asegurado_edad);
    $("#sexo").text(data.asegurado_sexo);
    $("#tipo_beneficiario").text(data.tipo_beneficiario);
    $("#datos_del_titular").text(datos_del_titular ?? "-");
    $("#cip_dni_grado").text(cip_dni_grado ?? "-");
    $("#discapacidad").text(parseInt(data.asegurado_titular_discapacidad) === 1 ? "SI" : "NO");

    const fecha_ingreso = data.hospitalizacion_fecha_ingreso ? moment(data.hospitalizacion_fecha_ingreso, 'YYYY-MM-DD HH:mm').format("DD-MM-YYYY") : "-";

    $("#codigo_siteds_text").text(data.hospitalizacion_cod_siteds_susalud ?? "-");
    $("#codigo_siteds").val(data.hospitalizacion_cod_siteds_susalud ?? "-");
    $("#tipo_atencion").text(data.tipo_atencion_nombre ?? "-");
    $("#fecha_ingreso").text(fecha_ingreso);
    $("#servicio_procedencia").text(data.consultorio_origen ?? "-");
    $("#ubicacion_paciento").text("-");

    $("#asegurado_sexo_abrev").val(data.asegurado_sexo_abrev);
    $("#establecimiento_nivel").val(data.establecimiento_nivel);
    $("#id_establecimiento").val(data.id_establecimiento);

    const responsableCqx = `${data.medico_responsable_cqx_nombre} ${data.medico_responsable_cqx_paterno} ${data.medico_responsable_cqx_materno}`;

    $("#res_inter_nro_documento").text(data.medico_responsable_cqx_dni);
    $("#res_inter_profesional_medico").text(responsableCqx);
    $("#res_inter_profesion").text(data.medico_responsable_cqx_profesion);
    $("#res_inter_cpm").text(data.medico_responsable_cqx_colegiatura);
    $("#res_inter_rne").text(data.medico_responsable_cqx_rne);

    const fechaFormatoInput = moment(data.hospitalizacion_fecha_ingreso, "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DDTHH:mm");
    $("#hospitalizacion_fecha_ingreso").val(fechaFormatoInput);

    if (data.pre_diagnosticos && data.pre_diagnosticos.length > 0) {
      for (const preDiagnostico of data.pre_diagnosticos) {
        $('#faTableDiagnosticosPre tbody').append(`<tr>
                                                  <td>${preDiagnostico.codigo}</td>
                                                  <td>${preDiagnostico.nombre}</td>
                                                  <td>DEFINITIVO</td>
                                                </tr>`);
      }
    }
  }

  function validateRepeatedRowTable(TableId, InputName, idRowSearch) {
    let isRepeated = false;
    $(`#${TableId} tbody tr`).each(function () {
      const idRow = $(this).find(`input[name='${InputName}']`).val();
      if (idRow && idRowSearch && parseInt(idRow) === parseInt(idRowSearch)) {
        isRepeated = true;
        return false;
      }
    });
    return isRepeated;
  }

  async function getCqxTipoProfesionales() {
    const flags = [1, 2, 3];
    return Promise.all(
      flags.map(async flag => {
        const response = await $.ajax({
          url: `/api/cqx_tipo_professionales/flag_profesional/${flag}`,
          type: "GET",
          dataType: "json"
        });
        return { flag, response };
      })
    );
  }

  function actualizarSelectDiagnosticos() {
    const options = generarOptionsDiagnosticos(listDiagnosticos);

    $('select[name="procedimiento_diagnostico"]').each(function () {
      const selected = $(this).val(); // guardar valor actual
      $(this).html(options);          // actualizar options
      $(this).val(selected);          // restaurar valor si existía
    });
  }

  function generarOptionsDiagnosticos(listDiagnosticos) {
    let options = "<option value=''>--Seleccionar--</option>";
    listDiagnosticos.forEach(opcion => {
      options += `<option value="${opcion.diagnostico_id}">${opcion.nombre_diagnostico}</option>`;
    });
    return options;
  }

  function addItemListDiagnosticos(newDiagnosticos) {
    listDiagnosticos.push(newDiagnosticos);
    actualizarSelectDiagnosticos();
  }

  function removeItemListDiagnosticos(diagnosticoId) {
    listDiagnosticos = listDiagnosticos.filter(item => parseInt(item.diagnostico_id) !== parseInt(diagnosticoId));
    actualizarSelectDiagnosticos();
  }

  function getSelect2Container($select) {
    // intento 1: normalmente está justo después
    let $c = $select.nextAll('span.select2').first();
    if ($c && $c.length) return $c;

    // intento 2: algunos temas usan .select2-container
    $c = $select.nextAll('.select2-container').first();
    if ($c && $c.length) return $c;

    // intento 3: buscar en el parent .form-group (por si el DOM se reorganizó)
    $c = $select.closest('.form-group').find('span.select2').first();
    if ($c && $c.length) return $c;

    // intento 4: buscar por atributo aria-hidden (Select2 lo usa en algunos casos)
    $c = $select.siblings('span.select2[aria-hidden]').first();
    if ($c && $c.length) return $c;

    return null;
  }

  function getValuesRowByTable(tableName) {
    const rows = $(`#${tableName} tbody tr`);
    const resultados = [];
    rows.each(function (index, fila) {
      let valores = {};
      $(fila).find('input, select').each(function () {
        valores[$(this).attr('name')] = $(this).val();
      });
      resultados.push(valores);
    });
    return resultados;
  }

  function buildRequestSave() {
    const request = {
      intervencionId: $("#id_intervencion").val(),
      aseguradoSexoAbrev: $("#asegurado_sexo_abrev").val(),
      establecimientoNivel: $("#establecimiento_nivel").val(),
      hospitalizacionId: $("#id_hospitalizacion").val(),
      hospitalizacionId: $("#id_hospitalizacion").val(),
      codigoSiteds: $("#codigo_siteds").val(),
      aseguradoId: $("#id_asegurado").val(),
      establecimientoId: $("#id_establecimiento").val(),
      medicoId: $("#id_medico").val(),
      horaAtencion: $("#hora_atencion").val(),
      fechaAtencion: $("#fecha_atencion").val(),
      servicioId: $("#servicio").val(),
      upssId: $("#cod_upss").val(),
      cirujanos: getValuesRowByTable("faTableCirujano") || [],
      anastesiologos: getValuesRowByTable("faTableAnastesiologo") || [],
      enfermerasIntrumentistas: getValuesRowByTable("faTableEnfermeraIntru") || [],
      diagnosticos: getValuesRowByTable("faTableDiagnosticos") || [],
      procedimientos: getValuesRowByTable("faTableProcedures") || [],
      clasificacionCirugiaId: $("#tipo_cirugia").val(),
      tipoAnestesiaId: $("#tipo_anestesia").val(),
      hrProgramadas: $("#hr_programadas").val(),
      horaInicioIntervencion: $("#hra_inicio_intervencion").val(),
      horaFinIntervencion: $("#hra_fin_intervencion").val(),
      horasEfectivas: $("#hrs_efectivas").val(),
      horaInicioActoQx: $("#hra_inicio_acto_qx").val(),
      horaFinActoQx: $("#hra_fin_acto_qx").val(),
      horasActoQx: $("#hrs_acto_qx").val(),
      tieneComplicacionMedica: $("#complicacion_intervencion").val(),
      idTipoProgramacion: $("#tipo_programacion").val(),
    }

    return request;
  }

  function validarQueExistaCirujanoPrincipal() {
    let cantidadPrincipales = 0;
    $('#faTableCirujano tbody tr').each(function () {
      const valor = $(this).find('select[name="cirujano_tipo_cirujano"]').val();
      if (parseInt(valor) === 1) {
        cantidadPrincipales++;
      }
    });
    if (cantidadPrincipales === 0) {
      return false;
    }
    return true;
  }
});