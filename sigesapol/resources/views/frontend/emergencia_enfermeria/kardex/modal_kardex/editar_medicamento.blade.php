<div class="modal fade" id="modal_kardex_editar_medicamento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important; border-top-left-radius: 5px; border-top-right-radius: 5px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b>REGISTRO DE HORARIO POR MEDICAMENTO </b>
            </div>
            <div class="modal-body"  style="font-size: 11px">
                <form action="" id="form_editar_medicamento">
                    <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm" id="divButtonMedicamento">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <p><b>DNI - PACIENTE :</b></p>
                                        <p id="asegurado"></p>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <p><b>MEDICAMENTO :</b></p>
                                        <p id="medicamento"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info panel-bordered panel-sm" id="divButtonMedicamento">
                            <div class="panel-body">
                                <button
                                    type="button" 
                                    class="btn btn-success btn-sm"
                                    style="margin-bottom: 20px"
                                    id="btnAddRowTableHorarioMedicamento">
                                    <i class="fa fa-plus"></i> HORARIO POR MEDICAMENTO
                                </button>
                                <table id="tableHorarioMedicamento" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">FECHA</th>
                                            <th style="width: 10%">HORA</th>
                                            <th style="width: 15%">APLICABLE / NO APLICABLE</th>
                                            <th style="width: 10%; text-align: center">
                                                <p>REACCION ADVERSA</p>
                                                <p>AL MEDICAMENTO (RAM)</p>
                                            </th>
                                            <th style="width: 10%">FRASCO </th>
                                            <th style="width: 30%">OBSERVACION</th>
                                            <th style="width: 5%">ELIMINAR</th>
                                            <th style="width: 0%; visibility: hidden"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <div class="row" style="margin: 40px 0px">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <button type="button" id="modal_kardex_editar_medicamento_cancel" class="btn btn-danger btn-block" data-dismiss="modal">CANCELAR</button>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <button id="modal_kardex_editar_medicamento_ok" type="button" class="btn btn-success btn-block">GUARDAR</button>
                    </div>
                </div>
            </div>
        </div>
  </div>
</div>