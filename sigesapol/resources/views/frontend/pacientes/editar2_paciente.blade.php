@extends('frontend.layouts.master')
@section('renderjs')
<style>


</style>

<script src="{{ asset('/funciones/function.js') }}"></script>
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
  
  if ($('#frmMedico').find("#id_tipo_doc_ident").val() == "") {
    bootbox.alert("El Tipo de Documento del paciente no puede estar en vacío.");
    $('#btnReg').prop('disabled', false);
    return false;
  }
 
  if ($('#frmMedico').find("#dni").val() == "") {
    bootbox.alert("El Documento de Identidad del paciente no puede estar en vacío.");
    $('#btnReg').prop('disabled', false);
	document.getElementById("dni").focus();
    return false;
  }
  
  if ($('#frmMedico').find("#nombre").val() == "") {
    bootbox.alert("El nombre del paciente no puede estar en vacío.");
    $('#btnReg').prop('disabled', false);
	document.getElementById("nombre").focus();
    return false;
  }

  if ($('#frmMedico').find("#paterno").val() == "") {
    bootbox.alert("El apellido paterno del paciente no puede estar vacío.");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#materno").val() == "") {
    bootbox.alert("El apellido materno del paciente no puede estar vacío.");
    $('#btnReg').prop('disabled', false);
    return false;
  }
  
  if ($('#frmMedico').find("#id_tipo_beneficiario").val() == "") {
    bootbox.alert("Debe seleccionar el Tipo de Beneficiario.");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#id_establecimiento").val() == "") {
    bootbox.alert("Debe seleccionar el Establecimiento");
    $('#btnReg').prop('disabled', false);
    return false;
  }
  
  if ($('#frmMedico').find("#nro_historia").val() == "") {
    bootbox.alert("Debe ingresar el Nro de Historia");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#dni_titular").val() == "" && $('#frmMedico').find("#id_tipo_beneficiario").val() != "CIVIL") { // CIVIL
    bootbox.alert("Debe ingresar el Documento de Identidad del Titular");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  /*var ind = $('#example tbody tr').length;
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
  }*/

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

/*function AddFila() {

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
});*/

function maxlength_doc(){
  var combo = document.getElementById("id_tipo_doc_ident");
  var tipo_doc_ident = combo.options[combo.selectedIndex].text;
  document.getElementById("tipo_doc_ident").value= tipo_doc_ident;

  if ($("#id_tipo_doc_ident").val() == "1") {
    $("#dni").attr('maxlength', '8');
  } else {
    $("#dni").attr('maxlength', '12');
  }
  document.getElementById("dni").focus();
}

function maxlength_doc_usu() {

  $('#dni_titular').val('');

  if ($("#id_tipo_doc_ident_titular").val() == "1") {
    $("#dni_titular").attr('maxlength', '8');
  } else {
    $("#dni_titular").attr('maxlength', '12');
  }
  document.getElementById("dni_titular").focus();
}

function cambiar_tipo_beneficiario(){
  
  if ($("#id_tipo_beneficiario").val() == "TITULAR") { // TITULAR
	var dni=$('#dni').val();
    $('#dni_titular').val(dni);
	
	var id_tipo_doc_ident=$('#id_tipo_doc_ident').val();
    $('#id_tipo_doc_ident_titular').val(id_tipo_doc_ident);
	
	var id_asegurado=$('#id_asegurado').val();
    $('#id_asegurado_titular').val(id_asegurado);
	
	var datos_paciente = $("#paterno").val()+' '+$("#materno").val()+' '+$("#nombre").val();
	$('#datos_titular').val(datos_paciente);
	
	document.getElementById('dni_titular').readOnly = true;
	document.getElementById('id_tipo_doc_ident_titular').disabled = true;
	document.getElementById('cip').readOnly = false;
	
  } else if ($("#id_tipo_beneficiario").val() == "CIVIL") { // CIVIL
	$('#dni_titular').val('');    
	$('#id_tipo_doc_ident_titular').val(1);
	$('#id_asegurado_titular').val('');
	$('#datos_titular').val('');
	
	document.getElementById('dni_titular').readOnly = true;
  
  } else {
	$('#dni_titular').val('');    
	$('#id_tipo_doc_ident_titular').val(1);
	$('#id_asegurado_titular').val('');
	$('#datos_titular').val('');
	
	document.getElementById("dni_titular").focus();
	document.getElementById('dni_titular').readOnly = false;
	document.getElementById('id_tipo_doc_ident_titular').disabled = false;
	document.getElementById('cip').readOnly = true;
  }
  
}

 $(function () {
	//cambiar_tipo_beneficiario();	 
	//buscar por enter					
	$("#dni_titular").keypress(function (e) {	
		//alert("Pulsaste la tecla con codigo: "+e.which);		  
		if (e.which == 13) {	
			buscar_persona_titular();
		}
	});//FIN dni_titular

	$("#dni").keypress(function (e) {	
		//alert("Pulsaste la tecla con codigo: "+e.which);		  
		if (e.which == 13) {	
			buscar_persona();
		}
	});//FIN dni_titular	
  
});//fin function 

function borrardatos_paciente(){
	document.getElementById('nombre').value="";
	document.getElementById('paterno').value="";
	document.getElementById('materno').value="";
	document.getElementById('sexo').value="";
	document.getElementById('fecha_nac').value="";
	document.getElementById('domicilio').value="";
	document.getElementById('observacion').value="";
	
	document.getElementById('nombre').readOnly = false;
	document.getElementById('paterno').readOnly = false;
	document.getElementById('materno').readOnly = false;
	document.getElementById('sexo').readOnly = false;
	document.getElementById('fecha_nac').readOnly = false;
	document.getElementById('domicilio').readOnly = false;
	document.getElementById('observacion').readOnly = false;
	buscar_persona();
}

function buscar_persona(){
	var dni=$('#dni').val();
	var id_tipo_doc_ident=$('#id_tipo_doc_ident').val();
	
	if(id_tipo_doc_ident>1){
		alert("El tipo de Documento debe ser el DNI");
	}
		$.ajax({
		url: "{{ route('buscar_persona') }}",					
		type: "GET",
		dataType: "JSON",
		data: {						
			dni: dni,
			tipo_documento:id_tipo_doc_ident,
			tipo_busqueda:'reniec'			
		},							
			success: function (data) {				
				if(data==null){ //ocurrio una advertencia
					alert("NO SE ENCONTRÓ EL PACIENTE EN LA RENIEC");	
											
				}else{ //datos table persona	
					//alert(data);	
					document.getElementById('nombre').value=data.nombres;
					document.getElementById('paterno').value=data.paterno;
					document.getElementById('materno').value=data.materno;
					document.getElementById('sexo').value=(data.sexo).substring(0,1);
					document.getElementById('fecha_nac').value=vfecha(data.fecha_nac);
					document.getElementById('domicilio').value=data.direccionDomicilio;
					document.getElementById('observacion').value="reniec";
					
					document.getElementById('nombre').readOnly = true;
					document.getElementById('paterno').readOnly = true;
					document.getElementById('materno').readOnly = true;
					document.getElementById('sexo').readOnly = true;
					document.getElementById('fecha_nac').readOnly = true;
					document.getElementById('domicilio').readOnly = true;			
					document.getElementById('observacion').readOnly = true;	
					
					//document.getElementById('dni').readOnly = true;	
				}
			}				
		}).done(function (data) {
			console.log(data);
		});	//fin done
		//e.preventDefault();
		return false;
}

function borrardatos_titular(){
	$('#cip').val('');
	$('#id_situacion').val('');
	$('#id_unidad').val('');	
	$('#id_grado').val('');
	$('#id_categoria').val('');
	$('#fecha_inicio_servicio').val('');
	
	$('#id_tipo_doc_ident_titular').val('1');
	//$('#dni_titular').val('');
	$('#datos_titular').val('');
	
	$('#id_asegurado_titular').val('');
}

function buscar_persona_titular(){
	var dni_titular=$('#dni_titular').val();
	var id_tipo_doc_ident_titular=$('#id_tipo_doc_ident_titular').val();
	
	if(dni_titular.trim()==""){
		alert("Falta ingresar el Nro de Documento");
		return 0;
	}
	
		$.ajax({
		url: "{{ route('buscar_persona') }}",					
		type: "GET",
		dataType: "JSON",
		data: {						
			dni: dni_titular,
			tipo_documento:id_tipo_doc_ident_titular,
			tipo_busqueda:'tabla_asegurados'			
		},							
			success: function (data) {				
				if(data==null){ //ocurrio una advertencia
					alert("LA PERSONA NO SE ENCUENTRA REGISTRADA");	
											
				}else{ //datos table persona
					document.getElementById('cip').value=data.cip;
					if(data.id_situacion!=null){					
					document.getElementById('id_situacion').value=data.id_situacion;
					document.getElementById('id_unidad').value=data.id_unidad;
					document.getElementById('id_grado').value=data.id_grado;
					document.getElementById('id_categoria').value=data.id_categoria;
					document.getElementById('fecha_inicio_servicio').value=dfecha(data.fecha_inicio_servicio);
					}
					
					document.getElementById('id_tipo_doc_ident_titular').value=data.id_tipo_doc_ident;
					document.getElementById('dni_titular').value=data.nro_doc_ident;					
					document.getElementById('datos_titular').value=data.paterno+' '+data.materno+' '+data.nombre;
					
					document.getElementById('id_asegurado_titular').value=data.id;					
				}
			}				
		}).done(function (data) {
			console.log(data);
		});	//fin done
		//e.preventDefault();
		return false;
}

function buscar_persona_saludpol(){
	var dni_titular=$('#dni_titular').val();
	var id_tipo_doc_ident_titular=$('#id_tipo_doc_ident_titular').val();
	
	if(dni_titular.trim()==""){
		alert("Falta ingresar el Nro de Documento");
		return 0;
	}
	
		$.ajax({
		url: "{{ route('buscar_persona') }}",					
		type: "GET",
		dataType: "JSON",
		data: {						
			dni: dni_titular,
			tipo_documento:id_tipo_doc_ident_titular,
			tipo_busqueda:'tabla_asegurados'			
		},							
			success: function (data) {				
				if(data==null){ //ocurrio una advertencia
					alert("LA PERSONA NO SE ENCUENTRA REGISTRADA");	
											
				}else{ //buscar en saludpol
					document.getElementById('id_tipo_doc_ident_titular').value=data.id_tipo_doc_ident;
					//document.getElementById('dni_titular').value=data.nro_doc_ident;					
					document.getElementById('datos_titular').value=data.paterno+' '+data.materno+' '+data.nombre;									
					document.getElementById('id_asegurado_titular').value=data.id;
					document.getElementById('cip').value=data.cip;				
						$.ajax({
						url: "{{ route('buscar_persona') }}",					
						type: "GET",
						dataType: "JSON",
						data: {						
							dni: dni_titular,
							tipo_documento:id_tipo_doc_ident_titular,
							tipo_busqueda:'saludpol'			
						},							
							success: function (data) {							
								if(data==null){ //ocurrio una advertencia
									alert("LA PERSONA NO SE ENCUENTRA REGISTRADA");	
															
								}else{ //datos table persona									
									if(data.parentesco=="TITULAR"){
										if(data.nrodoctitular == document.getElementById('dni').value){
											document.getElementById('id_tipo_beneficiario').value="TITULAR";
										}										
										document.getElementById('cip').value=data.cip;
										
										//SITUACION	
										document.getElementById('id_situacion').value=data.situacion;										
										if(document.getElementById('id_situacion').value==""){
											const option_s = document.createElement('option');
											option_s.value = data.situacion;
											option_s.text = data.situacion;										
											document.getElementById('id_situacion').appendChild(option_s);
											document.getElementById('id_situacion').value=data.situacion;
										}
										
										//GRADO
										document.getElementById('id_grado').value=data.grado;										
										if(document.getElementById('id_grado').value==""){
											const option = document.createElement('option');
											option.value = data.grado;
											option.text = data.grado;										
											document.getElementById('id_grado').appendChild(option);
											document.getElementById('id_grado').value=data.grado;
										}
										
										//document.getElementById('id_unidad').value=data.id_unidad;
										//document.getElementById('id_categoria').value=data.id_categoria;
										//document.getElementById('fecha_inicio_servicio').value=dfecha(data.fecha_inicio_servicio);
									}else if(data.parentesco==undefined){
										alert("NO TIENE SEGURO SALUDPOL");
									}else{
										alert("NO ES UN PACIENTE TITULAR");		
									}													
								}
							}				
						}).done(function (data) {
							console.log(data);
						});	//fin done
						//e.preventDefault();
						return false;				
				}
			}				
		}).done(function (data) {
			console.log(data);
		});	//fin done
		//e.preventDefault();
		return false;
}

function cambiar_establecimiento(){
	var id_establecimiento=$('#id_establecimiento').val();
	var id_asegurado=$('#id_asegurado').val();
		$.ajax({
		url: "{{ route('buscar_paciente_ipress') }}",					
		type: "GET",
		dataType: "JSON",
		data: {						
			id_ipress: id_establecimiento,
			id_ase:id_asegurado			
		},							
			success: function (data) {				
				if(data==null){ //ocurrio una advertencia
					alert("NRO DE HISTORIA NO EXISTE");	
											
				}else{ //datos table persona	
					document.getElementById('nro_historia').value=data[0].nro_historia;
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
        <li><a href="/all_asegurados/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Pacientes</a>
        </li>
        <li class="active"><a href="/buscar_paciente"><i class="fa fa-plus"></i> Nuevo </a></li>       
      </ul>
    </div>
    <div class="col-md-7 well">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">Agregar Paciente</h3>
          </div>
          @if(count($personal)>0)
          <?php
			$id_tipo_doc_ident = $personal->id_tipo_doc_ident;
			$tipo_doc_ident = $personal->tipo_doc_ident;
			$nro_doc_ident = $personal->nro_doc_ident;
		    $nompersonal = $personal->nombre;
            $patpersonal = $personal->paterno;
            $matpersonal = $personal->materno;
            $sexo = $personal->sexo;
            $fecha_nac = $personal->fecha_nac;
			$telefono = $personal->telefono;
			$correo = $personal->correo;
			$domicilio = $personal->domicilio;	
			$observacion = $personal->observacion;
			$cip= $personal->cip;
			$id_tipo_beneficiario= $personal->id_tipo_beneficiario;
			$id_asegurado_titular= $personal->id_asegurado_titular;
            $readdatosper = "";
			
			//Obtener Datos del titular
			$cip=""; $id_situacion=""; $id_unidad=""; $id_grado=""; $id_categoria=""; $fecha_inicio_servicio=""; $id_tipo_doc_ident_titular=""; $dni_titular=""; $datos_titular="";
			if($id_asegurado_titular>0){
				if($personadatosadicional!=NULL){//cip, adi.id_situacion, adi.id_unidad, adi.id_grado, adi.id_categoria, adi.fecha_inicio_servicio					
					$cip = $personadatosadicional->cip;
					
					$model_1= new  App\Models\Situacion();
					$model_2= new  App\Models\Unidadpago();
					$model_3= new  App\Models\Tipo_grado();
					$model_4= new  App\Models\Tipo_categoria();

					if($personadatosadicional->id_situacion!='' or $personadatosadicional->id_situacion!=null):
						$busca_situacion= $model_1->BuscaSituacionD($personadatosadicional->id_situacion);
						$id_situacion=$busca_situacion->descripcion;
					else:
						$id_situacion=8;
					endif;
					
					if($personadatosadicional->id_unidad!='' or $personadatosadicional->id_unidad!=null):
						$busca_unidad= $model_2->BuscaUnidadD($personadatosadicional->id_unidad);
						$id_unidad=$busca_unidad->descripcion_larga;
					else:
						$id_unidad=441;
					endif;
					
					if($personadatosadicional->id_grado!='' or $personadatosadicional->id_grado!=null):
						$busca_grado= $model_3->BuscaGradoD($personadatosadicional->id_grado);
						$id_grado=$busca_grado->descripcion;
					else:
						$id_grado=999;
					endif;

					if($personadatosadicional->id_categoria!='' or $personadatosadicional->id_categoria!=null):
						$busca_categoria= $model_4->BuscaTipoCategoriaD($personadatosadicional->id_categoria);
						$id_categoria=$busca_categoria->descripcion;
					else:
						$id_categoria=99;
					endif;


					if($personadatosadicional->fecha_inicio_servicio!=null):
						$fecha_inicio_servicio = dfecha($personadatosadicional->fecha_inicio_servicio);	
					else:
						$fecha_inicio_servicio = '';
					endif;
					$id_tipo_doc_ident_titular= $personadatosadicional->id_tipo_doc_ident;
					$dni_titular = $personadatosadicional->nro_doc_ident;
					$datos_titular = $personadatosadicional->paterno.' '.$personadatosadicional->materno.' '.$personadatosadicional->nombre;
				}
				$readdatostit = " ";
			}else{
				$readdatostit = " readonly";
			}
          ?>
          <div style="margin-top: 20px">
            <form id="frmMedico" class="form-horizontal" role="form" method="post" action="{{ route('update_paciente') }}">
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
							<select class="form-control input-sm" id="id_tipo_doc_ident" name="id_tipo_doc_ident"
							  onchange="maxlength_doc()" tabindex="3"> 							  
							    @foreach($tipo_documento_identidad as $e)
								<option value="{{$e->id}}" <?php if ($e->id == $id_tipo_doc_ident) {echo " selected";} ?>> {{$e->descripcion}}</option>
								@endforeach
							</select>
							<input id="tipo_doc_ident" name="tipo_doc_ident" type="hidden" value="{{$tipo_doc_ident}}">
						</div>
						<div class="col-md-4">
						  <label>Documento</label>
						  <input id="dni" placeholder="Digite y ENTER" name="dni" type="text" value="{{$nro_doc_ident}}" class="form-control input-sm" required="" onkeypress="return IsNumber(event)" onchange="borrardatos_paciente()">
						  <input id="id_user" name="id_user" type="hidden" value="{{Auth::user()->id}}">
						</div>
						<div class="col-md-2">
						 <label></label>
						 <input class="form-control btn-primary"  id="buscar" name="buscar" type="button" value="Reniec" onclick="buscar_persona();" />
						  
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
						  <label>Domicilio</label>
						  <input id="domicilio" name="domicilio" value="<?php echo $domicilio; ?>" type="text" placeholder="" class="form-control input-sm" >
						</div>
						<div class="col-md-6">
						  <label>Observacion</label>
						  <input id="observacion" name="observacion" type="text" value="<?php echo $observacion; ?>" placeholder="" class="form-control input-sm">						  
						</div>	
						<div class="col-md-6">
						   <label>Telefono</label>
						  <input id="telefono" name="telefono" value="<?php echo $telefono; ?>" type="text" placeholder="" class="form-control input-sm" >
						</div>							
						<div class="col-md-6">
						  <label>E-mail</label>
						  <input id="correo" name="correo" type="email" value="<?php echo $correo; ?>" placeholder="" class="form-control input-sm">
						</div>

						<div class="col-md-6">
						  <label>Tipo Beneficiario</label>
						  <select id="id_tipo_beneficiario" name="id_tipo_beneficiario" class="form-control input-sm" required="" onchange="cambiar_tipo_beneficiario()">
							<option value="">SELECCIONE</option>
							@foreach($tipo_beneficiario as $e)
							<option value="{{$e->descripcion}}" <?php if ($e->descripcion == $id_tipo_beneficiario) {echo " selected";} ?> > {{$e->descripcion}}</option>
							@endforeach
						  </select>
						  <input id="id_asegurado" name="id_asegurado" value="{{ $id_asegurado }}" type="hidden" >
						  <input id="id_asegurado_titular" name="id_asegurado_titular" value="{{ $id_asegurado_titular }}" type="hidden" >
						</div>
						
											
					</div>
					
					<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
					
						<?php if($id_ipress==0){  ?>
						
						@foreach($establecimiento as $es)
						<div class="col-md-6">
						  <label>Establecimiento (IPRESS PNP)</label>						  
						  <!-- <select id="id_establecimiento" name="id_establecimiento" class="form-control input-sm" required=""
							style="width: 100%" onchange="cambiar_establecimiento()">
							<option value="">SELECCIONE</option>
							@foreach($establecimiento as $e)
							<option value="{{$e->id_establecimiento}}">
							  {{$e->establecimiento}}
							</option>
							@endforeach
						  </select>	
						  <input id="id_historia" name="datos_historias[]" value="{{$es->id_establecimiento.'-'.$es->nro_historia}}" type="text" > 
						  -->		
							 <input id="nombre_est" name="nombre_est" value="{{$es->establecimiento}}" type="text" class="form-control input-sm" readOnly >
							 <input id="id_establecimientos" name="id_establecimientos[]" value="{{$es->id_establecimiento}}" type="hidden" class="form-control input-sm" >	
						</div>
						
						<div class="col-md-6">
						  <label>Historia</label>
						  <input id="nro_historias" name="nro_historias[]" value="{{$es->nro_historia}}" type="text" class="form-control input-sm" >
						</div>
							
						@endforeach
						
						<?php   }else{ ?>
						<div class="col-md-12">
						  <label>Establecimiento (IPRESS PNP)</label>						  
							  <input id="nombre_est" name="nombre_est" value="{{$nombre_establecimiento}}" type="text" class="form-control input-sm" readOnly >
							  <input id="id_establecimiento" name="id_establecimiento" value="{{$id_ipress}}" type="hidden" class="form-control input-sm" >						  
						</div>
						
						<div class="col-md-12">
						  <label>Historia</label>
						  <input id="nro_historia" name="nro_historia" value="{{$nro_historia}}" type="text" placeholder="" class="form-control input-sm" >
						</div>						
						<?php } ?>						
						
					</div>
					
					<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
						<div class="col-md-6">
						    <label>Tipo Documento</label>
						    <select class="form-control input-sm" id="id_tipo_doc_ident_titular" name="id_tipo_doc_ident_titular"
							  onchange="maxlength_doc_usu()" tabindex="3"> 							  
							    @foreach($tipo_documento_identidad as $e)
								<option value="{{$e->id}}" <?php if ($e->id == $id_tipo_doc_ident_titular) {echo " selected";} ?> > {{$e->descripcion}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-4">
						  <label>Documento</label>
						  <input autocomplete="off" id="dni_titular" placeholder="Digite y ENTER" name="dni_titular" type="text" value="{{ $dni_titular }}" <?php echo $readdatostit ?> class="form-control input-sm" onkeypress="return IsNumber(event)" onchange="borrardatos_titular()">
						</div>
						<div class="col-md-2">
						 <label></label>
						 <input class="form-control btn-primary"  id="buscar" name="buscar" type="button" value="Saludpol" onclick="buscar_persona_saludpol();" />
						  
						</div>
						
						<div class="col-md-12">
						  <label>Apellidos y Nombres</label>
						  <input id="datos_titular" name="datos_titular" value="{{ $datos_titular }}" type="text" placeholder="" class="form-control input-sm" readOnly >
						</div>
						
						<div class="col-md-6">
						  <label>CIP</label>
						  <input id="cip" name="cip" value="{{ $cip }}" type="text" placeholder="" class="form-control input-sm" readOnly >
						</div>
						
						<div class="col-md-6">
						  <label>Grado</label>
						  <select id="id_grado" name="id_grado" class="form-control input-sm" required="" >
							<option>SELECCIONE</option>
							@foreach($grado as $e)
							<option value="{{$e->descripcion}}" <?php if ($e->descripcion == $id_grado) {echo " selected";} ?> > {{$e->descripcion}}</option>
							@endforeach
							<?php 
								if($id_grado!=""){
							?>
							<option value="{{$id_grado}}" <?php if ($id_grado == $id_grado) {echo " selected";} ?> > {{$id_grado}}</option>							
							<?php									
								}
							?>
						  </select>
						</div>
						
						<div class="col-md-6">
						  <label>Categoria</label>
						  <select id="id_categoria" name="id_categoria" class="form-control input-sm" required="" >
							<option>SELECCIONE</option>
							@foreach($categoria as $e)
							<option value="{{$e->descripcion}}" <?php if ($e->descripcion == $id_categoria) {echo " selected";} ?> > {{$e->descripcion}}</option>
							@endforeach
						  </select>
						</div>
						
						<div class="col-md-6">
						  <label>Situación</label>
						  <select id="id_situacion" name="id_situacion" class="form-control input-sm" required="" >
							<option>SELECCIONE</option>
							@foreach($situacion as $e)
							<option value="{{$e->descripcion}}" <?php if ($e->descripcion == $id_situacion) {echo " selected";} ?> > {{$e->descripcion}}</option>
							@endforeach
						  </select>
						</div>
						
						<div class="col-md-6">
						  <label>Unidad</label>
						  <select id="id_unidad" name="id_unidad" class="form-control input-sm" required="" >
							<option>SELECCIONE</option>
							@foreach($unidadpago as $e)
							<option value="{{$e->descripcion_larga}}" <?php if ($e->descripcion_larga == $id_unidad) {echo " selected";} ?> > {{$e->descripcion_larga}}</option>
							@endforeach
						  </select>
						</div>
						
						<div class="col-md-6">
						  <label>F. ini Servicio</label>
						  <input type="text" class="form-control datepicker" value="{{$fecha_inicio_servicio}}" name="fecha_inicio_servicio" id="fecha_inicio_servicio" autocomplete="off"/>
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