<!-- Modal -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script> --}}
<div class="modal fade" id="modalRegistrarCpt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 1400px;" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
                <b id="">Formulario de registro CPT</b>
            </div>

            <div class="modal-body">
                <form id="FormularioCpt" name="FormularioCpt" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_enfermeria_cpt_reg" name="id_enfermeria">
                    <input type="hidden" id="id_ext_cpt_reg" name="id_ext">
                    <input type="hidden" id="tiene_prestacion_cpt" name="tiene_prestacion_cpt">
                    <input type="hidden" id="id_prestacion_listado_cpt_reg" name="id_prestacion_listado">
                    <input type="hidden" id="id_tipo_atenciones_listado_cpt_reg" name="id_tipo_atenciones_listado">
                    <input type="hidden" id="id_tipo_atenciones_registro_cpt_reg" name="id_tipo_atenciones_registro">
                    <input type="hidden" id="nombre_tipo_atencion_cpt_reg" name="nombre_tipo_atencion">
                    <input type="hidden" id="prioridad_cpt_reg" name="prioridad">
                    <input type="hidden" id="id_asegurado2_cpt_reg" name="id_asegurado2">
                    <input type="hidden" id="id_prestacion_cpt_reg" name="id_prestacion_registro">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">

                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                        <div class="panel-title text-center"><br>
                                            <b>INFORMACIÓN DEL PACIENTE</b>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table" cellspacing="0" width="100%" style="margin-top:5px;">
                                            <tr>
                                                <th>DOCUMENTO</th>
                                                <th>PACIENTE</th>
                                                <th>PARENTESCO</th>
                                                <th>SEXO</th>
                                                <th>EDAD</th>
                                                <th>T. SANG.</th>
                                                <th>H. CLINICA</th>
                                                <th>Cel. ACOMP.</th>
                                                <th>Alergia:
                                                    <a href="javascript:void(0)" data-toggle="tooltip"
                                                        style="cursor: pointer;" title="Alergia"
                                                        class="listarAlergiasCpt_reg" id="listarAlergiasCpt_reg">
                                                        <label id="alergia_si_cpt_reg"
                                                            style="font-size: 14px;cursor: pointer; display: none;">SI</label>
                                                        <label id="alergia_no_cpt_reg"
                                                            style="font-size: 14px;cursor: pointer; display: none;">No
                                                            registrado</label>
                                                    </a>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="input_general" id="num_documento_cpt_n"></div>
                                                </td>
                                                <td>
                                                    <div class="input_paciente" id="nom_paciente_cpt_n"></div>
                                                </td>
                                                <td>
                                                    <div class="input_m" id="parentesgo_cpt_n"></div>
                                                </td>
                                                <td>
                                                    <div class="input_p" id="sexo_cpt_n"></div>
                                                </td>
                                                <td>
                                                    <div class="input_p" id="edad_cpt_n"></div>
                                                </td>
                                                <td>
                                                    <div class="input_p_2" id="tipo_sangre_cpt_n"></div>
                                                </td>
                                                <td>
                                                    <div class="input_hc" id="hc_cpt_n"></div>
                                                </td>
                                                <td>
                                                    <div class="input_hc" id="cel_acomp_cpt_n"></div>
                                                </td>
                                                <td>

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="panel panel-info panel-bordered panel-sm">

                                    <div class="panel-body">
                                        <table class="table" id="cpms_listado" cellspacing="0" width="100%"
                                            style="margin-top:5px;">
                                            <thead>

                                                <tr>
                                                    <th colspan="5"><b>DATOS DE LA ATENCIÓN</b></th>
                                                </tr>
                                                <tr>
                                                    <th width="20%">TIPO DE ATENCIÓN</th>
                                                    <th width="30%">SERVICIO</th>
                                                    <th width="15%">FECHA</th>
                                                    <th width="15%">HORA</th>
                                                    <th width="20%">TURNO</th>
                                                </tr>
                                            </thead>

                                            <tr>
                                                <td width="20%">
                                                    <input type="text" class="form-control datepicker"
                                                        style="height: 28px !important;"
                                                        id="nombre_tipo_atencion_registro_cpt_n" disabled>
                                                </td>
                                                <td width="30%">
                                                    <input type="text" class="form-control datepicker"
                                                        style="height: 28px !important;" id="prioridad_registro_cpt_n"
                                                        disabled>
                                                </td>
                                                <td width="15%">
                                                    <input type="date" class="form-control"
                                                        style="height: 28px !important;"
                                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                        name="fecha_registro_cpt" id="fecha_registro_cpt">
                                                </td>
                                                <td width="15%">
                                                    <input type="time" class="form-control"
                                                        style="height: 28px !important;" name="hora_registro_cpt"
                                                        id="hora_registro_cpt"
                                                        value="{{ \Carbon\Carbon::now()->format('H:i') }}">
                                                </td>
                                                <td width="20%">
                                                    <select class="form-control select2" style="width: 100%"
                                                        id="turnos_nuevo_cpt" name="turno_cpt">
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="panel with-nav-tabs panel-default"
                                        style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                        <div class="panel-heading">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a id="" href="#tab2defaultCpt"
                                                        data-toggle="tab" style="font-size:12px"
                                                        aria-expanded="true"><b><i class="fa fa-file-text-o"></i>
                                                            CPMS/CPT</b></a></li>
                                            </ul>
                                        </div>

                                        <div class="panel-body">
                                            <div class="tab-content">
                                                <div class="tab-pane fade active in" id="tab2defaultCpt">
                                                    <fieldset>
                                                        <div class="panel panel-info panel-bordered panel-sm">
                                                            <div class="panel-heading">
                                                                <div class="panel-title text-center"><br>
                                                                    <b>DIAGNOSTICOS</b>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                {{-- <table class="table table-bordered"
                                                                    id="tblDiagnosticos" cellspacing="0"
                                                                    width="100%" style="margin-top:5px;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="4"
                                                                                style="text-align: center;">DE ADMISION
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th width="15%">Codigo</th>
                                                                            <th width="50%">Nombre</th>
                                                                            <th width="15%">Tipo</th>
                                                                            <th width="15%">Clasificación</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tfoot>
                                                                    </tfoot>
                                                                    <tbody id="diagnosticos_nuevo_cpt">

                                                                    </tbody>
                                                                </table> --}}
                                                                <table class="table table-bordered"
                                                                    id="tblDiagnosticosPrest" cellspacing="0"
                                                                    width="100%" style="margin-top:5px;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="4"
                                                                                style="text-align: center;">DE ATENCION
                                                                                MEDICA
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th width="15%">Codigo</th>
                                                                            <th width="50%">Nombre</th>
                                                                            <th width="15%">Tipo</th>
                                                                            <th width="15%">Clasificación</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tfoot>
                                                                    </tfoot>
                                                                    <tbody id="diagnosticos_nuevo_cpt_prest">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="col-md-12"
                                                                        style="margin-bottom: 10px;">
                                                                        <a href="javascript:void(0)"
                                                                            id="add_CPMS_ENFERMERIA_cpt"
                                                                            name="add_CPMS_ENFERMERIA"
                                                                            class="btn btn-success btn-sm add_CPMS_ENFERMERIA">
                                                                            <i class="fa fa-plus"></i> Agregar
                                                                            Procedimiento
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_CPMS_ENFERMERIA"
                                                                            style="margin-bottom: 10px !important;width: 100%;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th width="40%">Procedimiento
                                                                                    </th>
                                                                                    <th width="10%">Cantidad</th>
                                                                                    <th width="10%">Tiempo (min)
                                                                                    </th>
                                                                                    <th width="40%">Diagnosticos
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tfoot>
                                                                            </tfoot>
                                                                            <tbody id="cuerpo_CPMS_ENFERMERIA"
                                                                                name="cuerpo_CPMS_ENFERMERIA"
                                                                                class="cuerpo_CPMS_ENFERMERIA">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left"
                            data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="registrarCpt"
                            value="create">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
