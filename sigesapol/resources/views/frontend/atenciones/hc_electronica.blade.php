@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->

<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

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
    .container:has(.marca_agua_container) {
      width: 1200px !important;
      max-width: none !important;
      zoom: 0.6;
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
    .marca_agua_container a[href]:after {
      content: none !important;
    }
    .marca_agua_container .table-responsive {
      overflow: visible !important;
    }
  }

  .swal-afiliacion {
    width: 700px !important;
    padding: 1rem !important;
  }
  .swal-afiliacion .swal2-icon {
    transform: scale(0.5);
    margin: 0;
  }
  .swal-afiliacion .swal2-title {
    font-size: 21px !important;
    margin: 0.3em 0;
  }
  .swal-afiliacion .swal2-html-container {
    font-size: 18px !important;
    margin: 0.3em 0 1em 0;
  }
  .swal-afiliacion .swal2-confirm {
    padding: 6px 70px !important;
    font-size: 13px !important;
  }
  .swal2-backdrop-show {
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    background: rgba(0, 0, 0, 0.4) !important;
  }
</style>

<script>

  $(document).ready(function() {

    $('#tblAtenciones').DataTable({
      "sPaginationType": "full_numbers",
      "language": {"url": "/js/Spanish.json"},
    });
    modalLaboratorio();
    modalRadiologico();
    Swal.fire({
        title: '⚠️ CONFIDENCIALIDAD DE INFORMACIÓN CLÍNICA',
        html: `
          Este sistema contiene información sensible de salud protegida por ley.<br>
          Está prohibido compartir, copiar o divulgar estos datos sin autorización.<br><br>
          <b style="color:red;">Infringir esta norma puede conllevar sanciones legales.</b>
        `,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        confirmButtonText: 'OK',
        customClass: {
          popup: 'swal-afiliacion'
        }
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          url: '/auditoria-hc/registrar',
          type: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            origen: 'Historia Clínica CE',
            doc_buscado: '{{ $paciente->nro_doc_ident }}',
            id_tipo_atencion: null
          }
        });
      }
    });
    generar_marca_agua_pagina_hc();
  });

  $(window).on('resize', function () {
    generar_marca_agua_pagina_hc();
  });

  function generar_marca_agua_pagina_hc() {
    var overlay = document.getElementById('marca_agua_overlay_hc_pagina');
    if (!overlay) return;
    overlay.innerHTML = '';
    var W = overlay.offsetWidth  || 800;
    var H = overlay.offsetHeight || 600;
    var cols = Math.ceil(W / 220) + 1;
    var rows = Math.ceil(H / 100) + 1;
    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var span = document.createElement('span');
        span.textContent = texto_marca_agua_hc;
        span.style.left = (c * 220 - 40) + 'px';
        span.style.top  = (r * 100 - 20) + 'px';
        overlay.appendChild(span);
      }
    }
  }

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

  function ver_det_atencion_hc(id){
    $('#nrorecmodal').text(id);
    $.ajax({
      url: '/mostrar_atencion_completa/' + id,
      async: false,
      success: function(result){
        $("#muestra-det-atencion-hc").html(result);
      },
      complete: function () {
        $("#myModalAtencionHC").modal();
        esperarYGenerarMarcaAguaHc(60);
      }
    });
  }

  var texto_marca_agua_hc = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';
  function generar_marca_agua_hc() {
    var overlay = document.getElementById('marca_agua_overlay_atencion_hc');
    if (!overlay) return;
    overlay.innerHTML = '';
    var W = overlay.offsetWidth  || 800;
    var H = overlay.offsetHeight || 600;
    var cols = Math.ceil(W / 220) + 1;
    var rows = Math.ceil(H / 100) + 1;
    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var span = document.createElement('span');
        span.textContent = texto_marca_agua_hc;
        span.style.left = (c * 220 - 40) + 'px';
        span.style.top  = (r * 100 - 20) + 'px';
        overlay.appendChild(span);
      }
    }
  }

  function esperarYGenerarMarcaAguaHc(intentosRestantes) {
    var overlay = document.getElementById('marca_agua_overlay_atencion_hc');
    if (overlay && overlay.offsetWidth > 0 && overlay.offsetHeight > 0) {
      generar_marca_agua_hc();
      return;
    }
    if (intentosRestantes > 0) {
      requestAnimationFrame(function () {
        esperarYGenerarMarcaAguaHc(intentosRestantes - 1);
      });
    }
  }

  $('#myModalAtencionHC').on('shown.bs.modal', function () {
    generar_marca_agua_hc();
  });

  $(window).on('resize', function () {
    if ($('#myModalAtencionHC').is(':visible')) {
      generar_marca_agua_hc();
    }
  });
  
  function modalLaboratorio(){
    var dni_beneficiario = $("#dnibeneficiario").val();
    $.ajax({
        url: "../modal_historial_laboratorio/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divLaboratorio").html(result);
            $('#tablemodalm1').DataTable({
              "sPaginationType": "full_numbers",
              
              "language": {"url": "/js/Spanish.json"},
            });
        }
    });
  }
  function modalRadiologico(){
    var dni_beneficiario = $("#dnibeneficiario").val();
    $.ajax({
        url: "../modal_historial_radiologia/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divRadiologico").html(result);
            $('#tablemodalm2').DataTable({
              "sPaginationType": "full_numbers",
              
              "language": {"url": "/js/Spanish.json"},
            });
        }
    });
  }

