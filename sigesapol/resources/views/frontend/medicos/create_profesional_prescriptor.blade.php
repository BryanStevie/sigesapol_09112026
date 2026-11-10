@extends('frontend.layouts.master')
@section('renderjs')
<style>


</style>

<script>
function jsDecimals(e) {

  var evt = (e) ? e : window.event;
  var key = (evt.keyCode) ? evt.keyCode : evt.which;
  if (key != null) {
    key = parseInt(key, 10);
    if ((key < 48 || key > 57) && (key < 96 || key > 105)) {
      //Aca tenemos que reemplazar "Decimals" por "NoDecimals" si queremos que no se permitan decimales
      if (!jsIsUserFriendlyChar(key, "Decimals")) {
        return false;
      }
    } else {
      if (evt.shiftKey) {
        return false;
      }
    }
  }
  return true;
}

function jsIsUserFriendlyChar(val, step) {
  // Backspace, Tab, Enter, Insert, y Delete
  if (val == 8 || val == 9 || val == 13 || val == 45 || val == 46) {
    return true;
  }
  // Ctrl, Alt, CapsLock, Home, End, y flechas
  if ((val > 16 && val < 21) || (val > 34 && val < 41)) {
    return true;
  }
  if (step == "Decimals") {
    if (val == 190 || val == 110) { //Check dot key code should be allowed
      return true;
    }
  }
  // The rest
  return false;
}

function IsNumeric(valor) {
  var log = valor.length;
  var sw = "S";
  for (x = 0; x < log; x++) {
    v1 = valor.substr(x, 1);
    v2 = parseInt(v1);
    //Compruebo si es un valor numérico 
    if (isNaN(v2)) {
      sw = "N";
    }
  }
  if (sw == "S") {
    return true;
  } else {
    return false;
  }
}
var primerslap = false;
var segundoslap = false;


