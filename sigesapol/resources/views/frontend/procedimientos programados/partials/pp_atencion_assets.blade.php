<style>
  .pp-encabezado { background: #0b6b0b; color: #fff; font-weight: bold; padding: 8px 12px; font-size: 13px; }
  .pp-seccion { border: 1px solid #c9dfc9; margin-bottom: 12px; }
  .pp-seccion-titulo { background: #dceedc; font-weight: bold; padding: 6px 10px; font-size: 12px; }
  .pp-seccion-cuerpo { padding: 10px; font-size: 12px; }
  .pp-dato { display: flex; margin-bottom: 3px; }
  .pp-dato .pp-etq { width: 170px; font-weight: bold; }
  .pp-dato .pp-sep { width: 12px; }
  .pp-siteds { color: rgb(255, 0, 0);}
  .pp-resp th { font-size: 11px; }
  .pp-resp td { font-size: 12px; }
  .pp-proc th, .pp-proc td { font-size: 12px; vertical-align: middle !important; }
  .pp-texto-rojo { color: rgb(255, 0, 0); }
  .pp-campo-error,
  .pp-campo-error:focus { border-color: #d9534f !important; box-shadow: 0 0 0 2px rgba(217, 83, 79, 0.25) !important; }
  .pp-historial { min-height: 200px; }
  .pp-contenedor { max-width: calc(1350px + 4cm); margin: 0 auto; }
  .pp-fila-igual { display: flex; flex-wrap: wrap; }
  .pp-fila-igual > [class*="col-"] { display: flex; }
  .pp-fila-igual .pp-seccion { flex: 1; display: flex; flex-direction: column; }
  .pp-fila-igual .pp-seccion-cuerpo { flex: 1; }

  .swal2-icon.swal2-warning {
    font-size: 15px;
  }
  .swal2-popup,
  .swal2-header {
    padding: 1.25em !important;
  }

  .swal-confidencialidad-pp-hc-backdrop.swal2-backdrop-show,
  .swal-confidencialidad-pp-hi-backdrop.swal2-backdrop-show,
  .swal-confidencialidad-pp-modal-backdrop.swal2-backdrop-show,
  .swal-confidencialidad-pp-hpp-backdrop.swal2-backdrop-show {
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    background: rgba(0, 0, 0, 0.4) !important;
  }

  .marca_agua_container {
    position: relative;
  }
  .marca_agua_overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none;
    z-index: 999;
    overflow: hidden;
  }
  .marca_agua_overlay span {
    position: absolute;
    color: rgba(5, 98, 24, 0.12);
    font-size: 14px;
    font-weight: bold;
    white-space: nowrap;
    transform: rotate(-35deg);
    user-select: none;
    letter-spacing: 1px;
  }
  @media print {
    .marca_agua_overlay span {
      display: none !important;
    }
    .marca_agua_overlay {
      z-index: 0 !important;
      bottom: 0 !important;
      height: auto !important;
      background-image: var(--marca-agua-print) !important;
      background-repeat: no-repeat !important;
      background-size: 100% 100% !important;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }
    .marca_agua_container > :not(.marca_agua_overlay) {
      position: relative;
      z-index: 1;
    }
    .marca_agua_container .panel,
    .marca_agua_container .panel-heading,
    .marca_agua_container .panel-body,
    .marca_agua_container .modal-content,
    .marca_agua_container .modal-body,
    .marca_agua_container .well,
    .marca_agua_container .form-control,
    .marca_agua_container table,
    .marca_agua_container tr,
    .marca_agua_container td,
    .marca_agua_container th {
      background: transparent !important;
    }
    .container:has(.marca_agua_container),
    .pp-contenedor:has(.marca_agua_container) {
      width: 1200px !important;
      max-width: none !important;
      zoom: 0.6;
    }
    .marca_agua_container a[href]:after {
      content: none !important;
    }
    .marca_agua_container .table-responsive {
      overflow: visible !important;
    }
  }
  .swal-afiliacion {
    width: 350px !important;
    height: 300px !important;
    padding: 1rem !important;
    display: flex !important;
    flex-direction: column;
    justify-content: center;
    overflow: hidden;
  }

  .swal-afiliacion .swal2-header {
    padding: 0 !important;
  }

  .swal-afiliacion .swal2-icon {
    font-size: 20px !important;
    margin: 0 auto 6px auto !important;
  }

  .swal-afiliacion .swal2-title {
    font-size: 20px !important;
    margin: 0 0 7px 0 !important;
    padding: 7px !important;
  }

  .swal-afiliacion .swal2-content,
  .swal-afiliacion .swal2-html-container {
    font-size: 17px !important;
    line-height: 1.3 !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  .swal-afiliacion .swal2-actions {
    margin: 10px auto 0 auto !important;
  }

  .swal-afiliacion .swal2-confirm {
    padding: 5px 25px !important;
    font-size: 15px !important;
    border-radius: 5px !important;
    background-color: #1b5e20 !important; 
    color: #fff !important;
  }

  .swal-afiliacion .swal2-confirm:hover {
    background-color: #144a18 !important;
  }

  .swal-afiliacion .swal2-confirm:focus {
    box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.35) !important;
  }
</style>
<script>
  (function () {
    if (!window.matchMedia || window.marcaAguaImpresionRegistrada) return;
    window.marcaAguaImpresionRegistrada = true;
    var mq = window.matchMedia('print');
    var ctx = document.createElement('canvas').getContext('2d');
    ctx.font = '14px Arial, sans-serif';
    function svg_marca_agua(W, H, texto) {
      var txt = String(texto).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
      var anchoTexto = ctx.measureText(texto).width + texto.length;
      var textos = '';
      for (var r = 0; r < Math.ceil(H / 100) + 2; r++) {
        for (var c = 0; c < Math.ceil(W / 220) + 2; c++) {
          var x = c * 220 - 40 + anchoTexto / 2;
          var y = r * 100 - 20 + 9;
          textos += '<text x="' + x + '" y="' + y + '" text-anchor="middle" dominant-baseline="middle" transform="rotate(-35 ' + x + ' ' + y + ')">' + txt + '</text>';
        }
      }
      var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '">' +
        '<g font-family="Arial, sans-serif" font-size="14" letter-spacing="1" fill="#e6e6e6">' + textos + '</g></svg>';
      return 'url("data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg) + '")';
    }
    function al_cambiar_impresion(e) {
      if (!e.matches) return;
      var overlays = document.querySelectorAll('.marca_agua_overlay');
      for (var i = 0; i < overlays.length; i++) {
        var span = overlays[i].querySelector('span');
        if (!span || !overlays[i].offsetWidth) continue;
        overlays[i].style.setProperty('--marca-agua-print', svg_marca_agua(overlays[i].offsetWidth, overlays[i].offsetHeight, span.textContent));
      }
    }
    if (mq.addEventListener) mq.addEventListener('change', al_cambiar_impresion);
    else if (mq.addListener) mq.addListener(al_cambiar_impresion);
  })();
</script>

@if(!$modal)
@include('frontend.procedimientos programados.partials.pp_atencion_ordenes_js')
@endif

@if(!$modal && $confirmacion && (!empty($pp_requiere_token) || !empty($pp_puede_firmar_token)))
@include('frontend.procedimientos programados.partials.pp_firma_token_js')
@endif<script>
  var PP_DNI = "{{ $solicitud->nro_doc_ident }}";
  var PP_ID_ASEGURADO = {{ intval($solicitud->id_asegurado) }};
  var PP_TOKEN = "{{ csrf_token() }}";
  var PP_HISTORIAL_CARGADO = {};

  var texto_marca_agua_historial = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';
  function generar_marca_agua_historial(overlayId) {
    var overlay = document.getElementById(overlayId);
    if (!overlay) return;
    overlay.innerHTML = '';
    var W = overlay.offsetWidth  || 800;
    var H = overlay.offsetHeight || 600;
    var cols = Math.ceil(W / 220) + 1;
    var rows = Math.ceil(H / 100) + 1;
    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var span = document.createElement('span');
        span.textContent = texto_marca_agua_historial;
        span.style.left = (c * 220 - 40) + 'px';
        span.style.top  = (r * 100 - 20) + 'px';
        overlay.appendChild(span);
      }
    }
  }
  function marca_agua_pestana(tabId) {
    generar_marca_agua_historial('marca_agua_overlay_' + tabId.replace('#', ''));
  }

  function generar_marca_agua_atencion_origen() {
    var overlay = document.getElementById('marca_agua_overlay_atencion_origen');
    if (!overlay || !$('#modalAtencionOrigen').is(':visible')) return;
    overlay.innerHTML = '';
    var container = overlay.parentElement;
    var W = overlay.offsetWidth || 800;
    var H = (container ? container.scrollHeight : 0) || overlay.offsetHeight || 600;
    overlay.style.height = H + 'px';
    var cols = Math.ceil(W / 220) + 1;
    var rows = Math.ceil(H / 100) + 1;
    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var span = document.createElement('span');
        span.textContent = texto_marca_agua_historial;
        span.style.left = (c * 220 - 40) + 'px';
        span.style.top  = (r * 100 - 20) + 'px';
        overlay.appendChild(span);
      }
    }
  }

  var PP_ID_TIPO_ATENCION = {{ $solicitud->id_tipo_atencion ? intval($solicitud->id_tipo_atencion) : 'null' }};
  function registrarAuditoriaPP(origen, idTipoAtencion) {
    $.ajax({
      url: '/auditoria-hc/registrar',
      type: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        origen: origen,
        doc_buscado: PP_DNI,
        id_tipo_atencion: idTipoAtencion
      }
    });
  }

  function alertaConfidencialidadPP(tabId) {
    if (tabId === "#tabHistAtenciones") {
      Swal.fire({
        icon: "warning",
        html: `<div style="padding: 0px 20px 20px 20px">
                <p style="font-size: 16px"><b>La historia clínica contiene datos sensibles y que su protección es esencial para el bienestar del paciente, así como para el cumplimiento de la normativa legal de privacidad y protección de datos</b></p><br>
                <span style="font-size: 14px; color: #d9534f"><b>Ley N.° 26659</b></span><br>
                <span style="font-size: 14px; color: #d9534f"><b>Ley de Protección de la Historia Clínica</b></span>
              </div>`,
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        customClass: {
          container: 'swal-confidencialidad-pp-hc-backdrop'
        }
      });
      registrarAuditoriaPP('Procedimientos program (HC)', PP_ID_TIPO_ATENCION);
    } else if (tabId === "#tabHistInterconsulta") {
      Swal.fire({
        icon: "warning",
        html: `<div style="padding: 0px 20px 20px 20px">
                <p style="font-size: 16px"><b>El historial de Interconsulta contiene datos sensibles y que su protección es esencial para el bienestar del paciente, así como para el cumplimiento de la normativa legal de privacidad y protección de datos y Ley.</b></p>
              </div>`,
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        customClass: {
          container: 'swal-confidencialidad-pp-hi-backdrop'
        }
      });
      registrarAuditoriaPP('Procedimientos program (HI)', null);
    } else if (tabId === "#tabHistProcProg") {
      Swal.fire({
        icon: "warning",
        html: `<div style="padding: 0px 20px 20px 20px">
                <p style="font-size: 16px"><b>El historial de Procedimientos Programados contiene datos sensibles y que su protección es esencial para el bienestar del paciente, así como para el cumplimiento de la normativa legal de privacidad y protección de datos y Ley.</b></p>
              </div>`,
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        customClass: {
          container: 'swal-confidencialidad-pp-hpp-backdrop'
        }
      });
      registrarAuditoriaPP('Procedimientos program (HPP)', PP_ID_TIPO_ATENCION);
    }
  }

  var PP_HIST_PP_CARGADO = false;
  function cargarHistorialProcProgPP() {
    if (PP_HIST_PP_CARGADO) return;
    PP_HIST_PP_CARGADO = true;
    $.ajax({
      url: "/historial_atenciones_procprogramado/" + PP_DNI,
      type: "GET",
      dataType: "json",
      success: function (data) {
        var ppHayInforme = (data || []).some(function (r) { return parseInt(r.tiene_informe) === 1 || r.gastro; });
        $('#tblHistorialInformesPP').DataTable({
          data: data || [],
          destroy: true,
          order: [[4, 'desc']],
          language: {
            emptyTable: "El paciente no tiene procedimientos programados atendidos",
            lengthMenu: "Mostrar _MENU_ registros",
            search: "Buscar:",
            info: "Mostrando _START_ al _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 al 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros)",
            zeroRecords: "No se encontraron resultados",
            paginate: { previous: "Previo", next: "Siguiente" }
          },
          columns: [
            { data: 'establecimiento' },
            { data: 'nro_doc', className: 'text-center' },
            { data: 'servicio_solicitante' },
            { data: 'descripcion', render: function (data, type, row) { return row.codigo + ' - ' + data; } },
            { data: 'fecha_atencion', className: 'text-center', render: function (data, type, row) {
                return type === 'sort' ? row.fecha_orden : data;
              }
            },
            { data: 'medico' },
            { data: null, orderable: false, className: 'text-center', render: function (data, type, row) {
                if (parseInt(row.puede_ver) !== 1) {
                  return `<button type="button" class="btn btn-success btn-sm" disabled title="Atención de otra IPRESS"><i class="fa fa-eye"></i></button>`;
                }
                return `<button type="button" class="btn btn-success btn-sm btnVerAtencionPPHist" title="${row.codigo} - ${row.descripcion}"
                  data-id-solicitud="${row.id_solicitud}" data-id-procedimiento="${row.id_procedimiento}"><i class="fa fa-eye"></i></button>`;
              }
            },
            { data: null, orderable: false, className: 'text-center', visible: ppHayInforme, render: function (data, type, row) {
                if (row.gastro) {
                  var htmlGastro = '';
                  [['baja', 'Endoscopia Baja'], ['alta', 'Endoscopia Alta']].forEach(function (p) {
                    if (parseInt(row.gastro[p[0]]) !== 1) return;
                    htmlGastro += parseInt(row.puede_ver) !== 1
                      ? `<button type="button" class="btn btn-primary btn-sm" style="margin:1px" disabled title="${p[1]} - Informe de otra IPRESS"><i class="fa fa-file-pdf-o"></i></button>`
                      : `<a href="/pp_informe_gastro_pdf/${row.gastro.id}/${p[0]}" target="_blank" class="btn btn-primary btn-sm" style="margin:1px" title="${p[1]}"><i class="fa fa-file-pdf-o"></i></a>`;
                  });
                  if (parseInt(row.tiene_informe) !== 1) return htmlGastro;
                }
                if (parseInt(row.tiene_informe) !== 1) return '';
                if (parseInt(row.puede_ver) !== 1) {
                  return (htmlGastro || '') + `<button type="button" class="btn btn-primary btn-sm" disabled title="Informe de otra IPRESS"><i class="fa fa-file-pdf-o"></i></button>`;
                }
                return (htmlGastro || '') + String(row.ids_informes || '').split(',').filter(Boolean).map(id => `<a href="/pp_ver_informe/${row.id_solicitud}/${row.id_procedimiento}/${id}" target="_blank" class="btn btn-primary btn-sm" style="margin:1px" title="Ver informe"><i class="fa fa-file-pdf-o"></i></a>`).join('');
              }
            }
          ]
        });
        marca_agua_pestana('#tabHistProcProg');
      },
      error: function () {
        PP_HIST_PP_CARGADO = false;
        $('#tblHistorialInformesPP tbody').html('<tr><td colspan="8" class="text-center text-danger">No se pudo cargar el historial</td></tr>');
      }
    });
  }

  function generar_marca_agua_ver_pp_hist() {
    if (!$('#modalVerAtencionPPHist').is(':visible')) return;
    generar_marca_agua_historial('marca_agua_overlay_modal_pp');
  }

  @if($modal)
  (function () {
    registrarAuditoriaPP('Ver Atención Proc. Prog.', PP_ID_TIPO_ATENCION);

    var marcaAguaModalPp = function () { generar_marca_agua_historial('marca_agua_overlay_modal_pp'); };
    setTimeout(marcaAguaModalPp, 400);
    $('#modalVerAtencionPP').off('shown.bs.modal.marcaAguaPp').on('shown.bs.modal.marcaAguaPp', marcaAguaModalPp);
    var resizeMarcaAguaModalPp = null;
    $(window).off('resize.marcaAguaModalPp').on('resize.marcaAguaModalPp', function () {
      clearTimeout(resizeMarcaAguaModalPp);
      resizeMarcaAguaModalPp = setTimeout(function () {
        if ($('#marca_agua_overlay_modal_pp').is(':visible')) marcaAguaModalPp();
      }, 250);
    });
  })();
  @endif

  var PP_HISTORIALES = {
    "#tabHistAtenciones": "/modal_historial_atenciones/" + PP_DNI,
    "#tabHistLaboratorio": "/modal_historial_laboratorio/" + PP_DNI,
    "#tabHistRadiologia": "/modal_historial_radiologia/" + PP_DNI,
    "#tabHistInterconsulta": "/modal_historial_interconsulta/" + PP_DNI,
    "#tabHistReferencias": "/modal_historial_rc/" + PP_ID_ASEGURADO
  };

  $(document).ready(function () {
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var destino = $(e.target).attr('href');
      alertaConfidencialidadPP(destino);
      if (PP_HISTORIALES[destino]) marca_agua_pestana(destino);
      if (!PP_HISTORIALES[destino] || PP_HISTORIAL_CARGADO[destino]) return;
      PP_HISTORIAL_CARGADO[destino] = true;
      $(destino + ' .pp-historial').html('<div class="text-center" style="padding:30px">Cargando...</div>');
      $.ajax({
        url: PP_HISTORIALES[destino],
        type: "GET",
        success: function (result) {
          $(destino + ' .pp-historial').html(result);
          $(destino + ' table').each(function () {
            if ($(this).attr('id') && !$.fn.DataTable.isDataTable(this)) {
              $(this).DataTable({ "sPaginationType": "full_numbers", "language": { "url": "/js/Spanish.json" } });
            }
          });
          marca_agua_pestana(destino);
          setTimeout(function () { marca_agua_pestana(destino); }, 600);
        },
        error: function () {
          PP_HISTORIAL_CARGADO[destino] = false;
          $(destino + ' .pp-historial').html('<div class="text-center text-danger" style="padding:30px">No se pudo cargar el historial</div>');
        }
      });
    });

    @unless($modal)
    $('a[href="#tabHistProcProg"]').on('shown.bs.tab', function () {
      marca_agua_pestana('#tabHistProcProg');
      cargarHistorialProcProgPP();
    });
    $('#tblHistorialInformesPP').on('draw.dt', function () {
      if ($('#tabHistProcProg').is(':visible')) marca_agua_pestana('#tabHistProcProg');
    });

    $('#tblHistorialInformesPP').on('click', '.btnVerAtencionPPHist', function () {
      $('#modalVerAtencionPPHistBody').html('<div class="text-center" style="padding:40px"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>');
      $('#modalVerAtencionPPHist').modal('show');
      $.ajax({
        url: '/modal_ver_atencion_procprogramado/' + $(this).data('id-solicitud') + '/' + $(this).data('id-procedimiento') + '?embebido=1',
        type: 'GET',
        success: function (result) {
          $('#modalVerAtencionPPHistBody').html(result);
          setTimeout(generar_marca_agua_ver_pp_hist, 400);
        },
        error: function () { $('#modalVerAtencionPPHistBody').html('<div class="text-danger text-center" style="padding:40px">No se pudo cargar la atención.</div>'); }
      });
    });
    $('#modalVerAtencionPPHist').on('shown.bs.modal', generar_marca_agua_ver_pp_hist);
    $('#modalVerAtencionPPHist').on('hidden.bs.modal', function () {
      $('#modalVerAtencionPPHistBody').empty();
    });
    @endunless

    var PP_URL_ATENCION_ORIGEN = {
      1: "/mostrar_atencion/",
      2: "/mostrar_atencion_emergencia/",
      3: "/mostrar_atencion_hospitalizacion_hc/"
    };
    $(document).on("click", ".btnVerAtencionOrigen", function () {
      var url = (PP_URL_ATENCION_ORIGEN[PP_ID_TIPO_ATENCION] || PP_URL_ATENCION_ORIGEN[1]) + "{{ $solicitud->id_prestacion }}";
      $("#pp-det-atencion-origen").html('<div class="text-center" style="padding:30px">Cargando...</div>');
      $("#modalAtencionOrigen").modal("show");
      $.ajax({
        url: url,
        type: "GET",
        success: function (result) {
          $("#pp-det-atencion-origen").html(result);
          var $solicitud = $($("#pp-plantilla-solicitud").html());
          var $procMedicos = $("#pp-det-atencion-origen .panel-title").filter(function () {
            return $.trim($(this).text()).toUpperCase().indexOf("PROCEDIMIENTOS M") === 0;
          }).first().closest("fieldset");
          if ($procMedicos.length) {
            $procMedicos.after($solicitud);
          } else {
            $("#pp-det-atencion-origen").append($solicitud);
          }
          setTimeout(function () { generar_marca_agua_atencion_origen(); }, 300);
        },
        error: function () {
          $("#pp-det-atencion-origen").html('<div class="text-center text-danger" style="padding:30px">No se pudo cargar la atención de origen</div>');
        }
      });
    });
    $('#modalAtencionOrigen').on('shown.bs.modal', function () {
      generar_marca_agua_atencion_origen();
    });

    var resizeMarcaAguaHistorialTimer = null;
    $(window).on('resize', function () {
      clearTimeout(resizeMarcaAguaHistorialTimer);
      resizeMarcaAguaHistorialTimer = setTimeout(function () {
        $.each(PP_HISTORIALES, function (tabId) {
          if ($(tabId).is(':visible')) marca_agua_pestana(tabId);
        });
        if ($('#tabHistProcProg').is(':visible')) marca_agua_pestana('#tabHistProcProg');
        generar_marca_agua_ver_pp_hist();
      }, 250);
    });

    $(document).on('draw.dt', '.pp-historial table', function () {
      var tabId = '#' + $(this).closest('.tab-pane').attr('id');
      if ($(tabId).is(':visible')) marca_agua_pestana(tabId);
    });

    $("#btnCancelarPP").on("click", function () {
      window.location.href = "/all_procprogramados";
    });

    $("#observacion_pp").on("input", function () {
      if (/[A-Za-z0-9ÁÉÍÓÚÜÑáéíóúüñ]/.test($.trim($(this).val()))) $(this).removeClass("pp-campo-error");
    });

    $("#btnGuardarPP").on("click", function () {
      var ids = [];
      $(".chk-proc-pp:checked").each(function () { ids.push($(this).val()); });
      if (ids.length == 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Alerta',
          html: 'Seleccione el <b>procedimiento</b> realizado.',
          showConfirmButton: true,
          customClass: {
              popup: 'swal-afiliacion'
          }
        });
        return;
      }
      var observacion = $.trim($("#observacion_pp").val());
      if (!/[A-Za-z0-9ÁÉÍÓÚÜÑáéíóúüñ]/.test(observacion)) {
        $("#observacion_pp").addClass("pp-campo-error");
        Swal.fire({
          icon: 'warning',
          title: 'Alerta',
          html: 'Registre las <b>observaciones</b> del procedimiento.',
          showConfirmButton: true,
          customClass: {
              popup: 'swal-afiliacion'
          }
        }).then(function () {
          $("#observacion_pp").focus();
        });
        return;
      }
      $("#observacion_pp").removeClass("pp-campo-error");
      var msgOrdenes = typeof ppValidarOrdenes === 'function' ? ppValidarOrdenes() : '';
      if (msgOrdenes != '') {
        Swal.fire({ icon: 'warning', title: 'Alerta', html: msgOrdenes, showConfirmButton: true });
        return;
      }
      var datos = typeof ppDatosOrdenes === 'function' ? ppDatosOrdenes() : [];
      datos.push({ name: '_token', value: PP_TOKEN });
      datos.push({ name: 'id_solicitud', value: {{ intval($solicitud->id_solicitud) }} });
      datos.push({ name: 'observacion', value: $("#observacion_pp").val() });
      $.each(ids, function (i, id) { datos.push({ name: 'ids[]', value: id }); });
      var $btn = $("#btnGuardarPP").prop("disabled", true);
      $.ajax({
        url: "/guardar_atencion_procprogramado",
        type: "POST",
        data: datos,
        success: function (res) {
          if (res.success) {
            window.PP_ORDENES_GUARDADAS = true;
            window.location.href = "/ver_atencion_procprogramado/{{ intval($solicitud->id_solicitud) }}/{{ intval($id_procedimiento) }}";
          } else {
            Swal.fire({ icon: 'warning', title: 'Alerta', html: res.message, showConfirmButton: true, customClass: { popup: 'swal-afiliacion' } });
            $btn.prop("disabled", false);
          }
        },
        error: function () {
          Swal.fire({ icon: 'error', title: 'Alerta', html: 'No se pudo registrar la atención.', showConfirmButton: true, customClass: { popup: 'swal-afiliacion' } });
          $btn.prop("disabled", false);
        }
      });
    });

    $("#btnFinalizarPP").on("click", function () {
      if (window.PP_EDITANDO_OBS) {
        Swal.fire({ icon: 'warning', title: 'Alerta', html: 'Guarde los cambios de la <b>observación</b> antes de finalizar.', showConfirmButton: true, customClass: { popup: 'swal-afiliacion' } });
        return;
      }
      var $btn = $("#btnFinalizarPP").prop("disabled", true);
      $.ajax({
        url: "/finalizar_atencion_procprogramado",
        type: "POST",
        data: {
          _token: PP_TOKEN,
          id_solicitud: {{ intval($solicitud->id_solicitud) }},
          id_procedimiento: {{ intval($id_procedimiento) }},
          observacion: $("#observacion_pp_confirmacion").val()
        },
        success: function (res) {
          if (res.success) {
            var irAlListado = function () {
              if (window.PP_ES_MODAL) { $('#modalVerAtencionPP').modal('hide'); return; }
              window.location.href = "/all_procprogramados";
            };
            if (res.pendiente_token && typeof ppIniciarFirmaToken === 'function') {
              ppIniciarFirmaToken();
              return;
            }
            irAlListado();
          } else {
            Swal.fire({ icon: 'warning', title: 'Alerta', html: res.message, showConfirmButton: true, customClass: { popup: 'swal-afiliacion' } });
            $btn.prop("disabled", false);
          }
        },
        error: function () {
          Swal.fire({ icon: 'error', title: 'Alerta', html: 'No se pudo finalizar la atención.', showConfirmButton: true, customClass: { popup: 'swal-afiliacion' } });
          $btn.prop("disabled", false);
        }
      });
    });

    var ppObsOriginal = $("#observacion_pp_confirmacion").val();
    $("#VerGastro .pp-gastro-campo").each(function () { $(this).data("original", $(this).val()); });
    function ppGastroCampos() {
      var campos = {};
      $("#VerGastro .pp-gastro-campo").each(function () { campos[$(this).data("campo")] = $(this).val(); });
      return campos;
    }
    function ppGastroCambio() {
      var cambio = false;
      $("#VerGastro .pp-gastro-campo").each(function () {
        if ($.trim($(this).val()) !== $.trim($(this).data("original"))) cambio = true;
      });
      return cambio;
    }
    function ppGastroRestaurar() {
      $("#VerGastro .pp-gastro-campo").each(function () { $(this).val($(this).data("original")); });
    }
    function ppGastroConfirmar() {
      $("#VerGastro .pp-gastro-campo").each(function () {
        var valor = $.trim($(this).val());
        $(this).val(valor).data("original", valor);
        $(this).siblings(".pp-gastro-valor").text(valor);
      });
    }
    function ppModoEdicionObs(activo) {
      window.PP_EDITANDO_OBS = activo;
      $("#VerGastro .pp-gastro-valor").toggle(!activo);
      $("#VerGastro .pp-gastro-campo").toggle(activo);
      $("#observacion_pp_confirmacion").prop("readonly", !activo).css("background", activo ? "#fff" : "#f9f9f9");
      $("#btnEditarObsPP").toggle(!activo);
      $("#btnGuardarObsPP").toggle(activo);
      $("#btnFinalizarPP").prop("disabled", activo).attr("title", activo ? "Guarde los cambios de la observación antes de finalizar" : null);
    }
    $("#btnEditarObsPP").on("click", function () {
      ppModoEdicionObs(true);
      $("#observacion_pp_confirmacion").focus();
    });
    $("#btnGuardarObsPP").on("click", function () {
      var observacion = $.trim($("#observacion_pp_confirmacion").val());
      if (!/[A-Za-z0-9ÁÉÍÓÚÜÑáéíóúüñ]/.test(observacion)) {
        Swal.fire({ icon: 'warning', title: 'Alerta', html: 'Registre las <b>observaciones</b> del procedimiento.', showConfirmButton: true, customClass: { popup: 'swal-afiliacion' } });
        return;
      }
      if (observacion === $.trim(ppObsOriginal) && !ppGastroCambio()) {
        $("#observacion_pp_confirmacion").val(ppObsOriginal);
        ppGastroRestaurar();
        ppModoEdicionObs(false);
        return;
      }
      var $btn = $("#btnGuardarObsPP").prop("disabled", true);
      $.ajax({
        url: "/pp_editar_observacion_atencion",
        type: "POST",
        data: {
          _token: PP_TOKEN,
          id_solicitud: {{ intval($solicitud->id_solicitud) }},
          id_procedimiento: {{ intval($id_procedimiento) }},
          observacion: observacion,
          gastro: ppGastroCampos()
        },
        success: function (res) {
          $btn.prop("disabled", false);
          if (res.success) {
            ppObsOriginal = res.observacion;
            $("#observacion_pp_confirmacion").val(ppObsOriginal);
            ppGastroConfirmar();
            ppModoEdicionObs(false);
            Swal.fire({ icon: 'success', title: 'Correcto', html: res.message, timer: 2000, showConfirmButton: false, customClass: { popup: 'swal-afiliacion' } });
          } else {
            Swal.fire({ icon: 'warning', title: 'Alerta', html: res.message, showConfirmButton: true, customClass: { popup: 'swal-afiliacion' } });
          }
        },
        error: function () {
          $btn.prop("disabled", false);
          Swal.fire({ icon: 'error', title: 'Alerta', html: 'No se pudo guardar los cambios.', showConfirmButton: true, customClass: { popup: 'swal-afiliacion' } });
        }
      });
    });

    $("#btnVolverPP").on("click", function () {
      if (window.PP_EDITANDO_OBS) {
        $("#observacion_pp_confirmacion").val(ppObsOriginal);
        ppGastroRestaurar();
        ppModoEdicionObs(false);
        return;
      }
      if (window.PP_ES_MODAL) { $('#modalVerAtencionPP').modal('hide'); return; }
      if (window.self !== window.top) { window.parent.$('#modalVerAtencionPP').modal('hide'); return; }
      window.location.href = "/all_procprogramados";
    });
    if (window.self !== window.top) {
      registrarAuditoriaPP('Ver Atención Proc. Prog.', PP_ID_TIPO_ATENCION);
      var marcaAguaIframePp = function () { generar_marca_agua_historial('marca_agua_overlay_iframe_pp'); };
      marcaAguaIframePp();
      $(window).on('resize', marcaAguaIframePp);
      $(document).on('click', '.pp-btn-agregar', function () { setTimeout(marcaAguaIframePp, 300); });
      $('form[action^="/pp_invalidar"]').on('submit', function () { window.parent.PP_LISTADO_CAMBIO = true; });
    }
  });
</script>
