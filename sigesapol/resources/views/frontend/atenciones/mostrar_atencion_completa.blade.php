@section('renderjs')

{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
@endsection
@section('content')
<style>
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
    .container:has(.marca_agua_container) {
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
<script>
  $(document).ready(function() {
    $('#evolucion').hide();
    $('#evaluacion').hide();
    @if(isset($prestacion))
        @if($prestacion->id_tipo_atencion == 2)
            if (typeof datatableEvaluaciones === "function") {
                datatableEvaluaciones();
                $('#evaluacion').show();
            }
        @elseif($prestacion->id_tipo_atencion == 3)
            if (typeof datatableEvoluciones === "function") {
                datatableEvoluciones();
                $('#evolucion').show();
            }
        @endif
    @endif 
  });
  function datatableEvoluciones() {
    if ($.fn.DataTable.isDataTable('#tblPrestacionEvoluciones')) {
      $('#tblPrestacionEvoluciones').DataTable().clear().destroy(); // Limpiar y destruir
    }
    var oTable1 = $('#tblPrestacionEvoluciones').dataTable({
      "bServerSide": true,
      "sAjaxSource": "/listar_evolucion_hospi_ajax",
      "bProcessing": true,
      "sPaginationType": "full_numbers",
      //"paging":false,
      "bFilter": false,
      "bSort": false,
      "info": true,
      //"responsive": true,
      "language": {
        "url": "/js/Spanish.json"
      },
      "autoWidth": false,
      "bLengthChange": true,
      "destroy": true,
      "lengthMenu": [
        [10, 50, 100, 200, 60000],
        [10, 50, 100, 200, "Todos"]
      ],
      "aoColumns": [{}, ],
      "dom": '<"top">rt<"bottom"flpi><"clear">',
      "fnDrawCallback": function(json) {
        $('[data-toggle="tooltip"]').tooltip();
      },

      "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

        var sEcho = aoData[0].value;
        var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
        var iCantMostrar = aoData[4].value;

        var fecha_ini = "<?php echo $fechaDesde; ?>";
        var fecha_fin = "<?php echo $fechaHasta; ?>";
        var estado = 1;
        var id_servicio = 0;
        var id_paciente = "<?php echo (isset($prestacion->id_ext) && $prestacion->id_ext !== '') ? $prestacion->id_ext : '1'; ?>";
        // var id_medico = $('#id_medico').val();
        var id_atencione = 3;
        var _token = $('#_token').val();
        oSettings.jqXHR = $.ajax({
          "dataType": 'json',
          //"contentType": "application/json; charset=utf-8",
          "type": "POST",
          "url": sSource,
          "data": {
            NumeroPagina: iNroPagina,
            NumeroRegistros: iCantMostrar,
            fecha_ini: fecha_ini,
            fecha_fin: fecha_fin,
            estado: estado,
            id_paciente: id_paciente,
            // id_medico: id_medico,
            id_atencione: id_atencione,
            _token: _token
          },
          "success": function(result) {
            fnCallback(result);
          },
          "error": function(msg, textStatus, errorThrown) {
            //location.href="login";
          }
        });
      },

      "aoColumnDefs": [{
          "mRender": function(data, type, row) {
            var id = "";
            if (row.id != null) id = row.id;
            return id;
          },
          "bSortable": false,
          "aTargets": [0]
        },
        {
          "mRender": function(data, type, row) {
            var fecha_evaluacion = "";
            if (row.fecha_evaluacion != null) fecha_evaluacion = row.fecha_evaluacion;
            return fecha_evaluacion;
          },
          "bSortable": false,
          "aTargets": [1]
        },
        {
          "mRender": function(data, type, row) {
            var hora_evaluacion = "";
            if (row.hora_evaluacion != null) hora_evaluacion = row.hora_evaluacion;
            return hora_evaluacion;
          },
          "bSortable": false,
          "aTargets": [2]
        },
        {
          "mRender": function(data, type, row) {
            var servicio = "";
            if (row.servicio != null) servicio = row.servicio;
            return servicio;
          },
          "bSortable": false,
          "aTargets": [3]
        },
        {
          "mRender": function(data, type, row) {
            var cama = "";
            if (row.cama != null) cama = row.cama;
            return cama;
          },
          "bSortable": false,
          "aTargets": [4]
        },
        {
          "mRender": function(data, type, row) {
            var medico = "";
            if (row.apepaterno_profesional != null) medico += row.apepaterno_profesional;
            if (row.apematerno_profesional != null) medico += " " + row.apematerno_profesional;
            if (row.nombre_profesional != null) medico += " ," + row.nombre_profesional;
            return medico;
          },
          "bSortable": false,
          "aTargets": [5]
        },
        {
          "mRender": function(data, type, row) {
            var html = "";
            if (row.id_estado_reg == 1) {
              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Evolución">';
              html += '<a href="javascript:void(0)" onClick="ver_det_evolucion(\'' + row.id+ '\', \'' + row.id_tipo_atencion + '\')" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';                                    
              // html +=
              //   '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evaluacion</b>">';
              // html += '<a href="/ver_evaluacion_hospitalizacion/<?php echo Auth::user()->id ?>/' + row.id +
              //   '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
            } else {
              html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></a></button>';
            }

            return html;
          },
          "bSortable": false,
          "aTargets": [6],
          "className": "text-center",
        },
      ]
    });
  }
  function datatableEvaluaciones() {
    
    var oTable1 = $('#tblPrestacionEvaluaciones').dataTable({
      "bServerSide": true,
      "sAjaxSource": "/listar_evaluacion_ajax",
      "bProcessing": true,
      "sPaginationType": "full_numbers",
      //"paging":false,
      "bFilter": false,
      "bSort": false,
      "info": true,
      //"responsive": true,
      "language": {
        "url": "/js/Spanish.json"
      },
      "autoWidth": false,
      "bLengthChange": true,
      "destroy": true,
      "lengthMenu": [
        [10, 50, 100, 200, 60000],
        [10, 50, 100, 200, "Todos"]
      ],
      "aoColumns": [{}, ],
      "dom": '<"top">rt<"bottom"flpi><"clear">',
      "fnDrawCallback": function(json) {
        $('[data-toggle="tooltip"]').tooltip();
      },

      "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

        var sEcho = aoData[0].value;
        var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
        var iCantMostrar = aoData[4].value;

        var fecha_ini = $('#fecha_ini').val();
        var fecha_fin = $('#fecha_fin').val();
        var dni_beneficiario = '';
        var nombre_beneficiario = '';
        var estado =1;
        var id_servicio = 0;
        var id_paciente = "<?php echo $prestacion->id_ext; ?>";

        var id_medico = "<?php echo $medico; ?>";
        var id_establecimiento = "<?php echo $farmacia->id_establecimiento; ?>";
        var id_atencione = 2;

        var _token = $('#_token').val();
        oSettings.jqXHR = $.ajax({
          "dataType": 'json',
          //"contentType": "application/json; charset=utf-8",
          "type": "POST",
          "url": sSource,
          "data": {
            NumeroPagina: iNroPagina,
            NumeroRegistros: iCantMostrar,
            fecha_ini: fecha_ini,
            fecha_fin: fecha_fin,
            estado: estado,
            id_paciente: id_paciente,
            id_medico: id_medico,
            id_atencione: id_atencione,
            dni_beneficiario: dni_beneficiario,
            _token: _token
          },
          "success": function(result) {
            fnCallback(result);
          },
          "error": function(msg, textStatus, errorThrown) {
            //location.href="login";
          }
        });
      },

      "aoColumnDefs": [{
          "mRender": function(data, type, row) {
            var id = "";
            if (row.id != null) id = row.id;
            return id;
          },
          "bSortable": false,
          "aTargets": [0]
        },
        {
          "mRender": function(data, type, row) {
            var fecha_evaluacion = "";
            if (row.fecha_evaluacion != null) fecha_evaluacion = row.fecha_evaluacion;
            return fecha_evaluacion;
          },
          "bSortable": false,
          "aTargets": [1]
        },
        {
          "mRender": function(data, type, row) {
            var hora_evaluacion = "";
            if (row.hora_evaluacion != null) hora_evaluacion = row.hora_evaluacion;
            return hora_evaluacion;
          },
          "bSortable": false,
          "aTargets": [2]
        },
        {
          "mRender": function(data, type, row) {
            var prioridad = "";
            if (row.prioridad != null) prioridad = row.prioridad;
            return prioridad;
          },
          "bSortable": false,
          "aTargets": [3]
        },
        {
          "mRender": function(data, type, row) {
            var topico = "";
            if (row.topico != null) topico = row.topico;
            return topico;
          },
          "bSortable": false,
          "aTargets": [4]
        },
        {
          "mRender": function(data, type, row) {
            var medico = "";
            if (row.apepaterno_profesional != null) medico += row.apepaterno_profesional;
            if (row.apematerno_profesional != null) medico += " " + row.apematerno_profesional;
            if (row.nombre_profesional != null) medico += " ," + row.nombre_profesional;
            return medico;
          },
          "bSortable": false,
          "aTargets": [5]
        },
        {
          "mRender": function(data, type, row) {
            var html = "";
            if (row.id_estado_reg == 1) {
            
              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Evaluación">';
              html += '<a href="javascript:void(0)" onClick="ver_det_evaluacion(\'' + row.id+ '\', \'' + row.id_tipo_atencion + '\')" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';                                    
            
        
              // html +=
              //   '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evaluacion</b>">';
              // html += '<a href="/ver_evaluacion/<?php echo Auth::user()->id ?>/' + row.id +
              //   '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
            } else {
              html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></a></button>';
            }

            return html;
          },
          "bSortable": false,
          "aTargets": [6],
          "className": "text-center",
        },
      ]
    });
  }
  
  function ver_det_evaluacion(id, id_tipo_atencion){
    $.ajax({
        url: '/mostrar_hc_emergencia_evaluacion_form/' + id+"/"+id_tipo_atencion,
            async: false,
            success: function(result){
            $("#muestra-det-evaluacion").html(result);
            },
            complete: function () {
            $("#myModalEvaluacion").modal();
            }
    });
  }
  function ver_det_evolucion(id, id_tipo_atencion){
    $.ajax({
    url: '/mostrar_hc_hospitalizacion_evolucion_form/' + id+"/"+id_tipo_atencion,
            async: false,
            success: function(result){
            $("#muestra-det-evolucion").html(result);
            },
            complete: function () {
            $("#myModalEvolucion").modal();
            }
    });
  }
  function ver_det_atencion_evaluacion(id){
    $('#nrorecmodalAtencion').text(id);
    $.ajax({
      url: '/mostrar_atencion_hospitalizacion_hc/' + id,
      async: false,
      success: function(result){
        $("#muestra-det-atencion-hospi").html(result);
      },
      complete: function () {
        $("#myModalAtencionHospitalizacion").modal();
        esperarYGenerarMarcaAguaAtencion(60);
      }
    });
  }
  function fn_util_obtieneNroPagina(iDisplayStart, iDisplayLength) {
    var start = (iDisplayStart == 0 ? 1 : iDisplayStart);
    return (start / iDisplayLength) + 1;
  }
  $('.cerrar-modal-hijo-evaluacion').on('click', function() {
      $('#myModalEvaluacion').modal('hide');
      $('body').addClass('modal-open');
      $('#myModalAtencionHC').css('overflow', 'auto');
  });
  $('.cerrar-modal-hijo-evolucion').on('click', function() {
      $('#myModalEvolucion').modal('hide');
      $('body').addClass('modal-open');
      $('#myModalAtencionHC').css('overflow', 'auto');
  });
  $('.cerrar-modal-hijo-atencion').on('click', function() {
      $('#myModalAtencionHospitalizacion').modal('hide');
      $('body').addClass('modal-open');
      $('#myModalAtencionHC').css('overflow', 'auto');
  });

  var texto_marca_agua_atencion = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';
  function generar_marca_agua_atencion() {
    var overlay = document.getElementById('marca_agua_overlay_atencion_hospi');
    if (!overlay) return;
    overlay.innerHTML = '';
    var W = overlay.offsetWidth  || 800;
    var H = overlay.offsetHeight || 600;
    var cols = Math.ceil(W / 220) + 1;
    var rows = Math.ceil(H / 100) + 1;
    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var span = document.createElement('span');
        span.textContent = texto_marca_agua_atencion;
        span.style.left = (c * 220 - 40) + 'px';
        span.style.top  = (r * 100 - 20) + 'px';
        overlay.appendChild(span);
      }
    }
  }

  function esperarYGenerarMarcaAguaAtencion(intentosRestantes) {
    var overlay = document.getElementById('marca_agua_overlay_atencion_hospi');
    if (overlay && overlay.offsetWidth > 0 && overlay.offsetHeight > 0) {
      generar_marca_agua_atencion();
      return;
    }
    if (intentosRestantes > 0) {
      requestAnimationFrame(function () {
        esperarYGenerarMarcaAguaAtencion(intentosRestantes - 1);
      });
    }
  }

  $('#myModalAtencionHospitalizacion').on('show.bs.modal', function () {
    var overlayPrincipal = document.getElementById('marca_agua_overlay_atencion_hc');
    if (overlayPrincipal) overlayPrincipal.style.visibility = 'hidden';
  });

  $('#myModalAtencionHospitalizacion').on('shown.bs.modal', function () {
    generar_marca_agua_atencion();
  });

  $('#myModalAtencionHospitalizacion').on('hide.bs.modal', function () {
    var overlayPrincipal = document.getElementById('marca_agua_overlay_atencion_hc');
    if (overlayPrincipal) overlayPrincipal.style.visibility = 'visible';
  });

  $(window).on('resize', function () {
    if ($('#myModalAtencionHospitalizacion').is(':visible')) {
      generar_marca_agua_atencion();
    }
  });
  ///////////////////////////////////////////////////////////////////// VALIDACIÓN PARA NO PERMITIR SELECCIONAR NI HACER COPIADO DEL MODAL DETALLE
  $('#myModalAtencionHospitalizacion').on('shown.bs.modal', function () {
    document.addEventListener('contextmenu', bloquearCopiadoAtencion);
    document.addEventListener('keydown', bloquearTecladoAtencion);
    document.addEventListener('selectstart', bloquearCopiadoAtencion);
    document.addEventListener('copy', bloquearCopiadoAtencion);
  });

  $('#myModalAtencionHospitalizacion').on('hidden.bs.modal', function () {
    document.removeEventListener('contextmenu', bloquearCopiadoAtencion);
    document.removeEventListener('keydown', bloquearTecladoAtencion);
    document.removeEventListener('selectstart', bloquearCopiadoAtencion);
    document.removeEventListener('copy', bloquearCopiadoAtencion);
  });
  function bloquearCopiadoAtencion(e) {
    var modal = document.getElementById('myModalAtencionHospitalizacion');
    if (modal.contains(e.target)) {
      e.preventDefault();
    }
  }
  function bloquearTecladoAtencion(e) {
    var modal = document.getElementById('myModalAtencionHospitalizacion');
    if (modal.contains(e.target) && e.ctrlKey) {
      e.preventDefault();
    }
  }
  
