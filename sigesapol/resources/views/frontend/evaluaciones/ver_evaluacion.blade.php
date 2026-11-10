@extends('frontend.layouts.master')
@section('renderjs')
<?php use Carbon\Carbon; ?>
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
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

  .diagnosticos-button-container {
    display: flex;
  }

  .diagnosticos-button-container button {
    margin-right: 10px;
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

  .table-row {
    cursor: pointer;
    transition: background-color 0.3s  !important;
  }

  .table-row:hover {
    background-color: lightgreen !important;
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
  .size-titulo {
      font-size: 13px;
      font-weight: bold;
      text-align: center;
  }

  .table-borderless,
  .table-borderless th,
  .table-borderless td {
      border: none !important;
  }

  .table-borderless th,
  .table-borderless td {
      padding: 2px 4px !important;
      vertical-align: top;
      font-size: 12px;
  }

    .table.table-bordered {
    border: 1px solid #ccc;
  }


  .table.table-bordered td,
  .table.table-bordered th {
    border: 0 !important;
  }

  .box-signos {
    padding: 12px 18px;    
    margin-top: 6px;         
  }

  .signos-table {
    border: none !important;
    width: 100%;
  }

  .signos-table td,
  .signos-table th {
    border: none !important;
    padding: 8px 12px !important; 
    vertical-align: middle;
  }

  .signos-table .form-control {
    height: 28px;
    padding: 3px 6px;
  }

  .signos-table textarea.form-control {
    height: auto;
  }
</style>

<script>

  const DIA_SEMANA_DOMINGO = 0;
  const DIA_SEMANA_SABADO = 6;
  const SERVICIO_NEUMOLOGIA = 63;
  //<![CDATA[
    var obtenerDiagnosticoGeneroUrl = "{{ route('obtener_diagnostico_genero_ver', ['buscar' => '_buscar_', 'genero' => '_genero_']) }}";
  var id_prestacion = "{{ $id_prestacion }}";
  var listIdDiagnosticoEgresoObjetos  = {!! json_encode($diagnostico_egreso) !!};
  var listIdDiagnosticoObjetos =[];
  var codigos = [];
  var codigosprocedimientos = [];

  var procedimientosSeleccionados =[];
  var listIdDiagnostico =[];
  // var listIdDiagnosticoObjetos =[];

 var filasmedicamentos = 0;
  let showValidacionCantInterconsulta = false;

  // "is_estable_auxiliar" se usa para guardar el valor actual antes de su cambio
  let is_estable_auxiliar = null;

  $(document).ready(function() {

   
    $('#btnLimpiar').prop('disabled',true);
    $('#btnLimpiarprocedimiento').prop('disabled',true);
  
    listIdDiagnosticoObjetos = {!! json_encode($diagnostico_evaluacion) !!};
  // console.log(listIdDiagnosticoObjetos);
 

    $('#tblDiagnostico tbody tr').each(function() {
        let codigo = $(this).find('td:first-child input.input-diagnostico').val();
        codigos.push(codigo);
    });

    $('#tblProcedimiento tbody tr').each(function() {
        let codigop = $(this).find('td:first-child input.input-procedimiento').val();
        codigosprocedimientos.push(codigop);
    });

    console.log(codigos);
    console.log(codigosprocedimientos);

    $('.select-2').select2();
  });
  $("#div_uci_ventilador").hide();

  const evitarLetras = (event) => {
    let charCode = event.which ? event.which : event.keyCode;
    let inputValue = String.fromCharCode(charCode);

    // Expresión regular para permitir solo números y un punto decimal
    let pattern = /^[\d.]+$/;

    if (!pattern.test(inputValue)) {
      event.preventDefault();
    }

    return true;
  }

  function getVentiladorUci(){
      var uci = $('#id_uci').val();

      if(uci=='1'){
          $("#div_uci_ventilador").show();
      }
      else{
          $("#div_uci_ventilador").hide();
      }
  }

  function getAmbulancia(){
      var ambulancia = $('#id_ambulancia').val();

      if(ambulancia=='1'){
          $("#div_ambulancia").show();
      }
      else{
          $("#div_ambulancia").hide();
      }
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
  //let flagServicioFirma = $("#flagServicioFirma").val();
  //if (flagServicioFirma == "1") {
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
  /*if (flagServicioFirma == "2") {
    $.post("{{route('postArgumentsLaboratorio')}}", {
    _token: CSRF_TOKEN,
    type: "W",
    documentName: $('#doc_firmar_laboratorio').val(),
    entidad: $('#user_firma').val()
    }, function(data, status) {
      document.getElementById("argumentos").value = data;
      getArguments();
    });
  }
  if (flagServicioFirma == "3") {
    $.post("{{route('postArgumentsRadiologia')}}", {
    _token: CSRF_TOKEN,
    type: "W",
    documentName: $('#doc_firmar_radiologia').val(),
    entidad: $('#user_firma').val()
    }, function(data, status) {
      document.getElementById("argumentos").value = data;
      getArguments();
    });
  }*/



async function MiFuncionOkWeb() {
  var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  //let flagServicioFirma = $("#flagServicioFirma").val();
  let id_resol = $("#id_evaluacion").val();
  //let id_prestacion = $("#id_prestacion").val();
  //let id_radiologia = $("#id_radiologia_actualizar").val();
  //let id_laboratorio = $("#id_laboratorio_actualizar").val();
  //if (flagServicioFirma == "1") {
    $.post("{{route('actualiza_evaluacion')}}", {
      _token: CSRF_TOKEN,
      id_tipo: "evaluacion",
      id_resol: id_resol,
    }, function(data, status) {
        $.post("{{route('actualiza_evaluacion')}}", {
          _token: CSRF_TOKEN,
          id_tipo: "receta",
          id_resol: id_resol,
      }, function(data, status) {
          $.post("{{route('actualiza_evaluacion')}}", {
          _token: CSRF_TOKEN,
          id_tipo: "laboratorio",
          id_resol: id_resol,
        }, function(data, status) {
            $.post("{{route('actualiza_evaluacion')}}", {
            _token: CSRF_TOKEN,
            id_tipo: "radiologia",
            id_resol: id_resol,
          }, function(data, status) {
            $.post("{{route('actualiza_evaluacion')}}", {
            _token: CSRF_TOKEN,
            id_tipo: "interconsulta",
            id_resol: id_resol,
          }, function(data, status) {
              $.post("{{route('actualiza_evaluacion')}}", {
              _token: CSRF_TOKEN,
              id_tipo: "referencia",
              id_resol: id_resol,
            }, function(data, status) {
                $.post("{{route('actualiza_evaluacion')}}", {
                _token: CSRF_TOKEN,
                id_tipo: "alta_medica",
                id_resol: id_resol,
              }, function(data, status) {
                  location.href = '/all_pacientes_evaluaciones_by_emergencia';
              });
            });
            });
          });
        });
      });
/*    }
    if (flagServicioFirma == "2") {
      $.post("{{route('actualiza_rua')}}", {
        _token: CSRF_TOKEN,
        id_tipo: "laboratorio",
        id_resol: id_laboratorio,
      }, function(data, status) {
        $("#flagServicioFirma").val("");
        $("#id_laboratorio_actualizar").val("");
        location.reload();
      });
    }
    if (flagServicioFirma == "3") {
    $.post("{{route('actualiza_rua')}}", {
      _token: CSRF_TOKEN,
      id_tipo: "radiologia",
      id_resol: id_radiologia,
    }, function(data, status) {
      $("#flagServicioFirma").val("");
      $("#id_radiologia_actualizar").val("");
      location.reload(); */
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

  if ($("#flag_laboratorio").val() == "0") {
    $("#divBtnLaboratorio").hide();
  } else {
    $("#divBtnLaboratorio").show();
    if ($("#flag_firmar_laboratorio").val() == "") {
      $("#btnFirmaLaboratorio").show();
      $("#btnFinalizarLaboratorio").show();
      $("#icon-doc-firmado").hide();
    } else {
      $("#btnFirmaLaboratorio").hide();
      $("#btnFinalizarLaboratorio").hide();
      $("#icon-doc-firmado").show();
    }
  }

  if ($("#flag_radiologia").val() == "0") {
    $("#divBtnRadiologia").hide();
  } else {
    $("#divBtnRadiologia").show();
    if ($("#flag_firmar_radiologia").val() == "") {
      $("#btnFirmaRadiologia").show();
      $("#btnFinalizarRadiologia").show();
      $("#icon-doc-firmado_imagenes").hide();
    } else {
      $("#btnFirmaRadiologia").hide();
      $("#btnFinalizarRadiologia").hide();
      $("#icon-doc-firmado_imagenes").show();
    }
  }


  $(document).on('click', '#btnfirmar', function(e) {
    e.preventDefault();
    firmar();
  })

  $(document).on('click', '.btnFinalizarLaboratorio', function(e) {
    e.preventDefault();
    firmarLaboratorioSinTokens($(this).data('id-lab'));
  })
  
  const firmarLaboratorioSinTokens = (id_laboratorio) => {
    let  batchSignatureProcessPoller = setInterval(function() {
      let id_prestacion = $("#id_prestacion").val();
      let proviene_formulario = $("#proviene_formulario").val();
      let id_evaluacion = $("#id_evaluacion").val();
      let id_tipo_atencion = $('#id_tipo_atencion').val();
      let postData =  { 
                        id_prestacion : id_prestacion, 
                        proviene_formulario : proviene_formulario,
                        id_evaluacion : id_evaluacion,
                        id_tipo_atencion : id_tipo_atencion,
                        id_laboratorio : id_laboratorio
                      }
      $.ajax({
        url: "<?php echo url()?>/sinTokensLaboratorio",
        type: "post",
        cache: false,
        dataType: 'json',
        data: postData,
        success: function(completed) {
          if (completed) {
            location.reload();
            clearInterval(batchSignatureProcessPoller);
          }
        }
      });
    }, 3000);
  }

  $(document).on('click', '.btnFirmaLaboratorio', function(e) {
    e.preventDefault();
    firmarLaboratorio($(this).data('id-lab'));
  })

  const firmarLaboratorio = (id_laboratorio) => {
    let id_prestacion = $("#id_prestacion").val();
    let id_evaluacion = $("#id_evaluacion").val();
    let postData = { id_prestacion : id_prestacion, 
                     id_evaluacion : id_evaluacion,
                     id_laboratorio : id_laboratorio 
                   };
  $.ajax({
      url: "<?php echo url()?>/genera_csv_laboratorio_evaluacion",
      type: "post",
      //data: p,
      data: postData,
      //timeout: 30000,
      dataType: 'json'
    })
    .done(function(resultado) {
      console.log(resultado);
      if (resultado.sw) {
        var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
        $("#doc_firmar_laboratorio").val(`${resultado.csv}.7z`);
	      $("#opt_firma_laboratorio").val(`${resultado.opt}`);
        $("#flagServicioFirma").val("2");
        $("#id_laboratorio_actualizar").val(id_laboratorio);
	      initInvoker('W');
      } else {
        //$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
        //dialogError(function () {});
      }

    })
    .fail(function(jqXHR, textStatus) {
      //$("#dialogError").html('<p>Error de Coneccion, verifique si tiene Internet</p>');
      //dialogError(function () {});
      console.log("Request failed: " + textStatus)
    })
    .always(function() {
      //bootbox.hideAll();
    });
  }

  $(document).on('click', '.btnFirmaRadiologia', function(e) {
    e.preventDefault();
    firmarRadiologia($(this).data('id-rad'));
  })

  const firmarRadiologia = (id_radiologia) => {
    let id_prestacion = $("#id_prestacion").val();
    let id_evaluacion = $("#id_evaluacion").val();
    let postData = { id_prestacion : id_prestacion, 
                     id_evaluacion : id_evaluacion,
                     id_radiologia : id_radiologia 
                   };
  $.ajax({
      url: "<?php echo url()?>/genera_csv_radiologia",
      type: "post",
      //data: p,
      data: postData,
      //timeout: 30000,
      dataType: 'json'
    })
    .done(function(resultado) {
      console.log(resultado);
      if (resultado.sw) {
        var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
        $("#doc_firmar_radiologia").val(`${resultado.csv}.7z`);
	      $("#opt_firma_radiologia").val(`${resultado.opt}`);
        $("#flagServicioFirma").val("3");
        $("#id_radiologia_actualizar").val(id_radiologia);
	      initInvoker('W');
      } else {
        //$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
        //dialogError(function () {});
      }

    })
    .fail(function(jqXHR, textStatus) {
      //$("#dialogError").html('<p>Error de Coneccion, verifique si tiene Internet</p>');
      //dialogError(function () {});
      console.log("Request failed: " + textStatus)
    })
    .always(function() {
      //bootbox.hideAll();
    });
  }

  $(document).on('click', '.btnFinalizarRadiologia', function(e) {
    e.preventDefault();
    firmarRadiologiaSinTokens($(this).data('id-rad'));
  })
  
  const firmarRadiologiaSinTokens = (id_radiologia) => {
    //let  batchSignatureProcessPoller = setInterval(function() {
      let id_prestacion = $("#id_prestacion").val();
      let proviene_formulario = $("#proviene_formulario").val();
      let id_evaluacion = $("#id_evaluacion").val();
      let id_tipo_atencion = $('#id_tipo_atencion').val();
      let postData =  { 
                        id_prestacion : id_prestacion, 
                        proviene_formulario : proviene_formulario,
                        id_evaluacion : id_evaluacion,
                        id_tipo_atencion : id_tipo_atencion,
                        id_radiologia : id_radiologia
                      }
      $.ajax({
        url: "<?php echo url()?>/sinTokensRadiologiaOrden",
        type: "post",
        cache: false,
        dataType: 'json',
        data: postData,
        success: function(completed) {
          if (completed) {
            location.reload();
            //clearInterval(batchSignatureProcessPoller);
          }
        }
      });
    //}, 3000);
  }

  const validaExamenSeleccionado = (id_examen_lab) => {
  let dni_beneficiario = $('#dni_beneficiario').val();
  console.log({
    'dni_beneficiario' : dni_beneficiario,
    'id_examen_lab' : id_examen_lab,
  });
  $.ajax({
      url: '/validacion_beneficiario_dias_por_examen_rua/' + dni_beneficiario + '/' + id_examen_lab,
      success: function (result) {
        let msg = "";
        let n = 0;
        console.log(["result",result]);
        if (result.examenes.length > 0) {
          console.log(["result > 0",result]);
          for (let key of result.examenes) {
            if (n == 0) {
                msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.materno_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene análisis de laboratorio pendientes del examen seleccionado en los últimos "+result.dias.option_value+" días, CANTIDAD : " + key.total_registros + "</h1>";
                msg += "<table class='table table-list-search' style='font-size: 11px !important;' >"; 
                msg += "<thead>";
                msg += "<tr>";
                msg += "<th>Código</th>";
                msg += "<th>Descripción Corta</th>";
                msg += "<th>Tipo de Atención</th>";
                msg += "<th>Servicio</th>";
                msg += "<th>Especialista</th>";
                msg += "<th>Fecha de Orden</th>";
                msg += "<th>Dias Transcurridos</th>";
                msg += "</tr>";
                msg += "</thead>";
                msg += "<tbody>";
            }
            msg += "<tr><td>" + key.codigo_lab + "</td>";
            msg += "<td>" + key.descripcion_corta + "</td>";
            if (key.id_tipo_atencion == 1) {
              msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
            }
            if (key.id_tipo_atencion == 2) {
              if (key.id_evaluacion > 0) {
                msg += "<td>" + `EMERGENCIA - EVALUACIÓN` + "</td>";
              } else {
                msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
              }
            }
            if (key.id_tipo_atencion == 1) {
              msg += "<td>" + `${key.nombre_servicio}` + "</td>";
            }
            if (key.id_tipo_atencion == 2) {
              msg += "<td>" + `${key.nombre_servicio_emergencia}` + "</td>";
            }
            msg += "<td>" + `${key.medico_paterno} ${key.medico_materno}, ${key.medico_nombre}` + "</td>";
            msg += "<td>" + (key.fecha_expedicion) + "</td>";
            msg += "<td>" + (key.dias_transcurridos) + "</td></tr>";
            n++;
          }
          msg += "</tbody></table>";
          $("#contenido-table-pendiente").html(msg);
          // Swal.fire({
            //   title: "Alerta Laboratorio",
            //   html: msg,
            //   width: 1000  
            // });
            $("#showDetalleExamenPendiente").modal(); 
          }
        }
      });
}

const validaExamenRadiologiaSeleccionado = (id_examen_rad) => {
  let dni_beneficiario = $('#dni_beneficiario').val();
  console.log({
    'dni_beneficiario' : dni_beneficiario,
    'id_examen_rad' : id_examen_rad,
  });
  $.ajax({
  url: '/validacion_beneficiario_dias_por_examen_rad/' + dni_beneficiario + '/' + id_examen_rad,
  success: function (result) {
    let msg = "";
    let n = 0;
    console.log(["result",result]);
    if (result.length > 0) {
      console.log(["result > 0",result]);
      for (let key of result) {
        if (n == 0) {
            msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.materno_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene análisis de laboratorio pendientes del examen seleccionado en los últimos 3 meses, CANTIDAD : " + key.total_registros + "</h1>";
            msg += "<table class='table table-list-search' style='font-size: 11px !important;' >"; 
            msg += "<thead>";
            msg += "<tr>";
            msg += "<th>Código</th>";
            msg += "<th>Descripción Corta</th>";
            msg += "<th>Tipo de Atención</th>";
            msg += "<th>Servicio</th>";
            msg += "<th>Especialista</th>";
            msg += "<th>Fecha de Orden</th>";
            msg += "<th>Estado</th>";
            msg += "<th>Dias Transcurridos</th>";
            msg += "</tr>";
            msg += "</thead>";
            msg += "<tbody>";
        }
        msg += "<tr><td>" + key.codigo_rad + "</td>";
        msg += "<td>" + key.descripcion_corta + "</td>";
        if (key.id_tipo_atencion == 1) {
          msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
        }
        if (key.id_tipo_atencion == 2) {
          if (key.id_evaluacion > 0) {
            msg += "<td>" + `EMERGENCIA - EVALUACIÓN` + "</td>";
          } else {
            msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
          }
        }
        if (key.id_tipo_atencion == 1) {
          msg += "<td>" + `${key.nombre_servicio}` + "</td>";
        }
        if (key.id_tipo_atencion == 2) {
          msg += "<td>" + `${key.nombre_servicio_emergencia}` + "</td>";
        }
        msg += "<td>" + `${key.medico_paterno} ${key.medico_materno}, ${key.medico_nombre}` + "</td>";
        msg += "<td>" + (key.fecha_expedicion) + "</td>";
        if (key.flag_validated == '1') {
          msg += "<td style='font-weight : 700;'>ATENDIDO</td>";
        } else {
          if (key.flag_informe == '1') {
            msg += "<td style='font-weight : 700;'>PENDIENTE VALIDAR</td>";
          } else {
            if (key.flag_cita_imagenes == 1 ) {
              msg += "<td style='font-weight : 700;'>PENDIENTE CITADO</td>";
            } else if (key.confirmar_toma == null || key.confirmar_toma == '') {
              msg += "<td style='font-weight : 700;'>RECIENTE</td>";
            }
          }
        }
        
        msg += "<td>" + (key.dias_transcurridos) + "</td></tr>";
        n++;
      }
      msg += "</tbody></table>";
      $("#contenido-table-pendiente-radiologia").html(msg);
      // Swal.fire({
        //   title: "Alerta Laboratorio",
        //   html: msg,
        //   width: 1000  
        // });
        $("#showDetalleExamenPendienteRadiologia").modal(); 
      }
    }
  });
}

$(document).on('click', '.toggleExamenesResult', function () {
  let registro = $(this).data('registro');
    $(".elementoOcultarResult" + registro).toggle();

    let icono = $(this).find('i');

    // icono.toggleClass('icon-visible');
    icono.toggleClass('icon-hidden-result');
});

const consultaWsResultExamenSeleccionado = (id_examen_lab) => {
  let dni_beneficiario = $('#dni_beneficiario').val();
  let nombreCompleto = `${$('#paterno_beneficiario').val()} ${$('#materno_beneficiario').val()}, ${$('#nombre_beneficiario').val()}`;
  console.log({
    'dni_beneficiario' : dni_beneficiario,
    'id_examen_lab' : id_examen_lab,
  });
  $.ajax({
    url: '/consulta_ws_result_beneficiario_examen_rua/' + dni_beneficiario + '/' + id_examen_lab,
    success: function (result) {
      let res = result;
      console.log("valor ws");
      console.log(res);

      let numerosImpresos = new Set();
      let n = 0;
      let n2 = 0;
      let msg = "";
      
      if (res == "vacio") {
        return;
      }
      if (res.length > 0) {
        console.log("Entro en la condición");
        if (n == 0) {
              // msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + nombreCompleto + " con DNI " + dni_beneficiario + " tiene resultados de la prueba de laboratorio seleccionado de los últimos 3 meses</h1>";
              msg += `<table class="table table-list-search" style="font-size: 11px !important;">`; 
              msg += `<thead class="thead-resultados">`;
              msg += "<tr>";
              msg += `<th class="encabezado"></th>`;
              msg += `<th class="encabezado">Código de orden</th>`;
              msg += `<th class="encabezado">F.Toma de Muestra</th>`;
              msg += `<th class="encabezado">Tipo de Atención</th>`;
              msg += `<th class="encabezado">Establecimiento</th>`;
              msg += "</tr>";
              msg += "</thead>";
              msg += "<tbody>";
          }
        for (let key of res) {
          console.log( key.p_nombre );
          let numeroActual = key.o_numero;
          let elementoOcultar = "elementoOcultarResult" + n;
          console.log(numeroActual);
          // Verificar si el número ya se ha impreso
          if (!numerosImpresos.has(numeroActual)) {
            // Si no se ha impreso, imprimir y agregar al conjunto
            console.log('o_numero:', numeroActual);
            
            msg += `<tr><td><a class="toggleExamenesResult" data-registro="${n}"><i class="fa fa-plus-circle icon-visible" aria-hidden="true"></i></a></td>`;
            msg += `<td>${numeroActual}</td>`;
            msg += `<td>${key.o_fecha_toma}</td>`;
            msg += `<td>${key.s_nombre}</td>`;
            msg += `<td>${key.c_nombre}</td>`;
            msg += `</tr>`;
            msg += `<tr class="thead-resultados-detalle ${elementoOcultar} hide-element-result">`;
            msg += `<th class="encabezado">Examen</th>`;
            msg += `<th class="encabezado">Resultado</th>`;
            msg += `<th class="encabezado">Rango Referencial</th>`;
            msg += "</tr>";
            numerosImpresos.add(numeroActual);
            n2 = n;
            n++;
          }
          elementoOcultar = "elementoOcultarResult" + n2;
          msg += `<tr class="tbody-resultados-detalle ${elementoOcultar} hide-element-result">`
          msg += `<td>${key.p_nombre}</td>`;
          msg += `<td>${key.l_resultado}</td>`;
          msg += `<td>${key.l_ref_inf} - ${key.l_ref_sup}</td>`;
          msg += "</tr>";
            
          
        }
        //console.table(result);
          msg += "</tbody></table>";
          $("#contenido-table-result").html(msg);
          // Swal.fire({
          //   title: "Resultados Laboratorio",
          //   html: msg,
          //   width: 1000  
          // });
          
          $("#showDetalleExamenResult").modal(); 
      }
    }
  });
}
  /*
  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();
  fullCalendar();
  */
  //calendar.render();

  $('#fecha_atencion').datepicker();

  /*$(".fc-event-container").on("click", function (e) {
  	alert("cssc");
  });*/

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

  $('#addDiagnostico').on('click', function() {
    AddDiagnostico();
  });

  $('#addProcedimiento').on('click', function() {
    AddProcedimiento();
  });

  $('#addProducto').on('click', function() {
    AddProducto();
  });

  $('#addProductoAlta').on('click', function () {
    AddProductoAlta();
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

  $('#addInterconsulta').on('click', function () {
    var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
    $("#fsInterconsulta").hide();
    if(addInterconsulta == "false"){
      $("#fsInterconsulta").show();
    }
  });

  $('#addInterconsulta2').on('click',function(){
    AddInterconsulta2();
  });

  $('#addReferencia').on('click', function () {
    var addReferencia = $('#addReferencia').attr("aria-pressed");
    $("#fsReferencia").hide();
    if(addReferencia == "false"){
      $("#fsReferencia").show();
    }
  });

  $('#addExamenLab2').on('click', function() {
    AddExamenLab();
  });

  // $('#addExamenRad2').on('click', function() {
  //   AddExamenRad();
  // });
  ////////////////////////////////////////////////////////////////////////////// NUEVA MODIFICACION EXAMEN DE IMÁGENES 29-05-26
    var nivel = {{ $nivel }};
    if(nivel === 3){
      let enviandoFormulario = false;
      var idImagenologiaActual = null;
      var imagenologiaModalMostrado = false;
      $('#addExamenRad2').off('click').on('click', function() {
        if (!imagenologiaModalMostrado) {
          $('#rad_anamnesis').val('');
          $('#rad_examen_fisico').val('');
          $('#rad_examen_auxiliar').val('');
          $('#detalleModal').modal('show');
        } else {
          AddExamenRad();
        }
      });

      $('#tblExamenRad tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();

        var totalFilas = $('#tblExamenRad tbody tr').length;
        if (totalFilas === 0) {
          imagenologiaModalMostrado = false;
          if (idImagenologiaActual) {
            $.ajax({
              url: '/actualizar_estado_imagenologia',
              type: 'POST',
              data: {
              _token: $('meta[name="csrf-token"]').attr('content'),
              id: idImagenologiaActual 
            }
          });
        }
        }
      });

      $('#btnConfirmarImagenologia').off('click').on('click', function() {
        var anamnesis         = $.trim($('#rad_anamnesis').val());
        var examen_fisico     = $.trim($('#rad_examen_fisico').val());
        var examen_auxiliar   = $.trim($('#rad_examen_auxiliar').val());
        var errores = [];

        function validarCampo(texto, nombreCampo) {
          if (texto === '') {
            errores.push('Debe ingresar ' + nombreCampo + '.');
            return;
          }

          if (texto.length < 20) {
            errores.push(nombreCampo + ' debe tener mínimo 20 caracteres.');
          }

          if (!/[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]/.test(texto)) {
            errores.push(nombreCampo + ' no puede contener solo símbolos.');
          }

          if (/^[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]+$/.test(texto)) {
            errores.push('Ingrese ' + nombreCampo + ' válido(a).');
          }

          if (/^([^\w\s])\1+$/.test(texto)) {
            errores.push(nombreCampo + ' contiene caracteres inválidos.');
          }
        }

        validarCampo(anamnesis, '<b>Anamnesis</b>');
        validarCampo(examen_fisico, '<b>Examen Físico</b>');
        validarCampo(examen_auxiliar, '<b>Examen Auxiliar</b>');

        if (errores.length > 0) {
          Swal.fire({
            icon: 'error',
            title: 'Campos requeridos',
            html: errores.join('<br>'),
            confirmButtonText: 'ENTENDIDO',
          });
          return false;
        }

        var id_emergencia      = $('#id_principal').val();
        var id_asegurado       = $('#id_asegurado').val();
        var id_establecimiento = $('#id_establecimiento').val();
        var id_tipo_atencion   = $('#id_tipo_atencion').val();

        $('#btnConfirmarImagenologia').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

        $.ajax({
          url: '/guardar_anamnesis_imagenologia',
          type: 'POST',
          data: {
              _token:             $('meta[name="csrf-token"]').attr('content'),
              id_emergencia:      id_emergencia,
              id_asegurado:       id_asegurado,
              id_establecimiento: id_establecimiento,
              anamnesis:          anamnesis,
              examen_fisico:      examen_fisico,
              examen_auxiliar:    examen_auxiliar,
              id_tipo_atencion:   id_tipo_atencion,
              
          },
          success: function(response) {
            $('#btnConfirmarImagenologia').prop('disabled', false).html('<i class="fa fa-save"></i> <strong>CONFIRMAR Y AGREGAR EXAMEN</strong>');
            if (response.success) {
              idImagenologiaActual = response.id;
              $('#id_rua_imagenes').val(idImagenologiaActual);
              $('#detalleModal').modal('hide');
              imagenologiaModalMostrado = true;
              Swal.fire({
                icon: 'success',
                title: 'Guardado correctamente',
                showConfirmButton: false,
                timer: 1500,
              }).then(function() {
                  AddExamenRad();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                html: response.message || 'Intente nuevamente.',
                confirmButtonText: 'ENTENDIDO',
              });
            }
          },
          error: function(xhr) {
            $('#btnConfirmarImagenologia').prop('disabled', false).html('<i class="fa fa-save"></i> <strong>CONFIRMAR Y AGREGAR EXAMEN</strong>');

            var msg = 'Ocurrió un error al guardar los datos.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
            Swal.fire({
              icon: 'error',
              title: 'Error inesperado',
              html: msg,
              confirmButtonText: 'ENTENDIDO',
            });
          }
        });
      });

      $('#detalleModal').on('hidden.bs.modal', function() {
          if (!imagenologiaModalMostrado) {
              imagenologiaModalMostrado = false;
          }
      });

      window.addEventListener('beforeunload', function (e) {
        if (!enviandoFormulario && idImagenologiaActual) {
          navigator.sendBeacon('/actualizar_estado_imagenologia', new URLSearchParams({
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: idImagenologiaActual
          }));
        }
      });
      $('#frmPrestacion').on('submit', function() {
          enviandoFormulario = true;
      });
    }else{
      $('#addExamenRad2').on('click',function(){
        AddExamenRad();
      });
    }
    ////////////////////////////////////////////////////////////////////////////// FIN MODIFICACION EXAMEN DE IMÁGENES 29-05-26

  $(document).on('click', '#example .deleteFila, #exampleAlta .deleteFila', function(e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).closest('tr').remove();
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

  $('#tblInterconsulta tbody').on('click', 'button.deleteFila', function () {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $("#id_servicio_derivar").select2({
    width: 'resolve'
  });

  $("#id_farmacia_receta").select2({
    width: 'resolve'
  });

  $("#id_farmacia_receta_egreso").select2({
    width: 'resolve'
  });

  $("#id_servicio_interconsulta").select2({
        width: 'resolve'
    });

    $("#id_establecimiento_referencia").select2({
        width: 'resolve'
    });

    $("#id_uci").select2({
        width: 'resolve'
    });

    $("#id_uci_cv").select2({
        width: 'resolve'
    });

    $("#id_ambulancia").select2({
        width: 'resolve'
    });

    $("#id_ambulancia_ref").select2({
        width: 'resolve'
    });
  
  $("#id_tipo_ambulancia").select2({
        width: 'resolve'
    });

    $("#id_pasaje").select2({
        width: 'resolve'
    });

    $("#id_acompanante").select2({
        width: 'resolve'
    });
  
  $("#id_situacion").select2({
        width: 'resolve'
    });

  $("#id_farmacia_receta").val($("#id_farmacia").val()).trigger("change");

  $("#id_farmacia_receta_egreso").val($("#id_farmacia").val()).trigger("change");

  //AddDiagnostico();
  //AddProcedimiento();
  // AddExamenLab();
  //AddExamenRad();

});

function getProducto(){
	
	$('#example tbody').html('');
	var id_ipress 		= $('#id_establecimiento').val();
	var id_consultorio 	= $('#id_servicio').val();
	var id_tiporeceta 	= $('#id_tipo_atencion').val();
	var id_farmacia     = $('#id_farmacia_receta').val();
  
	var nivel     = $('#id_nivel').val();
	var x  = $('#nro_interconsultas').val();
	var dni = $('#dni_beneficiario').val();
	var interconsulta = $('#interconsulta').val();
	if(id_tiporeceta == 2 && id_farmacia == 21){ //nutricional
    
		if(id_tiporeceta==5 || id_tiporeceta == 2){
			id_consultorio = 110;
		}
		else{
			id_consultorio = 111;
		}
		$.ajax({
			url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia_nutricial/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
			success: function(result){                    
				var newOption = "";
				newOption = "<option value=''>--Seleccionar-</option>";
				$(result).each(function (ii, oo) {
					newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
				});                 
				$('#cmbMedicamentoTemp').html(newOption);
			}
		});
	}
  else{
      if(id_tiporeceta==2 && (id_farmacia == 19 || id_farmacia == 107 || id_farmacia ==22 || id_farmacia ==14)){
        id_consultorio=76;
      }
      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          });                 
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });
  }
  if(id_farmacia == 132){
    $.ajax({
      url: '/obtener_producto_farmacotecnia/' + id_ipress,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
        });                 
        $('#cmbMedicamentoTemp').html(newOption);
      }
    });
  }
}

let contadorFilasAlta = 0;

function getProductoAlta(){
  $('#exampleAlta tbody').html('');
  var id_ipress   = $('#id_establecimiento').val();
  var id_consultorio = $('#id_servicio').val();
  var id_tiporeceta = $('#id_tipo_atencion').val();
  var id_farmacia   = $('#id_farmacia_receta_egreso').val();

  if (id_tiporeceta == 2 && id_farmacia == 21) { // nutricional
    if (id_tiporeceta == 5 || id_tiporeceta == 2) {
      id_consultorio = 110;
    } else {
      id_consultorio = 111;
    }
    $.ajax({
      url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia_nutricial/' + id_ipress + '/' + id_farmacia + '/' + id_consultorio,
      success: function(result){
        var newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' infecto='" + oo.infecto + "'>" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
        });
        $('#cmbMedicamentoTempAlta').html(newOption);
      }
    });
  } else {
    if (id_tiporeceta == 2 && (id_farmacia == 19 || id_farmacia == 107 || id_farmacia == 22 || id_farmacia == 14)) {
      id_consultorio = 76;
    }
    $.ajax({
      url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + id_consultorio,
      success: function(result){
        var newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' infecto='" + oo.infecto + "'>" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
        });
        $('#cmbMedicamentoTempAlta').html(newOption);
      }
    });
  }

  if (id_farmacia == 132) {
    $.ajax({
      url: '/obtener_producto_farmacotecnia/' + id_ipress,
      success: function(result){
        var newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' infecto='" + oo.infecto + "'>" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
        });
        $('#cmbMedicamentoTempAlta').html(newOption);
      }
    });
  }
}

