<!-- Modal -->
<div class="modal fade" id="modalKardex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="width: 85%;">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important; border-top-left-radius: 5px; border-top-right-radius: 5px">
                <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioKardex"></b>
            </div>

            <div class="modal-body">
                <div class="panel with-nav-tabs panel-default">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tabNewKardex" data-toggle="tab" style="font-size:12px">NUEVO KARDEX</a></li>
                            <li><a href="#tabHistoryTratamiento" data-toggle="tab" style="font-size:12px">HISTORIAL DE TRATAMIENTO Y/O TERAPÉUTICA</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tabNewKardex">
                                <form id="formularioKardex" name="formularioKardex" action="{{'/api/kardex-enfermerias'}}" method="POST">
                                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" id="id" name="id">
                                    <input type="hidden" id="id_hospitalizacion_kardex" name="id_hospitalizacion_kardex">
                                    <input type="hidden" id="id_medicamento" name="id_medicamento">
                                    <input type="hidden" id="flag_registro" name="flag_registro">
                                    <input type="hidden" id="flag_action_form" name="flag_action_form" value="1"> {{-- 1 => CREAR, 2 EDITAR --}}
                                    <input type="hidden" id="flag_action_form_medicamento" name="flag_action_form_medicamento" value="1"> {{-- 1 => CREAR MEDICAMENTO, 2 => EDITAR MEDICAMENTO --}}
                                    <fieldset>
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading text-center">
                                                <b>DATOS DEL PACIENTE</b>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div id="mkr_nro_doc_ident_div" class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                                        <label class="control-label">DOCUMENTO DE IDENTIDAD</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_nro_doc_ident" id="mkr_nro_doc_ident" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3">
                                                        <label>PACIENTE</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_asegurado" id="mkr_asegurado" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                                        <label>PARENTESCO</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_parentesco" id="mkr_parentesco" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-1">
                                                        <label>SEXO</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_sexo" id="mkr_sexo" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-1">
                                                        <label>EDAD</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_edad" id="mkr_edad" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-1">
                                                        <label>T. SANG</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_sangre" id="mkr_sangre" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-1">
                                                        <label>H. CLINICA</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_historia_clinica" id="mkr_historia_clinica" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-1">
                                                        <label>ALERGÍA</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_alergia" id="mkr_alergia" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                
                                    <fieldset>
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading text-center">
                                                <b>DATOS DE LA ATENCIÓN</b>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                                        <label>TIPO DE ATENCIÓN</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_tipo_atencion" id="mkr_tipo_atencion" value="HOSPITALIZACION" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                                        <label>FECHA DE ATENCIÓN</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_fecha_atencion" id="mkr_fecha_atencion" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                                        <label>HORA DE ATENCIÓN</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_hora_atencion" id="mkr_hora_atencion" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3">
                                                        <label>SERVICIO</label>
                                                        <input class="form-control input-sm" type="text" name="mkr_prioridad_atencion" id="mkr_prioridad_atencion" readonly>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3" id="mkr_turno_div">
                                                        <label for="mkr_turno">TURNO</label>
                                                        <select class="form-control input-sm" name="mkr_turno" id="mkr_turno">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                
                                    <fieldset>
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading text-center">
                                                <b>DIAGNOSTICOS DE LA ATENCIÓN</b>
                                            </div>
                                            <div class="panel-body">
                                                <table id="mkr_table_diagnostivos_atencion" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>N</th>
                                                        <th>CODIGO</th>
                                                        <th>DESCRIPCIÓN</th>
                                                        <th>TIPO</th>
                                                        <th>CLASIFICACIÓN</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>
                
                                    <fieldset>
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading text-center">
                                                <b>TRATAMIENTO Y/O TERAPEUTICA</b>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row" style="padding: 0px 15px">
                                                    <div class="form-group col-12">
                                                        <label for="mkr_tratamiento">Tratamiento Auxiliar</label>
                                                        <textarea id="mkr_tratamiento" class="form-control" name="mkr_tratamiento" rows="2" readonly></textarea>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    </fieldset> 
                                    
                                    <fieldset>
                                        <div class="panel with-nav-tabs panel-default" style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs" id="tabs_kardex_register">
                                                    <li class="active">
                                                        <a href="#mkr_tab_kardex" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b><i class="fa fa-list-ol" aria-hidden="true"></i> KARDEX</b>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#mkr_tab_examenes_auxiliares" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b><i class="fa fa-file-text-o" aria-hidden="true"></i> EXAMENES AUXILIARES</b>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#mkr_tab_cuidado_enfermero" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b><i class="fa fa-user-md" aria-hidden="true"></i> CUIDADO DEL ENFERMERO</b>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#mkr_tab_interconsulta" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b><i class="fa fa-phone" aria-hidden="true"></i> INTERCONSULTA</b>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="panel-body" style="padding-bottom: 0px !important;">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade active in" id="mkr_tab_kardex">
                                                        <div class="panel panel-info panel-bordered panel-sm" id="listMedicamentosContainer">
                                                            <div class="panel-body">
                                                                <button
                                                                    type="button" 
                                                                    class="btn btn-success btn-sm"
                                                                    style="margin-bottom: 10px" 
                                                                    id="btnShowFormMedicamento">
                                                                </button>
                                                                <fieldset>
                                                                    <table id="tableMedicamentosKardex" class="table table-striped" style="font-size: 12px">
                                                                        <thead></thead><tbody></tbody>
                                                                    </table>
                                                                </fieldset>
                                                            </div>
                                                        </div>                                       
                                                    </div>
                
                                                    <div class="tab-pane fade" id="mkr_tab_examenes_auxiliares">
                                                        <div class="panel panel-info panel-bordered panel-sm">
                                                            <div class="panel-body">
                                                                <button
                                                                    type="button" 
                                                                    class="btn btn-success btn-sm" 
                                                                    style="margin-bottom: 10px" 
                                                                    id="btnAddRowTableExamAuxLab">
                                                                    <i class="fa fa-plus"></i> EXAMEN AUXILIAR DE LABORATORIO
                                                                </button>
                                                                <fieldset>
                                                                    <table id="tableExamAuxLab" class="table table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 15%">FECHA</th>
                                                                                <th style="width: 10%">HORA</th>
                                                                                <th style="width: 41%">NOMBRE EXAMEN</th>
                                                                                <th style="width: 15%">TOMA DE MUESTRA</th>
                                                                                <th style="width: 7%">RESULTADO</th>
                                                                                <th style="width: 7%">SIN REACTIVO</th>
                                                                                <th style="width: 5%">ELIMINAR</th>
                                                                                <th style="width: 0%; visibility: hidden">INDEX</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        </tbody>
                                                                    </table>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                
                                                        <div class="panel panel-info panel-bordered panel-sm">
                                                            <div class="panel-body">
                                                                <button
                                                                    type="button" 
                                                                    class="btn btn-success btn-sm" 
                                                                    style="margin-bottom: 10px" 
                                                                    id="btnAddRowTableExamAuxImag">
                                                                    <i class="fa fa-plus"></i> EXAMEN AUXILIAR DE IMAGENOLOGÍA
                                                                </button>
                                                                <fieldset>
                                                                    <table id="tableExamAuxImag" class="table table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 15%">FECHA</th>
                                                                                <th style="width: 15%">HORA</th>
                                                                                <th style="width: 50%">NOMBRE EXAMEN</th>
                                                                                <th style="width: 15%">TOMA DE IMAGEN</th>
                                                                                <th style="width: 5%">ELIMINAR</th>
                                                                                <th style="width: 0%; visibility: hidden">INDEX</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        </tbody>
                                                                    </table>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                
                                                    <div class="tab-pane fade" id="mkr_tab_cuidado_enfermero">
                                                        <input type="hidden" name="cuidado_enfermeros_id" id="cuidado_enfermeros_id">
                                                        <fieldset class="form-horizontal">
                                                            <div class="form-group" id="control_funciones_vitales_div">
                                                                <label for="control_funciones_vitales" class="col-sm-3 control-label">Control de Funciones Vitales (CFV) :</label>
                                                                <div class="col-sm-3">
                                                                    <input type="checkbox" class="form-check-input" id="control_funciones_vitales" name="control_funciones_vitales" value="true">
                                                                </div>
                                                            </div>
                                                            <div class="form-group" id="barrera_hematoencefalica_div">
                                                                <label for="barrera_hematoencefalica" class="col-sm-3 control-label">Balance Hídrico Estricto (BHE) :</label>
                                                                <div class="col-sm-3">
                                                                    <input type="checkbox" class="form-check-input" id="barrera_hematoencefalica" name="barrera_hematoencefalica" value="true">
                                                                </div>
                                                            </div>
                                                            <div class="form-group" id="cabecera_div">
                                                                <label for="cabecera" class="col-sm-3 control-label">Cabecera :</label>
                                                                <div class="col-sm-2">
                                                                <input type="number" step="1" min="0" placeholder="0" class="form-control input-sm" id="cabecera" name="cabecera">
                                                                </div>
                                                            </div>
                                                            <div class="form-group" id="oxigenoterapia_div">
                                                                <label for="oxigenoterapia" class="col-sm-3 control-label">Oxigenoterapia : </label>
                                                                <div class="col-sm-9">
                                                                    <div class="row">
                                                                        <div class="col-sm-3">
                                                                            <select class="form-control input-sm" id="id_oxigeno_terapia" name="id_oxigeno_terapia"></select>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="number" step="1" min="0" placeholder="0" class="form-control input-sm" id="oxigenoterapia_cantidad" name="oxigenoterapia_cantidad" readonly>
                                                                        </div>
                                                                    </div>    
                                                                </div>
                                                            </div>
                                                            <div class="form-group" id="movilización_div" >
                                                                <label for="movilización" class="col-sm-3 control-label">Movilización cada 2 horas : </label>
                                                                <div class="col-sm-9">
                                                                    <input type="checkbox" class="form-check-input" id="movilización" name="movilización" value="true">
                                                                </div>
                                                            </div>
                                                            <div class="form-group" id="hemoglucotest_cuidado_enfermero_kardex_div" >
                                                                <label for="hemoglucotest_cuidado_enfermero_kardex" class="col-sm-3 control-label">Hemoglucotest : </label>
                                                                <div class="col-sm-2">
                                                                    <input type="number" step="1" min="0" placeholder="0" class="form-control input-sm" id="hemoglucotest_cuidado_enfermero_kardex" name="hemoglucotest_cuidado_enfermero_kardex">
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                
                                                    <div class="tab-pane fade" id="mkr_tab_interconsulta">
                                                        <button
                                                            type="button" 
                                                            class="btn btn-success btn-sm" 
                                                            style="margin-bottom: 10px" 
                                                            id="btnAddRowInterconsulta">
                                                            <i class="fa fa-plus"></i> INTERCONSULTA
                                                        </button>
                                                        <fieldset>
                                                            <table id="tableInterconsulta" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 55%">NOMBRE DEL SERVICIO</th>
                                                                        <th style="width: 10%">FECHA ENVIO</th>
                                                                        <th style="width: 10%">HORA ENVIO</th>
                                                                        <th style="width: 10%">FECHA RESPUESTA</th>
                                                                        <th style="width: 10%">HORA RESPUESTA</th>
                                                                        <th style="width: 5%">ELIMINAR</th>
                                                                        <th style="width: 0%; visibility: hidden">INDEX</th>
                                                                        <th style="width: 0%; visibility: hidden">ID</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </fieldset>
                                                    </div>
                
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <button type="button" id="btnCancel" class="btn btn-danger btn-block">CANCELAR</button>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <button id="btnSave" type="button" class="btn btn-success btn-block">GUARDAR</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tabHistoryTratamiento">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="tableHistoryTratamiento" class="table table-striped" style="width: 100%; font-size: 12px">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>FECHA ATENCION</th> 
                                                        <th>HORA ATENCIÓN</th> 
                                                        <th>DOCTOR RESPONSABLE</th> 
                                                        <th>TRATAMIENTO Y/O TERAPEUTICA</th>
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
                </div>
            </div>
        </div>
    </div>
</div>