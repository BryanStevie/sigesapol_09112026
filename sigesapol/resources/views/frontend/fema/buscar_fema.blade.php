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
  }/* else if (tipoDocumento != "1" && len < 9) {
    bootbox.alert("El Documento tiene que ser de 9 a más digitos");
    return false;
  }*/
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
        <!--<li><a href="/gestion_medicos/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Personal de Salud</a>-->
        <!--</li>-->
        <!--<li class="active"><a href="/buscar_medico"><i class="fa fa-plus"></i> Nuevo </a></li>-->
        <!--<li class="active"><a href="/create_medico/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo </a></li>-->
        <!--<li><a href="/formatoxls_personal_medico/{{Auth::user()->id}}"><i class="fa fa-download"></i> Descargar Formato</a></li>-->
        <!--<li><a href="/importaciones_medicos/{{Auth::user()->id}}"><i class="fa fa-info-circle"></i> Importarciones</a></li>-->
      </ul>
    </div>
    <div class="col-md-7">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">BUSCAR FICHA APERTURADAS EN FEMA </h3>
            <a href="/all_triajes_fema/{{ Auth::user()->id_farmacia }}" class="btn btn-info pull-right">
                <i class="fa fa-list"></i> LISTADO DE TRIAJE
            </a>
          </div>
          <div class="panel-body">
            <div class="panel-body">

              <form id="frmfarmacia" class="form-horizontal" role="form" method="post"
                action="{{ route('buscar_dni_fema') }}" onsubmit="return validarDni()">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <!-- Text input-->
                <div class="form-group">
                  <label for="tipo" class="col-md-3 control-label">PROCESO DE FICHA</label>
                  <div class="col-md-6">

                 <select class="form-control input-sm" id="tipo_proceso" name="tipo_proceso"
                      onchange="maxlength_doc_usu()" tabindex="3">
                      <option value="1" selected="">FEMA GENERAL - FEMA</option>
                      <option value="2">FEMA GENERAL - LICENCIA DE CONDUCIR</option>
                      <option value="3">FEMA GENERAL - CURSO</option>
                      <option value="4">FEMA GENERAL - REINCORPORACION</option>
                      <option value="5">FEMA GENERAL - OTROS</option>
                      <option value="6">FEMA ESPECIAL - PILOTOS</option>
                      <option value="7">FEMA ESPECIAL - TRIPULANTES</option>
                      <option value="8">FEMA ESPECIAL - AUXILIAR DE AVIACION</option>
                      <option value="9">FEMA ESPECIAL - VIAJES AL EXTRANJERO</option>
                      <option value="10">FEMA ESPECIAL - CURSO AL EXTRANJERO</option>
                      <option value="11">FEMA ESPECIAL - CADETE</option>
                      <option value="12">FEMA ESPECIAL - ALUMNO</option>
                      <option value="13">FEMA ESPECIAL - OTROS</option>
                    </select>
                    <input type="hidden" value="" name="tipo_documento_text" id="tipo_documento_text"
                      class="form-control">
                  </div>
                  
                </div>
                <div class="form-group">
                  
                  <label for="tipo" class="col-md-3 control-label">TIPO DOCUMENTO</label>
                  <div class="col-md-4">
                    

                  <label for="tipo" class="col-md-3 control-label">DNI</label>
                   
                    <input type="hidden" value="" name="tipo_documento_text" id="tipo_documento_text"
                      class="form-control">
                  </div>
                  
                </div>
                
                <div class="form-group">
                  <label class="col-md-3 control-label" for="textinput">DOCUMENTO</label>
                  <div class="col-md-4">
                    <input id="dni" name="dni" type="text" placeholder="" class="form-control input-md" maxlength="8"
                      required="" onkeypress="return IsNumber(event)">
                    <input id="id_farmacia" name="id_farmacia" type="hidden" value="{{Auth::user()->id_farmacia}}">
                  </div>
                  <div class="col-md-2">
                    <button class="btn btn-info" type="submit">
                      Buscar
                    </button>
                  </div>
                  <div class="col-md-2">
                    <a href="/apertura_fema" class="form-control btn btn-danger">Cancelar</a>
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
