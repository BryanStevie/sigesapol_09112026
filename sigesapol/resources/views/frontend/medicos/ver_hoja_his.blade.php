<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master')
@section('renderjs')
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
  //<![CDATA[

function firmar() {
  $.ajax({
      url: "<?php echo url()?>/genera_csv_hoja_his",
      type: "post",
      data: $("#frmBloque").serialize(),
      dataType: 'json'
    })
    .done(function(resultado) {
      console.log(resultado);
      if (resultado.sw) {
        var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
        $("#doc_firmar").val(`${resultado.csv}.7z`);
        $("#id_hoja_his").val(`${resultado.id_hoja_his}`);
        initInvoker('W');
      } else {
        
      }
      $('#btnLiquidacionFirmarPdf').attr("disabled", false);

    })
    .fail(function(jqXHR, textStatus) {
      console.log("Request failed: " + textStatus)
    })
    .always(function() {
      
    });
}

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

  $.post("{{route('postArgumentsHojaHis')}}", {
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
  var id_hh = $('#id_hoja_his').val();
  location.reload();
  $.post("{{route('actualiza_hoja_his')}}", {
    _token: CSRF_TOKEN,
    $id_hoja_his: id_hh,
  }, function(data, status) {
    location.reload();
  });
}

function MiFuncionCancel() {
  alert("El proceso de firma digital fue cancelado.");
}
//]]>

$(document).ready(function() {

  $(document).on('click', '#btnfirmar', function(e) {
    e.preventDefault();
    firmar();
  })

  $('#fecha_atencion').datepicker();


  $('input[name="parent"]').on("change", function(e) {
    getServicio();
  });

  $('#id_servicio,#id_establecimiento').on("change", function(e) {
    $('#calendar').fullCalendar('destroy');
    fullCalendar();
    modalTurnos();
    modalHistorial();
  });


  $('#btnPrestacion').on("click", function(e) {
    guardarPrestacion();
  });

  
  $('#example tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

});


function guardarPrestacion() {

  var msg = "";
  if (msg != "") {
    bootbox.alert(msg);
    return false;
  } else {
    //fn_save_prestacion();
    $("#frmBloque").submit();
  }
}

function fn_save_prestacion() {


  $.ajax({
    url: "../registrar_prestacion",
    type: "POST",
    data: $("#frmPrestacion").serialize(),
    success: function(result) {

    }
  });
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
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">VISUALIZACIÓN DE LA HOJA HIS</h3>
        </div>  
        <div class="panel-body">
            <form class="form-horizontal" method="post" id="frmBloque" action="#">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                  <?php
                  $flag_firmar = 0;
                  if($flag_pendiente_firma == 1) {
                    $flag_firmar = 1;
                  }
                  ?>
                <div class="col-md-12">
                    <?php
                        $script_tz = date_default_timezone_get();
                        $fecha_hoy = date('d-m-Y');
                          
                        $separa = explode("-",$fecha_atencion);
                        $dia = $separa[0];
                        $mes = $separa[1];
                        $ano = $separa[2]; 
                        switch($mes){
                            case '01': $meses='Enero'; break;
                            case '02': $meses='Febrero'; break;
                            case '03': $meses='Marzo'; break;
                            case '04': $meses='Abril'; break;
                            case '05': $meses='Mayo'; break;
                            case '06': $meses='Junio'; break;
                            case '07': $meses='Julio'; break;
                            case '08': $meses='Agosto'; break;
                            case '09': $meses='Septiembre'; break;
                            case '10': $meses='Octubre'; break;
                            case '11': $meses='Noviembre'; break;
                            case '12': $meses='Diciembre'; break;
                        }
                        $arrayDias = array('Domingo', 'Lunes', 'Martes','Miercoles', 'Jueves', 'Viernes', 'Sabado');
                    ?>
                    <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                                <div class="panel-title text-center">
                                    <b>INFORMACIÓN DEL RESPONSABLE DE ATENCION</b>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label for="tipo">AÑO</label>
                                        <p>{{$ano}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="dni_beneficiario">MES</label>
                                        <p>{{$meses}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>DNI DEL PROFESIONAL</label>
                                        <p>{{$medico->dni}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>APELLIDOS Y NOMBRES DEL PROFESIONAL</label>
                                        <p>{{$medico->paterno}} {{$medico->materno}}, {{$medico->nombre}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label>PROFESIÓN</label>
                                        <p>{{$medico->especializacion}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>N° TURNO</label>
                                        <p></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>FECHA DE ATENCIÓN</label>
                                        <p>{{$fecha_atencion}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <h5>ATENCIONES</h5>
                    <table class="table table-bordered dataTable no-footer" border="0px" style="background-color:white !important"  width="100%">
                        <thead>
                          <tr role="row">
                            <th style="border: 1px solid #A4A4A4;" ><p align="center" style="font-size:12px"><b>N°</b></p></th>
                            <th style="border: 1px solid #A4A4A4;" ><p align="center" style="font-size:12px"><b>HORA DE <br/>ATENCION</b></p></th>
                            <th style="border: 1px solid #A4A4A4;" ><p align="center" style="font-size:12px"><b>DNI</b></p><hr><p align="center" style="font-size:12px"><b>HISTORIA CLINICA</b></p></th>
                            <th style="border: 1px solid #A4A4A4;" ><p align="center" style="font-size:12px"><b>E</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>S</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>DIAGNOSTICO MOTIVO DE CONSULTA</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>TIPO <br/>DIAG</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>CIE10</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>CANT</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>CPT</b></p></th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $x=1;?>
                            @foreach ($prestaciones as $r)
                            <tr>
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">{{$x}} @if (Auth::user()->dni == "48839708") {{$r->id}} @endif</p></td>
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">{{$r->horario}}</p></td>
                              <td style="border: 1px solid #A4A4A4;">
                                <p align="center" style="font-size:12px">{{$r->nro_doc_ident}}</p><hr><p align="center" style="font-size:12px"><b>{{$r->nro_historia}}</b></p>
                              </td style="border: 1px solid #A4A4A4;">
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">{{$r->ipress}}</p></td>
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">{{$r->upss}}</p></td>
                                
                              <td style="border: 1px solid #A4A4A4;"><p style="font-size:12px">
                                <?php 
                                    $model_prestaciones = new \App\Models\Prestaciones;
                                    $diagnostico = $model_prestaciones->GetDiagnosticoByIdPrestacion($r->id);
                                    foreach ($diagnostico as $d):
                                      echo $d->nombre.'<br/>';
                                    endforeach; 
                                ?>
                              </p></td>
                              <td style="border: 1px solid #A4A4A4;"><p  align="center" style="font-size:12px">
                                <?php 
                                  foreach ($diagnostico as $d):
                                      $tipo_diagnostico = "";
                                      if($d->id_tipo_diagnostico==1)$tipo_diagnostico = "P";
                                      if($d->id_tipo_diagnostico==2)$tipo_diagnostico = "D";
                                      if($d->id_tipo_diagnostico==3)$tipo_diagnostico = "R";
                                      echo $tipo_diagnostico.'<br/>';
                                    endforeach; 
                                ?>
                              </p>
                              </td>          
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">
                                <?php 
                                  foreach ($diagnostico as $d):
                                      echo $d->codigo.'<br/>';
                                    endforeach; 
                                ?>
                                </p>
                              </td style="border: 1px solid #A4A4A4;">
                                <?php 
                                    //$procedimientos = $model_prestaciones->GetProcedimientoByIdPrestacionNew($r->id,1);
                                    $procedimientos = $model_prestaciones->GetProcedimientosByIdPrestacion($r->id, 1);
                                    //$laboratorios = $model_prestaciones->GetProcedimientosByIdPrestacion($r->id, 2);
                                    //$radiologias = $model_prestaciones->GetProcedimientosByIdPrestacion($r->id, 3);
                                ?>
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">
                                <?php 
                                  foreach ($procedimientos as $p):
                                      echo $p->cantidad.'<br/>';
                                  endforeach; 
                                  //foreach ($laboratorios as $lab):
                                  //    echo $lab->cantidad.'<br/>';
                                  //endforeach; 
                                  //foreach ($radiologias as $rad):
                                  //    echo $rad->cantidad.'<br/>';
                                  //endforeach; 
                                ?>
                                </p>
                              </td>        
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">
                                <?php 
                                    foreach ($procedimientos as $p):
                                      echo $p->codigo.'<br/>';
                                    endforeach; 
                                    //foreach ($laboratorios as $lab):
                                    //  echo $lab->codigo.'<br/>';
                                    //endforeach; 
                                    //foreach ($radiologias as $rad):
                                    //  echo $rad->codigo.'<br/>';
                                    //endforeach;
                                ?>
                                </p>
                              </td>
                            </tr>
                            <?php $x++;?>
                            @endforeach
                        </tbody>
                    </table>
                    @if($tipo_atencion>1)
                    <br/>
                    <h5>EVALUACIONES</h5>
                    <br/>
                    <table class="table table-bordered dataTable no-footer" border="0px" style="background-color:white !important"  width="100%">
                        <thead>
                          <tr role="row">
                            <th style="border: 1px solid #A4A4A4;" ><p align="center" style="font-size:12px"><b>N°</b></p></th>
                            <th style="border: 1px solid #A4A4A4;" ><p align="center" style="font-size:12px"><b>HORA DE <br/>ATENCION</b></p></th>
                            <th style="border: 1px solid #A4A4A4;" ><p align="center" style="font-size:12px"><b>DNI</b></p><hr><p align="center" style="font-size:12px"><b>HISTORIA CLINICA</b></p></th>
                            <th style="border: 1px solid #A4A4A4;" ><p align="center" style="font-size:12px"><b>E</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>S</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>DIAGNOSTICO MOTIVO DE CONSULTA</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>TIPO <br/>DIAG</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>CIE10</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>CANT</b></p></th>
                            <th style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"><b>CPT</b></p></th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $x=1;?>
                            @foreach ($evaluaciones as $r)
                            <tr>
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">{{$x}}</p></td>
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">{{$r->horario}}</p></td>
                              <td style="border: 1px solid #A4A4A4;">
                                <p align="center" style="font-size:12px">{{$r->nro_doc_ident}}</p><hr><p align="center" style="font-size:12px"><b>{{$r->nro_historia}}</b></p>
                              </td style="border: 1px solid #A4A4A4;">
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"> C </p>
                              </td>
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px"> C </p>
                              </td>
                              <td style="border: 1px solid #A4A4A4;"><p style="font-size:12px">
                                <?php 
                                    $model_prestaciones = new \App\Models\Prestaciones;
                                    $model_evaluacion = new \App\Models\Evaluacione;
                                    $model_radiologia = new \App\Models\Radiologia;
                                    $model_laboratorio = new \App\Models\Laboratorio;
                                    //$diagnostico = $model_prestaciones->GetDiagnosticoByIdPrestacionEvaluacion($r->id_prestacion,$r->id);
                                    $diagnostico = $model_evaluacion->GetDiagnosticoByIdEvaluacion($r->id);
                                    foreach ($diagnostico as $d):
                                      echo $d->nombre.'<br/>';
                                    endforeach; 
                                ?>
                              </p></td>
                              <td style="border: 1px solid #A4A4A4;"><p  align="center" style="font-size:12px">
                                <?php 
                                  foreach ($diagnostico as $d):
                                      $tipo_diagnostico = "";
                                      if($d->id_tipo_diagnostico==1)$tipo_diagnostico = "P";
                                      if($d->id_tipo_diagnostico==2)$tipo_diagnostico = "D";
                                      if($d->id_tipo_diagnostico==3)$tipo_diagnostico = "R";
                                      echo $tipo_diagnostico.'<br/>';
                                    endforeach; 
                                ?>
                              </p>
                              </td>          
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">
                                <?php 
                                  foreach ($diagnostico as $d):
                                      echo $d->codigo.'<br/>';
                                    endforeach; 
                                ?>
                                </p>
                              </td style="border: 1px solid #A4A4A4;">
                                <?php 
                                    //$procedimientos = $model_prestaciones->GetProcedimientoByIdPrestacionNew($r->id_prestacion,1);
                                    $procedimientos = $model_prestaciones->GetProcedimientosByIdPrestacionydiagEvaluacion($r->id_prestacion, 1, $r->id);
                                    $laboratorios = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($r->id_prestacion,$r->id);
                                    $radiologias = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($r->id_prestacion,$r->id);
                                   // $laboratorios = $model_prestaciones->GetProcedimientosByIdPrestacionydiagEvaluacion($r->id_prestacion, 2,$r->id);
                                   // $radiologias = $model_prestaciones->GetProcedimientosByIdPrestacionydiagEvaluacion($r->id_prestacion, 3,$r->id);
                                ?>
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">
                                <?php 
                                  foreach ($procedimientos as $p):
                                      echo $p->cantidad.'<br/>';
                                  endforeach; 
                                  foreach ($laboratorios as $lab):
                                      //echo $lab->cantidad.'<br/>';
                                  endforeach; 
                                  foreach ($radiologias as $rad):
                                      //echo $rad->cantidad.'<br/>';
                                  endforeach; 
                                ?>
                                </p>
                              </td>        
                              <td style="border: 1px solid #A4A4A4;"><p align="center" style="font-size:12px">
                                <?php 
                                    foreach ($procedimientos as $p):
                                      echo $p->codigo.'<br/>';
                                    endforeach; 
                                    foreach ($laboratorios as $lab):
                                      //echo $lab->codigo.'<br/>';
                                    endforeach; 
                                    foreach ($radiologias as $rad):
                                      //echo $rad->codigo.'<br/>';
                                    endforeach;
                                ?>
                                </p>
                              </td>
                            </tr>
                            <?php $x++;?>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    <br/>
                    <div>
                        <input type="hidden" value="<?php echo $id_atencion?>" name="id_atencion" id="id_atencion">
                        <input type="hidden" value="<?php echo $id_atencion?>" name="id_atencion" id="id_atencion">
                        <input type="hidden" value="<?php echo $fecha_atencion?>" name="fecha_atencion" id="fecha_atencion">
                        <input type="hidden" value="<?php echo $id_medico?>" name="id_medico" id="id_medico">
                        <input type="hidden" value="<?php echo $id_consultorio?>" name="id_consultorio" id="id_consultorio">
                        <input type="hidden" value="<?php echo $id_establecimiento?>" name="id_establecimiento" id="id_establecimiento">
                        @if ($flag_pendiente_firma == 0)
                            @if(count($prestaciones)>0)
                                <a class="btn btn-primary btn-sm hidden-print pull-right" target="_blank" href="/print_hoja_his/{{$id_atencion}}/{{$id_establecimiento}}/{{$fecha_atencion}}/{{$id_medico}}/{{$id_consultorio}}"> <i class="fa fa-download"></i> &nbsp; Ver PDF Atención</a>
                            @endif
                            <?php if($tipo_atencion == 2): ?>
                              @if(count($evaluaciones)>0)
                                      <a class="btn btn-primary btn-sm hidden-print pull-right" target="_blank" href="/print_hoja_his_evaluacion/{{$id_atencion}}/{{$id_establecimiento}}/{{$fecha_atencion}}/{{$id_medico}}/76"> <i class="fa fa-download"></i> &nbsp; Ver PDF Evaluación</a>
                              @endif
                            <?php endif; ?>
                            <?php if($tipo_atencion == 3): ?>
                              @if(count($evaluaciones)>0)
                                      <a class="btn btn-primary btn-sm hidden-print pull-right" target="_blank" href="/print_hoja_his_evaluacion/{{$id_atencion}}/{{$id_establecimiento}}/{{$fecha_atencion}}/{{$id_medico}}/{{$id_consultorio}}"> <i class="fa fa-download"></i> &nbsp; Ver PDF Evaluación</a>
                              @endif
                            <?php endif; ?>
                        @else
                           <a class="btn btn-primary btn-sm hidden-print pull-right" target="_blank" href="/{{$dato_hoja_his[0]->ruta_hoja_his}}"> <i class="fa fa-download"></i> &nbsp; His Firmada</a>
                        @endif
                        <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_reporte_hoja_his"> <span class="glyphicon glyphicon-list"></span> &nbsp; Regresar</a>
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" id="argumentos" value="" />
<input type="hidden" id="doc_firmar" value="" />
<input type="hidden" id="user_firma" value="{{ Auth::user()->dni }}" />
<input type="hidden" value="<?php echo $flag_firmar?>" name="flag_firmar" id="flag_firmar">
<div id="addComponent"></div>
@include('frontend.includes.footer')
@endsection