</script>
<script>
    $(document).ready(function () {
        // Mantener `modal-open` si hay otros modales abiertos
        $('.modal').on('hidden.bs.modal', function () {
            if ($('.modal.show').length) { 
                $('body').addClass('modal-open'); 
            } else {
                $('body').removeClass('modal-open'); 
            }
        });
      // Restaurar el scroll del modal padre cuando se cierra un hijo
        $('#myModalEvaluacion, #myModalEvolucion, #myModalAtencionHospitalizacion').on('hidden.bs.modal', function () {
            $('#myModalAtencionHC').css({
                'overflow-y': 'auto',  // Habilita el scroll en el modal padre
                'padding-right': '0px' // Elimina el ajuste de padding que Bootstrap agrega
            });
        });

        // Prevenir que el modal padre se cierre si un hijo sigue abierto
        $('#myModalEvaluacion, #myModalEvolucion, #myModalAtencionHospitalizacion').on('hide.bs.modal', function (e) {
            if ($('.modal.show').length) { 
                e.preventDefault(); 
            }
        });

        // Forzar reactivación del modal padre después de cerrar un hijo
        $('#myModalEvaluacion, #myModalEvolucion, #myModalAtencionHospitalizacion').on('hidden.bs.modal', function () {
            setTimeout(function () {
                $('#myModalAtencionHC').css('overflow-y', 'auto'); 
            }, 300);
        });
        
        $('.modal').on('click', function (e) {
            if ($(e.target).hasClass('modal')) {
                $(this).css('overflow-y', 'auto');
            }
        });
    });
</script>

<style>
  #black_contenido {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: black;
      opacity: 1;
      z-index: 9999;
      display: none;
  }
</style>
<script>
    var black_contenido_timer = null;
    // Activar la capa de opacidad para difuminar el contenido
    function blackContenido() {
        const black_contenido  = document.getElementById('black_contenido');
        black_contenido.style.display = "block"; // Muestra la capa de opacidad
        clearTimeout(black_contenido_timer);
        black_contenido_timer = setTimeout(ocultarBlackContenido, 2000);
    }
    function ocultarBlackContenido() {
      const black_contenido = document.getElementById('black_contenido');
      black_contenido.style.display = "none";
    }

    document.addEventListener('DOMContentLoaded', function () {
      const black_contenido = document.getElementById('black_contenido');
      if (black_contenido) {
        black_contenido.addEventListener('click', ocultarBlackContenido);
      }
    });

    // Deshabilitar clic derecho en la página
    document.addEventListener('contextmenu', function(event) {
        event.preventDefault(); // Previene el menú contextual (clic derecho)
    });
    document.addEventListener('keydown', function(event) {
      // Evita copiar texto
      if ((event.metaKey || event.ctrlKey) && event.key === 'c') {
          event.preventDefault();  
      }
      //PARA MAC
      if (event.shiftKey && event.metaKey) {
        blackContenido();  
      }
      //PARA WINDOWS
      if (event.key === "PrintScreen") {
        blackContenido();
      }
      // evita la herramientas de recorte
      if (event.key === "S" && event.metaKey && event.shiftKey) {
        blackContenido();
      }
      // Detectar si se presiona Windows + Impr Pant
      if (event.key === "PrintScreen" && event.metaKey) {
        blackContenido();
      }
      if(event.code ==='F13'){
        blackContenido();
      }
      
    });
</script>

