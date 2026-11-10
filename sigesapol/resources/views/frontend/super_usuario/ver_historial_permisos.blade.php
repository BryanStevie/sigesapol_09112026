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

  $.post("{{route('postArguments')}}", {
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
  var id_resol = $('#id_prestacion').val();
  $.post("{{route('actualiza_prestacion')}}", {
    _token: CSRF_TOKEN,
    id_tipo: "prestacion",
    id_resol: id_resol,
  }, function(data, status) {
    $.post("{{route('actualiza_prestacion')}}", {
      _token: CSRF_TOKEN,
      id_tipo: "receta",
      id_resol: id_resol,
    }, function(data, status) {
      $.post("{{route('actualiza_prestacion')}}", {
        _token: CSRF_TOKEN,
        id_tipo: "laboratorio",
        id_resol: id_resol,
      }, function(data, status) {
        $.post("{{route('actualiza_prestacion')}}", {
          _token: CSRF_TOKEN,
          id_tipo: "radiologia",
          id_resol: id_resol,
        }, function(data, status) {
          location.reload();
        });
      });
    });
  });
}



$(document).ready(function() {

  if ($("#flag_firmar").val() == 0) {
    $("#btnfirmar").hide();
  } else {
    $("#btnfirmar").show();
  }

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
            <h3 class="panel-title">MÓDULO HISTORIAL DE PERMISOS</h3>
          </div>

          <div class="panel-body">


            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->


            <form class="form-horizontal" method="post" id="frmBloque">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
              <?php
             
            ?>

            

              <fieldset>

                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DE PERMISOS DEL USUARIO</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">


                    <div class="form-group">
                      
                      <div class="col-md-3">
                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $user_administrado->dni?></p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $user_administrado->paterno?></p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $user_administrado->materno?></p>
                      </div>
                      <div class="col-md-3">
                        <label>NOMBRES</label>
                        <p><?php echo $user_administrado->name?></p>
                      </div>
                    
                    </div>

                    <div class="form-group">
                        <div class="col-md-4">
                            <label for="dni_beneficiario">ESTABLECIMIENTO</label>
                            <p><?php echo $local_usuario->nombre?></p>
                        </div>
                        <div class="col-md-4">
                            <label for="dni_beneficiario">FARMACIA</label>
                            <p><?php echo $farmacia_usuario->nombre?></p>
                        </div>
                        <div class="col-md-4">
                            <label for="dni_beneficiario">CORREO</label>
                            <p><?php echo $user_administrado->email?></p>
                        </div>
                    </div>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                        <b>MÓDULOS:</b><br>
                            <div class="form-group">
                            <br>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">ESTABLECIEMIENTO</label>
                                    <p><?php echo $user_administrado->flagestablecimientos?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">FARMACIA</label>
                                    <p><?php echo $user_administrado->flagfarmacias?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">SERVICIOS</label>
                                    <p><?php echo $user_administrado->flagservicios?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">RESPONSABLE DE ALMACEN</label>
                                    <p><?php echo $user_administrado->responsable?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">RESPONSABLE FARMACIA</label>
                                    <p><?php echo $user_administrado->flagresponsablefarmacia?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">FISCALIZADOR</label>
                                    <p><?php echo $user_administrado->flagfiscalizador?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">RESPONSABLE DE CITAS</label>
                                    <p><?php echo $user_administrado->flaghistoria?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">EMERGENCIA Y REFERENCIA</label>
                                    <p><?php echo $user_administrado->flagemergenciareferencia?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">HOSPITALIZACION</label>
                                    <p><?php echo $user_administrado->flaghospitalizacion?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">EMERGENCIA</label>
                                    <p><?php echo $user_administrado->flagemergencia?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">VENTANILLA</label>
                                    <p><?php echo $user_administrado->flagventanilla?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">LLAMADA CITAS</label>
                                    <p><?php echo $user_administrado->flagllamadacita?></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="dni_beneficiario">MODULO LLAMADA</label>
                                    <p><?php echo $user_administrado->flagmodulollamado?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                            <b>ACCESOS A LAS PESTAÑAS</b>
                                <div class="form-group">
                                    <table class="display" id="tblDiagnostico_anteriores" cellspacing="1" width="100%" style="margin-top:10px;">
                                        <thead>
                                            <tr>
                                            <th width="25%">MENU</th>
                                            <th width="15%">CREAR</th>
                                            <th width="15%">MODIFICAR</th>
                                            <th width="15%">ELIMINAR</th>
                                            <th width="15%">LECTURA</th>
                                            <th width="15%">FECHA ACTUALIZACION</th>
                                            </tr>
                                            <tr> <td><br></td> </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                $crear = "";
                                                if($row->crear==1)$crear = "ACTIVO";
                                                if($row->crear==0)$crear = "DESACTIVO";
                                                $modificar = "";
                                                if($row->modificar==1)$modificar = "ACTIVO";
                                                if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                $eliminar = "";
                                                if($row->eliminar==1)$eliminar = "ACTIVO";
                                                if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                $lectura = "";
                                                if($row->lectura==1)$lectura = "ACTIVO";
                                                if($row->lectura==0)$lectura = "DESACTIVO";
                                                ?>
                                                  <tr>
                                                  <td><?php if($row->id == "17"): echo "<b>$row->nombremenu </b>"; endif;?></td>
                                                  <td><?php if($row->id == "17"): echo "<b>$crear</b>"; endif;?></td>
                                                  <td><?php if($row->id == "17"): echo "<b>$modificar</b>"; endif;?></td>
                                                  <td><?php if($row->id == "17"): echo "<b>$eliminar</b>"; endif;?></td>
                                                  <td><?php if($row->id == "17"): echo "<b>$lectura</b>"; endif;?></td>
                                                  <td><?php if($row->id == "17"): echo "<b>$row->updated_at</b>"; endif;?></td>
                                                  </tr>  
                                            <?php endforeach;?>
                                        </tbody>
                                        <tbody>
                                          <tr><br> </tr>
                                        </tbody>
                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                $crear = "";
                                                if($row->crear==1)$crear = "ACTIVO";
                                                 if($row->crear==0)$crear = "DESACTIVO";
                                                 $modificar = "";
                                                 if($row->modificar==1)$modificar = "ACTIVO";
                                                 if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                 $eliminar = "";
                                                 if($row->eliminar==1)$eliminar = "ACTIVO";
                                                 if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                 $lectura = "";
                                                 if($row->lectura==1)$lectura = "ACTIVO";
                                                 if($row->lectura==0)$lectura = "DESACTIVO";
                                                ?>
                                                
                                                  <tr width>
                                                  <td><?php if($row->parentid == "17"): echo $row->nombremenu ; endif;?></td>
                                                  <td><?php if($row->parentid == "17"): if($row->crear == 1): echo "<font color='red'><b> $crear</b> </font>";else: echo $crear; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "17"): if($row->modificar == 1): echo "<font color='red'><b> $modificar</b> </font>";else: echo $modificar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "17"): if($row->eliminar == 1): echo "<font color='red'><b> $eliminar</b> </font>";else: echo $eliminar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "17"): if($row->lectura == 1): echo "<font color='red'><b> $lectura</b> </font>";else: echo $lectura; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "17"): echo $row->updated_at; endif;?></td>
                                                  </tr>
                                                  
                                            <?php endforeach;?>
                                            <tr> <td><br></td> </tr>
                                        </tbody>

                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                 $crear = "";
                                                 if($row->crear==1)$crear = "ACTIVO";
                                                  if($row->crear==0)$crear = "DESACTIVO";
                                                  $modificar = "";
                                                  if($row->modificar==1)$modificar = "ACTIVO";
                                                  if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                  $eliminar = "";
                                                  if($row->eliminar==1)$eliminar = "ACTIVO";
                                                  if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                  $lectura = "";
                                                  if($row->lectura==1)$lectura = "ACTIVO";
                                                  if($row->lectura==0)$lectura = "DESACTIVO";
                                                 ?>
                                                  <tr>
                                                  <td width="25%"><?php if($row->id == "3"): echo "<b>$row->nombremenu </b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "3"): echo "<b>$crear</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "3"): echo "<b>$modificar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "3"): echo "<b>$eliminar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "3"): echo "<b>$lectura</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "3"): echo "<b>$row->updated_at</b>"; endif;?></td>
                                                  </tr>  
                                            <?php endforeach;?>
                                        </tbody>
                                        <tbody>
                                             <?php foreach($permisos_user as $row):
                                                 $crear = "";
                                                 if($row->crear==1)$crear = "ACTIVO";
                                                  if($row->crear==0)$crear = "DESACTIVO";
                                                  $modificar = "";
                                                  if($row->modificar==1)$modificar = "ACTIVO";
                                                  if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                  $eliminar = "";
                                                  if($row->eliminar==1)$eliminar = "ACTIVO";
                                                  if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                  $lectura = "";
                                                  if($row->lectura==1)$lectura = "ACTIVO";
                                                  if($row->lectura==0)$lectura = "DESACTIVO";
                                                 ?>
                                                
                                                  <tr>
                                                  <td><?php if($row->parentid == "3"): echo $row->nombremenu; endif;?></td>
                                                  <td><?php if($row->parentid == "3"): if($row->crear == 1): echo "<font color='red'><b> $crear</b> </font>";else: echo $crear; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "3"): if($row->modificar == 1): echo "<font color='red'><b> $modificar</b> </font>";else: echo $modificar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "3"): if($row->eliminar == 1): echo "<font color='red'><b> $eliminar</b> </font>";else: echo $eliminar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "3"): if($row->lectura == 1): echo "<font color='red'><b> $lectura</b> </font>";else: echo $lectura; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "3"): echo $row->updated_at; endif;?></td>
                                                  </tr>
                                                  
                                            <?php endforeach;?>
                                            <tr> <td><br></td> </tr>
                                        </tbody>

                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                 $crear = "";
                                                 if($row->crear==1)$crear = "ACTIVO";
                                                  if($row->crear==0)$crear = "DESACTIVO";
                                                  $modificar = "";
                                                  if($row->modificar==1)$modificar = "ACTIVO";
                                                  if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                  $eliminar = "";
                                                  if($row->eliminar==1)$eliminar = "ACTIVO";
                                                  if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                  $lectura = "";
                                                  if($row->lectura==1)$lectura = "ACTIVO";
                                                  if($row->lectura==0)$lectura = "DESACTIVO";
                                                 ?>
                                                  <tr>
                                                  <td width="25%"><?php if($row->id == "20"): echo "<b>$row->nombremenu </b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "20"): echo "<b>$crear</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "20"): echo "<b>$modificar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "20"): echo "<b>$eliminar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "20"): echo "<b>$lectura</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "20"): echo "<b>$row->updated_at</b>"; endif;?></td>
                                                  </tr>  
                                            <?php endforeach;?>
                                        </tbody>
                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                $crear = "";
                                                if($row->crear==1)$crear = "ACTIVO";
                                                 if($row->crear==0)$crear = "DESACTIVO";
                                                 $modificar = "";
                                                 if($row->modificar==1)$modificar = "ACTIVO";
                                                 if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                 $eliminar = "";
                                                 if($row->eliminar==1)$eliminar = "ACTIVO";
                                                 if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                 $lectura = "";
                                                 if($row->lectura==1)$lectura = "ACTIVO";
                                                 if($row->lectura==0)$lectura = "DESACTIVO";
                                                ?>
                                                
                                                  <tr>
                                                  <td><?php if($row->parentid == "20"): echo $row->nombremenu; endif;?></td>
                                                  <td><?php if($row->parentid == "20"): if($row->crear == 1): echo "<font color='red'><b> $crear</b> </font>";else: echo $crear; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "20"): if($row->modificar == 1): echo "<font color='red'><b> $modificar</b> </font>";else: echo $modificar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "20"): if($row->eliminar == 1): echo "<font color='red'><b> $eliminar</b> </font>";else: echo $eliminar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "20"): if($row->lectura == 1): echo "<font color='red'><b> $lectura</b> </font>";else: echo $lectura; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "20"): echo $row->updated_at; endif;?></td>
                                                  </tr>
                                                  
                                            <?php endforeach;?>
                                            <tr> <td><br></td> </tr>
                                        </tbody>

                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                 $crear = "";
                                                 if($row->crear==1)$crear = "ACTIVO";
                                                  if($row->crear==0)$crear = "DESACTIVO";
                                                  $modificar = "";
                                                  if($row->modificar==1)$modificar = "ACTIVO";
                                                  if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                  $eliminar = "";
                                                  if($row->eliminar==1)$eliminar = "ACTIVO";
                                                  if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                  $lectura = "";
                                                  if($row->lectura==1)$lectura = "ACTIVO";
                                                  if($row->lectura==0)$lectura = "DESACTIVO";
                                                 ?>
                                                  <tr>
                                                  <td width="25%"><?php if($row->id == "120"): echo "<b>$row->nombremenu </b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "120"): echo "<b>$crear</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "120"): echo "<b>$modificar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "120"): echo "<b>$eliminar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "120"): echo "<b>$lectura</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "120"): echo "<b>$row->updated_at</b>"; endif;?></td>
                                                  </tr>  
                                            <?php endforeach;?>
                                        </tbody>
                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                $crear = "";
                                                if($row->crear==1)$crear = "ACTIVO";
                                                 if($row->crear==0)$crear = "DESACTIVO";
                                                 $modificar = "";
                                                 if($row->modificar==1)$modificar = "ACTIVO";
                                                 if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                 $eliminar = "";
                                                 if($row->eliminar==1)$eliminar = "ACTIVO";
                                                 if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                 $lectura = "";
                                                 if($row->lectura==1)$lectura = "ACTIVO";
                                                 if($row->lectura==0)$lectura = "DESACTIVO";
                                                ?>
                                                
                                                  <tr>
                                                  <td><?php if($row->parentid == "120"): echo $row->nombremenu; endif;?></td>
                                                  <td><?php if($row->parentid == "120"): if($row->crear == 1): echo "<font color='red'><b> $crear</b> </font>";else: echo $crear; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "120"): if($row->modificar == 1): echo "<font color='red'><b> $modificar</b> </font>";else: echo $modificar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "120"): if($row->eliminar == 1): echo "<font color='red'><b> $eliminar</b> </font>";else: echo $eliminar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "120"): if($row->lectura == 1): echo "<font color='red'><b> $lectura</b> </font>";else: echo $lectura; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "120"): echo $row->updated_at; endif;?></td>
                                                  </tr>
                                                  
                                            <?php endforeach;?>
                                            <tr> <td><br></td> </tr>
                                        </tbody>

                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                 $crear = "";
                                                 if($row->crear==1)$crear = "ACTIVO";
                                                  if($row->crear==0)$crear = "DESACTIVO";
                                                  $modificar = "";
                                                  if($row->modificar==1)$modificar = "ACTIVO";
                                                  if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                  $eliminar = "";
                                                  if($row->eliminar==1)$eliminar = "ACTIVO";
                                                  if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                  $lectura = "";
                                                  if($row->lectura==1)$lectura = "ACTIVO";
                                                  if($row->lectura==0)$lectura = "DESACTIVO";
                                                 ?>
                                                  <tr>
                                                  <td width="25%"><?php if($row->id == "14"): echo "<b>$row->nombremenu </b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "14"): echo "<b>$crear</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "14"): echo "<b>$modificar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "14"): echo "<b>$eliminar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "14"): echo "<b>$lectura</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "14"): echo "<b>$row->updated_at</b>"; endif;?></td>
                                                  </tr>  
                                            <?php endforeach;?>
                                        </tbody>
                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                $crear = "";
                                                if($row->crear==1)$crear = "ACTIVO";
                                                 if($row->crear==0)$crear = "DESACTIVO";
                                                 $modificar = "";
                                                 if($row->modificar==1)$modificar = "ACTIVO";
                                                 if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                 $eliminar = "";
                                                 if($row->eliminar==1)$eliminar = "ACTIVO";
                                                 if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                 $lectura = "";
                                                 if($row->lectura==1)$lectura = "ACTIVO";
                                                 if($row->lectura==0)$lectura = "DESACTIVO";
                                                ?>
                                                
                                                  <tr>
                                                  <td><?php if($row->parentid == "14"): echo $row->nombremenu; endif;?></td>
                                                  <td><?php if($row->parentid == "14"): if($row->crear == 1): echo "<font color='red'><b> $crear</b> </font>";else: echo $crear; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "14"): if($row->modificar == 1): echo "<font color='red'><b> $modificar</b> </font>";else: echo $modificar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "14"): if($row->eliminar == 1): echo "<font color='red'><b> $eliminar</b> </font>";else: echo $eliminar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "14"): if($row->lectura == 1): echo "<font color='red'><b> $lectura</b> </font>";else: echo $lectura; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "14"): echo $row->updated_at; endif;?></td>
                                                  </tr>
                                                  
                                            <?php endforeach;?>
                                            <tr> <td><br></td> </tr>
                                        </tbody>

                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                 $crear = "";
                                                 if($row->crear==1)$crear = "ACTIVO";
                                                  if($row->crear==0)$crear = "DESACTIVO";
                                                  $modificar = "";
                                                  if($row->modificar==1)$modificar = "ACTIVO";
                                                  if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                  $eliminar = "";
                                                  if($row->eliminar==1)$eliminar = "ACTIVO";
                                                  if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                  $lectura = "";
                                                  if($row->lectura==1)$lectura = "ACTIVO";
                                                  if($row->lectura==0)$lectura = "DESACTIVO";
                                                 ?>
                                                  <tr>
                                                  <td width="25%"><?php if($row->id == "124"): echo "<b>$row->nombremenu </b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "124"): echo "<b>$crear</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "124"): echo "<b>$modificar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "124"): echo "<b>$eliminar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "124"): echo "<b>$lectura</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "124"): echo "<b>$row->updated_at</b>"; endif;?></td>
                                                  </tr>  
                                            <?php endforeach;?>
                                        </tbody>
                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                $crear = "";
                                                if($row->crear==1)$crear = "ACTIVO";
                                                 if($row->crear==0)$crear = "DESACTIVO";
                                                 $modificar = "";
                                                 if($row->modificar==1)$modificar = "ACTIVO";
                                                 if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                 $eliminar = "";
                                                 if($row->eliminar==1)$eliminar = "ACTIVO";
                                                 if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                 $lectura = "";
                                                 if($row->lectura==1)$lectura = "ACTIVO";
                                                 if($row->lectura==0)$lectura = "DESACTIVO";
                                                ?>
                                                
                                                  <tr>
                                                  <td><?php if($row->parentid == "124"): echo $row->nombremenu; endif;?></td>
                                                  <td><?php if($row->parentid == "124"): if($row->crear == 1): echo "<font color='red'><b> $crear</b> </font>";else: echo $crear; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "124"): if($row->modificar == 1): echo "<font color='red'><b> $modificar</b> </font>";else: echo $modificar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "124"): if($row->eliminar == 1): echo "<font color='red'><b> $eliminar</b> </font>";else: echo $eliminar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "124"): if($row->lectura == 1): echo "<font color='red'><b> $lectura</b> </font>";else: echo $lectura; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "124"): echo $row->updated_at; endif;?></td>
                                                  </tr>
                                                  
                                            <?php endforeach;?>
                                            <tr> <td><br></td> </tr>
                                        </tbody>

                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                 $crear = "";
                                                 if($row->crear==1)$crear = "ACTIVO";
                                                  if($row->crear==0)$crear = "DESACTIVO";
                                                  $modificar = "";
                                                  if($row->modificar==1)$modificar = "ACTIVO";
                                                  if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                  $eliminar = "";
                                                  if($row->eliminar==1)$eliminar = "ACTIVO";
                                                  if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                  $lectura = "";
                                                  if($row->lectura==1)$lectura = "ACTIVO";
                                                  if($row->lectura==0)$lectura = "DESACTIVO";
                                                 ?>
                                                  <tr>
                                                  <td width="25%"><?php if($row->id == "18"): echo "<b>$row->nombremenu </b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "18"): echo "<b>$crear</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "18"): echo "<b>$modificar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "18"): echo "<b>$eliminar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "18"): echo "<b>$lectura</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "18"): echo "<b>$row->updated_at</b>"; endif;?></td>
                                                  </tr>  
                                            <?php endforeach;?>
                                        </tbody>
                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                $crear = "";
                                                if($row->crear==1)$crear = "ACTIVO";
                                                 if($row->crear==0)$crear = "DESACTIVO";
                                                 $modificar = "";
                                                 if($row->modificar==1)$modificar = "ACTIVO";
                                                 if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                 $eliminar = "";
                                                 if($row->eliminar==1)$eliminar = "ACTIVO";
                                                 if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                 $lectura = "";
                                                 if($row->lectura==1)$lectura = "ACTIVO";
                                                 if($row->lectura==0)$lectura = "DESACTIVO";
                                                ?>
                                                
                                                  <tr>
                                                  <td><?php if($row->parentid == "18"): echo $row->nombremenu; endif;?></td>
                                                  <td><?php if($row->parentid == "18"): if($row->crear == 1): echo "<font color='red'><b> $crear</b> </font>";else: echo $crear; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "18"): if($row->modificar == 1): echo "<font color='red'><b> $modificar</b> </font>";else: echo $modificar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "18"): if($row->eliminar == 1): echo "<font color='red'><b> $eliminar</b> </font>";else: echo $eliminar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "18"): if($row->lectura == 1): echo "<font color='red'><b> $lectura</b> </font>";else: echo $lectura; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "18"): echo $row->updated_at; endif;?></td>
                                                  </tr>
                                                  
                                            <?php endforeach;?>
                                            <tr> <td><br></td> </tr>
                                        </tbody>
                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                 $crear = "";
                                                 if($row->crear==1)$crear = "ACTIVO";
                                                  if($row->crear==0)$crear = "DESACTIVO";
                                                  $modificar = "";
                                                  if($row->modificar==1)$modificar = "ACTIVO";
                                                  if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                  $eliminar = "";
                                                  if($row->eliminar==1)$eliminar = "ACTIVO";
                                                  if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                  $lectura = "";
                                                  if($row->lectura==1)$lectura = "ACTIVO";
                                                  if($row->lectura==0)$lectura = "DESACTIVO";
                                                 ?>
                                                  <tr>
                                                  <td width="25%"><?php if($row->id == "137"): echo "<b>$row->nombremenu </b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "137"): echo "<b>$crear</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "137"): echo "<b>$modificar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "137"): echo "<b>$eliminar</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "137"): echo "<b>$lectura</b>"; endif;?></td>
                                                  <td width="15%"><?php if($row->id == "137"): echo "<b>$row->updated_at</b>"; endif;?></td>
                                                  </tr>  
                                            <?php endforeach;?>
                                        </tbody>
                                        <tbody>
                                            <?php foreach($permisos_user as $row):
                                                $crear = "";
                                                if($row->crear==1)$crear = "ACTIVO";
                                                 if($row->crear==0)$crear = "DESACTIVO";
                                                 $modificar = "";
                                                 if($row->modificar==1)$modificar = "ACTIVO";
                                                 if($row->modificar==0)$modificar = "DESACTIVO";                                                
                                                 $eliminar = "";
                                                 if($row->eliminar==1)$eliminar = "ACTIVO";
                                                 if($row->eliminar==0)$eliminar = "DESACTIVO";
                                                 $lectura = "";
                                                 if($row->lectura==1)$lectura = "ACTIVO";
                                                 if($row->lectura==0)$lectura = "DESACTIVO";
                                                ?>
                                                
                                                  <tr>
                                                  <td><?php if($row->parentid == "137"): echo $row->nombremenu; endif;?></td>
                                                  <td><?php if($row->parentid == "137"): if($row->crear == 1): echo "<font color='red'><b> $crear</b> </font>";else: echo $crear; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "137"): if($row->modificar == 1): echo "<font color='red'><b> $modificar</b> </font>";else: echo $modificar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "137"): if($row->eliminar == 1): echo "<font color='red'><b> $eliminar</b> </font>";else: echo $eliminar; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "137"): if($row->lectura == 1): echo "<font color='red'><b> $lectura</b> </font>";else: echo $lectura; endif; ; endif;?></td>
                                                  <td><?php if($row->parentid == "137"): echo $row->updated_at; endif;?></td>
                                                  </tr>
                                                  
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>

                  </div>
                </div>

              </fieldset>

		</form>
		
            <form class="form-horizontal" method="post" id="frmAltaMedica">

                <a class="btn btn-primary btn-sm hidden-print pull-right" href="/ver_usuarios_su"> <span class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>

            </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
</div>

<input type="hidden" id="argumentos" value="" />
<input type="hidden" id="doc_firmar" value="" />
<div id="addComponent"></div>

@include('frontend.includes.footer')
@endsection
