
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

#tablemodalm9{
  
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
                            <?php foreach($evoluciones as $rowi):?>
                            <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>TEMPERATURA (°C) &nbsp;</label>
                                            <p><?php echo $rowi->temperatura?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>TALLA (cm) &nbsp;</label>
                                            <p><?php echo $rowi->talla_cm?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>PESO (Kg) &nbsp;</label>
                                            <p><?php echo $rowi->peso_kg?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>IMC &nbsp;</label>
                                            <p></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>PESO POR ALTURA &nbsp;</label>
                                            <p></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Sa02 (%) &nbsp;</label>
                                            <p><?php echo $rowi->sa02_porcentaje?></p>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>TAMAÑO DE CINTURA (cm) &nbsp;</label>
                                            <p><?php echo $rowi->tamano_cintura_cm?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>FREC. CARDIACA FETAL (/min) &nbsp;</label>
                                            <p><?php echo $rowi->frecuencia_cardiaca_fetal?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>PRESIÓN ARTERIAL &nbsp;</label>
                                            <p><?php echo $rowi->presion_arterial_x?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>FREC. RESPIRATORIA (/min) &nbsp;</label>
                                            <p><?php echo $rowi->frecuencia_respiratoria?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>FREC. CARDIACA (/min) &nbsp;</label>
                                            <p><?php echo $rowi->frecuencia_cardiaca?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Fi02 (%) &nbsp;</label>
                                            <p><?php echo $rowi->fi02?></p>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label class="col-md-2" style="padding-left:0px">SUBJETIVO</label>
                                        <div class="col-md-10">
                                          <p><?php echo $rowi->subjetivo?></p>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label class="col-md-2" style="padding-left:0px">OBJETIVO</label>
                                        <div class="col-md-10">
                                          <p><?php echo $rowi->objetivo?></p>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label class="col-md-2" style="padding-left:0px">ANALISIS</label>
                                        <div class="col-md-10">
                                          <p><?php echo $rowi->analisis?></p>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label class="col-md-2" style="padding-left:0px">TRATAMIENTO Y DIETA</label>
                                        <div class="col-md-10">
                                          <p><?php echo $rowi->tratamiento_dieta?></p>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label class="col-md-2" style="padding-left:0px">INDICACIONES</label>
                                        <div class="col-md-10">
                                          <p><?php echo $rowi->indicaciones?></p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                            <?php endforeach;?>
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