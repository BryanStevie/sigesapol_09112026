<div class="panel-body">
    <form name="frmValidacionProgramacion" id="frmValidacionProgramacion" method="post"
        action="{{ route('validar_programacion_quirurgica') }}" class="form-horizontal" role="form" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id_tipo_atencion_origen" value="{{ $id_tipo_atencion_origen }}">
        <input type="hidden" name="id_intervencion_quirurgica" value="{{ $intervencion->id }}">
        <input type="hidden" name="id_medico" value="{{ $id_medico }}">
        <input type="hidden" name="is_origen_emergencia" value="{{ $is_origen_emergencia }}">
        <input type="hidden" id="sexo" value="{{ !empty($intervencion->sexo) ? $intervencion->sexo : '' }}">
        <input type="hidden" id="duracion_cirugia" name="duracion_cirugia"
            value="{{ $intervencion->emergencia_duracion_cirugia }}">
        <input type="hidden" id="view_sugerencia_medica" value="{{ $intervencion->sugerencia_medica }}">
        @if (!$is_origen_emergencia)
            <input type="hidden" name="id_lugar_operacion" value="{{ $intervencion->id_lugar_operacion }}">
        @endif

        <div class="panel panel-info panel-sm">
            <div class="panel-heading">
                <div class="panel-title text-center"><b>OBSERVACIÓN</b></div>
            </div>
            <div class="panel-body">
                <p><strong> LISTA DE OBSERVACIONES</strong></p>
                <table class="table table-bordered table-condensed" id="tblObservaciones">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha Hora</th>
                            <th>Medico</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($observaciones->count() > 0)
                            @foreach ($observaciones as $index => $obs)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $obs->fecha_registro ? \Carbon\Carbon::parse($obs->fecha_registro)->format('d/m/Y') : '' }}
                                        {{ $obs->hora_registro ? $obs->hora_registro : '' }}</td>
                                    <td>{{ $obs->medico ? $obs->medico->nombre . ' ' . $obs->medico->paterno . ' ' . $obs->medico->materno : '' }}
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-eye text-danger" data-toggle="popover" data-trigger="hover"
                                            data-placement="top" data-content="{{ $obs->motivo }}"></i>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">Sin observaciones</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="form-group">
                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                        <label>Observado</label>
                    </div>
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                            <input type="hidden" id="is_observado" name="is_observado" value="0">
                            <button tabindex="110" type="button" id="addObservado" class="btn btn-lg btn-toggle"
                                data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                            </button>
                        </div>
                    @else
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                            <button tabindex="110" type="button" class="btn btn-lg btn-toggle" data-toggle="button"
                                aria-pressed="false" autocomplete="off" disabled>
                                <div class="handle"></div>
                            </button>
                        </div>
                    @endif
                </div>
                <div id="fsObservado" style="display: none">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label for="">FECHA DE REGISTRO</label>
                            <input tabindex="6" type="date" class="form-control" name="fecha_registro_observacion"
                                id="fecha_registro_observacion" style="text-align:left;">
                        </div>
                        <div class="col-md-3">
                            <label for="">HORA DE REGISTRO</label>
                            <input tabindex="7" type="time" class="form-control" id="hora_registro_observacion"
                                name="hora_registro_observacion" style="text-align:left">
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">INFORMACIÓN DEL RESPONSABLE DE LA ATENCION</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                    <input tabindex="11" readonly="" type="text" class="form-control"
                                        value="DNI" style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                    <label for="">N° DE DOCUMENTO DE IDENTIDAD</label>
                                    <input tabindex="12" readonly="" type="text" class="form-control"
                                        value="{{ !empty($data_medico->dni) ? $data_medico->dni : '' }}"
                                        style="text-align:left">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="">APELLIDO PATERNO</label>
                                    <input tabindex="15" readonly="" type="text" class="form-control"
                                        value="{{ !empty($data_medico->paterno) ? $data_medico->paterno : '' }}"
                                        style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                    <label for="">APELLIDO MATERNO</label>
                                    <input tabindex="16" readonly="" type="text" class="form-control"
                                        value="{{ !empty($data_medico->materno) ? $data_medico->materno : '' }}"
                                        style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                    <label for="">NOMBRES</label>
                                    <input tabindex="17" readonly="" type="text" class="form-control"
                                        value="{{ !empty($data_medico->nombre) ? $data_medico->nombre : '' }}"
                                        style="text-align:left">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="">PROFESIÓN</label>
                                    <input tabindex="27" readonly=""
                                        value="{{ !empty($data_medico->profesion) ? $data_medico->profesion : '' }}"
                                        type="text" class="form-control" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                    <label for="">N° COLEGIATURA</label>
                                    <input tabindex="25" readonly="" type="text" class="form-control"
                                        value="{{ !empty($data_medico->colegiatura) ? $data_medico->colegiatura : '' }}"
                                        style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                    <label for="">N° RNE</label>
                                    <input tabindex="26" readonly=""
                                        value="{{ !empty($data_medico->rne) ? $data_medico->rne : '' }}"
                                        type="text" class="form-control" style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                    <label for="">ESPECIALIDAD</label>
                                    <input tabindex="28" readonly=""
                                        value="{{ !empty($data_medico->especialidad) ? $data_medico->especialidad : '' }}"
                                        type="text" class="form-control" style="text-align:left">
                                </div>
                            </div>
                        </div>
                    </div>

                    <fieldset class="panel panel-info panel-bordered panel-sm">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="">MOTIVO</label>
                                <textarea class="form-control" id="motivo_observacion" name="motivo_observacion" rows="4" cols="50"></textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="panel panel-info panel-bordered panel-sm panel-body" id="programacion_validacion">
            <div class="panel-heading">
                <div class="panel-title text-center"><b>REGISTRAR PROGRAMACIÓN QUIRÚRGICA</b></div>
            </div>
            <div class="panel-body">
                @if ($is_origen_emergencia || $es_traslado_hospitalizacion_acqx)
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <div class="col-md-2">
                            <label for="">FECHA DE REGISTRO</label>
                            <input tabindex="6" type="date" class="form-control" name="fecha_registro"
                                id="fecha_registro" style="text-align:left;">
                        </div>
                        <div class="col-md-2">
                            <label for="">HORA DE REGISTRO</label>
                            <input tabindex="7" type="time" class="form-control" id="hora_registro"
                                name="hora_registro" style="text-align:left">
                        </div>
                    @else
                        <div class="col-md-2">
                            <label for="">FECHA DE REGISTRO</label>
                            <p>{{ $intervencion->fecha_registro ? \Carbon\Carbon::parse($intervencion->fecha_registro)->format('Y-m-d') : '' }}
                            </p>
                        </div>
                        <div class="col-md-2">
                            <label for="">HORA DE REGISTRO</label>
                            <p>{{ $intervencion->hora_registro ? \Carbon\Carbon::parse($intervencion->hora_registro)->format('H:i') : '' }}
                            </p>
                        </div>
                    @endif

                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <div class="col-sm-12 col-md-2">
                            <label
                                for="">{{ $es_traslado_hospitalizacion_acqx ? 'SELECCIONAR' : 'TIPO DE CIRUGIA' }}:</label>
                            <select name="id_tipo_cirugia" id="id_tipo_cirugia" class="form-control">
                                <option value="0">Seleccione</option>
                                @foreach ($tipo_cirugias as $row)
                                    <option value="{{ $row->id }}"
                                        @if ($intervencion->emergencia_tipo_cirugia == $row->id) selected @endif>{{ $row->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="col-sm-12 col-md-2">
                            <label for="">TIPO DE CIRUGIA:</label>
                            <p>{{ $intervencion->tipo_cirugia }}</p>
                        </div>
                    @endif

                    <div id="electiva_div" style="display: none;">
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-2">
                                <label for="">HORA DE INICIO:</label>
                                <input type="time" class="form-control input-sm" name="hora_inicio_intervencion"
                                    id="hora_inicio_intervencion" value="{{ $intervencion->hora_inicio_prog_qx }}">
                            </div>
                        @else
                            <div class="col-md-2">
                                <label for="">HORA DE INICIO:</label>
                                <p>{{ date('H:i', strtotime($intervencion->fecha_inicio_intervencion)) }}</p>
                            </div>
                        @endif

                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-2">
                                <label for="">HORA DE FIN:</label>
                                <input type="time" class="form-control input-sm" name="hora_fin_intervencion"
                                    id="hora_fin_intervencion" value="{{ $intervencion->hora_fin_prog_qx }}">
                            </div>
                        @else
                            <div class="col-md-2">
                                <label for="">HORA DE FIN:</label>
                                <p>{{ date('H:i', strtotime($intervencion->fecha_fin_intervencion)) }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="col-sm-12 col-md-3">
                        <label for="">DURACIÓN DE LA CIRUGIA:</label>
                        <input type="time" id="duracion_cirugia_calculada" class="form-control"
                            style="width: 105px;" disabled>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2" style="text-align:right;">
                            <label for="">SALA DE INTERVENCIÓN:</label>
                        </div>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-2">
                                <select name="id_sala_intervencion" id="id_sala_intervencion" class="form-control input-sm">
                                    <option value="0">Seleccione</option>
                                    @foreach ($cq_salas as $row)
                                        <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-md-2">
                                <p>{{ $intervencion->sala_intervencion }}</p>
                            </div>
                        @endif
                        <div class="col-md-2" style="text-align:right;">
                            <label for="">TIPO DE ANESTESIA:</label>
                        </div>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-6">
                                <select name="id_tipo_anestesia" id="id_tipo_anestesia" class="form-control input-sm">
                                    <option value="0">Seleccione</option>
                                    @foreach ($tipos_anestesia as $row)
                                        <option value="{{ $row->id }}">{{ $row->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-md-6">
                                <p>{{ $intervencion->tipo_anestesia }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center">
                                <b>DIAGNOSTICO DE INTERVENCIÓN</b>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table id="tblDiagnosticoQuirurgico" cellspacing="0" width="100%" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="50%">NOMBRE</th>
                                        <th width="15%">TIPO</th>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($diagnosticos_cqx as $key => $row)
                                        <tr>
                                            <?php $tipo = '';
                                            switch ($row->id_tipo_diagnostico):
                                                case 1:
                                                    $tipo = 'PRESUNTIVO';
                                                    break;
                                                case 2:
                                                    $tipo = 'DEFINITIVO';
                                                    break;
                                                case 3:
                                                    $tipo = 'REITERATIVO';
                                                    break;
                                            endswitch;
                                            ?>
                                            <td style="width:60%; 1px solid gray;">
                                                <p>{{ $row->codigo_diagnostico }} - {{ $row->descripcion_diagnostico }}</p>
                                            </td>
                                            <td style="width:10%; 1px solid gray;">{{ $tipo }}</td>
                                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                            @endif
                                            <?php $i++; ?>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center">
                                <b>PROCEDIMIENTOS DE INTERVENCIÓN</b>
                            </div>
                        </div>
                        <div class="panel-body">
                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <div class="form-group" style="display:none">
                                    <select id="cmbProcedimientoIntervencionTempEmergencia">
                                        <option value="0">--Seleccionar--</option>
                                        @foreach ($procedimientos as $p)
                                            <option value="{{ $p->id }}">{{ $p->codigo }} - {{ $p->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <table id="table_procedimiento_intervencion_emergencia" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="70%">DESCRIPCION</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                    @foreach ($procedimientos_cqx_emergencia as $pintervencion)
                                        <tr>
                                            <td>{{ $pintervencion->codigo_cpms }} - {{ $pintervencion->descripcion_cpms }}
                                            </td>
                                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Incio PROFESIONALES QUE INTERVIENEN -->
                    <div class="panel panel-info panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center"><b>PROFESIONALES QUE INTERVIENEN</b></div>
                        </div>
                        <div class="panel-body">

                            <!-- INICIO CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN -->
                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <div class="form-group">
                                    <div class="col-sm-2">
                                        {{-- <button tabindex="75" type="button" id="addMedicoCirujanoEmergencia" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClassModal">Agregar Cirujano que intervienen en la operación</button> --}}
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <select id="cmbProfesionalesCirujanosTodosTempEmergencia">
                                        <option value="">--Seleccionar--</option>
                                        @foreach ($lista_profesionales_cirujanos as $pa)
                                            <option value="{{ $pa->id }}">{{ $pa->paterno }} {{ $pa->materno }},
                                                {{ $pa->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <select id="cmbTipoProfesionalCirujanoTempEmergencia">
                                        <option value="0">--Seleccionar--</option>
                                        @foreach ($cqx_tipo_profesionales as $tp)
                                            @if ($tp->flag_profesional == 1)
                                                <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <table id="table_medico_cirujano_emergencia" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Apellidos y Nombres</th>
                                                <th style="width:30%;padding-left:20px;">Cargo</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                            @foreach ($profesionales_cqx_emergencia as $pq)
                                                <tr>
                                                    <td>{{ $pq->paterno }} {{ $pq->materno }}, {{ $pq->nombre }}</td>
                                                    <td>{{ $pq->tipo_profesional }}</td>
                                                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                                        <td></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- FIN CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN -->

                            <!-- INICIO ANESTESIOLOGOS QUE INTERVIENEN EN LA OPERACIÓN -->
                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <div class="form-group">
                                    <div class="col-sm-2">
                                        <button tabindex="75" type="button" id="addMedicoAnestesiologo"
                                            class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#addClassModal">Agregar Anestesiólogo que intervienen en la
                                            operación</button>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <select id="cmbProfesionalesAntesiologiaTemp">
                                        <option value="">--Seleccionar--</option>
                                        @foreach ($lista_profesionales_anestesiologos as $pa)
                                            <option value="{{ $pa->id }}">{{ $pa->paterno }} {{ $pa->materno }},
                                                {{ $pa->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <select id="cmbTipoProfesionalAnestesiologoTemp">
                                        <option value="0">--Seleccionar--</option>
                                        @foreach ($cqx_tipo_profesionales as $tp)
                                            @if ($tp->flag_profesional == 2)
                                                <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <table id="table_medico_anestesiologo" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Apellidos y Nombres</th>
                                                    <th style="width:30%;padding-left:20px;">Cargo</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Apellidos y Nombres</th>
                                                    <th style="width:30%">Cargo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($profesionales_anestesiologos as $pq)
                                                    <tr>
                                                        <td>{{ $pq->paterno }} {{ $pq->materno }}, {{ $pq->nombre }}</td>
                                                        <td>{{ $pq->tipo_profesional }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <!-- FIN ANESTESIOLOGOS QUE INTERVIENEN EN LA OPERACIÓN -->

                        </div>
                    </div>
                    <!-- Fin PROFESIONALES QUE INTERVIENEN -->
                @else
                    <?php
                    $lugar_operacion = '';
                    switch ($intervencion->id_lugar_operacion) {
                        case 1:
                            $lugar_operacion = 'HOSPITALIZADO';
                            break;
                        case 2:
                            $lugar_operacion = 'CLÍNICA DE DÍA';
                            break;
                    }
                    ?>

                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="">FECHA DE REGISTRO</label>
                                <input tabindex="6" type="date" class="form-control" name="fecha_registro"
                                    id="fecha_registro" style="text-align:left;">
                            </div>
                            <div class="col-md-3">
                                <label for="">HORA DE REGISTRO</label>
                                <input tabindex="7" type="time" class="form-control" id="hora_registro"
                                    name="hora_registro" style="text-align:left">
                            </div>
                            <div class="col-md-3">
                                <label for="">A SER OPERADO EN:</label>
                                <select name="id_lugar_operacion" id="id_lugar_operacion" class="form-control input-sm">
                                    <option value="0">Seleccione</option>
                                    <option value="1"
                                        {{ !empty($datos_interconsulta) && $datos_interconsulta->id_tipo_atencion == 3 ? 'selected' : ($intervencion->id_lugar_operacion == 1 ? 'selected' : '') }}>
                                        HOSPITALIZADO</option>
                                    <option value="2" @if (
                                        ($id_tipo_origen == 1 && $intervencion->es_nuevo_admision_hospitalizacion) ||
                                            $intervencion->id_lugar_operacion == 2) selected @endif>CLÍNICA DE DÍA
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">FECHA PROBABLE DE INTERVENCIÓN:</label>
                                @if ($intervencion->es_nuevo_admision_hospitalizacion)
                                    <input tabindex="6" type="date" class="form-control"
                                        name="fecha_probable_intervencion" id="fecha_probable_intervencion"
                                        value="{{ \Carbon\Carbon::parse($fecha_registro_admision)->format('Y-m-d') }}"
                                        style="text-align:left;">
                                @else
                                    <input tabindex="6" type="date" class="form-control"
                                        name="fecha_probable_intervencion" id="fecha_probable_intervencion"
                                        value="{{ \Carbon\Carbon::parse($intervencion->fecha_probable)->format('Y-m-d') }}"
                                        style="text-align:left;">
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="">FECHA DE REGISTRO: </label>
                                <p>{{ $intervencion->fecha_registro ? \Carbon\Carbon::parse($intervencion->fecha_registro)->format('Y-m-d') : '' }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label for="">HORA DE REGISTRO</label>
                                <p>{{ $intervencion->hora_registro ? \Carbon\Carbon::parse($intervencion->hora_registro)->format('H:i') : '' }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label for="">A SER OPERADO EN:</label>
                                <p> {{ $lugar_operacion }}</p>
                            </div>
                            <div class="col-md-3">
                                <label for="">FECHA PROBABLE DE INTERVENCIÓN: </label>
                                <p>{{ !empty($intervencion->fecha_probable) ? date('d-m-Y', strtotime($intervencion->fecha_probable)) : '' }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="col-md-2">
                            <label for="">TURNOS QUIRURGICOS</label>
                        </div>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-2">
                                <input type="text" name="id_prioridad_programacion" id="id_prioridad_programacion"
                                    class="form-control" style="width: 105px;" oninput="filtrarNumero(this)"
                                    value="{{ !empty($intervencion->id_prioridad_programacion) ? $intervencion->id_prioridad_programacion : 1 }}">
                            </div>
                            <div class="col-md-2">
                                <label for="">TIPO DE CIRUGIA</label>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <select name="id_tipo_cirugia" id="id_tipo_cirugia" class="form-control">
                                    <option value="0">Seleccione</option>
                                    @foreach ($tipo_cirugias as $row)
                                        <option value="{{ $row->id }}"
                                            @if ($row->id == $intervencion->emergencia_tipo_cirugia) selected @endif>{{ $row->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-md-2">
                                <p>{{ !empty($intervencion->id_prioridad_programacion) ? $intervencion->id_prioridad_programacion : '0' }}
                                </p>
                            </div>
                            <div class="col-md-2">
                                <label for="">TIPO DE CIRUGIA</label>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <p>{{ $intervencion->tipo_cirugia }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                            <label>REQUIERE INTERNAMIENTO PRE QX</label>
                        </div>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-2" style="margin-left:0px;padding-left:0px">
                                <button tabindex="100" type="button" id="addRequiereInternamientoPreQx"
                                    class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                                    {{ !empty($datos_interconsulta) && $datos_interconsulta->id_tipo_atencion == 3 ? 'disabled' : '' }}
                                    autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" id="has_requiere_internamiento_preqx"
                                    name="has_requiere_internamiento_preqx" value="0">
                            </div>
                            <div class="col-md-2">
                                <fieldset id="fsRequiereInternamientoPreQx">
                                    <select name="id_tiempo_internamiento_preqx" id="id_tiempo_internamiento_preqx"
                                        class="form-control input-sm">
                                        <option value="0">Seleccione</option>
                                        @foreach ($cqx_dias as $row)
                                            @if ($row->flag == 2)
                                                <option value="{{ $row->id }}"
                                                    @if ($intervencion->is_requiere_internamiento_preqx) @if ($intervencion->id_tiempo_internamiento_preqx == $row->id) selected @endif
                                                    @endif>{{ $row->descripcion }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        @else
                            @if ($intervencion->is_requiere_internamiento_preqx)
                                <div class="col-md-2">
                                    <p>SI</p>
                                </div>
                                <div class="col-md-2">
                                    <p>{{ $intervencion->tiempo_internamiento_preqx }}</p>
                                </div>
                            @else
                                <div class="col-md-2">NO</div>
                            @endif
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                            <label>DIAS DE HOSPITALIZADO POST QX</label>
                        </div>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-2" style="margin-left:0px;padding-left:0px">
                                <button tabindex="100" type="button" id="addDiasHospitalizadoPostQx"
                                    class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                                    {{ !empty($datos_interconsulta) && $datos_interconsulta->id_tipo_atencion == 3 ? 'disabled' : '' }}
                                    autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" id="is_requiere_hospitalizacion_postqx"
                                    name="is_requiere_hospitalizacion_postqx" value="0">
                            </div>
                            <div class="col-md-2">
                                <fieldset id="fsDiasHospitalizadoPostQx">
                                    <select name="id_tiempo_hospitalizacion_postqx" id="id_tiempo_hospitalizacion_postqx"
                                        class="form-control input-sm">
                                        <option value="0">Seleccione</option>
                                        @foreach ($cqx_dias as $row)
                                            @if ($row->flag == 3)
                                                <option value="{{ $row->id }}"
                                                    @if ($intervencion->is_hospitalizacion_postqx) @if ($intervencion->id_tiempo_hospitalizacion_postqx == $row->id) selected @endif
                                                    @endif>{{ $row->descripcion }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        @else
                            @if ($intervencion->is_hospitalizacion_postqx)
                                <div class="col-md-2">
                                    <p>SI</p>
                                </div>
                                <div class="col-md-2">
                                    <p>{{ $intervencion->tiempo_hospitalizacion_postqx }}</p>
                                </div>
                            @else
                                <div class="col-md-2">
                                    <p>NO</p>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-2">
                            <label for="">FECHA DE INTERVENCIÓN:</label>
                        </div>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-2">
                                @if ($intervencion->es_nuevo_admision_hospitalizacion)
                                    <input type="text" class="form-control datepicker input-sm" id="fecha_intervencion"
                                        name="fecha_intervencion" readonly
                                        value="{{ \Carbon\Carbon::parse($fecha_registro_admision)->format('d-m-Y') }}">
                                @else
                                    <input tabindex="6" type="date" class="form-control" name="fecha_intervencion"
                                        id="fecha_intervencion"
                                        value="{{ \Carbon\Carbon::parse($intervencion->fecha_probable)->format('Y-m-d') }}"
                                        style="text-align:left;">
                                @endif
                            </div>
                        @else
                            <div class="col-md-2">
                                <p>{{ date('d-m-Y', strtotime($intervencion->fecha_intervencion)) }}</p>
                            </div>
                        @endif
                        <div id="electiva_div" style="display: none;">
                            <div class="col-md-1" style="text-align:right;">
                                <label for="">HORA DE INICIO:</label>
                            </div>
                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <div class="col-md-1">
                                    <input type="time" class="form-control input-sm" name="hora_inicio_intervencion"
                                        id="hora_inicio_intervencion" value="{{ $intervencion->hora_inicio_prog_qx }}">
                                </div>
                            @else
                                <div class="col-md-1">
                                    <p>{{ date('H:i', strtotime($intervencion->hora_inicio_prog_qx)) }}</p>
                                </div>
                            @endif
                            <div class="col-md-1" style="text-align:right;">
                                <label for="">HORA DE FIN:</label>
                            </div>
                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <div class="col-md-1">
                                    <input type="time" class="form-control input-sm" name="hora_fin_intervencion"
                                        id="hora_fin_intervencion" value="{{ $intervencion->hora_fin_prog_qx }}">
                                </div>
                            @else
                                <div class="col-md-1">
                                    <p>{{ date('H:i', strtotime($intervencion->hora_fin_prog_qx)) }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-3" style="text-align:right;">
                            <label for="">DURACIÓN DE LA CIRUGIA:</label>
                        </div>
                        <div class="col-md-2">
                            <input type="time" id="duracion_cirugia_calculada" class="form-control" style="width: 105px;"
                                disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2">
                            <label for="">SALA DE INTERVENCIÓN:</label>
                        </div>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-2">
                                <select name="id_sala_intervencion" id="id_sala_intervencion" class="form-control input-sm">
                                    <option value="0">Seleccione</option>
                                    @foreach ($cq_salas as $row)
                                        <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-md-2">
                                <p>{{ $intervencion->sala_intervencion }}</p>
                            </div>
                        @endif
                        <div class="col-md-2" style="text-align:right;">
                            <label for="">TIPO DE ANESTESIA:</label>
                        </div>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-6">
                                <select name="id_tipo_anestesia" id="id_tipo_anestesia" class="form-control input-sm">
                                    <option value="0">Seleccione</option>
                                    @foreach ($tipos_anestesia as $row)
                                        <option value="{{ $row->id }}">{{ $row->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-md-6">
                                <p>{{ $intervencion->tipo_anestesia }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                @if (!$is_origen_emergencia && !$es_traslado_hospitalizacion_acqx)
                    @if ($intervencion->es_nuevo_admision_hospitalizacion)
                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                                <div class="panel-title text-center">
                                    <b>DIAGNOSTICO DE INTERVENCIÓN</b>
                                </div>
                            </div>
                            <div class="panel-body">
                                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                    <table>
                                        <tr>
                                            <td>
                                                <button type="button" id="addDiagnosticoQuirurgico"
                                                    class="btn btn-primary" data-toggle="modal"
                                                    data-target="#addClassModal">Agregar
                                                    Diagnostico</button>
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                                <br />
                                <table id="tblDiagnosticoQuirurgico" cellspacing="0" width="100%"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="50%">NOMBRE</th>
                                            <th width="15%">TIPO</th>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($diagnosticos_cqx as $key => $row)
                                            <tr>
                                                <?php $tipo = '';
                                                switch ($row->id_tipo_diagnostico):
                                                    case 1:
                                                        $tipo = 'PRESUNTIVO';
                                                        break;
                                                    case 2:
                                                        $tipo = 'DEFINITIVO';
                                                        break;
                                                    case 3:
                                                        $tipo = 'REITERATIVO';
                                                        break;
                                                endswitch;
                                                ?>
                                                <td style="width:60%; 1px solid gray;">
                                                    <p>{{ $row->codigo_diagnostico }} -
                                                        {{ $row->descripcion_diagnostico }}</p>
                                                </td>
                                                <td style="width:10%; 1px solid gray;">{{ $tipo }}</td>
                                                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                                @endif
                                                <?php $i++; ?>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    @if (!$intervencion->es_nuevo_admision_hospitalizacion)
                        <div class="panel panel-info panel-sm">
                            <div class="panel-heading">
                                <div class="panel-title text-center"><b>PROCEDIMIENTOS DE INTERVENCIÓN</b></div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <table class="display" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Código y Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($procedimientos as $p)
                                                    <tr>
                                                        <td>{{ $p->codigo_cpms }} - {{ $p->descripcion_cpms }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Incio PROFESIONALES QUE INTERVIENEN -->
                    <div class="panel panel-info panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center"><b>PROFESIONALES QUE INTERVIENEN</b></div>
                        </div>
                        <div class="panel-body">
                            <!-- INICIO CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN -->
                            @if (!$is_origen_emergencia)
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <table class="display" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Apellidos y Nombres</th>
                                                    <th style="width:30%">Cargo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($profesionales_quirugicos as $pq)
                                                    <tr>
                                                        <td>{{ $pq->paterno }} {{ $pq->materno }},
                                                            {{ $pq->nombre }}</td>
                                                        <td>{{ $pq->tipo_profesional }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <div class="col-sm-2">
                                        <button tabindex="75" type="button" id="addMedicoCirujano"
                                            class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#addClassModal">Agregar Cirujano que intervienen en la
                                            operación</button>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <select id="cmbProfesionalesCirujanosTodosTemp">
                                        <option value="">--Seleccionar--</option>
                                        @foreach ($lista_profesionales_cirujanos as $pa)
                                            <option value="{{ $pa->id }}">{{ $pa->paterno }}
                                                {{ $pa->materno }}, {{ $pa->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <select id="cmbTipoProfesionalCirujanoTemp">
                                        <option value="0">--Seleccionar--</option>
                                        @foreach ($cqx_tipo_profesionales as $tp)
                                            @if ($tp->flag_profesional == 1)
                                                <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <table id="table_medico_cirujano" class="display" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Apellidos y Nombres</th>
                                                    <th style="width:30%;padding-left:20px;">Cargo</th>
                                                    <th style="width:10%;padding-left:30px;">Eliminar</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <!-- FIN CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN -->

                            <!-- INICIO ANESTESIOLOGOS QUE INTERVIENEN EN LA OPERACIÓN -->
                            <hr>
                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)

                                <div class="form-group">
                                    <div class="col-sm-2">
                                        <button tabindex="75" type="button" id="addMedicoAnestesiologo"
                                            class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#addClassModal">Agregar Anestesiólogo que intervienen en la
                                            operación</button>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <select id="cmbProfesionalesAntesiologiaTemp">
                                        <option value="">--Seleccionar--</option>
                                        @foreach ($lista_profesionales_anestesiologos as $pa)
                                            <option value="{{ $pa->id }}">{{ $pa->paterno }}
                                                {{ $pa->materno }}, {{ $pa->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <select id="cmbTipoProfesionalAnestesiologoTemp">
                                        <option value="0">--Seleccionar--</option>
                                        @foreach ($cqx_tipo_profesionales as $tp)
                                            @if ($tp->flag_profesional == 2)
                                                <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <table id="table_medico_anestesiologo" class="display" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Apellidos y Nombres</th>
                                                    <th style="width:30%">Cargo</th>
                                                    <th style="width:10%">Eliminar</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <table class="display" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Apellidos y Nombres</th>
                                                    <th style="width:30%">Cargo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($profesionales_anestesiologos as $pq)
                                                    <tr>
                                                        <td>{{ $pq->paterno }} {{ $pq->materno }},
                                                            {{ $pq->nombre }}</td>
                                                        <td>{{ $pq->tipo_profesional }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <!-- FIN ANESTESIOLOGOS QUE INTERVIENEN EN LA OPERACIÓN -->
                        </div>
                    </div>
                    <!-- Fin PROFESIONALES QUE INTERVIENEN -->
                @endif
                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                    <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <div class="form-group">
                            <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Sugerencia</label>
                            </div>
                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <input type="hidden" id="is_sugerencia_medica" name="is_sugerencia_medica"
                                    value="0">
                                <button tabindex="110" type="button" id="addSugerenciaMedica"
                                    class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                                    autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                            </div>
                        </div>
                        <div id="fsSugerenciaMedica" style="display: none">
                            <fieldset class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <textarea class="form-control" id="sugerencia_medica" name="sugerencia_medica" rows="4" cols="50"></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                @else
                    <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <div class="form-group">
                            <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Sugerencia</label>
                            </div>
                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <button tabindex="110" type="button"
                                    class="btn btn-lg btn-toggle {{ $intervencion->is_sugerencia_medica ? 'active' : '' }}"
                                    data-toggle="button"
                                    aria-pressed="{{ $intervencion->is_sugerencia_medica ? 'true' : 'false' }}"
                                    autocomplete="off" disabled>
                                    <div class="handle"></div>
                                </button>
                            </div>
                        </div>
                        @if ($intervencion->is_sugerencia_medica)
                            <fieldset class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <textarea class="form-control" rows="4" cols="50" disabled style="white-space: pre-wrap; resize: none;">{{ !empty($intervencion->sugerencia_medica) ? $intervencion->sugerencia_medica : '' }}</textarea>
                                    </div>
                                </div>
                            </fieldset>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Cerrar
                </button>
                @if (!$intervencion->is_validated)
                <button type="button" class="btn btn-success" id="btnValidar">
                    Validar
                </button>
                @endif
            </div>
        </div>
    </form>
</div>  