function AddProductoAlta() {
  var tipo_receta = $('#tipo_receta').val();
  var id_consultorio = $('#id_consultorio').val();
  var id_farmacia = $('#id_farmacia_receta_egreso').val();
  if (tipo_receta == "") {
    bootbox.alert("Seleccione el tipo atención");
    return false;
  }
  if (id_consultorio == "") {
    bootbox.alert("Seleccione un servicio");
    return false;
  }
  var newRow = "";
  var ind = contadorFilasAlta++;
  var tabindex = 201;

  if (ind > 0) {
    $('.cmbMedicamentoAlta').each(function() {
      var ind_tmp = $(this).attr("ind");
      if (ind_tmp => ind) {
        tabindex += 3;
      }
    });
  }

  var item_producto = "";
  $('#cmbMedicamentoTempAlta option').each(function() {
    item_producto += "<option value='" + $(this).val() + "' ru='" + $(this).attr("ru") + "' infecto='" + $(this).attr("infecto") + "'>" + $(this).html() + "</option>";
  });

  var item_via_administracion = '';
  item_via_administracion += '<option value="0">--Seleccionar--</option>';
  item_via_administracion += '<option value="1">Oral</option>';
  item_via_administracion += '<option value="2">Sublingual</option>';
  item_via_administracion += '<option value="3">Rectal</option>';
  item_via_administracion += '<option value="4">Intravenosa</option>';
  item_via_administracion += '<option value="5">Intramuscular</option>';
  item_via_administracion += '<option value="6">Subcutánea</option>';
  item_via_administracion += '<option value="7">Dérmica</option>';
  item_via_administracion += '<option value="8">Nasal</option>';
  item_via_administracion += '<option value="9">Oftalmológica</option>';
  item_via_administracion += '<option value="10">Inhalatoria</option>';
  item_via_administracion += '<option value="11">Epidural</option>';
  item_via_administracion += '<option value="12">Intratecal</option>';
  item_via_administracion += '<option value="13">Vaginal</option>';
  item_via_administracion += '<option value="14">Intraarticular</option>';
  item_via_administracion += '<option value="17">Parenteral</option>';
  item_via_administracion += '<option value="15">No Aplica</option>';
  item_via_administracion += '<option value="16">Otros</option>';

  newRow += '<tr>';
  newRow += '<td><select class="form-control cmbMedicamentoAlta" id="cmbMedicamentoAlta' + ind + '" ind="' + ind + '" tabindex="' + tabindex + '" name="id_producto_alta[]" style="width: 450px">' + item_producto + '</select></td>';
  newRow += '<input type="hidden" name="nro_stocks_almacen_alta[]" id="nro_stocks_almacen_alta" value=""/>';
  newRow += '<input type="hidden" name="nro_stocks_establecimiento_alta[]" id="nro_stocks_establecimiento_alta" value=""/>';
  newRow += '<td><input type="text" name="nro_stocks_alta[]" required id="nro_stocks_alta" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  newRow += '<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo_alta[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo_alta' + ind + '" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value=""/>';
  newRow += '<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion_alta[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';

  newRow += '<td><input type="text" name="posologia_alta[]" maxlength="150" tabindex="' + (tabindex + 2) + '" required="" id="posologia_alta' + ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';
  newRow += '<input type="hidden" name="lotes_lote_alta[]" id="lotes_lote_alta" value=""/>';
  newRow += '<input type="hidden" name="lotes_registro_sanitario_alta[]" id="lotes_registro_sanitario_alta" value=""/>';
  newRow += '<input type="hidden" name="lotes_fecha_vencimiento_alta[]" id="lotes_fecha_vencimiento_alta" value=""/>';
  newRow += '<input type="hidden" name="lotes_precio_unitario_alta[]" id="lotes_precio_unitario_alta" value=""/>';
  newRow += '<input type="hidden" name="lotes_cantidad_alta[]" id="lotes_cantidad_alta" value=""/></td>';
  newRow += '<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) + '" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita" name="nro_med_solictados_alta[]" required="" id="nro_med_solictados_alta' + ind + '" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';
  newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico-alta"  id="miSelectProdAlta' + ind + '" name="miSelectProd_alta[]"></select></td>';
  newRow += '<td><button type="button" class="btn btn-primary btn-xs" id="add_producto_alta' + ind + '"><i class="fa fa-plus"></i> </button></td>';
  newRow += '<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
  newRow += '</tr>';

  $('#exampleAlta tbody').append(newRow);

  let select = $("#cmbMedicamentoAlta" + ind);
  select.select2({
    width: '100%',
    dropdownAutoWidth: true
  });

  const selectElementProdAlta = document.getElementById('miSelectProdAlta' + ind);

  listIdDiagnosticoEgresoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id_diagnostico;
    option.textContent = item.nombre;
    selectElementProdAlta.appendChild(option);
  });

  $("#nro_med_solictados_alta" + ind).keypress(function(e) {
    if (e.which == 13) {
      AddProductoAlta();
    }
  });

  $("#add_producto_alta" + ind).click(function(e){
    AddProductoAlta();
    validproductoseleccionadoAlta({{Auth::user()->id_farmacia}}, this);
    $(this).attr('disabled', 'disabled');
  });

  function validproductoseleccionadoAlta(id_farmacia, btn) {
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_dias = 30;
    var objTr = $(btn).closest("tr");
    var id_producto = objTr.find(".cmbMedicamentoAlta").val();
    $.ajax({
      url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario + '/' + nro_dias + '/' + id_producto,
      success: function (result) {
        var msg = "";
        var n = 0;
        for (var key in result) {
          if (n == 0) {
            msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + (result[key].paterno_beneficiario) + " " + (result[key].materno_beneficiario) + ", " + (result[key].nombre_beneficiario) + " con DNI " + (result[key].dni_beneficiario) + " tiene receta con el mismo producto</h1>";
            msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
            msg += "<thead><tr>";
            msg += "<th>Farmacia</th><th>Nro. Receta</th><th>Fecha Expedicion</th><th>Producto</th><th>Tiempo(Dias)</th><th>Dias Transcurridos</th><th>Dias Pendientes</th><th>Cantidad Prescrita</th>";
            msg += "</tr></thead><tbody>";
          }
          msg += "<tr><td>" + (result[key].farmacia) + "</td>";
          msg += "<td>" + (result[key].nro_receta) + "</td>";
          msg += "<td>" + (result[key].fecha_expedicion) + "</td>";
          msg += "<td>" + (result[key].codigo) + " - " + (result[key].producto) + "</td>";
          msg += "<td>" + (result[key].tiempo_periodo) + "</td>";
          msg += "<td>" + (result[key].dias_transcurridos) + "</td>";
          msg += "<td>" + (result[key].tiempo_periodo - result[key].dias_transcurridos) + "</td>";
          msg += "<td>" + (result[key].cantidad_prescrita) + "</td></tr>";
          n++;
        }
        if (msg != "") {
          bootbox.confirm({
            title: "Alerta",
            message: msg,
            buttons: {
              cancel: { label: '<i class="fa fa-times"></i> Cancelar' },
              confirm: { label: '<i class="fa fa-check"></i> Continuar' }
            },
            callback: function (result) {
              if (result == true) {
                verlotes(id_farmacia, btn);
              }
            }
          });
        }
      }
    });
  }
}

$('#exampleAlta tbody').on('change', '.cmbMedicamentoAlta', function(e) {
  let cmb = $(this);
  let fila = cmb.closest('tr');
  let id_producto = cmb.val();
  let flagx = 0;

  $('.cmbMedicamentoAlta').each(function() {
    if ($(this).val() == id_producto) flagx++;
  });
  if (flagx > 1) {
    bootbox.alert("El producto farmaceutico ya ha sido ingresado");
    fila.find('input[type="text"], input[type="number"], textarea').val('');
    fila.find('select').each(function () { $(this).val(null).trigger('change'); });
    return false;
  }

  let id_farmacia = $('#id_farmacia_receta_egreso').val();
  $.ajax({
    url: '/cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia,
    type: 'GET',
    dataType: 'json'
  }).done(function(data) {
    fila.find(".limpia_text").val("");
    fila.find("#nro_stocks_alta").val(data.cantidad);
    fila.find("#nro_stocks_establecimiento_alta").val(data.cantidadEstablecimiento);
    fila.find("#nro_stocks_almacen_alta").val(data.cantidadAlmacen);
    fila.find(".tiempo_periodo").focus();
    fila.find("#nro_med_solictados_alta").val("");
    fila.find("#lotes_lote_alta").val("");
    fila.find("#lotes_cantidad_alta").val("");
    fila.find("#lotes_registro_sanitario_alta").val("");
    fila.find("#lotes_fecha_vencimiento_alta").val("");
  });
});

function guardarPrestacion() {

  var msg = "";
  $('#btnPrestacion').prop("disabled",true);
  /*
	var id_ipress = $('#id_ipress').val();
    var id_consultorio = $('#id_consultorio').val();
    var fecha_atencion = $('#fecha_atencion').val();
    var dni_beneficiario = $("#dni_beneficiario").val();
	if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";
    if(id_ipress==""){msg+="Debe ingresar una Ipress<br>";}
    if(id_consultorio==""){msg+="Debe ingresar un Consultorio<br>";}
    if(fecha_atencion==""){msg+="Debe ingresar una fecha de atencion<br>";}
   	
	var horario = $('input[name=horario]:checked').val();
	var data = horario.split("#");
	var fecha_cita = data[0];
	var id_medico = data[1];
	*/
  var seleccionados = $("input[name='cod_lab[]']").map(function () {
            return $(this).val();
        }).get();

        msg += verificarFaltantes(seleccionados);

    let totalRowProducto = $('#example tbody tr').length;
    if (totalRowProducto > 0) {
      let producto_tr = $('#example tbody').children('tr');
        let erroresProducto = [];

      let Tiempo_producto    = false;
      let Via_producto       = false;
      let Posologia_producto = false;
      let Cantidad_producto  = false;

      for (let i = 0; i < totalRowProducto; i++) {
        let fila = producto_tr[i];

        let $sel = $(fila).find('select[name="id_producto[]"]');
        let id_producto = $sel.val();

        let tiempo_producto = $(fila).find('input[name="tiempo_periodo[]"]').val();
        let via_producto    = $(fila).find('select[name="id_via_administracion[]"]').val();
        let posol_producto  = $(fila).find('input[name="posologia[]"]').val();
        let cant_producto   = $(fila).find('input[name="nro_med_solictados[]"]').val();

      
        if (tiempo_producto == "" || tiempo_producto == null || isNaN(tiempo_producto) || parseInt(tiempo_producto) <= 0) {
          erroresProducto.push("Uno de los productos no cuenta con el tiempo(dias), tiene que ser mayor a 0");
        }
        if (via_producto == "" || via_producto == null || via_producto == "0") {
          erroresProducto.push("Uno de los productos no cuenta con la vía de administración");
        }
        if (posol_producto == "" || posol_producto == null || posol_producto.trim() === "") {
          erroresProducto.push("Uno de los productos no cuenta con la posología");
        }
        if (cant_producto == "" || cant_producto == null || cant_producto === "NaN" || isNaN(cant_producto) || parseInt(cant_producto) <= 0) {
          erroresProducto.push("Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0");
        }
      }

      if (erroresProducto.length > 0) {
          msg += erroresProducto.join("<br>") + "<br>";
      }
    }

    let totalRowProductoAlta = $('#exampleAlta tbody tr').length;
    if (totalRowProductoAlta > 0) {
    let producto_alta_tr = $('#exampleAlta tbody').children('tr');
    let erroresProductoAlta = [];

    for (let i = 0; i < totalRowProductoAlta; i++) {
      let fila = producto_alta_tr[i];
      let nro = i + 1;

      let $sel = $(fila).find('select[name="id_producto_alta[]"]');
      let id_producto_alta = $sel.val();

      let tiempo_alta = $(fila).find('input[name="tiempo_periodo_alta[]"]').val();
      let via_alta    = $(fila).find('select[name="id_via_administracion_alta[]"]').val();
      let posol_alta  = $(fila).find('input[name="posologia_alta[]"]').val();
      let cant_alta   = $(fila).find('input[name="nro_med_solictados_alta[]"]').val();

      if (tiempo_alta == "" || tiempo_alta == null || isNaN(tiempo_alta) || parseInt(tiempo_alta) <= 0) {
        erroresProductoAlta.push("Uno de los productos no cuenta con el tiempo(dias), tiene que ser mayor a 0");
      }
      if (via_alta == "" || via_alta == null || via_alta == "0") {
        erroresProductoAlta.push("Uno de los productos no cuenta con la vía de administración");
      }
      if (posol_alta == "" || posol_alta == null || posol_alta.trim() === "") {
        erroresProductoAlta.push("Uno de los productos no cuenta con la posología");
      }
      if (cant_alta == "" || cant_alta == null || cant_alta === "NaN" || isNaN(cant_alta) || parseInt(cant_alta) <= 0) {
        erroresProductoAlta.push("Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0");
      }
    }

    if (erroresProductoAlta.length > 0) {
      msg += erroresProductoAlta.join("<br>") + "<br>";
    }
  }

  let totalRowExamen = $('#tblExamenLab tbody tr').length;
  if (totalRowExamen > 0) {
    let examen_lab_tr = $('#tblExamenLab tbody').children('tr');

    for (i = 0; i < totalRowExamen; i++) {
      let fila_examen_lab = examen_lab_tr[i];

      let nombre_examen_lab = $(fila_examen_lab).find('input[name="nombre_examen_lab[]"]').val();
      let id_examen_lab = $(fila_examen_lab).find('input[name="id_examen_lab[]"]').val();
      let cant_examen_lab = $(fila_examen_lab).find('input[name="examen_lab_cantidad[]"]').val();
      let diagnostico_examen_lab = $(fila_examen_lab).find('select[name="miSelectLab[]"]').val();

      if (id_examen_lab == "") {
        msg += "Debe registrar un examen de laboratorio en la fila: " + (i+1) + "<br>";
      } else {
        if (cant_examen_lab == "") msg += "Debe registrar una cantidad en laboratorio: <b>" + nombre_examen_lab + "</b><br>";
        if (cant_examen_lab == "0") msg += "Debe registrar una cantidad mayor a 0 para el examen de laboratorio: <b>" + nombre_examen_lab + "</b><br>";
        if (diagnostico_examen_lab == "" || diagnostico_examen_lab == null) msg += "Debe seleccionar un diagnostico para el examen de laboratorio: <b>" + nombre_examen_lab + "</b><br>";
      }
    }
  }

  /*if (totalRowExamen > 1) {
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA FINAL =================
    let fin_flag_descripcion_tipo_muestra = $('#flag_descripcion_tipo_muestra' + (totalRowExamen - 1)).val();
    if (fin_flag_descripcion_tipo_muestra == 1) {
      let fin_descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + (totalRowExamen - 1)).val();
      if (fin_descripcion_tipo_muestra == "") {
        msg+='Debe indicar el tipo de muestra para microbilogía en el último exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Tipo de muestra</button><br>';
      }
    }
    
    //=== LABORATORIO VALIDA SOLICITUD HEMOCULTIVO ADULTO ESTE CARGADA FINAL =================
    let fin_flag_solicitud_examen_especializados_hemocultivo_adulto = $('#flag_solicitud_examen_especializados_hemocultivo_adulto' + (totalRowExamen - 1)).val()
    if (fin_flag_solicitud_examen_especializados_hemocultivo_adulto == 1) {
      let fin_des_adulto_respuesta_ocular = $('#des_adulto_respuesta_ocular' + (totalRowExamen - 1)).val();
      if (fin_des_adulto_respuesta_ocular == "") {
        msg+='Debe indicar la solicitud del examen especializado Hemocultivo adulto en el último exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
      }
    }
    
    //=== LABORATORIO VALIDA SOLICITUD HEMOCULTIVO PEDRIATRIA ESTE CARGADA FINAL =================
    let fin_flag_solicitud_examen_especializados_hemocultivo_pedriatria = $('#flag_solicitud_examen_especializados_hemocultivo_pedriatria' + (totalRowExamen - 1)).val()
    if (fin_flag_solicitud_examen_especializados_hemocultivo_pedriatria == 1) {
      let fin_des_pedriatria_respuesta_ocular = $('#des_pedriatria_respuesta_ocular' + (totalRowExamen - 1)).val();
      if (fin_des_pedriatria_respuesta_ocular == "") {
        msg+='Debe indicar la solicitud del examen especializado Hemocultivo neonato y pedriatria en el último exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
      }
    }

    //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS FINAL ===============================
    let fin_flag_solicitud_citoquimicos_liquidos_biologicos = $('#flag_solicitud_citoquimicos_liquidos_biologicos' + (totalRowExamen - 1)).val();
    if (fin_flag_solicitud_citoquimicos_liquidos_biologicos == 1) {
      let fin_des_num_frasco_muestra_opcion = $('#des_num_frasco_muestra_opcion' + (totalRowExamen - 1)).val();
      if (fin_des_num_frasco_muestra_opcion == "") {
        msg+='Debe registrar la solicitud para estudio citoquímico líquido en el último exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
      }
    }

  } else {
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA INICIO =================
    let inicio_flag_descripcion_tipo_muestra = $('#flag_descripcion_tipo_muestra0').val();
    if (inicio_flag_descripcion_tipo_muestra == 1) {
      let inicio_descripcion_tipo_muestra = $('#descripcion_tipo_muestra0').val();
      if (inicio_descripcion_tipo_muestra == "") {
        msg+='Debe indicar el tipo de muestra para microbilogía en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Tipo de muestra</button><br>';
      }
    }

    //=== LABORATORIO VALIDA SOLICITUD HEMOCULTIVO ADULTO ESTE CARGADA INICIO =================
    let inicio_flag_solicitud_examen_especializados_hemocultivo_adulto = $('#flag_solicitud_examen_especializados_hemocultivo_adulto0').val()
    if (inicio_flag_solicitud_examen_especializados_hemocultivo_adulto == 1) {
      let inicio_des_adulto_respuesta_ocular = $('#des_adulto_respuesta_ocular0').val();
      if (inicio_des_adulto_respuesta_ocular == "") {
        msg+='Debe indicar la solicitud del examen especializado Hemocultivo adulto en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
      }
    }

    //=== LABORATORIO VALIDA SOLICITUD HEMOCULTIVO PEDRIATRIA ESTE CARGADA INICIO =================
    let inicio_fin_flag_solicitud_examen_especializados_hemocultivo_pedriatria = $('#flag_solicitud_examen_especializados_hemocultivo_pedriatria0').val()
    if (inicio_fin_flag_solicitud_examen_especializados_hemocultivo_pedriatria == 1) {
      let inicio_des_pedriatria_respuesta_ocular = $('#des_pedriatria_respuesta_ocular0').val();
      if (inicio_des_pedriatria_respuesta_ocular == "") {
        msg+='Debe indicar la solicitud del examen especializado Hemocultivo neonato y pedriatria en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
      }
    }

    //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS FINAL ===============================
    let inicio_flag_solicitud_citoquimicos_liquidos_biologicos = $('#flag_solicitud_citoquimicos_liquidos_biologicos0').val();
    if (inicio_flag_solicitud_citoquimicos_liquidos_biologicos == 1) {
      let inicio_des_num_frasco_muestra_opcion = $('#des_num_frasco_muestra_opcion0').val();
      if (inicio_des_num_frasco_muestra_opcion == "") {
        msg+='Debe registrar la solicitud para estudio citoquímico líquido en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
      }
    }

  }*/

  let tblInterconsulta_cantidad_tr = $('#tblInterconsulta tbody tr').length;
  if (tblInterconsulta_cantidad_tr > 0) {
    let interconsulta_tr = $('#tblInterconsulta tbody').children('tr');

    for (i = 0; i < tblInterconsulta_cantidad_tr; i++) {
      let fila_interconsulta = interconsulta_tr[i];

      let id_servicio_interconsulta = $(fila_interconsulta).find('[name="id_servicio_interconsulta[]"]').val();
      let motivo_interconsulta = $(fila_interconsulta).find('textarea[name="motivo_interconsulta[]"]').val();

      if (id_servicio_interconsulta == "") msg += "Debe seleccionar el Servicio de Interconsulta<br>";
      if (motivo_interconsulta == "") msg += "Debe ingresar el Motivo de la Interconsulta<br>";
    }
  }
        
  if (msg != "") {
    bootbox.alert(msg);
    $('#btnPrestacion').prop("disabled",false);
    return false;
  } else {
    $("#frmBloque").submit();
  }
}



function obtenerBeneficiario() {

  var id_tiporeceta = 1;
  var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
  var dni_beneficiario = $("#dni_beneficiario").val();
  var msg = "";

  //if(id_tiporeceta == "")msg += "Debe seleccionar el tipo de receta <br>";
  //if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";

  if (msg != "") {
    bootbox.alert(msg);
    return false;
  }

  $.ajax({
    url: '../obtener_beneficiario/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
    dataType: "json",
    success: function(result) {
      if (result.sw == false) {
        bootbox.alert(result.msg);
        $('#beneficiario').val("");
        $('#tipo_beneficiario').val("");
        $('#nombre_beneficiario').val("");
        $('#paterno_beneficiario').val("");
        $('#materno_beneficiario').val("");
        $('#tipodoc_beneficiario').val("");
        $('#cip_beneficiario').val("");
        $('#grado').val("");
        return false;
      }

      var beneficiario = result.beneficiario.apepatafiliado + " " + result.beneficiario.apematafiliado + " " +
        result.beneficiario.apecasafiliado + ", " + result.beneficiario.nomafiliado;
      $('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
      $('#beneficiario').val(beneficiario);
      $('#tipo_beneficiario').val(result.beneficiario.parentesco);
      $('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
      $('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
      $('#materno_beneficiario').val(result.beneficiario.apematafiliado + " " + result.beneficiario
        .apecasafiliado);
      $('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
      $('#cip_beneficiario').val(result.beneficiario.cip);
      $('#grado').val(result.beneficiario.grado);

      var parentesco = result.beneficiario.parentesco;
      var ubigeo = result.beneficiario.ubigeo;

      obtenerEstablecimiento(parentesco, ubigeo);
      //alert(ubigeo);
      /*
      if(id_tiporeceta == 3){	
      	ShowByServicioAndId_farmacia();
      }
      */
    }

  });

}


function obtenerEstablecimiento(parentesco, ubigeo) {
  var newOption = "";
  newOption = "<option value=''>--Seleccionar--</option>";
  $('#id_establecimiento').html(newOption);

  $.ajax({
    url: '../obtener_establecimiento/' + parentesco + "/" + ubigeo,
    success: function(result) {
      var newOption = "";
      newOption = "<option value=''>--Seleccionar--</option>";
      $(result).each(function(ii, oo) {
        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
      });
      $('#id_establecimiento').html(newOption);
      $("#id_establecimiento").select2({
        max_selected_options: 4
      });

    }
  });
}



function limpiarBeneficiario() {
  var dni_beneficiario = $('#dni_beneficiario').val();
  var nrodocafiliado = $('#nrodocafiliado').val();
  if (dni_beneficiario != nrodocafiliado) {
    $('#beneficiario').val("");
    $('#tipo_beneficiario').val("");
    $('#nombre_beneficiario').val("");
    $('#paterno_beneficiario').val("");
    $('#materno_beneficiario').val("");
    $('#grado').val("");
    $('#nro_historia').val("");
    $('#tipodoc_beneficiario').val("");
    $('#cip_beneficiario').val("");
    $('#id_admision').val("");
    $('#grado').val("");
  }

  obtenerBeneficiario();
}


function getServicio() {

  var newOption = "";
  newOption = "<option value=''>--Seleccionar--</option>";
  $('#id_servicio').html(newOption);
  //$("#id_servicio").val('').trigger("change");
  var parent = $('input[name="parent"]:checked').val();
  //var parent	= $('#parent').val();

  $.ajax({
    url: '../obtener_consultorio_parent/' + parent,
    success: function(result) {
      var newOption = "";
      newOption = "<option value=''>--Seleccionar--</option>";
      $(result).each(function(ii, oo) {
        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
      });
      $('#id_servicio').html(newOption);
      $("#id_servicio").select2({
        max_selected_options: 4
      });

    }
  });
}

function AddExamenLab() {
  var newRow = "";
  var ind = $('#tblExamenLab tbody tr').length;

  if (ind > 0) {
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA INICIO =================
    console.log('ENTRO EN CONDICIÓN');
    console.log('INDICE : ' + ind);
    let flag_descripcion_tipo_muestra = $('#flag_descripcion_tipo_muestra' + (ind - 1)).val();
    if (flag_descripcion_tipo_muestra == 1) {
      let descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + (ind - 1)).val();
      if (descripcion_tipo_muestra == "") {
        Swal.fire({
            icon: 'error',
            title: 'Debe registrar el tipo de muestra para microbiología. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Tipo de muestra</button>',
            showConfirmButton: false,
            timer: 3000
        });
        return;
      }
    }
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA FIN =================
    //=== VALIDA SOLICITUD EXAMEN ESPECIALIZADO HEMOCULTIVO INICIO ============================
    let descripcion_examen = $('#descripcion_examen' + (ind - 1)).val();
    if (descripcion_examen == 'HEMOCULTIVO NEONATO Y PEDRIATRIA') {
      let flag_solicitud_examen_especializados_hemocultivo_pedriatria = $('#flag_solicitud_examen_especializados_hemocultivo_pedriatria' + (ind - 1)).val();
      if (flag_solicitud_examen_especializados_hemocultivo_pedriatria == 1) {
        let des_pedriatria_respuesta_ocular = $('#des_pedriatria_respuesta_ocular' + (ind - 1)).val();
        if (des_pedriatria_respuesta_ocular == "") {
          Swal.fire({
              icon: 'error',
              title: 'Debe registrar la solicitud del examen especializado Hemocultivo de Neonato y Pedriatría Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs><i class="fa fa-check-square"></i>Solicitud</button>',
              showConfirmButton: false,
              timer: 3000
          });
          return;
        }
      }
    }
    if (descripcion_examen == 'HEMOCULTIVO ADULTO') {
      let flag_solicitud_examen_especializados_hemocultivo_adulto = $('#flag_solicitud_examen_especializados_hemocultivo_adulto' + (ind - 1)).val();
      if (flag_solicitud_examen_especializados_hemocultivo_adulto == 1) {
        let des_adulto_respuesta_ocular = $('#des_adulto_respuesta_ocular' + (ind - 1)).val();
        if (des_adulto_respuesta_ocular == "") {
          Swal.fire({
              icon: 'error',
              title: 'Debe registrar la solicitud del examen especializado Hemocultivo adulto Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs><i class="fa fa-check-square"></i>Solicitud</button>',
              showConfirmButton: false,
              timer: 3000
          });
          return;
        }
      }
    }
    //=== VALIDA SOLICITUD EXAMEN ESPECIALIZADO HEMOCULTIVO FIN ===============================
    //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================
    if (descripcion_examen == 'CITOQUIMICO LIQUIDOS BIOLOGICOS') {
      let flag_solicitud_citoquimicos_liquidos_biologicos = $('#flag_solicitud_citoquimicos_liquidos_biologicos' + (ind - 1)).val();
      if (flag_solicitud_citoquimicos_liquidos_biologicos == 1) {
        let des_num_frasco_muestra_opcion = $('#des_num_frasco_muestra_opcion' + (ind - 1)).val();
        if (des_num_frasco_muestra_opcion == "") {
          Swal.fire({
              icon: 'error',
              title: 'Debe registrar la solicitud para estudio citoquímico líquido Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs><i class="fa fa-check-square"></i>Solicitud</button>',
              showConfirmButton: false,
              timer: 3000
          });
          return;
        }
      }
    }
    $('.id_laboratorio').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });

    fila_medicamentos = $('#tblExamenLab tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];

  }
  const id_nivel = $("#id_nivel").val();
  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query cod_lab" id="cod_lab' + ind +'" name="cod_lab[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_categoria" id="id_categoria_examen_lab' + ind +'" name="id_categoria_examen_lab[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad typeahead tt-query" style="margin:0 5px;width:95%" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1"></td>';
  if (id_nivel && parseInt(id_nivel) < 3) {
  newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
  }
  newRow += '<td><select style="margin:0 10px;width:95%" class="form-control select-diagnostico"  id="miSelectLab' + ind + '" name="miSelectLab[]"></select></td>';
  // ====================== Tipo de Muestra Inicio ===============================================
  newRow +='<td>';
    newRow +='<button type="button" class="btn btn-success btn-xs tipoMuestraMicrobiologia" id="tipoMuestraMicrobiologia' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Tipo de muestra</button>';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="descripcion_tipo_muestra' + ind +'" name="descripcion_tipo_muestra[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_descripcion_tipo_muestra' + ind +'" name="flag_descripcion_tipo_muestra[]" value="0" />';
    newRow +='<button type="button" class="btn btn-warning btn-xs editarTipoMuestraMicrobiologia" id="editarTipoMuestraMicrobiologia' + ind +'" data-id='+ind+' style="display:none; margin-left:4px"><i class="fa fa-edit">&nbsp;Tipo de muestra</button>';
    newRow +='</td>';
    // ====================== Tipo de Muestra Fin ===============================================
    // ====================== Solicitud de Examenes Especiales HEMOCULTIVO ADULTO Inicio ===============================================
    newRow +='<td>';
    newRow +='<button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoAdulto" id="solicitudExamenEspecializadoHemocultivoAdulto' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="descripcion_examen' + ind +'" name="descripcion_examen[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_fecha_tiempo_enfermedad' + ind +'" name="des_adulto_fecha_tiempo_enfermedad[]" value="" data-id='+ind+' />';
    /** ESCALA DE GLASGOW **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_respuesta_ocular' + ind +'" name="des_adulto_respuesta_ocular[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_respuesta_verbal' + ind +'" name="des_adulto_respuesta_verbal[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_respuesta_motora' + ind +'" name="des_adulto_respuesta_motora[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_total_escala_glasgow' + ind +'" name="des_adulto_total_escala_glasgow[]" value="" data-id='+ind+' />';
    /** SIGNOS VITALES **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_presion_arterial_x' + ind +'" name="des_adulto_presion_arterial_x[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_presion_arterial_y' + ind +'" name="des_adulto_presion_arterial_y[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frecuencia_cardiaca' + ind +'" name="des_adulto_frecuencia_cardiaca[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frecuencia_respiratorio' + ind +'" name="des_adulto_frecuencia_respiratorio[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_temperatura' + ind +'" name="des_adulto_temperatura[]" value="" data-id='+ind+' />';
    /** EXÁMENES DE LABORATORIO **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_creatinina' + ind +'" name="des_adulto_creatinina[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_diuresis' + ind +'" name="des_adulto_diuresis[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_procalcitonina' + ind +'" name="des_adulto_procalcitonina[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_pcr' + ind +'" name="des_adulto_pcr[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_fiO2' + ind +'" name="des_adulto_fiO2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_leucocitos' + ind +'" name="des_adulto_leucocitos[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_plaquetas' + ind +'" name="des_adulto_plaquetas[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_bilirrubina' + ind +'" name="des_adulto_bilirrubina[]" value="" data-id='+ind+' />';
    /** ANTECEDENTES PATOLÓGICOS **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_ima' + ind +'" name="des_adulto_ima[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_icc' + ind +'" name="des_adulto_icc[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_evp' + ind +'" name="des_adulto_evp[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_ecv' + ind +'" name="des_adulto_ecv[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_demencia' + ind +'" name="des_adulto_demencia[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_dm' + ind +'" name="des_adulto_dm[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_diabetes_lesion_organica' + ind +'" name="des_adulto_diabetes_lesion_organica[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_hemiplejia' + ind +'" name="des_adulto_hemiplejia[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_epoc' + ind +'" name="des_adulto_epoc[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_patologia_tejido_conectivo' + ind +'" name="des_adulto_patologia_tejido_conectivo[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_enfermedad_ulcerosa' + ind +'" name="des_adulto_enfermedad_ulcerosa[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_patologia_renal_moderada_o_grave' + ind +'" name="des_adulto_patologia_renal_moderada_o_grave[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_neoplasias' + ind +'" name="des_adulto_neoplasias[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_neoplasias_especifique' + ind +'" name="des_adulto_neoplasias_especifique[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_leucemias' + ind +'" name="des_adulto_leucemias[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_leucemias_especifique' + ind +'" name="des_adulto_leucemias_especifique[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_linfomas_malignos' + ind +'" name="des_adulto_linfomas_malignos[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_metastasis_colida' + ind +'" name="des_adulto_metastasis_colida[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_sida' + ind +'" name="des_adulto_sida[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_1' + ind +'" name="des_adulto_antecedentes_patologicos_otros_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_1_especifique' + ind +'" name="des_adulto_antecedentes_patologicos_otros_1_especifique[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_2' + ind +'" name="des_adulto_antecedentes_patologicos_otros_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_2_especifique' + ind +'" name="des_adulto_antecedentes_patologicos_otros_2_especifique[]" value="" data-id='+ind+' />';
    /** TERAPIA ANTIMICROBIANA **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_terapia_antimicrobiana' + ind +'" name="des_adulto_terapia_antimicrobiana[]" value="" data-id='+ind+' />';
    /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_1' + ind +'" name="des_adulto_frasco_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_1_opcion_1' + ind +'" name="des_adulto_frasco_1_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_1_opcion_2' + ind +'" name="des_adulto_frasco_1_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_2' + ind +'" name="des_adulto_frasco_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_2_opcion_1' + ind +'" name="des_adulto_frasco_2_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_2_opcion_2' + ind +'" name="des_adulto_frasco_2_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_3' + ind +'" name="des_adulto_frasco_3[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_3_opcion_1' + ind +'" name="des_adulto_frasco_3_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_3_opcion_2' + ind +'" name="des_adulto_frasco_3_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_hemocultivo_seguimiento_y_vigilancia' + ind +'" name="des_adulto_hemocultivo_seguimiento_y_vigilancia[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_fecha_primer_resultado_hemocultivo' + ind +'" name="des_adulto_fecha_primer_resultado_hemocultivo[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_solicitud_examen_especializados_hemocultivo_adulto' + ind +'"  name="flag_solicitud_examen_especializados_hemocultivo_adulto[]"  data-id='+ind+' value="0" />';
    newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoAdulto" id="editarSolicitudExamenEspecializadoHemocultivoAdulto' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';
    // ====================== Solicitud de Examenes Especiales HEMOCULTIVO ADULTO Fin ===============================================
    
    // ====================== Solicitud de Examenes Especiales HEMOCULTIVO NEONATO Y PEDRIATRÍA INICIO ===============================================
    newRow +='<button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoPedriatria" id="solicitudExamenEspecializadoHemocultivoPedriatria' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_fecha_tiempo_enfermedad' + ind +'" name="des_pedriatria_fecha_tiempo_enfermedad[]" value="" data-id='+ind+' />';
    /** EDAD GESTIONAL **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_postermino' + ind +'" name="des_pedriatria_postermino[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_termino' + ind +'" name="des_pedriatria_termino[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_prematuro_leve' + ind +'" name="des_pedriatria_prematuro_leve[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_prematuro_moderado' + ind +'" name="des_pedriatria_prematuro_moderado[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_prematuro_extremo' + ind +'" name="des_pedriatria_prematuro_extremo[]" value="" data-id='+ind+' />';
    /** APGAR **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_apariencia' + ind +'" name="des_pedriatria_apariencia[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_pulso' + ind +'" name="des_pedriatria_pulso[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_gesto' + ind +'" name="des_pedriatria_gesto[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_actividad_respiratoria' + ind +'" name="des_pedriatria_actividad_respiratoria[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_tono_muscular' + ind +'" name="des_pedriatria_tono_muscular[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_total_apgar' + ind +'" name="des_pedriatria_total_apgar[]" value="" data-id='+ind+' />';
    /** ESCALA DE GLASGOW **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_respuesta_ocular' + ind +'" name="des_pedriatria_respuesta_ocular[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_respuesta_verbal' + ind +'" name="des_pedriatria_respuesta_verbal[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_respuesta_motora' + ind +'" name="des_pedriatria_respuesta_motora[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_total_escala_glasgow' + ind +'" name="des_pedriatria_total_escala_glasgow[]" value="" data-id='+ind+' />';
    /** SIGNOS VITALES **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_presion_arterial_x' + ind +'" name="des_pedriatria_presion_arterial_x[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_presion_arterial_y' + ind +'" name="des_pedriatria_presion_arterial_y[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frecuencia_cardiaca' + ind +'" name="des_pedriatria_frecuencia_cardiaca[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frecuencia_respiratorio' + ind +'" name="des_pedriatria_frecuencia_respiratorio[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_temperatura' + ind +'" name="des_pedriatria_temperatura[]" value="" data-id='+ind+' />';
    /** EXÁMENES DE LABORATORIO **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_creatinina' + ind +'" name="des_pedriatria_creatinina[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_diuresis' + ind +'" name="des_pedriatria_diuresis[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_procalcitonina' + ind +'" name="des_pedriatria_procalcitonina[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_pcr' + ind +'" name="des_pedriatria_pcr[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_fiO2' + ind +'" name="des_pedriatria_fiO2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_leucocitos' + ind +'" name="des_pedriatria_leucocitos[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_plaquetas' + ind +'" name="des_pedriatria_plaquetas[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_bilirrubina' + ind +'" name="des_pedriatria_bilirrubina[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_glucosa' + ind +'" name="des_pedriatria_glucosa[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_alt' + ind +'" name="des_pedriatria_alt[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_vcg' + ind +'" name="des_pedriatria_vcg[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_tp' + ind +'" name="des_pedriatria_tp[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_sodio' + ind +'" name="des_pedriatria_sodio[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_potasio' + ind +'" name="des_pedriatria_potasio[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_calcio' + ind +'" name="des_pedriatria_calcio[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_pco2' + ind +'" name="des_pedriatria_pco2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_tpta' + ind +'" name="des_pedriatria_tpta[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_examen_laboratorio_otros' + ind +'" name="des_pedriatria_examen_laboratorio_otros[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_examen_laboratorio_otros_especifique' + ind +'" name="des_pedriatria_examen_laboratorio_otros_especifique[]" value="" data-id='+ind+' />';    
    /** TERAPIA ANTIMICROBIANA **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_terapia_antimicrobiana' + ind +'" name="des_pedriatria_terapia_antimicrobiana[]" value="" data-id='+ind+' />';
    /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_1' + ind +'" name="des_pedriatria_frasco_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_1_opcion_1' + ind +'" name="des_pedriatria_frasco_1_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_1_opcion_2' + ind +'" name="des_pedriatria_frasco_1_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_2' + ind +'" name="des_pedriatria_frasco_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_2_opcion_1' + ind +'" name="des_pedriatria_frasco_2_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_2_opcion_2' + ind +'" name="des_pedriatria_frasco_2_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_3' + ind +'" name="des_pedriatria_frasco_3[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_3_opcion_1' + ind +'" name="des_pedriatria_frasco_3_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_3_opcion_2' + ind +'" name="des_pedriatria_frasco_3_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_hemocultivo_seguimiento_y_vigilancia' + ind +'" name="des_pedriatria_hemocultivo_seguimiento_y_vigilancia[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_fecha_primer_resultado_hemocultivo' + ind +'" name="des_pedriatria_fecha_primer_resultado_hemocultivo[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_solicitud_examen_especializados_hemocultivo_pedriatria' + ind +'"  name="flag_solicitud_examen_especializados_hemocultivo_pedriatria[]"  data-id='+ind+' value="0" />';
    newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoPedriatria" id="editarSolicitudExamenEspecializadoHemocultivoPedriatria' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';
    // ====================== Solicitud de Examenes Especiales HEMOCULTIVO NEONATO Y PEDRIATRÍA FIN ===============================================
    // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================================
    newRow +='<button type="button" class="btn btn-success btn-xs solicitudCitoquimicosLiquidosBiologicos" id="solicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_antecedentes_patologicos_personales' + ind +'" name="des_antecedentes_patologicos_personales[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_antecedentes_patologicos_familiares' + ind +'" name="des_antecedentes_patologicos_familiares[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_cefaloraquideo' + ind +'" name="des_liquido_cefaloraquideo[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_pleural' + ind +'" name="des_liquido_pleural[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_ascitico' + ind +'" name="des_liquido_ascitico[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_sinovial' + ind +'" name="des_liquido_sinovial[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_otro' + ind +'" name="des_liquido_otro[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_otro_especifique' + ind +'" name="des_liquido_otro_especifique[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_num_frasco_muestra_opcion' + ind +'" name="des_num_frasco_muestra_opcion[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_solicitud_citoquimicos_liquidos_biologicos' + ind +'"  name="flag_solicitud_citoquimicos_liquidos_biologicos[]"  data-id='+ind+' value="0" />';
    newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudCitoquimicosLiquidosBiologicos" id="editarSolicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';
    
    // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS FIN ===============================================
    newRow +='</td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
  $('#tblExamenLab tbody').append(newRow);
  // aqui abre el input para agregar el button agregar
  /*$("#examen_lab_cantidad" + ind).on('input', function() {
    var valor = $(this).val();
    if (valor === '0') {
      $(this).val(''); // Si ingresan 0, eliminarlo
    }
  });*/
	const selectElementLab = document.getElementById('miSelectLab' + ind);
  
  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id_diagnostico;
    option.textContent = item.nombre;
    selectElementLab.appendChild(option);
  });

  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  if (sexo == 'M')
    genero = 1;
  else
    genero = 2;
  //tipo=2
  $("#nombre_examen_lab" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '{{ url("/") }}/obtener_laboratorio_convenio_emergencia/' + request.term + '/' + nivel + '/' + genero + '/' + 2,
        //url: '../obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaLaboratorioSelecionado = $(".id_laboratorio").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaLaboratorioSelecionado.includes(element.id));
          response(filtro);
        }
      })

    },
    select: function(e, ui) {
      var id_examen_lab = ui.item.id;
      let codigo_procedimiento = ui.item.codigo_procedimiento;
      let id_categoria_examen_lab = ui.item.id_categoria;
      $("#cod_lab" + ind).val(ui.item.codigo_procedimiento);
      $("#id_examen_lab" + ind).val(id_examen_lab);
      $("#id_categoria_examen_lab" + ind).val(id_categoria_examen_lab);
      $("#nombre_examen_lab" + ind).prop("readonly", true);
      //error de función indefinida
      //validaExamenSeleccionado($("#id_examen_lab" + ind).val());  //Verificar función problema (not defined function)
      let dni_beneficiario = $('#dni_beneficiario').val();
      let id_examen_lab_alt1 = $("#id_examen_lab" + ind).val();
      console.log({
        'dni_beneficiario' : dni_beneficiario,
        'id_examen_lab' : id_examen_lab,
      });
      $.ajax({
      url: '/validacion_beneficiario_dias_por_examen_rua/' + dni_beneficiario + '/' + id_examen_lab_alt1,
      success: function (result) {
      let msg = "";
      let n = 0;
      console.log(["result",result]);
      if (result.examenes.length  > 0) {
        console.log(["result > 0",result]);
        for (let key of result.examenes) {
          if (n == 0) {
              msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.materno_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene análisis de laboratorio pendientes del examen seleccionado en los últimos "+result.dias.option_value+" días, CANTIDAD : " + key.total_registros + "</h1>";
              msg += "<table class='table table-list-search' style='font-size: 11px !important;' >"; 
              msg += "<thead>";
              msg += "<tr>";
              msg += "<th>Código</th>";
              msg += "<th>Descripción Corta</th>";
              msg += "<th>Tipo de Atención</th>";
              msg += "<th>Servicio</th>";
              msg += "<th>Especialista</th>";
              msg += "<th>Fecha de Orden</th>";
              msg += "<th>Dias Transcurridos</th>";
              msg += "</tr>";
              msg += "</thead>";
              msg += "<tbody>";
          }
          msg += "<tr><td>" + key.codigo_lab + "</td>";
          msg += "<td>" + key.descripcion_corta + "</td>";
          if (key.id_tipo_atencion == 1) {
            msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
          }
          if (key.id_tipo_atencion == 2) {
            if (key.id_evaluacion > 0) {
              msg += "<td>" + `EMERGENCIA - EVALUACIÓN` + "</td>";
            } else {
              msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
            }
          }
          if (key.id_tipo_atencion == 1) {
            msg += "<td>" + `${key.nombre_servicio}` + "</td>";
          }
          if (key.id_tipo_atencion == 2) {
            msg += "<td>" + `${key.nombre_servicio_emergencia}` + "</td>";
          }
          msg += "<td>" + `${key.medico_paterno} ${key.medico_materno}, ${key.medico_nombre}` + "</td>";
          msg += "<td>" + (key.fecha_expedicion) + "</td>";
          msg += "<td>" + (key.dias_transcurridos) + "</td></tr>";
          n++;
        }
        msg += "</tbody></table>";
        $("#contenido-table-pendiente").html(msg);
        // Swal.fire({
          //   title: "Alerta Laboratorio",
          //   html: msg,
          //   width: 1000  
          // });
          $("#showDetalleExamenPendiente").modal(); 
        }
      }
      });
      //consultaWsResultExamenSeleccionado($("#id_examen_lab" + ind).val()); //Uncaught ReferenceError: consultaWsResultExamenSeleccionado is not defined
      let nombreCompleto = `${$('#paterno_beneficiario').val()} ${$('#materno_beneficiario').val()}, ${$('#nombre_beneficiario').val()}`;
      // console.log({
      //   'dni_beneficiario' : dni_beneficiario,
      //   'id_examen_lab' : id_examen_lab,
      // });
      $.ajax({
        url: '/consulta_ws_result_beneficiario_examen_rua/' + dni_beneficiario + '/' + id_examen_lab_alt1,
        success: function (result) {
          let res = result;
          console.log("valor ws");
          console.log(res);

          let numerosImpresos = new Set();
          let n = 0;
          let n2 = 0;
          let msg = "";
          
          if (res == "vacio") {
            return;
          }
          if (res.length > 0) {
            console.log("Entro en la condición");
            if (n == 0) {
                  // msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + nombreCompleto + " con DNI " + dni_beneficiario + " tiene resultados de la prueba de laboratorio seleccionado de los últimos 3 meses</h1>";
                  msg += `<table class="table table-list-search" style="font-size: 11px !important;">`; 
                  msg += `<thead class="thead-resultados">`;
                  msg += "<tr>";
                  msg += `<th class="encabezado"></th>`;
                  msg += `<th class="encabezado">Código de orden</th>`;
                  msg += `<th class="encabezado">F.Toma de Muestra</th>`;
                  msg += `<th class="encabezado">Tipo de Atención</th>`;
                  msg += `<th class="encabezado">Establecimiento</th>`;
                  msg += "</tr>";
                  msg += "</thead>";
                  msg += "<tbody>";
              }
            for (let key of res) {
              console.log( key.p_nombre );
              let numeroActual = key.o_numero;
              let elementoOcultar = "elementoOcultarResult" + n;
              console.log(numeroActual);
              // Verificar si el número ya se ha impreso
              if (!numerosImpresos.has(numeroActual)) {
                // Si no se ha impreso, imprimir y agregar al conjunto
                console.log('o_numero:', numeroActual);
                
                msg += `<tr><td><a class="toggleExamenesResult" data-registro="${n}"><i class="fa fa-plus-circle icon-visible" aria-hidden="true"></i></a></td>`;
                msg += `<td>${numeroActual}</td>`;
                msg += `<td>${key.o_fecha_toma}</td>`;
                msg += `<td>${key.s_nombre}</td>`;
                msg += `<td>${key.c_nombre}</td>`;
                msg += `</tr>`;
                msg += `<tr class="thead-resultados-detalle ${elementoOcultar} hide-element-result">`;
                msg += `<th class="encabezado">Examen</th>`;
                msg += `<th class="encabezado">Resultado</th>`;
                msg += `<th class="encabezado">Rango Referencial</th>`;
                msg += "</tr>";
                numerosImpresos.add(numeroActual);
                n2 = n;
                n++;
              }
              elementoOcultar = "elementoOcultarResult" + n2;
              msg += `<tr class="tbody-resultados-detalle ${elementoOcultar} hide-element-result">`
              msg += `<td>${key.p_nombre}</td>`;
              msg += `<td>${key.l_resultado}</td>`;
              msg += `<td>${key.l_ref_inf} - ${key.l_ref_sup}</td>`;
              msg += "</tr>";
                
              
            }
            //console.table(result);
              msg += "</tbody></table>";
              $("#contenido-table-result").html(msg);
              // Swal.fire({
              //   title: "Resultados Laboratorio",
              //   html: msg,
              //   width: 1000  
              // });
              
              $("#showDetalleExamenResult").modal(); 
          }
        }
      });
      console.log(`id_categoria : ${id_categoria_examen_lab}`);
      if (id_categoria_examen_lab == "26") {
        // mostrarBotonParaTipoDeMuestraMicrobiologia(ind);
      }
      // SOLICITUD DE EXAMEN PARA HEMOCULTIVO
      if (codigo_procedimiento == '87040') {
        let fecnacafiliado = $('#fecnacafiliado').val();
        let partesFecha = fecnacafiliado.split('/');
        let fechaFormateada = `${partesFecha[2]}-${partesFecha[1]}-${partesFecha[0]}`;
        // Convertir la fecha de nacimiento a un objeto Date
        let fechaNacimiento = new Date(fechaFormateada);
        console.log(`Fecha nacimiento : ${fechaNacimiento}`);

        // Obtener la fecha actual
        let fechaActual = new Date();
        console.log(`Fecha actual : ${fechaActual}`);

        // Calcular la diferencia de años entre la fecha de nacimiento y la fecha actual
        let edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
        // Ajustar la edad si todavía no se ha alcanzado el cumpleaños en el año actual
        if (fechaActual.getMonth() < fechaNacimiento.getMonth() || (fechaActual.getMonth() === fechaNacimiento.getMonth() && fechaActual.getDate() < fechaNacimiento.getDate())) {
            edad--;
          }
          console.log(`Edad : ${edad}`);
        // Validar la edad y mostrar los botones correspondientes
        if (edad <= 14) {
          // SOLICITUD DE EXAMEN PARA HEMOCULTIVO NEONATO Y PEDIATRÍA
          // mostrarBotonParaSolicitudHemocultivoPedriatria(ind);
        } else {
          // SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO
          // mostrarBotonParaSolicitudHemocultivoAdulto(ind);
        }
      }
      // SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS
      if (codigo_procedimiento == '87163') {
        // mostrarBotonParaSolicitudCitoquimicosLiquidosBiologicos(ind);
      }

       //valida las examenes depeendidntes
      var seleccionados = $("input[name='cod_lab[]']").map(function () {
          return $(this).val();
      }).get();

      if (verificarFaltantes(seleccionados) != "") {
          alert(verificarFaltantes(seleccionados));
      }
    }
  });

  $("#nombre_examen_lab" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar el examen laboratorio en esta fila");
      // $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });

  /*$("#examen_lab_cantidad" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen laboratorio");
      // $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });*/

}

const mostrarBotonParaSolicitudCitoquimicosLiquidosBiologicos = (ind) => {
  $('#solicitudCitoquimicosLiquidosBiologicos' + ind).show();
  $('#flag_solicitud_citoquimicos_liquidos_biologicos' + ind).val('1');
  $('#descripcion_examen' + ind).val('CITOQUIMICO LIQUIDOS BIOLOGICOS');
}

// ========== REGISTRAR SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO INICIO =========
$(document).on('click','.solicitudCitoquimicosLiquidosBiologicos', function () {
  let i = $(this).data('id');
  $('#solicitud_citoquimicos_liquidos_biologicos_data_id').val(i);
  $('#antecedentes_patologicos_personales').val( $('#antecedente').val() )
  $('#antecedentes_patologicos_familiares').val( $('#antecedente_familiar').val() )
  $('#modalSolicitudCitoquimicosLiquidosBiologicos').modal();

});
$(document).on('click', '#btnGuardarRegistroSolicitudCitoquimicosLiquidosBiologicos', function(e) {
  e.preventDefault();
  validarRegistroSolicitudCitoquimicosLiquidosBiologicos();
});

const validarRegistroSolicitudCitoquimicosLiquidosBiologicos = () => {
  let msj = ''
  
  let antecedentes_patologicos_personales = $('#antecedentes_patologicos_personales').val()
  let antecedentes_patologicos_familiares = $('#antecedentes_patologicos_familiares').val()
  let liquido_cefaloraquideo = $('#liquido_cefaloraquideo').is(':checked');
  let liquido_pleural = $('#liquido_pleural').is(':checked');
  let liquido_ascitico = $('#liquido_ascitico').is(':checked');
  let liquido_sinovial = $('#liquido_sinovial').is(':checked');
  let liquido_otro = $('#liquido_otro').is(':checked');

  let liquido_otro_especifique = $('#liquido_otro_especifique').val()

  if ($('.grupo_checkbox_citoquimico_liquidos_biologicos:checked').length === 0) {
      msj += 'Debe seleccionar al menos un tipo de líquido biológico.<br>'
  }
  
  if ($('#liquido_otro').is(':checked')) {
      if ($('#liquido_otro_especifique').val().trim() === '') {
          msj += 'Debe especificar el líquido otro.<br>'
      }
  }
  let num_frasco_muestra_opcion = $('#num_frasco_muestra_opcion').val() //  NÚMERO DE FRASCO DE MUESTRA
  if (num_frasco_muestra_opcion == '0') {
    msj += 'Debe seleccionar el número de frasco.<br>'
  }

  if (msj !== '') {
    Swal.fire({
        icon: 'error',
        title: 'Alerta',
        html: msj,
        showConfirmButton: true,
    });
    return;
  }

  let i = $('#solicitud_citoquimicos_liquidos_biologicos_data_id').val();

  $('#des_antecedentes_patologicos_personales' + i).val(antecedentes_patologicos_personales)
  $('#des_antecedentes_patologicos_familiares' + i).val(antecedentes_patologicos_familiares)
  $('#des_liquido_cefaloraquideo' + i).val(liquido_cefaloraquideo)
  $('#des_liquido_pleural' + i).val(liquido_pleural)
  $('#des_liquido_ascitico' + i).val(liquido_ascitico)
  $('#des_liquido_sinovial' + i).val(liquido_sinovial)
  $('#des_liquido_otro' + i).val(liquido_otro)
  $('#des_liquido_otro_especifique' + i).val(liquido_otro_especifique)
  $('#des_num_frasco_muestra_opcion' + i).val(num_frasco_muestra_opcion)
  
  $('#modalSolicitudCitoquimicosLiquidosBiologicos').modal('hide');
  //========================== LIMPIAR CAMPOS ==========================
  formatearCamposModalSolicitudCitoquimicosLiquidosBiologicos()
  //=========================================

  Swal.fire({
      icon: 'success',
      title: 'Se guardo con exito!',
      showConfirmButton: false,
      timer: 2000,
      onClose: function() {
        //console.log(`Se guardo el tipo de muestra en el input ${i}`);
        $('#solicitudCitoquimicosLiquidosBiologicos' + i).hide();
        $('#editarSolicitudCitoquimicosLiquidosBiologicos' + i).show();
      }
  });
}

const formatearCamposModalSolicitudCitoquimicosLiquidosBiologicos = () => {
  
  $('#solicitud_citoquimicos_liquidos_biologicos_data_id').val('')
  $('#antecedentes_patologicos_personales').val('')
  $('#antecedentes_patologicos_familiares').val('')

  $('#liquido_cefaloraquideo').prop('checked', false)
  $('#liquido_pleural').prop('checked', false)
  $('#liquido_ascitico').prop('checked', false)
  $('#liquido_sinovial').prop('checked', false)
  $('#liquido_otro').prop('checked', false)
  $('#liquido_otro_especifique').val('')
  $('#liquido_otro_especifique').hide()
  $('#num_frasco_muestra_opcion').val('0')

}

$(document).ready(function() {
    $('#modalSolicitudCitoquimicosLiquidosBiologicos').on('hidden.bs.modal', function() {
        // Limpiamos los campos de entrada al ocultar el modal
        formatearCamposModalSolicitudCitoquimicosLiquidosBiologicos()
    });
});
// ========== REGISTRAR SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO FIN =========
// ========== EDITAR SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO INICIO =========
$(document).on('click','.editarSolicitudCitoquimicosLiquidosBiologicos', function () {
  
  let i = $(this).data('id');
  $('#solicitud_citoquimicos_liquidos_biologicos_data_id').val(i);  
  //------------------------------------------------
  $('#antecedentes_patologicos_personales').val( $('#des_antecedentes_patologicos_personales' + i).val() ) 
  $('#antecedentes_patologicos_familiares').val( $('#des_antecedentes_patologicos_familiares' + i).val() )
  
  if ($('#des_liquido_cefaloraquideo' + i).val() == 'true') {
    $('#liquido_cefaloraquideo').prop('checked', true)
  } else {
    $('#liquido_cefaloraquideo').prop('checked', false)
  }
  
  if ($('#des_liquido_pleural' + i).val() == 'true') {
    $('#liquido_pleural').prop('checked', true)
  } else {
    $('#liquido_pleural').prop('checked', false)
  }
  
  if ($('#des_liquido_ascitico' + i).val() == 'true') {
    $('#liquido_ascitico').prop('checked', true)
  } else {
    $('#liquido_ascitico').prop('checked', false)
  }
  
  if ($('#des_liquido_sinovial' + i).val() == 'true') {
    $('#liquido_sinovial').prop('checked', true)
  } else {
    $('#liquido_sinovial').prop('checked', false)
  }
  
  if ($('#des_liquido_otro' + i).val() == 'true') {
    $('#liquido_otro_especifique').val( $('#des_liquido_otro_especifique' + i).val() )
    $('#liquido_otro_especifique').show()
    $('#liquido_otro').prop('checked', true)
  } else {
    $('#liquido_otro_especifique').val('')
    $('#liquido_otro_especifique').hide()
    $('#liquido_otro').prop('checked', false)
  }
  
  $('#num_frasco_muestra_opcion').val( $('#des_num_frasco_muestra_opcion' + i).val() )
  
  //------------------------------------------------
  
  $("#modalSolicitudCitoquimicosLiquidosBiologicos").modal();
  
});
// ========== EDITAR SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO FIN =========


const mostrarBotonParaSolicitudHemocultivoPedriatria = (ind) => {
  $('#solicitudExamenEspecializadoHemocultivoPedriatria' + ind).show();
  $('#flag_solicitud_examen_especializados_hemocultivo_pedriatria' + ind).val('1');
  $('#descripcion_examen' + ind).val('HEMOCULTIVO NEONATO Y PEDRIATRIA');
}
// ========== REGISTRAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO PEDRIATRIA INICIO =========
$(document).on('click','.solicitudExamenEspecializadoHemocultivoPedriatria', function () {
  
  let i = $(this).data('id');
  $('#solicitud_hemocultivo_pedriatria_data_id').val(i);
  let id_diagnostico = $('#miSelectLab' + i).val();
  if (id_diagnostico == 3806) { // cia10  I33.0
    console.log('Entro como cia10 i33.0');
    $('#pedriatria_contenedor_frasco_3').show();
  } else { 
    $('#pedriatria_contenedor_frasco_3').hide();
  }
  // SIGNOS VITALES DE ATENCIÓN MÉDICA A SOLICITUD HEMOCULTIVO
  $('#pedriatria_presion_arterial_x').val( $('#presion_arterial_x').val() );
  $('#pedriatria_presion_arterial_y').val( $('#presion_arterial_y').val() );
  $('#pedriatria_frecuencia_cardiaca').val( $('#frec_cardiaca').val() );
  $('#pedriatria_frecuencia_respiratorio').val( $('#frecuencia_respiratoria').val() );
  $('#pedriatria_temperatura').val( $('#temperatura').val() );

  $('#btnGuardarRegistroSolicitudHemocultivoPedriatria').show();
  $("#modalSolicitudHemocultivoPedriatria").modal();

});
$(document).on('click', '#btnGuardarRegistroSolicitudHemocultivoPedriatria', function(e) {
  e.preventDefault();
  validarRegistroSolicitudHemocultivoPedriatria();
});
const validarRegistroSolicitudHemocultivoPedriatria = () => {
  let msj = ''
  let pedriatria_fecha_tiempo_enfermedad = $('#pedriatria_fecha_tiempo_enfermedad').val() //  TIEMPO DE ENFERMEDAD
  if (pedriatria_fecha_tiempo_enfermedad == '') {
    msj = 'Debe indicar el Tiempo de Enfermedad.<br>'
  }
  //  EDAD GESTIONAL
  let pedriatria_postermino = $('#pedriatria_postermino').prop('checked')
  let pedriatria_termino = $('#pedriatria_termino').prop('checked')
  let pedriatria_prematuro_leve = $('#pedriatria_prematuro_leve').prop('checked')
  let pedriatria_prematuro_moderado = $('#pedriatria_prematuro_moderado').prop('checked')
  let pedriatria_prematuro_extremo = $('#pedriatria_prematuro_extremo').prop('checked')
  if (pedriatria_postermino || pedriatria_termino || pedriatria_prematuro_leve || pedriatria_prematuro_moderado || pedriatria_prematuro_extremo) {
    console.log("Al menos uno de los checkboxes está seleccionado.");
  } else {
    msj += 'Debe marcar una opción en Edad Gestional.<br>'
  }
  //  APGAR
  let pedriatria_apariencia = $('#pedriatria_apariencia').val()
  if (pedriatria_apariencia == '') {
    msj += 'Debe indicar el campo apariencia.<br>'
  }
  let pedriatria_pulso = $('#pedriatria_pulso').val()
  if (pedriatria_pulso == '') {
    msj += 'Debe indicar el campo pulso.<br>'
  }
  let pedriatria_gesto = $('#pedriatria_gesto').val()
  if (pedriatria_gesto == '') {
    msj += 'Debe indicar el campo gesto.<br>'
  }
  let pedriatria_actividad_respiratoria = $('#pedriatria_actividad_respiratoria').val()
  if (pedriatria_actividad_respiratoria == '') {
    msj += 'Debe indicar el campo actividad respiratoria.<br>'
  }
  let pedriatria_tono_muscular = $('#pedriatria_tono_muscular').val()
  if (pedriatria_tono_muscular == '') {
    msj += 'Debe indicar el campo tono muscular.<br>'
  }
  let pedriatria_total_apgar = $('#pedriatria_total_apgar').val()
  if (pedriatria_total_apgar == '') {
    msj += 'Debe indicar el campo total apgar.<br>'
  }

  //  ESCALA DE GLASGOW
  let pedriatria_respuesta_ocular = $('#pedriatria_respuesta_ocular').val() 
  if (pedriatria_respuesta_ocular == '') {
    msj += 'Debe indicar la Respuesta Ocular.<br>'
  }
  let pedriatria_respuesta_verbal = $('#pedriatria_respuesta_verbal').val() 
  if (pedriatria_respuesta_verbal == '') {
    msj += 'Debe indicar la Respuesta Verbal.<br>'
  }
  let pedriatria_respuesta_motora = $('#pedriatria_respuesta_motora').val() 
  if (pedriatria_respuesta_motora == '') {
    msj += 'Debe indicar la Respuesta Motora.<br>'
  }
  let pedriatria_total_escala_glasgow = $('#pedriatria_total_escala_glasgow').val() 
  if (pedriatria_total_escala_glasgow == '') {
    msj += 'Debe indicar el Total.<br>'
  }
  // SIGNOS VITALES
  let pedriatria_presion_arterial_x = $('#pedriatria_presion_arterial_x').val() 
  if (pedriatria_presion_arterial_x == '') {
    msj += 'Debe indicar la presión arterial x.<br>'
  }
  let pedriatria_presion_arterial_y = $('#pedriatria_presion_arterial_y').val() 
  if (pedriatria_presion_arterial_y == '') {
    msj += 'Debe indicar la presión arterial y.<br>'
  }
  let pedriatria_frecuencia_cardiaca = $('#pedriatria_frecuencia_cardiaca').val() 
  if (pedriatria_frecuencia_cardiaca == '') {
    msj += 'Debe indicar la frecuencia cardiaca.<br>'
  }
  let pedriatria_frecuencia_respiratorio = $('#pedriatria_frecuencia_respiratorio').val() 
  if (pedriatria_frecuencia_respiratorio == '') {
    msj += 'Debe indicar la frecuencia respiratoria.<br>'
  }
  let pedriatria_temperatura = $('#pedriatria_temperatura').val() 
  if (pedriatria_temperatura == '') {
    msj += 'Debe indicar la temperatura.<br>'
  }
  // EXÁMENES DE LABORATORIO
  let pedriatria_creatinina = $('#pedriatria_creatinina').val()
  let pedriatria_diuresis = $('#pedriatria_diuresis').val()
  let pedriatria_procalcitonina = $('#pedriatria_procalcitonina').val()
  let pedriatria_pcr = $('#pedriatria_pcr').val()
  let pedriatria_fiO2 = $('#pedriatria_fiO2').val()
  let pedriatria_leucocitos = $('#pedriatria_leucocitos').val()
  if (pedriatria_leucocitos == '') {
    msj += 'Debe indicar el campo leucocitos.<br>'
  }
  let pedriatria_plaquetas = $('#pedriatria_plaquetas').val()
  if (pedriatria_plaquetas == '') {
    msj += 'Debe indicar el campo plaquetas.<br>'
  }
  let pedriatria_bilirrubina = $('#pedriatria_bilirrubina').val()
  
  let pedriatria_glucosa = $('#pedriatria_glucosa').val()
  if (pedriatria_glucosa == '') {
    msj += 'Debe indicar el campo glucosa.<br>'
  }
  let pedriatria_alt = $('#pedriatria_alt').val()
  let pedriatria_vcg = $('#pedriatria_vcg').val()
  let pedriatria_tp = $('#pedriatria_tp').val()
  let pedriatria_sodio = $('#pedriatria_sodio').val()
  let pedriatria_potasio = $('#pedriatria_potasio').val()
  let pedriatria_calcio = $('#pedriatria_calcio').val()
  let pedriatria_pco2 = $('#pedriatria_pco2').val()

  let pedriatria_tpta = $('#pedriatria_tpta').val()
  let pedriatria_examen_laboratorio_otros = $('#pedriatria_examen_laboratorio_otros').attr("aria-pressed")
  let pedriatria_examen_laboratorio_otros_especifique = $('#pedriatria_examen_laboratorio_otros_especifique').val()
  // TERAPIA ANTIMICROBIANA
  let pedriatria_terapia_antimicrobiana = $('#pedriatria_terapia_antimicrobiana').attr("aria-pressed")
  if (pedriatria_terapia_antimicrobiana == 'false') {
    msj += 'Debe ser obligatorio terapia antimicrobiana.<br>'
  }
  // SOLICITUD DE FRASCO DE HEMOCULTIVO
  let pedriatria_frasco_1 = $('#pedriatria_frasco_1').attr("aria-pressed")
  if (pedriatria_frasco_1 == 'false') {
    msj += 'Debe ser obligatorio el frasco n° 1.<br>'
  } else {
    let pedriatria_frasco_1_opcion_1 = $('#pedriatria_frasco_1_opcion_1').val()
    if (pedriatria_frasco_1_opcion_1 == '0') {
      msj += 'Debe seleccionar la primera opcion del frasco n° 1.<br>'
    }
    let pedriatria_frasco_1_opcion_2 = $('#pedriatria_frasco_1_opcion_2').val()
    if (pedriatria_frasco_1_opcion_2 == '0') {
      msj += 'Debe seleccionar la segunda opcion del frasco n° 1.<br>'
    }
  }
  let pedriatria_frasco_2 = $('#pedriatria_frasco_2').attr("aria-pressed")
  if (pedriatria_frasco_2 == 'false') {
    msj += 'Debe ser obligatorio el frasco n° 2.<br>'
  } else {
    let pedriatria_frasco_2_opcion_1 = $('#pedriatria_frasco_2_opcion_1').val()
    if (pedriatria_frasco_2_opcion_1 == '0') {
      msj += 'Debe seleccionar la primera opcion del frasco n° 2.<br>'
    }
    let pedriatria_frasco_2_opcion_2 = $('#pedriatria_frasco_2_opcion_2').val()
    if (pedriatria_frasco_2_opcion_2 == '0') {
      msj += 'Debe seleccionar la segunda opcion del frasco n° 2.<br>'
    }
  }

  let y = $('#solicitud_hemocultivo_pedriatria_data_id').val();
  let id_diagnostico = $('#miSelectLab' + y).val();
  if (id_diagnostico == 3806) { // cia10  I33.0 
    let pedriatria_frasco_3 = $('#pedriatria_frasco_3').attr("aria-pressed")
    if (pedriatria_frasco_3 == 'false') {
      msj += 'Debe ser obligatorio el frasco n° 3.<br>'
    } else {
      let pedriatria_frasco_3_opcion_1 = $('#pedriatria_frasco_3_opcion_1').val()
      if (pedriatria_frasco_3_opcion_1 == '0') {
        msj += 'Debe seleccionar la primera opcion del frasco n° 3.<br>'
      }
      let pedriatria_frasco_3_opcion_2 = $('#pedriatria_frasco_3_opcion_2').val()
      if (pedriatria_frasco_3_opcion_2 == '0') {
        msj += 'Debe seleccionar la segunda opcion del frasco n° 3.<br>'
      }
    }
  }
  let pedriatria_hemocultivo_seguimiento_y_vigilancia = $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed")
  if (pedriatria_hemocultivo_seguimiento_y_vigilancia == 'true') {
    let pedriatria_fecha_primer_resultado_hemocultivo = $('#pedriatria_fecha_primer_resultado_hemocultivo').val()
    if (pedriatria_fecha_primer_resultado_hemocultivo == '') {
      msj += 'Debe indicar la fecha del primer resultado de hemocultivo.<br>'
    }
  }
  if (msj !== '') {
    //bootbox.alert(msj)
    Swal.fire({
        icon: 'error',
        title: 'Alerta',
        html: msj,
        showConfirmButton: true,
    });
    return;
  }
  let i = $('#solicitud_hemocultivo_pedriatria_data_id').val();
  $('#des_pedriatria_fecha_tiempo_enfermedad' + i).val(pedriatria_fecha_tiempo_enfermedad);
  //  EDAD GESTIONAL
  $('#des_pedriatria_postermino' + i).val(pedriatria_postermino);
  $('#des_pedriatria_termino' + i).val(pedriatria_termino);
  $('#des_pedriatria_prematuro_leve' + i).val(pedriatria_prematuro_leve);
  $('#des_pedriatria_prematuro_moderado' + i).val(pedriatria_prematuro_moderado);
  $('#des_pedriatria_prematuro_extremo' + i).val(pedriatria_prematuro_extremo);
  //  APGAR
  $('#des_pedriatria_apariencia' + i).val(pedriatria_apariencia)
  $('#des_pedriatria_pulso' + i).val(pedriatria_pulso)
  $('#des_pedriatria_gesto' + i).val(pedriatria_gesto)
  $('#des_pedriatria_actividad_respiratoria' + i).val(pedriatria_actividad_respiratoria)
  $('#des_pedriatria_tono_muscular' + i).val(pedriatria_tono_muscular)
  $('#des_pedriatria_total_apgar' + i).val(pedriatria_total_apgar)
  /** ESCALA DE GLASGOW **/
  $('#des_pedriatria_respuesta_ocular' + i).val(pedriatria_respuesta_ocular);
  $('#des_pedriatria_respuesta_verbal' + i).val(pedriatria_respuesta_verbal);
  $('#des_pedriatria_respuesta_motora' + i).val(pedriatria_respuesta_motora);
  $('#des_pedriatria_total_escala_glasgow' + i).val(pedriatria_total_escala_glasgow);
  /** SIGNOS VITALES **/
  $('#des_pedriatria_presion_arterial_x' + i).val(pedriatria_presion_arterial_x);
  $('#des_pedriatria_presion_arterial_y' + i).val(pedriatria_presion_arterial_y);
  $('#des_pedriatria_frecuencia_cardiaca' + i).val(pedriatria_frecuencia_cardiaca);
  $('#des_pedriatria_frecuencia_respiratorio' + i).val(pedriatria_frecuencia_respiratorio);
  $('#des_pedriatria_temperatura' + i).val(pedriatria_temperatura);
  /** EXÁMENES DE LABORATORIO **/
  $('#des_pedriatria_creatinina' + i).val(pedriatria_creatinina);
  $('#des_pedriatria_diuresis' + i).val(pedriatria_diuresis);
  $('#des_pedriatria_procalcitonina' + i).val(pedriatria_procalcitonina);
  $('#des_pedriatria_pcr' + i).val(pedriatria_pcr);
  $('#des_pedriatria_fiO2' + i).val(pedriatria_fiO2);
  $('#des_pedriatria_leucocitos' + i).val(pedriatria_leucocitos);
  $('#des_pedriatria_plaquetas' + i).val(pedriatria_plaquetas);
  $('#des_pedriatria_bilirrubina' + i).val(pedriatria_bilirrubina);
  
  $('#des_pedriatria_glucosa' + i).val(pedriatria_glucosa)
  $('#des_pedriatria_alt' + i).val(pedriatria_alt)
  $('#des_pedriatria_vcg' + i).val(pedriatria_vcg)
  $('#des_pedriatria_tp' + i).val(pedriatria_tp)
  $('#des_pedriatria_sodio' + i).val(pedriatria_sodio)
  $('#des_pedriatria_potasio' + i).val(pedriatria_potasio)
  $('#des_pedriatria_calcio' + i).val(pedriatria_calcio)
  $('#des_pedriatria_pco2' + i).val(pedriatria_pco2)

  $('#des_pedriatria_tpta' + i).val(pedriatria_tpta)
  $('#des_pedriatria_examen_laboratorio_otros' + i).val(pedriatria_examen_laboratorio_otros)
  $('#des_pedriatria_examen_laboratorio_otros_especifique' + i).val(pedriatria_examen_laboratorio_otros_especifique)
  
  /** TERAPIA ANTIMICROBIANA **/
  $('#des_pedriatria_terapia_antimicrobiana' + i).val(pedriatria_terapia_antimicrobiana);
  /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
  $('#des_pedriatria_frasco_1' + i).val(pedriatria_frasco_1)
  $('#des_pedriatria_frasco_1_opcion_1' + i).val( $('#pedriatria_frasco_1_opcion_1').val() )
  $('#des_pedriatria_frasco_1_opcion_2' + i).val( $('#pedriatria_frasco_1_opcion_2').val() )
  $('#des_pedriatria_frasco_2' + i).val(pedriatria_frasco_2)
  $('#des_pedriatria_frasco_2_opcion_1' + i).val( $('#pedriatria_frasco_2_opcion_1').val() )
  $('#des_pedriatria_frasco_2_opcion_2' + i).val( $('#pedriatria_frasco_2_opcion_2').val() )
  $('#des_pedriatria_frasco_3' + i).val( $('#pedriatria_frasco_3').attr("aria-pressed") )
  $('#des_pedriatria_frasco_3_opcion_1' + i).val( $('#pedriatria_frasco_3_opcion_1').val() )
  $('#des_pedriatria_frasco_3_opcion_2' + i).val( $('#pedriatria_frasco_3_opcion_2').val() )
  $('#des_pedriatria_hemocultivo_seguimiento_y_vigilancia' + i).val(pedriatria_hemocultivo_seguimiento_y_vigilancia)
  $('#des_pedriatria_fecha_primer_resultado_hemocultivo' + i).val( $('#pedriatria_fecha_primer_resultado_hemocultivo').val() ) 

  $('#modalSolicitudHemocultivoPedriatria').modal('hide');
  //========================== LIMPIAR CAMPOS ==========================
  formatearCamposModalSolicitudHemocultivoPedriatria()
  //=========================================

  Swal.fire({
      icon: 'success',
      title: 'Se guardo con exito!',
      showConfirmButton: false,
      timer: 2000,
      onClose: function() {
        //console.log(`Se guardo el tipo de muestra en el input ${i}`);
        $('#solicitudExamenEspecializadoHemocultivoPedriatria' + i).hide();
        $('#editarSolicitudExamenEspecializadoHemocultivoPedriatria' + i).show();
      }
  });
}

const formatearCamposModalSolicitudHemocultivoPedriatria = () => {
  $('#solicitud_hemocultivo_pedriatria_data_id').val('');
  $('#pedriatria_fecha_tiempo_enfermedad').val('');
  //  EDAD GESTIONAL
  $('#pedriatria_postermino').prop('checked', false);
  $('#pedriatria_termino').prop('checked', false);
  $('#pedriatria_prematuro_leve').prop('checked', false);
  $('#pedriatria_prematuro_moderado').prop('checked', false);
  $('#pedriatria_prematuro_extremo').prop('checked', false);
  //  APGAR
  $('#pedriatria_apariencia').val('')
  $('#pedriatria_pulso').val('')
  $('#pedriatria_gesto').val('')
  $('#pedriatria_actividad_respiratoria').val('')
  $('#pedriatria_tono_muscular').val('')
  $('#pedriatria_total_apgar').val('')
  /** ESCALA DE GLASGOW **/
  $('#pedriatria_respuesta_ocular').val('');
  $('#pedriatria_respuesta_verbal').val('');
  $('#pedriatria_respuesta_motora').val('');
  $('#pedriatria_total_escala_glasgow').val('');
  /** SIGNOS VITALES **/
  $('#pedriatria_presion_arterial_x').val('') 
  $('#pedriatria_presion_arterial_y').val('') 
  $('#pedriatria_frecuencia_cardiaca').val('') 
  $('#pedriatria_frecuencia_respiratorio').val('') 
  $('#pedriatria_temperatura').val('') 
  /** EXÁMENES DE LABORATORIO **/
  $('#pedriatria_creatinina').val('')
  $('#pedriatria_diuresis').val('')
  $('#pedriatria_procalcitonina').val('')
  $('#pedriatria_pcr').val('')
  $('#pedriatria_fiO2').val('')
  $('#pedriatria_leucocitos').val('')
  $('#pedriatria_plaquetas').val('')
  $('#pedriatria_bilirrubina').val('')

  $('#pedriatria_glucosa').val('')
  $('#pedriatria_alt').val('')
  $('#pedriatria_vcg').val('')
  $('#pedriatria_tp').val('')
  $('#pedriatria_sodio').val('')
  $('#pedriatria_potasio').val('')
  $('#pedriatria_calcio').val('')
  $('#pedriatria_pco2').val('')
  $('#pedriatria_tpta').val('')
  $('#pedriatria_examen_laboratorio_otros').removeClass('active')
  $('#pedriatria_examen_laboratorio_otros').attr("aria-pressed", "false")
  $('#pedriatria_examen_laboratorio_otros_especifique').val('')
  $('#pedriatria_examen_laboratorio_otros_especifique').hide()
  /** TERAPIA ANTIMICROBIANA **/
  $('#pedriatria_terapia_antimicrobiana').removeClass('active')
  $('#pedriatria_terapia_antimicrobiana').attr("aria-pressed", "false")
  /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
  $('#pedriatria_frasco_1').removeClass('active')
  $('#pedriatria_frasco_1').attr("aria-pressed", "false")
  $('#pedriatria_frasco_1_opcion_1').val('0')
  $('#pedriatria_frasco_1_opcion_2').val('0')
  $('#pedriatria_frasco_1_opcion_1').hide()
  $('#pedriatria_frasco_1_opcion_2').hide()
  $('#pedriatria_frasco_2').removeClass('active')
  $('#pedriatria_frasco_2').attr("aria-pressed", "false")
  $('#pedriatria_frasco_2_opcion_1').val('0')
  $('#pedriatria_frasco_2_opcion_2').val('0')
  $('#pedriatria_frasco_2_opcion_1').hide()
  $('#pedriatria_frasco_2_opcion_2').hide()
  $('#pedriatria_frasco_3').removeClass('active')
  $('#pedriatria_frasco_3').attr("aria-pressed", "false")
  $('#pedriatria_frasco_3_opcion_1').val('0')
  $('#pedriatria_frasco_3_opcion_2').val('0')
  $('#pedriatria_frasco_3_opcion_1').hide()
  $('#pedriatria_frasco_3_opcion_2').hide()
  $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').removeClass('active')
  $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed", "false")
  $('#pedriatria_fecha_primer_resultado_hemocultivo').val('')
  $('#pedriatria_contenedor_fecha_primer_resultado_hemocultivo').hide()
}

$(document).ready(function() {
    $('#modalSolicitudHemocultivoPedriatria').on('hidden.bs.modal', function() {
        // Limpiamos los campos de entrada al ocultar el modal
        formatearCamposModalSolicitudHemocultivoPedriatria()
    });
});
// ========== REGISTRAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO PEDRIATRIA FIN =========
// ========== EDITAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO PEDRIATRIA INICIO =========
$(document).on('click','.editarSolicitudExamenEspecializadoHemocultivoPedriatria', function () {
  
  let i = $(this).data('id');
  $('#solicitud_hemocultivo_pedriatria_data_id').val(i);
  
  //------------------------------------------------
  //  TIEMPO DE ENFERMEDAD
  $('#pedriatria_fecha_tiempo_enfermedad').val( $('#des_pedriatria_fecha_tiempo_enfermedad' + i).val() ) 
  //  EDAD GESTIONAL
  if ($('#des_pedriatria_postermino' + i).val() == "true") {
    $('#pedriatria_postermino').prop('checked',true)
  }
  if ($('#des_pedriatria_termino' + i).val() == "true") {
    $('#pedriatria_termino').prop('checked',true)
  }
  if ($('#des_pedriatria_prematuro_leve' + i).val() == "true") {
    $('#pedriatria_prematuro_leve').prop('checked',true)
  }
  if ($('#des_pedriatria_prematuro_moderado' + i).val() == "true") {
    $('#pedriatria_prematuro_moderado').prop('checked',true)
  }
  if ($('#des_pedriatria_prematuro_extremo' + i).val() == "true") {
    $('#pedriatria_prematuro_extremo').prop('checked',true)
  }
  //  APGAR
  $('#pedriatria_apariencia').val( $('#des_pedriatria_apariencia' + i).val() )
  $('#pedriatria_pulso').val( $('#des_pedriatria_pulso' + i).val() )
  $('#pedriatria_gesto').val( $('#des_pedriatria_gesto' + i).val() )
  $('#pedriatria_actividad_respiratoria').val( $('#des_pedriatria_actividad_respiratoria' + i).val() )
  $('#pedriatria_tono_muscular').val( $('#des_pedriatria_tono_muscular' + i).val() )
  $('#pedriatria_total_apgar').val( $('#des_pedriatria_total_apgar' + i).val() )
  //  ESCALA DE GLASGOW
  $('#pedriatria_respuesta_ocular').val( $('#des_pedriatria_respuesta_ocular' + i).val() ) 
  $('#pedriatria_respuesta_verbal').val( $('#des_pedriatria_respuesta_verbal' + i).val() ) 
  $('#pedriatria_respuesta_motora').val( $('#des_pedriatria_respuesta_motora' + i).val() ) 
  $('#pedriatria_total_escala_glasgow').val( $('#des_pedriatria_total_escala_glasgow' + i).val() ) 
  // SIGNOS VITALES
  $('#pedriatria_presion_arterial_x').val( $('#des_pedriatria_presion_arterial_x' + i).val() )
  $('#pedriatria_presion_arterial_y').val( $('#des_pedriatria_presion_arterial_y' + i).val() )
  $('#pedriatria_frecuencia_cardiaca').val( $('#des_pedriatria_frecuencia_cardiaca' + i).val() )
  $('#pedriatria_frecuencia_respiratorio').val( $('#des_pedriatria_frecuencia_respiratorio' + i).val() ) 
  $('#pedriatria_temperatura').val( $('#des_pedriatria_temperatura' + i).val() ) 
  // EXÁMENES DE LABORATORIO
  $('#pedriatria_creatinina').val( $('#des_pedriatria_creatinina' + i).val() )
  $('#pedriatria_diuresis').val( $('#des_pedriatria_diuresis' + i).val() )
  $('#pedriatria_procalcitonina').val( $('#des_pedriatria_procalcitonina' + i).val() )
  $('#pedriatria_pcr').val( $('#des_pedriatria_pcr' + i).val() )
  $('#pedriatria_fiO2').val( $('#des_pedriatria_fiO2' + i).val() )
  $('#pedriatria_leucocitos').val( $('#des_pedriatria_leucocitos' + i).val() )
  $('#pedriatria_plaquetas').val( $('#des_pedriatria_plaquetas' + i).val() )
  $('#pedriatria_bilirrubina').val( $('#des_pedriatria_bilirrubina' + i).val() )

  $('#pedriatria_glucosa').val( $('#des_pedriatria_glucosa' + i).val() )
  $('#pedriatria_alt').val( $('#des_pedriatria_alt' + i).val() )
  $('#pedriatria_vcg').val( $('#des_pedriatria_vcg' + i).val() )
  $('#pedriatria_tp').val( $('#des_pedriatria_tp' + i).val() )
  $('#pedriatria_sodio').val( $('#des_pedriatria_sodio' + i).val() )
  $('#pedriatria_potasio').val( $('#des_pedriatria_potasio' + i).val() )
  $('#pedriatria_calcio').val( $('#des_pedriatria_calcio' + i).val() )
  $('#pedriatria_pco2').val( $('#des_pedriatria_pco2' + i).val() )
  $('#pedriatria_tpta').val( $('#des_pedriatria_tpta' + i).val() )
  if($('#des_pedriatria_examen_laboratorio_otros' + i).val() == 'true'){
    $('#pedriatria_examen_laboratorio_otros').attr("aria-pressed","true")
    $('#pedriatria_examen_laboratorio_otros').addClass("active")
    $('#pedriatria_examen_laboratorio_otros_especifique').val( $('#des_pedriatria_examen_laboratorio_otros_especifique' + i).val() )
    $('#pedriatria_examen_laboratorio_otros_especifique').show()
  }

  // TERAPIA ANTIMICROBIANA
  if($('#des_pedriatria_terapia_antimicrobiana' + i).val() == 'true'){
    $('#pedriatria_terapia_antimicrobiana').attr("aria-pressed","true")
    $('#pedriatria_terapia_antimicrobiana').addClass("active")
  }
  // SOLICITUD DE FRASCO DE HEMOCULTIVO
  if($('#des_pedriatria_frasco_1' + i).val() == 'true'){
    $('#pedriatria_frasco_1').attr("aria-pressed","true")
    $('#pedriatria_frasco_1').addClass("active")
    $('#pedriatria_frasco_1_opcion_1').val( $('#des_pedriatria_frasco_1_opcion_1' + i).val() ) 
    $('#pedriatria_frasco_1_opcion_2').val( $('#des_pedriatria_frasco_1_opcion_2' + i).val() ) 
    $('#pedriatria_frasco_1_opcion_1').show()
    $('#pedriatria_frasco_1_opcion_2').show()
  }
  if($('#des_pedriatria_frasco_2' + i).val() == 'true'){
    $('#pedriatria_frasco_2').attr("aria-pressed","true")
    $('#pedriatria_frasco_2').addClass("active")
    $('#pedriatria_frasco_2_opcion_1').val( $('#des_pedriatria_frasco_2_opcion_1' + i).val() ) 
    $('#pedriatria_frasco_2_opcion_2').val( $('#des_pedriatria_frasco_2_opcion_2' + i).val() ) 
    $('#pedriatria_frasco_2_opcion_1').show()
    $('#pedriatria_frasco_2_opcion_2').show()
  }
  if($('#des_pedriatria_frasco_3' + i).val() == 'true'){
    $('#pedriatria_frasco_3').attr("aria-pressed","true")
    $('#pedriatria_frasco_3').addClass("active")
    $('#pedriatria_frasco_3_opcion_1').val( $('#des_pedriatria_frasco_3_opcion_1' + i).val() ) 
    $('#pedriatria_frasco_3_opcion_2').val( $('#des_pedriatria_frasco_3_opcion_2' + i).val() ) 
    $('#pedriatria_frasco_3_opcion_1').show()
    $('#pedriatria_frasco_3_opcion_2').show()
  }
  if($('#des_pedriatria_hemocultivo_seguimiento_y_vigilancia' + i).val() == 'true'){
    $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed","true")
    $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').addClass("active")
    $('#pedriatria_fecha_primer_resultado_hemocultivo').val( $('#des_pedriatria_fecha_primer_resultado_hemocultivo' + i).val() )
    $('#pedriatria_contenedor_fecha_primer_resultado_hemocultivo').show()
  }
  //------------------------------------------------

  // let descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + i).val();
  // $('#solicitud_edit_descripcion_tipo_muestra').val(descripcion_tipo_muestra);
  $("#modalSolicitudHemocultivoPedriatria").modal();
  
});
// ========== EDITAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO PEDRIATRIA FIN =========

const mostrarBotonParaSolicitudHemocultivoAdulto = (ind) => {
  $('#solicitudExamenEspecializadoHemocultivoAdulto' + ind).show();
  $('#flag_solicitud_examen_especializados_hemocultivo_adulto' + ind).val('1');
  $('#descripcion_examen' + ind).val('HEMOCULTIVO ADULTO');
}

// ========== REGISTRAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO INICIO =========
$(document).on('click','.solicitudExamenEspecializadoHemocultivoAdulto', function () {
  
  let i = $(this).data('id');
  $('#solicitud_hemocultivo_adulto_data_id').val(i);
  let id_diagnostico = $('#miSelectLab' + i).val();
  if (id_diagnostico == 3806) { // cia10  I33.0
    console.log('Entro como cia10 i33.0');
    $('#adulto_contenedor_frasco_3').show();
  } else { 
    $('#adulto_contenedor_frasco_3').hide();
  }

  // SIGNOS VITALES DE ATENCIÓN MÉDICA A SOLICITUD HEMOCULTIVO
  $('#adulto_presion_arterial_x').val( $('#presion_arterial_x').val() );
  $('#adulto_presion_arterial_y').val( $('#presion_arterial_y').val() );
  $('#adulto_frecuencia_cardiaca').val( $('#frec_cardiaca').val() );
  $('#adulto_frecuencia_respiratorio').val( $('#frecuencia_respiratoria').val() );
  $('#adulto_temperatura').val( $('#temperatura').val() );

  $("#modalSolicitudHemocultivoAdulto").modal();
});

$(document).on('click', '#btnGuardarRegistroSolicitudHemocultivoAdulto', function(e) {
  e.preventDefault();
  validarRegistroSolicitudHemocultivoAdulto();
});

const validarRegistroSolicitudHemocultivoAdulto = () => {
  let msj = ''
  let adulto_fecha_tiempo_enfermedad = $('#adulto_fecha_tiempo_enfermedad').val() //  TIEMPO DE ENFERMEDAD
  if (adulto_fecha_tiempo_enfermedad == '') {
    msj = 'Debe indicar el Tiempo de Enfermedad.<br>'
  }
  //  ESCALA DE GLASGOW
  let adulto_respuesta_ocular = $('#adulto_respuesta_ocular').val() 
  if (adulto_respuesta_ocular == '') {
    msj += 'Debe indicar la Respuesta Ocular.<br>'
  }
  let adulto_respuesta_verbal = $('#adulto_respuesta_verbal').val() 
  if (adulto_respuesta_verbal == '') {
    msj += 'Debe indicar la Respuesta Verbal.<br>'
  }
  let adulto_respuesta_motora = $('#adulto_respuesta_motora').val() 
  if (adulto_respuesta_motora == '') {
    msj += 'Debe indicar la Respuesta Motora.<br>'
  }
  let adulto_total_escala_glasgow = $('#adulto_total_escala_glasgow').val() 
  if (adulto_total_escala_glasgow == '') {
    msj += 'Debe indicar el Total.<br>'
  }
  // SIGNOS VITALES
  let adulto_presion_arterial_x = $('#adulto_presion_arterial_x').val()
  if (adulto_presion_arterial_x == '') {
    msj += 'Debe indicar la presión arterial x.<br>'
  }
  let adulto_presion_arterial_y = $('#adulto_presion_arterial_y').val() 
  if (adulto_presion_arterial_y == '') {
    msj += 'Debe indicar la presión arterial y.<br>'
  }
  let adulto_frecuencia_cardiaca = $('#adulto_frecuencia_cardiaca').val() 
  if (adulto_frecuencia_cardiaca == '') {
    msj += 'Debe indicar la frecuencia cardiaca.<br>'
  }
  let adulto_frecuencia_respiratorio = $('#adulto_frecuencia_respiratorio').val() 
  if (adulto_frecuencia_respiratorio == '') {
    msj += 'Debe indicar la frecuencia respiratoria.<br>'
  }
  let adulto_temperatura = $('#adulto_temperatura').val() 
  if (adulto_temperatura == '') {
    msj += 'Debe indicar la temperatura.<br>'
  }
  // EXÁMENES DE LABORATORIO
  let adulto_creatinina = $('#adulto_creatinina').val()
  let adulto_diuresis = $('#adulto_diuresis').val()
  let adulto_procalcitonina = $('#adulto_procalcitonina').val()
  let adulto_pcr = $('#adulto_pcr').val()
  let adulto_fiO2 = $('#adulto_fiO2').val()
  let adulto_leucocitos = $('#adulto_leucocitos').val()
  if (adulto_leucocitos == '') {
    msj += 'Debe indicar leucocitos.<br>'
  }
  let adulto_plaquetas = $('#adulto_plaquetas').val()
  if (adulto_plaquetas == '') {
    msj += 'Debe indicar plaquetas.<br>'
  }
  let adulto_bilirrubina = $('#adulto_bilirrubina').val()
  //ANTECEDENTES PATOLÓGICOS
  let adulto_ima = $('#adulto_ima').attr("aria-pressed")
  let adulto_icc = $('#adulto_icc').attr("aria-pressed")
  let adulto_evp = $('#adulto_evp').attr("aria-pressed")
  let adulto_ecv = $('#adulto_ecv').attr("aria-pressed")
  let adulto_demencia = $('#adulto_demencia').attr("aria-pressed")
  let adulto_dm = $('#adulto_dm').attr("aria-pressed")
  let adulto_diabetes_lesion_organica = $('#adulto_diabetes_lesion_organica').attr("aria-pressed")
  let adulto_hemiplejia = $('#adulto_hemiplejia').attr("aria-pressed")
  let adulto_epoc = $('#adulto_epoc').attr("aria-pressed")
  let adulto_patologia_tejido_conectivo = $('#adulto_patologia_tejido_conectivo').attr("aria-pressed")
  let adulto_enfermedad_ulcerosa = $('#adulto_enfermedad_ulcerosa').attr("aria-pressed")
  let adulto_patologia_renal_moderada_o_grave = $('#adulto_patologia_renal_moderada_o_grave').attr("aria-pressed")
  let adulto_neoplasias = $('#adulto_neoplasias').attr("aria-pressed")
  let adulto_neoplasias_especifique = $('#adulto_neoplasias_especifique').val()
  let adulto_leucemias = $('#adulto_leucemias').attr("aria-pressed")
  let adulto_leucemias_especifique = $('#adulto_leucemias_especifique').val()
  let adulto_linfomas_malignos = $('#adulto_linfomas_malignos').attr("aria-pressed")
  let adulto_metastasis_colida = $('#adulto_metastasis_colida').attr("aria-pressed")
  let adulto_sida = $('#adulto_sida').attr("aria-pressed")
  let adulto_antecedentes_patologicos_otros_1 = $('#adulto_antecedentes_patologicos_otros_1').attr("aria-pressed")
  let adulto_antecedentes_patologicos_otros_1_especifique = $('#adulto_antecedentes_patologicos_otros_1_especifique').val()
  let adulto_antecedentes_patologicos_otros_2 = $('#adulto_antecedentes_patologicos_otros_2').attr("aria-pressed")
  let adulto_antecedentes_patologicos_otros_2_especifique = $('#adulto_antecedentes_patologicos_otros_2_especifique').val()
  // TERAPIA ANTIMICROBIANA
  let adulto_terapia_antimicrobiana = $('#adulto_terapia_antimicrobiana').attr("aria-pressed")
  if (adulto_terapia_antimicrobiana == 'false') {
    msj += 'Debe ser obligatorio terapia antimicrobiana.<br>'
  }
  // SOLICITUD DE FRASCO DE HEMOCULTIVO
  let adulto_frasco_1 = $('#adulto_frasco_1').attr("aria-pressed")
  if (adulto_frasco_1 == 'false') {
    msj += 'Debe ser obligatorio el frasco n° 1.<br>'
  } else {
    let adulto_frasco_1_opcion_1 = $('#adulto_frasco_1_opcion_1').val()
    console.log("adulto_frasco_1_opcion_1 :" +adulto_frasco_1_opcion_1);
    if (adulto_frasco_1_opcion_1 == '0') {
      msj += 'Debe seleccionar la primera opcion del frasco n° 1.<br>'
    }
    let adulto_frasco_1_opcion_2 = $('#adulto_frasco_1_opcion_2').val()
    if (adulto_frasco_1_opcion_2 == '0') {
      msj += 'Debe seleccionar la segunda opcion del frasco n° 1.<br>'
    }
  }
  let adulto_frasco_2 = $('#adulto_frasco_2').attr("aria-pressed")
  if (adulto_frasco_2 == 'false') {
    msj += 'Debe ser obligatorio el frasco n° 2.<br>'
  } else {
    let adulto_frasco_2_opcion_1 = $('#adulto_frasco_2_opcion_1').val()
    if (adulto_frasco_2_opcion_1 == '0') {
      msj += 'Debe seleccionar la primera opcion del frasco n° 2.<br>'
    }
    let adulto_frasco_2_opcion_2 = $('#adulto_frasco_2_opcion_2').val()
    if (adulto_frasco_2_opcion_2 == '0') {
      msj += 'Debe seleccionar la segunda opcion del frasco n° 2.<br>'
    }
  }

  let y = $('#solicitud_hemocultivo_adulto_data_id').val();
  let id_diagnostico = $('#miSelectLab' + y).val();
  if (id_diagnostico == 3806) { // cia10  I33.0 
    let adulto_frasco_3 = $('#adulto_frasco_3').attr("aria-pressed")
    if (adulto_frasco_3 == 'false') {
      msj += 'Debe ser obligatorio el frasco n° 3.<br>'
    } else {
      let adulto_frasco_3_opcion_1 = $('#adulto_frasco_3_opcion_1').val()
      if (adulto_frasco_3_opcion_1 == '0') {
        msj += 'Debe seleccionar la primera opcion del frasco n° 3.<br>'
      }
      let adulto_frasco_3_opcion_2 = $('#adulto_frasco_3_opcion_2').val()
      if (adulto_frasco_3_opcion_2 == '0') {
        msj += 'Debe seleccionar la segunda opcion del frasco n° 3.<br>'
      }
    }
  }
  let adulto_hemocultivo_seguimiento_y_vigilancia = $('#adulto_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed")
  if (adulto_hemocultivo_seguimiento_y_vigilancia == 'true') {
    let adulto_fecha_primer_resultado_hemocultivo = $('#adulto_fecha_primer_resultado_hemocultivo').val()
    if (adulto_fecha_primer_resultado_hemocultivo == '') {
      msj += 'Debe indicar la fecha del primer resultado de hemocultivo.<br>'
    }
  }

  if (msj !== '') {
    //bootbox.alert(msj)
    Swal.fire({
        icon: 'error',
        title: 'Alerta',
        html: msj,
        showConfirmButton: true,
    });
    return;
  }
  let i = $('#solicitud_hemocultivo_adulto_data_id').val();
  $('#des_adulto_fecha_tiempo_enfermedad' + i).val(adulto_fecha_tiempo_enfermedad);
  /** ESCALA DE GLASGOW **/
  $('#des_adulto_respuesta_ocular' + i).val(adulto_respuesta_ocular);
  $('#des_adulto_respuesta_verbal' + i).val(adulto_respuesta_verbal);
  $('#des_adulto_respuesta_motora' + i).val(adulto_respuesta_motora);
  $('#des_adulto_total_escala_glasgow' + i).val(adulto_total_escala_glasgow);
  /** SIGNOS VITALES **/
  $('#des_adulto_presion_arterial_x' + i).val(adulto_presion_arterial_x);
  $('#des_adulto_presion_arterial_y' + i).val(adulto_presion_arterial_y);
  $('#des_adulto_frecuencia_cardiaca' + i).val(adulto_frecuencia_cardiaca);
  $('#des_adulto_frecuencia_respiratorio' + i).val(adulto_frecuencia_respiratorio);
  $('#des_adulto_temperatura' + i).val(adulto_temperatura);
  /** EXÁMENES DE LABORATORIO **/
  $('#des_adulto_creatinina' + i).val(adulto_creatinina);
  $('#des_adulto_diuresis' + i).val(adulto_diuresis);
  $('#des_adulto_procalcitonina' + i).val(adulto_procalcitonina);
  $('#des_adulto_pcr' + i).val(adulto_pcr);
  $('#des_adulto_fiO2' + i).val(adulto_fiO2);
  $('#des_adulto_leucocitos' + i).val(adulto_leucocitos);
  $('#des_adulto_plaquetas' + i).val(adulto_plaquetas);
  $('#des_adulto_bilirrubina' + i).val(adulto_bilirrubina);
  /** ANTECEDENTES PATOLÓGICOS **/
  $('#des_adulto_ima' + i).val(adulto_ima);
  $('#des_adulto_icc' + i).val(adulto_icc);
  $('#des_adulto_evp' + i).val(adulto_evp);
  $('#des_adulto_ecv' + i).val(adulto_ecv);
  $('#des_adulto_demencia' + i).val(adulto_demencia);
  $('#des_adulto_dm' + i).val(adulto_dm);
  $('#des_adulto_diabetes_lesion_organica' + i).val(adulto_diabetes_lesion_organica);
  $('#des_adulto_hemiplejia' + i).val(adulto_hemiplejia);
  $('#des_adulto_epoc' + i).val(adulto_epoc);
  $('#des_adulto_patologia_tejido_conectivo' + i).val(adulto_patologia_tejido_conectivo);
  $('#des_adulto_enfermedad_ulcerosa' + i).val(adulto_enfermedad_ulcerosa);
  $('#des_adulto_patologia_renal_moderada_o_grave' + i).val(adulto_patologia_renal_moderada_o_grave);
  $('#des_adulto_neoplasias' + i).val(adulto_neoplasias);
  $('#des_adulto_neoplasias_especifique' + i).val(adulto_neoplasias_especifique);
  $('#des_adulto_leucemias' + i).val(adulto_leucemias);
  $('#des_adulto_leucemias_especifique' + i).val(adulto_leucemias_especifique);
  $('#des_adulto_linfomas_malignos' + i).val(adulto_linfomas_malignos);
  $('#des_adulto_metastasis_colida' + i).val(adulto_metastasis_colida);
  $('#des_adulto_sida' + i).val(adulto_sida);
  $('#des_adulto_antecedentes_patologicos_otros_1' + i).val(adulto_antecedentes_patologicos_otros_1);
  $('#des_adulto_antecedentes_patologicos_otros_1_especifique' + i).val(adulto_antecedentes_patologicos_otros_1_especifique);
  $('#des_adulto_antecedentes_patologicos_otros_2' + i).val(adulto_antecedentes_patologicos_otros_2);
  $('#des_adulto_antecedentes_patologicos_otros_2_especifique' + i).val(adulto_antecedentes_patologicos_otros_2_especifique);
  /** TERAPIA ANTIMICROBIANA **/
  $('#des_adulto_terapia_antimicrobiana' + i).val(adulto_terapia_antimicrobiana);
  /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
  $('#des_adulto_frasco_1' + i).val(adulto_frasco_1)
  $('#des_adulto_frasco_1_opcion_1' + i).val( $('#adulto_frasco_1_opcion_1').val() )
  $('#des_adulto_frasco_1_opcion_2' + i).val( $('#adulto_frasco_1_opcion_2').val() )
  $('#des_adulto_frasco_2' + i).val(adulto_frasco_2)
  $('#des_adulto_frasco_2_opcion_1' + i).val( $('#adulto_frasco_2_opcion_1').val() )
  $('#des_adulto_frasco_2_opcion_2' + i).val( $('#adulto_frasco_2_opcion_2').val() )
  $('#des_adulto_frasco_3' + i).val( $('#adulto_frasco_3').attr("aria-pressed") )
  $('#des_adulto_frasco_3_opcion_1' + i).val( $('#adulto_frasco_3_opcion_1').val() )
  $('#des_adulto_frasco_3_opcion_2' + i).val( $('#adulto_frasco_3_opcion_2').val() )
  $('#des_adulto_hemocultivo_seguimiento_y_vigilancia' + i).val(adulto_hemocultivo_seguimiento_y_vigilancia)
  $('#des_adulto_fecha_primer_resultado_hemocultivo' + i).val( $('#adulto_fecha_primer_resultado_hemocultivo').val() ) 

  $('#modalSolicitudHemocultivoAdulto').modal('hide');
  //========================== LIMPIAR CAMPOS ==========================
  formatearCamposModalSolicitudHemocultivoAdulto()
  //=========================================

  Swal.fire({
      icon: 'success',
      title: 'Se guardo con exito!',
      showConfirmButton: false,
      timer: 2000,
      onClose: function() {
        //console.log(`Se guardo el tipo de muestra en el input ${i}`);
        $('#solicitudExamenEspecializadoHemocultivoAdulto' + i).hide();
        $('#editarSolicitudExamenEspecializadoHemocultivoAdulto' + i).show();
      }
  });
}

const formatearCamposModalSolicitudHemocultivoAdulto = () => {
  /** ESCALA DE GLASGOW **/
  $('#solicitud_hemocultivo_adulto_data_id').val('');
  $('#adulto_fecha_tiempo_enfermedad').val('');
  $('#adulto_respuesta_ocular').val('');
  $('#adulto_respuesta_verbal').val('');
  $('#adulto_respuesta_motora').val('');
  $('#adulto_total_escala_glasgow').val('');
  /** SIGNOS VITALES **/
  $('#adulto_presion_arterial_x').val('') 
  $('#adulto_presion_arterial_y').val('') 
  $('#adulto_frecuencia_cardiaca').val('') 
  $('#adulto_frecuencia_respiratorio').val('') 
  $('#adulto_temperatura').val('') 
  /** EXÁMENES DE LABORATORIO **/
  $('#adulto_creatinina').val('')
  $('#adulto_diuresis').val('')
  $('#adulto_procalcitonina').val('')
  $('#adulto_pcr').val('')
  $('#adulto_fiO2').val('')
  $('#adulto_leucocitos').val('')
  $('#adulto_plaquetas').val('')
  $('#adulto_bilirrubina').val('')
  /** ANTECEDENTES PATOLÓGICOS **/
  $('#adulto_ima').removeClass('active')
  $('#adulto_ima').attr("aria-pressed", "false")
  $('#adulto_icc').removeClass('active')
  $('#adulto_icc').attr("aria-pressed", "false")
  $('#adulto_evp').removeClass('active')
  $('#adulto_evp').attr("aria-pressed", "false")
  $('#adulto_ecv').removeClass('active')
  $('#adulto_ecv').attr("aria-pressed", "false")
  $('#adulto_demencia').removeClass('active')
  $('#adulto_demencia').attr("aria-pressed", "false")
  $('#adulto_dm').removeClass('active')
  $('#adulto_dm').attr("aria-pressed", "false")
  $('#adulto_diabetes_lesion_organica').removeClass('active')
  $('#adulto_diabetes_lesion_organica').attr("aria-pressed", "false")
  $('#adulto_hemiplejia').removeClass('active')
  $('#adulto_hemiplejia').attr("aria-pressed", "false")
  $('#adulto_epoc').removeClass('active')
  $('#adulto_epoc').attr("aria-pressed", "false")
  $('#adulto_patologia_tejido_conectivo').removeClass('active')
  $('#adulto_patologia_tejido_conectivo').attr("aria-pressed", "false")
  $('#adulto_enfermedad_ulcerosa').removeClass('active')
  $('#adulto_enfermedad_ulcerosa').attr("aria-pressed", "false")
  $('#adulto_patologia_renal_moderada_o_grave').removeClass('active')
  $('#adulto_patologia_renal_moderada_o_grave').attr("aria-pressed", "false")
  $('#adulto_neoplasias').removeClass('active')
  $('#adulto_neoplasias').attr("aria-pressed", "false")
  $('#adulto_neoplasias_especifique').val('')
  $('#adulto_neoplasias_especifique').hide()
  $('#adulto_leucemias').removeClass('active')
  $('#adulto_leucemias').attr("aria-pressed", "false")
  $('#adulto_leucemias_especifique').val('')
  $('#adulto_leucemias_especifique').hide()
  $('#adulto_linfomas_malignos').removeClass('active')
  $('#adulto_linfomas_malignos').attr("aria-pressed", "false")
  $('#adulto_metastasis_colida').removeClass('active')
  $('#adulto_metastasis_colida').attr("aria-pressed", "false")
  $('#adulto_sida').removeClass('active')
  $('#adulto_sida').attr("aria-pressed", "false")
  $('#adulto_antecedentes_patologicos_otros_1').removeClass('active')
  $('#adulto_antecedentes_patologicos_otros_1').attr("aria-pressed", "false")
  $('#adulto_antecedentes_patologicos_otros_1_especifique').val('')
  $('#adulto_antecedentes_patologicos_otros_1_especifique').hide()
  $('#adulto_antecedentes_patologicos_otros_2').removeClass('active')
  $('#adulto_antecedentes_patologicos_otros_2').attr("aria-pressed", "false")
  $('#adulto_antecedentes_patologicos_otros_2_especifique').val('')
  $('#adulto_antecedentes_patologicos_otros_2_especifique').hide()
  /** TERAPIA ANTIMICROBIANA **/
  $('#adulto_terapia_antimicrobiana').removeClass('active')
  $('#adulto_terapia_antimicrobiana').attr("aria-pressed", "false")
  /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
  $('#adulto_frasco_1').removeClass('active')
  $('#adulto_frasco_1').attr("aria-pressed", "false")
  $('#adulto_frasco_1_opcion_1').val('0')
  $('#adulto_frasco_1_opcion_2').val('0')
  $('#adulto_frasco_1_opcion_1').hide()
  $('#adulto_frasco_1_opcion_2').hide()
  $('#adulto_frasco_2').removeClass('active')
  $('#adulto_frasco_2').attr("aria-pressed", "false")
  $('#adulto_frasco_2_opcion_1').val('0')
  $('#adulto_frasco_2_opcion_2').val('0')
  $('#adulto_frasco_2_opcion_1').hide()
  $('#adulto_frasco_2_opcion_2').hide()
  $('#adulto_frasco_3').removeClass('active')
  $('#adulto_frasco_3').attr("aria-pressed", "false")
  $('#adulto_frasco_3_opcion_1').val('0')
  $('#adulto_frasco_3_opcion_2').val('0')
  $('#adulto_frasco_3_opcion_1').hide()
  $('#adulto_frasco_3_opcion_2').hide()
  $('#adulto_hemocultivo_seguimiento_y_vigilancia').removeClass('active')
  $('#adulto_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed", "false")
  $('#adulto_fecha_primer_resultado_hemocultivo').val('')
  $('#adulto_contenedor_fecha_primer_resultado_hemocultivo').hide()
}

$(document).ready(function() {
    $('#modalSolicitudHemocultivoAdulto').on('hidden.bs.modal', function() {
        // Limpiamos los campos de entrada al ocultar el modal
        formatearCamposModalSolicitudHemocultivoAdulto()
    });
});
// ========== REGISTRAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO FIN =========

// ========== EDITAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO INICIO =========
$(document).on('click','.editarSolicitudExamenEspecializadoHemocultivoAdulto', function () {
  
  let i = $(this).data('id');
  $('#solicitud_hemocultivo_adulto_data_id').val(i);
  
  //------------------------------------------------
  //  TIEMPO DE ENFERMEDAD
  $('#adulto_fecha_tiempo_enfermedad').val( $('#des_adulto_fecha_tiempo_enfermedad' + i).val() ) 

  //  ESCALA DE GLASGOW
  $('#adulto_respuesta_ocular').val( $('#des_adulto_respuesta_ocular' + i).val() ) 
  $('#adulto_respuesta_verbal').val( $('#des_adulto_respuesta_verbal' + i).val() ) 
  $('#adulto_respuesta_motora').val( $('#des_adulto_respuesta_motora' + i).val() ) 
  $('#adulto_total_escala_glasgow').val( $('#des_adulto_total_escala_glasgow' + i).val() ) 
  // SIGNOS VITALES
  $('#adulto_presion_arterial_x').val( $('#des_adulto_presion_arterial_x' + i).val() )
  $('#adulto_presion_arterial_y').val( $('#des_adulto_presion_arterial_y' + i).val() )
  $('#adulto_frecuencia_cardiaca').val( $('#des_adulto_frecuencia_cardiaca' + i).val() )
  $('#adulto_frecuencia_respiratorio').val( $('#des_adulto_frecuencia_respiratorio' + i).val() ) 
  $('#adulto_temperatura').val( $('#des_adulto_temperatura' + i).val() ) 
  // EXÁMENES DE LABORATORIO
  $('#adulto_creatinina').val( $('#des_adulto_creatinina' + i).val() )
  $('#adulto_diuresis').val( $('#des_adulto_diuresis' + i).val() )
  $('#adulto_procalcitonina').val( $('#des_adulto_procalcitonina' + i).val() )
  $('#adulto_pcr').val( $('#des_adulto_pcr' + i).val() )
  $('#adulto_fiO2').val( $('#des_adulto_fiO2' + i).val() )
  $('#adulto_leucocitos').val( $('#des_adulto_leucocitos' + i).val() )
  $('#adulto_plaquetas').val( $('#des_adulto_plaquetas' + i).val() )
  $('#adulto_bilirrubina').val( $('#des_adulto_bilirrubina' + i).val() )
  //ANTECEDENTES PATOLÓGICOS
  if ($('#des_adulto_ima' + i).val() == 'true') {
    $('#adulto_ima').attr("aria-pressed","true")
    $('#adulto_ima').addClass("active")
  }
  
  if($('#des_adulto_icc' + i).val() == 'true'){
    $('#adulto_icc').attr("aria-pressed","true")
    $('#adulto_icc').addClass("active")
  }
  if($('#des_adulto_evp' + i).val() == 'true'){
    $('#adulto_evp').attr("aria-pressed","true")
    $('#adulto_evp').addClass("active")
  }
  if($('#des_adulto_ecv' + i).val() == 'true'){
    $('#adulto_ecv').attr("aria-pressed","true")
    $('#adulto_ecv').addClass("active")
  }
  if($('#des_adulto_demencia' + i).val() == 'true'){
    $('#adulto_demencia').attr("aria-pressed","true")
    $('#adulto_demencia').addClass("active")
  }
  if($('#des_adulto_dm' + i).val() == 'true'){
    $('#adulto_dm').attr("aria-pressed","true")
    $('#adulto_dm').addClass("active")
  }
  if($('#des_adulto_diabetes_lesion_organica' + i).val() == 'true'){
    $('#adulto_diabetes_lesion_organica').attr("aria-pressed","true")
    $('#adulto_diabetes_lesion_organica').addClass("active")
  }
  if($('#des_adulto_hemiplejia' + i).val() == 'true'){
    $('#adulto_hemiplejia').attr("aria-pressed","true")
    $('#adulto_hemiplejia').addClass("active")
  }
  if($('#des_adulto_epoc' + i).val() == 'true'){
    $('#adulto_epoc').attr("aria-pressed","true")
    $('#adulto_epoc').addClass("active")
  }
  if($('#des_adulto_patologia_tejido_conectivo' + i).val() == 'true'){
    $('#adulto_patologia_tejido_conectivo').attr("aria-pressed","true")
    $('#adulto_patologia_tejido_conectivo').addClass("active")
  }
  if($('#des_adulto_enfermedad_ulcerosa' + i).val() == 'true'){
    $('#adulto_enfermedad_ulcerosa').attr("aria-pressed","true")
    $('#adulto_enfermedad_ulcerosa').addClass("active")
  }
  if($('#des_adulto_patologia_renal_moderada_o_grave' + i).val() == 'true'){
    $('#adulto_patologia_renal_moderada_o_grave').attr("aria-pressed","true")
    $('#adulto_patologia_renal_moderada_o_grave').addClass("active")
  }
  if($('#des_adulto_neoplasias' + i).val() == 'true'){
    $('#adulto_neoplasias').attr("aria-pressed","true")
    $('#adulto_neoplasias').addClass("active")
    $('#adulto_neoplasias_especifique').val( $('#des_adulto_neoplasias_especifique' + i).val() ) 
    $('#adulto_neoplasias_especifique').show()
  }
  if($('#des_adulto_leucemias' + i).val() == 'true'){
    $('#adulto_leucemias').attr("aria-pressed","true")
    $('#adulto_leucemias').addClass("active")
    $('#adulto_leucemias_especifique').val( $('#des_adulto_leucemias_especifique' + i).val() ) 
    $('#adulto_leucemias_especifique').show()
  }
  if($('#des_adulto_linfomas_malignos' + i).val() == 'true'){
    $('#adulto_linfomas_malignos').attr("aria-pressed","true")
    $('#adulto_linfomas_malignos').addClass("active")
  }
  if($('#des_adulto_metastasis_colida' + i).val() == 'true'){
    $('#adulto_metastasis_colida').attr("aria-pressed","true")
    $('#adulto_metastasis_colida').addClass("active")
  }
  if($('#des_adulto_sida' + i).val() == 'true'){
    $('#adulto_sida').attr("aria-pressed","true")
    $('#adulto_sida').addClass("active")
  }
  if($('#des_adulto_antecedentes_patologicos_otros_1' + i).val() == 'true'){
    $('#adulto_antecedentes_patologicos_otros_1').attr("aria-pressed","true")
    $('#adulto_antecedentes_patologicos_otros_1').addClass("active")
    $('#adulto_antecedentes_patologicos_otros_1_especifique').val( $('#des_adulto_antecedentes_patologicos_otros_1_especifique' + i).val() )  
    $('#adulto_antecedentes_patologicos_otros_1_especifique').show()
  }
  if($('#des_adulto_antecedentes_patologicos_otros_2' + i).val() == 'true'){
    $('#adulto_antecedentes_patologicos_otros_2').attr("aria-pressed","true")
    $('#adulto_antecedentes_patologicos_otros_2').addClass("active")
    $('#adulto_antecedentes_patologicos_otros_2_especifique').val( $('#des_adulto_antecedentes_patologicos_otros_2_especifique' + i).val() ) 
    $('#adulto_antecedentes_patologicos_otros_2_especifique').show()
  }
  // TERAPIA ANTIMICROBIANA
  if($('#des_adulto_terapia_antimicrobiana' + i).val() == 'true'){
    $('#adulto_terapia_antimicrobiana').attr("aria-pressed","true")
    $('#adulto_terapia_antimicrobiana').addClass("active")
  }
  // SOLICITUD DE FRASCO DE HEMOCULTIVO
  if($('#des_adulto_frasco_1' + i).val() == 'true'){
    $('#adulto_frasco_1').attr("aria-pressed","true")
    $('#adulto_frasco_1').addClass("active")
    $('#adulto_frasco_1_opcion_1').val( $('#des_adulto_frasco_1_opcion_1' + i).val() ) 
    $('#adulto_frasco_1_opcion_2').val( $('#des_adulto_frasco_1_opcion_2' + i).val() ) 
    $('#adulto_frasco_1_opcion_1').show()
    $('#adulto_frasco_1_opcion_2').show()
  }
  if($('#des_adulto_frasco_2' + i).val() == 'true'){
    $('#adulto_frasco_2').attr("aria-pressed","true")
    $('#adulto_frasco_2').addClass("active")
    $('#adulto_frasco_2_opcion_1').val( $('#des_adulto_frasco_2_opcion_1' + i).val() ) 
    $('#adulto_frasco_2_opcion_2').val( $('#des_adulto_frasco_2_opcion_2' + i).val() ) 
    $('#adulto_frasco_2_opcion_1').show()
    $('#adulto_frasco_2_opcion_2').show()
  }
  if($('#des_adulto_frasco_3' + i).val() == 'true'){
    $('#adulto_frasco_3').attr("aria-pressed","true")
    $('#adulto_frasco_3').addClass("active")
    $('#adulto_frasco_3_opcion_1').val( $('#des_adulto_frasco_3_opcion_1' + i).val() ) 
    $('#adulto_frasco_3_opcion_2').val( $('#des_adulto_frasco_3_opcion_2' + i).val() ) 
    $('#adulto_frasco_3_opcion_1').show()
    $('#adulto_frasco_3_opcion_2').show()
  }
  if($('#des_adulto_hemocultivo_seguimiento_y_vigilancia' + i).val() == 'true'){
    $('#adulto_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed","true")
    $('#adulto_hemocultivo_seguimiento_y_vigilancia').addClass("active")
    $('#adulto_fecha_primer_resultado_hemocultivo').val( $('#des_adulto_fecha_primer_resultado_hemocultivo' + i).val() )
    $('#adulto_contenedor_fecha_primer_resultado_hemocultivo').show()
  }
  //------------------------------------------------

  // let descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + i).val();
  // $('#solicitud_edit_descripcion_tipo_muestra').val(descripcion_tipo_muestra);
  $("#modalSolicitudHemocultivoAdulto").modal();
  
});
// ========== EDITAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO FIN =========

const mostrarBotonParaTipoDeMuestraMicrobiologia = (ind) => {
  $('#tipoMuestraMicrobiologia' + ind).show();
  $('#flag_descripcion_tipo_muestra' + ind).val('1');
}

// =========== REGISTRAR TIPO DE MUESTRA INICIO ==========================
$(document).on('click','.tipoMuestraMicrobiologia', function () {
  
  let i = $(this).data('id');
  $('#solicitud_data_id').val(i);
  $("#modalTipoMuestrabMicrobiologia").modal();

});

$(document).on('click', '#btnGuardarRegistroTipoMuestraMicrobiologia', function(e) {
  e.preventDefault();
  validarRegistroTipoMuestraMicrobiologia();
});

const validarRegistroTipoMuestraMicrobiologia = () => {
    let solicitud_descripcion_tipo_muestra = $('#solicitud_descripcion_tipo_muestra').val().trim();

    if(solicitud_descripcion_tipo_muestra === ""){
        Swal.fire({
            icon: 'error',
            title: 'El campo está vacío. Por favor, ingrese el tipo de muestra.',
            showConfirmButton: false,
            timer: 2000
        });
    return;
    }

    let i = $('#solicitud_data_id').val();
    $('#descripcion_tipo_muestra' + i).val(solicitud_descripcion_tipo_muestra);

    $('#modalTipoMuestrabMicrobiologia').modal('hide');
    //============Limpiamos los campos=========
    $('#solicitud_data_id').val('');
    $('#solicitud_descripcion_tipo_muestra').val('');
    //=========================================
    Swal.fire({
        icon: 'success',
        title: 'Se guardo con exito!',
        showConfirmButton: false,
        timer: 2000,
        onClose: function() {
          //console.log(`Se guardo el tipo de muestra en el input ${i}`);
          $('#tipoMuestraMicrobiologia' + i).hide();
          $('#editarTipoMuestraMicrobiologia' + i).show();
        }
    });
}

$(document).ready(function() {
    $('#modalTipoMuestrabMicrobiologia').on('hidden.bs.modal', function() {
        // Limpiamos los campos de entrada al ocultar el modal
        $('#solicitud_data_id').val('');
        $('#solicitud_descripcion_tipo_muestra').val('');
    });
});
// =========== REGISTRAR TIPO DE MUESTRA FIN ==========================
// =========== EDITAR TIPO DE MUESTRA INICIO ==========================
$(document).on('click','.editarTipoMuestraMicrobiologia', function () {
  
  let i = $(this).data('id');
  let descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + i).val();
  $('#solicitud_edit_data_id').val(i);
  $('#solicitud_edit_descripcion_tipo_muestra').val(descripcion_tipo_muestra);
  $("#modalEditTipoMuestrabMicrobiologia").modal();
  
});
$(document).on('click', '#btnGuardarEditTipoMuestraMicrobiologia', function(e) {
  e.preventDefault();
  validarEditTipoMuestraMicrobiologia();
});
const validarEditTipoMuestraMicrobiologia = () => {
    let solicitud_edit_descripcion_tipo_muestra = $('#solicitud_edit_descripcion_tipo_muestra').val().trim();

    if(solicitud_edit_descripcion_tipo_muestra === ""){
        Swal.fire({
            icon: 'error',
            title: 'El campo está vacío. Por favor, ingrese el tipo de muestra.',
            showConfirmButton: false,
            timer: 2000
        });
    return;
    }

    let i = $('#solicitud_edit_data_id').val();
    $('#descripcion_tipo_muestra' + i).val(solicitud_edit_descripcion_tipo_muestra);

    $('#modalEditTipoMuestrabMicrobiologia').modal('hide');
    //============Limpiamos los campos=========
    $('#solicitud_edit_data_id').val('');
    $('#solicitud_edit_descripcion_tipo_muestra').val('');
    //=========================================
    Swal.fire({
        icon: 'success',
        title: 'Se guardo con exito!',
        showConfirmButton: false,
        timer: 2000,
        onClose: function() {
          //console.log(`Se guardo el tipo de muestra en el input ${i}`);
          $('#editarTipoMuestraMicrobiologia' + i).prop('disabled', true);
        }
    });
}
// =========== EDITAR TIPO DE MUESTRA FIN ==========================

function  AddInterconsulta2(){
  var newRow = "";
  var ind = $('#tblInterconsulta tbody tr').length;
  
  if(ind > 0){
    $('.id_servicio_interconsulta').each(function(){
      var ind_tmp = $(this).data("id");
      if(ind_tmp => ind){
        ind=Number(ind_tmp)+1;
      }
    });
          
    fila_medicamentos=$('#tblInterconsulta tbody').children('tr');
    rowIndex=$('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento=fila_medicamentos[idval];
    
  }

  let interconsulta_servicio_items = "";
  $('#cmbInterconsultaServiciosTemp option').each(function() {
    interconsulta_servicio_items += '<option value="' + $(this).val() + '" data-has_guardia="' + $(this).data('has_guardia') + '" >' + $(this).html() + '</option>';
  });
  
  /*<select tabindex="72" class="form-control" name="personal_turno" id="personal_turno" onchange="getMedico()" style="width:100%">
<option value="">-Seleccionar-</option><option value="M">MAÑANA</option><option value="T">TARDE</option><option value="N">NOCHE</option></select>
*/
  
  newRow +='<tr>';
  newRow +='  <td>';
  newRow +='    <div class="panel panel-info panel-bordered panel-sm">';
  newRow +='        <div class="panel-body">';
  newRow +='          <div class="form-group">';
  newRow +='            <div class="col-md-2">';
  newRow +='              <label>SERVICIO:</label>';
  newRow +='            </div>';
  newRow +='            <div class="col-md-4" >';
  newRow +='              <select class="form-control cmbInterconsultaServicios input-sm" id="id_servicio_interconsulta' + ind + '" ind="' + ind +'" name="id_servicio_interconsulta[]" style:"width:100%" data-guardia="">' + interconsulta_servicio_items + '</select>'
  newRow +='            </div>';
  newRow +='          </div>'; // .form-group
  newRow +='          <div class="form-group" id="panel_interconsulta_resumen_' + ind + '" style="display:none">';
  newRow +='            <div class="col-md-12">';
  newRow +='              <label>RESUMEN:</label>';
  newRow +='              <textarea class="form-control" cols="5" id="motivo_interconsulta'+ind+'" name="motivo_interconsulta[]" placeholder="Relato m&eacute;dico o caracteristicas de la interconsulta"></textarea>';
  newRow +='            </div>';
  newRow +='          </div>'; // .form-group
  newRow +='          <div class="form-group" id="panel_interconsulta_personal_coordinacion_' + ind + '" style="display:none">';
  newRow +='            <div class="col-md-2">';
  newRow +='              <label>PERSONAL EN COORDINACIÓN:</label>';
  newRow +='            </div>';
  newRow +='            <div class="col-md-10" >';
  newRow +='              <span id="span_interconsulta_personal_coodinacion_' + ind + '"></span>';
  newRow +='              <input type="hidden" name="interconsulta_personal_coodinacion[]" id="interconsulta_personal_coodinacion_' + ind + '" value="">';
  newRow +='            </div>';
  newRow +='          </div>'; // .form-group
  newRow +='        </div>'; // .panel-body
  newRow +='    </div>'; // .panel
  newRow +='  </td>';
  newRow +='  <td>';
  newRow +='    <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>';
  newRow +='  </td>';
  newRow +='</tr>';
  
  var id_ipress     = $('#id_establecimiento').val();

  $('#tblInterconsulta tbody').append(newRow);
  $("#id_servicio_interconsulta" + ind).select2({max_selected_options: 4}).focus();

  /*$("#nombre_servicio_interconsulta"+ind).autocomplete({
    minLength: 1,
    delay : 400,
    source: function(request, response) {
      $.ajax({
        url: '{{ url("/") }}/obtener_consultorio_interconsulta/' + request.term +'/'+id_ipress,
        dataType: "json",
        success: function(data)  {
          let listaServicioSelecionado = $(".id_servicio_interconsulta").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaServicioSelecionado.includes(element.id));
          response(filtro);
        }         
      })
  
    },select:  function(e, ui) {
      var id_servicio_interconsulta = ui.item.id;
      let nombre_servicio_interconsulta = ui.item.value;
      let servicio_has_guardia = ui.item.has_guardia;
      let is_estable = $("#is_estable").val();
      $("#id_servicio_interconsulta"+ind).val(id_servicio_interconsulta);
      $("#nombre_servicio_interconsulta" + ind).val(nombre_servicio_interconsulta)
      $("#nombre_servicio_interconsulta"+ind).prop("readonly",true);
      // getMedico(id_servicio_interconsulta,ind);
      if (is_estable == 0) {
        bootbox.alert("<br>Favor de coordinar con el Jefe de guardia para responder la interconsulta.");
      } else {
        if (!servicio_has_guardia) {
          validarHorarioYFechasNoPermitidasInterconsulta(ind, id_servicio_interconsulta);
          return false;
        }
      }
    }
    
  });
  $("#nombre_servicio_interconsulta"+ind).on('blur', function() {
    if (!showValidacionCantInterconsulta) {
      var id_servicio_interconsulta = $("#id_servicio_interconsulta"+ind).val();
      if(id_servicio_interconsulta == ""){
        bootbox.alert("Falta ingresar el servicio en esta interconsulta");
        $("#nombre_servicio_interconsulta"+ind).val("");
        return false;
      }
    } else {
      showValidacionCantInterconsulta = false;
    }
  });*/ 

  $("#id_servicio_interconsulta" + ind).on("change", function(e) {
    let flagx = 0;
    // cmb = $(this);
    let interconsulta_servicio_id = $("#id_servicio_interconsulta" + ind).val();
    // console.log("interconsulta_servicio_id:"+ interconsulta_servicio_id);
    if (interconsulta_servicio_id != "") {

      $('.cmbInterconsultaServicios').each(function() {
        var ind_tmp = $(this).val();
        if ($(this).val() == interconsulta_servicio_id) flagx++;
      });

      if (flagx > 1) {
        bootbox.alert("El Servicio ya ha sido ingresado.");
        $("#id_servicio_interconsulta" + ind).val("").trigger("change");
        return false;
      } else {

        let mostrar_mensaje_coordinacion = false;
        let mostrar_textarea_motivo_interconsulta = false;

        try {
          $.ajax({
            url: "{{ url('/') }}/get_datos_dia_actual",
            dataType: 'json',
            async: false,
            success: function(response) {
              data_del_dia = response;
            },
            error: function(xhr, status, error) {
              // console.error("Error en AJAX:", status, error);
              // console.log("Respuesta del servidor:", xhr.responseText);
              bootbox.alert("<span class='text-danger'><i class='fa fa-exclamation-triangle'></i></span> Ha ocurrido un error obtener información del día.<br><br>" + xhr.responseText);
            }
          });
          
          if (data_del_dia.dia_semana_numero != DIA_SEMANA_DOMINGO && !data_del_dia.es_feriado) {
            
            const horaActual = new Date();
            const [horas, minutos] = data_del_dia.hora_actual.split(':').map(Number);
            horaActual.setHours(horas, minutos, 0, 0);

            // Definir la hora límite inicio (07:30)
            const horaLimiteInicio = new Date();
            horaLimiteInicio.setHours(7, 30, 0, 0);

            // Definir la hora límite fin (13:30)
            const horaLimiteFin = new Date();
            horaLimiteFin.setHours(13, 30, 0, 0);

            if (horaActual < horaLimiteInicio || horaActual > horaLimiteFin) {
              // Fuera del rango de horario
              let has_guardia = $(this).find('option:selected').data('has_guardia');

              if (has_guardia == 1) {
                // El servicio si hace guardia
                mostrar_textarea_motivo_interconsulta = true;

              } else if (has_guardia == 0) {
                // El servicio no hace guardia
                mostrar_mensaje_coordinacion = true;
              }

            } else {
              // Esta en el rango del horario
              mostrar_textarea_motivo_interconsulta = true;
            }

          } else {
            // Si es Domingo o es feriado
            mostrar_mensaje_coordinacion = true;

          }
        } catch (error) {
          bootbox.alert("<span class='text-danger'><i class='fa fa-exclamation-triangle'></i></span> Ha ocurrido un error al validar el horario.<br><br>" + error);
        }

        if (mostrar_mensaje_coordinacion) {

          Swal.fire({
            title: 'MENSAJE DE COORDINACIÓN',
            html: 'Para continuar con el registro de la interconsulta, deberá coordinar con el Coronel de Guardia, y registrar el nombre del personal médico que responderá la interconsulta',
            input: 'text',
            inputLabel: 'Datos del Médico',
            // inputPlaceholder: 'Escriba su nombre aquí',
            showCancelButton: true,
            confirmButtonText: 'CONTINUAR',
            cancelButtonText: 'CANCELAR',
            inputValidator: (value) => {
              if (!value) {
                return 'Porfavor completar los campos requeridos para continuar.';
              }
            }
          }).then((result) => {
            if (result.isConfirmed) {
              const datosPersonalCoordinacion = result.value;
              $("#panel_interconsulta_resumen_" + ind).css('display', 'block');
              $("#panel_interconsulta_personal_coordinacion_" + ind).css('display', 'block');
              $("#span_interconsulta_personal_coodinacion_" + ind).text(datosPersonalCoordinacion);
              $("#interconsulta_personal_coodinacion_" + ind).val(datosPersonalCoordinacion);
              // $("#nombre_servicio_interconsulta" + ind).prop("readonly", true);
              let $select = $("#id_servicio_interconsulta" + ind);

              // Bloquear visualmente Select2
              $select.next('.select2-container').css({
                'pointer-events': 'none',
                'background-color': '#eee',
                'opacity': 0.8
              });

            } else if (result.isDismissed) {
              $("#id_servicio_interconsulta" + ind).val("").trigger("change");
            }
          });

        }

        if (mostrar_textarea_motivo_interconsulta) {
          $("#panel_interconsulta_resumen_" + ind).css('display', 'block');
          let $select = $("#id_servicio_interconsulta" + ind);

          // Bloquear visualmente Select2
          $select.next('.select2-container').css({
            'pointer-events': 'none',
            'background-color': '#eee',
            'opacity': 0.8
          });
        }

      }
    }

  });
}

/**
 * Validar horario de registro de interconsulta y no permitir registros en feriados
 * sabados y domingos
 */
async function validarHorarioYFechasNoPermitidasInterconsulta(ind, id_servicio) {

  let id_tipo_atencion = $("#id_tipo_atencion").val();

  /*function limpiarCampos(ind) {
    $("#id_servicio_interconsulta" + ind).val('');
    $("#nombre_servicio_interconsulta" + ind).val('');
    $("#nombre_servicio_interconsulta" + ind).prop("readonly", false);
  }*/

  // if (id_servicio == SERVICIO_NEUMOLOGIA) {
  if (id_tipo_atencion == 2) { // Emergencia
    try {
      /*const is_feriado = await $.ajax({
        url: "{{ url('validar_fecha_actual_es_feriado') }}/",
        dataType: 'json',
      });

      if (is_feriado) {
        showValidacionCantInterconsulta = true;
        bootbox.alert("No se puede adicionar esta interconsulta porque es <b>feriado</b>.");
        limpiarCampos(ind);
        return;
      } else {*/
        let id_establecimiento = $('#id_establecimiento').val();
        const response = await $.ajax({
          url: "{{ url('interconsulta_obtener_cantidad') }}/" + id_establecimiento + "/" + id_servicio + "/2",
          dataType: 'json',
        });

        /*if (response.dia_semana_numero != DIA_SEMANA_SABADO
          || response.dia_semana_numero != DIA_SEMANA_DOMINGO) {*/
          // if (id_servicio == 27) {
            const horaActual = new Date();
            const [horas, minutos] = response.hora_actual.split(':').map(Number);
            horaActual.setHours(horas, minutos, 0, 0);

            // Definir la hora límite inicio (07:30)
            const horaLimiteInicio = new Date();
            horaLimiteInicio.setHours(07, 30, 0, 0);

            // Definir la hora límite fin (13:30)
            const horaLimiteFin = new Date();
            horaLimiteFin.setHours(13, 30, 0, 0);

            if (horaActual < horaLimiteInicio || horaActual > horaLimiteFin) {
              // showValidacionCantInterconsulta = true;
              Swal.fire({
                title: 'MENSAJE DE COORDINACIÓN',
                html: 'Para continuar con el registro de la interconsulta, deberá coordinar con el Coronel de Guardia, y registrar el nombre del personal médico que responderá la interconsulta',
                input: 'text',
                inputLabel: 'Datos del Médico',
                // inputPlaceholder: 'Escriba su nombre aquí',
                showCancelButton: true,
                confirmButtonText: 'CONTINUAR',
                cancelButtonText: 'CANCELAR',
                inputValidator: (value) => {
                  if (!value) {
                    return 'Porfavor completar los campos requeridos para continuar.';
                  }
                }
              }).then((result) => {
                if (result.isConfirmed) {
                  const datosPersonalCoordinacion = result.value;
                  $("#panel_interconsulta_resumen_" + ind).css('display', 'block');
                  $("#panel_interconsulta_personal_coordinacion_" + ind).css('display', 'block');
                  $("#span_interconsulta_personal_coodinacion_" + ind).text(datosPersonalCoordinacion);
                  $("#interconsulta_personal_coodinacion_" + ind).val(datosPersonalCoordinacion);
                  // $("#nombre_servicio_interconsulta" + ind).prop("readonly", true);

                  let $select = $("#id_servicio_interconsulta" + ind);

                  // Bloquear visualmente Select2
                  $select.next('.select2-container').css({
                    'pointer-events': 'none',
                    'background-color': '#eee',
                    'opacity': 0.8
                  });

                } else if (result.isDismissed) {
                  $("#id_servicio_interconsulta" + ind).val("").trigger("change");
                }
              });
            } else {
              $("#panel_interconsulta_resumen_" + ind).css('display', 'block');

              let $select = $("#id_servicio_interconsulta" + ind);

              // Bloquear visualmente Select2
              $select.next('.select2-container').css({
                'pointer-events': 'none',
                'background-color': '#eee',
                'opacity': 0.8
              });
            }
          // }
        /*} else {
          showValidacionCantInterconsulta = true;
          bootbox.alert("No se puede adicionar la interconsulta para este servicio, porque hoy <b>es Sábado o Domingo</b>.");
          limpiarCampos(ind);
          return;
        }*/
      // }
    } catch (error) {
      bootbox.alert("<span class='text-danger'><i class='fa fa-exclamation-triangle'></i></span> Ha ocurrido un error al validar el horario.");
    }
  }
}

function getMedico(id_consultorio,i){
    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";
    $('#personal_salud').html(newOption);
    $("#personal_salud").val('').trigger("change");

    var turno     = $('#turno').val();
    var fecha_admision  = $('#fecha_atencion').val();
    var id_ipress     = $('#id_establecimiento').val();
    //var id_consultorio  = $('#id_servicio_interconsulta').val();
    //var id_consultorio  = $('#inter_servicio').val();

    
  
    
    $.ajax({
      url: '/obtener_medico_por_idipress_idconsultorio_turno_emergencia/' + id_ipress+ '/' + id_consultorio+'/'+turno + '/' + fecha_admision,
      success: function(result){                    
        var newOption = "";
        if (result.length > 0) {
          newOption = "<option value=''>--Seleccionar-</option>";
        } else {
          newOption = "<option value=''>--No hay registros-</option>";
        }
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
        });                 
        $('#personal_salud'+i).html(newOption);
        //$("#personal_salud").select2({max_selected_options: 4});      
      }
    });
        
    }

  $(document).on('click','.addRadioPortatil', function () {
    let id = $(this).data("id");
    let addRadioPortatil = $(`#addRadioPortatil${id}`).attr("aria-pressed");
    console.log(addRadioPortatil,id);
    
    $(`#radio_portatil${id}`).val('NO');
    console.log(`#radio_portatil${id}`);
    if(addRadioPortatil == "true"){
      $(`#radio_portatil${id}`).val('SI');
    }
  });

function AddExamenRad() {
  var newRow = "";
  var ind = $('#tblExamenRad tbody tr').length;

  if (ind > 0) {
    $('.id_radiologia').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });

    fila_medicamentos = $('#tblExamenRad tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];

  }

  var item_comparativo = '';
	item_comparativo+='<option value="0">--Seleccionar--</option>';
	item_comparativo+='<option value="1">SI</option>';
  item_comparativo+='<option value="2">NO</option>';
	
	var item_lado = '';
	item_lado+='<option value="0">--Seleccionar--</option>';
	item_lado+='<option value="1">Izquierdo</option>';
  item_lado+='<option value="2">Derecho</option>';
  item_lado+='<option value="3">Ambos</option>';

  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de Radiografia" name="nombre_examen_rad[]" tabindex="8" required="" id="nombre_examen_rad' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_radiologia" id="id_examen_rad' + ind +'" name="id_examen_rad[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_codigo_rad" id="id_codigo_rad' + ind +'" name="id_codigo_rad[]" value="" data-id='+ind+'></td>';
  newRow +='<td><select class="form-control id_comparativo" id="cmbComparativo'+ind+'" name="id_comparativo[]" style="margin:0 20px;width:150px">'+item_comparativo+'</select></td>';
  newRow +='<td><select class="form-control id_lado" id="cmbLado'+ind+'" name="id_lado[]" style="margin:0 20px;width:150px">'+item_lado+'</select></td>';
  newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_rad_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="examen_rad_observacion' +ind + '" name="examen_rad_observacion[]"></td>';
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_rad_cantidad typeahead tt-query" style="margin:0 5px;width:50%" id="examen_rad_cantidad' +ind + '" name="examen_rad_cantidad[]" value="1"></td>';
  newRow += '<td><select class="form-control select-diagnostico"  id="miSelectRad' + ind + '" name="miSelectRad[]" style="margin:0 10px;width:95%"></select></td>';
  newRow += `<td>
              <input type="hidden" id="radio_portatil${ind}" name="radio_portatil[]" value="NO" data-id="${ind}" />
              <button tabindex="100" type="button" id="addRadioPortatil${ind}" class="btn btn-lg btn-toggle addRadioPortatil" data-id="${ind}" data-toggle="button" aria-pressed="false" autocomplete="off">
                <div class="handle"></div>
              </button>
            </td>`;
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblExamenRad tbody').append(newRow);
  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  if (sexo == 'M')
    genero = 1;
  else
    genero = 2;
  //tipo=3
	const selectElementRad = document.getElementById('miSelectRad' + ind);
  
  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id_diagnostico;
    option.textContent = item.nombre;
    selectElementRad.appendChild(option);
  });

  $("#nombre_examen_rad" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '../../obtener_radiologia_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 2 + '/' + 76,
        //url: '../obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaRadiologiaSelecionado = $(".id_radiologia").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaRadiologiaSelecionado.includes(element.id));
          response(filtro);
        }
      })

    },
    select: function(e, ui) {
      var id_examen_rad = ui.item.id;
      var id_codigo_rad = ui.item.codigo_procedimiento;
      $("#id_examen_rad" + ind).val(id_examen_rad);
      $("#id_codigo_rad" + ind).val(id_codigo_rad);
      $("#nombre_examen_rad" + ind).prop("readonly", true);
      console.log($("#id_codigo_rad" + ind).val());
      //validaExamenRadiologiaSeleccionado($("#id_codigo_rad" + ind).val()); //Revisar 
    }
  });

  $("#nombre_examen_rad" + ind).on('blur', function() {
    var id_examen_rad = $("#id_examen_rad" + ind).val();
    if (id_examen_rad == "") {
      bootbox.alert("Falta ingresar el examen radiografia en esta fila");
      $("#examen_rad_cantidad" + ind).val("");
      return false;
    }
  });

  $("#examen_rad_cantidad" + ind).on('blur', function() {
    var id_examen_rad = $("#id_examen_rad" + ind).val();
    if (id_examen_rad == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen radiografia");
      $("#examen_rad_cantidad" + ind).val("");
      return false;
    }
  });

}

