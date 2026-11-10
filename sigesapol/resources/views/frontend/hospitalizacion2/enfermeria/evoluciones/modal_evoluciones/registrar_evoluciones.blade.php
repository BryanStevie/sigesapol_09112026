<!-- Modal -->
<div class="modal fade" id="modalEvoluciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioEvoluciones"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioEvoluciones" name="FormularioEvoluciones" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_atencion_evolucion_registro" name="id_atencion_evolucion_registro">
                    <input type="hidden" id="id_tipo_atenciones_evolucion_registro"
                        name="id_tipo_atenciones_evolucion_registro">
                    <input type="hidden" id="id_nota_enfermeria_evolucion_registro"
                        name="id_nota_enfermeria_evolucion_registro">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera')

                                    <div class="form-group">
                                        <div class="col-md-6">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">TURNO</label>
                                            <select class="form-control select2" style="width: 100%"
                                                id="descripcion_turno" name="descripcion_turno">
                                                <option value="">--SELECCIONAR--</option>
                                                <option value="M">MAÑANA</option>
                                                <option value="T">TARDE</option>
                                                <option value="N">NOCHE</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">FECHA</label>
                                            <input type="text" class="form-control" style="height: 28px !important;"
                                                disabled name="fecha_registro_evoluciones_inicial"
                                                id="fecha_registro_evoluciones_inicial">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </fieldset>

                                <div class="form-group">
                                    <div class="panel with-nav-tabs panel-default"
                                        style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                        <div class="panel-heading">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#tab0defaultEvoluciones" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" aria-hidden="true"></i>
                                                            VALORACIÓN DE ENFERMERÍA </b></a></li>
                                                <li><a href="#tab1defaultEvoluciones" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" aria-hidden="true"></i>
                                                            ATENCIÓN DE ENFERMERÍA</b></a></li>
                                                <li><a href="#tab2defaultEvoluciones" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" aria-hidden="true"></i>
                                                            CPMS</b></a></li>
                                            </ul>
                                        </div>

                                        <div class="panel-body">
                                            <div class="tab-content">

                                                <div class="tab-pane fade active in" id="tab0defaultEvoluciones">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        <div class="col-md-12">
                                                            <label for="tipo">SUBJETIVO</label>
                                                            <textarea class="form-control" rows="6" required
                                                                style="text-transform:uppercase"
                                                                id="descripcion_subjetivo"
                                                                name="descripcion_subjetivo"></textarea>
                                                        </div>

                                                        <div class="col-md-12"> <br>
                                                            <label for="tipo">OBJETIVO</label>
                                                            <textarea class="form-control" rows="6" required
                                                                style="text-transform:uppercase"
                                                                id="descripcion_objetivo"
                                                                name="descripcion_objetivo"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane" id="tab1defaultEvoluciones">
                                                    <fieldset>
                                                        <div class="row"> <br>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <label for="">DIAGNÓTICO DE
                                                                            ENFERMERÍA</label><br>
                                                                        <a href="javascript:void(0)"
                                                                            id="add_DIAGNOSTICO_ENFERMERIA"
                                                                            name="add_DIAGNOSTICO_ENFERMERIA"
                                                                            class="btn btn-success btn-sm add_DIAGNOSTICO_ENFERMERIA"><i
                                                                                class="fa fa-plus"
                                                                                aria-hidden="true"></i> AGREGAR
                                                                            DIAGNÓTICO
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_DIAGNOSTICO_ENFERMERIA"
                                                                            style="margin-bottom: 10px !important;">
                                                                            <tbody id="cuerpo_DIAGNOSTICO_ENFERMERIA"
                                                                                name="cuerpo_DIAGNOSTICO_ENFERMERIA"
                                                                                class="cuerpo_DIAGNOSTICO_ENFERMERIA">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-12"
                                                                            style="margin-bottom: 10px;">
                                                                            <label for="">PLANEACIÓN</label><br>
                                                                            <a href="javascript:void(0)"
                                                                                id="add_PLAN_CUIDADO_ENFERMERIA"
                                                                                name="add_PLAN_CUIDADO_ENFERMERIA"
                                                                                class="btn btn-success btn-sm add_PLAN_CUIDADO_ENFERMERIA"><i
                                                                                    class="fa fa-plus"
                                                                                    aria-hidden="true"></i> AGREGAR NOC
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <table id="tbl_PLAN_CUIDADO_ENFERMERIA"
                                                                                style="margin-bottom: 10px !important;">
                                                                                <tbody
                                                                                    id="cuerpo_PLAN_CUIDADO_ENFERMERIA"
                                                                                    name="cuerpo_PLAN_CUIDADO_ENFERMERIA"
                                                                                    class="cuerpo_PLAN_CUIDADO_ENFERMERIA">
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <label for="">EJECUCIÓN</label><br>
                                                                        <a href="javascript:void(0)"
                                                                            id="add_INTERVENCIONES_ENFERMERIA"
                                                                            name="add_INTERVENCIONES_ENFERMERIA"
                                                                            class="btn btn-success btn-sm add_INTERVENCIONES_ENFERMERIA"><i
                                                                                class="fa fa-plus"
                                                                                aria-hidden="true"></i> AGREGAR NIC
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_INTERVENCIONES_ENFERMERIA"
                                                                            style="margin-bottom: 10px !important;">
                                                                            <tbody id="cuerpo_INTERVENCIONES_ENFERMERIA"
                                                                                name="cuerpo_INTERVENCIONES_ENFERMERIA"
                                                                                class="cuerpo_INTERVENCIONES_ENFERMERIA">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="tipo">DETALLE DE
                                                                            INTERVENCIONES</label>
                                                                        <textarea class="form-control" rows="3" required
                                                                            style="text-transform:uppercase"
                                                                            id="descripcion_intervenciones"
                                                                            name="descripcion_intervenciones"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="col-md-12"
                                                                        style="margin-bottom: 10px;padding-left: 0px !important;">
                                                                        <label for="">EVALUACIÓN</label><br>
                                                                        <a href="javascript:void(0)"
                                                                            id="add_EVALUACIONES_ENFERMERIA"
                                                                            name="add_EVALUACIONES_ENFERMERIA"
                                                                            class="btn btn-success btn-sm add_EVALUACIONES_ENFERMERIA"><i
                                                                                class="fa fa-plus"
                                                                                aria-hidden="true"></i> GENERAR
                                                                            EVALUACIÓN
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12"
                                                                        style="padding-left: 0px !important;margin-bottom: 10px !important;width: 100%">
                                                                        <table id="tbl_EVALUACIONES_ENFERMERIA">
                                                                            <tbody id="cuerpo_EVALUACIONES_ENFERMERIA"
                                                                                name="cuerpo_EVALUACIONES_ENFERMERIA"
                                                                                class="cuerpo_EVALUACIONES_ENFERMERIA">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12"
                                                                        style="padding-left: 0px !important;">
                                                                        <label for="tipo">DETALLE DE
                                                                            EVALUACIONES</label>
                                                                        <textarea class="form-control" rows="3" required
                                                                            style="text-transform:uppercase"
                                                                            id="descripcion_evaluaciones"
                                                                            name="descripcion_evaluaciones"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="tab-pane fade" id="tab2defaultEvoluciones">
                                                    <fieldset>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <a href="javascript:void(0)"
                                                                            id="add_CPMS_ENFERMERIA"
                                                                            name="add_CPMS_ENFERMERIA"
                                                                            class="btn btn-success btn-sm add_CPMS_ENFERMERIA">
                                                                            <i class="fa fa-plus"
                                                                                aria-hidden="true"></i> AGREGAR CPMS
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_CPMS_ENFERMERIA"
                                                                            style="margin-bottom: 10px !important;width: 100%;">
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
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="registrarEvoluciones"
                            value="create">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>