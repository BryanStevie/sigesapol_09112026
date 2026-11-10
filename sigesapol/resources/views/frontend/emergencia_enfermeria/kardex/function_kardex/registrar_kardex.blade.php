<script>
$(document).ready(function () {

  let form = {
    atencion: [
      {id: 1, name: "mkr_turno", container_div: "mkr_turno_div", required: true, tab: null},  
    ],
    examenes: [],
    cuidado: [
      {id: 1, name: "control_funciones_vitales", container_div: "control_funciones_vitales_div", required: false, tab: "mkr_tab_cuidado_enfermero"},
      {id: 2, name: "barrera_hematoencefalica", container_div: "barrera_hematoencefalica_div", required: false, tab: "mkr_tab_cuidado_enfermero"},
      {id: 3, name: "cabecera", container_div: "cabecera_div", required: false, tab: "mkr_tab_cuidado_enfermero"},
      {id: 4, name: "id_oxigeno_terapia", container_div: "oxigenoterapia_div", required: true, tab: "mkr_tab_cuidado_enfermero"},
      {id: 5, name: "oxigenoterapia_cantidad", container_div: "oxigenoterapia_div", required: true, tab: "mkr_tab_cuidado_enfermero"},
      {id: 6, name: "movilización", container_div: "movilización_div", required: false, tab: "mkr_tab_cuidado_enfermero"},
      {id: 7, name: "hemoglucotest_cuidado_enfermero_kardex", container_div: "hemoglucotest_cuidado_enfermero_kardex_div", required: true, tab: "mkr_tab_cuidado_enfermero"},
    ],
    interconsulta: [],
  }
  
  let flagActionForm = 1; // 1 = CREATE, 2 = EDIT

  $('#modalKardex').on('shown.bs.modal', async function () {
    try {
      const kardex_enfermerias_id = $("#id").val();
      $("#btnShowFormMedicamento").html('<i class="fa fa-plus"></i> MEDICAMENTO');
      Swal.fire({
        html: '<h5>OBTENIENDO DATOS DEL KARDEX...</h5>',
        showLoaderOnConfirm: true,
        onOpen: () => {
            Swal.showLoading();
        }
      });

      clearTableViewMedicamentos();

      form.examenes = [];
      form.interconsulta = [];

      handlerTableMedicamentos(kardex_enfermerias_id);

      await buildOptionsSelectOxigenoterapia();
      
      if (kardex_enfermerias_id) {
        await handlerEdit();
      } else {
        flagActionForm = 1;
        $("#flag_action_form").val("1");

        const prestacionId = $('#id_prestacion').val();
        if (prestacionId && parseInt(prestacionId) > 0) {
          const examenesLaboratorio = await getExamenesLaboratorio(prestacionId, 2);
          buildTableAuxiliaryExam(examenesLaboratorio, 2)

          const examenesImagen = await getExamenesLaboratorio(prestacionId, 3);
          buildTableAuxiliaryExam(examenesImagen, 3);

          const interconsultas = await getInterconsultas(prestacionId);
          buildTableInterconsultas(interconsultas);
        }
      
        const id_asegurado = $("#id_asegurado").val();
        const latestTera = await getLatestIndiciacionTera(id_asegurado);
        if (latestTera && latestTera.indicacion_terapeutica) {
          $("#mkr_tratamiento").val(latestTera.indicacion_terapeutica);
        }
      }
    } catch (error) {
      Swal.fire({
        title: "ERROR KARDEX ENFERMERIA",
        html: `<h5>${error}</h5>`,
        showLoaderOnConfirm: true,
        onOpen: () => {
            Swal.showLoading();
        }
      });
    } finally {
      Swal.close();
      Swal.hideLoading();
    }
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    const activatedTabHref = $(e.target).attr('href');
    if (activatedTabHref === '#tabHistoryTratamiento') {
      const id_asegurado = $("#id_asegurado").val();
      if (id_asegurado && parseInt(id_asegurado) > 0) {
        getHistoryTratamiento(id_asegurado);
      }
    }
  });

  function getHistoryTratamiento(id_asegurado) {
    const renderTableHistory = $('#tableHistoryTratamiento').DataTable();
    if ($.fn.DataTable.isDataTable('#tableHistoryTratamiento') ) {
        $('#tableHistoryTratamiento').DataTable().destroy();
        $('#tableHistoryTratamiento tbody').empty();
    }
    $('#tableHistoryTratamiento').DataTable( {
        processing: true,
        serverSide: true,
        searching: false,
        paging: false,
        ordering: false,
        retrieve: false,
        destroy: true,
        resetPaging: false,
        language: {
            "processing": "Buscando tratamientos..."
        },
        ajax: {
            url: `/api/obtener-terapeuticas-asegurado/${id_asegurado}`,
            type: 'GET',
            dataSrc: function (json) {
              return json;
            },
            error: function (xhr, status, error) {
              console.error("Error en la solicitud AJAX:", status, error);
            }
        },
        columns: [
            { 
                data: 'prestacion_id',
                width: '10%',
                className: 'text-center dt-center',
            },
            { 
                data: null,
                width: '10%',
                className: 'dt-center',
                render: function (data, type, row) {
                  return `<span>${row.prestacion_fecha_atencion}</span>`;
                },
            },
            { 
                data: null,
                width: '10%',
                className: 'dt-center',
                render: function (data, type, row) {
                  return `<span>${row.prestacion_hora_atencion}</span>`;
                },
            },
            { 
                data: 'medico_nombre',
                width: '20%',
                className: 'text-center dt-center',
            },
            { 
                data: 'indicacion_terapeutica',
                width: '50%',
                className: 'text-center dt-center',
            },
        ]
    });
  }

  async function handlerEdit() {
    flagActionForm = 2;
    $("#flag_action_form").val("2");
    form.examenes = [];
    form.interconsulta = [];

    const kardexEnfermeria = await getKardexEnfermeriasById();
    setForms(kardexEnfermeria)

    const kardex_medicamentos_enfermerias = await getKardexMedicamentosEnfermerias();
    buildTableViewMedicamentos(kardex_medicamentos_enfermerias);

    $("#btnShowFormMedicamento").html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> EDITAR MEDICAMENTOS');
  }

  $("#btnAddRowTableExamAuxLab").on("click", () => {
    addRowTableExamAuxLab();
  });

  $("#btnAddRowTableExamAuxImag").on("click", () => {
    addRowTableExamAuxImag();
  });

  $("#btnAddRowInterconsulta").on("click", () => {
    addRowTableInterconsulta();
  });

  $("#btnShowFormMedicamento").on("click", async () => {
    try {
      const flag_action_form = $("#flag_action_form").val();
      if (parseInt(flag_action_form) === 1) {
        if (arrayMedicamentosKardex && arrayMedicamentosKardex.length > 0) {
          $("#flag_action_form_medicamento").val("2");
        }
      } else {
        $("#flag_action_form_medicamento").val("2");
      }

      $("#modal_kardex_formulario_medicamento").modal('show');
      $("#modal_kardex_formulario_medicamento_title").html(flag_action_form === 1 ? "REGISTRAR MEDICAMENTOS KARDEX" : "EDITAR MEDICAMENTOS KARDEX" );

      if (flag_action_form === FORM_ACTION_EDIT_ID) {
        Swal.fire({
          title: "KARDEX ENFERMERIA",
          html: '<h5>OBTENIENDO DATOS DEL KARDEX...</h5>',
          showLoaderOnConfirm: true,
          onOpen: () => {
              Swal.showLoading();
          }
        });

        const kardex_medicamentos_enfermerias = await getKardexMedicamentosEnfermerias();
        buildTableViewMedicamentos(kardex_medicamentos_enfermerias);
      }
    } catch (error) {
      Swal.fire({
        title: "ERROR KARDEX ENFERMERIA",
        html: `<h5>${error || "ERROR"}</h5>`,
        showLoaderOnConfirm: true,
        onOpen: () => {
            Swal.showLoading();
        }
      });
    } finally {
      Swal.hideLoading();
      Swal.close();
    }
  });

  $("#id_oxigeno_terapia").change(function () {
    const value = $(this).val();
    if (value && value.length > 0) {
      if (parseInt(value) === 1503) {
        form.cuidado[4].required = false;
        $("#oxigenoterapia_cantidad").val("");
        $('#oxigenoterapia_cantidad').attr('readonly', true);
        clearInputError(form.cuidado[4].name, form.cuidado[4].container_div);
      } else {
        form.cuidado[4].required = true;
        $('#oxigenoterapia_cantidad').removeAttr('readonly');
      }
    } else {
      form.cuidado[4].required = true;
      $("#oxigenoterapia_cantidad").val("");
      $('#oxigenoterapia_cantidad').attr('readonly', true);
    }
  });

  $("#btnSave").on("click", () => {
    onSubmitForm();
  })

  $("#btnCancel").on("click", () => {
    Swal.fire({
      title: '¿Estás seguro?',
      text: "Si cancelas, perderás los cambios realizados.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, cancelar!'
    }).then((result) => {
      if (result.isConfirmed) {
         onCloseModalRegister();
         clearFormKardex();
      }
    })
  })

  $("#btnCloseModal").on("click", () => {
    clearFormMedicamentos();
    clearFormKardex();
  })

  $(document).on('click', '[id^="show_modal_horario-"]', function() {
    const id = $(this).data('id')
    const flag_registro = $(this).data('flag_registro');
    $("#id_medicamento").val(id);
    const medicamento = $(this).data('medicamento');
    $('#modal_kardex_editar_medicamento').modal('show');
    $("#asegurado").html($("#mk_paciente").val());
    $("#medicamento").html(medicamento);
    $("#flag_registro").val(flag_registro);
  });

  $(document).on('click', '[id^="suspend_medicamento-"]', async function() {
    const id = $(this).data('id');
    const estado_id = $(this).data('estado_id');
    const result = await Swal.fire({
        title: '¿Está seguro?',
        text: `¿Desea ${parseInt(estado_id) === 1 ? "reanudar" : "suspender"} el medicamento?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Si, ${parseInt(estado_id) === 1 ? "reanudar" : "suspender"}!`
    });
    if (result.isConfirmed) {
      await onClickSuspenderMedicamento(id, estado_id);
    }
  });

  function handlerTableMedicamentos(kardexId = null) { // 1 => CREAR, 2 => EDITAR
    const $thead = $("#tableMedicamentosKardex thead");
    if (kardexId) {
      $thead.html(
        `<tr>
          <th style="width: 30%">MEDICAMENTOS </th>
          <th style="width: 15%">ENFERMERA QUE REALIZO EL KARDEX</th>
          <th style="width: 15%">VIA</th>
          <th style="width: 10%">FRECUENCIA</th>
          <th style="width: 10%">DIAS ADMINISTRACION</th>
          <th style="width: 10%">F/H</th>
          <th style="width: 10%">SUSPENCION / REANUDAR</th>
        </tr>`
      );
    } else {
      $thead.html(
        `<tr>
          <th style="width: 30%">MEDICAMENTOS </th>
          <th style="width: 15%">ENFERMER@ QUE REALIZO EL KARDEX</th>
          <th style="width: 15%">VIA</th>
          <th style="width: 10%">FRECUENCIA</th>
          <th style="width: 15%">DIAS ADMINISTRACION</th>
        </tr>`
      );
    }
  }

  async function onClickSuspenderMedicamento(medicamento_id, estado_id) {
    try {
      Swal.fire({
        html: '<h5>Suspendiendo medicamento...</h5>',
        showLoaderOnConfirm: true,
        onOpen: () => {
            Swal.showLoading();
        }
      });

      const response = await $.ajax({
        type: "PUT",
        url: `/api/kardex-medicamentos-enfermerias/cambiar-estado/${medicamento_id}`,
        contentType: 'application/json',
        dataType: 'json',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify({estado: estado_id}),
      });

      Swal.fire({
        icon: "success",
        title: "KARDEX ENFERMERIA",
        text: `${response.message || "OK"}`,
        confirmButtonText: 'ENTENDIDO',
      });

      await refreshTableMedicamentos();

    } catch (error) {
      console.log("[error] => ", error);
      
    } finally {
      Swal.hideLoading();
      Swal.close();
    }
  }

  function onCloseModalRegister() {
    $('#modalKardex').modal('hide'); 
  }

  function addRowTableExamAuxLab(examenValues = null) {
    if (!examenValues) {
      const resultValidateLastRow = validateTable("tableExamAuxLab");
      if (!resultValidateLastRow) {
        Swal.fire({
          icon: "warning",
          title: '<p>EXAMEN AUXILIAR DE LABORATORIO</p>',
          html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO EXAMEN AUXILIAR DE LABORATORIO</p>', 
        });
        return null;
      }
    }

    const indexRow = $('#tableExamAuxLab tbody tr').length + 1;
    const newRow = `
      <tr id="tr_examen_labo_${indexRow}">
        <td id="exam_aux_lab_fecha_div${indexRow}"><input type="text" name="exam_aux_lab_fecha[]" id="exam_aux_lab_fecha${indexRow}" class="form-control input-sm" style="background-color: #ffffff" placeholder="dd/mm/yyyy" readonly ></td>
        <td id="exam_aux_lab_hora_div${indexRow}"><input type="time" name="exam_aux_lab_hora[]" id="exam_aux_lab_hora${indexRow}" class="form-control input-sm"></td>
        <td id="exam_aux_lab_nombre_div${indexRow}" ><input type="text" name="exam_aux_lab_nombre[]" id="exam_aux_lab_nombre${indexRow}" class="form-control input-sm" maxlength="255"></td>
        <td style="text-align: center"><input type="checkbox" style="margin-right: 40px" name="exam_aux_lab_muestra[]" id="exam_aux_lab_muestra${indexRow}" value="true"></td>
        <td style="text-align: center"><input type="checkbox" name="exam_aux_lab_result[]" id="exam_aux_lab_result${indexRow}" value="true"></td>
        <td style="text-align: center"><input type="checkbox" name="exam_aux_lab_reactive[]" id="exam_aux_lab_reactive${indexRow}" value="true"></td>
        ${examenValues ? "" : '<td><button id="btnRemoveRowExamAuxLab" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>'}
        <td><input type="hidden" name="exam_aux_lab_index[]" id="exam_aux_lab_index${indexRow}" value="${indexRow}" data-id=${indexRow}></td>
        <td><input type="hidden" name="exam_aux_lab_id[]" id="exam_aux_lab_id${indexRow}"></td>
        <td><input type="hidden" name="id_procedimiento" id="exam_aux_lab_procedimiento_id${indexRow}"></td>
      </tr>`;
    $('#tableExamAuxLab tbody').append(newRow);

    if (examenValues === null) {
      $(`#exam_aux_lab_nombre${indexRow}`).autocomplete({
        minLength: 1,
        source: function(request, response) {
        $.ajax({
          url: `/api/procedimientos/search/tipo-procedimiento/2`,
          type: "GET",
          dataType: "json",
          data: {
            text: request.term
          },
          success: function(data)  {
            response(data.map(function(item) {
              return { id: item.id, label: item.descripcion};
            }));
          },
          error: function() {
            response([]);
          }			
        })
        },
        select: function(event, ui) {
          $(this).val(ui.item.label);
          $(`#exam_aux_lab_procedimiento_id${indexRow}`).val(ui.item.id);
        }
      });
    }
 
    if (examenValues === null) {
      $(`#exam_aux_lab_fecha${indexRow}`).datepicker({
        useCurrent: true,
        format: 'dd/mm/yyyy', 
        todayHighlight: true,
        autoclose: true,
        endDate: new Date()
      });
    }

    $(`#exam_aux_lab_nombre${indexRow}`).on('input', function() {
        $(`#exam_aux_lab_procedimiento_id${indexRow}`).val("");
    });

    $(`#exam_aux_lab_nombre${indexRow}`).blur(function() {
      const interconsulta_id_service = $(`#exam_aux_lab_procedimiento_id${indexRow}`).val();
      clearInputError(`exam_aux_lab_nombre${indexRow}`, `exam_aux_lab_nombre_div${indexRow}`)
      if (!interconsulta_id_service) {
        markInputError(`exam_aux_lab_nombre${indexRow}`, `exam_aux_lab_nombre_div${indexRow}`, "espere los resultados y seleccione uno")
      }
    });

    $('#tableExamAuxLab').on('click', '#btnRemoveRowExamAuxLab', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      form.examenes = form.examenes.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    if (examenValues) {
      // $(`#exam_aux_lab_fecha${indexRow}`).datepicker("setDate", moment(examenValues.fecha, "YYYY-MM-DD").format("DD/MM/YYYY"));
      $(`#exam_aux_lab_fecha${indexRow}`).val(moment(examenValues.fecha, "YYYY-MM-DD").format("DD/MM/YYYY"));
      $(`#exam_aux_lab_fecha${indexRow}`).prop('readonly', true);
      $(`#exam_aux_lab_fecha${indexRow}`).removeAttr( 'style' );

      $(`#exam_aux_lab_hora${indexRow}`).val(examenValues.hora);
      $(`#exam_aux_lab_nombre${indexRow}`).val(examenValues.nombre_procedimiento);

      $(`#exam_aux_lab_nombre${indexRow}`).prop('readonly', true);

      $(`#exam_aux_lab_muestra${indexRow}`).prop("checked", parseInt(examenValues.toma_muestra) === 1 ? true : false);
      $(`#exam_aux_lab_result${indexRow}`).prop("checked", parseInt(examenValues.resultado) === 1 ? true : false);
      $(`#exam_aux_lab_reactive${indexRow}`).prop("checked", parseInt(examenValues.reactivo) === 1 ? true : false);
      $(`#exam_aux_lab_id${indexRow}`).val(examenValues.id);
      
      $(`#exam_aux_lab_procedimiento_id${indexRow}`).val(examenValues.id_procedimiento);
    }

    form.examenes.push({id: indexRow, name: `exam_aux_lab_fecha${indexRow}`,container_div: `exam_aux_lab_fecha_div${indexRow}`, required: true, tr_id: `tr_examen_labo_${indexRow}`, tab: "mkr_tab_examenes_auxiliares"});
    form.examenes.push({id: indexRow, name: `exam_aux_lab_hora${indexRow}`,container_div: `exam_aux_lab_hora_div${indexRow}`, required: true, tr_id: `tr_examen_labo_${indexRow}`, tab: "mkr_tab_examenes_auxiliares"});
    form.examenes.push({id: indexRow, name: `exam_aux_lab_nombre${indexRow}`,container_div: `exam_aux_lab_nombre_div${indexRow}`, required: true, tr_id: `tr_examen_labo_${indexRow}`, tab: "mkr_tab_examenes_auxiliares"});
  }

  function addRowTableExamAuxImag(examenImagen = null) {
    if (!examenImagen) {
      const resultValidateLastRow = validateTable("tableExamAuxImag");
      if (!resultValidateLastRow) {
        Swal.fire({
          icon: "warning",
          title: '<p>EXAMEN AUXILIAR DE IMAGENOLOGÍA</p>',
          html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO EXAMEN AUXILIAR DE IMAGENOLOGÍA</p>', 
        });
        return null;
      }
    }

    const currentDate = moment().format('DD/MM/YYYY');
    const indexRow = $('#tableExamAuxImag tbody tr').length + 1;
    const newRow = `
      <tr id="tr_examen_imag_${indexRow}">
        <td id="exam_aux_imag_fecha_div${indexRow}"><input type="text" name="exam_aux_imag_fecha[]" id="exam_aux_imag_fecha${indexRow}" class="form-control input-sm" value="${currentDate}" readonly></td>
        <td id="exam_aux_imag_hora_div${indexRow}"><input type="time" name="exam_aux_imag_hora[]" id="exam_aux_imag_hora${indexRow}" class="form-control input-sm"></td>
        <td id="exam_aux_imag_nombre_div${indexRow}"><input type="text" name="exam_aux_imag_nombre[]" id="exam_aux_imag_nombre${indexRow}" class="form-control input-sm" maxlength="255"></td>
        <td style="text-align: center"><input type="checkbox" style="margin-right: 40px" name="exam_aux_imag_imagen[]" id="exam_aux_imag_imagen${indexRow}" value="true"></td>
        ${examenImagen ? "" : '<td><button id="btnRemoveRowExamAuxImag" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>'}
        <td><input type="hidden" name="exam_aux_imag_index[]" id="exam_aux_imag_index${indexRow}" value="${indexRow}" data-id=${indexRow}></td>
        <td><input type="hidden" name="exam_aux_imag_id[]" id="exam_aux_imag_id${indexRow}"></td>
        <td><input type="hidden" name="id_procedimiento" id="exam_aux_imag_procedimiento_id${indexRow}"></td>
      </tr>`;
      
    $('#tableExamAuxImag tbody').append(newRow);

    $(`#exam_aux_imag_nombre${indexRow}`).autocomplete({
      minLength: 1,
      source: function(request, response) {
      $.ajax({
        url: `/api/procedimientos/search/tipo-procedimiento/3`,
        type: "GET",
        dataType: "json",
        data: {
          text: request.term
        },
        success: function(data)  {
          response(data.map(function(item) {
            return { id: item.id, label: item.descripcion};
          }));
        },
        error: function() {
          response([]);
        }			
      })
      },
      select: function(event, ui) {
        $(this).val(ui.item.label);
        $(`#exam_aux_imag_procedimiento_id${indexRow}`).val(ui.item.id);
      }
    });

    $('#tableExamAuxImag').on('click', '#btnRemoveRowExamAuxImag', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      form.examenes = form.examenes.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    if (examenImagen) {
      $(`#exam_aux_imag_fecha${indexRow}`).val(moment(examenImagen.fecha, "YYYY-MM-DD").format("DD/MM/YYYY"));
      $(`#exam_aux_imag_fecha${indexRow}`).prop('readonly', true);

      $(`#exam_aux_imag_hora${indexRow}`).val(examenImagen.hora);

      $(`#exam_aux_imag_nombre${indexRow}`).val(examenImagen.nombre_imagen);
      $(`#exam_aux_imag_nombre${indexRow}`).prop('readonly', true);

      $(`#exam_aux_imag_imagen${indexRow}`).prop("checked", parseInt(examenImagen.toma_imagen) === 1 ? true : false);
      $(`#exam_aux_imag_id${indexRow}`).val(examenImagen.id);

      $(`#exam_aux_imag_procedimiento_id${indexRow}`).val(examenImagen.id_procedimiento);
    }

    form.examenes.push({id: indexRow, name: `exam_aux_imag_fecha${indexRow}`,container_div: `exam_aux_imag_fecha_div${indexRow}`, required: true, tr_id: `tr_examen_imag_${indexRow}`, tab: "mkr_tab_examenes_auxiliares"});
    form.examenes.push({id: indexRow, name: `exam_aux_imag_hora${indexRow}`,container_div: `exam_aux_imag_hora_div${indexRow}`, required: true, tr_id: `tr_examen_imag_${indexRow}`, tab: "mkr_tab_examenes_auxiliares"});
    form.examenes.push({id: indexRow, name: `exam_aux_imag_nombre${indexRow}`,container_div: `exam_aux_imag_nombre_div${indexRow}`, required: true, tr_id: `tr_examen_imag_${indexRow}`, tab: "mkr_tab_examenes_auxiliares"});
  }

  function addRowTableInterconsulta(valuesInterconsulta = null) {
    if (!valuesInterconsulta) {
      const resultValidateLastRow = validateTable("tableInterconsulta");
      if (!resultValidateLastRow) {
        Swal.fire({
          icon: "warning",
          title: '<p>INTERCONSULTA</p>',
          html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UNA NUEVA INTERCONSULTA</p>', 
        });
        return null;
      }
    }
    
    const currentDate = moment().format('DD/MM/YYYY');
    const indexRow = $('#tableInterconsulta tbody tr').length + 1;
    const newRow = `
      <tr id="tr_interconsulta_${indexRow}">
        <td id="interconsulta_nombre_service_div${indexRow}"><input type="text" class="form-control input-sm" name="interconsulta_nombre_service[]" id="interconsulta_nombre_service${indexRow}" /></td>
        <td id="interconsulta_fecha_envio_div${indexRow}"><input type="text" name="interconsulta_fecha_envio[]" id="interconsulta_fecha_envio${indexRow}" class="form-control input-sm" value="${currentDate}" readonly></td>
        <td id="interconsulta_hora_envio_div${indexRow}"><input type="time" name="interconsulta_hora_envio[]" id="interconsulta_hora_envio${indexRow}" class="form-control input-sm"></td>
        <td id="interconsulta_fecha_respuesta_div${indexRow}"><input type="text" name="interconsulta_fecha_respuesta[]" id="interconsulta_fecha_respuesta${indexRow}" class="form-control input-sm" style="background-color: #ffffff" placeholder="dd/mm/yyyy" readonly></td>
        <td id="interconsulta_hora_respuesta_div${indexRow}"><input type="time" name="interconsulta_hora_respuesta[]" id="interconsulta_hora_respuesta${indexRow}" class="form-control input-sm"></td>
        ${valuesInterconsulta ? "" : '<td><button id="btnRemoveRowInterconsulta" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>'}
        <td><input type="hidden" name="interconsulta_index[]" id="interconsulta_index${indexRow}" value="${indexRow}" data-id=${indexRow}></td>
        <td><input type="hidden" name="interconsulta_id[]" id="interconsulta_id${indexRow}"></td>
        <td><input type="hidden" class="form-control input-sm" name="interconsulta_id_service[]" id="interconsulta_id_service${indexRow}"/></td>
      </tr>`;

    $('#tableInterconsulta tbody').append(newRow);

    $(`#interconsulta_nombre_service${indexRow}`).autocomplete({
      minLength: 1,
      source: function(request, response) {
      $.ajax({
        url: `/api/consultorios/search`,
        type: "GET",
        dataType: "json",
        data: {
          nombre: request.term
        },
        success: function(data)  {
          response(data.map(function(item) {
            return { id: item.id, label: item.nombre};
          }));
        },
        error: function() {
          response([]);
        }			
      })
      },
      select: function(event, ui) {
        $(this).val(ui.item.label);
        $(`#interconsulta_id_service${indexRow}`).val(ui.item.id);
      }
    });

    $(`#interconsulta_nombre_service${indexRow}`).on('input', function() {
        $(`#interconsulta_id_service${indexRow}`).val("");
    });

    $(`#interconsulta_nombre_service${indexRow}`).blur(function() {
      const interconsulta_id_service = $(`#interconsulta_id_service${indexRow}`).val();
      clearInputError(`interconsulta_nombre_service${indexRow}`, `interconsulta_nombre_service_div${indexRow}`)
      if (!interconsulta_id_service) {
        markInputError(`interconsulta_nombre_service${indexRow}`, `interconsulta_nombre_service_div${indexRow}`, "espere los resultados y seleccione uno")
      }
    });

    $("input[name='interconsulta_fecha_respuesta[]']").datepicker({
      useCurrent: true,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,
    });

    $('#tableInterconsulta').on('click', '#btnRemoveRowInterconsulta', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      form.interconsulta = form.interconsulta.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    if (valuesInterconsulta) {
      $(`#interconsulta_nombre_service${indexRow}`).val(valuesInterconsulta.consultorio_nombre);
      $(`#interconsulta_nombre_service${indexRow}`).prop('readonly', true);

      $(`#interconsulta_fecha_envio${indexRow}`).val(moment(valuesInterconsulta.fecha_envio, "YYYY-MM-DD").format("DD/MM/YYYY"));
      $(`#interconsulta_hora_envio${indexRow}`).val(moment(valuesInterconsulta.hora_envio, "HH:mm:ss").format("HH:mm"));
      $(`#interconsulta_fecha_respuesta${indexRow}`).datepicker("setDate", moment(valuesInterconsulta.fecha_respuesta, "YYYY-MM-DD").format("DD/MM/YYYY"));
      $(`#interconsulta_hora_respuesta${indexRow}`).val(moment(valuesInterconsulta.hora_respuesta, "HH:mm:ss").format("HH:mm"));
      $(`#interconsulta_id${indexRow}`).val(valuesInterconsulta.id);
      $(`#interconsulta_id_service${indexRow}`).val(valuesInterconsulta.id_consultorios);
    }

    form.interconsulta.push({id: indexRow, name: `interconsulta_nombre_service${indexRow}`,container_div: `interconsulta_nombre_service_div${indexRow}`, required: true, tr_id: `tr_interconsulta_${indexRow}`, tab: "mkr_tab_interconsulta"});
    form.interconsulta.push({id: indexRow, name: `interconsulta_fecha_envio${indexRow}`,container_div: `interconsulta_fecha_envio_div${indexRow}`, required: true, tr_id: `tr_interconsulta_${indexRow}`, tab: "mkr_tab_interconsulta"});
    form.interconsulta.push({id: indexRow, name: `interconsulta_hora_envio${indexRow}`,container_div: `interconsulta_hora_envio_div${indexRow}`, required: true, tr_id: `tr_interconsulta_${indexRow}`, tab: "mkr_tab_interconsulta"});
    form.interconsulta.push({id: indexRow, name: `interconsulta_fecha_respuesta${indexRow}`,container_div: `interconsulta_fecha_respuesta_div${indexRow}`, required: true, tr_id: `tr_interconsulta_${indexRow}`, tab: "mkr_tab_interconsulta"});
    form.interconsulta.push({id: indexRow, name: `interconsulta_hora_respuesta${indexRow}`,container_div: `interconsulta_hora_respuesta_div${indexRow}`, required: true, tr_id: `tr_interconsulta_${indexRow}`, tab: "mkr_tab_interconsulta"});
  }

  function validateTable(tableId) {
    let isValid = true;
    $(`#${tableId} tbody tr`).each(function() {
      $(this).find('input:not([type="checkbox"]):not([type="hidden"]), select').each(function() {
          if ($(this).is('input') && $(this).val().trim() === '') {
              isValid = false;
              return false;
          }
          if ($(this).is('select') && $(this).val() === '') {
              isValid = false;
              return false;
          }
      });
      if (!isValid) {
        return false;
      }
    });
    return isValid;
  }

  function buildRequestSaveKardex() {
    let data = {
      emergenciaId: $('#id_hospitalizacion').val(),
      aseguradoId: $('#id_asegurado').val(),
      establecimientoId: $('#id_establecimiento').val(),
      turnoId:  $('#mkr_turno').val(),
      controlFuncionesVitales: $('#control_funciones_vitales').is(':checked') ? true : false,
      barreraHematoencefalica: $('#barrera_hematoencefalica').is(':checked') ? true : false,
      cabecera: $('#cabecera').val(),
      oxigenoTerapiaId: $('#id_oxigeno_terapia').val(),
      oxigenoterapiaCantidad: $('#oxigenoterapia_cantidad').val(),
      movilización: $('#movilización').is(':checked') ? true : false,
      /* hemoglucotest: $('#hemoglucotest_cuidado_enfermero_kardex').val(), */
      hemoglucotest: $('[name="hemoglucotest_cuidado_enfermero_kardex[]"]').map(function () {
        return $(this).val();
      }).get(),
      cuidadoEnfermerosId: $('#cuidado_enfermeros_id').val(),
      arrayLaboratorio: getValuesRowByTable("tableExamAuxLab"),
      arrayImagenologia: getValuesRowByTable("tableExamAuxImag"),
      arrayInterconsulta: getValuesRowByTable("tableInterconsulta"),
    }
    return Object.assign({}, data, formMedicamentosData);
  }

  function onSubmitForm() {
    const kardex_enfermerias_id = $("#id").val();
    if (kardex_enfermerias_id && parseInt(kardex_enfermerias_id) > 0) {
      updateKardex(kardex_enfermerias_id);
    } else {
      createKardex();
    }
  }

  function createKardex() {
    const controlsErros =  validateForm();
    if (controlsErros.length > 0) {
      Swal.fire({
        icon: "warning",
        title: '<p>REGISTRAR KARDEX</p>',
        html: '<p class="descripcion-alert">EXISTE CAMPOS QUE SON NECESARIOS DE LLENAR</p>', 
      });
      return null;
    }
    const data = buildRequestSaveKardex();
    $.ajax({
      type: "POST",
      url: "/api/kardex-enfermerias",
      contentType: 'application/json',
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: JSON.stringify(data),
      beforeSend: function() {
        $("#btnSave").prop('disabled', true);
        Swal.fire({
            html: '<h5>Guardando registro...</h5>',
            showLoaderOnConfirm: true,
            onOpen: () => {
                Swal.showLoading();
            }
        });
      },
      success: function(response) {
        Swal.fire({
          icon: "success",
          title: "KARDEX ENFERMERIA",
          text: `${response.message || "OK"}`,
          confirmButtonText: 'ENTENDIDO',
          customClass: "swal2-custom"
        });
        onCloseModalRegister();
        $('#formularioKardex')[0].reset();
        clearFormMedicamentos();
        clearFormKardex();
        clearTableViewMedicamentos();
        searchListKardex();
      },
      error: function(xhr, status, error) {
        console.log("[error] => ", error);
      },
      complete: function() {
        $('#btnSave').prop('disabled', false);
        Swal.hideLoading();
      }
    });
  }

  function updateKardex(kardex_enfermerias_id) {
    const controlsErros =  validateForm();
    if (controlsErros.length > 0) {
      Swal.fire({
        icon: "warning",
        title: '<p>EDITAR KARDEX</p>',
        html: '<p class="descripcion-alert">EXISTE CAMPOS QUE SON NECESARIOS DE LLENAR</p>', 
      });
      return null;
    }
    const data = buildRequestSaveKardex();
    $.ajax({
      type: "PUT",
      url: `/api/kardex-enfermerias/${kardex_enfermerias_id}`,
      contentType: 'application/json',
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: JSON.stringify(data),
      beforeSend: function() {
        $("#btnSave").prop('disabled', true);
        Swal.fire({
            html: '<h5>Guardando registro...</h5>',
            showLoaderOnConfirm: true,
            onOpen: () => {
                Swal.showLoading();
            }
        });
      },
      success: function(response) {
        Swal.fire({
          icon: "success",
          title: "KARDEX ENFERMERIA",
          text: `${response.message || "OK"}`,
          confirmButtonText: 'ENTENDIDO',
          customClass: "swal2-custom"
        });
        onCloseModalRegister();
        $('#formularioKardex')[0].reset();
        clearFormKardex();
        clearTableViewMedicamentos();
        searchListKardex();
      },
      error: function(xhr, status, error) {
        console.log("[error] => ", error);
      },
      complete: function() {
        $('#btnSave').prop('disabled', false);
        Swal.hideLoading();
      }
    });
  }

  /* function validateForm() {
    let $control = null;
    let valueControl = null;
    let controlsError = [];
    for (const key in form) {
      if (form.hasOwnProperty(key)) {
        if (form && form[key] && form[key].length > 0) {
          form[key].forEach((control, index) => {
            $control = $(`#${control.name}`);

            $(`#${control.container_div}`).removeClass("has-error  has-feedback");
            $control.siblings('.form-control-feedback, .label-error').remove();
            $control.nextAll('.label-error').remove();

        
            if (control.required) {
              // valueControl = $(`#${control.name}`).val();
              valueControl = $control.val();
              if (valueControl === null || valueControl === "" || valueControl === undefined) {
                if ($control.is('select') && $control.hasClass('select2')) {
                    $control.next('.select2-container').addClass('input-error');
                    $control.next('.select2-container').after(`<span class="label-error" style="color: #dc3545; font-size: 11px; font-weight: bold">campo requerido</span>`);
                } else {
                  $control.after(`<span class="label-error" style="color: #dc3545; font-size: 11px; font-weight: bold">campo requerido</span>`);
                  $(`#${control.container_div}`).addClass("has-error  has-feedback");
                }
                controlsError.push(control);
              }
            }

          });
        }
      }
    }

    if (controlsError.length > 0) {
      const firstControl = controlsError[0];
      if (firstControl.tab) {
        $(`#tabs_kardex_register a[href="#${firstControl.tab}"]`).tab('show');
      }
      $(`#${firstControl.name}`).focus();
    }
    return controlsError;
  } */

  function validateForm() {
      let $control = null;
      let valueControl = null;
      let controlsError = [];

      for (const key in form) {
          if (form.hasOwnProperty(key)) {
              if (form[key] && form[key].length > 0) {
                  form[key].forEach((control) => {

                      // Soporta inputs normales y names que terminan en []
                      $control = $(`[name="${control.name}"], [name="${control.name}[]"]`);

                      $(`#${control.container_div}`).removeClass("has-error has-feedback");
                      $control.each(function () {
                          $(this).siblings('.form-control-feedback, .label-error').remove();
                          $(this).nextAll('.label-error').remove();
                      });

                      // Limpiar errores previos de Select2
                      $control.next('.select2-container').removeClass('input-error');

                      if (control.required) {
                          let controlVacio = false;

                          $control.each(function () {
                              const $input = $(this);
                              valueControl = $input.val();

                              if (
                                  valueControl === null ||
                                  valueControl === "" ||
                                  valueControl === undefined
                              ) {
                                  controlVacio = true;

                                  if ($input.is('select') && $input.hasClass('select2')) {
                                      $input.next('.select2-container').addClass('input-error');
                                      $input.next('.select2-container').after(
                                          `<span class="label-error" style="color: #dc3545; font-size: 11px; font-weight: bold">campo requerido</span>`
                                      );
                                  } else {
                                      $input.after(
                                          `<span class="label-error" style="color: #dc3545; font-size: 11px; font-weight: bold">campo requerido</span>`
                                      );
                                      $(`#${control.container_div}`).addClass("has-error has-feedback");
                                  }
                              }
                          });

                          if (controlVacio) {
                              controlsError.push(control);
                          }
                      }
                  });
              }
          }
      }

      if (controlsError.length > 0) {
          const firstControl = controlsError[0];

          if (firstControl.tab) {
              $(`#tabs_kardex_register a[href="#${firstControl.tab}"]`).tab('show');
          }

          $(`[name="${firstControl.name}"], [name="${firstControl.name}[]"]`).first().focus();
      }

      return controlsError;
  }

  async function buildOptionsSelectOxigenoterapia() {
    const result = await getValuesOxinoterapia();
    let htmlString = `<option value="" selected>--- seleccionar ---</option>`
    if (result && result.length > 0) {
      $("#id_oxigeno_terapia").empty();
      result.forEach((item, index) => {
        htmlString = htmlString + `<option value="${item.id}">${item.descrip_larga}</option>`
      });
      $("#id_oxigeno_terapia").html(htmlString);
    }
  }

  function getValuesOxinoterapia() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/parametro/CATEGORIA_EV_ADV/OXIGENOTERAPIA`,
        method: "GET",
        dataType: 'json',
        success: function(result) {
          resolve(result);
        },
        error: function(xhr, status, error) {
          reject(error);
        }
      });
    })
  }

  function getExamenesLaboratorio(prestacionId, tipoProcedimientoId) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/procedimientos-diagnoticos/prestacion/${prestacionId}/tipo-procedimiento/${tipoProcedimientoId}`,
        method: "GET",
        dataType: 'json',
        success: function(result) {
          resolve(result);
        },
        error: function(xhr, status, error) {
          reject(error);
        }
      });
    })
  }

  function getInterconsultas(prestacionId) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/interconsultas/consultorios/prestacion/${prestacionId}`,
        method: "GET",
        dataType: 'json',
        success: function(result) {
          resolve(result);
        },
        error: function(xhr, status, error) {
          reject(error);
        }
      });
    })
  }

  // tableId: 2 => Examenes Laboratorio, 3 => Examenes Imagen
  function buildTableAuxiliaryExam(listaExamenes, tableId) {
    if (tableId === 2) {
      $("#tableExamAuxLab tbody").empty();
    } else {
      $("#tableExamAuxImag tbody").empty();
    }
    let objExamen = {};
    if (listaExamenes && listaExamenes.length > 0) {
      listaExamenes.forEach((examen, index) => {
        if (tableId === 2) {
          objExamen = {
            fecha: moment(examen.fecha, "YYYY-MM-DD").format("YYYY-MM-DD"),
            hora: "",
            nombre_procedimiento: examen.descripcion || "-",
            toma_muestra: 0,
            resultado: 0,
            reactivo: 0,
            id: null,
            id_procedimiento: examen.id_procedimientos
          };
          addRowTableExamAuxLab(objExamen);
        } else {
          objExamen = {
            fecha: moment(examen.fecha, "YYYY-MM-DD").format("YYYY-MM-DD"),
            hora: "",
            nombre_imagen: examen.descripcion || "-",
            toma_imagen: 0,
            id: null,
            id_procedimiento: examen.id_procedimientos
          };
          addRowTableExamAuxImag(objExamen);
        }
      });
    }
  }

  function buildTableInterconsultas(listaInterconsultas) {
    $("#tableInterconsulta tbody").empty();
    let obj = {}
    if (listaInterconsultas && listaInterconsultas.length > 0) {
      listaInterconsultas.forEach((interconsulta, index) => {
        obj = {
          consultorio_nombre: interconsulta.consultorio_nombre || "-",
          fecha_envio: interconsulta.fecha_atencion ? interconsulta.fecha_atencion : "",
          hora_envio: interconsulta.hora_atencion ? interconsulta.hora_atencion : "",
          fecha_respuesta: interconsulta.fecha_respuesta ? interconsulta.fecha_respuesta : "",
          hora_respuesta: interconsulta.hora_respuesta ? interconsulta.hora_respuesta : "",
          id: interconsulta.id_interconsultas,
          id_consultorios: interconsulta.id_consultorio,
        };
        addRowTableInterconsulta(obj);
      });
    }
  }

  function getKardexEnfermeriasById() {
    return new Promise((resolve, reject) => {
      const id_kardex_enfermeria = $("#id").val();
      $.ajax({
        type: "GET",
        url: `/api/kardex-enfermerias/${id_kardex_enfermeria}`,
        dataType: "json",
        success: function(result) {
            resolve(result);
        },
        error: function(xhr, status, error) {
          reject(error);
        }
      });
    })
  }

  function addHemotest() {
    var container = $('#container_horas');
    var cantidad = container.find('input[name="hemoglucotest_cuidado_enfermero_kardex[]"]').length;

    var html = `
        <div class="col-sm-1">
            <input type="time"
                   step="60"
                   placeholder="0"
                   class="form-control input-sm"
                   id="hemoglucotest_cuidado_enfermero_kardex${cantidad}"
                   name="hemoglucotest_cuidado_enfermero_kardex[]">
        </div>
    `;

    container.append(html);
  }

  $('#addHemotest').on('click', function () {
      addHemotest();
  });

  $('#limpiarHemotest').on('click', function () {
    $('#container_horas').html(`
        <div class="col-sm-1">
            <input type="time"
                   step="60"
                   placeholder="0"
                   class="form-control input-sm"
                   id="hemoglucotest_cuidado_enfermero_kardex0"
                   name="hemoglucotest_cuidado_enfermero_kardex[]">
        </div>
    `);
  });

  function cargarHemoglucotest(hemoglucotest) {
    var horas = [];

    // Convertir el string JSON a array
    if (hemoglucotest) {
        try {
            horas = typeof hemoglucotest === "string"
                ? JSON.parse(hemoglucotest)
                : hemoglucotest;
        } catch (e) {
            horas = [];
        }
    }

    // Si no hay horas, dejar un input vacío por defecto
    if (!Array.isArray(horas) || horas.length === 0) {
        horas = [""];
    }

    var html = "";

    horas.forEach(function (hora, index) {
        html += `
            <div class="col-sm-1">
                <input type="time"
                       step="60"
                       placeholder="0"
                       class="form-control input-sm"
                       id="hemoglucotest_cuidado_enfermero_kardex${index}"
                       name="hemoglucotest_cuidado_enfermero_kardex[]"
                       value="${hora}">
            </div>
        `;
    });

    $("#container_horas").html(html);
  }
  
  function setForms(data) {
    if (data) {
      $("#tableInterconsulta tbody").empty();
      $("#tableExamAuxLab tbody").empty();
      $("#tableExamAuxImag tbody").empty();

      $("#mkr_turno").val(data.kardexEnfermeria.id_turno);
      
      if (data.cuidadoEnfermeros && JSON.stringify(data.cuidadoEnfermeros) !== "{}") {
        $("#cuidado_enfermeros_id").val(data.cuidadoEnfermeros.id)
        $("#control_funciones_vitales").prop('checked', parseInt(data.cuidadoEnfermeros.cfv) === 1 ? true : false);
        $("#barrera_hematoencefalica").prop('checked', parseInt(data.cuidadoEnfermeros.bhe) === 1 ? true : false);
        $("#cabecera").val(data.cuidadoEnfermeros.cabecera || "");
        $("#id_oxigeno_terapia").val(data.cuidadoEnfermeros.id_oxigeno_terapia || "");
        $("#oxigenoterapia_cantidad").val(data.cuidadoEnfermeros.oxigenoterapia_cantidad || "");
        $("#movilización").prop('checked', parseInt(data.cuidadoEnfermeros.movilizacion) === 1 ? true : false);
        /* $("#hemoglucotest_cuidado_enfermero_kardex").val(data.cuidadoEnfermeros.hemoglucotest || ""); */
        cargarHemoglucotest(data.cuidadoEnfermeros.hemoglucotest);

        $("#oxigenoterapia_cantidad").removeAttr("disabled");
      }

      if (data.interconsultas && data.interconsultas.length > 0) {
        data.interconsultas.forEach((interconsulta, index) => {
          addRowTableInterconsulta(interconsulta)
        });
      }

      if (data.examenLaboratorioKardexes && data.examenLaboratorioKardexes.length > 0) {
        data.examenLaboratorioKardexes.forEach((examenLaboratorio, index) => {
          addRowTableExamAuxLab(examenLaboratorio)
        });
      }

      if (data.examenImagenKardexes && data.examenImagenKardexes.length > 0) {
        data.examenImagenKardexes.forEach((examenImagen, index) => {
          addRowTableExamAuxImag(examenImagen)
        });
      }
    }
  }

  function getLatestIndiciacionTera(id_asegurado) {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: `/api/obtener-ultima-terapeutica/asegurado/${id_asegurado}`,
        dataType: "json",
        success: function (response) {
          resolve(response);
        },
        error: function (xhr, status, error) {
          reject(error);
        }
      });
    });
  }
});
</script>
<style>
  .swal2-custom {
    width: 25%;
  }

  .form-check {
    display: flex; 
    align-items: center;
    margin-bottom: 8px;
  }

  .form-check-label {
    margin-left: 10px !important;
  }

  .input-error {
    border: 1px solid #dc3545 !important;
    border-radius: 5px;
  }

  .strikethrough {
    color: #dc3545;
    position: relative;
    display: inline-block;
  }

  .strikethrough::after {
    content: '';
    position: absolute;
    top: 60%;
    left: 0;
    width: 120%;
    height: 0.5px;
    background-color: #dc3545;
    transform: rotate(-10deg);
    transform-origin: left center;
  }

  .table-row-medicamento {
    background-color: #28a745;
  }

  .ui-autocomplete-loading {
    background:url('images/loader.gif') no-repeat right center; 
  }
</style>
    