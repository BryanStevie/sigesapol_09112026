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

#tablemodalm1{
  
}

.label-error {
  color: #d9534f; 
  font-size: 11px; 
  font-weight: bold
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
  var texto_marca_agua_atencion1 = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';
  function generar_marca_agua_atencion1() {
    var overlay = document.getElementById('marca_agua_overlay_atencion1');
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
        span.textContent = texto_marca_agua_atencion1;
        span.style.left = (c * 220 - 40) + 'px';
        span.style.top  = (r * 100 - 20) + 'px';
        overlay.appendChild(span);
      }
    }
  }

  $('#myModalAtencion1').on('shown.bs.modal', function () {
    generar_marca_agua_atencion1();
  });

  var resizeMarcaAguaAtencion1Timer = null;
  $(window).on('resize', function () {
    clearTimeout(resizeMarcaAguaAtencion1Timer);
    resizeMarcaAguaAtencion1Timer = setTimeout(function () {
        if ($('#myModalAtencion1').is(':visible')) generar_marca_agua_atencion1();
    }, 250);
  });
</script>

<script type="text/javascript">

  $(document).ready(function() {
    $(document).on('click', '#btn_show_detail1', function() {
      const atencion_id = $(this).data('id');
      showDetailAttention1(atencion_id);
    });

    function showDetailAttention1(atencionId) {
      $("#modalFooterMyModalAtencion").hide();
      $('#nrorecmodal').text(atencionId);
      $.ajax({
        url: '/mostrar_atencion_1/' + atencionId,
        // async: false,
        success: function(result){
          $("#muestra-det-atencion1").html(result);
          $("#myModalAtencion1").modal();
          const showFormIntervencionQuirurgica = $("#showFormIntervencionQuirurgica").val();
          const showFormDetailIntervencionQuirurgica = $("#showFormDetailIntervencionQuirurgica").val();
          if (parseInt(showFormIntervencionQuirurgica) === 1) {
            $("#modalFooterMyModalAtencion").show();
          }
          if (parseInt(showFormDetailIntervencionQuirurgica) === 1) {
            $("#toogleProgramacionQuirurgica").addClass("active");
            // $("#panelProgramacionQuirurgica").slideDown("slow");
            $("#panelProgramacionQuirurgica").slideDown("slow", function () {
              generar_marca_agua_atencion1();
            });
            $("#toogleProgramacionQuirurgica").prop("disabled", true);
            $("#valueToogleProgramacionQuirurgica").val("0");

            $("#toogleInterconsultaPreQuirurgica").addClass("inactive");
            $("#toogleInterconsultaPreQuirurgica").prop("disabled", true);
            $("#valueToogleInterconsultaPreQuirurgica").val("0");

            $("#btnAddRowTableProcedure_h_iq").hide();
            $("#btnMopriQx").hide();
          }
        },
        complete: function () {
        },
        error: function (xhr, status, err){
          const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message  : "Ocurrio un error, intentelo en unos minutos";
          Swal.fire({
            icon: 'error',
            title: "HISTORIAL DE ATENCIONES MEDICAS",
            html: `<p style="font-size: 13px">${message}</p>`,
            showConfirmButton: false,
            timer: 3000,
          });
        }
      });
    }
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
                            <table class="table table-bordered table-sm" id="tablemodalm1a">
                                <thead>
                                    <tr>
                                        <th>FECHA</th>
                                        <th>HORA</th>
                                        <th>ESTABLECIMIENTO</th>
                                        <th>TIPO ATENCION</th>
                                        <th>SERVICIO</th>
                                        <th>CONSULTORIO</th>
                                        <th>MEDICO</th>
                                        <th class="text-center">VER</th>
                                    </tr>
                                </thead>
                                <tbody id="muestra-atenciones" style="padding-top:32px">
                                    <?php foreach($atenciones as $rowHorario):?>
                                    <tr>
                                        <td><?php echo $rowHorario->dia?></td>
                                        <td><?php echo $rowHorario->hora?></td>
                                        <td><?php echo $rowHorario->establecimiento?></td>
                                        <td><?php echo $rowHorario->tipo_atencion?></td>
                                        <td><?php echo $rowHorario->servicio?></td>
                                        <td><?php echo $rowHorario->consultorio?></td>
                                        <td><?php echo $rowHorario->medico_nombre." ".$rowHorario->medico_paterno." ".$rowHorario->medico_materno?></td>
                                        <td class="text-center">
                                          @if ($rowHorario->id_estado_reg != 0)
                                            <button 
                                              type="button" 
                                              id="btn_show_detail1" 
                                              data-id="{{$rowHorario->id}}" 
                                              class="btn btn-success btn-xs"
                                              data-toggle="tooltip" 
                                              data-placement="bottom" 
                                              title="Ver Atencion"
                                            ><i class="fa fa-eye"></i></button>
                                          @else
                                            <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                          @endif
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
<div class="modal fade" id="myModalAtencion1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE ATENCION. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body marca_agua_container" style="position:relative; overflow-y: auto; max-height: 80vh;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_atencion1"></div>
              <div id="muestra-det-atencion1" style="position:relative; z-index:1;"></div>
            </div>
            <div class="modal-footer" id="modalFooterMyModalAtencion" hidden>
                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> --}}
              <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <button type="button" class="btn btn-danger btn-sm btn-block" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> CANCELAR</button>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <button type="button" class="btn btn-success btn-sm btn-block" id="btnSaveAtencion"><i class="fa fa-floppy-o" aria-hidden="true"></i> GUARDAR</button>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

</body>
    