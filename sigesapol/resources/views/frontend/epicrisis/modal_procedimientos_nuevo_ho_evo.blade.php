<title>SIGESAPOL</title>

<style>
</style>

<script type="text/javascript">

$(document).ready(function() {
    
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
                            <fieldset><b>PROCEDIMIENTOS DE ATENCION </b><br/>
                                <table class="display" id="tblProcedimientoE" cellspacing="0" width="100%" style="margin-top:5px;">
                                  <thead>
                                    <tr>
                                      <th width="10%">Codigo</th>
                                      <th width="70%">Nombre</th>
                                      <th width="10%">Cantidad</th>
                                      <th width="10%">Diagnóstico</th>
                                    </tr>
                                  </thead>
                                  <tfoot>
                                  </tfoot>
                                  <tbody>
                                    <?php foreach($procedimientos as $row):?>
                                    <tr>
                                      <td><?php echo $row->codigo?></td>
                                      <td><?php echo $row->nombre?></td>
                                      <td><?php echo $row->cantidad?></td>
                                      <td><?php echo $row->codigo_diagnostico?></td>
                                    </tr>
                                    <?php endforeach;?>
                                  </tbody>
                                </table>
                            </fieldset>
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
<div class="modal fade" id="myModalProcedimientosEvolucion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Procedimientos en Evolucion <span id="nrorecmodal49"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-procedimientos-evolucion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>