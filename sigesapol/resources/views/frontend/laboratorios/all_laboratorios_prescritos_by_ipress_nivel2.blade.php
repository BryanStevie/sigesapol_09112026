<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master')
<link href='js/fullcalendar-2.3.2/fullcalendar.css' rel='stylesheet' />
<link href='js/fullcalendar-2.3.2/fullcalendar.print.css' rel='stylesheet' media='print' />
@section('renderjs')
<script src='js/fullcalendar-2.3.2/lib/moment.min.js'></script>
<script src='js/fullcalendar-2.3.2/fullcalendar.min.js'></script>
<script src='js/fullcalendar-2.3.2/lang/es.js'></script>
<script src="https://unpkg.com/popper.js@1"></script>
<script src="https://unpkg.com/tippy.js@5"></script>
<style>
  #fechaDesde {
    background-color: #fff !important;
  }
  #fechaHasta {
    background-color: #fff !important;
  }
  .custom-header-cambiar-estado {
    background-color: #d9534f !important;
    border-color: #d43f3a !important;
  }
  #obs_asistencia_del_paciente {
    resize: none;
  }
  .swal2-icon.swal2-warning {
    font-size: 15px;
  }
  .align-middle {
    vertical-align: middle;
  }
  #tblListadoLaboratorio th,
  #tblListadoLaboratorio td {
    vertical-align: middle !important;
  }
