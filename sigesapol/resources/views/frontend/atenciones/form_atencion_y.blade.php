@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<style>
  .loader {
    width: 100%;
    height: 100%;
    /*height: 1500px;*/
    overflow: hidden;
    top: 0px;
    left: 0px;
    z-index: 10000;
    text-align: center;
    position: absolute;
    background-color: #000;
    opacity: 0.6;
    filter: alpha(opacity=40);
    display: none;
  }
  .dataTables_processing {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 500px !important;
    font-size: 1.7em;
    border: 0px;
    margin-left: -17% !important;
    text-align: center;
    background: #3c8dbc;
    color: #FFFFFF;
  }
  .modal-dialog {
    width: 90%;
  }
  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
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
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }
  .ui-autocomplete>li>div {
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
    color: #FFFFFF !important;
    background: #1d71b8 !important;
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
  input[readonly] {
    background-color: #eeeeee;
    text-align: center
  }
  span:focus,
  textarea:focus,
  input[type="text"]:focus,
  input[type="password"]:focus,
  input[type="datetime"]:focus,
  input[type="datetime-local"]:focus,
  input[type="date"]:focus,
  input[type="month"]:focus,
  input[type="time"]:focus,
  input[type="week"]:focus,
  input[type="number"]:focus,
  input[type="email"]:focus,
  input[type="url"]:focus,
  input[type="search"]:focus,
  input[type="tel"]:focus,
  input[type="color"]:focus,
  .uneditable-input:focus {
    border-color: #2196F3;
    box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
    outline: 0 none;
  }
  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    /*width:150px;*/
    margin: 10px;
    text-align: center;
    margin-top: 40px;
    /*background:#5cb85c*/
    background: transparent;
    color: #009966
  }
  .fc-day-grid-event :hover {
    color: #009966
  }
  .fc-content {
    padding: 5px !important;
  }
  .fc-title {
    text-align: center;
    font-size: 12px
  }
  /*
.select2-container:focus {
	border-color: rgba(126, 239, 104, 0.8);
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6);
	outline: 0 none;
}
*/
  /*
.select2-container-active .select2-choice {
  border: 1px solid #999;
  box-shadow: inset 0 2px 3px 0 rgba(0,0,0,.06);
  border-radius: 4px;
} 
 
.select2-container-active.select2-dropdown-open .select2-choice {
  border-bottom: 2px solid #ccc;
  border-left: 1px solid #ccc;
  border-right: 1px solid #ccc;
  border-top: 1px solid #ccc;
  box-shadow: none;
}
*/
</style>

<script>
	$("#div_uci_ventilador").hide();
	function getVentiladorUci(){
	    var uci = $('#id_uci').val();
	    if(uci=='1'){
	        $("#div_uci_ventilador").show();
	    }
	    else{
	        $("#div_uci_ventilador").hide();
	    }
	}
	function getAmbulancia(){
	    var ambulancia = $('#id_ambulancia').val();
	    if(ambulancia=='1'){
	        $("#div_ambulancia").show();
	    }
	    else{
	        $("#div_ambulancia").hide();
	    }
	}
  $(document).ready(function() {
	$('#fecha_atencion').datepicker();
	
	/*$(".fc-event-container").on("click", function (e) {
		alert("cssc");
	});*/
	
	$('input[name="parent"]').on("change", function (e) {
		getServicio();
	});
	
	$('#id_servicio,#id_establecimiento').on("change", function (e) {
		$('#calendar').fullCalendar('destroy'); 
		fullCalendar();
		modalTurnos();
		modalHistorial();
	});
	
	
	$('#btnPrestacion').on("click", function (e) {
		guardarPrestacion();
	});
	
	$('#addDiagnostico').on('click',function(){
		AddDiagnostico();
	});
	
	$('#addProcedimiento').on('click',function(){
		AddProcedimiento();
	});
	$('#addExamenLab2').on('click',function(){
		AddExamenLab();
	});
	$('#addExamenRad2').on('click',function(){
		AddExamenRad();
	});
	
	$('#addProducto').on('click', function () {
		AddProducto();
	});
	$('#addDerivarServicio').on('click', function () {
		var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
		$("#divServicio").hide();
		if(addDerivarServicio == "false"){
			$("#divServicio").show();
		}
	});
    $('#addRegistrarCita').on('click', function () {
      var addRegistrarCita = $('#addRegistrarCita').attr("aria-pressed");
      $('#registrar_cita').val(addRegistrarCita);
    });
	$('#addAlta').on('click', function () {
		var addAlta = $('#addAlta').attr("aria-pressed");
		$("#divAlta2").hide();
		if(addAlta == "false"){
			$("#divAlta2").show();
		}
	});
	$("#id_modalidad").select2({
	    width: 'resolve'
	});
	
	$('#addExamenLab').on('click', function () {
		var addExamenLab = $('#addExamenLab').attr("aria-pressed");
		$("#fsExamenLab").hide();
		if(addExamenLab == "false"){
			$("#fsExamenLab").show();
		}
	});
	
	
	$('#addExamenRad').on('click', function () {
		var addExamenRad = $('#addExamenRad').attr("aria-pressed");
		$("#fsExamenRad").hide();
		if(addExamenRad == "false"){
			$("#fsExamenRad").show();
		}
	});
	$('#addRecetaVale').on('click', function () {
		var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
		$("#fsRecetaVale").hide();
		if(addRecetaVale == "false"){
           //adicionado
           getProductoAndAddProducto();
	   //getProducto();
	   $("#fsRecetaVale").show();
	  }
	});
	
	$('#addCondicion').on('click', function () {
		var addCondicion = $('#addCondicion').attr("aria-pressed");
		$("#divCondicion").hide();
		$("#condicion").val(0);
		if(addCondicion == "false"){
			$("#divCondicion").show();
			$("#condicion").val(1);
		}
	});
	$('#addSignosVitales').on('click', function () {
    var addSignosVitales = $('#addSignosVitales').attr("aria-pressed");
    $("#fsSignosVitales").hide();
    if(addSignosVitales == "false"){
      $("#fsSignosVitales").show();
    }
  });
	$('#addInterconsulta').on('click', function () {
		var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
		$("#fsInterconsulta").hide();
		if(addInterconsulta == "false"){
			$("#fsInterconsulta").show();
		}
	});
	$('#addInterconsulta2').on('click',function(){
		AddInterconsulta2();
	});
	$('#addReferencia').on('click', function () {
		var addReferencia = $('#addReferencia').attr("aria-pressed");
		$("#fsReferencia").hide();
		if(addReferencia == "false"){
			$("#fsReferencia").show();
		}
	});
	
	$('#example tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
		obj.parent().parent().remove();
	});
	
	$('#tblDiagnostico tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
		obj.parent().parent().remove();
	});
	
	$('#tblProcedimiento tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
		obj.parent().parent().remove();
	});
	$('#tblInterconsulta tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
		obj.parent().parent().remove();
	});
	
	$('#tblExamenLab tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
		obj.parent().parent().remove();
	});
	$('#tblExamenRad tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
		obj.parent().parent().remove();
	});
    $("#id_servicio_derivar").select2({
        width: 'resolve'
    });
    $("#id_servicio_interconsulta").select2({
        width: 'resolve'
    });
    $("#id_establecimiento_referencia").select2({
        width: 'resolve'
    });
    $("#id_uci").select2({
        width: 'resolve'
    });
    $("#id_uci_cv").select2({
        width: 'resolve'
    });
    $("#id_ambulancia").select2({
        width: 'resolve'
    });
    $("#id_ambulancia_ref").select2({
        width: 'resolve'
    });
	
	$("#id_tipo_ambulancia").select2({
        width: 'resolve'
    });
    $("#id_pasaje").select2({
        width: 'resolve'
    });
    $("#id_acompanante").select2({
        width: 'resolve'
    });
	
	$("#id_situacion").select2({
        width: 'resolve'
    });
	$("#id_farmacia_receta").select2({
        width: 'resolve'
    });
	$("#upss_bene").select2({
        width: 'resolve'
    });
    $("#ipress_bene").select2({
        width: 'resolve'
    });
    $("#cmbServicio0").select2({
        width: 'resolve'
    });
    $("#codigo_upss").select2({
	    width: 'resolve'
	  });
    $("#temperatura").inputmask("decimal", {
    digits: 2,
    placeholder: "0.00",
    allowMinus: false,
    allowPlus: false,
    max: 150,
    digitsOptional: false
  });
  $("#talla").inputmask("decimal", {
    digits: 0,
    placeholder: "0",
    allowMinus: false,
    allowPlus: false,
    max: 250,
    digitsOptional: false
  });
  $("#peso").inputmask("decimal", {
    digits: 2,
    placeholder: "0.00",
    allowMinus: false,
    allowPlus: false,
    max: 500,
    digitsOptional: false
  });
  $("#saturacion").inputmask("decimal", {
    digits: 0,
    placeholder: "0",
    allowMinus: false,
    allowPlus: false,
    max: 100,
    digitsOptional: false
  });
  $("#tamanio_cintura").inputmask("decimal", {
    digits: 0,
    placeholder: "0",
    allowMinus: false,
    allowPlus: false,
    max: 200,
    digitsOptional: false
  });
  $("#frecuencia_cardiaca_fetal").inputmask("decimal", {
    digits: 0,
    placeholder: "0",
    allowMinus: false,
    allowPlus: false,
    max: 200,
    digitsOptional: false
  });
  $("#presion_arterial_x").inputmask("decimal", {
    digits: 0,
    placeholder: "0",
    allowMinus: false,
    allowPlus: false,
    max: 200,
    digitsOptional: false
  });
  $("#presion_arterial_y").inputmask("decimal", {
    digits: 0,
    placeholder: "0",
    allowMinus: false,
    allowPlus: false,
    max: 200,
    digitsOptional: false
  });
  $("#frecuencia_respiratoria").inputmask("decimal", {
    digits: 0,
    placeholder: "0",
    allowMinus: false,
    allowPlus: false,
    max: 100,
    digitsOptional: false
  });
  $("#frec_cardiaca").inputmask("decimal", {
    digits: 0,
    placeholder: "0",
    allowMinus: false,
    allowPlus: false,
    max: 250,
    digitsOptional: false
  });
  $("#oxigenacion").inputmask("decimal", {
    digits: 0,
    placeholder: "0",
    allowMinus: false,
    allowPlus: false,
    max: 100,
    digitsOptional: false
  });
    
	AddDiagnostico();
	AddProcedimiento();
	//AddExamenLab();
	//AddExamenRad();
    getProducto();
	modalHistorial();
	modalLaboratorio();
	modalRadiologico();
	modalAntecedentes();
  @if($cita->id_estado_cita == 9)
  activarToggleSignosVitales();
  @endif
			
});
function activarToggleSignosVitales() {
  var addSignosVitales = $('#addSignosVitales').attr("aria-pressed");
  $("#fsSignosVitales").hide();
  if(addSignosVitales == "false"){
    $("#fsSignosVitales").show();
  }
}
function compruebaIMC(){ 
  
  peso = document.frmPrestacion.peso.value;
  talla = document.frmPrestacion.talla.value;
  if (talla != "") { 
      //PESO POR ALTURA
      var sexo = document.frmPrestacion.nomsexo.value; //MASCULINO , FEMENINO
      //edad
      var edad_anos = document.frmPrestacion.edadpaciente.value; //12/05/1992
      
      var pesoIdeal=50+((talla-150)/4)*3+(edad_anos-20)/4
      if(sexo=="M") {
            pesoIdeal=pesoIdeal*0.9;
      }
      document.frmPrestacion.peso_x_altura.value = pesoIdeal.toFixed(2);
  }else{
     document.frmPrestacion.peso_x_altura.value = "";
  }
  if (peso != "" && talla != "") { 
      var talla_en_metros =  talla*0.01;     
      //IMC
      var imcx=  ( peso / (talla_en_metros * talla_en_metros) ).toFixed(2);
      document.frmPrestacion.imc.value = imcx;
  } else {
      document.frmPrestacion.imc.value = '';
  }
}
function getProducto(){
	//$("#turno").val('').trigger("change");
	//var newOption = "";
	//newOption = "<option value=''>--Seleccionar-</option>";
	//$('#id_medico').html(newOption);
	//$("#id_medico").val('').trigger("change");
	
	$('#example tbody').html('');
	
	if($('#id_servicio').val()=="") return false;
	
	//var id_ipress 		= $('#id_ipress').val();
	var id_ipress 		= $('#id_establecimiento').val();
	var id_consultorio 	= $('#id_servicio').val();
	//var tipo_receta		= $('#tipo_receta').val();
	//var id_tiporeceta 	= $('#id_tiporeceta').val();
	var id_tiporeceta 	= $('#id_tipo_atencion').val();
	
	var id_farmacia     = $('#id_farmacia_receta').val();
	var nivel     = $('#id_nivel').val();
	var x  = $('#nro_interconsultas').val();
	var dni = $('#dni_beneficiario').val();
	var interconsulta = $('#interconsulta').val();
	if(id_tiporeceta == "3"){ 
		
		if(interconsulta=='SI'){
		  if (id_consultorio==104){
			id_consultorio2=104
		  }
		  else
		  {
			id_consultorio2=105
		  }
			$.ajax({
			  //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + id_consultorio2+ '/' + dni,
			  url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +id_consultorio2,
			  success: function(result){                    
				var newOption = "";
				newOption = "<option value=''>--Seleccionar--</option>";
				$(result).each(function (ii, oo) {
				  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
				});                 
				$('#cmbMedicamentoTemp').html(newOption);
			  }
			}); 
		}
		else
		{  
		  
		  if(x==0){ //SIN INTERCONSULTA
			if (id_consultorio==104){
			  $.ajax({
				url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 104,
				//url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
				success: function(result){                    
				  var newOption = "";
				  newOption = "<option value=''>--Seleccionar--</option>";
				  $(result).each(function (ii, oo) {
					newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
				  });                 
				  $('#cmbMedicamentoTemp').html(newOption);
				}
			  });
			}
			else
			{
			  if (id_consultorio==21){
				$.ajax({
				  url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 21,
				  //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
				  success: function(result){                    
					var newOption = "";
					newOption = "<option value=''>--Seleccionar-</option>";
					$(result).each(function (ii, oo) {
					  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
					});                 
					$('#cmbMedicamentoTemp').html(newOption);
					//$("#id_medico").select2({max_selected_options: 4});
				  
				  }
				});
			  }
			  else
			  {
				if (id_consultorio==72){
					$.ajax({
					  //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
					  url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 72,
					  success: function(result){                    
						var newOption = "";
						newOption = "<option value=''>--Seleccionar-</option>";
						$(result).each(function (ii, oo) {
						  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
						});                 
						$('#cmbMedicamentoTemp').html(newOption);
					  }
					});
				}
				else
				{
					$.ajax({
					  url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
					  //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
					  success: function(result){                    
						var newOption = "";
						newOption = "<option value=''>--Seleccionar-</option>";
						$(result).each(function (ii, oo) {
						  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
						});                 
						$('#cmbMedicamentoTemp').html(newOption);
					  }
					});  
				}
				
			  }
			}
		  }
		  else
		  {
			if (id_consultorio==104){
			  $.ajax({
				url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
				success: function(result){                    
				  var newOption = "";
				  newOption = "<option value=''>--Seleccionar--</option>";
				  $(result).each(function (ii, oo) {
					newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
				  });                 
				  $('#cmbMedicamentoTemp').html(newOption);
				}
			  });
			}
			else
			{
			  if (id_consultorio==21){
				$.ajax({
				  url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 21 + '/' + dni,
				  success: function(result){                    
					var newOption = "";
					newOption = "<option value=''>--Seleccionar-</option>";
					$(result).each(function (ii, oo) {
					  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
					});                 
					$('#cmbMedicamentoTemp').html(newOption);
				  }
				});
			  }
			  else
			  {
				if (id_consultorio==72){
					$.ajax({
					  //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
					  url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 72,
					  success: function(result){                    
						var newOption = "";
						newOption = "<option value=''>--Seleccionar-</option>";
						$(result).each(function (ii, oo) {
						  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
						});                 
						$('#cmbMedicamentoTemp').html(newOption);
					  }
					});
				}
				else
				{
					$.ajax({
					  url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 105+ '/' + dni,
					  success: function(result){                    
						var newOption = "";
						newOption = "<option value=''>--Seleccionar-</option>";
						$(result).each(function (ii, oo) {
						  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
						});                 
						$('#cmbMedicamentoTemp').html(newOption);
					  }
					});  
				}
				
			  }
			}  
		  }          
		}       
	}else{
	
		
		if(id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia!=18 && id_farmacia!=25){
			if(id_tiporeceta==2){
        id_consultorio=76;
      }
			$.ajax({
				url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
				success: function(result){                    
					var newOption = "";
					newOption = "<option value=''>--Seleccionar-</option>";
					$(result).each(function (ii, oo) {
						newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
					});                 
					$('#cmbMedicamentoTemp').html(newOption);
					//$("#id_medico").select2({max_selected_options: 4});
				
				}
			});
		
		}
		if(id_tiporeceta == 5 || id_tiporeceta == 6 || id_farmacia == 21){ //nutricional
			if(id_tiporeceta==5){
				id_consultorio = 110;
			}
			else{
				id_consultorio = 111;
			}
				
		
			$.ajax({
				url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
				success: function(result){                    
					var newOption = "";
					newOption = "<option value=''>--Seleccionar-</option>";
					$(result).each(function (ii, oo) {
						newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
					});                 
					$('#cmbMedicamentoTemp').html(newOption);
					//$("#id_medico").select2({max_selected_options: 4});
				
				}
			});
		
		}
		if(id_farmacia==18 || id_farmacia==25 || id_tiporeceta == 4){ //centro quirurgico
			if(id_tiporeceta == 1){
        $.ajax({
          url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
          success: function(result){                    
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $(result).each(function (ii, oo) {
              newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
            });                 
            $('#cmbMedicamentoTemp').html(newOption);
          }
        });
      }
      else
      {
      	if(id_tiporeceta == 4){
	        $.ajax({
	          url: '/obtener_producto_por_idipress_idconsultorio_quirurgico/' + id_ipress+ '/' + id_consultorio,
	          success: function(result){                    
	            var newOption = "";
	            newOption = "<option value=''>--Seleccionar-</option>";
	            $(result).each(function (ii, oo) {
	              newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
	            });                 
	            $('#cmbMedicamentoTemp').html(newOption);
	            //$("#id_medico").select2({max_selected_options: 4});
	          
	          }
	        });
	      }
      }
		}
	 }
	 
	 /*
	 if(id_tiporeceta != 1 || id_farmacia == 22){
		$.ajax({
		url: '/obtener_medico_por_idipress_idconsultorio_solo_emergencia/' + id_ipress+ '/' + id_consultorio,
		success: function(result){                    
			var newOption = "";
			newOption = "<option value=''>--Seleccionar-</option>";
			$(result).each(function (ii, oo) {
			newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
			});                 
			$('#id_medico').html(newOption);
			$("#id_medico").select2({max_selected_options: 4});
		}
		});
	}
  	*/
	
  
}
function getSubConsultorio() {
	
	var id_establecimiento = $('#id_establecimiento').val();
	var id_consultorio = $('#id_servicio').val();
	var id_tipo_atencion = $('#id_tipo_atencion').val();
	$.ajax({
		//url: '/obtener_servicio_por_id_establecimiento_and_id_medico/' + id_establecimiento + '/' + id_medico + '/' + id_tipo_atencion,
			url: '/obtener_sub_consultorio_por_id_establecimiento_and_id_servicio/' + id_establecimiento + '/' + id_consultorio,
			success: function (result) {
			var newOption = "";
			var reg = result.length;
			if(reg >= 2){
				newOption = "<option value='0'>--Seleccionar-</option>";
			}
			$(result).each(function (ii, oo) {
				newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
			});
			$('#id_sub_consultorio').html(newOption);
			$("#id_sub_consultorio").select2({max_selected_options: 4});
		}
	});
}
$("#id_farmacia_receta").select2({
        width: 'resolve'
    });
