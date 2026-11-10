@extends('frontend.layouts.master3')
@section('renderjs')
<script src="{{ asset('centro_quirurgico/nivel2/js/ver_detalle_cq_nivel_2.js') }}"></script>
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
@endsection
@section('styles')
<link rel="stylesheet" href="{{ asset('centro_quirurgico/nivel2/css/ver_detalle_cq_nivel_2.css') }}">
@endsection
@section('content')
<div class="container" style="width: 95%">
  <div class="panel panel-primary filterable">
    <div class="panel-heading" style="text-align: left">INTERVENCIONES QUIRURGICAS: PACIENTE</div>
      <div class="panel-body">
        <form>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="id_intervencion" id="id_intervencion" value="{{ $intervencion->cqx_intervencion_id }}">
          <input type="hidden" name="cqx_atencion_id" id="cqx_atencion_id" value="{{ $intervencion->cqx_atencion_id }}">
          <input type="hidden" name="prestacion_id" id="prestacion_id" value="{{ $intervencion->prestacion_id }}">
          <input type="hidden" name="asegurado_sexo_abrev" id="asegurado_sexo_abrev">
          <input type="hidden" name="establecimiento_nivel" id="establecimiento_nivel">
          <input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion">
          <input type="hidden" name="codigo_siteds" id="codigo_siteds">
          <input type="hidden" name="id_asegurado" id="id_asegurado">
          <input type="hidden" name="asegurado_nro_doc_ident" id="asegurado_nro_doc_ident" value="{{ $intervencion->asegurado_nro_doc_ident }}">
          <input type="hidden" name="id_establecimiento" id="id_establecimiento">
          <input type="hidden" name="id_medico" id="id_medico">
          <input type="hidden" name="argumentos" id="argumentos">
          <input type="hidden" name="doc_firmar" id="doc_firmar">
          <input type="hidden" name="opt_firma" id="opt_firma">
          <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
          <input type="hidden" name="user_dni" id="user_dni" value="{{ $user->dni }}">
          <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center">DATOS ATENCIÓN</div>
            <div class="panel-body">
              <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">TIPO DE ATENCIÓN</label>
                  <p class="field-value">{{ $intervencion->tipo_atencion }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">SERVICIO</label>
                  <p class="field-value">{{ $intervencion->consultorio }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">FECHA DE ATENCIÓN</label>
                  <p class="field-value">{{ $intervencion->fecha_atencion_intervencion }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">HORA DE ATENCIÓN</label>
                  <p class="field-value">{{ $intervencion->hora_atencion_intervencion }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">CODIGO SITEDS</label>
                  <p class="field-value">{{ $intervencion->atencion_codigo_siteds }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">CODIGO UPSS</label>
                  <p class="field-value">{{ $intervencion->upss_codigo }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">DESCRIPCIÓN UPSS</label>
                  <p class="field-value">{{ $intervencion->descripcion_upss }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">NOMBRE DE LA IPRESS</label>
                  <p class="field-value">{{ $intervencion->establecimiento }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center">INFORMACION DEL PACIENTE</div>
            <div class="panel-body">
              <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">DOCUMENTO DE IDENTIDAD</label>
                  <p class="field-value">{{ $intervencion->asegurado_nro_doc_ident }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">APELLIDO PATERNO</label>
                  <p class="field-value">{{ $intervencion->asegurado_paterno }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">APELLIDOS MATERNO</label>
                  <p class="field-value">{{ $intervencion->asegurado_materno }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">NOMBRES</label>
                  <p class="field-value">{{ $intervencion->asegurado_nombre }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">N° HC</label>
                  <p class="field-value">{{ $intervencion->nro_historia }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">PARENTESCO</label>
                  <p class="field-value">{{ $intervencion->tipo_beneficiario }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">FEC. NACIMIENTO</label>
                  <p class="field-value">{{ $intervencion->asegurado_fecha_nac }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">CIP</label>
                  <p class="field-value">{{ $intervencion->asegurado_cip }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-1 col-xl-1 form-group">
                  <label class="field-label">GRADO</label>
                  <p class="field-value">{{ $intervencion->asegurado_grado }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">SITUACIÓN</label>
                  <p class="field-value">{{ "-" }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-1 col-xl-1 form-group">
                  <label class="field-label">SEXO</label>
                  <p class="field-value">{{ $intervencion->asegurado_sexo }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-1 col-xl-1 form-group">
                  <label class="field-label">EDAD</label>
                  <p class="field-value">{{ $intervencion->asegurado_edad }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center">INFORMACION DEL RESPONSABLE DE LA ATENCION</div>
            <div class="panel-body">
              <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                  <p class="field-value">{{ $intervencion->medico_tipo_documento }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-9 col-xl-9 form-group">
                  <label class="field-label">N° DOCUMENTO DE IDENTIDAD</label>
                  <p class="field-value">{{ $intervencion->medico_dni }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">APELLIDO PATERNO</label>
                  <p class="field-value">{{ $intervencion->medico_paterno }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">APELLIDO MATERNO</label>
                  <p class="field-value">{{ $intervencion->medico_materno }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
                  <label class="field-label">NOMBRES</label>
                  <p class="field-value">{{ $intervencion->medico_nombre }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">PROFESION</label>
                  <p class="field-value">{{ $intervencion->medico_profesion }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">N° COLEGIATURA</label>
                  <p class="field-value">{{ $intervencion->medico_colegiatura }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">N° RNE</label>
                  <p class="field-value">{{ $intervencion->medico_rne }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label class="field-label">ESPECIALIDAD</label>
                  <p class="field-value">{{ $intervencion->medico_especializacion }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" style="background: #d4d4d4">
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tabDiagnosticos" aria-controls="tabDiagnosticos" role="tab" data-toggle="tab">DIAGNOSTICOS</a></li>
                <li role="presentation"><a href="#tabProcedimientos" aria-controls="tabProcedimientos" role="tab" data-toggle="tab">PROCEDIMIENTOS</a></li>
              </ul>
            </div>
            <div class="panel-body">
              <div>
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="tabDiagnosticos">
                    <div class="panel panel-primary">
                      <div class="panel-heading" style="text-align: center">DIAGNOSTICOS</div>
                      <div class="panel-body">
                        <table class="table table-condensed" class="table-custom">
                          <thead>
                            <tr>
                              <th class="table-cell" style="width: 10%">COD</th>
                              <th class="table-cell" style="width: 70%">DESCRIPCIÓN</th>
                              <th class="table-cell" style="width: 20%">TIPO</th>
                            </tr>
                          </thead>
                          @if (isset($diagnosticos) && count($diagnosticos) > 0)
                            @foreach ($diagnosticos as $diagnostico)
                              <tr>
                                <td class="table-cell">{{ $diagnostico->diagnostico_codigo }}</td>
                                <td class="table-cell">{{ $diagnostico->diagnostico_nombre }}</td>
                                <td class="table-cell">{{ $diagnostico->tipo_diagnostico_nombre }}</td>
                              </tr>
                            @endforeach
                          @endif
                        </table>
                      </div>
                    </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="tabProcedimientos">
                    <div class="panel panel-primary">
                      <div class="panel-heading" style="text-align: center">PROCEDIMIENTOS</div>
                      <div class="panel-body">
                        <table class="table table-condensed">
                          <thead>
                            <tr>
                              <th class="table-cell" style="width: 10%">COD</th>
                              <th class="table-cell" style="width: 35%">DESCRIPCIÓN</th>
                              <th class="table-cell" style="width: 10%">CANTIDAD</th>
                              <th class="table-cell" style="width: 10%">TIEMPO</th>
                              <th class="table-cell" style="width: 35%">DIAGNOSTICO</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (isset($procedimientos) && count($procedimientos) > 0)
                              @foreach ($procedimientos as $procedimiento)
                                <tr>
                                  <td class="table-cell">{{ $procedimiento->procedimiento_codigo }}</td>
                                  <td class="table-cell">{{ $procedimiento->procedimiento_descripcion }}</td>
                                  <td class="table-cell">{{ $procedimiento->cantidad }}</td>
                                  <td class="table-cell">{{ $procedimiento->tiempo }}</td>
                                  <td class="table-cell">{{ $procedimiento->diagnostico }}</td>
                                </tr>
                              @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" style="background: #d4d4d4">
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tabCirujanos" aria-controls="tabCirujanos" role="tab" data-toggle="tab">CIRUJANOS</a></li>
                <li role="presentation"><a href="#tabAnestesiologos" aria-controls="tabAnestesiologos" role="tab" data-toggle="tab">ANESTESIOLOGOS </a></li>
                <li role="presentation"><a href="#tabEnfermerasInstrumentista" aria-controls="tabEnfermerasInstrumentista" role="tab" data-toggle="tab">ENFERMERAS INSTRUMENTISTA</a></li>
              </ul>
            </div>
            <div class="panel-body">
              <div>
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="tabCirujanos">
                    <div class="panel panel-primary">
                      <div class="panel-body">
                        <table class="table table-condensed">
                          <thead>
                            <tr>
                              <th class="table-cell" style="width: 60%">NOMBRES Y APELLIDOS</th>
                              <th class="table-cell" style="width: 20%">PROFESIÓN</th>
                              <th class="table-cell" style="width: 10%">CPM</th>
                              <th class="table-cell" style="width: 10%">CARGO</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (isset($profesionales['cirujanos']) && count($profesionales['cirujanos']) > 0)
                              @foreach ($profesionales['cirujanos'] as $cirujano)
                                <tr>
                                  <td class="table-cell">{{ $cirujano->medico_nombre }} {{ $cirujano->medico_paterno }} {{ $cirujano->medico_materno }}</td>
                                  <td class="table-cell">{{ $cirujano->profesional }}</td>
                                  <td class="table-cell">{{ $cirujano->colegiatura }}</td>
                                  <td class="table-cell">{{ $cirujano->cqx_tipo_professional }}</td>
                                </tr>
                              @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="tabAnestesiologos">
                    <div class="panel panel-primary">
                      <div class="panel-body">
                        <table class="table table-condensed">
                          <thead>
                            <tr>
                              <th class="table-cell" style="width: 60%">NOMBRES Y APELLIDOS</th>
                              <th class="table-cell" style="width: 20%">PROFESIÓN</th>
                              <th class="table-cell" style="width: 10%">CPM</th>
                              <th class="table-cell" style="width: 10%">CARGO</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (isset($profesionales['anestesiologos']) && count($profesionales['anestesiologos']) > 0)
                              @foreach ($profesionales['anestesiologos'] as $anestesiologo)
                                <tr>
                                  <td class="table-cell">{{ $anestesiologo->medico_nombre }} {{ $anestesiologo->medico_paterno }} {{ $anestesiologo->medico_materno }}</td>
                                  <td class="table-cell">{{ $anestesiologo->profesional }}</td>
                                  <td class="table-cell">{{ $anestesiologo->colegiatura }}</td>
                                  <td class="table-cell">{{ $anestesiologo->cqx_tipo_professional }}</td>
                                </tr>
                              @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="tabEnfermerasInstrumentista">
                    <div class="panel panel-primary">
                      <div class="panel-body">
                        <table class="table table-condensed">
                          <thead>
                            <tr>
                              <th class="table-cell" style="width: 60%">NOMBRES Y APELLIDOS</th>
                              <th class="table-cell" style="width: 20%">PROFESIÓN</th>
                              <th class="table-cell" style="width: 10%">CPM</th>
                              <th class="table-cell" style="width: 10%">CARGO</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (isset($profesionales['enfermeria']) && count($profesionales['enfermeria']) > 0)
                              @foreach ($profesionales['enfermeria'] as $enfermera)
                                <tr>
                                  <td class="table-cell">{{ $enfermera->medico_nombre }} {{ $enfermera->medico_paterno }} {{ $enfermera->medico_materno }}</td>
                                  <td class="table-cell">{{ $enfermera->profesional }}</td>
                                  <td class="table-cell">{{ $enfermera->colegiatura }}</td>
                                  <td class="table-cell">{{ $enfermera->cqx_tipo_professional }}</td>
                                </tr>
                              @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center">INFORMACION DE LA INTERVENCION QUIRURGICA</div>
            <div class="panel-body">
              <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">TIPO DE CIRUGIA</label>
                  <p class="field-value">{{ intval($intervencion->id_clasificacion_cirugia) === 1 ? "CIRUGIA MENOR" : "CIRUGIA MAYOR" }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">TIPO DE ANASTESIA</label>
                  <p class="field-value">{{ $intervencion->tipo_anestesia }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">Hr. Programadas</label>
                  <p class="field-value">{{ $intervencion->horas_programadas }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">Hra. Incio Intervencion Qx.</label>
                  <p class="field-value">{{ $intervencion->hora_inicio_intervencion }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">Hra. Fin Intervencion Qx.</label>
                  <p class="field-value">{{ $intervencion->hora_fin_intervencion }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">Hrs. Efectivas</label>
                  <p class="field-value">{{ $intervencion->horas_efectivas }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">Hra. Incio Acto Qx.</label>
                  <p class="field-value">{{ $intervencion->hora_inicio_acto_qx }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">Hra. Fin Acto Qx.</label>
                  <p class="field-value">{{ $intervencion->hora_fin_acto_qx }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">Hrs. Acto Qx.</label>
                  <p class="field-value">{{ $intervencion->horas_acto_qx }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">COMPLICACION DE LA INTERVENCION</label>
                  <p class="field-value">{{ $intervencion->tiene_complicacion_medica ? "SIN COMPLICACIONES" : "COMPLICADO" }}</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                  <label class="field-label">TIPO DE PROGRAMACION</label>
                  <p class="field-value">{{ $intervencion->tipo_programacion }}</p>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="panel-footer" style="text-align: right; padding: 20px 15px 30px 0px">
        <a class="btn btn-danger" style="width: 20%" href="/cq_lista_pendiente"><i class="fa fa-chevron-left"></i> VOLVER LISTADO</a>
        @if (isset($intervencion->cqx_intervenciones_flag_firma_atencion_qx) && intval($intervencion->cqx_intervenciones_flag_firma_atencion_qx) === 1)
          <a 
            type="button" 
            class="btn btn-primary" 
            style="width: 20%" 
            id="btnShowPdfFirmadoCq"
            href="/{{ \Carbon\Carbon::now()->format('Y/m/d') }}/{{ $intervencion->cqx_intervenciones_ruta_firma_atencion_qx }}" 
            target="__blank"
          ><i class="fa fa-file-pdf-o"></i> PDF FIRMADO</a>
        @else
          <button type="button" class="btn btn-primary" style="width: 20%" id="btnFirmarCq"><i class="fa fa-pencil-square-o"></i> FIRMAR</button>
        @endif
      </div>
    </div>
  </div>
</div>
<div id="addComponent"></div>
@include('frontend.includes.footer')
@endsection