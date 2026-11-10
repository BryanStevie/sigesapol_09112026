<div id="cptEmergencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="miModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 1000px;" role="document">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
                <b id="">Modulo CPT</b>
            </div>
            <div class="modal-body" style="font-size: 11px !important">
                <form id="FormularioListarCPT" name="FormularioListarCPT" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_asegurado_cpt" name="id_asegurado_cpt">
                    <input type="hidden" id="servicio_cpt">
                    <input type="hidden" id="area_cpt">
                    <input type="hidden" id="nombre_cama_cpt">
                    <input type="hidden" id="nombre_cuarto_cpt">
                    <input type="hidden" id="id_enfermeria_ver_cpt">
                    <input type="hidden" id="id_atencion_evolucion_listado_cpt"
                        name="id_atencion_evolucion_listado_cpt">
                    <input type="hidden" id="id_prestacion_listado_cpt" name="id_prestacion_listado_cpt">
                    <input type="hidden" id="id_tipo_atenciones_listado_cpt" name="id_tipo_atenciones_listado_cpt">
                    <input type="hidden" id="id_enfermeria_cpt" name="id_enfermeria_cpt">
                    <input type="hidden" id="id_ext_cpt" name="id_ext_cpt">
                    <input type="hidden" id="nombre_tipo_atencion_cpt" name="nombre_tipo_atencion_cpt">
                    <input type="hidden" id="prioridad_cpt" name="prioridad_cpt">

                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <fieldset>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label for="tipo">DNI - PACIENTE: </label> <br>
                                                <span style="font-size: 14px;">
                                                    <span id="num_documento_cpt"></span> -
                                                    <span id="nom_paciente_cpt"></span>
                                                </span>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">G.S.: </label> <br>
                                                <label style="font-size: 14px;"><span
                                                        id="tipo_sangre_cpt"></span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">ALERGIA: </label> <br>
                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                    style="cursor: pointer;" title="Alergia" class="listarAlergias"
                                                    id="listarAlergiasCpt">
                                                    <label id="alergia_si_listado_cpt"
                                                        style="font-size: 11px;cursor: pointer; display: none;">SI</label>
                                                    <label id="alergia_no_listado_cpt"
                                                        style="font-size: 11px;cursor: pointer; display: none;">No
                                                        registrado</label>
                                                </a>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">EDAD: </label> <br>
                                                <label style="font-size: 14px;" id="edad_cpt"></label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">SEXO: </label> <br>
                                                <label style="font-size: 14px;" id="sexo_cpt"></label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">HC: </label> <br>
                                                <label style="font-size: 14px;" id="hc_cpt"></label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">CEL. ACOMP.: </label> <br>
                                                <label style="font-size: 14px;" id="cel_acomp_cpt"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label for="tipo">FECHA INICIAL</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;font-size: 12px !important"
                                                name="fecha_cpt_inicial_listado" id="fecha_cpt_inicial_listado"
                                                value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo">FECHA FINAL</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;font-size: 12px !important"
                                                name="fecha_cpt_final_listado" id="fecha_cpt_final_listado"
                                                value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">TIPO</label>
                                            <select class="form-control input-sm w-full" style="width: 100%;"
                                                name="tipo_atenciones_cpt" id="tipo_atenciones_listado_cpt">
                                                <option value="0">---Seleccionar---</option>
                                                <option value="2" selected>EMERGENCIA</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo">TURNO</label>
                                            <select class="form-control" style="width: 100%" id="id_turno_cpt"
                                                name="id_turno_cpt">
                                                <option value="">TODOS</option>
                                                <option value="1">MADRUGADA</option>
                                                <option value="2">MAÑANA</option>
                                                <option value="3">TARDE</option>
                                                <option value="4">NOCHE</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>PROCESO</label><br>
                                            <div style="width: auto; display: inline-block;" data-toggle="tooltip" data-placement="top" data-html="true">
                                                <button style="" type="button" data-toggle="tooltip"
                                                    class="btn btn-success btn-sm crearCpt" id="crearCpt">
                                                    REGISTRAR CPT
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset><br>
                                    <div class="col-md-12">
                                        <div class="table-responsive" style=" height: 350px; ">
                                            <table class="table table-bordered dt-responsive" id="exampleCpt"
                                                style="overflow: auto; width:100%;text-align: left;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 130px; text-align: center;">FECHA / HORA
                                                            CPT
                                                        </th>
                                                        <th style="width: 130px; text-align: center;">DATOS DE LA
                                                            ATENCIÓN</th>
                                                        <th style="width: 130px; text-align: center;">UBICACIÓN</th>
                                                        <th style="width: 130px; text-align: center;">PROFESIONAL DE
                                                            SALUD</th>
                                                        <th style="width: 160px; text-align: center;">VER / EDITAR</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="listaDatosCpt" class="listaDatosCpt">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
