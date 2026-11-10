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


function guardarCita_(id_medico,fecha_cita){
    
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
        fn_save_cita_(id_medico,fecha_cita);
    }
}

function fn_save_cita_(id_medico,fecha_cita){
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
					/*
					$('#calendar').fullCalendar("refetchEvents");
					modalDelegar(fecha_atencion_original);
					*/
					location.href="ver_cita/"+id_user+"/"+result;

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
			  <input type="hidden" name="fecha_atencion" id="fecha_atencion" value="<?php echo $fecha_atencion?>" />
			  <input type="hidden" name="id_ipress" id="id_ipress" value="<?php echo $id_ipress?>" />
			  <input type="hidden" name="id_consultorio" id="id_consultorio" value="<?php echo $id_consultorio?>" />
        <div class="col-md-12">
          <div class="box box-primary">
            <br>
              <div class="box-body">
                <?php //print_r($medicos);?>
				<div class="table-responsive">
                    <table class="table table-bordered table-sm" id="tablemodalm">
                        <thead>
                            <tr>
								<th style="width:50px">SELECCIONAR</th>
								<th>FECHA</th>
                                <th>TURNO</th>
								<th>HORARIO</th>
								<th>MEDICO</th>
                            </tr>
                        </thead>
                        <tbody id="muestra-atenciones" style="padding-top:32px">
							<?php foreach($turno as $rowTurno):
									if($rowTurno->id == 2 || $rowTurno->id == 3):
									$fecha_atencion = date("Y-m-d");
									$hora = "";
									$medico = "";
									$fecha = "";
									$idmedico = "";
									$medico_turno = new \App\Models\Medico_turno;
									
									$horario = $medico_turno->GetHorarioByTurno($id_ipress,$id_consultorio,$fecha_atencion,$rowTurno->id,$consultorio->dura_cons);//horario de hoy
									if(!isset($horario)){//si no hay turno para hoy
										$existe_turno = $medico_turno->verificaHorarioTurno($id_ipress,$id_consultorio,$fecha_atencion,$rowTurno->id);//verifica si hay horarios futuros 
										if($existe_turno){//si existe turno para otro dia
											//$d=0;
											while (!isset($horario) /*&& $d<=50*/){
												$fecha_atencion = date("Y-m-d",strtotime($fecha_atencion."+ 1 days")); 
												$horario = $medico_turno->GetHorarioByTurno($id_ipress,$id_consultorio,$fecha_atencion,$rowTurno->id,$consultorio->dura_cons);
												//$fecha_atencion = date("Y-m-d",strtotime($fecha_atencion."+ 1 days"));
												//$d++;
											}
										}
									}
									
									//$existe_turno = $medico_turno->verificaHorarioTurno($id_ipress,$id_consultorio,$fecha_atencion,$rowTurno->id);//si hay futuras fechas programadas
									/*
									if($existe_turno){
										$d=0;
										while (!isset($horario) && $d<=50){
											//echo $fecha_atencion."|".$d."___";
											echo $rowTurno->id;
											$horario = $medico_turno->GetHorarioByTurno($id_ipress,$id_consultorio,$fecha_atencion,$rowTurno->id);
											$fecha_atencion = date("Y-m-d",strtotime($fecha_atencion."+ 1 days")); 
											$d++;
										}
									}
									*/
									
									if(isset($horario)){
										$datos = explode('#',$horario->horario);
										if(isset($datos[0]))$hora = $datos[0];
										if(isset($datos[1]))$fecha = $datos[1];
										if(isset($datos[2]))$idmedico = $datos[2];
										if(isset($datos[3]))$medico = $datos[3];
										unset($horario);
									}
							?>
							<tr>
								<td style="text-align:center" align="center"><input name="horario" value="<?php echo $fecha."#".$idmedico?>" type="radio" /></td>
								<td><?php if($fecha!="")echo date("d/m/Y", strtotime($fecha))?></td>
								<td><?php echo $rowTurno->descripcion?></td>
								<td><?php echo $hora?></td>
								<td><?php echo $medico?></td>
							</tr>
							<?php endif;?>
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
    
    