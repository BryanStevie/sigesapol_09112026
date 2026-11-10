// $(document).ready(function () {
  let isFirstActiveToogle = false;
  let listCqxProfesionales = [];
  let listCqxTipoProfessionales = [];
  let global_id_tiempo_internamiento_preqx = null;
  let global_id_tiempo_hospitalizacion_postqx = null; 
  let formProgramacionQuirurgica = [
    { id: 1, name: 'id_tiempo_internamiento_preqx', required: true, message: "Seleccione el tiempo de internamiento prequirúrgico en programación quirúrgica" },
    { id: 2, name: 'id_tiempo_hospitalizacion_postqx', required: true, message: "Seleccione el tiempo de hospitalización postquirúrgico en programación quirúrgica" },
    { id: 3, name: 'id_lugar_operacion', required: true, message: "Seleccione el lugar de operación en programación quirúrgica" },
    { id: 4, name: 'hora_inicio_pq', required: true, message: "Ingrese la hora de inicio en programación quirúrgica" },
    { id: 5, name: 'hora_fin_pq', required: true, message: "Ingrese la hora de fin en programación quirúrgica" },
    { id: 6, name: 'tiempo_curacion_cqx', required: false, message: "Ingrese el tiempo de duración de la cirugía en programación quirúrgica" },
    { id: 7, name: 'fecha_probable_pq', required: true, message: "Ingrese la fecha probable de cirugía en programación quirúrgica" },
    { id: 8, name: 'id_prioridad_programacion', required: true, minValue: 1, message: "Ingrese el turno en programación quirúrgica" },
    { id: 9, name: 'id_tipo_cirugia', required: true, message: "Seleccione el tipo de cirugía en programación quirúrgica" },
    { id: 10, name: 'fecha_hora_registro', required: true, message: "Ingrese la fecha y hora de registro" },
    { id: 11, name: 'observacion_suspension', required: false, message: "Ingrese la observacion de la suspension" },  
    { id: 12, name: 'fecha_hora_egreso', required: false, message: "Ingrese la fecha y hora de egreso" }, 
    { id: 13, name: 'motivo_suspension', required: false, message: "Ingrese la observacion de la suspension" }, 
    { id: 14, name: 'motivo_otro', required: false, message: "Ingrese otro motivo de la suspensión" },  

  ];
  let formReevaluarflag2 = [
    { id: 1, name: 'fecha_hora_registro', required: true, message: "Ingrese la fecha y hora de registro" },
    { id: 2, name: 'motivo_reevaluar', required: true, message: "Ingrese el motivo de la reevaluación" },
    { id: 3, name: 'fecha_hora_egreso', required: false, message: "Ingrese la fecha y hora de egreso" }, 
  ];
  let formReevaluarflag3 = [
    { id: 1, name: 'fecha_hora_registro', required: true, message: "Ingrese la fecha y hora de registro" },
    { id: 2, name: 'motivo_suspension', required: true, message: "Seleccione el motivo de la suspensión" },
    { id: 3, name: 'motivo_otro', required: false, message: "Ingrese otro motivo de la suspensión" },
    { id: 4, name: 'motivo_reevaluar', required: true, message: "Ingrese el motivo de la reevaluación" },
    { id: 5, name: 'observacion_suspension', required: true, message: "Ingrese la observacion de la suspension" }, 
    { id: 6, name: 'fecha_hora_egreso', required: false, message: "Ingrese la fecha y hora de egreso" },  
  ];
  
    const meses_es = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    const dias_es = ['Do','Lu','Ma','Mi','Ju','Vi','Sa'];

    // Configuración del datetimepicker
    $.timepicker.regional['es'] = {
        timeOnlyTitle: 'Elija la hora',
        timeText: 'Hora',
        hourText: 'Horas',
        minuteText: 'Minutos',
        secondText: 'Segundos',
        millisecText: 'Milisegundos',
        timezoneText: 'Zona horaria',
        currentText: 'Ahora',
        closeText: 'Cerrar',
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        isRTL: false
    };
    $.timepicker.setDefaults($.timepicker.regional['es']);
    $.datepicker.setDefaults($.datepicker.regional['es']);

    // $('#fecha_hora_registro').datetimepicker({
    //   dateFormat: 'dd-mm-yy',
    //   timeFormat: 'HH:mm',
    //   stepMinute: 1,
    //   controlType: 'select',
    //   minDate: 0,
    //   monthNames: meses_es,
    //   dayNamesMin: dias_es
    // });

    // $('#fecha_hora_egreso').datetimepicker({
    //   dateFormat: 'dd-mm-yy',
    //   timeFormat: 'HH:mm',
    //   stepMinute: 1,
    //   controlType: 'select',
    //   monthNames: meses_es,
    //   dayNamesMin: dias_es
    // });

    $('#fecha_hora_registro').datetimepicker({
      dateFormat: 'dd-mm-yy',
      timeFormat: 'HH:mm',
      stepMinute: 1,
      controlType: 'select',
      maxDate: 0,
      monthNames: meses_es,
      dayNamesMin: dias_es
    });

   $('#fecha_hora_egreso').datetimepicker({
    dateFormat: 'dd-mm-yy',
    timeFormat: 'HH:mm', 
    stepMinute: 1,
    controlType: 'select',
    maxDate: 0,
    monthNames: meses_es,
    dayNamesMin: dias_es,

    onClose: function () {
        validarEgresoVsHospi();
    }
});

