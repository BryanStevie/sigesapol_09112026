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
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
</style>

<script>
  //<![CDATA[
    var obtenerDiagnosticoGeneroUrl = "{{ route('obtener_diagnostico_genero_ver', ['buscar' => '_buscar_', 'genero' => '_genero_']) }}";
  var id_prestacion = "{{ $id_prestacion }}";
 var listIdDiagnosticoObjetos =[];
  var codigos = [];
  var codigosprocedimientos = [];

  var procedimientosSeleccionados =[];
  var listIdDiagnostico =[];

 var filasmedicamentos = 0;


  $(document).ready(function() {
    listIdDiagnosticoObjetos = {!! json_encode($diagnostico) !!};
  console.log(listIdDiagnosticoObjetos);
 

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
  let flagServicioFirma = $("#flagServicioFirma").val();
  if (flagServicioFirma == "1") {
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
  if (flagServicioFirma == "2") {
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
  }
  
}

async function MiFuncionOkWeb() {
  var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  let flagServicioFirma = $("#flagServicioFirma").val();
  if (flagServicioFirma == "1") {
    let id_resol = $('#id_prestacion').val();  
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
        id_tipo: "interconsulta",
        id_resol: id_resol,
      }, function(data, status) {
        $.post("{{route('actualiza_prestacion')}}", {
        _token: CSRF_TOKEN,
        id_tipo: "referencia",
        id_resol: id_resol,
      }, function(data, status) {
        $("#flagServicioFirma").val("");
        //location.reload();
  location.href = '/cq_lista_pendiente';        
  });
      });
      });
    });
  }
  if (flagServicioFirma == "2") {
    let id_resol = $('#id_prestacion').val();
    $.post("{{route('actualiza_prestacion')}}", {
      _token: CSRF_TOKEN,
      id_tipo: "laboratorio",
      id_resol: id_resol,
    }, function(data, status) {
      $("#flagServicioFirma").val("");
      location.reload();
    });
  }
  if (flagServicioFirma == "3") {
    $.post("{{route('actualiza_prestacion')}}", {
      _token: CSRF_TOKEN,
      id_tipo: "radiologia",
      id_resol: id_resol,
    }, function(data, status) {
      $("#flagServicioFirma").val("");
      location.reload();
    });
  }
}

function MiFuncionCancel() {
  alert("El proceso de firma digital fue cancelado.");
}
//]]>

