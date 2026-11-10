<div class="modal fade" id="modal_kardex_formulario_medicamento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="width: 80%;">
        <div class="modal-content" style="font-size: 11px">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p class="modal-title" style="font-size: 12px" id="modal_kardex_formulario_medicamento_title"></p>
            </div>
            <div class="modal-body">
                <div class="panel panel-info panel-bordered panel-sm" style="background-color: #F0F0F0">
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-horizontal col-md-4" id="kardex_fecha_div">
                                <label for="kardex_fecha" class="col-sm-2 control-label" style="margin-left: -40px !important">FECHA:  </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-sm" id="kardex_fecha" name="kardex_fecha" readonly style="background-color: #ffffff" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <div class="radio">
                                    <input type="radio" name="radio_form_medicamento" id="radio_form_medicamento_option_1" checked value="0">
                                    <label class="form-check-label" for="radio_form_medicamento_option_1" style="margin-left: 20px">HIDRATACION</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" name="radio_form_medicamento" id="radio_form_medicamento_option_2" value="1">
                                    <label class="form-check-label" for="radio_form_medicamento_option_2" style="margin-left: 20px">VIA SALINAS</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="panel_hidratacion" class="panel panel-info panel-bordered panel-sm" style="background-color: #F0F0F0">
                    <div class="panel-body">
                        <button
                            type="button" 
                            class="btn btn-success btn-sm" 
                            style="margin-bottom: 10px" 
                            id="btnAddRowTableHidratacion">
                            <i class="fa fa-plus"></i> HIDRATACION
                        </button>
                        <fieldset>
                            <table id="tableHidratacion" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 45%">MEDICAMENTO</th>
                                        <th style="width: 15%">VOL. DE INFUSION (CC)</th>
                                        <th style="width: 15%">VIA DE ADMINISTRACIÓN</th>
                                        <th style="width: 10%">FRASCO</th>
                                        <th style="width: 10%">HORA</th>
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
                <div class="panel panel-info panel-bordered panel-sm" style="background-color: #F0F0F0">
                    <div class="panel-body">
                        <button
                            type="button" 
                            class="btn btn-success btn-sm" 
                            style="margin-bottom: 10px" 
                            id="btnAddRowTableInfusion">
                            <i class="fa fa-plus"></i> INFUSIÓN
                        </button>
                        <fieldset>
                            <table id="tableInfusion" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 45%">MEDICAMENTO</th>
                                        <th style="width: 20%"></th>
                                        <th style="width: 15%">VOL. DE INFUSIÓN</th>
                                        <th style="width: 15%">VIA DE ADMINISTRACIÓN</th>
                                        <th style="width: 10%">HORA</th>
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
                <div class="panel panel-info panel-bordered panel-sm" style="background-color: #F0F0F0">
                    <div class="panel-body">
                        <button
                            type="button" 
                            class="btn btn-success btn-sm" 
                            style="margin-bottom: 10px" 
                            id="btnAddRowTableAntibioticos">
                            <i class="fa fa-plus"></i> ANTIBIOTICOS
                        </button>
                        <fieldset>
                            <table id="tableAntibioticos" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">MEDICAMENTO</th>
                                        <th style="width: 25%">VIA. ADMINISTRACIÓN</th>
                                        <th style="width: 10%">FRECUENCIA (HORAS)</th>
                                        <th style="width: 10%">HORA</th>
                                        <th style="width: 10%">DIAS DE ADMINISTRACIÓN</th>
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
                <div class="panel panel-info panel-bordered panel-sm" style="background-color: #F1F1F1">
                    <div class="panel-body">
                        <button
                            type="button" 
                            class="btn btn-success btn-sm" 
                            style="margin-bottom: 10px" 
                            id="btnAddRowTableOtros">
                            <i class="fa fa-plus"></i> OTROS
                        </button>
                        <fieldset>
                            <table id="tableOtros" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">MEDICAMENTO</th>
                                        <th style="width: 15%">VIA. ADMINISTRACIÓN</th>
                                        <th style="width: 10%"></th>
                                        <th style="width: 10%">FRECUENCIA</th>
                                        <th style="width: 10%">HORA</th>
                                        <th style="width: 10%">DIAS DE ADMINISTRACIÓN</th>
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
                <div class="panel panel-info panel-bordered panel-sm" style="background-color: #F1F1F1">
                    <div class="panel-body">
                        <button
                            type="button" 
                            class="btn btn-success btn-sm" 
                            style="margin-bottom: 10px" 
                            id="btnAddRowTableInhaladores">
                            <i class="fa fa-plus"></i> INHALADORES
                        </button>
                        <fieldset>
                            <table id="tableInhaladores" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">MEDICAMENTO</th>
                                        <th style="width: 15%">VIA. ADMINISTRACIÓN</th>
                                        <th style="width: 10%">TERAPIA DE RESCATE</th>
                                        <th style="width: 10%">FRECUENCIA (HORAS)</th>
                                        <th style="width: 10%">HORA</th>
                                        <th style="width: 10%">DIAS DE ADMINISTRACIÓN</th>
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
                <div class="panel panel-info panel-bordered panel-sm" style="background-color: #F1F1F1">
                    <div class="panel-body">
                        <button
                            type="button" 
                            class="btn btn-success btn-sm" 
                            style="margin-bottom: 10px" 
                            id="btnAddRowTableTranfusiones">
                            <i class="fa fa-plus"></i> TRANSFUSIONES
                        </button>
                        <fieldset>
                            <table id="tableTranfusiones" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 65%">MEDICAMENTO</th>
                                        <th style="width: 15%">PAQUETES</th>
                                        <th style="width: 15%">HORA</th>
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
                <div class="panel panel-info panel-bordered panel-sm" style="background-color: #F1F1F1">
                    <div class="panel-body">
                        <button
                            type="button" 
                            class="btn btn-success btn-sm" 
                            style="margin-bottom: 10px" 
                            id="btnAddRowTableHemoglucotest">
                            <i class="fa fa-plus"></i> HEMOGLUCOTEST
                        </button>
                        <fieldset>
                            <table id="tableHemoglucotest" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 50%">MEDICAMENTO</th>
                                        <th style="width: 15%">FRECUENCIA</th>
                                        <th style="width: 15%">HORA</th>
                                        <th style="width: 15%">DIAS DE ADMINISTRACIÓN</th>
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
            <div class="modal-footer">
                <div class="row" style="margin: 40px 0px">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <button type="button" id="btnCancelFormMedicamento" class="btn btn-danger btn-block">CANCELAR</button>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <button id="btnSaveFormMedicamento" type="button" class="btn btn-success btn-block">GUARDAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>