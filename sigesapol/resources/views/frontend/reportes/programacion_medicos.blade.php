@extends('frontend.layouts.master')

@section('rendercss')

@endsection

@section('renderjs')
  <script src="{{asset('dateTimePicker/bootstrap-datetimepicker.js')}}"></script>
  <script>

  $(document).ready(function() {
    $('#ifecha').datepicker({
      format: 'mm/yyyy',
      viewMode: 'years',
      minViewMode: "months",
      language: "es",
      autoclose: true,
      weekStart: 0,
      todayBtn: "linked"
    });
  });
  </script>
@endsection

@section('content')

<div class="container">
  <div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Reportes - Programaci&oacute;n de Personal de Salud</h3>
        </div>
        <div class="panel-body">
          <form class="form-horizontal" method="post" action="{{ route('exportar-excel-programacion_medicos') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              <label class="col-sm-3 control-label">Tipo de Atención</label>
              <div class="col-sm-9">
                <select class="form-control" name="tipoatencion">
                  @foreach ($tipo_atenciones as $item)
                  <option value="{{ $item['nombre'] }}">{{ $item['nombre'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            @if ($flagservicios == 1)
            <div class="form-group">
              <label class="col-sm-3 control-label">Servicio</label>
              <div class="col-sm-9">
                <select class="form-control" name="servicio" id="iservicio">
                  <option value="0">-Todos-</option>
                  <?php foreach ($consultorio as $row) : ?>
                    <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            @else
            <?php
            $servicio_id = '';
            $servicio_nombre = '';
            if (count($consultorio) > 0) {
              $servicio_id = $consultorio[0]->id;
              $servicio_nombre = $consultorio[0]->nombre;
            }
            ?>
            <div class="form-group">
              <label class="col-sm-3 control-label">Servicio</label>
              <div class="col-sm-9">
                <span class="form-control tag_disabled">{{ $servicio_nombre }}</span>
              </div>
            </div>
            <input type="hidden" name="servicio" value="{{ $servicio_id }}">
            @endif
            <div class="form-group">
              <label class="col-sm-3 control-label">Fecha</label>
              <div class="col-sm-3">
                <div class='input-group date'>
                  <input type='text' class="form-control input-sm" name="fecha" id="ifecha" value="<?php echo date('m/Y')?>" require/>
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-success">Generar Documento Excel</button>
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