@extends('frontend.layouts.master')
@section('renderjs')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.function_edad')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->

<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>

<script text="text/javascript">
  $(document).on('click', '#btnfirmar', function(e) {
    e.preventDefault();
    firmar();
    //validaRegistrarCitaPorMedico();
  });

  
  $(document).ready(function() {   
        calcular_edad_detallada();
    });  
    
    function calcular_edad_detallada(){
        var fecha_nac=$('#fecha_nacimiento_beneficiario').val();
        if(fecha_nac != null && fecha_nac != undefined && fecha_nac.length>8  ){
            var oldDate = fecha_nac; //20/04/1946 
            var values = oldDate.split("/");
            if(values.length==1){
                var values = oldDate.split("-");
                var dia = values[2];
                var mes = values[1];
                var ano = values[0];  
            }else{
                var dia = values[0];
                var mes = values[1];
                var ano = values[2];  
            } 
               
            calcularEdadPersona(ano,mes,dia,'label','edad_detallada','') 
  
        }
    }

  function firmar() {

  //var rowindex = $('#Grd2 tbody tr td').length;
  //$('#btnLiquidacionFirmarPdf').attr("disabled",true);
  //if (rowindex > 0) {

  $.ajax({
      url: "<?php echo url()?>/genera_emer_triaje_csv",
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

    if ($("#flag_firmar").val() !=0) {
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

  $.post("{{route('postArgumentsEmergenciaTriaje')}}", {
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
  var id_resol = $('#id_emergencia').val();
  $.post("{{route('actualiza_prestacion')}}", {
    _token: CSRF_TOKEN,
    id_tipo: "emergencia_triaje",
    id_resol: id_resol,
  }, function(data, status) {
          //location.reload();
	  location.href = '/all_triajes_atendidos';        
	  });
  }

  function MiFuncionCancel() {
  alert("El proceso de firma digital fue cancelado.");
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
    color: black !important; 
    font-size: 15px !important; 
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
<div class="container" style="width:90%;text-transform: uppercase;">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <div class="row">
              <div class="col-lg-8 col-md-8 col-sm-12">
                <h3 class="panel-title" style="padding-top: 5px;">MÓDULO DE TRIAJE DE EMERGENCIA</h3> 
              </div>
              <div class="col-lg-4 col-md-8 col-sm-12" style="text-align: right">
                <a href="/all_triajes_atendidos" class="btn btn-default btn-filter btn-sm">LISTA DE PACIENTES CON TRIAJE</a>
              </div>
            </div>  
          </div>
          <div class="panel-body">
            <form class="form-horizontal" method="post" id="frmBloque">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{ $data->id }}" name="id_emergencia" id="id_emergencia">
              <?php
                  $flag_firmar = 0;
                  if($flag_pendinte_firma == 1) {
                    $flag_firmar = 1;
                  }
                ?>
  
              <!--informacion de paciente-->
              <fieldset>
                <div class="col-md-12">
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important; font-size: 14px !important;">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0default" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-user" aria-hidden="true"></i> INFORMACIÓN DE ATENCIÓN</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body">
                      <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab0default">
                          <fieldset>

                            <div class="row" >
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">TIPO DE ATENCIÓN: </label> TRIAJE - EMERGENCIA 
                              </div>  
                              <div class="col-md-2">
                                <label style="padding-right: 10px;">F. REGISTRO: </label> <?php echo date("d/m/Y", strtotime($data->fecha_registro_emergencia_triaje)); ?>
                              </div>
                              <div class="col-md-2">
                                <label style="padding-right: 10px;">N° DE REGISTRO: </label> {{$data->secuencia }}
                              </div>
                              <div class="col-md-5">
                                <label for="tipo" style="padding-right: 10px;">ESTABLECIMIENTO: </label> <r style="text-transform: uppercase;">{{$nombre_establecimiento}}</r> 
                              </div>
                            </div>

                            <div class="row" >
                              <hr  style="margin: 10px !important;">
                              <div class="col-md-2">
                                <label>TIPO DE DOCUMENTO: </label><br>
                                <p>DNI</p>
                              </div>
                              <div class="col-md-2">
                                <label>N° DE DOCUMENTO: </label><br>
                                <p>{{$data->nro_dni}}</p>
                              </div>
                              <div class="col-md-4">
                                <label>PACIENTE: </label><br>
                                <p>{{$data->apellido_pat_paciente}} {{$data->apellido_mat_paciente}}
                                  {{$data->nombres_paciente}}</p>
                              </div>
                              <div class="col-md-1">
                                <label>SEXO: </label><br>
                                <p>{{$data->sexo}}</p>
                              </div>
                              <div class="col-md-1">
                                <label>F.NACIM: </label><br>
                                <p>{{$data->fecha_nac_paciente}}</p>
                                <input type="hidden" name="fecha_nacimiento_beneficiario" id="fecha_nacimiento_beneficiario"
                                class="form-control" value="{{$data->fecha_nac_paciente}}">
                              </div>
                              <div class="col-md-2">
                                <label>EDAD: </label><br> 
                                <p id="edad_detallada" name="edad_detallada"></p>
                              </div> 
                            </div>


                            <div class="row" >
                              <hr  style="margin: 10px !important;"> 
                              <div class="col-md-12">
                                <b style="font-size: 20px;">DATOS DEL TITULAR</b><br><br>
                              </div>
                              <div class="col-md-2">
                                <label>N° DE DOCUMENTO: </label><br>
                                <p>{{$data->nomtipdoctitular}} - {{$data->nrodoctitular}}</p>
                              </div>
                              <div class="col-md-4">
                                <label>PACIENTE: </label><br>
                                <p>{{$data->apepattitular}} {{$data->apemattitular}}
                                  {{$data->nomtitular}}</p>
                              </div>
                              <div class="col-md-2">
                                <label>CIP: </label><br>
                                <p>{{$data->ciptitular}}</p>
                              </div> 
                              <div class="col-md-4">
                                <label>GRADO: </label><br> 
                                <p>{{$data->gradotitular}}</p>
                              </div> 
                            </div>
                            

                            @if($padre_paciente !=null || $madre_paciente !=null)
                              <div class="row" >
                                <hr  style="margin: 10px !important;">
                                @if($padre_paciente !=null)
                                  <div class="col-md-6">
                                    <label style="padding-right: 15px;">PADRE RESPONSABLE:</label> 
                                    {{ $padre_paciente->nro_doc_ident }} - {{ $padre_paciente->paterno }} {{ $padre_paciente->materno }} {{ $padre_paciente->nombres }}<br> 
                                  </div>
                                @endif
                                @if($madre_paciente !=null)
                                  <div class="col-md-6">
                                    <label style="padding-right: 15px;">MADRE RESPONSABLE:</label> 
                                    {{ $madre_paciente->nro_doc_ident }} - {{ $madre_paciente->paterno }} {{ $madre_paciente->materno }} {{ $madre_paciente->nombres }}<br> 
                                  </div>
                                @endif
                              </div>
                             @endif

                          </fieldset>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <!--#informacion de paciente-->

              <!--informacion de Signos vitales-->
              <fieldset>
                <div class="col-md-12">
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important;  font-size: 14px !important; ">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0default" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-heartbeat" aria-hidden="true"></i> 
                              INFORMACIÓN DE SIGNOS VITALES</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body">
                      <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab0default">
                          <fieldset>
                            <div class="row">
                              <div class="col-md-2">
                                <label>TEMPERATURA (°C): </label><br>
                                <p>{{$data->temperatura}}</p>
                              </div>
                              <div class="col-md-2">
                                <label>TALLA (cm): </label><br>
                                <p>{{$data->talla_cm}}</p>
                              </div> 
                              <div class="col-md-2">
                                <label>PESO (Kg): </label><br>
                                <p>{{$data->peso_kg}}</p>
                              </div> 
                              <div class="col-md-2">
                                <label>IMC: </label><br>
                                @if($imc!='')
                                  <p>{{$imc}}</p>
                                @else
                                  <p>-</p>
                                @endif
                              </div> 
                              <div class="col-md-2">
                                <label>PESO IDEAL: </label><br>
                                @if($pesoIdeal!='')
                                  <p>{{$pesoIdeal}}</p>
                                @else
                                  <p>-</p>
                                @endif 
                              </div> 
                              <div class="col-md-2">
                                <label>Sa02 (%): </label><br>
                                <p>{{$data->sa02_porcentaje}}</p>
                              </div> 

                              @if ($data->es_gestante == 1) 
                                <div class="col-md-2">
                                  <label>ALTURA UTERINA (cm) </label><br>
                                  <p>{{$data->tamano_cintura_cm}}</p>
                                </div> 
                                <div class="col-md-2">
                                  <label>FREC. CARDIACA FETAL (/min): </label><br>
                                  <p>{{$data->frecuencia_cardiaca_fetal}}</p>
                                </div> 
                              @endif 

                              <div class="col-md-2">
                                <label>PRESIÓN ARTERIAL: </label><br>
                                <p>{{$data->presion_arterial_x}} / {{$data->presion_arterial_y}}</p>
                              </div>
                              <div class="col-md-2">
                                <label>FREC. RESPIRATORIA (/min): </label><br>
                                <p>{{$data->frecuencia_respiratoria}}</p>
                              </div>  
                              <div class="col-md-2">
                                <label>FREC. CARDIACA (/min): </label><br>
                                <p>{{$data->frecuencia_cardiaca}}</p>
                              </div>  
                              <div class="col-md-2">
                                <label>Fi02 (%): </label><br>
                                <p>{{$data->fi02}}</p>
                              </div>  
                            </div>
                          </fieldset>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </fieldset> 
              <!--#informacion de Signos Vitales-->


              


              <!--informacion de paciente-->
              <fieldset>
                <div class="col-md-12">
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important;  font-size: 14px !important;">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0default" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-file-text" aria-hidden="true"></i> INFORMACIÓN DEL INGRESO</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body">
                      <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab0default">
                          <fieldset>
                            <div class="row"> 
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">FORMA DE INGRESO: </label>  {{$data->descripcion_forma_ingreso}} 
                              </div> 
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">ESTADO DE INGRESO: </label> {{$data->descripcion_estado_ingreso}}
                              </div> 
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">MOTIVO DE INGRESO: </label> {{$data->descripcion_motivo_ingreso}} 
                              </div> 
                            </div>
                            <div class="row"> 
                              <hr  style="margin: 10px !important;">
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">PRIORIDAD: </label> {{$data->prioridad_nombre}}
                              </div> 
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">TÓPICO: </label> {{$data->topico_nombre}} 
                              </div> 
                              <div class="col-md-6">
                                <label style="padding-right: 10px;">MOTIVO DE LA CONSULTA: </label>  <r style="text-transform: uppercase;">{{$data->observacion_triaje}} </r> 
                              </div> 
                            </div>
                          </fieldset>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <!--#informacion de paciente-->  
              <div id="divFirmar">
                <input type="hidden" id="argumentos" value="" />
                <input type="hidden" id="doc_firmar" value="" />
                <input type="hidden" id="opt_firma" value="" />
                <input type="hidden" value="{{$data->flag_triaje}}" name="flag_firmar" id="flag_firmar">
                <input name="mov[]" id="mov[]" value="{{$data->id}}" type="hidden" /> 
 
                <a class="btn btn-info btn-sm hidden-print pull-right" id="btnfirmar" 
                  name="btnfirmar" href="/all_triajes_atendidos/"> 
                  <span class="fa fa-pencil"></span> IMPRIMIR Y  FIRMAR
                </a>

                  <a class="btn btn-primary btn-sm hidden-print pull-right" id="open"> 
                    <span class="fa fa-print"></span> IMPRIMIR SIN FIRMAR
                  </a> 

              </div> 
            </fieldset>
          </div>
        </div>
      </div>
    </div>

    </form>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>

<div id="addComponent"></div>

<script> 
  $('#open').click(function(){// Cambiar a una nueva pestaña 
    var win = window.open('/PdfEmergenciaTriaje/{{ $data->id }}', '_blank'); 
    win.focus(); 
    setTimeout(function() { 
      window.location.href = "/all_triajes_atendidos"; 
    }, 1000);
  })
</script>



@include('frontend.includes.footer')
@endsection