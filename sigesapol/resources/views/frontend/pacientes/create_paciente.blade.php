@extends('frontend.layouts.master')
@section('renderjs')
<style>


</style>

<script>
function jsDecimals(e) {

  var evt = (e) ? e : window.event;
  var key = (evt.keyCode) ? evt.keyCode : evt.which;
  if (key != null) {
    key = parseInt(key, 10);
    if ((key < 48 || key > 57) && (key < 96 || key > 105)) {
      //Aca tenemos que reemplazar "Decimals" por "NoDecimals" si queremos que no se permitan decimales
      if (!jsIsUserFriendlyChar(key, "Decimals")) {
        return false;
      }
    } else {
      if (evt.shiftKey) {
        return false;
      }
    }
  }
  return true;
}

function jsIsUserFriendlyChar(val, step) {
  // Backspace, Tab, Enter, Insert, y Delete
  if (val == 8 || val == 9 || val == 13 || val == 45 || val == 46) {
    return true;
  }
  // Ctrl, Alt, CapsLock, Home, End, y flechas
  if ((val > 16 && val < 21) || (val > 34 && val < 41)) {
    return true;
  }
  if (step == "Decimals") {
    if (val == 190 || val == 110) { //Check dot key code should be allowed
      return true;
    }
  }
  // The rest
  return false;
}

function IsNumeric(valor) {
  var log = valor.length;
  var sw = "S";
  for (x = 0; x < log; x++) {
    v1 = valor.substr(x, 1);
    v2 = parseInt(v1);
    //Compruebo si es un valor numérico 
    if (isNaN(v2)) {
      sw = "N";
    }
  }
  if (sw == "S") {
    return true;
  } else {
    return false;
  }
}
var primerslap = false;
var segundoslap = false;


function validateFrm() {
  $('#btnReg').prop('disabled', true);

  if ($('#frmMedico').find("#nombre").val() == "") {
    bootbox.alert("El nombre del médico no puede estar en vacío.");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#paterno").val() == "") {
    bootbox.alert("El apellido paterno del médico no puede estar vacío.");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#materno").val() == "") {
    bootbox.alert("El apellido materno del médico no puede estar vacío.");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#id_especializacion").val() == "") {
    bootbox.alert("Debe seleccionar la especialidad del médico.");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#tipo_contrato").val() == "") {
    bootbox.alert("Debe seleccionar el tipo de contrato del médico");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#id_profesion").val() == "") {
    bootbox.alert("Debe seleccionar la profesión del médico");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#id_profesion").val() == "4") {
    if ($('#frmMedico').find("#rne").val() == "") {
      bootbox.alert("Selecciono MEDICO ESPECIALISTA, portanto deberá ingresar el Nro. RNE");
      $('#btnReg').prop('disabled', false);
      return false;
    }
  }


  var ind = $('#example tbody tr').length;
  if (ind > 0) {
    var fila_medicamentos = $('#example tbody').children('tr');
    var fila_medicamento = "";
    var valmedentre = "";
    var nind = 0;
    for (var i = 0; i < ind; i++) {
      fila_medicamento = fila_medicamentos[i];
      valmedentre = $("#consultorio" + nind).val();
      if (valmedentre == "") {
        bootbox.alert("No selecciono uno de los servicios agregados");
        $('#btnReg').prop('disabled', false);
        return false;
      }
      nind++;
    }
  }

  submitFrm();
  return false;
}

function submitFrm() {
  bootbox.confirm({
    message: "Aviso: Se guardarán los registros ingresados. ¿Desea continuar?.",
    className: 'title-dialog',
    buttons: {
      confirm: {
        label: 'Si',
        className: 'btn-success btn-lg'
      },
      cancel: {
        label: 'No',
        className: 'btn-danger btn-lg'
      }
    },
    callback: function(result) {
      if (result == true) {
        $("#frmMedico").submit();
      } else {
        $('#btnReg').prop('disabled', false);
      }
    }
  });
}

