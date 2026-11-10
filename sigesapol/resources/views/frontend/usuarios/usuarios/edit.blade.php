@extends('frontend.layouts.master')
@section('renderjs')
<style>
.solomayusculas{
     
       text-transform:uppercase;
    }
 </style>
 
<script type="text/javascript">

$(document).ready(function () {

	$('#id_consultorio').select2();
	$('#id_establecimiento').select2();
	
	/*
	$("#btnReg").on("click", function () {
          
        if ($('#id_consultorio').val() == "") {
			bootbox.alert("Falta Seleccionar un Hospital");
            return false;
        }                
        if ($('#id_farmacia').val() == "") {
			bootbox.alert("Falta Seleccionar una Farmacia");
            return false;
        }        
        
		$("#frm").submit();
		
		return false;
		
    });
	*/
	
	
	
	
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
								<select class="form-control" id="tipo_registro" name="tipo_registro" tabindex="5" style="width: 100%">
									<option value="" selected>-Seleccionar-</option>
									<option value="1" <?php if($usuarios->tipo_registro == 1)echo "selected='selected'";?>>PRESCRIPTOR</option>
									<option value="2" <?php if($usuarios->tipo_registro == 2)echo "selected='selected'";?>>DIGITADOR DE FARMACIA</option>
									<option value="3" <?php if($usuarios->tipo_registro == 3)echo "selected='selected'";?>>DIGITADOR DE CONSULTORIO</option>
								</select>
							</div>
						</div>						
						<div class="form-group">
							<label class="col-lg-2 control-label">Celular</label>
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->celular?>" name="celular" required="" class="form-control" placeholder="Celular" onkeypress="return IsNumber(event)" maxlength="9">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">{{ trans('validation.attributes.active') }}</label>
							<div class="col-lg-1">
								<input type="checkbox" value="1" name="status" <?php if($usuarios->status == 1)echo 'checked="checked"';?>  />
							</div>
						</div>
				
                        @if(Auth::user()->flagestablecimientos==1)
						<div class="form-group">
							<label class="col-lg-3 control-label">Responsable de Almacen</label>
							<div class="col-lg-1">
								<input type="checkbox" value="3" name="responsable" <?php if(isset($id_rol) && $id_rol > 0)echo 'checked="checked"';?> />
								<input type="hidden" name="id_rol" value="<?php if(isset($id_rol) && $id_rol > 0)echo $id_rol?>" />
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
                        </div>
                        @endif
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