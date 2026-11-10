@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->

<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}

<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>

<script text="text/javascript">
  $(document).on('click', '#btnfirmar', function(e) {
    e.preventDefault();
    firmar();
    //validaRegistrarCitaPorMedico();
  });

  function enviarReceta(id) {

    $("#id_receta").val(id);

  }
  function firmar() {

  //var rowindex = $('#Grd2 tbody tr td').length;
  //$('#btnLiquidacionFirmarPdf').attr("disabled",true);
  //if (rowindex > 0) {

  $.ajax({
      url: "<?php echo url()?>/genera_interconsulta_rpta_csv",
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
        $("#opt_firma").val(`${resultado.opt}`);        
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

  $(document).ready(function () {

    if ($("#flag_firmar").val() == 0) {
    $("#btnfirmar").hide();
    } else {
      $("#btnfirmar").show();
    }

  });

  window.addEventListener('getArguments', async function(e) {
  await ObtieneArgumentosParaFirmaDesdeLaWeb();
  });

  window.addEventListener('invokerOk', function(e) {
  MiFuncionOkWeb();
  });

  window.addEventListener('invokerCancel', function(e) {
    MiFuncionCancel();
  });



  async function getArguments() {
  arg = document.getElementById("argumentos").value;
  await dispatchEventClient('sendArguments', arg);
}

  async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
  var CSRF_TOKEN = $('meta[name="_token"]').attr('content');

  $.post("{{route('postArguments')}}", {
    _token: CSRF_TOKEN,
    type: "W",
    documentName: $('#doc_firmar').val(),
    opt_firma: $('#opt_firma').val(),    
    entidad: <?=Auth::user()->dni?>
  }, function(data, status) {
    document.getElementById("argumentos").value = data;
    getArguments();
  });

  }

  async function MiFuncionOkWeb() {
  var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  var id_resol = $('#id_interconsulta').val();
  $.post("{{route('actualiza_interconsulta')}}", {
    _token: CSRF_TOKEN,
    id_tipo: "interconsulta_rpta",
    id_resol: id_resol,
  }, function(data, status) {
          //location.reload();
	  location.href = '/all_interconsultas';        
	  });
  }

  function MiFuncionCancel() {
  alert("El proceso de firma digital fue cancelado.");
  }
  
  function firmarsintokens() {
    let id_prestacion = $("#id_prestacion").val();
    let id_interconsulta = $("#id_interconsulta").val();
    let id_evaluacion = $("#id_evaluacion").val();
    let id_tipo_atencion = $("#id_tipo_atencion").val();

    // var batchSignatureProcessPoller = setInterval(function() {
      
      Swal.fire({
        html: '<h5 class="text-primary">FINALIZANDO...</h5>',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      $.ajax({
        url: "<?php echo url()?>/sinTokensInterconsultaRpta",
        type: "post",
        async: false,
        cache: false,
        dataType: 'json',
        data: $("#frmBloque").serialize(),
        success: function(completed) {
          Swal.close();

          if (completed) {
            location.href = '/all_interconsultas';
            //location.href = '/ver_atencion_interconsulta_rptas/' + id_prestacion + '/' + id_interconsulta + '/' + id_evaluacion + '/' + id_tipo_atencion;
            // clearInterval(batchSignatureProcessPoller);
            
          } else {
            bootbox.alert({
              title: '<i class="fa fa-exclamation-triangle text-danger"></i> Error',
              message: 'Ha ocurrido un error al procesar la solicitud.',
              size: 'small'
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.close();
          bootbox.alert({
            title: '<i class="fa fa-exclamation-triangle text-danger"></i> Error',
            message: 'Error en la conexión o en el servidor.',
            size: 'small'
          });
        }
      });

    //}, 3000);
  }

</script>
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

  $('input[name="parent"]').on("change", function(e) {
    getServicio();
  });

  $('#example tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  });


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
    width: 100%;
    height: 550px;
    overflow-x: hidden;
    overflow-y: auto;
    text-align: center;
    padding: 0px !important;
  }

  body {
    /* font-family: 'Montserrat', 'Lato', 'Open Sans', 'Helvetica Neue', Helvetica, Calibri, Arial, sans-serif;
    color: #6b7381;
    background: #f2f2f2; */
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
            <h3 class="panel-title">RESPUESTA INTECONSULTA MEDICA</h3>
          </div>
          <div class="panel-body">
            <form class="form-horizontal" method="post" id="frmBloque"
              action="">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{ $nivel }}" name="id_nivel" id="id_nivel">
              <input type="hidden" name="id_interconsulta" id="id_interconsulta" value="<?php echo $id_interconsulta?>">
              <input type="hidden" name="id_prestacion" id="id_prestacion" value="<?php echo $id_prestacion?>">
              <input type="hidden" name="nomsexo" id="nomsexo" value="<?php echo $interconsulta->sexo?>">
              <input type="hidden" id="id_evaluacion" value="{{ $id_evaluacion }}">
              <input type="hidden" id="id_tipo_atencion" value="{{ $interconsulta->id_tipo_atencion }}">

              <?php
                $flag_firmar = 0;
                if($flag_pendinte_firma == 1) {
                  $flag_firmar = 1;
                }
              ?>
              <div class="row">
                  <!-- Columna izquierda: Datos del Paciente -->
                <div class="col-md-6">
                  <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo"><b>DATOS DEL PACIENTE</b></h3>
                    </div>
                    <div class="panel-body">
                      <table class="table table-condensed table-borderless" style="width: auto; border: 0px solid;">
                        <tr>
                            <td style="text-align: left; border: 0px; width: 40%;"><strong>N° DE DOCUMENTO</strong></td>
                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                            <td style="border: 0px;"><b> {{ $interconsulta->tipo_doc_ident }} - {{ $interconsulta->nro_doc_ident }}</b></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; border: 0px;"><strong>N° HISTORIA CLÍNICA</strong></td>
                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                            <td style="text-transform: uppercase; border: 0px;"><b>{{ $prestacion->nro_historia }}</b></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; border: 0px;"><strong>APELLIDOS Y NOMBRES</strong></td>
                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                            <td style="text-transform: uppercase; border: 0px;"><b>{{ $interconsulta->asegurado_paterno }} {{ $interconsulta->asegurado_materno }}, {{ $interconsulta->asegurado_nombre }}</b></td>
                        </tr>
                        <?php
                          $fecha_nac = DateTime::createFromFormat('d/m/Y', $interconsulta->fecha_nac);
                          $hoy = new DateTime();
                          $edad_return = "";

                          if ($fecha_nac !== false) {
                            $edad = $fecha_nac->diff($hoy);
                            $edad_return = $edad->y . ' a ' . $edad->m . ' m y ' . $edad->d . ' d';
                          } else {
                            $edad_return = "Formato de fecha inválido";
                          }
                        ?>
                        <tr>
                            <td style="text-align: left; border: 0px;"><strong>FECHA DE NACIMIENTO</strong></td>
                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                            <td style=" border: 0px;">{{ $interconsulta->fecha_nac }} - <b>EDAD ({{ $edad_return }})</b>
                            </td>
                        </tr>
                        <tr>
                            <?php
                              $sexo = "";
                              switch ($interconsulta->sexo) {
                                case "F": $sexo = "FEMENINO"; break;
                                case "M": $sexo = "MASCULINO"; break;
                                default: $sexo = $interconsulta->sexo;
                              }
                            ?>
                            <td style="text-align: left; border: 0px;"><strong>SEXO</strong></td>
                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                            <td style="text-transform: uppercase; border: 0px;">{{ $sexo }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; border: 0px;"><strong>GRADO</strong></td>
                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                            <td style="text-transform: uppercase; border: 0px;">{{ $interconsulta->grado }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; border: 0px;"><strong>TIPO BENEFICIARIO</strong></td>
                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                            <td style="text-transform: uppercase; border: 0px;">{{ $interconsulta->parentesco }}</td>
                        </tr>
                        <!-- <tr>
                            <td style="text-align: left; border: 0px;"><strong>CONDICIÓN</strong></td>
                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                            <td style="text-transform: uppercase; border: 0px;"></td>
                        </tr> -->
                      </table>
                    </div>
                  </div>
                </div>
                
                <!-- Columna derecha: Datos Generales -->
                <div class="col-md-6">
                  <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo"><b>DATOS GENERALES DE LA ATENCIÓN</b></h3>
                    </div>
                    <div class="panel-body">
                      <table class="table table-condensed table-borderless" style="width: auto; border: 0px solid;">
                        <tr>
                          <td style="text-align: left; border: 0px; width: 40%;"><strong>COD.SITEDS SUSALUD</strong></td>
                          <td style="text-align: left; border: 0px;">:</td>                        
                          <td style="text-align: left; border: 0px;">{{ $interconsulta->cod_siteds }}</td>
                        </tr>
                        <tr>
                          <td style="text-align: left; border: 0px;"><strong>FECHA DE ATENCIÓN</strong></td>
                          <td style="text-align: left; border: 0px;">:</td>
                          <td style="text-align: left; border: 0px;">{{ date('d/m/Y H:i:s', strtotime($interconsulta->fecha_rpta_atencion)) }}</td>
                        </tr>
                        <?php
                        
                          $ubicacion = "";

                          if (intval($interconsulta->id_tipo_atencion) === 2): // Emergencia
                            
                            $ubicacion = $datos_ubicacion["emergencia_cuarto"] . " / " . $datos_ubicacion["emergencia_cama"];

                          elseif (intval($interconsulta->id_tipo_atencion) === 3): // Hospitalizacion

                            $ubicacion = $datos_ubicacion["hospitalizacion_servicio_fisico"] . " / " . $datos_ubicacion["hospitalizacion_cama"];

                          endif;
                        ?>
                        <tr>
                          <td style="text-align: left; border: 0px;"><strong>TIPO ATENCIÓN</strong></td>
                          <td style="text-align: left; border: 0px;">:</td>
                          <td style="text-align: left; border: 0px;"><b>{{ $interconsulta->tipo_atencion }} / INTERCONSULTA</b></td>
                        </tr>
                        <tr>
                          <td style="text-align: left; border: 0px;"><strong>SERVICIO</strong></td>
                          <td style="text-align: left; border: 0px;">:</td>
                          <td style="text-align: left; border: 0px;">{{ $interconsulta->servicio }}</td>
                        </tr>
                        <tr>
                          <td style="text-align: left; border: 0px;"><strong>UBICACIÓN DE PACIENTE</strong></td>
                          <td style="text-align: left; border: 0px;">:</td>
                          <td style="text-align: left; border: 0px;">{{ $ubicacion }}</td>
                        </tr>
                        <tr>
                          <td style="text-align: left; border: 0px;"><strong>PROFESIONAL DE SALUD</strong></td>
                          <td style="text-align: left; border: 0px;">:</td>
                          <td style="text-align: left; border: 0px;">{{ $interconsulta->documento_tr }} - {{ $interconsulta->medico_paterno_tr }} {{ $interconsulta->medico_materno_tr }}, {{ $interconsulta->medico_nombre_tr }} <b>({{ $interconsulta->profesion }})</b>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fin nuevo diseño -->

                <div class="panel panel-info panel-bordered panel-sm" style="display:none">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL PACIENTE</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                    <div class="form-group">
                      <div class="col-md-4">
                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $interconsulta->tipo_doc_ident?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $interconsulta->nro_doc_ident?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">SEXO</label>
                        <p><?php echo $interconsulta->sexo?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $interconsulta->asegurado_paterno?></p>
                      </div>

                      <div class="col-md-4">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $interconsulta->asegurado_materno?></p>
                      </div>

                      <div class="col-md-4">
                        <label>NOMBRES</label>
                        <p><?php echo $interconsulta->asegurado_nombre?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label>FECHA DE NACIMIENTO</label>
                        <p><?php echo $interconsulta->fecha_nac?></p>
                      </div>
                      <div class="col-md-4">
                        <label>PARENTESCO &nbsp;</label>
                        <p><?php echo $interconsulta->parentesco?></p>
                      </div>

                      <div class="col-md-4">
                        <label>GRADO &nbsp;</label>
                        <p><?php echo $interconsulta->grado?></p>
                      </div>
                    </div>
                  </div>
                </div>
              <!-- </fieldset> -->
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DIAGNOSTICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblDiagnostico2" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="20%">Codigo</th>
                          <th width="60%">Nombre</th>
                          <th width="20%">Tipo</th>
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
              					?>
                        <tr>
                          <td><?php echo $row->codigo?></td>
                          <td><?php echo $row->nombre?></td>
                          <td><?php echo $tipo_diagnostico?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PROCEDIMIENTOS MÉDICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblProcedimientoM" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="20%">Codigo</th>
                          <th width="60%">Nombre</th>
                          <th width="20%">Cantidad</th>
                        </tr>
                      </thead>
                      <tfoot>
                      </tfoot>
                      <tbody>
                        <?php foreach($procedimiento as $row):?>
                        <tr>
                          <td><?php echo $row->codigo?></td>
                          <td><?php echo $row->nombre?></td>
                          <td><?php echo $row->cantidad?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </fieldset>
              <fieldset id="fsInterconsulta">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>RESPUESTA DE LA INTERCONSULTA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <br />
                    <div style="clear:both"></div>
                    <fieldset>
                      <div class="col-md-12">
                        <!-- MOSTRAR INTERCONSULTAS---->
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="form-group"> 
                            <div class="panel-body">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  {!! nl2br(e($interconsulta->evaluacion)) !!}
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </fieldset>
              <fieldset style="display:none">
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
                                <p>DNI</p>
                              </div>
                              <div class="col-md-4">
                                <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                                <p><?php echo $interconsulta->documento_tr?></p>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-4">
                                <label>APELLIDO PATERNO</label>
                                <p><?php echo $interconsulta->medico_paterno_tr?></p>
                              </div>
                              <div class="col-md-4">
                                <label>APELLIDO MATERNO</label>
                                <p><?php echo $interconsulta->medico_materno_tr?></p>
                              </div>
                              <div class="col-md-4">
                                <label>NOMBRES</label>
                                <p><?php echo $interconsulta->medico_nombre_tr?></p>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2">
                                <label>N° COLEGIATURA</label>
                                <p><?php echo $interconsulta->colegiatura_medico_tr?></p>
                              </div>
                              <div class="col-md-2">
                                <label>N° RNE</label>
                                <p><?php echo $interconsulta->tr_rne?></p>
                              </div>
                              <div class="col-md-4">
                                <label>PROFESION</label>
                                <p><?php echo $interconsulta->profesion?></p>
                              </div>
                              <div class="col-md-4">
                                <label>ESPECIALIDAD</label>
                                <p><?php echo $interconsulta->especializacion?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                              
              <!-- Inicio Receta vale -->
              <fieldset id="fsRecetaVale">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PRODUCTOS MEDICOS</b>
                    </div>
                  </div>
                  <div class="panel-body panel-body-receta">
                    <?php foreach($receta as $rowReceta):
                      if ($rowReceta->fecha_firma == "") {
                        $flag_firmar = 1;
                      }
                      $model_productos_receta = new \App\Models\Producto_receta;
                      $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);
                      //$medicamentos_indicaciones = $model_productos_receta->ShowMedicinasById_receta_vale_combinacion_evaluacion($rowReceta->id,$rowReceta->id_evaluacion);
                    ?>
                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                      <?php if($rowReceta->estado=="2"){?>
                      <a data-toggle="modal" data-target="#squarespaceModal"
                        onclick="enviarReceta(<?php echo $rowReceta->id?>)" class="btn btn-danger">
                        <i class="fa fa-xing"></i> Invalidar Receta
                      </a>
                      <?php }else{ ?>
                        <button class="btn btn-danger" disabled="disabled"><i class="fa fa-xing"></i> Invalidar Receta</button>
                      <?php } ?>
                    </div>
                    <br />
                    <div style="clear:both"></div>
                    <fieldset>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label class="col-md-2" style="padding-left:0px">RECETA VALE N°</label>
                              <div class="col-md-1">
                                <p><?php echo $rowReceta->nro_receta; ?></p>
                              </div>
                              <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                              <div class="col-md-2">
                                <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                              </div>
                              <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                              <div class="col-md-4" style="padding:0px">
                                <p><?php echo $rowReceta->farmacia; ?></p>
                              </div>
                            </div>
                          </div>
                          <div class="table-responsive">
                          <table class="table table-condensed display" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th width="15%">Codigo</th>
                                <th width="30%">Producto farmacéutico o dispositivo médico</th>
                                <th width="15%">Unidad Medida</th>
                                <th width="10%">Tiempo(Dias)</th>
                                <th width="15%">Posologia</th>
                                <th width="10%">Via Administración</th>
                                <th width="15%">Cantidad Prescrita</th>
                                <th width="15%">Diagnosticos</th>
                              </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <?php foreach($medicamentos as $row):?>
                            <tr>
                              <td><?php echo $row->codigo?></td>
                              <td><?php echo $row->nombre?>
                                <?php 
                                  if($row->codigo=='59075700'):
                                    echo "(".$row->cantidad_prescrita."L)";
                                  endif; 
                                ?>
                              </td>
                              <td><?php echo $row->abreviatura?></td>
                              <td><?php echo $row->tiempo_periodo?></td>
                              <td><?php echo $row->posologia?></td>
                              <td><?php echo $row->via?></td>
                              <td><?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                          echo $row->cantidad_prescrita/1000;
                                        else:
                                          echo $row->cantidad_prescrita;
                                  endif; ?>
                              </td>
                              <td><?php
                                    $model_productos_receta = new \App\Models\Producto_receta;
                                    $diagnostico_id = $row->id_diagnosticos;
                                    $diagnostico_nombre = $model_productos_receta->get_diagnostico_nombre($diagnostico_id);
                                    echo $diagnostico_nombre; ?>    
                              </td>
                            </tr>
                            <?php endforeach;?>
                            <tbody>
                            </tbody>
                          </table>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    <?php endforeach;?>
                    <!-- <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                      <a onclick="agregarReceta()" id="btnNuevoReceeta" flag="0" class="btn btn-success">Agregar Receta</a>
                    </div>
                    <div style="clear:both"></div> -->
                    <fieldset id="fsNuevaRecetaVale" style="display:none">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-2">
                              <label>FEC. EXPEDICIÓN (A)</label>
                              <input type="text" class="form-control datepicker"
                                value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_expedicion"
                                id="fecha_expedicion" tabindex="2" autocomplete="off" />
                            </div>
                            <div class="col-md-5">
                              <label>FARMACIA</label>
                              <select class="form-control" name="id_farmacia_receta" id="id_farmacia_receta"
                                style="width:100%" onchange="getProducto()">
                                <option value="">-Seleccionar-</option>
                                @if($prestacion->id_establecimiento!=76)
                                  <?php foreach($farmacias as $row):?>
                                    <option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
                                  <?php endforeach;?>
                                  @else
                                        <option value="128">Farmacia Dosis Unitaria PIP I del Hospital Nacional PNP Luis N. Saenz</option>
                                        <option value="21">Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                                  @endif

                              </select>
                            </div>
                          </div>
                          <br />
                          <table>
                            <tr>
                              <td>
                                <button type="button" onclick="AddProducto()" class="btn btn-primary">Agregar Producto</button>
                              </td>
                            </tr>
                          </table>
                          <br />
                          <div style="display: none">
                            <select class="form-control" id="cmbMedicamentoTemp" tabindex="16" style="width: 500px">
                              <option value="">Seleccionar Producto</option>
                                @foreach($producto as $p)
                                  <option value="{{$p->id}}" ru="{{$p->ru}}">{{$p->codigo}} - {{$p->nombre}},
                                  {{$p->abreviatura}}</option>
                                @endforeach
                            </select>
                          </div>
                          <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                          <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th>Producto farmacéutico o dispositivo médico</th>
                                <th style="width:10%">Stock Farmacia</th>
                                <th>Tiempo(Dias)</th>
                                <th> Via Adm. </th>
                                <th>Posologia</th>
                                <th> Cant. Pres.</th>
                                <th> DiagnosticoxProducto </th>
                                <th>  </th>
                                <th>  </th>
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
                </div>
              </fieldset>
              <!-- Fin Recetas vale -->

              <!--  -->
              <div class="form-group">
                <div class="col-md-12">   
                  <fieldset id="fsExamenLab">
                    <div class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title text-center">
                            <b>EXAMEN DE LABORATORIO</b>
                        </div>
                      </div>
                      <div class="panel-body">
                        <?php 
                        foreach($laboratorio as $rowLaboratorio):
                          if ($rowLaboratorio->fecha_firma == "") {
                            $flag_firmar_lab = 1;
                          }
                          $model_prestacion = new \App\Models\Prestaciones;
                          //$proclaboratorio = $model_prestacion->GetPrestacionLaboratorio($id_prestacion, $rowLaboratorio->id);
                          $proclaboratorio = $model_prestacion->GetPrestacionLaboratorioEvaluacion2($id_prestacion, $rowLaboratorio->id, $id_evaluacion);
                        ?>
                          <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                            <?php if($rowLaboratorio->estado=="1"){?>
                            <a data-toggle="modal" data-target="#squarespaceModal2"
                              onclick="enviarLaboratorio(<?php echo $rowLaboratorio->id?>)" class="btn btn-danger">
                            <i class="fa fa-xing"></i> Invalidar Examen</a>
                            <?php }else{ ?>
                                <button class="btn btn-danger" disabled="disabled"><i class="fa fa-xing"></i> Invalidar Examen</button>
                            <?php } ?>
                          </div>
                          <br/>
                          <div style="clear:both"></div>
                          <div class="form-group">
                            <div class="col-md-12">
                              <label class="col-md-2" style="padding-left:0px">N° ORDEN</label>
                              <div class="col-md-3">
                                <p><?php echo $id_prestacion.' - '. $rowLaboratorio->id; ?></p>
                              </div>
                              <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                              <div class="col-md-3">
                                <p><?php echo date("d-m-Y", strtotime($rowLaboratorio->created_at)); ?></p>
                              </div>
                            </div>
                          </div>
                          <!-- <div class="form-group">
                          <div class="col-md-12"> -->
                            <fieldset>
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-body">
                                  <div class="form-group">
                                    <div class="col-md-12">
                                      <div class="table-responsive">
                                      <table class="table table-condensed display" cellspacing="0" width="100%">
                                        <thead>
                                          <tr>
                                            <th width="5%">Código</th>
                                            <th width="45%">Nombre</th>
                                            @if (!$rowLaboratorio->has_observacion)
                                              <th width="30%">Observación</th>
                                            @endif
                                            <th width="10%">Cantidad</th>
                                            <th width="10%">Diagnóstico</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($proclaboratorio as $row):?>
                                          <tr>
                                            <td><?php echo $row->codigo?></td>
                                            <td><?php echo $row->nombre?></td>
                                            @if (!$rowLaboratorio->has_observacion)
                                              <td><?php echo $row->observacion?></td>
                                            @endif
                                            <td><?php echo $row->cantidad?></td>
                                            <td><?php echo $row->codigo_diagnostico?></td>
                                          </tr>
                                          <?php endforeach;?>

                                          @if ($rowLaboratorio->has_observacion)
                                            <?php
                                              $colspan = "4";
                                              $hora_toma_muestra = "";
                                              switch (intval($rowLaboratorio->id_hora_toma_muestra)) {
                                                case 0: $hora_toma_muestra = "OTROS"; break;
                                                case 1: $hora_toma_muestra = "05:00 AM"; break;
                                                case 2: $hora_toma_muestra = "09:00 AM"; break;
                                                case 3: $hora_toma_muestra = "16:00 PM"; break;
                                                case 4: $hora_toma_muestra = "22:00 PM"; break;
                                              }
                                            ?>
                                            <tr>
                                              <td colspan="{{ $colspan }}">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td colspan="{{ $colspan }}"><b>Observación:</b> {{$hora_toma_muestra}} - {{ $rowLaboratorio->observacion }}</td>
                                            </tr>
                                          @endif
                                        </tbody>
                                      </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </fieldset>
                        <?php 
                        endforeach;
                        ?>
                        <!-- <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                          <a onclick="agregarExamenLab()" id="btnNuevoExamenLab" flag="0" class="btn btn-success">Examen Laboratorio</a>
                        </div>
                        <div style="clear:both"></div> -->
                        <fieldset id="fsNuevoExamenLab" style="display:none">
                          <div class="panel-body">
                            <table>
                              <tr>
                                <td>
                                  <button type="button" id="addExamenLab2" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addClassModal">Agregar Examen</button>
                                </td>
                              </tr>
                            </table>
                            <br />
                            <table class="display" id="tblExamenLab" cellspacing="0" width="100%" style="margin-top:5px;">
                              <thead>
                                <tr>
                              <th width="45%">Examen</th>
                              <th width="18%">Observacion</th>
                              <th width="10%">Cantidad</th>
                              <th width="10%">Diagnosticos</th>
                              <th></th>
                                </tr>
                              </thead>
                              <tfoot>
                              </tfoot>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
              <!-- Fin de orden laboratorio  -->

              <!-- Inicio de orden de imagenes -->
              <fieldset id="fsExamenRad">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>EXAMEN DE IMAGENOLOGIA</b>
                    </div>
                  </div>
                  <div class="panel-body">

                    <?php 
                    foreach($radiologia as $rowRadiologia):

                        if ($rowRadiologia->fecha_firma == "") {
                            $flag_firmar_lab = 1;
                        }

                      $model_procedimiento = new \App\Models\Procedimiento();
                      $procradiologia = $model_procedimiento->GetProcedimientosByIdRadiologia($rowRadiologia->id);
                    ?>

                      <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                        <?php if($rowRadiologia->estado=="1"){?>
                        <a data-toggle="modal" data-target="#squarespaceModal3"
                          onclick="enviarRadiologia(<?php echo $rowRadiologia->id?>)" class="btn btn-danger">
                          <i class="fa fa-xing"></i> Invalidar Examen
                        </a>
                        <?php }else{ ?>
                        <button class="btn btn-danger" disabled="disabled"><i class="fa fa-xing"></i> Invalidar
                          Examen</button>
                        <?php } ?>
                      </div>
                      <br />
                      <div style="clear:both"></div>
                      <div class="form-group">
                        <div class="col-md-12">
                          <label class="col-md-2" style="padding-left:0px">N° ORDEN</label>
                          <div class="col-md-3">
                            <p><?php echo $id_prestacion.' - '. $rowRadiologia->id; ?></p>
                          </div>
                          <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                          <div class="col-md-3">
                            <p><?php echo date("d-m-Y", strtotime($fechaServidor)); ?></p>
                          </div>
                        </div>
                      </div>
                      <br />
                      <div style="clear:both"></div>                    
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div class="table-responsive">
                                <table class="table table-condensed display" cellspacing="0" width="100%">
                                  <thead>
                                    <tr>
                                      <th width="5%">Codigo</th>
                                      <th width="40%">Nombre</th>
                                      <th width="10%">Comparativo</th>
                                      <th width="10%">Lado</th>
                                      <th width="15%">Observacion</th>
                                      <th width="5%">Cantidad</th>
                                      <th width="10%">Diagnostico</th>
                                      <th width="10%">Radiología Portátil</th>
                                    </tr>
                                    </tr>
                                  </thead>
                                  <tfoot>
                                  </tfoot>
                                  <tbody>
                                    <?php foreach($procradiologia as $row):?>
                                    <tr>
                                      <td>{{ $row->examen_codigo_personalizado }}</td>
                                      <td>{{ $row->examen_nombre_personalizado }}</td>
                                      <td><?php if($row->comparativo_radio!=null):
                                                  if($row->comparativo_radio==1):
                                                    echo "SI";
                                                  else:
                                                    echo "NO";
                                                  endif;
                                                endif;
                                          ?>
                                      </td>
                                      <td><?php 
                                          if($row->lado_radio!=null):
                                            if($row->lado_radio==1):
                                              echo "Izquierdo";
                                            elseif($row->lado_radio==2):
                                              echo "Derecho";
                                            elseif($row->lado_radio==3):
                                              echo "Ambos";
                                            endif;
                                          endif;
                                          ?>
                                      </td>
                                      <td><?php if($row->observacion!='0'):
                                                  echo $row->observacion;
                                                endif;
                                          ?>
                                      </td>
                                      <td>{{ $row->cantidad }}</td>
                                      <td>{{ $row->diagnostico_codigo }}</td>
                                      <td>{{ $row->radiologia_portatil }}</td>
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
                    <?php 
                    endforeach;
                    ?>
                    <!-- <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                      <a onclick="agregarExamenRad()" id="btnNuevoExamenRad" flag="0" class="btn btn-success">Examen de Imagenología</a>
                    </div>
                    <div style="clear:both"></div> -->
                    <fieldset id="fsNuevoExamenRad" style="display:none">
                      <div class="panel-body">
                        <table>
                          <tr>
                            <td>
                              <button type="button" id="addExamenRad2" class="btn btn-primary" data-toggle="modal"
                                data-target="#addClassModal">Agregar Examen</button>
                            </td>
                          </tr>
                        </table>
                        <br />
                        <table class="display" id="tblExamenRad" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                            <tr>
                            <th width="35%">Nombre</th>
                            <th width="3%">Comparativo</th>
                            <th width="3%">Lado</th>
                            <th width="20%">Observacion</th>
                            <th width="5%">Cantidad</th>
                            <th width="20%">Diagnostico</th>
                            <th width="14%">Radiología Portátil</th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </fieldset>
              <!-- Fin de orden de imagenes -->
              
              <!-- Datos para centro quirurgico  -->    
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center" style="position: relative">
                      <b>REQUIERE INTERVENCIÓN QUIRÚRGICA:</b>
                       @if($existe_intervencion_cqx) <b>SI</b> @else <b>NO</b> @endif
                    </div>
                  </div>
                  @if($existe_intervencion_cqx)
                  <div class="panel panel-info panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center"><b>INFORMACIÓN QUIRÚRGICA</b></div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-md-2">
                          <label><b>TURNOS QUIRURGICOS :</b>   {{isset($datos_intervencion->turno)?$datos_intervencion->turno:''}}  </label>
                        </div>
                        <div class="col-md-2">
                          <label><b>CLASIFICACIÓN DE CIRUGÍA:</b> {{isset($datos_intervencion->clasificacion_cirugia)?$datos_intervencion->clasificacion_cirugia:''}}</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-2">
                          <label><b>TIPO DE CIRUGIA :</b>  {{isset($datos_intervencion->tipo_cirugia)?$datos_intervencion->tipo_cirugia:''}}   </label>
                        </div>
                         @if($datos_intervencion->emergencia_tipo_cirugia === 1)
                        <div class="col-md-3">
                          <label><b>FECHA DE PROGRAMACIÓN:</b> {{!empty($datos_intervencion->fecha_probable)?date('d/m/Y',strtotime($datos_intervencion->fecha_probable)):''}}</label>
                        </div>
                        @endif
                        <div class="col-md-2">
                          <label><b>TIEMPO DE DURACION:</b> {{ isset($datos_intervencion->emergencia_duracion_cirugia) ? floor($datos_intervencion->emergencia_duracion_cirugia / 60) . 'h ' . ($datos_intervencion->emergencia_duracion_cirugia % 60) . 'm' : '' }}</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group"><br>
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
                    </div>
                  </div>
                  @endif
              </fieldset>
              <!-- Fin datos para centro quirurgico  --> 
              <div>
                <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_interconsultas"> <span
                    class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
              </div>
              <div id="divFirmar">
                <input type="hidden" id="argumentos" value="" />
                <input type="hidden" id="doc_firmar" value="" />
                <input type="hidden" id="opt_firma" value="" />
                <input type="hidden" value="{{$flag_firmar}}" name="flag_firmar" id="flag_firmar">

                <input name="mov[]" id="mov[]" value="{{$id_interconsulta}}" type="hidden" /> 
                @if ($flag_firmar == 1)
                  @if (Auth::user()->status_token == 1)
                    <input id="btnFinalizar" class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button" onclick="firmarsintokens()" style="margin-left:20px"/>
                  @else
                    <a class="btn btn-info btn-sm hidden-print pull-right" id="btnfirmar" name="btnfirmar" href="/all_triajes_atendidos/"> <span class="fa fa-pencil"></span> FIRMAR</a>
                  @endif
                @endif
              </div> <!-- .#divFirmar -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<div id="addComponent"></div>
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span
            class="sr-only">Close</span></button>
        <h3 class="modal-title" id="lineModalLabel">Invalidar Receta</h3>
      </div>
      <div class="modal-body">
        <!-- content goes here -->
        <form class="" method="post" action="{{ route('invalidar_receta_digital')}}" id="">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de esta receta</label>
            <input type="text" class="form-control" id="motivo" name="motivo" placeholder="" required="">
            <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia"
              value="{{Auth::user()->id_farmacia}}">
            <input type="hidden" class="form-control" id="id_receta" name="id_receta"
              value="<?php //echo $prestacion->id_receta?>">
            <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id_prestacion" id="id_prestacion" value="<?php echo $id_prestacion?>">
          </div>
          <div class="form-group">
            <input id="btnInvalidar" class="btn btn-danger" type="submit" value="Invalidar">
          </div>
        </form>

      </div>

    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection
