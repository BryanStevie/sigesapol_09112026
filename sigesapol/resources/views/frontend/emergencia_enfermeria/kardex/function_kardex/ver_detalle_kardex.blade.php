<script>
  $('#modalShowDetailKardex').on('shown.bs.modal', async function () {
    try {
      Swal.fire({
        title: "KARDEX ENFERMERIA",
        html: '<h5>OBTENIENDO DATOS DEL KARDEX...</h5>',
        showLoaderOnConfirm: true,
        onOpen: () => {
            Swal.showLoading();
        }
      });
      const id_asegurado = $('#id_asegurado').val();  
      const id_establecimiento = $('#id_establecimiento').val();
      const id_kardex = $('#id_kardex').val();

      setValuesPacient();
      
      const kardex = await obtenerKardexById(id_kardex);
      setValuesKardexEnfermeria(kardex);
      $("#mkr_table_diagnostivos_atencion_detalle tbody").empty();
      if (kardex && parseInt(kardex.prestacion_id) > 0) {
        const diagnosticos = await obtenerDiagnosticosPorPrestacionId(kardex.prestacion_id);
        if (diagnosticos && diagnosticos.length > 0) {
          setValuesTableDiagnosticos(diagnosticos);
          $("#mkr_tratamiento_detalle").val(diagnosticos[0].tratamiento_auxiliar || "-");
        } else {
        }
      } else {
        $("#mkr_tratamiento_detalle").val('No disponible, no existe la atencion por el momento.');
          $('#mkr_table_diagnostivos_atencion_detalle tbody').append(`
            <tr>
              <td colspan="5">No disponible, no existe la atencion por el momento.</td>
            </tr>
          `);
      }

    } catch (error) {
      console.log(error);
    } finally {
      Swal.close();
      Swal.hideLoading();
    }
  })

  $('#modalShowDetailKardex').on('hide.bs.modal', async function () {
    $("#alert_kardex_anulado").hide();
  })

  function obtenerHistoriaClinica(id_asegurado, id_establecimiento) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/asegurado-historias/asegurado/${id_asegurado}/establecimiento/${id_establecimiento}`,
        method: "GET",
        dataType: 'json',
        success: function(result) {
          resolve(result);
        },
        error: function(xhr, status, error) {
          reject(error);
        }
      });
    });
  }

  function obtenerKardexById(id_kardex) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/kardex-enfermerias/full/${id_kardex}`,
        method: "GET",
        dataType: 'json',
        success: function(result) {
          resolve(result);
        },
        error: function(xhr, status, error) {
          reject(error);
        }
      });
    });
  }

  function obtenerDiagnosticosPorPrestacionId(prestacionId) {
    return new Promise(function (resolve, reject) {
      $.ajax({
        url: `/buscar_datos_prestacion_enfermeria/${prestacionId}`,
        method: "GET",
        dataType: 'json',
        success: function(result) {
          resolve(result);
        },
        error: function(xhr, status, error) {
          resolve(null)
        }
      });
    });
  }

  function setValuesPacient() {
    $("#mkr_nro_doc_ident_detalle").val(`${dataCabecera.nro_doc_ident || ""}`);
    $("#mkr_asegurado_detalle").val(`${dataCabecera.nombre || ""} ${dataCabecera.paterno || ""} ${dataCabecera.materno || ""}`);
    $("#mkr_parentesco_detalle").val(`${dataCabecera.parentesgo || "-"}`);
    $("#mkr_sexo_detalle").val(`${String(dataCabecera.sexo).toUpperCase() === "M" ? "MASCULINO" : "FEMENINO" || ""}`);
    $("#mkr_edad_detalle").val(`${dataCabecera.edad_corta || "-"}`);
    $("#mkr_sangre_detalle").val(`${dataCabecera.tipo_sangre || "-"}`);
    $("#mkr_historia_clinica_detalle").val(`${dataCabecera.nro_historia || "-"}`);
    $("#mkr_alergia_detalle").val(`${dataCabecera.alergia || "-"}`);
  }

  function setValuesKardexEnfermeria(data) {
    console.log(data)
    if (data && JSON.stringify(data) !== "{}") {
      $("#mkr_fecha_atencion_detalle").val(moment(data.kardex_enfermerias_created_at).format('YYYY-MM-DD') || "-");
      $("#mkr_hora_atencion_detalle").val(moment(data.kardex_enfermerias_created_at).format('HH:mm')  || "-");
      $("#mkr_prioridad_atencion_detalle").val(data.prioridad_nombre || "-");
      $("#mkr_turno_detalle").val(data.turno_nombre || "-");

      if (data.medicamentos && data.medicamentos.length > 0) {
        buildRowsTableMedicamentos(data.medicamentos);
      }

      setValuesTableExamenesLab(data.examenesLaboratorio);
      setValuesTableExamenesImagen(data.examenesImagen);
      setValuesCuidadosEnfermero(data.cuidadoEnfermeros);
      setValuesTableInterconsulta(data.interconsultas);

      handlerShowBtnFirmar(data.kardex_enfermerias_estado, data.kardex_enfermerias_flag_firma);
    }
  }

  function buildRowsTableMedicamentos(data) {
    $('#tableMedicamentosKardexDetalle tbody').empty();
    const sections = {
      0: "HIDRATACIONES",
      1: "INFUSIONES",
      2: "ANTIBIOTICOS",
      3: "OTROS",
      4: "INHALADORES",
      5: "TRASFUSIONES",
      6: "HEMOGLUCOTESTES",
    }
    let rowString = "";
    let titleSection = "";
    if (data && data.length > 0) {
        const medicamentosPorFlagRegistro = agruparMedicamentosPorFlagRegistro(data);
        const tbody = $('#tableMedicamentosKardexDetalle tbody');
        $.each(medicamentosPorFlagRegistro, function(flag_registro, medicamentos) {
            titleSectionHtml = `<tr><td colspan="7" class="titulo-categoria"><strong>${sections[parseInt(flag_registro)] || "OTROS"}</strong></td></tr>`;
            tbody.append(titleSectionHtml);

            $.each(medicamentos, function(index, medicamento) {
              tbody.append(buildRowHtmlString(medicamento));
            });
        });
    }
  }

  function buildRowHtmlString(data) {
      let timesString = "";
      if (data.horarios && data.horarios.length > 0) {
          data.horarios.forEach((horario, index) => {
              if (horario.aplicacion) {
              timesString += `<b class='strikethrough'>${moment(horario.hora, "HH:mm:ss").format("HH:mm")}</b> - `;
              } else {
              timesString += `<b>${moment(horario.hora, "HH:mm:ss").format("HH:mm")}</b> - `;    
              }
          });
      }
      return ` 
      <tr>
          <td>
              <p>${data.id_infusion ? data.infusion_descripcion : data.medicamento || "-"}</p>
              <p>${timesString}</p>
          </td>
          <td>${data.usuario_nombre || ""} ${data.usuario_paterno || ""} ${data.usuario_materno || ""}</td>
          <td>${data.via_administracion_descripcion || "-"}</td>
          <td>C/${data.frecuencia || "0"}</td>
          <td>${data.dias_administracion || "0"} DÍAS</td>
      </tr>`;
  }

  function setValuesTableExamenesLab(data) {
    $('#tableExamAuxLabDetalle').DataTable().destroy();
    $('#tableExamAuxLabDetalle').DataTable({
        data: data,
        paging: false,  
        searching: false,
        ordering: false,
        info: false,
        columns: [
            { data: 'fecha', title: 'FECHA' },
            { data: 'hora', title: 'HORA' },
            { data: 'nombre_procedimiento', title: 'NOMBRE EXAMEN' },
            { 
              data: null, 
              title: 'TOMA DE MUESTRA',
              render: function (data, type, row) {
                  return parseInt(row.toma_muestra) === 1 ? "SI" : "NO";
              }
            },
            { 
              data: 'ciudad', 
              title: 'RESULTADO',
              render: function (data, type, row) {
                  return parseInt(row.resultado) === 1 ? "SI" : "NO";
              } 
            },
            { 
              data: 'ciudad', 
              title: 'SIN REACTIVO',
              render: function (data, type, row) {
                  return parseInt(row.reactivo) === 1 ? "SI" : "NO";
              }
            }
        ]
    });
  }

  function setValuesTableExamenesImagen(data) {
    $('#tableExamAuxImagDetalle').DataTable().destroy();
    $('#tableExamAuxImagDetalle').DataTable({
        data: data,
        paging: false,  
        searching: false,
        ordering: false,
        info: false,
        columns: [
            { data: 'fecha', title: 'FECHA' },
            { data: 'hora', title: 'HORA' },
            { data: 'nombre_imagen', title: 'NOMBRE EXAMEN' },
            { 
              data: null, 
              title: 'TOMA DE IMAGEN',
              render: function (data, type, row) {
                  return parseInt(row.toma_imagen) === 1 ? "SI" : "NO";
              }
            }
        ]
    });
  }

  function setValuesCuidadosEnfermero(data) {
    if (data && JSON.stringify(data) !== "{}") {
      $("#control_funciones_vitales_detalle").html(parseInt(data.cfv) === 1 ? "SI" : "NO");
      $("#barrera_hematoencefalica_detalle").html(parseInt(data.bhe) === 1 ? "SI" : "NO");
      $("#cabecera_detalle").val(data.cabecera || "-");
      $("#id_oxigeno_terapia_detalle").val(data.oxigeno_terapia_descripcion || "-");
      $("#oxigenoterapia_cantidad_detalle").val(data.oxigenoterapia_cantidad || "-");
      $("#movilización_detalle").html(parseInt(data.movilizacion) === 1 ? "SI" : "NO");
      /* $("#hemoglucotest_detalle").val(data.hemoglucotest || "-"); */
      var hemoglucotest = data.hemoglucotest || [];
      var html = "-";

      if (hemoglucotest) {
          try {
              if (typeof hemoglucotest === "string") {
                  hemoglucotest = JSON.parse(hemoglucotest);
              }

              if (Array.isArray(hemoglucotest) && hemoglucotest.length > 0) {
                  html = hemoglucotest.map(function (hora) {
                      return '<span class="label label-primary" ' +
                          'style="display:inline-block; margin:2px; font-size:12px;">' +
                          $('<div>').text(hora).html() +
                          '</span>';
                  }).join(" ");
              }
          } catch (e) {
              html = "-";
          }
      }

      $("#hemoglucotest_detalle").html(html);
    }
  }

  function setValuesTableInterconsulta(data) {
    $('#tableInterconsultaDetalle').DataTable().destroy();
    $('#tableInterconsultaDetalle').DataTable({
        data: data,
        paging: false,  
        searching: false,
        ordering: false,
        info: false,
        columns: [
            { data: 'consultorio_nombre', title: 'NOMBRE DEL SERVICIO' },
            { 
              data: null, 
              title: 'FECHA ENVIO',
              render: function (data, type, row) {
                return moment(row.fecha_envio, "YYYY-MM-DD").format("DD/MM/YYYY");
              }
            },
            { 
              data: null, 
              title: 'FECHA RESPUESTA',
              render: function (data, type, row) {
                return moment(row.fecha_respuesta, "YYYY-MM-DD").format("DD/MM/YYYY");
              }
            }
        ]
    });
  }

  function setValuesTableDiagnosticos(data) {
    $('#mkr_table_diagnostivos_atencion_detalle').DataTable().destroy();
    $('#mkr_table_diagnostivos_atencion_detalle').DataTable({
        data: data,
        paging: false,  
        searching: false,
        ordering: false,
        info: false,
        columns: [
            { data: 'codigo_diagnostico', title: 'CODIGO' },
            { data: 'nombre_diagnostico', title: 'DESCRIPCIÓN' },
            { data: 'tipo_diagnostico', title: 'TIPO' },
            { data: 'clasificacion', title: 'CLASIFICACIÓN' }
        ]
    });
  }

  function firmarPdfKardex() {
    $('#btnFirmarPdfKardex').attr("disabled", true);
    $("#flag_firma_enfermeria").val("2");
    const id_kardex = $('#id_kardex').val();
    const fechaHora = moment().format("YYYY-MM-DD HH:mm:ss");
    $.ajax({
        url: `/api/kardex-enfermerias/pdf-firmar/${id_kardex}/${fechaHora}`,
        type: "post",
        dataType: 'json'
    })
    .done(function(resultado) {
      if (resultado.sw) {
          var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
          $("#doc_firmar").val(`${resultado.csv}.7z`);
          $("#opt_firma").val(`${resultado.opt}`);
          initInvoker('W');
      }
    })
    .fail(function(jqXHR, textStatus) {
        console.log("Request failed: " + textStatus)
    })
    .always(function() {
      $('#btnFirmarPdfKardex').attr("disabled", false);
    });
  }

  /* window.addEventListener('getArgumentsKardex', async function(e) {
    await ObtieneArgumentosParaFirmaDesdeLaWebKardex();
  }); */

  async function getArgumentsKardex() {
    arg = document.getElementById("argumentos_kardex").value;
    await dispatchEventClient('sendArguments', arg);
  }

  async function ObtieneArgumentosParaFirmaDesdeLaWebKardex() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    $.post("{{route('postArgumentsKardexEnfermeria')}}", {
      _token: CSRF_TOKEN,
      type: "W",
      documentName: $('#doc_firmar').val(),
      entidad: <?=Auth::user()->dni?>
    }, function(data, status) {
      document.getElementById("argumentos_kardex").value = data;
      getArgumentsKardex();
    });
  }

  function handlerShowBtnFirmar(estado, flagFirma) {
    let htmlString = "";
    const flagRefirma = flagFirma ?? 0;
    if (estado === 1) {
      if (flagFirma === 1) {
        htmlString = `<a href="/${moment().format("YYYY/MM/DD")}/${id_kardex}" class="btn btn-primary btn-block" target="_blank"> <i class="fa fa-download" aria-hidden="true"></i> DESCARGAR KARDEX FIRMADO</a>`
      } else {
        htmlString  = `<button id="btnFirmarPdfKardex" type="button" class="btn btn-success btn-block"><i class="fa fa-pencil" aria-hidden="true"></i> FIRMAR</button>`;
      }
    } else {
      $("#alert_kardex_anulado").show();
    }
    $("#btnOkContainer").html(htmlString); 

    $("#btnFirmarPdfKardex").on("click", function () {
      Swal.fire({
          title: '¿Está seguro?',
          text: "¿Desea firmar el registro de kardex?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, firmar!'
      }).then((result) => {
        if (result.isConfirmed) {
          firmarPdfKardex();
        }
      })    
    });
  }
</script>