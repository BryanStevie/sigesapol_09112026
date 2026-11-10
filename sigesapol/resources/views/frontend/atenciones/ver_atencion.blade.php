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
/*===============TABLA IMAGENOLOGÍA RESPONSIVE INICIO=====================================*/ 
#tblExamenRad {
    width: 100%;
    table-layout: auto; /* Permite que las columnas se ajusten al contenido */
    border-collapse: collapse; /* Une bordes para mejor presentación */
}

#tblExamenRad th, 
#tblExamenRad td {
    white-space: nowrap; /* Evita división en varias líneas */
    text-align: center; /* Centra el contenido */
    padding: 8px; /* Añade espacio*/
}

#tblExamenRad thead th {
    vertical-align: middle; /* los encabezados se alinean correctamente */
}

@media (max-width: 1207px) {
    .panel-body {
        overflow-x: auto;
        display: block;
    }
}
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
  //}
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


/*async function MiFuncionOkWeb() {
  var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  let flagServicioFirma = $("#flagServicioFirma").val();
  let id_radiologia = $("#id_radiologia_actualizar").val();
  let id_laboratorio = $("#id_laboratorio_actualizar").val();
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
  location.href = '/all_citas_medico_atenciones';        
  });
      });
      });
    });
  }
  if (flagServicioFirma == "2") {
    let id_resol = $('#id_prestacion').val();
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
    let id_resol = $('#id_prestacion').val();
    $.post("{{route('actualiza_rua')}}", {
      _token: CSRF_TOKEN,
      id_tipo: "radiologia",
      id_resol: id_radiologia,
    }, function(data, status) {
      $("#flagServicioFirma").val("");
      $("#id_radiologia_actualizar").val("");
      location.reload();
    });
  }
}
*/
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
              //location.reload();
              location.href = '/all_citas_medico_atenciones';        
            });
          });
        });
      });
    });
  });
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

  
  $(document).on('click', '.btnFinalizarLaboratorio', function(e) {
    e.preventDefault();
    firmarLaboratorioSinTokens($(this).data('id-lab'));
  })
  
  const firmarLaboratorioSinTokens = (id_laboratorio) => {
    let  batchSignatureProcessPoller = setInterval(function() {
      let id_prestacion = $("#id_prestacion").val();
      let proviene_formulario = $("#proviene_formulario").val();
      let id_tipo_atencion = $('#id_tipo_atencion').val();
      let postData =  { 
                        id_prestacion : id_prestacion, 
                        proviene_formulario : proviene_formulario,
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
    let proviene_formulario = $("#proviene_formulario").val();
    let postData = { id_prestacion : id_prestacion, 
                     proviene_formulario : proviene_formulario, 
                     id_laboratorio : id_laboratorio 
                   };
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
    let postData = { id_prestacion : id_prestacion, id_radiologia : id_radiologia };
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
      let id_tipo_atencion = $('#id_tipo_atencion').val();
      let postData =  { 
                        id_prestacion : id_prestacion, 
                        proviene_formulario : proviene_formulario,
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

  $('#addProcProgra').on('click', function() {
    AddProcedimientoProgramado();
  });

  $('#tblSolicitudPp').on('click', 'button.deleteFilaPp', function() {
    $(this).closest('.pp-item').remove();
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

  $('#addExamenLab2').on('click', function() {
    AddExamenLab();
  });

  // $('#addExamenRad2').on('click', function() {
  //   AddExamenRad();
  // });
  ////////////////////////////////////////////////////////////////////////////// NUEVA MODIFICACION EXAMEN DE IMÁGENES 15-05-26 //ACTUALIZADO 29-05-26
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

        var id_emergencia      = 0;
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
    ////////////////////////////////////////////////////////////////////////////// FIN MODIFICACION EXAMEN DE IMÁGENES 15-05-26

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

  $("#id_farmacia_receta").val($("#id_farmacia").val()).trigger("change");

  //AddDiagnostico();
  //AddProcedimiento();
  // AddExamenLab();
  //AddExamenRad();

});


function guardarPrestacion() {

  var msg = "";

  // Laboratorio
  const tblExamenLab_cantidad_tr = $('#tblExamenLab tbody tr').length;
  if (tblExamenLab_cantidad_tr > 0) {
    const ordenlab_tr = $('#tblExamenLab tbody').children('tr');

    let fila_ordenlab, id_examen_lab, cantidad_lab, id_diagnostico_lab, cpms_lab;
    
    for (let i = 0; i < tblExamenLab_cantidad_tr; i++) {
      fila_ordenlab = ordenlab_tr[i];

      id_examen_lab = $(fila_ordenlab).find('input[name="id_examen_lab[]"]').val();
      cantidad_lab = $(fila_ordenlab).find('input[name="examen_lab_cantidad[]"]').val();
      id_diagnostico_lab = $(fila_ordenlab).find('select[name="miSelectLab[]"]').val();
      cpms_lab = $(fila_ordenlab).find('input[name="cod_lab[]"]').val();

      if(id_examen_lab == "") {
        msg += "Debe registrar un examen de laboratorio<br>";
      } else {
        if (cantidad_lab == "") msg += `Debe registrar una cantidad para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
        if (cantidad_lab == 0) msg += `Debe registrar una cantidad mayor a 0 para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
        if (id_diagnostico_lab == "" || id_diagnostico_lab == null) msg += `Debe seleccionar el diagnóstico para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
      }
    }
  }

  if ($('#pp_activo').val() == '1') {
    $('#tblSolicitudPp .pp-item').each(function() {
      var nombre_pp = $(this).find('input[name="nombre_procedimiento_p[]"]').val();
      var id_pp = $(this).find('input[name="id_procedimiento_p[]"]').val();
      var cantidad_pp = $(this).find('input[name="procedimiento_cantidad_p[]"]').val();
      var diagnostico_pp = $(this).find('select[name="miSelectPp[]"]').val();
      var indicacion_pp = $.trim($(this).find('textarea[name="procedimiento_indicacion_p[]"]').val());
      if (id_pp == "") {
        msg += "Debe seleccionar el procedimiento programado de la lista<br>";
      } else {
        if (cantidad_pp == "" || parseInt(cantidad_pp) <= 0) msg += `Debe registrar una cantidad mayor a 0 para el <b>Procedimiento: ${nombre_pp}</b><br>`;
        if (diagnostico_pp == "" || diagnostico_pp == null) msg += `Debe seleccionar el diagnóstico para el <b>Procedimiento: ${nombre_pp}</b><br>`;
        if (indicacion_pp == "") msg += `Debe registrar la indicación para el <b>Procedimiento: ${nombre_pp}</b><br>`;
      }
    });
  }

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

  function obtenerIndiceTblExamenLab() {
    let indice = $('#tblExamenLab tbody tr').length;
    
    $('.id_laboratorio').each(function() {
      let ind_tmp = $(this).data("id");
      if (ind_tmp >= indice) {
        indice = Number(ind_tmp) + 1;
      }
    });

    return indice;
  }

function AddExamenLab() {
  var newRow = "";
  var ind = $('#tblExamenLab tbody tr').length;
  const id_nivel = $("#id_nivel").val();
  if (ind > 0) {
    /*$('.id_laboratorio').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });*/

    ind = obtenerIndiceTblExamenLab();

    fila_medicamentos = $('#tblExamenLab tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];

  }

  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id="'+ind+'">';
  newRow +='<input type="hidden" class="form-control cod_lab" id="cod_lab' + ind +'" name="cod_lab[]" value="" data-id='+ind+'>';
  newRow += '</td>';
  if (id_nivel && parseInt(id_nivel) < 3) {
    newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion" style="margin:0 10px;width:95%" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
  }
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1"></td>';
  newRow += '<td><select class="form-control select-diagnostico" id="miSelectLab' + ind + '" name="miSelectLab[]"></select></td>';
  // if (id_nivel && parseInt(id_nivel) < 3) {
  //   newRow +='<td><input type="text" maxlength="2" placeholder="Tiempo" class="form-control examen_lab_tiempo typeahead tt-query" style="margin:0 20px;width:150px" id="examen_lab_tiempo' + ind + '" name="examen_lab_tiempo[]" value="1"></td>';
  // }
  newRow += '<td></td>';
  newRow += '<td></td>';
  newRow += '<td></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblExamenLab tbody').append(newRow);

  var nivel = $('#id_nivel').val();
  genero = getSexoGenero();
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  let id_servicio = $("#id_servicio").val();
  //tipo=2
  const selectElementLab = document.getElementById('miSelectLab' + ind);
  
  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id_diagnostico;
    option.textContent = item.nombre;
    selectElementLab.appendChild(option);
  });

  $("#nombre_examen_lab" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        //url: '../../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 2,
        url: '../../obtener_laboratorio_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + id_servicio,
        //url: '../obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaLaboratorioSelecionado = $(".id_laboratorio").map(function(){return parseInt($(this).val());}).get();
          
          // IDs de los exámenes de la tabla auxiliar
          let listaExamenAux = $(".id_lna_examen")
            .map(function () {
                return parseInt($(this).val());
            }).get();

          let idsExcluir = listaLaboratorioSelecionado.concat(listaExamenAux);

          let filtro = data.filter(element => !idsExcluir.includes(parseInt(element.id)));

          response(filtro);
        }
      })

    },
    select: function(e, ui) {
      var id_examen_lab = ui.item.id;
      $("#id_examen_lab" + ind).val(id_examen_lab);
      $("#nombre_examen_lab" + ind).prop("readonly", true);
      $("#cod_lab" + ind).val(ui.item.codigo_procedimiento);
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
      $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });*/

}

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
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_rad_cantidad typeahead tt-query" style="margin:0 5px;width:100%" id="examen_rad_cantidad' +ind + '" name="examen_rad_cantidad[]" value="1"></td>';
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
  $("#examen_rad_cantidad" + ind).on('input', function() {
    var valor = $(this).val();
    if (valor === '0') {
      $(this).val(''); // Eliminar el valor si ingresan 0
    }
  });
  var nivel = $('#id_nivel').val();
  let id_servicio = $('#id_servicio').val();
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
        url: '../../obtener_radiologia_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1 + '/' + id_servicio,
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

  // $("#nombre_examen_rad" + ind).on('blur', function() {
  //   var id_examen_rad = $("#id_examen_rad" + ind).val();
  //   if (id_examen_rad == "") {
  //     bootbox.alert("Falta ingresar el examen radiografia en esta fila");
  //     $("#examen_rad_cantidad" + ind).val("");
  //     return false;
  //   }
  // });

  // $("#examen_rad_cantidad" + ind).on('blur', function() {
  //   var id_examen_rad = $("#id_examen_rad" + ind).val();
  //   if (id_examen_rad == "") {
  //     bootbox.alert("Falta ingresar la cantidad en este examen radiografia");
  //     $("#examen_rad_cantidad" + ind).val("");
  //     return false;
  //   }
  // });
  $("#nombre_examen_rad" + ind).on('blur', function() {
    var id_examen_rad = $("#id_examen_rad" + ind).val();
    var nombre_examen = $("#nombre_examen_rad" + ind).val();
    var cant_examen = $("#examen_rad_cantidad" + ind).val();

    if (id_examen_rad === "") {
        bootbox.alert("Falta ingresar un examen de radiografía válido en esta fila");
        $("#examen_rad_cantidad" + ind).val("1");
        return false;
    }
    if (id_examen_rad !== "" && (!cant_examen || cant_examen <= 0)) {
        bootbox.alert("Debe ingresar una cantidad válida mayor a 0");
        $("#examen_rad_cantidad" + ind).val("1");
        return false;
    }
  });

  $("#examen_rad_cantidad" + ind).on('blur', function() {
    var id_examen_rad = $("#id_examen_rad" + ind).val();
    var cantidad = $(this).val().trim();

    if (id_examen_rad === "") {
        bootbox.alert("Primero debe seleccionar un examen de radiografía");
        $(this).val("");
        return false;
    }

    if (cantidad === "" || isNaN(cantidad) || parseInt(cantidad) <= 0) {
        bootbox.alert("Debe ingresar una cantidad válida mayor a 0");
        $(this).val("1");
        return false;
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


function AddProducto() {
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
  var batchSignatureProcessPoller = setInterval(function() {
    $.ajax({
      url: "<?php echo url()?>/sinTokens",
      type: "post",
      cache: false,
      dataType: 'json',
      data: $("#frmBloque").serialize(),
      success: function(completed) {
        if (completed) {
          location.href = '/all_citas_medico_atenciones';
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

var ppPlazoVencido = false;

function ppBloquearProcProgramado() {
  var estabaAbierto = $('#btnNuevoProcProgramado').attr("flag") == 1;
  if (estabaAbierto) {
    agregarProcedimientoProgramado();
  }
  ppPlazoVencido = true;
  $('#btnNuevoProcProgramado').addClass('disabled').attr({
    disabled: 'disabled',
    title: 'Solo se pueden agregar procedimientos programados el mismo día de la atención'
  });
  if (estabaAbierto) {
    Swal.fire('Plazo vencido', 'Ya no se pueden agregar procedimientos programados: terminó el día de la atención.', 'warning');
  }
}

$(document).ready(function() {
  var ppMsRestantes = Number($('#pp_ms_restantes').val());
  if (ppMsRestantes === 0) {
    ppBloquearProcProgramado();
  } else if (ppMsRestantes > 0) {
    setTimeout(ppBloquearProcProgramado, ppMsRestantes);
  }
});

function agregarProcedimientoProgramado() {
  var flagProcProgramado = $('#btnNuevoProcProgramado').attr("flag");

  if (flagProcProgramado == 0 && ppPlazoVencido) {
    return;
  }

  if (flagProcProgramado == 0) {
    $('#btnNuevoProcProgramado').attr("flag", 1).html("Ocultar Procedimiento Programado");
    $("#fsNuevoProcProgramado").show();
    $("#pp_activo").val(1);
    $("#divGuardar").show();
    $("#divFirmar").hide();
  } else {
    $('#btnNuevoProcProgramado').attr("flag", 0).html("Procedimiento Programado");
    $("#fsNuevoProcProgramado").hide();
    $("#pp_activo").val(0);
    if ($('#btnNuevoReceeta').attr("flag") != 1
      && $('#btnNuevoExamenLab').attr("flag") != 1
      && $('#btnNuevoExamenRad').attr("flag") != 1
      && $('#btnNuevaInterconsulta').attr("flag") != 1
      && $('#btnNuevaReferencia').attr("flag") != 1) {
      $("#divGuardar").hide();
      $("#divFirmar").show();
    }
  }
}

function AddProcedimientoProgramado() {
  var newRow = "";
  var ind = 0;
  $('.id_procedimiento_p').each(function() {
    var ind_tmp = Number($(this).data("id"));
    if (ind_tmp >= ind) {
      ind = ind_tmp + 1;
    }
  });
  newRow += '<div class="pp-item" style="margin-top:15px;">';
  newRow += '<div class="row">';
  newRow += '<div class="col-sm-7"><label>Procedimiento</label>';
  newRow += '<select name="nombre_procedimiento_p[]" id="nombre_procedimiento_p' + ind + '" class="form-control" style="width:100%"><option></option></select>';
  newRow += '<input type="hidden" class="id_procedimiento_p" id="id_procedimiento_p' + ind + '" name="id_procedimiento_p[]" value="" data-id="' + ind + '"></div>';
  newRow += '<div class="col-sm-1"><label>Cantidad</label>';
  newRow += '<input type="number" min="1" max="99" class="form-control procedimiento_cantidad_p" id="procedimiento_cantidad_p' + ind + '" name="procedimiento_cantidad_p[]" value="1"></div>';
  newRow += '<div class="col-sm-3"><label>Diagnostico</label>';
  newRow += '<select class="form-control select-diagnostico" id="miSelectPp' + ind + '" name="miSelectPp[]"></select></div>';
  newRow += '<div class="col-sm-1"><label>&nbsp;</label><br>';
  newRow += '<button type="button" class="btn btn-danger deleteFilaPp">ELIMINAR</button></div>';
  newRow += '</div>';
  newRow += '<div class="row" style="margin-top:10px;"><div class="col-sm-12"><label>Indicación del Procedimiento</label>';
  newRow += '<textarea class="form-control procedimiento_indicacion_p" rows="4" id="procedimiento_indicacion_p' + ind + '" name="procedimiento_indicacion_p[]" placeholder="--- Registrar indicaciones del procedimiento programado---"></textarea>';
  newRow += '</div></div>';
  newRow += '</div>';
  $('#tblSolicitudPp').append(newRow);

  var selectElement = document.getElementById('miSelectPp' + ind);
  listIdDiagnosticoObjetos.forEach(function(item, i) {
    var option = document.createElement('option');
    option.value = item.id_diagnostico;
    option.textContent = item.nombre;
    if (i == 0) option.setAttribute("selected", "selected");
    selectElement.appendChild(option);
  });

  var nivel = $('#id_nivel').val();
  var genero = ($('#nomsexo').val() == 'M') ? 1 : 2;
  var proceduresExceptions = ['99199.01', '93000', 'D0160'];
  var yaSolicitados = JSON.parse($('#pp_ids_solicitados').val() || '[]').map(Number);

  // Select2: al abrir lista los 10 primeros procedimientos del servicio; al escribir, filtra
  $("#nombre_procedimiento_p" + ind).select2({
    placeholder: '--- Seleccione procedimiento ---',
    allowClear: true,
    minimumInputLength: 0,
    width: '100%',
    language: {
      noResults: function() { return 'No se encontraron procedimientos'; },
      searching: function() { return 'Buscando...'; }
    },
    ajax: {
      url: '/pp_obtener_procedimiento_servicio',
      dataType: 'json',
      delay: 400,
      data: function(params) {
        return { buscar: params.term || '', nivel: nivel, genero: genero, id_servicio: $('#id_servicio').val() };
      },
      processResults: function(data) {
        var seleccionados = $(".id_procedimiento_p").not("#id_procedimiento_p" + ind).map(function() { return parseInt($(this).val()); }).get();
        var filtro = data.filter(function(element) {
          return !seleccionados.includes(element.id)
            && !yaSolicitados.includes(element.id)
            && !proceduresExceptions.includes(String(element.codigo).toUpperCase());
        });
        return { results: filtro.map(function(element) { return { id: element.id, text: element.label }; }) };
      }
    }
  }).on('select2:select', function(e) {
    $("#id_procedimiento_p" + ind).val(e.params.data.id);
  }).on('select2:unselect', function() {
    $("#id_procedimiento_p" + ind).val("");
  });
}

function eliminarProcedimientoProgramado(id_solicitud, id_procedimiento) {
  bootbox.confirm("¿Se eliminará el procedimiento programado de la solicitud, desea continuar?", function(result) {
    if (result == true) {
      $.ajax({
        url: "/pp_eliminar_procedimiento_solicitud",
        type: "POST",
        data: { _token: $('input[name="_token"]').val(), id_solicitud: id_solicitud, id_procedimiento: id_procedimiento },
        success: function(res) {
          if (res.success) {
            location.reload();
          } else {
            bootbox.alert(res.message);
          }
        },
        error: function() {
          bootbox.alert("No se pudo eliminar el procedimiento.");
        }
      });
    }
  });
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

  var id_ipress = $('#id_establecimiento').val();
  var id_consultorio = $('#id_servicio').val();
  var id_tiporeceta = $('#id_tipo_atencion').val();

  var id_farmacia = $('#id_farmacia_receta').val();
  var nivel = $('#id_nivel').val();
  var x = 0;
  var x = nro_interconsulta();
  var dni = $('#dni_beneficiario').val();
  var interconsulta = "NO";
    if (id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia != 18) {
      if(id_tiporeceta==2){
        id_consultorio=76;
      }
      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' +
          id_consultorio,
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

    if (id_tiporeceta == 5) {
      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/110',
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
      if (response == "3") {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "No puedes invalidar, ya cuenta con Cita de Reevaluación",
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

  const grupos = [];

  document.querySelector('#id_perfil_preoperatorio').addEventListener('change', function() {
    $("#tblExamenLab tbody").empty();

    let id_perfil_preoperatorio = this.value;

    if (parseInt(id_perfil_preoperatorio) > 0) {
      let nivel = $('#id_nivel').val();
      // let sexo = $('#nomsexo').val();
      let id_tipo_atencion = $('#id_tipo_atencion').val();
      let id_servicio = 26; // CENTRO QUIRURGICO
      let genero = getSexoGenero();

      $.ajax({
        url: '/obtener_laboratorio_convenio/all/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + id_servicio + '/' + id_perfil_preoperatorio,
        dataType: "json",
        success: function(data) {
          console.log(data);
          // let listaLaboratorioSelecionado = $(".id_laboratorio").map(function(){
          //   return parseInt($(this).val());
          // }).get();
          // let listaExamenLabFiltrada = data.filter((element) => !listaLaboratorioSelecionado.includes(element.id));

          if (data && Array.isArray(data)) {
            data.forEach((item, index) => {
              // const ind = $('#tblExamenLab tbody tr').length + 1;
              const ind = obtenerIndiceTblExamenLab();

              // Crear una nueva fila para cada examen
              const newRow = `
                <tr>
                  <td>
                    <input type="text" name="nombre_examen_lab[]" id="nombre_examen_lab${ind}" class="input-sm" style="text-align: left; width: 100%;" value="${item.label || ''}" readonly />
                    <input type="hidden" name="id_examen_lab[]" id="id_examen_lab${ind}" value="${item.id}" class="id_laboratorio" data-id="${ind}" />
                    <input type="hidden" name="cod_lab[]" id="cod_lab${ind}" value="${item.codigo_procedimiento || ''}" />
                    <input type="hidden" class="examenRepetida" id="examenRepetida${ind}" name="examenRepetida[]" value="" data-id="${ind}" />
                    <input type="hidden" name="id_categoria_examen_lab[]" id="id_categoria_examen_lab${ind}" value="${item.id_categoria || ''}" />
                  </td>
                  <td>
                    <input type="number" name="examen_lab_cantidad[]" id="examen_lab_cantidad${ind}" class="form-control" value="1" readonly />
                  </td>
                  <td>
                    <select name="miSelectLab[]" id="miSelectLab${ind}" class="form-control select-diagnostico">
                      <option value="">-- Seleccionar --</option>
                    </select>
                  </td>

                  <td>
                  </td>
                  <td>
                  </td>
                  <td>
                  </td>

                  <td>
                    <button type="button" class="btn btn-danger btn-xs deleteFila" style="margin-left: 4px">
                      <i class="fa fa-times"></i> Eliminar
                    </button>
                  </td>
                </tr>
              `;

              // Agregar la nueva fila a la tabla
              $('#tblExamenLab tbody').append(newRow);

              // Rellenar el select con los diagnósticos disponibles
              const select = document.getElementById(`miSelectLab${ind}`);
              if (Array.isArray(listIdDiagnosticoObjetos)) {
                listIdDiagnosticoObjetos.forEach(diagnostico => {
                  const option = document.createElement('option');
                  option.value = diagnostico.id_diagnostico;
                  option.textContent = diagnostico.nombre;
                  select.appendChild(option);
                });
              }

              if (item.id) {
                // validaExamenSeleccionado(item.id);
                // consultaWsResultExamenSeleccionado(item.id);
              }
            });
          } else {
              console.error('No se recibieron datos válidos de la ruta obtener_laboratorio_convenio.');
          }
        },
        error: function (xhr, status, error) {
          console.error('Error al obtener los exámenes:', error);
        }

      });
    }
  });

  // RUA Laboratorio Checklist
  function cargarDiagnosticosTodos() {
    $("#contenedorDX").html("Cargando...");
    const nivel = $('#id_nivel').val();
    // const sexo = $('#nomsexo').val();
    const id_tipo_atencion = $('#id_tipo_atencion').val();
    const id_servicio = $('#id_servicio').val();
    const genero = getSexoGenero();

    $.ajax({
      url: '/obtener_laboratorio_convenio/all/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + id_servicio,
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        // console.log("ITEM EJEMPLO:", data[0]); 
        let total = data.length;
        let clase = 'col1';
        let modalClase = 'modal-col1';

        if (total > 5 && total <= 10) { clase = 'col2'; modalClase = 'modal-col2'; }
        else if (total > 10 && total <= 20) { clase = 'col3'; modalClase = 'modal-col3'; }
        else if (total > 20) { clase = 'col4'; modalClase = 'modal-col4'; }

        $("#contenedorDX").removeClass("col1 col2 col3 col4").addClass(clase);
        $("#modalDX").removeClass("modal-col1 modal-col2 modal-col3 modal-col4").addClass(modalClase);

        let grupos = {};
        data.forEach(function(item) {
            let idCat = item.id_categoria || "0";
            let nombreCat = item.categoria_nombre || "SIN CATEGORÍA";
            if (!grupos[idCat]) {
              grupos[idCat] = { nombre: nombreCat, items: [] };
            }
            grupos[idCat].items.push(item);
        });

        let html = "";
        Object.keys(grupos).sort().forEach(function(idCat) {
          html += `
            <h4 style="margin: 6px 0 4px 0 !important;font-weight: 600;color: #3c763d;border-bottom: 1px solid white;padding-bottom: 2px;padding-top: 12px;font-size: 15px;text-transform: uppercase;">
                ${grupos[idCat].nombre}
            </h4>
          `;
          grupos[idCat].items.forEach(function(item) {
            html += `
              <label style="display:block; margin-left:10px;">
                <input type="checkbox" 
                  class="chk_rualab"  
                  name="chk_rualab[]" 
                  data-id="${item.id}"
                  data-codigo="${item.codigo_procedimiento || ''}"
                  data-categoria="${item.id_categoria || ''}"
                  data-label="${item.label || ''}"
                  style="transform: scale(1.4); margin-right:8px;">
                ${item.label || ''}
              </label>
            `;
          });
        });

        $("#contenedorDX").html(html);

        const unionMap = {
          /*"82803": "36600",
          "36600": "82803",
          "82575": "82565",
          "82565": "82575",
          "82043.01": "84999.09",
          "84999.09": "82043.01",
          "89190": "87999",
          "87999": "89190",
          "87070": "87184",
          "87184": "87070",
          "87163": "87184",
          "87184": "87163",
          "87040.01": "87184",
          "87184": "87040.01",
          "87040.03": "87184",
          "87184": "87040.03",
          "87045": "87184",
          "87184": "87045",
          "87115": "84999.13",
          "84999.13": "87115"*/
        };

        $(".chk_rualab").change(function() {
          const codigo = $(this).data("codigo");
          const union = unionMap[codigo];
          if (union) {
            if (Array.isArray(union)) {
              union.forEach(u => {
                const target = $(`.chk_rualab[data-codigo='${u}']`);
                if (target.length) target.prop('checked', $(this).prop('checked'));
              });
            } else {
              const target = $(`.chk_rualab[data-codigo='${union}']`);
              if (target.length) target.prop('checked', $(this).prop('checked'));
            }
          }
        });

      }
    });
  }

  $('#modalDX').on('show.bs.modal', function () {
    cargarDiagnosticosTodos();
  });

  $("#guardarRUALab").on('click', function () {

    const checked = $("input[name='chk_rualab[]']:checked");
    if (checked.length === 0) {
      $("#modalDX").modal('hide');
      return;
    }

    const tblExamenLab_cantidad_tr = $('#tblExamenLab tbody tr').length;
    const tblAuxExamenLab_cantidad_tr = $('#tblAuxExamenLab tbody tr').length;
    let examenExiste = false;

    checked.each(function () {
      const $this = $(this);
      const label = $this.data('label');
      const id_examen_lab = $this.data('id');
      const codigo = $this.data('codigo');
      const categoria = $this.data('categoria');

      examenExiste = false;

      if (tblAuxExamenLab_cantidad_tr > 0) {
        const ordenlabAux_tr = $('#tblAuxExamenLab tbody').children('tr');

        let fila_ordenlabAux, id_examen_lab_table_aux;
        
        for (let i = 0; i < tblAuxExamenLab_cantidad_tr; i++) {
          fila_ordenlabAux = ordenlabAux_tr[i];

          id_examen_lab_table_aux = $(fila_ordenlabAux).find('.id_lna_examen').val();

          if (parseInt(id_examen_lab) === parseInt(id_examen_lab_table_aux)) {
            examenExiste = true;
            break;
          }

        }
      }

      if (!examenExiste) {
        if (tblExamenLab_cantidad_tr > 0) {
          const ordenlab_tr = $('#tblExamenLab tbody').children('tr');

          let fila_ordenlab, id_examen_lab_table;
          
          for (let i = 0; i < tblExamenLab_cantidad_tr; i++) {
            fila_ordenlab = ordenlab_tr[i];

            id_examen_lab_table = $(fila_ordenlab).find('input[name="id_examen_lab[]"]').val();

            if (parseInt(id_examen_lab) === parseInt(id_examen_lab_table)) {
              examenExiste = true;
              break;
            }

          }
        }
      }

      if ($("#tblExamenLab tbody tr[data-id='" + id_examen_lab + "']").length > 0) {
        return true; 
      }

      if (!examenExiste) {
        const ind = obtenerIndiceTblExamenLab();

        const newRow = `
          <tr>
            <td>
              <input type="text" name="nombre_examen_lab[]" id="nombre_examen_lab${ind}" class="input-sm" style="text-align: left; width: 100%;" value="${label}" readonly />
              <input type="hidden" name="id_examen_lab[]" id="id_examen_lab${ind}" value="${id_examen_lab}" class="id_laboratorio" data-id="${ind}" />
              <input type="hidden" name="cod_lab[]" id="cod_lab${ind}" value="${codigo}" />
              <input type="hidden" class="examenRepetida" id="examenRepetida${ind}" name="examenRepetida[]" value="" data-id="${ind}" />
              <input type="hidden" name="id_categoria_examen_lab[]" id="id_categoria_examen_lab${ind}" value="${categoria}" />
            </td>
            <td>
              <input type="number" name="examen_lab_cantidad[]" id="examen_lab_cantidad${ind}" class="form-control" value="1" readonly />
            </td>
            <td>
              <select name="miSelectLab[]" id="miSelectLab${ind}" class="form-control select-diagnostico">
                <option value="">-- Seleccionar --</option>
              </select>
            </td>

            <td>
            </td>
            <td>
            </td>
            <td>
            </td>

            <td>
              <button type="button" class="btn btn-danger btn-xs deleteFila" style="margin-left: 4px">
                <i class="fa fa-times"></i> Eliminar
              </button>
            </td>
          </tr>
        `;

        // Agregar la nueva fila a la tabla
        $("#tblExamenLab tbody").append(newRow);

        const select = document.getElementById(`miSelectLab${ind}`);
        if (Array.isArray(listIdDiagnosticoObjetos) && select) {
          listIdDiagnosticoObjetos.forEach(diagnostico => {
            const option = document.createElement('option');
            option.value = diagnostico.id_diagnostico;
            option.textContent = diagnostico.nombre;
            select.appendChild(option);
          });
        }

        if (id_examen_lab) {
          // validaExamenSeleccionado(id_examen_lab);
          // consultaWsResultExamenSeleccionado(id_examen_lab);
        }
      }

    });

    $("#modalDX").modal('hide');
    $("input[name='chk_rualab[]']").prop('checked', false);
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

  .panel-sm .panel-body .row {
    margin-bottom: 2px;
  }

  .panel-sm .panel-body p {
    margin: 0;
    line-height: 1.2;
  }

  .panel-sm .panel-body label {
    margin-bottom: 0;
    line-height: 1.2;
  }

  .panel-body {
      padding: 10px;
  }

  .titulo-seccion {
      margin-bottom: 1px;
      margin-top: 3px;
  }

  .titulo-seccion:first-child {
      margin-top: 0;
  }

  .titulo-label {
      font-weight: 700;
      font-size: 1.2rem;
      color: #000;
      display: block;
      margin: 0;
      padding-left: 6px;
  }

  .titulo-label span {
      margin-left: 5px;
  }

  .row {
      margin-bottom: 0px;
  }

  .size-titulo {
      font-size: 1.2rem;
      font-weight: 700;
      text-align: center;
      padding-right: 5px;
      padding-left: 21px;
  }

  .size-titulo label {
      margin: 0;
  }

  .size-titulo label span {
      margin-left: 5px;
  }

  .size-titulo p {
      margin: 0;
      padding: 0;
  }

  .size-contenido {
      font-size: 1.2rem;
      text-align: left;
      padding-left: 5px;
  }

  .text-left {
      text-align: left;
  }

  .panel-body > .row:not(.titulo-seccion) {
      padding: 1px 0;
  }

  .label-delgado{
    font-weight: 300;
    font-size: 12px;
    padding: 2px 8px;
    line-height: 1.2;
   }

  @media (max-width: 768px) {
      .size-titulo,
      .size-contenido {
          margin-bottom: 2px;
      }
      .size-titulo {
          padding-right: 0;
          padding-left: 24px;
          text-align: left;
      }
      .size-contenido {
          padding-left: 10px;
      }
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
            <h3 class="panel-title">MÓDULO DE GESTIÓN DE ATENCIONES MEDICAS</h3>
          </div>

          <div class="panel-body">

            <form class="form-horizontal" method="post" id="frmBloque"
              action="{{ route('registrar_receta_prestacion')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
              <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacion" id="id_prestacion">

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
              <input type="hidden" value="{{ $prestacion->fecha_atencion }}" name="fecha_hora_atencion">
              <input type="hidden" name="has_intervencion_quirurgica" value="{{$es_primera_atencion_cqx ? '1' : '0'}}">
              <input type="hidden" id="id_rua_imagenes" name="id_rua_imagenes" value="">

              <?php
              $flag_firmar = 0;
              if($flag_pendinte_firma == 1) {
                $flag_firmar = 1;
              }
            ?>

            <div class="row" style="display: flex;flex-wrap:wrap;">
             <div class="col-md-6" style="padding-bottom: 1rem">
               <fieldset style="height: 100%!important;">
                  <div class="panel panel-info panel-bordered panel-sm" style="height: 100%!important;margin-bottom: 0">
                    <div class="panel-heading">
                       <h3 class="panel-title size-titulo">INFORMACIÓN DEL PACIENTE</h3>
                    </div>
                    <div class="panel-body" id="collapseprestacionespaciente">
                      <div class="row titulo-seccion">
                          <div class="col-md-12">
                              <label class="titulo-label">DATOS DEL PACIENTE:</label>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>TIPO Y N° DE DOC</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->tipo_doc_ident?> - <?php echo $prestacion->nro_doc_ident?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>APELLIDOS Y NOMBRES</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->asegurado_paterno . ' ' . $prestacion->asegurado_materno . ' ' . $prestacion->asegurado_nombre; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>FECHA DE NACIMIENTO /SEXO</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->fecha_nac?> / <?php echo $prestacion->sexo?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>N ° HISTORIA CLINICA</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->nro_historia?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>TELEFONO / TELEFONO 2</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->telefono?> / <?php echo $prestacion->telefono2?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>DOMICILIO</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->domicilio?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>PARENTESCO</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->parentesco?>
                        </div>
                      </div>
                      <div class="row" style="margin-bottom:8px;">
                        <div class="col-md-3 text-left size-titulo">
                          <label>SITUACION</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php /*echo $prestacion->situacion_paciente*/echo $prestacion->situacion_titular?>
                        </div>
                      </div>
                      <div class="row titulo-seccion">
                          <div class="col-md-12">
                              <label class="titulo-label">DATOS DEL TITULAR:</label>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label> N° DE DOC / N° DE CIP</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->dni_titular . ' / ' . $prestacion->cip;?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>APELLIDOS Y NOMBRES</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->paterno_titular . ' ' . $prestacion->materno_titular . ' ' . $prestacion->nombre_titular;?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>GRADO</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->grado_titular?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>DISCAPACIDAD</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php if($prestacion->discapacidad==1): echo 'SI'; else: echo 'NO'; endif;?>
                        </div>
                      </div>
                      @if($prestacion->discapacidad_carnet==1)
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>CARNET CONADIS</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php echo $prestacion->nro_carnet_conadis?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3 text-left size-titulo">
                          <label>COLOR CARNET</label>
                        </div>
                        <div class="col-md-1 text-right size-titulo">
                          :
                        </div>
                        <div class="col-md-7 size-contenido">
                          <?php if($prestacion->color_carnet_conadis==1): echo 'AMARILLO'; else: echo 'CELESTE'; endif; ?> 
                        </div>
                      </div>
                      <div class="row">
                      </div>
                      @else
                      <div class="row">
                      </div>
                      @endif
                    </div>
                  </div>
               </fieldset>
             </div>
             
             <div class="col-md-6" style="padding-bottom: 1rem">
              <div class="row" style="height: 100%!important;">
                <div class="col-md-12" style="height: 100%!important;">
                  <fieldset style="height: 100%!important;">
                    <div class="panel panel-info panel-bordered panel-sm" style="height: 100%!important;margin-bottom: 0">
                      <div class="panel-heading">
                        <h3 class="panel-title size-titulo">DATOS DE LA ATENCIÓN ADICIONAL</h3>
                      </div>
                      <div class="panel-body">
                        <div class="row titulo-seccion">
                          <div class="col-md-12">
                              <label class="titulo-label">DATOS DE LA ATENCIÓN:</label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                            <label>COD.SITEDS SUSALUD</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                            <p class="label label-warning label-delgado" style="font-weight: lighter; font-size: 13px;">
                              <?php echo $prestacion->codigo_siteds?>
                            </p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                            <label>NOMBRE DE LA IPRESS</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                            <?php echo $prestacion->establecimiento?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                            <label>TIPO DE ATENCIÓN</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                            <?php echo $prestacion->tipo_atencion?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                            <label>FECHA DE ATENCIÓN</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                            <?php echo $prestacion->fecha_atencion?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                            <label>SERVICIO</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                            <?php echo $prestacion->servicio?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                            <label>CÓDIGO UPSS</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                            <?php echo $prestacion->codigo_upss?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                            <label>MODALIDAD</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                            <?php 
                              if($prestacion->id_modalidad==1){
                                echo "PRESENCIAL";
                              }
                              else
                              {
                                if($prestacion->id_modalidad==2){
                                 echo "VIRTUAL";
                                } 
                                else
                                {
                                  echo "EXTRAMURAL";
                                }
                              }
                            ?>
                          </div>
                        </div>
                        <?php 
                       
                          switch ($prestacion->id_tipo_atencion) {
                            case '1': //AMBULATORIO
                              $descripcion='SERVICIO'; $descripcion2='CONSULTORIO';break;
                            
                            case '2': //EMERGENCIA
                              $descripcion='PRIORIDAD'; $descripcion2='TOPICO';break;

                            case '3': //HOSPITALIZACION
                              $descripcion='SALA'; $descripcion2='SERVICIO';break;

                            case '7': //URGENCIA
                              $descripcion='URGENCIA'; $descripcion2='SERVICIO';break;
                          }
                           if($prestacion->id_modalidad!=3){
                        ?>
                        <div class="row" style="margin-bottom:8px;">
                          <div class="col-md-3 text-left size-titulo">
                            <label>CONSULTORIO</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                            <?php echo $prestacion->consultorio?>                          
                          </div>
                        </div>
                        <?php } ?>
                        @if($prestacion->id_tipo_atencion==3)
                          <div class="row">
                            <div class="col-md-3 text-left size-titulo">
                              <label>PABELLON</label>
                            </div>
                            <div class="col-md-1 text-right size-titulo">
                              :
                            </div>
                            <div class="col-md-7 size-contenido">
                              <p><?php //echo $prestacion->pabellon?></p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-3 text-left size-titulo">
                              <label>PISO</label>
                            </div>
                            <div class="col-md-1 text-right size-titulo">
                              :
                            </div>
                            <div class="col-md-7 size-contenido">
                              <p><?php //echo $prestacion->cuarto?></p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-3 text-left size-titulo">
                              <label>CAMA</label>
                            </div>
                            <div class="col-md-1 text-right size-titulo">
                              :
                            </div>
                            <div class="col-md-7 size-contenido">
                              <p><?php //echo $prestacion->cama?></p>
                            </div>
                          </div>
                        @endif
                        <div class="row titulo-seccion">
                          <div class="col-md-12">
                              <label class="titulo-label">RESPONSABLE DE ATENCIÓN:</label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                              <label>TIPO Y N° DE DOC</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                            <?php echo $prestacion->tipo_doc_ident ?> - <?php echo $prestacion->dni ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                              <label>APELLIDOS Y NOMBRES</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                              <?php echo $prestacion->medico_paterno . ' ' . $prestacion->medico_materno . ' ' . $prestacion->medico_nombre; ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                            <label>PROFESIÓN / ESPECIALIDAD</label>
                          </div>
                          <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                            <?php echo $prestacion->profesion . ' / ' . $prestacion->especializacion?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 text-left size-titulo">
                            <label>N° COLEGIATURA / N° RNE</label>
                          </div>
                           <div class="col-md-1 text-right size-titulo">
                            :
                          </div>
                          <div class="col-md-7 size-contenido">
                              <?php echo $prestacion->colegiatura . ' / ' . $prestacion->rne;?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>  
              </div>
             </div>
            </div>

            <?php if(isset($cita->id_referencia)):?>     
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>DATOS DE LA REFERENCIA</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <table class="displa" id="tblDiagnostico111" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                          <th width="10%"></th>
                            <th width="30%">Establecimiento Origen</th>
                            <th width="30%">Servicio Origen</th>
                            <th width="30%">Motivo de Referencia</th>
                          </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                          <tr>
                            <td></td>
                            <td><?php echo $referencias->nombre_establecimiento?></td>
                            <td><?php echo $referencias->nombre_servicio?></td>
                            <td><?php echo $referencias->observacion_referencia?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </fieldset>        
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>DIAGNOSTICOS DE REFERENCIA</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <table class="displa" id="tblDiagnostico11" cellspacing="0" width="100%" style="margin-top:5px;">
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
                          <?php foreach($diagnostico_prestacion_referencia as $row):
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
                        <b>PROCEDIMIENTOS DE REFERENCIA</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <table class="display" id="tblProcedimiento1" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                            <th width="20%">Codigo</th>
                            <th width="60%">Nombre</th>
                            <th width="20%">Observacion</th>
                          </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                          <?php foreach($procedimientos_refencia as $row):?>
                          <tr>
                            <td><?php echo $row->codigo?></td>
                            <td><?php echo $row->descripcion?></td>
                            <td><?php echo $row->observacion?></td>
                          </tr>
                          <?php endforeach;?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </fieldset>
            <?php endif; ?> 
            
          

                      
                      <?php if($prestaciones->flag_ref_cref == '2'): ?>
                      <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>CONTRAREFERENCIA</b>
                                      </div>
                                    </div>
                                    <?php if ($prestaciones->flag_ref_cref == '2'): ?>
                                    <div class="panel-body">
                                        <div class="panel panel-info panel-bordered panel-sm panel-body">
                                            <div class="form-group">
                                              <div class="col-sm-1" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                <label>CONTRAREFERIR CONSULTA</label>
                                              </div>
                                              <div class="col-sm-1" style="margin-left:0px;padding-left:0px">
                                                 <button type="button" id="addreferenciacontra" class="btn btn-lg btn-toggle active"
                                                  data-toggle="button" aria-pressed="false" autocomplete="off" disabled>
                                                  <div class="handle"></div>
                                                </button>
                                              </div>
                                              <div id="fsReferenciacontra" >
                                                <div class="col-md-2"
                                                  style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                                  <label>SERVICIO DE CONTRAREFERENCIA</label>
                                                </div>
                                                <div class="col-md-3" style="margin-left:0px;padding-left:0px">
                                                  <select class="form-control input-sm" id="servicio_referencia" name="servicio_referencia" disabled>
                                                    <option value="" selected=""> <?php echo $dato_servicio_ref ?></option>
                                                  </select>
                                                </div>
                                                <div class="col-md-2"
                                                  style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                                  <label>COMENTARIO DE LA REFERENCIA</label>
                                                </div>
                                                <div class="col-md-3" style="margin-left:0px;padding-left:0px">
                                                    <textarea class="form-control" cols="5" id="texto_referencia" name="texto_referencia"
                                                        placeholder="Antecedente Familiar" disabled> <?php echo $dato_texto_obs ?></textarea>
                                                </div>
                                              </div>

                                            </div>
                                          </div>
                                     </div>
                                     <?php else: ?>
                                      <div class="panel-body">
                                        <div class="panel panel-info panel-bordered panel-sm panel-body">
                                            <div class="form-group">
                                              <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                <label>CONTRAREFERIR CONSULTA</label>
                                              </div>
                                              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                <button type="button" id="addreferenciacontra" class="btn btn-lg btn-toggle"
                                                  data-toggle="button" aria-pressed="false" autocomplete="off" disabled>
                                                  <div class="handle"></div>
                                                </button>
                                              </div>
                                            </div>
                                          </div>
                                     </div>
                                     <?php endif; ?>
                                  </div>
                      </fieldset>
                      <?php else: ?>
                      <?php endif; ?>

                      <?php if($prestacion->id_consultorio == 10): ?>
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm panel-body">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                                <b>INFORMACIÓN DEL PACIENTE</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="row">
                              <div class="col-md-3">
                                <label>PACIENTE CON ALGUNA DISCAPACIDAD</label>
                                <span style="margin-left: 8px;"></span><?php echo ($prestacion->discapacidad != "") ? "SI" : "NO"; ?></span>
                              </div>
                              <div class="col-md-3">
                                <label>PACIENTE CON CARNET CONADIS</label>
                                <span style="margin-left: 8px;"><?php echo ($prestacion->discapacidad_carnet != "") ? "SI" : "NO"; ?></span>
                              </div>
                              <div class="col-md-3">
                                <label>COLOR CARNET</label>
                                <span style="margin-left: 8px;"><?php echo ($prestacion->color_carnet_conadis == 1) ? "AMARILLO" : "CELESTE"; ?></span>
                              </div>
                              <div class="col-md-3">
                                <label>N CARNET</label>
                                <span style="margin-left: 8px;"><?php echo ($prestacion->nro_carnet_conadis)?></span>
                              </div>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="row">
                              <div class="col-md-3">
                                <label>PRESC. PRODUCTO DE APOYO</label>
                                <span style="margin-left: 8px;"></span><?php echo ($prestacion->presc_producto_apoyo != "") ? "SI" : "NO"; ?></span>
                              </div>
                              <div class="col-md-3">
                                <label>USA PRODUCTO DE APOYO</label>
                                <span style="margin-left: 8px;"><?php echo ($prestacion->usa_product_apoyo != "") ? "SI" : "NO"; ?></span>
                              </div>

                              <div class="col-md-3">
                                <label>TIPO DE PRODUCTO</label>
                                <span style="margin-left: 8px;">
                                  <?php
                                    $tipos = [1 => "SILLA DE RUEDAS", 2 => "PRÓTESIS", 3 => "ÓRTESIS", 4 => "AUDÍFONOS", 5 => "BASTONES", 6 => "OTRO"];
                                    echo isset($tipos[$prestacion->tipo_producto]) ? $tipos[$prestacion->tipo_producto] : "";
                                  ?>
                                </span>
                              </div>
                              <div class="col-md-3">
                                <label>N DE PRODUCTO</label>
                                <span style="margin-left: 8px;"><?php echo ($prestacion->nro_tipo_producto)?></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      <?php endif; ?>
                      
                      <fieldset id="fsAntecedente">
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                                <b>ANTECEDENTES</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="row">
                              <div class="col-md-6">
                                <label style="display:flex; align-items:center;"><b>ANTECEDENTE<b></label>
                                <p style="font-weight: normal;">{!! nl2br(e($prestacion->antecedente)) !!}</p>
                              </div>
                            <!-- </div>
                          </div> -->
                          <!-- <div class="panel-body"> -->
                              <div class="col-md-6">
                                <label style="display:flex; align-items:center;"><b>ANTECEDENTE FAMILIAR<b></label>
                                  <p style="font-weight: normal;">{!! nl2br(e($prestacion->antecedente_familiar)) !!}</p>
                              </div>
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
                              <label>MOTIVO DE VISITA</label>
                              <p style="font-weight: normal;">{!! nl2br(e($prestacion->motivo_visita)) !!}</p>
                          </div>
                          <div class="panel-body">
                              <label>TIEMPO DE ENFERMEDAD - RELATO CRONOLOGICO</label>
                                <p style="font-weight: normal;">{{$prestacion->tiempo_enfermedad}}</p>
                                <p style="font-weight: normal;">{!! nl2br(e($prestacion->anamnesis)) !!}</p>
                                <!-- <p>{{$prestacion->anamnesis}}</p> -->
                          </div>
                          <div class="panel-body">
                            <label>EXAMENES AUXILIARES</label>
                            <p style="font-weight: normal;"><?php echo $prestacion->examen_auxiliar?></p>
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
                      @if(Auth::user()->id_establecimiento==75)
                        @if($prestacion->id_consultorio == 28 || $prestacion->id_consultorio == 106 || $prestacion->id_consultorio == 107)
                          <div class="panel-body">
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                <div class="panel-title text-center">
                                  <b>ASPECTOS GENERALES</b>
                                </div>
                              </div>
                              <div class="panel-body">
                                <p><?php 
                                    $aux_aspectos_generales = $prestacion->aspectos_generales;
                                    if (!empty($aux_aspectos_generales)) {
                                      echo str_replace(chr(13), '<br>', $prestacion->aspectos_generales);
                                    }
                                  ?>
                                </p>
                              </div>
                            </div>
                          </div>
                        @endif
                      @endif
                        @if($prestacion->temperatura>0)
                          <div class="panel-body">
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                <div class="panel-title text-center">
                                  <b>INFORMACIÓN DE SIGNOS VITALES</b>
                                </div>
                              </div>
                              <div class="panel-body">
                                <div class="form-group">
                                  <div class="col-md-2">
                                    <label>TEMPERATURA ( °C) &nbsp;</label>
                                    <p><?php echo $prestacion->temperatura?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>TALLA (cm) &nbsp;</label>
                                    <p><?php echo $prestacion->talla_cm?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>PESO (Kg) &nbsp;</label>
                                    <p><?php echo $prestacion->peso_kg?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>IMC &nbsp;</label>
                                    <p>
                                    <?php
                                      if($prestacion->talla_cm!=0):
                                       echo ($prestacion->peso_kg / (($prestacion->talla_cm * $prestacion->talla_cm)*(0.01*0.01)));
                                      endif;
                                    ?>
                                    </p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>PESO IDEAL &nbsp;</label>
                                    <p><?php echo 45+(($prestacion->peso_kg-150)*3/4)?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>Sa02 (%) &nbsp;</label>
                                    <p><?php echo $prestacion->sa02_porcentaje?></p>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-md-2">
                                    <label>TAMAÃ‘O DE CINTURA (cm)</label>
                                    <p><?php echo $prestacion->tamano_cintura_cm?></p>
                                  </div>
                                  <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                    <label>FREC. CARDIACA FETAL(/min)</label>
                                    <p><?php echo $prestacion->frecuencia_cardiaca_fetal?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>PRESIÓN ARTERIAL &nbsp;</label>
                                    <div class="row">
                                      <div class="col-xs-5">                          
                                        <p><?php echo $prestacion->presion_arterial_x?></p>
                                      </div>  
                                        <div class="col-xs-2">
                                           <label>/</label> 
                                        </div>
                                      <div class="col-xs-5">
                                        <p><?php echo $prestacion->presion_arterial_y?></p>
                                      </div>  
                                    </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-md-2">
                                    <label>FREC.RESPIRATORIA(/min)</label>
                                    <p><?php echo $prestacion->frecuencia_respiratoria?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>FREC. CARDIACA (/min) &nbsp;</label>
                                    <p><?php echo $prestacion->frecuencia_cardiaca?></p>
                                  </div>
                                  <!--div class="col-md-2">
                                    <label>FREC. CARDIACA (/min) &nbsp;</label>
                                    <p><?php //echo $prestacion->frecuencia_cardiaca?></p>
                                  </div-->
                                  <div class="col-md-2">
                                    <label>Fi02 (%) &nbsp;</label>
                                    <p><?php echo $prestacion->fi02?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                        @if(($cita->id_establecimiento==76 &&  $cita->id_servicio == 13))
                          @if($datos_oftalmo!=null)
                            <div class="panel-body">
                              <label>DATOS DE OFTALMOLOGIA</label>
                              <div class="panel panel-info panel-bordered panel-sm" style="padding-bottom: 0px">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">1. AV</b></label>
                                    </div>
                                    <div class="col-md-12">
                                      <table class="table table-striped table-list-search">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center;"> </th>
                                            <th style="text-align: center;">OD</th>
                                            <th style="text-align: center;">OI</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td style="width: 30%;">
                                              <label style="font-size: 12px; padding-right: 15px;">AV SC</label>
                                            </td>
                                            <td>

                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->od_av_sc}} @endif">
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->oi_av_sc}} @endif">
                                            </td>
                                          </tr>
                                          <tr>
                                            <td style="width: 30%;">
                                              <label style="font-size: 12px;padding-right: 15px;">AV CC</label>
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->od_av_cc}} @endif">
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->oi_av_cc}} @endif">
                                            </td>
                                          </tr>
                                          <tr>
                                            <td style="width: 30%;">
                                              <label style="font-size: 12px;padding-right: 15px;">AV CAE</label>
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->od_av_cae}} @endif">
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->oi_av_cae}} @endif">
                                            </td>
                                          </tr>
                                          <tr>
                                            <td style="width: 30%;">
                                              <label style="font-size: 12px;padding-right: 15px;">PIO</label>
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->od_pio}} @endif">
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->oi_pio}} @endif">
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">2. REFRACCION</b></label>
                                    </div>
                                    <div class="col-md-1">
                                      <br><label style="font-size: 14px;">OD LEJOS</label>
                                    </div>
                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">SPH</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_lejos_sph}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">CIL</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_lejos_cil}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">EJE</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_lejos_eje}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">DIP</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_lejos_dip}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">AV</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_lejos_av}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <br><label style="font-size: 14px; margin-bottom: 0px !important;">OI LEJOS</label>
                                    </div>
                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">SPH</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_lejos_sph}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">CIL</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_lejos_cil}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">EJE</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_lejos_eje}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">DIP</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_lejos_dip}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">AV</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_lejos_av}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <br><label style="font-size: 14px; margin-bottom: 0px !important;">OD CERCA</label>
                                    </div>
                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">SPH</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_cerca_sph}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">CIL</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_cerca_cil}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">EJE</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_cerca_eje}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">DIP</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_cerca_dip}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-1">
                                      <br><label style="font-size: 14px; margin-bottom: 0px !important;">OI CERCA</label>
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">SPH</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cerca_sph}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">CIL</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cerca_cil}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">EJE</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cerca_eje}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">DIP</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cerca_dip}}@endif">
                                    </div>

                                    <div class="col-md-1"><br>
                                      @if($datos_oftalmo!=null)
                                      <a href="/imprimir_oftalmo/{{$datos_oftalmo->prestacion_id}}" aria-hidden="true"
                                        title="PDF" class="btn btn-danger btn-xs" target="_blank">
                                        <i class="fa fa-file-pdf-o"></i>
                                      </a>
                                      @endif
                                    </div>
                                    <div class="col-md-3" style="padding-top: 10px">
                                      <table>
                                        <tr>
                                          <td>
                                            <label
                                              style="font-size: 12px; margin-bottom: 0px !important; padding-right: 15px;">ADD</label>
                                          </td>
                                          <td>
                                            <input type="text" class="form-control" disabled
                                              value="@if($datos_oftalmo!=null){{$datos_oftalmo->refraccion_add}}@endif">
                                          </td>
                                        </tr>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>



                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">3. MOTILIDAD OCULAR</b></label>
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_1}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_2}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_3}}@endif">
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br><img src="../../images/oftalmologia/motalidad.png" width="105" height="auto">
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_4}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_5}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_6}}@endif">
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_1}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_2}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_3}}@endif">
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br>
                                      <img src="../../images/oftalmologia/motalidad2.png" width="105" height="auto">
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_4}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_5}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_6}}@endif">
                                    </div>
                                    <div class="col-md-6">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">TEST HIRSCHBERG-COVER
                                        TEST-OTROS</label><br>
                                      <textarea class="form-control" cols="30" rows="4"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->motilidad_test}}@endif</textarea>
                                    </div>
                                  </div>
                                </div>
                              </div>




                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">4. BIOMICROSCOPIA</b></label>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD PARPADO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_parpado}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD CONJUNTIVA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_conjuntiva}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD CORNEA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_cornea}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD CA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_ca}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD PUPILA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_pupila}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD CRISTALINO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_cristalino}}@endif</textarea>
                                    </div>
                                    <div class="col-md-12">
                                      <hr>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI PARPADO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_parpado}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI CONJUNTIVA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_conjuntiva}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI CORNEA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cornea}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI CA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_ca}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI PUPILA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_pupila}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI CRISTALINO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cristalino}}@endif</textarea>
                                    </div>
                                  </div>
                                </div>
                              </div>


                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">5. FONDO DE OJO</b></label>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD CRISTALINO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_cristalino_fondo}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD DISCO
                                        OPTICO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_disco_optico}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD DISCO
                                        MACULA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_disco_macula}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD RETINA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_retina}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD VASOS
                                        RETINIANOS</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_vasos_retinianos}}@endif</textarea>
                                    </div>
                                    <div class="col-md-12">
                                      <hr>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI CRISTALINO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cristalino_fondo}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI DISCO
                                        OPTICO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_disco_optico}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI DISCO
                                        MACULA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_disco_macula}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI RETINA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_retina}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI VASOS
                                        RETINIANOS</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_vasos_retinianos}}@endif</textarea>
                                    </div>
                                  </div>
                                </div>
                              </div>




                              <div class="panel panel-info panel-bordered panel-sm"
                                style="padding-left: 14px;padding-right: 14px; padding-bottom: 0px important;">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">6. IMAGENES AUXILIARES</b></label>
                                    </div>
                                    <div class="col-md-3 panel panel-info panel-bordered panel-sm">
                                      <br><label style="font-size: 14px;">BIOMETRIA</label>
                                      <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" disabled
                                          value="@if($datos_oftalmo!=null){{$datos_oftalmo->imag_biometria}}@endif">
                                        <span class="input-group-btn">
                                          @if($datos_oftalmo!=null)
                                          @if($datos_oftalmo->imag_biometria == null)
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @else
                                          <a href="{{ asset('uploads_oftalmo/') }}/{{$datos_oftalmo->imag_biometria}}"
                                            download class="btn btn-success btn-flat">DESCARGAR</a>
                                          @endif
                                          @else
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @endif
                                        </span>
                                      </div> <br>
                                    </div>
                                    <div class="col-md-3 panel panel-info panel-bordered panel-sm">
                                      <br><label style="font-size: 14px;">RETINOGRAFIA</label>
                                      <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" disabled
                                          value="@if($datos_oftalmo!=null){{$datos_oftalmo->imag_retinografia}}@endif">
                                        <span class="input-group-btn">
                                          @if($datos_oftalmo!=null)
                                          @if($datos_oftalmo->imag_retinografia == null)
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @else
                                          <a href="{{ asset('uploads_oftalmo/') }}/{{$datos_oftalmo->imag_retinografia}}"
                                            download class="btn btn-success btn-flat">DESCARGAR</a>
                                          @endif
                                          @else
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @endif
                                        </span>
                                      </div> <br>
                                    </div>
                                    <div class="col-md-3 panel panel-info panel-bordered panel-sm">
                                      <br><label style="font-size: 14px;">CAMPIMETRIA</label>
                                      <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" disabled
                                          value="@if($datos_oftalmo!=null){{$datos_oftalmo->imag_campimetria}}@endif">
                                        <span class="input-group-btn">
                                          @if($datos_oftalmo!=null)
                                          @if($datos_oftalmo->imag_campimetria == null)
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @else
                                          <a href="{{ asset('uploads_oftalmo/') }}/{{$datos_oftalmo->imag_campimetria}}"
                                            download class="btn btn-success btn-flat">DESCARGAR</a>
                                          @endif
                                          @else
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @endif
                                        </span>
                                      </div> <br>
                                    </div>
                                    <div class="col-md-3 panel panel-info panel-bordered panel-sm">
                                      <br><label style="font-size: 14px;">ECOGRAFÍA OCULAR</label>
                                      <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" disabled
                                          value="@if($datos_oftalmo!=null){{$datos_oftalmo->imag_ecografia_ocular}}@endif">
                                        <span class="input-group-btn">
                                          @if($datos_oftalmo!=null)
                                          @if($datos_oftalmo->imag_ecografia_ocular == null)
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @else
                                          <a href="{{ asset('uploads_oftalmo/') }}/{{$datos_oftalmo->imag_ecografia_ocular}}"
                                            download class="btn btn-success btn-flat">DESCARGAR</a>
                                          @endif
                                          @else
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @endif
                                        </span>
                                      </div> <br>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          @endif
                        @endif
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>EXAMEN FISICO</label>
                                <p style="font-weight: normal;"><?php echo $prestacion->examen_fisico?></p>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>EXAMEN CLINICO</label>
                              <p style="font-weight: normal;"><?php echo $prestacion->examen_clinico?></p>
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
                                    <input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->codigo }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent; font-weight: normal;" readonly>
                                    </td>
                                   <td>
                                 
                                  <input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->nombre }}" class=" input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent; font-weight: normal;" readonly>
                                 </td>
                                   <td>
                               <input name="id_tipo_diagnosticos['{{ $row->id_diagnostico }}']" id="" value="{{ $tipo_diagnostico }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent; font-weight: normal;" readonly>
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
                                        <td><input type="text" name="codigodeprod[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->codigo }}"  style="border: none; outline: none; background-color: transparent; font-weight: normal;" readonly></td>
                                             <td>
                                            <span style="font-weight: normal;">{{$row->nombre}}</span>
                                            <input name="id_procedimientos[{{ $row->cod }}]" id="idProcedimientos" class=" input-procedimiento form-control" value="{{$row->nombre}}" type="hidden" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                                    </td>
                                      <td><input type="text" name="cantidad_procedimientos[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->cantidad }}"  style="border: none; outline: none; background-color: transparent; font-weight: normal;" readonly></td>
                                       @if($id_prestacion>1910698)
                                       <td>
                                     <input name="iddiagnosticosxProcedimiento[{{ $row->id_diagnostico }}]" id="iddiagnosticosxProcedimiento" class="input-procedimiento form-control" disabled style="border: none; outline: none; background-color: transparent; font-weight: normal;" readonly
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
                      
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>PROCEDIMIENTO PROGRAMADO</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <input type="hidden" name="pp_activo" id="pp_activo" value="0">
                            <?php
                              $pp_ids_solicitados = array();
                              if ($detallesAgrupados) {
                                foreach ($detallesAgrupados as $grupo_pp) {
                                  if ($grupo_pp->where('estado', 0)->count() < $grupo_pp->count()) $pp_ids_solicitados[] = intval($grupo_pp->first()->id_procedimiento);
                                }
                                $pp_ids_solicitados = array_values(array_unique($pp_ids_solicitados));
                                $pp_solicitud_anterior = 0;
                              }
                            ?>
                            <input type="hidden" id="pp_ids_solicitados" value="{{ json_encode($pp_ids_solicitados) }}">
                            <?php
                              $pp_limite = \App\Models\SolicitudProgramacionProcedimientos::ppLimiteAgregar($prestacion->id);
                              $pp_ms_restantes = $pp_limite ? max(0, ($pp_limite->timestamp + 1 - time()) * 1000) : -1;
                            ?>
                            <input type="hidden" id="pp_ms_restantes" value="{{ $pp_ms_restantes }}">
                            @if ($detallesAgrupados)
                            @foreach ($detallesAgrupados as $detalle)
                              <?php
                                $pp_activos = $detalle->filter(function ($d) { return intval($d->estado) !== 0; });
                                if ($pp_activos->count() == 0) continue;
                                // Atendido (3): no se elimina nunca. Citado (2): primero se anula la cita del procedimiento, 6 = en atención (guardada sin finalizar)
                                $pp_atendido = $pp_activos->filter(function ($d) { return in_array(intval($d->estado), array(3, 6)); })->count() > 0;
                                // 4 = anulado, 5 = observado
                                $pp_cerrado = $pp_activos->filter(function ($d) { return in_array(intval($d->estado), array(4, 5)); })->count() > 0;
                                $pp_con_cita = $pp_activos->filter(function ($d) { return in_array(intval($d->estado), array(2, 3, 6)); })->count() > 0;
                                $pp_cita_reeval = null;
                                foreach ($pp_activos as $d_pp) {
                                  if ($d_pp->id_cita_reevaluacion) {
                                    $pp_cita_reeval = \App\Models\Cita::where('id', $d_pp->id_cita_reevaluacion)->where('id_estado_reg', 1)->first();
                                    if ($pp_cita_reeval) break;
                                  }
                                }
                                $pp_requiere_reeval = intval($detalle->first()->requiere_cita) === 2;
                                $pp_nueva_solicitud = intval($detalle->first()->id_solicitud) !== $pp_solicitud_anterior;
                                $pp_solicitud_anterior = intval($detalle->first()->id_solicitud);
                              ?>
                              @if ($pp_nueva_solicitud)
                                <div style="margin-top:15px; padding:4px 8px; background:#eef5ee; border-left:4px solid #1b5e20; font-weight:bold; font-size:12px;">
                                  SOLICITUD Nº {{ $detalle->first()->id_solicitud }}
                                  @if ($detalle->first()->solicitud && $detalle->first()->solicitud->fecha_solicitud)
                                    - FECHA Y HORA: {{ date('d/m/Y H:i', strtotime($detalle->first()->solicitud->fecha_solicitud)) }}
                                  @endif
                                </div>
                              @endif
                              <div style="margin-top:15px;">
                                <div class="row" style="margin-bottom:10px;">
                                  <div class="col-sm-12">
                                    @if ($pp_cerrado)
                                      <button type="button" class="btn btn-danger" disabled title="El procedimiento programado fue observado o anulado"><i class="fa fa-xing"></i> Eliminar Procedimiento</button>
                                    @elseif ($pp_atendido)
                                      <button type="button" class="btn btn-danger" disabled title="El procedimiento programado ya fue atendido"><i class="fa fa-xing"></i> Eliminar Procedimiento</button>
                                    @elseif ($pp_con_cita)
                                      <button type="button" class="btn btn-danger" disabled title="Primero debe anularse la cita del procedimiento"><i class="fa fa-xing"></i> Eliminar Procedimiento</button>
                                      <!-- <span style="margin-left:10px;color:#a94442;font-weight:bold">EL PROCEDIMIENTO YA CUENTA CON CITA</span> -->
                                    @elseif ($pp_cita_reeval)
                                      <button type="button" class="btn btn-danger" disabled title="Primero debe anularse la cita de reevaluación"><i class="fa fa-xing"></i> Eliminar Procedimiento</button>
                                    @else
                                      <button type="button" class="btn btn-danger" onclick="eliminarProcedimientoProgramado({{ $detalle->first()->id_solicitud }}, {{ $detalle->first()->id_procedimiento }})"><i class="fa fa-xing"></i> Eliminar Procedimiento</button>
                                    @endif
                                    @if ($pp_requiere_reeval)
                                      @if ($pp_cita_reeval)
                                        <!-- <span style="margin-left:10px;color:#008000;font-weight:bold">CITA DE REEVALUACIÓN EL: {{ date('d-m-Y h:i A', strtotime($pp_cita_reeval->fecha_hora)) }}</span> -->
                                      @else
                                        <!-- <span style="margin-left:10px;color:#a94442;font-weight:bold">PENDIENTE CITA DE REEVALUACIÓN</span> -->
                                      @endif
                                    @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-sm-7">
                                    <label style="font-weight:bold;">Procedimiento</label><br>
                                    <span style="font-weight:normal;">{{ $detalle->first()->procedimiento->codigo . ' - ' . $detalle->first()->procedimiento->descripcion }}</span>
                                  </div>
                                  <div class="col-sm-2">
                                    <label style="font-weight:bold;">Cantidad</label><br>
                                    <span style="font-weight:normal;">{{ $detalle->first()->cantidad }}</span>
                                  </div>
                                  <div class="col-sm-3">
                                    <label style="font-weight:bold;">Diagnostico</label><br>
                                    <span style="font-weight:normal;">{{ $detalle->first()->diagnostico ? $detalle->first()->diagnostico->nombre : '' }}</span>
                                  </div>
                                </div>
                                <div class="row" style="margin-top:10px;">
                                  <div class="col-sm-12">
                                    <label style="font-weight:bold;">Indicación del Procedimiento</label><br>
                                    <span style="font-weight:normal; white-space:pre-wrap;">{{ $detalle->first()->indicacion }}</span>
                                  </div>
                                </div>
                              </div>
                            @endforeach
                            @endif
                            <div style="clear:both"></div>
                            {{-- Procedimientos programados: solo IPRESS de nivel 3 o establecimiento 76 --}}
                            @if (\App\Models\Establecimiento::ppHabilitado($prestacion->id_establecimiento))
                            <div class="col-md-12" style="margin-top:15px;margin-bottom:10px;padding-left:0px">
                              <a onclick="agregarProcedimientoProgramado()" id="btnNuevoProcProgramado" flag="0" class="btn btn-success">Procedimiento Programado</a>
                            </div>
                            @endif
                            <div style="clear:both"></div>
                            <fieldset id="fsNuevoProcProgramado" style="display:none">
                              <div class="form-group">
                                <div class="col-sm-12">
                                  <button type="button" id="addProcProgra" class="btn btn-primary">Agregar Procedimiento</button>
                                </div>
                              </div>
                              <div id="tblSolicitudPp"></div>
                            </fieldset>
                          </div>
                        </div>
                      </fieldset>
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>REFERENCIA</b>
                            </div>
                          </div>
                        <?php if ($prestaciones->flag_ref_cref == '1'): ?>
                          <div class="panel-body">
                            <div class="panel panel-info panel-bordered panel-sm panel-body">
                              <div class="form-group">
                                <div class="col-sm-1" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                  <label>REFERIR CONSULTA</label>
                                </div>
                                <div class="col-sm-1" style="margin-left:0px;padding-left:0px">
                                   <button type="button" id="addreferenciacontra" class="btn btn-lg btn-toggle active"
                                    data-toggle="button" aria-pressed="false" autocomplete="off" disabled>
                                    <div class="handle"></div>
                                  </button>
                                </div>
                                <div id="fsReferenciacontra" >
                                  <div class="col-md-2"
                                    style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                    <label>SERVICIO DE REFERENCIA</label>
                                  </div>
                                  <div class="col-md-3" style="margin-left:0px;padding-left:0px">
                                    <select class="form-control input-sm" id="servicio_referencia" name="servicio_referencia" disabled>
                                      <option value="" selected=""> <?php echo $dato_servicio_ref ?></option>
                                    </select>
                                  </div>
                                  <div class="col-md-2"
                                    style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                    <label>COMENTARIO DE LA REFERENCIA</label>
                                  </div>
                                  <div class="col-md-3" style="margin-left:0px;padding-left:0px">
                                      <textarea class="form-control" cols="5" id="texto_referencia" name="texto_referencia"
                                          placeholder="Antecedente Familiar" disabled> <?php echo $dato_texto_obs ?></textarea>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                         <?php else: ?>
                          <div class="panel-body">
                            <div class="panel panel-info panel-bordered panel-sm panel-body">
                              <div class="form-group">
                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>REFERIR CONSULTA</label>
                                </div>
                                <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                  <button type="button" id="addreferenciacontra" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off" disabled>
                                      <div class="handle"></div>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                         <?php endif; ?>
                        </div>
                      </fieldset>
                      
                      @if(($cita->id_establecimiento==76 &&  $cita->id_servicio == 102))
                        @if($datos_gastro!=null)
                          <!-- <div class="panel-body" id="VerGastro">
                            <div id="fsGastro" >
                              <div class="panel with-nav-tabs panel-default">
                                <div class="panel-heading">
                                  <ul class="nav nav-tabs">
                                    @if($datos_gastro->baja==1 && $datos_gastro->alta==0)
                                    <li class="active"><a href="#tab_baja" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA BAJA</a></li>
                                    @else
                                      @if($datos_gastro->alta==1 && $datos_gastro->baja==0)
                                      <li class="active"><a href="#tab_alta" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA ALTA</a></li>
                                      @else
                                        @if($datos_gastro->baja==1 && $datos_gastro->alta==1)
                                        <li class="active"><a href="#tab_baja" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA BAJA</a></li>
                                        <li><a href="#tab_alta" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA ALTA</a></li>
                                        @endif
                                      @endif
                                    @endif
                                  </ul>
                                </div>
                                <div class="panel-body">
                                  <div class="tab-content">
                                    @if($datos_gastro->baja==1 && $datos_gastro->alta==0)
                                    <div class="tab-pane fade in active" id="tab_baja">
                                    @else
                                      @if($datos_gastro->baja==0 && $datos_gastro->alta==1)
                                      <div class="tab-pane fade" id="tab_baja">
                                      @else
                                        @if($datos_gastro->baja==1 && $datos_gastro->alta==1)
                                        <div class="tab-pane fade in active" id="tab_baja">
                                        @endif
                                      @endif
                                    @endif
                                      <fieldset>
                                        <div class="panel-body">
                                          <div class="table-responsive">
                                            <table class="table table-striped table-list-search" id="tblGastroBaja" cellspacing="0" width="100%" style="margin-top:5px;">
                                              <tfoot>
                                              </tfoot>
                                              <tbody>
                                                <tr>
                                                    <td width="15%"><b>INDICACIONES</b></td>
                                                    <td width="35%" colspan="4">
                                                      {{$datos_gastro->baja_indicaciones}}
                                                    </td>
                                                    <td width="15%"><b>MEDICACION:</b></td>
                                                    <td width="35%" colspan="3">
                                                      {{$datos_gastro->baja_medicacion}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>INSPECCION:</b></td>
                                                    <td width="85%" colspan="8">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"></td>
                                                    <td width="15%"><b>ESTATICA:</b></td>
                                                    <td width="85%" colspan="7">
                                                      {{$datos_gastro->baja_estatica}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"></td>
                                                    <td width="15%"><b>DINAMICA:</b></td>
                                                    <td width="85%" colspan="7">
                                                      {{$datos_gastro->baja_dinamica}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"></td>
                                                    <td width="15%"><b>TACTO RECTAL:</b></td>
                                                    <td width="85%" colspan="7">
                                                      {{$datos_gastro->baja_tacto_rectal}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>PREPARACION:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_preparacion}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>ESCALA DE BOSTON:</b></td>
                                                    <td>CD</td>
                                                    <td>
                                                      {{$datos_gastro->baja_escala_cd}}
                                                    </td>
                                                    <td>CT</td>
                                                    <td>
                                                      {{$datos_gastro->baja_escala_ct}}
                                                    </td>
                                                    <td>CI</td>
                                                    <td>
                                                      {{$datos_gastro->baja_escala_ci}}
                                                    </td>
                                                    <td>TOTAL</td>
                                                    <td>
                                                      {{$datos_gastro->baja_escala_total}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>INSERCION:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_insercion}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>HALLAZGOS:</b></td>
                                                    <td width="85%" colspan="8">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>CANAL ANAL:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_canal_anal}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>RECTO:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_recto}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>SIGMOIDES:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_sigmoide}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>DESCENDENTE:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_descendente}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Transverso:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_transverso}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Ascendente:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_ascendente}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Ciego:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_ciego}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>BIOPSIA:</b></td>
                                                    <td width="30%" colspan="3">
                                                      {{$datos_gastro->baja_biopsia}}
                                                    </td>
                                                    <td width="15%"><b>COMPLICACIONES</b></td>
                                                    <td width="30%" colspan="4">
                                                      {{$datos_gastro->baja_complicaciones}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%" colspan="4"><b>OBSERVACIONES:</b></td>
                                                    <td width="85%" colspan="5">
                                                      {{$datos_gastro->baja_observaciones}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" colspan="4"><b>DIAGNOSTICO ENDOSCOPICO:</b></td>
                                                    <td width="50%" colspan="5">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="100%" colspan="9">
                                                      {{$datos_gastro->baja_diag_endoscopico}}
                                                    </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </fieldset>
                                    </div>
                                    @if($datos_gastro->alta==1 && $datos_gastro->baja==0)
                                    <div class="tab-pane fade in active" id="tab_alta">
                                    @else
                                      @if($datos_gastro->baja==1 && $datos_gastro->alta==1)
                                      <div class="tab-pane fade" id="tab_alta">
                                      @else
                                        @if($datos_gastro->baja==1 && $datos_gastro->alta==0)
                                        <div class="tab-pane fade" id="tab_alta">
                                        @endif
                                      @endif
                                    @endif
                                    
                                      <fieldset>
                                        <div class="panel-body">
                                          <div class="row">
                                            <table class="table table-striped table-list-search" id="tblGastroAlta" cellspacing="0" width="100%" style="margin-top:5px;">
                                              <tfoot>
                                              </tfoot>
                                              <tbody>
                                                <tr>
                                                    <td width="15%"><b>INDICACIONES</b></td>
                                                    <td width="30%" colspan="4">
                                                      {{$datos_gastro->alta_indicaciones}}
                                                    </td>
                                                    <td width="15%"><b>MEDICACION:</b></td>
                                                    <td width="30%" colspan="3">
                                                      {{$datos_gastro->alta_medicacion}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>HALLAZGOS</b></td>
                                                    <td width="85%" colspan="8">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>ESOFAGO:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_esofago}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>ESTOMAGO</b></td>
                                                    <td width="85%" colspan="8">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Cardias:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_cardias}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Fondo:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_fondo}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Cuerpo:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_cuerpo}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Antro:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_antro}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>DUODENO</b></td>
                                                    <td width="85%" colspan="8">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Bulbo:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_bulbo}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Segunda porción:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_seg_porcion}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>BIOPSIA:</b></td>
                                                    <td width="30%" colspan="3">
                                                      {{$datos_gastro->alta_biopsia}}
                                                    </td>
                                                    <td width="15%"><b>COMPLICACIONES</b></td>
                                                    <td width="30%" colspan="4">
                                                      {{$datos_gastro->alta_complicaciones}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%" colspan="4"><b>OBSERVACIONES:</b></td>
                                                    <td width="85%" colspan="5">
                                                      {{$datos_gastro->alta_observaciones}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" colspan="3"><b>DIAGNOSTICO ENDOSCOPICO:</b></td>
                                                    <td width="50%" colspan="6">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="100%" colspan="9">
                                                      {{$datos_gastro->alta_diag_endoscopico}}
                                                    </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </fieldset>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>     -->
                        @endif
                      @endif
                      
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
                                <p style="font-weight: normal;"><?php echo $prestacion->tratamiento_auxiliar?></p>
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
                                <p style="font-weight: normal;"><?php echo $prestacion->plan_trabajo?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      
                      <fieldset>
                      @if($prestacion->id_tipo_atencion==1)
                        <div class="panel panel-info panel-bordered panel-sm panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label class="col-md-2" style="padding-left:0px">DERIVAR CONSULTA</label>
                              <div class="col-md-2">
                                <p><?php echo ($prestacion->servicio_derivar!="")?"SI":"NO"?></p>
                              </div>
                              <?php if($prestacion->servicio_derivar!=""):?>
                              <label class="col-md-1" style="padding-left:0px">SERVICIO</label>
                              <div class="col-md-4">
                                <p><?php echo $prestacion->servicio_derivar?></p>
                              </div>
                              <?php endif;?>
                            </div>
                          </div>
                        </div>
                      @endif
                    </fieldset>
                    <fieldset>
                      <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <div class="form-group">
                          @if ($prestacion->id_establecimiento == 76)
                            <div class="col-md-12">
                              <label class="col-md-2" style="padding-left:0px">REGISTRAR CITA</label>
                              <div class="col-md-8">
                                <p><?php echo $prestacion->registrar_cita_por_medico == 1 ?"SI":"NO"?></p>
                                <input type="hidden" id="registrar_cita_por_medico" name="registrar_cita_por_medico" value="{{$prestacion->registrar_cita_por_medico}}" />
                              </div>
                            </div>
                          @endif  
                          <div class="col-md-12">
                            <label class="col-md-2" style="padding-left:0px">PRESCRIBIR RECETA</label>
                            <div class="col-md-8">
                              <p><?php echo (count($receta)>0)?"SI":"NO"?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    {{-- GESTACIÃ“N --}}
                    @if (isset($gestacion->id))
                      <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <div class="row">
                          <div class="col-2 col-sm-2">
                            <label >GESTACIÓN</label>
                          </div>
                        </div>
                        <hr />
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>ULTIMA MESTRUACIÓN:</label>
                            <p>{{$gestacion->fech_ult_regla}}</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>FECHA PROBABLE DE PARTO:</label>
                            <p>{{$gestacion->fech_prob_parto}}</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>SEMANAS DE GESTACIÓN:</label>
                            <p>{{$gestacion->semanas_gestacion}}</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>N ° DE CONTROLES PRENATALES:</label>
                            <p>{{$gestacion->num_controles}}</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>DUDA:</label>
                            <p>{{$gestacion->duda ? 'SI' : 'NO'}}</p>
                          </div>
                        </div>
                      </div>
                    @endif
                    {{-- GESTACIÃ“N END --}}

            {{-- PUERPERA --}}
                    @if (isset($puerperio->id))
                      <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <div class="row">
                          <div class="col-2 col-sm-2">
                            <label >PUERPERA</label>
                          </div>
                        </div>
                        <hr />
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>FECHA Y HORA PARTO:</label>
                            <p>{{$puerperio->fecha_parto}}</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>TIPO DE PARTO:</label>
                            <p>{{$puerperio->id_tipo_parto}}</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>COMPLICACIÓN:</label>
                            <p>{{$puerperio->complicaciones}}</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>DESCRIPCIÓN:</label>
                            <p>{{$puerperio->descripcion}}</p>
                          </div>
                        </div>
                        <hr />
                        @if (isset($natis) && count($natis) > 0)
                          <div class="container-fluid">
                            <div class="row">
                              <div class="col-2 col-sm-2"><label >NEONATOS</label></div>
                            </div>
                            <div class="panel panel-info panel-bordered panel-sm panel-body">
                            @foreach ($natis as $row)
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>ESTADO DE VIDA:</label>
                                  <p><?php echo $row->estado?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>TIPO DE DOCUMENTOS:</label>
                                  <p><?php echo $row->medico_paterno?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>NUM. DOCUMENTOS:</label>
                                  <p><?php echo $row->num_doc?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>SEXO:</label>
                                  <p><?php echo $row->sexo == 'M' ? 'MASCULINO' : 'FEMENINO' ?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>TALLA:</label>
                                  <p><?php echo $row->talla?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                  <label>PESO:</label>
                                  <p><?php echo $row->peso?></p>
                                </div>
                              </div>
                              <hr>
                            @endforeach
                            </div>
                          </div>
                        @endif
                      </div>
                    @endif 
            {{-- PUERPERA END --}}
                    <fieldset id="fsRecetaVale">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>PRODUCTOS MEDICOS</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <?php foreach($receta as $rowReceta):
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
                                      <label class="col-md-2" style="padding-left:0px">RECETA VALE N °</label>
                                      <div class="col-md-1">
                                        <p style="font-weight: normal;"><?php echo $rowReceta->nro_receta; ?></p>
                                      </div>
                                      <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                      <div class="col-md-2">
                                        <p style="font-weight: normal;"><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                                      </div>
                                      <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                      <div class="col-md-4" style="padding:0px">
                                        <p style="font-weight: normal;"><?php echo $rowReceta->farmacia; ?></p>
                                      </div>
                                    </div>
                                  </div>
                                  <table class="display" cellspacing="0" width="100%">
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
                                      </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <?php foreach($medicamentos as $row):?>
                                    <tr>
                                      <td style="font-weight: normal;"><?php echo $row->codigo?></td>
                                      <td style="font-weight: normal;"><?php echo $row->nombre?>
                                          <?php 
                                            if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                              echo "(".$row->cantidad_prescrita."L)";
                                            endif; 
                                          ?>
                                      </td>
                                      <td style="font-weight: normal;"><?php echo $row->abreviatura?></td>
                                      <td style="font-weight: normal;"><?php echo $row->tiempo_periodo?></td>
                                      <td style="font-weight: normal;"><?php echo $row->posologia?></td>
                                            <td style="font-weight: normal;">{{$row->via}}</td>
                                            <td style="font-weight: normal;"><?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                               echo $row->cantidad_prescrita/1000;
                                               else:
                                                echo $row->cantidad_prescrita;
                                               endif; ?>
                                            </td>
                                            <td style="font-weight: normal;"><?php
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

                              <select class="form-control" name="id_farmacia_receta" id="id_farmacia_receta"
                                style="width:100%" onchange="getProducto()">
                                <option value="">-Seleccionar-</option>
                                <?php if(Auth::user()->id_establecimiento != 76):?>
                                  <?php foreach($farmacias as $row):?>
                                    <option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
                                  <?php endforeach;?>
                                <?php else:?>
                                  <option value="121" <?php if(Auth::user()->id_farmacia==121) echo "selected"; ?>>Farmacia PIP I del Hospital Nacional PNP Luis N. Saenz</option>
                                  <option value="3" <?php if(Auth::user()->id_farmacia==3) echo "selected"; ?>>Farmacia Central del Hospital Nacional PNP Luis N. Saenz</option>
                                  <option value="10" <?php if(Auth::user()->id_farmacia==10) echo "selected"; ?>>Farmacia Oncologica del Hospital Nacional PNP Luis N. Saenz</option>
                                  <option value="21" <?php if(Auth::user()->id_farmacia==21) echo "selected"; ?>>Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                                  <option value="17" <?php if(Auth::user()->id_farmacia==17) echo "selected"; ?>>Farmacia TARGA del Hospital Nacional PNP Luis N. Saenz</option>
                                  <option value="109" <?php if(Auth::user()->id_farmacia==109) echo "selected"; ?>>Almacén Rehabilitacion del Hospital Nacional PNP Luis N. Saenz</option>
                                  <option value="132" <?php if(Auth::user()->id_farmacia==132) echo "selected"; ?>>Farmacia de Preparados Farmacéutico No Estériles del Hospital Nacional PNP Luis N. Saenz</option>
                                <?php endif;?>
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

              {{--CENTRO QUIRURGICO INICIO--}}
              @if ($data_cqx_cita == null)
              <?php
              $hay_riesgo_qx = "NO";
              //$hay_riesgo_qx = (count($riesgos_qx) > 0) ? "SI" : "NO";
              if($es_primera_atencion_cqx){
                $hay_riesgo_qx="SI";
              }
              ?>
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <div class="form-group">
                  <div class="col-sm-2">
                    <label>INTERVENCIÓN QUIRÚRGICA</label>
                  </div>
                  <div class="col-sm-2">
                    <p>{{$hay_riesgo_qx}}</p>
                  </div>
                </div>
                @if ($hay_riesgo_qx == "SI")
                <fieldset id="fsInterQuirurgica">
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>RIESGO QUIRÚRGICO</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <!-- <div style="display: flex; flex-direction: column; gap: 1rem; padding-bottom: 3rem;"> -->
                        @foreach ($riesgos_qx as $r)
                        <div class="form-group">
                          <div class="col-sm-1">
                            <input type="checkbox" name="rq_cardiologia" id="rq_cardiologia" value="1" class="" disabled checked="checked" />
                          </div>
                          <div class="col-sm-10" style="">
                            <label>{{$r->servicio}}</label>
                          </div>
                        </div>
                        @endforeach
                      <!-- </div> -->

                      <?php
                      $hay_interconsulta_pre_qx = "NO";
                      $hay_interconsulta_pre_qx = (count($interconsultas_pre_qx) > 0) ? "SI" : "NO";
                      ?>
                      <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <div class="form-group">
                          <div class="col-sm-2">
                            <label>INTERCONSULTA PRE-QUIRÚRGICA</label>
                          </div>
                          <div class="col-sm-2">
                            <p>{{$hay_interconsulta_pre_qx}}</p>
                          </div>
                        </div>
                        @if ($hay_interconsulta_pre_qx == "SI")
                        <fieldset id="fsInterPreQuirurgica">
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                              <div class="panel-title text-center">
                                <b>INTERCONSULTA</b>
                              </div>
                            </div>
                            <div class="panel-body">
                              <div class="form-group">
                                <div class="col-sm-3">
                                  <p><b>SERVICIO</b></p>
                                </div>
                                <div class="col-sm-7">
                                  <p><b>MOTIVO DE INTERCONSULTA</b></p>
                                </div>
                              </div>
                              @foreach($interconsultas_pre_qx as $i)
                              <div class="form-group">
                                <div class="col-sm-3">
                                  <p>{{$i->servicio}}</p>
                                </div>
                                <div class="col-sm-7">
                                  <p>{{$i->motivo_interconsulta}}</p>
                                </div>
                              </div>
                              @endforeach
                            </div>
                          </div>
                        </fieldset>
                        @endif
                      </div>


                    </div>
                  </div>
                </fieldset>
                @endif
              </div>
              {{----------------------------}}
              @endif

              <!-- Inicio Programación Quirúrgica -->
              @if ($data_cqx_cita != null && $data_cqx_cita->tipo == 2)
              <!-- Es una Cita de Seguimiento -->
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <div class="form-group">
                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                    <label>PROGRAMACIÓN QUIRÚRGICA</label>
                  </div>
                  <div class="col-sm-2">
                    <p>@if ($data_cqx_cita->is_programacion_quirurgica) SI @else NO @endif</p>
                  </div>
                </div>
                @if ($data_cqx_cita->is_programacion_quirurgica)
                <fieldset id="fsProgramacionQx">
                  <div class="panel panel-info panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center"><b>REGISTRAR PROGRAMACIÓN QUIRÚRGICA</b></div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-sm-2">
                          <label for="">PRIORIDAD DE PROGRAMACION EN SEMANAS</label>
                        </div>
                        <div class="col-sm-2">
                          <p>{{ $data_cqx_cita->prioridad }}</p>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                          <label>REQUIERE INTERNAMIENTO PRE QX</label>
                        </div>
                        <div class="col-sm-2">
                          <p>@if ($data_cqx_cita->is_requiere_internamiento_preqx) SI @else NO @endif</p>
                        </div>
                        <div class="col-sm-2">
                          @if ($data_cqx_cita->is_requiere_internamiento_preqx)
                          <p>{{ $data_cqx_cita->tiempo_internamiento_preqx }}</p>
                          @endif
                        </div>
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                          <label>DIAS DE HOSPITALIZADO POST QX</label>
                        </div>
                        <div class="col-sm-2">
                          <p>@if ($data_cqx_cita->is_hospitalizacion_postqx) SI @else NO @endif</p>
                        </div>
                        <div class="col-sm-2">
                          @if ($data_cqx_cita->is_hospitalizacion_postqx)
                          <p>{{ $data_cqx_cita->tiempo_hospitalizacion_postqx }}</p>
                          @endif
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-2">
                          <label for="">A SER OPERADO EN:</label>
                        </div>
                        <div class="col-sm-2">
                          <p>{{ $data_cqx_cita->lugar_operacion }}</p>
                        </div>
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
                                  @foreach ($cqx_cita_profesionales as $p)
                                  <tr>
                                    <td>{{ $p->paterno }} {{ $p->materno }}, {{ $p->nombre }}</td>
                                    <td>{{ $p->tipo_profesional }}</td>
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
                          <div class="panel-title text-center"><b>PROCEDIMIENTOS DE INTERVENCIÓN</b></div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-sm-12">
                              <table id="table_procedimiento_intervencion" class="display" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th>Código y Descripción</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach ($cqx_cita_procedimientos as $p)
                                  <tr>
                                    <td>{{ $p->codigo_cpms }} - {{ $p->descripcion_cpms }}</td>
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
                </fieldset>
                @endif
              </div>
              @elseif ($data_cqx_cita != null && $data_cqx_cita->tipo == 1)
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <div class="form-group">
                  <div class="col-md-2">
                    @if ($data_cqx_cita->id_consultorio == 72)
                    <label for="">Sociedad Americana de Anestesiología - ASA:</label>
                    @else
                    <label for="">RIESGO:</label>
                    @endif
                  </div>
                  <div class="col-md-1">
                    <p>{{ $data_cqx_cita->numero_riesgo }}</p>
                  </div>
                  @if ($data_cqx_cita->id_consultorio == 72)
                  <div class="col-md-2">
                    <label for="">TIPO CIRUGÍA:</label>
                  </div>
                  <div class="col-md-2">
                    <p>{{ $data_cqx_cita->tipo_cirugia }}</p>
                  </div>
                  <div class="col-md-2">
                    <label for="">OBSERVACIÓN DE ASA:</label>
                  </div>
                  <div class="col-md-3">
                    <p>{{ $data_cqx_cita->observacion_asa }}</p>
                  </div>
                  @endif
                </div>
                <hr>
                <!--  -->
                <div class="form-group">
                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                    <label>OBSERVACION DE INTERVENCION QX</label>
                  </div>
                  <div class="col-sm-2">
                    <p>@if ($data_cqx_cita->flag == 3) NO @elseif ($data_cqx_cita->flag == 4) SI @endif</p>
                  </div>
                </div>
                @if ($data_cqx_cita->flag == 4) 
                <div class="form-group">
                  <div class="col-md-12">
                    <label for="">MOTIVO DE OBSERVACION PARA INTERVENCION QUIRÚRGICA</label>
                    <p>{{ $data_cqx_cita->observacion }}</p>
                  </div>
                </div>
                @endif
              </div>
              @endif
              <!-- Fin Programación Quirúrgica -->

              <input type="hidden" id="argumentos_laboratorio" value="" />
              <input type="hidden" id="doc_firmar_laboratorio" value="" />
              <input type="hidden" id="opt_firma_laboratorio" value="" />
              <input type="hidden" value="{{ $flag_laboratorio }}" name="flag_laboratorio" id="flag_laboratorio">
              <input type="hidden" value="{{ $flag_firmar_laboratorio }}" name="flag_firmar_laboratorio" id="flag_firmar_laboratorio">

              <fieldset id="fsExamenLab">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>EXAMEN DE LABORATORIO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <?php
                      $lab_examenes_todos = [];
                    ?>
                    @foreach($laboratorio as $rowLaboratorio)
                      <?php 
                        if ($rowLaboratorio->fecha_firma == "") {
                            $flag_firmar_lab = 1;
                        }

                        $model_prestacion = new \App\Models\Prestaciones;
                        $proclaboratorio = $model_prestacion->GetPrestacionLaboratorio($id_prestacion, $rowLaboratorio->id);

                        if ($proclaboratorio) {
                          foreach ($proclaboratorio as $item_procedimiento_lab) {
                            $lab_examenes_todos[] = $item_procedimiento_lab;
                          }
                        }
                      ?>

                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                      <?php 
                        $bloquear_invalidar_lab = ($cita->id_establecimiento == 76 && $cita->flag_cita_laboratorio == "1"); 
                      ?>
                      <?php if($rowLaboratorio->estado=="1" && !$bloquear_invalidar_lab){?>
                      <a data-toggle="modal" data-target="#squarespaceModal2"
                        onclick="enviarLaboratorio(<?php echo $rowLaboratorio->id?>)" class="btn btn-danger">
                        <i class="fa fa-xing"></i> Invalidar Examen
                      </a>
                      <?php }elseif($rowLaboratorio->estado=="1" && $bloquear_invalidar_lab){
                        $cita_reevaluacion = \App\Models\Cita::find($cita->id_cita_reevaluacion);
                        $fecha_reevaluacion = $cita_reevaluacion ? date("d-m-Y", strtotime($cita_reevaluacion->fecha_hora)) : '';
                      ?>
                        <div style="padding-bottom:0px;line-height:30px;color:#008000;font-weight:bold">
                          LA ORDEN DE LABORATORIO YA CUENTA CON CITA DE REEVALUACION EL: {{ $fecha_reevaluacion }}
                        </div>
                      <?php }else{ ?>
                      <button class="btn btn-danger" disabled="disabled"><i class="fa fa-xing"></i> Invalidar
                        Examen</button>
                      <?php } ?>
                    </div>
                    <br />
                    <div style="clear:both"></div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <label class="col-md-2" style="padding-left:0px">N ° ORDEN</label>
                        <div class="col-md-3">
                          <p style="font-weight: normal;"><?php echo $id_prestacion.' - '. $rowLaboratorio->id; ?></p>
                        </div>
                        <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                        <div class="col-md-3">
                          <p style="font-weight: normal;"><?php echo date("d-m-Y", strtotime($rowLaboratorio->created_at)); ?></p>
                        </div>
                      </div>
                    </div>
                    <fieldset>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              
                              <table class="display" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    @if ($rowLaboratorio->has_observacion)
                                      <th width="5%">Código</th>
                                      <th width="75%">Nombre</th>
                                      <th width="10%">Cantidad</th>
                                      <th width="10%">Diagnóstico</th>
                                    @else
                                      <th width="5%">Código</th>
                                      <th width="45%">Nombre</th>
                                      <th width="30%">Observación</th>
                                      <th width="10%">Cantidad</th>
                                      <th width="10%">Diagnóstico</th>
                                      @if (!empty($nivel) && (int)$nivel < 3)
                                        <th width="5%">Tiempo</th>
                                      @endif
                                    @endif
                                  </tr>
                                  </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                  <?php foreach($proclaboratorio as $row):?>
                                  <tr>
                                    <td style="font-weight: normal;"><?php echo $row->codigo?></td>
                                    <td style="font-weight: normal;"><?php echo $row->nombre?></td>
                                    @if (!$rowLaboratorio->has_observacion)
                                      <td style="font-weight: normal;"><?php echo $row->observacion?></td>
                                    @endif
                                    <td style="font-weight: normal;"><?php echo $row->cantidad?></td>
                                    <td style="font-weight: normal;"><?php echo $row->cod_diag?></td>
                                    {{--@if (!empty($nivel) && (int)$nivel < 3)
                                      <td>{{ $row->tiempo }}</td>
                                    @endif--}}
                                  </tr>
                                  <?php endforeach;?>

                                  @if (intval($nivel) === 3 && $rowLaboratorio->has_observacion)
                                    <?php
                                      $colspan = (intval($rowLaboratorio->flag) > 0) ? "5" : "4";
                                      $hora_toma_muestra = "";
                                      if (!is_null($rowLaboratorio->id_hora_toma_muestra)) {
                                        switch (intval($rowLaboratorio->id_hora_toma_muestra)) {
                                          case 0: $hora_toma_muestra = "OTROS"; break;
                                          case 1: $hora_toma_muestra = "05:00 AM"; break;
                                          case 2: $hora_toma_muestra = "09:00 AM"; break;
                                          case 3: $hora_toma_muestra = "16:00 PM"; break;
                                          case 4: $hora_toma_muestra = "22:00 PM"; break;
                                        }
                                      }
                                    ?>
                                    <tr>
                                      <td colspan="{{ $colspan }}">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td colspan="{{ $colspan }}"><b>Observación:</b><span style="font-weight: normal"> {{$hora_toma_muestra}} - {{ $rowLaboratorio->observacion }}</span></td>
                                    </tr>
                                  @endif
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    @endforeach

                    <table id="tblAuxExamenLab" class="table" style="display: none;">
                      <tbody>
                        @if ($lab_examenes_todos)
                          @foreach($lab_examenes_todos as $row)
                            <tr>
                              <td><input type="text" class="id_lna_examen" value="{{ $row->id }}" readonly></td>
                              <td><input type="text" value="{{ $row->codigo }}" readonly></td>
                            </tr>
                          @endforeach
                        @endif
                      </tbody>
                    </table>
                    
                    {{-- @if (intval($nivel) === 1 || intval($nivel) === 2) --}}
                    <?php $bloquear_agregar_lab = ($cita->id_establecimiento == 76 && $cita->flag_cita_laboratorio == "1"); ?>
                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                      @if(!$bloquear_agregar_lab)
                      <a onclick="agregarExamenLab()" id="btnNuevoExamenLab" flag="0" class="btn btn-success">Examen Laboratorio</a>
                      @endif
                    </div>
                    {{-- @endif --}}
                    <div style="clear:both"></div>
                    <fieldset id="fsNuevoExamenLab" style="display:none">
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-md-4">
                            <button type="button" id="addExamenLab2" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
                          </div>
                          <div class="col-md-4">
                            <?php $display = "none"; ?>
                            @if (is_null($data_cqx_cita) && $es_primera_atencion_cqx)
                              <?php $display = "block"; ?>
                            @endif
                            <div id="section_perfil_preoperatorio" style="display: {{ $display }};">
                              <div class="col-md-6">
                                <label for="id_perfil_preoperatorio">Perfil Preoperatorio:</label>
                              </div>
                              <div class="col-md-6">
                                <select name="id_perfil_preoperatorio" id="id_perfil_preoperatorio" class="form-control">
                                  <option value="0">-Seleccionar-</option>
                                  <option value="1">CIRUGÍA MAYOR</option>
                                  <option value="2">CIRUGÍA MENOR</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          @if (intval($prestacion->id_establecimiento) === 76)
                            <div class="col-md-4" style="text-align: right;">
                              <button type="button" id="RUALab2" class="btn btn-primary" data-toggle="modal" data-target="#modalDX">RUA LABORATORIO</button>
                            </div>
                          @endif
                        </div>
                        <br />
                        <table class="display" id="tblExamenLab" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                            <tr>
                              @if (intval($nivel) == 3)
                                <th width="65%">Exámen</th>
                                <th width="10%">Cantidad</th>
                                <th width="20%">Diagnóstico</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th width="5%">Eliminar</th>
                              @elseif (!empty($nivel) && (int)$nivel < 3)
                                <th width="45%">Exámen</th>
                                <th width="20%">Observación</th>
                                <th width="10%">Cantidad</th>
                                <th width="20%">Diagnóstico</th>
                                {{--@if (!empty($nivel) && (int)$nivel < 3)
                                  <th width="5%">Tiempo</th>
                                @endif--}}
                                <th></th>
                                <th></th>
                                <th></th>
                                <th width="5%">Eliminar</th>
                              @endif
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                          </tbody>
                        </table>
                        @if (intval($nivel) === 3)
                          <br/>
                          <!--<table style="width: 100%;">
                            <tr>
                              <th width="5%">Observación</th>
                              <th width="95%"></th>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" id="lab_orden_toma_muestra" maxlength="8" readonly /></td>
                              <td><input type="text" class="form-control" name="lab_orden_observacion" maxlength="255" /></td>
                            </tr>
                          </table> -->
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
                </div>
              </fieldset>
              <input type="hidden" id="doc_firmar_radiologia" value="" />
              <input type="hidden" value="{{ $flag_radiologia }}" name="flag_radiologia" id="flag_radiologia">
              <input type="hidden" value="{{ $flag_firmar_radiologia }}" name="flag_firmar_radiologia" id="flag_firmar_radiologia">
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
                      $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLaboratorio($id_prestacion, $rowRadiologia->id,3);
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
                        <label class="col-md-2" style="padding-left:0px">N ° ORDEN</label>
                        <div class="col-md-3">
                          <p style="font-weight: normal;"><?php echo $id_prestacion.' - '. $rowRadiologia->id; ?></p>
                        </div>
                        <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                        <div class="col-md-3">
                          <p style="font-weight: normal;"><?php echo date("d-m-Y", strtotime($fechaServidor)); ?></p>
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
                                  
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                  <?php foreach($procradiologia as $row):?>
                                  <tr>
                                    <td style="font-weight: normal;"><?php echo $row->codigo?></td>
                                    <td style="font-weight: normal;"><?php echo $row->nombre_corto?></td>
                          					<td style="font-weight: normal;"><?php 
                                              if($row->comparativo_radio!=null):
                                  						   if($row->comparativo_radio==1):
                                                  echo "SI";
                                  						   else:
                                  						    echo "NO";
                                  						   endif;
                                              endif;
                                       ?>
                                    </td>
                          					<td style="font-weight: normal;"><?php 
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
                                      <td style="font-weight: normal;"><?php 
                                                if($row->observacion!='0'):
                                                    echo $row->observacion;
                                                endif;
                                            ?>
                                      </td>
                                      <td style="font-weight: normal;"><?php echo $row->cantidad?></td>
                                      <td style="font-weight: normal;"><?php echo $row->cod_diag?></td>
                                      <td style="font-weight: normal;"><?php echo $row->radiologia_portatil?></td>
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
                      <a onclick="agregarExamenRad()" id="btnNuevoExamenRad" flag="0" class="btn btn-success">Examen
                        de Imagenología</a>
                    </div>
                    <?php //endif; ?>
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
              <!--INTERCONSULTA 
              <fieldset id="fsInterconsulta">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INTERCONSULTA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <br />
                    <div style="clear:both"></div>
                    <fieldset>
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
                                  <?php echo $rowi->servicio ?>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="col-md-2"> 
                                  <label>MOTIVO:</label> 
                                </div> 
                                <div class="col-md-10"> 
                                  <?php echo $rowi->motivo_interconsulta ?>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="col-md-12"><br/> 
                                  <label>RESUMEN</label><br/>  
                                  <?php echo $rowi->resumen_interconsulta ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php endforeach;?>
                      </div>
                    </fieldset>
                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                      <a onclick="agregarInterconsulta()" id="btnNuevaInterconsulta" flag="0" class="btn btn-success">INTERCONSULTA</a>
                    </div>
                    <div style="clear:both"></div>
                    <fieldset id="fsNuevaInterconsulta" style="display:none">
                      <div class="panel-body">
                        <table>
                          <tr>
                            <td>
                              <button type="button" id="addInterconsulta2" class="btn btn-primary" data-toggle="modal"
                                data-target="#addClassModal">Agregar Interconsulta</button>
                            </td>
                          </tr>
                        </table>
                        <br />
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
                    </fieldset>
                  </div>
                </div>
              </fieldset>
              -->
              

              <?php //endif;?>

              <!-- Inicio Fecha Probable Intervencion -->
              @if ($data_cqx_cita != null && $data_cqx_cita->tipo == 2)
              <div class="panel panel-info panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center"><b>FECHA PROBABLE DE INTERVENCIÓN QUIRÚRGICA</b></div>
                </div>
                <div class="panel-body">
                  <div class="form-group">
                    <div class="col-md-offset-5 col-md-2">
                      <input type="text" id="fecha_probable" class="form-control" value="@if ($data_cqx_cita->fecha_probable != null) {{ date('d-m-Y', strtotime($data_cqx_cita->fecha_probable)) }} @endif" readonly>
                    </div>
                  </div>
                </div>
              </div>
              @endif
              <!-- Fin Fecha Probable Intervencion -->

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
                <?php if ($is_habilitado_firma_electronica): ?>
	                      <input class="btn btn-info input-sm pull-right" value="FIRMAR" type="button" onclick="firmarsintokens()" style="margin-left:20px" />
	                    <?php else: ?>
	                      <input class="btn btn-info input-sm pull-right" value="FIRMAR" type="button" id="btnfirmar" style="margin-left:20px" />
                  <?php endif; ?>
                <?php endif; ?>
                <?php endif; ?>
                <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_citas_medico_atenciones"> <span
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
<input type="hidden" id="id_radiologia_actualizar" value="" />
<input type="hidden" id="id_laboratorio_actualizar" value="" />
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

<div class="modal fade" id="squarespaceModal2" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
  aria-hidden="true">
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
            <input type="hidden" class="form-control" id="id_laboratorio" name="id_laboratorio"
              value="">
          </div>
          <div class="form-group">
            <input id="btnInvalidarLaboratorio" class="btn btn-danger" type="submit" value="Invalidar">
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

  <!-- Modal RUA LABORATORIO -->
  <div class="modal fade" id="modalDX" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">RUA LABORATORIO</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div id="contenedorDX" class="contenedor-checks">Cargando...</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="guardarRUALab">Guardar</button>
        </div>
      </div>
    </div>
  </div>

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

@if (Request::get('solo_lectura') == '1')

  <style>
    .marca-agua-atencion {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 99999;
      pointer-events: none;
      overflow: hidden;
    }

    .marca-agua-atencion span {
      position: absolute;
      color: rgba(100, 100, 100, 0.13);
      font-size: 20px;
      font-weight: bold;
      white-space: nowrap;
      transform: rotate(-35deg);
    }
  </style>

  <div class="marca-agua-atencion">
      @for ($fila = 0; $fila < 7; $fila++)
          @for ($columna = 0; $columna < 5; $columna++)
              <span style="
                  top: {{ ($fila * 160) + 40 }}px;
                  left: {{ ($columna * 430) - 100 }}px;
              ">
                  {{ Auth::user()->dni }} -
                  {{ Auth::user()->paterno }}
                  {{ Auth::user()->materno }}
                  {{ Auth::user()->name }}
              </span>
          @endfor
      @endfor
  </div>

  <script type="text/javascript">
    $(document).ready(function () {

      $('body > nav').hide();
      $('body > header').hide();
      $('.navbar').hide();
      $('.btn-danger').hide();

      $('#btnNuevoReceeta').hide();
      $('#fsNuevaRecetaVale').hide();

      $('#btnNuevoExamenLab').hide();
      $('#fsNuevoExamenLab').hide();

      $('#btnNuevoExamenRad').hide();
      $('#fsNuevoExamenRad').hide();

      $('#btnNuevaInterconsulta').hide();
      $('#fsNuevaInterconsulta').hide();

      $('#btnNuevaReferencia').hide();
      $('#fsNuevaReferencia').hide();

      $('#btnAltaPaciente').hide();
      $('#divAlta2').hide();

      $('#divGuardar').hide();
      $('#divFirmar').hide();

    });
  </script>

@endif

@include('frontend.includes.footer')
@endsection


