function getHistorialAtencionMedica(nro_doc_ident){
  $.ajax({
    url: `/modal_historial_atenciones/${nro_doc_ident}`,
    type: 'GET',
    beforeSend: function() {
      Swal.fire({
        title: 'Historial de Atenciones',
        text: 'Cargando datos, Por favor espera.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }
  }).done(function(response) {
      $("#contentHistorialAtenciones").html(response);
      $('#tablemodalm').DataTable({
        "order": [],
        "sPaginationType": "full_numbers",
        "language": {"url": "/js/Spanish.json"},
      });
      Swal.close();
      if (typeof generar_marca_agua_hist_enfermeria === 'function') {
        generar_marca_agua_hist_enfermeria();
      }
      if (typeof mostrarAvisoConfidencialidadEnfermeria === 'function') {
        mostrarAvisoConfidencialidadEnfermeria();
      }
  }).fail(function(jqXHR, textStatus, errorThrown) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error al procesar la solicitud.'
    });
  });
}

function getHistorialLaboratorio(nro_doc_ident){
  $.ajax({
    url: `/modal_historial_laboratorio/${nro_doc_ident}`,
    type: 'GET',
    beforeSend: function() {
      Swal.fire({
        title: 'Historial de Laboratorio',
        text: 'Cargando datos, Por favor espera.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }
  }).done(function(response) {
      $("#divLaboratorio").html(response);
      $('#tablemodalm1').DataTable({
        "order": [],
        "sPaginationType": "full_numbers",
        "language": {"url": "/js/Spanish.json"},
      });
      Swal.close();
  }).fail(function(jqXHR, textStatus, errorThrown) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error al procesar la solicitud.'
    });
  });
}

function getHistorialRadiologia(nro_doc_ident){
  $.ajax({
    url: `/modal_historial_radiologia/${nro_doc_ident}`,
    type: 'GET',
    beforeSend: function() {
      Swal.fire({
        title: 'Historial de Radiología',
        text: 'Cargando datos, Por favor espera.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }
  }).done(function(response) {
      $("#divRadiologico").html(response);
      $('#tablemodalm2').DataTable({
        "order": [],
        "sPaginationType": "full_numbers",
        "language": {"url": "/js/Spanish.json"},
      });
      Swal.close();
  }).fail(function(jqXHR, textStatus, errorThrown) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error al procesar la solicitud.'
    });
  });
}

function getHistorialInterconsulta(nro_doc_ident){
  $.ajax({
    url: `/modal_historial_interconsulta/${nro_doc_ident}`,
    type: 'GET',
    beforeSend: function() {
      Swal.fire({
        title: 'Historial de Interconsultas',
        text: 'Cargando datos, Por favor espera.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }
  }).done(function(response) {
      $("#divInterconsultaH").html(response);
      $('#tablemodalm5').DataTable({
        "order": [],
        "sPaginationType": "full_numbers",
        "language": {"url": "/js/Spanish.json"},
      });
      Swal.close();
  }).fail(function(jqXHR, textStatus, errorThrown) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error al procesar la solicitud.'
    });
  });
}

function getHistorialReferenciaContrareferencia(nro_doc_ident){
  $.ajax({
    url: `/modal_historial_rc_dni/${nro_doc_ident}`,
    type: 'GET',
    beforeSend: function() {
      Swal.fire({
        title: 'Historial de Referencias y Contrareferencias',
        text: 'Cargando datos, Por favor espera.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }
  }).done(function(response) {
      $("#divRC1").html(response);
      Swal.close();
  }).fail(function(jqXHR, textStatus, errorThrown) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error al procesar la solicitud.'
    });
  });
}

function getHistorialEnfermeria(idAsegurado){
  $.ajax({
    url: `/modal_historial_enfermeria/${idAsegurado}`,
    type: 'GET',
    beforeSend: function() {
      Swal.fire({
        title: 'Historial de Enfermería',
        text: 'Cargando datos, Por favor espera.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }
  }).done(function(response) {
      $("#divHistorialEnfermeria").html(response);
      Swal.close();
  }).fail(function(jqXHR, textStatus, errorThrown) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error al procesar la solicitud.'
    });
  });
}