@endsection
@section('content')
<div id="black_contenido"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE HISTORIA CLINICA DE ATENCIONES</h3>
            <!-- <h3 class="panel-title">MÓDULO DE FICHA ELECTRONICA DE ATENCIONES</h3> -->
          </div>

          <div class="panel-body marca_agua_container" style="position:relative;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_hc_pagina"></div>

              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>
                  <div class="panel with-nav-tabs panel-default">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tabdefault" data-toggle="tab" style="font-size:12px">DATOS DEL PACIENTE</a></li>
                        <li><a href="#tab_laboratorio" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE LABORATORIO</a></li>
                        <li><a href="#tab_imagenologia" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE IMAGENOLOGIA</a></li>
                      </ul>
                    </div>
                    <div class="panel-body">
                      <div class="tab-content">
                        <div class="tab-pane fade in active" id="tabdefault">
                        
                          <div class="row">
                            <div class="form-group">
                              <div class="col-md-4">
                                <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                <p><?php echo $paciente->tipo_documento?></p>
                                <input type="hidden" value="<?php echo $paciente->nro_doc_ident?>" name="dnibeneficiario" id="dnibeneficiario" class="form-control">
                              </div>
        
                              <div class="col-md-4">
                                <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                                <p><?php echo $paciente->nro_doc_ident?></p>
                              </div>
        
                              <div class="col-md-4">
                                <label for="dni_beneficiario">SEXO</label>
                                <p><?php echo $paciente->sexo?></p>
                              </div>
                            </div>
        
                            <div class="form-group">
                              <div class="col-md-4">
                                <label>APELLIDO PATERNO</label>
                                <p><?php echo $paciente->asegurado_paterno?></p>
                              </div>
        
                              <div class="col-md-4">
                                <label>APELLIDO MATERNO</label>
                                <p><?php echo $paciente->asegurado_materno?></p>
                              </div>
        
                              <div class="col-md-4">
                                <label>NOMBRES</label>
                                <p><?php echo $paciente->asegurado_nombre?></p>
                              </div>
                            </div>
        
                            <div class="form-group">
                              <div class="col-md-4">
                                <label>FECHA DE NACIMIENTO</label>
                                <p><?php echo $paciente->fecha_nac?></p>
                              </div>
                              <div class="col-md-4">
                                <label>PARENTESCO &nbsp;</label>
                                <p><?php echo $paciente->parentesco?></p>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="form-group">
                              <div class="col-sm-12 pull-left">
                                <a href="<?php echo url('/print_atencion_completa/'.$paciente->id_asegurado) ?>" target="_blank" class="btn btn-danger"><i class="fa fa-print"></i> Historia completa</a>
                              </div>  
                            </div>
                          </div>

                        </div>
  
                        <div class="tab-pane" id="tab_laboratorio">
                          <fieldset>
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
                          </fieldset>
                        </div>
  
                        <div class="tab-pane" id="tab_imagenologia">
                          <fieldset>
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
                          </fieldset>
                        </div>
  
                      </div>
                    </div>
                  </div>
                </fieldset>
              </div>

              <fieldset>

                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>ATENCIONES</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="table-responsive">
                      <table class="table table-hover" id="tblAtenciones">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Establecimiento</th>
                            <th>Tipo de Atención</th>
                            <th>Servicio</th>
                            <th>Profesional</th>
                            <th>Ver</th>
                          </tr>
                        </thead>
                        <?php $i = 1 ?>
                        <tbody>
                          @foreach($atenciones as $a)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{date('d/m/Y', strtotime($a->dia))}}</td>
                            <td>{{$a->establecimiento}}</td>
                            <td>{{$a->tipo_atencion}}</td>
                            <td>{{$a->servicio}}</td>
                            <td>{{$a->medico_nombre}} {{$a->medico_paterno}} {{$a->medico_materno}}</td>
                            <td>
                              <!-- <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Atencion"> -->
                                <a href="javascript:void(0)" onclick="ver_det_atencion_hc({{$a->id}})" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> Ver</a>
                              <!-- </div>-->&nbsp; 
                              <!-- <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Atencion"> -->
                                <!--<a href="<?php echo url('/print_atencion/'.$a->id) ?>" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Imprimir</a> -->
                                @if($accesos->flagfiscalizador == 1 || ($accesos->flagestablecimientos == 1 && $accesos->flagservicios == 1 && $accesos->flagfarmacias == 1))
                                <a href="<?php echo url('/print_hc/'.$a->id) ?>" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Imprimir</a>
                                @endif
                              <!-- </div> -->
                            </td>  
                          </tr>
                          <?php $i++; ?>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div> <!-- .panel-body-->
                </div> <!-- .panel -->

              </fieldset>
                
              <a class="btn btn-primary btn-sm hidden-print pull-right" href="/busqueda_hc_electronica"> <span
                  class="glyphicon glyphicon-list"></span> &nbsp; Busqueda</a>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<div id="myModalAtencionHC" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width: 1600px !important; max-width: 95% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE ATENCION. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body marca_agua_container" style="position:relative;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_atencion_hc"></div>
              <div id="muestra-det-atencion-hc" style="position:relative; z-index:1;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection
