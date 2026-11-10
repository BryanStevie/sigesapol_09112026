<?php ini_set('memory_limit', '-1'); set_time_limit(0); ?>
@extends('frontend.layouts.master3')
  <link rel="stylesheet" href="{{ asset('laboratorio/nivel2/css/verDetalleAtencion.css') }}">
@section('renderjs')
    <script src="{{ asset('laboratorio/nivel2/js/verDetalleAtencion.js') }}"></script>
@endsection
@section('content')
    <div class="container" style="width:95%">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="row">
                    <div class="panel panel-primary filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">MÓDULO DE EXAMENES DE LABORATORIO</h3>
                        </div>
                        <div style=" font-size: 11px" class="panel-body">
                          <input type="hidden" name="id_laboratorio" id="id_laboratorio" value="{{$id_laboratorio}}">
                          <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$id_prestacion}}">
                          <input type="hidden" name="fecha_registro_toma_muestra" id="fecha_registro_toma_muestra">
                          <input type="hidden" name="id_asegurado" id="id_asegurado">

                          <div class="row" style="padding-bottom: 1rem;">
                            <div class="col-md-4 text-center">
                              <img src="{{ asset('images/logo_sanidad.png') }}" width="50" height="auto">
                            </div>
                            <div class="col-md-4" style="text-align: center">
                              <label id="establecimiento"></label><br>
                              <label style="font-size: 24px">ANALISIS DE LABORATORIO</label><br>
                            </div>
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('images/logo_sanidad.png') }}" width="50" height="auto">
                            </div>
                          </div>
                          
                          <div class="panel panel-success">
                            <div class="panel-heading">
                              <h3 class="panel-title" style="text-align: center">Datos Generales</h3>
                            </div>
                            <div class="panel-body">
                              <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="tipo_atencion">Tipo de Atención</label>
                                  <input type="text" class="form-control input-sm" name="tipo_atencion" id="tipo_atencion" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="consultorio_procedencia">Consultorio de Procedencia</label>
                                  <input type="text" class="form-control input-sm" name="consultorio_procedencia" id="consultorio_procedencia" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="fecha_orden">Fecha de Orden</label>
                                  <input type="text" class="form-control input-sm" name="fecha_orden" id="fecha_orden" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label label-help" for="cod_siteds_susalud">COD. SITEDS SUSALUD</label>
                                  <input type="text" style="border: 1px solid #dc3545;" class="form-control input-sm" name="cod_siteds_susalud" id="cod_siteds_susalud" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
                                  <label class="control-label" for="procedencia">Procedencia</label>
                                  <input type="text" class="form-control input-sm" name="procedencia" id="procedencia" readonly>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="panel panel-success">
                            <div class="panel-heading">
                              <h3 class="panel-title" style="text-align: center">ESPECIALISTA</h3>
                            </div>
                            <div class="panel-body">
                              <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="especialista_numero_documento">Nro. Doc</label>
                                  <input type="text" class="form-control input-sm" name="especialista_numero_documento" id="especialista_numero_documento" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
                                  <label class="control-label" for="especialista_apellido_nombres">Apellidos y Nombres</label>
                                  <input type="text" class="form-control input-sm" name="especialista_apellido_nombres" id="especialista_apellido_nombres" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="especialista_profesion">Profesión</label>
                                  <input type="text" class="form-control input-sm" name="profesespecialista_profesionion" id="especialista_profesion" readonly>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="panel panel-success">
                            <div class="panel-heading">
                              <h3 class="panel-title" style="text-align: center">PACIENTE</h3>
                            </div>
                            <div class="panel-body">
                              <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="paciente_numero_documento">Nro. Doc</label>
                                  <input type="text" class="form-control input-sm" name="paciente_numero_documento" id="paciente_numero_documento" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
                                  <label class="control-label" for="paciente_apellido_nombres">Apellidos y Nombres</label>
                                  <input type="text" class="form-control input-sm" name="paciente_apellido_nombres" id="paciente_apellido_nombres" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="paciente_tipo_beneficiario">Tipo Beneficiario</label>
                                  <input type="text" class="form-control input-sm" name="paciente_tipo_beneficiario" id="paciente_tipo_beneficiario" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="paciente_numero_hc">Nro HC</label>
                                  <input type="text" class="form-control input-sm" name="paciente_numero_hc" id="paciente_numero_hc" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="paciente_sexo">Sexo</label>
                                  <input type="text" class="form-control input-sm" name="paciente_sexo" id="paciente_sexo" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="paciente_edad">Edad</label>
                                  <input type="text" class="form-control input-sm" name="paciente_edad" id="paciente_edad" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                  <label class="control-label" for="paciente_referido">Referido</label>
                                  <input type="text" class="form-control input-sm" name="paciente_referido" id="paciente_referido" readonly>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="panel panel-success">
                            <div class="panel-heading">
                              <h3 class="panel-title" style="text-align: center">DIAGNOSTICO</h3>
                            </div>
                            <div class="panel-body">
                              <table id="tbDiagnosticos" class="table table-striped" style="font-size: 11px; width: 100%">
                                <thead>
                                  <tr>
                                    <th style="width: 80%">Código y Nombre de DX</th>
                                    <th style="width: 20%">Tipo de DX</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                          </div>

                          <div class="panel panel-success">
                            <div class="panel-heading">
                              <h3 class="panel-title" style="text-align: center">ANALISIS CLINICO</h3>
                            </div>
                            <div class="panel-body">
                              <table id="tbExamenes" class="table table-striped" style="font-size: 11px; width: 100%">
                                <thead>
                                  <tr>
                                    <th style="width: 30%">Examen</th>
                                    <th style="width: 15%">Observación</th>
                                    <th style="width: 5%">Cant</th>
                                    <th style="width: 20%">Diagnóstico</th>
                                    <th style="width: 10%">Reembolso</th>
                                    <th style="width: 20%">Resultado</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                              <hr style="margin: 5px 0px 10px 0px">
                              <span id="cantidadExamenes" style="font-weight: 700"></span>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xl-8 form-group">
                              <label class="control-label" for="observacion_toma_muestra">Observación</label>
                              <input type="text" class="form-control input-sm" name="observacion_toma_muestra" id="observacion_toma_muestra">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                              <div class="row container-detail">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-10 col-xl-10">
                                  <span class="label_nro_examenes">Nro. Exámenes de laboratorio por atención: </span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                  <button type="button" class="btn btn-success btn-xs" id="btnShowDetail"><i class="fa fa-list" aria-hidden="true"></i> Detalle</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="row container-buttons" style="margin: 20px 0px">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <a href="/all_laboratorio_prescritos_by_ipress" class="btn btn-danger btn-sm button"><i class="fa fa-chevron-left" aria-hidden="true"></i> VOLVER</a>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <button type="button" class="btn btn-success btn-sm button btn-sm" id="btnEditarOrden"><i class="fa fa-floppy-o" aria-hidden="true"></i> GUARDAR</button>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- @include('frontend.laboratorios.modal_solicitud_procedimiento') --}}


  <div class="modal fade" id="modalDetalle" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="width: 70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title" style="font-size: 2rem">Historial</h5>
        </div>
        <div class="modal-body">
          <table id="tbListDetalle" class="table table-striped" style="width: 100%; font-size: 11px;">
            <thead>
              <tr>
                  <th>Código de Atención</th>
                  <th>Fecha Orden</th>
                  <th>Tipo de Atención</th>
                  <th>Establecimiento de Referencia</th>
                  <th>Consultorio de Procedencia</th>
                  <th>Médico</th>
                  <th>Detalle Examenes</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

@include('frontend.includes.footer')
@endsection