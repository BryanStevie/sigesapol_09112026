$(document).ready(function () {
  $('#fecha_ini').val(moment().subtract(24, 'months').format('DD-MM-YYYY'));
  $('#fecha_fin').val(moment().format('DD-MM-YYYY'));

  onSearchIntervencionNivel2();

  $("#btnSearch").on("click", function () {
    onSearchIntervencionNivel2();
  });

  $("#btnExport").on("click", function () {
    onExportReport();
  });

  $(document).on('click', '.btnSuspender', function () {
    let cqx_intervencio_id = $(this).data('cqx_intervencio_id');
    if (cqx_intervencio_id) {
      $('#cqx_intervencio_id').val(cqx_intervencio_id);
      $('#modalSuspender').modal({ backdrop: 'static', keyboard: false }).modal('show');
    }
  });

  $(document).on('click', '.btnAlta', function () {
    let cqx_intervencio_id = $(this).data('cqx_intervencio_id');
    if (cqx_intervencio_id) {
      $('#modalCqAltaNivel2').modal({ backdrop: 'static', keyboard: false }).modal('show');
      $("#fa_id_intervencion").val(cqx_intervencio_id);
    }
  });

  $(document).on('click', '.btnShowAlta', function () {
    let cqx_intervencio_id = $(this).data('cqx_intervencio_id');
    if (cqx_intervencio_id) {
      $('#modalCqDetalleAltaNivel2').modal({ backdrop: 'static', keyboard: false }).modal('show');
      $("#fr_det_alta_cqx_intervencion_id").val(cqx_intervencio_id);
    }
  });

  function onExportReport() {
    const params = $.param({
      fecha_solicitud_inicio: $('#fecha_ini').val(),
      fecha_solicitud_fin: $('#fecha_fin').val(),
      nro_doc_ident: $('#dni_beneficiario').val(),
      id_tipo_atencion: $('#id_tipo_atencion').val(),
      id_servicio: $('#id_servicio').val(),
      id_estado: $('#estado').val(),
      nombres_apellido: $('#nombre_beneficiario').val(),
      cantidad_pagina : 100000,
      pagina : 1,
    });

    window.open('/api/cqx-intervenciones/report/nivel_2?' + params, '_blank');
  }

});

