<title>Sistema de Farmacia</title>

<style>/*
.table-fixed thead,
.table-fixed tfoot{
  width: 97%;
}

.table-fixed tbody {
  height: 230px;
  overflow-y: auto;
  width: 100%;
}

.table-fixed thead,
.table-fixed tbody,
.table-fixed tfoot,
.table-fixed tr,
.table-fixed td,
.table-fixed th {
  display: block;
}

.table-fixed tbody td,
.table-fixed thead > tr> th,
.table-fixed tfoot > tr> td{
  float: left;
  border-bottom-width: 0;
}
*/
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

/*
tr:nth-child(2n) {
    background: none repeat scroll 0 0 #edebeb;
}  
*/

#tablemodalm{
    /*
    width: 30em;
    overflow-x: auto;
    white-space: nowrap;
    */
    
    /*background-color: #fed9ff; 
      width: 600px; 
      height: 150px; 
      overflow-x: hidden;
      overflow-y: auto; 
      text-align: center; 
      padding: 20px;*/
}
</style>

<script type="text/javascript">

$(document).ready(function() {
    $('#example_imagenes').dataTable( {
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                },
                "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        } );
});

function validacion(){
    
    var msg = "";
    var cobservaciones=$("#frmComentar #cobservaciones").val();
    
    if(cobservaciones==""){msg+="Debe ingresar una Observacion <br>";}
    
    if(msg!=""){
        bootbox.alert(msg); 
        return false;
    }
}

function guardarCita__(){
    alert("fdssf");
}

function guardarCita(id_medico,fecha_cita){
    
    var msg = "";
    var id_ipress = $('#id_ipress').val();
    var id_consultorio = $('#id_consultorio').val();
    var fecha_atencion = $('#fecha_atencion').val();
    var dni_beneficiario = $("#dni_beneficiario").val();
    //alert(id_ipress);
    if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";
    if(id_ipress==""){msg+="Debe ingresar una Ipress<br>";}
    if(id_consultorio==""){msg+="Debe ingresar un Consultorio<br>";}
    if(fecha_atencion==""){msg+="Debe ingresar una fecha de atencion<br>";}
   
    if(msg!=""){
        bootbox.alert(msg); 
        return false;
    }
    else{
        fn_save_cita(id_medico,fecha_cita);
    }
}

function fn_save_cita(id_medico,fecha_cita){
    /*
    var tipodoc_beneficiario = $('#tipodoc_beneficiario').val();
    var nrodocafiliado = $('#nrodocafiliado').val();
    var nrodocafiliado = $('#nrodocafiliado').val();
    var id_ipress = $('#id_ipress').val();
    var id_consultorio = $('#id_consultorio').val();
    */  
    var fecha_atencion_original = $('#fecha_atencion').val();
    
    $.ajax({
            url: "registrar_cita",
            type: "POST",
            //data:{id_medico:id_medico,id_ipress:id_ipress,id_consultorio:id_consultorio,fecha_atencion:fecha_cita},
            data : $("#frmCita").serialize()+"&id_medico="+id_medico+"&fecha_cita="+fecha_cita,
            success: function (result) {  
                    $('#openOverlayOpc').modal('hide');
                    //parent.$('#idMaestroPersona').val(result);
                    //parent.obtenerinformacionpersona();
                    
                    /*
                    var date = new Date();
                    var d = date.getDate();
                    var m = date.getMonth();
                    var y = date.getFullYear();
                    fullCalendar();
                    */
                    //$('#calendar').fullCalendar({ events: "cronograma_cita",    });
                    $('#calendar').fullCalendar("refetchEvents");
                    modalDelegar(fecha_atencion_original);

            }
    });
}

function ver_det_cita(id){
    $('#nrorecVmodal').text(id);
    $.ajax({
    url: '/mostrar_receta_cita/' + id,
            async: false,
            success: function(result){
            $("#muestra-det-recetaV").html(result);
            },
            complete: function () {
            $("#myModalRecetaV").modal();
            }
    });
}

</script>


<body class="hold-transition skin-blue sidebar-mini">

    <div>
        <!--
        <section class="content-header">
          <h1>
            <small style="font-size: 20px">Programados del Medicos del dia <?php //echo $fecha_atencion?></small>
          </h1>
        </section>
        -->
        <section class="content">
            <div class="row">
             
        <div class="col-md-12">
          <div class="box box-primary">
            <br>
              <div class="box-body">
                <?php //print_r($medicos);?>
                <div class="table-responsive">
                    <!--table class="table table-bordered table-sm" id="tablemodalm"-->
                        <table class="table"  id="example_imagenes">
                        <thead>
                            <tr>
                                <th>NUMERO DOC</th>
                                <th>FECHA</th>
                                <th>HORA</th>
                                <th>ESTABLECIMIENTO</th>
                                <th>SERVICIO</th>
                                <th>CONSULTORIO</th>
                                <th>TIPO CITA</th>
                                <th>REGISTRADOR</th>
                                <th>VALIDADOR</th>
                                <th>CPMS</th>
                                <th>OBSERVACIÓN</th>
                                <th>ESTADO</th>
                                <th>COMENTARIO</th>
                                <th>VISUALIZAR ORDEN</th>
                                @if($flagservicios == 0&& $id_consultorio == 114 && auth()->user()->flaghistoria== 1)
                                <th>PDF INFORME</th>
                                @endif
                                <th class="text-center">ANULAR</th>
                            </tr>
                        </thead>
                        <tbody id="muestra-det" style="padding-top:32px">
                            <?php foreach($horario as $rowHorario):
                                //$datos = explode('#',$rowHorario->horario);
                            ?>
                            <tr>
                                <!--<td style="text-align:center" align="center"><input name="horario" value="<?php //echo $datos[1]."#".$datos[2]?>" type="radio" /></td>>
                                <td><?php //echo $rowHorario->asegurado_nombre." ".$rowHorario->asegurado_paterno." ".$rowHorario->asegurado_materno?></td>
                                <td><?php //echo $rowHorario->tipo_doc_ident?></td-->
                                <td><?php echo $rowHorario->nro_doc_ident?></td>
                                <td><?php echo $rowHorario->dia?></td>
                                <td><?php echo $rowHorario->hora?></td>
                                <td><?php echo $rowHorario->establecimiento?></td>
                                <td><?php echo $rowHorario->servicio?></td>
                                <td><?php echo $rowHorario->consultorio?></td>
                                <td><?php echo $rowHorario->tipo_cita?></td>
                                <td><?php 
                                        // if ( $rowHorario->updated_nombre_digitador_radiologia !== null || $rowHorario->updated_nombre_digitador_radiologia !== '' ) {
                                        if (!empty($rowHorario->updated_nombre_digitador_radiologia)) {
                                            echo $rowHorario->updated_nombre_digitador_radiologia;
                                        } else if ( $rowHorario->created_nombre_digitador_radiologia !== null || $rowHorario->created_nombre_digitador_radiologia !== '' ) {
                                            echo $rowHorario->created_nombre_digitador_radiologia;
                                        }
                                    ?></td>
                                <td><?php 
                                        // if ( $rowHorario->updated_nombre_registrador_informe !== null || $rowHorario->updated_nombre_registrador_informe !== '' ) {
                                        if (!empty($rowHorario->updated_nombre_registrador_informe)) {
                                            echo $rowHorario->updated_nombre_registrador_informe;
                                        }else /*if ( $rowHorario->created_nombre_registrador_informe !== null || $rowHorario->created_nombre_registrador_informe !== '' )*/ {
                                            echo $rowHorario->created_nombre_registrador_informe;
                                        }
                                    ?></td>
                                <td>{{$rowHorario->codigo_rad}} - {{$rowHorario->descripcion_corta}}</td>
                                <td><?php echo $rowHorario->observacion?></td>
                                <td><?php echo $rowHorario->estado_cita?></td>
                                <td><?php echo $rowHorario->comentario?></td>
                                <!--?php dd($rowHorario)?-->
                                <!-- <td><a href="/ver_radiologia_imprimir/<!?php echo $rowHorario->id_rad; ?>" target="_blank" class="btn btn-primary btn-xs" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div></td></td> -->
                                <td style="text-align: center;">
                                    <?php if ($rowHorario->id_rad == null || $rowHorario->id_rad == ""): ?>
                                        <button class="btn btn-primary btn-xs" disabled>
                                        <i class="fa fa-download"></i>
                                        </button>
                                    <?php else: ?>
                                        <a href="/ver_radiologia_imprimir/<?php echo $rowHorario->id_rad; ?>" target="_blank" class="btn btn-primary btn-xs">
                                        <i class="fa fa-download"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                @if($flagservicios == 0 && $id_consultorio == 114 && auth()->user()->flaghistoria== 1)
                                <td style="text-align: center;">
                                    @if(!empty($rowHorario->ruta_prestacion_procedimiento) && !is_null($rowHorario->servicio) && $rowHorario->id_estado_cita != 5)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Informe<br>{{$rowHorario->servicio}}</b>">
                                            <button type="button" style="background-color: #c4e3c5; color: #2b620a; border:none;" class="btn btn-default btn-xs verPdf" id="verPdf"data-id-rad="{{$rowHorario->id_rad}}" data-id-servicio-rad="{{$rowHorario->id_servicio_radiologia}}" data-nombre-servicio-rad="{{$rowHorario->servicio}}" data-id-pp="{{$rowHorario->id_presproc}}">
                                                <i class="fa fa-file-text-o fa-lg"></i>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                                @endif
                                <td class="text-center">
                                    <?php 
                                      if (!($flagservicios == 0 && $rowHorario->id_servicio != $id_consultorio)):
                                        if (($rowHorario->id_estado_cita == 1 || $rowHorario->id_estado_cita == 10) && $rowHorario->id_estado_reg != 0 && $rowHorario->confirmar_toma_created_at == null && ($rowHorario->id_establecimiento == auth::user()->id_establecimiento)) {
                                        ?>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="Anular Cita">
                                            <a href="javascript:void(0)" onClick="anularCitaradiologia({{$rowHorario->id}})" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
                                          </div>
                                        <?php
                                        } else {
                                        ?>
                                          <button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>
                                        <?php 
                                        }
                                      endif;
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                
                <!--
                <div class="form-group">
                    <label for="exampleInputEmail1">Comentario</label>
                    <textarea class="form-control" name="cobservaciones" id="cobservaciones" placeholder="Asunto"></textarea>
                    <input type="hidden" name="creferencia" id="creferencia" value="${vista}" />
                </div>
                -->
              </div>
              
              <!-- /.box-body -->
              
              <!--<div class="box-footer">
                <input type="submit" class="btn btn-primary" value="Guardar">
              </div>-->
            
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
    <div class="modal fade" id="myModalRecetaV" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE lAS RECETAS</span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-recetaV"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
    