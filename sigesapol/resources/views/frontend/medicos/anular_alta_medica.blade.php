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

</style>

<script>
$(document).ready(function() {

  $('#fecha_atencion').datepicker();

});


function guardarAltaMedica() {

  var msg = "";
  
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
            <h3 class="panel-title">MÓDULO DE GESTIÓN DE ALTAS MEDICAS  - <?php echo $prestacion->establecimiento?> </h3>
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
              <input type="hidden" value="<?php echo $prestacion->id_asegurado?>" name="id_asegurado" id="id_asegurado">
              <input type="hidden" value="<?php echo $prestacion->id_establecimiento?>" name="id_establecimiento"
                id="id_establecimiento">
              <input type="hidden" value="<?php echo $prestacion->id_sub_consultorio?>" name="id_sub_consultorio"
                id="id_sub_consultorio">
              <input type="hidden" value="<?php echo $prestacion->id_medico?>" name="id_medico" id="id_medico">
              <input type="hidden" value="<?php echo $id_prestacion?>" name="id_tipo_atencion" id="id_tipo_atencion">
              <input type="hidden" value="<?php echo $prestacion->id_consultorio?>" name="id_servicio" id="id_servicio">
              <input type="hidden" value="<?php echo $prestacion->sexo?>" name="nomsexo" id="nomsexo">
              <input type="hidden" value="<?php echo $nivel?>" name="id_nivel" id="id_nivel">
              <input name="mov[]" id="mov[]" value="<?php echo $prestacion->id_ext ;?>" type="hidden" />
              
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>

                  <legend>
                    <b>DATOS DE LA ATENCI&Oacute;N</b>
                  </legend>

                  <div>

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
                        <p>{{$prestacion->nro_historia}}</p>
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

                    
                </fieldset>
              </div>
              <fieldset>
                <br/>
              @if($emergencia->estado==5 && $emergencia->id_anula_alta == null) 
              <div class="col-md-12">
                  <a data-toggle="modal" data-target="#squarespaceModal" class="btn btn-danger">
                      <i class="fa fa-xing"></i>Anular Alta Médica
                  </a>                          
              </div>
              @else
              <div class="col-md-12">
                  <button class="btn btn-danger" disabled="">
                      Registro de Alta Médica Invalidada
                  </button>
              </div>
              <div class="col-md-8" style="border: 1px solid red; margin-top: 10px; background-color: #FFDA6A">
                  Motivo: {{$alta_anulada->motivo_anulacion}}<br>
                  <?php
                  $originalDate8 = $alta_anulada->fecha_anulacion;
                  $fecha_anulacion = date("d-m-Y", strtotime($originalDate8));
                  
                  ?>
                  Fecha de Anulaci&oacute;n: <?php echo $fecha_anulacion ?><br>
                  <div class="col-md-12">
                  Usuario Invalidador:  {{$usuario_anulador[0]->nombre}}  {{$usuario_anulador[0]->paterno}}  {{$usuario_anulador[0]->materno}}
                  <div>
                <div class="col-md-12">
                  @if($emergencia->estado==5)
                  <div class="col-md-12">
                  Se Modificaron los siguientes datos :
                    </div>
                  <div class="col-md-4">Dato </div>
                  <div class="col-md-4">Antes</div>
                  <div class="col-md-4">despues</div><br>

                        @if($datosanulados->id_diag_cab  != $datosposanulados->id_diag_cab)
                        <br>  <div class="col-md-4">Diagnostico Cabecera </div>
                        <div class="col-md-4">{{$datosanulados->id_diag_cab}} </div>
                        <div class="col-md-4"> {{$datosposanulados->id_diag_cab}}</div><br>
                        @endif
                        @if($datosanulados->id_diag_cuer1  != $datosposanulados->id_diag_cuer1)
                        <br> <div class="col-md-4">Diagnostico Cuerpo1</div>
                        <div class="col-md-4"> {{$datosanulados->id_diag_cuer1}} </div>
                        <div class="col-md-4"> {{$datosposanulados->id_diag_cuer1}}</div><br>
                          @endif
                        @if($datosanulados->id_diag_cuer2  != $datosposanulados->id_diag_cuer2)
                        <br>  <div class="col-md-4">Diagnostico Cuerpo2</div>
                        <div class="col-md-4"> {{$datosanulados->id_diag_cuer2}} </div>
                        <div class="col-md-4"> {{$datosposanulados->id_diag_cuer2}}</div><br>
                          @endif
                        @if($datosanulados->fecha_alta_medica  != $datosposanulados->fecha_alta_medica)
                        <br>   <div class="col-md-4">Fecha Alta</div>
                        <div class="col-md-4"> {{$datosanulados->fecha_alta_medica}} </div>
                        <div class="col-md-4"> {{$datosposanulados->fecha_alta_medica}}</div><br>
                          @endif
                        @if($datosanulados->hora_alta_medica  != $datosposanulados->hora_alta_medica)
                        <br>   <div class="col-md-4">Hora del Alta</div>
                        <div class="col-md-4"> {{$datosanulados->hora_alta_medica}} </div>
                        <div class="col-md-4"> {{$datosposanulados->hora_alta_medica}}</div><br>
                          @endif
                        @if($datosanulados->id_tipo_egres_med  != $datosposanulados->id_tipo_egres_med)
                        <br>   <div class="col-md-4">Tipo de Egreso Cabecera</div>
                        <div class="col-md-4"> {{$datosanulados->id_tipo_egres_med}} </div>
                        <div class="col-md-4"> {{$datosposanulados->id_tipo_egres_med}}</div><br>
                          @endif
                        @if($datosanulados->id_condi_egres_med  != $datosposanulados->id_condi_egres_med)
                        <br>   <div class="col-md-4"> Condicion de Egreso</div>
                        <div class="col-md-4"> {{$datosanulados->id_condi_egres_med}} </div>
                        <div class="col-md-4"> {{$datosposanulados->id_condi_egres_med}} </div><br>
                          @endif
                        @if($datosanulados->id_establecimiento_traslado  != $datosposanulados->id_establecimiento_traslado)
                        <br>   <div class="col-md-4">Establecimientode traslado </div>
                        <div class="col-md-4"> {{$datosanulados->id_establecimiento_traslado}} </div>
                        <div class="col-md-4"> {{$datosposanulados->id_establecimiento_traslado}}</div><br>
                          @endif
                        @if($datosanulados->id_upss_alta  != $datosposanulados->id_upss_alta)
                        <br>   <div class="col-md-4">UPSS de Alta</div>
                        <div class="col-md-4"> {{$datosanulados->id_upss_alta}} </div>
                        <div class="col-md-4"> {{$datosposanulados->id_upss_alta}}</div><br>
                          @endif
                        @if($datosanulados->detalle_alta_admin  != $datosposanulados->detalle_alta_admin)
                        <br>    <div class="col-md-4">Detalle del Alta</div>
                        <div class="col-md-4"> {{$datosanulados->detalle_alta_admin}} </div>
                        <div class="col-md-4"> {{$datosposanulados->detalle_alta_admin}}</div><br>
                          @endif
                        @if($datosanulados->especialida_destino  != $datosposanulados->especialida_destino)
                        <br>   <div class="col-md-4">Especialidad Destino</div>
                       <div class="col-md-4"> {{$datosanulados->especialida_destino}} </div>
                       <div class="col-md-4"> {{$datosposanulados->especialida_destino}}</div><br>
                          @endif
                        @if($datosanulados->condicion_traslado  != $datosposanulados->condicion_traslado)
                        <br>     <div class="col-md-4">Condicion de Traslado</div>
                        <div class="col-md-4"> {{$datosanulados->condicion_traslado}} </div>
                        <div class="col-md-4"> {{$datosposanulados->condicion_traslado}}</div><br>
                          @endif
                        @if($datosanulados->id_servicio_destino  != $datosposanulados->id_servicio_destino)
                        <br>     <div class="col-md-4">Servicio de Destino </div>
                        <div class="col-md-4"> {{$datosanulados->id_servicio_destino}} </div>
                        <div class="col-md-4"> {{$datosposanulados->id_servicio_destino}}</div><br>
                          @endif

                  @else
                  <div class="col-md-12">Debe darse de Alta para comparar los cambios hechos </div>
                  @endif
                  </div>
              </div>

              @endif
              
            </fieldset>
              <fieldset>
                <br/>
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
                          <th width="15%">Codigo</th>
                          <th width="50%">Nombre</th>
                          <th width="15%">Tipo</th>
                          <th width="15%">Clasificación</th>
                          
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
          					<input type="text" class="form-control datepicker" value="<?php if ($emergencia->estado==5): echo date("d-m-Y", strtotime($emergencia->fecha_alta_medica)); else: echo date("d-m-Y", strtotime($alta_anulada->fecha_alta_medica)); endif; ?>" name="fecha_egreso" id="fecha_egreso" style="text-align:left" disabled />
          				</div>
          				<div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
          					<label>HORA EGRESO MEDICO</label>
          				</div>
          				<div class="col-sm-1" style="margin-left:0px;padding-left:0px">
          				<input type="text" class="form-control" value="<?php if ($emergencia->estado==5): echo date("H:i:s", strtotime($emergencia->hora_alta_medica)); else: echo date("H:i:s", strtotime($alta_anulada->hora_alta_medica)); endif; ?>" name="hora_egreso" id="hora_egreso" style="text-align:left" disabled />
          			
          				</div>
                  </div>
                  <div class="col-sm-12" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                  <label>DETALLES DE LA ALTA</label><br>
          				<div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
          					<label>TIPO EGRESO</label>
                  
          				</div>			  
          				<div class="col-sm-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                  <?php 
                  if ($emergencia->estado==5):
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
                  endif;
                  
                  
                  if ($emergencia->estado==3):
                    if($alta_anulada->id_tipo_egres_med=="1"){
                      echo "ALTA";
                     }else if($alta_anulada->id_tipo_egres_med=="2"){
                      echo "HOSPITALIZACION";
                     }else if($alta_anulada->id_tipo_egres_med=="3"){
                      echo "TRASLADO";
                     }else if($alta_anulada->id_tipo_egres_med=="4"){
                      echo "FALLECIDO";
                     }else if($alta_anulada->id_tipo_egres_med=="7"){
                      echo "RETIRO VOLUNTARIO";
                     }else if($alta_anulada->id_tipo_egres_med=="6"){
                      echo "FUGA";
                     }
                  endif; 
                   ?>
          				</div>
                  
                  <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                    <label>CONDICION ALTA</label>
                  </div>
                  <div class="col-sm-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                  <?php 
                    if ($emergencia->estado==5):
                      if($emergencia->id_condi_egres_med=="1"){
                        echo "MEJORADO";
                       }else if($emergencia->id_condi_egres_med=="2"){
                        echo "CURADO";
                       }else if($emergencia->id_condi_egres_med=="3"){
                        echo "TRASLADO";
                       }else if($emergencia->id_condi_egres_med=="4"){
                        echo "RETIRO VOLUNTARIO";
                       }else if($emergencia->id_condi_egres_med=="5"){
                        echo "FUGADO";
                       }else if($emergencia->id_condi_egres_med=="6"){
                        echo "FALLECIDO";
                       }
                    endif;
                    if ($emergencia->estado==3):
                      if($alta_anulada->id_condi_egres_med=="1"){
                        echo "MEJORADO";
                       }else if($alta_anulada->id_condi_egres_med=="2"){
                        echo "CURADO";
                       }else if($alta_anulada->id_condi_egres_med=="3"){
                        echo "TRASLADO";
                       }else if($alta_anulada->id_condi_egres_med=="4"){
                        echo "RETIRO VOLUNTARIO";
                       }else if($alta_anulada->id_condi_egres_med=="5"){
                        echo "FUGADO";
                       }else if($alta_anulada->id_condi_egres_med=="6"){
                        echo "FALLECIDO";
                       }
                    endif;
                   ?>
          			  </div>
                  </div>
                  <div class="col-sm-12" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                  <div class="col-sm-8" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                  <br>
                  <?php 
                    if ($emergencia->estado==5):
                       if($emergencia->id_tipo_egres_med=="1"){
                      
                       }else if($emergencia->id_tipo_egres_med=="2"){
                        echo "SE  HOSPITALIZO  EL SIGUIENTE SERVICIO DE: " ;
                        echo $upss->nombre_consultorio;
                        echo ", CON EL CODIGO UPSS: ";
                        echo $upss->codigo_upss;
                       }else if($emergencia->id_tipo_egres_med=="3"){
                        
                        echo "SE  TRASLADO  A LA SIGUIENTE IPRESS: ";
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
                       }else if($emergencia->id_tipo_egres_med=="7"){
                        echo "OBSERVACION DEL RETIRO VOLUNTARIO: " ;
                        echo $emergencia->detalle_alta_admin;
                       }else if($emergencia->id_tipo_egres_med=="6"){
                        echo "DETALLES DE LA FUGA: " ;
                        echo $emergencia->detalle_alta_admin;
                       }
                    endif;
                    if ($emergencia->estado==3):
                      if($alta_anulada->id_tipo_egres_med=="1"){
                      
                       }else if($alta_anulada->id_tipo_egres_med=="2"){
                        echo "SE  HOSPITALIZO  EL SIGUIENTE SERVICIO DE: " ;
                        echo $upss->nombre_consultorio;
                        echo ", CON EL CODIGO UPSS: ";
                        echo $upss->codigo_upss;
                       }else if($alta_anulada->id_tipo_egres_med=="3"){
                        
                        echo "SE  TRASLADO  A LA SIGUIENTE IPRESS: ";
                        echo $estable->nombre ."<br>". "LA OBSERVACIONE DEl TRASLADO:  ";
                        echo $alta_anulada->obs_traslado ."<br>". "ESPECIALIDA DE TRASLADO: ";
                        echo $alta_anulada->especialida_destino ."<br>"." DETALLE:  ";
                        echo $alta_anulada->especialidad_destino_otro ."<br>". "CONDICION DE TRASLADO: ";
                        echo $alta_anulada->condicion_traslado  ."<br>".  "ACOMPAÑANTE:  ";
                        echo $alta_anulada->personal_acompañante;
                       }else if($alta_anulada->id_tipo_egres_med=="4"){
                        echo "SE  REALIZO NECROPCIA " ;
                        echo $alta_anulada->necropsia;
                        echo ", LA CAUSAS DE LA MUERTE FUE  ";
                        echo $alta_anulada->causas_muerte;
                       }else if($alta_anulada->id_tipo_egres_med=="7"){
                        echo "OBSERVACION DEL RETIRO VOLUNTARIO: " ;
                        echo $alta_anulada->detalle_alta_admin;
                       }else if($alta_anulada->id_tipo_egres_med=="6"){
                        echo "DETALLES DE LA FUGA: " ;
                        echo $alta_anulada->detalle_alta_admin;
                       }
                    endif;

                   ?>
                  </div>
                  </div>
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
              @if ($emergencia->estado==3)
              <a href="/{{$alta_anulada->ruta_egreso}}" class="btn btn-danger btn-sm pull-right"
                target="_blank" style="margin-right:15px">
                Ver Alta Medica Firmada<i class="fa fa-file-pdf-o"></i>
              </a>
              @endif
              <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_atenciones_by_emergencia"> <span class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
             
            </form>

          </div>


        </div>
      </div>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Anular Alta Médica</h3>
            </div>
            <div class="modal-body">
                <!-- content goes here -->
                <form class="" method="post" action="{{ route('registrar_alta_medica_anular')}}" id="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Escribir el motivo para anular el alta médica</label>
                        <input type="text" class="form-control" id="motivo" name="motivo" placeholder="" required="">
                        <input type="hidden" class="form-control" id="id_establecimiento" name="id_establecimiento" value="{{$prestacion->id_establecimiento}}">
                        <input type="hidden" class="form-control" id="id" name="id" value="{{$prestacion->id_ext}}">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-danger" type="submit" value="Anular">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>





@include('frontend.includes.footer')
@endsection