</style>
<script>
  $(document).ready(function () {
    $('#modalReprogramar').on('show.bs.modal', function (e) {
      $("#divHorario").html("");
      $("#panelHorario").hide();
    });
    $('#modalReprogramar').on('hidden.bs.modal', function (e) {
      $("#divHorario").html("");
      $("#panelHorario").hide();
    });

    const sessionFlashError = {!! json_encode(session('error')) !!};
    const sessionFlashSuccess = {!! json_encode(session('success')) !!};
    if (sessionFlashError || sessionFlashSuccess) {
      Swal.fire({
        icon: sessionFlashSuccess ? 'success' : 'error',
        title: "LABORATORIO",
        html: `<p style="font-size: 13px">${sessionFlashError || sessionFlashSuccess}</p>`,
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    }

    let tablaLaboratorio = $('#tblListadoLaboratorio').DataTable();

    $('#fechaDesde').datepicker({
      useCurrent: false,
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left"
    }).datepicker("setDate", new Date(new Date().setDate(new Date().getDate() - 7)));

    $('#fechaHasta').datepicker({
      useCurrent: false,
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left"
    }).datepicker("setDate", new Date(new Date().setDate(new Date().getDate() + 7)));

    onClickSearch();

    $('#dni_beneficiario').on('input', function () {
      let val = $(this).val();
      if (!/^-?\d*$/.test(val)) {
          $(this).val(val.replace(/[^0-9\-]/g, '').replace(/(?!^)-/g, ''));
      }
    });

    $("#btnBuscar").click(function (e) { 
      e.preventDefault();
      onClickSearch();
    });

    $(document).on("click", "#btnAnular", function (e) {
      e.preventDefault();
      const idLaboratorio = $(this).data("id_laboratorio");
      const idTipoAtencion = $(this).data("id_tipo_atencion");
      const idCitaLaboratorio = $(this).data("id_cita_laboratorio");

      $("#id_laboratorio_anular").val(idLaboratorio);
      $("#id_tipo_atencion_anular").val(idTipoAtencion);
      $("#id_cita_anular").val(idCitaLaboratorio);

      $('#modalAnular').modal('show');
    });

    $("#btnAnularOrdenLab").click(function (e) { 
      e.preventDefault();
      onClickAnular();
    });

    $("#btnReporteLaboratorio").click(function (e) { 
      e.preventDefault();
      generateReportExcel();
    });

    $("#tipo_atencion").on("change", function () {
      const valor = $(this).val();
      if (parseInt(valor) === 1) {
        $("#id_prioridad").val("");

        $("#divReferidos").show();
        $("#divPrioridad").hide();

        $("#divSpacing").show();
        $("#divSpacing2").hide();
      } else if (parseInt(valor) === 2) {
        $("#referido").val("");
        
        $("#divReferidos").hide();
        $("#divPrioridad").show();

        $("#divSpacing").hide();
        $("#divSpacing2").show();
      } else {
        $("#id_prioridad").val("");
        $("#referido").val("");

        $("#divReferidos").hide();
        $("#divPrioridad").hide();
        
        $("#divSpacing").show();
        $("#divSpacing2").show();
      }
    });

    $(document).on("click", "#btnReprogramar", function (e) {
      e.preventDefault();
    
      const idEstablecimiento = $(this).data("id_establecimiento");
      const nroDocIdentAsegurado = $(this).data("nro_doc_ident_asegurado");
      const idLaboratorio = $(this).data("id_laboratorio");
      const fechaCitaLaboratorio = $(this).data("fecha_cita_laboratorio"); 
      const idTipoAtencion = $(this).data("id_tipo_atencion"); 

      if (parseInt(idTipoAtencion) === 1) {
        $('#modalReprogramar').modal('show');
        const fechaCitaLaboratorioFormatted = moment(fechaCitaLaboratorio).format("DD-MM-YYYY HH:mm");
        $("#datetime_current_cita").text(fechaCitaLaboratorioFormatted);

        $('#calendar').fullCalendar('destroy');
        buildCalendar(idEstablecimiento, nroDocIdentAsegurado, idLaboratorio);
      } else {
        Swal.fire({
          title: 'Confirmación de Reprogramación',
          html: `<p>¿Está seguro de que desea reprogramar la cita del laboratorio?</p>`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: '<i class="fa fa-check"></i> Sí, reprogramar',
          cancelButtonText: '<i class="fa fa-times"></i> No, cancelar',
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#dc3545',
          reverseButtons: true, 
          focusCancel: true,
          allowOutsideClick: false,
          allowEscapeKey: false
        }).then((result) => {
          if (result.isConfirmed) {
            reprogramarOrdenLaboratorio(idLaboratorio);
          }
        });
      }
    });

    function onClickSearch() {
      const data = { 
        id_user: $("#id_user").val() || "",
        id_establecimiento: $("#id_establecimiento").val() || "",
        fechaDesde: $("#fechaDesde").val() || "",
        fechaHasta: $("#fechaHasta").val() || "",
        dni_beneficiario: $("#dni_beneficiario").val() || "",
        referido: $("#referido").val() || "",
        tipo_atencion: $("#tipo_atencion").val() || "",
        id_servicio: $("#id_servicio").val() || "",
        nro_examen: $("#nro_examen").val() || "",
        id_prioridad: $("#id_prioridad").val() || "",
        estado: $("#estado").val() || "",
        id_farmacia: $("#id_farmacia").val() || ""
      };
      if ($.fn.DataTable.isDataTable('#tblListadoLaboratorio')) {
        $('#tblListadoLaboratorio').DataTable().destroy();
        $('#tblListadoLaboratorio tbody').empty();
      }
      tablaLaboratorio = $('#tblListadoLaboratorio').DataTable({
          destroy: true,
          processing: true,
          serverSide: false,
          responsive: true,
          bAutoWidth: true,
          searching: false,
          paging: true,
          pageLength: 10,
          ordering: false,
          lengthChange: false,
          ajax: {
            url: "/api/listar_examenes_laboratorios_nivel2",
            type: 'GET',
            data: data,
            dataSrc: function (json) {
                $("#totalRecords").text(json.recordsTotal);
                return json.data;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la carga de datos: ", textStatus, errorThrown);
            }
          },
          createdRow: function(row, data, dataIndex) {
            //  $(row).css('background-color', '#fff3cd'); // Amarillo claro
          },
          columns: [
            { 
              data: 'id_laboratorio',  // N° -> [0]
              width: '5%' 
            },
            { 
              data: 'id_prestacion',  // Nro.Prestación -> [1]
              width: '5%' 
            },
            { 
              data: null, // Nro.Ex. Lab -> [2]
              width: '5%',
              render: function (data, type, row) {
                return `
                      <div>
                        <span>${row.cod_orden_lab || " - "}</span>
                      </div>
                `;
              }  
            },
            { 
              data: 'tipo_atencion', // Tipo Atención -> [3]
              width: '10%' 
            },
            { 
              data: 'consultorio', // Especialidad -> [4]
              width: '10%' 
            },
            {
              data: null, // Reg.Lab -> [5]
              width: '5%',
              render: function (data, type, row) {
                return `
                      <div>
                        <span>${moment(row.laboratorio_registro).format("DD-MM-YYYY")}</span>
                      </div>
                `;
              } 
            },
            {
              data: null, // Fec.Cita Lab -> [6]
              width: '5%',
              render: function (data, type, row) {
                return `
                      <div>
                        <span>${moment(row.fecha_cita_laboratorio).format("DD-MM-YYYY HH:mm")}</span>
                      </div>
                `;
              }
            },
            {
              data: null, // 	Fec.Cita Prox.Servicio -> [7]
              width: '5%',
              render: function (data, type, row) {
                return `
                      <div>
                        <span>${row.fecha_cita_proxima_laboratorio ? moment(row.fecha_cita_proxima_laboratorio).format("DD-MM-YYYY") : "-"}</span>
                      </div>
                `;
              }
            },
            {
              data: null, // Hr.Cita Prox.Servicio -> [8]
              width: '5%',
              render: function (data, type, row) {
                return `
                      <div>
                        <span>${row.fecha_cita_proxima_laboratorio ? moment(row.fecha_cita_proxima_laboratorio).format("HH:mm") : "-"}</span>
                      </div>
                `;
              }
            },
            { 
              data: null, // Beneficiario -> [9]
              width: '10%',
              render: function (data, type, row) {
                return `
                      <div>
                        <span><strong>DNI: </strong> ${row.nro_doc_ident_asegurado}</span><br>
                        <span><strong>Nombre: </strong> ${row.nombre_asegurado}</span><br>
                        <span><strong>Apellidos: </strong> ${row.apellido_paterno_asegurado} ${row.apellido_materno_asegurado}</span>
                      </div>
                `;
              } 
            },
            { 
              data: null, // Medico -> [10]
              width: '10%',
              render: function (data, type, row) {
                return `
                      <div>
                        <span><strong>DNI: </strong> ${row.nro_doc_ident_medico}</span><br>
                        <span><strong>Nombre: </strong> ${row.nombre_medico}</span><br>
                        <span><strong>Apellidos: </strong> ${row.paterno_medico} ${row.materno_medico}</span>
                      </div>
                `;
              } 
            },
            {
              data: null, // Referido -> [11]
              width: '5%',
              render: function (data, type, row) {
                return `
                      <div>
                        <span>${row.referido || " - "}</span>
                      </div>
                `;
              } 
            },
            {
              data: null, // Estado -> [12]
              width: '5%',
              render: function (data, type, row) {
                let label = {text: 'PENDIENTE', color: 'label-warning'};
                switch (parseInt(row.laboratorio_estado)) {
                  case 0: label = {text: 'PENDIENTE', color: 'label-warning'}; break;
                  case 1: label = {text: 'ATENDIDO', color: 'label-success'}; break;
                  case 2: label = {text: 'ANULADO', color: 'label-danger'}; break;
                  case 3: label = {text: 'REPROGRAMADO', color: 'label-warning'}; break;
                  default: label = {text: 'PENDIENTE', color: 'label-warning'}; break;
                }
                return `
                      <div> 
                        <span class="label ${label.color}">${label.text.toUpperCase()}</span>
                      </div>
                `;
              } 
            },
            { 
              data: null, // PDF Exa. -> [13]
              width: '5%',
              render: function(data, type, row) {
                return `<div>
                          <a
                            href="/laboratorio/rua/pdf/${row.id_laboratorio}"
                            class="btn btn-success btn-xs"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="PDF Examen"
                            target="_blank"
                          >
                            <i class="fa fa-file-text-o"></i>
                          </a>
                        </div>`;
              }
            },
            { 
              data: null, // Atender -> [14]
              width: '5%',
              render: function (data, type, row) {
                if (parseInt(row.laboratorio_estado) === 2) {
                  return ``;
                }
                const url = parseInt(row.laboratorio_estado) === 0 || parseInt(row.laboratorio_estado) === 3
                              ? `/formulario_atencion_laboratorio_nivel2/${row.id_laboratorio}/${row.id_tipo_atencion}`
                              : `/nivel_1_2/laboratorio/${row.id_laboratorio}/prestacion/${row.id_prestacion}`;
                const labelTootip = parseInt(row.laboratorio_estado) === 0 || parseInt(row.laboratorio_estado) === 3 ? `Atender` : `Ver Atención`;
                const icon = parseInt(row.laboratorio_estado) === 0 || parseInt(row.laboratorio_estado) === 3 ? `fa-edit` : `fa-eye`;
                  return `<div>
                            <a 
                              href="${url}"
                              class="btn btn-success btn-xs"
                              data-toggle="tooltip"
                              data-placement="bottom"
                              title="${labelTootip}"
                            >
                              <i class="fa ${icon}"></i>
                            </a>
                          </div>`;
              }, 
            },
            { 
              data: null, // Reprogramar -> [15]
              width: '5%',
              render: function (data, type, row) {
                if (parseInt(row.id_estado_cita) === 5 || parseInt(row.laboratorio_estado) === 2 || parseInt(row.laboratorio_estado) === 1) {
                  return ``;
                }
                return `<div>
                          <button 
                            class="btn btn-warning btn-xs"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="reprogramar cita"
                            id="btnReprogramar"
                            data-id_establecimiento="${row.id_establecimiento}"
                            data-nro_doc_ident_asegurado="${row.nro_doc_ident_asegurado}"
                            data-id_laboratorio="${row.id_laboratorio}"
                            data-fecha_cita_laboratorio="${row.fecha_cita_laboratorio}"
                            data-id_tipo_atencion="${row.id_tipo_atencion}"
                          >
                            <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                          </a>
                        </button>`;
              }, 
            },
            { 
              data: null, // Anular -> [16]
              width: '5%',
              render: function (data, type, row) {
                if (parseInt(row.id_estado_cita) === 5 || parseInt(row.laboratorio_estado) === 2 || parseInt(row.laboratorio_estado) === 1) {
                  return ``;
                }
                return `<div>
                          <button 
                            type="button"
                            class="btn btn-danger btn-xs"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="Anular"
                            id="btnAnular"
                            data-id_laboratorio="${row.id_laboratorio}"
                            data-id_cita_laboratorio="${row.id_cita_laboratorio}"
                            data-id_tipo_atencion="${row.id_tipo_atencion}"
                          >
                              <i class="fa fa-ban"></i>
                          </button>
                        </div>`;
              }, 
            },
          ]
      });

      $('#tblListadoLaboratorio').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
      });

      const id_tipo_atencion = $("#tipo_atencion").val();
      handlerColumnsTableByIdTipoAtencion(id_tipo_atencion);
    }

    function onClickAnular() {
      const motivoAnular = $('#obs_asistencia_del_paciente').val().trim();
      if(motivoAnular === ""){
          Swal.fire({
            icon: 'error',
            text: 'El campo está vacío. Por favor, ingrese un texto.',
            showConfirmButton: false,
            timer: 2000
          });
        return;
      }
      const body = {
        motivo: $('#motivoAnular').val(),
        idCita: $('#id_cita_anular').val(),
      };
      const id_laboratorio = $('#id_laboratorio_anular').val();
      $.ajax({
        url: `/api/nivel-1-2/laboratorio/${id_laboratorio}/anular`,
        type: 'PUT',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(body),
        beforeSend: function() {
          Swal.fire({
            html: '<h5><b>Anulando Registro</b></h5>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
        },
      })
      .done(function(response) {
        if(response.success){
          $('#modalAnular').modal('hide');
          clearFormAnular();
          onClickSearch();
          Swal.fire({
            icon: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 2000
          });
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        const errorMessage = jqXHR.responseJSON && jqXHR.responseJSON.message
          ? jqXHR.responseJSON.message
          : "Ocurrió un error al anular el registro. Por favor, inténtelo de nuevo más tarde.";
        Swal.fire({
          icon: 'error',
          title: "LABORATORIO",
          html: `<p>${errorMessage}</p>`,
          confirmButtonText: "ENTENDIDO",
        });
      })
      .always(function() {
        // console.log('Petición finalizada');
      });
    }

    function clearFormAnular() {
      $('#id_laboratorio_anular').val('');
      $('#id_tipo_atencion_anular').val('');
      $('#id_cita_anular').val('');
      $('#obs_asistencia_del_paciente').val('');
    }

    function generateReport() {
      Swal.fire({
        title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
        allowOutsideClick: false,
        showConfirmButton: false,
        customClass: {
            header : 'swal2-header-generar'
        },
        onBeforeOpen: () => {
          $('#blur-container').append('<div class="blur-background"></div>');
          Swal.showLoading();
          $.ajax({
            type: "GET",
            url: "/api/nivel-1-2/laboratorio/reporte",
            data: {
              "id_user" : $("#id_user").val() || 0,
              "id_establecimiento" : $("#id_establecimiento").val() || 0,
              "fechaDesde" : $("#fechaDesde").val() || 0,
              "fechaHasta" : $("#fechaHasta").val() || 0,
              "dni_beneficiario" : $("#dni_beneficiario").val() || 0,
              "referido" : $("#referido").val() || 0,
              "tipo_atencion" : $("#tipo_atencion").val() || 0,
              "id_servicio" : $("#id_servicio").val() || 0,
              "nro_examen" : $("#nro_examen").val() || 0,
              "id_prioridad" : $("#id_prioridad").val() || 0,
              "estado" : $("#estado").val() || 0
            },
            success: function (response) {
              if (response.excelNameResponse) {
                let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                let link = document.createElement('a');
                link.href = fileUrl;
                link.download = 'Exportar Reportes de Laboratorio.xlsx';
                link.innerText = 'Descargar archivo';
                let divLinkReporte = document.getElementById('link-reporte');
                divLinkReporte.appendChild(link);
                link.click();
                divLinkReporte.removeChild(link);
                Swal.hideLoading();
                Swal.fire({
                  icon: 'success',
                  title: 'El reporte se ha generado correctamente!',
                  showConfirmButton: false,
                  showConfirmButton: false,
                  timer: 1500,
                  onClose: () => {
                    $('.blur-background').remove();
                  }
                });
              }
            },
            error: function() {
              Swal.hideLoading();
              Swal.fire({
                icon: 'error',
                title: 'Error al generar el reporte',
                text: 'Por favor, inténtelo de nuevo más tarde',
                confirmButtonText: 'Cerrar',
                customClass: {
                  confirmButton : 'custom-swal-button-cerrar'
                },
                onClose: () => {
                  $('.blur-background').remove();
                }
              });
            }
          });
        }
      });
    }

    function generateReportExcel() {
      const params = $.param({
        id_user: $("#id_user").val() || "",
        id_establecimiento: $("#id_establecimiento").val() || "",
        fechaDesde: $("#fechaDesde").val() || "",
        fechaHasta: $("#fechaHasta").val() || "",
        dni_beneficiario: $("#dni_beneficiario").val() || "",
        referido: $("#referido").val() || "",
        tipo_atencion: $("#tipo_atencion").val() || "",
        id_servicio: $("#id_servicio").val() || "",
        nro_examen: $("#nro_examen").val() || "",
        id_prioridad: $("#id_prioridad").val() || "",
        estado: $("#estado").val() || "",
        id_farmacia: $("#id_farmacia").val() || ""
      });
      window.open('/api/nivel-1-2/laboratorio/reporte?' + params, '_blank');
    }

    function hideColumnTable(index) {
      if (tablaLaboratorio) {
        tablaLaboratorio.column(index).visible(false);
      }
    }

    function showColumnTable(index) {
      if (tablaLaboratorio) {
        tablaLaboratorio.column(index).visible(true);
      }
    }

    function handlerColumnsTableByIdTipoAtencion(idTipoAtencion) {
      if (parseInt(idTipoAtencion) === 1) {
        showColumnTable(6);
        showColumnTable(7);
        showColumnTable(8);
        showColumnTable(11);
      }
      if (parseInt(idTipoAtencion) === 2) {
        // hideColumnTable(6);
        hideColumnTable(7);
        hideColumnTable(8);
        hideColumnTable(11);
      }
      if (parseInt(idTipoAtencion) === 3) {
        // showColumnTable(6);
        showColumnTable(7);
        showColumnTable(8);
        hideColumnTable(11);
      }
    }

    async function buildCalendar(id_ipress, dni_beneficiario, id_laboratorio) {
      var fecha = new Date(2022, 4, 10);
      var feriados = await getFeriados(moment().format("YYYY"));

      $("#divHorario").html("");
      $('#calendar').fullCalendar({
        header: {
          left: 'today',
          center: 'title',
          right: 'prev,next'
        },
        editable: false,
        annotations: [{
          start: new Date(2000, 1, 1),
          end: new Date(2022, 4, 13),
          title: 'Blocked Day',
          cls: 'open',
          color: '#777777',
          background: '#000'
        }],
        selectable: true,
        selectHelper: true,
        select: function(start, end, allDay) {
          if(start.format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')) {
            bootbox.alert("No se puede registrar citas con fechas pasadas");
            return;
          } else {
            dateObj = new Date(start);
            dateIntNTZ = dateObj.getTime() + dateObj.getTimezoneOffset() * 60 * 1000;
            dateObjNTZ = new Date(dateIntNTZ);
            start = dateObjNTZ.toISOString().slice(0, 10);
            start = $.trim(start);
            var sValidaciones = "";
            modalDelegar(start, id_ipress, dni_beneficiario, id_laboratorio);
          }
        },
        dayRender: function(date, cell) {
          var enabled = true;
          if(date < moment()) {
            cell.addClass("app-fullcalendar-day-disabled");
            enabled = false
          }
          feriados.forEach(element => {
            if (date.isSame(element.feriado)) {
              cell.addClass("app-fullcalendar-feriado");
              enabled = false
            }
          });
          if (enabled)
            cell.addClass("app-fullcalendar-day-enabled");
        }
      });
    }
    
    async function getFeriados(anio) {
      let feriados;
      try {
        feriados = await $.ajax({
          url: "listar_feriados/" + anio,
          type: "GET"
        });
        return JSON.parse(feriados);
      } catch (error) {
        console.log(error);
      }
    }

    async function modalDelegar(start, id_ipress, dni_beneficiario, id_laboratorio) {
      let tieneMensaje = false;
      const validacion = JSON.parse(await validateProgramacionCitas(start));
      if (validacion['validacion'] == '1') {
        $('#es_asegurado').val(1);
      } else if (validacion['validacion'] == '90') {
        $('#es_asegurado').val(0);
      } else {
        tieneMensaje = true;
        $("#divHorario").html("");
        $("#panelHorario").slideUp();
        bootbox.alert(validacion['validacion']);
      }

      if (!tieneMensaje) {
        $.ajax({
          url: `modal_cita_horario/reprogramar-laboratorio/nivel_1_2/${id_ipress}/113/${start}/${dni_beneficiario}/${id_laboratorio}`,
          type: "GET",
          beforeSend: function() {
            $("#panelHorario").slideDown();
            Swal.fire({
              html: '<p style="margin: 20px 0px 0px 0px"><b>BUSCANDO HORARIOS DISPONIBLES</b></p>',
              allowOutsideClick: false,
              didOpen: () => Swal.showLoading()
            });
          },
          success: function(result) { 
            $("#divHorario").html(result);
          },
          complete: function() {
            Swal.close();
          },
        });
      }
    }

    async function validateProgramacionCitas(fechaCita) {
      try {
        let validacion = await $.ajax({
          url: "valida_programacion_cita",
          data: {
            _token: $("#_token").val(),
            nro_documento: $("#nrodocafiliado").val(),
            id_establecimiento: $("#id_establecimiento").val(),
            id_sub_consultorio: $("#id_servicio").val(),
            fecha: fechaCita,
          },
          type: "POST"
        });
        return validacion;
      } catch (error) {
      }
    }

    function reprogramarOrdenLaboratorio(idLaboratorio) {
      const body = {
        idSubConsultorio: "",
        fechaHora: "",
      };
      $.ajax({
        url: `/api/nivel-1-2/laboratorio/${idLaboratorio}/reprogramar-cita`,
        type: "PUT",
        contentType: "application/json",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "json",
        data: JSON.stringify(body),
        beforeSend: function() {
          Swal.fire({
            title: 'Reprogramando cita...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
          });
        }
      })
      .done(function(response) {
        $('#modalReprogramar').modal('hide');
        const mensaje = response.message || "Cita reprogramada exitosamente.";
        Swal.fire({
          icon: 'success',
          title: 'Reprogramación Laboratorio',
          text: mensaje,
        });
        location.reload();
      })
      .fail(function(xhr, status, error) {
        const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Error al reprogramar la cita.";
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: errorMessage,
        });
      })
      .always(function() {
      });
    }
  });

</script>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">Buscar Examenes de Laboratorio - {{ $farmacia->nombre }}</h3>
            </div>
            <div class="panel-body" style="padding: 15px;">
                <div class="container-fluid">
                  <!-- Hidden fields -->
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="id_user" id="id_user" value="{{ $id_user }}">
                  <input type="hidden" id="id_establecimiento" name="id_establecimiento" value="{{ $id_establecimiento }}">
                  <input type="hidden" id="tipo_perfil" name="tipo_perfil" value="{{ $tipo_perfil }}">

                  <!-- First row -->
                  <div class="row" style="margin-bottom: 15px;">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                        <div class="form-group">
                            <label class="control-label fecha-inicio">Fecha cita Desde</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="dd/mm/aaaa" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                        <div class="form-group">
                            <label class="control-label fecha-fin">Fecha cita Hasta</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="dd/mm/aaaa" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                        <div class="form-group">
                            <label class="control-label">Nro. Documento</label>
                            <input id="dni_beneficiario" name="dni_beneficiario" type="text" class="form-control input-sm" maxlength="12" placeholder="00000000">
                        </div>
                    </div>

                    <div id="divReferidos" class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                        <div class="form-group">
                            <label class="control-label">Referido</label>
                            <select class="form-control input-sm" name="referido" id="referido">
                                <option value="">-Seleccionar-</option>
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                        <div class="form-group">
                            <label class="control-label">Tipo Atención</label>
                            <select class="form-control input-sm" name="tipo_atencion" id="tipo_atencion">
                                <?php foreach ($tipo_atenciones as $row) : ?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->nombre; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                        <div class="form-group">
                            <label class="control-label">Especialidad</label>
                            <select class="form-control input-sm" name="id_servicio" id="id_servicio">
                                <option value="" selected>-Seleccionar-</option>
                                @if ($consultorios && count($consultorios) > 0)
                                  @foreach ($consultorios as $consultorio)
                                      <option value="{{ $consultorio->id }}">{{ $consultorio->nombre }}</option>
                                  @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                      <div class="form-group">
                        <label class="control-label">Nro. Ex Laboratorio</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-flask"></i></span>
                          <input id="nro_examen" name="nro_examen" type="text" class="form-control input-sm" onKeyPress="return soloNumeros(event)" maxlength="12">
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                        <div class="form-group">
                            <label class="control-label">Estado</label>
                            <select class="form-control input-sm" name="estado" id="estado">
                              <option value="0" selected>Pendiente</option>
                              <option value="1">Atendido</option>
                              <option value="2">Anulados</option>
                              {{-- <option value="2">No Atendido</option> --}}
                              <option value="">Todos</option>
                            </select>
                        </div>
                    </div>

                    <div id="divPrioridad" class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2" style="display: none">
                        <div class="form-group">
                            <label class="control-label">Prioridad</label>
                            <select class="form-control input-sm" name="id_prioridad" id="id_prioridad">
                              <option value="" selected>-Seleccionar-</option>
                              @if ($prioridades && count($prioridades) > 0)
                                @foreach ($prioridades as $prioridad)
                                    <option value="{{ $prioridad->id }}">{{ $prioridad->prioridad }}</option>
                                @endforeach
                              @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2"></div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2" id="divSpacing"></div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2" style="display: none" id="divSpacing2"></div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2" style="padding-top: 23px">
                        <button id="btnBuscar" type="button" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Buscar</button>
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2" style="padding-top: 23px">
                        <button id="btnReporteLaboratorio" type="button" class="btn btn-success btn-block"><i class="fa fa-file-excel-o"></i> Exportar</button>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="totalRecordsContainer" style="margin-top: 15px" >
                      <legend>
                        <div class="pull-right">Total: <span id="totalRecords">0</span> examen(es) encontrado(s)</div>
                      </legend>
                    </div>  

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <div id="global">
                        <div class="table-responsive">
                          <table id="tblListadoLaboratorio" class="table table-striped datatable" style="width: 100%; font-size: 11px;">
                            <thead>
                              <tr>
                                  <th width="5%">N°</th>
                                  <th width="5%">Nro.Prestación</th>
                                  <th width="5%">Nro.Ex. Lab</th>
                                  <th width="5%">Tipo Atención</th>
                                  <th width="5%">Especialidad</th>
                                  <th width="5%">Reg.Lab</th>
                                  <th width="5%">Fec.Cita Lab</th>
                                  <th width="5%">Fec.Cita Prox.Servicio</th>
                                  <th width="5%">Hr.Cita Prox.Servicio</th>
                                  <th width="10%">Beneficiario</th>
                                  <th width="10%">Medico</th>
                                  <th width="5%">Referido</th>
                                  <th width="5%">Estado</th>
                                  <th width="5%">PDF Exa.</th>
                                  <th width="5%">Atender</th>
                                  <th width="5%">Reprogramar</th>
                                  <th width="5%">Anular</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
                <input type="hidden" id="argumentos_laboratorio" value="" />
                <input type="hidden" id="doc_firmar_laboratorio" value="" />
                <input type="hidden" id="opt_firma_laboratorio" value="" />
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModalDetRV" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">DETALLE DE ANALISIS DE LABORATORIO. <span id="nropresmodal"></span></h5>
                </div>
                <div class="modal-body">
                    <div id="muestra-det-laboratorio"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="link-reporte"></div>

    <div class="modal fade" id="modalAnular" role="dialog" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header custom-header-cambiar-estado">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title" style="font-size: 2rem">¿Por qué desea anular la cita?<span id="nropresmodal"></span></h5>
          </div>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="id_tipo_atencion_anular" id="id_tipo_atencion_anular">
          <input type="hidden" name="id_cita_anular" id="id_cita_anular">
          <input type="hidden" name="id_laboratorio_anular" id="id_laboratorio_anular">
          <div class="modal-body">
            <div class="form-group" style="padding: 0px 15px">
              <label for="obs_asistencia_del_paciente">Motivo</label>
              <textarea class="form-control" class="form-control" name="obs_asistencia_del_paciente" id="obs_asistencia_del_paciente" rows="3" maxlength="250" placeholder="..."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-md-6">
                <button type="button" class="btn btn-danger btn-block " data-dismiss="modal">CERRAR</button>
              </div>
              <div class="col-md-6">
                <button type="button" class="btn btn-success btn-block" id="btnAnularOrdenLab">CONFIRMAR</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalReprogramar" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalReprogramarLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered" style="width: 90%;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalReprogramarLabel">Reprogramar Cita</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="panel panel-default" style="margin-top: 15px;">
                <div class="panel-heading">
                  <strong>Información de la Cita Actual</strong>
                </div>
                <div class="panel-body" style="font-size: 14px;">
                  <p style="margin: 0;">
                    <strong>Fecha y Hora Actual:</strong>
                    <span id="datetime_current_cita" style="color: #007bff; font-weight: bold;"></span>
                  </p>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-sm-col-sm-12 col-md-12 col-lg-6 col-xl-6">
                  <div id='calendar'></div>
                </div>
                <div class="col-12 col-sm-col-sm-12 col-md-12 col-lg-6 col-xl-6">
                  <div class="panel panel-default" id="panelHorario">
                    <div class="panel-body">
                      <div id='divHorario'></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-12 col-sm-col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">CERRAR</button>
              </div>
              {{-- <div class="col-12 col-sm-col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <button type="button" class="btn btn-primary btn-block">CREAR</button>
              </div> --}}
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('frontend.includes.footer')
@endsection