function fullCalendar() {
  //var SITEURL = "{{url('/')}}";
  var iIdMovimientoCompra = $('#hdIdMovimientoCompra').val();
  var iIdTipoCompra = $('#hdIdTipoCompra').val();

  var id_servicio = $('#id_servicio').val();
  var id_ipress = $('#id_establecimiento').val();
  $("#divHorario").html("");

  $('#calendar').fullCalendar({
    header: {
      left: 'prev,next today',
      center: 'title',
      /*right: 'month,agendaWeek,agendaDay'*/
    },
    editable: false,
    //events: "submit/cronogramaCompraData.php?iIdMovimientoCompra="+iIdMovimientoCompra,
    //events: "cronograma_cita",
    events: "cronograma_cita/" + id_ipress + "/" + id_servicio,
    //data: {id_ipress:id_ipress,id_servicio:id_servicio},
    selectable: true,
    selectHelper: true,
    select: function(start, end, allDay) {
      dateObj = new Date(start); /* Or empty, for today */
      dateIntNTZ = dateObj.getTime() + dateObj.getTimezoneOffset() * 60 * 1000;
      dateObjNTZ = new Date(dateIntNTZ);
      start = dateObjNTZ.toISOString().slice(0, 10);
      start = $.trim(start);
      var sValidaciones = "";
      modalDelegar(start)

    }
  });

}

function fn_RegistrarNuevoCronograma(start, iIdMovimientoCompra, iIdTipoCompra) {
  /*
    var oBotones = {
		"Pagar": function () {
            var btnGrabar = $("#ifrModal").contents().find("input[id='btnGrabar']");
            btnGrabar.click();
        },
        "Cerrar": function () {
            $(this).dialog("close");
            $(this).remove();
        }
    };
    var sRuta = "frmCronogramaCompraDetalle.php?param=" + iIdMovimientoCompra + "&param1=" + iIdTipoCompra + "&param2=" + start;
    fn_util_AbreModalNew("Agendar Pago", sRuta, '40', 150, null, oBotones);
	*/
  $('#showAtencionesMensual').modal();
}


function modalDelegar___(start, iIdMovimientoCompra, iIdTipoCompra) {

  $(".modal-dialog").css("width", "80%");
  $('#openOverlayOpc').modal('show');
  //$('#openOverlayOpc').modal();
  $('#openOverlayOpc .modal-body').css('height', 'auto');
  //alert(start);
  $.ajax({
    url: "../modal_cita_medico/" + start,
    type: "GET",
    success: function(result) {
      $("#diveditpregOpc").html(result);
    }
  });

}

function modalDelegar(start) {

  //$(".modal-dialog").css("width","80%");
  //$('#openOverlayOpc').modal('show');
  //$('#openOverlayOpc .modal-body').css('height', 'auto');

  var id_servicio = $('#id_servicio').val();
  var id_ipress = $('#id_establecimiento').val();

  $.ajax({
    url: "../modal_cita_medico/" + id_ipress + "/" + id_servicio + "/" + start,
    type: "GET",
    success: function(result) {
      $("#divHorario").html(result);
    }
  });

}


function modalTurnos() {

  //$(".modal-dialog").css("width","80%");
  //$('#openOverlayOpc').modal('show');
  //$('#openOverlayOpc .modal-body').css('height', 'auto');

  var id_servicio = $('#id_servicio').val();
  var id_ipress = $('#id_establecimiento').val();;
  //var start = '2019-10-24';
  var start = "<?php //echo $fechaServidor?>";
  //alert(start);
  $.ajax({
    url: "../modal_turnos/" + id_ipress + "/" + id_servicio + "/" + start,
    type: "GET",
    success: function(result) {
      $("#divTurno").html(result);
    }
  });

}

function modalHistorial() {
  /*
  var id_servicio	= $('#id_servicio').val();
  var id_ipress	= $('#id_establecimiento').val();;
  var start = '2019-10-24';
  */
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
    url: "../modal_historial_cita/" + dni_beneficiario,
    type: "GET",
    success: function(result) {
      $("#divHistorial").html(result);
    }
  });

}


function AddDiagnostico() {
  var newRow = "";
  var ind = $('#tblDiagnostico tbody tr').length;

  if (ind > 0) {
    $('.id_diagnostico').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });

    fila_medicamentos = $('#tblDiagnostico tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];

  }

  var item_tipo_diagnostico = '';
  item_tipo_diagnostico += '<option value="0">--Seleccionar--</option>';
  item_tipo_diagnostico += '<option value="1">PRESUNTIVO</option>';
  item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';
  item_tipo_diagnostico += '<option value="3">REITERATIVO</option>';
  newRow += '<tr>';
newRow += '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico' + ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" /></td>';
newRow += '<td><input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico' + ind + '" name="id_diagnostico[]" value="" data-id=' + ind + '>';
newRow += '<input type="hidden" class="codigoEjemplo" id="codigo' + ind + '" name="codigo[]" value=""></td>';
newRow += '<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico' + ind + '" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
newRow += '</tr>';
  $('#diagnosticosAgregar tbody').append(newRow);


  var sexo = $('#nomsexo').val();
  if (sexo == 'M') {
    genero = 1;
  } else {
    if (sexo == 'F') {
      genero = 2;
    }
  }

  $("#nombre_diagnostico" + ind).autocomplete({
  minLength: 1,
  delay: 400,
  source: function(request, response) {
    $.ajax({
      url: "/obtener_diagnostico_genero_ver_xd",
      type: "POST",
      dataType: "json",
      data: {
    'buscar': request.term,
    'genero': genero,
    '_token': CSRF_TOKEN // Añade el token CSRF para proteger la solicitud POST
  },
      success: function(data) {
        let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function() { return parseInt($(this).val()); }).get();
      
        let filtro = data.filter((element) => !listaDiagnosticoSeleccionado.includes(element.id));
        response(filtro);
      }
    });
  },
  select: function(e, ui) {
    var value = ui.item.label;
    console.log(value);
    var value = ui.item.label;
    var labelParts = value.split(" - ");
    var firstValue = labelParts[0];
    var id_diagnostico = ui.item.id;
    $("#id_diagnostico" + ind).val(id_diagnostico);
    $("#codigo" + ind).val(firstValue);
    $("#nombre_diagnostico" + ind).prop("readonly", true);
  }
});

$("#nombre_diagnostico" + ind).on('blur', function() {
    var id_diagnostico = $("#id_diagnostico" + ind).val();
    var codigoasignado = $("#codigo" + ind).val();
    console.log(codigoasignado);
    
    if (id_diagnostico == "") {
        bootbox.alert("Falta ingresar el diagnostico en esta fila");
        $("#nombre_diagnostico" + ind).val("");
        return false;
    }
    else if (codigos.includes(codigoasignado)) {
        bootbox.alert("El diagnostico ya se encuentra registrado");
        $("#nombre_diagnostico" + ind).val(""); 
        $("#id_diagnostico" + ind).val("");
        $("#codigo" + ind).val("");
        $("#nombre_diagnostico" + ind).prop("readonly", false);
        return false;
    }
    else {
        //addNewArray(id_diagnostico);
    }
});

}

function AddProcedimiento() {
  var newRow = "";
  var ind = $('#tblProcedimiento tbody tr').length;

  if (ind > 0) {
    $('.id_procedimiento').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });

    fila_medicamentos = $('#tblProcedimiento tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];

  }

  newRow += '<tr>';
  newRow +=
    '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" required="" id="nombre_procedimiento' +
    ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento' + ind +
    '" name="id_procedimiento[]" value="1" data-id='+ind+'></td>';
  newRow +=
    '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';
  $('#tblProcedimiento tbody').append(newRow);
  sexo = getSexoGenero();

  $("#nombre_procedimiento" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
        dataType: "json",
        success: function(data) {
          let listaProcedimientoSelecionado = $(".id_procedimiento").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
          response(filtro);
        }
      })
    },
    select: function(e, ui) {
      var id_procedimiento = ui.item.id;
      $("#id_procedimiento" + ind).val(id_procedimiento);
      $("#nombre_procedimiento" + ind).prop("readonly", true);
    }
  });

  $("#nombre_procedimiento" + ind).on('blur', function() {
    var id_procedimiento = $("#id_procedimiento" + ind).val();
    if (id_procedimiento == "") {
      bootbox.alert("Falta ingresar el procedimiento en esta fila");
      $("#nombre_procedimiento" + ind).val("");
      return false;
    }
  });
}

let contadorFilas = 0;
var modalInfectoMostrado = false;
function AddProducto() {
  var tipo_receta = $('#tipo_receta').val();
  var id_consultorio = $('#id_consultorio').val();
  var id_farmacia = $('#id_farmacia_receta').val();
  if (tipo_receta == "") {
    bootbox.alert("Seleccione el tipo atención");
    return false;
  }
  if (id_consultorio == "") {
    bootbox.alert("Seleccione un servicio");
    return false;
  }
  var newRow = "";
  //var ind = $('#example tbody tr').length;
    // var ind = filasmedicamentos
  var ind = contadorFilas++;
  var tabindex = 101;
  var nuevalperiodo = "";
  if (ind > 0) {
    $('.cmbMedicamento').each(function() {
      var ind_tmp = $(this).attr("ind");
      if (ind_tmp => ind) {
        //ind = Number(ind_tmp) + 1;
        tabindex += 3;
      }
    });

    fila_medicamentos = $('#example tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
    fila_medicamento = fila_medicamentos[idval];
    var valante = $(fila_medicamento).find('#nro_med_entregados').val();
    if (valante == "") {
      bootbox.alert("Ingrese cantidad entregada en el producto anterior");
      return false;
    }
    <?php //endif;?>

    //nuevalperiodo = document.getElementById('tiempo_periodo' + idval).value;
  }

  var item_producto = "";
  $('#cmbMedicamentoTemp option').each(function() {
    item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
      "</option>"
  });

  var item_via_administracion = '';
	item_via_administracion+='<option value="0">--Seleccionar--</option>';
	item_via_administracion+='<option value="1">Oral</option>';
	item_via_administracion+='<option value="2">Sublingual</option>';
  item_via_administracion+='<option value="3">Rectal</option>';
	item_via_administracion+='<option value="4">Intravenosa</option>';
  item_via_administracion+='<option value="5">Intramuscular</option>';
  item_via_administracion+='<option value="6">Subcutánea</option>';
  item_via_administracion+='<option value="7">Dérmica</option>';
  item_via_administracion+='<option value="8">Nasal</option>';
  item_via_administracion+='<option value="9">Oftalmológica</option>';
  item_via_administracion+='<option value="10">Inhalatoria</option>';
  item_via_administracion+='<option value="11">Epidural</option>';
  item_via_administracion+='<option value="12">Intratecal</option>';
  item_via_administracion+='<option value="13">Vaginal</option>';
  item_via_administracion+='<option value="14">Intraarticular</option>';
  item_via_administracion+='<option value="17">Parenteral</option>';
  item_via_administracion+='<option value="15">No Aplica</option>';
  item_via_administracion+='<option value="16">Otros</option>';
	
  newRow +='<tr>';
  newRow +='<td><select class="form-control cmbMedicamento" id="cmbMedicamento' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto[]" style="width: 450px">' + item_producto + '</select></td>';
	
  //newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  //newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
    newRow +='<input type="hidden" name="nro_stocks_almacen[]" id="nro_stocks_almacen" value=""/>';
    newRow +='<input type="hidden" name="nro_stocks_establecimiento[]" id="nro_stocks_establecimiento" value=""/>';
    newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
  newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind +'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="' + nuevalperiodo +'"/>';
    newRow +='<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';
  newRow += '<td><input type="text" name="posologia[]" maxlength="150" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
    ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';
	
  <?php //endif;?>
  newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
  newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
  newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
  newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
  newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
  newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados' +ind +'" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';
	
  newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico"  id="miSelectProd' + ind + '" name="miSelectProd[]"></select></td>';  
  newRow +='<td><button type="button" class="btn btn-primary btn-xs" id="add_producto'+ind+'"><i class="fa fa-plus"></i> </button></td>';
  newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
  newRow +='</tr>';
  $('#example tbody').append(newRow);
  // $("#cmbMedicamento" + ind).select2({max_selected_options: 4}).focus();
  let select = $("#cmbMedicamento" + ind);
  select.select2({
      width: '100%',
      dropdownAutoWidth: true
  });


  const selectElementProd = document.getElementById('miSelectProd' + ind);

  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id_diagnostico;
    option.textContent = item.nombre;
    selectElementProd.appendChild(option);
  });
  $("#abrir-modal-" + ind).click(function(e) {
    e.stopPropagation();
  
    abrirModal(ind);
  });
	
  $("#nro_med_solictados" + ind).keypress(function(e) {
    if (e.which == 13) {
      <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
      AddProducto();
      <?php //else:?>
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      <?php //endif;?>
    }
  });

  $("#add_producto"+ind).click(function(e){
      AddProducto();
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      btnAgregarProducto = $(this);
      $(btnAgregarProducto).attr('disabled', 'disabled');
	});

  function validproductoseleccionado(id_farmacia, btn) {//Jose
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_dias = 30;
    var objTr = $(btn).closest("tr");
    var id_producto = objTr.find(".cmbMedicamento").val();
    $.ajax({
      url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
      success: function (result) {
      var msg   = "";
      var n     = 0;
      var flag  = true;
      for(var key in result) {
        if(n == 0){
          msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";              
          msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                      msg += "<thead>";
                        msg += "<tr>";
          msg += "<th>Farmacia</th>";
          msg += "<th>Nro. Receta</th>";
          msg += "<th>Fecha Expedicion</th>";
          msg += "<th>Producto</th>";
          msg += "<th>Tiempo(Dias)</th>";
          msg += "<th>Dias Transcurridos</th>";
          msg += "<th>Dias Pendientes</th>";
          msg += "<th>Cantidad Prescrita</th>";
                        msg += "</tr>";
                      msg += "</thead>";
          msg += "<tbody>";
        }
            
        msg += "<tr><td>"+(result[key].farmacia)+"</td>";
        msg += "<td>"+(result[key].nro_receta)+"</td>";
        msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
        msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
        msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
        msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
        msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
        msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
        n++;
                                            }
        if(msg != "")/*{
            verlotes(id_farmacia, btn);      
        } 
        else */
        {
          bootbox.confirm({
              title: "Alerta",
              message: msg,
              buttons: {
                  cancel: {
                      label: '<i class="fa fa-times"></i> Cancelar'
                  },
                  confirm: {
                      label: '<i class="fa fa-check"></i> Continuar'
                  }
              },
              callback: function (result) {
                if(result==true){
                  verlotes(id_farmacia, btn);
                }
              }
          });
        }
      }
    });
   }
    filasmedicamentos++;
  }
  
  var _evitarChangeMedicamento = false;
  $('#example tbody').on('change', '.cmbMedicamento', function(e) {

    if (_evitarChangeMedicamento) {
        return;
    }

    let cmb = $(this);
    let fila = cmb.closest('tr');
    let id_producto = cmb.val();
    let id_user = {{ Auth::user()->id }};
    let flagx = 0;
    let infecto = cmb.find('option:selected').attr('infecto');
    let inputTiempo = fila.find('.tiempo_periodo');
    let nivel = $('#id_nivel').val();

    // Validar productos duplicados
    $('.cmbMedicamento').each(function() {
        if ($(this).val() == id_producto) {
            flagx++;
        }
    });

    if (flagx > 1) {
        bootbox.alert("El producto farmacéutico ya ha sido ingresado");
        _evitarChangeMedicamento = true;

        cmb.val(null).trigger('change');
        fila.find('input[type="text"], input[type="number"], textarea').val('');
        fila.find('input[type="checkbox"], input[type="radio"]').prop('checked', false);

        setTimeout(function() {
            _evitarChangeMedicamento = false;
        }, 50);

        return false;
    }

    let id_farmacia = $('#id_farmacia_receta').val(); 

    var option = {
        url: '/cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia,
        type: 'GET',
        dataType: 'json',
        data: {}
    };

    $.ajax(option).done(function(data) {

        var cantidad = data.cantidad;
        var cantidadEstablecimiento = data.cantidadEstablecimiento;
        var cantidadAlmacen = data.cantidadAlmacen;

        fila.find(".limpia_text").val("");
        fila.find("#nro_stocks").val(cantidad);
        fila.find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
        fila.find("#nro_stocks_almacen").val(cantidadAlmacen);
        fila.find(".tiempo_periodo").focus();
        fila.find("#nro_med_solictados").val("");
        fila.find("#nro_med_entregados").val("");
        fila.find("#lotes_lote").val("");
        fila.find("#lotes_cantidad").val("");
        fila.find("#lotes_registro_sanitario").val("");
        fila.find("#lotes_fecha_vencimiento").val("");
    });
    filasmedicamentos++;
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
  Swal.fire({
    icon: 'success',
    title: 'Procesando...',
    text: 'Por favor, espera un momento.',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading(); // Muestra el loader
    }
  });
  $.ajax({
      url: "<?php echo url()?>/genera_csv_evaluacion",
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
        //$("#flagServicioFirma").val("1");
        initInvoker('W');
      } else {
        //$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
        //dialogError(function () {});
      }
      $('#btnLiquidacionFirmarPdf').attr("disabled", false);
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'Se esta cargando el modulo de refirma.'
      });
    })
    .fail(function(jqXHR, textStatus) {
      //$("#dialogError").html('<p>Error de Coneccion, verifique si tiene Internet</p>');
      //dialogError(function () {});
      console.log("Request failed: " + textStatus)
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: textStatus
      });
    })
    .always(function() {
      //bootbox.hideAll();
    });

  //} else {

  //dialogWarning("Debe hacer check en minimo una solicitud");
  //}	


}

function firmarsintokens() {

  var id_paciente = $('#id_paciente').val();
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  let origen_alta = $("#has_alta_medica").val();
 
  var batchSignatureProcessPoller = setInterval(function() {
    $.ajax({
      url: "<?php echo url()?>/sinTokens",
      type: "post",
      cache: false,
      dataType: 'json',
      data: $("#frmBloque").serialize(),
      success: function(completed) {
        if (completed) {
          if (parseInt(origen_alta) > 0) {
            location.href = '/ver_atenciones_evaluacion/'+id_paciente+'/5/'+id_tipo_atencion;
          } else {
            location.href = '/ver_atenciones_evaluacion/'+id_paciente+'/3/'+id_tipo_atencion;
          }
          clearInterval(batchSignatureProcessPoller);
        }
      }
    });
  }, 3000);
}

function enviarReceta(id) {

  $("#id_receta").val(id);

}

function enviarRadiologia(id) {

  $("#id_radiologia").val(id);

}

function enviarLaboratorio(id) {

  $("#id_laboratorio").val(id);

}

$("#btnNuevoReceeta").click(function () {
  $("#tiene_receta").val($("#tiene_receta").val() == "true" ? "false" : "true");
});

function agregarReceta2() {
  var flagReceta    = $('#btnNuevoReceeta2').attr("flag");
  if (flagReceta == 0) {
    $('#btnNuevoReceeta2').attr("flag", 1).html("Ocultar Receta");
    $("#fsNuevaRecetaVale2").show();
    $("#divGuardar").show();
    $("#divFirmar").hide();
  } else {
    $('#btnNuevoReceeta2').attr("flag", 0).html("Agregar Receta");;
    $("#fsNuevaRecetaVale2").hide();
      $("#divGuardar").hide();
      $("#divFirmar").show();
    }
}

function agregarReceta() {

  var flagReceta            = $('#btnNuevoReceeta').attr("flag");
  var flagExamenLaboratorio = $('#btnNuevoExamenLab').attr("flag");
  var flagExamenRadiologia  = $('#btnNuevoExamenRad').attr("flag");
  var flagInterconsulta     = $('#btnNuevaInterconsulta').attr("flag");
  var flagReferencia        = $('#btnNuevaReferencia').attr("flag");

  if (flagReceta == 0) {
    $('#btnNuevoReceeta').attr("flag", 1).html("Ocultar Receta");
    $("#fsNuevaRecetaVale").show();
    $("#divGuardar").show();
    $("#divFirmar").hide();
  } else {
    $('#btnNuevoReceeta').attr("flag", 0).html("Agregar Receta");;
    $("#fsNuevaRecetaVale").hide();
    if (flagReceta == 1
      && flagExamenLaboratorio == 0
      && flagExamenRadiologia == 0
      && flagInterconsulta == 0
      ) {
      $("#divGuardar").hide();
      $("#divFirmar").show();
    }
  }

}

function agregarExamenLab() {

  var flagReceta            = $('#btnNuevoReceeta').attr("flag");
  var flagExamenLaboratorio = $('#btnNuevoExamenLab').attr("flag");
  var flagExamenRadiologia  = $('#btnNuevoExamenRad').attr("flag");
  var flagInterconsulta     = $('#btnNuevaInterconsulta').attr("flag");
  var flagReferencia        = $('#btnNuevaReferencia').attr("flag");

  if (flagExamenLaboratorio == 0) {
    $('#btnNuevoExamenLab').attr("flag", 1).html("Ocultar Examen Laboratorio");
    $("#fsNuevoExamenLab").show();
    $("#divGuardar").show();
    $("#divFirmar").hide();
  } else {
    $('#btnNuevoExamenLab').attr("flag", 0).html("Agregar Examen Laboratorio");;
    $("#fsNuevoExamenLab").hide();
    if (flagReceta == 0
      && flagExamenLaboratorio == 1
      && flagExamenRadiologia == 0
      && flagInterconsulta == 0
      ) {
      $("#divGuardar").hide();
      $("#divFirmar").show();
    }
  }
}

function agregarExamenRad() {

  var flagReceta            = $('#btnNuevoReceeta').attr("flag");
  var flagExamenLaboratorio = $('#btnNuevoExamenLab').attr("flag");
  var flagExamenRadiologia  = $('#btnNuevoExamenRad').attr("flag");
  var flagInterconsulta     = $('#btnNuevaInterconsulta').attr("flag");
  var flagReferencia        = $('#btnNuevaReferencia').attr("flag");

  if (flagExamenRadiologia == 0) {
    $('#btnNuevoExamenRad').attr("flag", 1).html("Ocultar Examen de Imagenología");
    $("#fsNuevoExamenRad").show();
    $("#divGuardar").show();
    $("#divFirmar").hide();
  } else {
    $('#btnNuevoExamenRad').attr("flag", 0).html("Agregar Examen de Imagenología");
    $("#fsNuevoExamenRad").hide();
    if (flagReceta == 0
      && flagExamenLaboratorio == 0
      && flagExamenRadiologia == 1
      && flagInterconsulta == 0
      ) {
      $("#divGuardar").hide();
      $("#divFirmar").show();
    }
  }
}

