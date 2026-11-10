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
<link rel="stylesheet" href="{{ asset('sceditor/minified/themes/office.min.css') }}" id="theme-style" />
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
  .size-titulo {
        font-size: 1.2rem;
        font-weight: 700;
        text-align: center;
    }

    .title-dialog {
    font-size: 18px;
    font-family:Arial, Helvetica, sans-serif;
  }
  
  label {
    font-size: 11px;
    display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333333;
  }

  p{
    font-size:12px;
    /* display: inline-block; */
    max-width: 100%;
    margin-bottom: 5px;
  }


</style>

<script>
  //<![CDATA[

function firmar() {
  
  $.ajax({
      url: "<?php echo url()?>/genera_csv_imagenologia_adicional",
      type: "post",
      data: $("#frmBloque").serialize(),
      dataType: 'json'
    })
    .done(function(resultado) {
      if (resultado.sw) {
        $("#doc_firmar").val(`${resultado.csv}.7z`);
        initInvoker('W');
      } else {
        //$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
        //dialogError(function () {});
      }
      $('#btnLiquidacionFirmarPdf').attr("disabled", false);

    })
    .fail(function(jqXHR, textStatus) {
      console.log("Request failed: " + textStatus)
    })
    .always(function() {
      //bootbox.hideAll();
    });
}