</script>
<div class="container" style="width:100%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="">
        <div class="panel-body">
          <div class="panel panel-info panel-bordered panel-sm panel-body">
            <div class="col-sm-12 col-md-12">
              <fieldset>
                <div>
                  <div class="form-group">
                    <div class="col-md-3">
                      <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                      <p><?php echo $prestacion->tipo_atencion?></p>
                    </div>
                    <div class="col-md-2">
                      <label for="dni_beneficiario">FECHA DE ATENCION</label>
                       <p><?php echo $prestacion->fecha_atencion?></p>
                    </div>
                    <div class="col-md-2">
                      <label>N° HISTORIA CLINICA</label>
                      <p><?php echo $prestacion->nro_historia?></p>
                    </div>
                    <div class="col-md-3">
                      <label for="dni_beneficiario">SERVICIO</label>
                      <p><?php echo $prestacion->servicio?></p>
                    </div>
                    <div class="col-md-2">
                      <label>CONSULTORIO</label>
                      <p><?php echo $prestacion->consultorio?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <label for="tipo">NOMBRE DE LA IPRESS</label>
                      <p><?php echo $prestacion->establecimiento?></p>
                    </div>
                  </div>
                  @if($prestacion->id_tipo_atencion==3)
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="tipo">PABELLON</label><br/>
                          {{ isset($prestacion->pabellon) ? $prestacion->pabellon : '' }}
                        </div>
                        <div class="col-md-3">
                          <label for="dni_beneficiario">PISO</label><br/>
                          {{ isset($prestacion->piso) ? $prestacion->piso : '' }}
                        </div>
                        <div class="col-md-3">
                          <label>CUARTO</label><br/>
                          {{ isset($prestacion->cuarto) ? $prestacion->cuarto : '' }}
                        </div>
                        <div class="col-md-3">
                          <label>CAMA</label><br/>
                          {{ isset($prestacion->cama) ? $prestacion->cama : '' }}
                        </div>
                      </div>
                    @endif
                </div>
              </fieldset>
            </div></div>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL RESPONSABLE DE ATENCION</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-3">
                        <label for="tipo">TIPO DE DOCUMENTO</label>
                        <p>DNI</p>
                      </div>
                      <div class="col-md-2">
                        <label for="dni_beneficiario">N° DE DOCUMENTO</label>
                        <p><?php echo $prestacion->dni?></p>
                      </div>
                      <div class="col-md-2">
                        <label>N° COLEGIATURA</label>
                        <p><?php echo $prestacion->colegiatura?></p>
                      </div>
                      <div class="col-md-2">
                        <label>N° RNE</label>
                        <p><?php echo $prestacion->rne?></p>
                      </div>
                      <div class="col-md-3">
                        <label>PROFESION</label>
                        <p><?php echo $prestacion->profesion?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-3">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $prestacion->medico_paterno?></p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $prestacion->medico_materno?></p>
                      </div>
                      <div class="col-md-3">
                        <label>NOMBRES</label>
                        <p><?php echo $prestacion->medico_nombre?></p>
                      </div>
                      <div class="col-md-3">
                        <label>ESPECIALIDAD</label>
                        <p><?php echo $prestacion->especializacion?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DIAGNOSTICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="20%">Codigo</th>
                          <th width="60%">Nombre</th>
                          <th width="20%">Tipo</th>
                        </tr>
                      </thead>
                      <tfoot>
                      </tfoot>
                      <tbody>
                        <?php if (!empty($diagnostico) && count($diagnostico) > 0): ?>
                        <?php foreach($diagnostico as $row):
              						$tipo_diagnostico = "";
              						if($row->id_tipo_diagnostico==1)$tipo_diagnostico = "PRESUNTIVO";
              						if($row->id_tipo_diagnostico==2)$tipo_diagnostico = "DEFINITIVO";
              						if($row->id_tipo_diagnostico==3)$tipo_diagnostico = "REITERATIVO";
              					?>
                        <tr>
                          <td><?php echo isset($row->codigo) ? $row->codigo : ''; ?></td>
                          <td><?php echo isset($row->nombre) ? $row->nombre : ''; ?></td>
                          <td><?php echo isset($tipo_diagnostico) ?$tipo_diagnostico : ''; ?></td>
                        </tr>
                        <?php endforeach;?>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PROCEDIMIENTOS MÉDICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="20%">Codigo</th>
                          <th width="60%">Nombre</th>
                          <th width="20%">Cantidad</th>
                        </tr>
                      </thead>
                      <tfoot>
                      </tfoot>
                      <tbody>
                        <?php foreach($procedimiento as $row):?>
                        <tr>
                          <td><?php echo $row->codigo?></td>
                          <td><?php echo $row->nombre?></td>
                          <td><?php echo $row->cantidad?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel with-nav-tabs panel-default">
                  <div class="panel-heading">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab6default" data-toggle="tab" style="font-size:12px">ANTECEDENTES</a></li>
                      <li  ><a href="#tab7default" data-toggle="tab" style="font-size:12px">ANAMNESIS</a></li>
                      <li><a href="#tab8default" data-toggle="tab" style="font-size:12px">EXAMEN FÍSICO</a></li>
                      <li><a href="#tab9default" data-toggle="tab" style="font-size:12px">TRATAMIENTO HABITUAL</a></li>
                      <li><a href="#tab10default" data-toggle="tab" style="font-size:12px">PLAN DE TRABAJO</a></li> 
                      <!-- <li><a href="#tab11default" data-toggle="tab" style="font-size:12px">DIAGNÓSTICOS</a></li>
                      <li><a href="#tab12default" data-toggle="tab" style="font-size:12px">PROCEDIMIENTOS MÉDICOS</a></li> -->
                    </ul>
                  </div>
                  <div class="panel-body">
                    <div class="tab-content">


                      <div class="tab-pane fade in active" id="tab6default">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>ANTECEDENTE</label>
                              <p><?php echo $prestacion->antecedente?></p>
                            </div>
                          </div>
                      </div>

                      <div class="tab-pane" id="tab7default">
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>MOTIVO DE VISITA</label>
                            <p><?php echo $prestacion->motivo_visita?></p>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>TIEMPO DE ENFERMEDAD</label>
                            <p><?php echo $prestacion->tiempo_enfermedad?></p>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>RELATO CRONOLOGICO</label>
                            <p><?php echo $prestacion->anamnesis?></p>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane" id="tab8default">
                        @if($prestacion->id_establecimiento==75)
                          @if($prestacion->id_consultorio == 28 || $prestacion->id_consultorio == 106 || $prestacion->id_consultorio == 107)
                            <div class="panel-body">
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title text-center">
                                    <b>ASPECTOS GENERALES</b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <p><?php 
                                      $aux_aspectos_generales = $prestacion->aspectos_generales;
                                      if (!empty($aux_aspectos_generales)) {
                                        echo str_replace(chr(13), '<br>', $prestacion->aspectos_generales);
                                      }
                                    ?>
                                  </p>
                                </div>
                              </div>
                            </div>
                          @endif
                          @if($prestacion->id_consultorio == 27)
                            <div class="panel-body">
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title text-center">
                                    <b>INFORMACIÓN DE SIGNOS VITALES</b>
                                  </div>
                                </div>
                                <div class="panel-body">

                                  <div class="form-group">
                                    <div class="col-md-2">
                                      <label>TEMPERATURA (°C) &nbsp;</label>
                                      <p><?php echo $prestacion->temperatura?></p>
                                    </div>
                                    <div class="col-md-2">
                                      <label>TALLA (cm) &nbsp;</label>
                                      <p><?php echo $prestacion->talla_cm?></p>
                                    </div>
                                    <div class="col-md-2">
                                      <label>PESO (Kg) &nbsp;</label>
                                      <p><?php echo $prestacion->peso_kg?></p>
                                    </div>
                                    <div class="col-md-2">
                                      <label>IMC &nbsp;</label>
                                      <p>
                                      <?php
                                        if($prestacion->talla_cm!=0):
                                        echo ($prestacion->peso_kg / (($prestacion->talla_cm * $prestacion->talla_cm)*(0.01*0.01)));
                                        endif;
                                      ?>
                                      
                                    </p>
                                    </div>
                                    <div class="col-md-2">
                                      <label>PESO IDEAL &nbsp;</label>
                                      <p><?php echo 45+(($prestacion->peso_kg-150)*3/4)?></p>
                                    </div>
                                    <div class="col-md-2">
                                      <label>Sa02 (%) &nbsp;</label>
                                      <p><?php echo $prestacion->sa02_porcentaje?></p>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-md-2">
                                      <label>TAMAÑO DE CINTURA (cm)</label>
                                      <p><?php echo $prestacion->tamano_cintura_cm?></p>
                                    </div>
                                    <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                      <label>FREC. CARDIACA FETAL(/min)</label>
                                      <p><?php echo $prestacion->frecuencia_cardiaca_fetal?></p>
                                    </div>
                                    <div class="col-md-2">
                                      <label>PRESIÓN ARTERIAL &nbsp;</label>
                                      <div class="row">
                                        <div class="col-xs-5">                          
                                          <p><?php echo $prestacion->presion_arterial_x?></p>
                                        </div>  
                                          <div class="col-xs-2">
                                            <label>/</label> 
                                          </div>
                                        <div class="col-xs-5">
                                          <p><?php echo $prestacion->presion_arterial_y?></p>
                                        </div>  
                                      </div>
                                  </div>
                                    <div class="col-md-2">
                                      <label>FREC.RESPIRATORIA(/min)</label>
                                      <p><?php echo $prestacion->frecuencia_respiratoria?></p>
                                    </div>
                                    <div class="col-md-2">
                                      <label>FREC. CARDIACA (/min) &nbsp;</label>
                                      <p><?php echo $prestacion->frecuencia_cardiaca?></p>
                                    </div>
                                    <!--div class="col-md-2">
                                      <label>FREC. CARDIACA (/min) &nbsp;</label>
                                      <p><?php //echo $prestacion->frecuencia_cardiaca?></p>
                                    </div-->
                                    <div class="col-md-2">
                                      <label>Fi02 (%) &nbsp;</label>
                                      <p><?php echo $prestacion->fi02?></p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          @endif 
                        @endif
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>EXAMEN FISICO</label>
                            <p><?php echo $prestacion->examen_fisico?></p>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>EXAMEN CLINICO</label>
                            <p><?php echo $prestacion->examen_clinico?></p>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>EXAMENES AUXILIARES</label>
                            <p><?php echo $prestacion->examen_auxiliar?></p>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane" id="tab9default">
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>TRATAMIENTO AUXILIAR</label>
                            <p><?php echo $prestacion->tratamiento_auxiliar?></p>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane" id="tab10default">
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>PLAN DE TRABAJO</label>
                            <p><?php echo $prestacion->plan_trabajo?></p>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset id="fsRecetaVale">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PRODUCTOS MEDICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    @if(count($receta)>0)
                    <?php foreach($receta as $rowReceta):
              				if ($rowReceta->estado == 1) { ?>
                        
                        <div style="padding-bottom:0px;line-height:30px;color:#008000;font-weight:bold">
                            LA RECETA HA SIDO DISPENSADA EL <?php echo date("d-m-Y", strtotime($rowReceta->fecha_expedicion)); ?>          
                        </div>
                      <?php                
                      }
                      else{ //estado 2 
                      ?>
                        <div style="padding-bottom:0px;line-height:30px;color:#FF5151;font-weight:bold">
                            LA RECETA AUN NO HA SIDO DISPENSADA
                        </div>    
                      <?php }
                      
              				$model_productos_receta = new \App\Models\Producto_receta;
              				$medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);
              			?>
                    <br />
                    <div style="clear:both"></div>
                    <fieldset>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label class="col-md-2" style="padding-left:0px">RECETA VALE N°</label>
                              <div class="col-md-1">
                                <p><?php echo $rowReceta->nro_receta; ?></p>
                              </div>
                              <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                              <div class="col-md-2">
                                <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                              </div>
                              <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                              <div class="col-md-4" style="padding:0px">
                                <p><?php echo $rowReceta->farmacia; ?></p>
                              </div>
                            </div>
                          </div>
                          <table class="display" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th width="15%">Codigo</th>
                                <th width="30%">Producto farmacéutico o dispositivo médico</th>
                                <th width="15%">Unidad Medida</th>
                                <th width="10%">Tiempo(Dias)</th>
                                <th width="15%">Posologia</th>
                                <th width="15%">Cantidad Prescrita</th>
                              </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <?php foreach($medicamentos as $row):?>
                            <tr>
                              <td><?php echo $row->codigo?></td>
                              <td><?php echo $row->nombre?></td>
                              <td><?php echo $row->abreviatura?></td>
                              <td><?php echo $row->tiempo_periodo?></td>
                              <td><?php echo $row->posologia?></td>
                              <td><?php echo $row->cantidad_prescrita?></td>
                            </tr>
                            <?php endforeach;?>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </fieldset>
                    <?php endforeach;?>
                    @else
                      <p>No cuenta con Recetas registradas.<p>
                    @endif
                  </div>
                </div>
              </fieldset>

              @if(intval($prestacion->id_tipo_atencion) === 1 && isset($prestacion->id_gestacion))
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>SALUD MATERNA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                  <b>GESTACION</b>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-body">
                        <div class="form-group">
                          <div class="col-md-2">
                            <label for="tipo">DUDA</label><br/>
                            {{$prestacion->duda ? "SI" : "NO"}}
                          </div>
                          <div class="col-md-3">
                            <label for="tipo">FECHA ULTIMA REGLA</label><br/>
                            {{$prestacion->fech_ult_regla}}
                          </div>
                          <div class="col-md-2">
                            <label for="tipo">FECHA PARTO</label><br/>
                            {{$prestacion->fech_prob_parto}}
                          </div>
                          <div class="col-md-2">
                            <label for="tipo">SEMANAS</label><br/>
                            {{$prestacion->semanas_gestacion}}
                          </div>
                          <div class="col-md-3">
                            <label for="tipo">NUMERO CONTROLES</label><br/>
                            {{$prestacion->num_controles}}
                          </div>
                        </div>
                    </div>
                  </div>
                  </div>
                </div>
              </fieldset>
              @endif

              @if ((intval($prestacion->id_tipo_atencion) === 2 || intval($prestacion->id_tipo_atencion) === 3) && isset($salud_materna) && (isset($salud_materna->id_gestacion) || isset($salud_materna->id_puerperio)))
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>SALUD MATERNA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    @if (isset($salud_materna->id_gestacion))
                      <b>GESTACION</b>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="tipo">DUDA</label><br/>
                                {{$salud_materna->gestacion_duda ? "SI" : "NO"}}
                              </div>
                              <div class="col-md-3">
                                <label for="tipo">FECHA ULTIMA REGLA</label><br/>
                                {{$salud_materna->gestacion_fech_ult_regla}}
                              </div>
                              <div class="col-md-2">
                                <label for="tipo">FECHA PARTO</label><br/>
                                {{$salud_materna->gestacion_fech_prob_parto}}
                              </div>
                              <div class="col-md-2">
                                <label for="tipo">SEMANAS</label><br/>
                                {{$salud_materna->gestacion_semanas_gestacion}}
                              </div>
                              <div class="col-md-3">
                                <label for="tipo">NUMERO CONTROLES</label><br/>
                                {{$salud_materna->gestacion_num_controles}}
                              </div>
                            </div>
                        </div>
                      </div>
                    @endif

                    @if (isset($salud_materna->id_puerperio))
                      <b>PUERPERA</b>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-2">
                              <label for="tipo">FECHA PARTO</label><br/>
                              {{$salud_materna->puerperio_fecha_parto}}
                            </div>
                            <div class="col-md-2">
                              <label for="tipo">COMPLICACIONES</label><br/>
                              {{$salud_materna->puerperio_complicaciones}}
                            </div>
                            <div class="col-md-3">
                              <label for="tipo">DESCRIPCIÓN</label><br/>
                              {{$salud_materna->puerperio_descripcion}}
                            </div>
                            <div class="col-md-3">
                              <label for="tipo">TIPO PARTO</label><br/>
                              {{$salud_materna->tipo_parto}}
                            </div>
                          </div>
                        </div>
                        @if (isset($natis) && count($natis) > 0)
                          <div style="margin: 0px 20px">
                            <b>NEONATOS</b>
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-body">
                                @foreach ($natis as $nati)
                                  <div class="form-group">
                                    <div class="col-md-3">
                                      <label>TIPO DOCUMENTO</label><br/>
                                      {{$nati->tipo_doc_nati}}
                                    </div>
                                    <div class="col-md-3">
                                      <label>NÚMERO DOCUMENTO</label><br/>
                                      {{$nati->num_doc}}
                                    </div>
                                    <div class="col-md-2">
                                      <label>SEXO</label><br/>
                                      {{$nati->sexo === "M" ? "MASCULINO" : "FEMENINO"}}
                                    </div>
                                    <div class="col-md-2">
                                      <label>TALLA</label><br/>
                                      {{$nati->talla}}
                                    </div>
                                    <div class="col-md-2">
                                      <label>PESO</label><br/>
                                      {{$nati->peso}}
                                    </div>
                                  </div>
                                @endforeach
                              </div>
                            </div>
                          </div>
                        @endif
                      </div>
                    @endif
                  </div>
                </div>
              </fieldset>
              @endif
              
              <fieldset id="fsExamenLab">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>EXAMEN DE LABORATORIO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    @if(count($laboratorio)>0)
                    <br />
                    <div style="clear:both"></div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <table class="display" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th width="20%">Codigo</th>
                                    <th width="60%">Nombre</th>
                                    <th width="20%">Cantidad</th>
                                  </tr>
                                  </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                  <?php foreach($laboratorio as $row):?>
                                  <tr>
                                    <td><?php echo $row->codigo?></td>
                                    <td><?php echo $row->nombre?></td>
                                    <td><?php echo $row->cantidad?></td>
                                  </tr>
                                  <?php endforeach;?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                    <div style="clear:both"></div>
                  </div>
                  @else 
                    <p>No cuenta con Examenes de Laboratorio registradas.<p>
                  @endif
                </div>
              </fieldset>

              <fieldset id="fsExamenRad">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>EXAMEN DE IMAGENOLOGÍA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    @if(count($radiologia)>0)
                    <div style="clear:both"></div>
                    <table class="display" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th width="20%">Codigo</th>
                          <th width="60%">Nombre</th>
                          <th width="20%">Cantidad</th>
                        </tr>
                        </tr>
                      </thead>
                      <tfoot>
                      </tfoot>
                      <tbody>
                        <?php foreach($radiologia as $row):?>
                        <tr>
                          <td><?php echo $row->codigo?></td>
                          <td><?php echo $row->nombre?></td>
                          <td><?php echo $row->cantidad?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                    @else
                      <p>No cuenta con Examenes de Imagenología registradas.<p>
                    @endif
                  </div>
                  <div style="clear:both"></div>
                </div>
              </fieldset>

        <fieldset id="evolucion"> 
          @if(isset($atenciones)) 
          <div class="panel panel-info panel-bordered panel-sm"> 
            <div class="panel-heading">
              <div class="panel-title text-center" style="margin-top:-18px;"><br>
              <b>ATENCION(ES) MÉDICA(S)</b>
              </div>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover">
                  <thead>
                    <tr>
                        <th rowspan="2">#</th>
                        @if (isset($is_fiscalizador))
                        <th rowspan="2">N</th>
                        @endif
                        <th rowspan="2">FECHA Y HORA DE ATENCIÓN</th>
                        <th rowspan="2">ESTABLECIMIENTO</th>
                        <th rowspan="2">TIPO ATENCION</th>
                        <th rowspan="2">SERVICIO A CARGO</th>
                        <th colspan="2">UBICACIÓN FÍSICA DE LA ATENCIÓN</th>
                        <th rowspan="2">MEDICO</th>
                        <th rowspan="2" class="text-center">VER</th>
                    </tr>
                    <tr>
                      <th>SERVICIO</th>
                      <th>CAMA</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = $atenciones != null ? count($atenciones) : 0; ?>
                    @foreach ($atenciones as $rowHorario)
                    <tr>
                      <td>{{$i}}</td>
                      @if (isset($is_fiscalizador))
                      <td>{{$rowHorario->id}}</td>
                      @endif
                      <td>{{$rowHorario->dia}} {{$rowHorario->hora}}</td>
                      <td>{{$rowHorario->establecimiento}}</td>
                      <td>{{$rowHorario->tipo_atencion}}</td>
                      <td>{{$rowHorario->servicio_encargado_atencion}}</td>
                      <td>{{$rowHorario->servicio}}</td>
                      <td>{{$rowHorario->cama}}</td>
                      <td>{{$rowHorario->medico_paterno." ".$rowHorario->medico_materno." ".$rowHorario->medico_nombre}}</td>
                      <td class="text-center"> 
                        @if ($rowHorario->id_estado_reg != 0)
                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                            <a href="javascript:void(0)" onClick="ver_det_atencion_evaluacion({{$rowHorario->id}})" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                          </div>
                        @else
                          <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                        @endif
                      </td>
                    </tr>
                    <?php $i--; ?>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          @endif
        </fieldset>
      
              @if($prestacion->id_tipo_atencion == 3)
              <fieldset id="evolucion">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>Evoluciones</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table id="tblPrestacionEvoluciones" class="table table-bordered">
                      <thead>
                        <tr>
                          <th>N</th>
                          <th>Fecha</th>
                          <th>Hora</th>
                          <th>Servicio</th>
                          <th>Cama</th>
                          <th>Medico</th>
                          <th style="text-align:center">Evolución</th> 
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                   
                  </div>
                </div>
              </fieldset>
              @endif
             @if($prestacion->id_tipo_atencion == 2)
              <fieldset id="evaluacion">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>Evaluaciones</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table id="tblPrestacionEvaluaciones" class="table table-bordered">
                      <thead>
                        <tr>
                          <th>N</th>
                          <th>Fecha</th>
                          <th>Hora</th>
                          <th>Prioridad</th>
                          <th>Area</th>
                          <th>Medico</th>
                          <th style="text-align:center">Evaluación</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                </div>
              </fieldset>
              @endif
              <div class="modal fade" id="myModalEvaluacion" role="dialog">
                <div class="modal-dialog" role="document" style="width: 1400px !important; max-width: 95% !important;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close cerrar-modal-hijo" >&times;</button>
                            <h5 class="modal-title">DETALLE DE LA EVALUACIÓN MEDICA: <span id="nrorecmodal2"></span></h5>
                        </div>
                        <div class="modal-body">
                            <div id="muestra-det-evaluacion"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary cerrar-modal-hijo" >Cerrar</button>
                        </div>
                    </div>
                </div>
              </div>

              <div class="modal fade" id="myModalEvolucion" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close cerrar-modal-hijo">&times;</button>
                            <h5 class="modal-title">DETALLE DE LA EVOLUCIÓN MEDICA: <span id="nrorecmodal2"></span></h5>
                        </div>
                        <div class="modal-body">
                            <div id="muestra-det-evolucion"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary cerrar-modal-hijo">Cerrar</button>
                        </div>
                    </div>
                </div>
              </div>

              <div class="modal fade" id="myModalAtencionHospitalizacion" role="dialog">
                  <div class="modal-dialog modal-lg" style="width: 1500px !important; max-width: 95% !important;" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close cerrar-modal-hijo">&times;</button>
                              <h5 class="modal-title">DETALLE DE ATENCION. <span id="nrorecmodalAtencion"></span></h5>
                          </div>
                          <div class="modal-body marca_agua_container" style="position:relative;">
                            <div class="marca_agua_overlay" id="marca_agua_overlay_atencion_hospi"></div>
                            <div id="muestra-det-atencion-hospi" style="position:relative; z-index:1;"></div>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary cerrar-modal-hijo">Cerrar</button>
                          </div>
                      </div>
                  </div>
              </div>

              
          </div>
      </div>
    </div>
  </div>
</div>
</div>

