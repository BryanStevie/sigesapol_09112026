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
        .grafico-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Espacio entre gráficos */
            justify-content: center; /* Centrar los gráficos */
            padding: 20px;
        }
        .grafico-item {
            width: 45%; /* Ancho de cada gráfico */
            height: 300px; /* Alto de cada gráfico */
        }
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
#grafico {
            width: 600px;
            height: 400px;
            margin: 0 auto;
        }
</style>

<script>

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
            function toggleDetalleOxi() {
                if ($('input[name="opcion"]:checked').val() === '1') {
                    $('#detalle_oxi').show();
                } else {
                    $('#detalle_oxi').hide();
                }
            }

            // Initial state
            toggleDetalleOxi();

            // Event listeners
            $('input[name="opcion"]').change(toggleDetalleOxi);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const presionArteriax = document.getElementById('presionarteriax');
            const presionArteriaY = document.getElementById('presionarteriay');
            const presionArteriaxMedia = document.getElementById('presionarteriax_media');

            function calcularPresionArterialMedia() {
                const pas = parseFloat(presionArteriax.value);
                const pad = parseFloat(presionArteriaY.value);
                
                if (!isNaN(pas) && !isNaN(pad) && pad <= pas) {
                    const pam = (pas + (pad * 2)) / 3;
                    presionArteriaxMedia.value = pam.toFixed(2);
                } else {
                    presionArteriaxMedia.value = '';
                }
            }

            // Attach event listeners to the inputs
            presionArteriax.addEventListener('input', calcularPresionArterialMedia);
            presionArteriaY.addEventListener('input', calcularPresionArterialMedia);
        });
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

  $('#addreferencia').on('click', function() {
                var addreferencia = $('#addreferencia').attr("aria-pressed");
                console.log(addreferencia)
                $('#referencia_activo').val(addreferencia);
                $("#divreferencia").hide();
                if (addreferencia == "false") {
                    $('#referencia_activo').val(addreferencia);
                    $("#divreferencia").show();
                    $("#divcontrareferencia").hide();
                    $('#addcontrareferencia').prop('disabled', true);
                } else {
                    $('#addcontrareferencia').prop('disabled', false);
                }
            });

            $( "#guarda" ).click(function() {

               $('#frm').submit();

            });


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
 .form-group-spacing {
        margin-bottom: 20px; 
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
            <h3 class="panel-title">LISTA DE SIGNOS VITALES</h3>
                      </div>

          <div class="panel-body">

            <form id="frm" action="{{route('guardar_edicion_signos_vitales')}}" method="post">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="diasTranscurridos" id="diasTranscurridos" value="{{$diasTranscurridos}}">
              <input type="hidden" name="id_signo_vital" id="id_signo_vital" value="{{$id_signo_vital}}">
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>

                  <legend>
                    <b>DATOS DE LA ATENCI&Oacute;N ENFERMERIA</b>
                  </legend>

                  <div>

                    <div class="form-group">

                      <div class="col-md-4">
                        <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                        <p>EMERGENCIA</p>
                      </div>

                      <div class="col-md-4">
                        <label for="dni_beneficiario">FECHA DE INGRESO</label>
                        <p><?php echo $dato_cabecera_jhon[0]->fecha_registro_ingreso?></p>
                      </div>
                  
                      <div class="col-md-4">
                        <label for="nombre_ipres">AREA</label>
                        <p><?php echo $dato_cabecera_jhon[0]->servicio?></p>
                      </div>

                      <div class="col-md-4">
                        <label for="nombre_ipres">PABELLON</label>
                        <p><?php echo $dato_cabecera_jhon[0]->pabellon?></p>
                      </div>

                      <div class="col-md-4">
                        <label for="nombre_ipres">CUARTO</label>
                        <p><?php echo $dato_cabecera_jhon[0]->cuarto?></p>
                      </div>

                      <div class="col-md-4">
                        <label for="nombre_ipres">CAMA</label>
                        <p><?php echo $dato_cabecera_jhon[0]->cama?></p>
                      </div>

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
  
                      <div class="col-md-3">
                        <label for="dni_beneficiario">TIPO DE DOCUMENTO</label>
                        <p><?php echo $datos_nota_ingreso[0]->nomtipdoctitular?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $dato_cabecera_jhon[0]->dni?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">SEXO</label>
                        <p><?php   
                            if ($dato_cabecera_jhon[0]->sexo == 'M') {
                                echo 'MASCULINO';
                            } else {
                                echo 'FEMENINO';
                            }
                            ?></p>
                      </div>

                      <div class="col-md-3">
                        <label>N° HISTORIA CLINICA</label>
                        <p><?php echo $dato_cabecera_jhon[0]->historia_clinica?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <label>NOMBRES Y APELLIDOS</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_nombre . ', ' . $dato_cabecera_jhon[0]->asegurado_paterno . ' ' . $dato_cabecera_jhon[0]->asegurado_materno; ?></p>

                      </div>
                      <div class="col-md-3">
                        <label>FECHA DE NACIMIENTO</label>
                          <p><?php echo $dato_cabecera_jhon[0]->asegurado_fecha_nacimiento?></p>
                      </div>

                      <div class="col-md-3">
                        <label>EDAD</label>
                          <p><?php echo $dato_cabecera_jhon[0]->asegurado_edad_anios . ' años, ' . $dato_cabecera_jhon[0]->asegurado_edad_meses . ' meses, ' .$dato_cabecera_jhon[0]->asegurado_edad_dias . ' días. '?></p>
                      </div>
                    </div>

                    <div class="form-group">
                  
                      <div class="col-md-4">
                        <label>PARENTESCO &nbsp;</label>
                          <p><?php echo $dato_cabecera_jhon[0]->asegurado_tipo_beneficiario?></p>
                      </div>
                      
                      <div class="col-md-4">
                        <label>GRADO &nbsp;</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_grado?></p>
                      </div>

                      <div class="col-md-4">
                        <label>CIP &nbsp;</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_carnet?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label>TIPO DE SANGRE &nbsp;</label>
                          <p><?php echo $dato_cabecera_jhon[0]->asegurado_tipo_sangre?></p>
                      </div>
                      
                      <div class="col-md-4">
                        <label>ALERGIA &nbsp;</label>
                        <p><?php  
                            if ($antecedentes->id_alergia == '1') {
                                    echo 'SI';
                                } else {
                                    echo 'NO';
                                }
                          ?></p>
                      </div>
                    @if($antecedentes->id_alergia == '1')
                      <div class="col-md-4">
                        <label>DETALLE DE ALERGIA &nbsp;</label>
                        <p><?php echo $antecedentes->descripcion_alergia?></p>
                      </div>
                      @endif
                    </div>

                  </div>
                </div>
             </fieldset>


              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>  SIGNOS VITALES </b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                        <div class="col-md-12 form-group-spacing">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label><center> TIPO ATENCION</center></label></br>
                                        <div class="col-md-12">
                                          <select name="tipo_atencion" id="tipo_atencion" disabled class="form-control">
                                              <option value="1">EMERGENCIA</option>
                                          </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>TURNO</center></label></br>
                                        <div class="col-md-12"><input value="{{$dato_turno}}" disabled type="number" name="diacddia" id="diacddia" placeholder="<?php echo $dato_turno ;?>" class="form-control"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>DIAS DE ESTANCIA</center></label></br>
                                        <div class="col-md-12"><input value="{{$diasTranscurridos}}" disabled type="number" name="diancia" id="diancia" placeholder="" class="form-control"></div>
                                    </div>
                                </div>
                                <br>
                         </div>
                        <div class="col-md-12 form-group-spacing">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label><center>PRESIÓN ARTERIAL (MM/HG)</center></label></br>                                        
                                        <div class="col-md-6"><input value="<?php echo $prsionx ;?>" disabled type="number" name="presiax" id="presiax" placeholder="presion arterial x" class="form-control"></div>
                                        <div class="col-md-6"><input value="<?php echo $prsiony ;?>" disabled type="number" name="presiy" id="presiy" placeholder="presion arterial y" class="form-control"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >FREC. RESPIRATORIA (EN UN MIN)</label>
                                        <div class="col-md-12">  <input value="<?php echo $respiratorio ;?>" disabled type="number" name="frecresria" id="frecresria" placeholder="frec. respiratoria (por min)" class="form-control" tabindex=""></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >FREC. CARDIACA (LPM/MIN)</label>
                                        <div class="col-md-12">  <input value="<?php echo $cardiaca ;?>" disabled type="number" name="freccaca" id="freccaca" placeholder="frec. cardiaca (lpm/min)" class="form-control" placeholder="" tabindex=""> </div> 
                                    </div>
                                </div>
                                <br>
                         </div>
                         <div class="col-md-12 form-group-spacing">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >TEMPERATURA</label>
                                        <div class="col-md-12"> <input value="<?php echo $temperatura ;?>" disabled type="number" name="temra" id="temra" placeholder="temperatura C°" class="form-control" ></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>PRESIÓN ARTERIAL MEDIA</center></label></br>
                                        <div class="col-md-12"><input value="<?php echo $pam ;?>" disabled type="number" name="presdia" id="presdia"  class="form-control"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>SA02</center></label></br>
                                        <div class="col-md-12"><input value="<?php echo $sa02 ;?>" disabled type="number" name="sasdsad" id="sasdsad" placeholder=" SA02" class="form-control"></div>
                                    </div>
                                </div>
                          </div>
                          <div class="col-md-12 form-group-spacing">
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="">OXIGENOTERAPIA</label>
                                          @if($flag_oxi == '1')
                                              <input type="radio" name="dispositivos_invasivos1" disabled checked>
                                          @else
                                              <input type="radio" name="dispositivos_invasivos1" disabled >
                                          @endif
                                          <b>SI</b>
                                          @if($flag_oxi == '2')
                                              <input type="radio" name="dispositivos_invasivos1" disabled checked>
                                          @else
                                              <input type="radio" name="dispositivos_invasivos1" disabled >
                                          @endif
                                          <b>NO</b> 
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <div class="col-md-12">
                                            @if($flag_oxi == '1')
                                              <div class="col-md-12" id="sondas_descripcion" name="sondas_descripcion">
                                                <label for="">DETALLE OXIGENOTERAPIA</label>
                                                <input value="<?php echo $detalle_oxi ;?>" disabled type="text" name="detalxi" id="detalxi" class="form-control">
                                              </div>
                                            @endif
                                          </div>
                                      </div>
                                  </div>
                          </div> 
                          <div class="form-group">
                              <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label for="">USUARIO ENCARGAO DE REGISTRO</label>
                                        <div class="col-md-12">  <input value="<?php echo $responsable_signos_vitales->name.', '.$responsable_signos_vitales->paterno.' '.$responsable_signos_vitales->materno ;?>" disabled type="text" name="freccardiaca" id="freccardiaca" class="form-control" tabindex=""> </div> 
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">FECHA DE REGISTRO</label>
                                        <div class="col-md-12">  <input value="<?php echo $fecha_creacion ;?>" disabled type="text" name="freccardiaca" id="freccardiaca" class="form-control" tabindex=""> </div> 
                                    </div>
                              </div>  
                           </div>  
                    </div>
                </div>

              </fieldset>
              <!--------->
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>  SIGNOS VITALES DATOS NUEVOS</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                        <div class="col-md-12 form-group-spacing">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label><center> TIPO ATENCION</center></label></br>
                                        <div class="col-md-12">
                                          <select name="tipo_atencion" id="tipo_atencion" disabled class="form-control">
                                              <option value="1">EMERGENCIA</option>
                                          </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>TURNO</center></label></br>
                                        <div class="col-md-12"><input value="{{$dato_turno}}" disabled type="number" name="dias_estancia" id="dias_estancia" placeholder="<?php echo $dato_turno ;?>" class="form-control"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>DIAS DE ESTANCIA</center></label></br>
                                        <div class="col-md-12"><input value="{{$diasTranscurridos}}" disabled type="number" name="dias_estancia" id="dias_estancia" placeholder="" class="form-control"></div>
                                    </div>
                                </div>
                                <br>
                         </div>
                         <div class="col-md-12 form-group-spacing">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label><center>PRESIÓN ARTERIAL (MM/HG)</center></label></br>                                        
                                        <div class="col-md-6"><input value="" type="number" name="presionarteriax" id="presionarteriax" placeholder="presion arterial x" class="form-control"></div>
                                        <div class="col-md-6"><input value="" type="number" name="presionarteriay" id="presionarteriay" placeholder="presion arterial y" class="form-control"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >FREC. RESPIRATORIA (EN UN MIN)</label>
                                        <div class="col-md-12">  <input value="" type="number" name="frecrespiratoria" id="frecrespiratoria" placeholder="frec. respiratoria (por min)" class="form-control" tabindex=""></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >FREC. CARDIACA (LPM/MIN)</label>
                                        <div class="col-md-12">  <input value="" type="number" name="freccardiaca" id="freccardiaca" placeholder="frec. cardiaca (lpm/min)" class="form-control" placeholder="" tabindex=""> </div> 
                                    </div>
                                </div>
                                <br>
                         </div>
                        
                         <div class="col-md-12 form-group-spacing">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >TEMPERATURA</label>
                                        <div class="col-md-12"> <input type="number" name="temperatura" id="temperatura" placeholder="temperatura C°" class="form-control" ></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>PRESIÓN ARTERIAL MEDIA</center></label></br>
                                        <div class="col-md-12"><input value="" disabled type="number" name="presionarteriax_media" id="presionarteriax_media"  class="form-control"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>SA02</center></label></br>
                                        <div class="col-md-12"><input value="" type="number" name="sa02" id="sa02" placeholder=" SA02" class="form-control"></div>
                                    </div>
                                </div>

                          </div>
                          <div class="col-md-12 form-group-spacing">
                              
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="">OXIGENOTERAPIA</label>
                                          <label>
                                              <input type="radio" name="opcion" value="1"> SI
                                          </label>
                                          <label>
                                              <input type="radio" name="opcion" value="2" checked> NO
                                          </label>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <div class="col-md-12">
                                              <input value="" type="text" name="detalle_oxi" id="detalle_oxi" placeholder="detalle de oxigenoterapia" class="form-control">
                                          </div>
                                      </div>
                                  </div>
                            
                          </div>    
                          <div class="form-group">
                              <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label for="">USUARIO ENCARGAO DE LA MODIFICACION</label>
                                        <div class="col-md-12">  <input value="<?php echo $responsable_editor->name.', '.$responsable_editor->paterno.' '.$responsable_editor->materno ;?>" disabled type="text" name="freccardiaca" id="freccardiaca" class="form-control" tabindex=""> </div> 
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">FECHA DE MODIFICACION</label>
                                        <div class="col-md-12">  <input value="<?php echo $fechaedicion ;?>" disabled type="text" name="fechaedic" id="fechaedic" class="form-control" tabindex=""> </div> 
                                    </div>
                              </div>  
                           </div>  
                    </div>
                </div>
                <div class="col-md-12 form-group-spacing">
                   <input id="guarda" class="btn btn-primary pull-right" value="Guardar Signos Vitales"  >
                </div>
              </fieldset>

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