var documentName_ = null;
//
window.addEventListener('getArguments', async function(e) {
  await ObtieneArgumentosParaFirmaDesdeLaWeb();
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




async function MiFuncionOkWeb() {
  const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  const id_resol = $('#id_radiologia').val();
  const id_pp = $('#id_pp').val();
  const id_servicio_radiologia = $('#id_servicio_radiologia').val();
  const id_informe_adicional = $('#id_informe_adicional').val();
  $.post("{{route('registra_firma_informe_imagenologia_adicional')}}", {
    _token: CSRF_TOKEN,
    id_tipo: "imagenologia",
    id_resol: id_resol,
    id_pp: id_pp,
    id_servicio_radiologia : id_servicio_radiologia,
    id_informe_adicional : id_informe_adicional
  }, function(data, status) {
    location.href = '/all_radiologia_prescritas_by_ipress';
  });
}

function MiFuncionCancel() {
  alert("El proceso de firma digital fue cancelado.");
}
//]]>

function firmarsintokens() {
  //var batchSignatureProcessPoller = setInterval(function() {
    $.ajax({
      url: "<?php echo url()?>/sinTokensRadiologiaAdicional",
      type: "post",
      cache: false,
      dataType: 'json',
      data: $("#frmBloque").serialize(),
      success: function(completed) {
        if (completed) {
          location.href = '/all_radiologia_prescritas_by_ipress';
          //clearInterval(batchSignatureProcessPoller);
        }
      }
    });
  //}, 3000);
  
}

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

  // $('#fecha_atencion').datepicker();


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
    font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
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

  .label-th {
    font-size: 11px;
    color: #333333;
    text-align: left;
    border: 0px solid;
  }
  .label-td{
    font-size: 11px;
  }
  .table-borderless tbody > tr > td,.table-borderless tbody > tr > th{
  
  padding: 4px 8px !important;
  border: 0px solid;
  }
  .table-borderless{
     margin-bottom: 0px !important;
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

<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE INFORME DE IMAGENOLOGIA</h3>
          </div>
          <div class="panel-body">
            <form class="form-horizontal" method="post" id="frmBloque"
              action="#">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{ $id_pp }}" name="id_pp" id="id_pp">
              <input type="hidden" value="{{ $radiologia->id }}" name="id_radiologia" id="id_radiologia">
              <input type="hidden" value="{{ $id_informe_adicional }}" name="id_informe_adicional" id="id_informe_adicional">
              <?php
              $flag_firmar = 0;
              if($flag_pendiente_firma == 1) {
                $flag_firmar = 1;
              }
              ?>
              <div class="row">
                <!-- Columna izquierda: Datos del Paciente -->
                <div class="col-md-6" style="height: 100%;">
                    <div class="panel panel-info" style="height: 100%;">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
                        </div>
                        <div class="panel-body" style="margin: 0px 10px;">
                            <div class="row">
                                <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                    <tr>
                                        <th class="label-th" style="text-align: left; border: 0px solid;width: 40%;"><strong>N° DE DOCUMENTO</strong></th>
                                        <th class="label-th" style="font-size: 11px;color: #333333;text-align: left;border: 0px solid;"><strong>:</strong></th>
                                        <input id="nro_dni_paciente" type="hidden" value="{{$prestacion->nro_doc_ident}}" />
                                        <td class="label-td" style="border: 0px solid;"><span id="nro_doc_ident" style="font-weight: bold; font-size: 11px;"> {{$prestacion->tipo_doc_ident}} - <?php  echo  isset($prestacion->nro_doc_ident) ? $prestacion->nro_doc_ident : ''; ?></span></td>

                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>N° H.C</strong></th>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                        <td class="label-td" style="text-transform: uppercase;border: 0px solid;">
                                            <span id="nro_historia" style="font-weight: bold;"><?php echo isset($prestacion->nro_historia ) ? $prestacion->nro_historia  : ''; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                        <td class="label-td" style="text-transform: uppercase;border: 0px solid;">
                                            <span id="nro_historia" style="font-weight: bold;"><?php  echo (isset($prestacion->asegurado_paterno) ? $prestacion->asegurado_paterno : '') . " " . 
                                                        (isset($prestacion->asegurado_materno) ? $prestacion->asegurado_materno : '') . ", " . 
                                                        (isset($prestacion->asegurado_nombre) ? $prestacion->asegurado_nombre : ''); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>FECHA DE NACIMIENTO</strong></th>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                        <td class="label-td" style="border: 0px solid;">
                                            <span id="nro_historia">
                                            <?php  echo isset($prestacion->fecha_nac) ? $prestacion->fecha_nac : ''; ?>
                                            - <strong>EDAD (
                                                                <?php
                                                    $fecha_nac = DateTime::createFromFormat('d/m/Y', $prestacion->fecha_nac);
                                                    $hoy = new DateTime();

                                                    if ($fecha_nac !== false) {
                                                        $edad = $fecha_nac->diff($hoy);
                                                        echo $edad->y . ' a ' . $edad->m . ' m y ' . $edad->d . ' d';
                                                    } else {
                                                        echo "Formato de fecha inválido";
                                                    }
                                                    ?>
                                                )</strong>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>SEXO</strong></th>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                        <td class="label-td" style="text-transform: uppercase;border: 0px solid;">
                                            <span id="nro_historia">
                                                <?php echo isset($prestacion->sexo) ? ($prestacion->sexo == "F" ? "Femenino":"Masculino") : ''; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>TIPO BENEFICIARIO</strong></th>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                        <td  class="label-td" style="text-transform: uppercase;border: 0px solid;">
                                            <span id="nro_historia">
                                                <?php echo isset($prestacion->parentesco) ? $prestacion->parentesco : ''; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>CONDICIÓN</strong></th>
                                        <th class="label-th" style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                        <td class="label-td" style="text-transform: uppercase;border: 0px solid;">
                                            <span id="nro_historia">
                                                <?php echo ($prestacion->referido == 'SI') ? 'REFERIDO' : 'NO REFERIDO'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Columna derecha: Datos Generales -->
                <div class="col-md-6">
                    <div class="panel panel-info contenedor-datos-generales">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">DATOS GENERALES DE LA ATENCIÓN</h3>
                        </div>
                        <div class="panel-body" style="margin: 0px 10px;">
                            <table class="table table-borderless" style="width: 100%;MARGIN: 0px;">
                                <tr>
                                    <th class="label-th"><strong>COD.SITEDS SUSALUD</strong></th>
                                    <th class="label-th">:</th>                        
                                    <td class="label-td" style="text-transform: uppercase;">
                                        @if (isset($radiologia->cod_siteds_susalud))
                                            <span id="cod_siteds_susalud">{{ $radiologia->cod_siteds_susalud }}</span>
                                        @else
                                            <span class="cod_siteds_susalud"></span>
                                            <input type="hidden" class="form-control" id="cod_siteds_susalud" name="cod_siteds_susalud" onkeypress="return esEntero(event);" style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important" maxlength="12" pattern="[0-9]*">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-th" style="width: 35%;"><strong>TIPO ATENCIÓN</strong></th>
                                    <th class="label-th" style="width: 1%;">:</th>
                                    <td class="label-td">
                                        <?php
                                            switch ($prestacion->id_tipo_atencion) {
                                            case '1':$tipo_atencion = "AMBULATORIO"; break;
                                            case '2':$tipo_atencion = "EMERGENCIA"; break;
                                            case '3':$tipo_atencion = "HOSPITALIZACION"; break;
                                            case '4':$tipo_atencion = "CENTRO QUIRURGICO"; break;
                                            case '5':$tipo_atencion = "SERVICIO NUTRICIONAL"; break;
                                            }
                                        ?>
                                        <?php
                                            if ($prestacion->id_tipo_atencion == 2 && $radiologia->id_evaluacion > 0){
                                                $tipo_atencion = "EMERGENCIA - EVALUACION";                                                                    
                                            }elseif($prestacion->id_tipo_atencion == 3 && $radiologia->id_evaluacion > 0){
                                                $tipo_atencion = "HOSPITALIZACION - EVOLUCION";
                                            }else{
                                                $tipo_atencion = $prestacion->tipo_atencion;
                                            }
                                                echo '<span id="tipo_atencion" style="font-weight: bold;">' . $tipo_atencion . '</span>';
                                        ?>
                                    </td>
                                </tr>
                        
                                @if($id_tipo_atencion== 2)
                                  <tr>
                                      <th class="label-th" style="color:red;"><strong>PRIORIDAD</strong></th>
                                      <th class="label-th" style="color:red;">:</th>
                                      <td class="label-td" style="text-transform: uppercase;color:red;">
                                          <span id="prioridad"><?php echo isset($prestacion->prioridades) ? $prestacion->prioridades : ''; ?></span>
                                      </td>
                                  </tr>
                                @endif
                                <tr>
                                    <th class="label-th"><strong>SERVICIO DE PROCEDENCIA</strong></th>
                                    <th class="label-th">:</th>
                                    <td  class="label-td" style="text-transform: uppercase;">
                                        <span id="servicio"><?php echo isset($prestacion->servicio) ? $prestacion->servicio : ''; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-th"><strong>FECHA ATENCIÓN</strong></th>
                                    <th class="label-th">:</th>
                                    <td class="label-td">                                                                                                
                                        @if($id_tipo_atencion == 1)
                                            <span id="fecha_atencion"><?php echo isset($prestacion->fecha_atencion) ? date('d/m/Y', strtotime($prestacion->fecha_atencion)) : ''; ?></span>
                                        @elseif($id_tipo_atencion == 2)
                                            @if(!empty($prestacion->id_evaluacion))
                                                <span id="fecha_atencion"><?php echo isset($prestacion->fecha_evaluacion) ? date('d/m/Y', strtotime($prestacion->fecha_evaluacion)) : ''; ?></span>
                                            @else
                                                <span id="fecha_atencion"><?php echo isset($prestacion->fecha_atencion) ? date('d/m/Y', strtotime($prestacion->fecha_atencion)) : ''; ?></span>
                                            @endif
                                        @elseif($id_tipo_atencion == 3)
                                            @if(!empty($evolucion->id_evaluacion))
                                                <span id="fecha_atencion"><?php echo isset($evolucion->fecha_evolucion) ? date('d/m/Y', strtotime($evolucion->fecha_evolucion)) : ''; ?></span>
                                            @else
                                                <span id="fecha_atencion"><?php echo isset($prestacion->fecha_atencion) ? date('d/m/Y', strtotime($prestacion->fecha_atencion)) : ''; ?></span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @if($id_tipo_atencion == 2 || $id_tipo_atencion==3)
                                <tr>
                                    <th class="label-th"><strong>UBICACIÓN DE PACIENTE</strong></th>
                                    <th class="label-th">:</th>
                                    <td class="label-td">
                                        <span id="ubicacion" style="font-weight: bold;"><?php echo isset($prestacion->ubicacion) ? $prestacion->ubicacion : ''; ?> / <strong> CAMA: <?php echo isset($prestacion->nmro_cama) ? $prestacion->nmro_cama : ''; ?> </strong> </span>
                                    </td>
                                </tr>
                                @endif
                                
                                <tr>
                                    <th class="label-th"><strong>PROFESIONAL DE SALUD</strong></th>
                                    <th class="label-th">:</th>
                                    <td style="text-transform: uppercase; font-size:11px">
                                        <span id="dni_medico"><b></b><strong>{{$prestacion->descripcion}} -</strong></th>
                                        <?php echo isset($prestacion->dni) ? $prestacion->dni : ''; ?>
                                        - <?php 
                                            echo (isset($prestacion->medico_paterno) ? $prestacion->medico_paterno : '') . " " . 
                                                (isset($prestacion->medico_materno) ? $prestacion->medico_materno : '') . ", " . 
                                                (isset($prestacion->medico_nombre) ? $prestacion->medico_nombre : ''); 
                                        ?>
                                        (<?php echo isset($prestacion->profesion) ? $prestacion->profesion : ''; ?>)
                                        / <b>N.Coleg: </b>{{$prestacion->colegiatura}}
                                    
                                        </span>
                                    </td>
                                </tr>                                                    
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row" >
              <!-- Columna izquierda: Datos del Paciente -->
              <div class="col-md-6" style="height: 100%;">
                <div class="panel panel-info" style="height: 100%;">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">PROFESIONAL QUE REGISTRA EXAMEN</h3>
                  </div>
                  <div class="panel-body" style="margin: 0px 10px;">
                      <div class="row">
                        <table class="table table-borderless" style="width: auto; border: 0px solid;">
                          <tr>
                              <th class="label-th" style="text-align: left; border: 0px solid;width: 40%;"><strong>N° DE DOCUMENTO</strong></th>
                              <th class="label-th" style="font-size: 11px;color: #333333;text-align: left;border: 0px solid;"><strong>:</strong></th>
                              <input id="nro_dni_paciente" type="hidden" value="{{$prestacion->nro_doc_ident}}" />
                              <td class="label-td" style="border: 0px solid;">
                                <span id="nro_doc_ident" style="font-weight: bold; font-size: 11px;"> 
                                  <?php 
                                    if ( $procradiologia[0]->created_nrodoc_digitador_radiologia !== null && $procradiologia[0]->created_nrodoc_digitador_radiologia !== '' ) {
                                      echo $procradiologia[0]->created_nrodoc_digitador_radiologia;
                                    }else{
                                        echo "";
                                    }
                                  ?>
                                </span></td>
                          </tr>
                          <tr>
                              <th class="label-th" style="text-align: left; border: 0px solid;width: 40%;"><strong>APELLIDOS Y NOMBRES</strong></th>
                              <th class="label-th" style="font-size: 11px;color: #333333;text-align: left;border: 0px solid;"><strong>:</strong></th>
                              <td class="label-td" style="border: 0px solid;"><span  id="nro_historia"> 
                                <?php 
                                  if ( $procradiologia[0]->created_nombre_digitador_radiologia !== null && $procradiologia[0]->created_nombre_digitador_radiologia !== '' ) {
                                  echo $procradiologia[0]->created_nombre_digitador_radiologia;
                                  }else{
                                      echo "" ;
                                  }
                                ?>
                              </span></td>
                          </tr>
                        </table>
                      </div>
                  </div>
                </div>
                </div>
                <!-- Columna derecha: Datos Generales -->
                <div class="col-md-6">
                    <div class="panel panel-info contenedor-datos-generales">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">MÉDICO RESPONSABLE DEL INFORME</h3>
                        </div>
                        <div class="panel-body" style="margin: 0px 10px;">
                            <table class="table table-borderless" style="width: 100%;MARGIN: 0px;">
                                <tr>
                                    <th class="label-th" style="text-align: left; border: 0px solid;width: 40%;"><strong>N° DE DOCUMENTO</strong></th>
                                    <th class="label-th" style="font-size: 11px;color: #333333;text-align: left;border: 0px solid;"><strong>:</strong></th>
                                    <input id="nro_dni_paciente" type="hidden" value="{{$prestacion->nro_doc_ident}}" />
                                    <td class="label-td" style="border: 0px solid;">
                                      <span id="nro_doc_ident" style="font-weight: bold; font-size: 11px;"> 
                                        <?php 
                                          if ( $procradiologia[0]->dni_nombre_validador_adicional !== null && $procradiologia[0]->dni_nombre_validador_adicional !== '' ) {
                                            //echo $procradiologia[0]->created_nrodoc_registrador_informe;
                                            echo $procradiologia[0]->dni_nombre_validador_adicional;
                                            
                                          }else{
                                              echo Auth::user()->dni;
                                          }
                                        ?>
                                      </span></td>
                                </tr>
                                <tr>
                                    <th class="label-th" style="text-align: left; border: 0px solid;width: 40%;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                    <th class="label-th" style="font-size: 11px;color: #333333;text-align: left;border: 0px solid;"><strong>:</strong></th>
                                    <td class="label-td" style="border: 0px solid;"><span id="nro_historia" > 
                                      <?php 
                                        if ( $procradiologia[0]->nombre_validador_adicional !== null && $procradiologia[0]->nombre_validador_adicional !== '' ) {
                                              //echo $procradiologia[0]->created_nombre_registrador_informe;
                                              echo $procradiologia[0]->nombre_validador_adicional;  
                                        }else{
                                            echo Auth::user()->paterno . ' ' . Auth::user()->materno . ', ' . Auth::user()->name;
                                        }
                                      ?>
                                    </span></td>
                                </tr>
                        </table>
                      </div>
                  </div>
                </div>
              </div>
            </div>
              <!-- <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                    <h3 class="panel-title size-titulo">INFORMACIÓN DEL PACIENTE</h3>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="col-md-3">
                          <label>TIPO ATENCION</label></br>
                          <p>
                          <-?php
                              switch ($prestacion->id_tipo_atencion) {
                                case '1': echo $tipo_atencion = "AMBULATORIO"; break;
                                case '2': echo $tipo_atencion = "EMERGENCIA"; break;
                                case '3': echo $tipo_atencion = "HOSPITALIZACION"; break;
                                case '4': echo $tipo_atencion = "CENTRO QUIRURGICO"; break;
                                case '5': echo $tipo_atencion = "SERVICIO NUTRICIONAL"; break;
                              }
                          ?>
                          </p>
                        </div>
                        <div class="col-md-3" >
                            <label for="tipo">TIPO DE DOCUMENTO</label><br/>
                            <p>{{$prestacion->tipo_doc_ident}}</p>
                        </div>
                        <div class="col-md-3" >
                            <label for="dni_beneficiario">N° DE DOCUMENTO</label><br/>
                            <p>{{$prestacion->nro_doc_ident}}</p>
                        </div>
                        <div class="col-md-3" >
                            <label>FECHA DE NACIMIENTO</label><br/>
                            <p >{{$prestacion->fecha_nac}}</p>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-3" >
                          <label>APELLIDO PATERNO</label><br/>
                          <p >{{$prestacion->asegurado_paterno}}</p>
                        </div>
                        <div class="col-md-3" >
                          <label>APELLIDO MATERNO</label><br/>
                          <p >{{$prestacion->asegurado_materno}}</p>
                        </div>
                        <div class="col-md-3" >
                          <label>NOMBRES</label><br/>
                          <p >{{$prestacion->asegurado_nombre}}</p>
                        </div>
                        <div class="col-md-3" >
                          <label>NRO HC</label><br/>
                          <p >{{$prestacion->nro_historia}}</p>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-3" >
                          <label>PARENTESCO</label><br/>
                          <p >{{$prestacion->parentesco}}</p>
                        </div>
                        <div class="col-md-3" >
                          <label>GRADO</label><br/>
                          <p >{{$prestacion->grado}}</p>
                        </div>
                        <div class="col-md-3" >
                            <label>SEXO &nbsp;</label><br/>
                            <p>{{$prestacion->sexo}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>EDAD</label></br>
                          <p>{{$prestacion->edadpaciente}}</p>
                        </div>
                      </div>
                    </div>
                  </div>
              </fieldset>

              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                    <h3 class="panel-title size-titulo">PROFESIONAL QUE SOLICITA EL PROCEDIMIENTO</h3>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="col-md-3" >
                            <label>SERVICIOS</label><br/>
                            <p >{{$prestacion->servicio}}</p>
                        </div>
                          <div class="col-md-3" >
                            <label>MEDICO</label><br/>
                            <p ><-?php echo $prestacion->medico_paterno." ".$prestacion->medico_materno.", ".$prestacion->medico_nombre ?></p>
                          </div>
                          <div class="col-md-3" >
                            <label>N° ORDEN</label><br/>
                            <p ><-?php echo $radiologia->id?></p>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>    
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <h3 class="panel-title size-titulo">
                       PROFESIONAL QUE REGISTRA EXAMEN
                      </h3>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="col-md-3" >
                          <label>NRO DOC</label><br/>
                          <p ><-?php 
                           if ( $procradiologia[0]->created_nrodoc_digitador_radiologia !== null && $procradiologia[0]->created_nrodoc_digitador_radiologia !== '' ) {
                            echo $procradiologia[0]->created_nrodoc_digitador_radiologia;
                          }else{
                              echo "";
                          }
                          ?></p>
                        </div>
                        <div class="col-md-5" >
                          <label>NOMBRE Y APELLIDOS</label><br/>
                          <p ><-?php 
                          if ( $procradiologia[0]->created_nombre_digitador_radiologia !== null && $procradiologia[0]->created_nombre_digitador_radiologia !== '' ) {
                            echo $procradiologia[0]->created_nombre_digitador_radiologia;
                            }else{
                                echo "" ;
                            }
                          ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>    
              </fieldset>              
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <h3 class="panel-title size-titulo">
                        RESPONSABLE DEL REGISTRO
                      </h3>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="col-md-3" >
                          <label>NRO DOC</label><br/>
                          <p ><-?php 
                            if ( $procradiologia[0]->dni_nombre_validador_adicional !== null && $procradiologia[0]->dni_nombre_validador_adicional !== '' ) {
                              //echo $procradiologia[0]->created_nrodoc_registrador_informe;
                              echo $procradiologia[0]->dni_nombre_validador_adicional;
                              
                            }else{
                                echo Auth::user()->dni;
                            }
                          ?></p>
                        </div>
                        <div class="col-md-5" >
                          <label>NOMBRE Y APELLIDOS</label><br/>
                          <p ><-?php 
                            if ( $procradiologia[0]->nombre_validador_adicional !== null && $procradiologia[0]->nombre_validador_adicional !== '' ) {
                                  //echo $procradiologia[0]->created_nombre_registrador_informe;
                                  echo $procradiologia[0]->nombre_validador_adicional;  
                            }else{
                                echo Auth::user()->paterno . ' ' . Auth::user()->materno . ', ' . Auth::user()->name;
                            }
                          ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>    
              </fieldset> -->
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <h3 class="panel-title size-titulo">DIAGNÓSTICO(S)</h3>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="table-responsive">
                          <?php if (isset($existe_cie) && $existe_cie == 1): ?>
                          <select class="form-control" name="id_diagnostico" id="id_diagnostico" required="">
                              <option value="{{$cie->id}}">{{$cie->codigo}} - {{$cie->nombre}}</option>
                          </select>
                          <?php else: ?>
                          <div id="entregar_receta" >
                              <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                                  <thead>
                                      <tr>
                                          <th class="col-lg-6"><label>CÓDIGO - NOMBRE <label></th> <!-- Columna "Nombre y Código" ahora ocupa la mitad del ancho -->
                                          <th class="col-lg-6"><label>TIPO DE DX <label></th><!-- Columna "Descripción" también ocupa la mitad del ancho -->
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                        // Filtrar diagnósticos duplicados, priorizando los de tipo "Definitivo"
                                        $diagnostico_filtrado = [];

                                        foreach ($diagnostico as $dx) {
                                            $key = $dx->codigo." - ".$dx->nombre;

                                            if (isset($diagnostico_filtrado[$key])) {
                                                if (strtolower($dx->descripcion) === 'definitivo' &&
                                                    strtolower($diagnostico_filtrado[$key]->descripcion) !== 'definitivo') {
                                                    $diagnostico_filtrado[$key] = $dx;
                                                }
                                            } else {
                                                $diagnostico_filtrado[$key] = $dx;
                                            }
                                        }
                                      ?>
                                      <?php foreach($diagnostico_filtrado as $row):?>
                                      <tr>
                                          <td class="col-lg-6">
                                              <p><?php echo $row->codigo." - ".$row->nombre?></p>
                                          </td>
                                          <td class="col-lg-6">
                                              <p><?php echo $row->descripcion?></p>
                                          </td>
                                      </tr>
                                      <?php endforeach;?>
                                      <!-- <-?php foreach($diagnostico as $row):?>
                                      <tr>
                                          <td class="col-lg-6">
                                              <p><-?php echo $row->codigo." - ".$row->nombre?></p>
                                          </td>
                                          <td class="col-lg-6">
                                              <p><-?php echo $row->descripcion?></p>
                                          </td>
                                      </tr>
                                      <-?php endforeach;?> -->
                                  </tbody>
                              </table>
                          </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <h3 class="panel-title size-titulo">EXÁMENES SOLICITADOS</h3>
                    </div>
                  </div>
                  <div class="panel-body">
                  <!--?php  dd($diagnostico) ?-->
                    <div class="col-md-12">
                        <div class="form-group">
                          <!--div class="col-md-3"-->
                          <div class="table-responsive">
                            <div class="panel-body analisis-clinico">
                              <!--table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;"-->
                              <table id="tblDiagnostico" class="display" cellspacing="0" style="width: 170rem;">
                                  <thead>
                                  <!-- <tr>
                                    <th width="100%"></th>
                                  </tr> -->
                                  <thead>
                                    <tr>
                                        <th style="text-align: center; width: 25rem;"><label>NOMBRE DE EXAMEN<label></th>
                                        <th style="text-align: center; width: 5.5rem;"><label>ESTADO<label></th>
                                        <th style="text-align: center; width: 5.5rem;"><label>CANTIDAD<label></th>
                                        <th style="text-align: center; width: 5.5rem;"><label>LADO<label></th>
                                        <th style="text-align: center; width: 5.5rem;"><label>COMPARATIVO<label></th>
                                        <th style="text-align: center; width: 6rem;"><label>DX<label></th>
                                        <th style="text-align: center; width: 20rem;"><label>OBSERVACIÓN<label></th>
                                        <th style="text-align: center; width: 5rem;"><label>RADIOLOGÍA PORTATIL<label></th>
                                    </tr>
                                </thead>
                                <!--<tfoot>
                                </tfoot> -->
                                <tbody>
                                <!-- <-?php foreach($diagnostico as $row):?>
                                  <tr>
                                    <td>
                                      <p><-?php echo $row->codigo." - ".$row->nombre?></p>
                                    </td>
                                  </tr>
                                <-?php endforeach;?> -->
                                  <?php 
                                  $i=0;
                                  foreach($procradiologia as $row):
                                  ?>
                                      <tr>
                                          <td >
                                            <input type="checkbox" name="id_pres_proc[]"  @if ($row->confirmar_toma == '0' || $row->confirmar_toma == '3') checked @endif value="<?php echo $row->id ?>" onChange="seleccionGrupo('<?php echo $row->id ?>',this);" class="id_pres_proc" data-id="{{ $row->id }}" onClick="return false;" ><span>{{" "}}</span><span class="checkboxtexto" style="display: inline-block;">&nbsp;&nbsp;&nbsp;<p><?php echo $row->codigo." - ".$row->nombre?></p></span>
                                          </td>
                                          <td style="text-align: center;">
                                            <!-- Campo de "Estado" aquí -->
                                            <?php if ($row->confirmar_toma == '0' || $row->confirmar_toma == '3') { ?>
                                                <p><?php echo "REGISTRADO"?></p>
                                            <?php } else { ?>
                                                <p><?php echo "SR"?></p>
                                            <?php } ?>
                                          </td>
                                          <td style="padding-left: 1rem;text-align: center;">
                                            <p><?php echo($row->cantidad)?></p>
                                          </td>
                                          <td style="padding-left: 1rem;text-align: center;">
                                            <p><?php 
                                                switch($row->lado_radio){ 
                                                    case 0 : echo ""; break;
                                                    case 1 : echo "Izquierdo"; break;
                                                    case 2 : echo "Derecho"; break;  
                                                }
                                            ?></p>
                                          </td>
                                          <td style="padding-left: 1rem;text-align: center;">
                                            <p><?php 
                                              switch($row->comparativo_radio){ 
                                                  case 0 : echo ""; break;
                                                  case 1 : echo "SI"; break;
                                                  case 2 : echo "NO"; break;  
                                              }
                                            ?></p>
                                          </td>
                                          <td style="padding-left: 1rem;text-align: center;">
                                            <p><?php echo($row->diagnostico)?></p>
                                          </td>
                                          <td style="padding-left: 1rem;">
                                            <p><?php echo($row->observacion)?></p>
                                          </td>
                                          <td style="padding-left: 1rem;text-align: center;">
                                            <p><?php echo($row->radiologia_portatil)?></p>
                                          </td>
                                      </tr>
                                  <?php 
                                      $i++;
                                  endforeach;
                                  ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
                    <!-- <div class="col-sm-12 col-md-12">
                       <div class="col-md-12" style="text-align: left; font-size: 18px">
                        DIAGNOSTICO <br/><br/>
                      </div> 
                      <div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                        <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                            <tr>
                              <th width="100%"></th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                          <-?php foreach($diagnostico as $row):?>
                            <tr>
                              <td>
                                <p><-?php echo $row->codigo." - ".$row->nombre?></p>
                              </td>
                            </tr>
                          <-?php endforeach;?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div> -->
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm" style="font-size: 12px">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                    <h3 class="panel-title size-titulo">PÁRRAFO</h3>
                    </div>
                  </div>
                  <div class="panel-body">
                    <?php if($total_informe == 1): ?>
                      <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                          <div class="col-md-12">
                            <!-- <div class="col-md-12" style="text-align: left; font-size: 18px">
                              PARRAFO <br/><br/>
                            </div> -->
                            {!!$procradiologia[0]->informe_parrafo!!} 
                          </div>
                        </div>
                      </div>
                    <?php else:  //cuando tiene mas opciones   ?>
                      <div class="row col-md-12">
                        <br/>
                        <?php 
                        $i=1; $a=0; 
                        foreach($procradiologia as $row):
                            $i++;
                        ?>
                        <div class="col-sm-12 col-md-12">
                            <br/>
                            <p><b><?php echo $row->codigo." - ".$row->nombre?></b></p>
                            <br/>
                        </div>
                        <div class="col-sm-12 col-md-12">
                          <div class="form-group">
                              <div class="col-md-12"><br/>
                                  <div class="col-md-12" style="text-align: left; font-size: 18px">
                                    PÁRRAFO {{$i-1}} <br/><br/>
                                  </div>
                                  {!!$row->informe_parrafo!!}
                              </div>
                          </div>
                        </div>  
                        <?php 
                          endforeach;
                        ?>    
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </fieldset>
              <!--#informacion de paciente-->
              <div id="divFirmar">
                
                <input name="mov[]" id="mov[]" value="<?php echo $radiologia->id?>" type="hidden" />

                <input type="hidden" value="1" name="proviene_formulario" id="proviene_formulario">
                <input type="hidden" value="<?php echo $id_servicio_radiologia; ?>" name="id_servicio_radiologia" id="id_servicio_radiologia">
                @if ($flag_pendiente_firma == 1)
                    @if (Auth::user()->status_token!==1)
                        <input class="btn btn-info input-sm pull-right" value="FIRMAR" type="button" id="btnfirmar" style="margin-left:20px" />
                    @else
                        <input <?php if($radiologia->ruta_radiologia=="" || Auth::user()->tipo_registro == 6) : echo disabled; endif; ?> class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button" onclick="firmarsintokens()" style="margin-left:20px" />
                    @endif
                @else
                  <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_radiologia_prescritas_by_ipress"> <span
                    class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>   
                @endif
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
<input type="hidden" id="opt" value="7" />
<input type="hidden" id="user_firma" value="{{ Auth::user()->dni }}" />
<input type="hidden" value="<?php echo $flag_firmar?>" name="flag_firmar" id="flag_firmar">
<input type="hidden" id="id_imagenologia" value="{{ $prestacion->id }}" />
<div id="addComponent"></div>





@include('frontend.includes.footer')
@endsection
