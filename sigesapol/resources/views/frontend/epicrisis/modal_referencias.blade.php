
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

#tablemodalm6{
  
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
                            <?php if(count($referencia)>0):?>
                            <fieldset>
                              <div class="col-md-12">
                               
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <div class="col-md-4">
                                          <label>ESTABLECIMIENTO: </label><br/>
                                          <?php echo $establecimiento[0]->nombre; ?>
                                        </div>
                                        <div class="col-md-4">
                                          <label>ESPECIFICAR SI REQUIERE UCI: </label><br/>
                                          <?php if($referencia->id_uci==1):  echo 'SI'; else: echo 'NO'; endif;?>
                                        </div>
                                        @if($referencia->id_uci==1)
                                          <div class="col-md-4">
                                            <?php 
                                                switch($referencia->id_uci_cv){
                                                  case '1' : echo "CON VENTILADOR MECANICO"; break;
                                                  case '2' : echo "SIN VENTILADOR MECANICO"; break;
                                                  case '3' : echo "NINGUNO"; break;
                                                }
                                            ?>
                                          </div>
                                        @endif
                                      </div>
                                      <div class="col-md-12">
                                        <div class="col-md-4">
                                          <br/>
                                        <label>REQUIERE TRASLADO EN AMBULANCIA: </label><br/>
                                        <?php if($referencia->id_ambulancia==1):  echo 'SI'; else: echo 'NO'; endif;?>
                                      </div>
                                      @if($referencia->id_ambulancia==1)
                                        <div class="col-md-4">
                                          <br/>
                                          <label><br/></label><br/>
                                          <?php if($referencia->id_ambulancia_ref==1):  echo 'IDA'; else: echo 'VUELTA'; endif;?>
                                        </div>
                                        <div class="col-md-4">
                                          <br/>
                                          <label>TIPO DE AMBULANCIA</label><br/>
                                          <?php 
                                                switch($referencia->id_tipo_ambulancia){
                                                  case '1' : echo "Tipo I"; break;
                                                  case '2' : echo "Tipo II"; break;
                                                  case '3' : echo "Tipo III"; break;
                                                }
                                          ?>
                                      </div>
                                      @endif
                                  </div>
                                  <div class="col-md-12">
                                      <div class="col-md-4">
                                          <br/>
                                          <label>REQUIERE PASAJE: </label><br/>
                                          <?php if($referencia->id_pasaje==1):  echo 'SI'; else: echo 'NO'; endif;?>
                                        </div>
                                        <div class="col-md-4">
                                           <br/> 
                                          <label>NECESITA ACOMPAÑANTE: </label><br/>
                                          <?php if($referencia->id_acompanante==1):  echo 'SI'; else: echo 'NO'; endif;?>
                                        </div>
                                        <div class="col-md-4">
                                          <br/>
                                          <label>SITUACION CLINICA DEL PACIENTE :</label><br/>
                                          <?php 
                                                switch($referencia->id_situacion){
                                                  case '1' : echo "ELECTIVO"; break;
                                                  case '2' : echo "URGENCIA"; break;
                                                  case '3' : echo "EMERGENCIA"; break;
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="col-md-12">
                                            <br/>
                                          <label>OBSERVACION</label><br/>
                                          <?php echo $referencia->observacion_referencia;?>
                                          
                                      </div>
                                    </div>
                                    </div>
                                  </div>
                            </fieldset>
                            <?php endif;?>
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