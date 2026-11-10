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
    $("#id_servicio_asignar").select2({width: 'resolve'});
	$("#id_consultorio_asignar").select2({width: 'resolve'});
	$('#btnAsignarCita').on("click", function (e) {
		guardarAsignarCita();
	});
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

function guardarAsignarCita(){
    
    var msg = "";
    var id_servicio_asignar = $('#id_servicio_asignar').val();
    var id_consultorio_asignar = $('#id_consultorio_asignar').val();
	var id_medico = $('#id_medico_asignar').val();
    //alert(id_servicio_asignar);
	if(id_servicio_asignar=="0")msg += "Debe ingresar un Servicio <br>";
    if(id_consultorio_asignar=="0"){msg+="Debe ingresar un Consultorio<br>";}
	if(id_medico==""){msg+="Debe programar un Medico en el Consultorio seleccionado<br>";}
    
    if(msg!=""){
        bootbox.alert(msg); 
        return false;
    }
    else{
        fn_save_asignar_cita();
    }
}

function fn_save_asignar_cita(){
    
	var id_consultorio_asignar = $('#id_consultorio_asignar').val();
	var id_medico = $('#id_medico_asignar').val();
    $.ajax({
            url: "asignar_cita",
            type: "POST",
			data : $("#frmCita").serialize()+"&id_consultorio="+id_consultorio_asignar+"&id_medico="+id_medico,
            success: function (result) {  
                    $('#openOverlayOpc').modal('hide');
					parent.fn_ListarBusqueda();
					parent.addMovimientoAll();

            }
    });
}

function getSubConsultorioAsignar() {

	var id_establecimiento = parent.$('#id_establecimiento').val();
	var id_servicio = $('#id_servicio_asignar').val();
	//var id_tipo_atencion = $('#id_tipo_atencion').val();
	$.ajax({
			url: '/obtener_sub_consultorio_por_id_establecimiento_and_id_servicio/' + id_establecimiento + '/' + id_servicio,
			success: function (result) {
			var newOption = "";
			var reg = result.length;
			if(reg >= 2){
				newOption = "<option value='0'>--Seleccionar-</option>";
			}
			$(result).each(function (ii, oo) {
				newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
			});
			$('#id_consultorio_asignar').html(newOption);
			$("#id_consultorio_asignar").select2({max_selected_options: 4});
		}
	});

}

function getMedicoAsignar() {
	
	var id_establecimiento = parent.$('#id_establecimiento').val();
	var id_consultorio_asignar = $('#id_consultorio_asignar').val();
	var fecha = parent.$('#fecha_ini').val();
	
	$.ajax({
			url: '/obtener_medico_por_id_establecimiento_and_id_sub_consultorio/' + id_establecimiento + '/' + id_consultorio_asignar + '/' + fecha,
			success: function (result) {
				$("#medico").val("");
				$("#id_medico_asignar").val("");
				if(result!=""){
					var medico = result[0]["medico"];
					var id_medico = result[0]["id_medico"];
					$("#medico").val(medico);
					$("#id_medico_asignar").val(id_medico);
				}
			
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
					
					<form name="frmAsignarCita" id="frmAsignarCita" class="form-horizontal" method="post" action="{{ route('asignar_cita')}}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					
					<input type="hidden" name="id" id="id" value="<?php //echo $id?>" />
					<input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
					<div class="form-group">
						<label class="col-lg-3 control-label">Servicio</label>
						<div class="col-lg-9">
							<select class="form-control input-sm" name="id_servicio_asignar" id="id_servicio_asignar" onchange="getSubConsultorioAsignar()">
								<option value="0">-Seleccionar-</option>
								<?php foreach($consultorio as $row):?>
								<option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-lg-3 control-label">Consultorio</label>
						<div class="col-lg-9">
							<select class="form-control input-sm" name="id_consultorio_asignar" id="id_consultorio_asignar" required="" onchange="getMedicoAsignar()">
								<option value="0">-Seleccionar-</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-lg-3 control-label">Medico</label>
						<div class="col-lg-9">
							<input type="text" value="" name="medico" id="medico" readonly="readonly" class="form-control" placeholder="" >
							<input type="hidden" name="id_medico_asignar" id="id_medico_asignar" value="<?php //echo $id?>" />
						</div>
					</div>
					
					<div style="margin-top:10px;float:right" class="form-group">
						<div class="col-sm-12 controls">
							<input type="button" value="Guardar" class="btn btn-success" id="btnAsignarCita">
							<a href="/all_citas_medico_paciente" class="btn btn-danger">Cancelar</a>
						</div>
					</div> 
					</form>
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
    
    