function AddFila() {

  var newRow = "";

  var ind = $('#example tbody tr').length;

  var item_servicio = "";
  $('#id_consultoriotemp option').each(function() {
    item_servicio += "<option value=" + $(this).val() + ">" + $(this).html() + "</option>"
  });
  newRow += '<tr>';
  newRow += '<td class="col-sm-9"><select class="form-control consultorio" id="consultorio' + ind +
    '" name="id_consultorio[]" style="width:100%" >' + item_servicio + '</select></td>';
  newRow +=
    '<td class="col-sm-3"><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';
  //alert(newRow);
  $('#example tbody').append(newRow);
  $("#consultorio" + ind).select2({
    max_selected_options: 4
  }).focus();

  var nind = 0;
  $("#consultorio" + ind).on("change", function(e) {
    var flagx = 0;
    cmb = $(this);
    id_consultorio = $("#consultorio" + ind).val();

    $('.consultorio').each(function() {
      var ind_tmp = $(this).val();
      if ($(this).val() == id_consultorio) flagx++;
    });

    if (flagx > 1) {
      bootbox.alert("El servicio de atención, ya ha sido seleccionado");
      $("#consultorio" + ind).val("").trigger("change");
      return false;
    }
  });
}
$(document).ready(function() {

  $("#id_establecimiento").select2({
    width: 'resolve', // need to override the changed default
    max_selected_options: 4
  });

  $("#id_establecimiento").trigger('change');

  $('#addRow').on('click', function() {
    AddFila();
  });

  $('#example tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  AddFila();
});

function maxlength_doc_usu() {

  $('#dni_titular').val('');

  if ($("#tipo_documento_titular").val() == "1") {
    $("#dni_titular").attr('maxlength', '8');
  } else {
    $("#dni_titular").attr('maxlength', '12');
  }
  document.getElementById("dni_titular").focus();
}

function cambiar_tipo_beneficiario(){
  
  if ($("#id_tipo_beneficiario").val() == "8") { //titular
	var dni=$('#dni').val();
    $('#dni_titular').val(dni);
	var tipo_documento_id=$('#tipo_documento_id').val();
    $('#tipo_documento_titular').val(tipo_documento_id);		
	document.getElementById('dni_titular').readOnly = true;
	document.getElementById('tipo_documento_titular').disabled = true;
	document.getElementById('cip').readOnly = false;
	
  } else {
	$('#dni_titular').val('');
    document.getElementById("dni_titular").focus();
	$('#tipo_documento_titular').val(1);
	
	document.getElementById('dni_titular').readOnly = false;
	document.getElementById('tipo_documento_titular').disabled = false;
	document.getElementById('cip').readOnly = true;
  }
  
}

 $(function () {
	//$("#DatosPaciente_r").hide();
		
	//buscar por enter					
	$("#dni_titular").keypress(function (e) {	
		//alert("Pulsaste la tecla con codigo: "+e.which);		  
		if (e.which == 13) {	
			buscar_persona();
		}
	});//FIN nro_documento_b		
  
});//fin function 

function borrardatos_titular(){
	$('#cip').val('');
	$('#datos_titular').val('');
	
	
}

function buscar_persona(){
	var dni_titular=$('#dni_titular').val();
	var tipo_documento_titular=$('#tipo_documento_titular').val();
		$.ajax({
		url: "{{ route('buscar_persona') }}",					
		type: "GET",
		dataType: "JSON",
		data: {						
			dni: dni_titular,
			tipo_documento:tipo_documento_titular		
		},							
			success: function (data) {				
				if(data==null){ //ocurrio una advertencia
					alert("DEBE REGISTRAR PRIMERO EL TITULAR");	
											
				}else{ //datos table persona	
					//alert("CONTINUAR");
					//alert(data.cip);	
					document.getElementById('cip').value=data.cip;
					document.getElementById('datos_titular').value=data.paterno+' '+data.materno+' '+data.nombre;
					//data.paterno.concat(data.materno).concat(cadena2);
				}
			}				
		}).done(function (data) {
			console.log(data);
		});	//fin done
		//e.preventDefault();
		return false;
}
</script>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="/all_asegurados/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Asegurados</a>
        </li>
        <li class="active"><a href="/buscar_paciente"><i class="fa fa-plus"></i> Nuevo </a></li>       
      </ul>
    </div>
    <div class="col-md-7 well">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">Agregar Asegurado</h3>
          </div>
          @if(count($medico_registrado)>0)
          <div style="margin: 20px 20px 20px 20px">
            El Asegurado <b>{{$medico_registrado[0]->nombre}} {{$medico_registrado[0]->paterno}}
              {{$medico_registrado[0]->materno}} </b> identificado con DNI <b>{{$medico_registrado[0]->nro_doc_ident}}</b> ya
            se encuentra registrado en la IPRESS PNP
            <b>{{$medico_registrado[0]->establecimiento}}</b>, en estado
            <?php echo $medico_registrado[0]->estado == "1" ? "<b>ACTIVO</b>" : "<b>INACTIVO</b>"; ?>.<br /><br />
            <a href="/buscar_paciente" class="btn btn-default"><i class="fa fa-plus-circle"></i> Registrar otro
              Asegurado</a>
          </div>
          @else
          <?php
		  $sexo = "";
		  $fecha_nac="";
		  $telefono="";
		  $correo="";
		  $domicilio="";
		  
          if ($existe == 1) { //si existe en la tabla asegurados
            $nompersonal = $personal->nombre;
            $patpersonal = $personal->paterno;
            $matpersonal = $personal->materno;
            $sexo = $personal->sexo;
            $fecha_nac = $personal->fecha_nac;
			$telefono = $personal->telefono;
			$correo = $personal->correo;
			$domicilio = $personal->domicilio;			
            $readdatosper = " readonly";
          } else if ($existe == 2) {
            //si existe en el Webservice de afiliados(Saludpol) -->
            $nompersonal = $personal[0]->nomafiliado;
            $patpersonal = $personal[0]->apepatafiliado;
            $matpersonal = $personal[0]->apematafiliado;
            $sexo = $personal[0]->sexo;
            $fecha_nac = $personal[0]->fecha_nac;
            $readdatosper = " readonly";
          } else if ($existe == 3) {
            //si existe en la tabla asegurados
            $nompersonal = $personal["nombres"];
            $patpersonal = $personal["paterno"];
            $matpersonal = $personal["materno"];
            $sexo = $personal["sexo"];
            $fecha_nac = $personal["fecha_nac"];
            $readdatosper = " readonly";
          } else { //si no existe en el Webservice de afiliados-->
            $nompersonal = "";
            $patpersonal = "";
            $matpersonal = "";
            $sexo = "";
            $fecha_nac = "";
            $readdatosper = "";
          }
		   //$sexo = $existe;
          ?>
          <div style="margin-top: 20px">
            <form id="frmMedico" class="form-horizontal" role="form" method="post" action="{{ route('store_paciente') }}">
			<fieldset>
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
				<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
					<li class="nav-item active">
					  <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">DATOS PERSONALES</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">DATOS DEL PACIENTE</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">DATOS DEL TITULAR</a>
					</li>
				</ul>

				<div class="tab-content" id="pills-tabContent">
					<div class="tab-pane fade active in" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
						  
						<div class="col-md-6">
						  <label>Tipo Documento</label>
						  <input id="tipo_documento" name="tipo_documento" type="text" value="{{$tipo_documento_text}}"
							class="form-control input-sm" required="" readonly="">
						  <input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{$tipo_documento_id}}">
						</div>
						<div class="col-md-6">
						  <label>Documento</label>
						  <input id="dni" name="dni" type="text" value="{{$dni}}" class="form-control input-sm" required=""
							readonly="" onkeypress="return IsNumber(event)">
						  <input id="id_user" name="id_user" type="hidden" value="{{Auth::user()->id}}">
						</div>
						<div class="col-md-6">
						  <label>Nombre</label>
						  <input id="nombre" name="nombre" type="text" value="<?php echo $nompersonal; ?>"
							class="form-control input-sm" required="" <?php echo $readdatosper ?>
							style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"
							autofocus="" onkeypress="return solo_letra(event)">
						</div>
						<div class="col-md-6">
						  <label>Apellido Paterno</label>
						  <input id="paterno" name="paterno" type="text" value="<?php echo $patpersonal; ?>"
							class="form-control input-sm" required="" <?php echo $readdatosper ?>
							style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"
							onkeypress="return solo_letra(event)">
						</div>
						<div class="col-md-6">
						  <label>Apellido Materno</label>
						  <input id="materno" name="materno" type="text" value="<?php echo $matpersonal; ?>"
							class="form-control input-sm" required="" <?php echo $readdatosper ?>
							style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"
							onkeypress="return solo_letra(event)">
						</div>
						<div class="col-md-6">
						  <label>Sexo</label>
						  <input id="sexo" name="sexo" value="<?php echo $sexo; ?>" type="text"
							placeholder="" class="form-control input-sm" required="" onkeydown="return solo_letra(event);" <?php echo $readdatosper ?> >
						</div>
						<div class="col-md-6">
						  <label>Fecha Nac.</label>
						  <input id="fecha_nac" name="fecha_nac" value="<?php echo $fecha_nac; ?>" type="text" placeholder="" class="form-control input-sm" <?php echo $readdatosper ?> >
						</div>
						<div class="col-md-6">
						   <label>Telefono</label>
						  <input id="telefono" name="telefono" value="<?php echo $telefono; ?>" type="text" placeholder="" class="form-control input-sm" >
						</div>
						<div class="col-md-6">
						  <label>Domicilio</label>
						  <input id="domicilio" name="domicilio" value="<?php echo $domicilio; ?>" type="text" placeholder="" class="form-control input-sm" >
						</div>	
						<div class="col-md-6">
						  <label>E-mail</label>
						  <input id="email" name="email" type="email" value="<?php echo $correo; ?>" placeholder="" class="form-control input-sm">
						</div>											
						 
					</div>
					
					<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
						<div class="col-md-12">
						  <label>Establecimiento (IPRESS PNP)</label>
						  <select id="id_establecimiento" name="id_establecimiento" class="form-control input-sm" required=""
							style="width: 100%">
							@foreach($establecimiento as $e)
							<?php
							$idEst = $e->id; ?>
							<option value="{{$e->id}}" <?php if ($id_establecimiento == $idEst) {
														  echo " selected";
														} ?>>
							  {{$e->nombre}}
							</option>
							@endforeach
						  </select>
						</div>
						
						<div class="col-md-6">
						  <label>Historia</label>
						  <input id="nro_historia" name="nro_historia" value="{{$dni}}" type="text" placeholder="" class="form-control input-sm" >
						</div>
						
						<div class="col-md-6">
						  <label>Tipo Beneficiario</label>
						  <select id="id_tipo_beneficiario" name="id_tipo_beneficiario" class="form-control input-sm" required="" onchange="cambiar_tipo_beneficiario()">
							<option value="">SELECCIONE</option>
							@foreach($tipo_beneficiario as $e)
							<option value="{{$e->id}}"> {{$e->descripcion}}</option>
							@endforeach
						  </select>
						</div>
					</div>
					
					<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
						<div class="col-md-6">
						    <label>Tipo Documento</label>
						    <select class="form-control input-sm" id="tipo_documento_titular" name="tipo_documento_titular"
							  onchange="maxlength_doc_usu()" tabindex="3">
							  <option value="1" selected="">DNI</option>
							  <option value="2">CARNET DE EXTRANJERIA</option>
							  <option value="3">PASAPORTE</option>
							  <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
							</select>
						</div>
						<div class="col-md-6">
						  <label>Documento</label>
						  <input id="dni_titular" placeholder="Digite y ENTER" name="dni_titular" type="text" value="" class="form-control input-sm" onkeypress="return IsNumber(event)" onchange="borrardatos_titular()">
						</div>
						
						<div class="col-md-12">
						  <label>Apellidos y Nombres</label>
						  <input id="datos_titular" name="datos_titular" value="" type="text" placeholder="" class="form-control input-sm" readOnly >
						</div>
						
						<div class="col-md-6">
						  <label>CIP</label>
						  <input id="cip" name="cip" value="" type="text" placeholder="" class="form-control input-sm" readOnly >
						</div>
						
						<div class="col-md-6">
						  <label>Grado</label>
						  <select id="id_tipo_beneficiario" name="id_tipo_beneficiario" class="form-control input-sm" required="" >
							<option>SELECCIONE</option>
							@foreach($grado as $e)
							<?php
							$idEst = $e->id; ?>
							<option value="{{$e->id}}"> {{$e->descripcion}}</option>
							@endforeach
						  </select>
						</div>
						
						<div class="col-md-6">
						  <label>Categoria</label>
						  <select id="id_tipo_beneficiario" name="id_tipo_beneficiario" class="form-control input-sm" required="" >
							<option>SELECCIONE</option>
							@foreach($categoria as $e)
							<?php
							$idEst = $e->id; ?>
							<option value="{{$e->id}}"> {{$e->descripcion}}</option>
							@endforeach
						  </select>
						</div>
						
						<div class="col-md-6">
						  <label>Situación</label>
						  <select id="id_tipo_beneficiario" name="id_tipo_beneficiario" class="form-control input-sm" required="" >
							<option>SELECCIONE</option>
							@foreach($situacion as $e)
							<?php
							$idEst = $e->id; ?>
							<option value="{{$e->id}}"> {{$e->descripcion}}</option>
							@endforeach
						  </select>
						</div>
						
						<div class="col-md-6">
						  <label>Unidad</label>
						  <select id="id_tipo_beneficiario" name="id_tipo_beneficiario" class="form-control input-sm" required="" >
							<option>SELECCIONE</option>
							@foreach($unidadpago as $e)
							<?php
							$idEst = $e->id; ?>
							<option value="{{$e->id}}"> {{$e->descripcion_larga}}</option>
							@endforeach
						  </select>
						</div>
						
						<div class="col-md-6">
						  <label>F. ini Servicio</label>
						  <input type="text" class="form-control datepicker" value="" name="fecha_atencion" id="fecha_atencion" tabindex=""   autocomplete="off"/>
						</div>
					</div>		
				</div>
				
				<div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
				  <a href="/all_asegurados/{{ Auth::user()->id }}" class="form-control btn btn-danger">Cancelar</a>
				</div>
				<div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
				  <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921"
					onclick="validateFrm()">Guardar</button>
				</div>
				
			</fieldset>
            </form>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection