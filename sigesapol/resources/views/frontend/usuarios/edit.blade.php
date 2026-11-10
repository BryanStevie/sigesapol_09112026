@extends('frontend.layouts.master')
@section('renderjs')
<style>
.solomayusculas{
     
       text-transform:uppercase;
    }
 </style>
 
<script type="text/javascript">
$("#divmedico").hide();
$("#divmedico2").show();

$(document).ready(function () {

	$('#id_consultorio').select2();
	$('#id_establecimiento').select2();
	let id_farmacia = $('#id_farmacia').val();
	var id_establecimiento = $('#id_establecimiento').val();
	
	if (id_establecimiento !== '76') {
		$('#totems').hide();
		
		}
	if (id_farmacia == '121') {
		$('#divLlamadaCita').show();
	}
	if (id_farmacia !== '121') {
		$('#divLlamadaCita').hide();
		$('#id_llamadaCita').val('0');
	}
	
	if ($('#flagmodulollamado').is(':checked')) {
		$('#servicio_ticket').show();
		if ($('#id_servicio_ticket').val() == 114) {
			$('#sub_consultorio_ticket').show();
		}
	}else {
		$('#servicio_ticket').hide();
		$('#sub_consultorio_ticket').hide();
	}

	if ($('#id_servicio_ticket').val() == 114) {
		$('#sub_consultorio_ticket').show();
	} else {
		$('#sub_consultorio_ticket').hide();
	}

	$('#flagmodulollamado').on('change', function () {
			if ($(this).is(':checked')) {
				$('#servicio_ticket').show();
				if ($('#id_servicio_ticket').val() == 114) {
					$('#sub_consultorio_ticket').show();
				}
			}else {
				$('#servicio_ticket').hide();
				$('#sub_consultorio_ticket').hide();
			}
	});
	
	$('#id_servicio_ticket').on('change', function () {
		if ($(this).val() == 114) {
			id_establecimiento = $('#id_establecimiento').val();
			id_servicio = $('#id_servicio_ticket').val();
			$('#id_sub_consultorio_ticket').html('').addClass('form-control');
            $.ajax({
                url: '/get_sc_servicio_ticket/' + id_establecimiento + '/' + id_servicio,
                success: function(result) {
                    let newOption = "";
                    newOption = "<option value='0'>--Seleccione--</option>";
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.descripcion + "</option>";
                    });
                    $('#id_sub_consultorio_ticket').html(newOption);
                }
            });
			$('#sub_consultorio_ticket').show();
		} else {
			$('#sub_consultorio_ticket').hide();
		}
	});

	if($('#flag_consultor').is(":checked")){
		$("#consultor_views").show();
	}else{
		$("#consultor_views").hide();
	}

	$("#flag_consultor").on('change', function(){
		if($('#flag_consultor').is(":checked")){
			$("#consultor_views").show();
		}else{
			$("#consultor_views").hide();
		}
	})
	
});

function validarDni() {
        var msg = "";
        var sw = true;
        var dni = $('#dni').val();
        var len = dni.length;
        var id_establecimiento = $('#id_establecimiento').val();
        var id_farmacia = $('#id_farmacia').val();

        if (len != 8) {
            msg += "En Dni tiene que ser de 8 digitos.<br/>";
            sw = false
        }

        if ($('#flagestablecimientos').is(':checked')) {
        } else {
            if (id_establecimiento == "0") {
                msg += "Seleccione un Hospital y elija la opcion Todos de ser necesario.<br/>";
                sw = false
            }
        }
        if ($('#flagfarmacias').is(':checked')) {

        } else {
            if (id_farmacia == "0") {
                msg += "Seleccione una Farmacia y elija la opcion Todos de ser necesario.<br/>";
                sw = false
            }
        }
		
		if (tipo_registro == "") {
			msg += "Seleccione un Tipo de registro.<br/>";
			sw = false
		}
		
        if (sw == false) {
            bootbox.alert(msg);
            return sw;
        } else {
            submitFrm();
        }
        return false;
    }

