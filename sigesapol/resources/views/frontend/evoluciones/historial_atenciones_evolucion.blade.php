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
</style>

<script type="text/javascript">

$(document).ready(function() {
    
});

function ver_det_evolucion(id){
    $('#nrorecmodal2').text(id);
    $.ajax({
    url: '/mostrar_evolucion/' + id,
            async: false,
            success: function(result){
            $("#muestra-det-evolucion").html(result);
            },
            complete: function () {
            $("#myModalEvolucion").modal();
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
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="tablemodalm">
                                <thead>
                                    <tr>
                                        <th>FECHA</th>
                                        <th>HORA</th>
                                        <th>ESTABLECIMIENTO</th>
                                        <th>MEDICO</th>
                                        <th style="text-align:center">Evolucion</th>
                                        <th style="text-align:center">Pdf Atencion</th>
                                        <th style="text-align:center;min-width:106px">Receta</th>
                                        <th style="text-align:center">Lab</th>
                                        <th style="text-align:center">Rad</th>
                                        <th style="text-align:center">Interconsultas</th>
                                        <th style="text-align:center">Ref</th>
                                        <th>Anular</th>
                                        <th class="text-center">VER</th>
                                    </tr>
                                </thead>
                                <tbody id="muestra-atenciones" style="padding-top:32px">
                                    <?php foreach($evoluciones as $rowHorario):?>
                                    <tr>
                                        <td><?php echo $rowHorario->fecha_ingreso?></td>
                                        <td><?php echo $rowHorario->hora_ingreso?></td>
                                        <td><?php echo $rowHorario->establecimiento?></td>
                                        <td><?php echo $rowHorario->medico_nombre." ".$rowHorario->medico_paterno." ".$rowHorario->medico_materno?></td>

                                        <td class="text-center">
                                          <?php 
                                          if ( $rowHorario->id_estado_reg != 0) {
                                          ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evolución</b>">
                                              <a href="javascript:void(0)" onClick="ver_det_evolucion({{$rowHorario->id}})" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                                            </div>
                                          <?php
                                          } else {
                                          ?>
                                            <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                          <?php 
                                          }
                                          ?>
                                        </td>
                                        <td><?php echo $rowHorario->fecha_ingreso?></td>
                                        <td><?php echo $rowHorario->fecha_ingreso?></td>
                                        <td><?php echo $rowHorario->fecha_ingreso?></td>
                                        <td><?php echo $rowHorario->fecha_ingreso?></td>
                                        <td><?php echo $rowHorario->fecha_ingreso?></td>
                                        <td><?php echo $rowHorario->fecha_ingreso?></td>
                                        <td><?php echo $rowHorario->fecha_ingreso?></td>
                                        <td><?php echo $rowHorario->fecha_ingreso?></td>
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
<div class="modal fade" id="myModalEvolucion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE EVOLUCION. <span id="nrorecmodal2"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-evolucion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</body>
    