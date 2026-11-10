@extends('frontend.layouts.master2')
@section('renderjs')
<style>
.row {
  margin-top: 10px;
  padding: 0 10px;
}

.dataTables_filter {
  display: none;
}

#tblProcProgramados th {
  text-align: center;
  vertical-align: middle;
  font-size: 12px;
}

#tblProcProgramados td {
  vertical-align: middle;
  font-size: 11px;
}

.pp-label {
  font-weight: bold;
}

.pp-accion {
  display: inline-block;
  text-align: center;
  margin: 0 8px;
  font-size: 11px;
  font-weight: bold;
}

.pp-accion .btn {
  display: block;
  margin: 3px auto 0;
  width: 24px !important;
  height: 24px !important;
  padding: 0 !important;
  font-size: 11px !important;
  line-height: 24px !important;
  border-radius: 3px !important;
  text-align: center !important;
}

.btn-exportar-pp {
  color: #fff;
  background-color: #1e6b34;
  border-color: #17552a;
}

.btn-exportar-pp:hover,
.btn-exportar-pp:focus,
.btn-exportar-pp:active {
  color: #fff;
  background-color: #17552a;
  border-color: #113f1f;
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

.btn-ga_baja { color: #fff; background-color: #572364; border-color: #42214b; }
.btn-ga_baja:hover, .btn-ga_baja:focus { color: #fff; background-color: #42214b; }
.btn-ga_alta { color: #fff; background-color: #000080; border-color: #0a0a5c; }
.btn-ga_alta:hover, .btn-ga_alta:focus { color: #fff; background-color: #0a0a5c; }
.btn-subir-informe { color: #fff; background-color: #17a2d8; border-color: #1390c0; }
.btn-subir-informe:hover, .btn-subir-informe:focus { color: #fff; background-color: #1390c0; border-color: #0f7aa3; }
.btn-electronico { color: #fff; background-color: #5bc0de; border-color: #46b8da; }
.btn-electronico:hover, .btn-electronico:focus { color: #fff; background-color: #31B0D5; }
</style>
<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
$(document).ready(function() {
  $('#fecha_ini').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
  });
  $('#fecha_fin').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
  });

  $('#btnBuscar').on('click', function() {
    datatableProcProgramados();
  });

  $('#dni_beneficiario, #nombre_beneficiario').on('keypress', function(e) {
    if (e.which == 13) {
      e.preventDefault();
      datatableProcProgramados();
    }
  });

  datatableProcProgramados();
});

function txt(valor) {
  return (valor != null && valor !== undefined) ? valor : '';
}

function htmlPaciente(row) {
  var html = txt(row.paciente) + '<br>';
  html += 'DNI - ' + txt(row.nro_doc_ident) + (row.sexo ? ' (' + row.sexo + ')' : '') + '<br>';
  html += '<span class="pp-label">F.NAC:</span> ' + txt(row.fecha_nacimiento) + ' - <span class="pp-label">EDAD:</span> ' + txt(row.edad) + '<br>';
  html += '<span class="pp-label">CONDICIÓN:</span> ' + txt(row.condicion);
  return html;
}

function htmlSolicitud(row) {
  var html = '<span class="pp-label">Nº:</span> ' + txt(row.id_solicitud) + ' ' + txt(row.fecha_solicitud) + '<br>';
  html += '<span class="pp-label">ORIGEN:</span> ' + txt(row.tipo_atencion) + '<br>';
  html += '<span class="pp-label">SERVICIO:</span> ' + txt(row.servicio) + '<br>';
  html += '<span class="pp-label">PROCEDIMIENTO:</span> ' + txt(row.codigo_procedimiento) + ' - ' + txt(row.procedimiento);
  return html;
}

function htmlProgramacion(row) {
  var html = '<span class="pp-label">F.CITA:</span> ' + txt(row.fecha_cita) + '<br>';
  html += '<span class="pp-label">CONSULTORIO:</span> ' + txt(row.consultorio);
  return html;
}

function htmlAtencion(row, conProcedimiento) {
  var html = '<span class="pp-label">FECHA Y HORA DE ATENCIÓN:</span> ' + txt(row.fecha_atencion) + '<br>';
  html += '<span class="pp-label">RESPONSABLE DE ATENCIÓN:</span> ' + txt(row.responsable_atencion);
  if (conProcedimiento) {
    html += '<br><span class="pp-label">PROCEDIMIENTO:</span> ' + txt(row.codigo_procedimiento) + ' - ' + txt(row.procedimiento);
  }
  return html;
}

// Anulación (estado 4) u observación (5): mismas columnas
function htmlAnulacion(row) {
  var tipo = parseInt(row.estado) === 5 ? 'OBSERVACIÓN' : 'ANULACIÓN';
  var html = '<span class="pp-label">FECHA Y HORA DE ' + tipo + ':</span> ' + txt(row.fecha_anulacion) + '<br>';
  html += '<span class="pp-label">RESPONSABLE:</span> ' + txt(row.responsable_anulacion) + '<br>';
  html += '<span class="pp-label">MOTIVO:</span> ' + txt(row.motivo_anulacion);
  return html;
}

function botonAccion(titulo, clase, icono, accion, row) {
  return '<div class="pp-accion">' + titulo +
    '<a href="javascript:void(0)" class="btn ' + clase + ' btn-sm ' + accion + '" data-id="' + txt(row.id_pp) + '" data-id-solicitud="' + txt(row.id_solicitud) + '" data-id-procedimiento="' + txt(row.id_procedimiento) + '"><i class="fa ' + icono + '"></i></a></div>';
}

$(document).on('click', '.btnAtenderPP', function() {
  window.location.href = '/atender_procprogramado/' + $(this).data('id-solicitud') + '/' + $(this).data('id-procedimiento');
});

$(document).on('click', '.btnVerAtencionPP', function() {
  var idSolicitud = $(this).data('id-solicitud');
  var idProcedimiento = $(this).data('id-procedimiento');
  var $cargando = $('<div class="text-center" style="position:absolute;left:0;right:0;top:40px"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>');
  var $iframe = $('<iframe>', {
    src: '/ver_atencion_procprogramado/' + idSolicitud + '/' + idProcedimiento,
    title: 'Ver atención del procedimiento programado',
    css: { width: '100%', height: 'calc(80vh - 30px)', border: 0, visibility: 'hidden', display: 'block' }
  }).on('load', function() {
    $cargando.remove();
    $(this).css('visibility', 'visible');
  });
  $('#modalVerAtencionPPBody').empty().append($('<div style="position:relative"></div>').append($cargando, $iframe));
  $('#modalVerAtencionPP').modal('show');
});
$('#modalVerAtencionPP').on('hidden.bs.modal', function() {
  $('#modalVerAtencionPPBody').empty();
  if (window.PP_LISTADO_CAMBIO) {
    window.PP_LISTADO_CAMBIO = false;
    datatableProcProgramados();
  }
});

var anularPpIdSolicitud = null;
var anularPpIdProcedimiento = null;
$(document).on('click', '.btnAnularPP', function() {
  anularPpIdSolicitud = $(this).data('id-solicitud');
  anularPpIdProcedimiento = $(this).data('id-procedimiento');
  $('#motivoAnularPP').val('');
  $('#modalAnularPP').modal('show');
});

$(document).on('click', '#btnConfirmarAnularPP', function() {
  var motivo = $.trim($('#motivoAnularPP').val());
  var letras = (motivo.match(/[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]/g) || []).length;
  if (letras < 5) {
    Swal.fire({
      icon: 'warning',
      title: 'Alerta',
      html: 'Registre un <b>motivo</b> válido.',
      showConfirmButton: true,
      customClass: { popup: 'swal-afiliacion' }
    });
    return;
  }
  var $btn = $(this).prop('disabled', true);
  $.ajax({
    url: '/anular_procprogramado',
    type: 'POST',
    data: {
      _token: $('#_token').val(),
      id_solicitud: anularPpIdSolicitud,
      id_procedimiento: anularPpIdProcedimiento,
      motivo: motivo
    },
    success: function(res) {
      $btn.prop('disabled', false);
      if (res.success) {
        $('#modalAnularPP').modal('hide');
        datatableProcProgramados();
      } else {
        bootbox.alert(res.message);
      }
    },
    error: function() {
      $btn.prop('disabled', false);
      bootbox.alert('No se pudo anular la atención.');
    }
  });
});

// Observar (pendiente o citado): pasa a OBSERVADO, definitivo; si estaba citado se anula su cita
var observarPpIdSolicitud = null;
var observarPpIdProcedimiento = null;
$(document).on('click', '.btnObservarPP', function() {
  observarPpIdSolicitud = $(this).data('id-solicitud');
  observarPpIdProcedimiento = $(this).data('id-procedimiento');
  $('#motivoObservarPP').val('');
  $('#modalObservarPP').modal('show');
});

$(document).on('click', '#btnConfirmarObservarPP', function() {
  var motivo = $.trim($('#motivoObservarPP').val());
  var letras = (motivo.match(/[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]/g) || []).length;
  if (letras < 5) {
    Swal.fire({
      icon: 'warning',
      title: 'Alerta',
      html: 'Registre un <b>motivo</b> válido.',
      showConfirmButton: true,
      customClass: { popup: 'swal-afiliacion' }
    });
    return;
  }
  var $btn = $(this).prop('disabled', true);
  $.ajax({
    url: '/observar_procprogramado',
    type: 'POST',
    data: {
      _token: $('#_token').val(),
      id_solicitud: observarPpIdSolicitud,
      id_procedimiento: observarPpIdProcedimiento,
      motivo: motivo
    },
    success: function(res) {
      $btn.prop('disabled', false);
      if (res.success) {
        $('#modalObservarPP').modal('hide');
        datatableProcProgramados();
      } else {
        bootbox.alert(res.message);
      }
    },
    error: function() {
      $btn.prop('disabled', false);
      bootbox.alert('No se pudo observar el procedimiento.');
    }
  });
});

function accionesPendiente(row) {
  // Atención ya guardada (sin finalizar): solo Finalizar
  if (parseInt(row.en_atencion) === 1) {
    return accionesEnAtencion(row);
  }
  return botonAccion('Atender', 'btn-success', 'fa-pencil', 'btnAtenderPP', row) +
    botonAccion('Observar', 'btn-warning', 'fa-search', 'btnObservarPP', row);
}

function accionesAtendido(row) {
  var informe = botonInformePP(row);
  var hayDocumentos = parseInt(row.tiene_ordenes) === 1 || parseInt(row.tiene_informe) === 1 || row.gastro;
  var documentos = hayDocumentos ? botonAccion('Documentos', 'btn-info', 'fa-eye', 'btnVerOrdenesPP', row) : '';
  return botonAccion('Ver Atención', 'btn-success', 'fa-eye', 'btnVerAtencionPP', row) +
    informe + documentos +
    botonAccion('Anular', 'btn-danger', 'fa-times', 'btnAnularPP', row);
}

var documentosIdSolicitud = null;
var documentosIdProcedimiento = null;
$(document).on('click', '.btnVerOrdenesPP', function() {
  documentosIdSolicitud = $(this).data('id-solicitud');
  documentosIdProcedimiento = $(this).data('id-procedimiento');
  $('#modalOrdenesPP').modal('show');
  cargarDocumentosPP();
});

function cargarDocumentosPP() {
  $('#modalOrdenesPPBody').html('<div class="text-center" style="padding:40px"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>');
  $.ajax({
    url: '/pp_ordenes_atencion/' + documentosIdSolicitud + '/' + documentosIdProcedimiento,
    type: 'GET',
    success: function(result) { $('#modalOrdenesPPBody').html(result); },
    error: function() { $('#modalOrdenesPPBody').html('<div class="text-danger text-center" style="padding:40px">No se pudieron cargar los documentos.</div>'); }
  });
}

$(document).on('click', '#btnSubirInformeDocumentosPP', function() {
  var $btn = $(this);
  subirInformesPP($('#archivoInformeDocumentosPP')[0].files, documentosIdSolicitud, documentosIdProcedimiento, parseInt($btn.data('disponibles')), $btn, function() {
    cargarDocumentosPP();
  });
});

$(document).on('click', '.btnEliminarInformePP', function() {
  var idInforme = $(this).data('id-informe');
  Swal.fire({
    icon: 'warning',
    title: 'Eliminar informe',
    html: '¿Está seguro de eliminar este informe?',
    showCancelButton: true,
    confirmButtonText: 'Eliminar',
    cancelButtonText: 'Cancelar',
    customClass: { popup: 'swal-afiliacion' }
  }).then(function(respuesta) {
    if (!respuesta.isConfirmed) return;
    $.ajax({
      url: '/pp_eliminar_informe',
      type: 'POST',
      data: { _token: $('#_token').val(), id_informe: idInforme },
      success: function(res) {
        if (res.success) {
          Swal.fire({ icon: 'success', title: res.message, showConfirmButton: false, timer: 1500 });
          cargarDocumentosPP();
          datatableProcProgramados();
        } else {
          bootbox.alert(res.message);
        }
      },
      error: function() { bootbox.alert('No se pudo eliminar el informe.'); }
    });
  });
});

function botonInformePP(row) {
  if (parseInt(row.requiere_informe) !== 1) return '';
  if (parseInt(row.tiene_informe) === 1) return '';
  if (parseInt(row.puede_subir_informe) === 1) {
    return botonAccion('Subir Informe', 'btn-subir-informe', 'fa-upload', 'btnSubirInformePP', row);
  }
  return '<div class="pp-accion" title="Pendiente: lo sube el médico que realizó la atención">Informe' +
    '<button class="btn btn-default btn-sm" disabled><i class="fa fa-clock-o"></i></button></div>';
}

var subirInformeIdSolicitud = null;
var subirInformeIdProcedimiento = null;
$(document).on('click', '.btnSubirInformePP', function() {
  subirInformeIdSolicitud = $(this).data('id-solicitud');
  subirInformeIdProcedimiento = $(this).data('id-procedimiento');
  $('#archivoInformePP').val('');
  $('#modalSubirInformePP').modal('show');
});

$(document).on('click', '#btnConfirmarSubirInformePP', function() {
  subirInformesPP($('#archivoInformePP')[0].files, subirInformeIdSolicitud, subirInformeIdProcedimiento, 3, $(this), function() {
    $('#modalSubirInformePP').modal('hide');
  });
});

function subirInformesPP(archivos, idSolicitud, idProcedimiento, maximo, $btn, alSubir) {
  var alerta = function(mensaje) {
    Swal.fire({ icon: 'warning', title: 'Alerta', html: mensaje, showConfirmButton: true, customClass: { popup: 'swal-afiliacion' } });
  };
  if (archivos.length === 0) {
    alerta('Seleccione el archivo <b>PDF</b> del informe.');
    return;
  }
  if (archivos.length > maximo) {
    alerta('Puede subir hasta <b>' + maximo + '</b> PDF.');
    return;
  }
  var datos = new FormData();
  datos.append('_token', $('#_token').val());
  datos.append('id_solicitud', idSolicitud);
  datos.append('id_procedimiento', idProcedimiento);
  for (var i = 0; i < archivos.length; i++) {
    if (!/\.pdf$/i.test(archivos[i].name)) {
      alerta('El informe debe ser un archivo <b>PDF</b>.');
      return;
    }
    if (archivos[i].size > 10 * 1024 * 1024) {
      alerta('Cada PDF no debe superar los <b>10 MB</b>.');
      return;
    }
    datos.append('informes[]', archivos[i]);
  }
  $btn.prop('disabled', true);
  $.ajax({
    url: '/pp_subir_informe',
    type: 'POST',
    data: datos,
    processData: false,
    contentType: false,
    success: function(res) {
      $btn.prop('disabled', false);
      if (res.success) {
        alSubir();
        Swal.fire({ icon: 'success', title: res.message, showConfirmButton: false, timer: 1500 });
        datatableProcProgramados();
      } else {
        bootbox.alert(res.message);
      }
    },
    error: function() {
      $btn.prop('disabled', false);
      bootbox.alert('No se pudo subir el informe.');
    }
  });
}

function accionesEnAtencion(row) {
  return botonAccion('Finalizar', 'btn-primary', 'fa-check', 'btnFinalizarAtencionPP', row) +
    botonesGastro(row, false);
}

function botonesGastro(row, atendido) {
  var g = row.gastro;
  if (!g) return '';
  var flag = parseInt(g.flag) || 0;
  var estadoFirma = flag === 1 ? 'Firmado digitalmente' : (flag === 2 ? 'Firmado electrónicamente' : 'Sin firma');
  var html = '';
  var partes = [{ clave: 'baja', titulo: 'Informe (PDF)', clase: 'btn-ga_baja' }, { clave: 'alta', titulo: 'Informe (PDF)', clase: 'btn-ga_alta' }];
  $.each(partes, function(i, p) {
    if (parseInt(g[p.clave]) !== 1) return;
    html += '<div class="pp-accion">' + p.titulo +
      '<a href="/pp_informe_gastro_pdf/' + g.id + '/' + p.clave + '" target="_blank" class="btn ' + p.clase + ' btn-sm" title="' + estadoFirma + '"><i class="fa fa-file-pdf-o"></i></a></div>';
  });
  return html;
}

$(document).on('click', '.btnFinalizarAtencionPP', function() {
  window.location.href = '/ver_atencion_procprogramado/' + $(this).data('id-solicitud') + '/' + $(this).data('id-procedimiento');
});

function nroFila(meta) {
  return meta.settings._iDisplayStart + meta.row + 1;
}

function estadoFila(row) {
  var estado = parseInt(row.estado);
  if (estado === 3) return 3;
  if (estado === 4) return 4;
  if (estado === 5) return 5;
  return 1;
}

function columnasPorEstado(estado) {
  if (estado === 1) {
    return [
      { titulo: 'Nº', ancho: '4%', clase: 'text-center', render: function(row, meta) { return nroFila(meta); } },
      { titulo: 'DATOS DEL PACIENTE', ancho: '22%', render: htmlPaciente },
      { titulo: 'DATOS DE LA SOLICITUD', ancho: '34%', render: htmlSolicitud },
      { titulo: 'PROGRAMACIÓN DEL PROCEDIMIENTO', ancho: '22%', render: htmlProgramacion },
      { titulo: 'ACCIONES', ancho: '18%', clase: 'text-center', render: accionesPendiente }
    ];
  }
  if (estado === 3) {
    return [
      { titulo: 'Nº', ancho: '4%', clase: 'text-center', render: function(row, meta) { return nroFila(meta); } },
      { titulo: 'DATOS DEL PACIENTE', ancho: '22%', render: htmlPaciente },
      { titulo: 'DATOS DE LA SOLICITUD', ancho: '30%', render: htmlSolicitud },
      { titulo: 'DATOS DE LA ATENCIÓN', ancho: '24%', render: function(row) { return htmlAtencion(row, false); } },
      { titulo: 'ACCIONES', ancho: '20%', clase: 'text-center', render: accionesAtendido }
    ];
  }
  if (estado === 4) {
    return [
      { titulo: 'Código', ancho: '6%', clase: 'text-center', render: function(row) { return txt(row.id_solicitud); } },
      { titulo: 'DATOS DEL PACIENTE', ancho: '26%', render: htmlPaciente },
      { titulo: 'DATOS DE LA ATENCIÓN', ancho: '34%', render: function(row) { return htmlAtencion(row, true); } },
      { titulo: 'DATOS DE ANULACIÓN', ancho: '34%', render: htmlAnulacion }
    ];
  }
  if (estado === 5) {
    return [
      { titulo: 'Código', ancho: '6%', clase: 'text-center', render: function(row) { return txt(row.id_solicitud); } },
      { titulo: 'DATOS DEL PACIENTE', ancho: '26%', render: htmlPaciente },
      { titulo: 'DATOS DE LA SOLICITUD', ancho: '34%', render: htmlSolicitud },
      { titulo: 'DATOS DE LA OBSERVACIÓN', ancho: '34%', render: htmlAnulacion }
    ];
  }
  return [
    { titulo: 'Código', ancho: '6%', clase: 'text-center', render: function(row) { return txt(row.id_solicitud); } },
    { titulo: 'DATOS DEL PACIENTE', ancho: '22%', render: htmlPaciente },
    { titulo: 'DATOS DE LA SOLICITUD', ancho: '34%', render: htmlSolicitud },
    { titulo: 'ESTADO', ancho: '10%', clase: 'text-center', render: function(row) {
        var e = estadoFila(row);
        if (e === 3) return '<b style="color: #5cb85c">ATENDIDO</b>';
        if (e === 4) return '<b>ANULADO</b>';
        if (e === 5) return '<b style="color: #8a6d3b;">OBSERVADO</b>';
        if (parseInt(row.en_atencion) === 1) return '<b style="color: #e67e00;">EN ATENCIÓN</b>';
        return '<b style="color: red;">PENDIENTE</b>';
      }
    },
    { titulo: 'ACCIONES', ancho: '28%', clase: 'text-center', render: function(row) {
        var e = estadoFila(row);
        if (e === 3) return accionesAtendido(row);
        if (e === 4 || e === 5) return '<div class="text-left">' + htmlAnulacion(row) + '</div>';
        return accionesPendiente(row);
      }
    }
  ];
}

function datatableProcProgramados() {
  var estado = parseInt($('#estado').val());
  var columnas = columnasPorEstado(estado);

  if ($.fn.DataTable.isDataTable('#tblProcProgramados')) {
    $('#tblProcProgramados').DataTable().destroy();
  }

  var thead = '<thead><tr>';
  $.each(columnas, function(i, col) {
    thead += '<th style="width:' + col.ancho + '">' + col.titulo + '</th>';
  });
  thead += '</tr></thead><tbody></tbody>';
  $('#tblProcProgramados').empty().html(thead);

  var aoColumns = [];
  var aoColumnDefs = [];
  $.each(columnas, function(i, col) {
    aoColumns.push({});
    aoColumnDefs.push({
      "mRender": function(data, type, row, meta) {
        return col.render(row, meta);
      },
      "aTargets": [i],
      "className": col.clase ? col.clase : ''
    });
  });

  $('#tblProcProgramados').dataTable({
    "bServerSide": true,
    "sAjaxSource": "/listar_procprogramados_ajax",
    "bProcessing": true,
    "sPaginationType": "full_numbers",
    "bFilter": false,
    "bSort": false,
    "info": true,
    "language": {
      "url": "/js/Spanish.json"
    },
    "autoWidth": false,
    "bLengthChange": true,
    "destroy": true,
    "lengthMenu": [
      [10, 50, 100, 200],
      [10, 50, 100, 200]
    ],
    "aoColumns": aoColumns,
    "dom": '<"top">rt<"bottom"flpi><"clear">',
    "fnDrawCallback": function() {
      $('[data-toggle="tooltip"]').tooltip();
    },
    "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
      var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
      var iCantMostrar = aoData[4].value;
      oSettings.jqXHR = $.ajax({
        "dataType": 'json',
        "type": "POST",
        "url": sSource,
        "data": {
          NumeroPagina: iNroPagina,
          NumeroRegistros: iCantMostrar,
          fecha_ini: $('#fecha_ini').val(),
          fecha_fin: $('#fecha_fin').val(),
          dni_beneficiario: $('#dni_beneficiario').val(),
          nombre_beneficiario: $('#nombre_beneficiario').val(),
          estado: $('#estado').val(),
          id_tipo_atencion: $('#id_tipo_atencion').val(),
          _token: $('#_token').val()
        },
        "success": function(result) {
          fnCallback(result);
        }
      });
    },
    "aoColumnDefs": aoColumnDefs
  });
}
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">MÓDULO DE PROCEDIMIENTOS PROGRAMADOS</h3>
    </div>
    <div class="panel-body">
      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

      <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
          <label class="form-control-sm">Fecha de Solicitud (Desde)</label>
          <input class="form-control input-sm" type="text" id="fecha_ini" placeholder="Fecha Desde" value="{{ $fechaDesde }}" maxlength="10">
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
          <label class="form-control-sm">Fecha de Solicitud (Hasta)</label>
          <input class="form-control input-sm" type="text" id="fecha_fin" placeholder="Fecha Hasta" value="{{ $fechaHasta }}" maxlength="10">
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
          <label class="form-control-sm">Nº de Documento</label>
          <input class="form-control input-sm" type="text" id="dni_beneficiario" maxlength="12">
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
          <label class="form-control-sm">Nombres y Apellidos</label>
          <input class="form-control input-sm" type="text" id="nombre_beneficiario" maxlength="60">
        </div>
        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
          <label class="form-control-sm">Estado de Solicitud</label>
          <select class="form-control input-sm" id="estado">
            <option value="0" selected="selected">TODOS</option>
            <option value="1">PENDIENTE</option>
            <option value="3">ATENDIDO</option>
            <option value="4">ANULADO</option>
            <option value="5">OBSERVADO</option>
          </select>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
          <label class="form-control-sm">Tipo de Atención</label>
          <select class="form-control input-sm" id="id_tipo_atencion">
            <option value="0" selected="selected">TODOS</option>
            @foreach ($tipo_atenciones as $at)
              <option value="{{ $at->id }}">{{ $at->nombre }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6" style="padding-top:20px">
          <button id="btnBuscar" class="form-control btn btn-success input-sm" type="button">Buscar</button>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="tblProcProgramados" class="table table-bordered" style="width:100%">
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalVerAtencionPP" role="dialog">
  <div class="modal-dialog modal-lg" role="document" style="width: 1200px; max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">VER ATENCIÓN DEL PROCEDIMIENTO PROGRAMADO</h5>
      </div>
      <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
        <div id="modalVerAtencionPPBody"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalOrdenesPP" role="dialog">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">DOCUMENTOS DE LA ATENCIÓN DEL PROCEDIMIENTO</h5>
      </div>
      <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
        <div id="modalOrdenesPPBody"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalSubirInformePP" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">SUBIR INFORME DEL PROCEDIMIENTO</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="archivoInformePP">Archivos PDF del informe (hasta 3, máximo 10 MB cada uno)</label>
          <input type="file" id="archivoInformePP" class="form-control" accept="application/pdf,.pdf" multiple>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btnConfirmarSubirInformePP" class="btn btn-success">Subir</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAnularPP" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">ANULAR ATENCIÓN DEL PROCEDIMIENTO</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label style="font-size:12px">Motivo de la anulación</label>
          <textarea id="motivoAnularPP" class="form-control" rows="4" maxlength="500" placeholder="--- Registre el motivo de la anulación ---"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btnConfirmarAnularPP" class="btn btn-danger">Anular</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalObservarPP" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">OBSERVAR PROCEDIMIENTO PROGRAMADO</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label style="font-size:12px">Motivo de la observación</label>
          <textarea id="motivoObservarPP" class="form-control" rows="4" maxlength="500" placeholder="--- Registre el motivo de la observación ---"></textarea>
        </div>
        <small class="text-muted">La observación es definitiva. Si el procedimiento tiene cita, la cita se anulará.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btnConfirmarObservarPP" class="btn btn-warning">Observar</button>
      </div>
    </div>
  </div>
</div>
@endsection
