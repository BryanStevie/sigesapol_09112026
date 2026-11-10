<?php ini_set('memory_limit', '-1');
set_time_limit(0); ?>
@extends('frontend.layouts.master3')
<link rel="stylesheet" href="{{ asset('enfermeria/consulta_externa/css/registrarNotaIngreso.css') }}">
@section('renderjs')
     <style>
        /**************************** MARCA DE AGUA - HISTORIAL DE ATENCIONES ***********************/
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
        .swal-confidencialidad-enfermeria-backdrop.swal2-backdrop-show {
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            background: rgba(0, 0, 0, 0.4) !important;
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
        window.signosVitales = {!! json_encode($signosVitales) !!};
        window.prestacion = {!! json_encode($prestacion) !!};
        window.enfermeria = {!! json_encode($enfermeria) !!};
        window.procedimientos = {!! json_encode($procedimientos) !!};
    </script>
    <script src="{{ asset('enfermeria/consulta_externa/js/editarNotaIngreso.js') }}"></script>
    <script src="{{ asset('enfermeria/consulta_externa/js/handlerHistorial.js') }}"></script>
    <script>
        var texto_marca_agua_hist_enfermeria = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';

        function generar_marca_agua_hist_enfermeria() {
            var overlay = document.getElementById('marca_agua_overlay_hist_enfermeria');
            if (!overlay) return;

            overlay.innerHTML = '';

            var W = overlay.offsetWidth  || 800;
            var H = overlay.offsetHeight || 600;

            var cols = Math.ceil(W / 220) + 1;
            var rows = Math.ceil(H / 100) + 1;

            for (var r = 0; r < rows; r++) {
                for (var c = 0; c < cols; c++) {
                    var span = document.createElement('span');
                    span.textContent = texto_marca_agua_hist_enfermeria;
                    span.style.left = (c * 220 - 40) + 'px';
                    span.style.top  = (r * 100 - 20) + 'px';
                    overlay.appendChild(span);
                }
            }
        }

        function mostrarAvisoConfidencialidadEnfermeria() {
            Swal.fire({
                icon: "warning",
                width: 700,
                html: `<div style="padding: 0px 20px 20px 20px">
                        <p style="font-size: 16px"><b>La historia clínica contiene datos sensibles y que su protección es esencial para el bienestar del paciente, así como para el cumplimiento de la normativa legal de privacidad y protección de datos</b></p><br>
                        <span style="font-size: 14px; color: #d9534f"><b>Ley N.° 26659</b></span><br>
                        <span style="font-size: 14px; color: #d9534f"><b>Ley de Protección de la Historia Clínica</b></span>
                      </div>`,
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                customClass: {
                    container: 'swal-confidencialidad-enfermeria-backdrop'
                }
            });

            $.ajax({
                url: '/auditoria-hc/registrar',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    origen: 'Enfermería CE(HA)',
                    doc_buscado: $('#asegurado_nro_doc_ident').val(),
                    id_tipo_atencion: null
                }
            });
        }

        $(document).ready(function () {
            $('a[href="#tabHistorialAtenciones"]').on('shown.bs.tab', function () {
                if ($('#contentHistorialAtenciones').children().length === 0) {
                    return;
                }
                mostrarAvisoConfidencialidadEnfermeria();
            });

            var resizeMarcaAguaHistEnfermeriaTimer = null;
            $(window).on('resize', function () {
                clearTimeout(resizeMarcaAguaHistEnfermeriaTimer);
                resizeMarcaAguaHistEnfermeriaTimer = setTimeout(function () {
                    if ($('#tabHistorialAtenciones').is(':visible')) generar_marca_agua_hist_enfermeria();
                }, 250);
            });
        });
    </script>
@endsection
@include('frontend.hospitalizacion.enfermeria.nota_ingreso.function_nota_ingreso.parametros.listar_parametros')
<style>
    
    .ui-autocomplete-loading {
        background: white url('{{ asset('images/loader.gif') }}') no-repeat right center !important; 
    }
    .ui-autocomplete {
        font-family: 'Arial', sans-serif; 
        font-size: 14px;
        width: 70%;
    }
    .ui-menu-item {
        font-family: 'Arial', sans-serif;
        font-size: 14px;
    }
