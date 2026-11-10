@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>-->
<script type="text/javascript">
$("#divnacion").hide();
$(document).ready(function() {

  $("#dni").blur(function(event) {
    getPersonalByDni();
  });

  $('#id_consultorio').select2();
  $('#id_establecimiento').select2();

  /*
   $("#btnReg").on("click", function () {
   
   if ($('#id_establecimiento').val() == "0") {
   bootbox.alert("Falta Seleccionar un Hospital");
   return false;
   }                
   if ($('#id_farmacia').val() == "0") {
   bootbox.alert("Falta Seleccionar una Farmacia");
   return false;
   }
   
   if ($('#name').val() == "0") {
   bootbox.alert("Falta Ingresar un Nombre");
   return false;
   }
   
   $("#frm").submit();
   
   return false;
   
   });
   */


});

function getPersonalByDni() {

  var dni = $('#dni').val();

  $('.table tbody').html("");

  $.ajax({
    url: '/buscar_ajax_medico_dni/' + dni,
    success: function(result) {

      $('#name').val("").attr("readonly", false);
      $('#paterno').val("").attr("readonly", false);
      $('#materno').val("").attr("readonly", false);

      if (result.length == 1) {
        $('#name').val(result[0].nomafiliado).attr("readonly", true);
        $('#paterno').val(result[0].apepatafiliado).attr("readonly", true);
        $('#materno').val(result[0].apematafiliado).attr("readonly", true);
      } else {
        fetch(`/validar_documento/${dni}`)
          .then((resp) => resp.json())
          .then(function(data) {
            if (data.codigoMensaje != '00') {
              alert('documento no existe');
            } else {
              $('#name').val(data.nombres).attr("readonly", true);
              $('#paterno').val(data.paterno).attr("readonly", true);
              $('#materno').val(data.materno).attr("readonly", true);
            }
          })
          .catch(function(error) {
            console.error(error);
          })
      }
    }
  });

}

function getFarmacia() {

  var id_establecimiento = $('#id_establecimiento').val();
  $.ajax({
    url: '/obtener_farmacia_por_id_establecimiento/' + id_establecimiento,
    success: function(result) {
      var newOption = "";
      newOption = "<option value='0'>--Seleccionar-</option>";
      $(result).each(function(ii, oo) {
        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
      });
      $('#id_farmacia').html(newOption);
      $("#id_farmacia").select2({
        max_selected_options: 4
      });
      var id_establecimiento = $('#id_establecimiento').val(); 
	if (id_establecimiento !== '76') {
		$('#totems').hide();
            
		}else{$('#totems').show();}

    }
  });

}

function validarDni() {
  var msg = "";
  var sw = true;
  var dni = $('#dni').val();
  var len = dni.length;
  var id_establecimiento = $('#id_establecimiento').val();
  var id_farmacia = $('#id_farmacia').val();
  var tipo_registro = $('#tipo_registro').val();

  if (len != 8) {
    msg += "En Dni tiene que ser de 8 digitos.<br/>";
    sw = false
  }

  if ($('#flagestablecimientos').is(':checked')) {
    if (id_establecimiento == "0") {
      msg += "Seleccione almenos un Hospital.<br/>";
      sw = false
    }
  } else {
    if (id_establecimiento == "0") {
      msg += "Seleccione un Hospital y elija la opción Todos de ser necesario.<br/>";
      sw = false
    }
  }
  if ($('#flagfarmacias').is(':checked')) {

  } else {
    if (id_farmacia == "0") {
      msg += "Seleccione una Farmacia y elija la opción Todos de ser necesario.<br/>";
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
    message: "Se registrarán los registros ingresados, ¿Está seguro de continuar?",
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
    callback: function(result) {
      if (result == true) {
        document.frm.submit();
      }
    }
  });
}

function getHabilitaMedico() {
  var tipo_registro = $('#tipo_registro').val();
  if (tipo_registro == '1') {
    $("#divmedico").show();
  } else {
    $("#divmedico").hide();
  }
}

