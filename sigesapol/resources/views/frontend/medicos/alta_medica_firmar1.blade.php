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

<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<style>
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
  //<![CDATA[
var documentName_ = null;
//
window.addEventListener('getArguments', async function(e) {
  await ObtieneArgumentosParaFirmaDesdeLaWeb();
});

async function getArguments() {
  arg = document.getElementById("argumentos").value;
  await dispatchEventClient('sendArguments', arg);
}

window.addEventListener('invokerOk', function(e) {
  MiFuncionOkWeb();
});

window.addEventListener('invokerCancel', function(e) {
  MiFuncionCancel();
});


async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
  var CSRF_TOKEN = $('meta[name="_token"]').attr('content');

  $.post("{{route('postArgumentsEmergenciaAlta')}}", {
    _token: CSRF_TOKEN,
    type: "W",
    documentName: $('#doc_firmar').val(),
    entidad: <?=Auth::user()->dni?>
  }, function(data, status) {
    document.getElementById("argumentos").value = data;
    getArguments();
  });

}

async function MiFuncionOkWeb() {
  var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  var id_resol = $('#id_emergencia').val();
  $.post("{{route('actualiza_prestacion')}}", {
    _token: CSRF_TOKEN,
    id_tipo: "alta_medica",
    id_resol: id_resol,
  }, function(data, status) {
          //location.reload();
	  location.href = '/all_atenciones_by_emergencia';        
	  });
  }

function MiFuncionCancel() {
  alert("El proceso de firma digital fue cancelado.");
}
//]]>

$(document).ready(function() {

  if ($("#flag_firmar").val() == 0) {
    $("#btnfirmar").hide();
  } else {
    $("#btnfirmar").show();
  }
  
  $(document).on('click', '#btnfirmar', function(e) {
    e.preventDefault();
    firmar();
  })

  $('#fecha_atencion').datepicker();

  $('#btnPrestacion').on("click", function(e) {
    guardarAltaMedica();
  });

  $('#addDiagnostico').on('click', function() {
    AddDiagnostico();
  });

  $('#addDerivarServicio').on('click', function() {
    var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
    $("#divServicio").hide();
    if (addDerivarServicio == "false") {
      $("#divServicio").show();
    }
  });

  $('#addRecetaVale').on('click', function() {
    var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
    $("#fsRecetaVale").hide();
    if (addRecetaVale == "false") {
      $("#fsRecetaVale").show();
    }
  });

  $('#addExamenLab').on('click', function() {
    var addExamenLab = $('#addExamenLab').attr("aria-pressed");
    $("#fsExamenLab").hide();
    if (addExamenLab == "false") {
      $("#fsExamenLab").show();
    }
  });

  $('#addExamenRad').on('click', function() {
    var addExamenRad = $('#addExamenRad').attr("aria-pressed");
    $("#fsExamenRad").hide();
    if (addExamenRad == "false") {
      $("#fsExamenRad").show();
    }
  });

  $('#addAlta').on('click', function() {
    var addAlta = $('#addAlta').attr("aria-pressed");
    $("#divAlta2").hide();
    if (addAlta == "false") {
      $("#divAlta2").show();
    }
  });



  $('#example tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblExamenLab tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  
  $('#tblExamenRad tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $("#id_servicio_derivar").select2({
    width: 'resolve'
  });

  $("#id_farmacia_receta").select2({
    width: 'resolve'
  });

  $("#id_farmacia_receta").val($("#id_farmacia").val()).trigger("change");



});


function guardarAltaMedica() {

  var msg = "";
  
  /*var ind_diag = $('#tblDiagnostico tbody tr').length;
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
  }*/

  var fecha_egreso = $('#fecha_egreso_administrativa').val();
  if(fecha_egreso==""){msg+="Debe ingresar la Fecha de egreso medico<br>";}

  var hora_egreso = $('#hora_egreso_administrativa').val();  
  if(hora_egreso==""){msg+="Debe ingresar la Hora de egreso medico<br>";}


  if (msg != "") {
    bootbox.alert(msg);
    return false;
  } else {   
    //$("#frmAltaMedica").submit();
    var r = confirm("¿Seguro de Concluir el alta medica al Paciente?");
    if (r == true) {
      $('#frmAltaMedica').submit();
    } 
  }
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

  item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';

  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico'+ind+'" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
  newRow +='<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico'+ind+'" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">'+item_tipo_diagnostico+'</select></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblDiagnostico tbody').append(newRow);

  var sexo  = $('#nomsexo').val();
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
        url: '../../obtener_diagnostico_genero/' + request.term + '/'+ genero,
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


function soloNumerosMenosCero(e) {

  var key = window.Event ? e.which : e.keyCode;
  //alert(key);
  return ((key >= 48 && key <= 57) || key == 0 || key == 8)
}

function firmar() {

  //var rowindex = $('#Grd2 tbody tr td').length;
  //$('#btnLiquidacionFirmarPdf').attr("disabled",true);
  //if (rowindex > 0) {

  $.ajax({
      url: "<?php echo url()?>/genera_alta_emergencia_csv",
      type: "post",
      //data: p,
      data: $("#frmBloque").serialize(),
      //timeout: 30000,
      dataType: 'json'
    })
    .done(function(resultado) {
      console.log(resultado);
      if (resultado.sw) {
        var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
        $("#doc_firmar").val(`${resultado.csv}.7z`);
        initInvoker('W');
      } else {
        //$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
        //dialogError(function () {});
      }
      $('#btnLiquidacionFirmarPdf').attr("disabled", false);

    })
    .fail(function(jqXHR, textStatus) {
      //$("#dialogError").html('<p>Error de Coneccion, verifique si tiene Internet</p>');
      //dialogError(function () {});
      console.log("Request failed: " + textStatus)
    })
    .always(function() {
      //bootbox.hideAll();
    });

  //} else {

  //dialogWarning("Debe hacer check en minimo una solicitud");
  //}	


}

function firmarsintokens() {
  var batchSignatureProcessPoller = setInterval(function() {
    $.ajax({
      url: "<?php echo url()?>/sinTokens",
      type: "post",
      cache: false,
      dataType: 'json',
      data: $("#frmBloque").serialize(),
      success: function(completed) {
        if (completed) {
          location.href = '/all_atenciones_by_emergencia';
          clearInterval(batchSignatureProcessPoller);
        }
      }
    });
  }, 3000);
}


function cambiar_tipo_egreso(){
  var id_tipo_egreso=  $("#id_tipo_egreso").val();

  if(id_tipo_egreso=='1'){ //ALTA
    $("#egreso_alta").hide();
    $("#egreso_hospitalizacion").hide();
    $("#egreso_defuncion").hide();
    $("#id_condicion_alta").val('1');
    $("#egreso_traslado").hide();
    cambiar_derivacion();
     

  }else if(id_tipo_egreso=='2'){ //HOSPITALIZACIÓN  
    $("#egreso_alta").hide();
    $("#egreso_hospitalizacion").show();
    $("#egreso_defuncion").hide();
    $("#id_condicion_alta").val('');
    $("#egreso_traslado").hide(2);
    $.getJSON("/listar_consultorios_ajax/3", function(json){  
        document.getElementById("id_servicio_hospitalizacion").innerHTML = "";
        $('#id_servicio_hospitalizacion').append($('<option>').text("Seleccione").attr('value', ""));   
        $('#id_servicio_hospitalizacion').append($('<option>').text("OTRO").attr('value', "OTRO"));  

        $.each(json, function(i, obj){      
           var len= obj.length;
           for(j=0;j<len;j++){
            $('#id_servicio_hospitalizacion').append($('<option>').text( obj[j].nombre).attr('value', obj[j].id));  
           }                        
                                 
        });           
      });
  
  }else if(id_tipo_egreso=='4'){ //DEFUNCION  
    $("#egreso_alta").hide();
    $("#egreso_hospitalizacion").hide();
    $("#egreso_traslado").hide();
    $("#egreso_defuncion").show();

    $("#id_condicion_alta").val('4');

  }else if(id_tipo_egreso=='3'){ //6 TRASLADO
    $("#egreso_alta").hide();
    $("#egreso_hospitalizacion").hide();
    $("#egreso_defuncion").hide();
    $("#egreso_traslado").show();
    $("#id_condicion_alta").val('3');

  }else if(id_tipo_egreso=='2'){ //HOSPITALIZACIÓN  
    $("#egreso_alta").hide();
    $("#egreso_hospitalizacion").show();
    $("#egreso_defuncion").hide();
    $("#id_condicion_alta").val('1');
    $("#egreso_traslado").hide();
    
  
  }
}

