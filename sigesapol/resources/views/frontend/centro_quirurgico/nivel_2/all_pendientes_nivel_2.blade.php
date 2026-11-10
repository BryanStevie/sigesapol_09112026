@extends('frontend.layouts.master')
@section('renderjs')
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<script src="{{ asset('centro_quirurgico/nivel2/js/listar_pendientes_nivel_2.js') }}"></script>
<script src="{{ asset('centro_quirurgico/nivel2/js/suspender_solicitud_intervencion_nivel_2.js') }}"></script>
<link rel="stylesheet" href="{{ asset('centro_quirurgico/nivel2/css/listar_pendientes_nivel_2.css') }}">
<style>
  .divider {
    border-top: 2px solid #CCCCCC;
    border-radius: 10px;
    margin: 1rem 0px 2rem 0px;
  }
  table {
    font-size: 12px;
    width: 100%;
  }
  table th,
  table td {
    text-align: center !important;
    vertical-align: middle !important;
  }

</style>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">LISTA DE INTERVENCIONES QUIRURGICAS</h3>
    </div>
    <div class="panel-body">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="cqx_intervencio_id" id="cqx_intervencio_id">
      <div class="row">
        <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2 form-group">
          <label class="form-control-sm">Fec. Desde</label>
          <input class="form-control input-sm datepicker" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde" maxlength="10">
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2 form-group">
          <label class="form-control-sm">Fec. Hasta</label>
          <input class="form-control input-sm datepicker" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Desde" maxlength="10">
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2 form-group">
          <label class="form-control-sm">N° Doc Paciente</label>
          <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="12">
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-6 col-xl-6 form-group">
          <label class="form-control-sm">Apellidos y Nombres</label>
          <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="100">
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2 form-group">
          <label class="form-control-sm">Tipo de Atencion</label>
          <select id="id_tipo_atencion" name="id_tipo_atencion" class="form-control input-sm" style="width: 100%">
            <option value="0">Todos</option>
            <option value="1">AMBULATORIO</option>
            <option value="2">EMERGENCIA</option>
            <option value="3">HOSPITALIZACION</option>
          </select>
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2 form-group">
          <label class="form-control-sm">Servicio / Especialidad</label>
          <select id="id_servicio" name="id_servicio" class="form-control input-sm" style="width: 100%">
            <option value="0">Todos</option>
            @foreach($upss as $row)
            <option value="{{$row->id_upss}}">{{$row->nombre}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2 form-group">
          <label>Estado</label>
          <select id="estado" name="estado" class="form-control input-sm" style="width: 100%">
            <option value="1">PENDIENTE</option>
            <option value="3">ATENDIDO</option>
            <option value="4">ALTA</option>
            <option value="5">SUSPENDIDO</option>
          </select>
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2 form-group"></div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2 form-group">
          <label>&nbsp;</label>
          <button id="btnSearch" type="button" class="btn btn-sm btn-block btn-info"><i class="fa fa-search"></i> BUSCAR</button>
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2 form-group">
          <label>&nbsp;</label>
          <button id="btnExport" type="button" class="btn btn-sm btn-block btn-success"><i class="fa fa-file-excel-o"></i> EXPORTAR</button>
        </div>
      </div>
      <div class="divider"></div>
      <table id="tblIntervencionQx" class="table table-bordered" style="width:100%">
        <thead>
          <tr>
            <th  style="width: 5%;">Cod.</th>
            <th  style="width: 5%;">Tipo Atención</th>
            <th  style="width: 10%;">Fecha Admisión</th>
            <th  style="width: 10%;">Fecha Intervención</th>
            <th  style="width: 5%;">N° Doc</th>
            <th  style="width: 15%;">Paciente</th>
            <th  style="width: 10%;">Servicio</th>
            <th  style="width: 5%;">Estado</th>
            <th  style="width: 5%;">Atención ADM</th>
            <th  style="width: 5%;">Atención CQ</th>
            <th  style="width: 5%;">Suspender</th>
            <th  style="width: 5%;">CPMS</th>
            <th  style="width: 5%;">ALTA</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<div id="addComponent"></div>

@include('frontend.centro_quirurgico.components.modal_suspender_nivel_2')
@include('frontend.centro_quirurgico.nivel_2.form_alta_nivel_2')
@include('frontend.centro_quirurgico.nivel_2.form_detalle_alta_nivel_2')

@include('frontend.includes.footer')
@endsection