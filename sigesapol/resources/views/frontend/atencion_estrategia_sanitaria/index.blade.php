@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .form-inline .form-group {
    display: inline-block;
    margin-bottom: 0;
    vertical-align: middle;
}

.form-inline .form-control {
    display: inline-block;
    vertical-align: middle;
}

.form-inline label {
    margin-bottom: 0;
    vertical-align: middle;
}

.datepicker {
    z-index: 1151 !important;
}
</style>
<script src="{{ asset('js/programacion/atencion_estrategia.js') }}"></script>
<script src="{{ asset('js/programacion/diagnostico.js') }}"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection

@section('content')
<div class="container" style="margin-top: 20px;">
    <div class="panel panel-success">
        <div class="panel-heading"><strong>ESTRATEGIAS SANITARIO</strong></div>
        <div class="panel-body">
    
    <form class="form-inline" id="formFiltro">
        {!! csrf_field() !!}
    <div class="row align-items-end">
    <!-- Fecha Desde -->
    <div class="col-md-2 col-sm-6">
        <div class="form-group mb-0">
            <label for="fecha_desde">Fecha Desde</label>
            <input type="text" class="form-control datepicker input-sm w-100" name="fecha_desde" id="fecha_desde" placeholder="dd-mm-aaaa">
        </div>
    </div>

    <!-- Fecha Hasta -->
    <div class="col-md-2 col-sm-6">
        <div class="form-group mb-0">
            <label for="fecha_hasta">Fecha Hasta</label>
            <input type="text" class="form-control datepicker input-sm w-100" name="fecha_hasta" id="fecha_hasta" placeholder="dd-mm-aaaa">
        </div>
    </div>

    <!-- Documento -->
    <div class="col-md-2 col-sm-6">
        <div class="form-group mb-0">
            <label for="documento">Nro. Doc</label>
            <input type="text" class="form-control input-sm w-100" name="documento" id="documento" placeholder="Documento">
        </div>
    </div>

    <!-- Tipo Estrategia -->
    <div class="col-md-2 col-sm-6">
        <div class="form-group mb-0">
            <label for="tipo_estrategia">Tipo</label>
            <select class="form-control input-sm w-100" name="tipo_estrategia" id="tipo_estrategia" >
                <option value="">- Seleccionar -</option>
                <option value="1">Individual</option>
                <option value="2">Masivo</option>
            </select>
        </div>
    </div>

    <!-- Estado -->
    <div class="col-md-2 col-sm-6" style="margin-left:-35px;">
        <div class="form-group mb-0">
            <label for="estado_estrategia">Estado</label>
            <select class="form-control input-sm w-100" name="estado_estrategia" id="estado_estrategia" >
                <option value="">- Seleccionar -</option>
                <option value="1">Protegido</option>
                <option value="2">Vacunado</option>
            </select>
        </div>
    </div>

    <!-- Botones -->
    <div class="col-md-2 col-sm-6"  style="margin-left:-35px;">
        <div class="form-group mb-0" style="margin-right: -165px;">
            <label class="d-block" style="height: 16px; visibility: hidden;">Botones</label>
            <div class="d-flex justify-content-between w-100">
                <button type="button" id="btnBuscar" class="btn btn-dark btn-sm flex-grow-1 mr-1">
                    <i class="fa fa-search"></i> Buscar
                </button>
                <button type="button" id="btnNuevo" class="btn btn-success btn-sm flex-grow-1 ml-1">
                    <i class="fa fa-plus"></i> Nuevo
                </button>
            </div>
        </div>
    </div>
</div>
    </form>
</div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading bg-lightgreen">
            <strong>LISTADO DE ESTRATEGIAS SANITARIAS</strong>
        </div>
        <div class="panel-body" style="padding: 0">
            <table class="table table-bordered table-striped" id="tablaListado" style="font-size: 13px; margin: 0;">
                <thead>
                    <tr>
                        <th style="width: 30px">N</th>
                        <th style="width: 180px">ESTABLECIMIENTO</th>
                        <th>ATENCIÓN</th>
                        <th>PACIENTE</th>
                        <th>REGISTRADOR</th>
                        <th style="width: 40px">Ver</th>
                        <th style="width: 40px">Editar</th>
                        <th style="width: 40px">Anular</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Cuerpo dinámico por JS --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('frontend.atencion_estrategia_sanitaria.modal')

@endsection
