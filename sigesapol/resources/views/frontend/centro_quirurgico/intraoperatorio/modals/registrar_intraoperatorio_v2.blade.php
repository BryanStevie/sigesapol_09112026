<div id="blur-container">
    <div style=" font-size: 11px" class="panel-body">
        {{-- <form id="formIntraoperario" method="POST" action="{{ route('guardar_intraoperario') }}"> --}}
        <form id="formIntraoperario" method="POST" action="{{ route('guardar_intraoperario_v2') }}">
            <input type="hidden" name="id_asegurado" id="id_asegurado" value="">
            <input type="hidden" name="id_intervencion" id="id_intervencion" value="">
            <input type="hidden" name="id_nivel" id="id_nivel_intra" value="">
            <input type="hidden" name="id_intraoperatorio" id="id_intraoperatorio" value="">
            <input type="hidden" name="id_prestacion" id="id_prestacion" value="">
            <input type="hidden" name="tipo_real" id="tipo_real">
            <input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion" value="">
            <input type="hidden" name="nro_doc_ident" id="nro_doc_ident" value="">
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
                            <input type="text" value="HOSPITALIZACIÓN" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="">SERVICIO</label>
                            <input type="text" id="intra_servicio-reg" value="" class="form-control" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="">SALA</label>
                            <input type="text" id="intra_sala-reg" value="" class="form-control" readonly>
                        </div>
                        <div class="col-lg-2">
                            <label>FECHA DE REGISTRO</label>
                            <input type="date" id="intra_fecha-reg" name="fecha_registro" class="form-control"
                                autocomplete="off">
                        </div>
                        <div class="col-lg-2">
                            <label>HORA DE REGISTRO</label>
                            <input type="time" id="intra_hora-reg" name="hora_registro" class="form-control">
                        </div>
                    </div>
                    <div class="row intro-contenedor">
                        <div class="col-lg-2">
                            <label>COD UPSS</label>
                            <input type="text" id="intra_codigo_upss-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-5">
                            <label>NOMBRE DE LA UPSS</label>
                            <input type="text" id="intra_nombre_upss-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-5">
                            <label>NOMBRE DE LA IPRESS</label>
                            <input type="text" id="intra_datos_ipress-reg" value="" class="form-control"
                                readonly>
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
                            <input type="text" id="intra_doc_ident-reg" value="" class="form-control" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="control-label">APELLIDO PATERNO</label>
                            <input type="text" id="intra_aseg_paterno-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="control-label">APELLIDO MATERNO</label>
                            <input type="text" id="intra_aseg_materno-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-4">
                            <label class="control-label">NOMBRES</label>
                            <input type="text" id="intra_aseg_nombres-reg" value="" class="form-control"
                                readonly>
                        </div>
                    </div>
                    <div class="row intro-contenedor">
                        <div class="col-lg-2">
                            <label>PARENTESCO</label>
                            <input type="text" id="intra_aseg_parentesco-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-2">
                            <label>F. DE NACIMIENTO</label>
                            <input type="text" id="intra_aseg_fechanac-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-2">
                            <label>CIP</label>
                            <input type="text" id="intra_aseg_cip-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-2">
                            <label>SEXO</label>
                            <input type="text" id="intra_aseg_sexo-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-2">
                            <label>EDAD</label>
                            <input type="text" id="intra_aseg_edad-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-2">
                            <label>T. SANGRE</label>
                            <input type="text" id="intra_aseg_tiposangre-reg" value="" class="form-control"
                                readonly>
                        </div>
                    </div>
                    <div class="row intro-contenedor">
                        <div class="col-lg-3">
                            <label>SITUACIÓN</label>
                            <input type="text" id="intra_aseg_situacion-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-3">
                            <label>GRADO</label>
                            <input type="text" id="intra_aseg_grado-reg" value="" class="form-control"
                                readonly>
                        </div>
                        <div class="col-lg-6">
                            <label>DIRECCIÓN</label>
                            <input type="text" id="intra_aseg_direccion-reg" value="" class="form-control"
                                readonly>
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
                    <div class="row intro-contenedor">
                        <div class="col-lg-3" style="padding-bottom: 1rem;">
                            <button type="button" class="btn btn-success btn-xs" style="width: 100%;"
                                id="addCirujanoIntraoperatorio">Cirujanos que Intervienen Operación</button>
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-bordered" cellspacing="0" width="100%" id="tblCirujano"
                                cellspacing="0" width="100%" style="margin-top:5px;">
                                <tbody id="tblCirujanoRows">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row intro-contenedor">
                        <div class="col-lg-3" style="padding-bottom: 1rem;">
                            <button type="button" class="btn btn-success btn-xs" style="width: 100%;"
                                id="addAnestesiologo">Anestesiologos que Intervienen Operación</button>
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-bordered" id="tblAnestesiologo" cellspacing="0" width="100%"
                                style="margin-top:5px;">
                                <tbody id="tblAnestesiologoRows">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row intro-contenedor">
                        <div class="col-lg-3" style="padding-bottom: 1rem;">
                            <button type="button" class="btn btn-success btn-xs" style="width: 100%;"
                                id="addEnfermeriaInstrumentista">Enf. Instrumentista Intervienen Operación</button>
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-bordered" id="tblEnfermeriaInstrumentista" cellspacing="0"
                                width="100%" style="margin-top:5px;">
                                <tbody id="tblEnfermeriaInstrumentistaRows">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab1default" data-toggle="tab" style="font-size:12px">REGISTRO DE
                        PACIENTE EN CENTRO QUIRÚRGICO</a></li>
                <li><a href="#tab2default" data-toggle="tab" style="font-size:12px">DIAGNÓSTICO DE ENFERMERÍA</a>
                </li>
                <li><a href="#tab3default" data-toggle="tab" style="font-size:12px">BALANCE DE FLUIDOS</a></li>
                <li><a href="#tab4defaultintraop" data-toggle="tab" style="font-size:12px">RECUENTO DE GASAS</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1default">
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
                                                    class="form-control" name="recepcion_paciente_descripcion">
                                            </div>
                                            <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                <input type="time" class="form-control" style="width: 100%;"
                                                    id="recepcion_paciente_tiempo" name="recepcion_paciente_tiempo"
                                                    min="08:00" max="18:00" />
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
                                                    max="18:00" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">INICIO DE CIRUGÍA</label>
                                            </div>
                                            <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                <input type="time" class="form-control" style="width: 100%;"
                                                    id="cirugia_inicio" name="cirugia_inicio" min="08:00"
                                                    max="18:00" />
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
                                                    max="18:00" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">HORA DE LLEGADA URPA</label>
                                            </div>
                                            <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                <input type="time" class="form-control" style="width: 100%;"
                                                    id="hora_llegada_urpa" name="hora_llegada_urpa" min="08:00"
                                                    max="18:00" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-4"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">TIPO DE ANESTESIA</label>
                                            </div>
                                            <div class="col-lg-8" style="padding-bottom: 1rem;">
                                                <select id="tipo_anestesia" name="tipo_anestesia"
                                                    class="form-control" style="width: 100%">
                                                    <option value="">Seleccionar</option>
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
                                                    id="anestesia_termino" name="anestesia_termino" min="08:00"
                                                    max="18:00" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">TÉRMINO DE CIRUGÍA</label>
                                            </div>
                                            <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                <input type="time" class="form-control" style="width: 100%;"
                                                    id="cirugia_termino" name="cirugia_termino" min="08:00"
                                                    max="18:00" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">TIEMPO REAL</label>
                                            </div>
                                            <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                <input type="text" class="form-control" style="width: 100%;"
                                                    id="tiempo_real" name="tipo_real" min="08:00" max="18:00"
                                                    readonly />
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
                                                    maxlength="250">
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
                                                    maxlength="250">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4"
                                                style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                <label style="margin-left: 2rem;">OPERACIÓN REALIZADA</label>
                                            </div>
                                            <div class="col-lg-8" style="padding-bottom: 1rem;">
                                                <input type="text" class="form-control" name="operacion_realizada"
                                                    id="operacion_realizada" maxlength="250">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-bottom: 1rem;">
                                        <div class="col-lg-2"
                                            style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                            <label style="margin-left: 2rem;">COMPLICACIONES</label>
                                        </div>
                                        <div class="col-lg-8" style="align-content: center; padding-bottom: 1rem;">
                                            <textarea class="form-control" cols="5" id="complicaciones" name="complicaciones"
                                                placeholder="Descripción de las complicaciones"></textarea>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-bottom: 1rem;">
                                        <div class="col-lg-2"
                                            style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                            <label style="margin-left: 2rem;">CAJAS DE INSTRUMENTAL</label>
                                        </div>
                                        <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
                                            <input type="text" class="form-control"
                                                name="cajas_instrumental_descripcion"
                                                id="cajas_instrumental_descripcion" />
                                        </div>
                                        <div class="col-lg-2"
                                            style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                            <label style="margin-left: 2rem;">DISPONIBILIDAD DE</label>
                                        </div>
                                        <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
                                            <input type="hidden" name="cajas_instrumental_equipo" value="0">
                                            <input type="checkbox" id="cajas_instrumental_equipo"
                                                name="cajas_instrumental_equipo" value="1"><span
                                                class="checkboxtexto"
                                                style="margin-left: 2rem; font-weight: 700;">EQUIPOS</span>
                                        </div>
                                        <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
                                            <input type="hidden" name="cajas_instrumental_insumos" value="0">
                                            <input type="checkbox" id="cajas_instrumental_insumos"
                                                name="cajas_instrumental_insumos" value="1"><span
                                                class="checkboxtexto"
                                                style="margin-left: 2rem; font-weight: 700;">INSUMOS</span>
                                        </div>
                                        <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
                                            <input type="hidden" name="cajas_instrumental_instrumental"
                                                value="0">
                                            <input type="checkbox" id="cajas_instrumental_instrumental"
                                                name="cajas_instrumental_instrumental" value="1"><span
                                                class="checkboxtexto"
                                                style="margin-left: 2rem; font-weight: 700;">INSTRUMENTAL</span>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-bottom: 1rem;">
                                        <div class="col-lg-2"
                                            style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                            <label style="margin-left: 2rem;">ENF I Y ENF II VERIFICA Y
                                                CONTROLA</label>
                                        </div>
                                        <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
                                            <input type="hidden" id="" name="enf1_enf2_vc_insumos"
                                                value="0">
                                            <input type="checkbox" id="enf1_enf2_vc_insumos"
                                                name="enf1_enf2_vc_insumos" value="1"><span
                                                class="checkboxtexto"
                                                style="margin-left: 2rem; font-weight: 700;">INSUMOS</span>
                                        </div>
                                        <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
                                            <input type="hidden" id="" name="enf1_enf2_vc_instrumental"
                                                value="0">
                                            <input type="checkbox" id="enf1_enf2_vc_instrumental"
                                                name="enf1_enf2_vc_instrumental" value="1"><span
                                                class="checkboxtexto"
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
                                                id="maquina_anestesia_descripcion" />
                                        </div>
                                        <div class="col-lg-2"
                                            style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                            <label style="margin-left: 2rem;">OXIGENO</label>
                                        </div>
                                        <div class="col-lg-3" style="padding-bottom: 1rem;">
                                            <input type="text" class="form-control" name="oxigeno_descripcion"
                                                id="oxigeno_descripcion" />
                                        </div>
                                    </div>

                                    <div class="row" style="padding-bottom: 1rem;">
                                        <div class="col-lg-2"
                                            style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                            <label style="margin-left: 2rem;">COMENTARIOS</label>
                                        </div>
                                        <div class="col-lg-8" style="align-content: center; padding-bottom: 1rem;">
                                            <textarea class="form-control" cols="5" id="comentarios" name="comentarios" placeholder="Comentarios"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="tab-pane fade" id="tab2default">
                    <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="" {{-- style="display: flex; flex-direction: row;" --}}
                                        style="display:flex; gap:20px; align-items:flex-start;">
                                        <div style="flex: 1; min-width:0; max-width: 56%;">
                                            <div
                                                style="background-color: rgb(250, 250, 250); border: 1px solid rgb(226, 226, 226);">
                                                <div
                                                    style="border-bottom: 1px solid rgb(226, 226, 226); padding: 1rem 0;">
                                                    <p style="margin: 0; text-align: center; font-weight: 700;">
                                                        DIAGNÓSTICO
                                                        DE ENFERMERIA</p>
                                                </div>
                                                <div id="d_enfermeria-container" style=""></div>
                                            </div>
                                        </div>
                                        <div style="flex: 1; min-width:0; max-width: 44%;">
                                            <div style="border: 1px solid rgb(226, 226, 226);">
                                                <div
                                                    style="border-bottom: 1px solid rgb(226, 226, 226); padding: 1rem 0;">
                                                    <p style="margin: 0; text-align: center; font-weight: 700;">
                                                        INTERVENCION DE ENFERMERIA (NIC)</p>
                                                </div>
                                                <div id="i_enfermeria-container" style=""></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="tab-pane fade" id="tab3default">
                    <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row intro-contenedor" style="padding: 0 1rem;">
                                        <div class="row intro-contenedor">
                                            <div class="col-lg-3" style="padding-bottom: 1rem;">
                                                <button type="button" class="btn btn-success btn-xs"
                                                    style="width: 100%;" id="addBalanceFluidos">Agregar Balance de
                                                    Fluidos</button>
                                            </div>
                                            <div class="col-lg-12">
                                                <table class="table table-bordered" id="tblBalanceFluidos"
                                                    cellspacing="0" width="100%" style="margin-top:5px;">
                                                    <thead>
                                                        <tr>
                                                            <th width="30%">INGRESO</th>
                                                            <th width="30%">EGRESO</th>
                                                            <th width="30%">TEREAPEUTICA</th>
                                                            <th width="10%">ACCION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tblBalanceFluidosRows">
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
                <div class="tab-pane fade" id="tab4defaultintraop">
                    <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row intro-contenedor" style="padding: 0 1rem;">
                                        <div class="col-lg-3" style="padding-bottom: 1rem;">
                                        </div>
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
                                                        <input type="hidden" name="enfermeriatres[]" id="total0">
                                                        <input type="number" data-toggle="tooltip"
                                                            data-placement="top" id="totalx0"
                                                            class="form-control ui-autocomplete-input"
                                                            style="height: 28px !important;font-weight: 800;" readonly>
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
                                                        <input type="hidden" name="enfermeriatres[]" id="total1">
                                                        <input type="number" data-toggle="tooltip"
                                                            data-placement="top" id="totalx1"
                                                            class="form-control ui-autocomplete-input"
                                                            style="height: 28px !important;font-weight: 800;" readonly>
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

            <br>

            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <h3 class="panel-title size-titulo">
                        PROCEDIMIENTOS DE INTERVENCIÓN
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered" id="tblProcedimientos" cellspacing="0" width="100%"
                        style="margin-top:5px;">
                        <thead>
                            <tr>
                                <th width="25%">CODIGO</th>
                                <th width="75%">DESCRIPCION</th>
                            </tr>
                        </thead>
                        <tbody id="tblProcedimientosRows">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <h3 class="panel-title size-titulo">
                        DIAGNOSTICOS DE LA ATENCION
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered" id="tblDiagnosticos" cellspacing="0" width="100%"
                        style="margin-top:5px;">
                        <thead>
                            <tr>
                                <th width="15%">CODIGO</th>
                                <th width="70%">DESCRIPCION</th>
                                <th width="15%">TIPO</th>
                            </tr>
                        </thead>
                        <tbody id="tblDiagnosticosRows">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <h3 class="panel-title size-titulo">
                        AGREGAR PROCEDIMIENTOS
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row intro-contenedor">
                            <div class="col-lg-3" style="padding-bottom: 1rem;">
                                <button type="button" class="btn btn-success btn-xs" style="width: 100%;"
                                    id="addProcedimientoPlus">Agregar Procedimiento</button>
                            </div>

                            <div class="col-lg-12">
                                <table class="table table-bordered" id="tblAddProcedimiento" cellspacing="0"
                                    width="100%" style="margin-top:5px;">
                                    <thead>
                                        <tr>
                                            <th width="50%">Procedimiento</th>
                                            <th width="10%">Cantidad</th>
                                            <th width="35%">Diagnosticos</th>
                                            <th width="5%">Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblAddProcedimientoRows">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="">
                <div class="col-sm-10"></div>
                <div class="col-sm-2">
                    <div style="width: 100%; display: flex; justify-content: flex-end; align-items: center;">
                        <input type="button" value="GUARDAR" class="btn btn-success" id="btnGuardarIntraOperatorio"
                            style="text-align:center!important">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>