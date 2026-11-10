<?php ini_set('memory_limit', '-1');
set_time_limit(0); ?>
@extends('frontend.layouts.master3')
<link rel="stylesheet" href="{{ asset('enfermeria/consulta_externa/css/registrarNotaIngreso.css') }}">
@section('renderjs')
    <script>
        window.medico = {!! json_encode($medico) !!}
    </script>
    <script src="{{ asset('enfermeria/consulta_externa/js/registrarNotaIngreso.js') }}"></script>
    <script src="{{ asset('enfermeria/consulta_externa/js/handlerHistorial.js') }}"></script>
@endsection
@include('frontend.hospitalizacion.enfermeria.nota_ingreso.function_nota_ingreso.parametros.listar_parametros')
<style>
    .ui-autocomplete-loading {
        background: white url('{{ asset('images/loader.gif') }}') no-repeat right center !important; 
    }
    .ui-autocomplete {
        font-family: 'Arial', sans-serif; 
        font-size: 14px;
        width: 70%;
    }
    .ui-menu-item {
        font-family: 'Arial', sans-serif;
        font-size: 14px;
    }
    .tooltip-inner {
        max-width: 300px;
        white-space: nowrap;
    }
</style>
@section('content')
<div class="container" style="width:95%">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
              <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                  <ul class="nav nav-tabs" id="tabs">
                    <li class="active"><a href="#tabNuevaAtencion" data-toggle="tab" style="font-size:12px">EDITAR ATENCION</a></li>
                    <li class="disabled-tab"><a href="#tabHistorialAtenciones" data-toggle="tab" style="font-size:12px">HIST. ATENCIONES</a></li>
                    <li class="disabled-tab"><a href="#tabHistorialLaboratorio" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE LABORATORIO</a></li>
                    <li class="disabled-tab"><a href="#tabHistorialRadiologia" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE RADIOLOGIA</a></li>
                    <li class="disabled-tab"><a href="#tabHistorialnterconsulta" data-toggle="tab" style="font-size:12px">HIST. INTERCONSULTA</a></li>
                    <li class="disabled-tab"><a href="#tabHistorialReferencia" data-toggle="tab" style="font-size:12px">HIST. REFERENCIAS / CONTRAREFERENCIAS</a></li>
                    <li class="disabled-tab"><a href="#tabHistorialEnfermeria" data-toggle="tab" style="font-size:12px">HIST. ENFERMERIA</a></li>
                  </ul>
                </div>
                <div class="panel-body">
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="tabNuevaAtencion">
                      <div class="panel panel-primary filterable">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <h3 class="panel-title" style="padding-top: 5px;">NOTA DE INGRESO DE ENFERMERÍA - CONSULTA EXTERNA</h3> 
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                          <form method="post" action="{{ route('registrar_nota_ingreso_emergencia')}}" id="frmNotaIngreso" name="frmNotaIngreso" autocomplete="off">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="asegurado_edad" id="asegurado_edad">
                            <input type="hidden" name="id_prestacion" id="id_prestacion">
                            <input type="hidden" name="asegurado_sexo_abreviatura" id="asegurado_sexo_abreviatura">
                            <input type="hidden" name="firmaPrestacion" id="firmaPrestacion">
                            <input type="hidden" name="establecimiento_nivel" id="establecimiento_nivel">
                            <input type="hidden" name="nro_doc_ident_hidden" id="nro_doc_ident_hidden">
                            <input type="hidden" name="asegurado_id" id="asegurado_id">
                            <input type="hidden" name="cita_id" id="cita_id">
                            <fieldset>
                              <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                      <div class="panel-title text-center">
                                          <b>DATOS DE LA ATENCION</b>
                                      </div>
                                  </div>
                                  <div class="panel-body">
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="tipo_atencion">TIPO DE LA ATENCION</label>
                                              <input class="form-control input-sm" name="tipo_atencion" id="tipo_atencion" value="CONSULTA EXTERNA" readonly />
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="id_servicio">SERVICIO</label>
                                              <select class="form-control input-sm" id="id_servicio" name="id_servicio">
                                                  {{-- <option value="">--Seleccionar--</option>
                                                  @foreach($consultorio as $c)
                                                      <option value="{{$c->id}}">{{$c->nombre}}</option>
                                                  @endforeach --}}
                                              </select>
                                          </div> 
                                          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                              <label class="control-label" for="id_establecimiento">IPRESS DONDE SE ATENDIO</label>
                                              <select class="form-control input-sm" id="id_establecimiento" name="id_establecimiento">
                                                  @foreach($establecimientos as $establecimiento)
                                                      <option value="{{$establecimiento->id}}" {{intval($establecimiento->id) === intval(Auth::user()->id_establecimiento) ? "selected" : ""}}>{{$establecimiento->nombre}}</option>
                                                  @endforeach
                                              </select>
                                          </div> 
                                      </div> 
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="nro_historia_clinica">NUMERO DE HISTORIA</label>
                                              <input class="form-control input-sm" name="nro_historia_clinica" id="nro_historia_clinica" readonly>
                                          </div> 
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="fecha_ultima_atencion">FECHA DE ULTIMA ATENCION</label>
                                              <input class="form-control input-sm" name="fecha_ultima_atencion" id="fecha_ultima_atencion" readonly style="background-color: #fff; border-color: #b2b2b2;">
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="id_tipo_doc_ident">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                              <select class="form-control input-sm" id="id_tipo_doc_ident" name="id_tipo_doc_ident">
                                                  <option value="1" selected="">DNI</option>
                                                  <option value="2">CARNET DE EXTRANJERIA</option>
                                                  <option value="3">PASAPORTE</option>
                                                  <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                              </select>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="nro_doc_ident">N ° DE DOCUMENTO DE IDENTIDAD</label>
                                              <div class="input-group">
                                                  <input type="text" id="nro_doc_ident" name="nro_doc_ident" class="form-control input-sm"  maxlength="8" placeholder="00000000">
                                                  <span class="input-group-btn">
                                                      <button 
                                                          type="button" 
                                                          id="btnBuscarAsegurado" 
                                                          class="btn btn-primary btn-sm"
                                                          data-html="true"
                                                          data-toggle="tooltip" 
                                                          data-placement="bottom" 
                                                          title="<div><span>- Seleccionar el servicio</span><br><span>- Ingrese el número de documento</span></div>" disabled>BUSCAR CITA</button>
                                                  </span>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group" id="div_fecha_atencion">
                                            <label class="control-label" for="fecha_atencion_enfermeria">FECHA DE ATENCION</label>
                                            <input class="form-control input-sm" name="fecha_atencion_enfermeria" id="fecha_atencion_enfermeria" readonly style="background-color: #fff; border-color: #b2b2b2;">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group" id="div_fecha_hora">
                                            <label class="control-label" for="hora_atencion_enfermeria">HORA DE ATENCION</label>
                                            <input type="time" class="form-control input-sm" name="hora_atencion_enfermeria" id="hora_atencion_enfermeria"  >
                                        </div>
                                      </div>
                                  </div>
                              </div>

                              <div class="panel panel-info panel-bordered panel-sm" id="formDataPaciente" style="display: none">
                                  <div class="panel-heading">
                                      <div class="panel-title text-center">
                                          <b>INFORMACIÓN DEL PACIENTE</b>
                                      </div>
                                  </div>
                                  <div class="panel-body">
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="asegurado_tipo_doc_identidad">TIPO DE DOC DE IDENTIDAD</label>
                                              <input class="form-control input-sm" name="asegurado_tipo_doc_identidad" id="asegurado_tipo_doc_identidad" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="asegurado_sexo">SEXO</label>
                                              <input class="form-control input-sm" name="asegurado_sexo" id="asegurado_sexo" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="asegurado_paterno">APELLIDO PATERNO</label>
                                              <input class="form-control input-sm" name="asegurado_paterno" id="asegurado_paterno" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="asegurado_materno">APELLIDO MATERNO</label>
                                              <input class="form-control input-sm" name="asegurado_materno" id="asegurado_materno" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="asegurado_nombre">NOMBRES</label>
                                              <input class="form-control input-sm" name="asegurado_nombre" id="asegurado_nombre" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="asegurado_fecha_nacimiento">FECHA DE NACIMIENTO</label>
                                              <input class="form-control input-sm" name="asegurado_fecha_nacimiento" id="asegurado_fecha_nacimiento" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="asegurado_tipo_beneficiario">PARENTESCO</label>
                                              <input class="form-control input-sm" name="asegurado_tipo_beneficiario" id="asegurado_tipo_beneficiario" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="asegurado_grado">GRADO</label>
                                              <input class="form-control input-sm" name="asegurado_grado" id="asegurado_grado" readonly>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                      <div class="panel-title text-center">
                                          <b>INFORMACIÓN DEL RESPONSABLE DE ENFERMERIA</b>
                                      </div>
                                  </div>
                                  <div class="panel-body">
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="tipo_documento_enfermero">TIPO DE DOC DE IDENTIDAD</label>
                                              <input class="form-control input-sm" name="tipo_documento_enfermero" id="tipo_documento_enfermero" value="{{$medico->tipo_documento_nombre}}" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="nro_documento_enfermero">DOCUMENTO DE IDENTIDAD</label>
                                              <input class="form-control input-sm" name="nro_documento_enfermero" id="nro_documento_enfermero" value="{{$medico->dni}}" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="paterno_enfermero">APELLIDO PATERNO</label>
                                              <input class="form-control input-sm" name="paterno_enfermero" id="paterno_enfermero" value="{{$medico->paterno}}" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="materno_enfermero">APELLIDO MATERNO</label>
                                              <input class="form-control input-sm" name="materno_enfermero" id="materno_enfermero" value="{{$medico->materno}}" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="nombre_enfermero">NOMBRES</label>
                                              <input class="form-control input-sm" name="nombre_enfermero" id="nombre_enfermero" value="{{$medico->nombre}}" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="especialidad_enfermero">ESPECIALIDAD</label>
                                              <input class="form-control input-sm" name="especialidad_enfermero" id="especialidad_enfermero" value="{{$medico->especializacion}}" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="profesion_enfermero">PROFESION</label>
                                              <input class="form-control input-sm" name="profesion_enfermero" id="profesion_enfermero" value="{{$medico->profesion}}" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="nro_colegiatura_enfermero">N° COLEGIATURA</label>
                                              <input class="form-control input-sm" name="nro_colegiatura_enfermero" id="nro_colegiatura_enfermero" value="{{$medico->colegiatura}}" readonly>
                                          </div>
                                      </div>          
                                  </div>
                              </div>

                              <div class="panel panel-info panel-bordered panel-sm" id="formDataMedicoAtencion" style="display: none">
                                  <div class="panel-heading">
                                      <div class="panel-title text-center">
                                          <b>INFORMACION DEL RESPONSABLE DE ATENCION</b>
                                      </div>
                                  </div>
                                  <div class="panel-body">
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="medico_tipo_documento">TIPO DE DOC DE IDENTIDAD</label>
                                              <input class="form-control input-sm" name="medico_tipo_documento" id="medico_tipo_documento" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="medico_nro_doc_ident">N° DE DOCUMENTO DE IDENTIDAD</label>
                                              <input class="form-control input-sm" name="medico_nro_doc_ident" id="medico_nro_doc_ident" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="medico_paterno">APELLIDO PATERNO</label>
                                              <input class="form-control input-sm" name="medico_paterno" id="medico_paterno" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="medico_materno">APELLIDO MATERNO</label>
                                              <input class="form-control input-sm" name="medico_materno" id="medico_materno" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="medico_nombre">NOMBRES</label>
                                              <input class="form-control input-sm" name="medico_nombre" id="medico_nombre" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="medico_colegiatura">N° COLEGIATURA</label>
                                              <input class="form-control input-sm" name="medico_colegiatura" id="medico_colegiatura" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="medico_rne">N° DE RNE</label>
                                              <input class="form-control input-sm" name="medico_rne" id="medico_rne" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="medico_profesion">PROFESION</label>
                                              <input class="form-control input-sm" name="medico_profesion" id="medico_profesion" readonly>
                                          </div>
                                          <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                              <label class="control-label" for="medico_especializacion">ESPECIALIDAD</label>
                                              <input class="form-control input-sm" name="medico_especializacion" id="medico_especializacion" readonly>
                                          </div>
                                      </div>          
                                  </div>
                              </div>

                              <div class="panel panel-info panel-bordered panel-sm" id="tableReceta" style="display: none">
                                  <div class="panel-heading">
                                      <div class="panel-title text-center">
                                          <b>INFORMACION DE RECETA</b>
                                      </div>
                                  </div>
                                  <div class="panel-body">
                                      <table id="tableRecetas" class="table table-striped" style="font-size: 11px; width: 100%">
                                          <thead>
                                              <tr>
                                                  <th>N</th>
                                                  <th>PRODUCTO</th>
                                                  <th>UN MED</th>
                                                  <th>CANT</th>
                                                  <th>DIAS</th>
                                                  <th>VIA ADM</th>
                                                  <th>POSOLOGIA</th>
                                              </tr>
                                          </thead>
                                      </table>
                                  </div>
                              </div>

                              <div class="panel with-nav-tabs panel-default" id="sectionTabs" style="display: none">
                                  <div class="panel-heading">
                                      <ul class="nav nav-tabs" role="tablist" id="tabsRegistrarNotaIngreso">
                                          <li role="presentation" class="active"><a href="#tabSignosVitales" aria-controls="tabSignosVitales" role="tab" data-toggle="tab">SIGNOS VITALES</a></li>
                                          <li role="presentation"><a href="#tabAntecedentes" aria-controls="tabAntecedentes" role="tab" data-toggle="tab">ANTECEDENTES</a></li>
                                          <li role="presentation"><a href="#tabCpmsCpt" aria-controls="tabCpmsCpt" role="tab" data-toggle="tab">CPMS/CPT</a></li>
                                          <li role="presentation"><a href="#tabObservaciones" aria-controls="tabObservaciones" role="tab" data-toggle="tab">OBSERVACIONES</a></li>
                                      </ul>
                                  </div>
                                  <div class="panel-body">
                                      <div class="tab-content">
                                          <div role="tabpanel" class="tab-pane active" id="tabSignosVitales">
                                              <div class="row">
                                                  <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label class="control-label">PRESION ARTERIAL</label>
                                                      <div class="input-group">
                                                          <input class="form-control input-sm" name="presion_arterial_1" id="presion_arterial_1" maxlength="10">
                                                          <span class="input-group-addon separate-addon">/</span>
                                                          <input class="form-control input-sm" name="presion_arterial_2" id="presion_arterial_2" maxlength="10">
                                                      </div>
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label class="control-label" for="pulso_frec_cardiaca">PULSO/FREC. CARDIACA</label>
                                                      <input type="text" class="form-control input-sm" name="pulso_frec_cardiaca" id="pulso_frec_cardiaca" maxlength="10">
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label class="control-label" for="frec_respiratorio">FREC. RESPIRATORIA (/MIN)</label>
                                                      <input type="text" class="form-control input-sm" name="frec_respiratorio" id="frec_respiratorio" maxlength="10">
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label class="control-label" for="fi02">FIO2 (%)</label>
                                                      <input type="text" class="form-control input-sm" name="fi02" id="fi02" maxlength="10">
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label class="control-label" for="sa02">SA02 (%)</label>
                                                      <input type="text" class="form-control input-sm" name="sa02" id="sa02" maxlength="10">
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label class="control-label" for="temperatura">TEMPERATURA (°C)</label>
                                                      <input type="text" class="form-control input-sm" name="temperatura" id="temperatura" maxlength="10">
                                                  </div>
                                              </div>
                                              <br>
                                              <div class="row">
                                                  <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label class="control-label" for="talla">TALLA (CM)</label>
                                                      <input type="text" class="form-control input-sm" name="talla" id="talla" maxlength="10">
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label class="control-label" for="peso">PESO (KG)</label>
                                                      <input type="text" class="form-control input-sm" name="peso" id="peso" maxlength="10">
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label class="control-label" for="imc">IMC</label>
                                                      <input class="form-control input-sm" name="imc" id="imc" readonly>
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label class="control-label" for="peso_ideal">PESO IDEAL</label>
                                                      <input class="form-control input-sm" name="peso_ideal" id="peso_ideal" readonly>
                                                  </div>
                                              </div>
                                          </div>
                                          <div role="tabpanel" class="tab-pane" id="tabAntecedentes">
                                              <div class="panel panel-info panel-bordered panel-sm">
                                                  <div class="panel-body">
                                                      <div class="row">
                                                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                              <p class="label-antecedentes">ANTECEDENTES</p>
                                                              <p id="antecedente" class="antecedentes-content"></p>
                                                          </div>
                                                      </div>
                                                      <hr>
                                                      <div class="row">
                                                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                              <p class="label-antecedentes">ANTECEDENTES FAMILIARES</p>
                                                              <p id="antecedente_familiar" class="antecedentes-content"></p>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                      <label class="control-label">HIPERTENSION ARTERIAL</label><br>
                                                      <label class="control-label" class="radio-inline">
                                                          <input type="radio" name="hipertensionArterial" id="hipertensionArterial_01" value="1"> SI
                                                      </label>
                                                      <label class="control-label" class="radio-inline">
                                                          <input type="radio" name="hipertensionArterial" id="hipertensionArterial_02" value="0" checked> NO
                                                      </label>
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                      <label class="control-label" for="tratamiento_hipertension" style="display: none;">TRATAMIENTO (HIPERTENSION)</label>
                                                      <input class="form-control input-sm" name="tratamiento_hipertension" id="tratamiento_hipertension" style="display: none">
                                                  </div>

                                                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                      <label class="control-label">DIABETES MELLITUS</label><br>
                                                      <label class="control-label" class="radio-inline">
                                                          <input type="radio" name="diabetes_mellitus" id="diabetes_mellitus_01" value="1"> SI
                                                      </label>
                                                      <label class="control-label" class="radio-inline">
                                                          <input type="radio" name="diabetes_mellitus" id="diabetes_mellitus_02" value="0" checked> NO
                                                      </label>
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                      <label class="control-label" for="tratamiento_diabetes" style="display: none"">TRATAMIENTO (DIABETES)</label>
                                                      <input class="form-control input-sm" name="tratamiento_diabetes" id="tratamiento_diabetes" style="display: none">
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                      <label class="control-label">ALERGIA</label><br>
                                                      <label class="control-label" class="radio-inline">
                                                          <input type="radio" name="alergia" id="alergia_01" value="1"> SI
                                                      </label>
                                                      <label class="control-label" class="radio-inline">
                                                          <input type="radio" name="alergia" id="alergia_02" value="0" checked> NO
                                                      </label>
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                      <label class="control-label" for="detalle_alergia" style="display: none">DETALLE (ALERGIA)</label>
                                                      <input class="form-control input-sm" name="detalle_alergia" id="detalle_alergia" style="display: none">
                                                  </div>

                                                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                      <label class="control-label">HEMODIALISIS</label><br>
                                                      <label class="control-label" class="radio-inline">
                                                          <input type="radio" name="hemodialisis" id="hemodialisis_01" value="1"> SI
                                                      </label>
                                                      <label class="control-label" class="radio-inline"> 
                                                          <input type="radio" name="hemodialisis" id="hemodialisis_02" value="0" checked> NO
                                                      </label>
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                      <label class="control-label" for="detalle_hemodialisis" style="display: none">DETALLE (HEMODIALISIS)</label>
                                                      <input class="form-control input-sm" name="detalle_hemodialisis" id="detalle_hemodialisis" style="display: none">
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                                      <label class="control-label" for="cardiologico">CARDIOLOGICO</label>
                                                      <select name="cardiologico[]" id="cardiologico" class="js-example-basic-multiple"  style="width: 100%;" multiple="multiple">
                                                      </select>
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                                      <label class="control-label" for="observacion">OBSERVACION</label>
                                                      <input class="form-control input-sm" name="observacion" id="observacion">
                                                  </div>
                                                  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                                      <label class="control-label" for="otros_antecedentes">OTROS ANTECEDENTES</label>
                                                      <input class="form-control input-sm" name="otros_antecedentes" id="otros_antecedentes">
                                                  </div>
                                              </div>
                                          </div>
                                          <div role="tabpanel" class="tab-pane" id="tabCpmsCpt">
                                              <div class="panel panel-info panel-bordered panel-sm">
                                                  <div class="panel-heading">
                                                      <div class="panel-title text-center">
                                                          <b>DIAGNOSTICOS DE LA ATENCION</b>
                                                      </div>    
                                                  </div>
                                                  <div class="panel-body">
                                                      <table class="table table-striped" style="font-size: 11px; width: 100%" id="tableDiagnosticos">
                                                          <thead>
                                                              <tr>
                                                                  <th width="10%">CÓDIGO</th>
                                                                  <th width="50%">NOMBRE</th>
                                                                  <th width="20%">TIPO</th>
                                                                  <th width="20%">CLASIFICACIÓN</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody></tbody>
                                                      </table>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                      <button type="button" class="btn btn-primary btn-sm" id="btnAddProcedure">
                                                          <i class="fa fa-plus"></i> AGREGAR PROCEDIMIENTO
                                                      </button>
                                                  </div>
                                                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                      <table class="table table-striped" style="font-size: 11px" id="tableProcedures">
                                                          <thead>
                                                              <tr id="tableProceduresHeader">
                                                                  <th width="50%">PROCEDIMIENTO</th>
                                                                  <th width="10%">CANTIDAD</th>
                                                                  <th width="35%">DIAGNOSTICO</th>
                                                                  <th width="5%">ELIMINAR</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody></tbody>
                                                      </table>
                                                  </div>
                                              </div>
                                          </div>
                                          <div role="tabpanel" class="tab-pane" id="tabObservaciones">
                                              <label class="control-label" for="observaciones">DETALLE DE OBSERVACIONES</label>
                                              <textarea class="form-control" id="observaciones" name="observaciones" rows="5" maxlength=""></textarea>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                            </fieldset>
                          </form>
                        </div>
                        <div class="panel-footer" style="padding: 20px 15px 30px 15px">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <a href="/enfermeria/consulta-externa" class="btn btn-danger btn-block"><i class="fa fa-ban"></i> CANCELAR</a>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <button 
                                        type="button" 
                                        class="btn btn-primary btn-block" 
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        title="Realize la busqueda de un cita para guardar la nota de enfermeria" 
                                        id="btnSubmit" 
                                        disabled
                                    ><i class="fa fa-floppy-o"></i> GUARDAR
                                    </button>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialAtenciones">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE ATENCIONES MEDICAS</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='contentHistorialAtenciones'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialLaboratorio">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE EXAMENES DE LABORATORIO</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divLaboratorio'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialRadiologia">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE EXAMENES DE IMAGENOLOGIA</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divRadiologico'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialnterconsulta">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE INTERCONSULTA</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divInterconsultaH'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialReferencia">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE REFERENCIAS / CONTRAREFERENCIAS</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divRC1'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialEnfermeria">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE ENFERMERIA</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divHistorialEnfermeria'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalListadoCitas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="listaAtencionesModalLabel" aria-hidden="true"> 
  <div class="modal-dialog modal-dialog-centered modal-xl" style="width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="listaAtencionesModalLabel">DETALLES ENFERMERIA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="panel panel-success">
          <div class="panel-heading">
            <h3 class="panel-title" style="text-align: center"><b>DATOS PACIENTE</b></h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-group">
                  <label for="tipo_documento_modal">Tipo Documento</label>
                  <input type="text" class="form-control input-sm" id="tipo_documento_modal" readonly>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-group">
                  <label for="numero_documento_modal">Número Documento</label>
                  <input type="text" class="form-control input-sm" id="numero_documento_modal" readonly>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-group">
                  <label for="nombres_modal">Nombres</label>
                  <input type="text" class="form-control input-sm" id="nombres_modal" readonly>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-group">
                  <label for="apellido_paterno_modal">Apellido Paterno</label>
                  <input type="text" class="form-control input-sm" id="apellido_paterno_modal" readonly>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-group">
                  <label for="apellido_materno_modal">Apellido Materno</label>
                  <input type="text" class="form-control input-sm" id="apellido_materno_modal" readonly>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-group">
                  <label for="grado_modal">Grado</label>
                  <input type="text" class="form-control input-sm" id="grado_modal" readonly>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-group">
                  <label for="sexo_modal">Sexo</label>
                  <input type="text" class="form-control input-sm" id="sexo_modal" readonly>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-group">
                  <label for="edad_modal">Edad</label>
                  <input type="text" class="form-control input-sm" id="edad_modal" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel panel-success" id="panelListadoCitas">
          <div class="panel-heading">
            <h3 class="panel-title" style="text-align: center"><b>LISTADO DE CITAS</b></h3>
          </div>
          <div class="panel-body">
            <table class="table table-striped" style="font-size: 11px; width: 100%" id="tableAtencionesCitas">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Fecha Cita</th>
                  <th>Hora Cita</th>
                  <th>Fecha Atención Cita</th>
                  <th>Hora Atención Cita</th>
                  <th>Consultorio</th>
                  <th>Medico</th>
                  {{-- <th>Enfermer@</th> --}}
                  <th>Notas Enfermería</th>
                  <th>Nueva Nota Enfemeria</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div> 

        <div class="panel panel-success" id="panelListadoAtencionesEnfermeria" style="display: none;">
          <div class="panel-heading">
            <h3 class="panel-title" style="text-align: center"><b>LISTADO ATENCIONES ENFERMERIA</b></h3>
          </div>
          <div class="panel-body">
            <button type="button" class="btn btn-primary btn-sm" id="btnBackPanelListadoCitas" name="btnBackPanelListadoCitas" style="margin-bottom: 20px">
              <i class="fa fa-arrow-left" aria-hidden="true"></i> Volver Listado Citas
            </button>
            <table class="table table-striped" style="font-size: 11px; width: 100%" id="tableAtencionesEnfermeria">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Fecha Atención</th>
                  <th>Hora Atención</th>
                  <th>Responsable</th>
                  <th>Detalle</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>    
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection