$(document).ready(function () {
  let changes = [];
  let archivoCargados = [];
  let archivosPorFila = {};

  main();

  $("#btnShowDetail").click(function (e) { 
    e.preventDefault();
    $('#modalDetalle').modal('show');
    getHistorialAtenciones();
  });

  /**
   * action: 1 => ver, 2 => editar
   */
  function main(action = 1, messageSwalRef = "") {
    changes = [];
    archivoCargados = [];
    archivosPorFila = {};

    const id_laboratorio = $("#id_laboratorio").val(); 
    const id_prestacion = $("#id_prestacion").val();

    $.ajax({
      url: `/api/nivel_1_2/laboratorio/${id_laboratorio}/prestacion/${id_prestacion}`,
      method: 'GET',
      beforeSend: function () {
        Swal.fire({
          title: 'Cargando Detalles...',
          text: 'Por favor espera',
          allowOutsideClick: false,
          showConfirmButton: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
      }
    })
    .done(function (response) {
      if (response && response.success && response.data) {
        const fechaRegistroTomaMuestra = response.data.ordenLaboratorio.fecha_registro_toma_muestra || moment().format('YYYY-MM-DD HH:mm:ss');
        $("#fecha_registro_toma_muestra").val(fechaRegistroTomaMuestra);
        setValuesForm(response.data);
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        icon: 'error',
        title: 'Error en la solicitud',
        text: textStatus
      });
    })
    .always(function () {
      if (Swal.isVisible()) {
        Swal.close();
      }
      if (parseInt(action) === 2) {
        Swal.fire({
          icon: 'success',
          title: 'Laboratorio',
          text: messageSwalRef,
        })
      }
    });
  }

  function setValuesForm(data) {
    if (data.establecimiento) {
      $("#establecimiento").text(data.establecimiento.nombre);
    }
    if (data.prestacion) {
      $("#id_asegurado").val(data.prestacion.id_asegurado);

      $("#tipo_atencion").val(data.prestacion.tipo_atencion || " - ");
      $("#consultorio_procedencia").val(data.prestacion.servicio || " - ");
      $("#fecha_orden").val(data.prestacion.fecha_atencion || " - ");
      $("#cod_siteds_susalud").val(data.ordenLaboratorio.cod_siteds_susalud || " - ");
      $("#procedencia").val(`${data.prestacion.ubicacion || ""} / CAMA: ${data.prestacion.nmro_cama || ""}`);

      $("#especialista_numero_documento").val(data.prestacion.dni || " - ");
      $("#especialista_apellido_nombres").val(`${data.prestacion.medico_paterno || ""} ${data.prestacion.medico_materno || ""} ${data.prestacion.medico_nombre || ""}`);
      $("#especialista_profesion").val(data.prestacion.profesion || " - ");

      $("#paciente_numero_documento").val(data.prestacion.nro_doc_ident || " - ");
      $("#paciente_apellido_nombres").val(`${data.prestacion.asegurado_paterno || ""} ${data.prestacion.asegurado_materno || ""} ${data.prestacion.asegurado_nombre || ""}`);
      $("#paciente_tipo_beneficiario").val(data.prestacion.parentesco || " - ");
      $("#paciente_numero_hc").val(data.prestacion.nro_historia || " - ");
      $("#paciente_sexo").val(`${data.prestacion.sexo === 'M' ? 'MASCULINO' : 'FEMENINO'}`);
      $("#paciente_edad").val(data.prestacion.edadpaciente || " - ");
      $("#paciente_referido").val(data.prestacion.referido || " - ");
    }

    if (data.diagnosticos && data.diagnosticos.length > 0) {
      buildTableDiagnosticos(data.diagnosticos);
    }

    if (data.examenesLaboratorio && data.examenesLaboratorio.length > 0) {
      buildTableExamenes(data.examenesLaboratorio);

      $("#cantidadExamenes").text(`Cantidad de Análisis Clínico: ${data.examenesLaboratorio.length}`);
    }

    if (data.ordenLaboratorio) {
      $("#observacion_toma_muestra").val(data.ordenLaboratorio.observacion_toma_muestra || " - ");
    }
  }

  function clearValuesForm() {
      if ($.fn.DataTable.isDataTable('#tbDiagnosticos')) {
        $('#tbDiagnosticos').DataTable().clear().draw();
      }

      if ($.fn.DataTable.isDataTable('#tbExamenes')) {
        $('#tbExamenes').DataTable().clear().draw();
      }
    $("#establecimiento").text("");
    $("#id_asegurado").val("");
    $("#tipo_atencion").val("");
    $("#consultorio_procedencia").val("");
    $("#fecha_orden").val("");
    $("#cod_siteds_susalud").val("");
    $("#procedencia").val("");
    $("#especialista_numero_documento").val("");
    $("#especialista_apellido_nombres").val("");
    $("#especialista_profesion").val("");
    $("#paciente_numero_documento").val("");
    $("#paciente_apellido_nombres").val("");
    $("#paciente_tipo_beneficiario").val("");
    $("#paciente_numero_hc").val("");
    $("#paciente_sexo").val("");
    $("#paciente_edad").val("");
    $("#paciente_referido").val("");
    $("#cantidadExamenes").text("");
    $("#observacion_toma_muestra").val("");

    // $("#divBtnEditarOrden").hide();
    // $("#btnEditarOrden").prop('disabled', true);
  }

  function buildTableDiagnosticos(listDiagnosticos) {
    if ($.fn.DataTable.isDataTable('#tbDiagnosticos')) {
      $('#tbDiagnosticos').DataTable().clear().destroy();
    }
    $('#tbDiagnosticos').DataTable({
      data: listDiagnosticos,
      columns: [
        {
          data: null,
          render: function (data, type, row) {
            return `${row.codigo} - ${row.nombre}`;
          }
        },
        { data: 'descripcion' }
      ],
      paging: false,
      searching: false,
      ordering: false,
      info: false,
    });
  }

  function buildTableExamenes(listExamenes) {
    if ($.fn.DataTable.isDataTable('#tbExamenes')) {
      $('#tbExamenes').DataTable().clear().destroy();
    }
    const fecha_registro_toma_muestra = $("#fecha_registro_toma_muestra").val();
    const attentionDateMoment = moment(fecha_registro_toma_muestra, "YYYY-MM-DD HH:mm:ss");
    const currentDate = moment();
    $('#tbExamenes').DataTable({
      data: listExamenes,
      columns: [
        {
          data: null,
          width: '30%',
          render: function (data, type, row, meta) {
            if (parseInt(row.confirmar_toma) === 0 || parseInt(row.confirmar_toma) === 3) {
              return `
                  <div class="row-examen">
                    <i 
                      class="fa fa-check-circle-o fa-2x" 
                      aria-hidden="true" 
                      style="color: #28a745; margin-right: 5px;"
                      data-toggle="tooltip"
                      data-placement="bottom"
                      title="Examen Atendido"
                    ></i>
                    <span><b>${row.codigo}</b> - ${row.nombre}</span>
                  </div>
                  `;  
            }
            if (attentionDateMoment.isSame(currentDate, 'day')) {
              return `
                <div class="row-examen">
                  <label>
                    <input 
                      type="checkbox"
                      id="ac_examen_procesado_${meta.row}"
                      name="ac_examen_procesado_${meta.row}"
                      data-row_id="${meta.row}"
                      > <span><b>${row.codigo}</b> - ${row.nombre}</span>
                  </label>
                </div>`;   
            }
            return `<div class="row-examen">
                      <i 
                        class="fa fa-ban fa-2x" 
                        aria-hidden="true" 
                        style="color: #d9534f; margin-right: 5px;"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Examen No Atendido"
                      ></i>
                      <span><b>${row.codigo}</b> - ${row.nombre}</span>
                    </div>`;
          }
        },
        { data: 'observacion', width: '15%' },
        { data: 'cantidad', width: '5%' },
        { data: 'diagnostico', width: '20%' },
        { 
          data: null, 
          width: '10%',
          render: function (data, type, row) {
            if (parseInt(row.confirmar_toma) === 0 || parseInt(row.confirmar_toma) === 3) {
              return `<span>${row.reembolso ? "SI": "NO"}</span>`
            }
            return "-";
          }
        },
        { 
          data: null,
          width: '20%',
          render: function (data, type, row, meta) {
            archivoCargados[meta.row] = row.files
            let htmlString = `<div style="display: flex; flex-direction: row; justify-content: space-between; column-gap: 5px;">`;

            htmlString += `<div id="file_result_${meta.row}" style="width: 80%; display: flex; flex-direction: row; gap: 7px; flex-wrap: wrap;">`
            if (row.files && row.files.length > 0) {
              row.files.forEach((file, index) => {
                htmlString += `<button
                                type="button"
                                class="btn btn-default btn-sm"
                                id="btnDownloadFile"
                                data-toggle="tooltip"
                                data-path="${file.uri}"
                                data-placement="bottom"
                                title="${file.original_name}"
                              >
                                <i class="fa fa-download text-danger" aria-hidden="true" style="color: #198754"></i>
                              </button>`;
              });
            }
            htmlString += `</div>`

            htmlString += `${ attentionDateMoment.isSame(currentDate, 'day') || parseInt(row.confirmar_toma) === 0 || parseInt(row.confirmar_toma) === 3 ? `<div>
                            <button 
                              type="button"
                              class="btn btn-default btn-sm btnSubirArchivo"
                              id="btnSubirArchivo_${meta.row}"
                              data-toggle="tooltip"
                              data-placement="bottom"
                              title="<strong>Subir archivo de resultados</strong><br>Solo se aceptan PDF"
                              data-html="true"
                              data-target="#ac_file_resultado_${meta.row}"
                              ${parseInt(row.confirmar_toma) === 0 || parseInt(row.confirmar_toma) === 3 ? '' : 'disabled'}
                            >
                              <i class="fa fa-plus-square" aria-hidden="true" style="color: #ff0000"></i>
                            </button>
                            <input 
                              type="file" 
                              id="ac_file_resultado_${meta.row}"
                              name="ac_file_resultado_${meta.row}"
                              data-id_prestacion_procedimiento="${row.id_prestacion_procedimiento}"
                              data-confirmar_toma="${row.confirmar_toma}"
                              accept=".pdf"
                              multiple
                              class="archivo-resultado"
                              style="display: none;"
                            ><br>
                          </div>` : ''}
                        </div>`;
            return htmlString;
            // if (!row.archivo_resultado) {
            //   return `
            //   <button 
            //     type="button"
            //     class="btn btn-link btn-sm btnSubirArchivo"
            //     id="btnSubirArchivo_${meta.row}"
            //     data-toggle="tooltip"
            //     data-placement="bottom"
            //     title="<strong>Subir archivo de resultados</strong><br>Solo se aceptan PDF"
            //     data-html="true"
            //     data-target="#ac_file_resultado_${meta.row}"
            //   >
            //     <i class="fa fa-plus-square" aria-hidden="true" style="color: #ff0000"></i>
            //   </button>
            //   <input 
            //     type="file" 
            //     id="ac_file_resultado_${meta.row}"
            //     name="ac_file_resultado_${meta.row}"
            //     data-id_prestacion_procedimiento="${row.id_prestacion_procedimiento}"
            //     accept=".pdf"
            //     class="archivo-resultado"
            //     style="display: none;"
            //   ><br>
            //   <div id="file_result_${meta.row}"></div>`;
            // }
            // return `<button 
            //           type="button"
            //           class="btn btn-link btn-sm"
            //           id="btnDownloadFile"
            //           data-toggle="tooltip"
            //           data-path="${row.archivo_resultado}"
            //           data-placement="bottom"
            //           title="Ver Resultado"
            //         >
            //           <i class="fa fa-download text-danger" aria-hidden="true" style="color: #198754"></i>
            //         </button>`;
          }  
        }
      ],
      paging: false,
      searching: false,
      ordering: false,
      info: false,
    });

    $('#tbExamenes').on('draw.dt', function () {
      $('[data-toggle="tooltip"]').tooltip();
    });

    $('[data-toggle="tooltip"]').tooltip();
  }

  $(document).on('change', 'input[type="checkbox"][name^="ac_examen_procesado_"]', function () {
    const rowId = $(this).data('row_id');
    const isChecked = $(this).is(':checked');
    // $(`#btnSubirArchivo_${rowId}`).prop('disabled', !isChecked);

    if (isChecked) {
      $(`#btnSubirArchivo_${rowId}`).prop('disabled', false);
    } else {
      $(`#btnSubirArchivo_${rowId}`).prop('disabled', true);
      $(`#ac_file_resultado_${rowId}`).val('');
      archivosPorFila[rowId] = [];
    }
    renderArchivos(rowId);
  })

  $(document).on('click', '.btnSubirArchivo', function () {
    const targetInputSelector = $(this).data('target');
    $(targetInputSelector).click();
  });

  $(document).on('click', '#btnDownloadFile', function () {
    const path = $(this).data('path');
    if (path) {
      const filename = path.split('/').pop();
      downloadResultFile(filename);
    }
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
  }

  function downloadResultFile(filename) {
    const nameWithoutExtension = filename.split('.').slice(0, -1).join('.');

    const url = `/api/nivel_1_2/laboratorio/resultado/${nameWithoutExtension}`;
    window.open(url, '_blank');
  }

  $(document).on('change', '.archivo-resultado', function () {
    const files = this.files;
    const tiposPermitidos = ['application/pdf'];
    const inputId = $(this).attr('id');
    const index = inputId.split('_').pop();

    let htmlString = "";
    if (files && files.length > 0) {
      for (const file of files) {
        if (!tiposPermitidos.includes(file.type)) {
          Swal.fire({
            icon: 'error',
            title: 'Formato no válido',
            html: `El archivo <strong>${file.name}</strong> es de formato incorrecto. Solo se aceptan archivos <strong>PDF</strong>.`,
            confirmButtonText: 'Entendido'
          });
        } else {
          // htmlString += `<div type="button" data-toggle="tooltip" data-placement="bottom" class="file-updated" title="${file.name}">
          //                 <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
          //                   <button type="button" class="btn-remove-file" id="btn_remove_file">
          //                     <i class="fa fa-times"></i>
          //                   </button>
          //                 </div>`;
          if (!archivosPorFila[index]) {
            archivosPorFila[index] = [];
          }
          archivosPorFila[index].push(file);
        }
      }
      $(this).val('');
      renderArchivos(index);
      // $(`#file_result_${index}`).append(htmlString);
    }
    // const archivo = this.files[0];
    // const tiposPermitidos = ['application/pdf'];
    // const inputId = $(this).attr('id');
    // const index = inputId.split('_').pop();
    // if (archivo) {
    //   if (!tiposPermitidos.includes(archivo.type)) {
    //     Swal.fire({
    //       icon: 'error',
    //       title: 'Formato no válido',
    //       html: 'Solo se aceptan archivos <strong>PDF</strong>, <strong>PNG</strong> o <strong>JPG</strong>.',
    //       confirmButtonText: 'Entendido'
    //     });
    //     $(this).val('');
    //     $(`#file_result_${index}`).html('');
    //   } else {
    //     $(`#file_result_${index}`).html(`
    //       <div style="padding-left: 10px; display: flex; align-items: center; border-top: 1px solid #e1e1e1; padding-top: 10px; justify-content: space-between;">
    //         <span class="text-info d-block file_result" style="margin-right: 10px">
    //           <i class="fa fa-file" aria-hidden="true"></i> ${archivo.name}
    //         </span>
    //         <button 
    //           type="button"
    //           class="btn btn-link btn-xs mt-1 ml-1"
    //           id="btnEliminarArchivo"
    //           data-index="${index}"
    //           data-toggle="tooltip"
    //           data-placement="bottom"
    //           title="Eliminar archivo"
    //         >
    //           <i class="fa fa-times" aria-hidden="true" style="color: #ff0000"></i>
    //         </button>
    //       </div>
    //     `
    //     );
    //     $('[data-toggle="tooltip"]').tooltip();
    //   }
    // } else {
    //   $(`#file_result_${index}`).html('');
    // }
    // detectChangesPage(parseInt(index));
    $('[data-toggle="tooltip"]').tooltip();
  });

  $(document).on('click', '#btnEliminarArchivo', function () {
    const index = $(this).data('index');
    $(`#ac_file_resultado_${index}`).val('');
    $(`#file_result_${index}`).html('');
    removeChangePage(parseInt(index));
  });

  function detectChangesPage(idChange) {
    changes.push(idChange);
    if (changes.length) {
      // $("#divBtnEditarOrden").show();
      // $("#btnEditarOrden").prop('disabled', false);
    }
  }

  function removeChangePage(idChange) {
    changes = changes.filter(num => num !== idChange);
    if (changes.length === 0) {
      // $("#divBtnEditarOrden").hide();
      // $("#btnEditarOrden").prop('disabled', true);
    }
  }

  // function getValuesRowByTableExams() {
  //   const rows = $('#tbExamenes tbody tr');
  //   const resultados = [];

  //   const promises = [];

  //   rows.each(function (_, fila) {
  //     const valores = {};
  //     $(fila).find('input, select').each(function () {
  //       const input = $(this);
  //       const type = input.attr('type');
  //       const id_prestacion_procedimiento = input.data('id_prestacion_procedimiento');

  //       if (type === 'file') {
  //         const file = input[0].files[0];
  //         if (file) {
  //           const promise = fileToBase64(file).then(base64 => {
  //             valores['archivo_resultado_nombre'] = file.name;
  //             valores['archivo_resultado_base64'] = base64;
  //             valores['id_prestacion_procedimiento'] = id_prestacion_procedimiento;
  //           });
  //           promises.push(promise);
  //         }
  //         resultados.push(valores);
  //       }
  //     });
  //   });

  //   return Promise.all(promises).then(() => resultados);
  // }

  function buildFormDataFromTable() {
    const rows = $('#tbExamenes tbody tr');
    const formData = new FormData();
    formData.append('id_laboratorio', parseInt($("#id_laboratorio").val()));
    formData.append('id_prestacion', parseInt($("#id_prestacion").val()));
    formData.append('id_asegurado', parseInt($("#id_asegurado").val()));
    formData.append('observacion_toma_muestra', $("#observacion_toma_muestra").val());
    rows.each(function (index, fila) {
      let files = [];
      let rowIndex;
      $(fila).find('input, select').each(function () {
        const input = $(this);
        const name = input.attr('name');
        const type = input.attr('type');
        const matches = name?.match(/^ac_(.+)_(\d+)$/);
        if (!matches) return;
        const cleanName = matches[1];
        rowIndex = matches[2];
        if (type === 'checkbox') {
          formData.append(`examenes[${index}][examen_procesado]`, input.is(':checked') ? 1 : 0);
        }
        const id_prestacion_procedimiento = input.data('id_prestacion_procedimiento');
        const confirmar_toma = input.data('confirmar_toma');
        if (rowIndex && archivosPorFila[rowIndex]) {
          files = archivosPorFila[rowIndex];
        }
        formData.append(`examenes[${index}][id_prestacion_procedimiento]`, id_prestacion_procedimiento);
        formData.append(`examenes[${index}][confirmar_toma]`, confirmar_toma);
        if (files && files.length > 0) {
          Array.from(files).forEach((file, fileIndex) => {
            formData.append(`examenes[${index}][archivos][${fileIndex}]`, file);
          });
        }
      });
    });
    return formData;
  }

  $("#btnEditarOrden").on("click", function () {
    onEdit();
  });

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

  async function onEdit() {
    const id_laboratorio = $("#id_laboratorio").val();

    const formData = await buildFormDataFromTable();
    $.ajax({
      url: `/api/nivel-2/laboratorio/${id_laboratorio}`,
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function () {
        Swal.fire({
          title: 'Actualizando...',
          text: 'Por favor, espere mientras se actualiza la orden.',
          allowOutsideClick: false,
          showConfirmButton: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
      }
    })
    .done(function (response) {
      if (response && response.success) {
        const message = response.message || "Orden registrada exitosamente.";
        clearValuesForm();
        main(2, message);
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

  $(document).on('click', '#btnShowProcedures', function () {
    const idLaboratorio = $(this).data('id_laboratorio');
    const idPrestacion = $(this).data('id_prestacion');

    getExamenesOnHistory(idLaboratorio, idPrestacion);
  });

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
    $(`#file_result_${index}`).empty();

    if (archivoCargados[index] && archivoCargados[index].length > 0) {
      archivoCargados[index].forEach((file, index) => {
        htmlString += `<button
                        type="button"
                        class="btn btn-default btn-sm"
                        id="btnDownloadFile"
                        data-toggle="tooltip"
                        data-path="${file.uri}"
                        data-placement="bottom"
                        title="${file.original_name}"
                      >
                        <i class="fa fa-download text-danger" aria-hidden="true" style="color: #198754"></i>
                      </button>`;
      });
    }
    if (archivosPorFila[index] && archivosPorFila[index].length > 0) {
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
    }

    
    $(`#file_result_${index}`).html(htmlString);

    $('[data-toggle="tooltip"]').tooltip();
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
