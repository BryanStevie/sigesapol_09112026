<div id="blur-container">
    <div style=" font-size: 11px" class="panel-body">
        <form id="formIntraoperario2" method="POST" action="{{ route('actualizar_intraoperatorio') }}">
            <input type="hidden" name="_token" value="{{-- {{ csrf_token() }} --}}">
            <input type="hidden" name="id_asegurado" id="id_asegurado" value="{{-- {{ $datos_paciente->id_asegurado }} --}}">
            <input type="hidden" name="id_intervencion" id="id_intervencion" value="{{-- {{ $datos_paciente->id_intervencion }} --}}">
            <input type="hidden" name="id_nivel" id="id_nivel" value="{{-- <?php echo $nivel; ?> --}}">
            <input type="hidden" name="id_intraoperatorio" id="id_intraoperatorio" value="{{-- {{ $intra_operatorio->id }} --}}">
            <input type="hidden" name="id_prestacion" value="{{-- {{ $datos_paciente->id_prestacion }} --}}">
            <input type="hidden" name="tipo_real" id="tipo_real">

            <div class="panel panel-info contenedor-datos-generales">
                <div class="panel-heading">
                    <h3 class="panel-title size-titulo">
                        DATOS DE LA ATENCIÓN
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row intro-contenedor">
                        <div class="col-lg-3">
                            <label>TIPO DE ATENCION</label>
                            <input type="text" readonly="" value="HOSPITALIZACIÓN" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label for="">SERVICIO</label>
                            <input type="text" name="servicio" class="form-control" style="text-align:left"
                                value="{{-- {{ $datos_paciente->servicio }} --}}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="">SALA</label>
                            <input type="text" name="sala" class="form-control" style="text-align:left"
                                value="{{-- {{ $datos_paciente->sala }} --}}" readonly>
                        </div>
                        <div class="row intro-contenedor">
                            <div class="col-lg-2">
                                <label>FECHA DE REGISTRO</label>
                                <input type="date" class="form-control" value="{{-- {{ !empty($datos_paciente->fecha_registro)
                                        ? date('Y-m-d', strtotime($datos_paciente->fecha_registro))
                                        : date('Y-m-d') }} --}}"
                                    style="height: 28px !important; font-size: 12px !important" name="fecha_registro"
                                    id="fecha_registro" autocomplete="off">
                            </div>
                            <div class="col-lg-2">
                                <label>HORA DE REGISTRO</label>
                                <input type="time" class="form-control" name="hora_registro" id="hora_registro"
                                    value="{{-- {{ !empty($datos_paciente->hora_registro)
                                        ? date('H:i', strtotime($datos_paciente->hora_registro))
                                        : date('H:i') }} --}}">
                            </div>
                            <div class="col-lg-2">
                                <label>CÓDIGO UPSS</label>
                                <input type="text" class="form-control" readonly="" name="codigo_upss"
                                    id="codigo_upss" value="{{-- {{ $datos_paciente->codigo_upss }} --}}">
                            </div>
                            <div class="col-lg-3">
                                <label>NOMBRE DE LA UPSS</label>
                                <input type="text" class="form-control" readonly="" name="nombre_upss"
                                    id="nombre_upss" value="{{-- {{ $datos_paciente->nombre_upss }} --}}">
                            </div>
                            <div class="col-lg-5">
                                <label>NOMBRE DE LA IPRESS</label>
                                <input type="text" class="form-control" readonly="" value="{{-- {{ $datos_paciente->codigo_ipress }} - {{ $datos_paciente->establecimiento }} --}}"
                                    name="nombre_ipress" id="nombre_ipress">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">
                            INFORMACIÓN DEL PACIENTE
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row intro-contenedor">
                            <div class="col-lg-2">
                                <label>DOCUMENTO DE IDENTIDAD</label>
                                <input type="text" readonly="" value="{{-- {{ $datos_paciente->tipo_documento }}: {{ $datos_paciente->dni }} --}}"
                                    name="documento_identidad" id="documento_identidad" class="form-control">
                            </div>
                            <div class="col-lg-2">
                                <label class="control-label">APELLIDO PATERNO</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->asegurado_paterno }} --}}" name="asegurado_apellido_paterno"
                                    id="asegurado_apellido_paterno">
                            </div>
                            <div class="col-lg-2">
                                <label class="control-label">APELLIDO MATERNO</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->asegurado_materno }} --}}" name="asegurado_apellido_materno"
                                    id="asegurado_apellido_materno">
                            </div>
                            <div class="col-lg-3">
                                <label class="control-label">NOMBRES</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->asegurado_nombre }} --}}" name="asegurado_nombres" id="asegurado_nombres">
                            </div>
                            <div class="col-lg-3">
                                <label>PARENTESCO</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->parentesco }} --}}" name="parentesco" id="parentesco">
                            </div>
                        </div>
                        <div class="row intro-contenedor">
                            <div class="col-lg-1">
                                <label>F. DE NACIMIENTO</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ date('d/m/Y', strtotime($datos_paciente->asegurado_fecha_nacimiento)) }} --}}" name="fecha_nacimiento" id="fecha_nacimiento">
                            </div>
                            <div class="col-lg-1">
                                <label>CIP</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->cip }} --}}" name="cip" id="cip">
                            </div>
                            <div class="col-lg-2">
                                <label>GRADO</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->grado }} --}}" name="grado" id="grado">
                            </div>
                            <div class="col-lg-2">
                                <label>SITUACIÓN</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->situacion }} --}}" name="" id="situacion">
                            </div>
                            <div class="col-lg-1">
                                <label>SEXO</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->sexo }} --}}" name="sexo" id="sexo">
                            </div>
                            <div class="col-lg-1">
                                <label>EDAD</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->asegurado_edad_dias }} --}}" name="edad" id="edad">
                            </div>
                            <div class="col-lg-1">
                                <label>T. SANGRE</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->tipo_sangre }} --}}" name="tipo_sangre" id="tipo_sangre">
                            </div>
                            <div class="col-lg-3">
                                <label>DIRECCIÓN</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{-- {{ $datos_paciente->domicilio }} --}}" name="direccion" id="direccion">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info contenedor-datos-generales">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">
                            PROFESIONALES QUE INTERVIENEN EN LA OPERACIÓN
                        </h3>
                    </div>
                    <div class="panel-body">

                        <br />
                        <div class="row intro-contenedor">
                            <div class="col-lg-3" style="padding-bottom: 1rem;">
                                <button type="button" class="btn btn-success btn-xs" style="width: 100%;"
                                    id="addCirujanoIntraoperatorio2">Cirujanos que Intervienen Operación</button>
                            </div>
                            <div class="col-lg-12">
                                <table class="table table-bordered" cellspacing="0" width="100%" id="tblCirujano2"
                                    cellspacing="0" width="100%" style="margin-top:5px;">
                                    <tbody>
                                        {{-- @if (!empty($cirujanos))
                                            @foreach ($cirujanos as $key => $row)
                                                <?php
                                                $isEliminadoCirujano = !empty(old('id_cirujano_anulado.' . $key)) || $row->estado == 3;
                                                ?>
                                                <tr class="<?php echo $isEliminadoCirujano ? 'eliminado' : ''; ?>">
                                                    <td>
                                                        {{ $row->nombre }} {{ $row->paterno }} {{ $row->materno }}
                                                    </td>
                                                    <td>
                                                        {{ $row->descripcion }}
                                                    </td>
                                                    @if ($row->estado == 0 || $row->estado == 1)
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger AnularFilaCirujano btn-xs"
                                                                data-id_cirujano_registro="{{ $row->id }}"
                                                                data-toggle="modal" style="margin-left:4px">
                                                                <i class="fa fa-times"></i> Eliminar
                                                            </button>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row intro-contenedor">
                            <div class="col-lg-3" style="padding-bottom: 1rem;">
                                <button type="button" class="btn btn-success btn-xs" style="width: 100%;"
                                    id="addAnestesiologo2">Anestesiologos que Intervienen Operación</button>
                            </div>
                            <div class="col-lg-12">
                                <table class="table table-bordered" id="tblAnestesiologo2" cellspacing="0"
                                    width="100%" style="margin-top:5px;">
                                    <tbody>
                                        {{-- @if (!empty($anestesiologos))
                                            @foreach ($anestesiologos as $key => $row)
                                                <?php
                                                $isEliminadoAnestesiologo = !empty(old('id_anestesiologo_anulado.' . $key)) || $row->estado == 3;
                                                ?>
                                                <tr class="<?php echo $isEliminadoAnestesiologo ? 'eliminado' : ''; ?>">
                                                    <td>
                                                        {{ $row->nombre }} {{ $row->paterno }} {{ $row->materno }}
                                                    </td>
                                                    <td>
                                                        {{ $row->descripcion }}
                                                    </td>
                                                    @if ($row->estado == 0 || $row->estado == 1)
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger AnularFilaAnestesiologos btn-xs"
                                                                data-id_anestesiologo_registro="{{ $row->id }}"
                                                                data-toggle="modal" style="margin-left:4px">
                                                                <i class="fa fa-times"></i> Eliminar
                                                            </button>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row intro-contenedor">
                            <div class="col-lg-3" style="padding-bottom: 1rem;">
                                <button type="button" class="btn btn-success btn-xs" style="width: 100%;"
                                    id="addEnfermeriaInstrumentista2">Enf. Instrumentista Intervienen
                                    Operación</button>
                            </div>
                            <div class="col-lg-12">
                                <table class="table table-bordered" id="tblEnfermeriaInstrumentista2" cellspacing="0"
                                    width="100%" style="margin-top:5px;">
                                    <tbody>
                                        {{-- @if (!empty($instrumentistas))
                                            @foreach ($instrumentistas as $key => $row)
                                                <?php
                                                $isEliminadoInstrumentista = !empty(old('id_instrumentista_anulado.' . $key)) || $row->estado == 3;
                                                ?>
                                                <tr class="<?php echo $isEliminadoInstrumentista ? 'eliminado' : ''; ?>">
                                                    <td>
                                                        {{ $row->nombre }} {{ $row->paterno }} {{ $row->materno }}
                                                    </td>
                                                    <td>
                                                        {{ $row->descripcion }}
                                                    </td>
                                                    @if ($row->estado == 0 || $row->estado == 1)
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger AnularFilaInstrumentista btn-xs"
                                                                data-id_instrumentista_registro="{{ $row->id }}"
                                                                data-toggle="modal" style="margin-left:4px">
                                                                <i class="fa fa-times"></i> Eliminar
                                                            </button>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1default_edit" data-toggle="tab" style="font-size:12px">REGISTRO
                            DE PACIENTE EN CENTRO QUIRÚRGICO</a></li>
                    <li><a href="#tab2default_edit" data-toggle="tab" style="font-size:12px">DIAGNÓSTICO DE
                            ENFERMERÍA</a></li>
                    <li><a href="#tab3default_edit" data-toggle="tab" style="font-size:12px">BALANCE DE FLUIDOS</a>
                    </li>
                    <li><a href="#tab4defaultintraop_edit" data-toggle="tab" style="font-size:12px">RECUENTO DE
                            GASAS</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default_edit">
                        <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">RECEPCIÓN DEL PACIENTE</label>
                                                </div>
                                                <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                    <input type="text" id="recepcion_paciente_descripcion"
                                                        class="form-control" name="recepcion_paciente_descripcion"
                                                        value="{{-- {{ isset($intra_operatorio) && isset($intra_operatorio->recepcion_paciente_descripcion) ? $intra_operatorio->recepcion_paciente_descripcion : '' }} --}}">
                                                </div>
                                                <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                    <input type="time" class="form-control" style="width: 100%;"
                                                        id="recepcion_paciente_tiempo"
                                                        name="recepcion_paciente_tiempo" min="08:00"
                                                        max="18:00" value="{{-- {{ isset($intra_operatorio->recepcion_paciente_tiempo)
                                                            ? date('H:i', strtotime($intra_operatorio->recepcion_paciente_tiempo))
                                                            : '' }} --}}" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">INICIO DE ANESTESIA</label>
                                                </div>
                                                <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                    <input type="time" class="form-control" style="width: 100%;"
                                                        id="anestesia_inicio" name="anestesia_inicio" min="08:00"
                                                        max="18:00" value="{{-- {{ isset($intra_operatorio->anestesia_inicio) ? date('H:i', strtotime($intra_operatorio->anestesia_inicio)) : '' }} --}}" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">INICIO DE CIRUGÍA</label>
                                                </div>
                                                <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                    <input type="time" class="form-control" style="width: 100%;"
                                                        id="cirugia_inicio-edit" name="cirugia_inicio" min="08:00"
                                                        max="18:00" value="{{-- {{ isset($intra_operatorio->anestesia_inicio) ? date('H:i', strtotime($intra_operatorio->anestesia_inicio)) : '' }} --}}" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">SALIDA DE PACIENTE</label>
                                                </div>
                                                <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                    <input type="time" class="form-control" style="width: 100%;"
                                                        id="salida_paciente" name="salida_paciente" min="08:00"
                                                        max="18:00" value="{{-- {{ isset($intra_operatorio->salida_paciente) ? date('H:i', strtotime($intra_operatorio->salida_paciente)) : '' }} --}}" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">HORA DE LLEGADA URPA</label>
                                                </div>
                                                <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                    <input type="time" class="form-control" style="width: 100%;"
                                                        id="hora_llegada_urpa" name="hora_llegada_urpa"
                                                        min="08:00" max="18:00"
                                                        value="{{-- {{ isset($intra_operatorio->hora_llegada_urpa) ? date('H:i', strtotime($intra_operatorio->hora_llegada_urpa)) : '' }} --}}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">TIPO DE ANESTESIA</label>
                                                </div>
                                                <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                    <select id="tipo_anestesia" name="tipo_anestesia"
                                                        class="form-control" style="width: 100%">
                                                        <option value="">Seleccionar</option>
                                                        {{-- @foreach ($tipo_anestesia as $valor)
                                                            <option value="{{ $valor->id }}"
                                                                {{ isset($intra_operatorio->id_cqx_tipo_anestesia) && $intra_operatorio->id_cqx_tipo_anestesia == $valor->id ? 'selected' : '' }}>
                                                                {{ $valor->descripcion }}</option>
                                                        @endforeach; --}}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">TÉRMINO DE ANESTESIA</label>
                                                </div>
                                                <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                    <input type="time" class="form-control" style="width: 100%;"
                                                        id="anestesia_termino" name="anestesia_termino"
                                                        min="08:00" max="18:00"
                                                        value="{{-- {{ isset($intra_operatorio->anestesia_termino) ? date('H:i', strtotime($intra_operatorio->anestesia_termino)) : '' }} --}}" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">TÉRMINO DE CIRUGÍA</label>
                                                </div>
                                                <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                    <input type="time" class="form-control" style="width: 100%;"
                                                        id="cirugia_termino-edit" name="cirugia_termino" min="08:00"
                                                        max="18:00" value="{{-- {{ isset($intra_operatorio->cirugia_termino) ? date('H:i', strtotime($intra_operatorio->cirugia_termino)) : '' }} --}}" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">TIEMPO REAL</label>
                                                </div>
                                                <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                    <input type="time" class="form-control" style="width: 100%;"
                                                        id="tipo_real2" name="tipo_real2" min="08:00"
                                                        max="18:00" value="{{-- {{ isset($intra_operatorio->tipo_real) ? date('H:i', strtotime($intra_operatorio->tipo_real)) : '' }} --}}" disabled />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">OPERACIÓN PROGRAMADA</label>
                                                    <input type="hidden" name="tipo_operacion"
                                                        value="Operación Programada">
                                                </div>
                                                <div class="col-lg-8" style="padding-bottom: 1rem;">
                                                    <input type="text" class="form-control"
                                                        name="operacion_programada" id="operacion_programada"
                                                        maxlength="250" value="{{-- {{ isset($intra_operatorio->operaciones_programadas) ? $intra_operatorio->operaciones_programadas : '' }} --}}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">OPERACIÓN EMERGENCIA</label>
                                                    <input type="hidden" name="tipo_operacion"
                                                        value="Operación de emergencia">
                                                </div>
                                                <div class="col-lg-8" style="padding-bottom: 1rem;">
                                                    <input type="text" class="form-control"
                                                        name="operacion_emergencia" id="operacion_emergencia"
                                                        maxlength="250" value="{{-- {{ isset($intra_operatorio->operaciones_emergencia) ? $intra_operatorio->operaciones_emergencia : '' }} --}}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4"
                                                    style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                    <label style="margin-left: 2rem;">OPERACIÓN REALIZADA</label>
                                                </div>
                                                <div class="col-lg-8" style="padding-bottom: 1rem;">
                                                    <input type="text" class="form-control"
                                                        name="operacion_realizada" id="operacion_realizada"
                                                        maxlength="250" value="{{-- {{ isset($intra_operatorio->operaciones_realizadas) ? $intra_operatorio->operaciones_realizadas : '' }} --}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 1rem;">
                                            <div class="col-lg-2"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">COMPLICACIONES</label>
                                            </div>
                                            <div class="col-lg-8"
                                                style="align-content: center; padding-bottom: 1rem;">
                                                <textarea class="form-control" cols="5" id="complicaciones" name="complicaciones"
                                                    placeholder="Descripción de las complicaciones">{{-- {{ isset($intra_operatorio->complicaciones) ? $intra_operatorio->complicaciones : '' }} --}}</textarea>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 1rem;">
                                            <div class="col-lg-2"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">CAJAS DE INSTRUMENTAL</label>
                                            </div>
                                            <div class="col-lg-2"
                                                style="align-content: center; padding-bottom: 1rem;">
                                                <input type="text" class="form-control"
                                                    name="cajas_instrumental_descripcion"
                                                    id="cajas_instrumental_descripcion"
                                                    value="{{-- {{ isset($intra_operatorio->cajas_instrumental_descripcion) ? $intra_operatorio->cajas_instrumental_descripcion : '' }} --}}" />
                                            </div>
                                            <div class="col-lg-2"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">DISPONIBILIDAD DE</label>
                                            </div>
                                            <div class="col-lg-2"
                                                style="align-content: center; padding-bottom: 1rem;">
                                                <input type="hidden" name="cajas_instrumental_equipo"
                                                    value="0">
                                                <input type="checkbox" id="cajas_instrumental_equipo"
                                                    name="cajas_instrumental_equipo" value="1"
                                                    {{-- {{ isset($intra_operatorio) && $intra_operatorio->cajas_instrumental_equipo ? 'checked' : '' }} --}}><span class="checkboxtexto"
                                                    style="margin-left: 2rem; font-weight: 700;">EQUIPOS</span>
                                            </div>
                                            <div class="col-lg-2"
                                                style="align-content: center; padding-bottom: 1rem;">
                                                <input type="hidden" name="cajas_instrumental_insumos"
                                                    value="0">
                                                <input type="checkbox" id="cajas_instrumental_insumos"
                                                    name="cajas_instrumental_insumos" value="1"
                                                    {{-- {{ isset($intra_operatorio) && $intra_operatorio->cajas_instrumental_insumos ? 'checked' : '' }} --}}><span class="checkboxtexto"
                                                    style="margin-left: 2rem; font-weight: 700;">INSUMOS</span>
                                            </div>
                                            <div class="col-lg-2"
                                                style="align-content: center; padding-bottom: 1rem;">
                                                <input type="hidden" name="cajas_instrumental_instrumental"
                                                    value="0">
                                                <input type="checkbox" id="cajas_instrumental_instrumental"
                                                    name="cajas_instrumental_instrumental" value="1"
                                                    {{-- {{ isset($intra_operatorio) && $intra_operatorio->cajas_instrumental_instrumental ? 'checked' : '' }} --}}><span class="checkboxtexto"
                                                    style="margin-left: 2rem; font-weight: 700;">INSTRUMENTAL</span>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 1rem;">
                                            <div class="col-lg-2"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">ENF I Y ENF II VERIFICA Y
                                                    CONTROLA</label>
                                            </div>
                                            <div class="col-lg-2"
                                                style="align-content: center; padding-bottom: 1rem;">
                                                <input type="hidden" id="enf1_enf2_vc_insumos"
                                                    name="enf1_enf2_vc_insumos" value="0">
                                                <input type="checkbox" id="enf1_enf2_vc_insumos"
                                                    name="enf1_enf2_vc_insumos" value="1"
                                                    {{-- {{ isset($intra_operatorio) && $intra_operatorio->enf1_enf2_vc_insumos ? 'checked' : '' }} --}}><span class="checkboxtexto"
                                                    style="margin-left: 2rem; font-weight: 700;">INSUMOS</span>
                                            </div>
                                            <div class="col-lg-2"
                                                style="align-content: center; padding-bottom: 1rem;">
                                                <input type="hidden" id="enf1_enf2_vc_instrumental"
                                                    name="enf1_enf2_vc_instrumental" value="0">
                                                <input type="checkbox" id="enf1_enf2_vc_instrumental"
                                                    name="enf1_enf2_vc_instrumental" value="1"
                                                    {{-- {{ isset($intra_operatorio) && $intra_operatorio->enf1_enf2_vc_instrumental ? 'checked' : '' }} --}}><span class="checkboxtexto"
                                                    style="margin-left: 2rem; font-weight: 700;">INSTRUMENTAL</span>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 1rem;">
                                            <div class="col-lg-2"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">MAQUINA DE ANESTESIA</label>
                                            </div>
                                            <div class="col-lg-3" style="padding-bottom: 1rem;">
                                                <input type="text" class="form-control"
                                                    name="maquina_anestesia_descripcion"
                                                    id="maquina_anestesia_descripcion"
                                                    value="{{-- {{ isset($intra_operatorio->maquina_anestesia_descripcion) ? $intra_operatorio->maquina_anestesia_descripcion : '' }} --}}" />
                                            </div>
                                            <div class="col-lg-2"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">OXIGENO</label>
                                            </div>
                                            <div class="col-lg-3" style="padding-bottom: 1rem;">
                                                <input type="text" class="form-control" name="oxigeno_descripcion"
                                                    id="oxigeno_descripcion" value="{{-- {{ isset($intra_operatorio->oxigeno_descripcion) ? $intra_operatorio->oxigeno_descripcion : '' }} --}}" />
                                            </div>
                                        </div>

                                        <div class="row" style="padding-bottom: 1rem;">
                                            <div class="col-lg-2"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">COMENTARIOS</label>
                                            </div>
                                            <div class="col-lg-8"
                                                style="align-content: center; padding-bottom: 1rem;">
                                                <textarea class="form-control" cols="5" id="comentarios" name="comentarios" placeholder="Comentarios">{{-- {{ isset($intra_operatorio->comentarios) ? $intra_operatorio->comentarios : '' }} --}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade" id="tab2default_edit">
                        <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-lg-6" style="display: flex; justify-content: center;">
                                            <div
                                                style="width: 80%; background-color: rgb(250, 250, 250); border: 1px solid rgb(226, 226, 226);">
                                                <div
                                                    style="border-bottom: 1px solid rgb(226, 226, 226); padding: 1rem 0;">
                                                    <p style="margin: 0; text-align: center; font-weight: 700;">
                                                        DIAGNÓSTICO DE ENFERMERIA</p>

                                                    <div id="diagnosticoError" style="color: red;"></div>

                                                </div>
                                                {{-- @foreach ($diagnosticos_enfermeria as $key => $diagnostico)
                                                    <div
                                                        style="display: flex; justify-content: space-between; border-bottom: 1px solid rgb(226, 226, 226); padding: 1rem;">

                                                        <div
                                                            style="display: flex; align-items: flex-start; width: 100%;">
                                                            <span
                                                                style="font-weight: 700;">{{ $diagnostico->descripcion }}</span>
                                                            <input type="hidden" id="diagnostico"
                                                                name="diagnostico[]" value="{{ $diagnostico->id }}">
                                                        </div>

                                                        <div
                                                            style="display: flex; align-items: flex-start; justify-content: flex-end; width: 10%;">
                                                            <input type="checkbox" name="diagnostico_estado[]"
                                                                value="{{ $diagnostico->id }}"
                                                                {{ $diagnostico->checked ? 'checked' : '' }}>
                                                        </div>

                                                    </div>
                                                @endforeach --}}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div style="width: 100%; border: 1px solid rgb(226, 226, 226);">
                                                <div
                                                    style="border-bottom: 1px solid rgb(226, 226, 226); padding: 1rem 0;">
                                                    <p style="margin: 0; text-align: center; font-weight: 700;">
                                                        INTERVENCION DE ENFERMERIA (NIC)</p>
                                                    <div id="nicError" style="color: red;"></div>
                                                </div>
                                                {{-- @foreach ($intervencion_enfermeria as $key => $nic)
                                                    <div
                                                        style="border-bottom: 1px solid rgb(226, 226, 226); padding: 1rem 0;">

                                                        <div style="display: flex; padding: 0 1rem;">

                                                            <div style="display: flex; align-items: flex-start;">
                                                                <span style="font-weight: 700; padding-right: 1rem;">
                                                                    {{ $nic->descripcion }}
                                                                </span>

                                                                <input type="hidden" id="nics_padre"
                                                                    name="nics_padre[]" value="{{ $nic->id_padre }}">
                                                            </div>

                                                            <div style="display: flex; flex-direction: column;">

                                                                @if (!empty($nic->hijos))
                                                                    <input type="hidden"
                                                                        name="nic_estado[{{ $nic->id_padre }}]"
                                                                        value="">

                                                                    @foreach ($nic->hijos as $hijo)
                                                                        <div>

                                                                            <input type="hidden" id="nics"
                                                                                name="nics[{{ $nic->id_padre }}][]"
                                                                                value="{{ $hijo['id'] }}">

                                                                            <label>
                                                                                <input type="checkbox" id="nic_estado"
                                                                                    name="nic_estado[{{ $nic->id_padre }}][]"
                                                                                    value="{{ $hijo['id'] }}"
                                                                                    {{ $hijo['checked'] ? 'checked' : '' }}>

                                                                                <span class="checkboxtexto"
                                                                                    style="margin-left: 2rem; font-weight: 700;">
                                                                                    {{ $hijo['descripcion'] }}
                                                                                </span>

                                                                            </label>

                                                                        </div>
                                                                    @endforeach
                                                                @endif

                                                            </div>

                                                        </div>

                                                    </div>
                                                @endforeach --}}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade" id="tab3default_edit">
                        <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row intro-contenedor" style="padding: 0 1rem;">
                                            <div class="row intro-contenedor">
                                                <div class="col-lg-3" style="padding-bottom: 1rem;">
                                                    <button type="button" class="btn btn-success btn-xs"
                                                        style="width: 100%;" id="addBalanceFluidos2">Agregar Balance
                                                        de Fluidos</button>
                                                </div>
                                                <div class="col-lg-12">
                                                    <table class="display" id="tblBalanceFluidos2" cellspacing="0"
                                                        width="100%" style="margin-top:5px;">
                                                        <thead>
                                                            <tr>
                                                                <th width="20%">INGRESO</th>
                                                                <th width="60%">EGRESO</th>
                                                                <th width="20%">TEREAPEUTICA</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="lista_balance_fluidos">
                                                            {{-- @foreach ($balance_fluidos_enfermeria as $i => $bf)
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden"
                                                                            name="id_balance_fluido[]"
                                                                            value="{{ $bf->id }}">
                                                                        <input type="hidden"
                                                                            name="id_balance_fluido_existente[]"
                                                                            value="1">

                                                                        <input type="text" class="form-control"
                                                                            name="balance_ingreso[]"
                                                                            value="{{ $bf->ingreso_fluido }}">
                                                                    </td>

                                                                    <td style="padding-left: 1rem;">
                                                                        <input type="text"
                                                                            class="form-control input-sm"
                                                                            style="text-align:left"
                                                                            name="balance_egreso[]"
                                                                            value="{{ $bf->egreso_fluido }}">
                                                                    </td>

                                                                    <td style="padding-left: 1rem;">
                                                                        <input type="text"
                                                                            class="form-control input-sm"
                                                                            style="text-align:left"
                                                                            name="tareapeutica[]"
                                                                            value="{{ $bf->terapeutica_fluido }}">
                                                                    </td>
                                                                </tr>
                                                            @endforeach --}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade" id="tab4defaultintraop_edit">
                        <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row intro-contenedor" style="padding: 0 1rem;">
                                            <div class="col-lg-12">
                                                <table class="table table-bordered" style="width: 100%;">
                                                    <tr>
                                                        <th style="width: 20%;">&nbsp;</th>
                                                        <th style="width: 20%;">ENFERMERÍA I</th>
                                                        <th style="width: 20%;">ENFERMERÍA II</th>
                                                        <th style="width: 20%;">TOTAL</th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            &nbsp;GASA GRANDE
                                                            <input type="hidden" name="tipo_gasa[]" value="1">
                                                            <input type="hidden" id="id_gasa0" name="id_gasa[]">
                                                        </td>
                                                        <td>
                                                            <input type="number" data-toggle="tooltip"
                                                                data-placement="top" name="enfermeriauno[]"
                                                                id="enfermeriauno0"
                                                                class="form-control ui-autocomplete-input"
                                                                style="height: 28px !important;font-weight: 800;"
                                                                required="" autocomplete="off">
                                                        </td>
                                                        <td>
                                                            <input type="number" data-toggle="tooltip"
                                                                data-placement="top" name="enfermeriados[]"
                                                                id="enfermeriados0"
                                                                class="form-control ui-autocomplete-input"
                                                                style="height: 28px !important;font-weight: 800;"
                                                                required="" autocomplete="off">
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="enfermeriatres[]"
                                                                id="total0">
                                                            <input type="number" data-toggle="tooltip"
                                                                data-placement="top" id="totalx0"
                                                                class="form-control ui-autocomplete-input"
                                                                style="height: 28px !important;font-weight: 800;"
                                                                readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            &nbsp;GASA CHICA
                                                            <input type="hidden" name="tipo_gasa[]" value="2">
                                                            <input type="hidden" id="id_gasa1" name="id_gasa[]">
                                                        </td>
                                                        <td>
                                                            <input type="number" data-toggle="tooltip"
                                                                data-placement="top" name="enfermeriauno[]"
                                                                id="enfermeriauno1"
                                                                class="form-control ui-autocomplete-input"
                                                                style="height: 28px !important;font-weight: 800;"
                                                                required="" autocomplete="off">
                                                        </td>
                                                        <td>
                                                            <input type="number" data-toggle="tooltip"
                                                                data-placement="top" name="enfermeriados[]"
                                                                id="enfermeriados1"
                                                                class="form-control ui-autocomplete-input"
                                                                style="height: 28px !important;font-weight: 800;"
                                                                required="" autocomplete="off">
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="enfermeriatres[]"
                                                                id="total1">
                                                            <input type="number" data-toggle="tooltip"
                                                                data-placement="top" id="totalx1"
                                                                class="form-control ui-autocomplete-input"
                                                                style="height: 28px !important;font-weight: 800;"
                                                                readonly>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">
                            PROCEDIMIENTOS DE INTERVENCIÓN
                        </h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered" id="tblDiagnosticos" cellspacing="0" width="100%"
                            style="margin-top:5px;">
                            <thead>
                                <tr>
                                    <th width="15%">Codigo</th>
                                    <th width="50%">DESCRIPCION</th>
                                </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
                                {{-- @foreach ($procedimientos_intervencion as $pintervencion)
                                    <tr>
                                        <td>{{ $pintervencion->codigo }}</td>
                                        <td>{{ $pintervencion->descripcion }}</td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">
                            DIAGNOSTICO DE LA ATENCION
                        </h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered" id="tblDiagnosticos" cellspacing="0" width="100%"
                            style="margin-top:5px;">
                            <thead>
                                <tr>
                                    <th width="15%">Codigo</th>
                                    <th width="50%">DESCRIPCION</th>
                                    <th width="15%">Tipo</th>
                                    <!-- <th width="15%">Clasificación</th> -->
                                </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody id="diagnosticos_io_edit">

                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- PROCEDIMIENTO --}}
                <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">
                            PROCEDIMIENTOS
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row intro-contenedor">
                                <div class="row intro-contenedor">
                                    <div class="col-lg-3" style="padding-bottom: 1rem;">
                                        <button type="button" class="btn btn-success btn-xs" style="width: 100%;"
                                            id="addProcedimiento2">Agregar Procedimiento</button>
                                    </div>

                                    <div class="col-lg-12">
                                        <table class="display" id="tblProcedimiento2" cellspacing="0" width="100%"
                                            style="margin-top:5px;">
                                            <thead>
                                                <tr>
                                                    <th width="45%">Procedimiento</th>
                                                    <th width="5%">Cantidad</th>
                                                    <th width="45%">Diagnosticos</th>
                                                    <th width="5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding: 1.5rem 0;">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">

                        <input type="button" value="GUARDAR" class="form-control btn btn-success"
                            id="btnGuardarIntraOperatorio2" style="text-align:center!important">
                    </div>
                </div>
        </form>
    </div>
</div>