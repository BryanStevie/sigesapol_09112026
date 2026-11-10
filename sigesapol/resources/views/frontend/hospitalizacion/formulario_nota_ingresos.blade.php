@extends('frontend.layouts.master')
@section('renderjs')
<script src='../funciones/function.js'></script>
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<style>
  .modal-dialog {
    width: 80%;
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
    /*margin:10px;*/
    text-align: left;
    /*margin-top:20px;*/
    /*background:#5cb85c*/
    background: transparent;
    color: #000000
  }

  .fc-day-grid-event :hover {
    color: #000000
  }

  .fc-content {
    padding: 0px !important;
  }

  .fc-title {
    text-align: left;
    font-size: 10px
  }

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
</style>
<script>
  $(document).ready(function() {

		
    $("#nomsexo").change(function(e) {
      var sexo= $('#nomsexo').val();
      if(sexo=="F"){
        $('#frecuencia_fetal').show();
      }else{
        $('#frecuencia_fetal').hide(); 
      }
    });

    
		$('#fecha_atencion').datepicker();
    
    $('input[name="parent"]').on("change", function (e) {
      getServicio();
    });
    
    $('#btnNota').on("click", function (e) {
      guardarNota();
    });
    
    $('#example tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });
    
      
	
	$('#example tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
		obj.parent().parent().remove();
	});
			
});

function compruebaIMC(){ 
	
    peso = document.frmNotaIngreso.peso.value;
    talla = document.frmNotaIngreso.talla.value;

    if (talla != "") { 
        //PESO POR ALTURA
        var sexo = document.frmNotaIngreso.nomsexo.value; //MASCULINO , FEMENINO
        //edad
        var edad_anos = document.frmNotaIngreso.edadpaciente.value; //12/05/1992
        
        var pesoIdeal=50+((talla-150)/4)*3+(edad_anos-20)/4
        if(sexo=="M") {
              pesoIdeal=pesoIdeal*0.9;
        }
        document.frmNotaIngreso.peso_x_altura.value = pesoIdeal.toFixed(2);
    }else{
       document.frmNotaIngreso.peso_x_altura.value = "";
    }

    if (peso != "" && talla != "") { 
        var talla_en_metros =  talla*0.01;     
        //IMC
        var imcx=  ( peso / (talla_en_metros * talla_en_metros) ).toFixed(2);
        document.frmNotaIngreso.imc.value = imcx;

    } else {
        document.frmNotaIngreso.imc.value = '';
    }
  }



