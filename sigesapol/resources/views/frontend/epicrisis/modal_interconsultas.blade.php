
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

#tablemodalm5{
  
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
                              <div class="col-md-12">
                                <!-- MOSTRAR INTERCONSULTAS---->
                                <?php foreach($interconsultas as $rowi):?>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="form-group"> 
                                    <div class="panel-body"> 
                                      <div class="col-md-12">
                                        <div class="col-md-2"> 
                                          <label>SERVICIO: </label>
                                        </div> 
                                        <div class="col-md-10"> 
                                          <?php echo $rowi->servicio ?>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="col-md-2"> 
                                          <label>MOTIVO:</label> 
                                        </div> 
                                        <div class="col-md-10"> 
                                          <?php echo $rowi->motivo_interconsulta ?>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="col-md-12"><br/> 
                                          <label>RESUMEN</label><br/>  
                                          <?php echo $rowi->resumen_interconsulta ?>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <?php endforeach;?>
                              </div>
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