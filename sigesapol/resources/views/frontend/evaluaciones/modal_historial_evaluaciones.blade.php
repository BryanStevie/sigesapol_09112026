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

#tablemodalm3{
  
}
</style>

<script type="text/javascript">


function ver_det_evaluacion(id, id_tipo_atencion){
    
    var cad;
    if(id_tipo_atencion==2){
        cad = '/mostrar_evaluacion/';
    }
    else{
        cad = '/mostrar_evolucion/';
    }

    $.ajax({
    
            url: cad + id+"/"+id_tipo_atencion,
            async: false,
            success: function(result){
                $("#muestra-det-evaluacion").html(result);
                $("#myModalEvolucion").modal();
            },
            complete: function () {
            },
            error: function (xhr, status, err) {
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
</script>


<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                  <div class="box box-primary">
                    <br>
                    <div class="col-md-12">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="tablemodalm3">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>FECHA</th>
                                            <th>HORA</th>
                                            @if ($id_tipo_atencion == 2)
                                            <th>PRIORIDAD</th>
                                            <th>TOPICO</th>
                                            @endif
                                            <th>MEDICO</th>
                                            <th class="text-center">VER</th>
                                        </tr>
                                    </thead>
                                    <tbody id="muestra-atenciones" style="padding-top:32px">
                                        <?php 
                                            $i=1; 
                                            foreach($evaluaciones as $rowEvolucion):?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $rowEvolucion->fecha_evaluacion?></td>
                                            <td><?php echo $rowEvolucion->hora?></td>
                                            @if ($id_tipo_atencion == 2)
                                            <td><?php echo $rowEvolucion->nom_prioridad?></td>
                                            <td><?php echo $rowEvolucion->consultorio?></td>
                                            @endif
                                            <td><?php echo $rowEvolucion->medico_nombre." ".$rowEvolucion->medico_paterno." ".$rowEvolucion->medico_materno?></td>
                                            <td class="text-center">
                                              <?php 
                                              if ( $rowEvolucion->id_estado_reg != 0) {
                                              ?>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Evaluación">
                                                  <a href="javascript:void(0)" onClick="ver_det_evaluacion({{$rowEvolucion->id}},{{$rowEvolucion->id_tipo_atencion}})" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
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
<div class="modal fade" id="myModalEvolucion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE LA EVALUACION MEDICA: <span id="nrorecmodal2"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-evaluacion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</body>
    