function validateFrm() {
  $('#btnReg').prop('disabled', true);

  if ($('#frmMedico').find("#nombre").val() == "") {
    bootbox.alert("El nombre del médico no puede estar en vacío.");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#id_colegio_profesional").val() == "") {
    bootbox.alert("Debe seleccionar una opcion de Colegio.");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#paterno").val() == "") {
    bootbox.alert("El apellido paterno del médico no puede estar vacío.");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  /*if ($('#frmMedico').find("#materno").val() == "") {
    bootbox.alert("El apellido materno del médico no puede estar vacío.");
    $('#btnReg').prop('disabled', false);
    return false;
  }*/

  if ($('#frmMedico').find("#id_especializacion").val() == "") {
    bootbox.alert("Debe seleccionar la especialidad del médico.");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#tipo_contrato").val() == "") {
    bootbox.alert("Debe seleccionar el tipo de contrato del médico");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#id_profesion").val() == "") {
    bootbox.alert("Debe seleccionar la profesión del médico");
    $('#btnReg').prop('disabled', false);
    return false;
  }

  if ($('#frmMedico').find("#id_profesion").val() == "4") {
    if ($('#frmMedico').find("#rne").val() == "") {
      bootbox.alert("Selecciono MEDICO ESPECIALISTA, portanto deberá ingresar el Nro. RNE");
      $('#btnReg').prop('disabled', false);
      return false;
    }
  }


  var ind = $('#example tbody tr').length;
  if (ind > 0) {
    var fila_medicamentos = $('#example tbody').children('tr');
    var fila_medicamento = "";
    var valmedentre = "";
    var nind = 0;
    for (var i = 0; i < ind; i++) {
      fila_medicamento = fila_medicamentos[i];
      valmedentre = $("#consultorio" + nind).val();
      if (valmedentre == "") {
        bootbox.alert("No selecciono uno de los servicios agregados");
        $('#btnReg').prop('disabled', false);
        return false;
      }
      nind++;
    }
  }

  submitFrm();
  return false;
}

function submitFrm() {
  bootbox.confirm({
    message: "Aviso: Se guardarán los registros ingresados. ¿Desea continuar?.",
    className: 'title-dialog',
    buttons: {
      confirm: {
        label: 'Si',
        className: 'btn-success btn-lg'
      },
      cancel: {
        label: 'No',
        className: 'btn-danger btn-lg'
      }
    },
    callback: function(result) {
      if (result == true) {
        $("#frmMedico").submit();
      } else {
        $('#btnReg').prop('disabled', false);
      }
    }
  });
}

function AddFila() {

  var newRow = "";

  var ind = $('#example tbody tr').length;

  var item_servicio = "";
  $('#id_consultoriotemp option').each(function() {
    item_servicio += "<option value=" + $(this).val() + ">" + $(this).html() + "</option>"
  });
  newRow += '<tr>';
  newRow += '<td class="col-sm-9"><select class="form-control consultorio" id="consultorio' + ind +
    '" name="id_consultorio[]" style="width:100%" >' + item_servicio + '</select></td>';
  newRow +=
    '<td class="col-sm-3"><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';
  //alert(newRow);
  $('#example tbody').append(newRow);
  $("#consultorio" + ind).select2({
    max_selected_options: 4
  }).focus();

  var nind = 0;
  $("#consultorio" + ind).on("change", function(e) {
    var flagx = 0;
    cmb = $(this);
    id_consultorio = $("#consultorio" + ind).val();

    $('.consultorio').each(function() {
      var ind_tmp = $(this).val();
      if ($(this).val() == id_consultorio) flagx++;
    });

    if (flagx > 1) {
      bootbox.alert("El servicio de atención, ya ha sido seleccionado");
      $("#consultorio" + ind).val("").trigger("change");
      return false;
    }
  });
}
$(document).ready(function() {

  $("#id_establecimiento").select2({
    width: 'resolve', // need to override the changed default
    max_selected_options: 4
  });

  $("#id_establecimiento").trigger('change');

  $('#addRow').on('click', function() {
    AddFila();
  });

  $('#example tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  AddFila();
});
</script>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="/gestion_medicos/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Personal de Salud</a>
        </li>
        <li class="active"><a href="/buscar_medico"><i class="fa fa-plus"></i> Nuevo </a></li>
        <!--<li class="active"><a href="/create_medico/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo Personal de Salud</a></li>-->
        <!--<li><a href="/formatoxls_personal_medico/{{Auth::user()->id}}"><i class="fa fa-download"></i> Descargar Formato</a></li>-->
        <!--<li><a href="/importaciones_medicos/{{Auth::user()->id}}"><i class="fa fa-info-circle"></i> Importarciones</a></li>-->
      </ul>
    </div>
    <div class="col-md-9 well">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">Agregar Personal de Salud</h3>
          </div>
          @if(count($medico_registrado)>0)
          <div style="margin: 20px 20px 20px 20px">
            El Profesional Prescriptor <b>{{$medico_registrado->nombre}} {{$medico_registrado->paterno}}
              {{$medico_registrado->materno}}
              ({{$medico_registrado->especializacion}})</b> identificado con DNI <b>{{$medico_registrado->dni}}</b> ya
            se encuentra registrado en la IPRESS PNP
            <b>{{$medico_registrado->ipress}}</b>, en estado
            <?php echo $medico_registrado->estado == "1" ? "<b>ACTIVO</b>" : "<b>INACTIVO</b>"; ?>.<br /><br />
            <a href="/buscar_medico" class="btn btn-default"><i class="fa fa-plus-circle"></i> Registrar otro
              prescriptor</a>
          </div>
          @else
          <?php
          if ($existe == 1) { //si existe en la base de datos antes registrado -->
            $nompersonal = $personal->nombre;
            $patpersonal = $personal->paterno;
            $matpersonal = $personal->materno;
            $colepersonal = $personal->colegiatura;
            $rnepersonal = $personal->rne;
            $readdatosper = " readonly";
          } else if ($existe == 2) {
            //si existe en el Webservice de afiliados(Saludpol) -->
            $nompersonal = $personal[0]->nomafiliado;
            $patpersonal = $personal[0]->apepatafiliado;
            $matpersonal = $personal[0]->apematafiliado;
            //si existe en el Webservice de afiliados(DIRREHUM) -->
            /*$nompersonal = $personal["nomafiliado"];
                        $patpersonal = $personal["apepatafiliado"];
                        $matpersonal = $personal["apematafiliado"]; */
            $colepersonal = "";
            $rnepersonal = "";
            $readdatosper = " readonly";
          } else if ($existe == 3) {
            //si existe en el WS RENIEC
            /*$nompersonal = $personal["nombres"];
            $patpersonal = $personal["paterno"];
            $matpersonal = $personal["materno"];
            $colepersonal = "";
            $rnepersonal = "";
            $readdatosper = " readonly";*/
            $nompersonal = "";
            $patpersonal = "";
            $matpersonal = "";
            $colepersonal = "";
            $rnepersonal = "";
            $readdatosper = "";
          } else { //si no existe en el Webservice de afiliados(Jose) -->
            $nompersonal = "";
            $patpersonal = "";
            $matpersonal = "";
            $colepersonal = "";
            $rnepersonal = "";
            $readdatosper = "";
          }
          ?>
          <div style="margin-top: 20px">
            <form id="frmMedico" class="form-horizontal" role="form" method="post" action="{{ route('store_medico') }}" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <fieldset>
                <div class="col-md-6">
                  <label>Tipo Documento</label>
                  <input id="tipo_documento" name="tipo_documento" type="text" value="{{$tipo_documento_text}}"
                    class="form-control input-sm" required="" readonly="">
                  <input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{$tipo_documento_id}}">
                </div>
                <div class="col-md-6">
                  <label>Documento</label>
                  <input id="dni" name="dni" type="text" value="{{$dni}}" class="form-control input-sm" required=""
                    readonly="" onkeypress="return IsNumber(event)">
                  <input id="id_user" name="id_user" type="hidden" value="{{Auth::user()->id}}">
                </div>
                <div class="col-md-6">
                  <label>Nombre</label>
                  <input id="nombre" name="nombre" type="text" value="<?php echo $nompersonal; ?>"
                    class="form-control input-sm" required="" <?php echo $readdatosper ?>
                    style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"
                    autofocus="" onkeypress="return solo_letra(event)">
                </div>
                <div class="col-md-6">
                  <label>Apellido Paterno</label>
                  <input id="paterno" name="paterno" type="text" value="<?php echo $patpersonal; ?>"
                    class="form-control input-sm" required="" <?php echo $readdatosper ?>
                    style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"
                    onkeypress="return solo_letra(event)">
                </div>
                <div class="col-md-6">
                  <label>Apellido Materno</label>
                  <input id="materno" name="materno" type="text" value="<?php echo $matpersonal; ?>"
                    class="form-control input-sm" required="" <?php echo $readdatosper ?>
                    style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"
                    onkeypress="return solo_letra(event)">
                </div>
                <div class="col-md-6">
                  <label>Colegio</label>
                  <select id="id_colegio_profesional" name="id_colegio_profesional" class="form-control input-sm" required="">
                    <option value="">--Seleccionar--</option>
                    @foreach($colegio_profesional as $col)
                    <option value="{{$col->id}}">{{$col->descripcion}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Profesi&oacute;n</label>
                  <select id="id_profesion" name="id_profesion" class="form-control input-sm" required="">
                    <option value="">Seleccionar</option>
                    @foreach($profesion as $e)
                    <option value="{{$e->id}}">{{$e->nombre}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label>Nro. Colegiatura</label>
                  <input id="colegiatura" name="colegiatura" value="<?php echo $colepersonal; ?>" type="text"
                    placeholder="" class="form-control input-sm" required="" onkeydown="return jsDecimals(event);"
                    maxlength="6">
                </div>
                <div class="col-md-3">
                  <label>Nro. RNE</label>
                  <input id="rne" name="rne" value="<?php echo $rnepersonal; ?>" type="text" placeholder=""
                    class="form-control input-sm" onkeydown="return jsDecimals(event);">
                </div>
                <div class="col-md-6">
                  <label>Especialidad</label>
                  <select id="id_especializacion" name="id_especializacion" class="form-control input-sm" required="">
                    <option value="">--Seleccionar--</option>
                    @foreach($especializaciones as $esp)
                    <option value="{{$esp->id}}">{{$esp->nombre}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Contrato</label>
                  <select id="tipo_contrato" name="tipo_contrato" class="form-control input-sm" required="">
                    <option value="">--Seleccionar--</option>
                    <?php foreach ($tipo as $rowTipo) : ?>
                    <option value="<?php echo $rowTipo->id ?>"><?php echo $rowTipo->nombre ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>E-mail</label>
                  <input id="email" name="email" type="email" placeholder="" class="form-control input-sm">
                </div>
                <div class="col-md-6">
                  <label>Foto Firma</label>
                  <input type="file" name="archivo_firma" id="archivo_firma" class="form-control input-sm">
                </div>
                <div class="col-md-12">
                  <label>Establecimiento (IPRESS PNP)</label>
                  <select id="id_establecimiento" name="id_establecimiento" class="form-control input-sm" required=""
                    style="width: 100%">
                    @foreach($establecimiento as $e)
                    <?php
                    $idEst = $e->id; ?>
                    <option value="{{$e->id}}" <?php if ($id_establecimiento == $idEst) {
                                                  echo " selected";
                                                } ?>>
                      {{$e->nombre}}
                    </option>
                    @endforeach
                  </select>
                </div>
                <hr />
                <div class="col-md-12">
                  <div class="col-md-12" style="text-align: left; font-size: 18px">
                    Servicio de Atención
                  </div>
                  <div id="entregar_receta" class="row col-md-12"
                    style="padding-top:10px; padding-bottom:0px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                    <button type="button" id="addRow" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar
                      Servicio(s)</button>
                    <div style="display: none;">
                      <select class="form-control" name="id_consultoriotemp" id="id_consultoriotemp">
                        <option value="">Seleccionar</option>
                        @foreach($consultorio as $e)
                        <option value="{{$e->id}}">{{$e->nombre}}</option>
                        @endforeach
                      </select>
                    </div>
                    <table id="example" class="display" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Descripción</th>
                          <th>Eliminar</th>
                        </tr>
                      </thead>
                      <tfoot></tfoot>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>

                <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                  <a href="/gestion_medicos/{{ Auth::user()->id }}" class="form-control btn btn-danger">Cancelar</a>
                </div>
                <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                  <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921"
                    onclick="validateFrm()">Guardar</button>
                </div>
              </fieldset>
            </form>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection