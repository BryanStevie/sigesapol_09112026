<?php
use App\Models\Cita;
?>
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


function guardarCita(id_medico,fecha_cita){

  var dni_beneficiario = $("#dni_beneficiario").val();
  var id_consultorio = $('#id_consultorio').val();
  var id_ipress = $('#id_ipress').val();
  var msg = "";
  var sw=0;
  
  if (msg != "") {
    bootbox.alert(msg);
    return false;
  }
  
  $.ajax({
    url: '/buscar_cita_beneficiario/' + fecha_cita + '/' + dni_beneficiario + '/' + id_ipress + '/' + id_consultorio,
    dataType: "json",
    success: function(result){
      if(result.sw == false){
        bootbox.alert(result.msg);
        return false;
      }

      var cita        = result.cita;
      var nro_cita_mes        = result.nro_cita_mes;
      
      if(nro_cita_mes!=undefined){
        if(id_consultorio!=21){
          if(nro_cita_mes == 5){//ESTADO PENDIENTE AUN NO HA SIDO ATENDIDO LA CITA
            bootbox.alert("El beneficiario tiene 5 citas programadas en el mes, se tiene que elegir otro mes");
            sw=1;
          }
        }
      } 
      if(cita[0]!=undefined){
        if(id_consultorio!=21){
          if(cita[0].id_estado_cita == 1){//ESTADO PENDIENTE AUN NO HA SIDO ATENDIDO LA CITA
            bootbox.alert("El beneficiario ya tiene o ha tenido una cita programada en el mes, se tiene que elegir otro mes");
            sw=1;
          }
        }
      }
  
      if(sw==0){
        bootbox.confirm({ 
            size: "small",
            message: "&iquest;Deseas guardar la cita?", 
            callback: function(result){
                if (result==true) {       
                 fn_save_cita(id_medico,fecha_cita);
                }
            }
        });  
      }
//      $('#calendar').fullCalendar('destroy'); fullCalendar();     
    }
  });
}


function fn_save_cita(id_medico,fecha_cita){
    
	var msg = "";
    var id_ipress = $('#id_ipress').val();
    var id_consultorio = $('#id_consultorio').val();
    var fecha_atencion = $('#fecha_atencion').val();
    var dni_beneficiario = $("#dni_beneficiario").val();
	if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";
    if(id_ipress==""){msg+="Debe ingresar una Ipress<br>";}
    if(id_consultorio==""){msg+="Debe ingresar un Consultorio<br>";}
    if(fecha_atencion==""){msg+="Debe ingresar una fecha de atencion<br>";}
   
    if(msg!=""){
        bootbox.alert(msg); 
        return false;
    }
	
	var correo = $('#correo').val();
  var correo = $('#correo').val();
  var domicilio = $('#domicilio').val();
  var celular = $('#celular').val();
  var nro_historia = $('#nro_historia').val();

	var msgLoader = "";
	if(correo == "")msgLoader = "Procesando, espere un momento por favor";
	else msgLoader = "Enviado correo, espere un momento por favor";
	
	$('#btnCita').prop("disabled",true);
	
	var heightBrowser = $(window).width()+500;
	
	$('.loader').css("opacity","0.8").css("height",heightBrowser).html("<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>"+msgLoader+"</div></div>");
    $('.loader').show();
	
    var fecha_atencion_original = $('#fecha_atencion').val();	
	
    $.ajax({
            url: "registrar_gestion_cita",
            type: "POST",
			data : $("#frmCita").serialize()+"&id_consultorio="+id_medico+"&fecha_cita="+fecha_cita,
            success: function (result) {
					$('#btnCita').prop("disabled",false);
					$('.loader').hide();

					location.href="ver_cita_registrada/"+result;

            }
    });
}

function buscarPaciente(fecha_cita){


  
  
}


    function mostrarCita(id_cita){
    $('#nrorecmodal').text(id_cita);
    $.ajax({
    url: '/ver_modal_cita_registrada/' + id_cita,
            async: false,
            success: function(result){
            $("#muestra-det-cita").html(result);
            },
            complete: function () {
            $("#myModalDetCita").modal();
            }
    });
    }



</script>

<body class="hold-transition skin-blue sidebar-mini">
  <div>
    <section class="content-header">
      <h1 style="margin:0px;padding:0px;margin-bottom:8px">
        <small style="font-size:20px;color:#000000">Programaci&oacute;n Medica: <?php echo date("d/m/Y", strtotime($fecha_atencion));?></small>
      </h1>
    </section>
		<section class="content">
      <div class="row">
			  <input type="hidden" name="fecha_atencion" id="fecha_atencion" value="<?php echo $fecha_atencion?>" />
			  <input type="hidden" name="id_ipress" id="id_ipress" value="<?php echo $id_ipress?>" />
			  <input type="hidden" name="id_consultorio" id="id_consultorio" value="<?php echo $id_consultorio?>" />
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-body">
				      <div class="table-responsive">
                <table class="table table-bordered table-sm" id="tablemodalm">
                  <thead>
                    <tr>      								
                      <?php 
                        $nombre='';
                        foreach($medicos as $row):
        									$medico_turno = new \App\Models\Medico_turno;      									
        									$subconsultorio_turno = $medico_turno->GetSubConsultorioTurnoByIdConsultorio($row->id);
        									foreach($subconsultorio_turno as $rowst):
        									  $horario = $medico_turno->GetHorarioConsultorioByIdConsultorioTurno($id_ipress,$rowst->id_sub_consultorio_turno,$fecha_atencion,$rowst->tiempo_atencion,$rowst->hora_ini,$rowst->hora_fin);        									  
        									  $horarioArray[$rowst->id_sub_consultorio_turno] = $horario;
        									endforeach;                          
                      ?>
                        
                        <th style="background:#e2e3e5"><?php 
                          if($nombre==''):
                            $nombre = $row->nombre;
                            echo $nombre;
                          else:
                            if($nombre!=$row->nombre){
                              $nombre = $row->nombre;
                              echo $nombre;
                            }
                          endif; ?>
                        </th>
                      <?php 
                          endforeach;?>
                    </tr>
                  </thead>
                  <tbody id="muestra-atenciones" style="padding-top:32px">
      							<tr>
      							 <?php

                        foreach($medicos as $row):?>
      							     <td style="padding:0px;margin:0px">
      							      <table class="table table-bordered table-sm" style="padding:0px;margin:0px;width:100%">
      							       <?php 
      									     $subconsultorio_turno = $medico_turno->GetSubConsultorioTurnoByIdConsultorioAndTurno($row->id,$row->turno);



      									      foreach($subconsultorio_turno as $rowst):
      									        foreach($horarioArray[$rowst->id_sub_consultorio_turno] as $rowHorario):
      									         $estadoCita = "";
      									         $estado = "";
      									         $color = "";
      									         $onclick = "";
            									   //print_r($rowst->id_sub_consultorio_turno);
                                 //print_r($rowHorario);
            									   //$cadMedico = explode(",",$rowHorario->id_medico);
            									   $cadMedicoCita = explode("#",$rowHorario->id_medico);
            									   $estadoCita = $cadMedicoCita[1];
            									   //$estadoCita = "0";
                                  if($estadoCita == 0){
            										    //$estado = "DISPONIBLE";
                                    //Buscar Paciente
            										    $estado = $rowHorario->fecha_hora." - ".$rowHorario->fecha_fin;
            										    $color = "background:#d4edda;color:#000000;font-size:11px;cursor:pointer";
                                    //if(count($citas)>0):
            										    //  $onclick = "#";
                                    //else:
                                      $onclick = "onclick=\"guardarCita(".$row->id.",'".$rowHorario->fecha_cita."')\" ";
                                    //endif;
                                    $dni='DISPONIBLE';		
            									    }
            									    else if($estadoCita >= 1){
            										        //$dni='';
                                        //$estado = "PROGRAMADO";
                                          $busca_citas = new Cita();
                                          $id_medico=$cadMedicoCita[0];
                                          $citas = $busca_citas->buscaPaciente($id_medico,$row->id_medico,$rowHorario->fecha_cita);
            										          $estado = $rowHorario->fecha_hora." - ".$rowHorario->fecha_fin;
            										          $color = "background:#fff3cd;color:#000000;font-size:11px;";
                                          $onclick = "onclick=\"mostrarCita(".$citas->id.")\" ";
                                          $dni=$citas->nro_doc_ident;
            									    }
      							       ?>
            							        <tr>
            							          <td>
            								          <?php if($estadoCita != "")echo $estado; ?>
            							          </td>
                                    
                                    @if ($fechaServidor<=$rowHorario->fecha_cita)
                                      <td <?php if($estadoCita != "")echo "style='".$color."' ".$onclick." ";?> >
                                        <?php if($estadoCita != "")echo $dni; ?>
                                      </td>
                                    @else
                                      <td>
                                        <?php if($estadoCita >= 1):
                                                  echo $dni; 
                                              else:
                                                  echo 'SIN REGISTRO'; 
                                              endif;?>
                                      </td>
                                    @endif
                    
            							        </tr>
      							        <?php 
                                endforeach;
                              endforeach;
                            ?>
      							      </table>
      							     </td>
      							<?php 
                        endforeach;?>
      							</tr>
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

  <div class="modal fade" id="myModalDetCita" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE LA CITA. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-cita"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

    
    