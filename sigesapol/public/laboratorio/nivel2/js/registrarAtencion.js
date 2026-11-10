$(document).ready(function () {
  const tipo_atenciones = {
    1: 'AMBULATORIO',
    2: 'EMERGENCIA',
    3: 'HOSPITALIZACION'
  };

  let archivosPorFila = {};

  let form = [
    {id: 'observacion', name: 'observacion', type: 'text', required: false, isRowTable: false},
  ];

  $('#modalDetalle').on('show.bs.modal', function () {
    Swal.fire({
      title: 'Atenciones Médicas...',
      text: 'Por favor, espere mientras se carga el historial.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
  });

  $('#modalDetalle').on('shown.bs.modal', function () {
    console.log('El modal se ha mostrado completamente');
  });

  $('#modalDetalle').on('hide.bs.modal', function () {
    console.log('El modal está a punto de cerrarse');
  });

  $('#modalDetalle').on('hidden.bs.modal', function () {
      ('El modal se ha cerrado completamente');
  });

  $('input[type="checkbox"][id^="ac_examen_procesado_"]').change(function () {
    var rowId = $(this).data('row_id');
    if ($(this).is(':checked')) {
      $('#ac_observacion_' + rowId).prop('disabled', false);
      $('#ac_cantidad_' + rowId).prop('disabled', false);
      // $('#ac_diagnostico_' + rowId).prop('disabled', false);
      $('#ac_reembolso_' + rowId).prop('disabled', false);
      $('#ac_reembolso_' + rowId).prop('disabled', false);
      $('#ac_reembolso_' + rowId).prop('disabled', false);

      $('#btnSubirArchivo_' + rowId).prop('disabled', false);
      $('#ac_file_resultado_' + rowId).val('').prop('disabled', false);
    } else {
      $('#ac_observacion_' + rowId).val('').prop('disabled', true);
      $('#ac_cantidad_' + rowId).val('1').prop('disabled', true);
      // $('#ac_diagnostico_' + rowId).val('').prop('disabled', true);
      $('#ac_reembolso_' + rowId).val('0').prop('disabled', true);

      $('#btnSubirArchivo_' + rowId).prop('disabled', true);
      $('#ac_file_resultado_' + rowId).val('').prop('disabled', true);
      $("#file_result_" + rowId).text("");
    }
    form.forEach((control) => {
      if (control.isRowTable) {
        clearInputError(control.id);
        if (control.id === `ac_cantidad_${rowId}` /* || control.id === `ac_diagnostico_${rowId}` */ || control.id === `ac_reembolso_${rowId}`) {
          control.required = $(this).is(':checked');
        } 
      }
    });
  });

  $('.ac-cantidad').on('input', function () {
    let val = $(this).val();
    val = val.replace(/[^0-9]/g, '');
    if (val !== '') {
      val = parseInt(val);
      if (val < 1) val = 1;
      if (val > 9999) val = 9999;
    }

    $(this).val(val);
  });

  $("#btnSave").click(function (e) { 
    e.preventDefault();
    onSubmit();
  });

  $("#btnShowDetail").click(function (e) { 
    e.preventDefault();
    $('#modalDetalle').modal('show');
    getHistorialAtenciones();
  });

  $('.btnSubirArchivo').on('click', function () {
    const targetInputSelector = $(this).data('target');
    $(targetInputSelector).click();
  });

  $('.archivo-resultado').on('change', function () {
    const files = this.files;
    const tiposPermitidos = ['application/pdf'];
    const inputId = $(this).attr('id');
    const index = inputId.split('_').pop();

    let htmlString = "";
    if (files && files.length > 0) {
      Array.from(files).forEach((file, indexFile) => {
        if (!tiposPermitidos.includes(file.type)) {
          Swal.fire({
            icon: 'error',
            title: 'Formato no válido',
            html: `El archivo <strong>${file.name}</strong> es de formato incorrecto. Solo se aceptan archivos <strong>PDF</strong>.`,
            confirmButtonText: 'Entendido'
          });
        } else {
          // htmlString += `<div data-toggle="tooltip" data-placement="bottom" class="file-updated" title="${file.name}" id="resultado_${index}_${indexFile}">
          //                 <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
          //                 <button type="button" class="btn-remove-file" id="btn_remove_file" data-index_row="${index}" data-index_file="${indexFile}">
          //                   <i class="fa fa-times"></i>
          //                 </button>
          //               </div>`;
          if (!archivosPorFila[index]) {
            archivosPorFila[index] = [];
          }
          archivosPorFila[index].push(file);
        }
      });
      $(this).val('');
      renderArchivos(index);
      // $(`#file_result_${index}`).html(htmlString);
    }

    // if (archivo) {
      // if (!tiposPermitidos.includes(archivo.type)) {
      //   Swal.fire({
      //     icon: 'error',
      //     title: 'Formato no válido',
      //     html: 'Solo se aceptan archivos <strong>PDF</strong>, <strong>PNG</strong> o <strong>JPG</strong>.',
      //     confirmButtonText: 'Entendido'
      //   });
      //   $(this).val('');
      //   $(`#file_result_${index}`).html('');
      // } else {
      //   $(`#file_result_${index}`).html(`
      //     <hr style="margin: 4px 0px;">
      //     <span class="text-info ml-2 d-block file_result">
      //       <i class="fa fa-file" aria-hidden="true"></i> ${archivo.name}
      //     </span>
      //     <button 
      //       type="button"
      //       class="btn btn-danger btn-xs mt-1 ml-1"
      //       id="btnEliminarArchivo"
      //       data-index="${index}"
      //       data-toggle="tooltip"
      //       data-placement="bottom"
      //       title="Eliminar archivo"
      //     >
      //     <i class="fa fa-trash-o" aria-hidden="true"></i>
      //   </button>
      //   `
      //   );
      //   $('[data-toggle="tooltip"]').tooltip();
      // }
    // } else {
    //   $(`#file_result_${index}`).html('');
    // }
    $('[data-toggle="tooltip"]').tooltip();
  });

  $(document).on('click', '#btnEliminarArchivo', function () {
    const index = $(this).data('index');
    $(`#ac_file_resultado_${index}`).val(''); // limpiar input file
    $(`#file_result_${index}`).html(''); // limpiar vista del nombre
  });
  
  setValuesDatosGenerales();
  buildForm();
  generarCodigoSiteds();

  async function generarCodigoSiteds() {
    Swal.fire ({
      title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
      html: "<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor</span></div>",
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      onBeforeOpen: () => Swal.showLoading (),
    })

    $("#cod_siteds_susalud").prop("readonly", true);

    const nro_doc_ident = $("#nro_doc_ident").val();
    const id_tipo_atencion = $("#id_tipo_atencion").val();
    const id_ipress = $("#id_ipress").val();
    const id_asegurado = $("#id_asegurado").val();
    const id_establecimiento = $("#id_establecimiento").val();
    const id_prestacion = $("#id_prestacion").val();

    const codigoSitedsValid = await buscarSitedsGeneradoHoy(nro_doc_ident, id_tipo_atencion, id_ipress);
    if (codigoSitedsValid && codigoSitedsValid.cod_siteds_susalud) {
      $("#cod_siteds_susalud").val(codigoSitedsValid.cod_siteds_susalud);
      Swal.fire({
        icon: "success",
        title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS</span></div>",
        showConfirmButton: false,
        timer: '1500'
      })
      Swal.hideLoading();
      return codigoSitedsValid;
    }

    const body = {
      "idCorrelativo": id_prestacion,
      "aseguradoId": id_asegurado,
      "establecimientoId": id_establecimiento,
      "tipoAtencionId": id_tipo_atencion,
      "servicioId": "172"
    };
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      url:'/v2/ws-dirsapol-siteds',
      type:'POST',
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify(body),
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      success: (result, textStatus, jqXHR) => {
        if (result && result.nuAutorizacion) {
          $("#cod_siteds_susalud").val(result.nuAutorizacion);
          if (result.nuAutorizacion.endsWith("T") || String(result.nuAutorizacion).length <= 6) {
            Swal.fire({
              icon: "warning",
              title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>LABORATORIO</span></div>",
              html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Puede generar su código válido en la web oficial de SITEDS y reemplazar el código temporal en el sistema.</span></div>`,
              confirmButtonText: 'ENTENDIDO',
            });
            $("#cod_siteds_susalud").prop("readonly", false);
          } else {
            Swal.fire({
              icon: (parseInt(jqXHR.status) === 201 || parseInt(jqXHR.status) === 200) ? "success" : 'warning',
              title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS</span></div>",
              showConfirmButton: false,
              timer: '1500'
            });
          }
        }
      },
      error: function(xhr, status, error){
        $("#cod_siteds_susalud").val("").prop("readonly", false);
        const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
        Swal.fire({
          icon: 'error',
          title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
          html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
          confirmButtonText: 'ENTENDIDO',
        });
      }, 
      complete: function(xhr, status) {
        Swal.hideLoading();
      }
    })
  }

  function buscarSitedsGeneradoHoy(asegurado_dni, tipo_atencion_id, id_press) {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "get",
        url: `/api/laboratorio/buscar-siteds-generado-hoy/${asegurado_dni}/${tipo_atencion_id}/${id_press}`,
        dataType: "json",
        success: function (response) {
          resolve(response);
        },
        error: function (error) {
          reject(error);
        }
      });
    });
  }

  function setValuesDatosGenerales() {
    const id_tipo_atencion = $("#id_tipo_atencion").val();
    if (id_tipo_atencion) {
      const tipo_atencion = tipo_atenciones[parseInt(id_tipo_atencion)];
      $("#tipo_atencion").val(tipo_atencion); 
      $("#consultorio_procedencia").val(tipo_atencion);
    }
  }

  function buildForm() {
    const examenes = window.proclaboratorio;
    if (examenes && examenes.length > 0) {
      let control = {}
      examenes.forEach((element, index) => {
        control = { id: `ac_examen_procesado_${index + 1}`, name: `ac_examen_procesado_${index + 1}`, type: 'checkbox', isRowTable: true, required: false };
        form.push(control);

        control = { id: `ac_observacion_${index + 1}`, name: `ac_observacion_${index + 1}`, type: 'text', isRowTable: true, required: false };
        form.push(control);

        control = { id: `ac_cantidad_${index + 1}`, name: `ac_cantidad_${index + 1}`, type: 'text', isRowTable: true, required: false };
        form.push(control);

        control = { id: `ac_diagnostico_${index + 1}`, name: `ac_diagnostico_${index + 1}`, type: 'select', isRowTable: true, required: false };
        form.push(control);

        control = { id: `ac_reembolso_${index + 1}`, name: `ac_reembolso_${index + 1}`, type: 'select', isRowTable: true, required: false };
        form.push(control);
      });
    }
  }

  async function onSubmit() {
    const formValid = validateForm();
    if (!formValid) {
      Swal.fire({
        icon: "warning",
        title: "LABORATORIO",
        html: `<p>Faltan campos obligatorios. Por favor, revisa el formulario.</p>`,
        showLoaderOnConfirm: false,
        confirmButtonText: "ENTENDIDO"
      });
      return null;
    }
    const formData = await buildFormData();
    const id_laboratorio = $("#id_laboratorio").val();
    const id_prestacion = $("#id_prestacion").val();
    $.ajax({
      url: '/api/nivel-2/laboratorio/atencion',
      method: 'POST',
      processData: false,
      contentType: false,
      data: formData,
      beforeSend: function () {
        Swal.fire({
          title: 'Registrando...',
          text: 'Por favor, espere mientras se registra la orden.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
      }
    })
    .done(function (response) {
      if (response && response.success) {
        const message = response.message || "Orden registrada exitosamente.";
        Swal.close();
        Swal.fire({
          icon: 'success',
          title: 'Laboratorio',
          text: message,
        });
        window.location.href = `/nivel_1_2/laboratorio/${id_laboratorio}/prestacion/${id_prestacion}`;
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.close();
      const message = jqXHR.responseJSON?.message || "Error al registrar la orden.";
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message
      });
    });
  }

  // async function buildRequestSave() {
  //   let examenes = getValuesRowByTableExams();
  //   const request = {
  //     examenes: examenes || [],
  //     id_laboratorio: parseInt($("#id_laboratorio").val()),
  //     id_tipo_atencion: parseInt($("#id_tipo_atencion").val()),
  //     id_prestacion: parseInt($("#id_prestacion").val()),
  //     cod_siteds_susalud: $("#cod_siteds_susalud").val(),
  //     id_evaluacion: parseInt($("#id_evaluacion").val()),
  //     observacion: $("#observacion").val()
  //   }
  //   return request;
  // }

  async function buildFormData() {
    let examenes = getValuesRowByTableExams();
    const formData = new FormData();
    formData.append('id_laboratorio', parseInt($("#id_laboratorio").val()));
    formData.append('id_tipo_atencion', parseInt($("#id_tipo_atencion").val()));
    formData.append('id_prestacion', parseInt($("#id_prestacion").val()));
    formData.append('cod_siteds_susalud', $("#cod_siteds_susalud").val());
    formData.append('id_evaluacion', parseInt($("#id_evaluacion").val()));
    formData.append('observacion', $("#observacion").val());

    // examenes.forEach((examen, index) => {
    //   for (const key in examen) {
    //     if (key === 'archivo') {
    //       formData.append(`examenes[${index}][archivo]`, examen[key]);
    //     } else {
    //       formData.append(`examenes[${index}][${key}]`, examen[key]);
    //     }
    //   }
    // });
    examenes.forEach((examen, index) => {
      for (const key in examen) {
        if (key === 'archivos') {
          const valor = examen[key];
          if (valor instanceof FileList) {
            Array.from(valor).forEach((file, fileIndex) => {
              formData.append(`examenes[${index}][archivos][${fileIndex}]`, file);
            });
          } else if (Array.isArray(valor)) {
            valor.forEach((file, fileIndex) => {
              formData.append(`examenes[${index}][archivos][${fileIndex}]`, file);
            });
          } else if (valor instanceof File) {
            formData.append(`examenes[${index}][archivos][0]`, valor);
          }
        } else {
          formData.append(`examenes[${index}][${key}]`, examen[key]);
        }
      }
    });

    return formData;
  }

  function clearInputError(nameInput) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest('td');
    inputElement.siblings('.form-control-feedback, .label-help').remove();
    parentDiv.removeClass("has-error has-success");
  }

  function markInputError(nameInput, messsageError = null) {
    const inputElement = $(`#${nameInput}`);
    inputElement.closest('td, .form-group').addClass("has-error");
    inputElement.after(`<span class="label-help">${messsageError || "campo requerido"}</span>`);
  }

  function validateForm() {
    let controlsError = [];
    for (const control of form) {
      if (control.required) {
        const inputElement = $(`#${control.name}`);
        inputElement.nextAll('.label-help').remove();

        let parentDiv = null;
        if (control.isRowTable) {
          parentDiv = inputElement.closest('td'); 
        } else {
          parentDiv = inputElement.closest('.form-control, .form-group'); 
        }
        if (parentDiv) {
          if (inputElement.val() === "") {
            markInputError(inputElement.attr("id"));
            controlsError.push(control);
          } else {
            clearInputError(inputElement.attr("id"));
          }
        }
      }
    }
    return controlsError.length === 0;
  }

  // function getValuesRowByTableExams() {
  //   const rows = $('#tbAnalisisClinico tbody tr');
  //   const resultados = [];

  //   const promises = [];

  //   rows.each(function (_, fila) {
  //     const valores = {};

  //     $(fila).find('input, select').each(function () {
  //       const input = $(this);
  //       const name = input.attr('name');
  //       const type = input.attr('type');
  //       const matches = name?.match(/^ac_(.+)_\d+$/);
  //       if (!matches) return;
  //       const cleanName = matches[1];

  //       if (type === 'checkbox') {
  //         valores[cleanName] = input.is(':checked') ? 1 : 0;
  //       } else if (type === 'file') {
  //         const file = input[0].files[0];
  //         if (file) {
  //           const promise = fileToBase64(file).then(base64 => {
  //             valores['archivo_resultado_nombre'] = file.name;
  //             valores['archivo_resultado_base64'] = base64;
  //           });
  //           promises.push(promise);
  //         }
  //       } else {
  //         valores[cleanName] = input.val();
  //       }
  //     });
      
  //     if (parseInt(valores.examenProcesado) === 1) {
  //       resultados.push(valores);
  //     }
  //   });

  //   return Promise.all(promises).then(() => resultados);
  // }
  
  function getValuesRowByTableExams() {
    const rows = $('#tbAnalisisClinico tbody tr');
    const resultados = [];
    rows.each(function (_, fila) {
      const valores = {};

      let rowIndex;

      $(fila).find('input, select').each(function () {
        const input = $(this);
        const name = input.attr('name');
        const type = input.attr('type');
        // const matches = name?.match(/^ac_(.+)_\d+$/);
        const matches = name?.match(/^ac_(.+)_(\d+)$/);
        if (!matches) return;
        const cleanName = matches[1];
        rowIndex = matches[2];

        // if (type === 'checkbox') {
        //   valores[cleanName] = input.is(':checked') ? 1 : 0;
        // } else if (type === 'file') {
        //   if (input[0].files && input[0].files.length > 0) {
        //     valores.archivos = files;
        //   }
        // } else {
        //   valores[cleanName] = input.val();
        // }
        if (type === 'checkbox') {
          valores[cleanName] = input.is(':checked') ? 1 : 0;
        } else {
          valores[cleanName] = input.val();
        }
      });

      if (rowIndex && archivosPorFila[rowIndex]) {
        valores.archivos = archivosPorFila[rowIndex];
      }
      
      if (parseInt(valores.examenProcesado) === 1) {
        resultados.push(valores);
      }
    });

    return resultados;
  }

  function fileToBase64(file) {
    const deferred = $.Deferred();
    const reader = new FileReader();
    reader.onload = function (e) {
      deferred.resolve(e.target.result);
    };
    reader.onerror = function (e) {
      deferred.reject(e);
    };
    reader.readAsDataURL(file);
    return deferred.promise();
  }

  $(document).on('click', '#btnShowProcedures', function () {
    const idLaboratorio = $(this).data('id_laboratorio');
    const idPrestacion = $(this).data('id_prestacion');

    getExamenesOnHistory(idLaboratorio, idPrestacion);
  });

  function getHistorialAtenciones() {
    const id_asegurado = $("#id_asegurado").val();
    $('#tbListDetalle').DataTable({
      destroy: true,
      paging: false,
      ordering: false,
      searching: false,
      info: false,
      ajax: {
        url: `/api/laboratorio/historial/asegurado/${id_asegurado}`,
        type: 'GET',
        dataSrc: 'data',
        error: function (jqXHR) {
          const message = jqXHR.responseJSON?.message || "Error al obtener el historial.";
          Swal.fire({ icon: 'error', title: 'Error', text: message });
        }
      },
      columns: [
        { data: 'cod_orden_lab', width: '10%' },
        {
          data: null,
          width: '10%',
          render: function (data, type, row) {
            return `${moment(row.fecha_cita_laboratorio).format('DD/MM/YYYY HH:mm')}`;
          }
        },
        { data: 'tipo_atencion', width: '10%' },
        { data: 'establecimiento', width: '10%' },
        { data: 'servicio', width: '10%' },
        {
          data: null,
          width: '20%',
          render: function (data, type, row) {
            return `<div>
                      <span><strong>DNI: </strong> ${row.medico_dni}</span><br>
                      <span><strong>Nombre: </strong> ${row.medico_nombre}</span><br>
                      <span><strong>Apellidos: </strong> ${row.medico_paterno} ${row.medico_materno}</span>
                    </div>`;
          }
        },
        { 
          data: null, 
          width: '10%',
          render: function (data, type, row) {
            return `<div>
                      <button 
                        type="button"
                        class="btn btn-success btn-xs"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Ver Examenes"
                        id="btnShowProcedures"
                        data-id_laboratorio="${row.id_laboratorio}"
                        data-id_prestacion="${row.id_prestacion}"
                      >
                        <i class="fa fa-eye"></i>
                      </button>
                    </div>`;
          }
        },
      ],
      initComplete: function () {
        Swal.close(); // Cierra el loader cuando se termina de cargar la tabla
      }
    });

    $('#tbListDetalle').on('draw.dt', function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
  }

  function getExamenesOnHistory(idLaboratorio, idPrestacion) {
    $.ajax({
      url: `/api/laboratorio/${idLaboratorio}/prestacion/${idPrestacion}/examenes`,
      method: 'GET',
      beforeSend: function() {
        Swal.fire({
          title: 'Cargando...',
          text: 'Por favor espere',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });
      }
    })
    .done(function(response) {
      Swal.close();
      if (response.success) {
        const rows = response.data.map(item => `
          <tr>
            <td>${item.codigo}</td>
            <td>${item.nombre}</td>
            <td>${item.observacion || '-'}</td>
          </tr>
        `).join('');

        const tabla = `
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Observación</th>
              </tr>
            </thead>
            <tbody>${rows}</tbody>
          </table>
        `;

        Swal.fire({
          title: 'EXAMENES',
          html: tabla,
          width: '80%',
          showCloseButton: true,
          allowOutsideClick: false,
          allowEscapeKey: false
        });

      } else {
        Swal.fire('Error', 'No se encontraron datos.', 'error');
      }
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      Swal.close();
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Algo salió mal: ' + textStatus
      });
    });
  }

  function renderArchivos(index) {
    let htmlString = "";

    archivosPorFila[index].forEach((file, indexFile) => {
      htmlString += `
        <div data-toggle="tooltip" data-placement="bottom" class="file-updated" title="${file.name}" id="resultado_${index}_${indexFile}">
          <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
          <button type="button" class="btn-remove-file" id="btn_remove_file" data-index_row="${index}" data-index_file="${indexFile}">
            <i class="fa fa-times"></i>
          </button>
        </div>
      `;
    });

    $(`#file_result_${index}`).html(htmlString);
  }
  
  $(document).on('click', '#btn_remove_file', function (e) {
    // e.stopPropagation();
    // const div = $(this).closest('.file-updated');
    const rowIndex = $(this).data('index_row');
    const fileIndex = $(this).data('index_file');

    archivosPorFila[rowIndex].splice(fileIndex, 1);

    // div.tooltip('destroy').remove();
    renderArchivos(rowIndex);
  });
});