function addNewArray(id_diagnostico) {
  if(  listIdDiagnosticoObjetos.findIndex( e  => e.id == id_diagnostico) ==-1 ){
  listIdDiagnostico.push(id_diagnostico);// solo es para validar si existen datos

  if (listIdDiagnostico.length > 0) {

    let codigo = JSON.stringify(id_diagnostico);
    // Realizar la peticiÃ³n AJAX usando el cÃ³digo id_diagnostico
    $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": "/obtenerdatosDiagnosticos",
                "data":{
                  codigo : codigo
                       },
                "success": function (result) {
                  listIdDiagnosticoObjetos.push(result.objeto);   
                      console.log(JSON.stringify(listIdDiagnosticoObjetos));
                      updateSelectElements();
                },
                "error": function (msg, textStatus, errorThrown) {
                    //location.href="login";
                }
            });
  }
  }

 
}
function eliminarDiagnosticosarr(codigon){
  let codigo2 = parseInt(codigon.trim(), 10);

let listIdDiagnosticoObjetos2 =[];
  for (let index = 0; index < listIdDiagnosticoObjetos.length; index++) {
    const element = listIdDiagnosticoObjetos[index];
    if(element.id_diagnostico !== codigo2){
      listIdDiagnosticoObjetos2.push(element)
    }
    
  }
  listIdDiagnosticoObjetos=[];
  listIdDiagnosticoObjetos =listIdDiagnosticoObjetos.concat(listIdDiagnosticoObjetos2);



  procedimientosSeleccionados= procedimientosSeleccionados.map((item) => {
    if (item.codigo === codigo2) {
      return { ...item, id: "" };
    } else {
      return item;
    }
  });

  console.log("la lista con eliminacion de los diagnosticos x productos es : " + JSON.stringify(procedimientosSeleccionados))


   
console.log("la lista con eliminacion es " + JSON.stringify(listIdDiagnosticoObjetos));
updateSelectElements();   

}
$(document).ready(function() {
   ///  activacion de editar diagnostico
  $("#btnEditDiagnostico").click(function() {
    $(".input-diagnostico").removeAttr('disabled');
    $(".input-tipo-diagnostico").removeAttr('disabled');
    $("#btnSaveDiagnostico").removeAttr('disabled');
    
  
  });
  $("#btnSaveDiagnostico").click(function() {
    event.preventDefault();
    var _token = $("#token_tag").val();
    var url = '{{route("edit.receta.diagnositco")}}';
    var receta_diagnositico_id = $("input[name='receta_diagnositico_id']").val()
    var ids_diagnosticos = $("select[name^='id_diagnisticos']").val()
    var ids_tipo_diagnosticos = $("select[name^='id_tipo_diagnosticos']").val()
    // var _data = {'id': '1'};

    
    $.ajax({
      url: url,
      method: 'POST',
      headers: {'X-CSRF-TOKEN': _token},
      data: {
        "receta_diagnositico_id[]": receta_diagnositico_id,
        "ids_diagnosticos[]": ids_diagnosticos,
        "ids_tipo_diagnosticos[]": ids_tipo_diagnosticos
      }
    }).done(function(res){
      alert(res.mensaje)
    })
  });

  $("#btnSaveProcedimiento").click(function() {
    event.preventDefault();
    var forme = $('#formSaveProcedimiento').serialize();
    console.log(forme);
    var _token = `{{ csrf_token() }}`;
    var url = '{{route("edit.receta.procedimiento")}}';
    var prestacion_procedimiento_id = $("input[name='prestacion_procedimiento_id']").val()
    var id_procedimientos = $("select[name^='id_procedimientos']").serialize()
    var cantidad_procedimiento = $("input[name^='cantidad_procedimientos']").val()
    console.log(id_procedimientos);
    
    $.ajax({
      url: url,
      method: 'POST',
      headers: {'X-CSRF-TOKEN': _token},
      data: forme
    }).done(function(res){
      alert(res.mensaje)
    })
  });


  $("#btnEditProcedimiento").click(function() {
    $(".input-procedimiento").removeAttr('disabled');
    $("#btnSaveProcedimiento").removeAttr('disabled');
  });


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
  function registrarCitaPorMedico(){
    const id_prestacion = <?php echo $id_prestacion?>;
    const registrar_cita_por_medico = $('#registrar_cita_por_medico').val();
      if (registrar_cita_por_medico == 1) {
        location.href = `/formulario_cita_por_medico/${id_prestacion}`;
      }else {
        console.log('No Puedes Registrar Cita Como Médico');
      }
  }

  function validaRegistrarCitaPorMedico() {
    const id_prestacion = <?php echo $id_prestacion?>;
    $.ajax({
      type: "GET",
      url: "/obtener_prestacion/"+ id_prestacion,
      success: function (response) {
        if (response == 'true') {
          //console.log('Si esta firmado');
          registrarCitaPorMedico();
        }else {
          console.log('No esta firmado');
        }
      }
    });
  }

  $(document).on('click', '#btnfirmar', function(e) {
    e.preventDefault();
    firmar();
    validaRegistrarCitaPorMedico();
    //registrarCitaPorMedico();
  })

  
  $(document).on('click', '#btnFinalizarLaboratorio', function(e) {
    e.preventDefault();
    firmarLaboratorioSinTokens();
  })
  
  const firmarLaboratorioSinTokens = () => {
    let  batchSignatureProcessPoller = setInterval(function() {
      let id_prestacion = $("#id_prestacion").val();
      let proviene_formulario = $("#proviene_formulario").val();
      let id_tipo_atencion = $('#id_tipo_atencion').val();
      let postData =  { 
                        id_prestacion : id_prestacion, 
                        proviene_formulario : proviene_formulario,
                        id_tipo_atencion : id_tipo_atencion
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

  $(document).on('click', '#btnFirmaLaboratorio', function(e) {
    e.preventDefault();
    firmarLaboratorio();
  })

  const firmarLaboratorio = () => {
    let id_prestacion = $("#id_prestacion").val();
    let proviene_formulario = $("#proviene_formulario").val();
    let postData = { id_prestacion : id_prestacion, proviene_formulario : proviene_formulario };
  $.ajax({
      url: "<?php echo url()?>/genera_csv_laboratorio",
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

  $(document).on('click', '#btnFirmaRadiologia', function(e) {
    e.preventDefault();
    firmarRadiologia();
  })

  const firmarRadiologia = () => {
    let id_prestacion = $("#id_prestacion").val();
    let postData = { id_prestacion : id_prestacion };
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

  $(document).on('click', '#btnFinalizarRadiologia', function(e) {
    e.preventDefault();
    firmarRadiologiaSinTokens();
  })
  
  const firmarRadiologiaSinTokens = () => {
    //let  batchSignatureProcessPoller = setInterval(function() {
      let id_prestacion = $("#id_prestacion").val();
      let proviene_formulario = $("#proviene_formulario").val();
      let id_tipo_atencion = $('#id_tipo_atencion').val();
      let postData =  { 
                        id_prestacion : id_prestacion, 
                        proviene_formulario : proviene_formulario,
                        id_tipo_atencion : id_tipo_atencion
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
            clearInterval(batchSignatureProcessPoller);
          }
        }
      });
    //}, 3000);
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
    AddProductoAQ();
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

  // $('#addExamenLab').on('click', function() {
  //   var addExamenLab = $('#addExamenLab').attr("aria-pressed");
  //   $("#fsExamenLab").hide();
  //   if (addExamenLab == "false") {
  //     $("#fsExamenLab").show();
  //   }
  // });

  // $('#addExamenRad').on('click', function() {
  //   var addExamenRad = $('#addExamenRad').attr("aria-pressed");
  //   $("#fsExamenRad").hide();
  //   if (addExamenRad == "false") {
  //     $("#fsExamenRad").show();
  //   }
  // });

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

  $('#addreferenciacontra').on('click', function () {
    var addreferenciacontra = $('#addreferenciacontra').attr("aria-pressed");
    $("#fsReferenciacontra").hide();
    if(addreferenciacontra == "false"){
      $("#fsReferenciacontra").show();
    }
  });

  // $('#addExamenLab2').on('click', function() {
  //   AddExamenLab();
  // });

  // $('#addExamenRad2').on('click', function() {
  //   AddExamenRad();
  // });

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

  $("#id_farmacia_receta").val($("#id_farmacia_receta").val()).trigger("change");
  //id_farmacia_receta = $('#id_farmacia_receta2').val();


  //AddDiagnostico();
  //AddProcedimiento();
  // AddExamenLab();
  // AddExamenRad();

});


function guardarPrestacion() {

  var msg = "";
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
  if (msg != "") {
    bootbox.alert(msg);
    return false;
  } else {
    $("#frmBloque").find('input[disabled], select[disabled]').prop('disabled', false);
    //fn_save_prestacion();
    $("#frmBloque").submit();
  }
}

function fn_save_prestacion() {

  //var fecha_atencion_original = $('#fecha_atencion').val();

  $.ajax({
    url: "../registrar_prestacion",
    type: "POST",
    //data : $("#frmPrestacion").serialize()+"&id_medico="+id_medico+"&fecha_cita="+fecha_cita,
    data: $("#frmPrestacion").serialize(),
    success: function(result) {
      //modalTurnos();
      //modalHistorial();

    }
  });
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

// function AddExamenLab() {
//   var newRow = "";
//   var ind = $('#tblExamenLab tbody tr').length;

//   if (ind > 0) {
//     $('.id_laboratorio').each(function() {
//       var ind_tmp = $(this).data("id");
//       if (ind_tmp => ind) {
//         ind = Number(ind_tmp) + 1;
//       }
//     });

//     fila_medicamentos = $('#tblExamenLab tbody').children('tr');
//     rowIndex = $('#hidden_rowIndex').val();
//     var idval = ind - 1;
//     fila_medicamento = fila_medicamentos[idval];

//   }

//   newRow +='<tr>';
//   newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
//   newRow +='<input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id='+ind+'></td>';
//   newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
//   newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_lab_cantidad typeahead tt-query" style="margin:0 5px;width:95%" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1"></td>';
//   newRow += '<td><select style="margin:0 10px;width:95%" class="form-control select-diagnostico"  id="miSelectLab' + ind + '" name="miSelectLab[]"></select></td>';
//   newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
//   newRow +='</tr>';
//   $('#tblExamenLab tbody').append(newRow);

//   var nivel = $('#id_nivel').val();
//   genero = getSexoGenero();
//   var id_tipo_atencion = $('#id_tipo_atencion').val();
//   //tipo=2
//   const selectElementLab = document.getElementById('miSelectLab' + ind);
  
//   listIdDiagnosticoObjetos.forEach((item) => {
//     let option = document.createElement('option');
//     option.value = item.id_diagnostico;
//     option.textContent = item.nombre;
//     selectElementLab.appendChild(option);
//   });

//   $("#nombre_examen_lab" + ind).autocomplete({
//     minLength: 1,
//     delay: 400,
//     source: function(request, response) {
//       $.ajax({
//         //url: '../../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 2,
//         url: '../../obtener_laboratorio_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion,
//         //url: '../obtener_procedimiento/' + request.term,
//         dataType: "json",
//         success: function(data) {
//           let listaLaboratorioSelecionado = $(".id_laboratorio").map(function(){return parseInt($(this).val());}).get();
//           let filtro = data.filter((element)=>!listaLaboratorioSelecionado.includes(element.id));
//           response(filtro);
//         }
//       })

//     },
//     select: function(e, ui) {
//       var id_examen_lab = ui.item.id;
//       $("#id_examen_lab" + ind).val(id_examen_lab);
//       $("#nombre_examen_lab" + ind).prop("readonly", true);
//     }
//   });

//   $("#nombre_examen_lab" + ind).on('blur', function() {
//     var id_examen_lab = $("#id_examen_lab" + ind).val();
//     if (id_examen_lab == "") {
//       bootbox.alert("Falta ingresar el examen laboratorio en esta fila");
//       $("#examen_lab_cantidad" + ind).val("");
//       return false;
//     }
//   });

//   $("#examen_lab_cantidad" + ind).on('blur', function() {
//     var id_examen_lab = $("#id_examen_lab" + ind).val();
//     if (id_examen_lab == "") {
//       bootbox.alert("Falta ingresar la cantidad en este examen laboratorio");
//       $("#examen_lab_cantidad" + ind).val("");
//       return false;
//     }
//   });

// }

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
  
  var item_tipo_diagnostico = '';
  item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
  item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
  item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
  item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';
  var tabindex = 112;
  newRow +='<tr>';
  newRow +='<td><div class="panel panel-info panel-bordered panel-sm"><div class="form-group"> <div class="panel-body"> <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>SERVICIO</label></div> <div class="col-md-4" style="margin-left:0px;padding-left:0px"> <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el servicio" name="nombre_servicio_interconsulta[]" tabindex="' + tabindex + '" required="" id="nombre_servicio_interconsulta'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" /> </div> <div class="col-md-1"> <label>Personal de Salud</label> </div> <div class="col-md-6"><select tabindex="72" class="form-control" name="personal_salud[]" id="personal_salud'+ind+'" style="width:100%"><option value="">-Seleccionar-</option></select> </div> <div class="col-md-12"><br/> <label>RESUMEN</label> <textarea class="form-control" cols="5" id="motivo_interconsulta'+ind+'" name="motivo_interconsulta[]" placeholder="Relato m&eacute;dico o caracteristicas de la interconsulta"></textarea></div></div></div></div></td>';
  newRow +='<td><button tabindex="114" type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_servicio_interconsulta" id="id_servicio_interconsulta'+ind+'" name="id_servicio_interconsulta[]" value="" data-id='+ind+'></td>';
  newRow +='</tr>';
  
  var id_ipress     = $('#id_establecimiento').val();
  $('#tblInterconsulta tbody').append(newRow);

  $("#nombre_servicio_interconsulta"+ind).autocomplete({
    minLength: 1,
    delay : 400,
    source: function(request, response) {
      $.ajax({
//        url: '../obtener_procedimiento/' + request.term,
        url: '../../obtener_consultorio_interconsulta/' + request.term +'/'+id_ipress,

        dataType: "json",
        success: function(data)  {
          let listaServicioSelecionado = $(".id_servicio_interconsulta").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaServicioSelecionado.includes(element.id));
          response(filtro);
        }         
      })

    },select:  function(e, ui) {
      var id_servicio_interconsulta = ui.item.id;
      $("#id_servicio_interconsulta"+ind).val(id_servicio_interconsulta);
      $("#nombre_servicio_interconsulta"+ind).prop("readonly",false);
      getMedico(id_servicio_interconsulta,ind);
    }
  });

  $("#nombre_servicio_interconsulta"+ind).on('blur', function() {
    var id_servicio_interconsulta = $("#id_servicio_interconsulta"+ind).val();
    if(id_servicio_interconsulta == ""){
      bootbox.alert("Falta ingresar el servicio en esta interconsulta");
      $("#nombre_servicio_interconsulta"+ind).val("");
      return false;
    }
  }); 
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
      url: '/obtener_medico_por_idipress_idconsultorio_turno/' + id_ipress+ '/' + id_consultorio+'/'+turno + '/' + fecha_admision,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
        });                 
        $('#personal_salud'+i).html(newOption);
        //$("#personal_salud").select2({max_selected_options: 4});      
      }
    });
        
    }
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

  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de Radiografia" name="nombre_examen_rad[]" tabindex="8" required="" id="nombre_examen_rad' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_radiologia" id="id_examen_rad' + ind +'" name="id_examen_rad[]" value="" data-id='+ind+'></td>';
  newRow +='<td><select class="form-control id_comparativo" id="cmbComparativo'+ind+'" name="id_comparativo[]" style="margin:0 20px;width:150px">'+item_comparativo+'</select></td>';
  newRow +='<td><select class="form-control id_lado" id="cmbLado'+ind+'" name="id_lado[]" style="margin:0 20px;width:150px">'+item_lado+'</select></td>';
  newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_rad_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="examen_rad_observacion' +ind + '" name="examen_rad_observacion[]"></td>';
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_rad_cantidad typeahead tt-query" style="margin:0 5px;width:50%" id="examen_rad_cantidad' +ind + '" name="examen_rad_cantidad[]" value="1"></td>';
  newRow += '<td><select class="form-control select-diagnostico"  id="miSelectRad' + ind + '" name="miSelectRad[]" style="margin:0 10px;width:95%"></select></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblExamenRad tbody').append(newRow);

  var nivel = $('#id_nivel').val();
  genero = getSexoGenero();

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
        url: '../../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 3,
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
      $("#id_examen_rad" + ind).val(id_examen_rad);
      $("#nombre_examen_rad" + ind).prop("readonly", true);
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

  newRow += '<tr>';
  newRow +=
    '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico' +
    ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow += '<input type="hidden" class="form-control typeahead tt-query id_diagnostico" id="id_diagnostico' + ind +
    '" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
  newRow +=
    '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';
  $('#tblDiagnostico tbody').append(newRow);
  genero = getSexoGenero();

  $("#nombre_diagnostico" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '../obtener_diagnostico_genero/' + request.term + '/' + genero,
        dataType: "json",
        success: function(data) {
          let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
          response(filtro);
        }
      })

    },
    select: function(e, ui) {
      var id_diagnostico = ui.item.id;
      $("#id_diagnostico" + ind).val(id_diagnostico);
      $("#nombre_diagnostico" + ind).prop("readonly", true);
    }
  });

  $("#nombre_diagnostico" + ind).on('blur', function() {
    var id_diagnostico = $("#id_diagnostico" + ind).val();
    if (id_diagnostico == "") {
      bootbox.alert("Falta ingresar el diagnostico en esta fila");
      $("#nombre_diagnostico" + ind).val("");
      return false;
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


function AddProductoAQ() {
  var tipo_receta = $('#tipo_receta').val();
  var id_consultorio = $('#id_consultorio').val();
  var id_farmacia = $('#id_farmacia_receta').val();
  if (tipo_receta == "") {
    bootbox.alert("Seleccione el tipo atenciÃ³n");
    return false;
  }
  if (id_consultorio == "") {
    bootbox.alert("Seleccione un servicio");
    return false;
  }
  var newRow = "";
  var ind = $('#example tbody tr').length;
  var tabindex = 11;
  var nuevalperiodo = "";
  if (ind > 0) {
    $('.cmbMedicamento').each(function() {
      var ind_tmp = $(this).attr("ind");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
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
  newRow += '<td><input type="text" name="posologia[]" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
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
  $("#cmbMedicamento" + ind).select2({max_selected_options: 4}).focus();

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
      AddProductoAQ();
      <?php //else:?>
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      <?php //endif;?>
    }
  });

  $("#add_producto"+ind).click(function(e){
      AddProductoAQ();
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


  $("#cmbMedicamento" + ind).on("change", function(e) {
    var flagx = 0;
    cmb = $(this);
    id_producto = $("#cmbMedicamento" + ind).val();
    id_user = <?php echo Auth::user()->id ?>;

    $('.cmbMedicamento').each(function() {
      var ind_tmp = $(this).val();
      if ($(this).val() == id_producto) flagx++;
    });

    if (flagx > 1) {
      bootbox.alert("El producto farmaceutico ya ha sido ingresado");
      $("#cmbMedicamento" + ind).val("").trigger("change");
      return false;
    }

    option = {
      url: '/cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia,
      type: 'GET',
      dataType: 'json',
      data: {}
    };
    $.ajax(option).done(function(data) {

      var cantidad = data.cantidad;
      var cantidadEstablecimiento = data.cantidadEstablecimiento;
      var cantidadAlmacen = data.cantidadAlmacen;
      $(cmb).closest("tr").find(".limpia_text").val("");
      //$(cmb).closest("tr").find("#nro_stocks").val(data);
      $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
      $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
      $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
      <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
      $(cmb).closest("tr").find(".tiempo_periodo").focus();
      <?php //endif;?>
      $(cmb).closest("tr").find("#nro_med_solictados").val("");
      $(cmb).closest("tr").find("#nro_med_entregados").val("");
      $(cmb).closest("tr").find("#lotes_lote").val("");
      $(cmb).closest("tr").find("#lotes_cantidad").val("");
      $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
      $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
    });

  });
  filasmedicamentos++;
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
      url: "<?php echo url()?>/genera_csv",
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
  $("#flagServicioFirma").val("1");        
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
          location.href = '/cq_lista_pendiente';
          clearInterval(batchSignatureProcessPoller);
        }
      }
    });
  }, 3000);
}

/*
function validarFirma(){

	//$('.loader').show();
	var batchSignatureProcessPoller = setInterval(function () {
		$.ajax({
			url: "<?php echo url()?>/validarFirma",
			type: "post",
			cache: false,
			dataType: 'json',
			data: $("#frmBloque").serialize(),
			success: function (completed) {
				if (completed) {
					//$('.loader').hide();
					//cargarSolicitudes();
					//$('#Grd2 tbody tr').html("");
					//$('#spanContador').html("");
					clearInterval(batchSignatureProcessPoller);
					
				}
			}
		});
	}, 3000);
	

}
*/

function enviarReceta(id) {

  $("#id_receta").val(id);

}

function enviarRadiologia(id) {

  $("#id_radiologia").val(id);

}

function enviarLaboratorio(id) {

  $("#id_laboratorio").val(id);

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
      && flagReferencia == 0) {
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
      && flagReferencia == 0) {
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
      && flagReferencia == 0) {
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
      && flagReferencia == 0) {
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

function getProducto() {

  $('#example tbody').html('');
  if ($('#id_servicio').val() == "") return false;
  //var id_ipress 		= $('#id_ipress').val();
  var id_ipress = $('#id_establecimiento').val();
  var id_consultorio = $('#id_servicio').val();
  //var tipo_receta		= $('#tipo_receta').val();
  //var id_tiporeceta 	= $('#id_tiporeceta').val();
  var id_tiporeceta = $('#id_tipo_atencion').val();

  var id_farmacia = $('#id_farmacia_receta').val();
  var nivel = $('#id_nivel').val();
  //var x  = $('#nro_interconsultas').val();
	var x  = $('#nro_interconsultas').val();
  var dni = $('#dni_beneficiario').val();
	var interconsulta = $('#interconsulta').val();

  //$('#interconsulta').val();	
  if(id_tiporeceta == "3"){ 
		if(interconsulta=='SI'){
		  if (id_consultorio==104){
			id_consultorio2=104
		  }
		  else
		  {
			id_consultorio2=105
		  }
			$.ajax({
			  //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + id_consultorio2+ '/' + dni,
			  url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +id_consultorio2,
			  success: function(result){                    
				var newOption = "";
				newOption = "<option value=''>--Seleccionar--</option>";
				$(result).each(function (ii, oo) {
				  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
				});                 
				$('#cmbMedicamentoTemp').html(newOption);
			  }
			}); 
		}
		else
		{  
		  
		  if(x==0){ //SIN INTERCONSULTA
			if (id_consultorio==104){
			  $.ajax({
				url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 104,
				//url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
				success: function(result){                    
				  var newOption = "";
				  newOption = "<option value=''>--Seleccionar--</option>";
				  $(result).each(function (ii, oo) {
					newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
				  });                 
				  $('#cmbMedicamentoTemp').html(newOption);
				}
			  });
			}
			else
			{
			  if (id_consultorio==21){
				$.ajax({
				  url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 21,
				  //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
				  success: function(result){                    
					var newOption = "";
					newOption = "<option value=''>--Seleccionar-</option>";
					$(result).each(function (ii, oo) {
					  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
					});                 
					$('#cmbMedicamentoTemp').html(newOption);
					//$("#id_medico").select2({max_selected_options: 4});
				  
				  }
				});
			  }
			  else
			  {
				if (id_consultorio==72){
					$.ajax({
					  //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
					  url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 72,
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
				else
				{
					$.ajax({
					  url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
					  //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
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
			}
		  }
		  else
		  {
			if (id_consultorio==104){
			  $.ajax({
				url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
				success: function(result){                    
				  var newOption = "";
				  newOption = "<option value=''>--Seleccionar--</option>";
				  $(result).each(function (ii, oo) {
					newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
				  });                 
				  $('#cmbMedicamentoTemp').html(newOption);
				}
			  });
			}
			else
			{
			  if (id_consultorio==21){
				$.ajax({
				  url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 21 + '/' + dni,
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
			  else
			  {
				if (id_consultorio==72){
					$.ajax({
					  //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
					  url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 72,
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
				else
				{
					$.ajax({
					  url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 105+ '/' + dni,
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
			}  
		  }          
		}       
	}else{
	
		
		if(id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia!=18 && id_farmacia!=25){
			if(id_tiporeceta==2){
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
					//$("#id_medico").select2({max_selected_options: 4});
				
				}
			});
		
		}
		if((id_tiporeceta == 1 || id_tiporeceta == 5 || id_tiporeceta == 6) && id_farmacia == 21){ //nutricional
			if(id_tiporeceta==5 || id_tiporeceta == 1){
				id_consultorio = 110;
			}
			else{
				id_consultorio = 111;
			}
				
		
			$.ajax({
				url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
				success: function(result){                    
					var newOption = "";
					newOption = "<option value=''>--Seleccionar-</option>";
					$(result).each(function (ii, oo) {
						newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
					});                 
					$('#cmbMedicamentoTemp').html(newOption);
					//$("#id_medico").select2({max_selected_options: 4});
				
				}
			});
		
		}
		if(id_farmacia==18 || id_farmacia==25 || id_tiporeceta == 4){ //centro quirurgico
      if(id_tiporeceta == 1){
        if(id_farmacia == 25){
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
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
          if(id_consultorio == 72){
            $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                  '/' + 72,
                success: function(result) {
                  var newOption = "";
                  newOption = "<option value=''>--Seleccionar-</option>";
                  $(result).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                      .nombre + ", " + oo.abreviatura + "</option>";
                  });
                  $('#cmbMedicamentoTemp').html(newOption);
                }
            });
          }
          else{
            $.ajax({
              url: '/obtenerProductosFarmaciasByNivel/'+id_farmacia+'/' + id_tiporeceta+'/'+id_consultorio,
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
        //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
      }
      else
      {
        if(id_tiporeceta == 4){
          if(id_consultorio == 72){
            $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                  '/' + 72,
                success: function(result) {
                  var newOption = "";
                  newOption = "<option value=''>--Seleccionar-</option>";
                  $(result).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                      .nombre + ", " + oo.abreviatura + "</option>";
                  });
                  $('#cmbMedicamentoTemp').html(newOption);
                }
              });
          }
          else{
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_quirurgico_new/' + id_ipress+ '/' + id_consultorio,
              success: function(result){                    
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                });                 
                $('#cmbMedicamentoTemp').html(newOption);
                //$("#id_medico").select2({max_selected_options: 4});
              }
            });  
          }
        }
      }
	  }
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

$(document).on("click", ".EditarFilaProductosAnestesia", function() {
  let row = $(this).closest("tr");

  row.find("input, select").prop("disabled", false);

  row.find("input[name='accionRowProductoAnestesia[]']").val("edit");

  $(this).closest('tr').css('background-color', '#87c787');
  EditarReceta();
});

$('.AnularFilaProductosAnestesia').on('click', function() {
  let row = $(this).closest("tr");

  row.find("input, select").prop("disabled", true);

  row.find("input[name='accionRowProductoAnestesia[]']").val("delete");

  $(this).closest('tr').css('background-color', '#f8d7da !important');
  EliminarReceta();
});

$('#restaurarColoresMedicamentos').on('click', function() {
  $("input[name='accionRowProductoAnestesia[]']").val("");

  // Deshabilita todos los inputs de tipo 'text' en la tabla
  $("#tblMedicamentosAQ").find("input[type='text']").prop("disabled", true);

  // Deshabilita todos los selects en la tabla
  $("#tblMedicamentosAQ").find("select").prop("disabled", true);
  
  $('#tblMedicamentosAQ tr').css('background-color', '');
});
  
function EditarReceta() {
  $("input[name='accionRowProductoAnestesia[]']").each(function () {
        console.log('Valor inicial de accionRowProductoAnestesia: ' + $(this).val());
        $("#divGuardar").show();
        $("#divFirmar").hide();
    });
}

function EliminarReceta() {
  $("input[name='accionRowProductoAnestesia[]']").each(function () {
        console.log('Valor inicial de accionRowProductoAnestesia: ' + $(this).val());
        $("#divGuardar").show();
        $("#divFirmar").hide();
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

  .icon-doc-firmado {
    color: #5cb85c;
    font-size: 3rem;
  }
</style>
@endsection
@section('content')

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÃ‰REZ DE SERVICIO","ALFÃ‰REZ MAESTRO ARMERO","CAPITÃN","CAPITÃN DE SERVICIO","CAPITÃN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÃ‰CNICO DE 1RA","ESPECIALISTA TÃ‰CNICO DE 2DA","ESPECIALISTA TÃ‰CNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>

<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE CENTRO QUIRÚRGICO - ESPECIALISTA QUIRÚRGICO</h3>
          </div>
          <div class="panel-body">
            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->
            <form class="form-horizontal" method="post" id="frmBloque"
              action="{{ route('registro_receta_prestacion')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
              <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacion" id="id_prestacion">
              <input type="hidden" value="<?php echo $id_intervencion?>" name="id_intervencion" id="id_intervencion">

              <input type="hidden" value="<?php echo $prestacion->id_cita?>" name="id_cita" id="id_cita">
              <input type="hidden" value="<?php echo $prestacion->id_asegurado?>" name="id_asegurado" id="id_asegurado">
              <input type="hidden" value="<?php echo $prestacion->id_establecimiento?>" name="id_establecimiento"
                id="id_establecimiento">
              <input type="hidden" value="<?php echo $prestacion->id_sub_consultorio?>" name="id_sub_consultorio"
                id="id_sub_consultorio">
              <input type="hidden" value="<?php echo $prestacion->nro_doc_ident?>" name="dni_beneficiario"
                id="dni_beneficiario">
                
              <input type="hidden" value="<?php echo $prestacion->id_medico?>" name="id_medico" id="id_medico">
              <input type="hidden" value="<?php echo $prestacion->id_tipo_atencion?>" name="id_tipo_atencion" id="id_tipo_atencion">
              <input type="hidden" value="<?php echo $prestacion->id_consultorio?>" name="id_servicio" id="id_servicio">
              <input type="hidden" value="<?php echo $prestacion->sexo?>" name="nomsexo" id="nomsexo">
              <input type="hidden" value="<?php echo $nivel?>" name="id_nivel" id="id_nivel">

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
                      <div class="col-md-3">
                        <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                        <p><?php echo $prestacion->tipo_atencion?></p>
                      </div>
                      <div class="col-md-3">
                        <label for="modalidad_beneficiario">MODALIDAD</label><br/>
                        <?php 
                          if($prestacion->id_modalidad==1){
                            echo "PRESENCIAL";
                          }
                          else
                          {
                            echo "VIRTUAL";
                          }
                        ?>
                      </div>
                      <?php 
                      switch ($prestacion->id_tipo_atencion) {
                        case '1': //AMBULATORIO
                          $descripcion='SERVICIO'; $descripcion2='CONSULTORIO';break;
                        
                        case '2': //EMERGENCIA
                          $descripcion='PRIORIDAD'; $descripcion2='TOPICO';break;

                        case '3': //HOSPITALIZACION
                          $descripcion='SALA'; $descripcion2='SERVICIO';break;
                       }
                      ?>
                      <div class="col-md-3">
                        <label for="nombre_ipres"><?php echo $descripcion ?></label>
                        <p><?php echo $prestacion->servicio?></p>
                      </div>
                      @if($prestacion->id_tipo_atencion==1)
                        @if($prestacion->id_modalidad==1)
                          <div class="col-md-2">
                            <label>SALA</label>
                            <p><?php echo $prestacion->consultorio?></p>
                          </div>
                        @endif
                      @endif
                      <div class="col-md-3">
                        <label for="fecha_atencion">FECHA DE ATENCION</label>
                        <p><?php echo $prestacion->fecha_atencion?></p>
                      </div>
                      <div class="col-md-3">
                        <label for="fecha_atencion">HORA DE ATENCION</label>
                        <p>{{date('H:m')}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>CODIGO UPSS</label>
                        <p><?php echo $prestacion->codigo_upss?></p>
                      </div>
                      <div class="col-md-3">
                        <label for="tipo">NOMBRE DE LA IPRESS</label>
                        <p><?php echo $prestacion->establecimiento?></p>
                      </div>
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
                        <label for="dni_beneficiario">DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->nro_doc_ident?></p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $prestacion->asegurado_paterno?></p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $prestacion->asegurado_materno?></p>
                      </div>
                      <div class="col-md-3">
                        <label>NOMBRES</label>
                        <p><?php echo $prestacion->asegurado_nombre?></p>
                      </div>
                      <div class="col-md-3">
                        <label>PARENTESCO &nbsp;</label>
                        <p><?php echo $prestacion->parentesco?></p>
                      </div>
                      <div class="col-md-3">
                        <label>FECHA DE NACIMIENTO</label>
                        <p><?php echo $prestacion->fecha_nac?></p>
                      </div>
                      <div class="col-md-3">
                        <label>GRADO &nbsp;</label>
                        <p><?php echo $prestacion->grado?></p>
                      </div>
                      <div class="col-md-3">
                        <label>SITUACION &nbsp;</label>
                        <p><?php echo $prestacion->situacion_paciente?></p>
                      </div>
                      <div class="col-md-3">
                        <label for="dni_beneficiario">SEXO</label>
                        <p><?php echo $prestacion->sexo?></p>
                      </div>
                      <div class="col-md-3">
                        <label>DIRECCIÓN &nbsp;</label>
                        <p><?php echo $prestacion->domicilio?></p>
                      </div>
                    </div>
                  </div>
                </fieldset>
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
                        <p><?php echo $prestacion->tipo_doc_ident?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">N ° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->dni?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $prestacion->medico_paterno?></p>
                      </div>
                      <div class="col-md-4">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $prestacion->medico_materno?></p>
                      </div>
                      <div class="col-md-4">
                        <label>NOMBRES</label>
                        <p><?php echo $prestacion->medico_nombre?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label>PROFESION</label>
                        <p><?php echo $prestacion->profesion?></p>
                      </div>
                      <div class="col-md-4">
                        <label>N ° COLEGIATURA</label>
                        <p><?php echo $prestacion->colegiatura?></p>
                      </div>
                      <div class="col-md-4">
                        <label>N ° RNE</label>
                        <p><?php echo $prestacion->rne?></p>
                      </div>
                      <div class="col-md-3">
                        <label>ESPECIALIDAD</label>
                        <p><?php echo $prestacion->especializacion?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center" style="position: relative">
                      <b>DIAGNÓSTICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
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
                              <tr class="row-diagnosticosfinal">
                                <td>
                            <input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->codigo }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                            </td>
                            <td>
                          
                          <input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->nombre }}" class=" input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                          </td>
                            <td>
                        <input name="id_tipo_diagnosticos['{{ $row->id_diagnostico }}']" id="" value="{{ $tipo_diagnostico }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                      </td>
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
                        <div class="panel-title text-center" style="position: relative">
                      <b>PROCEDIMIENTOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                      <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
                              <thead>
                                  <tr>
                              <th width="10%">codigo</th>
                            <th width="40%">Nombre</th>
                          <th width="10%">Cantidad</th>
                          @if($id_prestacion>1910698)
                            <th width="40%">diagnostico</th>
                          @endif
                                    </tr>
                                </thead>
                                    <tbody>
                              @foreach($procedimiento as $row)
                            <input type="hidden" value="{{ $row->cod }}" name="prestacion_procedimiento_id[]" style="width : 1px; heigth : 1px">
                                <tr class="row2-{{ $row->cod }}">
                                <td><input type="text" name="codigodeprod[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->codigo }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                                      <td>
                                    {{$row->nombre}}
                                    <input name="id_procedimientos[{{ $row->cod }}]" id="idProcedimientos" class=" input-procedimiento form-control" value="{{$row->nombre}}" type="hidden" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                            </td>
                              <td><input type="text" name="cantidad_procedimientos[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->cantidad }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                                @if($id_prestacion>1910698)
                                <td>
                              <input name="iddiagnosticosxProcedimiento[{{ $row->id_diagnostico }}]" id="iddiagnosticosxProcedimiento" class="input-procedimiento form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly
                          value="<?php
                            $model_diagnosticos = new \App\Models\Producto_receta;
                              $diagnostico_id = $row->id_diagnostico;
                            $diagnostico_nombre = $model_diagnosticos->get_diagnostico_nombrexProcedimiento($diagnostico_id);
                                  echo $diagnostico_nombre;
                                            ?>">
                                  </td>
                                  @endif
                                </tr>
                              @endforeach
                                  </tbody>
                                        <!--tfoot>
                                          <tr>
                                            <td>
                                            <button type="button" style="margin-top: 6px;display:none;" class="btn btn-warning" id="btnSaveProcedimiento" disabled>Guardar</button>
                        </td>
                      </tr>
                      </tfoot-->
                      </table>
                  </div>
                </div>
              </fieldset>
              <fieldset id="fsAntecedente">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                        <b>ANTECEDENTES</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <label>ANTECEDENTE PERSONAL</label>
                      <p><?php echo $prestacion->antecedente?></p>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <label>ANTECEDENTE FAMILIAR</label>
                        <p><?php echo $prestacion->antecedente_familiar?></p>
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
                    <p><?php echo $prestacion->motivo_visita?></p>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-md-12">
                    <label>TIEMPO DE ENFERMEDAD</label>
                      <p><?php echo $prestacion->tiempo_enfermedad?></p>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-md-12">
                    <label>RELATO CRONOLOGICO</label>
                    <p><?php echo $prestacion->anamnesis?></p>
                  </div>
                </div>
              </fieldset>
              <fieldset id="fsAspectoGeneral">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>EXAMEN FÍSICO</b>
                    </div>
                  </div>  
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>EXAMEN FISICO</label>
                          <p><?php echo $prestacion->examen_fisico?></p>
                      </div>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>EXAMEN CLINICO</label>
                        <p><?php echo $prestacion->examen_clinico?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>EXAMENES AUXILIARES</label>
                        <p><?php echo $prestacion->examen_auxiliar?></p>
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
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>TRATAMIENTO AUXILIAR</label>
                        <p><?php echo $prestacion->tratamiento_auxiliar?></p>
                      </div>
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
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>PLAN DE TRABAJO</label>
                        <p><?php echo $prestacion->plan_trabajo?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset id="fsRecetaVale">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PRODUCTOS MEDICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                      <?php foreach($receta as $rowReceta):
                        if ($rowReceta->fecha_firma == "") {
                                        $flag_firmar = 1;
                          }
                        $model_productos_receta = new \App\Models\Producto_receta;
                        $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale_cqanestesia($rowReceta->id);
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
                                <label class="col-md-2" style="padding-left:0px">RECETA VALE N °</label>
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
                            <table class="table table-bordered" id="tblMedicamentosAQ" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                  <th width="10%">Codigo</th>
                                  <th width="30%">Producto farmacético o dispositivo médico</th>
                                  <th width="10%">Unidad Medida</th>
                                  <th width="7%">Tiempo(Dias)</th>
                                  <th width="15%">Posologia</th>
                                  <th width="10%">Via Administración</th>
                                  <th width="8%">Cantidad Prescrita</th>
                                  <th width="10%">diagnosticos</th>
                                  <th width="10%" colspan="2">
                                    <div style="width: 100%; display: flex; justify-content: space-around">
                                      <span>Cancelar Acción</span>
                                      <button type="button" class="btn btn-danger btn-xs" style="width: auto;" id="restaurarColoresMedicamentos">
                                        <i class="fa fa-times"></i>
                                      </button>
                                    </div>
                                  </th>
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
                                  <td>
                                    <?php echo $row->abreviatura?>
                                    <input name="id_producto_receta_editar[]" type="hidden" value="{{ $row->id_producto_receta }}"/>  
                                  </td>
                                  <td><input name="tiempo_periodo_editar[]" type="text" value="<?php echo $row->tiempo_periodo?>" disabled class="form-control" style="height: 28px !important;font-weight: 800;" required/></td>
                                  <td><input name="posologia_editar[]" type="text" value="<?php echo $row->posologia?>" disabled class="form-control" style="height: 28px !important;font-weight: 800;" required/></td>
                                  <td>
                                    <select name="id_via_administracion[]" class="form-control id_via_administracion_anestesia" style="margin:0 20px;width:150px" disabled>
                                      <option value="0">--Seleccionar--</option>
                                      @foreach ($via_administracion as $va)
                                      <option @if ($row->id_via_administracion == $va->id) selected @endif value="{{ $va->id }}">{{ $va->descripcion }}</option>
                                      @endforeach
                                    </select>
                                  </td>
                                  <td><input name="cantidad_prescrita_editar[]" type="text" value="<?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                    echo $row->cantidad_prescrita/1000;
                                    else:
                                      echo $row->cantidad_prescrita;
                                    endif; ?>" class="form-control" disabled style="height: 28px !important;font-weight: 800;" required/></td>
                                  <td><?php
                                      $model_productos_receta = new \App\Models\Producto_receta;
                                      $diagnostico_id = $row->id_diagnosticos;
                                      $diagnostico_nombre = $model_productos_receta->get_diagnostico_nombre($diagnostico_id);
                                      echo $diagnostico_nombre; ?>    
                                  </td>
                                  <td>
                                    <input type="hidden" value="" class="accionRowProductoAnestesia" id="accionRowProductoAnestesia" name="accionRowProductoAnestesia[]"/>
                                    <button type="button"  class="btn btn-primary btn-xs EditarFilaProductosAnestesia" data-id-producto="{{ $row->id_producto_receta }}" data-toggle="modal" style="margin-left:4px">
                                      <i class="fa fa-edit"></i> Editar
                                    </button>
                                  </td>
                                  <td>
                                    <button type="button"  class="btn btn-danger AnularFilaProductosAnestesia btn-xs" data-id-producto="{{ $row->id_producto_receta }}" data-toggle="modal" style="margin-left:4px">
                                      <i class="fa fa-times"></i> Eliminar
                                    </button>
                                  </td>
                                </tr>
                              <?php endforeach;?>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                          </div>
                      </fieldset>
                        <?php endforeach;?>
                        <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                          <a onclick="agregarReceta()" id="btnNuevoReceeta" flag="0" class="btn btn-success">Agregar
                            Receta</a>
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
                                <select class="form-control" name="id_farmacia_receta" id="id_farmacia_receta" style="width:100%" onchange="getProducto()" disabled>
                                  <option value="">-Seleccionar-</option>
                                      <?php foreach($farmacias as $row): ?>
                                          <option value="<?php echo $row->id; ?>"
                                              <?php if($row->id == 18) echo "selected='selected'"; ?>>
                                              <?php echo $row->nombre; ?>
                                          </option>
                                      <?php endforeach; ?>
                                 </select>
                              </div>
                            </div>
                            <br />
                            <table>
                              <tr>
                                <td>
                                  <button type="button" id="addProducto" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addClassModal">Agregar Producto</button>
                                </td>
                              </tr>
                            </table>
                            <br />
                            <div style="display: none">
                              <select class="form-control" id="cmbMedicamentoTemp" tabindex="16" style="width: 500px">
                                <option value="">Seleccionar Producto</option>
                              </select>
                            </div>
                            <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                            <table id="example" class="display" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                  <th>Producto farmaceútico o dispositivo médico</th>
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
              <div class="panel panel-info panel-sm">
                  <div class="panel-heading">
                     <div class="panel-title text-center"><b>REGISTRAR PROGRAMACIÓN QUIRÚRGICA</b></div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-sm-2">
                          <label for="">PRIORIDAD DE PROGRAMACION EN SEMANAS</label>
                      </div>
                      @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                      <div class="col-md-2">
                          <select name="id_prioridad_programacion" id="id_prioridad_programacion" class="form-control input-sm">
                              <option value="0">Seleccione</option>
                              @foreach ($cqx_dias as $row)
                                  @if ($row->flag == 1)
                                  <option value="{{ $row->id }}" @if($intervencion->id_prioridad_programacion==$row->id) selected @endif>{{ $row->descripcion }}</option>
                                  @endif
                              @endforeach
                          </select>
                      </div>
                      @else
                      <div class="col-md-2">
                          <p>{{ $intervencion->prioridad_programacion}}</p>
                      </div>
                      @endif
                    </div>
                    <div class="form-group">
                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                          <label>REQUIERE INTERNAMIENTO PRE QX</label>
                      </div>
                      @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                          <button tabindex="100" type="button" id="addRequiereInternamientoPreQx" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                          <div class="handle"></div>
                          </button>
                          <input type="hidden" id="has_requiere_internamiento_preqx" name="has_requiere_internamiento_preqx" value="0">
                      </div>
                      <div class="col-md-2">
                          <fieldset id="fsRequiereInternamientoPreQx">
                              <select name="id_tiempo_internamiento_preqx" id="id_tiempo_internamiento_preqx" class="form-control input-sm">
                                  <option value="0">Seleccione</option>
                                  @foreach ($cqx_dias as $row)
                                      @if ($row->flag == 2)
                                      <option value="{{ $row->id }}" @if ($intervencion->is_requiere_internamiento_preqx) @if($intervencion->id_tiempo_internamiento_preqx==$row->id) selected @endif @endif>{{ $row->descripcion }}</option>
                                      @endif
                                  @endforeach
                              </select>
                          </fieldset>
                      </div>
                      @else
                          @if ($intervencion->is_requiere_internamiento_preqx)
                          <div class="col-md-2"><p>SI</p></div>
                          <div class="col-md-2"><p>{{ $intervencion->tiempo_internamiento_preqx }}</p></div>
                          @else
                          <div class="col-md-2">NO</div>
                          @endif
                      @endif
                    </div>
                    <div class="form-group">
                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                          <label>DIAS DE HOSPITALIZADO POST QX</label>
                      </div>
                      @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                          <button tabindex="100" type="button" id="addDiasHospitalizadoPostQx" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                            <div class="handle"></div>
                          </button>
                          <input type="hidden" id="has_requiere_hospitalizacion_postqx" name="has_requiere_hospitalizacion_postqx" value="0">
                      </div>
                      <div class="col-sm-2">
                          <fieldset id="fsDiasHospitalizadoPostQx">
                              <select name="id_tiempo_hospitalizacion_postqx" id="id_tiempo_hospitalizacion_postqx" class="form-control">
                                  <option value="0">Seleccione</option>
                                  @foreach ($cqx_dias as $row)
                                      @if ($row->flag == 3)
                                      <option value="{{ $row->id }}" @if($intervencion->id_tiempo_hospitalizacion_postqx==$row->id) selected @endif>{{ $row->descripcion }}</option>
                                      @endif
                                  @endforeach
                              </select>
                          </fieldset>
                      </div>
                      @else
                          @if ($intervencion->is_hospitalizacion_postqx)
                          <div class="col-md-2">
                              <p>SI</p>
                          </div>
                          <div class="col-md-2">
                              <p>{{ $intervencion->tiempo_hospitalizacion_postqx }}</p>
                          </div>
                          @else
                          <div class="col-md-2">
                              <p>NO</p>
                          </div>
                          @endif
                      @endif
                  </div>
                  <?php
                  $lugar_operacion = "";
                  switch ($intervencion->id_lugar_operacion) {
                      case 1: $lugar_operacion = "HOSPITALIZADO"; break;
                      case 2: $lugar_operacion = "CLÍNICA DE DÍA"; break;
                  }
                  ?>
                  <div class="form-group">
                      <div class="col-sm-12 col-md-6">
                          <label for="">A SER OPERADO EN:</label><p> {{ $lugar_operacion }}</p>
                      </div>
                      <div class="col-sm-12 col-md-6">
                          <label for="">FECHA PROBABLE DE INTERVENCIÓN:</label><p> {{ $intervencion->fecha_probable }}</p>
                      </div>
                  </div>
                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                      <label>FECHA DE INTERVENCION</label>
                  </div>
                  @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                    <input type="text" class="form-control datepicker input-sm" id="fecha_intervencion" name="fecha_intervencion">
                  </div>
                  @else
                  <div class="col-md-2">
                      <p>{{ date('d-m-Y', strtotime($intervencion->fecha_inicio_intervencion)) }}</p>
                  </div>
                  @endif
                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                      <label>HORA DE INICIO</label>
                  </div>
                  @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                    <input type="time" class="form-control input-sm" name="hora_inicio_intervencion" id="hora_inicio_intervencion" value="{{ $intervencion->hora_inicio_prog_qx }}">
                  </div>
                  @else
                  <div class="col-md-2">
                      <p>{{ date('H:i', strtotime($intervencion->fecha_inicio_intervencion)) }}</p>
                  </div>
                  @endif
                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                      <label>HORA DE FIN</label>
                  </div>
                  @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                      <input type="time" name="hora_inicio" class="form-control">
                  </div>
                  @else
                  <div class="col-md-2">
                      <p>{{ date('H:i', strtotime($intervencion->fecha_fin_intervencion)) }}</p>
                  </div>
                  @endif
              </div> 
              <div class="form-group">
                <div class="col-md-2" style="margin-left:30px;">
                    <label for="">SALA DE INTERVENCIÓN:</label>
                </div>
                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                <div class="col-md-2" style="margin-left:-10px;">
                  <select name="id_sala_intervencion" id="id_sala_intervencion" class="form-control input-sm">
                    <option value="0">Seleccione</option>
                    @foreach($cq_salas as $row)
                    <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                    @endforeach
                  </select>
                </div>
                @else
                <div class="col-md-2">
                    <p>{{ $intervencion->sala_intervencion }}</p>
                </div>
                @endif
                <div class="col-md-2" style="margin-left:-11px;">
                    <label for="">TIPO DE ANESTESIA:</label>
                </div>
                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                <div class="col-md-6">
                    <select name="id_tipo_anestesia" id="id_tipo_anestesia" class="form-control input-sm">
                        <option value="0">Seleccione</option>
                        @foreach($tipos_anestesia as $row)
                        <option value="{{ $row->id }}">{{ $row->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="col-md-2">
                    <p>{{ $intervencion->tipo_anestesia }}</p>
                </div>
                @endif
              </div>
              <div class="panel panel-info panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center"><b>CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN</b></div>
                </div>
                <div class="panel-body">
                  <div class="form-group">
                    <div class="col-sm-12">
                      <table id="table_medico_cirujano" class="display" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th>Apellidos y Nombres</th>
                            <th style="width:30%">Cargo</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($cirujanos as $p)
                          <tr>
                            <td>{{ $p->paterno }} {{ $p->materno }}, {{ $p->nombre }}</td>
                            <td>{{ $p->descripcion }}</td>
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
                  <div class="panel-title text-center"><b>PROCEDIMIENTOS PRE OPERATORIO</b></div>
                </div>
                <div class="panel-body">
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
                        @foreach ($procedimientos_intervencion as $pintervencion)
                        <tr>
                          <td>{{$pintervencion->codigo}}</td>
                          <td>{{$pintervencion->descripcion}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                  </table>
                </div>
              </div>
              <div class="panel panel-info panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center"><b>REGISTRO DE INTERVENCION</b></div>
                </div>
              <div class="panel-body">
                <div class="row form-group">
                    <div class="col-md-2">
                        <label for="">TIPO DE ANESTESIA</label>
                    </div>
                    <div class="col-md-2">
                      @if(!empty($intraoperatorio))
                          <p>{{ $intraoperatorio->descripcion }}</p>
                      @endif
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2">
                        <label for="">INICIO DE ANESTESIA</label>
                    </div>
                    <div class="col-md-2">
                      @if(!empty($intraoperatorio))
                        <p>{{ $intraoperatorio->anestesia_inicio }}</p>
                      @endif
                    </div>
                    <div class="col-md-2">
                        <label for="">TÉRMINO DE ANESTESIA</label>
                    </div>
                    <div class="col-md-2">
                      @if(!empty($intraoperatorio))
                        <p>{{ $intraoperatorio->anestesia_termino }}</p>
                      @endif
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2">
                        <label for="">INICIO DE CIRUGÍA</label>
                    </div>
                    <div class="col-md-2">
                      @if(!empty($intraoperatorio))
                        <p>{{ $intraoperatorio->cirugia_inicio }}</p>
                      @endif
                    </div>
                    <div class="col-md-2">
                        <label for="">TÉRMINO DE CIRUGÍA</label>
                    </div>
                    <div class="col-md-2">
                      @if(!empty($intraoperatorio))
                        <p>{{ $intraoperatorio->cirugia_termino }}</p>
                      @endif
                    </div>
                    <div class="col-md-2">
                        <label for="">TIEMPO REAL</label>
                    </div>
                    <div class="col-md-2">
                      @if(!empty($intraoperatorio))
                        <p>{{ $intraoperatorio->tipo_real }}</p>
                      @endif
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2">
                        <label for="">INICIO DE PACIENTE</label>
                    </div>
                    <div class="col-md-2">
                      @if(!empty($intraoperatorio))
                        <p>{{ $intraoperatorio->inicio_paciente }}</p>
                      @endif
                    </div>
                    <div class="col-md-2">
                        <label for="">SALIDA DE PACIENTE</label>
                    </div>
                    <div class="col-md-2">
                      @if(!empty($intraoperatorio))
                        <p>{{ $intraoperatorio->salida_paciente }}</p>
                      @endif
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2">
                        <label for="">HORA LLEGADA URPA</label>
                    </div>
                    <div class="col-md-2">
                      @if(!empty($intraoperatorio))
                        <p>{{ $intraoperatorio->hora_llegada_urpa }}</p>
                      @endif
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4" id="complicacionDescripcion">
                        <label for="">COMPLICACIÓN</label>
                        @if(!empty($intraoperatorio))
                          <p>{{ $intraoperatorio->complicaciones }}</p>
                        @endif
                    </div>
                </div>
              </div>

              <!--<input class="btn btn-primary pull-right" value="LISTADO" name="crea" type="button" form="prestacionescrea" id="btnListar" />&nbsp;&nbsp;-->
              <div id="divGuardar" style="display:none">
                <input class="btn btn-primary input-sm pull-right" value="GUARDAR" name="crea" type="button"
                  form="prestacionescrea" id="btnPrestacion" />&nbsp;&nbsp;
              </div>
            
              <div id="divFirmar">
                <input name="mov[]" id="mov[]" value="<?php echo $id_prestacion?>" type="hidden" />
                <input type="hidden" value="1" name="proviene_formulario" id="proviene_formulario">
                <?php if(Auth::user()->status_token==1): ?>
                <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button"
                  onclick="firmarsintokens()" style="margin-left:20px" />
                <?php else: ?>
                <?php if(Auth::user()->tipo_registro==3): ?>
                <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button"
                  onclick="firmarsintokens()" style="margin-left:20px" />
                <?php else: ?>
                <input class="btn btn-info input-sm pull-right" value="FIRMAR" type="button" id="btnfirmar"
                  style="margin-left:20px" />
                <?php endif; ?>
                <?php endif; ?>
                <a class="btn btn-primary btn-sm hidden-print pull-right" href="/cq_lista_pendiente"> <span
                    class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
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
<input type="hidden" id="argumentos" value="" />
<input type="hidden" id="doc_firmar" value="" />
<input type="hidden" id="opt_firma" value="" />
<input type="hidden" value="<?php echo $flag_firmar?>" name="flag_firmar" id="flag_firmar">
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
        <form class="" method="post" action="{{ route('invalidar_receta_digital_quirurgica')}}" id="">
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
            <input type="hidden" name="id_intervencion" id="id_intervencion" value="<?php echo $id_intervencion?>">
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