function agregarInterconsulta() {

  var flagReceta            = $('#btnNuevoReceeta').attr("flag");
  var flagExamenLaboratorio = $('#btnNuevoExamenLab').attr("flag");
  var flagExamenRadiologia  = $('#btnNuevoExamenRad').attr("flag");
  var flagInterconsulta     = $('#btnNuevaInterconsulta').attr("flag");
  var flagReferencia        = $('#btnNuevaReferencia').attr("flag");

  if (flagInterconsulta == 0) {
    $('#btnNuevaInterconsulta').attr("flag", 1).html("Ocultar Interconsulta");
    $("#fsNuevaInterconsulta").show();
    $("#divGuardar").show();
    $("#divFirmar").hide();
  } else {
    $('#btnNuevaInterconsulta').attr("flag", 0).html("Agregar Interconsulta");
    $("#fsNuevaInterconsulta").hide();
    if (flagReceta == 0
      && flagExamenLaboratorio == 0
      && flagExamenRadiologia == 0
      && flagInterconsulta == 1
      ) {
      $("#divGuardar").hide();
      $("#divFirmar").show();
    }
  }
}

function agregarReferencia() {

  var flagReceta            = $('#btnNuevoReceeta').attr("flag");
  var flagExamenLaboratorio = $('#btnNuevoExamenLab').attr("flag");
  var flagExamenRadiologia  = $('#btnNuevoExamenRad').attr("flag");
  var flagInterconsulta     = $('#btnNuevaInterconsulta').attr("flag");
  var flagReferencia        = $('#btnNuevaReferencia').attr("flag");

  if (flagReferencia == 0) {
    $('#btnNuevaReferencia').attr("flag", 1).html("Ocultar Referencia");
    $("#fsNuevaReferencia").show();
    $("#divGuardar").show();
    $("#divFirmar").hide();
  } else {
    $('#btnNuevaReferencia').attr("flag", 0).html("Agregar Referencia");
    $("#fsNuevaReferencia").hide();
    if (flagReceta == 0
      && flagExamenLaboratorio == 0
      && flagExamenRadiologia == 0
      && flagInterconsulta == 0
      && flagReferencia == 1) {
      $("#divGuardar").hide();
      $("#divFirmar").show();
    }
  }
}

function agregarAltaPaciente() {

  var flag = $('#btnAltaPaciente').attr("flag");
  if (flag == 0) {
    $('#btnAltaPaciente').attr("flag", 1).html("Ocultar Alta Paciente");
    //$("#fsNuevaAltaPaciente").show();
    $("#divAlta2").show();
    $("#divGuardar").show();
    $("#divFirmar").hide();
  } else {
    $('#btnAltaPaciente').attr("flag", 0).html("Agregar Alta Paciente");;
    //$("#fsNuevaAltaPaciente").hide();
    $("#divAlta2").hide();
    $("#divGuardar").hide();
    $("#divFirmar").show();
  }
}


function nro_interconsulta() {
  var dni_beneficiario = $('#dni_beneficiario').val();
  var id_farmacia = $('#id_farmacia').val();
  var x = 0;
  if (dni_beneficiario != "") {
    $.ajax({
      url: '/obtenerCantInterconsultas/' + dni_beneficiario + '/' + id_farmacia,
      success: function(result) {
        if (result != null && result != "") {
          for (var key in result) {
            x++;
          }
        }
      }

    });

    return x;
  }
}

function getSexoGenero(){
  return ($('#nomsexo').val() == 'M') ? 1 : 2;
}

function enviarLaboratorio(id) {
  $("#id_laboratorio").val(id);
}

$('#btnInvalidarLaboratorio').click(function (e) {
  e.preventDefault();
  $.ajax({
    url: "<?php echo url()?>/invalidar_examen_laboratorio",
    type: "post",
    data: $("#formInvalidaExamenLab").serialize(),
    success: function(response) {
      if (response == "0") {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "Examen de laboratorio se encuentra anulada",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          willClose: () => {
            location.reload();
          }
        });
      }
      if (response == "1") {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Examen de laboratorio anulada correctamente",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          willClose: () => {
            location.reload();
          }
        });
      }
      if (response == "2") {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "No puedes invalidar, las pruebas ya se enviaron",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          willClose: () => {
            location.reload();
          }
        });
      }
    },
    error: function(xhr, status, error) {
        console.log(xhr.responseText);
        console.log(status);
        console.log(error);
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "Hubo un error",
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
          willClose: () => {
            location.reload();
          }
        });
    }
  });
  
});

