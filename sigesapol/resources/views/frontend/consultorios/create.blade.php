@extends('frontend.layouts.master')
@section('renderjs')
<script src='../../funciones/function.js'></script> 
<script>
  $("#tipo_consultorio").select2();
  $("#consultorio_division").select2();
  $("#consultorio_departamento").select2();
  $("#consultorio_division" ).change(function() {
    $("#consultorio_departamento").empty();
    var id = $(this).val();
    $.get('{{ route('api_departamentos_servicio') }}/' + id, function(data) {
      $("#consultorio_departamento").select2().val('');
      $('#consultorio_departamento').append("<option value=''>- Selecionar -</option>");
      $.each(data, function (key, value) {
        $('#consultorio_departamento').append('<option value=' + value.id + '>' + value.codigo_departamento + ' - ' + value.nombre + '</option>');
      });
      $("#consultorio_departamento").select2().val('');
    });
  });
</script>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="/all_servicios"><i class="fa fa-table fa-fw"></i> Servicios de Atención</a></li>
        <li class="active"><a href="/create_servicio"><i class="fa fa-plus"></i> Nuevo </a></li>
      </ul>
    </div>
    <div class="col-md-9 well">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <div class="panel-title">Crear Nuevo Servicio de Atención</div>
        </div>
        <div style="padding-top:30px" class="panel-body">
          <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
          <form id="frmfarmacia" class="form-horizontal" role="form" method="post" action="{{ route('store_consultorio') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              <label class="col-lg-2 control-label">División</label>
              <div class="col-lg-10">
                <select id="consultorio_division" name="consultorio_division" class="form-control" required="">
                  <option value="">- Selecionar -</option>
                  @foreach ($consultorio_divisiones as $item)
                    <option value="{{ $item->id }}">{{ $item->codigo_division }} - {{ $item->nombre }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">Departamento</label>
              <div class="col-lg-10">
                <select id="consultorio_departamento" name="consultorio_departamento" class="form-control" required="">
                  <option value="">- Selecionar -</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">Servicio</label>
              <div class="col-lg-10">
                <input name="nombre" id="nombre_servicio" onkeypress="return noEspaciosInicioSoloLetrasOtros(event,'nombre_servicio');" type="text" class="form-control" required="" placeholder="Nombre del Servicio"  autocomplete="off">
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">Abreviatura</label>
              <div class="col-lg-10">
                <input name="abreviatura" id="abreviatura_servicio" onkeypress="return noEspaciosInicioSoloLetras(event,'abreviatura_servicio');" type="text" class="form-control" required="" placeholder="Abreviatura"  autocomplete="off">
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">Tipo</label>
              <div class="col-lg-10">
                <select id="tipo_consultorio" name="tipo_consultorio" class="form-control" required="">
                  <option value="">- Selecionar -</option>
                  <option value="1"> Asistencial </option>
                  <option value="2"> Administrativo </option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-lg-offset-2 col-lg-10">
                <div class="checkbox">
                    <label>
                    <input type="checkbox" name="flag_intervalo_cita" value="1"> No aplicar distanciamiento entre citas
                    </label>
                </div>
              </div>
            </div>
            {{-- 
              <div style="margin-bottom: 25px">
                <select id="nombre" name="nombre" class="form-control" tabindex="11"  style="margin-bottom: 10px" disabled="true" required="">
                  <option value=""  selected style="color: #888888">Selecionar nombre de la Farmacia</option>      
                </select>
              </div>
            --}}
            <div style="margin-top:10px" class="form-group">
              <!-- Button -->
              <div class="col-sm-12 controls">
                <input type="submit" value="Guardar" class="btn btn-success">
                <a href="/all_servicios" class="btn btn-danger">Cancelar</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection