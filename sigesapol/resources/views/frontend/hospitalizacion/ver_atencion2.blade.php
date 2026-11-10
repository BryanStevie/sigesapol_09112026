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

  .custom-small-alert {
    max-width: 300px;
    margin: 1rem auto;
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



/*===============TABLA IMAGENOLOGÍA RESPONSIVE INICIO=====================================*/ Add commentMore actions
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
/*===============TABLA IMAGENOLOGÍA RESPONSIVE FIN=====================================*/ 
</style>

<script>
  const DIA_SEMANA_DOMINGO = 0;

  //<![CDATA[
    var obtenerDiagnosticoGeneroUrl = "{{ route('obtener_diagnostico_genero_ver', ['buscar' => '_buscar_', 'genero' => '_genero_']) }}";
  var id_prestacion = "{{ $id_prestacion }}";
  let listInterconsultaRegistradaServicio = <?php echo json_encode($interconsultas_id_servicio); ?>;
 var listIdDiagnosticoObjetos =[];
  var codigos = [];
  var codigosprocedimientos = [];

  var procedimientosSeleccionados =[];
  var listIdDiagnostico =[];
  var listIdDiagnosticoObjetos =[];

 var filasmedicamentos = 0;
  let showValidacionCantInterconsulta = false;

  // "is_estable_auxiliar" se usa para guardar el valor actual antes de su cambio
  let is_estable_auxiliar = null;

  $(document).ready(function() {

   
    $('#btnLimpiar').prop('disabled',true);
    $('#btnLimpiarprocedimiento').prop('disabled',true);
  
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

    @if ($obj_hospitalizacion_atencion->exam_reg_19 != "") // Primer registro de Pares craneales
      let toggleButton = document.getElementById('addParesCraneales');
      toggleButton.classList.add('active');      // Añadir la clase activa
      toggleButton.setAttribute('aria-pressed', 'true');  // Cambiar el atributo aria
      toggleButton.setAttribute('disabled', 'true');
      $("#fsParesCraneales").show();
    @endif

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
  var id_resol = $('#id_prestacion').val();
  let id_radiologia = $("#id_radiologia_actualizar").val();
  let id_laboratorio = $("#id_laboratorio_actualizar").val();
  if (flagServicioFirma == "1") {
    $.post("{{route('actualiza_prestacion')}}", {
      _token: CSRF_TOKEN,
      id_tipo: "hospitalizacion",
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
            //location.reload();
          location.href = '/all_hospitalizado_by_establecimiento';        
          });
        });
      });
    });
  }

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
      id_tipo: "hospitalizacion",
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
              location.href = '/all_hospitalizado_by_establecimiento';        
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
    // Realizar la petición AJAX usando el código id_diagnostico
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

$("#btnLimpiar").on("click", function() {
  $("#diagnosticosAgregar tbody").empty();
  $('#guardardiagnosticoseditar').prop("disabled", true);
  $('#btnLimpiar').prop("disabled", true);
});

$("#btnLimpiarprocedimiento").on("click", function() {
  $("#procedimientosagregar tbody").empty();
  $('#procedimientoboton').prop("disabled", true);
  $('#btnLimpiarprocedimiento').prop("disabled", true);
  
});



function updateBtnLimpiarState() {
  const rowCount = $("#diagnosticosAgregar tbody tr").length;
  if (rowCount == 0) {
    $('#btnLimpiar').prop('disabled', false);
  } else {
    $('#btnLimpiar').prop('disabled', true);
  }
}

function updateBtnLimpiarState2() {
  const rowCount = $("#procedimientosagregar tbody tr").length;
  if (rowCount == 0) {
    $('#btnLimpiarprocedimiento').prop('disabled', false);
  } else {
    $('#btnLimpiarprocedimiento').prop('disabled', true);
  }
}


function observeTableChanges() {
  // Crear una instancia de MutationObserver
  const observer = new MutationObserver(function(mutations) {
    // Llama a updateBtnLimpiarState() cuando haya cambios en la tabla
    updateBtnLimpiarState();
  });

  // Seleccionar el elemento a observar
  const targetNode = document.querySelector("#diagnosticosAgregar tbody");

  // Opciones de observación (atributos, hijos y árbol de elementos)
  const config = { attributes: true, childList: true, subtree: true };

  // Iniciar la observación
  observer.observe(targetNode, config);
}

function updateSelectElements() {
  // Obtener todos los elementos <select> con la clase "select-diagnostico"
  let selectElements = document.querySelectorAll(".select-diagnostico");

  selectElements.forEach((selectElement) => {
    // Almacenar el valor actualmente seleccionado
    let currentValue = selectElement.value;

    // Eliminar las opciones actuales
    selectElement.innerHTML = "";

    // Agregar la opción "Seleccione"
    let defaultOption = document.createElement("option");
    defaultOption.value = "0";
    defaultOption.textContent = "Seleccione";
    selectElement.appendChild(defaultOption);

    // Agregar las opciones basadas en listIdDiagnosticoObjetos
    listIdDiagnosticoObjetos.forEach((item) => {
      let option = document.createElement("option");
      option.value = item.id_diagnostico;
      option.textContent = item.nombre;
      selectElement.appendChild(option);
    });

    // Verificar si el valor seleccionado aún está disponible en las opciones
    let optionExists = Array.from(selectElement.options).some(
      (option) => option.value === currentValue
    );

    // Si el valor 1  e2  ado no existe en las opciones, cambiar a "Seleccione"
    if (!optionExists) {
      selectElement.value = "";
    } else {
      // Si el valor seleccionado aún está disponible en las opciones, restablecer el valor seleccionado
      selectElement.value = currentValue;
      console.log()
    }
  });


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
    const id_prestacion = {{$id_prestacion}};
    const registrar_cita_por_medico = $('#registrar_cita_por_medico').val();
      if (registrar_cita_por_medico == 1) {
        location.href = `/formulario_cita_por_medico/${id_prestacion}`;
      }else {
        console.log('No Puedes Registrar Cita Como Médico');
      }
  }

  function validaRegistrarCitaPorMedico() {
    const id_prestacion = {{$id_prestacion}};
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
  })

  $(document).on('click', '.btnFinalizarLaboratorio', function(e) {
    e.preventDefault();
    firmarLaboratorioSinTokens($(this).data('id-lab'));
  })

  const firmarLaboratorioSinTokens = (id_laboratorio) => {
    let  batchSignatureProcessPoller = setInterval(function() {
      let id_prestacion = $("#id_prestacion").val();
      let proviene_formulario_hosp = $("#proviene_formulario_hosp").val();
      let id_tipo_atencion = $('#id_tipo_atencion').val();
      let postData =  { 
                        id_prestacion : id_prestacion, 
                        proviene_formulario_hosp : proviene_formulario_hosp,
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
    let postData = { id_prestacion : id_prestacion, id_laboratorio : id_laboratorio };
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
      let proviene_formulario_hosp = $("#proviene_formulario_hosp").val();
      let id_tipo_atencion = $('#id_tipo_atencion').val();
      let postData =  { 
                        id_prestacion : id_prestacion, 
                        proviene_formulario_hosp : proviene_formulario_hosp,
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
    $('#guardardiagnosticoseditar').removeAttr('disabled');
    AddDiagnostico();
    updateBtnLimpiarState();
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

  $('#addAlta').on('click', function() {
    var addAlta = $('#addAlta').attr("aria-pressed");
    $("#divAlta2").hide();
    if (addAlta == "false") {
      $("#divAlta2").show();
    }
  });

  // $('#addInterconsulta').on('click', function () {
  //   var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
  //   $("#fsInterconsulta").hide();
  //   if(addInterconsulta == "false"){
  //     $("#fsInterconsulta").show();
  //   }
  // });

  $('#addInterconsulta2').on('click', async function() {
    await AddInterconsulta2();
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
  ////////////////////////////////////////////////////////////////////////////// NUEVA MODIFICACION EXAMEN DE IMÁGENES 04-05-26 //ACTUALIZADO 29-05-26
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
      $('#frmBloque').on('submit', function() {
          enviandoFormulario = true;
      });
    }else{
      $('#addExamenRad2').on('click',function(){
        AddExamenRad();
      });
    }
    ////////////////////////////////////////////////////////////////////////////// FIN MODIFICACION EXAMEN DE IMÁGENES 04-05-26

  $('#example tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
  // Selecciona la fila completa
  var fila = obj.closest('tr');
  
  // Obtiene la posición de la fila
  var filaPosition = fila.index();
    
    // Imprime la posición del elemento 'input' en la consola
    console.log("La posición del input es: " + filaPosition);
    eliminarDiagnosticoXProducto(filaPosition);
    // Elimina la fila
    obj.parent().parent().remove();
  });

  $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });
  $('#diagnosticosAgregar tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
    updateBtnLimpiarState();
  });


  $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#procedimientosagregar tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
    updateBtnLimpiarState2();
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

var submitButton = document.getElementById('guardardiagnosticoseditar');
submitButton.addEventListener('click', enviarFormulario);
function getProducto(){
	
	$('#example tbody').html('');
  $('#cmbMedicamentoTemp').html('');
	var id_ipress 		= $('#id_establecimiento').val();
	var id_consultorio 	= $('#id_servicio').val();
	var id_tiporeceta 	= $('#id_tipo_atencion').val();
	var id_farmacia     = $('#id_farmacia_receta').val();
  
	var nivel     = $('#id_nivel').val();
	var x  = $('#nro_interconsultas').val();
	var dni = $('#dni_beneficiario').val();
	if(id_tiporeceta == "3"){ 
		  if(id_farmacia!=21){ //SIN NUTRICIONAL
  			if (id_farmacia == 10) {
          if (id_consultorio == 44 || id_consultorio == 70) { //onco - hemato
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
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
  			}
  			else
  			{
  			  if (id_consultorio==21){ //odonto
    				$.ajax({
    				  url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 21,
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
    				if (id_consultorio==72){ //aneste
    					$.ajax({
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
              if(id_farmacia != 132){
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
		  }
      else
      {
        if(id_farmacia == 21){ //nutricional
       
          if(id_tiporeceta==5 || id_tiporeceta == 3){
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
      }
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

function enviarFormulario() {

  let arrayselects=[];
  var msg = "";
  var CSRF_TOKEN = $('meta[name="_token"]').attr('content');

  let nombreDiagnosticoElements = document.querySelectorAll('input[name="nombre_diagnostico[]"]');
  let idTipoDiagnosticoElements = document.querySelectorAll('select[name="id_tipo_diagnostico[]"]');

  let nombreDiagnosticoArray = Array.from(nombreDiagnosticoElements);
  let idTipoDiagnosticoArray = Array.from(idTipoDiagnosticoElements);

  let nombreDiagnosticoValues = nombreDiagnosticoArray.map(function (input) {
    return input.value;
  });

  let idTipoDiagnosticoValues = idTipoDiagnosticoArray.map(function (select) {
    return select.value;
  });

  console.log(nombreDiagnosticoValues);
  console.log(idTipoDiagnosticoValues);

  // Crear un objeto FormData
  var formData = new FormData();
  formData.append('_token', CSRF_TOKEN);
  formData.append('idprestacionnuevo', id_prestacion);

  // Agregar los elementos al objeto FormData
  nombreDiagnosticoValues.forEach(function (value, index) {
    formData.append('nombre_diagnostico_array[]', value);
  });

  idTipoDiagnosticoValues.forEach(function (value, index) {
    formData.append('id_tipo_diagnostico_array[]', value);
  });

  var ind_diag = $('#diagnosticosAgregar tbody tr').length;
  if (ind_diag > 0) {
    fila_dignosticos = $('#diagnosticosAgregar tbody').children('tr');
    for (i = 0; i < ind_diag; i++) {
      fila_dignostico = fila_dignosticos[i];
      var id_diagnostico = $(fila_dignostico).find('.id_diagnostico').val();
      var id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico').val();
      if (id_diagnostico == "") msg += "Debe ingresar el nombre de diagnostico<br>";
      if (id_tipo_diagnostico == "0") msg += "Debe ingresar el tipo de diagnostico<br>";
    }
  } else {
    msg += "Debe ingresar minimo un diagnostico<br>";
  }

  if (msg != "") {
    bootbox.alert(msg);
    $('#guardardiagnosticoseditar').prop("disabled", false);
    $('.loader').hide();
    return false;
  } else {
    console.log("se guardo");

    // Utilizar AJAX para enviar los datos
    $.ajax({
      url: "{{ route('guardar_nuevos_diagnosticos') }}",
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        $.ajax({
                    type: 'POST',
                         url: "/updatediagnosticos", 
                         data: {
                    'idprestacion': id_prestacion,
                    '_token': CSRF_TOKEN
                },
                    dataType: 'json',
                    success: function(updatedData) {
                      console.log("la lista select es  " + JSON.stringify(updatedData));
                      $('#diagnosticosAgregar tbody').empty();
                      $('#guardardiagnosticoseditar').prop("disabled", true);
                      
                    renderTableData(updatedData);
                    arrayselects=updatedData;
                    console.log("debajo arrayselects");
                    listIdDiagnosticoObjetos = arrayselects;
                    updateSelectElements();
                    console.log(arrayselects);
                   },
                   error: function(jqXHR, textStatus, errorThrown) {
                  // Manejar errores aquí, si los hay
                  }
                   });
        console.log(response);
      
      },
      error: function (xhr, status, error) {
        // Aquí puedes manejar los errores en la solicitud
        console.error(xhr.responseText);
        // Realizar acciones en caso de error (por ejemplo, mostrar un mensaje de error)
      }
    });
  }
}




var submitButton2 = document.getElementById('procedimientoboton');
submitButton2.addEventListener('click', enviarFormularioProcedimientos);

function enviarFormularioProcedimientos() {
  var msg = "";
  var CSRF_TOKEN = $('meta[name="_token"]').attr('content');

  let nombreprocedimiento = document.querySelectorAll('input[name="nombre_procedimiento[]"]');
  let cantidadesElemets = document.querySelectorAll('input[name="procedimiento_cantidad[]"]');
  let diagnosticosSeleccionadosElements = document.querySelectorAll('select[name="miSelect[]"]');

  let nombreprocedimientoarray = Array.from(nombreprocedimiento);
  let cantidadesArray = Array.from(cantidadesElemets);
  let arraydiagnosticosxprocedimiento = Array.from(diagnosticosSeleccionadosElements);

  let nombreprocedimientoValues = nombreprocedimientoarray.map(function (input) {
    return input.value;
  });

  let cantidadesvalues = cantidadesArray.map(function (input) {
    return input.value;
  });

  let DiagnosticosXprocedimientosvalues = arraydiagnosticosxprocedimiento.map(function (select) {
    return select.value;
  });

  console.log(nombreprocedimientoValues);
  console.log(cantidadesvalues);
  console.log(DiagnosticosXprocedimientosvalues);

  var ind_diagproc = $('#procedimientosagregar tbody tr').length;
  if (ind_diagproc > 0) {
    fila_procedimientos = $('#procedimientosagregar tbody').children('tr');
    for (i = 0; i < ind_diagproc; i++) {
      var fila_actual = fila_procedimientos[i];
      var id_procedimientor = $(fila_actual).find('.id_procedimiento').val();
      var id_diagnosticoProcedimiento = $(fila_actual).find('.select-diagnostico').val();
      if (id_procedimientor == "") msg += "Debe ingresar el nombre el nombre del procedimiento<br>";
      if (id_diagnosticoProcedimiento == "0") msg += "Debe seleccionar el diagnostico<br>";
    }
  } else {
    msg += "Debe ingresar minimo un procedimiento <br>";
  }

  if (msg != "") {
    bootbox.alert(msg);
    $('#guardardiagnosticoseditar').prop("disabled", false);
    $('.loader').hide();
    return false;
  } else {
    console.log("se guardo");

    var data = {
      _token: CSRF_TOKEN,
      idprestacionnuevo: id_prestacion,
      nombre_procedimiento_array: nombreprocedimientoValues,
      cantidades_procedimiento_array: cantidadesvalues,
      diagnosticoxProcedimiento_array: DiagnosticosXprocedimientosvalues
    };

    $.ajax({
      url: "{{ route('guardar_nuevos_procedimientos_x_diagnosticos') }}",
      type: 'POST',
      data: data,
      success: function (response) {
        console.log(response);
        $.ajax({
                    type: 'POST',
                         url: "/updateprocedimientos", 
                         data: {
                    'idprestacion': id_prestacion,
                    '_token': CSRF_TOKEN
                },
                    dataType: 'json',
                    success: function(updatedData) {
                      console.log(JSON.stringify(updatedData));
                      $('#procedimientosagregar tbody').empty();
                      $('#procedimientoboton').prop("disabled", true);
                      renderProcedimientosTableData(updatedData);
                     //eliminarDiagnosticosarr(cod);
                  
                   },
                   error: function(jqXHR, textStatus, errorThrown) {
                  // Manejar errores aquí, si los hay
                  }
                   });
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
      }
    });
  }

}
function escapeHtml(unsafe) {
  return unsafe
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}



function getDiagnosticoNombreReal(diagnostico_id) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "/obtenernombrediagnostico",
      type: "POST",
      data: { id: diagnostico_id },
      success: function (response) {
        console.log(response);
        const diagnostico_nombre = response.replace(/"/g, '');
        resolve(diagnostico_nombre);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        reject(errorThrown);
      },
    });
  });
}

async function renderProcedimientosTableData(data) {
  var tableBody = $("#tblProcedimiento tbody");
  tableBody.empty();

  const rowsPromises = data.map(async function (row) {
    const diagnostico_nombre = await getDiagnosticoNombreReal(row.id_diagnostico);

    var tableRow = `
      <tr class="row2-${row.cod}">
        <td>
          <input type="text" name="codigodeprod['${row.cod}']" class="form-control input-procedimiento" disabled value="${row.codigo}" style="border: none; outline: none; background-color: transparent;" readonly>
        </td>
        <td>
          <input name="id_procedimientos['${row.cod}']" id="idProcedimientos" class="input-procedimiento form-control" value="${row.nombre}" disabled style="border: none; outline: none; background-color: transparent;" readonly>
        </td>
        <td>
          <input type="text" name="cantidad_procedimientos['${row.cod}']" class="form-control input-procedimiento" disabled value="${row.cantidad}" style="border: none; outline: none; background-color: transparent;" readonly>
        </td>
        <td>
          <input name="iddiagnosticosxProcedimiento['${row.id_diagnostico}']" id="iddiagnosticosxProcedimiento" class="input-procedimiento form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly value="${escapeHtml(diagnostico_nombre)}">
        </td>
        <td>
          <button type="button" onclick="eliminarProcedimiento('${row.cod}', '${id_prestacion}');">Eliminar</button>
        </td>
      </tr>
    `;
    return tableRow;
  });

  const rows = await Promise.all(rowsPromises);

  rows.forEach((tableRow) => {
    tableBody.append(tableRow);
  });
}







async function guardarPrestacion() {

  var msg = "";
  // Obtener todos los elementos <select> por su atributo 'name'
  // let cantidad_row_interconsulta = $('.id_servicio_interconsulta').length;

  // Función para obtener los valores seleccionados de un elemento <select>
  // Obtener todos los elementos <select> por su atributo 'name'
  // Obtener todos los elementos <select> por su atributo 'name'
  // Obtener todos los elementos <select> por su atributo 'name idproductos'
  const selectElements = document.querySelectorAll('select[name="id_producto[]"]');

  // Función para obtener los valores seleccionados de un elemento <select>
  function getSelectedValues(select) {
    const selectedOptions = Array.from(select.selectedOptions);
    const selectedValues = selectedOptions.map(option => option.value);
    return selectedValues;
  }

  // Obtener los valores seleccionados de todos los elementos <select> y guardarlos en un único array
  const id_productosselect = Array.from(selectElements)
  .map(getSelectedValues)
  .flat();

  // Mostrar los valores seleccionados en la consola



  // Función para obtener el valor de un elemento <input>
  function getInputValue(input) {
    return input.value;
  }

  const nro_stocks_almacen = [];
  const nro_stocks_establecimiento = [];
  const nro_stocks = [];
  const tiempo_periodo = [];
  const posologia = [];
  const nro_med_solictados = [];

  const inputArrays = {
    'nro_stocks_almacen[]': nro_stocks_almacen,
    'nro_stocks_establecimiento[]': nro_stocks_establecimiento,
    'nro_stocks[]': nro_stocks,
    'tiempo_periodo[]': tiempo_periodo,
    'posologia[]': posologia,
    'nro_med_solictados[]': nro_med_solictados
  };

  Object.keys(inputArrays).forEach(name => {
    const inputElements = document.querySelectorAll(`input[name="${name}"]`);
    inputArrays[name].push(...Array.from(inputElements).map(getInputValue));
  });

  const farmaciaSelect = document.getElementById("id_farmacia_receta");

  // Obtener el valor del <select>
  const farmaciaValue = farmaciaSelect.value;

  // Almacenar el valor en una variable (en este caso, simplemente lo imprimiré en la consola)


  // Mostrar los valores en la consola
  console.log("arrays generales debajo");
  console.log(id_productosselect);
  console.log(nro_stocks_almacen);
  console.log(nro_stocks_establecimiento);
  console.log(nro_stocks);
  console.log(tiempo_periodo);
  console.log(posologia);
  console.log(nro_med_solictados);
  console.log(farmaciaValue);


  $('input[name="id_productosselect"]').val(id_productosselect);
  $('input[name="nro_stocks_almacensecuen"]').val(nro_stocks_almacen);

  $('input[name="nro_stocks_establecimientosecuen"]').val(nro_stocks_establecimiento);

  $('input[name="nro_stockssecuen"]').val(nro_stocks);

  $('input[name="tiempo_periodosecuen"]').val(tiempo_periodo);

  $('input[name="posologiasecuen"]').val(posologia);

  $('input[name="nro_med_solictadossecuen"]').val(nro_med_solictados);
  $('input[name="farmaciavalor"]').val(farmaciaValue);

  $('#procedimientosSeleccionados1').val(JSON.stringify(procedimientosSeleccionados));
  let fechaExpedicion = $('#fecha_expedicion').val();
  $('#fechexpedicion').val(fechaExpedicion);

  // Laboratorio - Hora Toma Muestra
  const tblExamenLab_cantidad_tr = $('#tblExamenLab tbody tr').length;
  if (tblExamenLab_cantidad_tr > 0) {
    const id_hora_toma_muestra = document.getElementById('id_hora_toma_muestra');
    const lab_orden_observacion = document.getElementById('lab_orden_observacion');
    const lab_orden_toma_muestra = document.getElementById("lab_orden_toma_muestra");

    if (!id_hora_toma_muestra.value) {
      const result = await Swal.fire({
        title: 'ALERTA',
        width: 400,
        confirmButtonText: 'GUARDAR',
        customClass: { confirmButton: 'btn btn-success' },
        buttonsStyling: false,
        html: `
            <div style="text-align: left">
              <label for="">Debe seleccionar la hora de la toma de muestra</label>
              <div class="radio">
                <label>
                  <input type="radio" name="hora" value="1">05:00 AM
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="hora" value="2">09:00 AM
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="hora" value="3">16:00 PM
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="hora" value="4">22:00 PM
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="hora" id="radio_otro" value="0">OTRO
                </label>
              </div>
              <textarea id="obs_otro" placeholder="Observación..." class="form-control" style="display:none; width:100%; margin-top:8px;" rows="2">${lab_orden_observacion.value}</textarea>
            </div>
        `,
        didOpen: () => {
          document.getElementById('radio_otro').addEventListener('change', function () {
            document.getElementById('obs_otro').style.display = this.checked ? 'block' : 'none';
          });
        },
        preConfirm: () => {
          const seleccionado = document.querySelector('input[name="hora"]:checked');
          if (!seleccionado) {
              Swal.showValidationMessage('Seleccione una opción');
              return false;
          }
          if (seleccionado.value === '0') {
            const obs = document.getElementById('obs_otro').value.trim();
            if (!obs) {
              Swal.showValidationMessage('Ingrese una observación');
              return false;
            }
            lab_orden_observacion.value = obs;
            return '0';
          }

          return seleccionado.value;
        }
      }); 
      
      if (!result.isConfirmed) return;
      
      id_hora_toma_muestra.value = result.value;

      const radioPrincipal = document.getElementById('hora_toma_muestra__' + result.value);
      if (radioPrincipal) {
          radioPrincipal.checked = true;
      }

      let descripcionHoraTomaMuestra = "";
      switch (result.value) {
        case "0": descripcionHoraTomaMuestra = "OTRO"; break;
        case "1": descripcionHoraTomaMuestra = "05:00 AM"; break;
        case "2": descripcionHoraTomaMuestra = "09:00 AM"; break;
        case "3": descripcionHoraTomaMuestra = "16:00 PM"; break;
        case "4": descripcionHoraTomaMuestra = "22:00 PM"; break;
      }

      lab_orden_toma_muestra.value = descripcionHoraTomaMuestra;
    } else if (id_hora_toma_muestra.value == "0" && lab_orden_observacion.value.trim() == "") {
      msg += "Al seleccionar la Hora de Toma de Muestra en <b>OTRO</b><br>debe ingresar el detalle en el campo de <b>Observación de la Orden de Laboratorio</b>.<br>";
    }
  }

  // Laboratorio
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

  // Interconsulta
  let tblInterconsulta_cantidad_tr = $('#tblInterconsulta tbody tr').length;
  if (tblInterconsulta_cantidad_tr > 0) {
    let interconsulta_tr = $('#tblInterconsulta tbody').children('tr');

    for (i = 0; i < tblInterconsulta_cantidad_tr; i++) {
      let fila_interconsulta = interconsulta_tr[i];

      let id_servicio_interconsulta = $(fila_interconsulta).find('[name="id_servicio_interconsulta[]"]').val();
      let motivo_interconsulta = $(fila_interconsulta).find('textarea[name="motivo_interconsulta[]"]').val();

      if (id_servicio_interconsulta == "") msg += "Debe seleccionar el Servicio de Interconsulta<br>";
      if (motivo_interconsulta == "") msg += "Debe ingresar el Motivo de la Interconsulta<br>";

      if (id_servicio_interconsulta == 27) {
        let id_establecimiento = $('#id_establecimiento').val();
        const cantidad = await obtenerCantidadInterconsulta({
          idEstablecimiento : id_establecimiento,
          idServicio : id_servicio_interconsulta,
          tipo: 3
        });
        
        if (cantidad >= 20) {
          msg += "No se puede adicionar la Interconsulta a <b>CARDIOLOGIA</b> porque supera la cantidad estimada por el Servicio.<br>";
        }
      }
    }
  }

  
  if (msg != "") {
    bootbox.alert(msg);
    return false;
  } else {
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
  
  if (ind > 0) {
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA INICIO =================
    /*console.log('ENTRO EN CONDICIÓN');
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
    }*/
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA FIN =================
    //=== VALIDA SOLICITUD EXAMEN ESPECIALIZADO HEMOCULTIVO INICIO ============================
    /*let descripcion_examen = $('#descripcion_examen' + (ind - 1)).val();
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
    }*/
    //=== VALIDA SOLICITUD EXAMEN ESPECIALIZADO HEMOCULTIVO FIN ===============================
    //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================
    /*if (descripcion_examen == 'CITOQUIMICO LIQUIDOS BIOLOGICOS') {
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
    });*/
    ind = obtenerIndiceTblExamenLab();

    /*fila_medicamentos = $('#tblExamenLab tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];*/

  }
  const id_nivel = $("#id_nivel").val();
  newRow +='<tr>';
    newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id="'+ind+'">';
  newRow +='<input type="hidden" class="form-control id_categoria" id="id_categoria_examen_lab' + ind +'" name="id_categoria_examen_lab[]" value="" data-id='+ind+'></td>';
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_lab_cantidad" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1" readonly></td>';
  if (id_nivel && parseInt(id_nivel) < 3) {
    newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
  }
  newRow += '<td><select class="form-control select-diagnostico" id="miSelectLab' + ind + '" name="miSelectLab[]"></select></td>';
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
  genero = getSexoGenero();
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  //tipo=2

  $("#nombre_examen_lab" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        //url: '../../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 2,
        url: '../../obtener_laboratorio_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion,
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
      $("#id_examen_lab" + ind).val(id_examen_lab);
      $("#id_categoria_examen_lab" + ind).val(id_categoria_examen_lab);
      $("#nombre_examen_lab" + ind).prop("readonly", true);
      //error de función indefinida
      //validaExamenSeleccionado($("#id_examen_lab" + ind).val());
      //consultaWsResultExamenSeleccionado($("#id_examen_lab" + ind).val());
      /*if (id_categoria_examen_lab == "9") {
        mostrarBotonParaTipoDeMuestraMicrobiologia(ind);
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
          mostrarBotonParaSolicitudHemocultivoPedriatria(ind);
        } else {
          // SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO
          mostrarBotonParaSolicitudHemocultivoAdulto(ind);
        }
      }
      // SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS
      if (codigo_procedimiento == '87163') {
        mostrarBotonParaSolicitudCitoquimicosLiquidosBiologicos(ind);
      }*/
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

async function  AddInterconsulta2(){
  let data_del_dia;
  let mostrar_servicio = false;
  let horario_sin_bloqueo = false;

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
      horaLimiteInicio.setHours(07, 0, 0, 0);

      // Definir la hora límite fin (13:30)
      const horaLimiteFin = new Date();
      horaLimiteFin.setHours(12, 0, 0, 0);

      if (horaActual < horaLimiteInicio || horaActual > horaLimiteFin) {
          let is_estable = $("#is_estable").val();

          if (is_estable == 0) { // INESTABLE
            mostrar_servicio = true;

          } else { // ESTABLE
            // Ahi nomas se queda
            // No aparece Servicio ni Motivo
            bootbox.alert("<br><span class='text-warning'><i class='fa fa-exclamation-circle'></i></span> Esta Interconsulta debe realizarse de Lunes a Sábados <b>de 07:00 am</b> hasta <b>12:00pm</b><br>De lo contrario registrar en el Plan de Trabajo.");
          }

      } else {
        // La Interconsulta se registrar normal sin validaciones
        // Esta en el rango de 7 a 12 Lunes a sabado
        horario_sin_bloqueo = true;
        mostrar_servicio = true;
      }
    } else {
      // Si es Domingo
      let is_estable = $("#is_estable").val();
      if (String(is_estable) == "0") { // INESTABLE
        mostrar_servicio = true;

      } else { // ESTABLE
        // Ahi nomas se queda
        // No aparece Servicio ni Motivo
        bootbox.alert("<br><span class='text-warning'><i class='fa fa-exclamation-circle'></i></span> Esta Interconsulta debe realizarse de <b>Lunes a Sábados</b> de 07:00 am hasta 12:00pm.");
      }
    }
  } catch (error) {
    bootbox.alert("<span class='text-danger'><i class='fa fa-exclamation-triangle'></i></span> Ha ocurrido un error al validar el horario.<br><br>" + error);
  }

  if (mostrar_servicio) {
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
var item_OTORRINO = '<option value="0">--Seleccionar--</option>';
var item_DIAGNOSTICO = '<option value="0">--Seleccionar--</option>';

// AJAX para OTORRINOLARINGOLOGÍA
  $.ajax({
      url: '../../cargar_upsses',
      method: 'GET',
      success: function (data) {
          $.each(data, function (index, item) {
              item_OTORRINO += '<option value="' + item.codigo + '">' + item.descripcion_upss + '</option>';
          });
          $("#select_OTORRINO" + ind).html(item_OTORRINO);
      }
  });

  // AJAX para DIAGNOSTICO E IMAGENES
  $.ajax({
      url: '../../cargar_ups_diagnostico',
      method: 'GET',
      success: function (data) {
          $.each(data, function (index, item) {
              item_DIAGNOSTICO += '<option value="' + item.codigo + '">' + item.descripcion_upss + '</option>';
          });
          $("#select_DIAGNOSTICO" + ind).html(item_DIAGNOSTICO);
      }
  });

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
  newRow +='            <div class="col-md-4">'; // Inicio select Otorrino
  newRow +='              <select id="select_OTORRINO' + ind + '" class="form-control" style="display:none"></select>';
  newRow +='              <input type="hidden" id="hidden_select_OTORRINO' + ind + '" name="hidden_select_OTORRINO[]" value="">';
  newRow +='            </div>'; // Fin select Otorrino
  newRow +='            <div class="col-md-4">'; // Inicio select Diagnostico
  newRow +='              <select id="select_DIAGNOSTICO' + ind + '" class="form-control" style="display:none"></select>';
  newRow +='              <input type="hidden" id="hidden_select_DIAGNOSTICO' + ind + '" name="hidden_select_DIAGNOSTICO[]" value="">';
  newRow +='            </div>'; // Fin select Diagnostico
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

  var id_ipress = $('#id_establecimiento').val();

  $('#tblInterconsulta tbody').append(newRow);
  $("#id_servicio_interconsulta" + ind).select2({max_selected_options: 4}).focus();

 $("#nombre_servicio_interconsulta" + ind).on('keyup change', function () {
          checkSelectInterconsulta(ind);
      });

      function checkSelectInterconsulta(ind) {
          var inputValue = $("#nombre_servicio_interconsulta" + ind).val().toUpperCase().trim();

          if (inputValue === "OTORRINOLARINGOLOGÍA") {
              $("#select_OTORRINO" + ind).show();
              $("#select_OTORRINO" + ind).val('0');
          } else {
              $("#select_OTORRINO" + ind).hide();
              $("#hidden_select_OTORRINO" + ind).val('');
          }

          if (inputValue === "DIAGNOSTICO E IMAGENES") {
              $("#select_DIAGNOSTICO" + ind).show();
              $("#select_DIAGNOSTICO" + ind).val('0');
          } else {
              $("#select_DIAGNOSTICO" + ind).hide();
              $("#hidden_select_DIAGNOSTICO" + ind).val('');
          }
      }

      // Capturar cambios en select OTORRINO
      $("#select_OTORRINO" + ind).on("change", function () {
          var selectedValue = $(this).val();
          $("#hidden_select_OTORRINO" + ind).val(selectedValue);
      });

      // Capturar cambios en select DIAGNOSTICO
      $("#select_DIAGNOSTICO" + ind).on("change", function () {
          var selectedValue = $(this).val();
          $("#hidden_select_DIAGNOSTICO" + ind).val(selectedValue);
      });

      $("#id_servicio_interconsulta" + ind).on("change", async function(e) {
        let flagx = 0;
        cmb = $(this);
        let interconsulta_servicio_id = $("#id_servicio_interconsulta" + ind).val();

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

            if (interconsulta_servicio_id == 27) {
              const idEstablecimiento = $('#id_establecimiento').val();

              const cantidad = await obtenerCantidadInterconsulta({
                idEstablecimiento : idEstablecimiento,
                idServicio : interconsulta_servicio_id,
                tipo: 3
              });
              
              if (cantidad >= 20) {
                bootbox.alert('No se puede adicionar esta interconsulta porque supera la cantidad estimada por el Servicio.');
                $('#id_servicio_interconsulta' + ind).val('').trigger('change');
                return;
              }
            }

            if (horario_sin_bloqueo) { // Horario entre las 7 - 12 pm

              $("#panel_interconsulta_resumen_" + ind).css('display', 'block');
              let $select = $("#id_servicio_interconsulta" + ind);

              // Bloquear visualmente Select2
              $select.next('.select2-container').css({
                'pointer-events': 'none',
                'background-color': '#eee',
                'opacity': 0.8
              });

            } else {
            let has_guardia = $(this).find('option:selected').data('has_guardia');

            if (has_guardia == 1) { // El Servicio si hace guardia
              Swal.fire({
                title: 'MENSAJE',
                text: 'Recuerde comunicar a la especialidad solicitante del registro de la Interconsulta.',
                icon: 'info',
                confirmButtonText: 'Aceptar'
              });

              $("#panel_interconsulta_resumen_" + ind).css('display', 'block');

              let $select = $("#id_servicio_interconsulta" + ind);

              // Bloquear visualmente Select2
              $select.next('.select2-container').css({
                'pointer-events': 'none',
                'background-color': '#eee',
                'opacity': 0.8
              });

            } else if (has_guardia == 0) { // El Servicio no hace guardia
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
            } // fin condicion horario_sin_bloqueo
          }
        }

      });
    }
  }

  async function obtenerCantidadInterconsulta({ idEstablecimiento, idServicio, tipo = 3 }) {
    const url = `{{ url("/") }}/interconsulta_obtener_cantidad/${idEstablecimiento}/${idServicio}/${tipo}`;

    const resp = await fetch(url, {
      method: 'GET',
      headers: { 'Accept': 'application/json' }
    });
    
    if (!resp.ok) throw new Error('Error al consultar interconsulta_obtener_cantidad');
    const data = await resp.json();
    return Number(data?.cantidad ?? 0);
  }

async function validarCantidadInterconsulta(ind, id_servicio) {

  function limpiarCampos(ind) {
    $("#id_servicio_interconsulta" + ind).val('');
    $("#nombre_servicio_interconsulta" + ind).val('');
    $("#nombre_servicio_interconsulta" + ind).prop("readonly", false);
  }

  // if (id_servicio == 27) {
    let id_establecimiento = $('#id_establecimiento').val();
    try {
      const is_feriado = await $.ajax({
        url: "{{ url('validar_fecha_actual_es_feriado') }}/",
        dataType: 'json',
      });

      if (is_feriado) {
        showValidacionCantInterconsulta = true;
        bootbox.alert("No se puede adicionar interconsultas en feriados para <b>pacientes estables</b>.");
        limpiarCampos(ind);
        return;

      } else {
      const response = await $.ajax({
        url: "{{ url('interconsulta_obtener_cantidad') }}/" + id_establecimiento + "/" + id_servicio + "/3",
        dataType: 'json',
      });

      if (response.dia_semana_numero != DIA_SEMANA_DOMINGO) {
        const horaActual = new Date();
        const [horas, minutos] = response.hora_actual.split(':').map(Number);
        horaActual.setHours(horas, minutos, 0, 0);

        // Definir la hora límite inicio (07:30)
        const horaLimiteInicio = new Date();
        horaLimiteInicio.setHours(07, 0, 0, 0);

        // Definir la hora límite fin (13:30)
        const horaLimiteFin = new Date();
        horaLimiteFin.setHours(12, 0, 0, 0);

        if (horaActual < horaLimiteInicio || horaActual > horaLimiteFin) {
          // if (parseInt(response.cantidad) >= 17) {
            showValidacionCantInterconsulta = true;
            bootbox.alert({
              message: "No se puede adicionar esta interconsulta porque está fuera del rango de horario <b>(07:00 AM - 12:00 PM)</b> establecido para pacientes Estables.",
              className: 'custom-small-alert', // Clase personalizada para el diálogo
              callback: function () {
                limpiarCampos(ind);
              }
            });
            // bootbox.alert("No se puede adicionar esta interconsulta porque supera la cantidad estimada por el Servicio.");
            // limpiarCampos(ind);
            return;
          // }
        } /*else {
          showValidacionCantInterconsulta = true;
          bootbox.alert({
            message: "No se puede adicionar esta interconsulta porque <b>esta fuera del horario (1 PM)</b> establecido por el servicio para solicitar Interconsultas.",
            className: 'custom-small-alert', // Clase personalizada para el diálogo
            callback: function () {
              limpiarCampos(ind);
            }
          });
          return;
        }*/
      } else {
        showValidacionCantInterconsulta = true;
        bootbox.alert({
          message: "El registro de Interconsultas para pacientes estables es de Lunes a Sábado.",
          className: 'custom-small-alert', // Clase personalizada para el diálogo
          callback: function () {
            limpiarCampos(ind);
          }
        });
        return;
      }
      } // .is_feriado
    } catch (error) {
      bootbox.alert({
        message: "<span class='text-danger'><i class='fa fa-exclamation-triangle'></i></span> Ha ocurrido un error al validar el horario.",
        className: 'custom-small-alert', // Clase personalizada para el diálogo
        callback: function () {
          //
        }
      });
      return;
    }
  // }
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
  $("#examen_rad_cantidad" + ind).on('input', function() {
    var valor = $(this).val();
    if (valor === '0') {
      $(this).val(''); // Eliminar el valor si ingresan 0
    }
  });
  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  const id_servicio = $('#id_consultorio_h').val();
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
        url: '../../obtener_radiologia_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 3 + '/' + 105,
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
      $("#examen_rad_cantidad" + ind).val("1");
      return false;
    }
  });

  $("#examen_rad_cantidad" + ind).on('blur', function() {
    var id_examen_rad = $("#id_examen_rad" + ind).val();
    if (id_examen_rad == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen radiografia");
      $("#examen_rad_cantidad" + ind).val("1");
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
  var ind = $('#diagnosticosAgregar tbody tr').length;

  if (ind > 0) {
    $('.id_diagnostico').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });

    fila_medicamentos = $('#diagnosticosAgregar tbody').children('tr');
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
  $('#procedimientoboton').removeAttr('disabled');
  var newRow = "";
  var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  var ind = $('#procedimientosagregar tbody tr').length;

  if (ind > 0) {
    $('.id_procedimiento').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });
					
    fila_medicamentos = $('#procedimientosagregar tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];
  }

  newRow += '<tr>';
  newRow +=
    '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" required="" id="nombre_procedimiento' +
    ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento' + ind +
    '" name="id_procedimiento[]" value="" data-id='+ind+'>';
    newRow += '<input type="hidden" class="codigoprocedimiento" id="codigoprocedimiento' + ind + '" name="codigoprocedimiento[]" value=""></td>';
  newRow +=
    '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad' +
    ind + '" name="procedimiento_cantidad[]" value="1"></td>';
    //lrojas
  newRow += '<td><select class="form-control select-diagnostico"  id="miSelect' + ind + '" name="miSelect[]"></select></td>';
  newRow +=
    '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';


  $('#procedimientosagregar tbody').append(newRow);
  const selectElement = document.getElementById('miSelect' + ind);
  let defaultOption = document.createElement('option');
  defaultOption.value = "0";
  defaultOption.textContent = "Seleccione";
  defaultOption.setAttribute("selected", "selected"); // Establecer la opción "Seleccione" como seleccionada por defecto
  selectElement.appendChild(defaultOption);
   // Agregar opciones al elemento <select>


    //lrojas
 //si entra la primera vez

  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id_diagnostico;
    option.textContent = item.nombre;
    selectElement.appendChild(option);
  });

  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  if (sexo == 'M')
    genero = 1;
  else
    genero = 2;
	
  //tipo = 1


  $("#nombre_procedimiento" + ind).autocomplete({
  minLength: 1,
  delay: 400,
  source: function(request, response) {
    $.ajax({
      type: "POST", // Agrega el tipo de solicitud como POST
      url: '/obtener_procedimiento_convenio_ver_prestacion',
      dataType: "json",
      data: { // Agrega los datos a enviar en la solicitud POST
        'term': request.term,
        'nivel': nivel,
        'genero': genero,
        'param': 1,
        '_token': CSRF_TOKEN // Añade el token CSRF para proteger la solicitud POST
      },
      success: function(data) {
        let listaProcedimientoSelecionado = $(".id_procedimiento").map(function(){return parseInt($(this).val());}).get();
        let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
        response(filtro);
      }
    });
  },
  select: function(e, ui) {
    var value = ui.item.label;
    var labelParts = value.split(" - ");
    var firstValue = labelParts[0];
    console.log(firstValue);
    var id_procedimiento = ui.item.id;
    $('#codigoprocedimiento'+ ind).val(firstValue);
    $("#id_procedimiento" + ind).val(id_procedimiento);
    $("#nombre_procedimiento" + ind).prop("readonly", true);
  }
});

	
  $("#nombre_procedimiento" + ind).on('blur', function() {
    var codigo_procedimiento = $('#codigoprocedimiento'+ ind).val();
    var id_procedimiento = $("#id_procedimiento" + ind).val();
    if (id_procedimiento == "") {
      bootbox.alert("Falta ingresar el procedimiento en esta fila");
      $("#nombre_procedimiento" + ind).val("");
      return false;
    }   else if (codigosprocedimientos.includes(codigo_procedimiento)) {
        bootbox.alert("El diagnostico ya se encuentra registrado");
        $("#nombre_procedimiento" + ind).val(""); 
        $("#id_procedimiento" + ind).val("");
        $("#codigoprocedimiento" + ind).val("");
        $("#nombre_procedimiento" + ind).prop("readonly", false);
        return false;
      

    }
  });
  $("#procedimiento_cantidad" + ind).on('blur', function() {
    var id_procedimiento = $("#id_procedimiento" + ind).val();
    if (id_procedimiento == "") {
      bootbox.alert("Falta ingresar la cantidad en este procedimiento");
      $("#procedimiento_cantidad" + ind).val("");
      return false;
    }
  });
	
}

function eliminarDiagnosticoXProducto(posicion) {
  // Se elimina el elemento en la posición dada
  procedimientosSeleccionados.splice(posicion, 1);

  // Se actualiza la posición de los elementos restantes
  procedimientosSeleccionados.forEach((item, index) => {
    item.posicion = index;
  });
  console.log(procedimientosSeleccionados);
}

function actualizararray(selectedId,selectedcodigo,ind) {
  if (procedimientosSeleccionados.length < 1) {
  let obj = {
    posicion: ind,
    id: selectedId,
    codigo:selectedcodigo
  }
  procedimientosSeleccionados.push(obj);
} else {
  let newArray = procedimientosSeleccionados.map((elemento) => {
    if (elemento.posicion === ind) {
      elemento.id = selectedId; // si el objeto existe, actualiza el id
      elemento.codigo =selectedcodigo;
    }
    return elemento;
  });
  if (!newArray.some((elemento) => elemento.posicion === ind)) {
    let obj = {
      posicion: ind,
      id: selectedId,
      codigo:selectedcodigo
    }
    newArray.push(obj); // si no existe, agrega un nuevo objeto
  }
  procedimientosSeleccionados = newArray; // actualiza el array original
}

console.log(procedimientosSeleccionados);
 

}
function abrirModal(ind) {
  $('#modal-content').empty();
  let arraydeprocedimientos =listIdDiagnosticoObjetos;
console.log(arraydeprocedimientos);


// Agregando los objetos del array a la tabla del modal
 let tableRows = arraydeprocedimientos.map(function (item) {
return '<tr class="table-row"><td>' + item.id_diagnostico + '</td><td>' + item.codigo + '</td><td>' + item.nombre + '</td></tr>';
}).join('');
$('#modalArrayList .modal-body #procedimientosTable tbody').html(tableRows);

// Agregar controlador de eventos al hacer clic en las filas de la tabla
$('#modalArrayList .modal-body #procedimientosTable tbody tr').on('click', function() {
  let selectedId = $(this).find('td:first').text();//aqui
  let selectedcodigo = $(this).find('td:nth-child(2)').text();
  let selectedName = $(this).find('td:nth-child(3)').text();
  console.log("posicion" + ind + "el id es" +selectedId );
  actualizararray(selectedId,selectedcodigo,ind);
  $('#modalArrayList').modal('hide');
});

$('#modalArrayList').modal('show');

}


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
  var ind = filasmedicamentos
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
    <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):  ?>
    fila_medicamento = fila_medicamentos[idval];
    var valante = $(fila_medicamento).find('#nro_med_entregados').val();
    if (valante == "") {
      bootbox.alert("Ingrese cantidad entregada en el producto anterior");
      return false;
    }
    <?php //endif; ?>

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
    id_user = {{ Auth::user()-> id }};

    $('.cmbMedicamento').each(function() {
      var ind_tmp = $(this).val();
      if ($(this).val() == id_producto) flagx++;
    });

    if (flagx > 1) {
      bootbox.alert("El producto farmaceutico ya ha sido ingresado");
      $("#cmbMedicamento" + ind).val("").trigger("change");
      return false;
    }
    id_farmacia_receta = $('#id_farmacia_receta').val();
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
      <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):
        ?>
      $(cmb).closest("tr").find(".tiempo_periodo").focus();
      <?php //endif;
        ?>
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
          location.href = '/all_hospitalizado_by_establecimiento';
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
			url: "{{url()?>/validarFirma",
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

function eliminar(cod, idprestacion) {
  // Muestra un mensaje de confirmación antes de eliminar el diagnóstico
  Swal.fire({
    title: "¿Estás seguro?",
    text: "La receta con este diagnóstico también se invalidará.",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      var row = document.querySelector(".row-" + cod);
      // Elimina la fila de la tabla
      if (row) {
        // row.parentNode.removeChild(row);
      }

      var CSRF_TOKEN = $('meta[name="_token"]').attr("content");
      $.ajax({
        type: "POST",
        url: "/eliminardiagnosticos",
        data: {
          codigo: cod,
          idprestacion: idprestacion,
          _token: CSRF_TOKEN,
        },
        dataType: "json",
        success: function (response) {
          // Muestra el mensaje de SweetAlert que indica que el diagnóstico ha sido eliminado
          Swal.fire("¡Eliminado!", "El diagnóstico ha sido eliminado.", "success");
         
          $.ajax({
            type: "POST",
            url: "/updatediagnosticos",
            data: {
              idprestacion: idprestacion,
              _token: CSRF_TOKEN,
            },
            dataType: "json",
            success: function (updatedData) {
              console.log(JSON.stringify(updatedData));
              renderTableData(updatedData);

              // Llamar a "/updateprocedimientos" dentro de la función de éxito de "/updatediagnosticos"
              $.ajax({
                type: "POST",
                url: "/updateprocedimientos",
                data: {
                  idprestacion: id_prestacion,
                  _token: CSRF_TOKEN,
                },
                dataType: "json",
                success: function (updatedData) {
                  console.log(JSON.stringify(updatedData));
                  renderProcedimientosTableData(updatedData);
                  eliminarDiagnosticosarr(cod);


                  $.ajax({
                  type: "POST",
                url: "/updaterecetas",
                data: {
                  idprestacion: id_prestacion,
                  _token: CSRF_TOKEN,
                },
                dataType: "json",
                success: function (updatedData) {
                  console.log("la nueva lista de recetas es");
                  console.log(updatedData);
                  renderReceta(updatedData);
                
                },
                error: function (jqXHR, textStatus, errorThrown) {
                  // Manejar errores aquí, si los hay
                },
              });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                  // Manejar errores aquí, si los hay
                },
              });

            
            },
            error: function (jqXHR, textStatus, errorThrown) {
              // Manejar errores aquí, si los hay
            },
          });
        },
        error: function (jqXHR, textStatus, errorThrown) {
          // Código que se ejecuta cuando hay un error en la solicitud
        },
      });
    }
  });

 
}


function eliminarProcedimiento(cod,idprestacion) {
    // Muestra un mensaje de confirmación antes de eliminar el diagnóstico
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta seguro de eliminar el procedimiento?.",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
          
            var row = document.querySelector('.row2-' + cod);
            // Elimina la fila de la tabla
            if (row) {
               // row.parentNode.removeChild(row);
            }

            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            $.ajax({
                type: 'POST',
                url: "/eliminarprocedimientos",
                data: {
                    'codigo': cod, //codigo prestacion_procedimiento
                    'idprestacion': idprestacion,
                    '_token': CSRF_TOKEN
                },
                dataType: 'json',
                success: function(response) {
                  console.log("el resultado es" + response);
                    // Muestra el mensaje de SweetAlert que indica que el diagnóstico ha sido eliminado
                    Swal.fire("¡Eliminado!", "El diagnóstico ha sido eliminado.", "success");
                    $.ajax({
                type: "POST",
                url: "/updateprocedimientos",
                data: {
                  idprestacion: id_prestacion,
                  _token: CSRF_TOKEN,
                },
                dataType: "json",
                success: function (updatedData) {
                  console.log(JSON.stringify(updatedData));
                  renderProcedimientosTableData(updatedData);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                  // Manejar errores aquí, si los hay
                },
              });
                 },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Código que se ejecuta cuando hay un error en la solicitud

                }
            });
        }
    });
}


function getMedicamentos(idRecetaVale) {
  return new Promise((resolve, reject) => {
    const CSRF_TOKEN = $('meta[name="_token"]').attr("content");
    $.ajax({
      url: "/obtenermedicamentos", // Reemplaza esta ruta con la ruta correcta en tu aplicación
      type: "POST",
      data: {
        idRecetaVale: idRecetaVale,
        _token: CSRF_TOKEN,
      },
      dataType: "text", // Agrega esto para que la respuesta sea tratada como texto sin procesar
      success: function (response) {
        const parsedResponse = JSON.parse(response); // Convierte la respuesta JSON en un objeto JavaScript
        resolve(parsedResponse);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        reject(errorThrown);
      },
    });
  });
}

function getDiagnosticoNombre(diagnosticoId) {
  console.log("el valor del diganostico id receta es:");
  console.log(diagnosticoId)
  return new Promise((resolve, reject) => {
    const CSRF_TOKEN = $('meta[name="_token"]').attr("content");
    $.ajax({
      url: "/obtenerdiagnosticonombreCodigo",
      type: "POST",
      data: {
        diagnosticoId: diagnosticoId,
        _token: CSRF_TOKEN,
      },
      dataType: "text",
      success: function (response) {
        resolve(response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        reject(errorThrown);
      },
    });
  });
}



async function renderReceta(recetaData) {
  let recetaHTML = '';
  const panelBodyReceta = $('.panel-body-receta');
  panelBodyReceta.empty();

  const recetasPromises = recetaData.map(async (rowReceta) => {
    const medicamentos = await getMedicamentos(rowReceta.id);

    const recetaSection = `
      <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
        ${rowReceta.estado == "2" ? `
        <a data-toggle="modal" data-target="#squarespaceModal" onclick="enviarReceta(${rowReceta.id})" class="btn btn-danger">
          <i class="fa fa-xing"></i> Invalidar Receta
        </a>
        ` : `
        <button class="btn btn-danger" disabled="disabled"><i class="fa fa-xing"></i> Invalidar Receta</button>
        `}
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
                  <p>${rowReceta.nro_receta}</p>
                </div>
                <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                <div class="col-md-2">
                  <p>${rowReceta.fecha_registro}</p>
                </div>
                <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                <div class="col-md-4" style="padding:0px">
                  <p>${rowReceta.farmacia}</p>
                </div>
              </div>
            </div>
            
            <table class="display" cellspacing="0" width="100%">
              <table class="display" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th width="15%">Codigo</th>
                  <th width="30%">Producto farmacéutico o dispositivo médico</th>
                  <th width="15%">Unidad Medida</th>
                  <th width="10%">Tiempo(Dias)</th>
                  <th width="15%">Posologia</th>
                  <th width="15%">Cantidad Prescrita</th>
                  <th width="15%">Diagnosticos</th> <!-- Nuevo encabezado -->
                </tr>
              </thead>
              <tfoot>
              </tfoot>
              <tbody>
                ${medicamentos.map(med => `
                  <tr>
                    <td>${med.codigo}</td>
                    <td>${med.nombre}</td>
                    <td>${med.abreviatura}</td>
                    <td>${med.tiempo_periodo}</td>
                    <td>${med.posologia}</td>
                    <td>${med.cantidad_prescrita}</td>
                    <td class="diagnostico" data-diagnostico-id="${med.id_diagnosticos}"></td> <!-- Nueva celda para el diagnóstico -->
                  </tr>
                `).join('')}
              </tbody>
            </table>
            
          </div>
        </div>
      </fieldset>
    `;

    return recetaSection;
  });

  const recetaSections = await Promise.all(recetasPromises);
  recetaHTML = recetaSections.join('');
  panelBodyReceta.html(recetaHTML);

  // Realiza las peticiones AJAX para obtener los nombres de diagnóstico y agregarlos a las celdas correspondientes
  $('.diagnostico').each(async function() {
  const diagnosticoId = $(this).data('diagnostico-id');
  let diagnosticoNombre = await getDiagnosticoNombre(diagnosticoId);
  diagnosticoNombre = diagnosticoNombre.replace(/"/g, ''); // quitar las comillas
  $(this).text(diagnosticoNombre);
});
}



function renderTableData(data) {
  var tableBody = $('#tblDiagnostico tbody');
  tableBody.empty(); // Vacía el contenido actual del tbody

  data.forEach(function(row) {
    var tipoDiagnostico = '';
    switch(row.id_tipo_diagnostico) {
      case 1:
        tipoDiagnostico = 'PRESUNTIVO';
        break;
      case 2:
        tipoDiagnostico = 'DEFINITIVO';
        break;
      case 3:
        tipoDiagnostico = 'REITERATIVO';
        break;
      default:
    }
  
                                
    var tableRow = `
      <tr class="row-diagnosticosfinal">
        <td>
          <input type="text" name="id_diagnisticos['${row.id_diagnostico}']" value="${row.codigo}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
        </td>
        <td>
          <input type="hidden" value="${row.id_diagnostico}" name="receta_diagnositico_id" style="width : 1px; heigth : 1px">
          <input type="text" name="id_diagnisticos['${row.id_diagnostico}']" value="${row.nombre}" class=" input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
        </td>
        <td>
          <input name="id_tipo_diagnosticos['${row.id_diagnostico}']" value="${tipoDiagnostico}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
        </td>
        <td>
          <button type="button" onclick="eliminar('${row.id_diagnostico}', '${id_prestacion}');">Eliminar</button>


        </td>
      </tr>
    `;

    tableBody.append(tableRow);
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

  // Abrir modal de maximizado de las imagenes
  function openModalMaximizadoImagen(imagen) {
    document.getElementById("modalImage").src = imagen.src;
    $("#modalMaximizarImagen").modal("show");
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
            // if ($(this).val() != "") {
              has_interconsulta_agregada = true;
              return false;
            // }
          });
        }
      }
      // Fin validar si hay interconsultas agregadas

      if (has_interconsulta_agregada) {
        bootbox.confirm({
          message: "Al cambiar la <b>Condición del Paciente</b> se anulará los registros de solicitud de interconsulta ya adicionados.<br><br>¿Desea continuar?",
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
  /*.modal-dialog {
    width: 1200px !important;
  }*/

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

  .swal2-icon.swal2-warning,
  .swal2-icon.swal2-info {
    font-size: 15px;
  }
  
  .swal2-popup,
  .swal2-header {
    padding: 1.25em !important;
  }

  /* Estilos para Checklist de RUA Laboratorio */
  .modal-col1 .modal-dialog {
    max-width: 450px !important;
  }

  .modal-col2 .modal-dialog {
    max-width: 950px !important;
  }

  .modal-col3 .modal-dialog {
    max-width: 1200px !important;
  }

  .modal-col4 .modal-dialog {
    max-width: 1400px !important;
  }

  #contenedorDX.col1 { grid-template-columns: 350px; }
  #contenedorDX.col2 { grid-template-columns: repeat(2, 350px); }
  #contenedorDX.col3 { grid-template-columns: repeat(3, 320px); }
  #contenedorDX.col4 { grid-template-columns: repeat(4, 300px); }

  .modal-col5 .modal-dialog {
    max-width: 1400px !important;
  }

  #contenedorDX {
    display: grid;
    justify-content: center;
    gap: 10px;
    max-height: calc((50px + 10px) * 10);
    overflow-y: auto;
    padding-right: 10px;
  }

  #contenedorDX label {
    display: flex;
    align-items: center;
  }

  #contenedorDX.col5 {
    grid-template-columns: repeat(4, 250px); 
  }
  /* Fin estilos para Checklist de RUA Laboratorio */

</style>
<script>
  function cargarDiagnosticosTodos() {
    $("#contenedorDX").html("Cargando...");
    var nivel = $('#id_nivel').val();
    var sexo = $('#nomsexo').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();
    var genero = (sexo == 'M') ? 1 : 2;

    $.ajax({
      url: '/obtener_laboratorio_convenio/all/' + nivel + '/' + genero + '/' + id_tipo_atencion,  
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        console.log("ITEM EJEMPLO:", data[0]); 
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
            // Bidireccionales
            "82803": "36600",
            "36600": "82803",
            "82575": "82565",
            "82565": "82575",
            "82043.01": "84999.09",
            "84999.09": "82043.01",
            "89190": "87999",
            "87999": "89190",
            "87115": "84999.13",
            "84999.13": "87115",
            // Unidireccionales
            "87070": "82020",
            "87163": "87184",
            "87040.01": "87184",
            "87040.03": "87184",
            "87045": "87184"
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

    checked.each(function () {
      const $this = $(this);
      const id_examen_lab = $this.data('id');
      const codigo = $this.data('codigo');
      const categoria = $this.data('categoria');
      const label = $this.data('label');
      const id_nivel = $("#id_nivel").val();

      if ($("#tblExamenLab tbody tr[data-id='" + id_examen_lab + "']").length > 0) {
        return true; 
      }

      const ind = obtenerIndiceTblExamenLab();

      let rowHtml = `
        <tr data-id="${id_examen_lab}">
          <td>
            <input type="text" name="nombre_examen_lab[]" required id="nombre_examen_lab${ind}" class="input-sm" style="width:100%; text-align:left" value="${label}" readonly/>
            <input type="hidden" class="id_laboratorio" id="id_examen_lab${ind}" name="id_examen_lab[]" data-id="${ind}" value="${id_examen_lab}">
            <input type="hidden" class="cod_lab" id="cod_lab${ind}" name="cod_lab[]" data-id="${ind}" value="${codigo}">
            <input type="hidden" class="id_categoria" id="id_categoria_examen_lab${ind}" name="id_categoria_examen_lab[]" data-id="${ind}" value="${categoria}">
          </td>
      `;
      
      if (id_nivel && parseInt(id_nivel) < 3) {
        rowHtml += `
          <td>
            <input type="text" placeholder="Observacion" class="form-control examen_lab_observacion" id="examen_lab_observacion${ind}" name="examen_lab_observacion[]">
          </td>`;
      }

      rowHtml += `
          <td>
            <input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad" id="examen_lab_cantidad${ind}" name="examen_lab_cantidad[]" value="1">
          </td>
          <td>
            <select class="form-control select-diagnostico" id="miSelectLab${ind}" name="miSelectLab[]"></select>
          </td>
          <td>
          </td>
          <td>
          </td>
          <td>
            <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>
          </td>
        </tr>
      `;
      $("#tblExamenLab tbody").append(rowHtml);

      /*if (typeof asignarValoresDesdeModal === 'function') {
        asignarValoresDesdeModal({id_examen_lab: id_examen_lab, codigo_procedimiento: codigo, id_categoria: categoria, label: label}, ind);
      } else {
        $(`#id_examen_lab${ind}`).val(id_examen_lab);
        $(`#cod_lab${ind}`).val(codigo || '');
        $(`#id_categoria_examen_lab${ind}`).val(categoria || '');
        $(`#nombre_examen_lab${ind}`).val(label || '');
      }*/

      const select = document.getElementById('miSelectLab' + ind);
      if (Array.isArray(listIdDiagnosticoObjetos) && select) {
        listIdDiagnosticoObjetos.forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.id_diagnostico;
          opt.textContent = item.nombre;
          select.appendChild(opt);
        });
      }
    });

    $("#modalDX").modal('hide');
    $("input[name='chk_rualab[]']").prop('checked', false);
  });

  document.querySelectorAll('input[name="hora_toma_muestra"]').forEach(radio => {
    radio.addEventListener('change', function() {

      let stringIdHoraTomaMuestraSeleccionada = String(this.value);
      let descripcionHoraTomaMuestra = "";
      switch (stringIdHoraTomaMuestraSeleccionada) {
        case "0": descripcionHoraTomaMuestra = "OTRO"; break;
        case "1": descripcionHoraTomaMuestra = "05:00 AM"; break;
        case "2": descripcionHoraTomaMuestra = "09:00 AM"; break;
        case "3": descripcionHoraTomaMuestra = "16:00 PM"; break;
        case "4": descripcionHoraTomaMuestra = "22:00 PM"; break;
      }
      document.getElementById("id_hora_toma_muestra").value = String(stringIdHoraTomaMuestraSeleccionada);

      document.getElementById("lab_orden_toma_muestra").value = descripcionHoraTomaMuestra;
    });
  });
</script>
@endsection
@section('content')
@include('frontend.receta_vales.modal_procedimientos')
<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
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


            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->


            <form class="form-horizontal" method="post" id="frmBloque"
              action="{{ route('registrar_examenes_complementarios_hospitalizacion')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{$id_farmacia}}" name="id_farmacia" id="id_farmacia">
              <input type="hidden" value="{{$id_prestacion}}" name="id_prestacion" id="id_prestacion">
              <input type="hidden" value="{{$nivel}}" name="id_nivel" id="id_nivel">
              <input type="hidden" value="{{$turno}}" name="turno" id="turno">
              <input type="hidden" value="{{$prestacion->id_cita}}" name="id_cita" id="id_cita">
              <input type="hidden" value="{{$prestacion->id_asegurado}}" name="id_asegurado" id="id_asegurado">
              <input type="hidden" value="{{$prestacion->id_establecimiento}}" name="id_establecimiento"
                id="id_establecimiento">
              <input type="hidden" value="{{$prestacion->id_sub_consultorio}}" name="id_sub_consultorio"
                id="id_sub_consultorio">
              <input type="hidden" value="{{$prestacion->nro_doc_ident}}" name="dni_beneficiario"
                id="dni_beneficiario">
                <input id="procedimientosSeleccionados1" type="hidden" name="diagnosticosSeleccionados[]">
              <input type="hidden" value="{{$prestacion->id_medico}}" name="id_medico" id="id_medico">
              <input type="hidden" value="{{$prestacion->id_tipo_atencion}}" name="id_tipo_atencion" id="id_tipo_atencion">
              <input type="hidden" value="{{$prestacion->id_consultorio}}" name="id_servicio" id="id_servicio">
              <input type="hidden" value="{{$prestacion->nro_historia}}" name="nro_historia" id="nro_historia">
              <input type="hidden" value="{{$prestacion->sexo}}" name="nomsexo" id="nomsexo">
              <input type="hidden" value="{{ date('d-m-Y', strtotime($prestacion->fecha_hora_atencion)) }}" name="fecha_atencion" id="fecha_atencion">
              <input type="hidden" value="" name="id_productosselect">
              <input type="hidden" value="" name="nro_stocks_almacensecuen" >
              <input type="hidden" value="" name="nro_stocks_establecimientosecuen" >
              <input type="hidden" value="" name="nro_stockssecuen" >
              <input type="hidden" value="" name="tiempo_periodosecuen" >
              <input type="hidden" value="" name="posologiasecuen" >
              <input type="hidden" value="" name="nro_med_solictadossecuen" >
              <input type="hidden" value="" name="farmaciavalor" >
              <input type="hidden" value="" name="procedimientosSeleccionados1" id="procedimientosSeleccionados1" >
              <input type="hidden" value="" name="fechexpedicion" id="fechexpedicion" >
              <input type="hidden" value="{{ $dato_hospitalizacion->id_consultorio }}" name="id_consultorio_h" id="id_consultorio_h" >
              <input type="hidden" name="fecha_hora_atencion" value="{{ $prestacion->fecha_hora_atencion }}" />
              <input type="hidden" name="id_servicio_encargado" value="{{ $cambio_cama_data->id_servicio_encargado }}" />
              <input type="hidden" name="id_cama_atencion" value="{{ $cambio_cama_data->id_cama_atencion }}" />
              <input type="hidden" name="id_hora_toma_muestra" id="id_hora_toma_muestra" />
              <input type="hidden" name="id_principal" id="id_principal" value="{{$id_principal}}">
              <input type="hidden" id="id_rua_imagenes" name="id_rua_imagenes" value="">
              <?php
              $flag_firmar = 0;
              if($flag_pendinte_firma == 1) {
                $flag_firmar = 1;
              }
               ?>

               <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>
                  <legend>
                    <?php if ($prestacion->id_estado_reg==0): ?>
                      <b><span style="color:red;"> DATOS DE LA ATENCI&Oacute;N ANULADA</span></b>
                    <?php else: ?>
                      <b>DATOS DE LA ATENCI&Oacute;N</b>
                    <?php endif; ?>
                  </legend>
                  <div>
                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="fecha_atencion">FECHA DE ATENCION</label>
                        <p>{{ date('d-m-Y', strtotime($prestacion->fecha_hora_atencion)) }}</p>
                      </div>
                      <div class="col-md-2">
                        <label>N° HISTORIA CLINICA</label>
                        <p>{{$prestacion->nro_historia}}</p>
                      </div>
                      <div class="col-md-3">
                        <label for="nombre_ipres">AREA</label>
                        <p>{{$prestacion->servicio}}</p>
                      </div>
                      <div class="col-md-2">
                        <label>CODIGO UPSS</label>
                        <p>{{$prestacion->codigo_upss}}</p>
                      </div>
                      
                    </div>
                    
                    @if($sw==1)
                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="tipo">PABELLON</label>
                        <p>{{$datoadicional->pabellon}}</p>
                      </div>
                      <div class="col-md-2">
                        <label for="dni_beneficiario">PISO</label>
                        <p>{{$datoadicional->piso}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>HABITACION</label>
                        <p>{{$datoadicional->cuarto}}</p>
                      </div>
                      <div class="col-md-2">
                        <label>CAMA/CAMILLA</label>
                        <p>{{$datoadicional->cama}}</p>
                      </div>
                    </div>
                    @endif
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
                        <label for="tipo">DOCUMENTO DE IDENTIDAD</label>
                        <p>{{$prestacion->tipo_doc_ident}}: {{$prestacion->nro_doc_ident}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO PATERNO</label>
                        <p>{{$prestacion->asegurado_paterno}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO MATERNO</label>
                        <p>{{$prestacion->asegurado_materno}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>NOMBRES</label>
                        <p>{{$prestacion->asegurado_nombre}}</p>
                      </div>
                    </div>
                    @if($prestacion->parentesco=='TITULAR')
                    <div class="form-group">
                      <div class="col-md-2">
                        <label>FECHA DE NACIMIENTO</label>
                        <p>{{$prestacion->fecha_nac}}</p>
                      </div>
                      <div class="col-md-2">
                        <label>PARENTESCO &nbsp;</label>
                        <p>{{$prestacion->parentesco}}</p>
                      </div>
                      <div class="col-md-2">
                        <label for="cip">CIP</label>
                        <p>{{$prestacion->cip}}</p>
                      </div>
                      <div class="col-md-2">
                        <label for="grado">GRADO</label>
                        <p>{{$prestacion->grado}}</p>
                      </div>
                      <div class="col-md-2">
                        <label for="nomsexo">SEXO</label>
                        <p>{{$prestacion->sexo}}</p>
                      </div>
                      <div class="col-md-2">
                        <label for="edadpaciente">EDAD</label>
                        <p>{{$prestacion->edadpaciente}}</p>
                      </div>
                    </div>
                    @else
                    <div class="form-group">
                      <div class="col-md-3">
                        <label>FECHA DE NACIMIENTO</label>
                        <p>{{$prestacion->fecha_nac}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>PARENTESCO &nbsp;</label>
                        <p>{{$prestacion->parentesco}}</p>
                      </div>
                      <div class="col-md-3">
                        <label for="nomsexo">SEXO</label>
                        <p>{{$prestacion->sexo}}</p>
                      </div>
                      <div class="col-md-3">
                        <label for="edadpaciente">EDAD</label>
                        <p>{{$prestacion->edadpaciente}}</p>
                      </div>
                        @if($prestacion->situacion_paciente!='CIVIL' && $flag_no_encontrado==0)
                        <div class="col-md-3"><br/>
                          <label>DOCUMENTO TITULAR</label>
                          <p>{{$dato_titular[0]->tipo_doc_ident}} : {{$dato_titular[0]->nro_doc_ident}}</p>
                        </div>
                        <div class="col-md-3"><br/>
                          <label>DATO DEL TITULAR &nbsp;</label>
                          <p>{{$dato_titular[0]->nombre}}, {{$dato_titular[0]->paterno}} {{$dato_titular[0]->materno}}</p>
                        </div>
                        <div class="col-md-3"><br/>
                          <label for="cip">CIP</label>
                          <p>{{$dato_titular[0]->cip}}</p>
                        </div>
                        <div class="col-md-3"><br/>
                          <label for="grado">GRADO</label>
                          <p>{{$dato_titular[0]->grado}}</p>
                        </div>
                      </div>
                      @endif
                    @endif
                  </div>
                </div>
              </fieldset>
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
                        <label for="tipo">DOCUMENTO DE IDENTIDAD</label>
                        <p>DNI: {{$prestacion->dni}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO PATERNO</label>
                        <p>{{$prestacion->medico_paterno}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO MATERNO</label>
                        <p>{{$prestacion->medico_materno}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>NOMBRES</label>
                        <p>{{$prestacion->medico_nombre}}</p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-3">
                        <label>ESPECIALIDAD</label>
                        <p>{{$prestacion->especializacion}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>PROFESION</label>
                        <p>{{$prestacion->profesion}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>N° COLEGIATURA</label>
                        <p>{{$prestacion->colegiatura}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>N° RNE</label>
                        <p>{{$prestacion->rne}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>ENFERMEDAD ACTUAL</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-lg-3 col-md-12">
                        <label>TIEMPO DE ENFERMEDAD</label>
                        <input type="text" class="form-control" id="tiempo_enfermedad" name="tiempo_enfermedad" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->tiempo_enfermedad}}">
                      </div>
                      <div class="col-lg-3 col-md-12">
                        <label>FORMA DE INICIO</label>
                        <input type="text" class="form-control" id="forma_inicio" name="forma_inicio" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->forma_inicio}}">
                      </div>
                      <div class="col-lg-3 col-md-12">
                        <label>CURSO</label>
                        <input type="text" class="form-control" id="curso" name="curso" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->curso}}">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-12 col-md-12">
                        <label>SINTOMAS Y SIGNOS PRINCIPALES</label>
                        <textarea class="form-control" name="sintomas_signos_principal" id="sintomas_signos_principal" rows="3" readonly>{{$obj_hospitalizacion_atencion->sintomas_signos_principal}}</textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-12 col-md-12">
                        <label>RELATO CRONOLÓGICO</label>
                        <textarea tabindex="" class="form-control" rows="10" id="relato" name="relato" readonly>{{$obj_hospitalizacion_atencion->relato}}</textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-12 col-md-12">
                        <label>TRATAMIENTO RECIBIDO ANTES DE LA HOSPITALIZACIÓN</label>
                        <textarea class="form-control" name="tratamiento_recibido_antes" id="tratamiento_recibido_antes" rows="3" readonly>{{$obj_hospitalizacion_atencion->tratamiento_recibido_antes}}</textarea>
                      </div>
                    </div>
                    <div>
                      <label for="">FUNCIONES BIOLÓGICAS</label>
                      <div class="form-group">
                        <div class="col-lg-3 col-md-12">
                          <label>APETITO</label>
                          <input type="text" class="form-control" id="fn_bio_1" name="fn_bio_1" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_1}}">
                        </div>
                        <div class="col-lg-3 col-md-12">
                          <label>SED</label>
                          <input type="text" class="form-control" id="fn_bio_2" name="fn_bio_2" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_2}}">
                        </div>
                        <div class="col-lg-3 col-md-12" style="display:none">
                          <label>PESO</label>
                          <input type="text" class="form-control" id="fn_bio_3" name="fn_bio_3" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_3}}">
                        </div>
                        <div class="col-lg-3 col-md-12">
                          <label>MICCIÓN</label>
                          <input type="text" class="form-control" id="fn_bio_4" name="fn_bio_4" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_4}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-lg-3 col-md-12">
                          <label>DEPOSICIONES</label>
                          <input type="text" class="form-control" id="fn_bio_5" name="fn_bio_5" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_5}}">
                        </div>
                        <div class="col-lg-3 col-md-12">
                          <label>SUEÑO</label>
                          <input type="text" class="form-control" id="fn_bio_6" name="fn_bio_6" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_6}}">
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
                      <b>ANTECEDENTES</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="panel panel-primary panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title">
                          <b>PERSONALES</b>
                        </div>
                      </div>
                      <div class="panel-body">
                          <label for=""><span>Generales:</span></label>
                          <div class="form-group">
                            <div class="col-lg-2 col-md-12">
                              <label>Grupo sanguíneo y factor RH:</label>
                              <input type="text" class="form-control" id="antc_1" name="antc_1" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_1}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Alergías-RAM:</label>
                              <input type="text" class="form-control" id="antc_2" name="antc_2" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_2}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Drogas:</label>
                              <input type="text" class="form-control" id="antc_3" name="antc_3" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_3}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Alcohol:</label>
                              <input type="text" class="form-control" id="antc_4" name="antc_4" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_4}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Tabaco:</label>
                              <input type="text" class="form-control" id="antc_5" name="antc_5" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_5}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Café:</label>
                              <input type="text" class="form-control" id="antc_6" name="antc_6" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_6}}">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-lg-2 col-md-12">
                              <label>Transfusiones:</label>
                              <input type="text" class="form-control" id="antc_7" name="antc_7" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_7}}">
                            </div>
                            <div class="col-lg-10 col-md-12">
                              <label>Otros de importancia:</label>
                              <textarea class="form-control" name="antc_8" id="antc_8" rows="2" readonly>{{$obj_hospitalizacion_atencion->antc_8}}</textarea>
                            </div>
                          </div>
                          <label for=""><span>Fisiológicos:</span></label>
                          <div class="form-group">
                            <div class="col-lg-2 col-md-12">
                              <label>Nacimiento:</label>
                              <input type="text" class="form-control" id="antc_9" name="antc_9" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_9}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Desarrollo Psicomotor:</label>
                              <input type="text" class="form-control" id="antc_10" name="antc_10" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_10}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Vacunas:</label>
                              <input type="text" class="form-control" id="antc_11" name="antc_11" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_11}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Inicio de relaciones sexuales:</label>
                              <input type="text" class="form-control" id="antc_12" name="antc_12" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_12}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Comportamiento Sexual:</label>
                              <input type="text" class="form-control" id="antc_13" name="antc_13" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_13}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Menarquía:</label>
                              <input type="text" class="form-control" id="antc_14" name="antc_14" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_14}}">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-lg-2 col-md-12">
                              <label>Régimen Catamenial:</label>
                              <input type="text" class="form-control" id="antc_15" name="antc_15" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_15}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Fecha de última regla:</label>
                              <input type="text" class="form-control" id="antc_16" name="antc_16" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_16}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Método Anticonceptivo:</label>
                              <input type="text" class="form-control" id="antc_17" name="antc_17" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_17}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Uso de hormonas:</label>
                              <input type="text" class="form-control" id="antc_18" name="antc_18" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_18}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Gesta:</label>
                              <input type="text" class="form-control" id="antc_19" name="antc_19" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_19}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>PARA:</label>
                              <input type="text" class="form-control" id="antc_20" name="antc_20" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_20}}">
                            </div>
                          </div>
                          <label for=""><span>Patológicos:</span></label>
                          <div class="form-group">
                            <div class="col-lg-12 col-md-12">
                              <label>Enfermedades de la infancia:</label>
                              <textarea class="form-control" name="antc_21" id="antc_21" rows="2" readonly>{{$obj_hospitalizacion_atencion->antc_21}}</textarea>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-lg-2 col-md-12">
                              <label>Diabetes:</label>
                              <input type="text" class="form-control" id="antc_22" name="antc_22" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_22}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Hipertensión:</label>
                              <input type="text" class="form-control" id="antc_23" name="antc_23" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_23}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>TBC:</label>
                              <input type="text" class="form-control" id="antc_24" name="antc_24" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_24}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Dislipidemias:</label>
                              <input type="text" class="form-control" id="antc_25" name="antc_25" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_25}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Hepatitis:</label>
                              <input type="text" class="form-control" id="antc_26" name="antc_26" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_26}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Convulsiones:</label>
                              <input type="text" class="form-control" id="antc_27" name="antc_27" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_27}}">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-lg-2 col-md-12">
                              <label>Asma:</label>
                              <input type="text" class="form-control" id="antc_28" name="antc_28" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_28}}">
                            </div>
                            <div class="col-lg-2 col-md-12">
                              <label>Otros:</label>
                              <input type="text" class="form-control" id="antc_29" name="antc_29" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_29}}">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-lg-12 col-md-12">
                              <label>Intervenciones Quirúrgicas:</label>
                              <input type="text" class="form-control" id="antc_30" name="antc_30" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->antc_30}}">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-lg-12 col-md-12">
                              <label>Traumatismo y Accidentes:</label>
                              <input type="text" class="form-control" id="antc_31" name="antc_31" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->antc_31}}">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-lg-12 col-md-12">
                              <label>Medicación de uso habitual:</label>
                              <input type="text" class="form-control" id="antc_32" name="antc_32" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->antc_32}}">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-lg-12 col-md-12">
                              <label>Hospitalizaciones previas:</label>
                              <input type="text" class="form-control" id="antc_33" name="antc_33" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->antc_33}}">
                            </div>
                          </div>


                      </div>
                    </div>

                    <div class="panel panel-primary panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title">
                          <b>FAMILIARES</b>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-lg-12 col-md-12">
                            <textarea class="form-control" name="antecedente_familiar" id="antecedente_familiar" rows="3" readonly>{{$prestacion->antecedente_familiar}}</textarea>
                          </div>
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
                      <b>EXAMEN CLÍNICO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="panel-body" id="VerSignosVitales">
                      <div class="form-group">
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                          <label>SIGNOS VITALES</label>
                        </div>
                      </div>
                      <div class="panel panel-info panel-bordered panel-sm" id="fsSignosVitales">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-2">
                              <label>TEMPERATURA (°C) &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                              <input tabindex="" placeholder="00.00" value="{{$prestacion->temperatura}}" type="text" name="temperatura" id="temperatura" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="5" readonly/>
                            </div>
                            <div class="col-md-2">
                              <label>TALLA (cm) &nbsp;</label>
                              <input tabindex="" value="{{$prestacion->talla_cm}}" type="text" name="talla" id="talla" class="form-control" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                            </div>
                            <div class="col-md-2">
                              <label>PESO (Kg) &nbsp;</label>
                              <input tabindex="" placeholder="00.00" value="{{$prestacion->peso_kg}}" type="text" name="peso" id="peso" class="form-control" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="6" readonly>
                            </div>
                            <div class="col-md-2">
                              <label>IMC &nbsp;</label>
                              <input value="{{$imc}}" type="text" name="imc" id="imc" class="form-control" readonly="" tabindex="">
                            </div>
                            <div class="col-md-2">
                              <label>PESO IDEAL &nbsp;</label>
                              <input value="{{$peso_ideal}}" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control" readonly="" max="0" tabindex="">
                            </div>
                            <div class="col-md-2">
                              <label>Sa02 (%) &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                              <input value="{{$prestacion->sa02_porcentaje}}" type="text" name="saturacion" id="saturacion" class="form-control" tabindex="" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                              <label>FREC. CARDIACA FETAL(/min)</label>
                              <input tabindex="" value="{{$prestacion->frecuencia_cardiaca_fetal}}" type="text" name="frecuencia_cardiaca_fetal" id="frecuencia_cardiaca_fetal" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                            </div>
                            <div class="col-md-2">
                              <label>PRESIÓN ARTERIAL &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                              <div class="row">
                                <div class="col-xs-5">                          
                                  <input value="{{$prestacion->presion_arterial_x}}" tabindex="" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                                </div>  
                                  <div class="col-xs-2">
                                    <label>/</label> 
                                  </div>
                                <div class="col-xs-5">
                                  <input value="{{$prestacion->presion_arterial_y}}" type="text" tabindex="" name="presion_arterial_y" id="presion_arterial_y" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                                </div>  
                              </div>
                            </div>
                            <div class="col-md-2">
                              <label>FREC. RESPIRATORIA (/min) <span style="font-weight: bold; color: red;">(*)</span></label>
                              <input value="{{$prestacion->frecuencia_respiratoria}}" type="text" name="frecuencia_respiratoria" tabindex="" id="frecuencia_respiratoria" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                            </div>
                            <div class="col-md-2">
                              <label>FREC. CARDIACA (/min) &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                              <input value="{{$prestacion->frecuencia_cardiaca}}" type="text" name="frec_cardiaca" id="frec_cardiaca" tabindex="" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                            </div>
                            <div class="col-md-2">
                              <label>Fi02 (%) &nbsp;</label>
                              <input value="{{$prestacion->fi02}}" type="text" name="oxigenacion" id="oxigenacion" tabindex="" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-lg-12 col-md-12">
                          <label>EXAMEN CLÍNICO GENERAL</label>
                          
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>Ectoscopía:</label>
                                  <textarea class="form-control" name="exam_gen_1" id="exam_gen_1" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_gen_1}}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-3 col-md-12">
                                  <label>Tipo morfológico:</label>
                                  <input type="text" class="form-control" id="exam_gen_2" name="exam_gen_2" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_2}}">
                                </div>
                                <div class="col-lg-3 col-md-12">
                                  <label>Fascies:</label>
                                  <input type="text" class="form-control" id="exam_gen_3" name="exam_gen_3" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_3}}">
                                </div>
                                <div class="col-lg-3 col-md-12" style="display:none">
                                  <label>Talla:</label>
                                  <input type="text" class="form-control" id="exam_gen_4" name="exam_gen_4" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_4}}">
                                </div>
                                <div class="col-lg-3 col-md-12" style="display:none">
                                  <label>Peso:</label>
                                  <input type="text" class="form-control" id="exam_gen_5" name="exam_gen_5" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_5}}">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>Piel y faneras:</label>
                                  <input type="text" class="form-control" id="exam_gen_6" name="exam_gen_6" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_6}}">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>Tejido celular subcutáneo:</label>
                                  <input type="text" class="form-control" id="exam_gen_7" name="exam_gen_7" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_7}}">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>Sistema Linfático:</label>
                                  <textarea class="form-control" name="exam_gen_8" id="exam_gen_8" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_gen_8}}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>Sistema Locomotor:</label>
                                  @if (!empty($obj_hospitalizacion_atencion->ruta_img_exam_gen_9))
                                  <div id="container_preview_file_exam_gen_9" style="position: relative; width: 150px; margin-top: 10px;">
                                    <img id="preview_img_file_exam_gen_9" src="{{ asset('/images/atenciones/'.$obj_hospitalizacion_atencion->ruta_img_exam_gen_9) }}" style="width: 100%; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 5px; cursor: pointer;" onclick="openModalMaximizadoImagen(this)">
                                  </div>
                                  @endif
                                  <textarea class="form-control" name="exam_gen_9" id="exam_gen_9" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_gen_9}}</textarea>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-lg-12 col-md-12">
                          <label>EXAMEN CLÍNICO REGIONAL</label>
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">

                              <label for="">CABEZA Y CUELLO</label>
                              @if (!empty($obj_hospitalizacion_atencion->ruta_img_cabeza_cuello))
                              <div id="container_preview_file_cabeza_cuello" style="position: relative; width: 150px; margin-top: 10px;">
                                <img id="preview_img_file_cabeza_cuello" src="{{ asset('/images/atenciones/'.$obj_hospitalizacion_atencion->ruta_img_cabeza_cuello) }}" style="width: 100%; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 5px; cursor: pointer;" onclick="openModalMaximizadoImagen(this)">
                              </div>
                              @endif
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-body">
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Cráneo:</label>
                                      <input type="text" class="form-control" id="exam_reg_1" name="exam_reg_1" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_1}}">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Cabello:</label>
                                      <input type="text" class="form-control" id="exam_reg_2" name="exam_reg_2" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_2}}">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Ojos:</label>
                                      <input type="text" class="form-control" id="exam_reg_3" name="exam_reg_3" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_3}}">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Nariz:</label>
                                      <input type="text" class="form-control" id="exam_reg_4" name="exam_reg_4" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_4}}">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Oídos:</label>
                                      <input type="text" class="form-control" id="exam_reg_5" name="exam_reg_5" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_5}}">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Boca:</label>
                                      <input type="text" class="form-control" id="exam_reg_6" name="exam_reg_6" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_6}}">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Cuello:</label>
                                      <input type="text" class="form-control" id="exam_reg_7" name="exam_reg_7" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_7}}">
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <label for="">MAMAS</label>
                              @if (!empty($obj_hospitalizacion_atencion->ruta_img_mama))
                              <div id="container_preview_file_mamas" style="position: relative; width: 150px; margin-top: 10px;">
                                  <img id="preview_img_file_mamas" src="{{ asset('/images/atenciones/'.$obj_hospitalizacion_atencion->ruta_img_mama) }}" style="width: 100%; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 5px; cursor: pointer;" onclick="openModalMaximizadoImagen(this)">
                              </div>
                              @endif
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-body">
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Derecha:</label>
                                      <input type="text" class="form-control" id="exam_reg_8" name="exam_reg_8" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_8}}">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Izquierda:</label>
                                      <input type="text" class="form-control" id="exam_reg_9" name="exam_reg_9" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_9}}">
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>TÓRAX Y PULMONES:</label>
                                  @if (!empty($obj_hospitalizacion_atencion->ruta_img_exam_reg_10))
                                  <div id="container_preview_file_exam_reg_10" style="position: relative; width: 150px; margin-top: 10px;">
                                    <img id="preview_img_file_exam_reg_10" src="{{ asset('/images/atenciones/'.$obj_hospitalizacion_atencion->ruta_img_exam_reg_10) }}" style="width: 100%; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 5px; cursor: pointer;" onclick="openModalMaximizadoImagen(this)">
                                  </div>
                                  @endif
                                  <textarea class="form-control" name="exam_reg_10" id="exam_reg_10" rows="3" readonly>{{$obj_hospitalizacion_atencion->exam_reg_10}}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>CARDIOVASCULAR:</label>
                                  @if (!empty($obj_hospitalizacion_atencion->ruta_img_exam_reg_11))
                                  <div id="container_preview_file_exam_reg_11" style="position: relative; width: 150px; margin-top: 10px;">
                                    <img id="preview_img_file_exam_reg_11" src="{{ asset('/images/atenciones/'.$obj_hospitalizacion_atencion->ruta_img_exam_reg_11) }}" style="width: 100%; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 5px; cursor: pointer;" onclick="openModalMaximizadoImagen(this)">
                                  </div>
                                  @endif
                                  <textarea class="form-control" name="exam_reg_11" id="exam_reg_11" rows="3" readonly>{{$obj_hospitalizacion_atencion->exam_reg_11}}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>ABDOMEN:</label>
                                  @if (!empty($obj_hospitalizacion_atencion->ruta_img_exam_reg_12))
                                  <div id="container_preview_file_exam_reg_12" style="position: relative; width: 150px; margin-top: 10px;">
                                    <img id="preview_img_file_exam_reg_12" src="{{ asset('/images/atenciones/'.$obj_hospitalizacion_atencion->ruta_img_exam_reg_12) }}" style="width: 100%; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 5px; cursor: pointer;" onclick="openModalMaximizadoImagen(this)">
                                  </div>
                                  @endif
                                  <textarea class="form-control" name="exam_reg_12" id="exam_reg_12" rows="3" readonly>{{$obj_hospitalizacion_atencion->exam_reg_12}}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>GENITO URINARIO:</label>
                                  @if (!empty($obj_hospitalizacion_atencion->ruta_img_exam_reg_13))
                                  <div id="container_preview_file_exam_reg_13" style="position: relative; width: 150px; margin-top: 10px;">
                                    <img id="preview_img_file_exam_reg_13" src="{{ asset('/images/atenciones/'.$obj_hospitalizacion_atencion->ruta_img_exam_reg_13) }}" style="width: 100%; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 5px; cursor: pointer;" onclick="openModalMaximizadoImagen(this)">
                                  </div>
                                  @endif
                                  <textarea class="form-control" name="exam_reg_13" id="exam_reg_13" rows="3" readonly>{{$obj_hospitalizacion_atencion->exam_reg_13}}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>TACTO RECTAL:</label>
                                  <textarea class="form-control" name="exam_reg_14" id="exam_reg_14" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_reg_14}}</textarea>
                                </div>
                              </div>

                              <label for="">NEUROLÓGICO</label>
                              @if (!empty($obj_hospitalizacion_atencion->ruta_img_neurologico))
                              <div id="container_preview_file_neurologico" style="position: relative; width: 150px; margin-top: 10px;">
                                <img id="preview_img_file_neurologico" src="{{ asset('/images/atenciones/'.$obj_hospitalizacion_atencion->ruta_img_neurologico) }}" style="width: 100%; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 5px; cursor: pointer;" onclick="openModalMaximizadoImagen(this)">
                              </div>
                              @endif
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-body">
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Estado mental (conciencia-orientación):</label>
                                      <textarea class="form-control" name="exam_reg_15" id="exam_reg_15" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_reg_15}}</textarea>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Funciones superiores (gnosis, praxis, lenguaje y memoria):</label>
                                      <textarea class="form-control" name="exam_reg_16" id="exam_reg_16" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_reg_16}}</textarea>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Función motora (fuerza y tono muscular, movimientos involuntarios, coordinación y reflejos):</label>
                                      <textarea class="form-control" name="exam_reg_17" id="exam_reg_17" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_reg_17}}</textarea>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-lg-12 col-md-12">
                                      <label>Función sensitiva (superficie y profunda):</label>
                                      <textarea class="form-control" name="exam_reg_18" id="exam_reg_18" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_reg_18}}</textarea>
                                    </div>
                                  </div>

                                  <!-- <label for="">Pares craneales:</label> -->
                                  <div class="panel panel-info panel-bordered panel-sm panel-body">
                                    <div class="form-group">
                                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                        <label>Pares craneales:</label>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="110" type="button" id="addParesCraneales" class="btn btn-lg btn-toggle"
                                          data-toggle="button" aria-pressed="false" autocomplete="off" disabled>
                                          <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                    
                                    <div class="panel panel-info panel-bordered panel-sm" style="display: none;" id="fsParesCraneales">
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-lg-12 col-md-12">
                                            <label>I Olfatorio:</label>
                                            <input type="text" class="form-control" id="exam_reg_19" name="exam_reg_19" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_19}}">
                                            <input type="hidden" id="flag_pares_craneales" name="flag_pares_craneales" value="0">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-lg-12 col-md-12">
                                            <label>II Óptico (agudeza visual, campos visuales, colore, fondo de ojo, presión intraocular):</label>
                                            <textarea class="form-control" name="exam_reg_20" id="exam_reg_20" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_reg_20}}</textarea>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-lg-12 col-md-12">
                                            <label>III IV VI Oculomotores (movimientos oculares, pupilas):</label>
                                            <textarea class="form-control" name="exam_reg_21" id="exam_reg_21" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_reg_21}}</textarea>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-lg-12 col-md-12">
                                            <label>V Trigésimo (reflejo corneal, sensibilidad facial, fuerza músculos masticadores):</label>
                                            <textarea class="form-control" name="exam_reg_22" id="exam_reg_22" rows="2" readonly>{{$obj_hospitalizacion_atencion->exam_reg_22}}</textarea>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-lg-12 col-md-12">
                                            <label>VII Facial (simetría facial, gusto):</label>
                                            <input type="text" class="form-control" id="exam_reg_23" name="exam_reg_23" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_23}}">
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <div class="col-lg-12 col-md-12">
                                            <label>VIII Cocleovesticular (audición, equilibrio):</label>
                                            <input type="text" class="form-control" id="exam_reg_24" name="exam_reg_24" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_24}}">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-lg-12 col-md-12">
                                            <label>IX y X Glosofaríngeo y Neumogástrico (voz, deglución, gusto):</label>
                                            <input type="text" class="form-control" id="exam_reg_25" name="exam_reg_25" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_25}}">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-lg-12 col-md-12">
                                            <label>XI Espinal (esternocleidomastoideo):</label>
                                            <input type="text" class="form-control" id="exam_reg_26" name="exam_reg_26" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_26}}">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-lg-12 col-md-12">
                                            <label>XII Hipogloso (lengua):</label>
                                            <input type="text" class="form-control" id="exam_reg_27" name="exam_reg_27" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_27}}">
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                  </div> <!-- Panel switch Pares craneales -->

                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <div class="col-lg-12 col-md-12">
                        <label>AMPLIACIÓN DEL EXAMEN FÍSICO (si fuese necesario):</label>
                        <textarea class="form-control" name="ampliacion_exam_fisico" id="ampliacion_exam_fisico" rows="4" readonly>{{$obj_hospitalizacion_atencion->ampliacion_exam_fisico}}</textarea>
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
                      <!-- boton para habilitar la edicion (al ahacr clic tiene q activar los campos de formulario)
                        generar el evento click en javascript
                      -->
                      <button type="button" class="btn btn-primary" id="btnEditDiagnostico" style="position: absolute; top:-6px; right: 0; display: none;">Editar</button>

                    </div>
                  </div>
                  <div class="panel-body">
                    
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token_tag">
                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="10%">Código</th>
                          <th width="60%">Nombre</th>
                          <th width="20%">Tipo</th>
                          <th width="10%">Categoria</th>
                        </tr>
                      </thead>

                      <tbody>
                        @foreach($diagnostico as $row)
                          @if ($row->id_clasificacion_diagnosticos != 9)
                          <?php
                            $tipoDiagnostico = '';
                            switch($row->id_tipo_diagnostico) {
                              case 1: $tipoDiagnostico = 'PRESUNTIVO'; break;
                              case 2: $tipoDiagnostico = 'DEFINITIVO'; break;
                              case 3: $tipoDiagnostico = 'REITERATIVO'; break;
                            }
                            $categoria = '';
                            if ($row->id_categoria != null) {
                              $categoria = ($row->id_categoria == 1) ? 'PRINCIPAL' : 'SECUNDARIO';
                            }
                          ?>
                          <tr class="row-diagnosticosfinal">
                            <td><input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->codigo }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                            <td><input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->nombre }}" class=" input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                            <td><input name="id_tipo_diagnosticos['{{ $row->id_diagnostico }}']" id="" value="{{ $tipoDiagnostico }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                            <td><input type="text" value="{{ $categoria }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                          </tr>
                          @endif
                        @endforeach
                      </tbody>
                    </table>
                    <div style="margin-top: 10px;">
                    
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center" style="position: relative">
                      <b>PROCEDIMIENTOS</b>
                      <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0;display:none;">Editar</button>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="10%">Código</th>
                          <th width="70%">Nombre</th>
                          <th width="10%">Cantidad</th>
                          <th width="10%">Diagnóstico</th>
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
                            <td>
                              <input name="iddiagnosticosxProcedimiento[{{ $row->id_diagnostico }}]" id="iddiagnosticosxProcedimiento" class="input-procedimiento form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly value="{{$row->codigo_diagnostico}}">
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </fieldset>

              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm" @if(empty($gestacion)) style="display: none;" @endif>
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
                            <p>{{ $gestacion->fech_ult_regla }}</p>
                        </div>
                        <div class="col-md-3">
                            <label>Fecha probable de parto</label>
                            <p>{{ $gestacion->fech_prob_parto }}</p>
                        </div>
                        <div class="col-md-3">
                            <label>Semanas de gestación</label>
                            <p>{{ $gestacion->semanas_gestacion }}</p>
                        </div>
                        <div class="col-md-3">
                            <label>N° de controles prenatales:</label>
                            <p>{{ $gestacion->num_controles }}</p>
                        </div>
                      </div>
                      <div class="form-group">
                          <div class="col-md-3">
                              <label>DUDA</label>
                              <p>{{ $gestacion->duda ? 'Sí' : 'No' }}</p>
                          </div>
                      </div>
                    @endif
                  </div>
                </div>
              </fieldset>

              @if (isset($obj_hospitalizacion_atencion->id_puerperio) || !empty($natis))
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center" style="position: relative"><b>SALUD MATERNA</b></div>
                    </div>
                    <div class="panel-body" style="font-size: 12px">
                      @if (isset($obj_hospitalizacion_atencion->id_puerperio))
                        <div class="panel panel-default">
                          <div class="panel-title text-center" style="text-align: left; margin: 10px 0px 0px 10px"><b>PUERPERA</b></div>
                          <div class="panel-body">
                            <div class="row">
                              <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <label class="control-label">FECHA Y HORA DE PARTO</label>
                                <p>{{ \Carbon\Carbon::parse($obj_hospitalizacion_atencion->fecha_parto)->format('d-m-Y H:i') }}</p> 
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <label class="control-label">TIPO DE PARTO</label>
                                <p>{{ $obj_hospitalizacion_atencion->puerperio_tipo_parto }}</p> 
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <label class="control-label">COMPLICACIONES</label>
                                <p>{{ $obj_hospitalizacion_atencion->puerperio_complicaciones }}</p> 
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                      @endif
                      
                      @if (isset($puerperio))
                        <div class="panel panel-default">
                          <div class="panel-title text-center" style="text-align: left; margin: 10px 0px 0px 10px"><b>NEONATOS</b></div>
                          <div class="panel-body" style="font-size: 12px">
                            @foreach ( $puerperio->natis as $nati)
                              <div class="row">
                                <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
                                  <label class="control-label">ESTADO DE VIDA</label>
                                  <p>{{ $nati->estado }}</p> 
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
                                  <label class="control-label">NUM. DOCUMENTO</label>
                                  <p>{{ $nati->num_doc }}</p> 
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
                                  <label class="control-label">SEXO</label>
                                  <p>{{ $nati->sexo == 'M' ? "MASCULINO" : "FEMENINO" }}</p> 
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
                                  <label class="control-label">TALLA(cm)</label>
                                  <p>{{ $nati->talla }}</p> 
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
                                  <label class="control-label">PESO(gr)</label>
                                  <p>{{ $nati->peso }}</p> 
                                </div>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      @endif
                    </div>
                  </div>
                </fieldset>
              @endif

                <!-- <div class="form-group">
                  <div class="col-md-12"> -->

               @if (isset($obj_hospitalizacion_atencion->id_puerperio))
                <fieldset id="fieldEventosEp" style="">
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
                      <b>PLAN DE TRABAJO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <!-- <div class="col-lg-6 col-md-12"> -->
                      <label>EXÁMENES DE AYUDA DIAGNÓSTICA</label>
                      <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <!-- <div class="panel-body"> -->
                          <!-- Laboratorio -->
                          <input type="hidden" id="doc_firmar_laboratorio" value="" />
                          <input type="hidden" value="{{ $flag_laboratorio }}" name="flag_laboratorio" id="flag_laboratorio">
                          <input type="hidden" value="{{ $flag_firmar_laboratorio }}" name="flag_firmar_laboratorio" id="flag_firmar_laboratorio">
                          <div class="form-group">
                            <div class="col-md-12">   
                              <fieldset id="fsExamenLab">
                                <div class="panel panel-info panel-bordered panel-sm" style=" 
                                <?php
                                      if(!$is_profesion_medico){
                                        echo "display:none;";
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
                                    $proclaboratorio = $model_prestacion->GetPrestacionLaboratorio2($id_prestacion, $rowLaboratorio->id);
                                  ?>
                                  <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                    <?php if($rowLaboratorio->estado=="1"){?>
                                    <a data-toggle="modal" data-target="#squarespaceModal2"
                                      onclick="enviarLaboratorio({{$rowLaboratorio->id}})" class="btn btn-danger">
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
                                            @if ($rowLaboratorio->id < 18570)
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
                                            @if ($rowLaboratorio->id < 18570)
                                            <td><?php echo $row->observacion?></td>
                                            @endif
                                            <td><?php echo $row->cantidad?></td>
                                            <td><?php echo $row->codigo_diagnostico?></td>
                                                      @if (intval($nivel) === 3 && intval($rowLaboratorio->flag) > 0)
                                                        <td>@if ($row->pendiente_laboratorio == "SI") Pendiente de Toma de muestra @endif</td>
                                                      @endif
                                                    </tr>
                                                    <?php endforeach;?>

                                                    @if (intval($nivel) === 3 && $rowLaboratorio->has_observacion)
                                                      <?php
                                                      $colspan = (intval($rowLaboratorio->flag) > 0) ? "5" : "4";
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
                                      </fieldset>
                                  <?php endforeach;?>
                                  <?php if ($prestacion->id_estado_reg==1): ?>
                                  <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                    <a onclick="agregarExamenLab()" id="btnNuevoExamenLab" flag="0" class="btn btn-success">Examen
                                      Laboratorio</a>
                                  </div>
                                  <?php endif; ?>
                                  <div style="clear:both"></div>
                                  <fieldset id="fsNuevoExamenLab" style="display:none">
                                    <div class="panel-body">
                                      <div class="col-lg-10">
                                        <table style="width:100%;">
                                          <tr>
                                            <td style="width:50%; text-align:left;">
                                              <button type="button" id="addExamenLab2" class="btn btn-primary" data-toggle="modal"
                                                data-target="#addClassModal">Agregar Examen</button>
                                            </td>
                                            @if(intval($prestacion->id_establecimiento) === 76)
                                              <td style="width:50%; text-align:right;">
                                                <button type="button" id="RUALab2" class="btn btn-primary" data-toggle="modal" data-target="#modalDX">
                                                  RUA LABORATORIO
                                                </button>
                                              </td>
                                            @endif
                                          </tr>
                                        </table>
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
                                          <table style="width: 100%;">
                                            <tr>
                                              <th width="5%">Observación</th>
                                              <th width="95%"></th>
                                            </tr>
                                            <tr>
                                              <td><input type="text" class="form-control" id="lab_orden_toma_muestra" maxlength="8" readonly /></td>
                                              <td><input type="text" class="form-control" id="lab_orden_observacion" name="lab_orden_observacion" maxlength="255" /></td>
                                            </tr>
                                          </table>
                                        @endif
                                      </div>
                                      <div class="col-lg-2">
                                        <div class="panel panel-default">
                                          <div class="panel-body">
                                            <label for="">Hora de toma de muestra:</label>
                                            <div class="radio">
                                              <label>
                                                <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__1" value="1">05:00 AM
                                              </label>
                                            </div>
                                            <div class="radio">
                                              <label>
                                                <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__2" value="2">09:00 AM
                                              </label>
                                            </div>
                                            <div class="radio">
                                              <label>
                                                <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__3" value="3">16:00 PM
                                              </label>
                                            </div>
                                            <div class="radio">
                                              <label>
                                                <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__4" value="4">22:00 PM
                                              </label>
                                            </div>
                                            <div class="radio">
                                              <label>
                                                <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__0" value="0">OTRO
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </div> <!-- .col-md-2 -->
                                    </div>
                                  </fieldset>
                                </div>
                              </fieldset>
                            </div>
                          </div>
                          <!-- Fin Laboratorio -->

                          <!-- Imagenologia -->
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
                                      $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLaboratorio($id_prestacion, $rowRadiologia->id,3);
                                    ?>

                                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                      <?php if($rowRadiologia->estado=="1"){?>
                                      <a data-toggle="modal" data-target="#squarespaceModal3"
                                        onclick="enviarRadiologia({{$rowRadiologia->id}})" class="btn btn-danger">
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
                                          <p>{{$id_prestacion.' - '. $rowRadiologia->id}}</p>
                                        </div>
                                        <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                                        <div class="col-md-3">
                                          <p><p><?php echo $rowRadiologia->fecha_firma; ?></p></p>
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
                                                    
                                                    <td>{{$row->codigo}}</td>    
                                                    <td>{{$row->nombre_corto}}</td>
                                                   
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
                                    <?php if ($prestacion->id_estado_reg==1): ?>
                                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                      <a onclick="agregarExamenRad()" id="btnNuevoExamenRad" flag="0" class="btn btn-success">Examen
                                        de Imagenología</a>
                                    </div>
                                  <?php endif; ?>
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
                          <!-- Fin Imagenologia -->

                        <!-- </div> -->
                      </div>
                    <!-- </div> -->
                    <!-- <div class="col-lg-6 col-md-12"> -->
                      <!-- <label>PROCEDIMIENTOS MÉDICO - QUIRÚRGICOS</label>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <p>{!! nl2br(e($prestacion->procedimiento_medico_quirurgico)) !!}</p>
                        </div>
                      </div> -->
                    <!-- </div> -->
                  <!-- </div> -->
                  <!-- <div class="panel-body"> -->
                    <!-- <div class="col-lg-12 col-md-12"> -->
                      <label>INTERCONSULTAS</label>
                      <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <!-- <div class="panel-body"> -->
                          <!-- Interconsulta -->
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
                                            $is_estable = "SIN INFORMACIÓN";
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
                                                  <!--?php dd($rowi)?-->
                                                  <?php
                                                    if ($rowi->id_servicio == '114' || $rowi->id_servicio == '16') {
                                                      $descripcion_limpia = str_replace("HOSPITALIZACIÓN QUIRÚRGICA-", "", $rowi->descripcion_upss);
                                                      echo $rowi->codigo_interconsulta . ' - ' . $descripcion_limpia;
                                                    } else {
                                                        echo $rowi->codigo_interconsulta . ' - ' . $rowi->servicio;
                                                    }
                                                  ?> 
                                                  <!--{{$rowi->codigo_interconsulta}} - {{$rowi->servicio}}-->
                                                  <!--?php echo $rowi->codigo_interconsulta ?> - <-?php echo $rowi->servicio ?-->
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
                                                      @if (!$existe && (intval($row->id) !== 137 || intval($cambio_cama_data->id_servicio_encargado) === 8))
                                                        <option value="{{ $row->id }}" data-has_guardia="{{ $row->has_guardia }}">{{ $row->has_guardia }} - {{ $row->nombre }}</option>
                                                      @endif
                                                    @endforeach
                                                  </select>
                                                </div> <!-- .form-group -->
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
                                    <?php if ($prestacion->id_estado_reg==1): ?>
                                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                      <a onclick="agregarInterconsulta()" id="btnNuevaInterconsulta" flag="0" class="btn btn-success">Agregar Interconsulta</a>
                                    </div>
                                  <?php endif; ?>
                                  </div>
                                </div>
                              </fieldset>
                              
                            </div>
                          </div>
                          <!-- Fin Interconsulta -->
                        <!-- </div> -->
                      </div>

                      <label>PROCEDIMIENTOS MÉDICO - QUIRÚRGICOS</label>
                      <textarea class="form-control" rows="5" id="procedimiento_medico_quirurgico" name="procedimiento_medico_quirurgico" placeholder="Procedimientos médico – quirúrgicos" readonly>{{$prestacion->procedimiento_medico_quirurgico}}</textarea>
                    <!-- </div> -->
                  </div>
                </div>
              </fieldset>

              <fieldset id="fsAntecedente">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>TRATAMIENTO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>DIETA</label>
                        <textarea class="form-control" tabindex="57" rows="5" id="dieta_terapeutica" name="dieta_terapeutica" placeholder="Dieta." readonly>{{$obj_hospitalizacion_atencion->dieta_terapeutica}}</textarea>
                      </div>
                      <div class="col-md-12">
                        <label>INDICACIONES TERAPÉUTICAS</label>
                        <textarea class="form-control" tabindex="57" rows="5" id="indicacion_terapeutica" name="indicacion_terapeutica" placeholder="Indicaciones terapeutica." readonly>{{$obj_hospitalizacion_atencion->indicacion_terapeutica}}</textarea>
                      </div>
                      <div class="col-md-12">
                        <label>INDICACION COMPLEMENTARIO</label>
                        <textarea class="form-control" tabindex="57" rows="5" id="complementario_terapeutica" name="complementario_terapeutica" placeholder="Cuidados de enfermería y de otros profesionales que sean considerados necesarios." readonly>{{$obj_hospitalizacion_atencion->complementario_terapeutica}}</textarea>
                      </div>
                      
                      <div class="col-md-12">
                        <br>
                        <label for="">PRESCRIBIR RECETA</label>
                        <!-- Receta Vale -->
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
                                          <p>{{$rowReceta->nro_receta}}</p>
                                        </div>
                                        <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                        <div class="col-md-2">
                                          <p><?php date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                                        </div>
                                        <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                        <div class="col-md-4" style="padding:0px">
                                          <p>{{$rowReceta->farmacia}}</p>
                                        </div>
                                      </div>
                                    </div>
                                    <table class="display" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                          <th width="10%">Codigo</th>
                                          <th width="30%">Producto farmacéutico o dispositivo médico</th>
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
                                        <td>{{$row->codigo}}</td>
                                        <td>{{$row->nombre}}
                                            <?php 
                                              if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
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
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <?php endforeach;?>
                                <?php if ($prestacion->id_estado_reg==1): ?>
                                <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                  <a onclick="agregarReceta()" id="btnNuevoReceeta" flag="0" class="btn btn-success">Agregar
                                    Receta</a>
                                </div>
                                <?php endif; ?>
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
                                            @if($nivel<3)
                                                <?php foreach($farmacias as $row):?>
                                                <option value="<?php echo $row->id?>"
                                                  <?php if($row->id==Auth::user()->id_farmacia) echo "selected='selected'"?>>
                                                  <?php echo $row->nombre?></option>
                                                <?php endforeach;?>
                                              @else
                                                <option value="128">Farmacia Dosis Unitaria PIP I del Hospital Nacional PNP Luis N. Saenz</option>
                                                <option value="21">Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                                                <option value="10">Farmacia Oncologica del Hospital Nacional PNP Luis N. Saenz</option>
                                                <option value="132">Farmacia de Preparados Farmacéutico No Estériles del Hospital Nacional PNP Luis N. Saenz</option>
                                              @endif
                                          </select>
                                        </div>
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
                                </fieldset>
                              </fieldset>
                            </div>
                          </div>
                        </fieldset>
                        <!-- Fin Receta Vale -->
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <!-- <fieldset id="fsAltaServicio">
                <div class="panel panel-success panel-bordered panel-sm">
                  <div class="panel-heading">
                  <div class="panel-title text-center"><b>ALTA DE SERVICIO</b></div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">FECHA DE ALTA</label>
                        <input type="text" data-date-end-date="0d" class="form-control datepicker" value="" name="fecha_egreso" id="fecha_egreso"/>
                      </div>

                      <div class="col-md-2">
                        <label for="">HORA DE ALTA</label>
                        <input type="time" name="hora_egreso" id="hora_egreso" value="" class="form-control">
                      </div>

                      <div class="col-md-2">
                        <label for="">SERVICIO</label>
                        <select id="id_area_destino" name="id_area_destino"class="form-control" onchange="getCamas();" style="width:100%">
                          <option value="0">-Seleccione-</option>
                          
                        </select>
                      </div>

                      <div class="col-md-2">
                        <label for="">CAMA/CAMILLA</label>
                        <select id ="id_cama_destino" name="id_cama_destino" class="form-control" style="width:100%">
                          <option value="0">-Seleccione-</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-8">
                        <label for="">OBSERVACION</label>
                        <textarea rows="2" class="form-control" id="observacion_alta_servicio" name="observacion_alta_servicio"></textarea>
                      </div>
                    </div>
                    <hr>
                    <label for=""><b>DIAGNOSTICOS</b></label>
                    <p>Los Diagnósticos de Alta de Servicio se tomarán de los Diagnósticos ya ingresados.</p>
                  </div>
                </div>
              </fieldset> -->
              <?php if ($prestacion->id_estado_reg==1): ?>
              <div id="divGuardar" style="display:none">
                <input class="btn btn-primary input-sm pull-right" value="GUARDAR" name="crea" type="button"
                  form="prestacionescrea" id="btnPrestacion" />&nbsp;&nbsp;
              </div>
              <?php endif; ?>
              <div id="divFirmar">
               
                @if ($prestacion->id_estado_reg == 1)
                    @if ($permiso->crear == 1 && Auth::user()->tipo_registro == 1)
                        <input name="mov[]" id="mov[]" value="{{$id_prestacion}}" type="hidden" />
                        <input type="hidden" value="3" name="proviene_formulario" id="proviene_formulario">
                        <input type="hidden" value="1" name="proviene_formulario_hosp" id="proviene_formulario_hosp">

                        @if (Auth::user()->status_token == 1)
                            <!-- Sin token -->
                            <input class="btn btn-info btn-sm pull-right" value="FINALIZAR" type="button" onclick="firmarsintokens()" style="margin-left:20px" />
                        @else
                            <!-- Con token -->
                            @if ($is_habilitado_firma_electronica)
                              <input class="btn btn-info btn-sm pull-right" value="FIRMAR" type="button" onclick="firmarsintokens()" style="margin-left:20px" />
                            @else
                            <input class="btn btn-info btn-sm pull-right" value="FIRMAR" type="button" id="btnfirmar" style="margin-left:20px" />
                            @endif
                        @endif

                    @else
                        <!-- Boton de Firmar bloqueado -->
                        <button class="btn btn-info btn-sm pull-right" disabled>FIRMAR</button>
                    @endif

                @endif
                
                <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_hospitalizado_by_establecimiento"> <span class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
                
              </div>
              <!-- </fieldset> -->
            </form>

            <!-- </div> -->


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
<input type="hidden" value="{{$flag_firmar}}" name="flag_firmar" id="flag_firmar">
<div id="addComponent"></div>

<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="lineModalLabel">Invalidar Receta</h3>
      </div>
      <div class="modal-body">
        <!-- content goes here -->
        <form class="" method="post" action="{{ route('invalidar_receta_digital')}}" id="">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de esta receta</label>
            <input type="text" class="form-control" id="motivo" name="motivo" placeholder="" required="">
            <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia" value="{{$id_farmacia}}">
            <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="<?php //echo $prestacion->id_receta?>">
            <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$id_prestacion}}">
            <input type="hidden" name="id_evaluacion" id="id_evaluacion" value="0">
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
        <form class="" method="post" action="{{ route('invalidar_examen_laboratorio')}}" id="">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de este examen de laboratorio</label>
            <input type="text" class="form-control" id="motivo_laboratorio" name="motivo_laboratorio" placeholder="" required="">
            <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia"
              value="{{$id_farmacia}}">
            <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$id_prestacion}}">
            <input type="hidden" name="id_evaluacion" id="id_evaluacion" value="0">
            <input type="hidden" class="form-control" id="id_laboratorio" name="id_laboratorio"
              value="">
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
        <form class="" method="post" action="{{ route('invalidar_examen_imagenologia')}}" id="">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de este examen de imagenologia</label>
            <input type="text" class="form-control" id="motivo_imagenologia" name="motivo_imagenologia" placeholder="" required="">
            <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia"
              value="{{$id_farmacia}}">
            <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id_evaluacion" id="id_evaluacion" value="0">
            <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$id_prestacion}}">
            <input type="hidden" class="form-control" id="id_radiologia" name="id_radiologia"
              value="">
          </div>
          <div class="form-group">
            <input id="btnInvalidar" class="btn btn-danger" type="submit" value="Invalidar">
          </div>
        </form>

      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="showDetalleExamenPendienteRadiologia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-examen" style="background-color: #ffffff">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #494949">&times;</span></button>
                <h4 class="modal-title" style="background-color: #ffffff"><small style="color: #494949; font-weight: 700">Examenes de Imagenes</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="contenido-table-pendiente-radiologia">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="showDetalleExamenPendiente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-examen" style="background-color: #ffffff">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #494949">&times;</span></button>
                <h4 class="modal-title" style="background-color: #ffffff"><small style="color: #494949; font-weight: 700">Pendientes Laboratorio</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="contenido-table-pendiente">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
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

{{-- Modal de Hemocultivo --}}
@include('frontend.laboratorios.modal_solicitud_hemocultivo')

{{-- MODAL SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS --}}
@include('frontend.laboratorios.modal_solicitud_citoquimicos_liquidos_biologicos')

<!-- Modal para maximizar las imagenes -->
<div id="modalMaximizarImagen" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="modalImage" src="" class="img-responsive" style="margin: auto;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
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
@include('frontend.includes.footer')
@endsection