function guardarPrestacion(){
    
	var msgLoader = "";
	msgLoader = "Procesando, espere un momento por favor";
	$('#btnPrestacion').prop("disabled",true);
	var heightBrowser = $(window).width()/2;
	$('.loader').css("opacity","0.8").css("height",heightBrowser).html("<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>"+msgLoader+"</div></div>");
    $('.loader').show();
	
    var msg = "";
    
	var id_tipo_atencion = $('#id_tipo_atencion').val();
	var id_modalidad = $('#id_modalidad').val();
	var fecha_atencion = $('#fecha_atencion').val();
	var id_servicio= $('#id_servicio').val();
	var id_sub_consultorio= $('#id_sub_consultorio').val();
	var dni_beneficiario= $('#dni_beneficiario').val();
	var beneficiario= $('#beneficiario').val();
	var id_diagnostico = $('input[name="id_diagnostico[]"]').map(function(){return $(this).val();}).get();
	var id_procedimiento = $('input[name="id_procedimiento[]"]').map(function(){return $(this).val();}).get();
	var procedimiento_cantidad = $('input[name="procedimiento_cantidad[]"]').map(function(){return $(this).val();}).get();
	var id_tipo_diagnostico = $('input[name="id_tipo_diagnostico[]"]').map(function(){return $(this).val();}).get();
  var fecha_expedicion = $('#fecha_expedicion').val();
  var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
	var id_servicio_derivar= $('#id_servicio_derivar').val();
	var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
	var addExamenLab = $('#addExamenLab').attr("aria-pressed");
	var addExamenRad = $('#addExamenRad').attr("aria-pressed");
	var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
  var addReferencia = $('#addReferencia').attr("aria-pressed");
	var addCondicion = $('#addCondicion').attr("aria-pressed");
	var cantidadReceta = $('#cantidadReceta').val();
	
    if(id_tipo_atencion=="")msg+="Debe ingresar un Tipo de Atencion<br>";
	if(fecha_atencion=="")msg+="Debe ingresar una Fecha de Atencion<br>";
	if(id_servicio=="0")msg+="Debe ingresar un Servicio<br>";
	if(id_sub_consultorio=="0")msg+="Debe ingresar un Consultorio<br>";
	if(dni_beneficiario=="" && beneficiario=="")msg+="Debe buscar un Asegurado<br>";
	if(id_tipo_atencion==1){
		if(id_modalidad=="0")msg+="Debe elegir una modalidad de atención<br>";
	}
	
	//if(id_diagnostico=="")msg+="Debe ingresar minimo un diagnostico<br>";
	var ind_diag = $('#tblDiagnostico tbody tr').length;
	if(ind_diag > 0){
		fila_dignosticos=$('#tblDiagnostico tbody').children('tr');
		for (i = 0; i < ind_diag; i++) {
			fila_dignostico=fila_dignosticos[i];
			var id_diagnostico = $(fila_dignostico).find('.id_diagnostico').val();
			var id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico').val();
			if(id_diagnostico == "")msg+="Debe ingresar el nombre de diagnostico<br>";
			if(id_tipo_diagnostico == "0")msg+="Debe ingresar el tipo de diagnostico<br>";
		}
	}else{
		msg+="Debe ingresar minimo un diagnostico<br>";
		msg+="Debe ingresar el tipo de diagnostico<br>";
	}
	
	if(id_procedimiento=="")msg+="Debe ingresar minimo un procedimiento<br>";
	if(procedimiento_cantidad=="")msg+="Debe ingresar una cantidad en un procedimiento<br>";
    if(fecha_expedicion=="")msg+="Debe ingresar una Fecha de Expedicion<br>";
   	if(addDerivarServicio == "true" && id_servicio_derivar=="0")msg+="Debe ingresar un Servicio para Derivar<br>";
	if(addCondicion == "true" && cantidadReceta=="0")msg+="Debe ingresar la cantidad de dias de la Receta Vale<br>";
	
	if(addRecetaVale == "true"){
		var ind = $('#example tbody tr').length;        
		if(ind > 0){
			var nind = ind-1;
			var cmbMedicamento = $("#cmbMedicamento"+nind).val();
			if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == ""){
				$('#example .deleteFila').eq(ind - 1).parent().parent().remove();
				ind = ind-1;
			}
			fila_medicamentos=$('#example tbody').children('tr');            
			for (i = 0; i < ind; i++) {
				fila_medicamento=fila_medicamentos[i];
				var tiempo_periodo = $(fila_medicamento).find('.tiempo_periodo').val();
				var posologia = $(fila_medicamento).find('.posologia').val();
				var valmedentre = $(fila_medicamento).find('.nro_med_solictados').val();
				if(tiempo_periodo == "")msg+="Uno de los productos no cuenta el tiempo(dias)<br>";
				if(posologia == "")msg+="Uno de los productos no cuenta con la posologia<br>";
				if(valmedentre == "")msg+="Uno de los productos no cuenta con la cantidad prescrita<br>";
			}
		}else{
			msg+="Debe ingresar minimo un producto medico a la Receta Vale<br>";
		}
	}
	if(addExamenLab == "true"){
		var id_examen_lab = $('input[name="id_examen_lab[]"]').map(function(){return $(this).val();}).get();
		var proce_lab = $('input[name="examen_lab_cantidad[]"]').map(function(){return $(this).val();}).get();
		if(id_examen_lab == "")msg+="Debe registrar un examen de laboratorio<br>";
		if(proce_lab == "")msg+="Debe registrar una cantidad en laboratorio<br>";
	}
	if(addExamenRad == "true"){
		var id_examen_rad = $('input[name="id_examen_rad[]"]').map(function(){return $(this).val();}).get();
		var proce_rad = $('input[name="examen_rad_cantidad[]"]').map(function(){return $(this).val();}).get();
		if(id_examen_rad == "")msg+="Debe registrar un examen de imagenologia<br>";
		if(proce_rad == "")msg+="Debe registrar una cantidad en imagenologia<br>";
	}
	if(addInterconsulta == "true"){
		var id_servicio_interconsulta = $('input[name="id_servicio_interconsulta[]"]').map(function(){return $(this).val();}).get();
    var motivo_interconsulta = $('input[name="motivo_interconsulta[]"]').map(function(){return $(this).val();}).get();
    //var resumen_interconsulta = $('input[name="resumen_interconsulta[]"]').map(function(){return $(this).val();}).get();
	  
	  if(id_servicio_interconsulta == "")msg+="Debe elegir un servicio para la interconsulta<br>";
		if(motivo_interconsulta == "")msg+="Debe registrar una motivo para la interconsulta<br>";
		//if(resumen_interconsulta == "")msg+="Debe registrar una resumen para la interconsulta<br>";
	}
  if(addReferencia == "true"){
    var id_establecimiento_referencia = $('#id_establecimiento_referencia').val();
    var id_uci = $('#id_uci').val();
    if(id_uci==1)
      var id_uci_cv= $('#id_uci_cv').val();
    var id_ambulancia = $('#id_ambulancia').val();
    if(id_ambulancia==1){
      var id_ambulancia_ref = $('#id_ambulancia_ref').val();
      var id_tipo_ambulancia = $('#id_tipo_ambulancia').val();
    }
    var observacion_referencia = $('#observacion_referencia').val();
    var id_pasaje = $('#id_pasaje').val();
    var id_acompanante = $('#id_acompanante').val();
    var id_situacion = $('#id_situacion').val(); 
    var observacion_referencia = $('#observacion_referencia').val();  
    if(id_establecimiento_referencia == "")msg+="Debe elegir un establecimiento<br>";
    if(id_uci == "")msg+="Debe especificar si quiere UCI<br>";
    if(id_uci==1)
      if(id_uci_cv == "")msg+="Debe especificar si quiere o no ventilador<br>";
    if(id_ambulancia == "")msg+="Debe especificar si quiere Ambulancia<br>";
    if(id_ambulancia==1){
      if(id_ambulancia_ref== "")msg+="Debe especificar si quiere de Ida o Vuelta<br>";
      if(id_tipo_ambulancia== "")msg+="Debe elegir el tipo de ambulancia<br>";
    }
    if(id_pasaje == "")msg+="Debe especificar si requiere pasaje<br>";
    if(id_acompanante == "")msg+="Debe especificar si quiere acompañante<br>";
    if(id_situacion == "")msg+="Debe elegir una situacion<br>";
    if(observacion_referencia == "")msg+="Debe escribir un relato o caracteristicas de la referencia<br>";
     
  }
    if(msg!=""){
        bootbox.alert(msg);
		$('#btnPrestacion').prop("disabled",false);
		$('.loader').hide();
        return false;
    }
    else{
		$("#frmPrestacion").submit();
    }
}
function fn_save_prestacion(){
    
    //var fecha_atencion_original = $('#fecha_atencion').val();
    $.ajax({
            url: "../registrar_prestacion",
            type: "POST",
			//data : $("#frmPrestacion").serialize()+"&id_medico="+id_medico+"&fecha_cita="+fecha_cita,
			data : $("#frmPrestacion").serialize(),
            success: function (result) {  
					//modalTurnos();
					//modalHistorial();
            }
    });
}
function obtenerBeneficiario(){
		
	var id_tipo_atencion = 1;
	var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
	var dni_beneficiario = $("#dni_beneficiario").val();
	var msg = "";
	
	//if(id_tipo_atencion == "")msg += "Debe seleccionar el tipo de receta <br>";
	//if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";
	
	if (msg != "") {
		bootbox.alert(msg);
		return false;
	}
	
	$.ajax({
		url: '../obtener_beneficiario/' + id_tipo_atencion + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
		dataType: "json",
		success: function(result){
			if(result.sw == false){
				bootbox.alert(result.msg);
				$('#beneficiario').val("");
				$('#tipo_beneficiario').val("");
				$('#nombre_beneficiario').val("");
				$('#paterno_beneficiario').val("");
				$('#materno_beneficiario').val("");
				$('#tipodoc_beneficiario').val("");
				$('#cip_beneficiario').val("");
				$('#grado').val("");
				return false;
			}
			
			var beneficiario = result.beneficiario.apepatafiliado+" "+result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado+", "+result.beneficiario.nomafiliado;
			$('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
			$('#beneficiario').val(beneficiario);
			$('#tipo_beneficiario').val(result.beneficiario.parentesco);
			$('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
			$('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
			$('#materno_beneficiario').val(result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado);
			$('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
			$('#cip_beneficiario').val(result.beneficiario.cip);
			$('#grado').val(result.beneficiario.grado);
			
			var parentesco 	= result.beneficiario.parentesco;
			var ubigeo 		= result.beneficiario.ubigeo;
			
			obtenerEstablecimiento(parentesco,ubigeo);
			modalHistorial();
			modalLaboratorio();
			modalRadiologico();
			getProducto();
			AddDiagnostico();
			AddProcedimiento();
			AddExamenLab();
			AddExamenRad();
			/*
			if(id_tipo_atencion == 3){	
				ShowByServicioAndId_farmacia();
			}
			*/
		},
		error: function(response) {
			location.reload();
		}
		
	});
	
}
function obtenerBeneficiario_dirrehum(){
		
	var id_tiporeceta = 1;
	var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
	var dni_beneficiario = $("#dni_beneficiario").val();
	var msg = "";
	
	if (msg != "") {
		bootbox.alert(msg);
		return false;
	}
	
	$.ajax({
		url: '/obtener_beneficiario_dirrehum/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
		dataType: "json",
		success: function(result){
			if(result.sw == false){
				bootbox.alert(result.msg);
				$('#beneficiario').val("");
				$('#tipo_beneficiario').val("");
				$('#nombre_beneficiario').val("");
				$('#paterno_beneficiario').val("");
				$('#materno_beneficiario').val("");
				$('#tipodoc_beneficiario').val("");
				$('#cip_beneficiario').val("");
				$('#grado').val("");
				$('#fecnacafiliado').val("");
				$('#nomsexo').val("");
				$('#nro_historia').val("");
				$('#cip_beneficiario').val("");
				return false;
			}
			
			var beneficiario = result.beneficiario.apepatafiliado+" "+result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado+", "+result.beneficiario.nomafiliado;
			$('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
			$('#beneficiario').val(beneficiario);
			$('#tipo_beneficiario').val(result.beneficiario.parentesco);
			$('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
			$('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
			$('#materno_beneficiario').val(result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado);
			$('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
			$('#cip_beneficiario').val(result.beneficiario.cip);
			$('#grado').val(result.beneficiario.grado);
			$('#fecnacafiliado').val(result.beneficiario.fecnacafiliado);
			$('#nomsexo').val(result.beneficiario.nomsexo);
			
			if(result.numero_historia!=null){
				if(result.numero_historia!='')
					$('#nro_historia').val(result.numero_historia);
				else
					$('#nro_historia').val(result.beneficiario.nrodocafiliado);	
			}
			else				
				$('#nro_historia').val(result.beneficiario.nrodocafiliado);
			
			$('#cip_beneficiario').val(result.beneficiario.cip);
			
			$('#tipodoc_titular').val(result.beneficiario.nomtipdoctitular);
			$('#nrodoctitular').val(result.beneficiario.nrodoctitular);
			//$('#tipo_beneficiario').val(result.beneficiario.parentesco);
			$('#nombre_titular').val(result.beneficiario.nomtitular);
			$('#paterno_titular').val(result.beneficiario.apepattitular);
			$('#materno_titular').val(result.beneficiario.apemattitular+" "+result.beneficiario.apecastitular);
			//$('#beneficiario').val(beneficiario);
			
			var parentesco 			= result.beneficiario.parentesco;
			var ubigeo 				= result.beneficiario.ubigeo;
			var establecimiento 	= result.establecimiento;
			var cita 				= result.cita;
			var id_establecimiento 	= 0;
			
			if(establecimiento[0]!=undefined){
				id_establecimiento = establecimiento[0].id_establecimiento;
				nombre_establecimiento = establecimiento[0].establecimiento;
				$('#nombre_establecimiento').html(nombre_establecimiento);
			}
			
			if(cita[0]!=undefined){
				if(cita[0].id_estado_cita == 1){
					//bootbox.alert("El beneficiario tiene una cita programada");
					//return false;
					//var id_servicio = cita[0].id_servicio;
					var id_servicio = 8;
					$("#parent1").prop("checked", true);
				}
				if(cita[0].id_estado_cita == 3){
					var id_servicio = cita[0].id_servicio;
					//$('#parent').val("2");
					//$('#nombre_parent').html("DERIVADO");
					//var parent = $('input[name="parent"]:checked').val();
					$("#parent2").prop("checked", true);
				}
			}else{
				var id_servicio = 8;
				$("#parent1").prop("checked", true);
			}
			
			//obtenerEstablecimiento(parentesco,ubigeo,76,id_servicio);
			modalHistorial();
			modalLaboratorio();
			modalRadiologico();
			getProducto();
			AddDiagnostico();
			AddProcedimiento();
			AddExamenLab();
			AddExamenRad();
			$("#divTurno").html("");
			getProducto();
			
		},
		error: function(response) {
			location.reload();
		}
	});
}
function obtenerEstablecimiento(parentesco,ubigeo){
	var newOption = "";
	newOption = "<option value=''>--Seleccionar--</option>";
	$('#id_establecimiento').html(newOption);
	
	$.ajax({
		url: '../obtener_establecimiento/'+parentesco+"/"+ubigeo,
		success: function(result){                    
			var newOption = "";
			newOption = "<option value=''>--Seleccionar--</option>";
			$(result).each(function (ii, oo) {
				newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
			});                 
			$('#id_establecimiento').html(newOption);
			$("#id_establecimiento").select2({max_selected_options: 4});
		
		}
	});
}
function limpiarBeneficiario(){
		
	var dni_beneficiario = $('#dni_beneficiario').val();
	var nrodocafiliado = $('#nrodocafiliado').val();
	if(dni_beneficiario!=nrodocafiliado){
		$('#beneficiario').val("");
		$('#tipo_beneficiario').val("");
		$('#nombre_beneficiario').val("");
		$('#paterno_beneficiario').val("");
		$('#materno_beneficiario').val("");
		$('#grado').val("");
		$('#nro_historia').val("");
		$('#tipodoc_beneficiario').val("");
		$('#cip_beneficiario').val("");
		$('#id_admision').val("");
		$('#grado').val("");
	}
	
	if(dni_beneficiario!="")obtenerBeneficiario();
	//if(dni_beneficiario!="")obtenerBeneficiario_dirrehum();
}
function getServicio(){
	var newOption = "";
	newOption = "<option value=''>--Seleccionar--</option>";
	$('#id_servicio').html(newOption);
	//$("#id_servicio").val('').trigger("change");
	var parent = $('input[name="parent"]:checked').val();
	//var parent	= $('#parent').val();
	
	$.ajax({
		url: '../obtener_consultorio_parent/'+parent,
		success: function(result){                    
			var newOption = "";
			newOption = "<option value=''>--Seleccionar--</option>";
			$(result).each(function (ii, oo) {
				newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
			});                 
			$('#id_servicio').html(newOption);
			$("#id_servicio").select2({max_selected_options: 4});
		
		}
	});
	
}
function fullCalendar(){
	//var SITEURL = "{{url('/')}}";
	var iIdMovimientoCompra = $('#hdIdMovimientoCompra').val();
	var iIdTipoCompra		= $('#hdIdTipoCompra').val();
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();
	$("#divHorario").html("");
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			/*right: 'month,agendaWeek,agendaDay'*/
		},
		editable: false,
		//events: "submit/cronogramaCompraData.php?iIdMovimientoCompra="+iIdMovimientoCompra,
		//events: "cronograma_cita",
		events: "cronograma_cita/"+id_ipress+"/"+id_servicio,
		//data: {id_ipress:id_ipress,id_servicio:id_servicio},
		selectable: true,
		selectHelper: true,
		select: function(start, end, allDay) {
			dateObj = new Date(start); /* Or empty, for today */
			dateIntNTZ = dateObj.getTime() + dateObj.getTimezoneOffset() * 60 * 1000;
			dateObjNTZ = new Date(dateIntNTZ);
			start = dateObjNTZ.toISOString().slice(0, 10);
			start = $.trim(start);
			var sValidaciones = "";
				modalDelegar(start)
	
		}
	});	
		
}
function fn_RegistrarNuevoCronograma(start,iIdMovimientoCompra,iIdTipoCompra) {
	/*
    var oBotones = {
		"Pagar": function () {
            var btnGrabar = $("#ifrModal").contents().find("input[id='btnGrabar']");
            btnGrabar.click();
        },
        "Cerrar": function () {
            $(this).dialog("close");
            $(this).remove();
        }
    };
    var sRuta = "frmCronogramaCompraDetalle.php?param=" + iIdMovimientoCompra + "&param1=" + iIdTipoCompra + "&param2=" + start;
    fn_util_AbreModalNew("Agendar Pago", sRuta, '40', 150, null, oBotones);
	*/
	$('#showAtencionesMensual').modal();
}
function modalDelegar___(start,iIdMovimientoCompra,iIdTipoCompra){
	
	$(".modal-dialog").css("width","80%");
	$('#openOverlayOpc').modal('show');
	//$('#openOverlayOpc').modal();
	$('#openOverlayOpc .modal-body').css('height', 'auto');
	//alert(start);
	$.ajax({
			url: "../modal_cita_medico/"+start,
			type: "GET",
			success: function (result) {  
					$("#diveditpregOpc").html(result);
			}
	});
}
function modalDelegar(start){
	
	//$(".modal-dialog").css("width","80%");
	//$('#openOverlayOpc').modal('show');
	//$('#openOverlayOpc .modal-body').css('height', 'auto');
	
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();;
			
	$.ajax({
			url: "../modal_cita_medico/"+id_ipress+"/"+id_servicio+"/"+start,
			type: "GET",
			success: function (result) {  
					$("#divHorario").html(result);
			}
	});
}
function modalTurnos(){
	
	//$(".modal-dialog").css("width","80%");
	//$('#openOverlayOpc').modal('show');
	//$('#openOverlayOpc .modal-body').css('height', 'auto');
	
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();;
	//var start = '2019-10-24';
	var start = "<?php echo $fechaServidor?>";
	//alert(start);
	$.ajax({
			url: "../modal_turnos/"+id_ipress+"/"+id_servicio+"/"+start,
			type: "GET",
			success: function (result) {  
					$("#divTurno").html(result);
			}
	});
}
function modalHistorial(){
	/*
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();;
	var start = '2019-10-24';
	*/
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "../modal_historial_atenciones/"+dni_beneficiario,
			type: "GET",
			success: function (result) {  
					$("#divHistorial").html(result);
					$('#tablemodalm').DataTable({
						"sPaginationType": "full_numbers",
						
						"language": {"url": "/js/Spanish.json"},
					});
			}
	});
}
function modalAntecedentes(){
	
	var dni_beneficiario = $("#dni_beneficiario").val();
	var id_tipo_atencion = $("#id_tipo_atencion").val();
	var id_cita = $("#id_cita").val();
	
	$.ajax({
			url: "../paciente_antecedentes/"+dni_beneficiario+'/'+id_tipo_atencion+'/'+id_cita,
			type: "GET",
			success: function (result) {  
					$("#divAntecedentes").html(result);
			}
	});
}
function modalLaboratorio(){
	
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "../modal_historial_laboratorio/"+dni_beneficiario,
			type: "GET",
			success: function (result) {  
					$("#divLaboratorio").html(result);
					$('#tablemodalm1').DataTable({
						"sPaginationType": "full_numbers",
						
						"language": {"url": "/js/Spanish.json"},
					});
			}
	});
}
function modalRadiologico(){
	
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "../modal_historial_radiologia/"+dni_beneficiario,
			type: "GET",
			success: function (result) {  
					$("#divRadiologico").html(result);
					$('#tablemodalm2').DataTable({
						"sPaginationType": "full_numbers",
						
						"language": {"url": "/js/Spanish.json"},
					});
			}
	});
}
function AddDiagnostico(){
	var newRow = "";
	var ind = $('#tblDiagnostico tbody tr').length;
	
	if(ind > 0){
		$('.id_diagnostico').each(function(){
			var ind_tmp = $(this).data("id");
			if(ind_tmp => ind){
				ind=Number(ind_tmp)+1;
			}
		});
					
		fila_medicamentos=$('#tblDiagnostico tbody').children('tr');
		rowIndex=$('#hidden_rowIndex').val();
		var idval = ind - 1;
		fila_medicamento=fila_medicamentos[idval];
		
	}
	
	var item_tipo_diagnostico = '';
	item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
	item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
	item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
	item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';
	newRow +='<tr>';
	newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="44" required="" id="nombre_diagnostico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
	newRow +='<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico'+ind+'" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
	newRow +='<td><select tabindex="45" class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico'+ind+'" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">'+item_tipo_diagnostico+'</select></td>';
	newRow +='<td><button type="button" tabindex="46" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
	newRow +='</tr>';
	$('#tblDiagnostico tbody').append(newRow);
	var sexo	= $('#nomsexo').val();
	if(sexo=='M'){
		genero=1;
	}
	else{
		if(sexo=='F'){
			genero=2;
		}
	}
	
	$("#nombre_diagnostico"+ind).autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
			$.ajax({
				//url: '../obtener_diagnostico/' + request.term,
				url: '../obtener_diagnostico_genero/' + request.term + '/'+ genero,
				dataType: "json",
				success: function(data)  {
					let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
          response(filtro);
				}					
			})
	
		},select:  function(e, ui) {
			var id_diagnostico = ui.item.id;
			$("#id_diagnostico"+ind).val(id_diagnostico);
			$("#nombre_diagnostico"+ind).prop("readonly",true);
		}
	});
	
	$("#nombre_diagnostico"+ind).on('blur', function() {
		var id_diagnostico = $("#id_diagnostico"+ind).val();
		if(id_diagnostico == ""){
			bootbox.alert("Falta ingresar el diagnostico en esta fila");
			$("#nombre_diagnostico"+ind).val("");
			return false;
		}
	});
	
}
function AddProcedimiento(){
	var newRow = "";
	var ind = $('#tblProcedimiento tbody tr').length;
	
	if(ind > 0){
		$('.id_procedimiento').each(function(){
			var ind_tmp = $(this).data("id");
			if(ind_tmp => ind){
				ind=Number(ind_tmp)+1;
			}
		});
					
		fila_medicamentos=$('#tblProcedimiento tbody').children('tr');
		rowIndex=$('#hidden_rowIndex').val();
		var idval = ind - 1;
		fila_medicamento=fila_medicamentos[idval];
		
	}
	
	newRow +='<tr>';
	newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="51" required="" id="nombre_procedimiento'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
	newRow +='<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento'+ind+'" name="id_procedimiento[]" value="" data-id='+ind+'></td>';
	newRow +='<td><input tabindex="52" type="text" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad'+ind+'" name="procedimiento_cantidad[]" value="1"></td>';
	newRow +='<td><button tabindex="53" type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
	newRow +='</tr>';
	$('#tblProcedimiento tbody').append(newRow);
	var nivel     = $('#id_nivel').val();
	var sexo	= $('#nomsexo').val();
	if(sexo=='M')
		genero=1;
	else
		genero=2;
	
	//tipo = 1
	
	$("#nombre_procedimiento"+ind).autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
			$.ajax({
//				url: '../obtener_procedimiento/' + request.term,
				url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
				dataType: "json",
				success: function(data)  {
					let listaProcedimientoSelecionado = $(".id_procedimiento").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
          response(filtro);
				}					
			})
	
		},select:  function(e, ui) {
			var id_procedimiento = ui.item.id;
			$("#id_procedimiento"+ind).val(id_procedimiento);
			$("#nombre_procedimiento"+ind).prop("readonly",true);
		}
	});
	
	$("#nombre_procedimiento"+ind).on('blur', function() {
		var id_procedimiento = $("#id_procedimiento"+ind).val();
		if(id_procedimiento == ""){
			bootbox.alert("Falta ingresar el procedimiento en esta fila");
			$("#nombre_procedimiento"+ind).val("");
			return false;
		}
	});
	$("#procedimiento_cantidad"+ind).on('blur', function() {
		var id_procedimiento = $("#id_procedimiento"+ind).val();
		if(id_procedimiento == ""){
			bootbox.alert("Falta ingresar la cantidad en este procedimiento");
			$("#procedimiento_cantidad"+ind).val("");
			return false;
		}
	});
	
	
} 
function  AddInterconsulta2(){
	var newRow = "";
	var ind = $('#tblInterconsulta tbody tr').length;
	
	if(ind > 0){
		$('.id_servicio_interconsulta').each(function(){
			var ind_tmp = $(this).data("id");
			if(ind_tmp => ind){
				ind=Number(ind_tmp)+1;
			}
		});
					
		fila_medicamentos=$('#tblInterconsulta tbody').children('tr');
		rowIndex=$('#hidden_rowIndex').val();
		var idval = ind - 1;
		fila_medicamento=fila_medicamentos[idval];
		
	}
	
	newRow +='<tr>';
	newRow +='<td><div class="panel panel-info panel-bordered panel-sm"><div class="form-group"> <div class="panel-body"> <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>SERVICIO</label></div> <div class="col-md-4" style="margin-left:0px;padding-left:0px"> <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el servicio" name="nombre_servicio_interconsulta[]" tabindex="112" required="" id="nombre_servicio_interconsulta'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" /> </div> <div class="col-md-1"> <label>Motivo</label> </div> <div class="col-md-6"> <input type="text" class="form-control" value="" name="motivo_interconsulta[]" id="motivo_interconsulta'+ind+'" tabindex="113" data-date-end-date="0d" autocomplete="off" /> </div> <div class="col-md-12"><br/> <label>RESUMEN</label> <textarea class="form-control" cols="5" id="resumen_interconsulta'+ind+'" name="resumen_interconsulta[]" placeholder="Relato m&eacute;dico o caracteristicas de la interconsulta"></textarea></div></div></div></div></td>';
	newRow +='<td><button tabindex="114" type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
	newRow +='<input type="hidden" class="form-control typeahead tt-query id_servicio_interconsulta" id="id_servicio_interconsulta'+ind+'" name="id_servicio_interconsulta[]" value="" data-id='+ind+'></td>';
	newRow +='</tr>';
	
	$('#tblInterconsulta tbody').append(newRow);
	$("#nombre_servicio_interconsulta"+ind).autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
			$.ajax({
//				url: '../obtener_procedimiento/' + request.term,
				url: '../obtener_consultorio_interconsulta/' + request.term,
				dataType: "json",
				success: function(data)  {
					let listaServicioSelecionado = $(".id_servicio_interconsulta").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaServicioSelecionado.includes(element.id));
          response(filtro);
				}					
			})
	
		},select:  function(e, ui) {
			var id_servicio_interconsulta = ui.item.id;
			$("#id_servicio_interconsulta"+ind).val(id_servicio_interconsulta);
			$("#nombre_servicio_interconsulta"+ind).prop("readonly",true);
		}
	});
	$("#nombre_servicio_interconsulta"+ind).on('blur', function() {
		var id_servicio_interconsulta = $("#id_servicio_interconsulta"+ind).val();
		if(id_servicio_interconsulta == ""){
			bootbox.alert("Falta ingresar el servicio en esta interconsulta");
			$("#nombre_servicio_interconsulta"+ind).val("");
			return false;
		}
	});	
}
function AddExamenLab() {
  var newRow = "";
  var ind = $('#tblExamenLab tbody tr').length;
	
  if (ind > 0) {
    $('.id_laboratorio').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });
    fila_medicamentos = $('#tblExamenLab tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];
		
  }
	
  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id='+ind+'></td>';
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_lab_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1"></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblExamenLab tbody').append(newRow);
	
  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  if (sexo == 'M')
    genero = 1;
  else
    genero = 2;
  //tipo=2
  $("#nombre_examen_lab" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '../obtener_laboratorio_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion,
        //url: '../obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaLaboratorioSelecionado = $(".id_laboratorio").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaLaboratorioSelecionado.includes(element.id));
          response(filtro);
        }
      })
	
    },
    select: function(e, ui) {
      var id_examen_lab = ui.item.id;
      $("#id_examen_lab" + ind).val(id_examen_lab);
      $("#nombre_examen_lab" + ind).prop("readonly", true);
    }
  });
	
  $("#nombre_examen_lab" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar el examen laboratorio en esta fila");
      $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });
  $("#examen_lab_cantidad" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen laboratorio");
      $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });
} 
function AddExamenRad() {
  var newRow = "";
  var ind = $('#tblExamenRad tbody tr').length;
	
  if (ind > 0) {
    $('.id_radiologia').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });
    fila_medicamentos = $('#tblExamenRad tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];
		
  }
  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de Radiografia" name="nombre_examen_rad[]" tabindex="8" required="" id="nombre_examen_rad' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_radiologia" id="id_examen_rad' + ind +'" name="id_examen_rad[]" value="" data-id='+ind+'></td>';
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_rad_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="examen_rad_cantidad' +ind + '" name="examen_rad_cantidad[]" value="1"></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblExamenRad tbody').append(newRow);
  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  if (sexo == 'M')
    genero = 1;
  else
    genero = 2;
  //tipo=3
	
  $("#nombre_examen_rad" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 3,
        //url: '../obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaRadiologiaSelecionado = $(".id_radiologia").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaRadiologiaSelecionado.includes(element.id));
          response(filtro);
        }
      })
	
    },
    select: function(e, ui) {
      var id_examen_rad = ui.item.id;
      $("#id_examen_rad" + ind).val(id_examen_rad);
      $("#nombre_examen_rad" + ind).prop("readonly", true);
    }
  });
	
  $("#nombre_examen_rad" + ind).on('blur', function() {
    var id_examen_rad = $("#id_examen_rad" + ind).val();
    if (id_examen_rad == "") {
      bootbox.alert("Falta ingresar el examen radiografia en esta fila");
      $("#examen_rad_cantidad" + ind).val("");
      return false;
    }
  });
  $("#examen_rad_cantidad" + ind).on('blur', function() {
    var id_examen_rad = $("#id_examen_rad" + ind).val();
    if (id_examen_rad == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen radiografia");
      $("#examen_rad_cantidad" + ind).val("");
      return false;
    }
  });
}
function AddProducto(){
	var tipo_receta = $('#tipo_receta').val();
	var id_consultorio = $('#id_consultorio').val();
	if(tipo_receta == ""){
		bootbox.alert("Seleccione el tipo atención");
		return false;
	}		
	if(id_consultorio == ""){
		bootbox.alert("Seleccione un servicio");
		return false;
	}
	var newRow = "";
	var ind = $('#example tbody tr').length;
	var tabindex = 71;
		var nuevalperiodo = "";
	if(ind > 0){
	$('.cmbMedicamento').each(function(){
		var ind_tmp = $(this).attr("ind");
		if(ind_tmp => ind){
							ind=Number(ind_tmp)+1;
							tabindex+=3;
		}
	});
			
			fila_medicamentos=$('#example tbody').children('tr');
			rowIndex=$('#hidden_rowIndex').val();
			var idval = ind - 1;
		<?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>    
			fila_medicamento=fila_medicamentos[idval];
			var valante = $(fila_medicamento).find('#nro_med_entregados').val();
			if(valante == ""){
				bootbox.alert("Ingrese cantidad entregada en el producto anterior");
				return false;
			}
		<?php //endif;?>
			
		nuevalperiodo = document.getElementById('tiempo_periodo'+idval).value;
	}
	
	var item_producto 	= "";
	$('#cmbMedicamentoTemp option').each(function(){
	item_producto += "<option value="+$(this).val()+" ru='"+$(this).attr("ru")+"'>"+$(this).html()+"</option>"	
	});
	
	newRow +='<tr>';
	newRow +='<td><select class="form-control cmbMedicamento" id="cmbMedicamento'+ind+'" ind="'+ind+'" tabindex="'+tabindex+'" name="id_producto[]" style="width: 500px">'+item_producto+'</select></td>';
	
	newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
	newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
	
	newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
	<?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
	newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="'+(tabindex+1)+'" required="" id="tiempo_periodo'+ind+'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="'+nuevalperiodo+'"/>';
	
	newRow +='<td><input type="text" name="posologia[]" tabindex="'+(tabindex+1)+'" required="" id="posologia'+ind+'" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';
	
	<?php //endif;?>
	newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
	newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
	newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
		newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
	newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
	newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="'+(tabindex+2)+'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados'+ind+'" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';
	
	<?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
	//newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
	<?php //endif;?>
	
	newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';
  newRow +='<td><button type="button" class="btn btn-primary btn-xs" id="add_producto'+ind+'"><i class="fa fa-plus"></i> Agregar</button></td>';
	newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
	newRow +='</tr>';
	$('#example tbody').append(newRow);
	$("#cmbMedicamento"+ind).select2({max_selected_options: 4}).focus();
	
	$("#nro_med_solictados"+ind).keypress(function(e){
	if(e.which == 13) {
		<?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
			AddProducto();
		<?php //else:?>
			validproductoseleccionado({{Auth::user()->id_farmacia}},this);
		<?php //endif;?>
	}
	});

  $("#add_producto"+ind).click(function(e){
      AddProducto();
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      btnAgregarProducto = $(this);
      $(btnAgregarProducto).attr('disabled', 'disabled');
	});
	function validproductoseleccionado(id_farmacia, btn) {//Jose
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_dias = 30;
    var objTr = $(btn).closest("tr");
    var id_producto = objTr.find(".cmbMedicamento").val();
    $.ajax({
      url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
      success: function (result) {
      var msg   = "";
      var n     = 0;
      var flag  = true;
      for(var key in result) {
        if(n == 0){
          msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";              
          msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                      msg += "<thead>";
                        msg += "<tr>";
          msg += "<th>Farmacia</th>";
          msg += "<th>Nro. Receta</th>";
          msg += "<th>Fecha Expedicion</th>";
          msg += "<th>Producto</th>";
          msg += "<th>Tiempo(Dias)</th>";
          msg += "<th>Dias Transcurridos</th>";
          msg += "<th>Dias Pendientes</th>";
          msg += "<th>Cantidad Prescrita</th>";
                        msg += "</tr>";
                      msg += "</thead>";
          msg += "<tbody>";
        }
            
        msg += "<tr><td>"+(result[key].farmacia)+"</td>";
        msg += "<td>"+(result[key].nro_receta)+"</td>";
        msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
        msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
        msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
        msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
        msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
        msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
        n++;
                                            }
        if(msg != "")/*{
            verlotes(id_farmacia, btn);      
        } 
        else */
        {
          bootbox.confirm({
              title: "Alerta",
              message: msg,
              buttons: {
                  cancel: {
                      label: '<i class="fa fa-times"></i> Cancelar'
                  },
                  confirm: {
                      label: '<i class="fa fa-check"></i> Continuar'
                  }
              },
              callback: function (result) {
                if(result==true){
                  verlotes(id_farmacia, btn);
                }
              }
          });
        }
      }
    });
  }
	
	
	$("#cmbMedicamento"+ind).on("change", function (e) {
	var flagx = 0;
	cmb = $(this);
	id_producto = $("#cmbMedicamento"+ind).val();
	id_user={{Auth::user()->id}};
	
	$('.cmbMedicamento').each(function(){
		var ind_tmp = $(this).val();
		if($(this).val() == id_producto)flagx++;
	});
	
	if(flagx > 1){
		bootbox.alert("El producto farmaceutico ya ha sido ingresado");
		$("#cmbMedicamento"+ind).val("").trigger("change");
						return false;
	}
	
	option = {
		url: '../cargarstock_farmacia/' + id_producto+ '/' + id_user,
		type: 'GET',
		dataType: 'json',
		data: {}
	};
		$.ajax(option).done(function (data) {
			
			var cantidad = data.cantidad;
			var cantidadEstablecimiento = data.cantidadEstablecimiento;
			var cantidadAlmacen = data.cantidadAlmacen;
			$(cmb).closest("tr").find(".limpia_text").val("");                
			//$(cmb).closest("tr").find("#nro_stocks").val(data);
			$(cmb).closest("tr").find("#nro_stocks").val(cantidad);
			$(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
			$(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
			<?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
			$(cmb).closest("tr").find(".tiempo_periodo").focus();
			<?php //endif;?>
			$(cmb).closest("tr").find("#nro_med_solictados").val("");  
			$(cmb).closest("tr").find("#nro_med_entregados").val("");
			$(cmb).closest("tr").find("#lotes_lote").val("");
			$(cmb).closest("tr").find("#lotes_cantidad").val("");
			$(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
			$(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
		});
	
	});
	
}
function soloNumerosMenosCero(e){
	var key = window.Event ? e.which : e.keyCode;
	//alert(key);
	return ((key >= 48 && key <= 57) || key == 0 || key == 8)       
}
function nro_interconsulta(){
	var dni_beneficiario = $('#dni_beneficiario').val();
	var id_farmacia = $('#id_farmacia').val();
	var x = 0;
	if(dni_beneficiario!=""){
        $.ajax({
                url: '/obtenerCantInterconsultas/' + dni_beneficiario+ '/' + id_farmacia,
                success: function (result) {
					if(result != null && result != ""){
		                for(var key in result) {
		                  x++;
		                }
					} 
                }
               
        });
        
        return x;
    }
}
function getModalidad(){
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  
  if (id_tipo_atencion == 1) {
    $("#divModalidad").show();
  } else {
    $("#divSubCons").hide();
    $("#divModalidad").hide();
  }
}
function getConsultorio(){
	
	var id_tipo_atencion 		= $('#id_tipo_atencion').val();
	var nivel     = $('#id_nivel').val();
	var valor;
  var dni_beneficiario = $("#dni_beneficiario").val();
  if(nivel==3){
		switch(id_tipo_atencion){
			case '1': valor=3;  /*$("#divSubCons").show();*/break; //ambulatorio
			case '2': valor=19; break; //emergencia
			case '3': valor=20; break; //ho
		}
		//$("#id_farmacia_receta option[value='"+ valor +"']").attr("selected",false);
		//$("#id_farmacia_receta option[value='"+ valor +"']").attr("selected",true);
		$("#id_farmacia_receta").val(valor);
		$("#id_farmacia_receta").select2();
	}
	else{
		valor={{Auth::user()->id_farmacia}};
		//$("#id_farmacia_receta option[value='"+ valor +"']").attr("selected",false);
		//$("#id_farmacia_receta option[value='"+ valor +"']").attr("selected",true);
		$("#id_farmacia_receta").val(valor);
		$("#id_farmacia_receta").select2();
		
	}
  $.ajax({
    url: '../obtener_antecedentes_by_id_beneficiario/'+ dni_beneficiario,
    dataType: "json",
    success: function(resultado){
      $("#antecedente").val(resultado.antecedente);
      $("#antecedente_familiar").val(resultado.antecedente_familiar);
    }
  });
	
	//getProducto();
	/*
	if(id_tipo_atencion == 1){
		$("#divSubCons").show();
	}
	else{
		$("#divSubCons").hide();
	}
	*/
}
function llamarTv() {
  var id_cita = document.getElementById("id_cita").value;
  var cant_llamado_tv = document.getElementById("cant_llamado_tv").value;
  $.ajax({
    url: "/tv_llamar/" + id_cita,
    async: false,
    type: "GET",
    // dataType: "JSON",
    success: function(result) {
      
      if (result > 0) {
        // cant_llamado_tv += result + 1;
        document.getElementById("cant_llamado_tv").textContent = result;
        // document.getElementById("cant_llamado_tv").value = result;
        // console.log(result + " cant:" + cant_llamado_tv);
      } else {
        bootbox.alert("Actualmente se está llamando a otro paciente de este consultorio.");
      }
    },
    error: function(xhr, status) {
      console.log("error");
    },
    complete : function(xhr, status) {
      return false;
    }
  });
}
function posponerLlamadoTv() {
  var id_cita = document.getElementById("id_cita").value;
  $.ajax({
    url: "/tv_posponer_llamado/" + id_cita,
    async: false,
    type: "GET",
    success: function(result) {
      if (result=="2") {
        document.getElementById("cant_llamado_tv").textContent = "0";
        // console.log = result;
      } else {
        console.log = "error";
      }
    },
    complete : function(xhr, status) {
      return false;
    }
  });
}

function getProductoAndAddProducto() {
  getProducto();
  AddProducto();
}

getConsultorio();
</script>
<style>
  .modal-dialog {
    width: 1200px !important;
  }
  .suggest-element {
    margin-left: 5px;
    margin-top: 5px;
    width: 350px;
    cursor: pointer;
  }
  #suggestions {
    width: 350px;
    height: 150px;
    overflow: auto;
  }
  .alert {
    padding: 10px;
    background-position: 2% 7px;
    background-repeat: no-repeat;
    background-size: auto 35px;
    /*background-color: rgba(0, 0, 0, 0);*/
    border: 0;
    min-width: auto !important;
    text-align: left;
    padding-left: 68px;
    font-size: 14px;
    margin-bottom: 10px;
  }
  .alert.alert-danger {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAARVBMVEX////7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0NAQECdhUHYsUKGdEG1l0JLSEBuYkHkukPKU8NPAAAADnRSTlMA4EDAoCAQ8IBgkDDQUFItelQAAAC0SURBVHherZBLDsMwCAVD7Nj5gvPr/Y/a0FpGLTibdnbozQJN829agPZu7/CiuxE8C76+T/hiqgruLbjqh5ip/TljZrb3AQuDKYAIYO09L3TBRq/3EHk4shCDEkZkTqITmdGMLIIO7r8FbzfaiDappSNjIkpZcCqyCDo4WAJYkR8prTp4ADSBUCIX9uPY5epL5AJdyBWDRDYFHCWyCiXBObKwrh+nl4g2TiLVhSXW97g0v/MEHIQbCYeFmYAAAAAASUVORK5CYII=);
    border-top: 1px solid rgba(140, 0, 0, 0.4);
    border-bottom: 1px solid rgba(140, 0, 0, 0.4);
  }
  .alert.alert-info {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAADBklEQVR42u3YyWsTURwHcP8aQUWtWlu7JEbtpngQvIqXevDWm1fXehO12BTErdQiVMFQ8ODBusWkmSa1lVrTxdrG0FA9WJtkljfL19/LTDKMibl0EorkwReSCQ8+fN+bl2G2AdhSqYH+P1CpAS0DfXUI+ufzUCfaoQptYMIxsMhRsE/d0L4/BNR0qcnug4y159CmTkOLdkKNdnBQAaNEjkAZ90EOH4YcOQ51dQQVBRnLN6DHuqDFOqGZGEpbaVDICynkAVvoRUVAxuqQE8PjXC7C/AX64KG0gq3ch6sgQ1zkEBtTph2FY8JeApkYKdgCMdgMPTMP10D64kUOsTDtDsw/27FAIge9b4Y8e8EdELS0jTAh5TF2Ow5Q9l0TDDWNTYOMjSkLQREcEAdGCRfvHdFcLgI1EegQtPXY5kFactAE2JDyGHvvONrhILZ81w3QIxNQCEfw2JvYxpRYKrMdSqNLoNSzPMBGTJwEmz4HuSSGMnkWYrgjv1Rm3jZATQ5vHqT//sgRVhtmI8p0NwADbOF6EUaOX4GiZCHGzhAkD2ogUD20XxNw5baXhRN2G2EvR+QwhqFDme91YJgiQfpyCTamkTAHaQm97p1DauIBR1jxWI14CHMNuqZCmbsKmcKYBLkYQzkA9m3APRA/P6TxrvztbCfYkoOoqgKVydTQ5ZIYMdgKQ91wD8SHvj7JAYXb2T5jmqEkhiEnHnNIESb7po72joCK/Ntray8sRD7UBkVeGoBEIQilwYFRUwFU9HmIrdzjCHtpKNJXP48FqSfIfspe2jf+Sj4P2UOMnOKQfCOE6Ye0eMdqZR+yr/fwc8icXA2QmnzCD7pCI+JiH8SFPt4KYXYjO7YTLDFYPZCensu3QakjzC2I8zcJsguZsR3IvNoOfWO2eiA+tJ9jfI9Q+qEkn1JGoCz1UW5D+/HSnFhNUCqVQjwezyUajSIUChW+02/VB42OjqJMaqCtB5qZmUEgEIDf73eEXxMEofogPtLpNHp6euDz+Xhyn+lauUm1tx81UMXzBw5wT9JYWi53AAAAAElFTkSuQmCC);
    border-top: 1px solid rgba(255, 165, 0, 0.4);
    border-bottom: 1px solid rgba(255, 165, 0, 0.4);
    color: rgb(225, 75, 0);
  }
  .alert.alert-success {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
    border-top: 1px solid limegreen;
    border-bottom: 1px solid limegreen;
  }
  .form-control {
    height: 28px;
    text-align: left !important;
  }
  .form-group {
    margin-bottom: 10px;
  }
  #divHorariox {
    /*height: 200px!important;;*/
    /*background-color: #fed9ff; */
    /*width: 600px; */
    width: 100%;
    height: 550px;
    overflow-x: hidden;
    overflow-y: auto;
    text-align: center;
    padding: 0px !important;
  }
  /********************/
  body {
    font-family: 'Montserrat', 'Lato', 'Open Sans', 'Helvetica Neue', Helvetica, Calibri, Arial, sans-serif;
    color: #6b7381;
    background: #f2f2f2;
  }
  .jumbotron {
    background: #6b7381;
    color: #bdc1c8;
  }
  .jumbotron h1 {
    color: #fff;
  }
  .example {
    margin: 4rem auto;
  }
  .example>.row {
    margin-top: 2rem;
    height: 5rem;
    vertical-align: middle;
    text-align: center;
    border: 1px solid rgba(189, 193, 200, 0.5);
  }
  .example>.row:first-of-type {
    border: none;
    height: auto;
    text-align: left;
  }
  .example h3 {
    font-weight: 400;
  }
  .example h3>small {
    font-weight: 200;
    font-size: 0.75em;
    color: #939aa5;
  }
  .example h6 {
    font-weight: 700;
    font-size: 0.65rem;
    letter-spacing: 3.32px;
    text-transform: uppercase;
    color: #bdc1c8;
    margin: 0;
    line-height: 5rem;
  }
  .example .btn-toggle {
    top: 50%;
    transform: translateY(-50%);
  }
  .btn-toggle {
    margin: 0 4rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
    color: #6b7381;
    background: #bdc1c8;
  }
  .btn-toggle:focus,
  .btn-toggle.focus,
  .btn-toggle:focus.active,
  .btn-toggle.focus.active {
    outline: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
    line-height: 1.5rem;
    width: 4rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle:before {
    content: 'NO';
    left: -4rem;
  }
  .btn-toggle:after {
    content: 'SI';
    right: -4rem;
    opacity: 0.5;
  }
  .btn-toggle>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }
  .btn-toggle.active:before {
    opacity: 0.5;
  }
  .btn-toggle.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }
  .btn-toggle.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    display: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
    color: #6b7381;
  }
  .btn-toggle.active {
    background-color: #29b5a8;
  }
  .btn-toggle.btn-lg {
    margin: 0 5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 2.5rem;
    width: 5rem;
    border-radius: 2.5rem;
  }
  .btn-toggle.btn-lg:focus,
  .btn-toggle.btn-lg.focus,
  .btn-toggle.btn-lg:focus.active,
  .btn-toggle.btn-lg.focus.active {
    outline: none;
  }
  .btn-toggle.btn-lg:before,
  .btn-toggle.btn-lg:after {
    line-height: 2.5rem;
    width: 5rem;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-lg:before {
    content: 'NO';
    left: -5rem;
  }
  .btn-toggle.btn-lg:after {
    content: 'SI';
    right: -5rem;
    opacity: 0.5;
  }
  .btn-toggle.btn-lg>.handle {
    position: absolute;
    top: 0.3125rem;
    left: 0.3125rem;
    width: 1.875rem;
    height: 1.875rem;
    border-radius: 1.875rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-lg.active>.handle {
    left: 2.8125rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-lg.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-lg.btn-sm:before,
  .btn-toggle.btn-lg.btn-sm:after {
    line-height: 0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.6875rem;
    width: 3.875rem;
  }
  .btn-toggle.btn-lg.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-lg.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-lg.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-lg.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-lg.btn-xs:before,
  .btn-toggle.btn-lg.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-sm {
    margin: 0 0.5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
  }
  .btn-toggle.btn-sm:focus,
  .btn-toggle.btn-sm.focus,
  .btn-toggle.btn-sm:focus.active,
  .btn-toggle.btn-sm.focus.active {
    outline: none;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: 1.5rem;
    width: 0.5rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-sm:before {
    content: 'NO';
    left: -0.5rem;
  }
  .btn-toggle.btn-sm:after {
    content: 'SI';
    right: -0.5rem;
    opacity: 0.5;
  }
  .btn-toggle.btn-sm>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-sm.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-sm.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-sm.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm.btn-sm:before,
  .btn-toggle.btn-sm.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }
  .btn-toggle.btn-sm.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-sm.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-sm.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-sm.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm.btn-xs:before,
  .btn-toggle.btn-sm.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-xs {
    margin: 0 0;
    padding: 0;
    position: relative;
    border: none;
    height: 1rem;
    width: 2rem;
    border-radius: 1rem;
  }
  .btn-toggle.btn-xs:focus,
  .btn-toggle.btn-xs.focus,
  .btn-toggle.btn-xs:focus.active,
  .btn-toggle.btn-xs.focus.active {
    outline: none;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    line-height: 1rem;
    width: 0;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-xs:before {
    content: 'NO';
    left: 0;
  }
  .btn-toggle.btn-xs:after {
    content: 'SI';
    right: 0;
    opacity: 0.5;
  }
  .btn-toggle.btn-xs>.handle {
    position: absolute;
    top: 0.125rem;
    left: 0.125rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 0.75rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-xs.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-xs.active>.handle {
    left: 1.125rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-xs.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-xs.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs.btn-sm:before,
  .btn-toggle.btn-xs.btn-sm:after {
    line-height: -1rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.275rem;
    width: 1.55rem;
  }
  .btn-toggle.btn-xs.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-xs.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-xs.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-xs.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs.btn-xs:before,
  .btn-toggle.btn-xs.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-secondary {
    color: #6b7381;
    background: #bdc1c8;
  }
  .btn-toggle.btn-secondary:before,
  .btn-toggle.btn-secondary:after {
    color: #6b7381;
  }
  .btn-toggle.btn-secondary.active {
    background-color: #ff8300;
  }
</style>
@endsection
@section('content')

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>
<div class="loader"></div>
<div class="container" style="width:90%">
  @include('frontend.atenciones.modal_atencion_escala')
  @include('frontend.atenciones.ficha_psicologia')

  <div class="row">
    <div class="col-md-12">
      <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
        	<?php 
            $disabled_atencion = "";
            if($flag==1){
              $disabled_atencion = "disabled='disabled'";
              echo '<div class="col-sm-12">
                  <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
                    El paciente citado, ya no esta asegurado y por consiguiente no tiene derecho de atención.
                  </div>
                </div>';
            }
          ?>
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">NUEVA ATENCION</a></li>
            <!--li><a href="#tab3default" data-toggle="tab" style="font-size:12px">ANTECEDENTES</a></li-->
            <li><a href="#tab4default" data-toggle="tab" style="font-size:12px">HISTORIAL DE ATENCIONES</a></li>
            <li><a href="#tab5default" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE LABORATORIO</a>
            </li>
            <li><a href="#tab6default" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE IMAGENOLOGIA</a>
            </li>
          </ul>
        </div>
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active" id="tab2default">
              <fieldset>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-sm-12 col-md-12"> 
                      <div class="row">
                        <div class="panel panel-primary filterable">
                          <div class="panel-heading">
                            <h3 class="panel-title">MÓDULO DE GESTIÓN DE ATENCIONES MEDICAS</h3>
                          </div>

                          <div class="panel-body">
                            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->
                            <form class="form-horizontal" method="post" action="{{ route('registrar_prestacion')}}"
                              id="frmPrestacion" autocomplete="off">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="id_nivel" id="id_nivel" value="<?php echo $nivel?>">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia"
                                id="id_farmacia">
                              <input type="hidden" name="id_cita" id="id_cita" value="<?php echo $id_cita?>">
                              <input type="hidden" name="id_establecimiento" id="id_establecimiento"
                                value="<?php echo $cita->id_establecimiento?>">
                              <input type="hidden" name="id_asegurado" id="id_asegurado"
                                value="<?php echo $cita->id_asegurado?>">
                              <input type="hidden" name="id_servicio" id="id_servicio"
                                value="<?php echo $cita->id_servicio?>">
                              <input type="hidden" name="id_sub_consultorio" id="id_sub_consultorio"
                                value="<?php echo $cita->id_sub_consultorio?>">
                              <input type="hidden" name="id_medico" id="id_medico"
                                value="<?php echo $cita->id_medico?>">
                                <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="<?php echo $id_tipo_atencione?>">
                                

                              <div class="panel panel-info panel-bordered panel-sm panel-body">
                                <fieldset>
                                <!--  <legend>
                                    <b>DATOS DE LA ATENCI&Oacute;N</b>
                                    @if($id_tipo_atencione==1)
                                    <button type="button" id="btn_tv_posponer" class="btn btn-sm btn-warning" onclick="posponerLlamadoTv()">Posponer</button>&nbsp;
                                    <button type="button" id="btn_tv_llamar" class="btn btn-sm btn-success" onclick="llamarTv()">Llamar</button>
                                    <span>Cantidad de llamados: </span><span id="cant_llamado_tv">{{$cita->cantidad_llamada}}</span>
                                  	@endif
                                  </legend>-->
                                  <?php 
                                      	switch ($id_tipo_atencione) {
                                      		case '1': //AMBULATORIO
                                      			$descripcion='SERVICIO'; $descripcion2='CONSULTORIO'; $atencion='AMBULATORIO';break;
                                      		
                                      		case '2': //EMERGENCIA
                                      			$descripcion='PRIORIDAD'; $descripcion2='TOPICO';$atencion='EMERGENCIA';break;
                                      		case '3': //HOSPITALIZACION
                                      			$descripcion='SALA'; $descripcion2='SERVICIO';$atencion='HOSPITALIZACION';break;                                      		
                                      	}
                                      ?>
                                  <div>
                                    <div class="form-group">
                                      <div class="col-md-3">
                                        <label>TIPO DE ATENCI&Oacute;N</label>
                                        <input tabindex="6" readonly="" type="text" class="form-control"
                                          value="<?php echo $atencion?>" name="tipo_atencione" id="tipo_atencione"
                                          style="text-align:left">
                                      </div>
                                      
                                      <div class="col-md-3" id="divModalidad" <?php if($id_tipo_atencione!=1) echo  'style="display:none"'; ?>>
                                        <label for="modalidad_beneficiario">MODALIDAD</label>
                                        <select class="form-control" name="id_modalidad" id="id_modalidad" onchange="getConsultorio()"
                                          tabindex="8" style="width: 100%">
                                          <option value="0">--Seleccionar--</option>
                                          <option value="1" <?php if($modalidad == 1 ) echo 'selected'; ?>>Presencial</option>
                                          <option value="2" <?php if($modalidad == 2 ) echo 'selected'; ?>>Virtual</option>
                                        </select>
                                      </div>
                                      <div class="col-md-3">
                                        <label for="dni_beneficiario"><?php echo $descripcion ?></label>
                                        <input tabindex="5" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->servicio?>" name="cc" id="cc"
                                          style="text-align:left">
                                      </div>
                                      <div class="col-md-3">
                                        <label><?php echo $descripcion2 ?></label>
                                        <input tabindex="6" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->consultorio?>" name="beneficiario" id="beneficiario"
                                          style="text-align:left">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                    	<div class="col-md-2">
                                        <label>N° HISTORIA CLINICA</label>
                                        <input <?php echo ($cita->nro_historia!="")?"readonly='readonly'":""?>
                                          tabindex="3" type="text" class="form-control" value="<?php echo $cita->nro_historia?>"
                                          name="nro_historia" id="nro_historia" style="text-align:left">
                                      </div>
                                      <div class="col-md-2">
                                        <label>FECHA DE ATENCION</label>
                                        <input tabindex="2" type="text" class="form-control datepicker"
                                          value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>"
                                          name="fecha_atencion" id="fecha_atencion" style="text-align:left" data-date-end-date="0d">
                                      </div>
                                      <div class="col-md-2">
                                        <label for="dni_beneficiario">CODIGO UPSS</label>
                                        <select tabindex="8" class="form-control" name="codigo_upss" id="codigo_upss"
                                          style="width:100%">
                                          <?php if(count($codigo_upss)>1) : ?>
                                          	<option value="">-Seleccionar-</option>
                                          <?php endif; ?>
                                          <?php foreach($codigo_upss as $row):?>
                                          <option value="<?php echo $row->codigo?>">
                                            <?php echo $row->codigo?></option>
                                          <?php endforeach;?>
                                        </select>
                                      </div>
                                      <div class="col-md-6">
                                        <label for="tipo">NOMBRE DE LA IPRESS</label>
                                        <input tabindex="4" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->establecimiento?>" name="cc" id="cc"
                                          style="text-align:left">
                                      </div>
                                    </div>
                                  @if($id_tipo_atencione==3)
                                    <div class="form-group">
                                      <div class="col-md-3">
                                        <label for="tipo">PABELLON</label>
                                        <input tabindex="7" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->pabellon?>" name="cc" id="cc"
                                          style="text-align:left">
                                      </div>
                                      <div class="col-md-3">
                                        <label for="dni_beneficiario">PISO</label>
                                        <input tabindex="8" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->piso?>" name="cc" id="cc"
                                          style="text-align:left">
                                      </div>
                                      <div class="col-md-3">
                                        <label>CUARTO</label>
                                        <input tabindex="9" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->cuarto?>" name="cc" id="cc"
                                          style="text-align:left">
                                      </div>
                                      <div class="col-md-3">
                                        <label>CAMA</label>
                                        <input tabindex="10" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->cama?>" name="cc" id="cc"
                                          style="text-align:left">
                                      </div>
                                    </div>
                                    <input type="hidden" value="1" name="id_modalidad" id="id_modalidad" class="form-control">
                                  @endif
                                  </div>
                                </fieldset>
                              </div>

                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>INFORMACIÓN DEL PACIENTE</b>
                                    </div>
                                  </div>
                                  <div class="panel-body" id="collapseprestacionespaciente">
                                    <div class="form-group">
                                      <div class="col-md-4">
                                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                        <input tabindex="11" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->tipo_doc_ident?>" name="tipodoc_beneficiario_bus"
                                          id="tipodoc_beneficiario_bus" style="text-align:left">
                                        <input type="hidden" value="<?php echo $cita->tipo_doc_ident?>" name="tipodoc_beneficiario"
                                          id="tipodoc_beneficiario" class="form-control">
                                        <input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular"
                                          class="form-control">
                                      </div>
                                      <div class="col-md-4">
                                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                                        <input tabindex="12" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->nro_doc_ident?>" name="dni_beneficiario"
                                          id="dni_beneficiario" style="text-align:left">
                                        <input type="hidden" class="form-control" value="" name="beneficiario"
                                          id="beneficiario" style="text-align:left">
                                        <input type="hidden" class="form-control" value="" name="cip_beneficiario"
                                          id="cip_beneficiario" readonly="" />
                                        <input type="hidden" class="form-control" value="<?php echo $cita->nro_doc_ident?>" name="nrodocafiliado"
                                          id="nrodocafiliado" readonly="" />
                                        <input type="hidden" class="form-control" value="" name="nrodoctitular"
                                          id="nrodoctitular" readonly="" />
                                      </div>
                                      <div class="col-md-4">
                                        <label for="dni_beneficiario">SEXO</label>
                                        <input tabindex="13" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->sexo?>" name="nomsexo" id="nomsexo"
                                          style="text-align:left">
                                      </div>
                                    </div>

                                    <div class="form-group">
                                      <div class="col-md-4">
                                        <label>APELLIDO PATERNO</label>
                                        <input tabindex="14" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->asegurado_paterno?>" name="paterno_beneficiario"
                                          id="paterno_beneficiario" style="text-align:left">
                                        <input type="hidden" class="form-control" value="" name="nombre_titular"
                                          id="nombre_titular" />
                                        <input type="hidden" class="form-control" value="" name="paterno_titular"
                                          id="paterno_titular" />
                                        <input type="hidden" class="form-control" value="" name="materno_titular"
                                          id="materno_titular" />
                                      </div>

                                      <div class="col-md-4">
                                        <label>APELLIDO MATERNO</label>
                                        <input tabindex="15" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->asegurado_materno?>" name="materno_beneficiario"
                                          id="materno_beneficiario" style="text-align:left">
                                      </div>

                                      <div class="col-md-4">
                                        <label>NOMBRES</label>
                                        <input tabindex="16" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->asegurado_nombre?>" name="nombre_beneficiario"
                                          id="nombre_beneficiario" style="text-align:left">
                                      </div>
                                    </div>

                                    <div class="form-group">
                                      <div class="col-md-4">
                                        <label>FECHA DE NACIMIENTO</label>
                                        <input tabindex="17" readonly="" type="text" class="form-control"
                                          value="<?php echo $cita->fecha_nac?>" name="fecnacafiliado"
                                          id="fecnacafiliado" style="text-align:left">
                                      </div>
                                      <div class="col-md-4">
                                        <label>PARENTESCO &nbsp;</label>
                                        <input tabindex="18" readonly="" value="<?php echo $cita->parentesco?>" type="text"
                                          name="tipo_beneficiario" id="tipo_beneficiario" class="form-control">
                                      </div>

                                      <div class="col-md-4">
                                        <label>GRADO &nbsp;</label>
                                        <input tabindex="19" readonly="" value="<?php //echo $cita->grado?>" type="text" name="grado"
                                          id="grado" class="form-control">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                            <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  	<div class="panel-heading">
                                    	<div class="panel-title text-center">
                                      		<b>INFORMACIÓN DEL RESPONSABLE DE ATENCION</b>
                                    	</div>
                                  	</div>
                                	<div class="panel-body">
                                    	<div class="form-group">
                                      		<div class="col-md-4">
                                        		<label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                        		<input tabindex="20" readonly="" type="text" class="form-control" value="DNI" style="text-align:left">
                                      			</div>
                                      		<div class="col-md-4">
                                        		<label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                                        		<input tabindex="21" readonly="" type="text" class="form-control" value="<?php echo $cita->dni?>" name="cc" id="cc" style="text-align:left">
    		                                </div>
	                                    </div>
                                    	<div class="form-group">
		                                    <div class="col-md-4">
		                                        <label>APELLIDO PATERNO</label>
		                                        <input tabindex="22" readonly="" type="text" class="form-control"
		                                          value="<?php echo $cita->medico_paterno?>" name="cc" id="cc"
		                                          style="text-align:left">
		                                    </div>
		                                    <div class="col-md-4">
		                                        <label>APELLIDO MATERNO</label>
		                                        <input tabindex="23" readonly="" type="text" class="form-control"
		                                          value="<?php echo $cita->medico_materno?>" name="cc" id="cc"
		                                          style="text-align:left">
		                                    </div>
		                                    <div class="col-md-4">
		                                        <label>NOMBRES</label>
		                                        <input tabindex="24" readonly="" type="text" class="form-control"
		                                          value="<?php echo $cita->medico_nombre?>" name="cc" id="cc"
		                                          style="text-align:left">
		                                    </div>
                                    	</div>
                                    	<div class="form-group">
		                                    <div class="col-md-2">
		                                        <label>N° COLEGIATURA</label>
		                                        <input tabindex="25" readonly="" type="text" class="form-control"
		                                          value="<?php echo $cita->colegiatura?>" name="cc" id="cc"
		                                          style="text-align:left">
		                                      </div>
		                                      <div class="col-md-2">
		                                        <label>N° RNE</label>
		                                        <input tabindex="26" readonly="" value="<?php echo $cita->rne?>" type="text" name="rne"
		                                          id="rne" class="form-control">
		                                      </div>
		                                      <div class="col-md-4">
		                                        <label>PROFESION</label>
		                                        <input tabindex="27" readonly="" value="<?php echo $cita->profesion?>" type="text"
		                                          name="profesion" id="profesion" class="form-control">
		                                      </div>
		                                      <div class="col-md-4">
		                                        <label>ESPECIALIDAD</label>
		                                        <input tabindex="28" readonly="" value="<?php echo $cita->especializacion?>" type="text"
		                                          name="especializacion" id="especializacion" class="form-control">
		                                      </div>
                                    		</div>
                                  		</div>
                                	</div>
                              	</fieldset>
                              	@if($id_tipo_atencione==2)
                              	<fieldset>
				                  	<div class="panel panel-info panel-bordered panel-sm">
				                    	<div class="panel-heading">
				                      		<div class="panel-title text-center">
				                        		<b>INFORMACIÓN DE SIGNOS VITALES - TRIAJE</b>
				                      		</div>
				                    	</div>
				                    	<div class="panel-body">
					                      	<!-- <div class="form-group">
					                        	<div class="col-md-2">
					                          		<label>TEMPERATURA (°C) &nbsp;</label>
					                          		<input placeholder="0.00" value="" type="text" name="temperatura" id="temperatura" class="form-control" tabindex="29" onkeypress="return esNumero(event,this.id)" maxlength="6" />
					                        	</div>
				                        		<div class="col-md-2">
				                          			<label>TALLA (cm) &nbsp;</label>
                                        <input placeholder="0" value="" type="text" name="talla" id="talla" class="form-control" tabindex="30" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="3" >
				                        		</div>
				                        		<div class="col-md-2">
				                          			<label>PESO (Kg) &nbsp;</label>
				                          			<input placeholder="0.00" value="" type="text" name="peso" id="peso" class="form-control"  tabindex="31" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)"  maxlength="6" >
				                        		</div>
				                        		<div class="col-md-2">
				                          			<label>IMC &nbsp;</label>
				                          			<input value="" type="text" name="imc" id="imc" class="form-control" readonly="" tabindex="32" >
				                        		</div>
				                        		<div class="col-md-2">
				                          			<label>PESO POR ALTURA &nbsp;</label>
				                          			<input value="" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control" readonly="" max="0" tabindex="33" >
				                        		</div>
				                        		<div class="col-md-2">
				                          			<label>Sa02 (%) &nbsp;</label>
				                          			<input placeholder="0" value="" type="text" name="saturacion" id="saturacion" class="form-control" tabindex="34" onkeypress="return esNumero(event,this.id)" maxlength="3" />
				                        		</div>
				                      		</div>
				                      		<div class="form-group">
				                        		<div class="col-md-2">
				                          			<label>TAMAÑO DE CINTURA (cm) &nbsp;</label>
				                          			<input placeholder="0" value="" type="text" name="tamanio_cintura" id="tamanio_cintura" class="form-control" tabindex="35" onkeypress="return esNumero(event,this.id)" maxlength="3" />
				                        		</div>
				                        		<div class="col-md-2">
				                          			<label>FREC. CARDIACA FETAL (/min) &nbsp;</label>
				                          			<input placeholder="0" value="" type="text" name="frecuencia_cardiaca_fetal" id="frecuencia_cardiaca_fetal" class="form-control" tabindex="36" onkeypress="return esNumero(event,this.id)" maxlength="3" />
				                        		</div>
				                        		<div class="col-md-2">
				                          			<label>PRESIÓN ARTERIAL &nbsp;</label>
				                          			<div class="row">
                                          <div class="col-xs-5">  
                                            <input placeholder="0" value="" tabindex="37" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                          </div>  
                                            <div class="col-xs-2">
                                               <label>/</label> 
                                            </div>
                                          <div class="col-xs-5">
                                            <input placeholder="0" value="" type="text" tabindex="32" name="presion_arterial_y" id="presion_arterial_y" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                          </div>  
                                        </div>
				                        		</div>
				                        		<div class="col-md-2">
				                          			<label>FREC. RESPIRATORIA (/min) &nbsp;</label>
				                          			<input placeholder="0" value="" type="text" name="frecuencia_respiratoria" tabindex="38" id="frecuencia_respiratoria" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
				                        		</div>
				                        		<div class="col-md-2">
				                          			<label>FREC. CARDIACA (/min) &nbsp;</label>
				                          			<input placeholder="0" value="" type="text" name="frec_cardiaca" id="frec_cardiaca" tabindex="39" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
				                        		</div>
				                        		<div class="col-md-2">
				                          			<label>Fi02 (%) &nbsp;</label>
				                          			<input placeholder="0" value="" type="text" name="oxigenacion" id="oxigenacion" tabindex="40" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
				                          			<input type="hidden" value="1" name="id_modalidad" id="id_modalidad" class="form-control">
				                        		</div>
				                      		</div> -->
				                    	</div>
				                  	</div>
				                </fieldset>
				                @endif
                              	<fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>CONDICION</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <div class="col-md-4">
                                        <label for="tipo">IPRESS</label>
                                        <select class="form-control input-sm" id="ipress_bene" name="ipress_bene"
                                          tabindex="41">
                                          <option value="NUEVO" <?php if($ipress_bene=='NUEVO') echo 'selected'; ?>>NUEVO</option>
                                          <option value="CONTINUADOR" <?php if($ipress_bene=='CONTINUADOR') echo 'selected'; ?>>CONTINUADOR</option>
                                          <option value="REPETITIVO" <?php if($ipress_bene=='REPETITIVO') echo 'selected'; ?>>REPETITIVO</option>
                                        </select>
                                      </div>
                                      <div class="col-md-4">
                                        <label for="tipo">UPSS</label>
                                        <select class="form-control input-sm" id="upss_bene" name="upss_bene"
                                          tabindex="42">
                                          <option value="NUEVO" <?php if($upss_bene=='NUEVO') echo 'selected'; ?>>NUEVO</option>
                                          <option value="CONTINUADOR" <?php if($upss_bene=='CONTINUADOR') echo 'selected'; ?>>CONTINUADOR</option>
                                          <option value="REPETITIVO" <?php if($upss_bene=='REPETITIVO') echo 'selected'; ?>>REPETITIVO</option>
                                        </select>
                                      </div>
                                    <div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>DIAGNÓSTICOS</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <table>
                                      <tr>
                                        <td>
                                          <button type="button" id="addDiagnostico" class="btn btn-primary"
                                            data-toggle="modal" data-target="#addClassModal">Agregar
                                            Diagnostico</button>
                                        </td>
                                      </tr>
                                    </table>
                                    <br />
                                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%"
                                      style="margin-top:5px;">
                                      <thead>
                                        <tr>
                                          <th width="90%"></th>
                                          <th width="10%"></th>
                                        </tr>
                                      </thead>
                                      <tfoot>
                                      </tfoot>
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>PROCEDIMIENTOS</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <table>
                                      <tr>
                                        <td>
                                          <button type="button" id="addProcedimiento" class="btn btn-primary"
                                            data-toggle="modal" data-target="#addClassModal">Agregar
                                            Procedimiento</button>
                                        </td>
                                      </tr>
                                    </table>
                                    <br />
                                    <table class="display" id="tblProcedimiento" cellspacing="0" width="100%"
                                      style="margin-top:5px;">
                                      <thead>
                                        <tr>
                                          <th width="90%"></th>
                                          <th width="10%"></th>
                                        </tr>
                                      </thead>
                                      <tfoot>
                                      </tfoot>
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </fieldset>
                              <div class="panel with-nav-tabs panel-default">
                                <div class="panel-heading">
                                  <ul class="nav nav-tabs">
                                    <li  class="active"><a href="#tab110default" data-toggle="tab" style="font-size:12px">ANTECEDENTES</a></li>
                                    <li><a href="#tab7default" data-toggle="tab" style="font-size:12px">ANAMNESIS</a></li>
                                    <li><a href="#tab8default" data-toggle="tab" style="font-size:12px">EXAMEN FÍSICO</a></li>
                                    <li><a href="#tab9default" data-toggle="tab" style="font-size:12px">TRATAMIENTO HABITUAL</a></li>
                                    <li><a href="#tab10default" data-toggle="tab" style="font-size:12px">PLAN DE TRABAJO</a></li>
                                  </ul>
                                </div>
                                <div class="panel-body">
                                	<div class="tab-content">
                                  	<div class="tab-pane fade in active" id="tab110default">
                                      <div class="panel-body">
                                        <div class="col-md-12">
                                          <label>ANTECEDENTES</label>
                                          <textarea class="form-control" cols="5" id="antecedente" name="antecedente"
                                            placeholder="Antecedentes personales"></textarea>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="col-md-12">
                                          <label>ANTECEDENTE FAMILIARES</label>
                                          <textarea class="form-control" cols="5" id="antecedente_familiar" name="antecedente_familiar"
                                            placeholder="Antecedente Familiar"></textarea>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="tab-pane" id="tab7default">
                                      <div class="panel-body">
                                        <div class="col-md-12">
                                          <label>MOTIVO DE VISITA</label>
                                          <textarea class="form-control" cols="5" id="motivo_visita" name="motivo_visita"
                                            placeholder="Relato m&eacute;dico del motivo de la visita"></textarea>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="col-md-12">
                                          <label>TIEMPO DE ENFERMEDAD</label>
                                          <textarea class="form-control" cols="5" id="tiempo_enfermedad" name="tiempo_enfermedad"
                                            placeholder="Relato m&eacute;dico del tiempo de enfermedad"></textarea>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="col-md-12">
                                          <label>RELATO CRONOLOGICO</label>
                                          <textarea class="form-control" cols="5" id="anamnesis" name="anamnesis"
                                            placeholder="Relato m&eacute;dico del relato cronologico"></textarea>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="tab-pane" id="tab8default">
																		@if($cita->id_establecimiento==75 &&  ($cita->id_servicio == 28 || $cita->id_servicio == 106 || $cita->id_servicio == 107))
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                            <label>ASPECTOS GENERALES</label>
                                          </div>
                                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                            <button tabindex="62" type="button" id="addAspectosGenerales" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </div>
                                        </div>
                                        <div class="panel panel-info panel-bordered panel-sm" id="blockAspectosGenerales" style="display: none;">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center">
                                              <b>ASPECTOS GENERALES</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <table>
                                              <tr>
                                                <td>
                                                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEscala">Escalas de Valor</button>
                                                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFichaPsicologia">Ficha Psicológica</button>
                                                </td>
                                              </tr>
                                            </table>
                                            <br />
                                            <div class="form-group" id="bloqueEscalas">
                                              <div class="col-md-12">
                                                <textarea tabindex="61" class="form-control app-textarea" cols="5" rows="5" id="aspectos_generales" name="aspectos_generales"placeholder="Aspectos Generales" readonly></textarea>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    @endif
                                    @if(($cita->id_establecimiento==75 &&  $cita->id_servicio == 27) || ($cita->id_establecimiento==76))
                                      <div class="panel-body" id="VerSignosVitales">
                                        <div class="form-group">
                                          <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                            <label>SIGNOS VITALES</label>
                                          </div>
                                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                            <button tabindex="62" type="button" id="addSignosVitales" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </div>
                                        </div>
                                        <div class="panel panel-info panel-bordered panel-sm" id="fsSignosVitales" style="display:none">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center">
                                              <b>INFORMACIÓN DE SIGNOS VITALES</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">

                                            <div class="form-group">
                                              <div class="col-md-2">
                                                <label>TEMPERATURA (°C) &nbsp;</label>
                                                <input placeholder="00.00" value="@if(isset($prestacion)){{$prestacion->temperatura}}@endif" type="text" name="temperatura" id="temperatura" class="form-control" tabindex="23" onkeypress="return esNumero(event,this.id)" maxlength="5" />
                                              </div>
                                              <div class="col-md-2">
                                                <label>TALLA (cm) &nbsp;</label>
                                                <input value="@if(isset($prestacion)){{$prestacion->talla_cm}}@endif" type="text" name="talla" id="talla" class="form-control" tabindex="24" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>PESO (Kg) &nbsp;</label>
                                                <input placeholder="00.00" value="@if(isset($prestacion)){{$prestacion->peso_kg}}@endif" type="text" name="peso" id="peso" class="form-control"  tabindex="25" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="6" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>IMC &nbsp;</label>
                                                <input value="" type="text" name="imc" id="imc" class="form-control" readonly="" tabindex="26" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>PESO IDEAL &nbsp;</label>
                                                <input value="" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control" readonly="" max="0" tabindex="27" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>Sa02 (%) &nbsp;</label>
                                                <input value="@if(isset($prestacion)){{$prestacion->sa02_porcentaje}}@endif" type="text" name="saturacion" id="saturacion" class="form-control" tabindex="28" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <div class="col-md-2">
                                                <label>TAMAÑO DE CINTURA (cm)</label>
                                                <input value="@if(isset($prestacion)){{$prestacion->tamano_cintura_cm}}@endif" type="text" name="tamanio_cintura" id="tamanio_cintura" class="form-control" tabindex="29" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                              <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                                <label>FREC. CARDIACA FETAL(/min)</label>
                                                <input tabindex="30" value="@if(isset($prestacion)){{$prestacion->frecuencia_cardiaca_fetal}}@endif" type="text" name="frecuencia_cardiaca_fetal" id="frecuencia_cardiaca_fetal" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>PRESIÓN ARTERIAL &nbsp;</label>
                                                <div class="row">
                                                  <div class="col-xs-5">                          
                                                    <input value="@if(isset($prestacion)){{$prestacion->presion_arterial_x}}@endif" tabindex="31" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                                  </div>  
                                                    <div class="col-xs-2">
                                                       <label>/</label> 
                                                    </div>
                                                  <div class="col-xs-5">
                                                    <input value="@if(isset($prestacion)){{$prestacion->presion_arterial_y}}@endif" type="text" tabindex="32" name="presion_arterial_y" id="presion_arterial_y" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                                  </div>  
                                                </div>
                                            </div>
                                              <div class="col-md-2">
                                                <label>FREC.RESPIRATORIA(/min)</label>
                                                <input value="@if(isset($prestacion)){{$prestacion->frecuencia_respiratoria}}@endif" type="text" name="frecuencia_respiratoria" tabindex="33" id="frecuencia_respiratoria" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>FREC. CARDIACA (/min) &nbsp;</label>
                                                <input value="@if(isset($prestacion)){{$prestacion->frecuencia_cardiaca}}@endif" type="text" name="frec_cardiaca" id="frec_cardiaca" tabindex="34" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>Fi02 (%) &nbsp;</label>
                                                <input value="@if(isset($prestacion)){{$prestacion->fi02}}@endif" type="text" name="oxigenacion" id="oxigenacion" tabindex="35" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    @endif
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                            <label>EXAMEN FISICO</label>
                                            <textarea class="form-control" cols="5" id="examen_fisico" name="examen_fisico"
                                              placeholder="Examen Físico"></textarea>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                            <label>EXAMEN CLINICO</label>
                                            <textarea class="form-control" cols="5" id="examen_clinico" name="examen_clinico"
                                              placeholder="Examen Clínico"></textarea>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                            <label>EXAMENES AUXILIARES</label>
                                            <textarea class="form-control" cols="5" id="examen_auxiliar" name="examen_auxiliar"
                                              placeholder="Examenes auxiliares"></textarea>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="tab-pane" id="tab9default">
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                            <label>TRATAMIENTO AUXILIAR</label>
                                            <textarea class="form-control" cols="5" id="tratamiento_auxiliar" name="tratamiento_auxiliar"
                                              placeholder="Tratamiento auxiliar"></textarea>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="tab-pane" id="tab10default">
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                            <label>PLAN DE TRABAJO</label>
                                            <textarea class="form-control" cols="5" id="plan_trabajo" name="plan_trabajo"
                                              placeholder="Plan de Trabajo"></textarea>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="panel panel-info panel-bordered panel-sm panel-body">
                                @if($id_tipo_atencione==1)
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>DERIVAR CONSULTA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="62" type="button" id="addDerivarServicio" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                  <div id="divServicio" style="display:none">
                                    <div class="col-md-1"
                                      style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                      <label>SERVICIO</label>
                                    </div>
                                    <div class="col-md-4" style="margin-left:0px;padding-left:0px">
                                      <select class="form-control" name="id_servicio_derivar" id="id_servicio_derivar"
                                        tabindex="63" style="width: 100%">
                                        <option value="0">--Seleccionar--</option>
                                        <?php foreach($consultorio as $row):?>
                                        <option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
                                        <?php endforeach;?>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                @endif
                                @if ($cita->id_establecimiento == 76 && $cita->id_servicio == 27)
                                  <div class="form-group">
                                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>REGISTRAR CITA</label>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="69" type="button" id="addRegistrarCita" class="btn btn-lg btn-toggle"
                                        data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                      </button>
                                      <input type="hidden" name="registrar_cita" id="registrar_cita" value="true">
                                    </div>
                                  </div>
                                @endif
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>PRESCRIBIR RECETA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="70" type="button" id="addRecetaVale" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                </div>
                              </div>
                              <fieldset id="fsRecetaVale" style="display:none">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>PRODUCTOS MEDICOS</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-2">
                                        <label>FEC. EXPEDICIÓN (A)</label>
                                        <input tabindex="71" type="text" class="form-control datepicker"
                                          value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>"
                                          name="fecha_expedicion" id="fecha_expedicion" tabindex="2"
                                          data-date-end-date="0d" autocomplete="off" />
                                      </div>
                                      <div class="col-md-5">
                                        <label>FARMACIA</label>
                                        <select tabindex="72" class="form-control" name="id_farmacia_receta" id="id_farmacia_receta"
                                          style="width:100%" onchange="getProductoAndAddProducto()">
                                          <option value="">-Seleccionar-</option>
                                          <?php foreach($farmacias as $row):?>
                                          <option value="<?php echo $row->id?>"
                                            <?php if($row->id==Auth::user()->id_farmacia) echo "selected='selected'"?>>
                                            <?php echo $row->nombre?></option>
                                          <?php endforeach;?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-sm-2"
                                        style="padding-top:5px;margin-right:0px;padding-right:0px;font-size:13px">
                                        <label>PACIENTE CONTINUADOR</label>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="73" type="button" id="addCondicion" class="btn btn-lg btn-toggle"
                                          data-toggle="button" aria-pressed="false" autocomplete="off">
                                          <div class="handle"></div>
                                        </button>
                                      </div>
                                      <input type="hidden" name="condicion" id="condicion" value="0">
                                      <div id="divCondicion" style="display:none">
                                        <div class="col-md-2"
                                          style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px;font-size:13px">
                                          <label>DIAS DE RECETAS</label>
                                        </div>
                                        <div class="col-md-1" style="margin-left:0px;padding-left:0px">
                                          <select class="form-control" name="cantidadReceta" id="cantidadReceta"
                                            tabindex="74" style="width: 100%">
                                            <option value="0"></option>
                                            <option value="1">30</option>
                                            <option value="2">60</option>
                                            <option value="3">90</option>
                                            <option value="4">120</option>
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                    <br />
                                    <!-- <table>
                                      <tr>
                                        <td>
                                          <button tabindex="75" type="button" id="addProducto" class="btn btn-primary"
                                            data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                                        </td>
                                      </tr>
                                    </table>
                                    <br /> -->
                                    <!--
																	<table class="table table-striped table-bordered table-condensed table-hover my_datatable" id="listaprestacionesdiagnosticos">
																	</table>
																	-->
                                    <div style="display: none">
                                      <select class="form-control" id="cmbMedicamentoTemp" tabindex="16"
                                        style="width: 500px">
                                        <option value="">Seleccionar Producto</option>
                                      </select>
                                    </div>
                                    <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                    <table id="example" class="display" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                          <th>Producto farmacéutico o dispositivo médico</th>
                                          <th style="width:10%">Stock Almacen</th>
                                          <th style="width:10%">Stock <?php //echo $establecimiento->nombre?></th>
                                          <th style="width:10%">Stock <?php //echo $farma->nombre?></th>
                                          <th>Tiempo(Dias)</th>
                                          <th>Posologia</th>
                                          <th>Cantidad Prescrita</th>
                                          <th></th>
                                          <th>Agregar</th>
                                          <th>Eliminar</th>
                                        </tr>
                                      </thead>
                                      <tfoot>
                                      </tfoot>
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </fieldset>
                              <div class="panel panel-info panel-bordered panel-sm panel-body">
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>EXAMEN DE LABORATORIO</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="90" type="button" id="addExamenLab" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                </div>
                                <fieldset id="fsExamenLab" style="display:none">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>EXAMEN LABORATORIO</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <table>
                                        <tr>
                                          <td>
                                            <button tabindex="91" type="button" id="addExamenLab2" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
                                          </td>
                                        </tr>
                                      </table>
                                      <br />
                                      <table class="display" id="tblExamenLab" cellspacing="0" width="100%"
                                        style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                            <th width="90%"></th>
                                            <th width="10%"></th>
                                          </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              <div class="panel panel-info panel-bordered panel-sm panel-body">
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>EXAMEN IMAGENOLOGIA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="100" type="button" id="addExamenRad" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                </div>
                                <fieldset id="fsExamenRad" style="display:none">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>EXAMEN IMAGENOLOGIA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <table>
                                        <tr>
                                          <td>
                                            <button tabindex="101" type="button" id="addExamenRad2" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
                                          </td>
                                        </tr>
                                      </table>
                                      <br />
                                      <table class="display" id="tblExamenRad" cellspacing="0" width="100%"
                                        style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                            <th width="90%"></th>
                                            <th width="10%"></th>
                                          </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              <!--div class="panel panel-info panel-bordered panel-sm panel-body">
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>INTERCONSULTA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="110" type="button" id="addInterconsulta" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                </div>
                                <fieldset id="fsInterconsulta" style="display:none">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                  	<div class="panel-heading">
                                    	<div class="panel-title text-center">
                                      		<b>INTERCONSULTA MÉDICA</b>
                                    	</div>
                                  	</div>
                                  	<div class="panel-body">
                                    	<div class="form-group">
                                    		<div class="panel-body">
                                      			<table>
                                        			<tr>
                                          				<td>
                                            				<button tabindex="111" type="button" id="addInterconsulta2" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Interconsulta</button>
                                          				</td>
                                        			</tr>
                                      			</table>
                                      			<br />
                                      			<table class="display" id="tblInterconsulta" cellspacing="0" width="100%" style="margin-top:5px;">
                                        			<thead>
                                          				<tr>
				                                           <th width="90%"></th>
				                                           <th width="10%"></th>
				                                        </tr>
                                        			</thead>
			                                        <tfoot>
			                                        </tfoot>
			                                        <tbody>
			                                        </tbody>
                                      			</table>
                                    		</div>
                                    	</div>
                                  	</div>
                                 </div>
                                </fieldset>
                              </div>
                              <div class="panel panel-info panel-bordered panel-sm panel-body">
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>REFERENCIA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="120" type="button" id="addReferencia" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                </div>
                                <fieldset id="fsReferencia" style="display:none">
                                  	<div class="panel panel-info panel-bordered panel-sm">
	                                  	<div class="panel-heading">
	                                    	<div class="panel-title text-center">
	                                      		<b>REFERENCIA MÉDICA</b>
	                                    	</div>
	                                  	</div>
                                  		<div class="panel-body">
                                    		<div class="form-group">
                                    			<div class="col-md-12">
			                                        <div class="col-md-4">
				                                      <label>ESTABLECIMIENTO</label>
				                                      <select class="form-control" name="id_establecimiento_referencia" id="id_establecimiento_referencia"
				                                        tabindex="121" style="width: 100%">
				                                        <option value="">--Seleccionar--</option>
				                                        <?php foreach($establecimientos as $row):?>
				                                        <option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
				                                        <?php endforeach;?>
				                                      </select>
				                                    </div>
				                                    <div class="col-md-4">
					                                    <label>ESPECIFICAR SI REQUIERE UCI</label>
					                                    <select class="form-control" name="id_uci" onchange="getVentiladorUci()" id="id_uci"
				                                        tabindex="122" style="width: 100%">
					                                        <option value="">--Seleccionar--</option>
					                                        <option value="1">SI</option>
					                                        <option value="2">NO</option>
				                                      	</select>
			                                      	</div>
			                                      	<div  id="div_uci_ventilador" style="display:none">
				                                    	<div class="col-md-4">
						                                    <label><br/></label>
						                                    <select class="form-control" name="id_uci_cv" id="id_uci_cv"
					                                        tabindex="123" style="width: 100%">
						                                        <option value="">--Seleccionar--</option>
						                                        <option value="1">CON VENTILADOR MECANICO</option>
						                                        <option value="2">SIN VENTILADOR MECANICO</option>
						                                        <option value="3">NINGUNO</option>
					                                      	</select>
				                                      	</div>
				                                   	</div>
				                                </div>
				                                
				                                <div class="col-md-12">
			                                      	<div class="col-md-4">
			                                      		<br/>
					                                    <label>REQUIERE TRASLADO EN AMBULANCIA</label>
					                                    <select class="form-control" name="id_ambulancia" onchange="getAmbulancia()" id="id_ambulancia"
				                                        tabindex="124" style="width: 100%">
					                                        <option value="">--Seleccionar--</option>
					                                        <option value="1">SI</option>
					                                        <option value="2">NO</option>
				                                      	</select>
				                                      	<label><br/></label>
				                                  	</div>
				                                    <div  id="div_ambulancia" style="display:none">
				                                    	<div class="col-md-4">
				                                    		<br/>
				                                    		<label><br/></label>
						                                    <select class="form-control" name="id_ambulancia_ref" id="id_ambulancia_ref"
					                                        tabindex="125" style="width: 100%">
						                                        <option value="">--Seleccionar--</option>
						                                        <option value="1">IDA</option>
						                                        <option value="2">VUELTA</option>				                                        
					                                      	</select>
					                                      	<label><br/></label>
				                                      	</div>
				                                      	<div class="col-md-4">
				                                      		<br/>
					                                   		<label>TIPO DE AMBULANCIA</label>
					                                   		<select class="form-control" name="id_tipo_ambulancia" id="id_tipo_ambulancia" tabindex="126" style="width: 100%">
							                                        <option value="">--Seleccionar--</option>
							                                        <option value="1">Tipo I</option>
							                                        <option value="2">Tipo II</option>
						                                    </select>
					                                   </div>
					                                </div>
					                            </div>
					                            <div class="col-md-12">
					                                <div class="col-md-4">
					                                    <label>REQUIERE PASAJE</label>
					                                    <select class="form-control" name="id_pasaje" id="id_pasaje"
				                                        tabindex="127" style="width: 100%">
					                                        <option value="">--Seleccionar--</option>
					                                        <option value="1">SI</option>
					                                        <option value="2">NO</option>
				                                      	</select>
				                                  	</div>
				                                  	<div class="col-md-4">
					                                    <label>NECESITA ACOMPAÑANTE</label>
					                                    <select class="form-control" name="id_acompanante" id="id_acompanante"
				                                        tabindex="128" style="width: 100%">
					                                        <option value="">--Seleccionar--</option>
					                                        <option value="1">SI</option>
					                                        <option value="2">NO</option>
				                                      	</select>
				                                  	</div>
				                                  	<div class="col-md-4">
					                                    <label>SITUACION CLINICA DEL PACIENTE</label>
					                                    <select class="form-control" name="id_situacion" id="id_situacion"
				                                        tabindex="129" style="width: 100%">
					                                        <option value="">--Seleccionar--</option>
					                                        <option value="1">ELECTIVO</option>
					                                        <option value="2">URGENCIA</option>
					                                        <option value="3">EMERGENCIA</option>
				                                      	</select>
				                                  	</div>
				                                </div>
				                                <div class="col-md-12">
				                                	<div class="col-md-12">
			                                        	<br/>
					                                    <label>OBSERVACION</label>
					                                    <textarea tabindex="130" class="form-control" cols="5" id="observacion_referencia" name="observacion_referencia"
					                                      placeholder="Relato m&eacute;dico o caracteristicas de la referencia"></textarea>
				                                	</div>
				                                </div>
                                    		</div>
                                  		</div>
                                	</div>
                                </fieldset>
                              </div-->
                              <input tabindex="140" <?php echo $disabled_atencion?> class="btn btn-primary pull-right" value="GUARDAR" name="crea" type="button"
                                form="prestacionescrea" id="btnPrestacion" />&nbsp;&nbsp;
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <!--div class="tab-pane fade" id="tab3default">
              <fieldset>
                <div class="panel panel-primary filterable">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>ANTECEDENTES</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divAntecedentes'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div-->
            <div class="tab-pane fade" id="tab4default">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE ATENCIONES MEDICAS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divHistorial'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade" id="tab5default">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE EXAMENES DE LABORATORIO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divLaboratorio'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade" id="tab6default">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE EXAMENES DE IMAGENOLOGIA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divRadiologico'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true" style="">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="{{ route('reasignar_lotes')}}" id="frmlotizar">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="modal-header" id="modal-header">
            <!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>-->
            <h4 class="modal-title" id="lineModalLabel">Dispensar</h4>
            <div id="alerta" class="alert alert-danger"></div>
          </div>
          <div class="modal-body">
            <table border="0px" id="tabla_lote">
              <thead>
                <tr>
                  <th style="width:10%">CÓdigo</th>
                  <th style="width:40%">Nombre</th>
                  <th style="width:10%">Lote</th>
                  <th style="width:10%">Reg. San.</th>
                  <th style="width:10%">Precio</th>
                  <th style="width:10%">Fec. ven.</th>
                  <th style="width:10%">Cant. Actual.</th>
                  <th style="width:10%">Cant. Entregar</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-group-justified" role="group" aria-label="group button">
              <div class="btn-group" role="group">
                <div class="col-md-6 text-center">
                  <button type="button" onclick="sumarCantidadLotes(0)" id="saveImage"
                    class="btn btn-success btn-hover-green" data-action="save" role="button">Guardar</button>
                </div>
                <div class="col-md-6 text-center">
                  <button type="button" onclick="sumarCantidadLotes(1)" id="saveImage"
                    class="btn btn-success btn-hover-green" data-action="save" role="button">Guardar y Agregar Nueva
                    Fila</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="openOverlayOpc" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">

        <!--<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title"><small style="font-size: 22px; color: #fff">Listado de medicos por dia</small></h4>
		</div>
		-->
        <div class="modal-body">

          <div id="diveditpregOpc"></div>

        </div>

      </div>

    </div>
  </div>

  <div class="modal fade" id="showAtencionesMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><small style="color: #fff">Nro. Recetas dispensadas y atenciones en el mes
              actual</small></h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th><small>Atenciones</small></th>
                  <th><small>Fecha <br />Dispensación</small></th>
                  <th><small>Fecha <br />Expedición</small></th>
                  <th><small>Consultorio</small></th>
                  <th><small>Farmacia</small></th>
                  <th><small>Médico</small></th>
                  <th><small>Diagnóstico</small></th>
                  <th><small>Nro. Aten-<br>ciones</small></th>
                  <th><small>Detalle<br />Producto</small></th>
                </tr>
              </thead>
              <tbody id="muestra-atenciones"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="showAtencionesDetMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" style="font-size: 22px; color: #fff">Nro. Atenciones según fecha de expedición</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered" style="font-size: 13px;">
              <thead>
                <tr>
                  <th><small>Nro. <br />Atención</small></th>
                  <th><small>Farmacia</small></th>
                  <th><small>Fecha <br />Expedición</small></th>
                  <th><small>Consultorio</small></th>
                  <th><small>Médico</small></th>
                  <th><small>Nro Receta</small></th>
                  <th><small>Fecha <br />Dispensación</small></th>
                  <th><small>Detalle<br />Producto</small></th>
                </tr>
              </thead>
              <tbody id="muestra-det-atenciones"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="showProductosReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Detalle de Recetas</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th><small>Código</small></th>
                  <th><small>Descipción</small></th>
                  <th><small>Unidad</small></th>
                  <th><small>Pedido</small></th>
                  <th><small>Despachado</small></th>
                </tr>
              </thead>
              <tbody id="muestra-productos"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="showStockEstablecimiento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><small style="color: #fff">PROD. FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS</small></h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th><small>Nro</small></th>
                  <th><small>Farmacia</small></th>
                  <th><small>Código</small></th>
                  <th><small>Producto Farmacéutico o Dispositivo Médico</small></th>
                  <th><small>Unidad</small></th>
                  <th><small>Stock Actual</small></th>
                </tr>
              </thead>
              <tbody id="muestra-stock"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
@include('frontend.includes.footer')
@endsection