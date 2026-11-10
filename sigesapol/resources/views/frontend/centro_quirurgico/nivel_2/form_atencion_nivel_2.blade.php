@extends('frontend.layouts.master3')
@section('renderjs')
<script src="{{ asset('centro_quirurgico/nivel2/js/registrar_atencion_nivel_2.js') }}"></script>
@endsection
@section('styles')
<link rel="stylesheet" href="{{ asset('centro_quirurgico/nivel2/css/registrar_atencion_nivel_2.css') }}">
@endsection
@section('content')
<div class="container" style="width: 95%">
  <div class="panel panel-primary filterable">
    <div class="panel-heading" style="text-align: left">INTERVENCIONES QUIRURGICAS: PACIENTE</div>
    <form>
      <input type="hidden" name="id_intervencion" id="id_intervencion" value="{{ $id }}">
      <input type="hidden" name="asegurado_sexo_abrev" id="asegurado_sexo_abrev">
      <input type="hidden" name="establecimiento_nivel" id="establecimiento_nivel">
      <input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion">
      <input type="hidden" name="codigo_siteds" id="codigo_siteds">
      <input type="hidden" name="id_asegurado" id="id_asegurado">
      <input type="hidden" name="id_establecimiento" id="id_establecimiento">
      <input type="hidden" name="hospitalizacion_fecha_ingreso" id="hospitalizacion_fecha_ingreso">
      <input type="hidden" name="id_medico" id="id_medico" value="{{ $medico->id }}">
      <div class="panel-body">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="panel panel-primary">
              <div class="panel-heading">INTERVENCIONES QUIRURGICAS: PACIENTE</div>
              <div class="panel-body">
                <div class="form-body">
                  <div class="form-row double">
                    <span class="label">N° DOCUMENTO</span><span class="colon">:</span>
                    <span class="value" id="nro_documento"></span>

                    <span class="label">N° HC</span><span class="colon">:</span>
                    <span class="value" id="nro_hc"></span>
                  </div>

                  <div class="form-row">
                    <span class="label">APELLIDOS Y NOMBRES</span><span class="colon">:</span>
                    <span class="value" id="apellido_nombres"></span>
                  </div>

                  <div class="form-row double">
                    <span class="label">FEC. NACIMIENTO</span><span class="colon">:</span>
                    <span class="value" id="fec_nacimiento"></span>
                    
                    <span class="label">EDAD</span><span class="colon">:</span>
                    <span class="value" id="edad"></span>
                  </div>

                  <div class="form-row">
                    <span class="label">SEXO</span><span class="colon">:</span>
                    <span class="value" id="sexo"></span>
                  </div>

                  <div class="form-row">
                    <span class="label">TIPO DE BENEFICIARIO</span><span class="colon">:</span>
                    <span class="value" id="tipo_beneficiario"></span>
                  </div>

                  <div class="form-row">
                    <span class="label">DATOS DEL TITULAR</span><span class="colon">:</span>
                    <span class="value" id="datos_del_titular"></span>
                  </div>

                  <div class="form-row">
                    <span class="label">CIP/DNI/GRADO</span><span class="colon">:</span>
                    <span class="value" id="cip_dni_grado"></span>
                  </div>

                  <div class="form-row">
                    <span class="label">DISCAPACIDAD</span><span class="colon">:</span>
                    <span class="value" id="discapacidad"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="panel panel-primary">
              <div class="panel-heading">INTERVENCIONES QUIRURGICAS: ATENCIÓN</div>
              <div class="panel-body">
                <div class="form-row double">
                  <span class="label">COD.SITEDS SUSALUD</span><span class="colon">:</span>
                  <span class="value" id="codigo_siteds_text"></span>

                  <label class="label" for="hora_atencion">HORA DE ATENCIÓN</label><span class="colon">:</span>
                  <div class="form-group">
                    <input class="form-control input-sm value" type="time" name="hora_atencion" id="hora_atencion">
                  </div>
                </div>

                <div class="form-row">
                  <span class="label">TIPO DE ATENCIÓN</span><span class="colon">:</span>
                  <span class="value" id="tipo_atencion"></span>
                </div>

                <div class="form-row">
                  <label class="label" for="fecha_ingreso">FEC. DE INGRESO</label><span class="colon">:</span>
                  <span class="value" id="fecha_ingreso"></span>
                </div>
                
                <div class="form-row">
                  <span class="label" for="fecha_atencion">FEC. DE ATENCIÓN</span><span class="colon">:</span>
                  <div class="form-group">
                    <input type="text" class="form-control input-sm value input-readonly-white" id="fecha_atencion" name="fecha_atencion" readonly style="width: 35%">
                  </div>
                </div>

                <div class="form-row">
                  <span class="label">SERVICIO DE PROCEDENCIA</span><span class="colon">:</span>
                  <span class="value" id="servicio_procedencia"></span>
                </div>

                <div class="form-row">
                  <span class="label">UBICACION DE PACIENTE</span><span class="colon">:</span>
                  <span class="value" id="ubicacion_paciento"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading">DATOS DEL RESPONSABLE DE REGISTRO</div>
          <div class="panel-body">
            <div class="form-row">
              <span class="label">RESPONSABLE DE LA ATENCIÓN</span><span class="colon">:</span>
              <span class="value" id="responsable_atencion">{{ $medico->nombre }} {{ $medico->paterno }} {{ $medico->materno }}</span>
            </div>
            <div class="form-row">
              <span class="label">PROFESIÓN/ESPECIALIDAD</span><span class="colon">:</span>
              <span class="value" id="res_aten_profesion_especialidad">{{ $medico->profesion }} / {{ $medico->especialidad ? $medico->especialidad : "-" }}</span>
            </div>
            <div class="form-row">
              <span class="label">N° CMP/N° RNE</span><span class="colon">:</span>
              <span class="value" id="res_aten_cmp">{{ $medico->colegiatura }} / {{ $medico->rne }}</span>
            </div>
            <div class="form-row double">
              <label class="label" for="servicio">SERVICIO</label><span class="colon">:</span>
              <div class="form-group">
                <select id="servicio" name="servicio" class="form-control input-sm" style="width: 80%">
                  <option value="" selected>-- Seleccionar --</option>
                  @if ($consultorios && count($consultorios) > 0)
                    @foreach ($consultorios as $consultorio)
                      <option value="{{ $consultorio->id }}">{{ $consultorio->nombre }}</option>
                    @endforeach
                  @endif
                </select>
              </div>

              <label class="label right" for="cod_upss">COD. UPSS</label><span class="colon">:</span>
              <div class="form-group">
                <select id="cod_upss" name="cod_upss" class="form-control input-sm" style="width: 100%">
                  <option value="" selected>-- Seleccionar --</option>
                  @if ($upsses && count($upsses) > 0)
                    @foreach ($upsses as $upss)
                      <option value="{{ $upss->id_upss }}">{{ $upss->descripcion_upss }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading">DATOS DEL RESPONSABLE QUE INDICA LA INTERVENCION</div>
          <div class="panel-body">
            <div class="row">
              <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                <label>N° DOCUMENTO</label><br>
                <span class="value-row" id="res_inter_nro_documento"></span>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5 form-group">
                <label>PROFESIONAL MEDICO</label><br>
                <span class="value-row" id="res_inter_profesional_medico"></span>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                <label>PROFESION</label><br>
                <span class="value-row" id="res_inter_profesion"></span>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-1 col-xl-1 form-group">
                <label>CPM</label><br>
                <span class="value-row" id="res_inter_cpm"></span>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-1 col-xl- form-group">
                <label>R.N.E.</label><br>
                <span class="value-row" id="res_inter_rne"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading">DATOS DEL RESPONSABLE DE LA INTERVENCION QUIRURGICA</div>
          <div class="panel-body">
            <div>
              <button id="faBtnAddTableCirujano" type="button" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> AGREGAR CIRUJANO</button>
              <table id="faTableCirujano" class="table table-condensed">
                <thead id="faTableCirujanoHead">
                  <tr>
                    <th style="width: 50%;">PROFESIONAL</th>
                    <th style="width: 25%">PROFESION</th>
                    <th style="width: 10%">CPM</th>
                    <th style="width: 10%">TIPO CIRUJANO</th>
                    <th style="width: 5%"></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
            <div>
              <button id="faBtnAddTableAnastesiologo" type="button" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> AGREGAR ANASTESIOLOGO</button>
              <table id="faTableAnastesiologo" class="table table-condensed">
                <thead id="faTableAnastesiologoHead">
                  <tr>
                    <th style="width: 50%;">PROFESIONAL</th>
                    <th style="width: 25%">PROFESION</th>
                    <th style="width: 10%">CPM</th>
                    <th style="width: 10%">TIPO ANASTESIOLOGO</th>
                    <th style="width: 5%"></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
            <div>
              <button id="faBtnAddTableEnfermeraIntru" type="button" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> AGREGAR ENFERMERA INSTRUMENTISTA</button>
              <table id="faTableEnfermeraIntru" class="table table-condensed">
                <thead id="faTableEnfermeraIntruHead">
                  <tr>
                    <th style="width: 50%;">PROFESIONAL</th>
                    <th style="width: 25%">PROFESION</th>
                    <th style="width: 10%">CPM</th>
                    <th style="width: 10%">TIPO ENFERMERA</th>
                    <th style="width: 5%"></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading">DIAGNOSTICOS</div>
          <div class="panel-body">
            <table id="faTableDiagnosticosPre" class="table table-condensed table-bordered">
              <thead>
                <tr>
                  <th style="width: 20%">Cod.</th>
                  <th style="width: 60%">Descripción</th>
                  <th style="width: 20%">Tipo</th>
                </tr>
              </thead>
              <tbody>
                
              </tbody>
            </table>
            <div>
              <button id="faBtnAddTableDiagnosticos" type="button" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> AGREGAR DIAGNOSTICO</button>
              <table id="faTableDiagnosticos" class="table table-condensed">
                <thead id="faTableDiagnosticosHead">
                  <tr>
                    <th style="width: 60%">CÓDIGO - DESCRIPCIÓN</th>
                    <th style="width: 20%">TIPO DE DIAGNOTICO</th>
                    <th style="width: 15%">CLASIFICACIÓN</th>
                    <th style="width: 5%"></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading">PROCEDIMIENTOS</div>
          <div class="panel-body">
            <button id="faBtnAddProcedures" type="button" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> AGREGAR PROCEDIMIENTOS</button>
            <table id="faTableProcedures" class="table table-condensed">
              <thead id="faTableProceduresHead">
                <tr>
                  <th style="width: 40%">CÓDIGO - DESCRIPCIÓN</th>
                  <th style="width: 10%">CANTIDAD</th>
                  <th style="width: 10%">TIEMPO</th>
                  <th style="width: 35%">DIAGNOSTICO</th>
                  <th style="width: 5%"></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading">DATOS DE LAINTERVENCION QUIRURGICA</div>
          <div class="panel-body">
            <div class="row">
              <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                <label for="tipo_cirugia">Tipo de cirugia</label>
                <select id="tipo_cirugia" name="tipo_cirugia" class="form-control input-sm">
                  <option value="" selected>-- seleccionar --</option>
                  <option value="1">CIRUGIA MAYOR</option>
                  <option value="2">CIRUGIA MENOR</option>
                </select>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                <label for="tipo_anestesia">Tipo de anestesia</label>
                <select id="tipo_anestesia" name="tipo_anestesia" class="form-control input-sm">
                  <option value="" selected>-- seleccionar --</option>
                  @if ($tiposAnestesia && count($tiposAnestesia) > 0)
                    @foreach ($tiposAnestesia as $tipoAnestesia)
                      <option value="{{ $tipoAnestesia->id }}">{{ $tipoAnestesia->descripcion }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                <label for="hr_programadas">Hr. Programadas</label>
                <input class="form-control input-sm" type="number" step="1" name="hr_programadas" id="hr_programadas">
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                <label for="hra_inicio_intervencion">Hra. Inicio Intervencion Qx.</label>
                <input class="form-control input-sm" type="datetime-local" name="hra_inicio_intervencion" id="hra_inicio_intervencion">
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                <label for="hra_fin_intervencion">Hra. Fin Intervencion Qx.</label>
                <input class="form-control input-sm" type="datetime-local" name="hra_fin_intervencion" id="hra_fin_intervencion">
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                <label for="hrs_efectivas">Hrs. Efectivas</label>
                <input class="form-control input-sm input-readonly" type="text" name="hrs_efectivas" id="hrs_efectivas" readonly>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                <label for="hra_inicio_acto_qx">Hra. Incio Acto Qx.</label>
                <input class="form-control input-sm" type="datetime-local" name="hra_inicio_acto_qx" id="hra_inicio_acto_qx">
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                <label for="hra_fin_acto_qx">Hra. Fin Acto Qx.</label>
                <input class="form-control input-sm" type="datetime-local" name="hra_fin_acto_qx" id="hra_fin_acto_qx">
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                <label for="hrs_acto_qx">Hrs. Acto Qx.</label>
                <input class="form-control input-sm input-readonly" type="text" name="hrs_acto_qx" id="hrs_acto_qx" readonly>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                <label for="complicacion_intervencion">Complicación de la intervención</label>
                <select id="complicacion_intervencion" name="complicacion_intervencion" class="form-control input-sm">
                  <option value="" selected>-- seleccionar --</option>
                  <option value="0">SIN COMPLICACIONES</option>
                  <option value="1">COMPLICADO</option>
                </select>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                <label for="tipo_programacion">Tipo de programación</label>
                <select id="tipo_programacion" name="tipo_programacion" class="form-control input-sm">
                  <option value="" selected>-- seleccionar --</option>
                  @if ($tiposProgramacion && count($tiposProgramacion) > 0)
                    @foreach ($tiposProgramacion as $tipoProgramacion)
                      <option value="{{ $tipoProgramacion->id }}">{{ $tipoProgramacion->descripcion }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="container-buttons">
          <div class="row ">
            <div class="col-12 col-sm-12 col-md-6">
              <a type="button" class="btn btn-danger btn-block" href="/cq_lista_pendiente" ><i class="fa fa-ban"></i>  CANCELAR</a>
            </div>
            <div class="col-12 col-sm-12 col-md-6">
              <button type="button" class="btn btn-primary btn-block" id="faBtnSaveAttention"><i class="fa fa-floppy-o"></i>  GUARDAR</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@include('frontend.includes.footer')
@endsection