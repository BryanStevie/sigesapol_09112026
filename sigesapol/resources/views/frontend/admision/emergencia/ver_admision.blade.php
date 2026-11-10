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
  var id_resol = $('#id_admision').val();
  $.post("{{route('registra_firma_admision_emergencia')}}", {
    _token: CSRF_TOKEN,
    id_tipo: "admision",
    id_resol: id_resol,
  }, function(data, status) {
    location.reload();
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


function firmar() {
  
  $.ajax({
      url: "<?php echo url()?>/genera_csv_admision_emergencia",
      type: "post",
      data: $("#frmBloque").serialize(),
      dataType: 'json'
    })
    .done(function(resultado) {
      if (resultado.sw) {
        //var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
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
  var id = $('#id_admision').val();
  var batchSignatureProcessPoller = setInterval(function() {
    $.ajax({
      url: "<?php echo url()?>/sinTokensAdmision",
      type: "post",
      cache: false,
      dataType: 'json',
      data: $("#frmBloque").serialize(),
      success: function(completed) {
        if (completed) {
          location.href = '/ver_datos_admision/'+id+'/2/';
          clearInterval(batchSignatureProcessPoller);
        }
      }
    });
  }, 3000);
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
    color: black !important;  
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

<div class="container" style="width:90%; font-size: 14px !important;text-transform: uppercase;" >
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE ADMISIÓN DE EMERGENCIA</h3>
          </div>
          <div class="panel-body">
            <form class="form-horizontal" method="post" id="frmBloque"
              action="{{ route('registrar_receta_prestacion')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <?php
                $flag_firmar = 0;
                if($flag_pendiente_firma == 1) {
                  $flag_firmar = 1;
                }
              ?>


              <!--informacion de admision--> 
              <fieldset>
                <div class="row">
                  <div class="col-md-12">
                    <div class="panel with-nav-tabs panel-default" >
                      <div class="panel-heading">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab"
                              style="font-size:12px" aria-expanded="true"><b><i class="fa fa-file-text-o"
                                  aria-hidden="true"></i> INFORMACIÓN DE ADMISIÓN</b></a></li> 
                        </ul>
                      </div>
                      <div class="panel-body" >
                        <div class="tab-content">

                          <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                            <fieldset>
                              <div class="row"> 
                                <div class="col-md-2">
                                  <label>TIPO DE ATENCI&Oacute;N</label><br />
                                  EMERGENCIA
                                </div>
                                <div class="col-md-2">
                                  <label>FECHA DE REGISTRO</label><br />
                                  <?php echo date("d/m/Y", strtotime($admision_emergencia->created_at)); ?>
                                </div>
                                <div class="col-md-2">
                                  <label>N° ORDEN</label><br />
                                  {{$admision_emergencia->secuencia}}
                                </div>
                                <div class="col-md-6">
                                  <label for="tipo">NOMBRE DEL ESTABLECIMIENTO</label><br />
                                  {{$nombre_establecimiento}}
                                </div>


                              </div>
                            </fieldset>
                          </div> 

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <!--informacion de admision--> 

              <!--informacion de paciente--> 
              <fieldset>
                <div class="row">
                  <div class="col-md-12">
                    <div class="panel with-nav-tabs panel-default" >
                      <div class="panel-heading">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab"
                              style="font-size:12px" aria-expanded="true"><b><i class="fa fa-file-text-o"
                                  aria-hidden="true"></i> INFORMACIÓN DE PACIENTE</b></a></li> 
                        </ul>
                      </div>
                      <div class="panel-body" >
                        <div class="tab-content">

                          <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                            <fieldset>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="col-md-2">
                                      <label for="tipo">DOCUMENTO: </label><br />
                                      <p>
                                      <?php 
                                        switch ($admision_emergencia->id_tipo_doc_ident) {
                                          case '1': echo "DNI"; break;
                                          case '2': echo "CARNET DE EXTRANJERIA"; break;
                                          case '3': echo "PASAPORTE"; break;
                                          case '4': echo "DOCUMENTO DE IDENTIDAD EXTRANJERO"; break;
                                          case '5': echo "NN"; break;
                                        }
                                      ?> {{$admision_emergencia->dni_beneficiario}}</p>
                                    </div> 
                                    <div class="col-md-4">
                                      <label>PACIENTE:</label><br />
                                      <p>{{$admision_emergencia->paterno}} {{$admision_emergencia->materno}} {{$admision_emergencia->nombre}}</p>
                                    </div>
                                    <div class="col-md-2">
                                      <label>SITUACIÓN </label><br />
                                      <p>{{$admision_emergencia->situacion_paciente}}</p>
                                    </div> 
                                    <div class="col-md-1">
                                      <label>SEXO </label><br />
                                      <p>{{$admision_emergencia->sexo}}</p>
                                    </div>
                                    <div class="col-md-2">
                                      <label>F.NACIMIENTO: </label><br>
                                      <p>{{$admision_emergencia->fecha_nac}}</p>
                                    </div>
                                    <div class="col-md-1">
                                      <label>EDAD: </label><br>
                                      <p>{{$admision_emergencia->edadpaciente}}</p>
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <div class="col-md-4">
                                      <label>PRIORIDAD </label><br />
                                      <p>{{$admision_emergencia->prioridad}}</p>
                                    </div>
                                    <div class="col-md-4">
                                      <label>TOPICO &nbsp;</label><br />
                                      <p>{{$admision_emergencia->descripcion}}</p>
                                    </div>
                                  </div>


                                  

                                  <div class="row" >
                                    <hr  style="margin: 10px !important;"> 
                                    <div class="col-md-12">
                                      <b style="font-size: 20px;">DATOS DEL TITULAR</b><br><br>
                                    </div>
                                    <div class="col-md-2">
                                      <label>N° DE DOCUMENTO: </label><br>
                                      <p>{{$admision_emergencia->nomtipdoctitular}} - {{$admision_emergencia->nrodoctitular}}</p>
                                    </div>
                                    <div class="col-md-4">
                                      <label>PACIENTE: </label><br>
                                      <p>{{$admision_emergencia->apepattitular}} {{$admision_emergencia->apemattitular}}
                                        {{$admision_emergencia->nomtitular}}</p>
                                    </div>
                                    <div class="col-md-2">
                                      <label>CIP: </label><br>
                                      <p>{{$admision_emergencia->ciptitular}}</p>
                                    </div> 
                                    <div class="col-md-4">
                                      <label>GRADO: </label><br> 
                                      <p>{{$admision_emergencia->gradotitular}}</p>
                                    </div> 
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
              </fieldset> 
              <!--#informacion de paciente-->

              <!--informacion de ingreso-->
              <fieldset>
                <div class="row">
                  <div class="col-md-12">
                    <div class="panel with-nav-tabs panel-default" >
                      <div class="panel-heading">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab"
                              style="font-size:12px" aria-expanded="true"><b><i class="fa fa-file-text-o"
                                  aria-hidden="true"></i> INFORMACIÓN DE INGRESO</b></a></li> 
                        </ul>
                      </div>
                      <div class="panel-body" >
                        <div class="tab-content">

                          <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                            <fieldset>
                              <div class="row">
                                <div class="col-md-4"><br />
                                  <label>FORMA INGRESO</label><br />
                                  @foreach($forma_ingreso as $f)
                                  @if ($admision_emergencia->id_forma_ingreso == $f->id)
                                  {{$f->descripcion}}
                                  @endif
                                  @endforeach
                                </div>
                                <div class="col-md-4"><br />
                                  <label>ESTADO INGRESO</label><br />
                                  @foreach($estado_ingreso as $f)
                                  @if ($admision_emergencia->id_estado_ingreso == $f->id)
                                  {{$f->descripcion}}
                                  @endif
                                  @endforeach
                                </div>
                                <div class="col-md-4"><br />
                                  <label>MOTIVO DE INGRESO</label><br />
                                  @foreach($categoria as $f)
                                  @if ($admision_emergencia->id_motivo_ingreso == $f->id)
                                  {{$f->descripcion}}
                                  @endif
                                  @endforeach
                                </div>

                                  <div class="col-md-12"><br />
                                    <label>CONDUCIDO POR</label>
                                      <div class="row">
                                        @if($tipo_parentesco != 'SOLO') 
                                          <div class="col-md-3"><br />
                                            TIPO DE ACOMPAÑANTE:<br>
                                            {{$tipo_parentesco}}
                                          </div>

                                          <div class="col-md-7"><br />
                                            Nro Doc y Nombres:<br>
                                            {{ $admision_emergencia->dni_conducido }} - {{ $admision_emergencia->nombre_conducido }}
                                          </div> 

                                          <div class="col-md-2"><br />
                                            Telefono:<br />{{ $admision_emergencia->telf_conducido }}
                                          </div>
                                        @else
                                          <div class="col-md-3"><br />
                                            TIPO DE ACOMPAÑANTE:<br>
                                            VIENE SOLO
                                          </div>
                                        @endif
                                      </div>
                                  </div>

                                <div class="col-md-12"><br />
                                  <label>OBSERVACIÓN:</label>
                                  <div class="row">
                                    <div class="col-md-12">
                                      {{ $admision_emergencia->observacion_admision }}
                                    </div>
                                  </div>
                                </div>

                                @if($admision_emergencia->id_tipo_beneficiario == 13)
                                <div class="col-md-6"><br />
                                  <label>OFICIAL DE GUARDIA</label><br />
                                  {{ $admision_emergencia->oficial_guardia }}
                                </div>
                                @endif 
                              </div>
                            </fieldset>
                          </div> 

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>  
              <!--informacion de ingreso-->  

              <!--#informacion de referencial-->
              <fieldset>
                <div class="row">
                  <div class="col-md-12">
                    <div class="panel with-nav-tabs panel-default" >
                      <div class="panel-heading">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab"
                              style="font-size:12px" aria-expanded="true"><b><i class="fa fa-file-text-o"
                                  aria-hidden="true"></i> DATOS REFERENCIALES</b></a></li> 
                        </ul>
                      </div>
                      <div class="panel-body" >
                        <div class="tab-content">

                          <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                            <fieldset>
                              <div class="row">
                                <div class="col-md-2"><br />
                                  <label>DEPARTAMENTO</label><br />
                                  @foreach($departamento as $f)
                                  @if ($admision_emergencia->id_departamento == $f->id_departamento)
                                  {{$f->descripcion}}
                                  @endif
                                  @endforeach
                                </div>
                                <div class="col-md-2"><br />
                                  <label>PROVINCIA</label><br />
                                  @if($provincia!='')
                                  @foreach($provincia as $p)
                                  @if ($admision_emergencia->id_provincia == $p->id_provincia)
                                  {{$p->descripcion}}
                                  @endif
                                  @endforeach
                                  @endif
                                </div>
                                <div class="col-md-2"><br />
                                  <label>DISTRITO</label><br />
                                  @if($distrito!='')
                                  @foreach($distrito as $d)
                                  @if ($admision_emergencia->id_distrito == $d->id_distrito)
                                  {{$d->descripcion}}
                                  @endif
                                  @endforeach
                                  @endif
                                </div>
                                <div class="col-md-4"><br />
                                  <label>DIRECCIÓN REFERENCIAL</label><br />
                                  {{$admision_emergencia->domicilio}}<br />
                                </div>
                                <div class="col-md-2"><br />
                                  <label>TELÉFONO</label><br />
                                  {{$admision_emergencia->telefono}}<br />
                                </div>
                              </div>
                            </fieldset>
                          </div> 

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>  
              <!--#informacion de referencial-->

              <!--informacion de violencia-->
              
              @if ($data_violencia->id_emergencia_violencia  !== null)
              <fieldset>
                <div class="row">
                  <div class="col-md-12">
                    <div class="panel with-nav-tabs panel-default" >
                      <div class="panel-heading">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab"
                              style="font-size:12px" aria-expanded="true"><b><i class="fa fa-file-text-o"
                                  aria-hidden="true"></i>INFORMACIÓN DE VIOLENCIA</b></a></li> 
                        </ul>
                      </div>
                      <div class="panel-body" >
                        <div class="tab-content">

                          <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                            <fieldset>
                              <div class="row">
                                <div class="col-md-2"><br />
                                  <label>TIPO DE VIOLENCIA</label><br />
                                  @foreach($violencia as $item)
                                  @if ($data_violencia->id_violencia == $item->id)
                                  {{$item->descripcion}}
                                  @endif
                                  @endforeach
                                </div>
                                <div class="col-md-2"><br />
                                  <label>MODALIDAD</label><br />
                                  @if($modoviolencia!='')
                                  @foreach($modoviolencia as $item2)
                                  @if ($data_violencia->id_modalidad == $item2->id_tipo_violencia)
                                  {{$item2->descripcion}}
                                  @endif
                                  @endforeach
                                  @endif
                                </div>
                                @if($data_violencia->id_violencia!=3)
                                <div class="col-md-3"><br />
                                  <label>COMISARIA</label><br />
                                  @foreach($comisaria as $item)
                                  @if ($data_violencia->id_comisaria == $item->id)
                                  {{$item->comisaria}}
                                  @endif
                                  @endforeach
                                  </select>
                                </div>
                                @endif
                                <div class="col-md-3"><br />
                                  <label>FEC. ACCIDENTE</label><br />
                                  <?php echo date("d/m/Y", strtotime($data_violencia->fecha_accidente)); ?>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3"><br />
                                  <label>HORA ACCIDENTE</label><br />
                                  {{ $data_violencia->hora_accidente }}
                                </div>
                                @if($data_violencia->id_violencia!=3)
                                <div class="col-md-3"><br />
                                  <label>DEPARTAMENTO</label><br />
                                  @foreach($departamento_violencia as $f)
                                  @if ($data_violencia->id_dpto == $f->id_departamento)
                                  {{$f->descripcion}}
                                  @endif
                                  @endforeach
                                </div>
                                <div class="col-md-3"><br />
                                  <label>PROVINCIA</label><br />
                                  @if($provincia_violencia!='')
                                  @foreach($provincia_violencia as $p)
                                  @if ($data_violencia->id_prov == $p->id_provincia)
                                  {{$p->descripcion}}
                                  @endif
                                  @endforeach
                                  @endif
                                </div>
                                <div class="col-md-3"><br />
                                  <label>DISTRITO</label><br />
                                  @if($distrito_violencia!='')
                                  @foreach($distrito_violencia as $d)
                                  @if ($data_violencia->id_dist == $d->id_distrito)
                                  {{$d->descripcion}}
                                  @endif
                                  @endforeach
                                  @endif
                                </div>
                                @endif
                              </div>
                              <div class="row">
                                <div class="col-md-6"><br />
                                  <label>LUGAR ACCIDENTE</label><br />
                                  {{ $data_violencia->lugar_accidente }}
                                </div>
                                @if($data_violencia->id_violencia!=3)
                                <div class="col-md-6"><br />
                                  <label>UNIDAD POLICIAL</label><br />
                                  @foreach($unidad as $item)
                                  @if ($data_violencia->id_unidad == $item->id)
                                  {{$item->descripcion_larga}}
                                  @endif
                                  @endforeach

                                </div>
                                @endif
                              </div>
                            </fieldset>
                          </div> 

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>   
              @endif 
              <!--#informacion de violencia-->

              <!--informacion de evacuados-->
              @if ($data_evacuado->id_evacuado !== null)
              <fieldset>
                <div class="row">
                  <div class="col-md-12">
                    <div class="panel with-nav-tabs panel-default" >
                      <div class="panel-heading">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab"
                              style="font-size:12px" aria-expanded="true"><b><i class="fa fa-file-text-o"
                                  aria-hidden="true"></i> INFORMACIÓN DEL EVACUADO</b></a></li> 
                        </ul>
                      </div>
                      <div class="panel-body" >
                        <div class="tab-content">

                          <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                            <fieldset>
                              <div class="row">
                                <div class="col-md-2"><br />
                                  <label>DEPARTAMENTO</label><br />
                                  @foreach($departamento as $f)
                                  @if ($data_evacuado->id_departamento == $f->id_departamento)
                                  {{$f->descripcion}}
                                  @endif
                                  @endforeach
                                </div>
                                <div class="col-md-2"><br />
                                  <label>NUMERO DE OFICIO</label><br />
                                  {{ $data_evacuado->numero_oficio }}
                                </div>
                                <div class="col-md-5"><br />
                                  <label>ESTABLECIMIENTO</label><br />
                                  @foreach($ipress as $item)
                                  @if ($data_evacuado->unidad_policial == $item->id)
                                  {{$item->nombre}}
                                  @endif
                                  @endforeach
                                  </select>
                                </div>

                                <div class="col-md-12"><br />
                                  <label>DIAGNOSTICO INICIAL</label><br />
                                  <table class="display" id="tblDiagnostico" cellspacing="0" width="100%"
                                    style="margin-top:5px;">
                                    <thead>
                                      <tr>
                                        <th width="85%"></th>
                                        <th width="15%"></th>
                                      </tr>
                                    </thead>

                                    <tbody> 
                                      @foreach($diagnostico_evacuado as $row)
                                      <tr>
                                        <td>
                                          <input type="hidden" value="{{ $row->id_diagnostico }}" name="receta_diagnositico_id"
                                            style="width : 1px; heigth : 1px;">
                                          <select name="id_diagnosticos['{{ $row->id_diagnostico }}]'" id=""
                                            class="select-2 input-diagnostico form-control" style=" background-color: white;"
                                            disabled>
                                            @foreach ($diagnostico_evacuado as $d ) 
                                            <option value="{{ $d->id_diagnostico }}" @if($d->id_diagnostico ==
                                              $row->id_diagnostico) selected="selected" id="diagnostico_ids" @endif>
                                              {{ $d->nombre_diagnostico }}
                                            </option>
                                            @endforeach
                                          </select>
                                        </td>
                                        <td>
                                          <select name="id_tipo_diagnosticos['{{ $row->id_diagnostico }}']" id=""
                                            class="input-diagnostico form-control" style=" background-color: white;" disabled>
                                            <option value="{{$row->id_tipo_diagnostico}}">{{ $row->descripcion_tipo_diagnostico
                                              }}</option>
                                          </select>
                                        </td>
                                      </tr>
                                      @endforeach 
                                    </tbody> 
                                  </table>
                                </div>

                                <div class="col-md-12"><br />
                                  <label>OBSERVACION EVACUADO</label><br />
                                  {{ $data_evacuado->observacion }}
                                </div>

                              </div>
                            </fieldset>
                          </div> 

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>  
              @endif 

              <div id="divFirmar">
                <input name="mov[]" id="mov[]" value="<?php echo $id_admision?>" type="hidden" />
                <input type="hidden" value="1" name="proviene_formulario" id="proviene_formulario">
                @if ($flag_pendiente_firma == 1)
                <input class="btn btn-info input-sm pull-right" value="FINALIZAR REGISTRO" type="button"  id="open"
                  onclick="firmarsintokens()" style="margin-left:20px" />
                @endif
                <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_triajes_atendidas_by_emergencia">
                  <span class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
              </div>
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
<input type="hidden" id="user_firma" value="{{ Auth::user()->dni }}" />
<input type="hidden" value="<?php echo $flag_firmar?>" name="flag_firmar" id="flag_firmar">
<input type="hidden" id="id_admision" value="{{ $id_admision }}" />
<div id="addComponent"></div>


<script> 
  $('#open').click(function(){// Cambiar a una nueva pestaña 
    var win = window.open('/PdfEmergenciaAdmision/{{ $id_admision }}', '_blank');  
  })
</script>




@include('frontend.includes.footer')
@endsection