function validarEgresoVsHospi() {

    let egreso = $('#fecha_hora_egreso').datetimepicker('getDate');
    let raw = $('#fecha_ingreso_hospi').val();
    if (!raw || !egreso) return;

    let fechaEgreso = new Date(
        egreso.getFullYear(),
        egreso.getMonth(),
        egreso.getDate()
    );

    let f = raw.split(' ')[0].split('-');
    let fechaIngreso = new Date(
        parseInt(f[0]),
        parseInt(f[1]) - 1,
        parseInt(f[2])
    );

    if (fechaEgreso < fechaIngreso) {
        let fechaSolo = raw.split(' ')[0];
        let fe = fechaEgreso.getFullYear() + '-' +
             String(fechaEgreso.getMonth() + 1).padStart(2,'0') + '-' +
             String(fechaEgreso.getDate()).padStart(2,'0');

        Swal.fire({
            icon: 'warning',
            title: 'Fecha inválida',
            html: `
                La fecha de egreso <strong>(${fe})</strong>
                no puede ser menor a la fecha de ingreso
                <strong>(${fechaSolo})</strong> de hospitalización.
            `
        });
        $('#fecha_hora_egreso').val('');
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

  $('#myModalAtencion').on('shown.bs.modal', function () {
    $('[data-toggle="tooltip"]', this).tooltip({ container: 'body' });
  });

  //addAsteriskToLabel();
  showAlertExistsIntervencion();

  // function addAsteriskToLabel() {
  //   let accion =$('input[name="accion"]:checked').val();
  //   let flag = $('[id="flag_qx"]').val();
  //   let form_tipos = [];

  //   if((flag==2 || flag==3) && accion=="reprogramacion"){
  //     form_tipos = formProgramacionQuirurgica;
  //   }else if(flag==2 && accion=="reevaluacion"){
  //     form_tipos = formReevaluarflag2;
  //   }else if(flag==2 && accion=="reevaluacion"){
  //     form_tipos = formReevaluarflag3;
  //   }
  //   console.log(flag);
  //    console.log(accion);
  //    console.log(form_tipos);
     
    
  //   for (const control of form_tipos) {
  //       if (control.required) {
  //         const label = $('label[for="' + control.name + '"]');
  //         const labelText = label.text();
  //         if (!labelText.includes('*')) {
  //             label.html(labelText + ' <span style="color:red;">*</span>');
  //         }
  //       }
  //   }
  // }

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

  function main(id_intervencion_quirurgica) {
    Swal.fire({
      title: '<p style="font-size: 15px; margin-top: 20px">CARGANDO DATOS...</p>',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });
    loadAllData(id_intervencion_quirurgica)
      .then(([cqxProfesionales, cqxTipoProfessionales, cqxDias, buscarAtencionProramacion]) => {
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
        if (buscarAtencionProramacion) {
          //cargarDatosProgramacion(id_intervencion_quirurgica);
          buscarAtencionProramacion(id_intervencion_quirurgica);
        }
        Swal.close();
        reliableSelect($('#id_tiempo_internamiento_preqx'), global_id_tiempo_internamiento_preqx);
        reliableSelect($('#id_tiempo_hospitalizacion_postqx'), global_id_tiempo_hospitalizacion_postqx);
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

  function loadAllData(id_intervencion_quirurgica) {
    return Promise.all([
      Promise.resolve(getCqxProfesionales()),
      Promise.resolve(getCqxTipoProfessionales()),
      Promise.resolve(getCqxDias()),
      Promise.resolve(buscarAtencionProramacion(id_intervencion_quirurgica)) 
    ]);
  }

  function getCqxProfesionales() {
    const id_establecimiento = $("#id_establecimiento_suspension").val();
    const id_consultorio = $("#id_consultorio_suspension").val();
    return $.ajax({
        url: `/api/cqx_profesionales/establecimiento/${id_establecimiento}/consultorio/0`,
        method: "GET",
        dataType: "json"
    });
  }

  function getCqxTipoProfessionales() {
    return $.ajax({
        url: `/api/cqx_tipo_professionales_cirujanos`,
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
            <select class="select-tom" name="cqx_profesional[]" id="cqx_profesional_${indexRow}">${optionsProfesionales}</select>
          </td>
          <td>
            <select class="form-control input-sm" name="cqx_tipo_professional[]" id="cqx_tipo_professional_${indexRow}">${options}</select>
          </td>
          <td><button id="btnRemoveRowCirujano" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
      </tr>`;

    $('#tableCirujanoIntOper tbody').append(newRow);
    $("#tableCirujanoIntOperHeader").removeClass("table-error danger").nextAll('.label-help').remove();
    // $(`#cqx_profesional_${indexRow}`).select2({ width: '100%' });
    const select = new TomSelect("#cqx_profesional_" + indexRow, {
      maxItems: 1,
      plugins: ['clear_button']
    });
    select.focus();

    $('#tableCirujanoIntOper').on('click', '#btnRemoveRowCirujano', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      formProgramacionQuirurgica = formProgramacionQuirurgica.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    formProgramacionQuirurgica.push({id: indexRow, name: `cqx_profesional_${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, message: "Seleccione el profesional que interviene en la operación" });
    formProgramacionQuirurgica.push({id: indexRow, name: `cqx_tipo_professional_${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, message: "Seleccione el tipo de profesional que interviene en la operación" });
  }

  $(document).on('blur', 'input[name="cqx_procedimiento_nombre_pq[]"]', function() { 
        const input = $(this);
        const currentRow = input.closest("tr");
        const idItem = currentRow.find('input[name="cqx_procedure_id_pq[]"]').val();

        if (!idItem) {
            markInputError(input.attr("id"), "Espere los resultados y seleccione un procedimiento");
        }
    });

  function onClickAddRowTableProcedimientosPq() { 
    const lastRowValid = validateLastRowTablePq("tableProcedures_pq");
    if (!lastRowValid) {
        Swal.fire({
            icon: "warning",
            title: '<p style="font-size: 15px">PROCEDIMIENTOS DE INTERVENCIÓN</p>',
            html: '<p class="descripcion-alert" style="font-size: 12px">TIENE QUE RELLENAR TODOS LOS CAMPOS PARA PODER AGREGAR UN NUEVO PROCEDIMIENTO</p>', 
        });
        return null;
    }

    const indexRow = $('#tableProcedures_pq tbody tr').length + 1;

    const newRow = `
      <tr id="tr_procedimientos_${indexRow}">
          <td><input type="text" name="cqx_procedimiento_nombre_pq[]" id="cqx_procedimiento_nombre_pq${indexRow}" class="form-control input-sm"></td>
          <td><button type="button" id="btnRemoveRowProcedure_pq" style="margin-left: 10px" class="btn btn-sm btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
          <td><input type="hidden" name="cqx_procedure_id_pq[]" id="cqx_procedure_id_pq_${indexRow}"></td>
      </tr>`;

    $('#tableProcedures_pq tbody').append(newRow);
    $("#tableProceduresHeader_pq").removeClass("table-error danger").nextAll('.label-help').remove();

    const sexo = String($("#nomsexo").val()).toUpperCase() === "M" ? 1 : 2;
    const id_establecimiento = $("#id_establecimiento").val() || "76";

    // Autocomplete
    $(`#cqx_procedimiento_nombre_pq${indexRow}`).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
        $.ajax({
            url: `/obtener_procedimiento_convenio/${request.term}/${id_establecimiento}/${sexo}/1`,
            type: "GET",
            dataType: "json",
            success: function(data) {
                response(data.map(item => ({ id: item.id,codigo: item.codigo, label: item.label })));
            },
            error: function() { response([]); }			
        });
    },
    select: function(event, ui) {
        const input = $(this);
        const currentRow = input.closest("tr");
        const currentRowId = currentRow.attr("id");

        // SOLO USAR EL CÓDIGO REAL DEL ITEM
        const codigo_puro = ui.item.id;

        // VALIDAR DUPLICADO
        if (existsProcedimiento(codigo_puro, currentRowId)) {
            Swal.fire({
                icon: "warning",
                title: "⚠️Procedimiento duplicado",
                text: "Este procedimiento ya fue agregado."
            });

            input.val("");
            input.removeAttr("data-codigo");
            currentRow.find('input[name="cqx_procedure_id_pq[]"]').val("");

            return false;
        }

        // GUARDAR CÓDIGO PURO
        input.attr("data-codigo", codigo_puro);

        // GUARDAR ID EN HIDDEN
        currentRow.find('input[name="cqx_procedure_id_pq[]"]').val(codigo_puro);

        // MOSTRAR DESCRIPCIÓN COMPLETA
        input.val(ui.item.label);

        const td = input.closest("td");
        td.removeClass("has-error");
        input.removeClass("input-error");
        td.find(".label-help").remove();
    }

    });

    // Limpiar hidden si escriben manual
    $(`#cqx_procedimiento_nombre_pq${indexRow}`).on('input', function() {
        $(`#cqx_procedure_id_pq_${indexRow}`).val("");
    });

    // Validación al perder foco
    

    // Eliminar fila
    $('#tableProcedures_pq').on('click', '#btnRemoveRowProcedure_pq', function(){
        const row = $(this).closest('tr');
        const rowId = row.attr('id');
        formProgramacionQuirurgica = formProgramacionQuirurgica.filter(obj => String(obj.tr_id) !== String(rowId));
        row.remove();
    });

    formProgramacionQuirurgica.push({
        id: indexRow,
        name: `cqx_procedimiento_nombre_pq${indexRow}`,
        required: true,
        tr_id: `tr_procedimientos_${indexRow}`,
        message: "Ingrese el nombre del procedimiento en programación quirúrgica"
    });
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
          <td><button id="btnRemoveRowTablePreQx_pq" type="button" style="margin-left: 10px" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
      </tr>`;

    // $('#tableInterconsultaPreQx_pq tbody').append(newRow);
    // $("#tableInterconsultaPreQxHead_pq").removeClass("table-error danger").nextAll('.label-help').remove();
    $(`#preqx_id_servicio_pq_${indexRow}`).select2({ width: '100%' });

    // $('#tableInterconsultaPreQx_pq').on('click', '#btnRemoveRowTablePreQx_pq', function(){
    //   const row = $(this).closest('tr');
    //   const rowId = row.attr('id');
    //   formProgramacionQuirurgica = formProgramacionQuirurgica.filter(obj => String(obj.tr_id) !== String(rowId));
    //   row.remove();
    // });

    // formProgramacionQuirurgica.push({id: indexRow, name: `preqx_id_servicio_pq_${indexRow}`, required: true, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, message: "Seleccione el servicio en interconsulta prequirúrgica en programación quirúrgica" });
    // formProgramacionQuirurgica.push({id: indexRow, name: `preqx_motivo_interconsulta_${indexRow}`, required: false, tr_id: `tr_cirujanos_intervienen_operacion_${indexRow}`, message: "Ingrese el motivo de la interconsulta en programación quirúrgica en programación quirúrgica" });
  }

    function validateLastRowTablePq(tableId) {

        let lastRow = $(`#${tableId} tbody tr:last`);
        let isValid = true;

        // validar selects tomSelect
        lastRow.find('select[name="cqx_profesional[]"]').each(function () {

            let val = $(this).val(); // TomSelect actualiza el valor REAL

            clearInputError($(this).attr("id"));

            if (!val || val === "") {
                markInputError($(this).attr("id"));
                isValid = false;
            }
        });

        // validar cargo (select normal)
        lastRow.find('select[name="cqx_tipo_professional[]"]').each(function () {

            clearInputError($(this).attr("id"));

            if ($(this).val() === "") {
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
    const idIntervencion = $('#id_intervencion_suspension').val(); // o como lo tengas
    const idPrestacion = $('#id_prestacion_suspension').val();
    const formData = buildFormDataPq();

    $.ajax({
    type: "POST",
      url: `/actualizar-suspension-general-cqx/${idIntervencion}/${idPrestacion}`,
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function () {
        $("#btnGuardarSuspension").prop('disabled', true);
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

            $('#myModalAtencion').modal('hide');

            const message = response.message || "La información fue registrada correctamente.";

            Swal.fire({
                icon: "success",
                title: "✔ OPERACIÓN COMPLETADA",
                html: `
                    <div style="font-size:16px; margin-top:10px;">
                        ${message}
                    </div>
                `,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "ENTENDIDO",
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                location.reload();
            });
        }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.hideLoading();
      $("#btnGuardarSuspension").prop('disabled', false);
      const message = jqXHR.responseJSON.message || "Ocurrio un error al registrar, intentelo en unos minutos";
      Swal.fire({
        icon: 'error',
        title: "INTERVENCIÓN QUIRÚRGICA",
        html: `<p>${message}</p>`,
        confirmButtonText: "ENTENDIDO",
      });
    })
    .always(function () {
      $("#btnGuardarSuspension").prop('disabled', false);
      Swal.hideLoading();
    });
  }

  function validateFormProgramacionQuirurgicaPq(tipo) {
    let controlsError = [];
    let arrayMessageError = [];
    let motivoSuspension = $('#motivo_suspension').val();
    for (const control of formProgramacionQuirurgica) {
      // Regla dinámica: observacion_suspension solo obligatorio si tipo = 3
        let isRequired = control.required;
        if (control.name === 'observacion_suspension') {
            isRequired = (tipo == 3); // solo obligatorio para tipo 3 q es el flag validado
        }
        if (control.name === 'motivo_suspension') {
            isRequired = (tipo == 3); // solo obligatorio para tipo 3 q es el flag validado
        }
        if (control.name === 'motivo_otro' && motivoSuspension == "8") {
            isRequired = (tipo == 3); // solo obligatorio para tipo 3 q es el flag validado
        }

        if (isRequired) {
            const inputElement = $(`#${control.name}`);
            inputElement.nextAll('.label-help').remove();

            let parentDiv = null;
            if (control.tr_id) {
                parentDiv = inputElement.closest('td'); 
            } else {
                parentDiv = inputElement.closest('.form-group'); // más seguro que 'div'
            }

            if (parentDiv) {
                const rawValue = inputElement.val();
                const value = (rawValue === null || rawValue === undefined ? '' : rawValue)
                .toString()
                .trim();

                if (value === "") {
                    parentDiv.addClass('has-error');
                    inputElement.after(`<span class="label-help">campo requerido</span>`);
                    controlsError.push(control);
                    arrayMessageError.push(control.message ? `${control.message}<br>` : "Campo requerido<br>");
                } else if (control.minValue && parseFloat(value) <= 0) {
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

    return arrayMessageError;
  }
  function validateFormReevaluacionFlag2Pq() {
    let controlsError = [];
    let arrayMessageError = [];
    for (const control of formReevaluarflag2) {
      if (control.required) {
        const inputElement = $(`#${control.name}`);
        inputElement.nextAll('.label-help').remove();
        let parentDiv = null;
        if (control.tr_id) {
          parentDiv = inputElement.closest('td'); 
        } else {
          parentDiv = inputElement.closest('div'); 
        }
        if (parentDiv) {
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
    return arrayMessageError;
  }
  function validateFormReevaluacionFlag3Pq() { 
      let arrayMessageError = [];
      let motivoSuspension = $('#motivo_suspension').val();

      for (const control of formReevaluarflag3) {

          let isRequired = control.required;
          if (control.name === "motivo_otro") {
              isRequired = (motivoSuspension == "8");
          }

          if (isRequired) {
              const inputElement = $(`#${control.name}`);
              inputElement.nextAll('.label-help').remove();
              inputElement.closest('.form-group, td, div').removeClass('has-error');

              const parentDiv = inputElement.closest('.form-group, td, div');
              if (!parentDiv.length) continue;

              const value = (inputElement.val() || '').trim();

              if (value === "") {
                  parentDiv.addClass('has-error');
                  inputElement.after('<span class="label-help">campo requerido</span>');
                  arrayMessageError.push(control.message ? `${control.message}<br>` : "Campo requerido<br>");
              } 
              else if (control.minValue !== undefined && parseFloat(value) <= control.minValue) {
                  parentDiv.addClass('has-error');
                  inputElement.after(`<span class="label-help">Ingrese un número mayor a ${control.minValue}</span>`);
                  arrayMessageError.push(control.message ? `${control.message}. El valor debe ser mayor a ${control.minValue}<br>` : "");
              }
          }
      }

      return arrayMessageError;
  }

  function buildFormDataPq() {
    const formData = new FormData();
    var valorAccion = $('input[name="accion"]:checked').val() || null;
    formData.append("accion",  valorAccion);
    var valorAccion2 = $('input[name="accion2"]:checked').val() || null;
    formData.append("accion2",  valorAccion2);

    formData.append("flag_qx",  $('[id="flag_qx"]').val() || null);
    formData.append("id_consultorio_suspension",  $('[id="id_consultorio_suspension"]').val() || null);
    /****solo cuando es revaluar  y flag =2 o 3*/
    formData.append("motivo_reevaluar", $("#motivo_reevaluar").val() || null);
    /**********FLAG= 2******/
    /***solo si accion es reprogramar cuando el flag es=2 o 3**/
    formData.append("id_cqx_cita", $("#id_cqx_cita").val() || null);
    const cirujanos = getValuesRowByTablePq("tableCirujanoIntOper") || [];
    const procedimientos = getValuesRowByTablePq("tableProcedures_pq") || [];
    formData.append("fecha_hora_registro", $("#fecha_hora_registro").val() || null);
    formData.append("id_tipo_cirugia", $("#id_tipo_cirugia").val() || null);
    formData.append("id_prioridad_programacion", $("#id_prioridad_programacion").val() || null);
    formData.append("idCita", $("#id_prioridad_programacion").val() || null);
    formData.append("valueToogleRequiereInternPreQx_pq", $("#valueToogleRequiereInternPreQx_pq").val() === "1" ? 1 : 0);
    formData.append("id_tiempo_internamiento_preqx", $("#id_tiempo_internamiento_preqx").val() || null);
    formData.append("valueToogleDiasHospitPostQx_pq", $("#valueToogleDiasHospitPostQx_pq").val() === "1" ? 1 : 0);
    formData.append("id_tiempo_hospitalizacion_postqx", $("#id_tiempo_hospitalizacion_postqx").val() || null);
    formData.append("id_lugar_operacion", $("#id_lugar_operacion").val() || null);
    formData.append("tiempo_curacion_cqx", $("#tiempo_curacion_cqx").val() || null);
    formData.append("hora_inicio_pq", $("#hora_inicio_pq").val() || null);
    formData.append("hora_fin_pq", $("#hora_fin_pq").val() || null);
    formData.append("fecha_probable_pq", $("#fecha_probable_pq").val() || null);
    formData.append("fecha_probable_pq_anterior", $("#fecha_probable_pq_anterior").val() || null);
    formData.append("cirujanos", JSON.stringify(cirujanos));
    formData.append("procedimientos", JSON.stringify(procedimientos));
    formData.append("fecha_hora_egreso", $("#fecha_hora_egreso").val() || null);
    

    let files = $('#fileMopri_pq')[0].files;
    for (let i = 0; i < files.length; i++) {
      formData.append("fileMopri_pq[]", files[i]);
    }
    /***fin solo si accion es reprogramar**/

    /**********solo cuando el FLAG=3******/
    formData.append("motivo_suspension", $("#motivo_suspension").val() || null);
    formData.append("motivo_otro", $("#motivo_otro").val() || null);
    formData.append("observacion_suspension", $("#observacion_suspension").val() || null);
    
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
// Cargar cirujanos en la tabla
    function cargarCirujanosTabla(cirujanos) {
        $('#tableCirujanoIntOper tbody').empty();

        cirujanos.forEach((cirujano, index) => {
            const idx = index + 1;

            // Opciones tipo profesional
            let optionsTipo = '';
            listCqxTipoProfessionales.forEach(tipo => {
                const selected = tipo.id == cirujano.tipo_professional_id ? 'selected' : '';
                optionsTipo += `<option value="${tipo.id}" ${selected}>${tipo.descripcion}</option>`;
            });

            // Opciones profesionales
            let optionsProfesionales = '<option value="">--- Seleccionar ---</option>';
            listCqxProfesionales.forEach(prof => {
                console.log(cirujano.id_cqx_cita);
                
                const selected = prof.id == cirujano.id_medico ? 'selected' : '';
                optionsProfesionales += `<option value="${prof.id}" ${selected}>${prof.paterno} ${prof.materno}, ${prof.nombre}</option>`;
            });

            const row = `
            <tr id="tr_cirujanos_${idx}">
                
                <!--Hidden para saber si es existente o nuevo -->
                <input type="hidden" 
                    name="id_cirujano_existente[]" 
                    value="${cirujano.id_medico ? cirujano.id_medico : ''}">

                <td>
                    <select id="cqx_profesional_${idx}" name="cqx_profesional[]" class="select-tom">
                        ${optionsProfesionales}
                    </select>
                </td>
                <td>
                    <select id="cqx_tipo_professional_${idx}" name="cqx_tipo_professional[]" class="form-control">
                        ${optionsTipo}
                    </select>
                </td>
                <td>
                    <button type="button" 
                            class="btn btn-xs btn-danger btnRemoveRowCirujano"
                            data-id="${cirujano.id}">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;

            $('#tableCirujanoIntOper tbody').append(row);

            new TomSelect(`#cqx_profesional_${idx}`, {
                maxItems: 1,
                plugins: ['clear_button']
            });
        });
    }
    // Función principal que asegura cargar listas primero
    function initCirujanosTabla(cirujanos) {
        $.when(getCqxProfesionales(), getCqxTipoProfessionales()).done(function() {
            cargarCirujanosTabla(cirujanos);
        });
    }
    function buscarAtencionProramacion(id_intervencion_quirurgica) {
        $.ajax({
            url: "/buscar-datos-programacion-qx",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_intervencion: id_intervencion_quirurgica
            },
            success: function(resp) {
                
                let data = resp.programacion;

                let fecha_solicitud = data.fecha_solicitud;
                let soloFecha = fecha_solicitud.split(' ')[0];
                let partes = soloFecha.split('-');
                let fechaMinima = new Date(
                    parseInt(partes[0]),
                    parseInt(partes[1]) - 1,
                    parseInt(partes[2])
                );

                $('#fecha_probable_pq').datepicker({
                    dateFormat: 'dd-mm-yy',
                    autoclose: true,
                    changeMonth: true,
                    changeYear: true,
                    minDate: fechaMinima
                });

                // TIPO DE CIRUGÍA
                $("#id_tipo_cirugia").val(data.id_tipo_cirugia);
                $("#id_cqx_cita").val(data.id_cita);

                // TURNO QUIRÚRGICO
                $("#id_prioridad_programacion").val(data.turno);
                
                 global_id_tiempo_internamiento_preqx = data.id_tiempo_internamiento_preqx;
                 global_id_tiempo_hospitalizacion_postqx = data.id_tiempo_hospitalizacion_postqx;
                 // Mostrar toggles y asignar selects la primera vez
                if (data.is_requiere_internamiento_preqx == 1) {
                    $("#toogleRequiereInternPreQx_pq").addClass("active");
                    $("#valueToogleRequiereInternPreQx_pq").val(1);
                    $("#formControlTiempoInternamiento").show();

                    // requestAnimationFrame(() => {
                    //     $("#id_tiempo_internamiento_preqx")
                    //         .val(String(global_id_tiempo_internamiento_preqx))
                    //         .trigger("change");
                    // });
                } else {
                    $("#toogleRequiereInternPreQx_pq").removeClass("active");
                    $("#valueToogleRequiereInternPreQx_pq").val(0);
                    $("#formControlTiempoInternamiento").hide();
                }

                if (data.is_hospitalizacion_postqx == 1) {
                    $("#toogleDiasHospitPostQx_pq").addClass("active");
                    $("#valueToogleDiasHospitPostQx_pq").val(1);
                    $("#formControlTiempoHospitalizacion").show();

                    // requestAnimationFrame(() => {
                    //     $("#id_tiempo_hospitalizacion_postqx")
                    //         .val(String(global_id_tiempo_hospitalizacion_postqx))
                    //         .trigger("change");
                    // });
                } else {
                    $("#toogleDiasHospitPostQx_pq").removeClass("active");
                    $("#valueToogleDiasHospitPostQx_pq").val(0);
                    $("#formControlTiempoHospitalizacion").hide();
                }
                reliableSelect($('#id_tiempo_internamiento_preqx'), data.id_tiempo_internamiento_preqx);
                reliableSelect($('#id_tiempo_hospitalizacion_postqx'), data.id_tiempo_hospitalizacion_postqx);


                // LUGAR DE OPERACIÓN
                $("#id_lugar_operacion").val(data.id_lugar_operacion);

                // HORAS
                $("#hora_inicio_pq").val(data.hora_inicio_prog_qx);
                $("#hora_fin_pq").val(data.hora_fin_prog_qx);

                // DURACIÓN
                $("#tiempo_curacion_cqx").val(data.emergencia_duracion_cirugia);

                // FECHA PROBABLE
                $("#fecha_probable_pq").val(data.fecha_probable);
                $("#fecha_probable_pq_anterior").val(data.fecha_probable);

                // --- CIRUJANOS ---
                // Llamar a la función de cargar cirujanos
                if (resp.cirujanos && resp.cirujanos.length > 0) {
                    initCirujanosTabla(resp.cirujanos);
                } else {
                   initCirujanosTabla([{
                        id: null,
                        id_medico: null,
                        nombre: '',
                        paterno: '',
                        materno: '',
                        tipo_professional_id: null
                    }]);
                }


                // =============================
                // CARGAR PROCEDIMIENTOS
                // =============================
                let tbodyProc = $("#tableProcedures_pq tbody");
                tbodyProc.empty();

                if (resp.procedimientos && resp.procedimientos.length > 0) {

                    resp.procedimientos.forEach(function(p, index) {
                        let indexRow = index + 1;

                        const newRow = `
                            <tr id="tr_procedimientos_${indexRow}">
                                
                                <!-- Hidden para identificar si es existente o nuevo -->
                                <input type="hidden" 
                                    name="id_procedimiento_existente[]" 
                                    value="${p.id ? p.id : ''}">

                                <td>
                                    <input type="text" 
                                        name="cqx_procedimiento_nombre_pq[]" 
                                        id="cqx_procedimiento_nombre_pq${indexRow}" 
                                        class="form-control input-sm"
                                        data-codigo="${p.codigo_cpms}"
                                        value="${p.codigo_cpms} - ${p.descripcion_cpms}">
                                </td>

                                <td>
                                    <button type="button" 
                                        class="btn btn-sm btn-danger btn-xs btnRemoveRowProcedure_pq" 
                                        data-id="${p.id}" 
                                        style="margin-left:10px">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>

                                <td>
                                    <!-- ID del procedimiento seleccionado (CPMS) -->
                                    <input type="hidden" 
                                        name="cqx_procedure_id_pq[]" 
                                        id="cqx_procedure_id_pq_${indexRow}" 
                                        value="${p.id_procedimiento}">
                                </td>
                            </tr>`;

                        tbodyProc.append(newRow);

                        // Activar autocomplete
                        inicializarAutocompleteProcedimientos(indexRow);
                    });
                }
                
                let listFiles = $("#fileList_pq");
                listFiles.empty();

                let mopri = resp.formatos_mopri;
                

                if (mopri) {

                    let url = mopri.ruta + "" + mopri.nombre_archivo;
                    let ext = mopri.nombre_archivo.split('.').pop().toLowerCase();
                    console.log(url);
                    
                    let icon = (ext === 'pdf')
                        ? '<i class="fa fa-file-pdf-o text-danger"></i>'
                        : '<i class="fa fa-file-image-o text-info"></i>';

                    listFiles.append(`
                        <li class="list-group-item" id="mopri_item">
                            ${icon}
                            <strong style="margin-left:8px">${mopri.nombre_simple}</strong>

                            <button class="btn btn-primary btn-xs pull-right btnVerMopri" 
                                    data-url="${url}" 
                                    data-ext="${ext}"
                                    style="margin-left:5px">
                                <i class="fa fa-eye"></i>
                            </button>

                            <button type="button" class="btn btn-danger btn-xs pull-right" id="btnEliminarMopri" data-id="${mopri.id}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </li>
                    `);

                } else {
                    listFiles.append(`<li class="list-group-item text-warning">No hay archivo MOPRI registrado</li>`);
                }

            }
        });
    }

    function inicializarAutocompleteProcedimientos(indexRow) {
        const sexo = String($("#nomsexo").val()).toUpperCase() === "M" ? 1 : 2;
        const id_establecimiento = $("#id_establecimiento").val() || "76";

        $("#cqx_procedimiento_nombre_pq" + indexRow).autocomplete({
            minLength: 1,
            delay: 400,
            source: function(request, response) {
                $.ajax({
                    url: `/obtener_procedimiento_convenio/${request.term}/${id_establecimiento}/${sexo}/1`,
                    type: "GET",
                    dataType: "json",
                    success: function(data)  {
                        response(data.map(function(item) {
                            return { id: item.id, label: item.label };
                        }));
                    }
                });
            },
            select: function(event, ui) {
                $(this).val(ui.item.label);
                $("#cqx_procedure_id_pq_" + indexRow).val(ui.item.id);
            }
        });

        $("#cqx_procedimiento_nombre_pq" + indexRow).on("input", function() {
            $("#cqx_procedure_id_pq_" + indexRow).val("");
        });
    }

    $("#btnMopriQx_pq").on("click", function() {
        $("#fileMopri_pq").click();
    });

    $(document).on("click", "#btnEliminarMopri", function() {

        let idFormato = $(this).data("id");
        let modal = $(this).closest('.modal');

        bootbox.confirm({
            message: "¿Desea eliminar el archivo MOPRI?",
            buttons: {
                confirm: { label: 'Sí', className: 'btn-danger' },
                cancel: { label: 'No', className: 'btn-default' }
            },
            callback: function(result) {
                if (result) {

                    $.ajax({
                        url: "/eliminar-formato-mopri",
                        type: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: idFormato
                        },
                        success: function(r) {
                            $("#mopri_item").remove();

                            toastr.success("Archivo MOPRI eliminado correctamente");
                        }
                    });

                }
            }
        }).on('hidden.bs.modal', function () {
            modal.modal('show');
            $('body').addClass('modal-open');
        });
    });

    $(document).on("click", ".btnVerMopri", function () {

        let url = $(this).data("url");
        let ext = $(this).data("ext");
        let html = "";

        // Contenido para pdf o imágenes
        if (ext === "pdf") {
            html = `
                <embed src="${url}" 
                    type="application/pdf"
                    style="width:100%; height:100%; border:none;">
            `;
        } else if (["jpg", "jpeg", "png"].includes(ext)) {
            html = `
                <img src="${url}" 
                    style="max-width:100%; max-height:100%; display:block; margin:auto;">
            `;
        } else {
            html = `
                <p class="text-danger text-center" style="padding:20px;">
                    No se puede visualizar este formato. Descárguelo.
                </p>`;
        }

        $("#contenedorMopri").html(html);

        // ABRIR EL MODAL HIJO
        $("#modalVerMopri").modal("show");

        // AÑADIR modal-open PARA EVITAR QUE SE BLOQUEE EL PADRE
        $('body').addClass('modal-open');
    });

    // ⭐ CUANDO SE CIERRA EL MODAL HIJO, REACTIVAR modal-open SI HAY OTRO MODAL ACTIVO
    $('#modalVerMopri').on('hidden.bs.modal', function () {
        if ($('.modal.in').length) {
            $('body').addClass('modal-open');
        }
    });



    $(document).on("click", ".btnRemoveRowProcedure_pq", function () {
        let idProcedimiento = $(this).data("id");
        let row = $(this).closest("tr");
        let modal = $(this).closest('.modal');

        bootbox.confirm({
            message: "¿Desea eliminar este procedimiento?",
            buttons: {
                confirm: { label: "Sí", className: "btn-danger" },
                cancel: { label: "No", className: "btn-default" }
            },
            callback: function (result) {

                if (result) {

                    $.ajax({
                        url: "/eliminar-procedimiento-cqx",
                        type: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id_procedimiento: idProcedimiento
                        },
                        success: function (resp) {

                            if (resp.status === "ok") {
                                row.remove();
                            } else {
                                bootbox.alert("No se pudo eliminar.");
                            }
                        }
                    });

                }

            }
        }).on('hidden.bs.modal', function () {
            modal.modal('show');
            $('body').addClass('modal-open');
        });
    });

    $(document).on("click", ".btnRemoveRowCirujano", function () {
        let idCirujano = $(this).data("id");
        let row = $(this).closest("tr");
        let modal = $(this).closest('.modal');

        bootbox.confirm({
            message: "¿Desea eliminar este profesional de la programación?",
            buttons: {
                confirm: { label: "Sí", className: "btn-danger" },
                cancel: { label: "No", className: "btn-default" }
            },
            callback: function (result) {

                if (result) {
                    $.ajax({
                        url: "/eliminar-profesional-cqx",
                        type: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id_cirujano: idCirujano
                        },
                        success: function (resp) {
                            if (resp.status === "ok") {
                                row.remove();
                            } else {
                                bootbox.alert("No se pudo eliminar.");
                            }
                        }
                    });

                }

            }
        }).on('hidden.bs.modal', function () {
            modal.modal('show');
            $('body').addClass('modal-open');
        });
    });

    function validarReevaluacion() {
        if ($("#opt_reevaluar").is(":checked") || $("#opt_reevaluar2").is(":checked")) {

            let motivo = $("#motivo_reevaluar").val().trim();

            if (motivo.length === 0) {

                bootbox.alert({
                    message: "<b>Debe detallar el motivo de reevaluación.</b>",
                    size: "small",
                    backdrop: true
                });

                // marcar borde rojo como validación
                $("#motivo_reevaluar").css("border", "1px solid red");

                return false; // evitar continuar
            }

            // limpiar borde si está lleno
            $("#motivo_reevaluar").css("border", "");
        }

        return true; // todo ok
    }

    function existsProfesionalSelected(value, currentSelectId) {
        let exists = false;

        $('select[name="cqx_profesional[]"]').each(function () {
            if ($(this).attr("id") !== currentSelectId) { // no comparar contra sí mismo
                if ($(this).val() == value && value !== "") {
                    exists = true;
                }
            }
        });

        return exists;
    }

    $(document).on("change", 'select[name="cqx_profesional[]"]', function () {

        const selectedValue = $(this).val();
        const selectId = $(this).attr("id");

        if (selectedValue && existsProfesionalSelected(selectedValue, selectId)) {

            // alerta
            Swal.fire({
                icon: "warning",
                title: "Cirujano ya seleccionado",
                text: "Este cirujano ya fue agregado. Seleccione otro.",
            });

            // limpiar TomSelect correctamente
            let tom = $(this)[0].tomselect;
            tom.clear();

            return false;
        }
    });

    /************PROCEDIMIENTOS ***/
    // Función para verificar si un procedimiento ya fue seleccionado
   function existsProcedimiento(codigo, currentRowId) {
        let exists = false;

        $('input[name="cqx_procedimiento_nombre_pq[]"]').each(function () {
            const row = $(this).closest("tr");

            // saltar la fila actual
            if (row.attr("id") === currentRowId) return;
            const codigoRow = $(this).attr("data-codigo");
            // si coincide con el código puro → duplicado
            if (codigoRow && codigoRow == codigo) {
                exists = true;
                return false; // salir del each
            }
        });

        return exists;
    }

let esFullscreen = false;
$(document).on("click", "#btnFullscreen", function () {

    if (!esFullscreen) {
        // Activar Fullscreen
        $("#modalVerMopri .modal-dialog").css({
            width: "100%",
            maxWidth: "100%",
            height: "100%",
            margin: "0",
            padding: "0"
        });

        $("#modalVerMopri .modal-content").css({
            height: "100vh",
            borderRadius: "0"
        });

        $("#contenedorMopri").css({
            height: "calc(100vh - 60px)"
        });

        // Cambiar botón
        $("#btnFullscreen").text("⛶ Cerrar Vista Completa")
                           .removeClass("btn-success")
                           .addClass("btn-danger");

        // Ocultar botón X
        $("#btnCloseModal").hide();

        esFullscreen = true;

    } else {
        // Salir de Fullscreen
        $("#modalVerMopri .modal-dialog").css({
            width: "",
            maxWidth: "",
            height: "",
            margin: "",
            padding: ""
        });

        $("#modalVerMopri .modal-content").css({
            height: "",
            borderRadius: ""
        });

        $("#contenedorMopri").css({
            height: "550px"
        });

        // Restaurar botón
        $("#btnFullscreen").text("⛶ Pantalla Completa")
                           .removeClass("btn-danger")
                           .addClass("btn-success");

        // Mostrar botón X
        $("#btnCloseModal").show();

        esFullscreen = false;
    }
});

function setSelectValueWhenVisible($select, value) {
    if ($select.is(':visible')) {
        $select.val(String(value)).trigger('change');
    } else {
        // Esperar a que se muestre
        let observer = new MutationObserver(() => {
            if ($select.is(':visible')) {
                $select.val(String(value)).trigger('change');
                observer.disconnect();
            }
        });
        observer.observe($select.parent()[0], { attributes: true, attributeFilter: ['style', 'class'] });
    }
}

function reliableSelect($select, value) {
    const applyValue = () => {
        if ($select.find(`option[value='${value}']`).length) {
            $select.val(String(value)).trigger('change');
            return true;
        }
        return false;
    };

    if (!applyValue()) {
        let attempts = 0;
        const interval = setInterval(() => {
            attempts++;
            if (applyValue() || attempts > 20) {
                clearInterval(interval);
            }
        }, 50);
    }
}












