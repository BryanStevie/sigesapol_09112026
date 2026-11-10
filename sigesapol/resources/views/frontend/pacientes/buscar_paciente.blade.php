@extends('frontend.layouts.master')
@section('renderjs')
<style>
</style>
<script>
function validarDni() {
  var dni = $('#dni').val();
  let tipoDocumento = $("#tipo_documento").val();
  $('#tipo_documento_text').val($("#tipo_documento option:selected").text());

  var len = dni.length;
  if (tipoDocumento == "1" && len != 8) {
    bootbox.alert("El DNI tiene que ser de 8 digitos");
    return false;
  } else if (tipoDocumento != "1" && len < 6) {
    bootbox.alert("El Documento tiene que ser de 6 a más digitos");
    return false;
  }
  document.getElementById("frmfarmacia").submit();
}

function maxlength_doc_usu() {

  $('#dni').val('');

  if ($("#tipo_documento").val() == "1") {
    $("#dni").attr('maxlength', '8');
  } else {
    $("#dni").attr('maxlength', '12');
  }
  document.getElementById("dni").focus();
}
</script>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="/all_asegurados/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Asegurados</a>
        </li>
        <li class="active"><a href="/buscar_paciente"><i class="fa fa-plus"></i> Nuevo </a></li>
        <!--<li class="active"><a href="/create_medico/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo </a></li>-->
        <!--<li><a href="/formatoxls_personal_medico/{{Auth::user()->id}}"><i class="fa fa-download"></i> Descargar Formato</a></li>-->
        <!--<li><a href="/importaciones_medicos/{{Auth::user()->id}}"><i class="fa fa-info-circle"></i> Importarciones</a></li>-->
      </ul>
    </div>
    <div class="col-md-7">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">Agregar Asegurado</h3>
          </div>
          <div class="panel-body">
            <div class="panel-body">

              <form id="frmfarmacia" class="form-horizontal" role="form" method="post"
                action="{{ route('buscar_paciente_dni') }}" onsubmit="return validarDni()">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <!-- Text input-->
                <div class="form-group">
                  <label for="tipo" class="col-md-2 control-label">TIPO DOC.</label>
                  <div class="col-md-4">
                    <select class="form-control input-sm" id="tipo_documento" name="tipo_documento"
                      onchange="maxlength_doc_usu()" tabindex="3">
                      <option value="1" selected="">DNI</option>
                      <option value="2">CARNET DE EXTRANJERIA</option>
                      <option value="3">PASAPORTE</option>
                      <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                    </select>
                    <input type="hidden" value="" name="tipo_documento_text" id="tipo_documento_text"
                      class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label" for="textinput">Documento</label>
                  <div class="col-md-4">
                    <input id="dni" name="dni" type="text" placeholder="" class="form-control input-md" maxlength="8"
                      required="" onkeypress="return IsNumber(event)">
                    <input id="id_farmacia" name="id_farmacia" type="hidden" value="{{Auth::user()->id_farmacia}}">
                  </div>
                  <div class="col-md-2">
                    <button class="btn btn-info" type="submit">
                      <i class="fa fa-plus"></i> Buscar
                    </button>
                  </div>
                  <div class="col-md-2">
                    <a href="/all_asegurados" class="form-control btn btn-danger">Cancelar</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('frontend.includes.footer')
  @endsection