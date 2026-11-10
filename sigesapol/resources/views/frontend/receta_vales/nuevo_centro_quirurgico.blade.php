@extends('frontend.layouts.master')
@section('renderjs')

{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<style>

legend{
	font-size: 15px;
}
.ui-autocomplete {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none;
  float: left;
  min-width: 160px;
  padding: 5px 7px;
  margin: 2px 0 0;
  list-style: none;
  font-size: 15px;
  font-family:Arial, Helvetica, sans-serif;
  text-align: left;
  background-color: #ffffff;
  border: 1px solid #cccccc;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 4px;
  -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  background-clip: padding-box;
}

.ui-autocomplete > li > div {
  display: block;
  padding: 3px 20px;
  clear: both;
  font-weight: normal;
  line-height: 1.42857143;
  color: #333333;
  white-space: nowrap;
}

.ui-state-hover,
.ui-state-active,
.ui-state-focus {
  text-decoration: none;
  color: #FFFFFF!important;
  background: #1d71b8!important;
  cursor: pointer;
}

.ui-helper-hidden-accessible {
  border: 0;
  clip: rect(0 0 0 0);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
}


</style>

<script>

	function __autocomplete__AjaxBusqueda(){
		
		$("#nombre_diagnostico").autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
		$.ajax({
			url: '/obtener_diagnostico/' + request.term,
			dataType: "json",
			success: function(data)  {
			response(data);
		  }
			
		})
		
		  },select:  function(e, ui) {
				var id_diagnostico = ui.item.id;
				$('#id_diagnostico').val(id_diagnostico);
		  }
		});
		
		
	}
	
	function __autocomplete__AjaxBusqueda_procedimiento(){
		
		$("#nombre_procedimiento").autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
		$.ajax({
			url: '/obtener_procedimiento/' + request.term,
			dataType: "json",
			success: function(data)  {
			response(data);
		  }
			
		})
		
		  },select:  function(e, ui) {
				var id_procedimiento = ui.item.id;
				$('#id_procedimiento').val(id_procedimiento);
		  }
		});
		
		
	}
	
	
	
    $cmbUni = $("#frmcrear_prod").find("#id_unidad");
    $cmbUni.select2();
    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }
    
    
    $("#btnBuscar").on("click", function () {
        if ($('#frmBuscarBeneficiario').find("#dni_beneficiario").val() == "") {
            $("#respuesta").html("");
            $("#respuesta").append(
                '<p> Falta digitar el DNI</p>'
            ); 
            $('#squarespaceModal').modal();
            return false;
        }                
    });
	
	$("#btnBuscarAdmision").on("click", function () {
		var msg = "";
        if ($('#frmBuscarBeneficiario').find("#dni_beneficiario").val() == "") {
            $("#respuesta").html("");
            msg += '<p> Falta digitar el DNI</p>';
        }
		
		if ($('#frmBuscarBeneficiario').find("#nro_admision").val() == "") {
            msg += '<p> Falta seleccionar la Admisión</p>';
        }
		
		if(msg != ""){
			$("#respuesta").html("");
            $("#respuesta").append(msg); 
            $('#squarespaceModal').modal();
            return false;
		}
		
    });
	
	
	
	$(document).ready(function () {
		
		__autocomplete__AjaxBusqueda();
		__autocomplete__AjaxBusqueda_procedimiento();
		
		$("#id_medico_cirujano").select2({max_selected_options: 4});
		$("#id_medico_anestesiologo").select2({max_selected_options: 4});
		$("#id_medico_cirujano_ayudante").select2({max_selected_options: 4});
		$("#id_enfermera_1").select2({max_selected_options: 4});
		$("#id_enfermera_2").select2({max_selected_options: 4});
		
		//$("#id_admision").select2({max_selected_options: 4});
		/*
		$("#dni_beneficiario").keypress(function(event) {
			if(event.keyCode == 13){
				getAdmision();
			}
		});
		*/
		$("#dni_beneficiario").blur(function(event) {
			getAdmision();
		});
	
	});
	
	
	function getAdmision(){
        
		var dni_beneficiario = $('#dni_beneficiario').val();
		var id_farmacia = $('#id_farmacia').val();
		$('#nro_admision').html("<option value=''>--Seleccionar-</option>");
		
		$.ajax({
			url: '/obtener_admision_por_idfarmacia_dni/' + id_farmacia+ '/' + dni_beneficiario,
			success: function(result){                    
				var newOption = "";
				newOption = "<option value=''>--Seleccionar-</option>";
				$(result).each(function (ii, oo) {
					var turno = "";
					newOption += "<option value='"+oo.id+"'>FECHA: "+oo.fecha_admision+" - SERVICIO: "+oo.consultorio+" - TIPO DE ATENCION: "+oo.tipo_admision+" - TURNO: "+oo.turnos+" - MEDICO:	"+oo.nombre+", "+oo.paterno+" "+oo.materno+"</option>";
				});                 
				$('#id_admision').html(newOption);
				//$("#id_admision").select2({max_selected_options: 4});
			
			}
		});
        
    }
	
	function getDatosAdmision(){
		
		var id_admision = $('#id_admision').val();
		$.ajax({
			url: '/obtener_admision_por_id/' + id_admision,
			success: function(result){
				//alert(result[0].tipo_beneficiario);
				$('#tipo_beneficiario').val(result[0].tipo_beneficiario);
				$('#nro_historia').val(result[0].nro_historia);
				$('#fecha_admision').val(result[0].fecha_admision);
				
				$('#beneficiario').val(result[0].nombre_beneficiario+", "+result[0].paterno_beneficiario+" "+result[0].materno_beneficiario);
				$('#nombre_beneficiario').val(result[0].nombre_beneficiario);
				$('#paterno_beneficiario').val(result[0].paterno_beneficiario);
				$('#materno_beneficiario').val(result[0].materno_beneficiario);
				
				$('#id_consultorio').val(result[0].id_consultorio);
				$('#consultorio').val(result[0].consultorio);
				$('#edad').val(result[0].edad);
				/*
				var newOption = "";
				newOption = "<option value=''>--Seleccionar-</option>";
				$(result).each(function (ii, oo) {
					var turno = "";
					newOption += "<option value='"+oo.nro_admision+"'>FECHA: "+oo.fecha_admision+" - SERVICIO: "+oo.consultorio+" - TIPO DE ATENCION: "+oo.tipo_admision+" - TURNO: "+oo.turnos+" - MEDICO:	"+oo.nombre+", "+oo.paterno+" "+oo.materno+"</option>";
				});                 
				$('#nro_admision').html(newOption);
				$("#nro_admision").select2({max_selected_options: 4});
				*/
			}
		});
	
	}
	
	
	$("#btnReg").on("click", function () {
          
        if ($('#frmReceta').find("#dni_beneficiario").val() == "") {
			bootbox.alert("Falta Digitar el DNI del Paciente");
            return false;
        }                
        if ($('#frmReceta').find("#id_admision").val() == "") {
			bootbox.alert("Falta Seleccionar una admsion");
            return false;
        }        
        if ($('#frmReceta').find("#id_origen_paciente").val() == "") {
			bootbox.alert("Falta seleccionar el Origen del Paciente");
            return false;
        }
        if ($('#frmReceta').find("#id_sala").val() == "") {
			bootbox.alert("Falta seleccionar la Sala");
            return false;
        }
        
        if ($('#frmReceta').find("#id_consultorio").val() == "") {
			bootbox.alert("Falta seleccionar el Servicio");
            return false;
        }
        
        if ($('#frmReceta').find("#turno").val() == "") {
			bootbox.alert("Falta seleccionar el Turno");
            return false;
        }
        
        if ($('#frmReceta').find("#tipo_anestesia").val() == "") {
			bootbox.alert("Falta seleccionar el Tipo de Anestesia");
            return false;
        }
                
        
        if ($('#frmReceta').find("#id_diagnostico").val() == "") {
			bootbox.alert("Falta seleccionar el diagnostico (CIE10)");
            return false;
        }
		
		if ($('#frmReceta').find("#id_procedimiento").val() == "") {
			bootbox.alert("Falta seleccionar el procedimiento");
            return false;
        }
		
        if ($('#frmReceta').find("#id_medico_cirujano").val() == "") {
			bootbox.alert("Falta seleccionar el Medico Cirujano");
            return false;
        }
		
		bootbox.confirm("Aviso: Revise la informacion antes de registrar. ¿Desea Grabar?.", function(result) {
		if (result==true) {
			$("#frmReceta").submit();
		}
		
		});
		return false;
        
		
    });
	
	
	
