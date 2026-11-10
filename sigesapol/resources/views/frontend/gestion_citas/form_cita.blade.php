@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<link href='js/fullcalendar-2.3.2/fullcalendar.css' rel='stylesheet' />
<link href='js/fullcalendar-2.3.2/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='js/fullcalendar-2.3.2/lib/moment.min.js'></script>
<script src='js/fullcalendar-2.3.2/fullcalendar.min.js'></script>
<script src='js/fullcalendar-2.3.2/lang/es.js'></script>
<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<style>

.modal-dialog {
	width: 90%;
  }
		  
.title-dialog {
  font-size: 18px;
  font-family:Arial, Helvetica, sans-serif;
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

input[readonly]{
    background-color:#eeeeee;
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
.uneditable-input:focus{   
  border-color: #2196F3;
  box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
  outline: 0 none;
}

/*.fc-event-container{margin:20px!important}*/
.fc-day-grid-event{/*width:150px;*//*margin:10px;*/text-align:left;/*margin-top:20px;*//*background:#5cb85c*/background:transparent;color:#000000}
.fc-day-grid-event :hover{color:#000000}
.fc-content{padding:0px!important;}
.fc-title{text-align:left;font-size:10px}
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

$(document).ready(function() {
    $('.nav-tabs li a').click(function(){
	  setTimeout("$('#calendar').fullCalendar('destroy'); fullCalendar();", 300);
    });
	
	obtenerEstablecimiento(2);
	
	$('#id_medico').on("change", function (e) {	
		fullCalendar();
	});

	$('#btnCita').on("click", function (e) {
		guardarCitaAutomatico();
	});

});

function guardarCitaAutomatico(){
    
    var msg = "";
    var id_establecimiento = $('#id_establecimiento').val();
    var id_servicio = $('#id_servicio').val();
    var fecha_atencion = $('#fecha_atencion').val();
    var dni_beneficiario = $("#dni_beneficiario").val();
	
	if(dni_beneficiario == "")msg += "Debe ingresar el Numero de Documento <br>";
    if(id_establecimiento=="")msg+="Debe seleccionar un Establecimiento<br>";
    if(id_servicio=="")msg+="Debe ingresar un Servicio<br>";
	if($('input[name=horario]').is(':checked')==false)msg+="Debe seleccionar un Turno<br>";
	
	if($('input[name=horario]').is(':checked')==true){
		var horario = $('input[name=horario]:checked').val();
		var data = horario.split("#");
		var fecha_cita = data[0];
		var id_medico = data[1];
	}
	
    if(msg!=""){
        bootbox.alert(msg); 
        return false;
    }
    else{
        fn_save_cita_automatico(id_medico,fecha_cita);
    }
}

function fn_save_cita_automatico(id_medico,fecha_cita){
	
	var correo = $('#correo').val();
	var domicilio = $('#domicilio').val();
	var celular = $('#celular').val();
	var nro_historia = $('#nro_historia').val();
	var msgLoader = "";
	if(correo == "")msgLoader = "Procesando, espere un momento por favor";
	else msgLoader = "Enviado correo, espere un momento por favor";

	$('#btnCita').prop("disabled",true);
	
	var heightBrowser = $(window).width()/2;
	
	$('.loader').css("opacity","0.8").css("height",heightBrowser).html("<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>"+msgLoader+"</div></div>");
    $('.loader').show();
	
    var fecha_atencion_original = $('#fecha_atencion').val();
	var id_user = $('#id_user').val();
    $.ajax({
            url: "registrar_gestion_cita",
            type: "POST",
			data : $("#frmCita").serialize()+"&id_medico="+id_medico+"&fecha_cita="+fecha_cita,
            success: function (result) {
					$('#btnCita').prop("disabled",false);
					$('.loader').hide();
					location.href="ver_cita_registrada/"+id_user+"/"+result;
					

            }
    });
}


function obtenerBeneficiario(){
		
	var id_tiporeceta = 1;
	var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
	var dni_beneficiario = $("#dni_beneficiario").val();
	var id_servicio = $("#id_servicio").val();
	var id_establecimiento = $("#id_establecimiento").val();
	var msg = "";
	
	//if(id_tiporeceta == "")msg += "Debe seleccionar el tipo de receta <br>";
	//if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";
	
	if (msg != "") {
		bootbox.alert(msg);
		return false;
	}
	
	$.ajax({
		url: '/buscar_obtener_beneficiario/' + tipodoc_beneficiario + '/' + dni_beneficiario + '/' + id_establecimiento + '/' + id_servicio,
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
				$('#domicilio').val("");
				$('#celular').val("");
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
			$('#nro_historia').val(result.numero_historia);
			$('#domicilio').val(result.domicilio);
			$('#celular').val(result.celular);
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
				if(cita[0].id_estado_cita == 1){//ESTADO PENDIENTE AUN NO HA SIDO ATENDIDO LA CITA
					//bootbox.alert("El beneficiario tiene una cita programada en ese mes, se tiene que elegir otro mes");
					//return false;
					var id_servicio = cita[0].id_servicio;
					//$("#parent2").prop("checked", true);
				}
				if(cita[0].id_estado_cita == 3){//ESTADO DERIVADO
					var id_servicio = cita[0].id_sub_consultorio;
					//$('#nombre_parent').html("DERIVADO");
				}
			}
			
			//modalTurnos();
			modalHistorial();
			$("#divTurno").html("");
			
			$('#calendar').fullCalendar('destroy'); fullCalendar();			
			
			
		}
		
	});
	
}

function anularCita(id_cita){
	
	bootbox.confirm({ 
        size: "small",
        message: "¿Deseas anular la cita?", 
        callback: function(result){
            if (result==true) {
                fn_anularCita(id_cita);
            }
        }
    });
	
}

function fn_anularCita(id_cita){
	
	$.ajax({
		url: '/anular_nro_cita/' + id_cita,
		dataType: "json",
		success: function(result){
			
			if(result.sw == true){
				obtenerBeneficiario();
				$('.nav-tabs a[href="#tab1default"]').tab('show');
			}
			
		}
		
	});
	
}

function obtenerEstablecimiento(nivel){

	var newOption = "";
	newOption = "<option value=''>--Seleccionar--</option>";
	
	//$('#id_user_establecimiento').html(newOption);
	var id_user_establecimiento = $('#id_user_establecimiento').val();
	
	$.ajax({
		url: '/obtener_establecimiento_nivel/'+nivel,
		success: function(result){                    
			var newOption = "";
			newOption = "<option value=''>--Seleccionar--</option>";
			$(result).each(function (ii, oo) {
				var sel="";
				if(id_user_establecimiento == oo.id)sel="selected='selected'";
				newOption += "<option value='"+oo.id+"' "+sel+" >"+oo.nombre+"</option>";
			});                 
			$('#id_establecimiento').html(newOption);
			$("#id_establecimiento").select2({max_selected_options: 4});
			
			getServicio(id_user_establecimiento);
		}
	});
}

function getServicio(id_user_establecimiento){
	option = {
        url: '/mostrar_consultorios/' + id_user_establecimiento,
        type: 'GET',
        dataType: 'json',
        data: {}
    };
    $.ajax(option).done(function (data) {  
        cargarComboDestino($cmbid_servicio, data);
        $cmbid_servicio.val(null).trigger("change");     
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
	
	obtenerBeneficiario();

}

function fullCalendar(){
	
	var id_medico	= $('#id_medico').val();
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();
	$("#divHorario").html("");
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			//right: 'month,agendaWeek,agendaDay'
		},
		editable: false,

	    initialDate: '2020-07-01',
	    initialView: 'agendaWeek',
	    events: [
	      {
	        start: '2020-06-24',
	        end: '2020-06-28',
	        color: '#ff9f89',
	        display: 'background'
	      }
	    ],
		//events: "submit/cronogramaCompraData.php?iIdMovimientoCompra="+iIdMovimientoCompra,
		//events: "cronograma_cita",
	
		//events: "cronograma_gestion_cita/"+id_ipress+"/"+id_servicio,
		eventClick: function(calEvent, jsEvent, view) {
			console.log(calEvent);
			console.log(jsEvent);
			console.log(view);
			var start = calEvent.start._i;
			modalDelegar(start);
		},
		
		selectable: true,
		selectHelper: true,
		select: function(start, end, allDay) {
			dateObj = new Date(start); 
			dateIntNTZ = dateObj.getTime() + dateObj.getTimezoneOffset() * 60 * 1000;
			dateObjNTZ = new Date(dateIntNTZ);
			start = dateObjNTZ.toISOString().slice(0, 10);
			start = $.trim(start);
			var sValidaciones = "";
				modalDelegar(start);
	
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
			url: "mostrar_cita_medico/"+start,
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
	var id_medico	= $('#id_medico').val();	
	var id_turno	= $('#id_turno').val();
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();
			
	$.ajax({
			url: "mostrar_cita_medico/"+id_ipress+"/"+id_servicio+"/"+start+"/"+id_turno+"/"+id_medico,
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
	var id_ipress	= $('#id_establecimiento').val();
	//var start = '2019-10-24';
	var start = "<?php echo $fechaServidor?>";
	
	$("#divTurno").html("");
	$.ajax({
			url: "mostrar_turnos/"+id_ipress+"/"+id_servicio+"/"+start,
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
			url: "mostrar_historial_cita/"+dni_beneficiario,
			type: "GET",
			success: function (result) {  
					$("#divHistorial").html(result);
			}
	});

}

$cmbid_ipress = $("#frmCita").find("#id_establecimiento");
    $cmbid_ipress.select2();

$cmbid_servicio = $("#frmCita").find("#id_servicio");
$cmbid_servicio.select2();

$cmbid_turno = $("#frmCita").find("#id_turno");
$cmbid_turno.select2();

$cmbid_medico = $("#frmCita").find("#id_medico");
$cmbid_medico.select2();

$cmbid_ipress.change(function () {
    $this = $(this);
    cmbid_ipress = $cmbid_ipress.val();
    $cmbid_servicio.html('');
    option = {
        url: '/mostrar_consultorios/' + cmbid_ipress,
        type: 'GET',
        dataType: 'json',
        data: {}
    };
    $.ajax(option).done(function (data) {  
        cargarComboDestino($cmbid_servicio, data);
        $cmbid_servicio.val(null).trigger("change");     
    });
});

function cargarComboDestino($select, data) {
    $select.html('');
    $(data).each(function (ii, oo) {
        $select.append('<option value="' + oo.id + '">' + oo.nombre + '</option>')
    });
}

$cmbid_servicio.change(function () {
    $this = $(this);
    cmbid_ipress = $cmbid_ipress.val();
    cmbid_servicio = $cmbid_servicio.val();
    $cmbid_medico.html('');
    option = {
        url: '/obtener_medico_por_idipress_idconsultorio/' + cmbid_ipress + '/ ' + cmbid_servicio,
        type: 'GET',
        dataType: 'json',
        data: {}
    };
    $.ajax(option).done(function (data) {  
        cargarComboMedico($cmbid_medico, data);
        $cmbid_medico.val(null).trigger("change");     
    });

    $cmbid_turno.html('');    
    $cmbid_turno.append('<option value="null">-- Seleccionar --</option>')
    $cmbid_turno.append('<option value="M"> MAÑANA</option>')
    $cmbid_turno.append('<option value="T"> TARDE </option>')
    $cmbid_turno.val(null).trigger("change");

});

function cargarComboMedico($select, data) {
    $select.html('');
    $(data).each(function (ii, oo) {
        $select.append('<option value="' + oo.id + '">'  + oo.nombre + ' ' + oo.paterno + ' ' + oo.materno + '</option>')
    });
}



</script>
<style>
    .modal-dialog{
        width: 1200px !important;
    }    
    .suggest-element{
        margin-left:5px;
        margin-top:5px;
        width:350px;
        cursor:pointer;
    }
    #suggestions {
        width:350px;
        height:150px;
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
border-top:1px solid rgba(255, 165, 0,0.4);
border-bottom:1px solid rgba(255, 165, 0,0.4);
color:  rgb(225, 75, 0);
}
.alert.alert-success {
background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
border-top:1px solid limegreen;
border-bottom:1px solid limegreen;
}

.form-control{
    height: 28px;
	text-align:left!important;
}

.form-group{
    margin-bottom: 10px;
}

#divHorariox{
	/*height: 200px!important;;*/
	/*background-color: #fed9ff; */
      /*width: 600px; */
	  width: 100%; 
      height: 550px; 
      overflow-x: hidden;
      overflow-y: auto; 
      text-align: center; 
      padding: 0px!important;
}
.loader {
            width: 100%;
            height: 100%;
			/*height: 1500px;*/
            overflow: hidden; 
            top: 0px;
            left: 0px;
            z-index: 10000;
            text-align: center;
            position:absolute; 
            background-color: #000;
            opacity:0.6;
            filter:alpha(opacity=40);
            display:none;
        }
.dataTables_processing {
	position: absolute;
	top: 50%;
	left: 50%;
	width: 500px!important;
	font-size: 1.7em;
	border: 0px;
	margin-left: -17%!important;
	text-align: center;
	background: #3c8dbc;
	color: #FFFFFF;
}

</style>
@endsection
@section('content')

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>
<div class="loader"></div>
	<div class="container" style="width:90%">
    	<div class="row">
        	<div class="col-sm-12 col-md-12">
            	<div class="row">
                	<div class="panel panel-primary filterable">            
                    	<div class="panel-heading">
                        	<h3 class="panel-title">MÓDULO DE GESTIÓN DE CITAS</h3>      
                    	</div>					
                    	<div style="font-size: 11px" class="panel-body" >
							<div class="panel panel-info panel-bordered panel-sm panel-body">
    							<form class="form-horizontal" method="post" action="{{ route('registrar_receta_rua')}}" id="frmCita" autocomplete="off">
		                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
									<input type="hidden" name="id_nivel" id="id_nivel" value="<?php //echo $id_nivel?>">
									<input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
									<input type="hidden" value="{{ Auth::user()->id_establecimiento }}" name="id_user_establecimiento" id="id_user_establecimiento">
									<fieldset>
										<div class="panel panel-info panel-bordered panel-sm">
											<div class="panel-heading">
												<div class="panel-title text-center">
													<b>INFORMACIÓN DE LA PRESCRIPCION Y SERVICIO</b>
												</div>
											</div>
											<div class="panel-body">
												<div class="form-group">							
													<div class="col-sm-3">
														<label class="col-sm-3">ESTABLECIMIENTO:</label><label class="col-sm-8" id="nombre_establecimiento"></label>
														<select class="form-control" name="id_establecimiento" id="id_establecimiento" autofocus="" tabindex="8" style="width: 100%">
																<option value="">--Seleccionar--</option>
														</select>
													</div>
													<div class="col-sm-3">									
														<label class="col-sm-2" style="padding-top:5px">SERVICIO&nbsp;:&nbsp;&nbsp;</label>
														<select class="form-control" name="id_servicio" id="id_servicio" tabindex="8" style="width: 100%"><option value="">--Seleccionar--</option>
														</select>
													</div>
													<div class="col-sm-3">									
														<label class="col-sm-2" style="padding-top:5px">TURNO&nbsp;:&nbsp;&nbsp;</label>
														<select class="form-control" name="id_turno" id="id_turno"  tabindex="7" style="width: 100%">
															<option value="">--Seleccionar--</option>
														</select>
													</div>
													<div class="col-sm-3">									
														<label class="col-sm-2" style="padding-top:5px">MEDICO&nbsp;:&nbsp;&nbsp;</label>
														<select class="form-control" name="id_medico" id="id_medico" tabindex="9" style="width: 100%"><option value="">--Seleccionar--</option>
														</select>
													</div>
									            </div>											
											</div>
										</div>
									</fieldset>
									<fieldset>
										<div class="panel panel-info panel-bordered panel-sm">
											<div class="panel-heading">
												<div class="panel-title text-center">
													<b>INFORMACIÓN DEL PACIENTE</b>
												</div>
											</div>
											<div class="panel-body" id="collapseprestacionespaciente">
												<div class="form-group">
													<div class="col-md-1">
														<label for="tipo">TIPO DOC.</label>									
														<select class="form-control input-sm" id="tipodoc_beneficiario_bus" name="tipodoc_beneficiario_bus" onchange="maxlength_doc_usu()" tabindex="3" >
															<option value="1" selected="">DNI</option>
															<option value="2">CARNET DE EXTRANJERIA</option>
															<option value="3">PASAPORTE</option>
															<option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
														</select>
														<input type="hidden" value="" name="tipodoc_beneficiario" id="tipodoc_beneficiario" class="form-control">
														<input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular" class="form-control">
													</div>
													<div class="col-md-2">
														<label for="dni_beneficiario">NRO DOC.</label>
														<div class="input-group">
															<input onKeyPress="return soloNumeros(event)" id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm text-uppercase" maxlength="8"  onblur="limpiarBeneficiario()" tabindex="4">
															<span class="input-group-btn" style="height:28px!important;">
																<button type="button" id="btnBuscar" onclick="obtenerBeneficiario()" class="btn btn-info" style="height:28px!important;line-height:10px">Buscar</button>
															</span>
														</div>
														<input type="hidden" class="form-control" value="" name="cip_beneficiario" id="cip_beneficiario" readonly=""/>
														<input type="hidden" class="form-control" value="" name="nrodocafiliado" id="nrodocafiliado" readonly=""/>
														<input type="hidden" class="form-control" value="" name="nrodoctitular" id="nrodoctitular" readonly=""/>
													</div>
					                                <div class="col-md-3">
					                                    <label>APELLIDOS Y NOMBRES</label>
														<input readonly="" type="text" class="form-control" value="" name="beneficiario" id="beneficiario" style="text-align:left">
														<input type="hidden" class="form-control" value="" name="nombre_beneficiario" id="nombre_beneficiario"/>
														<input type="hidden" class="form-control" value="" name="paterno_beneficiario" id="paterno_beneficiario"/>
														<input type="hidden" class="form-control" value="" name="materno_beneficiario" id="materno_beneficiario"/>
														<input type="hidden" class="form-control" value="" name="nombre_titular" id="nombre_titular"/>
														<input type="hidden" class="form-control" value="" name="paterno_titular" id="paterno_titular"/>
														<input type="hidden" class="form-control" value="" name="materno_titular" id="materno_titular"/>
														<input type="hidden" class="form-control" value="" name="fecnacafiliado" id="fecnacafiliado"/>
														<input type="hidden" class="form-control" value="" name="nomsexo" id="nomsexo"/>
														<!--input type="hidden" class="form-control" value="" name="nro_historia" id="nro_historia"/-->
					                                </div>
													<div class="col-md-2">
					                                    <label>PARENTESCO &nbsp;</label>
					                                    <input readonly="" value="" type="text" name="tipo_beneficiario" id="tipo_beneficiario" class="form-control" >
					                                </div>												
													<div class="col-md-4">
					                                    <label>GRADO &nbsp;</label>
					                                    <input readonly="" value="" type="text" name="grado" id="grado" class="form-control" >
					                                </div>												
													
					                            </div>
					                            <div class="form-group">
													<div class="col-md-3">
					                                    <label>CORREO &nbsp;</label>
					                                    <input value="" type="text" name="correo" id="correo" class="form-control" >
					                                </div>
													<div class="col-md-2">
					                                    <label>HISTORIA CLINICA</label>
					                                    <input value="" type="text" name="nro_historia" id="nro_historia" class="form-control" >
					                                </div>												
					                                <div class="col-md-2">
					                                    <label>CELULAR &nbsp;</label>
					                                    <input value="" type="text" name="celular" id="celular" class="form-control" >
					                                </div>												
					                                <div class="col-md-5">
					                                    <label>DIRECCION &nbsp;</label>
					                                    <input value="" type="text" name="domicilio" id="domicilio" class="form-control" >
					                                </div>
												</div>
											</div>
										</div>
									</fieldset>
									
								<div style="clear:both"></div>    
							    <div class="row">
							    	<div class="col-md-12">
							            <div class="panel with-nav-tabs panel-default">
							                <div class="panel-heading">
							                    <ul class="nav nav-tabs">
													<?php if($flagemergenciareferencia==1):?>
													<li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">CITAS MEDICAS</a></li>
													<?php endif;?>
													<li><a href="#tab3default" data-toggle="tab" style="font-size:12px">HISTORIAL DE CITA</a></li>
						                        </ul>
							                </div>
							                <div class="panel-body">
							                    <div class="tab-content">
							                        <div class="tab-pane fade" id="tab1default">
														<fieldset>
															<div class="panel panel-info panel-bordered panel-sm">
																<div class="panel-heading">
																	<div class="panel-title text-center">
																		<b>INFORMACIÓN DEL TURNO Y RESPONSABLE DE ATENCION</b>
																	</div>
																</div>
																<div class="panel-body">
																	<div class="form-group">
																		<div id='divTurno'></div>
																	</div>
																</div>
															</div>
														</fieldset>
													</div>													
							                        <div class="tab-pane fade in active" id="tab2default">
														<fieldset>
															<div class="panel panel-info panel-bordered panel-sm">
																<div class="panel-heading">
																	<div class="panel-title text-center">
																		<b>PROGRAMACIONES MEDICAS</b>
																	</div>
																</div>
																<div class="panel-body">
																	<div class="form-group">
																		<div class="col-md-4" style="padding:0px">
																			<div id='calendar'></div>
																		</div>
																		<div class="col-md-8">
																			<div id='divHorario'></div>
																			<div style="clear:both"></div>
																		</div>
																	</div>
																</div>
															</div>
														</fieldset>
													</div>
							                        <div class="tab-pane fade" id="tab3default">
														<fieldset>
															<div class="panel panel-info panel-bordered panel-sm">
																<div class="panel-heading">
																	<div class="panel-title text-center">
																		<b>INFORMACIÓN DE LAS CITAS DEL ASEGURADO</b>
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
							                    </div>
							                </div>
							            </div>
							        </div>
								</div>	
								<!--input class="btn btn-primary pull-right" value="CREAR" name="crea" type="button" form="prestacionescrea" id="btnCita" /-->&nbsp;&nbsp; 
		                    </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
