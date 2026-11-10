<title>Sistema de Farmacia</title>

<style>
/*****************/
.modal-dialog {
  width: 90%;
  }
  
#tablemodal{
    border-spacing: 0;
    display: flex;/*Se ajuste dinamicamente al tamano del dispositivo**/
    max-height: 80vh; /*El alto que necesitemos**/
    overflow-y: auto; /**El scroll verticalmente cuando sea necesario*/
    overflow-x: hidden;/*Sin scroll horizontal*/
    table-layout: fixed;/**Forzamos a que las filas tenga el mismo ancho**/
    width: 98vw; /*El ancho que necesitemos*/
    border:1px solid #c4c0c9;
}

#tablemodal thead{
    background-color: #e2e3e5;
    position: fixed !important;
}

#tablemodal th{
    border-bottom: 1px solid #c4c0c9;
    border-right: 1px solid #c4c0c9;
}

#tablemodal th{
    font-weight: normal;
    margin: 0;
    max-width: 9.5vw; 
    min-width: 9.5vw;
    word-wrap: break-word;
    font-size: 10px;
  font-weight:bold;
    height: 3.5vh !important;
  line-height:12px;
  vertical-align:middle;
  /*height:20px;*/
    padding: 4px;
    border-right: 1px solid #c4c0c9;
}

#tablemodal td{
    font-weight: normal;
    margin: 0;
    max-width: 9.5vw; 
    min-width: 9.5vw;
    word-wrap: break-word;
    font-size: 11px;
    height: 3.5vh !important;
    padding: 4px;
    border-right: 1px solid #c4c0c9;
}

#tablemodal tbody tr:hover td, #tablemodal tbody tr:hover th {
  /*background-color: red!important;*/
  font-weight:bold;
  /*mix-blend-mode: difference;*/
  
}

#tablemodalm{
  
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

<script type="text/javascript">

function ver_det_atencion(id){
    $('#nrorecmodal').text(id);
    $.ajax({
    url: '/ver_modal_emergencia_triaje/' + id,
            async: false,
            success: function(result){
            $("#muestra-det-atencion").html(result);
            },
            complete: function () {
            $("#myModalAtencion").modal();
            }
    });
    }

  var texto_marca_agua_triaje_modal = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';
  function generar_marca_agua_triaje_modal() {
    var overlay = document.getElementById('marca_agua_overlay_triaje_modal');
    if (!overlay) return;
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
            span.textContent = texto_marca_agua_triaje_modal;
            span.style.left = (c * 220 - 40) + 'px';
            span.style.top  = (r * 100 - 20) + 'px';
            overlay.appendChild(span);
        }
    }
  }

  $('#myModalAtencion').on('shown.bs.modal', function () {
      generar_marca_agua_triaje_modal();
  });

  var resizeMarcaAguaTriajeModalTimer = null;
  $(window).on('resize', function () {
    clearTimeout(resizeMarcaAguaTriajeModalTimer);
    resizeMarcaAguaTriajeModalTimer = setTimeout(function () {
        if ($('#myModalAtencion').is(':visible')) generar_marca_agua_triaje_modal();
    }, 250);
  });

</script>


<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                  <div class="box box-primary">
                    <br>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="tablemodalm">
                                <thead>
                                    <tr>
                                        <th>Fecha Triaje</th>
                                        <th>Hora Triaje</th>
                                        <th>ESTABLECIMIENTO</th>
                                        <th>Prioridad</th>
                                        <th>Area</th>
                                        <th>MEDICO</th>
                                        <th>Motivo Consulta</th>
                                        <th class="text-center">VER</th>
                                    </tr>
                                </thead>
                                <tbody id="muestra-atenciones" style="padding-top:32px">
                                    <?php foreach($atenciones as $rowHorario):?>
                                    <tr>
                                        <td><?php echo $rowHorario->fecha_triaje?></td>
                                        <td><?php echo $rowHorario->hora_triaje?></td>
                                        <td><?php echo $rowHorario->establecimiento?></td>
                                        <td><?php echo $rowHorario->prioridad?></td>
                                        <td><?php echo $rowHorario->topico?></td>
                                        <td><?php echo $rowHorario->nombre_profesional." ".$rowHorario->apepaterno_profesional." ".$rowHorario->apematerno_profesional?></td>
                                        <td><?php echo $rowHorario->observacion_triaje?></td>
                                        <td class="text-center">
                                          <?php 
                                          if ( $rowHorario->estado >= 2) {
                                          ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                                              <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                                              <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                                              <a href="javascript:void(0)" onClick="ver_det_atencion({{$rowHorario->cod_emergencia}})" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                                            </div>
                                            </div>
                                          <?php
                                          } else {
                                          ?>
                                            <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                          <?php 
                                          }
                                          ?>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                  </div>
                <!-- /.box -->
                </div>
                <!--/.col (left) -->
            </div>
          <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

<!-- /.content-wrapper -->
<div class="modal fade" id="myModalAtencion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE ATENCION EN EL MODULO DE TRIAJE DE EMERGENCIA. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body marca_agua_container" style="position:relative;">
                <div class="marca_agua_overlay" id="marca_agua_overlay_triaje_modal"></div>
                <div id="muestra-det-atencion" style="position:relative; z-index:1;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</body>
    