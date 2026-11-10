$(document).ready(function () {
  const tipo_diagnosticos = [
    {id: 1, descripcion: 'PRESUNTIVO'},
    {id: 2, descripcion: 'DEFINITIVO'},
    {id: 3, descripcion: 'REPETITIVO'},
  ];

  const categoria_diagnostico = [
    {id: 1, descripcion: 'PRINCIPAL'},
    {id: 2, descripcion: 'SECUNDARIO'},
  ];

  let form = [
    {id: 1, name: 'fecha_atencion', required: true},
    {id: 2, name: 'hora_atencion', required: true},
    {id: 3, name: 'especializacion_medico', required: true},
    {id: 4, name: 'codigo_upss_profesional', required: true},
    {id: 5, name: 'observacion', required: false},
  ];

  let diagnosticosList = $("#diagnosticos").data("list");

  const servicesExceptions = [10, 278, 279, 152, 65, 131, 162, 165, 182, 243, 244, 245, 259, 272];

  $('#especializacion_medico').select2({ width: '100%' });
  $('#codigo_upss_profesional').select2({ width: '100%' });

  $("#especializacion_medico").on("change", function() {
    const selectedValue = $(this).val();
    $("#codigo_upss_profesional").empty().prop("disabled", true).trigger("change");
    if (selectedValue && parseInt(selectedValue) > 0) {
      getUpssByConsultorioId(selectedValue);
    }

    if (servicesExceptions.includes(parseInt(selectedValue))) {
      buildOptionsSelectDiagnostic();
    } else {
      clearOptionsSelectDiagnosticHistory();
    }
  });

  $('#addDiagnostico').click(function() {
    addRowTableDiagnostic();
  });

  $('#addProcedimiento').click(function() {
    addRowTableProcedure();
  });

  $('#btnSave').click(function() {
    onSubmit();
  });

  $('#btnCancel').click(function() {
    const hospitalizacion_id = $("#hospitalizacion_id").val();
    window.location.replace(`/ver_prestaciones_consejeria/${hospitalizacion_id}`);
  });

  main();

  function main() {
    addAsteriskToLabel();
    setearFechaHoraAtencion();

    buildRowsDiagnostics();
    buildRowsProcedures();
    
    const especializacion_medico = $("#especializacion_medico").val();
    console.log(especializacion_medico);
    if (especializacion_medico) {
      getUpssByConsultorioId(especializacion_medico);

      const upss_especialista_id = $("#upss_especialista_id").val();
      console.log("[upss_especialista_id] =>", upss_especialista_id);
       //$("#codigo_upss_profesional").val(String(upss_especialista_id)).trigger("change");
    }

    const consultorio_id = $("#consultorio_id").val();
    if (servicesExceptions.includes(parseInt(consultorio_id))) {
      buildOptionsSelectDiagnostic(); 
    }
  }

  function setearFechaHoraAtencion() {
    const consejeria_fecha_atencion = $("#consejeria_fecha_atencion").val();
    if (consejeria_fecha_atencion) {
      const dateAttention = moment(consejeria_fecha_atencion, "YYYY-MM-DD HH:mm:ss").format("DD-MM-YYYY");
      const timeAttention = moment(consejeria_fecha_atencion, "YYYY-MM-DD HH:mm:ss").format("HH:mm");

      $("#fecha_atencion").val(dateAttention);
      $("#hora_atencion").val(timeAttention);
    }
  }

  function buildRowsDiagnostics() {
    const diagnosticos = $("#diagnosticos").data("list");
    if (diagnosticos && diagnosticos.length > 0) {
      diagnosticos.forEach(element => {
        addRowTableDiagnostic(element);
      });
    }
  }

  function buildRowsProcedures() {
    const procedimientos = $("#procedimientos").data("list");
    if (procedimientos && procedimientos.length > 0) {
      procedimientos.forEach(element => {
        addRowTableProcedure(element);
      });
    }
  }

  function addRowTableDiagnostic(diagnostic = null) {
    if (diagnostic === null) {
      const lastRowValid = validateLastRowTable("tblDiagnostico");
      if (!lastRowValid) {
        Swal.fire({
          icon: "warning",
          title: '<p>EDITAR CONSEJERIA</p>',
          html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA AGREGAR UN NUEVO DIAGNOSTICO</p>', 
        });
        return null;
      }
    }

    const indexRow = $('#tblDiagnostico tbody tr').length + 1;

    const optionsSelectTipoDiagnosticoString = tipo_diagnosticos.map((tipo) => {
      return `<option value="${tipo.id}">${tipo.descripcion}</option>`;
    }).join('');

    const optionsSelectCategoriaDiagnosticoString = categoria_diagnostico.map((categoria) => {
      return `<option value="${categoria.id}" ${diagnostic && diagnostic.id_categoria == categoria.id ? "selected" : ""}>${categoria.descripcion}</option>`;
    }).join('');

    const newRow = `
      <tr id="tr_diagnosticos_${indexRow}">
        <td><input class="form-control" type="text" title="Ingresar el diagnóstico" name="nombre_diagnostico" id="nombre_diagnostico${indexRow}" style="width:100%;" ${diagnostic && "readonly"}/></td>
        <td>
          <select class="form-control" id="id_tipo_diagnostico${indexRow}" name="id_tipo_diagnostico">
            <option value="0">--Seleccionar--</option>${optionsSelectTipoDiagnosticoString}
          </select>
        </td>
        <td>
          <select class="form-control" id="id_categoria_diagnostico${indexRow}" name="id_categoria_diagnostico">
            <option value="0">--Seleccionar--</option>${optionsSelectCategoriaDiagnosticoString}
          </select>
        </td>
        <td>${!diagnostic ? '<button id="btnRemoveRowDiagnostic" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>' : ''}</td>
        <td><input type="hidden" id="id_diagnostico${indexRow}" name="id_diagnostico" value=""></td>
        <td><input type="hidden" name="receta_diagnostico_id" id="receta_diagnostico_id${indexRow}" value=""></td>
      </tr>`;

    $('#tblDiagnostico tbody').append(newRow);
    // const sexo = String($("#asegurado_sexo").val()).toUpperCase() === "M" ? 1 : 2;

    $(`#nombre_diagnostico${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          url: `../obtener_diagnostico/${request.term}/`,
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
        $(this).val(ui.item.label);
        $(`#id_diagnostico${indexRow}`).val(ui.item.id);

        diagnosticosList.push(ui.item);
        refreshListDiagnostic(ui.item);

      }
    });

    $(`#nombre_diagnostico${indexRow}`).on('input', function() {
      $(`#id_diagnostico${indexRow}`).val("");
    });

    $(`#nombre_diagnostico${indexRow}`).blur(function() {
      const id_procedimiento = $(`#id_diagnostico${indexRow}`).val();
      const nombre_diagnostico = $(this).val().trim();
      
      // Verificar si el diagnóstico ya ha sido agregado
      let diagnosticoDuplicado = false;
      $('#tblDiagnostico tbody tr').each(function() {
          const diagnosticoExistente = $(this).find('input[name="nombre_diagnostico"]').val().trim();
          if (diagnosticoExistente === nombre_diagnostico && $(this).attr('id') !== `tr_diagnosticos_${indexRow}`) {
              diagnosticoDuplicado = true;
              return false; 
          }
      });

      if (diagnosticoDuplicado) {
          Swal.fire({
              icon: "error",
              title: "Diagnóstico Duplicado",
              text: "Este diagnóstico ya ha sido registrado.",
          }).then(() => {
              $(this).val("");
              $(`#id_diagnostico${indexRow}`).val("");
          });
          return;
      }

      if (!id_procedimiento) {
          bootbox.alert("Falta ingresar el diagnóstico en esta fila");
          $(this).val("");
          $(`#id_diagnostico${indexRow}`).val("");
      }
  });

  // Eliminar fila
  $('#tblDiagnostico').on('click', '#btnRemoveRowDiagnostic', function() {
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      const idDiagnostico = row.find('input[id^="id_diagnostico"]').val();

      // Eliminar de la lista de diagnósticos
      $(`select[id^="procedimiento_diagnostico"] option[value="${idDiagnostico}"]`).remove();
      diagnosticosList = diagnosticosList.filter(item => String(item.id) !== String(idDiagnostico) && String(item.id_diagnostico) !== String(idDiagnostico));
      form = form.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
  });

    if (diagnostic) {
      $(`#nombre_diagnostico${indexRow}`).val(`${diagnostic.codigo} - ${diagnostic.nombre}`);
      $(`#id_tipo_diagnostico${indexRow}`).val(diagnostic.id_tipo_diagnostico);
      $(`#id_diagnostico${indexRow}`).val(diagnostic.id_diagnostico);
      $(`#receta_diagnostico_id${indexRow}`).val(diagnostic.id);
    }
    form.push({id: indexRow, name: `nombre_diagnostico${indexRow}`, required: true, tr_id: `tr_diagnosticos_${indexRow}`});
    form.push({id: indexRow, name: `id_tipo_diagnostico${indexRow}`, required: true, tr_id: `tr_diagnosticos_${indexRow}`});
    form.push({id: indexRow, name: `id_categoria_diagnostico${indexRow}`, required: true, tr_id: `tr_diagnosticos_${indexRow}`});
  }

  function addRowTableProcedure(procedure = null) {
    if (procedure === null) {
      const lastRowValid = validateLastRowTable("tblProcedimiento");
      if (!lastRowValid) {
        Swal.fire({
          icon: "warning",
          title: '<p>EDITAR CONSEJERIA</p>',
          html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA AGREGAR UN NUEVO PROCEDIMIENTO</p>', 
        });
        return null;
      }
    }

    const indexRow = $('#tblProcedimiento tbody tr').length + 1;

    let diagnosticRelationsOptions = "";
    let labelOptions = "";
    if (diagnosticosList && diagnosticosList.length > 0) {
      diagnosticRelationsOptions = diagnosticosList.map((diagnostic) => {

        if (diagnostic.label) {
          labelOptions = `${diagnostic.label}`;
        } else {
          labelOptions = `${diagnostic.codigo} - ${diagnostic.nombre}`
        }

        return `<option value="${diagnostic.id_diagnostico || diagnostic.id}">${labelOptions}</option>`;
      })
    }

    const newRow = `
      <tr id="tr_procedimientos_${indexRow}">
        <td><input class="form-control" type="text" title="Ingresar el procedimiento" name="procedimiento_nombre" id="procedimiento_nombre${indexRow}" style="width:100%;" ${procedure && "readonly"}/></td>
        <td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control typeahead tt-query" id="procedimiento_cantidad${indexRow}" name="procedimiento_cantidad" value="1"></td>
        <td>
          <select class="form-control select-diagnostico" id="procedimiento_diagnostico${indexRow}" name="procedimiento_diagnostico" data-id_original="">
            <option value="0" selected>--Seleccionar--</option>${diagnosticRelationsOptions}
          </select>
        </td>
        <td>${!procedure ? '<button id="btnRemoveRowProcedure" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>' : ''}</td>
        <td>
          <input type="hidden" id="id_procedimiento${indexRow}" name="id_procedimiento" value="">
        </td>
        <td><input type="hidden" name="prestacion_procedimiento_id" id="prestacion_procedimiento_id${indexRow}" value=""></td>
      </tr>
    `;

    $('#tblProcedimiento tbody').append(newRow);

    // const nivel = $('#establecimiento_nivel').val();
    // const genero = String($("#asegurado_sexo").val()).toUpperCase() === "M" ? 1 : 2;

    $(`#procedimiento_nombre${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          url: '../obtener_procedimiento/' + request.term,
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
        $(this).val(ui.item.label);
        $(`#id_procedimiento${indexRow}`).val(ui.item.id);
      }
    });

    $(`#procedimiento_nombre${indexRow}`).on('input', function() {
      $(`#id_procedimiento${indexRow}`).val("");
    });

    $(`#procedimiento_nombre${indexRow}`).blur(function() {
    const id_procedimiento = $(`#id_procedimiento${indexRow}`).val();
    const nombre_procedimiento = $(this).val().trim();
  
      // Verificar si el procedimiento ya ha sido agregado
      let procedimientoDuplicado = false;
      $('#tblProcedimiento tbody tr').each(function() {
        const procedimientoExistente = $(this).find('input[name="procedimiento_nombre"]').val().trim();
        if (procedimientoExistente === nombre_procedimiento && $(this).attr('id') !== `tr_procedimientos_${indexRow}`) {
          procedimientoDuplicado = true;
          return false; 
        }
      });
  
      if (procedimientoDuplicado) {
        Swal.fire({
          icon: "error",
          title: "Procedimiento Duplicado",
          text: "Este procedimiento ya ha sido registrado.",
        }).then(() => {
          $(this).val("");
          $(`#id_procedimiento${indexRow}`).val("");
        });
        return;
      }
  
      // Si el campo está vacío, mostrar la alerta de procedimiento faltante
      if (!id_procedimiento) {
        bootbox.alert("Falta ingresar el procedimiento en esta fila");
        $(this).val("");
        $(`#id_procedimiento${indexRow}`).val("");
      }
    });

    $('#tblProcedimiento').on('click', '#btnRemoveRowProcedure', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      form = form.filter(obj => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    if (procedure) {
      $(`#procedimiento_nombre${indexRow}`).val(`${procedure.codigo} - ${procedure.nombre}`);
      $(`#procedimiento_cantidad${indexRow}`).val(procedure.cantidad);
      $(`#procedimiento_diagnostico${indexRow}`).val(procedure.id_diagnostico);
      $(`#procedimiento_diagnostico${indexRow}`).attr('data-id_original', procedure.id_diagnostico);
      $(`#id_procedimiento${indexRow}`).val(procedure.procedimiento_id);
      $(`#prestacion_procedimiento_id${indexRow}`).val(procedure.prestacion_procedimientos_id);
    }

    form.push({id: indexRow, name: `procedimiento_nombre${indexRow}`, required: true, tr_id: `tr_procedimientos_${indexRow}`});
    form.push({id: indexRow, name: `procedimiento_cantidad${indexRow}`, required: true, tr_id: `tr_procedimientos_${indexRow}`});
    form.push({id: indexRow, name: `procedimiento_diagnostico${indexRow}`, required: true, tr_id: `tr_procedimientos_${indexRow}`});
  }

  function getUpssByConsultorioId(id_consultorio) {
    const id_tipo_atencion = $('#id_tipo_atencion').val();
    // var valor2=1;
    // if(id_tipo_atencion == 5 || id_tipo_atencion == 7 ){
    //   id_tipo_atencion = 1; 
    // }

    $.ajax({
      url: `/obtener_upss_hos/${id_consultorio}/${id_tipo_atencion}`,
      success: function(result) {
        if (result && result.length > 0) {
          var newOption = "";
          if (result.length >= 2) {
            newOption = "<option value='0'>--Seleccionar-</option>";
          }
          $(result).each(function(ii, oo) {
            //console.log("#asdasd =", oo.id);
            var selecupss = (oo.id == $("#upss_especialista_id").val() ? "selected" : "");
            //console.log("====asd" , $("#upss_especialista_id").val());
            newOption += "<option value='" + oo.id + "'  "+selecupss+"  >" + oo.codigo + ' - ' + oo.descripcion_upss + "</option>";
          });
          $('#codigo_upss_profesional').html(newOption);
          $("#codigo_upss_profesional").prop("disabled", false);
          $("#codigo_upss_profesional").select2({ max_selected_options: 4 });
        //   if (id_tipo_atencion != 1) {
        //     $("#codigo_upss_profesional[value='" + valor2 + "']").attr("selected", false);
        //     $("#codigo_upss_profesional[value='" + valor2 + "']").attr("selected", true);
        //   }else {
        //   $('#codigo_upss_profesional').html("<option value='0'>--Seleccionar-</option>");
        // }
        }
      }
    });
  }

  function onSubmit() {
    const formValid = validateForm();
    if (!formValid) {
      Swal.fire({
        icon: "error",
        title: "EDITAR CONSEJERIA",
        html: `<p>Faltan campos obligatorios. Asegúrese de llenar aquellos marcados con (*) para continuar.</p>`,
        showLoaderOnConfirm: false,
        confirmButtonText: "ENTENDIDO"
      });
      return null;
    }

    let diagnosticos = [];
    let procedimientos = [];
    let duplicados = false;

    $("#tblDiagnostico tbody tr").each(function() {
        const idDiagnostico = $(this).find('input[id^="id_diagnostico"]').val();

        if (idDiagnostico) {
            if (diagnosticos.includes(idDiagnostico)) {
                duplicados = true;
                return false; 
            }
            diagnosticos.push(idDiagnostico);
        }
    });

    $("#tblProcedimiento tbody tr").each(function() {
        const idProcedimiento = $(this).find('input[id^="id_procedimiento"]').val();

        if (idProcedimiento) {
            if (procedimientos.includes(idProcedimiento)) {
                duplicados = true;
                return false; 
            }
            procedimientos.push(idProcedimiento);
        }
    });

    if (duplicados) {
        Swal.fire({
            icon: "warning",
            title: "DUPLICADOS DETECTADOS",
            html: `<p>No se pueden registrar diagnósticos o procedimientos repetidos. Por favor, elimine los duplicados antes de continuar.</p>`,
            confirmButtonText: "ENTENDIDO"
        });
        return null;
    }

    const body = buildRequestUpdate();
    const consejeria_id = $("#consejeria_id").val();
    const hospitalizacion_id = $("#hospitalizacion_id").val();

    $.ajax({
      type: "PUT",
      url: `/api/consejeria/${consejeria_id}`,
      contentType: 'application/json',
      data: JSON.stringify(body),
      dataType: "json",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      beforeSend: function() {
        $("#btnSave").prop('disabled', true);
        Swal.fire({
            html: '<h5>ACTUALIZANDO CONSEJERIA...</h5>',
            showLoaderOnConfirm: true,
            onOpen: () => {
                Swal.showLoading();
            }
        });
      },
      success: (response) => {
        Swal.close();
        window.location.replace(`/ver_prestaciones_consejeria/${hospitalizacion_id}`);
      },
      error: (error) => {
        console.log(error);
        Swal.fire({
          icon: 'error',
          title: "CONSEJERIA",
          html: `<p>Ocurrio un error al actualizar la consejeria, intentelo en unos minutos</p>`,
          confirmButtonText: "ENTENDIDO",
        });
      },
      complete: function() {
        Swal.hideLoading();
        $("#btnSave").prop('disabled', false);
      }
    });
  }

  function buildRequestUpdate() {
    const fechaAtencion = moment($("#fecha_atencion").val(), "DD-MM-YYYY").format("YYYY-MM-DD");

    const request = {
      fecha_atencion: fechaAtencion,
      hora_atencion: $("#hora_atencion").val(),
      especializacion_medico: $("#especializacion_medico").val(),
      codigo_upss_profesional: $("#codigo_upss_profesional").val(),
      diagnosticos: getValuesRowByTable("tblDiagnostico") || [],
      procedimientos: getValuesRowByTable("tblProcedimiento") || [],
      observacion: $("#observacion").val(),
    }

    return request;
  }

  function getValuesRowByTable(tableName) {
    const rows = $(`#${tableName} tbody tr`);
    const resultados  = [];
    rows.each(function(index, fila) {
        let valores = {};
        $(fila).find('input, select').each(function() {
            valores[$(this).attr('name')] = $(this).val();
        });
        resultados.push(valores);
    });
    return resultados ;
  }

  function validateLastRowTable(tableId) {
    let lastRow = $(`#${tableId} tbody tr:last`);
    let isValid = true;
    lastRow.find('input:visible, select:visible').each(function() {
      clearInputError($(this).attr("id"));
      if ($(this).val().trim() === '' || $(this).val() === '0') {
        markInputError($(this).attr("id"));
        isValid = false;
      }
    });
    return isValid; 
  }

  function clearInputError(nameInput) {
      const inputElement = $(`#${nameInput}`);
      const parentDiv = inputElement.closest('.form-control, td');
      inputElement.siblings('.form-control-feedback, .label-help').remove();
      parentDiv.removeClass("has-error has-success");
  }

  function markInputError(nameInput, messsageError = null) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest('.form-control, td');
    parentDiv.addClass("has-error");
    inputElement.after(`<span class="label-help">${messsageError || "campo requerido"}</span>`);
  }

  function validateForm() {
    let controlsError = [];
    for (const control of form) {
      if (control.required) {
        const inputElement = $(`#${control.name}`);
        inputElement.nextAll('.label-help').remove();

        let parentDiv = null;
        if (control.tr_id) {
          parentDiv = inputElement.closest('td'); 
        } else {
          parentDiv = inputElement.closest('.form-control, .form-group'); 
        }
        if (parentDiv) {
          if (inputElement.val() === "" || inputElement.val() === "0") {
            // parentDiv.addClass('has-error');
            // inputElement.after(`<span class="label-help">campo requerido</span>`);
            markInputError(inputElement.attr("id"));
            controlsError.push(control);
          } else {
            clearInputError(inputElement.attr("id"));
            // parentDiv.removeClass('has-error');
            // inputElement.nextAll('.label-help').remove();
          }
        }
      }
    }
    return controlsError.length === 0;
  }

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

  function refreshListDiagnostic(newItemDiagnostic) {
    $('select[id^="procedimiento_diagnostico"]').each(function() {
      $(this).append(`<option value="${String(newItemDiagnostic.id)}">${newItemDiagnostic.label}</option>`);
    });
  }

  function buildOptionsSelectDiagnostic() {
    const diagnosticoHistory = $("#diagnosticoHistory").data("list");
    let optionDiagnostic = {};
    if (diagnosticoHistory && diagnosticoHistory.length > 0) {
      diagnosticoHistory.forEach(element => {
        optionDiagnostic = {id: element.id_diagnostico, label: `${element.codigo} - ${element.nombre}`};
        refreshListDiagnostic(optionDiagnostic);
        diagnosticosList.push(optionDiagnostic);
      });
      $('select[id^="procedimiento_diagnostico"]').each(function() {
        const idOriginal = $(this).data("id_original");
        if (idOriginal) {
          $(this).val(idOriginal).trigger('change');
        }
      });
    }
  }

  function clearOptionsSelectDiagnosticHistory() {
    const diagnosticoHistory = $("#diagnosticoHistory").data("list");
    let arrayIds = [];
    if (diagnosticoHistory && diagnosticoHistory.length > 0) {
      diagnosticoHistory.forEach(element => {
        arrayIds.push(parseInt(element.id_diagnostico));
      });
    }

    $('select[id^="procedimiento_diagnostico"]').each(function() {
      arrayIds.forEach(itemId => {
        //$(this).find(`option[value="${itemId}"]`).remove();
      });
    });  
  }
});