</script>
@endsection
@section('content')
<div class="container">
	<?php //print_r($cita);?>
    <div class="row">        
		<div class="col-sm-1 col-md-1"></div>
        <div class="col-sm-10 col-md-10">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">MODULO DE GESTION DE CENTRO QUIRURGICO</h3>
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('registrar_centro_quirurgico')}}" id="frmReceta" autocomplete="off">
						<!--  action="{{ route('registrar_receta_rua')}}" -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
							
                            <div class="form-group">
                                <div class="col-md-2" style="position:relative">
                                    <img src="../../images/logo_sanidad.png" width="70" style="position:absolute;top:-20px">
                                </div>
                                <div class="col-md-6" style="text-align: center">
                                    <label style="font-size: 15px">{{$farmacia->nombre}}</label><br>
                                    <label style="font-size: 24px">CENTRO QUIRURGICO</label><br>
                                </div>
								
                                <div class="col-md-4">
                                    <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
									
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">                                    
                                    <input type="hidden" value="dni" name="tipo">
									
                                </div>
                            </div>
                            
                            <div class="form-group">
							
								<div class="col-md-3">
                                    <label>DNI</label>
                                    <input type="text" class="form-control" value="" name="dni_beneficiario" id="dni_beneficiario" tabindex="1" maxlength="8" >
                                </div>
								
                                <div class="col-md-3">
									<label>ADMISI&Oacute;N</label>
                                    <select class="form-control" id="id_admision" name="id_admision" onchange="getDatosAdmision()" tabindex="2">
										<option value="">Seleccionar Admisi&oacute;n</option>
									</select>  
									
                                </div>
								
								<div class="col-md-3">
                                    <label>FEC. ADMISI&Oacute;N</label>
                                    <input type="text" class="form-control" value="" name="fecha_admision" id="fecha_admision" readonly="readonly">
                                </div>
								
								<div class="col-md-3">
                                    <label>FECHA DE CIRUGIA</label>
                                    <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor));?>" name="fecha_quirurgico" id="fecha_quirurgico">
                                </div>
								
                            </div>  
							
							<div class="form-group">
								
								<div class="col-md-3">
                                    <label>TIPO DE BENEFICARIO</label>
                                    <input readonly="" value="" type="text" name="tipo_beneficiario" id="tipo_beneficiario" class="form-control">
                                </div>
								
								<div class="col-md-2">
                                    <label>HISTORIA CLINICA</label>
                                    <input type="text" class="form-control" value="" id="nro_historia" name="nro_historia" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" readonly="" >
                                </div>
								
								<div class="col-md-1" style="padding:0px;margin:0px">
                                    <label>EDAD</label>
                                    <input type="text" class="form-control" value="" id="edad" name="edad" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" readonly="" >
                                </div>
								
								<div class="col-md-6">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <input readonly="" type="text" class="form-control" value="" id="beneficiario" name="beneficiario" >
                                    <input type="hidden" class="form-control" value="" id="nombre_beneficiario" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="" id="paterno_beneficiario" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="" id="materno_beneficiario" name="materno_beneficiario">
                                </div> 
								
								
                            </div>
							
							
							<div class="form-group">
								
								<div class="col-md-3">
                                    <label>ORIGEN DEL PACIENTE</label>
                                    <select class="form-control" id="id_origen_paciente" name="id_origen_paciente" tabindex="3">
										<option value="">--Seleccionar--</option>
										<!--
                                        <option value="HOSPITALIZACION">HOSPITALIZACION</option>
                                        <option value="CLINICIA DEL DIA">CLINICIA DEL DIA</option>
										<option value="EMERGENCIA">EMERGENCIA</option>
										-->
										<?php foreach($origen as $rowOrigen):?>
											<option value="<?php echo $rowOrigen->id?>"><?php echo $rowOrigen->nombre?></option>
										<?php endforeach;?>
                                    </select>
									
                                </div>
								
								<div class="col-md-3">
                                    <label>SALA</label>
									<?php //$sala = array("I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII","XIII","XIV","XV","XVI","XVII","XVII","XIX","XX");?>
                                    <select class="form-control" id="id_sala" name="id_sala" tabindex="4">                                        
										<option value="">--Seleccionar--</option>
										<?php foreach($sala as $rowSala):?>
											<option value="<?php echo $rowSala->id?>"><?php echo $rowSala->nombre?></option>
										<?php endforeach;?>
                                	</select>
                                </div> 
								
                                <div class="col-md-6">
                                    <label>SERVICIOS</label>
									<!--
                                    <select class="form-control" id="id_consultorio" name="id_consultorio" tabindex="5">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($consultorio as $c)
                                        <option value="{{$c->id}}">{{$c->nombre}}</option>
                                        @endforeach
                                    </select>
									-->
									<input type="text" class="form-control" value="" name="consultorio" id="consultorio" readonly="readonly" tabindex="-1">
									<input type="hidden" class="form-control" value="" name="id_consultorio" id="id_consultorio" tabindex="-1">
									
                                </div>
							
							</div>
							
							<div class="form-group">
								
								<div class="col-md-3">
                                    <label>TURNO</label>
                                    <select class="form-control" name="turno" id="turno"  tabindex="5">
										<option value="">--Seleccionar--</option>
										<option value="M">MAÑANA</option>
										<option value="T">TARDE</option>
                                    </select>
                                </div>
								
								<div class="col-md-3">
									<label>TIPO DE ANESTESIA</label>
									<select class="form-control" name="tipo_anestesia" id="tipo_anestesia"  tabindex="6">
										<option value="">--Seleccionar--</option>
										<option value="GENERAL">GENERAL</option>
										<option value="REGIONAL">REGIONAL</option>
										<option value="LOCAL">LOCAL</option>
										<option value="EFECTIVA">EFECTIVA</option>
                                    </select>
								</div>
								
								<div class="col-md-6">
								<label>DIAGNOSTICO</label>
								<input type="text" class="form-control typeahead tt-query" id="nombre_diagnostico" name="nombre_diagnostico" tabindex="7">
								<input type="hidden" class="form-control typeahead tt-query" id="id_diagnostico" name="id_diagnostico">
								</div>
								
							</div>
							
							<div class="form-group">
							
								<div class="col-md-6">
									<label>PROCEDIMIENTO</label>
									<input type="text" class="form-control typeahead tt-query" id="nombre_procedimiento" name="nombre_procedimiento" tabindex="7">
									<input type="hidden" class="form-control typeahead tt-query" id="id_procedimiento" name="id_procedimiento">
								</div>
								
								<div class="col-md-6">
                                    <label>MEDICO CIRUJANO (DNI | CMP | Nombre y Apellidos)</label>
                                    <select class="form-control" name="id_medico_cirujano" id="id_medico_cirujano" tabindex="8">
                                        <option value="">--Seleccionar--</option>
										<?php foreach($medico as $rowMedico):?>
											<option value="<?php echo $rowMedico->id?>"><?php echo $rowMedico->paterno." ".$rowMedico->materno." ".$rowMedico->nombre." - DNI: ".$rowMedico->dni." - CMP: ".$rowMedico->colegiatura?></option>
										<?php endforeach;?>
                                    </select>
                                </div>
								
							</div>
							
							<div class="form-group">
								
								<div class="col-md-6">
                                    <label>ANESTESIOLOGO (DNI | CMP | Nombre y Apellidos)</label>
                                    <select class="form-control" name="id_medico_anestesiologo" id="id_medico_anestesiologo" tabindex="10">
                                        <option value="">--Seleccionar--</option>
										<?php foreach($medico as $rowMedico):?>
											<option value="<?php echo $rowMedico->id?>"><?php echo $rowMedico->paterno." ".$rowMedico->materno." ".$rowMedico->nombre." - DNI: ".$rowMedico->dni." - CMP: ".$rowMedico->colegiatura?></option>
										<?php endforeach;?>
                                    </select>
                                </div>
								
								<div class="col-md-6">
									<label>CIRUJANO AYUDANTE (DNI | CMP | Nombre y Apellidos)</label>
                                    <select class="form-control" name="id_medico_cirujano_ayudante" id="id_medico_cirujano_ayudante" tabindex="11">
                                        <option value="">--Seleccionar--</option>
										<?php foreach($medico as $rowMedico):?>
											<option value="<?php echo $rowMedico->id?>"><?php echo $rowMedico->paterno." ".$rowMedico->materno." ".$rowMedico->nombre." - DNI: ".$rowMedico->dni." - CMP: ".$rowMedico->colegiatura?></option>
										<?php endforeach;?>
                                    </select>
								</div>
								
							</div>
							
							
							<div class="form-group">
								
								<div class="col-md-6">
                                    <label>ENFERMERA 01 (DNI | CMP | Nombre y Apellidos)</label>
                                    <select class="form-control" name="id_enfermera_1" id="id_enfermera_1" tabindex="12">
                                        <option value="">--Seleccionar--</option>
										<?php foreach($enfermera as $rowMedico):?>
											<option value="<?php echo $rowMedico->id?>"><?php echo $rowMedico->paterno." ".$rowMedico->materno." ".$rowMedico->nombre." - DNI: ".$rowMedico->dni." - CMP: ".$rowMedico->colegiatura?></option>
										<?php endforeach;?>
                                    </select>
                                </div>
								
								<div class="col-md-6">
									<label>ENFERMERA 02 (DNI | CMP | Nombre y Apellidos)</label>
                                    <select class="form-control" name="id_enfermera_2" id="id_enfermera_2" tabindex="13">
                                        <option value="">--Seleccionar--</option>
										<?php foreach($enfermera as $rowMedico):?>
											<option value="<?php echo $rowMedico->id?>"><?php echo $rowMedico->paterno." ".$rowMedico->materno." ".$rowMedico->nombre." - DNI: ".$rowMedico->dni." - CMP: ".$rowMedico->colegiatura?></option>
										<?php endforeach;?>
                                    </select>
								</div>
								
							</div>
							
							
							<br />
							
							<div class="form-group">
								<div class="col-md-4"></div>
								<div class="col-md-2">
								<a href="/nuevo_centro_quirurgico" class="form-control btn btn-danger" tabindex="15">CANCELAR</a>
								</div>
								<div class="col-md-4">
								<input id="btnReg" class="form-control btn btn-success" type="submit" value="REGISTRAR CENTRO QUIRURGICO" tabindex="14"> 
								</div>
								<div class="col-md-3"></div>
							</div>
									
							
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection