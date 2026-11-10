@extends('frontend.layouts.master')
@section('renderjs')
<link href='js/fullcalendar-2.3.2/fullcalendar.css' rel='stylesheet' />
<link href='js/fullcalendar-2.3.2/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='js/fullcalendar-2.3.2/lib/moment.min.js'></script>
<script src='js/fullcalendar-2.3.2/fullcalendar.min.js'></script>
<script src='js/fullcalendar-2.3.2/lang/es.js'></script>
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
</style>

<script>
$(document).ready(function() {
    $('.nav-tabs li a').click(function(){
	  setTimeout("$('#calendar').fullCalendar('destroy'); fullCalendar();", 300);
    });
});

function obtenerBeneficiario(){
		
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
			
			if(result.numero_historia!='')
				$('#nro_historia').val(result.numero_historia);
			else
				$('#nro_historia').val(result.beneficiario.nrodocafiliado);

			$('#cip_beneficiario').val(result.beneficiario.cip);
			
			$('#tipodoc_titular').val(result.beneficiario.nomtipdoctitular);
			$('#nrodoctitular').val(result.beneficiario.nrodoctitular);
			$('#nombre_titular').val(result.beneficiario.nomtitular);
			$('#paterno_titular').val(result.beneficiario.apepattitular);
			$('#materno_titular').val(result.beneficiario.apemattitular+" "+result.beneficiario.apecastitular);
			
			var parentesco 			= result.beneficiario.parentesco;
			var ubigeo 				= result.beneficiario.ubigeo;
			var establecimiento 	= result.establecimiento;
			var cita 				= result.cita;
			var id_establecimiento 	= 0;
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
	
	obtenerBeneficiario();

}

function limpiar(){
		$('#dni_beneficiario').val("");
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
                        <h3 class="panel-title">MÓDULO DE ADMISIÓN</h3>      
                    </div>					
                    <div style="font-size: 11px" class="panel-body" >
						<div class="panel panel-info panel-bordered panel-sm panel-body">
							<form class="form-horizontal" method="post" action="{{ route('registrar_receta_rua')}}" id="frmCita" autocomplete="off">
                        	    <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id_nivel" id="id_nivel" value="<?php //echo $id_nivel?>">
								<input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
								<fieldset>
								<div class="panel panel-info panel-bordered panel-sm">
									<div class="panel-heading">
										<div class="panel-title text-center">
											<b>BUSCAR PACIENTE</b>
										</div>
									</div>
									<div class="panel-body" id="collapseprestacionespaciente">
										<div class="form-group">
											<div class="col-md-2">
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
													<input onKeyPress="return soloNumeros(event)" id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm text-uppercase" maxlength="8" autofocus="" onblur="limpiarBeneficiario()" tabindex="4">
													<span class="input-group-btn" style="height:28px!important;">
														<button type="button" id="btnBuscar" onclick="obtenerBeneficiario()" class="btn btn-info" style="height:28px!important;line-height:10px">Buscar</button>
													</span>
												</div>
												<input type="hidden" class="form-control" value="" name="cip_beneficiario" id="cip_beneficiario" readonly=""/>
												<input type="hidden" class="form-control" value="" name="nrodocafiliado" id="nrodocafiliado" readonly=""/>
												<input type="hidden" class="form-control" value="" name="nrodoctitular" id="nrodoctitular" readonly=""/>
											</div>											
							                <div class="col-md-4">
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
							                </div>
											<div class="col-md-2">
							                    <label>PARENTESCO &nbsp;</label>
							                    <input readonly="" value="" type="text" name="tipo_beneficiario" id="tipo_beneficiario" class="form-control" >
							                </div>
											<div class="col-md-2">
							                    <label>GRADO &nbsp;</label>
							                    <input readonly="" value="" type="text" name="grado" id="grado" class="form-control" >
							                </div>
							            </div>
							            <div class="form-group">
							                <div class="col-md-2">
							                    <label>HISTORIA CLINICA &nbsp;</label>
							                    <input readonly="" value="" type="text" name="nro_historia" id="nro_historia" class="form-control" >
							                </div> 
							                <div class="col-md-2">
							                    <label>TELEFONO &nbsp;</label>
							                    <input readonly="" value="" type="text" name="telefono" id="telefono" class="form-control" maxlength="9" >
							                </div>
							                <div class="col-md-4">
							                    <label>DOMICILIO &nbsp;</label>
							                    <input readonly="" value="" type="text" name="domicilio" id="domicilio" class="form-control" >
							                </div>
							                <div class="col-md-4">
							                    <label>CORREO &nbsp;</label>
							                    <div class="input-group">
							                    <input readonly="" value="" type="text" name="correo" id="correo" class="form-control" >
							                    <span class="input-group-btn" style="height:28px!important;">
														<button type="button" id="btnLimpiar" onclick="limpiar()" class="btn btn-info" style="height:28px!important;line-height:10px">Limpiar</button>
													</span>
												</div>
							                </div>
							            </div>
									</div>
								</div>
							</fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('frontend.includes.footer')
@endsection