function guardarNota(){
    
	var msgLoader = "";
	msgLoader = "Procesando, espere un momento por favor";
	$('#btnPrestacion').prop("disabled",true);
	var heightBrowser = $(window).width()/2;
	$('.loader').css("opacity","0.8").css("height",heightBrowser).html("<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>"+msgLoader+"</div></div>");
    $('.loader').show();
	
    var msg = "";
    
	var id_tipo_atencion = $('#id_tipo_atencion').val();
	var fecha_atencion = $('#fecha_atencion').val();
	var condicion = $('#condicion').val();
	var estado_general = $('#estado_general').val();
	var hora= $('#hora').val();
  
  if(fecha_atencion=="")msg+="Debe elegir una fecha de la atencion<br>";
  if(hora=="")msg+="Debe ingresar la hora de la evolucion medica<br>";
  if(condicion=="")msg+="Debe registrar la condición del paciente, como se encuentra físicamente<br>";
  if(estado_general=="")msg+="Debe registrar el estado general del paciente.<br>";
	
    if(msg!=""){
        bootbox.alert(msg);
    $('#btnNota').prop("disabled",false);
    $('.loader').hide();
        return false;
    }
    else{
    $("#frmNotaIngreso").submit();
    }
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

<div class="loader"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE NOTAS DE INGRESO HOSPITALIZADOS - {{$establecimiento[0]->nombre}}</h3>
          </div>
          <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{ route('registrar_nota_ingreso_hospitalizado')}}" id="frmNotaIngreso" name="frmNotaIngreso" autocomplete="off">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="id_nivel" id="id_nivel" value="<?php echo $nivel?>">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
              <input type="hidden" name="id_principal" id="id_principal" value="<?php echo $id_principal?>">
              <input type="hidden" name="estado" id="estado" value="<?php echo $estado?>">
              <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="<?php echo $datos_hospitalizacion->id_establecimiento?>">
              <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="<?php echo $id_tipo_atencion?>">
              <input type="hidden" name="id_asegurado" id="id_asegurado"  value="<?php echo $datos_hospitalizacion->id_asegurado?>">
              <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $datos_hospitalizacion->id_servicio?>">
              <input type="hidden" name="id_sub_consultorio" id="id_sub_consultorio" value="<?php echo $datos_hospitalizacion->id_sub_consultorio?>">
              <input type="hidden" name="id_medico" id="id_medico" value="<?php echo $datos_hospitalizacion->id_medico?>">
              
              <input type="hidden" name="edadpaciente" id="edadpaciente" value="<?php echo $datos_hospitalizacion->edadpaciente?>">
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
                      <input readonly="" type="text" class="form-control"
                          value="<?php echo $datos_hospitalizacion->tipo_doc_ident?>" name="tipodoc_beneficiario_bus" id="tipodoc_beneficiario_bus" style="text-align:left">
                      <input type="hidden" value="<?php echo $datos_hospitalizacion->tipo_doc_ident?>" name="tipodoc_beneficiario" id="tipodoc_beneficiario" class="form-control">
                      <input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular" class="form-control">
                    </div>
                    <div class="col-md-4">
                      <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                      <input readonly="" type="text" class="form-control" value="<?php echo $datos_hospitalizacion->nro_doc_ident?>" name="dni_beneficiario" id="dni_beneficiario" style="text-align:left">
                      <input type="hidden" class="form-control" value="" name="beneficiario"  id="beneficiario" style="text-align:left">
                      <input type="hidden" class="form-control" value="" name="cip_beneficiario" id="cip_beneficiario" readonly="" />
                      <input type="hidden" class="form-control" value="<?php echo $datos_hospitalizacion->nro_doc_ident?>" name="nrodocafiliado" id="nrodocafiliado" readonly="" />
                      <input type="hidden" class="form-control" value="" name="nrodoctitular" id="nrodoctitular" readonly="" />
                    </div>
                    <div class="col-md-4">
                      <label for="dni_beneficiario">SEXO</label>
                      <input readonly="" type="text" class="form-control" value="<?php echo $datos_hospitalizacion->sexo?>" name="nomsexo" id="nomsexo" style="text-align:left">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-4">
                      <label>APELLIDO PATERNO</label>
                      <input readonly="" type="text" class="form-control" value="<?php echo $datos_hospitalizacion->asegurado_paterno?>" name="paterno_beneficiario" id="paterno_beneficiario" style="text-align:left">
                    </div>
                    <div class="col-md-4">
                      <label>APELLIDO MATERNO</label>
                      <input readonly="" type="text" class="form-control" value="<?php echo $datos_hospitalizacion->asegurado_materno?>" name="materno_beneficiario" id="materno_beneficiario" style="text-align:left">
                    </div>
                    <div class="col-md-4">
                      <label>NOMBRES</label>
                      <input readonly="" type="text" class="form-control" value="<?php echo $datos_hospitalizacion->asegurado_nombre?>" name="nombre_beneficiario" id="nombre_beneficiario" style="text-align:left">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-4">
                      <label>FECHA DE NACIMIENTO</label>
                      <input readonly="" type="text" class="form-control" value="<?php echo $datos_hospitalizacion->fecha_nac?>" name="fecnacafiliado" id="fecnacafiliado" style="text-align:left">
                    </div>
                    <div class="col-md-4">
                      <label>PARENTESCO &nbsp;</label>
                      <input readonly="" value="<?php echo $datos_hospitalizacion->parentesco?>" type="text" name="tipo_beneficiario" id="tipo_beneficiario" class="form-control">
                    </div>
                    <div class="col-md-4">
                      <label>GRADO &nbsp;</label>
                      <input readonly="" value="<?php //echo $datos_hospitalizacion->grado ?>" type="text" name="grado" id="grado" class="form-control">
                    </div>
                  </div>
                </div>      
              </div>
              <!--empieza dato atencion-->
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>
                  <legend>
                    <b>DATOS DE LA ATENCI&Oacute;N</b>
                  </legend>
                  <div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label>TIPO DE ATENCI&Oacute;N</label>
                        @if($id_tipo_atencion==2)
                        <input readonly="" type="text" class="form-control" value="EMERGENCIA" name="tipo_atencion" id="tipo_atencion" style="text-align:left">
                        @else
                        <input readonly="" type="text" class="form-control" value="HOSPITALIZACION" name="tipo_atencion" id="tipo_atencion" style="text-align:left">
                        @endif
                      </div>
                      <div class="col-md-2">
                        <label>FECHA DE ATENCION</label>
                        <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_atencion" id="fecha_atencion" style="text-align:left" tabindex="1" >
                      </div>
                      <div class="col-md-2">
                        <label>HORA &nbsp;</label>
                        <input type="time" name="hora" id="hora" class="form-control" tabindex="2">
                      </div>
                      <div class="col-md-2">
                        <label>N° HISTORIA CLINICA</label>
                        <input <?php echo ($datos_hospitalizacion->nro_historia!="")?"readonly='readonly'":""?> type="text" class="form-control" value="<?php echo $datos_hospitalizacion->nro_historia?>"  name="nro_historia" id="nro_historia" style="text-align:left">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label for="tipo">NOMBRE DE LA IPRESS</label>
                        <input readonly="" type="text" class="form-control" value="<?php echo $datos_hospitalizacion->establecimiento?>" name="cc" id="cc"  style="text-align:left">
                      </div>
                      <?php 
                        switch ($id_tipo_atencion) {
                          case '1': //AMBULATORIO
                            $descripcion='SERVICIO'; $descripcion2='CONSULTORIO';break;
                          
                          case '2': //EMERGENCIA
                            $descripcion='PRIORIDAD'; $descripcion2='TOPICO';break;

                          case '3': //HOSPITALIZACION
                            $descripcion='SALA'; $descripcion2='SERVICIO';break;  
                        }
                      ?>
                      <div class="col-md-4">
                        <label for="dni_beneficiario"><?php echo $descripcion ?></label>
                        <input readonly="" type="text" class="form-control"  value="<?php echo $datos_hospitalizacion->servicio?>" name="cc" id="cc"  style="text-align:left">
                      </div>
                      <div class="col-md-4">
                        <label><?php echo $descripcion2 ?></label>
                        <input readonly="" type="text" class="form-control"  value="<?php echo $datos_hospitalizacion->consultorio?>" name="beneficiario" id="beneficiario"  style="text-align:left">
                      </div>
                    </div>
                    @if($id_tipo_atencion==3)
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="tipo">PABELLON</label>
                          <input readonly="" type="text" class="form-control"
                            value="<?php echo $datos_hospitalizacion->pabellon?>" name="cc" id="cc"
                            style="text-align:left">
                        </div>
                        <div class="col-md-3">
                          <label for="dni_beneficiario">PISO</label>
                          <input readonly="" type="text" class="form-control"
                            value="<?php echo $datos_hospitalizacion->piso?>" name="cc" id="cc"
                            style="text-align:left">
                        </div>
                        <div class="col-md-3">
                          <label>CUARTO</label>
                          <input readonly="" type="text" class="form-control"
                            value="<?php echo $datos_hospitalizacion->cuarto?>" name="cc" id="cc"
                            style="text-align:left">
                        </div>
                        <div class="col-md-3">
                          <label>CAMA</label>
                          <input readonly="" type="text" class="form-control"
                            value="<?php echo $datos_hospitalizacion->cama?>" name="cc" id="cc"
                            style="text-align:left">
                        </div>
                      </div>
                    @endif
                    
                  </div>
                </fieldset>
              </div>
              <!--fin dato atencion-->
              <!--empieza informacion signos vitales-->
            	<fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DE SIGNOS VITALES</b>
                    </div>
                  </div>
                  <div class="panel-body">

                    <div class="form-group">
                      <div class="col-md-2">
                        <label>TEMPERATURA (°C) &nbsp;</label>
                        <input placeholder="00.00" value="" type="text" name="temperatura" id="temperatura" class="form-control" tabindex="3" onkeypress="return esNumero(event,this.id)" maxlength="5" />
                      </div>
                      <div class="col-md-2">
                        <label>TALLA (cm) &nbsp;</label>
                        <input value="" type="text" tabindex="4" name="talla" id="talla" class="form-control" tabindex="8" onkeyup="compruebaIMC()" onkeypress="return esEntero(event);" maxlength="3" >
                      </div>
                      <div class="col-md-2">
                        <label>PESO (Kg) &nbsp;</label>
                        <input placeholder="00.00" value="" tabindex="5" type="text" name="peso" id="peso" class="form-control" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="6" >
                      </div>
                      <div class="col-md-2">
                        <label>IMC &nbsp;</label>
                        <input value="" type="text" name="imc" id="imc" tabindex="6" class="form-control" readonly="" >
                      </div>
                      <div class="col-md-2">
                        <label>PESO IDEAL &nbsp;</label>
                        <input value="" type="text" name="peso_x_altura" tabindex="7" id="peso_x_altura" class="form-control" readonly="" max="0">
                      </div>
                      <div class="col-md-2">
                        <label>Sa02 (%) &nbsp;</label>
                        <input value="" type="text" name="saturacion" id="saturacion" tabindex="8" class="form-control" onkeypress="return esEntero(event);" maxlength="3" >
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-2">
                        <label>TAMAÑO DE CINTURA (cm)</label>
                        <input value="" type="text" name="tamanio_cintura" id="tamanio_cintura" tabindex="9" class="form-control" onkeypress="return esEntero(event);" maxlength="3" >
                      </div>
                      <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                        <label>FREC. CARDIACA FETAL(/min)</label>
                        <input  value="" type="text" name="frecuencia_cardiaca_fetal" tabindex="10" id="frecuencia_cardiaca_fetal" class="form-control" onkeypress="return esEntero(event);" maxlength="3" >
                      </div>
                      <div class="col-md-2">
                        <label>PRESIÓN ARTERIAL &nbsp;</label>
                        <div class="row">
                          <div class="col-xs-5">                          
                            <input value="" tabindex="11" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" onkeypress="return esEntero(event);" maxlength="3" >
                          </div>  
                            <div class="col-xs-2">
                               <label>/</label> 
                            </div>
                          <div class="col-xs-5">
                            <input value="" type="text" tabindex="12" name="presion_arterial_y" id="presion_arterial_y" class="form-control" onkeypress="return esEntero(event);" maxlength="3" >
                          </div>  
                        </div>
                    </div>
                      <div class="col-md-2">
                        <label>FREC.RESPIRATORIA(/min)</label>
                        <input value="" type="text" name="frecuencia_respiratoria" tabindex="13" id="frecuencia_respiratoria" class="form-control" onkeypress="return esEntero(event);" maxlength="3" >
                      </div>
                      <div class="col-md-2">
                        <label>FREC. CARDIACA (/min) &nbsp;</label>
                        <input value="" type="text" name="frec_cardiaca" id="frec_cardiaca" tabindex="14" class="form-control" onkeypress="return esEntero(event);" maxlength="3" >
                      </div>
                      <div class="col-md-2">
                        <label>Fi02 (%) &nbsp;</label>
                        <input value="" type="text" name="oxigenacion" id="oxigenacion" tabindex="15" class="form-control" onkeypress="return esEntero(event);" maxlength="3" >
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <!--fin informacion signos vitales-->
              <!--empieza evolucion-->
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>NOTA DE INGRESO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>CONDICION</label>
                        <textarea class="form-control" cols="5" id="condicion" tabindex="16" name="condicion" placeholder="Registra la condición del paciente, como se encuentra físicamente"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>ESTADO GENERAL DEL PACIENTE</label>
                        <textarea class="form-control" cols="5" id="estado_general" tabindex="17" name="estado_general" placeholder="Registra el estado general del paciente"></textarea>
                      </div>
                    </div>
                </div>
              </fieldset>
              <!--fin evolucion-->
              <input class="btn btn-primary pull-right" value="GUARDAR" name="crea" tabindex="18" type="button" form="prestacionescrea" id="btnNota" />
            </form>
          </div>
          <!--fin panel body -->
      	</div>
      	<!--fin col-md-12 -->
    	</div>
    	<!--fin row -->
  	</div>
  	<!--fin container -->
	</div>
  <!--fin row -->
</div>
<!--fin container -->


@include('frontend.includes.footer')
@endsection
