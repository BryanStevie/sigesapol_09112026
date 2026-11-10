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
                        <p><span class="label label-info"><i class="fa fa-info"></i></span> Exámenes registrados antes de la Implementacion del <b>SIGESAPOL</b></p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="tablemodalm1">
                                <thead>
                                    <tr>
                                        <th>TIPO DOC</th>
                                        <th>NUMERO DOC</th>
                                        <th>FECHA</th>
                                        <th>HORA</th>
                                        <th>ESTABLECIMIENTO</th>
                                        <th>TIPO ATENCION</th>
                                        <th>CONSULTORIO</th>
                                        <th>MEDICO</th>
                                        <th class="text-center">VER</th>
                                    </tr>
                                </thead>
                                <tbody id="muestra-atenciones" style="padding-top:32px">
                                    <?php
                                    foreach($examenes as $r):?>
                                    <tr>
                                        <td><?php echo $r->tipo_doc_ident?></td>
                                        <td><?php echo $r->nro_doc_ident?></td>
                                        <td><?php echo date("d/m/Y", strtotime($r->dia));?></td>
                                        <td><?php echo $r->hora?></td>
                                        <td><?php echo $r->establecimiento?></td>
                                        <td><?php echo $r->tipo_atencion?></td>
                                        <td><?php echo $r->consultorio?></td>
                                        <td><?php echo $r->nombre_medico." ".$r->paterno_medico." ".$r->materno_medico?></td>
                                        <td class="text-center">
                                
                                <?php 
                                if ($r->id_establecimiento == 76):
                                    if ($r->fecha_toma_muestra != null):
                                ?>
                                    <a target="_blank" href="/print_examen_laboratorio_lns/{{$r->id}}/{{$r->nro_doc_ident}}/{{$r->fecha_toma_muestra}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                    <!-- <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button> -->
                                <?php
                                    else:
                                ?>
                                        <a target="_blank" href="/print_examen_laboratorio_lns/{{$r->id}}/{{$r->nro_doc_ident}}/" class="btn btn-info btn-xs" disabled><i class="fa fa-eye"></i></a>
                                <?php
                                    endif;
                                else:

                                if ($r->ruta_laboratorio == "") {
                                ?>
                                <!-- si no esta firmado se pone BOTON DE EDITAR -->
                                    <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-edit"></i></button>
                                <?php 
                                } else {
                                ?>      <table>
                                        <tr><td>
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b> Bioquimica</b>">
                                        <?php 
                                            if($r->tipo_bioquimica=='SI'):
                                                if($r->rpta_bioquimica=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/1" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Hematologia</b>">
                                        <?php
                                            if($r->tipo_hematologia=='SI'):
                                                if($r->rpta_hematologia=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/2" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Inmunologia</b>">
                                        <?php
                                            if($r->tipo_inmunologia=='SI'):
                                                if($r->rpta_inmunologia=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/3" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Microbiologia</b>">
                                        <?php
                                            if($r->tipo_microbiologia=='SI'):
                                                if($r->rpta_microbiologia=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/4" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anatomia Patologica</b>">
                                        <?php
                                            if($r->tipo_anatomia=='SI'):
                                                if($r->rpta_anatomia=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/5" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Citopatologia</b>">
                                        <?php
                                            if($r->tipo_citopatologia=='SI'):
                                                if($r->rpta_citopatologia=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/6" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Citogenetico</b>">
                                        <?php
                                            if($r->tipo_citogenetico=='SI'):
                                                if($r->rpta_citogenetico=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/7" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Quirurgica</b>">
                                        <?php
                                            if($r->tipo_quirurgica=='SI'):
                                                if($r->rpta_quirurgica=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/8" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Otro</b>">
                                        <?php
                                            if($r->tipo_otro=='SI'):
                                                if($r->rpta_otro=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/9" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Reproductivo</b>">
                                        <?php
                                            if($r->tipo_reproductiva=='SI'):
                                                if($r->rpta_reproductiva=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/10" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Covid-19</b>">
                                        <?php
                                            if($r->tipo_covid=='SI'):
                                                if($r->rpta_covid=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/11" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                     <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        </tr>
                                        </table>
                                        <?php
                                    }

                                endif;
                                ?>
                            </td>
                                    </tr>
                                    <?php endforeach;?>

                                    <?php
                                    if (!empty($ordenes_laboratorio->RespuestaOrdenLab)):
                                        if(count($ordenes_laboratorio->RespuestaOrdenLab)>1):
                                            foreach($ordenes_laboratorio->RespuestaOrdenLab as $q):
                                    ?>
                                                <tr>
                                                    <td><span class="label label-info"><i class="fa fa-info"></i></span><?php ?></td>
                                                    <td><?php echo $q->nroDocumento?></td>
                                                    <td><?php echo $q->fechaMuestra?></td>
                                                    <td><?php ?></td>
                                                    <td style="font-size:13px"><?php echo $q->establecimiento?></td>
                                                    <td><?php ?></td>
                                                    <td><?php echo $q->tipoAtencion?></td>
                                                    <td><?php ?></td>
                                                    <td class="text-center">
                                                        <a target="_blank" href="/print_examen_laboratorio_lns/0/{{$q->nroDocumento}}/{{$q->fechaMuestra}}/{{$q->orden}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                    <?php
                                            endforeach;
                                        else: 
                                    ?>
                                            <tr>
                                                <td><span class="label label-info"><i class="fa fa-info"></i></span><?php ?></td>
                                                <td><?php echo $ordenes_laboratorio->RespuestaOrdenLab->nroDocumento?></td>
                                                <td><?php echo $ordenes_laboratorio->RespuestaOrdenLab->fechaMuestra?></td>
                                                <td><?php ?></td>
                                                <td style="font-size:13px"><?php echo $ordenes_laboratorio->RespuestaOrdenLab->establecimiento?></td>
                                                <td><?php ?></td>
                                                <td><?php echo $ordenes_laboratorio->RespuestaOrdenLab->tipoAtencion?></td>
                                                <td><?php ?></td>
                                                <td class="text-center">
                                                    <a target="_blank" href="/print_examen_laboratorio_lns/0/{{$ordenes_laboratorio->RespuestaOrdenLab->nroDocumento}}/{{$ordenes_laboratorio->RespuestaOrdenLab->fechaMuestra}}/{{$ordenes_laboratorio->RespuestaOrdenLab->orden}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                    <?php
                                        endif;
                                    endif;
                                    ?>

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
                <h5 class="modal-title">DETALLE DEL EXAMEN DE LABORATORIO. <span id="nrorecmodal"></span></h5>
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

    
    