$('#id_consultorio').change(function (e) {
	e.preventDefault();
	let id_consultorio = $('#id_consultorio').val();
	$.ajax({
		type: "GET",
		url: "/obtenerVentanilla/"+ id_consultorio,
		success: function (response) {
			var newOption = "";
			newOption = "<option value=''>--Seleccionar-</option>";
			$(response).each(function (ii, oo) {
				var sel = "";
				newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
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

</script>


@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-2">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="/all_usuario"><i class="fa fa-table fa-fw"></i> Usuario</a></li>
        <li class="active"><a href="/create_usuario"><i class="fa fa-plus"></i> Nuevo</a></li>
      </ul>
    </div>
    <div class="col-md-10 well">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <div class="panel-title">Crear Usuario</div>
        </div>
        <div style="padding-top:30px" class="panel-body">
          <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
          <form name="frm" id="frm" class="form-horizontal" role="form" method="post"
            action="{{ route('store_usuario') }}" onsubmit="return validarDni()" data-toggle="validator">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
              <label class="col-lg-2 control-label">Documento de Identidad</label>
              <div class="col-lg-10">
                <input type="text" value="" name="dni" id="dni" required="" class="form-control" placeholder="DNI"
                  maxlength="9" onkeypress="return IsNumber(event)">
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('name', trans('validation.attributes.name'), ['class' => 'col-lg-2 control-label']) !!}
              <div class="col-lg-10">
                <input type="text" value="" name="name" id="name" required="" class="form-control"
                  placeholder="Nombre Completo" onkeypress="return solo_letra(event)" style="text-transform: uppercase;"
                  onkeyup="javascript:this.value = this.value.toUpperCase();">
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">Apellido Paterno</label>
              <div class="col-lg-10">
                <input type="text" value="" name="paterno" id="paterno" required="" class="form-control"
                  placeholder="Apellido Paterno" onkeypress="return solo_letra(event)"
                  style="text-transform: uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">Apellido Materno</label>
              <div class="col-lg-10">
                <input type="text" value="" name="materno" id="materno" required="" class="form-control"
                  placeholder="Apellido Materno" onkeypress="return solo_letra(event)"
                  style="text-transform: uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('email', trans('validation.attributes.email'), ['class' => 'col-lg-2 control-label']) !!}
              <div class="col-lg-10">
                <input type="email" value="" name="email" required="" class="form-control" placeholder="E-mail">
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('password', trans('validation.attributes.password'), ['class' => 'col-lg-2
              control-label']) !!}
              <div class="col-lg-10">
                <input type="password" value="" name="password" required="" class="form-control" placeholder="">
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('password_confirmation', trans('validation.attributes.password_confirmation'), ['class' =>
              'col-lg-2 control-label']) !!}
              <div class="col-lg-10">
                <input type="password" value="" name="password_confirmation" required="" class="form-control"
                  placeholder="">
              </div>
            </div>
            @if($user->nivel_user == 3)
            <div class="form-group">
							<label class="col-lg-2 control-label">Nivel de Usuario</label>
							<div class="col-lg-10">
							<select class="form-control" id="nivel_user"  name="nivel_user" tabindex="5" style="width: 100%">
								<option value="" selected>-Seleccionar-</option>
								<option value="1">Usuario Final</option>
								<option value="2">Usuario Administrador</option>
								<option value="3">Super Usuario</option>
								</select>
							</div>
							</div>
							@else
							<div class="form-group">
							<label class="col-lg-2 control-label">Nivel de Usuario</label>
							<div class="col-lg-10">
							<select class="form-control" id="nivel_user"  name="nivel_user" tabindex="5" style="width: 100%">
								<option value="" selected>-Seleccionar-</option>
								<option value="1">Usuario Final</option>
								</select>
							</div>
							</div>
            @endif
            <div class="form-group">
              <label class="col-lg-2 control-label">Establecimiento</label>
              <div class="col-lg-9">
                <select class="form-control" name="id_establecimiento" id="id_establecimiento" onchange="getFarmacia()"
                  <?php if(Auth::user()->flagestablecimientos!=1): echo "disabled='disabled'"; endif;?> required=""
                  placeholder="Seleccionar un Hospital" style="width: 100%">
                  <option value="0">-Seleccionar-</option>
                  @foreach($establecimientos as $f)
                  <!--option value="{{$f->id}}">{{$f->codigo}} - {{$f->nombre}}</option-->
                  <option value="{{$f->id}}"
                    <?php if($f->id == Auth::user()->id_establecimiento)echo "selected='selected'";?>>{{$f->codigo}} -
                    {{$f->nombre}}</option>
                  @endforeach
                </select>
              </div>
              @if(Auth::user()->flagestablecimientos==1)
              <div class="col-lg-1" style="float:right;padding:0px;margin:0">
                <input type="checkbox" id="flagestablecimientos" name="flagestablecimientos">
                <span for="chkAll" style="margin-top:-2px;"> Todos</span>
              </div>
              @endif
            </div>

            <div class="form-group">
              <label class="col-lg-2 control-label">Farmacia</label>
              <div class="col-lg-9">
                <!--<select class="form-control input-sm" name="id_farmacia" id="id_farmacia" required="">
                                    <option value="0">-Seleccionar-</option>
                                </select>-->
                <select class="form-control" name="id_farmacia" id="id_farmacia" required="">
                  <option value="0">-Seleccionar-</option>
                  @foreach($farmacias as $f)
                  <option value="{{$f->id}}" <?php if(Auth::user()->id_farmacia == $f->id)echo "selected='selected'";?>>
                    {{$f->nombre}}</option>
                  @endforeach
                </select>
              </div>

              @if(Auth::user()->flagestablecimientos==1)
              <div class="col-lg-1" style="float:right;padding:0px;margin:0">
                <input type="checkbox" id="flagfarmacias" name="flagfarmacias">
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
                  <option value="{{$f->id}}">{{$f->nombre}}</option>
                  @endforeach
                </select>
                <p class="help-block">Para programación del Personal de Salud</p>
              </div>
              

              <div class="col-lg-1" style="float:right;padding:0px;margin:0">
                <input type="checkbox" id="flagservicios" name="flagservicios">
                <span for="chkAll3" style="margin-top:-2px;"> Todos</span>
              </div>
            </div>





          <div id="totems" class="form-group">
						
              <label class="col-lg-2 control-label">TOTEMS</label>
              <div class="col-lg-10">
              <select class="form-control" name="id_totem" id="id_totem">
                <option value="0">-Seleccionar-</option>
                @foreach($totems as $f)
                <option value="{{$f->id}}">{{$f->descripcion}}</option>
                @endforeach
              </select>
              <p class="help-block">Para Configurar Totems</p>
            </div>
            </div>



            
            <div class="form-group">
              <label class="col-lg-2 control-label">Tipo Registro</label>
              <div class="col-lg-10">
                <select class="form-control" id="tipo_registro" onchange="getHabilitaMedico();" name="tipo_registro"
                  tabindex="5" style="width: 100%">
                  <option value="" selected>-Seleccionar-</option>
                  <option value="1">PRESCRIPTOR MEDICO</option>
                  <option value="2">DIGITADOR DE FARMACIA</option>
                  <option value="3">DIGITADOR DE CONSULTORIO</option>
                  <option value="4">DIGITADOR PRESCRIPTOR</option>
                  <option value="5">DIGITADOR DE LABORATORIO</option>
                  <option value="6">DIGITADOR DE RADIOLOGIA</option>
                  <option value="7">PROFESIONAL DE IMAGENOLOGIA</option>
		              <option value="8">PROFESIONAL DE ARCHIVO</option>
                  <option value="9">PROFESIONAL DE TRIAJE</option>
                  <option value="10">PROFESIONAL DE EJECUTORA 020</option>
                  <option value="11">PROFESIONAL COMITE</option>
                  <option value="12">PROFESIONAL SIGA</option>
                </select>
              </div>
            </div>
            <div class="form-group " id="divmedico" style="display:none">
              <label class="col-lg-2 control-label">sin tokens:</label><br />
              <div class="col-lg-1">
                <input type="checkbox" value="1" name="status_token" checked="checked" />
              </div>
            </div>
            {{-------------------------------VENTANILLAS DE FARMACIA E IMAGENES---------------------------------}}
						{{-- VALORES DE id_vguardar 1 = vf / 0 = null /2 = vi --}}
            @if (auth()->user()->id_establecimiento == 76 && auth()->user()->id_farmacia == 121)
						<input type="hidden" id="id_vguardar" name="id_vguardar" value="0"/>
						<div id="divVentanilla" class="form-group">
							<label class="col-lg-2 control-label">Ventanilla</label>
							<div class="col-lg-9">
								<select class="form-control" name="id_ventanilla" id="id_ventanilla">

								</select>
							</div>
              <div class="col-lg-1" style="float:right;padding:0px;margin:0">
								<input type="checkbox" id="flagventanilla" name="flagventanilla"/>                                        
								<span for="chkAll3" style="margin-top:-2px;"> Todos</span> 
							</div>
						</div>
            @endif
						{{----------------------------------------------------------------}}
            <div class="form-group">
              <label class="col-lg-2 control-label">Celular</label>
              <div class="col-lg-10">
                <input type="text" value="" name="celular" required="" class="form-control" placeholder="Celular"
                  onkeypress="return IsNumber(event)" maxlength="9">
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">{{ trans('validation.attributes.active') }}</label>
              <div class="col-lg-1">
                <input type="checkbox" value="1" name="status" checked="checked" />
              </div>
            </div>

            <div class="form-group">
              <label class="col-lg-3 control-label">Responsable de Almacen</label>
              <div class="col-lg-1">
                <input type="checkbox" value="3" name="responsable" />
              </div>

              <label class="col-lg-3 control-label">Responsable de Farmacia</label>
              <div class="col-lg-1">
                <input type="checkbox" value="1" name="flagresponsablefarmacia" />
              </div>

              <label class="col-lg-2 control-label">Fiscalizador</label>
              <div class="col-lg-1">
                <input type="checkbox" value="1" name="flagfiscalizador" />
              </div>

            </div>
            @if(Auth::user()->flagestablecimientos==1)
            <div class="form-group">
              <label class="col-lg-3 control-label">Responsable de Historia</label>
              <div class="col-lg-1">
                <input type="checkbox" value="1" name="flaghistoria" />
              </div>

              <label class="col-lg-3 control-label">Emergencia y Referencia</label>
              <div class="col-lg-1">
                <input type="checkbox" value="1" name="flagemergenciareferencia" />
              </div>
              <label class="col-lg-2 control-label">Hospitalizacion</label>
              <div class="col-lg-1">
                <input type="checkbox" value="1" name="flaghospitalizacion" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Emergencia</label>
              <div class="col-lg-1">
                <input type="checkbox" value="1" name="flagemergencia" />
              </div>
            </div>
            @endif
            
            <div class="form-group">
              <label class="col-lg-3 control-label" style="padding:0;">Personal UNITIC</label>
              <div class="col-lg-1">
                <input type="checkbox" value="1" name="flagRRHH" id="flagRRHH">
              </div>
            </div>

            <div style="margin-top:10px" class="form-group">
              <div class="col-sm-12 controls">
                <input type="submit" value="Guardar" class="btn btn-success" id="btnReg">
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

<div id="openOverlayOpc" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">
      <!--
      <div class="modal-header" style="padding: 0px;margin: 0px">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Categorias</h4>
      </div>
      -->
      <div class="modal-body" style="padding: 0px;margin: 0px">

        <div id="diveditpregOpc"></div>

      </div>
      <div class="modal-footer">

      </div>
    </div>

  </div>
</div>
