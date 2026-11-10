<link rel="stylesheet" href="{{ asset('receta_vale/css/editarReceta.css') }}">
<script src="{{ asset('receta_vale/js/editarReceta.js') }}"></script>

<div class="modal fade" id="modalEditRecetaVale" tabindex="-1" role="dialog" aria-labelledby="modalEditRecetaValeLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 90% !important; max-width: 90% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditRecetaValeLabel">MÓDULO DE GESTIÓN DE RECETAS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="merv_id_receta_vale" id="merv_id_receta_vale">
                <input type="hidden" name="merv_sexo_beneficiario" id="merv_sexo_beneficiario">
                <input type="hidden" name="merv_id_prestacion" id="merv_id_prestacion">
                <input type="hidden" name="merv_type_recipe" id="merv_type_recipe">
                <div style="display: flex; flex-direction: row; justify-content: space-evenly; align-items: center; margin-bottom: 15px;">
                    <div>
                        <img src="../../images/logo_sanidad.png" width="50" height="auto">
                    </div>
                    <div style="text-align: center;">
                        {{-- <label style=" font-size: 11px">HOSPITAL NACIONAL PNP LUIS N. SAENZ</label><br> --}}
                        <label style="font-size: 24px">EDITAR RECETA</label><br>
                    </div>
                    <div>
                        <img src="../../images/logo_sanidad.png" width="50" height="auto">
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_id_tipo_atencion">TIPO ATENCIÓN</label>
                                <select class="form-control input-sm" name="merv_id_tipo_atencion" id="merv_id_tipo_atencion">
                                    <option value="">--Seleccionar--</option>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_nro_receta">N° RECETA</label>
                                <input type="text" class="form-control input-sm" name="merv_nro_receta" id="merv_nro_receta">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_nro_guia">N° GUIA</label>
                                <input type="text" class="form-control input-sm" name="merv_nro_guia" id="merv_nro_guia">
                            </div>
                            {{-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_anio_receta">AÑO RECETA</label>
                                <input type="text" class="form-control input-sm" name="merv_anio_receta" id="merv_anio_receta">
                            </div> --}}
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_fecha_expedicion">FEC. EXPEDICIÓN (A)</label>
                                <input type="date" class="form-control input-sm" name="merv_fecha_expedicion" id="merv_fecha_expedicion">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_fecha_expiracion">FEC. EXPIRACIÓN (E)</label>
                                <input type="date" class="form-control input-sm" name="merv_fecha_expiracion" id="merv_fecha_expiracion">
                            </div>
                            {{-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_fecha_dispensacion">FEC. DISPENSACIÓN (R)</label>
                                <input type="text" class="form-control input-sm" name="merv_fecha_dispensacion" id="merv_fecha_dispensacion">
                            </div> --}}

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-1 col-xl-1 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_tipo_doc_ident">TIPO DOC.</label>
                                <input type="text" class="form-control input-sm" name="merv_tipo_doc_ident" id="merv_tipo_doc_ident">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_nro_doc_ident">NRO DOC.</label>
                                <input type="text" class="form-control input-sm" name="merv_nro_doc_ident" id="merv_nro_doc_ident">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_apellidos_nombres">APELLIDOS Y NOMBRES</label>
                                <input type="text" class="form-control input-sm" name="merv_apellidos_nombres" id="merv_apellidos_nombres">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_parentesco">PARENTESCO</label>
                                <input type="text" class="form-control input-sm" name="merv_parentesco" id="merv_parentesco">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_nro_hc">NRO HC</label>
                                <input type="text" class="form-control input-sm" name="merv_nro_hc" id="merv_nro_hc">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_grado">GRADO</label>
                                <select name="merv_grado" id="merv_grado" class="form-control input-sm">
                                    <option value="">-- Seleccionar --</option>
                                </select>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_interconsulta">INTERCONSULTA</label>
                                <select name="merv_interconsulta" id="merv_interconsulta" class="form-control input-sm">
                                    <option value="">--Seleccionar--</option>
                                    <option>SI</option>
                                    <option>NO</option>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_diastto">DIAS TTO</label>
                                <input type="text" name="merv_diastto" id="merv_diastto" class="form-control input-sm">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_servicios">SERVICIOS</label>
                                <select name="merv_servicios" id="merv_servicios" class="form-control input-sm"></select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_upss">CODIGO UPS</label>
                                <select name="merv_upss" id="merv_upss" class="form-control input-sm"></select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-1 col-xl-1 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_turno">TURNO</label>
                                <select name="merv_turno" id="merv_turno" class="form-control input-sm"></select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-4 col-xl-4 form-group">
                                <label class="control-label modal-edit-rv-label" for="merv_medico">MEDICO (DNI | CMP | Nombre y Apellidos)</label>
                                <select name="merv_medico" id="merv_medico" class="form-control input-sm"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading" style="font-size: 12px !important;">DIAGNOSTICOS</div>
                    <div class="panel-body">
                        <button id="mervBtnAddTableDiagnosticos" type="button" class="btn btn-sm btn-primary" style="display: none;"><i class="fa fa-plus" aria-hidden="true"></i> AGREGAR DIAGNOSTICO</button>
                        <table id="mervTableDiagnosticos" class="table table-condensed" style="margin-top: 15px">
                            <thead id="mervTableDiagnosticosHead">
                                <tr>
                                    <th style="width: 75%">CÓDIGO - DESCRIPCIÓN</th>
                                    <th style="width: 20%">TIPO DE DIAGNOTICO</th>
                                    <th style="width: 5%"></th>
                                    <th style="width: 0%"></th>
                                    <th style="width: 0%"></th>
                                    <th style="width: 0%"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading" style="font-size: 12px !important;">PRESCRIPCIÓN</div>
                    <div class="panel-body">
                        <table id="mervTablePrescripcion" class="table table-condensed" style="margin-top: 10px">
                            <thead id="mervTablePrescripcionHead">
                                <tr>
                                    <th style="width: 37%" id="mervTablePresThProd">Producto farmacéutico o dispositivo médico</th>
                                    <th style="width: 7%">Stock Establecimiento</th>
                                    <th style="width: 7%">Stock Farmacia</th>
                                    <th style="width: 7%">Tiempo(Dias)</th>
                                    <th style="width: 7%" id="mervTablePresThViaAdm">Via Adm.</th>
                                    <th style="width: 7%" id="mervTablePresThDiag">Diagnostico</th>
                                    <th style="width: 7%" id="mervTablePresThPos">Posologia</th>
                                    <th style="width: 7%">Cant. Presc.</th>
                                    <th style="width: 7%">Cant. Entreg.</th>
                                    <th style="width: 7%" id="mervTablePresThObs">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <label>Nro. Recetas dispensadas y atenciones en el mes actual: </label>&nbsp;&nbsp;
                    <button type="button" data-toggle="modal" class="btn btn-primary btn-xs" id="mervBtnShowDetail"><i class="fa fa-list"></i> Detalle</button>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row" style="padding: 0px 15%">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <button type="button" class="btn btn-danger btn-block" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> CANCELAR</button>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <button type="button" class="btn btn-success btn-block" id="mervBtnSubmit" ><i class="fa fa-floppy-o" aria-hidden="true"></i> GUARDAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showAtencionesMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><small style="color: #fff">Nro. Recetas dispensadas y atenciones en el mes actual</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Nro. Receta</small></th>
                                <th><small>Fecha <br />Dispensación</small></th>
                                <th><small>Fecha <br />Expedición</small></th>
                                <th><small>Consultorio</small></th>
                                <th><small>Farmacia</small></th>
                                <th><small>Médico</small></th>
                                <th><small>Diagnóstico</small></th>
                                <th><small>Nro. Aten-<br>ciones</small></th>
                                <th><small>Detalle<br />Producto</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-atenciones"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showProductosReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalle de Recetas</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Código</small></th>
                                <th><small>Descipción</small></th>
                                <th><small>Unidad</small></th>
                                <th><small>Pedido</small></th>
                                <th><small>Despachado</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-productos"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>