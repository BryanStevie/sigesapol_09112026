
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

#tablemodal tbody tr:hover td, #tablemodal1 tbody tr:hover th {
  /*background-color: red!important;*/
  font-weight:bold;
  /*mix-blend-mode: difference;*/
  
}

#tablemodalm1{
  
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
                            <fieldset>
                                

                                    <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" style="margin-top:5px;">
                                      <thead>
                                        <tr>
                                          <th width="15%">Codigo</th>
                                          <th width="50%">Nombre</th>
                                          <th width="15%">Tipo</th>
                                          <th width="15%">Clasificación</th>
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
                                            $clase_diagnostico = "Atencion";
                                          }else if($row->id_clasificacion_diagnosticos==2){
                                            $clase_diagnostico = "Ingreso";
                                          }else if($row->id_clasificacion_diagnosticos==3){
                                            $clase_diagnostico = "Egreso";
                                          }else if($row->id_clasificacion_diagnosticos==4){
                                            $clase_diagnostico = "Mortalidad";
                                          }else if($row->id_clasificacion_diagnosticos==8){
                                            $clase_diagnostico = "Evolución";
                                          }else{
                                            $clase_diagnostico = "Atencion";
                                          }

                                        ?>
                                        <tr>
                                          <td><?php echo $row->codigo?></td>
                                          <td><?php echo $row->nombre?></td>
                                          <td><?php echo $tipo_diagnostico?></td>
                                          <td><?php echo $clase_diagnostico?></td>
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