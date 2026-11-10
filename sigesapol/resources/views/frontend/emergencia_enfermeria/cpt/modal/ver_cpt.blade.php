<!-- Modal -->
<div class="modal fade" id="modalVerCpt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false"
    data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 1350px;" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
                <b id="">Ver detalle CPT</b>
            </div>

            <div class="modal-body">
                <form id="" name="" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_cpt_ver">
                    <input type="hidden" id="argumentos_cpt" />
                    <input type="hidden" id="doc_firmar_cpt" />
                    <input type="hidden" name="flag_firma" id="flag_firma_cpt">
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
                                        <table class="table" id="" cellspacing="0" width="100%"
                                            style="margin-top:5px;">
                                            <tr>
                                                <th>DOCUMENTO</th>
                                                <th width="50%">PACIENTE</th>
                                                <th>PARENTESCO</th>
                                                <th>SEXO</th>
                                                <th>EDAD</th>
                                                <th>T. SANG.</th>
                                                <th>H. CLINICA</th>
                                                <th>Cel. ACOMP.</th>
                                                <th>Alergia:
                                                    <a href="javascript:void(0)" data-toggle="tooltip"
                                                        style="cursor: pointer;" title="Alergia"
                                                        class="listarAlergiasCpt_det" id="listarAlergiasCpt_det">
                                                        <label id="alergia_si_cpt_det"
                                                            style="font-size: 14px;cursor: pointer; display: none;">SI</label>
                                                        <label id="alergia_no_cpt_det"
                                                            style="font-size: 14px;cursor: pointer; display: none;">No
                                                            registrado</label>
                                                    </a>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="input_general" id="num_documento_cpt_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_paciente" id="nom_paciente_cpt_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_m" id="parentesgo_cpt_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_p" id="sexo_cpt_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_p" id="edad_cpt_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_p" id="tipo_sangre_cpt_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_hc" id="hc_cpt_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_hc" id="cel_acomp_cpt_ver"></div>
                                                </td>
                                                <td>

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                        <div class="panel-title text-center"><br>
                                            <b>DATOS DE LA ATENCIÓN</b>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table" id="" cellspacing="0" width="100%"
                                            style="margin-top:5px;">
                                            <thead>
                                                <tr>
                                                    <th width="20%">TIPO DE ATENCIÓN</th>
                                                    <th width="35%">SERVICIO</th>
                                                    <th width="15%">FECHA DE ATENCIÓN</th>
                                                    <th width="15%">HORA DE ATENCIÓN</th>
                                                    <th width="15%">TURNO</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <td width="20%">
                                                    <input disabled type="text" class="form-control datepicker"
                                                        style="height: 28px !important;"
                                                        id="nombre_tipo_atencion_det_cpt" disabled>
                                                </td>
                                                <td width="35%">
                                                    <input disabled type="text" class="form-control datepicker"
                                                        style="height: 28px !important;" id="prioridad_det_cpt"
                                                        disabled>
                                                </td>
                                                <td width="15%">
                                                    <input disabled type="date" class="form-control"
                                                        style="height: 28px !important;"
                                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                        name="fecha_det_cpt" id="fecha_det_cpt">
                                                </td>
                                                <td width="15%">
                                                    <input disabled type="time" class="form-control"
                                                        style="height: 28px !important;" name="hora_det_cpt"
                                                        id="hora_det_cpt"
                                                        value="{{ \Carbon\Carbon::now()->format('H:m') }}">
                                                </td>
                                                <td width="15%">
                                                    <select disabled class="form-control select2" style="width: 100%"
                                                        id="turno_det_cpt" name="turno_det_cpt">
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
                                                <li class="active"><a id="" href="#tab2defaultCptDet"
                                                        data-toggle="tab" style="font-size:12px"
                                                        aria-expanded="true"><b><i class="fa fa-file-text-o"></i>
                                                            CPMS/CPT</b></a></li>
                                            </ul>
                                        </div>

                                        <div class="panel-body">
                                            <div class="tab-content">
                                                <div class="tab-pane fade active in" id="tab2defaultCptDet">
                                                    <fieldset>
                                                        <div class="panel panel-info panel-bordered panel-sm">
                                                            <div class="panel-heading">
                                                                <div class="panel-title text-center"><br>
                                                                    <b>DIAGNOSTICOS</b>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                {{-- <table class="table table-bordered"
                                                                    id="tblDiagnosticosDet" cellspacing="0"
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
                                                                    <tbody id="diagnosticos_det_cpt">

                                                                    </tbody>
                                                                </table> --}}
                                                                <table class="table table-bordered"
                                                                    id="tblDiagnosticosDetPrest" cellspacing="0"
                                                                    width="100%" style="margin-top:5px;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="4"
                                                                                style="text-align: center;">DE ATENCION MEDICA
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
                                                                    <tbody id="diagnosticos_det_cpt_prest">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_CPMS_ENFERMERIA_det"
                                                                            style="margin-bottom: 10px !important;width: 100%;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th width="40%">Procedimiento
                                                                                    </th>
                                                                                    <th width="40%">Diagnosticos</th>
                                                                                    <th width="10%">Cantidad</th>
                                                                                    <th width="10%">Tiempo (min)
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tfoot>
                                                                            </tfoot>
                                                                            <tbody id="cuerpo_CPMS_ENFERMERIA_det"
                                                                                name="cuerpo_CPMS_ENFERMERIA_det"
                                                                                class="cuerpo_CPMS_ENFERMERIA_det">
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
                        <table width="100%">
                            <tr>
                                <td>
                                    {{-- @if (Auth::user()->status_token == 1)
                                        <a class="btn btn-info" id="btnFinalizarCpt">
                                            <i class="fa-solid fa-file-signature"></i> FINALIZAR EVOLUCIONES
                                        </a>
                                    @else
                                        <a class="btn btn-info" id="btnFirmaCpt">
                                            <i class="fa-solid fa-file-signature"></i> FIRMAR EVOLUCIONES
                                        </a>
                                    @endif --}}
                                    {{-- <div id="icon-doc-firmado-cpt"
                                        style="display: flex; align-items: center; justify-content: center; gap: 0.5rem">
                                        <i class="fa fa-check-circle icon-doc-firmado" aria-hidden="true"></i><span
                                            style="font-size: 1rem; font-weight: 600;">Documento Firmado</span>
                                    </div> --}}
                                </td>
                                <td>
                                    <div><button type="button" class="btn btn-danger pull-left"
                                            data-dismiss="modal">Cerrar</button></div>
                                </td>
                            </tr>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>
