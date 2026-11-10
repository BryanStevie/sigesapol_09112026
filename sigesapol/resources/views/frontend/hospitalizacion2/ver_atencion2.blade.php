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
  var listIdDiagnosticoObjetos =[];

 var filasmedicamentos = 0;



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

  $('#addExamenRad2').on('click', function() {
    AddExamenRad();
  });

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
  AddExamenLab();
  AddExamenRad();

});

var submitButton = document.getElementById('guardardiagnosticoseditar');
submitButton.addEventListener('click', enviarFormulario);
function getProducto(){
	
	$('#example tbody').html('');
	
	//if($('#id_servicio').val()=="") return false;
	
	//var id_ipress 		= $('#id_ipress').val();
	var id_ipress 		= $('#id_establecimiento').val();
	var id_consultorio 	= $('#id_servicio').val();
	//var tipo_receta		= $('#tipo_receta').val();
	//var id_tiporeceta 	= $('#id_tiporeceta').val();
	var id_tiporeceta 	= $('#id_tipo_atencion').val();
	var id_farmacia     = $('#id_farmacia_receta').val();
  
	var nivel     = $('#id_nivel').val();
	var x  = $('#nro_interconsultas').val();
	var dni = $('#dni_beneficiario').val();
	//var interconsulta = $('#interconsulta').val();
	if(id_tiporeceta == "3"){ 
		
		/*if(interconsulta=='SI'){
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
		{ */ 
		  
		  if(id_farmacia!=21){ //SIN NUTRICIONAL
  			if (id_consultorio==44 || id_consultorio==70){ //onco - hemato
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
  			  if (id_consultorio==21){ //odonto
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
    				if (id_consultorio==72){ //aneste
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
        if(id_farmacia == 21){ //nutricional
       
          if(id_tiporeceta==5 || id_tiporeceta == 3){
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
      }
		  /*else
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
		  }*/          
		//}       
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







function guardarPrestacion() {

  var msg = "";
// Obtener todos los elementos <select> por su atributo 'name'


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

function AddExamenLab() {
  var newRow = "";
  var ind = $('#tblExamenLab tbody tr').length;
  	var tabindex = 71;	
  if (ind > 0) {
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

  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id='+ind+'></td>';
  newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_lab_cantidad typeahead tt-query" style="margin:0 5px;width:95%" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1"></td>';
  newRow += '<td><select style="margin:0 10px;width:95%" class="form-control select-diagnostico"  id="miSelectLab' + ind + '" name="miSelectLab[]"></select></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblExamenLab tbody').append(newRow);
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
      $("#id_examen_lab" + ind).val(id_examen_lab);
      $("#nombre_examen_lab" + ind).prop("readonly", true);
    }
  });

  $("#nombre_examen_lab" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar el examen laboratorio en esta fila");
      $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });

  $("#examen_lab_cantidad" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen laboratorio");
      $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });

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

  
  var tabindex = 112;
  
  /*<select tabindex="72" class="form-control" name="personal_turno" id="personal_turno" onchange="getMedico()" style="width:100%">
<option value="">-Seleccionar-</option><option value="M">MAÑANA</option><option value="T">TARDE</option><option value="N">NOCHE</option></select>
*/
  
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
              <input type="hidden" value="{{$prestacion->fecha_atencion}}" name="fecha_atencion" id="fecha_atencion">
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
                        <p>{{$prestacion->fecha_atencion}}</p>
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
                        <div class="col-lg-3 col-md-12">
                          <label>PESO</label>
                          <input type="text" class="form-control" id="fn_bio_3" name="fn_bio_3" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_3}}">
                        </div>
                        <div class="col-lg-3 col-md-12">
                          <label>ORINA</label>
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
                                <div class="col-lg-3 col-md-12">
                                  <label>Talla:</label>
                                  <input type="text" class="form-control" id="exam_gen_4" name="exam_gen_4" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_4}}">
                                </div>
                                <div class="col-lg-3 col-md-12">
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
                                  <input type="file" name="file_exam_gen_9" id="file_exam_gen_9" class="input-sm">
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
                              <input type="file" name="file_cabeza_cuello" id="file_cabeza_cuello" class="input-sm">
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
                              <input type="file" name="file_mamas" id="file_mamas" class="input-sm">
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
                                  <input type="file" name="file_exam_reg_10" id="file_exam_reg_10" class="input-sm">
                                  <textarea class="form-control" name="exam_reg_10" id="exam_reg_10" rows="3" readonly>{{$obj_hospitalizacion_atencion->exam_reg_10}}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>CARDIOVASCULAR:</label>
                                  <input type="file" name="file_exam_reg_11" id="file_exam_reg_11" class="input-sm">
                                  <textarea class="form-control" name="exam_reg_11" id="exam_reg_11" rows="3" readonly>{{$obj_hospitalizacion_atencion->exam_reg_11}}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>ABDOMEN:</label>
                                  <input type="file" name="file_exam_reg_12" id="file_exam_reg_12" class="input-sm">
                                  <textarea class="form-control" name="exam_reg_12" id="exam_reg_12" rows="3" readonly>{{$obj_hospitalizacion_atencion->exam_reg_12}}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-12 col-md-12">
                                  <label>GENITO URINARIO:</label>
                                  <input type="file" name="file_exam_reg_13" id="file_exam_reg_13" class="input-sm">
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
                              <input type="file" name="file_neurologico" id="file_neurologico" class="input-sm">
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
                                          data-toggle="button" aria-pressed="false" autocomplete="off">
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
                <!-- <div class="form-group">
                  <div class="col-md-12"> -->

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
                                        <p><?php echo $rowLaboratorio->fecha_firma; ?></p>
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
                                            <th width="5%">Codigo</th>
                                            <th width="45%">Nombre</th>
                                            <th width="30%">Observacion</th>
                                            <th width="10%">Cantidad</th>
                                            <th width="10%">Diagnosticos</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                  <?php foreach($proclaboratorio as $row):?>
                                                    <tr>
                                            <td><?php echo $row->codigo?></td>
                                            <td><?php echo $row->nombre?></td>
                                            <td><?php echo $row->observacion?></td>
                                            <td><?php echo $row->cantidad?></td>
                                            <td><?php echo $row->codigo_diagnostico?></td>
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
                                    <a onclick="agregarExamenLab()" id="btnNuevoExamenLab" flag="0" class="btn btn-success">Examen
                                      Laboratorio</a>
                                  </div>
                                  <?php endif; ?>
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
                                            
                                            <th width="45%">Examen</th>
                                            <th width="25%">Observacion</th>
                                            <th width="5%">Cantidad</th>
                                            <th width="25%">Diagnosticos</th>
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
                              </fieldset>
                            </div>
                          </div>
                          <!-- Fin Laboratorio -->

                          <!-- Imagenologia -->
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
                                                    <th width="45%">Nombre</th>
                                                    <th width="10%">Comparativo</th>
                                                    <th width="10%">Lado</th>
                                                    <th width="15%">Observacion</th>
                                                    <th width="5%">Cantidad</th>
                                                    <th width="10%">Diagnostico</th>
                                                    <th width="5%"></th>
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
                                                          else:
                                                            echo "Derecho";
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
                                              <th width="40%">Nombre</th>
                                              <th width="5%">Comparativo</th>
                                              <th width="5%">Lado</th>
                                              <th width="20%">Observacion</th>
                                              <th width="5%">Cantidad</th>
                                              <th width="15%">Diagnostico</th>
                                              <th width="5%"></th>
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
                                                  {{$rowi->codigo_interconsulta}} - {{$rowi->servicio}}
                                                </div>
                                              </div>
                                              <div class="col-md-12">
                                                <div class="col-md-2"> 
                                                  <label>MOTIVO:</label> 
                                                </div> 
                                                <div class="col-md-10"> 
                                                  {{$rowi->motivo_interconsulta}}
                                                </div>
                                              </div>
                                              <div class="col-md-12">
                                                <div class="col-md-2">
                                                  <label>MEDICO DE INTERCONSULTA</label><br/>
                                                </div>
                                                <div class="col-md-10"> 
                                                  {{$rowi->medico_nombre_tr}} {{$rowi->medico_paterno_tr}} {{$rowi->medico_materno_tr}}
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <?php endforeach;?>
                                      </div>
                                    </fieldset>
                                    <fieldset id="fsNuevaInterconsulta" style="display:none">
                                      <div class="col-md-12">
                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-body">
                                            <div class="form-group">
                                              <div class="panel-body">
                                                <table>
                                                  <tr>
                                                      <td>
                                                        <button tabindex="111" type="button" id="addInterconsulta2" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Interconsulta</button>
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
                        <label>INDICACIONES TERAPÉUTICAS</label>
                        <textarea class="form-control" tabindex="57" rows="5" id="indicacion_terapeutica" name="indicacion_terapeutica" placeholder="Dieta, cuidados de enfermería y de otros profesionales que sean considerados necesarios, medicamentos (consignando presentación, dosis, frecuencia y vía de administración)." readonly>{{$prestacion->indicacion_terapeutica}}</textarea>
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
                                            <?php //foreach($farmacias as $row):?>
                                            <option value="128">Farmacia Dosis Unitaria PIP I del Hospital Nacional PNP Luis N. Saenz</option>
                                                <option value="20">Farmacia Dosis Unitaria del Hospital Nacional PNP Luis N. Saenz</option>
                                                <option value="21">Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                                            <?php //endforeach;?>
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
                @if($permiso->crear==1)
                  <input name="mov[]" id="mov[]" value="{{$id_prestacion}}" type="hidden" />
                  <input type="hidden" value="3" name="proviene_formulario" id="proviene_formulario">
                  <?php if( Auth::user()->status_token==1 && $prestacion->fecha_firma=='' && $prestacion->id_estado_reg==1): ?>
                  <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button"
                    onclick="firmarsintokens()" style="margin-left:20px" />
                  <?php else: ?>
                  <?php if(Auth::user()->tipo_registro==3 && $prestacion->fecha_firma=='' && $prestacion->id_estado_reg==1): ?>
                  <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button"
                    onclick="firmarsintokens()" style="margin-left:20px" />
                  <?php else: ?>
                    <?php if ($prestacion->id_estado_reg==1): ?>
                      <input class="btn btn-info input-sm pull-right" value="FIRMAR" type="button" id="btnfirmar" style="margin-left:20px" />
                    <?php endif; ?>
                  <?php endif; ?>
                  <?php endif; ?>
                  <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_hospitalizado_by_establecimiento"> <span
                    class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
                @else
                <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_hospitalizado_by_establecimiento"> <span
                    class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
                @endif
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



@include('frontend.includes.footer')
@endsection
