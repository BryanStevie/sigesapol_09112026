<title>SIGESAPOL</title>

<style>
</style>

<script type="text/javascript">
$(document).ready(function() {
    
});
function ver_det_diagnostico_evoluciones(id_prestacion){
    $.ajax({
    
            url: "../../modal_diagnostico_paciente_nuevo_ho_evo/"+id_prestacion,
            async: false,
            success: function(result){
            $("#muestra-det-diagnosticos-evolucion").html(result);
            },
            complete: function () {
            $("#myModalDiagnosticosEvolucion").modal();
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
                            <fieldset><b>DIAGNOSTICOS DE ATENCION </b><br/>
                              <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" style="margin-top:5px;">
                                <thead>
                                  <tr>
                                    <th width="10%">Codigo</th>
                                    <th width="60%">Nombre</th>
                                    <th width="10%">Tipo</th>
                                    <th width="10%">Categoría</th>
                                    <th width="10%">Clasificación</th>
                                  </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                  <?php foreach($diagnosticos as $row):
                                    $tipo_diagnostico = "";
                                    if($row->id_tipo_diagnostico==1)$tipo_diagnostico = "PRESUNTIVO";
                                    if($row->id_tipo_diagnostico==2)$tipo_diagnostico = "DEFINITIVO";
                                    if($row->id_tipo_diagnostico==3)$tipo_diagnostico = "REITERATIVO";

                                    $clase_diagnostico="";
                                    if($row->id_clasificacion_diagnosticos==1){
                                      $clase_diagnostico = "ATENCIÓN";
                                    }else if($row->id_clasificacion_diagnosticos==2){
                                      $clase_diagnostico = "INGRESO";
                                    }else if($row->id_clasificacion_diagnosticos==3){
                                      $clase_diagnostico = "EGRESO";
                                    }else if($row->id_clasificacion_diagnosticos==4){
                                      $clase_diagnostico = "MORTALIDAD";
                                    }else if($row->id_clasificacion_diagnosticos==8){
                                      $clase_diagnostico = "EVOLUCIÓN";
                                    }else{
                                      $clase_diagnostico = "";
                                    }

                                    $categoria = '';
                                    if ($row->id_categoria != null) {
                                      $categoria = ($row->id_categoria == 1) ? 'PRINCIPAL' : 'SECUNDARIO';
                                    }

                                  ?>
                                  <tr>
                                    <td>{{ $row->codigo }}</td>
                                    <td>{{ $row->nombre }}</td>
                                    <td>{{ $tipo_diagnostico }}</td>
                                    <td>{{ $categoria }}</td>
                                    <td>{{ $clase_diagnostico }}</td>
                                  </tr>
                                  <?php endforeach;?>
                                </tbody>
                              </table>
                                  
                            </fieldset>  
                            <fieldset><br/><br/><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Indicaciones Terapeutica"><b>DIAGNOSTICOS DE EVOLUCION </b><a href="javascript:void(0)" onClick="ver_det_diagnostico_evoluciones({{$id_prestacion}})" class="btn btn-success btn-xs"> <i class="fa fa-list"></i></a>
                                        </div><br/></fieldset>
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
<div class="modal fade" id="myModalDiagnosticosEvolucion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Diagnosticos en Evolucion <span id="nrorecmodal49"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-diagnosticos-evolucion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>