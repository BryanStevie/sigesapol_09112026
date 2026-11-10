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
                                        <th>#</th>
                                        <th>TIPO DOC</th>
                                        <th>NUMERO DOC</th>
                                        <th>FECHA</th>
                                        <th>SERVICIO</th>
                                        <th>SALA</th>
                                        <th>PERSONAL</th>
                                        <th>MEDICO</th>
                                        <th>PABELLON</th>
                                        <th>PISO</th>
                                        <th>CUARTO</th>
                                        <th>CAMA</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="muestra-atenciones" style="padding-top:32px">
                                    <?php $n=1;?>
                                    <?php foreach($rotaciones as $row):?>
                                    <tr>
                                        <td><?php echo $n; ?></td>
                                        <td><?php if($row->id_tipo_cambio==1): echo "ROTACION"; else: echo "TRASLADO"; endif; ?></td>
                                        <td><?php if($row->id_tipo_cambio==1): echo ""; else: echo $row->documento; endif; ?></td>
                                        <td><?php echo $row->fecha_cambio?></td>
                                        <td><?php echo $row->servicio?></td>
                                        <td><?php echo $row->consultorio?></td>
                                        <td><?php echo $row->nombre_usuario.' '.$row->paterno_usuario.' '.$row->materno_usuario?></td>
                                        <td><?php if($row->id_tipo_cambio==2): echo $row->nombre_medico.' '.$row->paterno_medico.' '.$row->materno_medico; else: echo ""; endif; ?></td>
                                        <td><?php echo $row->pabellon?></td>
                                        <td><?php echo $row->piso?></td>
                                        <td><?php echo $row->cuarto?></td>
                                        <td><?php echo $row->cama?></td>
                                    </tr>
                                    <?php $n++;?>
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
</body>
    