<?php ini_set('memory_limit', '-1'); set_time_limit(0); ?>
@extends('frontend.layouts.master3')
  <link rel="stylesheet" href="{{ asset('laboratorio/nivel2/css/registrarAtencion.css') }}">
@section('renderjs')
    <script>  
      window.proclaboratorio = {!! json_encode($proclaboratorio) !!}
    </script>
    <script src="{{ asset('laboratorio/nivel2/js/registrarAtencion.js') }}"></script>
@endsection
@section('content')
    <?php $grados = [
        'NINGUNO',
        'ALUMNO',
        'CADETE',
        'ALFÉREZ DE SERVICIO',
        'ALFÉREZ MAESTRO ARMERO',
        'CAPITÁN',
        'CAPITÁN DE SERVICIO',
        'CAPITÁN MAESTRO ARMERO',
        'COMANDANTE',
        'COMANDANTE DE SERVICIO',
        'COMANDANTE MAESTRO ARMERO',
        'CORONEL',
        'CORONEL DE SERVICIO',
        'ESPECIALISTA BRIGADIER',
        'ESPECIALISTA DE PRIMERA',
        'ESPECIALISTA DE SEGUNDA',
        'ESPECIALISTA DE TERCERA',
        'ESPECIALISTA SUPERIOR',
        'ESPECIALISTA TÉCNICO DE 1RA',
        'ESPECIALISTA TÉCNICO DE 2DA',
        'ESPECIALISTA TÉCNICO DE 3RA',
        'GENERAL',
        'GENERAL DE SERVICIO',
        'MAYOR',
        'MAYOR DE SERVICIO',
        'MAYOR MAESTRO ARMERO',
        'SUBOFICIAL BRIGADIER',
        'SUBOFICIAL BRIGADIER DE SERVICIO',
        'SUBOFICIAL DE PRIMERA',
        'SUBOFICIAL DE PRIMERA DE SERVICIO',
        'SUBOFICIAL DE SEGUNDA',
        'SUBOFICIAL DE SEGUNDA DE SERVICIO',
        'SUBOFICIAL DE TERCERA',
        'SUBOFICIAL DE TERCERA DE SERVICIO',
        'SUBOFICIAL SUPERIOR',
        'SUBOFICIAL SUPERIOR DE SERVICIO',
        'SUBOFICIAL TECNICO DE 1RA',
        'SUBOFICIAL TECNICO DE 1RA DE SERVICIO',
        'SUBOFICIAL TECNICO DE 2DA',
        'SUBOFICIAL TECNICO DE 2DA DE SERVICIO',
        'SUBOFICIAL TECNICO DE 3RA',
        'SUBOFICIAL TECNICO DE 3RA DE SERVICIO',
        'TENIENTE',
        'TENIENTE DE SERVICIO',
        'TENIENTE GENERAL',
        'TENIENTE MAESTRO ARMERO',
    ]; ?>

    <div class="container" style="width:95%">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="row">
                    <div class="panel panel-primary filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">MÓDULO DE EXAMENES DE LABORATORIO</h3>
                        </div>
                        <div style=" font-size: 11px" class="panel-body">
                            <form name="frmOrdenLaboratorio" id="frmOrdenLaboratorio" role="form" method="post" action="{{ route('registrar_examen_laboratorio_lns') }}">
                              <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="{{$id_tipo_atencion}}">
                              <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$establecimiento->id}}">
                              <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$prestacion->id}}">
                              <input type="hidden" name="id_laboratorio" id="id_laboratorio" value="{{ $laboratorio->id }}">
                              <input type="hidden" name="id_evaluacion" id="id_evaluacion" value="{{ $laboratorio->id_evaluacion }}">
                              <input type="hidden" name="id_asegurado" id="id_asegurado" value="{{ $prestacion->id_asegurado }}">
                              <input type="hidden" name="id_ipress" id="id_ipress" value="{{ $establecimiento->id }}">

                              <div class="row" style="padding-bottom: 1rem;">
                                <div class="col-md-4 text-center">
                                  <img src="../../images/logo_sanidad.png" width="50" height="auto">
                                </div>
                                <div class="col-md-4" style="text-align: center">
                                  <label>{{$establecimiento->nombre}}</label><br>
                                  <label style="font-size: 24px">ANALISIS DE LABORATORIO</label><br>
                                </div>
                                <div class="col-md-4 text-center">
                                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
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
                                      <label class="control-label" for="tipo_atencion">Consultorio de Procedencia</label>
                                      <input type="text" class="form-control input-sm" name="consultorio_procedencia" id="consultorio_procedencia" readonly>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                      <label class="control-label" for="tipo_atencion">Fecha de Orden</label>
                                      <input type="text" class="form-control input-sm" name="fecha_orden" id="fecha_orden" value="{{$prestacion->fecha_atencion}}" readonly>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                      <label class="control-label label-help" for="tipo_atencion">COD. SITEDS SUSALUD</label>
                                      <input type="text" style="border: 1px solid #dc3545;" class="form-control input-sm" name="cod_siteds_susalud" id="cod_siteds_susalud" value="{{$prestacion->codigo_siteds}}" readonly>
                                    </div>
                                    @if (isset($prestacion->ubicacion) && isset($prestacion->nmro_cama))
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
                                      <label class="control-label" for="tipo_atencion">Procedencia</label>
                                      <input type="text" class="form-control input-sm" name="procedencia" id="procedencia" value="{{$prestacion->ubicacion}} / CAMA: {{$prestacion->nmro_cama}}" readonly>
                                    </div>
                                    @endif
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
                                      <label class="control-label" for="tipo_atencion">Nro. Doc</label>
                                      <input type="text" class="form-control input-sm" name="tipo_atencion" id="tipo_atencion" value="{{$prestacion->dni}}" readonly>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
                                      <label class="control-label" for="tipo_atencion">Apellidos y Nombres</label>
                                      <input type="text" class="form-control input-sm" name="consultorio_procedencia" id="consultorio_procedencia" value="{{$prestacion->medico_paterno}} {{$prestacion->medico_materno}} {{$prestacion->medico_nombre}}" readonly>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                      <label class="control-label" for="tipo_atencion">Profesión</label>
                                      <input type="text" class="form-control input-sm" name="fecha_orden" id="fecha_orden" value="{{$prestacion->profesion}}" readonly>
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
                                      <label class="control-label" for="nro_doc_ident">Nro. Doc</label>
                                      <input type="text" class="form-control input-sm" name="nro_doc_ident" id="nro_doc_ident" value="{{$prestacion->nro_doc_ident}}" readonly>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
                                      <label class="control-label" for="tipo_atencion">Apellidos y Nombres</label>
                                      <input type="text" class="form-control input-sm" name="consultorio_procedencia" id="consultorio_procedencia" value="{{$prestacion->asegurado_paterno}} {{$prestacion->asegurado_materno}} {{$prestacion->asegurado_nombre}}" readonly>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                      <label class="control-label" for="tipo_atencion">Tipo Beneficiario</label>
                                      <input type="text" class="form-control input-sm" name="fecha_orden" id="fecha_orden" value="{{$prestacion->parentesco}}" readonly>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                      <label class="control-label" for="tipo_atencion">Nro HC</label>
                                      <input type="text" class="form-control input-sm" name="fecha_orden" id="fecha_orden" value="{{$prestacion->nro_historia}}" readonly>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                      <label class="control-label" for="tipo_atencion">Sexo</label>
                                      <input type="text" class="form-control input-sm" name="fecha_orden" id="fecha_orden" value="{{$prestacion->sexo === 'M' ? 'MASCULINO' : 'FEMENINO'}}" readonly>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                      <label class="control-label" for="tipo_atencion">Edad</label>
                                      <input type="text" class="form-control input-sm" name="fecha_orden" id="fecha_orden" value="{{$prestacion->edadpaciente}}" readonly>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                      <label class="control-label" for="tipo_atencion">Referido</label>
                                      <input type="text" class="form-control input-sm" name="fecha_orden" id="fecha_orden" value="{{$prestacion->referido}}" readonly>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="panel panel-success">
                                <div class="panel-heading">
                                  <h3 class="panel-title" style="text-align: center">DIAGNOSTICO</h3>
                                </div>
                                <div class="panel-body">
                                  <table class="table table-striped" style="font-size: 11px; width: 100%">
                                    <thead>
                                      <tr>
                                        <th style="width: 80%">Código y Nombre de DX</th>
                                        <th style="width: 20%">Tipo de DX</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($diagnostico as $diag)
                                        <tr>
                                          <td>{{ $diag->codigo }} - {{ $diag->nombre }}</td>
                                          <td>{{ $diag->descripcion }}</td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                              </div>

                              <div class="panel panel-success">
                                <div class="panel-heading">
                                  <h3 class="panel-title" style="text-align: center">ANALISIS CLINICO</h3>
                                </div>
                                <div class="panel-body">
                                  <table id="tbAnalisisClinico" class="table table-striped" style="font-size: 11px; width: 100%">
                                    <thead>
                                      <tr>
                                        <th style="width: 30%">Examen</th>
                                        <th style="width: 20%">Observación</th>
                                        <th style="width: 5%">Cant</th>
                                        <th style="width: 20%">Diagnóstico</th>
                                        <th style="width: 7%">Reembolso</th>
                                        <th style="width: 18%">Resultado</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php $i = 0; ?>
                                      @foreach($proclaboratorio as $examen)
                                        <tr>
                                          <td style="display: none;">
                                            <input type="hidden" name="ac_idPrestacionProcedimiento_{{$i}}" value="{{ $examen->id_prestacion_procedimiento }}">
                                            <input type="hidden" name="ac_idProcedimiento_{{$i}}" value="{{ $examen->id_procedimiento }}">
                                            <input type="hidden" name="ac_diagnostico_{{$i}}" id="ac_diagnostico_{{$i}}" value="{{ $examen->id_diagnostico }}">
                                          </td>
                                          <td>
                                            <div class="checkbox">
                                              <label>
                                                <input type="checkbox" style="margin-top: -0.9px" name="ac_examenProcesado_{{$i}}" id="ac_examen_procesado_{{$i}}" data-row_id="{{$i}}"> {{$examen->codigo}} - {{$examen->nombre}}
                                              </label>
                                            </div>
                                          </td>
                                          <td>
                                            <input type="text" class="form-control input-sm" name="ac_observacion_{{$i}}" id="ac_observacion_{{$i}}" disabled maxlength="255">
                                          </td>
                                          <td>
                                            <input type="text" class="form-control input-sm ac-cantidad" name="ac_cantidad_{{$i}}" id="ac_cantidad_{{$i}}" value="1" disabled>
                                          </td>
                                          <td>
                                            <input type="text" class="form-control input-sm ac-cantidad" name="ac_diagnostico_nombre_{{$i}}" id="ac_diagnostico_nombre_{{$i}}" value="{{ $examen->diagnostico }}" disabled>
                                          </td>
                                          <td>
                                            <select class="form-control input-sm" id="ac_reembolso_{{$i}}" name="ac_reembolso_{{$i}}" disabled>
                                              <option value="1">SI</option>
                                              <option value="0" selected>NO</option>
                                            </select>
                                          </td>
                                          <td>
                                            <div style="display: flex; flex-direction: row; justify-content: space-between">
                                              <div id="file_result_{{$i}}" style="width: 80%; display: flex; flex-direction: row; gap: 7px; flex-wrap: wrap;"></div>
                                              <div>
                                                <button 
                                                  type="button"
                                                  class="btn btn-success btn-sm btnSubirArchivo"
                                                  id="btnSubirArchivo_{{$i}}"
                                                  data-toggle="tooltip"
                                                  data-placement="bottom"
                                                  title="<strong>Subir archivo de resultados</strong><br>Solo se aceptan PDF"
                                                  data-html="true"
                                                  data-target="#ac_file_resultado_{{$i}}"
                                                  disabled
                                                >
                                                  <i class="fa fa-plus-square" aria-hidden="true"></i>
                                                </button>
                                                <input 
                                                  type="file" 
                                                  id="ac_file_resultado_{{$i}}"
                                                  name="ac_file_resultado_{{$i}}"
                                                  accept=".pdf"
                                                  multiple
                                                  class="archivo-resultado"
                                                  style="display: none;"
                                                >
                                              </div>
                                            </div>
                                          </td>
                                          <td style="display: none;">{{ $i++ }}</td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                  <span>Cantidad de Análisis Clínico: {{ count($proclaboratorio) }}</span>
                                </div>
                              </div>

                              <div>
                                <div class="row">
                                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xl-8 form-group">
                                    <label class="control-label" for="observacion">Observación</label>
                                    <input type="text" class="form-control input-sm" name="observacion" id="observacion">
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
                                <div class="row container-buttons">
                                  <div>
                                    <a href="/all_laboratorio_prescritos_by_ipress" class="btn btn-danger btn-sm button" id="btnCancel">CANCELAR</a>
                                  </div>
                                  <div>
                                    <button type="button" class="btn btn-success btn-sm button" id="btnSave">GUARDAR</button>
                                  </div>
                                </div>
                              </div>
                            </form>
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
          <h5 class="modal-title" style="font-size: 2rem">Atenciones Médicas</h5>
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