@extends('frontend.layouts.master')
@section('content')
<script src='../../funciones/function.js'></script> 
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_servicios"><i class="fa fa-table fa-fw"></i> Servicios de Atención </a></li>
                <li class="active"><a href="/create_servicio"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar {{$consultorio->nombre}}</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_servicio') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" class="form-control" name="id"  value="{{$consultorio->id}}"> 
                        <div class="form-group">
                            <label class="col-lg-2 control-label">División</label>
                            <div class="col-lg-10">
                                <select id="consultorio_division" name="consultorio_division" class="form-control" required="">
                                @foreach ($consultorio_divisiones as $item)
                                  <option value="{{ $item->id }}"<?php if ($consultorio->id_consultorio_division == $item->id) echo ' selected'; ?>>{{ $item->codigo_division }} - {{ $item->nombre }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Departamento</label>
                            <div class="col-lg-10">
                                <select id="consultorio_departamento" name="consultorio_departamento" class="form-control" required="">
                                  @foreach ($consultorio_departamentos as $item)
                                    <option value="{{ $item->id }}"<?php if ($consultorio->id_consultorio_departamento == $item->id) echo ' selected'; ?>>{{ $item->codigo_departamento }} - {{ $item->nombre }}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Servicio</label>
                            <div class="col-lg-10">
                                <input <?php if($ff) echo 'readonly'; ?> name="nombre" id="nombre_servicio" onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'nombre_servicio');" type="text" class="form-control" value="{{$consultorio->nombre}}" required="" placeholder="Nombre del Servicio" onkeypress="return solo_letra(event)" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Abreviatura</label>
                            <div class="col-lg-10">
                                <input name="abreviatura" id="abreviatura_servicio" onkeypress="return noEspaciosInicioSoloLetras(event,'abreviatura_servicio');" type="text" class="form-control" value="{{$consultorio->abreviatura}}" required="" placeholder="Abreviatura" onkeypress="return solo_letra(event)">             
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                <select id="tipo_consultorio" name="tipo_consultorio" class="form-control" required="">
                                    <option value="">- Selecionar -</option>
                                    <option value="1" <?php if ($consultorio->tipo==1) echo 'selected'; ?>> Asistencial </option>
                                    <option value="2" <?php if ($consultorio->tipo==2) echo 'selected'; ?>> Administrativo </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" name="flag_intervalo_cita" value="1"<?php 
                                    if ($consultorio->flag_intervalo_cita == 1): 
                                        echo ' checked'; 
                                    endif;
                                    ?>> No aplicar distanciamiento entre citas
                                </label>
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                            <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="flag_quirurgico" value="1" {{intval($consultorio->flag_quirurgico) === 1 ? 'checked' : ''}}> Centro Quirúrgico
                                </label>
                            </div>
                            </div>
                        </div>
                        <input type="hidden" name="ff" id="ff" value="<?php echo $ff ?>">
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
@section('renderjs')
<script>
    $("#tipo_consultorio").select2();
    $("#consultorio_division").select2().val({{ $consultorio->id_consultorio_division }});
    $("#consultorio_departamento").select2().val({{ $consultorio->id_consultorio_departamento }});
    $("#consultorio_division" ).change(function() {
    $("#consultorio_departamento").empty();
    var id = $(this).val();
    $.get('{{ route('api_departamentos_servicio') }}/' + id, function(data) {
      $("#consultorio_departamento").select2().empty();
      $('#consultorio_departamento').append("<option value='' seledted>- Selecionar -</option>");
      $.each(data, function (key, value) {
        $('#consultorio_departamento').append('<option value=' + value.id + '>' + value.codigo_departamento + ' - ' + value.nombre + '</option>');
      });
      $("#consultorio_departamento").select2().val('');
    });
  });
</script>
@endsection
