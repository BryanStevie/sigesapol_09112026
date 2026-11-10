
<script>

  $(document).ready(function() {

    $('#tblAtenciones').DataTable({
      "sPaginationType": "full_numbers",
      "language": {"url": "/js/Spanish.json"},
    });
    modalLaboratorio();
    modalRadiologico();
    modalInterconsulta();
  });
  function ver_det_atencion_hc(id) {
    $("#muestra-det-atencion-hc").html("");
    $('#nrorecmodal').text(""); 

    $('#nrorecmodal').text(id);

    // Mostrar el loading con SweetAlert2
    Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando los datos del detalle de atención del paciente</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
        Swal.showLoading();
        }
    });

    $.ajax({
        url: '/mostrar_atencion_completa/' + id,
        //async: false, // según tu código actual
        success: function(result) {
        $("#muestra-det-atencion-hc").html(result);
        },
        complete: function () {
        // Cerrar el loading
        Swal.close();

        // Mostrar el modal
        $("#myModalAtencionHC").modal();
        },
        error: function () {
        Swal.fire('Error', 'Ocurrió un error al cargar la atención.', 'error');
        }
    });
  }
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
  function modalInterconsulta(){
  
    var dni_beneficiario = $("#dni_beneficiario").val();
    $.ajax({
        url: "../modal_historial_interconsulta/"+dni_beneficiario,
        type: "GET",
        success: function (result) {   
            $("#divInterconsultaH").html(result);
            $('#tablemodalm4').DataTable({
              "sPaginationType": "full_numbers",
              
              "language": {"url": "/js/Spanish.json"},
            });
        }
    });
  }
  // Definimos la función que cierra únicamente el modal de atención HC
  function cerrarSoloModalAtencion() {
    $('#myModalAtencionHC').modal('hide');
  }

  // Restaurar scroll en el modal padre si sigue abierto
  $(document).ready(function () {
    if ($('#myModalHC').hasClass('show')) {
        setTimeout(function () {
            $('body').addClass('modal-open');
        }, 10); // leve retraso para que Bootstrap termine de quitarlo
    }
  });
  $('#myModalHC .modal-body').css({
    'overflow-y': 'auto',
    'max-height': '80vh'
  });
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
    // Activar la capa de opacidad para difuminar el contenido
    function blackContenido() {
        const black_contenido  = document.getElementById('black_contenido');
        black_contenido.style.display = "block"; // Muestra la capa de opacidad
    }
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

<div id="black_contenido"></div>

  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class=" " >
       
          <div class="panel-body">

              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>
                  <div class="panel with-nav-tabs panel-default">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tabdefault" data-toggle="tab" style="font-size:12px">DATOS DEL PACIENTE</a></li>
                        <li><a href="#tab_laboratorio" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE LABORATORIO</a></li>
                        <li><a href="#tab_imagenologia" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE IMAGENOLOGIA</a></li>
                        <li><a href="#tab_historialInterconsulta" data-toggle="tab" style="font-size:12px">HISTORIAL DE INTERCONSULTA</a></li>
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

                        <div class="tab-pane"  id="tab_historialInterconsulta">
                            <fieldset>
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
                        <?php //$i = 1 ?>
                        <tbody>
                          @foreach($atenciones as $a)
                          <tr>
                            <td>{{$a->id}}</td>
                            <td>{{date('d/m/Y', strtotime($a->dia))}}</td>
                            <td>{{$a->establecimiento}}</td>
                            <td>{{$a->tipo_atencion}}</td>
                            <td>{{$a->servicio}}</td>
                            <td>{{$a->medico_nombre}} {{$a->medico_paterno}} {{$a->medico_materno}}</td>
                            <td>
                              
                            <a href="javascript:void(0)" onclick="ver_det_atencion_hc({{$a->id}})" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> Ver</a>
                            </td>  
                          </tr>
                          <?php //$i++; ?>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div> <!-- .panel-body-->
                </div> <!-- .panel -->

              </fieldset>

          </div>
        
      </div>
    </div>
  </div>


<div id="myModalAtencionHC" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width: 1600px !important; max-width: 95% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="cerrarSoloModalAtencion()">&times;</button>
                <h5 class="modal-title">DETALLE DE ATENCION. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-atencion-hc"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