$('#btnInvalidarRadiologia').click(function (e) {
  console.log('desde click invalidar');  
  e.preventDefault();
  $.ajax({
    url: "<?php echo url()?>/invalidar_examen_imagenologia",
    type: "post",
    data: $("#formInvalidaExamenRad").serialize(),
    success: function(response) {
      if (response == "0") {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "Examen de Imagenes se encuentra anulada",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          willClose: () => {
            location.reload();
          }
        });
      }
      if (response == "1") {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Examen de Imagenes anulada correctamente",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          willClose: () => {
            location.reload();
          }
        });
      }
      if (response == "2") {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "No puedes invalidar, las pruebas ya se genero un informe",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          willClose: () => {
            location.reload();
          }
        });
      }
    },
    error: function(xhr, status, error) {
        console.log(xhr.responseText);
        console.log(status);
        console.log(error);
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "Hubo un error",
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
          willClose: () => {
            location.reload();
          }
        });
    }
  });
  
  
});


  <?php if($nivel == 3): ?>
  		var grupos = [
	        ["82803.01", "36600"],
	        ["82575", "82565"],
	        ["89190", "87999"],
	        ["87070", "87184"],
	        ["87163", "87184"],
	        ["87045", "87184"],
	        ["87040.01", "87184"],
	        ["87040.02", "87184"]
	    ];
	<?php else:?>
		var grupos = [];
	<?php endif;?>

    function verificarFaltantes(seleccionados) {
        var mensajes = [];

        /*$.each(grupos, function (index, grupo) {
            var presentes = grupo.filter(codigo => seleccionados.includes(codigo));

            if (presentes.length === 1) { // Solo uno del grupo está presente
                var faltante = grupo.find(codigo => !seleccionados.includes(codigo));
                mensajes.push("En Examenes de laboratorio: Falta la prueba " + faltante + " que está relacionada con " + presentes[0]);
            }
        });*/

        return mensajes.length > 0 ? mensajes.join("\n") : "";
    }

  @if (empty($interconsultas))
    $("#is_estable").on("mousedown", function () {
      // Guardamos el valor actual antes del cambio
      is_estable_auxiliar = $(this).val();
    });

    $("#is_estable").change(function() {

      // Inicio validar si hay interconsultas agregadas
      let cant_registros = $('#tblInterconsulta tbody tr').length;
      let has_interconsulta_agregada = false;

      if (cant_registros > 0) {
        
        let interconsulta_filas = $('#tblInterconsulta tbody').children('tr');
        
        for (let i = 0; i < cant_registros; i++) {
          $('.cmbInterconsultaServicios').each(function() {
            if ($(this).val() != "") {
              has_interconsulta_agregada = true;
              return false;
            }
          });
        }
      }
      // Fin validar si hay interconsultas agregadas

      if (has_interconsulta_agregada) {
        bootbox.confirm({
          message: "Al cambiar la opción de <b>Estable</b> se anulará los registros de solicitud de interconsulta ya adicionados.<br><br>¿Desea continuar?",
          buttons: {
            confirm: {
                label: 'Continuar',
                className: 'btn-success'
            },
            cancel: {
                label: 'Cancelar',
                className: 'btn-danger'
            }
          },
          callback: function (result) {
            if (result) {
              const tbody_tblInterconsulta = document.querySelector("#tblInterconsulta tbody");
              tbody_tblInterconsulta.innerHTML = "";
            } else {
              $("#is_estable").val(is_estable_auxiliar);
            }
          }
        });
      }

    });
  @endif

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
  
  .icon-doc-firmado {
    color: #5cb85c;
    font-size: 3rem;
  }

  .swal2-popup,
  .swal2-header {
    padding: 1.25em !important;
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
            <h3 class="panel-title">MÓDULO DE EVALUACIONES MEDICAS</h3>
          </div>

          <div class="panel-body">


            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->


            <form class="form-horizontal" method="post" id="frmBloque"
              action="{{ route('registrar_evaluacion_adicional')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" name="tiene_receta" id="tiene_receta" value="">
              <input type="hidden" value="{{$id_farmacia}}" name="id_farmacia" id="id_farmacia">
              <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacion" id="id_prestacion">
              <input type="hidden" value="<?php echo $id_evaluacion?>" name="id_evaluacion" id="id_evaluacion">
              <input type="hidden" value="<?php echo $prestacion->id_cita?>" name="id_cita" id="id_cita">
              <input type="hidden" value="<?php echo $prestacion->id_asegurado?>" name="id_asegurado" id="id_asegurado">
              <input type="hidden" value="<?php echo $prestacion->id_establecimiento?>" name="id_establecimiento"
                id="id_establecimiento">
              <input type="hidden" value="<?php echo $prestacion->id_sub_consultorio?>" name="id_sub_consultorio"
                id="id_sub_consultorio">
              <input type="hidden" value="<?php echo $evaluacion->id_medico?>" name="id_medico" id="id_medico">
              <input type="hidden" value="<?php echo $nivel?>" name="id_nivel" id="id_nivel">
              <input type="hidden" value="<?php echo $turno?>" name="turno" id="turno">
              <input type="hidden" value="<?php echo $prestacion->id_tipo_atencion?>" name="id_tipo_atencion" id="id_tipo_atencion">
              <input type="hidden" value="<?php echo $prestacion->id_consultorio?>" name="id_servicio" id="id_servicio">
              <input type="hidden" value="<?php echo $prestacion->sexo?>" name="nomsexo" id="nomsexo">
              <input type="hidden" value="<?php echo date("d-m-Y", strtotime($fechaServidor))?>" name="fecha_atencion" id="fecha_atencion">
              <input type="hidden" value="{{ $prestacion->nro_doc_ident }}" name="dni_beneficiario" id="dni_beneficiario">
              <input type="hidden" name="fecha_evaluacion" value="{{ $evaluacion->fecha_evaluacion }}">
              <input type="hidden" name="id_topico" value="{{ $obtenerdato->ubica_topico }}">
              <input type="hidden" name="id_cama" value="{{ $obtenerdato->cama }}">
              <input type="hidden" name="id_principal" id="id_principal" value="{{$id_principal}}">
              <input type="hidden" id="id_rua_imagenes" name="id_rua_imagenes" value="">
              <?php
              $flag_firmar = 0;
              if($flag_pendinte_firma == 1) {
                $flag_firmar = 1;
              }
            ?>
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                  <div class="row">
                    <!-- ===================== DATOS DEL PACIENTE ===================== -->
                    <div class="col-md-6">
                      <div class="panel panel-info" style="min-height:320px;">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
                        </div>
                        <div class="row">
                          <div class="col-md-3">
                            
                          </div>
                        </div>
                          <div class="panel-body" style="padding-bottom: 24px;">
                            <table class="table table-borderless" style="width:100%;margin-bottom: 0px !important;" >
                              <tr>
                                  <th colspan="3" style="padding-bottom:8px !important; color:#000" ><strong>DATOS DEL PACIENTE:</strong></th>
                              </tr>
                              <tr>
                                  <th style="width:45%;">TIPO Y N° DE DOC</th>
                                  <th style="width:5%;">:</th>
                                  <td><?php echo $prestacion->tipo_doc_ident?> - <?php echo $prestacion->nro_doc_ident?></td>
                              </tr>

                              <tr>
                                  <th>APELLIDOS Y NOMBRES</th>
                                  <th>:</th>
                                  <td style="text-transform:uppercase;">
                                      <?php  echo $prestacion->asegurado_paterno.' '.$prestacion->asegurado_materno.', '.$prestacion->asegurado_nombre; ?>
                                  </td>
                              </tr>

                              <tr>
                                  <th>FECHA DE NACIMIENTO</th>
                                  <th>:</th>
                                  <td><?php echo $prestacion->fecha_nac; ?></td>
                              </tr>

                              <tr>
                                  <th>EDAD / SEXO</th>
                                  <th>:</th>
                                  <td>
                                      <?php
                                      $fecha_nac = DateTime::createFromFormat('d/m/Y', $prestacion->fecha_nac);
                                      $hoy = new DateTime();
                                      if ($fecha_nac) {
                                        $edad = $fecha_nac->diff($hoy);
                                        echo $edad->y.' AÑOS, '.$edad->m.' MESES, '.$edad->d.' DÍAS';
                                      }
                                      ?>
                                      - <?php 
                                        if($prestacion->sexo === 'M'){
                                          echo "MASCULINO";
                                        }else{
                                          echo "FEMENINO";
                                        }
                                        ?>
                                  </td>
                              </tr>

                              <tr>
                                  <th>TIPO BENEFICIARIO</th>
                                  <th>:</th>
                                  <td> <?php echo $prestacion->parentesco?></td>
                              </tr>

                              <tr>
                                  <th>N° DE HISTORIA CLÍNICA</th>
                                  <th>:</th>
                                  <td><span id="nro_historia" style="display:inline-block; text-align:left;"><?php echo $prestacion->nro_historia ?></span></td>
                              </tr>

                              <?php 
                                $doc   = null;
                                $cip   = null;
                                $nom   = null;
                                $grado = null;
                              
                                if($prestacion->parentesco=='TITULAR'){
                                  $doc   = $prestacion->nrodoctitular;
                                  $cip   = $prestacion->cip_titular;
                                  $nom   = $prestacion->paterno_titular.' '.$prestacion->materno_titular.', '.$prestacion->nombre_titular;
                                  $grado = $prestacion->grado;
                                }elseif($prestacion->situacion_paciente!='CIVIL' && $flag_no_encontrado==0){
                                  $doc   = $dato_titular[0]->nro_doc_ident;
                                  $cip   = $dato_titular[0]->cip;
                                  $nom   = $dato_titular[0]->paterno.' '.$dato_titular[0]->materno.', '.$dato_titular[0]->nombre;
                                  $grado = $dato_titular[0]->grado;
                                }else{
                                  $doc   = 'CIVIL';
                                  $cip   = 'CIVIL';
                                  $nom   = 'CIVIL';
                                  $grado = 'CIVIL';
                                }

                              ?>   
                              <tr>
                                  <th colspan="3" style="padding-bottom:8px !important; padding-top:8px !important; color:#000"><strong>DATOS DEL TITULAR:</strong></th>
                              </tr>
                              <tr>
                                  <th>N° DE DOC</th>
                                  <th>:</th>
                                  <td><?php echo $prestacion->nrodoctitular;?></td>
                              </tr>
                              <tr>
                                  <th>N° DE CIP</th>
                                  <th>:</th>
                                  <td><?php echo $prestacion->cip_titular;?></td>
                              </tr>

                              <tr>
                                  <th>APELLIDOS Y NOMBRES</th>
                                  <th>:</th>
                                  <td><?php echo $prestacion->paterno_titular.' '.$prestacion->materno_titular.', '.$prestacion->nombre_titular;?></td>
                              </tr>

                              <tr>
                                  <th>GRADO</th>
                                  <th>:</th>
                                  <td><?php echo $prestacion->grado_titular; ?></td>
                              </tr>
                              <?php 
                                $parentesco   = null;
                                if($tipo_parentesco !='SOLO'){
                                  $parentesco   = $paciente_conducido_por." - (".$tipo_parentesco.")";
                                }else{
                                  $parentesco   = 'VIENE SOLO';
                                }
                              ?> 
                              <tr>
                                  <th style="padding-bottom:8px !important; padding-top:8px !important; color:#000"><strong>CONDUCIDO POR</strong></th>
                                  <th style="padding-bottom:8px !important; padding-top:8px !important; color:#000">:</th>
                                  <td style="padding-bottom:8px !important; padding-top:8px !important;"><?php echo $parentesco; ?></td>
                              </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                      <!-- ===================== DATOS DE LA ATENCIÓN ===================== -->
                      <div class="col-md-6">
                          <div class="panel panel-info" style="min-height:320px;">
                              <div class="panel-heading">
                                  <h3 class="panel-title size-titulo">DATOS DE LA ATENCIÓN</h3>
                              </div>
                              <div class="panel-body">

                                  <table class="table table-borderless" style="width:100%;margin-bottom: 0px;">
                                      <tr>
                                          <th  style="padding-top:8px !important; color:#000"><strong>DATOS DE LA ATENCIÓN: </strong></th>
                                      </tr>
                                      <tr>
                                          <th style="width:45%;">COD. SITEDS SUSALUD</th>
                                          <th style="width:5%;">:</th>
                                          <td><span><?php echo $evaluacion->cod_siteds_susalud?></span></tr>

                                      <tr>
                                          <th>FECHA DE ATENCIÓN</th>
                                          <th>:</th>
                                          <td> <?php echo $evaluacion->fecha_evaluacion;?>
                                          </td>
                                      </tr>

                                      <tr>
                                          <th>PRIORIDAD</th>
                                          <th>:</th>
                                          <td><?php echo $paciente->servicio;?>
                                          </td>
                                      </tr>

                                      <tr>
                                          <th>AREA</th>
                                          <th>:</th>
                                          <td><?php echo $paciente->consultorio;?>
                                          </td>
                                      </tr>
                                    
                                      <tr>
                                          <th>PABELLON</th>
                                          <th>:</th>
                                          <td><?php echo $datoadicional->pabellon;?>
                                          </td>
                                      </tr>

                                      <tr>
                                          <th>PISO</th>
                                          <th>:</th>
                                          <td><?php echo $datoadicional->piso;?></td>
                                      </tr>

                                      <tr>
                                          <th>HABITACIÓN</th>
                                          <th>:</th>
                                          <td><?php echo $datoadicional->cuarto;?></td>
                                      </tr>

                                      <tr>
                                          <th>CAMA/CAMILLA</th>
                                          <th>:</th>
                                          <td><?php echo $datoadicional->cama;?><span>
                                              
                                          </span>
                                          </td>
                                      </tr>
                                    
                                      <tr>
                                          <th colspan="3" style="padding-bottom:8px !important; padding-top:8px !important; color:#000"><strong>RESPONSABLE DE ATENCIÓN: </strong></th>
                                      </tr>
                                      <tr>
                                          <th>TIPO Y N° DE DOC</th>
                                          <th>:</th>
                                          <td><?php echo $prestacion->descripcion?> - <?php echo $evaluacion->dni?>
                                          </td>
                                      </tr>

                                      <tr>
                                          <th>APELLIDOS Y NOMBRES</th>
                                          <th>:</th>
                                          <td><?php echo $evaluacion->medico_paterno.' '.$evaluacion->medico_materno.', '.$evaluacion->medico_nombre;?>
                                          </td>
                                      </tr>

                                      <tr>
                                          <th>PROFESION/ESPECIALIDAD</th>
                                          <th>:</th>
                                          <td><?php echo $evaluacion->profesion." / ".$evaluacion->especializacion;?>
                                          </td>
                                      </tr>
                                      <tr>
                                          <th>N° DE COLEGIATURA/N° DE REG. DE ESPECIALIDAD</th>
                                          <th>:</th>
                                          <td><?php echo $evaluacion->colegiatura." / ".$evaluacion->rne;?>
                                          </td>
                                      </tr>
                                  </table>
                              </div>
                          </div>
                      </div>
                    </div>
                  <div> 
              </div>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center" style="position: relative">
                      <b>DIAGNÓSTICOS ATENCION</b>
                      <!--button type="button" class="btn btn-primary" id="btnEditDiagnostico" style="position: absolute; top:-6px; right: 0; display: none;">Editar</button-->
                    </div>
                  </div>
                  @if(count($diagnostico_atencion)>0)
                  <div class="panel-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token_tag">
                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="20%">Codigo</th>
                          <th width="60%">Nombre</th>
                          <th width="20%">Tipo</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($diagnostico_atencion as $row)
                          <?php
                            $tipoDiagnostico = '';
                            switch($row->id_tipo_diagnostico) {
                              case 1: $tipoDiagnostico = 'PRESUNTIVO'; break;
                              case 2: $tipoDiagnostico = 'DEFINITIVO'; break;
                              case 3: $tipoDiagnostico = 'REITERATIVO'; break;
                              default:
                              // Contenido para el caso por defecto
                            }
                            ?>
                          <tr>
                            <td>{{ $row->codigo }}</td>
                            <td>{{$row->nombre}}</td>
                            <td>{{ $tipoDiagnostico }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  @endif
               </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center" style="position: relative">
                      <b>DIAGNÓSTICOS EVALUACION</b>
                    </div>
                  </div>
                  @if(count($diagnostico_evaluacion)>0)
                  <div class="panel-body">
                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="20%">Codigo</th>
                          <th width="60%">Nombre</th>
                          <th width="20%">Tipo</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($diagnostico_evaluacion as $rowi)
                          <?php
                            $tipoDiagnostico = '';
                            switch($rowi->id_tipo_diagnostico) {
                              case 1: $tipoDiagnostico = 'PRESUNTIVO'; break;
                              case 2: $tipoDiagnostico = 'DEFINITIVO'; break;
                              case 3: $tipoDiagnostico = 'REITERATIVO'; break;
                              default:
                              // Contenido para el caso por defecto
                            }
                            ?>
                          <tr>
                            <td>{{ $rowi->codigo }}</td>
                            <td>{{$rowi->nombre}}</td>
                            <td>{{ $tipoDiagnostico }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  @endif
               </div>
              </fieldset>
              <fieldset>
    <div class="panel panel-info panel-bordered panel-sm" @if(empty($gestacion) && empty($puerperio) && empty($natis)) style="display: none;" @endif>

        <div class="panel-heading">
            <div class="panel-title text-center">
                <b>SALUD MATERNA</b>
            </div>
        </div>
        <div class="panel-body">
            @if (!empty($gestacion))
            <div class="form-group">
                <div class="panel-title text-center">
                    <b>GESTANTES</b>
                </div>
                <div class="col-md-3">
                 
                    <label for="tipo">Última menstruación</label>
                    <p>{{ $gestacion['fech_ult_regla'] }}</p>
                </div>
                <div class="col-md-3">
                    <label>Fecha probable de parto</label>
                    <p>{{ $gestacion['fech_prob_parto'] }}</p>
                </div>
                <div class="col-md-3">
                    <label>Semanas de gestación</label>
                    <p>{{ $gestacion['semanas_gestacion'] }}</p>
                </div>
                <div class="col-md-3">
                    <label>N° de controles prenatales:</label>
                    <p>{{ $gestacion['num_controles'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3">
                    <label>DUDA</label>
                    <p>{{ $gestacion['duda'] ? 'Sí' : 'No' }}</p>
                </div>
            </div>
            @endif

            @if (!empty($puerperio))
            <div class="form-group">
                <div class="panel-title text-center">
                    <b>PUERPERIAS</b>
                </div>

                <div class="col-md-3">
                    <label>FECHA PARTO</label>
                    <p>{{ $puerperio['fecha_parto'] }}</p>
                </div>
<div class="col-md-3">
    <label>TIPO DE PARTO</label>
    <p>
        @if ($puerperio['id_tipo_parto'] == 1)
            Vaginal
        @elseif ($puerperio['id_tipo_parto'] == 2)
            Cesárea
        @else
            Otro
        @endif
    </p>
</div>
<div class="col-md-3">
                    <label>COMPLICACION</label>
                    <p>{{ $puerperio['complicaciones'] }}</p>
                </div>
            </div>
            @endif
                <div class="panel-title text-center">
    <b>NEONATOS</b>
</div>

            @if (!empty($natis))
                @foreach ($natis as $neonato)
                    <div class="form-group">

                      <div class="col-md-3">
                <label>ESTADO DE VIDA</label>
                <p>{{ $neonato['estado'] }}</p>
            </div>
            <div class="col-md-3">
                <label>NUM. DOCUMENTOS</label>
                <p>{{ $neonato['num_doc'] }}</p>
            </div>
            <div class="col-md-3">
    <label>SEXO</label>
    <p>
        @if ($neonato['sexo'] == 'M')
            MASCULINO
        @elseif ($neonato['sexo'] == 'F')
            FEMENINO
        @endif
    </p>
</div>
            <div class="col-md-1">
                <label>TALLA(cm)</label>
                <p>{{ $neonato['talla'] }}</p>
            </div>
            <div class="col-md-1">
                <label>PESO(gr)</label>
                <p>{{ $neonato['peso'] }}</p>
            </div>

                    </div>
                @endforeach
            @endif
        </div>
    </div>
</fieldset>
              
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center" style="position: relative">
                        <b>PROCEDIMIENTOS ATENCION</b>
                        <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0;display:none;">Editar</button>
                      </div>
                    </div>
                    @if(count($procedimiento_atencion)>0)
                    <div class="panel-body">
                      <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                            <th width="20%">codigo</th>
                            <th width="60%">Nombre</th>
                            <th width="20%">Cantidad</th>
                          </tr>
                        </thead>
                        <tbody>
                          
                        @foreach($procedimiento_atencion as $row)
                          <tr>
                            <td>{{ $row->codigo }}</td>
                            <td>{{$row->nombre}}</td>
                            <td>{{ $row->cantidad }}</td>
                          </tr>
                        @endforeach
                        </tbody>
                      </table>
                    </div>
                    @endif
                  </div>
                </fieldset>
              
              
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center" style="position: relative">
                        <b>PROCEDIMIENTOS EVALUACION</b>
                        <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0;display:none;">Editar</button>
                      </div>
                    </div>
                    @if(count($procedimiento_evaluacion)>0)
                    <div class="panel-body">
                      <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                            <th width="20%">codigo</th>
                            <th width="60%">Nombre</th>
                            <th width="20%">Cantidad</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                          
                        @foreach($procedimiento_evaluacion as $rowx)
                          <input type="hidden" value="{{ $rowx->cod }}" name="prestacion_procedimiento_id[]" style="width : 1px; heigth : 1px">
                          <tr>
                            <td>{{ $rowx->codigo }}</td>
                            <td>{{$rowx->nombre}}</td>
                            <td>{{ $rowx->cantidad }}</td>
                          </tr>
                        @endforeach
                        </tbody>
                        
                      </table>
                    </div>
                    @endif
                    <br/><br/>
                  </div>
                </fieldset>
                @if ($verificar_existe->id_triaje == 6 || $verificar_existe->id_triaje == 7)
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>EVENTOS EPIDEMIOLOGICOS</b>
                      </div>
                    </div>
                    <div class="panel-body" id="">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="">
                              <div class="col-md-6">
                                <div class="checkbox">
                                  <label>
                                    <input disabled type="checkbox" name="evento_ep[]" value="11" {{ in_array(11, $eventosMarcados) ? 'checked' : '' }}>
                                    <span style="margin-left: 6px;">Gestantes que han recibido por lo menos un control prenatal en la IPRESS</span></label>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="checkbox">
                                  <label>
                                    <input disabled type="checkbox" name="evento_ep[]" value="12" {{ in_array(12, $eventosMarcados) ? 'checked' : '' }}>
                                    <span style="margin-left: 6px;">Gestantes que han recibido por lo menos 6 controles prenatales</span></label>
                                </div>
                              </div>
                            </div>
                            <div class="">
                              <div class="col-md-6">
                                <div class="checkbox">
                                  <label>
                                    <input disabled type="checkbox" name="evento_ep[]" value="13" {{ in_array(13, $eventosMarcados) ? 'checked' : '' }}>
                                    <span style="margin-left: 6px;">Gestantes que han recibido la primera consulta en el primer trimestre del embarazo</span></label>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="checkbox">
                                  <label>
                                    <input disabled type="checkbox" name="evento_ep[]" value="16" {{ in_array(16, $eventosMarcados) ? 'checked' : '' }}>
                                    <span style="margin-left: 6px;">Nacidos vivos con bajo peso al nacer (menos de 2500 gramos)</span></label>
                                </div>
                              </div>
                            </div>
                            <div class="">
                              <div class="col-md-6">
                                <div class="checkbox">
                                  <label>
                                    <input disabled type="checkbox" name="evento_ep[]" value="21" {{ in_array(21, $eventosMarcados) ? 'checked' : '' }}>
                                    <span style="margin-left: 6px;">Nacidos vivos con edad gestacional menor a 37 semanas de gestacion (segun fecha ultima regla)</span></label>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="checkbox">
                                  <label>
                                    <input disabled type="checkbox" name="evento_ep[]" value="15" {{ in_array(15, $eventosMarcados) ? 'checked' : '' }}>
                                    <span style="margin-left: 6px;">Gestantes con diagnostico confirmado de infeccion HIV</span></label>
                                </div>
                              </div>
                            </div>
                            <div class="">
                              <div class="col-md-6">
                                <div class="checkbox">
                                  <label>
                                    <input disabled type="checkbox" name="evento_ep[]" value="14" {{ in_array(14, $eventosMarcados) ? 'checked' : '' }}>
                                    <span style="margin-left: 6px;">Gestantes con diagnostico confirmado de Tuberculosis</span></label>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="checkbox">
                                  <label>
                                    <input disabled type="checkbox" name="evento_ep[]" value="8" {{ in_array(8, $eventosMarcados) ? 'checked' : '' }}>
                                    <span style="margin-left: 6px;">Fallecimiento del paciente</span></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
                @endif
                <fieldset id="fsAntecedente">
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                          <b>ANTECEDENTES</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="col-md-12">
                        <label>ANTECEDENTE</label>
                        <p>{!! nl2br(e($evaluacion->antecedente)) !!}</p>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="col-md-12">
                        <label>ANTECEDENTE FAMILIAR</label>
                          <p>{!! nl2br(e($evaluacion->antecedente_familiar)) !!}</p>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset id="fsAnamnesis">
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                          <b>ANAMNESIS</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="col-md-12">
                        <label>MOTIVO DE VISITA</label>
                        <p>{!! nl2br(e($evaluacion->motivo_visita)) !!}</p>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="col-md-12">
                        <label>TIEMPO DE ENFERMEDAD/RELATO CRONOLOGICO</label>
                          <p>{!! nl2br(e($evaluacion->tiempo_enfermedad)) !!}</p>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="col-md-12">
                        <label>EXAMENES AUXILIARES</label>
                        <p>{!! nl2br(e($evaluacion->examen_auxiliar)) !!}</p>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset id="fsAnamnesis">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                              <b>EXAMEN FÍSICO</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          @if($evaluacion->temperatura>0)
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
                                  <p><?php echo $evaluacion->temperatura?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>TALLA (cm) &nbsp;</label>
                                  <p><?php echo $evaluacion->talla_cm?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>PESO (Kg) &nbsp;</label>
                                  <p><?php echo $evaluacion->peso_kg?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>IMC &nbsp;</label>
                                  <p>
                                  <?php
                                    if($prestacion->talla_cm!=0 and $evaluacion->talla_cm!=0):
                                      echo ($evaluacion->peso_kg / (($evaluacion->talla_cm * $evaluacion->talla_cm)*(0.01*0.01)));
                                    endif;
                                  ?>
                                  </p>
                                </div>
                                <div class="col-md-2">
                                  <label>PESO IDEAL &nbsp;</label>
                                  <p><?php echo 45+(($evaluacion->peso_kg-150)*3/4)?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>Sa02 (%) &nbsp;</label>
                                  <p><?php echo $evaluacion->sa02_porcentaje?></p>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-2">
                                  <label>TAMAÑO DE CINTURA (cm)</label>
                                  <p><?php echo $evaluacion->tamano_cintura_cm?></p>
                                </div>
                                <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                  <label>FREC. CARDIACA FETAL(/min)</label>
                                  <p><?php echo $evaluacion->frecuencia_cardiaca_fetal?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>PRESIÓN ARTERIAL &nbsp;</label>
                                  <div class="row">
                                    <div class="col-xs-5">                          
                                      <p><?php echo $evaluacion->presion_arterial_x?></p>
                                    </div>  
                                    <div class="col-xs-2">
                                      <label>/</label> 
                                    </div>
                                    <div class="col-xs-5">
                                      <p><?php echo $evaluacion->presion_arterial_y?></p>
                                    </div>  
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <label>FREC.RESPIRATORIA(/min)</label>
                                  <p><?php echo $evaluacion->frecuencia_respiratoria?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>FREC. CARDIACA (/min) &nbsp;</label>
                                  <p><?php echo $evaluacion->frecuencia_cardiaca?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>Fi02 (%) &nbsp;</label>
                                  <p><?php echo $evaluacion->fi02?></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>EXAMEN FISICO</label>
                                <p>{!! nl2br(e($evaluacion->examen_fisico)) !!}</p>
                            </div>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>EXAMEN CLINICO</label>
                              <p>{!! nl2br(e($evaluacion->examen_clinico)) !!}</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    <fieldset id="fsAntecedente">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>TRATAMIENTO Y/O TERAPIA</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="col-md-12">
                            <p>{!! nl2br(e($evaluacion->tratamiento_auxiliar)) !!}</p>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    <fieldset id="fsAntecedente">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>PLAN DE TRABAJO</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="col-md-12">
                            <p>{!! nl2br(e($evaluacion->plan_trabajo)) !!}</p>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    @if (empty($verificar_existe->origen_alta) || intval($verificar_existe->origen_alta) != 3)
                    <fieldset id="fsRecetaVale">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>PRODUCTOS MEDICOS</b>
                          </div>
                        </div>
                        <div class="panel-body panel-body-receta">
                          <?php foreach($receta as $rowReceta):?>
                          <?php if($rowReceta->flag_origen == null): ?>
                          <?php if ($rowReceta->fecha_firma == "") {
                              $flag_firmar = 1;
                            }
                            $model_productos_receta = new \App\Models\Producto_receta;
                            $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);
                          ?>
                          <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                            <?php if($rowReceta->estado=="2"){?>
                            <a data-toggle="modal" data-target="#squarespaceModal"
                              onclick="enviarReceta(<?php echo $rowReceta->id?>)" class="btn btn-danger">
                              <i class="fa fa-xing"></i> Invalidar Receta
                            </a>
                            <?php }else{ ?>
                              <button class="btn btn-danger" disabled="disabled"><i class="fa fa-xing"></i> Invalidar
                                Receta</button>
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
                                      <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_expedicion)); ?></p>
                                    </div>
                                    <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                    <div class="col-md-4" style="padding:0px">
                                      <p><?php echo $rowReceta->farmacia; ?></p>
                                    </div>
                                  </div>
                                </div>
                                <table class="display" cellspacing="0" width="100%">
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
                          </fieldset>
                          <?php endif; ?>
                          <?php endforeach;?>
                            <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                              <a onclick="agregarReceta()" id="btnNuevoReceeta" flag="0" class="btn btn-success">Agregar Receta</a>
                            </div>
                          <div style="clear:both"></div>
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
                                        <?php foreach($farmacias as $row):?>
                                      <option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
                                           @if($nivel==3) 
                                            <option value="21">Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                                            <option value="132">Farmacia de Preparados Farmacéutico No Estériles del Hospital Nacional PNP Luis N. Saenz</option>
                                           @endif
                                        <?php endforeach;?>
                                    </select>
                                  </div>
                                </div>
                                <br />
                                <table>
                                  <tr>
                                    <td>
                                      <button type="button" onclick="AddProducto()" class="btn btn-primary" 
                                  >Agregar Producto</button>
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
                    @endif
                        <input type="hidden" id="doc_firmar_laboratorio" value="" />
                        <input type="hidden" value="{{ $flag_laboratorio }}" name="flag_laboratorio" id="flag_laboratorio">
                        <input type="hidden" value="{{ $flag_firmar_laboratorio }}" name="flag_firmar_laboratorio" id="flag_firmar_laboratorio">
                      <div class="form-group">
                        <div class="col-md-12">   
                        <fieldset id="fsExamenLab">
                          <div class="panel panel-info panel-bordered panel-sm" style="  
                          <?php
                           if(!$is_profesion_medico){
                            echo "display: none;";
                           }
                          
                          ?>
                          ">
                            <div class="panel-heading">
                              <div class="panel-title text-center">
                                  <b>EXAMEN DE LABORATORIO</b>
                              </div>
                            </div>
                            <div class="panel-body">
                              <?php foreach($laboratorio as $rowLaboratorio):
                                if ($rowLaboratorio->fecha_firma == "") {
                                  $flag_firmar_lab = 1;
                                }
                                $model_prestacion = new \App\Models\Prestaciones;
                                //$proclaboratorio = $model_prestacion->GetPrestacionLaboratorio($id_prestacion, $rowLaboratorio->id);
                                $proclaboratorio = $model_prestacion->GetPrestacionLaboratorioEvaluacion($id_prestacion, $rowLaboratorio->id, $id_evaluacion);
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
                              <div class="form-group">
                                <div class="col-md-12">
                                  <fieldset>
                                    <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                            <table class="display" cellspacing="0" width="100%">
                                              <thead>
                                                <tr>
                                                  <th width="5%">Código</th>
                                                  <th>Nombre</th>
                                                  @if (!$rowLaboratorio->has_observacion)
                                                  <th width="20%">Observación</th>
                                                  @endif
                                                  <th width="10%">Cantidad</th>
                                                  <th width="10%">Diagnóstico</th>
                                                  @if (intval($nivel) === 3 && intval($rowLaboratorio->flag) > 0)
                                                    <th width="20%">Observaciones de Laboratorio</th>
                                                  @endif
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
                                                    <td><?php echo $row->cod_diag?></td>
                                                    @if (intval($nivel) === 3 && intval($rowLaboratorio->flag) > 0)
                                                      <td>@if ($row->pendiente_laboratorio == "SI") Pendiente de Toma de muestra @endif</td>
                                                    @endif
                                                  </tr>
                                                <?php endforeach;?>

                                                
                                                @if (intval($nivel) === 3 && $rowLaboratorio->has_observacion)
                                                  <?php
                                                  $colspan = (intval($rowLaboratorio->flag) > 0) ? "5" : "4";
                                                  ?>
                                                  <tr>
                                                    <td colspan="{{ $colspan }}">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="{{ $colspan }}"><b>Observación:</b> {{ $rowLaboratorio->observacion }}</td>
                                                  </tr>
                                                @endif
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>
                              </div>
                              <?php endforeach;?>
                                <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                  <a onclick="agregarExamenLab()" id="btnNuevoExamenLab" flag="0" class="btn btn-success">Examen Laboratorio</a>
                                </div>
                              <div style="clear:both"></div>
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
                                        <th width="45%">Exámen</th>
                                        @if (intval($nivel) !== 3)
                                        <th width="18%">Observación</th>
                                        @endif
                                        <!-- <th width="10%">Cantidad</th> -->
                                        <th width="10%">Diagnóstico</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                      </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                  </table>
                                  @if (intval($nivel) === 3)
                                  <br/>
                                  <div class="form-group">
                                    <div class="col-xs-12">
                                      <label for="">Observación</label>
                                      <input type="text" class="form-control" name="lab_orden_observacion" maxlength="255">
                                    </div>
                                  </div>
                                  @endif
                                </div>
                              </fieldset>
                            </div>
                          </fieldset>
                        </div>
                      </div>
                      <input type="hidden" id="doc_firmar_radiologia" value="" />
                      <input type="hidden" value="{{ $flag_radiologia }}" name="flag_radiologia" id="flag_radiologia">
                      <input type="hidden" value="{{ $flag_firmar_radiologia }}" name="flag_firmar_radiologia" id="flag_firmar_radiologia">
                      <div class="form-group">
                        <div class="col-md-12">
                      <fieldset id="fsExamenRad">
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>EXAMEN DE IMAGENOLOGIA</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            @if ( count($ordenImagenesAnulada) > 0 )
                              @foreach ($ordenes as $orden )
                                <div class="alert alert-danger" role="alert">
                                  <div class="col-lg-12">
                                    Examenes Invalidados por el servicio de imagenes
                                  </div>
                                  <div class="col-lg-6">
                                    <label class="control-label">Tecnologo o Profesional de Imagenes: </label>
                                    <span>{{ $orden->usuario }}</span>
                                  </div>
                                  <div class="col-lg-3">
                                      <label class="control-label">Orden: </label>
                                      <span>{{ $orden->id_radiologia }}</span>
                                  </div>
                                  <div class="col-lg-3">
                                      <label class="control-label">Fecha de Anulación: </label>
                                      <span>{{ $orden->fecha_orden_invalidado }}</span>
                                  </div>
                                  <div class="col-lg-12">
                                    <label class="control-label">Motivo: </label>
                                    <span>{{ $orden->obs_examen_invalidado }}</span>
                                  </div>
                                  <table  class="table table-bordered" cellspacing="0" width="100%" style="margin-top:5px;">
                                      <thead>
                                          <tr>
                                              <th>N°</th>
                                              <th class="col-lg-2">CÓDIGO</th>
                                              <th class="col-lg-8">DESCRIPCIÓN</th>
                                              <th class="col-lg-2">DIAGNOSTICO</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php 
                                              $i = 1;
                                              foreach( $orden->cpms as $row):
                                          ?>
                                          <tr>
                                              <td>{{$i}}</td>
                                              <td class="col-lg-2">{{$row->codigo_rad}}</td>
                                              <td class="col-lg-8">{{$row->descripcion_corta}}</td>
                                              <td class="col-lg-2">{{$row->diagnostico}}</td>
                                          </tr>
                                          <?php 
                                              $i++;
                                              endforeach;
                                          ?>
                                      </tbody>
                                  </table>
                                </div>  
                              @endforeach
                            @endif
                            <?php foreach($radiologia as $rowRadiologia):

                                if ($rowRadiologia->fecha_firma == "") {
                                    $flag_firmar_lab = 1;
                                }

                              $model_prestacion = new \App\Models\Prestaciones;
                              $procradiologia = $model_prestacion->GetPrestacionRadiologia($id_prestacion, $rowRadiologia->id,3,$id_evaluacion);
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
                                      <table class="display" cellspacing="0" width="100%">
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
                                        <td>{{$row->codigo}}</td>
                                        <td>{{$row->nombre}}</td>
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
                                        <td>{{$row->cantidad}}</td>
                                        <td>{{$row->cod_diag}}</td>
                                        <td><?php echo $row->radiologia_portatil?></td>
				                              </tr>
                                          <?php endforeach;?>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </fieldset>
                            <?php endforeach;?>
                              <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                <a onclick="agregarExamenRad()" id="btnNuevoExamenRad" flag="0" class="btn btn-success">Examen de Imagenología</a>
                              </div>
                            <div style="clear:both"></div>
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
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <fieldset id="fsInterconsulta">
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>INTERCONSULTA</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            @if (!empty($interconsultas))
                            <fieldset>
                              <div class="col-md-12">
                                <?php
                                  $is_estable = "";
                                  if (is_null($interconsultas[0]->is_estable)):
                                    $is_estable = "SIN INFO";
                                  else:
                                    if (intval($interconsultas[0]->is_estable) == 1):
                                      $is_estable = "ESTABLE";
                                    elseif (intval($interconsultas[0]->is_estable) == 0):
                                      $is_estable = "INESTABLE";
                                    endif;
                                  endif;
                                ?>
                                <label>CONDICIÓN DEL PACIENTE: </label> {{ $is_estable }}
                              </div>
                              <div class="col-md-12">
                                <?php foreach($interconsultas as $rowi):?>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="form-group"> 
                                    <div class="panel-body"> 
                                      <div class="col-md-12">
                                        <div class="col-md-2"> 
                                          <label>SERVICIO: </label>
                                        </div> 
                                        <div class="col-md-10"> 
                                          {{ $rowi->codigo_interconsulta }} - {{ $rowi->servicio }}
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="col-md-2"> 
                                          <label>MOTIVO:</label> 
                                        </div> 
                                        <div class="col-md-10"> 
                                          {!! nl2br(e($rowi->motivo_interconsulta)) !!}
                                        </div>
                                      </div>
                                      @if (!is_null($rowi->personal_coordinacion))
                                      <div class="col-md-12">
                                        <div class="col-md-2"> 
                                          <label>PERSONAL EN COORDINACIÓN:</label> 
                                        </div>
                                        <div class="col-md-10"> 
                                          {{ $rowi->personal_coordinacion }}
                                        </div>
                                      </div>
                                      @endif
                                      @if ($rowi->has_profesional_asignado)
                                      <div class="col-md-12">
                                        <div class="col-md-2">
                                          <label>MEDICO DE INTERCONSULTA</label><br/>
                                        </div>
                                        <div class="col-md-10"> 
                                          {{$rowi->medico_nombre_tr}} {{$rowi->medico_paterno_tr}} {{$rowi->medico_materno_tr}}
                                        </div>
                                      </div>
                                      @endif
                                    </div>
                                  </div>
                                </div>
                                <?php endforeach;?>
                              </div>
                            </fieldset>
                            @endif
                            <fieldset id="fsNuevaInterconsulta" style="display:none">
                              <div class="col-md-12">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-4 col-lg-2">
                                            <button tabindex="111" type="button" id="addInterconsulta2" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Interconsulta</button>
                                          </div>
                                        </div> <!-- .form-group -->
                                        <div class="form-group">
                                          <select class="form-control" id="cmbInterconsultaServiciosTemp" style="display:none">
                                            <option value="">Seleccionar Servicio</option>
                                            @foreach($establecimiento_servicios as $row)
                                              <?php
                                                $existe = false;
                                                foreach ($interconsultas as $ic) {
                                                  if ($row->id == $ic->id_servicio) {
                                                    $existe = true;
                                                    break;
                                                  }
                                                }
                                              ?>
                                              @if (!$existe)
                                                @if(intval($row->id) !== 137)
                                                  <option value="{{ $row->id }}" data-has_guardia="{{ $row->has_guardia }}">{{ $row->has_guardia }} - {{ $row->nombre }}</option>
                                                @endif
                                              @endif
                                            @endforeach
                                          </select>
                                        </div> <!-- .form-group -->
                                        <?php 
                                          $is_estable2 = "";
                                          if (!empty($interconsultas)):
                                            $is_estable2 = $interconsultas[0]->is_estable;
                                          endif
                                        ?>
                                        <div class="form-group">
                                          <div class="col-md-4 col-lg-2">
                                            <label for="">CONDICIÓN DEL PACIENTE:</label>
                                          </div>
                                          <div class="col-md-4 col-lg-2">
                                            @if (!empty($interconsultas))
                                            <?php
                                              $is_estable2 = $interconsultas[0]->is_estable;
                                            ?>
                                            <input type="hidden" name="is_estable" id="is_estable" value="{{ $is_estable2 }}">
                                            <select class="form-control" disabled>
                                              <option value="1" @if($is_estable2 == "1") selected @endif>ESTABLE</option>
                                              <option value="0" @if($is_estable2 == "0") selected @endif>INESTABLE</option>
                                            </select>
                                            @else
                                            <select class="form-control" name="is_estable" id="is_estable" required="" >
                                              <option value="1">ESTABLE</option>
                                              <option value="0">INESTABLE</option>
                                            </select>
                                            @endif
                                          </div>
                                        </div> <!-- .form-group -->
                                        <table class="display" id="tblInterconsulta" cellspacing="0" width="100%" style="margin-top:5px;">
                                          <thead>
                                              <tr>
                                              <th width="90%"></th>
                                              <th width="10%"></th>
                                            </tr>
                                          </thead>
                                          <tfoot>
                                          </tfoot>
                                          <tbody>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </fieldset>
                              <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                <a onclick="agregarInterconsulta()" id="btnNuevaInterconsulta" flag="0" class="btn btn-success">Examen de Interconsulta</a>
                              </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>  
                 
                  <!--  -->
                  @if (!empty($verificar_existe->origen_alta) && intval($verificar_existe->origen_alta) == 3)
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
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                           <?php $tipoDiagnostico = 'DEFINITIVO'; ?>
                          <tbody>
                              <?php foreach($diagnostico_egreso as $row): ?>
                                  <tr>
                                      <td><?php echo $row->codigo; ?></td>
                                      <td><?php echo $row->nombre; ?></td>
                                      <td><?php echo $tipoDiagnostico; ?></td>
                                  </tr>
                              <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <fieldset id="fsRecetaVale">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>PRODUCTOS MEDICOS DE EGRESO</b>
                          </div>
                        </div>
                        <div class="panel-body panel-body-receta">
                         <?php foreach($receta_alta as $rowReceta): ?>
                          <?php if($rowReceta->flag_origen == 2): ?>
                            <?php if ($rowReceta->fecha_firma == "") {
                                $flag_firmar = 1;
                              }
                              $model_productos_receta = new \App\Models\Producto_receta;
                              $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);
                            ?>
                          <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                            <?php if($rowReceta->estado=="2"){?>
                            <a data-toggle="modal" data-target="#squarespaceModal"
                              onclick="enviarReceta({{$rowReceta->id}})" class="btn btn-danger">
                              <i class="fa fa-xing"></i> Invalidar Receta
                            </a>
                            <?php }else{ ?>
                            <button class="btn btn-danger" disabled="disabled"><i class="fa fa-xing"></i> Invalidar
                              Receta</button>
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
                                      <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_expedicion)); ?></p>
                                    </div>
                                    <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                    <div class="col-md-4" style="padding:0px">
                                      <p><?php echo $rowReceta->farmacia; ?></p>
                                    </div>
                                  </div>
                                </div>
                                <table class="display" cellspacing="0" width="100%">
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
                                  <tbody style="font-weight: normal;">
                                  <?php foreach($medicamentos as $row):?>
                                  <tr>
                                    <td>{{$row->codigo}}</td>
                                    <td>{{$row->nombre}}
                                        <?php 
                                          if($row->codigo=='59075700'):
                                            echo "(".$row->cantidad_prescrita."L)";
                                          endif; 
                                        ?>
                                    </td>
                                    <td>{{$row->abreviatura}}</td>
                                    <td>{{$row->tiempo_periodo}}</td>
                                    <td>{{$row->posologia}}</td>
                                    <td>{{$row->via}}</td>
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
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <?php endif;?>
                            <?php endforeach;?>
                            <?php if ($prestacion->id_estado_reg==1): ?>
                              <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                <a onclick="agregarReceta2()" id="btnNuevoReceeta2" flag="0" class="btn btn-success">Agregar Receta</a>
                              </div>
                            <?php endif; ?>
                            <div style="clear:both"></div>
                            <fieldset id="fsNuevaRecetaVale2" style="display:none">
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-body">
                                  <div class="form-group">
                                    <div class="col-md-2">
                                      <label>FEC. EXPEDICIÓN (A)</label>
                                      <input type="text" class="form-control datepicker"
                                        value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_expedicion_alta"
                                        id="fecha_expedicion_alta" tabindex="2" autocomplete="off" />
                                    </div>
                                    <div class="col-md-5">
                                      <label>FARMACIA</label>
                                      <select class="form-control" name="id_farmacia_receta_egreso" id="id_farmacia_receta_egreso"
                                        style="width:100%" onchange="getProductoAlta()">
                                        <option value="">-Seleccionar-</option>
                                          <?php foreach($farmacias as $row):?>
                                        <option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
                                              @if($nivel==3) 
                                              <option value="21">Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                                              <option value="132">Farmacia de Preparados Farmacéutico No Estériles del Hospital Nacional PNP Luis N. Saenz</option>
                                              @endif
                                          <?php endforeach;?>
                                      </select>
                                    </div>
                                  </div>
                                  <br />
                                  <table>
                                    <tr>
                                      <td>
                                        <button type="button" onclick="AddProductoAlta()" class="btn btn-primary">Agregar Producto</button>
                                      </td>
                                    </tr>
                                  </table>
                                  <br />
                                  <div style="display: none">
                                    <select class="form-control" id="cmbMedicamentoTempAlta" tabindex="16" style="width: 500px">
                                      <option value="">Seleccionar Producto</option>
                                        @foreach($producto as $p)
                                          <option value="{{$p->id}}" ru="{{$p->ru}}">{{$p->codigo}} - {{$p->nombre}},
                                          {{$p->abreviatura}}</option>
                                        @endforeach
                                    </select>
                                  </div>
                                  <input type="hidden" value="0" name="hidden_rowIndex_alta" id="hidden_rowIndex_alta">
                                  <table id="exampleAlta" class="display" cellspacing="0" width="100%">
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
                    </fieldset>
                    <fieldset id="fsAlta">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>ALTA DE PACIENTE</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <input type="hidden" value="{{ $prestacion->id_medico }}" name="id_medico_egreso" id="id_medico_egreso">
                          <input type="hidden" value="{{ $id_prestacion }}" name="id_prestacionx" id="id_prestacionx">
                          <input type="hidden" value="{{ $verificar_existe->id }}" name="id_emergencia" id="id_emergencia">
                          <div style="clear:both"></div>

                          <fieldset id="divAlta2" >
                            <!-- <div class="form-group">  -->
                              <div class="col-sm-12">
                                <div class="form-group">
                                  <div class="col-md-3">
                                    <label>FECHA INGRESO</label>
                                    <p><?php echo date("d-m-Y", strtotime($verificar_existe->fecha_registro_ingreso)); ?></p>
                                  </div>
                                  <div class="col-md-3">
                                    <label>HORA INGRESO</label>
                                    <p><?php echo date("H:i:s", strtotime($verificar_existe->hora_registro_ingreso)); ?></p>
                                  </div>
                                  <div class="col-md-3">
                                    <label>FECHA EGRESO MEDICO</label>
                                    <p><?php echo date("d-m-Y", strtotime($verificar_existe->fecha_alta_medica)); ?></p>
                                  </div>
                                  <div class="col-md-3">
                                    <label>HORA EGRESO MEDICO</label>
                                    <p><?php echo date("H:i:s", strtotime($verificar_existe->hora_alta_medica)); ?></p>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-12">
                                <hr>
                                <label>DETALLES DE LA ALTA</label><br>
                                <div class="form-group">
                                  <div class="col-md-3">
                                    <label>TIPO EGRESO</label>
                                    <?php
                                      $tipo_egreso = "";
                                      switch ($verificar_existe->id_tipo_egres_med):
                                        case "1":
                                          $tipo_egreso = "ALTA";
                                          break;
                                        case "2":
                                          $tipo_egreso = "HOSPITALIZACION";
                                          break;
                                        case "3":
                                          $tipo_egreso = "TRANSFERIDO";
                                          break;
                                        case "4":
                                          $tipo_egreso = "FALLECIDO";
                                          break;
                                        case "6":
                                          $tipo_egreso = "FUGA";
                                          break;
                                        case "7":
                                          $tipo_egreso = "RETIRO VOLUNTARIO";
                                          break;
                                      endswitch;
                                    ?>
                                    <p>{{ $tipo_egreso }}</p>
                                  </div>
                                  <div class="col-md-3">
                                    <label>CONDICION ALTA</label>
                                    <?php 
                                      $condicion_egreso = "";
                                      switch ($verificar_existe->id_condi_egres_med):
                                        case "1":
                                          $condicion_egreso = "MEJORADO";
                                          break;
                                        case "2":
                                          $condicion_egreso = "CURADO";
                                          break;
                                        case "3":
                                          $condicion_egreso = "TRASLADO";
                                          break;
                                        case "4":
                                          $condicion_egreso = "RETIRO VOLUNTARIO";
                                          break;
                                        case "5":
                                          $condicion_egreso = "FUGADO";
                                          break;
                                        case "6":
                                          $condicion_egreso = "FALLECIDO";
                                          break;
                                      endswitch;
                                    ?>
                                    <p>{{ $condicion_egreso }}</p>
                                  </div>

                                  @if (intval($verificar_existe->id_tipo_egres_med) === 2)
                                    <div class="col-md-3">
                                      <label class="control-label">ESPECIALIDAD A CARGO</label>
                                      <p>{{ $upss_alta->descripcion_corta_upss }}</p>
                                    </div>
                                  @endif

                                </div>
                              </div>
                              <div class="col-sm-12">
                                <hr>
                                <!-- <div class="col-sm-8" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> -->
                                  <?php 
                                    if($verificar_existe->id_tipo_egres_med=="1"){
                                    
                                    }else if($verificar_existe->id_tipo_egres_med=="2"){
                                      if ($verificar_existe->id_pabellon_ubicacion):
                                        ?>
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label class="control-label">PABELLON</label>
                                            <p>{{ $dato_traslado->edificio_ubicacion }}</p>
                                          </div>

                                          <div class="col-md-3">
                                            <label class="control-label">&nbsp;</label>
                                            <p>{{ $pabellon->nombre }}</p>
                                            {{-- <p>{{ $dato_traslado->upss_codigo }} - {{ $dato_traslado->upss_descripcion }}</p> --}}
                                          </div>

                                          {{-- <div class="col-md-2">
                                            <label class="control-label">CAMA:</label>
                                            <p>{{ $dato_traslado->cama_ubicacion }}</p>
                                          </div>

                                          <div class="col-md-3">
                                            <label class="control-label">COORDINACIÓN:</label>
                                            <p>{{ $dato_traslado->personal_coordinacion }}</p>
                                          </div> --}}
                                        </div>
                                      <?php
                                      endif;

                                    }else if($verificar_existe->id_tipo_egres_med=="3"){
                                      
                                      echo "<b>SE HA TRANSFERIDO  A LA SIGUIENTE IPRESS:</b> ";
                                      echo $establecimiento_traslado->nombre ."<br>". "<b>LA OBSERVACION DEL TRASLADO:</b> ";
                                      echo $verificar_existe->obs_traslado ."<br>". "<b>ESPECIALIDAD DE TRASLADO:</b> ";
                                      echo $verificar_existe->especialida_destino ."<br>"."<b>DETALLE: </b>";
                                      echo $verificar_existe->especialidad_destino_otro ."<br>". "<b>CONDICION DE TRASLADO: </b>";
                                      echo $verificar_existe->condicion_traslado  ."<br>".  "<b>ACOMPAÑANTE: </b>";
                                      echo $verificar_existe->personal_acompañante;
                                    }else if($verificar_existe->id_tipo_egres_med=="4"){
                                      echo "SE  REALIZO NECROPSIA " ;
                                      echo $verificar_existe->necropsia;
                                      echo ", LA CAUSAS DE LA MUERTE FUE  ";
                                      echo $verificar_existe->causas_muerte;
                                    }else if($verificar_existe->id_tipo_egres_med=="7"){
                                      echo "OBSERVACION DEL RETIRO VOLUNTARIO: " ;
                                      echo $verificar_existe->detalle_alta_admin;
                                    }else if($verificar_existe->id_tipo_egres_med=="6"){
                                      echo "DETALLES DE LA FUGA: " ;
                                      echo $verificar_existe->detalle_alta_admin;
                                    }
                                  ?>
                                <!-- </div> -->
                              </div>
                            <!-- </div> -->
                            
                          </fieldset>
                        </div>
                      </div>
                    </fieldset>
                  @endif
                  <!--  -->
              
              <div id="divGuardar" style="display:none">
                <input class="btn btn-primary input-sm pull-right" value="GUARDAR" name="crea" type="button"
                  form="prestacionescrea" id="btnPrestacion" />&nbsp;&nbsp;
              </div>
              <div id="divFirmar">
                @if($permiso->crear==1)
                <input name="mov[]" id="mov[]" value="<?php echo $id_evaluacion?>" type="hidden" />
                <input type="hidden" value="2" name="proviene_formulario" id="proviene_formulario">
                <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacion" id="id_prestacion">
                <input type="hidden" value="<?php echo $evaluacion->id_emergencia?>" name="id_paciente" id="id_paciente">
                <input type="hidden" value="2" name="id_tipo_atencion" id="id_tipo_atencion">
                <?php $origen_alta = (!empty($verificar_existe->origen_alta)) ? $verificar_existe->origen_alta : 0; ?>
                <input type="hidden" name="has_alta_medica" id="has_alta_medica" value="{{ $origen_alta }}">
                
                <?php if ($flag_firmar == 1): ?>
                  <?php if(Auth::user()->status_token==1): ?>
                    <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button"
                      onclick="firmarsintokens()" style="margin-left:20px" />
                  <?php else: ?>
                        <?php if(Auth::user()->tipo_registro==3): ?>
                              <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button" onclick="firmarsintokens()" style="margin-left:20px" />
                        <?php else: ?>
                            <!-- Con token -->
                            @if ($is_habilitado_firma_electronica)
                              <input class="btn btn-info input-sm pull-right" value="FIRMAR" type="button" onclick="firmarsintokens()" style="margin-left:20px" />
                            @else
                              <input class="btn btn-info input-sm pull-right" value="FIRMAR" type="button" id="btnfirmar" style="margin-left:20px" />
                            @endif
                        <?php endif; ?>
                  <?php endif; ?>
                <?php endif; ?>
                  <a class="btn btn-primary btn-sm hidden-print pull-right" href="/ver_atenciones_evaluacion/<?php echo $evaluacion->id_emergencia ?>/3/<?php echo $evaluacion->id_tipo_atencion  ?>"> <span
                      class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
                @else
                  <a class="btn btn-primary btn-sm hidden-print pull-right" href="/ver_atenciones_evaluacion/<?php echo $evaluacion->id_emergencia ?>/3/<?php echo $evaluacion->id_tipo_atencion  ?>"> <span
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
<input type="hidden" id="flagServicioFirma" value="" />
<input type="hidden" id="id_radiologia_actualizar" value="" />
<input type="hidden" id="id_laboratorio_actualizar" value="" />
<input type="hidden" id="argumentos" value="" />
<input type="hidden" id="doc_firmar" value="" />
<input type="hidden" id="opt_firma" value="" />
<input type="hidden" value="<?php echo $flag_firmar?>" name="flag_firmar" id="flag_firmar">
<div id="addComponent"></div>

<div class="modal fade" id="squarespaceModal2" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span
            class="sr-only">Close</span></button>
        <h3 class="modal-title" id="lineModalLabel">Invalidar Examen de Laboratorio</h3>
      </div>
      <div class="modal-body">
        <!-- content goes here -->
        <form class="" method="post" id="formInvalidaExamenLab">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de este examen de laboratorio</label>
            <input type="text" class="form-control" id="motivo_laboratorio" name="motivo_laboratorio" placeholder="" required="">
            <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia"
              value="{{Auth::user()->id_farmacia}}">
            <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id_prestacion" id="id_prestacion" value="<?php echo $id_prestacion?>">
            <input type="hidden" class="form-control" id="id_evaluacion" name="id_evaluacion" value="{{$id_evaluacion}}">
            <input type="hidden" class="form-control" id="id_laboratorio" name="id_laboratorio"
              value="">
          </div>
          <div class="form-group">
            <input id="btnInvalidarLaboratorio" class="btn btn-danger" value="Invalidar" />
          </div>
        </form>

      </div>

    </div>
  </div>
</div>

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
             <input type="hidden" class="form-control" id="id_evaluacion" name="id_evaluacion" value="{{$id_evaluacion}}">
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

<div class="modal fade" id="squarespaceModal3" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span
            class="sr-only">Close</span></button>
        <h3 class="modal-title" id="lineModalLabel">Invalidar Examen Imagenologia</h3>
      </div>
      <div class="modal-body">
        <!-- content goes here -->
        <form class="" method="post" id="formInvalidaExamenRad">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de este examen de imagenologia</label>
            <input type="text" class="form-control" id="motivo_imagenologia" name="motivo_imagenologia" placeholder="" required="">
            <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia"
              value="{{Auth::user()->id_farmacia}}">
            <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
            <input type="hidden" class="form-control" id="id_evaluacion" name="id_evaluacion" value="{{$id_evaluacion}}">
            <input type="hidden" name="id_prestacion" id="id_prestacion" value="<?php echo $id_prestacion?>">
            <input type="hidden" class="form-control" id="id_radiologia" name="id_radiologia"
              value="">
          </div>
          <div class="form-group">
            <input id="btnInvalidarRadiologia" class="btn btn-danger" type="submit" value="Invalidar">
          </div>
        </form>

      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="showDetalleExamenResult" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-examen" style="background-color: #ffffff">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #494949">&times;</span></button>
                <h4 class="modal-title" style="background-color: #ffffff"><small style="color: #494949">Resultados Laboratorio</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="contenido-table-result">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
  </div>
  
  {{-- REGISTRO TIPO DE MUESTRA DE CATEGORIA DE MICROBIOLOGÍA PARA LABORATORIO --}}
<div class="modal fade" id="modalTipoMuestrabMicrobiologia" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header custom-header-cambiar-estado">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="font-size: 2rem">Escribe el tipo de muestra para los examenes de microbiología<span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="solicitud_data_id" id="solicitud_data_id">
                <input type="text" name="solicitud_descripcion_tipo_muestra" id="solicitud_descripcion_tipo_muestra" class="form-control">
            </div>
            <div class="modal-footer">
                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarRegistroTipoMuestraMicrobiologia" class="btn btn-success">Guardar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- EDITAR TIPO DE MUESTRA DE CATEGORIA DE MICROBIOLOGÍA PARA LABORATORIO --}}
<div class="modal fade" id="modalEditTipoMuestrabMicrobiologia" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header custom-header-cambiar-estado-edit">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="font-size: 2rem">Edite el tipo de muestra para los examenes de microbiología<span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="solicitud_edit_data_id" id="solicitud_edit_data_id">
                <input type="text" name="solicitud_edit_descripcion_tipo_muestra" id="solicitud_edit_descripcion_tipo_muestra" class="form-control">
            </div>
            <div class="modal-footer">
                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarEditTipoMuestraMicrobiologia" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Hemocultivo --}}
@include('frontend.laboratorios.modal_solicitud_hemocultivo')

{{-- MODAL SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS --}}
@include('frontend.laboratorios.modal_solicitud_citoquimicos_liquidos_biologicos')
{{-- MODAL IMAGENOLOGÍA --}}
<div class="modal fade" id="detalleModal" tabindex="-1">
  <div class="modal-dialog" style="width:95%; max-width:1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center" style="font-size:20px;"><strong>DATOS CLÍNICOS RELACIONADOS A LA SOLICITUD DEL EXAMEN DE IMAGENOLOGÍA</strong></h4>
      </div>
      
      <div class="modal-body" style="background:#f5f5f5; font-size:12px;">
        <div class="panel" style="border:1px solid #9fbf9f;">
          <div class="panel-heading text-center" style="background:#cfe3cf;">
            <strong>ANAMNESIS</strong>
          </div>
          <div class="panel-body">
            <div class="row" style="margin-top:10px;">
              <div class="col-md-12">
                <label><b>ANAMNESIS</b>&nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                <textarea id="rad_anamnesis" name="rad_anamnesis" 
                  class="form-control" rows="3" 
                  placeholder="Ingrese anamnesis..."></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="panel" style="border:1px solid #9fbf9f; margin-top:10px;">
          <div class="panel-heading text-center" style="background:#cfe3cf;">
            <strong>EXAMENES AUXILIARES</strong>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <label><b>EXAMEN FÍSICO RELACIONADO A LA SOLICITUD DEL EXAMEN</b>&nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                <textarea id="rad_examen_fisico" name="rad_examen_fisico" 
                  class="form-control" rows="4" 
                  placeholder="Ingrese el examen físico..."></textarea>
              </div>
              <div class="col-md-6">
                <label><b>EXAMENES AUXILIARES</b>&nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                <textarea id="rad_examen_auxiliar" name="rad_examen_auxiliar" 
                  class="form-control" rows="4" 
                  placeholder="Ingrese el examen auxiliar..."></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-dismiss="modal">
          <i class="fa fa-times"></i> <strong>CERRAR</strong>
        </button>
        <button type="button" class="btn btn-success" id="btnConfirmarImagenologia">
          <i class="fa fa-save"></i> <strong>CONFIRMAR</strong>
        </button>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection
