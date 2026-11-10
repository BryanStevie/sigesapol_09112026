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
  AddExamenLab();
  AddExamenRad();

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

function AddExamenLab() {
  var newRow = "";
  var ind = $('#tblExamenLab tbody tr').length;

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

  newRow += '<tr>';
  newRow +=
    '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +
    ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow += '<input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="id_examen_lab' + ind +
    '" name="id_examen_lab[]" value="" data-id='+ind+'></td>';
  newRow +=
    '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_lab_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="examen_lab_cantidad' +
    ind + '" name="examen_lab_cantidad[]" value="1"></td>';
  newRow +=
    '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';
  $('#tblExamenLab tbody').append(newRow);

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
  
  var item_tipo_diagnostico = '';
  item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
  item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
  item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
  item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';
  newRow +='<tr>';
  newRow +='<td><div class="panel panel-info panel-bordered panel-sm"><div class="form-group"> <div class="panel-body"> <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>SERVICIO</label></div> <div class="col-md-4" style="margin-left:0px;padding-left:0px"> <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el servicio" name="nombre_servicio_interconsulta[]" tabindex="8" required="" id="nombre_servicio_interconsulta'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" /> </div> <div class="col-md-1"> <label>Motivo</label> </div> <div class="col-md-6"> <input type="text" class="form-control" value="" name="motivo_interconsulta[]" id="motivo_interconsulta'+ind+'" tabindex="2" data-date-end-date="0d" autocomplete="off" /> </div> <div class="col-md-12"><br/> <label>RESUMEN</label> <textarea class="form-control" cols="5" id="resumen_interconsulta'+ind+'" name="resumen_interconsulta[]" placeholder="Relato m&eacute;dico o caracteristicas de la interconsulta"></textarea></div></div></div></div></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_servicio_interconsulta" id="id_servicio_interconsulta'+ind+'" name="id_servicio_interconsulta[]" value="" data-id='+ind+'></td>';
  newRow +='</tr>';
  
  $('#tblInterconsulta tbody').append(newRow);

  $("#nombre_servicio_interconsulta"+ind).autocomplete({
    minLength: 1,
    delay : 400,
    source: function(request, response) {
      $.ajax({
//        url: '../obtener_procedimiento/' + request.term,
        url: '../../obtener_consultorio_interconsulta/' + request.term,
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

  newRow += '<tr>';
  newRow +=
    '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de Radiografia" name="nombre_examen_rad[]" tabindex="8" required="" id="nombre_examen_rad' +
    ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow += '<input type="hidden" class="form-control typeahead tt-query id_radiologia" id="id_examen_rad' + ind +
    '" name="id_examen_rad[]" value="" data-id='+ind+'></td>';
  newRow +=
    '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_rad_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="examen_rad_cantidad' +
    ind + '" name="examen_rad_cantidad[]" value="1"></td>';
  newRow +=
    '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';
  $('#tblExamenRad tbody').append(newRow);

  var nivel = $('#id_nivel').val();
  genero = getSexoGenero();

  //tipo=3

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
  var id_ipress = $('#id_establecimiento').val();;

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
    bootbox.alert("Seleccione el tipo atención");
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

    nuevalperiodo = document.getElementById('tiempo_periodo' + idval).value;
  }

  var item_producto = "";
  $('#cmbMedicamentoTemp option').each(function() {
    item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
      "</option>"
  });

  newRow += '<tr>';
  newRow += '<td><select class="form-control cmbMedicamento" id="cmbMedicamento' + ind + '" ind="' + ind +
    '" tabindex="' + tabindex + '" name="id_producto[]" style="width: 400px">' + item_producto + '</select></td>';

  newRow +=
    '<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  newRow +=
    '<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';

  newRow +=
    '<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
  newRow +=
    '<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="' + (
      tabindex + 1) + '" required="" id="tiempo_periodo' + ind +
    '" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="' + nuevalperiodo +
    '"/>';

  newRow += '<td><input type="text" name="posologia[]" tabindex="' + (tabindex + 1) + '" required="" id="posologia' +
    ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';

  <?php //endif;?>
  newRow += '<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
  newRow += '<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
  newRow += '<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
  newRow += '<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
  newRow += '<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
  newRow += '<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 2) +
    '" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados' +
    ind +
    '" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';

  <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
  //newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
  <?php //endif;?>

  newRow += '<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';
  newRow +=
    '<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';
  $('#example tbody').append(newRow);
  $("#cmbMedicamento" + ind).select2({
    max_selected_options: 4
  }).focus();

  $("#nro_med_solictados" + ind).keypress(function(e) {
    if (e.which == 13) {
      <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
      AddProducto();
      <?php //else:?>
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      <?php //endif;?>
    }
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
  //$("#turno").val('').trigger("change");
  //var newOption = "";
  //newOption = "<option value=''>--Seleccionar-</option>";
  //$('#id_medico').html(newOption);
  //$("#id_medico").val('').trigger("change");

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
  var x = 0;
  var x = nro_interconsulta();
  var dni = $('#dni_beneficiario').val();
  var interconsulta = "NO";
  //$('#interconsulta').val();	
  if (id_tiporeceta == "3") {
    if (nivel == 3)
      var id_farmacia = 20;

    if (interconsulta == 'SI') {
      if (id_consultorio == 104) {
        id_consultorio2 = 104
      } else {
        id_consultorio2 = 105
      }
      $.ajax({
        //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + id_consultorio2+ '/' + dni,
        url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia + '/' + id_consultorio + '/' +
          id_consultorio2,
        success: function(result) {
          var newOption = "";
          newOption = "<option value=''>--Seleccionar--</option>";
          $(result).each(function(ii, oo) {
            newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
              .nombre + ", " + oo.abreviatura + "</option>";
          });
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });

    } else {

      if (x == 0) { //SIN INTERCONSULTA
        if (id_consultorio == 104) {
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' +
              104,
            //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
            success: function(result) {
              var newOption = "";
              newOption = "<option value=''>--Seleccionar--</option>";
              $(result).each(function(ii, oo) {
                newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                  .nombre + ", " + oo.abreviatura + "</option>";
              });
              $('#cmbMedicamentoTemp').html(newOption);
            }
          });
        } else {
          if (id_consultorio == 21) {
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                '/' + 21,
              //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
              success: function(result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function(ii, oo) {
                  newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                    .nombre + ", " + oo.abreviatura + "</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
                //$("#id_medico").select2({max_selected_options: 4});

              }
            });
          } else {
            if (id_consultorio == 72) {
              $.ajax({
                //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
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
            } else {
              $.ajax({
                url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia + '/' +
                  id_consultorio + '/' + 105,
                //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
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

          }
        }
      } else {
        if (id_consultorio == 104) {
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress + '/' + 104 + '/' +
              dni,
            success: function(result) {
              var newOption = "";
              newOption = "<option value=''>--Seleccionar--</option>";
              $(result).each(function(ii, oo) {
                newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                  .nombre + ", " + oo.abreviatura + "</option>";
              });
              $('#cmbMedicamentoTemp').html(newOption);
            }
          });
        } else {
          if (id_consultorio == 21) {
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress + '/' + 21 + '/' +
                dni,
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
          } else {
            if (id_consultorio == 72) {
              $.ajax({
                //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
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
            } else {
              $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress + '/' + 105 +
                  '/' + dni,
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

          }
        }
      }
    }
  } else {

    
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
          //$("#id_medico").select2({max_selected_options: 4});

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
          //$("#id_medico").select2({max_selected_options: 4});

        }
      });

    }

    //}

  }

  /*
	 if(id_tiporeceta != 1 || id_farmacia == 22){

		$.ajax({
		url: '/obtener_medico_por_idipress_idconsultorio_solo_emergencia/' + id_ipress+ '/' + id_consultorio,
		success: function(result){                    
			var newOption = "";
			newOption = "<option value=''>--Seleccionar-</option>";
			$(result).each(function (ii, oo) {
			newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
			});                 
			$('#id_medico').html(newOption);
			$("#id_medico").select2({max_selected_options: 4});
		}
		});
	}
  	*/


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
                        <label for="fecha_atencion">FECHA DE ATENCION</label>
                        <p><?php echo $prestacion->fecha_atencion?></p>
                      </div>
                      <div class="col-md-3">
                        <label>N° HISTORIA CLINICA</label>
                        <p><?php echo $prestacion->nro_historia?></p>
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
                    <div class="form-group">
                      <div class="col-md-4">
                        <label for="tipo">NOMBRE DE LA IPRESS</label>
                        <p><?php echo $prestacion->establecimiento?></p>
                      </div>
                      <div class="col-md-3">
                        <label for="nombre_ipres"><?php echo $descripcion ?></label>
                        <p><?php echo $prestacion->servicio?></p>
                      </div>
                      <div class="col-md-2">
                        <label>CODIGO UPSS</label>
                        <p><?php echo $prestacion->codigo_upss?></p>
                      </div>
                      
                      @if($prestacion->id_tipo_atencion==1)
                        @if($prestacion->id_modalidad==1)
                          <div class="col-md-2">
                            <label><?php echo $descripcion2 ?></label>
                            <p><?php echo $prestacion->consultorio?></p>
                          </div>
                        @endif
                      @endif
                    </div>
                    @if($prestacion->id_tipo_atencion==3)
                    <div class="form-group">
                      <div class="col-md-3">
                        <label for="tipo">PABELLON</label>
                        <p><?php //echo $prestacion->pabellon?></p>
                      </div>
                      <div class="col-md-3">
                        <label for="dni_beneficiario">PISO</label>
                        <p><?php //echo $prestacion->piso?></p>
                      </div>
                      <div class="col-md-3">
                        <label>HABITACION</label>
                        <p><?php //echo $prestacion->cuarto?></p>
                      </div>
                      <div class="col-md-3">
                        <label>CAMA</label>
                        <p><?php //echo $prestacion->cama?></p>
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

                      <div class="col-md-4">
                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->tipo_doc_ident?></p>
                      </div>

                      <div class="col-md-4">
                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->nro_doc_ident?></p>
                      </div>

                      <div class="col-md-4">
                        <label for="dni_beneficiario">SEXO</label>
                        <p><?php echo $prestacion->sexo?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $prestacion->asegurado_paterno?></p>
                      </div>

                      <div class="col-md-4">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $prestacion->asegurado_materno?></p>
                      </div>

                      <div class="col-md-4">
                        <label>NOMBRES</label>
                        <p><?php echo $prestacion->asegurado_nombre?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label>FECHA DE NACIMIENTO</label>
                        <p><?php echo $prestacion->fecha_nac?></p>
                      </div>
                      <div class="col-md-4">
                        <label>PARENTESCO &nbsp;</label>
                        <p><?php echo $prestacion->parentesco?></p>
                      </div>

                      <div class="col-md-4">
                        <label>GRADO &nbsp;</label>
                        <p><?php echo $prestacion->grado?></p>
                      </div>
                    </div>

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

                      <div class="col-md-4">
                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                        <p>DNI</p>

                      </div>

                      <div class="col-md-4">
                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
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
                      <div class="col-md-2">
                        <label>N° COLEGIATURA</label>
                        <p><?php echo $prestacion->colegiatura?></p>
                      </div>

                      <div class="col-md-2">
                        <label>N° RNE</label>
                        <p><?php echo $prestacion->rne?></p>
                      </div>

                      <div class="col-md-4">
                        <label>PROFESION</label>
                        <p><?php echo $prestacion->profesion?></p>
                      </div>

                      <div class="col-md-4">
                        <label>ESPECIALIDAD</label>
                        <p><?php echo $prestacion->especializacion?></p>
                      </div>

                    </div>

                  </div>
                </div>

              </fieldset>

              <!--
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm panel-body">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>CONDICION</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <div class="col-md-4">
                        <label for="tipo">IPRESS</label>
                        <div class="col-md-10">
                          <p><?php echo $prestacion->ipress?></p>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label for="tipo">UPSS</label>
                        <div class="col-md-10">
                          <p><?php echo $prestacion->upss?></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      @if($prestacion->id_tipo_atencion>1)
                      <div class="col-md-4">
                        <br /><br />
                        <label for="tipo">FECHA INGRESO</label>
                        <div class="col-md-10">
                          <p><?php //echo $prestacion->fecha_ingresos?></p>
                        </div>
                      </div>
                      @if($prestacion->id_tipo_atencion<4) @if($prestacion->fecha_alta!="")
                        <div class="col-md-4">
                          <br /><br />
                          <label for="tipo">FECHA ALTA</label>
                          <div class="col-md-10">
                            <p><?php echo $prestacion->fecha_alta?></p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <br /><br />
                          <label for="tipo">MOTIVO ALTA</label>
                          <div class="col-md-10">
                            <p>
                              <?php
															switch ($prestacion->id_motivo_alta) {
																case '1': echo "Mejorado"; break;
																case '2': echo "Curado"; break;
																case '3': echo "Transferido"; break;
																case '4': echo "Voluntario"; break;
																default : echo ""; break;
															}								
														?>
                            </p>
                          </div>
                        </div>
                        @endif
                        @endif
                        @endif
                    </div>
                  </div>
                </div>
              </fieldset>
			  -->
              
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
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
                              <b>PROCEDIMIENTOS</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
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
                    
              <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab110default" data-toggle="tab" style="font-size:12px">ANTECEDENTES</a></li>
                    <li><a href="#tab7default" data-toggle="tab" style="font-size:12px">ANAMNESIS</a></li>
                    <li><a href="#tab8default" data-toggle="tab" style="font-size:12px">EXAMEN FÍSICO</a></li>
                    <li><a href="#tab9default" data-toggle="tab" style="font-size:12px">TRATAMIENTO HABITUAL</a></li>
                    <li><a href="#tab10default" data-toggle="tab" style="font-size:12px">PLAN DE TRABAJO</a></li>
                    
                  </ul>
                </div>
                <div class="panel-body">
                  <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab110default">
                      <div class="panel-body">
                        <div class="col-md-12">
                          <label>ATECEDENTE</label>
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
                    <div class="tab-pane" id="tab7default">
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
                    </div>
                    <div class="tab-pane" id="tab8default">
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
                        @if($prestacion->id_consultorio == 27)
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
                                    <label>TEMPERATURA (°C) &nbsp;</label>
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
                                    <label>TAMAÑO DE CINTURA (cm)</label>
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
                      @endif
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
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>EXAMENES AUXILIARES</label>
                            <p><?php echo $prestacion->examen_auxiliar?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="tab9default">
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>TRATAMIENTO AUXILIAR</label>
                            <p><?php echo $prestacion->tratamiento_auxiliar?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="tab10default">
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>PLAN DE TRABAJO</label>
                            <p><?php echo $prestacion->plan_trabajo?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                @if($prestacion->id_tipo_atencion==1)
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
                @endif
                <div class="form-group">
                  <div class="col-md-12">
                    <label class="col-md-2" style="padding-left:0px">PRESCRIBIR RECETA</label>
                    <div class="col-md-8">
                      <p><?php echo (count($receta)>0)?"SI":"NO"?></p>
                    </div>
                  </div>
                </div>
              </div>

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
                                <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
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
                                <th width="15%">Cantidad Prescrita</th>
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
                              <td><?php if($row->codigo=='59075700'):
                                 echo $row->cantidad_prescrita/1000;
                                 else:
                                  echo $row->cantidad_prescrita;
                                 endif; ?>
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
                    <?php //if($prestacion->condicion==0): ?>

                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                      <a onclick="agregarReceta()" id="btnNuevoReceeta" flag="0" class="btn btn-success">Agregar
                        Receta</a>
                    </div>
                    <?php //endif; ?>
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
                                <?php endforeach;?>
                              </select>
                            </div>
                          </div>

                          <!--
							<div class="form-group">
								<div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px;font-size:13px">
									<label>PACIENTE CONTINUADOR</label>
								</div>
								<div class="col-sm-2" style="margin-left:0px;padding-left:0px">
								  <button type="button" id="addCondicion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
									<div class="handle"></div>
								  </button>
								</div>
								<input type="hidden" name="condicion" id="condicion" value="0">
								
								<div id="divCondicion" style="display:none">
									<div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px;font-size:13px">
										<label>DIAS DE RECETAS</label>
									</div>
									
									<div class="col-md-1" style="margin-left:0px;padding-left:0px">
										<select class="form-control" name="cantidadReceta" id="cantidadReceta" tabindex="8" style="width: 100%">
											<option value="0"></option>
											<option value="1">30</option>
											<option value="2">60</option>
											<option value="3">90</option>
										</select>
									</div>
								</div>
								
							</div>
							-->
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
                                <th>Producto farmacéutico o dispositivo médico</th>
                                <th style="width:10%">Stock Almacen</th>
                                <th style="width:10%">Stock <?php //echo $establecimiento->nombre?></th>
                                <th style="width:10%">Stock <?php //echo $farma->nombre?></th>
                                <th>Tiempo(Dias)</th>
                                <th>Posologia</th>
                                <th>Cantidad Prescrita</th>
                                <th></th>
                                <th>Eliminar</th>
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
                      $proclaboratorio = $model_prestacion->GetPrestacionLaboratorio($id_prestacion, $rowLaboratorio->id);
                    ?>

                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                      <?php if($rowLaboratorio->estado=="1"){?>
                      <a data-toggle="modal" data-target="#squarespaceModal2"
                        onclick="enviarLaboratorio(<?php echo $rowLaboratorio->id?>)" class="btn btn-danger">
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
                          <p><?php echo $id_prestacion.' - '. $rowLaboratorio->id; ?></p>
                        </div>
                        <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                        <div class="col-md-3">
                          <p><?php echo date("d-m-Y", strtotime($fechaServidor)); ?></p>
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
                                    <th width="20%">Codigo</th>
                                    <th width="60%">Nombre</th>
                                    <th width="20%">Cantidad</th>
                                  </tr>
                                  </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                  <?php foreach($proclaboratorio as $row):?>
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
                        </div>
                      </div>
                    </fieldset>
                    <?php endforeach;?>
                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                      <a onclick="agregarExamenLab()" id="btnNuevoExamenLab" flag="0" class="btn btn-success">Examen
                        Laboratorio</a>
                    </div>
                    <?php //endif; ?>
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
                                    <th width="20%">Codigo</th>
                                    <th width="60%">Nombre</th>
                                    <th width="20%">Cantidad</th>
                                  </tr>
                                  </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                  <?php foreach($procradiologia as $row):?>
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
              <!-- REFERENCIA 
              <fieldset id="fsReferencia">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>REFERENCIA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <br />
                    <div style="clear:both"></div>
                    <?php if(count($referencia)>0):?>
                    <fieldset>
                      <div class="col-md-12">
                       
                            <div class="form-group">
                              <div class="col-md-12">
                                <div class="col-md-4">
                                  <label>ESTABLECIMIENTO: </label>
                                  <?php  echo $referencia->establecimiento?>
                                </div>
                                <div class="col-md-4">
                                  <label>ESPECIFICAR SI REQUIERE UCI: </label>
                                  <?php if($referencia->id_uci==1):  echo 'SI'; else: echo 'NO'; endif;?>
                                </div>
                                @if($referencia->id_uci==1)
                                  <div class="col-md-4">
                                    <?php 
                                        switch($referencia->id_uci_cv){
                                          case '1' : echo "CON VENTILADOR MECANICO"; break;
                                          case '2' : echo "SIN VENTILADOR MECANICO"; break;
                                          case '3' : echo "NINGUNO"; break;
                                        }
                                    ?>
                                  </div>
                                @endif
                              </div>
                              <div class="col-md-12">
                                <div class="col-md-4">
                                  <br/>
                                <label>REQUIERE TRASLADO EN AMBULANCIA: </label>
                                <?php if($referencia->id_ambulancia==1):  echo 'SI'; else: echo 'NO'; endif;?>
                              </div>
                              @if($referencia->id_ambulancia==1)
                                <div class="col-md-4">
                                  <br/>
                                  <label><br/></label>
                                  <?php if($referencia->id_ambulancia_ref==1):  echo 'IDA'; else: echo 'VUELTA'; endif;?>
                                </div>
                                <div class="col-md-4">
                                  <br/>
                                  <label>TIPO DE AMBULANCIA</label>
                                  <?php 
                                        switch($referencia->id_tipo_ambulancia){
                                          case '1' : echo "Tipo I"; break;
                                          case '2' : echo "Tipo II"; break;
                                          case '3' : echo "Tipo III"; break;
                                        }
                                  ?>
                              </div>
                              @endif
                          </div>
                          <div class="col-md-12">
                              <div class="col-md-4">
                                  <br/>
                                  <label>REQUIERE PASAJE: </label>
                                  <?php if($referencia->id_pasaje==1):  echo 'SI'; else: echo 'NO'; endif;?>
                                </div>
                                <div class="col-md-4">
                                   <br/> 
                                  <label>NECESITA ACOMPAÑANTE: </label>
                                  <?php if($referencia->id_acompanante==1):  echo 'SI'; else: echo 'NO'; endif;?>
                                </div>
                                <div class="col-md-4">
                                  <br/>
                                  <label>SITUACION CLINICA DEL PACIENTE :</label>
                                  <?php 
                                        switch($referencia->id_situacion){
                                          case '1' : echo "ELECTIVO"; break;
                                          case '2' : echo "URGENCIA"; break;
                                          case '3' : echo "EMERGENCIA"; break;
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                              <div class="col-md-12">
                                    <br/>
                                  <label>OBSERVACION</label>
                                  <?php echo $referencia->observacion_referencia;?>
                                  
                              </div>
                            </div>
                            </div>
                          </div>
                        
                    </fieldset>
                    <?php endif;?>
                    <?php if(count($referencia)==0):?>
                    <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                      <a onclick="agregarReferencia()" id="btnNuevaReferencia" flag="0" class="btn btn-success">REFERENCIA</a>
                    </div>
                    <div style="clear:both"></div>
                    <fieldset id="fsNuevaReferencia" style="display:none">
                      <div class="panel-body">
                        <br />
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                                <b>REFERENCIA MÉDICA</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                  <div class="col-md-4">
                                  <label>ESTABLECIMIENTO</label>
                                  <select class="form-control" name="id_establecimiento_referencia" id="id_establecimiento_referencia"
                                    tabindex="8" style="width: 100%">
                                    <option value="">--Seleccionar--</option>
                                    <?php foreach($establecimientos as $row):?>
                                    <option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
                                    <?php endforeach;?>
                                  </select>
                                </div>
                                <div class="col-md-4">
                                  <label>ESPECIFICAR SI REQUIERE UCI</label>
                                  <select class="form-control" name="id_uci" onchange="getVentiladorUci()" id="id_uci"
                                    tabindex="8" style="width: 100%">
                                      <option value="">--Seleccionar--</option>
                                      <option value="1">SI</option>
                                      <option value="2">NO</option>
                                    </select>
                                  </div>
                                  <div  id="div_uci_ventilador" style="display:none">
                                  <div class="col-md-4">
                                    <label><br/></label>
                                    <select class="form-control" name="id_uci_cv" id="id_uci_cv"
                                      tabindex="8" style="width: 100%">
                                        <option value="">--Seleccionar--</option>
                                        <option value="1">CON VENTILADOR MECANICO</option>
                                        <option value="2">SIN VENTILADOR MECANICO</option>
                                        <option value="3">NINGUNO</option>
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                  <div class="col-md-4">
                                    <br/>
                                  <label>REQUIERE TRASLADO EN AMBULANCIA</label>
                                  <select class="form-control" name="id_ambulancia" onchange="getAmbulancia()" id="id_ambulancia"
                                    tabindex="8" style="width: 100%">
                                      <option value="">--Seleccionar--</option>
                                      <option value="1">SI</option>
                                      <option value="2">NO</option>
                                    </select>
                                    <label><br/></label>
                                </div>
                                <div  id="div_ambulancia" style="display:none">
                                  <div class="col-md-4">
                                    <br/>
                                    <label><br/></label>
                                    <select class="form-control" name="id_ambulancia_ref" id="id_ambulancia_ref"
                                      tabindex="8" style="width: 100%">
                                        <option value="">--Seleccionar--</option>
                                        <option value="1">IDA</option>
                                        <option value="2">VUELTA</option>                                               
                                      </select>
                                      <label><br/></label>
                                    </div>
                                    <div class="col-md-4">
                                      <br/>
                                    <label>TIPO DE AMBULANCIA</label>
                                    <select class="form-control" name="id_tipo_ambulancia" id="id_tipo_ambulancia" tabindex="8" style="width: 100%">
                                          <option value="">--Seleccionar--</option>
                                          <option value="1">Tipo I</option>
                                          <option value="2">Tipo II</option>
                                    </select>
                                 </div>
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="col-md-4">
                                  <label>REQUIERE PASAJE</label>
                                  <select class="form-control" name="id_pasaje" id="id_pasaje"
                                    tabindex="8" style="width: 100%">
                                      <option value="">--Seleccionar--</option>
                                      <option value="1">SI</option>
                                      <option value="2">NO</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                  <label>NECESITA ACOMPAÑANTE</label>
                                  <select class="form-control" name="id_acompanante" id="id_acompanante"
                                    tabindex="8" style="width: 100%">
                                      <option value="">--Seleccionar--</option>
                                      <option value="1">SI</option>
                                      <option value="2">NO</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                  <label>SITUACION CLINICA DEL PACIENTE</label>
                                  <select class="form-control" name="id_situacion" id="id_situacion"
                                    tabindex="8" style="width: 100%">
                                      <option value="">--Seleccionar--</option>
                                      <option value="1">ELECTIVO</option>
                                      <option value="2">URGENCIA</option>
                                      <option value="3">EMERGENCIA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                              <div class="col-md-12">
                                    <br/>
                                  <label>OBSERVACION</label>
                                  <textarea class="form-control" cols="5" id="observacion_referencia" name="observacion_referencia"
                                    placeholder="Relato m&eacute;dico o caracteristicas de la referencia"></textarea>
                              </div>
                            </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                <?php endif;?>
                </div>
              </fieldset>    
              -->           
              <!--fieldset id="fsAlta">

	<div class="panel panel-info panel-bordered panel-sm">
		<div class="panel-heading">
			<div class="panel-title text-center">
				<b>ALTA DE PACIENTE</b>
			</div>
		</div>
		<div class="panel-body">
			<?php if($prestacion->condicion==0): ?>
				<div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
					<a onclick="agregarAltaPaciente()" id="btnAltaPaciente" flag="0" class="btn btn-success">Dar de Alta</a>
				</div>
			<?php endif; ?>
			<div style="clear:both"></div>
			<fieldset id="divAlta2" style="display:none">
				<div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
					<label>FECHA ALTA</label>
				</div>
				<div class="col-sm-2" style="margin-left:0px;padding-left:0px">
					<input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_alta" id="fecha_alta" style="text-align:left">
				</div>
				<div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
					<label>MOTIVO ALTA</label>
				</div>
				<div class="col-sm-4" style="margin-left:0px;padding-left:0px">
					<select class="form-control input-sm" id="id_motivo_alta" name="id_motivo_alta" tabindex="3" style="width: 100%">
						<option value="">Elegir</option>
						<option value="1">MEJORADO</option>
						<option value="2">CURADO</option>
						<option value="3">TRANSFERIDO</option>
						<option value="4">VOLUNTARIO</option>
					</select>
				</div>
			</fieldset>
		</div>
	</div>
	
</fieldset-->

              <?php //endif;?>

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
        <form class="" method="post" action="{{ route('invalidar_examen_laboratorio')}}" id="">
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
              value="{{Auth::user()->id_farmacia}}">
            <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id_prestacion" id="id_prestacion" value="<?php echo $id_prestacion?>">
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
