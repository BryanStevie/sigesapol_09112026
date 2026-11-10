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

function ver_det_atencion(id){
    $('#nrorecmodal').text(id);
    $.ajax({
    url: '/mostrar_atencion/' + id,
            async: false,
            success: function(result){
            $("#muestra-det-atencion").html(result);
            },
            complete: function () {
            $("#myModalAtencion").modal();
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
                                        <th>TIPO DOC</th>
                                        <th>NUMERO DOC</th>
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
                                        <td><?php echo $rowHorario->tipo_doc_ident?></td>
                                        <td><?php echo $rowHorario->nro_doc_ident?></td>
                                        <td><?php echo $rowHorario->dia?></td>
                                        <td><?php echo $rowHorario->hora?></td>
                                        <td><?php echo $rowHorario->establecimiento?></td>
                                        <td><?php echo $rowHorario->tipo_atencion?></td>
                                        <td><?php echo $rowHorario->servicio?></td>
                                        <td><?php echo $rowHorario->consultorio?></td>
                                        <td><?php echo $rowHorario->medico_nombre." ".$rowHorario->medico_paterno." ".$rowHorario->medico_materno?></td>
                                        <td class="text-center">
                                          <?php 
                                          if ( $rowHorario->id_estado_reg == 1) {
                                          ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                                              <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                                              <a href="/ver_prestacion/<?php echo Auth::user()->id ?>/<?php echo $rowHorario->id ?>" class="btn btn-success btn-xs"><i class="fa fa-eye"></i>
                                              </a>
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
                <h5 class="modal-title">DETALLE DE ATENCION EN EMERGENCIA. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-atencion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</body>
    