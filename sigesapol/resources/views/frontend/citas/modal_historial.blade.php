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
    $('#example_receta').dataTable( {
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
                        <table class="table"  id="example_receta">
                        <thead>
                            <tr>
                                <!--<th style="width:50px">SELECCIONAR</th>>
                                <th>ASEGURADO</th>
                                <th>TIPO DOC</th-->
                                <th>NUMERO DOC</th>
                                <th>FECHA</th>
                                <th>HORA</th>
                                <th>ESTABLECIMIENTO</th>
                                <th>SERVICIO</th>
                                <th>CONSULTORIO</th>
                                <th>TIPO CITA</th>
                                <th>MEDICO</th>
                                <th>OBSERVACIÓN</th>
                                <th>ESTADO</th>
                                <th>RECETAS</th>
                                <th>ANULADO POR</th>
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
                                <td><?php echo $rowHorario->medico_nombre." ".$rowHorario->medico_paterno." ".$rowHorario->medico_materno?></td>
                                <td><?php echo $rowHorario->observacion?></td>
                                <td><?php echo $rowHorario->estado_cita?></td>
                                <td><?php 
                                      if (!($flagservicios == 0 && $rowHorario->id_servicio != $id_consultorio)):
                                        if ($rowHorario->id_estado_cita == 4 && $rowHorario->id_estado_reg != 0) {
                                        ?>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Recetas">
                                            <a href="javascript:void(0)" onClick="ver_det_cita({{$rowHorario->id}})" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                                          </div>
                                        <?php
                                        } else {
                                        ?>
                                          <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                        <?php 
                                        }
                                      endif;
                                    ?>
                                </td>
                                <td><?php echo (!empty($rowHorario->nombre_anula) && !empty($rowHorario->fecha_anulacion)) ? $rowHorario->nombre_anula . ' / <strong>' . date('d/m/Y H:i', strtotime($rowHorario->fecha_anulacion)) . '</strong>' : ''; ?></td>
                                <td class="text-center">
                                    <?php 
                                      if (!($flagservicios == 0 && $rowHorario->id_servicio != $id_consultorio)):
                                        if (($rowHorario->id_estado_cita == 1 || $rowHorario->id_estado_cita == 10) && $rowHorario->id_estado_reg != 0 && ($rowHorario->id_establecimiento == auth::user()->id_establecimiento)) {
                                        ?>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="Anular Cita">
                                            <a href="javascript:void(0)" onClick="anularCita({{$rowHorario->id}})" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
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
    