function onSearchIntervencionNivel2() {
  if ($.fn.DataTable.isDataTable('#tblIntervencionQx')) {
    $('#tblIntervencionQx').DataTable().clear().destroy();
  }
  const estadosCqx = {
    1: { label: "PENDIENTE", class: "label label-warning", icon: "fa-clock-o" },
    3: { label: "ATENDIDO", class: "label label-success", icon: "fa-check-circle" },
    4: { label: "ALTA", class: "label label-primary", icon: "fa-sign-out" },
    5: { label: "SUSPENDIDO", class: "label label-danger", icon: "fa-ban" },
  };
  $('#tblIntervencionQx').DataTable({
    searching: false,
    lengthChange: false,
    ordering: false,
    info: false,
    processing: true,
    serverSide: true,
    ajax: {
      url: '/api/cqx_intervenciones',
      type: 'GET',
      data: function (d) {
        let page = Math.ceil(d.start / d.length) + 1;
        d.fecha_solicitud_inicio = $('#fecha_ini').val();
        d.fecha_solicitud_fin = $('#fecha_fin').val();
        d.nro_doc_ident = $('#dni_beneficiario').val();
        d.id_tipo_atencion = $('#id_tipo_atencion').val();
        d.id_servicio = $('#id_servicio').val();
        d.id_estado = $('#estado').val();
        d.nombres_apellido = $('#nombre_beneficiario').val();
        d.pagina = page;
        d.cantidad_pagina = 10;
      },
      dataSrc: function (json) {
        return json.data;
      }
    },
    columns: [
      { data: 'cqx_intervencio_id' }, // Cod.
      { data: 'tipo_atencion' }, // Tipo Atención	
      { data: 'fecha_admision' }, // Fecha Admisión
      { 
        data: null, // Fecha Intervención
        render: function (data, type, row) {
          if (!row.fecha_intervencion) {
            return ``;
          }
          return moment(row.fecha_intervencion, "YYYY-MM-DD").format("DD-MM-YYYY")
        }
      }, 
      { data: 'asegurado_nro_doc_ident' }, // N° Doc
      {
        data: null, // Paciente
        render: function (data, type, row) {
          return `<div style="text-align: left">
                    <span><b>Nombre:</b> ${row.asegurado_nombre} ${row.asegurado_paterno} ${row.asegurado_materno}</span><br>
                    <span><b>Edad:</b> ${row.edad}</span><br>
                    <span><b>Sexo:</b> ${row.asegurado_sexo}</span><br>
                  </div>`;
        }
      },
      { data: 'servicio' }, // Servicio
      {
        data: null, // Estado
        render: function (data, type, row) {
          const flagIntervencion = parseInt(row.flag_intervencion) || 1;
          const e = estadosCqx[flagIntervencion];
          return `
            <span class="${e.class}" style="color: #ffffff; font-size: 10px">
              <i class="fa ${e.icon}"></i> ${e.label}
            </span>
          `;
        }
      },
      {
        data: null, // Atención ADM
        render: function (data, type, row) {
          if (!row.id_hospitalizacion) {
            return ``;
          }
          return `
                  <div>
                    <a 
                      type="button" 
                      class="btn btn-xs btn-primary"
                      data-toggle="tooltip"
                      data-placement="bottom"
                      title="VER DETALLE ADMISIÓN"
                      href="/ver_prestaciones_hospitalizacion/${row.id_hospitalizacion}"
                    ><i class="fa fa-eye"></i></a>
                    <a
                      href="/api/cqx-intervenciones/${row.cqx_intervencio_id}/hospitalizacion-admision/pdf"
                      class="btn btn-xs btn-success"
                      data-toggle="tooltip"
                      data-placement="bottom"
                      title="PDF ADMISIÓN"
                      target="_blank"
                    ><i class="fa fa-file-pdf-o"></i></a>
                  </div>`;
        }
      },
      {
        data: null, // Atención CQ
        render: function (data, type, row) {
          if (!row.cqx_atencion_id) {
            return `<a
                      type="button"
                      class="btn btn-xs btn-primary"
                      data-toggle="tooltip"
                      data-placement="bottom"
                      title="ATENDER ORDEN QUIRÚRGICA"
                      href="/intervenciones/${row.cqx_intervencio_id}/atender"
                    ><i class="fa fa-user-md" aria-hidden="true"></i></a>`;
          }
          let attributeBtnFirma = {
            "titleTooltip": "PDF ATENCIÓN",
            "icon": "fa fa-file-pdf-o",
            "href": `/api/cqx_intervenciones/${row.cqx_intervencio_id}/pdf`,
          };
          if (row.cqx_intervencion_flag_firma_atencion_qx) {
            attributeBtnFirma.titleTooltip = "PDF FIRMADO ATENCIÓN";
            attributeBtnFirma.icon = "fa fa-download";
            attributeBtnFirma.href = `/${moment().format("YYYY/MM/DD")}/${row.cqx_intervencion_ruta_firma_atencion_qx}`;
          }
          return `
                  <div>
                    <a 
                      type="button" 
                      class="btn btn-xs btn-primary"
                      data-toggle="tooltip"
                      data-placement="bottom"
                      title="VER DETALLE ATENCIÓN"
                      href="/intervenciones/${row.cqx_intervencio_id}/ver-detalle"
                    ><i class="fa fa-eye"></i></a>
                    <a
                      href="${attributeBtnFirma.href}"
                      class="btn btn-xs btn-success"
                      data-toggle="tooltip"
                      data-placement="bottom"
                      title="${attributeBtnFirma.titleTooltip}"
                      target="_blank"
                    ><i class="${attributeBtnFirma.icon}"></i></a>
                  </div>`;
        }
      },
      {
        data: null, // Suspender
        render: function (data, type, row) {
          const flagIntervencion = parseInt(row.flag_intervencion) || 1;
          if (flagIntervencion == 3 || flagIntervencion == 4) {
            return ``
          }
          if (row.is_suspended) {
            return `<span class="label label-danger" style="color: #ffffff">SUSPENDIDO</span>`
          }
          return `<button
                    type="button"
                    class="btn btn-xs btn-danger btnSuspender"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    data-cqx_intervencio_id="${row.cqx_intervencio_id}"
                    title="SUSPENDER ORDEN QUIRÚRGICA"
                  ><i class="fa fa-ban"></i></button>`;
        }
      },
      {
        data: null, // CPMS ENF
        render: function (data, type, row) {
          if (!row.id_hospitalizacion) {
            return ``;
          }
          if (row.is_suspended) {
            return `<span class="label label-danger" style="color: #ffffff">SUSPENDIDO</span>`;
          }
          return `<a
                    type="button"
                    class="btn btn-xs btn-success"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="CPMS ENFERMERÍA"
                    href="/ver_prestaciones_consejeria/${row.id_hospitalizacion}"
                  ><i class="fa fa-pencil-square-o"></i></a>`;
        }
      },
      {
        data: null, // ALTA
        render: function (data, type, row) {
          const flagIntervencion = parseInt(row.flag_intervencion) || 1;
          if (flagIntervencion == 1) {
            return ``
          }
          if (row.is_suspended) {
            return `<span class="label label-danger" style="color: #ffffff">SUSPENDIDO</span>`
          }
          let attributeBtnEpicrisis = {
            "titleTooltip": "PDF EPICRISIS",
            "icon": "fa fa-file-pdf-o",
            "href": `/api/cqx_intervenciones/${row.cqx_intervencio_id}/pdf_alta_crisis/nivel_2`,
          };
          let attributeBtnPapeleta = {
            "titleTooltip": "PDF PAPELETA EGRESO",
            "icon": "fa fa-file-pdf-o",
            "href": `/api/cqx_intervenciones/${row.cqx_intervencio_id}/pdf_papelete_egreso/nivel_2`,
          };
          if (row.hospitalizacion_ruta_epicrisis) {
            attributeBtnEpicrisis.titleTooltip = "PDF FIRMADO EPICRISIS";
            attributeBtnEpicrisis.icon = "fa fa-download";
            attributeBtnEpicrisis.href = `/${moment().format("YYYY/MM/DD")}/${row.hospitalizacion_ruta_epicrisis}`;
          }
          if (row.hospitalizacion_ruta_papeleta_egreso) {
            attributeBtnPapeleta.titleTooltip = "PDF FIRMADO PAPELETA EGRESO";
            attributeBtnPapeleta.icon = "fa fa-download";
            attributeBtnPapeleta.href = `/${moment().format("YYYY/MM/DD")}/${row.hospitalizacion_ruta_papeleta_egreso}`;
          }
          if (flagIntervencion === 4) {
            if (!row.hospitalizacion_fecha_firma_alta_medica) {
              return `<button
                        type="button"
                        class="btn btn-xs btn-warning btnShowAlta"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="FIRMAR ALTA"
                        data-cqx_intervencio_id="${row.cqx_intervencio_id}"
                        data-id_establecimiento="${row.id_establecimiento}"
                      ><i class="fa fa-pencil" aria-hidden="true"></i></button>`;
            }
            return `<div>
                    <a 
                      type="button" 
                      class="btn btn-xs btn-primary"
                      data-toggle="tooltip"
                      data-placement="bottom"
                      title="${attributeBtnEpicrisis.titleTooltip}"
                      target="_blank"
                      href="${attributeBtnEpicrisis.href}"
                    ><i class="${attributeBtnEpicrisis.icon}" aria-hidden="true"></i></a>
                    <a
                      class="btn btn-xs btn-success"
                      data-toggle="tooltip"
                      data-placement="bottom"
                      title="${attributeBtnPapeleta.titleTooltip}"
                      target="_blank"
                      href="${attributeBtnPapeleta.href}"
                    ><i class="${attributeBtnPapeleta.icon}" aria-hidden="true"></i></a>
                  </div>`
          }
          return `<button
                    type="button"
                    class="btn btn-xs btn-success btnAlta"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="Dar Alta"
                    data-cqx_intervencio_id="${row.cqx_intervencio_id}"
                  ><i class="fa fa-pencil-square-o"></i></button>
                `;
        }
      },
    ],
    drawCallback: function () {
      $('[data-toggle="tooltip"]').tooltip({
        container: 'body',
      });
    }
  });
}