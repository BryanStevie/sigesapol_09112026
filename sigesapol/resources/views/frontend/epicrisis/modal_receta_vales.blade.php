
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

#tablemodalm7{
  
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
                            <fieldset id="fsRecetaVale">
                                    <div class="panel-body">
                                        <?php foreach($recetas as $rowReceta):
                                            if ($rowReceta->fecha_firma == "") {
                                                $flag_firmar = 1;
                                            }
                                            $model_productos_receta = new \App\Models\Producto_receta;
                                            $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);
                                        ?>
                                    <div style="clear:both"></div>
                                    <fieldset>
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="col-md-2" style="padding-left:0px">RECETA VALE N°</label>
                                                        <div class="col-md-1">
                                                            <p><?php echo $rowReceta->nro_receta; ?></p>
                                                        </div>
                                                        <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                                        <div class="col-md-2">
                                                            <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                                                        </div>
                                                        <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                                        <div class="col-md-4" style="padding:0px">
                                                            <p><?php echo $rowReceta->farmacia; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <table class="display" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="15%">Codigo</th>
                                                            <th width="30%">Producto farmacéutico o dispositivo médico</th>
                                                            <th width="15%">Unidad Medida</th>
                                                            <th width="10%">Tiempo(Dias)</th>
                                                            <th width="15%">Posologia</th>
                                                            <th width="15%">Cantidad Prescrita</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                    </tfoot>
                                                    <?php foreach($medicamentos as $row):?>
                                                        <tr>
                                                            <td><?php echo $row->codigo?></td>
                                                            <td><?php echo $row->nombre?></td>
                                                            <td><?php echo $row->abreviatura?></td>
                                                            <td><?php echo $row->tiempo_periodo?></td>
                                                            <td><?php echo $row->posologia?></td>
                                                            <td><?php echo $row->cantidad_prescrita?></td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <?php endforeach;?>
                                
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