</style>
@section('content')
<div class="container" style="width:95%">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
              <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                  <ul class="nav nav-tabs" id="tabs">
                    <li class="active"><a href="#tabNuevaAtencion" data-toggle="tab" style="font-size:12px">NUEVA ATENCION</a></li>
                    <li><a href="#tabHistorialAtenciones" data-toggle="tab" style="font-size:12px">HIST. ATENCIONES</a></li>
                    <li><a href="#tabHistorialLaboratorio" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE LABORATORIO</a></li>
                    <li><a href="#tabHistorialRadiologia" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE RADIOLOGIA</a></li>
                    <li><a href="#tabHistorialnterconsulta" data-toggle="tab" style="font-size:12px">HIST. INTERCONSULTA</a></li>
                    <li><a href="#tabHistorialReferencia" data-toggle="tab" style="font-size:12px">HIST. REFERENCIAS / CONTRAREFERENCIAS</a></li>
                    <li><a href="#tabHistorialEnfermeria" data-toggle="tab" style="font-size:12px">HIST. ENFERMERIA</a></li>
                  </ul>
                </div>
                <div class="panel-body">
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="tabNuevaAtencion">
                      <div class="panel panel-primary filterable">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <h3 class="panel-title" style="padding-top: 5px;">EDITAR NOTA DE INGRESO DE ENFERMERÍA 123</h3> 
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form method="post" action="{{ route('registrar_nota_ingreso_emergencia')}}" id="frmNotaIngreso" name="frmNotaIngreso" autocomplete="off">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="enfermeria_id" id="enfermeria_id" value="{{$enfermeria->enfermeria_id}}">
                                <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$enfermeria->prestacion_id}}">
                                <input type="hidden" name="firmaPrestacion" id="firmaPrestacion" value="{{ isset($prestacion->fecha_firma) ? 1 : 0 }}">
                                <input type="hidden" name="cita_id" id="cita_id" value="{{$enfermeria->cita_id}}">
                                <input type="hidden" name="asegurado_edad" id="asegurado_edad" value="{{$enfermeria->asegurado_edad}}">
                                <input type="hidden" name="asegurado_id" id="asegurado_id" value="{{$enfermeria->asegurado_id}}">
                                <input type="hidden" name="asegurado_sexo_abreviatura" id="asegurado_sexo_abreviatura" value="{{$enfermeria->asegurado_sexo}}">
                                <input type="hidden" name="establecimiento_nivel" id="establecimiento_nivel" value="{{$enfermeria->establecimiento_nivel}}">
                                <input type="hidden" name="antecedentes_enfermeria_id" id="antecedentes_enfermeria_id" value="{{$enfermeria->antecedentes_enfermeria_id}}">
                                <input type="hidden" name="consultorio_id" id="consultorio_id" value="{{$enfermeria->consultorio_id}}">
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>DATOS DE LA ATENCION</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="tipo_atencion_nombre">TIPO DE LA ATENCION</label>
                                                  <input class="form-control input-sm" name="tipo_atencion_nombre" id="tipo_atencion_nombre" value="{{$enfermeria->tipo_atencion_nombre}}" readonly />
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="consultorio">SERVICIO</label>
                                                  <input class="form-control input-sm" name="consultorio" id="consultorio" value="{{$enfermeria->consultorio}}" readonly />
                                              </div> 
                                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                  <label class="control-label" for="establecimiento">IPRESS DONDE SE ATENDIO</label>
                                                  <input class="form-control input-sm" name="establecimiento" id="establecimiento" value="{{$enfermeria->establecimiento}}" readonly />
                                              </div> 
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="fecha_atencion">FECHA DE ULTIMA ATENCION</label>
                                                  <input class="form-control input-sm" name="fecha_atencion" id="fecha_atencion" value="{{$enfermeria->fecha_atencion_formatter}}" readonly />
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                <label class="control-label" for="nro_historia">NUMERO DE HISTORIA</label>
                                                <input class="form-control input-sm" name="nro_historia" id="nro_historia" value="{{$enfermeria->nro_historia}}" readonly />
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="fecha_atencion_enfermeria">FECHA ATENCIÓN ENFERMERIA</label>
                                                  <input class="form-control input-sm" name="fecha_atencion_enfermeria" id="fecha_atencion_enfermeria" readonly style="background-color: #fff; border-color: #b2b2b2;" value="{{$enfermeria->fecha_atencion_enfermeria_formatter}}">
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="hora_atencion_enfermeria">HORA ATENCIÓN ENFERMERIA</label>
                                                  <input type="time" class="form-control input-sm" name="hora_atencion_enfermeria" id="hora_atencion_enfermeria" value="{{$enfermeria->hora_atencion_enfermeria_formatter}}" step="60">
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="panel panel-info panel-bordered panel-sm" id="formDataPaciente">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>INFORMACIÓN DEL PACIENTE</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_tipo_doc_identidad">TIPO DE DOC DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="asegurado_tipo_doc_identidad" id="asegurado_tipo_doc_identidad" value="{{$enfermeria->asegurado_tipo_documento_identidad}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_nro_doc_ident">DOCUMENTO DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="asegurado_nro_doc_ident" id="asegurado_nro_doc_ident" value="{{$enfermeria->asegurado_nro_doc_ident}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_sexo">SEXO</label>
                                                  <input class="form-control input-sm" name="asegurado_sexo" id="asegurado_sexo" value="{{$enfermeria->asegurado_sexo}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_paterno">APELLIDO PATERNO</label>
                                                  <input class="form-control input-sm" name="asegurado_paterno" id="asegurado_paterno" value="{{$enfermeria->asegurado_paterno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_materno">APELLIDO MATERNO</label>
                                                  <input class="form-control input-sm" name="asegurado_materno" id="asegurado_materno" value="{{$enfermeria->asegurado_materno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_nombre">NOMBRES</label>
                                                  <input class="form-control input-sm" name="asegurado_nombre" id="asegurado_nombre" value="{{$enfermeria->asegurado_nombre}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_fecha_nacimiento">FECHA DE NACIMIENTO</label>
                                                  <input class="form-control input-sm" name="asegurado_fecha_nacimiento" id="asegurado_fecha_nacimiento" value="{{$enfermeria->asegurado_fecha_nacimiento}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_tipo_beneficiario">PARENTESCO</label>
                                                  <input class="form-control input-sm" name="asegurado_tipo_beneficiario" id="asegurado_tipo_beneficiario" value="{{$enfermeria->asegurado_tipo_beneficiario}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_grado">GRADO</label>
                                                  <input class="form-control input-sm" name="asegurado_grado" id="asegurado_grado" value="{{$enfermeria->grado}}" readonly>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>INFORMACIÓN DEL RESPONSABLE DE ENFERMERIA</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="tipo_documento_enfermero">TIPO DE DOC DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="tipo_documento_enfermero" id="tipo_documento_enfermero" value="{{$enfermeria->enfermero_tipo_documento_identidad}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="nro_documento_enfermero">DOCUMENTO DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="nro_documento_enfermero" id="nro_documento_enfermero" value="{{$enfermeria->enfermero_nro_doc_ident}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="paterno_enfermero">APELLIDO PATERNO</label>
                                                  <input class="form-control input-sm" name="paterno_enfermero" id="paterno_enfermero" value="{{$enfermeria->enfermero_paterno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="materno_enfermero">APELLIDO MATERNO</label>
                                                  <input class="form-control input-sm" name="materno_enfermero" id="materno_enfermero" value="{{$enfermeria->enfermero_materno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="nombre_enfermero">NOMBRES</label>
                                                  <input class="form-control input-sm" name="nombre_enfermero" id="nombre_enfermero" value="{{$enfermeria->enfemero_nombre}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="especialidad_enfermero">ESPECIALIDAD</label>
                                                  <input class="form-control input-sm" name="especialidad_enfermero" id="especialidad_enfermero" value="{{$enfermeria->enfemero_especialidad}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="profesion_enfermero">PROFESION</label>
                                                  <input class="form-control input-sm" name="profesion_enfermero" id="profesion_enfermero" value="{{$enfermeria->enfemero_profesion}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="nro_colegiatura_enfermero">N° COLEGIATURA</label>
                                                  <input class="form-control input-sm" name="nro_colegiatura_enfermero" id="nro_colegiatura_enfermero" value="{{$enfermeria->enfemero_colegiatura}}" readonly>
                                              </div>
                                          </div>          
                                      </div>
                                  </div>

                                  <div class="panel panel-info panel-bordered panel-sm" id="formDataMedicoAtencion">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>INFORMACION DEL RESPONSABLE DE ATENCION</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_tipo_documento">TIPO DE DOC DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="medico_tipo_documento" id="medico_tipo_documento" value="{{$enfermeria->medico_tipo_documento_identidad}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_nro_doc_ident">N° DE DOCUMENTO DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="medico_nro_doc_ident" id="medico_nro_doc_ident" value="{{$enfermeria->medico_nro_doc_ident}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_paterno">APELLIDO PATERNO</label>
                                                  <input class="form-control input-sm" name="medico_paterno" id="medico_paterno" value="{{$enfermeria->medico_paterno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_materno">APELLIDO MATERNO</label>
                                                  <input class="form-control input-sm" name="medico_materno" id="medico_materno" value="{{$enfermeria->medico_materno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_nombre">NOMBRES</label>
                                                  <input class="form-control input-sm" name="medico_nombre" id="medico_nombre" value="{{$enfermeria->medico_nombre}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_colegiatura">N° COLEGIATURA</label>
                                                  <input class="form-control input-sm" name="medico_colegiatura" id="medico_colegiatura" value="{{$enfermeria->medico_colegiatura}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_rne">N° DE RNE</label>
                                                  <input class="form-control input-sm" name="medico_rne" id="medico_rne" value="{{$enfermeria->medico_rne}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_profesion">PROFESION</label>
                                                  <input class="form-control input-sm" name="medico_profesion" id="medico_profesion" value="{{$enfermeria->medico_profesion}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_especialidad">ESPECIALIDAD</label>
                                                  <input class="form-control input-sm" name="medico_especialidad" id="medico_especialidad" value="{{$enfermeria->medico_especialidad}}" readonly>
                                              </div>
                                          </div>          
                                      </div>
                                  </div>

                                  <div class="panel panel-info panel-bordered panel-sm" id="tableReceta">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>INFORMACION DE RECETA</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <table id="tableRecetas" class="table table-striped" style="font-size: 11px; width: 100%">
                                              <thead>
                                                  <tr>
                                                      <th>N</th>
                                                      <th>PRODUCTO</th>
                                                      <th>UN MED</th>
                                                      <th>CANT</th>
                                                      <th>DIAS</th>
                                                      <th>VIA ADM</th>
                                                      <th>POSOLOGIA</th>
                                                  </tr>
                                              </thead>
                                          </table>
                                      </div>
                                  </div>

                                  <div class="panel with-nav-tabs panel-default" id="sectionTabs">
                                      <div class="panel-heading">
                                          <ul class="nav nav-tabs" role="tablist" id="tabsRegistrarNotaIngreso">
                                              <li role="presentation" class="active">
                                                  <a href="#tabSignosVitales" aria-controls="tabSignosVitales" role="tab" data-toggle="tab">SIGNOS VITALES</a>
                                              </li>
                                              <li role="presentation">
                                                  <a href="#tabAntecedentes" aria-controls="tabAntecedentes" role="tab" data-toggle="tab">ANTECEDENTES</a>
                                              </li>
                                              <li role="presentation">
                                                  <a href="#tabCpmsCpt" aria-controls="tabCpmsCpt" role="tab" data-toggle="tab">CPMS/CPT</a>
                                              </li>
                                              <li role="presentation">
                                                  <a href="#tabObservaciones" aria-controls="tabObservaciones" role="tab" data-toggle="tab">OBSERVACIONES</a>
                                              </li>
                                          </ul>
                                      </div>
                                      <div class="panel-body">
                                          <div class="tab-content">
                                              <div role="tabpanel" class="tab-pane active" id="tabSignosVitales">
                                                  <div class="row">
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label">PRESION ARTERIAL</label>
                                                          <div class="input-group">
                                                              <input class="form-control input-sm" name="presion_arterial_1" id="presion_arterial_1" maxlength="10" data-id="">
                                                              <span class="input-group-addon separate-addon">/</span>
                                                              <input class="form-control input-sm" name="presion_arterial_2" id="presion_arterial_2" maxlength="10" data-id="">
                                                          </div>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="pulso_frec_cardiaca">PULSO/FREC. CARDIACA</label>
                                                          <input type="text" class="form-control input-sm" name="pulso_frec_cardiaca" id="pulso_frec_cardiaca" maxlength="10" data-id="">
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="frec_respiratorio">FREC. RESPIRATORIA (/MIN)</label>
                                                          <input type="text" class="form-control input-sm" name="frec_respiratorio" id="frec_respiratorio" maxlength="10" data-id="">
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="fi02">FIO2 (%)</label>
                                                          <input type="text" class="form-control input-sm" name="fi02" id="fi02" maxlength="10" data-id="">
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="sa02">SA02 (%)</label>
                                                          <input type="text" class="form-control input-sm" name="sa02" id="sa02" maxlength="10" data-id="">
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="temperatura">TEMPERATURA (°C)</label>
                                                          <input type="text" class="form-control input-sm" name="temperatura" id="temperatura" maxlength="10" data-id="">
                                                      </div>
                                                  </div>
                                                  <br>
                                                  <div class="row">
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="talla">TALLA (CM)</label>
                                                          <input type="text" class="form-control input-sm" name="talla" id="talla" maxlength="10" data-id="">
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="peso">PESO (KG)</label>
                                                          <input type="text" class="form-control input-sm" name="peso" id="peso" maxlength="10" data-id="">
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="imc">IMC</label>
                                                          <input class="form-control input-sm" name="imc" id="imc" readonly data-id="">
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="peso_ideal">PESO IDEAL</label>
                                                          <input class="form-control input-sm" name="peso_ideal" id="peso_ideal" readonly data-id="">
                                                      </div>
                                                  </div>
                                              </div>
                                              <div role="tabpanel" class="tab-pane" id="tabAntecedentes">
                                                  <div class="panel panel-info panel-bordered panel-sm">
                                                      <div class="panel-body">
                                                          <div class="row">
                                                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                  <p class="label-antecedentes">ANTECEDENTES</p>
                                                                  <p id="antecedente" class="antecedentes-content">{{$enfermeria->antecedente}}</p>
                                                              </div>
                                                          </div>
                                                          <hr>
                                                          <div class="row">
                                                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                  <p class="label-antecedentes">ANTECEDENTES FAMILIARES</p>
                                                                  <p id="antecedente_familiar" class="antecedentes-content">{{$enfermeria->antecedente_familiar}}</p>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="control-label">HIPERTENSION ARTERIAL</label><br>
                                                          <label class="control-label" class="radio-inline">
                                                              <input type="radio" name="hipertensionArterial" id="hipertensionArterial_01" value="1" {{intval($enfermeria->id_hipertension) === 1 ? 'checked' : ''}}> SI
                                                          </label>
                                                          <label class="control-label" class="radio-inline">
                                                              <input type="radio" name="hipertensionArterial" id="hipertensionArterial_02" value="0" {{intval($enfermeria->id_hipertension) === 0 ? 'checked' : ''}}> NO
                                                          </label>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="control-label" for="tratamiento_hipertension">TRATAMIENTO (HIPERTENSION)</label>
                                                          <input class="form-control input-sm" name="tratamiento_hipertension" id="tratamiento_hipertension" value="{{$enfermeria->descripcion_hipertension}}">
                                                      </div>

                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="control-label">DIABETES MELLITUS</label><br>
                                                          <label class="control-label" class="radio-inline">
                                                              <input type="radio" name="diabetes_mellitus" id="diabetes_mellitus_01" value="1" {{intval($enfermeria->id_diabetes_millitus) === 1 ? 'checked' : ''}}> SI
                                                          </label>
                                                          <label class="control-label" class="radio-inline">
                                                              <input type="radio" name="diabetes_mellitus" id="diabetes_mellitus_02" value="0" {{intval($enfermeria->id_diabetes_millitus) === 0 ? 'checked' : ''}}> NO
                                                          </label>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="control-label" for="tratamiento_diabetes">TRATAMIENTO (DIABETES)</label>
                                                          <input class="form-control input-sm" name="tratamiento_diabetes" id="tratamiento_diabetes" value="{{$enfermeria->descripcion_diabetes_millitus}}">
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="control-label">ALERGIA</label><br>
                                                          <label class="control-label" class="radio-inline">
                                                              <input type="radio" name="alergia" id="alergia_01" value="1" {{intval($enfermeria->id_alergia) === 1 ? 'checked' : ''}}> SI
                                                          </label>
                                                          <label class="control-label" class="radio-inline">
                                                              <input type="radio" name="alergia" id="alergia_02" value="0" {{intval($enfermeria->id_alergia) === 0 ? 'checked' : ''}}> NO
                                                          </label>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="control-label" for="detalle_alergia">DETALLE (ALERGIA)</label>
                                                          <input class="form-control input-sm" name="detalle_alergia" id="detalle_alergia" value="{{$enfermeria->descripcion_alergia}}">
                                                      </div>

                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="control-label">HEMODIALISIS</label><br>
                                                          <label class="control-label" class="radio-inline">
                                                              <input type="radio" name="hemodialisis" id="hemodialisis_01" value="1" {{intval($enfermeria->id_hemodialisis) === 1 ? 'checked' : ''}}> SI
                                                          </label>
                                                          <label class="control-label" class="radio-inline">
                                                              <input type="radio" name="hemodialisis" id="hemodialisis_02" value="0" {{intval($enfermeria->id_hemodialisis) === 0 ? 'checked' : ''}}> NO
                                                          </label>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="control-label" for="detalle_hemodialisis">DETALLE (HEMODIALISIS)</label>
                                                          <input class="form-control input-sm" name="detalle_hemodialisis" id="detalle_hemodialisis" value="{{$enfermeria->descripcion_hemodialisis}}">
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                                          <label class="control-label" for="cardiologico">CARDIOLOGICO</label>
                                                          <select name="cardiologico[]" id="cardiologico" class="js-example-basic-multiple"  style="width: 100%;" multiple="multiple">
                                                          </select>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                                          <label class="control-label" for="observacion">OBSERVACION</label>
                                                          <input class="form-control input-sm" name="observacion" id="observacion" maxlength="149" value="{{$enfermeria->descripcion_observacion}}">
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                                          <label class="control-label" for="otros_antecedentes">OTROS ANTECEDENTES</label>
                                                          <input class="form-control input-sm" name="otros_antecedentes" id="otros_antecedentes" maxlength="149" value="{{$enfermeria->descripcion_otro}}">
                                                      </div>
                                                  </div>
                                              </div>
                                              <div role="tabpanel" class="tab-pane" id="tabCpmsCpt">
                                                  <div class="panel panel-info panel-bordered panel-sm">
                                                      <div class="panel-heading">
                                                          <div class="panel-title text-center">
                                                              <b>DIAGNOSTICOS DE LA ATENCION</b>
                                                          </div>    
                                                      </div>
                                                      <div class="panel-body">
                                                          <table class="table table-striped" style="font-size: 11px; width: 100%" id="tableDiagnosticos">
                                                              <thead>
                                                                  <tr>
                                                                      <th width="10%">CÓDIGO</th>
                                                                      <th width="50%">NOMBRE</th>
                                                                      <th width="20%">TIPO</th>
                                                                      <th width="20%">CLASIFICACIÓN</th>
                                                                  </tr>
                                                              </thead>
                                                              <tbody></tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                          <button type="button" class="btn btn-primary btn-sm" id="btnAddProcedure">
                                                              <i class="fa fa-plus"></i> AGREGAR PROCEDIMIENTO
                                                          </button>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                          <table class="table table-striped" style="font-size: 11px" id="tableProcedures">
                                                              <thead>
                                                                  <tr id="tableProceduresHeader">
                                                                      <th width="50%">PROCEDIMIENTO</th>
                                                                      <th width="10%">CANTIDAD</th>
                                                                      <th width="35%">DIAGNOSTICO</th>
                                                                      <th width="5%">ELIMINAR</th>
                                                                  </tr>
                                                              </thead>
                                                              <tbody></tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div role="tabpanel" class="tab-pane" id="tabObservaciones">
                                                  <label class="control-label" for="observaciones">DETALLE DE OBSERVACIONES</label>
                                                  <textarea class="form-control" id="observaciones" name="observaciones" rows="5" maxlength="">{{$enfermeria->observacion_enfermeria}}</textarea>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="panel-footer" style="padding: 20px 15px 30px 15px">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <a href="/enfermeria/consulta-externa" class="btn btn-danger btn-block"><i class="fa fa-ban"></i> CANCELAR</a>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <button 
                                        type="button" 
                                        class="btn btn-primary btn-block" 
                                        id="btnSubmit" 
                                    ><i class="fa fa-floppy-o"></i> GUARDAR
                                    </button>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane marca_agua_container" id="tabHistorialAtenciones" style="position:relative;">
                      <div class="marca_agua_overlay" id="marca_agua_overlay_hist_enfermeria"></div>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE ATENCIONES MEDICAS</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='contentHistorialAtenciones'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialLaboratorio">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE EXAMENES DE LABORATORIO</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divLaboratorio'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialRadiologia">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE EXAMENES DE IMAGENOLOGIA</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divRadiologico'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialnterconsulta">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE INTERCONSULTA</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divInterconsultaH'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialReferencia">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE REFERENCIAS / CONTRAREFERENCIAS</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divRC1'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialEnfermeria">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE ENFERMERIA</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divHistorialEnfermeria'></div>
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

	@include('frontend.includes.footer')
@endsection