function submitFrm() {
	bootbox.confirm({
		message: "Se registraran los registros ingresados, Esta seguro de continuar?",
		buttons: {
			confirm: {
				label: 'Si',
				className: 'btn-success'
			},
			cancel: {
				label: 'No',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if (result == true) {
				document.frm.submit();
			}
		}
	});
}

function getFarmacia(){
	
	var id_establecimiento = $('#id_establecimiento').val();

	$.ajax({
		url: '/obtener_farmacia_por_id_establecimiento/' + id_establecimiento,
		success: function(result){                    
			var newOption = "";
			newOption = "<option value='0'>--Seleccionar-</option>";
			$(result).each(function (ii, oo) {
				newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
			});                 
			$('#id_farmacia').html(newOption);
			$("#id_farmacia").select2({max_selected_options: 4});
		
		}
	});
	var id_establecimiento = $('#id_establecimiento').val(); 
	if (id_establecimiento !== '76') {
		$('#totems').hide();
            
		}else{$('#totems').show();}
}

function getFarmaciaEdit(id_establecimiento,id_farmacia){
	
	$.ajax({
		url: '/obtener_farmacia_por_id_establecimiento/' + id_establecimiento,
		success: function(result){                    
			var newOption = "";
			newOption = "<option value='0'>--Seleccionar-</option>";
			$(result).each(function (ii, oo) {
				var sel = "";
				if(oo.id == id_farmacia)sel = "selected='selected'";
				newOption += "<option value='"+oo.id+"' "+sel+" >"+oo.nombre+"</option>";
			});                 
			$('#id_farmacia').html(newOption);
			$("#id_farmacia").select2({max_selected_options: 4});
		
		}
	});

}

<?php //if($usuarios->id_farmacia > 0):?>
	getFarmaciaEdit(<?php echo $id_establecimiento?>,<?php echo $usuarios->id_farmacia?>);
<?php //endif;?>

function getHabilitaMedico2(){
    var tipo_registro        = $('#tipo_registro').val();

    if(tipo_registro=='1'){
        $("#divmedico").show();
        $("#divmedico2").show();
    }
    else{
        $("#divmedico").hide();
        $("#divmedico2").hide();
    }
}
$('#id_farmacia').change(function (e) { 
	e.preventDefault();
	let id_farmacia = $('#id_farmacia').val();
	if (id_farmacia == '121') {
		$('#divLlamadaCita').show();
	}
	if (id_farmacia !== '121') {
		$('#divLlamadaCita').hide();
		$('#id_llamadaCita').val('0');
	}
});


/*
$('#id_consultorio').change(function (e) { 
	e.preventDefault();
	let id_consultorio = $('#id_consultorio').val();
	let vent = $('#id_vent').val();
	$.ajax({
		type: "GET",
		url: "/obtenerVentanilla/"+ id_consultorio,
		success: function (response) {
			var newOption = "";
			newOption = "<option value=''>--Seleccionar-</option>";
			$(response).each(function (ii, oo) {
				var sel = "";
				if(oo.id == vent)sel = "selected='selected'";
				newOption += "<option value='"+oo.id+"' "+sel+" >"+oo.nombre+"</option>";
			});                 
			$('#id_ventanilla').html(newOption);
			$("#id_ventanilla").select2({max_selected_options: 4});
		}
	});
});

$('#id_farmacia').change(function (e) { 
	e.preventDefault();
	let id_farmacia = $('#id_farmacia').val();
	if (id_farmacia == '121') {
		$('#divVentanilla').show();
	}
	if (id_farmacia !== '121') {
		$('#divVentanilla').hide();
		$('#id_ventanilla').val('0');
	}
});
*/
</script>


@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_usuario"><i class="fa fa-table fa-fw"></i> Usuarios</a></li>
                <li><a href="/create_usuario"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-10 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar Usuario</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form name="frm" id="frm" class="form-horizontal" role="form" method="post"  action="{{ route('update_usuario') }}" onsubmit="return validarDni()">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" class="form-control" name="id"  value="{{$usuarios->id}}"> 
						
                        <div class="form-group">
							<label class="col-lg-2 control-label">Documento de Identidad</label>
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->dni?>" id="dni" name="dni" required="" class="form-control" placeholder="DNI"  maxlength="8" onkeypress="return IsNumber(event)">
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('name', trans('validation.attributes.name'), ['class' => 'col-lg-2 control-label']) !!}
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->name?>" name="name" required="" class="form-control" placeholder="Nombre Completo" onkeypress="return solo_letra(event)" style="text-transform: uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Apellido Paterno</label>
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->paterno?>" name="paterno" required="" class="form-control" placeholder="Apellido Paterno" onkeypress="return solo_letra(event)" style="text-transform: uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Apellido Materno</label>
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->materno?>" name="materno" required="" class="form-control" placeholder="Apellido Materno" onkeypress="return solo_letra(event)" style="text-transform: uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
							</div>
						</div>        
						<div class="form-group">
							{!! Form::label('email', trans('validation.attributes.email'), ['class' => 'col-lg-2 control-label']) !!}
							<div class="col-lg-10">
								<input type="email" value="<?php echo $usuarios->email?>" name="email" required="" class="form-control" placeholder="E-mail">
							</div>
						</div>
						
           					<div class="form-group">
							<label class="col-lg-2 control-label">Nivel de Usuario</label>
								<div class="col-lg-10">
								<select class="form-control" id="nivel_user"  name="nivel_user" tabindex="5" required="" style="width: 100%">
								@if($user->nivel_user == 3)
									<option value="" selected>-Seleccionar-</option>
									<option value="1" <?php if($usuarios->nivel_user == 1)echo "selected='selected'";?>>Usuario Final</option>
									<option value="2" <?php if($usuarios->nivel_user == 2)echo "selected='selected'";?>>Usuario Administrador</option>
									<option value="3" <?php if($usuarios->nivel_user == 3)echo "selected='selected'";?>>Super Usuario</option>
									@elseif($user->nivel_user == 2 and $usuarios->nivel_user == 2 ):

										<option value="2" desible <?php if($usuarios->nivel_user == 2)echo "selected='selected'";?>>Usuario Administrador</option>
									@elseif($user->nivel_user == 2 and $usuarios->nivel_user == 3 ):

										<option value="3" <?php if($usuarios->nivel_user == 3)echo "selected='selected'";?>>Super Usuario</option>


									@else:
										<option value="" selected>-Seleccionar-</option>
										<option value="1" <?php if($usuarios->nivel_user == 1)echo "selected='selected'";?>>Usuario Final</option>
									@endif
									</select>
								</div>
							</div>
							
						<div class="form-group">
							<label class="col-lg-2 control-label">Establecimiento</label>
							<div class="col-lg-9">
								<select class="form-control" name="id_establecimiento" id="id_establecimiento" onchange="getFarmacia()" <?php if(Auth::user()->flagestablecimientos!=1): echo "disabled='disabled'"; endif;?> required="">
									<option value="0">-Seleccionar-</option>
									@foreach($establecimientos as $f)
									<option value="{{$f->id}}" <?php if($f->id == $id_establecimiento)echo "selected='selected'";?> >{{$f->codigo}} - {{$f->nombre}}</option>
									@endforeach
								</select>
							</div>
							@if(Auth::user()->flagestablecimientos==1)
							<div class="col-lg-1" style="float:right;padding:0px;margin:0">
								<input type="checkbox" id="flagestablecimientos" name="flagestablecimientos" <?php if($usuarios->flagestablecimientos == 1)echo "checked='checked'";?> >
								<span for="chkAll" style="margin-top:-2px;"> Todos</span> 
							</div>
							@endif
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Farmacia</label>
							<div class="col-lg-9">
								<select class="form-control" name="id_farmacia" id="id_farmacia" required="">
									<option value="0">-Seleccionar-</option>
									@foreach($farmacias as $f)
									<option value="{{$f->id}}" <?php if($usuarios->id_farmacia == $f->id)echo "selected='selected'";?>>{{$f->nombre}}</option>
									@endforeach
								</select>
							</div>
							@if(Auth::user()->flagestablecimientos==1)
							<div class="col-lg-1" style="float:right;padding:0px;margin:0">
								<input type="checkbox" id="flagfarmacias" name="flagfarmacias" <?php if($usuarios->flagfarmacias == 1)echo "checked='checked'";?> >
								<span for="chkAll2" style="margin-top:-2px;"> Todos</span> 
							</div>
							@endif
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Servicio</label>
							<div class="col-lg-9">
								<select class="form-control" name="id_consultorio" id="id_consultorio">
									<option value="0">-Seleccionar-</option>
									@foreach($consultorios as $f)
									<option value="{{$f->id}}" <?php if($usuarios->id_consultorio == $f->id)echo "selected='selected'";?>>{{$f->nombre}}</option>
									@endforeach
								</select>
                                                            <p class="help-block">Para programación del Personal de Salud</p>
							</div>
                                                        <div class="col-lg-1" style="float:right;padding:0px;margin:0">
								<input type="checkbox" id="flagservicios" name="flagservicios" <?php if($usuarios->flagservicios == 1)echo "checked='checked'";?> >
								<span for="chkAll3" style="margin-top:-2px;"> Todos</span> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Tipo Registro</label>
							<div class="col-lg-10">
								<select class="form-control" id="tipo_registro" name="tipo_registro" onchange="getHabilitaMedico2();" tabindex="5" style="width: 100%">
									<option value="" selected>-Seleccionar-</option>
									<option value="1" <?php if($usuarios->tipo_registro == 1)echo "selected='selected'";?>>PRESCRIPTOR MEDICO</option>
									<option value="2" <?php if($usuarios->tipo_registro == 2)echo "selected='selected'";?>>DIGITADOR DE FARMACIA</option>
									<option value="3" <?php if($usuarios->tipo_registro == 3)echo "selected='selected'";?>>DIGITADOR DE CONSULTORIO</option>
									<option value="4" <?php if($usuarios->tipo_registro == 4)echo "selected='selected'";?>>DIGITADOR PRESCRIPTOR</option>
									<option value="5" <?php if($usuarios->tipo_registro == 5)echo "selected='selected'";?>>DIGITADOR DE LABORATORIO</option>
									<option value="6" <?php if($usuarios->tipo_registro == 6)echo "selected='selected'";?>>DIGITADOR DE RADIOLOGIA</option>
									<option value="7" <?php if($usuarios->tipo_registro == 7)echo "selected='selected'";?>>PROFESIONAL DE IMAGENOLOGIA</option>
									<option value="8" <?php if($usuarios->tipo_registro == 8)echo "selected='selected'";?>>PROFESIONAL DE ARCHIVO</option>
									<option value="9" <?php if($usuarios->tipo_registro == 9)echo "selected='selected'";?>>PROFESIONAL DE TRIAJE</option>
									<option value="10" <?php if($usuarios->tipo_registro == 10)echo "selected='selected'";?>>PROFESIONAL DE EJECUTORA 020</option>
									<option value="11" <?php if($usuarios->tipo_registro == 11)echo "selected='selected'";?>>PROFESIONAL COMITE</option>
									<option value="12" <?php if($usuarios->tipo_registro == 12)echo "selected='selected'";?>>PROFESIONAL SIGA</option>
								</select>
							</div>
						</div>
						{{-------------------------------VENTANILLAS DE FARMACIA E IMAGENES---------------------------------}}
						{{-- VALORES DE id_vguardar 1 = vf / 0 = null /2 = vi --}}
						{{--
						@if (auth()->user()->id_establecimiento == 76 && auth()->user()->id_farmacia == 121)
						<input type="hidden" id="id_vguardar" name="id_vguardar" value="0"/>
						<input type="hidden" id="id_vent" name="id_vent" value="{{$vent}}"/>
						<div id="divVentanilla" class="form-group">
							<label class="col-lg-2 control-label">Ventanilla</label>
							<div class="col-lg-9">
								<select class="form-control" name="id_ventanilla" id="id_ventanilla">
									<option value="0">-Seleccionar-</option>
						  			@foreach($ventanillas as $f)
						  			<option value="{{$f->id}}" @if ($f->id == $vent) {{"selected='selected'"}}  @endif >{{$f->nombre}}</option>
						  			@endforeach
								</select>
							</div>
							<div class="col-lg-1" style="float:right;padding:0px;margin:0">
								<input type="checkbox" id="flagventanilla" name="flagventanilla" @if ($usuarios->flagventanilla == 1) {{"checked='checked'"}}@endif/>
								<span for="chkAll3" style="margin-top:-2px;"> Todos</span> 
							</div>
						</div>
						@endif
						--}}
						{{----------------------------------------------------------------}}
						@if($usuarios->tipo_registro == 1)
							<div class="form-group " id="divmedico2" style="display:none">
	                            <label class="col-lg-2 control-label">sin tokens:</label><br/>
	                            <div class="col-lg-1">
	                            	<input type="checkbox" value="1" name="status_token" <?php if($usuarios->status_token == 1)echo 'checked="checked"';?>  />
	                            </div>
	                        </div>
		                @else
		                    <div class="form-group " id="divmedico" style="display:none">
	                            <label class="col-lg-2 control-label">sin tokens:</label><br/>
	                            <div class="col-lg-1">
	                                <input type="checkbox" value="1" name="status_token" <?php if($usuarios->status_token == 1)echo 'checked="checked"';?>  />
	                            </div>
	                        </div>
		                @endif
						<div class="form-group">
							<label class="col-lg-2 control-label">Celular</label>
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->celular?>" name="celular" required="" class="form-control" placeholder="Celular" onkeypress="return IsNumber(event)" maxlength="9">
							</div>
						</div>
						<div class="form-group" id="servicio_ticket">
							<label class="col-lg-2 control-label">Servicio de Tickets</label>
							<div class="col-lg-9">
								<select class="form-control" name="id_servicio_ticket" id="id_servicio_ticket">
									<option value="0">-Seleccionar-</option>
									@foreach($ticket_servicios as $f)
									<option value="{{$f->id}}" <?php if($usuarios->id_consultorio_ticket == $f->id)echo "selected='selected'";?>>{{$f->nombre}}</option>
									@endforeach
								</select>
								<p class="help-block">Para Módulo de Llamado</p>
							</div>
						</div>
						<div class="form-group" id="sub_consultorio_ticket">
							<label for="inputEmail3" class="col-sm-2 control-label">Sub Consultorio de Tickets</label>
							<div class="col-lg-9">
								<select name="id_sub_consultorio_ticket" id="id_sub_consultorio_ticket" class="form-control">
									<option value="0">--Seleccionar--</option>
									@foreach($sub_consultorio_t as $sc)
									<option value="{{$sc->id}}" <?php if($usuarios->id_sub_consultorio_cita_ticket == $sc->id)echo "selected='selected'";?>>{{$sc->descripcion}}</option>
									@endforeach
								</select>
								<p class="help-block">Se activa solo con diagnostico e imagenes</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">{{ trans('validation.attributes.active') }}</label>
							<div class="col-lg-1">
								<input type="checkbox" value="1" name="status" <?php if($usuarios->status == 1)echo 'checked="checked"';?>  />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Responsable de Almacen</label>
							<div class="col-lg-1">
								<input type="checkbox" name="responsable" <?php if($usuarios->responsable == 1) echo 'checked="checked"';?> />
								<input type="hidden" name="id_rol">
							</div>
							
							<label class="col-lg-3 control-label">Responsable de Farmacia</label>
                            <div class="col-lg-1">
                                <input type="checkbox" value="1" name="flagresponsablefarmacia" <?php if($usuarios->flagresponsablefarmacia == 1)echo 'checked="checked"';?>/>
                            </div>
							
							<label class="col-lg-2 control-label">Fiscalizador</label>
							<div class="col-lg-1">
								<input type="checkbox" value="3" name="flagfiscalizador" <?php if($usuarios->flagfiscalizador == 1)echo 'checked="checked"';?>/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Módulo de LLamado</label>
							<div class="col-lg-1">
								<input type="checkbox" value="1" id="flagmodulollamado" name="flagmodulollamado" <?php if($usuarios->flagmodulollamado == 1)echo 'checked="checked"';?>/>
							</div>
							<label class="col-lg-3 control-label">Perfil Estadistica</label>
							<div class="col-lg-1">
								<input type="checkbox" value="1" id="flag_estadistica_vista" name="flag_estadistica_vista" <?php if($usuarios->flag_estadistica_vista == 1)echo 'checked="checked"';?>/>
							</div>
							<!----------------------------------------------- REPORTE IMAGENOLOGÍA ----------------------------------------------------------------------->
							<label class="col-lg-2 control-label">Reporte Imágenes</label>
							<div class="col-lg-1">
								<input type="checkbox" value="3" name="flag_reporte_imagenes" <?php if($usuarios->flag_reporte_imagenes == 1)echo 'checked="checked"';?>/>
							</div>
							<!-------------------------------------------------------------------------------------------------------------------------------------------->
						</div>
						<div class="form-group" style="border: 1px solid #3c763d; border-radius: 5px; margin: 5px;">
							<?php 
								$vistas_consultor = $usuarios->vistas_consultor;
								$json_vistas_consultor = json_decode($vistas_consultor);
							?> 
							<div class="row">

                <label class="col-lg-3 control-label">Módulo de Consultas</label>
                <div class="col-lg-1">
                  <input type="checkbox" value="1" id="flag_consultor" name="flag_consultor" <?php if($usuarios->flag_consultor == 1)echo 'checked="checked"';?>/>
                </div>

                <label class="col-sm-2 control-label"></label>
                <div class="col-lg-1"></div>
                {{-- FLAG RRHH --}}
                <label class="col-lg-3 control-label" style="padding:0;"> Personal UNITIC </label>
                <div class="col-lg-1">
                  <input type="checkbox" value="1" name="flagRRHH" id="flagRRHH" <?php if($usuarios->flag_rrhh == 1)echo 'checked="checked"';?> />
                </div>

							</div>
							<br>
							<div id="consultor_views">
								<label class="col-lg-3 control-label">Citas</label>
								<div class="col-lg-1">
									<input type="checkbox" value="1" id="flag_citas_consultor" name="flag_citas_consultor" <?php if($json_vistas_consultor->flag_citas_consultor  == 1)echo 'checked="checked"';?>/>
								</div>
								<label class="col-lg-3 control-label">Atenciones Médicas</label>
								<div class="col-lg-1">
									<input type="checkbox" value="1" id="flag_atenciones_consultor" name="flag_atenciones_consultor" <?php if($json_vistas_consultor->flag_atenciones_consultor  == 1)echo 'checked="checked"';?>/>
								</div>
								
								<label class="col-lg-3 control-label">Procedimientos</label>
								<div class="col-lg-1">
									<input type="checkbox" value="1" id="flag_procedimientos_consultor" name="flag_procedimientos_consultor" <?php if($json_vistas_consultor->flag_procedimientos_consultor  == 1)echo 'checked="checked"';?>/>
								</div>

								<label class="col-lg-4 control-label">Recetas</label>
								<div class="col-lg-1">
									<input type="checkbox" value="1" id="flag_recetas_consultor" name="flag_recetas_consultor" <?php if($json_vistas_consultor->flag_recetas_consultor  == 1)echo 'checked="checked"';?>/>
								</div>

								<label class="col-lg-4 control-label">Examenes Auxiliares</label>
								<div class="col-lg-1">
									<input type="checkbox" value="1" id="flag_examenes_consultor" name="flag_examenes_consultor" <?php if($json_vistas_consultor->flag_examenes_consultor  == 1)echo 'checked="checked"';?>/>
								</div>
							</div>
							
						</div>
						@if(Auth::user()->flagestablecimientos==1)
						<div class="form-group">
                            <label class="col-lg-3 control-label">Responsable de Citas</label>
                            <div class="col-lg-1">
                                <input type="checkbox" value="1" name="flaghistoria" <?php if($usuarios->flaghistoria == 1)echo 'checked="checked"';?>/>
                            </div>
							
							<label class="col-lg-3 control-label">Emergencia y Referencia</label>
                            <div class="col-lg-1">
                                <input type="checkbox" value="1" name="flagemergenciareferencia" <?php if($usuarios->flagemergenciareferencia == 1)echo 'checked="checked"';?>/>
                            </div>
                            <label class="col-lg-2 control-label">Hospitalizacion</label>
                            <div class="col-lg-1">
                                <input type="checkbox" value="1" name="flaghospitalizacion" <?php if($usuarios->flaghospitalizacion == 1)echo 'checked="checked"';?>/>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-3 control-label">Emergencia</label>
                            <div class="col-lg-1">
                                <input type="checkbox" value="1" name="flagemergencia" <?php if($usuarios->flagemergencia == 1)echo 'checked="checked"';?>/>
                            </div>
							
							<label class="col-lg-3 control-label">Editor SITEDS</label>
                            <div class="col-lg-1">
                                <input type="checkbox" value="1" name="flag_editor_siteds" <?php if($usuarios->flag_editor_siteds == 1)echo 'checked="checked"';?>/>
                            </div>
                            
                            <label class="col-lg-2 control-label">CAN</label>
                            <div class="col-lg-1">
                                <input type="checkbox" value="1" name="flag_can" <?php if($usuarios->flag_can == 1)echo 'checked="checked"';?>/>
                            </div>
							
                        </div>
						<div class="form-group">
							<!--------------------------------------------------------- FLAG FIRMAS PENDIENTES --------------------------------------------------------------->
							<label class="col-lg-3 control-label">Firmas Pendientes</label>
							<div class="col-lg-1" style="padding-top:7px;">
								<input type="checkbox" value="1" name="flag_firmas_pendientes" <?php if($usuarios->flag_firmas_pendientes == 1) echo 'checked="checked"'; ?> />
							</div>
							<!------------------------------------------------------------------------------------------------------------------------------------------------->
						</div>
                        @endif
						<div id="divLlamadaCita" class="form-group">
								
								<label class="col-lg-3 control-label">Llamada Citas</label>
								<div id="divLlamadaCita" class="col-lg-1" >
									<input type="checkbox" id = "id_llamadaCita" value="1" name="flagllamadacita" <?php if($usuarios->flagllamadacita == 1)echo 'checked="checked"';?>/>
								</div>
							</div>
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value=" Guardar " class="btn btn-success" id="btnReg">
                                <a href="/all_usuario" class="btn btn-danger">Cancelar</a>
                            </div>
                        </div> 
                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
