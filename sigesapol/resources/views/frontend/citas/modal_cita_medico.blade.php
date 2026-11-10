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
    
	bootbox.confirm({ 
        size: "small",
        message: "&iquest;Deseas guardar la cita?", 
        callback: function(result){
            if (result==true) {
                //fn_anularCita(id_cita);
				fn_save_cita(id_medico,fecha_cita);
            }
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
	var msgLoader = "";
	if(correo == "")msgLoader = "Procesando, espere un momento por favor";
	else msgLoader = "Enviado correo, espere un momento por favor";
	
	$('#btnCita').prop("disabled",true);
	
	var heightBrowser = $(window).width()+500;
	
	$('.loader').css("opacity","0.8").css("height",heightBrowser).html("<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>"+msgLoader+"</div></div>");
    $('.loader').show();
	
    var fecha_atencion_original = $('#fecha_atencion').val();
	var id_user = $('#id_user').val();
	
    $.ajax({
            url: "registrar_cita",
            type: "POST",
			data : $("#frmCita").serialize()+"&id_medico="+id_medico+"&fecha_cita="+fecha_cita,
            success: function (result) {
					$('#btnCita').prop("disabled",false);
					$('.loader').hide();
                    //$('#openOverlayOpc').modal('hide');
					location.href="ver_cita/"+id_user+"/"+result;

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
								<th style="background:#FFFFFF;width:10px;border:0px"></th>
                                <th style="background:#e2e3e5;width:80px">HORA</th>
								<?php foreach($medicos as $row):?>
                                <th style="background:#e2e3e5"><?php echo $row->nombre." ".$row->paterno." ".$row->materno?></th>
                                <?php endforeach;?>
                            </tr>
                        </thead>
                        <tbody id="muestra-atenciones" style="padding-top:32px">
							
							<?php foreach($turno as $rowTurno):
								$medico_turno = new \App\Models\Medico_turno;
								$horario = $medico_turno->GetHorarioMedicoByIdIpressAndIdConsultorio($id_ipress,$id_consultorio,$fecha_atencion,$rowTurno->id,$consultorio->dura_cons);
							?>
							<?php foreach($horario as $key=>$rowHorario):
								if($rowTurno->id == 2 || $rowTurno->id == 3):
							?>
							<tr>
								<?php
								if($rowTurno->id == 2 && $key == 0)echo "<td rowspan=".count($horario)." style='letter-spacing:15px;text-align:center;vertical-align:middle;font-weight:bold;font-size:14px;background:#D9EDF7'><SPAN STYLE='writing-mode: vertical-lr;-ms-writing-mode: tb-rl;transform: rotate(180deg);'>MA&Ntilde;ANA</SPAN></td>";
								if($rowTurno->id == 3 && $key == 0)echo "<td rowspan=".count($horario)." style='letter-spacing:15px;text-align:center;vertical-align:middle;font-weight:bold;font-size:14px;background:#fff3cd'><SPAN STYLE='writing-mode: vertical-lr;-ms-writing-mode: tb-rl;transform: rotate(180deg);'>TARDE</SPAN></td>";
								?>
							
								<td style="font-weight:bold;font-size:11px;background:#e2e3e5"><?php echo $rowHorario->fecha_hora?></td>
								<?php foreach($medicos as $row):
										$cadMedico = explode(",",$rowHorario->id_medico);
										$estadoCita = "";
										foreach($cadMedico as $rowcad):
											$cadMedicoCita = explode("#",$rowcad);
											if($cadMedicoCita[0] == $row->id_medico)$estadoCita = $cadMedicoCita[1];
										endforeach;
										
										$estado = "";
										$color = "";
										$onclick = "";
										if($estadoCita == 0){
											//if($rowHorario->situacion == 1){
												//$estado = "DISPONIBLE";
												//$color = "background:#e2e3e5;color:#000000;font-size:11px";
											//}else{
												$estado = "DISPONIBLE";
												$color = "background:#d4edda;color:#000000;font-size:11px;cursor:pointer";
												$onclick = "onclick=\"guardarCita(".$row->id_medico.",'".$rowHorario->fecha_cita."')\" ";
											//}
											
										}
										else if($estadoCita == 1){
											$estado = "PROGRAMADO";
											$color = "background:#fff3cd;color:#000000;font-size:11px;";
										}
										
								?>
									<td <?php if($estadoCita != "")echo "style='".$color."' ".$onclick." ";?> >
										<?php if($estadoCita != "")echo $estado; ?>
									</td>
                                <?php endforeach;?>
							</tr>
							<?php endif;?>
							<?php endforeach;?>
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
    
    