function cambiar_derivacion(){
    var derivar= $("#derivar").val();
    if(derivar=='SI'){ //SI
       $.getJSON("/listar_consultorios_ajax/1", function(json){  
        document.getElementById("id_servicio_consulta_externa").innerHTML = "";
        $('#id_servicio_consulta_externa').append($('<option>').text("Seleccione").attr('value', ""));   
        $('#id_servicio_consulta_externa').append($('<option>').text("OTRO").attr('value', "OTRO"));  

        $.each(json, function(i, obj){ 
           //alert(obj[0].nombre);      alert(obj.length);   
           var len= obj.length;
           for(j=0;j<len;j++){
            $('#id_servicio_consulta_externa').append($('<option>').text( obj[j].nombre).attr('value', obj[j].id));  
           }                        
                                 
        });           
      }); 
    }else{
       document.getElementById("id_servicio_consulta_externa").innerHTML = "";
    }

} 



function getSexoGenero(){
  return ($('#nomsexo').val() == 'M') ? 1 : 2;
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

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>

<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE GESTIÓN DE ALTAS MEDICAS - <?php echo $prestacion->establecimiento?></h3>
          </div>

          <div class="panel-body">


            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->


            <form class="form-horizontal" method="post" id="frmBloque"
              action="{{ route('registrar_receta_prestacion')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
              <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacion" id="id_prestacion">
              <input type="hidden" value="<?php echo $prestacion->id_ext ;?>" name="id_emergencia" id="id_emergencia">
              <input type="hidden" value="<?php echo $prestacion->id_cita?>" name="id_cita" id="id_cita">
              <input name="mov[]" id="mov[]" value="<?php echo $prestacion->id_ext ;?>" type="hidden" />
              <input type="hidden" value="<?php echo $prestacion->id_asegurado?>" name="id_asegurado" id="id_asegurado">
              <input type="hidden" value="<?php echo $prestacion->id_establecimiento?>" name="id_establecimiento"
                id="id_establecimiento">
              <input type="hidden" value="<?php echo $prestacion->id_sub_consultorio?>" name="id_sub_consultorio"
                id="id_sub_consultorio">
              <input type="hidden" value="<?php echo $prestacion->id_medico?>" name="id_medico" id="id_medico">
              <input type="hidden" value="<?php echo $id_prestacion?>" name="id_tipo_atencion" id="id_tipo_atencion">
              <input type="hidden" value="<?php echo $prestacion->id_consultorio?>" name="id_servicio" id="id_servicio">
              <input type="hidden" value="<?php echo $prestacion->sexo?>" name="nomsexo" id="nomsexo">
              <input type="hidden" value="<?php echo $nivel?>" name="id_nivel" id="id_nivel">¨
              <input name="id_tipo_egreso" id="id_tipo_egreso" value="<?php echo $emergencia->id_tipo_egres_med ;?>" type="hidden" />
              <input name="id_condicion_egreso" id="id_condicion_egreso" value="<?php echo $emergencia->id_condi_egres_med ;?>" type="hidden" />
                 

              <?php
              $flag_firmar = 0;
              if($flag_pendinte_firma == 1) {
                $flag_firmar = 1;
              }
            ?>

              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>

                  <legend>
                    <b>DATOS DE LA ATENCI&Oacute;N</b>
                  </legend>

                  <div>
                    <div class="form-group">
                      <div class="col-md-2">
                        <label >CÓD. SITEDS SUSALUD</label>
                        <p>{{$prestacion->codigo_siteds}}</p>
                      </div>                      
                    </div>

                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                        <p><?php echo $prestacion->tipo_atencion?></p>
                      </div>

                      <div class="col-md-2">
                        <label for="dni_beneficiario">FECHA DE ATENCION</label>
                        <p><?php echo $prestacion->fecha_atencion?></p>
                      </div>

                      <div class="col-md-2">
                        <label>N° HISTORIA CLINICA</label>
                        <p><?php echo $prestacion->nro_historia?></p>
                      </div>
                     
                      <div class="col-md-3">
                        <label for="nombre_ipres">PRIORIDAD</label>
                        <p>{{$prestacion->servicio}}</p>
                      </div>
                      <div class="col-md-3">
                        <label for="nombre_ipres">AREA</label>
                        <p>{{$prestacion->consultorio}}</p>
                      </div>

                    </div>
                    @if($sw==1)
                      <div class="form-group">
                        <div class="col-md-2">
                          <label>CODIGO UPSS</label>
                          <p>{{$prestacion->codigo_upss}}</p>
                        </div>
                        <div class="col-md-2">
                          <label for="tipo">PABELLON</label>
                          <p>{{$datoadicional->pabellon}}</p>
                        </div>
                        <div class="col-md-2">
                          <label for="dni_beneficiario">PISO</label>
                          <p>{{$datoadicional->piso}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>HABITACION</label>
                          <p>{{$datoadicional->cuarto}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>CAMA/CAMILLA</label>
                          <p>{{$datoadicional->cama}}</p>
                        </div>
                      </div>
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
                        <p><?php echo $prestacion->tipo_doc_ident?></p>
                      </div>

                      <div class="col-md-4">
                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->nro_doc_ident?></p>
                      </div>

                      <div class="col-md-4">
                        <label for="dni_beneficiario">SEXO</label>
                        <p><?php echo $prestacion->sexo?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $prestacion->asegurado_paterno?></p>
                      </div>

                      <div class="col-md-4">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $prestacion->asegurado_materno?></p>
                      </div>

                      <div class="col-md-4">
                        <label>NOMBRES</label>
                        <p><?php echo $prestacion->asegurado_nombre?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label>FECHA DE NACIMIENTO</label>
                        <p><?php echo $prestacion->fecha_nac?></p>
                      </div>
                      <div class="col-md-4">
                        <label>PARENTESCO &nbsp;</label>
                        <p><?php echo $prestacion->parentesco?></p>
                      </div>

                      <div class="col-md-4">
                        <label>GRADO &nbsp;</label>
                        <p><?php echo $prestacion->grado?></p>
                      </div>
                    </div>

                  </div>
                </div>

              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL RESPONSABLE DEL ALTA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-3">
                        <label for="tipo">DOCUMENTO DE IDENTIDAD</label>
                        <p>DNI: {{$medico[0]->dni}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO PATERNO</label>
                        <p>{{$medico[0]->paterno}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO MATERNO</label>
                        <p>{{$medico[0]->materno}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>NOMBRES</label>
                        <p>{{$medico[0]->nombre}}</p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-3">
                        <label>ESPECIALIDAD</label>
                        <p>{{$medico[0]->especializacion}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>PROFESION</label>
                        <p>{{$medico[0]->profesion}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>N° COLEGIATURA</label>
                        <p>{{$medico[0]->colegiatura}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>N° RNE</label>
                        <p>{{$medico[0]->rne}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>

             <!--
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm panel-body">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>CONDICION</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <div class="col-md-4">
                        <label for="tipo">IPRESS</label>
                        <div class="col-md-10">
                          <p><?php echo $prestacion->ipress?></p>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label for="tipo">UPSS</label>
                        <div class="col-md-10">
                          <p><?php echo $prestacion->upss?></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      @if($prestacion->id_tipo_atencion>1)
                      <div class="col-md-4">
                        <br /><br />
                        <label for="tipo">FECHA INGRESO</label>
                        <div class="col-md-10">
                          <p><?php //echo $prestacion->fecha_ingresos?></p>
                        </div>
                      </div>
                      @if($prestacion->id_tipo_atencion<4) @if($prestacion->fecha_alta!="")
                        <div class="col-md-4">
                          <br /><br />
                          <label for="tipo">FECHA ALTA</label>
                          <div class="col-md-10">
                            <p><?php echo $prestacion->fecha_alta?></p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <br /><br />
                          <label for="tipo">MOTIVO ALTA</label>
                          <div class="col-md-10">
                            <p>
                              <?php
															switch ($prestacion->id_motivo_alta) {
																case '1': echo "Mejorado"; break;
																case '2': echo "Curado"; break;
																case '3': echo "Transferido"; break;
																case '4': echo "Voluntario"; break;
																default : echo ""; break;
															}								
														?>
                            </p>
                          </div>
                        </div>
                        @endif
                        @endif
                        @endif
                    </div>
                  </div>
                </div>
              </fieldset>
			  -->

              <fieldset>

                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DIAGNOSTICOS DE ATENCION</b>
                    </div>
                  </div>
                  <div class="panel-body">

                    <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="10%">Codigo</th>
                          <th width="65%">Nombre</th>
                          <th width="10%">Tipo</th>
                          <th width="10%">Clasificación</th>
                          
                        </tr>
                      </thead>
                      <tfoot>
                      </tfoot>
                      <tbody>
                        <?php foreach($diagnostico as $row):
              						$tipo_diagnostico = "";
              						if($row->id_tipo_diagnostico==1)$tipo_diagnostico = "PRESUNTIVO";
              						if($row->id_tipo_diagnostico==2)$tipo_diagnostico = "DEFINITIVO";
              						if($row->id_tipo_diagnostico==3)$tipo_diagnostico = "REITERATIVO";

                          $clase_diagnostico="";
                          if($row->id_clasificacion_diagnosticos==1){
                            $clase_diagnostico = "Atencion";
                          }else if($row->id_clasificacion_diagnosticos==2){
                            $clase_diagnostico = "Ingreso";
                          }else if($row->id_clasificacion_diagnosticos==3){
                            $clase_diagnostico = "Egreso";
                          }else if($row->id_clasificacion_diagnosticos==4){
                            $clase_diagnostico = "Mortalidad";
                          }else{
                            $clase_diagnostico = "Atencion";
                          }

              					?>
                        <tr>
                          <td><?php echo $row->codigo?></td>
                          <td><?php echo $row->nombre?></td>
                          <td><?php echo $tipo_diagnostico?></td>
                          <td><?php echo $clase_diagnostico?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>

                  </div>

                </div>

              </fieldset>            
              <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DIAGNOSTICOS DE EGRESO</b>
                    </div>
                  </div>
                  <div class="panel-body">

                    <table class="display" id="" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="15%">Codigo</th>
                          <th width="50%">Nombre</th>
                          <th width="10%">Tipo</th>
                          <th width="10%"></th>
                          
                        </tr>
                      </thead>
                      <tfoot>
                      </tfoot>
                      <tbody>
                        <?php foreach($diagnosti as $row):?>
                       <tr>
                          <td><?php echo $row->codigo?></td>
                          <td><?php echo $row->nombre?></td>
                          <td><?php echo $row->tipo?></td>
                          <td><?php echo $row->cabecera?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>

                    </table>

                  </div>

                </div>
		</form>
		
		 <form class="form-horizontal" method="post" id="frmAltaMedica"
              action="">
         <!-- ALTA MEDICA --> 

          <fieldset id="fsAlta">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
          	<div class="panel panel-info panel-bordered panel-sm">
            
             

          		<div class="panel-heading">
          			<div class="panel-title text-center">
          				<b>ALTA DE PACIENTE</b>
          			</div>
          		</div>
          		<div class="panel-body">
      					<input type="hidden" value="<?php echo $prestacion->id_medico?>" name="id_medico_egreso" id="id_medico_egreso">
                 <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacionx" id="id_prestacionx">
      					 <input type="hidden" value="<?php echo $prestacion->id_ext ;?>" name="id_emergencia" id="id_emergencia">
                 <input name="mov[]" id="mov[]" value="<?php echo $prestacion->id_ext ;?>" type="hidden" />
                 <input name="id_tipo_egreso" id="id_tipo_egreso" value="<?php echo $emergencia->id_tipo_egres_med ;?>" type="hidden" />
              <input name="id_condicion_egreso" id="id_condicion_egreso" value="<?php echo $emergencia->id_condi_egres_med ;?>" type="hidden" />
                 
          			<div style="clear:both"></div>
          			<fieldset id="divAlta2" >
          			  <div class="form-group"> 
                  <div class="col-sm-12" style="margin-left:0px;padding-left:0px">
                  <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
          					<label>FECHA INGRESO</label>
          				</div>
          				<div class="col-sm-2" style="margin-left:0px;padding-left:0px">
          					<input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($emergencia->fecha_registro_ingreso)); ?>" name="fecha_egreso" id="fecha_egreso" style="text-align:left" <?php if($emergencia->fecha_registro_ingreso!=''): echo 'disabled'; endif; ?> />
          				</div>
          				<div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
          					<label>HORA INGRESO</label>
          				</div> 
                  <div class="col-sm-1" style="margin-left:0px;padding-left:0px">
          				<input type="text" class="form-control" value="<?php echo date("H:i:s", strtotime($emergencia->hora_registro_ingreso)); ?>" name="hora_egreso" id="hora_egreso" style="text-align:left" <?php if($emergencia->hora_registro_ingreso!=''): echo 'disabled'; endif; ?> />
          			
          				</div>
          				<div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
          					<label>FECHA EGRESO MEDICO</label>
          				</div>
          				<div class="col-sm-2" style="margin-left:0px;padding-left:0px">
          					<input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($emergencia->fecha_alta_medica)); ?>" name="fecha_egreso" id="fecha_egreso" style="text-align:left" <?php if($emergencia->fecha_alta_medica!=''): echo 'disabled'; endif; ?> />
          				</div>
          				<div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
          					<label>HORA EGRESO MEDICO</label>
          				</div>
          				<div class="col-sm-1" style="margin-left:0px;padding-left:0px">
          				<input type="text" class="form-control" value="<?php echo date("H:i:s", strtotime($emergencia->hora_alta_medica)); ?>" name="hora_egreso" id="hora_egreso" style="text-align:left" <?php if($emergencia->hora_alta_medica!=''): echo 'disabled'; endif; ?> />
          			
          				</div>
                  </div>
                  <div class="col-sm-12" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                  <label>DETALLES DE LA ALTA</label><br>
          				<div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
          					<label>TIPO EGRESO</label>
                  
          				</div>			  
          				<div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                  <?php 
                   if($emergencia->id_tipo_egres_med=="1"){
                    echo "ALTA";
                   }else if($emergencia->id_tipo_egres_med=="2"){
                    echo "HOSPITALIZACION";
                   }else if($emergencia->id_tipo_egres_med=="3"){
                    echo "TRASLADO";
                   }else if($emergencia->id_tipo_egres_med=="4"){
                    echo "FALLECIDO";
                   }else if($emergencia->id_tipo_egres_med=="7"){
                    echo "RETIRO VOLUNTARIO";
                   }else if($emergencia->id_tipo_egres_med=="6"){
                    echo "FUGA";
                   }
                   
                   ?>
          				</div>
                  
                  <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                    <label>CONDICION ALTA</label>
                  </div>
                  <div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                  <?php 
                   if($emergencia->id_condi_egres_med=="1"){
                    echo "MEJORADO";
                   }else if($emergencia->id_condi_egres_med=="2"){
                    echo "CURADO";
                   }else if($emergencia->id_condi_egres_med=="3"){
                    echo "TRANSFERIDO";
                   }else if($emergencia->id_condi_egres_med=="4"){
                    echo "RETIRO VOLUNTARIO";
                   }else if($emergencia->id_condi_egres_med=="5"){
                    echo "FUGADO";
                   }else if($emergencia->id_condi_egres_med=="6"){
                    echo "FALLECIDO";
                   }
                   
                   ?>
          			  </div>
                  </div>
                  <div class="col-sm-8" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                  <br>
                  <label>
                  <?php 
                   if($emergencia->id_tipo_egres_med=="1"){
                    
                   }else if($emergencia->id_tipo_egres_med=="2"){
                    echo "SE  HOSPITALIZO  EL SIGUIENTE SERVICIO DE: " ;
                    echo $upss->nombre_consultorio;
                    echo ", CON EL CODIGO UPSS: ";
                    echo $upss->codigo_upss;
                   }else if($emergencia->id_tipo_egres_med=="3"){
                    
                    echo "SE HA TRANSFERIDO  A LA SIGUIENTE IPRESS: ";
                    echo $estable->nombre ."<br>". "LA OBSERVACIONE DEl TRASLADO:  ";
                    echo $emergencia->obs_traslado ."<br>". "ESPECIALIDA DE TRASLADO: ";
                    echo $emergencia->especialida_destino ."<br>"." DETALLE:  ";
                    echo $emergencia->especialidad_destino_otro ."<br>". "CONDICION DE TRASLADO: ";
                    echo $emergencia->condicion_traslado  ."<br>".  "ACOMPAÑANTE:  ";
                    echo $emergencia->personal_acompañante;
                   }else if($emergencia->id_tipo_egres_med=="4"){
                    echo "SE  REALIZO NECROPCIA " ;
                    echo $emergencia->necropsia;
                    echo ", LA CAUSAS DE LA MUERTE FUE  ";
                    echo $emergencia->causas_muerte;
                    echo "<br> FECHA Y HORA DE LA DEFUNCION " ;
                    echo $emergencia->fecha_defuncion; echo " / " ;
                    echo $emergencia->hora_defuncion;
                   }else if($emergencia->id_tipo_egres_med=="7"){
                    echo "OBSERVACION DEL RETIRO VOLUNTARIO: " ;
                    echo $emergencia->detalle_alta_admin;
                   }else if($emergencia->id_tipo_egres_med=="6"){
                    echo "DETALLES DE LA FUGA: " ;
                    echo $emergencia->detalle_alta_admin;
                   }
                   ?>
                   </label>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                      <label>REQUIERE INTERNAMIENTO QX</label>
                    </div>
                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-left:0px">
                      @if($existe_intervencion) SI @else NO @endif
                    </div>
                  </div>
                   @if($existe_intervencion)
                    <div class="panel panel-info panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title text-center"><b>INFORMACIÓN QUIRÚRGICA</b></div>
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label><b>TIPO DE CIRUGIA :</b>  {{isset($datos_intervencion->tipo_cirugia)?$datos_intervencion->tipo_cirugia:''}}   </label>                                   
                            </div>
                            <div class="col-md-2">
                                <label><b>TIEMPO DE DURACION:</b> {{ isset($datos_intervencion->emergencia_duracion_cirugia) ? rtrim(rtrim(number_format($datos_intervencion->emergencia_duracion_cirugia, 2, '.', ''), '0'), '.') . ' horas' : '' }}</label>                                       
                            </div>
                        </div>
                      </div>

                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>DIAGNOSTICO QUIRURGICO</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <table style="width: 100%;" id="tblDiagnosticoDetalle" class="tbl_diagnostico">
                                <tr>
                                    <th width="20%">Codigo</th>
                                    <th width="60%">Nombre</th>
                                    <th width="10%">Tipo</th>
                                </tr>
                                <?php $i = 1;?>
                              @foreach($diagnosticos_cqx as $key=> $row)
                                <tr>
                                    <?php $tipo = "";
                                    switch($row->id_tipo_diagnostico): 
                                        case 1: $tipo = "PRESUNTIVO"; break;
                                        case 2: $tipo = "DEFINITIVO"; break;
                                        case 3: $tipo = "REITERATIVO"; break;
                                    endswitch;
                                    ?>
                                  
                                    <td style="width:20%; 1px solid gray;">
                                    {{ $row->codigo_diagnostico }}
                                    </td>
                                    <td style="width:60%; 1px solid gray;"><p>{{ $row->descripcion_diagnostico }}</p></td>
                                    <td style="width:10%; 1px solid gray;">{{ $tipo }}</td>
                                    <?php $i++; ?>
                                </tr>
                              @endforeach
                          </table>
                        </div>
                      </div>

                      <div class="panel panel-info panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center"><b>OPERACION , PROCEDIMIENTO A REALIZAR</b></div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group" style="display:none">
                            <select id="cmbProcedimientoIntervencionTemp"></select>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-12">
                              <table class="table table-bordered" cellspacing="0" width="100%">
                                  <thead>
                                    <tr>
                                      <th width="15%">Codigo</th>
                                      <th width="50%">DESCRIPCION</th>
                                    </tr>
                                  </thead>
                                  <tfoot>
                                  </tfoot>
                                  <tbody>
                                    @foreach ($procedimientos_cqx as $pintervencion)
                                    <tr>
                                      <td>{{$pintervencion->codigo_cpms}}</td>
                                      <td>{{$pintervencion->descripcion_cpms}}</td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="panel panel-info panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center"><b>CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN</b></div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-sm-12">
                                <table class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Apellidos y Nombres</th>
                                            <th style="width:30%">Cargo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($profesionales_cqx as $pq)
                                        <tr>
                                            <td>{{ $pq->paterno }} {{ $pq->materno }}, {{ $pq->nombre }}</td>
                                            <td>{{ $pq->tipo_profesional }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                  </table>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  @endif

                  <div class="col-md-12" id="numeroboleta"   >  
                  <br>       
                    <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                        <label>NUMERO DE BOLETA</label>
                      </div>
                      <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                        <input type="text" class="form-control" value="<?php if($emergencia->numero_boleta!='0'): echo $emergencia->numero_boleta; endif; ?>" name="numeroboleta" id="" style="text-align:left" <?php if($emergencia->id_tipo_beneficiario!='12'): echo 'disabled'; endif; ?><?php if($emergencia->numero_documento_coronel_guardia!='0'): echo 'disabled'; endif; ?>>
                      </div>
                      <div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                      <label>NUMERO DE OFICIAL DE GUARDIA</label>
                    </div>
                    <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                       <input type="text" class="form-control" value="<?php if($emergencia->numero_documento_coronel_guardia!='0'): echo $emergencia->numero_documento_coronel_guardia; endif; ?>" name="numeroguar" id="numeroguar" style="text-align:left"  <?php if($emergencia->id_tipo_beneficiario!='12'): echo 'disabled'; endif; ?><?php if($emergencia->numero_boleta!='0'): echo 'disabled'; endif; ?>>
                    </div>
                     
                    </div>
          			</fieldset>
          		
          	</div>
          </fieldset>
        
                <div id="divFirmar">
                    <!--input type="submit" class="btn btn-info input-sm pull-right" value="Firmar" name="crea" form="prestacionescrea" id="btnPrestacion" style="margin-left:20px"  /-->
                    <a class="btn btn-info btn-sm hidden-print pull-right" id="btnfirmar" name="btnfirmar"> <span
                    class="fa fa-pencil"></span> FIRMAR</a>
                </div>
              
                <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_atenciones_by_emergencia"> <span class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
              
              <?php //endif;?>
       

            </form>

          </div>


        </div>
      </div>
    </div>
  </div>
</div>
</div>

<input type="hidden" id="argumentos" value="" />
<input type="hidden" id="doc_firmar" value="" />
<input type="hidden" value="<?php echo $flag_firmar?>" name="flag_firmar" id="flag_firmar">
<div id="addComponent"></div>




@include('frontend.includes.footer')
@endsection
